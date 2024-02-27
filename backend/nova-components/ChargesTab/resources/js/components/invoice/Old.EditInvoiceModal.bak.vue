<template>
    <div>
        <div v-if="invoiceLoaded === true"  class="add-invoice-modal-container p-8 dx-viewport container" style="max-height: 80vh; width: 100%; overflow-y:auto">
            <div class="flex">
                <div class="flex-1" style="width:30%!impotant">
                    <label>QB Customer: </label>
                    <customer-search-box
                        :resourceId="resourceId"
                        @changed="onCustomerSelect"
                        :initCustomerValue="initCustomerValue"
                    ></customer-search-box>
                </div>
                <div class="flex-1 mr-2"></div>
                <div class="flex-1 mr-2"></div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label>Customer Email:</label>
                    <input class="w-full form-control form-input form-input-bordered" type="email" v-model='qbCustomerEmail'>
                </div>
                <div class="flex-1 mr-2">
                    <label>Invoice #:</label>
                    <input class="w-full form-control form-input form-input-bordered" type="text" v-model='qbInvoiceNumber'>
                </div>
                <div class="flex-1 mr-2">
                    <label>Billing Address: </label>
                    <textarea rows="2" style="height:auto;" class="w-full form-control form-input form-input-bordered" v-model='qbCustomerBillingAddress'></textarea>
                </div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label>Terms: </label>
                    <v-select
                        v-if="terms.length > 0"
                        :options="terms"
                        :label="'Name'"
                        placeholder="Select Term"
                        @input="onTermSelect"
                        v-model="termValue"
                    ></v-select>
                </div>
                <div class="flex-1 mr-2">
                    <label>Invoice Date: </label>
                    <input class="w-full form-control form-input form-input-bordered" type="date" v-model='qbCustomerInvoiceDate'>
                </div>
                <div class="flex-1  mr-2">
                    <label>Due Date: </label>
                    <input
                        class="w-full form-control form-input form-input-bordered"
                        type="date"
                        v-model="qbCustomerInvoiceDueDate"
                    />
                </div>
            </div>
            <div class="flex my-5">
                <div class="flex-1">
                    <label>Services: </label>
                    <services-section
                        :resourceId="resourceId"
                        @valueChanged="onItemSectionChange"
                        :initServiceSectionValue="initServiceSectionValue"
                    ></services-section>
                </div>
            </div>
            <div class="flex mt-6">
                <div class="flex-1">
                    <button
                        :disabled="saving"
                        type="button"
                        ref="addInvoiceSaveButton"
                        data-testid="add-save-button"
                        dusk="add-save-add-invoice-button"
                        @click.prevent="handleSaveInvoice"
                        class="btn-default btn-save-invoice float-right"
                        style="border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                    >{{saving ? "Saving...":"Save"}}</button>
                    <button
                        type="button"
                        @click.prevent="closeInvoiceModal"
                        class="btn-default btn-close-modal"
                        style="border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                    >Cancel</button>
                </div>
            </div>
        </div>
        <div v-else class="p-8 dx-viewport container" style="height: 80%; width: 100%;">
            <center>
                <h4>Loading neccessary data...</h4>
            </center>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import ServicesGrid from './ServicesGrid';
import ServicesSection from './ServicesSection';
import ItemsGrid from './ItemsGrid';
import JQuery from 'jquery';
import CustomerSearchBox from './CustomerSearchBox.vue';
import vSelect from 'vue-select';
window.$ = JQuery

export default {
    name: "EditInvoiceModal",
    components: {
        ServicesGrid,
        ItemsGrid,
        CustomerSearchBox,
        vSelect,
        ServicesGrid,
        ServicesSection
    },
    props: ['terms', 'resourceId','shiflRef','selectedInvoice'],
    data() {
        return {
            //services: [],
            events: [],
            //items: [],
            viewLines: false,
            //errors: [],
            selectedCustomer:[],
            customerDetails:{},
            termValue:[],
            qbCustomerId:0,
            qbCustomerName:'',
            qbTermId:0,
            qbTermName:'',
            qbTermDueDays:0,
            qbCustomerBillingAddress:'',
            qbMemo: this.shiflRef ? "ShiflRef# "+this.shiflRef : '',
            qbCustomerInvoiceDate:'',
            qbCustomerInvoiceDueDate:'',
            qbInvoiceNumber:'',
            serviceItems:[],
            saving:false,
            //edit data
            invoiceLoaded: false,
            initCustomerValue:[],
            initServiceSectionValue:[],
            qboInvoiceId:0,
            shiflInvoiceId:0,
            qbCustomerEmail:'',
        };
    },
    created() {
        if(this.selectedInvoice > 0){
            this.invoiceLoaded = false;
            axios.get("/custom/invoice/by-id/"+this.selectedInvoice)
            .then(response => {
                const result = response.data.results[0];
                console.log(result)
                if(Object.keys(result).length > 0 ){
                    this.invoiceData = result;

                   //set Initial Modal Value
                    this.initCustomerValue = [{"DisplayName":result.qbo_customer_name,"Id":result.qbo_customer_id}];
                    this.initServiceSectionValue = result.invoice_items;
                    this.termValue = [{"Name":result.qbo_term_name,"Id":result.qbo_term_id,"DueDays":result.qbo_term_days}]

                    this.qbCustomerId = result.qbo_customer_id,
                    this.qbTermId = result.qbo_term_id;
                    this.qbTermName = result.qbo_term_name;
                    this.qbTermDueDays = result.qbo_term_days;
                    this.qbInvoiceNumber = result.qbo_invoice_num;
                    this.qbCustomerName = result.qbo_customer_name;
                    this.qbCustomerEmail = result.qbo_customer_email;
                    this.qbCustomerInvoiceDueDate = result.qbo_due_date;
                    this.qbCustomerInvoiceDate = result.qbo_invoice_date;
                    this.qbCustomerBillingAddress = result.qbo_billing_address;
                    this.qbMemo = result.qbo_memo;
                    this.qboInvoiceId = result.qbo_invoice_id;
                    this.shiflInvoiceId = result.id;

                    this.invoiceLoaded = true;
                }
            })
            .catch(e => {
                this.invoiceLoaded = true;
                console.log(e);
            });
        }
    },
    beforeMount() {
        //console.log(this.customers);
    },
    methods: {
        handleClose() {
            this.$emit("close");
        },
        onClickGrid (value) {
            //console.log(value) // someValue
            this.gridValues = value
        },
        onItemSectionChange (value){
            this.serviceItems = value;
            //console.log(this.serviceItems)
        },
        onCustomerSelect(value) {
            if(value){
                this.qbCustomerId = value.Id;
                this.selectedCustomer = value;
                this.qbCustomerName = value.DisplayName;
                this.qbCustomerEmail = value.CustomerEmail;
                this.consCustomerBillingAddr(value);
            }
        },
        consCustomerBillingAddr(customerDetails){
            //console.log(customerDetails);
            let invoiceAddr = customerDetails.InvoiceAddr ? customerDetails.InvoiceAddr : {};

            let invoiceerName = `${customerDetails.GivenName ? customerDetails.GivenName : ''} ${customerDetails.FamilyName ? customerDetails.FamilyName + `\n` : ''}`;
            let companyName = `${customerDetails.CompanyName +`\n`}`
            let line1 = `${invoiceAddr.Line1 ? invoiceAddr.Line1 + `\n`: ''}`;
            let city = `${invoiceAddr.City ? invoiceAddr.City + `, ` : ''}`;
            let country = `${invoiceAddr.Country ? invoiceAddr.Country + ` ` : (invoiceAddr.CountrySubDivisionCode ? invoiceAddr.CountrySubDivisionCode + ` ` : '') }`;
            let postal = `${invoiceAddr.PostalCode ? invoiceAddr.PostalCode : '' }`;

            this.qbCustomerBillingAddress = `${invoiceerName}${companyName}${line1}${city}${country}${postal}`;
        },
        onTermSelect(value){
            this.termValue = value;
            this.qbTermId= value.Id;
            this.qbTermName = value.Name;
            this.qbTermDueDays= value.DueDays;
        },
        addDays(date,days){
            const result = new Date(date);
            result.setDate(result.getDate() + parseInt(days));
            return result;
        },
        handleSaveInvoice() {
            this.saving = true;
            let filteredServiceItems = _.reject(this.serviceItems, function(o){return _.isNull(o.Service && o.Customer)});
            let data = {
                qboInvoiceId: this.qboInvoiceId,
                shiflInvoiceId: this.shiflInvoiceId,
                customerId: this.qbCustomerId,
                //line: this.gridValues,
                line: filteredServiceItems,
                billingAddress: this.qbCustomerBillingAddress,
                termId: this.qbTermId,
                termName: this.qbTermName,
                termDueDays: this.qbTermDueDays,
                invoiceNum: this.qbInvoiceNumber,
                customerName: this.qbCustomerName,
                dueDate: this.qbCustomerInvoiceDueDate,
                invoiceDate: this.qbCustomerInvoiceDate,
                memo: this.qbMemo,
                resourceId : this.resourceId,
                customerEmail: this.qbCustomerEmail,
            }
            console.log(data)

            // axios.post("/custom/qbo/invoice/edit",{
            //     qboInvoiceId: this.qboInvoiceId,
            //     shiflInvoiceId: this.shiflInvoiceId,
            //     customerId: this.qbCustomerId,
            //     //line: this.gridValues,
            //     line: filteredServiceItems,
            //     billingAddress: this.qbCustomerBillingAddress,
            //     termId: this.qbTermId,
            //     termName: this.qbTermName,
            //     termDueDays: this.qbTermDueDays,
            //     invoiceNum: this.qbInvoiceNumber,
            //     customerName: this.qbCustomerName,
            //     dueDate: this.qbCustomerInvoiceDueDate,
            //     invoiceDate: this.qbCustomerInvoiceDate,
            //     memo: this.qbMemo,
            //     resourceId : this.resourceId,
            //     customerEmail: this.qbCustomerEmail,
            // })
            // .then(response =>{
            //     this.viewLines = true;
            //     //console.log(response.data);
            //     if (response.data.success === true) {
            //         //console.log(response.data);
            //         Nova.success('Invoice Saved Successfully!');
            //         setTimeout(()=>{ this.$emit('close')},500)
            //         this.saving = false;
            //     }else{
            //         Nova.error(response.data);
            //         this.saving = false;
            //         console.log(response.data);
            //     }

            // })
            // .catch( e => {
            //     this.saving = false;
            //     console.log(e)
            //      Nova.error(e);
            // });
        },
        // changeStyle() {
        //     $('#meModal > div:nth-child(1)').css('height' , '100%');
        //     $('#meModal > div:nth-child(1) > div:nth-child(1)').css('width' , '60%');
        // },
        // getAccessToken() {
        //     return localStorage.getItem('token');
        // },
        // mapCustomers() {
        //     const obj = {}

        //     for(let  i = 0 ; i < customers.length; i++){
        //         let name = customers[i].ID
        //         obj[customers[i].ID]=customers[i].Name;
        //     }
        //     var customerValues = this.extractValues(obj);
        // }
        closeInvoiceModal(){
            this.$emit('close');
        }
    },

    watch: {
        qbTermDueDays: function(newVal,oldVal){
            if(this.qbCustomerInvoiceDate){
                let newDate = this.addDays(this.qbCustomerInvoiceDate,newVal);
                //console.log(newDate);
                this.qbCustomerInvoiceDueDate = newDate.getFullYear() + '-' + ("0" + (newDate.getMonth() + 1)).slice(-2) + '-' + ("0" + newDate.getDate()).slice(-2);
            }
        },
        qbCustomerInvoiceDate: function(newVal,oldVal){
            let newDate = this.addDays(newVal,this.qbTermDueDays);
            //console.log(newDate);
            this.qbCustomerInvoiceDueDate = newDate.getFullYear() + '-' + ("0" + (newDate.getMonth() + 1)).slice(-2) + '-' +("0" + newDate.getDate()).slice(-2);
        },
    },

    mounted() {

    }
};

</script>

<style scoped>
    .input-bordered {
        border-width: 1px;
        border-color: black;
        width: auto;
    }

    .fiftyleft {
        float: left;
        /* width: 39% */
        font-size: 12px;
    }

    .fiftyright {
        float: right;
       /*  width: 39% */
       font-size: 12px;
    }
    .btn-save-invoice{
        background-color:#16A34A;
        color:#ffffff !important;
    }
    .btn-save-invoice:hover{
        background-color:#15803D !important;
    }
    .btn-close-modal{
        background-color:#71717A !important;
    color:#ffffff !important;
    }
    .btn-close-modal:hover{
        background-color:#52525B !important;
    }

</style>
