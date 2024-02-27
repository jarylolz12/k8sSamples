<!-- @format -->

<template>
	<div class="billing-table-wrapper">
		<div class="details-breadcrumbs">
			<div class="navigation-back">
				<router-link to="/shipments" class="shipment-link">
					Bill
				</router-link>
				<span class="right-chevron">
					<img
						src="../../../assets/images/right_chevron.svg"
						alt=""
						srcset=""
						width="7px"
					/>
				</span>
				<p class="shipment-ref">Payment History</p>
			</div>
		</div>
		<div class="due-info">
			<h3>Billing</h3>
			<h2>Payment History</h2>
		</div>

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
			show-select
			@input="checkboxValue"
			v-model="selected"
			color="primary"
		>
			<template v-slot:top>
				<v-toolbar flat>
					<v-btn
						href="/billing"
						class="mr-2 back-to-billing-btn"
						outlined
						color="#0171A1"
					>
						<v-icon>mdi-arrow-left</v-icon>
					</v-btn>
					<v-toolbar-title>Payment History</v-toolbar-title>

					<v-spacer></v-spacer>

					<div class="search-component">
						<Search
							placeholder="Search Receipt #"
							className="search custom-search"
							:inputData.sync="search"
						/>
					</div>
				</v-toolbar>
			</template>

			<template v-slot:[`item.payment_method`]="{ item }">
				<div>
					<img
						:src="require(`@/assets/icons/${item.card_icon}`)"
						width="40px"
						height="42px"
						alt=""
					/>
					<p class="mb-0">{{ item.payment_method }}</p>
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

					<v-btn class="btn-white icons" @click="download(item)">
						<img src="../../../assets/icons/download.svg" alt="" />
					</v-btn>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="no-data-wrapper" v-if="billings.length == 0">
					<div class="no-data-heading">
						<img
							src="../../../assets/icons/document.svg"
							width="40px"
							height="42px"
							alt=""
						/>
						<h3>No Payment History</h3>
					</div>
				</div>
			</template>
		</v-data-table>
		<Pagination
			v-if="typeof billings !== 'undefined' && billings.length > 0"
			:pageData.sync="page"
			:lengthData.sync="pageCount"
		/>
		<ViewPaymentDetailsDialog
			:openPaymentDetailsDialogFlag.sync="openPaymentDetailsDialogFlag"
		/>
	</div>
</template>

<script>
import ViewPaymentDetailsDialog from "../../BillingComponents/Dialog/ViewPaymentDetailsDialog.vue";
import Search from "../../Search.vue";
import Pagination from "../../Pagination.vue";
import _ from "lodash";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "PaymentHistory",
	props: [],
	components: {
		Search,
		Pagination,
		ViewPaymentDetailsDialog,
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
				text: "Receipt #",
				align: "start",
				sortable: false,
				value: "receipt_no",
				width: "10%",
				fixed: true,
			},
			{
				text: "Date & Time",
				align: "start",
				sortable: false,
				value: "date_time",
				width: "15%",
				fixed: true,
			},
			{
				text: "Payment Method",
				align: "start",
				sortable: false,
				value: "payment_method",
				width: "20%",
				fixed: true,
			},
			{
				text: "Invoice Reference",
				align: "start",
				sortable: true,
				value: "invoice_reference",
				width: "30%",
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
				width: "20%",
				fixed: true,
			},
		],
		searchData: "",
		selected: [],
		billings: [
			{
				receipt_no: "#76KS091",
				date_time: "Mar 13, 2021 9:31AM",
				card_icon: "mastercard.svg",
				payment_method: "**** **** **** 3840 Alfraid Johson, 06/24",
				invoice_reference: "1234567890, 1234567890, 1234567890",
				amount: 12261,
			},
			{
				receipt_no: "#76KS091",
				date_time: "Mar 13, 2021 9:31AM",
				card_icon: "visa.svg",
				payment_method: "**** **** **** 3840 Alfraid Johson, 06/24",
				invoice_reference: "1234567890, 1234567890, 1234567890",
				amount: 12261,
			},
			{
				receipt_no: "#76KS091",
				date_time: "Mar 13, 2021 9:31AM",
				card_icon: "american_express.svg",
				payment_method: "**** **** **** 3840 Alfraid Johson, 06/24",
				invoice_reference: "1234567890, 1234567890, 1234567890",
				amount: 12261,
			},
			{
				receipt_no: "#76KS091",
				date_time: "Mar 13, 2021 9:31AM",
				card_icon: "ach.svg",
				payment_method: "**** **** **** 3840 Alfraid Johson, 06/24",
				invoice_reference: "1234567890, 1234567890, 1234567890",
				amount: 12261,
			},
		],
		openPaymentDetailsDialogFlag: false,
	}),
	methods: {
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
		openPaymentDetailsDialog(item) {
			console.log("item", item);
			this.openPaymentDetailsDialogFlag = true;
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/billing/billingTable.scss";
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/buttons.scss";

.back-to-billing-btn {
	background-color: #fff;
	height: 34px !important;
	width: 34px !important;
	min-width: 34px !important;
}
.ccicon {
	top: calc(50% - 7px) !important;
	width: 33px !important;
}
</style>
