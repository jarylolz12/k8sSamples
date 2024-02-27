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
			<v-form v-model="formValid" ref="myForm" @submit.prevent="">
				<v-card-title>
					<span class="headline">Edit ACH Payment Method</span>
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
									v-model="paymentData.name"
									class="text-fields"
									hide-details="auto"
									placeholder="Name"
									outlined
									:rules="rules.required"
								>
								</v-text-field>
							</v-col>
							<v-col cols="12" sm="12" md="12" class="pb-0">
								<label class="text-item-label">Routing</label>
								<v-text-field
									v-model="paymentData.routing"
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
									v-model="paymentData.account"
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
						@click="save"
						:disabled="getACHPaymentMethodLoading || !formValid"
					>
						<span>
							<span v-if="!getACHPaymentMethodLoading">Update Method</span>
							<span v-if="getACHPaymentMethodLoading">Updating...</span>
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

export default {
	name: "EditACHPaymentDialog",
	props: ["editDialog", "editedItemData"],
	data: () => ({
		formValid: false,
		rules: {
			required: [(value) => !!value || "Required."],
		},
	}),
	computed: {
		...mapGetters({
			getACHPaymentMethodLoading: "getACHPaymentMethodLoading",
		}),
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
	},
	methods: {
		...mapActions({
			updatePaymentMethod: "updatePaymentMethod",
			fetchPaymentMethods: "fetchPaymentMethods",
		}),
		...globalMethods,
		close() {
			this.$refs.myForm.resetValidation();
			this.$emit("close");
		},
		async save() {
			const params = {
				record_id: this.paymentData.record_id,
				xName: this.paymentData.name,
				xRouting: this.paymentData.routing,
				xAccount: this.paymentData.account,
			};
			this.$emit("editPaymentMethodDialog", params);
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/billing/addPaymentDialog.scss";
</style>
