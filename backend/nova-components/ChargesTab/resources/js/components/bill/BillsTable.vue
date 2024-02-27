<template>
    <div>
        <vue-good-table
         :columns="columns"
         :rows="rows"
         :sort-options="{
            enabled: false,
         }"
         styleClass="vgt-table striped"
         @on-cell-click="onCellClick"
        >
            <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'delete_btn'">
                    <a class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3 has-tooltip" @click.prevent="onDeleteClick(props.row)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                            <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                        </svg>
                    </a>
                </span>
                <span v-else>
                {{props.formattedRow[props.column.field]}}
                </span>
            </template>
            <div slot="emptystate">
                <div class="text-center">{{tableEmptyStateText}}</div>
            </div>
        </vue-good-table>
    </div>
</template>

<script>
import { VueGoodTable } from 'vue-good-table';
import moment from 'moment';
import 'vue-good-table/dist/vue-good-table.css'
export default {
    name:"BillsTable",
    components:{
        VueGoodTable,
    },
    props: ['resourceName', 'resourceId'],

    data(){
        return{
            columns:[
                { label: 'Date', field: 'qbo_bill_date'},
                { label: 'Bill #', field: 'qbo_bill_num'},
                { label: 'Due Date', field: 'qbo_due_date'},
                { label: 'Total', field: 'total_amount'},
                { label: '', field: 'delete_btn'},
            ],
            rows:[],
            invoices:[],
            totalBillsAmount:0,
            tableEmptyStateText:'No bills created.'
        }
    },

    beforeCreate: function(){

    },

    created: function(){
        this.fetchBills();
    },

    mounted: function(){

    },

    methods: {
        fetchBills: function(){
            this.tableEmptyStateText = "Fetching bills...";
            axios.get("/custom/bill/by-shipment/" + this.resourceId)
            .then(response => {
                let rowData = [];
                if(response.data.success){
                    const responseData = response.data.results;
                    if(responseData.length > 0){
                        let calculatedTotalBills = 0;
                        responseData.map(function(o){
                            let billSubTotal = 0;
                            o.bill_items.forEach((item) => {
                                billSubTotal = billSubTotal + parseFloat(item.qbo_amount);
                            });
                            let row = {
                                qbo_bill_date: o.qbo_bill_date ? moment.utc(String(o.qbo_bill_date)).format('MMM DD, YYYY') : '',
                                qbo_bill_num: o.qbo_bill_num,
                                qbo_due_date: o.qbo_due_date ? moment.utc(String(o.qbo_due_date)).format('MMM DD, YYYY') : '',
                                total_amount: parseFloat(o.total_amount).toFixed(2),
                                id: o.id,
                                qbo_bill_id: o.qbo_bill_id,
                                qbo_vendor_name: o.qbo_vendor_name,
                            }
                            calculatedTotalBills += billSubTotal;
                            rowData.push(row);
                        });
                        this.totalBillsAmount = parseFloat(calculatedTotalBills).toFixed(2);
                        this.$emit('getTotalBillsAmount',calculatedTotalBills);
                    }
                }
                this.rows = rowData;
                this.tableEmptyStateText = "No bills created.";
            })
            .catch(e => {
                console.log(e);
            });
        },

        onCellClick: function(params){
            const field = params.column.field;
            const rowData = params.row;
            if(field !== 'delete_btn'){
                this.$emit('onRowClick',rowData)
            }else{
                
            }
        },

        onDeleteClick: function(data){
            this.$emit('onDeleteClick',data);
        },
    }
}
</script>
<style scoped>

</style>