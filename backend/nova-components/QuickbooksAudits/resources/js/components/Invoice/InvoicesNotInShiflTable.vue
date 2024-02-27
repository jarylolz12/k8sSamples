<template>
    <div>
        <div class="flex w-full">
            <vue-good-table
             :columns="columns"
             :rows="rows"
             styleClass="vgt-table striped row-hover"
            >
                <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field == 'action_btn'">
                        <button @click.prevent="onOpenVerificationModal(props.row)" class="btn-default btn-primary btn-sm">Verify</button>
                    </span>
                    <span v-else>
                        {{props.formattedRow[props.column.field]}}
                    </span>
                </template>
            </vue-good-table>
        </div>
    </div>
</template>
<script>

import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
export default {
    name: "InvoicesNotInShiflTable",
    components: {
        VueGoodTable,
    },
    props: ["tableData"],

    data(){
        return{
            columns: [
                {
                    label: 'QBO ID',
                    field: 'qbo_id',
                },
                // {
                //     label: 'Customer',
                //     field: 'qbo_customer_name',
                // },
                {
                    label: 'Invoice Number',
                    field: 'invoice_num',
                },
                {
                    label: 'Amount',
                    field: 'total_amount',
                },
                {
                    label: 'Created At',
                    field: 'created_at',
                },
                {
                    label: '',
                    field: 'action_btn',
                    sortable: false,
                },
            ],
            rows: [],
            }
    },
    computed:{
        //
    },

    mounted() {
        //console.log(this.tableData);
    },
  
    methods:{
         onOpenVerificationModal: function(payload){
            this.$emit('onOpenVerificationModal', payload)
        }
    },

    watch: {
        tableData:{
            immediate: true,
            handler(newVal, oldVal){
                let rowData = [];
                if(Object.keys(newVal).length > 0){
                    _.forIn(newVal, function(val,key){
                        let row = {
                            qbo_id: val.Id,
                            qbo_customer_name : val.CustomerRef,
                            invoice_num: val.DocNumber,
                            total_amount: val.TotalAmt,
                            created_at: moment(val.MetaData.CreateTime).format('MMM DD, YYYY hh:mm'),
                            location:'QBO_DB',
                            qbo_country: '',
                            qbo_company: '',
                            invoice_date: moment(val.TxnDate).format('MMM DD, YYYY'),
                            invoice_created_at: moment(val.MetaData.CreateTime).format('MMM DD, YYYY hh:mm'),
                            payload:'',
                        };
                        rowData.push(row);
                    })
                    this.rows = rowData;
                }
            }
        }
    }
}

</script>

<style scoped>
</style>
