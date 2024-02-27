<template>
	<v-dialog
		v-model="showDialog"
		max-width="680"
		origin="top center"
		content-class="accounting-module-dialog pa-0"
		persistent
		scrollable
		:fullscreen="isMobile"
	>
		<v-card :loading="isSaving">
			<v-card-title class="">
				<span class="headline">{{ isEditMode ? 'Edit Account' : 'Add Account' }}</span>
				<v-spacer></v-spacer>
				<v-btn icon @click="onClose">
					<v-icon>mdi-close</v-icon>
				</v-btn>
			</v-card-title>

			<v-card-text class="px-0 pb-0 mb-0">
				<v-form ref="accountForm">
					<v-row no-gutters>
						<v-col cols="12" sm="12">
							<v-card flat class="item-form-column">
								<v-card-text class="pa-0">
									<v-row>
										<v-col cols="12" md="6" class="pb-0">
											<label class="text-item-label" for="formdata-currency">Type</label>
											<v-select
												v-model="formData.type"
												:items="typeList"
												label="Select type"
												solo
												class="app-theme-input-border"
												outlined
												flat
												dense
												id="formdata-type"
												item-text="name"
												item-value="value"
												hide-details="auto"
												:rules="[(v) => !!v || 'Field required']"
											></v-select>
										</v-col>
									</v-row>
									<v-row>
										<v-col cols="12" md="6" class="pb-0">
											<label class="text-item-label" for="formdata-name">{{ 'name' }}</label>
											<v-text-field
												v-model="formData.name"
												label="Enter Name"
												solo
												class="app-theme-input-border"
												outlined
												flat
												required
												dense
												id="formdata-name"
												hide-details="auto"
												:rules="[(v) => !!v || 'Field required']"
											></v-text-field>
										</v-col>

										<v-col cols="12" md="6" class="pb-0">
											<label class="text-item-label" for="formdata-tax-number">Number</label>
											<v-text-field
												v-model="formData.number"
												label="Enter Number"
												solo
												class="app-theme-input-border"
												outlined
												flat
												required
												dense
												id="formdata-tax-number"
												hide-details="auto"
												:rules="[(v) => !!v || 'Field required']"
											></v-text-field>
										</v-col>

										<v-col cols="12" md="6" class="pb-0">
											<label class="text-item-label" for="formdata-currency">Currency</label>
											<v-select
												v-model="formData.currency_code"
												:items="currencyList"
												label="Select Currency"
												solo
												class="app-theme-input-border"
												outlined
												flat
												dense
												id="formdata-currency"
												item-text="name"
												item-value="code"
												hide-details="auto"
												:rules="[(v) => !!v || 'Field required']"
												:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
											></v-select>
										</v-col>

										<v-col cols="12" md="6" class="pb-0">
											<label class="text-item-label" for="formdata-opening_balance"
												>Opening Balance</label
											>
											<v-text-field
												v-model="formData.opening_balance"
												solo
												class="app-theme-input-border"
												outlined
												flat
												dense
												id="formdata-opening_balance"
												hide-details="auto"
												type="number"
												placeholder="Enter balance"
											>
												<template v-slot:prepend-inner>
													<div class="d-flex align-center">
														<!-- <v-icon>mdi-scale-balance</v-icon>
                                <v-spacer>&nbsp;</v-spacer> -->
														<span class="currency">{{ currencySymbol }}</span>
													</div>
												</template>
											</v-text-field>
										</v-col>

										<v-col cols="12" md="6" class="pb-0">
											<label class="text-item-label" for="formdata-bank_name">Bank Name</label>
											<v-text-field
												v-model="formData.bank_name"
												label="Enter Bank Name"
												solo
												class="app-theme-input-border"
												outlined
												flat
												dense
												id="formdata-bank_name"
												hide-details="auto"
											></v-text-field>
										</v-col>

										<v-col cols="12" md="6" class="pb-0">
											<label class="text-item-label" for="formdata-bank_phone">Bank Phone</label>
											<v-text-field
												v-model="formData.bank_phone"
												label="Enter Bank Phone"
												solo
												class="app-theme-input-border"
												outlined
												flat
												dense
												id="formdata-bank_phone"
												hide-details="auto"
											></v-text-field>
										</v-col>

										<v-col cols="12" class="pb-0">
											<label class="text-item-label" for="formdata-bank_address"
												>Bank Address</label
											>
											<v-textarea
												v-model="formData.bank_address"
												solo
												label="Enter Bank Address"
												class="app-theme-input-border"
												flat
												id="formdata-bank_address"
												rows="2"
												outlined
												hide-details="auto"
											></v-textarea>
										</v-col>

										<v-col cols="12">
											<div class="d-flex">
												<!-- <div>
                              <label class="text-item-label">{{ ('default_account') }}</label>
                              <div>
                                <v-btn small rounded outlined :color="formData.default_account ? 'success': 'error'" class="pa-4" @click="onToggleDefaultAccount">
                                  <div v-if="formData.default_account" class="d-flex align-center justify-space-around" style="min-width: 60px">
                                    {{ ('yes') }}
                                    <v-icon>mdi-check-circle</v-icon>
                                  </div>
                                  <div v-else class="d-flex align-center justify-space-around" style="min-width: 60px">
                                    {{ ('no') }}
                                    <v-icon>mdi-minus-circle</v-icon>
                                  </div>
                                </v-btn>
                              </div>
                            </div> -->
												<div>
													<label class="text-item-label">{{ 'enabled' }}</label>
													<div>
														<v-btn
															small
															rounded
															outlined
															:color="formData.enabled ? 'success' : 'error'"
															class="pa-4"
															@click="onToggleEnabled"
														>
															<div
																v-if="formData.enabled"
																class="d-flex align-center justify-space-around"
																style="min-width: 60px"
															>
																{{ 'yes' }}
																<v-icon>mdi-check-circle</v-icon>
															</div>
															<div
																v-else
																class="d-flex align-center justify-space-around"
																style="min-width: 60px"
															>
																{{ 'no' }}
																<v-icon>mdi-minus-circle</v-icon>
															</div>
														</v-btn>
													</div>
												</div>
											</div>
										</v-col>
									</v-row>
								</v-card-text>
							</v-card>
						</v-col>
					</v-row>
				</v-form>
			</v-card-text>

			<v-divider></v-divider>

			<v-card-actions class="justify-start">
				<v-btn
					@click="onSaveForm"
					class="text-capitalize btn-primary btn-blue"
					:loading="isSaving"
					:disabled="isSaving"
					v-if="!isEditMode"
					>{{ 'save' }}</v-btn
				>
				<v-btn
					@click="onSaveForm"
					class="text-capitalize btn-primary btn-blue"
					:loading="isSaving"
					:disabled="isSaving"
					v-if="isEditMode"
					>{{ 'update' }}</v-btn
				>
				<v-btn outlined text class="text-capitalize primary--text btn-white" @click="onClose" :disabled="isSaving">{{
					'cancel'
				}}</v-btn>
				<v-spacer></v-spacer>
			</v-card-actions>
		</v-card>
		<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar> -->
	</v-dialog>
</template>

<script>
// import countries from '../data/world-countries/countries/en/countries.json';
// import countryflags from '../data/world-countries/flags/16x16/flags-16x16.json';

import { mapActions, mapState, mapGetters } from 'vuex';

import AkauntingService from '../../services/akaunting.service';
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'AccountForm',
	components: {},
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			formData: {
				name: '',
				number: '',
				currency_code: '',
				opening_balance: 0,
				bank_name: '',
				bank_phone: '',
				bank_address: '',
				enabled: 1
				// default_account: 0,
			},
			defaultFields: {},
			isSaving: false,
			showSnackbar: false,
			snackbarOption: {},
			currencyList: [],
			typeList: [
				{ name: 'Bank', value: 'bank'},
				{ name: 'Credit Card', value: 'credit_card'},
			]
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
					id,
					type,
					name,
					number,
					currency_code,
					opening_balance,
					bank_name,
					bank_phone,
					bank_address,
					enabled
					// default_account,
				} = values;

				this.formData = {
					id,
					type,
					name,
					number,
					currency_code,
					opening_balance,
					bank_name,
					bank_phone,
					bank_address,
					enabled: enabled ? 1 : 0
					// default_account,
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
				if (this.formData.currency_code) {
					const selectedCurrency = this.formData.currency_code;
					return this.currencyList.find((currency) => currency.code === selectedCurrency)?.symbol;
				}
				return '';
			}
		},

		showDialog: {
			get() {
				return this.open;
			}
		}
	},

	methods: {
		...mapActions('accounting', ['createAccountForm', 'updateAccountForm']),
		...globalMethods,
		async getCurrencies() {
			try {
				const response = await AkauntingService.getCurrencies();
				this.currencyList = response?.data?.data?.data || [];
			} catch (error) {
				//
			}
		},

		onToggleEnabled() {
			const enabled = !this.formData.enabled;
			this.formData = {
				...this.formData,
				enabled: enabled ? 1 : 0
			};
		},

		/* onToggleDefaultAccount() {
	const default_account = !this.formData.default_account;
	this.formData = {
	...this.formData,
	default_account: default_account ? 1 : 0,
	}
}, */

		onClose() {
			this.formData = {
				...this.defaultFields
			};
			this.$refs.accountForm.reset();
			this.$emit('toggle');
		},

		async onSaveForm() {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.accountForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const form = { ...this.formData, qbo_enabled: this.isQBOEnabled };
					const { data } = this.isEditMode
						? await this.updateAccountForm(form)
						: await this.createAccountForm(form);

					const message = data.message || 'Data was successfully saved.';
					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };

					this.$refs.accountForm.reset();
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
