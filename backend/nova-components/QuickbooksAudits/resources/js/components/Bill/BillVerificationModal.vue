<template>
    <div>
        <div v-if="selectedBill.location === 'SHIFL_DB'" class="p-4 dx-viewport container" style="height: 100%; width: 100%;">
             <center>
                <h3 class="mt-3">{{selectedBill.qbo_vendor_name}} - QBO Bill ID <span class="color-primary-dark">#{{selectedBill.qbo_bill_id}}</span></h3>
                <h3 class="mt-3">Bill Number <span class="color-primary-dark">#{{selectedBill.qbo_bill_num}}</span></h3>
                <h3 class="mt-3">Amount: <span class="color-primary-dark">{{selectedBill.total_amount}}</span></h3>
            </center>
            <center class="mt-6" v-if="verificationText !==''">
                    <h4>{{verificationText}}</h4>
            </center>
            <div class="flex">
                <div v-if="isMismatchedVerified" class="mt-6 mb-6" style="margin-right:auto; margin-left:auto;">
                    <h3>Bill mismatched verified: <span style="color:#C44141">Not found in quickbooks database.</span></h3>
                </div>
                <div v-else-if="isPresent" class="mt-6 mb-6" style="margin-right:auto; margin-left:auto;">
                    <h3><span style="color:#C44141">Bill is found in database. Please check again.</span></h3>
                </div>
            </div>
        </div>
        <div v-if="selectedBill.location === 'QBO_DB'" class="p-8 dx-viewport container" style="height: 100%; width: 100%;">
             <center>
                <h3 class="mt-3">QBO Bill ID <span class="color-primary-dark">#{{selectedBill.qbo_bill_id}}</span></h3>
                <h3 class="mt-3">Bill Number <span class="color-primary-dark">#{{selectedBill.qbo_bill_num}}</span></h3>
                <h3 class="mt-3">Amount: <span class="color-primary-dark">{{selectedBill.total_amount}}</span></h3>
            </center>
            <center class="mt-6" v-if="verificationText !==''">
                    <h4>{{verificationText}}</h4>
            </center>
            <div class="flex">
                <div v-if="isMismatchedVerified === true" class="mt-6 mb-6" style="margin-right:auto; margin-left:auto;">
                    <h3>Bill mismatched verified: <span style="color:#C44141">Not found in database.</span></h3>
                </div>
                <div v-else-if="isPresent === true" class="mt-6 mb-6" style="margin-right:auto; margin-left:auto;">
                    <h3><span style="color:#C44141">Bill is found in database. Please check again.</span></h3>
                </div>
                <div v-else>

                </div>
            </div>
        </div>
        
        <div class="flex mb-6">
            <div v-if="showConfirmDeleteDialog" class="w-full">
                <confirm-delete-dialog
                 @confirmDelete="deleteInvoiceClick"
                 :selectedBill="selectedBill"
                />
            </div>
        </div>
        <div class="flex mb-3 mt-2">
            <div class="flex-1">
                <div style="height:100%; display: inline-block; width:auto; float:left">
                    <button
                        type="button"
                        ref="deleteInvoiceButton"
                        @click.prevent="closeModal"
                        class="btn-default mr-3 btn-close-modal"
                        style="border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                    >Close</button>
                </div>
            </div>
            <div class="flex-1">
                <div v-if="isMismatchedVerified && showConfirmDeleteDialog === false" style="height:100%; display: inline-block; width:auto; float:right">
                    <button
                        type="button"
                        @click.prevent="deleteInvoiceClick"
                        class="btn-default btn-delete-invoice"
                        style="border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                    >Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ConfirmDeleteDialog from './ConfirmDeleteDialog.vue';
export default {
    name: "BillVerificationModal",

    components: {
        ConfirmDeleteDialog,
    },

    props: ["selectedBill"],

    data() {
        return {
            verificationText: "",
            isMismatchedVerified: false,
            isPresent: false,
            showConfirmDeleteDialog: false,
        };
    },

    created() {
        //
    },

    mounted(){
        this.verifyBill();
    },

    methods: {
       closeModal(){
           this.$emit('close');
       },

       verifyBill(){
            this.verificationText = "Verifying bill, please wait...";
            this.isMismatchedVerified = false;
            this.isPresent = false;
            if(this.selectedBill.location === 'SHIFL_DB'){
                const payload = JSON.stringify(this.selectedBill);
                axios.get('/nova-vendor/quickbooks-audits/bill/verify-in-qbo?payload='+payload)
                .then(response => {
                    const data = response.data;
                    if(data.success === true){
                        if(data.is_present === false){
                            this.isMismatchedVerified = true;
                        }else{
                            this.isPresent = true;
                        }
                    }else{
                        if(data.error === true){
                            Nova.error(data.result)
                        }
                        this.isMismatchedVerified = false;
                    }
                    this.verificationText = '';
                })
                .catch(e=>{
                    this.isMismatchedVerified = false;
                    this.verificationText = '';
                    Nova.error(e);
                    console.log(e);
                })
            }
            if(this.selectedBill.location === 'QBO_DB'){
                const payload = JSON.stringify(this.selectedBill);
                axios.get('/nova-vendor/quickbooks-audits/bill/verify-in-shifl?payload='+payload)
                .then(response => {
                    const data = response.data;
                    if(data.success === true){
                        if(data.is_present === false){
                            this.isMismatchedVerified = true;
                        }
                    }else{
                        if(data.is_present === true){
                            this.isMismatchedVerified = false;
                            this.isPresent = true;
                        }  
                    }
                    this.verificationText = '';
                })
                .catch(e=>{
                    this.isMismatchedVerified = false;
                    this.verificationText = '';
                    Nova.error(e);
                    console.log(e);
                })
            }

       },

        deleteInvoiceClick: function(){
            this.showConfirmDeleteDialog = this.showConfirmDeleteDialog ? false : true;
        }

    },
};

</script>

<style scoped>
    .color-primary-dark{
        color:#004B88;
    }
    .btn-delete-invoice{
        background-color:#e74444;
        color:#ffffff !important;
    }
    .btn-delete-invoice:hover{
        background-color:#CA2D2D !important;
    }
    .btn-close-modal{
        background-color:#71717A !important;
        color:#ffffff !important;
    }
    .btn-close-modal:hover{
        background-color:#52525B !important;
    }
</style>
