<!-- @format -->

<template>
	<div class="users-wrapper" v-resize="onResize">
		<CompanyInfo
			:companyDetail.sync="companyDetail"
		/>

		<UsersDesktopTable
			:isMobile="isMobile"
			:items="items"
			@addUser="addUser"
			@deleteUser="deleteUser"
			@close="close"
			@closeDelete="closeDelete"
			@resendInvitation="resendInvitation"
			@addUserGroup="addUserGroup"
			:companyId="getCompnayId"
			v-if="!isMobile"
		/>

		<UsersMobileTable
			:isMobile="isMobile"
			:items="items"
			@addUser="addUser"
			@deleteUser="deleteUser"
			@close="close"
			@closeDelete="closeDelete"
			@resendInvitation="resendInvitation"
			v-if="isMobile"
		/>

		<!-- <Pagination 
            v-if="typeof items !== 'undefined' && items.length > 0"
            :pageData.sync="page"
            :lengthData.sync="pageCount"
            :isMobile="isMobile"
        /> -->

		<AddUserInviteDialog
			:dialog.sync="dialog"
			:userId="getLoggedUserId"
			:companyId="getCompnayId"
			@close="close"
		/>

		<DeleteDialog
			:dialogData.sync="dialogDelete"
			:editedItemData.sync="currentItemToDelete"
			:editedIndexWarehouse.sync="editedIndex"
			:defaultItemWarehouse.sync="defaultItem"
			@delete="deleteUserConfirm"
			@close="closeDelete"
			fromComponent="user"
			:loadingDelete="getDeletePaymentMethod"
			componentName="User"
		/>
		<NewUserGroupDialog
			:dialog.sync="addNewUserGroupDialog"
			@closeNewUserGroupDialog="closeNewUserGroupDialog"
			:companyId="getCompnayId"
			:fromAddUser="false"
		/>
		<ConfirmDialog :dialogData.sync="resendInvitationDialog">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/info-blue.svg" alt="alert" />
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2>Confirm Resend Invitation</h2>
            </template>

            <template v-slot:dialog_content>
                <p>
                   Are you sure you want to re-send user invitation email to {{selectedInviteUserItem ? selectedInviteUserItem.email_address : ''}}?
                </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn
                    class="btn-blue"
                    text
                    @click="sendResendInvitation"
					:disabled="getReSendUserInvitationForCompanyLoading"
                >
					{{
						getReSendUserInvitationForCompanyLoading ? "Invitation Sending..."  : "Yes"
					}}
                </v-btn>
				<v-btn
                    class="btn-white"
                    text
                    @click="closeResendInviteDialog"
                >
                    No
                </v-btn>
            </template>
        </ConfirmDialog>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import UsersDesktopTable from "../../Tables/Settings/Users/UsersDesktopTable.vue";
import CompanyInfo from "../CompanyInfo/CompanyInfo.vue";
import UsersMobileTable from "../../Tables/Settings/Users/UsersMobileTable.vue";
import DeleteDialog from "../../Dialog/DeleteDialog.vue";
import AddUserInviteDialog from "../Dialog/AddUserInviteDialog.vue";
import ConfirmDialog from "../../../components/Dialog/GlobalDialog/ConfirmDialog.vue";
import NewUserGroupDialog from "../Dialog/NewUserGroupDialog.vue"
// import Search from '../../Search.vue'
// import Pagination from '../../Pagination.vue'
import globalMethods from "../../../utils/globalMethods";
import _ from "lodash";

export default {
	name: "Users",
	components: {
		CompanyInfo,
		UsersDesktopTable,
		UsersMobileTable,
		DeleteDialog,
		AddUserInviteDialog,
		ConfirmDialog,
		NewUserGroupDialog
		// Search,
		// Pagination
	},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "settings/users");
		this.customerAdmin();
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		search: "",
		dialog: false,
		dialogDelete: false,
		resendInvitationDialog: false,
		selectedInviteUserItem: null,
		headers: [
			{
				text: "User",
				align: "start",
				sortable: false,
				value: "name",
				width: "30%",
				fixed: true,
			},
			{
				text: "Email Address",
				align: "start",
				sortable: false,
				value: "email_address",
				width: "20%",
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
				text: "Last Activity At",
				align: "start",
				sortable: false,
				value: "activity_at",
				width: "15%",
				fixed: true,
			},
			{
				text: "",
				align: "end",
				sortable: false,
				value: "actions",
				width: "15%",
				fixed: true,
			},
		],
		editedIndex: -1,
		editedItem: {
			name: "",
			email_address: "",
			date_added: "",
			activity_at: "",
			isCurrent: "",
		},
		defaultItem: {
			name: "",
			email_address: "",
			date_added: "",
			activity_at: "",
			isCurrent: "",
		},
		isMobile: false,
		tabs: ["Users", "Notifications", "Payment Methods"],
		activeTab: 0,
		currentItemToDelete: null,
		getDeletePaymentMethod: false,
		isInputExpanded: false,
		searchData: "",
		// add UserGroup
		addNewUserGroupDialog:false
	}),
	computed: {
		...mapGetters({
			getCustomerAdmins: "getCustomerAdmins",
			getUser: "getUser",
			getReSendUserInvitationForCompanyLoading:
                "customers/getReSendUserInvitationForCompanyLoading",
			getReSendUserInvitationForCompany:
                "customers/getReSendUserInvitationForCompany",
		}),
		items() {
			let userId;
			if (typeof this.getUser === "string") {
				userId = JSON.parse(this.getUser).id;
			} else {
				userId = this.getUser.id;
			}

			return this.getCustomerAdmins.map((item) => {
				return {
					activity_at: item.updated_at,
					date_added: item.created_at,
					email_address: item.email,
					isCurrent: item.id === userId,
					name: item.name,
					is_confirm: item.is_customer_invite_confirm,
					is_manager: item.is_customer_invite_confirm ? 0 : 1
				};
			});
		},
		companyDetail() {
			let customerDetail;
			let getUser;
			if (typeof this.getUser === "string") {
				getUser = JSON.parse(this.getUser);
			} else {
				getUser = this.getUser;
			}
			let getCustomers = getUser.customers_api
            if (getCustomers && getCustomers.length > 0) {
				let selectedCustomer = getUser.default_customer_id; 
				customerDetail = _.find(getCustomers, function(customer) {
					if (customer.id === selectedCustomer) {
						return true;
					}
				});	
			}
			return customerDetail;
		},
		getCompnayId() {
			let getUser;
			if (typeof this.getUser === "string") {
				getUser = JSON.parse(this.getUser);
			} else {
				getUser = this.getUser;
			}
			return getUser.default_customer_id ?? ''
		},
		getLoggedUserId() {
			let getUser;
			if (typeof this.getUser === "string") {
				getUser = JSON.parse(this.getUser);
			} else {
				getUser = this.getUser;
			}
			return getUser.id ?? ''
		}
	},
	watch: {
		dialog(val) {
			val || this.close();
		},
		dialogDelete(val) {
			val || this.closeDelete();
		},
		addNewUserGroupDialog(val) {
			val || this.closeNewUserGroupDialog();
		},
	},
	methods: {
		...mapActions({
			fetchUsersDetail: "fetchUsersDetail",
			reSendUserInvitationForCompany:
                "customers/reSendUserInvitationForCompany",
		}),
		...globalMethods,
		getCurrentTab(id) {
			console.log(id);
		},
		onTabChange() {
			this.page = 1;

			if (this.activeTab == 1) {
				this.$router.push(`notifications`);
			}

			if (this.activeTab == 2) {
				this.$router.push(`manage-payment-methods`);
			}
		},
		addUser() {
			this.dialog = true;
		},
		addUserGroup(){
			this.addNewUserGroupDialog =true
		},
		closeNewUserGroupDialog(){
			this.addNewUserGroupDialog =false
		},
		editUser(user) {
			this.editedIndex = this.items.indexOf(user);
			this.editedItem = Object.assign({}, user);
			this.dialog = true;
		},
		close() {
			this.dialog = false;
		},
		deleteUser(item) {
			this.dialogDelete = true;
			this.currentItemToDelete = item;
		},
		async deleteUserConfirm() {
			if (this.currentItemToDelete !== null) {
				this.getDeletePaymentMethod = true;
				setTimeout(() => {
					this.notificationMessage("User has been deleted.");
					this.getDeletePaymentMethod = false;
					this.closeDelete();
				}, 3000);
			}
		},
		closeDelete() {
			this.dialogDelete = false;
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
		clearInput() {
			this.isInputExpanded = false;
			this.searchData = "";
			this.search = "";
		},
		inputClick() {
			this.isInputExpanded = true;
			document.getElementById("search-input").focus();
		},
		handleSearch() {},
		async customerAdmin() {
			let customerId;
			if (typeof this.getUser === "string") {
				customerId = JSON.parse(this.getUser).default_customer_id;
			} else {
				customerId = this.getUser.default_customer_id;
			}
			const params = {
				customer_id: customerId,
			};
			await this.fetchUsersDetail(params);
		},
		resendInvitation(item) {
			this.resendInvitationDialog = true;
			this.selectedInviteUserItem = item;
		},
		async sendResendInvitation() {
			try {
				await this.reSendUserInvitationForCompany({
					invitedToEmail: this.selectedInviteUserItem.email_address,
					companyId: this.getCompnayId,
					invitedById: this.getLoggedUserId,
				});
			} catch (e) {
				this.notificationError(e);
			} finally {
				if (this.getReSendUserInvitationForCompany.success) {
					this.notificationMessage(
						this.getReSendUserInvitationForCompany.message
					);	
				} else {
					this.notificationError(
						this.getReSendUserInvitationForCompany.message
					);
				}
				this.selectedInviteUserItem = null;
				this.resendInvitationDialog = false;
			} 
		},
		closeResendInviteDialog() {
			this.resendInvitationDialog = false;
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/settings/users.scss";
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/buttons.scss";
</style>
