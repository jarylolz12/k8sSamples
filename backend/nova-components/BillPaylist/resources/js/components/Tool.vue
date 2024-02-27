<template>
    <div>
        <heading class="mb-6">Bill Pay List</heading>
        <div>
            <search 
                :searchQuery.sync="searchQuery"
                @searchItemNow="searchItemNow"
            />
            <vue-good-table
             mode="remote"
             :columns="columns"
             :rows="itemsFiltered"
             @on-sort-change="onSortChange"
             :totalRows="total"
             styleClass="vgt-table striped"
            >
                <div slot="emptystate">
                    <div class="text-center">{{emptyItemsText}}</div>
                </div>
                <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field == 'payment_status'">
                      <span class="block w-96 text-sm text-black-50" v-if="typeof props.row.status_loading!=='undefined' && props.row.status_loading">Loading Status...</span>
                      <span class="block w-96 text-sm" style="color: red;" v-if="props.row.payment_status==0 && (typeof props.row.status_loading!=='undefined' && !props.row.status_loading)">Not Paid</span>
                      <span class="block w-96 text-sm" style="color: red;" v-if="props.row.payment_status==1 && (typeof props.row.status_loading!=='undefined' && !props.row.status_loading)">Pending</span>
                      <span class="block w-96 text-sm" style="color: orange;" v-if="props.row.payment_status==3 && (typeof props.row.status_loading!=='undefined' && !props.row.status_loading)">Partial - Pending Completion</span>
                      <span class="block w-96 text-sm" style="color: green;" v-if="props.row.payment_status==2 && (typeof props.row.status_loading!=='undefined' && !props.row.status_loading)">Paid</span>
                    </span>
                    <div v-else-if="props.column.field == 'payment_cta'" class="inline-flex items-center">
                        <div v-if="!props.row.marking && !props.row.removing && !props.row.paying">
                            <span class="inline-flex">
                                <a @click="pay(props.row)" class="inline-flex cursor-pointer text-70 hover:text-primary mr-3 has-tooltip" dusk="218-edit-button" data-original-title="null">
                                    <svg height="20" width="24" xmlns="http://www.w3.org/2000/svg" class="inline-flex appearance-none cursor-pointer" fill="none" viewBox="0 0 24 20" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </a>
                            </span>
                            <span class="inline-flex">
                                <a @click="mark(props.row)" class="inline-flex cursor-pointer text-70 hover:text-primary mr-3 has-tooltip" dusk="218-edit-button" data-original-title="null">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current">
                                        <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z">                   
                                        </path>
                                    </svg>
                                </a>
                            </span>
                            <span class="inline-flex">
                                <button @click="removeItem(props.row)" class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3 has-tooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                                        <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                                    </svg>
                                </button>
                            </span>
                        </div>
                        <div v-else>
                            <span v-if="props.row.removing">Removing...</span>
                            <span v-if="props.row.marking">Marking...</span>
                            <span v-if="props.row.paying">Paying...</span>
                        </div>
                    </div>
                    <span v-else-if="props.column.field == 'payment_cta'">
                      
                    </span>
                    <span v-else>
                      {{props.formattedRow[props.column.field]}}
                    </span>
                </template>
            </vue-good-table>
            <select-modal
                :selectClass.sync="selectClass"
                v-show="selectModalShow"
                :modal.sync="selectModalShow"
                :item="payItem"
                title="Please Note!"
                @select="selectAmount"
            >
            </select-modal>
            <pay-modal
                :payClass.sync="payClass"
                v-show="payModalShow"
                :modal.sync="payModalShow"
                title="Payment"
                :item="payItem"
                :paying="paying"
                @confirm="confirmPay"
            >
                <template v-slot:content="{getItem}">
                    <div class="pay-options-container">
                        <div class="flex method-option-row" v-for="(v,kk) in getItem.methodOptions">
                            <div>
                                <p class="flex">
                                    <input :id="`item-pay-${kk}-${getItem.bill_id}`" class="form-control form-input bill-pay-radio" v-model="getItem.method" type="radio" :value="v" />
                                    <label class="label-pay-radio" :for="`item-pay-${kk}-${getItem.bill_id}`"></label>
                                    <span class="text-white">{{ v }}</span>
                                </p>
                            </div>
                            <div class="billpaylist-icon-container">
                                <svg width="65" height="20" viewBox="0 0 122 32" fill="none"><path d="M82.3 6.613c-2.158 0-3.822.769-5.366 2.374v-2h-4.238v19.68h4.56V15.253c0-2.613 1.556-4.266 3.917-4.266.429 0 .805.053 1.073.106l3.165-4.16c-1.234-.213-2.307-.32-3.112-.32zM121.625 6.987h-5.258l-4.855 6.419-4.855-6.42h-5.258l7.418 9.917-7.418 9.764h5.365l4.721-6.226 4.721 6.226h5.419l-7.468-9.748 7.468-9.932zM25.86 2.56c-1.556 2.08-1.932 2.773-3.434 2.773H0V32h8.799c1.985 0 3.916-.96 5.15-2.56 1.61-2.133 1.824-2.773 3.38-2.773h22.48V0h-8.853c-1.985 0-3.916.96-5.097 2.56zm6.974 17.227h-9.389c-2.039 0-3.916.853-5.15 2.506-1.61 2.134-1.932 2.774-3.434 2.774h-7.94v-12.8h9.389c2.038 0 3.916-.96 5.15-2.56 1.61-2.08 2.039-2.72 3.487-2.72h7.887v12.8zM66.526 13.227c1.986-1.12 3.38-3.094 3.38-5.707 0-3.84-2.95-7.52-7.242-7.52H49.788v26.667h12.876c4.667 0 7.886-3.307 7.886-7.467 0-2.453-1.126-4.587-4.024-5.973zM54.348 4.16h7.94c1.985 0 3.165 1.867 3.165 3.52 0 2.027-1.77 3.413-3.433 3.413h-7.672V4.16zm7.672 18.347h-7.672v-7.254h7.94c2.039 0 3.702 1.76 3.702 3.574 0 2.026-2.092 3.68-3.97 3.68z" fill="currentColor"></path><path d="M96.839 21.013c-.751 1.174-2.2 1.867-3.81 1.867-2.52 0-4.613-1.707-4.613-4.32h13.627c.215-.8.268-1.173.268-1.867 0-5.973-3.702-10.08-9.281-10.08-5.473 0-9.335 3.894-9.335 10.24 0 6.187 3.701 10.187 9.442 10.187 4.024 0 7.565-1.973 8.584-6.027h-4.882zm-3.81-10.24c2.522 0 4.454 1.6 4.4 4h-8.96c0-2.346 2.04-4 4.56-4z" fill="currentColor"></path></svg>
                            </div>
                        </div>
                    </div>
                </template>
            </pay-modal>
            <remove-modal
                :removeClass.sync="removeClass"
                v-show="removeModalShow"
                :modal.sync="removeModalShow"
                title="Removal of Bill"
                :item="removeCurrentItem"
                :removing="removing"
                @confirm="confirmRemoveBill"
            >
                <template v-slot:content="{getItem}">
                    <p v-if="!getItem.removing">
                        Are you sure you want to remove "<strong>{{ getItem.reference_num }}</strong>" from the list?
                    </p>
                    <p v-if="getItem.removing">
                        Removing...
                    </p>
                </template>
            </remove-modal>
            <warning-modal
                :warningClass.sync="warningClass"
                v-show="warningModalShow"
                :modal.sync="warningModalShow"
                :title="warningModalTitle"
                :item="currentItem"
            >
                <template v-slot:content="{getItem}">
                    <p v-if="warningModalTitle!=='Not associated with Quickbooks'">
                        This vendor is not associated with any brex vendor.
                    </p>
                    <p v-if="warningModalTitle=='Not associated with Quickbooks'">
                        Your current login account is not associated with quickbooks account or your quickbooks account is not associated with this vendor.
                    </p>
                    <p v-if="getItem.vendor_link!=='' && warningModalTitle!=='Not associated with Quickbooks'">
                        You can associate this vendor by clicking this <a class="no-underline dim text-primary font-bold" :href="`/administrator/resources/vendors/${getItem.vendor_link}/edit?viaResource&viaResourceId&viaRelationship`">link</a>.
                    </p>
                </template>
            </warning-modal>
            <confirm-modal
                :confirmClass.sync="confirmClass"
                v-show="modalShow"
                :modal.sync="modalShow"
                title="Confirmation"
                :item="currentItem"
                :marking="marking"
                @confirm="confirmPayment"
            >
                <template v-slot:content="{getItem}">
                    <p v-if="!marking">
                        Are you sure you want to mark "<strong>{{ getItem.reference_num }}</strong>" as paid?
                    </p>
                    <p v-if="marking">
                        Marking...
                    </p>
                </template>
            </confirm-modal>
            <pagination
                :items="items"
                :from="from"
                :to="to"
                :total="total"
                :firstLoad="firstLoad"
                :nextPageUrl="nextPageUrl"
                :prevPageUrl="prevPageUrl"
                @nextPage="nextPage"
                @prevPage="prevPage"
            >
                <template slot="previous">
                    <span>Previous</span>
                </template>
                <template slot="next">
                    <span>Next</span>
                </template>
            </pagination>
        </div>
    </div>
</template>

<script>
import { VueGoodTable } from 'vue-good-table'
import 'vue-good-table/dist/vue-good-table.css'
import { mapActions, mapGetters } from 'vuex'
import _ from 'lodash'
import SelectModal from './Modals/SelectModal'
import ConfirmModal from './Modals/ConfirmModal'
import RemoveModal from './Modals/RemoveModal'
import WarningModal from './Modals/WarningModal'
import PayModal from './Modals/PayModal'
import Pagination from './Pagination/Pagination'
import Search from './Search/Search'
import iziToast from 'izitoast'
import vSelect from 'vue-select'
import 'izitoast/dist/css/iziToast.css'
import 'vue-select/dist/vue-select.css'

export default {
    metaInfo() {
        return {
          title: 'BillPaylist',
        }
    },
    components:{
        VueGoodTable,
        Pagination,
        Search,
        ConfirmModal,
        WarningModal,
        RemoveModal,
        PayModal,
        SelectModal,
        'v-select': vSelect
    },
    data() {
        return {
            balance_content: '',
            amount_to_pay: 0,
            selectClass: '',
            payClass: '',
            confirmClass: '',
            removeClass: '',
            warningClass: '',
            marking: false,
            removing: false,
            paying: false,
            warningModalTitle: '',
            warningModalShow: false,
            removeModalShow: false,
            selectModalShow: false,
            payModalShow: false,
            currentSelectedBillLine: 0,
            payItem: {},
            currentItem: {},
            removeCurrentItem: {},
            modalShow: false,
            searchQuery: '',
            firstLoad: false,
            removeSuccessMessage: '<h4 style="font-weight: 500 !important;">Success</h4><small>Bill was successfully removed from the list.</small>',
            globalSuccessMessage: '<h4 style="font-weight: 500 !important;">Success</h4><small>Bill was successfully marked as paid.</small>',
            globalErrorMessage: '<h4 style="font-weight: 500 !important;">An error occured while trying to process your request.</h4><small>Please try again</small>',
            columns:[
                { label: 'Shipment Ref #', field: 'shifl_ref', sortable: false},
                { label: 'Account Rep', field: 'account_representative_email', sortable: false},
                { label: 'Customer', field: 'customer_name', sortable: false},
                { label: 'Overdue Balance', field: 'overdue_balance', sortable: false},
                { label: 'Last Payment', field: 'last_payment', sortable: false},
                { label: 'Vendor', field: 'vendor_name', sortable: false},
                { label: 'Vendor Ref #', field: 'reference_num', sortable: false},
                { label: 'ETA', field: 'eta', sortable: true},
                { label: 'Amount', field: 'total_amount', sortable: false},
                { label: 'Status', field: 'payment_status', sortable: false},
                { label: 'Actions', field: 'payment_cta', sortable: false}
            ],
            typingTimeout: 0,
            serverParams: {
              columnFilters: {
              },
              sort: [
                {
                  field: 'eta',
                  type: 'desc'
                }
              ],
              page: 1, 
              perPage: 50
            }
        }
    },
    computed: {
        ...mapGetters({
            currentBillLines: 'billPaymentList/getCurrentBillLines',
            accounts: 'account/getItems',
            accountsLoading: 'account/getItemsLoading',
            items: 'billPaymentList/getItems',
            itemsLoading: 'billPaymentList/getItemsLoading',
            currentPage: 'billPaymentList/getCurrentPage',
            nextPageUrl: 'billPaymentList/getNextPageUrl',
            prevPageUrl: 'billPaymentList/getPrevPageUrl',
            from: 'billPaymentList/getFrom',
            to: 'billPaymentList/getTo',
            total: 'billPaymentList/getTotal'

        }),
        emptyItemsText() {
            return (this.itemsLoading) ? 'Loading bills. Please wait...' : 'No bills available to display for your account.'
        },
        itemsFiltered() {
            let items = this.items
            let newItems = []
            if (items.length > 0) {
                items.map( item => {
                    item.marking = false
                    item.removing = false
                    item.paying = false
                    item.method = 'Brex Payment'
                    item.methodOptions= ['Brex Payment']
                    //if (item.payment_status==1 || item.payment_status==2)
                    if (item.payment_status==1 || item.payment_status==3)
                        newItems.push(item)
                })
            }
            return newItems
        }
    },
    methods: {
        ...mapActions({
            payBill: 'billPaymentList/payBill',
            fetchAccounts: 'account/fetchAccounts',
            fetchItems: 'billPaymentList/fetchItems',
            markAsPaid: 'billPaymentList/markAsPaid',
            removeBill: 'billPaymentList/removeBill',
            setPage: 'billPaymentList/setPage',
            fetchPage: 'billPaymentList/fetchPage',
            setItemsLoading: 'billPaymentList/setItemsLoading',
            searchItem: 'billPaymentList/search',
            getBillLines: 'billPaymentList/getBillLines'
        }),
        selectAmount() {
            this.selectClass = 'slide-out-left'
            setTimeout(() => {
                this.selectClass = ''
                this.selectModalShow = false
                this.payModalShow = true
                this.payClass = 'tilt-in-fwd-tr'
            },750)
        },
        selectBillLine() {

            this.selectClass = 'slide-out-elliptic-top-bck'
            setTimeout(() => {
                this.selectClass = ''
                this.selectModalShow = false
                this.payModalShow = true
                this.payClass = 'tilt-in-fwd-tr'
            },750)
            
        },
        updateParams(newProps) {
          this.serverParams = Object.assign({}, this.serverParams, newProps);
        },
        async onSortChange(params) {
          this.updateParams({
            sort: [{
              type: params[0].type,
              field: 'eta',
            }],
          })
          await this.fetchItems({params: this.serverParams})
        },
        async searchItemNow() {
            
            clearTimeout(this.typingTimeout)

            this.typingTimeout = setTimeout(() => {
                this.searchItem({qry: this.searchQuery, params: this.serverParams})
            },800)
        },
        async nextPage() {
            this.serverParams.page++
            await this.fetchPage({url: this.nextPageUrl, params: this.serverParams})

        },
        async prevPage() {
            this.serverParams.page--
            await this.fetchPage({url: this.prevPageUrl, params: this.serverParams})
        },
        async search() {


        },
        alertMessage(success, message) {

            if (success) {
                iziToast.success({
                    theme: 'dark',
                    message,
                    backgroundColor: '#16B442',
                    messageColor: '#ffffff',
                    iconColor: '#ffffff',
                    progressBarColor: '#ADEEBD',
                    displayMode: 1,
                    position: 'topRight',
                    timeout: 3000,
                })
            } else {
                iziToast.warning({
                    theme: 'dark',
                    title: 'Warning',
                    message,
                    displayMode: 1,
                    position: 'bottomCenter',
                    timeout: 5000,
                })
            }
            
        },
        async confirmPay() {
            //this.modalShow = false
            let item = this.payItem
            if (!item.paying) {
                 this.removing = true
                try {
                    //mark state true
                    item.paying = true
                    let payload = {
                        bill_id: item.bill_id
                    }

                    const response = await this.payBill(payload)
                    this.payClass = 'slide-out-elliptic-top-bck'
                    setTimeout(() => {
                        this.payClass = ''
                        this.payModalShow = false
                    },750)

                    this.paying = false
                    //mark state false
                    item.paying = false

                    if (response.status=='not ok') {
                        if (typeof response.no_brex!=='undefined') {
                            this.alertMessage(false, 'This vendor is not associated with Brex Vendor.')
                        } else {
                            this.alertMessage(false, this.globalErrorMessage)
                        }
                        
                    } else {
                        this.alertMessage(true, this.removeSuccessMessage)
                        //mark as paid
                        let findIndex = _.findIndex(this.items, e => (e.bill_id == item.bill_id))
                        this.items.splice(findIndex, 1)
                    }
                }catch(e) {
                    item.removing = false
                    console.log(e)
                }
            }
        },
        async confirmRemoveBill() {
            
           
            //this.modalShow = false
            let item = this.removeCurrentItem

            if (!item.removing) {
                 this.removing = true
                try {
                    let item = this.removeCurrentItem
                    //mark state true
                    item.removing = true
                    let payload = {
                        bill_id: item.bill_id
                    }

                    const response = await this.removeBill(payload)

                    this.removeClass = 'slide-out-elliptic-top-bck'
                    setTimeout(() => {
                        this.removeModalShow = false
                        this.removeClass = ''
                    },750)

                    
                    this.removing = false
                    //mark state false
                    item.removing = false

                    if (response.status=='not ok') {
                        this.alertMessage(false, this.globalErrorMessage)
                    } else {
                        this.alertMessage(true, this.removeSuccessMessage)
                        //mark as paid
                        let findIndex = _.findIndex(this.items, e => (e.bill_id == item.bill_id))
                        this.items.splice(findIndex, 1)
                    }
                    
                }catch(e) {
                    item.removing = false
                    console.log(e)
                }
            }
            
        },
        async confirmPayment() {
            
            
            //this.modalShow = false
            let item = this.currentItem

            if (!item.marking) {
                this.marking = true

                try {
                    let item = this.currentItem
                    //mark state true
                    item.marking = true
                    let { payment_status, ...otherProps} = item
                    payment_status = 2
                    let payload = {
                        payment_status,
                        ...otherProps
                    }
                    const response = await this.markAsPaid(payload)

                    this.confirmClass = 'slide-out-elliptic-top-bck'
                    setTimeout(() => {
                        this.modalShow = false
                        this.confirmClass = ''
                    },750)

                    this.marking = false
                    //mark state false
                    item.marking = false

                    if (response.status=='not ok') {
                        this.alertMessage(false, this.globalErrorMessage)

                    } else {
                        this.alertMessage(true, this.globalSuccessMessage)
                        //mark as paid
                        item.payment_status = 2
                        let findIndex = _.findIndex(this.items, e => (e.bill_id == item.bill_id))
                        this.items.splice(findIndex, 1)
                    }
                    
                }catch(e) {
                    item.marking = false
                    console.log(e)
                }
            }
            
        },
        removeItem(item) {
            if (!item.removing) {
                this.removeClass = 'tilt-in-fwd-tr'
                this.removeModalShow = true
                this.removeCurrentItem = item
            }
        },

        pay(item) {

            this.currentItem = item
            if (!item.paying) {


                if (typeof this.currentItem.connected_qb!=='undefined') {
                    if ( !this.currentItem.connected_qb ) {
                        this.warningModalTitle = 'Not associated with Quickbooks'
                        this.warningClass = 'slide-in-right'
                        this.warningModalShow = true
                        return false
                    }
                }
                if (typeof this.currentItem.is_integrated_brex!=='undefined' && !this.currentItem.is_integrated_brex) {
                    
                    this.warningModalTitle = 'Not Associated with Brex Vendor'
                    this.warningClass = 'slide-in-right'
                    this.warningModalShow = true
                    return false
                }

                let payload = {
                    bill_id: item.qbo_bill_id
                }

                if (typeof item.balance_pay!=='undefined') {
                    
                    let parse_balance = parseFloat(item.balance_pay)
                    let total_amount = parseFloat(item.total_amount)
                        

                    if (parse_balance< total_amount) {
                        this.payItem = item
                        this.selectClass = 'slide-in-right'
                        this.selectModalShow = true
                    } else {
                        this.payClass = 'tilt-in-fwd-tr'
                        this.payModalShow = true
                        this.payItem = item
                    }


                }
                //this.getBillLines(payload)
                //this.selectClass = 'tilt-in-fwd-tr'
                //this.currentSelectedBillLine = 0
                //this.selectModalShow = true
                
                /*
                this.payClass = 'tilt-in-fwd-tr'
                this.payModalShow = true
                this.payItem = item
                */
            }            
        },
        mark(item) {

            if (!item.marking) {
                this.confirmClass = 'tilt-in-fwd-tr'
                this.modalShow = true
                this.currentItem = item
            }
            
        }
    },
    async mounted() {
        //
        try {
            await this.fetchItems({params: this.serverParams})
            this.firstLoad = true
        } catch(e) {
            this.setItemsLoading(false)
            console.log(e)
        }        

    },
}
</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

    .style-chooser {
        display:  none !important;
    }
    .v-select #vs1__combobox,
    .v-select .vs__dropdown-menu
     {
        position:  relative !important;
        width: 65% !important;
        background-color: white !important;
        margin: 0px auto !important;
    }

    .tilt-in-fwd-tr {
        -webkit-animation: tilt-in-fwd-tr 0.6s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
            animation: tilt-in-fwd-tr 0.6s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
    }

    .slide-out-elliptic-top-bck {
        -webkit-animation: slide-out-elliptic-top-bck 0.7s ease-in both;
                animation: slide-out-elliptic-top-bck 0.7s ease-in both;
    }

    .roll-in-right {
        -webkit-animation: roll-in-right 0.6s ease-out both;
            animation: roll-in-right 0.6s ease-out both;
    }

    .slide-in-right {
        -webkit-animation: slide-in-right 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
            animation: slide-in-right 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
    }

    .slide-out-left {
        -webkit-animation: slide-out-left 0.5s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
            animation: slide-out-left 0.5s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
    }

    @-webkit-keyframes slide-out-left {
      0% {
        -webkit-transform: translateX(0);
                transform: translateX(0);
        opacity: 1;
      }
      100% {
        -webkit-transform: translateX(-1000px);
                transform: translateX(-1000px);
        opacity: 0;
      }
    }
    @keyframes slide-out-left {
      0% {
        -webkit-transform: translateX(0);
                transform: translateX(0);
        opacity: 1;
      }
      100% {
        -webkit-transform: translateX(-1000px);
                transform: translateX(-1000px);
        opacity: 0;
      }
    }

    @-webkit-keyframes slide-in-right {
      0% {
        -webkit-transform: translateX(1000px);
                transform: translateX(1000px);
        opacity: 0;
      }
      100% {
        -webkit-transform: translateX(0);
                transform: translateX(0);
        opacity: 1;
      }
    }
    @keyframes slide-in-right {
      0% {
        -webkit-transform: translateX(1000px);
                transform: translateX(1000px);
        opacity: 0;
      }
      100% {
        -webkit-transform: translateX(0);
                transform: translateX(0);
        opacity: 1;
      }
    }


    @-webkit-keyframes roll-in-right {
      0% {
        -webkit-transform: translateX(800px) rotate(540deg);
                transform: translateX(800px) rotate(540deg);
        opacity: 0;
      }
      100% {
        -webkit-transform: translateX(0) rotate(0deg);
                transform: translateX(0) rotate(0deg);
        opacity: 1;
      }
    }
    @keyframes roll-in-right {
      0% {
        -webkit-transform: translateX(800px) rotate(540deg);
                transform: translateX(800px) rotate(540deg);
        opacity: 0;
      }
      100% {
        -webkit-transform: translateX(0) rotate(0deg);
                transform: translateX(0) rotate(0deg);
        opacity: 1;
      }
    }

    @-webkit-keyframes tilt-in-fwd-tr {
      0% {
        -webkit-transform: rotateY(20deg) rotateX(35deg) translate(300px, -300px) skew(-35deg, 10deg);
                transform: rotateY(20deg) rotateX(35deg) translate(300px, -300px) skew(-35deg, 10deg);
        opacity: 0;
      }
      100% {
        -webkit-transform: rotateY(0) rotateX(0deg) translate(0, 0) skew(0deg, 0deg);
                transform: rotateY(0) rotateX(0deg) translate(0, 0) skew(0deg, 0deg);
        opacity: 1;
      }
    }

    @keyframes tilt-in-fwd-tr {
      0% {
        -webkit-transform: rotateY(20deg) rotateX(35deg) translate(300px, -300px) skew(-35deg, 10deg);
                transform: rotateY(20deg) rotateX(35deg) translate(300px, -300px) skew(-35deg, 10deg);
        opacity: 0;
      }
      100% {
        -webkit-transform: rotateY(0) rotateX(0deg) translate(0, 0) skew(0deg, 0deg);
                transform: rotateY(0) rotateX(0deg) translate(0, 0) skew(0deg, 0deg);
        opacity: 1;
      }
    }

    @-webkit-keyframes slide-out-elliptic-top-bck {
      0% {
        -webkit-transform: translateY(0) rotateX(0) scale(1);
                transform: translateY(0) rotateX(0) scale(1);
        -webkit-transform-origin: 50% 1400px;
                transform-origin: 50% 1400px;
        opacity: 1;
      }
      100% {
        -webkit-transform: translateY(-600px) rotateX(-30deg) scale(0);
                transform: translateY(-600px) rotateX(-30deg) scale(0);
        -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
        opacity: 1;
      }
    }

    @keyframes slide-out-elliptic-top-bck {
      0% {
        -webkit-transform: translateY(0) rotateX(0) scale(1);
                transform: translateY(0) rotateX(0) scale(1);
        -webkit-transform-origin: 50% 1400px;
                transform-origin: 50% 1400px;
        opacity: 1;
      }
      100% {
        -webkit-transform: translateY(-600px) rotateX(-30deg) scale(0);
                transform: translateY(-600px) rotateX(-30deg) scale(0);
        -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
        opacity: 1;
      }
    }
     
</style>

