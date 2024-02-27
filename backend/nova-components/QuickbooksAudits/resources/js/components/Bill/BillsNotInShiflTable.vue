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
                        <button @click.prevent="onOpenBillVerificationModal(props.row)" class="btn-default btn-primary btn-sm">Verify</button>
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
import Modal from '../Modal.vue';
export default {
    name: "BillsNotInQboTable",
    components: {
        VueGoodTable,
        Modal,
    },
    props: ["tableData"],

    data(){
        return{
            columns: [
                {
                    label: 'QBO ID',
                    field: 'qbo_bill_id',
                },
                {
                    label: 'Bill Number',
                    field: 'qbo_bill_num',
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
                    sortable:false
                },
            ],
            rows: [],
            }
    },
    computed:{
        //
    },

    mounted() {
        //
    },
  
    methods:{
        onOpenBillVerificationModal: function(payload){
            this.$emit('onOpenBillVerificationModal', payload)
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
                            shifl_id: '',
                            qbo_bill_id: val.Id,
                            qbo_vendor_name : val.VendorRef,
                            qbo_bill_num: val.DocNumber,
                            total_amount: val.TotalAmt,
                            created_at: moment(String(val.MetaData.CreateTime)).format('MMM DD, YYYY hh:mm'),
                            location:'QBO_DB',
                            qbo_country: '',
                            qbo_company: '',
                            qbo_bill_date: val.TxnDate,
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
