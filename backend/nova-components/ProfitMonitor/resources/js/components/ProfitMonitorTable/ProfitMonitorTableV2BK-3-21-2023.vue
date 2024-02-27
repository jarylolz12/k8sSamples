<template>
<div class="flex w-full mt-4">
    <div class="w-full">        
        <div class="flex w-full mb-2 pl-2 pr-2">
            <div class="flex-auto">
                <span class="uppercase text-90 text-2xl font-bold mr-4" v-if="fetchParams.columnFilters.customer && fetchParams.columnFilters.customer != ''">Total Profit:</span><span class="text-90 text-xl font-semibold">{{totalProfit ? parseFloat(totalProfit).toFixed(2) : parseFloat(0).toFixed(2)}}</span>
            </div>
            <div class="flex content-end">
                <button 
                @click="importXls()" 
                style="
                    padding: 5px 8px;
                    border: 1px solid #ededed;
                    background: #ededed;
                    border-radius: 5px;
                ">Export</button>
            </div>
        </div>
        <vue-good-table mode="remote" :columns="columns" :rows="rows" :line-numbers="true" :sort-options="{
                enabled: true,
                initialSortBy: {field: 'profit', type: 'asc'}
            }" :pagination-options="paginationOptions" :totalRows="totalRecords" @on-row-click="onRowClick" @on-page-change="onPageChange" @on-per-page-change="onPerPageChange" @on-sort-change="onSortChange" @on-column-filter="onColumnFilter" styleClass="vgt-table striped" compactMode>

            <!-- ///// -->

            <div class="text-center items-center" slot="emptystate">
                <icon v-if="loading" icon_type="loading" width="20" height="20" color="green"/>
                <div v-if="!loading" class="text-center">
                    No records found.
                </div>
            </div>
            <div slot="table-actions" class="flex">

                <div class="mt-3  items-center  select-none bg-30 px-3 border-2 border-30 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="filter" role="presentation" class="fill-current text-80">
                        <path fill-rule="nonzero"
                          d="M.293 5.707A1 1 0 0 1 0 4.999V1A1 1 0 0 1 1 0h18a1 1 0 0 1 1 1v4a1 1 0 0 1-.293.707L13 12.413v2.585a1 1 0 0 1-.293.708l-4 4c-.63.629-1.707.183-1.707-.708v-6.585L.293 5.707zM2 2v2.585l6.707 6.707a1 1 0 0 1 .293.707v4.585l2-2V12a1 1 0 0 1 .293-.707L18 4.585V2H2z">
                        </path>
                    </svg>

                </div>

                <!-- //// -->
                <div>
                    <h3 class="text-sm uppercase tracking-wide text-80 bg-30 p-3">
                        Filter by Profit
                    </h3>
                    <div class="p-2">
                        <select class="block w-full form-control-sm form-select" v-model="profit_filter">
                            <option value="">—</option>
                            <option value="less than 50"> Profit less than $50</option>
                            <option value="profit less than projected"> Profit less than projected</option>
                        </select>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm uppercase tracking-wide text-80 bg-30 p-3">
                        Filter by Sales Person
                    </h3>
                    <div class="p-2">
                        <select dusk="Filter by Account Manager-filter-select" class="block w-full form-control-sm form-select" v-model="selectedSalesRepresentative" @change="(e)=>{ fetchShipment() }">
                            <option :value="null" selected="selected">—</option>
                            <option v-for="option in salesRepresentative" :value="option.id" :key="option.id"> {{ option.name }} </option>
                        </select>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm uppercase tracking-wide text-80 bg-30 p-3">
                        Filter by MONTH
                    </h3>
                    <div class="p-2">

                        <vue-monthly-picker class="block w-full w-full " v-model="selectedMonth" @selected="(e)=>{ fetchShipment() }" :monthLabels="['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']">
                        </vue-monthly-picker>
                    </div>
                </div>
            </div>
            <template slot="table-row" slot-scope="props">
                <div style="position:relative;" v-if="props.column.field == 'notes'">
                  {{ props.formattedRow['notes'] }}
                  <v-popover @show="notes=props.formattedRow['notes']" placement="auto" offset="16">
                      <!-- This will be the popover target (for the events and position) -->
                      <span class="tooltip-target" v-tooltip="{ content: ( props.formattedRow[props.column.field] ? 'Update' : 'Create' ) }" style="position:absolute;top:5px;right:0px;z-index:10;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width:16px"><path fill="#039ddc" d="M362.7 19.32C387.7-5.678 428.3-5.678 453.3 19.32L492.7 58.75C517.7 83.74 517.7 124.3 492.7 149.3L444.3 197.7L314.3 67.72L362.7 19.32zM421.7 220.3L188.5 453.4C178.1 463.8 165.2 471.5 151.1 475.6L30.77 511C22.35 513.5 13.24 511.2 7.03 504.1C.8198 498.8-1.502 489.7 .976 481.2L36.37 360.9C40.53 346.8 48.16 333.9 58.57 323.5L291.7 90.34L421.7 220.3z"/></svg></span>

                      <!-- This will be the content of the popover -->
                      <template slot="popover">
                        <textarea class="w-full form-control form-input form-input-bordered py-3 h-auto" v-model="notes" placeholder="Notes" />
                        <div>
                            <button class="btn btn-default btn-primary inline-flex items-center relative mr-3" @click="saveNote(props.formattedRow['id'])" :disabled="is_note_saving">{{ is_note_saving ? 'Saving...' : 'Save' }}</button>
                            <button class="btn btn-default btn-danger inline-flex items-center relative mr-3" v-close-popover>Cancel</button>
                        </div>

                      </template>
                    </v-popover>
                </div>
                <div style="position:relative;" v-else-if="props.column.field == 'view'">
                    <span class="inline-flex">
                        <a
                            :href="`resources/shipments/${props.formattedRow['id']}`"
                            target="_blank"
                            class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center has-tooltip"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 16" aria-labelledby="view" role="presentation" class="fill-current"><path d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path></svg>
                        </a>
                    </span>
                </div>
                <span v-else>
                  {{ props.formattedRow[props.column.field] }}
                </span>
              </template>
            <!-- ///// -->
        </vue-good-table>
    </div>
</div>
</template>

<script>
import axios from 'axios';
import 'vue-good-table/dist/vue-good-table.css'
import VueMonthlyPicker from 'vue-monthly-picker'
import {
    VueGoodTable
} from 'vue-good-table';
import _ from "lodash";
import Icon from '../Icon';
import { VTooltip, VPopover, VClosePopover } from 'v-tooltip'

export default {
    name: "ProfitMonitorTable",
    directives: { VTooltip, VClosePopover, VPopover },
    components: {
        VueGoodTable,
        VueMonthlyPicker,
        Icon
    },
    props: ["tabRealmId"],

    data() {
        return {
            paginationOptions: {
                enabled: true,
                dropdownAllowAll: false,
                perPageDropdown: [ 50, 100, 150,300,600,1000 ]
            },
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    sortable: false,
                    hidden: true,
                },
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
                {
                    label: 'ETA',
                    field: 'eta',
                    sortable: true,
                },
                {
                    label: 'Total Invoice Amount',
                    field: 'total_invoice_amount',
                    sortable: false,
                },
                {
                    label: 'Total Bill Amount',
                    field: 'total_bill_amount',
                    sortable: false,
                },
                {
                    label: 'Profit',
                    field: 'profit',
                    sortable: true,
                    firstSortType: 'asc'
                },
                {
                    label: 'Projected Profit',
                    field: 'projected_profit',
                    sortable: true,
                },
                {
                    label: 'Notes',
                    field: 'notes',
                    sortable: false,
                },
                {
                    label: 'View',
                    field: 'view',
                    sortable: false,
                }
            ],
            rows: [],
            fetchParams: {
                columnFilters: {},
                sort: [{
                    field: 'eta',
                    type: 'desc',
                }],
                currentPage: 1,
                perPage: 50
            },
            filterTrigger: '',
            currentPage: 1,
            lastPage: 0,
            firstPageUrl: '',
            lastPageUrl: '',
            nextPageUrl: '',
            fromPage: 0,
            toPage: 0,
            perPage: 50,
            totalRecords: 0,
            isTableLoading: false,
            selectedMonth: null,
            salesRepresentative: [],
            selectedSalesRepresentative: null,
            totalProfit:0,
            loading: false,
            profit_filter: '',
            notes: '',
            is_note_saving: false,
            is_booted: false
        }
    },
    watch: {
        'fetchParams.columnFilters.customer': function(newVal, oldVal) {
            this.filterTrigger = 'customer';
        },
        'fetchParams.columnFilters.shifl_ref': function(newVal, oldVal) {
            this.filterTrigger = 'shifl_ref';
        }
    },
    computed: {
        // totalProfit(){
        //     let profit = 0;
        //     this.rows.forEach((row)=>{
        //         console.log(row.profit);
        //          profit = profit + parseFloat(row.profit)
        //     })
        //     return profit;
        // }
    },
    created() {
        //
    },
    mounted() {
        this.fetchSalesRepresentatives();
        this.fetchShipment();
    },
    methods: {
        saveNote(id){
            let _this = this;

            this.is_note_saving = true;

            axios.post("/nova-vendor/profit-monitor/save-notes",{
                id: id,
                notes: this.notes
            })
            .then(res => {
                _this.$toasted.show(res.data.message, { type: res.data.success ? 'success' : 'error' });

                _this.is_note_saving = true;
            }).catch(err => {
                if( err ){
                    _this.$toasted.show('Something went wrong, unable to process request', { type: 'error' })
                    console.log(err)
                }
                _this.is_note_saving = true;
            });
        },
        fetchSalesRepresentatives() {
            axios.get("/nova-vendor/profit-monitor/get-sales-representatives")
            .then(res => {
                this.salesRepresentative = res.data
                // console.log(this.salesRepresentative)
            }).catch(err => {
                console.log(err)
            });
        },
        fetchShipment() {
            this.rows = [];
            this.totalRecords = 0;
            this.loading = true;

            let _this = this;
            let refnumQString = this.fetchParams.columnFilters.shifl_ref ? this.fetchParams.columnFilters.shifl_ref : '';
            let customerQString = this.fetchParams.columnFilters.customer ? this.fetchParams.columnFilters.customer : '';
            let sortBy = this.fetchParams.sort[0].field;
            let sortType = this.fetchParams.sort[0].type;
            let sale_persons = this.selectedSalesRepresentative ? this.selectedSalesRepresentative : '';

            axios.get('/nova-vendor/profit-monitor/get-shipments-v2?profit_filter='+this.profit_filter+'&realmId=' + this.tabRealmId + '&page=' + this.fetchParams.currentPage + '&per-page=' + this.fetchParams.perPage + '&filterbyrefnum=' + refnumQString + '&filterbycustomer=' +
                    customerQString + '&sortby=' + sortBy + '&sorttype=' + sortType + '&sale_persons=' + sale_persons + '&month=' + (_.isEmpty(this.selectedMonth) ? '' : this.selectedMonth.format("YYYY/MM/DD").toString()))
                .then(response => {
                    _this.loading = false;

                    let result = response.data.data.map( i =>{
                        i.profit = i.profitt;

                        return i;
                    });

                    //let result = response.data.results;
                    //  console.log(response)
                    let rowData = [];
                    
                    if (Object.keys(result).length > 0) {
                        //let tableData = result.data;
                        let tableData = result
                        tableData.map(function(o) {

                            //get and set invoice and bill amounts
                            let total_invoice_amt = 0;
                            let total_bill_amt = 0;
                            let service_amt = 0;
                            o.invoice.forEach((inv) => {
                                let invoice_sub_total = 0;
                                if (inv.invoice_services) {
                                    inv.invoice_services.forEach((service) => {
                                        if (service.quantity != null && service.rate != null) {
                                            service_amt = parseFloat(service.quantity) * parseFloat(service.rate);
                                        } else {
                                            service_amt = 0;
                                        }
                                        invoice_sub_total = invoice_sub_total + service_amt;
                                    });
                                }
                                total_invoice_amt = total_invoice_amt + invoice_sub_total;
                            });
                            o.bill.forEach((b) => {
                                let bill_sub_total = 0;
                                if (b.bill_items) {
                                    b.bill_items.forEach((item) => {
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
                                total_invoice_amount: parseFloat(total_invoice_amt).toFixed(2),
                                total_bill_amount: parseFloat(total_bill_amt).toFixed(2),
                                id: o.id,
                                notes: o.notes ? o.notes.content : '',
                                profitt: o.profitt,
                                profit: parseFloat(total_invoice_amt - total_bill_amt).toFixed(2),
                                projected_profit: o.projected_profit
                            }

                            rowData.push(row);

                        });


                        this.totalRecords = response.data.total
                        //this.totalRecords = result.total;
                    }

                    if( _this.profit_filter == 'less than 50' ){
                        rowData = rowData.filter( i => i.profit < 50 );
                    }else if( _this.profit_filter == 'profit less than projected'){
                        rowData = rowData.filter( i => i.profit < i.projected_profit );
                    }

                    _this.rows = rowData;

                    if( customerQString != '' ){
                        _this.getProfit();
                    }

                    _this.is_booted = true;
                })
                .catch(e => {
                    _this.loading = false;

                    console.log(e);
                })
        },

        getProfit(){ 
            let refnumQString = this.fetchParams.columnFilters.shifl_ref ? this.fetchParams.columnFilters.shifl_ref : '';
            let customerQString = this.fetchParams.columnFilters.customer ? this.fetchParams.columnFilters.customer : '';
            let sortBy = this.fetchParams.sort[0].field;
            let sortType = this.fetchParams.sort[0].type;
            let sale_persons = this.selectedSalesRepresentative ? this.selectedSalesRepresentative : '';       
            
            axios.get('/nova-vendor/profit-monitor/get-profit-by-request?realmId=' + this.tabRealmId + '&page=' + this.fetchParams.currentPage + '&per-page=' + this.fetchParams.perPage + '&filterbyrefnum=' + refnumQString + '&filterbycustomer=' +
                    customerQString + '&sortby=' + sortBy + '&sorttype=' + sortType + '&sale_persons=' + sale_persons + '&month=' + (_.isEmpty(this.selectedMonth) ? '' : this.selectedMonth.format("YYYY/MM/DD").toString()))
            .then(response => {
                let data = response.data.results;
                let bills = data.bills ? data.bills : 0;
                let invoices = data.invoices ? data.invoices : 0;
                this.totalProfit = parseFloat(invoices) - parseFloat(bills);
                console.log('bills', bills);
                console.log('invoice', invoices);
                console.log('totalProfit', invoices - bills);
                console.log('Shipment Count', data.count);
            })
            .catch(e => {
                console.log(e)
            });
        },

        updateParams(newProps) {
            this.fetchParams = Object.assign({}, this.fetchParams, newProps);
        },

        onRowClick(row) {
            //if click redirect page to shipment charges tab
        },

        onPageChange(params) {
            this.updateParams({
                currentPage: params.currentPage
            });

            this.fetchShipment();
        },

        onPerPageChange(params) {
            this.updateParams({
                perPage: params.currentPerPage
            });

            this.fetchShipment();
        },

        onSortChange(params) {
            this.updateParams({
                sort: [{
                    type: params[0].type,
                    field: params[0].field
                }],
            });

            if( this.is_booted ){
                this.fetchShipment();
            }
        },

        onColumnFilter(params) {
            this.updateParams({
                columnFilters: {
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
        filterfn: function(data, filterString) {
            cosole.log(data);
        },
        capitalizeTxt(txt) {
          return txt.charAt(0).toUpperCase() + txt.slice(1);
        },
        importXls(){
            let _this = this;

            if( this.rows.length == 0 ){
                this.$toasted.show('No data to import!', { type: 'error' })
                return;
            }

            let filename =(new Date).getTime()+'-import.xlsx';
            let headers = Object.keys(this.rows[0]);            

            let data = this.rows.map( i =>{
                let obj = {};

                headers.forEach( (item,index) =>{
                    let exs = ['id','profitt'];

                    if( !exs.includes(item.toLowerCase() ) ){
                        let header_real = item;

                        if( item.toLowerCase() == 'id'){
                            header_real = "ID";
                        }else{
                            header_real = _this.capitalizeTxt(item).replaceAll('_',' ').replaceAll('-',' ');
                        }
                    
                        obj[header_real] = i[item];
                    }
                });

                return obj;
            });

            var ws = XLSX.utils.json_to_sheet(data);
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Data");
            XLSX.writeFile(wb,filename);
        }
    }
}
</script>

<style scoped>

</style>
