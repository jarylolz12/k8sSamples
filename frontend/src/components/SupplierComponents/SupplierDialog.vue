<!-- @format -->

<template>
	<v-dialog
		v-model="dialog"
		max-width="560px"
		content-class="add-supplier-dialog"
		v-resize="onResize"
		:retain-focus="false"
		persistent
		scrollable>

		<v-card class="add-supplier-card">			
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>

				<button icon dark class="btn-close" @click="close">
					<v-icon>mdi-close</v-icon>
				</button>
			</v-card-title>

			<v-card-text>
				<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
					<v-row>
						<v-col cols="12" sm="12" md="12" class="pb-0">
							<label class="text-item-label">Company Key <small class="text-capitalize">(Optional)</small></label>
							<v-text-field
								:disabled="editedIndexData > -1"
								placeholder="Company Key"
								outlined
								class="text-fields company-key"
								hide-details="auto"
								@change="checkCompanyKey"
								:error-messages="companyKeyError"
								v-model="editedItem.company_key"
							>
							</v-text-field>
						</v-col>
						
						<v-col cols="12" sm="12" md="12" class="pb-0">
							<label class="text-item-label">Name</label>
							<v-text-field
								:placeholder="
									`Type name of the ${
										editedItem.isSupplier ? 'vendor' : 'buyer'
									}`
								"
								outlined
								class="text-fields"
								v-model="editedItem.name"
								:rules="rules"
								hide-details="auto"
							>
							</v-text-field>
						</v-col>

						<v-col cols="12" sm="12" md="12" class="pb-0">
							<label class="text-item-label">Phone Number</label>
							<!-- <vue-tel-input-vuetify
								class="text-fields"
								type="number"
								outlined
								dense
								single-line
								hide-details="auto"
								:rules="numberRules"
								v-bind="telProps"
								v-model="editedItem.phone"
								:menu-props="{ bottom: true, offsetY: true }">

								<template v-slot:selection="{ item, index }">
									<div class="v-select__selection v-select__selection--comma" :key="index">
										{{ item }}
									</div>
								</template>
							</vue-tel-input-vuetify> -->

							<vue-tel-input
								v-model="editedItem.phone"
								mode="international"
								defaultCountry="us"
								validCharactersOnly
								:autoDefaultCountry="true"
								:dropdownOptions="vueTelDropdownOptions"
								:inputOptions="vueTelInputOptions"
								:styleClasses="isPhoneNumberEmpty && editedItem.phone === '' ? 'is-error' : ''">
								
								<template v-slot:arrow-icon>
									<v-icon class="ml-1">mdi-chevron-down</v-icon>
								</template>
							</vue-tel-input>

							<span class="error-message" style="color: red; font-size: 12px;" v-if="isPhoneNumberEmpty">
								{{ editedItem.phone === '' ? 'Phone number is required.' : ''}}
							</span>
						</v-col>

						<v-col cols="12" sm="12" md="12" class="pb-0">
							<label class="text-item-label">Email</label>
							<div class="tags-email-wrapper mb-1">
								<vue-tags-input
									hide-details="auto"
									:rules="arrayNotEmptyRules"
									allow-edit-tags
									:tags="options"
									:add-on-blur="true"
									:add-on-key="[13, ',']"
									:validation="tagsValidation"
									v-model="warehouseEmailAddress"
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
											if (typeof el !== 'udnefined') {
												if (this.tagsInput.hasError)
													el.classList.add('ti-new-tag-input-error');
												else el.classList.remove('ti-new-tag-input-error');
											}
										}
									"
								/>
							</div>

							<div v-if="tagsInput.touched" class="v-text-field__details">
								<div class="v-messages theme--light error--text" role="alert">
									<div class="v-messages__wrapper">
										<div
											v-if="options.length > 0 && warehouseEmailAddress !== ''"
											class="v-messages__message"
										>
											{{ tagsInput.errorMessage }}
										</div>

										<div
											v-if="options.length == 0 && warehouseEmailAddress !== ''"
											class="v-messages__message"
										>
											{{ tagsInput.errorMessage }}
										</div>
										<div
											v-if="options.length == 0 && warehouseEmailAddress == ''"
											class="v-messages__message"
										>
											Please provide at least 1 valid email address.
										</div>
									</div>
								</div>
							</div>

							<span style="color: #819FB2; font-size: 12px; letter-spacing: 0;">
								Press the "Enter" or "," key in your keyboard to confirm the
								email address
							</span>
						</v-col>

						<v-col cols="12" sm="12" md="12">
							<label class="text-item-label">Address</label>
							<v-textarea
								height="76px"
								class="text-fields"
								outlined
								name="input-7-4"
								:placeholder="
									`Enter ${
										editedItem.isSupplier ? 'vendor' : 'buyer'
									}'s address`
								"
								v-model="editedItem.address"
								:rules="rules"
								hide-details="auto"
							>
							</v-textarea>
						</v-col>
					</v-row>
				</v-form>
			</v-card-text>

			<v-card-actions>
				<v-btn
					class="btn-blue"
					text
					@click="save"
					:disabled="disabledSaveButton || getCreateSuppliersLoading || getCreateCustomersLoading || getUpdateCustomersLoading">

					<span v-if="editedIndexData === -1">
						<span v-if="editedItem.isSupplier">
							{{ getCreateSuppliersLoading ? "Adding Vendor..." : "Add Vendor" }}
						</span>

						<span v-else>
							{{ getCreateCustomersLoading ? "Adding Buyer..." : "Add Buyer" }}
						</span>
					</span>

					<span v-if="editedIndexData > -1">
						<span v-if="editedItem.isSupplier">
							{{
								getCreateSuppliersLoading
								? "Saving..."
								: "Save Vendor"
							}}
						</span>

						<span v-else>
							{{
								getUpdateCustomersLoading
								? "Saving..."
								: "Save Buyer"
							}}
						</span>
					</span>
				</v-btn>

				<!-- <v-btn class="btn-white" text @click="saveAndAddSupplier" v-if="editedIndexData === -1">
					{{ ( getSupplierSaveAddLoading ) ? 'Saving...' : 'Save & Add Another' }}
				</v-btn> -->

				<v-btn class="btn-white" text @click="close"
					:disabled="getCreateSuppliersLoading || getCreateCustomersLoading || getUpdateCustomersLoading">
					Cancel
				</v-btn>
			</v-card-actions>
			
			<v-overlay v-if="getCompanyKeyLoading" contained class="align-center justify-center loading-overlay-z-index-top">
				<v-progress-circular
					:size="40"
					color="#0171a1"
					indeterminate
					>
				</v-progress-circular>
			</v-overlay>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
// import VueTelInputVuetify from "vue-tel-input-vuetify/lib/vue-tel-input-vuetify.vue"
import { VueTelInput } from "vue-tel-input"
import "vue-tel-input/dist/vue-tel-input.css"
import VueTagsInput from "@johmun/vue-tags-input"
import jQuery from "jquery"
import globalMethods from "../../utils/globalMethods"

export default {
	name: "SupplierDialog",
	props: [
		"editedItemData",
		"editedIndexData",
		"dialogData",
		"defaultItemData",
		"customerPagination",
		"suppliersPagination",
		'searchVendors',
		'searchCustomers',
		'suppliersData',
		'customersData',
		'fromComponent'
	],
	components: {
		// VueTelInputVuetify,
		VueTelInput,
		VueTagsInput,
	},
	data: () => ({
		valid: true,
		options: [],
		value: [],
		isMobile: false,
		rules: [(v) => !!v || "Input is required."],
		telProps: {
			mode: "international",
			defaultCountry: "US",
			placeholder: "Type phone number",
			label: "Type phone number",
			autocomplete: "off",
			maxLen: 25,
			preferredCountries: ["US", "CN"],
			disabledDialCode: false,
			inputOptions: {
				showDialCode: false,
			},
		},
		vueTelDropdownOptions: {
			showDialCodeInSelection: true,
			showDialCodeInList: true,
			showFlags: true,
			showSearchBox: true,
		},
		vueTelInputOptions: {
			autocomplete: false,
			placeholder: "Type phone number",
			styleClasses: "tel-input-class",
			required: true,
		},
		countryCode: null,
		numberRules: [
			(v) => !!v || "Input is required.",
			(v) => /^(?=.*[0-9])[- +()0-9]+$/.test(v) || "Letters are not allowed.",
		],
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
		warehouseEmailAddress: "",
		arrayNotEmptyRules: [
			(v) => !!v || "Email is required",
			() =>
				this.optionsFiltered.length > 0 ||
				"Make sure to supply at least 1 email.",
		],
		isPhoneNumberEmpty: false,
		companyKeyError: [],
		disabledSaveButton: false,
	}),
	computed: {
		...mapGetters({
			getUser: "getUser",
			getCreateSuppliersLoading: "suppliers/getCreateSuppliersLoading",
			getSupplierSaveAddLoading: "suppliers/getSupplierSaveAddLoading",
			getCreateCustomersLoading: "customers/getCreateCustomersLoading",
			getUpdateCustomersLoading: "customers/getUpdateCustomersLoading",
			getSearchedSuppliers: "suppliers/getSearchedSuppliers",
			getSearchedCustomer: 'customers/getSearchedCustomer',
			getCompanyKeyLoading: "suppliers/getCompanyKeyLoading",
			getCompanyKeyResponse: "suppliers/getCompanyKeyResponse",
			getCustomerDetailsData: "suppliers/getCustomerDetailsData",
		}),
		formTitle() {
			if (this.editedItem.isSupplier) {
				return this.editedIndexData === -1 ? "Add Vendor" : "Edit Vendor"
			} else {
				return this.editedIndexData === -1 ? "Add Buyer" : "Edit Buyer"
			}
		},
		dialog: {
			get() {
				return this.dialogData
			},
			set(value) {
				if (!value) {
					this.$emit("update:dialogData", false)
				} else {
					this.$emit("update:dialogData", true)
				}
			},
		},
		editedItem: {
			get() {
				return this.editedItemData
			},
			set(value) {
				this.$emit('update:editedItemData', value)
			},
		},
		defaultItem: {
			get() {
				return this.defaultItemData
			},
			set(value) {
				this.$emit('update:defaultItemData', value)
			},
		},
		addSupplierTemplate() {
			let { name, address, phone, emails } = this.editedItem

			return {
				custom_customers_id: [
					typeof this.getUser == "string"
					? JSON.parse(this.getUser).customer.id
					: "",
				],
				company_name: name,
				address,
				phone,
				emails,
			}
		},
		addCustomerTemplate() {
			let { name, address, phone, emails } = this.editedItem

			return {
				custom_customers_id: [
					typeof this.getUser == "string"
					? JSON.parse(this.getUser).customer.id
					: "",
				],
				company_name: name,
				address,
				phone,
				emails,
			}
		},
		optionsFiltered: {
			get() {
				let validEmailAddress = []

				if (this.editedItem.emails !== null &&
					this.editedItem.emails.length > 0) {
					this.editedItem.emails.map((wea) => {
						if (wea !== null) {
							validEmailAddress.push({ text: wea, tiClasses: ["ti-valid"] })
						}
					})
				}
				return validEmailAddress
			},
			set(value) {
				this.options = value
			},
		},
	},
	methods: {
		...mapActions({
			createSuppliers: "suppliers/createSuppliers",
			fetchSuppliers: "suppliers/fetchSuppliers",
			updateSuppliers: "suppliers/updateSuppliers",
			fetchCustomers: "customers/fetchCustomers",
			createCustomers: "customers/createCustomers",
			updateCustomers: "customers/updateCustomers",
			fetchSearchedVendors: "suppliers/fetchSearchedVendors",
			setSearchedVendorsLoading: "suppliers/setSearchedVendorsLoading",
			fetchSearchedCustomers: 'customers/fetchSearchedCustomers',
			setSearchedCustomerLoading: 'customers/setSearchedCustomerLoading',
			checkCompanyKeyExists: 'suppliers/checkCompanyKeyExists',
			fetchCustomerDetails: 'suppliers/fetchCustomerDetails',
		}),
		...globalMethods,
		countrySelected(val) {
			this.countryCode = val.dialCode
			console.log(val)
		},
		generateErrorMessage() {
			this.tagsInput.hasError = this.options.length > 0 ? false : true
			if (this.tagsInput.hasError)
				jQuery(".ti-input").addClass("ti-new-tag-input-error")
			else jQuery(".ti-input").removeClass("ti-new-tag-input-error")

			this.isPhoneNumberEmpty = this.addSupplierTemplate.phone === '' ? true : false
		},
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true
			} else {
				this.isMobile = false
			}
		},
		close() {
			this.$refs.form.reset();
			this.$refs.form.resetValidation();
			this.editedItem.phone = '';
			this.$emit("update:dialogData", false)
			this.isPhoneNumberEmpty = false
			this.companyKeyError = []
			this.disabledSaveButton = false
		},
		async save() {
			if (!this.tagsInput.touched) this.tagsInput.touched = true

			this.$refs.form.validate()
			this.tagsInput.hasError = this.options.length > 0 ? false : true

			this.generateErrorMessage()

			setTimeout(async () => {
				let parms = {
					itemsPerPage: this.customerPagination.per_page,
					pageNumber: this.customerPagination.current_page,
				}

				let parmsSuppliers = {
					pageNumber: this.suppliersPagination.current_page
				}

				if (this.$refs.form.validate()) {
					if (!this.tagsInput.hasError && !this.isPhoneNumberEmpty) {
						try {
							jQuery(".ti-new-tag-input").trigger(
								jQuery.Event("keyup", { keyCode: 13, which: 13 })
							)

							if (this.editedItem.isSupplier) {
								// function if for supplier
								if (this.editedIndexData === -1) {
									let finalEmailAddress =
										this.options.length > 0
											? this.options.map((o) => {
												return o.text
											})
											: []

									let addSupplierTemplate = this.addSupplierTemplate
									addSupplierTemplate.emails = finalEmailAddress
									addSupplierTemplate.company_key = this.editedItem.company_key

									await this.createSuppliers(this.addSupplierTemplate)
									this.notificationMessage("Vendor has been created.")
									//close dialog
									this.close()

									if (this.searchVendors !== '') {
										if (typeof this.getSearchedSuppliers !== 'undefined') {
											let page = typeof this.getSearchedSuppliers.current_page !== 'undefined' ?
												this.getSearchedSuppliers.current_page : 1

											await this.fetchSearchedVendorsAPI(page)
										}
									} else {
										if(this.fromComponent == 'BookingShipmentDialog'){
											this.$store.dispatch('booking/getShipperOptions', {
												page: 1
											})
										}
										await this.fetchSuppliers(parmsSuppliers)
									}
									
									this.editedIndexData = -1
								} else {
									let finalEmailAddress =
										this.options.length > 0
											? this.options.map((o) => {
												return o.text
											})
											: []

									const {
										custom_customers_id,
										...newSupplierTemplate
									} = this.addSupplierTemplate
									newSupplierTemplate.id = this.editedItem.id
									newSupplierTemplate.emails = finalEmailAddress

									await this.updateSuppliers(newSupplierTemplate)
									console.log(custom_customers_id)
									this.notificationMessage("Vendor has been updated.")
									// close dialog
									this.close()

									if (this.searchVendors !== '') {
										if (typeof this.getSearchedSuppliers !== 'undefined') {
											let page = typeof this.getSearchedSuppliers.current_page !== 'undefined' ?
												this.getSearchedSuppliers.current_page : 1

											await this.fetchSearchedVendorsAPI(page)
										}
									} else {
										await this.fetchSuppliers(parmsSuppliers)
									}								
								}
							} else {
								// add function for creating and editing here if for customer
								if (this.editedIndexData === -1) {
									let finalEmailAddress =
										this.options.length > 0
											? this.options.map((o) => {
												return o.text
											})
											: []

									let addCustomerTemplate = this.addCustomerTemplate
									addCustomerTemplate.emails = finalEmailAddress
									addCustomerTemplate.company_key = this.editedItem.company_key
									
									await this.createCustomers(this.addCustomerTemplate)

									this.notificationMessage("Buyer has been created.")
									
									//close dialog
									this.close()

									if (this.searchCustomers !== '') {
										if (typeof this.getSearchedCustomer !== 'undefined') {
											let page = typeof this.getSearchedCustomer.current_page !== 'undefined' ?
												this.getSearchedCustomer.current_page : 1

											await this.fetchSearchedCustomersAPI(page)
										}
									} else {
										if(this.fromComponent == 'BookingShipmentDialog'){
											this.$store.dispatch('booking/getConsigneeOptions', {
												page: 1
											})
										}
										await this.fetchCustomers(parms)
									}

									this.editedIndexData = -1
								} else {
									let finalEmailAddress =
										this.options.length > 0
											? this.options.map((o) => {
												return o.text
											})
											: []

									const { ...newCustomerTemplate } = this.addCustomerTemplate
									newCustomerTemplate.id = this.editedItem.id
									newCustomerTemplate.emails = finalEmailAddress

									await this.updateCustomers(newCustomerTemplate)
									this.notificationMessage("Buyer has been updated.")

									// close dialog
									this.close()

									if (this.searchCustomers !== '') {
										if (typeof this.getSearchedCustomer !== 'undefined') {
											let page = typeof this.getSearchedCustomer.current_page !== 'undefined' ?
												this.getSearchedCustomer.current_page : 1

											await this.fetchSearchedCustomersAPI(page)
										}
									} else {
										await this.fetchCustomers(parms)
									}									
								}
							}
						} catch (e) {
							this.notificationError(e)
							console.log(e)
						}
					}
				}
			}, 200)
		},
		// disregard this function - backup only
		async saveAndAddSupplier() {
			if (!this.tagsInput.touched) this.tagsInput.touched = true

			this.$refs.form.validate()
			this.tagsInput.hasError = this.options.length > 0 ? false : true

			this.generateErrorMessage()

			setTimeout(async () => {
				if (this.$refs.form.validate()) {
					if (!this.tagsInput.hasError) {
						try {
							jQuery(".ti-new-tag-input").trigger(
								jQuery.Event("keyup", { keyCode: 13, which: 13 })
							)

							if (this.editedIndexData === -1) {
								let finalEmailAddress =
									this.options.length > 0
										? this.options.map((o) => {
												return o.text
											})
										: []

								this.addSupplierTemplate.customerMethod = "save-add"
								this.addSupplierTemplate.emails = finalEmailAddress
								await this.createSuppliers(this.addSupplierTemplate)

								await this.fetchSuppliers()
								this.setToDefault()

								this.notificationMessage("Vendor has been created.")
							}
						} catch (e) {
							this.notificationError(e)
							console.log(e)
						}
					}
				}
			}, 200)
		},
		async fetchSearchedVendorsAPI(page) {
			let passedData = {
				method: "get",
				url: 'https://beta.shifl.com/api/v2/suppliers',
				params: {
					qry: this.searchVendors,
					page
				}
			}

            try {
                passedData.tab = 'vendors'
                await this.fetchSearchedVendors(passedData)

				if (this.suppliersData.length === 0 && page !== 1) {
					passedData.params.page = page - 1
					await this.fetchSearchedVendors(passedData)
				}

				await this.fetchSuppliers(1)
            } catch(e) {
                this.notificationError(e)
                this.setSearchedVendorsLoading(false)
                console.log(e, 'Search error')
            }
		},
		async fetchSearchedCustomersAPI(page) {
			let passedData = {
				method: "get",
				url: 'https://beta.shifl.com/api/v2/buyers',
				params: {
					qry: this.searchCustomers,
					page
				}
			}

            try {
                passedData.tab = 'customers'
                await this.fetchSearchedCustomers(passedData)

				if (this.customersData.length === 0 && page !== 1) {
					passedData.params.page = page - 1
					await this.fetchSearchedCustomers(passedData)
				}

				let parms = {
					itemsPerPage: this.customerPagination.per_page,
					pageNumber: 1,
				}

				await this.fetchCustomers(parms)
            } catch(e) {
                this.notificationError(e)
                this.setSearchedCustomerLoading(false)
                console.log(e, 'Search error')
            }
		},
		setToDefault() {
			this.$emit("setToDefault")
			this.tagsInput.touched = false
			this.options = []
		},
		async checkCompanyKey(e) {
			this.companyKeyError = []
			this.disabledSaveButton = false
			if (e) {
				await this.checkCompanyKeyExists(e)
				if (this.getCompanyKeyResponse) {
					await this.fetchCustomerDetails(e)
					const custDetailsData = this.getCustomerDetailsData
					this.editedItem.name = custDetailsData.company_name
					if (custDetailsData.phone) {
						this.editedItem.phone = custDetailsData.phone
					}
					this.editedItem.address = custDetailsData.address
					if (custDetailsData.emails) {
						let newEmailLists = custDetailsData.emails.filter(v => { return v.email !== null })
						var result = newEmailLists.map(item => ({ "text": item.email,"tiClasses":["ti-valid"] }))
						this.options = result
					}
				} else {
					// this.companyKeyError = ["This key does not found..!"]
					this.companyKeyError = ["Key provided is not found"]
					this.disabledSaveButton = true
				}
			}
		},
	},
	watch: {
		tagsInput(value) {
			if (value.hasError) {
				jQuery(".ti-input").addClass("ti-new-tag-input-error")
			} else {
				jQuery(".ti-input").removeClass("ti-new-tag-input-error")
			}
		},
		dialog(value, oldValue) {
			this.tagsInput.hasError = false
			this.tagsInput.touched = false

			jQuery(".ti-input").removeClass("ti-new-tag-input-error")
			if (typeof el !== "undefined") {
				let el = document.getElementsByClassName("ti-input")[0]
				el.classList.remove("ti-new-tag-input-error")
			}

			if (!value) {
				this.options = []
				this.warehouseEmailAddress = ""
			} else if (value && !oldValue) {
				if (this.editedIndex == -1) {
					this.options = []
				} else {
					let validEmailAddress = []
					if (
						this.editedItem.emails !== null &&
						this.editedItem.emails.length > 0
					) {
						this.editedItem.emails.map((wea) => {
							if (wea?.email) {
								validEmailAddress.push({
									text: wea.email,
									tiClasses: ["ti-valid"],
								})
							} else if (wea !== null) {
								validEmailAddress.push({ text: wea, tiClasses: ["ti-valid"] })
							}
						})
					}
					this.options = validEmailAddress
				}
			}
		},
	},
	updated() {
		if (this.editedIndexData === -1) {
			if (typeof this.$refs.form !== "undefined") {
				if (typeof this.$refs.form.resetValidation() !== "undefined") {
					this.$refs.form.resetValidation()
				}
			}
		}
	},
}
</script>

<style lang="scss">
@import "../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../assets/scss/pages_scss/supplier/supplierAddDialog.scss";

.v-autocomplete__content.v-menu__content {
	border-radius: 4px !important;
}
</style>
