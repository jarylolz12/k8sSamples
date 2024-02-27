<template>
    <div>
        <v-select
          class="customer-searchbox"
         :options="selectOptions"
         :label="'FullyQualifiedName'"
         placeholder="Search Customer"
         @input="onCustomerSelect"
         @search="searchCustomer"
         v-model="initValue"
        ></v-select>
    </div>
</template>

<script>
import axios from 'axios';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    name: "CustomerSearchBox",
    components: {
        vSelect
    },
    props: ["resourceId","initCustomerValue","isAddInvoice"],

    data() {
        return {
            selectOptions: [],
            initValue: this.initCustomerValue && this.isAddInvoice === true ? this.initCustomerValue.customer : this.initCustomerValue,
            customerDetails:[]
        };
    },
    beforeCreate: function () {
        //
    },
    created() {
        //
    },
    beforeMount() {
       //
    },
    mounted() {
        if(typeof this.initCustomerValue.customer !== 'undefined' && this.isAddInvoice === true){
            const customer = this.initCustomerValue.customer;
            if(typeof customer.Id !== 'undefined'){
                this.initValue = customer;
                this.$emit('changed', customer);
            }
        }
    },
    methods: {

        onCustomerSelect: function (value) {
            this.$emit('changed', value)
        },

        searchCustomer(search,loading){
            if(search.length){
                loading(true);
                this.search(loading, search, this);
            }
        },
        search: _.debounce((loading,search,vm)=>{
            axios.get('/custom/qbo/customers-search?query='+search)
            .then(response => {
                if(response.data.length>0){
                    loading(false);
                    vm.selectOptions = response.data;
                }else{
                    loading(false);
                    vm.selectOptions = []
                }
            })
            .catch(e => {
                console.log(e)
                loading(false);
            })
        },500)
    },
};
</script>

<style scoped>
</style>
