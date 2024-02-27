<!-- @format -->

<template>
	<div>
		<v-data-table
			:headers="_header"
			:items="getGroupWithUsersAndPermissionsData"
			sort-by="calories"
			class="methods-table elevation-1"
			:class="items !== 'undefined' && items.length > 0 ? '' : 'no-data-table'"
			hide-default-footer
			:page.sync="page"
			:items-per-page="itemsPerPage"
			:search="search"
			@page-count="pageCount = $event"
			mobile-breakpoint="769">

			<template v-slot:top>
				<div class="tab-name">
					<div class="tab-method" v-if="activeTab == 0">
						<v-tabs
							class="company-profile-user-group-tab"
      						v-model="activeUserGroupTab"
      						background-color=""
    					>

    	  					<v-tab class="text-capitalize" :class="index == 1 ? 'company-profile-tab-last' : ''" v-for="(tab ,index) in UserGroupTab" :key="index">
								{{tab}}
    	  					</v-tab>
    					</v-tabs>

						<v-spacer></v-spacer>

						<div class="search-component">
							<Search
								:placeholder="activeUserGroupTab == 0 ? 'Search User':'Search Group User'"
								className="search custom-search"
								:inputData.sync="search"
							/>
						</div>

						<v-btn v-if="activeUserGroupTab == 0" color="primary" dark class="ml-2 btn-blue" @click="addUser">
                            Add User
                        </v-btn>
						<v-btn v-if="activeUserGroupTab == 1" color="primary" dark class="ml-2 btn-blue" @click="addUserGroup">
                            Add User Group
                        </v-btn>

					</div>
				</div>
			</template>

			<template v-slot:[`item.name`]="{ item }">
				<div class="user-name">
					<p class="mb-0" v-if="item.isCurrent">
						{{ item.name }}

						<span>(Current User)</span>
					</p>

					<p class="mb-0" v-else>{{ item.name }}</p>
				</div>
			</template>
			<template v-slot:[`item.activity_at`]="{ item }">
				<div>
					<p class="mb-0" v-if="item.is_confirm">
						{{ item.activity_at }}
					</p>
					
					<p class="mb-0" v-else-if="item.is_manager == 1">
						{{ checkLoggedInUserData(item.activity_at) }}
					</p>

					<p class="mb-0 d-flex align-center" v-else>
						<span style="color:#D68A1A; padding-right: 5px;">Invitation Pending</span>
						<v-btn icon @click="resedInvitation(item)" style="height: 30px; width: 30px;">
							<img src="../../../../assets/icons/email-blue.svg" alt="Resend Invitation" title="Resend Invitation" />
						</v-btn>
					</p>

					{{ getUser.roles }}
				</div>
			</template>
			<template v-slot:[`item.users_length`]="{ item }">
				<div>
					<p class="mb-0">
						{{ item.users.length }}
					</p>
				</div>
			</template>
			<template v-slot:[`item.users`]="{ item }">
				<div>
					<p class="mb-0" v-if="item.users.length">
						<span v-for="(name,index) in item.users" :key="name.id">
							<span v-if="index == 0 || index == 1 || index == 2" class="py-1 px-2 mx-1 ml-0" style="background: #F7F7F7;border: 1px solid #EBF2F5;border-radius: 4px; font-size: 12px;">
								{{getName(name.name)}}
							</span> 
							<span class="my-1" v-if="index == 3" style="color: #0171A1;">
								+{{item.users.length - 3}} Others
							</span>
						</span>
						
					</p>
					
					<p class="mb-0 d-flex align-center mr-2" v-else>
						--
					</p>
				</div>
			</template>
			<template v-slot:[`item.permissions`]="{ item }">
				<div>
					<p class="mb-0" v-if="item.permissions.length">
						<span  class="py-1 px-2 mx-1 ml-0 text-capitalize" style="background: #F7F7F7;border: 1px solid #EBF2F5;border-radius: 99px; color:#6D858F; font-size: 12px;">
							{{GetPermissionTotal(item.permissions)}} permissions
						</span>	
					</p>
					
					<p class="mb-0 d-flex align-center mr-2" v-else>
						--
					</p>
				</div>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<!-- <div class="user-action-buttons">
					<v-btn class="btn-white icons" @click="editPaymentMethod(item)">
						<img src="../../../assets/icons/edit-blue.svg" alt="" />
					</v-btn>

					<v-btn class="btn-white icons" @click="deleteUser(item)">
						<img src="../../../../assets/icons/delete-blue.svg" alt="" />
					</v-btn>
				</div> -->
				<div v-if="activeUserGroupTab == 1" class="user-action-buttons">
					<v-btn v-if="showButtonBasedOnPermissions(item,'view')" class="btn-white icons" @click="viewUserGroup(item)">
						<img src="../../../../assets/icons/view-blue.svg" alt="" />
					</v-btn>
					<v-btn v-if="showButtonBasedOnPermissions(item,'update')" class="btn-white icons" @click="editUserGroup(item)" >
						<img src="../../../../assets/icons/edit-blue.svg" alt="" />
					</v-btn>

					<v-btn v-if="showButtonBasedOnPermissions(item,'delete')" class="btn-white icons" @click="deleteUserGroup(item)">
						<img src="../../../../assets/icons/delete-blue.svg" alt="" />
					</v-btn>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper mt-4" v-if="(activeUserGroupTab == 0 && getCustomerAdminsLoading) || (activeUserGroupTab == 1 && getGroupWithUsersAndPermissionsLoading)">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>
				<div class="no-data-wrapper" v-if="activeUserGroupTab == 0 && !getCustomerAdminsLoading">
                    No Users
                </div>
				<div class="no-data-wrapper" v-if="activeUserGroupTab == 1 && !getGroupWithUsersAndPermissionsLoading">
                    No User Groups
                </div>
			</template>
		</v-data-table>

		<Pagination
			v-if="typeof items !== 'undefined' && items.length > 35"
			:pageData.sync="page"
			:lengthData.sync="pageCount"
			:isMobile="isMobile"
		/>
		<EditUserGroupDialog
			:dialogEdit.sync="editUserGroupdialogValue"
			@close="closeEditUserGroupDialog"
			:userGroupData="ViewUserGroupData"
			:companyId="companyId"
		/>
		<ConfirmDialog :dialogData.sync="deleteUserGroupConfirmDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert">
				</div>
			</template>
			
			<template v-slot:dialog_title>
				<h2>Delete User Group </h2>
			</template>

			<template v-slot:dialog_content>
				<div>
					<p>
						Do you want to delete <span class="font-semi-bold">'{{deleteUserGroupData !== null ? deleteUserGroupData.group_name :'N/A'}}’</span> from user group?
						<span v-if="target_group_id !== null">Please mention where you want to move all the users within this user group:</span> 
					</p>

					<v-row no-gutters>
						<v-col cols="12" class="delete-user-group-height">
							<v-card-text class="px-0 pb-0">
								<p class="text-uppercase font-semi-bold mb-1" style="color: #819FB2; font-size: 10px;">User group</p>
								<v-select
									append-icon="mdi-chevron-down"
									:items="MoveUserToAnotherGroup"
									v-model="target_group_id"
									class="text-fields select-items"
									item-text="group_name"
									item-value="id"
									placeholder="Group Name"
									clear-icon=""
									outlined
									dense
									hide-details="auto"
									:menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true,closeOnContentClick: true }"
									clearable
								>
							</v-select>
							</v-card-text>
							
						</v-col>
					</v-row>
				</div>
			</template>

			<template v-slot:dialog_actions>
				<v-btn @click="deleteGroupAndMoveUserToOtherGroup" :disabled="deleteGroupLoading" class="btn-blue" text>
                   {{target_group_id !== null ? 'Move & Delete Group' :'Delete Group'}} 
				</v-btn>

				<v-btn class="btn-white" text @click="closeDeleteUserGroup">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>
	</div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import Search from "../../../Search.vue";
import Pagination from "../../../Pagination.vue";
import globalMethods from "../../../../utils/globalMethods";
import EditUserGroupDialog from "../../../SettingsComponents/Dialog/EditUserGroupDialog.vue"
import ConfirmDialog from "../../../Dialog/GlobalDialog/ConfirmDialog.vue"

export default {
	name: "UsersDesktopTable",
	props: ["items", "isMobile","companyId"],
	components: {
		Search,
		Pagination,
		EditUserGroupDialog,
		ConfirmDialog
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		search: "",
		headers: [
			{
				text: "User",
				align: "start",
				sortable: false,
				value: "name",
				width: "25%",
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
				width: "15%",
				fixed: true,
			},
			{
				text: "Last Activity At",
				align: "start",
				sortable: false,
				value: "activity_at",
				width: "18%",
				fixed: true,
			},
		],
		activeTab: 0,
		// user Group
		activeUserGroupTab:0,
		UserGroupTab:['User','User Groups'],
		headersUserGroup: [
			{
				text: "User Group",
				align: "start",
				sortable: false,
				value: "group_name",
				width: "20%",
				fixed: true,
				class:'user-group-header-class-for-text'
			},
			{
				text: "No. of Users",
				align: "start",
				sortable: false,
				value: "users_length",
				width: "13%",
				fixed: true,
				class:'user-group-header-class-for-text'
			},
			{
				text: "Users",
				align: "start",
				sortable: false,
				value: "users",
				width: "32%",
				fixed: true,
				class:'user-group-header-class-for-text'
			},
			{
				text: "PERMISSIONS",
				align: "start",
				sortable: false,
				value: "permissions",
				width: "18%",
				fixed: true,
				class:'user-group-header-class-for-permissions'
			},
			{
				text: "",
				align: "start",
				sortable: false,
				value: "actions",
				width: "18%",
				fixed: true,
			},
		],
		editUserGroupdialogValue:false,
		deleteUserGroupConfirmDialog:false,
		ViewUserGroupData:[],
		deleteUserGroupData:null,
		target_group_id:null
	}),
	computed: {
		...mapGetters({
			getCustomerAdminsLoading: "getCustomerAdminsLoading",
			getGroupWithUsersAndPermissions:'settings/getGroupWithUsersAndPermissions',
			getGroupWithUsersAndPermissionsLoading:'settings/getGroupWithUsersAndPermissionsLoading',
			getGroupDeleteWithoutMoveUserToOtherGroupLoading:'settings/getGroupDeleteWithoutMoveUserToOtherGroupLoading',
			getGroupDeleteAndMoveUserToOtherGroupLoading:'settings/getGroupDeleteAndMoveUserToOtherGroupLoading',
			getUserAndUserGroupActveTab:'settings/getUserAndUserGroupActveTab',
			getUser: 'getUser'
		}),
		_header(){
				let final_header = []
				if(this.activeUserGroupTab == 0){
					final_header = this.headers
				}else{
					final_header = this.headersUserGroup
				}
				return final_header
			},
			getGroupWithUsersAndPermissionsData(){
				let data = []
				if(this.activeUserGroupTab == 0){
					data = this.items
				}else{
					if(typeof this.getGroupWithUsersAndPermissions !== 'undefined' && 
                    this.getGroupWithUsersAndPermissions !== null){
						data = this.getGroupWithUsersAndPermissions
				}else{
					data = []
				}
				}
				
				return data
			},
			MoveUserToAnotherGroup(){
				let data = []
				if(this.deleteUserGroupData !== null){
					data = this.getGroupWithUsersAndPermissions.filter(val => val.id !== this.deleteUserGroupData.id )
				}else{
					data = []
				}
				return data
			},
			deleteGroupLoading(){
				let loading = false
				if(this.target_group_id !== null){
					loading = this.getGroupDeleteAndMoveUserToOtherGroupLoading
				}else{
					loading = this.getGroupDeleteWithoutMoveUserToOtherGroupLoading
				}
				return loading
			}
	},
	watch: {},
	methods: {
		...mapActions({
			fetchGroupWithUsersAndPermissions:'settings/fetchGroupWithUsersAndPermissions',
			deleteGrouAndMoveUserToOtherGroupApi:'settings/deleteGrouAndMoveUserToOtherGroupApi',
			deleteGrouWithoutMoveUserToOtherGroupApi:'settings/deleteGrouWithoutMoveUserToOtherGroupApi'
			}),
		...mapMutations({
			setUserGroupAsASelectiveTab:'settings/setUserGroupAsASelectiveTab'
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
			this.$emit("addUser");
		},
		addUserGroup(){
			this.$emit("addUserGroup");
		},
		editUser(user) {
			this.editedIndex = this.items.indexOf(user);
			this.editedItem = Object.assign({}, user);
			this.dialog = true;
		},
		close() {
			this.$emit("close");
		},
		deleteUser(item) {
			this.$emit("deleteUser", item);
		},
		closeDelete() {
			this.$emit("closeDelete");
		},
		resedInvitation(item) {
			this.$emit("resendInvitation", item);
		},
		save() {
			if (this.editedIndex > -1) {
				Object.assign(this.desserts[this.editedIndex], this.editedItem);
			} else {
				this.desserts.push(this.editedItem);
			}
			this.close();
		},
		// User Group Code Start
		deleteUserGroup(item){
			this.deleteUserGroupData = item
			this.deleteUserGroupConfirmDialog =true
		},
		async deleteGroupAndMoveUserToOtherGroup(){
			let payload = {
				target_group_id:this.target_group_id,
				company_id:this.deleteUserGroupData.company_id,
				group_id:this.deleteUserGroupData.id
			}
			try{
				if(this.target_group_id !== null){
					await this.deleteGrouAndMoveUserToOtherGroupApi(payload)
				}else{
					let senData={
						group_id :this.deleteUserGroupData.id,
						company_id:this.deleteUserGroupData.company_id,
					}
					await this.deleteGrouWithoutMoveUserToOtherGroupApi(senData)
				}
				await this.fetchGroupWithUsersAndPermissions(this.companyId)
				
				if(this.target_group_id !== null){
					this.notificationMessage("Group has been moved successfully")
				}else{
					this.notificationMessage("Group with permissions has been deleted successfully")
				}
				this.closeDeleteUserGroup()

			}catch(e){
				this.notificationError(e);
			}
		},
		closeDeleteUserGroup(){
			this.deleteUserGroupConfirmDialog = false
			this.target_group_id = null
			this.deleteUserGroupData = null
		},
		viewUserGroup(item){
			this.ViewUserGroupData= item
			this.editUserGroupdialogValue = true
		},
		closeEditUserGroupDialog(){
			this.editUserGroupdialogValue = false
		},
		editUserGroup(item){
			let role = 1
			if(item.group_name == 'Admin'){
				role = 1
			}else if(item.group_name == 'Manager'){
				role = 2
			}else if(item.group_name == 'Operator'){
				role = 3
			}
			else{
				role = 4
			}
			 if(this.$route.name === 'Settings'){
                this.$router.push(`/settings/newusergroup?group_id=${item.id}&add_edit=edit&role=${role}`)
            }
		},
		GetGroupWithUserAndPermissions(){
			if(this.companyId !== undefined && this.companyId !== 'undefined'){
			this.fetchGroupWithUsersAndPermissions(this.companyId)
			}
		},
		getName(name){
			if(name !== null && typeof name !== undefined && name !== 'undefined'){
				const first = name.split(' ').at(0);
				return first
			}else{
				return name
			}
			
		},
		GetPermissionTotal(item){
			let total = 0
			if(item !== undefined && item !== null){
				item.map(val => {
					if(val.is_view == 1 || val.is_add ==1 || val.is_update == 1 || val.is_delete == 1){
						total = total + 1
					}
				})
			}
			return total == 110 ? 'All' : total
		},
		showButtonBasedOnPermissions(item,from){
			if(item.permissions.length > 0){
				let result = item.permissions.filter(val => val.const_name == 'SETTINGS_COMPANY_PROFILE_USER_GROUPS')
				if(result.length == 0 ) return false
				if(from == 'view') return result[0].is_view == 1 ? true : false
				else if(from == 'update') return result[0].is_update == 1 ? true : false
				else{
					return result[0].is_delete == 1 ? true : false
				}
			}else{
				return false
			}
		},
		checkLoggedInUserData(activityDate) {
			let user = this.getUser;
			if (typeof user !== "object") {
				user = JSON.parse(this.getUser);
			}

			let roles = [];
			if (user.roles && user.roles.length > 0) {
				roles = user.roles.map((role) => role.name);
			}
			if(roles[0] === 'Customer Admin' && user.for_testing === 1) {
				return activityDate;
			} else if(roles[0] === 'Account Manager') {
				return activityDate;
			} else {
				return 'N/A';
			}
		}
	},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "settings/users");
		this.GetGroupWithUserAndPermissions()
		if(this.getUserAndUserGroupActveTab !== null && this.getUserAndUserGroupActveTab !== undefined && 
		this.getUserAndUserGroupActveTab !== 'undefined'){
			if(this.getUserAndUserGroupActveTab == 1){
			this.activeUserGroupTab = this.getUserAndUserGroupActveTab
			}
		}
	},
	beforeDestroy(){
		this.setUserGroupAsASelectiveTab(0)
	},
};
</script>

<style lang="scss">
// @import "../../../../assets/scss/pages_scss/settings/users.scss";
// @import "../../../../assets/scss/pages_scss/dialog/globalDialog.scss";
// @import "../../../../assets/scss/buttons.scss";
.company-profile-user-group-tab {
    .v-item-group {
        height: 40px;

        .v-tab {
            font-size: 12px;
            letter-spacing: 0;
            font-family: 'Inter-Medium', sans-serif !important;
            padding: 6px 12px;
			border-top: 1px solid #B3CFE0;
            border-bottom: 1px solid #B3CFE0;
        	border-left: 1px solid #B3CFE0;
			color: #4a4a4a !important;
			border-radius: 4px;
			min-width: 65px;

            &.v-tab--active {
                color: #0171A1 !important;
				background: #EBF2F5;
            }
			&:nth-child(2) {
                border-radius: 4px 0 0 4px;
            }
			&.company-profile-tab-last {
                border-right: 1px solid #B4CFE0;
                border-radius: 0 4px 4px 0;
                margin-right: 24px;
            }
        }
		.v-tabs-slider {
            background-color: #0171A1;
			height: 0;
        }
    }
}
.user-group-header-class-for-text {
	text-transform: capitalize !important;
	font-size: 14px !important;
}
.user-group-header-class-for-permissions{
	font-size: 12px !important;
}
.delete-user-group-height{
	.v-input.text-fields.v-text-field--outlined>.v-input__control>.v-input__slot {
    min-height: 40px !important;
    font-size: 14px;
}
}
</style>
