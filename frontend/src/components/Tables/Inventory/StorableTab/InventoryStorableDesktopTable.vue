<template>
    <div class="storable-unit-wrapper-desktop">
        <div class="overlay" :class="getCurrentLoadingToDisplay ? 'show' : ''">  
            <div class="preloader" v-if="(getCurrentLoadingToDisplay)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>
        <v-data-table
            :headers="headersComputed"
            :items="storableUnitItems"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            item-key="id"
            class="inventory-table storable-table elevation-1"
            v-bind:class="{
                'no-data-table': (typeof storableUnitItems !== 'undefined' && storableUnitItems.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
                'warehouse-connected' : isWarehouseConnected && (typeof storableUnitItems !== 'undefined' && storableUnitItems.length > 0)
            }"
            hide-default-footer
            fixed-header
            :expanded.sync="expanded"
            single-expand
            show-expand
            show-select
            :item-class="itemRowBackground"
            ref="my-table"
            @click:row="viewStorableUnit">

            <template v-slot:top>
                <div class="inventory-search-wrapper">      
                    <v-tabs class="inventory-inner-tab" @change="onTabChange" v-model="currentTab" v-if="!isWarehouseConnected">
                        <v-tab v-for="(tab, index) in tabs" 
                            :key="index" 
                            :class="index == 2 ? 'inventory-inner-tab-last' : ''"
                            @click="onClickTab(index)">                            
                            <span>{{ tab }}</span>
                        </v-tab>
                    </v-tabs>          

                    <v-spacer></v-spacer>

                    <!-- <v-btn dark color="primary" class="btn-white btn-filters mr-2">
                        <img src="@/assets/icons/filters.svg" class="mr-1"> Filters
                    </v-btn> -->

                    <div class="search-and-filter">
                        <div class="filter-tags-input" v-if="(isWarehouse3PLProvider && !isWarehouseConnected) && handleFilterComponent">
                            <FilterCustomers 
                                @onClickFilter="onClickFilter"
                                @filterAllWarehouseCustomers="filterAllWarehouseCustomers"
                                @cancelSelectingWarehouseCustomers="cancelSelectingWarehouseCustomers"
                                @searchWarehouseCustomers="searchWarehouseCustomers"
                                @removeCustomerLists="removeCustomerLists"
                                @removeCustomerListsEmptyOnChange="removeCustomerListsEmptyOnChange"
                                :searchCustomerData.sync="searchCustomerData"
                                :menu.sync="filterMenu"
                                :selectedWhCustomersCopy.sync="selectedWhCustomersCopy"
                                :selectedWhCustomers.sync="selectedWhCustomers"
                                :warehouseCustomerLists.sync="warehouseCustomerLists"
                                :loading="fetchWarehouseCustomersLoading"
                                @clickOutside="clickOutsideFilter"
                                :isActiveClicked.sync="isActiveClicked"
                                @clearAllFilter="clearAllFilter"
                            />
                        </div>

                        <SearchComponent
                            placeholder="Search Storable Unit"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />

                        <!-- <v-btn dark color="primary" class="btn-blue ml-2">
                            Merge
                        </v-btn> -->
                    </div>                    
                </div>                
            </template>

            <!-- FOR OPENING MULTIPLE ROWS AT A TIME -->
            <!-- <template v-slot:[`item.data-table-expand`]="{ item, index }">                
                <div class="inventory-wrapper collapse-button-wrapper inventory-blue-text">
                    <div> {{ item.label }}</div>

                    <v-btn class="button-collapse" @click="clicked(item, index)">
                        <v-icon color="primary">
                            {{ item.isExpanded ? 'mdi-chevron-up' : 'mdi-chevron-down'}}
                        </v-icon>
                    </v-btn>
                </div>
            </template> -->

            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">
                    <div class="expanded-item-info">
                        <div class="expanded-header-wrapper">
                            <div class="expanded-header-content">
                                <div class="header-title w-80">SKU</div>
                                <div class="header-title w-10">CARTON</div>
                                <div class="header-title w-10">UNIT</div>
                            </div>
                        </div>

                        <div class="expanded-body-wrapper">
                            <div class="expanded-body-content" 
                                v-for="(v, index) in item.storable_unit_products" :key="index">

                                <div class="header-data w-80">
                                    #{{ (v.product !== null && v.product.category_sku !== null) ? v.product.category_sku : '' }} 
                                    {{  (v.product !== null && v.product.name !== null) ? v.product.name : ''}}
                                </div>
                                <div class="header-data w-10">
                                    {{ v.carton_count }}
                                </div>
                                <div class="header-data w-10">
                                    {{ v.total_unit }}
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="inventory-wrapper">
                    <div style="text-transform: capitalize;">{{ item.type !== null ? item.type : '--' }}</div>
                </div>
            </template>

            <template v-slot:[`item.label`]="{ item }">
                <div class="inventory-wrapper inventory-blue-text">
                    <div class="storable-label-unit" style="width: 60px;">{{ item.label !== null ? item.label : '--' }}</div>
                </div>
            </template>

            <template v-slot:[`item.spec`]="{ item }">
                <div class="storable-spec-wrapper">
                    <div class="inventory-wrapper">
                        {{ item.parse_dimensions.l !== undefined && item.parse_dimensions.l !== '' ? item.parse_dimensions.l : 0 }} x
                        {{ item.parse_dimensions.w !== undefined && item.parse_dimensions.w !== '' ? item.parse_dimensions.w : 0 }} x
                        {{ item.parse_dimensions.h !== undefined && item.parse_dimensions.h !== '' ? item.parse_dimensions.h : 0 }} 
                        {{ item.parse_dimensions.uom }}
                    </div>

                    <div class="weight">
                        <div style="color: #6D858F !important;"> 
                            {{ item.parse_weight.value !== undefined && item.parse_weight.value !== '' ? item.parse_weight.value : 0 }} 
                            <span class="text-lowercase">{{ item.parse_weight.unit }}</span>
                        </div>
                    </div>
                </div>
            </template>
            
            <template v-slot:[`item.location`]="{ item, index }">
                <div class="inventory-wrapper"> 
                    {{ getLocationDetails(item.location) }}

                    <div class="btn-edit-show-on-hover" v-if="currentTab === 0 && storableUnitItems.length > 0 && !isWarehouseConnected">     
                        <v-menu 
                            v-model="storableUnitItems[index].ActiveValue"
                            :close-on-content-click="false"
                            :nudge-right="150"
                            :nudge-width="200"
                            offset-y
                            bottom left 
                            @click="setActiveLocationTrue()"
                            content-class="storable-location-menu">

                            <template v-slot:activator="{ on, attrs }">
                                <v-btn :ripple="false" class="btn-white" v-bind="attrs" v-on="on" 
                                    @click.stop="moveStorableUnitLocation(item, index)">
                                    <img src="@/assets/icons/edit-blue.svg" alt="Edit" width="16px" height="16px">
                                </v-btn>
                            </template>
 
                            <v-card flat>
                                <div class="change-location-sub-header">
                                    <h3>Change Location</h3>
                                    <button class="btn-white close" @click="closeLocationUpdate(index)">
                                        <v-icon>mdi-close</v-icon>
                                    </button>
                                </div>

                                <v-list-item v-if="fetchLocationsListsLoading" style="min-height:100px;" 
                                    @click="setActiveLocationTrue()">
                                    <v-list-item-content class="option-items loading" style="padding:0; border-bottom:none;">
                                        <div class="d-flex justify-center align-center">
                                            <v-progress-circular
                                                :size="40"
                                                color="#0171a1"
                                                indeterminate>
                                            </v-progress-circular>
                                        </div>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item v-if="locationsLists.length > 0" @click="setActiveLocationTrue()">
                                    <v-list-item-content class="option-items" 
                                        style="align-self:unset; padding:0; border-bottom:none;">
                                        <v-radio-group v-model="currentSelectedLocation" hide-details="auto" class="mt-0">
                                            <v-radio 
                                                v-for="(item) in locationsLists"
                                                :key="item.id"
                                                :label="item.name" 
                                                :value="item.id"
                                                hide-details="auto"
                                                @change="setActiveLocationTrue()">
                                            </v-radio>
                                        </v-radio-group>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item v-if="!fetchLocationsListsLoading && locationsLists.length === 0" @click="setActiveLocationTrue()" style="min-height:100px;">
                                    <v-list-item-content class="option-items" 
                                        style="align-self:unset; padding:0; border-bottom:none;">
                                        <p style="color:#4a4a4a; font-size:14px;" class="mb-0 text-center">
                                            No available data
                                        </p>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-card-actions v-if="!fetchLocationsListsLoading && locationsLists.length > 0"
                                    class="filters-card-actions d-flex align-center append-filter-button-inbound">
                                    <v-btn class="btn-apply btn-blue" @click="applyLocationUpdate(item, index)" :disabled="getLocationsUpdateLoading">
                                        {{ getLocationsUpdateLoading ? 'Applying...' : 'Apply'}}
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-menu>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="inventory-wrapper"> {{ formatDate(item.updated_at) }} </div>
            </template>

            <template v-slot:[`item.no_sku`]="{ item }">
                <span>{{ item.no_of_sku }}</span>
            </template>

            <template v-slot:[`item.total_carton_count`]="{ item }">
                <span>{{ item.total_carton_count !== null ? item.total_carton_count : 0 }}</span>
            </template>

            <template v-slot:[`item.total_units`]="{ item }">
                <span>{{ item.total_units !== null ? item.total_units : 0 }}</span>
            </template>

            <template v-slot:[`item.history_reference`]="{ item }">
                <span>
                    {{ 
                        item.history_reference !== null &&
                        item.history_reference !== 'undefined' ? 
                        item.history_reference : '--'
                    }}
                </span>
            </template>

            <template v-slot:[`item.warehouse_customer`]="{ item }">
                <span>
                    {{ 
                        item.warehouse_customer !== null &&
                        item.warehouse_customer !== 'undefined' ? 
                        item.warehouse_customer.company_name : '--'
                    }}
                </span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions mr-1" v-if="(item.is_active === 1)">                    
                    <v-menu bottom left offset-y content-class="outbound-lists-menu" v-if="!isWarehouseConnected">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on">
                                <v-icon>mdi-dots-horizontal</v-icon>
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <v-list-item @click="viewStorableUnit(item)">
                                <v-list-item-title>
                                    View
                                </v-list-item-title>
                            </v-list-item>
                            <v-list-item v-if="!!item.label" @click="printLabel(item.label)">
                                <v-list-item-title>
                                    Print Label
                                </v-list-item-title>
                            </v-list-item>

                            <!-- <v-list-item>
                                <v-list-item-title>
                                    Merge With
                                </v-list-item-title>
                            </v-list-item> -->
                        </v-list>
                    </v-menu>
                </div>

                <div class="actions mr-1" v-if="item.is_active === 0">
                    <button class="btn-white history-view-button" @click.stop="viewStorableUnit(item)">
                        View
                    </button>

                    <!-- <v-menu bottom left offset-y content-class="outbound-lists-menu">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on" class="ml-2">
                                <v-icon>mdi-dots-horizontal</v-icon>
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <v-list-item @click="viewStorableUnit(item)">
                                <v-list-item-title>
                                    View
                                </v-list-item-title>
                            </v-list-item>

                            <v-list-item>
                                <v-list-item-title>
                                    Merge With
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu> -->
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="getHandlePageLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper" v-if="!getHandlePageLoading && storableUnitItems.length == 0">
                    <div class="no-data-heading" v-if="search === '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">
                        <h3> No Storable Units </h3>
                        <p>
                            <span v-if="currentTab === 0">You don't have any active storable units yet.</span>
                            <span v-if="currentTab === 1">You don't have storable units in history.</span>
                        </p>
                    </div>

                    <div class="no-data-heading" v-if="search !== '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">
                        <div>
                            <h3> No Storable Units </h3>
                            <p> No storable units found. Try searching another keyword.</p>
                        </div>
                    </div>

                    <div v-if="selectedWhCustomers.length > 0">
						<img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">
                        <div>
                            <h3> No Storable Units </h3>
                            <p> There are no storable units listed. Change another Customer.</p>
                        </div>
					</div>
                </div>
            </template>
        </v-data-table>
        <PrintLabelDialog @init="getPrintLabelInterface" @close="showPrintLabelDialog = false" :showDialog.sync="showPrintLabelDialog"></PrintLabelDialog>
        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import SearchComponent from '../../../SearchComponent/SearchComponent.vue'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'
import FilterCustomers from '../../../InventoryComponents/FilterCustomers/FilterCustomers'
// import FilterComponent from '../../../FilterComponent/FilterComponent.vue'
import globalMethods from '../../../../utils/globalMethods'
import inventoryGlobalMethods from '../../../../utils/inventoryMethods/inventoryGlobalMethods'
import PrintLabelDialog from "@/components/InventoryComponents/InboundComponents/Dialogs/PrintLabelDialog";
import _ from 'lodash'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'InventoryStorableDesktopTable',
    props: ['currentWarehouseSelected', 'isMobile', 'isWarehouseConnected', 'isWarehouse3PLProvider'],
    components: {
        SearchComponent,
        PaginationComponent,
        FilterCustomers,
        PrintLabelDialog,
        // FilterComponent
    },
    data: () => ({
        showPrintLabelDialog: false,
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        tabs: ['Active', 'History'],
        currentTab: 0,
        expanded: [],
        headersDefault: [
            {
                text: 'Label',
                align: 'start',
                sortable: false,
                value: 'label',
                fixed: true,
                width: "70px"
            },
            { text: '', value: 'data-table-expand', width: ""},
            { 
                text: 'Type',
                align: 'start',
                sortable: false,
                value: 'type',
                fixed: true,
                width: "10%"
            },
            { 
                text: 'Spec',
                align: 'start',
                sortable: false,
                value: 'spec',
                fixed: true,
                width: "12%"
            },
            { 
                text: 'Location',
                align: 'start location-edit',
                sortable: false,
                value: 'location',
                fixed: true,
                width: ""
            },
            { 
                text: 'Customer',
                align: 'start',
                sortable: false,
                value: 'warehouse_customer',
                fixed: true,
                width: "18%"
            },
            { 
                text: 'Updated At',
                align: 'start',
                sortable: false,
                value: 'updated_at',
                fixed: true,
                width: "16%"
            },
            { 
                text: 'Reference',
                align: 'start',
                sortable: false,
                value: 'history_reference',
                fixed: true,
                width: ""
            },
            { 
                text: 'No of SKU',
                align: 'end',
                sortable: false,
                value: 'no_of_sku',
                fixed: true,
                width: "10%"
            },
            { 
                text: 'Carton',
                align: 'end',
                sortable: false,
                value: 'total_carton_count',
                fixed: true,
                width: ""
            },
            { 
                text: 'Unit',
                align: 'end',
                sortable: false,
                value: 'total_units',
                fixed: true,
                width: ""
            },
            { 
                text: '', 
                align: 'end',
                value: 'actions', 
                sortable: false,
                fixed: true,
                width: "6%"
            },
        ],
        typingTimeout: 0,
        fetchActiveNextPageStorableLoading: false,
        fetchHistoryNextPageStorableLoading: false,
        // warehouse customers data
        selectedWhCustomers: [],
        selectedWhCustomersCopy: [],
        current_page_is_warehouse_products: 1,
		current_page_is_whcustomers: 1,
        searchCustomerData: '',
        warehouseCustomerListsData: [],
        warehouseCustomerLists: [],
        warehouseCustomerListsCopy: [],
        fetchWarehouseCustomersLoading: true,
        filterMenu: false,
        isActiveClicked: false,
        moveLocationMenu: [],
        isActiveClickedLocation: false,
        locationsLists: [],
        location_page: 1,
        lastLocationsListsCheck: [],
        fetchLocationsListsLoading: false,
        currentSelectedLocation: '',
        selectedWhCustomerReAssignValue : []
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getCurrentWarehouse: 'warehouse/getCurrentWarehouse',
            getStorableUnitsActive: 'storableUnit/getStorableUnitsActive',
            getStorableUnitsActiveLoading: 'storableUnit/getStorableUnitsActiveLoading',
            getStorableUnitsHistory: 'storableUnit/getStorableUnitsHistory',
            getStorableUnitsHistoryLoading: 'storableUnit/getStorableUnitsHistoryLoading',
            poBaseUrlState: 'products/poBaseUrlState',
            getCurrentStorableUnitTab: 'storableUnit/getCurrentStorableUnitTab',
            getSearchedStorableUnit: 'storableUnit/getSearchedStorableUnit',
            getSearchedStorableUnitLoading: 'storableUnit/getSearchedStorableUnitLoading',
            getStorableSearchData: 'storableUnit/getStorableSearchData',
            // filter and search
            getFilteredStorableLoading:'storableUnit/getFilteredStorableLoading',
            getFilteredStorable:'storableUnit/getFilteredStorable',
            getAllWarehouseCustomerListsData: 'warehouseCustomers/getAllWarehouseCustomerListsData',
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            getAllWarehouseLocations: 'inbound/getAllWarehouseLocations',
            getLocationsUpdateLoading: 'storableUnit/getLocationsUpdateLoading'
        }),
        allActiveStorableListsData() {
            let activeLists = []

            if (typeof this.getSearchedStorableUnit !== 'undefined' && this.getSearchedStorableUnit !== null && 
                this.getFilteredStorable !== 'undefined' && this.getFilteredStorable !== null) {
                if (this.search !== '' && this.currentTab === 0 && this.getSearchedStorableUnit.tab === 'storable-active') {
                    activeLists = this.getSearchedStorableUnit
                } else if (this.selectedWhCustomers.length > 0 && this.currentTab === 0 && 
                    this.getFilteredStorable.tab === 'storable-active') {
					activeLists = this.getFilteredStorable
				} else {
                    if (typeof this.getStorableUnitsActive !== 'undefined' && this.getStorableUnitsActive !== null) {
                        activeLists = this.getStorableUnitsActive
                    }
                }
            }

            return activeLists
        },
        allHistoryStorableListsData() {
            let historyLists = []

            if (typeof this.getSearchedStorableUnit !== 'undefined' && this.getSearchedStorableUnit !== null && 
                this.getFilteredStorable !== 'undefined' && this.getFilteredStorable !== null) {
                if (this.search !== '' && this.currentTab === 1 && this.getSearchedStorableUnit.tab === 'storable-history') {
                    historyLists = this.getSearchedStorableUnit
                } else if (this.selectedWhCustomers.length > 0 && this.currentTab === 1 && 
                    this.getFilteredStorable.tab === 'storable-history') {
					historyLists = this.getFilteredStorable
				} else {
                    if (typeof this.getStorableUnitsHistory !== 'undefined' && this.getStorableUnitsHistory !== null) {
                        historyLists = this.getStorableUnitsHistory
                    }                     
                }
            }

            return historyLists
        },
        storableUnitItems() {
            let storableUnits = this.currentTab === 0 ? this.allActiveStorableListsData : this.allHistoryStorableListsData
            let units = []

            if (typeof storableUnits !== 'undefined' && storableUnits !== null && 
                storableUnits.items !== 'undefined' && Array.isArray(storableUnits.items)) {

                let items = storableUnits.items

                items.map(v => {
                    let  { dimensions, weight, storable_unit_products, ...otherItems } = v
                    let parse_dimensions = dimensions !== '' && dimensions !== null ? JSON.parse(dimensions) : null
                    let parse_weight = weight !== '' && weight !== null ? JSON.parse(weight) : null
                    let no_of_sku = storable_unit_products.length !== 'undefined' ? storable_unit_products.length : 0
                    let ActiveValue =false
                    units.push({
                        parse_dimensions,
                        parse_weight,
                        no_of_sku,
                        storable_unit_products,
                        ActiveValue,
                        ...otherItems
                    })
                })
            }

            return units            
        },
        getHandlePageLoading() {
            if (this.currentTab === 0) {
                return this.getStorableUnitsActiveLoading
            } else {
                return this.getStorableUnitsHistoryLoading
            }
        },
        getTotalPage: {
            get() {
                let total = 1
                let storableUnitsData = this.currentTab === 0 ? this.allActiveStorableListsData : this.allHistoryStorableListsData

                if (typeof storableUnitsData.last_page !== 'undefined' && storableUnitsData.last_page !== null) {
                    total = storableUnitsData.last_page
                }

                return total
            }
        },
        getCurrentPage: {
            get() {
                let current_page = 1
                let storableUnitsData = this.currentTab === 0 ? this.allActiveStorableListsData : this.allHistoryStorableListsData

                if (typeof storableUnitsData.current_page !== 'undefined' && storableUnitsData.current_page !== null) {
                    current_page = storableUnitsData.current_page
                }

                return current_page
            },
            set() {
                return {}
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.storableUnitItems.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.storableUnitItems.length === 0) {
                isShow = true
            } else if (this.selectedWhCustomers.length > 0 && this.storableUnitItems.length === 0) {
                isShow = false
            }

            return isShow
        },
        handleFilterComponent() {
            let isShow = true

            if (this.search == '' && this.selectedWhCustomers.length === 0 && this.storableUnitItems.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.storableUnitItems.length === 0) {
                isShow = true
            } else if (this.search !== '' || this.storableUnitItems.length > 0) {
				isShow = true
			}
            return isShow
        },
        getCurrentLoadingToDisplay() {
            if (this.search === '' && this.selectedWhCustomers.length === 0) {
                return this.getHandleLoading
            } else if(this.selectedWhCustomers.length > 0) {
                return this.getFilteredStorableLoading
            } else {
                return this.getSearchedStorableUnitLoading
            }
        },   
        getHandleLoading() {
            let storePaginationCurrentTab = this.$store.state.storableUnit.currentStorableUnitTab

            if (storePaginationCurrentTab === 0) {
                return this.fetchActiveNextPageStorableLoading
            } else {
                return this.fetchHistoryNextPageStorableLoading
            }
        },
        headersComputed() {
            let defaultHeaders = this.headersDefault

             if (this.isWarehouse3PLProvider) {
                if (this.isWarehouseConnected) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'Customer' && v.text !== 'Reference'
                    })
                } else {
                    if (this.currentTab === 0) {
                        defaultHeaders = defaultHeaders.filter(v => {
                            return v.text !== 'Updated At' && v.text !== 'Reference'
                        })
                    } else {
                        defaultHeaders = defaultHeaders.filter(v => {
                            return v.text !== 'Updated At' && v.text !== 'No of SKU' && v.text !== 'Carton' && v.text !== 'Unit'
                        })
                    }
                }                
            } else {
                if (this.currentTab === 0) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'Customer' && v.text !== 'Reference'
                    })
                } else {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'Customer' && v.text !== 'No of SKU' && v.text !== 'Carton' && v.text !== 'Unit'
                    })
                }
            }

            return defaultHeaders
        },
    },
    watch: {
        search(value) {
            this.setStorableSearchValue(value)
        }
    },
    methods: {
        ...mapActions({
            fetchStorableUnitsActive: 'storableUnit/fetchStorableUnitsActive',
            fetchStorableUnitsHistory: 'storableUnit/fetchStorableUnitsHistory',
            setSearchedStorableUnitsLoading: 'storableUnit/setSearchedStorableUnitsLoading',
            setStorableUnitSearchedVal: 'storableUnit/setStorableUnitSearchedVal',
            fetchSearchedStorableUnits: 'storableUnit/fetchSearchedStorableUnits',
            setCurrentStorableUnitTab: 'storableUnit/setCurrentStorableUnitTab',
            setStorableSearchValue: 'storableUnit/setStorableSearchValue',
            // filter and search 
            setStorableFilteredVal:'storableUnit/setStorableFilteredVal',
            setFilteredStorableLoading:'storableUnit/setFilteredStorableLoading',
            fetchFilterStorableCustomers:'storableUnit/fetchFilterStorableCustomers',
            fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
            setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
            getPlaceStorableLocations: 'inbound/getPlaceStorableLocations',
            updateStorableLocation: 'storableUnit/updateStorableLocation'
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        setEmptyValues() {
            this.setStorableUnitSearchedVal([])
            this.setStorableFilteredVal([])
            this.searchCustomerData = ''
            this.search = ''
            this.selectedWhCustomersCopy = []
            this.selectedWhCustomers = []
            this.selectedWhCustomerReAssignValue = []
        },
        onTabChange(i) {
            this.currentTab = i
            this.setCurrentStorableUnitTab(i)
            this.setEmptyValues()
        },
        getPrintLabelInterface(printLabelInterface) {
          this.$options.printLabel = printLabelInterface
        },
        printLabel(label) {
            this.notificationCustom('Generating label...')
            this.$options.printLabel.print(label);
        },
        async onClickTab(i) {
            this.setEmptyValues()

            try {
                if (this.source) this.source.cancel("cancel_previous_request")
                this.source = axios.CancelToken.source()

                let storePagination = this.$store.state.storableUnit
                let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1, cancelToken: this.source.token }

                if (i == 0 && this.currentTab !== i) {
                    dataWithPage.page = storePagination.storableUnitsActive.current_page
                    await this.fetchStorableUnitsActive(dataWithPage)
                    storePagination.storableUnitsActive.old_page = storePagination.storableUnitsActive.current_page
                    this.setCurrentStorableUnitTab(i)
                } else if (i === 1 && this.currentTab !== i) {
                    dataWithPage.page = storePagination.storableUnitsHistory.current_page
                    await this.fetchStorableUnitsHistory(dataWithPage)
                    storePagination.storableUnitsHistory.old_page = storePagination.storableUnitsHistory.current_page
                    this.setCurrentStorableUnitTab(i)
                }
            } catch(e) {
                if (e !== "cancel_previous_request") this.notificationError(e)
            }
        },
        openMultipleItem(value) {
            // code for opening multiple
            const index = this.expanded.indexOf(value)
            value.isExpanded = false

            if (index === -1) {
                this.expanded.push(value)
            } else {
                this.expanded.splice(index, 1)
            }
        },
        getLocationDetails(location) {
            let locationName = ""
            let shelf = ''
            let row = ''
            let rack = ''

            if (location !== null) {
                 if (location.type === 'storage') {
                    if (location.shelf !== null) {
                        shelf = location.shelf
                    }

                    if (location.row !== null) {
                        row = location.row
                    }

                    if (location.rack !== null) {
                        rack = location.rack
                    }
                } else {
                    locationName = location.gate_name
                }
            }

            locationName = location.type === 'storage' ? row + rack + shelf : locationName
            locationName = locationName !== '' ? locationName : '--'

            return locationName
        },
        itemRowBackground(item) {
            return item.isExpanded ? 'background-light-blue' : ''
        },
        formatDate(date) {
            return this.formatTimeAndDateTogether(date)
        },
        viewStorableUnit(item) {
            if (!this.isWarehouseConnected) {
                this.$router.push(`inventory/storable-unit-view?storableid=${item.id}&warehouseid=${item.warehouse_id}`)
            }
        },
        clearSearched() {
            this.search = ''
            this.setStorableUnitSearchedVal([])
            this.setStorableSearchValue('')
            if (this.isWarehouse3PLProvider) {
				if(this.selectedWhCustomers.length > 0) {
					this.setStorableFilteredVal([])
					this.filterAllWarehouseCustomers()
				}
			}
        },
        handleSearch() {
            if (cancel !== undefined) {
                cancel("cancel_previous_request")
            }
            this.setSearchedStorableUnitsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { search: this.search } 
                if (this.selectedWhCustomers.length > 0) {
                    return this.filterAllWarehouseCustomers()
                } else {
                    this.setSearchedStorableUnitsLoading(true)
                    this.apiCall(data)
                }                
            }, 500)
        },
        apiCall(data) {
            let warehouse_id = this.currentWarehouseSelected.id

            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        search: this.search,
                        page: 1
                    }
                }

                if (this.currentTab === 0) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storable-units/active`
                    passedData.tab = 'storable-active'
                } else {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storable-units/history`
                    passedData.tab = 'storable-history'
                }

                if (passedData.url !== '') {
                    try {
                        this.fetchSearchedStorableUnits(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedStorableUnitsLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.setStorableUnitSearchedVal([])
            }
        },
        async handlePageChange(page) {
            this.handleScrollToTop()
            if (page !== null) {
                let storePaginationCurrentTab = this.$store.state.storableUnit.currentStorableUnitTab
                let storePagination = this.$store.state.storableUnit
                let dataWithPage = { id: this.currentWarehouseSelected.id, page }

                if (this.search == '' && this.selectedWhCustomers.length === 0) {
                    try {
                        if (storePaginationCurrentTab === 0) {
                            if (storePagination.storableUnitsActive.old_page !== page) {
                                this.fetchActiveNextPageStorableLoading = true
                                await this.fetchStorableUnitsActive(dataWithPage)
                                storePagination.storableUnitsActive.old_page = page
                                this.fetchActiveNextPageStorableLoading = false
                            }
                        } else if (storePaginationCurrentTab === 1) {
                            if (storePagination.storableUnitsHistory.old_page !== page) {
                                this.fetchHistoryNextPageStorableLoading = true
                                await this.fetchStorableUnitsHistory(dataWithPage)
                                storePagination.storableUnitsHistory.old_page = page
                                this.fetchHistoryNextPageStorableLoading = false
                            }
                        }
                        
                    } catch (e) {
                        this.notificationError(e)
                    }
                } else if (this.search !=='' && this.selectedWhCustomers.length === 0) {
                    let data = { search: this.search, page }
                    this.handlePageSearched(data)
                } else {
                    if (this.search !== '' && this.selectedWhCustomers.length > 0) {
                        let	data = {
							filter_data: this.selectedWhCustomers.map(val => val.id),
							search_data: this.search,
							wid: dataWithPage.id,
							page
						}
                        this.apiCallFilterAndSearchCustomer(data)
                    } else {
						if (this.search === '' && this.selectedWhCustomers.length > 0) {
							let	data = {
                                filter_data: this.selectedWhCustomers.map(val => val.id),
                                search_data: this.search,
                                wid: dataWithPage.id,
                                page
                            }
						    this.apiCallFilteredCustomerOnly(data)
						}
					}
                }
            }
        },
        handlePageSearched(data) {
            this.handleScrollToTop()
            let searchedPagination = this.$store.state.storableUnit.searchedStorableUnits

            if (data !== null && this.search !== '') {
                if (searchedPagination.old_page !== data.page) {
                    let warehouse_id = this.currentWarehouseSelected.id

                    let passedData = {
                        method: "GET",
                        url: '',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: this.search,
                            page: data.page
                        }
                    }

                    if (this.currentTab === 0) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storable-units/active`
                        passedData.tab = 'storable-active'
                    } else {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storable-units/history`
                        passedData.tab = 'storable-history'
                    }

                    if (passedData.url !== '') {
                        try {
                            this.fetchSearchedStorableUnits(passedData)
                        } catch(e) {
                            this.notificationError(e)
                            this.setSearchedStorableUnitsLoading(false)
                            console.log(e, 'Search error')
                        }
                    }
                }                
            } else {
                this.setStorableUnitSearchedVal([])
            }
        },
        handleScrollToTop() {
            this.scrollTableToTop()
        },
        // call warehouse customers lists
        async callWarehouseCustomerListsData(dataWithPage) {
			this.setAllWarehouseCustomerLists([])
            let parmsWarehouseCustomers = {
				id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
				page: 1,
                cancelToken: dataWithPage.cancelToken
			}

			try {				
				if (this.getAllWarehouseCustomerListsData.length === 0) {
					this.current_page_is_whcustomers = 1
					await this.fetchWarehouseCustomersDropdown(parmsWarehouseCustomers)

					if (typeof this.getWarehouseCustomersDropdown !== "undefined" && 
						this.getWarehouseCustomersDropdown !== null) {							
						if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
							this.getWarehouseCustomersDropdown.data.length !== "undefined") {
							let data = this.getWarehouseCustomersDropdown.data;
                            data.map(v => {
                                v.isChecked = false
                            })

							this.warehouseCustomerListsData = data
							this.warehouseCustomerLists = data
							this.warehouseCustomerListsCopy = data

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
                            this.fetchWarehouseCustomersLoading = false
						}
					} else {
                        this.fetchWarehouseCustomersLoading = false
						this.warehouseCustomerListsData = []
						this.warehouseCustomerLists = []
						this.warehouseCustomerListsCopy = []
					}
				}
			} catch(e) {
                this.fetchWarehouseCustomersLoading = false
				if (e !== "cancel_previous_request") this.notificationError(e)
			}
		},
		async loadMoreWarehouseCustomerData(parmsWarehouseCustomers) {
			if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
				this.current_page_is_whcustomers++
				parmsWarehouseCustomers.page = this.current_page_is_whcustomers

				try {
					await this.fetchWarehouseCustomersDropdown(parmsWarehouseCustomers)
					if (typeof this.getWarehouseCustomersDropdown !== "undefined" && 
						this.getWarehouseCustomersDropdown !== null) {							
						if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
							this.getWarehouseCustomersDropdown.data.length !== "undefined") {
							let data = this.getWarehouseCustomersDropdown.data;
                            data.map(v => {
                                v.isChecked = false
                            })

							this.warehouseCustomerListsData = [...this.warehouseCustomerListsData, ...data]
							this.warehouseCustomerLists =[...this.warehouseCustomerListsData, ...data]
							this.warehouseCustomerListsCopy =[...this.warehouseCustomerListsData, ...data]

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
						}
					} else {
						this.warehouseCustomerListsData
						this.warehouseCustomerListsCopy
						this.warehouseCustomerLists
					}
				} catch (e) {
					this.notificationError(e)
				}
			}
		},
        removeCustomerListsEmptyOnChange() {
            if (this.selectedWhCustomersCopy.length === 0) {
				this.setStorableFilteredVal([])
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomers = []
                this.selectedWhCustomerReAssignValue = []
			}
            // this.warehouseCustomerListsCopy = this.warehouseCustomerLists
		},
        removeCustomerLists(item, removeIs) {
            if (removeIs === 'single') {
                // this.selectedWhCustomersCopy = this.selectedWhCustomersCopy.filter(val => val.id !== item.id)
                // let index = this.selectedWhCustomersCopy.indexOf(item)
                let indexLodash = _.findIndex(this.selectedWhCustomersCopy, e => (e.id == item.id))	
                
                if (indexLodash > -1){
					this.selectedWhCustomersCopy.splice(indexLodash, 1)
				}
            } else {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
                this.setStorableFilteredVal([])

                if (this.search !== '') {
                    setTimeout(() => {
                        this.filterMenu = false
                        let data = { search: this.search } 
                        this.setSearchedStorableUnitsLoading(true)
                        this.apiCall(data)  
                    }, 200);
                }
            }
            // this.selectedWhCustomers = this.selectedWhCustomersCopy
        },
        // for filter warehouse customers
        searchWarehouseCustomers() {
            if (this.searchCustomerData !== '') {
                this.warehouseCustomerLists = this.warehouseCustomerListsCopy.filter((customer) => {
                    return customer.name.toLowerCase().indexOf(this.searchCustomerData.toLowerCase()) > -1
                })
            } else {
                this.warehouseCustomerLists = this.warehouseCustomerListsCopy
                return this.warehouseCustomerLists
            }
        },
        /* eslint-disable */
        cancelSelectingWarehouseCustomers() {
            if (this.selectedWhCustomers.length === 0) {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
                this.setStorableFilteredVal([])
            } else {
                // if (this.selectedWhCustomers !== this.selectedWhCustomersCopy) {
                //     this.selectedWhCustomersCopy = this.selectedWhCustomers
                // }
                this.selectedWhCustomersCopy = this.selectedWhCustomerReAssignValue.map(({dummy_value_Add_For_same_refrence,...rest}) => ({...rest}));
                this.selectedWhCustomers = this.selectedWhCustomersCopy
            }
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
            this.filterMenu = false
            this.isActiveClicked = false
        },
        /* eslint-enable */
        filterAllWarehouseCustomers() {
            this.setFilteredStorableLoading(false)
            if (this.selectedWhCustomersCopy.length > 0 && this.search === '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilteredCustomerOnly()
            } else if (this.selectedWhCustomersCopy.length > 0 && this.search !== '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilterAndSearchCustomer()
            }
            this.setStorableFilteredVal([])
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
            this.filterMenu = false
            this.isActiveClicked = false
        },
        async apiCallFilteredCustomerOnly() {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)

            if (this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0) {
                this.setFilteredStorableLoading(true)
                var searchParams = new URLSearchParams();

                for (var ser = 0 ; ser < final_selectedWhCustomers.length; ser++) {
					searchParams.append(`ids[]`, final_selectedWhCustomers[ser])
				}
                searchParams.append('page', 1)
                
                let warehouse_id = this.currentWarehouseSelected.id
                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }
                
                if (this.currentTab === 0) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storable-units/active?filter=true`, {params: searchParams}
                    passedData.tab = 'storable-active'
                } else {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storable-units/history?filter=true`, {params: searchParams}
                    passedData.tab = 'storable-history'
                }

                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterStorableCustomers(passedData)                       
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredStorableLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            } else {
                this.setStorableFilteredVal([])
            }
        },
        async apiCallFilterAndSearchCustomer() {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)
            if(this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0 && this.search !=='') {
                this.setFilteredStorableLoading(true)
                var searchParams = new URLSearchParams();
                for (var ser = 0 ;ser < final_selectedWhCustomers.length; ser++) {
					searchParams.append(`ids[]`, final_selectedWhCustomers[ser])
				}

                searchParams.append('page', 1)
                let warehouse_id = this.currentWarehouseSelected.id
                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }

                if (this.currentTab === 0) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storable-units/active?search=${this.search}&filter=true`, {params: searchParams}
                    passedData.tab = 'storable-active'
                } else {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storable-units/history?search=${this.search}&filter=true`, {params: searchParams}
                    passedData.tab = 'storable-history'
                }

                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterStorableCustomers(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredStorableLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            } else {
                this.setStorableFilteredVal([])
            }
        },
        onClickFilter() {
            if (!this.filterMenu) {
                this.filterMenu = true
                var deepCopy = _.cloneDeep(this.selectedWhCustomersCopy);
                this.selectedWhCustomerReAssignValue = deepCopy
                this.selectedWhCustomerReAssignValue = this.selectedWhCustomerReAssignValue.map(v => ({ ...v, dummy_value_Add_For_same_refrence: v.name }))
            }
        },
        clickOutsideFilter() {
            if (this.isActiveClicked) {
                this.filterMenu = false
                this.cancelSelectingWarehouseCustomers()
                this.isActiveClicked = false
            }
        },
        // 
        async moveStorableUnitLocation(item, index) {
            this.storableUnitItems[index].ActiveValue
            
            if (item.location_id !== null) {
                this.currentSelectedLocation = item.location_id

                let getLocationsPayload = {
                    warehouse_id: this.currentWarehouseSelected.id,
                    warehouse_customer_id: item.warehouse_customer_id,
                    page: 1,
                    per_page: 500
                }

                if (this.isWarehouse3PLProvider) {
                    getLocationsPayload.type = 'provider'
                    await this.fetchListsOfLocations(getLocationsPayload)
                } else {
                    getLocationsPayload.type = 'own'
                    await this.fetchListsOfLocations(getLocationsPayload)
                }                
            }
        },
        closeLocationUpdate(index) {
            this.storableUnitItems[index].ActiveValue = false
        },
        setActiveLocationTrue() {
            this.isActiveClickedLocation = true
        },
        async fetchListsOfLocations(getLocationsPayload) {
            this.fetchLocationsListsLoading = true
            this.location_page = 1
            this.locationsLists = []

            try {
                await this.getPlaceStorableLocations(getLocationsPayload)
                
                if (typeof this.getAllWarehouseLocations !== 'undefined' && 
                    this.getAllWarehouseLocations !== null && 
                    typeof this.getAllWarehouseLocations.results !== 'undefined' && 
                    Array.isArray(this.getAllWarehouseLocations.results) &&
                    this.getAllWarehouseLocations.results.length > 0) {
                        
                    this.locationsLists = this.getAllWarehouseLocations.results.map(v => {
                        // let name = v.type === 'storage' ? v.row + v.rack + v.shelf : v.gate_name
                        // let name = v.row + v.rack + v.shelf
                        let name = v.label

                        return {
                            id: v.id,
                            name,
                            storable_count: v.storable_unit_count
                        }
                    })

                    this.lastLocationsListsCheck = this.locationsLists
                    this.fetchLocationsListsLoading = false

                    if (this.location_page < this.getAllWarehouseLocations.last_page) {
                        this.loadMoreLocations(getLocationsPayload)
                    }
                } else {
                    this.locationsLists = []
                    this.lastLocationsListsCheck = []
                    this.fetchLocationsListsLoading = false
                }
            } catch(e) {
                this.fetchLocationsListsLoading = false
                this.notificationError(e)
                console.log(e)
            }
        },
        async loadMoreLocations(getLocationsPayload) {
            if (this.location_page < this.getAllWarehouseLocations.last_page) {
				this.location_page++
                getLocationsPayload.page = this.location_page

				try {
					await this.getPlaceStorableLocations(getLocationsPayload)

                    if (typeof this.getAllWarehouseLocations !== 'undefined' && 
                        this.getAllWarehouseLocations !== null && 
                        typeof this.getAllWarehouseLocations.results !== 'undefined' && 
                        Array.isArray(this.getAllWarehouseLocations.results) &&
                        this.getAllWarehouseLocations.results.length > 0) {
                        
                        let newloaddata = this.getAllWarehouseLocations.results.map(v => {
                            // let name = v.type === 'storage' ? v.row + v.rack + v.shelf : v.gate_name
                            // let name = v.row + v.rack + v.shelf
                            let name = v.label

                            return {
                                id: v.id,
                                name,
                                storable_count: v.storable_unit_count
                            }
                        })

                        this.locationsLists = [...this.locationsLists, ...newloaddata]

                        if (this.location_page < this.getAllWarehouseLocations.last_page) {
                            this.loadMoreLocations(getLocationsPayload)
                        }
                    } else {
                        this.locationsLists;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        async applyLocationUpdate(item, index) {
            if (item !== null) {
                try {
                    let payload = {
                        storable_unit_id: item.id,
                        location_id: this.currentSelectedLocation
                    }

                    await this.updateStorableLocation(payload)
                    this.notificationMessage('Location updated successfully.')

                    let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1 }
                    await this.fetchStorableUnitsActive(dataWithPage)
                } catch (e) {
                    this.notificationError(e)
                    console.log(e);
                }
            }

            this.storableUnitItems[index].ActiveValue = false
            this.isActiveClickedLocation = false
        },
        clearAllFilter() {
            if (this.selectedWhCustomers.length > 0) {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
                this.setStorableFilteredVal([])
            }
        }
    },
    async mounted() {
        //set tab
        if (this.getCurrentStorableUnitTab !== 'undefined') {
            if (this.currentTab !== this.getCurrentStorableUnitTab) {
                this.currentTab = this.getCurrentStorableUnitTab
            }
        }

        if (this.search === '' && this.getStorableSearchData !== '') {
            this.search = this.getStorableSearchData
        }

        let currentInventoryTabName = this.$router.history.current.query.tab
        let checkWarehouseTypeId = this.currentWarehouseSelected !== null ? this.currentWarehouseSelected.warehouse_type_id : null

        if (this.$store.state.page.currentInventoryTab === 1 && 
            typeof currentInventoryTabName !== 'undefined' && 
            currentInventoryTabName === 'Storable Unit') {

            if (this.search === '' && this.storableUnitItems.length === 0) {
                try {
                    this.source = axios.CancelToken.source()
                    let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1, cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }) }
                    await this.fetchStorableUnitsActive(dataWithPage)
                    if (checkWarehouseTypeId !== null && checkWarehouseTypeId === 6) {
                        if (!this.isWarehouseConnected) {
                            await this.callWarehouseCustomerListsData(dataWithPage)
                        }
                    }
                } catch(e) {
                    if (e !== "cancel_previous_request") this.notificationError(e)
                } 
            }

            
        }
    },
    beforeDestroy(){
		if (cancel !== undefined) {
			cancel("cancel_previous_request")
		}
	},
    updated() {}
}
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/inventory/storableUnit/storableTable.scss";
</style>