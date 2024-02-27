<template>
    <v-dialog
        v-model="dialogEdit"
        max-width="700px"
        content-class="edit-profile-dialog custom-dialog-rightside-for-Edit-UserGroup-Dialog"
        v-resize="onResize"
        :retain-focus="false"
        persistent
        scrollable>

        <v-card style="height:100%;" class="" elevation="0">                      
            <v-card-text class=" text-h5 mx-0 py-3 px-6" style="color: #4A4A4A;display:flex;flex:initial;">
                <span class="font-semi-bold mt-1">{{editedItem.group_name !==null ? editedItem.group_name :''}} </span>
                <v-spacer></v-spacer>
                <v-btn v-if="showButtonBasedOnPermissions" @click="EditAddUserGroupData" class="btn-white mr-2" style="color: #0171A1;">Edit</v-btn>
                <v-btn @click="close" color= "#0171A1" icon> <v-icon>mdi-close</v-icon></v-btn>
            </v-card-text>

            <v-divider style="border-color:#EBF2F5;"></v-divider>

            <v-tabs class="company-profile-user-group-tab my-4 px-6" grow style="flex:none" v-model="activeTab">
                <v-tab 
                    :class="index == 1 ? 'company-profile-tab-last mr-0' : ''"
                    v-for="(tab,index) in  usersPermissionsTab" :key="index">
                        {{tab}}
                </v-tab>
            </v-tabs>

            <v-card-text class="px-0 mx-0 py-0 my-0">                
                <v-data-table
                    :headers="_header"
                    :items="_dataGroup"
                    class="edit-user-group-table elevation-0 px-6"
                    :class="activeTab == 1 ? 'permissions' : 'users'"
                    :expanded.sync="expanded"
                    fixed-header
                    item-key="id"
                    :single-expand="singleExpand"
                    :show-expand="activeTab == 1"
                    hide-default-footer
                    style="position:absolute;width:inherit"
                    mobile-breakpoint="769">

                    <template v-slot:[`item.name`]="{ item }">
                        <div class="d-flex align-center" v-if="item.isCurrent">
                            {{item.name}} <span class="mt-1 ml-2"><img src="../../../assets/icons/CustomerCurrent.svg" alt=""></span> 
                        </div>
                        <div v-else> {{item.name}} </div>
                    </template>

                    <template v-slot:[`item.group_added_by_user`]="{ item }">
                        <div>
                            <p class="mb-0" v-if="item.isCurrent">You</p>
                            
                            <p class="mb-0 d-flex align-center" v-else>
                                {{item.group_added_by_user !== undefined && item.group_added_by_user !== null ? item.group_added_by_user.name :'--'}}
                            </p>
                        </div>
                    </template>

                    <template v-slot:[`item.email`]="{ item }">
                        <div>
                            <div class="mb-0" v-if="item.is_confirm">
                                {{ item.email }}
                            </div>
        
                            <div class="mb-0" v-else>
                                <p style="display:block" class="mb-0">{{ item.email }}</p>
                                    <p class="d-flex align-center mb-0">
                                <span style="display:block;color:#D68A1A; padding-right: 5px;">Invitation Pending</span>
                                <!-- <v-btn icon @click="resedInvitation(item)" style="height: 30px; width: 30px;">
                                    <img src="../../../assets/icons/email-blue.svg" alt="Resend Invitation" title="Resend Invitation" />
                                </v-btn> -->
                                </p>
                            </div>
                        </div>
                    </template>

                    <template v-slot:[`item.module_name`]="{ item }">
                        <div class="font-semi-bold" :style="addColorIfValueMatch(item) == true ? 
                            'color: #4A4A4A;font-size:18px;' : activeTab == 1 ? 'font-size:18px;;color: #6D858F;' : ''">
                            {{item.module_name}}
                        </div>
                    </template>
                    
                    <template v-slot:[`item.created_at`]="{ item }">
                        <div>
                        {{dateAdded(item.created_at)}}
                        </div>
                    </template>
                    
                    <template v-slot:[`item.view`]="{ item, index}">
                        <div :style="addColorIfValueMatch(item) == true ? 'color: #4A4A4A;': activeTab == 1 ? 'color: #6D858F;':''" class="font-semi-bold">
                            {{getAllOrSelectiveValue(item.view,index,'view')}}
                        </div>
                    </template>

                    <template v-slot:[`item.add`]="{ item, index }">
                        <div :style="addColorIfValueMatch(item) == true ? 'color: #4A4A4A;': activeTab == 1 ? 'color: #6D858F;':''" class="font-semi-bold">
                        {{getAllOrSelectiveValue(item.add,index,'add')}}
                        </div>
                    </template>

                    <template v-slot:[`item.update`]="{ item, index }">
                        <div :style="addColorIfValueMatch(item) == true ? 'color: #4A4A4A;': activeTab == 1 ? 'color: #6D858F;':''" class="font-semi-bold">
                            {{getAllOrSelectiveValue(item.update,index,'update')}}
                        </div>
                    </template>

                    <template v-slot:[`item.delete`]="{ item, index }">
                        <div :style="addColorIfValueMatch(item) == true ? 'color: #4A4A4A;': activeTab == 1 ? 'color: #6D858F;':''" class="font-semi-bold">
                            {{getAllOrSelectiveValue(item.delete,index,'delete')}}
                        </div>
                    </template>

                    <template v-slot:expanded-item="{ headers, item }">
                        <td :colspan="headers.length" class="py-0 px-0">
                            <div>
                                <div class="edit-user-group-permissions">
                                    <v-expansion-panels class="white py-1" :ripple="false" multiple flat style="border-radius:0;">
                                        <v-expansion-panel
                                            class="px-0 mx-0 mt-0"
                                            :ripple="false"
                                            v-for="(main_item,i) in item.sub_modules"
                                            :key="i">

                                            <v-expansion-panel-header hide-actions class="py-0" style="min-height: 40px !important;">
                                                <template v-slot:default="{ open }">
                                                    <div class="font-semi-bold" style="font-size:14px" :style="open ? 'color: #4A4A4A;':'color: #6D858F'">
                                                        <v-icon>
                                                            <template v-if="open">mdi-chevron-up</template>
                                                            <template v-else>mdi-chevron-down</template>
                                                        </v-icon>
                                                        {{main_item.module_name}}
                                                    </div>
                                                </template>
                                            </v-expansion-panel-header>

                                            <v-expansion-panel-content>
                                                <v-row v-for="(sub_item, sub_index) in main_item.sub_modules" :key="sub_index" no-gutters class="align-center edit-usergroup-checkbox-change" :class="sub_index !== 0 ? 'mt-2' : ''">
                                                    <v-col cols="3" class="d-flex align-center font-semi-bold text-uppercase" offset="1" style="color: #6D858F;font-size:12px;">
                                                        {{sub_item.module_name}}
                                                    </v-col>

                                                    <v-col cols="2" class="px-0 mx-0">
                                                        <v-card elevation="0" class="d-flex align-center justify-center">
                                                            <v-checkbox
                                                                v-if="sub_item.is_display_view == 1"
                                                                class="my-0 text-center"
                                                                hide-details
                                                                :disabled="sub_item.is_display_view == 1"
                                                                v-model="sub_item.is_display_view"
                                                                v-bind:false-value="0" v-bind:true-value="1"
                                                                :on-icon="'mdi-check-circle-outline'" color="green" :off-icon="'mdi-checkbox-blank-circle-outline'">
                                                            </v-checkbox>
                                                        </v-card>
                                                    </v-col>

                                                    <v-col cols="2">
                                                        <v-card elevation="0" class=" d-flex align-center justify-center ml-2">
                                                            <v-checkbox
                                                                v-if="sub_item.is_display_add == 1"
                                                                v-model="sub_item.is_display_add"
                                                                :disabled="sub_item.is_display_add == 1"
                                                                :on-icon="'mdi-check-circle-outline'" :off-icon="'mdi-checkbox-blank-circle-outline'"
                                                                class="my-0 text-center" color="green"
                                                                hide-details>
                                                            </v-checkbox>
                                                        </v-card>
                                                    </v-col>

                                                    <v-col cols="2">
                                                        <v-card elevation="0" class=" d-flex align-center justify-center">
                                                            <v-checkbox
                                                                v-if="sub_item.is_display_update == 1"
                                                                class="my-0 text-center"
                                                                v-model="sub_item.is_display_update"
                                                                v-bind:false-value="0" v-bind:true-value="1"
                                                                :disabled="sub_item.is_display_update == 1"
                                                                :on-icon="'mdi-check-circle-outline'" :off-icon="'mdi-checkbox-blank-circle-outline'"
                                                                hide-details color="green">
                                                            </v-checkbox>
                                                        </v-card>
                                                    </v-col>

                                                    <v-col cols="2">
                                                        <v-card elevation="0" class=" d-flex align-center justify-center ml-1">
                                                            <v-checkbox
                                                                v-if="sub_item.is_display_delete == 1"
                                                                class="my-0 text-center"
                                                                v-model="sub_item.is_display_delete"
                                                                v-bind:false-value="0" v-bind:true-value="1"
                                                                :disabled="sub_item.is_display_delete == 1"
                                                                :on-icon="'mdi-check-circle-outline'" :off-icon="'mdi-checkbox-blank-circle-outline'"
                                                                hide-details color="green">
                                                            </v-checkbox>
                                                        </v-card>
                                                    </v-col>
                                                </v-row>
                                            </v-expansion-panel-content>
                                        </v-expansion-panel>
                                    </v-expansion-panels>
                                </div>

                                <!-- <div class="expanded-body-wrapper">
                                        <div class="expanded-body-content">
                                            <div class="header-data w-100 text-center"> No data found </div>
                                        </div>
                                </div> -->
                            </div>
                        </td>
                    </template>

                    <template v-slot:no-data>
                        <div class="loading-wrapper" v-if="getGroupWithUsersAndPermissionsLoading || getAllModulesLoading">
                            <v-progress-circular :size="40" color="#0171a1" indeterminate></v-progress-circular>
                        </div>
                        <div class="no-data-wrapper" v-if="!getGroupWithUsersAndPermissionsLoading && activeTab == 0">
                            No Users
                        </div>
                        <div class="no-data-wrapper" v-if="!getGroupWithUsersAndPermissionsLoading && !getAllModulesLoading &&  activeTab == 1">
                            No Permissions
                        </div>
                    </template>
                </v-data-table>
            </v-card-text>            
        </v-card>
    </v-dialog>
</template>

<script>
import _ from "lodash";
import moment from "moment"
import { mapGetters, mapActions } from "vuex"
export default {
    props: ['dialogEdit','userGroupData'],
    components: {},
    data() {
        return {
            activeTab:0,
            usersPermissionsTab:['Users','Permissions'],
            userHeader:[
                {
			    	text: "User",
			    	align: "start",
			    	sortable: false,
			    	value: "name",
			    	width: "30%",
			    	fixed: true,
                    class:'Edit-group-table-header'
			    },
			    {
			    	text: "Email Address",
			    	align: "start",
			    	sortable: false,
			    	value: "email",
			    	width: "18%",
			    	fixed: true,
                    class:'Edit-group-table-header'
			    },
                {
			    	text: "Date Added",
			    	align: "start",
			    	sortable: false,
			    	value: "created_at",
			    	width: "16%",
			    	fixed: true,
                    class:'Edit-group-table-header'
			    },
			    {
			    	text: "Added By",
			    	align: "start",
			    	sortable: false,
			    	value: "group_added_by_user",
			    	width: "18%",
			    	fixed: true,
                    class:'Edit-group-table-header'
			    },
            ],
            permissionHeader: [
                { text: '', value: 'data-table-expand', width: "", class:'Edit-group-table-header'},   
                {
                    text: "Module",
                    align: "start",
                    sortable: false,
                    value: "module_name",
                    width: "25%",
                    fixed: true,
                    class:'Edit-group-table-header'
                },
                {
                    text: "View",
                    align: "center",
                    sortable: false,
                    value: "view",
                    width: "20%",
                    fixed: true,
                    class:'Edit-group-table-header'
                },
                {
                    text: "Add",
                    align: "center",
                    sortable: false,
                    value: "add",
                    width: "16%",
                    fixed: true,
                    class:'Edit-group-table-header'
                },
                {
                    text: "Update",
                    align: "center",
                    sortable: false,
                    value: "update",
                    width: "16%",
                    fixed: true,
                    class:'Edit-group-table-header'
                },
                {
                    text: "Delete",
                    align: "center",
                    sortable: false,
                    value: "delete",
                    width: "18%",
                    fixed: true,
                    class:'Edit-group-table-header'
                },
            ],
            expanded: [],
            singleExpand: false,
            showButtonBasedOnPermissions:false
        }
    },
    watch: {
        dialogEdit:function(newVal){
            if(newVal == true){
                this.setGroupPermissions()
                this.showButtonBasedOnPermissionsCheckFunction()
            }
        }
    },
    computed: {
        ...mapGetters({
            getGroupWithUsersAndPermissionsLoading:'settings/getGroupWithUsersAndPermissionsLoading',
            getAllModules:'settings/getAllModules',
            getAllModulesLoading:'settings/getAllModulesLoading',
            getUser: "getUser",
        }),
        _header(){
				let final_header = []
				if(this.activeTab == 0){
					final_header = this.userHeader
				}else{
					final_header = this.permissionHeader
				}
				return final_header
			},
        _dataGroup(){
            let final_data = []
				if(this.activeTab == 0){
					final_data = this.userItems
				}else{
					final_data = this.allModulesData
				}
			return final_data
        },
        editedItem: {
            get() {
		        return this.userGroupData;
            },
            set(value) {
                this.$emit("update:userGroupData", value);
            },
        },
        userItems(){
            let data = []
            if(this.editedItem.users !== null && this.editedItem.users !== undefined && this.editedItem.users !== 'undefined'){
                let userId;
			    if (typeof this.getUser === "string") {
				    userId = JSON.parse(this.getUser).id;
			    } else {
				    userId = this.getUser.id;
			    }
                    data = this.editedItem.users.map((item) => {
				return {
					updated_at: item.updated_at,
					created_at: item.created_at,
					email: item.email,
					isCurrent: item.id === userId,
					name: item.name,
					is_confirm: item.is_customer_invite_confirm,
                    group_added_by_user:item.group_added_by_user
				};
			});

            }else{
                data = []
            }
            return data
        },
        userPermissions(){
            let data = []
            if(this.editedItem.permissions !== null && this.editedItem.permissions !== undefined && this.editedItem.permissions !== 'undefined'){
                data = this.editedItem.permissions
            }else{
                data = []
            }
            return data
        },
        allModulesData(){
            let data = []
            if(this.getAllModules !==null && this.getAllModules !=='undefined' &&
                this.getAllModules !== undefined && this.getAllModules.length > 0 && this.userPermissions.length > 0){
                    data = this.getAllModules
                }else{
                    data = []
                }
            return data 
        },
        
    },
    methods: {
        ...mapActions({
            fetchAllModules:'settings/fetchAllModules'
        }),
        dateAdded(date) {
           return moment(date).format('ll');
        },
        onResize() {
            if (window.innerWidth < 769) {
                this.isMobile = true;
            } else {
                this.isMobile = false;
            }
        },
        close(){
            this.activeTab = 0;
            this.expanded = [];
            this.singleExpand = false;
            this.$emit('close')
        },
        EditAddUserGroupData(){
            if(this.$route.name === 'Settings'){
                let role = 1
			    if(this.editedItem.group_name == 'Admin'){
				    role = 1
			    }else if(this.editedItem.group_name == 'Manager'){
				    role = 2
			    }else if(this.editedItem.group_name == 'Operator'){
				    role = 3
			    }
			    else{
				    role = 4
			    }
                this.$router.push(`/settings/newusergroup?group_id=${this.editedItem.id}&add_edit=edit&role=${role}`)
            }
        },
        addColorIfValueMatch(item){
            let findItem = _.find(this.expanded, (e) => e === item)
            if(findItem !== "undefined" && findItem !== undefined){
                return true
            }
            return false
        },
        setGroupPermissions(){
            if(this.userPermissions.length > 0 && this.allModulesData.length > 0){
                this.allModulesData.map((modules,index) =>{
                    this.userPermissions.map((perm)=>{
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
        getAllOrSelectiveValue(value,index,from){
            if(from == 'view'){
                let final_data = null
                const uniqueItems = this.allModulesData[index].sub_modules?.filter(val =>{
                    if('sub_modules' in val){
                        return  true
                    }
                    else{
                        return false
                    }
                } )
                if(uniqueItems.length == 0) return final_data = 'No'
                let result = this.allModulesData[index].sub_modules?.every((child_val)=>{
                    if('sub_modules' in child_val){
                        return child_val.sub_modules?.every(a => a.is_display_view === 1)
                    }else{
                        return true
                    }    
                })
                
                if(result == true){
                   final_data = 'All'
                }else {
                    let sub_result = this.allModulesData[index].sub_modules?.every((child_val)=>{
                        if('sub_modules' in child_val){
                            return child_val.sub_modules?.every(a => a.is_display_view === 0)
                        }else{
                            return true
                        }
                    })

                    if(sub_result == true){
                        final_data = 'No'
                    }else{
                        final_data = 'Selective'
                    }

                }
                return final_data
            }else if(from == 'add'){
                let final_data = null
                const uniqueItems = this.allModulesData[index].sub_modules?.filter(val =>{
                    if('sub_modules' in val){
                        return  true
                    }
                    else{
                        return false
                    }
                } )
                if(uniqueItems.length == 0) return final_data = 'No'
                let result = this.allModulesData[index].sub_modules?.every((child_val)=>{
                    if('sub_modules' in child_val){
                        return child_val.sub_modules?.every(a => a.is_display_add === 1)
                    }else{
                        return true
                    }    
                })
                
                if(result == true){
                   final_data = 'All'
                }else {
                    let sub_result = this.allModulesData[index].sub_modules?.every((child_val)=>{
                        if('sub_modules' in child_val){
                            return child_val.sub_modules?.every(a => a.is_display_add === 0)
                        }else{
                            return true
                        }
                    })

                    if(sub_result == true){
                        final_data = 'No'
                    }else{
                        final_data = 'Selective'
                    }

                }
                return final_data
            }else if(from == 'update'){
                let final_data = null
                const uniqueItems = this.allModulesData[index].sub_modules?.filter(val =>{
                    if('sub_modules' in val){
                        return  true
                    }
                    else{
                        return false
                    }
                } )
                if(uniqueItems.length == 0) return final_data = 'No'
                let result = this.allModulesData[index].sub_modules?.every((child_val)=>{
                    if('sub_modules' in child_val){
                        return child_val.sub_modules?.every(a => a.is_display_update === 1)
                    }else{
                        return true
                    }    
                })
                
                if(result == true){
                   final_data = 'All'
                }else {
                    let sub_result = this.allModulesData[index].sub_modules?.every((child_val)=>{
                        if('sub_modules' in child_val){
                            return child_val.sub_modules?.every(a => a.is_display_update === 0)
                        }else{
                            return true
                        }
                    })

                    if(sub_result == true){
                        final_data = 'No'
                    }else{
                        final_data = 'Selective'
                    }

                }
                return final_data
            }else{
                let final_data = null
                const uniqueItems = this.allModulesData[index].sub_modules?.filter(val =>{
                    if('sub_modules' in val){
                        return  true
                    }
                    else{
                        return false
                    }
                } )
                if(uniqueItems.length == 0) return final_data = 'No'
                let result = this.allModulesData[index].sub_modules?.every((child_val)=>{
                    if('sub_modules' in child_val){
                        return child_val.sub_modules?.every(a => a.is_display_delete === 1)
                    }else{
                        return true
                    }    
                })
                
                if(result == true){
                   final_data = 'All'
                }else {
                    let sub_result = this.allModulesData[index].sub_modules?.every((child_val)=>{
                        if('sub_modules' in child_val){
                            return child_val.sub_modules?.every(a => a.is_display_delete === 0)
                        }else{
                            return true
                        }
                    })

                    if(sub_result == true){
                        final_data = 'No'
                    }else{
                        final_data = 'Selective'
                    }

                }
                return final_data
            }
                
        },
        showButtonBasedOnPermissionsCheckFunction(){
            
			if(this.userPermissions.length > 0){
                
				let result = this.userPermissions.filter(val => val.const_name == 'SETTINGS_COMPANY_PROFILE_USER_GROUPS')
                if(result.length > 0){
                    return result[0].is_update == 1 ? this.showButtonBasedOnPermissions = true : this.showButtonBasedOnPermissions = false
                }else{
                    return false
                }	
			}else{
				return this.showButtonBasedOnPermissions = false
			}
		}
    },
    async mounted() {
        await this.fetchAllModules()
    }
}
</script>

<style lang="scss">
@import "../../../assets/scss/tables.scss";

.v-data-table {
    &.edit-user-group-table {
        .v-data-table__wrapper {
            table {
                thead {
                    tr {
                        th {
                            font-size: 14px !important;

                            &:first-child {
                                padding-left: 16px;
                            }
                        }
                    }
                }

                tbody {
                    tr {
                        td {
                            &:first-child {
                                padding-left: 16px;
                            }
                        }
                    }
                }
            }
        }

        &.permissions {
            .v-data-table__wrapper {
                table {
                    thead {
                        tr {
                            th {
                                font-size: 14px !important;

                                &:first-child {
                                    padding-left: 10px;
                                }

                                &:nth-child(2) {
                                    padding-left: 0;
                                }
                            }
                        }
                    }

                    tbody {
                        tr {
                            td {
                                &:first-child {
                                    padding-left: 10px;
                                }

                                &:nth-child(2) {
                                    padding-left: 0;
                                }

                                .v-data-table__expand-icon,
                                .mdi-chevron-up,
                                .mdi-chevron-down {
                                    color: #0171A1;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

.custom-dialog-rightside-for-Edit-UserGroup-Dialog.v-dialog:not(.v-dialog--fullscreen) {
    max-height: 100%;
    height: 100%;
    right: 0 !important;
    margin: 0%;
    position: absolute !important;
    border-radius: 0 !important;
}

.Edit-group-table-header {
    background-color: #F7F7F7 !important;
    text-transform: capitalize !important;
    color: #4A4A4A;
}

.edit-user-group-permissions .v-expansion-panel-content__wrap {
    padding:0 3px 6px 3px;
    flex: 1 1 auto;
    max-width: 100%;
    max-height: 100%;
}

.edit-usergroup-checkbox-change .v-input {
    margin-top: 4px;

    i {
        &.mdi-check-circle-outline {
            width: 26px !important;
            // height: 26px !important;
            padding: 0 !important;

            &::before {
                content: '' !important;
                background-image: url('../../../assets/icons/CircleCheckOutline.svg') !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
                background-size: cover !important;
                width: 21px !important;
                height: 21px !important;
            }
        }

        &.mdi-checkbox-blank-circle-outline {
            width: 26px !important;
            // height: 26px !important;
            padding: 0 !important;

            &::before {
                content: '' !important;
                background-image: url('/radio-empty-1.svg') !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
                background-size: cover !important;
                width: 21px !important;
                height: 21px !important;
            }
        }
    }
}
</style>