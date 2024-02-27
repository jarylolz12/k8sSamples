<template>
    <div style="height: 100%;">
        <div class="add_user-group-loading-wrapper" v-if="fetchAddEditUserGroupLoading">
			<v-progress-circular
				:size="40"
				color="#0171a1"
				indeterminate>
			</v-progress-circular>
		</div>

        <div v-if="!fetchAddEditUserGroupLoading" style="height: 100%;">
            <v-card class="transparent" elevation="0">
                <v-card-title class="px-6">
                    <p class="font-semi-bold mb-0" style="color: #4A4A4A; font-size: 24px;">{{formTitle}} </p>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="addOrUpdateLoading || !valid" @click="AddOrEditUserAndPermissions" class="btn-blue">
                        {{buttonTitle}}
                    </v-btn>
                    <v-btn :disabled="addOrUpdateLoading" class="btn-white ml-2" @click="cancelAddUserGroup">
                        Cancel
                    </v-btn>
                </v-card-title>
            </v-card>

            <v-card class="user-group-card-wrapper mx-6 px-2" elevation="0">
                <v-row no-gutters :class="$store.state.settings.createGroupType == 'UsePredefinedGroup' && formTitle == 'Add User Group' ? '':'mb-4'">
                    <v-col cols="4" class="py-0 my-auto">
                        <v-card-text class="pb-1 mt-2">
                            <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                                <p class="font-semi-bold py-0 my-0" style="color: #819FB2;text-transform: uppercase; font-size: 10px;">Name of group</p>
                                <div class="d-flex add-user-group-text-field">
                                    <v-text-field
                                        style="min-height:40px !important;min-width:400px"
                                        color="#002F44"
                                        width="400px"
                                        dense
                                        :rules="rules"
                                        class="text-fields  select-items"
                                        placeholder="Enter group name"
                                        outlined
                                        v-model="getGroupDetailsData.group_name"
                                        hide-details="auto">
                                    </v-text-field>
                                    <v-btn v-if="formTitle == 'Add User Group'" @click="usePredefineGroup" style="min-height:40px !important" class="btn-white ml-2">
                                        {{$store.state.settings.createGroupType == 'CreateYourOwnGroup' ? 'Use Predefined Group' :'Change Predefined Group'}}
                                    </v-btn>
                                </div> 
                            </v-form>
                        </v-card-text>
                    </v-col>
                    <v-col class="py-0 my-0" v-if="$store.state.settings.createGroupType == 'UsePredefinedGroup' && formTitle == 'Add User Group'" cols="12">
                        <p class="ml-4" style="color: #6D858F;">Currently using predefined user group: <span v-if="groupType.length" style="font-weight: 600;">{{getGroupDetailsData.group_name}}</span></p>
                    </v-col>
                </v-row>

                <v-tabs
                    class="px-2 mx-2"
                    v-model="activeUserGroupTab"
                    background-color="">

                    <v-tab class="text-capitalize font-semi-bold" style="color: #0171A1; letter-spacing:0;" v-for="(tab, index) in UserGroupTab" :key="index">
                        {{tab}}
                    </v-tab>
                </v-tabs>

                <v-divider style="border-color: #EBF2F5;" class="mx-4"></v-divider>

                <div v-if="activeUserGroupTab == 0">
                    <div class="add-user-by-searching-list">
                        <v-card-title>
                            <div>
                                <v-autocomplete
                                    class="text-fields select-items"
                                    v-model="selectedUsersList"
                                    :items="getAllAdmins"
                                    outlined
                                    append-icon=""
                                    item-text="name"
                                    item-value="name"
                                    background-color="white"
                                    chips
                                    deletable-chips
                                    return-object
                                    multiple
                                    placeholder="Add Users by searching the user list"
                                    :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true,maxHeight: 480,maxWidth:500 }"
                                    hide-details="auto"
                                    ref="searchingUserGroupDropdownRef">
                                    <template v-slot:prepend-inner>
                                        <v-icon size="20" color="#4A4A4A" class="my-auto">mdi-magnify</v-icon>
                                    </template>
                                    <template v-slot:selection="{ item,index }">
                                        <v-chip v-if="index == 0 || index == 1" close @click:close="removeItem(index)">
                                            <span class="name">{{ item.name }}</span>
                                            <!-- <button @click.stop="removeCustomerLists(item, 'single')" class="btn-icon-wrapper">
                                                <v-icon>mdi-close</v-icon>
                                            </button> -->
                                        </v-chip>
                                        <div style="color:#4A4A4A;" class="mr-1 body-2" v-if="index == 2">
                                        +{{selectedUsersList.length -2 }} Others
                                        </div>
                                    </template>
                                    <template v-slot:item="{ item, on, attrs }">
                                        <v-list-item v-on="on" v-bind="attrs" #default="{ active }">
                                            <v-list-item-action class="ml-0 mr-2">
                                                <v-checkbox :input-value="active"></v-checkbox>
                                            </v-list-item-action>

                                            <v-list-item-content>
                                                <v-list-item-title class="d-flex">           
                                                    <p class="mb-1 mt-1 font-medium" style="color: #4A4A4A;">{{ item.name }}</p>  
                                                    <v-spacer></v-spacer>         
                                                    <p class="mb-1 mt-1" style="color: #6D858F;">{{ item.email}}</p>
                                                </v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </template>
                                    <template v-slot:append-item>
                                            <v-divider></v-divider>
                                            <v-card class="d-flex px-4 py-3 add-Users-by-searching-the-user-list" flat
                                                style="border-top-right-radius: 0; border-top-left-radius: 0; border-top:2px solid #F5F9FC;">
                                                <!-- <v-btn :disabled="getAddUsersInGroupLoading || selectedUsersList.length == 0" @click="AddUsersInGroup" class="btn-blue mr-2">
                                                    Add ({{selectedUsersList.length}}) User
                                                </v-btn> -->
                                                <v-btn :disabled="selectedUsersList.length == 0 || (formtitle == 'edit' && getAddUsersInGroupLoading)" @click="addUsersInGroupTemporary" class="btn-blue mr-2">
                                                    Add ({{selectedUsersList.length}}) User
                                                </v-btn>
                                                <v-btn  @click="closeSearchingUserListDropDown" class="btn-white">
                                                    Cancel
                                                </v-btn>
                                            </v-card>                               
                                        </template>
                                        <template v-slot:no-data>
                                            <div class=""
                                                style="min-height: 40px; font-size: 14px;">
                                                <div class="" style="padding: 12px;color: #6D858F;">
                                                <i>No user found</i> 
                                                </div>
                                                <v-divider></v-divider>
                                                <div style="padding :4px 3px;">
                                                    <v-btn @click="AddNewUser"  class="text-capitalize px-2" text style="color: #0171A1;letter-spacing: normal">
                                                    <span class="font-medium">+ Add New User</span>
                                                    </v-btn>
                                                </div>
                                            </div>
                                        </template>
                                </v-autocomplete>
                            </div>
                            <v-spacer></v-spacer>

                            <v-btn :disabled="selectedGroups.length == 0" class="btn-white" style="min-height:40px !important" @click="removeMultipleUserGroup">
                                Remove <span v-if="selectedGroups.length > 0">({{selectedGroups.length}})</span> User
                            </v-btn>
                        </v-card-title>
                    </div>

                    <v-divider style="border-color: #EBF2F5;" class="mx-4"></v-divider>

                    <v-data-table
                        :headers="userHeader"
                        :items="UserGroup"
                        :page.sync="page"
                        :items-per-page="itemsPerPage"
                        :search="search"
                        show-select
                        class="elevation-0 px-4"
                        hide-default-footer
                        v-model="selectedGroups"
                        mobile-breakpoint="769">
                        <template v-slot:[`item.created_at`]="{ item }">
                            <div>
                                {{AddedBydateSet(item.created_at)}}
                            </div>
                        </template>

                        <template v-slot:[`item.group_added_by_user`]="{ item }">
                            <div>
                                <p class="mb-0 d-flex align-center" v-if="item.isCurrent">
                                    You <span class=" ml-2 pt-1"><img src="../../../assets/icons/CustomerCurrent.svg" alt=""></span>
                                </p>

                                <p class="mb-0 d-flex align-center" v-else>
                                    {{item.group_added_by_user !== undefined && item.group_added_by_user !== null ? item.group_added_by_user.name :'--'}}
                                </p>
                            </div>
                        </template>

                        <template v-slot:[`item.actions`]="{ item }">
                            <div class="d-flex justify-end">
                                <v-btn :style="selectedGroups.length > 0 ? 'pointer-events:none;background-color:inherit':''" style="height:30px !important;border: 1px solid #B4CFE0;border-radius: 4px;background:#fff;color: #0171A1;font-weight:500" class="btn-white text-capitalize" elevation="0" @click="removeUser(item)">
                                    Remove
                                </v-btn>
                            </div>
                        </template>

                        <template v-slot:no-data>
                            <div class="loading-wrapper" v-if="getGroupUsersLoading">
                                <v-progress-circular :size="40" color="#0171a1" indeterminate></v-progress-circular>
                            </div>
                            <div class="no-data-wrapper" v-if="!getGroupUsersLoading">
                                No Users
                            </div>
                        </template>
                    </v-data-table>
                </div>

                <div v-if="activeUserGroupTab == 1">
                    <div class="d-flex px-4 my-4">
                        <v-spacer></v-spacer>
                        <v-btn v-if="expensionPanelParentArray.length >0" @click="hideAll" class="btn-white">
                            <v-icon>mdi-chevron-up</v-icon> Hide All
                        </v-btn>
                        <v-btn v-if="expensionPanelParentArray.length == 0" @click="showAll" class="btn-white">
                            <v-icon>mdi-chevron-down</v-icon> Show All
                        </v-btn>
                        <div class="search-component ml-2">
                            <Search
                                placeholder="Search Permission"
                                className="search"
                                :inputData.sync="search" />
                        </div>
                    </div>

                    <v-row class="add-usergroup-expension-panel mx-3" v-if="allModulesData.length > 0" no-gutters>
                            <!-- <v-col cols="3" class="px-4 pr-8"> -->
                                <!-- <h3 class="mb-3" style="color: #4A4A4A;font-weight: 600;">{{permission.name}}</h3> -->
                                <!-- <p style="color: #4A4A4A;">Ability to view, add, update and delete shipments.</p>
                        </v-col> -->
                        <v-col cols="12" class="add-user-group-checkbox-change" >
                            <v-expansion-panels v-model="expensionPanelParentArray" flat :ripple="false" multiple>
                                <v-expansion-panel style="background-color: transparent;" :style="allModulesData.length !== index + 1 ? 'border-bottom:1px solid #D8E7F0':''" class="pr-16" v-for="(permission,index) in allModulesData" :key="index" >
                                    <v-expansion-panel-header hide-actions class="py-0" style="min-height: 40px !important;">
                                        <template v-slot:default="{open}">
                                            <v-row no-gutters :class="!open ? 'py-4':''">
                                                <v-col cols="4">
                                                    <p class="font-semi-bold mb-0" style="font-size: 16px; color: #4a4a4a;">{{permission.module_name}}</p>
                                                    <p v-if="!open" style="color: #4A4A4A;line-height: 20px; color: #4a4a4a;" class="pt-2">{{permission.module_description}}</p>
                                                </v-col>

                                                <v-col cols="8" class="text--secondary">
                                                    <v-fade-transition leave-absolute>
                                                        <v-row class="mx-0 px-0" >
                                                            <v-col cols="4" class="d-flex">
                                                                <v-icon>
                                                                    <template v-if="open">mdi-chevron-up</template>
                                                                    <template v-else>mdi-chevron-down</template>
                                                                </v-icon>
                                                                <p class="my-auto font-semi-bold" style="font-size: 16px;" :style="open ? 'color:#4A4A4A;':'color: #6D858F;'">All Permissions</p>
                                                            </v-col>

                                                            <v-col class="" cols="2">
                                                                <v-checkbox
                                                                    @click.native.capture="changeStatus($event)"
                                                                    class="my-0 text-center"
                                                                    :off-icon="'mdi-minus-box'"
                                                                    v-bind:false-value="0" v-bind:true-value="1"
                                                                    v-model="allModulesData[index].is_display_view"
                                                                    @change="selectDeslectAndViewAllPermissions(allModulesData[index].is_display_view,index,'view')"
                                                                    hide-details color="green">
                                                                    <template slot="label">
                                                                        <p class="my-auto" style="color: #6D858F;font-weight: 500;">View</p>
                                                                    </template>
                                                                </v-checkbox>
                                                            </v-col>

                                                            <v-col cols="2">
                                                                <v-checkbox
                                                                    :off-icon="'mdi-minus-box'"
                                                                    v-bind:false-value="0" v-bind:true-value="1"
                                                                    @click.native.capture="changeStatus($event)"
                                                                    class="my-0 text-center"
                                                                    v-model="allModulesData[index].is_display_add"
                                                                    hide-details color="green"
                                                                    @change="selectDeslectAndViewAllPermissions(allModulesData[index].is_display_add,index,'add')">
                                                                    <template slot="label">
                                                                        <p class="my-auto" style="color: #6D858F;font-weight: 500;">Add</p>
                                                                    </template>
                                                                </v-checkbox>
                                                            </v-col>

                                                            <v-col cols="2">
                                                                <v-checkbox
                                                                    :off-icon="'mdi-minus-box'"
                                                                    v-bind:false-value="0" v-bind:true-value="1"
                                                                    @click.native.capture="changeStatus($event)"
                                                                    class="my-0 text-center"
                                                                    v-model="allModulesData[index].is_display_update"
                                                                    :disabled="getGroupDetailsData.group_name == 'Manager'"
                                                                    hide-details color="green"
                                                                    @change="selectDeslectAndViewAllPermissions(allModulesData[index].is_display_update,index,'update')">
                                                                    <template slot="label">
                                                                        <p class="my-auto" style="color: #6D858F;font-weight: 500;">Update</p>
                                                                    </template>
                                                                </v-checkbox>
                                                            </v-col>

                                                            <v-col cols="2">
                                                                <v-checkbox
                                                                    :off-icon="'mdi-minus-box'"
                                                                    v-bind:false-value="0" v-bind:true-value="1"
                                                                    @click.native.capture="changeStatus($event)"
                                                                    class="my-0 text-center"
                                                                    v-model="allModulesData[index].is_display_delete"
                                                                    hide-details color="green"
                                                                    @change="selectDeslectAndViewAllPermissions(allModulesData[index].is_display_delete,index,'delete')">
                                                                    <template slot="label">
                                                                        <p class="my-auto" style="color: #6D858F;font-weight: 500;">Delete</p>
                                                                    </template>
                                                                </v-checkbox>
                                                            </v-col>
                                                        </v-row>
                                                    </v-fade-transition>
                                                </v-col>
                                            </v-row>
                                        </template>
                                    </v-expansion-panel-header>

                                    <v-expansion-panel-content class="px-0 mx-0">
                                        <v-row no-gutters>
                                            <v-col cols="4" class="my-0">
                                                <p style="color: #4A4A4A; line-height: 20px;">{{permission.module_description}}</p>
                                            </v-col>

                                            <v-col cols="8" v-if="permission.sub_modules.length > 0">
                                                <v-expansion-panels v-model="sub_expensionPanel[index]" :ripple="false" multiple flat>
                                                    <v-expansion-panel v-for="(sub_permission,sub_index) in permission.sub_modules" :key="sub_index" class="px-0 mx-0 mt-0">
                                                        <v-expansion-panel-header hide-actions class="py-0" style="min-height: 40px !important;">
                                                            <template v-slot:default="{open}">
                                                                <v-row no-gutters>
                                                                    <v-col cols="12" class="d-flex pl-5 py-3" :style="!open && permission.sub_modules.length !== sub_index +1 ? 'border-bottom:1px solid #D8E7F0;border-top:1px solid #D8E7F0':''">
                                                                        <v-icon class="">
                                                                            <template v-if="open">mdi-chevron-up</template>
                                                                            <template v-else>mdi-chevron-down</template>
                                                                        </v-icon>
                                                                        <h3 class="my-auto font-regular" style="font-size: 14px;" :style="open ? 'color:#4A4A4A':'color: #6D858F;'">{{sub_permission.module_name}}</h3>
                                                                    </v-col>
                                                                </v-row>
                                                            </template>
                                                        </v-expansion-panel-header>

                                                        <v-expansion-panel-content :class="permission.sub_modules.length !== sub_index +1 ? '':'pb-4'" class="px-0 mx-0">
                                                            <v-row v-for="(child_permission,child_index) in  sub_permission.sub_modules" :key="child_index">
                                                                <v-col cols="12" class="text--secondary">
                                                                    <v-fade-transition leave-absolute>
                                                                        <v-row class="">
                                                                            <v-col cols="3" offset="1" class="my-auto py-2 pl-0">
                                                                                <h3 class="text-start" style="font-size:12px;text-transform:uppercase;color: #6D858F;">{{child_permission.module_name}}</h3>
                                                                            </v-col>

                                                                            <v-col cols="2" class="py-2">
                                                                                <v-checkbox
                                                                                    v-bind:false-value="0" v-bind:true-value="1"
                                                                                    class="my-0 text-center"
                                                                                    hide-details color="green"
                                                                                    v-model="child_permission.is_display_view">

                                                                                    <template slot="label">
                                                                                        <p class="my-auto" style="color: #6D858F;font-weight: 500;">View</p>
                                                                                    </template>
                                                                                </v-checkbox>
                                                                            </v-col>

                                                                            <v-col cols="2" class="py-2">
                                                                                <v-checkbox
                                                                                    v-bind:false-value="0" v-bind:true-value="1"
                                                                                    class="my-0 text-center"
                                                                                    hide-details color="green"
                                                                                    v-model="child_permission.is_display_add">
                                                                                    <template slot="label">
                                                                                        <p class="my-auto" style="color: #6D858F;font-weight: 500;">Add</p>
                                                                                    </template>
                                                                                </v-checkbox>
                                                                            </v-col>

                                                                            <v-col cols="2" class="py-2">
                                                                                <v-checkbox
                                                                                    v-if="getGroupDetailsData.group_name !== 'Manager'"
                                                                                    v-bind:false-value="0" v-bind:true-value="1"
                                                                                    class="my-0 text-center"
                                                                                    hide-details color="green"
                                                                                    v-model="child_permission.is_display_update">
                                                                                    <template slot="label">
                                                                                        <p class="my-auto" style="color: #6D858F;font-weight: 500;">Update</p>
                                                                                    </template>
                                                                                </v-checkbox>
                                                                            </v-col>

                                                                            <v-col cols="2" class="py-2">
                                                                                <v-checkbox
                                                                                    v-bind:false-value="0" v-bind:true-value="1"
                                                                                    class="my-0 text-center"
                                                                                    hide-details color="green"
                                                                                    v-model="child_permission.is_display_delete">
                                                                                    <template slot="label">
                                                                                        <p class="my-auto" style="color: #6D858F;font-weight: 500;">Delete</p>
                                                                                    </template>
                                                                                </v-checkbox>
                                                                            </v-col>
                                                                        </v-row>
                                                                    </v-fade-transition>
                                                                </v-col>
                                                            </v-row>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-col>
                                        </v-row>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-col>
                    </v-row>   
                </div>
            </v-card>

            <ConfirmDialog :dialogData.sync="removeUserDialog">
                <template v-slot:dialog_icon>
                    <div class="header-wrapper-close">
                        <img src="@/assets/icons/icon-delete.svg" alt="alert">
                    </div>
                </template>
                
                <template v-slot:dialog_title>
                    <h2 v-if="removeUserData !== null">Remove User </h2>
                    <h2 v-else>Remove Multiple Users</h2>
                </template>

                <template v-slot:dialog_content>
                    <div v-if="removeUserData !== null">
                        <p>
                        Do you want to remove '{{removeUserData.name}}' <span v-if="formtitle == 'edit'"> from <span style="font-weight:600">‘{{getGroupDetailsData.group_name !== '' ? getGroupDetailsData.group_name :'N/A'}}’ </span> user group?</span>
                        </p>
                    </div>
                    <div v-else>
                        <p>Do you want to remove {{selectedGroups.length}} users <span v-if="formtitle == 'edit'"> from <span style="font-weight:600">{{getGroupDetailsData.group_name !== '' ? getGroupDetailsData.group_name :'N/A'}} </span> User Group? </span></p>
                    </div>
                </template>

                <template v-slot:dialog_actions>
                    <v-btn :disabled="getRemoveUsersFromGroupLoading" @click="removeUsersFromGroup" class="btn-blue" text>
                        Remove
                    </v-btn>

                    <v-btn :disabled="getRemoveUsersFromGroupLoading" class="btn-white" text @click="closeRemoveUserDialog">
                        Cancel
                    </v-btn>
                </template>
            </ConfirmDialog>

            <!-- <AddUserGroupDialog
                :dialog.sync="addUserGroupDialogValue"
                @close="closeAddUserGroupDialog"
            /> -->
            <AddUserInviteDialog
                :dialog.sync="addUserGroupDialogValue"
                :userId="getLoggedUserId"
                :companyId="getCompnayId"
                @close="closeAddUserGroupDialog"
            />

            <NewUserGroupDialog
            :dialog.sync="addNewUserGroupDialog"
            @closeNewUserGroupDialog="closeNewUserGroupDialog"
            @setGroupPermissions="setGroupPermissions"
            :companyId="getCompnayId"
            :fromAddUser="true"
            @selectedValueUdate="selectedValueUdate"
            />
        </div>
    </div>
</template>

<script>
import ConfirmDialog from "../../Dialog/GlobalDialog/ConfirmDialog.vue"
// import AddUserGroupDialog from "../../SettingsComponents/Dialog/AddUserGroupDialog.vue"
import Search from "../../../components/Search.vue"
import NewUserGroupDialog from "../../SettingsComponents/Dialog/NewUserGroupDialog.vue"
import { mapActions, mapGetters, mapMutations } from 'vuex'
import globalMethods from '../../../utils/globalMethods'
import AddUserInviteDialog from "../../SettingsComponents/Dialog/AddUserInviteDialog.vue"
import moment from "moment"
    export default {
        components:{
            Search,
            // AddUserGroupDialog,
            ConfirmDialog,
            NewUserGroupDialog,
            AddUserInviteDialog
        },
        data(){
            return {
                valid: true,
                page: 1,
		        pageCount: 0,
		        itemsPerPage: 35,
		        search: "",
                activeUserGroupTab:0,
		        UserGroupTab:['Users','Permissions'],
                selectedUsersList:[],
                selectedGroups:[],
                userHeader:[
                    {
			        	text: "Name",
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
			        	value: "email",
			        	width: "20%",
			        	fixed: true,
			        },
                    {
			        	text: "Added At",
			        	align: "start",
			        	sortable: false,
			        	value: "created_at",
			        	width: "18%",
			        	fixed: true,
			        },
			        {
			        	text: "Added By",
			        	align: "start",
			        	sortable: false,
			        	value: "group_added_by_user",
			        	width: "18%",
			        	fixed: true,
			        },
                    {
			        	text: "",
			        	align: "end",
			        	sortable: false,
			        	value: "actions",
			        	width: "18%",
			        	fixed: true,
			        },
                ],
                expensionPanelParentArray:[],
                sub_expensionPanel:[],
                removeUserDialog:false,
                removeUserData:null,
                addUserGroupDialogValue:false,
                addNewUserGroupDialog:false,
                formtitle:null,
                group_id_is:null,
                groupDetailsObject:{
                    group_name: "",
                    group_description: "",
                },
                selectedRole:null,
                groupType:[],
                rules: [
                    (v) => !!v || 'Input is required.'
                ],
                fetchAddEditUserGroupLoading:false,
                UserGroup:[]
            }
        },
        computed:{
            ...mapGetters({
                getUser: "getUser",
                getCustomerAdmins: "getCustomerAdmins",
                getGroupPermissions:'settings/getGroupPermissions',
                getGroupUsers:'settings/getGroupUsers',
                getGroupUsersLoading:'settings/getGroupUsersLoading',
                getGroupWithUsersAndPermissionsLoading:'settings/getGroupWithUsersAndPermissionsLoading',
                getAddUsersInGroup:'settings/getAddUsersInGroup',
                getAddUsersInGroupLoading:'settings/getAddUsersInGroupLoading',
                getAddGroupLoading:'settings/getAddGroupLoading',
                getEditGroupLoading:'settings/getEditGroupLoading',
                getRemoveUsersFromGroupLoading:'settings/getRemoveUsersFromGroupLoading',
                getAddGroupPermissionsLoading:'settings/getAddGroupPermissionsLoading',
                getUpdateGroupPermissionsLoading:'settings/getUpdateGroupPermissionsLoading',
                getAllModules:'settings/getAllModules',
                getGroupWithUsersAndPermissions:'settings/getGroupWithUsersAndPermissions',
                getAllDefaultGroupTemplates:'settings/getAllDefaultGroupTemplates',
                getAddGroup:'settings/getAddGroup',
                getEditGroup:'settings/getEditGroup',
                getGroupTemplatePermissions:'settings/getGroupTemplatePermissions',
                getGroupDetails:'settings/getGroupDetails'
            }),
            formTitle(){
                return this.formtitle == 'add' ? 'Add User Group' :'Edit User Group'
            },
            buttonTitle(){
                return this.formtitle == 'add' ? 'Add User Group' :'Save'
            },
            addOrUpdateLoading(){
                let loading = false
                if(this.formtitle == 'add'){
                    if(this.getAddGroupLoading || this.getAddGroupPermissionsLoading){
                        loading = true
                    }else{
                        loading = false
                    }
                }else{
                    if(this.getEditGroupLoading || this.getUpdateGroupPermissionsLoading){
                        loading = true
                    }else{
                        loading = false
                    }
                }
                return loading
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
            
            allModulesData(){
                let data = []
                if(this.getAllModules !==null && this.getAllModules !=='undefined' &&
                    this.getAllModules !== undefined && this.getAllModules.length > 0){
                        data = this.getAllModules
                    }else{
                        data = []
                    }
                return data 
            },
            // getGroupUserData(){
            //     let data = []
			// 	if(typeof this.getGroupUsers !== 'undefined' && 
            //     this.getGroupUsers !== null){
            //         let userId;
			//         if (typeof this.getUser === "string") {
			// 	        userId = JSON.parse(this.getUser).id;
			//         } else {
			// 	        userId = this.getUser.id;
			//         }
			// 	    data = this.getGroupUsers?.map((item) => {
			// 	        return {
            //                 id:item.id,
            //                 user_id:item.user_id,
			// 	        	updated_at: item.updated_at,
			// 	        	created_at: item.created_at,
			// 	        	email: item.email,
			// 	        	isCurrent: item.user_id === userId,
			// 	        	name: item.name,
			// 	        	is_confirm: item.is_invite_confirm,
            //                 group_added_by_user:item.group_added_by_user
			// 	        };
			//         });
			// 	}else{
			// 		data = []
            //     }
			// 	return data
            // },
            getGroupPermissionsData(){
				let data = []
				if(typeof this.getGroupPermissions !== 'undefined' && 
                    this.getGroupPermissions !== null){
					data = this.getGroupPermissions
				}else{
					data = []
                }
				return data
			},
            getGroupDetailsData(){
                let data = {}
                if(this.getGroupDetails !== undefined && this.getGroupDetails !== 'undefined' && 
                this.getGroupDetails !== null){
                    data = this.getGroupDetails
                }
                else{
                    data = this.groupDetailsObject
                }
                return data
            },
            getAllAdmins(){
                 let data = []
				if(typeof this.getCustomerAdmins !== 'undefined' && 
                this.getCustomerAdmins !== null){
                    let userId;
			        if (typeof this.getUser === "string") {
				        userId = JSON.parse(this.getUser).id;
			        } else {
				        userId = this.getUser.id;
			        }
				    data = this.getCustomerAdmins?.map((item) => {
				        return {
                            id:item.id,
                            user_id:item.id,
				        	updated_at: item.updated_at,
				        	created_at: item.created_at,
				        	email: item.email,
				        	name: item.name,
                            isCurrent: item.user_id === userId,
				        	is_confirm: item.is_customer_invite_confirm,
                            customer_admin_pk:item.customer_admin_pk,
                            group_id:item.group_id,
                            group_name:item.group_name
				        };
			        });
				}else{
					data = []
                }
				return data
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
        watch:{
            addNewUserGroupDialog(val) {
			val || this.closeNewUserGroupDialog();
		    },
            groupType: function(newVal) {
                if(this.$store.state.settings.createGroupType == 'UsePredefinedGroup' && this.formTitle == 'Add User Group'){
                    this.getGroupDetailsData.group_name = newVal[0].name
                    this.getGroupDetailsData.group_description = newVal[0].description
                }else{
                    this.getGroupDetailsData.group_name 
                    this.getGroupDetailsData.group_description
                }
            }
        },
        methods:{
            ...mapActions({
                fetchGroupPermissions:'settings/fetchGroupPermissions',
                fetchGroupUsersApi:'settings/fetchGroupUsersApi',
                addGroupPermissionsApi:'settings/addGroupPermissionsApi',
                updateGroupPermissionsApi:'settings/updateGroupPermissionsApi',
                removeUsersFromGroupApi:'settings/removeUsersFromGroupApi',
                addUsersInGroupApi:'settings/addUsersInGroupApi',
                fetchAllModules:'settings/fetchAllModules',
                editGroupApi:'settings/editGroupApi',
                fetchGroupWithUsersAndPermissions:'settings/fetchGroupWithUsersAndPermissions',
                fetchGroupTemplatePermissions:'settings/fetchGroupTemplatePermissions',
                addGroupApi:'settings/addGroupApi',
                fetchGroupDetails:'settings/fetchGroupDetails',
                fetchUsersDetail:'fetchUsersDetail'
            }),
            ...mapMutations({
                setGroupUserDataEmpty:'settings/setGroupUserDataEmpty',
                setGroupPermissionsEmpty:'settings/setGroupPermissionsEmpty',
                setGroupDetailsDataEmpty:'settings/setGroupDetailsDataEmpty',
                setUserGroupAsASelectiveTab:'settings/setUserGroupAsASelectiveTab',
                emptyAddUserGroupData:'settings/emptyAddUserGroupData'
            }),
            ...globalMethods,
            AddedBydateSet(date){
                 let final_date = date 
                 if(moment(date).isAfter(moment().subtract(1, 'hours'))) {
                    final_date = moment(date).isAfter(moment().subtract(1, 'hours'))
                 }else {
                    final_date = moment(date).format("MMM Do YY"); 
                 }
                return final_date
            },
            removeUser(item){
                this.removeUserDialog = true
                this.removeUserData = item
            },
            removeMultipleUserGroup(){
                this.removeUserDialog = true
            },
            closeRemoveUserDialog(){
                this.selectedGroups = []
                this.removeUserDialog = false
                this.removeUserData = null
            },
            // async removeUsersFromGroup(){
            //     let payload = {
            //         group_id :+this.group_id_is,
            //         user_ids:this.selectedGroups.map(val => val.user_id),
            //         company_id:this.getCompnayId
            //     }
            //     if(this.selectedGroups.length == 0 && this.removeUserData !== null){
            //         let local_Users = this.getGroupUsers?.filter(v => v.user_id == this.removeUserData.user_id)
            //         if(local_Users.length == 0){
            //             return this.UserGroup =  this.UserGroup.filter(val => val.user_id !== this.removeUserData.user_id)
            //         }else{
            //             payload.user_ids = [this.removeUserData.user_id]
            //         }
            //     }
            //     try{
            //         await this.removeUsersFromGroupApi(payload)
            //         this.notificationMessage("Users are removed from the group successfully")
            //         let payloadGroup = {
            //             group_id : +this.group_id_is,
            //             company_id:this.getCompnayId
            //         }
            //         await this.fetchGroupUsersApi(payloadGroup)
            //         this.setAllUsersData()
            //         this.closeRemoveUserDialog()
            //     }catch(e){
            //         this.notificationError(e)
            //     }
            // },
            async removeUsersFromGroup(){
                let payload = {
                    group_id :+this.group_id_is,
                    user_ids:this.selectedGroups.map(val => val.user_id),
                    company_id:this.getCompnayId
                }
                if(this.selectedGroups.length == 0 && this.removeUserData !== null){
                    if(this.formtitle == 'add'){
                        this.UserGroup =  this.UserGroup.filter(val => val.user_id !== this.removeUserData.user_id)
                        this.selectedUsersList = this.selectedUsersList.filter(val => val.user_id !== this.removeUserData.user_id)
                        return this.closeRemoveUserDialog()
                    }else{
                        payload.user_ids = [this.removeUserData.user_id]
                    }
                }else{
                    if(this.formtitle == 'add'){
                        this.UserGroup = this.UserGroup?.filter(val =>{
                            return !this.selectedGroups?.some(y => y.user_id === val.user_id)
                        })
                        this.selectedUsersList = this.selectedUsersList?.filter(val =>{
                            return !this.selectedGroups?.some(y => y.user_id === val.user_id)
                        })    
                        return this.closeRemoveUserDialog()
                    }
                }
                try{
                    await this.removeUsersFromGroupApi(payload)
                    this.notificationMessage("Users are removed from the group successfully")
                    let payloadGroup = {
                        group_id : +this.group_id_is,
                        company_id:this.getCompnayId
                    }
                    await this.fetchGroupUsersApi(payloadGroup)
                    this.setAllUsersData()
                    this.closeRemoveUserDialog()
                }catch(e){
                    this.notificationError(e)
                }
            },
            closeSearchingUserListDropDown(){
                this.$refs.searchingUserGroupDropdownRef.blur();
                this.selectedUsersList = []
            },
            cancelAddUserGroup(){
                this.setGroupUserDataEmpty([])
                this.setGroupPermissionsEmpty([])
                this.setGroupDetailsDataEmpty({
                    group_name: "",
                    group_description: "",
                },)
                this.$router.push(`/settings/?tab=users`);
			    this.$store.dispatch("page/setCurrentSettingsTab", 0);
                this.setUserGroupAsASelectiveTab(1)
                this.emptyAddUserGroupData([])
            },
            changeStatus(event) {
                event.stopPropagation()
            },
            removeItem(index) {
                if (index !== null) {
                    this.selectedUsersList.splice(index, 1)
                }
            },
            showAll () {
               this.expensionPanelParentArray = this.allModulesData.map((k, i) => i)
               this.sub_expensionPanel = this.allModulesData.map((values,ind)=>{
                return this.allModulesData[ind].sub_modules.map((val,index)=> index)
               })
            },
            // Reset the panel
            hideAll () {
                this.expensionPanelParentArray = []
                this.sub_expensionPanel = this.allModulesData.map(() => [])
            }, 
            // Add User Group Dialog
            AddNewUser(){
                this.addUserGroupDialogValue =true
            },
            closeAddUserGroupDialog(){
                this.addUserGroupDialogValue =false
            },
            usePredefineGroup(){
                this.addNewUserGroupDialog =true
            },
            closeNewUserGroupDialog(){
			    this.addNewUserGroupDialog =false
		    },
            selectDeslectAndViewAllPermissions(value,index,from){
                this.allModulesData[index].sub_modules?.filter((child_val,child_index)=>{
                    this.allModulesData[index].sub_modules[child_index].sub_modules?.filter((sub_child_val,sub_child_index)=>{
                        if(from == 'view'){
                            this.allModulesData[index].sub_modules[child_index].sub_modules[sub_child_index].is_display_view = value
                        }
                        else if(from == 'add'){
                            this.allModulesData[index].sub_modules[child_index].sub_modules[sub_child_index].is_display_add = value
                        }
                        else if(from == 'update'){
                            this.allModulesData[index].sub_modules[child_index].sub_modules[sub_child_index].is_display_update = value
                        }else{
                            this.allModulesData[index].sub_modules[child_index].sub_modules[sub_child_index].is_display_delete = value
                        }
                    })
                })
            },
            /* eslint-disable */
            setAddOrUpdatePermissionPayloadForApi(){
                let value =  this.allModulesData.map(({id,is_display_view,is_display_add,is_display_update,is_display_delete,sub_modules,...rest})=>{
                    return { 
                        module_id:id,
                        is_view:is_display_view,
                        is_add:is_display_add,
                        is_update:is_display_update,
                        is_delete:is_display_delete,
                        sub_modules:sub_modules?.map(({id,is_display_view,is_display_add,is_display_update,is_display_delete,sub_modules, ...v})=>{
                            return {
                                module_id:id,
                                is_view:is_display_view,
                                is_add:is_display_add,
                                is_update:is_display_update,
                                is_delete:is_display_delete,
                                sub_modules:sub_modules?.map(({id,is_display_view,is_display_add,is_display_update,is_display_delete,...child_val}) =>{
                                    return {
                                        module_id:id,
                                        is_view:is_display_view,
                                        is_add:is_display_add,
                                        is_update:is_display_update,
                                        is_delete:is_display_delete
                                    }
                                })
                            }
                        })
                    }
                })
                let filteredArr = []
                    value.map((Arr1) => {
                        const parentOne = { ...Arr1 };
                        delete parentOne.sub_modules;
                        filteredArr.push(parentOne);

                        Arr1?.sub_modules.map((Arr2) => {
                            const parentTwo = { ...Arr2 };
                            delete parentTwo.sub_modules;
                            filteredArr.push(parentTwo);

                        Arr2?.sub_modules?.map((Arr3) => {
                            const parentThree = { ...Arr3 };
                            delete parentThree.sub_modules;
                            filteredArr.push(parentThree);
                        });
                    });
                });
                return filteredArr
            },
            /* eslint-enable */
            // Add Group Or Update Group with group users and permission add or update api
            async AddOrEditUserAndPermissions(){
                if(this.formtitle == 'add'){
                    try{
                        let add_group = {
                                company_id:this.getCompnayId,
                                group_name:this.getGroupDetailsData.group_name,
                                // group_description:this.getGroupDetailsData.group_description,
                                group_description:this.getGroupDetailsData.group_name
                            }
                            await this.addGroupApi(add_group)
                            if(this.getAddGroup !== undefined && this.getAddGroup !== 'undefined' && this.getAddGroup !== null){
                                let payload = {
                                    group_id :this.getAddGroup.id,
                                    permissions:[]
                                }
                                    payload.permissions = this.setAddOrUpdatePermissionPayloadForApi()
                                await this.addGroupPermissionsApi(payload)
                                await this.AddUsersInGroup()
                                // let payloadGroup = {
                                //     group_id : +this.group_id_is,
                                //     company_id:this.getCompnayId
                                // }
                                // await this.fetchGroupUsersApi(payloadGroup)
                                await this.fetchGroupPermissions(this.getAddGroup.id)
                                this.notificationMessage('Group added successfully')
                                this.setAllUsersData()
                                this.setGroupPermissions()
                                this.setGroupDetailsDataEmpty({
                                    group_name: "",
                                    group_description: "",
                                },)
                                if (this.$route.name === 'AddUserGroup') {
                                    this.cancelAddUserGroup()
                                    // this.$store.dispatch("page/setCurrentSettingsTab", 0);
                                    // this.setUserGroupAsASelectiveTab(1)
                                    // this.$router.push({ path: '/settings/?tab=users' })
                            
                                }
                                // this.setGroupDetailsDataEmpty({
                                //     group_name: "",
                                //     group_description: "",
                                // },)
                                // if(this.group_id_is == null){
                                //     this.group_id_is = this.getAddGroup.id
                                // }
                                // if (this.$route.name === 'AddUserGroup') {
                                //     this.$router.push({ path: '/settings/?tab=users' })
                                        // this.setUserGroupAsASelectiveTab(1)
                                //     this.$store.dispatch("page/setCurrentSettingsTab", 0);
                                // }
                            }
                            
                    }catch(e){
                        this.notificationError(e)
                    }
                    
                }else{
                    try{
                        let edit_group = {
                                company_id:this.getCompnayId,
                                group_name:this.getGroupDetailsData.group_name,
                                // group_description:this.getGroupDetailsData.group_description,
                                group_description:this.getGroupDetailsData.group_name,
                                group_id:+this.group_id_is
                            }
                        await this.editGroupApi(edit_group)
                        let payload ={
                            group_id:+this.group_id_is,
                            permissions:[]
                        }
                            payload.permissions = this.setAddOrUpdatePermissionPayloadForApi()
                        await this.updateGroupPermissionsApi(payload)
                        // await this.AddUsersInGroup()
                        // let payloadGroup = {
                        //     group_id : +this.group_id_is,
                        //     company_id:this.getCompnayId
                        // }
                        // await this.fetchGroupUsersApi(payloadGroup)
                        await this.fetchGroupPermissions(+this.group_id_is)
                        this.notificationMessage('Group has been updated successfully')
                        this.setAllUsersData()
                        this.setGroupPermissions()
                        // this.setGroupDetailsDataEmpty({
                        //     group_name: "",
                        //     group_description: "",
                        // },)
                        if (this.$route.name === 'AddUserGroup') {
                            this.cancelAddUserGroup()
                            // this.$store.dispatch("page/setCurrentSettingsTab", 0);
                            // this.setUserGroupAsASelectiveTab(1)
                            // this.$router.push({ path: '/settings/?tab=users' })
                            
                        }
                    }catch(e){
                        this.notificationError(e)
                    }
                }
            },
            setAllUsersData(){
                if(typeof this.getGroupUsers !== 'undefined' && 
                this.getGroupUsers !== null){
                    let userId;
			        if (typeof this.getUser === "string") {
				        userId = JSON.parse(this.getUser).id;
			        } else {
				        userId = this.getUser.id;
			        }
                    this.UserGroup = this.getGroupUsers?.map((item) => {
				        return {
                            id:item.id,
                            user_id:item.user_id,
				        	updated_at: item.updated_at,
				        	created_at: item.created_at,
				        	email: item.email,
				        	isCurrent: item.user_id === userId,
				        	name: item.name,
				        	is_confirm: item.is_invite_confirm,
                            group_added_by_user:item.group_added_by_user
				        };
			        });
                }else{
                    this.UserGroup
                }
            },
            // Add User payload check for sending data in api 
            addUserPayloadDataFunction(){
                const uniqueItems = this.UserGroup?.filter(val =>{
                       return !this.getGroupUsers?.some(y => y.user_id === val.user_id)
                    } )
                    if(uniqueItems.length > 0){
                        return uniqueItems 
                    }
                    return []
            },
            // Temporary show data in user Tab before add User api
            addUsersInGroupTemporary(){
                if(this.selectedUsersList.length > 0){
                    if(this.formtitle == 'add'){
                        const uniqueItems = this.selectedUsersList?.filter(val =>{
                            return !this.UserGroup?.some(y => y.user_id === val.user_id)
                        })
                        if(uniqueItems.length > 0){
                            uniqueItems.map(val => {
                                this.UserGroup.push(val)
                            })    
                        }
                        this.$refs.searchingUserGroupDropdownRef.blur();
                    }else{
                        this.AddUsersInGroup()
                    }
                }
            },
            // add user api
            /*
            async AddUsersInGroup(){
                let valueCheck = this.addUserPayloadDataFunction()
                console.log('valueCheck',valueCheck)
                if(valueCheck.length > 0){
                    try{
                        let payload = {
                            user_ids:valueCheck.map(val => val.id),
                            company_id:this.getCompnayId,
                            group_id:this.getAddGroup.id
                        }
                        await this.addUsersInGroupApi(payload)
                        if(this.getAddUsersInGroup.success == true){
                            // let payloadGroup = {
                            //     group_id : +this.group_id_is,
                            //     company_id:this.getCompnayId
                            // }
                            // await this.fetchGroupUsersApi(payloadGroup)
                            this.$refs.searchingUserGroupDropdownRef.blur();
                            // this.notificationMessage(this.getAddUsersInGroup.message)
                            this.selectedUsersList=[]
                        }else{
                            this.notificationError(this.getAddUsersInGroup.message)
                        }
                    }catch(e){
                        this.notificationError(e)
                    }
                }
            },
            */
           async AddUsersInGroup(){
                try{
                    let payload = {
                        user_ids:this.UserGroup.map(val => val.id),
                        company_id:this.getCompnayId,
                        group_id:this.getAddGroup.id
                    }
                    if(this.formtitle == 'edit'){
                        payload.group_id = this.group_id_is
                        payload.user_ids = this.selectedUsersList.map(val => val.id)
                    }
                    else{
                        if(this.UserGroup.length == 0) return
                    }
                    await this.addUsersInGroupApi(payload)
                    if(this.getAddUsersInGroup.success == true){
                        let payloadGroup = {
                            group_id : +this.group_id_is,
                            company_id:this.getCompnayId
                        }
                        if(this.formtitle == 'edit'){
                            await this.fetchGroupUsersApi(payloadGroup)
                            this.setAllUsersData()
                            this.notificationMessage(this.getAddUsersInGroup.message)
                        }
                        
                        this.$refs.searchingUserGroupDropdownRef.blur();
                        this.selectedUsersList=[]
                    }else{
                        this.notificationError(this.getAddUsersInGroup.message)
                    }
                }catch(e){
                    this.notificationError(e)
                }
            },
            setGroupPermissions(){
                if(this.getGroupPermissionsData.length > 0 && this.allModulesData.length > 0){
                    this.allModulesData.map((modules,index) =>{
                        this.getGroupPermissionsData.map((perm)=>{
                            if(modules.id == perm.module_id){
                                // console.log("if",this.allModulesData[index].id)
                                this.allModulesData[index].is_display_view = perm.is_view
                                this.allModulesData[index].is_display_add = perm.is_add
                                this.allModulesData[index].is_display_update = perm.is_update
                                this.allModulesData[index].is_display_delete = perm.is_delete
                            }
                            else{
                                modules.sub_modules?.map((sub,i)=>{
                                    if(sub.id == perm.module_id){
                                        // console.log(this.allModulesData[index].sub_modules[i].id,"--",perm.module_id)
                                        this.allModulesData[index].sub_modules[i].is_display_view = perm.is_view
                                        this.allModulesData[index].sub_modules[i].is_display_add = perm.is_add
                                        this.allModulesData[index].sub_modules[i].is_display_update = perm.is_update
                                        this.allModulesData[index].sub_modules[i].is_display_delete = perm.is_delete
                                    }else{
                                        sub.sub_modules?.map((child_perm,child_i)=>{
                                            if(child_perm.id == perm.module_id ){
                                            // console.log(this.allModulesData[index].sub_modules[i].sub_modules[child_i].id,"--",perm.module_id)
                                            this.allModulesData[index].sub_modules[i].sub_modules[child_i].is_display_view = perm.is_view
                                            this.allModulesData[index].sub_modules[i].sub_modules[child_i].is_display_add = perm.is_add
                                            this.allModulesData[index].sub_modules[i].sub_modules[child_i].is_display_update = perm.is_update
                                            this.allModulesData[index].sub_modules[i].sub_modules[child_i].is_display_delete = perm.is_delete
                                            }
                                        })
                                    }
                                })
                            }
                        })
                    })
                }
            },
            setAllModulesBasedOnRole(){
                if(this.getGroupTemplatePermissions.data.length > 0 && this.allModulesData.length > 0){
                    this.allModulesData.map((modules,index) =>{
                        this.getGroupTemplatePermissions.data.map((perm)=>{
                            if(modules.id == perm.module_id){
                                // console.log("if",this.allModulesData[index].id)
                                this.allModulesData[index].is_display_view = perm.is_view
                                this.allModulesData[index].is_display_add = perm.is_add
                                this.allModulesData[index].is_display_update = perm.is_update
                                this.allModulesData[index].is_display_delete = perm.is_delete
                            }
                            else{
                                modules.sub_modules?.map((sub,i)=>{
                                    if(sub.id == perm.module_id){
                                        // console.log(this.allModulesData[index].sub_modules[i].id,"--",perm.module_id)
                                        this.allModulesData[index].sub_modules[i].is_display_view = perm.is_view
                                        this.allModulesData[index].sub_modules[i].is_display_add = perm.is_add
                                        this.allModulesData[index].sub_modules[i].is_display_update = perm.is_update
                                        this.allModulesData[index].sub_modules[i].is_display_delete = perm.is_delete
                                    }else{
                                        sub.sub_modules?.map((child_perm,child_i)=>{
                                            if(child_perm.id == perm.module_id ){
                                            // console.log(this.allModulesData[index].sub_modules[i].sub_modules[child_i].id,"--",perm.module_id)
                                            this.allModulesData[index].sub_modules[i].sub_modules[child_i].is_display_view = perm.is_view
                                            this.allModulesData[index].sub_modules[i].sub_modules[child_i].is_display_add = perm.is_add
                                            this.allModulesData[index].sub_modules[i].sub_modules[child_i].is_display_update = perm.is_update
                                            this.allModulesData[index].sub_modules[i].sub_modules[child_i].is_display_delete = perm.is_delete
                                            }
                                        })
                                    }
                                })
                            }
                        })
                    })
                }
                this.setGroupPermissions()
            },
            async selectedValueUdate(val){
                this.groupType=val.groupType  
                this.selectedRole = val.role
                if(this.selectedRole !== undefined && this.selectedRole !=='undefined' && this.selectedRole !== null){
                    await this.fetchGroupTemplatePermissions(this.selectedRole)
                    this.setAllModulesBasedOnRole()
                }
                
            },
        },
        async mounted(){
            this.$store.dispatch("page/setPage", "settings/newusergroup");
            let group_id = new URL(location.href).searchParams.get("group_id");
            this.group_id_is = group_id
            this.formtitle = new URL(location.href).searchParams.get("add_edit");
            this.selectedRole =  new URL(location.href).searchParams.get("role");
            if(this.formtitle == 'edit'){
            this.fetchAddEditUserGroupLoading = true
            }
            try{
                let admins_payload = {
                    customer_id :this.getCompnayId 
                }
                await this.fetchUsersDetail(admins_payload)
                if(this.formtitle == 'edit'){
                    let payload = {
                        group_id:+this.group_id_is,
                        company_id:this.getCompnayId
                    }
                    await this.fetchGroupUsersApi(payload)
                    await this.fetchGroupPermissions(+this.group_id_is)
                    await this.fetchGroupDetails(+this.group_id_is)
                }

                await this.fetchAllModules()
                await this.fetchGroupTemplatePermissions(this.selectedRole)
                this.setAllModulesBasedOnRole()
                this.fetchAddEditUserGroupLoading = false
                this.setAllUsersData()
            }catch(e){
                this.fetchAddEditUserGroupLoading = false
                this.notificationError(e);
            }
        },
        updated(){
        }
    }
</script>

<style lang="scss">
.user-group-card-wrapper {
    background-color: #fff;
    height: calc(100% - 95px);
}

.add_user-group-loading-wrapper {
    width: 100%;
    min-height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.add-user-group-text-field .v-input.text-fields.v-text-field--outlined>.v-input__control>.v-input__slot {
    min-height: 40px !important; 
}
.add-user-by-searching-list{
.v-input {
    background-color: white !important;
    .v-input__control {
        background-color: white !important;
        .v-input__slot {
            min-height: 40px;
            height: 40px;
            // max-width: 500px;
            min-width: 500px;
            flex-wrap: nowrap;
            background-color: white;

            fieldset {
                border: 1px solid #B3CFE0;
            }

            .v-input--selection-controls__input {
                margin-right: 6px;
            }

            .v-select__slot {
                .v-select__selections {
                    min-height: 48px;
                    font-size: 14px;
                    .v-chip {
                        background: #f7f7f7;
                        background-color: #f7f7f7;
                        height: 24px;
                        border-radius: 4px;
                        border: 1px solid #EBF2F5;
                        padding: 0 8px;
                        font-size: 12px;
                        margin: 2px 4px 4px 0;
                        .v-chip__content {
                            color: #4a4a4a;
                            .name {
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                max-width: 275px;
                            }
                            .btn-icon-wrapper {
                                height: auto;
                                width: auto;
                                min-width: unset;
                                box-shadow: none;
                                padding: 0;
                                padding-left: 6px;
                                display: flex;
                                i {
                                    font-size: 18px;
                                    color: #819FB2;
                                }
                            }
                        }
                    }   

                    .mdi-close-circle::before {
                        content: "\F0156";
                        color: #6D858F;
                        font-size: 16px;
                    }
                }

                .v-input__append-inner {
                    .v-icon {
                        &.mdi-close {
                            font-size: 20px;
                            color: #F93131 !important;
                        }
                    }
                }
            }                                
        }                            
    }

    &.v-autocomplete:not(.v-input--is-focused).v-select--chips input {
        max-height: 25px !important;
    }
    &.v-autocomplete:not(.v-input--is-disabled).v-select.v-text-field input {
        width: 33px;
    }
}
}
.add-usergroup-expension-panel .v-expansion-panel-content__wrap {
     padding:0 10px;

    flex: 1 1 auto;
    max-width: 100%;
}
.add-usergroup-expension-panel .v-expansion-panel-header {
    padding: 0 10px 0 10px;
    min-height: 50px;
}
.add-usergroup-expension-panel .v-expansion-panel--active > .v-expansion-panel-header {
    min-height: 50px;
}
.add-user-group-checkbox-change .v-input {
    margin-bottom: 4px;

    i {
        &.mdi-checkbox-blank-outline {
            width: 26px !important;
            // height: 26px !important;
            padding: 3px !important;
            &::before {
                content: '' !important;
                background-image: url('/checkbox-empty-icon-1.svg') !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
                background-size: cover !important;
                width: 16px !important;
                height: 16px !important;
            }
        }
        &.mdi-minus-box {
            width: 26px !important;
            // height: 26px !important;  
            padding: 3px !important;
    
            &::before {
                content: '' !important;
                background-image: url('/checkbox-minus-filled-1.svg') !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
                background-size: cover;
                width: 16px !important;
                height: 16px !important;
            }
        }
    
        &.mdi-checkbox-marked {
            width: 26px !important;
            // height: 26px !important;
            padding: 3px !important;

            &::before {
                content: '' !important;
                background-image: url('/checkbox-filled-icon-1.svg') !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
                background-size: cover !important;
                width: 16px !important;
                height: 16px !important;
            }
        }
    }

    &.v-input--is-disabled {
        i {        
            &.mdi-checkbox-marked {        
                &::before {
                    content: '' !important;
                    background-image: url('/checkbox-checked-disabled.svg') !important;
                    background-position: center !important;
                    background-repeat: no-repeat !important;
                    background-size: cover !important;
                    width: 16px !important;
                    height: 16px !important;
                }
            }
        }
    }
}
.add-Users-by-searching-the-user-list {
    position: sticky;
    bottom: 0px;
}
</style>