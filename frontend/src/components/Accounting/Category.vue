<template>
	<div class="account-settings-categories-wrapper" :class="isCategoryDataLoading ? 'is-loading-wrapper' : ''">
		<div class="loading-wrapper" v-if="isCategoryDataLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

		<div v-if="!isCategoryDataLoading">
			<v-data-table
				:headers="tableHeader"
				:items="tableData"
				class=""
				v-model="selectedCurrencies"
				:loading="isCategoryDataLoading"
				disable-pagination
				:hide-default-footer="true"
				:item-class="rowActive"
			>
				<template v-slot:top>
					<div class="d-block justify-end d-sm-flex d-md-flex d-lg-flex pa-4">
						<div class="d-sm-flex d-inline">
							<Search :inputData.sync="searchText" placeholder="Search" />
							<div class="ml-sm-2 ml-0 mt-2 mt-sm-0">
								<v-btn
									class="form-border text-capitalize btn-blue"
									color="btn-blue"
									@click="onToggleCategoryForm"
									height="38"
									block
								>
									{{ 'Add Category' }}
								</v-btn>
							</div>
						</div>
					</div>
					<v-divider />
				</template>
				<template v-slot:[`item.color`]="{ item }">
					<v-sheet :color="item.color" class="pa-2">{{ item.color }}</v-sheet>
				</template>
				<template v-slot:[`item.type`]="{ item }">
					<span style="text-transform: capitalize;">{{ item.type }}</span>
				</template>
				<template v-slot:[`item.enabled`]="{ item }">
					<!-- <v-btn small rounded outlined :color="item.enabled ? 'success' : 'error'" class="pa-4">
						<div
							v-if="item.enabled"
							class="d-flex align-center justify-space-around"
							style="min-width: 60px; letter-spacing: 0;"
						>
							<v-icon>mdi-check-circle</v-icon>
							{{ 'Yes' }}
						</div>
						<div v-else class="d-flex align-center justify-space-around" style="min-width: 60px; letter-spacing: 0;">
							<v-icon>mdi-minus-circle</v-icon>
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
							<v-list-item link @click="onSelectEditCategoryData(item)">
								<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link @click="onSelectCategoryInformation(item)">
								<v-list-item-title>{{ 'View' }}</v-list-item-title>
							</v-list-item>
							<v-list-item link @click="onSelectCategoryToDelete(item)">
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
		<category-form
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedCategoryData"
			@toggle="onToggleCategoryForm"
		></category-form>
		<category-delete
			:open="showDeleteModal"
			:form-data="selectedDataToDelete"
			@toggle="onToggleDeleteCategoryForm"
		></category-delete>
		<v-dialog v-model="showInfoModal" max-width="500" origin="top center" content-class="view-category-accounting-dialog accounting-module-dialog pa-0" persistent scrollable>
			<v-card>
				<v-card-title class="pa-0">
					<span class="headline">{{ selectedCategoryInformation.name }}</span>
					<v-spacer></v-spacer>

					<v-btn
						class="d-none d-sm-flex btn-blue"
						color="primary"
						@click="onSelectEditCategoryData(selectedCategoryInformation)"
					>
						{{ 'Edit' }}
					</v-btn>
					<v-btn
						text
						outlined
						class="ml-1 mr-2 d-none d-sm-flex btn-white mr-1"
						@click="onSelectCategoryToDelete(selectedCategoryInformation)"
					>
						{{ 'delete' }}
					</v-btn>
					<v-btn icon class="d-none d-sm-flex" @click="onToggleCategoryInformationModal">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text class="pa-0">
					<v-row no-gutters v-for="item in tableHeader" :key="item.text" class="row-stripe py-2 px-1">
						<v-col cols="6" class="text-left">
							<h5 class="labelcolor--text ml-5">{{ item.text }}</h5>
						</v-col>
						<v-col cols="6" class="text-left">
							<h5 style="color: #4a4a4a; font-size: 12px !important;">{{ selectedCategoryInformation[item.value] }}</h5>
						</v-col>
					</v-row>
				</v-card-text>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
import { mapActions } from 'vuex';
import globalMethods, { apiErrorMessage, debounce } from '../../utils/globalMethods';
import Pagination from './Pagination.vue';
import Search from './SearchInput.vue';
import CategoryForm from './CategoryForm.vue';
import CategoryDelete from './CategoryDeleteModal.vue';
export default {
	name: 'CategorySettings',
	components: {
		Pagination,
		Search,
		CategoryForm,
		CategoryDelete
	},
	data() {
		return {
			isCategoryDataLoading: false,
			selectedCurrencies: [],
			categoryList: [],
			selectedCategoryData: {},
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
			selectedCategoryInformation: {}
		};
	},
	watch: {
		searchText: debounce(function() {
			this.fetchCategoryData();
		}, 300)
	},
	methods: {
		...mapActions('accounting', ['getCategories']),
		...globalMethods,
		onSelectCategoryInformation(data) {
			this.selectedCategoryInformation = data;
			this.onToggleCategoryInformationModal();
		},

		onToggleCategoryInformationModal() {
			this.showInfoModal = !this.showInfoModal;
			if (!this.showInfoModal) {
				this.selectedCategoryInformation = {};
			}
		},
		onToggleCategoryForm(options = {}) {
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
				this.fetchCategoryData();
			}
		},

		onSelectCategoryToDelete(data = {}) {
			this.selectedDataToDelete = JSON.parse(JSON.stringify(data));
			this.onToggleDeleteCategoryForm();
		},

		onToggleDeleteCategoryForm(options = {}) {
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

				this.fetchCategoryData();
			}
		},

		onSelectEditCategoryData(data = {}) {
			this.selectedCategoryData = JSON.parse(JSON.stringify(data));
			this.isEditMode = true;
			this.showFormDialog = true;
		},

		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchCategoryData();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchCategoryData();
		},
		async fetchCategoryData() {
			if (this.isCategoryDataLoading) {
				return;
			}
			this.isCategoryDataLoading = true;
			try {
				const data = await this.getCategories({
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || ''
				});
				this.categoryList = data;
				this.isCategoryDataLoading = false;
			} catch (error) {
				this.isCategoryDataLoading = false;
				apiErrorMessage(error);
			}
		},

		rowActive(item) {
			const { id } = this.selectedCategoryInformation;
			if (item.id === id) {
				return 'cyan lighten-4';
			}
			return null;
		}
	},
	created() {
		this.fetchCategoryData();
	},
	computed: {
		tableData: {
			get() {
				return this.categoryList?.data || [];
			}
		},

		pagination: {
			get() {
				return this.categoryList.data ? this.categoryList.meta : {};
			}
		},

		tableHeader() {
			return [
				{
					text: 'NAME',
					value: 'name',
					class: 'text-uppercase th--text font-weight-bold',
					width: '20%',
					align: 'start'
				},
				{
					text: 'TYPE',
					value: 'type',
					class: 'text-capitalize th--text font-weight-bold',
					align: 'start',
					width: '20%',
				},
				{
					text: 'COLOR',
					value: 'color',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'center',
					width: '25%',
				},
				{
					text: 'STATUS',
					value: 'enabled',
					class: 'text-uppercase th--text font-weight-bold',
					align: 'center',
					width: '20%',
					sortable: false,
				},
				{ text: '', value: 'actions', sortable: false, class: "th--text", width: '10%', }
			];
		}
	}
};
</script>

<style lang="scss" scoped>
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
	.row-stripe:nth-child(odd) {
		background-color: rgb(243, 243, 243);
	}
</style>

<style lang="scss">
.account-settings-categories-wrapper {
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
							height: 40px;
							background-color: #f7f7f7;

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
