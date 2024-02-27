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
            compactMode>
            <template slot="table-row" slot-scope="props">
                <div v-if="props.column.field == 'action'" style="width:70px">
                    <button class="float-left p-1 mr-2 btn btn-xs btn-primary items-center" @click="updateData(props.row)" v-if="props.row.is_custom == 1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="15" style="width:14px;line-height:initial">
                            <path fill="#FFF" d="M490.3 40.4C512.2 62.27 512.2 97.73 490.3 119.6L460.3 149.7L362.3 51.72L392.4 21.66C414.3-.2135 449.7-.2135 471.6 21.66L490.3 40.4zM172.4 241.7L339.7 74.34L437.7 172.3L270.3 339.6C264.2 345.8 256.7 350.4 248.4 353.2L159.6 382.8C150.1 385.6 141.5 383.4 135 376.1C128.6 370.5 126.4 361 129.2 352.4L158.8 263.6C161.6 255.3 166.2 247.8 172.4 241.7V241.7zM192 63.1C209.7 63.1 224 78.33 224 95.1C224 113.7 209.7 127.1 192 127.1H96C78.33 127.1 64 142.3 64 159.1V416C64 433.7 78.33 448 96 448H352C369.7 448 384 433.7 384 416V319.1C384 302.3 398.3 287.1 416 287.1C433.7 287.1 448 302.3 448 319.1V416C448 469 405 512 352 512H96C42.98 512 0 469 0 416V159.1C0 106.1 42.98 63.1 96 63.1H192z"/>
                        </svg>
                    </button>
                    <button class="p-1 float-left btn btn-xs btn-danger items-center" @click="deleteData(props.row)" v-if="props.row.is_custom == 1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="12" style="width:12px;line-height:initial">
                            <path fill="#FFF" d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM111.1 208V432C111.1 440.8 119.2 448 127.1 448C136.8 448 143.1 440.8 143.1 432V208C143.1 199.2 136.8 192 127.1 192C119.2 192 111.1 199.2 111.1 208zM207.1 208V432C207.1 440.8 215.2 448 223.1 448C232.8 448 240 440.8 240 432V208C240 199.2 232.8 192 223.1 192C215.2 192 207.1 199.2 207.1 208zM304 208V432C304 440.8 311.2 448 320 448C328.8 448 336 440.8 336 432V208C336 199.2 328.8 192 320 192C311.2 192 304 199.2 304 208z"/>
                        </svg>
                    </button>
                </div>
                <span v-else>
                    {{props.formattedRow[props.column.field]}}
                </span>
            </template>
            </vue-good-table>
        </div>
        <ManualImportModal ref="ManualImportModal" @manual_import_update="manualImportUpdate"/>
    </div>
</template>

<script>
import axios from 'axios';
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
import ManualImportModal from './ManualImportModal.vue';

export default {
    props : ['terminal'],
    data(){
        return{
            columns: [
                { 
                    label: 'Date',
                    field: 'data_date',
                    sortable: true,
                },
                { 
                    label: 'Location From',
                    field: 'location_from',
                    sortable: true
                },
                { 
                    label: 'Location To',
                    field: 'location_to',
                    sortable: true
                },
                { 
                    label: 'Terminal',
                    field: 'terminal',
                    sortable: true
                },
                { 
                    label: 'Amount',
                    field: 'amount',
                    sortable: true
                },
                { 
                    label: 'Container',
                    field: 'container',
                    sortable: true
                },
                { 
                    label: 'Creation Date',
                    field: 'created_at',
                    sortable: true
                },
                { 
                    label: '',
                    field: 'action'
                },
            ],
            rows: [],
            fetchParams: {
                columnFilters: {},
                sort: [{ field: 'date', type: 'desc',}],
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
        VueGoodTable,
        ManualImportModal
    },
    watch: {
        'fetchParams.columnFilters.carrier'(){
            this.filterTrigger = 'date';
        },
        'fetchParams.columnFilters.service'(){
            this.filterTrigger = 'date';
        }
    },
    methods:{
        manualImportUpdate(v){
            let q = this.rows.findIndex( i => i.id == v.id );

            if( q ){
                this.rows[q].data_date = v.data_date;
                this.rows[q].container = v.container;
                this.rows[q].location_from = v.location_from;
                this.rows[q].location_to = v.location_to;
                this.rows[q].terminal = v.terminal;
                this.rows[q].amount = v.amount;
            }
        },
        deleteData(t){
            let _this = this;

            let q = confirm('Are you sure you want to delete?');

            if( q ){
                Nova.request().post('/nova-vendor/container-buy-rates/index-rates/delete',{
                    id: t.id,
                }).then(res => {
                    if( res.data.success ){
                        _this.fetchData();
                    }

                    _this.$toasted.show(res.data.message, { type: ( res.data.success ? 'success' : 'error' ) });

                }).catch(err=>{
                    _this.$toasted.show('Unable to process request!', { type: 'error' });
                });
            }
        },
        updateData(t){
            this.$refs['ManualImportModal'].$data.id = t.id;
            this.$refs['ManualImportModal'].$data.data_date = t.data_date;
            this.$refs['ManualImportModal'].$data.location_from = t.location_from;
            this.$refs['ManualImportModal'].$data.location_to = t.location_to;
            this.$refs['ManualImportModal'].$data.terminal = t.terminal;
            this.$refs['ManualImportModal'].$data.amount = t.amount;
            this.$refs['ManualImportModal'].$data.container = t.container;
            this.$refs['ManualImportModal'].$data.open = true;
        },
        fetchData(){
            this.isLoading = true;
        	
            let _this = this;

            let sortBy = this.fetchParams.sort[0].field;
            let sortType = this.fetchParams.sort[0].type;

            axios.get('/nova-vendor/container-buy-rates/index-rates/all?page=' + this.fetchParams.currentPage + '&per-page='+ this.fetchParams.perPage + '&sortby='+sortBy+'&sorttype='+sortType)
            .then(res => {
            	if( res.data.success ){
            		_this.totalRecords = res.data.message.total;                
                	_this.rows = res.data.message.data;

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
                    field: this.filterTrigger !== '' ? this.filterTrigger : 'date'
                }],
            });
            this.fetchData();
        }
    },
    mounted() {
        let _this = this;

        this.fetchData();

        Nova.$on('index_rates_reload',(v)=>{
            _this.fetchData();          
        });

    },
    beforeDestroy(){
        Nova.$off('country');
    }
}
</script>