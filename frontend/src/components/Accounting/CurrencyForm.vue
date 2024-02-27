<template>
	<div class="text-center">
		<v-form ref="currencyForm">
			<v-dialog
				v-model="showDialog"
				max-width="500"
				origin="top center"
				content-class="accounting-module-dialog pa-0"
				persistent
				scrollable
				:fullscreen="isMobile"
			>
				<v-card :loading="isSaving">
					<v-card-title class="pa-0 z-index-front">
						<span class="headline">{{ isEditMode ? 'Edit Currency' : 'Add Currency' }}</span>
						<v-spacer></v-spacer>
						<v-btn icon @click="onClose">
							<v-icon>mdi-close</v-icon>
						</v-btn>
					</v-card-title>
					<v-card-text class="pt-3 item-form-column">
						<v-row no-gutters justify="space-between">
							<v-col cols="12">
								<label class="form-label text-uppercase" for="formdata-name">{{
									'Currency Name'
								}}</label>
								<v-autocomplete
									v-model="selectedCurrencyName"
									:label="'Input Some Value'"
									solo
									:items="currencyJson"
									item-text="name"
									return-object
									class="app-theme-input-border"
									flat
									required
									dense
									outlined
									:rules="[(v) => !!v || 'Field required']"
									@change="currencyNameWasChanged"
									hide-details="auto"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items currency-form' }"
								></v-autocomplete>
							</v-col>
							<v-col cols="12" md="6" class="pr-0 pr-sm-2">
								<label class="form-label text-uppercase" for="formdata-name">{{
									'Currency Rate'
								}}</label>
								<v-text-field
									v-model.number="formData.rate"
									type="number"
									:label="'Input Some Value'"
									solo
									class="app-theme-input-border"
									flat
									required
									dense
									outlined
									:rules="[(v) => !!v || 'Field required']"
									hide-details="auto"
								></v-text-field>
							</v-col>
							<v-col cols="12" md="6">
								<label class="form-label text-uppercase" for="formdata-name">{{ 'Code' }}</label>
								<v-text-field
									v-model="formData.code"
									:label="'Input Some Value'"
									solo
									class="app-theme-input-border"
									flat
									required
									dense
									outlined
									:rules="[(v) => !!v || 'Field required']"
									hide-details="auto"
								></v-text-field>
							</v-col>
							<v-col cols="12" md="6" class="pr-0 pr-sm-2">
								<label class="form-label text-uppercase" for="formdata-name">{{ 'Symbol' }}</label>
								<v-text-field
									v-model="formData.symbol"
									:label="'Input Some Value'"
									solo
									class="app-theme-input-border"
									flat
									required
									dense
									outlined
									:rules="[(v) => !!v || 'Field required']"
									hide-details="auto"
								></v-text-field>
							</v-col>
							<v-col cols="12" md="6">
								<label class="form-label text-uppercase" for="formdata-name">{{
									'Symbol Position'
								}}</label>
								<v-select
									v-model="formData.symbol_first"
									:items="symbolFirstSelection"
									solo
									flat
									class="app-theme-input-border"
									dense
									outlined
									hide-details="auto"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items currency-form' }"
								>
								</v-select>
							</v-col>
							<v-col cols="12" md="6" class="pr-0 pr-sm-2">
								<label class="form-label text-uppercase" for="formdata-name">{{ 'Precision' }}</label>
								<v-select
									v-model="formData.precision"
									:items="precisionSelection"
									solo
									flat
									class="app-theme-input-border"
									dense
									outlined
									hide-details="auto"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items currency-form' }"
								>
								</v-select>
							</v-col>
							<v-col cols="12" md="6">
								<label class="form-label text-uppercase" for="formdata-name">{{
									'Decimal Mark'
								}}</label>
								<v-text-field
									v-model.number="formData.decimal_mark"
									:label="'Input Some Value'"
									solo
									class="app-theme-input-border"
									flat
									required
									dense
									outlined
									:rules="[(v) => !!v || 'Field required']"
									hide-details="auto"
								></v-text-field>
							</v-col>
							<v-col cols="12" md="6" class="pr-0 pr-sm-2">
								<label class="form-label text-uppercase" for="formdata-thousand-separator">{{
									'Thousand Separator'
								}}</label>
								<v-text-field
									v-model.number="formData.thousands_separator"
									:label="'Input Some Value'"
									solo
									class="app-theme-input-border"
									flat
									required
									dense
									outlined
									id="formdata-thousand-separator"
									hide-details="auto"
								></v-text-field>
							</v-col>
							<v-col cols="12" md="6">
								<label class="form-label text-uppercase" for="formdata-name">{{
									'Default Currency'
								}}</label>
								<v-select
									v-model="formData.default_currency"
									:items="defaultCurrencyList"
									solo
									flat
									class="app-theme-input-border"
									dense
									outlined
									hide-details="auto"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items currency-form' }"
								>
								</v-select>
							</v-col>
						</v-row>
					</v-card-text>
					<v-divider></v-divider>
					<v-card-actions class="justify-start">
						<v-btn
							@click="onSaveForm"
							class="text-capitalize btn-primary btn-blue"
							v-if="!isEditMode"
							:disabled="isSaving"
							:loading="isSaving"
							>{{ 'Save' }}</v-btn
						>
						<v-btn
							@click="onSaveForm"
							class="text-capitalize btn-primary btn-blue"
							v-if="isEditMode"
							:loading="isSaving"
							:disabled="isSaving"
							>{{ 'Update' }}</v-btn
						>
						<v-btn
							text
							outlined
							class="text-capitalize primary--text btn-white"
							@click="onClose"
							:disabled="isSaving"
							>{{ 'Cancel' }}</v-btn
						>
						<v-spacer></v-spacer>
					</v-card-actions>
				</v-card>
				<!-- <v-snackbar timeout="5000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
					<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
				</v-snackbar> -->
			</v-dialog>
		</v-form>
	</div>
</template>
<script>
import currencyJson from './data/currencies.json';
import { mapActions, mapMutations, mapState } from 'vuex';
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'CurrencyForm',
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			isSaving: false,
			selectedCurrencyName: null,
			formData: {
				thousands_separator: ',',
				name: '',
				rate: 0.0,
				code: '',
				symbol: '',
				symbol_first: 1,
				precision: 0,
				decimal_mark: '.',
				default_currency: 0
			},
			defaultFields: {},
			currencyJson: currencyJson,
			showSnackbar: false,
			snackbarOption: {}
		};
	},
	created() {
		this.defaultFields = { ...this.formData };
	},
	computed: {
		...mapState('accounting', ['companyInformation']),
		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		},
		precisionSelection() {
			return [0, 1, 2, 3, 4];
		},
		symbolFirstSelection() {
			return [
				{ text: 'After Amount', value: 0 },
				{ text: 'Before Amount', value: 1 }
			];
		},
		showDialog: {
			get() {
				return this.open;
			}
		},

		defaultCurrencyList() {
			return [
				{
					text: 'Yes',
					value: 1
				},
				{
					text: 'No',
					value: 0
				}
			];
		}
	},

	watch: {
		showDialog(isTrue) {
			if (isTrue) {
				const obj = this.formValues;
				if (obj && this.isEditMode) {
					this.formData = {
						thousands_separator: obj.thousands_separator,
						name: obj.name,
						rate: obj.rate,
						code: obj.code,
						symbol: obj.symbol,
						symbol_first: obj.symbol_first,
						precision: obj.precision,
						decimal_mark: obj.decimal_mark,
						default_currency: obj.default_currency || 0,
						id: obj.id
					};
					this.selectedCurrencyName = this.currencyJson.find(({ name }) => name === obj.name);
					this.$refs.currencyForm.resetValidation();
				} else {
					this.selectedCurrencyName = null;
					this.formData = { ...this.defaultFields };
					this.$refs.currencyForm.resetValidation();
				}
			}
		}
	},

	methods: {
		...mapActions('accounting', ['createCurrencyForm', 'updateCurrencyForm']),
		...mapMutations('accounting', ['updateAccountingCompany']),
		...globalMethods,
		currencyNameWasChanged({ name, symbolNative, decimalDigits, code }) {
			this.formData = Object.assign({}, this.formData, {
				name,
				code,
				symbol: symbolNative,
				precision: decimalDigits
			});
		},
		onClose() {
			this.$refs.currencyForm.reset();
			this.$emit('toggle');
		},

		async onSaveForm() {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.currencyForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const isEdit = this.isEditMode;
					const formData = { ...this.formData };
					const data = isEdit
						? await this.updateCurrencyForm(formData)
						: await this.createCurrencyForm(formData);
					const message = data.message || 'Data was successfully saved.';

					if (formData.default_currency === 1) {
						const currencyCode = formData.code;

						this.updateAccountingCompany({
							...this.companyInformation,
							accounting_details: {
								...this.companyInformation.accounting_details,
								currency: currencyCode
							}
						});
					}

					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };
					this.$emit('toggle', { created: true, message });
				} catch (error) {
					console.log(error);

					const { data } = error.response || { data: {} };

					// this.snackbarOption = {
					// 	icon: 'mdi-alert-circle',
					// 	color: 'error',
					// 	message: data.message || 'Could not save the data.'
					// };

					const message = data.message || 'Could not save the data.'
					this.notificationError(message)
				} finally {
					this.showSnackbar = true;
					this.isSaving = false;
				}
			}
		}
	}
};
</script>
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

			.v-chip {
				font-size: 12px;
				height: 30px;
				text-transform: capitalize !important;

				.v-chip__content {					
					font-family: 'Inter-Medium', sans-serif;
				}
			}

			.v-input {
				&.v-input--is-disabled {
					fieldset {
						background-color: #ebf2f5 !important;
						border: 1px solid #ebf2f5 !important;
					}
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
