<!-- @format -->

<template>
	<div class="payment-history-wrapper">
		<v-overlay v-if="getPaginationLoader">
			<v-progress-circular :size="40" color="#0171a1" indeterminate>
			</v-progress-circular>
		</v-overlay>
		<v-breadcrumbs :items="breadcrumbsItem">
			<template v-slot:divider>
				<v-icon>mdi-chevron-right</v-icon>
			</template>
		</v-breadcrumbs>

		<v-row>
			<v-col sm="8" md="8" class="d-flex">
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
			</v-col>
			<v-col sm="4" md="4">
				<div class="search-component float-right">
					<Search
						placeholder="Search Receipt #"
						className="search custom-search"
						:inputData.sync="search"
					/>
				</div>
			</v-col>
		</v-row>

		<v-data-table
			:headers="headers"
			:items="getPayments"
			class="payment-history-table elevation-1"
			:class="
				getPayments !== null && getPayments.length > 0 ? '' : 'no-data-table'
			"
			hide-default-footer
			sort-by="due_date"
			:page.sync="page"
			:items-per-page="itemsPerPage"
			:search="search"
			@page-count="pageCount = $event"
			mobile-breakpoint="769"
			show-select
			@input="checkboxValue"
			v-model="selected"
			color="primary"
			item-key="receipt_no"
		>
			<template v-slot:top>
				<v-tabs v-if="paymentIds && paymentIds.length > 0">
					<div class="manage-action">
						<div class="d-flex">
							<span class="ml-5 text--secondary"
								>{{ paymentIds.length }} Items selected.</span
							>
							<a @click="unCheckAll" class="d-flex text-decoration-none ml-3">
								<span>Clear Selection</span>
							</a>
						</div>
						<div class="d-flex justify-center justify-space-between">
							<v-btn
								class="btn-white payment mr-5"
								@click="downloadPaymentReceiptFun"
								>Download ({{ paymentIds.length }})
							</v-btn>
						</div>
					</div>
				</v-tabs>
				<v-overlay
					v-if="getBillingPaymentsReceiptLoading"
					class="align-end mb-10"
				>
					<v-card width="250px" color="#4A4A4A">
						<v-card-text class="text-center d-flex justify-center">
							<img
								src="../../../assets/images/download-white.svg"
								alt=""
								class="mr-3"
							/>
							<span>Downloading receipt...</span>
						</v-card-text>
					</v-card>
				</v-overlay>
			</template>

			<template v-slot:[`item.receipt_no`]="{ item }">
				<p class="mb-0">#{{ item.receipt_no }}</p>
			</template>

			<template v-slot:[`item.date_time`]="{ item }">
				<div>
					<p class="mb-0">{{ item.date_time }}</p>
					<p class="mt-1 mb-0 time-dark">{{ item.transactionTime }}</p>
				</div>
			</template>

			<template v-slot:[`item.card_number`]="{ item }">
				<div class="card-number-wrapper">
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
						<p class="name mb-0">
							{{ item.card_holder_name }}
							{{ item.card_exp && item.card_exp != "-" ? item.card_exp : "" }}
						</p>
					</div>
				</div>
			</template>

			<template v-slot:[`item.amount`]="{ item }">
				<div>
					<p class="mb-0">
						{{ currencyNumberFormat(item.amount) }}
					</p>
				</div>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="manage-action-buttons">
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
				class: "th-heading",
			},
			{
				text: "Date & Time",
				align: "start",
				sortable: false,
				value: "date_time",
				width: "13%",
				fixed: true,
				class: "th-heading",
			},
			{
				text: "Payment Method",
				align: "start",
				sortable: false,
				value: "card_number",
				width: "25%",
				fixed: true,
				class: "th-heading",
			},
			{
				text: "Invoice Reference",
				align: "start",
				sortable: true,
				value: "invoice_reference",
				width: "25%",
				fixed: true,
				class: "th-heading",
			},
			{
				text: "Amount",
				align: "end",
				sortable: false,
				value: "amount",
				width: "15%",
				fixed: true,
				class: "th-heading",
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
