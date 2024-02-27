<template>
    <div>
        <div v-if="qboCompanyInfo !== null && terms.length > 0" class="add-bill-modal-container p-8 dx-viewport container" style="max-height: 80vh; width: 100%; overflow-y:auto">
            <div class="flex">
                <div class="flex-1" style="width:30%!impotant">
                    <label>Vendor: </label>
                    <vendor-search-box
                        :resourceId="resourceId"
                        @changed="onVendorSelect"
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
                        <span> as of {{exchangeRateValue.AsOfDate}}</span>
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
                    <textarea rows="2" style="height:auto; border-radius:0px;" class="w-full form-control form-control-sm form-input form-input-bordered" v-model='qbMemo'></textarea>
                </div>
            </div>
            <div class="flex my-3 flex-col">
                <label class="py-2">Attachment(s): </label>
                <div class="w-full pb-2">
                    <div class="flex flex-row py-1" v-for="(attachment, index) in attachments">
                        <div v-tooltip="`${attachment.file.name}`" class="attachment-filename-container">{{ attachment.file.name + ' ' + getFileSize(attachment.file.size) + '*' + attachment.file.name.split('.').pop() }}
                        </div>
                        <a style="color: red;" class="cursor-pointer no-underline pl-2" @click.prevent="removeFile(index)">Cancel</a>
                    </div>
                </div>
                <a @click.prevent="handleBillAttachment" style="width: 14%;" class="btn btn-default btn-primary cursor-pointer text-center">Upload File</a>
                <input ref="bill_attachment" @change="(e) => fileChanged(e)"  class="hidden" type="file" multiple/>
            </div>
            <div class="flex my-5">
                <div class="flex-1">
                    <label>Category Details: </label>
                    <div v-if="qboCompanyInfo.Country === 'IN'">
                        <india-categories-section
                            :resourceId="resourceId"
                            @valueChanged="onItemSectionChange"
                            :initCategorySectionValue="initCategorySectionValue"
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
                    <!-- <div v-else>
                        <center>Loading components...</center>
                    </div> -->
                </div>
            </div>
            <div class="flex mt-6">
                <div class="flex-1">
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
                    <button
                        type="button"
                        @click.prevent="closeAddBillModal"
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
import CategoriesSection from './CategoriesSection';
import IndiaCategoriesSection from '../india-bill/IndiaCategoriesSection';
import VendorSearchBox from './VendorSearchBox.vue';
import vSelect from 'vue-select';
import {
    mapActions
} from 'vuex'

import iziToast from 'izitoast'
import 'izitoast/dist/css/iziToast.min.css'

export default {
    name: "AddBillModal",
    components: {
        VendorSearchBox,
        vSelect,
        CategoriesSection,
        IndiaCategoriesSection
    },
    props: ['terms', 'resourceId','shiflRef', 'qboCompanyInfo','shipmentInfo', 'billAttachments'],
    data() {
        return {
            attachments: [],
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
            categoryItems:[],
            initCategorySectionValue:[],
            saving:false,

            vendorCurrencyRef:null,
            exchangeRateValue:null,
            exchangeRateError:false,
            homeCurrency: null,
        };
    },
    created() {
        const state = this.$store.state.chargesTab
        const activeCompanyRealm = state.qboCompanyInfo.realm_id;
        const qboCompanies = state.qboCompanyAccounts;
        const activeCompany = _.find(qboCompanies,function(o){
            return o.company_realm_id === activeCompanyRealm
        });
        this.homeCurrency = typeof activeCompany !== 'undefined' ? activeCompany.currency_code : '';


    },
    beforeMount() {
        //
    },
    methods: {
        ...mapActions({
            uploadBillAttachmentAction: 'billCharges/uploadBillAttachment'
        }),
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
            this.$refs.bill_attachment.click()
        },
        fileChanged(e) {
            let files = this.$refs.bill_attachment.files;
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

            axios.post("/custom/qbo/bill/add",{
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
                resourceId : this.resourceId,
                memo: this.qbMemo,
                qboCompanyInfo: this.qboCompanyInfo,
                shipmentInfo: this.shipmentInfo,
                vendorCurrencyRef:this.vendorCurrencyRef,
                exchangeRateValue:this.exchangeRateValue
            })
            .then(response =>{
                if (response.data.success === true) {
                    let {
                        qbo_bill_id,
                        bill_id
                    } = response.data

                    if ( this.attachments && this.attachments.length > 0 ){
                        this.uploadBillAttachment({
                            qbo_bill_id,
                            bill_id,
                            sihpment_id: this.resourceId
                        }).then(r => {
                            Nova.success('Bill Saved Successfully!');
                            this.updateBillsTable();
                            this.closeAddBillModal();
                            this.saving = false;
                        }).catch(e => {
                            iziToast.error({
                              title: 'An error occured',
                              message: 'An error occured while trying to upload document(s).',
                              displayMode: 1,
                              position: 'bottomCenter',
                              timeout: 3000
                            });
                            this.saving = false;
                        })
                    } else {
                        Nova.success('Bill Saved Successfully!');
                        this.updateBillsTable();
                        this.closeAddBillModal();
                        this.saving = false;
                    }

                    // this.$emit('updateBillsTable');
                    // setTimeout(()=>{ this.$emit('close')},500)

                }else{
                    Nova.error(response.data);
                    this.saving = false;
                    console.log(response.data);
                }

            })
            .catch( e => {
                this.saving = false;
                console.log(e)
            });

        },

        closeAddBillModal(){
            this.$emit('closeAddBillModal');
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
                return o.company_realm_id === activeCompanyRealm;
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
        }
    },

    watch: {
        qbTermDueDays: function(newVal,oldVal){
            if(this.qbVendorBillDate){
                this.qbVendorDueDate =  moment(this.qbVendorBillDate).add(newVal,'d').format('YYYY-MM-DD');
            }
        },
        qbVendorBillDate: function(newVal,oldVal){
            if(this.qbTermDueDays){
                this.qbVendorDueDate = moment(newVal).add(this.qbTermDueDays,'d').format('YYYY-MM-DD');
            }
        },
        shiflRef : function(newVal,oldVal){
            if(this.shiflRef){
                this.qbMemo = "ShiflRef# "+this.shiflRef;
            }
        },
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
        this.qbVendorBillDate = moment.utc().format('YYYY-MM-DD');
        this.qbVendorDueDate = moment.utc().format('YYYY-MM-DD');

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
