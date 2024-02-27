<template>
    <div class="storable-view-wrapper" v-resize="onResize">
        <div class="loading-wrapper" v-if="fetchSingleStorableLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

        <div class="storable-content-wrapper" 
            v-if="!fetchSingleStorableLoading && 
            typeof getSingleStorableUnit !== 'undefined' && 
            getSingleStorableUnit !== null">

            <div class="storable-header-information-wrapper">
                <div class="details-breadcrumbs">
                    <router-link to="/inventory?tab=Products" class="warehouse-name" @click.native="clickWarehouseName()">
                        {{ getSingleStorableUnit !== 'undefined' ? getSingleStorableUnit.warehouse_name : '' }}
                    </router-link>

                    <span class="right-chevron">
                        <img src="../../../assets/images/right_chevron.svg" alt="" srcset="" width="7px">
                    </span>

                    <router-link to="/inventory?tab=Storable Unit" class="warehouse-name" @click.native="clickRecentMenu()">
                        Storable Unit
                    </router-link>

                    <span class="right-chevron">
                        <img src="../../../assets/images/right_chevron.svg" alt="" srcset="" width="7px">
                    </span>

                    <p class="order-id">
                        {{ getSingleStorableUnit !== 'undefined' ? getSingleStorableUnit.label : '' }}
                    </p>
                </div>

                <div class="storable-header-wrapper">
                    <div class="storable-info d-sm-flex justify-space-between">
                        <div class="info-wrapper">
                            <div class="order-status">
                                <h2> {{ getSingleStorableUnit !== 'undefined' ? getSingleStorableUnit.label : '' }} </h2>
                                <span class="status"> 
                                    {{ 
                                        getSingleStorableUnit !== 'undefined' ? 
                                        (getSingleStorableUnit.is_active === 1 ? 'Active' : 'History') 
                                        : '' 
                                    }} 
                                </span>
                            </div>

                            <p>Last Updated: 
                                <span style="text-transform: capitalize;"> {{ formatDate(getSingleStorableUnit.updated_at) }} </span>
                            </p>
                        </div>
                      <div class="info-buttons d-flex align-center mb-2 mb-sm-0">
                        <v-btn class="btn-blue" @click.stop="printLabel" :small="$vuetify.breakpoint.xsOnly">
                          Print Label
                        </v-btn>
                      </div>
                    </div>
                </div>
            </div>

            <div class="storable-body-wrapper">
                <div class="storable-informations">
                    <v-row>
                        <v-col cols="12" sm="4">
                            <div class="storable-info dflex warehouse">
                                <p class="storable-title">Type</p>
                                <p class="storable-data" style="text-transform: capitalize;">
                                    {{ getSingleStorableUnit !== 'undefined' ? getSingleStorableUnit.type : '' }}
                                </p>
                            </div>

                            <div class="storable-info dflex warehouse" v-if="isWarehouse3PLProvider">
                                <p class="storable-title">Customer</p>
                                <p class="storable-data" style="text-transform: capitalize;">
                                    {{ 
                                        getSingleStorableUnit !== 'undefined' &&
                                        getSingleStorableUnit.warehouse_customer !== null ? 
                                        getSingleStorableUnit.warehouse_customer.company_name : '' 
                                    }}
                                </p>
                            </div>

                            <div class="storable-info dflex warehouse">
                                <p class="storable-title">Location</p>
                                <p class="storable-data">
                                    {{ getStorableUnitLocationsFormat(getSingleStorableUnit) }}
                                </p>
                            </div>

                            <div class="storable-info dflex warehouse">
                                <p class="storable-title">Dimension</p>
                                <p class="storable-data"> 
                                    {{ parseStringifyItem(getSingleStorableUnit.dimensions, 'dimensions') }}
                                </p>
                            </div>

                            <div class="storable-info dflex warehouse">
                                <p class="storable-title">Weight</p>
                                <p class="storable-data">
                                    {{ parseStringifyItem(getSingleStorableUnit.weight, 'weight') }}
                                </p>
                            </div>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <div class="storable-info dflex">
                                <p class="storable-title">Created At</p>
                                <p class="storable-data"> {{ formatDate(getSingleStorableUnit.created_at) }} </p>
                            </div>

                            <div class="storable-info dflex">
                                <p class="storable-title">NO OF SKU</p>
                                <p class="storable-data">
                                    {{ 
                                        getSingleStorableUnit.storable_unit_products !== 'undefined' &&
                                        getSingleStorableUnit.storable_unit_products !== null ? 
                                        getSingleStorableUnit.storable_unit_products.length : 0
                                    }}
                                </p>
                            </div>

                            <div class="storable-info dflex">
                                <p class="storable-title">CARTONS</p>
                                <p class="storable-data">0</p>
                            </div>

                            <div class="storable-info dflex">
                                <p class="storable-title">UNITS</p>
                                <p class="storable-data">0</p>
                            </div>
                        </v-col>
                    </v-row>
                </div>

                <div class="storable-tabs" 
                    :class="getSingleStorableUnit.is_active === 1 ? 'active-storable' : 'history-storable'">
                    <div class="storable-tabs-header">
                        <v-tabs class="storable-inner-tab" @change="onTabChange" v-model="currentTabComputed">
                            <v-tab v-for="(tab, index) in tabs" :key="index" :class="tab">
                                {{ tab }}
                            </v-tab>
                        </v-tabs>

                        <div class="products-detail-wrapper" 
                            v-if="currentTabComputed === 0 && getSingleStorableUnit.is_active === 1">
                            <v-data-table
                                :headers="productsHeader"
                                :items="getSingleStorableUnit.storable_unit_products"
                                item-key="id"
                                :items-per-page="itemsPerPage"
                                class="elevation-1 storable-tabs-details"
                                mobile-breakpoint="769"
                                hide-default-footer
                                fixed-header>

                                <!-- <template v-slot:[`item.reference`]="{ item }">
                                    <div class="inventory-wrapper inventory-blue-text">
                                        <div class="storable-label-unit" style="color: #0171A1;"> 
                                            {{ 
                                                item.reference !== null && item.reference !== 'undefined' ? 
                                                item.reference : '--'
                                            }}
                                        </div>
                                    </div>
                                </template> -->
                            </v-data-table>
                        </div>

                        <div class="activity-wrapper" v-if="currentTabComputed === 1">
                            <div class="activity-search-wrapper" 
                                v-if="getSingleStorableUnit.storable_activities !== null && getSingleStorableUnit.storable_activities.length !== 0">
                                <!-- <v-tabs class="storable-inner-tab-boxes" @change="onStorableTabChange" v-model="currentStorableTab">
                                    <v-tab v-for="(tab, index) in storableTabs" :key="index">
                                        {{ tab }}
                                    </v-tab>
                                </v-tabs> -->

                                <v-spacer></v-spacer>

                                <SearchComponent
                                    placeholder="Search Activity"
                                    :searchValue.sync="search"
                                    :handleSearchComponent="true"
                                    @handleSearch="handleSearch"
                                    @clearSearched="clearSearched" />
                                    <!-- :handleSearchComponent="handleSearchComponent" -->
                            </div>
                            
                            <v-data-table
                                :headers="storableUnitHeaders"
                                :items="getSingleStorableUnit.storable_activities"
                                item-key="id"
                                :items-per-page="itemsPerPage"
                                class="elevation-1 storable-tabs-details"
                                :class="getSingleStorableUnit.storable_activities !== null && getSingleStorableUnit.storable_activities.length !== 0 ? '' : 'no-data-table'"
                                mobile-breakpoint="769"
                                hide-default-footer
                                :search.sync="search"
                                fixed-header>

                                <template v-slot:[`item.created_at`]="{ item }">
                                    <div class="inventory-wrapper inventory-blue-text">
                                        <div class="storable-label-unit" style="white-space: pre;">{{ formatDateNextLine(item.created_at) }}</div>
                                    </div>
                                </template>

                                <template v-slot:[`item.category_sku`]="{ item }">
                                    <div class="inventory-wrapper inventory-blue-text">
                                        <div class="storable-label-unit"> 
                                            {{ item.category_sku !== null ? item.category_sku : '--' }}
                                        </div>

                                        <div class="storable-label-unit" style="color: #6D858F;"> 
                                            {{ item.product_name !== null ? item.product_name : '--' }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:[`item.reference`]="{ item }">
                                    <div class="inventory-wrapper inventory-blue-text">
                                        <div class="storable-label-unit" style="color: #0171A1;" 
                                            @click="openNewTabForDetails(item)"> 
                                            {{ 
                                                item.reference !== null && 
                                                item.reference !== 'undefined' ? 
                                                item.reference : '--' 
                                            }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:[`item.notes`]="{ item }">
                                    <div class="inventory-wrapper inventory-blue-text">
                                        <div class="storable-label-unit"> 
                                            {{
                                                item.notes !== null && 
                                                item.notes !== 'undefined' &&
                                                item.notes !== 'null' ? item.notes : '--' 
                                            }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:[`item.activity_name`]="{ item }">
                                    <div class="inventory-wrapper inventory-blue-text">
                                        <div class="storable-label-unit" style="text-transform: capitalize;"> 
                                            {{ 
                                                item.activity_name !== null && 
                                                item.activity_name !== 'undefined' ? 
                                                (item.activity_name === 'outbound-cancelled' ? 'Outbound Cancelled' : 
                                                item.activity_name) : '--'
                                            }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:no-data>                                    
                                    <div class="no-data-wrapper" 
                                        v-if="getSingleStorableUnit.storable_activities !== null && getSingleStorableUnit.storable_activities.length == 0">
                                        <div class="no-data-heading">
                                            <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                                            <h3> No Activity Logs </h3>
                                            <p v-if="search == ''">
                                                You don't have any activity logs yet.
                                            </p>

                                            <p v-if="search !== ''">
                                                No Activity Logs found. Try searching another keyword.
                                            </p>
                                        </div>
                                    </div>
                                </template>
                            </v-data-table>
                        </div>
                    </div>              
                </div>
            </div>
          <PrintLabelDialog @init="getPrintLabelInterface" @close="showPrintLabelDialog = false" :label="getSingleStorableUnit.label" :showDialog.sync="showPrintLabelDialog"></PrintLabelDialog>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import SearchComponent from '../../SearchComponent/SearchComponent.vue'
import moment from 'moment'
import globalMethods from '../../../utils/globalMethods'
import inventoryGlobalMethods from '../../../utils/inventoryMethods/inventoryGlobalMethods'
import PrintLabelDialog from "@/components/InventoryComponents/InboundComponents/Dialogs/PrintLabelDialog";

export default {
    name: 'InventoryStorableView',
    props: [],
    components: {
      PrintLabelDialog,
        SearchComponent,
    },
    data: () => ({
        itemsPerPage: 50,
        search: '',
        isMobile: false,
        fetchSingleStorableLoading: false,
        tabs: ['Products', 'Activity Log'],
        currentTabActive: 0,
        currentTabHistory: 1,
        productsHeader: [
            {
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'product.category_sku',
				fixed: true,
				width: "12%"
			},
            {
				text: 'NAME',
				align: 'start',
				sortable: false,
				value: 'product.name',
				fixed: true,
				width: "20%"
			},
			// { 
			// 	text: 'REFERENCE',
			// 	align: 'start',
			// 	sortable: false,
			// 	value: 'reference',
			// 	fixed: true,
			// 	width: "14%"
			// },
			{ 
				text: 'CARTON',
				align: 'end',
				sortable: false,
				value: 'carton_count',
				fixed: true,
				width: "12%"
			},
            { 
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "12%"
			},
            { 
				text: '',
				align: 'end',
				sortable: false,
				value: '',
				fixed: true,
				width: "15%"
			},
        ],
        storableTabs: ['All', 'Inbound', 'Outbound', 'Merge', 'Adjustment'],
        currentStorableTab: 0,
        storableUnitHeaders: [
            {
				text: 'DATE',
				align: 'start',
				sortable: false,
				value: 'created_at',
				fixed: true,
				width: "12%"
			},
            {
				text: 'ACTIVITY',
				align: 'start',
				sortable: false,
				value: 'activity_name',
				fixed: true,
				width: "12%"
			},
			{ 
				text: 'SKU & NAME',
				align: 'start',
				sortable: false,
				value: 'category_sku',
				fixed: true,
				width: "20%"
			},
            { 
				text: 'REFERENCE',
				align: 'start',
				sortable: false,
				value: 'reference',
				fixed: true,
				width: "10%"
			},
			{ 
				text: 'CARTON',
				align: 'end',
				sortable: false,
				value: 'carton_count',
				fixed: true,
				width: "10%"
			},
            { 
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "10%"
			},
            { 
				text: 'NOTE',
				align: 'start',
				sortable: false,
				value: 'notes',
				fixed: true,
				width: "25%"
			},
        ],
        readMore: {},
      doc: {},
      showPrintLabelDialog: false
    }),
    computed: {
        ...mapGetters({
            getSingleStorableUnit: 'storableUnit/getSingleStorableUnit',
            getSingleStorableUnitLoading: 'storableUnit/getSingleStorableUnitLoading',
            getSingleWarehouse: 'warehouse/getSingleWarehouse',
        }),
        currentTabComputed: {
            get() {
                if (typeof this.getSingleStorableUnit !== 'undefined' && this.getSingleStorableUnit !== null) {
                    if (this.getSingleStorableUnit.is_active === 1) {
                        return this.currentTabActive
                    } else {
                        return this.currentTabHistory
                    }
                } else {
                    return this.currentTabActive
                }
            },
            set() {
                return {}
            }
        },
        isWarehouse3PLProvider() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 6) {
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
            fetchSingleStorableUnitInbound: 'storableUnit/fetchSingleStorableUnitInbound',
            fetchSingleWarehouse: 'warehouse/fetchSingleWarehouse',
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        onTabChange(i) {
            if (typeof this.getSingleStorableUnit !== 'undefined' && this.getSingleStorableUnit !== null) {
                if (this.getSingleStorableUnit.is_active === 1) {
                    this.currentTabActive = i
                } else {
                    this.currentTabHistory
                }
            } else {
                this.currentTabActive = i
            }
        },
        onStorableTabChange(i) {
            console.log(i);
        },
        clickWarehouseName() {
            this.$store.dispatch("page/setCurrentInventoryTab", 0);
        },
        clickRecentMenu() {
            this.$store.dispatch("page/setCurrentInventoryTab", 1);
        },
        onResize() {
            if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
        },
        formatDate(date) {
            return this.formatTimeAndDateTogether(date)
        },
        formatDateNextLine(item) {
            if (typeof item !== 'undefined' && item !== null) {
                return moment(item).format(`hh:mmA,[\r\n]MMM DD, YYYY`) 
            } else {
                return 'N/A'
            }
        },
        getStorableUnitLocationsFormat(item) {
            if (item !== null) {
                let locationFormat = item.location !== 'undefined' ? (item.location.type === 'storage' ? 
                item.location.shelf + item.location.row + item.location.rack : item.location.gate_name) : ''

                return locationFormat
            }
        },
        parseStringifyItem(item, type) {
            if (item !== null) {
                if (type === 'dimensions') {
                    let parseDimensions = JSON.parse(item)
                    let dimensionsFormat = parseDimensions.h + 'x' + parseDimensions.w + 'x' + parseDimensions.l + ' ' + parseDimensions.uom
                    return dimensionsFormat
                } else if (type === 'weight') {
                    let parseUnit = JSON.parse(item)
                    let unitFormat = parseUnit.value + ' ' + parseUnit.unit
                    return unitFormat
                }
            }
        },
        clearSearched() {
            this.search = ''
            // this.setStorableUnitSearchedVal([])
            // document.getElementById("search-input").focus();
        },
        handleSearch() { },
        openNewTabForDetails(item) {
            if (typeof item !== 'undefined' && item !== null) {
                if (item.reference !== 'Adjustment' || item.reference !== 'Merge') {
                    if (typeof this.getSingleStorableUnit !== 'undefined' && this.getSingleStorableUnit !== null) {
                        if (this.getSingleStorableUnit.warehouse_id !== null) {
                            let warehouse_id = this.getSingleStorableUnit.warehouse_id

                            if (item.activity_name === 'inbound' || item.activity_name === 'inbound-cancelled') {
                                let inbound_id = null

                                if (item.inbound !== null) {
                                    inbound_id = item.inbound_id
                                } else {
                                    if (this.getSingleStorableUnit.inbound_id !== null) {
                                        inbound_id = this.getSingleStorableUnit.inbound_id
                                    }
                                }

                                if (inbound_id !== null) {
                                    let routeData = this.$router.resolve({
                                        name: 'Inventory Inbound View', 
                                        query: {
                                            inboundid: inbound_id, 
                                            warehouseid: warehouse_id 
                                        }
                                    });

                                    window.open(routeData.href, '_blank');
                                } else {
                                    this.notificationError("Can't fetch Inbound Details. Inbound id is null.")
                                }

                            } else if (item.activity_name === 'outbound' || item.activity_name === 'outbound-cancelled') {
                                let outbound_id = null

                                if (item.outbound_id !== null) {
                                    outbound_id = item.outbound_id
                                }                        

                                if (outbound_id !== null) {
                                    let routeData = this.$router.resolve({
                                        name: 'Inventory Outbound View', 
                                        query: {
                                            id: outbound_id, 
                                            wid: warehouse_id 
                                        }
                                    });

                                    window.open(routeData.href, '_blank');
                                } else {
                                    this.notificationError("Can't fetch Outbound Details. Outbound id is null.")
                                }
                            }
                        }
                    }
                    
                    // var references = item.reference.split('#');

                    // if (references !== 'undefined' && references.length > 0) {
                    //     if (typeof this.getSingleStorableUnit !== 'undefined' && this.getSingleStorableUnit !== null) {
                    //         if (this.getSingleStorableUnit.warehouse_id !== null) {
                    //             let warehouse_id = this.getSingleStorableUnit.warehouse_id    

                    //             if (references[0] === 'IB' && this.getSingleStorableUnit.inbound_id !== null) {
                    //                 let inbound_id = this.getSingleStorableUnit.inbound_id

                    //                 let routeData = this.$router.resolve({
                    //                     name: 'Inventory Inbound View', 
                    //                     query: {
                    //                         inboundid: inbound_id, 
                    //                         warehouseid: warehouse_id 
                    //                     }
                    //                 });

                    //                 window.open(routeData.href, '_blank');
                    //             } else if (references[0] === 'OB' && this.getSingleStorableUnit.outbound_id !== null) {
                    //                 let outbound_id = this.getSingleStorableUnit.outbound_id

                    //                 let routeData = this.$router.resolve({
                    //                     name: 'Inventory Outbound View', 
                    //                     query: {
                    //                         id: outbound_id, 
                    //                         wid: warehouse_id 
                    //                     }
                    //                 });

                    //                 console.log(routeData);

                    //                 // window.open(routeData.href, '_blank');
                    //             }

                    //             console.log(references);
                    //         }
                    //     }
                    // }
                }
            }
            
        },
        getPrintLabelInterface(printLabelInterface) {
            this.$options.printLabel = printLabelInterface
        },
        printLabel() {
            this.notificationCustom('Generating label...')
            this.$options.printLabel.print();
        },
    },
    async mounted() {
        this.$store.dispatch("page/setPage", "inventory");
        this.fetchSingleStorableLoading = true

        let unit_id = new URL(location.href).searchParams.get('storableid')
        let warehouse_id = new URL(location.href).searchParams.get('warehouseid')

        if (unit_id !== null && warehouse_id !== null) {
            let payload = { wid: warehouse_id, unit_id }

            try {
                await this.fetchSingleStorableUnitInbound(payload)
                this.fetchSingleStorableLoading = false
                await this.fetchSingleWarehouse(warehouse_id)

                if (typeof this.getSingleWarehouse !== 'undefined') {
                    this.$store.state.warehouse.currentWarehouse = this.getSingleWarehouse
                }
            } catch(e) {
                this.fetchSingleStorableLoading = false
                console.log(e);
            }
        }

    },
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/inventory/storableUnit/storableUnitView.scss';
</style>
