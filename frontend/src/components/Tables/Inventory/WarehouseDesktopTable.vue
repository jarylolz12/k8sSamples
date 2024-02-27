<template>
    <div class="warehouse-table-wrapper" 
        :class="currentWarehouseSelected !== null && 
        currentWarehouseSelected.warehouse_type_id !== 1 &&
        currentWarehouseSelected.warehouse_type_id !== 6 ? 'warehouse-3pl-wrapper' : ''">

        <v-tabs class="warehouse-tabs-wrapper" @change="onTabChange" v-model="$store.state.page.currentInventoryTab">
            <v-tab 
                v-for="(n, i) in tabs" 
                :key="i"
                @click="getCurrentTab(i)" 
                :class="n">

                {{ n }}
            </v-tab>
        </v-tabs>

        <!-- TABS COMPONENTS -->
        <!-- PRODUCTS TAB -->
        <div class="content-tabs-wrapper" v-if="$store.state.page.currentInventoryTab === 0">
            <InventoryProductsDesktopTable v-if="!isMobile"
                :currentWarehouseSelected="currentWarehouseSelected"
                :productsListsDataForAddInventory="productsListsDataForAddInventory"
                @callProductsForAddInventory="callProductsForAddInventory"
                :fetchProductLoading="fetchProductLoading"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL" />

            <InventoryProductsMobileTable v-if="isMobile"
                :currentWarehouseSelected="currentWarehouseSelected"
                :productsListsDataForAddInventory="productsListsDataForAddInventory"
                @callProductsForAddInventory="callProductsForAddInventory"
                :fetchProductLoading="fetchProductLoading"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL" />
        </div>      

        <!-- STORABLE UNIT TAB -->
        <div class="content-tabs-wrapper" v-if="$store.state.page.currentInventoryTab === 1">
            <InventoryStorableDesktopTable v-if="!isMobile"
                :currentWarehouseSelected="currentWarehouseSelected"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL" />

            <InventoryStorableMobileTable v-if="isMobile" 
                :currentWarehouseSelected="currentWarehouseSelected"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL" />
        </div>  

        <!-- LOCATIONS TAB -->
        <div class="content-tabs-wrapper" v-if="$store.state.page.currentInventoryTab === 2">
            <InventoryLocationsDesktopTable v-if="!isMobile"
                                            @printLocationLabel="printLocationLabel"
                :currentWarehouseSelected="currentWarehouseSelected"
                :isMobile="isMobile"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL" />

            <InventoryLocationsMobileTable v-if="isMobile"
                                           @printLocationLabel="printLocationLabel"
                :currentWarehouseSelected="currentWarehouseSelected"
                :isMobile="isMobile"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL" />
        </div> 

        <!-- INBOUND TAB -->
        <div class="content-tabs-wrapper" v-if="$store.state.page.currentInventoryTab === 3">
            <InventoryInboundDesktopTable 
                v-if="!isMobile"
                :currentWarehouseSelected="currentWarehouseSelected"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL"
                :isWarehouseOwn="isWarehouseOwn" />

            <InventoryInboundMobileTable 
                v-if="isMobile"
                :currentWarehouseSelected="currentWarehouseSelected"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL"
                :isWarehouseOwn="isWarehouseOwn" />
        </div> 

        <!-- OUTBOUND TAB -->
        <div class="content-tabs-wrapper" v-if="$store.state.page.currentInventoryTab === 4">
            <InventoryOutboundDesktopTable 
                v-if="!isMobile"
                :currentWarehouseSelected="currentWarehouseSelected"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL" />

            <InventoryOutboundMobileTable 
                v-if="isMobile"
                :currentWarehouseSelected="currentWarehouseSelected"
                :isWarehouseConnected="isWarehouseConnected"
                :isWarehouse3PLProvider="isWarehouse3PLProvider"
                :isWarehouse3PL="isWarehouse3PL" />
        </div> 

        <!-- <InventoryWorkOrdersDesktopTable v-if="currentTab === 5" /> -->
        <!-- <InventoryEmployeesDesktopTable v-if="currentTab === 5" /> -->
        <!-- <InventoryYardDesktopTable v-if="currentTab === 7" /> -->
      <PrintLocationLabelDialog @init="getPrintLocationLabelInterface" @close="showPrintLocationLabelDialog = false" :showDialog.sync="showPrintLocationLabelDialog"></PrintLocationLabelDialog>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import InventoryProductsDesktopTable from './ProductsTab/InventoryProductsDesktopTable.vue'
import InventoryStorableDesktopTable from './StorableTab/InventoryStorableDesktopTable.vue'
import InventoryLocationsDesktopTable from './LocationsTab/InventoryLocationsDesktopTable.vue'
import InventoryInboundDesktopTable from './InboundTab/InventoryInboundDesktopTable.vue'
import InventoryOutboundDesktopTable from './OutboundTab/InventoryOutboundDesktopTable.vue'

// MOBILE COMPONENTS
import InventoryProductsMobileTable from './ProductsTab/InventoryProductsMobileTable.vue'
import InventoryStorableMobileTable from './StorableTab/InventoryStorableMobileTable.vue'
import InventoryLocationsMobileTable from './LocationsTab/InventoryLocationsMobileTable.vue'
import InventoryInboundMobileTable from './InboundTab/InventoryInboundMobileTable.vue'
import InventoryOutboundMobileTable from './OutboundTab/InventoryOutboundMobileTable.vue'
import globalMethods from '../../../utils/globalMethods'
import PrintLocationLabelDialog
  from "@/components/InventoryComponents/InboundComponents/Dialogs/PrintLocationLabelDialog";

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'WarehousesDesktopTable',
    props: ['currentWarehouseSelected', 'isMobile', 'productsListsDataForAddInventory', 'fetchProductLoading'],
    components: {
      PrintLocationLabelDialog,
        InventoryProductsDesktopTable,
        InventoryStorableDesktopTable,
        InventoryLocationsDesktopTable,
        InventoryInboundDesktopTable,
        InventoryOutboundDesktopTable,
        // MOBILE
        InventoryProductsMobileTable,
        InventoryStorableMobileTable,
        InventoryLocationsMobileTable,
        InventoryInboundMobileTable,
        InventoryOutboundMobileTable
    },
    data: () => ({
        showPrintLocationLabelDialog: false,
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
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
				text: 'Name & Category',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "30%" 
			},
			{ 
				text: 'Carton',
				align: 'end',
				sortable: false,
				value: 'carton_count',
				fixed: true,
				width: "15%" 
			},
			{ 
				text: 'In Each',
				align: 'end',
				sortable: false,
				value: 'product_in_each_carton',
				fixed: true,
				width: "15%" 
			},
			{ 
				text: 'Unit',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "15%" 
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "10%" 
			},
		],
        dialogDelete: false,
        dialogDeleteWarehouse: false,
        // tabs: ["Products", "Pallets", "Locations", "Inbound", "Outbound", "Employees", "Work Orders", "Yard"],
        tabs: ["Inventory", "Storable Unit", "Locations", "Inbound", "Outbound"],
        currentTab: 0,
        currentTabName: 'Inventory',
    }),
    computed: {
        ...mapGetters({
            getOutboundInventoriesLoading: 'outbound/getOutboundInventoriesLoading',
            getInboundInventoriesLoading: 'inbound/getInboundInventoriesLoading',
            getLocationsLoading: 'locations/getLocationsLoading',
            getProductInventoryLoading: 'productInventories/getProductInventoryLoading',
        }),
        isWarehouseConnected() {
            if (this.currentWarehouseSelected !== null) {
                if (typeof this.currentWarehouseSelected.is_own !== 'undefined' && 
                    this.currentWarehouseSelected.is_own === 0) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouse3PLProvider() {
            if (this.currentWarehouseSelected !== null) {
                if (typeof this.currentWarehouseSelected.warehouse_type_id !== 'undefined' && 
                    this.currentWarehouseSelected.warehouse_type_id === 6) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouse3PL() {
            if (this.currentWarehouseSelected !== null) {
                if (typeof this.currentWarehouseSelected.warehouse_type_id !== 'undefined' && 
                    this.currentWarehouseSelected.warehouse_type_id === 3) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouseOwn() {
            if (this.currentWarehouseSelected !== null) {
                if (typeof this.currentWarehouseSelected.warehouse_type_id !== 'undefined' && 
                    this.currentWarehouseSelected.warehouse_type_id === 1) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
    },
    methods: {
        ...mapActions({
            fetchProductInventories: 'productInventories/fetchProductInventories',
			fetchOutboundInventories: 'outbound/fetchOutboundInventories',
            setProductEmptyData: 'productInventories/setProductEmptyData',
            setEmptyInventoryInboundData: 'inbound/setEmptyInventoryInboundData',
			setEmptyInventoryLocationsData: 'locations/setEmptyInventoryLocationsData',
			setEmptyInventoryStorableUnitData: 'storableUnit/setEmptyInventoryStorableUnitData',
            // tabs
            setCurrentLocationTypeTab: 'locations/setCurrentLocationTypeTab',
            setCurrentLocationTypeSubTab: 'locations/setCurrentLocationTypeSubTab',
            setCurrentInboundTab: 'inbound/setCurrentInboundTab',
            // set search empty
            setInventoryProductSearchedVal: 'productInventories/setInventoryProductSearchedVal',
            setLocationSearchedVal: 'locations/setLocationSearchedVal',
            setInboundSearchedVal: 'inbound/setInboundSearchedVal',
            setStorableUnitSearchedVal: 'storableUnit/setStorableUnitSearchedVal',
            setCurrentStorableUnitTab: 'storableUnit/setCurrentStorableUnitTab',
            setAllInboundProductsLists: 'inbound/setAllInboundProductsLists',
            setCurrentOutboundTab: "outbound/setCurrentOutboundTab",
            setEmptyInventoryOutboundData: 'outbound/setEmptyInventoryOutboundData',
            setStorableSearchValue: 'storableUnit/setStorableSearchValue',
            // 3pl
            fetchProductInventories3pl: 'productInventories/fetchProductInventories3pl'
        }),
        ...globalMethods,
        onTabChange(i) {
            this.currentTab = i;
            this.$store.dispatch("page/setCurrentInventoryTab", i);
            if(this.currentTab !== 0){
                if (cancel !== undefined) {
			        cancel()
		        }
            }
        },
        getCurrentTab(i) {
            this.currentTab = i;
            this.$store.dispatch("page/setCurrentInventoryTab", i);
            this.getCurrentTabOnLoad(i)
        },
        // warehouse
        viewWarehouse(warehouse) {
            this.$emit('viewWarehouse', warehouse)
        },
        editWarehouse(warehouse) {
            this.$emit('editWarehouse', warehouse)
        },
        async getCurrentTabOnLoad(index) {
            let pathData = typeof this.$router.history.current.query.tab !== 'undefined' 
                ? this.$router.history.current.query.tab : 'Inventory'

            if (index == this.$store.state.page.currentInventoryTab && 
                pathData !== null && pathData !== this.tabs[index]) {

                this.$router.push(`?tab=${this.tabs[index]}`)
                this.$router.history.current.query.tab = this.tabs[index]                

                if (this.currentWarehouseSelected !== null) {                    
                    if (this.tabs[index] == 'Inventory') {
                        // this.setAllInboundProductsLists([])
                        this.setProductEmptyData([])
                        this.setInventoryProductSearchedVal([])
                        if (cancel !== undefined) {
			                cancel("cancel_previous_request")
		                }
                        let dataWithPage = { 
                            page: 1,
                            id: this.currentWarehouseSelected.id,
                            cancelToken : new CancelToken(function executor(c) {
                                cancel = c
                            }),
                        }
                        try{
                            if (!this.isWarehouse3PL) {
                            await this.fetchProductInventories(dataWithPage)
                        } else {                  
                            await this.fetchProductInventories3pl(dataWithPage)
                            this.callProductsForAddInventory('Inventory')
                        }
                        }catch(e){
                            if (e !== "cancel_previous_request" && e.message!== undefined) this.notificationError(e)
                        }
                        
                        
                    } else if (this.tabs[index] == 'Storable Unit') {
                        this.setEmptyInventoryStorableUnitData([])
                        this.setStorableUnitSearchedVal([])
                        this.setCurrentStorableUnitTab(0)
                        this.setStorableSearchValue('')

                    } else if (this.tabs[index] == 'Locations') {
                        this.setLocationSearchedVal([])
                        this.setEmptyInventoryLocationsData([])
                        this.setCurrentLocationTypeTab(0)
                        this.setCurrentLocationTypeSubTab(0)

                    } else if (this.tabs[index] == 'Inbound') {
                        this.setInboundSearchedVal([])
                        this.setEmptyInventoryInboundData([])
                        // this.setAllInboundProductsLists([])
                        this.$store.state.inbound.pendingInboundsLoading = true
                        this.setCurrentInboundTab(0)

                    } else if (this.tabs[index] == 'Outbound') {
                        this.setEmptyInventoryOutboundData([])
                        this.setCurrentOutboundTab(0)
                    } 
                }
            }
        },
        callProductsForAddInventory(from) {
            this.$emit('callProductsForAddInventory', from)
        },
      printLocationLabel(item) {
        this.notificationCustom('Generating label...')
        this.$options.printLabel.print(item);
      },
      getPrintLocationLabelInterface(printLabelInterface) {
        this.$options.printLabel = printLabelInterface
      }
    },
    async mounted() {
        this.getCurrentTabOnLoad(this.currentTab)        
    },
    updated() {},
}
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/warehouse/warehouseTable.scss';
</style>