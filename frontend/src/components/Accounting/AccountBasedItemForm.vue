<template>
	<div class="">
		<v-dialog
			v-model="showDialog"
			max-width="1200"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card :loading="isSaving">
				<v-card-title class="">
					<span class="headline">{{ isEditMode ? 'Edit Account Based Item' : 'Add Account Based Item' }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>

				<v-card-text class="px-0 pb-0">
					<v-form ref="itemForm" lazy-validation v-model="formValid">
						<v-row no-gutters>
							<v-col md="4" cols="12" class="item-form-column grey lighten-4 pa-4">
								<v-card class="" flat color="transparent">
									<label class="form-label text-uppercase" for="formdata-item-name">{{
										'Name'
									}}</label>
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

									<label class="form-label text-uppercase" for="formdata-category">{{
										'Category'
									}}</label>
									<v-select
										v-model="formData.category"
										:items="categoryData"
										id="formdata-category"
										:label="'Select category'"
										solo
										flat
										outlined
										dense
										hide-details="auto"
										:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
									>
									</v-select>

									<label class="form-label text-uppercase" for="formdata-item-description">{{
										'Item Description'
									}}</label>
									<v-textarea
										solo
										:label="'Type description of the item...'"
										outlined
										flat
										id="formdata-item-description"
										rows="2"
										v-model="formData.description"
										hide-details="auto"
									></v-textarea>
								</v-card>
							</v-col>
							<v-col class="item-form-column white pa-4">
								<v-card class="pa-2" flat>
									<fieldset class="item-legend-rounded-wrapper px-5 rounded mb-4 pb-4" v-if="isQBOEnabled === 1">
										<legend class="item-legend-rounded rounded pa-2 mb-4">
											{{ 'Quickbooks Information' }}
										</legend>
										<v-row>
											<v-col md="6" cols="12" class="pb-0">
												<div class="d-flex align-center justify-space-between">
													<label
														class="form-label text-uppercase"
														for="form-qbo-account-type"
														>{{ 'Account Type' }}</label
													>
													<v-icon
														@click="fetchQBOAccountTypes"
														v-if="QBOAccountTypeList.length === 0"
														>mdi-refresh</v-icon
													>
												</div>
												<v-autocomplete
													v-model="formData.qbo_account_type"
													:items="QBOAccountTypeList"
													id="form-qbo-account-type"
													flat
													outlined
													dense
													:rules="[(v) => !!v || 'Field required']"
													hide-details="auto"
													@change="onChangeQBOAccountType"
													placeholder="Select Account Type"
													:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
												>
												</v-autocomplete>
											</v-col>
											<v-col cols="12" md="6" class="pb-0">
												<label
													class="form-label text-uppercase"
													for="form-qbo-account-subtype"
													>{{ 'Detail Type' }}</label
												>
												<v-autocomplete
													v-model="formData.qbo_account_sub_type"
													:items="QBOAccountSubTypeList"
													id="form-qbo-account-subtype"
													flat
													outlined
													dense
													hide-details="auto"
													placeholder="Select Detail Type"
													:rules="[(v) => !!v || 'Field required']"
													:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
												>
												</v-autocomplete>
											</v-col>
											<v-col cols="12" md="6">
												<label class="form-label text-uppercase">{{
													'Is Sub-account'
												}}</label>
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
											</v-col>
											<v-col cols="12" md="6">
												<div class="d-flex align-center justify-space-between">
													<label
														class="form-label text-uppercase"
														for="form-parent-account"
														:class="{ 'grey--text': formData.is_sub_account === 0 }"
														>{{ 'Parent Account' }}</label
													>
													<v-icon
														@click="fetchQBOParentAccounts"
														v-if="QBOParentAccountList.length === 0"
														:disabled="formData.is_sub_account === 0"
														>mdi-refresh</v-icon
													>
												</div>
												<v-autocomplete
													v-model="formData.parent_account"
													:items="QBOParentAccountList"
													id="form-qbo-account-subtype"
													flat
													outlined
													dense
													hide-details="auto"
													placeholder="Select Parent Account"
													:loading="isParentAccountsLoading"
													:disabled="formData.is_sub_account === 0"
													:rules="[
														(v) =>
															!!v || formData.is_sub_account === 0 || 'Field required'
													]"
													:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
												>
												</v-autocomplete>
											</v-col>
											<v-col cols="12" md="6" class="pt-0" v-if="showCurrencyField">
												<label for="formdata-currency" class="form-label text-uppercase">{{
													'Currency'
												}}</label>
												<v-autocomplete
													v-model="formData.currency"
													:items="currencyData"
													:rules="[(v) => !!v || 'Field required']"
													id="formdata-currency"
													solo
													flat
													class="app-theme-input-border mb-3"
													dense
													outlined
													hide-details
													:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
												>
													<template v-slot:prepend-inner>
														<span class="primary--text">{{ currencySymbol }}</span>
													</template>
												</v-autocomplete>
											</v-col>
										</v-row>
									</fieldset>

									<fieldset class="item-legend-rounded-wrapper px-5 rounded pb-5">
										<legend class="item-legend-rounded rounded pa-2 mb-4">
											{{ 'Sales Information' }}
										</legend>

										<v-row>
											<v-col md="6" cols="12">
												<label
													class="form-label text-uppercase"
													for="formdata-sales-price"
													>{{ 'Sales Price' }}</label
												>
												<v-text-field
													v-model.number="formData.sale_price"
													:label="'Enter Sales Price'"
													solo
													outlined
													flat
													required
													dense
													id="formdata-sales-price"
													type="number"
													hide-details
												></v-text-field>
											</v-col>
											<v-col md="6" cols="12">
												<div class="d-flex align-center justify-space-between">
													<label
														class="form-label text-uppercase"
														for="formdata-income-account"
														>{{ 'Income Account' }}</label
													>
													<v-icon
														@click="getDEaccounts"
														v-if="incomeAccountData.length === 0"
														>mdi-refresh</v-icon
													>
												</div>
												<v-autocomplete
													v-model="formData.de_income_account_value"
													:items="incomeAccountData"
													:rules="
														!formData.de_expense_account_value
															? [(v) => !!v || 'Field required']
															: []
													"
													id="formdata-income-account"
													:label="'Select Income Account'"
													solo
													flat
													outlined
													dense
													hide-details
													clearable
													:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
												>
												</v-autocomplete>
											</v-col>
										</v-row>
									</fieldset>

									<fieldset class="item-legend-rounded-wrapper px-5 rounded mt-4 pb-4">
										<legend class="item-legend-rounded rounded pa-2 mb-4">
											{{ 'Purchase Information' }}
										</legend>
										<v-row>
											<v-col md="6" cols="12">
												<label
													class="form-label text-uppercase"
													for="formdata-purchase-price"
													>{{ 'Purchase Price' }}</label
												>
												<v-text-field
													v-model.number="formData.purchase_price"
													:label="'Enter Cost Price'"
													solo
													outlined
													flat
													required
													dense
													id="formdata-purchase-price"
													type="number"
													hide-details
												></v-text-field>
											</v-col>
											<v-col md="6" cols="12">
												<div class="d-flex align-center justify-space-between">
													<label
														class="form-label text-uppercase"
														for="formdata-expense-account"
														>{{ 'Expense Account' }}</label
													>
													<v-icon
														@click="getDEaccounts"
														v-if="expenseAccountData.length === 0"
														>mdi-refresh</v-icon
													>
												</div>
												<v-autocomplete
													v-model="formData.de_expense_account_value"
													:items="expenseAccountData"
													:rules="
														!formData.de_income_account_value
															? [(v) => !!v || 'Field required']
															: []
													"
													id="formdata-expense-account"
													:label="'Select Expense Account'"
													solo
													flat
													outlined
													dense
													hide-details
													clearable
													:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
												>
												</v-autocomplete>
											</v-col>
										</v-row>
									</fieldset>
								</v-card>
							</v-col>
						</v-row>
					</v-form>
				</v-card-text>

				<v-divider></v-divider>

				<v-card-actions class="d-none d-sm-flex">
					<v-btn
						color="primary"
						@click="onSaveForm(false)"
						class="text-capitalize btn-blue"
						v-if="isEditMode"
						:disabled="!formValid || isSaving"
						:loading="isSaving"
						>{{ 'Update' }}</v-btn
					>
					<v-btn
						color="primary"
						@click="onSaveForm(false)"
						class="text-capitalize btn-blue"
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
						color="primary"
						@click="onSaveForm(false)"
						class="text-capitalize btn-blue"
						v-if="isEditMode"
						:disabled="!formValid || isSaving"
						:loading="isSaving"
						>{{ 'Update' }}</v-btn
					>
					<!-- <v-btn x-small color="primary" @click="onSaveForm(false)" class="text-capitalize btn-primary" v-if="!isEditMode" :disabled="!formValid || isSaving" :loading="isSaving">{{ ('save_send') }}</v-btn>
			<v-btn x-small outlined text @click="onSaveForm(true)" class="text-capitalize" v-if="!isEditMode" :disabled="!formValid || isSaving">{{ ('save_add_another') }}</v-btn> -->
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
					<v-btn outlined text class="btn-white text-capitalize primary--text" @click="onClose" :disabled="isSaving">{{
						'Cancel'
					}}</v-btn>
				</v-card-actions>
			</v-card>
			<v-snackbar timeout="5000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar>
		</v-dialog>
	</div>
</template>

<script>
	import { mapState, mapActions, mapGetters } from 'vuex';

	import AkauntingService from '../../services/akaunting.service';
	import { apiErrorMessage } from '../../utils/globalMethods';

	export default {
		name: 'AccountBasedItemForm',
		props: ['open', 'isEditMode', 'formValues'],
		data() {
			return {
				formData: {
					name: '',
					sale_price: 0,
					purchase_price: 0,
					description: '',
					category: null,
					de_income_account_value: null,
					de_expense_account_value: null,
					qbo_enabled: 0,
					qbo_item_type: 'Account',
					qbo_account_type: null,
					qbo_account_sub_type: null,
					is_sub_account: 0,
					parent_account: null,
					currency: JSON.stringify({ name: 'US Dollar', code: 'USD' })
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
				QBOAccountTypes: {},
				parentAccounts: [],
				isParentAccountsLoading: false,
				isFetchingQBOAccountTypes: false,
				incomeExpenseAccountLists: []
			};
		},

		created() {
			this.fetchItemCateogory();
			this.getCurrencies();
			this.defaultFields = { ...this.formData };
			this.fetchQBOAccountTypes();
		},

		computed: {
			...mapState('accounting', [
				'incomeAccountLists',
				'expenseAccountLists',
				'QBOExpenseAccountLists',
				'QBOIncomeAccountLists',
				'isQBOAccountLoading',
				'QBOInventoryAssetAccountLists',
				'QBOInventoryExpenseAccountLists',
				'QBOInventoryIncomeAccountLists',
				'currencies'
			]),
			...mapGetters('accounting', ['isQBOEnabled']),

			isMobile() {
				return this.$vuetify.breakpoint.mobile;
			},

			incomeAccountData() {
				/* get() {
				return Object.keys(this.incomeAccountLists).map(key => ({text: this.incomeAccountLists[key], value: JSON.stringify({[key]: this.incomeAccountLists[key]})}));
			} */
				return this.incomeExpenseAccountLists;
			},
			expenseAccountData() {
				/* get() {
				return Object.keys(this.expenseAccountLists).map(key => ({text: this.expenseAccountLists[key], value: JSON.stringify({[key]: this.expenseAccountLists[key]})}));
			} */
				return this.incomeExpenseAccountLists;
			},

			showDialog: {
				get() {
					return this.open;
				},
				set(value) {
					this.$emit('toggle', value);
				}
			},

			QBOAccountTypeList() {
				const keys = Object.keys(this.QBOAccountTypes);
				if (keys.length) {
					const data = [];
					keys.forEach((key) => {
						const subKeys = Object.keys(this.QBOAccountTypes[key]);
						subKeys.forEach((subKey) => {
							data.push(subKey);
						});
					});
					return data.sort();
				}
				return [];
			},

			QBOAccountSubTypeList() {
				const keys = Object.keys(this.QBOAccountTypes);
				if (keys.length && this.formData.qbo_account_type) {
					let data = [];
					keys.forEach((key) => {
						const subKeys = Object.keys(this.QBOAccountTypes[key]);
						subKeys.forEach((subKey) => {
							if (subKey === this.formData.qbo_account_type) {
								data = this.QBOAccountTypes[key][subKey].map((record) => {
									return {
										text: record.name,
										value: JSON.stringify({ name: record.name, value: record.value })
									};
								});
								return;
							}
						});
					});
					return data;
				}
				return [];
			},

			/* accountType() {
			const type = this.formData.qbo_account_type || '';
			if(type) {

			}
			return [];
		}, */

			QBOParentAccountList() {
				const type = this.formData.qbo_account_type || '';
				if (type) {
					return this.parentAccounts
						.filter((record) => record.AccountType === type)
						.map((account) => ({
							text: account.Name,
							value: JSON.stringify({ Id: account.Id, Name: account.Name })
						}));
				}
				return [];
			},

			currencyData() {
				return this.currencies.map((currency) => ({
					text: currency.name,
					value: JSON.stringify({ name: currency.name, code: currency.code })
				}));
			},

			currencySymbol() {
				if (this.formData.currency) {
					const selectedCurrency = JSON.parse(this.formData.currency)?.code;
					return this.currencies.find((currency) => currency.code === selectedCurrency)?.symbol;
				}
				return '';
			},

			showCurrencyField() {
				const accounTypes = ['Bank', 'Other Current Asset', 'Fixed Asset', 'Other Asset'];
				return (
					(this.formData.qbo_account_type && accounTypes.includes(this.formData.qbo_account_type)) || false
				);
			}
		},

		watch: {
			formValues(values) {
				if (values && this.isEditMode) {
					const {
						id,
						sale_price,
						qbo_item_type,
						description,
						name,
						purchase_price,
						qbo_item_id,
						de_expense_account_value,
						de_income_account_value,
						quantity_on_hand,
						category,
						qbo_reference
					} = values;
					const qbo_ref = JSON.parse(qbo_reference) || {};
					this.formData = {
						id,
						de_expense_account_value: de_expense_account_value,
						de_income_account_value: de_income_account_value,
						qbo_item_id,
						description,
						name,
						purchase_price,
						qbo_item_type,
						quantity_on_hand,
						sale_price,
						category: category
							? JSON.stringify({
									id: category.data.id,
									name: category.data.name,
									type: category.data.type
							  })
							: null,
						qbo_account_type: qbo_ref?.AccFieldRef?.qbo_account_type,
						qbo_account_sub_type: JSON.stringify(qbo_ref?.AccFieldRef?.qbo_account_sub_type || {}),
						currency: JSON.stringify(qbo_ref?.AccFieldRef?.currency_values || null),
						parent_account: JSON.stringify(qbo_ref?.AccFieldRef?.parent_account || null),
						is_sub_account: qbo_ref?.SubAccount ? 1 : 0
					};
				} else {
					this.formData = { ...this.defaultFields };
				}
			},

			open(isOpen) {
				if (isOpen) {
					this.getDEaccounts();
				}
			}
		},

		methods: {
			...mapActions('accounting', [
				'createCategoryBasedItemForm',
				'getCategoryBasedItemData',
				'getQBOAccounts',
				'updateCategoryBasedItemForm',
				'getCurrencies',
				'getQBOAccountTypes',
				'getQBOParentAccounts'
			]),

			async fetchQBOParentAccounts() {
				if (this.isParentAccountsLoading) {
					return;
				}
				this.isParentAccountsLoading = true;
				try {
					this.parentAccounts = await this.getQBOParentAccounts();
					this.isParentAccountsLoading = false;
				} catch (error) {
					apiErrorMessage(error);
					this.isParentAccountsLoading = false;
				}
			},

			onChangeQBOAccountType() {
				this.formData = {
					...this.formData,
					qbo_account_sub_type: null,
					parent_account: null
				};
			},

			async fetchQBOAccountTypes() {
				if (this.isFetchingQBOAccountTypes) {
					return;
				}
				this.isFetchingQBOAccountTypes = true;
				try {
					const data = await this.getQBOAccountTypes({ qbo_enabled: this.isQBOEnabled });
					this.QBOAccountTypes = data;
					this.isFetchingQBOAccountTypes = false;
				} catch (error) {
					apiErrorMessage(error);
					this.isFetchingQBOAccountTypes = false;
				}
			},

			fetchItemCateogory() {
				AkauntingService.getAkauntingItemCategory()
					.then((response) => {
						if (response.data && response.data.data) {
							this.categoryData = response.data.data.data.map((data) => ({
								text: data.name,
								value: JSON.stringify({
									id: data.id,
									name: data.name,
									type: data.type
								})
							}));
						}
					})
					.catch(() => {
						this.categoryData = [];
					});
			},

			onClose() {
				this.$refs.itemForm.resetValidation();
				this.formData = { ...this.defaultFields };
				this.$emit('toggle');
			},

			onToggleIsSubAccount() {
				const is_sub_account = this.formData.is_sub_account === 1 ? 0 : 1;
				const formData = { ...this.formData };
				formData.is_sub_account = is_sub_account;
				if (is_sub_account === 0) {
					this.$refs.itemForm.resetValidation();
					formData.parent_account = null;
				} else if (is_sub_account === 1 && this.parentAccounts.length === 0) {
					this.fetchQBOParentAccounts();
				}
				this.formData = {
					...formData
				};
			},

			async onSaveForm(isAddAnother = false) {
				if (this.isSaving) {
					return;
				}

				const validated = this.$refs.itemForm.validate();
				if (validated) {
					this.isSaving = true;
					try {
						const form = { ...this.formData, qbo_enabled: this.isQBOEnabled };

						if (this.formData.currency === null) {
							form.currency = JSON.stringify({ name: 'US Dollar', code: 'USD' });
						}

						const { data } = this.isEditMode
							? await this.updateCategoryBasedItemForm(form)
							: await this.createCategoryBasedItemForm(form);
						const message = data.message || 'Data was successfully saved.';
						this.snackbarOption = {
							icon: 'mdi-check',
							color: 'success',
							message
						};

						this.$refs.itemForm.reset();
						if (!isAddAnother) {
							this.$emit('toggle', { created: true, message });
						} else {
							// Reload the table
							this.getCategoryBasedItemData();
						}
					} catch (error) {
						const { data } = error.response || { data: {} };

						this.snackbarOption = {
							icon: 'mdi-alert-circle',
							color: 'error',
							message: data.message || 'Could not save the data.'
						};
					} finally {
						this.showSnackbar = true;
						this.isSaving = false;
					}
				}
			},

			async getDEaccounts() {
				try {
					const { data } = await AkauntingService.getDEaccounts();
					const accounts = data?.data?.data?.accounts || {};
					const _data = [];
					Object.keys(accounts).forEach((key) => {
						_data.push({ header: key });
						Object.keys(accounts[key]).forEach((k) => {
							_data.push({ text: accounts[key][k], value: JSON.stringify({ [k]: accounts[key][k] }) });
						});
					});
					this.incomeExpenseAccountLists = _data;
				} catch (error) {
					apiErrorMessage(error);
				}
			}
		}
	};
</script>

<style lang="scss">
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';
@import '../../views/Accounting/scss/globalAccounting.scss';
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
	::v-deep {
		.v-dialog {
			.v-input__control {
				background: transparent !important;
				margin-bottom: 12px !important;

				.v-input__slot {
					min-height: 45px !important;

					label {
						line-height: 18px;
						font-size: 14px;
						color: #B4CFE0;
						font-family: 'Inter-Regular', sans-serif;
					}

					input,
					textarea {
						font-size: 14px;
						font-family: 'Inter-Regular', sans-serif;
					}

					.v-select__slot {
						.v-select__selections {
							.v-select__selection {
								font-size: 14px;
							}
						}
					}

					.v-input__append-inner {
						button {
							font-size: 18px;
    						color: #ff5252!important;
						}
					}
				}

				.v-text-field__details {
					margin-bottom: 0;
					padding-top: 5px !important;
				}
			}

			.v-input.v-input--is-focused {
				fieldset {
					border: 1px solid #0171A1 !important;
				}
			}

			.v-text-field--enclosed .v-input__prepend-inner {
				margin-top: 0 !important;
			}

			.item-form-column {
				padding: 20px 24px !important;

				.form-label {
					font-size: 10px;
					color: #819fb2;
					font-family: "Inter-SemiBold",sans-serif;
					text-transform: uppercase;
					margin-bottom: 0;
				}
			}

			.item-legend-rounded-wrapper {
				border: 1px solid #D8E7F0;

				.item-legend-rounded {
					border: 1px solid #D8E7F0;
					padding: 6px 16px !important;
					text-transform: uppercase;
					color: #6D858F;
					font-size: 12px;
					font-family: 'Inter-Medium', sans-serif;
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
