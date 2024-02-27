<!-- @format -->

<template>
	<div class="manage-payment-methods-wrapper" v-resize="onResize">
		<v-tabs class="payment-method-tabs" @change="onTabChange" v-if="!isMobile">
			<v-tab
				v-for="(n, i) in paymentTab"
				:key="i"
				:class="i === 0 ? 'tab-pm-cc' : 'tab-pm-ach'"
			>
				{{ n }}
			</v-tab>

			<div class="ml-auto">
				<v-btn
					v-if="paymentTabKey === 0"
					color="primary"
					dark
					class="btn-blue"
					@click="openPaymentMethodDialog"
				>
					Add Credit Card
				</v-btn>
				<v-btn
					v-if="paymentTabKey === 1"
					color="primary"
					dark
					class="btn-blue"
					@click="openACHPaymentMethodDialog"
				>
					Add Account
				</v-btn>
			</div>

			<v-tab-item :transition="false">
				<v-card flat>
					<PaymentMethodDesktopTable
						@openPaymentMethodDialog="openPaymentMethodDialog"
						@editPaymentMethod="editPaymentMethod"
						@close="close"
						@deletePaymentMethod="deletePaymentMethod"
						@closeDelete="closeDelete"
						:isMobile="isMobile"
						v-if="!isMobile"
					/>
				</v-card>
			</v-tab-item>

			<v-tab-item :transition="false">
				<ACHPaymentMethodDesktopTable
					@openACHPaymentMethodDialog="openACHPaymentMethodDialog"
					@editPaymentMethod="editACHPaymentMethod"
					@close="close"
					@deletePaymentMethod="deleteACHPaymentMethod"
					@closeDelete="closeDelete"
					:isMobile="isMobile"
					v-if="!isMobile"
				/>
			</v-tab-item>
		</v-tabs>

		<v-tabs class="payment-method-tabs" @change="onTabChange" v-if="isMobile">
			<v-tab
				v-for="(n, i) in paymentTab"
				:key="i"
				:class="i === 0 ? 'tab-pm-cc' : 'tab-pm-ach'"
			>
				{{ n }}
			</v-tab>
			<v-tab-item :transition="false">
				<v-card flat>
					<PaymentMethodMobileTable
						@openPaymentMethodDialog="openPaymentMethodDialog"
						@editPaymentMethod="editPaymentMethod"
						@close="close"
						@deletePaymentMethod="deletePaymentMethod"
						@closeDelete="closeDelete"
						:isMobile="isMobile"
						v-if="isMobile"
					/>
				</v-card>
			</v-tab-item>

			<v-tab-item :transition="false">
				<ACHPaymentMethodMobileTable
					@openACHPaymentMethodDialog="openACHPaymentMethodDialog"
					@editPaymentMethod="editACHPaymentMethod"
					@close="close"
					@deletePaymentMethod="deleteACHPaymentMethod"
					@closeDelete="closeDelete"
					:isMobile="isMobile"
					v-if="isMobile"
				/>
			</v-tab-item>
		</v-tabs>

		<AddPaymentDialog
			:dialog.sync="paymentMethodDialog"
			:addMethodButtonDisabled="addMethodButtonDisabled"
			:buttonTextAdding="buttonTextAdding"
			:editedIndex.sync="editedIndex"
			:editedItemData.sync="editedItem"
			@close="close"
			@savePaymentMethod="savePaymentMethod"
			@isButtonDisabled="isButtonDisabled"
			:key="componentKey"
		/>

		<AddACHPaymentDialog
			:dialog.sync="ACHPaymentMethodDialog"
			:addACHMethodButtonDisabled="addACHMethodButtonDisabled"
			:buttonTextAdding="buttonTextAdding"
			:editedACHIndex.sync="editedACHIndex"
			:editedItemData.sync="editedACHItem"
			@ACHClose="ACHClose"
			@saveACHPaymentMethod="saveACHPaymentMethod"
			@isButtonDisabled="isButtonDisabled"
		/>

		<EditPaymentDialog
			:editDialog.sync="editDialog"
			:editedIndex.sync="editedIndex"
			:editedItemData.sync="editedItem"
			@close="close"
			@editPaymentMethodDialog="editPaymentMethodDialog"
			:collect="collect"
		/>

		<EditACHPaymentDialog
			:editDialog.sync="editACHDialog"
			:editedItemData.sync="editedACHItem"
			@close="ACHClose"
			@editPaymentMethodDialog="editACHPaymentMethodDialog"
		/>

		<DeleteDialog
			:dialogData.sync="dialogDelete"
			:editedItemData.sync="currentItemToDelete"
			:editedIndexWarehouse.sync="editedIndex"
			:defaultItemWarehouse.sync="defaultItem"
			@delete="deletePaymentMethodConfirm"
			@close="closeDelete"
			fromComponent="card"
			:loadingDelete="getDeletePaymentMethod"
			componentName="Payment Method"
		/>

		<DeleteACHDialog
			:dialogData.sync="dialogACHDelete"
			:editedItemData.sync="currentACHItemToDelete"
			:editedIndexWarehouse.sync="editedACHIndex"
			:defaultItemWarehouse.sync="defaultACHItem"
			@delete="deleteACHPaymentMethodConfirm"
			@close="closeDelete"
			fromComponent="ACH"
			:loadingDelete="getDeletePaymentMethod"
			componentName="ACH Payment Method"
		/>

		<VerificationFailedDialog
			:failedDialog="failedDialog"
			@openPaymentMethodDialog="openPaymentMethodDialog"
			@close="closeFailedDialog"
		/>

		<ACHFailedDialog
			:ACHFailedDialog="ACHFailedDialog"
			@openACHPaymentMethodDialog="openACHPaymentMethodDialog"
			@close="closeACHFailedDialog"
		/>
	</div>
</template>

<script
	type="application/javascript"
	src="https://cdn.jsdelivr.net/vue.js"
></script>
<script>
import { mapActions, mapGetters } from "vuex";
import PaymentMethodDesktopTable from "../../Tables/Settings/PaymentMethods/PaymentMethodDesktopTable.vue";
import ACHPaymentMethodDesktopTable from "../../Tables/Settings/PaymentMethods/Ach/ACHPaymentMethodDesktopTable.vue";
import ACHPaymentMethodMobileTable from "../../Tables/Settings/PaymentMethods/Ach/ACHPaymentMethodMobileTable.vue";
import PaymentMethodMobileTable from "../../Tables/Settings/PaymentMethods/PaymentMethodMobileTable.vue";
import AddPaymentDialog from "../../BillingComponents/Dialog/AddPaymentDialog.vue";
import AddACHPaymentDialog from "../../BillingComponents/Dialog/AddACHPaymentDialog.vue";
import EditPaymentDialog from "../../BillingComponents/Dialog/EditPaymentDialog.vue";
import EditACHPaymentDialog from "../../BillingComponents/Dialog/EditACHPaymentDialog.vue";
import DeleteDialog from "../../Dialog/DeleteDialog.vue";
import DeleteACHDialog from "../../Dialog/DeleteACHDialog.vue";
import VerificationFailedDialog from "../../Dialog/VerificationFailedDialog.vue";
import ACHFailedDialog from "../../Dialog/ACHFailedDialog.vue";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "ManagePayment",
	components: {
		PaymentMethodDesktopTable,
		ACHPaymentMethodDesktopTable,
		ACHPaymentMethodMobileTable,
		PaymentMethodMobileTable,
		AddPaymentDialog,
		AddACHPaymentDialog,
		EditPaymentDialog,
		EditACHPaymentDialog,
		DeleteDialog,
		DeleteACHDialog,
		VerificationFailedDialog,
		ACHFailedDialog,
	},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "settings/manage-payment-methods");
		this.fetchPaymentMethods(this.defaultCustomerId());
		this.fetchACHPaymentMethods(this.defaultCustomerId());
	},
	data: () => ({
		collect: undefined,
		addMethodButtonDisabled: false,
		addACHMethodButtonDisabled: false,
		buttonTextAdding: false,
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		search: "",
		editDialog: false,
		editACHDialog: false,
		paymentMethodDialog: false,
		ACHPaymentMethodDialog: false,
		dialogDelete: false,
		dialogACHDelete: false,
		failedDialog: false,
		ACHFailedDialog: false,
		headers: [
			{
				text: "Method",
				align: "start",
				sortable: false,
				value: "card_number",
				width: "40%",
				fixed: true,
			},
			{
				text: "Date Added",
				align: "start",
				sortable: false,
				value: "date_added",
				width: "20%",
				fixed: true,
			},
			{
				text: "Last Used",
				align: "start",
				sortable: false,
				value: "last_used",
				width: "20%",
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

		editedIndex: -1,
		editedACHIndex: -1,
		editedItem: {
			card_number: "",
			expiration: "",
			cvc: "",
			name_on_card: "",
			country: "",
			default: false,
		},
		editedACHItem: {
			name: "",
			routing: "",
			account: "",
			is_default: false,
		},
		defaultItem: {
			card_number: "",
			expiration: "",
			cvc: "",
			name_on_card: "",
			country: "",
			default: false,
		},
		defaultACHItem: {
			name: "",
			routing: "",
			account: "",
			is_default: false,
		},
		isMobile: false,
		tabs: ["Users", "Notifications", "Payment Methods"],
		paymentTab: ["Credit Card", "ACH Accounts"],
		activeTab: 2,
		currentItemToDelete: null,
		currentACHItemToDelete: null,
		getDeletePaymentMethod: false,
		businessId: "57fa9f63-6419-423b-89b7-a4589f394dae",
		applicationId: "urn:aid:1f9bd647-f933-4446-af5f-6a79c3539b52",
		options: {
			amount: 1500,
			iFrame: {
				border: "0px",
				height: "320px",
				width: "100%",
			},
			style: {
				theme: "customer",
			},
			displayComponents: {
				firstName: true,
				lastName: true,
				emailAddress: true,
				zipCode: true,
				labels: true,
			},
			additionalFieldsToValidate: ["firstName", "lastName", "zip"],
		},
		componentKey: 0,
		paymentTabKey: 0,
	}),
	computed: {
		...mapGetters({
			getUpdateCardLoading: "getUpdateCardLoading",
			getACHPaymentMethodLoading: "getACHPaymentMethodLoading",
			getcards: "getcards",
			getACHPayments: "getACHPayments",
			getUser: "getUser",
		}),
	},
	watch: {
		paymentMethodDialog(val) {
			val || this.close();
		},
		ACHPaymentMethodDialog(val) {
			val || this.ACHClose();
		},
		dialogDelete(val) {
			val || this.closeDelete();
		},
	},
	methods: {
		...mapActions({
			fetchPaymentMethods: "fetchPaymentMethods",
			fetchACHPaymentMethods: "fetchACHPaymentMethods",
			removePaymentMethod: "removePaymentMethod",
			removeACHPaymentMethod: "removeACHPaymentMethod",
			addPaymentMethod: "addPaymentMethod",
			addACHPaymentMethod: "addACHPaymentMethod",
			updatePaymentMethod: "updatePaymentMethod",
			updateACHPaymentMethod: "updateACHPaymentMethod",
		}),
		...globalMethods,
		onTabChange(i) {
			this.paymentTabKey = i;
			this.page = 1;
		},
		openPaymentMethodDialog() {
			this.componentKey += 1;
			this.failedDialog = false;
			this.paymentMethodDialog = true;
			this.addMethodButtonDisabled = true;
		},
		async savePaymentMethod(creditCardDetails) {
			this.buttonTextAdding = true;
			const payload = {
				cardHolder_first_name: creditCardDetails.cardHolder_first_name,
				number: creditCardDetails.number,
				expiration_month: creditCardDetails.expiration_month,
				expiration_year: creditCardDetails.expiration_year,
				cv_data: creditCardDetails.cv_data,
				default_customer_id: this.defaultCustomerId(),
				is_default: creditCardDetails.is_default
					? creditCardDetails.is_default
					: 0,
			};
			const response = await this.addPaymentMethod(payload);
			if (response.data.code !== 200) {
				this.failedDialog = true;
			} else {
				this.fetchPaymentMethods(this.defaultCustomerId());
				setTimeout(() => {
					this.notificationMessage(
						"Payment method have been added successfully"
					);
				}, 1000);
				this.close();
			}
			this.buttonTextAdding = false;
		},
		async editPaymentMethodDialog(params) {
			await this.updatePaymentMethod(params);
			this.fetchPaymentMethods(this.defaultCustomerId());
			// close dialog
			this.close();
			setTimeout(() => {
				this.notificationMessage("Payment method has been updated.");
			}, 1000);
		},
		openACHPaymentMethodDialog() {
			this.ACHFailedDialog = false;
			this.ACHPaymentMethodDialog = true;
			this.addACHMethodButtonDisabled = true;
		},
		async saveACHPaymentMethod(ACHFormData) {
			this.buttonTextAdding = true;
			const payload = {
				xName: ACHFormData.name,
				xRouting: ACHFormData.routing,
				xAccount: ACHFormData.account,
				default_customer_id: this.defaultCustomerId(),
			};
			const response = await this.addACHPaymentMethod(payload);
			if (response.data.code !== 200) {
				this.ACHFailedDialog = true;
				this.ACHPaymentMethodDialog = false;
			} else {
				this.fetchACHPaymentMethods(this.defaultCustomerId());
				setTimeout(() => {
					this.notificationMessage(
						"Payment method have been added successfully"
					);
				}, 1000);
				this.ACHClose();
			}
			this.buttonTextAdding = false;
			this.addACHMethodButtonDisabled = false;
		},
		async editACHPaymentMethodDialog(params) {
			params["default_customer_id"] = this.defaultCustomerId();
			const updateACHResult = await this.updateACHPaymentMethod(params);
			if (updateACHResult.data.code !== 200) {
				setTimeout(() => {
					this.notificationError(updateACHResult.data.error);
				}, 1000);
			} else {
				this.fetchACHPaymentMethods(this.defaultCustomerId());
				setTimeout(() => {
					this.notificationMessage("ACH Payment method has been updated");
				}, 1000);
				this.ACHClose();
			}
		},
		editPaymentMethod(payment) {
			this.editedIndex = this.getcards.findIndex((payment) => payment.id);
			this.editedItem = Object.assign({}, payment);
			this.editDialog = true;
		},
		editACHPaymentMethod(payment) {
			this.editedACHItem = Object.assign({}, payment);
			this.editACHDialog = true;
		},
		close() {
			this.editDialog = false;
			this.paymentMethodDialog = false;
			//this.collect.unmount("card-element", document);
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},
		ACHClose() {
			this.editACHDialog = false;
			this.ACHPaymentMethodDialog = false;
			this.$nextTick(() => {
				this.editedACHItem = Object.assign({}, this.defaultACHItem);
			});
		},
		isButtonDisabled(flag) {
			this.addMethodButtonDisabled = flag;
		},
		closeFailedDialog() {
			this.failedDialog = false;
		},
		closeACHFailedDialog() {
			this.ACHFailedDialog = false;
		},
		deletePaymentMethod(item) {
			this.dialogDelete = true;
			this.currentItemToDelete = item;
		},
		deleteACHPaymentMethod(item) {
			this.dialogACHDelete = true;
			this.currentACHItemToDelete = item;
		},
		async deletePaymentMethodConfirm() {
			if (this.currentItemToDelete !== null) {
				this.getDeletePaymentMethod = true;
				const params = {
					card_id: this.currentItemToDelete.card_id,
					default_customer_id: this.defaultCustomerId(),
				};
				await this.removePaymentMethod(params);
				this.fetchPaymentMethods(this.defaultCustomerId());
				setTimeout(() => {
					this.notificationMessage("Payment method has been deleted.");
					this.getDeletePaymentMethod = false;
					this.closeDelete();
				}, 3000);
			}
		},
		async deleteACHPaymentMethodConfirm() {
			if (this.currentACHItemToDelete !== null) {
				this.getDeletePaymentMethod = true;
				const params = {
					record_id: this.currentACHItemToDelete.record_id,
					default_customer_id: this.defaultCustomerId(),
				};
				await this.removeACHPaymentMethod(params);
				this.fetchACHPaymentMethods(this.defaultCustomerId());
				setTimeout(() => {
					this.notificationMessage("Payment method has been deleted.");
					this.getDeletePaymentMethod = false;
					this.closeDelete();
				}, 3000);
			}
		},
		closeDelete() {
			this.dialogDelete = false;
			this.dialogACHDelete = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},
		save() {
			if (this.editedIndex > -1) {
				Object.assign(this.desserts[this.editedIndex], this.editedItem);
			} else {
				this.desserts.push(this.editedItem);
			}
			this.close();
		},
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		handleManagePayment() {
			this.$router.push(`billing/manage-payment-methods`);
		},
		managePayment(item) {
			console.log(item);
		},
		getImgUrl(pic) {
			if (pic !== "undefined" && pic !== null) {
				return require(`../../../assets/icons/${pic}.svg`);
			} else {
				return require("../../../assets/icons/default-product-icon.svg");
			}
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/settings/managePayment.scss";
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/buttons.scss";
</style>
