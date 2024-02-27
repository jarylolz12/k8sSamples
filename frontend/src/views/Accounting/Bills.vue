<template>
	<v-sheet color="transparent" class="accounting-bills-wrapper">
		<h3 class="font-20 mb-3 ml-2 mt-3 ml-sm-0 mt-sm-0">Bills</h3>
		<v-sheet>
			<v-data-table
				class="v-table-middle-align"
				v-bind:class="{
					'no-data__table' : (transactionData.length === 0),
					'no-current__pagination' : (pagination.last_page <= 1),
				}"
				fixed-header
				show-select
				v-model="selected"
				hide-default-footer
				disable-pagination
				:single-select="false"
				:headers="tableHeaders"
				:items="transactionData"
				:loading="isBillDataLoading"
			>
				<template v-slot:top>
					<div class="d-md-flex justify-end w-100 pt-3 px-4 pb-3 border-bottom">
						<div class="d-md-flex justify-md-end my-2 my-md-0 mx-md-0">
							<div class="d-flex">
								<v-menu offset-y bottom content-class="outbound-lists-menu">
									<template v-slot:activator="{ on, attrs }">
										<v-btn
											v-bind="attrs"
											v-on="on"
											outlined
											large
											height="40"
											class="text-capitalize btn-white"
											color="primary"
										>
											{{ 'status' + (selectedStatus ? ': ' + selectedStatus.toUpperCase() : '') }}
											<v-icon>mdi-menu-down</v-icon>
										</v-btn>
									</template>
									<v-card width="225">
										<v-card-text class="px-0 py-2">
											<v-list dense class="py-0">
												<v-list-item-group
													v-model="selectedStatus"
													color="primary"
													@change="fetchBills()"
												>
													<v-list-item :value="''">
														<v-list-item-content>
															<v-list-item-title>NONE</v-list-item-title>
														</v-list-item-content>
													</v-list-item>
													<v-list-item
														v-for="status in billStatusList"
														:key="status"
														:value="status"
													>
														<v-list-item-content>
															<v-list-item-title
																v-text="status"
																class="text-uppercase"
															></v-list-item-title>
														</v-list-item-content>
													</v-list-item>
												</v-list-item-group>
											</v-list>
										</v-card-text>
									</v-card>
								</v-menu>
								<div class="search-field-wrapper ml-2">
									<!-- <v-text-field :label="'bill_search_no'" prepend-inner-icon="mdi-magnify" clearable hide-details flat outlined solo dense v-model="searchText" color="primary"></v-text-field> -->
									<Search :inputData.sync="searchText" placeholder="Search No." />
								</div>
							</div>
							<div class="d-md-block d-none ml-2">
								<v-btn
									@click="onToggleBillingForm"
									class="text-capitalize"
									color="btn-blue"
									height="38"
								>
									{{ 'Add Bill' }}
								</v-btn>
							</div>
							<div class="d-sm-block d-md-none mt-2">
								<v-btn
									@click="onToggleBillingForm"
									class="text-capitalize d-block"
									color="btn-blue"
									height="38"
									width="100%"
								>
									{{ 'Add Bill' }}
								</v-btn>
							</div>
						</div>
					</div>
				</template>
				<template v-slot:[`item.document_number`]="{ item }">
					<a
						style="text-decoration: none;"
						href="#"
						@click.prevent="
							$router.push(`/accounting/${item.type === 'bill' ? 'bills' : 'expenses'}/${item.id}`)
						"
					>
						<span>{{ item.document_number }}</span>
					</a>
				</template>
				<template v-slot:[`item.issued_at`]="{ item }">
					<span>{{ convertDate(item.issued_at) }}</span>
				</template>
				<template v-slot:[`item.due_at`]="{ item }">
					<span>{{ convertDate(item.due_at) }}</span>
				</template>
				<template v-slot:[`item.status`]="{ item }">
					<v-chip dark :class="item.status" v-text="item.status" small class="text-capitalize" style="height: 26px;" />
				</template>
				<template v-slot:[`item.actions`]="{ item }">
					<v-menu offset-y left content-class="outbound-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn v-bind="attrs" primary v-on="on" color="primary" class="btn-more-items btn-white" text outlined small>
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>
						<v-list>
							<v-list-item link v-if="item.status === 'draft'">
								<v-list-item-title class="text-primary" @click="onToggleMarkReceivedForm(item)">{{
									'Mark as Received'
								}}</v-list-item-title>
							</v-list-item>
							<v-list-item link @click="onTransactionSelected(item.type === 'bill' ? 0 : 1, item)">
								<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
							</v-list-item>
							<v-list-item
								link
								@click="
									$router.push(
										`/accounting/${item.type === 'bill' ? 'bills' : 'expenses'}/${item.id}`
									)
								"
							>
								<v-list-item-title>{{ 'View' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link v-if="item.type === 'Expense'">
								<v-list-item-title>{{ 'Print' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link v-if="item.type === 'Expense'">
								<v-list-item-title>{{ 'Void' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link>
								<v-list-item-title
									class="text-red"
									@click="
										item.type === 'bill' ? onToggleDeleteBill(item) : onSelectDataToDelete(item)
									"
									>{{ 'Delete' }}</v-list-item-title
								>
							</v-list-item>
						</v-list>
					</v-menu>
				</template>
				<template v-slot:no-data>
					<div class="mt-5" v-if="isBillDataLoading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate>
						</v-progress-circular>
					</div>

					<v-alert class="mt-2" v-if="!isBillDataLoading && transactionData.length === 0 && searchText == ''">
						<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
						<p class="subtitle-2 no-data-title">Add Bills</p>
						<span class="subtitle-p-data">You don't have any bills yet.</span>
						<br />
						<v-btn
							outlined
							text
							small
							@click="onToggleBillingForm"
							color="#0889a0"
							class="py-4 text-capitalize btn-white"
						>
							<v-icon size="18">mdi-plus</v-icon>
							{{ 'Add Bill' }}
						</v-btn>
					</v-alert>

					<v-alert class="mt-2" v-if="!isBillDataLoading && transactionData.length === 0 && searchText !== ''">
						<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
						<p class="subtitle-2 no-data-title">No matching result</p>
						<span class="subtitle-p-data">Sorry! We could not find any bills that matches your search term.</span>
						<br />
					</v-alert>
				</template>
				<template v-slot:loading>
					<div class="mt-5" v-if="isBillDataLoading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate>
						</v-progress-circular>
					</div>
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

			<!-- <expense-form :open="showExpenseFormDialog" :form-values="formDataSelected" :is-edit-mode="isFormEditMode" @toggle="onTransactionSelected(1)"></expense-form>
    <payment-bills-form :open="showPaymentBillFormDialog" @toggle="onTransactionSelected(2)"></payment-bills-form>
    <bill-make-payment-form :open="showMakePaymentFormDialog" @toggle="showMakePaymentFormDialog = !showMakePaymentFormDialog"></bill-make-payment-form> -->

			<billing-form
				:open="showBillingFormDialog"
				:form-values="selectedBillingData"
				:is-edit-mode="isFormEditMode"
				@toggle="onToggleBillingForm"
			></billing-form>
			<BillDelete :open="showDeleteBillModal" :form-data="selectedBillingData" @toggle="onToggleDeleteBill" />
			<bill-received
				:open="showMarkReceivedModal"
				:form-data="selectedBillingData"
				@toggle="onToggleMarkReceivedForm"
			></bill-received>
			<!-- <expense-delete :open="showDeleteExpenseModal" :form-data="formDataToDelete" @toggle="showDeleteExpenseModal = !showDeleteExpenseModal"></expense-delete> -->
		</v-sheet>
	</v-sheet>
</template>

<script>
import { apiErrorMessage, debounce } from '../../utils/globalMethods';

import { mapActions } from 'vuex';

import BillingForm from '../../components/Accounting/BillingForm.vue';
// import ExpenseForm from './ExpenseForm.vue';
// import PaymentBillsForm from './PayBillsForm.vue';
// import BillMakePaymentForm from './BillMakePaymentForm.vue';
import BillDelete from '../../components/Accounting/BillingDeleteModal.vue';
import BillReceived from '../../components/Accounting/BillMarkReceivedModal.vue';
// import ExpenseDelete from './ExpenseDeleteModal.vue';
import Pagination from '../../components/Accounting/Pagination.vue';
import Search from '../../components/Accounting/SearchInput.vue';
import moment from 'moment';
import globalMethods from '../../utils/globalMethods'

export default {
	name: 'Bills',
	components: {
		Search,
		BillingForm,
		// ExpenseForm,
		// PaymentBillsForm,
		// BillMakePaymentForm,
		BillDelete,
		BillReceived,
		// ExpenseDelete,
		Pagination
	},
	data: () => ({
		showBillingFormDialog: false,
		showExpenseFormDialog: false,
		showPaymentBillFormDialog: false,
		showMakePaymentFormDialog: false,
		showDeleteBillModal: false,
		showDeleteExpenseModal: false,
		showMarkReceivedModal: false,
		isFormEditMode: false,
		dialogDelete: false,
		formDataSelected: {},
		formDataToDelete: {},
		searchText: '',
		selected: [],
		transactionMenu: [{ title: 'Add Bill' }, { title: 'Add Expense' }, { title: 'Pay Bills' }],
		filterBy: '',
		selectedStatus: '',
		currentPage: 1,
		// pageLimit: 25,
		pageLimit: 35,
		snackbarOption: {
			icon: '',
			message: '',
			color: ''
		},
		showSnackbar: false,
		isDataLoading: false,
		selectedBillingData: null,
		billsData: [],
		isBillDataLoading: false,
		billStatusList: ['draft', 'received', 'partial', 'paid', 'overdue', 'unpaid', 'cancelled']
	}),

	created() {
		this.fetchBills();
	},

	watch: {
		searchText: debounce(function() {
			this.fetchBills();
		}, 300)
	},

	computed: {
		// ...mapState('accounting', ['billsData', 'isBillDataLoading']),
		tableHeaders() {
			return [
				{
					text: 'Number',
					value: 'document_number',
					class: 'text-uppercase th--text',
					width: '10%',
					align: 'start'
				},
				{
					text: 'Vendor',
					sortable: false,
					value: 'contact.name',
					class: 'text-uppercase th--text',
					width: '20%',
					align: 'start'
				},
				{
					text: 'Bill Date',
					value: 'issued_at',
					class: 'text-uppercase th--text',
					width: '15%',
					align: 'start'
				},
				{
					text: 'Due Date',
					value: 'due_at',
					class: 'text-uppercase th--text',
					width: '15%',
					align: 'start'
				},
				{
					text: 'Amount',
					value: 'amount_formatted',
					class: 'text-uppercase th--text',
					width: '12%',
					align: 'end'
				},
				// {
				//   text: ('total'),
				//   value: "total",
				//   class: "text-uppercase"
				// },
				{
					text: 'Status',
					value: 'status',
					class: 'text-uppercase th--text',
					align: 'center',
					width: '15%',
					sortable: false
				},
				{ text: '', value: 'actions', sortable: false }
			];
		},

		transactionData: {
			get() {
				return this.billsData?.data || [];
			}
		},

		pagination: {
			get() {
				return this.billsData.data ? this.billsData.meta : {};
			}
		}
	},

	methods: {
		...mapActions('accounting', ['getBillsData']),
		moment,
		...globalMethods,
		itemStatus(status) {
			switch (status.toLowerCase()) {
				case 'received':
					return 'red darken-4';
				case 'paid':
					return 'green darken-4';
				case 'draft':
					return 'purple darken-4';
				case 'partial':
					return 'blue darken-4';
				case 'cancelled':
					return 'black';
			}
		},
		convertDate(val) {
			// return moment(val).format('DD MMM YYYY, h:mm A')
			return moment(val).format('DD MMM YYYY');
		},

		async fetchBills(filters = {}) {
			if (this.isBillDataLoading) {
				return;
			}
			this.isBillDataLoading = true;
			try {
				const data = await this.getBillsData({
					...filters,
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || '',
					status: this.selectedStatus
				});

				this.billsData = data;
				this.isBillDataLoading = false;
			} catch (error) {
				this.isBillDataLoading = false;
				apiErrorMessage(error);
			}
		},

		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchBills();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchBills();
		},

		currencyDollarFormat(number = 0) {
			return new Intl.NumberFormat('en-US').format(number);
		},

		onSelectDataToDelete(data = {}) {
			this.showDeleteExpenseModal = false;
			this.showDeleteBillModal = false;
			if (data.type === 'Bill') {
				this.showDeleteBillModal = true;
			} else if (data.type === 'Expense') {
				this.showDeleteExpenseModal = true;
			}
			this.formDataToDelete = { ...data };
		},

		onSelectselectedStatus() {
			this.fetchBills();
		},

		onToggleBillingForm(options = {}) {
			this.showBillingFormDialog = !this.showBillingFormDialog;
			if (options.created || options.updated) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'success',
				// 	message: options.message
				// };

				this.notificationMessage(options.message)
				this.showSnackbar = true;
				this.isFormEditMode = false;
				this.fetchBills();
				this.selectedBillingData = null;
			}
			if (!this.showBillingFormDialog) {
				this.isFormEditMode = false;
			}
		},

		onToggleDeleteBill(data = null, options = {}) {
			this.selectedBillingData = JSON.parse(JSON.stringify(data));
			this.showDeleteBillModal = !this.showDeleteBillModal;
			if (options.deleted) {
				// this.snackbarOption = {
				// 	icon: 'mdi-delete',
				// 	color: 'red',
				// 	message: options.message || 'Deleted'
				// };

				const message = options.message || 'Customer has been deleted.'
				this.notificationCustom(message)
				this.fetchBills();
				this.showSnackbar = true;
			}
		},

		onTransactionSelected(index, data = null) {
			this.selectedBillingData = null;
			this.formDataSelected = data;

			this.isFormEditMode = data !== null;

			if (index === 0) {
				this.selectedBillingData = JSON.parse(JSON.stringify(data));
				this.onToggleBillingForm({});
			}

			if (index === 1) {
				this.showExpenseFormDialog = !this.showExpenseFormDialog;
			}

			if (index === 2) {
				this.showPaymentBillFormDialog = !this.showPaymentBillFormDialog;
			}
		},

		onToggleMarkReceivedForm(data = null, options = {}) {
			this.selectedBillingData = JSON.parse(JSON.stringify(data));
			this.showMarkReceivedModal = !this.showMarkReceivedModal;
			if (options.received) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'green',
				// 	message: options.message || 'Mark as received.'
				// };

				const message = options.message || 'Mark as received.'
				this.notificationCustom(message)
				this.fetchBills();
				this.showSnackbar = true;
			}
		}
	}
};
</script>

<style lang="scss" scoped>
	$form-color: #0889a0;
	$btn-active-color: #0171a1;
	$text-muted-color: #6d858f;
	@media screen and (max-width: 600px) {
		::v-deep .v-data-table > .v-data-table__wrapper {
			height: calc(100vh - 298px) !important;
			overflow-y: auto;
			overflow-x: hidden;
		}
	}
	::v-deep .v-data-table {
		.v-data-table__wrapper {
			height: calc(100vh - 286px);
			overflow-y: auto;
			overflow-x: hidden;
		}

		&.no-current__pagination,
		&.no-data__table {
			.v-data-table__wrapper {
				height: calc(100vh - 230px);
			}
		}
	}
	.text-green {
		color: #07a107be;
	}

	.text-red {
		color: red;
	}

	.form-label {
		color: $form-color;
	}

	.form-border {
		border: 1px solid;
		border-color: $form-color !important;
	}

	.btn-active-color:active {
		background-color: $btn-active-color;
		color: #fff;
	}

	.text-muted {
		color: ext-muted-color;
	}

	.v-btn.v-item--active.v-btn--active {
		color: $btn-active-color;
	}

	.search-field-wrapper {
		max-width: 400px;
	}

	.text-white {
		color: #fff;
	}

	.text-primary {
		color: var(--shifl-pressed-blue);
	}

	.v-list-item {
		min-height: 36px;
	}

	.btn-primary {
		background-color: $btn-active-color !important;
		color: #ffffff !important;
	}

	.border-bottom {
		border-bottom: thin solid #EBF2F5;
	}

	.font-20 {
		font-size: 20px;
	}

	.draft {
		background-color: #F1F6FA !important;
		color: #4a4a4a !important;
		font-family: 'Inter-Medium', sans-serif;
		border-radius: 99px;
		padding: 6px 10px;
		font-size: 12px;
	}

	.received {
		background-color: #F5F9FC !important;
		border-radius: 99px;
		padding: 6px 10px;
		font-size: 12px;
		color: #16B442 !important;
		font-family: 'Inter-Medium', sans-serif;
	}
</style>

<style lang="scss">
@import './scss/bills.scss';
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';
</style>