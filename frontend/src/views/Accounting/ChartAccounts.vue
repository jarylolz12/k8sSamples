<template>
	<v-sheet color="transparent">
		<h3 class="font-20 mb-3 ml-2 mt-3 ml-sm-0 mt-sm-0">Chart of Accounts</h3>

		<v-sheet color="transparent">
			<v-sheet class="d-flex align-center justify-end px-4" height="80" outlined style="border-radius: 4px; border-color: #EBF2F5;">
				<Search :inputData.sync="searchText" placeholder="Search accounts" />
				<v-btn color="btn-blue" class="ml-2 text-capitalize" height="40" @click="onToggleChartAccountForm">
					{{ 'Add Account' }}
				</v-btn>
			</v-sheet>

			<v-sheet v-for="group in tableGroup" :key="group" class="mt-4" flat outlined 
				style="border-color: #EBF2F5; border-radius: 4px;">

				<v-data-table
					:headers="tableHeaders"
					:loading="isFetchingData"
					:items="chartAccountsData[group]"
					:search="searchText"
					class="chart-of-accounts-table"
				>
					<template v-slot:top>
						<v-toolbar flat style="border-bottom: 2px solid #EBF2F5;">
							<v-toolbar-title class="text-capitalize">
								{{ group }}
							</v-toolbar-title>
						</v-toolbar>
					</template>

					<template v-slot:item="{ item }" v-if="!$vuetify.breakpoint.mobile">
						<tr :key="`acct-${item.id}`">
							<td>
								<a
									href="#"
									v-if="Array.isArray(item.sub_accounts) && item.sub_accounts.length"
									@click.prevent="item.showSubAccount = !item.showSubAccount"
									class="text-decoration-none"
								>
									{{ item.code }}
									<v-icon>mdi-{{ !item.showSubAccount ? 'chevron-right' : 'chevron-down' }}</v-icon>
								</a>
								<span v-else>{{ item.code }}</span>
							</td>
							<td>{{ item.trans_name }}</td>
							<td>{{ item.account_type }}</td>
							<td>
								<span :class="{ 'red--text': item.balance < 0, 'blue--text': item.balance > 0 }">{{
									currencyFormat(item.balance)
								}}</span>
							</td>
							<td>
								<div class="d-flex">
									<button class="btn-more-items btn-white mr-2" style="min-width: 35px;" 
										@click="onSelectToEdit(item)">
										<v-icon size="18" color="#0171A1">mdi-pencil</v-icon>
									</button>

									<button class="btn-more-items btn-white" style="min-width: 35px;" 
										@click="onSelectToDelete(item)">
										<!-- <v-icon size="18" color="red">mdi-delete</v-icon> -->
										<img src="@/assets/icons/delete-po.svg" class="" width="16px" height="16px">
									</button>
								</div>
							</td>
						</tr>
						<template v-if="item.showSubAccount">
							<tr v-for="sub_account in item.sub_accounts" :key="`sub-${sub_account.id}`">
								<td class="sub-account-code">
									<v-icon>mdi-subdirectory-arrow-right</v-icon> {{ sub_account.code }}
								</td>
								<td colspan="2">{{ sub_account.trans_name }}</td>
								<td></td>
								<td>
									<div class="d-flex">
										<button class="btn-more-items btn-white mr-2" style="min-width: 35px;" 
											@click="onSelectToEdit(item)">
											<v-icon size="18" color="#0171A1">mdi-pencil</v-icon>
										</button>

										<button class="btn-more-items btn-white" style="min-width: 35px;" 
											@click="onSelectToDelete(item)">
											<!-- <v-icon size="18" color="red">mdi-delete</v-icon> -->
											<img src="@/assets/icons/delete-po.svg" class="" width="16px" height="16px">
										</button>
									</div>
								</td>
							</tr>
						</template>
					</template>
					<template v-slot:[`item.balance`]="{ item }">
						<span :class="{ 'red--text': item.balance < 0, 'blue--text': item.balance > 0 }">{{
							currencyFormat(item.balance)
						}}</span>
					</template>
					<template v-slot:[`item.actions`]="{ item }">
						<div class="d-flex">
							<button class="btn-more-items btn-white mr-2" style="min-width: 35px;" 
								@click="onSelectToEdit(item)">
								<v-icon size="18" color="#0171A1">mdi-pencil</v-icon>
							</button>

							<button class="btn-more-items btn-white" style="min-width: 35px;" 
								@click="onSelectToDelete(item)">
								<!-- <v-icon size="18" color="red">mdi-delete</v-icon> -->
								<img src="@/assets/icons/delete-po.svg" class="" width="16px" height="16px">
							</button>
						</div>
					</template>
				</v-data-table>
			</v-sheet>
			<chart-form
				:open="showChartAccountForm"
				@toggle="onToggleChartAccountForm"
				@saved="chartAccountSaved"
				:form-values="selectedDataToEdit"
				:is-edit-mode="isEditMode"
			/>
			<chart-delete :open="showChartAccountDelete" :form-data="selectedDataToDelete" @toggle="onToggleDelete" />
			<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar> -->
		</v-sheet>
	</v-sheet>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import ChartForm from '../../components/Accounting/ChartAccountsForm.vue';
import ChartDelete from '../../components/Accounting/ChartAccountDeleteModal.vue';
import Search from '../../components/Accounting/SearchInput.vue';
import { apiErrorMessage } from '../../utils/globalMethods';
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'ChartOfAccounts',
	components: {
		ChartForm,
		ChartDelete,
		Search
	},
	data() {
		return {
			isFetchingData: false,
			chartAccountsData: [],
			showChartAccountForm: false,
			showChartAccountDelete: false,
			isFetchingAccountTypes: false,
			searchText: '',
			accountTypes: {},
			tableGroup: ['assets', 'liabilities', 'expenses', 'income', 'equity'],
			selectedDataToDelete: null,
			selectedDataToEdit: null,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			},
			showSnackbar: false,
			isEditMode: false
		};
	},

	mounted() {
		this.fetchChartAccounts();
	},

	computed: {
		...mapGetters('accounting', ['isQBOEnabled', 'homeCurrency', 'getDEtypes']),
		tableHeaders() {
			return [
				{
					text: 'Code',
					value: 'code',
					class: 'text-uppercase th--text font-weight-bold',
					width: '15%'
				},
				{
					text: 'Name',
					value: 'trans_name',
					class: 'text-uppercase th--text font-weight-bold'
				},
				{
					text: 'Type',
					value: 'account_type',
					class: 'text-uppercase th--text font-weight-bold'
				},
				{
					text: 'Balance',
					value: 'balance',
					class: 'text-uppercase th--text font-weight-bold'
				},
				{
					value: 'actions',
					width: 80
				}
			];
		}
	},

	/* watch: {
searchText: debounce(function() {
	this.fetchChartAccounts();
}, 300)
}, */

	methods: {
		...mapActions('accounting', ['getChartAccounts']),
		...globalMethods,
		currencyFormat(value) {
			return new Intl.NumberFormat('en-US', { style: 'currency', currency: this.homeCurrency }).format(value);
		},

		onToggleChartAccountForm() {
			this.showChartAccountForm = !this.showChartAccountForm;
			this.isEditMode = false;
			this.selectedDataToEdit = null;
		},

		async fetchChartAccounts() {
			if (this.isFetchingData) {
				return;
			}
			this.isFetchingData = true;
			try {
				const { data } = await this.getChartAccounts({
					search: this.searchText || ''
				});

				const chartData = {};
				data.forEach(function(record){
					const deAccount = record.accounts;
					deAccount.forEach(function(account){
						const accountType = account.declass.name.split('.').pop(); //error here
						if (typeof chartData[accountType] === 'undefined') {
							chartData[accountType] = [];
						}
						chartData[accountType].push({
							...account,
							account_type: this.getDEtypes[account.type.name] || '',
							showSubAccount: false,
							balance: account.debit_total - account.credit_total
						});
					},this);
					
				},this);

				this.chartAccountsData = chartData;
				this.isFetchingData = false;
			} catch (error) {
				console.log(error);
				apiErrorMessage(error);
				this.isFetchingData = false;
			}
		},

		onSelectToEdit(data) {
			this.selectedDataToEdit = Object.assign({}, data);
			this.showChartAccountForm = true;
			this.isEditMode = true;
		},

		onToggleEdit() {},

		onSelectToDelete(data) {
			this.selectedDataToDelete = Object.assign({}, data);
			this.showChartAccountDelete = true;
		},

		onToggleDelete(options) {
			this.showChartAccountDelete = false;
			if (options && options.deleted) {
				this.selectedDataToDelete = null;
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'red',
				// 	message: options.message || 'Deleted.'
				// };

				const message = options.message || 'Account has been successfully deleted.'
				this.notificationCustom(message)
				this.showSnackbar = true;
				this.fetchChartAccounts();
			}
		},

		chartAccountSaved(options) {
			this.showChartAccountForm = false;
			// this.snackbarOption = {
			// 	icon: 'mdi-check',
			// 	color: 'success',
			// 	message: options.message || 'Saved.'
			// };

			const message = options.message || 'Account has been saved.'
			this.notificationMessage(message)
			this.showSnackbar = true;
			this.fetchChartAccounts();
		}
	}
};
</script>

<style lang="scss">
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';
</style>

<style lang="scss" scoped>
	h3 {
		font-size: 24px !important;
		margin-bottom: 0;
		color: #4a4a4a;
	}

	.sub-account-code {
		text-indent: 32px;
	}

	.v-toolbar {
		.v-toolbar__content {
			.v-toolbar__title {
				font-size: 20px !important;
				letter-spacing: 0;
				font-family: "Inter-Medium", sans-serif;
				color: #4a4a4a;
			}
		}
	}
</style>
