<!-- @format -->
<template>
	<v-dialog
		v-model="paymentDetailsDialogView"
		max-width="800px"
		content-class="view-payment-details-dialog"
		:retain-focus="false"
		v-if="openPaymentDetailsDialogFlag"
	>
		<v-card>
			<v-card-title>
				<span class="headline"
					>Receipt #{{ paymentReceiptDetails.receipt_no }}</span
				>
				<div class="header-actions">
					<button
						class="btn btn-white"
						@click="download(paymentReceiptDetails)"
					>
						Download
					</button>
					<button icon dark class="btn-close" @click="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</div>
			</v-card-title>

			<v-card-text>
				<div class="view-payment-details-wrapper">
					<div class="view-payment-info">
						<v-row class="row-between">
							<v-col class="row-inline" cols="12" sm="12">
								<div class="card-name mb-4">
									<p class="card-title">Receipt No</p>
									<span>#{{ paymentReceiptDetails.receipt_no }}</span>
								</div>

								<div class="card-name mb-4">
									<p class="card-title">Date & Time</p>
									<span
										>{{ paymentReceiptDetails.transactionTime }},
										{{ paymentReceiptDetails.date_time }}</span
									>
								</div>

								<div class="card-name mb-4">
									<p class="card-title">Amount</p>
									<span class="amount">{{
										currencyNumberFormat(paymentReceiptDetails.amount)
									}}</span>
								</div>

								<div class="card-name mb-4">
									<p class="card-title">Payment Method</p>
									<span class="d-flex">
										<img
											v-if="paymentReceiptDetails.card_icon"
											:src="
												require(`@/assets/icons/${paymentReceiptDetails.card_icon}`)
											"
											width="30px"
											height="21.43px"
											alt=""
											class="mr-2"
										/>
										<div>
											<p class="mb-0">
												{{ paymentReceiptDetails.card_number }}
											</p>
											<p class="card-holder-name mb-0">
												{{ paymentReceiptDetails.card_holder_name }}
												{{
													paymentReceiptDetails.card_exp &&
													paymentReceiptDetails.card_exp != "-"
														? paymentReceiptDetails.card_exp
														: ""
												}}
											</p>
										</div>
									</span>
								</div>
							</v-col>
						</v-row>

						<v-row class="table-row">
							<v-col>
								<div class="view-table-wrapper">
									<v-data-table
										:headers="headers"
										:items="paymentReceiptDetails.invoice_list"
										class="elevation-1 view-payment-table"
										hide-default-footer
									>
										<template v-slot:[`item.invoice_num`]="{ item }">
											<a class="router-link-ui">{{ item.invoice_num }}</a>
										</template>
										<template v-slot:[`item.total_amount`]="{ item }">
											{{ currencyNumberFormat(item.total_amount) }}
										</template>
									</v-data-table>
								</div>
								<div
									class="total"
									v-if="paymentReceiptDetails.processingFee > 0"
								>
									<p class="balance">Processing Fee</p>
									<p class="balance-value">
										{{
											currencyNumberFormat(paymentReceiptDetails.processingFee)
										}}
									</p>
								</div>
								<div class="total">
									<p class="balance">Total Payment</p>
									<p class="balance-value">
										{{ currencyNumberFormat(paymentReceiptDetails.amount) }}
									</p>
								</div>
							</v-col>
						</v-row>
					</div>
				</div>
			</v-card-text>
		</v-card>
	</v-dialog>
</template>

<script>
import moment from "moment";
import globalMethods from "../../../utils/globalMethods";
export default {
	name: "ViewPaymentDetailsDialog",
	props: ["openPaymentDetailsDialogFlag", "paymentReceiptDetails"],
	data: () => ({
		headers: [
			{
				text: "INVOICE #",
				align: "start",
				sortable: false,
				value: "invoice_num",
				fixed: true,
				width: "80%",
			},
			{
				text: "AMOUNT",
				align: "end",
				sortable: false,
				value: "total_amount",
				fixed: true,
				width: "20%",
			},
		],
		invoices: [
			{
				invoiceNumber: 1234567890,
				invoiceAmount: 5087.0,
			},
			{
				invoiceNumber: 1234567890,
				invoiceAmount: 5087.0,
			},
		],
	}),
	computed: {
		paymentDetailsDialogView: {
			get() {
				return this.openPaymentDetailsDialogFlag;
			},
			set(value) {
				this.$emit("update:openPaymentDetailsDialogFlag", value);
			},
		},
	},
	methods: {
		...globalMethods,
		download(paymentData) {
			this.$emit("download", paymentData);
			this.$emit("close");
		},
		close() {
			this.$emit("close");
		},
		dateFormatChange(date) {
			return moment.utc(date).format("MMM DD, YYYY");
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/billing/paymentDetailsDialog.scss";
@import "../../../assets/scss/buttons.scss";
</style>
