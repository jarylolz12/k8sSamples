<template>
    <div class="product-inventory-desktop-wrapper">
        <div class="overlay search" :class="search !== '' && getProductInventorySearchedLoading ? 'show' : ''">  
            <div class="preloader" v-if="(search !== '' && getProductInventorySearchedLoading)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>

        <v-data-table
            :headers="headers"
            :items="currentWarehouseProducts"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            item-key="sku"
            class="inventory-table elevation-1"
            :class="(currentWarehouseProducts !== null &&
                    currentWarehouseProducts.length > 0) ? '' : 'no-data-table'"
            hide-default-footer
            fixed-header
            show-select>

            <template v-slot:top>
                <div class="inventory-search-wrapper">
                    <!-- UNCOMMENT IN V2 -->
                    <!-- <v-tabs class="inventory-inner-tab" @change="onTabChange" v-model="currentTab">
                        <v-tab v-for="(tab, index) in tabs" 
                            :key="index" 
                            :class="index == 2 ? 'inventory-inner-tab-last' : ''">
                            
                            <span v-if="index == 0">{{ tab }}</span>
                            <span v-if="index == 1">Single Unit</span>
                            <span v-if="index == 2">Bundles</span>
                        </v-tab>
                    </v-tabs> -->

                    <v-spacer></v-spacer>
                    
                    <div class="search-and-filter">
                        <!-- <v-btn dark color="primary" class="btn-white btn-filters">
                            <img src="@/assets/icons/filters.svg" alt=""> Filters
                        </v-btn> -->
                        
                        <!-- <Search 
                            placeholder="Search Products"
                            className="search custom-search"
                            :inputData.sync="search" /> -->

                        <FilterComponent :menu.sync="filterMenu" :isMobile="isMobile" :customClass="'from-product-inventory'">
                            <template v-slot:filter_title>
                                <img src="@/assets/icons/settings-blue.svg" class="filter-img mr-1" width="18px" height="18px">
                                <span class="filter-main-title">Customize Table</span>
                            </template>

                            <template v-slot:filter_body>
                                <div class="select-deselect-all-wrapper">
                                    <button class="btn-white select-all" @click="selectAll">Select All</button>
                                    <button class="btn-white deselect-all" @click="deselectAll">Deselect All</button>
                                </div>

                                <v-divider></v-divider>

                                <div class="filter-component-body">
                                    <div v-for="(header, index) in customizedTablesHeader" :key="index">
                                        <v-checkbox                                            
                                            v-model="header.isChecked"
                                            :label="header.text"
                                            hide-details="auto"
                                            class="mt-0"
                                            v-if="isShowHeaderCustomized(header)"
                                            :disabled="header.disabled">
                                        </v-checkbox>
                                    </div>
                                </div>
                            </template>

                            <template v-slot:filter_actions>
                                <v-btn class="btn-apply btn-blue" @click="applyCustom">
                                    Apply
                                </v-btn>

                                <v-btn class="btn-cancel btn-white" @click="clearCustom">
                                    Cancel
                                </v-btn>
                            </template>
                        </FilterComponent>

                        <div class="search-wrapper">
                            <img src="@/assets/images/search-icon.svg" alt="">

                            <input
                                class="search" 
                                type="text"
                                id="search-input"
                                v-model.trim="search"
                                placeholder="Search Products"
                                @input="handleSearch"
                                autocomplete="off" />
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.product_sku`]="{ item }">
                <div class="inventory-wrapper">
                    <div> {{ item.product_sku }}</div>
                </div>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="inventory-wrapper">
                    <div style="text-transform: capitalize;"> {{ typeof item.type !== 'undefined' && item.type !== null ? item.type : '' }}</div>
                </div>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="inventory-img">
                        <img :src="getImgUrl(item.image)" v-bind:alt="item.image" width="56px" height="56px">
                    </div>

                    <div class="info-wrapper">
                        <p class="inventory-info">{{ item.name }}</p>
                        <p class="inventory-category">{{ getCategory(item.category_id) }}</p>
                        <!-- <p class="inventory-category">{{ getCategoryName(item.category_id) }}</p> -->
                    </div>
                </div>
            </template>

            <template v-slot:[`item.carton_count`]="{ item }">
                <span>{{ item.carton_count !== null ? item.carton_count : 0 }}</span>
            </template>

            <template v-slot:[`item.in_each_carton`]="{ item }">
                <span>{{ item.total_unit !== null ? item.in_each_carton : 0 }}</span>
            </template>

            <template v-slot:[`item.total_unit`]="{ item }">
                <span>{{ item.total_unit !== null ? item.total_unit : 0 }}</span>
            </template>

            <template v-slot:[`item.inbound`]="{ item }">
                <span>{{ typeof item.inbound !== 'undefined' && item.inbound !== null ? item.inbound : 0 }}</span>
            </template>

            <template v-slot:[`item.products_allocated`]="{ item }">
                <span>{{ typeof item.products_allocated !== 'undefined' && item.products_allocated !== null ? item.products_allocated : '--' }}</span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions mr-2">
                    <v-menu bottom left content-class="outbound-lists-menu">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on">
                                <v-icon>mdi-dots-horizontal</v-icon>
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <v-list-item @click="viewProduct(item)">
                                <v-list-item-title>
                                    View
                                </v-list-item-title>
                            </v-list-item>

                            <v-list-item @click="openEditProduct(item)">
                                <v-list-item-title>
                                    Edit Product Info
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="getProductInventoryLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper" v-if="!getProductInventoryLoading && currentWarehouseProducts.length == 0">
                    <div class="no-data-heading">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div v-if="currentTab == 0">
                            <h3> Empty Products </h3>
                            <p v-if="search === ''">
                                This warehouse doesn’t have any product in inventory yet.
                            </p>

                            <p v-if="search !== ''">
                                No Products found. Try searching another keyword.
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </v-data-table>

        <InventoryProductViewDialog
            :editedItemData.sync="viewProductItem"
            :dialogViewData.sync="dialogViewInventory"
            :isMobile="isMobile"
            :categoryLists="categoryLists"
            @deleteItem="deleteItem"
            @editItem="openEditProduct"
            @close="closeView" />            
            <!-- @editItem="openEditProduct" -->

        <ProductAddDialog 
            :dialog.sync="dialogEditProduct"
            :editedIndex.sync="editedIndexProduct"
			:defaultItem.sync="defaultProductItem"
			:editedItem.sync="editedProductItem"
			:categoryLists="categoryLists"
			@close="closeProduct"
			@setToDefault="setToDefault"
			:isMobile="isMobile"
            :isInventoryPage="true" />

        <Pagination 
            v-if="currentWarehouseProducts !== 'undefined' && currentWarehouseProducts.length > 0"
            :pageData.sync="page"
            :lengthData.sync="pageCount"
            :isMobile="isMobile"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import SearchComponent from '../../../SearchComponent/SearchComponent.vue'
import InventoryProductViewDialog from '../../../InventoryComponents/ProductsComponents/InventoryProductViewDialog.vue'
import ProductAddDialog from '../../../ProductComponents/Dialog/ProductAddDialog.vue'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'
import AddInventoryProducts from '../../../InventoryComponents/ProductsComponents/AddInventoryProducts.vue'
import FilterComponent from '../../../FilterComponent/FilterComponent.vue'
import _ from 'lodash'
import globalMethods from '../../../../utils/globalMethods'
import inventoryGlobalMethods from '../../../../utils/inventoryMethods/inventoryGlobalMethods'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'InventoryProductsDesktopTable',
    props: ['currentWarehouseSelected', 'isMobile', 'productsListsDataForAddInventory', 'fetchProductLoading'],
    components: {
        SearchComponent,
        InventoryProductViewDialog,
        ProductAddDialog,
        PaginationComponent,
        AddInventoryProducts,
        FilterComponent
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        // headers: [
		// 	{
		// 		text: 'SKU',
		// 		align: 'start',
		// 		sortable: false,
		// 		value: 'category_sku',
		// 		fixed: true,
		// 		width: "",
        //         isShow: true,
        //         disabled: true,
        //         isChecked: true
		// 	},
		// 	{ 
		// 		text: 'Name & Category',
		// 		align: 'start',
		// 		sortable: false,
		// 		value: 'name',
		// 		fixed: true,
		// 		width: "",
        //         isShow: true,
        //         isChecked: true
		// 	},
        //     { 
		// 		text: 'Unit',
		// 		align: 'end',
		// 		sortable: false,
		// 		value: 'total_unit',
		// 		fixed: true,
		// 		width: "",
        //         isShow: true,
        //         isChecked: true
		// 	},
		// 	{ 
		// 		text: 'Carton',
		// 		align: 'end',
		// 		sortable: false,
		// 		value: 'expected_carton_count',
		// 		fixed: true,
		// 		width: "",
        //         isShow: false,
        //         isChecked: false
		// 	},
		// 	{ 
		// 		text: 'Available',
		// 		align: 'end',
		// 		sortable: false,
		// 		value: 'available',
		// 		fixed: true,
		// 		width: "",
        //         isShow: true,
        //         isChecked: true
		// 	},
        //     { 
		// 		text: 'Allocated',
		// 		align: 'end',
		// 		sortable: false,
		// 		value: 'products_allocated',
		// 		fixed: true,
		// 		width: "",
        //         isShow: true,
        //         isChecked: true
		// 	},
        //     { 
		// 		text: 'Inbound',
		// 		align: 'end',
		// 		sortable: false,
		// 		value: 'inbound',
		// 		fixed: true,
		// 		width: "",
        //         isShow: true,
        //         isChecked: true
		// 	},
        //     { 
		// 		text: 'Preferred',
		// 		align: 'end',
		// 		sortable: false,
		// 		value: 'preferred_unit_quantity',
		// 		fixed: true,
		// 		width: "",
        //         isShow: false,
        //         isChecked: false
		// 	},
        //     { 
		// 		text: 'Delta',
		// 		align: 'end',
		// 		sortable: false,
		// 		value: 'delta',
		// 		fixed: true,
		// 		width: "",
        //         isShow: false,
        //         isChecked: false
		// 	},
        //     { 
		// 		text: 'On Floor',
		// 		align: 'end',
		// 		sortable: false,
		// 		value: 'on_floor_count',
		// 		fixed: true,
		// 		width: "",
        //         isShow: false,
        //         isChecked: false
		// 	},
		// 	{ 
		// 		text: '', 
		// 		align: 'center',
		// 		value: 'actions', 
		// 		sortable: false,
		// 		fixed: true,
		// 		width: "",
        //         isShow: true,
        //         isChecked: true
		// 	},
		// ],
        headers3PLProvider: [
			{
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'category_sku',
				fixed: true,
				width: "",
                isShow: true,
                disabled: true,
                isChecked: true
			},
			{ 
				text: 'Name & Category',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true
			},
            { 
				text: 'Customer',
				align: 'start',
				sortable: false,
				value: 'warehouse_customer',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true
			},
            { 
				text: 'Unit',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true
			},
			{ 
				text: 'Carton',
				align: 'end d-none',
				sortable: false,
				value: 'expected_carton_count',
				fixed: true,
				width: "",
                isShow: false,
                isChecked: false
			},
            { 
				text: 'Allocated',
				align: 'end',
				sortable: false,
				value: 'products_allocated',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true
			},
			{ 
				text: 'Available',
				align: 'end',
				sortable: false,
				value: 'available',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true
			},
            { 
				text: 'Inbound',
				align: 'end',
				sortable: false,
				value: 'inbound',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true
			},
            { 
				text: 'Preferred',
				align: 'end d-none',
				sortable: false,
				value: 'preferred_unit_quantity',
				fixed: true,
				width: "",
                isShow: false,
                isChecked: false
			},
            { 
				text: 'Delta',
				align: 'end d-none',
				sortable: false,
				value: 'delta',
				fixed: true,
				width: "",
                isShow: false,
                isChecked: false
			},
            { 
				text: 'On Floor',
				align: 'end d-none',
				sortable: false,
				value: 'on_floor_count',
				fixed: true,
				width: "",
                isShow: false,
                isChecked: false
			},
			{ 
				text: '', 
				align: 'center',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true
			},
		],
        headersCopy: [],
        headers3PLProviderCopy: [],
        viewProductItem: {
            name: '',
            sku: '',
            type: '',
            category_id: '',
            image: null,
            product_in_each_carton: null,
            carton_dimensions: {
                l: 0,
                w: 0,
                h: 0,
                uom: 'cm'
            },
            in_each_carton: 0,
            products_allocated: 0,
            carton_count: 0,
            total_unit: 0,
            inbound: 0,
            unit_price: 0
        },   
        defaultViewProductItem: {
            name: '',
            sku: '',
            type: '',
            category_id: '',
            image: null,
            product_in_each_carton: null,
            carton_dimensions: {
                l: 0,
                w: 0,
                h: 0,
                uom: 'cm'
            },
            in_each_carton: 0,
            products_allocated: 0,
            carton_count: 0,
            total_unit: 0,
            inbound: 0,
            unit_price: 0
        },
        dialogViewInventory: false,
        // tabs: ["All", "Single", "Bundle"],
        tabs: ["All", "Single"],
        currentTab: 0,
        // edit Product dialog
        dialogEditProduct: false,
        editedIndexProduct: 0,
        editedProductItem: {
			sku: null,
			name: '',
			category_id: null,
			description: '',
			units_per_carton: '',
			image: null,
			classification_code: '',
			additional_classification_code: '',
			duty_rate: '',
			unit_price: '',
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,			
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_weight: {
				value: '',
				unit: 'kg'
			},
			preferred_unit_quantity: '',
			warehouse_customer_id: null
		},
		defaultProductItem: {
			sku: null,
			name: '',
			category_id: null,
			description: '',
			units_per_carton: '',
			image: null,
			classification_code: '',
			additional_classification_code: '',
			duty_rate: '',
			unit_price: '',
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,			
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_weight: {
				value: '',
				unit: 'kg'
			},
			preferred_unit_quantity: '',
			warehouse_customer_id: null
		},
        typingTimeout: 0,
        fetchNextPageProductsLoading: false,
        categoryListData: [],
        lastDataCheck: [],
        current_page_is: 1,
        // 3pl
        dialogAddInventoryProducts: false,
        editedProductIndex: -1,
        editedProductItems: {
            inventory_as_of: '',
            notes: '',
            products: []
        },
        defaultProductItems: {
            inventory_as_of: '',
            notes: '',
            products: []
        },
        // fetching products for adding Inventory
        productInventoryLogs: [],
        lastItemsLogsChecks: [],
        current_page_is_logs: 1,
        // warehouse customers
		current_page_is_whcustomers: 1,
		warehouseCustomerListsData: [],
		lastCheckWHData: [],
        // checkbox
        filterMenu: false
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getCategories: 'category/getCategories',
            getProductInventory: 'productInventories/getProductInventory',
            getProductInventoryLoading: 'productInventories/getProductInventoryLoading',
            poBaseUrlState: 'products/poBaseUrlState',
            getProductInventorySearched: 'productInventories/getProductInventorySearched',
            getProductInventorySearchedLoading: 'productInventories/getProductInventorySearchedLoading',
            getProductInventoryTab: 'productInventories/getProductInventoryTab',
            getCategoriesDropdown: 'category/getCategoriesDropdown',
            getAllCategoryDropdownLists: 'productInventories/getAllCategoryDropdownLists',
            // 3pl
            getProductInventory3pl: 'productInventories/getProductInventory3pl',
            getProductInventory3plLoading: 'productInventories/getProductInventory3plLoading',
            getProductInventoryLogs: 'productInventories/getProductInventoryLogs',
            getIsShowAddInventoryDialog: 'productInventories/getIsShowAddInventoryDialog',
            getAllWarehouseCustomerListsData: 'warehouseCustomers/getAllWarehouseCustomerListsData',
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            getProductInventoryHeader: 'productInventories/getProductInventoryHeader',
            getProductInventoryHeader3PLProvider: 'productInventories/getProductInventoryHeader3PLProvider'
        }),
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
        allProductsDataFromAPI() {
            let productsLists = []

            if (typeof this.getProductInventorySearched !== 'undefined' && this.getProductInventorySearched !== null) {
                if (this.search !== '' &&  this.getProductInventorySearched.tab === 'all') {
                    productsLists = this.getProductInventorySearched
                } else {
                    if (!this.isWarehouse3PL) {
                        productsLists = this.getProductInventory
                    } else {
                        productsLists = this.getProductInventory3pl
                    }                    
                }
            } else {
                if (!this.isWarehouse3PL) {
                    productsLists = this.getProductInventory
                } else {
                    productsLists = this.getProductInventory3pl
                }
            }

            return productsLists
        },
		currentWarehouseProducts() {
            let productInventories = this.allProductsDataFromAPI

            let inventories = []

            if (typeof productInventories !== 'undefined' && productInventories !== null) {
                if (typeof productInventories.items !== 'undefined') {
                    inventories = productInventories.items

                    if (inventories !== 'undefined' && Array.isArray(inventories) && inventories.length !== 0) {
                        inventories = inventories.map(item => {
                            let newItem = {}
                            let { product, ...otherItems } = item
        
                            newItem = {
                                name: product!==null ? product.name : '',
                                product_sku: product!==null ? product.sku : '',
                                type: typeof product.type !=='undefined' && product.type !== null ? product.type : 'single',
                                category_id: product!==null ? product.category_id : '',
                                image: product!==null ? product.image : null,
                                product_in_each_carton: product !== null ? product.units_per_carton : null,
                                carton_dimensions: product !== null ? JSON.parse(product.carton_dimensions) : null,
                                unit_price: product !== null ? product.unit_price : null,
                                carton_upc: product !== null ? product.carton_upc : null,
                                unit_dimensions: product !== null ? JSON.parse(product.unit_dimensions) : null,
                                unit_weight: product !== null ? JSON.parse(product.unit_weight) : null,                               
                                upc_number: product !== null ? product.upc_number : null,
                                description: product !== null ? product.description : null,                             
                                country_from: product !== null ? product.country_from : null,
                                country_to: product !== null ? product.country_to : null,
                                product_classification_description: product !== null ? product.product_classification_description : null,
                                primary_classification_code: product !== null ? product.classification_code : null,
                                additional_classification_code: product !== null ? product.additional_classification_code : null,
                                is_for_classification_code: product !== null ? product.is_for_classification_code : 1,
                                duty_rate_value: product !== null ? product.duty_rate : null,
                                product_id: product !== null ? product.id : null,
                                preferred_unit_quantity: product !== null ? product.preferred_unit_quantity : null,
                                warehouse_customer_id: product !== null ? product.warehouse_customer_id : null,
                                ...otherItems
                            }
        
                            return newItem
                        })
                    }
                }
            }

            return inventories
        },
        getTotalPage: {
            get() {
                let total = 1
                let inventoryProducts = this.allProductsDataFromAPI

                if (typeof inventoryProducts.last_page !== 'undefined' && inventoryProducts.last_page !== null) {
                    total = inventoryProducts.last_page
                }

                return total
            }
        },
        getCurrentPage: {
            get() {
                let current_page = 1
                let inventoryProducts = this.allProductsDataFromAPI

                if (typeof inventoryProducts.current_page !== 'undefined' && inventoryProducts.current_page !== null) {
                    current_page = inventoryProducts.current_page
                }

                return current_page
            },
            set() {
                return {}
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.currentWarehouseProducts.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentWarehouseProducts.length === 0) {
                isShow = true
            }

            return isShow
        },
        getCurrentLoadingToDisplay() {
            if (this.search === '') {
                return this.fetchNextPageProductsLoading               
            } else {
                return this.getProductInventorySearchedLoading
            }
        },
        getLoadingNoDataDisplay() {
            if (!this.isWarehouse3PL) {
                return this.getProductInventoryLoading
            } else {
                return this.getProductInventory3plLoading
            } 
        },
        getSearchedDataClass() {
            if (this.currentWarehouseProducts.length == 0 && this.search !== '') {
                return true
            } else {
                return false
            }
        },
        headersComputed() {
            // let defaultHeaders = this.headers

            // if (this.isWarehouse3PLProvider) {
            //     defaultHeaders = this.headers3PLProvider
            // } else {
            //     if (this.isWarehouse3PL) {
            //         defaultHeaders = defaultHeaders.filter(v => {
            //             return v.text !== 'On Floor' && v.text !== 'Customer' && v.isShow
            //         })
            //     } else {
            //         defaultHeaders = defaultHeaders.filter(v => {
            //             return v.text !== 'Customer' && v.isShow
            //         })
            //     }
            // }

            // return defaultHeaders

            let defaultHeaders = this.headers3PLProviderCopy

            if (!this.isWarehouse3PLProvider) {
                if (this.isWarehouse3PL) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'On Floor' && v.text !== 'Customer' && v.isShow
                    })
                } else {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'Customer' && v.isShow
                    })
                }
            }

            return defaultHeaders
        },
        customizedTablesHeader() {
            // let defaultHeaders = this.headersCopy

            // if (this.isWarehouse3PLProvider) {
            //     defaultHeaders = this.headers3PLProviderCopy
            // } else {
            //     if (this.isWarehouse3PL) {
            //         defaultHeaders = defaultHeaders.filter(v => {
            //             return v.text !== 'On Floor' && v.text !== 'Customer'
            //         })
            //     } else {
            //         defaultHeaders = defaultHeaders.filter(v => {
            //             return v.text !== 'Customer'
            //         })
            //     }
            // }

            // return defaultHeaders

            let defaultHeaders = this.headers3PLProviderCopy

            if (!this.isWarehouse3PLProvider) {
                if (this.isWarehouse3PL) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'On Floor' && v.text !== 'Customer'
                    })
                } else {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'Customer'
                    })
                }
            }

            return defaultHeaders
        }
    },
    methods: {
        ...mapActions({
            fetchSingleProduct: 'products/fetchSingleProduct',
            setEditInventory: 'inventory/setEditInventory',
            deleteInventory: 'inventory/deleteInventory',
            fetchInventories: 'inventory/fetchInventories',
            setIsEditing: 'inventory/setIsEditing',
            fetchProductInventories: 'productInventories/fetchProductInventories',
            setInventoryProductSearchedVal: 'productInventories/setInventoryProductSearchedVal',
            setSearchedInventoryProductsLoading: 'productInventories/setSearchedInventoryProductsLoading',
            fetchProductInventoriesSearched: 'productInventories/fetchProductInventoriesSearched',
            setProductInventoryTab: 'productInventories/setProductInventoryTab',
            fetchCategoriesDropdown: 'category/fetchCategoriesDropdown',
            setAllCategoryDropdownLists: 'productInventories/setAllCategoryDropdownLists',
            // 3pl
            setAllInboundProductsLists: 'inbound/setAllInboundProductsLists',
            setCurrentInboundTab: 'inbound/setCurrentInboundTab',
            fetchProductInventories3pl: 'productInventories/fetchProductInventories3pl',
            fetchProductInventoryLogs: 'productInventories/fetchProductInventoryLogs',
            setLogsData: 'productInventories/setLogsData',
            setIsAddInventoryShow: 'productInventories/setIsAddInventoryShow',
            setIsCreateInboundShow: 'inbound/setIsCreateInboundShow',
            fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
			setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
            setProductInventoryHeader: 'productInventories/setProductInventoryHeader',
            setProductInventoryHeader3PLProvider: 'productInventories/setProductInventoryHeader3PLProvider'
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        itemRowBackground(item) {
            if (item.delta !== null && item.delta < 0) {
                return 'light-red'
            }
        },
        getTotalCount(item) {
            if (typeof item !== 'undefined' && item !== null && item !== 0) {
                return this.addCommaToNum(item)
            } else {
                return 0
            }
        },
        checkDeltaValue(item) {
            if (item !== null) {
                if (item < 0) {
                    return 'light-red'
                } else if (item === 0) {
                    return 'custom-green'
                } else if (item >= 1) {
                    return 'custom-warning'
                }
            }
        },
        onTabChange(i) {
            this.currentTab = i
            this.setProductInventoryTab(i)
        },
        getImgUrl(pic) {
            if (pic !== 'undefined' && pic !== null) {
				return pic
			} else {
				return require('@/assets/icons/default-product-icon.svg')
			}
        },
        getCategory(id) {
            if (typeof this.categoryListData !== 'undefined' && this.categoryListData !== null &&
                this.categoryListData.length > 0) {
                let findSizeValue = _.find(this.categoryListData, (e) => (e.id == id))

                if (typeof findSizeValue !== 'undefined') {
                    if (findSizeValue.name !== 'undefined') {
                        return `<span>${findSizeValue.name}</span>`
                    }
                } else {
                    if (id !== 'undefined' && id !== null) {
                        return `<span style='color: red !important;'>Category Deleted</span>`
                    } else {
                        return `<span>--</span>`
                    }
                }
            }
        },
        async viewProduct(item) {
            this.dialogViewInventory = true
            this.$nextTick(() => {
                this.viewProductItem = Object.assign({}, item)
            });

            let payload = { id: item.warehouse_id, pid: item.id, page: 1 }
            await this.fetchProductInventoryLogs(payload)

            if (typeof this.getProductInventoryLogs !== 'undefined' && this.getProductInventoryLogs !== null) {
                if (typeof this.getProductInventoryLogs.items !== 'undefined' && 
                    this.getProductInventoryLogs.items !== null && 
                    Array.isArray(this.getProductInventoryLogs.items) && this.getProductInventoryLogs.items.length > 0) {

                    this.productInventoryLogs = this.getProductInventoryLogs.items.map(v => {
                        return {
                            created_at: v.created_at,
                            reference: v.reference,
                            carton_count: v.carton_count,
                            total_unit: v.total_unit,
                            balance: v.balance,
                            color: v.color,
                            shipping_unit: v.shipping_unit
                        }
                    })

                    this.lastItemsLogsChecks = this.productInventoryLogs
                }
            }
        },
        async loadMoreLogs() {
            if (this.current_page_is_logs < this.getProductInventoryLogs.last_page) {
                this.current_page_is_logs++

                try {
                    let payload = { 
                        id: this.viewProductItem.warehouse_id, 
                        pid: this.viewProductItem.id, 
                        page: this.current_page_is_logs 
                    
                    }
                    await this.fetchProductInventoryLogs(payload)
                    if (typeof this.getProductInventoryLogs !== 'undefined' && this.getProductInventoryLogs !== null) {
                        if (typeof this.getProductInventoryLogs.items !== 'undefined' && 
                            this.getProductInventoryLogs.items !== null && 
                            Array.isArray(this.getProductInventoryLogs.items) && 
                            this.getProductInventoryLogs.items.length > 0) {    

                            let newloaddata = this.getProductInventoryLogs.items.map(v => {
                                return {
                                    created_at: v.created_at,
                                    reference: v.reference,
                                    carton_count: v.carton_count,
                                    total_unit: v.total_unit,
                                    balance: v.balance,
                                    color: v.color,
                                    shipping_unit: v.shipping_unit
                                }
                            })

                            this.productInventoryLogs = [...this.productInventoryLogs, ...newloaddata]
                        }
                    }
                } catch(e) {
                    console.log(e);
                }
            }
        },
        closeView() {
            this.dialogViewInventory = false
            this.$nextTick(() => {                
                this.viewProductItem = Object.assign({}, this.defaultViewProductItem)
                this.editedInventoryIndex = -1
                this.productInventoryLogs = []
                this.lastItemsLogsChecks = []
                this.current_page_is_logs = 1
                this.setLogsData([])
            })
        },
        deleteItem(item) {
            console.log(item, 'delete');
        },
        async openEditProduct(product) {
            this.editedIndexProduct = this.currentWarehouseProducts.indexOf(product)
			if (this.editedIndexProduct==-1) {
				if (typeof product.id!=='undefined') {
					this.editedIndexProduct = _.findIndex(this.currentWarehouseProducts, e => (e.id == product.id))	
				}
			}

			let tempProduct = {...product}

            tempProduct.sku = tempProduct.product_sku
            tempProduct.units_per_carton = tempProduct.product_in_each_carton
            tempProduct.classification_code = tempProduct.primary_classification_code
            tempProduct.duty_rate = tempProduct.duty_rate_value
            tempProduct.product_inventory_id = tempProduct.id
            tempProduct.id = tempProduct.product_id

			if(!tempProduct.carton_dimensions){
				tempProduct = {...tempProduct, carton_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				}}
			}

			if(!tempProduct.unit_dimensions){
				tempProduct = {...tempProduct, unit_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				}}
			}

			if(!tempProduct.unit_weight){
				tempProduct = {...tempProduct, unit_weight: {
					value: 0,
					unit: 'kg'
				}}
			}

			if(!tempProduct.is_for_classification_code){
				tempProduct = {...tempProduct, is_for_classification_code: 0, userClassification: 1}
			}

			if(!tempProduct.classification_code){
				tempProduct = {...tempProduct, classification_code: ''}
			} 

			if (!tempProduct.additional_classification_code || tempProduct.additional_classification_code === 'null') {
				tempProduct = {...tempProduct, additional_classification_code: ''}
			}
			
			this.editedProductItem = { ...tempProduct }
			this.dialogEditProduct = true

            if (this.isWarehouse3PLProvider) {
                await this.callWarehouseCustomerListsData()
            }
        },
        closeProduct() {
			this.$nextTick(() => {
				this.editedProductItem = Object.assign({}, this.defaultProductItem)
				this.editedIndexProduct = 0				
			})
			this.dialogEditProduct = false
        },
        setToDefault() {
			this.editedProductItem = {
				sku: null,
                name: '',
                category_id: null,
                description: '',
                units_per_carton: '',
                image: null,
                classification_code: '',
                additional_classification_code: '',
                duty_rate: '',
                unit_price: '',
                upc_number: '',
                carton_upc: '',
                is_for_classification_code: 1,			
                userClassification: 0,
                country_from: '',
                country_to: '',
                product_classification_description: '',
                carton_dimensions: {
                    l: '',
                    w: '',
                    h: '',
                    uom: 'cm'
                },
                unit_dimensions: {
                    l: '',
                    w: '',
                    h: '',
                    uom: 'cm'
                },
                unit_weight: {
                    value: '',
                    unit: 'kg'
                },
                preferred_unit_quantity: '',
                warehouse_customer_id: null
			}
			this.editedIndexProduct = 0
			this.dialogEditProduct = true
        },
        clearSearched() {
            this.search = ''
            this.setInventoryProductSearchedVal([])
            // document.getElementById("search-input").focus();
        },
        handleSearch() {
            if (cancel !== undefined) {
                cancel()
            }
            this.setSearchedInventoryProductsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { 
                    search: this.search
                }  

                this.setSearchedInventoryProductsLoading(true)
                this.apiCall(data)
            }, 500)
        },
        apiCall(data) {
            if (data !== null && this.search !== '') {               
                let warehouse_id = this.currentWarehouseSelected.id

                let passedData = {
                    method: "GET",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        search: this.search,
                        page: 1
                    }
                }

                if (!this.isWarehouse3PL) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/products`
                } else {
                    passedData.url = `${this.poBaseUrlState}/warehouse-3pl/${warehouse_id}/products`
                }

                passedData.tab = 'all'

                try {
                    if (passedData.url !== '') {
                        this.fetchProductInventoriesSearched(passedData)
                    }
                } catch(e) {
                    this.setSearchedInventoryProductsLoading(false)
                    console.log(e, 'Search error');
                }
            } else {
                this.setInventoryProductSearchedVal([])
            }
        },
        async handlePageChange(page) {
            if (page !== null) {
                let storeProductsTab = this.$store.state.productInventories

                let dataWithPage = {
                    id: this.currentWarehouseSelected.id,
                    page
                }

                if (this.search == '') {
                    try {
                        if (!this.isWarehouse3PL) {
                            if (storeProductsTab.productInventory.old_page !== page) {
                                this.fetchNextPageProductsLoading = true
                                await this.fetchProductInventories(dataWithPage)
                                storeProductsTab.productInventory.old_page = page
                                this.fetchNextPageProductsLoading = false
                            }
                        } else {
                            if (storeProductsTab.productInventory3pl.old_page !== page) {
                                this.fetchNextPageProductsLoading = true
                                await this.fetchProductInventories3pl(dataWithPage)
                                storeProductsTab.productInventory3pl.old_page = page
                                this.fetchNextPageProductsLoading = false
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
                
                this.handleScrollToTop()
            }
        },
        handlePageSearched(data) {
            let searchedPagination = this.$store.state.productInventories.productInventorySearched

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

                    if (!this.isWarehouse3PL) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/products`
                    } else {
                        passedData.url = `${this.poBaseUrlState}/warehouse-3pl/${warehouse_id}/products`
                    }

                    passedData.tab = 'all'

                    try {
                        if (passedData.url !== '') {
                            this.fetchProductInventoriesSearched(passedData)
                        }
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedInventoryProductsLoading(false)
                        console.log(e, 'Search error')
                    }
                }                
            } else {
                this.setInventoryProductSearchedVal([])
            }

            this.handleScrollToTop()
        },        
        async loadMoreCategories() {
			if (this.current_page_is < this.getCategoriesDropdown.last_page) {
				this.current_page_is++

				try {
					await this.fetchCategoriesDropdown(this.current_page_is)

					if (typeof this.getCategoriesDropdown !== 'undefined' && this.getCategoriesDropdown !== null && 
						typeof this.getCategoriesDropdown.categories !== 'undefined' && 
						Array.isArray(this.getCategoriesDropdown.categories) && 
						this.getCategoriesDropdown.categories.length > 0) {

						let newloaddata = this.getCategoriesDropdown.categories.map((value) => {
							let nameWithId = value.name + ' (' + value.id + ')'

							return {
								id: value.id,
								name: value.name,
								nameWithId
							}
						})

						this.categoryListData = [...this.categoryListData, ...newloaddata]

						if (this.current_page_is < this.getCategoriesDropdown.last_page) {
							this.loadMoreCategories()
						}

                        this.setAllCategoryDropdownLists(this.categoryListData)
					} else {
						this.categoryListData;
					}
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        handleScrollToTop() {
            this.scrollTableToTop()
        },
        // 3pl
        addInventoryProducts() {
            this.setIsAddInventoryShow(true)
            this.dialogAddInventoryProducts = true
        },
        closeAddInventory() {
            this.setIsAddInventoryShow(false)
            this.dialogAddInventoryProducts = false

             this.$nextTick(() => {
				this.editedProductItems = Object.assign({}, this.defaultProductItems)
				this.editedProductIndex = -1
            })
        },
        navigateToInboundTab() {
            if (this.$router.history.current.query.tab !== 'undefined' && 
                this.$router.history.current.query.tab !== 'Inbound') {
                    
                this.$router.push(`?tab=Inbound`)
                this.$router.history.current.query.tab = 'Inbound'
                this.$store.state.page.currentInventoryTab = 3
                this.setCurrentInboundTab(0)
                this.setIsCreateInboundShow(true)
            }
        },
        openAddProductDialog() {
            this.dialogEditProduct = true
            this.editedIndexProduct = -1
        },
        async callInboundProductsFor3PL() {
            // this.setAllInboundProductsLists([])
            this.$emit('callProductsForAddInventory', 'Products-Inventory')
        },
        // call warehouse customers api
        async callWarehouseCustomerListsData() {
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

							data.map((value) => {
								if (value.address === null) {
									value.address = "";
								}

								if (value.phone === null) {
									value.phone = "";
								}

								if (value.emails === null) {
									value.emails = "";
								}
							});

							this.warehouseCustomerListsData = data
							this.lastCheckWHData = data

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}
							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
						}
					} else {
						this.warehouseCustomerListsData = []
						this.lastCheckWHData = []
					}
				}
			} catch(e) {
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
							this.warehouseCustomerListsData = [...this.warehouseCustomerListsData, ...data]

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}
							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
						}
					} else {
						this.warehouseCustomerListsData
					}
				} catch (e) {
					this.notificationError(e)
				}
			}
		},
        async callDefaultHeaderAction() {
            // this.headersCopy = this.headers
            this.headers3PLProviderCopy = this.headers3PLProvider

            // if (this.isWarehouse3PLProvider) {
            //     if (this.getProductInventoryHeader3PLProvider.length === 0) {
            //         await this.setProductInventoryHeader3PLProvider(this.headers3PLProvider)
            //         this.headers3PLProviderCopy = this.headers3PLProvider
            //     } else {
            //         if (this.getProductInventoryHeader3PLProvider !== this.headers3PLProvider) {
            //             this.headers3PLProvider = this.getProductInventoryHeader3PLProvider
            //             this.headers3PLProviderCopy = this.getProductInventoryHeader3PLProvider
            //         }
            //     }
            // } else {
            //     if (this.getProductInventoryHeader.length === 0) {
            //         await this.setProductInventoryHeader(this.headers)
            //         this.headersCopy = this.headers
            //     } else {
            //         if (this.getProductInventoryHeader !== this.headers) {
            //             this.headers = this.getProductInventoryHeader
            //             this.headersCopy = this.getProductInventoryHeader
            //         }
            //     }
            // }

            if (this.getProductInventoryHeader3PLProvider.length === 0) {
                await this.setProductInventoryHeader3PLProvider(this.headers3PLProvider)
                this.headers3PLProviderCopy = this.headers3PLProvider
            } else {
                if (this.getProductInventoryHeader3PLProvider !== this.headers3PLProvider) {
                    this.headers3PLProvider = this.getProductInventoryHeader3PLProvider
                    this.headers3PLProviderCopy = this.getProductInventoryHeader3PLProvider
                }
            }
        },
        isShowHeaderCustomized(header) {
            let show = true

            if (this.isWarehouse3PLProvider) {
                if (header.text === '') {
                    show = false
                }
            } else {
                if (this.isWarehouse3PL) {
                    if (header.text === 'On Floor' || header.text === '' || header.text === 'Customer') {
                        show = false
                    }
                } else {
                    if (header.text === '' || header.text === 'Customer') {
                        show = false
                    }
                }
            }

            return show
        },
        applyCustom() {
            let containsString = 'd-none'

            // if (this.isWarehouse3PLProvider) {
            //     this.headers3PLProvider = []
            //     this.headers3PLProviderCopy.map(v => {
            //         v.width = ''

            //         if (!v.isChecked) {
            //             if (!v.align.includes(containsString)) {
            //                 v.align = `${v.align} d-none`
            //                 v.isShow = false
            //                 v.isChecked = false
            //             } else {
            //                 v.isShow = false
            //                 v.isChecked = false
            //             }
            //         } else {
            //             if (v.align.includes(containsString)) {
            //                 v.align = v.align.replace('d-none', '')
            //                 v.isShow = true
            //                 v.isChecked = true
            //             } else {
            //                 v.isShow = true
            //                 v.isChecked = true
            //             }
            //         }
            //     })

            //     this.headers3PLProvider = this.headers3PLProviderCopy
            //     this.setProductInventoryHeader3PLProvider(this.headers3PLProvider)
            // } else {
            //     this.headers = []
            //     this.headersCopy.map(v => {
            //         v.width = ''

            //         if (!v.isChecked) {
            //             if (!v.align.includes(containsString)) {
            //                 v.align = `${v.align} d-none`
            //                 v.isShow = false
            //                 v.isChecked = false
            //             } else {
            //                 v.isShow = false
            //                 v.isChecked = false
            //             }
            //         } else {
            //             if (v.align.includes(containsString)) {
            //                 v.align = v.align.replace('d-none', '')
            //                 v.isShow = true
            //                 v.isChecked = true
            //             } else {
            //                 v.isShow = true
            //                 v.isChecked = true
            //             }
            //         }
            //     })

            //     this.headers = this.headersCopy
            //     this.setProductInventoryHeader(this.headers)
            // }

            this.headers3PLProvider = []
            this.headers3PLProviderCopy.map(v => {
                v.width = ''

                if (!v.isChecked) {
                    if (!v.align.includes(containsString)) {
                        v.align = `${v.align} d-none`
                        v.isShow = false
                        v.isChecked = false
                    } else {
                        v.isShow = false
                        v.isChecked = false
                    }
                } else {
                    if (v.align.includes(containsString)) {
                        v.align = v.align.replace('d-none', '')
                        v.isShow = true
                        v.isChecked = true
                    } else {
                        v.isShow = true
                        v.isChecked = true
                    }
                }
            })

            this.headers3PLProvider = this.headers3PLProviderCopy
            this.setProductInventoryHeader3PLProvider(this.headers3PLProvider)

            this.filterMenu = false            
        },
        clearCustom() {
            // if (!isRestore) {
            //     if (!v.isChecked) {
            //         if (!v.align.includes(containsString)) {
            //             v.align = `${v.align} d-none`
            //             v.isShow = false
            //             v.isChecked = false
            //         } else {
            //             v.isShow = false
            //             v.isChecked = false
            //         }
            //     } else {
            //         if (v.align.includes(containsString)) {
            //             v.align = v.align.replace('d-none', '')
            //             v.isShow = true
            //             v.isChecked = true
            //         } else {
            //             v.isShow = true
            //             v.isChecked = true
            //         }
            //     }
            // } else {
            //     if (!v.default) {
            //         if (!v.align.includes(containsString)) {
            //             v.align = `${v.align} d-none`
            //             v.isShow = false
            //             v.isChecked = false
            //         } else {
            //             v.isShow = false
            //             v.isChecked = false
            //         }
            //     } else {
            //         if (v.align.includes(containsString)) {
            //             v.align = v.align.replace('d-none', '')
            //             v.isShow = true
            //             v.isChecked = true
            //         } else {
            //             v.isShow = true
            //             v.isChecked = true
            //         }
            //     }
            // }  

            this.filterMenu = false
        },
        selectAll() {
            this.headers3PLProviderCopy.map(v => {
                v.isChecked = true
            })
        },
        deselectAll() {
            this.headers3PLProviderCopy.map(v => {
                if (typeof v.disabled === 'undefined' && v.text !== '') {
                    v.isChecked = false
                }
            })
        }
    },
    async mounted() {
        this.callDefaultHeaderAction()

        // category fetching
        if (this.getAllCategoryDropdownLists.length === 0) {
            await this.fetchCategoriesDropdown(1)
            if (typeof this.getCategoriesDropdown !== 'undefined' && this.getCategoriesDropdown !== null && 
                typeof this.getCategoriesDropdown.categories !== 'undefined' &&
                Array.isArray(this.getCategoriesDropdown.categories) &&
                this.getCategoriesDropdown.categories.length > 0) {

                this.categoryListData = this.getCategoriesDropdown.categories.map((value) => {
                    let nameWithId = value.name + ' (' + value.id + ')'
                    
                    return {
                        id: value.id,
                        name: value.name,
                        nameWithId
                    }
                })

                this.lastDataCheck = this.categoryListData
                                
                if (this.current_page_is < this.getCategoriesDropdown.last_page) {
                    this.loadMoreCategories()
                }

                this.setAllCategoryDropdownLists(this.categoryListData)
            } else {
                this.categoryListData = []
                this.lastDataCheck = []
            }
        } else {
            this.categoryListData = this.getAllCategoryDropdownLists
        }
    },
    updated() {
        this.callDefaultHeaderAction()
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/product/productTable.scss';
</style>