<template>
    <div class="product-inventory-mobile-wrapper">
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
            :headers="headers"
            :items="currentWarehouseProducts"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            item-key="sku"
            class="inventory-table mobile-table elevation-1"
            v-bind:class="{
                'no-data-table': (typeof currentWarehouseProducts !== 'undefined' && currentWarehouseProducts.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
                'no-searched-data': (getSearchedDataClass)
            }"
            hide-default-footer
            fixed-header
            show-select
            @click:row="viewProduct">

            <template v-slot:top>
                <div class="inventory-search-wrapper" v-if="handleSearchComponent">
                    <v-spacer></v-spacer>
                    
                    <div class="search-and-filter">
                        <SearchComponent
                            placeholder="Search Products"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />
                    </div>

                    <button class="btn-white ml-2 mr-0" @click.stop="addInventoryProducts" v-if="isWarehouse3PL">
                        Add Inventory
                    </button>
                </div>
            </template>

            <template v-slot:[`item.category_sku`]="{ item }">
                <div class="inventory-wrapper mt-1">
                    <div> <span>SKU# {{ item.category_sku }}</span> </div>

                    <div class="actions">
                        <v-menu bottom left offset-y content-class="outbound-lists-menu">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn class="btn-edit" icon v-bind="attrs" v-on="on">
                                    <v-icon>mdi-dots-horizontal</v-icon>
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists">
                                <v-list-item @click.stop="viewProduct(item)">
                                    <v-list-item-title>
                                        View
                                    </v-list-item-title>
                                </v-list-item>  

                                <v-list-item @click.stop="openEditProduct(item)">
                                    <v-list-item-title>
                                        Edit Product Info
                                    </v-list-item-title>
                                </v-list-item>              
                            </v-list>
                        </v-menu>
                    </div>                    
                </div>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div class="inventory-product">
                    <div class="info-wrapper">
                        <p class="inventory-info">{{ item.name }}</p>
                        <p class="inventory-category" v-html="getCategory(item.category_id)"></p>                    
                        
                        <div class="cartons-separator">
                            <p>{{ item.expected_carton_count !== null ? getTotalCount(item.expected_carton_count) : 0 }} Cartons</p>
                            <span class="separator"></span>
                            <p> {{ item.total_unit !== null ? getTotalCount(item.total_unit) : 0 }} Available </p>
                        </div>

                        <div class="cartons-separator">
                            <p>{{ typeof item.inbound !== 'undefined' && item.inbound !== null ? getTotalCount(item.inbound) : 0 }} Inbound</p>
                            <span class="separator"></span>
                            <p>
                                {{ typeof item.products_allocated !== 'undefined' && item.products_allocated !== null ? getTotalCount(item.products_allocated) : '--' }}
                                Allocated
                            </p>
                        </div>

                        <div class="cartons-separator">
                            <p>{{ item.preferred_unit_quantity !== null ? getTotalCount(item.preferred_unit_quantity) : 0 }} Preferred</p>
                            <span class="separator"></span>
                            <p>
                                <span :class="checkDeltaValue(item.delta)">
                                    {{ item.delta >= 1 ? ('+' + getTotalCount(item.delta)) : getTotalCount(item.delta) }}
                                    Delta
                                </span> 
                            </p>
                        </div>
                    </div>

                    <div class="inventory-img">
                        <img :src="getImgUrl(item.image)" v-bind:alt="item.image" width="68px" height="68px">
                    </div>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="getLoadingNoDataDisplay">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper" v-if="!getLoadingNoDataDisplay && currentWarehouseProducts.length == 0">
                    <div class="no-data-heading" v-if="!isWarehouse3PL">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div v-if="currentTab == 0 && search === ''">
                            <h3> Empty Products </h3>
                            <p>
                                This warehouse doesn’t have any product in inventory yet.
                            </p>
                        </div>

                        <div v-if="currentTab == 0 && search !== ''">
                            <h3> No Products found. </h3>
                            <p>
                                No Products found. <br/> Try searching another keyword.
                            </p>
                        </div>
                    </div>

                    <div class="no-data-heading" v-if="isWarehouse3PL">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div v-if="currentTab == 0 && search === ''">
                            <h3> No Inventory </h3>
                            <p>
                                There is no inventory in this warehouse. Inventory level of products
                                will be shown here once inbound orders are added.
                            </p>

                            <div class="button-wrapper-3pl-products">
                                <button class="btn-blue mr-2" @click.stop="addInventoryProducts">
                                    Add Current Inventory
                                </button>

                                <button class="btn-white" @click.stop="navigateToInboundTab(3)">
                                    Create Inbound
                                </button>
                            </div>
                        </div>

                        <div v-if="currentTab == 0 && search !== ''">
                            <h3> No Products found. </h3>
                            <p>
                                No Products found. <br/> Try searching another keyword.
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </v-data-table>

        <AddInventoryProducts 
            :editedProductIndex="editedProductIndex"
            :editedProductItems.sync="editedProductItems"
            :dialogAdd.sync="getIsShowAddInventoryDialog"
            :isMobile="isMobile"
            :productListsDropdownData="productsListsDataForAddInventory"
            @close="closeAddInventory"
            :currentWarehouseSelected="currentWarehouseSelected"
            @openAddProductDialog="openAddProductDialog"
            :fetchProductLoading="fetchProductLoading" />

        <InventoryProductViewDialog
            :editedItemData.sync="viewProductItem"
            :dialogViewData.sync="dialogViewInventory"
            :isMobile="isMobile"
            :categoryLists="categoryListData"
            @deleteItem="deleteItem"
            @editItem="openEditProduct"
            @close="closeView"
            :isWarehouse3PL="isWarehouse3PL"
            :productInventoryLogs="productInventoryLogs"
            @loadMoreLogs="loadMoreLogs"
            :productInventoryBreakdown="productInventoryBreakdown"
            @loadMoreInventoryBreakDowns="loadMoreInventoryBreakDowns" />

        <ProductAddDialog 
            :dialog.sync="dialogEditProduct"
            :editedIndex.sync="editedIndexProduct"
			:defaultItem.sync="defaultProductItem"
			:editedItem.sync="editedProductItem"
			:categoryLists="categoryListData"
			@close="closeProduct"
			@setToDefault="setToDefault"
			:isMobile="isMobile"
            :isInventoryPage="true"
            :isWarehouse3PL="isWarehouse3PL"
            :isWarehouse3PLProvider="false"
            @callInboundProductsFor3PL="callInboundProductsFor3PL" />

        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />

        <!-- <Pagination 
            v-if="currentWarehouseProducts !== 'undefined' && currentWarehouseProducts.length > 0"
            :pageData.sync="page"
            :lengthData.sync="pageCount"
            :isMobile="isMobile"
        /> -->
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import SearchComponent from '../../../SearchComponent/SearchComponent.vue'
import InventoryProductViewDialog from '../../../InventoryComponents/ProductsComponents/InventoryProductViewDialog.vue'
import ProductAddDialog from '../../../ProductComponents/Dialog/ProductAddDialog.vue'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'
import AddInventoryProducts from '../../../InventoryComponents/ProductsComponents/AddInventoryProducts.vue'
import _ from 'lodash'
import globalMethods from '../../../../utils/globalMethods'

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
        AddInventoryProducts
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        headers: [
			{
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'category_sku',
				fixed: true,
				width: "10%"
			},
            // {
			// 	text: 'Type',
			// 	align: 'start',
			// 	sortable: false,
			// 	value: 'type',
			// 	fixed: true,
			// 	width: "8%"
			// },
			{ 
				text: 'Name & Category',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "18%" 
			},
			{ 
				text: 'Carton',
				align: 'end',
				sortable: false,
				value: 'expected_carton_count',
				fixed: true,
				width: "10%" 
			},
			// { 
			// 	text: 'In Each',
			// 	align: 'end',
			// 	sortable: false,
			// 	value: 'product_in_each_carton',
			// 	fixed: true,
			// 	width: "10%" 
			// },
			{ 
				text: 'Available',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "12%" 
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
				text: 'Allocated',
				align: 'end',
				sortable: false,
				value: 'products_allocated',
				fixed: true,
				width: "10%" 
			},
            { 
				text: 'Preferred',
				align: 'end',
				sortable: false,
				value: 'preferred_unit_quantity',
				fixed: true,
				width: "12%" 
			},
            { 
				text: 'Delta',
				align: 'end',
				sortable: false,
				value: 'delta',
				fixed: true,
				width: "12%" 
			},
			{ 
				text: '', 
				align: 'center',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "10%" 
			},
		],
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
			unit_price: 0,
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,			
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: 0,
				w: 0,
				h: 0,
				uom: 'cm'
			},
			unit_dimensions: {
				l: 0,
				w: 0,
				h: 0,
				uom: 'cm'
			},
			unit_weight: {
				value: 0,
				unit: 'kg'
			},
            preferred_unit_quantity: ''
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
			duty_rate: 0,
			unit_price: 0,
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: 0,
				w: 0,
				h: 0,
				uom: 'cm'
			},
			unit_dimensions: {
				l: 0,
				w: 0,
				h: 0,
				uom: 'cm'
			},
			unit_weight: {
				value: 0,
				unit: 'kg'
			},
            preferred_unit_quantity: ''
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
        productInventoryBreakdown:[],
        lastItemsInventroyBreakdownChecks:[],
        current_page_is_inventory_breakdown:1
    }),
    computed: {
        ...mapGetters({
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
            // Inventory Brekdown
            getProductInventoryBreakdown:'productInventories/getProductInventoryBreakdown'
        }),
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
                                carton_dimensions: product !== null && product.carton_dimensions !== '' ? JSON.parse(product.carton_dimensions) : null,
                                unit_price: product !== null ? product.unit_price : null,
                                carton_upc: product !== null ? product.carton_upc : null,
                                unit_dimensions: product !== null && product.unit_dimensions !== '' ? JSON.parse(product.unit_dimensions) : null,
                                unit_weight: product !== null && product.unit_weight !== '' ? JSON.parse(product.unit_weight) : null,                               
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
            // inentory breakdown
            fetchInventoryBreakdown:'productInventories/fetchInventoryBreakdown'
        }),
        ...globalMethods,
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
        async loadInventoryBreakdown(item){
            if(item == undefined || item == 'undefined') return
            let payload = { id: item.warehouse_id, pid: item.id, page: 1 }
            await this.fetchInventoryBreakdown(payload)

            if (typeof this.getProductInventoryBreakdown !== 'undefined' && this.getProductInventoryBreakdown !== null) {
                if (typeof this.getProductInventoryBreakdown.items !== 'undefined' && 
                    this.getProductInventoryBreakdown.items !== null && 
                    Array.isArray(this.getProductInventoryBreakdown.items) && this.getProductInventoryBreakdown.items.length > 0) {

                    this.productInventoryBreakdown = this.getProductInventoryBreakdown.items.map(v => {
                        return {
                            source: v.source,
                            reference: v.reference,
                            carton_count: v.carton_count,
                            total_unit: v.total_unit,
                        }
                    })

                    this.lastItemsInventroyBreakdownChecks = this.productInventoryBreakdown
                }
            }
        },
        async loadMoreInventoryBreakDowns () {
            if (this.current_page_is_inventory_breakdown < this.getProductInventoryBreakdown.last_page) {
                this.current_page_is_inventory_breakdown++

                try {
                    let payload = { 
                        id: this.viewProductItem.warehouse_id, 
                        pid: this.viewProductItem.id, 
                        page: this.current_page_is_inventory_breakdown                    
                    }

                    await this.fetchInventoryBreakdown(payload)
                    if (typeof this.getProductInventoryBreakdown !== 'undefined' && this.getProductInventoryBreakdown !== null) {
                        if (typeof this.getProductInventoryBreakdown.items !== 'undefined' && 
                            this.getProductInventoryBreakdown.items !== null && 
                            Array.isArray(this.getProductInventoryBreakdown.items) && 
                            this.getProductInventoryBreakdown.items.length > 0) {    

                            let newloaddata = this.getProductInventoryBreakdown.items.map(v => {
                                return {
                                    source: v.source,
                                    reference: v.reference,
                                    carton_count: v.carton_count,
                                    total_unit: v.total_unit,
                                }
                            })

                            this.productInventoryBreakdown = [...this.productInventoryBreakdown, ...newloaddata]
                        }
                    }
                } catch(e) {
                    console.log(e)
                }
            }
        },
        async viewProduct(item) {
            this.dialogViewInventory = true
            this.$nextTick(() => {
                this.viewProductItem = Object.assign({}, item)
            });
            if(!this.isWarehouse3PL){
                this.loadInventoryBreakdown(item)
            }
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
                // inventory breakdown
                this.productInventoryBreakdown = []
                this.lastItemsInventroyBreakdownChecks = []
                this.current_page_is_inventory_breakdown =1
            })
        },
        deleteItem(item) {
            console.log(item, 'delete');
        },
        openEditProduct(product) {
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
				unit_price: 0,
				upc_number: '',
				carton_upc: '',
				is_for_classification_code: 1,			
				userClassification: 0,
				country_from: '',
				country_to: '',
				product_classification_description: '',
				carton_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				},
				unit_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				},
				unit_weight: {
					value: 0,
					unit: 'kg'
				},
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
        async loadMoreCategories(){
			if (this.current_page_is < this.getCategoriesDropdown.last_page ){
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
            var table = this.$refs['my-table'];
            var wrapper = table.$el.querySelector('div.v-data-table__wrapper');
            
            this.$vuetify.goTo(table); // to table
            this.$vuetify.goTo(table, {container: wrapper}); // to header
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
        }
    },
    async mounted() {
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
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/product/productTableMobile.scss';
</style>