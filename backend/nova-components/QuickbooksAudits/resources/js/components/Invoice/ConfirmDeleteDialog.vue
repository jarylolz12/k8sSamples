<template>
    <div>
        <div class="w-full mb-4">
            <center>
                <h3>Are you sure you want to delete this invoice from {{selectedInvoice.location === "SHIFL_DB" ? "this system database" : "Quickbooks"}}? </h3>
                <h4>This action cannot be undone.</h4>
            </center>
        </div>
        <div class="flex">
            <button
                type="button"
                @click.prevent="confirmDeleteClick"
                class="btn-default btn-delete-invoice"
                style="margin-left: auto; margin-right:auto; border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                :disabled="disabled"
            >{{deleteBtnText}}</button>
        </div>
    </div>
</template>

<script>

export default {
    name: "ConfirmDeleteDialog",

    components: {

    },

    props: ["selectedInvoice"],

    data() {
        return {
            deleteBtnText: 'Yes Delete Invoice.',
            disabled:false,
        };
    },

    created() {
        //
    },

    mounted(){
        
    },

    methods: {
       confirmDeleteClick: function(){
            this.deleteBtnText = "Deleting...";
            this.disabled = true;
            axios.post('/nova-vendor/quickbooks-audits/invoice/delete-invoice',this.selectedInvoice)
            .then( response => {
                const data = response.data;
                if(data.success === true){
                    Nova.success("Invoice deleted successfully.");
                    this.deleteBtnText = "Yes Delete Invoice";
                    this.disabled = false;
                }else{
                    Nova.error(data.errors);
                    this.deleteBtnText = "Yes Delete Invoice";
                    this.disabled = false;
                }
            })
            .catch( e => {
                Nova.error(e);
                console.log(e)
                this.deleteBtnText = "Yes Delete Invoice";
                this.disabled = false;
            })
        }
    },
};

</script>

<style scoped>
    .btn-delete-invoice{
        background-color:#e74444;
        color:#ffffff !important;
    }
    .btn-delete-invoice:hover{
        background-color:#CA2D2D !important;
    }
</style>
