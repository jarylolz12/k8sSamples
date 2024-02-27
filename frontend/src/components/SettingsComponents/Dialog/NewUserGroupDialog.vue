<template>
    <div>
        <v-dialog
            v-model="dialogNewUserGroup"
            max-width="480px"
            content-class="add-user-dialog"
            :retain-focus="false"
            @click:outside="close">
            <v-card>
                <!-- <v-card-text class="py-0 my-0"> -->
				    <div class=" pt-6 mx-3 px-3">
					    <img src="@/assets/icons/question.svg" alt="" />
				    </div>
			    <!-- </v-card-text> -->
                <v-form
                    ref="form"
                    v-model="valid"
                    action="#"
                    @submit.prevent="">

                    <h3 class="py-3 my-0 px-3 mx-3">                        
                        <span>New User Group</span>                        
                    </h3>
                    <p class="px-3 mx-3 py-0">Choose how you want to create the new user group:</p>                    

                    <v-card-text class="py-0">
                        <div class="add-user-wrapper pb-3">
                            <div class="add-user-info">
                                <v-row>
                                    <v-col cols="12" class="pt-0">
                                        <v-radio-group
                                            dense hide-details="auto"
                                            v-model="$store.state.settings.createGroupType"
                                            column
                                            class="mx-2">

                                            <v-radio value="CreateYourOwnGroup">
                                                <template slot="label">
                                                    <p class="my-auto font-semi-bold" style="color: #4A4A4A;">Create Your Own Group</p>
                                                </template>
                                            </v-radio>

                                            <v-radio
                                                label="Use Predefined  Group (You can modity if needd)"
                                                color="#4A4A4A"
                                                value="UsePredefinedGroup">

                                                <template slot="label">
                                                    <p class="my-auto font-semi-bold" style="color: #4A4A4A;">Use Predefined  Group <span style="font-weight: 400;">(You can modify if needed)</span></p>
                                                </template>
                                            </v-radio>
                                        </v-radio-group>
                                    </v-col>

                                    <v-col cols="11" class="mx-auto pt-2">
                                        <v-list class="new-user-group-selection mx-1">
                                            <v-list-item-group
                                                v-model="selectRole"
                                                color="primary">
                                                <v-list-item v-for="(item,index) in  getAllDefaultGroupTemplates" :key="item.id" class="mb-2">
                                                    <v-list-item-content>
                                                        <v-list-item-title class="heading mb-0 font-semi-bold">{{item.name}}</v-list-item-title>
                                                        <v-list-item-subtitle class="sub-heading">{{item.description}}</v-list-item-subtitle>
                                                    </v-list-item-content>
                                                    <v-list-item-icon v-if="selectRole == index">
                                                        <v-icon color="#0171A1">mdi-check</v-icon>
                                                    </v-list-item-icon>
                                                </v-list-item>
                                                <!-- <v-list-item class="mb-2">
                                                    <v-list-item-content>
                                                        <v-list-item-title class="heading">Manager</v-list-item-title>
                                                        <v-list-item-subtitle class="sub-heading">Manage operation but not edit</v-list-item-subtitle>
                                                    </v-list-item-content>
                                                    <v-list-item-icon v-if="selectRole == 1">
                                                        <v-icon color="#0171A1">mdi-check</v-icon>
                                                    </v-list-item-icon>
                                                </v-list-item>
                                                <v-list-item class="mb-2">
                                                    <v-list-item-content>
                                                        <v-list-item-title class="heading">Operator</v-list-item-title>
                                                        <v-list-item-subtitle class="sub-heading">Manage some operation but not edit and update</v-list-item-subtitle>
                                                    </v-list-item-content>
                                                    <v-list-item-icon v-if="selectRole == 2">
                                                        <v-icon >mdi-check</v-icon>
                                                    </v-list-item-icon>
                                                </v-list-item>
                                                <v-list-item class="mb-2">
                                                    <v-list-item-content>
                                                        <v-list-item-title class="heading">Driver</v-list-item-title>
                                                        <v-list-item-subtitle class="sub-heading">Manage operation under driver permissions</v-list-item-subtitle>
                                                    </v-list-item-content>
                                                    <v-list-item-icon v-if="selectRole == 3">
                                                        <v-icon >mdi-check</v-icon>
                                                    </v-list-item-icon>
                                                </v-list-item> -->
                                            </v-list-item-group>
                                        </v-list>
                                    </v-col>
                                </v-row>
                            </div>
                        </div>
                    </v-card-text>

                    <v-card-actions style="border-top:none !important;">
                        <button
                            class="btn-blue mr-0"
                            @click="addNewUserGroup"
                            :disabled="newUserPredefinedGroupLoading || selectRole == null">
                            Continue
                        </button>

                        <button
                            :disabled="newUserPredefinedGroupLoading"
                            class="btn-white ml-2"
                            @click="close">
                            Cancel
                        </button>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {mapActions,mapGetters} from "vuex"
import globalMethods from "../../../utils/globalMethods"
    export default {
        props: ["dialog","companyId","fromAddUser"],
        data(){
            return {
                valid: true,
                // createGroupType:'UsePredefinedGroup',
                selectRole:0,
            }
        },
        computed:{
            ...mapGetters({
                getAllDefaultGroupTemplates:'settings/getAllDefaultGroupTemplates',
                getAllDefaultGroupTemplatesLoading:'settings/getAllDefaultGroupTemplatesLoading',
                getAddGroup:'settings/getAddGroup',
                getAddGroupLoading:'settings/getAddGroupLoading',
                getGroupTemplatePermissions:'settings/getGroupTemplatePermissions',
                getGroupTemplatePermissionsLoading:'settings/getGroupTemplatePermissionsLoading',
                getAddGroupPermissionsLoading:'settings/getAddGroupPermissionsLoading',
                getGroupPermissionsLoading:'settings/getGroupPermissionsLoading',
                getGroupUsersLoading:'settings/getGroupUsersLoading'
            }),
            dialogNewUserGroup: {
                get() {
                    return this.dialog;
                },
                set(value) {
                    this.$emit("update:dialog", value);
                },
            },
            groupTemplatePermissionsData(){
                let data = []
                if(this.getGroupTemplatePermissions.data !== undefined && this.getGroupTemplatePermissions.data !== 'undefined' && this.getGroupTemplatePermissions.data.length >0 ){
                    data = this.getGroupTemplatePermissions.data.map(({id,...rest}) => ({module_id:id,...rest}))
                }else{
                    data = []
                }
                return data
            },
            newUserPredefinedGroupLoading(){
                let loading = false
                if(this.getAllDefaultGroupTemplatesLoading || this.getAddGroupLoading || this.getGroupTemplatePermissionsLoading || 
                this.getAddGroupPermissionsLoading){
                    loading = true
                }else{
                    loading = false
                }
                return loading
            },
        },
        methods:{
            ...mapActions({
                fetchGroupWithUsersAndPermissions:'settings/fetchGroupWithUsersAndPermissions',
                fetchAllDefaultGrouppermissions:'settings/fetchAllDefaultGrouppermissions',
                fetchGroupTemplatePermissions:'settings/fetchGroupTemplatePermissions',
                addGroupApi:'settings/addGroupApi',
                addGroupPermissionsApi:'settings/addGroupPermissionsApi',
                fetchGroupPermissions:'settings/fetchGroupPermissions',
                fetchGroupUsersApi:'settings/fetchGroupUsersApi',
            }),
            ...globalMethods,
            close() {
                this.$refs.form.resetValidation();
                this.$store.state.settings.createGroupType = 'UsePredefinedGroup';
                this.selectRole = 0;
                this.$emit("closeNewUserGroupDialog");
            },
            async addNewUserGroup(){
                if(this.$store.state.settings.createGroupType == 'CreateYourOwnGroup' && this.$route.name === 'Settings'){
                    let payload = {
                        role:this.selectRole,
                        groupType:this.$store.state.settings.createGroupType
                    }
                    payload.role++
                    this.$router.push(`/settings/newusergroup?add_edit=add&role=${payload.role}`)
                    
                }else{
                    if(this.fromAddUser){
                        let payload ={
                            role :this.selectRole,
                            groupType:[]
                        }
                        payload.role++
                        payload.groupType
                        if(this.getAllDefaultGroupTemplates !== undefined && this.getAllDefaultGroupTemplates !== 'undefined' && 
                        this.getAllDefaultGroupTemplates !== null && this.getAllDefaultGroupTemplates.length > 0){
                                payload.groupType = this.getAllDefaultGroupTemplates.filter(val => val.id == payload.role)
                            }else{
                                payload.groupType = []
                            }
                        this.$emit('selectedValueUdate',payload)
                        this.close()
                        
                        // if(this.groupTemplatePermissionsData.length > 0){
                        //     let add_group = {
                        //         company_id:this.companyId,
                        //         group_name:this.getAllDefaultGroupTemplates[0].name,
                        //         group_description:this.getAllDefaultGroupTemplates[0].description
                        //     }
                        //     await this.addGroupApi(add_group)
                        //     if(this.getAddGroup !== 'undefined' && this.getAddGroup !== undefined){
                        //         let add_Permissions = {
                        //             group_id:+this.getAddGroup.id,
                        //             permissions:this.groupTemplatePermissionsData
                        //         }
                        //         let payloadGroup = {
                        //             group_id : +this.getAddGroup.id,
                        //             company_id:this.getCompnayId
                        //         }
                        //         await this.addGroupPermissionsApi(add_Permissions)
                        //         await this.fetchGroupUsersApi(payloadGroup)
                        //         await this.fetchGroupPermissions(this.getAddGroup.group_id)
                        //         this.$emit('setGroupPermissions')
                        //         this.notificationMessage("Group added successfully.");
                                
                            // }
                        // }
                    }
                    else{
                        try{
                            if(this.getAllDefaultGroupTemplates !== undefined && this.getAllDefaultGroupTemplates !== 'undefined' && 
                                this.getAllDefaultGroupTemplates.length > 0){
                                    let payload ={
                                        role:this.selectRole
                                    }
                                    payload.role++
                                    
                                await this.fetchGroupTemplatePermissions(payload.role)
                                if(this.groupTemplatePermissionsData.length > 0){
                                    let getAllDefaultTemplate = this.getAllDefaultGroupTemplates.filter(val => val.id == payload.role)
                                    let add_group = {
                                        company_id:this.companyId,
                                        group_name:getAllDefaultTemplate[0].name,
                                        group_description:getAllDefaultTemplate[0].description
                                    }
                                    await this.addGroupApi(add_group)
                                    /* eslint-disable */
                                    let sendData = this.groupTemplatePermissionsData.map(({group_template_id,created_at,updated_at,...rest})=>({...rest}))
                                    /* eslint-enable */
                                    // if(this.getAddGroup !== 'undefined' && this.getAddGroup !== undefined){
                                        let add_Permissions = {
                                            group_id:this.getAddGroup.id,
                                            permissions:sendData
                                        }
                                        await this.addGroupPermissionsApi(add_Permissions)

                                    // }
                                    await this.fetchGroupWithUsersAndPermissions(this.companyId)
                                    this.close()
                                    this.notificationMessage("Group added successfully.");
                                }
                            }
                        
                        }catch(e){
                            this.notificationError(e);
                        }
                    }
                    
                    // this.$emit("closeNewUserGroupDialog");
                }
            }
        },
        mounted(){
            this.fetchAllDefaultGrouppermissions()
            if(this.$router.currentRoute.name === 'Settings' && this.$store.state.page.currentSettingsTab == 0){
                this.selectRole = 0
                this.$store.state.settings.createGroupType = 'UsePredefinedGroup';
            }
            
        }
    }
</script>

<style lang="scss">
.new-user-group-selection .v-list-item {
    border-radius: 4px;
    border: 1px solid #D8E7F0;
    font-size: 12px;
    
    .heading {
        color: #4A4A4A;
        text-transform: uppercase;
        font-size: 12px;
        line-height: normal;
    }

    .sub-heading {
        padding-top: 5px;
        color: #6D858F;
        font-size: 12px !important;
        line-height: normal;
    }
}
.new-user-group-selection .v-list-item--active {
    // background-color: #F5F9FC;
    background-color: #F0FBFF;

    &::before {
        opacity: 0;
    }

    .heading {
        // font-weight: 600;
        color: #0171A1;
    }

    .sub-heading {
        // font-weight: 400;
        color: #0171A1;
    }    
}
</style>