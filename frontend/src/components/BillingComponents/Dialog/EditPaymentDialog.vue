<!-- @format -->

<template>
	<v-dialog
		v-model="dialogAdd"
		max-width="560px"
		content-class="add-payment-dialog"
		:retain-focus="false"
		@click:outside="close"
	>
		<v-card>
			<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
				<v-card-title>
					<span class="headline">{{ formTitle }}</span>
					<button icon dark class="btn-close" @click="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>

				<v-card-text>
					<div class="add-payment-wrapper">
						<div class="add-payment-info">
							<VCreditCard
								:noCard="this.showCardImage"
								:trans="translations"
								:yearDigits="4"
								@change="creditInfoChanged"
								:paymentData="this.paymentData"
								:cardEdit="this.cardEdit"
							/>
						</div>

						<v-checkbox
							class="payment-checkbox"
							v-model="paymentData.default"
							label="Make it my default payment method"
							hide-details="auto"
						>
						</v-checkbox>
					</div>
				</v-card-text>

				<v-card-actions>
					<button
						class="btn-blue"
						@click="save"
						:disabled="getUpdateCardLoading"
					>
						<span>
							<span v-if="!getUpdateCardLoading">Update Method</span>
							<span v-if="getUpdateCardLoading">Updating...</span>
						</span>
					</button>
					<button class="btn-white" @click="close">Cancel</button>
				</v-card-actions>
			</v-form>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
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
	props: ["editDialog", "editedIndex", "editedItemData", "collect"],
	components: {
		VCreditCard,
	},
	data: () => ({
		valid: true,
		makeDefault: false,
		rules: [(v) => !!v || "Input is required."],
		max: 5,
		translations,
		showCardImage: true,
		cardEdit: true,
	}),
	computed: {
		...mapGetters({
			getCountries: "warehouse/getCountries",
			getCountriesLoading: "warehouse/getCountriesLoading",
			updateCard: "getUpdateCard",
			getUpdateCardLoading: "getUpdateCardLoading",
		}),
		formTitle() {
			return this.editedIndex === -1 ? "Add Payment" : "Edit Payment";
		},
		dialogAdd: {
			get() {
				return this.editDialog;
			},
			set(value) {
				this.$emit("update:editDialog", value);
			},
		},
		paymentData: {
			get() {
				return this.editedItemData;
			},
			set(value) {
				this.$emit("update:editedItemData", value);
			},
		},
		countries() {
			return typeof this.getCountries !== "undefined" &&
				this.getCountries !== null &&
				this.getCountries.length !== 0
				? this.getCountries
				: [];
		},
		isValid() {
			let valid = true;
			if (
				this.paymentData.card_number !== "" &&
				this.paymentData.expiration !== "" &&
				(this.paymentData.first_name !== "" ||
					this.paymentData.last_name !== "")
			) {
				valid = true;
			} else {
				valid = false;
			}
			return valid;
		},
	},
	methods: {
		...mapActions({
			updatePaymentMethod: "updatePaymentMethod",
			fetchPaymentMethods: "fetchPaymentMethods",
		}),
		...globalMethods,
		expirationInput() {
			// var inputChar = String.fromCharCode(event.keyCode)
			// var code = event.keyCode;
			// var allowedKeys = [8];
			// if (allowedKeys.indexOf(code) !== -1) {
			//     return;
			// }
			// event.target.value = event.target.value.replace(
			//     /^([1-9]\/|[2-9])$/g, '0$1/' // 3 > 03/
			// ).replace(
			//     /^(0[1-9]|1[0-2])$/g, '$1/' // 11 > 11/
			// ).replace(
			//     /^([0-1])([3-9])$/g, '0$1/$2' // 13 > 01/3
			// ).replace(
			//     /^(0?[1-9]|1[0-2])([0-9]{2})$/g, '$1/$2' // 141 > 01/41
			// ).replace(
			//     /^([0]+)\/|[0]+$/g, '0' // 0/ > 0 and 00 > 0
			// ).replace(
			//     /[^\d\/]|^[\/]*$/g, '' // To allow only digits and `/`
			// ).replace(
			//     /\/\//g, '/' // Prevent entering more than 1 `/`
			// );
		},
		close() {
			this.$refs.form.resetValidation();
			this.$emit("close");
		},
		async save() {
			// this.$refs.form.validate();
			const params = {
				card_id: this.paymentData.card_id,
				first_name: this.paymentData.first_name,
				is_default: this.paymentData.default,
			};
			this.$emit("editPaymentMethodDialog", params);
		},
		creditInfoChanged(values) {
			this.paymentData.first_name = values.name;
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/billing/addPaymentDialog.scss";
</style>
