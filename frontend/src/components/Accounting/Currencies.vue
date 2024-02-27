<template>
	<div class="account-settings-currencies-wrapper" :class="isCurrencyDataLoading ? 'is-loading-wrapper' : ''">
		<div class="loading-wrapper" v-if="isCurrencyDataLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

		<div v-if="!isCurrencyDataLoading">
			<v-data-table
				:headers="tableHeader"
				:items="tableData"
				:single-select="false"
				show-select
				v-model="selectedCurrencies"
				:loading="isCurrencyDataLoading"
				disable-pagination
				:hide-default-footer="true"
				:item-class="rowActive"
			>
				<template v-slot:top>
					<div class="d-block d-sm-flex d-md-flex d-lg-flex pa-4">
						<div class="flex-grow-1">
							<p class="header-2 mb-0 pt-2">{{ 'Home Currency' }}: {{ homeCurrency }}</p>
						</div>
						<div class="d-sm-flex d-inline">
							<Search :inputData.sync="searchText" placeholder="Search" />
							<div class="ml-sm-2 ml-0 mt-2 mt-sm-0">
								<v-btn
									class="form-border text-capitalize"
									color="btn-blue"
									@click="onToggleCurrencyForm"
									height="38"
									block
								>
									{{ 'Add Currency' }}
								</v-btn>
							</div>
						</div>
					</div>
					<v-divider />
				</template>
				<template v-slot:[`item.rate`]="{ item }">
					<h5>{{ item.rate.toFixed(item.precision) }}</h5>
				</template>
				<template v-slot:[`item.thousands_separator`]="{ item }">
					<h5 class="separator">{{ item.thousands_separator }}</h5>
				</template>
				<template v-slot:[`item.decimal_mark`]="{ item }">
					<h5 class="separator">{{ item.decimal_mark }}</h5>
				</template>
				<template v-slot:[`item.actions`]="{ item }">
					<v-menu offset-y bottom left content-class="outbound-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn v-bind="attrs" primary v-on="on" class="btn-more-items btn-white" text outlined small>
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>
						<v-list>
							<v-list-item link @click="onSelectEditCurrencyData(item)">
								<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link @click="onSelectCurrencyInformation(item)">
								<v-list-item-title>{{ 'View' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link @click="onSelectCurrencyToDelete(item)">
								<v-list-item-title class="red--text">{{ 'Delete' }}</v-list-item-title>
							</v-list-item>
						</v-list>
					</v-menu>
				</template>
			</v-data-table>
			<v-divider />
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
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon>
				{{ snackbarOption.message }}
			</v-snackbar> -->
		</div>
		<currency-form
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedCurrencyData"
			@toggle="onToggleCurrencyForm"
		></currency-form>
		<currency-delete
			:open="showDeleteModal"
			:form-data="selectedDataToDelete"
			@toggle="onToggleDeleteCurrencyForm"
		></currency-delete>
		<v-dialog v-model="showInfoModal" max-width="500" origin="top center" content-class="accounting-module-dialog pa-0" persistent scrollable>
			<v-card>
				<v-card-title class="pa-0">
					<span class="headline">{{ selectedCurrencyInformation.name }}</span>
					<v-spacer></v-spacer>

					<v-btn
						class="d-none d-sm-flex btn-blue"
						color="btn-blue"
						@click="onSelectEditCurrencyData(selectedCurrencyInformation)"
					>
						{{ 'Edit' }}
					</v-btn>
					<v-btn
						text
						outlined
						class="ml-1 mr-2 d-none d-sm-flex btn-white"
						@click="onSelectCurrencyToDelete(selectedCurrencyInformation)"
					>
						{{ 'delete' }}
					</v-btn>
					<v-btn icon class="d-none d-sm-flex" @click="onToggleCurrencyInformationModal">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text class="pa-0">
					<v-row no-gutters v-for="item in tableHeader" :key="item.text" class="row-stripe py-2 px-1">
						<v-col cols="6" class="text-left ">
							<h5 class="labelcolor--text ml-5">{{ item.text }}</h5>
						</v-col>
						<v-col cols="6" class="text-left">
							<h5 style="color: #4a4a4a; font-size: 12px !important;">{{ selectedCurrencyInformation[item.value] }}</h5>
						</v-col>
					</v-row>
				</v-card-text>
			</v-card>
		</v-dialog>
	</div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import globalMethods, { apiErrorMessage, debounce } from '../../utils/globalMethods';
import Pagination from './Pagination.vue';
import Search from './SearchInput.vue';
import CurrencyForm from './CurrencyForm.vue';
import CurrencyDelete from './CurrencyDeleteModal.vue';
export default {
	name: 'Currencies',
	components: {
		Pagination,
		Search,
		CurrencyForm,
		CurrencyDelete
	},
	data() {
		return {
			isCurrencyDataLoading: false,
			selectedCurrencies: [],
			currencyLists: [],
			selectedCurrencyData: {},
			page: 1,
			currentPage: 1,
			// pageLimit: 25,
			pageLimit: 35,
			searchText: '',
			isEditMode: false,
			showFormDialog: false,
			showSnackbar: false,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			},
			showDeleteModal: false,
			selectedDataToDelete: {},
			showInfoModal: false,
			selectedCurrencyInformation: {}
		};
	},
	watch: {
		searchText: debounce(function() {
			this.fetchCurrencyData();
		}, 300)
	},
	methods: {
		...mapActions('accounting', ['getAccountingCurrencies']),
		...globalMethods,
		onSelectCurrencyInformation(data) {
			this.selectedCurrencyInformation = data;
			this.onToggleCurrencyInformationModal();
		},

		onToggleCurrencyInformationModal() {
			this.showInfoModal = !this.showInfoModal;
			if (!this.showInfoModal) {
				this.selectedCurrencyInformation = {};
			}
		},
		onToggleCurrencyForm(options = {}) {
			this.isEditMode = false;
			// this.selectedServiceData = {};
			this.showFormDialog = !this.showFormDialog;
			if (options.created || options.updated) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'success',
				// 	message: options.message
				// };
				this.notificationMessage(options.message)
				this.showSnackbar = true;
				this.showInfoModal = false;
				this.fetchCurrencyData();
			}
		},

		onSelectCurrencyToDelete(data = {}) {
			this.selectedDataToDelete = JSON.parse(JSON.stringify(data));
			this.onToggleDeleteCurrencyForm();
		},

		onToggleDeleteCurrencyForm(options = {}) {
			this.showDeleteModal = !this.showDeleteModal;

			if (options.deleted) {
				// this.snackbarOption = {
				// 	icon: 'mdi-delete',
				// 	color: 'red',
				// 	message: options.message || 'Deleted.'
				// };

				const message = options.message || 'Deleted.'
				this.notificationError(message)
				this.showSnackbar = true;
				this.showInfoModal = false;

				this.fetchCurrencyData();
			}
		},

		onSelectEditCurrencyData(data = {}) {
			this.selectedCurrencyData = JSON.parse(JSON.stringify(data));
			this.isEditMode = true;
			this.showFormDialog = true;
		},

		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchCurrencyData();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchCurrencyData();
		},
		async fetchCurrencyData() {
			if (this.isCurrencyDataLoading) {
				return;
			}
			this.isCurrencyDataLoading = true;
			try {
				const data = await this.getAccountingCurrencies({
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || ''
				});
				this.currencyLists = data;
				this.isCurrencyDataLoading = false;
			} catch (error) {
				this.isCurrencyDataLoading = false;
				apiErrorMessage(error);
			}
		},

		rowActive(item) {
			const { id } = this.selectedCurrencyInformation;
			if (item.id === id) {
				return 'cyan lighten-4';
			}
			return null;
		}
	},
	created() {
		this.fetchCurrencyData();
	},
	computed: {
		...mapGetters('accounting', ['homeCurrency']),
		tableData: {
			get() {
				return this.currencyLists?.data || [];
			}
		},

		pagination: {
			get() {
				return this.currencyLists.data ? this.currencyLists.meta : {};
			}
		},

		tableHeader() {
			return [
				{
					text: 'Code',
					value: 'code',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '6%',
				},
				{
					text: 'Currency Name',
					value: 'name',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '12%',
				},
				{
					text: 'Symbol',
					value: 'symbol',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '8%',
				},
				{
					text: 'Symbol Position',
					value: 'symbol_first',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '12%',
					sortable: false,
				},
				{
					text: 'Precision',
					value: 'precision',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '10%',
				},
				{
					text: 'Rate',
					value: 'rate',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '8%',
				},
				{
					text: 'Thousands Separator',
					value: 'thousands_separator',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'center',
					width: '15%',
					sortable: false,
				},
				{
					text: 'Decimal Mark',
					value: 'decimal_mark',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'center',
					width: '12%',
					sortable: false,
				},
				{
					text: 'Default Currency',
					value: 'default_currency',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'start',
					width: '15%',
					sortable: false,
				},
				{ text: '', value: 'actions', sortable: false, class: 'th--text', }
			];
		}
	}
};
</script>
<style lang="scss" scoped>
	@media screen and (max-width: 767px) {
		::v-deep {
			.v-data-table > .v-data-table__wrapper {
				height: calc(100vh - 262px) !important;
				overflow-y: auto;
				overflow-x: hidden;
			}
		}
	}

	.w-100 {
		width: 100%;
	}

	.v-list-item {
		min-height: 36px;
	}
	.row-stripe:nth-child(odd) {
		background-color: rgb(243, 243, 243);
	}
</style>

<style lang="scss">
.account-settings-currencies-wrapper {
	.search-component-wrapper {
		.v-input {
			.v-input__control {
				.v-input__slot{
					min-height: 40px !important;
				}
			}
		}
	}

	&.is-loading-wrapper {
        background-color: transparent !important;

        .loading-wrapper {
            width: 100%;
            min-height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }

	.v-data-table {
		.mdi-checkbox-blank-outline::before {
			content: "\F14FC";
			color: #b4cfe0;
		}
		
		.v-data-table__wrapper {
			table {
				thead {
					tr {
						th {
							box-shadow: none;
							border-bottom: 2px solid #EBF2F5 !important;
							padding: 8px 12px;
							background-color: #f7f7f7;
							height: 40px;

							&:nth-child(2) {
								padding-left: 0;
							}
						}
					}
				}

				tbody {
					tr {
						td {
							padding: 12px;
                        	border-bottom: 1px solid #EBF2F5 !important;

							&:nth-child(2) {
								padding-left: 0;
							}

							.btn-more-items {
								min-width: 36px;
								padding: 6px !important;
								height: 35px !important;
								margin: 0 10px 0 auto;

								i {
									font-size: 18px;
								}
							}
						}
					}
				}
			}
		}
	}
}
</style>
