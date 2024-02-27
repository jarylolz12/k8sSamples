<template>
	<v-sheet outlined color="#EBF2F5" style="border-radius: 4px;">
		<!--  <v-toolbar outlined flat v-if="!isNoInvoiceCreated">
              <v-tabs v-model="selectedStatus" @change="onSelectselectedStatus">
                <v-tab class="text-capitalize">{{ ('all') }}</v-tab>
                <v-tab class="text-capitalize">{{ ('paid') }}</v-tab>
                <v-tab class="text-capitalize">{{ ('unpaid') }}</v-tab>
              </v-tabs>
            </v-toolbar> -->

		<v-data-table
			v-model="selectedData"
			class="v-table-middle-align"
			v-bind:class="{
                'no-data__table' : (invoicesDataList.length === 0),
                'no-current__pagination' : (pagination.last_page <= 1),
            }"
			fixed-header
			show-select
			disable-pagination
			:single-select="false"
			:headers="tableHeaders"
			:items="invoicesDataList"
			:hide-default-footer="true"
			:loading="isInvoiceDataLoading"
		>
			<template v-slot:top>
				<div class="d-block d-sm-flex pa-4 py-3">
					<div class="mr-auto">
						<h3 class="mb-0 pt-2">{{ 'Invoices' }}</h3>
					</div>
					<div class="d-sm-flex justify-end">
						<div class="d-inline d-sm-flex">
							<div class="mb-sm-0 mb-2">
								<v-menu offset-y bottom content-class="outbound-lists-menu">
									<template v-slot:activator="{ on, attrs }">
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
											<span>{{ 'Status' + (selectedStatus ? ': ' + selectedStatus : '') }}</span>
											<v-icon>mdi-menu-down</v-icon>
										</v-btn>
									</template>
									<v-card width="225">
										<v-card-text class="px-0 py-2">
											<v-list dense class="py-0">
												<v-list-item-group
													v-model="selectedStatus"
													color="primary"
													@change="fetchInvoices"
												>
													<v-list-item :value="''">
														<v-list-item-content>
															<v-list-item-title>NONE</v-list-item-title>
														</v-list-item-content>
													</v-list-item>
													<v-list-item
														v-for="status in invoiceStatusList"
														:key="status"
														:value="status"
													>
														<v-list-item-content>
															<v-list-item-title
																v-text="status"
																class="text-capitalize"
															></v-list-item-title>
														</v-list-item-content>
													</v-list-item>
												</v-list-item-group>
											</v-list>
										</v-card-text>
									</v-card>
								</v-menu>
							</div>
							<div class="mx-0 mx-sm-2">
								<Search :inputData.sync="searchText" placeholder="Search Invoice" />
							</div>
						</div>
						<div class="mt-2 mt-sm-0">
							<v-btn
								class="text-white btn-blue text-capitalize"
								height="40px"
								text
								@click="onToggleInvoiceForm"
								block
							>
								Create Invoice
							</v-btn>
						</div>
					</div>
				</div>
				<v-divider />
			</template>
			<template v-slot:[`item.issued_at`]="{ item }">
				{{ dateMDYFormat(item.issued_at) }}
			</template>
			<template v-slot:[`item.document_number`]="{ item }">
				<a href="#" style="text-decoration: none;" @click.prevent="$router.push(`/accounting/invoices/${item.id}`)">
					<span>{{ item.document_number }}</span>
				</a>
			</template>
			<template v-slot:[`item.customer`]="{ item }">
				<span>{{ JSON.parse(item.contact_ref).name }}</span>
			</template>
			<template v-slot:[`item.due_at`]="{ item }">
				{{ dateMDYFormat(item.due_at) }}
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
					class="text-capitalize font-medium"
					>{{ item.status }}</span
				>
				<p v-if="item.status === 'paid'" class="grey--text">{{ 'on' }} {{ dateMDYFormat(item.paidDate) }}</p>
				<p v-if="item.partialAmount" class="grey--text">
					{{ item.status === 'partially paid' ? 'paid' : 'Partially Paid' + ' ' }} ${{
						currencyDollarFormat(item.partialAmount)
					}}
				</p>
			</template>
			<template v-slot:[`item.actions`]="{ item }">
				<!-- <v-btn small class="blue--text mr-1" text outlined>
                  <v-icon>mdi-eye-outline</v-icon>
                </v-btn> -->
				<v-menu offset-y bttom left content-class="outbound-lists-menu">
					<template v-slot:activator="{ on, attrs }">
						<v-btn v-bind="attrs" primary v-on="on" color="primary" class="btn-white btn-more-items" text outlined small>
							<v-icon>mdi-dots-horizontal</v-icon>
						</v-btn>
					</template>
					<v-list>
						<v-list-item
							link
							v-if="['partially paid', 'partial'].includes(item.status)"
							@click="onTogglePaymentModal(item)"
						>
							<v-list-item-title class="app-link-color">{{ 'Receive Payment' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link @click="$router.push(`/accounting/invoices/${item.id}`)">
							<v-list-item-title>{{ 'View' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link v-if="item.status === 'draft'" @click="onToggleMarkAsSent(item.id)">
							<v-list-item-title>Mark as Sent</v-list-item-title>
						</v-list-item>
						<v-list-item link v-if="item.status !== 'paid'" @click="onEditForm(item)">
							<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
						</v-list-item>
						<v-list-item link v-if="item.status !== 'cancelled'" @click="onToggleMarkAsCancelled(item.id)">
							<v-list-item-title>Cancel</v-list-item-title>
						</v-list-item>
						<!-- <v-list-item link>
                      <v-list-item-title>{{ ('duplicate') }}</v-list-item-title>
                    </v-list-item> -->
						<!-- <v-list-item link v-if="item.status !== 'paid'">
                      <v-list-item-title>{{ ('send') }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item link v-if="item.status !== 'paid'">
                      <v-list-item-title>{{ ('send_reminder') }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item link>
                      <v-list-item-title>{{ ('print') }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item link>
                      <v-list-item-title>{{ ('download') }}</v-list-item-title>
                    </v-list-item> -->
						<v-list-item link @click="onToggleDeleteInvoice(item)">
							<v-list-item-title class="red--text">{{ 'Delete' }}</v-list-item-title>
						</v-list-item>
					</v-list>
				</v-menu>
			</template>
			<template v-slot:no-data>
				<div class="mt-5" v-if="isInvoiceDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>

				<v-alert class="mt-2" v-if="!isInvoiceDataLoading && invoicesDataList.length === 0 && searchText == ''">
					<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">Create Invoice</p>
					<span class="subtitle-p-data">You don't have any invoice yet.</span>
					<br />
					<v-btn
						outlined
						text
						small
						@click="onToggleInvoiceForm"
						color="#0889a0"
						class="py-4 text-capitalize btn-white"
					>
						{{ 'Create Invoice' }}
					</v-btn>
				</v-alert>

				<v-alert class="mt-2" v-if="!isInvoiceDataLoading && invoicesDataList.length === 0 && searchText !== ''">
					<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
					<p class="subtitle-2 no-data-title">No matching result</p>
					<span class="subtitle-p-data">Sorry! We could not find any invoice that matches your search term.</span>
					<br />
				</v-alert>
			</template>
			<template v-slot:no-results>
				<v-card width="300" flat class="mx-auto text-center my-16">
					<v-icon size="52" color="#0171a1">mdi-magnify</v-icon>
					<v-card-text>
						<span class="black--text text-h5">{{ 'No matching result' }}</span>
						<p class="font-weight-bold">
							{{ 'Sorry! We could not find any invoice that matches your search term.' }}
						</p>
						<v-btn text outlined color="#0171a1">
							<span class="text-capitalize">{{ 'Try different search' }}</span>
						</v-btn>
					</v-card-text>
				</v-card>
			</template>
			<template v-slot:loading>
				<div class="mt-5" v-if="isInvoiceDataLoading">
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
		<invoice-form
			:open="showFormDialog"
			:is-edit-mode="isEditMode"
			:form-values="selectedInvoiceData"
			@toggle="onToggleInvoiceForm"
		></invoice-form>
		<invoice-delete
			:open="showDeleteInvoiceModal"
			:form-data="formDataToDelete"
			@toggle="onToggleDeleteInvoice"
		></invoice-delete>
		<invoice-mark-sent
			:open="showMarkSentModal"
			:invoiceId="invoiceMarkAsSentId"
			@toggle="onToggleMarkAsSent"
		></invoice-mark-sent>
		<invoice-mark-cancelled
			:open="showMarkCancelledModal"
			:invoiceId="invoiceMarkAsCancelledId"
			@toggle="onToggleMarkAsCancelled"
		></invoice-mark-cancelled>
		<!-- <receive-payment-form :open="showPaymentModal" :invoice="selectedInvoiceData || {}" :is-edit-mode="false" @toggle="onTogglePaymentModal"></receive-payment-form> -->
		<v-dialog> </v-dialog>
	</v-sheet>
</template>

<script>
import InvoiceForm from '../../components/Accounting/InvoiceForm.vue';
import InvoiceDelete from '../../components/Accounting/InvoiceDeleteModal.vue';
import InvoiceMarkSent from '../../components/Accounting/InvoiceMarkSent.vue';
import InvoiceMarkCancelled from '../../components/Accounting/InvoiceMarkCancelled.vue';
// import ReceivePaymentForm from '../components/ReceivePaymentForm.vue';
import { apiErrorMessage, debounce } from '../../utils/globalMethods';
import moment from 'moment';
import Pagination from '../../components/Accounting/Pagination.vue';
import Search from '../../components/Accounting/SearchInput.vue';
import globalMethods from '../../utils/globalMethods'

import { mapActions } from 'vuex';

export default {
	name: 'Invoices',
	components: {
		InvoiceForm,
		InvoiceDelete,
		// ReceivePaymentForm,
		InvoiceMarkSent,
		InvoiceMarkCancelled,
		Pagination,
		Search
	},
	data() {
		return {
			selectedInvoiceData: null,
			isEditMode: false,
			showFormDialog: false,
			showDeleteInvoiceModal: false,
			showMarkSentModal: false,
			showMarkCancelledModal: false,
			showPaymentModal: false,
			formDataToDelete: {},
			selectedData: [],
			filterBy: null,
			searchText: '',
			selectedStatus: null,
			isNoInvoiceCreated: false, // Get the value from the server
			invoicesData: [],
			isInvoiceDataLoading: false,
			showSnackbar: false,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			},
			isFormEditMode: false,
			// pageLimit: 25,
			pageLimit: 35,
			currentPage: 1,
			invoiceStatusList: ['cancelled', 'draft', 'paid', 'partial', 'sent', 'viewed'],
			invoiceMarkAsSentId: null,
			invoiceMarkAsCancelledId: null
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
					text: 'Customer',
					value: 'customer',
					class: 'text-uppercase th--text font-weight-bold',
					width: '20%',
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
					text: '',
					align: 'center',
					value: 'actions',
					class: 'text-uppercase th--text font-weight-bold',
					sortable: false
				}
			];
		},

		invoicesDataList: {
			get() {
				return this.invoicesData?.data || [];
			}
		},

		pagination: {
			get() {
				return this.invoicesData.data ? this.invoicesData.meta : {};
			}
		}
	},

	created() {
		// this.tempInvoiceData = JSON.parse(JSON.stringify(this.invoicesData));
		this.fetchInvoices();
	},

	watch: {
		searchText: debounce(function() {
			this.fetchInvoices();
		}, 600)
	},

	methods: {
		//
		...mapActions('accounting', ['getInvoicesData']),
		...globalMethods,
		async fetchInvoices() {
			if (this.isInvoiceDataLoading) {
				return;
			}
			try {
				this.isInvoiceDataLoading = true;
				const filters = {
					limit: this.pageLimit,
					page: this.currentPage,
					search: this.searchText || '',
					status: this.selectedStatus || ''
				};
				const { data } = await this.getInvoicesData(filters);
				this.invoicesData = data || [];
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.isInvoiceDataLoading = false;
			}
		},

		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchInvoices();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchInvoices();
		},

		onEditForm(data) {
			this.isEditMode = true;
			this.selectedInvoiceData = JSON.parse(JSON.stringify(data));
			this.showFormDialog = true;
		},

		onToggleInvoiceForm(options = {}) {
			this.showFormDialog = !this.showFormDialog;
			this.isEditMode = false;
			if (options.created || options.updated) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'success',
				// 	message: options.message
				// };
				this.notificationMessage(options.message)
				this.showSnackbar = true;
				this.fetchInvoices();
			}
		},

		onTogglePaymentModal(data = null) {
			this.showPaymentModal = !this.showPaymentModal;
			if (!this.showPaymentModal) {
				this.selectedInvoiceData = {};
			} else {
				this.selectedInvoiceData = JSON.parse(JSON.stringify(data));
			}
		},

		onToggleDeleteInvoice(data = {}, options = {}) {
			this.formDataToDelete = JSON.parse(JSON.stringify(data));
			this.showDeleteInvoiceModal = !this.showDeleteInvoiceModal;
			if (options.deleted) {
				// this.snackbarOption = {
				// 	icon: 'mdi-delete',
				// 	color: 'red',
				// 	message: options.message || 'Deleted'
				// };
				const message = options.message || 'Invoice has been deleted.'
				this.notificationCustom(message)
				this.fetchInvoices();
				this.showSnackbar = true;
			}
		},

		currencyDollarFormat(number = 0) {
			return new Intl.NumberFormat('en-US').format(number);
		},

		dateMDYFormat(dateString) {
			return moment(dateString, 'YYYY-MM-DD').format('MMM DD, YYYY');
		},

		onToggleMarkAsSent(id = null, options = {}) {
			this.invoiceMarkAsSentId = id;
			this.showMarkSentModal = !this.showMarkSentModal;
			if (options && options.success) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'success',
				// 	message: options.message
				// };

				this.notificationMessage(options.message)
				this.showSnackbar = true;
				this.fetchInvoices();
			}
		},

		onToggleMarkAsCancelled(id = null, options = {}) {
			this.invoiceMarkAsCancelledId = id;
			this.showMarkCancelledModal = !this.showMarkCancelledModal;
			if (options && options.success) {
				// this.snackbarOption = {
				// 	icon: 'mdi-check',
				// 	color: 'success',
				// 	message: options.message
				// };

				this.notificationCustom(options.message)
				this.showSnackbar = true;
				this.fetchInvoices();
			}
		}
	}
};
</script>
<style lang="scss" scoped>
	$btn-active-color: #0171a1;
	$app-text-color: #0171a1;

	@media screen and (max-width: 600px) {
		::v-deep .v-data-table > .v-data-table__wrapper {
			height: calc(100vh - 316px) !important;
			overflow-y: auto;
			overflow-x: hidden;
		}
	}
	::v-deep .v-data-table {
		.v-data-table__wrapper {
			height: calc(100vh - 235px);
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

	.app-link-color {
		color: $app-text-color;
	}

	.btn-primary {
		background-color: $btn-active-color !important;
		color: #ffffff !important;
	}
	.status-paid {
		color: #16B442;
		font-size: 12px;
		padding: 6px 10px;
		background-color: #EBFAEF;
		border-radius: 99px;
	}

	.status-overdue {
		color: #F93131;
		background-color: #FFF2F2;
		font-size: 12px;
		border-radius: 99px;
		padding: 6px 10px;
	}

	.status-partial {
		color: #2dc48d;
		font-size: 12px;
		border-radius: 99px;
		padding: 6px 10px;
		background-color: #F5F9FC;
	}

	.status-open {
		color: #0171a1;
		background-color: #F0FBFF;
		font-size: 12px;
		border-radius: 99px;
		padding: 6px 10px;
	}

	.status-draft {
		// color: #81a0b4;
		color: #4a4a4a;
		padding: 6px 10px;
		background-color: #F5F9FC;
		border-radius: 99px;
		font-size: 12px;
	}
	.status-cancelled {
		// color: #000000;
		color: #F93131;
		background-color: #f7f7f7;
		font-size: 12px;
		border-radius: 99px;
		padding: 6px 10px;
	}

	.status-sent {
		// color: red;
		background-color: #F5F9FC;
		border-radius: 99px;
		padding: 6px 10px;
		font-size: 12px;
		color: #16B442;
	}
	.v-list-item {
		min-height: 36px;
	}
	.select-page-limit {
		width: 80px;
	}
</style>
<style lang="scss"></style>
