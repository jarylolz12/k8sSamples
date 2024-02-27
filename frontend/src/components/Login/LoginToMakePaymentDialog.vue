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
					<img src="../../assets/icons/icon-delete.svg" alt="" />
				</div>
			</v-card-title>

			<v-card-text style="padding-bottom: 15px;">
				<h2>Log In To Make Payment</h2>
				<p>
					<span
						>If you want to make the payment, please log in to your Shifl
						account. You can request for a login credential if you are new to
						the platform.</span
					>
				</p>
			</v-card-text>

			<v-card-actions class="delete-btn-wrapper">
				<v-btn class="btn-blue" @click="close">
					Log In
				</v-btn>

				<v-btn class="btn-white" @click="handleRequestLoginCredentials"
					>Request Login Credentials</v-btn
				>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import globalMethods from "../../utils/globalMethods";
export default {
	name: "LoginToMakePaymentDialog",
	props: ["dialogShow", "statementId"],

	methods: {
		...globalMethods,
		...mapActions({
			requestLoginCredentials: "customers/requestLoginCredentials",
		}),
		close() {
			this.$emit("close");
		},
		async handleRequestLoginCredentials() {
			const data = await this.requestLoginCredentials({
				statementId: this.statementId,
			});
			if (data.data.status === 200) {
				this.$emit("close", true);
			} else {
				this.notificationError("Something went wrong, please try again later.");
				this.$emit("close");
			}
		},
	},
	computed: {
		...mapGetters({
			getRequestLoginCredentialsLoading:
				"customers/getRequestLoginCredentialsLoading",
		}),
	},
};
</script>

<style lang="scss">
@import "../../assets/scss/buttons.scss";
@import "../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../assets/scss/pages_scss/dialog/deleteDialog.scss";
</style>
