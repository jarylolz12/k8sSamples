<template>
	<v-sheet outlined class="accounting-statements-wrapper">
		<v-data-table
			v-model="selectedItems"
			class="v-table-middle-align"
			v-bind:class="{
                'no-data__table' : (tableData.length === 0),
                'no-current__pagination' : (pagination.last_page <= 1),
            }"
			fixed-header
			show-select
			disable-pagination
			:headers="tableHeader"
			:items="tableData"
			:single-select="false"
			:loading="isLoading"
			:hide-default-footer="true"
		>
			<template v-slot:top>
				<div class="d-block d-sm-flex d-md-flex d-lg-flex pa-4 py-3">
					<div class="mr-auto">
						<v-row>	
							<v-col cols="12" md="6">
								<v-select
									:items="filter_types" 
							        v-model="filter_type" 
							        label="Filter Type"
									class="form-label font14" 
									solo
									flat
									outlined
									dense
									hide-details
									clearable
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								>
								</v-select>
							</v-col>
							<v-col cols="12" md="6">
								<v-select
									:items="filter_statuses" 
									v-model="filter_status" 
									label="Filter Status"
									class="form-label font14" 
									solo
									flat
									outlined
									dense
									hide-details
									clearable
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								>
								</v-select>
							</v-col>
						</v-row>
					</div>
					<div class="d-sm-flex d-inline">
						<v-row>
							<v-col cols="12" md="6">
								<Search :inputData.sync="searchText" placeholder="Search statement" />
							</v-col>
						</v-row>
					</div>
				</div>
				<v-divider />
			</template>
			<template v-slot:[`item.actions`]="{ item }">
				<v-menu offset-y bottom left content-class="outbound-lists-menu">
					<template v-slot:activator="{ on, attrs }">
						<v-btn v-bind="attrs" primary v-on="on" class="btn-more-items btn-white" color="primary" text outlined small>
							<v-icon>mdi-dots-horizontal</v-icon>
						</v-btn>
					</template>
					<v-list>
						<v-list-item link @click="onSelectEditStatementData(item)">
							<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectStatementInformation(item)">
							<v-list-item-title>{{ 'View' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectStatementToDelete(item)">
							<v-list-item-title class="red--text">{{ 'Delete' }}</v-list-item-title>
						</v-list-item>
					</v-list>
				</v-menu>
			</template>
			<template v-slot:no-data>
				<div class="mt-5" v-if="isLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>

				<v-alert class="mt-2" v-if="!isLoading && tableData.length === 0 && searchText === ''">
					<img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">Add Statement</p>
					<span class="subtitle-p-data">You have not added any statements yet.</span>
					<br />
					<v-btn
						outlined
						text
						small
						@click="onToggleStatementForm"
						color="#0889a0"
						class="py-4 text-capitalize btn-white"
					>
						<v-icon size="18">mdi-plus</v-icon>
						{{ 'Add Statement' }}
					</v-btn>
				</v-alert>

				<v-alert class="mt-2" v-if="!isLoading && tableData.length === 0 && searchText !== ''">
					<img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">No Statements</p>
					<span class="subtitle-p-data">Sorry! We could not find any statements that matches your search term.</span>
				</v-alert>
			</template>
			<template v-slot:loading>
				<div class="mt-5" v-if="isLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>
			</template>
			<template v-slot:[`item.created_at`]="{ item }">
				<span>{{ item.created_at }}</span>
			</template>
			<template v-slot:[`item.amount_due`]="{ item }">
				<a href="#" style="text-decoration: none;" @click.prevent="$router.push(`/accounting/customers/${item.id}`)">
					<span>{{ item.amount_due.toLocaleString('en-US', { style: 'currency', currency: 'USD' }) }}</span>
				</a>
			</template>

			<template v-slot:[`item.status`]="{ item }">
				<div class="item-enabled-wrapper">
					<span v-if="item.status == 'sent'" class="item-active-text">Sent</span>
					<span v-else class="item-inactive-text">{{ item.status }}</span>
				</div>
			</template>
			<template v-slot:[`item.invoices`]="{ item }">
				<span>{{ item.invoices }}</span>
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
		<v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar>
		<statement-form 
			ref="statementForm"
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedStatementData"
			@toggle="onToggleStatementForm"
		></statement-form>
		<statement-delete
			:open="showDeleteModal"
			:form-data="selectedStatementToDelete"
			@toggle="onToggleDeleteStatementForm" 
		></statement-delete>
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
					<span class="headline">{{ selectedStatementInformation.statement_id }}</span>
					<v-spacer></v-spacer>
					<v-btn
						class="d-none d-sm-flex btn-blue mr-1"
						color="btn-blue"
						@click="onSelectEditStatementData(selectedStatementInformation)"
					>
						{{ 'Edit' }}
					</v-btn>
					<v-btn
						text
						outlined
						class="ml-1 mr-4 d-none d-sm-flex btn-white"
						@click="onSelectStatementToDelete(selectedCustomerInformation)"
					>
						{{ 'Delete' }}
					</v-btn>
					<v-btn icon class="d-none d-sm-flex" @click="onToggleStatementInformationModal">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text class="py-4 pb-8">
					<v-row>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Start Date' }}</h6>
							<span class="subtitle-data">{{ formatDateTime(selectedStatementInformation.start_date,'YYYY-MM-DD') }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'End Date' }}</h6>
							<span class="subtitle-data">{{ formatDateTime(selectedStatementInformation.end_date,'YYYY-MM-DD') }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Status' }}</h6>
							<span class="subtitle-data">{{ selectedStatementInformation.status }}</span>
						</v-col>
					</v-row>
					<v-row>
						<v-col cols="12" md="12" class="pb-0">
							<div style="height:1px;background:#EDEDED"></div>
						</v-col>
					</v-row>
					<v-row>
						<v-col cols="12" sm="12" md="3">
							<div>
								<p class="text-uppercase font-medium font-18 pt-2 text-center">{{ moneyFormat(selectedStatementInformation.opening_balance) }}</p>
								<p class="labelcolor--text text-uppercase font-medium font-18 text-center">Opening Balance</p>
							</div>
						</v-col>
						<v-col cols="12" sm="12" md="1">
							<div>
								<p class="text-uppercase font-bold font-18 pt-2 text-center">+</p>
								<p class="labelcolor--text text-uppercase font-medium font-18 text-center"> </p>
							</div>
						</v-col>
						<v-col cols="12" sm="12" md="2">
							<div>
								<p class="text-uppercase font-medium font-18 pt-2 text-center">
									{{ moneyFormat(selectedStatementInformation.amount) }}
								</p>
								<p class="labelcolor--text text-uppercase font-medium font-18 text-center">Invoice Amount</p>
							</div>
						</v-col>
						<v-col cols="12" sm="12" md="1">
							<div>
								<p class="text-uppercase font-bold font-18 pt-2 text-center">-</p>
								<p class="labelcolor--text text-uppercase font-medium font-18 text-center"> </p>
							</div>
						</v-col>
						<v-col cols="12" sm="12" md="2">
							<div>
								<p class="text-uppercase font-medium font-18 pt-2 text-center">
									{{ moneyFormat(selectedStatementInformation.total_paid_amount) }}
								</p>
								<p class="labelcolor--text text-uppercase font-medium font-18 text-center">Amount Paid</p>
							</div>
						</v-col>
						<v-col cols="12" sm="12" md="1">
							<div>
								<p class="text-uppercase font-bold font-18 pt-2 text-center">=</p>
								<p class="labelcolor--text text-uppercase font-medium font-18"> </p>
							</div>
						</v-col>
						<v-col cols="12" sm="12" md="2">
							<div>
								<p class="text-uppercase font-bold font-18 pt-2 text-center">
									{{ moneyFormat(selectedStatementInformation.closing_balance) }}
								</p>
								<p class="labelcolor--text text-uppercase font-bold font-18 text-center">Closing Balance</p>
							</div>
						</v-col>
					</v-row>
					<v-row>
						<v-col md="12" lg="12" cols="12">
							<table class="custom-table">
								<thead>
									<tr>
										<th>Date</th>
			                            <th>Document</th>
			                            <th>Value</th>
			                            <th>Paid</th>
			                            <th>Balance</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(item,index) in selectedStatementInformation.documents" :key="index">
										<td>{{ formatDateTime(item.issued_at,'YYYY-MM-DD') }}</td>
										<td>Invoice:{{ item.document_number }}</td>
										<td style="text-align:right">{{ moneyFormat(item.amount) }}</td>
										<td style="text-align:right">{{ moneyFormat(item.paid_amount) }}</td>
										<td style="text-align:right">{{ moneyFormat(item.open_balance) }}</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2"><p><b>Total:</b> </p></td>
										<td style="border-left:none!important;border-right:none!important;text-align:right">
											<p>
												<b>{{ moneyFormat(selectedStatementInformation.amount) }}</b> 
											</p>
										</td>
										<td style="border-left:none!important;border-right:none!important;text-align:right">
											<p>
												<b>{{ moneyFormat(selectedStatementInformation.total_paid_amount) }}</b> 
											</p>
										</td>
										<td style="text-align:right;">
											<p>
												<b> {{ moneyFormat(selectedStatementInformation.amount_due) }}</b>
											</p>
										</td>
									</tr>
								</tfoot>
							</table>
						</v-col>
					</v-row>
				</v-card-text>
				<v-divider class="d-flex d-sm-none" />
				<v-card-actions class="d-flex d-sm-none">
					<v-btn color="btn-blue" class="btn-blue" @click="onSelectEditStatementData(selectedStatementInformation)">
						{{ 'Edit' }}
					</v-btn>
					<v-spacer />
					<v-btn @click="onToggleStatementInformationModal" elevation="0">
						Close
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-sheet>
</template>

<script>
import { mapActions } from 'vuex';
import StatementForm from '../../components/Accounting/StatementForm.vue';
import Pagination from '../../components/Accounting/Pagination.vue';
import Search from '../../components/Accounting/SearchInput.vue';
import { apiErrorMessage, debounce } from '../../utils/globalMethods';
import moment from 'moment';
import StatementDelete from '../../components/Accounting/StatementDeleteModal.vue';
import globalMethods from '../../utils/globalMethods'

export default {
	name: 'CustomerStatementTable',
	components: {
		StatementForm,
		StatementDelete,
		Search,
		Pagination
	},
	data() {
		return {
			contact: {},
			filter_types: ['all','balance forward','open item','Transaction statement'],
			filter_type: 'open item',
			filter_statuses: ['all','unsent', 'sent'],
			filter_status: 'all',
			showInfoModal: false,
			isCustomerTogglingProgress: false,
			isLoading: false,
			statementLists: [],
			page: 1,
			showDeleteModal: false,
			selectedStatementData: {},
			selectedStatementInformation: {},
			selectedStatementToDelete: {},
			isEditMode: false,
			searchText: '',
			selectedItems: [],
			showFormDialog: false,
			showSnackbar: false,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			},
			currentPage: 1,
			pageLimit: 25
		};
	},
	watch: {
		filter_type(){
			this.fetchStatementsData();
		},
		filter_status(){
			this.fetchStatementsData();
		},
		searchText: debounce(function() {
			this.fetchStatementsData();
		}, 300),
		contact(v){
			this.$refs.statementForm.$data.formData.customer_data = v;
			this.$refs.statementForm.$data.formData.customer = v;
		},
		showFormDialog(){
			this.$refs.statementForm.$data.formData.customer_data = this.contact;
			this.$refs.statementForm.$data.formData.customer = this.contact;
		}
	},

	computed: {
		tableData: {
			get() {
				return this.statementLists || [];
			}
		},
		tableHeader() {
			return [
				{
					text: 'Date',
					value: 'created_at',
					class: 'text-uppercase th--text font-weight-bold',
					width: '10%',
					align: 'start'
				},
				{
					text: 'Number',
					value: 'statement_id',
					class: 'text-uppercase th--text font-weight-bold',
					width: '20%',
					align: 'start'
				},
				{
					text: 'Start Date',
					value: 'start_date',
					class: 'text-uppercase th--text font-weight-bold',
					width: '10%',
					align: 'start'
				},
				{
					text: 'End Date',
					value: 'end_date',
					class: 'text-uppercase th--text font-weight-bold',
					width: '10%',
					align: 'start'
				},
				{
					text: 'Amount Due',
					value: 'amount_due',
					class: 'text-uppercase th--text font-weight-bold',
					width: '15%',
					align: 'start'
				},
				{
					text: 'Status',
					value: 'status',
					class: 'text-uppercase th--text font-weight-bold',
					width: '15%',
					align: 'start'
				},
				{
					text: 'Sent Count',
					value: 'sent_count',
					class: 'text-uppercase th--text font-weight-bold',
					width: '15%',
					align: 'start'
				},
				{ text: '', value: 'actions', sortable: false, width: '15%', align: 'end' }
			];
		},

		pagination: {
			get() {
				return this.statementLists.data ? this.statementLists.meta : {};
			}
		},

		currencyValue: {
			get() {
				return this.selectedStatementInformation?.currency_values
					? JSON.parse(this.selectedStatementInformation.currency_values).name
					: '';
			}
		},

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		}
	},

	methods: {
		...mapActions('accounting', ['getStatements','getCustomerStat']),
		...globalMethods,
		moneyFormat(item){
			if( item && this.contact.currency_code == 'USD' ){
				return item.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
			}else{
				return ( this.contact.currency_code == 'USD' ? '$' : '')+item;
			}
		},
		$moment(){
			return moment;
		},
		formatDateTime(d,f){
			return moment(d).format(f);
		},
		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchStatementsData();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchStatementsData();
		},
		async fetchStatementsData() {
			let _this = this;

			if (this.isLoading) {
				return;
			}
			this.isLoading = true;

			try {
				const data = await this.getStatements({
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || '',
					customer_id: this.$route.params.id,
					filter_type: this.filter_type,
					filter_status: this.filter_status
				});
				this.statementLists = data.map( i =>{
					i.name = _this.contact.name;
					i.email = _this.contact.email;
					i.phone = _this.contact.phone;
					i.address = _this.contact.address;
					i.customer_id = _this.contact.id;
					i.documents = i.docs.map( i =>{
						i.paid_amount = typeof i.paid_amount != 'undefined' ? i.paid_amount : i.total_paid_amount;
					});

					return i;
				});

				this.isLoading = false;
			} catch (error) {
				this.isLoading = false;
				apiErrorMessage(error);
			}
		},
		onToggleStatementForm(options = {}) {
			this.isEditMode = false;
			this.selectedServiceData = {};
			this.showFormDialog = !this.showFormDialog;

			if (options.created || options.updated) {
				this.snackbarOption = {
					icon: 'mdi-check',
					color: 'success',
					message: options.message
				};
				this.showSnackbar = true;
				this.showInfoModal = false;
				this.fetchStatementsData();
			}
		},
		onSelectEditStatementData(data = {}) {
			this.selectedStatementData = JSON.parse(JSON.stringify(data));
			this.isEditMode = true;
			this.showFormDialog = true;
		},

		onSelectStatementToDelete(data = {}) {
			this.selectedStatementToDelete = JSON.parse(JSON.stringify(data));
			this.onToggleDeleteStatementForm();
		},

		onToggleDeleteStatementForm(options = {}) {
			this.showDeleteModal = !this.showDeleteModal;

			if (options.deleted) {

				const message = options.message || 'Customer has been successfully deleted.'
				this.notificationCustom(message)
				this.showSnackbar = true;
				this.showInfoModal = false;

				this.fetchStatementsData();
			}
			if(!this.showDeleteModal){
				this.selectedCustomerToDelete = {};
			}

		},

		onSelectStatementInformation(data) {
			data.documents = data.docs;

			this.selectedStatementInformation = data;
			this.onToggleStatementInformationModal();
		},

		onToggleStatementInformationModal() {
			this.showInfoModal = !this.showInfoModal;
			if (!this.showInfoModal) {
				this.selectedStatementInformation = {};
			}
		}
	}
};
</script>

<style lang="scss">
	.v-select .v-icon.mdi.mdi-close{ 
		color: #ff6347!important;
		font-size:18px!important;
	}
</style>

<style lang="scss" scoped>
	.v-select--is-menu-active{
		background:none!important;
	}
	.stat-box{
		width:100%;
		padding:16px;
		text-align:center;
		border:1px solid #F5F5F5;
		margin-bottom: 16px;
		h1{
			margin-bottom: 8px;
			font-weight: bold;
			color:#819fb2;
		}
		h4{
			margin-bottom: 0px;
			opacity: .7;
		}
	}
	.custom-table{
		border:none;
		width:100%;
		th{
			border-bottom-width: 2px !important;
		}
		th,td{
			padding:5px 8px;
			border:1px solid #F5F5F5;
		}
		tbody tr td{
			color: #242424;
		}
		
	}
	@media screen and (max-width: 600px) {
		::v-deep .v-data-table > .v-data-table__wrapper {
			height: calc(100vh - 268px) !important;
			overflow-y: auto;
			overflow-x: hidden;
		}
	}
	::v-deep .v-data-table {
		.v-data-table__wrapper {
			height: calc(100vh - 236px);
			overflow-y: auto;
			overflow-x: hidden;
		}

		&.no-current__pagination,
		&.no-data__table {
			.v-data-table__wrapper {
				height: calc(100vh - 180px);
			}
		}
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

	.v-tabs {
		border-bottom: 1px solid #ddd;

		.v-tab {
			color: #0889a0;
		}
	}

	.w-100 {
		width: 100%;
	}

	.v-list-item {
		min-height: 36px;
	}
</style>
