<template>
    <div>
        <vue-good-table
         :columns="columns"
         :rows="rows"
         :sort-options="{
            enabled: false,
         }"
         styleClass="vgt-table striped row-hover"
         :row-style-class="rowStyleClassFn"
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
                <span v-if="props.column.field == 'status'">
                    <span class="text-sm">{{props.row.status}}</span> 
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
import 'vue-good-table/dist/vue-good-table.css';
import {mapGetters} from 'vuex';
export default {
    name:"InvoicesTable",
    components:{
        VueGoodTable,
    },
    props: ["realmId"],

    data(){
        return{
            columns:[
                { label: 'Date', field: 'invoice_date', tooltip: 'A simple tooltip', thClass: 'text-center', tdClass:'cursor-pointer',},
                { label: 'Invoice #', field: 'invoice_num', thClass: 'text-center', tdClass:'cursor-pointer',},
                { label: 'Due Date', field: 'due_date', thClass: 'text-center', tdClass:'cursor-pointer',},
                { label: 'Total', field: 'total', thClass: 'text-center', tdClass:'cursor-pointer'},
                { label: 'Status', field: 'status', tdClass: 'status-text'},
                { label: '', field: 'delete_btn',},
            ],
            rows:[],
            invoices:[],
            totalInvoicesAmount:0,
            tableEmptyStateText:'No invoice created.',
        }
    },

    computed:{
        ...mapGetters({
            updateInvoiceTable: 'updateInvoiceTable',
        })
    },

    beforeCreate: function(){
        //
    },

    created: function(){
        //this.fetchInvoices();
        this.fetchInvoices();
    },

    mounted: function(){
        console.log('vx resource mounted', this.$store.state.chargesTab)
    },

    methods: {
        // fetchInvoices: function(){
        //     this.tableEmptyStateText = "Fetching invoices..."
        //     axios.get("/custom/invoices/by-shipment/" + this.$store.state.chargesTab)
        //     .then(response => {
        //         let rowData = [];
        //         if(response.data.success){
        //             const responseData = response.data.results;
        //             if(responseData.length > 0){
        //                 let calculatedTotalInvoices = 0;
        //                 responseData.map(function(o){
        //                     let invoiceTotal = 0;
        //                     let paymentTotal = 0;
        //                     o.invoice.invoice_services.forEach((service) => {
        //                         invoiceTotal = (invoiceTotal + (service.quantity * parseFloat(service.rate)))
        //                     });
        //                     if(o.payment.length > 0){
        //                         o.payment.forEach((payment)=>{
        //                             paymentTotal = paymentTotal + parseFloat(payment.TotalAmt);
        //                         })
        //                     }
        //                     let paymentStatus = paymentTotal >= invoiceTotal ? "PAID" : "Partially paid " + parseFloat(paymentTotal).toFixed(2);
        //                     let emailStatus = o.invoice.is_email_sent === 1 ? "Email Sent" : "";
        //                     let row = {
        //                         invoice_date: o.invoice.invoice_date ? moment.utc(String(o.invoice.invoice_date)).format('MMM DD, YYYY') : '',
        //                         invoice_num: o.invoice.invoice_num,
        //                         due_date: o.invoice.due_date ? moment.utc(String(o.invoice.due_date)).format('MMM DD, YYYY') : '',
        //                         total: parseFloat(invoiceTotal).toFixed(2),
        //                         status: o.payment.length > 0 ? paymentStatus : emailStatus,
        //                         id: o.invoice.id,
        //                         qbo_id: o.invoice.qbo_id,
        //                         qbo_customer_name: o.invoice.qbo_customer_name,
        //                     }
        //                     rowData.push(row);
        //                     calculatedTotalInvoices += invoiceTotal;
        //                 }, this);
        //                 this.totalInvoicesAmount = parseFloat(calculatedTotalInvoices).toFixed(2);
        //                 this.$emit('getTotalInvoicesAmount',calculatedTotalInvoices);
        //             }
        //         }
        //         this.rows = rowData;
        //         this.tableEmptyStateText = "No invoice created."
        //     })
        //     .catch(e => {
        //         console.log(e);
        //     });
        // },

        fetchInvoices: function(){
            this.tableEmptyStateText = "Fetching invoices..."
            axios.get("/custom/invoices/by-shipment-by-qbo?shipmentId="+this.$store.state.chargesTab.resourceId+'&realmId='+this.realmId)
            .then(response => {
                console.log('fetchinvoice',response)
                let rowData = [];
                if(response.data.success){
                    const responseData = response.data.results;
                    if(responseData.length > 0){
                        let calculatedTotalInvoices = 0;
                        responseData.map(function(o){
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
                            let paymentStatus = paymentTotal >= invoiceTotal ? "PAID" : "Partially paid " + parseFloat(paymentTotal).toFixed(2);
                            let emailStatus = o.invoice.is_email_sent === 1 || o.invoice.is_email_sent === "Yes" || o.invoice.email_sent_count > 0 ? "Email Sent" : "";
                            let row = {
                                invoice_date: o.invoice.invoice_date ? moment.utc(String(o.invoice.invoice_date)).format('MMM DD, YYYY') : '',
                                invoice_num: o.invoice.invoice_num,
                                due_date: o.invoice.due_date ? moment.utc(String(o.invoice.due_date)).format('MMM DD, YYYY') : '',
                                total: parseFloat(invoiceTotal).toFixed(2),
                                status: o.payment.length > 0 ? paymentStatus : emailStatus,
                                id: o.invoice.id,
                                qbo_id: o.invoice.qbo_id,
                                qbo_customer_name: o.invoice.qbo_customer_name,
                            }
                            rowData.push(row);
                            calculatedTotalInvoices += invoiceTotal;
                        }, this);
                        this.totalInvoicesAmount = parseFloat(calculatedTotalInvoices).toFixed(2);
                        this.$emit('getTotalInvoicesAmount',calculatedTotalInvoices);
                    }
                }
                this.rows = rowData;
                this.tableEmptyStateText = "No invoice created."
            })
            .catch(e => {
                console.log(e);
            });
        },

        updateInvoiceTableHandler(){
            const state = this.$store.state.chargesTab;
            console.log('compare1',this.realmId)
            console.log('compare2',state.qboCompanyInfo.realm_id)
            if(this.realmId === state.qboCompanyInfo.realm_id){
                console.log('realm ID matched');
            }
            this.$store.commit('updateInvoiceTable_CT', false)
            //this.fetchInvoices();
        },

        onCellClick: function(params){
            const field = params.column.field;
            const rowData = params.row;
            if(field !== 'delete_btn'){
                this.$store.commit('updateSelectedInvoice_CT', rowData)
                this.$store.commit('openEditInvoiceModal_CT',true)
            }
        },

        onDeleteClick: function(data){
            console.log(data);
            this.$store.commit('updateSelectedInvoice_CT', data)
            this.$store.commit('openDeleteInvoiceModal_CT',true)
        },

        rowStyleClassFn(row) {
            return'vgt-row';
        },
    },

    watch:{
        updateInvoiceTable(newVal,oldVal){
            if(newVal === true){
                this.updateInvoiceTableHandler();
            }
        },
    }
}
</script>
<style scoped>
    .text-center{
        text-align: center !important;
    }
    .status-text span{
        color: #16A34A;
        font-style: italic;
    }
    .row-hover>tbody>tr>span:hover{
        color: #000000 !important;
    }
    .vgt-row tr:hover td{
        background-color: #E8EAED !important;
    }
    .vgt-row td:hover span{
        color: #000000 !important;
    }
</style>