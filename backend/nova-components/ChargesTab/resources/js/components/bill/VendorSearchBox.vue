<template>
    <div>
        <v-select
         class="vendorSelect"
         :options="selectOptions"
         :label="'DisplayName'"
         placeholder="Search Vendor"
         @input="onVendorSelect"
         @search="searchVendor"
         v-model="initValue"
        ></v-select>
    </div>
</template>

<script>
import axios from 'axios';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    name: "VendorSearchBox",
    components: {
        vSelect
    },
    props: ["resourceId","initVendorValue"],

    data() {
        return {
            selectOptions: [],
            initValue: this.initVendorValue ? this.initVendorValue : [],
            vendorDetails:[]
        };
    },
    beforeCreate: function () {
        
    },
    created() {
    },
    beforeMount() {
       
    },
    methods: {

        onVendorSelect: function (value) {
            //TODO: fill Mailing Address, Terms, Bill # on select

            //pass value to parent AddBill Modal
            this.$emit('changed', value)
        },

        searchVendor(search,loading){
            if(search.length){ 
                loading(true);
                this.search(loading, search, this);
            }
        },
        search: _.debounce((loading,search,vm)=>{
            axios.get('/custom/qbo/vendors-search?query='+search)
            .then(response => {
                if(response.data.length>0){
                    //console.log(response.data)
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
    mounted() {
    }
};
</script>

<style scoped>
</style>
