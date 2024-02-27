<template>
    <div class="product-inventory-desktop-wrapper">
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
            :items="currentWarehouseProducts"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            item-key="category_sku"
            class="inventory-table product-inventory-table elevation-1"
            v-bind:class="{
                'no-data-table': (typeof currentWarehouseProducts !== 'undefined' && currentWarehouseProducts.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
                'no-searched-data': (getSearchedDataClass)
            }"
            hide-default-footer
            fixed-header
            show-select
            @click:row="viewProduct"
            :item-class="itemRowBackground"
            ref="my-table"
            v-model="selectedInventoryProducts">

            <template v-slot:top>
                <div class="inventory-search-wrapper search-and-filter" v-if="selectedInventoryProducts.length === 0">
                    <div class="search-and-filter">
                        <div v-if="(isWarehouse3PLProvider && !isWarehouseConnected) && handleFilterComponent" class="filter-tags-input mr-2">
                            <FilterWhCustomers 
                                @onClickFilter="onClickFilter"
                                @filterAllWarehouseCustomers="filterWithSearchProduct"
                                @cancelSelectingWarehouseCustomers="cancelFilteredProductList"
                                @searchWarehouseCustomers="searchWarehouseCustomers"
                                @removeCustomerLists="removeCustomerLists"
                                @removeCustomerListsEmptyOnChange="removeCustomerListsEmptyOnChange"
                                :searchCustomerData.sync="searchCustomerData"
                                :menu.sync="filterMenuWh"
                                :selectedWhCustomersCopy.sync="selectedWhCustomersCopy"
                                :selectedWhCustomers.sync="selectedWhCustomers"
                                :warehouseCustomerLists.sync="warehouseCustomerLists"
                                :loading="fetchWarehouseCustomersLoading"
                                @clickOutside="clickOutsideFilter"
                                :isActiveClicked.sync="isActiveClicked"
                                @clearAllFilter="clearAllFilter"
                            />
                        </div>

                        <FilterComponent :menu.sync="filterMenu" :isMobile="isMobile" 
                            :customClass="'from-product-inventory'"
                            :alignment="'left'"
                            @onClickCustomize="onClickCustomize"
                            :isCustomizedClicked.sync="isCustomizedClicked"
                            @onClickOutsideCustomize="clickOutsideCustomize">

                            <template v-slot:filter_title>
                                <img src="@/assets/icons/settings-blue.svg" class="filter-img mr-1" width="18px" height="18px">
                                <span class="filter-main-title">Customize Table</span>
                            </template>

                            <template v-slot:filter_body>
                                <div class="customized-sub-header">
                                    <h3>Customized Table</h3>
                                    <button class="btn-white deselect-all" @click="clearCustom">
                                        <v-icon>mdi-close</v-icon>
                                    </button>
                                </div>

                                <v-divider></v-divider>

                                <div class="select-deselect-all-wrapper">
                                    <button class="btn-white select-all" @click="selectAll">Select All</button>
                                    <button class="btn-white deselect-all" @click="deselectAll">Deselect All</button>
                                </div>

                                <v-divider></v-divider>

                                <div class="filter-component-body">
                                    <div v-for="(header, index) in customizedTablesHeader" @click="setActiveTrue()" :key="index">
                                        <v-checkbox                                            
                                            v-model="header.isChecked"
                                            :label="header.text"
                                            hide-details="auto"
                                            class="mt-0"
                                            v-if="isShowHeaderCustomized(header)"
                                            :disabled="header.disabled"
                                            @change="setActiveTrue()">
                                        </v-checkbox>
                                    </div>
                                </div>
                            </template>

                            <template v-slot:filter_actions>
                                <v-btn class="btn-apply btn-blue" @click="applyCustom(false)">
                                    Apply
                                </v-btn>

                                <v-btn class="btn-restore btn-black" @click="applyCustom(true)">
                                    Restore Default
                                </v-btn>
                            </template>
                        </FilterComponent>

                        <div v-if="handleSearchComponent">
                            <SearchComponent
                                placeholder="Search Products"
                                :searchValue.sync="search"
                                :handleSearchComponent="handleSearchComponent"
                                @handleSearch="handleSearch"
                                @clearSearched="clearSearched" />
                        </div>                        
                    </div>
                    
                    <button class="btn-white ml-2 mr-0" @click.stop="addInventoryProducts" v-if="isWarehouse3PL">
                        Add Inventory
                    </button>
                </div>

                <div class="inventory-search-wrapper search-and-filter" v-else>
                    <button class="btn-black ml-2 mr-0" @click.stop="clearSelectionsSelected">
                        Clear Selections
                    </button>
                    <button v-if="!isWarehouseConnected" class="btn-white ml-2 mr-0" style="color: #4A4A4A !important;" @click.stop="MultipleAdjustment(true)">
                        Adjustment
                    </button>

                    <button class="btn-white ml-2 mr-0" @click.stop="editPreferredMultiple(false)">
                        Edit Preferred Quantity
                    </button>
                </div>
            </template>

            <template v-slot:[`item.category_sku`]="{ item }">
                <div class="inventory-wrapper">
                    <div>{{ item.category_sku }}</div>
                </div>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="inventory-img" v-if="!isWarehouse3PLProvider">
                        <img :src="getImgUrl(item.image)" v-bind:alt="item.image" width="50px" height="50px" style="border:1px solid #EBF2F5;">
                    </div>

                    <div class="info-wrapper">
                        <p class="inventory-info" :class="isWarehouse3PLProvider ? 'service-provider' : ''">{{ item.name }}</p>
                        <!-- <p class="inventory-category" v-html="getCategory(item.category_id)"></p> -->
                        <p class="inventory-category">{{ item.category_name }}</p>
                    </div>
                </div>
            </template>           
            
            <template v-slot:[`item.warehouse_customer`]="{ item }">
                <span>{{ item.warehouse_customer }}</span>
            </template>

            <template v-slot:[`item.expected_carton_count`]="{ item }">
                <span>{{ item.expected_carton_count !== null ? getTotalCount(item.expected_carton_count) : 0 }}</span>
            </template>

             <template v-slot:[`item.available`]="{ item }">
                <span>{{ item.available !== null ? getTotalCount(item.available) : 0 }}</span>
            </template>

            <template v-slot:[`item.total_unit`]="{ item }">
                <span>{{ item.total_unit !== null ? getTotalCount(item.total_unit) : 0 }}</span>
            </template>

            <template v-slot:[`item.inbound`]="{ item }">
                <span>{{ typeof item.inbound !== 'undefined' && item.inbound !== null ? getTotalCount(item.inbound) : 0 }}</span>
            </template>

            <template v-slot:[`item.products_allocated`]="{ item }">
                <span>{{ typeof item.products_allocated !== 'undefined' && item.products_allocated !== null ? getTotalCount(item.products_allocated) : '--' }}</span>
            </template>

            <template v-slot:[`item.preferred`]="{ item }">
                <span>{{ item.preferred !== null ? getTotalCount(item.preferred) : 0 }}</span>
            </template>

            <template v-slot:[`item.delta`]="{ item }">
                <span :class="checkDeltaValue(item.delta)">
                    {{ item.delta >= 1 ? ('+' + getTotalCount(item.delta)) : getTotalCount(item.delta) }}
                </span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions mr-1">
                    <v-menu bottom left offset-y content-class="outbound-lists-menu">
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

                            <v-list-item @click="openEditProduct(item)" v-if="item.is_own_products === 1">
                                <v-list-item-title>
                                    Edit Product Info
                                </v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="MultipleAdjustment(false,item)" v-if="item.is_own_products === 1">
                                <v-list-item-title>
                                    Adjustment
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
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

                        <div v-if="currentTab == 0 && search === '' && selectedWhCustomers.length === 0">
                            <h3> Empty Products </h3>
                            <p>
                                This warehouse doesn’t have any product in inventory yet.
                            </p>
                        </div>

                        <div v-if="currentTab == 0 && search !== '' && selectedWhCustomers.length === 0">
                            <h3> No Products found. </h3>
                            <p>
                                No Products found. Try searching another keyword.
                            </p>
                        </div>
                        <div v-if="currentTab == 0 && selectedWhCustomers.length > 0">
                            <h3> No Products found. </h3>
                            <p>
                                No Products found. Change another Customer.
                            </p>
                        </div>
                    </div>

                    <div class="no-data-heading" v-if="isWarehouse3PL">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div v-if="currentTab == 0 && search === ''">
                            <h3> No Inventory </h3>
                            <p>
                                There is no inventory in this warehouse. Inventory level of products <br/>
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
                                No Products found. Try searching another keyword.
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
            @loadMoreInventoryBreakDowns="loadMoreInventoryBreakDowns"
            :isWarehouseConnected="isWarehouseConnected"
            :currentWarehouseSelected="currentWarehouseSelected"
            :getCurrentPage="getCurrentPage"
            :activityLogsData="activityLogsData"
            @loadMoreActivityLogs="loadMoreActivityLogs" />

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
            @callInboundProductsFor3PL="callInboundProductsFor3PL"
            :searchFromInventory="search"
            :productsData="currentWarehouseProducts" />

        <ProductAdjustment
            :dialogAdjustment.sync="productAdjustmentDialog"
            @closeAdjustment="productAdjustmentDialogClose"
            :selectedData="sendData"
            :defaultProducts="currentWarehouseProducts"
            :currentWarehouseSelected="currentWarehouseSelected"
            :isWarehouse3PL="isWarehouse3PL"
            :getCurrentPage="getCurrentPage"

        />

        <ConfirmDialog :dialogData.sync="editPreferredMultipleDialog" 
            :className="'with-header-dialog'" :customWidth="'695px'">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <h2 class="mb-0"> Edit Preferred Quantity </h2>
                    <v-btn icon dark class="btn-close" @click="closePreferredMultiple">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </div>
            </template>

            <template v-slot:dialog_content>
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="preferred-qty-wrapper-table-wrapper">
                        <v-data-table
                            :headers="headersPreferred"
                            :items="currentSelectedInventoryComputed"
                            :page.sync="page"
                            :items-per-page="35"
                            @page-count="pageCount = $event"
                            mobile-breakpoint="769"
                            item-key="category_sku"
                            class="inventory-table product-inventory-table elevation-0"
                            hide-default-footer
                            fixed-header>

                            <template v-slot:[`item.category_sku`]="{ item }">
                                <div class="inventory-wrapper">
                                    <div>{{ item.category_sku }}</div>
                                </div>
                            </template>

                            <template v-slot:[`item.name`]="{ item }">
                                <div class="inventory-wrapper d-flex align-center">
                                    <div class="inventory-img mr-1" style="width:45px;height:45px;">
                                        <img :src="getImgUrl(item.image)" v-bind:alt="item.image" width="45px" height="45px" style="border:1px solid #EBF2F5;border-radius:4px;">
                                    </div>

                                    <div class="info-wrapper">
                                        <p class="inventory-info" style="max-width:240px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">{{ item.name }}</p>
                                        <p class="inventory-category" style="font-size:12px;color:#6D858F;">
                                            {{ item.category_name }}
                                        </p>
                                    </div>
                                </div>
                            </template>           
                            
                            <template v-slot:[`item.preferred`]="{ item }">
                                <v-text-field
                                    type="text"
                                    class="text-fields" 
                                    placeholder="Enter preferred qty" 
                                    :class="item.preferred_qty === '' ? 'error-border' : ''"
                                    outlined
                                    hide-details="auto"
                                    :rules="rules"
                                    v-model="item.preferred_qty" />
                            </template>
                        </v-data-table>
                    </div>
                </v-form>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="editPreferredMultiple(true)" text :disabled="getEditPreferredQtyLoading">
                    <span v-if="!getEditPreferredQtyLoading">Save</span>
                    <span v-if="getEditPreferredQtyLoading">Saving...</span>
                </v-btn>

                <v-btn class="btn-white" text @click="closePreferredMultiple" :disabled="getEditPreferredQtyLoading">
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
import InventoryProductViewDialog from '../../../InventoryComponents/ProductsComponents/InventoryProductViewDialog.vue'
import ProductAddDialog from '../../../ProductComponents/Dialog/ProductAddDialog.vue'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'
import AddInventoryProducts from '../../../InventoryComponents/ProductsComponents/AddInventoryProducts.vue'
import FilterComponent from '../../../FilterComponent/FilterComponent.vue'
import _ from 'lodash'
import globalMethods from '../../../../utils/globalMethods'
import inventoryGlobalMethods from '../../../../utils/inventoryMethods/inventoryGlobalMethods'
import FilterWhCustomers from '../../../InventoryComponents/FilterCustomers/FilterCustomers'
import ConfirmDialog from '../../../Dialog/GlobalDialog/ConfirmDialog.vue'
import ProductAdjustment from "../../../InventoryComponents/ProductsComponents/ProductAdjustmentDialog.vue"
import axios from 'axios'
var cancel
var CancelToken = axios.CancelToken

export default {
    name: 'InventoryProductsDesktopTable',
    props: [
        'currentWarehouseSelected', 
        'isMobile', 
        'productsListsDataForAddInventory', 
        'fetchProductLoading', 
        'isWarehouseConnected', 
        'isWarehouse3PLProvider', 
        'isWarehouse3PL'
    ],
    components: {
        SearchComponent,
        InventoryProductViewDialog,
        ProductAddDialog,
        PaginationComponent,
        AddInventoryProducts,
        FilterComponent,
        FilterWhCustomers,
        ConfirmDialog,
        ProductAdjustment
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        headersDefault: [
			{
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'category_sku',
				fixed: true,
				width: "",
                isShow: true,
                disabled: true,
                isChecked: true,
                default: true
			},
			{ 
				text: 'Name & Category',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true,
                default: true
			},
            { 
				text: 'Customer',
				align: 'start',
				sortable: false,
				value: 'warehouse_customer',
				fixed: true,
				width: "18%",
                isShow: true,
                isChecked: true,
                default: true
			},
            { 
				text: 'Unit',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true,
                default: true
			},
			{ 
				text: 'Carton',
				align: 'end d-none',
				sortable: false,
				value: 'expected_carton_count',
				fixed: true,
				width: "",
                isShow: false,
                isChecked: false,
                default: false
			},
            { 
				text: 'Allocated',
				align: 'end',
				sortable: false,
				value: 'products_allocated',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true,
                default: true
			},
			{ 
				text: 'Available',
				align: 'end',
				sortable: false,
				value: 'available',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true,
                default: true
			},
            { 
				text: 'Inbound',
				align: 'end',
				sortable: false,
				value: 'inbound',
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true,
                default: true
			},
            { 
				text: 'Preferred',
				align: 'end d-none',
				sortable: false,
				value: 'preferred',
				fixed: true,
				width: "",
                isShow: false,
                isChecked: false,
                default: false
			},
            { 
				text: 'Delta',
				align: 'end d-none',
				sortable: false,
				value: 'delta',
				fixed: true,
				width: "",
                isShow: false,
                isChecked: false,
                default: false
			},
            { 
				text: 'On Floor',
				align: 'end d-none',
				sortable: false,
				value: 'on_floor_count',
				fixed: true,
				width: "",
                isShow: false,
                isChecked: false,
                default: false
			},
			{ 
				text: '', 
				align: 'center',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "",
                isShow: true,
                isChecked: true,
                default: true
			},
		],
        headersDefaultCopy: [],
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
        warehouseCustomerLists: [],
        warehouseCustomerListsCopy: [],
		lastCheckWHData: [],
        fetchWarehouseCustomersLoading:false,
        // checkbox
        filterMenu: false,
        isCustomizedClicked: false,
        // filter and search
        selectedWhCustomers: [],
        selectedWhCustomersCopy: [],
        searchCustomerData: '',
        filterMenuWh: false,
        isActiveClicked: false,
        selectedWhCustomerReAssignValue:[],
        productInventoryBreakdown:[],
        lastItemsInventroyBreakdownChecks:[],
        current_page_is_inventory_breakdown:1,
        current_page_is_activityLogs:1,
        selectedInventoryProducts: [],
        valid: true,
        editPreferredMultipleDialog: false,
        rules: [
            (v) => v !== '' || "Input is required."
        ],
        headersPreferred: [
            {
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'category_sku',
				fixed: true,
				width: "20%",
                isShow: true,
                disabled: true,
                isChecked: true,
                default: true
			},
			{ 
				text: 'Name & Category',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "40%",
                isShow: true,
                isChecked: true,
                default: true
			},
            { 
				text: 'Preferred Quantity',
				align: 'start',
				sortable: false,
				value: 'preferred',
				fixed: true,
				width: "25%",
                isShow: true,
                isChecked: true,
                default: true
			},
        ],
        // Product Ajustment
        productAdjustmentDialog:false,
        sendData:[],
        activityLogsData:[],
        lastItemsActivityLogsData:[]
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
            getProductInventoryHeaderDefault: 'productInventories/getProductInventoryHeaderDefault',
            // search and filter
            getProductInventoryFiltered: 'productInventories/getProductInventoryFiltered',
            getProductInventoryFilterLoading: 'productInventories/getProductInventoryFilterLoading',
            // Inventory Brekdown
            getProductInventoryBreakdown:'productInventories/getProductInventoryBreakdown',
            getEditPreferredQtyLoading: 'productInventories/getEditPreferredQtyLoading',
            getInventoryActivityLogs:'productInventories/getInventoryActivityLogs'
        }),
        allProductsDataFromAPI() {
            let productsLists = []

            if (typeof this.getProductInventorySearched !== 'undefined' && this.getProductInventorySearched !== null && this.getProductInventoryFiltered !== 'undefined' && this.getProductInventoryFiltered !== null) {
                if (this.search !== '' && this.selectedWhCustomers.length === 0 &&  this.getProductInventorySearched.tab === 'all') {
                    productsLists = this.getProductInventorySearched
                }
                else if(this.selectedWhCustomers.length > 0 && this.getProductInventoryFiltered.tab === 'all'){
                    productsLists = this.getProductInventoryFiltered
                }
                 else {
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

            if (inventories.length > 0) {
                inventories.map(v => {
                    Object.keys(v).map((key) => {
                        if (v[key] === 'null') {
                            v[key] = ""
                        }
                    })
                })
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
            }else if (this.selectedWhCustomers.length > 0 && this.currentWarehouseProducts.length === 0) {
                isShow = false
            }

            return isShow
        },
        handleFilterComponent() {
            let isShow = true

            if (this.search == '' && this.selectedWhCustomers.length === 0 && this.currentWarehouseProducts.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentWarehouseProducts.length === 0) {
                isShow = true
            } else if (this.search !== '' || this.currentWarehouseProducts.length > 0) {
				isShow = true
			}
            return isShow
        },
        getCurrentLoadingToDisplay() {
            if (this.search === '' && this.selectedWhCustomers.length === 0) {
                return this.fetchNextPageProductsLoading               
            }else if(this.search !== '' && this.selectedWhCustomers.length === 0){
                return this.getProductInventorySearchedLoading
            } else {
                return this.getProductInventoryFilterLoading
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
            let defaultHeaders = this.headersDefaultCopy

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
            } else {
                if (this.isWarehouseConnected) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'Customer' && v.isShow
                    })
                }
            }

            return defaultHeaders
        },
        customizedTablesHeader() {
            let defaultHeaders = this.headersDefaultCopy
            
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
            } else {
                if (this.isWarehouseConnected) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        return v.text !== 'Customer'
                    })
                }
            }

            return defaultHeaders
        },
        currentSelectedInventoryComputed() {
            let items = []

            if (this.selectedInventoryProducts.length > 0) {
                items = this.selectedInventoryProducts.map(v => {
                    let { preferred, ...otherItems } = v
                    return {
                        ...otherItems,
                        preferred,
                        preferred_qty: preferred
                    }
                })
            }

            return items
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
            setProductInventoryHeaderDefault: 'productInventories/setProductInventoryHeaderDefault',
            // search and flter
            fetchProductInventoryFiltered: 'productInventories/fetchProductInventoryFiltered',
            setFilteredInventoryProductLoading :'productInventories/setFilteredInventoryProductLoading',
            setInvnetoryFilteredVal :'productInventories/setInvnetoryFilteredVal',
            // Inventory Breakdown
            fetchInventoryBreakdown:'productInventories/fetchInventoryBreakdown',
            updatePreferredQtyMultipleAction: 'productInventories/updatePreferredQtyMultipleAction',
            // activity logs
            fetchActivityLogs:'productInventories/fetchActivityLogs'
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
            this.setInventoryProductSearchedVal([])
            this.setInvnetoryFilteredVal([])
            this.selectedWhCustomersCopy =[]
            this.selectedWhCustomers = []
            this.selectedWhCustomerReAssignValue = []
            this.search = ''
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
            let ActivityLogs_payload = {
                id:item.id,
                page:1
            }
            await this.fetchActivityLogs(ActivityLogs_payload)
            if (typeof this.getInventoryActivityLogs !== 'undefined' && this.getInventoryActivityLogs !== null) {
                if (typeof this.getInventoryActivityLogs.items !== 'undefined' && 
                    this.getInventoryActivityLogs.items !== null && 
                    Array.isArray(this.getInventoryActivityLogs.items) && this.getInventoryActivityLogs.items.length > 0) {

                    this.activityLogsData = this.getInventoryActivityLogs.items.map(v => {
                        return {
                            updated_by: v.updated_by,
                            updated_at: v.updated_at,
                            update_type: v.update_type,
                            id: v.id,
                            inventory_product_id:v.inventory_product_id,
                            description:v.description
                        }
                    })

                  this.lastItemsActivityLogsData =  this.activityLogsData
                }
            }else{
                this.activityLogsData = []
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
        async loadMoreActivityLogs () {
            if (this.current_page_is_activityLogs < this.getInventoryActivityLogs.last_page) {
                this.current_page_is_activityLogs++

                try {
                    let payload = { 
                        id: this.viewProductItem.id,
                        page: this.current_page_is_activityLogs                    
                    }

                    await this.fetchActivityLogs(payload)
                    if (typeof this.getInventoryActivityLogs !== 'undefined' && this.getInventoryActivityLogs !== null) {
                        if (typeof this.getInventoryActivityLogs.items !== 'undefined' && 
                            this.getInventoryActivityLogs.items !== null && 
                            Array.isArray(this.getInventoryActivityLogs.items) && 
                            this.getInventoryActivityLogs.items.length > 0) {    

                            let newloaddata = this.getInventoryActivityLogs.items.map(v => {
                                return {
                                    updated_by: v.updated_by,
                                    updated_at: v.updated_at,
                                    update_type: v.update_type,
                                    id: v.id,
                                    inventory_product_id:v.inventory_product_id,
                                    description:v.description
                                }
                            })

                            this.activityLogsData = [...this.activityLogsData, ...newloaddata]
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
            })
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
                            shipping_unit: v.shipping_unit,
                            reason:v.reason
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
                                    shipping_unit: v.shipping_unit,
                                    reason:v.reason
                                }
                            })

                            this.productInventoryLogs = [...this.productInventoryLogs, ...newloaddata]
                        }
                    }
                } catch(e) {
                    console.log(e)
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
                this.lastItemsActivityLogsData = []
                this.activityLogsData = []
                this.current_page_is_inventory_breakdown =1
                this.current_page_is_activityLogs = 1
            })
        },
        deleteItem(item) {
            console.log(item, 'delete')
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
                await this.callWarehouseCustomerListsData('nothing')
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
            if(this.isWarehouse3PLProvider) {
				if(this.selectedWhCustomers.length > 0) {
					this.setInvnetoryFilteredVal([])
					this.filterWithSearchProduct()
				}
			}
            // document.getElementById("search-input").focus()
        },
        handleSearch() {
            if (cancel !== undefined) {
                cancel("cancel_previous_request")
            }
            this.setSearchedInventoryProductsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { search: this.search }
                if (this.selectedWhCustomers.length > 0) {
                    return this.filterWithSearchProduct()
                }else{
                    this.setSearchedInventoryProductsLoading(true)
                    this.apiCall(data)
                }
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
                    console.log(e, 'Search error')
                }
            } else {
                this.setInventoryProductSearchedVal([])
            }
        },
        async handlePageChange(page) {
            this.handleScrollToTop()

            if (page !== null) {
                let storeProductsTab = this.$store.state.productInventories
                let dataWithPage = { id: this.currentWarehouseSelected.id, page }

                if (this.search == '' && this.selectedWhCustomers.length === 0) {
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
                } else if (this.search !== '' && this.selectedWhCustomers.length === 0) {
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
                        this.handlePageSearchedWithFiltered(data)
                    } else {
						if (this.search === '' && this.selectedWhCustomers.length > 0) {
							let	data = {
                                filter_data: this.selectedWhCustomers.map(val => val.id),
                                search_data: this.search,
                                wid: dataWithPage.id,
                                page
                            }
						    this.handlePageFilteredOnly(data)
						}
					}
                }
            }
        },
        handlePageSearched(data) {
            this.handleScrollToTop()
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
        },
        handlePageFilteredOnly(data) {
            this.handleScrollToTop()
            let searchedPagination = this.$store.state.productInventories

            if (data !== null && data.filter_data !== null &&  this.search === '' && this.selectedWhCustomers.length > 0) {
                if (searchedPagination.productInventoryFiltered.old_page !== data.page) {
                    this.setFilteredInventoryProductLoading(true)
                    var searchParams = new URLSearchParams()

					for (var ser = 0; ser < data.filter_data.length; ser++) {
					    searchParams.append(`ids[]`, data.filter_data[ser])
					}
					searchParams.append('page', data.page)

                    let passedData = {
                        method: "GET",
                        url: '',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: searchParams
                    }

                    passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/products?filter=true`, {params: searchParams}
                    passedData.tab = 'all'

                    try {
                        if (passedData.url !== '') {
                            this.fetchProductInventoryFiltered(passedData)
                        }
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredInventoryProductLoading(false)
                        console.log(e, 'Search error')
                    }
                }                
            } else {
                this.setInvnetoryFilteredVal([])
            }
        },
        handlePageSearchedWithFiltered(data) {
            this.handleScrollToTop()
            let searchedPagination = this.$store.state.productInventories

            if (data !== null && data.filter_data !== null &&  this.search !== '' && this.selectedWhCustomers.length > 0) {
                if (searchedPagination.productInventoryFiltered.old_page !== data.page) {
                    this.setFilteredInventoryProductLoading(true)
                    var searchParams = new URLSearchParams()

					for (var ser = 0; ser < data.filter_data.length; ser++) {
					    searchParams.append(`ids[]`, data.filter_data[ser])
					}
					searchParams.append('page', data.page)

                    let passedData = {
                        method: "GET",
                        url: '',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: searchParams
                    }

                    passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/products?search=${data.search_data}&filter=true`,{params: searchParams}
                    passedData.tab = 'all'

                    try {
                        if (passedData.url !== '') {
                            this.fetchProductInventoryFiltered(passedData)
                        }
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredInventoryProductLoading(false)
                        console.log(e, 'Search error')
                    }
                }                
            } else {
                this.setInvnetoryFilteredVal([])
            }
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
						this.categoryListData
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
            this.$emit('callProductsForAddInventory', 'Products-Inventory')
        },
        // call warehouse customers api
        async callWarehouseCustomerListsData(dataWithPage) {
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
							let data = this.getWarehouseCustomersDropdown.data

							this.warehouseCustomerListsData = data
							this.lastCheckWHData = data
                            this.warehouseCustomerListsCopy = data
                            this.warehouseCustomerLists = data

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}
							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
                            this.fetchWarehouseCustomersLoading =false
						}
					} else {
						this.warehouseCustomerListsData = []
						this.lastCheckWHData = []
                        this.warehouseCustomerLists = []
						this.warehouseCustomerListsCopy = []
                        this.fetchWarehouseCustomersLoading = false
					}
				}
			} catch(e) {
                this.fetchWarehouseCustomersLoading =false
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
							let data = this.getWarehouseCustomersDropdown.data
							this.warehouseCustomerListsData = [...this.warehouseCustomerListsData, ...data]
                            this.warehouseCustomerLists =[...this.warehouseCustomerLists, ...data]
							this.warehouseCustomerListsCopy =[...this.warehouseCustomerListsCopy, ...data]

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
        async callDefaultHeaderAction() {
            this.headersDefaultCopy = this.headersDefault

            if (this.getProductInventoryHeaderDefault.length === 0) {
                await this.setProductInventoryHeaderDefault(this.headersDefault)
                this.headersDefaultCopy = this.headersDefault
            } else {
                if (this.getProductInventoryHeaderDefault !== this.headersDefault) {
                    this.headersDefault = this.getProductInventoryHeaderDefault
                    this.headersDefaultCopy = this.getProductInventoryHeaderDefault
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
        applyCustom(isRestore) {
            let containsString = 'd-none'
            this.headersDefault = []

            this.headersDefaultCopy.map(v => {
                let getValue = null

                if (!isRestore) {
                    getValue = v.isChecked
                } else {
                    getValue = v.default
                }

                if (getValue !== null) {
                    if (!getValue) {
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
                }
            })

            this.headersDefault = this.headersDefaultCopy
            this.setProductInventoryHeaderDefault(this.headersDefault)
            this.filterMenu = false
            this.isCustomizedClicked = false
        },
        clearCustom() {
            this.headersDefault = []
            this.headersDefaultCopy.filter(v => {
                if (this.isWarehouse3PLProvider) {
                    if (v.isChecked !== v.isShow) {
                        v.isChecked = v.isShow
                    }
                } else {
                    if (this.isWarehouse3PL && (v.text !== 'Customer' || v.text !== 'On Floor')) {
                        if (v.isChecked !== v.isShow) {
                            v.isChecked = v.isShow
                        }
                    } else if (!this.isWarehouse3PL && v.text !== 'Customer') {
                        if (v.isChecked !== v.isShow) {
                            v.isChecked = v.isShow
                        }
                    }
                }
            })

            this.headersDefault = this.headersDefaultCopy
            this.setProductInventoryHeaderDefault(this.headersDefault)
            this.filterMenu = false
            this.isCustomizedClicked = false
        },
        selectAll() {
            this.isCustomizedClicked = true
            this.headersDefaultCopy.map(v => {
                v.isChecked = true
            })
        },
        deselectAll() {
            this.isCustomizedClicked = true
            this.headersDefaultCopy.map(v => {
                if (typeof v.disabled === 'undefined' && v.text !== '') {
                    v.isChecked = false
                }
            })
        },
        filterWithSearchProduct() {
            this.setFilteredInventoryProductLoading(false)
            let warehouse_id = this.currentWarehouseSelected.id
            let data = { 
                filter_data: this.selectedWhCustomers,
                search_data: this.search,
                wid: warehouse_id
            }  

            if (this.selectedWhCustomersCopy.length > 0 && this.search !== '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                data = {
                    filter_data : this.selectedWhCustomers.map(val => val.id),
                    search_data: this.search,
                    wid: warehouse_id
                }
                this.filteredAndSearchCustomer3plProvider(data)            
            }
            else if (this.selectedWhCustomersCopy.length >0 && this.search === '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                data = {
                    filter_data : this.selectedWhCustomers.map(val => val.id),
                    search_data: this.search,
                    wid: warehouse_id
                }
                this.filteredOnlyCustomer3plProvider(data)
            }
            this.setInvnetoryFilteredVal([])
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy    
            this.filterMenuWh = false
            this.isActiveClicked = false
	    },
        async filteredOnlyCustomer3plProvider(data) {
            if (data !== null && data.filter_data !== null && this.selectedWhCustomers.length !== 0 && this.search === '' && data.search_data == '') {
				this.setFilteredInventoryProductLoading(true)
				var searchParams = new URLSearchParams()

				for(var ser = 0; ser < data.filter_data.length; ser++) {
					searchParams.append(`ids[]`, data.filter_data[ser])
                }
                searchParams.append('page', 1)

                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }   
                passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/products?filter=true`, {params: searchParams}
                passedData.tab = 'all'

                if (passedData.url !== '') {
                    try {
                      await this.fetchProductInventoryFiltered(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredInventoryProductLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            } else {
                this.setInvnetoryFilteredVal([])
            }
	    },
		async filteredAndSearchCustomer3plProvider(data) {
            if (data !== null && data.filter_data !== null && this.selectedWhCustomers.length !== 0 && this.search !== '' && data.search_data !== '') {
				this.setFilteredInventoryProductLoading(true)
				var searchParams = new URLSearchParams()

				for(var ser = 0; ser < data.filter_data.length; ser++){
					searchParams.append(`ids[]`, data.filter_data[ser])
                }
                searchParams.append('page', 1)

                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }
                passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/products?search=${data.search_data}&filter=true`,{params: searchParams}
                passedData.tab = 'all'

                if (passedData.url !== '') {
                    try {
                        await this.fetchProductInventoryFiltered(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredInventoryProductLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.setInvnetoryFilteredVal([])
            }
	    },
        /* eslint-disable */
	    cancelFilteredProductList() {
            if (this.selectedWhCustomers.length === 0) {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
                this.setInvnetoryFilteredVal([])
            } else {
                // if (this.selectedWhCustomers !== this.selectedWhCustomersCopy) {
                //     this.selectedWhCustomersCopy = this.selectedWhCustomers
                // }
                this.selectedWhCustomersCopy = this.selectedWhCustomerReAssignValue.map(({dummy_value_Add_For_same_refrence,...rest}) => ({...rest}));
                this.selectedWhCustomers = this.selectedWhCustomersCopy
            }
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
            this.filterMenuWh = false
            this.isActiveClicked = false
		},
        /* eslint-enable */
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
		removeCustomerListsEmptyOnChange() {
			if(this.selectedWhCustomersCopy.length === 0) {
				this.setInvnetoryFilteredVal([])
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomers = []
                this.selectedWhCustomerReAssignValue = []
			}
            // this.warehouseCustomerListsCopy = this.warehouseCustomerLists
		},
		removeCustomerLists(item, removeIs) {
            if (removeIs === 'single') {
                // let index = this.selectedWhCustomersCopy.indexOf(item)
                let indexLodash = _.findIndex(this.selectedWhCustomersCopy, e => (e.id == item.id))	
                
                if (indexLodash > -1){
					this.selectedWhCustomersCopy.splice(indexLodash, 1)
				} 
            } else {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
				this.setInvnetoryFilteredVal([])

                if (this.search !== '') {
                    setTimeout(() => {
                        this.filterMenuWh = false
                        let data = { search: this.search } 
                        this.setSearchedInventoryProductsLoading(true)
                        this.apiCall(data)
                    }, 200)
                }
            }
            // this.selectedWhCustomers = this.selectedWhCustomersCopy
        },
        onClickCustomize() {
            if (!this.filterMenu) {
                this.filterMenu = true
            }
        },
        clickOutsideCustomize() {
            if (this.isCustomizedClicked) {
                this.filterMenu = false
                this.clearCustom()
                this.isCustomizedClicked = false
            }
        },
        setActiveTrue() {
            this.isCustomizedClicked = true
        },
        // 
        onClickFilter() {
            if (!this.filterMenuWh) {
                this.filterMenuWh = true
                var deepCopy = _.cloneDeep(this.selectedWhCustomersCopy);
                this.selectedWhCustomerReAssignValue = deepCopy
                this.selectedWhCustomerReAssignValue = this.selectedWhCustomerReAssignValue.map(v => ({ ...v, dummy_value_Add_For_same_refrence: v.name }))
            }
        },
        clickOutsideFilter() {
            if (this.isActiveClicked) {
                this.filterMenuWh = false
                this.cancelFilteredProductList()
                this.isActiveClicked = false
            }
        },
        clearAllFilter() {
            if (this.selectedWhCustomers.length > 0) {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
                this.setInvnetoryFilteredVal([])
            }
        },
        clearSelectionsSelected() {
            this.selectedInventoryProducts = []
        },
        async editPreferredMultiple(isConfirm) {
            this.editPreferredMultipleDialog = true

            if (isConfirm) {
                try {
                    if (this.$refs.form.validate()) {
                        let items = this.currentSelectedInventoryComputed.map(v => {
                            return {
                                inventory_product_id: v.id,
                                preferred: v.preferred_qty
                            }
                        })

                        let payload = { data: items }
                        await this.updatePreferredQtyMultipleAction(payload)
                        this.notificationCustom('Preferred has been updated.')
                        this.closePreferredMultiple()
                        this.clearSelectionsSelected()

                        let dataWithPage = { 
                            page: this.getCurrentPage,
                            id: this.currentWarehouseSelected.id
                        }

                        if (!this.isWarehouse3PL) {
                            await this.fetchProductInventories(dataWithPage)
                        } else {                  
                            await this.fetchProductInventories3pl(dataWithPage)
                            this.$emit('callProductsForAddInventory', 'Inventory')
                        }
                    }                    
                } catch(e) {
                    this.notificationCustom(e)
                }
            }
        },
        closePreferredMultiple() {
            this.editPreferredMultipleDialog = false
        },
        MultipleAdjustment(isMultiple,item){
            if(isMultiple){
                this.sendData = this.selectedInventoryProducts.map(v =>{
                    return {
                        adjusted_quantity:0,
                        available_after:v.total_unit,
                        product_sku:v.product_sku,
                        name:v.name,
                        id:v.id,
                        shipping_unit:v.shipping_unit,
                        difference:0,
                        current_quantity:v.total_unit,
                        transferred:0
                    }
                })
            }else{
                this.sendData = [item].map(v =>{
                    return {
                        adjusted_quantity:0,
                        available_after:v.total_unit,
                        product_sku:v.product_sku,
                        name:v.name,
                        id:v.id,
                        shipping_unit:v.shipping_unit,
                        difference:0,
                        current_quantity:v.total_unit,
                        transferred:0
                    }
                })
            }
            this.productAdjustmentDialog = true
        },
        productAdjustmentDialogClose(){
            this.selectedInventoryProducts = []
            this.sendData = []
            this.productAdjustmentDialog =false
        }
    },
    async mounted() {
        this.callDefaultHeaderAction()
        // let checkWarehouseTypeId = this.currentWarehouseSelected !== null ? this.currentWarehouseSelected.warehouse_type_id : null

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

        this.setAllWarehouseCustomerLists([])
        this.fetchWarehouseCustomersLoading = true
        try{

            let dataWithPage = {
                cancelToken: new CancelToken(function executor(c) {
                    cancel = c
                }),
            }
            await this.callWarehouseCustomerListsData(dataWithPage)
        }catch(e){
            if (e !== "cancel_previous_request") this.notificationError(e)
        }
    },
    beforeDestroy(){
		if (cancel !== undefined) {
			cancel("cancel_previous_request")
		}
	},
    updated() {
        this.callDefaultHeaderAction()
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/product/productTable.scss';
.append-filter-button-inventroy {
    position: sticky;
    bottom: 0;
    width: 100%;
    display:flex;
    justify-content: flex-start;
    background:white;  
    padding: 8px 16px;
}

.preferred-qty-wrapper-table-wrapper {
    .v-data-table {
        .v-data-table__wrapper {
            table {
                thead {
                    tr {
                        th {
                            height: 40px;
                            background-color: #f7f7f7;
                            padding: 8px 12px !important;
                        }
                    }
                }

                tbody {
                    tr {
                        &:hover {
                            background-color: #fff !important;
                        }

                        td {
                            padding: 6px 12px !important;
                            border: 1px solid #EBF2F5;

                            &:first-child,
                            &:nth-child(2) {
                                background-color: #EBF2F5;
                                border-color: #D8E7F0;
                            }

                            &:last-child {
                                padding: 0 !important;

                                .v-input {
                                    .v-input__control {
                                        .v-input__slot {
                                            min-height: 56px !important;

                                            fieldset {
                                                border: none !important;
                                                border-radius: 0 !important;
                                            }
                                        }
                                    }

                                    &.error-border {
                                        .v-input__control {
                                            .v-input__slot {
                                                margin-bottom: 0;

                                                fieldset {
                                                    border: 1px solid #F93131 !important;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
</style>