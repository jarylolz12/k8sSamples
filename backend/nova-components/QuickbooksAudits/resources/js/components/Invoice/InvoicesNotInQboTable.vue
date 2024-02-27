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
import InvoiceVerificationModal from './InvoiceVerificationModal.vue';
import Modal from '../Modal.vue';
export default {
    name: "InvoicesNotInQboTable",
    components: {
        VueGoodTable,
        Modal,
        InvoiceVerificationModal,
    },
    props: ["tableData"],

    data(){
        return{
            columns: [
                {
                    label: 'QBO ID',
                    field: 'qbo_id',
                },
                {
                    label: 'Customer',
                    field: 'qbo_customer_name',
                },
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
                            shifl_id: val.id,
                            qbo_id: val.qbo_id,
                            qbo_customer_name : val.qbo_customer_name,
                            invoice_num: val.invoice_num,
                            total_amount: val.total_amount,
                            created_at: moment(String(val.created_at)).format('MMM DD, YYYY hh:mm'),
                            location:'SHIFL_DB',
                            qbo_country: val.qbo_country,
                            qbo_company: val.qbo_company,
                            invoice_date: val.invoice_date,
                            invoice_created_at: val.created_at,
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
