<!-- @format -->

<template>
	<v-dialog
		v-model="dialogAdd"
		max-width="560px"
		content-class="add-payment-dialog"
		:retain-focus="false"
		persistent
	>
		<v-card>
			<v-form ref="form" action="#" @submit.prevent="">
				<v-card-title>
					<span class="headline">Add Payment Method</span>
					<button icon dark class="btn-close" @click="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>

				<v-card-text>
					<div class="add-payment-wrapper">
						<VCreditCard
							:noCard="this.showCardImage"
							:trans="translations"
							:yearDigits="4"
							@change="creditInfoChanged"
							:paymentData="this.paymentData"
							:cardEdit="this.cardEdit"
						/>

						<v-checkbox
							@change="defaultPaymentMethod($event)"
							class="payment-checkbox"
							label="Make it my default payment method"
							hide-details="auto"
						>
						</v-checkbox>
					</div>
				</v-card-text>

				<v-card-actions>
					<button
						class="btn-blue"
						@click="addPaymentMethod"
						:disabled="addMethodButtonDisabled || buttonTextAdding"
					>
						<span>
							<span v-if="!buttonTextAdding">Add Method</span>
							<span v-if="buttonTextAdding">Adding...</span>
						</span>
					</button>
					<button class="btn-white" @click="close">Cancel</button>
				</v-card-actions>
			</v-form>
		</v-card>
	</v-dialog>
</template>

<script>
import globalMethods from "../../../utils/globalMethods";
import VCreditCard from "../../VCreditCard/CreditCard.vue";

const translations = {
	name: {
		label: "Name on Card",
		placeholder: "Enter name",
	},
	card: {
		label: "Card Number",
		placeholder: "xxxx xxxx xxxx xxxx",
	},
	expiration: {
		label: "Expiry",
	},
	security: {
		label: "CVC",
		placeholder: "Enter CVC code",
	},
};

export default {
	name: "AddPaymentDialog",
	props: ["dialog", "addMethodButtonDisabled", "buttonTextAdding"],
	computed: {
		dialogAdd: {
			get() {
				return this.dialog;
			},
			set(value) {
				this.$emit("update:paymentMethodDialog", value);
			},
		},
	},
	components: {
		VCreditCard,
	},
	data: () => ({
		value: "1234432112344321",
		label: "Credit Card",
		focus: false,
		translations,
		showCardImage: true,
		cardEdit: false,
		paymentData: {},
		creditCardDetails: [],
	}),
	methods: {
		...globalMethods,
		close() {
			this.$refs.form.resetValidation();
			this.$emit("close");
		},
		addPaymentMethod() {
			this.$emit("savePaymentMethod", this.creditCardDetails);
		},
		creditInfoChanged(values) {
			this.$emit("isButtonDisabled", true);
			const name = values.name;
			const cardNumber = values.cardNumber.replace(/ /g, ""); //removed space
			const expiration = values.expiration.replace("/", ""); //remove slash
			const security = values.security;

			if (
				name != "" &&
				cardNumber != "" &&
				expiration != "" &&
				security != "" &&
				cardNumber.length >= 12 &&
				cardNumber.length <= 19 &&
				expiration.length == 6 &&
				(security.length == 3 || security.length == 4)
			) {
				this.$emit("isButtonDisabled", false);
				const expirationData = values.expiration.split("/");
				this.creditCardDetails["cardHolder_first_name"] = name;
				this.creditCardDetails["number"] = cardNumber;
				this.creditCardDetails["expiration_month"] = expirationData[0];
				this.creditCardDetails["expiration_year"] = expirationData[1];
				this.creditCardDetails["cv_data"] = security;
			}
			values.name = "";
			values.cardNumber = "";
			values.expiration = "";
			values.security = "";
		},
		defaultPaymentMethod(event) {
			this.creditCardDetails["is_default"] = event ? 1 : 0;
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/billing/addPaymentDialog.scss";
@import "../../../assets/scss/pages_scss/billing/vCreditCard.scss";
</style>
