<template>
    <div>
        <div v-if="billLoaded === false"><center>Loading...</center></div>
        <div v-if="billLoaded" class="p-8 dx-viewport container" style="height: 80%; width: 100%; overflow-y:scroll">
            <div class="flex">
                <div class="flex-1" style="width:30%!impotant">
                    <label>Vendor: </label>
                    <vendor-search-box
                        :resourceId="resourceId"
                        @clicked="onVendorClickSelect"
                        @changed="onVendorSelect"
                        :initVendorValue="initVendorValue"
                    ></vendor-search-box>
                </div>
                <div class="flex-1 mr-2"></div>
                <div class="flex-1 mr-2"></div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label>Mailling Address: </label>
                    <textarea class="w-full form-control form-input form-input-bordered" v-model='qbVendorMailingAddress'></textarea>
                </div>
                <div class="flex-1 mr-2">
                    <label>Terms: </label>
                    <v-select
                        :options="terms"
                        :label="'Name'"
                        placeholder="Select Term"
                        @input="onTermSelect"
                        v-model="qbTermName"
                    ></v-select>
                </div>
                <div class="flex-1 mr-2">
                    <label>Bill #:</label>
                    <input class="w-full form-control form-input form-input-bordered" type="text" v-model='qbBillNumber'>
                </div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label>Bill Date: </label>
                    <!-- <datepicker class="w-full form-control form-input form-input-bordered"></datepicker> -->
                    <input class="w-full form-control form-input form-input-bordered" type="date" v-model='qbVendorBillDate'>  
                </div>
                <div class="flex-1 mr-2">
                    <label>Due Date: </label>
                    <input 
                        class="w-full form-control form-input form-input-bordered" 
                        type="date" 
                        v-model="qbVendorDueDate"
                    /> 
                </div>
                <div class="flex-1">
                    <label>Memo: </label>
                    <textarea class="w-full form-control form-input form-input-bordered" v-model='qbMemo'></textarea>
                </div>
            </div>
            <!-- <div class="flex my-5" v-if="expenseAccounts.length > 0 && gridCustomers.length > 0"> -->
            <div class="flex my-5">
                <div class="flex-1">
                    <label>Category Details: </label>
                    <edit-categories-grid
                        v-if="expenseAccounts"
                        :gridCustomers="gridCustomers"
                        :initGridValue="initGridValue"
                        :resourceId="resourceId"
                        :expenseAccounts="expenseAccounts"
                        @clicked="onClickGrid"
                    ></edit-categories-grid>
                </div>
            </div>
            <div class="flex my-3">
                <div class="flex-1">
                    <button
                        :disabled="saving"
                        type="button"
                        ref="addBillSaveButton"
                        data-testid="add-save-button"
                        dusk="add-save-add-invoice-button"
                        @click.prevent="handleUpdateBill"
                        class="btn-default"
                        style="border:0 solid var(--black); background-color:var(--primary); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                    >{{saving ? "Updating...":"Update"}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import EditCategoriesGrid from './EditCategoriesGrid';
import JQuery from 'jquery';
import VendorSearchBox from './VendorSearchBox.vue';
import vSelect from 'vue-select';
window.$ = JQuery

export default {
    name: "EditBillModal",
    components: {
        VendorSearchBox,
        vSelect,
        EditCategoriesGrid
    },
    props: ['terms', 'resourceId', 'gridCustomers', 'expenseAccounts', 'selectedBill','shiflRef'],
    data() {
        return {
            items: [],
            viewLines: false,
            errrors: [],

            selectedVendor:[],
            qbVendorId:0,
            qbVendorName:'',
            qbTermId:0,
            qbTermName:'',
            qbTermDueDays:0,
            qbVendorMailingAddress:'',
            qbVendorBillDate:'',
            qbVendorDueDate:'',
            qbBillNumber:'',
            qbBillId:0,
            qbMemo:'',
            //vendors:[],
            gridValues: [],
            
            initVendorValue:[],
            initGridValue:[],
            
            
            billLoaded:false,


            saving:false,
        };
    },
    created() {
        
        //fetch Bill Data
        if(this.selectedBill > 0){
            axios.get("/custom/bill/by-id/"+this.selectedBill)
            .then(response => {
                const result = response.data.results[0];
                console.log(result)
                if(Object.keys(result).length > 0 ){
                    this.billData = result;
                   
                    
                    this.initVendorValue = [{"DisplayName":result.qbo_vendor_name,"Id":result.qbo_vendor_id}];
                    this.initGridValue = result.bill_items;
                        
                    //set Initial Modal Value
                    this.qbVendorId = result.qbo_bill_id,
                    //line =  this.gridValues,
                    //this.mailingAddress = this.qbVendorMailingAddress,
                    this.qbTermId = result.qbo_term_id;
                    this.qbTermName = result.qbo_term_name;
                    this.qbBillNumber = result.qbo_bill_num;
                    this.qbVendorName = result.qbo_vendor_name;
                    this.qbVendorDueDate = result.qbo_due_date;
                    this.qbVendorBillDate = result.qbo_bill_date;
                    this.qbBillId = result.qbo_bill_id;
                    this.qbVendorMailingAddress = result.qbo_mailing_address;
                    this.qbMemo = result.qbo_memo;

                    this.billLoaded = true;
                }
            })
            .catch(e => {
                console.log(e);
            });
        }
    },
    beforeMount() {
        //console.log(this.customers);
    },
    methods: {
        handleClose() {
            this.$emit("close");
        },
        onClickGrid (value) {
            //console.log(value) // someValue
            this.gridValues = value
        },
        onVendorClickSelect (value) {
            if(value){
                this.selectedVendor = value.Id;
            }
        },
        onVendorSelect(value) {
            if(value){
                this.qbVendorId = value.Id;
                this.selectedVendor = value;
                this.qbVendorName = value.DisplayName;
            }
        },
        onTermSelect(value){
            this.qbTermId= value.Id;
            this.qbtermName = value.Name;
            this.qbTermDueDays= value.DueDays;
        },
        addDays(date,days){
            const result = new Date(date);
            result.setDate(result.getDate() + parseInt(days));
            return result;
        },
        handleUpdateBill() {
            this.saving = true;
            let data = {
                vendorId: this.qbVendorId,
                line: this.gridValues,
                mailingAddress: this.qbVendorMailingAddress,
                termId: this.qbTermId,
                termName: this.qbTermName,
                billNum: this.qbBillNumber,
                vendorName: this.qbVendorName,
                dueDate: this.qbVendorDueDate,
                billDate: this.qbVendorBillDate,
                resourceId : this.resourceId,
                memo: this.qbMemo
            }
            console.log(data)
            this.saving = false;
            // axios.post("/custom/qbo/bill/add",{
            //     vendorId: this.qbVendorId,
            //     line: this.gridValues,
            //     mailingAddress: this.qbVendorMailingAddress,
            //     termId: this.qbTermId,
            //     billNum: this.qbBillNumber,
            //     vendorName: this.qbVendorName,
            //     dueDate: this.qbVendorDueDate,
            //     billDate: this.qbVendorBillDate,
            //     resourceId : this.resourceId
            // })
            // .then(response =>{ 
            //     this.viewLines = true; 
            //     console.log(response.data);
            //     if (response.data == "success") {
            //         console.log(response.data);
            //         Nova.success('Bill Saved Successfully!');
            //         this.$emit('close')
            //         this.saving = false;
            //     }else{
            //         Nova.error(response.data);
            //         this.saving = false;
            //     }
                 
            // })
            // .catch( e => {
            //     this.saving = false;
            //     console.log(e)
            //      Nova.error(event);
            //      this.errors.push(e)
            //      if (response.data.message == 'Unauthenticated.' || response.message == 'Unauthenticated.'){
            //          location.reload(true);
            //      }
                 
            // });
        },
        changeStyle() {
            $('#meModal > div:nth-child(1)').css('height' , '100%');
            $('#meModal > div:nth-child(1) > div:nth-child(1)').css('width' , '60%');
        },
        getAccessToken() {
            return localStorage.getItem('token');
        },
        mapCustomers() {
            const obj = {}

            for(let  i = 0 ; i < customers.length; i++){
                let name = customers[i].ID
                obj[customers[i].ID]=customers[i].Name;
            }
            var customerValues = this.extractValues(obj);
        }
    },

    watch: {
        qbTermDueDays: function(newVal,oldVal){
            if(this.qbVendorBillDate){
                let newDate = this.addDays(this.qbVendorBillDate,newVal);
                console.log(newDate);
                this.qbVendorDueDate = newDate.getFullYear() + '-' + ("0" + (newDate.getMonth() + 1)).slice(-2) + '-' + ("0" + newDate.getDate()).slice(-2);
            }
        },
        qbVendorBillDate: function(newVal,oldVal){
            if(this.qbTermDueDays){
                let newDate = this.addDays(newVal,this.qbTermDueDays);
                console.log(newDate);
                this.qbVendorDueDate = newDate.getFullYear() + '-' + ("0" + (newDate.getMonth() + 1)).slice(-2) + '-' +("0" + newDate.getDate()).slice(-2);
            }
        }
    },

    mounted() {

    }
};

</script>

<style scoped>
    .input-bordered {
        border-width: 1px;
        border-color: black;
        width: auto;
    }

    .fiftyleft {
        float: left;
        /* width: 39% */
        font-size: 12px;
    }

    .fiftyright {
        float: right;
       /*  width: 39% */
       font-size: 12px;
    }

</style>
