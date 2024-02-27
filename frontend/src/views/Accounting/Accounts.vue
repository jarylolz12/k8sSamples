<template>
	<v-sheet outlined class="accounting-customers-wrapper">
		<v-data-table
			:headers="tableHeader"
			:items="tableData"
			:single-select="false"
			show-select
			v-model="selectedAccounts"
			:loading="isAccountsDataLoading"
			disable-pagination
			:hide-default-footer="true"
			fixed-header
			:class="tableData.length === 0 ? 'no-data__table' : ''"
		>
			<template v-slot:top>
				<div class="d-block d-sm-flex d-md-flex d-lg-flex pa-4 pt-3 pb-3">
					<div class="mr-auto">
						<h3 class="pt-2">Accounts</h3>
					</div>
					<div class="d-sm-flex d-inline">
						<Search placeholder="Search Accounts" :inputData.sync="searchText" />
						<div class="ml-sm-2 ml-0 mt-2 mt-sm-0">
							<v-btn
								class="form-border text-capitalize"
								color="btn-blue"
								@click="onToggleAccountForm"
								height="40"
								block
							>
								Add Account
							</v-btn>
						</div>
					</div>
				</div>
				<v-divider />
			</template>
			
			<template v-slot:[`item.enabled`]="{ item }">
				<!-- <v-btn small rounded outlined :color="item.enabled ? 'success' : 'error'" class="pa-1"
					:loading="selectedAccountSyncingIds.includes(item.id)"
					@click="onChangeEnabledValue(item)">
					<div v-if="item.enabled" class="d-flex align-center justify-space-around" style="min-width: 60px">
						{{ 'yes' }}
						<v-icon>mdi-check-circle</v-icon>
					</div>
					<div v-else class="d-flex align-center justify-space-around" style="min-width: 60px">
						<v-icon>mdi-minus-circle</v-icon>
						{{ 'no' }}
					</div>
				</v-btn> -->

				<div class="item-enabled-wrapper">
					<span v-if="item.enabled" class="item-active-text">Active</span>
					<span v-else class="item-inactive-text">Inactive</span>
				</div>
			</template>

			<template v-slot:[`item.email`]="{ item }">
				<v-tooltip top>
					<template v-slot:activator="{ on, attrs }">
						<h6 v-bind="attrs" v-on="on" v-text="item.email" />
					</template>
					<span>{{ item.phone }}</span>
				</v-tooltip>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<v-menu offset-y bottom left content-class="outbound-lists-menu">
					<template v-slot:activator="{ on, attrs }">
						<v-btn v-bind="attrs" primary v-on="on" class="btn-more-items btn-white" text outlined small>
							<v-icon>mdi-dots-horizontal</v-icon>
						</v-btn>
					</template>
					<v-list>
						<v-list-item link @click="onSelectEditAccountData(item)">
							<v-list-item-title>Edit</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectAccountInformation(item)">
							<v-list-item-title>View</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectAccountToDelete(item)">
							<v-list-item-title class="red--text">Delete</v-list-item-title>
						</v-list-item>
					</v-list>
				</v-menu>
			</template>

			<template v-slot:loading>
				<div class="mt-5" v-if="isAccountsDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="mt-5" v-if="isAccountsDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>

				<v-alert class="mt-2" v-if="!isAccountsDataLoading && tableData.length === 0 && searchText === ''">
					<!-- <img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="" class="mb-3"> -->
					<p class="subtitle-2 no-data-title">Add Account</p>
					<span class="subtitle-p-data">You have not added any account yet.</span>
					<br />
					<v-btn
						outlined
						text
						small
						@click="onToggleAccountForm"
						color="#0889a0"
						class="py-4 text-capitalize btn-white"
					>
						<v-icon size="18">mdi-plus</v-icon>
						{{ 'Add Account' }}
					</v-btn>
				</v-alert>

				<v-alert class="mt-2" v-if="!isAccountsDataLoading && tableData.length === 0 && searchText !== ''">
					<!-- <img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="" class="mb-3"> -->
					<p class="subtitle-2 no-data-title">No Accounts</p>
					<span class="subtitle-p-data">Sorry! We could not find any accounts that matches your search term.</span>
				</v-alert>
			</template>
		</v-data-table>

		<v-divider />
		
		<pagination
			:total="pagination.last_page || 1"
			:count="pagination.total || 0"
			:current-page="currentPage"
			:total-visible="10"
			:pageLimit.sync="pageLimit"
			@pageSelected="onPaginationClick"
			@changeLimit="onChangePageLimit"
			:from="pagination ? pagination.from : 1"
			:to="pagination ? pagination.to : 1"
		/>
		<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar> -->
		<account-form
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedAccountData"
			@toggle="onToggleAccountForm"
		></account-form>
		<account-delete
			:open="showDeleteModal"
			:form-data="selectedDataToDelete"
			@toggle="onToggleDeleteAccountForm"
		></account-delete>

		<v-dialog
			v-model="showInfoModal"
			max-width="980"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card>
				<v-card-title class="pa-0">
					<span class="headline">{{ selectedAccountInformation.name }}</span>
					<v-spacer></v-spacer>

					<v-btn
						class="d-none d-sm-flex btn-blue mr-1"
						color="primary"
						@click="onSelectEditAccountData(selectedAccountInformation)"
					>
						{{ 'Edit' }}
					</v-btn>
					<v-btn
						text
						outlined
						class="ml-1 mr-4 d-none d-sm-flex btn-white" 
						@click="onSelectAccountToDelete(selectedAccountInformation)"
					>
						{{ 'delete' }}
					</v-btn>
					<v-btn icon class="d-none d-sm-flex" @click="onToggleAccountInformationModal">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text class="py-4 pb-8">
					<v-sheet class="pa-2">
						<v-row>
							<v-col sm="4" ols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Name' }}</h6>
								<span class="subtitle-data">{{ selectedAccountInformation.name }}</span>
							</v-col>
							<v-col sm="4" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Number' }}</h6>
								<span class="subtitle-data">{{ selectedAccountInformation.number }}</span>
							</v-col>
							<v-col sm="4" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Currency' }}</h6>
								<span class="subtitle-data">{{ currencyObj.name }}</span>
							</v-col>
							<v-col sm="4" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Opening Balance' }}</h6>
								<span class="subtitle-data">{{ selectedAccountInformation.opening_balance_formatted }}</span> </v-col
							><v-col sm="4" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Current Balance' }}</h6>
								<span class="subtitle-data">{{ selectedAccountInformation.current_balance_formatted }}</span>
							</v-col>
							<v-col sm="4" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Bank Name' }}</h6>
								<span class="subtitle-data">{{ selectedAccountInformation.bank_name }}</span>
							</v-col>
							<v-col sm="4" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Bank Phone' }}</h6>
								<span class="subtitle-data">{{ selectedAccountInformation.bank_phone }}</span>
							</v-col>
							<v-col sm="4" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Bank Address' }}</h6>
								<span class="subtitle-data">{{ selectedAccountInformation.bank_address }}</span>
							</v-col>
							<v-col sm="4" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Enabled' }}</h6>
								<span class="subtitle-data">{{ selectedAccountInformation.enabled ? 'yes' : 'no' }}</span>
							</v-col>
						</v-row>
					</v-sheet>

					<v-expansion-panels class="account-expansion-panels mt-6" flat>
						<v-expansion-panel>
							<v-expansion-panel-header>
								<span class="labelcolor--text" style="color: #4a4a4a !important;">{{ 'Transactions' }}</span>
							</v-expansion-panel-header>
							
							<v-expansion-panel-content>
								<v-data-table
									:headers="transactionTableHeader"
									:items="transactionLists"
									:loading="isFetchingTransactions"
									hide-default-footer
									disable-pagination
								>
									<template v-slot:[`item.paid_at`]="{ item }">
										<span>{{ moment(item.paid_at, 'YYYY-MM-DD').format('DD MMM YYYY') }}</span>
									</template>
									<template v-slot:[`item.vendor`]="{ item }">
										{{ item.contact.name }}
									</template>
								</v-data-table>
							</v-expansion-panel-content>
						</v-expansion-panel>
					</v-expansion-panels>
				</v-card-text>
				<v-divider class="d-flex d-sm-none" />
				<v-card-actions class="d-flex d-sm-none">
					<v-btn color="primary" class="btn-blue" @click="onSelectEditAccountData(selectedAccountInformation)">
						{{ 'Edit' }}
					</v-btn>
					<v-btn text outlined class="ml-1 mr-4 btn-white" @click="onSelectAccountToDelete(selectedAccountInformation)">
						{{ 'delete' }}
					</v-btn>
					<v-spacer />
					<v-btn @click="onToggleAccountInformationModal" elevation="0">
						close
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-sheet>
</template>

<script>
import moment from 'moment';
import { mapActions } from 'vuex';
import AccountForm from '../../components/Accounting/AccountForm.vue';
import AccountDelete from '../../components/Accounting/AccountDeleteModal.vue';
import Pagination from '../../components/Accounting/Pagination.vue';
import Search from '../../components/Accounting/SearchInput.vue';
import { apiErrorMessage, debounce } from '../../utils/globalMethods';
import AkauntingService from '../../services/akaunting.service';
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'Accounts',
	components: {
		AccountForm,
		AccountDelete,
		Pagination,
		Search
	},

	data() {
		return {
			showInfoModal: false,
			isAccountTogglingProgress: false,
			isAccountsDataLoading: false,
			accountLists: [],
			page: 1,
			showDeleteModal: false,
			selectedAccountData: {},
			selectedAccountInformation: {},
			selectedDataToDelete: {},
			isEditMode: false,
			searchText: '',
			selectedAccounts: [],
			showFormDialog: false,
			showSnackbar: false,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			},
			currentPage: 1,
			// pageLimit: 25,
			pageLimit: 35,
			currencyList: [],
			selectedAccountSyncingIds: [],
			accountTransactions: {
				data: [],
				meta: {}
			},
			isFetchingTransactions: false
		};
	},

	created() {
		this.fetchCurrencies();
		this.fetchAccountsData();
	},

	watch: {
		searchText: debounce(function() {
			this.fetchAccountsData();
		}, 300)
	},

	computed: {
		// ...mapState('accounting', ['accountLists']),
		tableData: {
			get() {
				return this.accountLists?.data || [];
			},
			set(data) {
				return data;
			}
		},

		tableHeader() {
			return [
				{
					text: 'Name',
					value: 'name',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '20%'
				},
				{
					text: 'Number',
					value: 'number',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '15%'
				},
				{
					text: 'Currency',
					value: 'currency_code',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '15%'
				},
				{
					text: 'Balance',
					value: 'current_balance_formatted',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'end',
					width: '15%'
				},
				{
					text: 'Status',
					value: 'enabled',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'center',
					width: '20%',
					sortable: false
				},
				{
					text: '',
					value: 'actions',
					sortable: false,
					class: 'text-uppercase th--text font-weight-bold',
					align: 'end',
					width: '12%'
				}
			];
		},

		transactionTableHeader() {
			return [
				{
					text: 'date',
					value: 'paid_at',
					class: 'text-uppercase th--text font-weight-bold'
				},
				{
					text: 'amount',
					value: 'amount_formatted',
					class: 'text-uppercase th--text font-weight-bold'
				},
				{
					text: 'vendor',
					value: 'vendor',
					class: 'text-uppercase th--text font-weight-bold'
				}
			];
		},

		pagination: {
			get() {
				return this.accountLists.data ? this.accountLists.meta : {};
			}
		},

		currencyValue: {
			get() {
				return this.selectedAccountInformation?.currency_values
					? JSON.parse(this.selectedAccountInformation.currency_values).name
					: '';
			}
		},

		currencyObj: {
			get() {
				if (this.selectedAccountInformation.currency_code) {
					const selectedCurrency = this.selectedAccountInformation.currency_code;
					return this.currencyList.find((currency) => currency.code === selectedCurrency);
				}
				return '';
			}
		},

		transactionLists() {
			return this.accountTransactions?.data || [];
		},

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		}
	},

	methods: {
		...mapActions('accounting', ['getAccountsData']),
		moment,
		...globalMethods,
		async fetchCurrencies() {
			try {
				const response = await AkauntingService.getCurrencies();
				this.currencyList = response?.data?.data?.data || [];
			} catch (error) {
				//
			}
		},

		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchAccountsData();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchAccountsData();
		},

		async fetchAccountsData() {
			if (this.isAccountsDataLoading) {
				return;
			}
			this.isAccountsDataLoading = true;
			try {
				const data = await this.getAccountsData({
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || ''
				});
				this.accountLists = data;
				this.isAccountsDataLoading = false;
			} catch (error) {
				this.isAccountsDataLoading = false;
				apiErrorMessage(error);
			}
		},

		onToggleAccountForm(options = {}) {
			this.isEditMode = false;
			this.selectedServiceData = {};
			this.showFormDialog = !this.showFormDialog;
			if (options.created || options.updated) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'success',
				// 	message: options.message
				// };
				this.notificationMessage(options.message)
				this.showSnackbar = true;
				this.showInfoModal = false;
				this.fetchAccountsData();
			}
		},

		onSelectEditAccountData(data = {}) {
			this.selectedAccountData = JSON.parse(JSON.stringify(data));
			this.isEditMode = true;
			this.showFormDialog = true;
		},

		onSelectAccountToDelete(data = {}) {
			this.selectedDataToDelete = JSON.parse(JSON.stringify(data));
			this.onToggleDeleteAccountForm();
		},

		onToggleDeleteAccountForm(options = {}) {
			this.showDeleteModal = !this.showDeleteModal;

			if (options.deleted) {
				// this.snackbarOption = {
				// 	icon: 'mdi-delete',
				// 	color: 'red',
				// 	message: options.message || 'Deleted.'
				// };

				const message = options.message || 'Account has been successfully deleted.'
				this.notificationCustom(message)
				this.showSnackbar = true;
				this.showInfoModal = false;

				this.fetchAccountsData();
			}
		},

		onSelectAccountInformation(data) {
			this.selectedAccountInformation = data;
			this.onToggleAccountInformationModal();
		},

		onToggleAccountInformationModal() {
			this.showInfoModal = !this.showInfoModal;
			if (!this.showInfoModal) {
				this.selectedAccountInformation = {};
			} else {
				this.getAccountTransactions();
			}
		},

		async getAccountTransactions() {
			if (this.isFetchingTransactions) {
				return;
			}
			this.isFetchingTransactions = true;
			try {
				const id = this.selectedAccountInformation.id;
				const response = await AkauntingService.getAccountTransactions(id);
				this.accountTransactions = response.data?.data || [];
				this.isFetchingTransactions = false;
			} catch (error) {
				this.isFetchingTransactions = false;
				apiErrorMessage(error);
			}
		},

		async onChangeEnabledValue(data) {
			if (this.selectedAccountSyncingIds.includes(data.id)) {
				return;
			}
			this.selectedAccountSyncingIds.push(data.id);
			try {
				const isEnabled = !data.enabled;

				const response = isEnabled
					? await AkauntingService.enableAccount(data.id)
					: await AkauntingService.disableAccount(data.id);

				if (response.status === 200) {
					this.tableData = this.tableData.map((record) => {
						if (record.id === data.id) {
							data.enabled = isEnabled;
						}
						return data;
					});
				}
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.selectedAccountSyncingIds = this.selectedAccountSyncingIds.filter((id) => id !== data.id);
			}
		}
	}
};
</script>

<style lang="scss">
@import './scss/customers.scss';

.v-expansion-panels {
	&.account-expansion-panels {
		.v-expansion-panel {
			border: 1px solid #B4CFE0;

			.v-expansion-panel-header {
				padding: 16px;
			}
		
			.v-expansion-panel-content {
				.v-expansion-panel-content__wrap {
					padding: 0 !important;

					.v-data-table {
						padding-bottom: 20px;
						
						.v-data-table__wrapper {
							height: unset !important;

							table {
								thead {
									tr {
										th {
											box-shadow: none;
											border-bottom: 2px solid #EBF2F5 !important;
											padding: 8px 16px;
										}
									}
								}

								tbody {
									tr {
										td {
											padding: 8px 16px;
											border-bottom: 1px solid #EBF2F5 !important;
										}
									}
								}
							}
						}
					}
				}
			}
		}	
	}	
}
</style>

<style lang="scss" scoped>
	@media screen and (max-width: 600px) {
		::v-deep .v-data-table > .v-data-table__wrapper {
			height: calc(100vh - 268px) !important;
			overflow-y: auto;
			overflow-x: hidden;
		}
	}
	::v-deep .v-data-table > .v-data-table__wrapper {
		height: calc(100vh - 180px);
		overflow-y: auto;
		overflow-x: hidden;
	}
	.subtitle-1 {
		font-size: 10px !important;
		color: #819fb2 !important;
		font-family: "Inter-SemiBold", sans-serif !important;
		text-transform: uppercase;
		margin-bottom: 5px;
		letter-spacing: 0 !important;
		line-height: 18px;
	}

	.subtitle-data {
		color: #4a4a4a;
	}
	.v-list-item {
		min-height: 36px;
	}
	::v-deep .select-page-limit {
		width: 80px;
	}
</style>
