<!-- @format -->

<template>
	<div>
		<v-dialog
			v-model="dialog"
			max-width="560px"
			:retain-focus="false"
			@click:outside="close"
			content-class="make-payment-dialog"
		>
			<v-card>
				<v-form ref="form">
					<v-card-title>
						<span class="headline">Make Payment</span>
						<v-btn icon dark class="btn-close" type="button" @click="close">
							<v-icon>mdi-close</v-icon>
						</v-btn>
					</v-card-title>

					<v-card-text class="pt-5">
						<v-sheet v-if="getcardsLoading" class="text-center mt-4">
							<v-progress-circular :size="40" color="#0171a1" indeterminate>
							</v-progress-circular>
							<p class="k-mt-8 pt-3">Fetching payment methods...</p>
						</v-sheet>

						<v-sheet v-else>
							<v-row
								v-if="!isMultipleInvoice && !isClearAllDue && invoiceData"
								class=""
							>
								<v-col cols="12" sm="6" :class="!isMobile ? '' : 'pb-0'">
									<div class="d-flex">
										<p class="text-muted mr-1">Invoice #</p>
										<span class="make-payment-data">{{
											invoiceData.invoice_number
										}}</span>
									</div>

									<div class="d-flex">
										<p class="text-muted mr-1">Date</p>
										<span class="make-payment-data">{{
											monthDayYearFormat(invoiceData.invoice_date)
										}}</span>
									</div>

									<div class="d-flex">
										<p class="text-muted mr-1">Reference</p>
										<span class="make-payment-data">{{
											invoiceData.shipment_reference_number
										}}</span>
									</div>
								</v-col>

								<v-col cols="12" sm="6" :class="!isMobile ? '' : 'pt-0'">
									<div class="d-flex">
										<p class="text-muted mr-1">Due Date</p>
										<span class="make-payment-data">{{
											monthDayYearFormat(invoiceData.due_date)
										}}</span>
									</div>

									<div class="d-flex">
										<p class="text-muted mr-1">Amount</p>
										<span class="amount make-payment-data">
											{{
												currencyNumberFormat(
													twoDecimalFormat(invoiceData.balance)
												)
											}}
										</span>
									</div>
								</v-col>
							</v-row>

							<v-row v-if="isMultipleInvoice" class="">
								<v-col cols="12">
									<div class="d-flex">
										<p class="text-muted multiple mr-1">Invoice #</p>
										<span class="make-payment-data">
											{{
												invoiceData
													.map((invoice) => invoice.invoice_number)
													.flat()
													.join(", ")
											}}
										</span>
									</div>

									<div class="d-flex">
										<p class="text-muted multiple mr-1">Amount</p>
										<span class="make-payment-data">
											{{
												currencyNumberFormat(
													twoDecimalFormat(
														invoiceData.reduce(
															(c, n) => c + Number(n.balance),
															0
														)
													)
												)
											}}
										</span>
									</div>
								</v-col>
							</v-row>

							<v-row v-if="isClearAllDue">
								<v-col cols="12" sm="6">
									<div class="d-flex">
										<p class="text-muted mr-1">Amount</p>
										<span class="amount make-payment-data">
											{{ currencyNumberFormat(twoDecimalFormat(invoiceData)) }}
										</span>
									</div>
								</v-col>
							</v-row>

							<v-divider class="payment-divider-wrapper mb-4"></v-divider>

							<v-row>
								<v-col cols="12">
									<div class="card-name">
										<p class="card-title text-muted">Payment Method</p>
										<v-select
											v-model="card_id"
											class="text-fields select-items"
											:items="cards"
											item-text="number_masked"
											item-value="id"
											placeholder="Select Payment Method"
											height="45px"
											dense
											outlined
											:rules="rules"
											hide-details="auto"
											prepend-inner="button"
											:menu-props="{ bottom: true, offsetY: true }"
										>
											<template v-slot:append-item>
												<v-divider class="mb-2"></v-divider>
												<v-list-item>
													<v-list-item-content>
														<v-list-item-title>
															<a
																@click="handleManagePayment"
																class="d-flex align-center text-decoration-none"
															>
																<img
																	src="../../../assets/icons/plus.svg"
																	class="mr-2"
																	width="12px"
																	height="12px"
																	alt=""
																/>
																<span>New Payment Method</span>
															</a>
														</v-list-item-title>
													</v-list-item-content>
												</v-list-item>
											</template>
										</v-select>
									</div>
								</v-col>

								<v-col v-show="getErrorMessage == true" cols="12" sm="12">
									<label>
										<div
											class="red lighten-5 caption red--text d-flex align-center pa-2"
										>
											<div class="d-flex">
												<img
													src="../../../assets/icons/alert.svg"
													alt=""
													width="16px"
													height="16px"
													class="mr-3"
												/>
											</div>

											<span>
												Sorry! We could not complete the payment process. Please
												try again or try a different payment method.
											</span>
										</div>
									</label>
								</v-col>
							</v-row>
						</v-sheet>
					</v-card-text>

					<v-card-actions>
						<button
							class="btn-blue"
							@click.prevent="save(invoiceData)"
							v-if="!Array.isArray(invoiceData) && !isNumeric(invoiceData)"
							:disabled="getPaymentLoading === true"
						>
							<span v-if="!getPaymentLoading">Make Payment</span>
							<span v-if="getPaymentLoading">Processing...</span>
						</button>

						<button
							class="btn-blue"
							@click.prevent="multipleInvoicesPayment(invoiceData)"
							v-if="Array.isArray(invoiceData)"
							:disabled="getMultiplePaymentLoading === true"
						>
							<span v-if="!getMultiplePaymentLoading">Make Payment</span>
							<span v-if="getMultiplePaymentLoading">Processing...</span>
						</button>

						<button
							class="btn-blue"
							@click.prevent="clearAllduePayment(invoiceData)"
							v-if="isNumeric(invoiceData)"
							:disabled="getMultiplePaymentLoading === true"
						>
							<span v-if="!getMultiplePaymentLoading">Make Payment</span>
							<span v-if="getMultiplePaymentLoading">Processing...</span>
						</button>

						<button
							class="btn-white"
							@click.prevent="close"
							:disabled="getPaymentLoading === true"
						>
							Cancel
						</button>
					</v-card-actions>
				</v-form>
			</v-card>
		</v-dialog>

		<v-snackbar v-model="snackbar" :timeout="5000">
			Paid invoices are excluded from the selection.
		</v-snackbar>
	</div>
</template>

<script>
import globalMethods from "../../../utils/globalMethods";
import { mapActions, mapGetters } from "vuex";
import moment from "moment";

export default {
	name: "MakePaymentDialog",
	props: ["open", "invoiceData", "isMobile"],
	data: () => ({
		rules: [(v) => !!v || "Input is required."],
		getErrorMessage: false,
		card_id: null,
		default_customer_id: 0,
	}),
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "shipments");
	},
	computed: {
		...mapGetters([
			"getUser",
			"getcards",
			"getcardsLoading",
			"getPaymentLoading",
			"getMultiplePaymentLoading",
		]),
		dialog: {
			get() {
				return this.open;
			},
			set() {
				this.$emit("close");
			},
		},
		cards() {
			const paymentCard = this.getcards.map((item) => {
				return { id: item.id, number_masked: item.number_masked };
			});
			return paymentCard;
		},
		isMultipleInvoice() {
			return Array.isArray(this.invoiceData);
		},
		isClearAllDue() {
			return !isNaN(this.invoiceData);
		},
		snackbar: {
			get() {
				return this.isMultipleInvoice && this.open;
			},
			set(value) {
				return value;
			},
		},
	},
	watch: {
		dialog(isOpened) {
			if (isOpened) {
				this.fetchPaymentMethod();
			}
		},
	},
	methods: {
		...globalMethods,
		...mapActions(["makePayment", "multipleInvoicePayments"]),
		close() {
			this.dialog = false;
			this.card_id = null;
		},
		fetchPaymentMethod() {
			this.default_customer_id = this.defaultCustomerId();
		},
		handleManagePayment() {
			this.$router.push(`/settings/?tab=manage-payment-methods`);
			this.$store.dispatch("page/setCurrentSettingsTab", 1);
		},
		async multipleInvoicesPayment(item) {
			let isMultipleInvoices = Array.isArray(item);
			if (isMultipleInvoices) {
				let payableAmount = 0;
				item.forEach((item) => {
					const itemAmount = parseFloat(item.amount);
					return (payableAmount += itemAmount);
				});

				let ids = item.map((item) => item.id);
				let multipleInvoiceDatas = {
					card_id: this.card_id,
					all: false,
					invoice_ids: ids,
					default_customer_id: this.default_customer_id,
				};
				const response = await this.multipleInvoicePayments(
					multipleInvoiceDatas
				);

				if (response && response.data && response.data.status === "success") {
					setTimeout(() => {
						this.$emit("paymentDialog", {
							invoices: [...item],
							paid: true,
							isMultiple: true,
							isSingle: false,
							clearAll: false,
						});
					}, 1000);
					this.close(payableAmount);
				} else {
					setTimeout(() => {
						this.getErrorMessage = true;
					}, 1000);
				}
			}
		},
		async clearAllduePayment(item) {
			if (this.isNumeric(item)) {
				let clearAllDue = {
					all: true,
					card_id: this.card_id,
					default_customer_id: this.default_customer_id,
				};

				const response = await this.multipleInvoicePayments(clearAllDue);
				if (response && response.data && response.data.status === "success") {
					setTimeout(() => {
						this.$emit("paymentDialog", {
							amount: this.currencyNumberFormat(this.twoDecimalFormat(item)),
							paid: true,
							isMultiple: false,
							isSingle: false,
							clearAll: true,
						});
					}, 1000);
					this.close(this.twoDecimalFormat(item));
				} else {
					setTimeout(() => {
						this.getErrorMessage = true;
					}, 1000);
				}
			}
		},
		async save(item) {
			this.$refs.form.validate();
			if (this.card_id && this.invoiceData.id) {
				const parms = {
					card_id: this.card_id,
					default_customer_id: this.default_customer_id,
					invoice_id: this.invoiceData.id,
				};

				const response = await this.makePayment(parms);
				if (response && response.data && response.data.status === "success") {
					setTimeout(() => {
						this.$emit("paymentDialog", {
							...item,
							paid: true,
							isMultiple: false,
							isSingle: true,
							clearAll: false,
						});
					}, 1000);
					this.close(this.invoiceData.amount);
				} else {
					setTimeout(() => {
						this.getErrorMessage = true;
					}, 1000);
				}
			}
		},
		monthDayYearFormat(date) {
			return moment.utc(date).format("MMM DD, YYYY");
		},
	},
};
</script>

<style lang="scss" scoped>
$text-muted: #819fb2;

.text-muted {
	color: $text-muted;
	font-size: 10px;
	text-transform: uppercase;
	font-family: "Inter-SemiBold", sans-serif;
	width: 30%;
	margin-bottom: 10px !important;

	&.multiple {
		width: 15%;
	}
}

.make-payment-data {
	color: #4a4a4a;
}

.payment-divider-wrapper {
	border-color: #ebf2f5 !important;
	margin-top: 10px;
}
</style>

<style lang="scss">
@import "../../../assets/scss/pages_scss/settings/makePaymentDialog.scss";
</style>
