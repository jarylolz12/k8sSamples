<template>
    <div class="flex w-full">
        <div class="w-full">
            <Loader type="3" color="#297ec0" v-if="isBootLoading"/>
            <div v-else>
                <div v-if="!isLoading && isLoadingCompany && !company">
                    <div class="flex justify-center">
                        <div style="width:80px;height:80px;" class="mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle" viewBox="0 0 24 24" stroke-width="2" stroke="#ffdb55" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <circle cx="12" cy="12" r="9" />
                              <line x1="12" y1="8" x2="12" y2="12" />
                              <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                         </div>
                    </div>
                    <h3 class="text-center">No company found. <a href="#" @click.prevent="fetchCompany()" style="font-weight: normal;color: #4da4e8;">Retry</a></h3>
                </div>
                <vue-good-table 
                v-else 
                mode="remote"
                :columns="columns"
                :rows="rows"
                :line-numbers="true"
                :sort-options="{
                    enabled: true,
                    initialSortBy: {field: 'created_at', type: 'asc'}
                }"
                :search-options="{ enabled: false }"
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
                    <div slot="table-actions" class="flex items-center justify-start">
                        <div style="width:250px;">
                            <div class="p-2">
                                <div class="loading-wrapper">
                                    <v-select :options="getCustomers" @search="searchCustomer" :value="selected_customer" placeholder="Select customer" v-model="selected_customer">
                                        <template #search="{attributes, events}">
                                            <input
                                              class="vs__search"
                                              v-bind="attributes"
                                              v-on="events"
                                            />
                                        </template>
                                        <li slot="list-footer" class="pagination" style="padding: 8px 12px">
                                          <button @click="fetchCustomers(customers.prev_page_url)" v-if="!isEmpty(customers.prev_page_url)" style="float:left;padding:5px;border:1px solid #ccc;border-radius:3px;">Prev</button>
                                          <button @click="fetchCustomers(customers.next_page_url)" v-if="!isEmpty(customers.next_page_url)" style="float:right;padding:5px;border:1px solid #ccc;border-radius:3px;">Next</button>
                                        </li>
                                    </v-select>
                                </div>
                            </div>
                        </div>
                        <div style="width:250px;">
                            <div class="p-2">
                                <select class="block w-full form-control-sm form-select" v-model="filter_type" :disabled="isLoadingCompany || isLoadingCustomer">
                                    <option value="" selected>Filter type</option>
                                    <option :value="item" v-for="item in filter_types">{{ item }}</option>
                                </select>
                            </div>
                        </div>
                        <div style="width:250px;">
                            <div class="p-2">
                                <select class="block w-full form-control-sm form-select" v-model="filter_status" :disabled="isLoadingCompany || isLoadingCustomer">
                                    <option value="" selected>Filter status</option>
                                    <option :value="item" v-for="item in filter_statuses">{{ item }}</option>
                                </select>
                            </div>
                        </div>
                        <div style="width:150px;padding-right:8px">
                            <button style="height:2rem;" @click.prevent="createStatement()" class="btn btn-default btn-primary" dusk="create-button" :disabled="isLoadingCompany || isLoadingCustomer || customer == '' || !company">
                                {{ isLoadingCompany || isLoadingCustomer ? 'Loading data...' : 'Statement'}}
                            </button>
                        </div>
                        <div style="width:80px;">
                            <button style="height:2rem;" @click.prevent="fetchData()" class="btn btn-default btn-primary" dusk="create-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-reload" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                  <path d="M19.933 13.041a8 8 0 1 1 -9.925 -8.788c3.899 -1.002 7.935 1.007 9.425 4.747" />
                                  <path d="M20 4v5h-5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <template slot="table-row" slot-scope="props">
                        <div v-if="props.column.field == 'status'">
                            <span :class="['badge',( props.formattedRow[props.column.field] == 'sent' ? 'bg-success' : 'bg-danger' )]">
                                {{props.formattedRow[props.column.field]}}
                            </span>
                        </div>
                        <div v-else-if="props.column.field == 'action'" class="csdropdown-wrapper" style="position:relative;width:40px">
                            <button :id="'dropdownDefaultButton'+props.row.id" :data-dropdown-toggle="'dropdown'+props.row.id" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600" type="button"> 
                              <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                            </button>
                            <div :id="'dropdown'+props.row.id" class="z-10 csdropdown bg-white divide-y divide-gray-100 rounded shadow w-44 dark:bg-gray-700">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" :aria-labelledby="'dropdownDefaultButton'+props.row.id">
                                  <li>
                                    <a href="#" @click.prevent="viewStatement(props.row)" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">View</a>
                                  </li>
                                  <li>
                                    <a href="#" @click.prevent="editStatement(props.row)" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                  </li>
                                  <li>
                                    <a href="#" @click.prevent="deleteStatement(props.row)" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete</a>
                                  </li>
                                </ul>
                            </div>
                        </div>
                        <div v-else>
                            <span>
                                {{props.formattedRow[props.column.field]}}
                            </span>
                        </div>
                    </template>
                </vue-good-table>
            </div>
        </div>
        <StatementModal @close="fetchData" ref="StatementModal"/>
        <StatementViewModal @edit_statement_action="editStatementAction" @delete_statement_action="deleteStatementAction" ref="StatementViewModal"/>
        <StatementDeleteModal @close="fetchData" ref="StatementDeleteModal"/>
        <MessageModal ref="MessageModal"/>
    </div>
</template>

<style lang="scss" scoped>
    .loading-wrapper{
        position:relative;
        &.loading:before{
            position:absolute;
            top:0px;
            left:0px;
            width:100%;
            height:100%;
            display:flex;
            align-items:center;
            content: 'Loading...';
            background-color: #FFF;
            border-width: 1px;
            border-color: #bacad6;
            padding-left: 0.75rem;
            padding-right: 2rem;
            border-radius: 0.5rem;
            color: #7c858e;
        }
    }
    .badge{
        &.bg-success{
            background: #60f3b5;
            padding: 2px 8px;
            border-radius: 20px;
        }

        &.bg-danger{
            background: #ffb9b9;
            padding: 2px 8px;
            border-radius: 20px;
        }
    }
    .csdropdown-wrapper{
        &:hover .csdropdown{
            display:block;
        }
        .csdropdown {
            position:absolute;
            right:0px;
            display: none;
            ul{
                list-style:none;
                padding: 0px;
                li{
                    list-style: none;
                    a{
                        display:block;
                        color:#242424;
                        opacity: .7;
                        text-decoration:none!important;
                        &:hover{
                            opacity: 1!important;
                        }
                    }
                }
            }
        }
    }
</style>

<script>
    import 'vue-good-table/dist/vue-good-table.css'
    import { VueGoodTable } from 'vue-good-table';
    import StatementModal from './StatementModal.vue';
    import StatementViewModal from './StatementViewModal.vue';
    import StatementDeleteModal from './StatementDeleteModal.vue';
    import MessageModal from './MessageModal.vue';
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';
    import Loader from './Loader.vue';

    export default {
        props : ['type'],
        components: {
            VueGoodTable,
            StatementModal,
            StatementViewModal,
            StatementDeleteModal,
            MessageModal,
            vSelect,
            Loader
        },
        data(){
            return{
                currency_code: 'USD',
                columns: [
                    { label: 'DATE',field: 'created_at', sortable: true },
                    { label: 'NUMBER',field: 'statement_id', sortable: true },
                    { label: 'START DATE', field: 'start_date', sortable: true },
                    { label: 'END DATE', field: 'end_date', sortable: true },
                    { label: 'AMOUNT DUE', field: 'amount_due', sortable: true },
                    { label: 'STATUS', field: 'status', sortable: true },
                    { label: 'SENT COUNT', field: 'sent_count', sortable: true },
                    { label: '', field: 'action', sortable: false }
                ],
                rows: [],
                fetchParams: {
                    columnFilters: {},
                    sort: [
                        { field: 'created_at', type: 'desc' },
                        { field: 'statement_id', type: 'desc' },
                        { field: 'start_date', type: 'asc' },
                        { field: 'end_date', type: 'asc' },
                        { field: 'amount_due', type: 'desc' },
                        { field: 'status', type: 'asc' },
                        { field: 'sent_count', type: 'desc' },
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
                company: false,
                customer: '',
                customers: {
                    data: [],
                    keyword: '',
                    prev_page_url: false,
                    next_page_url: false,
                    order_type: 'asc',
                    order_field: 'company_name',
                    per_page: 25,
                    current_page: 1
                },
                sent_status: '',
                filter_types: ['all','balance forward','open item','Transaction statement'],
                filter_type: 'open item',
                filter_statuses: ['all','unsent', 'sent'],
                filter_status: 'all',
                search: '',
                isLoading : true,
                isLoadingCompany: true,
                isLoadingCustomer: true,
                isBootLoading: true,
                selected_customer: '',
                isLoadingAutoSend: true,
                is_auto_send: false,
                offset: 0,
                limit: 10,
            }
        },
        watch: {
            customer(v){
                if( !v || v === '' ){
                    this.rows = [];
                    this.totalRecords = 0;

                    return;
                }

                this.fetchData();
            },
            filter_status(){
                this.fetchData();
            },
            filter_type(){
                this.fetchData();
            },
            selected_customer(v){
                let _this = this;

                if( this.isEmpty(v) ){
                    this.customer = '';
                    return;
                }

                let customer_index = this.customers.data.findIndex( i => i.id == v.value );

                if( customer_index == -1 ){
                    this.$refs.MessageModal.$data.type = 'error';
                    this.$refs.MessageModal.$data.text = 'Customer not found!';
                    this.$refs.MessageModal.$data.open = true;
                    this.$refs.MessageModal.$data.disable_confirm_button = true;
                    this.$refs.MessageModal.$data.disable_cancel_button = true;

                    return;
                }

                let customer = this.customers.data[customer_index];

                if( this.isEmpty(customer.qbo_id) || this.isEmpty(customer.qbo_customer) ){
                    this.$refs.MessageModal.$data.type = 'error';
                    this.$refs.MessageModal.$data.text = 'No email available!';
                    this.$refs.MessageModal.$data.open = true;
                    this.$refs.MessageModal.$data.disable_confirm_button = true;
                    this.$refs.MessageModal.$data.disable_cancel_button = true;

                    return;
                }

                if( this.isEmpty(customer.primary_email) ){
                   // try to retrieve from quickbooks
                    (async()=>{
                        let { data } = await axios('/nova-vendor/customer-statement/customer/'+customer.id);
                        
                        if( !data.message || !data.message.primary_email ){
                            _this.$refs.MessageModal.$data.type = 'error';
                            _this.$refs.MessageModal.$data.text = 'No email available!';
                            _this.$refs.MessageModal.$data.open = true;
                            _this.$refs.MessageModal.$data.disable_confirm_button = true;
                            _this.$refs.MessageModal.$data.disable_cancel_button = true;
                        }else{
                            this.customers.data[customer_index].primary_email = data.message.primary_email;
                            this.customer = this.customers.data[customer_index];
                        }

                    })();
                }else{
                    this.customer = customer;
                }
            },
        },
        computed: {
            getCustomers(){
                return this.customers.data.map( i => ({ label: i.company_name, value: i.id }) );
            }
        },
        methods:{
            searchCustomer(search,loading){
                let _this = this;

                loading(true);

                (async()=>{
                    _this.customers.keyword = search;
                    await _this.fetchCustomers()

                    loading(false);
                })();
            },
            viewStatement(data){
                this.$refs.StatementViewModal.$data.title = data.statement_id;
                this.$refs.StatementViewModal.$data.type = data.type;
                this.$refs.StatementViewModal.$data.status = data.status;
                this.$refs.StatementViewModal.$data.formData.statement_real_id = data.id;
                this.$refs.StatementViewModal.$data.formData.statement_id = data.statement_id;
                this.$refs.StatementViewModal.$data.formData.type = data.type;
                this.$refs.StatementViewModal.$data.formData.contact_id = data.contact_id;
                this.$refs.StatementViewModal.$data.formData.company = this.company;
                this.$refs.StatementViewModal.$data.formData.customer_data = this.customer;
                this.$refs.StatementViewModal.$data.formData.customer = this.customer;
                this.$refs.StatementViewModal.$data.formData.currency_code = this.currency_code;
                this.$refs.StatementViewModal.$data.formData.start_date = data.start_date;
                this.$refs.StatementViewModal.$data.formData.end_date = data.end_date;
                this.$refs.StatementViewModal.$data.formData.invoices = data.invoices.map( i => { 
                    i.selected = true;
                    return i;
                });
                this.$refs.StatementViewModal.$data.formData.amount = data.amount;
                this.$refs.StatementViewModal.$data.formData.amount_due = data.amount_due;
                this.$refs.StatementViewModal.$data.formData.total_paid_amount = data.total_paid_amount;
                this.$refs.StatementViewModal.$data.formData.opening_balance = data.opening_balance;
                this.$refs.StatementViewModal.$data.formData.closing_balance = data.closing_balance;
                this.$refs.StatementViewModal.$data.open = true;
            },
            editStatement(data){
                if( !this.company ){
                    this.$toasted.show( 'Company not found.', { type: 'error' });
                    return;
                }

                this.$refs.StatementModal.$data.isEditMode = true;
                this.$refs.StatementModal.$data.title = data.statement_id;
                this.$refs.StatementModal.$data.type = data.type;
                this.$refs.StatementModal.$data.status = data.status;
                this.$refs.StatementModal.$data.formData.company = this.company;
                this.$refs.StatementModal.$data.formData.statement_real_id = data.id;
                this.$refs.StatementModal.$data.formData.statement_id = data.statement_id;
                this.$refs.StatementModal.$data.formData.type = data.type;
                this.$refs.StatementModal.$data.formData.contact_id = data.contact_id;
                this.$refs.StatementModal.$data.formData.customer_data = this.customer;
                this.$refs.StatementModal.$data.formData.customer = this.customer;
                this.$refs.StatementModal.$data.formData.currency_code = data.currency_code;
                this.$refs.StatementModal.$data.formData.start_date = data.start_date;
                this.$refs.StatementModal.$data.formData.end_date = data.end_date;
                this.$refs.StatementModal.$data.formData.invoices = data.invoices.map( i => { 
                    i.selected = true;
                    return i;
                });
                this.$refs.StatementModal.$data.formData.amount = data.amount;
                this.$refs.StatementModal.$data.formData.amount_due = data.amount_due;
                this.$refs.StatementModal.$data.formData.total_paid_amount = data.total_paid_amount;
                this.$refs.StatementModal.$data.formData.opening_balance = data.opening_balance;
                this.$refs.StatementModal.$data.formData.closing_balance = data.closing_balance;
                this.$refs.StatementModal.$data.open = true;
            },
            deleteStatement(data){
                this.$refs.StatementDeleteModal.$data.title = data.statement_id;
                this.$refs.StatementDeleteModal.$data.open = true;
                this.$refs.StatementDeleteModal.$data.formData.statement_real_id = data.id;
            },
            createStatement(){
                if( !this.company ){
                    this.$toasted.show( 'Company not found.', { type: 'error' });
                    return;
                }else if( this.customer == '' ){
                    this.$toasted.show( 'Select customer first.', { type: 'error' });
                    return;
                }

                this.$refs.StatementModal.$data.isEditMode = false;
                this.$refs.StatementModal.$data.title = 'Create Statement';
                this.$refs.StatementModal.$data.open = true;
                this.$refs.StatementModal.$data.formData.company = this.company;
                this.$refs.StatementModal.$data.formData.customer_data = this.customer;
                this.$refs.StatementModal.$data.formData.contact_id = this.customer.qbo_id;
                this.$refs.StatementModal.$data.formData.customer = this.customer;
                this.$refs.StatementModal.$data.formData.currency_code = this.currency_code;
            },
            editStatementAction(data){
                this.editStatement(this.rows.find( i => i.id == data.statement_real_id));
            },
            deleteStatementAction(data){
                this.deleteStatement(this.rows.find( i => i.id == data.statement_real_id));
            },
            fetchData(){
                if( !this.isLoadingCompany && !this.company ){
                    this.$toasted.show( 'Company not found.', { type: 'error' });
                    return;
                }

                this.isLoading = true;
                
                let _this = this;

                let sortBy = this.fetchParams.sort[0].field;
                let sortType = this.fetchParams.sort[0].type;

                axios.get('/nova-vendor/customer-statement/statement/'+this.customer.qbo_id+'?search='+this.search+'&filter_status='+this.filter_status+'&filter_type='+this.filter_type+'&page=' + this.fetchParams.currentPage + '&per_page='+ this.fetchParams.perPage + '&sortby='+sortBy+'&sorttype='+sortType+'&customer='+( this.customer ? this.customer.id : '' ))
                .then(res => {
                    if( res.data ){
                        _this.totalRecords = res.data.total;
                        _this.rows = res.data.data.map( i =>{
                            i.amount_due_real = i.amount_due;
                            i.amount_due = _this.moneyFormat(i.amount_due,_this.currency_code);

                            return i;
                        });
                    }

                    _this.isLoading = false;
                })
                .catch(error => {
                    console.log(error);
                    _this.$toasted.show( 'Failed to get opening balance, please try again.', { type: 'error' });
                    _this.isLoading = false;
                });
            },
            formatDateTime(d,f){
                return moment(d).format(f);
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
            },
            fetchCompany(){
                let _this = this;

                axios.get('/nova-vendor/customer-statement/company/active')
                .then(res => {
                    if( !res.data.success ){
                        _this.$toasted.show('No company found!', { type: 'error' });
                    }else{
                        _this.company = res.data.message;
                        _this.currency_code = res.data.customer.currency_code;
                        _this.isLoadingCompany = false;
                    }
                })
                .catch(error => {
                    console.log(error);
                    _this.$toasted.show( 'Failed to get company, please try again.', { type: 'error' });
                });
            },
            fetchCustomers(url){
                this.isLoadingAutoSend = true;
                
                let _this = this;
                url = url ? url : '/nova-vendor/customer-statement/customer/all?per_page='+this.customers.per_page;
                url+='&order_field='+this.customers.order_field+'&order_type='+this.customers.order_type+'&q='+this.customers.keyword;

                axios.get(url)
                .then(res => {
                    if( !res.data.success ){
                        _this.$toasted.show('No customer found!', { type: 'error' });
                    }

                    _this.customers = {
                        data: res.data.message.data,
                        keyword: _this.customers.keyword,
                        prev_page_url: res.data.message.prev_page_url,
                        next_page_url: res.data.message.next_page_url,
                        order_type: _this.customers.order_type,
                        order_field: _this.customers.order_field,
                        per_page: res.data.message.per_page,
                        current_page: res.data.message.current_page
                    }

                    _this.isLoadingCustomer = false;
                })
                .catch(error => {
                    console.log(error);
                    _this.$toasted.show( 'Failed to get customer, please try again.', { type: 'error' });
                    _this.isLoadingCustomer = false;
                });
            }
        },
        mounted() {
            let _this = this;

            (async ()=>{
                await this.fetchCompany();
                await this.fetchCustomers();
                _this.isLoading = false;    

                setTimeout(()=>{
                    _this.isBootLoading = false
                },2000)
            })();
        },
        created(){
            console.log('v1.0.7');
            if( document.querySelector('#custom-style-vs') ) return;

            let style = document.createElement('style');
            style.setAttribute('id','custom-style-vs');
            style.innerHTML = '.vs__dropdown-toggle{ border-color: rgb(186, 202, 214) !important; border-radius: 0.5rem !important; } .vgt-responsive{ padding-bottom:90px!important; }';
            document.querySelector('head').appendChild(style);
        }
    }
</script>