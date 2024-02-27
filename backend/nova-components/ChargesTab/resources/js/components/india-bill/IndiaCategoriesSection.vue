<template>
    <div>
        <input
            type="hidden"
            v-model="categoryValue"
        />
        <div class="mt-2">
            <button
             style="border-radius: 0px;"
             class="btn-add-item btn btn-default btn-sm inline-flex items-center mr-3"
             @click.prevent="addItem()"
             >Add Item
            </button>
        </div>
        <div class="label-container mt-2" v-if="itemGroup.length > 0">
            <div class="item-label flex">
                <div class="inline-block" style="width:30%">
                    <center>Category</center>
                </div>
                <div class="inline-block" style="width:25%">
                    <center>Description</center>
                </div>
                <div class="inline-block" style="width:15%">
                    <center>Amount {{exchangeRateValue !== null ? '('+exchangeRateValue.SourceCurrencyCode+')' : ''}}</center>
                </div>
                <div class="inline-block" style="width:20%">
                    <center>Tax</center>
                </div>
                <div class="inline-block" style="width:10%"></div>
            </div>
        </div>
        <div class="items-container mt-1">
            <div :id="`category-group-${item.id}`" v-for="(item,key) in itemGroup" :key="item.key" class="flex mt-2">
                <div class="inline-block mr-1" style="width:30%">
                    <div>
                        <v-select
                            id="categorySelect"
                            :options="categoryOptions"
                            :label="'Name'"
                            placeholder="Search Category"
                            @search="searchCategory"
                            v-model="item.Category"
                        >
                            <template v-slot:option="option">
                                {{ option.Name }} <span style="font-style:italic; float:right; color:#6B7280">{{option.AccountType}}</span>
                            </template>
                        </v-select>
                    </div>
                </div>
                <div class="inline-block mr-1" style="width:25%">
                    <div>
                        <input placeholder="Description" type="text" v-model="item.Description" class=" w-full form-control-sm form-control form-input form-input-bordered item-input" />
                    </div>
                </div>
                <div class="inline-block mr-1" style="width:15%">
                    <div>
                        <input
                         v-model="item.Amount" 
                         placeholder="Amount" 
                         type="number" 
                         class="w-full form-control-sm form-control form-input form-input-bordered item-input"
                        />
                    </div>
                </div>
                <!-- <div class="inline-block mr-1" style="width:30%">
                    <div>
                        <v-select
                            id="customerSelect"
                            :options="customerOptions"
                            :label="'DisplayName'"
                            placeholder="Search Customer"
                            @search="searchCustomer"
                            v-model="item.Customer"
                        ></v-select>
                    </div>
                </div> -->
                <div class="inline-block mr-1"  style="width:20%">
                    <div>
                        <v-select
                            id="serviceSelect"
                            :options="qboTaxCodes"
                            :label="'Name'"
                            placeholder="Select Tax"
                            v-model="item.Tax"
                        >
                        </v-select>
                    </div>
                </div>
                <div class="inline-block" style="width:10%">
                    <button style="border-radius: 0px; padding-left: 1rem; padding-right: 1rem;" class="btn-delete-item btn btn-sm btn-default appearance-none cursor-pointer btn-danger inline-flex items-center relative mr-3" @click.prevent="removeItemGroup(item,key)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                            <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-col mt-4 mb-4" v-if="itemGroup.length > 0">
            <div v-if="totalTax" class="w-full flex flex-row py-2" style="justify-content:flex-end">
                <div class="w-1/6">
                    <label class="font-semibold text-xl mr-2 float-right">Total Tax Calculated</label>
                </div>
                <div class="w-1/6">
                    <label class="font-semibold text-xl mr-2 float-right">{{totalTax ? parseFloat(totalTax).toFixed(2) : 0}}</label>
                </div>
            </div>
            <div class="w-full flex flex-row py-2" style="justify-content:flex-end">
                <div class="w-1/6">
                    <label class="font-semibold text-xl mr-2 float-right">Total {{exchangeRateValue !== null ? '('+exchangeRateValue.SourceCurrencyCode+')' : ''}}</label>
                </div>
                <div class="w-1/6">
                    <label class="font-semibold text-xl mr-2 float-right">{{total ? parseFloat(total).toFixed(2) : 0}}</label>
                </div>
            </div>
            <div v-if="exchangeRateValue !== null" class="w-full flex flex-row py-2" style="justify-content:flex-end">
                <div class="w-1/6">
                    <label class="font-semibold text-xl mr-2 float-right">Total{{'('+exchangeRateValue.TargetCurrencyCode+')'}}</label>
                </div>
                <div class="w-1/6">
                    <label class="font-semibold text-xl mr-2 float-right">{{homeTotalAmount ? parseFloat(homeTotalAmount).toFixed(2) : 0}}</label>
                </div>
            </div>
            <!-- <div class="total-amount-label font-semibold" style="margin-left: auto;">
                <div style="margin-right:85px;">
                    <label class="mr-2 float-right">Total: {{totalAmount ? parseFloat(totalAmount).toFixed(2) : 0}}</label>
                    <div v-if="totalTax">
                        <label class="mr-2 float-right" style="font-style:italic;">Calculated Tax: {{totalTax ? parseFloat(totalTax).toFixed(2) : 0}}</label>
                        <br/>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>

<script>    

import axios from 'axios';
//import jQuery from 'jquery';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import './sass/bill.scss';

export default {
    name: "IndiaCategoriesSection",
    components: {  },

    props: ["resourceId","initCategorySectionValue","totalTax","exchangeRateValue"],

    data() {
        return {
           itemGroup:[],
           categoryValue:{},
           categoryOptions: [],
           customerOptions:[],
           totalAmount:0,
           qboTaxCodes:[],
           grandTotalAmount:0,
        }
    },
    computed:{
        total() {
           let getAmounts = _.map(this.itemGroup,function(o){return _.toNumber(o.Amount)});
           return _.sum(getAmounts);
        },
        homeTotalAmount(){
            if(this.exchangeRateValue !== null){
                return this.total * parseFloat(this.exchangeRateValue.Rate);
            }else{
                return 0;
            }
        }
    },
    beforeCreate: function () {
        //
    },
    created() {
        if(Object.keys(this.initCategorySectionValue).length > 0){
            let vm = this;
            let setId = new Date().getTime();
            _.forOwn(this.initCategorySectionValue, function(o){ 
                const taxValue = JSON.parse(o.qbo_tax_code)
                vm.itemGroup.push({
                    id: setId,
                    bill_item_id:o.id,
                    Category:{"Id":o.qbo_item_value,"Name":o.qbo_item_name},
                    Description: o.qbo_description,
                    Amount: o.qbo_amount,
                    Tax: taxValue
                    //Customer:{"Id":o.qbo_customer_id,"DisplayName":o.qbo_customer_name},
                });
            });
            
            this.calTotalAmount();
            this.calGrandTotalAmount();
        }

        this.getTaxCodes();
    },
    beforeMount() {
       //
    },
    mounted(){ 
        //
    },
    methods: {
        addItem(){
            let setId = new Date().getTime();
            this.itemGroup.push({
                id: setId,
                bill_item_id:0,
                Category:null,
                Description: '',
                Amount: '',
                Customer:null,
                Tax:null,
            });
            this.categoryValue = JSON.stringify(this.itemGroup);
        },

        removeItemGroup(item,key) {
            this.$delete(this.itemGroup,key);
            this.calTotalAmount();
            if(this.totalTax){
                this.calGrandTotalAmount();
            }
        },

        searchCategory(searchString,loading){
            if(searchString.length > 1){ 
                loading(true);
                this.fetchCategory(loading, searchString, this);
            }
        },

        fetchCategory: _.debounce((loading,searchString,vm)=>{
            axios.get('/custom/qbo/bill/accounts-expense-search?query='+searchString)
            .then(response => {
                if(response.data.length>0){
                    vm.categoryOptions = response.data
                }else{
                    vm.categoryOptions = []
                }
                loading(false);
            })
            .catch(e => {
                console.log(e)
                loading(false);
            })
        },
        500),

        searchCustomer(searchString,loading){
            if(searchString.length > 1){ 
                loading(true);
                this.fetchCustomer(loading, searchString, this);
            }
        },

        fetchCustomer: _.debounce((loading,searchString,vm)=>{
            axios.get('/custom/qbo/bill/customer-search?query='+searchString)
            .then(response => {
                if(response.data.length>0){
                    vm.customerOptions = response.data
                }else{
                    vm.customerOptions = []
                }
                loading(false);
            })
            .catch(e => {
                console.log(e)
                loading(false);
            })
        },
        500),

        calTotalAmount: function(){
            let getAmounts = _.map(this.itemGroup,function(o){return _.toNumber(o.Amount)});
            this.totalAmount = _.sum(getAmounts);
        },
        
        calGrandTotalAmount: function(){
            let getAmounts = _.map(this.itemGroup,function(o){return _.toNumber(o.Amount)});
            if(this.totalTax){
                this.grandTotalAmount = _.sum(getAmounts) + this.totalTax;
            }
        },

        getTaxCodes: function (){
            axios.get('/custom/qbo/tax/get-tax-codes')
            .then(response => {
                if(response.data.success){
                    this.qboTaxCodes = response.data.result;
                }
            })
            .catch(e => {
                console.log(e)
            })
        },
        
        // onSelectTax: function(val,key){
        //     console.log(val);
        //     console.log(key);
        //     if(val.SalesTaxRateList.TaxRateDetail){
        //         let taxRateDetail = val.SalesTaxRateList.TaxRateDetail;
        //         if(taxRateDetail.length > 0){
                
        //         }else{
        //             this.getTaxRate(taxRateDetail.TaxRateRef,key);
                   
        //         }
                
        //     }
        // },

        // getTaxRate:function (taxRateRef,key){
        //     axios.get('/custom/qbo/tax/get-taxrate-by-id?taxRateRef='+taxRateRef)
        //     .then(response => {
        //         console.log(response);
        //         if(response.data.success){
        //             console.log(response.data.result);
        //         }
        //     })
        //     .catch( e =>{
        //         console.log(e);
        //     })
        // },
        
        // calculateTax: function(){

        // }
    },
    
    watch: {
        itemGroup: function(newVal,oldVal){
            this.$emit('valueChanged', this.itemGroup);
        },
        deep: true
    }
};
</script>

<style scoped>

.item-input{
    border-radius: 0 !important;
}
.item-input:focus{
    border-radius: 0 !important;
}
/*prevent overflow in v-select when label is too long */
.items-container{
    width: 100%;
    /* max-height: 150px; */
    /* overflow-y: auto;
    overflow-x: auto; */
    /* min-height: 100px; */
}
.btn-add-item{
    background-color:#ffffff !important;
    color:#171717 !important;
    border:1px solid #737373 !important;
    line-height: 1rem !important;
    text-shadow: none !important;
    padding-left: .50rem !important;
    padding-right: .50rem !important;
}
.btn-add-item:hover{
    background-color:#E5E5E5 !important;
    color:#171717 !important;
}
.btn-delete-item{
    background-color:#EF4444;
    color:#ffffff !important;
}
.btn-delete-item:hover{
    background-color:#DC2626 !important;
}
.btn-delete-item:active{
    -webkit-box-shadow: 0 !important;
    box-shadow: 0 !important;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
