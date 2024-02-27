<template>
    <div class="border-b border-40">
        <div class="flex w-full">
            <label v-if="qboCompanyInfo !== null && qboCompanyInfo.company.CompanyName !== null" style="font-style: italic; font-size: 0.8rem; margin-left:auto; color:#16A34A">You are connected to Quickbooks {{qboCompanyInfo.company.CompanyName}}</label>
            <label v-else style="font-style: italic; font-size: 0.8rem; margin-left:auto; color:red">{{qboConStatText}}</label>
        </div>
        <div v-if="qboCompanyInfo !== null && terms.length > 0 && qboCustomerInfo !== null">
            <div class="flex w-full pt-2 px-2 mt-2">
                <tabs
                 ref="tabs"
                 @openEditInvoiceModal="openUpdateInvoiceModal"
                 @closeEditInvoiceModal="closeUpdateInvoiceModal"
                 @openDeleteInvoiceModal="openDeleteInvoiceModal"
                 @closeDeleteInvoiceModal="closeDeleteInvoiceModal"
                 @openEditBillModal="openEditBillModal"
                 @closeEditBillModal="closeEditBillModal"
                 @openDeleteBillModal="openDeleteBillModal"
                 @closeDeleteBillModal="closeDeleteBillModal"
                />
            </div>
            
            <div v-if="companyAccounts.length > 0" class="flex w-full pb-4 px-2 mt-4">
                <div class="flex-1">
                    <button
                    v-if="1==2"
                    v-on:click.prevent="openAddInvoiceModal"
                    class="btn-default  mr-6"
                    style="border: blue 2px; background-color: var(--primary); color: var(--white); border: 0 solid var(--black); line-height: 2.25rem; text-shadow: 0 1px 2px rgba(0,0,0,.2); box-sizing: inherit;"
                    >
                        <span>
                            Add Invoice
                        </span>
                    </button>
                    <button
                    v-on:click.prevent="openInvoiceModal"
                    class="btn-default  mr-6"
                    style="border: blue 2px; background-color: var(--primary); color: var(--white); border: 0 solid var(--black); line-height: 2.25rem; text-shadow: 0 1px 2px rgba(0,0,0,.2); box-sizing: inherit;"
                    >
                        <span>
                            Add Invoice
                        </span>
                    </button>
                    <button
                    v-on:click.prevent="openAddBillModal"
                    class="btn-default"
                    style="border: blue 2px; background-color: var(--primary); color: var(--white); border: 0 solid var(--black); line-height: 2.25rem; text-shadow: 0 1px 2px rgba(0,0,0,.2); box-sizing: inherit;"
                    >
                        <span>
                            Add Bill
                        </span>
                    </button>
                </div>
                <div class="flex-1 mr-6">
                    
                </div>
            </div>
        </div>
        <div v-else  class="w-full py-2 px-2">
            <center>
                <label class="font-semibold text-lg">{{loadingText}}</label>
            </center>
        </div>
        <!-- Invoice Modal Start -->
        <transition name="fade">
            <global-modal 
                ref="addInvoiceModal"
                :isAddInvoice="true"
                :show="showInvoiceModal"
                :servicesSectionValue="getServicesSectionValue"
                @close="closeInvoiceModal"
                :qboCustomerInfo="qboCustomerInfo"
                :dynamic="dynamic"
            >
                <template v-slot:notes>
                    <div class="flex flex-col">
                        <div class="w-full notes-header-wrapper">
                          <h3>Customer Notes</h3>
                        </div>
                        <div class="w-full notes-current-customer-wrapper">
                            {{ currentCustomer!=null && currentCustomer.billing_notes!='' ? currentCustomer.billing_notes : 'No notes found in the customer.' }}
                        </div>
                    </div>
                </template>
                <template v-slot:header>
                    <div class="w-full font-bold text-lg general-info-label">
                    Add Invoice</div>
                </template>
                <template v-slot:body="{ props }">
                        <add-invoice-modal
                         :resourceId="resourceId"
                         :terms="terms"
                         :isAddInvoice="props.isAddInvoice"
                         :dynamic="dynamic"
                         :qboCompanyInfo="qboCompanyInfo ? qboCompanyInfo.company : null"
                         :qboCustomerInfo="qboCustomerInfo"
                         :shipmentInfo="shipmentInfo"
                         @closeAddInvoiceModal="closeAddInvoiceModal"
                        />
                </template>
            </global-modal>
        </transition>
        <global-modal 
            ref="editInvoiceModal"
            :isAddInvoice="false"
            :show="showUpdateInvoiceModal"
            :servicesSectionValue="getServicesSectionValue"
            :qboCustomerInfo="qboCustomerInfo"
            @close="closeUpdateInvoiceModal"
        >
            <template v-slot:notes>
                <div class="flex flex-col">
                    <div class="w-full notes-header-wrapper">
                      <h3>Customer Notes</h3>
                    </div>
                    <div class="w-full notes-current-customer-wrapper">
                       {{ currentCustomer!=null && currentCustomer.billing_notes!='' ? currentCustomer.billing_notes : 'No notes found in the customer.' }}
                    </div>
                </div>
            </template>
            <template v-slot:header>
                <div class="w-full font-bold text-lg general-info-label">
                Edit Invoice</div>
            </template>
            <template v-slot:body="{ props }">
                <div>
                    <add-invoice-modal
                     :resourceId="resourceId"
                     :terms="terms"
                     :isAddInvoice="props.isAddInvoice"
                     :servicesSectionValue="getServicesSectionValue"
                     :qboCompanyInfo="qboCompanyInfo ? qboCompanyInfo.company : null"
                     :qboCustomerInfo="props.qboCustomerInfo"
                     :shipmentInfo="shipmentInfo"
                     @closeAddInvoiceModal="closeEditInvoiceModal"
                    />
                </div>
                
            </template>
        </global-modal>
        <transition name="fade">
            <modal ref="deleteInvoiceModal" class="delete-modal">
                <delete-invoice-modal
                 :resourceId="resourceId"
                 :qboCompanyInfo="qboCompanyInfo ? qboCompanyInfo.company : null"
                />
            </modal>
        </transition>
        <!-- Invoice Modal End -->
        <!-- Bill Modal START -->
        <transition name="fade">
            <modal ref="editBillModal">
                <edit-bill-modal
                 :resourceId="resourceId"
                 :terms="terms"
                 :qboCompanyInfo="qboCompanyInfo ? qboCompanyInfo.company : null"
                 :realmId="qboCompanyInfo ? qboCompanyInfo.realm_id : null"
                 :shiflRef="shiflRef"
                />
            </modal>
        </transition>
        <transition name="fade">
            <modal ref="addBillModal">
                <add-bill-modal
                 :resourceId="resourceId"
                 :terms="terms"
                 :billAttachments="attachments"
                 :qboCompanyInfo="qboCompanyInfo ? qboCompanyInfo.company : null"
                 :realmId="qboCompanyInfo ? qboCompanyInfo.realm_id : null"
                 :shiflRef="shiflRef"
                 @closeAddBillModal="closeAddBillModal"
                />
            </modal>
        </transition>
        <transition name="fade">
            <modal ref="deleteBillModal" class="delete-modal">
                <delete-bill-modal
                 :resourceId="resourceId"
                />
            </modal>
        </transition>
        <!-- Bill Modal END -->
    </div>
</template>

<script>
import axios from 'axios';
import _ from 'lodash'
import GlobalModal from './modals/GlobalModal.vue'
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import { mapGetters, mapActions } from 'vuex';


import Tabs from './tab-components/Tabs.vue';
import Modal from './modal-components/Modal.vue';

import EditInvoiceModal from './invoice/EditInvoiceModal.vue';
import AddInvoiceModal from './invoice/AddInvoiceModal.vue';
import DeleteInvoiceModal from './invoice/DeleteInvoiceModal.vue';

import EditBillModal from './bill/EditBillModal.vue';
import AddBillModal from './bill/AddBillModal.vue';
import DeleteBillModal from './bill/DeleteBillModal.vue';

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: () =>({
        terms:[],
        qboCompanyInfo:null,
        qboCustomerInfo:null,
        shipmentInfo:null,
        shiflRef:null,
        loadingText:'Fetching QuickBooks Information, please wait...',
        qboConStatText: '',
        showInvoiceModal: false,
        showUpdateInvoiceModal: false,
        isAdd: false,
        companyAccounts:[],
        attachments: [],
        dynamic: 0,
        currentCustomer: null
    }),
    components: {
        Tabs,
        Modal,
        EditInvoiceModal,
        AddInvoiceModal,
        DeleteInvoiceModal,
        EditBillModal,
        AddBillModal,
        GlobalModal,
        DeleteBillModal,
    },
    computed:{
        ...mapGetters({
            shipmentInvoices: 'shipmentInvoices',
            shipmentBills: 'shipmentBills',
            qboCompanyAccounts: 'qboCompanyAccounts',
            salesRepresentative: 'customer/getSalesRepresentative',
            getServicesSectionValue: 'page/getServicesSectionValue'
        })
    },
    created: function () {

        this.$store.commit('updateResourceId_CT', this.resourceId)
        this.getQBOTerms();
        this.getQBOCompanyInfo();
        this.getShipmentInfo();
    },

    mounted() {
        this.$store.dispatch('fetchShipmentInvoices',this.resourceId);
        this.$store.dispatch('fetchShipmentBills',this.resourceId);

        this.getCustomer(this.resourceId)

    },

    methods: {
        ...mapActions({
          setServicesSectionValue: 'page/setServicesSectionValue'
        }),
        getCustomer(shipment_id) {
            this.$store.dispatch('customer/getCustomer',{
                shipment_id
            })
        },
        openUpdateInvoiceModal() {
            this.showUpdateInvoiceModal = true
        },
        closeUpdateInvoiceModal() {
            this.showUpdateInvoiceModal = false
        },
        openInvoiceModal() {
            this.showInvoiceModal = true
            this.setServicesSectionValue([])
            console.log('herererere')
        },
        closeInvoiceModal() {
            this.showInvoiceModal = false
        },
        getQBOCompanyInfo: function(){
            axios.get("/custom/qbo/get-company-info-v2")
            .then( response =>{
                if (response.data.success === true) {
                    this.$store.commit('updateQBOCompanyInfo_CT',response.data.result);
                    this.qboCompanyInfo = response.data.result;
                }else{
                    this.loadingText = 'Failed to get QuickBooks information. Please connect to QuickBooks to continue access to Charges.';
                }
            })
            .catch(e =>{
                console.log(e);
                this.loadingText = 'Failed to get QuickBooks information. Please connect to QuickBooks to continue access to Charges.';
                //Nova.error('Failed to get QuickBooks information. Please connect to QuickBooks to continue access to Charges.');
            });
        },
        getQBOTerms: function(){
            axios.get("/custom/qbo/terms")
            .then(response => {
                if(response.data.length > 0){
                    this.terms = response.data
                }else{
                    this.loadingText = 'Failed to fetch QuickBooks Terms.';
                }
            })
            .catch(e => {
                console.log(e);
                this.loadingText = 'Failed to get QuickBooks Terms.';
                //Nova.error('Failed to fetch QuickBooks Terms.');
            });
        },

        getShipmentInfo: function(){

            axios.get("/custom/qbo/get-shipment-info/"+this.resourceId)
            .then( response =>{
                if (response.data.success === true) {
                    const data = response.data.result;
                    this.shipmentInfo =  data.shipment;
                    this.shiflRef = data.shipment.shifl_ref;

                    this.currentCustomer = data.shipment.customer
                    console.log('cc', this.currentCustomer)
                    if(data.customer !== null && typeof data.customer.customer !== 'undefined'){
                        const qboCust = JSON.parse(data.shipment.customer.qbo_customer);

                        console.log('qboCust', qboCust)
                        if(qboCust.customer.Id){

                            this.qboCustomerInfo = {
                                company: data.customer_qbo_company,
                                realm_id: data.customer_qbo_realm_id,
                                customer: qboCust.customer,
                            }
                            console.log('set herererer', this.qboCustomerInfo)
                        }
                    }else{
                        this.loadingText = "No QuickBooks Customer is associated. Please link customer to QBO Customer."
                    }
                }
            })
            .catch(e =>{
                this.loadingText = "No QuickBooks Customer is associated. Please link customer to QBO Customer."
                console.log(e);
            });
        },

        // INVOICE METHODS STARTS >>>>>
        openEditInvoiceModal(){
            this.$refs.editInvoiceModal.open();
        },
        closeEditInvoiceModal(){
            setTimeout( ()=>(this.$refs.editInvoiceModal.close(true)),100);
        },

        openAddInvoiceModal(){
            this.$refs.addInvoiceModal.open();
        },
        closeAddInvoiceModal(){
            setTimeout( ()=>(this.$refs.addInvoiceModal.close(true)),100);
        },

        openDeleteInvoiceModal(){
            this.$refs.deleteInvoiceModal.open();
        },
        closeDeleteInvoiceModal(){
            setTimeout( ()=>(this.$refs.deleteInvoiceModal.close(true)),100);
        },
        // INVOICE METHODS ENDS <<<<

        //BILL METHODS STARTS >>>>>
        openEditBillModal(){
            this.$refs.editBillModal.open();
        },
        closeEditBillModal(){
             setTimeout(()=>(this.$refs.editBillModal.close(true)),100)
        },
        openAddBillModal(){
            this.$refs.addBillModal.open()
        },
        closeAddBillModal(){
             setTimeout(()=>(this.$refs.addBillModal.close(true)),100)
        },
        openDeleteBillModal(){
            this.$refs.deleteBillModal.open();
        },
        closeDeleteBillModal(){
            setTimeout(()=>(this.$refs.deleteBillModal.close(true),100))
        }
        //BILL METHODS ENDS <<<<<

    },
    filters: {
        //
    },
    watch:{
        qboCompanyAccounts: function(nVal,oVal){
            if(nVal.length > 0){
                this.companyAccounts = nVal;
            }else{
                this.loadingText =  "No associated QuickBooks Accounts.";
                this.companyAccounts = null;
            }
        }
    }
}
</script>

<style>
.modal {
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.75);
  z-index: 23;
}

.modal__body {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);

  display: flex;
  flex-direction: column;
  width:75%;
  min-height: 200px;

  background: #fff;
  border-radius: 4px;
  padding: 20px;
}

.modal__content {
  flex: 1 0 90%;
}

.modal__footer {
  flex: 0 0 10%;

  display: flex;
  justify-content: center;
}

.delete-modal .modal__body{
    max-width: 50% !important;
    width:50% !important;
    min-height: 200px !important;
}

.fade-enter-active {
    transition: opacity .5s;
}
.fade-leave-active{
    transition: opacity .5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
