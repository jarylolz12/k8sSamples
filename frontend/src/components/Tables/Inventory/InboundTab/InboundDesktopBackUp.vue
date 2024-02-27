<template>
    <div class="inbounds-desktop-wrapper">
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
            :items="currentWarehouseInbounds.data"
            :page.sync="getCurrentPage"
            :items-per-page="itemsPerPage"
            item-key="order_id"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"            
            class="inventory-table inbound-table elevation-1"
            v-bind:class="{
                'no-data-table': (typeof currentWarehouseInbounds.data !== 'undefined' && currentWarehouseInbounds.data.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
                'no-searched-data' : getSearchedDataClass,
                'type-3pl' : currentWarehouseSelected.warehouse_type_id === 3
            }"
            hide-default-footer
            fixed-header
            show-select
            ref="my-table"
            @click:row="viewInbound">

            <template v-slot:top>
                <div class="inventory-search-wrapper inbound-search">
                    <v-tabs class="inventory-inner-tab" @change="onTabChange" v-model="currentTab">
                        <v-tab v-for="(tab, index) in tabs" 
                            :key="index" 
                            :class="index == 3 ? `inventory-inner-tab-last ${tab}` : `${tab}`"
                            @click="onClickTab(index)"> {{ tab }}

                            <small v-if="(index == 0 || index == 1) && getTabCount(index) !== '0'">
                                {{ getTabCount(index) }}
                            </small>
                        </v-tab>
                    </v-tabs>

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
                            placeholder="Search Inbound"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />

                        <v-btn color="primary"
                            dark class="btn-blue ml-2" 
                            @click.stop="addNewInbound">
                            Create Inbound
                        </v-btn>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div class="inventory-wrapper">
                    {{ 
                        typeof item.name !== 'undefined' && item.name !== null && 
                        item.name !== 'null' ? item.name : '--' 
                    }}
                </div>
            </template>

            <template v-slot:[`item.ref_no`]="{ item }">
                <div class="inventory-wrapper">
                    {{ 
                        typeof item.ref_no !== 'undefined' && item.ref_no !== null && 
                        item.ref_no !== 'null' ? item.ref_no : '--' 
                    }}
                </div>
            </template>

            <template v-slot:[`item.inbound_status`]="{ item }">
                <div v-if="currentWarehouseSelected.warehouse_type_id !== 3">
                    <div class="status inbound">
                        <span :class="item.inbound_status">
                            {{ item.inbound_status }}
                        </span>
                    </div>

                    <p class="undershipped" v-if="item.is_undershipped !== 0">
                        {{ getOvershippedInbound(item) }}
                    </p>
                </div>

                <div v-else>
                    <!-- if not under/overshipped -->
                    <div v-if="item.is_undershipped === 0"> 
                        <!-- show status if it's not pending, else hide -->
                        <div v-if="item.inbound_status !== 'pending'">
                            <div class="status inbound">
                                <span :class="item.inbound_status"> 
                                    {{ item.inbound_status }} 
                                </span>
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <span class="undershipped-status"> 
                            {{ getOvershippedInbound(item) }} 
                        </span>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.estimated_time`]="{ item }">
                <div class="estimated_time">
                    <span>{{ formatNewDate(item.estimated_date, item.estimated_time) }}</span>
                </div>
            </template>

            <template v-slot:[`item.warehouse_customer`]="{ item }">
                <span> {{ item.warehouse_customer !== null ? item.warehouse_customer.company_name : '' }} </span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions mr-2">
                    <v-menu bottom offset-y left content-class="outbound-lists-menu">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on">
                                <v-icon>mdi-dots-horizontal</v-icon>
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <v-list-item class="arrived-item" @click="arrived(item)" 
                                :class="isWarehouse3PL ? 'warehouse-3pl-hidden': ''" 
                                v-if="currentTab == 0 && item.inbound_status !== 'arrived'">
                                <v-list-item-title class="arrived">
                                    <img src="@/assets/icons/checkMark.png" height="16px" width="16px" class="mr-2">
                                    Arrived
                                </v-list-item-title>
                            </v-list-item>

                            <v-list-item class="all-receive-item" @click="receiveAllProductsInbound(item)" 
                                v-if="isWarehouse3PL && currentTab == 0 && item.inbound_status === 'pending'">
                                <v-list-item-title class="arrived">
                                    <img src="@/assets/icons/checkMark.png" height="16px" width="16px" class="mr-2">
                                    All Received
                                </v-list-item-title>
                            </v-list-item>

                            <div class="lists-separator" 
                                v-if="(!isWarehouse3PL && currentTab == 0 && item.inbound_status !== 'arrived')">
                            </div>

                            <div class="lists-separator" 
                                v-if="(isWarehouse3PL && currentTab == 0 && item.inbound_status === 'pending')">
                            </div>                            

                            <v-list-item @click="completeOrder(item)" v-show="findFloorReceivedProducts(item) && !isHasStorableUnits(item)">
                                <v-list-item-title class="arrived"> Mark as Completed </v-list-item-title>
                            </v-list-item>      

                            <v-list-item @click="viewInbound(item)">
                                <v-list-item-title> View </v-list-item-title>
                            </v-list-item>

                            <v-list-item @click="checkEditInbound(item, 'edit')" v-if="canStillEditInbound()">
                                <v-list-item-title> Edit </v-list-item-title>
                            </v-list-item>

                            <v-list-item @click="openNewStorableUnit(item)" v-show="findFloorReceivedProducts(item)">
                                <v-list-item-title> New Storable Unit </v-list-item-title>
                            </v-list-item>                      

                            <v-list-item @click="checkEditInbound(item, 'copy')" v-if="item.is_from_inventory !== 1">
                                <v-list-item-title> Duplicate </v-list-item-title>
                            </v-list-item>

                            <v-list-item @click="printOrder(item)" v-if="item.is_from_inventory !== 1">
                                <v-list-item-title> Print Order </v-list-item-title>
                            </v-list-item>

                            <v-list-item @click="cancelOrder(item)" v-if="isShowCancelButton(item)">
                                <v-list-item-title class="cancel"> Cancel Order </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="currentWarehouseLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper" 
                    v-if="!currentWarehouseLoading && currentWarehouseInbounds.data.length == 0">
                    <div class="no-data-heading" v-if="search === '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div v-if="currentTab === 0">
                            <h3> Create Inbound </h3>
                            <p> Let's add inbound to this warehouse. </p>

                            <div class="d-flex justify-center align-center mt-4">
                                <v-btn color="primary" dark class="btn-blue" @click.stop="addNewInbound">
                                    Create Inbound
                                </v-btn>
                            </div>
                        </div>                 

                        <div v-if="currentTab === 1">
                            <h3> No Floor Inbounds </h3>
                            <p> There are no floor inbounds listed. </p>
                        </div>

                        <div v-if="currentTab === 2">
                            <h3> No Completed Inbounds </h3>
                            <p> There are no completed inbounds listed. </p>
                        </div>

                        <div v-if="currentTab === 3">
                            <h3> No Cancelled Inbounds </h3>
                            <p> There are no cancelled inbounds listed. </p>
                        </div>
                    </div>

                    <div class="no-data-heading" v-if="search !== '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div>
                            <h3> No Inbounds found. </h3>
                            <p> There are no inbounds listed. Try searching another keyword.</p>
                        </div>
                    </div>
                    <div v-if="selectedWhCustomers.length > 0">
						<img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div>
                            <h3> No Inbounds found. </h3>
                            <p> There are no inbounds listed. Change another Customer.</p>
                        </div>
					</div>
                </div>
            </template>

            <!-- FOR 3PL -->
            <template v-slot:[`item.created_at`]="{ item }">
                <div class="estimated_time" v-if="!isWarehouse3PLProvider">
                    <span>{{ formatDateDefault(item.created_at) }}</span>
                </div>

                <div class="estimated_time" v-if="isWarehouse3PLProvider">
                    <p class="mb-0">
                        {{ 
                            typeof item.customer_admin_name !== 'undefined' && 
                            item.customer_admin_name !== null ?
                            item.customer_admin_name : '--'
                        }}
                    </p>
                    <span style="color: #6D858F;">{{ formatDateDefault(item.created_at) }}</span>
                </div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="estimated_time">
                    <span>{{ formatDateDefault(item.updated_at) }}</span>
                </div>
            </template>
        </v-data-table>

        <CreateInboundDialog 
            :dialogCreate.sync="dialogCreate"
            :editedInboundIndex.sync="editedInboundIndex"
            :editedInboundItems.sync="editedInboundItems"
            :defaultInboundItems.sync="defaultInboundItems"
            :currentWarehouseSelected="currentWarehouseSelected"
            @close="closeCreate"
            :isMobile="isMobile"
            :parentTab.sync="currentTab"
            :productListsDropdownData.sync="productsListsData"
            :fetchProductLoading="fetchProductLoading"
            @callInboundProductsFor3PL="callInboundProductsFor3PL"
            :searchVal="search"
            :currentWarehouseInboundsData="currentWarehouseInbounds.data"
            @callWarehouseCustomerProducts="callWarehouseCustomerProducts"
            :fetchWarehouseCustomersLoading="fetchWarehouseCustomersLoading" />

        <ConfirmDialog :dialogData.sync="dialogCancel">
            <template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2> Cancel Order </h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					Are you sure you want to cancel inbound order
					<span class="name">"{{ cancelOrderData !== null ? cancelOrderData.order_id : '' }}"</span>?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="cancelConfirm" text :disabled="getCancelInboundLoading">
					<span v-if="!getCancelInboundLoading">Confirm</span>
                    <span v-if="getCancelInboundLoading">Confirming...</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeCancel" :disabled="getCancelInboundLoading">
					Discard
				</v-btn>
			</template>
		</ConfirmDialog>      

        <TruckArrivedDialog 
            :dialogData.sync="dialogTruckArrived"
            :editedItemData.sync="truckArrivedData"
            @close="closeTruckArrivedDialog"
            :loading="getTruckArrivedLoading"
            :linkData="linkData"
            :isFetchSingleInbound="false" />

        <NewStorableUnitDialog 
            :dialog.sync="dialogNewStorableUnit" 
            :editedItem.sync="storableItemsData"
            :productsData="newStorableData"
            :linkData="linkData"
            @close="closeStorableUnit"
            :index="-1" />  

        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />

        <!-- SHOW edit once 3PL and Completed -->
        <ConfirmDialog :dialogData.sync="showWarningEditInboundDialog">
            <template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2> Edit Completed Order? </h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					This order has already been completed. Do you still want to edit this inbound order?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="confirmEditOrder" text>
                    Edit Order
				</v-btn>

				<v-btn class="btn-white" text @click="closeWarning">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>

        <ReceiveProductDialog 
            :dialogData.sync="dialogReceiveProduct"
            :editedItemData.sync="productSelectedToReceive"
            @close="closeProductReceive"
            :loading="false"
            :productLists="productsListsData"
            :linkData="linkData"
            :multiple="true"
            @clearSelection="clearSelection"
            :title="title"
            :inboundStatus="selectedInboundStatus"
            :isWarehouse3PL="isWarehouse3PL"
            :undershipped="isSelectedUndershipped" />

        <!-- FOR EDIT INVENTORY -->
        <AddInventoryProducts 
            :editedProductIndex="editedProductIndex"
            :editedProductItems.sync="editedProductItems"
            :dialogAdd.sync="dialogEditFromInventory"
            :isMobile="isMobile"
            :productListsDropdownData="productsListsData"
            @close="closeEditInventory"
            :currentWarehouseSelected="currentWarehouseSelected"
            @openAddProductDialog="openAddProductDialog"
            :fetchProductLoading="fetchProductLoading"
            :isFetchSingleInbound="false" />

        <ProductAddDialog 
            :dialog.sync="dialogEditProduct"
            :editedIndex.sync="editedIndexProductCreate"
            :defaultItem.sync="defaultProductItemCreate"
            :editedItem.sync="editedProductItemCreate"
            :categoryLists="categoryListData"
            @close="closeProductDialogCreate"
            @setToDefault="setToDefault"
            :isMobile="isMobile"
            :isInventoryPage="true"
            :isWarehouse3PL="isWarehouse3PL"
            :isWarehouse3PLProvider="isWarehouse3PLProvider"
            @callInboundProductsFor3PL="callInboundProductsFor3PL"
            @callWarehouseCustomerProducts="callWarehouseCustomerProducts"
            :inboundItems="editedInboundItems" />

        <!-- Complete Order Dialog -->
        <ConfirmDialog :dialogData.sync="dialogConfirm">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/question-blue.svg" width="48px" height="48px">
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2> Confirm Order as Completed? </h2>
            </template>

            <template v-slot:dialog_content>
                <p> Do you want to mark the Inbound order completed? </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="confirmCompleted" text :disabled="getCompleteInboundOrderLoading">
                    <span v-if="!getCompleteInboundOrderLoading">Mark as Completed</span>
                    <span v-if="getCompleteInboundOrderLoading">Marking...</span>
                </v-btn>

                <v-btn class="btn-white" text @click="closeCompletedDialog">
                    Cancel
                </v-btn>
            </template>
        </ConfirmDialog>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import SearchComponent from '../../../SearchComponent/SearchComponent.vue'
import CreateInboundDialog from '../../../InventoryComponents/InboundComponents/Dialogs/CreateInboundDialog.vue'
import ConfirmDialog from '../../../Dialog/GlobalDialog/ConfirmDialog.vue'
import TruckArrivedDialog from '../../../InventoryComponents/InboundComponents/Dialogs/TruckArrivedDialog.vue'
import NewStorableUnitDialog from '../../../InventoryComponents/InboundComponents/Dialogs/NewStorableUnitDialog.vue'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'
import ReceiveProductDialog from '../../../InventoryComponents/InboundComponents/Dialogs/ReceiveProductDialog.vue'
import AddInventoryProducts from '../../../InventoryComponents/ProductsComponents/AddInventoryProducts.vue'
import ProductAddDialog from '../../../ProductComponents/Dialog/ProductAddDialog.vue'
import globalMethods from '../../../../utils/globalMethods'
import _ from 'lodash'
import inventoryGlobalMethods from '../../../../utils/inventoryMethods/inventoryGlobalMethods'
import FilterCustomers from '../../../InventoryComponents/FilterCustomers/FilterCustomers.vue'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'InventoryInboundDesktopTable',
    props: ['currentWarehouseSelected', 'isMobile'],
    components: {
        SearchComponent,
        CreateInboundDialog,
        ConfirmDialog,
        TruckArrivedDialog,
        NewStorableUnitDialog,
        PaginationComponent,
        ReceiveProductDialog,
        AddInventoryProducts,
        ProductAddDialog,
        FilterCustomers
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        selected: [],
        headersDefault: [
            {
				text: 'Order ID',
				align: 'start',
				sortable: false,
				value: 'order_id',
				fixed: true,
				width: "6%"
			},
            { 
                text: 'Created At',
                align: 'start',
                sortable: false,
                value: 'created_at',
                fixed: true,
                width: "16%"
            },
            { 
                text: 'Created By & At',
                align: 'start',
                sortable: false,
                value: 'created_at',
                fixed: true,
                width: "16%"
            },
            { 
				text: 'Truck',
				align: 'start',
				sortable: false,
				value: 'name',
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
				text: 'Customer',
				align: 'start',
				sortable: false,
				value: 'warehouse_customer',
				fixed: true,
				width: "20%"
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
				text: 'ETA/Arrival Time',
				align: 'start',
				sortable: false,
				value: 'estimated_time',
				fixed: true,
				width: "16%"
			},
            { 
				text: 'Status',
				align: 'center',
				sortable: false,
				value: 'inbound_status',
				fixed: true,
				width: ""
			},
            { 
                text: 'Alert',
                align: 'center',
                sortable: false,
                value: 'inbound_status',
                fixed: true,
                width: ""
            },
            { 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: ""
			},
        ],
		dialogCreate: false,
		editedInboundIndex: -1,
        editedInboundItems: {
			order_id: '',
            supplier: '',
            shipping_from: '',
            notes: '',
            ref_no: '',
            name: '',
            estimated_date: null,
            estimated_time: null,
            inbound_products: [
                {
                    product: {
                        product_id: '',
                    },
                    product_id: '',
                    quantity: '',
                    shipping_unit: ''
                    // total_unit: ''
                }
            ],
            documents: [],
            warehouse_customer_id: ''
		},
		defaultInboundItems: {
			order_id: '',
            supplier: '',
            shipping_from: '',
            notes: '',
            ref_no: '',
            name: '',
            estimated_date: null,
            estimated_time: null,
            inbound_products: [
                {
                    product: {
                        product_id: '',
                    },
                    product_id: '',
                    quantity: '',
                    shipping_unit: ''
                }
            ],
            documents: [],
            warehouse_customer_id: ''
		},
        tabs: ["Pending", "Floor", "Completed", "Cancelled"],
        currentTab: 0,
        dialogCancel: false,
        cancelOrderData: null,
        dialogTruckArrived: false,
        truckArrivedData: {
            name: '',
            ref_no: ''
        },
        linkData: {
            inbound_id: '',
            warehouse_id: ''
        },
        dialogNewStorableUnit: false,
        newStorableData: null,
        pendingInboundLoadingPage: false,
        floorInboundLoadingPage: false,
        completedInboundLoadingPage: false,
        cancelledInboundLoadingPage: false,
        storableItemsData: {
            action: "",
            type: "",
            customer_id: null,
            dimension: {
                l: '',
                w: '',
                h: '',
                uom: 'cm'
            },
            weight: {
                value: '',
                unit: 'KG'
            },
            products: [],
            copies: 1
        },
        productsListsData: [],
        lastDataCheck: [],
        current_page_is: 1,
        fetchProductLoading: false,
        fetchPendingInboundsLoading: true,
        showWarningEditInboundDialog: false,
        currentEditInboundData: null,
        dialogReceiveProduct: false,
        title: 'receive',
        productSelectedToReceive: [],
        selectedInboundStatus: 'pending',
        isSelectedUndershipped: 0,
        dialogEditFromInventory: false,
        editedProductIndex: 0,
        editedProductItems: {
            inventory_as_of: '',
            notes: '',
            inbound_products: []
        },
        defaultProductItems: {
            inventory_as_of: '',
            notes: '',
            inbound_products: []
        },
        // add product dialog
        dialogEditProduct: false,
        editedIndexProductCreate: -1,
        editedProductItemCreate: {
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
		defaultProductItemCreate: {
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
        categoryListData: [],
        current_page_is_category: 1,
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
        // complete order
        dialogConfirm: false,
        toCompleteOrderData: null,
        selectedWhCustomerReAssignValue:[]
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getCancelInboundLoading: 'inbound/getCancelInboundLoading',
            getTruckArrivedLoading: 'inbound/getTruckArrivedLoading',
            // inbound loading
            getPendingInboundsLoading: 'inbound/getPendingInboundsLoading',
            getFloorInboundsLoading: 'inbound/getFloorInboundsLoading',
            getCompletedInboundsLoading: 'inbound/getCompletedInboundsLoading',
            getCancelledInboundsLoading: 'inbound/getCancelledInboundsLoading',
            // inbound data and paginations
            getPendingInboundPagination: 'inbound/getPendingInboundPagination',
            getFloorInboundPagination: 'inbound/getFloorInboundPagination',
            getCompletedInboundPagination: 'inbound/getCompletedInboundPagination',
            getCancelledInboundPagination: 'inbound/getCancelledInboundPagination',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
            poBaseUrlState: 'products/poBaseUrlState',
            getSearchedInbounds: 'inbound/getSearchedInbounds',
            getSearchedInboundsLoading: 'inbound/getSearchedInboundsLoading',
            getAllInboundProductsLists: 'inbound/getAllInboundProductsLists',
            getAllInboundProductsListsDropdownData: 'inbound/getAllInboundProductsListsDropdownData',
            getIsShowCreateInboundDialog: 'inbound/getIsShowCreateInboundDialog',
            getAllCategoryDropdownLists: 'productInventories/getAllCategoryDropdownLists',
            getAllWarehouseCustomerInboundProductsLists: 'inbound/getAllWarehouseCustomerInboundProductsLists',
            getAllWarehouseCustomerListsData: 'warehouseCustomers/getAllWarehouseCustomerListsData',
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            // filter and search
            getfilteredInboundsLoading:'inbound/getfilteredInboundsLoading',
            getfilteredInbounds:'inbound/getfilteredInbounds',
            getCompleteInboundOrderLoading: 'inbound/getCompleteInboundOrderLoading',
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
        pendingInboundsDataLists() {
            let inboundLists = []

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (this.search !== '' && this.currentTab === 0 && this.getSearchedInbounds.tab === 'pending') {
                    inboundLists = this.getSearchedInbounds
                } else if (this.selectedWhCustomers.length > 0 && this.currentTab === 0 && 
                    this.getfilteredInbounds.tab === 'pending') {
                    inboundLists = this.getfilteredInbounds
                } else {
                    if (typeof this.getPendingInboundPagination !== 'undefined' && 
                        this.getPendingInboundPagination !== null) {
                        inboundLists = this.getPendingInboundPagination
                    }
                }
            }           

            return inboundLists
        },
        floorInboundsDataLists() {
            let inboundLists = []

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (this.search !== '' && this.currentTab === 1 && this.getSearchedInbounds.tab === 'floor') {
                    inboundLists = this.getSearchedInbounds
                }
                else if(this.selectedWhCustomers.length > 0 && this.currentTab === 1 && 
                    this.getfilteredInbounds.tab === 'floor') {
					inboundLists = this.getfilteredInbounds
				} else {
                    if (typeof this.getFloorInboundPagination !== 'undefined' && 
                        this.getFloorInboundPagination !== null) {
                        inboundLists = this.getFloorInboundPagination
                    }
                }
            }

            return inboundLists
        },
        completedInboundsDataLists() {
            let inboundLists = []

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (this.search !== '' && this.currentTab === 2 && this.getSearchedInbounds.tab === 'completed') {
                    inboundLists = this.getSearchedInbounds
                } else if (this.selectedWhCustomers.length > 0 && this.currentTab === 2 && 
                    this.getfilteredInbounds.tab === 'completed') {
					inboundLists = this.getfilteredInbounds
				} else {
                    if (typeof this.getCompletedInboundPagination !== 'undefined' && 
                        this.getCompletedInboundPagination !== null) {
                        inboundLists = this.getCompletedInboundPagination
                    }
                }
            }

            return inboundLists
        },
        cancelledInboundsDataLists() {
            let inboundLists = []

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (this.search !== '' && this.currentTab === 3 && this.getSearchedInbounds.tab === 'cancelled') {
                    inboundLists = this.getSearchedInbounds
                } else if (this.selectedWhCustomers.length > 0 && this.currentTab === 3 && 
                    this.getfilteredInbounds.tab === 'cancelled') {
					inboundLists = this.getfilteredInbounds
				} else {
                    if (typeof this.getCancelledInboundPagination !== 'undefined' && 
                        this.getCancelledInboundPagination !== null) {
                        inboundLists = this.getCancelledInboundPagination
                    }
                }
            }

            return inboundLists
        },
		currentWarehouseInbounds() {
            let inboundsData = {}

            if (this.currentTab === 0) {
                inboundsData = this.pendingInboundsDataLists
            } else if (this.currentTab === 1) {
                inboundsData = this.floorInboundsDataLists
            } else if (this.currentTab === 2) {
                inboundsData = this.completedInboundsDataLists
            } else {
                inboundsData = this.cancelledInboundsDataLists
            }
            
            return inboundsData
        },
        currentWarehouseLoading() {
            if (this.currentTab === 0) {
                return this.getPendingInboundsLoading
            } else if (this.currentTab === 1) {
                return this.getFloorInboundsLoading
            } else if (this.currentTab === 2) {
                return this.getCompletedInboundsLoading
            } else {
                return this.getCancelledInboundsLoading
            }
        },
        getTotalPage: {
            get() {
                let total = 1
                let inboundsData = this.currentWarehouseInbounds

                if (typeof inboundsData.last_page !== 'undefined' && inboundsData.last_page !== null) {
                    total = inboundsData.last_page
                }

                return total
            }
        },
        getCurrentPage: {
            get() {
                let current_page = 1
                let inboundsData = this.currentWarehouseInbounds

                if (typeof inboundsData.current_page !== 'undefined' && inboundsData.current_page !== null) {
                    current_page = inboundsData.current_page
                }

                return current_page
            },
            set() {
                return {}
            }
        },
        getCurrentLoadingToDisplay() {
            if (this.search === '' && this.selectedWhCustomers.length === 0) {
                return this.getHandlePageLoading
            }else if(this.selectedWhCustomers.length > 0) {
                return this.getfilteredInboundsLoading
            } else {
                return this.getSearchedInboundsLoading
            }
        },
        getHandlePageLoading() {
            if (this.currentTab === 0) {
                // return this.fetchPendingInboundsLoading
                return this.pendingInboundLoadingPage
            } else if (this.currentTab === 1) {
                return this.floorInboundLoadingPage
            } else if (this.currentTab === 2) {
                return this.completedInboundLoadingPage
            } else {
                return this.cancelledInboundLoadingPage
            }
        },
        getSearchedDataClass() {
            if (this.currentWarehouseInbounds.data.length == 0 && this.search !== '') {
                return true
            } else {
                return false
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.currentWarehouseInbounds.data.length === 0 && this.selectedWhCustomers.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentWarehouseInbounds.data.length === 0) {
                isShow = true
            } else if (this.selectedWhCustomers.length > 0 && this.currentWarehouseInbounds.data.length === 0) {
                isShow = false
            }

            return isShow
        },
        handleFilterComponent() {
            let isShow = true

            if (this.search == '' && this.selectedWhCustomers.length === 0 && this.currentWarehouseInbounds.data.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentWarehouseInbounds.data.length === 0) {
                isShow = true
            } else if (this.search !== '' || this.currentWarehouseInbounds.data.length > 0) {
				isShow = true
			}
            return isShow
        },
        headersComputed() {
            let defaultHeaders = this.headersDefault

            if (this.isWarehouse3PL) {
                defaultHeaders = defaultHeaders.filter(v => {                    
                    if (this.currentTab === 0) {
                        return v.text !== 'Created By & At' && v.text !== 'Truck' && v.text !== 'Customer' && v.text !== 'Updated At' && v.text !== 'Status'
                    } else if (this.currentTab === 2) {
                        return v.text !== 'Created By & At' && v.text !== 'Truck' && v.text !== 'Customer' && v.text !== 'ETA/Arrival Time' && v.text !== 'Alert'
                    } else if (this.currentTab === 3) {
                        return v.text !== 'Created By & At' && v.text !== 'Truck' && v.text !== 'Customer' && v.text !== 'ETA/Arrival Time' && v.text !== 'Status' && v.text !== 'Alert'
                    }
                })
            } else if (this.isWarehouse3PLProvider) {
                defaultHeaders = defaultHeaders.filter(v => {
                    if (v.text == 'Created By & At') {
                        v.width = '18%'
                    }
                    return v.text !== 'Created At' && v.text !== 'Updated At' && v.text !== 'Alert'
                })
            } else {
                defaultHeaders = defaultHeaders.filter(v => {
                    return v.text !== 'Created At' && v.text !== 'Created By & At' && v.text !== 'Customer' && v.text !== 'Updated At' && v.text !== 'Alert'
                })
            }

            return defaultHeaders
        },
    },
    methods: {
        ...mapActions({
            cancelInbound: 'inbound/cancelInbound',            
            fetchPendingInbounds: 'inbound/fetchPendingInbounds',
            fetchFloorInbounds: 'inbound/fetchFloorInbounds',
            fetchCompletedInbounds: 'inbound/fetchCompletedInbounds',
            fetchCancelledInbounds: 'inbound/fetchCancelledInbounds',
            setCurrentInboundTab: 'inbound/setCurrentInboundTab',
            printInboundOrder: 'inbound/printInboundOrder',
            setInboundSearchedVal: 'inbound/setInboundSearchedVal',
            setSearchedInboundLoading: 'inbound/setSearchedInboundLoading',
            fetchSearchedInbounds: 'inbound/fetchSearchedInbounds',
            setSingleInboundData: 'inbound/setSingleInboundData',
            fetchInboundProducts: 'inbound/fetchInboundProducts',
            setAllInboundProductsLists: 'inbound/setAllInboundProductsLists',
            fetchSuppliers: "suppliers/fetchSuppliers",
            setIsCreateInboundShow: 'inbound/setIsCreateInboundShow',
            fetchCategoriesDropdown: 'category/fetchCategoriesDropdown',
            setAllCategoryDropdownLists: 'productInventories/setAllCategoryDropdownLists',
            fetchWarehouseCustomerInboundProducts: 'inbound/fetchWarehouseCustomerInboundProducts',
            setProductEmptyData: 'productInventories/setProductEmptyData',
            fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
            setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
            setSelectedPrevCustomerWarehouseID: 'inbound/setSelectedPrevCustomerWarehouseID',
            // filter and search 
            setInboundFilteredVal:'inbound/setInboundFilteredVal',
            setFilteredInboundLoading:'inbound/setFilteredInboundLoading',
            fetchFilterInboundsCustomers:'inbound/fetchFilterInboundsCustomers',
            completeInboundOrder: 'inbound/completeInboundOrder',
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        onTabChange(i) {
            this.currentTab = i
            this.setInboundSearchedVal([])
            this.setInboundFilteredVal([])
            this.selectedWhCustomers = []
            this.selectedWhCustomersCopy = []
            this.selectedWhCustomerReAssignValue =[]
            this.search = ''
        },
        formatNewDate(date, time) {
            return this.formatDateWithTimeSeparated(date, time, 'date-first')
		},
        formatDateDefault(data) {
            return this.formatDateWithTimeAbbr(data)
        },
        getTabCount(index) {
            let data = '0'

            if (index === 0) {
                data = this.pendingInboundsDataLists.total
            } else if (index === 1) {
                data = this.floorInboundsDataLists.total
            }
          
            let finalData = data !== 0 ? data : '0'
            return finalData
        },
        async onClickTab(i) {
            this.setInboundSearchedVal([])
            this.setInboundFilteredVal([])
            this.selectedWhCustomers = []
            this.selectedWhCustomersCopy = []
            this.selectedWhCustomerReAssignValue = []
            this.search = ''

            try {
                let storeInboundTab = this.$store.state.inbound
                let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1 }
                
                if (i === 0 && this.currentTab !== i) {
                    dataWithPage.page = storeInboundTab.pendingInboundPagination.current_page
                    await this.fetchPendingInbounds(dataWithPage)
                    storeInboundTab.pendingInboundPagination.old_page = storeInboundTab.pendingInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                } else if (i === 1 && this.currentTab !== i) {
                    dataWithPage.page = storeInboundTab.floorInboundPagination.current_page
                    await this.fetchFloorInbounds(dataWithPage)
                    storeInboundTab.floorInboundPagination.old_page = storeInboundTab.floorInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                } else if (i === 2 && this.currentTab !== i) {
                    dataWithPage.page = storeInboundTab.completedInboundPagination.current_page
                    await this.fetchCompletedInbounds(dataWithPage)
                    storeInboundTab.completedInboundPagination.old_page = storeInboundTab.completedInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                } else if (i === 3 && this.currentTab !== i) {
                    dataWithPage.page = storeInboundTab.cancelledInboundPagination.current_page
                    await this.fetchCancelledInbounds(dataWithPage)
                    storeInboundTab.cancelledInboundPagination.old_page = storeInboundTab.cancelledInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                }
            } catch(e) {
                this.notificationError(e)
            }
        },
        getOvershippedInbound(inbound) {
            return this.isDataOvershipped(inbound)
        },
        // inbounds
        async addNewInbound() {
            this.dialogCreate = true
            this.$nextTick(() => {
                this.editedInboundItems = Object.assign({}, this.defaultInboundItems)
                this.editedInboundIndex = -1;
            })

            this.setIsCreateInboundShow(false)
            if (this.isWarehouse3PLProvider) {
                await this.callInboundProductsFor3PL('Provider')
            } else {
                await this.callInboundProductsFor3PL('Inbound')
            }
        },
        closeCreate() {
            this.dialogCreate = false
            this.$nextTick(() => {
                this.editedInboundItems = Object.assign({}, this.defaultInboundItems)
                this.editedInboundIndex = -1;
            })

            if (this.currentEditInboundData !== null) {
                this.closeWarning()
            }
            this.setIsCreateInboundShow(false)
        },
        viewInbound(e) {
            this.$router.push(`inventory/inbound-view?inboundid=${e.id}&warehouseid=${this.currentWarehouseSelected.id}`)
        },
        checkEditInbound(item, toDo) {
            if (!this.isWarehouse3PL) {
                this.editOrder(item, toDo)
            } else {
                if (this.currentTab === 2) {
                    this.showWarningEditInboundDialog = true
                    let data = { item, toDo }
                    this.currentEditInboundData = data
                } else {
                    this.editOrder(item, toDo)
                }
            }
        },
        confirmEditOrder() {
            if (this.currentEditInboundData !== null) {
                if (this.currentEditInboundData.item.is_from_inventory === 0) {
                    this.editOrder(this.currentEditInboundData.item, this.currentEditInboundData.toDo)
                } else {
                    this.editAddInventoryInbound(this.currentEditInboundData.item)
                }                
            }
        },
        closeWarning() {
            this.showWarningEditInboundDialog = false
            this.currentEditInboundData = null
        },
        editAddInventoryInbound(item) {
            this.editedProductIndex = 0
            let inventoryItems = { ...item }

            if (!inventoryItems.inbound_products || inventoryItems.inbound_products.length === 0) {
                let buildProduct = [
                    {
                        product_id: '',
                        quantity: '',
                        shipping_unit: '',
                        status: ''
                    }
                ]
                inventoryItems = { ...inventoryItems, inbound_products: buildProduct }
            } else {
                let buildProduct = inventoryItems.inbound_products.map(v => {
                    let { id, product_id, expected_carton_count, shipping_unit, expected_total_unit, actual_carton_count, actual_total_unit } = v

                    if (shipping_unit === 'carton') {
                        return { 
                            inbound_product_id: id,
                            product_id,
                            quantity: expected_carton_count !== null ? parseInt(expected_carton_count) : '',
                            shipping_unit,
                            status: v.status,
                            expected_carton_count: expected_carton_count !== null ? parseInt(expected_carton_count) : '',
                            actual_carton_count: actual_carton_count !== null ? parseInt(actual_carton_count) : ''
                        }
                    } else {
                        return { 
                            inbound_product_id: id,
                            product_id,
                            quantity: expected_total_unit !== null ? parseInt(expected_total_unit) : '',
                            shipping_unit,
                            status: v.status,
                            expected_total_unit: expected_total_unit !== null ? parseInt(expected_total_unit) : '',
                            actual_total_unit: actual_total_unit !== null ? parseInt(actual_total_unit) : ''
                        }
                    }
                })
                inventoryItems = { ...inventoryItems, inbound_products: buildProduct }
            }

            inventoryItems.inventory_as_of = item.estimated_date
            inventoryItems.notes = item.notes

            this.editedProductItems = Object.assign({}, inventoryItems)
            this.dialogEditFromInventory = true
            this.callInboundProductsFor3PL('Inbound')
        },
        closeEditInventory() {
            this.dialogEditFromInventory = false
            this.$nextTick(() => {
				this.editedProductItems = Object.assign({}, this.defaultProductItems)
				this.editedProductIndex = -1
            })

            if (this.currentEditInboundData !== null) {
                this.closeWarning()
            }
        },
        async editOrder(item, toDo) {
            this.editedInboundIndex = this.currentWarehouseInbounds.data.indexOf(item)
            let inboundItem = { ...item }

            if (!inboundItem.inbound_products || inboundItem.inbound_products.length === 0) {
                let buildProduct = [{
                    product_id: '',
                    quantity: '',
                    shipping_unit: '',
                    status: ''
                }]
                inboundItem = { ...inboundItem, inbound_products: buildProduct }
            } else {
                if (inboundItem.inbound_products.length !== 0) {
                    let buildProduct = inboundItem.inbound_products.map(v => {
                        let { id, product_id, expected_carton_count, shipping_unit, expected_total_unit, actual_carton_count, actual_total_unit } = v

                        if (shipping_unit === 'carton') {
                            return { 
                                inbound_product_id: id,
                                product_id,
                                quantity: expected_carton_count !== null ? parseInt(expected_carton_count) : '',
                                shipping_unit,
                                status: v.status,
                                expected_carton_count: expected_carton_count !== null ? parseInt(expected_carton_count) : '',
                                actual_carton_count: actual_carton_count !== null ? parseInt(actual_carton_count) : ''
                            }
                        } else {
                            return { 
                                inbound_product_id: id,
                                product_id,
                                quantity: expected_total_unit !== null ? parseInt(expected_total_unit) : '',
                                shipping_unit,
                                status: v.status,
                                expected_total_unit: expected_total_unit !== null ? parseInt(expected_total_unit) : '',
                                actual_total_unit: actual_total_unit !== null ? parseInt(actual_total_unit) : ''
                            }
                        }
                    })
                    inboundItem = { ...inboundItem, inbound_products: buildProduct }
                }
            }

            if (!inboundItem.documents || inboundItem.documents == null) {
                inboundItem = { ...inboundItem, documents: [] }
            } else {
                let convertDocuments = inboundItem.documents.map(v => {
                    if (v.original_name !== 'undefined') {
                        return new File([v.original_name], `${v.original_name}`, {
                            type: v.type
                        });
                    }
                })
                inboundItem = { ...inboundItem, documents: convertDocuments }
            }

            if (!inboundItem.estimated_time || inboundItem.estimated_time == 'null') {
                inboundItem = { ...inboundItem, estimated_time: null }
            }

            if (!inboundItem.estimated_date || inboundItem.estimated_date == 'null') {
                inboundItem = { ...inboundItem, estimated_date: null }
            }

            if (!inboundItem.ref_no || inboundItem.ref_no == 'null') {
                inboundItem = { ...inboundItem, ref_no: null }
            }

            if (!inboundItem.shipping_from || inboundItem.shipping_from == 'null') {
                inboundItem = { ...inboundItem, shipping_from: null }
            }

            if (!inboundItem.name || inboundItem.name == 'null') {
                inboundItem = { ...inboundItem, name: null }
            }

            if (!inboundItem.notes || inboundItem.notes == 'null') {
                inboundItem = { ...inboundItem, notes: null }
            }

            inboundItem.isDuplicate = toDo == 'edit' ? false : true
            this.editedInboundIndex = inboundItem.isDuplicate ? -1 : this.editedInboundIndex
            // if is duplicate, set order id to empty
            inboundItem.order_id = inboundItem.isDuplicate ? '' : inboundItem.order_id 

            this.editedInboundItems = Object.assign({}, inboundItem)
            this.dialogCreate = true

            if (this.isWarehouse3PLProvider) {
                await this.callWarehouseCustomerProducts(inboundItem)
            } else {
                await this.callInboundProductsFor3PL('Inbound')
            }
        },     
        arrived(item) {
            this.dialogTruckArrived = true
            this.linkData = {
                inbound_id: item.id,
                warehouse_id: item.warehouse_id
            }
        },
        closeTruckArrivedDialog() {
            this.dialogTruckArrived = false
            this.truckArrivedData = { name: '', ref_no: '' }
            this.linkData = { inbound_id: '', warehouse_id: '' }
        },
        async printOrder(item) {
            if (item !== null) {
                let payload = {
                    warehouse_id: item.warehouse_id,
                    inbound_id: item.id,
                    order_id: item.order_id
                }

                try {
                    this.notificationCustom('Generating print order...')
                    await this.printInboundOrder(payload)
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        createWordOrder() {},        
        openNewStorableUnit(item) {
            if (item !== 'undefined' && item.inbound_products !== 'undefined') {
                if (item.inbound_status === 'floor') {
                    let findIndex = _.findIndex(item.inbound_products, (e) => e.status !== 'recieved')

                    if (findIndex === -1) {
                        this.newStorableData = item
                        this.dialogNewStorableUnit = true
                        this.linkData = {
                            inbound_id: item.id,
                            warehouse_id: item.warehouse_id
                        }
                    } else {
                        this.notificationError('You can add New Storable Unit only if all Inbound Products have been received.')
                    }
                } else {
                    this.notificationError("Something's wrong. Please reload the page and try again.")
                }
            }
        },
        closeStorableUnit() {
            this.newStorableData = null
            this.dialogNewStorableUnit = false
        },
        reportToVendor(item) {
            console.log(item);
        },
        cancelOrder(inbound) {
            if (inbound !== null) {
                this.cancelOrderData = inbound
                this.dialogCancel = true
            }
        },
        async cancelConfirm() {
            if (this.cancelOrderData !== null) {
                try {
                    let payload = {
                        wid: this.cancelOrderData.warehouse_id,
                        oid: this.cancelOrderData.id,
                        page: 1
                    }

                    let storePagination = this.currentTab === 0 ? 
                        this.$store.state.inbound.pendingInboundPagination : 
                        (this.currentTab === 1 ? this.$store.state.inbound.floorInboundPagination : 1)

                    let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
                    
                    if (storePagination.data.length === 1 && storePagination.current_page !== 1) {
                        page = page - 1
                    } 

                    payload.page = page

                    await this.cancelInbound(payload)
                    this.dialogCancel = false
                    this.notificationCustom('Inbound has been cancelled.')

                    let dataWithPage = { id: this.cancelOrderData.warehouse_id, page }

                    if (this.currentTab === 0) {
                        await this.fetchPendingInbounds(dataWithPage)
                    } else if (this.currentTab === 1) {
                        await this.fetchFloorInbounds(dataWithPage)
                    }

                    let cancelledData = { id: this.cancelOrderData.warehouse_id, page: 1 }
                    await this.fetchCancelledInbounds(cancelledData)

                    this.cancelOrderData = null
                    this.closeCancel()
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        closeCancel() {
            this.dialogCancel = false
        },
        // paginations
        async handlePageChange(page) {
            this.handleScrollToTop()
            if (page !== null) {
                let storeInboundTab = this.$store.state.inbound
                let dataWithPage = { id: this.currentWarehouseSelected.id, page }

                if (this.search == '' && this.selectedWhCustomers.length === 0) {
                    try {
                        if (storeInboundTab.setCurrentTab === 0) {
                            if (storeInboundTab.pendingInboundPagination.old_page !== page) {
                                this.pendingInboundLoadingPage = true
                                await this.fetchPendingInbounds(dataWithPage)
                                storeInboundTab.pendingInboundPagination.old_page = page
                                this.pendingInboundLoadingPage = false
                            }
                        } else if (storeInboundTab.setCurrentTab === 1) {
                            if (storeInboundTab.floorInboundPagination.old_page !== page) {
                                this.floorInboundLoadingPage = true
                                await this.fetchFloorInbounds(dataWithPage)
                                storeInboundTab.floorInboundPagination.old_page = page
                                this.floorInboundLoadingPage = false
                            }
                        } else if (storeInboundTab.setCurrentTab === 2) {
                            if (storeInboundTab.completedInboundPagination.old_page !== page) {
                                this.completedInboundLoadingPage = true
                                await this.fetchCompletedInbounds(dataWithPage)
                                storeInboundTab.completedInboundPagination.old_page = page
                                this.completedInboundLoadingPage = false
                            }
                        } else {
                            if (storeInboundTab.cancelledInboundPagination.old_page !== page) {
                                this.cancelledInboundLoadingPage = true
                                await this.fetchCancelledInbounds(dataWithPage)
                                storeInboundTab.cancelledInboundPagination.old_page = page
                                this.cancelledInboundLoadingPage = false
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
						
						this.handleFilterWithSearchFunction(data)
					} else {
						if (this.search === '' && this.selectedWhCustomers.length > 0) {
							let	data = {
                                filter_data: this.selectedWhCustomers.map(val => val.id),
                                search_data: this.search,
                                wid: dataWithPage.id,
                                page
                            }
						    this.handleFilterOnlyFunction(data)
						}
					}
                }
            }
        },
        async handlePageSearched(data) {
            this.handleScrollToTop()
            let searchedPagination = this.$store.state.inbound.searchedInbounds
            if (data !== null && this.search !== '') {
                if (searchedPagination.old_page !== data.page) {
                    let passedData = {
                        method: "get",
                        url: '',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: { search: this.search, page: data.page }
                    }

                    let warehouse_id = this.currentWarehouseSelected.id
                    if (this.currentTab == 0) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/pending`
                        passedData.tab = 'pending'
                    } else if (this.currentTab == 1) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/floor`
                        passedData.tab = 'floor'
                    } else if (this.currentTab == 2) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/completed`
                        passedData.tab = 'completed'
                    } else {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/cancelled`
                        passedData.tab = 'cancelled'
                    }

                    if (passedData.url !== '') {
                        try {
                            this.fetchSearchedInbounds(passedData)
                        } catch(e) {
                            this.notificationError(e)
                            this.setSearchedInboundLoading(false)
                            console.log(e, 'Search error')
                        }
                    }
                }                
            } else {
                this.setInboundSearchedVal([])
            }
        },
        async handleFilterWithSearchFunction(data) {
			let filterPagination = this.$store.state.inbound
			if (data !== null && data.filter_data !== null && this.search !== '' && this.selectedWhCustomers.length > 0) {
                if (filterPagination.filteredInbounds.old_page !== data.page) {
                    this.setFilteredInboundLoading(true)
					var searchParams = new URLSearchParams();

					for (var ser = 0; ser < data.filter_data.length; ser++) {
					    searchParams.append(`ids[]`, data.filter_data[ser])
					}
					searchParams.append('page', data.page)

                	let passedData = {
                        method: "get",
                        url: '',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: searchParams
               		}

					if (this.currentTab == 0) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/inbound/pending?search=${data.search_data}&filter=true`, {params: searchParams}
                        passedData.tab = 'pending'
                	} else if (this.currentTab == 1) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/inbound/floor?search=${data.search_data}&filter=true`, {params: searchParams}
                        passedData.tab = 'floor'
                	} else if (this.currentTab == 2) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/inbound/completed?search=${data.search_data}&filter=true`, {params: searchParams}
                        passedData.tab = 'completed'
                	} else {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/inbound/cancelled?search=${data.search_data}&filter=true`, {params: searchParams}
                        passedData.tab = 'cancelled'
                	}

                	if (passedData.url !== '') {
                        try {
                            await this.fetchFilterInboundsCustomers(passedData)
                            filterPagination.filteredInbounds.old_page = data.page
                        } catch(e) {
                            this.notificationError(e)
                            this.setFilteredInboundLoading(false)
                            console.log(e, 'filter with search error')
                        }
                	}
                } else {
					this.setInboundFilteredVal([])
				}               
            } 
		},
		async handleFilterOnlyFunction(data) {
			let filterPagination = this.$store.state.inbound
			if (data !== null && data.filter_data !== null &&  this.search === '' && this.selectedWhCustomers.length > 0) {
                if (filterPagination.filteredInbounds.old_page !== data.page) {
                    this.setFilteredInboundLoading(true)
					var searchParams = new URLSearchParams();

					for (var ser = 0; ser < data.filter_data.length; ser++) {
					    searchParams.append(`ids[]`, data.filter_data[ser])
					}
					searchParams.append('page', data.page)

                	let passedData = {
                        method: "get",
                        url: '',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: searchParams
               		}

					if (this.currentTab == 0) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/inbound/pending?filter=true`, {params: searchParams}
                        passedData.tab = 'pending'
                	} else if (this.currentTab == 1) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/inbound/floor?filter=true`, {params: searchParams}
                        passedData.tab = 'floor'
                	} else if (this.currentTab == 2) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/inbound/completed?filter=true`, {params: searchParams}
                        passedData.tab = 'completed'
                	} else {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${data.wid}/inbound/cancelled?filter=true`, {params: searchParams}
                        passedData.tab = 'cancelled'
                	}

                	if (passedData.url !== '') {
                        try {
                            await  this.fetchFilterInboundsCustomers(passedData)
                            filterPagination.filteredInbounds.old_page = data.page
                        } catch(e) {
                            this.notificationError(e)
                            this.setFilteredInboundLoading(false)
                            console.log(e, 'handle page error')
                        }
                	}
                } else {
					this.setInboundFilteredVal([])
				}               
            } 
		},
        clearSearched() {
            this.search = ''
            this.setInboundSearchedVal([])
            if(this.isWarehouse3PLProvider) {
				if(this.selectedWhCustomers.length > 0) {
					this.setInboundFilteredVal([])
					this.filterAllWarehouseCustomers()
				}
			}
        },
        // for searching call api
        handleSearch() {
            if (cancel !== undefined) {
                cancel()
            }
            this.setSearchedInboundLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = {  search: this.search }
                if (this.selectedWhCustomers.length > 0) {
                    return this.filterAllWarehouseCustomers()
                } else {
                    this.setSearchedInboundLoading(true)
                    this.apiCall(data)
                }
            }, 500)
        },
        apiCall(data) {
            let storePagination = this.$store.state.inbound.setCurrentTab
            if (data !== null && this.search !== '') {
                let warehouse_id = this.currentWarehouseSelected.id
                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: { search: this.search, page: 1 }
                }

                if (storePagination == 0) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/pending`
                    passedData.tab = 'pending'
                } else if (storePagination == 1) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/floor`
                    passedData.tab = 'floor'
                } else if (storePagination == 2) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/completed`
                    passedData.tab = 'completed'
                } else {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/cancelled`
                    passedData.tab = 'cancelled'
                }

                if (passedData.url !== '') {
                    try {
                        this.fetchSearchedInbounds(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedInboundLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.setInboundSearchedVal([])
            }
        },
        async callInboundProductsFor3PL(from) {
            if (from === 'Provider') {                
                this.setAllInboundProductsLists([])
            }

            try {
                if (this.getAllInboundProductsListsDropdownData.length === 0) {
                    this.current_page_is = 1
                    await this.fetchInboundProducts(1)
                    
                    if (typeof this.getAllInboundProductsLists !== 'undefined' && 
                        this.getAllInboundProductsLists !== null && 
                        typeof this.getAllInboundProductsLists.products !== 'undefined' && 
                        Array.isArray(this.getAllInboundProductsLists.products) &&
                        this.getAllInboundProductsLists.products.length > 0) {                            
                        this.productsListsData = this.getAllInboundProductsLists.products.map(value => {
                            return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
                                category_id: value.category_id,
                                units_per_carton: value.units_per_carton,
                                category_sku: value.category_sku
                            }
                        })

                        this.lastDataCheck = this.productsListsData
                        if (this.current_page_is < this.getAllInboundProductsLists.last_page) {
                            this.loadMoreProducts()
                        }                        
                        this.setAllInboundProductsLists(this.productsListsData)
                        this.fetchProductLoading = false
                    } else {
                        this.fetchProductLoading = false
                        this.productsListsData = []
                        this.lastDataCheck = []
                    }
                } else {
                    if (from === 'Inbound') {
                        this.productsListsData = this.getAllInboundProductsListsDropdownData
                        this.fetchProductLoading = false
                    } else {
                        let last_page = this.getAllInboundProductsLists.last_page

                        if (typeof last_page !== 'undefined') {
                            this.current_page_is = last_page
                            await this.fetchInboundProducts(last_page)

                            if (typeof this.getAllInboundProductsLists !== 'undefined' && 
                                this.getAllInboundProductsLists !== null && 
                                typeof this.getAllInboundProductsLists.products !== 'undefined' && 
                                Array.isArray(this.getAllInboundProductsLists.products) &&
                                this.getAllInboundProductsLists.products.length > 0) {
                                    
                                let newloaddata = this.getAllInboundProductsLists.products.map(value => {
                                    return {
                                        product_id: value.id,
                                        id: value.id,
                                        name: value.name,
                                        sku: value.sku,
                                        image: value.image,
                                        description: value.description,
                                        category_id: value.category_id,
                                        units_per_carton: value.units_per_carton,
                                        category_sku: value.category_sku
                                    }
                                })

                                this.productsListsData = [...this.productsListsData, ...newloaddata]
                                if (this.current_page_is < this.getAllInboundProductsLists.last_page) {
                                    this.loadMoreProducts()
                                }                                
                                this.setAllInboundProductsLists(this.productsListsData)
                                this.fetchProductLoading = false
                            } else {
                                this.fetchProductLoading = false
                                this.productsListsData = []
                                this.lastDataCheckProducts = []
                            }
                        }
                    }                    
                }
            } catch(e) {
                this.notificationError(e)
            }
        },
        async loadMoreProducts() {
            if (this.current_page_is < this.getAllInboundProductsLists.last_page) {
				this.current_page_is++

				try {
					await this.fetchInboundProducts(this.current_page_is)

                    if (typeof this.getAllInboundProductsLists !== 'undefined' && this.getAllInboundProductsLists !== null && 
                        typeof this.getAllInboundProductsLists.products !== 'undefined' && Array.isArray(this.getAllInboundProductsLists.products) &&
                        this.getAllInboundProductsLists.products.length > 0) {

                        let newloaddata = this.getAllInboundProductsLists.products.map(value => {
                            return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
                                category_id: value.category_id,
                                units_per_carton: value.units_per_carton,
                                category_sku: value.category_sku
                            }
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]
                        if (this.current_page_is < this.getAllInboundProductsLists.last_page) {
                            this.loadMoreProducts()
                        }
                        this.setAllInboundProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        handleScrollToTop() {
            this.scrollTableToTop()
        },
        canStillEditInbound() {
            if (!this.isWarehouse3PL) {
                if (this.currentTab === 0 || this.currentTab === 1) {
                    return true
                } else {
                    return false
                }      
            } else {
                if (this.currentTab === 3) {
                    return false
                } else {
                    return true
                }
            }                  
        },
        receiveAllProductsInbound(item) {
            this.linkData = { inbound_id: item.id, warehouse_id: item.warehouse_id }
            this.selectedInboundStatus = item.inbound_status
            this.isSelectedUndershipped = item.is_undershipped
            
            if (item.inbound_products.length !== 'undefined' && item.inbound_products.length > 0) {
                item.inbound_products.map(prod => {
                    let { id, product_id, product, expected_carton_count, expected_total_unit, in_each_carton, shipping_unit, actual_carton_count, actual_total_unit, storable_units, remaining_carton_count, remaining_total_unit, notes } = prod

                    let findProduct = _.findIndex(item.inbound_products, e => (id === e.id && e.status !== 'recieved'))
                    if (findProduct !== -1) {
                        this.productSelectedToReceive.push({
                            id,
                            product_id,
                            name: product.name,
                            carton_count: shipping_unit === 'single_item' ? null : parseInt(expected_carton_count),
                            total_unit: expected_total_unit,
                            in_each_carton: in_each_carton,
                            product_sku: product.sku,
                            shipping_unit,
                            expected_carton_count,
                            expected_total_unit,
                            actual_carton_count,
                            actual_total_unit,
                            storable_units,
                            remaining_carton_count,
                            remaining_total_unit,
                            notes: notes !== null ? notes : '',
                            noteError: false
                        })
                    }                    
                })
            }
            
            this.dialogReceiveProduct = true
        },
        findFloorReceivedProducts(item) {
            if (this.currentTab === 1) {
                if (typeof item !== 'undefined' && item !== null) {
                    if (typeof item.inbound_products !== 'undefined' && item.inbound_products !== undefined) {
                        if (item.inbound_status === 'floor') {
                            let findIndex = _.findIndex(item.inbound_products, (e) => e.status !== 'recieved')

                            if (findIndex === -1) {
                                return true
                            } else {
                                return false
                            }
                        } else {
                            return false
                        }
                    }
                }
            }
        },
        isHasStorableUnits(item) {
            let hasStorable = false

            if (this.currentTab === 1) {
                if (typeof item !== 'undefined' && item !== null) {
                    if (typeof item.no_of_storable_units !== 'undefined' && item.no_of_storable_units !== undefined) {
                        if (item.no_of_storable_units > 0) {
                            hasStorable = true
                        }
                    }
                }
            }

            return hasStorable
        },
        async closeProductReceive() {
            this.dialogReceiveProduct = false
            this.productSelectedToReceive = []
        },
        clearSelection() {
            this.productSelectedToReceive = []
        },
        isShowCancelButton(item) {
            let show = false
            if (!this.isWarehouse3PL) {
                if (item.inbound_status === 'pending' || item.inbound_status === 'arrived') {
                    show = true
                } else {
                    show = false
                }
            } else {
                if (item.inbound_status === 'pending') {                        
                    let productsLists = item.inbound_products
                    if (typeof productsLists !== 'undefined' && productsLists.length > 0) {
                        let findProduct = _.findIndex(productsLists, e => (e.status === 'recieved'))

                        if (findProduct > -1) {
                            show = false
                        } else {
                            show = true
                        }
                    }
                }
            }            
            return show
        },
        openAddProductDialog() {
            this.dialogEditProduct = true
            this.editedIndexProductCreate = -1
            this.callCategories()
        },
        closeProductDialogCreate() {
            this.$nextTick(() => {
				this.editedProductItemCreate = Object.assign({}, this.defaultProductItemCreate)
				this.editedIndexProductCreate = 0				
			})
			this.dialogEditProduct = false
        },
        setToDefault() {
            this.$nextTick(() => {
				this.editedProductItemCreate = Object.assign({}, this.defaultProductItemCreate)
				this.editedIndexProductCreate = -1
			})
            this.dialogEditProduct = true
        },
        async callCategories() {
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
                    if (this.current_page_is_category < this.getCategoriesDropdown.last_page) {
                        this.loadMoreCategories()
                    }
                    this.setAllCategoryDropdownLists(this.categoryListData)
                } else {
                    this.categoryListData = []
                    this.lastDataCheck = []
                }
            } else {
                this.categoryListData = this.getAllCategoryDropdownLists
                this.setAllCategoryDropdownLists(this.categoryListData)
            }
        },
        async loadMoreCategories() {
			if (this.current_page_is_category < this.getCategoriesDropdown.last_page) {
				this.current_page_is_category++

				try {
					await this.fetchCategoriesDropdown(this.current_page_is_category)
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
						if (this.current_page_is_category < this.getCategoriesDropdown.last_page) {
							this.loadMoreCategories()
						}
                        this.setAllCategoryDropdownLists(this.categoryListData)
					} else {
						this.categoryListData;
                        this.setAllCategoryDropdownLists(this.categoryListData)
					}
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        // call warehouse customer products
        async callWarehouseCustomerProducts(val) {
            this.productsListsData = []
            this.lastDataCheck = []

            if (val.warehouse_customer_id !== null) {
                try {                    
                    this.fetchProductLoading = true
                    let customer_id = (typeof this.getUser=='string') ? 
                        JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
                    let warehouse_customer_id = val.warehouse_customer_id
                    this.current_page_is_warehouse_products = 1

                    let payload = { customer_id, warehouse_customer_id, page: this.current_page_is_warehouse_products }
                    await this.fetchWarehouseCustomerInboundProducts(payload)
                    this.setSelectedPrevCustomerWarehouseID(warehouse_customer_id)
                    
                    if (typeof this.getAllWarehouseCustomerInboundProductsLists !== 'undefined' && 
                        this.getAllWarehouseCustomerInboundProductsLists !== null && 
                        typeof this.getAllWarehouseCustomerInboundProductsLists.products !== 'undefined' && 
                        Array.isArray(this.getAllWarehouseCustomerInboundProductsLists.products) &&
                        this.getAllWarehouseCustomerInboundProductsLists.products.length > 0) {
                            
                        this.productsListsData = this.getAllWarehouseCustomerInboundProductsLists.products.map(value => {
                            return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
                                category_id: value.category_id,
                                units_per_carton: value.units_per_carton,
                                category_sku: value.category_sku
                            }
                        })

                        this.lastDataCheck = this.productsListsData
                        if (this.current_page_is_warehouse_products < this.getAllWarehouseCustomerInboundProductsLists.last_page) {
                            this.loadMoreWarehouseCustomerProducts(val)
                        }                        
                        this.setAllInboundProductsLists(this.productsListsData)
                        this.fetchProductLoading = false
                    } else {
                        this.fetchProductLoading = false
                        this.productsListsData = []
                        this.lastDataCheck = []
                    }
                } catch(e) {
                    this.notificationError(e)
                    console.log(e)
                }
            } else {
                await this.callInboundProductsFor3PL('Provider')
            }
        },
        async loadMoreWarehouseCustomerProducts(val) {
            if (this.current_page_is_warehouse_products < this.getAllWarehouseCustomerInboundProductsLists.last_page) {
				this.current_page_is_warehouse_products++

                let customer_id = (typeof this.getUser=='string') ? 
                    JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
                let warehouse_customer_id = val.warehouse_customer_id

                let payload = { customer_id, warehouse_customer_id, page: this.current_page_is_warehouse_products }
				try {
					await this.fetchWarehouseCustomerInboundProducts(payload)
                    if (typeof this.getAllWarehouseCustomerInboundProductsLists !== 'undefined' && 
                        this.getAllWarehouseCustomerInboundProductsLists !== null && 
                        typeof this.getAllWarehouseCustomerInboundProductsLists.products !== 'undefined' && 
                        Array.isArray(this.getAllWarehouseCustomerInboundProductsLists.products) &&
                        this.getAllWarehouseCustomerInboundProductsLists.products.length > 0) {
                            
                        let newloaddata = this.getAllWarehouseCustomerInboundProductsLists.products.map(value => {
                            return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
                                category_id: value.category_id,
                                units_per_carton: value.units_per_carton,
                                category_sku: value.category_sku
                            }
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]
                        if (this.current_page_is_warehouse_products < this.getAllWarehouseCustomerInboundProductsLists.last_page) {
                            this.loadMoreWarehouseCustomerProducts()
                        }
                        this.setAllInboundProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        // call warehouse customers lists
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
				this.setInboundFilteredVal([])
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomers = []
                this.selectedWhCustomerReAssignValue = []
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
                this.selectedWhCustomerReAssignValue = []
                this.setInboundFilteredVal([])

                if (this.search !== '') {
                    setTimeout(() => {
                        this.filterMenu = false
                        let data = { search: this.search } 
                        this.setSearchedInboundLoading(true)
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
                this.selectedWhCustomerReAssignValue = []
                this.setInboundFilteredVal([])
            } else {
                // if (this.selectedWhCustomers !== this.selectedWhCustomersCopy) {
                //     this.selectedWhCustomersCopy = this.selectedWhCustomers
                // }
                this.selectedWhCustomersCopy =this.selectedWhCustomerReAssignValue.map(({dummy_value_Add_For_same_refrence,...rest}) => ({...rest}))
            }
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
            this.filterMenu = false
            this.isActiveClicked = false
        },
        /* eslint-enable */
        filterAllWarehouseCustomers() {
            this.setFilteredInboundLoading(false)
            if (this.selectedWhCustomersCopy.length > 0 && this.search === '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilteredCustomerOnly()
            } else if (this.selectedWhCustomersCopy.length > 0 && this.search !== '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilterAndSearchCustomer()
            }
            this.setInboundFilteredVal([])
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsData
            this.filterMenu = false
            this.isActiveClicked = false
        },
        async apiCallFilteredCustomerOnly() {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)

            if (this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0) {
                let storePagination = this.$store.state.inbound.setCurrentTab
                this.setFilteredInboundLoading(true)
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
                if (storePagination == 0) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/pending?filter=true`, {params: searchParams}
                    passedData.tab = 'pending'
                } else if (storePagination == 1) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/floor?filter=true`, {params: searchParams}
                    passedData.tab = 'floor'
                } else if (storePagination == 2) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/completed?filter=true`, {params: searchParams}
                    passedData.tab = 'completed'
                } else {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/cancelled?filter=true`, {params: searchParams}
                    passedData.tab = 'cancelled'
                }
                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterInboundsCustomers(passedData)                       
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredInboundLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            } else {
                this.setInboundFilteredVal([])
            }
        },
        async apiCallFilterAndSearchCustomer() {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)
            if(this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0 && this.search !=='') {
                let storePagination = this.$store.state.inbound.setCurrentTab
                this.setFilteredInboundLoading(true)
                
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

                if (storePagination == 0) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/pending?search=${this.search}&filter=true`, {params: searchParams}
                    passedData.tab = 'pending'
                } else if (storePagination == 1) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/floor?search=${this.search}&filter=true`, {params: searchParams}
                    passedData.tab = 'floor'
                } else if (storePagination == 2) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/completed?search=${this.search}&filter=true`, {params: searchParams}
                    passedData.tab = 'completed'
                } else {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/cancelled?search=${this.search}&filter=true`, {params: searchParams}
                    passedData.tab = 'cancelled'
                }

                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterInboundsCustomers(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredInboundLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            } else {
                this.setInboundFilteredVal([])
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
        completeOrder(item) {
            this.dialogConfirm = true
            this.toCompleteOrderData = item
        },
        async confirmCompleted() {
            if (typeof this.toCompleteOrderData !== 'undefined' && this.toCompleteOrderData !== null) {
                if (typeof this.toCompleteOrderData.id !== 'undefined' && this.toCompleteOrderData.warehouse_id !== null) {
                    let payload = {
                        id: this.toCompleteOrderData.warehouse_id,
                        inbound_id: this.toCompleteOrderData.id
                    }

                    await this.completeInboundOrder(payload)
                    this.notificationMessage('Inbound Order has been completed.')
                    this.dialogConfirm = false

                    let dataWithPage = { id: this.toCompleteOrderData.warehouse_id, page: 1 }
                    await this.fetchFloorInbounds(dataWithPage)
                    await this.fetchCompletedInbounds(dataWithPage)
                    this.closeCompletedDialog()
                }
            }
        },
        closeCompletedDialog() {
            this.dialogConfirm = false
            this.toCompleteOrderData = null    
        },
    },
    async mounted() {
        this.setSingleInboundData([])
        this.setInboundFilteredVal([])
        this.fetchProductLoading = true

        //set tab
        if (this.getCurrentInboundTab !== 'undefined') {
            if (this.currentTab !== this.getCurrentInboundTab) {
                this.currentTab = this.getCurrentInboundTab
            }
        }

        let checkWarehouseTypeId = this.currentWarehouseSelected !== null ? this.currentWarehouseSelected.warehouse_type_id : null
        let currentInventoryTabName = this.$router.history.current.query.tab

        if (this.$store.state.page.currentInventoryTab === 3 &&
            typeof currentInventoryTabName !== 'undefined' && 
            currentInventoryTabName === 'Inbound') {

            if (this.search === '' && this.currentWarehouseInbounds.data.length === 0) {
                try {
                    let dataWithPage = {
                        id: this.currentWarehouseSelected.id,
                        page: 1
                    }

                    if (checkWarehouseTypeId === 3 && this.getIsShowCreateInboundDialog) {
                        this.addNewInbound()
                        this.setIsCreateInboundShow(false)
                    }

                    await this.fetchPendingInbounds(dataWithPage)
                    this.fetchPendingInboundsLoading = false

                    if (checkWarehouseTypeId !== null && (checkWarehouseTypeId === 1 || checkWarehouseTypeId === 6)) {
                        await this.fetchFloorInbounds(dataWithPage)
                    } else if (checkWarehouseTypeId !== null && checkWarehouseTypeId !== 1) {
                        await this.fetchCompletedInbounds(dataWithPage)
                    }  
                } catch(e) {
                    this.notificationError(e)
                }
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
@import '@/assets/scss/pages_scss/inventory/inbound/inboundTable.scss';
</style>