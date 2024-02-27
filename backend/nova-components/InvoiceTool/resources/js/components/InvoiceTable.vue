<template>
    <div class="flex w-full">
        <div class="w-full">
            <vue-good-table
            mode="remote"
            :columns="columns"
            :rows="rows"
            :line-numbers="true"
            :sort-options="{
                enabled: true,
                initialSortBy: {field: 'invoice_num', type: 'asc'}
            }"
            :isLoading="isLoading" 
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
            >
                <div slot="table-actions" class="flex">
                    <div style="width:350px;">
                        <h3 class="text-sm uppercase tracking-wide text-80 bg-30 p-3">
                            Paid Status
                        </h3>
                        <div class="p-2">
                            <select class="block w-full form-control-sm form-select" v-model="paid_status">
                                <option value="" selected>—</option>
                                <option value="1">Paid</option>
                                <option value="0">Unpaid</option>
                            </select>
                        </div>
                    </div>
                    <div style="width:350px;">
                        <h3 class="text-sm uppercase tracking-wide text-80 bg-30 p-3">
                            Sent Status
                        </h3>
                        <div class="p-2">
                            <select class="block w-full form-control-sm form-select" v-model="sent_status">
                                <option value="" selected>—</option>
                                <option value="1">Sent</option>
                                <option value="0">Unsent</option>
                            </select>
                        </div>
                    </div>
                    <div style="width:350px;">
                        <h3 class="text-sm uppercase tracking-wide text-80 bg-30 p-3">
                            Filter by Customer
                        </h3>
                        <div class="p-2">
                            <select class="block w-full form-control-sm form-select" v-model="customer">
                                <option value="" selected>—</option>
                                <option :value="item" v-for="item in customers">{{ item }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </vue-good-table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';

export default {
    props : ['type'],
    data(){
        return{
            columns: [
                { label: 'Invoice Number',field: 'invoice_num', sortable: true },
                { label: 'Customer Name',field: 'qbo_customer_name', sortable: true },
                { label: 'Invoice Date', field: 'invoice_date', sortable: true },
                { label: 'Total Amount', field: 'total_amount', sortable: true },
                { label: 'Paid On', field: 'paid_on', sortable: true },
                { label: 'Is sent', field: 'is_email_sent', sortable: true },
                { label: 'Created At', field: 'created_at', sortable: true }
            ],
            rows: [],
            fetchParams: {
                columnFilters: {},
                sort: [
                    { field: 'invoice_num', type: 'desc' },
                    { field: 'qbo_customer_name', type: 'desc' },
                    { field: 'invoice_date', type: 'asc' },
                    { field: 'total_amount', type: 'asc' },
                    { field: 'paid_on', type: 'desc' },
                    { field: 'Is sent', type: 'asc' },
                    { field: 'created_at', type: 'desc' },
                ],
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
            isLoading : true,
            customer: '',
            customers: [],
            sent_status: '',
            paid_status: ''
        }
    },
    components: {
        VueGoodTable
    },
    watch: {
        customer(){
            this.fetchData();
        },
        sent_status(){
            this.fetchData();
        },
        paid_status(){
            this.fetchData();
        },
    },
    methods:{
        fetchCustomers(){
            let _this = this;
            axios.get('/nova-vendor/invoice-tool/customer/all')
            .then( res =>{
                _this.customers = res.data;
            })
            .catch(err => {
                if( err ) console.log(err);
            });
        },
        fetchData(){
            this.isLoading = true;
            
            let _this = this;

            let sortBy = this.fetchParams.sort[0].field;
            let sortType = this.fetchParams.sort[0].type;

            axios.get('/nova-vendor/invoice-tool/all?paid_status='+this.paid_status+'&sent_status='+this.sent_status+'&customer='+this.customer+'&page=' + this.fetchParams.currentPage + '&per-page='+ this.fetchParams.perPage + '&sortby='+sortBy+'&sorttype='+sortType)
            .then(res => {
                if( _this.customer != '' ){

                    document.querySelector('.vgt-wrap__footer').style.display = 'none';

                    _this.columns = [
                        { label: 'Customer Name',field: 'qbo_customer_name', sortable: true },
                        { label: 'Total Amount', field: 'total_amount', sortable: true },
                        { label: 'Total Open', field: 'total_open', sortable: true },
                    ];

                    _this.totalRecords = 1;
                    _this.rows = res.data;
                }else{
                    document.querySelector('.vgt-wrap__footer').style.display = 'block';

                    _this.columns = [
                        { label: 'Invoice Number',field: 'invoice_num', sortable: true },
                        { label: 'Customer Name',field: 'qbo_customer_name', sortable: true },
                        { label: 'Invoice Date', field: 'invoice_date', sortable: true },
                        { label: 'Total Amount', field: 'total_amount', sortable: true },
                        { label: 'Paid On', field: 'paid_on', sortable: true },
                        { label: 'Is sent', field: 'is_email_sent', sortable: true },
                        { label: 'Created At', field: 'created_at', sortable: true },
                    ];

                    _this.totalRecords = res.data.total;
                    _this.rows = res.data.data;
                }

                _this.isLoading = false;
            })
            .catch(err => {
                if( err ) console.log(err);
                _this.isLoading = false;
            })
        },
        fetchCustomer(){

        },
        updateParams(newProps) {
            this.fetchParams = Object.assign({}, this.fetchParams, newProps);
        },
        onRowClick(row){
            
        },
        onPageChange(params){
            this.updateParams({currentPage: params.currentPage});
            this.fetchData();
        },
        onPerPageChange(params) {
            this.updateParams({perPage: params.currentPerPage});
            this.fetchData();
        },
        onSortChange(params){
            this.updateParams({
                sort: [{
                    type: params[0].type,
                    field: params[0].field
                }],
            });
            this.fetchData();
        },
        onColumnFilter(params){
            this.updateParams({
                columnFilters : {
                    invoice_num: params.columnFilters.invoice_num,
                    qbo_customer_name: params.columnFilters.qbo_customer_name,
                },
                sort: [{
                    type: 'desc',
                    field: this.filterTrigger !== '' ? this.filterTrigger : 'invoice_num'
                }],
            });
            this.fetchData();
        }
    },
    mounted() {
        this.fetchData();
        this.fetchCustomers();
    }
}
</script>