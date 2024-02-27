<template>
    <div>
        <div class="w-full mb-4">
            <center>
                <h3>Are you sure you want to delete this bill from {{selectedBill.location === "SHIFL_DB" ? "this system database" : "Quickbooks"}}? </h3>
                <h4 class="mt-1">This action cannot be undone. Please review before deleting.</h4>
            </center>
        </div>
        <div class="flex">
            <button
                type="button"
                @click.prevent="confirmDeleteClick"
                class="btn-default btn-delete-bill"
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

    props: ["selectedBill"],

    data() {
        return {
            deleteBtnText: 'Yes, Delete Bill.',
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
            axios.post('/nova-vendor/quickbooks-audits/bill/delete-bill',this.selectedBill)
            .then( response => {
                const data = response.data;
                if(data.success === true){
                    Nova.success("Bill deleted successfully.");
                }else{
                    if(data.error === true){
                        Nova.error(data.result);
                    }
                }
                
                this.deleteBtnText = "Yes, Delete Bill.";
                this.disabled = false;
            })
            .catch( e => {
                Nova.error(e);
                console.log(e)
                this.deleteBtnText = "Yes, Delete Bill.";
                this.disabled = false;
            })
        }
    },
};

</script>

<style scoped>
    .btn-delete-bill{
        background-color:#e74444;
        color:#ffffff !important;
    }
    .btn-delete-bill:hover{
        background-color:#CA2D2D !important;
    }
</style>
