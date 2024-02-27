<template>
    <div class="storable-unit-wrapper-mobile">
        <div class="overlay search" :class="search !== '' && getSearchedStorableUnitLoading ? 'show' : ''">  
            <div class="preloader" v-if="(search !== '' && getSearchedStorableUnitLoading)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>

        <div class="overlay" :class="search == '' && getHandleLoading ? 'show' : ''">  
            <div class="preloader" v-if="(search == '' && getHandleLoading)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>

        <v-data-table
            :headers="headers"
            :items="storableUnitItems"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            item-key="id"
            class="inventory-table storable-mobile-table elevation-1"
            v-bind:class="{
                'no-data-table': (typeof storableUnitItems !== 'undefined' && storableUnitItems.length === 0),
                'no-current-pagination' : (getTotalPage <= 1)
            }"
            hide-default-footer
            fixed-header
            :expanded.sync="expanded"
            single-expand
            show-expand
            show-select
            :item-class="itemRowBackground"
            @click:row="clickRow">

            <template v-slot:top>
                <div class="inventory-search-wrapper">
                    <v-tabs class="inventory-inner-tab" @change="onTabChange" v-model="currentTab">
                        <v-tab v-for="(tab, index) in tabs" 
                            :key="index" 
                            :class="index == 2 ? 'inventory-inner-tab-last' : ''">
                            
                            <span>{{ tab }}</span>
                        </v-tab>
                    </v-tabs>

                    <v-spacer></v-spacer>

                    <!-- <v-btn dark color="primary" class="btn-white btn-filters">
                        <img src="@/assets/icons/filters.svg" alt=""> Filters
                    </v-btn> -->

                    <div class="search-and-filter">
                        <div class="search-wrapper" v-if="handleSearchComponent">
                            <img src="@/assets/images/search-icon.svg" alt="">

                            <input
                                class="search" 
                                type="text"
                                id="search-input"
                                v-model.trim="search"
                                placeholder="Search Storable Unit"
                                @input="handleSearch"
                                autocomplete="off" />
                        </div>
                    </div>
                </div>                
            </template>

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
                                    #{{ (v.product !== null && v.product.sku !== null) ? v.product.sku : '' }} 
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

            <template v-slot:[`item.label`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="storable-label">
                        <div class="inventory-blue-text" :class="item.type !== null ? 'mr-1' : ''"> {{ item.label }}</div>
                        <div class="storable-type" v-if="item.type !== null"> ({{ item.type }})</div>

                        <v-icon color="#0171A1" class="ml-1" style="padding-top: 2px;">
                            {{ expanded.length > 0 && (expanded[0].label === item.label) ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
                        </v-icon>
                    </div>

                    <div class="storable-location"> 
                        {{ getLocationDetails(item.location) }}
                    </div>
                </div>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="inventory-wrapper specs">
                    <div class="mr-1"> 
                        {{ item.parse_dimensions.l }}x
                        {{ item.parse_dimensions.w }}x
                        {{ item.parse_dimensions.h }} 
                        {{ item.parse_dimensions.uom }}
                    </div>

                    <div class="weight">
                        <div style="color: #6D858F !important;"> 
                            {{ item.parse_weight.value }} {{ item.parse_weight.unit }} 
                        </div>
                    </div>
                </div>
            </template>
            
            <template v-slot:[`item.spec`]="{ item }">                
                <div class="items-info">
                    <p>
                        {{ item.no_of_sku !== null ? item.no_of_sku : 0 }}
                        <span class="name">SKU</span>
                    </p>                    
                    <span class="separator"></span>

                    <p>
                        {{ item.total_carton_count !== null ? item.total_carton_count : 0 }}
                        <span class="name">Cartons</span>
                    </p>                    
                    <span class="separator"></span>

                    <p>
                        {{ item.total_units !== null ? item.total_units : 0 }}
                        <span class="name">Units</span>
                    </p> 
                </div>
            </template>

            <template v-slot:[`item.location`]="{ item }">
                <div class="items-info">
                    <p>
                        <span class="name">UPDATED AT: </span>
                        {{ formatDate(item.updated_at) }}
                    </p>
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
                
                <div class="no-data-wrapper" v-if="!getStorableUnitsActiveLoading && storableUnitItems.length == 0">
                    <div class="no-data-heading">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <h3> No Storable Units </h3>
                        <p v-if="search == ''">
                            <span v-if="currentTab === 0">You don't have any active storable units yet.</span>
                            <span v-if="currentTab === 1">No Storable Units in History yet.</span>
                        </p>

                         <p v-if="search !== ''">
                            No Storable Units found. Try searching another keyword.
                        </p>
                    </div>
                </div>
            </template>
        </v-data-table>

        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import globalMethods from '../../../../utils/globalMethods'
import moment from 'moment'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'InventoryStorableDesktopTable',
    props: ['currentWarehouseSelected', 'isMobile'],
    components: {
        PaginationComponent
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        tabs: ['Active', 'History'],
        currentTab: 0,
        expanded: [],
        headers: [
            { text: '', value: 'data-table-expand' },
            {
				text: 'Label',
				align: 'start',
				sortable: false,
				value: 'label',
				fixed: true,
				width: "7%"
			},
			{ 
				text: 'Type',
				align: 'start',
				sortable: false,
				value: 'type',
				fixed: true,
				width: "9%"
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
				align: 'start',
				sortable: false,
				value: 'location',
				fixed: true,
				width: "20%"
			},
			{ 
				text: 'Updated At',
				align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: "18%"
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
				width: "8%"
			},
            { 
				text: 'Unit',
				align: 'end',
				sortable: false,
				value: 'total_units',
				fixed: true,
                width: "8%"
			},
            { 
                // to be removed if in v2 and uncomment actions below in v2
				text: '',
				align: 'end',
				sortable: false,
				value: '',
				fixed: true,
                width: "3%"
			},
			// { 
			// 	text: '', 
			// 	align: 'end',
			// 	value: 'actions', 
			// 	sortable: false,
			// 	fixed: true,
			// 	width: "5%"
			// },
		],
        typingTimeout: 0,
        fetchActiveNextPageStorableLoading: false,
        fetchHistoryNextPageStorableLoading: false
    }),
    computed: {
        ...mapGetters({
            getCurrentWarehouse: 'warehouse/getCurrentWarehouse',
            getStorableUnitsActive: 'storableUnit/getStorableUnitsActive',
            getStorableUnitsActiveLoading: 'storableUnit/getStorableUnitsActiveLoading',
            getStorableUnitsHistory: 'storableUnit/getStorableUnitsHistory',
            getStorableUnitsHistoryLoading: 'storableUnit/getStorableUnitsHistoryLoading',
            poBaseUrlState: 'products/poBaseUrlState',
            getCurrentStorableUnitTab: 'storableUnit/getCurrentStorableUnitTab',
            getSearchedStorableUnit: 'storableUnit/getSearchedStorableUnit',
            getSearchedStorableUnitLoading: 'storableUnit/getSearchedStorableUnitLoading'
        }),
        storableUnitItems() {
            let storableUnits = this.loadStorableUnitData()
            let units = []

            if (typeof storableUnits !== 'undefined' && storableUnits !== null && 
                storableUnits.items !== 'undefined' && Array.isArray(storableUnits.items)) {

                let items = storableUnits.items

                items.map(v => {
                    let  { dimensions, weight, storable_unit_products, ...otherItems } = v
                    let parse_dimensions = dimensions !== '' && dimensions !== null ? JSON.parse(dimensions) : null
                    let parse_weight = weight !== '' && weight !== null ? JSON.parse(weight) : null
                    let no_of_sku = storable_unit_products.length !== 'undefined' ? storable_unit_products.length : 0

                    units.push({
                        parse_dimensions,
                        parse_weight,
                        no_of_sku,
                        storable_unit_products,
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
                let storableUnitsData = this.loadStorableUnitData()

                if (typeof storableUnitsData.last_page !== 'undefined' && storableUnitsData.last_page !== null) {
                    total = storableUnitsData.last_page
                }

                return total
            }
        },
        getCurrentPage: {
            get() {
                let current_page = 1
                let storableUnitsData = this.loadStorableUnitData()

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
            }

            return isShow
        },
        getHandleLoading() {
            let storePaginationCurrentTab = this.$store.state.storableUnit.currentStorableUnitTab

            if (storePaginationCurrentTab === 0) {
                return this.fetchActiveNextPageStorableLoading
            } else {
                return this.fetchHistoryNextPageStorableLoading
            }
        }
    },
    methods: {
        ...mapActions({
            fetchStorableUnitsActive: 'storableUnit/fetchStorableUnitsActive',
            fetchStorableUnitsHistory: 'storableUnit/fetchStorableUnitsHistory',
            setSearchedStorableUnitsLoading: 'storableUnit/setSearchedStorableUnitsLoading',
            setStorableUnitSearchedVal: 'storableUnit/setStorableUnitSearchedVal',
            fetchSearchedStorableUnits: 'storableUnit/fetchSearchedStorableUnits',
            setCurrentStorableUnitTab: 'storableUnit/setCurrentStorableUnitTab'
        }),
        ...globalMethods,
        clickRow(item, event) {
            if(event.isExpanded) {
                const index = this.expanded.findIndex(i => i === item);
                this.expanded.splice(index, 1)
            } else {
                this.expanded.splice(item, 1)
                this.expanded.push(item);
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

            locationName = location.type === 'storage' ? shelf + row + rack : locationName
            locationName = locationName !== '' ? locationName : '--'

            return locationName
        },
        onTabChange(i) {
            this.currentTab = i
            this.setCurrentStorableUnitTab(i)
        },
        clicked(value) {
            // code for opening multiple
            const index = this.expanded.indexOf(value)
            value.isExpanded = false

            if (index === -1) {
                this.expanded.push(value)
            } else {
                this.expanded.splice(index, 1)
            }
        },
        itemRowBackground(item) {
            return item.isExpanded ? 'background-light-blue' : ''
        },
        formatDate(date) {
            if (date !== '' && date !== null) {
                return moment(date).format('MMM DD, YYYY hh:mmA') 
            } else {
                return '--'
            }
        },
        vieStorableUnit(item) {
            this.$router.push(`inventory/storable-unit-view?storableid=${item.id}&warehouseid=${item.warehouse_id}`)
        },
        handleSearch() {
            if (cancel !== undefined) {
                cancel()
            }
            this.setSearchedStorableUnitsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { 
                    search: this.search
                }  

                this.setSearchedStorableUnitsLoading(true)
                this.apiCall(data)
            }, 300)
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
        loadStorableUnitData() {
            if (typeof this.getSearchedStorableUnit !== 'undefined' && this.getSearchedStorableUnit !== null) {
                if (this.search !== '' && this.currentTab === 0 && this.getSearchedStorableUnit.tab === 'storable-active') {
                    return this.getSearchedStorableUnit
                } else if (this.search !== '' && this.currentTab === 1 && this.getSearchedStorableUnit.tab === 'storable-history') {
                    return this.getSearchedStorableUnit
                } else {
                    if (this.currentTab === 0) {
                        if (typeof this.getStorableUnitsActive !== 'undefined' && this.getStorableUnitsActive !== null) {
                            return this.getStorableUnitsActive
                        }
                    } else {
                        if (typeof this.getStorableUnitsHistory !== 'undefined' && this.getStorableUnitsHistory !== null) {
                            return this.getStorableUnitsHistory
                        }
                    }
                }
            }

            if (this.currentTab === 0) {
                if (typeof this.getStorableUnitsActive !== 'undefined' && this.getStorableUnitsActive !== null) {
                    return this.getStorableUnitsActive
                }
            } else {
                if (typeof this.getStorableUnitsHistory !== 'undefined' && this.getStorableUnitsHistory !== null) {
                    return this.getStorableUnitsHistory
                }
            }
        },
        async handlePageChange(page) {
            if (page !== null) {
                let storePaginationCurrentTab = this.$store.state.storableUnit.currentStorableUnitTab
                let storePagination = this.$store.state.storableUnit


                let dataWithPage = {
                    id: this.currentWarehouseSelected.id,
                    page
                }

                if (this.search == '') {
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
                } else {
                    let data = {
                        search: this.search,
                        page
                    }

                    this.handlePageSearched(data)
                }                
            }
        },
        handlePageSearched(data) {
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
        }
    },
    async mounted() {
        //set tab
        if (this.getCurrentStorableUnitTab !== 'undefined') {
            if (this.currentTab !== this.getCurrentStorableUnitTab) {
                this.currentTab = this.getCurrentStorableUnitTab
            }
        }

        try {
            let dataWithPage = {
                id: this.currentWarehouseSelected.id,
                page: 1
            }

            await this.fetchStorableUnitsActive(dataWithPage)
            await this.fetchStorableUnitsHistory(dataWithPage)
        } catch(e) {
            this.notificationError(e)
        }
    },
    updated() {}
}
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/inventory/storableUnit/storableMobile.scss";
</style>