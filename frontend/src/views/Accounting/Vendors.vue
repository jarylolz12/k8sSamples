<template>
	<v-sheet fluid color="transparent">
		<h3 class="font-20 mb-3 ml-2 mt-3 ml-sm-0 mt-sm-0">Vendors</h3>
		<v-sheet outlined color="#EBF2F5" style="border-radius: 4px;">
			<v-data-table
				v-model="selectedVendors"
				class="v-table-middle-align"
				v-bind:class="{
					'no-data__table' : (tableData.length === 0),
					'no-current__pagination' : (pagination.last_page <= 1),
				}"
				fixed-header
				show-select
				disable-pagination
				:headers="tableHeader"
				:items="tableData"
				:single-select="false"
				:loading="isVendorsDataLoading"
				:hide-default-footer="true">

				<template v-slot:top>
					<div class="d-md-flex justify-end w-100 px-4 py-3 border-bottom">
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
											color="primary"
											style="color: #4a4a4a;">
											<img src="@/assets/icons/filters.svg" width="18px" height="18px" class="mr-2">
											<span style="color: #4a4a4a;">{{ 'Filters' }}</span>
											<!-- <v-icon>mdi-menu-down</v-icon> -->
										</v-btn>
									</template>

									<v-card width="300" class="filter-items-wrapper pt-4">
										<v-card-text>
											<label for="filter-enabled" class="primary--text form-label">{{ 'Status' }}</label>
											<v-select
												id="filter-enabled"
												v-model="isEnabled"
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
											<v-btn class="btn-blue" text link @click="fetchVendorsData">
												{{ 'Ok' }}
											</v-btn>
										</v-card-actions>
									</v-card>
								</v-menu>
								<div class="search-field-wrapper ml-2">
									<Search :inputData.sync="searchText" placeholder="Search Vendors" />
									<!-- <v-text-field
										v-model="searchText"
										:label="('search_vendors')"
										prepend-inner-icon="mdi-magnify"
										clearable
										hide-details
										flat
										solo
										outlined
										dense
									></v-text-field> -->
								</div>
							</div>
							<div class="d-md-block d-none">
								<v-btn
									class="form-border text-capitalize mx-2 mr-0"
									color="btn-blue"
									@click="onToggleVendorForm"
									height="38">
									{{ 'Add Vendor' }}
								</v-btn>
							</div>
							<div class="d-sm-block d-md-none mt-2">
								<v-btn
									class="form-border text-capitalize"
									color="btn-blue"
									@click="onToggleVendorForm"
									height="38"
									width="100%">
									{{ 'Add Vendor' }}
								</v-btn>
							</div>
						</div>
					</div>
				</template>
				<template v-slot:[`item.enabled`]="{ item }">
					<!-- <v-btn text small rounded outlined :color="item.enabled ? 'success' : 'error'" class="pa-0">
						<div
							v-if="item.enabled"
							class="d-flex align-center justify-space-around pa-2"
							style="min-width: 60px; letter-spacing: 0;">
							{{ 'Yes' }}
							<v-icon class="ml-1">mdi-check-circle</v-icon>
						</div>
						<div v-else class="d-flex align-center justify-space-around" style="min-width: 60px; letter-spacing: 0;">
							<v-icon class="ml-1">mdi-minus-circle</v-icon>
							{{ 'No' }}
						</div>
					</v-btn> -->

					<div class="item-enabled-wrapper">
						<span v-if="item.enabled" class="item-active-text">Active</span>
						<span v-else class="item-inactive-text">Inactive</span>
					</div>
				</template>

				<template v-slot:[`item.actions`]="{ item }">
					<v-menu offset-y bottom left content-class="outbound-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn v-bind="attrs" primary v-on="on" class="btn-more-items btn-white" text outlined small>
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>
						<v-list>
							<v-list-item link @click="onSelectEditVendorData(item)">
								<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link @click="onSelectVendorInformation(item)">
								<v-list-item-title>{{ 'View' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link @click="onSelectVendorToDisable(item)">
								<v-list-item-title class="red--text">{{ 'Disable' }}</v-list-item-title>
							</v-list-item>
						</v-list>
					</v-menu>
				</template>

				<template v-slot:no-data>
					<div class="mt-5" v-if="isVendorsDataLoading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate>
						</v-progress-circular>
					</div>

					<v-alert class="mt-2" v-if="!isVendorsDataLoading && tableData.length === 0 && searchText === ''">
						<img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="" class="mb-3">
						<p class="subtitle-2 no-data-title">Add Vendors</p>
						<span class="subtitle-p-data">You have not added any vendors yet.</span>
						<br />
						<v-btn
							outlined
							text
							small
							@click="onToggleVendorForm"
							color="#0889a0"
							class="py-4 text-capitalize btn-white">
							<v-icon size="18">mdi-plus</v-icon>
							{{ 'Add Vendor' }}
						</v-btn>
					</v-alert>

					<v-alert class="mt-2" v-if="!isVendorsDataLoading && tableData.length === 0 && searchText !== ''">
						<img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="" class="mb-3">
						<p class="subtitle-2 no-data-title">No Vendors</p>
						<span class="subtitle-p-data">Sorry! We could not find any vendors that matches your search term.</span>
					</v-alert>
				</template>
				<template v-slot:loading>
					<div class="mt-5" v-if="isVendorsDataLoading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate>
						</v-progress-circular>
					</div>
				</template>
				<template v-slot:[`item.name`]="{ item }">
					<a href="#" style="text-decoration: none;" @click.prevent="$router.push(`/accounting/vendors/${item.id}`)">
						<span>{{ item.name }}</span>
					</a>
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
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar> -->
			<vendor-form
				:open="showFormDialog"
				:is-edit-mode="isEditMode"
				:form-values="selectedVendorData"
				@toggle="onToggleVendorForm"
			></vendor-form>
			<vendor-disable
				:open="showDisableModal"
				:form-data="selectedDataToDisable"
				@toggle="onToggleDisableVendorForm"
			></vendor-disable>

			<v-dialog
				v-model="showInfoModal"
				max-width="980"
				origin="top center"
				content-class="accounting-module-dialog pa-0"
				persistent
				scrollable
				:fullscreen="isMobile">
				<v-card>
					<v-card-title class="pa-0">
						<span class="headline">{{ selectedVendorInformation.name }}</span>
						<v-spacer></v-spacer>

						<v-btn
							class="d-none d-sm-flex btn-blue mr-1"
							color="primary"
							@click="onSelectEditVendorData(selectedVendorInformation)">
							{{ 'Edit' }}
						</v-btn>
						<v-btn
							text
							outlined
							class="ml-1 mr-2 d-none d-sm-flex btn-white"
							@click="onSelectVendorToDisable(selectedVendorInformation)">
							{{ 'disable' }}
						</v-btn>
						<v-btn icon class="d-none d-sm-flex" @click="onToggleVendorInformationModal">
							<v-icon>mdi-close</v-icon>
						</v-btn>
					</v-card-title>
					<v-card-text class="py-4 pb-8">
						<v-row>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Email' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.email }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Phone' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.phone }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Currency' }}</h6>
								<span class="subtitle-data">{{ currencyValue }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Website' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.website }}</span>
							</v-col>
						</v-row>
						<v-row>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Reference' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.reference }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Tax Number' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.tax_number }}</span>
							</v-col>
							<v-col md="6" lg="6">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Address' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.address }}</span>
							</v-col>
						</v-row>
						<v-row>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'City' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.city }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'State' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.state }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Postal/Zip Code' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.zip_code }}</span>
							</v-col>
							<v-col md="3" lg="3" cols="12">
								<h6 class="subtitle-1 labelcolor--text">{{ 'Country' }}</h6>
								<span class="subtitle-data">{{ selectedVendorInformation.country }}</span>
							</v-col>
						</v-row>
					</v-card-text>
					<v-divider class="d-flex d-sm-none" />
					<v-card-actions class="d-flex d-sm-none">
						<v-btn color="primary" class="btn-blue" @click="onSelectEditVendorData(selectedVendorInformation)">
							{{ 'Edit' }}
						</v-btn>
						<v-btn
							text
							outlined
							class="ml-1 mr-4 btn-white"
							@click="onSelectVendorToDisable(selectedVendorInformation)">
							{{ 'Disable' }}
						</v-btn>
						<v-spacer />
						<v-btn @click="onToggleVendorInformationModal" elevation="0" class="btn-white">
							Close
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>
		</v-sheet>
	</v-sheet>
</template>

<script>
import { mapActions } from 'vuex';
import VendorForm from '../../components/Accounting/VendorForm.vue';
import VendorDisable from '../../components/Accounting/VendorDisableModal.vue';
import Pagination from '../../components/Accounting/Pagination.vue';
import { apiErrorMessage, debounce } from '../../utils/globalMethods';
import Search from '../../components/Accounting/SearchInput.vue';
import globalMethods from '../../utils/globalMethods'

export default {
	name: 'Vendors',
	components: {
		VendorForm,
		VendorDisable,
		Pagination,
		Search
	},
	data: () => ({
		showInfoModal: false,
		isVendorTogglingProgress: false,
		isVendorsDataLoading: false,
		vendorLists: [],
		page: 1,
		showDisableModal: false,
		selectedVendorData: {},
		selectedVendorInformation: {},
		selectedDataToDisable: {},
		isEditMode: false,
		searchText: '',
		selectedVendors: [],
		showFormDialog: false,
		showSnackbar: false,
		snackbarOption: {
			icon: '',
			message: '',
			color: ''
		},
		currentPage: 1,
		// pageLimit: 25,
		pageLimit: 35,
		showFilter: false,
		isEnabled: 'Active'
	}),
	created() {
		this.fetchVendorsData();
	},

	computed: {
		// ...mapState('accounting', ['vendorLists']),
		tableData: {
			get() {
				return this.vendorLists?.data || [];
			}
		},

		tableHeader() {
			return [
				{
					text: 'Name',
					value: 'name',
					class: 'text-uppercase th--text font-weight-bold',
					width: '25%',
					align: 'start'
				},
				// {
				//   text: ('phone'),
				//   value: "phone",
				//   class: "text-uppercase th--text font-weight-bold"
				// },
				{
					text: 'Address',
					value: 'address',
					class: 'text-uppercase th--text font-weight-bold',
					width: '25%',
					align: 'start'
				},
				{
					text: 'Email',
					value: 'email',
					class: 'text-uppercase th--text font-weight-bold',
					width: '25%',
					align: 'start'
				},
				{
					text: 'Status',
					value: 'enabled',
					class: 'text-uppercase th--text font-weight-bold',
					width: '12%',
					align: 'center',
					sortable: false
				},
				{ text: '', value: 'actions', sortable: false, width: '10%' }
			];
		},

		pagination: {
			get() {
				return this.vendorLists.data ? this.vendorLists.meta : {};
			}
		},

		currencyValue: {
			get() {
				return this.selectedVendorInformation?.currency_values
					? JSON.parse(this.selectedVendorInformation.currency_values).name
					: '';
			}
		},

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		}
	},

	watch: {
		searchText: debounce(function() {
			this.fetchVendorsData();
		}, 300)
	},

	methods: {
		...mapActions('accounting', ['getVendorsData']),
		...globalMethods,
		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchVendorsData();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchVendorsData();
		},

		toggleVendorIsEnable() {
			this.isVendorTogglingProgress = true;
			// this.isVendorTogglingProgress = false
		},

		async fetchVendorsData() {
			this.showFilter = false;
			if (this.isVendorsDataLoading) {
				return;
			}
			this.isVendorsDataLoading = true;
			try {
				const data = await this.getVendorsData({
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || '',
					enabled: this.isEnabled === 'Active' ? 1 : 0
				});
				this.vendorLists = data;
				this.isVendorsDataLoading = false;
			} catch (error) {
				this.isVendorsDataLoading = false;
				apiErrorMessage(error);
			}
		},

		onToggleVendorForm(options = {}) {
			this.isEditMode = false;
			this.selectedServiceData = {};
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
				this.fetchVendorsData();
			}
		},

		onSelectEditVendorData(data = {}) {
			this.selectedVendorData = JSON.parse(JSON.stringify(data));
			this.isEditMode = true;
			this.showFormDialog = true;
		},

		onSelectVendorToDisable(data = {}) {
			this.selectedDataToDisable = JSON.parse(JSON.stringify(data));
			this.onToggleDisableVendorForm();
		},

		onToggleDisableVendorForm(options = {}) {
			this.showDisableModal = !this.showDisableModal;

			if (options.disabled) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'red',
				// 	message: options.message || 'Disabled.'
				// };

				const message = options.message || 'Vendor has been disabled.'
				this.notificationCustom(message)
				this.showSnackbar = true;
				this.showInfoModal = false;

				this.fetchVendorsData();
			}
		},

		onSelectVendorInformation(data) {
			this.selectedVendorInformation = data;
			this.onToggleVendorInformationModal();
		},

		onToggleVendorInformationModal() {
			this.showInfoModal = !this.showInfoModal;
			if (!this.showInfoModal) {
				this.selectedVendorInformation = {};
			}
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
	@media screen and (max-width: 600px) {
		::v-deep .v-data-table > .v-data-table__wrapper {
			height: calc(100vh - 296px) !important;
			overflow-y: auto;
			overflow-x: hidden;
		}
	}
	::v-deep .v-data-table {
		.v-data-table__wrapper {
			height: calc(100vh - 285px);
			overflow-y: auto;
			overflow-x: hidden;
		}

		&.no-current__pagination,
		&.no-data__table {
			.v-data-table__wrapper {
				height: calc(100vh - 230px);
			}
		}
	}

	h3 {
		font-size: 24px !important;
        margin-bottom: 0;
        color: #4a4a4a;
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

	.v-tabs {
		border-bottom: 1px solid #ddd;

		.v-tab {
			color: #0889a0;
		}
	}

	.w-100 {
		width: 100%;
	}

	.v-list-item {
		min-height: 36px;
	}

	.border-bottom {
		border-bottom: thin solid #EBF2F5;
	}
</style>
