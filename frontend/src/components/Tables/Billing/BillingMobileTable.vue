<!-- @format -->

<template>
	<div class="billing-table-wrapper">
		<v-data-table
			:headers="headers"
			:items="billings"
			sort-by="calories"
			class="billing-table-mobile elevation-1"
			:class="billings !== null && billings.length > 0 ? '' : 'no-data-table'"
			hide-default-footer
			:page.sync="page"
			:items-per-page="itemsPerPage"
			:search="search"
			@page-count="pageCount = $event"
			mobile-breakpoint="769"
			:custom-sort="customSort"
		>
			<template v-slot:top>
				<v-toolbar flat>
					<v-toolbar-title v-if="!isButtonExpanded">Invoices</v-toolbar-title>

					<v-spacer></v-spacer>

					<button
						class="btn btn-white mr-2"
						@click="selectCheckbox"
						v-if="!isButtonExpanded"
					>
						Select
					</button>

					<div v-if="isButtonExpanded" class="d-flex">
						<v-btn
							color="error"
							dark
							class="btn-blue mr-3"
							text
							@click="openPaymentDialog(selected)"
							:disabled="selectedIvoicesAmount <= 0 || selected.length <= 0"
							v-if="userCanMakePayment"
							>Make Payment
						</v-btn>

						<v-btn
							class="btn-white mr-3"
							@click="download"
							:disabled="selected.length == 0"
						>
							<img src="../../../assets/icons/download.svg" alt="" /> ({{
								selected.length
							}})
						</v-btn>

						<v-btn class="btn-white" @click="clearCheckbox">
							Cancel
						</v-btn>
					</div>

					<div class="d-flex" v-if="!isButtonExpanded">
						<button class="btn btn-primary search-invoice" @click="inputClick">
							<img
								src="@/assets/images/search-icon.svg"
								alt=""
								width="15px"
								height="15px"
								class="mobile-search-icon"
							/>
						</button>

						<div class="search" :class="isInputExpanded ? 'expanded' : ''">
							<img
								src="@/assets/images/search-icon.svg"
								alt=""
								width="15px"
								height="15px"
								class="input-search-icon"
								:class="isInputExpanded ? 'expanded' : ''"
							/>

							<input
								class="search-asd"
								type="text"
								id="search-input"
								v-model.trim="search"
								placeholder="Search Invoices..."
								@input="handleSearch"
								autocomplete="off"
							/>
						</div>

						<button
							v-if="isInputExpanded"
							class="close-btn"
							@click="clearInput"
						>
							Cancel
						</button>

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
							/>
						</v-btn>

						<v-btn
							color="primary"
							class="btn-white manage-payment-methods-button"
							@click="openPaymentHistory"
						>
							<img
								src="../../../assets/icons/payment-history.svg"
								width="16px"
								height="16px"
								alt=""
							/>
						</v-btn>
					</div>
				</v-toolbar>

				<v-tabs class="billing-tabs" @change="onTabChange" v-model="activeTab">
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

			<template v-slot:[`item.invoice_no`]="{ item }">
				<div class="layout-flex">
					<div class="d-flex align-center">
						<v-checkbox
							v-model="selected"
							:value="item"
							:label="item.invoice_no"
							v-if="isButtonExpanded"
							class="darker mb-0 "
							@click="checkAmountLimit()"
						>
						</v-checkbox>
						<p class="darker mb-0" v-if="!isButtonExpanded">
							{{ item.invoice_no }}
						</p>
					</div>
					<p class="darker mb-0">
						{{ currencyNumberFormat(item.balance) }}
					</p>
				</div>
			</template>

			<template v-slot:[`item.invoice_date`]="{ item }">
				<div class="layout-inline">
					<p class="inline-title">Invoice Date</p>
					<p class="mb-0">{{ item.invoice_date }}</p>
				</div>
			</template>

			<template v-slot:[`item.shipment_reference`]="{ item }">
				<div class="layout-inline">
					<p class="inline-title">Reference</p>
					<p class="reference mb-0">{{ item.shipment_reference }}</p>
				</div>
			</template>

			<template v-slot:[`item.due_date`]="{ item }">
				<div class="layout-inline">
					<p class="inline-title">Due Date</p>
					<p class="mb-0">{{ item.due_date }}</p>
				</div>
			</template>

			<template v-slot:[`item.amount`]="{ item }">
				<div class="layout-inline">
					<p class="inline-title">Total amount</p>
					<p class="mb-0">{{ currencyNumberFormat(item.amount) }}</p>
				</div>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="manage-action-buttons">
					<v-btn
						class="btn-white payment"
						@click="openPaymentDialog(item)"
						v-if="!item.paid && userCanMakePayment"
					>
						<span>Make Payment</span>
					</v-btn>

					<v-btn
						class="btn-white payment"
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

					<v-btn class="btn-white icons" @click="view(item)">
						<img src="../../../assets/icons/visibility.svg" alt="" />
					</v-btn>

					<v-btn class="btn-white icons" @click="download(item)">
						<img src="../../../assets/icons/download.svg" alt="" />
					</v-btn>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper mt-4" v-if="getInvoiceLoadingStatus">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>

				<div
					class="no-data-wrapper pa-8"
					v-if="billings.length == 0 && !getInvoiceLoadingStatus"
				>
					<div class="no-data-heading" v-if="activeTab == 0">
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

					<div class="no-data-heading" v-if="activeTab == 1">
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

					<div class="no-data-heading" v-if="activeTab == 2">
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
import Pagination from "../../Pagination.vue";
import { mapActions, mapGetters } from "vuex";
import _ from "lodash";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "BillingMobileTable",
	props: ["items", "isMobile", "userCanMakePayment"],
	components: {
		Pagination,
	},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "billing");
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
		isButtonExpanded: false,
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
	},
	methods: {
		...mapActions(["downloadInvoice"]),
		...globalMethods,
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
		selectCheckbox() {
			this.isButtonExpanded = true;
		},
		clearCheckbox() {
			this.isButtonExpanded = false;
			this.selected = [];
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
		clearInput() {
			this.isInputExpanded = false;
			this.searchData = "";
		},
		inputClick() {
			this.isInputExpanded = true;
			document.getElementById("search-input").focus();
		},
		handleSearch() {},
		save() {
			if (this.editedIndex > -1) {
				Object.assign(this.desserts[this.editedIndex], this.editedItem);
			} else {
				this.desserts.push(this.editedItem);
			}
			this.close();
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
		checkAmountLimit() {
			this.selectedIvoicesAmount = _.sumBy(this.selected, (item) =>
				!item.paid ? parseFloat(item.total_amount) : 0
			);
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
