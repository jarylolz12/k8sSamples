<template>
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
			<v-card-title class="pa-0 z-index-front">
				<span class="headline">{{ isEditMode ? 'Edit Item' : 'Add Item' }}</span>
				<v-spacer></v-spacer>
				<v-btn icon @click="onClose">
					<v-icon>mdi-close</v-icon>
				</v-btn>
			</v-card-title>

			<v-card-text class="px-0 pb-0">
				<v-form ref="itemForm" lazy-validation v-model="formValid">
					<v-row no-gutters>
						<v-col md="4" cols="12" class="item-form-column pa-4 grey lighten-4">
							<v-card class="transparent" flat>
								<label class="form-label text-uppercase" for="formdata-item-name">{{ 'Name' }}</label>
								<v-text-field
									v-model="formData.name"
									:label="'Type item name'"
									:rules="[(v) => !!v || 'Field Required']"
									solo
									outlined
									flat
									required
									dense
									id="formdata-item-name"
									hide-details="auto"
									autocomplete="off"
								></v-text-field>

								<label class="form-label text-uppercase" for="formdata-type">{{
									'Type'
								}}</label>
								<v-select
									v-model="formData.type"
									:items="typeData"
									id="formdata-type"
									:label="'Select type'"
									solo
									flat
									outlined
									dense
									hide-details="auto"
									item-text="name"
									item-value="value"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								>
								</v-select>

								<label class="form-label text-uppercase" for="formdata-item-sku">{{ 'SKU' }}</label>
								<v-text-field
									v-model="formData.sku"
									:label="'Enter SKU'"
									solo
									outlined
									flat
									required
									dense
									id="formdata-item-sku"
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
									:label="'Type description of the item' + '...'"
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
												<label class="form-label text-uppercase" for="formdata-qbo-item-type">{{
													'QBO Item Type'
												}}</label>
												<v-icon
													@click="onChangeQBOItem"
													v-if="
														QBOIncomeAccountData.length === 0 ||
															QBOExpenseAccountData.length === 0
													"
													>mdi-refresh</v-icon
												>
											</div>
											<v-select
												v-model="formData.qbo_item_type"
												:items="QBOItemTypeData"
												:rules="[(v) => !!v || 'Field Required']"
												id="formdata-qbo-item-type"
												label=""
												solo
												flat
												outlined
												dense												
												hide-details="auto"
												@change="onChangeQBOItem"
												:loading="isQBOAccountLoading"
												:disabled="isQBOAccountLoading"
												placeholder="Select QBO Item Type"
												:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
											>
											</v-select>
										</v-col>
										<v-col md="6" cols="12" class="pb-0">
											<label
												class="form-label text-uppercase"
												for="formdata-qbo-income-account"
												>{{ 'QBO Income Account' }}</label
											>
											<v-autocomplete
												v-model="formData.qbo_income_account"
												:items="QBOIncomeAccountData"
												id="formdata-qbo-income-account"
												label=""
												solo
												flat
												outlined
												dense
												hide-details
												:loading="isQBOAccountLoading"
												:disabled="isQBOAccountLoading"
												:rules="[(v) => !!v || 'Field Required']"
												placeholder="Select QBO Income Account"
												:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
											>
											</v-autocomplete>
										</v-col>
										<v-col md="6" cols="12" class="pb-0">
											<label class="form-label text-uppercase" for="formdata-expense-account">{{
												'QBO Expense Account'
											}}</label>
											<v-autocomplete
												v-model="formData.qbo_expense_account"
												:items="QBOExpenseAccountData"
												id="formdata-qbo-expense-account"
												label=""
												solo
												flat
												outlined
												dense
												hide-details
												:loading="isQBOAccountLoading"
												:disabled="isQBOAccountLoading"
												:rules="[(v) => !!v || 'Field Required']"
												placeholder="Select QBO Expense Account"
												:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
											>
											</v-autocomplete>
										</v-col>
										<v-col md="6" cols="12" class="pb-0" v-if="formData.qbo_item_type === 'Inventory'">
											<label class="form-label text-uppercase" for="formdata-inv-asset-account">{{
												'QBO Inventory Asset Account'
											}}</label>
											<v-select
												v-model="formData.qbo_inventory_asset_account"
												:items="QBOInventoryAssetAccountData"
												id="formdata-inv-asset-account"
												label=""
												solo
												flat
												outlined
												dense
												hide-details
												:loading="isQBOAccountLoading"
												:disabled="isQBOAccountLoading"
												:rules="[(v) => !!v || 'Field Required']"
												placeholder="Select QBO Inventory Asset Account"
												:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
											>
											</v-select>
										</v-col>
										<v-col
											md="6"
											cols="12"
											v-if="formData.qbo_item_type === 'Inventory' && !isEditMode"
											class="pb-0"
										>
											<label class="form-label text-uppercase" for="formdata-asof-date">{{
												'As of Date'
											}}</label>
											<v-menu
												ref="asOfDateMenu"
												v-model="asOfDateMenu"
												close-on-content-click
												transition="scale-transition"
												max-width="290px"
												min-width="auto"
											>
												<template v-slot:activator="{ on, attrs }">
													<v-text-field
														v-model="formData.inv_start_date"
														label="As of Date"
														persistent-hint
														append-icon="mdi-calendar"
														v-bind="attrs"
														v-on="on"
														id="formdata-asof-date"
														solo
														dense
														flat
														outlined
														:rules="[(v) => !!v || 'Field Required']"
													></v-text-field>
												</template>
												<v-date-picker
													v-model="formData.inv_start_date"
													no-title
													@input="asOfDateMenu = false"
												></v-date-picker>
											</v-menu>
										</v-col>
										<v-col md="6" cols="12" class="pb-0" v-if="formData.qbo_item_type === 'Inventory'">
											<label class="form-label text-uppercase" for="formdata-quantity">{{
												'quantity'
											}}</label>
											<v-text-field
												v-model.number="formData.quantity_on_hand"
												solo
												outlined
												flat
												dense
												:label="'Enter Quantity'"
												id="formdata-quantity"
												type="number"
												:rules="[(v) => !!v || 'Field Required']"
											></v-text-field>
										</v-col>
									</v-row>
								</fieldset>
								<fieldset class="item-legend-rounded-wrapper px-5 rounded pb-5">
									<legend class="item-legend-rounded rounded pa-2 mb-4">
										{{ 'Sales Information' }}
									</legend>

									<v-row>
										<v-col md="6" cols="12">
											<label class="form-label text-uppercase" for="formdata-sales-price">{{
												'Sales Price'
											}}</label>
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
													@click="getAkauntingAccounts"
													v-if="incomeAccountData.length === 0"
													>mdi-refresh</v-icon
												>
											</div>
											<v-select
												v-model="formData.de_income_account_value"
												:items="incomeAccountData"
												:rules="
													!formData.de_expense_account_value
														? [(v) => !!v || 'Field Required']
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
											</v-select>
										</v-col>
									</v-row>
								</fieldset>

								<fieldset class="item-legend-rounded-wrapper px-5 rounded mt-4 pb-4">
									<legend class="item-legend-rounded rounded pa-2 mb-4">
										{{ 'Purchase Information' }}
									</legend>
									<v-row>
										<v-col md="6" cols="12">
											<label class="form-label text-uppercase" for="formdata-purchase-price">{{
												'Purchase Price'
											}}</label>
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
													@click="getAkauntingAccounts"
													v-if="expenseAccountData.length === 0"
													>mdi-refresh</v-icon
												>
											</div>
											<v-autocomplete
												v-model="formData.de_expense_account_value"
												:items="expenseAccountData"
												:rules="
													!formData.de_income_account_value
														? [(v) => !!v || 'Field Required']
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

			<!-- <v-divider></v-divider> -->

			<v-card-actions class="d-none d-sm-flex">
				<v-btn
					color="primary"
					@click="onUpdateForm"
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
					@click="onUpdateForm"
					class="text-capitalize btn-primary btn-blue"
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
							color="btn-blue"
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
								<v-list-item-title>{{ 'save' }}</v-list-item-title>
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
				<v-btn outlined text class="text-capitalize primary--text btn-white" @click="onClose" :disabled="isSaving">{{
					'Cancel'
				}}</v-btn>
			</v-card-actions>
		</v-card>

		<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar> -->
	</v-dialog>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import AkauntingService from '../../services/akaunting.service';
import globalMethods from '../../utils/globalMethods'

export default {
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			formData: {
				sku: '',
				name: '',
				sale_price: 0,
				purchase_price: 0,
				description: '',
				category: null,
				de_income_account_value: null,
				de_expense_account_value: null,
				qbo_enabled: 0,
				qbo_item_type: '',
				quantity_on_hand: 0,
				inv_state_date: null,
				qbo_inventory_asset_account: null,
				qbo_expense_account: null,
				qbo_income_account: null
			},
			formValid: true,
			categoryData: [],
			taxData: [],
			itemTypeData: [],
			supplierData: [],
			showSnackbar: false,
			snackbarOption: {
				message: '',
				color: 'primary'
			},
			isSaving: false,
			asOfDateMenu: null,
			defaultFields: null,
			typeData: [
				{ name: 'Product', value:'product' },
				{ name: 'Service', value:'service' },
			]
		};
	},

	created() {
		this.fetchItemCategory();
		this.defaultFields = JSON.parse(JSON.stringify(this.formData));
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
			'QBOInventoryIncomeAccountLists'
		]),
		...mapGetters('accounting', ['isQBOEnabled']),

		incomeAccountData: {
			get() {
				return Object.keys(this.incomeAccountLists).map((key) => ({
					text: this.incomeAccountLists[key],
					value: JSON.stringify({ [key]: this.incomeAccountLists[key] })
				}));
			}
		},
		expenseAccountData: {
			get() {
				return Object.keys(this.expenseAccountLists).map((key) => ({
					text: this.expenseAccountLists[key],
					value: JSON.stringify({ [key]: this.expenseAccountLists[key] })
				}));
			}
		},

		QBOIncomeAccountData: {
			get() {
				if (this.formData.qbo_item_type === '') {
					return [];
				}

				return this.formData.qbo_item_type === 'Inventory'
					? this.QBOInventoryIncomeAccountLists.map((data) => ({
							text: data['Name'],
							value: JSON.stringify({ Id: data['Id'], Name: data['Name'] })
						}))
					: this.QBOIncomeAccountLists.map((data) => ({
							text: data['Name'],
							value: JSON.stringify({ Id: data['Id'], Name: data['Name'] })
						}));
			}
		},
		QBOExpenseAccountData: {
			get() {
				if (this.formData.qbo_item_type === '') {
					return [];
				}

				return this.formData.qbo_item_type === 'Inventory'
					? this.QBOInventoryExpenseAccountLists.map((data) => ({
							text: data['Name'],
							value: JSON.stringify({ Id: data['Id'], Name: data['Name'] })
						}))
					: this.QBOExpenseAccountLists.map((data) => ({
							text: data['Name'],
							value: JSON.stringify({ Id: data['Id'], Name: data['Name'] })
						}));
			}
		},
		QBOItemTypeData: {
			get() {
				return ['Service', 'Inventory', 'NonInventory'];
			}
		},
		QBOInventoryAssetAccountData: {
			get() {
				return this.QBOInventoryAssetAccountLists.map((data) => ({
					text: data['Name'],
					value: JSON.stringify({ Id: data['Id'], Name: data['Name'] })
				}));
			}
		},
		showDialog: {
			get() {
				return this.open;
			},
			set(value) {
				this.$emit('toggle', value);
			}
		},

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		}
	},

	watch: {
		formValues(values) {
			if (values && this.isEditMode) {
				const {
					id,
					type,
					qbo_expense_account,
					qbo_income_account,
					sale_price,
					qbo_item_type,
					description,
					name,
					purchase_price,
					qbo_item_id,
					de_expense_account_value,
					de_income_account_value,
					quantity_on_hand,
					qbo_asset_account,
					sku,
					category,
					inv_start_date
				} = values;
				this.formData = {
					id,
					type,
					de_expense_account_value: de_expense_account_value,
					de_income_account_value: de_income_account_value,
					qbo_item_id,
					description,
					name,
					purchase_price,
					qbo_expense_account,
					qbo_income_account,
					qbo_item_type,
					quantity_on_hand,
					sale_price,
					qbo_inventory_asset_account: qbo_asset_account,
					sku,
					category: category
						? JSON.stringify({
								id: category.id,
								name: category.name,
								type: category.type
							})
						: null,
					inv_start_date
				};
				this.onChangeQBOItem();
			} else {
				this.formData = {
					...this.defaultFields
				};
			}
		}
	},

	methods: {
		...mapActions('accounting', [
			'createItemForm',
			'getItemsData',
			'getQBOAccounts',
			'updateItemForm',
			'getAkauntingAccounts'
		]),
		...globalMethods,
		fetchItemCategory() {
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

		onChangeQBOItem() {
			// Fetch only once for these values.
			if (['Service', 'NonInventory'].includes(this.formData.qbo_item_type)) {
				if (!this.isEditMode) {
					this.formData = {
						...this.formData,
						quantity_on_hand: 0,
						inv_state_date: null,
						qbo_inventory_asset: null
					};
				}
				// if(this.QBOIncomeAccountLists.length === 0 || this.QBOExpenseAccountLists.length === 0) {
				this.getQBOAccounts(this.formData.qbo_item_type);
				// }
			} else {
				if (!this.isEditMode) {
					this.formData = {
						...this.formData,
						qbo_expense_account: null,
						qbo_income_account: null
					};
				}
				// if(!this.QBOInventoryExpenseAccountLists.length || !this.QBOInventoryIncomeAccountLists.length) {
				this.getQBOAccounts(this.formData.qbo_item_type);
				// }
			}
		},

		onClose() {
			this.$refs.itemForm.resetValidation();
			this.formData = {
				...this.defaultFields
			};
			this.$emit('toggle');
		},

		async onSaveForm(isAddAnother = false) {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.itemForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const { data } = await this.createItemForm({
						...this.formData,
						qbo_enabled: this.isQBOEnabled
					});
					const message = data.message || 'Data was successfully saved.';
					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };

					this.$refs.itemForm.reset();
					if (!isAddAnother) {
						this.$emit('toggle', { created: true, message });
					} else {
						// Reload the table
						this.getItemsData();
					}
				} catch (error) {
					const { data } = error.response || { data: {} };

					// this.snackbarOption = {
					// 	icon: 'mdi-alert-circle',
					// 	color: 'error',
					// 	message: data.message || 'Could not save the data.'
					// };

					const message = data.message || 'Could not save the data.';
					this.notificationError(message)
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

			const validated = this.$refs.itemForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const { data } = await this.updateItemForm({
						...this.formData,
						qbo_enabled: this.isQBOEnabled
					});
					const message = data.message || 'Data was successfully updated.';
					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };

					this.$refs.itemForm.reset();
					this.$emit('toggle', { message, updated: true });
				} catch (error) {
					const { data } = error.response || { data: {} };

					// this.snackbarOption = {
					// 	icon: 'mdi-alert-circle',
					// 	color: 'error',
					// 	message: data.message || 'Could not save the data.'
					// };
					const message = data.message || 'Could not save the data.';
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
