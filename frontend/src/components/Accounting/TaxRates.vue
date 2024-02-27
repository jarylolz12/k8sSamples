<template>
	<div class="account-settings-taxrates-wrapper" :class="isTaxesDataLoading ? 'is-loading-wrapper' : ''">
		<div class="loading-wrapper" v-if="isTaxesDataLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

		<div v-if="!isTaxesDataLoading">		
			<v-data-table
				disable-pagination
				:headers="tableHeader"
				:items="tableData"
				:loading="isTaxesDataLoading"
				:hide-default-footer="true"
				:item-class="rowActive"
			>
				<template v-slot:top>
					<div class="d-block d-sm-flex justify-end d-md-flex d-lg-flex pa-4">
						<div class="d-sm-flex d-inline">
							<v-menu offset-y max-width="200">
								<template v-slot:activator="{ on, attrs }">
									<v-sheet class="mr-0 mb-2 mb-sm-0 mr-sm-2">
										<v-btn
											v-bind="attrs"
											v-on="on"
											outlined
											large
											height="40"
											class="text-capitalize text-left btn-white"
											color="primary"
											block
										>
											{{ 'Enabled' + (selectedStatus ? ': Yes' : ': No') }}
											<v-icon>mdi-menu-down</v-icon>
										</v-btn>
									</v-sheet>
								</template>
								<v-card width="300">
									<v-card-text class="pa-0">
										<v-list dense class="py-2">
											<v-list-item-group
												v-model="selectedStatus"
												color="primary"
												@change="fetchTaxesData"
											>
												<v-list-item :value="1">
													<v-list-item-content>
														<v-list-item-title>Yes</v-list-item-title>
													</v-list-item-content>
												</v-list-item>
												<v-list-item :value="0">
													<v-list-item-content>
														<v-list-item-title>No</v-list-item-title>
													</v-list-item-content>
												</v-list-item>
											</v-list-item-group>
										</v-list>
									</v-card-text>
								</v-card>
							</v-menu>
							<Search :inputData.sync="searchText" placeholder="Search" />
							<div v-if="isQBOEnabled === 0" class="ml-sm-2 ml-0 mt-2 mt-sm-0">
								<v-btn
									class="form-border text-capitalize"
									color="btn-blue"
									@click="onToggleTaxesForm"
									height="38"
									block
								>
									{{ 'Add Tax Rates' }}
								</v-btn>
							</div>
						</div>
					</div>
					<v-divider />
				</template>
				<template v-slot:[`item.enabled`]="{ item }">
					<!-- <v-btn small rounded outlined :color="item.enabled ? 'success' : 'error'" class="pa-1">
						<div v-if="item.enabled" class="d-flex align-center justify-space-around" style="min-width: 60px">
							{{ 'Yes' }}
							<v-icon>mdi-check-circle</v-icon>
						</div>
						<div v-else class="d-flex align-center justify-space-around" style="min-width: 60px">
							<v-icon>mdi-minus-circle</v-icon>
							{{ 'No' }}
						</div>
					</v-btn> -->

					<div class="item-enabled-wrapper">
						<span v-if="item.enabled" class="item-active-text">Active</span>
						<span v-else class="item-inactive-text">Inactive</span>
					</div>
				</template>
				<template v-if="isQBOEnabled === 0" v-slot:[`item.actions`]="{ item }">
					<v-menu offset-y bottom left content-class="outbound-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn v-bind="attrs" primary v-on="on" class="btn-more-items btn-white" text outlined small>
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>
						<v-list>
							<v-list-item link @click="onSelectEditTaxesData(item)">
								<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link @click="onSelectTaxesToDelete(item)">
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
		</div>
		<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon>
			{{ snackbarOption.message }}
		</v-snackbar> -->
		<taxes-rate-form
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedTaxesData"
			@toggle="onToggleTaxesForm"
		></taxes-rate-form>
	</div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex';
import globalMethods, { apiErrorMessage, debounce } from '../../utils/globalMethods';
import Pagination from './Pagination.vue';
import Search from './SearchInput.vue';
import TaxesRateForm from './TaxRateForm.vue';
// import TaxesDelete from './TaxesDeleteModal.vue';
export default {
	name: 'TaxRates',
	components: {
		Pagination,
		Search,
		TaxesRateForm
		// TaxesDelete
	},
	data() {
		return {
			isTaxesDataLoading: false,
			taxRateLists: [],
			selectedTaxesData: {},
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
			selectedTaxesInformation: {},
			selectedStatus: 1
		};
	},
	created() {
		this.fetchTaxesData();
	},
	computed: {
		...mapGetters('accounting', ['isQBOEnabled']),
		tableData: {
			get() {
				return this.taxRateLists?.data || [];
			}
		},

		pagination: {
			get() {
				return this.taxRateLists.data ? this.taxRateLists.meta : {};
			}
		},

		tableHeader() {
			return [
				{
					text: 'Name',
					value: 'name',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'left',
					width: '25%'
				},
				{
					text: 'Rate',
					value: 'rate',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'center',
					width: '25%',
					sortable: false
				},
				{
					text: 'Status',
					value: 'enabled',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'center',
					width: '25%',
					sortable: false
				},
				{ text: '', value: 'actions', sortable: false, class: 'th--text' }
			];
		}
	},
	watch: {
		searchText: debounce(function() {
			this.fetchTaxesData();
		}, 300)
	},
	methods: {
		...mapActions('accounting', ['getTaxRates']),
		...globalMethods,
		onSelectTaxesInformation(data) {
			this.selectedTaxesInformation = data;
			this.onToggleTaxesInformationModal();
		},

		onToggleTaxesInformationModal() {
			this.showInfoModal = !this.showInfoModal;
			if (!this.showInfoModal) {
				this.selectedTaxesInformation = {};
			}
		},
		onToggleTaxesForm(options = {}) {
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
				this.fetchTaxesData();
			}
		},

		onSelectTaxesToDelete(data = {}) {
			this.selectedDataToDelete = JSON.parse(JSON.stringify(data));
			this.onToggleDeleteTaxesForm();
		},

		onToggleDeleteTaxesForm(options = {}) {
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

				this.fetchTaxesData();
			}
		},

		onSelectEditTaxesData(data = {}) {
			this.selectedTaxesData = JSON.parse(JSON.stringify(data));
			this.isEditMode = true;
			this.showFormDialog = true;
		},

		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchTaxesData();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchTaxesData();
		},

		async fetchTaxesData() {
			if (this.isTaxesDataLoading) {
				return;
			}
			this.isTaxesDataLoading = true;
			try {
				const data = await this.getTaxRates({
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || '',
					enabled: this.selectedStatus
				});
				this.taxRateLists = data;
				this.isTaxesDataLoading = false;
			} catch (error) {
				this.isTaxesDataLoading = false;
				apiErrorMessage(error);
			}
		},

		rowActive(item) {
			const { id } = this.selectedTaxesInformation;
			if (item.id === id) {
				return 'cyan lighten-4';
			}
			return null;
		}
	}
};
</script>
<style lang="scss" scoped>
	@media screen and (max-width: 767px) {
		::v-deep {
			.v-data-table > .v-data-table__wrapper {
				height: calc(100vh - 292px) !important;
				overflow-y: auto;
				overflow-x: hidden;
			}
		}
	}
	::v-deep .v-data-table > .v-data-table__wrapper {
		height: calc(100vh - 420px);
		overflow-y: auto;
		overflow-x: hidden;
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
.account-settings-taxrates-wrapper {
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
		.v-data-table__wrapper {
			table {
				thead {
					tr {
						th {
							box-shadow: none;
							border-bottom: 2px solid #EBF2F5 !important;
							padding: 8px 12px;

							&:first-child {
								padding-left: 24px;
							}
						}
					}
				}

				tbody {
					tr {
						td {
							padding: 12px;
                        	border-bottom: 1px solid #EBF2F5 !important;

							&:first-child {
								padding-left: 24px;
							}

							.item-enabled-wrapper {
								.item-active-text {
									color: #16B442 !important;
									padding: 7px 10px;
									font-size: 12px;
									border-radius: 99px;
									font-family: 'Inter-Medium', sans-serif;
									text-transform: capitalize;
									margin-bottom: 3px;
									background-color: #ebfaef !important;
								}

								.item-inactive-text {
									padding: 7px 10px;
									font-size: 12px;
									border-radius: 99px;
									font-family: 'Inter-Medium', sans-serif;
									text-transform: capitalize;
									margin-bottom: 3px;
									background-color: #fff2f2 !important;
									color: #f93131 !important;
								}
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
