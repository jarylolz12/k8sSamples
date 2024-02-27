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
							<h2 class="invoice-title">Invoice #{{ invoiceNumber }}</h2>
							<v-progress-circular v-if="isLoading" color="primary" indeterminate size="24"></v-progress-circular>
							<v-chip
								class="ml-2 text-capitalize"
								filter
								:text-color="statusColor.color"
								:color="statusColor.background"
								v-if="invoiceNumber"
								:class="data.status">
								{{ data.status }}
							</v-chip>
						</span>
					</div>
					<div class="d-flex px-3 px-sm-0">
						<v-btn
							v-if="data.status !== 'paid'"
							class="text-capitalize blue--text btn-blue"
							outlined
							@click="onToggleEditModal"
							:loading="isLoading"
						>
							Edit Invoice
						</v-btn>
						<v-btn
							class="ml-2 text-capitalize btn-white"
							style="color: red !important;"
							color="red"
							outlined
							@click="onToggleDeleteInvoice"
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
								<span class="labelcolor--text text-uppercase form-label">Customer</span>
								<p class="subtitle-1 font-weight-medium">{{ data.contact_name || '--' }}</p>
							</div>
							<div>
								<span class="labelcolor--text text-uppercase form-label">Order Number</span>
								<p class="subtitle-1 font-weight-medium mb-0">{{ data.order_number || '--' }}</p>
							</div>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Billing Address</span>
								<p class="subtitle-1 text-uppercase font-weight-medium">
									<!-- {{ data.bill_to_address || '&nbsp;' }} -->
									{{  
										data.bill_to_address !== 'null' && 
										data.bill_to_address !== null &&  
										data.bill_to_address !== '' ? 
										data.bill_to_address : '--' 
									}}
								</p>
							</div>
							<div>
								<span class="labelcolor--text text-uppercase form-label">Category</span>
								<p class="subtitle-1 text-uppercase font-weight-medium mb-0">{{ category || '--' }}</p>
							</div>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Invoice Date</span>
								<p class="subtitle-1 text-capitalize font-weight-medium">
									{{ getMonthNameDayYearFormat(data.issued_at) }}
								</p>
							</div>
							<div>
								<span class="labelcolor--text text-uppercase form-label">Due Date</span>
								<p class="subtitle-1 text-capitalize font-weight-medium mb-0">
									{{ getMonthNameDayYearFormat(data.due_at) }}
								</p>
							</div>
						</v-col>
						<v-col cols="12" sm="6" md="3">
							<div>
								<span class="labelcolor--text text-uppercase form-label">Notes</span>
								<p class="subtitle-1 text-capitalize font-weight-medium">{{ data.notes || '--' }}</p>
							</div>
							<div>
								<span class="labelcolor--text text-uppercase form-label">Currency</span>
								<p class="subtitle-1 text-capitalize font-weight-medium mb-0">{{ currency || '--' }}</p>
							</div>
						</v-col>
					</v-row>
				</v-card-text>

				<v-toolbar outlined flat>
					<v-tabs v-model="tabStatus" @change="onChangeTab">
						<v-tab class="text-capitalize">Summary</v-tab>
						<!-- <v-tab class="text-capitalize">{{ ('payments') }}</v-tab> -->
					</v-tabs>
				</v-toolbar>

				<v-card-text class="pa-0" v-if="tabStatus === 0">
					<v-row>
						<v-col cols="12" class="border-right" md="12" sm="12">
							<v-simple-table class="v-table-middle-align">
								<template v-slot:default>
									<thead>
										<tr>
											<th class="text-uppercase labelcolor--text font-weight-bold" style="width: 25%; min-width: 25%;">Item</th>
											<th class="text-uppercase labelcolor--text font-weight-bold" style="width: 20%; min-width: 20%;">Description</th>
											<th class="text-uppercase labelcolor--text text-right font-weight-bold" style="width: 15%; min-width: 15%;">
												Quantity
											</th>
											<th class="text-uppercase labelcolor--text text-right font-weight-bold" style="width: 15%; min-width: 15%;">
												Price
											</th>
											<th class="text-uppercase labelcolor--text text-right font-weight-bold" style="width: 15%; min-width: 15%;">
												Total
											</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(item, index) in items" :key="index">
											<td>{{ item.item.name }}</td>
											<td>{{ item.description }}</td>
											<td class="text-right">{{ item.quantity }}</td>
											<td class="text-right">{{ item.price }}</td>
											<td class="text-right">
												{{ currencyFormat(item.total) }}
											</td>
										</tr>
									</tbody>
								</template>
							</v-simple-table>
							<v-row class="mt-4">
								<v-col cols="12" sm="7">
									<v-card class="text-center border-dashed" width="400" flat>
										<v-card-title class="text-subtitle-1 labelcolor--text">
											<span class="form-label">Attachments</span>
											<v-spacer />
											<v-btn
												v-if="attachedFiles.length"
												text
												class="text-capitalize"
												color="primary"
												outlined
												:disabled="!!downloadingFileIds.length"
												@click="onDownloadAll"
											>
												<v-icon>mdi-download</v-icon> Download
											</v-btn>
										</v-card-title>
										<v-card-text class="pa-0">
											<!-- <v-subheader class="text-subtitle-1 labelcolor--text">{{ ('attachments') }}</v-subheader> -->
											<v-skeleton-loader
												max-width="360"
												type="card"
												v-if="attachmentLoading"
											></v-skeleton-loader>
											<v-list subheader two-line>
												<v-list-item
													v-for="attachedFile in attachedFiles"
													:key="`attached-${attachedFile.id}`"
													dense
													class="pa-0"
												>
													<v-list-item-avatar height="20" width="20">
														<v-icon color="#0889a0" v-text="'mdi-image'"></v-icon>
													</v-list-item-avatar>

													<v-list-item-content>
														<v-list-item-title
															v-text="attachedFile.filename"
															class="text-left"
														></v-list-item-title>
													</v-list-item-content>

													<v-list-item-action class="flex-row">
														<v-btn
															icon
															:title="'download'"
															@click="onDownloadAttachment(attachedFile)"
															:loading="downloadingFileIds.includes(attachedFile.id)"
														>
															<v-icon color="primary">mdi-download</v-icon>
														</v-btn>
													</v-list-item-action>
												</v-list-item>
											</v-list>
										</v-card-text>
									</v-card>
								</v-col>
								<v-col class="mr-4">
									<v-row class="">
										<v-col class="text-right font-weight-bold">Sub-Total</v-col>
										<v-col class="text-right font-weight-bold">{{ currencyFormat(subTotal) }}</v-col>
									</v-row>
									<template v-if="isQBOEnabled && discount">
										<v-divider></v-divider>
										<v-row class="mt-0">
											<v-col class="text-right font-weight-bold">Discount</v-col>
											<v-col class="text-right font-weight-bold"
												>-{{ currencyFormat(discount || 0) }}</v-col
											>
										</v-row>
									</template>
									<template v-for="(item, i) in item_taxes">
										<v-row class="mt-0" :key="`tax-item-${item.id}-${i}`">
											<v-col class="text-right font-weight-bold"
												>{{ item.name }} ({{ item.tax.rate }}%)</v-col
											>
											<v-col class="text-right font-weight-bold">{{ item.amount_formatted }}</v-col>
										</v-row>
									</template>
									<div class="mb-2">&nbsp;</div>
									<v-divider></v-divider>
									<v-row class="mt-1">
										<v-col class="text-right font-weight-bold">Total</v-col>
										<v-col class="text-right font-weight-bold">{{
											currencyFormat(subTotal + taxTotal - discount || 0)
										}}</v-col>
									</v-row>
									<v-row>
										<v-col class="text-right font-weight-bold subtitle-1">Balance Due</v-col>
										<v-col class="text-right font-weight-bold">{{
											currencyFormat(data.open_balance || 0)
										}}</v-col>
									</v-row>
								</v-col>
							</v-row>
						</v-col>
						<!-- <v-col sm="12" md="3">
						<span class="text-uppercase subtitle-1 font-weight-black ml-10">{{ ('invoice') }} {{ ('activity') }}</span>
						<v-timeline
						dense
						align-top
						>
						<v-timeline-item
							v-for="(activity, i) in invoiceActivity"
							:key="activity.status"
							small
							color="white"
						>
							<template v-slot:icon>
							<v-icon :color="activity.isActive ? '#0b6084' : '#b3cfe0'">mdi-record-circle-outline</v-icon>
							</template>
							<span class="activity-color font-weight-bold" :class="{'in-active-activity': !activity.isActive}">
							{{ activity.status }}
							</span>
							<br>
							<small v-if="activity.isActive" class="activity-color">{{ getMDYFormat(activity.date) }}</small>
							<div v-if="activity.isPayment">
							<span class="activity-color">
								{{ activity.description }}
							</span>
							<div>
								<a
								href="#"
								class="text-decoration-none"
								>
								{{ ('view') }} {{ ('payment') }}
								</a>
							</div>
							</div>
						</v-timeline-item>
						</v-timeline>
					</v-col> -->
					</v-row>
				</v-card-text>

				<!-- <v-card-text v-else class="mb-16">
					<v-card width="300" class="mx-auto text-center my-8" flat v-if="payments.length === 0">
					<v-card-text>
						<span class="black--text text-h5">{{ ('no') }} {{ ('payments') }}</span>
						<p class="font-weight-medium">{{ ('invoice_no_payment') }}</p>
					</v-card-text>
					</v-card>

					<v-simple-table class="v-table-middle-align" v-else>
					<template v-slot:default>
						<thead>
						<tr>
							<th class="text-uppercase labelcolor--text">{{ ('payment') }} {{ ('date') }}</th>
							<th class="text-uppercase labelcolor--text">{{ ('payment') }} {{ ('method') }}</th>
							<th class="text-uppercase labelcolor--text">{{ ('deposit') }} {{ ('to') }}</th>
							<th class="text-uppercase labelcolor--text text-right">{{ ('original') }} {{ ('amount') }}</th>
							<th class="text-uppercase labelcolor--text text-right">{{ ('payment') }}</th>
							<th class="text-uppercase labelcolor--text text-right">{{ ('open') }} {{ ('balance') }}</th>
							<th v-if="data.status !== 'paid'"></th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="(payment, i) in payments" :key="i">
							<td>{{ getMonthNameDayYearFormat(payment.paymentDate) }}</td>
							<td>{{ payment.paymentMethod }}</td>
							<td>{{ payment.depositTo }}</td>
							<td class="text-right">{{ currencyFormat(payment.originalAmount) }}</td>
							<td class="text-right">{{ currencyFormat(payment.paymentAmount) }}</td>
							<td class="text-right">{{ currencyFormat(payment.openBalance) }}</td>
							<td v-if="data.status !== 'paid'">
							<v-btn text outlined small height="32" width="32" color="#0171a1" @click="onEditPaymentForm(payment)">
								<v-icon>mdi-pencil</v-icon>
							</v-btn>
							</td>
						</tr>
						</tbody>
					</template>
					</v-simple-table>
				</v-card-text> -->
			</v-card>
		</div>

		<invoice-form
			:open="showEditModal"
			:form-values="formData"
			:is-edit-mode="true"
			@toggle="onToggleEditModal"
		></invoice-form>
		<invoice-delete
			:open="showDeleteInvoiceModal"
			:form-data="data"
			@toggle="onToggleDeleteInvoice"
		></invoice-delete>
		<!-- <receive-payment-form :open="showPaymentModal" :form-values="paymentData" :invoice="data" :is-edit-mode="isPaymentModalEditMode" @toggle="onTogglePaymentModal"></receive-payment-form> -->
		<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar> -->
	</v-sheet>
</template>

<script>
import { mapGetters } from 'vuex';
import InvoiceForm from '../../components/Accounting/InvoiceForm.vue';
import InvoiceDelete from '../../components/Accounting/InvoiceDeleteModal.vue';
// import ReceivePaymentForm from '../components/ReceivePaymentForm.vue';

import AkauntingService from '../../services/akaunting.service';
import { apiErrorMessage } from '../../utils/globalMethods';
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'InvoiceDetails',
	components: {
		InvoiceForm,
		InvoiceDelete
		// ReceivePaymentForm,
	},
	data() {
		return {
			tabStatus: 0,
			showEditModal: false,
			showPaymentModal: false,
			showDeleteModal: false,
			isPaymentModalEditMode: false,
			paymentData: {},
			data: {
				items: []
			},
			payments: [
				{
					id: 1,
					paymentDate: '2021-06-04',
					paymentMethod: 'Cash',
					depositTo: 'Checking (9876)',
					originalAmount: 6879.99,
					paymentAmount: 6879.99,
					openBalance: 0
				}
			],
			invoiceActivity: [
				{
					status: 'Opened',
					date: '2021-12-03',
					isActive: true
				},
				{
					status: 'Sent',
					date: '2021-12-01',
					isActive: true
				},
				{
					status: 'paid',
					date: '2021-11-30',
					isActive: false
				},
				{
					status: 'Payment Received',
					date: '2021-11-28',
					isActive: true,
					description: '$1,200.00 out of $6,210.00',
					isPayment: true
				},
				{
					status: 'Deposited',
					date: '2021-11-28',
					isActive: false
				}
			],
			isLoading: false,
			showDeleteInvoiceModal: false,
			formData: null,
			attachedFiles: [],
			attachmentLoading: false,
			downloadingFileIds: [],
			showSnackbar: false,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			}
		};
	},
	created() {
		this.fetchInvoice();
	},
	computed: {
		//
		...mapGetters('accounting', ['isQBOEnabled']),

		statusColor() {
			if (this.data.status) {
				switch (this.data.status.toLowerCase()) {
					case 'paid':
						return { color: '#49af41', background: '#fff' };
					case 'overdue':
						return { color: '#e32d2d', background: '#fff2f2' };
					case 'partial':
						return { color: '#7cdab8', background: '#ecffed' };
					case 'open':
						return { color: '#0171a1', background: '#cbf1ff' };
					case 'draft':
						return { color: '#4a4a4a', background: '#fff' };
					case 'sent':
						return { color: '#ff0000', background: '#efefef' };
					case 'cancelled':
						return { color: '#888888', background: '#efefef' };
				}
			}
			return { color: '#fff', background: '#fff' };
		},

		breadCrumb() {
			return [
				{
					text: 'Accounting',
					disabled: true,
					href: '#'
				},
				{
					text: 'Invoices',
					disabled: false,
					href: '/accounting/invoices'
				},
				{
					text: this.invoiceNumber || '',
					disabled: true,
					href: '#'
				}
			];
		},

		invoiceNumber: {
			get() {
				return this.data.document_number || '';
			}
		},

		subTotal() {
			if (this.items && this.items.length) {
				return this.items.reduce((c, n) => c + Number(n.quantity) * Number(n.price), 0);
			}
			return 0;
		},

		taxTotal() {
			return this.data?.item_taxes?.data?.reduce((c, n) => c + n.amount, 0);
		},

		discount() {
			const discountData = JSON.parse(this.data.discount_ref || null);
			if (discountData) {
				let discounted = discountData?.discount || 0;
				if (discountData.discount_type === 'percentage') {
					discounted = this.subTotal * (discounted / 100);
				}

				if (discounted > this.subTotal) {
					return this.subTotal;
				}
				return discounted;
			}
			return 0;
		},

		invoiceId() {
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

		/*  attachedFiles() {
	return this.data?.attachment || [];
}, */

		currency() {
			return this.data?.currency?.data?.name;
		},

		item_taxes() {
			return this.data?.item_taxes?.data || [];
		}
	},
	methods: {
		//
		...globalMethods,
		async fetchInvoice() {
			if (this.isLoading) {
				return;
			}
			this.isLoading = true;
			try {
				const records = await AkauntingService.getInvoice(this.invoiceId);
				this.data = records.data?.data?.data || {};
				if (this.isQBOEnabled) {
					this.fetchAttachments(this.data.id);
				} else {
					this.attachedFiles = this.data.attachment;
				}
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.isLoading = false;
			}
		},

		async fetchAttachments(id) {
			if (this.attachmentLoading) {
				return;
			}
			this.attachmentLoading = true;
			try {
				const { data } = await AkauntingService.getInvoiceAttachments(id);
				this.attachedFiles = data?.data || [];
				this.attachmentLoading = false;
			} catch (error) {
				apiErrorMessage(error);
				this.attachmentLoading = false;
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

		onTogglePaymentModal() {
			this.showPaymentModal = !this.showPaymentModal;
			if (!this.showPaymentModal) {
				this.paymentData = {};
			}
		},

		onToggleEditModal(options = {}) {
			this.showEditModal = !this.showEditModal;
			this.formData = null;

			if (this.showEditModal) {
				this.formData = JSON.parse(JSON.stringify(this.data));
			}

			if (options.updated || options.created) {
				this.snackbarOption = {
					icon: 'mdi-check',
					color: 'success',
					message: options.message
				};

				this.notificationMessage(options.message)
				this.showSnackbar = true;
				this.fetchInvoice();
			}
		},

		/*eslint no-unused-vars: "off"*/
		onToggleDeleteInvoice(data = {}, options = {}) {
			this.showDeleteInvoiceModal = !this.showDeleteInvoiceModal;
			if (options.deleted) {
				this.snackbarOption = {
					icon: 'mdi-delete',
					color: 'success',
					message: options.message || 'Deleted.'
				};

				const message = options.message || 'Invoice has been deleted.'
				this.notificationCustom(message)
				this.$router.push('/accounting/invoices');
			}
		},

		onChangeTab() {
			//
		},

		async onDownloadAttachment(file) {
			if (this.downloadingFileIds.includes(file.id)) {
				return;
			}
			this.downloadingFileIds.push(file.id);
			try {
				const { data, headers } = this.isQBOEnabled
					? await AkauntingService.downloadInvoiceAttachment(file.id)
					: await AkauntingService.downloadInvoiceAttachmentV2(file.id);
				var blob = new Blob([data], { type: headers['content-type'] });
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = `${file.filename}.${file.extension}`;
				link.click();
				link.remove();
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.downloadingFileIds = this.downloadingFileIds.filter((fileId) => fileId !== file.id);
			}
		},

		onDownloadAll() {
			this.attachedFiles.forEach((file) => {
				this.onDownloadAttachment(file);
			});
		}
	},
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
