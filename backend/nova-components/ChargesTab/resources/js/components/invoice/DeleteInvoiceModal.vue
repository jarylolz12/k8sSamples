<template>
    <div>
        <div v-if="selectedInvoice.qbo_id" class="p-8 dx-viewport container" style="height: 100%; width: 100%;">
             <center>
                <h3>Confirm to Delete Invoice</h3>
                <h4 class="mt-3">{{selectedInvoice.qbo_customer_name}} - Invoice <span class="color-primary-dark">#{{selectedInvoice.invoice_num}}</span></h4>
                <h4 class="mt-3">Amount: <span class="color-primary-dark">{{selectedInvoice.total}}</span></h4>
            </center>
            <div class="flex mb-3 mt-8">
                 <div style="height:100%; display: inline-block; width:auto; margin: 0 auto;">
                        <button
                            :disabled="deleting"
                            type="button"
                            ref="deleteInvoiceButton"
                            @click.prevent="handleDeleteInvoice"
                            class="btn-default float-right"
                            style="border:0 solid var(--black); background-color:var(--danger); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                        >{{deleting ? "Deleting...":"Delete"}}</button>
                        <button
                            type="button"
                            ref="deleteInvoiceButton"
                            @click.prevent="cancelDeleteInvoice"
                            class="btn-default float-right mr-3"
                            style="border:0 solid var(--black); background-color:var(--primary); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                        >Cancel</button>
                    </div>
            </div>
        </div>
        <div v-else class="mt-6">
            <center>
                <h4>{{fetchingMessage}}</h4>
            </center>
        </div>
    </div>
</template>

<script>

export default {
    name: "DeleteInvoiceModal",

    components: {

    },

    props: ['resourceId'],

    data() {
        return {
            deleting:false,
            fetchingMessage:'Fetching data. Please wait...',
            invoiceObject:{},
            selectedInvoice:null,
        };
    },

    created() {
        const state = this.$store.state;
        this.selectedInvoice = state.chargesTab.selectedInvoice;
        // if(state.chargesTab.selectedInvoice){
        //     this.fetchingMessage ="Fetching data. Please wait..."
        //     axios.get('/custom/qbo/invoice/by-id/'+this.selectedInvoice.qbo_invoice_id)
        //     .then(response => {
        //         const result = response.data[0];
        //         if(result.Id > 0 && result.SyncToken){
        //             // this.syncToken = result.SyncToken;
        //             // this.invoiceObject = result;
        //             this.fetchingMessage = ""
        //         }else{
        //             this.fetchingMessage = " Failed to fetch Invoice Data."
        //         }
        //     })
        //     .catch(e=>{
        //         console.log(e)
        //         this.fetchingMessage = " Failed to fetch Invoice Data."
        //     })
        // }
    },

    methods: {
        handleDeleteInvoice(){
            this.deleting = true;
            if(Object.keys(this.selectedInvoice).length > 0){
                axios.post('/custom/qbo/invoice/delete',this.selectedInvoice)
                .then(response => {
                    console.log(response);
                        if(response.data.success === true){
                            Nova.success('Invoice has been successfully deleted!');
                            // this.$emit('updateInvoicesTable');
                            // setTimeout(()=>{this.$emit('close');},300);
                            this.updateInvoiceTable();
                            this.cancelDeleteInvoice();
                            this.deleting = false;
                        }
                })
                .catch(e => {
                        console.log(e);
                        Nova.error(e);
                        this.deleting = false;
                })
            }
        },
        cancelDeleteInvoice(){
            //this.$emit('close');
            this.$store.commit('showDeleteInvoiceModal_CT',false)
        },
        updateInvoiceTable: function(){
            this.$store.dispatch('fetchShipmentInvoices', this.resourceId)
        },
    },
};

</script>

<style scoped>
    .color-primary-dark{
        color:#004B88;
    }
</style>
