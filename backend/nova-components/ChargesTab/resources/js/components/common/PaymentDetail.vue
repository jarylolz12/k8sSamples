<template>
    <div>
        <div v-if="qboInvoice !== null || qboPayment !== null" class="flex flex-col">
            <div class="">
               <label class="font-bold">Payment Details:</label>
            </div>
            <div v-if="qboPayment && qboPayment.length > 0" class="mt-2">
               <label class="font-semibold float-right"><span @click="onClickShowPayment" class="show-payments">{{qboPayment.length}} Payment{{qboPayment.length > 1 ? 's' : ''}}</span> made ({{totalPayment}})</label><br/>
                <div v-if="showPayment">
                    <div v-for="payment in qboPayment" :key="payment.Id">
                        <label class="show-payments font-light italic float-right" style="color:#297ec0">{{payment.TotalAmt}} payment made on ({{payment.TxnDate}})</label><br/>
                    </div>
                </div>
            </div>
            <div v-if="qboInvoice !== null" class="mt-2">
               <label class="font-bold float-right">Balance Due: {{qboInvoice.Balance ? qboInvoice.Balance : ''}}</label>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "PaymentDetail",
    components:{
        
    },
    
    props: ["selectedInvoiceQBOId", "subTotal"],

    data() {
        return {
            qboInvoice:null,
            qboPayment:null,
            showPayment: false,
        };
    },

    computed: {
        totalPayment: function(){
            let total = 0;
            if(this.qboPayment !== null){
                this.qboPayment.map(e => {
                    total = total + parseFloat(e.TotalAmt);
                })
            }
            return parseFloat(total).toFixed(2);
        },

        computedBalance: function(){
            let total = 0;
            if(this.totalPayment){
                total = this.subTotal - this.totalPayment;
            }
            return total;
        }
    },

    created() {
     //
    },

    mounted(){
        this.fetchQBOInvoice();
    },

    methods: {
        fetchQBOInvoice: function(){
            if(this.selectedInvoiceQBOId > 0){
                axios.get('/custom/qbo/invoice/get-by-qbo-id?qboInvoiceId='+this.selectedInvoiceQBOId)
                .then( response => {
                    if(response.data.success){
                        let data = response.data.result;
                        this.qboInvoice = data.invoice;
                        this.qboPayment = data.payment;
                    }
                })
                .catch(e => {
                    console.log(e)
                })
            }
        },

        onClickShowPayment: function(){
            this.showPayment = this.showPayment === true ? false : true;
        }
    },
};

</script>

<style scoped>
    .show-payments{
        color:#297ec0;
        cursor: pointer;
    }
</style>
