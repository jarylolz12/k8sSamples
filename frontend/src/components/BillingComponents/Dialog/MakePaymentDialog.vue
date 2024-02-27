<!-- @format -->

<template>
	<div>
		<v-dialog
			v-model="dialogAdd"
			max-width="960px"
			content-class="make-payment-dialog"
			:retain-focus="false"
			@click:outside="close"
		>
			<v-card>
				<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
					<v-card-text>
						<div class="make-payment-wrapper">
							<v-col
								class="d-flex justify-space-between align-center mt-0"
								v-if="isMobile"
							>
								<span class="headline">Make Payment</span>
								<button icon dark class="btn-close" @click="close">
									<v-icon>mdi-close</v-icon>
								</button>
							</v-col>
							<div class="make-payment-info">
								<div class="invoice-details">
									<h2 class="invoice-title">
										Invoice items
										<span
											v-if="Array.isArray(editedItemData)"
											class="count-invoice"
											>({{ editedItemData.length }} Invoices)</span
										>
									</h2>

									<v-row
										class="row-inline ma-0"
										v-if="
											!Array.isArray(editedItemData) &&
												!isNumeric(editedItemData)
										"
									>
										<v-col cols="12" sm="12" class="card-section">
											<div class="card-name">
												<p class="card-title">Reference</p>
												<span>{{
													editedItemData.shipment_reference_number
												}}</span>
											</div>

											<div class="card-name">
												<p class="card-title">Date</p>
												<span>{{
													editedItemData.date
														? editedItemData.date
														: editedItemData.invoice_date
												}}</span>
											</div>

											<div class="card-name">
												<p class="card-title">Due on</p>
												<span>{{ editedItemData.due_date }}</span>
											</div>
										</v-col>
									</v-row>

									<v-row
										class="row-inline ma-0"
										v-if="Array.isArray(editedItemData)"
									>
										<v-col cols="12" sm="12" class="multiple-invoice">
											<div class="card-name flex-wrap mb-2">
												<span
													class="pr-2 chips"
													v-for="(multiplePaymentInvoice, i) in editedItemData"
													:key="i"
												>
													<v-chip class="invoice"
														>{{ multiplePaymentInvoice.invoice_number }}
														<v-icon>mdi-open-in-new </v-icon>
													</v-chip>
												</span>
											</div>
										</v-col>
									</v-row>

									<v-row
										class="row-inline ma-0"
										v-if="isNumeric(editedItemData)"
									>
										<v-col cols="12" sm="12">
											<div class="card-name mb-2">
												<p class="card-title multiple">Balance Due</p>
												<span class="amount ml-2">{{
													currencyNumberFormat(twoDecimalFormat(editedItemData))
												}}</span>
											</div>
										</v-col>
									</v-row>

									<v-row class="ma-0 flex-column invoice-items-wrapper">
										<v-col class="px-0 pt-3">
											<div class="invoice-table-details-wrapper">
												<v-simple-table dense class="invoice-details-wrapper">
													<template v-slot:default>
														<thead>
															<tr>
																<th class="text-start text-header">
																	Particulars
																</th>
																<th class="text-end text-header">
																	Amount
																</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="subtotal">Subtotal</td>
																<td>
																	{{ currencyNumberFormat(countSubTotal()) }}
																</td>
															</tr>
															<tr>
																<td>
																	<div class="duty-fees">
																		<v-container fluid class="pa-0">
																			<v-checkbox
																				class="ma-0 pa-0"
																				label="Duties"
																				:value="includingDutyFees"
																				v-model="includingDutyFees"
																				hide-details
																			></v-checkbox>
																		</v-container>
																	</div>
																</td>
																<td
																	:class="
																		!includingDutyFees
																			? 'text-decoration-line-through'
																			: ''
																	"
																>
																	{{ currencyNumberFormat(countDuties()) }}
																</td>
															</tr>
															<tr v-if="includingDutyFees">
																<td class="processing-fee-tooltip">
																	Processing Fee
																	<v-tooltip right>
																		<template v-slot:activator="{ on, attrs }">
																			<v-icon
																				color="grey-lighten-1"
																				v-bind="attrs"
																				v-on="on"
																			>
																				mdi-information-outline
																			</v-icon>
																		</template>
																		3.2% Processing Fee Will Be Charged For CC
																		payments
																	</v-tooltip>
																	<span class="processing-fee"
																		>3.2% of $100 (For paying with Credit
																		Card)</span
																	>
																</td>
																<td>
																	{{
																		paymentMethodsSelected !== "ACH"
																			? currencyNumberFormat(
																					countProcessingFee()
																			  )
																			: currencyNumberFormat(0)
																	}}
																</td>
															</tr>
														</tbody>
													</template>
												</v-simple-table>
											</div>

											<div class="total-payments pb-0">
												<p class="mb-0">Total Payment</p>
												<p class="mb-0">
													<span
														v-if="
															!Array.isArray(editedItemData) &&
																!isNumeric(editedItemData)
														"
													>
														{{
															currencyNumberFormat(
																totalPaymentAmount(editedItemData)
															)
														}}</span
													>
													<span
														class="amount ml-2"
														v-if="Array.isArray(editedItemData)"
													>
														{{
															currencyNumberFormat(
																totalPaymentAmount(multiplePaymentData)
															)
														}}
													</span>
													<span
														class="amount ml-2"
														v-if="isNumeric(editedItemData)"
														>{{
															currencyNumberFormat(
																totalPaymentAmount(editedItemData)
															)
														}}</span
													>
												</p>
											</div>
										</v-col>
									</v-row>
								</div>

								<div class="payment-details">
									<v-row>
										<v-col
											class="d-flex justify-space-between align-center payment-title"
											v-if="!isMobile"
										>
											<span class="headline">Make Payment</span>
											<button icon dark class="btn-close" @click="close">
												<v-icon>mdi-close</v-icon>
											</button>
										</v-col>

										<v-col cols="12" class="payment-info-details">
											<div class="card-name mb-4">
												<p>Payment Method</p>
												<v-radio-group
													v-model="paymentMethodsSelected"
													row
													class="mt-0"
													@change="paymentMethodSelectFunc"
												>
													<v-radio
														value="CC"
														label="Credit Card"
														class="v-radio-payment-method-label tab-pm-cc"
													></v-radio>
													<v-radio
														label="ACH Account"
														value="ACH"
														class="v-radio-payment-method-label tab-pm-ach"
													></v-radio>
												</v-radio-group>
												<div v-if="paymentMethodsSelected !== 'ACH'">
													<p class="card-title payment-method-select-title">
														Select Card
													</p>
													<v-select
														v-model="card_id"
														class="text-fields select-items"
														:items="items"
														item-text="number_masked"
														item-value="id"
														placeholder="Select Payment Method"
														height="45px"
														dense
														outlined
														hide-details="auto"
														prepend-inner="button"
														:menu-props="{ bottom: true, offsetY: true }"
														@change="paymentMethodSelectFunc"
													>
														<template v-slot:selection="{ item }">
															<img
																width="28"
																height="20"
																:src="getImgUrl(item.card_type)"
																class="pm-selection-cc-img"
															/>{{ item.number_masked }}
														</template>
														<template v-slot:item="{ item }">
															<img
																width="28"
																height="20"
																:src="getImgUrl(item.card_type)"
																class="pm-selection-cc-img"
															/>{{ item.number_masked }}
														</template>
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
												<div v-else>
													<p class="card-title payment-method-select-title">
														Select Account
													</p>
													<v-select
														v-model="ACH_id"
														class="text-fields select-items"
														:items="ACHItems"
														item-text="name"
														item-value="id"
														placeholder="Select ACH Payment Method"
														height="45px"
														dense
														outlined
														hide-details="auto"
														prepend-inner="button"
														:menu-props="{ bottom: true, offsetY: true }"
														@change="paymentMethodSelectFunc"
													>
														<template v-slot:selection="{ item }">
															{{ item.name + " (" + item.routing + ")" }}
														</template>
														<template v-slot:item="{ item }">
															<div class="ach-pm-selection mt-4">
																<p class="account-holder-name mb-1">
																	{{ item.name }}
																</p>
																<p class="account-details">
																	Account:
																	{{ item.account }}
																</p>
															</div>
														</template>
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
											</div>
										</v-col>

										<v-col
											class="d-flex align-center justify-space-between action-button"
										>
											<div class="payments-button d-flex align-center">
												<button
													class="btn-blue mr-2"
													@click="save(editedItemData)"
													v-if="
														!Array.isArray(editedItemData) &&
															!isNumeric(editedItemData)
													"
													:disabled="
														getPaymentLoading === true ||
															makePaymentSingleDisable ||
															btnDisabled
													"
												>
													<span v-if="!getPaymentLoading">Make Payment</span>
													<span v-if="getPaymentLoading">Processing...</span>
												</button>

												<button
													class="btn-blue mr-2"
													@click="multipleInvoicesPayment(editedItemData)"
													v-if="Array.isArray(editedItemData)"
													:disabled="
														getPaymentLoading === true ||
															makePaymentSingleDisable ||
															btnDisabled
													"
												>
													<span v-if="!getPaymentLoading">Make Payment</span>
													<span v-if="getPaymentLoading">Processing...</span>
												</button>

												<button
													class="btn-blue mr-2"
													@click="clearAllduePayment(editedItemData)"
													v-if="isNumeric(editedItemData)"
													:disabled="
														getPaymentLoading === true ||
															makePaymentSingleDisable ||
															btnDisabled
													"
												>
													<span v-if="!getPaymentLoading">Make Payment</span>
													<span v-if="getPaymentLoading">Processing...</span>
												</button>

												<button
													class="btn-white"
													@click="close"
													:disabled="getPaymentLoading === true"
												>
													Cancel
												</button>
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
													<span
														>Sorry! We could not complete the payment process.
														Please try again or try a different payment
														method.</span
													>
												</div>
											</label>
										</v-col>
									</v-row>
								</div>
							</div>
						</div>
					</v-card-text>
				</v-form>
			</v-card>
		</v-dialog>
		<v-snackbar v-model="snackbarFlag" :timeout="snackbarTimeout">
			Paid invoices are excluded from the selection.
		</v-snackbar>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "MakePaymentDialog",
	props: [
		"dialog",
		"editedItemData",
		"getcards",
		"paymentDialog",
		"default_customer_id",
		"isMobile",
		"fromComponent",
	],
	components: {},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "billing");
		this.$refs.form && this.$refs.form.reset();
		this.paymentMethodSelectFunc();
	},
	data: () => ({
		card_id: 0,
		ACH_id: 0,
		valid: true,
		getErrorMessage: false,
		snackbarTimeout: 5000,
		snackbarFlag: false,
		paymentMethodsSelected: "CC",
		makePaymentSingleDisable: true,
		makePaymentMultipleDisable: true,
		makePaymentClearAllDisable: true,
		includingDutyFees: false,
		btnDisabled: false,
	}),
	computed: {
		...mapGetters([
			"getPaymentLoading",
			"getMultiplePaymentLoading",
			"getAllInvoices",
			"getShipmentBills",
		]),
		dialogAdd: {
			get() {
				return this.dialog;
			},
			set(value) {
				this.$emit("update:dialog", value);
			},
		},
		items() {
			if (this.getcards.cards !== undefined) {
				const paymentCard = this.getcards.cards.map((item) => {
					return {
						id: item.id,
						number_masked: this.ccNumberFormat(
							item.number_masked,
							"************"
						),
						card_type: item.type.toLowerCase(),
					};
				});
				return paymentCard;
			} else {
				return false;
			}
		},
		ACHItems() {
			if (this.getcards.ACHMethods !== undefined) {
				const ACHpaymentCard = this.getcards.ACHMethods.map((item) => {
					return {
						id: item.id,
						name: item.name,
						routing: this.achRouteFormat(item.routing, "*****"),
						account: this.achAccountFormat(item.account, "*****"),
					};
				});
				return ACHpaymentCard;
			} else {
				return false;
			}
		},
		multiplePaymentData() {
			let isMultiple = Array.isArray(this.editedItemData);
			let multipleInvoiceData = {
				invoice_names: " ",
				total_amount: 0,
				total_balance_due: 0,
				total_duty_amount: this.countDuties(),
				processing_fee: this.countProcessingFee(),
			};

			if (isMultiple) {
				this.editedItemData.forEach((item) => {
					multipleInvoiceData.invoice_names = `${multipleInvoiceData.invoice_names} ,${item.invoice_number}`;
					multipleInvoiceData.total_amount = this.twoDecimalFormat(
						Number(multipleInvoiceData.total_amount) + Number(item.amount)
					);
					multipleInvoiceData.total_balance_due = this.twoDecimalFormat(
						Number(multipleInvoiceData.total_balance_due) + Number(item.balance)
					);
				});
			}
			return multipleInvoiceData;
		},
		snackbarFlagAction() {
			return Array.isArray(this.editedItemData);
		},
	},
	watch: {
		snackbarFlag: function() {
			this.snackbarFlag;
		},
		snackbarFlagAction(newValue) {
			this.snackbarFlag = newValue;
		},

		paymentMethodsSelected: function() {
			if (
				this.paymentMethodsSelected == null ||
				this.paymentMethodsSelected == "" ||
				this.paymentMethodsSelected == undefined
			) {
				this.paymentMethodsSelected = "CC";
				this.card_id = 0;
				this.ACH_id = 0;
			} else {
				this.paymentMethodsSelected;
			}
		},
		paymentMethodsSelectedAction(newValue) {
			this.paymentMethodsSelected = newValue;
		},
	},
	methods: {
		...mapActions({
			makePayment: "makePayment",
			fetchInvoices: "fetchInvoices",
		}),
		...globalMethods,
		onResize() {
			this.mobile = window.innerWidth < 769;
		},
		close(paidAmount) {
			this.$refs.form.resetValidation();
			this.$emit("close", paidAmount);
			this.getErrorMessage = false;
			this.makePaymentSingleDisable = true;
			this.paymentMethodsSelected = "CC";
			this.ACH_id = 0;
			this.card_id = 0;
			this.includingDutyFees = false;
		},
		handleManagePayment() {
			this.$router.push(`settings/?tab=manage-payment-methods`);
			this.$store.dispatch("page/setCurrentSettingsTab", 1);
		},
		async multipleInvoicesPayment(item) {
			let isMultipleInvoices = Array.isArray(item);
			if (isMultipleInvoices) {
				let payableAmount = 0;
				item.forEach((item) => {
					const itemAmount = parseFloat(item.balance);
					return (payableAmount += itemAmount);
				});

				let ids = item.map((item) => item.id);

				const parms = {
					card_id: this.card_id,
					ACH_id: this.ACH_id,
					default_customer_id: this.default_customer_id,
					invoice_ids: ids,
					all: false,
					process_duty: this.includingDutyFees ? true : false,
				};
				const response = await this.makePayment(parms);
				if (response && response.data && response.data.status === "success") {
					this.fetchInvoices();
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
				const parms = {
					card_id: this.card_id,
					ACH_id: this.ACH_id,
					default_customer_id: this.default_customer_id,
					invoice_ids: true,
					all: true,
					process_duty: this.includingDutyFees ? true : false,
				};

				const response = await this.makePayment(parms);
				if (response && response.data && response.data.status === "success") {
					this.fetchInvoices();
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
			if (this.editedItemData.id) {
				const invoice_ids = [this.editedItemData.id];
				const parms = {
					card_id: this.card_id,
					ACH_id: this.ACH_id,
					default_customer_id: this.default_customer_id,
					invoice_ids: invoice_ids,
					all: false,
					process_duty: this.includingDutyFees ? true : false,
				};

				const response = await this.makePayment(parms);
				if (response && response.data && response.data.status === "success") {
					this.fetchInvoices();
					setTimeout(() => {
						this.$emit("paymentDialog", {
							...item,
							paid: true,
							isMultiple: false,
							isSingle: true,
							clearAll: false,
						});
					}, 1000);
					this.close(
						this.editedItemData.balance !== undefined &&
							this.editedItemData.balance != null
							? this.editedItemData.balance
							: 0
					);
				} else {
					setTimeout(() => {
						this.getErrorMessage = true;
					}, 1000);
				}
			}
		},
		paymentMethodSelectFunc() {
			this.makePaymentSingleDisable = true;
			if (this.paymentMethodsSelected == "CC" && this.card_id > 0) {
				this.makePaymentSingleDisable = false;
				this.ACH_id = 0;
			}
			if (this.paymentMethodsSelected == "ACH" && this.ACH_id > 0) {
				this.makePaymentSingleDisable = false;
				this.card_id = 0;
			}
		},
		getImgUrl(pic) {
			if (pic !== undefined && pic !== null) {
				return require(`../../../assets/icons/${pic}.svg`);
			} else {
				return require("../../../assets/icons/payment-icon.svg");
			}
		},
		countSubTotal() {
			let subTotalAmount = 0;
			if (
				!Array.isArray(this.editedItemData) &&
				!this.isNumeric(this.editedItemData)
			) {
				subTotalAmount =
					parseFloat(this.editedItemData.balance) -
					parseFloat(this.editedItemData.total_duty_amount);
			} else if (Array.isArray(this.editedItemData)) {
				subTotalAmount == 0 &&
					this.editedItemData.forEach((item) => {
						subTotalAmount += parseFloat(item.balance);
					});
				let duties = this.countDuties();

				subTotalAmount = subTotalAmount - duties;
			} else if (this.isNumeric(this.editedItemData)) {
				let invoices =
					this.fromComponent === "Billing"
						? this.getAllInvoices
						: this.getShipmentBills;
				subTotalAmount === 0 &&
					invoices.forEach((item) => {
						item.paid === false
							? (subTotalAmount += parseFloat(item.balance))
							: 0;
					});
				subTotalAmount = subTotalAmount - this.countDuties();
			}
			return subTotalAmount;
		},
		countDuties() {
			let totalDutyAmount = 0;
			if (
				!Array.isArray(this.editedItemData) &&
				!this.isNumeric(this.editedItemData)
			) {
				totalDutyAmount = this.editedItemData.total_duty_amount;
			} else if (Array.isArray(this.editedItemData)) {
				totalDutyAmount == 0 &&
					this.editedItemData.forEach((item) => {
						totalDutyAmount += parseFloat(item.total_duty_amount);
					});
			} else if (this.isNumeric(this.editedItemData)) {
				let invoices =
					this.fromComponent === "Billing"
						? this.getAllInvoices
						: this.getShipmentBills;
				totalDutyAmount === 0 &&
					invoices.forEach((item) => {
						item.paid === false
							? (totalDutyAmount += parseFloat(item.total_duty_amount))
							: 0;
					});
			}
			return totalDutyAmount;
		},
		countProcessingFee() {
			let totalProcessingFee = 0;
			if (
				!Array.isArray(this.editedItemData) &&
				!this.isNumeric(this.editedItemData)
			) {
				totalProcessingFee = this.editedItemData.processing_fee;
			} else if (Array.isArray(this.editedItemData)) {
				totalProcessingFee == 0 &&
					this.editedItemData.forEach((item) => {
						totalProcessingFee += parseFloat(item.processing_fee);
					});
			} else if (this.isNumeric(this.editedItemData)) {
				let invoices =
					this.fromComponent === "Billing"
						? this.getAllInvoices
						: this.getShipmentBills;
				totalProcessingFee === 0 &&
					invoices.forEach((item) => {
						item.paid === false
							? (totalProcessingFee += parseFloat(item.processing_fee))
							: 0;
					});
			}
			return totalProcessingFee;
		},
		totalPaymentAmount(item) {
			let totalPaymentAmount = 0;
			if (this.includingDutyFees) {
				if (!this.isNumeric(item)) {
					let processingFee =
						this.paymentMethodsSelected !== "ACH" ? item.processing_fee : 0;
					totalPaymentAmount =
						parseFloat(processingFee) +
						parseFloat(item.total_duty_amount) +
						this.countSubTotal();
				} else {
					let processingFee =
						this.paymentMethodsSelected !== "ACH"
							? this.countProcessingFee()
							: 0;
					totalPaymentAmount =
						parseFloat(processingFee) +
						this.countDuties() +
						this.countSubTotal();
				}
			} else {
				totalPaymentAmount = this.countSubTotal();
			}
			this.btnDisabled = totalPaymentAmount === 0 ? true : false;
			return totalPaymentAmount;
		},
	},
	created() {
		this.snackbarFlag = this.snackbarFlagAction;
		this.paymentMethodsSelected = this.paymentMethodsSelectedAction;
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/settings/makePaymentDialog.scss";
</style>
