<template>
    <div>
        <vue-good-table
         :columns="columns"
         :rows="rows"
         :sort-options="{
            enabled: false,
         }"
         :row-style-class="rowStyleClassFn"
         styleClass="vgt-table striped row-hover invoice-details-table"
        >
            <template slot="table-row" slot-scope="props">
                <span v-tooltip="`Invoice #${props.row.invoice_num}`" class="qbo-ellipse-group" v-if="props.column.field == 'invoice_num'">
                    {{
                        props.row.invoice_num
                    }}
                </span>
                <span :class="`${(props.row.status=='Fully Paid') ? 'text-green' : (props.row.status=='Partially Paid') ? 'text-orange' : 'text-red'}`" v-else-if="props.column.field == 'status'">
                    {{props.row.status}}
                </span>
                <span class="text-orange" v-else-if="props.column.field == 'email_status'">
                    {{props.row.email_status}}
                </span>
            </template>
            <div slot="emptystate">
                <div class="text-center"> {{tableEmptyStateText}}</div>
            </div>
        </vue-good-table>
    </div>
</template>

<script>
import { VueGoodTable } from 'vue-good-table';
import moment from 'moment';
import 'vue-good-table/dist/vue-good-table.css'
export default {
    name:"DetailInvoicesTable",
    components:{
        VueGoodTable,
    },
    props: ["tableData"],

    data(){
        return{
            columns:[
                { label: 'Date', field: 'invoice_date', tooltip: 'A simple tooltip', thClass: 'text-center',},
                { label: 'Invoice #', field: 'invoice_num', thClass: 'text-center',},
                { label: 'Due Date', field: 'due_date', thClass: 'text-center',},
                { label: 'Total', field: 'total', thClass: 'text-center',},
                { label: 'Status', field: 'status', thClass: 'detail-status-text',},
                { label: 'Sent', field: 'email_status', thClass: 'detail-is-sent',},
            ],
            rows:[],
            totalInvoicesAmount:0,
            tableEmptyStateText:'No invoice created.'
        }
    },

    beforeCreate: function(){
        //
    },

    created: function(){
        //
    },

    mounted: function(){
        this.setTableData(this.tableData)
    },

    methods: {
       setTableData: function(payload){
            let rowData = [];
            let calculatedTotalInvoices = 0;
            if(payload.length > 0){
                payload.map(function(o){
                    let invoiceTotal = 0;
                    let paymentTotal = 0;
                    o.invoice.invoice_services.forEach((service) => {
                        invoiceTotal = (invoiceTotal + (service.quantity * parseFloat(service.rate)))
                    });
                    if(o.payment.length > 0){
                        o.payment.forEach((payment)=>{
                            paymentTotal = paymentTotal + parseFloat(payment.TotalAmt);
                        })
                    }
                    let balance = parseInt(o.invoice.balance)
                    let total_amount = parseInt(o.invoice.total_amount)
                    let payment_status = 'Not Paid'
                    if ( balance == 0) {
                        payment_status = 'Fully Paid'
                    } else if ( balance < total_amount && total_amount > 0) {
                        payment_status = 'Partially Paid'
                    }

                    //let paymentStatus = paymentTotal >= invoiceTotal ? "PAID" : "Partially paid " + parseFloat(paymentTotal).toFixed(2);
                    let emailStatus = o.invoice.is_email_sent === 1 || o.invoice.is_email_sent === 'Yes' || o.invoice.email_sent_count > 0 ? "Email Sent" : "Not Sent";
                    let row = {
                        invoice_date: o.invoice.invoice_date ? moment.utc(String(o.invoice.invoice_date)).format('MMM DD, YYYY') : '',
                        invoice_num: o.invoice.invoice_num,
                        due_date: o.invoice.due_date ? moment.utc(String(o.invoice.due_date)).format('MMM DD, YYYY') : '',
                        total: parseFloat(invoiceTotal).toFixed(2),
                        //status: o.payment.length > 0 ? paymentStatus : emailStatus,
                        status: payment_status,
                        email_status: emailStatus,
                        id: o.invoice.id,
                        qbo_id: o.invoice.qbo_id,
                        qbo_customer_name: o.invoice.qbo_customer_name,
                        realm_id: this.realmId
                    }
                    rowData.push(row);
                    calculatedTotalInvoices += invoiceTotal;
                }, this);
            }
            this.totalInvoicesAmount = parseFloat(calculatedTotalInvoices).toFixed(2);
            this.$emit('getCompanyTotalInvoices',calculatedTotalInvoices);
            this.rows = rowData;
            this.tableEmptyStateText = "No invoice created."
        },
        rowStyleClassFn:function(){
            return 'vgt-row';
        }
    },
    watch:{
        tableData(nVal,oVal){
            this.setTableData(nVal)
        }
    }
}
</script>
<style>
    .qbo-invoice-num {

    }
    .invoice-details-table .text-red {
        color: #ef4444;
    }

    .invoice-details-table .text-green {
        color: #22c55e;
    }

    .invoice-details-table .text-orange {
        color: #f97316;
    }

    .text-center{
        text-align: center !important;
    }
    .detail-status-text span{
        /*color: #16A34A !important;*/
        font-style: italic !important;
    }
</style>