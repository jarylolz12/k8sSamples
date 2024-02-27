<template>
	<div class="">
		<v-dialog v-model="showDialog" max-width="800" origin="top center" content-class="accounting-module-dialog pa-0" persistent scrollable>
			<v-card :loading="isSaving">
				<v-card-title class="pa-0 z-index-front">
					<span class="headline">{{ isEditMode ? 'Edit Chart of Account' : 'Add Chart of Account' }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>

				<v-card-text class="item-form-column">
					<v-form ref="chartForm" lazy-validation v-model="formValid">
						<v-row>
							<v-col md="6" cols="12" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-item-name">{{ 'Name' }}</label>
								<v-text-field
									v-model="formData.name"
									:label="'Type item name'"
									:rules="[(v) => !!v || 'Field required']"
									solo
									outlined
									flat
									required
									dense
									id="formdata-item-name"
									hide-details="auto"
								></v-text-field>
							</v-col>
							<v-col md="6" cols="12" class="pb-0">
								<label class="form-label" for="form-code">{{ 'Code' }}</label>
								<v-text-field
									type="number"
									v-model.number="formData.code"
									:label="'Code'"
									:rules="[(v) => !!v || 'Field required']"
									solo
									outlined
									flat
									required
									dense
									id="formdata-code"
									hide-details="auto"
								/>
							</v-col>
							<v-col md="6" cols="12" class="pb-0">
								<div class="d-flex align-center justify-space-between">
									<label class="form-label text-uppercase" for="form-qbo-account-type">{{
										'Type'
									}}</label>
									<v-icon @click="fetchAccountTypes" v-if="accountTypeList.length === 0"
										>mdi-refresh</v-icon
									>
								</div>
								<v-autocomplete
									v-model="formData.type_id"
									:items="accountTypeList"
									id="form-qbo-account-type"
									flat
									outlined
									dense
									:rules="[(v) => !!v || 'Field required']"
									@change="onChangeAccountType"
									hide-details="auto"
									placeholder="Select Type"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items chart-of-accounts' }"
								>
								</v-autocomplete>
							</v-col>
							<v-col cols="12" md="6" class="pb-0">
								<label class="form-label text-uppercase">{{ 'Is Sub-account' }}</label>
								<div>
									<v-btn
										small
										rounded
										outlined
										:color="formData.is_sub_account ? 'success' : 'error'"
										class="pa-4"
										@click="onToggleIsSubAccount"
									>
										<div
											v-if="formData.is_sub_account"
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
											{{ 'No' }}
											<v-icon>mdi-minus-circle</v-icon>
										</div>
									</v-btn>
								</div>
							</v-col>
							<v-col cols="12" md="6" class="pb-0">
								<div class="d-flex align-center justify-space-between">
									<label
										class="form-label text-uppercase"
										for="form-parent-account"
										:class="{ 'grey--text': formData.is_sub_account === 0 }"
										>{{ 'Parent Account' }}</label
									>
								</div>
								<v-autocomplete
									v-model="formData.account_id"
									:items="parentAccountList"
									id="form-qbo-account-subtype"
									flat
									outlined
									dense
									:loading="isParentAccountsLoading"
									:disabled="formData.is_sub_account === 0"
									:rules="[(v) => !!v || formData.is_sub_account === 0 || 'Field required']"
									placeholder="Select Parent Account"
									hide-details="auto"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								>
								</v-autocomplete>
							</v-col>
							<v-col cols="12" class="pb-0">
								<label for="form-description" class="form-label text-uppercase">{{
									'Description'
								}}</label>
								<v-textarea
									v-model="formData.description"
									id="form-description"
									rows="3"
									outlined
									hide-details="auto"
									placeholder="Type description..."
								/>
							</v-col>
							<v-col cols="12">
								<label class="form-label text-uppercase">{{ 'Enabled' }}</label>
								<div>
									<v-btn
										small
										rounded
										outlined
										:color="formData.enabled ? 'success' : 'error'"
										class="pa-4"
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
											{{ 'No' }}
											<v-icon>mdi-minus-circle</v-icon>
										</div>
									</v-btn>
								</div>
							</v-col>
						</v-row>
					</v-form>
				</v-card-text>

				<v-divider></v-divider>

				<v-card-actions class="d-none d-sm-flex">
					<v-btn
						color="primary"
						@click="onSaveForm(false)"
						class="text-capitalize btn-primary btn-blue"
						v-if="isEditMode"
						:disabled="!formValid || isSaving"
						:loading="isSaving"
						>{{ 'Update' }}</v-btn
					>
					<v-btn
						color="primary"
						@click="onSaveForm(false)"
						class="text-capitalize btn-primary btn-blue"
						v-if="!isEditMode"
						:disabled="!formValid || isSaving"
						:loading="isSaving"
						>{{ 'Save' }}</v-btn
					>
					<v-btn
						outlined
						text
						@click="onSaveForm(true)"
						class="text-capitalize primary--text btn-white"
						v-if="!isEditMode"
						:disabled="!formValid || isSaving"
						>{{ 'Save & Add Another' }}</v-btn
					>
					<v-btn outlined text class="text-capitalize primary--text btn-white" @click="onClose" :disabled="isSaving">{{
						'Cancel'
					}}</v-btn>
					<v-spacer></v-spacer>
				</v-card-actions>
				<v-card-actions class="d-flex d-sm-none">
					<v-btn
						color="btn-blue"
						@click="onSaveForm(false)"
						class="text-capitalize btn-primary btn-blue"
						v-if="isEditMode"
						:disabled="!formValid || isSaving"
						:loading="isSaving"
						>{{ 'Update' }}</v-btn
					>
					<v-menu offset-x v-else>
						<template v-slot:activator="{ on, attrs }">
							<v-btn
								color="primary"
								dark
								v-bind="attrs"
								v-on="on"
								:loading="isSaving"
								class="d-flex align-center"
							>
								{{ 'Actions' }}
								<v-divider vertical class="mx-2" />
								<v-icon>mdi-chevron-right</v-icon>
							</v-btn>
						</template>
						<v-list dense class="text-uppercase">
							<v-list-item @click="onSaveForm(false)">
								<v-list-item-content>
									<v-list-item-title>{{ 'Save' }}</v-list-item-title>
								</v-list-item-content>
							</v-list-item>
							<v-divider />
							<v-list-item @click="onSaveForm(true)">
								<v-list-item-content>
									<v-list-item-title>{{ 'Save & Add Another' }}</v-list-item-title>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</v-menu>
					<v-spacer></v-spacer>
					<v-btn outlined text class="text-capitalize primary--text" @click="onClose" :disabled="isSaving">{{
						'Cancel'
					}}</v-btn>
				</v-card-actions>
			</v-card>
			<!-- <v-snackbar timeout="5000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar> -->
		</v-dialog>
	</div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import { apiErrorMessage } from '../../utils/globalMethods';
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'ChartOfAccountsForm',
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			formData: {
				name: '',
				code: null,
				type_id: null,
				is_sub_account: 0,
				account_id: null,
				description: '',
				enabled: 1
			},
			defaultFields: {},
			formValid: true,
			categoryData: [],
			showSnackbar: false,
			snackbarOption: {
				message: '',
				color: 'primary'
			},
			isSaving: false,
			accountTypes: {},
			parentAccounts: [],
			isParentAccountsLoading: false,
			isFetchingAccountTypes: false
		};
	},

	created() {
		// this.getCurrencies();
		this.defaultFields = { ...this.formData };
		this.fetchAccountTypes();
	},

	computed: {
		...mapGetters('accounting', ['isQBOEnabled']),

		showDialog: {
			get() {
				return this.open;
			},
			set(value) {
				this.$emit('toggle', value);
			}
		},

		accountTypeList() {
			const keys = Object.keys(this.accountTypes?.types || {});
			if (keys.length) {
				const data = [];
				keys.forEach((key) => {
					data.push({ header: key });
					Object.keys(this.accountTypes.types[key]).forEach((k) => {
						data.push({
							text: this.accountTypes.types[key][k],
							value: Number(k)
						});
					});
				});
				return data;
			}
			return [];
		},

		parentAccountList() {
			const type_id = this.formData.type_id || '';
			if (type_id) {
				const keys = Object.keys(this.accountTypes?.accounts[type_id] || {});
				const data = [];
				keys.forEach((key) => {
					Object.keys(this.accountTypes.accounts[type_id][key]).forEach((k) => {
						data.push({ text: this.accountTypes.accounts[type_id][key][k], value: Number(k) });
					});
				});
				return data;
			}
			return [];
		}
	},

	watch: {
		showDialog(open) {
			if (open) {
				this.fetchAccountTypes();
			}
		},

		formValues(values) {
			if (values && this.isEditMode) {
				const { id, account_id, type_id, trans_name, code, description, enabled } = values;
				this.formData = {
					id,
					code,
					account_id,
					type_id,
					name: trans_name,
					/*eslint no-extra-boolean-cast: "off"*/
					is_sub_account: !!account_id ? 1 : 0,
					description,
					enabled: enabled ? 1 : 0
				};
			}
		}
	},

	methods: {
		...mapActions('accounting', ['getCoaAccountsAndTypes', 'createCoaAccount', 'updateCoaAccount']),
		...globalMethods,
		onChangeAccountType() {
			this.formData = {
				...this.formData,
				account_id: null
			};
		},

		async fetchAccountTypes() {
			if (this.isFetchingAccountTypes) {
				return;
			}
			this.isFetchingAccountTypes = true;
			try {
				const data = await this.getCoaAccountsAndTypes({ qbo_enabled: this.isQBOEnabled });
				this.accountTypes = data;
				this.isFetchingAccountTypes = false;
			} catch (error) {
				apiErrorMessage(error);
				this.isFetchingAccountTypes = false;
			}
		},

		onClose() {
			this.$refs.chartForm.resetValidation();
			this.$emit('toggle');
			this.formData = { ...this.defaultFields };
		},

		onToggleIsSubAccount() {
			const is_sub_account = this.formData.is_sub_account === 1 ? 0 : 1;
			const formData = { ...this.formData };
			formData.is_sub_account = is_sub_account;
			if (is_sub_account === 0) {
				this.$refs.chartForm.resetValidation();
				formData.account_id = null;
			}
			this.formData = {
				...formData
			};
		},

		async onSaveForm(isAddAnother = false) {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.chartForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const form = { ...this.formData, qbo_enabled: this.isQBOEnabled };

					if (this.formData.currency === null) {
						form.currency = JSON.stringify({ name: 'US Dollar', code: 'USD' });
					}

					const { message } = this.isEditMode
						? await this.updateCoaAccount(form)
						: await this.createCoaAccount(form);
					const _message = message || 'Data was successfully saved.';

					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message: _message
					// };

					this.$refs.chartForm.reset();
					if (!isAddAnother) {
						// this.$emit("toggle", { created: true, message });
						this.onClose();
						this.$emit('saved', { message: _message });
					}
				} catch (error) {
					console.log(error);

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
	$form-label: #819fb2;
	$border-color: #21606bab;
	$btn-active-color: #0171a1;

	.form-label,
	.v-label {
		color: $form-label;
	}
	.w-100 {
		width: 100%;
	}
	.display-none {
		display: none;
	}

	fieldset {
		border: 1px solid $border-color;
		border-radius: 5px 5px 5px 5px;
	}

	fieldset legend {
		border: 1px solid;
		border-color: $border-color;
		background-color: #f5f5f5;
	}
	.btn-primary {
		background-color: $btn-active-color !important;
		color: #ffffff !important;
	}
	.z-index-front {
		z-index: 1;
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
