<!-- @format -->

<template>
	<div class="billing-wrapper" v-resize="onResize">
		<div class="billing-due" v-if="!paymentHistoryFlag">
			<div class="due-info">
				<h3>Total Due</h3>
				<h2>{{ currencyNumberFormat(totalDueAmount) }}</h2>
			</div>

			<v-btn class="btn-white" v-if="getAllInvoices.length == 0">
				<img
					src="../assets/icons/checkMark.png"
					class="mr-1"
					width="15px"
					height="15px"
					alt=""
				/>
				<span class="green--text">All Paid</span>
			</v-btn>

			<div
				class="due-button"
				v-if="getAllInvoices && getAllInvoices.length > 0"
			>
				<v-btn
					color="error"
					dark
					class="btn-blue clear-due"
					v-if="!isAllPaid && userCanMakePayment"
					@click="makePayment(totalDueAmount)"
					text
					><span>Clear All Due</span>
				</v-btn>

				<v-btn class="btn-white" v-if="isAllPaid">
					<img
						src="../assets/icons/checkMark.png"
						class="mr-1"
						width="15px"
						height="15px"
						alt=""
					/>
					<span class="green--text">All Paid</span>
				</v-btn>
			</div>
		</div>
		<PaymentHistory
			v-if="paymentHistoryFlag && !isMobile"
			:dialog.sync="dialogView"
			@paymentHistoryHide="paymentHistoryHide"
		/>
		<PaymentHistoryMobile
			v-if="paymentHistoryFlag && isMobile"
			:dialog.sync="dialogView"
			@paymentHistoryHide="paymentHistoryHide"
		/>

		<BillingDesktopTable
			:items="items"
			@makePayment="makePayment"
			@close="close"
			@viewPayment="viewPayment"
			@closeView="closeView"
			@paymentHistoryShow="paymentHistoryShow"
			:isMobile="isMobile"
			v-if="!isMobile && !paymentHistoryFlag"
			:userCanMakePayment="userCanMakePayment"
		/>

		<BillingMobileTable
			:items="items"
			@makePayment="makePayment"
			@viewPayment="viewPayment"
			@paymentHistoryShow="paymentHistoryShow"
			:isMobile="isMobile"
			v-if="isMobile && !paymentHistoryFlag"
			:userCanMakePayment="userCanMakePayment"
		/>

		<ViewPaymentDialog
			:viewitems="items"
			@makePayment="makePayment"
			:dialog.sync="dialogView"
			:editedIndex.sync="editedIndex"
			:editedItemData.sync="editedItem"
			:isMobile="isMobile"
			@close="closeView"
			:invoiceDetail="invoiceDetail"
			:getInvoiceDetailLoadingStatus="getInvoiceDetailLoadingStatus"
			:userCanMakePayment="userCanMakePayment"
		/>

		<MakePaymentDialog
			@paymentDialog="paymentDialog"
			:dialog.sync="dialog"
			:editedItemData.sync="editedItem"
			:isMobile="isMobile"
			@close="close"
			:getcards="getcards"
			:default_customer_id="default_customer_id"
			:fromComponent="'Billing'"
		/>

		<NoPaymentDialog :dialogShow.sync="dialogShow" @close="closeDialog" />

		<PaymentCompletedDialog
			:paymentsDialog="paymentsDialog"
			:editedIndex.sync="editedIndex"
			:editedItemData.sync="editedItem"
			@close="closePaymentDialog"
		/>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import globalMethods from "../utils/globalMethods";
import BillingDesktopTable from "../components/Tables/Billing/BillingDesktopTable.vue";
import PaymentHistory from "../components/Tables/Billing/PaymentHistory.vue";
import BillingMobileTable from "../components/Tables/Billing/BillingMobileTable.vue";
import MakePaymentDialog from "../components/BillingComponents/Dialog/MakePaymentDialog.vue";
import ViewPaymentDialog from "../components/BillingComponents/Dialog/ViewPaymentDialog.vue";
import NoPaymentDialog from "../components/BillingComponents/Dialog/NoPaymentDialog.vue";
import PaymentCompletedDialog from "../components/BillingComponents/Dialog/PaymentCompletedDialog.vue";
import PaymentHistoryMobile from "../components/Tables/Billing/PaymentHistoryMobile.vue";
import { CUSTOMER_ADMIN } from "../constants/UserRoles";
export default {
	name: "Billing",
	components: {
		BillingDesktopTable,
		BillingMobileTable,
		MakePaymentDialog,
		ViewPaymentDialog,
		NoPaymentDialog,
		PaymentCompletedDialog,
		PaymentHistory,
		PaymentHistoryMobile,
	},
	data: () => ({
		isMobile: false,
		totalDueAmount: 0,
		dialog: false,
		dialogView: false,
		editedIndex: -1,
		editedItem: {
			invoice_no: "",
			invoice_date: "",
			shipment_reference: "",
			due_date: "",
			amuont: "",
		},
		defaultItem: {
			invoice_no: "",
			invoice_date: "",
			shipment_reference: "",
			due_date: "",
			amuont: "",
		},
		dialogShow: false,
		paymentsDialog: false,
		default_customer_id: 0,
		paymentHistoryFlag: false,
	}),
	computed: {
		...mapGetters([
			"getAllInvoices",
			"getInvoiceLoadingStatus",
			"getInvoice",
			"getInvoiceDetailLoadingStatus",
			"getcards",
			"getcardsLoading",
			"getUser",
		]),
		formTitle() {
			return this.editedIndex === -1 ? "New Item" : "Edit Item";
		},
		items() {
			this.totalDueAmount === 0 &&
				this.getAllInvoices.forEach((item) => {
					//const itemAmount = parseFloat(item.total_amount);
					//item.paid === false ? (this.totalDueAmount += itemAmount) : 0;
					this.totalDueAmount += parseFloat(item.balance);
				});
			return this.getAllInvoices;
		},
		invoiceDetail() {
			return this.getInvoice;
		},
		isAllPaid() {
			let filteredInvoices = this.getAllInvoices.filter((item) => item.paid);
			return filteredInvoices.length === this.getAllInvoices.length;
		},
		userCanMakePayment() {
			let user = this.getUser;
			if (typeof user !== "object") {
				user = JSON.parse(this.getUser);
			}

			let roles = [];
			if (user.roles && user.roles.length > 0) {
				roles = user.roles.map((role) => role.name);
			}
			return roles.includes(CUSTOMER_ADMIN);
		},
	},
	methods: {
		...globalMethods,
		...mapActions(["fetchInvoices", "fetchInvoice", "fetchAllPaymentMethods"]),
		getCurrentTab(id) {
			this.$store.dispatch("page/setTab", id);
		},
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		async makePayment(item) {
			this.default_customer_id = this.defaultCustomerId();
			this.editedIndex = this.items.indexOf(item);
			this.editedItem = item;
			const response = await this.fetchAllPaymentMethods(
				this.defaultCustomerId()
			);
			if (response.data.code !== 200) {
				this.dialogShow = true;
				this.dialog = false;
			} else {
				this.dialogShow = false;
				this.dialog = true;
			}
		},
		viewPayment(item) {
			this.fetchInvoice(item.id);
			this.editedItem = Object.assign({}, this.invoiceDetail);
			this.dialogView = true;
		},
		close(paidAmount) {
			this.dialog = false;
			if (paidAmount && paidAmount > 0) {
				const result =
					parseFloat(this.totalDueAmount).toFixed(2) -
					parseFloat(paidAmount).toFixed(2);
				this.totalDueAmount = result == 0 ? 0.0001 : result;

				this.$nextTick(() => {
					this.editedItem = Object.assign({}, this.defaultItem);
					this.editedIndex = -1;
				});
				this.dialogView = false;
			}
		},
		closeView() {
			this.dialogView = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},
		closeDialog() {
			this.dialogShow = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},
		paymentDialog(item) {
			if (item.paid == true) {
				this.paymentsDialog = true;
				this.editedItem = Object.assign({}, item);
			} else {
				this.paymentsDialog = false;
			}
		},
		closePaymentDialog() {
			this.paymentsDialog = false;
		},
		// DELETE DIALOG
		deleteItem(item) {
			this.editedIndex = this.desserts.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialogDelete = true;
		},
		deleteItemConfirm() {
			this.desserts.splice(this.editedIndex, 1);
			this.closeDelete();
		},
		closeDelete() {
			this.dialogDelete = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},
		paymentHistoryShow() {
			this.paymentHistoryFlag = true;
		},
		paymentHistoryHide() {
			this.paymentHistoryFlag = false;
		},
	},
	created() {
		this.fetchInvoices();
	},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "billing");
	},
};
</script>

<style lang="scss">
@import "../assets/scss/pages_scss/billing/billing.scss";
@import "../assets/scss/buttons.scss";
</style>
