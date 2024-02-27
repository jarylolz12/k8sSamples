<!-- @format -->

<template>
	<div class="payment-history-wrapper">
		<v-overlay v-if="getPaginationLoader">
			<v-progress-circular :size="40" color="#0171a1" indeterminate>
			</v-progress-circular>
		</v-overlay>
		<div class="payment-history-title">
			<v-breadcrumbs :items="breadcrumbsItem">
				<template v-slot:divider>
					<v-icon>mdi-chevron-right</v-icon>
				</template>
			</v-breadcrumbs>

			<v-row>
				<v-col sm="12" md="12" class="d-flex justify-space-between">
					<div class="d-flex" v-if="!isInputExpanded && !isButtonExpanded">
						<v-btn
							@click="clickBack"
							class="mr-2 back-to-billing-btn"
							outlined
							color="#0171A1"
						>
							<v-icon>mdi-arrow-left</v-icon>
						</v-btn>
						<v-toolbar-title class="payment-history-label"
							>Payment History</v-toolbar-title
						>
					</div>
					<div class="d-flex justify-space-between payment-history-search-div">
						<button
							class="btn btn-primary search-invoice"
							v-if="!isInputExpanded && !isButtonExpanded"
							@click="inputClick"
						>
							<img
								src="@/assets/images/search-icon.svg"
								alt=""
								width="15px"
								height="15px"
								class="mobile-search-icon"
							/>
						</button>
						<button
							class="btn ml-2 payment-history-mobile-select-button"
							@click="selectCheckbox"
							v-if="!isInputExpanded && !isButtonExpanded"
						>
							Select
						</button>

						<div v-if="isButtonExpanded" class="d-flex">
							<v-btn
								class="btn-white mr-3"
								@click="downloadPaymentReceiptFun"
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

						<div
							class="search"
							v-if="isInputExpanded"
							:class="isInputExpanded ? 'expanded' : ''"
						>
							<div class="search-component float-right">
								<Search
									placeholder="Search Receipt #"
									className="search custom-search"
									:inputData.sync="search"
								/>
							</div>
						</div>

						<button
							v-if="isInputExpanded"
							class="close-btn ml-4"
							@click="clearInput"
						>
							Cancel
						</button>
					</div>
				</v-col>
			</v-row>
		</div>

		<v-data-table
			:headers="headers"
			:items="getPayments"
			sort-by="due_date"
			class="payment-history-table-mobile elevation-1"
			:class="
				getPayments !== null && getPayments.length > 0 ? '' : 'no-data-table'
			"
			hide-default-footer
			:page.sync="page"
			:items-per-page="itemsPerPage"
			:search="search"
			@page-count="pageCount = $event"
			mobile-breakpoint="769"
		>
			<template v-slot:[`item.receipt_no`]="{ item }">
				<div class="layout-flex">
					<div class="d-flex align-center">
						<v-checkbox
							v-model="selected"
							:value="item"
							:label="item.receipt_no"
							v-if="isButtonExpanded"
							class="darker mb-0"
						>
						</v-checkbox>
						<p class="darker mb-0" v-if="!isButtonExpanded">
							{{ item.receipt_no }}
						</p>
					</div>
				</div>

				<div class="layout-inline">
					<p class="inline-title">Date & Time:</p>
					<p class="mb-0">
						{{ item.date_time }}
						<label class="time-dark">{{ item.transactionTime }}</label>
					</p>
				</div>
				<div class="card-number-wrapper" v-if="item.card_number">
					<div class="card-type">
						<img
							v-if="item.card_icon"
							:src="require(`@/assets/icons/${item.card_icon}`)"
							width="40px"
							height="42px"
							alt=""
						/>
					</div>
					<div>
						<p class="mb-0">{{ item.card_number }}</p>
						<p
							class="name mb-0"
							v-if="item.card_holder_name != '-' && item.card_exp != '-'"
						>
							{{ item.card_holder_name }} {{ item.card_exp }}
						</p>
					</div>
				</div>
				<div class="layout-inline">
					<p class="inline-title">Invoice Reference:</p>
					<p class="mb-0">{{ item.invoice_reference.join(", ") }}</p>
				</div>
				<div class="layout-inline">
					<p class="inline-title">Amount:</p>
					<p class="mb-0">{{ currencyNumberFormat(item.amount) }}</p>
				</div>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="manage-action-buttons my-0">
					<v-btn
						class="btn-white icons"
						@click="openPaymentDetailsDialog(item)"
					>
						<img src="../../../assets/icons/visibility.svg" alt="" />
					</v-btn>

					<v-btn
						class="btn-white icons"
						@click="downloadPaymentReceiptFun(item)"
					>
						<img src="../../../assets/icons/download.svg" alt="" />
					</v-btn>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper mt-4" v-if="getBillingPaymentsLoading">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>

				<div
					class="no-data-wrapper"
					v-if="getPayments.length == 0 && !getBillingPaymentsLoading"
				>
					<div class="no-data-heading">
						<img
							src="../../../assets/icons/no-card-found.svg"
							width="70px"
							height="82px"
							alt=""
						/>
						<h3>No Payment History Available</h3>
					</div>
				</div>
			</template>
		</v-data-table>

		<Pagination
			v-if="typeof getPayments !== 'undefined' && getPayments.length > 0"
			:pageData.sync="page"
			:lengthData.sync="getTotalPage"
			@goto-page="goToPage"
		/>

		<ViewPaymentDetailsDialog
			:paymentReceiptDetails.sync="paymentReceiptDetails"
			:openPaymentDetailsDialogFlag.sync="openPaymentDetailsDialogFlag"
			@close="closePaymentDetailDialog"
			@download="downloadPaymentReceiptFun"
		/>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import moment from "moment";
import ViewPaymentDetailsDialog from "../../BillingComponents/Dialog/ViewPaymentDetailsDialog.vue";
import Search from "../../Search.vue";
import Pagination from "../../Pagination.vue";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "PaymentHistory",
	props: [],
	components: {
		Search,
		Pagination,
		ViewPaymentDetailsDialog,
	},
	async mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "billing");
		const defaultCustomerId = await this.defaultCustomerId();
		const payload = {
			defaultCustomerId: defaultCustomerId,
			startPosition: 1,
			maximumResultPerPage: this.itemsPerPage,
		};
		await this.fetchPayments(payload);
	},
	computed: {
		...mapGetters([
			"getPayments",
			"getBillingPaymentsLoading",
			"getBillingPaymentsReceiptLoading",
			"getUser",
			"getTotalRecords",
			"getPaginationLoader",
		]),
		getTotalPage: {
			get() {
				return Math.ceil(this.getTotalRecords / this.itemsPerPage);
			},
		},
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 10,
		search: "",
		headers: [
			{
				text: "Receipt #",
				align: "start",
				sortable: false,
				value: "receipt_no",
				width: "12%",
				fixed: true,
			},
			{
				text: "",
				align: "end",
				sortable: false,
				value: "actions",
				width: "10%",
				fixed: true,
			},
		],
		searchData: "",
		selected: [],
		paymentIds: [],
		breadcrumbsItem: [
			{
				text: "Billing",
				disabled: false,
				href: "billing",
			},
			{
				text: "Payment History",
				disabled: true,
			},
		],
		openPaymentDetailsDialogFlag: false,
		paymentReceiptDetails: [],
		isInputExpanded: false,
		isButtonExpanded: false,
	}),
	methods: {
		...mapActions(["fetchPayments", "downloadPaymentReceipt"]),
		...globalMethods,
		unCheckAll() {
			this.paymentIds = [];
			this.selected = [];
		},
		checkboxValue(value) {
			this.paymentIds = value.map((item) => item.receipt_no);
		},
		openPaymentDetailsDialog(item) {
			this.paymentReceiptDetails = item;
			this.openPaymentDetailsDialogFlag = true;
		},
		async downloadPaymentReceiptFun(item) {
			const defaultCustomerId = this.defaultCustomerId();
			const payload = {
				defaultCustomerId: defaultCustomerId,
			};
			if (item?.receipt_no) {
				payload.receiptNo = item.receipt_no;
				await this.downloadPaymentReceipt(payload);
			} else if (Array.isArray(this.selected)) {
				this.selected.forEach(async (raw) => {
					payload.receiptNo = raw.receipt_no;
					await this.downloadPaymentReceipt(payload);
				});
			}
		},
		closePaymentDetailDialog() {
			this.openPaymentDetailsDialogFlag = false;
		},
		dateFormatChange(date) {
			return moment.utc(date).format("MMM DD, YYYY");
		},
		async goToPage(pageNumber) {
			const maximumResultPerPage = this.itemsPerPage;
			const startPosition =
				pageNumber * maximumResultPerPage - maximumResultPerPage + 1;
			const defaultCustomerId = await this.defaultCustomerId();
			const payload = {
				defaultCustomerId: defaultCustomerId,
				startPosition: startPosition,
				maximumResultPerPage: maximumResultPerPage,
				paginationLoader: true,
			};
			await this.fetchPayments(payload);
		},
		selectCheckbox() {
			this.isButtonExpanded = true;
		},
		clearCheckbox() {
			this.isButtonExpanded = false;
			this.selected = [];
		},
		clearInput() {
			this.isInputExpanded = false;
			this.search = "";
		},
		inputClick() {
			this.isInputExpanded = true;
			document.getElementById("search-input").focus();
		},
		clickBack() {
			this.$emit("paymentHistoryHide");
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/billing/billingTable.scss";
@import "../../../assets/scss/pages_scss/billing/paymentHistory.scss";
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/buttons.scss";

.ccicon {
	top: calc(50% - 7px) !important;
	width: 33px !important;
}
</style>
