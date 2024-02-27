<template>
    <div>
        <div v-if="qboCompanyInfo !== null && invoiceLoaded === true && terms.length > 0" class="add-invoice-modal-container p-8 dx-viewport container" style="max-height: 80vh; width: 100%; overflow-y:auto">
        <!-- <div v-if="true" class="add-invoice-modal-container p-8 dx-viewport container" style="max-height: 80vh; width: 100%; overflow-y:auto"> -->
            <div class="flex">
                <div class="flex-1 mr-2" style="width:30%!impotant">
                    <label class="font-semibold">QB Customer: </label>
                    <customer-search-box
                        :resourceId="resourceId"
                        @changed="onCustomerSelect"
                        :initCustomerValue="initCustomerValue"
                        :isAddInvoice="false"
                    ></customer-search-box>
                </div>
                <!-- <div class="flex-1 mr-2">
                    <label class="font-semibold">Allow Card Payment:</label>
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
                    <label class="font-semibold">Allow Bank Transfer Payment:</label>
                    <div class="flex">
                        <allow-bank-toggle
                        class="mr-1"
                        @setIsActiveState="handleAllowACHPayment"
                        :defaultValue="allowACHPayment"
                        />
                        <img src="/images/svg-icons/bank.svg" class="payment-icon mr-1"/>
                    </div>
                </div>
                <div class="flex-1"></div>
            </div>
            <div class="flex my-3" v-if="exchangeRateValue !== null">
                <div class="mr-2 w-40">
                    <div style="white-space:nowrap">
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
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label class="font-semibold">Customer Email:</label>
                    <input style="border-radius:0px;" class="w-full form-control form-control-sm form-input form-input-bordered" type="email" v-model='qbCustomerEmail'>
                    <span class="italic text-sm">If multiple emails, separate by comma</span>   
                </div>
                <div class="flex-1 mr-2">
                    <label class="font-semibold">Invoice #:</label>
                    <input style="border-radius:0px;" class="w-full form-control form-control-sm  form-input form-input-bordered" type="text" v-model='qbInvoiceNumber'>
                </div>
                <div class="flex-1 mr-2">
                    <label class="font-semibold">Billing Address: </label>
                    <textarea rows="2" style="height:auto; border-radius:0px;" class="w-full form-control form-input form-input-bordered" v-model='qbCustomerBillingAddress'></textarea>
                </div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label class="font-semibold">Terms: </label>
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
                    <label class="font-semibold">Invoice Date: </label>
                    <input
                        style="border-radius:0px;"
                        class="w-full form-control form-control-sm  form-input form-input-bordered" 
                        type="date" 
                        v-model="qbCustomerInvoiceDate"
                    />
                </div>
                <div class="flex-1  mr-2">
                    <label class="font-semibold">Due Date: </label>
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
                    <label class="font-semibold">Notes: </label>
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
            <div class="flex my-5">
                <div class="flex-1">
                    <label class="font-bold">Services: </label>
                    <div v-if="qboCompanyInfo.Country === 'IN'">
                        <india-services-section
                            :resourceId="resourceId"
                            @valueChanged="onItemSectionChange"
                            :initServiceSectionValue="initServiceSectionValue"
                            :totalTax="totalTax"
                            :selectedInvoiceQBOId="selectedInvoiceQBOId"
                            :edit="true"
                            :exchangeRateValue="exchangeRateValue"
                        />
                    </div>
                   <!-- <div v-else-if="qboCompanyInfo.Country === 'US'"> -->
                    <div v-else>
                        <services-section
                            :resourceId="resourceId"
                            @valueChanged="onItemSectionChange"
                            :initServiceSectionValue="initServiceSectionValue"
                            :selectedInvoiceQBOId="selectedInvoiceQBOId"
                            :edit="true"
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
                    <label class="font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-6 paper-clip" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        Attachments:
                    </label>
                    <drag-n-drop-input
                        :buttonState="buttonState"
                        :componentIndex="1"
                        :shipmentDocs="shipmentDocs"
                        @getAttachements="getAttachements"
                    />
                </div>
                <div class="flex-1 mr-2">
                    <label class="font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-6 paper-clip" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        Existing Files:
                    </label>
                    <div v-if="existingFiles.length > 0">
                        <div v-if="showExisting === true">
                            <transition-group class="existing-file-lists" name="fade" tag="ul" v-for="(file,i) in existingFiles" :key="i">
                                <li v-bind:key="i"><span @click.prevent="onRemoveFromExistingFiles(i,file)" class="mr-1 remove-existing-file">x </span>{{file.file_name}}</li>
                            </transition-group> 
                        </div>
                        <center class="mt-2">
                            <span v-if="existingFiles.length > 0" @click.prevent="onShowExistingFiles" class="show-file-toggle">
                                {{showExisting ? "Hide" : "Show"}} Existing Files
                            </span>
                        </center>
                    </div>
                </div>
            </div>
            <hr>
            <div class="flex mt-6">
                <div class="flex-1">
                    <div v-if="selectedInvoiceRealmId === realmId" class="float-right">
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
                        <div v-if="isInvoiceSent" class="status-badge-container">
                            <label class="mr-2">Status: 
                                <span class="text-sm px-2 status-badge">Sent</span> 
                                <span v-if="invoiceLastDelivery !== null" class="text-xs" style="font-style:italic">
                                    Last Delivery: Sent by email at  {{formattedLastDelivery}}
                                </span>
                            </label>
                        </div>
                        <div v-if="selectedInvoiceRealmId === realmId">  
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
                    <div>
                        <button
                            type="button"
                            @click.prevent="closeInvoiceModal"
                            class="btn-default btn-close-modal"
                            style="border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                        >Cancel</button>
                    </div>
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
import ServicesSection from './ServicesSection';
import CustomerSearchBox from './CustomerSearchBox.vue';
import vSelect from 'vue-select';
import moment from 'moment-timezone';
import DragNDropInput from './DragNDropInput';
// import AllowCreditCardToggle from './AllowCreditCardToggle.vue';
import AllowBankToggle from './AllowBankToggle';
import IndiaServicesSection from '../india-invoice/IndiaServicesSection';
import GenericIcon from '../Icons/GenericIcon';

export default {
    name: "EditInvoiceModal",
    components: {
        CustomerSearchBox,
        vSelect,
        ServicesSection,
        DragNDropInput,
        // AllowCreditCardToggle,
        AllowBankToggle,
        IndiaServicesSection,
        GenericIcon
    },
    props: ['terms', 'resourceId', 'shiflRef', 'selectedInvoice','selectedInvoiceQBOId','qboCompanyInfo', 'realmId'],
    data:() => ({
        autoInvoiceDateUpdate: false,
        selectedCustomer:[],
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
        saving:false,
        qbCustomerEmail:'',
        qbCustomerBillingAddress:'',
        qbCustomerMemo:'',
        isInvoiceSent:false,
        invoiceLastDelivery:"",

        //edit Data 
        invoiceLoaded: false,
        initCustomerValue:[],
        shiflInvoiceId: '',
        qboInvoiceId:'',
        totalTax:null,

        //attachment field
        buttonState: {
            isLoading: false,
            isShow: false
        },
        attachments: [],
        shipmentDocs: [],
        existingFiles: [],
        filesToDelete: [],
        showExisting:true,

        saveBtnText: 'Save',
        sending:false,
        saveNSendToggled:false,
        saveNSendBtnText:'Save and Send',
        disableSaveNSendBtn:false,
        disableSaveBtn:false,

        // allowCreditCard:true,
        allowACHPayment:true,
        qboTxnTaxDetail: [],
        qbCustomerGSTIN:null,
        selectedInvoiceRealmId:null,
        customerCurrencyRef:null,
        exchangeRateValue:null,
    }),

    computed:{
        formattedLastDelivery: function(){
            if(this.invoiceLastDelivery !== null){
                const lastDelivery = moment.utc(this.invoiceLastDelivery);
                return lastDelivery.tz("America/New_York").format('MMM DD, YYYY hh:mm A');
            }
        }
    },

    created() {
        const state = this.$store.state;

        if(state.chargesTab.selectedInvoice != null){
            this.invoiceLoaded = false;
            axios.get("/custom/invoice/by-id/"+ state.chargesTab.selectedInvoice.id)
            .then(response => {
                const result = response.data.results[0];
                if(Object.keys(result).length > 0 ){
                    //console.log(moment(result.invoice_date).format('YYYY-MM-DD'));
                    this.invoiceData = result;
                    const exchageRateInfo = JSON.parse(result.exchange_rate_info);

                   //set Initial Modal Value
                    this.initCustomerValue = [{"FullyQualifiedName":result.qbo_customer_name,"Id":result.qbo_customer_id}];
                    this.qbCustomerEmail = result.qbo_bill_to_email;
                    this.qbInvoiceNumber = result.invoice_num;
                    this.qbCustomerBillingAddress = result.qbo_bill_to_address;
                    this.qbCustomerInvoiceDate = result.invoice_date ? moment.utc(result.invoice_date).format('YYYY-MM-DD') : '';
                    this.qbCustomerInvoiceDueDate = moment(result.due_date).format('YYYY-MM-DD')
                    this.termValue = [{"Name":result.qbo_term_name,"Id":result.qbo_term_id,"DueDays":result.qbo_term_days}];
                    this.qbCustomerId = result.qbo_customer_id;
                    this.qbCustomerName = result.qbo_customer_name;
                    this.initServiceSectionValue = result.invoice_services;
                    this.qbTermId = result.term;
                    this.qbTermName = result.qbo_term_name;
                    this.qbTermDueDays = result.qbo_term_days;
                    this.qboInvoiceId = result.qbo_id;
                    this.shiflInvoiceId = result.id;
                    this.qbCustomerMemo = result.qbo_customer_memo;
                    this.isInvoiceSent = result.is_email_sent === 1|| result.is_email_sent === 'Yes' || result.email_sent_count > 0 ? true : false;
                    this.invoiceLastDelivery = result.email_sent_at !== null ? result.email_sent_at : null;
                    this.invoiceLoaded = true;
                    // this.allowCreditCard = result.allow_credit_card_payment === 1 ? true : false;
                    this.allowACHPayment= result.allow_online_ach_payment === 1 ? true : false;
                    this.qboTxnTaxDetail = result.qbo_tax_detail;
                    this.totalTax = result.total_tax;
                    this.qbCustomerGSTIN = result.qbo_customer_gstin;
                    this.exchangeRateValue = exchageRateInfo;
                    this.customerCurrencyRef = result.currency_ref !== null ? result.currency_ref : null;

                    this.autoInvoiceDateUpdate = result.auto_invoice_date_update


                }
            })
            .catch(e => {
                this.invoiceLoaded = true;
                console.log(e);
            });
        };
    },
    beforeMount() {
        //
    },
    mounted() {
        const state = this.$store.state.chargesTab;
        this.handleGetInvoiceAttachments(state.selectedInvoice.id);
        this.selectedInvoiceRealmId = state.selectedInvoice.realm_id;
    },
    methods: {
        onClickGrid (value) {
            this.gridValues = value
        },
        toggleAutoInvoiceDate() {
            this.autoInvoiceDateUpdate = !this.autoInvoiceDateUpdate
        },
        onItemSectionChange (value){
            this.serviceItems = value;
        },
        onCustomerSelect(value) {
             if(value){
                this.qbCustomerId = value.Id;
                this.qbCustomerName = value.FullyQualifiedName;
                // if(value.CurrencyRef){
                //     this.customerCurrencyRef = value.CurrencyRef;
                //     this.getExchangeRate(value.CurrencyRef);
                // }
                if(value.BillAddr){
                    this.consCustomerBillingAddr(value);
                }else{
                    this.qbCustomerBillingAddress = value.DisplayName;
                }
                if(value.PrimaryEmailAddr){
                    let email = value.PrimaryEmailAddr.Address;
                    this.qbCustomerEmail = email;
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
            let companyName = `${customerDetails.CompanyName +`\n`}`
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
            let result = new Date(date);
            result.setDate(result.getDate() + parseInt(days));
            return result;
        },

        closeInvoiceModal(){
            this.$store.commit('showEditInvoiceModal_CT',false)
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

        onRemoveFromExistingFiles: function(index,qbo_attachable_id){
            this.$delete(this.existingFiles,index)
            this.filesToDelete.push(qbo_attachable_id);
        },


        // handleAllowCreditCard: function(e){
        //     this.allowCreditCard = e;
        // },

        handleAllowACHPayment: function(e){
            this.allowACHPayment = e;
        },

        handleUploadAttachment: function(qbo_invoice_id,invoice_id,attachment,isSaveNSend){
            const formData = new FormData();
            formData.append('qbo_invoice_id',qbo_invoice_id);
            formData.append('invoice_id',invoice_id);
            formData.append('shipment_id',this.resourceId);
            
            if(this.filesToDelete.length > 0){
                formData.append("files_for_delete",JSON.stringify(this.filesToDelete));
            }

            if(this.existingFiles.length > 0){
                let i = 0;
                for(const file of this.existingFiles) {
                    formData.append("existing_file["+i+"]",JSON.stringify(file));
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

            if((this.attachments.files && this.attachments.files.length > 0) || (this.filesToDelete.length > 0)){
                if(isSaveNSend){
                    this.saveNSendBtnText = "Updating Attachment...";
                }else{
                    this.saveBtnText = "Updating Attachment...";
                }
                axios.post("/custom/qbo/attachable/upload",
                    formData,
                    { headers: {"Content-Type": "multipart/form-data"} }
                )
                .then( response =>{
                    if (response.data.success === true) {
                        Nova.success('Attachment updated successfully!');
                        if(isSaveNSend){
                            this.sendInvoiceByQBO(invoice_id);
                        }else{
                            this.saveBtnText = "Save";
                            this.updateInvoiceTable()
                            setTimeout(()=>{ this.closeInvoiceModal()},500);
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
                    console.log(e)
                    Nova.error(e);
                    this.saveBtnText = "Save";
                }); 
            }
            else{
                this.saving = false;
                this.saveBtnText = "Save";
                //setTimeout(()=>{ this.$emit('close')},500) 
                setTimeout(()=>{ this.closeInvoiceModal()},500);
            }      
        },

        handleGetInvoiceAttachments: function(invoiceId){
            if(invoiceId){
                axios.get("/custom/invoices/get-invoice-attachments/"+invoiceId)
                .then( response =>{
                    if (response.data.success === true) {
                        this.existingFiles = response.data.data;
                    }
                })
                .catch(e =>{
                    console.log(e)
                    Nova.error(e);
                }); 
            }
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
                    this.updateInvoiceTable()
                    setTimeout(()=>{ this.closeInvoiceModal()},500);
                    this.saveNSendBtnText = "Save and Send";
                }else{

                    if ( typeof response.data!=='undefined' && typeof response.data.errorMessage!=='undefined' ) {
                        Nova.error(response.data.errorMessage);
                    } else {
                        Nova.error('Failed to send invoice.');    
                    }
                    
                    this.$emit('updateInvoicesTable');
                    this.saveNSendBtnText = "Save and Send";
                    this.disableSaveBtn = false;
                    this.saveBtnText = "Save";
                    this.disableSaveNSendBtn = false;
                }
            })
            .catch(e =>{
                console.log(e)
                Nova.error(e);
            });
        },

        onSaveInvoice(isSaveNSend){
            if(isSaveNSend){
                this.saveNSendBtnText = "Saving...";
                this.disableSaveBtn = true;
                this.disableSaveNSendBtn = true;
            }else{
                this.saveBtnText = "Saving...";
                this.disableSaveBtn = true;
                this.disableSaveNSendBtn = true;
            }
            
            let filteredServiceItems = _.reject(this.serviceItems, function(o){return _.isNull(o.Service)});


            axios.post("/custom/qbo/invoice/edit",{
                auto_invoice_date_update: this.autoInvoiceDateUpdate ? 1 : 0,
                qboInvoiceId: this.qboInvoiceId,
                shiflInvoiceId: this.shiflInvoiceId,
                customerId: this.qbCustomerId,
                line: filteredServiceItems,
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
                qboTxnTaxDetail: this.qboTxnTaxDetail,
                customerGSTIN: this.qbCustomerGSTIN,
                customerCurrencyRef:this.customerCurrencyRef,
                exchangeRateValue:this.exchangeRateValue
            })
            .then(response =>{
                if(response.data.success === true) {
                    Nova.success('Invoice Saved Successfully!');
                    if((this.attachments.files && this.attachments.files.length > 0) || (this.filesToDelete.length > 0)){
                        this.handleUploadAttachment(this.qboInvoiceId,this.shiflInvoiceId,this.attachments,isSaveNSend);
                    }else{
                        if(isSaveNSend){
                           this.sendInvoiceByQBO(this.shiflInvoiceId);
                        }else{
                            this.updateInvoiceTable()
                            setTimeout(()=>{ this.closeInvoiceModal()},500);
                        }
                    }
                }else{
                    Nova.error(response.data);
                    console.log(response.data);
                    this.saveNSendBtnText = "Save and Send";
                    this.disableSaveBtn = false;
                    this.saveBtnText = "Save";
                    this.disableSaveNSendBtn = false;
                }
            })
            .catch( e => {
                console.log(e)
                Nova.error(e);
            });
        },
        updateInvoiceTable: function(){
            this.$store.dispatch('fetchShipmentInvoices', this.resourceId)
        },

        // getExchangeRate: function(customerCurrencyCode){
        //     this.exchangeRateValue = null;
        //     let targetCurrencyCode = '';
        //     if(this.qboCompanyInfo.Country === "US"){
        //         targetCurrencyCode = "USD"
        //     }else if(this.qboCompanyInfo.Country === "CN"){
        //         targetCurrencyCode = "CNY"
        //     }else if(this.qboCompanyInfo.Country === "IN"){
        //         targetCurrencyCode = "INR"
        //     }

        //     if(targetCurrencyCode !== ''){
        //         axios.get('/custom/qbo/exchangerate/get-by-currency-codes?sourceCurrencyCode='+customerCurrencyCode+'&targetCurrencyCode='+targetCurrencyCode)
        //         .then(response => {
        //             if(response.data.result !== null){
        //                 console.log(response.data.result)
        //                 this.exchangeRateValue = response.data.result;
        //             }
        //         })
        //         .catch(e => {
        //             console.log(e)
        //         })
        //     }
        // },
    },
    /*
    watch: {
        qbTermDueDays: function(newVal,oldVal){
            //this.qbCustomerInvoiceDueDate = moment(this.qbCustomerInvoiceDate).add(newVal,'d').format('YYYY-MM-DD');
        },
        qbCustomerInvoiceDate: function(newVal,oldVal){
           // this.qbCustomerInvoiceDueDate = moment(newVal).add(this.qbTermDueDays,'d').format('YYYY-MM-DD');
        },
    },*/
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
    hr {
        border: 0;
        clear:both;
        display:block;
        width: 100%;               
        background-color:#ebeef2;
        height: 1px;
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
    .status-badge{
        border-radius: 5px;
        background-color: #1ad65f;
        padding-top: .15rem;
        padding-bottom: .15rem;
        padding-right: .5rem;
        padding-left: .5rem;
        color: #ffffff;
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
