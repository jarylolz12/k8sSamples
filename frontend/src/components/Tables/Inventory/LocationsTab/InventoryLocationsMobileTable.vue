<template>
    <div class="location-mobile">
        <div class="overlay" :class="search == '' && getHandlePageLoading ? 'show' : ''">  
            <div class="preloader" v-if="(search == '' && getHandlePageLoading)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>

        <div class="overlay search" :class="search !== '' && getSearchedLocationsLoading ? 'show' : ''">  
            <div class="preloader" v-if="(search !== '' && getSearchedLocationsLoading)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>
        
        <v-data-table
            :headers="currentTypeTab == 0 ? headersStorage : headersGate"
            :items="currentSelectedLocations"
            :page.sync="getCurrentPage"
            :items-per-page="getItemPerPage()"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            class="inventory-table storage-locations elevation-1"
            v-bind:class="{
                'no-data-table': (typeof currentSelectedLocations !== 'undefined' && currentSelectedLocations.length === 0),
                'no-current-pagination' : (getTotalPage <= 1)
            }"
            hide-default-footer
            fixed-header
            show-select
            v-if="currentTypeTab === 0">

            <template v-slot:top>
                <div class="inventory-search-wrapper">
                    <div class="locations-mobile-tabs">
                        <v-tabs class="inventory-inner-tab first" @change="onTabChange" 
                            v-model="$store.state.locations.locationsTab.typeSubTab">
                            <v-tab v-for="(tab, index) in tabs" 
                                :key="index">
                                {{ tab }}

                                <small>{{ getTabCount(index) }}</small>
                            </v-tab>
                        </v-tabs>

                        <v-tabs class="inventory-inner-tab second" @change="onTypeTabChange" 
                            v-model="$store.state.locations.locationsTab.typeTab">
                            <v-tab v-for="(tab, index) in typeTabs" 
                                :key="index">
                                {{ tab }}
                            </v-tab>
                        </v-tabs>
                    </div>

                    <v-spacer></v-spacer>

                    <div class="search-and-filter">
                        <div class="search-wrapper" v-if="handleSearchComponent">
                            <img src="@/assets/images/search-icon.svg" alt="">

                            <input
                                class="search" 
                                type="text"
                                id="search-input"
                                v-model.trim="search"
                                placeholder="Search Locations"
                                @input="handleSearch"
                                autocomplete="off" />
                        </div>

                        <v-btn dark color="primary" class="btn-blue ml-2" @click.stop="addLocation">
                            Add Location
                        </v-btn>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.row`]="{ item }">
                <div class="location-items-wrapper">
                    <div>
                        <div class="location-items" v-if="item.type === 'storage'">
                            <span class="name">{{ item.shelf }}</span>
                            <span class="name">{{ item.row }}</span>
                            <span class="name">{{ item.rack }}</span>
                        </div>

                        <div class="location-items" v-else>
                            <span class="name">{{ item.gate_name }}</span>
                        </div>

                        <div class="location-items"> 
                            <div class="item" v-for="(unit, index) in item.storable_units" :key="index">
                                <p class="mb-0" v-if="item.storable_units.length === 1"> 
                                    {{ unit.label }}
                                </p>
                            </div>

                            <div class="item" v-if="item.storable_units.length > 1">
                                <p class="mb-0"> 
                                    Multiple Storable Units
                                </p>
                            </div>

                            <div class="item red--text lighten-2" v-if="item.storable_units.length === 0">
                                <p class="mb-0"> 
                                    Empty
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <button class="btn-edit mr-1 inventory-btn-edit" @click.stop="editLocation(item)">
                            <img src="@/assets/icons/edit-inventory.svg" alt="">
                        </button>

                        <button class="btn-delete" @click="deleteLoc(item)">                        
                            <img src="@/assets/icons/delete-blue.svg" alt="">
                        </button>

                      <button class="inventory-btn-print ml-1"  @click="$emit('printLocationLabel',item)">
                        <img src="@/assets/icons/print.svg" alt="">
                      </button>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.rack`]="{ item }">
                <div class="location-items-wrapper mb-1">
                    <div class="location-items"> 
                        <span class="location-type">
                            Type:
                            <div class="type-content" v-if="item.type === 'storage'">
                                <div class="item" v-for="(unit, index) in item.storable_units" :key="index">
                                    <p class="mb-0" v-if="item.storable_units.length === 1"> 
                                        {{ unit.type }}
                                    </p>
                                </div>

                                <p class="mb-0" v-if="item.storable_units.length > 1"> 
                                    Multiple Types
                                </p>

                                <p class="mb-0" v-if="item.storable_units.length === 0"> 
                                    Empty
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
                            </div>
                        </span>
                    </div>

                    <div class="location-items"> 
                        <span class="location-type">
                            Ref #: 
                            <span style="color: #4a4a4a;" :class="item.ref_no !== null ? '' : 'red--text lighten-2'">
                                {{ typeof item.ref_no !== 'undefined' && item.ref_no !== null ? item.ref_no : 'Empty' }}
                            </span>
                        </span>
                    </div>
                </div>

                <div class="location-items-wrapper mb-1">
                    <div class="location-items"> 
                        <span>Truck: 
                            <span class="date">
                                {{ typeof item.truck_name !== 'undefined' && item.truck_name !== null ? item.truck_name : '--' }}
                            </span>
                        </span>
                    </div>
                </div>  

                <div class="location-items-wrapper">
                    <div class="location-items"> 
                        <span>Updated at: <span class="date">{{ formatDate(item.updated_at) }}</span></span>
                    </div>
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
                    v-if="!currentLocationsLoading && currentSelectedLocations.length == 0 && search === ''">
                    <div class="no-data-heading">
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
                </div>

                <div class="no-data-wrapper" 
                    v-if="!currentLocationsLoading && currentSelectedLocations.length == 0 && search !== ''">
                    <div class="no-data-heading">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div>                           
                            <h3> No Locations found. </h3>
                            <p> There are no locations listed. Try searching another keyword.</p>
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
            @close="closeLocation" />

        <DeleteDialog 
            :dialogData.sync="dialogLocationDelete"
            :editedItemData.sync="currentLocationToDelete"
            :editedIndexWarehouse.sync="editedLocationIndex"
            :defaultItemWarehouse.sync="defaultLocationItems"
            @delete="deleteLocationConfirm"
            @close="closeLocationDelete"
            :fromComponent="$store.state.locations.locationsTab.typeTab === 0 ? 'location' : 'location name'"
            :loadingDelete="getDeleteLocationLoading"
            componentName="Locations" />

        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import LocationAddDialog from '../../../InventoryComponents/LocationComponents/LocationAddDialog.vue'
import moment from 'moment'
import globalMethods from '../../../../utils/globalMethods'
import DeleteDialog from '../../../Dialog/DeleteDialog.vue'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'InventoryLocationsMobileTable',
    props: ['currentWarehouseSelected', 'isMobile'],
    components: {
        LocationAddDialog,
        DeleteDialog,
        PaginationComponent
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        headersStorage: [
			{ 
				text: 'Row',
				align: 'center',
				sortable: false,
				value: 'row',
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
				width: "15%" 
			},
			{ 
				text: 'Type',
				align: 'start',
				sortable: false,
				value: 'type',
				fixed: true,
				width: ""
			},
            { 
				text: 'Reference',
				align: 'start',
				sortable: false,
				value: 'ref_no',
				fixed: true,
				width: ""
			},
            { 
				text: 'Updated At',
				align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: ""
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "5%"
			},
		],
        headersGate: [
			{ 
				text: 'Gate Name',
				align: 'start',
				sortable: false,
				value: 'gate_name',
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
				text: 'Storable Unit',
				align: 'start',
				sortable: false,
				value: 'storable_unit',
				fixed: true,
				width: "15%" 
			},
			{ 
				text: 'Type',
				align: 'start',
				sortable: false,
				value: 'type',
				fixed: true,
				width: ""
			},
            { 
				text: 'Reference',
				align: 'start',
				sortable: false,
				value: 'ref_no',
				fixed: true,
				width: ""
			},
            // { 
			// 	text: 'Truck',
			// 	align: 'start',
			// 	sortable: false,
			// 	value: 'name',
			// 	fixed: true,
			// 	width: ""
			// },
            { 
				text: 'Updated At',
				align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: ""
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "5%"
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
            gate_name: ''
        },
        defaultLocationItems: {
            type: 'storage',
            shelf: '',
            row: '',
            rack: '',
            position: '',
            gate_name: ''
        },
        tabs: ["All", "Filled", "Empty"],
        typeTabs: ["Storage", 'Gate'],
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
        typingTimeout: 0
    }),
    computed: {
        ...mapGetters({
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
            getSearchedLocationsItems: 'locations/getSearchedLocationsItems'
        }), 
		currentSelectedLocations() {
            let getLocationsData = this.locationsDataChecking()
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
                let getLocationsData = this.locationsDataChecking()
                return Math.ceil(getLocationsData.total / getLocationsData.per_page)
            }
        },
        getCurrentPage: {
            get() {
                let getLocationsData = this.locationsDataChecking()
                return getLocationsData.current_page
            },
            set() {
                return {}
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

            if (this.search == '' && this.currentSelectedLocations.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentSelectedLocations.length === 0) {
                isShow = true
            }

            return isShow
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
        }),
        ...globalMethods,
        async onTypeTabChange(i) {
            this.setCurrentLocationTypeTab(i)
            this.setCurrentLocationTypeSubTab(0)
            this.search = ''
            this.setLocationSearchedVal([])
            this.setEmptyInventoryLocationsData([])

            let dataWithPage = {
                id: this.currentWarehouseSelected.id,
                page: 1
            }

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
            this.$store.state.locations.locationsTab.typeSubTab = i
            this.setCurrentLocationTypeSubTab(i)
            this.search = ''
            this.setLocationSearchedVal([])

            let storePagination = this.$store.state.locations

            let dataWithPage = {
                id: this.currentWarehouseSelected.id,
                page: 1
            }

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
        getTabCount(index) {
            let data = '0'

            let storeLocationsItems = this.$store.state.locations.locationsTab
            let currentTypeTab = storeLocationsItems.typeTab

            if (typeof this.getSearchedLocations !== 'undefined' && this.getSearchedLocations !== null) {
                if (currentTypeTab === 0) {
                    if (this.search !== '' && index === 0 && 
                        this.getSearchedLocations.currentTabName === 'storage-all') {
                        data = this.getSearchedLocations.total
                    } else if (this.search !== '' && index === 1 && 
                        this.getSearchedLocations.currentTabName === 'storage-filled' ) {
                        data = this.getSearchedLocations.total
                    } else if (this.search !== '' && index === 2 && 
                        this.getSearchedLocations.currentTabName === 'storage-empty' ) {
                        data = this.getSearchedLocations.total
                    } else {
                        if (index == 0) {
                            data = this.getStorageAllPagination.total
                        } else if (index == 1) {
                            data = this.getStorageFilledPagination.total
                        } else if (index == 2) {
                            data = this.getStorageEmptyPagination.total
                        }
                    }
                } else {
                    if (this.search !== '' && index === 0 && 
                        this.getSearchedLocations.currentTabName === 'gate-all' ) {
                        data = this.getSearchedLocations.total
                    } else if (this.search !== '' && index === 1 && 
                        this.getSearchedLocations.currentTabName === 'gate-filled' ) {
                        data = this.getSearchedLocations.total
                    } else if (this.search !== '' && index === 2 && 
                        this.getSearchedLocations.currentTabName === 'gate-empty' ) {
                        data = this.getSearchedLocations.total
                    } else {
                        if (index == 0) {
                            data = this.getGateAllPagination.total
                        } else if (index == 1) {
                            data = this.getGateFilledPagination.total
                        } else if (index == 2) {
                            data = this.getGateEmptyPagination.total
                        }
                    }
                }
            } else { // if not, back to normal
                if (currentTypeTab == 0) {
                    if (index == 0) {
                        data = this.getStorageAllPagination.total
                    } else if (index == 1) {
                        data = this.getStorageFilledPagination.total
                    } else if (index == 2) {
                        data = this.getStorageEmptyPagination.total
                    }
                } else {
                    if (index == 0) {
                        data = this.getGateAllPagination.total
                    } else if (index == 1) {
                        data = this.getGateFilledPagination.total
                    } else if (index == 2) {
                        data = this.getGateEmptyPagination.total
                    }
                }
            }

            let finalData = data !== 0 ? data : '0'

            return finalData
        },
        formatDate(date) {
            if (date !== '' && date !== null) {
                return moment(date).format('hh:mmA, MMM DD, YYYY')
            } else {
                return '--'
            }
        },
        addLocation() {
            this.dialogLocation = true
            this.$nextTick(() => {
                this.editedLocationItems = Object.assign({}, this.defaultLocationItems)
                this.editedLocationIndex = -1;
            })
        },
        closeLocation() {
            this.dialogLocation = false
            this.$nextTick(() => {
                this.editedLocationItems = Object.assign({}, this.defaultLocationItems)
                this.editedLocationIndex = -1;
            })
        },
        editLocation(location) {
            this.editedLocationIndex = this.currentSelectedLocations.indexOf(location)
			this.editedLocationItems = Object.assign({}, this.currentSelectedLocations[this.editedLocationIndex])

            this.dialogLocation = true
        },
        deleteLoc(location) {
            this.dialogLocationDelete = true
            let storageName = location.shelf + location.row + location.rack

            this.currentLocationToDelete = {
                id: location.id,
                name: location.type === 'storage' ? `${storageName}` : location.gate_name,
                wid: location.warehouse_id
            }
        },
        async deleteLocationConfirm() {
            if (this.currentLocationToDelete !== null) {
                try {
                    let payload =  {
                        location_id: this.currentLocationToDelete.id,
                        wid: this.currentLocationToDelete.wid
                    }

                    let dataWithPage = {
						wid: this.currentWarehouseSelected.id,
						page: this.getCurrentPage
					}

                    await this.deleteLocation(payload)
                    this.notificationMessage('Location successfully deleted.')
                    this.fetchLocations(dataWithPage)
                    this.closeLocationDelete()
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
        getItemPerPage() {
            if (this.$store.state.locations.locationsTab.typeTab === 0) {
                if (this.$store.state.locations.locationsTab.typeSubTab == 0) {
                    return this.getStorageAllPagination.per_page
                } else if (this.$store.state.locations.locationsTab.typeSubTab == 1) {
                    return this.getStorageFilledPagination.per_page
                } else {
                    return this.getStorageEmptyPagination.per_page
                }
            } else {
                 if (this.$store.state.locations.locationsTab.typeSubTab == 0) {
                    return this.getGateAllPagination.per_page
                } else if (this.$store.state.locations.locationsTab.typeSubTab == 1) {
                    return this.getGateFilledPagination.per_page
                } else {
                    return this.getGateEmptyPagination.per_page
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
                let data = { 
                    search: this.search
                }  

                this.setSearchedLocationsLoading(true)
                this.apiCall(data)
            }, 300)
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
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/all`
                        passedData.tab = 'storage-all'
                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/filled`
                        passedData.tab = 'storage-filled'
                    } else {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/empty`
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
            if (page !== null) {
                let storeLocationsTab = this.$store.state.locations.locationsTab
                let storePagination = this.$store.state.locations

                let dataWithPage = {
                    id: this.currentWarehouseSelected.id,
                    page
                }

                if (this.search == '') {
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
                } else {
                    let data = {
                        search: this.search,
                        page
                    }

                    this.handlePageSearched(data)
                }                          
            }
        },
        // if search is not empty and click pagination
        async handlePageSearched(data) {
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
                            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/all`
                            passedData.tab = 'storage-all'
                        } else if (storePagination.locationsTab.typeSubTab == 1) {
                            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/filled`
                            passedData.tab = 'storage-filled'
                        } else {
                            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/storage-location/empty`
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
        locationsDataChecking() {
            let storeLocationsItems = this.$store.state.locations.locationsTab
            let currentTypeTab = storeLocationsItems.typeTab
            let currentTypeSubTab = storeLocationsItems.typeSubTab

            // if user searched
            if (typeof this.getSearchedLocations !== 'undefined' && this.getSearchedLocations !== null) {
                if (currentTypeTab === 0) {
                    if (this.search !== '' && currentTypeSubTab === 0 && 
                        this.getSearchedLocations.currentTabName === 'storage-all') {
                        return this.getSearchedLocations
                    } else if (this.search !== '' && currentTypeSubTab === 1 && 
                        this.getSearchedLocations.currentTabName === 'storage-filled' ) {
                        return this.getSearchedLocations
                    } else if (this.search !== '' && currentTypeSubTab === 2 && 
                        this.getSearchedLocations.currentTabName === 'storage-empty' ) {
                        return this.getSearchedLocations
                    } else {
                        if (currentTypeSubTab == 0) {
                            return this.getStorageAllPagination
                        } else if (currentTypeSubTab == 1) {
                            return this.getStorageFilledPagination
                        } else if (currentTypeSubTab == 2) {
                            return this.getStorageEmptyPagination
                        }
                    }
                } else {
                    if (this.search !== '' && currentTypeSubTab === 0 && 
                        this.getSearchedLocations.currentTabName === 'gate-all' ) {
                        return this.getSearchedLocations
                    } else if (this.search !== '' && currentTypeSubTab === 1 && 
                        this.getSearchedLocations.currentTabName === 'gate-filled' ) {
                        return this.getSearchedLocations
                    } else if (this.search !== '' && currentTypeSubTab === 2 && 
                        this.getSearchedLocations.currentTabName === 'gate-empty' ) {
                        return this.getSearchedLocations
                    } else {
                        if (currentTypeSubTab == 0) {
                            return this.getGateAllPagination
                        } else if (currentTypeSubTab == 1) {
                            return this.getGateFilledPagination
                        } else if (currentTypeSubTab == 2) {
                            return this.getGateEmptyPagination
                        }
                    }
                }
            } else { // if not, back to normal
                if (currentTypeTab == 0) {
                    if (currentTypeSubTab == 0) {
                        return this.getStorageAllPagination
                    } else if (currentTypeSubTab == 1) {
                        return this.getStorageFilledPagination
                    } else if (currentTypeSubTab == 2) {
                        return this.getStorageEmptyPagination
                    }
                } else {
                    if (currentTypeSubTab == 0) {
                        return this.getGateAllPagination
                    } else if (currentTypeSubTab == 1) {
                        return this.getGateFilledPagination
                    } else if (currentTypeSubTab == 2) {
                        return this.getGateEmptyPagination
                    }
                }
            }
        }
    },
    async mounted() {
        try {
            let dataWithPage = {
                id: this.currentWarehouseSelected.id,
                page: this.getCurrentPage
            }

            if (this.$store.state.locations.locationsTab.typeTab == 0) {
                await this.fetchAllStorageLocations(dataWithPage)
                await this.fetchFilledStorageLocations(dataWithPage)
                await this.fetchEmptyStorageLocations(dataWithPage)
            } else {
                await this.fetchAllGateLocations(dataWithPage)
                await this.fetchFilledGateLocations(dataWithPage)
                await this.fetchEmptyGateLocations(dataWithPage)
            }
        } catch(e) {
            this.notificationError(e)
        }
    },
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/location/locationMobileTable.scss';
</style>