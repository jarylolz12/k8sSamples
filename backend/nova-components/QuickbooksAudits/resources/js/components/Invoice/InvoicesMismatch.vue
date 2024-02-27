<template>
    <div>
        <div class="flex w-full">
            <div class="flex-1">
                <button @click.prevent="onSearchMismatchInvoices" class="btn btn-default btn-primary">{{btnTxt}}</button>
            </div>
        </div>
        <div class="flex w-full mt-4 mb-4"  v-if="Object.keys(invoicesNotInShifl).length > 0 || Object.keys(invoicesNotInQBO).length > 0">
            <div class="flex-1">
                <h3>Mismatches have found!</h3><br/>
                <h4>Summary:</h4>
                <label>Out of {{shiflResponseCount}} System query results and {{qboResponseCount}} Quickbooks query results</label><br/>
                <label>found:</label><br/>
                <label class="text-danger" v-if="Object.keys(invoicesNotInQBO).length > 0">{{Object.keys(invoicesNotInQBO).length}} shifl invoices not found in QBO Database</label><br/>
                <label class="text-danger" v-if="Object.keys(invoicesNotInShifl).length > 0">{{Object.keys(invoicesNotInShifl).length}} QBO invoices not found in shifl Database</label><br/>
            </div>
        </div>
        <div class="flex w-full mt-4">
            <div  v-if="Object.keys(invoicesNotInQBO).length > 0" class="flex-1 mt-4">
                <h4 class="text-danger">{{Object.keys(invoicesNotInQBO).length}}  Invoices from shifl Database not Found in Quickbooks Database:</h4><br/>
                <invoices-not-in-qbo-table
                 :tableData="invoicesNotInQBO"
                 @onOpenVerificationModal="onOpenVerificationModal"
                 @onCloseVerificationModal="onCloseVerificationModal"
                />
            </div>
        </div>
        <div class="flex w-full mt-4">
            <div  v-if="Object.keys(invoicesNotInShifl).length > 0" class="flex-1">
                <h4 class="text-danger">{{Object.keys(invoicesNotInShifl).length}}  Invoices from Quickbooks Database not Found in shifl Database:</h4><br/>
                <invoices-not-in-shifl-table
                 :tableData="invoicesNotInShifl"
                 @onOpenVerificationModal="onOpenVerificationModal"
                 @onCloseVerificationModal="onCloseVerificationModal"
                />
            </div>
        </div>
        <transition name="fade">
            <modal ref="verificationModal">
                <invoice-verification-modal
                    @close="onCloseVerificationModal"
                    :selectedInvoice="selectedInvoice"
                />
            </modal>
        </transition>
    </div>
</template>
<script>

import InvoicesNotInQboTable from './InvoicesNotInQboTable';
import InvoicesNotInShiflTable from './InvoicesNotInShiflTable.vue';
import Modal from '../Modal.vue';
import InvoiceVerificationModal from '../Invoice/InvoiceVerificationModal.vue'
export default {
    name: "InvoicesMismatch",
    components: {
        InvoicesNotInQboTable,
        InvoicesNotInShiflTable,
        InvoiceVerificationModal,
        Modal,
    },
    props: ["dateFrom","dateTo"],

    data(){
        return{
           entity: 'INVOICE',
           invoicesNotInShifl: [],
           invoicesNotInQBO: [],
           btnTxt: 'Search Mismatch Invoices',
           selectedInvoice: {},
           shiflResponseCount: null,
           qboResponseCount: null,
        }
    },
    computed:{

    },

    mounted() {
        //
    },
  
    methods:{
        onSearchMismatchInvoices: function(){
            this.btnTxt = 'Searching Mismatch Invoices...';
            axios.get('/nova-vendor/quickbooks-audits/invoice/search-mismatch?dateFrom=' +this.dateFrom + '&dateTo='+this.dateTo + '&entity=' + this.entity)
            .then(response => {
                if(response.data.success){
                    const result = response.data.result;
                    this.invoicesNotInShifl = result.invoicesNotInShifl;
                    this.invoicesNotInQBO = result.invoicesNotInQBO;
                    this.qboResponseCount = result.qboQueryCounts;
                    this.shiflResponseCount = result.shiflQueryCounts;
                }else{
                    if(response.data.error){
                        Nova.error(response.data.result);
                    }
                }
                this.btnTxt = 'Search Mismatch Invoices';
            })
            .catch(e => {
                Nova.error(e);
                this.btnTxt = 'Search Mismatch Invoices';
            });
        },
        
        onOpenVerificationModal: function(data){
            this.selectedInvoice = data;
            this.$refs.verificationModal.open();
        },
        onCloseVerificationModal: function(){
            this.$refs.verificationModal.close(true);
        }
    },
}

</script>

<style scoped>
</style>
