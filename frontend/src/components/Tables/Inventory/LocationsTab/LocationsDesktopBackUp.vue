<template>
    <div class="locations-desktop-wrapper">
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
            :items="currentSelectedLocations"
            :page.sync="getCurrentPage"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            class="inventory-table locations-table elevation-1"
            v-bind:class="{
                'no-data-table': (typeof currentSelectedLocations !== 'undefined' && currentSelectedLocations.length === 0),
                'no-current-pagination' : (getTotalPage <= 1)
            }"
            hide-default-footer
            fixed-header
            show-select
            ref="my-table">

            <template v-slot:top>
                <div class="inventory-search-wrapper location-search">
                    <v-tabs class="inventory-inner-tab" @change="onTabChange" 
                        v-model="$store.state.locations.locationsTab.typeSubTab">
                        <v-tab v-for="(tab, index) in tabs" 
                            :key="index">
                            {{ tab }}

                            <!-- the code for this is in the backup file -->
                            <!-- <small>{{ getTabCount(index) }}</small>  -->
                        </v-tab>
                    </v-tabs>

                    <!-- <v-tabs class="inventory-inner-tab second" @change="onTypeTabChange" 
                        v-model="$store.state.locations.locationsTab.typeTab">
                        <v-tab v-for="(tab, index) in typeTabs" 
                            :key="index" style="border-radius: 4px;">
                            {{ tab }}
                        </v-tab>
                    </v-tabs> -->

                    <v-spacer></v-spacer>

                    <div class="search-and-filter">
                        <div class="filter-tags-input" v-if="isWarehouse3PLProvider && handleFilterComponent">
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
                                :warehouseCustomerLists.sync="warehouseCustomerLists"
                                :loading="fetchWarehouseCustomersLoading"
                                @clickOutside="clickOutsideFilter"
                                :isActiveClicked.sync="isActiveClicked"
                            />
                        </div>

                        <SearchComponent
                            placeholder="Search Locations"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />

                        <v-btn dark color="primary" class="btn-blue ml-2" @click.stop="addLocation">
                            Add Location
                        </v-btn>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.shelf`]="{ item }">
                <div class="status"> {{ item.shelf !== null && item.shelf !== '' ? item.shelf : '--' }} </div>
            </template>

            <template v-slot:[`item.row`]="{ item }">
                <div class="status"> {{ item.row !== null && item.row !== '' ? item.row : '--' }} </div>
            </template>

            <template v-slot:[`item.rack`]="{ item }">
                <div class="status"> {{ item.rack !== null && item.rack !== '' ? item.rack : '--' }} </div>
            </template>

            <template v-slot:[`item.storable_unit`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="item" v-for="(unit, index) in item.storable_units" :key="index">
                        <p class="mb-0" v-if="item.storable_units.length === 1"> 
                            {{ unit.label }}
                        </p>
                    </div>

                    <div class="item" v-if="item.storable_units.length > 1">
                        <p class="mb-0"> Multiple </p>
                    </div>

                    <div class="item red--text lighten-2" v-if="item.storable_units.length === 0">
                        <p class="mb-0"> Empty </p>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="type">
                        <span class="text-capitalize">{{ item.type }}</span>

                        <!-- {{ getTypeOfStorable(item.storable_unit, item.type) }} -->

                        <!-- <div class="type-content" v-if="item.type === 'storage'">
                            <div class="item" v-for="(unit, index) in item.storable_units" :key="index">
                                <p class="mb-0" v-if="item.storable_units.length === 1"> 
                                    {{ unit.type !== null ? unit.type : '--' }}
                                </p>
                            </div>

                            <p class="mb-0" v-if="item.storable_units.length > 1"> 
                                Multiple
                            </p>

                            <p class="mb-0 " v-if="item.storable_units.length === 0"> 
                                --
                            </p>
                        </div>

                        <div class="type-content" v-if="item.type === 'gate'">
                            <div class="item" v-for="(unit, index) in item.storable_units" :key="index">
                                <p class="mb-0" v-if="item.storable_units.length === 1"> 
                                    {{ unit.type !== null ? unit.type : 'N/A' }}
                                </p>
                            </div>

                            <p class="mb-0" v-if="item.storable_units.length > 1"> 
                                Multiple
                            </p>

                            <p class="mb-0" v-if="item.storable_units.length === 0"> 
                                N/A
                            </p>
                        </div> -->
                    </div>
                </div>
            </template>

            <template v-slot:[`item.ref_no`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="type-content">
                        <div class="item" v-for="(ref, index) in item.ref_no" :key="index">
                            <p class="mb-0" v-if="item.ref_no.length === 1"> 
                                {{ ref.ref_no !== null ? ref.ref_no : '--' }}
                            </p>
                        </div>

                        <p class="mb-0" v-if="item.ref_no.length > 1"> 
                            Multiple
                        </p>

                        <p class="mb-0" v-if="item.ref_no.length === 0"> 
                            --
                        </p>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.warehouse_customer`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="type" :class="item.warehouse_customer_id === null ? 'red--text lighten-2' : ''">
                        {{ 
                            item.warehouse_customer !== null && 
                            item.warehouse_customer.company_name !== null ? 
                            item.warehouse_customer.company_name : 'Empty'
                        }}
                    </div>
                </div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="inventory-wrapper">
                    <div> {{ formatDate(item.updated_at) }}</div>
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions mr-1">
                    <button class="btn-edit inventory-btn-edit" @click.stop="editLocation(item)">
                        <img src="@/assets/icons/edit-inventory.svg" alt=""> Edit
                    </button>

                    <button class="btn-delete ml-2" @click="deleteLoc(item)"  
                    v-if="item.storable_units.length === 0">                        
                        <img src="@/assets/icons/delete-blue.svg" alt="">
                    </button>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="currentLocationsLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper"
                    v-if="!currentLocationsLoading && currentSelectedLocations.length == 0">
                    <div class="no-data-heading" v-if="search === '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 0">
                            <h3> Add Location </h3>
                            <p>
                                Add Location for this warehouse.
                            </p>

                            <div class="d-flex justify-center align-center mt-4">
                                <v-btn color="primary" dark class="btn-blue" @click.stop="addLocation">
                                    Add Location
                                </v-btn>
                            </div>
                        </div>                 

                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 1">
                            <h3> No Filled Locations </h3>
                            <p> There are no filled locations listed. </p>
                        </div>

                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 2">
                            <h3> No Empty Locations </h3>
                            <p> There are no empty locations listed. </p>
                        </div>
                    </div>

                    <div class="no-data-heading" v-if="search !== '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">
                        <div>                           
                            <h3> No Locations found. </h3>
                            <p> There are no locations listed. Try searching another keyword.</p>
                        </div>
                    </div>

                    <div v-if="selectedWhCustomers.length > 0">
						<img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">
                        <div>
                            <h3> No Locations found. </h3>
                            <p> There are no locations listed. Change another Customer.</p>
                        </div>
					</div>
                </div>
            </template>
        </v-data-table>

        <LocationAddDialog
            :dialogLocation.sync="dialogLocation"
            :editedLocationIndex.sync="editedLocationIndex"
            :editedLocationItems.sync="editedLocationItems"
            :defaultLocationItems.sync="defaultLocationItems"
            :currentWarehouseSelected="currentWarehouseSelected"
            :pagination.sync="getCurrentPage"
            :currentLocationTypeTab="$store.state.locations.locationsTab.typeTab === 0 ? 'storage' : 'gate'"
            @close="closeLocation"
            @setLocationToDefault="setLocationToDefault" 
            :searchVal="search"
            :currentSelectedLocations="currentSelectedLocations"
            :locationTypes="locationTypes" />

        <ConfirmDialog :dialogData.sync="dialogLocationDelete">
            <template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2> Delete Locations </h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					Do you want to delete the location
					<span class="name">
						"{{ currentLocationToDelete !== null ? currentLocationToDelete.name : '' }}"
					</span>? 
					Once deleted, this cannot be undone.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="deleteLocationConfirm" text :disabled="getDeleteLocationLoading">
					<span v-if="!getDeleteLocationLoading">Delete</span>
                    <span v-if="getDeleteLocationLoading">Deleting...</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeLocationDelete" :disabled="getDeleteLocationLoading">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

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
import LocationAddDialog from '../../../InventoryComponents/LocationComponents/LocationAddDialog.vue'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'
import ConfirmDialog from '../../../Dialog/GlobalDialog/ConfirmDialog.vue'
import FilterCustomers from '../../../InventoryComponents/FilterCustomers/FilterCustomers.vue'
import globalMethods from '../../../../utils/globalMethods'
import inventoryGlobalMethods from '../../../../utils/inventoryMethods/inventoryGlobalMethods'
import _ from 'lodash'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'InventoryLocationsDesktopTable',
    props: ['currentWarehouseSelected', 'isMobile'],
    components: {
        SearchComponent,
        LocationAddDialog,
        PaginationComponent,
        ConfirmDialog,
        FilterCustomers
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        headersDefault: [
            { 
                text: 'Row',
                align: 'center',
                sortable: false,
                value: 'row',
                fixed: true,
                width: ""
            },
            { 
                text: 'Gate Name',
                align: 'start',
                sortable: false,
                value: 'gate_name',
                fixed: true,
                width: ""
            },
            { 
                text: 'Rack',
                align: 'center',
                sortable: false,
                value: 'rack',
                fixed: true,
                width: ""
            },
            { 
                text: 'Position',
                align: 'start',
                sortable: false,
                value: 'position',
                fixed: true,
                width: ""
            },
            {
                text: 'Shelf',
                align: 'center',
                sortable: false,
                value: 'shelf',
                fixed: true,
                width: ""
            },
            { 
                text: 'Storable Unit',
                align: 'start',
                sortable: false,
                value: 'storable_unit',
                fixed: true,
                width: "" 
            },
            { 
                text: 'Type',
                align: 'start',
                sortable: false,
                value: 'type',
                fixed: true,
                width: "8%"
            },
            { 
                text: 'Reference',
                align: 'start',
                sortable: false,
                value: 'ref_no',
                fixed: true,
                width: "16%"
            },
            { 
                text: 'Truck',
                align: 'start',
                sortable: false,
                value: 'truck_name',
                fixed: true,
                width: ""
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
                text: 'Customer',
                align: 'start',
                sortable: false,
                value: 'warehouse_customer',
                fixed: true,
                width: "20%"
            },
            { 
                text: '', 
                align: 'end',
                value: 'actions', 
                sortable: false,
                fixed: true,
                width: "14%"
            },
        ],
        dialogLocation: false,
        editedLocationIndex: -1,
        editedLocationItems: {
            type: 'storage',
            shelf: '',
            row: '',
            rack: '',
            position: '',
            gate_name: '',
            storable_units: [],
            warehouse_customer_id: null
        },
        defaultLocationItems: {
            type: 'storage',
            shelf: '',
            row: '',
            rack: '',
            position: '',
            gate_name: '',
            storable_units: [],
            warehouse_customer_id: null
        },
        tabs: ["All", "Filled", "Empty"],
        typeTabs: ["Storage"],
        // typeTabs: ["Storage", 'Gates'],
        currentTab: 0,
        currentTypeTab: 0,
        dialogLocationDelete: false,
        currentLocationToDelete: null,
        allStorageLocationsNextLoading: false,
        filledStorageLocationsNextLoading: false,
        emptyStorageLocationsNextLoading: false,
        allGateLocationsNextLoading: false,
        filledGateLocationsNextLoading: false,
        emptyGateLocationsNextLoading: false,
        typingTimeout: 0,
        // warehouse customers data
		lastCheckWHData: [],
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
        selectedWhCustomersReAssignValue:[]
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getLocations: 'locations/getLocations',
            getLocationsLoading: 'locations/getLocationsLoading',
            getDeleteLocationLoading: 'locations/getDeleteLocationLoading',
            getStorageLocations: 'locations/getStorageLocations',
            getStorageLocationsLoading: 'locations/getStorageLocationsLoading',
            getGateLocations: 'locations/getGateLocations',
            getGateLocationsLoading: 'locations/getGateLocationsLoading',
            // storage locations
            getStorageAllPagination: 'locations/getStorageAllPagination',
            getStorageFilledPagination: 'locations/getStorageFilledPagination',
            getStorageEmptyPagination: 'locations/getStorageEmptyPagination',
            // gate locations
            getGateAllPagination: 'locations/getGateAllPagination',
            getGateFilledPagination: 'locations/getGateFilledPagination',
            getGateEmptyPagination: 'locations/getGateEmptyPagination',
            // storage loadings
            getStorageAllPaginationLoading: 'locations/getStorageAllPaginationLoading',
            getStorageFilledPaginationLoading: 'locations/getStorageFilledPaginationLoading',
            getStorageEmptyPaginationLoading: 'locations/getStorageEmptyPaginationLoading',
            // gate loadings
            getGateAllPaginationLoading: 'locations/getGateAllPaginationLoading',
            getGateFilledPaginationLoading: 'locations/getGateFilledPaginationLoading',
            getGateEmptyPaginationLoading: 'locations/getGateEmptyPaginationLoading',
            poBaseUrlState: 'products/poBaseUrlState',
            getSearchedLocations: 'locations/getSearchedLocations',
            getSearchedLocationsLoading: 'locations/getSearchedLocationsLoading',
            getSearchedLocationsItems: 'locations/getSearchedLocationsItems',
            getAllWarehouseCustomerListsData: 'warehouseCustomers/getAllWarehouseCustomerListsData',
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            // filter and search
            getFilteredLocationsLoading:'locations/getFilteredLocationsLoading',
            getFilteredLocations: 'locations/getFilteredLocations',
            getLocationTypes: 'locations/getLocationTypes',
            getLocationTypesLoading: 'locations/getLocationTypesLoading'
        }), 
        storageLocationsListsData() {
            let locationLists = {}
            let storeLocationsItems = this.$store.state.locations.locationsTab
            let currentTypeSubTab = storeLocationsItems.typeSubTab

            if (typeof this.getSearchedLocations !== 'undefined' && this.getSearchedLocations !== null && 
                this.getFilteredLocations !== 'undefined' && this.getFilteredLocations !== null) {
                if (this.search !== '' && currentTypeSubTab === 0 && 
                    this.getSearchedLocations.currentTabName === 'storage-all') {
                    locationLists = this.getSearchedLocations
                } else if (this.search !== '' && currentTypeSubTab === 1 && 
                    this.getSearchedLocations.currentTabName === 'storage-filled') {
                    locationLists = this.getSearchedLocations
                } else if (this.search !== '' && currentTypeSubTab === 2 && 
                    this.getSearchedLocations.currentTabName === 'storage-empty') {
                    locationLists = this.getSearchedLocations
                } else {
                    if (currentTypeSubTab === 0) {
                        if (this.selectedWhCustomers.length > 0 && this.getFilteredLocations.currentTabName === 'storage-all') {
                            locationLists = this.getFilteredLocations
                        } else {
                            locationLists = this.getStorageAllPagination
                        }
                    } else if (currentTypeSubTab == 1) {
                        if (this.selectedWhCustomers.length > 0 && this.getFilteredLocations.currentTabName === 'storage-filled') {
                            locationLists = this.getFilteredLocations
                        } else {
                            locationLists = this.getStorageFilledPagination
                        }
                    } else if (currentTypeSubTab == 2) {
                        if (this.selectedWhCustomers.length > 0 && this.getFilteredLocations.currentTabName === 'storage-empty') {
                            locationLists = this.getFilteredLocations
                        } else {
                            locationLists = this.getStorageEmptyPagination
                        }
                    }
                }
            }

            return locationLists
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
        // gateLocationsListsData() {},
		currentSelectedLocations() {
            // let getLocationsData = this.currentTab === 0 ? this.storageLocationsListsData : this.gateLocationsListsData
            let getLocationsData = this.storageLocationsListsData
            return getLocationsData.items
        },
        currentLocationsLoading() {
            if (this.$store.state.locations.locationsTab.typeTab == 0) {
                if (this.$store.state.locations.locationsTab.typeSubTab == 0) {
                    return this.getStorageAllPaginationLoading
                } else if (this.$store.state.locations.locationsTab.typeSubTab == 1) {
                    return this.getStorageFilledPaginationLoading
                } else {
                    return this.getStorageEmptyPaginationLoading
                }
            } else {
                if (this.$store.state.locations.locationsTab.typeSubTab == 0) {
                    return this.getGateAllPaginationLoading
                } else if (this.$store.state.locations.locationsTab.typeSubTab == 1) {
                    return this.getGateFilledPaginationLoading
                } else {
                    return this.getGateEmptyPaginationLoading
                }
            }
        },
        getTotalPage: {
            get() {
                // let getLocationsData = this.currentTab === 0 ? this.storageLocationsListsData : this.gateLocationsListsData
                let getLocationsData = this.storageLocationsListsData
                return getLocationsData.last_page
            }
        },
        getCurrentPage: {
            get() {
                // let getLocationsData = this.currentTab === 0 ? this.storageLocationsListsData : this.gateLocationsListsData
                let getLocationsData = this.storageLocationsListsData
                return getLocationsData.current_page
            },
            set() {
                return {}
            }
        }, 
        getCurrentLoadingToDisplay() {
            if (this.search === '' && this.selectedWhCustomers.length === 0) {
                return this.getHandlePageLoading
            }else if(this.selectedWhCustomers.length > 0) {
                return this.getFilteredLocationsLoading
            } else {
                return this.getSearchedLocationsLoading
            }
        },
        getHandlePageLoading() {
            let storeLocationsTab = this.$store.state.locations.locationsTab

            if (storeLocationsTab.typeTab === 0) {
                if (storeLocationsTab.typeSubTab === 0) {
                    return this.allStorageLocationsNextLoading
                } else if (storeLocationsTab.typeSubTab === 1) {
                    return this.filledStorageLocationsNextLoading
                } else {
                    return this.emptyStorageLocationsNextLoading
                }
            } else {
                if (storeLocationsTab.typeSubTab === 0) {
                    return this.allGateLocationsNextLoading
                } else if (storeLocationsTab.typeSubTab === 1) {
                    return this.filledGateLocationsNextLoading
                } else {
                    return this.emptyGateLocationsNextLoading
                }
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.currentSelectedLocations.length === 0 && this.selectedWhCustomers.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentSelectedLocations.length === 0) {
                isShow = true
            } else if (this.selectedWhCustomers.length > 0 && this.currentSelectedLocations.length === 0) {
                isShow = false
            }

            return isShow
        },
        handleFilterComponent() {
            let isShow = true

            if (this.search == '' && this.selectedWhCustomers.length === 0 && this.currentSelectedLocations.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentSelectedLocations.length === 0) {
                isShow = true
            } else if (this.search !== '' || this.currentSelectedLocations.length > 0) {
				isShow = true
			}
            return isShow
        },
        headersComputed() {
            let defaultHeaders = this.headersDefault

            if (this.isWarehouse3PLProvider) {
                if (this.$store.state.locations.locationsTab.typeTab === 0) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'Gate Name' && v.text !== 'Position' && v.text !== 'Truck' && v.text !== 'Updated At'
                    })
                } 
                // add the headers for gate type here 
            } else {
                if (this.$store.state.locations.locationsTab.typeTab === 0) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'Gate Name' && v.text !== 'Position' && v.text !== 'Truck' && v.text !== 'Customer'
                    })
                }
                // add the headers for gate type here                
            }

            return defaultHeaders
        },
        locationTypes() {
            let locationTypes = []

            if (typeof this.getLocationTypes !== 'undefined' && this.getLocationTypes !== null) {
                if (typeof this.getLocationTypes.results !== 'undefined' && this.getLocationTypes.results.length > 0) {
                    locationTypes = this.getLocationTypes.results
                }
            }

            return locationTypes
        }
    },  
    methods: {
        ...mapActions({
            fetchLocations: 'locations/fetchLocations',
            deleteLocation: 'locations/deleteLocation',
            // storage locations
            fetchAllStorageLocations: 'locations/fetchAllStorageLocations',
            fetchFilledStorageLocations: 'locations/fetchFilledStorageLocations',
            fetchEmptyStorageLocations: 'locations/fetchEmptyStorageLocations',
            // gate locations
            fetchAllGateLocations: 'locations/fetchAllGateLocations',
            fetchFilledGateLocations: 'locations/fetchFilledGateLocations',
            fetchEmptyGateLocations: 'locations/fetchEmptyGateLocations',
            // tabs
            setCurrentLocationTypeTab: 'locations/setCurrentLocationTypeTab',
            setCurrentLocationTypeSubTab: 'locations/setCurrentLocationTypeSubTab',
            fetchSearchedLocation: 'locations/fetchSearchedLocation',
            setSearchedLocationsLoading: 'locations/setSearchedLocationsLoading',
            setLocationSearchedVal: 'locations/setLocationSearchedVal',
            setEmptyInventoryLocationsData: 'locations/setEmptyInventoryLocationsData',
            fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
			setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
            setLocationsFilteredVal: 'locations/setLocationsFilteredVal',
            setFilteredLocationsLoading: 'locations/setFilteredLocationsLoading',
            fetchFilterLocationsCustomers: 'locations/fetchFilterLocationsCustomers',
            fetchLocationsTypes: 'locations/fetchLocationsTypes'
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        setEmptyValues() {
            this.search = ''
            this.setLocationSearchedVal([])
            this.setLocationsFilteredVal([])
            this.selectedWhCustomers = []
            this.selectedWhCustomersCopy = []
            this.selectedWhCustomersReAssignValue = []
        },
        async onTypeTabChange(i) {
            this.setCurrentLocationTypeTab(i)
            this.setCurrentLocationTypeSubTab(0)
            this.setEmptyInventoryLocationsData([])
            this.setEmptyValues()

            let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1 }
            if (this.$store.state.locations.locationsTab.typeTab == 0) {
                await this.fetchAllStorageLocations(dataWithPage)
                await this.fetchFilledStorageLocations(dataWithPage)
                await this.fetchEmptyStorageLocations(dataWithPage)
            } else {
                await this.fetchAllGateLocations(dataWithPage)
                await this.fetchFilledGateLocations(dataWithPage)
                await this.fetchEmptyGateLocations(dataWithPage)
            }
        },
        async onTabChange(i) {
            this.handleScrollToTop()
            this.$store.state.locations.locationsTab.typeSubTab = i
            this.setCurrentLocationTypeSubTab(i)
            this.setEmptyValues()

            let storePagination = this.$store.state.locations
            let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1 }

            if (storePagination.locationsTab.typeTab == 0) {
                if (storePagination.locationsTab.typeSubTab == 0) {
                    dataWithPage.page = storePagination.storageAllPagination.current_page
                    await this.fetchAllStorageLocations(dataWithPage)
                    storePagination.storageAllPagination.old_page = storePagination.storageAllPagination.current_page

                } else if (storePagination.locationsTab.typeSubTab == 1) {
                    dataWithPage.page = storePagination.storageFilledPagination.current_page
                    await this.fetchFilledStorageLocations(dataWithPage)
                    storePagination.storageFilledPagination.old_page = storePagination.storageFilledPagination.current_page

                } else {
                    dataWithPage.page = storePagination.storageEmptyPagination.current_page
                    await this.fetchEmptyStorageLocations(dataWithPage)
                    storePagination.storageEmptyPagination.old_page = storePagination.storageEmptyPagination.current_page
                }                
            } else {
                if (storePagination.locationsTab.typeSubTab == 0) {
                    dataWithPage.page = storePagination.gateAllPagination.current_page
                    await this.fetchAllGateLocations(dataWithPage)
                    storePagination.gateAllPagination.old_page = storePagination.gateAllPagination.current_page

                } else if (storePagination.locationsTab.typeSubTab == 1) {
                    dataWithPage.page = storePagination.gateFilledPagination.current_page
                    await this.fetchFilledGateLocations(dataWithPage)
                    storePagination.gateFilledPagination.old_page = storePagination.gateFilledPagination.current_page

                } else {
                    dataWithPage.page = storePagination.gateEmptyPagination.current_page
                    await this.fetchEmptyGateLocations(dataWithPage)
                    storePagination.gateEmptyPagination.old_page = storePagination.gateEmptyPagination.current_page
                }
            }
        },
        formatDate(date) {
            return this.formatTimeAndDateTogether(date)
        },
        getTypeOfStorable(item, storableType) {
            let unitType = ""

            if (typeof item !== 'undefined' && item !== null) {
                if (typeof item.type !== 'undefined' && item.type !== null) {
                    unitType = item.type
                }
            }

            let finalType = 
                storableType === 'gate' ? (unitType !== '' ? unitType : 'N/A') : (unitType !== '' ? unitType : '--')            

            return finalType
        },
        async addLocation() {
            this.dialogLocation = true
            this.$nextTick(() => {
                this.editedLocationItems = Object.assign({}, this.defaultLocationItems)
                this.editedLocationIndex = -1;
            })

            if (this.locationTypes.length === 0) {
                await this.fetchLocationsTypes()
            }
        },
        closeLocation() {
            this.dialogLocation = false
            this.$nextTick(() => {
                this.editedLocationItems = Object.assign({}, this.defaultLocationItems)
                this.editedLocationIndex = -1;
            })
        },
        setLocationToDefault() {
            this.$nextTick(() => {
                this.editedLocationItems = Object.assign({}, this.defaultLocationItems)
                this.editedLocationIndex = -1;
            })
        },
        async editLocation(location) {
            this.editedLocationIndex = this.currentSelectedLocations.indexOf(location)
			this.editedLocationItems = Object.assign({}, this.currentSelectedLocations[this.editedLocationIndex])
            this.dialogLocation = true

            if (this.locationTypes.length === 0) {
                await this.fetchLocationsTypes()
            }
        },
        deleteLoc(location) {
            this.dialogLocationDelete = true
            let storageName = location.row + location.rack + location.shelf
            this.currentLocationToDelete = {
                id: location.id,
                name: location.type === 'storage' ? `${storageName}` : location.gate_name,
                wid: location.warehouse_id
            }
        },
        async deleteLocationConfirm() {
            if (this.currentLocationToDelete !== null) {
                try {
                    let payloadToDelete =  { location_id: this.currentLocationToDelete.id, wid: this.currentLocationToDelete.wid }
                    await this.deleteLocation(payloadToDelete)
                    this.notificationCustom('Location successfully deleted.')
                    this.closeLocationDelete()

                    // FOR STORAGE ONLY
                    let storePagination = this.$store.state.locations.locationsTab.typeSubTab === 0 ? 
                        this.$store.state.locations.storageAllPagination : 
                        (this.$store.state.locations.locationsTab.typeSubTab === 2 ? 
                        this.$store.state.locations.storageEmptyPagination : 1)

					let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
                    let dataWithPage = { id: this.currentWarehouseSelected.id, page: this.getCurrentPage }

					if (storePagination.items.length === 1 && storePagination.current_page !== 1) {
						dataWithPage.page = page - 1
					}

                    if (this.search !== '') {
                        let searchedPage = typeof this.getSearchedLocations !== 'undefined' ? this.getSearchedLocations.current_page : 1

						if (this.currentSelectedLocations.length === 1 && this.getSearchedLocations.current_page !== 1) {
							searchedPage = searchedPage - 1
						}
						
						await this.fetchLocationSearchAPI(searchedPage)

                        // if current tab is all
                        if (this.$store.state.locations.locationsTab.typeSubTab === 0) {
                            dataWithPage.page = 1
                            await this.fetchAllStorageLocations(dataWithPage)                            
                            await this.fetchEmptyStorageLocations(dataWithPage)
                        // else if current tab is empty
                        } else if (this.$store.state.locations.locationsTab.typeSubTab === 2) {
                            dataWithPage.page = 1
                            await this.fetchEmptyStorageLocations(dataWithPage)                            
                            await this.fetchAllStorageLocations(dataWithPage)
                        }
                    } else {
                        // if current tab is all
                        if (this.$store.state.locations.locationsTab.typeSubTab === 0) {
                            await this.fetchAllStorageLocations(dataWithPage)

                            dataWithPage.page = 1
                            await this.fetchEmptyStorageLocations(dataWithPage)
                        // else if current tab is empty
                        } else if (this.$store.state.locations.locationsTab.typeSubTab === 2) {
                            await this.fetchEmptyStorageLocations(dataWithPage)

                            dataWithPage.page = 1
                            await this.fetchAllStorageLocations(dataWithPage)
                        }
                    }
                } catch(e) {
                    this.closeLocationDelete()
                    this.notificationError(e)
                }
            }
        },
        closeLocationDelete() {
            this.dialogLocationDelete = false
            this.currentLocationToDelete = null
        },
        clearSearched() {
            this.search = ''
            this.setLocationSearchedVal([])
            if(this.isWarehouse3PLProvider) {
				if(this.selectedWhCustomers.length > 0) {
					this.setLocationsFilteredVal([])
					this.filterAllWarehouseCustomers()
				}
			}
        },
        handleSearch() {
            if (cancel !== undefined) {
                cancel()
            }
            this.setSearchedLocationsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { search: this.search }
                if (this.selectedWhCustomers.length > 0) {
                    return this.filterAllWarehouseCustomers()
                } else {
                    this.setSearchedLocationsLoading(true)
                    this.apiCall(data)
                }
            }, 500)
        },
        apiCall(data) {
            let storePagination = this.$store.state.locations

            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: "",
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        search: this.search,
                        page: 1
                    }
                }

                let warehouse_id = this.currentWarehouseSelected.id
                if (storePagination.locationsTab.typeTab == 0) {
                    if (storePagination.locationsTab.typeSubTab == 0) {
                        // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/all`
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/all`
                        passedData.tab = 'storage-all'
                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/filled`
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/filled`
                        passedData.tab = 'storage-filled'
                    } else {
                        // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/empty`
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/empty`
                        passedData.tab = 'storage-empty'
                    }
                } else {
                    if (storePagination.locationsTab.typeSubTab == 0) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/all`
                        passedData.tab = 'gate-all'
                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/filled`
                        passedData.tab = 'gate-filled'
                    } else {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/empty`
                        passedData.tab = 'gate-empty'
                    }
                }

                if (passedData.url !== '') {
                    try {
                        this.fetchSearchedLocation(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedLocationsLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.setLocationSearchedVal([])
            }
        },
        // if search is empty and click pagination
        async handlePageChange(page) {
            this.handleScrollToTop()
            if (page !== null) {
                let storeLocationsTab = this.$store.state.locations.locationsTab
                let storePagination = this.$store.state.locations
                let dataWithPage = { id: this.currentWarehouseSelected.id, page }

                if (this.search == '' && this.selectedWhCustomers.length === 0) {
                    try {
                        if (storeLocationsTab.typeTab == 0) {
                            if (storeLocationsTab.typeSubTab === 0) {
                                if (storePagination.storageAllPagination.old_page !== page) {
                                    this.allStorageLocationsNextLoading = true
                                    await this.fetchAllStorageLocations(dataWithPage)
                                    storePagination.storageAllPagination.old_page = page
                                    this.allStorageLocationsNextLoading = false
                                }
                            } else if (storeLocationsTab.typeSubTab === 1) {
                                if (storePagination.storageFilledPagination.old_page !== page) {
                                    this.filledStorageLocationsNextLoading = true
                                    await this.fetchFilledStorageLocations(dataWithPage)
                                    storePagination.storageFilledPagination.old_page = page
                                    this.filledStorageLocationsNextLoading = false
                                }
                            } else {
                                if (storePagination.storageFilledPagination.old_page !== page) {
                                    this.emptyStorageLocationsNextLoading = true
                                    await this.fetchEmptyStorageLocations(dataWithPage)
                                    storePagination.storageFilledPagination.old_page = page
                                    this.emptyStorageLocationsNextLoading = false
                                }
                            }
                        } else {
                            if (storeLocationsTab.typeSubTab === 0) {
                                if (storePagination.gateAllPagination.old_page !== page) {
                                    this.allGateLocationsNextLoading = true
                                    await this.fetchAllGateLocations(dataWithPage)
                                    storePagination.gateAllPagination.old_page = page
                                    this.allGateLocationsNextLoading = false
                                }
                            } else if (storeLocationsTab.typeSubTab === 1) {
                                if (storePagination.gateFilledPagination.old_page !== page) {
                                    this.filledGateLocationsNextLoading = true
                                    await this.fetchFilledGateLocations(dataWithPage)    
                                    storePagination.gateFilledPagination.old_page = page
                                    this.filledGateLocationsNextLoading = false
                                }
                            } else {
                                if (storePagination.gateFilledPagination.old_page !== page) {
                                    this.emptyGateLocationsNextLoading = true
                                    await this.fetchEmptyGateLocations(dataWithPage)
                                    storePagination.gateFilledPagination.old_page = page
                                    this.emptyGateLocationsNextLoading = false
                                }
                            }
                        }
                    } catch(e) {
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
        // if search is not empty and click pagination
        async handlePageSearched(data) {
            this.handleScrollToTop()
            let storePagination = this.$store.state.locations
            let searchedPagination = this.$store.state.locations.searchedLocations

            if (data !== null && this.search !== '') {
                if (searchedPagination.old_page !== data.page) {
                    let passedData = {
                        method: "get",
                        url: "",
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: this.search,
                            page: data.page
                        }
                    }

                    let warehouse_id = this.currentWarehouseSelected.id
                    if (storePagination.locationsTab.typeTab == 0) {
                        if (storePagination.locationsTab.typeSubTab == 0) {
                            // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/all`
                            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/all`
                            passedData.tab = 'storage-all'
                        } else if (storePagination.locationsTab.typeSubTab == 1) {
                            // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/filled`
                            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/filled`
                            passedData.tab = 'storage-filled'
                        } else {
                            // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/empty`
                            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/empty`
                            passedData.tab = 'storage-empty'
                        }
                    } else {
                        if (storePagination.locationsTab.typeSubTab == 0) {
                            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/all`
                            passedData.tab = 'gate-all'
                        } else if (storePagination.locationsTab.typeSubTab == 1) {
                            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/filled`
                            passedData.tab = 'gate-filled'
                        } else {
                            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/empty`
                            passedData.tab = 'gate-empty'
                        }
                    }

                    if (passedData.url !== '') {
                        try {
                            this.fetchSearchedLocation(passedData)
                        } catch(e) {
                            this.notificationError(e)
                            this.setSearchedLocationsLoading(false)
                            console.log(e, 'Search error')
                        }
                    }
                }                
            } else {
                this.setLocationSearchedVal([])
            }
        },
        handleScrollToTop() {
            this.scrollTableToTop()
        },
        async fetchLocationSearchAPI(page) {
            let storePagination = this.$store.state.locations

            let passedData = {
                method: "get",
                url: "",
                params: {
                    search: this.search,
                    page
                }
            }

            let warehouse_id = this.currentWarehouseSelected.id
            if (storePagination.locationsTab.typeTab == 0) {
                if (storePagination.locationsTab.typeSubTab == 0) {
                    // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/all`
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/all`
                    passedData.tab = 'storage-all'
                } else if (storePagination.locationsTab.typeSubTab == 1) {
                    // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/filled`
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/filled`
                    passedData.tab = 'storage-filled'
                } else {
                    // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/empty`
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/empty`
                    passedData.tab = 'storage-empty'
                }
            } else {
                if (storePagination.locationsTab.typeSubTab == 0) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/all`
                    passedData.tab = 'gate-all'
                } else if (storePagination.locationsTab.typeSubTab == 1) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/filled`
                    passedData.tab = 'gate-filled'
                } else {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/empty`
                    passedData.tab = 'gate-empty'
                }
            }

            if (passedData.url !== '') {
                try {
                    await this.fetchSearchedLocation(passedData)
                } catch(e) {
                    this.notificationError(e)
                    this.setSearchedLocationsLoading(false)
                    console.log(e, 'Search error')
                }
            }
        },        
        // call warehouse customers api
        async callWarehouseCustomerListsData() {
			this.setAllWarehouseCustomerLists([])
            let parmsWarehouseCustomers = {
				id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
				page: 1
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
				this.notificationError(e)
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
				this.setLocationsFilteredVal([])
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomers = []
                this.selectedWhCustomersReAssignValue = []
			}
            // this.warehouseCustomerListsCopy = this.warehouseCustomerLists
		},
        removeCustomerLists(item, removeIs) {
            if (removeIs === 'single') {
                let index = this.selectedWhCustomersCopy.indexOf(item)
                if (index >= 0) {
                    this.selectedWhCustomersCopy.splice(index, 1)
                }
            } else {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomersReAssignValue = []
                this.setLocationsFilteredVal([])

                if (this.search !== '') {
                    setTimeout(() => {
                        this.filterMenu = false
                        let data = { search: this.search } 
                        this.setSearchedLocationsLoading(true)
                        this.apiCall(data)  
                    }, 200);
                }
            }
            this.selectedWhCustomers = this.selectedWhCustomersCopy
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
                this.selectedWhCustomersReAssignValue = []
                this.setLocationsFilteredVal([])
            } else {
                // if (this.selectedWhCustomers !== this.selectedWhCustomersCopy) {
                //     this.selectedWhCustomersCopy = this.selectedWhCustomers
                // }
                this.selectedWhCustomersCopy = this.selectedWhCustomersReAssignValue.map(({dummy_value_Add_For_same_refrence,...rest}) => ({...rest}))
            }
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
            this.filterMenu = false
            this.isActiveClicked = false
        },
        /* eslint-enable */
        filterAllWarehouseCustomers() {
            this.setFilteredLocationsLoading(false)
            if (this.selectedWhCustomersCopy.length > 0 && this.search === '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilteredCustomerOnly()
            } else if (this.selectedWhCustomersCopy.length > 0 && this.search !== '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilterAndSearchCustomer()
            }
            this.setLocationsFilteredVal([])
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
            this.filterMenu = false
            this.isActiveClicked = false
        },
        async apiCallFilteredCustomerOnly() {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)

            if (this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0) {
                let storePagination = this.$store.state.locations
                this.setFilteredLocationsLoading(true)
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

                if (storePagination.locationsTab.typeTab == 0) {
                    if (storePagination.locationsTab.typeSubTab == 0) {
                        // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/all?filter=true`, {params: searchParams}
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/all?filter=true`, {params: searchParams}
                        passedData.tab = 'storage-all'
                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/filled?filter=true`, {params: searchParams}
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/filled?filter=true`, {params: searchParams}
                        passedData.tab = 'storage-filled'
                    } else {
                        // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/empty?filter=true`, {params: searchParams}
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/empty?filter=true`, {params: searchParams}
                        passedData.tab = 'storage-empty'
                    }
                } else {
                    if (storePagination.locationsTab.typeSubTab == 0) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/all?filter=true`, {params: searchParams}
                        passedData.tab = 'gate-all'
                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/filled?filter=true`, {params: searchParams}
                        passedData.tab = 'gate-filled'
                    } else {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/empty?filter=true`, {params: searchParams}
                        passedData.tab = 'gate-empty'
                    }
                }
                
                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterLocationsCustomers(passedData)                       
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredLocationsLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            } else {
                this.setLocationsFilteredVal([])
            }
        },
        async apiCallFilterAndSearchCustomer() {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)
            if(this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0 && this.search !=='') {
                let storePagination = this.$store.state.locations
                this.setFilteredLocationsLoading(true)
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

                if (storePagination.locationsTab.typeTab == 0) {
                    if (storePagination.locationsTab.typeSubTab == 0) {
                        // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/all?search=${this.search}&filter=true`, {params: searchParams}
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/all?search=${this.search}&filter=true`, {params: searchParams}
                        passedData.tab = 'storage-all'
                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/filled?search=${this.search}&filter=true`, {params: searchParams}
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/filled?search=${this.search}&filter=true`, {params: searchParams}
                        passedData.tab = 'storage-filled'
                    } else {
                        // passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/empty?search=${this.search}&filter=true`, {params: searchParams}
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/empty?search=${this.search}&filter=true`, {params: searchParams}
                        passedData.tab = 'storage-empty'
                    }
                } else {
                    if (storePagination.locationsTab.typeSubTab == 0) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/all?search=${this.search}&filter=true`, {params: searchParams}
                        passedData.tab = 'gate-all'
                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/filled?search=${this.search}&filter=true`, {params: searchParams}
                        passedData.tab = 'gate-filled'
                    } else {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/gate-location/empty?search=${this.search}&filter=true`, {params: searchParams}
                        passedData.tab = 'gate-empty'
                    }
                }

                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterLocationsCustomers(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredLocationsLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            } else {
                this.setLocationsFilteredVal([])
            }
        },
        onClickFilter() {
            if (!this.filterMenu) {
                this.filterMenu = true
                var deepCopy = _.cloneDeep(this.selectedWhCustomersCopy);
				this.selectedWhCustomersReAssignValue = deepCopy
				this.selectedWhCustomersReAssignValue = this.selectedWhCustomersReAssignValue.map(v => ({ ...v, dummy_value_Add_For_same_refrence: v.name }))
            }
        },
        clickOutsideFilter() {
            if (this.isActiveClicked) {
                this.filterMenu = false
                this.cancelSelectingWarehouseCustomers()
                this.isActiveClicked = false
            }
        },
    },
    async mounted() {
        let currentInventoryTabName = this.$router.history.current.query.tab
        let checkWarehouseTypeId = this.currentWarehouseSelected !== null ? this.currentWarehouseSelected.warehouse_type_id : null

        if (this.$store.state.page.currentInventoryTab === 2 && 
            typeof currentInventoryTabName !== 'undefined' && 
            currentInventoryTabName === 'Locations') {
                
            try {
                let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1 }
                await this.fetchAllStorageLocations(dataWithPage)
            } catch(e) {
                this.notificationError(e)
            }

            if (checkWarehouseTypeId !== null && checkWarehouseTypeId === 6) {
                await this.callWarehouseCustomerListsData()
            }
        }
    },
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/location/locationTable.scss';
</style>