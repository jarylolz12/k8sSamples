<template>
    <div>
        <div class="flex w-full">
            <div class="flex-1">
                <button @click.prevent="onSearchMismatchBills" class="btn btn-default btn-primary">{{btnTxt}}</button>
            </div>
        </div>
        <div class="flex w-full mt-4 mb-4"  v-if="Object.keys(billsNotInShifl).length > 0 || Object.keys(billsNotInQBO).length > 0">
            <div class="flex-1">
                 <h3>Mismatches have found!</h3><br/>
                <h4>Summary:</h4>
                <label>Out of {{shiflResponseCount}} System query results and {{qboResponseCount}} Quickbooks query results</label><br/>
                <label>found:</label><br/>
                <label class="text-danger" v-if="Object.keys(billsNotInQBO).length > 0">{{Object.keys(billsNotInQBO).length}} shifl bills not found in Quickbooks Database</label><br/>
                <label class="text-danger" v-if="Object.keys(billsNotInShifl).length > 0">{{Object.keys(billsNotInShifl).length}} QBO Bills not found in shifl Database</label><br/>
            </div>
        </div>
        <div class="flex w-full mt-4">
            <div  v-if="Object.keys(billsNotInQBO).length > 0" class="flex-1 mt-4">
                <h4 class="text-danger">{{Object.keys(billsNotInQBO).length}} Bills from shifl Database not Found in Quickbooks Database:</h4><br/>
                <bills-not-in-qbo-table
                 :tableData="billsNotInQBO"
                 @onOpenBillVerificationModal="onOpenBillVerificationModal"
                />
            </div>
        </div>
        <div class="flex w-full mt-4">
            <div  v-if="Object.keys(billsNotInShifl).length > 0" class="flex-1">
                <h4 class="text-danger">{{Object.keys(billsNotInShifl).length}} Bills from Quickbooks Database not Found in shifl Database:</h4><br/>
                <bills-not-in-shifl-table
                 :tableData="billsNotInShifl"
                 @onOpenBillVerificationModal="onOpenBillVerificationModal"
                />
            </div>
        </div>
        <transition name="fade">
            <modal ref="verificationModal">
                <bill-verification-modal
                    @close="onCloseBIllVerificationModal"
                    :selectedBill="selectedBill"
                />
            </modal>
        </transition>
    </div>
</template>
<script>

import Modal from '../Modal.vue';
import BillsNotInQboTable from './BillsNotInQboTable';
import BillsNotInShiflTable from './BillsNotInShiflTable';
import BillVerificationModal from './BillVerificationModal';
export default {
    name: "BillsMismatch",
    components: {
        Modal,
        BillsNotInQboTable,
        BillVerificationModal,
        BillsNotInShiflTable,
    },
    props: ["dateFrom","dateTo"],

    data(){
        return{
           entity: 'BILL',
           billsNotInShifl: [],
           billsNotInQBO: [],
           btnTxt: 'Search Mismatch Bills',
           selectedBill:{},
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
        onSearchMismatchBills: function(){
            this.btnTxt = 'Searching Mismatch Bills...';
            axios.get('/nova-vendor/quickbooks-audits/bill/search-mismatch?dateFrom=' +this.dateFrom + '&dateTo='+this.dateTo + '&entity=' + this.entity)
            .then(response => {
                if(response.data.success){
                    const result = response.data.result;
                    this.billsNotInShifl = result.billsNotInShifl;
                    this.billsNotInQBO = result.billsNotInQBO;
                    this.qboResponseCount = result.qboQueryCounts;
                    this.shiflResponseCount = result.shiflQueryCounts;
                }else{
                    if(response.data.error){
                        Nova.error(response.data.result);
                    }
                }
                this.btnTxt = 'Search Mismatch Bills';
            })
            .catch(e => {
                Nova.error(e);
                this.btnTxt = 'Search Mismatch Bills';
            });
        },
        
        onOpenBillVerificationModal: function(data){
            this.selectedBill = data;
            this.$refs.verificationModal.open();
        },
        onCloseBIllVerificationModal: function(){
            this.$refs.verificationModal.close(true);
        }
    },
}

</script>

<style scoped>
</style>
