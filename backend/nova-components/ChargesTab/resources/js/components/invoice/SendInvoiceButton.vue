<template>
    <div class="flex">
        <div v-if="isInvoiceSent" class="status-badge-container">
            <label class="mr-2">Status: 
                <span class="text-sm px-2 status-badge">Sent</span> 
                <span v-if="invoiceLastDelivery !== null" class="text-xs" style="font-style:italic">
                    Last Delivery: Sent by Email at  {{formattedLastDelivery}}
                </span>
            </label>
        </div>
        <div>  
            <button
                :disabled="sending"
                type="button"
                ref="sendInvoiceButton"
                @click.prevent="handleSendInvoice"
                class="btn-default btn-send-invoice"
                style="border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
            >{{sending ? "Sending..." : (isInvoiceSent ? "Resend Invoice" : "Send Invoice")}}
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import './sass/invoice.scss';
export default {
    name: "SendInvoiceButton",
    props: ["resourceId", "invoiceId", "isInvoiceSent", "invoiceLastDelivery"],

    data() {
        return {
           sending: false,
        }
    },

    methods: {
        handleSendInvoice: function(){
            this.sending = true;
            const formData = new FormData();
            formData.append('invoice_id',this.invoiceId);

            axios.post("/custom/qbo/invoice/send-invoice",formData)
            .then( response =>{
                if (response.data.success === true) {
                    Nova.success('Invoice sent!');
                    this.sending = false;
                    this.$emit('updateInvoicesTable');
                }
                this.sending = false;
            })
            .catch(e =>{
                this.sending = false;
                console.log(e)
                Nova.error(e);
            });
        },
    },
    
    computed:{
        formattedLastDelivery: function(){
            if(this.invoiceLastDelivery !== null){
                return moment(this.invoiceLastDelivery).format('MMM DD, YYYY hh:mm A');
            }
        }
    }
};
</script>

<style scoped>
    .btn-send-invoice{
        background-color:#16A34A;
        color:#ffffff !important;
    }
    .btn-send-invoice:hover{
        background-color:#15803D !important;
    }
    .status-badge-container{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .status-badge{
        border-radius: 5px;
        background-color: #1ad65f;
        padding-top: .15rem;
        padding-bottom: .15rem;
        padding-right: .5rem;
        padding-left: .5rem;
        color: #ffffff;
    }
</style>
