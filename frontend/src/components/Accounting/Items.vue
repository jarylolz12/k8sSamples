<template>
	<v-sheet>
		<v-data-table
			v-model="selectedProducts"
			fixed-header
			class="v-table-middle-align"
			v-bind:class="{
                'no-data__table' : (tableData.length === 0),
                'no-current__pagination' : (pagination.last_page <= 1),
            }"
			show-select
			disable-pagination
			:single-select="false"
			:headers="productTableHeader"
			:items="tableData"
			:loading="isItemsDataLoading"
			:hide-default-footer="true">

			<template v-slot:top>
				<div class="d-md-flex justify-end w-100 pt-3 px-4 pb-3 border-bottom">
					<div class="d-md-flex justify-md-end my-2 my-md-0 mx-md-0">
						<div class="d-flex">
							<v-menu offset-y :close-on-content-click="false" v-model="showFilter">
								<template v-slot:activator="{ on, attrs }">
									<v-btn
										v-bind="attrs"
										v-on="on"
										outlined
										large
										height="40"
										class="text-capitalize btn-white"
										style="color: #4a4a4a;"
									>

										<img src="@/assets/icons/filters.svg" width="18px" height="18px" class="mr-2">
										<span style="color: #4a4a4a;">{{ 'Filters' }}</span>
										<!-- <v-icon>mdi-menu-down</v-icon> -->
									</v-btn>
								</template>
								<v-card width="300" class="filter-items-wrapper pt-4">
									<v-card-text>
										<label for="filter-enabled" class="primary--text form-label">Status</label>
										<v-select
											id="filter-enabled"
											v-model="isEnabled"
											height="45"
											solo
											:items="['Active', 'In-Active']"
											flat
											outlined
											dense
											color="primary"
											hide-details="auto"
										>
										</v-select>
									</v-card-text>
									<v-card-actions>
										<v-btn color="btn-blue" class="btn-blue" text link @click="fetchItemsData">
											{{ 'Ok' }}
										</v-btn>
									</v-card-actions>
								</v-card>
							</v-menu>
							<div class="search-field-wrapper ml-2">
								<Search :inputData.sync="searchText" placeholder="Search Items" />
							</div>
						</div>
						<div class="d-md-block d-none">
							<v-btn
								class="text-capitalize ml-md-2"
								color="btn-blue"
								@click="onToggleItemForm"
								height="38"
							>
								{{ 'Add Item' }}
							</v-btn>
						</div>
						<div class="d-sm-block d-md-none mt-2">
							<v-btn
								class="text-capitalize ml-md-2 d-block"
								width="100%"
								color="btn-blue"
								@click="onToggleItemForm"
								height="38"
							>
								{{ 'Add Item' }}
							</v-btn>
						</div>
					</div>
				</div>
			</template>

			<template v-slot:[`item.category`]="{ item }">
				{{ item.category.name }}
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<v-menu offset-y bottom left content-class="outbound-lists-menu">
					<template v-slot:activator="{ on, attrs }">
						<v-btn v-bind="attrs" primary v-on="on" class="btn-more-items btn-white" text outlined small>
							<v-icon>mdi-dots-horizontal</v-icon>
						</v-btn>
					</template>
					<v-list>
						<v-list-item link @click="onSelectEditItemData(item)">
							<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectItemInformation(item)">
							<v-list-item-title>{{ 'View' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onDuplicateItem(item)">
							<v-list-item-title>{{ 'Duplicate' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectItemToDisable(item)">
							<v-list-item-title class="text-danger">{{ 'Disable' }}</v-list-item-title>
						</v-list-item>
					</v-list>
				</v-menu>
			</template>

			<template v-slot:no-data>
				<div class="mt-5" v-if="isItemsDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>

				<v-alert class="mt-2" v-if="!isItemsDataLoading && selectedProducts.length === 0 && searchText === ''">
					<img src="@/assets/icons/empty-product-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">Add Items</p>
					<span class="subtitle-p-data">You have not added any items yet.</span>
					<br />
					<v-btn
						outlined
						text
						small
						@click="onToggleItemForm"
						color="#0889a0"
						class="py-4 text-capitalize btn-white"
					>
						<v-icon size="18">mdi-plus</v-icon>
						{{ 'Add Item' }}
					</v-btn>
				</v-alert>

				<v-alert class="mt-2" v-if="!isItemsDataLoading && selectedProducts.length === 0 && searchText !== ''">
					<img src="@/assets/icons/empty-product-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">No Items</p>
					<span class="subtitle-p-data">Sorry! We could not find any items that matches your search term.</span>
				</v-alert>
			</template>

			<template v-slot:loading>
				<div class="mt-5" v-if="isItemsDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>
			</template>
		</v-data-table>

		<!-- <v-divider /> -->
		<pagination
			:total="pagination.last_page || 1"
			:count="pagination.total || 0"
			:current-page="currentPage"
			:total-visible="10"
			:pageLimit.sync="pageLimit"
			@pageSelected="onPaginationClick"
			@changeLimit="onChangePageLimit"
			:from="pagination ? pagination.from : 1"
			:to="pagination ? pagination.to : 1"
		/>

		<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar> -->

		<item-form
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedItemData"
			@toggle="onToggleItemForm">
		</item-form>

		<item-disable
			:open="showDisableModal"
			:form-data="selectedDataToDisable"
			@toggle="onToggleDisableItemForm">
		</item-disable>

		<v-dialog v-model="showInfoModal" max-width="980" origin="top center" content-class="accounting-module-dialog pa-0" persistent scrollable>
			<v-card>
				<v-card-title class="pa-0">
					<span class="headline">{{ selectedItemInformation.name }}</span>
					<v-spacer></v-spacer>
					<v-btn
						class="d-none d-sm-flex btn-blue mr-1"
						color="btn-blue"
						@click="onSelectEditItemData(selectedItemInformation)">
						{{ 'Edit' }}
					</v-btn>
					<v-btn
						text
						outlined
						class="ml-1 mr-4 d-none d-sm-flex btn-white"
						@click="onSelectItemToDisable(selectedItemInformation)">
						{{ 'Disable' }}
					</v-btn>
					<v-btn icon class="d-none d-sm-flex" @click="onToggleItemInformationModal">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>

				<v-card-text class="py-6 pb-8">
					<v-row>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Item Description' }}</h6>
							<span class="subtitle-data">{{ selectedItemInformation.description }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'SKU' }}</h6>
							<span class="subtitle-data">{{ selectedItemInformation.sku }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Category' }}</h6>
							<span class="subtitle-data">{{
								selectedItemInformation.category_id ? selectedItemInformation.category.name : ''
							}}</span>
						</v-col>
					</v-row>

					<v-row>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Sales Price' }}</h6>
							<span class="subtitle-data">{{ selectedItemInformation.sale_price_formatted }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Income Account' }}</h6>
							<span class="subtitle-data">{{ de_income_account }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Purchase Price' }}</h6>
							<span class="subtitle-data">{{ selectedItemInformation.purchase_price_formatted }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Expense Account' }}</h6>
							<span class="subtitle-data">{{ de_expense_account }}</span>
						</v-col>
					</v-row>

					<div v-if="isQBOEnabled === 1" class="mt-4">
						<v-divider></v-divider>
						<h4 class="my-3">{{ 'Quickbooks Information' }}</h4>
						<v-divider></v-divider>

						<v-row class="mt-2">
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'QBO Item Type' }}</h6>
								<span class="subtitle-data">{{ selectedItemInformation.qbo_item_type }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'QBO Income Account' }}</h6>
								<span class="subtitle-data">{{ qbo_income_account }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'QBO Expense Account' }}</h6>
								<span class="subtitle-data">{{ qbo_expense_account }}</span>
							</v-col>
						</v-row>

						<v-row v-if="selectedItemInformation.qbo_item_type === 'Inventory'">
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'QBO Inventory Asset Account' }}</h6>
								<span class="subtitle-data">{{ qbo_asset_account }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Quantity' }}</h6>
								<span class="subtitle-data">{{ selectedItemInformation.quantity_on_hand }}</span>
							</v-col>
						</v-row>
					</div>
				</v-card-text>

				<v-divider class="d-flex d-sm-none" />

				<v-card-actions class="d-flex d-sm-none">
					<v-btn class="btn-blue mr-1" @click="onSelectEditItemData(selectedItemInformation)">
						{{ 'Edit' }}
					</v-btn>
					<v-btn text outlined class="ml-1 mr-4 btn-white" @click="onSelectItemToDisable(selectedItemInformation)">
						{{ 'Disable' }}
					</v-btn>
					<v-spacer />
					<v-btn @click="onToggleItemInformationModal" elevation="0">
						close
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-sheet>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import Search from './SearchInput.vue';
import ItemForm from './ItemForm.vue';
import ItemDisable from './ItemDisableModal.vue';
import Pagination from './Pagination.vue';
import { apiErrorMessage, debounce } from '../../utils/globalMethods';
import globalMethods from '../../utils/globalMethods'

export default {
	name: 'Items',
	components: {
		Search,
		ItemForm,
		ItemDisable,
		Pagination
	},
	data: () => ({
		page: 1,
		currentPage: 1,
		showDisableModal: false,
		selectedItemData: {},
		selectedDataToDisable: {},
		isEditMode: false,
		searchText: '',
		selectedProducts: [],
		serviceTypeSelected: '',
		showFormDialog: false,
		showSnackbar: false,
		snackbarOption: {
			icon: '',
			message: '',
			color: ''
		},
		itemsData: [],
		// pageLimit: 25,
		pageLimit: 35,
		isItemsDataLoading: true,
		selectedItemInformation: {},
		showInfoModal: false,
		isEnabled: 'Active',
		showFilter: false
	}),

	created() {
		this.fetchItemsData();
		// this.getAkauntingAccounts();
	},

	computed: {
		...mapGetters('accounting', ['isQBOEnabled']),
		tableData: {
			get() {
				return this.itemsData?.data || [];
			}
		},

		productTableHeader() {
			return [
				{
					text: 'Name',
					value: 'name',
					class: 'text-uppercase th--text font-weight-bold',
					width: '25%',
					align: 'start'
				},
				{
					text: 'Description',
					value: 'description',
					class: 'text-uppercase th--text font-weight-bold',
					width: '25%',
					align: 'start'
				},
				{
					text: 'Category',
					value: 'category',
					class: 'text-uppercase th--text font-weight-bold',
					width: '12%',
					align: 'start'
				},
				{
					text: 'Sales Price',
					value: 'sale_price_formatted',
					class: 'text-uppercase th--text font-weight-bold',
					width: '12%',
					align: 'end'
				},
				{
					text: 'Purchase Price',
					value: 'purchase_price_formatted',
					class: 'text-uppercase th--text font-weight-bold',
					width: '15%',
					align: 'end'
				},
				{ text: '', value: 'actions', sortable: false, width: '10%', align: 'end' }
			];
		},

		pagination: {
			get() {
				return this.itemsData.data ? this.itemsData.meta : {};
			}
		},

		de_income_account: {
			get() {
				return (
					(this.selectedItemInformation.de_income_account_value &&
						Object.values(JSON.parse(this.selectedItemInformation.de_income_account_value)).join('')) ||
					''
				);
			}
		},

		de_expense_account: {
			get() {
				return (
					(this.selectedItemInformation.de_expense_account_value &&
						Object.values(JSON.parse(this.selectedItemInformation.de_expense_account_value)).join(
							''
						)) ||
					''
				);
			}
		},

		qbo_income_account: {
			get() {
				return this.selectedItemInformation.qbo_income_account
					? JSON.parse(this.selectedItemInformation.qbo_income_account)['Name']
					: '';
			}
		},

		qbo_expense_account: {
			get() {
				return this.selectedItemInformation.qbo_expense_account
					? JSON.parse(this.selectedItemInformation.qbo_expense_account)['Name']
					: '';
			}
		},

		qbo_asset_account: {
			get() {
				return this.selectedItemInformation.qbo_asset_account
					? JSON.parse(this.selectedItemInformation.qbo_asset_account)['Name']
					: '';
			}
		}
	},

	watch: {
		searchText: debounce(function() {
			this.fetchItemsData();
		}, 300)
	},

	methods: {
		...mapActions('accounting', ['getItemsData']),
		...globalMethods,
		async fetchItemsData() {
			this.showFilter = false;
			try {
				this.isItemsDataLoading = true;
				const data = await this.getItemsData({
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || '',
					enabled: this.isEnabled === 'Active' ? 1 : 0
				});
				this.itemsData = data;
				this.isItemsDataLoading = false;
			} catch (error) {
				this.isItemsDataLoading = false;
				apiErrorMessage(error);
			}
		},

		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchItemsData();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchItemsData();
		},

		onToggleItemForm(options = {}) {
			this.isEditMode = false;
			this.selectedItemData = {};
			this.showFormDialog = !this.showFormDialog;
			if (options.created || options.updated) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'success',
				// 	message: options.message
				// };
				
				this.notificationMessage(options.message)
				this.showInfoModal = false;
				this.selectedItemInformation = {};
				this.showSnackbar = true;
				this.fetchItemsData();
			}
		},

		onSelectItemToDisable(data = {}) {
			this.selectedDataToDisable = JSON.parse(JSON.stringify(data));
			this.onToggleDisableItemForm();
		},

		onSelectEditItemData(data = {}) {
			this.selectedItemData = JSON.parse(JSON.stringify(data));
			this.isEditMode = true;
			this.showFormDialog = true;
		},

		onSelectItemInformation(data = {}) {
			this.selectedItemInformation = data;
			this.onToggleItemInformationModal();
		},

		onToggleItemInformationModal() {
			this.showInfoModal = !this.showInfoModal;
			if (!this.showInfoModal) {
				this.selectedItemInformation = {};
			}
		},

		onToggleDisableItemForm(options = {}) {
			this.showDisableModal = !this.showDisableModal;

			if (options.disabled) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'red',
				// 	message: options.message || 'Disabled.'
				// };
				
				const message = options.message || 'Disabled.'
				this.notificationError(message)
				this.showInfoModal = false;
				this.selectedItemInformation = {};
				this.showSnackbar = true;

				this.fetchItemsData();
			}
		},

		onDuplicateItem(data) {
			this.selectedItemData = JSON.parse(JSON.stringify(data));

			this.showFormDialog = true;
			this.isEditMode = false;
		}
	}
};
</script>

<style lang="scss">
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';

.filter-items-wrapper  {
	padding: 16px !important;
	font-size: 14px;

	.v-card__text {
		padding: 0;

		.form-label {
			font-size: 10px !important;
			color: #819fb2 !important;
			font-family: "Inter-SemiBold", sans-serif !important;
			text-transform: uppercase;
			margin-bottom: 5px;
			letter-spacing: 0 !important;
			line-height: 18px;
		}

		.v-input {
			font-size: 14px;
			margin-top: 5px;

			.v-input__control {
				.v-input__slot {
					fieldset {
						border: 1px solid #B4CFE0 !important;
					}

					.v-select__slot {
						.v-select__selections {
							.v-select__selection {
								color: #4a4a4a;
								margin: 3px 4px 3px 0;
							}
						}
					}
				}
			}
		}
	}

	.v-card__actions {
		padding: 16px 0 0;
	}
}
</style>

<style lang="scss" scoped>
	$form-color: #0889a0;
	$btn-active-color: #0171a1;
	$text-muted-color: #6d858f;
	$text-danger-color: red;

	@media screen and (max-width: 600px) {
		::v-deep .v-data-table > .v-data-table__wrapper {
			height: calc(100vh - 354px) !important;
			overflow-y: auto;
			overflow-x: hidden;
		}
	}
	::v-deep .v-data-table {
		.v-data-table__wrapper {
			height: calc(100vh - 340px);
			overflow-y: auto;
			overflow-x: hidden;
		}

		&.no-current__pagination,
		&.no-data__table {
			.v-data-table__wrapper {
				height: calc(100vh - 285px);
			}
		}
	}

	.subtitle-1 {
		font-size: 10px !important;
		color: #819fb2 !important;
		font-family: "Inter-SemiBold", sans-serif !important;
		text-transform: uppercase;
		margin-bottom: 5px;
		letter-spacing: 0 !important;
		line-height: 18px;
	}

	.subtitle-data {
		color: #4a4a4a;
	}

	.form-label {
		color: $form-color;
	}

	.form-border {
		border: 1px solid;
		border-color: $form-color !important;
	}

	.btn-active-color:active {
		background-color: $btn-active-color;
		color: #fff;
	}

	.text-muted {
		color: $text-muted-color;
	}

	.v-btn.v-item--active.v-btn--active {
		color: $btn-active-color;
	}

	.text-danger {
		color: $text-danger-color;
	}

	.v-list-item {
		min-height: 36px;
	}
	.btn-primary {
		background-color: $btn-active-color !important;
		color: #ffffff !important;
	}
	.w-100 {
		width: 100%;
	}

	.v-snack__wrapper {
		max-width: none;
	}

	.v-input__control {
		min-height: 20px !important;
	}

	.border-bottom {
		border-bottom: thin solid #EBF2F5;
	}
</style>
