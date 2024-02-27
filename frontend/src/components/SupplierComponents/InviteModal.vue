<template>
	<v-dialog
		v-model="dialogCreate"
		max-width="560px"
		content-class="email-dialog"
		:retain-focus="false"
		persistent
	>
		<v-card class="email-dialog-card">
			<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
				<v-card-title>
					<span class="headline">Invite to Shifl</span>

					<button icon dark class="btn-close" @click="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>

				<v-card-text>
					<div class="text-header-invitation">
						<p v-if="isVendor">
							Please confirm the email address or addresses below to invite 
							<span style="font-weight: 600; color: #4a4a4a;">{{ emailItem.info.name }}</span> to Shifl. 
							You can sent invitation to multiple emails. 
							Invitation accepted from any of those email will connect the vendor with your account.
						</p>

						<p v-else>
							Please confirm the email address or addresses below to invite 
							<span style="font-weight: 600; color: #4a4a4a;">{{ emailItem.info.company_name }}</span> to Shifl.
							You can sent invitation to multiple emails. 
							Invitation accepted from any of those email will connect the customer with your account.
						</p>
					</div>

					<v-row>
						<v-col cols="12" sm="12">
							<div class="email-wrapper">
								<div class="email-details mb-3">
									<p class="email-title">EMAIL ADDRESS</p>
									<div class="tags-email-wrapper mb-1">
										<vue-tags-input
											hide-details="auto"
											:rules="arrayNotEmptyRules"
											:tags="options"
											:add-on-blur="true"
											:add-on-key="[13, ',']"
											:validation="tagsValidation"
											v-model="vendorEmailAddress"
											placeholder="e.g. name@email.com"
											@tags-changed="
												(newTags) => {
													this.options = newTags;
													this.tagsInput.touched = true;
													this.tagsInput.hasError =
														this.options.length > 0 ? false : true;
													let el = this.documentProto.getElementsByClassName(
														'ti-input'
													)[0];
													if (typeof el !== 'undefined') {
														if (this.tagsInput.hasError)
															el.classList.add('ti-new-tag-input-error');
														else el.classList.remove('ti-new-tag-input-error');
													}
												}
											"
										/>
									</div>

									<div v-if="tagsInput.touched" class="v-text-field__details">
										<div
											class="
												v-messages
												theme--light
												error--text
											"
											role="alert"
										>
											<div class="v-messages__wrapper">
												<div
													v-if="options.length > 0 && vendorEmailAddress !== ''"
													class="v-messages__message"
												>
													{{ tagsInput.errorMessage }}
												</div>

												<div
													v-if="
														options.length == 0 && vendorEmailAddress !== ''
													"
													class="v-messages__message"
												>
													{{ tagsInput.errorMessage }}
												</div>
												<div
													v-if="options.length == 0 && vendorEmailAddress == ''"
													class="v-messages__message"
												>
													Please provide at least 1 valid email address.
												</div>
											</div>
										</div>
									</div>

									<span class="separate-info"
										>Separate multiple email addresses with comma</span
									>
								</div>
							</div>
						</v-col>
					</v-row>
				</v-card-text>

				<v-card-actions class="po-button-actions">
					<button class="btn-blue" 
						:disabled="getSendInvitationSupplierLoading || getSendInvitationCustomerLoading">
						<span v-if="this.emailItem.supplier_id">
							{{ getSendInvitationSupplierLoading ? "Sending..." : "Send Invitation" }}
						</span>

						<span v-if="this.emailItem.customer_id">
							{{ getSendInvitationCustomerLoading ? "Sending..." : "Send Invitation" }}
						</span>
					</button>

					<button class="btn-white" @click="close" 
						:disabled="getSendInvitationSupplierLoading || getSendInvitationCustomerLoading">
						Cancel
					</button>
				</v-card-actions>
			</v-form>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import globalMethods from "@/utils/globalMethods";
import VueTagsInput from "@johmun/vue-tags-input";
import jQuery from "jquery";

export default {
	name: "InviteModal",
	props: ["dialog", "editedItems", "editedIndex", "isMobile", "isVendor"],
	components: {
		VueTagsInput,
	},
	data: () => ({
		valid: true,
		options: [],
		tagsValidation: [
			{
				classes: "t-new-tag-input-text-error",
				rule: /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/,
				disableAdd: true,
			},
		],
		documentProto: document,
		tagsInput: {
			touched: false,
			hasError: false,
			errorMessage:
				'Please confirm the entered email address by pressing the "Enter" or "," key in your keyboard.',
		},
		vendorEmailAddress: "",
		arrayNotEmptyRules: [
			(v) => !!v || "Email is required",
			(v) => /.+@.+/.test(v) || "E-mail must be valid",
			() =>
				this.optionsFiltered.length > 0 ||
				"Make sure to supply at least 1 email.",
		],
	}),
	computed: {
		...mapGetters({
			getUser: "getUser",
			getSendInvitationSupplierLoading:
				"suppliers/getSendInvitationSupplierLoading",
			getSendInvitationCustomerLoading:
				"customers/getSendInvitationCustomerLoading",
		}),
		dialogCreate: {
			get() {
				return this.dialog;
			},
			set(value) {
				this.$emit("update:dialog", value);
			},
		},
		emailItem: {
			get() {
				return this.editedItems;
			},
			set(value) {
				this.$emit("update:editedItems", value);
			},
		},
		optionsFiltered: {
			get() {
				let validEmailAddress = [];

				if (
					this.emailItem.emails !== null &&
					this.emailItem.emails.length > 0
				) {
					this.emailItem.emails.map((wea) => {
						if (wea !== null) {
							validEmailAddress.push({
								text: wea,
								tiClasses: ["ti-valid"],
							});
						}
					});
				}

				return validEmailAddress;
			},
			set(value) {
				this.options = value;
			},
		},
		addSupplierEmailTemplate() {
			let { supplier_id, emails } = this.emailItem;

			return {
				customer_admin_id:
					typeof this.getUser == "string"
						? JSON.parse(this.getUser).default_customer_id
						: "",

				supplier_id,
				emails,
			};
		},
		addCustomerEmailTemplate() {
			let { customer_id, emails } = this.emailItem;

			return {
				customer_admin_id:
					typeof this.getUser == "string"
						? JSON.parse(this.getUser).default_customer_id
						: "",

				customer_id,
				emails,
			};
		},
	},
	watch: {
		tagsInput(value) {
			if (value.hasError) {
				jQuery(".ti-input").addClass("ti-new-tag-input-error");
			} else {
				jQuery(".ti-input").removeClass("ti-new-tag-input-error");
			}
		},
		dialogCreate(value, oldValue) {
			this.tagsInput.hasError = false;
			this.tagsInput.touched = false;

			jQuery(".ti-input").removeClass("ti-new-tag-input-error");
			if (typeof el !== "undefined") {
				let el = document.getElementsByClassName("ti-input")[0];
				el.classList.remove("ti-new-tag-input-error");
			}

			if (!value) {
				this.options = [];
				this.vendorEmailAddress = "";
			} else if (value && !oldValue) {
				let validEmailAddress = [];
				if (
					this.emailItem.emails !== null &&
					this.emailItem.emails.length > 0
				) {
					this.emailItem.emails.map((wea) => {
						if (wea?.email) {
							validEmailAddress.push({
								text: wea.email,
								tiClasses: ["ti-valid"],
							});
						} else if (wea !== null) {
							validEmailAddress.push({
								text: wea,
								tiClasses: ["ti-valid"],
							});
						}
					});
				}
				this.options = validEmailAddress;
			}
		},
	},
	methods: {
		...mapActions({
			sendInvitationSupplier: "suppliers/sendInvitationSupplier",
			sendInvitationCustomer: "customers/sendInvitationCustomer",
		}),
		...globalMethods,
		generateErrorMessage() {
			this.tagsInput.hasError = this.options.length > 0 ? false : true;
			if (this.tagsInput.hasError)
				jQuery(".ti-input").addClass("ti-new-tag-input-error");
			else jQuery(".ti-input").removeClass("ti-new-tag-input-error");
		},
		sendVendorEmail() {
			if (!this.tagsInput.touched) this.tagsInput.touched = true;

			this.$refs.form.validate();
			this.tagsInput.hasError = this.options.length > 0 ? false : true;

			this.generateErrorMessage();

			setTimeout(async () => {
				if (this.$refs.form.validate()) {
					let isValid = false;
					var re = /\S+@\S+\.\S+/;

					if (this.vendorEmailAddress !== "") {
						if (re.test(this.vendorEmailAddress)) {
							isValid = true;
						} else {
							isValid = false;
						}
					} else {
						isValid = true;
					}

					if (!this.tagsInput.hasError && isValid) {
						try {
							jQuery(".ti-new-tag-input").trigger(
								jQuery.Event("keyup", {
									keyCode: 13,
									which: 13,
								})
							);

							let finalEmailAddress =
								this.options.length > 0
									? this.options.map((o) => {
											return o.text;
										})
									: [];

							if (this.emailItem.supplier_id) {
								let addSupplierEmailTemplate = this.addSupplierEmailTemplate;
								addSupplierEmailTemplate.emails = finalEmailAddress;

								await this.sendInvitationSupplier(
									this.addSupplierEmailTemplate
								);
								this.notificationMessage("Invitation has been sent!");
							} else if (this.emailItem.customer_id) {

								let addCustomerEmailTemplate = this.addCustomerEmailTemplate;
								addCustomerEmailTemplate.emails = finalEmailAddress;

								await this.sendInvitationCustomer(
									this.addCustomerEmailTemplate
								);
								this.notificationMessage("Invitation has been sent!");
							}

							this.close();
						} catch (e) {
							this.notificationError(e);
							this.close();
						}
					}
				}
			}, 200);
		},
		close() {
			this.$refs.form.resetValidation();
			this.$emit("close");
		},
	},
	async mounted() {},
	updated() {
		if (typeof this.$refs.form !== "undefined") {
			if (typeof this.$refs.form.resetValidation() !== "undefined") {
				this.$refs.form.resetValidation();
			}
		}
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/supplier/inviteDialog.scss";

.text-header-invitation {
	p {
		font-family: "Inter-Regular", sans-serif;
		color: #4a4a4a;
		font-size: 14px;
	}
}
</style>
