<template>
    <div class="product-mobile-table-wrapper">
        <div class="overlay" :class="handleLoadingShow ? 'show' : ''">  
            <div class="preloader" v-if="(handleLoadingShow)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>

        <v-data-table
            :headers="headers"
            :items="getProductItems"
            :page.sync="getCurrentPage"
            :items-per-page="getItemsPerPage"
            hide-default-footer
            mobile-breakpoint="1023"
            @page-count="pageCount = $event"
            class="product-table-mobile elevation-1"
            v-bind:class="{
                'no-data-table' : (typeof getProductItems !== 'undefined' && getProductItems.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
                'no-searched-data' : getSearchedDataClass,
            }"
            @click:row="viewProductItem"
            ref="my-table">

            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Products</v-toolbar-title>
                    
                    <v-spacer></v-spacer>
                </v-toolbar>

                <div class="products-button-wrapper">
                    <v-btn color="primary" class="btn-white" @click="$emit('import')">
                        Import
                    </v-btn>

                    <v-btn color="primary" class="btn-white manage-category-button" @click="handleManageCategory">
                        <span v-if="$vuetify.breakpoint.mdAndUp">Manage Categories</span>
                        <span v-else>Categories</span>
                    </v-btn>

                    <v-btn color="primary" class="btn-blue add-product-button" @click.stop="addProduct">
                        Add Product
                    </v-btn>
                </div>

                <div class="search-filter-component-wrapper" :class="getProductsLoading ? 'border-none' : ''">
                    <!-- <div class="filter-component">
                        <v-btn class="btn-white filters" @click="openFilters">
                            <img src="@/assets/images/filters.png" alt="" class="filter-img mr-1" width="15px" height="15px">
                            Filters
                        </v-btn>
                        
                        <FilterComponent :menu.sync="filterMenu" :isMobile="isMobile">
                            <template v-slot:filter_title>
                                <span class="headline">Product Filters</span>

                                <button icon dark class="btn-close" @click="closeFilters">
                                    <v-icon>mdi-close</v-icon>
                                </button>
                            </template>

                            <template v-slot:filter_body>
                                <form>
                                    <div class="filter-component-body">
                                        <p class="filter-menu-title">Sort by</p>
                                        <v-radio-group v-model="filtersValue.selectedSort" hide-details="auto" class="pt-0 mt-0 mb-4">
                                            <div v-for="(n, index) in radioSortByOptions" :key="index" class="filter-radio-buttons">
                                                <v-radio
                                                    style="margin: unset;"
                                                    :key="index"
                                                    :label="`${n.name}`"
                                                    :value="n.value"
                                                    @change="changeValueFilter(index)">
                                                </v-radio>

                                                <div class="filter-sorting-icons">                                               
                                                    <v-btn icon 
                                                        :ripple="false"
                                                        @click="toggleAscDesc(true, n, index)" 
                                                        :class="(filtersValue.isAsc && filtersValue.index === index) ? 'active' : ''">
                                                        <v-icon aria-hidden="false">
                                                            mdi-sort-alphabetical-ascending
                                                        </v-icon>
                                                    </v-btn>

                                                    <v-btn icon 
                                                        :ripple="false"
                                                        @click="toggleAscDesc(false, n, index)"
                                                        :class="(!filtersValue.isAsc && filtersValue.index === index) ? 'active' : ''">
                                                        <v-icon aria-hidden="false">
                                                            mdi-sort-alphabetical-descending
                                                        </v-icon>
                                                    </v-btn>
                                                </div>
                                            </div>
                                        </v-radio-group>
                                    </div>

                                    <div class="filter-component-sub-body">
                                        <v-row>
                                            <v-col cols="12" sm="12" class="pb-1">
                                                <p class="filter-menu-title">Category</p>

                                                <v-autocomplete
                                                    class="text-fields select-items"
                                                    :items="categoryLists"
                                                    item-text="name"
                                                    item-value="id"
                                                    outlined
                                                    hide-details="auto"
                                                    placeholder="Select Category"
                                                    v-model="filtersValue.category"
                                                    clearable>
                                                </v-autocomplete>
                                            </v-col>

                                            <v-col cols="12" sm="12" class="pb-1">
                                                <p class="filter-menu-title">Warehouse Customer</p>

                                                <v-autocomplete
                                                    class="text-fields select-items"
                                                    :items="warehouseCustomerLists"
                                                    item-text="name"
                                                    item-value="id"
                                                    outlined
                                                    hide-details="auto"
                                                    placeholder="Select Warehouse Customer"
                                                    v-model="filtersValue.customers"
                                                    clearable>
                                                </v-autocomplete>
                                            </v-col>

                                            <v-col cols="12" sm="12" class="pb-1">
                                                <p class="filter-menu-title">Classification Code</p>

                                                <v-text-field
                                                    height="40px"
                                                    color="#002F44"
                                                    width="100%"
                                                    type="text"
                                                    dense
                                                    class="text-fields custom"
                                                    placeholder="Select Classification"
                                                    outlined
                                                    hide-details="auto"
                                                    v-model="filtersValue.code"
                                                    @wheel="$event.target.blur()">
                                                </v-text-field>
                                            </v-col>

                                            <v-col cols="12" sm="12" md="12" class="pb-1">
                                                <p class="filter-menu-title">Units In Each Carton</p>
                                                
                                                <v-row>
                                                    <v-col cols="6" sm="6" md="6" class="pr-1">
                                                        <v-text-field
                                                            height="40px"
                                                            color="#002F44"
                                                            width="100%"
                                                            type="number"
                                                            dense
                                                            class="text-fields custom"
                                                            placeholder="Enter Units"
                                                            outlined
                                                            hide-details="auto"
                                                            min="0"
                                                            v-model="filtersValue.in_each_carton_min"
                                                            @wheel="$event.target.blur()">
                                                        </v-text-field>
                                                        <span class="menu-subtitle">Minimum</span>
                                                    </v-col>

                                                    <v-col cols="6" sm="6" md="6" class="pl-1">
                                                        <v-text-field
                                                            height="40px"
                                                            color="#002F44"
                                                            width="100%"
                                                            type="number"
                                                            dense
                                                            class="text-fields custom"
                                                            placeholder="Enter Units"
                                                            outlined
                                                            hide-details="auto"
                                                            min="0"
                                                            v-model="filtersValue.in_each_carton_max"
                                                            @wheel="$event.target.blur()">
                                                        </v-text-field>
                                                        <span class="menu-subtitle">Maximum</span>
                                                    </v-col>
                                                </v-row>
                                            </v-col>

                                            <v-col cols="12" sm="12" md="12">
                                                <p class="filter-menu-title">Unit Price</p>
                                                
                                                <v-row>
                                                    <v-col cols="6" sm="6" md="6" class="pr-1">
                                                        <v-text-field
                                                            height="40px"
                                                            color="#002F44"
                                                            width="100%"
                                                            type="number"
                                                            dense
                                                            class="text-fields custom"
                                                            placeholder="Enter Price"
                                                            outlined
                                                            hide-details="auto"
                                                            min="0"
                                                            v-model="filtersValue.unit_price_min"
                                                            @wheel="$event.target.blur()">
                                                        </v-text-field>
                                                        <span class="menu-subtitle">Minimum</span>
                                                    </v-col>

                                                    <v-col cols="6" sm="6" md="6" class="pl-1">
                                                        <v-text-field
                                                            height="40px"
                                                            color="#002F44"
                                                            width="100%"
                                                            type="number"
                                                            dense
                                                            class="text-fields custom"
                                                            placeholder="Enter Price"
                                                            outlined
                                                            hide-details="auto"
                                                            min="0"
                                                            v-model="filtersValue.unit_price_max"
                                                            @wheel="$event.target.blur()">
                                                        </v-text-field>
                                                        <span class="menu-subtitle">Maximum</span>
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                        </v-row>
                                    </div>
                                </form>
                            </template>

                            <template v-slot:filter_actions>
                                <v-btn class="btn-apply btn-blue" @click="applyFilters">
                                    Apply
                                </v-btn>

                                <v-btn class="btn-cancel btn-white" @click="clearFilter">
                                    Clear
                                </v-btn>
                            </template>
                        </FilterComponent>
                    </div> -->

                    <div class="search-component" v-if="handleSearchComponent">
                        <SearchComponent
                            placeholder="Search Products"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />
                    </div>
                </div>
            </template>

            <template v-slot:[`item.sku`]="{ item }">
                <div class="products-row">
                    <p class="p-gray mb-0" style="color: #4a4a4a; font-family: 'Inter-SemiBold', sans-serif;">
                        SKU# <span v-if="item.category_id !== null">{{ item.category_id }}-</span>
                        <span>{{ (item.sku !== null && item.sku !== '' ? item.sku : '--') }}</span>
                    </p>

                    <div class="actions">
                        <v-menu bottom left offset-y content-class="outbound-lists-menu">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn class="btn-edit" icon v-bind="attrs" v-on="on">
                                    <v-icon>mdi-dots-horizontal</v-icon>
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists">
                                <v-list-item @click.stop="editProduct(item)">
                                    <v-list-item-title>
                                        Edit
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click.stop="deleteProductItem(item)">
                                    <v-list-item-title class="cancel">
                                        Delete
                                    </v-list-item-title>
                                </v-list-item>                
                            </v-list>
                        </v-menu>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div class="description-wrapper my-2">
                    <div class="info-wrapper">
                        <p class="desc-info mb-1" style="color: #4a4a4a;">
                            {{ item.name }}
                        </p>

                        <p class="light-gray category mb-1">{{ getCategoryName(item.category_id) }}
                            <span v-if="item.category_id !== null">
                                ({{ item.category_id }})
                            </span>
                        </p>

                        <div class="cat-units-wrapper mb-2">
                            <p class="light-gray mb-0">
                                <span>
                                    {{  item.carton_dimensions.w !== undefined &&
                                        item.carton_dimensions.w !== '' ?  
                                        item.carton_dimensions.w : 0 
                                    }}
                                </span> x
                                <span>
                                    {{  item.carton_dimensions.h !== undefined &&
                                        item.carton_dimensions.h !== '' ?  
                                        item.carton_dimensions.h : 0 
                                    }}
                                </span> x
                                <span>
                                    {{  item.carton_dimensions.l !== undefined &&
                                        item.carton_dimensions.l !== '' ?  
                                        item.carton_dimensions.l : 0 
                                    }}
                                </span>
                                <span>{{ item.carton_dimensions.uom }}</span> 
                            </p>
                       
                            <span class="round-divider"></span>
                            <p class="light-gray units mb-0">{{ item.units_per_carton }} Units/Carton</p>
                        </div>

                        <div class="cat-units-wrapper">
                            <p class="dark-grey mb-0">${{ item.unit_price !== null && item.unit_price !== '' ? parseFloat(item.unit_price).toFixed(2) : 0 }}</p>
                        
                            <span class="round-divider"></span>
                            <span class="dark-grey units mb-0">{{ getParsedAmount(item.duty_rate) }} Custom Duty</span>
                        </div>
                    </div>
                    
                    <div class="description-img">
                        <img :src="getImgUrl(item.image)" class="product-img" v-bind:alt="item.image" width="56px" height="56px">
                    </div>
                </div>
            </template>

            <template v-slot:[`item.description`]="{ item }">
                <div class="description-wrapper mb-3">
                    <div class="description-img mr-2">
                        <img src="@/assets/icons/info.svg" width="16px" height="16px">
                    </div>

                    <div class="info-wrapper">
                        <p class="light-gray desc mb-1" style="font-size: 12px;">{{ (item.description !== null && item.description !== '' ? item.description : '--') }}</p>
                    </div>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="no-data-preloader mt-4" v-if="getProductsLoading">
                        <v-progress-circular
                            :size="40"
                            color="#0171a1"
                            indeterminate
                            v-if="getProductsLoading">
                        </v-progress-circular>
                    </div>
                                    
                    <div class="no-data-wrapper" v-if="!getProductsLoading && getProductItems.length == 0 && search === ''">
                        <div class="no-data-heading">
                            <img src="../../../assets/icons/empty-product-icon.svg" width="40px" height="42px" alt="">

                            <h3> Add Product </h3>
                            <p>
                                Let’s add the first product on Shifl. You can use this product list for creating Purchase Orders or for managing Inventory.
                            </p>

                            <div class="mt-4">
                                <v-btn color="primary" class="btn-blue add-product-button mx-auto" @click.stop="addProduct">
                                    Add Product
                                </v-btn>                            
                            </div>
                        </div>
                    </div>

                    <div class="no-data-wrapper" v-if="!getProductsLoading && getProductItems.length == 0 && search !== ''">
                        <div class="no-data-heading">
                            <img src="../../../assets/icons/empty-product-icon.svg" width="40px" height="42px" alt="">

                            <div>                           
                                <h3> No matching result </h3>
                                <p> We couldn’t find any product that matches your search term.
                                    <br/> Have you spelled it correctly? 
                                </p>
                            </div>
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
import { mapActions, mapGetters } from 'vuex'
import _ from 'lodash'
import SearchComponent from '../../SearchComponent/SearchComponent.vue'
import PaginationComponent from '../../PaginationComponent/PaginationComponent.vue'
// import FilterComponent from '../../FilterComponent/FilterComponent.vue'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: "ProductMobileTable",
    props: ['items', 'categoryLists', 'isMobile', 'productsNextPageLoading', 'searchVal'],
	components: {
        SearchComponent,
        PaginationComponent,
        // FilterComponent
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,        
		headers: [
			{
				text: "Sku",
                align: "start",
                sortable: false,
                value: "sku",
                width: "12%", 
                fixed: true
			},
			{
				text: "Name & Category",
                align: "start",
                sortable: false,
                value: "name",
                width: "15%", 
                fixed: true
			},
			{
				text: "Description",
                align: "start",
                sortable: false,
                value: "description",
                width: "25%", 
                fixed: true
			},
			{
				text: "In Each Carton",
                align: "end",
                sortable: false,
                value: "units_per_carton",
                width: "15%", 
                fixed: true
			},
            {
				text: "Duty Rate",
                align: "end",
                sortable: false,
                value: "duty_rate",
                width: "12%", 
                fixed: true
			},
            {
				text: "Unit Price",
                align: "end",
                sortable: false,
                value: "unit_price",
                width: "12%", 
                fixed: true
			},
			{
				text: "",
                align: "center",
                sortable: false,
                value: "actions",
                width: "10%", 
                fixed: true
			},
            {
				text: "sku_with_category",
                align: " d-none",
                sortable: false,
                value: "sku_with_category",
                width: "10%", 
                fixed: true
			},
		],
        // filters
        radioSortBy: 'name',
        radioSortByOptions: [
            {
                value: 'name',
                name: 'Name'
            },
            {
                value: 'category',
                name: 'Category'
            },
            {
                value: 'in_each_carton',
                name: 'Unit in each carton'
            },
            {
                value: 'unit_price',
                name: 'Unit Price'
            },
        ],
        filterMenu: false,
        filtersValue: {
            isAsc: true,
            selectedSort: '',
            category: '',
            customers: [],
            code: '',
            in_each_carton_min: '',
            in_each_carton_max: '',
            unit_price_min: '',
            unit_price_max: '',
            index: -1
        }
	}),
	computed: {
		...mapGetters({
            getCategories: 'category/getCategories',
			getProducts: 'products/getProducts',
			getProductsLoading: 'products/getProductsLoading',
			getUser: 'getUser',
			getDeleteProductLoading: 'products/getDeleteProductLoading',
            getSearchedProductsLoading: 'products/getSearchedProductsLoading',
            getSearchedProducts: 'products/getSearchedProducts',            
			getHas3PLProviderWarehouse: 'warehouseCustomers/getHas3PLProviderWarehouse',
            getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
        }),
        search: {
            get() {
                return this.searchVal
            },
            set(value) {
                this.$emit('update:searchVal', value)
            }
        },
        getProductItems() {
            // if (typeof this.getSearchedProducts !== 'undefined' && this.getSearchedProducts !== null) {
            //     if (this.search !== '' && this.getSearchedProducts.tab === 'products') {
            //         return this.getSearchedProducts.products
            //     } else {
            //         return this.items
            //     }
            // } else {
            //     return this.items
            // }

            // return this.items
            let value = this.items

            value.map(v => {
                Object.keys(v).map((key) => {
                    if (v[key] === 'null') {
                        v[key] = ""
                    }
                })
            })

            return value
        },
        getTotalPage: {
            get() {
				let totalPage = 1

                if (typeof this.getSearchedProducts !== 'undefined' && this.getSearchedProducts !== null) {
                    if (this.search !== '' && this.getSearchedProducts.tab === 'products') {
                        if (typeof this.getSearchedProducts.last_page !== 'undefined') {
                            totalPage = this.getSearchedProducts.last_page
                        }
                    } else {
                        if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                            if (typeof this.getProducts.last_page !== 'undefined') {
                                totalPage = this.getProducts.last_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                        if (typeof this.getProducts.last_page !== 'undefined') {
                            totalPage = this.getProducts.last_page
                        }
                    }
                }

				return totalPage
            }
        },
        getCurrentPage: {
            get() {
                let currentPage = 1

                if (typeof this.getSearchedProducts !== 'undefined' && this.getSearchedProducts !== null) {
                    if (this.search !== '' && this.getSearchedProducts.tab === 'products') {
                        if (typeof this.getSearchedProducts.current_page !== 'undefined') {
                            currentPage = this.getSearchedProducts.current_page
                        }
                    } else {
                        if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                            if (typeof this.getProducts.current_page !== 'undefined') {
                                currentPage = this.getProducts.current_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                        if (typeof this.getProducts.current_page !== 'undefined') {
                            currentPage = this.getProducts.current_page
                        }
                    }
                }

				return currentPage
            },
            set() {
                return {}
            }
        }, 
		getItemsPerPage: {
            get() {
				let itemsPerPage = 1

                if (typeof this.getSearchedProducts !== 'undefined' && this.getSearchedProducts !== null) {
                    if (this.search !== '' && this.getSearchedProducts.tab === 'products') {
                        if (typeof this.getSearchedProducts.per_page !== 'undefined') {
                            itemsPerPage = this.getSearchedProducts.per_page
                        }
                    } else {
                        if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                            if (typeof this.getProducts.per_page !== 'undefined') {
                                itemsPerPage = this.getProducts.per_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                        if (typeof this.getProducts.per_page !== 'undefined') {
                            itemsPerPage = this.getProducts.per_page
                        }
                    }
                }

				return itemsPerPage
            }
        },
        handleLoadingShow() {
            if (this.search === '') {
                return this.productsNextPageLoading
            } else {
                return this.getSearchedProductsLoading
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.getProductItems.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.getProductItems.length === 0) {
                isShow = true
            }

            return isShow
        },
        headersComputed() {
            let defaultHeaders = this.headers

            if (this.getHas3PLProviderWarehouse) {
                defaultHeaders = this.headers3PLProvider
            }

            return defaultHeaders
        },
        warehouseCustomerLists() {
            let data = []

            if (typeof this.getWarehouseCustomersDropdown !== "undefined" && this.getWarehouseCustomersDropdown !== null) {
                if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
                    this.getWarehouseCustomersDropdown.data.length !== "undefined") {
                    data = this.getWarehouseCustomersDropdown.data;

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
                }
            }

            return data
        },
        getSearchedDataClass() {
            if (this.getProductItems.length == 0 && this.search !== '') {
                return true
            } else {
                return false
            }
        },
	},
	created () {
	},
	methods: {
		...mapActions({
            fetchCategories: 'category/fetchCategories',
			fetchProducts: 'products/fetchProducts',
			createProducts: 'products/createProducts',
			getCurrentCategory: 'category/getCurrentCategory',
			deleteProduct: 'products/deleteProduct',
			updateProducts: 'products/updateProducts',            
			fetchSearchedProducts: 'products/fetchSearchedProducts',
			setSearchedProductsLoading: 'products/setSearchedProductsLoading',
			setSearchedProductsVal: 'products/setSearchedProductsVal',
			poBaseUrlState: 'products/poBaseUrlState',
        }),
		getImgUrl(pic) {
			if (pic !== 'undefined' && pic !== null) {
				return pic				
			} else {
				return require('../../../assets/icons/default-product-icon.svg')
			}
		},
		handleManageCategory() {
			this.$router.push(`products/manage-categories`)
		},
		getCategoryName(id) {
			if (this.categoryLists.length !== 0 && id) {
                let findSizeValue = _.find(this.categoryLists, (e) => (e.id == id))

                if (typeof findSizeValue !== 'undefined') {
                    if (findSizeValue.name !== 'undefined') {
                        return findSizeValue.name
                    }
                } else {
                    return '--'
                }
            } else {
                return '--'
            }
		},
        addProduct() {
            this.$emit('addProduct')
        },
        editProduct(product) {
            this.$emit('editProduct', product)
        },
        deleteProductItem(product) {
            this.$emit('deleteProductItem', product)
        },
        viewProductItem(item) {
            this.$emit('viewProductItem', item)
        },
        getParsedAmount(amount) {
            return parseFloat(amount).toFixed(2)
        },
        async handlePageChange(page) {
            if (this.search == '') {
                this.$emit('handlePageChange', page)
            } else {
                let data = {
                    search: this.search,
                    page
                }

                this.handlePageSearched(data)
            }

            this.handleScrollToTop()
        },
        clearSearched() {
            this.search = ''
            this.setSearchedProductsVal([])
            // document.getElementById("search-input").focus();
        },
		handleSearch() {
            if (cancel !== undefined) {
                cancel()
            }
            // this.setSearchedProductsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { 
                    search: this.search
                }  

                this.setSearchedProductsLoading(true)
                this.apiCall(data)
            }, 500)
        },
        apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: 'https://po.shifl.com/api/products',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        search: this.search,
                        page: 1
                    }
                }

                try {
                    passedData.tab = 'products'
                    this.fetchSearchedProducts(passedData)
                } catch(e) {
                    this.notificationError(e)
                    this.setSearchedProductsLoading(false)
                    console.log(e, 'Search error')
                }
            } else {
                this.setSearchedProductsVal([])
            }
        },
        async handlePageSearched(data) {
            let storePagination = this.$store.state.products.searchedProducts

            if (data !== null && this.search !== '') {
                if (storePagination.old_page !== data.page) {
                    storePagination.old_page = data.page

                    let passedData = {
                        method: "get",
                        url: 'https://po.shifl.com/api/products',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: this.search,
                            page: data.page
                        }
                    }

                    try {
                        passedData.tab = 'products'
                        this.fetchSearchedProducts(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedProductsLoading(false)
                        console.log(e, 'Search error')
                    }
                }                
            } else {
                this.setSearchedProductsVal([])
            }

            this.handleScrollToTop()
        },
        handleScrollToTop() {
            var table = this.$refs['my-table'];
            var wrapper = table.$el.querySelector('div.v-data-table__wrapper');
            
            this.$vuetify.goTo(table); // to table
            this.$vuetify.goTo(table, {container: wrapper}); // to header
        },
        openFilters() {
            this.filterMenu = true
        },
        closeFilters() {
            this.filterMenu = false
            this.clearFilter()
        },
        changeValueFilter(val) {
            this.filtersValue.isAsc = true

            if (val !== -1) {
                this.filtersValue.index = val
            }
        },
        toggleAscDesc(val, n, index) {
            this.filtersValue.isAsc = val
            this.filtersValue.selectedSort = n.value
            this.filtersValue.index = index
        },
        applyFilters() {
            console.log(this.filtersValue);
        },
        clearFilter() {
            this.filtersValue = {
                isAsc: true,
                selectedSort: '',
                category: '',
                customers: [],
                code: '',
                in_each_carton_min: '',
                in_each_carton_max: '',
                unit_price_min: '',
                unit_price_max: '',
                index: -1
            }
        }
	},
    mounted() {},
	updated() {}
}
</script>

<style lang="scss">
.product-mobile-table-wrapper {
    /* position: relative; */

    .overlay {
        &.show {
            position: absolute;
            top: 182px;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #fff !important;
            z-index: 100;
            cursor: auto;
            border-top: 1px solid #EBF2F5;

            .preloader {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 3%;
                padding-top: 20px;
            }
        }
    }
}
</style>
