<template>
	<v-sheet outlined class="accounting-invoice-details-wrapper" :class="isLoading ? 'is-loading-wrapper' : ''">
		<div class="loading-wrapper" v-if="isLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

		<div v-if="!isLoading">
			<div class="accounting-invoice-main-header">
				<v-breadcrumbs :items="breadCrumb" class="pl-0 px-3 pb-4 px-sm-0 pt-0">
					<template v-slot:item="{ item }">
						<v-breadcrumbs-item
							link
							:href="item.href"
							@click.prevent="$router.push(item.href)"
							:disabled="item.disabled"
						>
							{{ item.text }}
						</v-breadcrumbs-item>
					</template>
					<template v-slot:divider>
						<v-icon>mdi-chevron-right</v-icon>
					</template>
				</v-breadcrumbs>

				<div class="d-md-flex d-sm-flex justify-space-between align-center accounting-invoice-details-header">
					<div class="px-3 px-sm-0">
						<span class="accounting-invoice-title text-h5">
							<h2 class="invoice-title">{{ contactName }}</h2>
							<v-progress-circular v-if="isLoading" color="primary" indeterminate size="24"></v-progress-circular>
							<v-chip
								class="ml-2 text-capitalize"
								filter
								:text-color="statusColor.color"
								:color="statusColor.background"
								v-if="contactName"
								:class="`${data.status} ${contactStatus}`">
								{{ contactStatus }}
							</v-chip>
						</span>
					</div>
					<div class="d-flex px-3 px-sm-0">
						<v-btn
							v-if="isCustomerEnabled"
							class="text-capitalize blue--text btn-blue"
							outlined
							@click="onSelectEditCustomerData"
							:loading="isLoading"
						>
							Edit Customer
						</v-btn>
						<v-btn
							v-if="isCustomerEnabled"
							class="text-capitalize blue--text btn-blue"
							outlined
							@click="$refs.customerStatementsTable.onToggleStatementForm()"
							:loading="isLoading"
						>
							Statement
						</v-btn>
						<v-btn
							v-if="isCustomerEnabled"
							class="ml-2 text-capitalize btn-white"
							style="color: red !important;margin-left:1px"
							color="red"
							outlined
							@click="onSelectCustomerToDelete"
							:loading="isLoading"
						>
							Disable
						</v-btn>
					</div>
				</div>
			</div>

			<v-card flat>
				<v-card-text>
					<v-row>
						<v-col cols="12" sm="6" md="6">
							<v-row>
								<v-col cols="6" sm="12" md="6">
									<div>
										<span class="labelcolor--text text-uppercase form-label">Email</span>
										<p class="subtitle-1 font-weight-medium">{{ contactEmail || '--' }}</p>
									</div>
									<div>
										<span class="labelcolor--text text-uppercase form-label">Phone</span>
										<p class="subtitle-1 font-weight-medium mb-0">{{ contactPhone || '--' }} </p>
									</div>
								</v-col>
								<v-col cols="6" sm="12" md="6">
									<div>
										<span class="labelcolor--text text-uppercase form-label">Tax Number</span>
										<p class="subtitle-1 text-uppercase font-weight-medium">
											{{ contactTaxNumber || '--' }}
										</p>
									</div>
									<div>
										<span class="labelcolor--text text-uppercase form-label">Billing Address</span>
										<p class="subtitle-1 text-uppercase font-weight-medium mb-0">
											{{ contactAddress || '--' }}
										</p>
									</div>
								</v-col>
							</v-row>
						</v-col>
						<v-col cols="12" sm="6" md="6">
							<v-row>
								<v-col cols="12" sm="12" md="4">
									<div>
										<span class="labelcolor--text text-uppercase font-medium font-18">Paid</span>
										<p class="text-uppercase font-medium font-18 pt-2">{{ totalPaidTransactionFormatted }}</p>
									</div>
								</v-col>
								<v-col cols="12" sm="12" md="4">
									<div>
										<span class="labelcolor--text text-uppercase font-medium font-18">Open</span>
										<p class="text-uppercase font-medium font-18 pt-2">
											{{ totalOpenTransactionFormatted }}
										</p>
									</div>
								</v-col>
								<v-col cols="12" sm="12" md="4">
									<div>
										<span class="labelcolor--text text-uppercase font-medium font-18">Overdue</span>
										<p class="text-uppercase font-medium font-18 pt-2" :class="{'status-overdue': totalOverdueTransaction > 0}">
											{{ totalOverdueTransactionFormatted }}
										</p>
									</div>
								</v-col>
							</v-row>
						</v-col>
					</v-row>
				</v-card-text>

				<v-toolbar outlined flat>
					<v-tabs v-model="tabStatus" @change="onChangeTab">
						<v-tab class="text-capitalize">Invoices</v-tab>
						<v-tab class="text-capitalize">Transactions</v-tab>
						<v-tab class="text-capitalize">Statements</v-tab>
					</v-tabs>
				</v-toolbar>

				<v-card-text class="pa-0" v-if="tabStatus === 0">
					<v-row>
						<v-col cols="12" class="border-right" md="12" sm="12">
	                        <customer-invoices-table :contactId="contactId"></customer-invoices-table>
						</v-col>
					</v-row>
				</v-card-text>
				<v-card-text class="pa-0" v-if="tabStatus === 1">
					<v-row>
						<v-col cols="12" class="border-right" md="12" sm="12">
							<p class="pa-6 subtitle-2 text-center">Work in progress, this feature will be available soon.</p>
						</v-col>
					</v-row>
				</v-card-text>
				<v-card-text class="pa-0" v-show="tabStatus === 2">
					<v-row>
						<v-col cols="12" class="border-right" md="12" sm="12">
	                        <customer-statements-table ref="customerStatementsTable" :contactId="contactId"></customer-statements-table>
						</v-col>
					</v-row>
				</v-card-text>
			</v-card>
		</div>

		<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar> -->
		
		<customer-form
			:open="showEditModal"
			:is-edit-mode="isEditMode"
			:form-values="selectedCustomerData"
			@toggle="onToggleCustomerForm">
		</customer-form>

		<customer-delete
			:open="showDeleteModal"
			:form-data="selectedCustomerToDelete"
			@toggle="onToggleDeleteCustomer">
		</customer-delete>
	</v-sheet>
</template>

<script>
import CustomerStatementsTable from '../../components/Accounting/CustomerStatementsTable.vue';
import CustomerInvoicesTable from '../../components/Accounting/CustomerInvoicesTable.vue';
import CustomerForm from '../../components/Accounting/CustomerForm.vue';
import CustomerDelete from '../../components/Accounting/CustomerDeleteModal.vue';

import AkauntingService from '../../services/akaunting.service';
import { apiErrorMessage } from '../../utils/globalMethods';
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'CustomerDetails',
	components: {
		CustomerInvoicesTable,
		CustomerForm,
		CustomerDelete,
		CustomerStatementsTable
	},
	data() {
		return {
			tabStatus: 0,
			showEditModal: false,
			showPaymentModal: false,
			showDeleteModal: false,
			isEditMode: true,
			selectedCustomerData: {},
			selectedCustomerToDelete: {},
			data: {
				items: []
			},
			isLoading: false,
			showDeleteInvoiceModal: false,
			formData: null,
			showSnackbar: false,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			}
		};
	},
	created() {
		this.fetchCustomer();
	},
	watch: {
		tabStatus(v){
			if( v != 2 ) return;

			let _this = this;

			setTimeout(()=>{
				_this.$refs.customerStatementsTable.$data.contact = this.data.contact ? this.data.contact : {};
				_this.$refs.customerStatementsTable.fetchStatementsData();
			},500);
		}
	},
	computed: {

		statusColor() {
			if (this.data.contact?.enabled) {
				return { color: '#fff', background: '#49af41' };
			}
			return { color: '#e32d2d', background: '#fff2f2' };
		},

		breadCrumb() {
			return [
				{
					text: 'Accounting',
					disabled: true,
					href: '#'
				},
				{
					text: 'Customers',
					disabled: false,
					href: '/accounting/customers'
				},
				{
					text: this.contactName || '',
					disabled: true,
					href: '#'
				}
			];
		},

		contactName: {
			get() {
				return this.data.contact?.name || '';
			}
		},

		contactEmail: {
			get() {
				return this.data.contact?.email || '';
			}
		},

		contactPhone: {
			get() {
				return this.data.contact?.phone || '';
			}
		},

		contactAddress: {
			get() {
				return this.data.contact?.address || '';
			}
		},

		contactTaxNumber: {
			get() {
				return this.data.contact?.tax_number || '';
			}
		},

		contactStatus: {
			get() {
				return this.data.contact?.enabled ?  'Active' : 'Inactive';
			}
		},

		totalPaidTransactionFormatted: {
			get() {
				return this.data.totals?.paid_formatted || '';
			}
		},

		totalOpenTransactionFormatted: {
			get() {
				return this.data.totals?.open_formatted || '';
			}
		},

		totalOverdueTransactionFormatted: {
			get() {
				return this.data.totals?.overdue_formatted || '';
			}
		},

		totalOverdueTransaction: {
			get() {
				return this.data.totals?.overdue || 0;
			}
		},

		isCustomerEnabled:{
			get(){
				return this.data.contact?.enabled || false;
			}
		},

		contactId() {
			return this.$route.params.id;
		},

		category() {
			return this.data.category_ref ? JSON.parse(this.data.category_ref)?.name : '';
		},

		items() {
			return this.data.items_ref ? JSON.parse(this.data.items_ref) : [];
		},

		customerCurrencySymbol() {
			return this.data?.currency?.data?.symbol || '';
		},

	},
	methods: {
		...globalMethods,
		async fetchCustomer() {
			if (this.isLoading) {
				return;
			}
			this.isLoading = true;
			try {
				const records = await AkauntingService.getCustomer(this.contactId);
				this.data = records.data?.data || {};
				this.$store.commit('customers/SET_CUSTOMER_DATA',this.data.contact);
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.isLoading = false;
			}
		},

		onEditPaymentForm(data) {
			this.paymentData = JSON.parse(JSON.stringify(data));
			this.showPaymentModal = true;
		},

		getMonthNameDayYearFormat(dateString) {
			if (!dateString) {
				return '';
			}
			const months = ['Jan', 'Feb', 'March', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
			const _date = new Date(dateString);
			return `${months[_date.getMonth()]} ${_date.getDate()}, ${_date.getFullYear()}`;
		},

		currencyFormat(amount) {
			return new Intl.NumberFormat('en-US', {
				style: 'currency',
				currency: this.data?.currency?.data?.code || 'USD'
			}).format(amount);
		},

		getMDYFormat(dateString) {
			const date = new Date(dateString);
			return `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear()}`;
		},

		onToggleCustomerForm(options = {}) {
			this.isEditMode = true;
			this.showEditModal = !this.showEditModal;
			this.selectedCustomerData = null;
			if (options.created || options.updated) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'success',
				// 	message: options.message
				// };

				this.notificationMessage(options.message)
				this.showSnackbar = true;
				this.showInfoModal = false;
				this.fetchCustomer();
			}
		},

		onSelectEditCustomerData() {
			this.selectedCustomerData = this.data.contact;
			this.isEditMode = true;
			this.showEditModal = true;
		},			

		onToggleDeleteCustomer(options = {}) {
			this.showDeleteModal = !this.showDeleteModal;

			if (options.deleted) {
				// this.snackbarOption = {
				// 	icon: 'mdi-delete',
				// 	color: 'red',
				// 	message: options.message || 'SuccessFully disabled.'
				// };

				const message = options.message || 'Customer has been successfully disabled.'
				this.notificationCustom(message)
				this.showSnackbar = true;
				this.showInfoModal = false;

				this.fetchCustomer();
			}
			if(!this.showDeleteModal){
				this.selectedCustomerToDelete = {};
			}
		},

		onSelectCustomerToDelete() {
			this.selectedCustomerToDelete = this.data.contact;
			this.onToggleDeleteCustomer();
		},

		onChangeTab() {
			//
		},
	}
};
</script>
<style lang="scss" scoped>
	$button-bg-color: #0171a1;

	.btn-primary {
		background-color: $button-bg-color !important;
		color: #fff !important;
	}

	.activity-color {
		color: #0b6084;
	}

	.activity-color.in-active-activity {
		color: #b3cfe0;
	}

	.status-overdue {
		color: #f93131;
	}

	.Active {
		border: 1px solid #16b442;
		background-color: #ebfaef !important;
		color: #16b442 !important;
	}

	.Inactive {
		background-color: #fff;
		color: #f93131 !important;
		border: 1px solid #f93131 !important;
	}

	.font-20 {
		font-size: 20px
	}

	.font-18 {
		font-size: 18px
	}

	.loading-wrapper {
        width: 100%;
        min-height: 200px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

</style>

<style lang="scss">
@import './scss/invoiceDetails.scss';
</style>
