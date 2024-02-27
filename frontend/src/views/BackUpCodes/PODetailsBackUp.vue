<template>          
    <div class="po-details-wrapper">
        <div class="preloader" v-if="getPoDetailLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate >
            </v-progress-circular>
        </div>

        <div v-if="!getPoDetailLoading">
            <div class="details-breadcrumbs">
                <router-link to="/pos" class="po-link">
                    POs
                </router-link>
                <span class="right-chevron"> 
                    <img src="../assets/images/right_chevron.svg"> 
                </span>

                <span class="po-ref">
                    {{ getPoDetail.po_number }}
                </span>
            </div>

            <div id="top-header" v-resize="onResize">
                <div class="reference-status">
                    <div class="d-flex align-center">
                        <h2> PO #{{ getPoDetail.po_number }} </h2>

                        <span class="btn poDetail-status ml-2"> 
                            {{ getPoDetail.status === 'partial_shipped' ? 'Partially Shipped' : getPoDetail.status }}
                        </span>
                    </div>

                    <div class="place-wrapper mt-2" v-if="isMobile">
                        <div class="place-content">
                            <p class="heading">
                                <span class="info-title">Issue at: </span> 
                                {{ getDateFormat(getPoDetail.created_at) }}
                            </p>

                            <div class="carrier-wrapper">
                                <p class="heading">                                        
                                    <span class="info-title">Updated at: </span> 
                                    {{ getDateFormat(getPoDetail.updated_at) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="header-buttons">
                        <button class="btn-blue" @click="editPo(getPoDetail)" v-if="getPoDetail.status !== 'shipped'">
                            <img src="@/assets/icons/edit-white.svg" width="16px" height="16px" class="mr-1"/> Edit
                        </button>

                        <button class="btn-white email" @click="emailPo(getPoDetail)">
                            <img src="@/assets/icons/email-blue.svg" width="16px" height="16px" class="mr-1"/> Email
                        </button>

                        <button class="btn-white" @click="download(getPoDetail)" :disabled="getDownloadLoading"
                            v-if="!isMobile">
                            <img src="@/assets/icons/download.svg" width="14px" height="14px" class="mr-1"/> 
                            {{ getDownloadLoading ? 'Downloading...' : 'Download'}}
                        </button>

                        <v-menu offset-y left content-class="po-details-more">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn class="btn-white dots" v-bind="attrs" v-on="on">
                                    <img src="@/assets/icons/dots.svg"/>
                                </v-btn>
                            </template>

                            <v-list class="po-details-lists">
                                <v-list-item @click="download(getPoDetail)" v-if="isMobile">
                                    <v-list-item-title>
                                        <img src="@/assets/icons/download.svg" width="14px" height="14px" class="mr-1"/>
                                        Download
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="deletePO(getPoDetail)">
                                    <v-list-item-title class="delete">
                                        <img src="@/assets/icons/delete-po.svg" />
                                        Delete
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>
                </div>

                <div class="place-wrapper" v-if="!isMobile">
                    <div class="place-content">
                        <p class="heading">
                            <span class="info-title">Issue at: </span> 
                            {{ getDateFormat(getPoDetail.created_at) }}
                        </p>

                        <div class="carrier-wrapper">
                            <p class="heading">                                        
                                <span class="info-title">Updated at: </span> 
                                {{ getDateFormat(getPoDetail.updated_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="poDetail-top-content">
                <v-row>
                    <v-col cols="12" sm="4">
                        <div class="po-info po-vendor mb-3">
                            <p class="po-title">VENDOR</p>
                            <p class="po-data">
                                {{ getVendor(getPoDetail.supplier_id) }}
                            </p>
                        </div>

                        <div class="po-info po-buyer">
                            <p class="po-title">BUYER</p>
                            <p class="po-data">
                                {{ getUserId(getPoDetail.customer_id) }}
                            </p>
                        </div>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <div class="po-info po-ship-to mb-3">
                            <p class="po-title">SHIP TO</p>
                            <!-- <p class="po-data" v-html="getWarehouseAddress(getPoDetail.warehouse_id)"></p> -->
                            <p class="po-data">{{ getPoDetail.ship_to }}</p>
                        </div>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <div class="po-info po-ship-via dFlex mb-1">
                            <p class="po-title">SHIP VIA</p>
                            <p class="po-data">
                                {{ getPoDetail.ship_via !== null ? getPoDetail.ship_via : 'N/A' }}
                            </p>
                        </div>

                        <!-- <div class="po-info po-method dFlex mb-1">
                            <p class="po-title">PACKING METHOD</p>
                            <p class="po-data">
                                {{ getPoDetail.packing_method !== null ? getPoDetail.packing_method : 'N/A' }}
                            </p>
                        </div> -->

                        <div class="po-info po-method dFlex mb-1">
                            <p class="po-title">SHIPPING TERM</p>
                            <p class="po-data">
                                {{ getPoDetail.terms !== null ? getPoDetail.terms : 'N/A' }}
                            </p>
                        </div>

                        <div class="po-info po-method dFlex mb-1">
                            <p class="po-title">PAYMENT TERMS</p>
                            <p class="po-data">
                                {{ getPoDetail.payment_term !== null ? getPoDetail.payment_term : 'N/A' }}
                            </p>
                        </div>

                        <div class="po-info po-method dFlex mb-1">
                            <p class="po-title">CARGO READY</p>
                            <p class="po-data">
                                {{ getDateFormat(getPoDetail.cargo_ready_date) }}
                            </p>
                        </div>
                    </v-col>
                </v-row>
            </div>

            <div class="po-details-tabs">
                <v-tabs @change="onTabChange" v-model="currentTab">
                    <v-tab 
                        v-for="(n, i) in tabs" 
                        :key="i"
                        @click="getCurrentTab(i)" >
                        {{ n }}
                    </v-tab>
                </v-tabs>
            </div>

            <!-- TABS COMPONENTS -->
            <PoDetailProductsDesktopTable v-if="currentTab === 0" :isMobile="isMobile" />
            <PoDetailShipmentsDesktopTable v-if="currentTab === 1" :isMobile="isMobile" />
        </div>

        <POCreateDialog 
            :dialog.sync="dialogEditPo"
            :editedIndex.sync="editedPoIndex"
            :editedItems.sync="editedPoItems"
            @close="closePoEdit"
            fromComponent="po-details-page"
            :isMobile="isMobile" />
        
        <PoEmail 
            :currentpo_id.sync="po_id"
            :dialog.sync="dialogEmail"
            :editedItems.sync="editedEmailItem"
            :editedIndex.sync="editedIndexEmail"
            :isMobile="isMobile"
            @close="closeEmail" />

        <DeleteDialog 
            :dialogData.sync="dialogPoDelete"
            :editedItemData.sync="currentPoToDelete"
            :editedIndexWarehouse.sync="editedPoIndex"
            :defaultItemWarehouse.sync="defaultPoItems"
            @delete="deletePoConfirm"
            @close="closePoDelete"
            fromComponent="purchase order"
            :loadingDelete="getPoDeleteLoading"
            componentName="Purchase Orders" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import moment from 'moment'
import PoDetailProductsDesktopTable from "../components/Tables/POsDetailTabs/PoDetailProductsDesktopTable.vue";
import PoDetailShipmentsDesktopTable from "../components/Tables/POsDetailTabs/PoDetailShipmentsDesktopTable.vue";
import POCreateDialog from '../components/PosComponents/Dialog/POCreateDialog.vue'
import PoEmail from '../components/PosComponents/Dialog/PoEmail.vue'
import DeleteDialog from '../components/Dialog/DeleteDialog.vue'
import _ from 'lodash'
import axios from 'axios'
import globalMethods from '../utils/globalMethods'

export default {
    components: {
        PoDetailProductsDesktopTable,
        PoDetailShipmentsDesktopTable,
        POCreateDialog,
        PoEmail,
        DeleteDialog
    },
    data: () => ({
        tabs: ["Products", "Shipments"],
        currentTab: 0,    
        isMobile: false,
        po_id: null,
        detailsLoading: true,
        items: [
            {
                text: "Po",
                disabled: false,
                href: "/pos",
            },
            {
                text: '#',
                disabled: true,
                href: "breadcrumbs_link_1",
            },
        ],
        headers: [
			{
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'sku',
				fixed: true,
				width: "15%"
			},
			{ 
				text: 'Description',
				align: 'start',
				sortable: false,
				value: 'description',
				fixed: true,
				width: "40%"
			},
			{ 
				text: 'Quantity',
				align: 'end',
				sortable: false,
				value: 'quality',
				fixed: true,
				width: "15%"
			},
			{ 
				text: 'Unit Price',
				align: 'end',
				sortable: false,
				value: 'unit_price',
				fixed: true,
				width: "15%"
			},
			{ 
				text: 'Amount',
				align: 'end',
				sortable: false,
				value: 'amount',
				fixed: true,
				width: "15%"
			},
		],
        dialogEditPo: false,
        editedPoIndex: 0,
        editedPoItems: {},
        defaultPoItems: {},
        dialogPoDelete: false,
        currentPoToDelete: null,
        dialogEmail: false,
        editedIndexEmail: -1,
        editedEmailItem: {
            emails: [],
            po: {}
        },
        defaultEmailItem: {
            emails: [],
            po: {}
        }
    }),
    computed: {
        ...mapGetters({
            getAllPo: 'po/getAllPo',
            getPoDetailLoading: 'po/getPoDetailLoading',
            getPoDetail: 'po/getPoDetail',
            getWarehouse: 'warehouse/getWarehouse',
            getVendorLists: 'po/getVendorLists',
            getUser: 'getUser',
            getDownloadLoading: 'po/getDownloadLoading',
            getPoDeleteLoading: 'po/getPoDeleteLoading',
            getAllPoPending: 'po/getAllPoPending',
            getPoLocalQuery: 'po/getPoLocalQuery',
            getPendingPage: 'po/getPendingPage',
        }),
        posPending() {
            let posData = []

            if (typeof this.getAllPoPending !== 'undefined' && this.getAllPoPending !== null) {
				if (typeof this.getAllPoPending.results !== 'undefined' && this.getAllPoPending.results !== null) {
                    posData = this.getAllPoPending.results.data
				}
			}

			return posData
        },
    },
    methods:{
        ...mapActions({
            getPo: 'po/getPo',
            fetchWarehouse: 'warehouse/fetchWarehouse',
            fetchVendorLists: 'po/fetchVendorLists',
            downloadPo: 'po/downloadPo',
            deletePo: 'po/deletePo',
            fetchPoShipmentDetails: 'po/fetchPoShipmentDetails',
            fetchTerms: "fetchTerms",
            //fetchPoPending: 'po/fetchPoPending',
           fetchPoPendingNew: 'po/fetchPoPendingNew'
        }),
        ...globalMethods,
        onResize() {
            if (window.innerWidth < 960) {
                this.isMobile = true;
            } else {
                this.isMobile = false;
            }
        },
        async loadPoMetaData() {
            try {                
                await this.getPo(this.po_id)

                if (typeof this.getPoDetail !== 'undefined' && this.getPoDetail !== null) {
                    let { supplier_id, customer_id, id, po_number } = this.getPoDetail
                    let payload = { po_id: id, supplier_id, customer_id, po_number }
                    await this.fetchPoShipmentDetails(payload)
                }
                /*
                await this.fetchPoPendingNew({
                    page: 1
                })*/

                //await this.fetchPoPending(1)
                await this.fetchWarehouse()
                await this.fetchVendorLists()
                this.detailsLoading = false
            } catch(e) {
                console.log(e)
                this.detailsLoading = false
            }
        },
        getDateFormat(date) {
            return moment(date).format('MMM DD, YYYY');
        },
        onTabChange(i) {
            this.currentTab = i;
        },
        getCurrentTab(i) {
            this.currentTab = i;
        },
        getVendor(id) {
            if ( Array.isArray(this.getVendorLists) && this.getVendorLists.length > 0) {
                let findVendor = _.find(this.getVendorLists, (e => e.id === id))
                if ( typeof findVendor!=='undefined' ) {
                    return findVendor.company_name
                }
            }

            return '--'
        },
        getWarehouseAddress(id) {
            if (typeof this.getWarehouse !== 'undefined' && this.getWarehouse !== null &&
                typeof this.getWarehouse.results !== 'undefined' && this.getWarehouse.results !== null &&
                this.getWarehouse.results.length !== 'undefined') {
                    
                    let getData = this.getWarehouse.results
                    let findSizeValue = id !== 'undefined' ? _.find(getData, (e) => (e.id == id)) : ''

                    if (typeof findSizeValue !== 'undefined') {
                        if (findSizeValue.address !== 'undefined') {
                            return `<span>${findSizeValue.name}</span><br/><span>${findSizeValue.address}</span>`;
                        }
                    } else {
                        return '--'
                    }
            }
        },
        getUserId(id) {
            if (typeof this.getUser !== 'undefined' && typeof this.getUser == 'string') {
                let parsedData = JSON.parse(this.getUser)
                
                if (parsedData.customers_api !== 'undefined' && Array.isArray(parsedData.customers_api) && 
                    parsedData.customers_api.length > 0) {

                    let findCustomer = _.find(parsedData.customers_api, (e => e.id === id))
                    if (typeof findCustomer!=='undefined' ) {
                        return findCustomer.company_name
                    }
                }
            }

            return '--'
        },
        async fetchSingleProduct(id) {
            try {
                const res = await axios.get(`${this.poBaseUrlState}/products/${id}`)
                if (res.status === 200) {
                    if (typeof res.data!=='undefined') {
                        if (typeof res.data.unit_price!=='undefined' && res.data.unit_price!=='' && res.data.unit_price!==null) {
                            return Promise.resolve(res.data.unit_price)    
                        } else {
                            return Promise.resolve(0)
                        }
                    } else {
                        return Promise.resolve(0)
                    }
                } else {
                    return Promise.resolve(0)
                }
            } catch(e) {

                if (typeof e.message!=='undefined' && e.message =='UnAuthenticated') {
                    this.$router.push('/login')
                } else {
                    return Promise.reject(0)
                }
            }            
        },
        processSingleProduct(getIndex, context, po) {
            if (po.purchase_order_products[getIndex]) {
                let ipp = po.purchase_order_products[getIndex]

                po.purchase_order_products[getIndex] = {
                    id: ipp.product_id,
                    // quantity: ipp.quantity,
                    carton_count: ipp.quantity,
                    units: ipp.units,
                    amount: ipp.amount,
                    product_id: ipp.product_id
                }

                let unit_price = ipp.unit_price

                if (unit_price===null || unit_price==='' || ipp.unit_price==0) {
                    context.fetchSingleProduct(ipp.product_id).then( data => {
                        unit_price = (typeof data.unit_price!=='undefined') ? data.unit_price : unit_price
                        unit_price = (unit_price==null || unit_price=='') ? 0 : unit_price
                        po.purchase_order_products[getIndex].unit_price = unit_price

                        this.processSingleProduct(++getIndex, context, po)
                    }).catch(e => {
                        console.log(e)
                        po.purchase_order_products[getIndex].unit_price = 0
                        this.processSingleProduct(++getIndex, context, po)
                    })
                } else {
                    po.purchase_order_products[getIndex].unit_price = (unit_price==null || unit_price=='') ? 0 : unit_price
                    this.processSingleProduct(++getIndex, context, po)
                }
            } else {
                po.loadingState = false
                po.products = po.purchase_order_products
                this.editedPoItems = Object.assign({}, po)
            }
        },
        async editPo() {
            let po = this.getPoDetail
            this.dialogEditPo = true
            this.editedPoIndex = 100000000

            if (this.hasMounted) {
                /*
                this.fetchPoPendingNew({
                    page: 1
                }).catch(e => {
                    if (e=='UnAuthenticated') {
                        window.location.href = '/login'
                    }
                })*/
            }

            if (this.posPending.length > 0) {
                this.editedPoIndex = _.findIndex(this.posPending, e => (e.id === po.id))
            }
            po.products = []
            // po.loadingState = true

            if (typeof po.purchase_order_products!=='undefined' && Array.isArray(po.purchase_order_products) && 
                po.purchase_order_products.length > 0) {
                // let index = 0
                // this.processSingleProduct(index, this, po)
                
                po.products = []
                po.purchase_order_products.map(v => {
                    po.products.push({
                        id: v.product_id,
                        carton_count: v.quantity,
                        units: v.units,
                        amount: v.amount,
                        product_id: v.product_id,
                        unit_price: v.unit_price,
                        units_per_carton: v.units_per_carton
                    })
                })

                this.editedPoItems = Object.assign({}, po)
            } else {
                // po.loadingState = false
                this.editedPoItems = Object.assign({}, po)
            }  
        },
        closePoEdit() {
            this.dialogEditPo = false

			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.getPoDetail)
                this.editedPoIndex = 0
			})
        },
        emailPo(po) {
            this.dialogEmail = true
            this.editedPoIndex = -1
            this.editedEmailItem.po = po

            if (Array.isArray(this.getVendorLists) && this.getVendorLists.length > 0) {
                let findVendor = _.find(this.getVendorLists, (e => e.id === po.supplier_id))
                if ( typeof findVendor!=='undefined' ) {
                    this.editedEmailItem.emails = findVendor.emails
                }
            }
        },
        closeEmail() {
            this.dialogEmail = false
            this.editedPoIndex = -1
            this.editedEmailItem = {
                emails: [],
                po: {}
            }
        },
        async download(po) {
            try {
                await this.downloadPo(po)
                this.notificationCustom('File has been downloaded.')
            } catch(e) {
                this.notificationError(e)
            }
        },
        deletePO(po) {
            this.dialogPoDelete = true
            this.currentPoToDelete = po
            this.currentPoToDelete.name = po.po_number
        },
        async deletePoConfirm() {
            try {
                await this.deletePo(this.currentPoToDelete.id)
                this.notificationCustom('Purchase order successfully deleted.')
                this.closePoDelete()
                this.$router.push(`/pos`)
                
                await this.fetchPoPendingNew({
                    page: this.getPendingPage
                })
            } catch(e) {
                this.closePoDelete()
                this.notificationError(e)
            }
        },
        closePoDelete() {
            this.dialogPoDelete = false
            this.currentPoToDelete = null
        }
    },
    async mounted() {    
        this.$store.dispatch("page/setPage","pos")
        this.po_id = this.$route.params.id
        this.loadPoMetaData()
        await this.fetchTerms()
    },
    async updated() {
        if (this.po_id !== this.$route.params.id) {
            this.po_id = this.$route.params.id
            this.loadPoMetaData()
        }
    },
}
</script>

<style lang="scss">
@import '../assets/scss/pages_scss/po/poDetails.scss';
@import "../assets/scss/buttons.scss";
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../assets/scss/pages_scss/po/poEmailDialog.scss';
</style>
