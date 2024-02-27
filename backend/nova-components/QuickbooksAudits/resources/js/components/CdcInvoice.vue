<template>
    <div class="flex w-full">
        <div class="flex-1">
                <input v-model="invoiceChangeSince" class="w-full form-control form-control-sm form-input form-input-bordered" type="date" style="border-radius:0px;">  
                <button @click.prevent="onRunDataChangeCheckOnInvoice" class="btn btn-default btn-primary">Run Data Change Check on Invoice</button>
        </div>
        <div class="flex-1"></div>
        <div class="flex-1"></div>
    </div>
</template>


<script>

export default {
    name: "CdcInvoice",
    components: {
        
    },
    props: [],

    data(){
        return{
           invoiceChangeSince:null,
           entity: 'INVOICE'
        }
    },
    computed:{

    },

    mounted() {

    },
  
    methods:{
        onRunDataChangeCheckOnInvoice: function(){
            console.log('run');
            axios.get('/nova-vendor/quickbooks-audits/qbo-audits/invoice/run-data-check?date='+this.invoiceChangeSince+'&entity='+this.entity)
            .then(response => {
                console.log(response);
                if(response.data.result){
                    const result = response.data.result;
                    if(result.invoicesNotInShifl.lenght > 0){
                        console.log(result.invoicesNotInShifl.lenght);
                    }

                }
            })
            .catch(e => {

            });
        }
    }
}

</script>

<style scoped>
</style>
