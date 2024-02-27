<template>
    <div>
        <v-dialog
            v-model="dialogInvite"
            max-width="560px"
            content-class="add-user-dialog"
            :retain-focus="false"
            @click:outside="close"
        >
            <v-card>
                <v-form
                    ref="form"
                    v-model="valid"
                    action="#"
                    @submit.prevent=""
                >
                    <v-card-title>
                        <span class="headline">Add User</span>

                        <button icon dark class="btn-close" @click="close">
                            <v-icon>mdi-close</v-icon>
                        </button>
                    </v-card-title>

                    <v-card-text>
                        <div class="add-user-wrapper">
                            <div class="add-user-info">
                                <v-row>
                                    <v-col cols="12">
                                        <div class="card-name mb-3">
                                            <p class="card-title">
                                                Name
                                            </p>
                                            <v-text-field
                                                v-model="name"
                                                height="40px"
                                                color="#002F44"
                                                width="200px"
                                                dense
                                                class="text-fields select-items"
                                                placeholder="Enter name"
                                                outlined
                                                hide-details="auto"
                                            >
                                            </v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div class="card-name mb-3">
                                            <p class="card-title">
                                                Email Address
                                            </p>
                                            <v-text-field
                                                v-model="email"
                                                height="40px"
                                                color="#002F44"
                                                width="200px"
                                                dense
                                                class="text-fields select-items"
                                                placeholder="Enter email address"
                                                outlined
                                                type="email"
                                                :rules="emailRules"
                                                hide-details="auto"
                                            >
                                            </v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div class="card-name mb-2">
                                            <p class="card-title">
                                                User group
                                            </p>
                                            <v-select
                                                :items="getGroupWithUsersAndPermissionsData"
                                                v-model="selectedUserGroup"
                                                item-text="group_name"
                                                item-value="id"
                                                class="text-fields select-items"
                                                outlined
                                                append-icon="mdi-chevron-down"
                                                background-color="white"
                                                placeholder="Select User Group"
                                                :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                hide-details="auto">
                                            </v-select>
                                        </div>
                                    </v-col>
                                </v-row>
                                <v-row no-gutters class="mark-as-warehouse-employee">
                                    <v-col cols="6" class="pt-0">
                                        <v-checkbox
                                            v-bind:false-value="0" v-bind:true-value="1"
                                            class="my-0 text-center"
                                            hide-details color="green"
                                            v-model="markAsWarehouseEmployee"
                                        >
                                        <template slot="label">
                                            <p class="my-auto" style="color: #4A4A4A;font-weight: 400;">Mark as Warehouse Employee</p>
                                        </template>
                                        </v-checkbox>
                                    </v-col>
                                    <v-col v-if="markAsWarehouseEmployee" cols="12">
                                        <div class="card-name mb-3">
                                            <p class="card-title">
                                                ASSIGNED WAREHOUSE
                                            </p>
                                            <v-select
                                                :items="AllAdminsCopy"
                                                v-model="selectedUsersList"
                                                item-text="name"
                                                item-value="id"
                                                chips
                                                deletable-chips
                                                return-object
                                                multiple
                                                class="text-fields select-items"
                                                outlined
                                                append-icon="mdi-chevron-down"
                                                background-color="white"
                                                placeholder="Select Warehouse"
                                                :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true,overflowY:true,maxHeight:227 }"
                                                hide-details="auto">
                                                <template v-slot:selection="{ item,index }">
                                                    <v-chip v-if="index == 0 || index == 1" close @click:close="removeItem(item,index)" >
                                                        <span class="name">{{ item.name }}</span>
                                                    </v-chip>
                                                    <div style="color:#4A4A4A;" class="mr-1 body-2" v-if="index == 2">
                                                       +{{selectedUsersList.length -2 }} Others
                                                    </div>
                                                </template>
                                                <template v-slot:prepend-item>
                                                    <v-card class="prepend-search-in-all-admins-settings" elevation="0">
                                                        <v-text-field
                                                            @input="searchWh"
                                                            v-model="searchWhAdmin"
                                                            height="40px"
                                                            color="#002F44"
                                                            width="200px"
                                                            dense
                                                            prepend-inner-icon="mdi-magnify"
                                                            class="text-fields select-items"
                                                            placeholder="Search Warehouse"
                                                            outlined
                                                            hide-details="auto"
                                                        >
                                                        </v-text-field>
                                                    </v-card>
                                                </template>
                                                <template v-slot:item="{ on, attrs }">
                                                    <v-list-item style="border-bottom:none;display:none !important" v-on="on" v-bind="attrs" >
                                                        <!-- <v-list-item-action class="ml-0 mr-2">
                                                            <v-checkbox :input-value="active"></v-checkbox>
                                                        </v-list-item-action>

                                                        <v-list-item-content>
                                                            <v-list-item-title class="d-flex">           
                                                                <p class="mb-1 mt-1 font-medium" style="color: #4A4A4A;">{{ item.name }}</p>
                                                            </v-list-item-title>
                                                        </v-list-item-content> -->
                                                    </v-list-item>
                                                </template>
                                                <template v-slot:append-item>
                                                    <v-divider></v-divider>
                                                    <div v-if="AllAdmins.length > 0">
                                                        <v-list class="my-0 pb-3">
                                                            <v-list-item style="border-bottom:none;padding:0 8px;min-height:20px !important;" v-for="(item,index) in AllAdmins" :key="item.id" >
                                                                <v-list-item-title class="py-0 my-0">
                                                                    <v-checkbox 
                                                                        dense 
                                                                        hide-details 
                                                                        @change="selectItem(item,index)" 
                                                                        v-model="item.active">
                                                                        <template slot="label">
                                                                            <p class="my-auto mb-0" style="color: #6D858F;font-weight: 500;width:100%">{{ item.name }}</p>
                                                                        </template>
                                                                    </v-checkbox>
                                                                </v-list-item-title>
                                                            </v-list-item>
                                                        </v-list>   
                                                    </div>
                                                    <div 
                                                        v-if="AllAdmins.length == 0 && AllAdminsCopy.length !== 0" 
                                                        style="min-height: 40px; padding: 12px; font-size: 14px;">
                                                           No available data
                                                    </div>
                                                </template>
                                                <template v-slot:no-data>
										            <div v-if="getWarehouseLoading" class="option-items loading"
										            	style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
										            	<div class="sku-item">
										            		<v-progress-circular
										            			:size="40"
										            			color="#0171a1"
										            			indeterminate>
										            		</v-progress-circular>
										            	</div>
										            </div>
										            <div v-if="!getWarehouseLoading" class="option-items no-data"
										            	style="min-height: 40px; padding: 12px; font-size: 14px;">
										            	<div class="sku-item">
										            		No available data
										            	</div>
										            </div>
									            </template>
                                            </v-select>
                                        </div>
                                    </v-col>
                                </v-row>
                            </div>
                        </div>
                    </v-card-text>

                    <v-card-actions>
                        <button
                            class="btn-blue"
                            @click="save"
                            :disabled="!email || getUserExistsStatusLoading"
                        >
                            <span>{{
                                getUserExistsStatusLoading
                                    ? "Adding..."
                                    : "Add User"
                            }}</span>
                        </button>

                        <button
                            class="btn-white"
                            @click="close"
                            :disabled="getUserExistsStatusLoading"
                        >
                            Cancel
                        </button>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
        <ConfirmDialog :dialogData.sync="userExistsConfirmDialog">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/info-blue.svg" alt="alert" />
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2>Confirm User</h2>
            </template>

            <template v-slot:dialog_content>
                <p>
                    <b>{{ email }}</b> this person has been user of your company.
                </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn
                    class="btn-blue"
                    text
                    @click="closeDialog"
                >
                    Ok
                </v-btn>
            </template>
        </ConfirmDialog>
        <AddUserConfirmDialog
            :email="email"
            :invitedById="userId"
            :companyId="companyId"
            :confirmDialog.sync="dialogInviteUserConfirm"
            :isInvitedUserExists="getUserExistsStatus"
            @closeConfirmDialog="closeConfirmDialog"
            :group_id="selectedUserGroup"
            :isWarehouseEmployee="markAsWarehouseEmployee"
            :warehouseIds="selectedUsersList"
            :name="name"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import ConfirmDialog from "../../../components/Dialog/GlobalDialog/ConfirmDialog.vue";
import AddUserConfirmDialog from "../Dialog/AddUserConfirmDialog.vue";
import globalMethods from "../../../utils/globalMethods";
import _ from 'lodash'
export default {
    name: "AddUserInviteDialog",
    props: ["dialog", "userId", "companyId"],
    components: {
        AddUserConfirmDialog,
        ConfirmDialog,
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "settings/users");
        this.fetchWarehouse()
    },
    data: () => ({
        isMobile: false,
        valid: true,
        dialogInviteUserConfirm: false,
        userExistsConfirmDialog: false,
        email: "",
        emailRules: [
            (v) =>
                !v ||
                /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                "Email must be valid.",
            (v) => !!v || "Email is required.",
        ],
        selectedUsersList:[],
        markAsWarehouseEmployee:false,
        AllAdmins:[],
        AllAdminsCopy:[],
        searchWhAdmin:'',
        name:'',
        selectedUserGroup:''
    }),
    computed: {
        ...mapGetters({
            getUserExistsStatus: "getUserExistsStatus",
            getUserExistsStatusLoading: "getUserExistsStatusLoading",
            getCustomerAdmins: "getCustomerAdmins",
            getWarehouse:'warehouse/getWarehouse',
            getWarehouseLoading:'warehouse/getWarehouseLoading',
            getGroupWithUsersAndPermissions:'settings/getGroupWithUsersAndPermissions',
        }),
        dialogInvite: {
            get() {
                return this.dialog;
            },
            set(value) {
                this.$emit("update:dialog", value);
            },
        },
        getGroupWithUsersAndPermissionsData(){
			let data = []
			if(typeof this.getGroupWithUsersAndPermissions !== 'undefined' && 
            this.getGroupWithUsersAndPermissions !== null){
				data = this.getGroupWithUsersAndPermissions
			}else{
				data = []
			}
			return data
		},
    },
    watch: {
        dialogInvite:function(newVal){
            if(newVal == true){
                if(this.getWarehouse !== null && this.getWarehouse !== undefined && 
                this.getWarehouse.results !== null && this.getWarehouse.results !== undefined){
                    this.AllAdmins = _.cloneDeep(this.getWarehouse.results)
                    this.AllAdminsCopy = _.cloneDeep(this.getWarehouse.results)
                    this.AllAdmins = this.AllAdmins.map((rest)=> ({active:false,...rest}))
                    this.AllAdminsCopy = this.AllAdminsCopy.map((rest)=> ({active:false,...rest}))
                }         
            }   
        }
    },
    methods: {
        ...mapActions({
            fetchUserExistsStatus: "fetchUserExistsStatus",
            fetchWarehouse:'warehouse/fetchWarehouse'
        }),
        ...globalMethods,
        onResize() {
            if (window.innerWidth < 769) {
                this.isMobile = true;
            } else {
                this.isMobile = false;
            }
        },
        closeDialog() {
            this.$refs.form.resetValidation();
            this.email = "";
            this.$emit("close");
            this.userExistsConfirmDialog = false;
        },
        close() {
            this.$refs.form.resetValidation();
            this.email = "";
            this.searchWhAdmin = ""
            this.AllAdmins = []
            this.AllAdminsCopy = []
            this.name = ''
            this.selectedUsersList = []
            this.selectedUserGroup = ''
            this.markAsWarehouseEmployee = false
            this.$emit("close");
        },
        closeConfirmDialog() {
            this.dialogInviteUserConfirm = false;
            this.email = "";
            this.name = '';
            this.selectedUserGroup = '';
            this.searchWhAdmin = ""
            this.markAsWarehouseEmployee = false
            this.AllAdmins = []
            this.AllAdminsCopy = []
            this.selectedUsersList = []

            if (typeof this.$refs.form !== "undefined") {
				if (typeof this.$refs.form.resetValidation() !== "undefined") {
					this.$refs.form.resetValidation()
				}
			}
        },
        async save() {
            setTimeout(async () => {
                if (this.$refs.form.validate()) {
                    try {
                        let checkExistUserInCompany = this.getCustomerAdmins.filter(obj => obj.email === this.email);
                        if(checkExistUserInCompany.length > 0) {
                            this.$emit("close");
                            this.userExistsConfirmDialog = true;
                        } else {
                            await this.fetchUserExistsStatus({ email: this.email });
                            this.$emit("close");
                            this.dialogInviteUserConfirm = true;
                        }
                    } catch (e) {
                        this.notificationError(e);
                        console.log(e);
                    }
                }
            }, 200);
        },
        /* eslint-disable */
        removeItem(item,index) {
            if(this.searchWhAdmin !== ''){
                let findSelectedOption = _.findIndex(
		  			this.AllAdminsCopy, (e) => e.id == item.id);
                if (findSelectedOption !== null) {
                    this.selectedUsersList.splice(index, 1)
                    this.AllAdminsCopy.map((val)=>{
                        this.AllAdminsCopy[findSelectedOption].active = false
                    })
                }
            }else{
                let findSelectedOption = _.findIndex(
		  			this.AllAdmins, (e) => e.id == item.id);
                if (findSelectedOption !== null) {
                    this.selectedUsersList.splice(index, 1)
                    this.AllAdmins.map((val)=>{
                        this.AllAdmins[findSelectedOption].active = false
                        this.AllAdminsCopy[findSelectedOption].active = false
                    })
                }
            }  
        },
        /* eslint-enable */
        searchWh(){
            if (this.searchWhAdmin !== '') {
                this.AllAdmins = this.AllAdminsCopy.filter((customer) => {
                    return customer.name.toLowerCase().indexOf(this.searchWhAdmin.toLowerCase()) > -1
                })
            } else {
                this.AllAdmins = this.AllAdminsCopy
                return this.AllAdmins
            }
        },
        
        selectItem(item){
            if(this.searchWhAdmin !== ''){
                if(item.active){
                    if(this.selectedUsersList.includes(item.id)){
                        return
                    }else{
                        this.selectedUsersList.push(item)
                        let getIndex = this.AllAdminsCopy.indexOf(item);
                        this.AllAdminsCopy.map(() => {
                            this.AllAdminsCopy[getIndex].active = item.active
                        })
                    }
                }else{
                    if(this.selectedUsersList.includes(item.id)){
                        return
                    }else{
                        // let getIndex = this.selectedUsersList.indexOf(item);
                        this.selectedUsersList = this.selectedUsersList.filter(val => val.id !== item.id)
                        let getAdminIndex = this.AllAdminsCopy.indexOf(item);
                        this.AllAdminsCopy.map(() => {
                            this.AllAdminsCopy[getAdminIndex].active = item.active
                        })
                    }
                }
            }else{
                if(item.active){
                    if(this.selectedUsersList.includes(item.id)){
                        return
                    }else{
                        this.selectedUsersList.push(item)
                        let getIndex = this.AllAdmins.indexOf(item);
                        this.AllAdminsCopy.map(() => {
                            this.AllAdminsCopy[getIndex].active = item.active
                        })
                    }
                }else{
                    if(this.selectedUsersList.includes(item.id)){
                        return
                    }else{
                        let getIndex = this.selectedUsersList.indexOf(item);
                        this.selectedUsersList.splice(getIndex,1)
                        let getAdminIndex = this.AllAdmins.indexOf(item);
                        this.AllAdminsCopy.map(() => {
                            this.AllAdminsCopy[getAdminIndex].active = item.active
                        })
                    }
                }
            }
        }     
    },
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/settings/userInviteDialog.scss";
.prepend-search-in-all-admins-settings {
    position: sticky;
    top: 0px;
    width: 100%;
    z-index: 1;
    .v-input.text-fields .v-input__control .v-input__slot fieldset {
        border: none !important;
        border-bottom: 1px solid #EBF2F5 !important;
    }
}
</style>
