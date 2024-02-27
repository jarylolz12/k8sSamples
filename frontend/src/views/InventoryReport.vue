<template>
    <div class="inventory-report-container-wrapper" v-resize="onResize">
		<div class="inventory-report-content-wrapper">
            <div class="inventory-report-main-header">
                <button icon dark class="btn-back-report" style="color: #0171A1;" @click="clickBack">
                    <v-icon>mdi-chevron-left</v-icon>
                    <span>Go Back</span>
                </button>
                <div class="inventory-report-header">
                    <div class="inventory-report-header-left">
                        <h3 class="inventory-report-title">Inventory Report</h3>
                    </div>

                    <div class="inventory-report-header-right">
                        <SearchComponent
                            placeholder="Search SKU"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />
                        
                        <!-- hide temporarily as it's not connected yet to the API -->
                        <!-- <v-btn dark color="primary" class="btn-white btn-add-warehouse ml-2">
                            <img src="@/assets/icons/download-po-blue.svg" width="14px" height="14px" class="mr-1">
                            Export
                        </v-btn> -->
                    </div>
                </div>
            </div>

			<div class="inventory-report-body-wrapper">
				<div class="inventory-report-load" v-if="allInventoryReportsLoading">
					<div class="inventory-report-loading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate>
						</v-progress-circular>
					</div>
				</div>

				<div class="inventory-report-body" v-if="!allInventoryReportsLoading && allInventoryReportsLoading !== null" style="position: relative;">
                    <div class="report-top-header">
                        <div style="min-width:400px;" v-if="handleFilterComponent == true">
                            <v-select
                                ref="Vueselect"
                                :items="warehousesLists"
                                class="select-product shrink"
                                item-text="name"
                                item-value="id"
                                outlined
                                attach
                                append-icon="mdi-chevron-down"
                                background-color="white"
                                v-model="selectedWharehouse"
                                hide-details="auto"
                                :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true, maxHeight: 500,maxWidth:500}"
                                multiple
                                @change="clearFilterValue"
                                clearable>

                                <template v-slot:prepend-inner>
                                    <div class="d-flex align-enter" style="color: #6D858F;font-size: 14px;font-weight:400;padding-top:1px">
                                        Warehouse
                                    </div>
                                </template>

                                <template v-slot:selection="{ item, index }">
                                    <span style="color: #4A4A4A;font-weight:500" v-if="index == 0 || index == 1"  class="name">
                                        {{ item.name }}<span class="mr-1" v-if="index == 0 && selectedWharehouse.length > 1 ">,</span>
                                    </span>
                                    <span style="color: #4A4A4A;" v-if="index == 2"  class="name ml-2"> +{{ selectedWharehouse.length - 2 }}</span>           
                                </template>

                                <template v-slot:prepend-item>
                                    <v-card elevation="0" class="prepend-btn-inventory-reports-filter-close">
                                        <v-card-title style="font-size:16px;font-weight:600;padding:6px 16px;" class="pr-1 black--text">
                                            Filter by Warehouse
                                            <v-spacer></v-spacer>
                                            <v-btn @click="closeDropdown" icon>
                                                <v-icon color="#0171A1">mdi-close</v-icon>
                                            </v-btn>
                                        </v-card-title>                                        
                                    
                                        <v-divider style="border:1px solid #F5F9FC!important;"></v-divider>
                                        <div class="d-flex align-center px-1">
                                            <v-btn @click="selectAllWharehouse" text style="padding:8px 12px !important;border:none!important;" class="btn-white text-capitalize">Select All</v-btn>
                                            <span style="color: #4a4a4a;">-</span>
                                            <v-btn @click="DeselectAllWharehouse" text style="padding:8px 12px !important;border:none!important;" class="btn-white text-capitalize">Deselect All</v-btn>
                                        </div>
                                        <v-divider style="border:1px solid #F5F9FC!important;"></v-divider>
                                    </v-card>
                                </template>

                                <template v-slot:item="{ item, on, attrs }">
                                    <v-list-item class="d-flex justify-start" style="border:none;min-height:45px;height:45px;" v-on="on" v-bind="attrs" #default="{ active }">
                                        <div><v-checkbox :input-value="active"></v-checkbox></div>
                                        <div>          
                                            <p class="name mb-1 font-regular" style="color: #4A4A4A;">{{ item.name }}</p>           
                                        </div>
                                    </v-list-item>
                                </template>

                                <template v-slot:append-item>
                                    <v-divider></v-divider>
                                    <v-card class="d-flex px-4 py-3 append-btn-inventory-reports-add-clear" flat
                                        style="border-top-right-radius: 0; border-top-left-radius: 0; border-top:2px solid #F5F9FC;">
                                        <v-btn class="btn-blue mr-2" @click="filtermultipleCustomer">
                                            Add ({{selectedWharehouse.length}}) Warehouse
                                        </v-btn>
                                        <v-btn class="btn-white" @click="clearAllFilter">
                                            Clear Filter
                                        </v-btn>
                                    </v-card>                                
                                </template>

                                <template v-slot:no-data>
                                    <div v-if="getWarehouseLoading" class="option-items loading"
                                        style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
                                        <div class="sku-item">
                                            <v-progress-circular
                                                :size="40"
                                                color="#0171a1"
                                                indeterminate>
                                            </v-progress-circular>
                                        </div>
                                    </div>
                                    <div v-if="!getWarehouseLoading" class="option-items no-data"
                                        style="min-height: 40px; padding: 12px; font-size: 14px;">
                                        <div class="sku-item">
                                            No available data
                                        </div>
                                    </div>
                                </template>
                            </v-select>
                        </div>

                        <div class="button-expand-collapse-wrapper">
                            <button class="btn-white mr-2" @click="collapseAll">
                                <v-icon>mdi-chevron-up</v-icon> 
                                <span>Collapse All</span>
                            </button>

                            <button class="btn-white" @click="expandAll">
                                <v-icon>mdi-chevron-down</v-icon> 
                                <span>Expand All</span>
                            </button>
                        </div>
                    </div>

                    <div class="report-table-content">
                        <v-data-table
                            :headers="headersDefault"
                            :items="allInventoryReports"
                            :page.sync="page"
                            :items-per-page="itemsPerPage"
                            @page-count="pageCount = $event"
                            mobile-breakpoint="769"
                            item-key="sku"
                            class="inventory-table storable-table elevation-0"
                            v-bind:class="{
                                'no-data-table' : (typeof allInventoryReports !== 'undefined' && allInventoryReports.length === 0),
                                'no-current-pagination' : (getTotalPage <= 1),
                            }"
                            hide-default-footer
                            fixed-header
                            :expanded.sync="expanded"
                            :single-expand="singleExpand"
                            show-expand
                            :item-class="itemRowBackground"
                            ref="my-table">

                            <template v-slot:expanded-item="{ headers, item }">
                                <td :colspan="headers.length">
                                    <div class="expanded-item-info">
                                        <div class="expanded-header-wrapper">
                                            <div class="expanded-header-content">
                                                <div class="header-title w-40">Warehouse</div>
                                                <div class="header-title w-10">Unit</div>
                                                <div class="header-title w-10">Allocated</div>
                                                <div class="header-title w-10">Available</div>
                                                <div class="header-title w-10">Inbound</div>
                                                <div class="header-title w-10">Preferred</div>
                                                <div class="header-title w-10">Delta</div>
                                            </div>
                                        </div>

                                        <div class="expanded-body-wrapper" v-if="item.warehouse_breakdown.length > 0">
                                            <div class="expanded-body-content" 
                                                v-for="(v, index) in item.warehouse_breakdown" :key="index">

                                                <div class="header-data w-40"> {{ v.warehouse_name }} </div>
                                                <div class="header-data w-10"> {{ v.total_unit }} </div>
                                                <div class="header-data w-10"> {{ v.products_allocated }} </div>
                                                <div class="header-data w-10"> {{ v.available }} </div>
                                                <div class="header-data w-10"> {{ v.inbound }} </div>
                                                <div class="header-data w-10"> {{ v.preferred }} </div>
                                                <div class="header-data w-10"> {{ v.delta }} </div>
                                            </div>
                                        </div>

                                        <div class="expanded-body-wrapper" v-else>
                                            <div class="expanded-body-content">
                                                <div class="header-data w-100 text-center"> No data found </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </template>

                            <template v-slot:[`item.name`]="{ item }">
                                <div class="report-name-wrapper">
                                    <img :src="getImgUrl(item.image)" v-bind:alt="item.image" width="40px" height="40px">
                                    
                                    <div class="name-and-category">
                                        <p class="mb-0 name">
                                            {{ 
                                                typeof item.name !== 'undefined' && item.name !== null && 
                                                item.name !== 'null' ? item.name : '--' 
                                            }}
                                        </p>
                                        <p class="mb-0 category">
                                            {{ 
                                                typeof item.category !== 'undefined' && item.category !== null && 
                                                item.category !== 'null' ? item.category : '--' 
                                            }}
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
				</div>
			</div>
		</div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import globalMethods from '../utils/globalMethods'
import SearchComponent from '../components/SearchComponent/SearchComponent.vue'
import PaginationComponent from '../components/PaginationComponent/PaginationComponent.vue'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: "InventoryReport",
	components: { 
        SearchComponent,
        PaginationComponent
    },
	data: () => ({
		isMobile: false,
        loading: false,
        search: '',
        page: 1,
        itemsPerPage: 35,
        pageCount: 0,
        expanded: [],
        singleExpand: false,
        headersDefault: [
            {
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'sku',
				fixed: true,
				width: "12%"
			},
            { text: '', value: 'data-table-expand', width: ""},
            { 
                text: 'Name & Category',
                align: 'start',
                sortable: false,
                value: 'name',
                fixed: true,
                width: "25%"
            },
            { 
                text: 'Unit',
                align: 'end',
                sortable: false,
                value: 'units',
                fixed: true,
                width: "10%"
            },
            { 
				text: 'Allocated',
				align: 'end',
				sortable: false,
				value: 'allocated',
				fixed: true,
				width: "10%"
			},
            { 
                text: 'Available',
                align: 'end',
                sortable: false,
                value: 'available',
                fixed: true,
                width: "10%"
            },
            { 
				text: 'Inbound',
				align: 'end',
				sortable: false,
				value: 'inbound',
				fixed: true,
				width: "10%"
			},
            { 
                text: 'Preferred',
                align: 'end',
                sortable: false,
                value: 'preferred',
                fixed: true,
                width: "10%"
            },
            { 
				text: 'Delta',
				align: 'end',
				sortable: false,
				value: 'delta',
				fixed: true,
				width: "10%"
			},
        ],
        selectedWharehouse: [],
        inventoryReportLoadingPage: false,
        isFetchingInventoryReport: false
        // search Report
	}),
	computed: {
		...mapGetters({ 
            getInventoryReport: 'productInventories/getInventoryReport',
            getInventoryReportLoading: 'productInventories/getInventoryReportLoading',
            getWarehouse: 'warehouse/getWarehouse',
            getWarehouseLoading:'warehouse/getWarehouseLoading',
            poBaseUrlState: 'products/poBaseUrlState',
            getSearchedReports:'productInventories/getSearchedReports',
            getFilteredReports:'productInventories/getFilteredReports',
            getSearchedReportsLoading:'productInventories/getSearchedReportsLoading',
            getFilteredReportsLoading:'productInventories/getFilteredReportsLoading'
        }),
        allInventoryReportsLoading() {
            if (this.search == '' && this.selectedWharehouse.length === 0) {
                // return this.getInventoryReportLoading
                return this.isFetchingInventoryReport
            } else if (this.search !== '' && this.selectedWharehouse.length === 0) {
                return this.getSearchedReportsLoading
            } else {
                return this.getFilteredReportsLoading
            }
        },
        allInventoryReports() { 
            let items = []
            if (typeof this.getSearchedReports !== 'undefined' && this.getSearchedReports !== null && this.getFilteredReports !== 'undefined' && this.getFilteredReports !== null) {
                if (this.search !== '' && this.selectedWharehouse.length === 0 && this.getSearchedReports.tab == 'searching') {
                    items = this.inventoryReportsSearched
                } else if (this.selectedWharehouse.length > 0 && this.getFilteredReports.tab == 'filtering') {
                    items = this.inventoryReportsFiltered
                } else {
                    items = this.inventoryReports
                }
            }
            
            return items
        },
        inventoryReports() {
            let items = []

            if (this.getInventoryReport !== 'undefined' && this.getInventoryReport !== null &&
                typeof this.getInventoryReport.items !== 'undefined' && this.getInventoryReport.items !== null) {

                items = this.getInventoryReport.items.map(v => {
                    return {
                        sku: v.category_sku,
                        name: v.name,
                        category: v.category_name,
                        units: typeof v.unit !== 'undefined' ? v.unit : 0,
                        available: typeof v.available !== 'undefined' ? v.available : 0,
                        allocated: typeof v.products_allocated !== 'undefined' ? v.products_allocated : 0,
                        inbound: typeof v.inbound !== 'undefined' ? v.inbound : 0,
                        preferred: typeof v.preferred_unit_quantity !== 'undefined' ? v.preferred_unit_quantity : 0,
                        delta: typeof v.delta !== 'undefined' ? v.delta : 0,
                        image: v.image,
                        warehouse_breakdown: v.warehouse_breakdown
                    }
                })
            }

            return items
        },
        inventoryReportsSearched() {
            let items = []

            if (this.getSearchedReports !== 'undefined' && this.getSearchedReports !== null &&
                typeof this.getSearchedReports.items !== 'undefined' && this.getSearchedReports.items !== null) {

                items = this.getSearchedReports.items.map(v => {
                    return {
                        sku: v.category_sku,
                        name: v.name,
                        category: v.category_name,
                        units: typeof v.unit !== 'undefined' ? v.unit : 0,
                        available: typeof v.available !== 'undefined' ? v.available : 0,
                        allocated: typeof v.products_allocated !== 'undefined' ? v.products_allocated : 0,
                        inbound: typeof v.inbound !== 'undefined' ? v.inbound : 0,
                        preferred: typeof v.preferred_unit_quantity !== 'undefined' ? v.preferred_unit_quantity : 0,
                        delta: typeof v.delta !== 'undefined' ? v.delta : 0,
                        image: v.image,
                        warehouse_breakdown: v.warehouse_breakdown
                    }
                })
            }

            return items
        },
        inventoryReportsFiltered() {
            let items = []

            if (this.getFilteredReports !== 'undefined' && this.getFilteredReports !== null &&
                typeof this.getFilteredReports.items !== 'undefined' && this.getFilteredReports.items !== null) {

                items = this.getFilteredReports.items.map(v => {
                    return {
                        sku: v.category_sku,
                        name: v.name,
                        category: v.category_name,
                        units: typeof v.unit !== 'undefined' ? v.unit : 0,
                        available: typeof v.available !== 'undefined' ? v.available : 0,
                        allocated: typeof v.products_allocated !== 'undefined' ? v.products_allocated : 0,
                        inbound: typeof v.inbound !== 'undefined' ? v.inbound : 0,
                        preferred: typeof v.preferred_unit_quantity !== 'undefined' ? v.preferred_unit_quantity : 0,
                        delta: typeof v.delta !== 'undefined' ? v.delta : 0,
                        image: v.image,
                        warehouse_breakdown: v.warehouse_breakdown
                    }
                })
            }

            return items
        },
        warehousesLists() {
            return (typeof this.getWarehouse !== 'undefined' && this.getWarehouse !== null && this.getWarehouse.results !== 'undefined' && this.getWarehouse.results !== null && this.getWarehouse.results !== 'undefined'  && this.getWarehouse.results.length !== 'undefined' && this.getWarehouse.results.length !== 0) ? this.getWarehouse.results : null
        },
        getTotalPage: {
            get() {
                let total = 1
                let inventoryReport = this.getInventoryReport
                if(this.search === '' && this.selectedWharehouse.length === 0){
                    if (inventoryReport !== null && typeof inventoryReport.last_page !== 'undefined' && inventoryReport.last_page !== null && inventoryReport.last_page !== undefined) {
                        total = inventoryReport.last_page
                    }
                }else if(this.search !== '' && this.selectedWharehouse.length === 0){
                    inventoryReport = this.getSearchedReports
                    if (typeof inventoryReport.last_page !== 'undefined' && inventoryReport.last_page !== null && inventoryReport.last_page !== undefined) {
                        total = inventoryReport.last_page
                    }
                }
                else{
                    inventoryReport = this.getFilteredReports
                    if (typeof inventoryReport.last_page !== 'undefined' && inventoryReport.last_page !== null && inventoryReport.last_page !== undefined) {
                        total = inventoryReport.last_page
                    }
                }
                

                return total
            }
        },
        getCurrentPage: {
            get() {
                let current_page = 1
                let inventoryReport = this.getInventoryReport
                if(this.search === '' && this.selectedWharehouse.length === 0){
                    if (inventoryReport !== null && typeof inventoryReport.current_page !== 'undefined' && inventoryReport.current_page !== null && inventoryReport.last_page !== undefined) {
                        current_page = inventoryReport.current_page
                    }
                }else if(this.search !== '' && this.selectedWharehouse.length === 0){
                    inventoryReport = this.getSearchedReports
                    if (typeof inventoryReport.current_page !== 'undefined' && inventoryReport.current_page !== null && inventoryReport.last_page !== undefined) {
                        current_page = inventoryReport.current_page
                    }
                }
                else{
                    inventoryReport = this.getFilteredReports
                    if (typeof inventoryReport.current_page !== 'undefined' && inventoryReport.current_page !== null && inventoryReport.last_page !== undefined) {
                        current_page = inventoryReport.current_page
                    }
                }

                return current_page
            },
            set() {
                return {}
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.allInventoryReports.length === 0 && this.selectedWharehouse.length == 0) {
                isShow = false
            } else if (this.search !== '' && this.allInventoryReports.length === 0) {
                isShow = true
            } else if (this.selectedWharehouse.length > 0 && this.allInventoryReports.length === 0) {
                isShow = false
            }
            return isShow
        },
        handleFilterComponent() {
            let isShow = true

            if (this.search == '' && this.selectedWharehouse.length ===0 &&  this.allInventoryReports.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.allInventoryReports.length === 0) {
                isShow = true
            }
			else if(this.search !== '' || this.allInventoryReports.length > 0){
				isShow = true
			}
            return isShow
        },
        getSearchedDataClass() {
            if (this.allInventoryReports.length == 0 && this.search !== '') {
                return true
            } else {
                return false
            }
        },
	},
	methods: {
		...mapActions({
            fetchInventoryReport: 'productInventories/fetchInventoryReport',
            // search and filter
            setSearchedReportLoading:'productInventories/setSearchedReportLoading',
            fetchSearchedReports:'productInventories/fetchSearchedReports',
            setFilteredReportsLoading:'productInventories/setFilteredReportsLoading',
            fetchFilterReports:'productInventories/fetchFilterReports',
            setReportFilteredVal:'productInventories/setReportFilteredVal',
            setReportSearchedVal:'productInventories/setReportSearchedVal',
            fetchWarehouse:'warehouse/fetchWarehouse'

        }),
        itemRowBackground(item) {
            if (item.delta !== null && item.delta < 0) {
                return 'light-red'
            }
        },
		onResize() {
            if (window.innerWidth < 769) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },
		...globalMethods,
        getImgUrl(pic) {
            if (pic !== 'undefined' && pic !== null) {
				return pic
			} else {
				return require('@/assets/icons/default-product-icon.svg')
			}
        },
        clickBack() {
            this.setReportFilteredVal([])
            this.setReportSearchedVal([])
            this.$router.push(`/inventory`)
        },
        handleSearch() {
            if (cancel !== undefined) {
				cancel()
			}
			this.setSearchedReportLoading(false)
			clearTimeout(this.typingTimeout)
			this.typingTimeout = setTimeout(() => {
				let data = { search: this.search }  

				if (this.selectedWharehouse.length > 0) {
					this.filtermultipleCustomer()
				} else {
					this.apiCall(data)
				}                
			}, 500)
        },
        async apiCall(data) {
            if (data !== null && this.search !== '' && this.selectedWharehouse.length === 0) {
				this.setSearchedReportLoading(true)
                let passedData = {
                    method: "get",
                    tab:'searching',
                    url: `${this.poBaseUrlState}/get-inventory-report`,
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: { search: this.search, page: 1 }
                }
                if (passedData.url !== '') {
                    try {
                       await this.fetchSearchedReports(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedReportLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.setReportSearchedVal([])
            }
        },
        async filtermultipleCustomer() {
            if (this.selectedWharehouse.length !== 0) {
				this.setFilteredReportsLoading(true)
				var searchParams = new URLSearchParams();
				for (var ser = 0; ser < this.selectedWharehouse.length; ser++) {
                    searchParams.append(`ids[]`, this.selectedWharehouse[ser])
                }
                searchParams.append('page', 1)
                let passedData = {}
                if (this.selectedWharehouse.length !== 0 && this.search !== '') {
                    passedData = {
                        method: "get",
                        tab:'filtering',
                        url: `${this.poBaseUrlState}/get-inventory-report?search=${this.search}&filter=true`,
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: searchParams
                    }
                } else {
                    passedData = {
                        method: "get",
                        tab:'filtering',
                        url: `${this.poBaseUrlState}/get-inventory-report?filter=true`,
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: searchParams
                    }
                }

                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterReports(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredReportsLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.setReportFilteredVal([])
            }
        },
        clearSearched() {
            this.search = ''
            this.setReportSearchedVal([])
            if (this.selectedWharehouse.length > 0) {
                this.setReportFilteredVal([])
                this.filtermultipleCustomer()
            }
        },
        async handlePageChange(page) {
            if (page !== null) {
                let storeInventoryproducts = this.$store.state.productInventories
                let dataWithPage = { page }

                if (this.search == '' && this.selectedWharehouse.length === 0) {
                    try {
                        if (storeInventoryproducts.inventoryReport.old_page !== page) {
                            // this.inventoryReportLoadingPage = true
                            this.isFetchingInventoryReport = true
                            await this.fetchInventoryReport(dataWithPage.page)
                            storeInventoryproducts.inventoryReport.old_page = page
                            // this.inventoryReportLoadingPage = false
                            this.isFetchingInventoryReport = false
                        }
                    } catch (e) {
                        this.isFetchingInventoryReport = false
                        this.notificationError(e)
                    }
				} else if (this.search !=='' && this.selectedWharehouse.length === 0) {
                    let data = { search: this.search, page }
                    this.handlePageSearched(data)
                } else {
					if (this.search !=='' && this.selectedWharehouse.length > 0) {						
						let data = {
							filter_data: this.selectedWharehouse.map(val => val.id),
							search_data: this.search,
							page
						}
						
						this.handleFilterWithSearchFunction(data)
					} else {
						if (this.search === '' && this.selectedWharehouse.length > 0) {
                            let data = {
                                filter_data: this.selectedWharehouse.map(val => val.id),
                                page
                            }
                            this.handleFilterWithSearchFunction(data)
						}
					}					
				}
            }
        },
        async handlePageSearched(data) {
            let storeInventoryproducts = this.$store.state.productInventories

            if (data !== null && this.search !== '') {
                if (storeInventoryproducts.searchedReports.old_page !== data.page) {
                    let passedData = {
						method: "get",
                        tab:'searching',
                        url: `${this.poBaseUrlState}/get-inventory-report`,
						cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: this.search,
                            page: data.page
                        }
                    }

                    if (passedData.url !== '') {
                        try {
                            await this.fetchSearchedReports(passedData)
							storeInventoryproducts.searchedReports.old_page = data.page
                        } catch(e) {
                            this.notificationError(e)
                            this.setSearchedReportLoading(false)
                            console.log(e, 'handle page search error')
                        }
                    }
                }                
            } else {
                this.setReportSearchedVal([])
            }
        },
        async handleFilterWithSearchFunction(data) {
            let storeInventoryproducts = this.$store.state.productInventories

			if (data !== null && data.filter_data !== null && this.selectedWharehouse.length > 0) {
                if (storeInventoryproducts.filteredReports.old_page !== data.page) {
                    this.setFilteredReportsLoading(true)
					var searchParams = new URLSearchParams();
					for (var ser = 0; ser < data.filter_data.length; ser++) {
					    searchParams.append(`ids[]`, data.filter_data[ser])
					}
					searchParams.append('page',data.page)

                    let passedData = {}
                    if(this.selectedWharehouse.length !== 0 && this.search !== ''){
                        passedData = {
                            method: "get",
                            tab:'filtering',
                            url: `${this.poBaseUrlState}/get-inventory-report?search=${this.search}&filter=true`,
                            cancelToken: new CancelToken(function executor(c) {
                                cancel = c
                            }),
                            params: searchParams
                        }
                    } else {
                        passedData = {
                            method: "get",
                            tab:'filtering',
                            url: `${this.poBaseUrlState}/get-inventory-report?filter=true`,
                            cancelToken: new CancelToken(function executor(c) {
                                cancel = c
                            }),
                            params: searchParams
                        }
                    }

                    if (passedData.url !== '') {
                        try {
                            await this.fetchFilterReports(passedData)
                            storeInventoryproducts.filteredReports.old_page = data.page
                        } catch(e) {
                            this.notificationError(e)
                            this.setFilteredReportsLoading(false)
                            console.log(e, 'filter with serch error')
                        }
                    }
                } else {
                    this.setReportFilteredVal([])
				}               
            }
        },
        clearAllFilter() {
            if (this.selectedWharehouse.length > 0) {
                this.selectedWharehouse = []
                this.setReportFilteredVal([])
            }
        },
        closeDropdown() {
           this.$refs.Vueselect.blur();
        //    this.selectedWharehouse = []
        },
        clearFilterValue(){
            if(this.selectedWharehouse.length == 0) {
                this.setReportFilteredVal([])
            }
        },
        expandAll() {
            this.expanded = this.inventoryReports;
        },
        collapseAll() {
            this.expanded = [];
        },
        selectAllWharehouse() {
            this.selectedWharehouse = this.warehousesLists
            this.selectedWharehouse = this.selectedWharehouse.map(val => val.id)
        },
        DeselectAllWharehouse() {
            this.selectedWharehouse = []
        },
	},
	async mounted() {
		//set current page
        this.isFetchingInventoryReport = true

        this.$store.dispatch("page/setPage", "inventory")        
        await this.fetchWarehouse()
        await this.fetchInventoryReport(1)
        this.isFetchingInventoryReport = false
	},
	updated() {}
};
</script>

<style lang="scss">
@import "../assets/scss/pages_scss/inventory/inventoryReport.scss";
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../assets/scss/buttons.scss';
@import "../assets/scss/tables.scss";

.append-btn-inventory-reports-add-clear {
    position: sticky;
    bottom: 1px;
    width: 100%;
    background: white;
}
.prepend-btn-inventory-reports-filter-close {
    position: sticky;
    top: 0px;
    width: 100%;
    z-index: 1;
}
</style>
