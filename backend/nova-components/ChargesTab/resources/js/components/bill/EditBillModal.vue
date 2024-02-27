<template>
    <div>
        <delete-attachment-modal
            :show="showDeleteAttachmentModal"
            @close="handleCloseDeleteAttachmentModal"
            :item="deleteAttachmentItem"
        >
            <template v-slot:header>
                <div class="font-bold">
                    Delete Attachment
                </div>
            </template>
            <template v-slot:body>
                <div>Are you sure you want to delete this attachment?</div>
            </template>
            <template v-slot:footer="{ footerProps }">
                <div class="flex flex-row">
                    <button class="btn btn-primary btn-default mr-2" @click.prevent="footerProps.deleteItem(footerProps.item)">
                        {{ footerProps.loading ? 'Deleting...' : 'Yes' }}
                    </button>
                    <button @click.prevent="footerProps.cancel" class="btn btn-danger btn-default">
                        No
                    </button>
                </div>
            </template>
        </delete-attachment-modal>
        <div v-if="qboCompanyInfo !== null && billLoaded === true && terms.length > 0"  class="add-bill-modal-container p-8 dx-viewport container" style="max-height: 80vh; width: 100%; overflow-y:auto">
            <div class="flex">
                <div class="flex-1" style="width:30%!impotant">
                    <label>Vendor: </label>
                    <vendor-search-box
                        :resourceId="resourceId"
                        @changed="onVendorSelect"
                        :initVendorValue="initVendorValue"
                    ></vendor-search-box>
                </div>
                <div class="flex-1 mr-2"></div>
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
                    </div>
                </div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label>Mailling Address: </label>
                    <textarea rows="2" style="height:auto; border-radius:0px;" class="w-full form-control form-control-sm form-input form-input-bordered" v-model='qbVendorMailingAddress'></textarea>
                </div>
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
                    <label>Bill #:</label>
                    <input class="w-full form-control form-control-sm form-input form-input-bordered" type="text" v-model='qbBillNumber' style="border-radius:0px;">
                </div>
            </div>
            <div class="flex my-3">
                <div class="flex-1 mr-2">
                    <label>Bill Date: </label>
                    <!-- <datepicker class="w-full form-control form-input form-input-bordered"></datepicker> -->
                    <input class="w-full form-control form-control-sm form-input form-input-bordered" type="date" v-model='qbVendorBillDate' style="border-radius:0px;">
                </div>
                <div class="flex-1  mr-2">
                    <label>Due Date: </label>
                    <input
                        class="w-full form-control form-control-sm form-input form-input-bordered"
                        type="date"
                        v-model="qbVendorDueDate"
                        style="border-radius:0px;"
                    />
                </div>
                <div class="flex-1 mr-2">
                    <label>Memo: </label>
                    <textarea rows="2" style="height:auto; border-radius:0px" class="w-full form-control form-control-sm form-input form-input-bordered" v-model='qbMemo'></textarea>
                </div>
            </div>
            <div class="flex my-3 flex-col">
                <div v-if="getAttachmentsLoading">
                    Loading Attachments
                </div>
                <div class="flex flex-col" v-if="!getAttachmentsLoading">
                    <label class="font-bold pt-2 pb-2">{{ (getEditAttachments.length > 0) ? 'Attached Documents' : 'No Attached Documents'}}</label>
                    <div class="flex flex-row items-center" v-for="i in getEditAttachments">
                        <div class="font-bold attachment-filename-container" v-tooltip="`${i.filename}`" style="width:30%; word-wrap: break-word;">
                        {{
                            i.filename
                        }}
                        </div>
                        <div style="width: 30%;" class="flex flex-row items-center">
                            <a :href="`/custom/download?link=${encodeURIComponent(i.path)}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center pl-2 mr-2">
                                <action-icon :width="24" :height="24" iconName="download"></action-icon>
                            </a>
                            <a class="dim btn btn-link text-primary inline-flex cursor-pointer" style="color: red;" @click.prevent="handleDeleteBillAttachment(i)">
                                <action-icon :width="24" :height="24" iconName="delete"></action-icon>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex my-3 flex-col">
                <label v-if="getEditAttachments.length == 0" class="py-2">Attachment(s): </label>
                <div class="w-full pb-2" v-if="attachments.length > 0">
                    <div class="flex flex-row" v-for="(attachment, index) in attachments">
                        <div v-tooltip="`${attachment.file.name}`" class="attachment-filename-container">{{ attachment.file.name + ' ' + getFileSize(attachment.file.size) + '*' + attachment.file.name.split('.').pop() }}
                        </div>
                        <a style="color: red;" class="cursor-pointer no-underline pl-2" @click.prevent="removeFile(index)">Cancel</a>
                    </div>
                </div>
                <a @click.prevent="handleBillAttachment" style="width: 14%;" class="btn btn-default btn-primary cursor-pointer text-center">Upload File</a>
                <input ref="bill_attachment_edit" @change="(e) => fileChanged(e)"  class="hidden" type="file" multiple/>
            </div>
            <div class="flex my-5">
                <div class="flex-1">
                    <label>Category Details: </label>
                    <div v-if="qboCompanyInfo.Country === 'IN'">
                        <india-categories-section
                            :resourceId="resourceId"
                            @valueChanged="onItemSectionChange"
                            :initCategorySectionValue="initCategorySectionValue"
                            :totalTax="totalTax"
                            :qboCountry="qboCompanyInfo.Country"
                            :exchangeRateValue="exchangeRateValue"
                        />
                    </div>
                    <!-- <div v-else-if="qboCompanyInfo.Country === 'US'"> -->
                    <div v-else>
                        <categories-section
                            :resourceId="resourceId"
                            @valueChanged="onItemSectionChange"
                            :initCategorySectionValue="initCategorySectionValue"
                            :exchangeRateValue="exchangeRateValue"
                        ></categories-section>
                    </div>
                </div>
            </div>
            <div class="flex mt-6">
                <div class="flex-1">
                    <div v-if="selectedBillRealmId === realmId">
                        <button
                            :disabled="saving"
                            type="button"
                            ref="addBillSaveButton"
                            data-testid="add-save-button"
                            dusk="add-save-add-invoice-button"
                            @click.prevent="handleSaveBill"
                            class="btn-default btn-save-bill float-right"
                            style="border:0 solid var(--black); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                        >{{saving ? "Saving...":"Save"}}</button>
                    </div>
                    <div>
                        <button
                            type="button"
                            @click.prevent="closeBillModal"
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
import CategoriesSection from './CategoriesSection';
import IndiaCategoriesSection from '../india-bill/IndiaCategoriesSection';
//import JQuery from 'jquery';
import VendorSearchBox from './VendorSearchBox.vue';
import vSelect from 'vue-select';
import moment from 'moment';
import DeleteAttachmentModal from '../Modals/DeleteAttachmentModal.vue';
import ActionIcon from '../Icons/ActionIcon.vue';
import iziToast from 'izitoast'
import 'izitoast/dist/css/iziToast.min.css'

import {
    mapActions,
    mapGetters
} from 'vuex'
//window.$ = JQuery

export default {
    name: "EditBillModal",
    components: {
        VendorSearchBox,
        vSelect,
        CategoriesSection,
        IndiaCategoriesSection,
        DeleteAttachmentModal,
        ActionIcon
    },
    computed: {
        ...mapGetters({
            getAttachmentsLoading: 'billCharges/getAttachmentsLoading',
            getEditAttachments: 'billCharges/getEditAttachments'
        })
    },
    props: ['resourceId', 'terms','qboCompanyInfo', 'realmId', 'shiflRef'],
    data() {
        return {
            //services: [],
            //events: [],
            //items: [],
            //viewLines: false,
            //errors: [],
            attachments: [],
            deleteAttachmentItem: {},
            showDeleteAttachmentModal: false,
            selectedVendor:[],
            vendorDetails:{},
            termValue:[],
            qbVendorId:0,
            qbVendorName:'',
            qbTermId:0,
            qbTermName:'',
            qbTermDueDays:0,
            qbVendorMailingAddress:'',
            qbMemo: this.shiflRef ? "ShiflRef# "+this.shiflRef : '',
            qbVendorBillDate:'',
            qbVendorDueDate:'',
            qbBillNumber:'',
            qboTxnTaxDetail: [],

            categoryItems:[],

            saving:false,

            //edit data
            billLoaded: false,
            initVendorValue:[],
            initCategorySectionValue:[],
            qboBillId:0,
            shiflBillId:0,
            totalTax:null,
            selectedBillRealmId:null,

            vendorCurrencyRef:null,
            exchangeRateValue:null,
        };
    },
    created() {
        const state = this.$store.state;
        if(state.chargesTab.selectedBill !== null){
            this.billLoaded = false;
            axios.get("/custom/bill/by-id/"+state.chargesTab.selectedBill.id)
            .then(response => {
                const result = response.data.results[0];
                //let exchangeRateInfo = (typeof response.data.exchange_rate_info!=='undefined') ? response.data.exchange_rate_info : null

                let exchangeRateInfo = typeof response.data.exchange_rate_info!=='undefined' ? response.data.exchange_rate_info : null

                let currencyRef = (typeof response.data.currency_ref!=='undefined') ? response.data.currency_ref : null

                if(Object.keys(result).length > 0 ){
                    this.billData = result;

                    const exchageRateInfo = JSON.parse(result.exchange_rate_info);

                   //set Initial Modal Value
                    this.initVendorValue = [{"DisplayName":result.qbo_vendor_name,"Id":result.qbo_vendor_id}];
                    this.initCategorySectionValue = result.bill_items;
                    this.termValue = [{"Name":result.qbo_term_name,"Id":result.qbo_term_id,"DueDays":result.qbo_term_days}]

                    this.qbVendorId = result.qbo_vendor_id,
                    this.qbTermId = result.qbo_term_id;
                    this.qbTermName = result.qbo_term_name;
                    this.qbTermDueDays = result.qbo_term_days;
                    this.qbBillNumber = result.qbo_bill_num;
                    this.qbVendorName = result.qbo_vendor_name;
                    this.qbVendorDueDate = result.qbo_due_date ? moment.utc(result.qbo_due_date).format('YYYY-MM-DD') : '';
                    this.qbVendorBillDate = result.qbo_bill_date ? moment.utc(result.qbo_bill_date).format('YYYY-MM-DD') : '';
                    this.qbVendorMailingAddress = result.qbo_mailing_address;
                    this.qbMemo = result.qbo_memo;
                    this.qboBillId = result.qbo_bill_id;
                    this.shiflBillId = result.id;
                    this.qboTxnTaxDetail = result.qbo_tax_detail;
                    this.totalTax = result.total_tax;
                    this.billLoaded = true;

                    this.exchangeRateValue = ( exchageRateInfo !== null ) ? exchageRateInfo :  null
                    this.exchangeRateValue = (this.exchangeRateValue == null) && exchageRateInfo != null ? exchangeRateInfo[0] : this.exchangeRateValue
                    //this.exchangeRateValue = exchageRateInfo;
                    this.vendorCurrencyRef = result.currency_ref !== null ? result.currency_ref : null;
                    this.vendorCurrencyRef = (this.vendorCurrencyRef==null) && currencyRef != null ? currencyRef : this.vendorCurrencyRef
                    //fetch bill attachments
                    this.fetchBillAttachments({
                        shipment_id: this.resourceId,
                        bill_id: result.id
                    })
                }
            })
            .catch(e => {
                this.billLoaded = true;
                console.log(e);
            });
        }
    },

    beforeMount() {
        //
    },

    mounted() {
        const state = this.$store.state.chargesTab;
        this.selectedBillRealmId = state.selectedBill.realm_id;

    },

    methods: {
        ...mapActions({
            uploadBillAttachmentAction: 'billCharges/uploadBillAttachment',
            fetchBillAttachments: 'billCharges/fetchBillAttachments',
            deleteBillAttachment: 'billCharges/deleteBillAttachment'
        }),
        handleCloseDeleteAttachmentModal() {
            this.showDeleteAttachmentModal = false
        },
        handleDeleteBillAttachment(item) {
            this.showDeleteAttachmentModal = true
            this.deleteAttachmentItem = item
        },
        uploadBillAttachment(data) {

            return new Promise((resolve, reject) => {
                let {
                    qbo_bill_id,
                    bill_id
                } = data

                let payload = {
                    qbo_bill_id,
                    bill_id,
                    shipment_id: this.resourceId,
                    bill_attachment_file: this.attachments
                }
                this.uploadBillAttachmentAction(payload).then(r => {
                    resolve(r)
                })
                .catch(e => {
                    reject(e)
                })
            })


        },
        removeFile(index) {
            this.attachments.splice(index, 1)
        },
        handleClearAttachment() {
            this.attachments = []
        },
        getFileSize(size) {
            let base = 1024
            let decimal = 2

            var kiloBytes = base
            var megaBytes = base * base
            var gigaBytes = base * base * base

            if(size < kiloBytes) {
                return size + " Bytes"
            }
            else if(size < megaBytes) {
                return (size / kiloBytes).toFixed(decimal) + " kb"
            }
            else if(size < gigaBytes) {
                return (size / megaBytes).toFixed(decimal) + " mb"
            }
        },
        handleBillAttachment() {
            this.$refs.bill_attachment_edit.click()
        },
        fileChanged(e) {
            let files = this.$refs.bill_attachment_edit.files;
            if (!files.length)
                return false

            this.attachments = []
            for (let i = 0; i < files.length; i++) {
                this.attachments.push({
                    file: files[i],
                    name: files[i].name,
                })
            }
        },
        handleClose() {
            this.$emit("close");
        },

        onItemSectionChange (value){
            this.categoryItems = value;
        },

        onVendorSelect(value) {
            if(value){
                if(value.CurrencyRef !== null){
                    this.vendorCurrencyRef = value.CurrencyRef;
                    this.getExchangeRate(value.CurrencyRef);
                }
                this.qbVendorId = value.Id;
                this.selectedVendor = value;
                this.qbVendorName = value.DisplayName;
                this.consVendorMailingAddr(value);
            }
        },

        consVendorMailingAddr(vendorDetails){

            let billAddr = vendorDetails.BillAddr ? vendorDetails.BillAddr : {};

            let billerName = `${vendorDetails.GivenName ? vendorDetails.GivenName : ''} ${vendorDetails.FamilyName ? vendorDetails.FamilyName + `\n` : ''}`;
            let companyName = `${vendorDetails.CompanyName +`\n`}`
            let line1 = `${billAddr.Line1 ? billAddr.Line1 + `\n`: ''}`;
            let city = `${billAddr.City ? billAddr.City + `, ` : ''}`;
            let country = `${billAddr.Country ? billAddr.Country + ` ` : (billAddr.CountrySubDivisionCode ? billAddr.CountrySubDivisionCode + ` ` : '') }`;
            let postal = `${billAddr.PostalCode ? billAddr.PostalCode : '' }`;

            this.qbVendorMailingAddress = `${billerName}${companyName}${line1}${city}${country}${postal}`;
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

        handleSaveBill() {
            this.saving = true;
            // let filteredCategoryItems = _.reject(this.categoryItems, function(o){return _.isNull(o.Category && o.Customer)});
            let filteredCategoryItems = _.reject(this.categoryItems, function(o){return _.isNull(o.Category)});
            axios.post("/custom/qbo/bill/edit",{
                qboBillId: this.qboBillId,
                shiflBillId: this.shiflBillId,
                vendorId: this.qbVendorId,
                line: filteredCategoryItems,
                mailingAddress: this.qbVendorMailingAddress,
                termId: this.qbTermId,
                termName: this.qbTermName,
                termDueDays: this.qbTermDueDays,
                billNum: this.qbBillNumber,
                vendorName: this.qbVendorName,
                dueDate: this.qbVendorDueDate,
                billDate: this.qbVendorBillDate,
                memo: this.qbMemo,
                resourceId : this.resourceId,
                qboCompanyInfo: this.qboCompanyInfo,
                qboTxnTaxDetail: this.qboTxnTaxDetail,
                vendorCurrencyRef:this.vendorCurrencyRef,
                exchangeRateValue:this.exchangeRateValue
            })
            .then(response =>{
                if (response.data.success === true) {



                    if ( this.attachments && this.attachments.length > 0 ){

                        this.uploadBillAttachment({
                            qbo_bill_id: this.qboBillId,
                            bill_id: this.shiflBillId,
                            shipment_id: this.resourceId
                        }).then(r => {
                            setTimeout(() => {
                                this.closeBillModal()
                            },500)
                            Nova.success('Bill Saved Successfully!');
                            this.updateBillsTable();
                            this.saving = false;
                        }).catch(e => {
                            iziToast.error({
                              title: 'An error occured',
                              message: 'An error occured while trying to upload document(s).',
                              displayMode: 1,
                              position: 'bottomCenter',
                              timeout: 3000
                            });
                        })
                    } else {
                        Nova.success('Bill Saved Successfully!');
                        this.updateBillsTable();
                        this.saving = false;
                    }


                }else{
                    Nova.error(response.data);
                    this.saving = false;
                }

            })
            .catch( e => {
                this.saving = false;
                console.log(e)
                Nova.error(e);
            });
        },
        closeBillModal(){
            this.$store.commit('showEditBillModal_CT',false);
        },
        updateBillsTable: function(){
            this.$store.dispatch('fetchShipmentBills', this.resourceId)
        },

        getExchangeRate: function(vendorCurrencyCode){

            this.exchangeRateValue = null;

            const qboCompanyInfo = this.qboCompanyInfo;
            const state = this.$store.state.chargesTab;
            const activeCompanyRealm = state.qboCompanyInfo.realm_id;
            const qboCompanies = state.qboCompanyAccounts;

            const activeCompany = _.find(qboCompanies,function(o){
                return o.company_realm_id === activeCompanyRealm && o.country === qboCompanyInfo.Country;
            });
            let targetCurrencyCode = typeof activeCompany !== 'undefined' ? activeCompany.currency_code : '';
            if(targetCurrencyCode !== ''){
                axios.get('/custom/qbo/exchangerate/get-by-currency-codes?sourceCurrencyCode='+vendorCurrencyCode+'&targetCurrencyCode='+targetCurrencyCode)
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
                this.exchangeRateError = true;
            }
        },

    },

    watch: {
        qbTermDueDays: function(newVal,oldVal){
            this.qbVendorDueDate = moment.utc(this.qbVendorBillDate).add(newVal,'d').format('YYYY-MM-DD');
        },
        qbVendorBillDate: function(newVal,oldVal){
            this.qbVendorDueDate = moment.utc(newVal).add(this.qbTermDueDays,'d').format('YYYY-MM-DD');
        },
        shiflRef : function(newVal,oldVal){
            if(this.shiflRef){
                this.qbMemo = "ShiflRef# "+this.shiflRef;
            }
        },
    },
};

</script>

<style scoped>
    .attachment-filename-container {
        cursor: pointer;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        display: block;
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
    .btn-save-bill{
        background-color:#16A34A;
        color:#ffffff !important;
    }
    .btn-save-bill:hover{
        background-color:#15803D !important;
    }
    .btn-close-modal{
        background-color:#71717A !important;
    color:#ffffff !important;
    }
    .btn-close-modal:hover{
        background-color:#52525B !important;
    }
    .currency-flag-icon{
        max-height: 20px;
    }

</style>
