<!-- @format -->

<template>
	<div class="billing-table-wrapper">
		<v-data-table
			:headers="headers"
			:items="billings"
			class="billing-table elevation-1"
			:class="billings !== null && billings.length > 0 ? '' : 'no-data-table'"
			hide-default-footer
			sort-by="due_date"
			:page.sync="page"
			:items-per-page="itemsPerPage"
			:search="search"
			@page-count="pageCount = $event"
			mobile-breakpoint="769"
			:custom-sort="customSort"
			show-select
			@input="checkboxValue"
			v-model="selected"
			color="primary"
		>
			<template v-slot:top>
				<v-toolbar flat>
					<v-toolbar-title>Bills</v-toolbar-title>

					<v-spacer></v-spacer>

					<div class="search-component">
						<Search
							placeholder="Search Bills"
							className="search custom-search"
							:inputData.sync="search"
						/>
					</div>

					<v-btn
						color="primary"
						class="btn-white manage-payment-methods-button"
						@click="handleManagePayment"
					>
						<img
							src="../../../assets/icons/payment-icon.svg"
							width="16px"
							height="16px"
							alt=""
							class="mr-1"
						/>
						<span class="btn-name-manage">Manage Payment Methods</span>
					</v-btn>

					<v-btn
						color="primary"
						class="btn-white manage-payment-methods-button"
						@click="openPaymentHistory"
					>
						<span class="btn-name-manage">Payment History</span>
					</v-btn>

					<!-- <v-dialog v-model="dialog" max-width="500px">
						<template v-slot:activator="{ on, attrs }">
							<v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
								New Item
							</v-btn>
						</template>
						<v-card>
							<v-card-title>
								<span class="text-h5">{{ formTitle }}</span>
							</v-card-title>

							<v-card-text>
								<v-container>
									<v-row>
										<v-col cols="12" sm="6" md="4">
											<v-text-field
												v-model="editedItem.name"
												label="Dessert name"
											></v-text-field>
										</v-col>
										<v-col cols="12" sm="6" md="4">
											<v-text-field
												v-model="editedItem.calories"
												label="Calories"
											></v-text-field>
										</v-col>
										<v-col cols="12" sm="6" md="4">
											<v-text-field
												v-model="editedItem.fat"
												label="Fat (g)"
											></v-text-field>
										</v-col>
										<v-col cols="12" sm="6" md="4">
											<v-text-field
												v-model="editedItem.carbs"
												label="Carbs (g)"
											></v-text-field>
										</v-col>
										<v-col cols="12" sm="6" md="4">
											<v-text-field
												v-model="editedItem.protein"
												label="Protein (g)"
											></v-text-field>
										</v-col>
									</v-row>
								</v-container>
							</v-card-text>

							<v-card-actions>
								<v-spacer></v-spacer>
								<v-btn color="blue darken-1" text @click="close">
									Cancel
								</v-btn>
								<v-btn color="blue darken-1" text @click="save">
									Save
								</v-btn>
							</v-card-actions>
						</v-card>
					</v-dialog>

					<v-dialog v-model="dialogDelete" max-width="500px">
						<v-card>
							<v-card-title class="text-h5"
								>Are you sure you want to delete this
								item?</v-card-title
							>
							<v-card-actions>
								<v-spacer></v-spacer>
								<v-btn
									color="blue darken-1"
									text
									@click="closeDelete"
									>Cancel</v-btn
								>
								<v-btn
									color="blue darken-1"
									text
									@click="deleteItemConfirm"
									>OK</v-btn
								>
								<v-spacer></v-spacer>
							</v-card-actions>
						</v-card>
					</v-dialog> -->
				</v-toolbar>

				<v-tabs v-if="invoiceIds && invoiceIds.length > 0" class="billing-tabs">
					<div class="manage-action">
						<div class="d-flex">
							<span class="ml-5 text--secondary"
								>{{ invoiceIds.length }} Items selected.</span
							>
							<a @click="unCheckAll" class="d-flex text-decoration-none ml-3">
								<span>Clear Selection</span>
							</a>
						</div>
						<div class="d-flex justify-center justify-space-between">
							<v-btn
								color="error"
								dark
								class="btn-blue mr-3"
								text
								@click="openPaymentDialog(selected)"
								:disabled="selectedIvoicesAmount <= 0"
								v-if="userCanMakePayment"
								>Make Payment
							</v-btn>
							<v-btn class="btn-white payment mr-5" @click="download"
								>Download ({{ invoiceIds.length }})
							</v-btn>
						</div>
					</div>
				</v-tabs>

				<v-tabs
					class="billing-tabs"
					@change="onTabChange"
					v-model="activeTab"
					v-else
				>
					<v-tab v-for="(n, i) in tabs" :key="i" @click="getCurrentTab(i)">
						{{ n }}
					</v-tab>
				</v-tabs>

				<v-overlay
					v-if="getInvoiceDownloadLoadingStatus"
					class="align-end mb-10"
				>
					<v-card width="250px" color="#4A4A4A">
						<v-card-text class="text-center d-flex justify-center">
							<img
								src="../../../assets/images/download-white.svg"
								alt=""
								class="mr-3"
							/>
							<span>Downloading invoices...</span>
						</v-card-text>
					</v-card>
				</v-overlay>
			</template>

			<template v-slot:[`item.balance`]="{ item }">
				<div>
					<p class="mb-0">{{ currencyNumberFormat(item.balance) }}</p>
				</div>
			</template>

			<template v-slot:[`item.amount`]="{ item }">
				<div>
					<p class="mb-0">{{ currencyNumberFormat(item.amount) }}</p>
					<small v-if="item.paid">{{
						item.paid_on ? `Paid on ${item.paid_on}` : ""
					}}</small>
				</div>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="manage-action-buttons">
					<v-btn
						class="btn-white payment billing-action-second-row-btn"
						@click="openPaymentDialog(item)"
						v-if="!item.paid && userCanMakePayment"
					>
						<span>Make Payment</span>
					</v-btn>

					<v-btn
						class="btn-white payment billing-action-second-row-btn"
						:class="'paid'"
						:disabled="item.paid"
						v-if="item.paid"
					>
						<span>
							<img
								src="../../../assets/icons/checkMark.png"
								class="mr-1"
								width="15px"
								height="15px"
								alt=""
							/>
							Paid
						</span>
					</v-btn>

					<v-btn
						class="btn-white icons billing-action-second-row-btn"
						@click="view(item)"
					>
						<img src="../../../assets/icons/visibility.svg" alt="" />
					</v-btn>

					<v-btn
						class="btn-white icons billing-action-second-row-btn"
						@click="download(item)"
					>
						<img src="../../../assets/icons/download.svg" alt="" />
					</v-btn>

					<div class="billing-actions">
						<v-menu bottom offset-y left content-class="po-lists-menu">
							<template v-slot:activator="{ on, attrs }">
								<v-btn color="btn-more elevation-0" v-bind="attrs" v-on="on">
									<img src="@/assets/icons/dots.svg" />
								</v-btn>
							</template>

							<v-list class="po-lists">
								<v-list-item
									:disabled="item.paid"
									@click="openPaymentDialog(item)"
								>
									<v-list-item-title>
										<div class="title-img">
											<img
												class="mr-2"
												src="../../../assets/icons/checkMark.png"
												width="16px"
												height="16px"
												v-if="item.paid"
											/>
											<img
												class="mr-2"
												src="../../../assets/icons/payment-icon.svg"
												width="16px"
												height="16px"
												v-if="!item.paid"
											/>
										</div>
										<span v-if="item.paid" class="paid">Paid</span>
										<span v-if="!item.paid">Make Payment</span>
									</v-list-item-title>
								</v-list-item>
								<v-list-item @click="view(item)">
									<v-list-item-title>
										<div class="title-img">
											<img
												class="mr-2"
												src="../../../assets/icons/visibility.svg"
												width="16px"
												height="16px"
											/>
										</div>
										<span>View</span>
									</v-list-item-title>
								</v-list-item>
								<v-list-item @click="download(item)">
									<v-list-item-title>
										<div class="title-img">
											<img
												class="mr-2"
												src="../../../assets/icons/download.svg"
												width="16px"
												height="16px"
											/>
										</div>
										<span>Download</span>
									</v-list-item-title>
								</v-list-item>
							</v-list>
						</v-menu>
					</div>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper mt-4" v-if="getInvoiceLoadingStatus">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>

				<div
					class="no-data-wrapper"
					v-if="billings.length == 0 && !getInvoiceLoadingStatus"
				>
					<div class="no-data-heading" v-if="activeTab == 0">
						<img
							src="../../../assets/icons/document.svg"
							width="40px"
							height="42px"
							alt=""
						/>

						<h3>No Unpaid Bills</h3>
						<p>
							Amazing! You have cleared all unpaid Bills.
						</p>
					</div>

					<div class="no-data-heading" v-if="activeTab == 1">
						<img
							src="../../../assets/icons/document.svg"
							width="40px"
							height="42px"
							alt=""
						/>

						<h3>No Paid Bills</h3>
						<p>
							You haven't paid for any bills yet.
						</p>
					</div>
					<div class="no-data-heading" v-if="activeTab == 2">
						<img
							src="../../../assets/icons/document.svg"
							width="40px"
							height="42px"
							alt=""
						/>
						<h3>No Invoice</h3>
						<p>
							No invoice has been issued yet.
						</p>
					</div>
				</div>
			</template>
		</v-data-table>

		<Pagination
			v-if="typeof billings !== 'undefined' && billings.length > 0"
			:pageData.sync="page"
			:lengthData.sync="pageCount"
			:isMobile="isMobile"
		/>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Search from "../../Search.vue";
import Pagination from "../../Pagination.vue";
import _ from "lodash";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "BillingDesktopTable",
	props: ["items", "isMobile", "userCanMakePayment"],
	components: {
		Search,
		Pagination,
	},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "billing");
		this.mounted = true;
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		search: "",
		headers: [
			{
				text: "Invoice #",
				align: "start",
				sortable: false,
				value: "invoice_no",
				width: "15%",
				fixed: true,
			},
			{
				text: "Invoice Date",
				align: "start",
				sortable: false,
				value: "invoice_date",
				width: "15%",
				fixed: true,
			},
			{
				text: "Shipment Reference",
				align: "start",
				sortable: false,
				value: "shipment_reference",
				width: "20%",
				fixed: true,
			},
			{
				text: "Due Date",
				align: "start",
				sortable: true,
				value: "due_date",
				width: "10%",
				fixed: true,
			},
			{
				text: "Balance Due",
				align: "end",
				sortable: false,
				value: "balance",
				width: "13%",
				fixed: true,
			},
			{
				text: "Amount",
				align: "end",
				sortable: false,
				value: "amount",
				width: "10%",
				fixed: true,
			},
			{
				text: "",
				align: "end",
				sortable: false,
				value: "actions",
				width: "17%",
				fixed: true,
			},
		],
		tabs: ["Unpaid", "Paid", "All Bills"],
		activeTab: 0,
		isInputExpanded: false,
		searchData: "",
		invoiceIds: [],
		selected: [],
		selectedIvoicesAmount: 0,
	}),
	computed: {
		...mapGetters([
			"getInvoiceDownloadLoadingStatus",
			"getInvoiceLoadingStatus",
		]),
		formTitle() {
			return this.editedIndex === -1 ? "New Item" : "Edit Item";
		},
		billingsData: {
			get() {
				return this.items;
			},
			set(value) {
				this.$emit("update:items", value);
			},
		},
		billings() {
			let data = this.billingsData.filter((billing) =>
				billing.billing_status.includes(this.tabs[this.activeTab])
			);
			return data;
		},
	},
	watch: {
		dialog(val) {
			val || this.close();
		},
		dialogDelete(val) {
			val || this.closeDelete();
		},
		billings: {
			handler(newValue) {
				let action = "";
				if (this.$route.query && this.$route.query.action !== undefined) {
					action = this.$route.query.action;
				}
				if (this.mounted && action == "makePayment") {
					if (newValue.length > 0) {
						this.selected = newValue;
					}
				}
				this.mounted = false;
			},
			deep: true,
		},
	},
	methods: {
		...mapActions(["downloadInvoice"]),
		...globalMethods,
		unCheckAll() {
			this.invoiceIds = [];
			this.selected = [];
		},
		checkboxValue(value) {
			this.invoiceIds = value.map((item) => item.id);
			this.selectedIvoicesAmount = _.sumBy(value, (item) =>
				!item.paid ? parseFloat(item.total_amount) : 0
			);
		},
		getCurrentTab() {
			// console.log(id);
		},
		onTabChange() {
			this.page = 1;
		},
		openPaymentDialog(item) {
			let filteredItem = item;
			if (Array.isArray(item)) {
				filteredItem = item.filter((i) => !i.paid);
			}
			this.$emit("makePayment", filteredItem);
		},
		close() {
			this.$emit("close");
		},
		view(item) {
			this.$emit("viewPayment", item);
		},
		closeView() {
			this.$emit("closeView");
		},
		handleManagePayment() {
			this.$router.push(`settings/?tab=manage-payment-methods`);
			this.$store.dispatch("page/setCurrentSettingsTab", 1);
		},
		async download(item) {
			if (item?.id) {
				await this.downloadInvoice(item);
			} else if (Array.isArray(this.selected)) {
				this.selected.forEach(async (id) => {
					await this.downloadInvoice(id);
				});
			}
		},
		save() {
			if (this.editedIndex > -1) {
				Object.assign(this.desserts[this.editedIndex], this.editedItem);
			} else {
				this.desserts.push(this.editedItem);
			}
			this.close();
		},

		editItem(item) {
			this.editedIndex = this.desserts.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialog = true;
		},
		customSort(billings, index, isDesc) {
			if (index[0] === "due_date") {
				billings.sort((a, b) => {
					if (isDesc[0]) {
						return new Date(b.due_date) - new Date(a.due_date);
					} else {
						return new Date(a.due_date) - new Date(b.due_date);
					}
				});
			}
			return billings;
		},
		openPaymentHistory() {
			this.$emit("paymentHistoryShow");
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/billing/billingTable.scss";
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/buttons.scss";
</style>
