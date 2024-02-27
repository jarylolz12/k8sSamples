<!-- @format -->

<template>
	<v-dialog
		v-model="dialogShow"
		max-width="500px"
		content-class="item-delete-dialog"
		@click:outside="close"
	>
		<v-card class="delete-dialog">
			<v-card-title class="headline">
				<div class="delete-icon mt-3 mb-1">
					<img src="../../../assets/icons/icon-delete.svg" alt="" />
				</div>
			</v-card-title>

			<v-card-text style="padding-bottom: 15px;">
				<h2>No Payment Method</h2>
				<p>
					<span
						>You haven’t added any payment method yet. To make payment, please
						add a payment method first.</span
					>
				</p>
			</v-card-text>

			<v-card-actions class="delete-btn-wrapper">
				<router-link
					class="mr-2 text-decoration-none"
					to="/settings/?tab=manage-payment-methods"
					@click.native="setPageNumber"
					v-if="fromShipmentBill"
				>
					<v-btn class="btn-blue ">
						Add Payment Method
					</v-btn></router-link
				>
				<v-btn v-else class="btn-blue" text @click="handleManagePayment">
					<span>Add Payment Method</span>
				</v-btn>
				<v-btn class="btn-white" @click="close">Cancel</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	name: "NoPaymentdata",
	props: ["dialogShow", "fromShipmentBill"],

	methods: {
		close() {
			this.$emit("close");
		},
		handleManagePayment() {
			this.$router.push(`settings/?tab=manage-payment-methods`);
			this.$store.dispatch("page/setCurrentSettingsTab", 1);
		},
		setPageNumber() {
			this.$store.dispatch("page/setCurrentSettingsTab", 1);
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/buttons.scss";
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/pages_scss/dialog/deleteDialog.scss";
</style>
