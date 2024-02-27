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
					<img
						src="../../assets/icons/circle-products-filled.svg"
						alt=""
						width="32"
					/>
				</div>
			</v-card-title>

			<v-card-text style="padding-bottom: 15px;">
				<h2>Request Sent</h2>
				<p>
					<span
						>Your request for login credential has been sent to the concerned
						team. They will reach out to you within next 48 Hours.</span
					>
				</p>
			</v-card-text>

			<v-card-actions class="delete-btn-wrapper">
				<v-btn class="btn-blue" @click="close">
					Close
				</v-btn>

				<v-btn class="btn-white" @click="close">Back To Login</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	name: "LoginCredentialsRequestSentDialog",
	props: ["dialogShow"],

	methods: {
		close() {
			this.$emit("close");
		},
	},
};
</script>

<style lang="scss">
@import "../../assets/scss/buttons.scss";
@import "../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../assets/scss/pages_scss/dialog/deleteDialog.scss";
</style>
