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
							<h2 class="invoice-title">Transaction #{{ transactionNumber }}</h2>
							<v-progress-circular v-if="isLoading" color="primary" indeterminate size="24"></v-progress-circular>
							<!-- <v-chip
								class="ml-2 text-capitalize"
								filter
								:text-color="statusColor.color"
								:color="statusColor.background"
								v-if="transactionNumber"
								:class="data.status"
							>
								{{ data.status }}
							</v-chip> -->
						</span>
					</div>
					<div class="d-flex px-3 px-sm-0">
						<v-btn
							v-if="data.status !== 'paid'"
							class="text-capitalize blue--text btn-blue"
							outlined
							@click="onEditClick"
							:loading="isLoading"
						>
							Edit
						</v-btn>
						<v-btn
							class="ml-2 text-capitalize btn-white"
							style="color: red !important;"
							color="red"
							outlined
							@click="onDeleteClick"
							:loading="isLoading"
						>
							Delete
						</v-btn>
					</div>
				</div>
			</div>

			<v-card flat>
				<v-card-text>
					<v-row>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Date</span>
								<p class="subtitle-1 font-weight-medium">{{ getMonthNameDayYearFormat(data.paid_at) }}</p>
							</div>
							<div>
								<span class="labelcolor--text text-uppercase form-label">Account</span>
								<p class="subtitle-1 font-weight-medium mb-0">{{ data.account ? data.account.name : '' }}</p>
							</div>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Category</span>
								<p class="subtitle-1 font-weight-medium">
									{{ data.category ? data.category.name : ''}}
								</p>
							</div>
							<div>
								<span class="labelcolor--text text-uppercase form-label">Reference</span>
								<p class="subtitle-1 text-capitalize font-weight-medium">
									{{ data.reference || '--' }}
								</p>
							</div>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							
							<div>
								<span class="labelcolor--text text-uppercase form-label">Description</span>
								<p class="subtitle-1 text-capitalize font-weight-medium mb-0">
									{{ data.description || '--' }}
								</p>
							</div>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Amount</span>
								<p class="subtitle-1 text-capitalize font-weight-bold mb-0">
									{{ currencyFormat(data.amount) }}
								</p>
							</div>
						</v-col>
					</v-row>
					<v-row>
						<v-col cols="12" sm="12" md="12">
							<h3>{{data.type === 'income' ? 'Bill From' : 'Paid To'}}</h3>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Name</span>
								<p class="subtitle-1 font-weight-medium">{{ data.contact ? data.contact.name : '--'}}</p>
							</div>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Address</span>
								<p class="subtitle-1 font-weight-medium mb-0">
									{{ data.contact && data.contact.address !== null ? data.contact.address : '--' }}
								</p>
							</div>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Phone</span>
								<p class="subtitle-1 text-uppercase font-weight-medium">
									{{ data.contact && data.contact.phone !== null ? data.contact.phone : '--' }}
								</p>
							</div>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Email</span>
								<p class="subtitle-1 font-weight-medium mb-0">{{ data.contact ? data.contact.email : '' }}</p>
							</div>
						</v-col>
					</v-row>
				</v-card-text>

				<v-toolbar outlined flat>
					<v-tabs v-model="tabStatus" @change="onChangeTab">
						<v-tab class="text-capitalize">Items</v-tab>
						<!-- <v-tab class="text-capitalize">{{ ('payments') }}</v-tab> -->
					</v-tabs>
				</v-toolbar>

				<v-card-text class="pa-0" v-if="tabStatus === 0">
					<v-row>
						<v-col cols="12" class="border-right" md="12" sm="12">
							<v-row>
								<v-card-text class="mb-4">
									<v-simple-table class="v-table-middle-align">
										<template v-slot:default>
											<thead>
											<tr>
												<th class="text-uppercase labelcolor--text">#</th>
												<th class="text-uppercase labelcolor--text">Description</th>
												<th class="text-uppercase labelcolor--text">Document</th>
												<th class="text-uppercase labelcolor--text text-right">Due Date</th>
												<th class="text-uppercase labelcolor--text text-right">Document Amount</th>
												<th class="text-uppercase labelcolor--text text-right">Payment Amount</th>
											</tr>
											</thead>
											<tbody>
											<tr v-for="(transactionItem, i) in items" :key="i">
												<td>{{ i + 1 }}</td>
												<td>{{ transactionItem.description }}</td>
												<td>{{ transactionItem.document.document_number }}</td>
												<td class="text-right">{{ getMonthNameDayYearFormat(transactionItem.document.due_at) }}</td>
												<td class="text-right">{{ currencyFormat(transactionItem.document.amount) }}</td>
												<td class="text-right">{{ currencyFormat(transactionItem.amount) }}</td>
											</tr>
											</tbody>
										</template>
									</v-simple-table>
								</v-card-text>
							</v-row>
						</v-col>
					</v-row>
				</v-card-text>
			</v-card>
		</div>


        <invoice-payment-form
			:open="showInvoicePaymentFormDialog"
			:is-edit-mode="true"
			:form-values="selectedTransactionData"
			@toggle="onToggleEditModal"
		></invoice-payment-form>
        <bill-payment-form
            :open="showBillPaymentFormDialog"
			:is-edit-mode="true"
			:form-values="selectedTransactionData"
			@toggle="onToggleEditModal"
        >
        </bill-payment-form>
        <transaction-delete-modal
			:header="'Delete Transaction!'"
			:message="'Are you sure you want to delete this transaction?'"
			:open="showDeleteConfirmationModal"
			:form-data="selectedTransactionData"
			@toggle="onToggleDeleteModal"
		>
		</transaction-delete-modal>
        <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar>
	</v-sheet>
</template>

<script>
	import { mapGetters } from 'vuex';
    import InvoicePaymentForm from '../../components/Accounting/InvoicePaymentForm.vue';
    import BillPaymentForm from '../../components/Accounting/BillPaymentForm.vue';
    import TransactionDeleteModal from '../../components/Accounting/TransactionDeleteModal.vue';

	import AkauntingService from '../../services/akaunting.service';
	import { apiErrorMessage } from '../../utils/globalMethods';

	export default {
		name: 'TransactionDetails',
		components: {
			InvoicePaymentForm,
            BillPaymentForm,
            TransactionDeleteModal,
		},
		data() {
			return {
                showInvoicePaymentFormDialog: false,
                isInvoicePaymentFormEditMode: false,
                showBillPaymentFormDialog: false,
                isBillPaymentFormEditMode: false,
                showDeleteConfirmationModal: false,
				tabStatus: 0,
                selectedTransactionData: {},
                isEditMode: false,
				data: {},
				isLoading: false,
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
			this.fetchTransaction();
		},
		computed: {
			//
			...mapGetters('accounting', ['isQBOEnabled']),

			breadCrumb() {
				return [
					{
						text: 'Accounting',
						disabled: true,
						href: '#'
					},
					{
						text: 'Transactions',
						disabled: false,
						href: '/accounting/transactions'
					},
					{
						text: this.transactionNumber || '',
						disabled: true,
						href: '#'
					}
				];
			},

			transactionNumber: {
				get() {
					return this.data.number || '';
				}
			},

			transactionId() {
				return this.$route.params.id;
			},

			items() {
				return this.data.transaction_items ? this.data.transaction_items : [];
			},

			customerCurrencySymbol() {
				return this.data?.currency?.data?.symbol || '';
			},

			currency() {
				return this.data?.currency?.data?.name;
			},

            isInvoicePayment(){
                return this.data.type === 'income';
            }
		},
		methods: {
			async fetchTransaction() {
				if (this.isLoading) {
					return;
				}
				this.isLoading = true;
				try {
					const records = await AkauntingService.getTransaction(this.transactionId);
					this.data = records.data?.data?.data || {};
				} catch (error) {
					apiErrorMessage(error);
				} finally {
					this.isLoading = false;
				}
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

            onEditClick(){
                this.selectedTransactionData = this.data;
				this.isEditMode = true;
				if(this.isInvoicePayment){
                    this.showInvoicePaymentFormDialog = !this.showInvoicePaymentFormDialog;
                }else{
                    this.showBillPaymentFormDialog = !this.showBillPaymentFormDialog;
                }
            },

            onDeleteClick(){
                this.selectedTransactionData = this.data;
                this.showDeleteConfirmationModal = true;
            },

			onToggleEditModal(options = {}) {
				this.isEditMode = false;
				this.selectedTransactionData = null;
				if(this.isInvoicePayment){
                    this.showInvoicePaymentFormDialog = !this.showInvoicePaymentFormDialog;
                }else{
                    this.showBillPaymentFormDialog = !this.showBillPaymentFormDialog;
                }

                if (options.created || options.updated) {
					this.snackbarOption = {
						icon: 'mdi-check',
						color: 'success',
						message: options.message
					};
					this.selectedPaymentInformation = {};
					this.showSnackbar = true;
					this.fetchTransaction();
				}
			},

			onToggleDeleteModal(options = null){
				this.showDeleteConfirmationModal = !this.showDeleteConfirmationModal;

				if (options.deleted) {
					this.snackbarOption = {
						icon: 'mdi-delete',
						color: 'red',
						message: options.message || 'Deleted.'
					};
					this.showSnackbar = true;

					this.$router.push('/accounting/transactions');
				}
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

	.subtitle-1 {
		margin-bottom: 12px;
	}
</style>

<style lang="scss">
@import './scss/invoiceDetails.scss';
</style>
