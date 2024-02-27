<template>
    <div class="">
        <div class="flex w-full">
            <div class="flex-1">
                <div class="mb-4">
                    <span class="mr-2"><strong>Invoice:</strong> ${{ parseFloat(totalCompanyInvoice).toFixed(2) }}</span>
                    <span class="mr-2"><strong>Bill:</strong> ${{ parseFloat(totalCompanyBill).toFixed(2) }}</span>
                    <span class="text-xl ml-2"><strong>Profit:</strong></span><span class="text-lg font-semibold"> ${{ parseFloat(profit).toFixed(2) }}</span>
                </div>
            </div>
        </div>
        <div v-if="true" class="flex w-full">
            <div class="flex-1 mr-6">
                <invoices-table-v2
                 ref="invoicesTableRef"
                 :realmId="realmId"
                 :tableData="invoicesTableData"
                 @openEditInvoiceModal="openEditInvoiceModal"
                 @getCompanyTotalInvoices="getCompanyTotalInvoices"
                ></invoices-table-v2>
            </div>
            <div class="flex-1">
                <bills-table-v2
                 ref="billsTableRef"
                 :realmId="realmId"
                 :tableData="billsTableData"
                 @getCompanyTotalBills="getCompanyTotalBills"
                ></bills-table-v2>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash'
import moment from 'moment'
import { mapGetters } from 'vuex'

import InvoicesTableV2 from '../invoice/InvoicesTableV2.vue'
import BillsTableV2 from '../bill/BillsTableV2.vue'

export default {

    props: ['realmId','companyInfo'],

    data() {
        return {
            invoicesTableData:[],
            billsTableData:[],
            totalCompanyInvoice:0,
            totalCompanyBill:0,
        }
    },
    components: {
        InvoicesTableV2,
        BillsTableV2,
    },
    computed:{
        ...mapGetters({
            shipmentInvoices: 'shipmentInvoices',
            shipmentBills: 'shipmentBills',
        }),
        profit(){
            return this.totalCompanyInvoice - this.totalCompanyBill;
        }
    },
    created() {
        //
    },

    mounted() {
        this.getShipmentInvoices();
        this.getShipmentBills();
    },

    methods: {
        openEditInvoiceModal() {
            this.$emit('openEditInvoiceModal')
        },
        getShipmentInvoices: function(){
            const state = this.$store.state.chargesTab;
            const realmId = this.realmId;
            let filteredValue = _.filter(state.shipmentInvoices, function(o){
                return o.realm_id === realmId
            })
            this.invoicesTableData = filteredValue;
        },
        getShipmentBills: function(){
            const state = this.$store.state.chargesTab;
            const realmId = this.realmId;
            let filteredValue = _.filter(state.shipmentBills, function(o){
                return o.realm_id === realmId
            })
            this.billsTableData = filteredValue;
        },
        getCompanyTotalInvoices: function(val){
            this.totalCompanyInvoice = val;
        },
        getCompanyTotalBills: function(val){
            this.totalCompanyBill= val;
        }
    },

    watch:{
        shipmentInvoices(newVal,oldval){
            const realmId = this.realmId;
            let filteredValue = _.filter(newVal, function(o){
                return o.realm_id === realmId
            })
            this.invoicesTableData = filteredValue;
        },
        shipmentBills(newVal,oldval){
            const realmId = this.realmId;
            let filteredValue = _.filter(newVal, function(o){
                return o.realm_id === realmId
            })
            this.billsTableData = filteredValue;
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
