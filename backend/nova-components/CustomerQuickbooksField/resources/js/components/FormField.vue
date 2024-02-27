<template>
    <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
        <template slot="field">
            <!-- <input
                :id="field.name"
                type="text"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value"
            /> -->
            
            <div v-if="qboCompanyInfo">
                <div>
                    <span class="text-sm italic">You are currently connected to:</span><span class="text-sm italic" style="color:var(--primary-dark)"> {{qboCompanyInfo.company.CompanyName}}</span>
                </div>
                <v-select
                    :id="field.name"
                    class="w-full"
                    :class="errorClasses"
                    :options="selectOptions"
                    :label="'FullyQualifiedName'"
                    placeholder="Search Customer"
                    @search="searchCustomer"
                    @input="onSelectCustomer"
                    v-model="vSelectVal"
                ></v-select>
                <div class="flex flex-col items-center">
                    <div class="text-sm italic mt-2" style="color:var(--primary-dark)">-OR-</div>
                    <div class="mt-2">Create new QuickBooks Customer</div>
                </div>
                
                <div class="flex w-full mt-2">
                    <div class="w-1/4">
                        <label class="inline-block pt-2">Create New:</label>
                    </div>
                    <div class="w-3/4">
                        <input
                         class="form-control form-input form-input-bordered ml-2"
                         style="transform:scale(2); cursor: pointer;"
                         type="checkbox"
                         :value="createQBOCustomer"
                         :checked="createQBOCustomer"
                         @change="onCustomerCheckboxChange"
                        />
                    </div>
                </div>
                <div v-if="createQBOCustomer" class="flex flex-col items-center mt-1">
                    <div class="flex w-full">
                        <div class="w-1/4">
                            <label class="inline-block pt-2">Bill To:</label>
                        </div>
                        <div class="w-3/4">
                            <input 
                             v-model="companyName" 
                             class="w-full form-control form-input form-input-bordered" 
                             type="text"
                             placeholder="Company Name by default"
                            />
                        </div>
                    </div>
                    <div class="flex w-full mt-1">
                        <div class="w-1/4">
                            <label class="inline-block pt-1">Invoice Email Address:</label>
                        </div>
                        <div class="w-3/4">
                            <input 
                             v-model="invoiceEmailAddress" 
                             class="w-full form-control form-input form-input-bordered" 
                             type="text"
                             placeholder="Email Recipients by default (If multiple separate by comma)"
                            />
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-else>
                You are not connected to QuickBooks
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import axios from 'axios';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field', 'panel'],

    components:{
        vSelect
    },    

    data(){
        return{
            selectOptions: [],
            qboCompanyInfo: null,
            fieldValue: null,
            vSelectVal: null,

            createQBOCustomer: false,
            createQBOCustomerError: true,
            invoiceEmailAddress: '',
            companyName: '',
            reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
        }
    },

    created(){
        axios.get('/custom/qbo/get-company-info-v2')
        .then(response =>{
            if(response.data.success){
                this.qboCompanyInfo = response.data.result;
            }
        })
        .catch(e => {
            console.log(e)
            Nova.error("Something went wrong while fetching QBO company info.")
        });
    },

    mounted(){

    },

    methods: {
        /*
        * Set the initial, internal value for the field.
        */
        setInitialValue() {
            if(this.field.value){
                let val = JSON.parse(this.field.value);
                this.vSelectVal = val ? val.customer : '';
                this.value = JSON.parse(this.field.value) || '';
            }
            //this.selectOptions = JSON.parse(this.field.value) || '';
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            //this.validateEmail();
            if(this.createQBOCustomer){
                const createCustomerValue = {
                    createQBOCustomer: true,
                    invoiceEmailAddress: this.invoiceEmailAddress,
                    companyName: this.companyName,
                }
                formData.append(this.field.attribute, JSON.stringify(createCustomerValue) || '')
            }else{
                formData.append(this.field.attribute, JSON.stringify(this.value) || '')
            }
        },

        searchCustomer(search,loading){
            if(search.length){
                loading(true);
                this.search(loading, search, this);
            }
        },

        search: _.debounce((loading,search,vm)=>{

            let newSearch = encodeURIComponent(search)
            axios.get('/custom/qbo/customers-search?query='+newSearch)
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
                console.log(e);
                loading(false);
            })
        },500),

        onSelectCustomer: function(value){
            if(value){
                let filterValue = _.pick(value,['Id','DisplayName','FullyQualifiedName', 'Balance','PrimaryEmailAddr','GivenName','FamilyName','BillAddr']);
                this.value = {
                    customer : filterValue,
                    company: this.qboCompanyInfo.company.CompanyName,
                    realm_id: this.qboCompanyInfo.realm_id,
                }
            }else{
                this.value = '';
            }
        },

        onCustomerCheckboxChange: function(e){
            this.createQBOCustomer = this.createQBOCustomer ? false : true;
            if(this.createQBOCustomer){
                const companyNameFieldInstance = this.findFieldByAttribute('company_name');
                //const compName = companyNameFieldInstance.field.value !== null ? companyNameFieldInstance.field.value : '';
                this.companyName = companyNameFieldInstance.value;
            }
        },

        validateEmail: function() {
            return (this.invoiceEmailAddress == "") ? this.createQBOCustomerError = true : (this.reg.test(this.invoiceEmailAddress)) ? this.createQBOCustomerError = false : this.createQBOCustomerError = true;
        },

        findFieldByAttribute: function(attribute) {
            return this.$parent.$children.find(brotherField => brotherField.field.attribute === attribute);
        },

        // initDefaultCompanyName: function(){
        //     const companyNameFieldInstance = this.findFieldByAttribute('company_name');
        //     const compName = companyNameFieldInstance.field.value !== null ? companyNameFieldInstance.field.value : '';
        //     this.companyName = compName;
        // }
    },
}
</script>
