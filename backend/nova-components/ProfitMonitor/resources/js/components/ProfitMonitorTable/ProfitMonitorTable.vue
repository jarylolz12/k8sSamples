<template>
    <div class="flex w-full">
        <div class="w-full">
            <vue-good-table
            mode="remote"
            :columns="columns"
            :rows="rows"
            :line-numbers="true"
            :sort-options="{
                enabled: true
            }"
            :pagination-options="{
                enabled: true,
                dropdownAllowAll: false,
                perPageDropdown: [50, 100, 150],
            }"
            :totalRows="totalRecords"
            @on-row-click="onRowClick"
            @on-page-change="onPageChange"
            @on-per-page-change="onPerPageChange"
            @on-sort-change="onSortChange"
            @on-column-filter="onColumnFilter"
            styleClass="vgt-table striped"
            compactMode
            />
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
export default {
    name: "ProfitMonitorTable",
    components: {
        VueGoodTable
    },
    props: [],
    data(){
        return{
            columns: [
                { 
                    label: 'Shifl Ref#',
                    field: 'shifl_ref',
                    filterOptions: {
                        styleClass: 'class1',
                        enabled: true,
                        placeholder: 'Filter by Ref#',
                        //trigger: 'enter',
                    },
                    sortable: true,
                },
                { 
                    label: 'Customer',
                    field: 'customer',
                    filterOptions: {
                        styleClass: 'class1',
                        enabled: true,
                        placeholder: 'Filter by Customer',
                        //trigger: 'enter',
                    },
                    sortable: true,
                },
                { label: 'ETA', field: 'eta', sortable: true,},
                { label: 'Total Invoice Amount', field: 'total_invoice_amount', sortable: false,},
                { label: 'Total Bill Amount', field: 'total_bill_amount', sortable: false,},
                { label: 'Profit', field: 'profit', sortable: true,},
                { label: 'Projected Profit', field: 'projected_profit', sortable: true,}
            ],
            rows: [],

            fetchParams: {
                columnFilters: {},
                sort: [{ field: 'eta', type: 'desc',}],
                currentPage: 1, 
                perPage: 50
            },

            filterTrigger:'',
            currentPage:1,
            lastPage:0,
            firstPageUrl:'',
            lastPageUrl:'',
            nextPageUrl:'',
            fromPage:0,
            toPage:0,
            perPage:50,
            totalRecords:0,
            isTableLoading: false,
        }
    },
    computed:{

    },
    created(){
        //
    },
    mounted() {
        this.fetchShipment();
    },
    methods:{
        fetchShipment(){

            let refnumQString = this.fetchParams.columnFilters.shifl_ref ? this.fetchParams.columnFilters.shifl_ref : '';
            let customerQString = this.fetchParams.columnFilters.customer ? this.fetchParams.columnFilters.customer : '';
            let sortBy = this.fetchParams.sort[0].field;
            let sortType = this.fetchParams.sort[0].type;

            axios.get('/nova-vendor/profit-monitor/get-shipments?page=' + this.fetchParams.currentPage + '&per-page='+ this.fetchParams.perPage + '&filterbyrefnum='+refnumQString+'&filterbycustomer='+customerQString+'&sortby='+sortBy+'&sorttype='+sortType)
            .then(response => {
                let result = response.data.results;
                let rowData = [];
                if(Object.keys(result).length > 0){
                    let tableData = result.data;
                    tableData.map(function(o){

                        //get and set invoice and bill amounts
                        let total_invoice_amt = 0;
                        let total_bill_amt = 0;
                        let service_amt = 0;

                        o.invoice.forEach((inv) => {
                            let invoice_sub_total = 0;
                            if(inv.invoice_services){
                               inv.invoice_services.forEach((service)=>{
                                    if( service.quantity != null && service.rate != null ){
                                       service_amt = parseFloat(service.quantity) * parseFloat(service.rate);
                                    }else{
                                       service_amt = 0;
                                    }
                                    invoice_sub_total = invoice_sub_total + service_amt;
                                });
                            }
                            total_invoice_amt = total_invoice_amt + invoice_sub_total;
                        });

                        o.bill.forEach((b) => {
                            let bill_sub_total = 0;
                            if(b.bill_items){
                               b.bill_items.forEach((item)=>{
                                    bill_sub_total = bill_sub_total + parseFloat(item.qbo_amount);
                                });
                            }
                            total_bill_amt = total_bill_amt + bill_sub_total;
                        });


                        // let invoice_amt = 0;
                        // let bill_amt = 0;
                        // o.bill.forEach((b) => {  
                        //     if(b.total_amount === null){
                        //         b.total_amount = 0;
                        //     }
                        //     bill_amt = bill_amt + parseFloat(b.total_amount);
                        // });  

                        // o.invoice.forEach((inv) => {
                        //     if(inv.total_amount === null){
                        //         inv.total_amount = 0;
                        //     }
                        //     invoice_amt = invoice_amt + parseFloat(inv.total_amount);
                        // });
                        // o.bill.forEach((b) => {
                        //     if(b.total_amount === null){
                        //         b.total_amount = 0;
                        //     }
                        //     bill_amt = bill_amt + parseFloat(b.total_amount);    
                        // });

                        //set row data
                        let row = {
                            shifl_ref: o.shifl_ref,
                            customer: o.customer ? o.customer.company_name : 'Missing customer',
                            eta: o.eta ? moment(o.eta).format('YYYY-MM-DD') : '',
                            total_invoice_amount: total_invoice_amt,
                            total_bill_amount: total_bill_amt,
                            profit: parseFloat(total_invoice_amt - total_bill_amt).toFixed(2),
                            projected_profit: o.projected_profit
                        }
                        rowData.push(row);
                    })
                    this.totalRecords = result.total;
                }
                this.rows = rowData;
            })
            .catch(e => {
                console.log(e);
            })
        },

        updateParams(newProps) {
            this.fetchParams = Object.assign({}, this.fetchParams, newProps);
        },

        onRowClick(row){
            //if click redirect page to shipment charges tab
        },

        onPageChange(params){
            this.updateParams({currentPage: params.currentPage});
            this.fetchShipment();
        },

        onPerPageChange(params) {
            this.updateParams({perPage: params.currentPerPage});
            this.fetchShipment();
        },

        onSortChange(params){
            this.updateParams({
                sort: [{
                    type: params[0].type,
                    field: params[0].field
                }],
            });
            this.fetchShipment();
        },

        onColumnFilter(params){
            this.updateParams({
                columnFilters : {
                    customer: params.columnFilters.customer,
                    shifl_ref: params.columnFilters.shifl_ref,
                },
                sort: [{
                    type: 'desc',
                    field: this.filterTrigger !== '' ? this.filterTrigger : 'eta'
                }],
            });
            this.fetchShipment();
        },

        filterfn: function(data, filterString){
            cosole.log(data);
        }
    },
    watch: {
        'fetchParams.columnFilters.customer': function(newVal,oldVal){
            this.filterTrigger = 'customer';
        },
        'fetchParams.columnFilters.shifl_ref': function(newVal,oldVal){
            this.filterTrigger = 'shifl_ref';
        }
    },
}
</script>

<style scoped>
</style>
