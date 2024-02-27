<template>
    <div class="">
        <div class="flex w-full">
            <div class="flex-1">
                <div class="mb-4">
                    <span class="mr-2"><strong>Invoice:</strong> ${{ parseFloat(totalCompanyInvoice).toFixed(2) }}</span>
                    <span class="mr-2"><strong>Bill:</strong> ${{ parseFloat(totalCompanyBill).toFixed(2) }}</span>
                    <span class="text-xl ml-2"><strong>Profit:</strong></span><span class="text-lg font-semibold"> ${{ parseFloat(profit).toFixed(2) }}</span>
                    <span class="text-xl ml-2"><strong>Projected Profit:</strong></span><span class="text-lg font-semibold"> {{ typeof projected_profit == 'string' ? projected_profit : ('$ '+projected_profit) }}</span>
                </div>
            </div>
        </div>
        <div v-if="true" class="flex w-full">
            <div class="flex-1 mr-6">
                <detail-invoices-table
                 :tableData="invoicesTableData"
                 @getCompanyTotalInvoices="getCompanyTotalInvoices"
                />
            </div>
            <div class="flex-1">
                <detail-field-bills-table
                 :tableData="billsTableData"
                 @getCompanyTotalBills="getCompanyTotalBills"
                />
                <!-- <div class="mb-3">
                    <span><strong>Total Bill:</strong> ${{ parseFloat(totalBillAmount).toFixed(2) }}</span>
                </div> -->
                <!-- <bills-table-v2
                 ref="billsTableRef"
                 :realmId="realmId"
                 :tableData="billsTableData"
                 @getCompanyTotalBills="getCompanyTotalBills"
                ></bills-table-v2> -->
            </div>
        </div>
    </div>
</template>

<script>

import _ from 'lodash'
import moment from 'moment'
import DetailInvoicesTable from "./DetailInvoicesTable.vue";
import DetailFieldBillsTable from "./DetailFieldBillsTable.vue";

export default {

    name:"DetailTabContent",

    props: ['tabRealmId','invoices','bills','projected_profit'],

    data() {
        return {
            invoicesTableData:[],
            billsTableData:[],
            totalCompanyInvoice:0,
            totalCompanyBill:0
        }
    },
    components: {
        DetailInvoicesTable,
        DetailFieldBillsTable,
    },
    computed:{
        profit(){
            return this.totalCompanyInvoice - this.totalCompanyBill;
        }
    },
    created() {
        //
    },

    mounted() {
        //
    },

    methods: {
        getCompanyTotalInvoices: function(val){
            this.totalCompanyInvoice = val;
        },
        getCompanyTotalBills: function(val){
            this.totalCompanyBill= val;
        },

        filterTableData: function(data){
            const realmId = this.tabRealmId;
            let filteredValue = _.filter(data, function(o){
                return o.realm_id === realmId
            })
            return filteredValue;
        }
    },

    watch:{
        invoices(newVal,oldval){
            if(this.tabRealmId){
                this.invoicesTableData = this.filterTableData(newVal);
            }
        },
        bills(newVal,oldval){
            if(this.tabRealmId){
                this.billsTableData = this.filterTableData(newVal);
            }
        },
    },

    filters: {
        formatDate: function (value) {
            if (value) {
                return moment(String(value)).format('MM/DD/YYYY')
            }
        }
    }
}
</script>

<style>
</style>
