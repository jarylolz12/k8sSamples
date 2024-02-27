<template>
	<v-sheet>
		<v-data-table
			v-model="selectedInvoices"
			v-bind:class="{
				'no-data__table' : (tableData.length === 0),
				'no-current__pagination' : (pagination && pagination.last_page <= 1),
			}"
			fixed-header
			show-select
			disable-pagination
			:single-select="false"
			:headers="tableHeaders"
			:items="tableData"
			:hide-default-footer="true">
			
			<template v-slot:top>
				<div class="d-md-flex justify-end w-100 pt-3 px-4 pb-3" v-if="tableData.length > 0" 
					style="border-bottom: 1px solid #EBF2F5 !important;">
					<div class="d-md-flex justify-md-end my-2 my-md-0 mx-md-0">
						<div class="d-flex">
							<div class="search-field-wrapper">
								<Search :inputData.sync="searchText" placeholder="Search Invoice #" />
							</div>
						</div>
					</div>
				</div>
			</template>

			<template v-slot:[`item.issued_at`]="{ item }">
				{{ formatDate(item.issued_at) }}
			</template>

			<template v-slot:[`item.due_at`]="{ item }">
				{{ formatDate(item.due_at) }}
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<v-menu offset-y bottom left content-class="outbound-lists-menu">
					<template v-slot:activator="{ on, attrs }">
						<v-btn v-bind="attrs" primary v-on="on" class="btn-more-items btn-white" text outlined small>
							<v-icon>mdi-dots-horizontal</v-icon>
						</v-btn>
					</template>
					<v-list>
						<v-list-item link @click.prevent="$router.push(`/accounting/invoices/${item.id}`)">
							<v-list-item-title>View</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="onSelectEditInvoice(item)">
							<v-list-item-title>Edit</v-list-item-title>
						</v-list-item>
						<!-- <v-list-item link @click="onSelectPaymentToDisable(item)">
							<v-list-item-title class="text-danger">Delete</v-list-item-title>
						</v-list-item> -->
					</v-list>
				</v-menu>
			</template>

			<template v-slot:[`item.status`]="{ item }">
				<span
					:class="{
						'status-paid': item.status === 'paid',
						'status-overdue': item.status === 'overdue',
						'status-partial': item.status === 'partial',
						'status-open': item.status === 'open',
						'status-draft': item.status === 'draft',
						'status-cancelled': item.status === 'cancelled',
						'status-sent': item.status === 'sent'
					}"
					class="text-uppercase"
					>{{ item.status }}</span
				>
				<!-- <p v-if="item.status === 'paid'" class="grey--text">{{ 'on' }} {{ dateMDYFormat(item.paidDate) }}</p>
				<p v-if="item.partialAmount" class="grey--text">
					{{ item.status === 'partially paid' ? 'paid' : 'Partially Paid' + ' ' }} ${{
						currencyDollarFormat(item.partialAmount)
					}}
				</p> -->
			</template>

			<template v-slot:no-data>
                <div class="loading-wrapper pt-6" v-if="isInvoicesLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper pt-6" style="min-height: 200px;" v-if="!isInvoicesLoading && tableData.length == 0">
					<h3 class="font-20 mb-1"> No Invoices found. </h3>
					<p> There are no invoices listed yet. </p>
                </div>
            </template>
		</v-data-table>

		<!-- <v-divider v-if="tableData.length > 0" /> -->

		<pagination
			:total="pagination ? pagination.last_page : 1"
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
		<invoice-form
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedInvoiceData"
			@toggle="onToggleInvoiceForm"
		></invoice-form>
	</v-sheet>
</template>

<script>
	import moment from 'moment';
	import Search from './SearchInput.vue';
	import Pagination from './Pagination.vue';
	import { apiErrorMessage, debounce } from '../../utils/globalMethods';
	import AkauntingService from '../../services/akaunting.service';
	import InvoiceForm from './InvoiceForm.vue';

	export default {
		name: 'CustomerInvoicesTable',
		components: {
			Search,
			Pagination,
			InvoiceForm,
		},
		props: ['contactId'],
		data() {
			return {
				page: 1,
				currentPage: 1,
				showDisableModal: false,
				selectedPaymentData: {},
				selectedDataToDisable: {},
				isEditMode: false,
				searchText: '',
				selectedInvoices	: [],
				showFormDialog: false,
				showSnackbar: false,
				snackbarOption: {
					icon: '',
					message: '',
					color: ''
				},
				invoicesData: [],
				selectedInvoiceData: {},
				pageLimit: 35,
				isInvoicesLoading: false,
				showInfoModal: false,
				isEnabled: 'Active',
				showFilter: false,
			};
		},
		computed: {
            tableHeaders() {
				return [
					{
						text: 'Invoice No.',
						value: 'document_number',
						class: 'text-uppercase th--text',
						width: '10%',
						align: 'start'
					},
					{
						text: 'Invoice Date',
						value: 'issued_at',
						class: 'text-uppercase th--text',
						width: '12%',
						align: 'start'
					},
					{
						text: 'Due Date',
						value: 'due_at',
						class: 'text-uppercase th--text font-weight-bold',
						width: '12%',
						align: 'start'
					},
					{
						text: 'Amount',
						value: 'amount_formatted',
						class: 'text-uppercase th--text font-weight-bold',
						width: '15%',
						align: 'end'
					},
					{
						text: 'Status',
						value: 'status',
						class: 'text-uppercase th--text font-weight-bold',
						width: '15%',
						align: 'center',
						sortable: false
					},
					{
						text: 'Balance',
						value: 'open_balance_formatted',
						class: 'text-uppercase th--text font-weight-bold',
						width: '15%',
						align: 'center',
						sortable: false
					},
					{
						text: '',
						align: 'center',
						value: 'actions',
						class: 'text-uppercase th--text font-weight-bold',
						sortable: false
					}
				];
			},

			tableData() {
				return this.invoicesData?.data || [];
			},

			pagination: {
				get() {
					return this.invoicesData ? this.invoicesData.meta : {};
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
			async fetchData() {
				if (this.isInvoicesLoading) {
					return;
				}
				this.showFilter = false;
				this.isInvoicesLoading = true;
				try {
					const data = await AkauntingService.getCustomerInvoices(this.contactId,{
						page: this.currentPage,
						limit: this.pageLimit,
						search: this.searchText || '',
						enabled: this.isEnabled === 'Active' ? 1 : 0
					});
					this.invoicesData = data.data;
					this.isInvoicesLoading = false;
				} catch (error) {
					this.isInvoicesLoading = false;
					apiErrorMessage(error);
				}
			},

			onPaginationClick(pageNumber) {
				this.currentPage = pageNumber;
				this.fetchData();
			},

			onChangePageLimit() {
				this.currentPage = 1;
				this.fetchData();
			},

			onSelectEditInvoice(invoice){
				this.isEditMode = true;
				this.selectedInvoiceData = JSON.parse(JSON.stringify(invoice));
				this.showFormDialog = true;
			},

			onToggleInvoiceForm(options = {}) {
				this.showFormDialog = !this.showFormDialog;
				this.isEditMode = true;
				if (options.created || options.updated) {
					this.snackbarOption = {
						icon: 'mdi-check',
						color: 'success',
						message: options.message
					};
					this.showSnackbar = true;
					this.fetchData();
				}
			},

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
			// height: calc(100vh - 342px);
			overflow-y: auto;
			overflow-x: hidden;
		}

		&.no-current__pagination,
		&.no-data__table {
			.v-data-table__wrapper {
				// height: calc(100vh - 285px);
				table {
					tbody {
						tr {
							&.v-data-table__empty-wrapper {
								td {
									border-bottom: none;
								}
							}
						}
					}
				}
			}
		}
	}
	.v-list-item {
		min-height: 36px;
	}
	.border-bottom {
		border-bottom: 1px solid #EBF2F5 !important;
	}
	.status-paid {
		color: #49af41;
	}

	.status-overdue {
		color: #f93131;
	}

	.status-partial {
		color: #2dc48d;
	}

	.status-open {
		color: #0171a1;
	}

	.status-draft {
		color: #81a0b4;
	}
	.status-cancelled {
		color: #000000;
	}

	.status-sent {
		color: red;
	}

	.font-20 {
		font-size: 20px;
	}
</style>
