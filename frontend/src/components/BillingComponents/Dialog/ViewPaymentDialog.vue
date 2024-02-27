<!-- @format -->

<template>
	<v-dialog
		v-model="dialogView"
		max-width="800px"
		content-class="view-payment-dialog"
		:retain-focus="false"
		@click:outside="close"
		v-if="!getInvoiceDetailLoadingStatus"
	>
		<v-card>
			<v-card-title>
				<span class="headline">Invoice #{{ paymentData.invoice_number }}</span>

				<div class="header-actions">
					<div>
						<button
							:disabled="getcardsLoading"
							class="btn btn-blue"
							v-if="
								paymentData.payment_status === 'Not Paid' && userCanMakePayment
							"
							@click="openPaymentDialog(paymentData)"
						>
							Make Payment
						</button>
						<button
							class="btn-white"
							v-if="paymentData.payment_status === 'Paid'"
							disabled
						>
							<img
								src="../../../assets/icons/checkMark.png"
								class="mr-1"
								width="15px"
								height="15px"
								alt=""
							/>
							<span class="green--text">Paid</span>
						</button>
					</div>

					<button
						:disabled="getInvoiceDownloadLoadingStatus"
						class="btn btn-white"
						@click="download(paymentData)"
					>
						{{
							getInvoiceDownloadLoadingStatus ? "Downloading..!" : "Download"
						}}
					</button>

					<button icon dark class="btn-close" @click="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</div>
			</v-card-title>

			<v-card-text>
				<div class="view-payment-wrapper">
					<div class="header-actions" v-if="isMobile">
						<div>
							<button
								:disabled="getcardsLoading"
								class="btn btn-blue"
								v-if="
									paymentData.payment_status === 'Not Paid' &&
										userCanMakePayment
								"
								@click="openPaymentDialog(paymentData)"
							>
								Make Payment
							</button>
							<button
								class="btn-white"
								v-if="paymentData.payment_status === 'Paid'"
								disabled
							>
								<img
									src="../../../assets/icons/checkMark.png"
									class="mr-1"
									width="15px"
									height="15px"
									alt=""
								/>
								<span class="green--text">Paid</span>
							</button>
						</div>

						<button
							:disabled="getInvoiceDownloadLoadingStatus"
							class="btn btn-white"
							@click="download(paymentData)"
						>
							{{
								getInvoiceDownloadLoadingStatus ? "Downloading..!" : "Download"
							}}
						</button>
					</div>

					<div class="view-payment-info">
						<v-row class="row-between mb-2">
							<v-col cols="12" sm="6">
								<div class="card-name mb-2">
									<p class="card-title">Bill To</p>
									<span>
										{{ paymentData.bill_to }} <br />
										{{ paymentData.bill_to_address }} <br />
									</span>
								</div>
							</v-col>

							<v-col class="row-inline" cols="12" sm="6">
								<div class="card-name mb-2">
									<p class="card-title">Date</p>
									<span>{{ paymentData.date }}</span>
								</div>

								<div class="card-name mb-2">
									<p class="card-title">Due Date</p>
									<span>{{ paymentData.due_date }}</span>
								</div>

								<div class="card-name mb-2">
									<p class="card-title">Reference</p>
									<span>{{ paymentData.shipment_reference_number }}</span>
								</div>
							</v-col>
						</v-row>

						<v-row>
							<v-col cols="12" sm="4">
								<div class="card-box first-card">
									<div class="card-name mb-3">
										<p class="card-title">Bl #</p>
										<span>{{
											paymentData.mbl_number ? paymentData.mbl_number : "N/A"
										}}</span>
									</div>

									<div class="card-name mb-3">
										<p class="card-title">From</p>
										<span v-if="paymentData.from">
											{{ paymentData.from }} <br />
											{{ paymentData.etd }}
										</span>
										<span v-else>
											N/A
										</span>
									</div>

									<div class="card-name mb-3">
										<p class="card-title">To</p>
										<span v-if="paymentData.to">
											{{ paymentData.to }} <br />
											{{ paymentData.eta }}
										</span>
										<span v-else>
											N/A
										</span>
									</div>
								</div>
							</v-col>

							<v-col cols="12" sm="8">
								<div class="card-box">
									<div class="card-name mb-3">
										<p class="card-title">Suppliers</p>
										<span
											v-for="(supplier, i) in paymentData.suppliers"
											:key="i"
										>
											{{ supplier.name }} <br />
										</span>
										<span
											v-if="
												paymentData.suppliers &&
													paymentData.suppliers.length == 0
											"
											>N/A</span
										>
									</div>

									<div class="card-name mb-3">
										<p class="card-title">Purchase Orders</p>
										<span
											v-for="(purchase_order, i) in paymentData.purchase_orders"
											:key="i"
										>
											{{ purchase_order }},
										</span>
										<span
											v-if="
												paymentData.purchase_orders &&
													paymentData.purchase_orders.length == 0
											"
											>N/A</span
										>
									</div>

									<div class="card-name mb-3">
										<p class="card-title">Containers</p>
										<span
											v-for="(container, i) in paymentData.containers"
											:key="i"
										>
											{{ container }},
										</span>
										<span
											v-if="
												paymentData.containers &&
													paymentData.containers.length == 0
											"
											>N/A</span
										>
									</div>
								</div>
							</v-col>
						</v-row>

						<v-row class="table-row">
							<v-col>
								<div class="table-header" v-if="isMobile">
									<p>Description</p>
									<p>Amount</p>
								</div>

								<div class="view-table-wrapper">
									<v-data-table
										:headers="headers"
										:items="items"
										class="elevation-1 view-payment-table"
										v-if="!isMobile"
										hide-default-footer
									>
										<template v-slot:[`item.name`]="{ item }">
											<div>
												<p class="mb-0">{{ item.name }}</p>
												<p class="description">{{ item.description }}</p>
											</div>
										</template>
										<template v-slot:[`item.rate`]="{ item }">
											${{ item.rate }}
										</template>
										<template v-slot:[`item.amount`]="{ item }">
											${{ item.amount }}
										</template>
									</v-data-table>

									<v-data-table
										:headers="headers"
										:items="items"
										class="elevation-1 view-payment-table"
										v-if="isMobile"
										hide-default-footer
									>
										<template v-slot:[`item.name`]="{ item }">
											<div class="layout-flex">
												<div class="desc-wrapper">
													<p class="mb-0">{{ item.name }}</p>
													<p class="mb-0">{{ item.description }}</p>
													<p class="mb-0">{{ item.qty }} x {{ item.rate }}</p>
												</div>

												<div>
													<p class="mb-0">${{ item.amount }}</p>
												</div>
											</div>
										</template>
									</v-data-table>
								</div>

								<div class="total mb-0">
									<p class="balance">Total Amount</p>
									<p>${{ total_amount }}</p>
								</div>

								<div class="total mb-0">
									<p class="balance">Balance Due</p>
									<p>${{ balance_due }}</p>
								</div>
							</v-col>
						</v-row>

						<div
							class="view-payment-info"
							v-if="paymentData.paid_on && paymentData.payment_method"
						>
							<v-row class="row" v-if="paymentData.payment_status === 'Paid'">
								<v-col class="row-inline" cols="12" sm="8">
									<div class="card-name mb-2">
										<p class="card-title paid">Paid On</p>
										<span>{{ paymentData.paid_on }}</span>
									</div>

									<div class="card-name mb-2">
										<p class="card-title paid">Payment Method</p>
										<span>{{ paymentData.payment_method }}</span>
									</div>
								</v-col>
							</v-row>
						</div>
					</div>
				</div>
			</v-card-text>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "ViewPaymentDialog",
	props: [
		"dialog",
		"editedIndex",
		"editedItemData",
		"isMobile",
		"invoiceDetail",
		"getInvoiceDetailLoadingStatus",
		"viewitems",
		"userCanMakePayment",
	],
	components: {},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "billing");
	},
	data: () => ({
		headers: [
			{
				text: "ITEM & DESCRIPTION",
				align: "start",
				sortable: false,
				value: "name",
				fixed: true,
				width: "55%",
			},
			{
				text: "QTY",
				align: "end",
				sortable: false,
				value: "qty",
				fixed: true,
				width: "15%",
			},
			{
				text: "RATE",
				align: "end",
				sortable: false,
				value: "rate",
				fixed: true,
				width: "15%",
			},
			{
				text: "AMOUNT",
				align: "end",
				sortable: false,
				value: "amount",
				fixed: true,
				width: "15%",
			},
		],
	}),
	computed: {
		...mapGetters({
			getcards: "getcards",
			getcardsLoading: "getcardsLoading",
			getInvoiceDownloadLoadingStatus: "getInvoiceDownloadLoadingStatus",
		}),
		dialogView: {
			get() {
				return this.dialog;
			},
			set(value) {
				this.$emit("update:dialog", value);
			},
		},
		paymentData: {
			get() {
				return this.invoiceDetail;
			},
			set(value) {
				this.$emit("update:editedItemData", value);
			},
		},
		total_amount() {
			let total = 0;
			this.invoiceDetail.invoice_items &&
				this.invoiceDetail.invoice_items.forEach((item) => {
					total += parseInt(item.quantity) * parseFloat(item.rate);
				});
			/*this.invoiceDetail.schedule &&
				this.invoiceDetail.schedule.sell_rates &&
				this.invoiceDetail.schedule.sell_rates.forEach((item) => {
					total += parseInt(item.qty) * parseFloat(item.amount);
				});*/
			return total;
		},
		balance_due() {
			return this.invoiceDetail.balance;
		},
		items() {
			return (
				this.invoiceDetail.invoice_items &&
				this.invoiceDetail.invoice_items.map((item) => {
					return {
						name: item.qbo_service_name,
						description: item.description,
						qty: parseInt(item.quantity),
						rate: parseFloat(item.rate),
						amount: parseInt(item.quantity) * parseFloat(item.rate),
					};
				})
			);
			/*return (
				this.invoiceDetail.schedule &&
				this.invoiceDetail.schedule.sell_rates &&
				this.invoiceDetail.schedule.sell_rates.map((item) => {
					return {
						name: item.service_name,
						description: "",
						qty: parseInt(item.qty),
						rate: parseFloat(item.amount),
						amount: parseInt(item.qty) * parseFloat(item.amount),
					};
				})
			);*/
		},
	},
	watch: {},
	methods: {
		...mapActions({
			downloadInvoice: "downloadInvoice",
		}),
		...globalMethods,
		close() {
			this.$emit("close");
		},
		openPaymentDialog(item) {
			this.$emit("makePayment", item);
		},
		async download(item) {
			if (item?.id) {
				await this.downloadInvoice(item);
			} else if (Array.isArray(this.selected)) {
				this.selected.forEach(async (id) => {
					await this.downloadInvoice(id);
				});
			}
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/settings/viewPaymentDialog.scss";
@import "../../../assets/scss/buttons.scss";
</style>
