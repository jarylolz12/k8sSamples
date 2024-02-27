<template>
	<div class='purchase-wrapper' v-resize="onResize">
        <!-- Desktop -->
        <PODesktopTable
            :selectedPO.sync="selected"
            @createPo="createPo"
            @editPo="checkEditPo"
            @email="email"
            @viewPo="openPoView"
            @deletePo="openDelete"
            @deleteMultiple="deleteMultiple"
            @downloadMultiple="downloadMultiple"
            @clearSelectedPO="clearSelectedPO"
            :isMobile="isMobile"
            v-if="!isMobile" />

		<!-- Mobile -->	
        <POMobileTable
            :selectedPO.sync="selected"
            @createPo="createPo"
            @editPo="checkEditPo"
            @email="email"
            @viewPo="openPoView"
            @deletePo="openDelete"
            v-if="isMobile"
            :isMobile="isMobile"/>

        <POCreateDialog 
            :dialog.sync="dialogCreatePo"
            :editedIndex.sync="editedPoIndex"
            :editedItems.sync="editedPoItems"
            @close="closePoCreate"
            fromComponent="po-desktop"
            :isMobile="isMobile" />
            
        <PoEmail 
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

        <POWarning 
            :dialogData.sync="dialogWarning"
            :poData.sync="poData"
            @editPo="editPo"
            @close="closeWarning" />
	</div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import PODesktopTable from '../components/Tables/POs/PODesktopTable.vue'
import POMobileTable from '../components/Tables/POs/POMobileTable.vue'
import POCreateDialog from '../components/PosComponents/Dialog/POCreateDialog.vue'
import PoEmail from '../components/PosComponents/Dialog/PoEmail.vue'
import DeleteDialog from '../components/Dialog/DeleteDialog.vue'
import globalMethods from '../utils/globalMethods'
import axios from 'axios'
import _ from 'lodash'
import POWarning from '../components/PosComponents/Dialog/POWarning.vue'

export default {
	name: "POs",
	components: {
        PODesktopTable,
        POMobileTable,
        POCreateDialog,
        PoEmail,
        DeleteDialog,
        POWarning
	},
	data: () => ({
        hasMounted: false,
        dialogCreatePo: false,
        editedPoIndex: -1,
        editedPoItems: {
            products: [{
                id: null,
                // quantity: 0,
                carton_count: 0,
                units: 0,
                unit_price: 0,
                amount: 0,
                units_per_carton: 0
            }],
            po_number: '',
			is_system_generated: 1,
			supplier_id: '',
			customer_id: '',
			notes: '',
			created_by: '',
			tax: 0,
			warehouse_id: '',
			sub_total: '',
			shipping: 0,
			total: '',
			discount: 0,
            ship_to: ''
        },
        defaultPoItems: {
            products: [{
                id: null,
                // quantity: 0,
                carton_count: 0,
                units: 0,
                unit_price: 0,
                amount: 0,
                units_per_carton: 0
            }],
            po_number: '',
			is_system_generated: 1,
			supplier_id: '',
			customer_id: '',
			notes: '',
			created_by: '',
			tax: 0,
			warehouse_id: '',
			sub_total: '',
			shipping: 0,
			total: '',
			discount: 0,
            ship_to: ''
        },
        dialogPoView: false,
        dialogPoDelete: false,
        currentPoToDelete: null,
		isMobile: false,
        dialogEmail: false,
        editedIndexEmail: -1,
        editedEmailItem: {
            emails: [],
            po: {}
        },
        defaultEmailItem: {
            emails: [],
            po: {}
        },
        selected: [],
        poIds: [],
        dialogWarning: false,
        poData: null
	}),
	computed: {
		...mapGetters({
            getPendingPage: 'po/getPendingPage',
            getShippedPage: 'po/getShippedPage',
            getAllPo: 'po/getAllPo',
			getPoLoading: 'po/getPoLoading',
            getPoDeleteLoading: 'po/getPoDeleteLoading',
            poBaseUrlState: 'products/poBaseUrlState',
            getVendorLists: 'po/getVendorLists',
            // 
            getAllPoPending: 'po/getAllPoPending',
            getAllPoShipped: 'po/getAllPoShipped',
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
        posShipped() {
            let posData = []

            if (typeof this.getAllPoShipped !== 'undefined' && this.getAllPoShipped !== null) {
				if (typeof this.getAllPoShipped.results !== 'undefined' && this.getAllPoShipped.results !== null) {
                    posData = this.getAllPoShipped.results.data
				}
			}

			return posData
        }
	},
	watch: {
		dialog (val) {
			val || this.close()
		},
		dialogView (val) {
			val || this.closeView()
		},
	},
	methods: {
		...mapActions({
			fetchWarehouse: 'warehouse/fetchWarehouse',
			fetchProducts: 'products/fetchProducts',
            deletePo: 'po/deletePo',
            deleteMultiplePO: 'po/deleteMultiplePO',
            downloadMultiplePO: 'po/downloadMultiplePO',
            downloadPo: 'po/downloadPo',
            fetchTerms: "fetchTerms",
            fetchPoPending: 'po/fetchPoPending',
            fetchPoShipped: 'po/fetchPoShipped',
            fetchPoShippedNew: 'po/fetchPoShippedNew',
            fetchPoPendingNew: 'po/fetchPoPendingNew',
        }),
        ...globalMethods,
		onResize() {
            if (window.innerWidth < 1023) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
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
        createPo() {
            this.dialogCreatePo = true     
            this.editedPoItems.products = [
                {
                    id: null,
                    // quantity: 0,
                    carton_count: 0,
                    units: 0,
                    unit_price: 0,
                    amount: 0,
                    units_per_carton: 0
                }
            ]
            this.editedPoIndex = -1
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
                let units_per_carton = ipp.units_per_carton

                if (unit_price===null || unit_price==='' || ipp.unit_price==0) {

                    context.fetchSingleProduct(ipp.product_id).then( data => {
                        unit_price = (typeof data.unit_price!=='undefined') ? data.unit_price : unit_price
                        unit_price = (unit_price==null || unit_price=='') ? 0 : unit_price
                        po.purchase_order_products[getIndex].unit_price = unit_price

                        units_per_carton = (typeof data.units_per_carton!=='undefined') ? data.units_per_carton : units_per_carton
                        units_per_carton = (units_per_carton==null || units_per_carton=='') ? 0 : units_per_carton
                        po.purchase_order_products[getIndex].units_per_carton = units_per_carton

                        this.processSingleProduct(++getIndex, context, po)
                    }).catch(e => {
                        console.log(e)
                        po.purchase_order_products[getIndex].unit_price = 0
                        po.purchase_order_products[getIndex].units_per_carton = 0
                        this.processSingleProduct(++getIndex, context, po)
                    })
                } else {
                    po.purchase_order_products[getIndex].unit_price = (unit_price==null || unit_price=='') ? 0 : unit_price
                    po.purchase_order_products[getIndex].units_per_carton = (units_per_carton==null || units_per_carton=='') ? 0 : units_per_carton
                    this.processSingleProduct(++getIndex, context, po)
                }
            } else {
                po.loadingState = false
                po.products = po.purchase_order_products
                this.editedPoItems = Object.assign({}, po)
            }
        },
        checkEditPo(po) {
            if (po.status === 'partially shipped') {
                this.dialogWarning = true
                this.poData = po
            } else {
                this.editPo(po)
            }
        },
        async editPo(po) {
            this.dialogCreatePo = true
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
                /*
                this.fetchPoPending(1).catch(e => {
                    if (e=='UnAuthenticated') {
                        window.location.href = '/login'
                    }
                })*/
            }

            // this.editedPoIndex = _.findIndex(this.pos.pending.data, e => (e.id === po.id))
            this.editedPoIndex = _.findIndex(this.posPending, e => (e.id === po.id))
            po.products = []
            po.loadingState = true
            if (typeof po.purchase_order_products!=='undefined' && Array.isArray(po.purchase_order_products) && po.purchase_order_products.length > 0) {
                let index = 0
                
                po.products = []
                this.editedPoItems = Object.assign({}, po)
                this.processSingleProduct(index, this, po)               

            } else {
                po.loadingState = false
                this.editedPoItems = Object.assign({}, po)
            }
        },
        closePoCreate() {
            this.dialogCreatePo = false            
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems)
				this.editedPoIndex = -1
			})

            if (this.poData !== null) {
                this.closeWarning()
            }
        },
        async openPoView(po) {
            let id = po.id

            if (id !== 'undefined' && id !== null) {
                this.$router.push(`/po-details/${id}`)
            }
        },
        closePoView() {
            this.dialogPoView = false            
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems)
				this.editedPoIndex = -1
			})
        },
        email(po) {
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
        openDelete(item) {
            this.dialogPoDelete = true
            this.currentPoToDelete = item
            this.currentPoToDelete.name = item.po_number
        },
        async deletePoConfirm() {
            if (this.currentPoToDelete !== null) {
                try {
                    await this.deletePo(this.currentPoToDelete.id)
                    this.notificationCustom('Purchase order successfully deleted.')
                    //await this.fetchPoPending(1)
                    //await this.fetchPoShipped(1)
                    this.closePoDelete()
                    this.closePoView()
                    
                    await this.fetchPoPendingNew({
                        page: 1
                    })
                    await this.fetchPoShippedNew({
                        page: 1
                    })

                    if (this.isMobile) {
                        this.$router.push(`/pos`)
                    }
                } catch(e) {
                    this.closePoDelete()
                    this.notificationError(e)
                }
            } else {
                if (this.poIds.length > 0) {
                    try {
                        await this.deleteMultiplePO(this.poIds)
                        this.notificationCustom('Purchase Orders have been deleted.')
                        //await this.fetchPoPending(1)
                        //await this.fetchPoShipped(1)
                        await this.fetchPoPendingNew({
                            page: 1
                        })
                        await this.fetchPoShippedNew({
                            page: 1
                        })
                        this.closePoDelete()
                        this.closePoView()
                        this.clearSelectedPO()
                    } catch(e) {
                        this.closePoDelete()
                        this.notificationError(e)
                        this.clearSelectedPO()
                    }
                }
            }
        },
        closePoDelete() {
            this.dialogPoDelete = false
            this.currentPoToDelete = null
            this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems)
				this.editedPoIndex = -1
                this.poIds = []
			})
        },
        clearSelectedPO() {
            this.currentPoToDelete = null
            this.selected = []
            this.poIds = []
        },
        deleteMultiple() {
            let ids = []

            if (this.selected.length > 0) {
                ids = this.selected.map(v => {
                    return v.id
                })
            }

            this.poIds = ids
            this.dialogPoDelete = true
        },
        async downloadMultiple() {
            // action for downloading multiple PO
            await this.downloadMultiplePO(this.selected)
        },        
        closeWarning() {
            this.dialogWarning = false
            this.poData = null
        }
	},
	async mounted() {
		//set current page
		this.$store.dispatch("page/setPage","pos")
        this.hasMounted = true

        try {
            //await this.fetchPoPending(1)
            //await this.fetchPoShipped(1)
            if (this.posShipped.length == 0 && this.posPending.length == 0) {
                await this.fetchPoPendingNew({
                    page: this.getPendingPage
                })
                await this.fetchPoShippedNew({
                    page: this.getShippedPage
                })
            }
            
            await this.fetchWarehouse()
            await this.fetchProducts()            
            await this.fetchTerms()
        } catch(e) {
            this.notificationError(e)
        }
	},	
	updated() {}
};
</script>

<style lang="scss">
@import "../assets/scss/pages_scss/po/po.scss";
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
@import "../assets/scss/buttons.scss";
</style>
