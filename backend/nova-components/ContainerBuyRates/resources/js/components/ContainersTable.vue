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
            />
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
export default {
    props : ['terminal'],
    data(){
        return{
            columns: [
                { 
                    label: 'Service',
                    field: 'service',
                    filterOptions: {
                        styleClass: 'class1',
                        enabled: true,
                        placeholder: 'Filter by name'
                    },
                    sortable: true,
                },
                { 
                    label: 'Carrier',
                    field: 'carrier',
                    filterOptions: {
                        styleClass: 'class1',
                        enabled: true,
                        placeholder: 'Filter by type'
                    },
                    sortable: true
                },
                { 
                    label: 'Vessel',
                    field: 'vessel',
                    filterOptions: {
                        styleClass: 'class1',
                        enabled: true,
                        placeholder: 'Filter by vessel'
                    },
                    sortable: true
                },
                { label: 'Amount', field: 'amount', sortable: true },
                { label: 'Quantity', field: 'qty', sortable: true },
                { label: 'Container Size', field: 'container', sortable: true },
                { label: 'Location From', field: 'location_from', sortable: true },
                { label: 'Location To', field: 'location_to', sortable: true }
            ],
            rows: [],
            fetchParams: {
                columnFilters: {},
                sort: [{ field: 'carrier', type: 'desc',}],
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
            isLoading : true
        }
    },
    components: {
        VueGoodTable
    },
    watch: {
        'fetchParams.columnFilters.carrier'(){
            this.filterTrigger = 'carrier';
        },
        'fetchParams.columnFilters.service'(){
            this.filterTrigger = 'service';
        }
    },
    methods:{
        fetchData(){
            this.isLoading = true;
        	
            let _this = this;

        	let carrier = this.fetchParams.columnFilters.carrier ? this.fetchParams.columnFilters.carrier : '';
            let service = this.fetchParams.columnFilters.service ? this.fetchParams.columnFilters.service : ''
            let sortBy = this.fetchParams.sort[0].field;
            let sortType = this.fetchParams.sort[0].type;

            axios.get('/nova-vendor/container-buy-rates/all?terminal='+this.terminal.name+'&page=' + this.fetchParams.currentPage + '&per-page='+ this.fetchParams.perPage + '&filterbycarrier='+carrier+'&filterbyservice='+service+'&sortby='+sortBy+'&sorttype='+sortType)
            .then(res => {
            	if( res.data.success ){
            		_this.totalRecords = res.data.message.total;                
                	_this.rows = res.data.message.data.map( item=>{
                        return {
                            service: item.service !== null ? item.service: '',
                            carrier: item.carrier !== null ? item.carrier : '',
                            vessel: item.vessel !== null ? item.vessel : '',
                            amount: item.amount,
                            quantity: item.qty,
                            container: item.container !== null ? item.container : '',
                            location_from: item.location_from !== null ? item.location_from : '',
                            location_to: item.location_to !== null ? item.location_to : ''
                        }
                    });

                    _this.isLoading = false;
            	}
            })
            .catch(err => {
                if( err ) console.log(err);
            })
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
                    carrier: params.columnFilters.carrier,
                    service: params.columnFilters.service,
                },
                sort: [{
                    type: 'desc',
                    field: this.filterTrigger !== '' ? this.filterTrigger : 'carrier'
                }],
            });
            this.fetchData();
        }
    },
    mounted() {
        this.fetchData();
    }
}
</script>