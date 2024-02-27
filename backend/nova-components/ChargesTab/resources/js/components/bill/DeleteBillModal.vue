<template>
    <div>
        <div v-if="selectedBill.qbo_bill_id" class="p-8 dx-viewport container" style="height: 100%; width: 100%;">
             <center>
                <h3>Confirm to Delete Bill</h3>
                <h4 class="mt-3">{{selectedBill.qbo_vendor_name}} - Bill <span class="color-primary-dark">#{{selectedBill.qbo_bill_num}}</span></h4>
                <h4 class="mt-3">Amount: <span class="color-primary-dark">{{selectedBill.total_amount}}</span></h4>
            </center>
            <div class="flex mb-3 mt-8">
                 <div style="height:100%; display: inline-block; width:auto; margin: 0 auto;">
                        <button
                            :disabled="deleting"
                            type="button"
                            ref="deleteBillButton"
                            @click.prevent="handleDeleteBill"
                            class="btn-default float-right"
                            style="border:0 solid var(--black); background-color:var(--danger); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                        >{{deleting ? "Deleting...":"Delete"}}</button>
                        <button
                            type="button"
                            ref="deleteBillButton"
                            @click.prevent="cancelDeleteBill"   
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
import axios from 'axios';

export default {
    name: "DeleteBillModal",

    components: {

    },

    props: ['resourceId',],

    data() {
        return {
            deleting:false,
            fetchingMessage:'Fetching data. Please wait...',
            billObject:{},
            selectedBill: null,
        };
    },

    created() {
        const state = this.$store.state;
        this.selectedBill = state.chargesTab.selectedBill;
        // if(this.selectedBill.qbo_bill_id){
        //     this.fetchingMessage ="Fetching data. Please wait..."
        //     axios.get('/custom/qbo/bill/by-id/'+this.selectedBill.qbo_bill_id)
        //     .then(response => {
        //         const result = response.data[0];
        //         if(result.Id > 0 && result.SyncToken){
        //             this.syncToken = result.SyncToken;
        //             this.billObject = result;
        //             this.fetchingMessage = ""
        //         }else{
        //             this.fetchingMessage = " Failed to fetch Bill Data."
        //         }
        //     })
        //     .catch(e=>{
        //         console.log(e)
        //         this.fetchingMessage = " Failed to fetch Bill Data."
        //     })
        // }
    },

    methods: {
       handleDeleteBill(){
           this.deleting = true
           if(Object.keys(this.selectedBill).length > 0){
               axios.post('/custom/qbo/bill/delete',this.selectedBill)
               .then(response => {
                    if(response.data.success){
                        Nova.success('Bill has been successfully deleted!');
                        // this.$emit('updateBillsTable');
                        // this.$emit('close')
                        this.updateBillsTable();
                        this.cancelDeleteBill();
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
       cancelDeleteBill(){
            this.$store.commit('showDeleteBillModal_CT',false)
       },
       updateBillsTable: function(){
            this.$store.dispatch('fetchShipmentBills', this.resourceId)
        },
    },
};

</script>

<style scoped>
    .color-primary-dark{
        color:#004B88;
    }
</style>
