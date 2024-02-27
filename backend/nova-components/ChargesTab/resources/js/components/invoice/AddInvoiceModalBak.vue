<template>
    <div>
        <div v-if="qboCompanyInfo.CompanyName !== null && qboCompanyInfo.Country !== null"  class="add-invoice-modal-container p-8 dx-viewport container" style="max-height: 80vh; width: 100%; overflow-y:auto">
            <div class="flex">
                <div class="flex-1 mr-2" style="width:30%!impotant">
                    <label>QB Customer: </label>
                    <customer-search-box
                        :resourceId="resourceId"
                        @changed="onCustomerSelect"
                        :initCustomerValue="qboCustomerInfo.realm_id === $store.state.chargesTab.qboCompanyInfo.realm_id ? qboCustomerInfo : []"
                        :isAddInvoice="true"
                    ></customer-search-box>
                </div>
                <!-- <div class="flex-1 mr-2">
                    <label>Allow Card Payment:</label>
                    <div class="flex">
                        <allow-credit-card-toggle
                        class="mr-2"
                        @setIsActiveState="handleAllowCreditCard"
                        :defaultValue="allowCreditCard"
                        />
                        <img src="/images/svg-icons/visa.svg" class="payment-icon mr-1"/>
                        <img src="/images/svg-icons/mastercard.svg" class="payment-icon mr-1"/>
                        <img src="/images/svg-icons/discover.svg" class="payment-icon mr-1"/>
                        <img src="/images/svg-icons/amex.svg" class="payment-icon mr-1"/>
                        <img src="/images/svg-icons/applepay.svg" class="payment-icon mr-1"/>
                    </div>
                </div> -->
                <div class="flex-1">
                    <label>Allow Bank Transfer Payment:</label>
                    <div class="flex">
                        <allow-bank-toggle
                        class="mr-1"
                        @setIsActiveState="handleAllowACHPayment"
                        :defaultValue="allowACHPayment"
                        />
                        <img src="/images/svg-icons/bank.svg" class="payment-icon mr-1"/>
                    </div>
                </div>
                <div class="flex-1 mr-2"></div>
            </div>
            <div class="flex my-3" v-if="exchangeRateValue !== null">
                <div class="mr-2 w-40">
                    <div style="white-space:nowrap" class="italic">
                        <span>1 {{exchangeRateValue.SourceCurrencyCode}}</span>
                        <img :src="'/images/svg-icons/flag_'+exchangeRateValue.SourceCurrencyCode.toLowerCase()+'.svg'" alt="flag" class="currency-flag-icon mr-1"/>
                        <span> = </span>
                        <input style="border-radius:0px;" class="w-full form-control form-input form-control-sm  form-input-bordered" type="email" v-model='exchangeRateValue.Rate'>
                        <span> {{exchangeRateValue.TargetCurrencyCode}}</span>
                        <img :src="'/images/svg-icons/flag_'+exchangeRateValue.TargetCurrencyCode.toLowerCase()+'.svg'" class="currency-flag-icon mr-1"/>
                        <span> as of {{exchangeRateValue.AsOfDate}}</span>
                    </div>
                </div>
            </div>
            <div class="flex my-3" v-if="exchangeRateError === true">
                <div class="mr-2 w-40">
                    <div style="white-space:nowrap">
                        <span class="text-danger italic">Failed to get exchange rate, Please refesh page.</span>
                    </div>
                </div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label>Customer Email:</label>
                    <input style="border-radius:0px;" class="w-full form-control form-input form-control-sm  form-input-bordered" type="email" v-model='qbCustomerEmail'>
                    <span class="italic text-sm">If multiple emails, separate by comma</span>
                </div>
                <div class="flex-1 mr-2">
                    <label>Invoice #:</label>
                    <input style="border-radius:0px;" class="w-full form-control form-input form-control-sm  form-input-bordered" type="text" v-model='qbInvoiceNumber'>
                </div>
                <div class="flex-1 mr-2">
                    <label>Billing Address: </label>
                    <textarea rows="2" style="height:auto;border-radius:0px;" class="w-full form-control form-control-sm form-input form-input-bordered" v-model='qbCustomerBillingAddress'></textarea>
                </div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label>Terms: </label>
                    <v-select
                        class="termSelect"
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
                    <input style="border-radius:0px;" class="w-full form-control form-control-sm form-input form-input-bordered" type="date" v-model='qbCustomerInvoiceDate'>
                </div>
                <div class="flex-1  mr-2">
                    <label>Due Date: </label>
                    <input
                        class="w-full form-control form-control-sm form-input form-input-bordered"
                        type="date"
                        v-model="qbCustomerInvoiceDueDate"
                        style="border-radius:0px;"
                    />
                </div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label>Notes: </label>
                    <textarea rows="2" style="height:auto;border-radius:0px;" class="w-full form-control form-control-sm form-input form-input-bordered" v-model='qbCustomerMemo'></textarea>
                </div>

                <div class="flex-1 mr-2">
                    <div class="flex flex-row items-center">
                        <div class="checkbox-wrapper">
                            <label :class="`relative ${autoInvoiceDateUpdate ? 'checked': ''}`">
                                <generic-icon :marginLeft="0" :iconName="`${(autoInvoiceDateUpdate) ? 'checked' : 'not-checked'}`"></generic-icon>
                                <input class="absolute opacity-0" @click.prevent="toggleAutoInvoiceDate" type="checkbox" :checked="autoInvoiceDateUpdate" />
                                <span class="pl-12 ml-2" style="color: #4A4A4A;">Invoice date update automatically</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex-1 mr-2"></div>
            </div>
            <hr>
            <div class="flex my-6">
                <div class="flex-1 my-2">
                    <label>Services: </label>
                    <div v-if="qboCompanyInfo.Country === 'IN'">
                        <india-services-section
                            :resourceId="resourceId"
                            @valueChanged="onItemSectionChange"
                            :initServiceSectionValue="initServiceSectionValue"
                            :exchangeRateValue="exchangeRateValue"
                        />
                    </div>
                    <!-- <div v-else-if="qboCompanyInfo.Country === 'US'"> -->
                    <div v-else>
                        <services-section
                            :resourceId="resourceId"
                            @valueChanged="onItemSectionChange"
                            :initServiceSectionValue="initServiceSectionValue"
                            :exchangeRateValue="exchangeRateValue"
                        ></services-section>
                    </div>
                    <!-- <div v-else>
                        <center>Loading components...</center>
                    </div> -->
                </div>
            </div>
            <hr>
            <div class="flex my-6">
                <div class="flex-1 mr-2">
                    <label>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-6 paper-clip" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        Attachements:
                    </label>
                    <drag-n-drop-input
                        :buttonState="buttonState"
                        :componentIndex="1"
                        :shipmentDocs="shipmentDocs"
                        @getAttachements="getAttachements"
                    />
                </div>
                <div class="flex-1 mr-2">
                    <label>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-6 paper-clip" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        Existing Files:
                    </label>
                    <div v-if="shipmentDocs.length > 0">
                        <div v-if="showExisting === true">
                            <transition-group class="existing-file-lists" name="fade" tag="ul" v-for="(docs,i) in shipmentDocs" :key="i">
                                <li v-bind:key="i"><span @click.prevent="onRemoveFromExistingFiles(i)" class="mr-1 remove-existing-file">x </span>{{docs.substring(docs.lastIndexOf("/")+1)}}</li>
                            </transition-group> 
                        </div>
                        <center class="mt-2">
                            <span @click.prevent="onShowExistingFiles" class="show-file-toggle">
                                {{showExisting ? "Hide" : "Show"}} Existing Files
                            </span>
                        </center>
                    </div>
                </div>
            </div>
            <hr>
            <div class="flex mt-6">
                <div class="flex-1">
                    <div class="float-right">
                        <button
                            :disabled="disableSaveBtn"
                            type="button"
                            ref="addInvoiceSaveButton"
                            data-testid="add-save-button"
                            dusk="add-save-add-invoice-button"
                            @click.prevent="onSaveInvoice(false)"
                            class="btn-default btn-save-invoice"
                            style="border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                        >{{saveBtnText}}</button>
                    </div>
                    <div class="float-right flex mr-2">
                        <div>  
                            <button
                                :disabled="disableSaveNSendBtn"
                                type="button"
                                ref="saveNSendInvoiceButton"
                                @click.prevent="onSaveInvoice(true)"
                                class="btn-default btn-send-invoice"
                                style="border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                            >{{saveNSendBtnText}}
                            </button>
                        </div>
                    </div>
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
import { mapGetters, mapActions } from 'vuex';
import axios from 'axios';
import ServicesSection from './ServicesSection';
import CustomerSearchBox from './CustomerSearchBox.vue';
import vSelect from 'vue-select';
import DragNDropInput from './DragNDropInput.vue';
// import AllowCreditCardToggle from './AllowCreditCardToggle.vue';
import AllowBankToggle from './AllowBankToggle';
import IndiaServicesSection from '../india-invoice/IndiaServicesSection';
import GenericIcon from '../Icons/GenericIcon';

const CURRENCY = {
    US: "USD",
    CN: "CNY",
    IN: "INR"
}

export default {
    name: "AddInvoiceModalBak",
    components: {
        CustomerSearchBox,
        vSelect,
        ServicesSection,
        DragNDropInput,
        // AllowCreditCardToggle,
        AllowBankToggle,
        GenericIcon,
        IndiaServicesSection
    },
    computed: {
        ...mapGetters({
            salesRepresentative: 'customer/getSalesRepresentative',
            defaultCustomer: 'customer/getDefaultCustomer'
        })
    },
    props: ['terms', 'resourceId', 'shiflRef', 'qboCompanyInfo','shipmentInfo', 'qboCustomerInfo'],
    data:() => ({
        autoInvoiceDateUpdate: true,
        customerDetails:{},
        termValue:[],
        qbCustomerId:0,
        qbCustomerName:'',
        qbTermId:0,
        qbTermName:'',
        qbTermDueDays:0,
        qbCustomerInvoiceDate:'',
        qbCustomerInvoiceDueDate:'',
        qbInvoiceNumber:'',
        serviceItems:[],
        initServiceSectionValue:[],
        qbCustomerEmail:'',
        qbCustomerBillingAddress:'',
        qbCustomerMemo:'',

        //START>>>>>>>>>>>DragNDrop
        buttonState: {
            isLoading: false,
            isShow: false
        },
        attachments: [],
        shipmentDocs: [],
        showExisting:false,
        //END>>>>>>>>>>>DragNDrop

        saveBtnText: 'Save',
        sending:false,
        saveNSendToggled:false,
        saveNSendBtnText:'Save and Send',
        disableSaveNSendBtn:false,
        disableSaveBtn:false,

        // allowCreditCard:true,
        allowACHPayment:true,
        qbCustomerGSTIN:null,

        customerCurrencyRef:null,
        exchangeRateValue:null,
        exchangeRateError:false,
        common_errors: [
            {
                error: 'String length is either shorter or longer than supported by specification',
                alias: 'Invoice number must be at least 8 characters long and not more than 21 characters'
            },
            {
                error: 'String length specified does not match the supported length. Min:0 Max:21 supported. Supplied length:33',
                alias: 'Invoice number must be at least 8 characters long and not more than 21 characters'
            },
            {
                error: 'Required param missing, need to supply the required value for the API',
                alias: 'Unable to connect to API'
            },
            {
                error: 'Required parameter Line is missing in the request',
                alias: 'Unable to connect to API'
            }
        ]
    }
    ),
    created() {
        axios.get("/custom/shipment/get-for-invoice?id="+this.resourceId)
        .then(response => {
            const result = response.data.result;
            if(result){
                let mbl_num = result.mbl_num ? result.mbl_num : '';
                let po_nums = result.po_numbers ? result.po_numbers : '';
                let containers = result.container_numbers ? result.container_numbers : '';
                let etd = result.etd ? moment.utc(result.etd).format('MM/DD/YYYY') : '';
                let eta = result.eta ? moment.utc(result.eta).format('MM/DD/YYYY') : '';
                let location_from = result.location_from ? result.location_from : '';
                let location_to = result.location_to ? result.location_to : '';
                let suppliers = result.suppliers.map(function(o){
                    return `${o.name}/${o.cartons}ctns\n`;
                });
                
                this.qbCustomerMemo = 
                    `MBL#: ${mbl_num}\n`+
                    `PO#: ${po_nums}\n`+
                    `From: ${location_from}\n`+
                    `To: ${location_to}\n`+
                    `ETD: ${etd}\n`+
                    `ETA: ${eta}\n`+                    
                    `Containers: ${containers}\n`+
                    `Suppliers:\n${suppliers.join('')}`
                ;
            }  
        })
        .catch(e => {
            console.log(e)
        });

        this.getShipmentDocuments();
        this.getShipmentCommecialDocuments();
        this.autoGenerateInvoiceNumber();
        //this.setDefaultCustomerValue();
        this.getUpdatedCustomerEmail();
    },
    beforeMount() {
        //
    },
    methods: {
        toggleAutoInvoiceDate() {
            this.autoInvoiceDateUpdate = !this.autoInvoiceDateUpdate
        },
        handleClose() {
            this.$emit("close");
        },
        onClickGrid (value) {
            this.gridValues = value
        },
        onItemSectionChange (value){
            this.serviceItems = value;
        },
        onCustomerSelect(value) {
            if(value){
                
                this.qbCustomerId = value.Id;
                this.qbCustomerName = value.FullyQualifiedName;
                
                if(value.CurrencyRef){
                    this.customerCurrencyRef = value.CurrencyRef;
                    this.getExchangeRate(value.CurrencyRef);
                }
                if(value.BillAddr){
                    this.consCustomerBillingAddr(value);
                }else{
                    this.qbCustomerBillingAddress = value.DisplayName;
                }
                if(value.PrimaryEmailAddr){
                    let email = value.PrimaryEmailAddr.Address;


                    let final_email = email
                    if ( this.salesRepresentative!=null && this.defaultCustomer!==null && value.Id == parseInt(this.defaultCustomer.qbo_id)) {
                        final_email += ',' + this.salesRepresentative.email
                    }
                    this.qbCustomerEmail = final_email;
                }else{
                    this.qbCustomerEmail = '';
                }
                if(value.GSTIN){
                    this.qbCustomerGSTIN = value.GSTIN;
                }
            }
        },
        consCustomerBillingAddr(customerDetails){
            let billAddr = customerDetails.BillAddr ? customerDetails.BillAddr : {};

            let billerName = `${customerDetails.GivenName ? customerDetails.GivenName : ''} ${customerDetails.FamilyName ? customerDetails.FamilyName + `\n` : ''}`;
            let companyName = `${customerDetails.CompanyName ? customerDetails.CompanyName +`\n` : ''}`
            let line1 = `${billAddr.Line1 ? billAddr.Line1 + `\n`: ''}`;
            let city = `${billAddr.City ? billAddr.City + `, ` : ''}`;
            let country = `${billAddr.Country ? billAddr.Country + ` ` : (billAddr.CountrySubDivisionCode ? billAddr.CountrySubDivisionCode + ` ` : '') }`;
            let postal = `${billAddr.PostalCode ? billAddr.PostalCode : '' }`;

            this.qbCustomerBillingAddress = `${billerName}${companyName}${line1}${city}${country}${postal}`;
        },
        onTermSelect(value){
            this.termValue = value;
            this.qbTermId= value.Id;
            this.qbTermName = value.Name;
            this.qbTermDueDays= value.DueDays;
            let buildDate = moment(this.qbCustomerInvoiceDate, "YYYY-MM-DD").add(parseInt(this.qbTermDueDays),'days')
            buildDate = buildDate.format('YYYY-MM-DD')
            this.qbCustomerInvoiceDueDate = buildDate

        },
        addDays(date,days){
            const result = new Date(date);
            result.setDate(result.getDate() + parseInt(days));
            return result;
        },

        handleUploadAttachment: function(qbo_invoice_id,invoice_id,attachment,isSaveNSend){
            const formData = new FormData();
            formData.append('qbo_invoice_id',qbo_invoice_id);
            formData.append('invoice_id',invoice_id);
            formData.append('shipment_id',this.resourceId);

            if(this.shipmentDocs.length > 0){
                let i = 0;
                for(const docs of this.shipmentDocs) {
                    formData.append("shipment_docs["+i+"]",docs);
                    i++;
                };
            }
            
            if(this.attachments.files && this.attachments.files.length > 0){
                let i = 0;
                for(const file of this.attachments.files) {
                    formData.append("file["+i+"]",file);
                    i++;
                };
            }
            if((this.attachments.files && this.attachments.files.length > 0) || (this.shipmentDocs.length > 0)){
                if(isSaveNSend){
                    this.saveNSendBtnText = "Uploading Attachment...";
                }else{
                    this.saveBtnText = "Uploading Attachment...";
                }
                axios.post("/custom/qbo/attachable/upload",
                    formData,
                    { headers: {"Content-Type": "multipart/form-data"} }
                )
                .then( response =>{
                    if (response.data.success === true) {
                        Nova.success('Attachment uploaded successfully!');
                        if(isSaveNSend){
                            this.sendInvoiceByQBO(invoice_id);
                        }else{
                            // this.$emit('updateInvoicesTable');
                            // setTimeout(()=>{ this.$emit('close')},500)
                            this.updateInvoiceTable(this.resourceId);
                            setTimeout(()=>{ this.$emit('closeAddInvoiceModal')},500);
                        }
                    }else{
                        this.saveNSendBtnText = "Save and Send";
                        this.disableSaveBtn = false;
                        this.saveBtnText = "Save";
                        this.disableSaveNSendBtn = false;
                        Nova.error("Failed to upload file(s). Please check if it exists.");
                        console.log(response.data);
                    }
                })
                .catch(e =>{
                    
                    if( e ) Nova.error(e);
                    
                    this.saveBtnText = "Save";
                }); 
            }
            else{
                this.saveBtnText = "Save";
                setTimeout(()=>{ this.$emit('close')},500) 
            }      
        },
        getShipmentDocuments: function(){
            axios.get('/custom/qbo/attachable/get-shipment-documents/'+this.resourceId)
            .then( response =>{
                if (response.data.success === true) {
                    const result = response.data.data;
                    const attachable = [];
                    result.map(function(o){
                        attachable.push(o.commercial_documents,o.commercial_invoice,o.packing_list,o.hbl_copy);
                    });
                    const filteredA = _.filter(attachable,null);
                    this.shipmentDocs.push(...filteredA);
                }
            })
            .catch(e =>{
                if( e ) Nova.error(e);
            });
        },

        getShipmentCommecialDocuments: function(){
            axios.get('/custom/qbo/attachable/get-shipment-commercial-documents/'+this.resourceId)
            .then( response =>{
                if (response.data.success === true) {
                    const result = response.data.data;
                    const attachable = [];
                    if(result.commercial_docs.length > 0){
                        result.commercial_docs.map(function(o){
                            attachable.push(o.file);
                        });
                    }
                    if(result.netchb_pdf !== null){
                        attachable.push(result.netchb_pdf);
                    }
                    this.shipmentDocs.push(...attachable);
                }
            })
            .catch(e =>{
                if( e ) Nova.error(e);
            });
        },

        autoGenerateInvoiceNumber: function(){
            axios.get('/custom/invoice/generate-invoice-num/'+this.resourceId)
            .then( response =>{
                if (response.data.success === true) {
                    const result = response.data.results;
                    this.qbInvoiceNumber = result;
                }
            })
            .catch(e =>{
                if( e ) Nova.error(e);
            });
        },

        closeInvoiceModal(){
            this.$emit('closeAddInvoiceModal');
        },

        getAttachements: function(value) {
            this.attachments = value;
        },

        onShowExistingFiles: function(){
            if(this.showExisting){
                this.showExisting = false;
            }else{
                this.showExisting = true;
            }
        },

        onRemoveFromExistingFiles: function(index){
            this.$delete(this.shipmentDocs,index)
        },

        // handleAllowCreditCard: function(e){
        //     this.allowCreditCard = e;
        // },

        handleAllowACHPayment: function(e){
            this.allowACHPayment = e;
        },

        onSaveInvoice: function(isSaveNSend){

            if(isSaveNSend){
                this.saveNSendBtnText = "Saving...";
                this.disableSaveBtn = true;
            }else{
                this.saveBtnText = "Saving...";
                this.disableSaveNSendBtn = true;
            }

            let filteredServiceItems = _.reject(this.serviceItems, function(o){return _.isNull(o.Service)});
                    
            axios.post("/custom/qbo/invoice/add",{
                customerId: this.qbCustomerId,
                line: filteredServiceItems,
                auto_invoice_date_update: this.autoInvoiceDateUpdate ? 1 : 0,
                billingAddress: this.qbCustomerBillingAddress,
                termId: this.qbTermId,
                termName: this.qbTermName,
                termDueDays: this.qbTermDueDays,
                invoiceNum: this.qbInvoiceNumber,
                customerName: this.qbCustomerName,
                dueDate: this.qbCustomerInvoiceDueDate,
                invoiceDate: this.qbCustomerInvoiceDate,
                resourceId : this.resourceId,
                customerEmail: this.qbCustomerEmail,
                customerMemo : this.qbCustomerMemo,
                // allowCreditCard: this.allowCreditCard,
                allowACHPayment: this.allowACHPayment,
                qboCompanyInfo: this.qboCompanyInfo,
                shipmentInfo: this.shipmentInfo,
                customerGSTIN: this.qbCustomerGSTIN,
                customerCurrencyRef:this.customerCurrencyRef,
                exchangeRateValue:this.exchangeRateValue
            })
            .then(response =>{

                if (response.data.success === true) {
                    Nova.success('Invoice Saved Successfully!');
                    this.$emit('updateInvoicesTable');
                    if((this.attachments.files && this.attachments.files.length > 0) || (this.shipmentDocs.length > 0)){
                        this.handleUploadAttachment(response.data.qbo_invoice_id, response.data.invoice_id, this.attachments, isSaveNSend);
                    }else{
                        if(isSaveNSend){
                           this.sendInvoiceByQBO(response.data.invoice_id);
                        }else{
                            //this.$emit('updateInvoicesTable');
                            this.updateInvoiceTable(this.resourceId);
                            setTimeout(()=>{ this.$emit('closeAddInvoiceModal')},500);
                            this.saveBtnText = "Save"
                        }
                    }
                }else{

                    let e = response.data;

                    if( e.indexOf('<?xml') != -1 ){

                        let parser = new DOMParser();
                        let xml = parser.parseFromString(e,"text/xml");

                        if( this.common_errors.findIndex( i => i.mark == 0 ) == -1 ){
                            this.common_errors = this.common_errors.map( i =>{
                                i.mark = 0;

                                return i;
                            });
                        }

                        for( var i = 0; i < xml.getElementsByTagName("Error").length; i++ ){
                            var q = this.common_errors.findIndex( item => item.error === xml.getElementsByTagName("Error")[i].querySelector('Message').textContent );

                            if( q != -1 ){
                                Nova.error(this.common_errors[q].alias);
                            }

                            Nova.error(xml.getElementsByTagName("Error")[i].querySelector('Detail').textContent);
                        }

                    }else{
                        Nova.error(e);
                    }

                    if(isSaveNSend){
                        this.saveNSendBtnText = "Save and Send";
                        this.disableSaveBtn = false;
                    }else{
                        this.saveBtnText = "Save";
                        this.disableSaveNSendBtn =false;
                    }
                }
            })
            .catch( e => {
                if( e ) Nova.error(e);
            });
        },

        sendInvoiceByQBO: function(invoice_id){
            this.saveNSendBtnText = "Sending...";
            const formData = new FormData();
            formData.append('invoice_id',invoice_id);
            formData.append('customer_emails',this.qbCustomerEmail);
            formData.append('auto_invoice_date_update', this.autoInvoiceDateUpdate ? 1 : 0)
            axios.post("/custom/qbo/invoice/send-invoice",formData)
            .then( response =>{
                if (response.data.success === true) {
                    Nova.success('Invoice sent successfully.');
                    // this.$emit('updateInvoicesTable');
                    // setTimeout(()=>{ this.$emit('close')},500);
                    this.updateInvoiceTable(this.resourceId);
                    setTimeout(()=>{ this.$emit('closeAddInvoiceModal')},500);
                    this.saveNSendBtnText = "Save and Send";
                }else{

                    if ( typeof response.data!=='undefined' && typeof response.data.errorMessage!=='undefined' ) {
                        Nova.error(response.data.errorMessage);
                    } else {
                        Nova.error('Failed to send invoice.');    
                    }
                    
                    //this.$emit('updateInvoicesTable');
                    this.updateInvoiceTable(this.resourceId);
                    this.saveNSendBtnText = "Save and Send";
                    this.disableSaveBtn = false;
                    this.saveBtnText = "Save";
                    this.disableSaveNSendBtn = false;
                }
            })
            .catch(e =>{
                if( e ) Nova.error(e);
            });
        },

        setDefaultCustomerValue: function(){
            if(this.qboCustomerInfo){
                const cust = this.qboCustomerInfo;
                this.qbCustomerId = cust.Id;
                this.qbCustomerName = cust.FullyQualifiedName;
                
                if(cust.BillAddr){
                    this.consCustomerBillingAddr(cust);
                }else{
                    this.qbCustomerBillingAddress = cust.DisplayName;
                }
                if(cust.PrimaryEmailAddr){
                    let email = cust.PrimaryEmailAddr.Address;

                    let final_email = email
                    if ( this.salesRepresentative!=null ) {
                        final_email += ',' + this.salesRepresentative.email
                    }

                    this.qbCustomerEmail = final_email;
                }else{
                    this.qbCustomerEmail = '';
                }
                if(cust.GSTIN){
                    this.qbCustomerGSTIN = value.GSTIN;
                }
            }
        },

        updateInvoiceTable: function(){
            this.$store.dispatch('fetchShipmentInvoices', this.resourceId)
        },

        getExchangeRate: function(customerCurrencyCode){
            console.log('customerCurrencyCode',customerCurrencyCode)
            this.exchangeRateValue = null;

            const qboCompanyInfo = this.qboCompanyInfo;
            const state = this.$store.state.chargesTab;
            const activeCompanyRealm = state.qboCompanyInfo.realm_id;
            const qboCompanies = state.qboCompanyAccounts;
            
            const activeCompany = _.find(qboCompanies,function(o){
                // return o.company_realm_id === activeCompanyRealm && o.country === qboCompanyInfo.Country;
                return o.company_realm_id === activeCompanyRealm;
            });
            let targetCurrencyCode = typeof activeCompany !== 'undefined' ? activeCompany.currency_code : '';

            // if(this.qboCompanyInfo.Country === "US"){
            //     // targetCurrencyCode = CURRENCY.US
            //     targetCurrencyCode = CURRENCY.CN
            // }else if(this.qboCompanyInfo.Country === "CN"){
            //     targetCurrencyCode = CURRENCY.CN
            // }else if(this.qboCompanyInfo.Country === "IN"){
            //     targetCurrencyCode = CURRENCY.IN
            // }
            
            if(targetCurrencyCode !== ''){
                axios.get('/custom/qbo/exchangerate/get-by-currency-codes?sourceCurrencyCode='+customerCurrencyCode+'&targetCurrencyCode='+targetCurrencyCode)
                .then(response => {
                    if(response.data.result !== null){
                        this.exchangeRateValue = response.data.result;
                    }
                })
                .catch(e => {
                    console.log(e);
                    this.exchangeRateError = true;
                })
            }else{
                //this.exchangeRateError = true;
            }
        },

        getUpdatedCustomerEmail(){
            if(this.qboCustomerInfo.customer.Id){
                axios.get('/custom/qbo/customer/get-udated-customer-email/'+this.qboCustomerInfo.customer.Id)
                .then(response => {
                    if(response.data.result !== null){
                        let customerEmails = response.data.result.PrimaryEmailAddr;

                        let final_email = customerEmails.Address;
                        if ( this.salesRepresentative!=null ) {
                            final_email += ',' + this.salesRepresentative.email
                        }
                        this.qbCustomerEmail = final_email;
                    }
                })
                .catch(e => {
                    console.log(e);
                })
            }else{
                //this.exchangeRateError = true;
            }
        }

    },

    watch: {
        /*
        qbTermDueDays: function(newVal,oldVal){
            this.qbCustomerInvoiceDueDate = moment(this.qbCustomerInvoiceDate).add(newVal,'d').format('YYYY-MM-DD');
        },
        qbCustomerInvoiceDate: function(newVal,oldVal){
            this.qbCustomerInvoiceDueDate = moment(newVal).add(parseInt(this.qbTermDueDays),'d').format('YYYY-MM-DD');
        },*/
        terms : function(newVal,oldVal){
            let termDefaultValue = _.filter(this.terms, function(c){ return parseInt(c.DueDays) === 0});
            this.termValue = termDefaultValue[0];
            this.qbTermDueDays = termDefaultValue[0].DueDays;
            this.qbTermId = termDefaultValue[0].Id;
            this.qbTermName = termDefaultValue[0].Name;
        }
    },

    mounted() {
        this.qbTermDueDays = 0;
        //this.qbCustomerInvoiceDate = moment.utc().format('YYYY-MM-DD');
        //this.qbCustomerInvoiceDueDate = moment.utc().format('YYYY-MM-DD');

        this.qbCustomerInvoiceDate = moment().format('YYYY-MM-DD');
        this.qbCustomerInvoiceDueDate = moment().format('YYYY-MM-DD');
        if(this.terms.length > 0){
            let termDefaultValue = _.filter(this.terms, function(c){ return parseInt(c.DueDays) === 0});
            this.termValue = termDefaultValue[0];
            this.qbTermDueDays = termDefaultValue[0].DueDays;
            this.qbTermId = termDefaultValue[0].Id;
            this.qbTermName = termDefaultValue[0].Name;
        }
    }
};

</script>

<style scoped>
    .checkbox-wrapper {
        padding: 0 !important;
        border: 0 solid transparent !important;
    }

    .checkbox-wrapper label {
        align-items: center;
        height: 16px!important;
        display: flex;
        justify-content: left;
        height: 40px;
        width: 100%;
        cursor: pointer;
    }

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
    .paper-clip{
        margin-bottom: -1px;
        color: #7c858e;
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
    .show-file-toggle{
        cursor: pointer;
        font-style: italic;
        color: #4099de;
    }
    .show-file-toggle:hover{
        cursor: pointer;
        font-style: italic;
        color: #297ec0;
    }
    .existing-file-lists{
        list-style-type: none;
    }
    .remove-existing-file{
        cursor: pointer;
        color: #bee3f8;
        font-weight: 900;
    }
    .remove-existing-file:hover{
        color: #297ec0;
    }
    hr {
        border: 0;
        clear:both;
        display:block;
        width: 100%;               
        background-color:#ebeef2;
        height: 1px;
    }
    .btn-send-invoice{
        background-color:#16A34A;
        color:#ffffff !important;
    }
    .btn-send-invoice:hover{
        background-color:#15803D !important;
    }
    .status-badge-container{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    button:disabled{
        cursor: not-allowed;
        background-color: #5DD87A;
    }
    .payment-icon{
        max-height: 28px;
    }
    .currency-flag-icon{
        max-height: 20px;
    }

</style>
