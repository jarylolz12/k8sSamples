<template>
	<v-sheet>
		<v-data-table
			v-model="selectedPayments"
			v-bind:class="{
					'no-data__table' : (tableData.length === 0),
					'no-current__pagination' : (pagination && pagination.last_page <= 1),
				}"
			fixed-header
			show-select
			disable-pagination
			:single-select="false"
			:headers="paymentTableHeader"
			:items="tableData"
			:loading="isPaymentsDataLoading"
			:hide-default-footer="true"
		>
			<template v-slot:top>
				<div class="d-md-flex justify-end w-100 pt-3 px-4 pb-3 border-bottom">
					<div class="d-md-flex justify-md-end my-2 my-md-0 mx-md-0">
						<div class="d-flex">
							<div class="search-field-wrapper">
								<Search :inputData.sync="searchText" placeholder="Search Payments" />
							</div>
						</div>
						<div class="d-md-block d-none">
							<v-btn
								class="text-capitalize ml-md-2"
								color="btn-blue"
								@click="onTogglePaymentForm"
								height="38"
							>
								Add Payment
							</v-btn>
						</div>
						<div class="d-sm-block d-md-none mt-2">
							<v-btn
								class="text-capitalize ml-md-2 d-block"
								width="100%"
								color="btn-blue"
								@click="onTogglePaymentForm"
								height="38"
							>
								Add Payment
							</v-btn>
						</div>
					</div>
				</div>
			</template>
			<template v-slot:[`item.paid_at`]="{ item }">
				{{ formatDate(item.paid_at) }}
			</template>
			<template v-slot:[`item.actions`]="{ item }">
				<v-menu offset-y bottom left content-class="outbound-lists-menu">
					<template v-slot:activator="{ on, attrs }">
						<v-btn v-bind="attrs" primary v-on="on" class="btn-more-items btn-white" text outlined small>
							<v-icon>mdi-dots-horizontal</v-icon>
						</v-btn>
					</template>
					<v-list>
						<v-list-item link @click="onSelectEditPaymentData(item)">
							<v-list-item-title>Edit</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectPaymentInformation(item)">
							<v-list-item-title>View</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectPaymentToDisable(item)">
							<v-list-item-title class="text-danger">Delete</v-list-item-title>
						</v-list-item>
					</v-list>
				</v-menu>
			</template>
			<template v-slot:no-data>
				<div class="mt-5" v-if="isPaymentsDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>

				<v-alert class="mt-2" v-if="!isPaymentsDataLoading && tableData.length === 0 && searchText === ''">
					<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">Add Payment</p>
					<span class="subtitle-p-data">You have not added any payments yet.</span>
					<br />
					<v-btn
						outlined
						text
						small
						@click="onTogglePaymentForm"
						color="#0889a0"
						class="py-4 text-capitalize btn-white"
					>
						<v-icon size="18">mdi-plus</v-icon>
						Add Payment
					</v-btn>
				</v-alert>

				<v-alert class="mt-2" v-if="!isPaymentsDataLoading && tableData.length === 0 && searchText !== ''">
					<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">No Payments</p>
					<span class="subtitle-p-data">Sorry! We could not find any payments that matches your search term.</span>
				</v-alert>
			</template>
			<template v-slot:loading>
				<div class="mt-5" v-if="isPaymentsDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>
			</template>
		</v-data-table>
		<v-divider />
		<pagination
			:total="pagination.last_page ? pagination.last_page : 1"
			:count="pagination ? pagination.total : 0"
			:current-page="currentPage"
			:total-visible="10"
			:pageLimit.sync="pageLimit"
			@pageSelected="onPaginationClick"
			@changeLimit="onChangePageLimit"
			:from="pagination ? pagination.from : 1"
			:to="pagination ? pagination.to : 1"
		/>
		<v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar>
		<invoice-payment-form
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedPaymentData"
			@toggle="onTogglePaymentForm"
		></invoice-payment-form>
	</v-sheet>
</template>

<script>
	import { mapActions } from 'vuex';
	import moment from 'moment';
	import Search from './SearchInput.vue';
	import Pagination from './Pagination.vue';
	import { apiErrorMessage, debounce } from '../../utils/globalMethods';
	import InvoicePaymentForm from './InvoicePaymentForm.vue';

	export default {
		name: 'AccountingInvoicePayments',
		components: {
			Search,
			Pagination,
			InvoicePaymentForm
		},
		data() {
			return {
				page: 1,
				currentPage: 1,
				showDisableModal: false,
				selectedPaymentData: {},
				selectedDataToDisable: {},
				isEditMode: false,
				searchText: '',
				selectedPayments: [],
				showFormDialog: false,
				showSnackbar: false,
				snackbarOption: {
					icon: '',
					message: '',
					color: ''
				},
				paymentsData: [],
				// pageLimit: 25,
				pageLimit: 35,
				isPaymentsDataLoading: false,
				selectedPaymentInformation: {},
				showInfoModal: false,
				isEnabled: 'Active',
				showFilter: false,
			};
		},
		computed: {
			paymentTableHeader() {
				return [
					{
						text: 'Date',
						value: 'paid_at',
						class: 'text-uppercase th--text font-weight-bold',
						width: '25%',
						align: 'start'
					},
					{
						text: 'Amount',
						value: 'amount_formatted',
						class: 'text-uppercase th--text font-weight-bold',
						width: '15%',
						align: 'start'
					},
					{
						text: 'Customer',
						value: 'contact.name',
						class: 'text-uppercase th--text font-weight-bold',
						width: '25%',
						align: 'start'
					},
					{
						text: 'Category',
						value: 'category.name',
						class: 'text-uppercase th--text font-weight-bold',
						width: '25%',
						align: 'start'
					},
					{ text: '', value: 'actions', sortable: false, width: '10%', }
				];
			},
			tableData() {
				return this.paymentsData?.data || [];
			},
			pagination: {
				get() {
					return this.paymentsData ? this.paymentsData.meta : {};
				}
			}
		},

		watch: {
			searchText: debounce(function() {
				this.fetchData();
			}, 300)
		},

		mounted() {
			this.fetchData();
		},
		methods: {
			...mapActions('accounting', ['getInvoicePaymentData']),
			async fetchData() {
				if (this.isPaymentsDataLoading) {
					return;
				}
				this.showFilter = false;
				this.isPaymentsDataLoading = true;
				try {
					const data = await this.getInvoicePaymentData({
						page: this.currentPage,
						limit: this.pageLimit,
						search: this.searchText || '',
						enabled: this.isEnabled === 'Active' ? 1 : 0
					});
					this.paymentsData = data;
					this.isPaymentsDataLoading = false;
				} catch (error) {
					this.isPaymentsDataLoading = false;
					apiErrorMessage(error);
				}
			},

			onPaginationClick(pageNumber) {
				this.currentPage = pageNumber;
				// this.fetchPaymentsData();
			},

			onChangePageLimit() {
				this.currentPage = 1;
				// this.fetchPaymentsData();
			},

			onTogglePaymentForm(options = {}) {
				this.isEditMode = false;
				this.selectedPaymentData = null;
				this.showFormDialog = !this.showFormDialog;
				if (options.created || options.updated) {
					this.snackbarOption = {
						icon: 'mdi-check',
						color: 'success',
						message: options.message
					};
					this.showInfoModal = false;
					this.selectedPaymentInformation = {};
					this.showSnackbar = true;
					this.fetchData();
				}
			},

			onSelectEditPaymentData(data = null) {
				this.selectedPaymentData = data;
				this.isEditMode = true;
				this.showFormDialog = true;
			},

			onSelectPaymentInformation() {},

			onSelectPaymentToDisable() {},

			formatDate(date) {
				return moment(date).format('DD MMM YYYY');
			}
		}
	};
</script>

<style lang="scss" scoped>
	@media screen and (max-width: 600px) {
		::v-deep .v-data-table > .v-data-table__wrapper {
			height: calc(100vh - 354px) !important;
			overflow-y: auto;
			overflow-x: hidden;
		}
	}
	::v-deep .v-data-table {
		.v-data-table__wrapper {
			height: calc(100vh - 342px);
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
	.v-list-item {
		min-height: 36px;
	}
	.border-bottom {
		border-bottom: thin solid #EBF2F5;
	}
</style>
