<!-- @format -->

<template>
	<v-dialog
		v-model="failedDialog"
		max-width="500px"
		content-class="item-delete-dialog"
		@click:outside="close"
	>
		<v-card class="delete-dialog">
			<v-card-title class="headline">
				<div class="delete-icon mt-3 mb-1">
					<img src="../../assets/icons/icon-delete.svg" alt="" />
				</div>
			</v-card-title>

			<v-card-text style="padding-bottom: 15px;">
				<h2>Verification Failed</h2>
				<p>
					<span
						>You cannot verify the card information. Please try again or try a
						different card.</span
					>
				</p>
			</v-card-text>

			<v-card-actions class="delete-btn-wrapper">
				<v-btn class="btn-blue" text @click="close">
					<span>Try Again</span>
				</v-btn>
				<v-btn class="btn-white" @click="openPaymentMethodDialog"
					>Try A Different Card</v-btn
				>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	name: "VerificationFailedDialog",
	props: ["failedDialog"],

	methods: {
		close() {
			this.$emit("close");
		},
		openPaymentMethodDialog() {
			this.$emit("openPaymentMethodDialog");
		},
	},
};
</script>

<style lang="scss">
@import "../../assets/scss/buttons.scss";
@import "../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../assets/scss/pages_scss/dialog/deleteDialog.scss";
</style>
