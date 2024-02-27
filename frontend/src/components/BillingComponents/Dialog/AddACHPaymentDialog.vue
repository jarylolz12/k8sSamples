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
			<v-form v-model="formValid" ref="myForm" @submit.prevent="">
				<v-card-title>
					<span class="headline">Add ACH Payment Method</span>
					<button icon dark class="btn-close" @click="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>

				<v-card-text>
					<div class="add-payment-wrapper">
						<v-row>
							<v-col cols="12" sm="12" md="12" class="pb-0">
								<label class="text-item-label">Name</label>
								<v-text-field
									v-model="ach_name"
									class="text-fields"
									hide-details="auto"
									placeholder="Name"
									:rules="rules.required"
									outlined
								>
								</v-text-field>
							</v-col>
							<v-col cols="12" sm="12" md="12" class="pb-0">
								<label class="text-item-label">Routing</label>
								<v-text-field
									v-model="ach_routing"
									placeholder="Routing"
									outlined
									class="text-fields"
									hide-details="auto"
									:rules="rules.required"
								>
								</v-text-field>
							</v-col>
							<v-col cols="12" sm="12" md="12" class="pb-0">
								<label class="text-item-label">Account</label>
								<v-text-field
									v-model="ach_account"
									placeholder="Account"
									outlined
									class="text-fields"
									hide-details="auto"
									:rules="rules.required"
								>
								</v-text-field>
							</v-col>
						</v-row>
					</div>
				</v-card-text>

				<v-card-actions>
					<button
						class="btn-blue"
						@click="addACHPaymentMethod"
						:disabled="buttonTextAdding || !formValid"
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

export default {
	name: "AddACHPaymentDialog",
	props: ["dialog", "addACHMethodButtonDisabled", "buttonTextAdding"],
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
	data: () => ({
		ach_name: "",
		ach_routing: "",
		ach_account: "",
		focus: false,
		formData: [],
		formValid: false,
		rules: {
			required: [(value) => !!value || "Required."],
		},
	}),
	methods: {
		...globalMethods,
		close() {
			this.$refs.myForm.resetValidation();
			this.$emit("ACHClose");
		},
		addACHPaymentMethod() {
			this.formData["name"] = this.ach_name;
			this.formData["routing"] = this.ach_routing;
			this.formData["account"] = this.ach_account;

			this.$emit("saveACHPaymentMethod", this.formData);
		},
	},
	watch: {
		dialogAdd() {
			this.$refs.myForm.reset();
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/billing/addPaymentDialog.scss";
</style>
