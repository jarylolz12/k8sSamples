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
							<label class="text-item-label">Company Key 
								<small class="text-capitalize" style="font-size: 10px">(Optional)</small>
							</label>
							<v-text-field
								:disabled="editedIndexData > -1"
								placeholder="Company Key"
								outlined
								class="text-fields company-key"
								hide-details="auto"
								@change="checkCompanyKey"
								:error-messages="companyKeyError"
								v-model="editedItem.company_key">
							</v-text-field>
						</v-col>

						<v-col cols="12" sm="12" md="12" class="pb-0">
							<label class="text-item-label">Name</label>
							<v-text-field
								placeholder="Type name of the warehouse customer"
								outlined
								class="text-fields"
								v-model="editedItem.name"
								:rules="rules"
								hide-details="auto">
							</v-text-field>
						</v-col>

						<v-col cols="12" sm="12" md="12" class="pb-0">
							<label class="text-item-label">Phone Number</label>
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

							<span style="color: #819FB2; font-size: 12px; letter-spacing: 0;">
								Press the "Enter" or "," key in your keyboard to confirm the
								email address
							</span>

							<div v-if="tagsInput.touched" class="v-text-field__details">
								<div class="v-messages theme--light error--text" role="alert">
									<div class="v-messages__wrapper">
										<div v-if="options.length > 0 && warehouseEmailAddress !== ''"
											class="v-messages__message" >
											{{ tagsInput.errorMessage }}
										</div>

										<div v-if="options.length == 0 && warehouseEmailAddress !== ''"
											class="v-messages__message" >
											{{ tagsInput.errorMessage }}
										</div>
										<div v-if="options.length == 0 && warehouseEmailAddress == ''"
											class="v-messages__message" >
											Please provide at least 1 valid email address.
										</div>
									</div>
								</div>
							</div>
						</v-col>

						<v-col cols="12" sm="12" md="12" class="pb-0">
							<label class="text-item-label">Address</label>
							<v-textarea
								height="76px"
								class="text-fields"
								outlined
								name="input-7-4"
								placeholder="Enter warehouse customer's address"
								v-model="editedItem.address"
								:rules="rules"
								hide-details="auto">
							</v-textarea>
						</v-col>

						<v-col cols="12" sm="12" md="12" class="pb-5">
							<label class="text-item-label">Associated Warehouses 
								<small class="text-capitalize" style="font-size: 10px">(Optional)</small>
							</label>

							<v-autocomplete
								class="text-fields select-items select-warehouses"								
								:items="serviceProviderWarehouses"
								v-model="editedItem.warehouses"
								item-text="name"
								item-value="id"
								outlined
								hide-details="auto"
								:placeholder="get3PLServiceProviderWarehousesLoading ? 'Fetching Warehouses...' : 'Select Warehouses'"
								:menu-props="{ contentClass: 'product-lists-items warehouse-3pl-provider-lists', bottom: true, offsetY: true, closeOnContentClick: false }"
								clearable
								chips
								deletable-chips
								multiple
								:disabled="get3PLServiceProviderWarehousesLoading">
							</v-autocomplete>
						</v-col>
					</v-row>
				</v-form>
			</v-card-text>

			<v-card-actions>
				<v-btn
					class="btn-blue"
					text
					@click="save"
					:disabled="disabledSaveButton || getWarehouseCreateCustomersLoading || getWarehouseUpdateCustomersLoading">

					<span v-if="editedIndexData === -1">
						{{ getWarehouseCreateCustomersLoading ? "Adding..." : "Add Warehouse Customer" }}
					</span>

					<span v-if="editedIndexData > -1">
						{{ getWarehouseUpdateCustomersLoading ? "Saving..." : "Save Edits" }}
					</span>
				</v-btn>

				<v-btn class="btn-white" text @click="close"
					:disabled="getWarehouseCreateCustomersLoading || getWarehouseUpdateCustomersLoading">
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
import { VueTelInput } from "vue-tel-input"
import "vue-tel-input/dist/vue-tel-input.css"
import VueTagsInput from "@johmun/vue-tags-input"
import jQuery from "jquery"
import globalMethods from "../../utils/globalMethods"

export default {
	name: "WarehouseCustomersDialog",
	props: [
		"editedItemData",
		"editedIndexData",
		"dialogData",
		"defaultItemData",
		"warehouseCustomersPagination",
		'searchWarehouseCustomers',
		'warehouseCustomersData'
	],
	components: {
		VueTelInput,
		VueTagsInput,
	},
	data: () => ({
		valid: true,
		options: [],
		value: [],
		isMobile: false,
		rules: [(v) => !!v || "Input is required."],
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
		disabledSaveButton: false
	}),
	computed: {
		...mapGetters({
			getUser: "getUser",
			getWarehouseCreateCustomersLoading: "warehouseCustomers/getWarehouseCreateCustomersLoading",
			getWarehouseUpdateCustomersLoading: 'warehouseCustomers/getWarehouseUpdateCustomersLoading',
			getSearchedWarehouseCustomers: 'warehouseCustomers/getSearchedWarehouseCustomers',
			getCompanyKeyLoading: "suppliers/getCompanyKeyLoading",
			getCompanyKeyResponse: "suppliers/getCompanyKeyResponse",
			getCustomerDetailsData: "suppliers/getCustomerDetailsData",
			get3PLServiceProviderWarehouses: 'warehouseCustomers/get3PLServiceProviderWarehouses',
			get3PLServiceProviderWarehousesLoading: 'warehouseCustomers/get3PLServiceProviderWarehousesLoading'
		}),
		formTitle() {
			return this.editedIndexData === -1 ? "Add Warehouse Customer" : "Edit Warehouse Customer"
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
		addCustomerTemplate() {
			let { name, address, phone, emails, company_key, warehouses } = this.editedItem

			return {
				id: [
					typeof this.getUser == "string"
					? JSON.parse(this.getUser).default_customer_id
					: "",
				],
				company_name: name,
				address,
				phone,
				emails,
				company_key,
				warehouses
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
		serviceProviderWarehouses() {
			let warehouses = []

			if (typeof this.get3PLServiceProviderWarehouses !== 'undefined' && this.get3PLServiceProviderWarehouses !== null) {
				if (typeof this.get3PLServiceProviderWarehouses.data !== 'undefined' && 
					this.get3PLServiceProviderWarehouses.data.length > 0) {
					warehouses = this.get3PLServiceProviderWarehouses.data
				}
			}

			return warehouses
		}
	},
	methods: {
		...mapActions({
			createWarehouseCustomers: "warehouseCustomers/createWarehouseCustomers",
			fetchWarehouseCustomers: "warehouseCustomers/fetchWarehouseCustomers",
			updateWarehouseCustomers: 'warehouseCustomers/updateWarehouseCustomers',
			fetchWarehouseCustomersSearched: 'warehouseCustomers/fetchWarehouseCustomersSearched',
			setSearchedWarehouseCustomerLoading: 'warehouseCustomers/setSearchedWarehouseCustomerLoading',
			setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
			checkCompanyKeyExists: 'suppliers/checkCompanyKeyExists',
			fetchCustomerDetails: 'suppliers/fetchCustomerDetails',
		}),
		...globalMethods,
		generateErrorMessage() {
			this.tagsInput.hasError = this.options.length > 0 ? false : true
			if (this.tagsInput.hasError)
				jQuery(".ti-input").addClass("ti-new-tag-input-error")
			else jQuery(".ti-input").removeClass("ti-new-tag-input-error")

			this.isPhoneNumberEmpty = this.addCustomerTemplate.phone === '' ? true : false
		},
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true
			} else {
				this.isMobile = false
			}
		},
		close() {
			this.$refs.form.resetValidation()
			this.$emit("close")
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
				let parmsWarehouseCustomers = {
					id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
					page: this.warehouseCustomersPagination.current_page,
				}

				if (this.$refs.form.validate()) {
					if (!this.tagsInput.hasError && !this.isPhoneNumberEmpty) {
						try {
							jQuery(".ti-new-tag-input").trigger(
								jQuery.Event("keyup", { keyCode: 13, which: 13 })
							)

							let warehouse_customer_id = this.editedItem.warehouse_customer_company_id

							if (this.editedIndexData === -1) {
								let finalEmailAddress =
									this.options.length > 0 ? this.options.map((o) => {
										return o.text
									}) : []

								let addCustomerTemplate = this.addCustomerTemplate
								addCustomerTemplate.emails = JSON.stringify(finalEmailAddress)

								if (addCustomerTemplate.company_key !== '' && 
									addCustomerTemplate.company_key !== null) {									
									addCustomerTemplate.warehouse_customer_company_id = warehouse_customer_id
								}

								await this.createWarehouseCustomers(this.addCustomerTemplate)
								this.close()
								this.notificationMessage("Warehouse Customer has been created.")
								this.setAllWarehouseCustomerLists([])
								
								if (this.searchWarehouseCustomers !== '') {
									if (typeof this.getSearchedWarehouseCustomers !== 'undefined') {
										let page = typeof this.getSearchedWarehouseCustomers.current_page !== 'undefined' ?
											this.getSearchedWarehouseCustomers.current_page : 1

										let data = { page, id: parmsWarehouseCustomers.id }
										await this.fetchWarehouseCustomersSearchedAPI(data)
									}
								} else {
									await this.fetchWarehouseCustomers(parmsWarehouseCustomers)
								}
							} else {
								let finalEmailAddress =
									this.options.length > 0
										? this.options.map((o) => {
											return o.text
										})
										: []

								const { ...newCustomerTemplate } = this.addCustomerTemplate
								newCustomerTemplate.id = parmsWarehouseCustomers.id
								newCustomerTemplate.wc_id = this.editedItem.id
								newCustomerTemplate.emails = JSON.stringify(finalEmailAddress)

								if (newCustomerTemplate.company_key !== '' && 
									newCustomerTemplate.company_key !== null && 
									newCustomerTemplate.warehouses.length > 0) {									
									newCustomerTemplate.warehouse_customer_company_id = warehouse_customer_id
								}

								await this.updateWarehouseCustomers(newCustomerTemplate)
								this.close()
								this.notificationMessage("Warehouse Customer has been updated.")
								this.setAllWarehouseCustomerLists([])

								if (this.searchWarehouseCustomers !== '') {
									if (typeof this.getSearchedWarehouseCustomers !== 'undefined') {
										let page = typeof this.getSearchedWarehouseCustomers.current_page !== 'undefined' ?
											this.getSearchedWarehouseCustomers.current_page : 1

										let data = { page, id: parmsWarehouseCustomers.id }
										await this.fetchWarehouseCustomersSearchedAPI(data)
									}
								} else {
									await this.fetchWarehouseCustomers(parmsWarehouseCustomers)
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
		async fetchWarehouseCustomersSearchedAPI(data) {
			let passedData = {
				method: "get",
				url: `https://po.shifl.com/api/customer/${data.id}/warehouse-customers`,
				params: {
					search: this.searchWarehouseCustomers,
					page: data.page
				}
			}

            try {
                passedData.tab = 'warehouse_customers'
                await this.fetchWarehouseCustomersSearched(passedData)

				if (this.warehouseCustomersData.length === 0 && data.page !== 1) {
					passedData.params.page = data.page - 1
					await this.fetchWarehouseCustomersSearched(passedData)
				}

				let parms = { id: data.id, page: 1 }

				await this.fetchWarehouseCustomers(parms)
            } catch(e) {
                this.notificationError(e)
                this.setSearchedWarehouseCustomerLoading(false)
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
					this.editedItem.warehouse_customer_company_id = custDetailsData.id
					
					if (custDetailsData.phone) {
						this.editedItem.phone = custDetailsData.phone
					}
					this.editedItem.address = custDetailsData.address
					if (custDetailsData.emails) {
						let newEmailLists = custDetailsData.emails.filter(v => { return v.email !== null })
						var result = newEmailLists.map(item => ({ "text": item.email,"tiClasses": ["ti-valid"] }))
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
					if (this.editedItem.emails !== null &&
						this.editedItem.emails.length > 0) {
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
