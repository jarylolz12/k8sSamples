<!-- @format -->

<template>
	<v-dialog
		v-model="paymentsDialog"
		max-width="500px"
		content-class="item-delete-dialog"
		@click:outside="close"
	>
		<v-card class="delete-dialog">
			<v-card-title class="headline">
				<div class="delete-icon mt-3 mb-1">
					<img src="../../../assets/icons/check-payment-completed.svg" alt="" />
				</div>
			</v-card-title>

			<v-card-text style="padding-bottom: 15px;" v-if="editedItemData.clearAll">
				<h2>Payment Completed</h2>
				<p>
					<span>You have successfully cleared all your dues.</span>
				</p>
			</v-card-text>

			<v-card-text
				style="padding-bottom: 15px;"
				v-if="editedItemData.isMultiple"
			>
				<h2>Payment Completed</h2>
				<p>
					<span>You have successfully paid for Invoice</span>
					<span
						v-for="(multiplePaymentInvoice, i) in editedItemData.invoices"
						:key="i"
					>
						{{ multiplePaymentInvoice.invoice_number }},
					</span>
				</p>
			</v-card-text>

			<v-card-text style="padding-bottom: 15px;" v-if="editedItemData.isSingle">
				<h2>Payment Completed</h2>
				<p>
					<span>
						You have successfully paid for Invoice
						{{ editedItemData.invoice_number }} .
					</span>
				</p>
			</v-card-text>

			<v-card-actions class="delete-btn-wrapper">
				<v-btn class="btn-blue" @click="close">Done</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	name: "PaymentCompletedDialog",
	props: ["paymentsDialog", "editedItemData"],
	computed: {},
	methods: {
		close() {
			this.$emit("close");
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/buttons.scss";
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/pages_scss/dialog/deleteDialog.scss";
</style>
