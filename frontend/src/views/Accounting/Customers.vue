<template>
	<v-sheet outlined class="accounting-customers-wrapper">
		<v-data-table
			v-model="selectedCustomers"
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
			:loading="isCustomersDataLoading"
			:hide-default-footer="true"
		>
			<template v-slot:top>
				<div class="d-block d-sm-flex d-md-flex d-lg-flex pa-4 py-3">
					<div class="mr-auto">
						<h3 class="mb-0 pt-2">{{ 'Customers' }}</h3>
					</div>
					<div class="d-sm-flex d-inline">
						<Search :inputData.sync="searchText" placeholder="Search Customers" />
						<div class="ml-sm-2 ml-0 mt-2 mt-sm-0">
							<v-btn
								class="form-border text-capitalize"
								color="btn-blue"
								@click="onToggleCustomerForm"
								height="38"
								block
							>
								{{ 'Add Customer' }}
							</v-btn>
						</div>
					</div>
				</div>
				<v-divider />
			</template>
			<template v-slot:[`item.enabled`]="{ item }">
				<!-- <v-btn small rounded outlined :color="item.enabled ? 'success' : 'error'" class="pa-1">
					<div v-if="item.enabled" class="d-flex align-center justify-space-around" style="min-width: 60px; letter-spacing: 0;">
						{{ 'Yes' }}
						<v-icon>mdi-check-circle</v-icon>
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
						<v-btn v-bind="attrs" primary v-on="on" class="btn-more-items btn-white" color="primary" text outlined small>
							<v-icon>mdi-dots-horizontal</v-icon>
						</v-btn>
					</template>
					<v-list>
						<v-list-item link @click="onSelectEditCustomerData(item)">
							<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectCustomerInformation(item)">
							<v-list-item-title>{{ 'View' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectCustomerToDelete(item)">
							<v-list-item-title class="red--text">{{ 'Delete' }}</v-list-item-title>
						</v-list-item>
					</v-list>
				</v-menu>
			</template>
			<template v-slot:no-data>
				<div class="mt-5" v-if="isCustomersDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>

				<v-alert class="mt-2" v-if="!isCustomersDataLoading && tableData.length === 0 && searchText === ''">
					<img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">Add Customers</p>
					<span class="subtitle-p-data">You have not added any customers yet.</span>
					<br />
					<v-btn
						outlined
						text
						small
						@click="onToggleCustomerForm"
						color="#0889a0"
						class="py-4 text-capitalize btn-white"
					>
						<v-icon size="18">mdi-plus</v-icon>
						{{ 'Add Customer' }}
					</v-btn>
				</v-alert>

				<v-alert class="mt-2" v-if="!isCustomersDataLoading && tableData.length === 0 && searchText !== ''">
					<img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">No Customers</p>
					<span class="subtitle-p-data">Sorry! We could not find any customers that matches your search term.</span>
				</v-alert>
			</template>
			<template v-slot:loading>
				<div class="mt-5" v-if="isCustomersDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>
			</template>
			<template v-slot:[`item.name`]="{ item }">
				<a href="#" style="text-decoration: none;" @click.prevent="$router.push(`/accounting/customers/${item.id}`)">
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
		<customer-form
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedCustomerData"
			@toggle="onToggleCustomerForm"
		></customer-form>
		<customer-delete
			:open="showDeleteModal"
			:form-data="selectedDataToDelete"
			@toggle="onToggleDeleteCustomerForm"
		></customer-delete>

		<v-dialog
			v-model="showInfoModal"
			max-width="980"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card>
				<v-card-title class="pa-0">
					<span class="headline">{{ selectedCustomerInformation.name }}</span>
					<v-spacer></v-spacer>
					<v-btn
						class="d-none d-sm-flex btn-blue mr-1"
						color="btn-blue"
						@click="onSelectEditCustomerData(selectedCustomerInformation)"
					>
						{{ 'Edit' }}
					</v-btn>
					<v-btn
						text
						outlined
						class="ml-1 mr-4 d-none d-sm-flex btn-white"
						@click="onSelectCustomerToDelete(selectedCustomerInformation)"
					>
						{{ 'Delete' }}
					</v-btn>
					<v-btn icon class="d-none d-sm-flex" @click="onToggleCustomerInformationModal">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text class="py-4 pb-8">
					<v-row>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Email' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.email }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Phone' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.phone }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Currency' }}</h6>
							<span class="subtitle-data">{{ currencyValue }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Website' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.website }}</span>
						</v-col>
					</v-row>
					<v-row>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Reference' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.reference }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Tax Number' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.tax_number }}</span>
						</v-col>
						<v-col md="6" lg="6">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Address' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.address }}</span>
						</v-col>
					</v-row>
					<v-row>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'City' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.city }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'State' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.state }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Postal/Zip Code' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.zip_code }}</span>
						</v-col>
						<v-col md="3" lg="3" cols="12">
							<h6 class="subtitle-1 labelcolor--text">{{ 'Country' }}</h6>
							<span class="subtitle-data">{{ selectedCustomerInformation.country }}</span>
						</v-col>
					</v-row>
				</v-card-text>
				<v-divider class="d-flex d-sm-none" />
				<v-card-actions class="d-flex d-sm-none">
					<v-btn color="btn-blue" class="btn-blue" @click="onSelectEditCustomerData(selectedCustomerInformation)">
						{{ 'Edit' }}
					</v-btn>
					<v-btn
						text
						outlined
						class="ml-1 mr-4 btn-white"
						@click="onSelectCustomerToDelete(selectedCustomerInformation)"
					>
						{{ 'Delete' }}
					</v-btn>
					<v-spacer />
					<v-btn @click="onToggleCustomerInformationModal" elevation="0">
						Close
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-sheet>
</template>

<script>
import { mapActions } from 'vuex';
import CustomerForm from '../../components/Accounting/CustomerForm.vue';
import CustomerDelete from '../../components/Accounting/CustomerDeleteModal.vue';
import Pagination from '../../components/Accounting/Pagination.vue';
import Search from '../../components/Accounting/SearchInput.vue';
import { apiErrorMessage, debounce } from '../../utils/globalMethods';
import globalMethods from '../../utils/globalMethods'

export default {
	name: 'Customers',
	components: {
		CustomerForm,
		CustomerDelete,
		Search,
		Pagination
	},

	data() {
		return {
			showInfoModal: false,
			isCustomerTogglingProgress: false,
			isCustomersDataLoading: false,
			customerLists: [],
			page: 1,
			showDeleteModal: false,
			selectedCustomerData: {},
			selectedCustomerInformation: {},
			selectedDataToDelete: {},
			isEditMode: false,
			searchText: '',
			selectedCustomers: [],
			showFormDialog: false,
			showSnackbar: false,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			},
			currentPage: 1,
			pageLimit: 25
		};
	},

	created() {
		this.fetchCustomersData();
	},

	watch: {
		searchText: debounce(function() {
			this.fetchCustomersData();
		}, 300)
	},

	computed: {
		// ...mapState('accounting', ['customerLists']),
		tableData: {
			get() {
				return this.customerLists?.data || [];
			}
		},

		tableHeader() {
			return [
				{
					text: 'Name',
					value: 'name',
					class: 'text-uppercase th--text font-weight-bold',
					width: '20%',
					align: 'start'
				},
				{
					text: 'Email',
					value: 'email',
					class: 'text-uppercase th--text font-weight-bold',
					width: '15%',
					align: 'start'
				},
				{
					text: 'Phone',
					value: 'phone',
					class: 'text-uppercase th--text font-weight-bold',
					width: '18%',
					align: 'start'
				},
				{
					text: 'Address',
					value: 'address',
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
				// {
				//   text: ('open_balance'),
				//   value: "enabled",
				//   class: "text-uppercase th--text font-weight-bold"
				// },
				{ text: '', value: 'actions', sortable: false, width: '15%', align: 'end' }
			];
		},

		pagination: {
			get() {
				return this.customerLists.data ? this.customerLists.meta : {};
			}
		},

		currencyValue: {
			get() {
				return this.selectedCustomerInformation?.currency_values
					? JSON.parse(this.selectedCustomerInformation.currency_values).name
					: '';
			}
		},

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		}
	},

	methods: {
		...mapActions('accounting', ['getCustomersData']),
		...globalMethods,
		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchCustomersData();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchCustomersData();
		},

		async fetchCustomersData() {
			if (this.isCustomersDataLoading) {
				return;
			}
			this.isCustomersDataLoading = true;
			try {
				const data = await this.getCustomersData({
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || ''
				});
				this.customerLists = data;
				this.isCustomersDataLoading = false;
			} catch (error) {
				this.isCustomersDataLoading = false;
				apiErrorMessage(error);
			}
		},

		onToggleCustomerForm(options = {}) {
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
				this.fetchCustomersData();
			}
		},

		onSelectEditCustomerData(data = {}) {
			this.selectedCustomerData = JSON.parse(JSON.stringify(data));
			this.isEditMode = true;
			this.showFormDialog = true;
		},

		onSelectCustomerToDelete(data = {}) {
			this.selectedDataToDelete = JSON.parse(JSON.stringify(data));
			this.onToggleDeleteCustomerForm();
		},

		onToggleDeleteCustomerForm(options = {}) {
			this.showDeleteModal = !this.showDeleteModal;

			if (options.deleted) {
				// this.snackbarOption = {
				// 	icon: 'mdi-delete',
				// 	color: 'red',
				// 	message: options.message || 'Deleted.'
				// };

				const message = options.message || 'Customer has been deleted.'
				this.notificationCustom(message)
				this.showSnackbar = true;
				this.showInfoModal = false;

				this.fetchCustomersData();
			}
		},

		onSelectCustomerInformation(data) {
			this.selectedCustomerInformation = data;
			this.onToggleCustomerInformationModal();
		},

		onToggleCustomerInformationModal() {
			this.showInfoModal = !this.showInfoModal;
			if (!this.showInfoModal) {
				this.selectedCustomerInformation = {};
			}
		}
	}
};
</script>

<style lang="scss">
@import './scss/customers.scss';
</style>

<style lang="scss" scoped>
@media screen and (max-width: 600px) {
	::v-deep .v-data-table > .v-data-table__wrapper {
		height: calc(100vh - 268px) !important;
		overflow-y: auto;
		overflow-x: hidden;
	}
}
::v-deep .v-data-table {
	.v-data-table__wrapper {
		height: calc(100vh - 236px);
		overflow-y: auto;
		overflow-x: hidden;
	}

	&.no-current__pagination,
	&.no-data__table {
		.v-data-table__wrapper {
			height: calc(100vh - 180px);
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
</style>
