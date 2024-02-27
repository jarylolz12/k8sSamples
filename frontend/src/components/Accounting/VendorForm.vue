<template>
	<div class="text-center">
		<v-dialog
			v-model="showDialog"
			max-width="980"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile">

			<v-card :loading="isSaving">
				<v-card-title class="pa-0 z-index-front">
					<span class="headline">{{ isEditMode ? 'Edit Vendor' : 'Add Vendor' }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>

				<v-card-text class="pa-0">
					<v-form ref="vendorForm">
						<v-row no-gutters>
							<v-col md="5" cols="12" class="item-form-column grey lighten-4 pa-4">
								<v-card flat class="grey lighten-4">
									<v-card-text class="transparent mb-0 pa-0">
										<label class="form-label text-uppercase" for="formdata-name">
											{{ 'Name' }}
										</label>
										<v-text-field
											v-model="formData.name"
											@change="onChangeNameValue"
											:label="'Type name of the vendor'"
											solo
											class="app-theme-input-border"
											flat
											required
											dense
											id="formdata-name"
											outlined
											:rules="[(v) => !!v || 'Name is required.']"
											hide-details="auto"
											autocomplete="off">
										</v-text-field>

										<!-- <h5 class="mb-3">{{('contact_Details_Title')}}</h5> -->
										<label class="form-label text-uppercase" for="formdata-phone">
											{{ 'Phone' }}
										</label>
										<vue-tel-input 
											v-model="formData.phone"
											mode="international"
											defaultCountry="us"
											validCharactersOnly
											:autoDefaultCountry="true"
											:dropdownOptions="vueTelDropdownOptions"
											:inputOptions="vueTelInputOptions">

											<template v-slot:arrow-icon>
												<v-icon class="ml-1">mdi-chevron-down</v-icon>
											</template>
										</vue-tel-input>
										<!-- <phone-input v-model="formData.phone"></phone-input> -->

										<label class="form-label text-uppercase" for="formdata-address">
											{{ 'Address' }}
										</label>
										<v-textarea
											v-model="formData.address"
											solo
											:label="'Type the full address of the vendor'"
											class="app-theme-input-border"
											flat
											id="formdata-address"
											rows="2"
											outlined
											hide-details="auto">
										</v-textarea>

										<label class="form-label text-uppercase" for="formdata-email">
											{{ 'Email' }}
										</label>
										<v-text-field
											v-model="formData.email"
											solo
											:label="'e.g. name@email.com'"
											class="app-theme-input-border"
											flat
											id="formdata-email"
											outlined
											dense
											:rules="emailRules"
											hide-details="auto"
											autocomplete="off">
										</v-text-field>

										<label class="form-label text-uppercase" for="formdata-website">
											{{ 'Website' }}
										</label>
										<v-text-field
											v-model="formData.website"
											label="Type the vendor's website url"
											solo
											class="app-theme-input-border"
											outlined
											flat
											required
											dense
											id="formdata-reference"
											hide-details="auto">
										</v-text-field>

										<label class="form-label text-uppercase" for="formdata-reference">
											{{ 'Reference' }}
										</label>
										<v-text-field
											v-model="formData.reference"
											:label="'Type the reference'"
											solo
											class="app-theme-input-border"
											outlined
											flat
											required
											dense
											id="formdata-reference"
											hide-details="auto">
										</v-text-field>
									</v-card-text>
								</v-card>
							</v-col>
							<v-col md="7" cols="12" class="item-form-column white pa-4">
								<v-card flat class="">
									<v-card-text class="mb-0 pa-0">
										<v-row>
											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-currency">
													{{ 'Currency' }}
												</label>
												<v-select
													v-model="formData.currency"
													:items="currencyData"
													:rules="[(v) => !!v || 'Field required.']"
													id="formdata-currency"
													:label="'Select a currency'"
													solo
													flat
													class="app-theme-input-border"
													dense
													outlined
													hide-details="auto"
													:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
												>
													<template v-slot:prepend-inner>
														<span class="primary--text">{{ currencySymbol }}</span>
													</template>
												</v-select>
											</v-col>

											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-tax-number">
													Tax Number
												</label>
												<v-text-field
													v-model="formData.tax_number"
													label="Type the vendor's tax number"
													solo
													class="app-theme-input-border"
													outlined
													flat
													required
													dense
													id="formdata-tax-number"
													hide-details="auto">
												</v-text-field>
											</v-col>

											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-country">
													{{ 'Country' }}
												</label>
												<v-autocomplete
													v-model="formData.country"
													:items="countryData"
													:rules="[(v) => !!v || 'Field required.']"
													id="formdata-country"
													:label="'Select the country'"
													solo
													flat
													class="app-theme-input-border"
													dense
													outlined
													prepend-inner-icon="mdi-flag-variant"
													hide-details="auto"
													item-value="code"
													item-text="name"
													:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
												>
													<template v-slot:prepend-inner>
														<img
															:src="countryFlag"
															height="16"
															width="16"
															v-if="countryFlag"
															class="mr-1"
														/>
													</template>
												</v-autocomplete>
											</v-col>

											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-state">
													{{ 'State' }}
												</label>
												<v-text-field
													v-model="formData.state"
													:label="'Type the Province/State'"
													solo
													class="app-theme-input-border"
													outlined
													flat
													dense
													id="formdata-state"
													hide-details="auto">
												</v-text-field>
											</v-col>

											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-town">
													{{ 'Town/City' }}
												</label>
												<v-text-field
													v-model="formData.city"
													:label="'Type a Town/City'"
													solo
													class="app-theme-input-border"
													outlined
													flat
													dense
													id="formdata-town"
													hide-details="auto">
												</v-text-field>
											</v-col>

											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-zip-code">
													{{ 'Postal/Zip Code' }}
												</label>
												<v-text-field
													v-model="formData.zip_code"
													:label="'Type the postal/zip code'"
													solo
													class="app-theme-input-border"
													outlined
													flat
													dense
													id="formdata-zip-code"
													hide-details="auto">
												</v-text-field>
											</v-col>
										</v-row>

										<v-row v-if="this.isQBOEnabled === 1">
											<!-- <hr class="my-4" /> -->
											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-display-name">
													{{ 'Display Name' }}
												</label>
												<v-text-field
													v-model="formData.qbo_display_name"
													:label="'Display Name'"
													solo
													class="app-theme-input-border"
													outlined
													flat
													dense
													id="formdata-display-name"
													hide-details="auto">
												</v-text-field>
											</v-col>

											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-title">
													{{ 'Title' }}
												</label>
												<v-text-field
													v-model="formData.qbo_title"
													:label="'Type the title'"
													solo
													class="app-theme-input-border"
													outlined
													flat
													dense
													id="formdata-title"
													hide-details="auto"
													counter="16"
													maxlength="16">
												</v-text-field>
											</v-col>

											<v-col cols="12" class="py-0">
												<label class="form-label text-uppercase" for="formdata-given-name">
													{{ 'Given Name' }}
												</label>
												<v-text-field
													v-model="formData.qbo_given_name"
													:label="'Type the given name'"
													solo
													class="app-theme-input-border"
													outlined
													flat
													dense
													id="formdata-given-name"
													hide-details="auto">
												</v-text-field>
											</v-col>
											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-middle-name">
													{{ 'Middle Name' }}
												</label>
												<v-text-field
													v-model="formData.qbo_middle_name"
													:label="'Type the the middle name'"
													solo
													class="app-theme-input-border"
													outlined
													flat
													dense
													id="formdata-middle-name"
													hide-details="auto">
												</v-text-field>
											</v-col>

											<v-col cols="12" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-family-name">
													{{ 'Family Name' }}
												</label>
												<v-text-field
													v-model="formData.qbo_family_name"
													:label="'Type the family name'"
													solo
													class="app-theme-input-border"
													outlined
													flat
													dense
													id="formdata-family-name"
													hide-details="auto">
												</v-text-field>
											</v-col>

											<v-col cols="12">
												<label class="form-label text-uppercase" for="formdata-suffix-name">
													{{ 'Suffix' }}
												</label>
												<v-text-field
													v-model="formData.qbo_suffix"
													:label="'Type the suffix'"
													solo
													class="app-theme-input-border"
													outlined
													flat
													dense
													id="formdata-suffix-name"
													hide-details="auto">
												</v-text-field>
											</v-col>
										</v-row>
									</v-card-text>
								</v-card>
							</v-col>
						</v-row>
					</v-form>
				</v-card-text>				

				<!-- <v-divider></v-divider> -->

				<v-card-actions class="justify-start">
					<v-btn
						@click="onSaveForm"
						class="text-capitalize btn-primary btn-blue"
						:loading="isSaving"
						:disabled="isSaving"
						v-if="!isEditMode">
						{{ 'Save' }}
					</v-btn>
					<v-btn
						@click="onUpdateForm"
						class="text-capitalize btn-primary btn-blue"
						:loading="isSaving"
						:disabled="isSaving"
						v-if="isEditMode">
						{{ 'Update' }}
					</v-btn>
					<v-btn outlined text class="text-capitalize primary--text btn-white" @click="onClose" :disabled="isSaving">
						{{ 'Cancel' }}
					</v-btn>
					<v-spacer></v-spacer>
				</v-card-actions>
			</v-card>
			<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar> -->
		</v-dialog>
	</div>
</template>

<script>
import countries from './data/world-countries/countries/en/countries.json';
import countryflags from './data/world-countries/flags/16x16/flags-16x16.json';
// import PhoneInput from './PhoneInput.vue';
import { VueTelInput } from 'vue-tel-input'
import 'vue-tel-input/dist/vue-tel-input.css'

import { mapActions, mapState, mapGetters } from 'vuex';
import globalMethods from '../../utils/globalMethods'

export default {
	name: 'VendorForm',
	components: {
		// PhoneInput
		VueTelInput
	},
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			formData: {
				name: '',
				phone: '',
				address: '',
				email: '',
				tax_number: '',
				website: '',
				city: '',
				zip_code: '',
				state: '',
				country: '',
				reference: '',
				qbo_display_name: '',
				qbo_title: '',
				qbo_given_name: '',
				qbo_middle_name: '',
				qbo_family_name: '',
				qbo_suffix: ''
			},
			defaultFields: {},
			isSaving: false,
			showSnackbar: false,
			snackbarOption: {},
			emailRules: [
				(v) => (v && /.+@.+/.test(v)) || (v && 'E-mail must be valid') || true,
				(v) => !!v || 'Email is required.'
			],
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
		};
	},

	created() {
		this.defaultFields = { ...this.formData };
		this.getCurrencies();
	},

	watch: {
		formValues(values) {
			if (values) {
				const {
					name,
					phone,
					address,
					email,
					tax_number,
					currency_values,
					website,
					city,
					zip_code,
					state,
					country,
					reference,
					qbo_values,
					id,
					qbo_id
				} = values;

				const qbo = JSON.parse(qbo_values) || {};

				this.formData = {
					id,
					name,
					email,
					tax_number,
					currency: JSON.stringify(JSON.parse(currency_values)),
					phone: phone === null ? '' : phone,
					website,
					address,
					city,
					zip_code,
					state,
					country,
					reference,
					qbo_display_name: qbo.DisplayName,
					qbo_title: qbo.Title,
					qbo_given_name: qbo.GivenName,
					qbo_middle_name: qbo.MiddleName,
					qbo_family_name: qbo.FamilyName,
					qbo_suffix: qbo.Suffix,
					qbo_id
				};
			} else {
				this.formData = {};
			}
		}
	},

	computed: {
		...mapState('accounting', ['currencies']),
		...mapGetters('accounting', ['isQBOEnabled']),

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		},

		currencySymbol: {
			get() {
				if (this.formData.currency) {
					const selectedCurrency = JSON.parse(this.formData.currency);
					return this.currencies.find((currency) => currency.code === selectedCurrency.code)?.symbol;
				}
				return '';
			}
		},

		showDialog: {
			get() {
				return this.open;
			}
		},

		countryData: {
			get() {
				return countries.map((country) => ({ code: country.alpha3.toUpperCase(), name: country.name }));
			}
		},

		countryFlag: {
			get() {
				if (this.formData.country) {
					const country = countries.find(
						(country) => country.alpha3 === this.formData.country.toLowerCase()
					);
					return country ? countryflags[country.alpha2] : '';
				}
				return '';
			}
		},

		currencyData() {
			return this.currencies.map((currency) => ({
				text: currency.code,
				value: JSON.stringify({ code: currency.code, name: currency.name })
			}));
		}
	},

	methods: {
		...mapActions('accounting', ['createVendorForm', 'updateVendorForm', 'getCurrencies']),
		...globalMethods,
		onChangeNameValue(value) {
			if (!this.formData.qbo_display_name) {
				this.$set(this.formData, 'name', value);
				this.$set(this.formData, 'display_name', value);
			}
		},

		onClose() {
			this.formData = {
				...this.defaultFields
			};
			this.$refs.vendorForm.reset();
			this.$emit('toggle');
		},

		async onSaveForm() {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.vendorForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const { data } = await this.createVendorForm({
						...this.formData,
						qbo_enabled: this.isQBOEnabled
					});

					const message = data.message || 'Data was successfully saved.';
					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };

					this.$refs.vendorForm.reset();
					this.$emit('toggle', { created: true, message });
				} catch (error) {
					const { data } = error.response || { data: {} };

					// this.snackbarOption = {
					// 	icon: 'mdi-alert-circle',
					// 	color: 'error',
					// 	message: data.message || 'Could not save the data.'
					// };

					const message = data.message || 'Could not save the data.'
					this.notificationCustom(message)
				} finally {
					this.showSnackbar = true;
					this.isSaving = false;
				}
			}
		},

		async onUpdateForm() {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.vendorForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const { data } = await this.updateVendorForm({
						...this.formData,
						qbo_enabled: this.isQBOEnabled
					});

					const message = data.message || 'Data was successfully saved.';
					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };

					this.$refs.vendorForm.reset();
					this.$emit('toggle', { updated: true, message });
				} catch (error) {
					const { data } = error.response || { data: {} };

					// this.snackbarOption = {
					// 	icon: 'mdi-alert-circle',
					// 	color: 'error',
					// 	message: data.message || 'Could not save the data.'
					// };

					const message = data.message || 'Could not save the data.'
					this.notificationCustom(message)
				} finally {
					this.showSnackbar = true;
					this.isSaving = false;
				}
			}
		}
	}
};
</script>

<style lang="scss">
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';
</style>

<style lang="scss" scoped>
	$button-bg-color: #0171a1;
	$form-label: #819fb2;
	.form-label {
		color: $form-label;
	}
	.w-100 {
		width: 100%;
	}
	.display-none {
		display: none;
	}

	hr {
		border-color: #ebf1f5;
	}

	th {
		color: $form-label;
		font-weight: bold;
	}
	.btn-primary {
		background-color: $button-bg-color !important;
		color: #fff !important;
	}
	.border-dashed {
		border: 1px dashed $form-label !important;
	}
	::v-deep {
		.v-dialog {
			.v-input__control {
				background: transparent !important;
			}

			.v-text-field--enclosed .v-input__prepend-inner {
				margin-top: 0 !important;
			}

			.vue-tel-input {
				margin-bottom: 12px;

				&:focus-within {
					box-shadow: none;
					border: 1px solid #0171A1 !important;
				}

				.vti__dropdown {
					.vti__flag {
						margin-right: 7px;
					}
				}
				
				.vti__input {
					padding-left: 1px;
					color: #4a4a4a !important;

					&.vti__search_box {                                    
						border: 1px solid #B4CFE0 !important;
						width: 94%;
						padding: 8px 12px !important;
						margin: 6px 8px;
					}
				}
				
				.vti__dropdown-list {
					&.below {
						max-width: 320px;
						width: 320px;
						border: 1px solid #B4CFE0 !important;

						/* Scrollbar */
						&::-webkit-scrollbar {
							width: 10px;
						}
						
						/* Track */
						&::-webkit-scrollbar-track {
							background-color: #f1f1f1; 
						}
						
						/* Handle */
						&::-webkit-scrollbar-thumb {
							background-color: #e2e2e2;
						}
						
						/* Handle on hover */
						&::-webkit-scrollbar-thumb:hover {
							background-color: #e2e2e2;
						}
					}
				}

				.vti__country-code {
					color: #4a4a4a !important;
					font-size: 14px;
				}
			}
		}

		.v-select--is-menu-active {
			background: transparent !important;
		}

		fieldset {
			border: 1px solid #b3cfe0;
			font-size: 14px;
		}

		fieldset:focus {
			border: 1px solid #b3cfe0 !important;
			outline: 0;
		}

		.v-text-field__slot input::placeholder {
			color: #b4cfe0 !important;
		}
	}
</style>
