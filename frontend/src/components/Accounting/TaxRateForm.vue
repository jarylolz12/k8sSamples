<template>
	<div class="text-center">
		<v-form ref="taxRateForm">
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
						<span class="headline">{{ isEditMode ? 'Edit Tax Rate' : 'Add Tax Rate' }}</span>
						<v-spacer></v-spacer>
						<v-btn icon @click="onClose">
							<v-icon>mdi-close</v-icon>
						</v-btn>
					</v-card-title>
					<v-card-text class="pt-3 item-form-column">
						<v-row no-gutters justify="space-between">
							<v-col cols="12">
								<label class="form-label text-uppercase" for="formdata-name">Name</label>
								<v-text-field
									v-model="formData.name"
									id="formdata-name"
									solo
									class="app-theme-input-border"
									flat
									required
									dense
									outlined
									:rules="[(v) => !!v || 'Field required']"
									hide-details="auto"
									placeholder="Enter Name"
								></v-text-field>
							</v-col>
							<v-col cols="12">
								<label class="form-label text-uppercase" for="formdata-rate">Rate</label>
								<v-text-field
									v-model.number="formData.rate"
									solo
									class="app-theme-input-border"
									id="formdata-rate"
									flat
									required
									dense
									outlined
									:rules="[(v) => !!v || 'Field required']"
									type="number"
									hide-details="auto"
								></v-text-field>
							</v-col>
							<v-col cols="12">
								<label class="form-label text-uppercase" for="formdata-name">Type</label>
								<v-select
									v-model="formData.type"
									:items="types"
									solo
									flat
									class="app-theme-input-border"
									dense
									outlined
									hide-details="auto"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								>
								</v-select>
							</v-col>
							<v-col cols="12">
								<label class="labelcolor--text text-uppercase form-label">Enable</label>
								<br />
								<v-btn
									small
									rounded
									outlined
									class="pa-2"
									:color="formData.enabled ? 'success' : 'error'"
									@click="formData.enabled = formData.enabled === 1 ? 0 : 1"
								>
									<div
										v-if="formData.enabled"
										class="d-flex align-center justify-space-around"
										style="min-width: 60px"
									>
										{{ 'Yes' }}
										<v-icon>mdi-check-circle</v-icon>
									</div>
									<div
										v-else
										class="d-flex align-center justify-space-around"
										style="min-width: 60px"
									>
										<v-icon>mdi-minus-circle</v-icon>
										{{ 'No' }}
									</div>
								</v-btn>
							</v-col>
						</v-row>
					</v-card-text>
					<v-divider></v-divider>
					<v-card-actions class="justify-start">
						<v-btn
							@click="onSaveForm"
							class="text-capitalize btn-blue"
							v-if="!isEditMode"
							:loading="isSaving"
							:disabled="isSaving"
							>{{ 'Save' }}</v-btn
						>
						<v-btn
							@click="onSaveForm"
							class="text-capitalize btn-blue"
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
import { mapActions } from 'vuex';
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'TaxRateForm',
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			isSaving: false,
			selectedCurrencyName: null,
			formData: {
				name: '',
				rate: 0,
				type: 'normal',
				enabled: 1
			},
			defaultFields: {},
			showSnackbar: false,
			snackbarOption: {},
			types: [
				{
					text: 'Compound',
					value: 'compound'
				},
				{
					text: 'Fixed',
					value: 'fixed'
				},
				{
					text: 'Inclusive',
					value: 'inclusive'
				},
				{
					text: 'Normal',
					value: 'normal'
				},
				{
					text: 'Withholding',
					value: 'withholding'
				}
			]
		};
	},
	created() {
		this.defaultFields = JSON.parse(JSON.stringify(this.formData));
	},
	computed: {
		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		},

		showDialog: {
			get() {
				return this.open;
			}
		}
	},

	watch: {
		showDialog(isTrue) {
			if (isTrue) {
				const data = this.formValues;
				if (data && this.isEditMode) {
					this.formData = {
						id: data.id,
						name: data.name,
						enabled: data.enabled ? 1 : 0,
						rate: data.rate
					};
					this.$refs.taxRateForm.resetValidation();
				} else {
					this.selectedCurrencyName = null;
					this.formData = { ...this.defaultFields };
					this.$refs.taxRateForm.resetValidation();
				}
			}
		}
	},

	methods: {
		...mapActions('accounting', ['createTaxRate', 'updateTaxRate']),
		...globalMethods,
		onClose() {
			this.$refs.taxRateForm.reset();
			this.$emit('toggle');
		},

		async onSaveForm() {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.taxRateForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const isEdit = this.isEditMode;
					const formData = { ...this.formData };
					const data = isEdit ? await this.updateTaxRate(formData) : await this.createTaxRate(formData);
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
