<template>
    <div>
        <input
            type="hidden"
            v-model="serviceValue"
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
                <div class="inline-block mr-1" style="width:35%">
                    <center>Service</center>
                </div>
                <div class="inline-block mr-1" style="width:28%">
                    <center>Description</center>
                </div>
                <div class="inline-block mr-1" style="width:10%">
                    <center>Quantity</center>
                </div>
                <div class="inline-block mr-1" style="width:10%">
                    <center>Unit Price</center>
                </div>
                <div class="inline-block mr-1" style="width:10%">
                    <center>Amount {{exchangeRateValue !== null ? '('+exchangeRateValue.SourceCurrencyCode+')' : ''}}</center>
                </div>
                <div class="inline-block mr-1" style="width:5%"></div>
            </div>
        </div>
        <div class="items-container mt-1">
            <div :id="`service-group-${item.id}`" v-for="(item,key) in itemGroup" :key="item.key" class="flex mt-2">
                <div class="inline-block mr-1"  style="width:35%">
                    <div>
                        <v-select
                            id="serviceSelect"
                            :options="serviceOptions"
                            :label="'Name'"
                            placeholder="Search Service"
                            @search="searchService"
                            v-model="item.Service"
                        >
                            <template v-slot:option="option">
                                {{ option.Name }} <span style="font-style:italic; float:right; color:#6B7280">{{option.Type}}</span>
                            </template>
                        </v-select>
                    </div>
                </div> 
                <div class="inline-block mr-1" style="width:28%">
                    <div>
                        <input placeholder="Description" type="text" v-model="item.Description" class=" w-full form-control-sm form-control form-input form-input-bordered item-input" />
                    </div>
                </div>
                <div class="inline-block mr-1" style="width:10%">
                    <div>
                        <input
                         v-model.number="item.Quantity"
                         placeholder="Quantity"
                         type="number"
                         class="w-full form-control-sm form-control form-input form-input-bordered item-input"
                        />
                    </div>
                </div>
                <div class="inline-block mr-1" style="width:10%">
                     <div>
                        <input
                         v-model.number="item.UnitPrice"
                         placeholder="Unit Price"
                         type="number"
                         class="w-full form-control-sm form-control form-input form-input-bordered item-input"
                        />
                    </div>
                </div>
                <div class="inline-block mr-1" style="width:10%">
                    <div>
                        <input
                         v-model.number="subtotalRow[key]"
                         placeholder="Amount"
                         readonly
                         class="w-full form-control-sm form-control form-input form-input-bordered item-input"
                        />
                    </div>
                </div>
                <div class="inline-block" style="width:5%">
                    <button style="border-radius: 0px; padding-left: 1rem; padding-right: 1rem;" class="btn-delete-item btn btn-sm btn-default appearance-none cursor-pointer btn-danger inline-flex items-center relative mr-3" @click.prevent="removeItemGroup(item,key)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                            <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-col mt-4 mb-3" v-if="itemGroup.length > 0">
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
            <div v-if="edit" class="mt-2">
                <payment-detail
                 :selectedInvoiceQBOId="selectedInvoiceQBOId"
                 :subTotal="total ? parseFloat(total).toFixed(2) : 0"
                />
            </div>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import jQuery from 'jquery';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import './sass/invoice.scss';
import PaymentDetail from '../common/PaymentDetail.vue';

export default {
    name: "ServicesSectionBak",
    components: {
        PaymentDetail
    },

    props: ["resourceId","initServiceSectionValue","edit", "selectedInvoiceQBOId","exchangeRateValue"],

    data() {
        return {
           itemGroup:[],
           serviceValue:{},
           serviceOptions: [],
           totalAmount:0,
           currencyRef:null,
           exchangeRate:0,
        }
    },
    beforeCreate: function () {
        //
    },
    created() {
        if(Object.keys(this.initServiceSectionValue).length > 0){
            //console.log(Object.keys(this.initServiceSectionValue).length)
            let vm = this;
            let setId = new Date().getTime();
            //console.log(this.initServiceSectionValue);
            _.forOwn(this.initServiceSectionValue, function(o){
                vm.itemGroup.push({
                    id: o.id,
                    invoice_item_id:o.id,
                    Service:{"Id":o.qbo_service_id,"Name":o.qbo_service_name},
                    Description: o.description,
                    Quantity: o.quantity,
                    UnitPrice: o.rate,
                });
            });
            //this.serviceValue = JSON.stringify(this.itemGroup);
            //this.calTotalAmount();
        }
    },
    beforeMount() {

    },
    mounted(){
    },
    computed: {
        subtotalRow() {
          return this.itemGroup.map((item) => {
            return Number(item.Quantity * item.UnitPrice)
          });
        },
        total() {
          return this.itemGroup.reduce((total, item) => {
            return total + item.Quantity * item.UnitPrice;
          }, 0);
        },
        homeTotalAmount(){
            if(this.exchangeRateValue !== null){
                return this.total * parseFloat(this.exchangeRateValue.Rate);
            }else{
                return 0;
            }
        }
    },
    methods: {
        addItem(){
            let setId = new Date().getTime();
            this.itemGroup.push({
                id: setId,
                invoice_item_id:0,
                Service:null,
                Description: '',
                Quantity: 1,
                UnitPrice: 0,
            });
            this.serviceValue = JSON.stringify(this.itemGroup);
        },

        removeItemGroup(item,key) {
            this.$delete(this.itemGroup,key);
            //this.calTotalAmount();
        },

        searchService(searchString,loading){
            if(searchString.length > 1){
                loading(true);
                this.fetchService(loading, searchString, this);
                //console.log(this.serviceOptions)
            }
        },

        fetchService: _.debounce((loading,searchString,vm)=>{
            axios.get('/custom/qbo/services-search?query='+searchString)
            .then(response => {
                //console.log(response.data);
                let filteredData = [];
                if(response.data.length>0){
                    filteredData = _.reject(response.data, function(o){
                        return o.Type === 'Category';
                    });
                    vm.serviceOptions = filteredData;
                    //console.log(filteredData)
                }else{
                    vm.serviceOptions = []
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
            this.totalAmount = _.sum(getAmounts);
        },
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
