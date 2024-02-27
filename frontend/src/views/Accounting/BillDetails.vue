<template>
	<div class="accounting-invoice-details-wrapper" :class="isDataLoading ? 'is-loading-wrapper' : ''">
		<div class="loading-wrapper" v-if="isDataLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

		<div v-if="!isDataLoading">
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
						<span class="accounting-invoice-title">
							<h2 class="invoice-title">Bill No. {{ refNumber }}</h2>
						</span>
					</div>

					<div class="d-flex justify-end mb-0">
						<!-- <v-menu offset-y>
							<template v-slot:activator="{ on, attrs }">
								<v-btn v-bind="attrs" primary v-on="on" color="primary" text outlined>
									<v-icon>mdi-dots-horizontal</v-icon>
								</v-btn>
							</template>
							<v-list>
								<v-list-item
									link
								>
									<v-list-item-title class="text-primary" @click="onToggleMarkReceivedForm">{{ ('billing_mark_receive') }}</v-list-item-title>
								</v-list-item>
							</v-list>
						</v-menu> -->
						<v-btn
							class="text-capitalize blue--text btn-blue"
							outlined
							@click="onToggleMarkReceivedForm"
							:loading="isDataLoading"
							v-if="data && data.status === 'draft'"
						>
							{{ 'Mark as Received' }}
						</v-btn>
						<v-btn class="text-capitalize blue--text mx-2 btn-blue" outlined @click="onToggleEditBill" :loading="isDataLoading">
							{{ 'Edit Bill' }}
						</v-btn>
						<!-- <v-btn
							class="primary white--text mx-2 text-capitalize"
							outlined
							large
							@click="showPaymentModal = true"
							:loading="isDataLoading"
						>
							{{ ('pay_bill') }}
						</v-btn> -->
						<v-btn class="text-capitalize red--text btn-white" style="color: red !important;" outlined @click="onToggleDeleteBill" :loading="isDataLoading">
							{{ 'Delete' }}
						</v-btn>
					</div>
				</div>
			</div>		

			<v-card id="bill-details-card" flat>
				<v-card-text>
					<v-row>
						<v-col cols="12" sm="4">
							<div>
								<span class="form-label text-uppercase">{{ 'Vendor' }}</span>
								<p class="subtitle-1 font-weight-medium">{{ vendor }}</p>
							</div>
							<div>
								<span class="form-label text-uppercase">{{ 'Mailing Address' }}</span>
								<p class="subtitle-1 text-lowercase font-weight-medium">{{ email }}</p>
							</div>
							<div>
								<span class="form-label text-uppercase">{{ 'Notes' }}</span>
								<p class="subtitle-1 font-weight-medium">{{ (data && data.notes) || '--' }}</p>
							</div>
						</v-col>
						<v-col cols="12" sm="4">
							<div>
								<span class="form-label text-uppercase">{{ 'Bill Date' }}</span>
								<p class="subtitle-1 text-capitalize font-weight-medium">{{ billDate }}</p>
							</div>
							<div class="">
								<span class="form-label text-uppercase">{{ 'Due Date' }}</span>
								<p class="subtitle-1 text-capitalize font-weight-medium">{{ dueDate }}</p>
							</div>
							<div class="">
								<span class="form-label text-uppercase">{{ 'Status' }}</span>
								<p class="subtitle-1 text-capitalize font-weight-medium">
									<v-chip
										dark
										v-text="data && data.status"
										small
										class="text-capitalize"
										v-if="!isDataLoading"
										:class="data.status" 
									/>
								</p>
							</div>
						</v-col>
						<v-col cols="12" sm="4">
							<div>
								<span class="form-label text-uppercase">{{ 'Currency' }}</span>
								<p class="subtitle-1 text-uppercase font-weight-medium">{{ currency }}</p>
							</div>
						</v-col>
					</v-row>
				</v-card-text>
				<!-- <v-divider></v-divider>
				<v-card-text>
					<h5 class="subtitle-1 font-weight-bold text-dark labelcolor--text">{{ 'Outstanding Transactions' }}</h5>
				</v-card-text> -->
				<v-divider></v-divider>
				<v-data-table
					hide-default-footer
					:items="items"
					:headers="outstandingTransactionsHeaders"
					class="v-table-middle-align">

					<template v-slot:[`item.price`]="{ item }">
						<p class="mb-0 font-semi-bold" v-text="formatCurrency(item.price)" />
					</template>
					<template v-slot:[`item.total`]="{ item }">
						<p  class="mb-0 font-semi-bold" v-text="formatCurrency(item.total)" />
					</template>
				</v-data-table>
				<v-card-text class="white">
					<v-row>
						<v-col cols="6" sm="9"></v-col>
						<v-col>
							<v-row class="">
								<v-col class="text-right font-semi-bold pb-1">Subtotal</v-col>
								<v-col class="text-right font-semi-bold pb-1">{{ currencyFormat(subTotal) }}</v-col>
							</v-row>
							<v-row v-if="taxTotal">
								<v-col class="text-right font-medium pb-1">Tax</v-col>
								<v-col class="text-right font-semi-bold pb-1">
									{{ new Intl.NumberFormat('en-US').format(taxTotal) }}
								</v-col>
							</v-row>
							<v-row>
								<v-col class="text-right font-semi-bold pb-1">Total</v-col>
								<v-col class="text-right font-semi-bold pb-1">{{ currencyFormat(subTotal + taxTotal) }}</v-col>
							</v-row>
							<v-row class="align-center">
								<v-col class="text-right font-semi-bold">Balance Due</v-col>
								<v-col class="text-right font-semi-bold">{{ open_balance }}</v-col>
							</v-row>
						</v-col>
					</v-row>
				</v-card-text>
			</v-card>
		</div>

		<billing-form
			:open="showEditModal"
			:form-values="formData"
			:is-edit-mode="true"
			@toggle="onToggleEditBill"
		></billing-form>
		<bill-delete :open="showDeleteModal" :form-data="data" @toggle="onToggleDeleteBill"></bill-delete>
		<!-- <bill-make-payment-form
      :open="showPaymentModal"
      :form-values="data"
      @toggle="showPaymentModal = !showPaymentModal"
    ></bill-make-payment-form> -->
		<bill-received
			:open="showMarkReceivedModal"
			:form-data="data"
			@toggle="onToggleMarkReceivedForm"
		></bill-received>
	</div>
</template>

<script>
import BillingForm from '../../components/Accounting/BillingForm.vue';
// import BillMakePaymentForm from '../components/BillMakePaymentForm.vue';
import BillDelete from '../../components/Accounting/BillingDeleteModal.vue';
import BillReceived from '../../components/Accounting/BillMarkReceivedModal.vue';

import globalMethods, { apiErrorMessage } from '../../utils/globalMethods';
import moment from 'moment';
import accountingMixin from '../../components/Accounting/mixin';
import { mapActions } from 'vuex';

export default {
	name: 'BillDetails',
	components: {
		BillingForm,
		// BillMakePaymentForm,
		BillDelete,
		BillReceived
	},
	mixins: [accountingMixin],

	data() {
		return {
			showEditModal: false,
			showPaymentModal: false,
			showDeleteModal: false,
			data: null,
			isDataLoading: true,
			formData: null,
			showMarkReceivedModal: false
		};
	},

	created() {
		this.fetchBill();
	},
	computed: {
		outstandingTransactionsHeaders() {
			return [
				{ text: 'CATEGORY', value: 'item.name', align: 'left', class: 'th--text font-weight-bold' },
				{ text: 'DESCRIPTION', value: 'description', align: 'left', class: 'th--text font-weight-bold' },
				{ text: 'QUANTITY', value: 'quantity', align: 'right', class: 'th--text font-weight-bold', sortable: false },
				{ text: 'AMOUNT', value: 'price', align: 'right', class: 'th--text font-weight-bold', sortable: false },
				{ text: 'TOTAL', value: 'total', align: 'right', class: 'th--text font-weight-bold', sortable: false }
			];
		},

		breadCrumb() {
			return [
				{
					text: 'Accounting',
					disabled: true,
					href: '#'
				},
				{
					text: 'Bills',
					disabled: false,
					href: '/accounting/bills'
				},
				{
					text: this.refNumber || '',
					disabled: true,
					href: '#'
				}
			];
		},

		refNumber: {
			get() {
				return this.data ? this.data.document_number : '';
			}
		},

		vendor: {
			get() {
				return this.data ? this.data.contact_name : '';
			}
		},

		email: {
			get() {
				return this.data ? this.data.contact.email : '';
			}
		},

		billDate: {
			get() {
				return this.data ? this.moment(this.data.issued_at).format('MMM DD, YYYY') : '';
			}
		},

		dueDate: {
			get() {
				return this.data ? this.moment(this.data.due_at).format('MMM DD, YYYY') : '';
			}
		},

		items: {
			get() {
				return this.data && this.data.items.length ? JSON.parse(this.data.items_ref) : [];
			}
		},

		subTotal: {
			get() {
				if (this.items.length) {
					return this.items.reduce((c, n) => c + Number(n.price || 0) * Number(n.quantity || 0), 0);
				}
				return 0;
			}
		},

		taxTotal() {
			/* 	if(this.data.billCategories.length) {
	return this.data.billCategories.reduce((c, n) => c + Number(n.taxAmount) ,0);
} */
			return 0;
		},

		currency() {
			return this.data?.currency_code;
		},

		statusColor: {
			get() {
				if (this.data) {
					switch (this.data.status.toLowerCase()) {
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
				}
				return '';
			}
		},

		vendorCurrency() {
			const vendor = JSON.parse(this.data.contact_ref) || {};
			return vendor?.currency_code || 'USD';
		},

		open_balance() {
			return this.data?.open_balance_formatted;
		}
	},
	methods: {
		//
		...mapActions('accounting', ['getBill']),
		moment,
		...globalMethods,
		formatCurrency(value) {
			return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
		},

		fetchBill() {
			this.getBill(this.$route.params.id)
				.then((response) => {
					this.data = response.data.data.data;
				})
				.catch((error) => apiErrorMessage(error))
				.finally(() => {
					this.isDataLoading = false;
				});
		},

		onToggleEditBill(options = {}) {
			this.showEditModal = !this.showEditModal;
			this.formData = null;

			if (this.showEditModal) {
				this.formData = JSON.parse(JSON.stringify(this.data));
			}

			if (options.created || options.updated) {
				/* this.$notify({
		title: 'Message',
		message: options.message || 'Updated.',
		type: 'success'
		}); */
				this.notificationMessage(options.message || 'Updated.');
				this.fetchBill();
			}
		},
		/*eslint no-unused-vars: "off"*/
		onToggleDeleteBill(data = null, options = {}) {
			this.showDeleteModal = !this.showDeleteModal;
			if (options.deleted) {
				/* this.$notify({
		title: 'Message',
		message: options.message || 'Deleted.',
		type: 'success'
		}); */
				this.notificationMessage(options.message || 'Deleted.');
				this.$router.push('/accounting/bills');
			}
		},

		/*eslint no-unused-vars: "off"*/
		onToggleMarkReceivedForm(data = null, options = {}) {
			this.showMarkReceivedModal = !this.showMarkReceivedModal;
			if (options.received) {
				/* this.$notify({
		icon: 'mdi-check',
		type: 'success',
		message: options.message || 'Mark as received.'
		}); */
				this.notificationMessage(options.message || 'Mark as received.');
				this.fetchBill();
			}
		}
	}
};
</script>
<style lang="scss" scoped>
	// $button-bg-color: #0171a1;
	// $form-label: #819fb2;
	// .form-label {
	// 	color: $form-label;
	// 	font-weight: bold;
	// }
	// #bill-details-card {
	// 	height: calc(100vh - 246px);
	// }
</style>

<style lang="scss">
@import './scss/invoiceDetails.scss';

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

.draft {
	background-color: #F1F6FA !important;
	color: #4a4a4a !important;
	font-family: 'Inter-Medium', sans-serif;
}
</style>
