<template>
    <div class="outbound-mobile">
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
            :headers="_header"
            :items="currentWarehouseOutbounds"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            item-key="order_id"
            mobile-breakpoint="769"
            class="inventory-table outbound-table elevation-1"
            :class="{'no-data-table' :(currentWarehouseOutbounds !== null && 
                    currentWarehouseOutbounds.length === 0),
                    'type-3pl' : currentWarehouseSelected.warehouse_type_id === 3,
                    'type-6pl' : currentWarehouseSelected.warehouse_type_id === 6}"
            hide-default-footer
            fixed-header
            show-select
            ref="my-table"
            @click:row="viewOutbound">

            <template v-slot:top>
                <div class="inventory-search-wrapper outbound-search">
                    <v-tabs class="inventory-inner-tab" @change="onTabChange" v-model="currentTab">
                        <v-tab v-for="(tab, index) in tabs" 
                            :key="index" 
                            @click="onClickTab(index)"
                            :class="conditionForFloorTab(tab) ? `${tab}` : `paddingZero ${tab}`">
                            <span v-if="conditionForFloorTab(tab)">{{ tab }}</span>	

                            <small v-if="conditionForTabCounts(index,tab)">{{ getTabCount(index) }}</small>
                        </v-tab>
                    </v-tabs>

                    <v-spacer></v-spacer>
                    
                    <div :class="isWarehouse6PL ==true ? '':'search-and-filter'" 
                       >
                       <div class="d-flex" :class="isWarehouse6PL ==true ? 'my-2':''">
                        <div v-if="(currentWarehouseOutbounds !== null && currentWarehouseOutbounds.length > 0)">
                             <!-- <Search 
                            placeholder="Search Outbound Orders"
                            className="search custom-search"
                            :inputData.sync="search" /> -->
                            
                            <SearchComponent
                            placeholder="Search Outbound Orders"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />
                        </div>
                        <v-btn color="primary" dark class="btn-blue ml-2" @click.stop="createOutbound">
                            Create Outbound
                        </v-btn>
                        </div>
                        
                        <!-- <v-select
                            v-if="isWarehouse6PL ==true && currentWarehouseOutbounds.length !== 0"
							v-model="SearchCustomerFinalArray"
							:items="warehouseCustomerLists"
							item-text="name"
							item-value="id"
							multiple
							class="customer-product"
							outlined
							hide-details
							dense 
							placeholder="Filter by Customer"
							deletable-chips
							chips
							:menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
							>
							<template v-slot:selection="{ item, index }">
								<span class="d-flex justify-end align-center">
								<span class="caption" style="color:#819FB2 !important" v-if="index === 0">Customer:</span>
								<v-chip small class="customer-filter"  v-if="index === 0">
								<span class="px-0 mx-0 subtitle-2" style=" color:#819FB2">{{getNameWithoutExt(item.name)}}</span> <v-icon class="pr-1" small right @click="removeCustomerFilter(item)">mdi-close</v-icon>
								</v-chip>
								<span v-if="index === 1" class="grey--text text-caption"
								>
								<v-chip small class="primary">+{{ SearchCustomerFinalArray.length - 1 }}</v-chip> 
								</span>
								</span>
							</template>
							<template v-slot:item="{item,attrs, on}">
								<v-list-item three-line class="pr-3 py-0 my-0 " v-on="on" v-bind="attrs" #default="{ active }">
										<v-list-item-content>	
											<v-list-item-title class=" py-0 checkbox-border-style">
											<v-checkbox color="#6D858F" :input-value="active"></v-checkbox><span style="color: #4A4A4A !important;">{{item.name}}</span>
											</v-list-item-title>
											<v-list-item-subtitle class="ml-4 pl-4 secondary-Color">{{item.address}}</v-list-item-subtitle>
										</v-list-item-content>
									</v-list-item>
                            </template>
							</v-select>    -->
                    </div>
                </div>
            </template>

            <template v-slot:[`item.order_id`]="{ item }">                
                <div class="inventory-outbound-wrapper">
                    <div class="inventory-outbound-items"> 
                        <p>ORDER ID</p>
                        <span class="order-id">#{{ typeof item.order_id !== 'undefined' && item.order_id !== null ? item.order_id : '--' }}</span>
                    
                        <div class="status">
                            <span :class="item.outbound_status">{{ item.outbound_status }}</span>
                        </div>
                    </div>

                    <div class="actions">
                        <v-menu bottom left content-class="outbound-lists-menu">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on">
                                    <v-icon>mdi-dots-horizontal</v-icon>
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists" v-if="currentTab == 0">
                                <v-list-item class="" v-if="isWarehouse3PL ==true && item.outbound_status =='pending'" @click="loadAllProducts3PL(item)">
								<v-list-item-title class="green--text">
									<img src="@/assets/icons/checkMark.png" height="16px" width="16px" class="mr-2">
									All Loaded </v-list-item-title>
							</v-list-item>
                                <v-list-item @click="viewOutbound(item)">
                                    <v-list-item-title>
                                        View
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="editOrder(item,'edit')">
                                    <v-list-item-title>
                                        Edit Order
                                    </v-list-item-title>
                                </v-list-item>

                                <!-- <v-list-item @click="arrangeTracking(item)">
                                    <v-list-item-title>
                                        Arrange Tracking
                                    </v-list-item-title>
                                </v-list-item> -->
                                <v-list-item @click="editOrder(item,'copy')">
                                    <v-list-item-title>
                                        Duplicate
                                    </v-list-item-title>
                                </v-list-item>
                                 <v-list-item @click="printOrder(item)">
                                    <v-list-item-title>
                                        Print Order
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item v-if="showCancelButton(item)" @click="cancel(item)">
                                    <v-list-item-title class="cancel">
                                        Cancel Order
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>

                            <v-list class="outbound-lists" v-if="currentTab == 1">
                                <v-list-item @click="viewOutbound(item)">
                                    <v-list-item-title>
                                        View
                                    </v-list-item-title>
                                </v-list-item>
                                 <v-list-item @click="editOrder(item,'copy')">
                                    <v-list-item-title>
                                        Duplicate
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="printOrder(item)">
                                    <v-list-item-title>
                                        Print Order
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item v-if="showCancelButton(item)" @click="cancel(item)">
                                    <v-list-item-title class="cancel">
                                        Cancel Order
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>

                            <v-list class="outbound-lists" v-if="currentTab == 2">
                                <v-list-item @click="viewOutbound(item)">
                                    <v-list-item-title>
                                        View
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item v-if="isWarehouse3PL ==true" @click="checkEditInbound(item, 'edit')">
								<v-list-item-title> Edit </v-list-item-title>
							</v-list-item>
                                 <v-list-item @click="editOrder(item,'copy')">
                                    <v-list-item-title>
                                        Duplicate
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="printOrder(item)">
                                    <v-list-item-title>
                                        Print Order
                                    </v-list-item-title>
                                </v-list-item>    
                
                            </v-list>
                             <v-list class="outbound-lists" v-if="currentTab == 3">
                                <v-list-item @click="viewOutbound(item)">
                                    <v-list-item-title>
                                        View
                                    </v-list-item-title>
                                </v-list-item>
                                 <v-list-item @click="editOrder(item,'copy')">
                                    <v-list-item-title>
                                        Duplicate
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="printOrder(item)">
                                    <v-list-item-title>
                                        Print Order
                                    </v-list-item-title>
                                </v-list-item>    
                            </v-list>
                        </v-menu>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.name`]="{ item }">                
                <div class="inventory-outbound-wrapper">
                    <div class="inventory-outbound-items"> 
                        <p>TRUCK</p>
                        <span>{{ typeof item.name !== 'undefined' && item.name !== null ? item.name : '--' }}</span>
                    </div>
                </div>
            </template>
            <template v-slot:[`item.created_at`]="{ item }">
				<div class="inventory-outbound-wrapper">
					<div class="inventory-outbound-items">
                        <p class="name">Created at: </p>
						<span>{{
							formatCreatedAt(item.created_at)
						}}
                        </span>
					</div>
				</div>
			</template>

            <template v-slot:[`item.ref_no`]="{ item }">                
                <div class="inventory-outbound-wrapper">
                    <div class="inventory-outbound-items"> 
                        <p>REF #</p>
                        <span>{{ typeof item.ref_no !== 'undefined' && item.ref_no !== null && item.ref_no !=='null' ? item.ref_no : '--' }}</span>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.customer`]="{ item }">                
                <div class="inventory-outbound-wrapper">
                    <div class="inventory-outbound-items"> 
                        <p>CUSTOMER</p>
                        <span>{{ typeof item.customer !== 'undefined' && item.customer !== null ? item.customer : '--' }}</span>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.estimated_time`]="{ item }">                
                <div class="inventory-outbound-wrapper">
                    <div class="inventory-outbound-items"> 
                        <p>ETA</p>
                        <span>{{ formatDate(item.estimated_date, item.estimated_time) }}</span>
                    </div>
                </div>
            </template>
            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="inventory-outbound-wrapper mt-1">
                    <div class="inventory-outbound-items">
                        <p class="name">Updated at: </p>
                        <span>{{ formatCreatedAt(item.updated_at) }}</span>
                    </div>
                </div>
            </template>
            <template v-slot:[`item.warehouse_customer`]="{ item }">
                <div class="inventory-outbound-wrapper mt-1">
                    <div class="inventory-outbound-items">
                        <p class="name">Customer</p>
                        <span>
                    {{ item.warehouse_customer !== null ? item.warehouse_customer.company_name : '--' }}
                        </span>
                    </div>
                </div>
            </template>
            <template v-slot:[`item.customer_admin_name`]="{ item }">
				<div>
					<span>
                    {{ item.customer_admin_name !== null && item.customer_admin_name !==undefined && item.customer_admin_name !=='undefined'  ? item.customer_admin_name : '--' }}
                </span>
				</div>
					<span style="color: #6D858F;">{{ formatCreatedAt(item.created_at) }} </span>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="currentWarehouseLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper" v-if="!currentWarehouseLoading && currentWarehouseOutbounds.length == 0">
                    <div class="no-data-heading">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">     

                        <div v-if="currentTab === 0">
                            <h3> Create Outbound </h3>
                            <p> Let's add outbound to this warehouse. </p>

                            <div class="d-flex justify-center align-center mt-4">
                                <v-btn color="primary" dark class="btn-blue" @click.stop="createOutbound">
                                    Create Outbound
                                </v-btn>
                            </div>
                        </div>
                         <div v-if="currentTab === 1">
                            <h3> No Floor Outbounds </h3>
                            <p> There are no floor outbounds listed. </p>
                        </div>                 

                        <div v-if="currentTab === 2">
                            <h3> No Completed Outbounds </h3>
                            <p> There are no completed outbounds listed. </p>
                        </div>

                        <div v-if="currentTab === 3">
                            <h3> No Cancelled Outbounds </h3>
                            <p> There are no cancelled outbounds listed. </p>
                        </div>
                    </div>
                </div>
            </template>
        </v-data-table>

        <CreateOutboundDialog 
            :dialogCreate.sync="dialogCreate"
            :editedOutboundIndex.sync="editedOutboundIndex"
            :editedOutboundItems.sync="editedOutboundItems"
            :defaultOutboundItems.sync="defaultOutboundItems"
            :currentWarehouseSelected="currentWarehouseSelected"
            :isFetchSingleOutboundbound="false"
            @close="closeCreate"
            :status="outbound_Status_for_3pl"
			:outboundProducts_clone="outboundProducts_clone"
            :productsListsData.sync="productsListsData"
            :fetchProductLoading="fetchProductLoading"
            @fetchOutboundProductsAPiFunction="fetchOutboundProductsAPiFunction"
            @Warehouse6PL_ProductsOnchange="Warehouse6PL_ProductsOnchange"
            :isWarehouseConnected="isWarehouseConnected" />

        <CancelOutbound 
            :dialogData.sync="dialogCancel"
            :editedItemData.sync="cancelOrderData"
            @cancel="cancelConfirm"
            @close="closeCancel"
            :loading="getCancelOutboundLoading" />

        <!-- <Pagination 
            v-if="currentWarehouseOutbounds !== 'undefined' && currentWarehouseOutbounds.length > 0"
            :pageData.sync="page"
            :lengthData.sync="pageCount"
            :isMobile="isMobile"
        /> -->
        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />
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
					This order has already been completed. Do you still want to edit this outbound order?
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
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
// import Search from '../../../Search.vue'
import SearchComponent from '../../../SearchComponent/SearchComponent.vue'
import CreateOutboundDialog from '../../../InventoryComponents/OutboundComponents/CreateOutboundDialog.vue'
import CancelOutbound from '../../../InventoryComponents/OutboundComponents/CancelOutbound.vue'
import ConfirmDialog from '../../../Dialog/GlobalDialog/ConfirmDialog.vue'
// import Pagination from '../../../Pagination'
import PaginationComponent from "../../..//PaginationComponent/PaginationComponent.vue"
import moment from 'moment'
import globalMethods from '../../../../utils/globalMethods'

// search OUtbound
import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'InventoryOutboundMobileTable',
    props: ['currentWarehouseSelected', 'isMobile','isWarehouseConnected'],
    components: {
        // Search,
        SearchComponent,
        CreateOutboundDialog,
        CancelOutbound,
        ConfirmDialog,
        // Pagination,
        PaginationComponent
    },
    data: () => ({
        showWarningEditInboundDialog:false,
        currentEditInboundData: null,
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        headers: [
			{
				text: 'Order ID',
				align: 'start',
				sortable: false,
				value: 'order_id',
				fixed: true,
				width: "12%"
			},
			{ 
				text: 'Truck',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "20%"
			},
            { 
				text: 'Customer',
				align: 'start',
				sortable: false,
				value: 'customer',
				fixed: true,
				width: "14%"
			},
            { 
				text: 'Reference',
				align: 'start',
				sortable: false,
				value: 'ref_no',
				fixed: true,
				width: "12%"
			},
			{ 
				text: 'ETA/Arrival Time',
				align: 'start',
				sortable: false,
				value: 'estimated_time',
				fixed: true,
				width: "18%"
			},
			{ 
				text: 'Status',
				align: 'center',
				sortable: false,
				value: 'outbound_status',
				fixed: true,
				width: "12%"
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "12%"
			},
		],
        header6PL:[
			{
			text: "Order ID",
			align: "start",
			sortable: false,
			value: "order_id",
			fixed: true,
			width: "12%",
		},
        {
			text: "Created By & At",
			align: "start",
			sortable: false,
			value: "customer_admin_name",
			fixed: true,
			width: "20%",
		},
		{
			text: "Truck",
			align: "start",
			sortable: false,
			value: "name",
			fixed: true,
			width: "20%",
		},
			{
			text: "Reference",
			align: "start",
			sortable: false,
			value: "ref_no",
			fixed: true,
			width: "22%",
		},
		{
			text: "Customer",
			align: "start",
			sortable: false,
			value: "warehouse_customer",
			fixed: true,
			width: "20%",
		},
		{
			text: "ETA/Arrival Time",
			align: "start",
			sortable: false,
			value: "estimated_time",
			fixed: true,
			width: "20%",
		},
		{
			text: "Status",
			align: "center",
			sortable: false,
			value: "outbound_status",
			fixed: true,
			width: "13%",
		},
		{
			text: "",
			align: "end",
			value: "actions",
			sortable: false,
			fixed: true,
			width: "5%",
		},
		],
        header3PL: [
		{
			text: "Order ID",
			align: "start",
			sortable: false,
			value: "order_id",
			fixed: true,
			width: "12%",
		},
		{
			text: "Created At",
			align: "start",
			sortable: false,
			value:'created_at',
			fixed: true,
			width: "20%",

		},
		{
			text: "Reference",
			align: "start",
			sortable: false,
			value: "ref_no",
			fixed: true,
			width: "20%",
		},
		{
			text: "ETA",
			align: "start",
			sortable: false,
			value: "estimated_time",
			fixed: true,
			width: "35%",
		},
		{
			text: "Updated At",
			align: "start",
			sortable: false,
			value: "updated_at",
			fixed: true,
			width: "35%",
		},
		{
			text: "",
			align: "end",
			value: "actions",
			sortable: false,
			fixed: true,
			width: "5%",
		},
		],
		completed_heder: [
		{
			text: "Order ID",
			align: "start",
			sortable: false,
			value: "order_id",
			fixed: true,
			width: "12%",
		},
		{
			text: "Created At",
			align: "start",
			sortable: false,
			value:'created_at',
			fixed: true,
			width: "18%",

		},
		{
			text: "Reference",
			align: "start",
			sortable: false,
			value: "ref_no",
			fixed: true,
			width: "18%",
		},
		{
			text: "Updated At",
			align: "start",
			sortable: false,
			value: "updated_at",
			fixed: true,
			width: "15%",
		},
		{
			text: "Status",
			align: "center",
			sortable: false,
			value: "outbound_status",
			fixed: true,
			width: "15%",
		},
		{
			text: "",
			align: "end",
			value: "actions",
			sortable: false,
			fixed: true,
			width: "10%",
		},
		],
		dialogCreate: false,
		editedOutboundIndex: -1,
        editedOutboundItems: {
			order_id: '',
            customer: '',
            ref_no: '',
            deliver_to: '',
            name: '',
            notes:"",
            estimated_date: null,
            estimated_time: null,
            warehouse_customer_id:'',
            outbound_products: [
                {
                    product: {
                        product_id: '',
                    },
                    product_id: '',
                    quantity: '',
                    shipping_unit: '',
                    total_unit: '',
                    outbound_product_id:null
                }
            ],
            pallets: [],
            outbound_documents: [],
            bol_number: '',
            outbound_storable_units: [
			{
				id: null,
				outbound_id: null,
				warehouse_id: null,
				customer_id: null,
				action: null,
				label: null,
				type: "",
				created_at: "",
				updated_at: "",
				dimension: {
					l: "",
					w: "",
					h: "",
					uom: "",
				},
				weight: {
					value: "",
					unit: "",
				},
				products: null,
				status: "",
				no_of_sku: null,
				deleted_at: null,
				storable_unit_id: null,
				outbound_storable_unit_products: [
					{
						id: null,
						outbound_product_id: null,
						outbound_storable_unit_id: null,
						carton_count: "",
						total_unit: "",
						in_each_carton: "",
						storable_unit_product_id: null,
						created_at: "",
						updated_at: "",
					},
				],
			},
		],
		},
		defaultOutboundItems: {
			order_id: '',
            customer: '',
            ref_no: '',
            deliver_to: '',
            name: '',
            notes:"",
            estimated_date: null,
            estimated_time: null,
            warehouse_customer_id:'',
            outbound_products: [
                {
                    product: {
                        product_id: '',
                    },
                    product_id: '',
                    quantity: '',
                    shipping_unit: '',
                    total_unit: '',
                    outbound_product_id:null
                }
            ],
            pallets: [],
            outbound_documents: [],
            bol_number: '',
            outbound_storable_units: []
		},
        editedPoItems: {
            products: [{
                id: null,
                quantity: 0,
                unit_price: 0,
                amount: 0
            }],
            po_number: '',
			is_system_generated: 1,
			supplier_id: '',
			customer_id: '',
			notes: '',
			created_by: '',
			tax: 0,
			warehouse_id: '',
			sub_total: '',
			shipping: 0,
			total: '',
			discount: 0
        },
        defaultPoItems: {
            products: [{
                id: null,
                quantity: 0,
                unit_price: 0,
                amount: 0
            }],
            po_number: '',
			is_system_generated: 1,
			supplier_id: '',
			customer_id: '',
			notes: '',
			created_by: '',
			tax: 0,
			warehouse_id: '',
			sub_total: '',
			shipping: 0,
			total: '',
			discount: 0
        },
        tabs: ["Pending", "Floor", "Completed", "Cancalled"],
        tabCount: [],
        currentTab: 0,
        cancelOrderData: null,
        dialogCancel: false,
        outbound_Status_for_3pl:'',
		outboundProducts_clone:[],
        // Products Drop Down 
		lastDataCheck: [],
		productsListsData: [],
		current_page_is: 1,
		fetchProductLoading:false,
        // search Outbound
        pendingOutboundLoadingPage:false,
        floorOutboundLoadingPage:false,
        completedOutboundLoadingPage:false,
        cancelledOutboundLoadingPage:false,
        // 6pl
        SearchCustomerFinalArray:[],
        // wareHouse 6PL
		current_page_6pl_products:1
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getOutboundInventories: 'outbound/getOutboundInventories',
            getOutboundInventoriesLoading: 'outbound/getOutboundInventoriesLoading',
            getCancelOutboundLoading: 'outbound/getCancelOutboundLoading',
            getCurrentOutboundTab: 'outbound/getCurrentOutboundTab',
            getPrintOutboundOrderLoading: "outbound/getPrintOutboundOrderLoading",
            // 
            getPendingOutbounds: 'outbound/getPendingOutbounds',
            getFloorOutbounds: 'outbound/getFloorOutbounds',
            getCompletedOutbounds: 'outbound/getCompletedOutbounds',
            getCancelledOutbounds: 'outbound/getCancelledOutbounds',
            getPendingOutboundsLoading: 'outbound/getPendingOutboundsLoading',
            getFloorOutboundsLoading: 'outbound/getFloorOutboundsLoading',
            getCompletedOutboundsLoading: 'outbound/getCompletedOutboundsLoading',
            getCancelledOutboundsLoading: 'outbound/getCancelledOutboundsLoading',
            // Products DropDown
			getAllOutboundProductsListsDropdownData:'outbound/getAllOutboundProductsListsDropdownData',
            getallOutboundProductsLists:'outbound/getallOutboundProductsLists',
            // Search Outbounds
			poBaseUrlState: 'products/poBaseUrlState',
			getsearchedOutboundsLoading:'outbound/getsearchedOutboundsLoading',
            getSearchedOutbounds:'outbound/getSearchedOutbounds',
            // 6pl
            getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
			getallProducts6PLists: 'outbound/getallProducts6PLists',
            // pagination
            getPendingOutboundPagination:'outbound/getPendingOutboundPagination',
            getFloorOutboundPagination:"outbound/getFloorOutboundPagination",
			getCompletedOutboundPagination:"outbound/getCompletedOutboundPagination",
			getCancelledOutboundPagination:"outbound/getCancelledOutboundPagination"
        }), 
        _header(){
			let final_header = []
			if( this.isWarehouse3PL == true){
				if( this.currentTab ==2){
					return final_header = this.completed_heder
				}
				else{
					final_header = this.header3PL
				return final_header.filter(val =>{
					if(this.currentTab == 3){
						return val.text !=='ETA'
					}
					else if(this.currentTab ==0){
						return val.text !== 'Updated At'
					}
				})
				}
			}
            else if(this.isWarehouse6PL ==true){
                return final_header = this.header6PL
            }
            else{
				return final_header = this.headers
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
        isWarehouse6PL() {
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
        getCurrentLoadingToDisplay() {
            if (this.search === '') {
                return this.getHandlePageLoading
            } else {
                return this.getsearchedOutboundsLoading
            }
        },
        getHandlePageLoading() {
            if (this.currentTab === 0) {
                return this.pendingOutboundLoadingPage
            } else if (this.currentTab === 1) {
                return this.floorOutboundLoadingPage
            } else if (this.currentTab === 2) {
                return this.completedOutboundLoadingPage
            } else {
                return this.cancelledOutboundLoadingPage
            }
        },
        currentWarehouseOutbounds() {
            let outboundsData = {}

            if (this.currentTab === 0) {
                outboundsData = this.pendingOutboundsDataLists.data
            } else if (this.currentTab === 1) {
                outboundsData = this.floorOutboundsDataLists.data
            } else if (this.currentTab === 2) {
                outboundsData = this.completedOutboundsDataLists.data
            } else {
                outboundsData = this.cancelledOutboundsDataLists.data
            }
            
            return outboundsData
        },
		// currentWarehouseOutbounds() {
        //     let outboundItems = []

        //     if (this.currentTab === 0) {
        //         if (typeof this.getPendingOutbounds !== 'undefined' && this.getPendingOutbounds !== null 
        //             && Array.isArray(this.getPendingOutbounds.data) && this.getPendingOutbounds.data.length > 0) {
        //             outboundItems = this.getPendingOutbounds.data
        //         }
        //     } else if (this.currentTab === 1) {
        //         if (typeof this.getFloorOutbounds !== 'undefined' && this.getFloorOutbounds !== null 
        //             && Array.isArray(this.getFloorOutbounds.data) && this.getFloorOutbounds.data.length > 0) {
        //             outboundItems = this.getFloorOutbounds.data
        //         }
        //     } else if (this.currentTab === 2) {
        //         if (typeof this.getCompletedOutbounds !== 'undefined' && this.getCompletedOutbounds !== null 
        //             && Array.isArray(this.getCompletedOutbounds.data) && this.getCompletedOutbounds.data.length > 0) {
        //             outboundItems = this.getCompletedOutbounds.data
        //         }
        //     } else if (this.currentTab === 3) {
        //         if (typeof this.getCancelledOutbounds !== 'undefined' && this.getCancelledOutbounds !== null 
        //             && Array.isArray(this.getCancelledOutbounds.data) && this.getCancelledOutbounds.data.length > 0) {
        //             outboundItems = this.getCancelledOutbounds.data
        //         }
        //     }

        //     return outboundItems
        // },
        currentWarehouseLoading() {
            if (this.currentTab === 0) {
                return this.getPendingOutboundsLoading
            } else if (this.currentTab === 1) {
                return this.getFloorOutboundsLoading
            } else if (this.currentTab === 2) {
                return this.getCompletedOutboundsLoading
            } else {
                return this.getCancelledOutboundsLoading
            }
        },
        getTotalPage: {
            get() {
                let total = 1
                let inboundsData = this.loadOutboundDataChecking()

                if (typeof inboundsData.last_page !== 'undefined' && inboundsData.last_page !== null) {
                    total = inboundsData.last_page
                }

                return total
            }
        },
        getCurrentPage: {
            get() {
                let current_page = 1
                let inboundsData = this.loadOutboundDataChecking()

                if (typeof inboundsData.current_page !== 'undefined' && inboundsData.current_page !== null) {
                    current_page = inboundsData.current_page
                }

                return current_page
            },
            set() {
                return {}
            }
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
        pendingOutboundsDataLists() {
            let outboundLists = []
            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null) {
                if (this.search !== '' && this.currentTab === 0 && this.getSearchedOutbounds.tab === 'pending') {
                    outboundLists = this.getSearchedOutbounds
                } 
				else {
                    if (typeof this.getPendingOutboundPagination !== 'undefined' && 
                        this.getPendingOutboundPagination !== null) {
                        outboundLists = this.getPendingOutboundPagination
                    }
                }
            }

            return outboundLists
        },
        floorOutboundsDataLists() {
            let outboundLists = []

            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null) {
                if (this.search !== '' && this.currentTab === 1 && this.getSearchedOutbounds.tab === 'floor') {
                    outboundLists = this.getSearchedOutbounds
                } else {
                    if (typeof this.getFloorOutboundPagination !== 'undefined' && 
                        this.getFloorOutboundPagination !== null) {
                        outboundLists = this.getFloorOutboundPagination
                    }
                }
            }

            return outboundLists
        },
        completedOutboundsDataLists() {
            let outboundLists = []

            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null) {
                if (this.search !== '' && this.currentTab === 2 && this.getSearchedOutbounds.tab === 'completed') {
                    outboundLists = this.getSearchedOutbounds
                } else {
                    if (typeof this.getCompletedOutboundPagination !== 'undefined' && 
                        this.getCompletedOutboundPagination !== null) {
                        outboundLists = this.getCompletedOutboundPagination
                    }
                }
            }

            return outboundLists
        },
        cancelledOutboundsDataLists() {
            let outboundLists = []

            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null) {
                if (this.search !== '' && this.currentTab === 3 && this.getSearchedOutbounds.tab === 'cancelled') {
                    outboundLists = this.getSearchedOutbounds
                } else {
                    if (typeof this.getCancelledOutboundPagination !== 'undefined' && 
                        this.getCancelledOutboundPagination !== null) {
                        outboundLists = this.getCancelledOutboundPagination
                    }
                }
            }

            return outboundLists
        },
    },
    methods: {
        ...mapActions({
            fetchSingleProduct: 'products/fetchSingleProduct',
            setEditInventory: 'inventory/setEditInventory',
            deleteInventory: 'inventory/deleteInventory',
            fetchInventories: 'inventory/fetchInventories',
            setIsEditing: 'inventory/setIsEditing',
            // 
            cancelOutbound: 'outbound/cancelOutbound',
            printOutboundOrder: "outbound/printOutboundOrder",
            fetchOutboundInventories: 'outbound/fetchOutboundInventories',
            fetchPendingOutbounds: 'outbound/fetchPendingOutbounds',
            fetchFloorOutbounds: 'outbound/fetchFloorOutbounds',
            fetchCompletedOutbounds: 'outbound/fetchCompletedOutbounds',
            fetchCancelledOutbounds: 'outbound/fetchCancelledOutbounds',
            setCurrentOutboundTab: 'outbound/setCurrentOutboundTab',
            // Products Dropdown
			setAllOutboundProductsLists: 'outbound/setAllOutboundProductsLists',
            fetchOutboundProducts:'outbound/fetchOutboundProducts',
            // Search Outbound
			setSearchedOutboundLoading: 'outbound/setSearchedOutboundLoading',
            setOutboundSearchedVal: 'outbound/setOutboundSearchedVal',
            fetchSearchedOutbounds: 'outbound/fetchSearchedOutbounds',
            // 3PL
			multipleLoadProductApi:"outbound/multipleLoadProductApi",
            //6pl
            wareHouse6plProduct: "outbound/wareHouse6plProduct",
        }),
        ...globalMethods,
        formatDate(date, time) {
            let newTime = null
            let newDate = null

            let final_estimated_time_and_date = null
            
            if (date !== '' && date !== null) {
                newDate = moment(date).format('MMM DD, YYYY')

                if (time !== '' && time !== null) {
                    newTime = moment(time, ["HH:mm"]).format("hh:mmA, ");
                }   
            }

            if (newDate !== null) {
                if (newTime !== null) {
                    final_estimated_time_and_date = newTime + newDate
                } else {
                    final_estimated_time_and_date = 'N/A, ' + newDate
                }
            } else {
                if (newTime !== null) {
                    final_estimated_time_and_date = newTime + ', N/A'
                } else {
                    final_estimated_time_and_date = '--'
                }
            }

            return final_estimated_time_and_date
        },
        formatCreatedAt(date_time){
			let final_estimated_time_and_date = null;
			if(typeof date_time !== "undefined" && date_time !==null && date_time !==undefined && date_time !==''){
			final_estimated_time_and_date =	moment(date_time).format('Do MMMM YYYY, h:mm:a');
			}else{
				final_estimated_time_and_date='--'
			}
			return final_estimated_time_and_date
		},
        onTabChange(i) {
            this.currentTab = i
            this.setOutboundSearchedVal([])
            this.SearchCustomerFinalArray = []
        },
        // getTabCount(index) {
        //     let data = '0'

        //     if (index === 0) {
        //         data = typeof this.getPendingOutbounds.total !== 'undefined' ? this.getPendingOutbounds.total : 0
        //     } else if (index === 1) {
        //         data = typeof this.getFloorOutbounds.total !== 'undefined' ? this.getFloorOutbounds.total : 0
        //     } else if (index === 2) {
        //         data = typeof this.getCompletedOutbounds.total !== 'undefined' ? this.getCompletedOutbounds.total : 0
        //     }
          
        //     let finalData = data !== 0 ? data : '0'

        //     return finalData
        // },
        async onClickTab(i) {
            this.setOutboundSearchedVal([])
            this.search = ''
			try {
				let storeOutboundTab = this.$store.state.outbound
                let dataWithPage = {
                    id: this.currentWarehouseSelected.id,
                    page: 1
                }
				if (i === 0 && this.currentTab !== i) {
					dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
					await this.fetchPendingOutbounds(dataWithPage);
					storeOutboundTab.pendingOutboundPagination.old_page = storeOutboundTab.pendingOutboundPagination.current_page
					this.setCurrentOutboundTab(i);
				} else if (i === 1 && this.currentTab !== i) {
					dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
					await this.fetchFloorOutbounds(dataWithPage);
					storeOutboundTab.floorOutboundPagination.old_page = storeOutboundTab.floorOutboundPagination.current_page
					this.setCurrentOutboundTab(i);
				} else if (i === 2 && this.currentTab !== i) {
					dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
					await this.fetchCompletedOutbounds(dataWithPage);
					storeOutboundTab.completedOutboundPagination.old_page = storeOutboundTab.completedOutboundPagination.current_page
					this.setCurrentOutboundTab(i);
				} else if (i === 3 && this.currentTab !== i) {
					dataWithPage.page = storeOutboundTab.cancelledOutboundPagination.current_page
					await this.fetchCancelledOutbounds(dataWithPage);
					storeOutboundTab.cancelledOutboundPagination.old_page = storeOutboundTab.cancelledOutboundPagination.current_page
					this.setCurrentOutboundTab(i);
				}
			} catch (e) {
				this.notificationError(e);
				// console.log(e);
			}
		},
        // outboundActions
        createOutbound() {
            this.dialogCreate = true
            this.$nextTick(() => {
                this.editedOutboundItems = Object.assign({}, this.defaultOutboundItems)
                this.editedOutboundIndex = -1;
            })
        },
        closeCreate() {
            this.dialogCreate = false
            this.$nextTick(() => {
                this.editedOutboundItems = Object.assign({}, this.defaultOutboundItems)
                this.editedOutboundIndex = -1;
            })
            if (this.currentEditInboundData !== null) {
                this.closeWarning()
            }
        },
        viewOutbound(e) {
            this.$router.push(`inventory/outbound-view?id=${e.id}&wid=${this.currentWarehouseSelected.id}`)
        },
        async editOrder(outbound,toDo) {
            this.editedOutboundIndex = this.currentWarehouseOutbounds.indexOf(outbound)
            let outboundItem = { ...outbound }
            this.outboundProducts_clone =outbound.outbound_products
            this.outbound_Status_for_3pl = outboundItem.outbound_status
            if (!outboundItem.outbound_products || outboundItem.outbound_products.length === 0) {
                let buildProduct = [
                    {
                        product_id: '',
                        quantity: '',
                        shipping_unit: '',
                        total_unit:"",
                        status:'',
                        outbound_product_id:null,
                        pr_id:'',
                        remaining_carton_count:0,
                        remaining_total_unit:0
                    }
                ]

                outboundItem = { ...outboundItem, outbound_products: buildProduct }

            } else {
                // let buildProduct = outboundItem.outbound_products.map(v => ({
                //     product_id: v.product_id,
                //     quantity: v.carton_count !== null ? parseInt(v.carton_count) : '',
                //     shipping_unit: v.shipping_unit,
                //     total_unit:v.total_unit !==null ? parseInt(v.total_unit):"",
                //     status: v.status,
                //     outbound_product_id:v.id,
                //     pr_id:v.product_id,
                //     remaining_carton_count:v.remaining_carton_count !==null ?v.remaining_carton_count:0,
				// 	remaining_total_unit:v.remaining_total_unit !==null ? v.remaining_total_unit:0,
                // }))
                let buildProduct = outboundItem.outbound_products.map(v => {
                    let { id, product_id, carton_count,total_unit, shipping_unit, remaining_total_unit, remaining_carton_count } = v

                    if (shipping_unit === 'carton') {
                        return { 
                            outbound_product_id: id,
                            product_id,
                            quantity: carton_count !== null ? parseInt(carton_count) : '',
                            shipping_unit,
                            status: v.status,
                            carton_count: carton_count !== null ? parseInt(carton_count) : '',
							total_unit: total_unit !== null ? parseInt(total_unit) : "",
                            remaining_carton_count: remaining_carton_count !== null ? parseInt(remaining_carton_count) : 0,
							remaining_total_unit: remaining_total_unit !== null ? parseInt(remaining_total_unit) : 0,
							pr_id:v.product_id
                        }
                    } else {
                        return { 
                            outbound_product_id: id,
                            product_id,
                            quantity: total_unit !== null ? parseInt(total_unit) : '',
                            shipping_unit,
							status: v.status,
							carton_count: carton_count !== null ? parseInt(carton_count) : '',
							total_unit: total_unit !== null ? parseInt(total_unit) : "",
                            remaining_total_unit: remaining_total_unit !== null ? parseInt(remaining_total_unit) : '',
                            remaining_carton_count: remaining_carton_count !== null ? parseInt(remaining_carton_count) : 0,
							pr_id:v.product_id
                        }
                    }
                })


                // let productStatusCheck=[]
                // let buildProduct=[]
                // if(toDo == "edit"){
                // productStatusCheck = outboundItem.outbound_products.filter(val=>val.status !=='picked')
                // buildProduct = productStatusCheck.map(v => ({
                //     product_id: v.product_id,
                //     quantity: v.carton_count !== null ? parseInt(v.carton_count) : '',
                //     shipping_unit: v.shipping_unit,
                //      total_unit:v.total_unit !==null ? parseInt(v.total_unit):""
                // }))
                // }else{
                // productStatusCheck = outboundItem.outbound_products
                // buildProduct = productStatusCheck.map(v => ({
                //     product_id: v.product_id,
                //     quantity: v.carton_count !== null ? parseInt(v.carton_count) : '',
                //     shipping_unit: v.shipping_unit,
                //      total_unit:v.total_unit !==null ? parseInt(v.total_unit):""
                // })) 
                // }
                
                // if(!productStatusCheck || productStatusCheck.length==0){
                //      buildProduct = [
                //     {
                //         product_id: '',
                //         quantity: '',
                //         shipping_unit: '',
                //         total_unit:''
                //     }
                // ]
                // }

                outboundItem = { ...outboundItem, outbound_products: buildProduct }
            }

            if (!outboundItem.outbound_documents || outboundItem.outbound_documents == null) {
                outboundItem = { ...outboundItem, outbound_documents: [] }
            } else {
                let convertDocuments = outboundItem.outbound_documents.map(v => {
                    if (v.original_name !== 'undefined') {
                        return new File([v.original_name], `${v.original_name}`, {
                            type: v.type
                        });
                    }
                })

                outboundItem = { ...outboundItem, outbound_documents: convertDocuments }
            }

            if (!outboundItem.estimated_time || outboundItem.estimated_time == 'null') {
                outboundItem = { ...outboundItem, estimated_time: null }
            }

            if (!outboundItem.estimated_date || outboundItem.estimated_date == 'null') {
                outboundItem = { ...outboundItem, estimated_date: null }
            }
            // storable units
			if (!outboundItem.outbound_storable_units ||
				outboundItem.outbound_storable_units.length === 0) {
				let buildSortable = [
					{
						id: null,
						outbound_id: null,
						warehouse_id: null,
						customer_id: null,
						action: null,
						label: null,
						type: "",
						created_at: "",
						updated_at: "",
						parse_dimensions: {
							l: "",
							w: "",
							h: "",
							uom: "",
						},
						parse_weight: {
							value: "",
							unit: "",
						},
						outbound_storable_unit_products: [
							{
							id: null,
							outbound_product_id: null,
							outbound_storable_unit_id: null,
							carton_count: "",
							total_unit: "",
							in_each_carton: "",
							storable_unit_product_id: null,
							created_at: "",
							updated_at: "",
							},
						],
						products: null,
						status: "",
						no_of_sku: null,
						deleted_at: null,
						storable_unit_id: null,
					},
				],

				outboundItem = {
					...outboundItem,
					outbound_storable_units: buildSortable,
				};
			} else {
				let buildSortable = outboundItem.outbound_storable_units.map((v) => ({
					id: v.id,
					outbound_id: v.outbound_id,
					warehouse_id: v.warehouse_id,
					customer_id: v.customer_id,
					action: v.action,
					label: v.label,
					type: v.type,
					created_at: v.created_at,
					updated_at: v.updated_at,
					parse_dimensions: v.dimension,
					parse_weight: v.weight,
					products: v.products,
					status: v.status,
					no_of_sku: v.no_of_sku,
					deleted_at: v.deleted_at,
					storable_unit_id: v.storable_unit_id,
					outbound_storable_unit_products: v.outbound_storable_unit_products,
				}));

				outboundItem = {
					...outboundItem,
					outbound_storable_units: buildSortable,
				};
			}
            outboundItem.isDuplicate = toDo == "edit" ? false : true;
			this.editedOutboundIndex = outboundItem.isDuplicate
				? -1
				: this.editedOutboundIndex;
			// if is duplicate, set order id to empty
			outboundItem.order_id = outboundItem.isDuplicate
				? ""
				: outboundItem.order_id;

            this.editedOutboundItems = Object.assign({}, outboundItem)
            this.dialogCreate = true
            if(this.isWarehouse6PL ==true){
                let warehouse_data = {
					id:outboundItem.warehouse_customer_id
				}
				await this.Warehouse6PL_ProductsOnchange(warehouse_data)
				await this.callWarehouseCustomerListsData()
			}
			else{
				this.fetchOutboundProductsAPiFunction('Outbound')
			}
        },
        arrangeTracking(e) {
            console.log('arrange', e);
        },
        printLabel(e) {
            console.log('print label', e);
        },
        duplicate(e) {
            console.log('duplicate', e);
        }, 
        cancel(outbound) {
            if (outbound !== null) {
                this.cancelOrderData = outbound
                this.dialogCancel = true
            }
        },
        async cancelConfirm() {
            if (this.cancelOrderData !== null) {
                try {
                    let payload = {
                        wid: this.cancelOrderData.warehouse_id,
                        oid: this.cancelOrderData.id
                    }

                    await this.cancelOutbound(payload)
                    this.dialogCancel = false
                    this.notificationMessage('Outbound has been cancelled.')
                    try {
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected.id,page: 1}
						if (this.currentTab == 0) {
							dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
							await this.fetchPendingOutbounds(dataWithPage);
						}
						
						if (this.currentTab == 1) {
							dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
							await this.fetchFloorOutbounds(dataWithPage);
						}
						this.dialogCancel = false;
						this.cancelOrderData = null;
					} catch (e) {
						this.notificationError(e);
					}
                    this.cancelOrderData = null
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        closeCancel() {
            this.dialogCancel = false
        },
       async printOrder(item) {
        if (item !== null) {
				let payload = {
					wid: item.warehouse_id,
					oid: item.id,
					order_id: item.order_id,
				};

				try {
					this.notificationCustom("Generating print order...");
					await this.printOutboundOrder(payload);
				} catch (e) {
					this.notificationError(e);
				}
			}
        },
        loadOutboundDataChecking() {
                if (this.currentTab === 0) {
                    if (typeof this.getPendingOutboundPagination !== 'undefined' && 
                        this.getPendingOutboundPagination !== null) {
                        return this.getPendingOutboundPagination
                    }
                } else if (this.currentTab === 1) {
                    if (typeof this.getFloorOutboundPagination !== 'undefined' && 
                        this.getFloorOutboundPagination !== null) {
                        return this.getFloorOutboundPagination
                    }
                } else if (this.currentTab === 2) {
                    if (typeof this.getCompletedOutboundPagination !== 'undefined' && 
                        this.getCompletedOutboundPagination !== null) {
                        return this.getCompletedOutboundPagination
                    }
                } else if (this.currentTab === 3) {
                    if (typeof this.getCancelledOutboundPagination !== 'undefined' && 
                        this.getCancelledOutboundPagination !== null) {
                        return this.getCancelledOutboundPagination
                    }
                }
        },
        getTabCount(index) {
            let data = '0'
                if (index === 0) {
                    if (typeof this.getPendingOutboundPagination !== 'undefined' && 
                        this.getPendingOutboundPagination !== null) {
                        return this.getPendingOutboundPagination.total
                    }
                } else if (index === 1) {
                    if (typeof this.getFloorOutboundPagination !== 'undefined' && 
                        this.getFloorOutboundPagination !== null) {
                        return this.getFloorOutboundPagination.total
                    }
                } else if (index === 2) {
                    if (typeof this.getCompletedOutboundPagination !== 'undefined' && 
                        this.getCompletedOutboundPagination !== null) {
                        return this.getCompletedOutboundPagination.total
                    }
                } else if (index === 3) {
                    if (typeof this.getCancelledOutboundPagination !== 'undefined' && 
                        this.getCancelledOutboundPagination !== null) {
                        return this.getCancelledOutboundPagination.total
                    }
                }
          
            let finalData = data !== 0 ? data : '0'

            return finalData
        },
		async handlePageChange(page) {
            if (page !== null) {
                let storeOutboundTab = this.$store.state.outbound
                let dataWithPage = {
                    id: this.currentWarehouseSelected.id,
                    page
                }

                if (this.search == '') {
                    try {
                        if (storeOutboundTab.setCurrentTab === 0 && this.currentTab === 0) {
                            if (storeOutboundTab.pendingOutboundPagination.old_page !== page) {
                                this.pendingOutboundLoadingPage = true
                                await this.fetchPendingOutbounds(dataWithPage)
                                storeOutboundTab.pendingOutboundPagination.old_page = page
                                this.pendingOutboundLoadingPage = false
                            }
                        } else if (storeOutboundTab.setCurrentTab === 1 && this.currentTab === 1) {
                            if (storeOutboundTab.floorOutboundPagination.old_page !== page) {
                                this.floorOutboundLoadingPage = true
                                await this.fetchFloorOutbounds(dataWithPage)
                                storeOutboundTab.floorOutboundPagination.old_page = page
                                this.floorOutboundLoadingPage = false
                            }
                        } else if (storeOutboundTab.setCurrentTab === 2 && this.currentTab === 2) {
                            if (storeOutboundTab.completedOutboundPagination.old_page !== page) {
                                this.completedOutboundLoadingPage = true
                                await this.fetchCompletedOutbounds(dataWithPage)
                                storeOutboundTab.completedOutboundPagination.old_page = page
                                this.completedOutboundLoadingPage = false
                            }
                        } else if(storeOutboundTab.setCurrentTab === 3 && this.currentTab === 3) {
                            if (storeOutboundTab.cancelledOutboundPagination.old_page !== page) {
                                this.cancelledOutboundLoadingPage = true
                                await this.fetchCancelledOutbounds(dataWithPage)
                                storeOutboundTab.cancelledOutboundPagination.old_page = page
                                this.cancelledOutboundLoadingPage = false
                            }
                        }
                    } catch (e) {
                        this.notificationError(e)
                    }
                }
				else {
                    let data = {
                        search: this.search,
                        page
                    }

                    this.handlePageSearched(data)
                }  
                this.handleScrollToTop()              
            }
        },
        async handlePageSearched(data) {
            let searchedPagination = this.$store.state.outbound.searchedOutbounds

            if (data !== null && this.search !== '') {
                if (searchedPagination.old_page !== data.page) {
                    let passedData = {
						method: "get",
                        url: '',
						cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: this.search,
                            page: data.page
                        }
                    }

                    let warehouse_id = this.currentWarehouseSelected.id

                    if (this.currentTab == 0) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/pending`
                        passedData.tab = 'pending'
                    } else if (this.currentTab == 1) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/floor`
                        passedData.tab = 'floor'
                    } else if (this.currentTab == 2) {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/completed`
                        passedData.tab = 'completed'
                    } else {
                        passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/cancelled`
                        passedData.tab = 'cancelled'
                    }

                    if (passedData.url !== '') {
                        try {
                            this.fetchSearchedOutbounds(passedData)
                        } catch(e) {
                            this.notificationError(e)
                            this.setSearchedOutboundLoading(false)
                            console.log(e, 'Search error')
                        }
                    }
                }                
            } else {
                this.setOutboundSearchedVal([])
            }

            this.handleScrollToTop()
        },
	handleScrollToTop() {
            var table = this.$refs['my-table'];
            var wrapper = table.$el.querySelector('div.v-data-table__wrapper');
            
            this.$vuetify.goTo(table); // to table
            this.$vuetify.goTo(table, {container: wrapper}); // to header
        },
        showCancelButton(outbound){
        if(typeof outbound !=='undefined' && outbound !== null && outbound !== 'null' && outbound.outbound_storable_units.length>0){
        let result = true
        outbound.outbound_storable_units.find(val=>{
         if( val.status =='loaded' ){
            return result = false
         }
        })
      return result
      }
    },
    conditionForFloorTab(index){
		if(index =='Floor' && this.isWarehouse3PL ==true){
			return false
		}
		else{
			return true
		}
	},
    conditionForTabCounts(index,tab){
		if(index ==2 || index ==3) return false
		else if(tab =='Floor' && this.isWarehouse3PL ==true) return false
        else if (typeof this.getPendingOutboundPagination !== 'undefined' && this.getPendingOutboundPagination !== null && index ==0 && this.getPendingOutboundPagination.total ==0) {
            return false
			}
        else if (typeof this.getFloorOutboundPagination !== 'undefined' && this.getFloorOutboundPagination !== null && index ==1 && this.getFloorOutboundPagination.total ==0) {
            return false
			}
		return true
	},
    closeWarning() {
            this.showWarningEditInboundDialog = false
            this.currentEditInboundData = null
        },
    checkEditInbound(item, toDo) {
                if (this.currentTab === 2 && this.isWarehouse3PL ==true) {
                    this.showWarningEditInboundDialog = true
                    let data = { item, toDo }
                    this.currentEditInboundData = data
                }
        },
        confirmEditOrder() {
            if (this.currentEditInboundData !== null) {
                this.editOrder(this.currentEditInboundData.item, this.currentEditInboundData.toDo)
            }
        },
        async loadAllProducts3PL(item){
			if(item !=='undefined' && item !=='' && item !==null){
				if(item.outbound_products.length>0){
				let final_value = item.outbound_products.filter(val =>{
				return	val.status !=='loaded'
				})
                var result2 = final_value.filter((dropdown) => {
                    return this.productsListsData.some((product_row) => dropdown.product_id === product_row.product_id && ((dropdown.expected_carton_count > 0 && dropdown.expected_carton_count <= product_row.expected_carton_count) || (dropdown.total_unit > 0 && dropdown.total_unit <= product_row.total_unit) )) 
                    });
				if(result2.length==0)return this.notificationError("We don't have enough product in the inventory")
				let Send_payload =[]
				result2 = result2.filter(val =>{
					Send_payload.push(val.id)
				})
				let payload ={
					wid:item.warehouse_id,
					oid:item.id,
					idss:Send_payload
				}
				try{
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected.id,page: 1}
					await this.multipleLoadProductApi(payload)
					dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
					await this.fetchPendingOutbounds(dataWithPage);
                    this.setAllOutboundProductsLists([])
                    this.$emit('fetchOutboundProductsAPiFunction','Outbound')
					this.notificationMessage('Product Loaded sucessfully')
				}catch(e){
					this.notificationError(e)
				}
				
				}
			}
		},
    async fetchOutboundProductsAPiFunction(from){
        if(from =='6plProvider'){
			this.setAllOutboundProductsLists([])
		}
      let payload =null
      if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
      payload =this.currentWarehouseSelected.id
     }
      try {
                if (this.getAllOutboundProductsListsDropdownData.length === 0) {
                    this.current_page_is = 1
                    this.fetchProductLoading =true
                    let sendData ={
                      wid:payload,
                      page:this.current_page_is
                    }
                    await this.fetchOutboundProducts(sendData)
                    
                    if (typeof this.getallOutboundProductsLists !== 'undefined' && 
                        this.getallOutboundProductsLists !== null && 
                        typeof this.getallOutboundProductsLists.products !== 'undefined' && 
                        Array.isArray(this.getallOutboundProductsLists.products) &&
                        this.getallOutboundProductsLists.products.length > 0) {
                            
                        this.productsListsData = this.getallOutboundProductsLists.products.map(value => {
                           if(value.is_from_inventory ==true){
								return {
								product_id: value.product.id,
                                id: value.product.id,
                                name: value.product.name,
                                sku: value.sku,
                                image: value.product.image,
                                description: value.product.description,
								in_each_carton:value.in_each_carton,
                                category_sku: value.category_sku,
								category_id:value.product.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:value.product,
                                expected_carton_count: value.expected_carton_count,
                                total_unit:value.total_unit,
                                carton_count:value.carton_count,
                                available:value.available
								}
							}
							else{
								return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
								in_each_carton:value.units_per_carton,
                                category_sku: value.category_sku,
								category_id:value.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:null,
                                expected_carton_count: null,
                                total_unit:null,
                                carton_count:null,
                                available:0
                            }
							}
                        })

                        this.lastDataCheck = this.productsListsData

                        if (this.current_page_is < this.getallOutboundProductsLists.last_page) {
                            this.loadMoreProducts()
                        }
                        
                        this.setAllOutboundProductsLists(this.productsListsData)
                        this.fetchProductLoading = false
                    } else {
                        this.fetchProductLoading = false
                        this.productsListsData = []
                        this.lastDataCheck = []
                    }
                } else {
                    if (from === 'Outbound') {
                        this.productsListsData = this.getAllOutboundProductsListsDropdownData
                        this.fetchProductLoading = false
                    }
                } 
            } catch(e) {
                this.notificationError(e)
            }
    },
    async loadMoreProducts() {
        if (this.current_page_is < this.getallOutboundProductsLists.last_page) {
				this.current_page_is++
        let payload =null
      if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
      payload =this.currentWarehouseSelected.id
    }
    let sendData ={
                      wid:payload,
                      page:this.current_page_is
                    }

				try {
					await this.fetchOutboundProducts(sendData)

                    if (typeof this.getallOutboundProductsLists !== 'undefined' && 
                        this.getallOutboundProductsLists !== null && 
                        typeof this.getallOutboundProductsLists.products !== 'undefined' && Array.isArray(this.getallOutboundProductsLists.products) &&
                        this.getallOutboundProductsLists.products.length > 0) {
                            
                        let newloaddata = this.getallOutboundProductsLists.products.map(value => {
                            if(value.is_from_inventory ==true){
								return {
								product_id: value.product.id,
                                id: value.product.id,
                                name: value.product.name,
                                sku: value.sku,
                                image: value.product.image,
                                description: value.product.description,
								in_each_carton:value.in_each_carton,
                                category_sku: value.category_sku,
								category_id:value.product.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:value.product,
                                expected_carton_count: value.expected_carton_count,
                                total_unit:value.total_unit,
                                carton_count:value.carton_count,
                                available:value.available
								}
							}
							else{
								return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
								in_each_carton:value.units_per_carton,
                                category_sku: value.category_sku,
								category_id:value.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:null,
                                expected_carton_count: null,
                                total_unit:null,
                                carton_count:null,
                                available:0
                            }
							}
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]

                        if (this.current_page_is < this.getallOutboundProductsLists.last_page) {
                            this.loadMoreProducts()
                        }

                        this.setAllOutboundProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        clearSearched() {
            this.search = ''
            this.setOutboundSearchedVal([])
            // document.getElementById("search-input").focus();
        },
	handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.currentWarehouseOutbounds.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentWarehouseOutbounds.length === 0) {
                isShow = true
            }

            return isShow
        },
	// for searching call api
    handleSearch() {
            if (cancel !== undefined) {
                cancel()
            }
            this.setSearchedOutboundLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { 
                    search: this.search
                }  

                this.setSearchedOutboundLoading(true)
                this.apiCall(data)
            }, 500)
        },
	apiCall(data) {
            let storePagination = this.$store.state.outbound.setCurrentTab

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

                let warehouse_id = this.currentWarehouseSelected.id

                if (storePagination == 0) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/pending`
                    passedData.tab = 'pending'
                } else if (storePagination == 1) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/floor`
                    passedData.tab = 'floor'
                } else if (storePagination == 2) {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/completed`
                    passedData.tab = 'completed'
                } else {
                    passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/cancelled`
                    passedData.tab = 'cancelled'
                }

                if (passedData.url !== '') {
                    try {
                        this.fetchSearchedOutbounds(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedOutboundLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.setOutboundSearchedVal([])
            }
        },
        removeCustomerFilter(item){
			let getIndex = this.SearchCustomerFinalArray.indexOf(item);
			this.SearchCustomerFinalArray.splice(getIndex, 1);
		},
        getNameWithoutExt(name){
			if(name !==null){
				if(name.length>20){
					return name.substring(0, 20).toLowerCase() + "...";
				}else{
					return name.toLowerCase()
				}
			}
		},
        // 6pl
        async Warehouse6PL_ProductsOnchange(val) {
            this.fetchProductLoading = true
            this.productsListsData = []
            this.lastDataCheck = []

            if (val.id !== null) {
                try {
                    let customer_id = (typeof this.getUser=='string') ? 
                        JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
                    let warehouse_customer_id = val.id
                    this.current_page_6pl_products = 1
                    let wid =null
					if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
						wid =this.currentWarehouseSelected.id
					}
                    let payload = { customer_id, warehouse_customer_id,wid, page: this.current_page_6pl_products }

                    await this.wareHouse6plProduct(payload)
                    
                    if (typeof this.getallProducts6PLists !== 'undefined' && 
                        this.getallProducts6PLists !== null && 
                        typeof this.getallProducts6PLists.products !== 'undefined' && 
                        Array.isArray(this.getallProducts6PLists.products) &&
                        this.getallProducts6PLists.products.length > 0) {
                            
                        this.productsListsData = this.getallProducts6PLists.products.map(value => {
                            if(value.is_from_inventory ==true){
								return {
								product_id: value.product.id,
                                id: value.product.id,
                                name: value.product.name,
                                sku: value.sku,
                                image: value.product.image,
                                description: value.product.description,
								in_each_carton:value.in_each_carton,
                                category_sku: value.category_sku,
								category_id:value.product.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:value.product,
								expected_carton_count: value.expected_carton_count,
								total_unit:value.total_unit,
								carton_count:value.carton_count,
                                available:value.available
								}
							}
							else{
								return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
								in_each_carton:value.units_per_carton,
                                category_sku: value.category_sku,
								category_id:value.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:null,
								expected_carton_count: null,
								total_unit:null,
								carton_count:null,
                                available:0
                            }
							}
                        })

                        this.lastDataCheck = this.productsListsData

                        if (this.current_page_6pl_products < this.getallProducts6PLists.last_page) {
                            this.loadMoreWarehouseCustomerProducts(val)
                        }
                        
                        this.setAllOutboundProductsLists(this.productsListsData)
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
                await this.fetchOutboundProductsAPiFunction('6plProvider')
            }
        },
        async loadMoreProducts6PL(val) {
            if (this.current_page_6pl_products < this.getallProducts6PLists.last_page) {
				this.current_page_6pl_products++

                let customer_id = (typeof this.getUser=='string') ? 
                    JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
                let warehouse_customer_id = val.id
                let wid =null
					if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
						wid =this.currentWarehouseSelected.id
					}

                let payload = { customer_id, warehouse_customer_id,wid, page: this.current_page_6pl_products }

				try {
					await this.wareHouse6plProduct(payload)

                    if (typeof this.getallProducts6PLists !== 'undefined' && 
                        this.getallProducts6PLists !== null && 
                        typeof this.getallProducts6PLists.products !== 'undefined' && 
                        Array.isArray(this.getallProducts6PLists.products) &&
                        this.getallProducts6PLists.products.length > 0) {
                            
                        let newloaddata = this.getallProducts6PLists.products.map(value => {
                            if(value.is_from_inventory ==true){
								return {
								product_id: value.product.id,
                                id: value.product.id,
                                name: value.product.name,
                                sku: value.sku,
                                image: value.product.image,
                                description: value.product.description,
								in_each_carton:value.in_each_carton,
                                category_sku: value.category_sku,
								category_id:value.product.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:value.product,
								expected_carton_count: value.expected_carton_count,
								total_unit:value.total_unit,
								carton_count:value.carton_count,
                                available:value.available
								}
							}
							else{
								return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
								in_each_carton:value.units_per_carton,
                                category_sku: value.category_sku,
								category_id:value.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:null,
								expected_carton_count: null,
								total_unit:null,
								carton_count:null,
                                available:0
                            }
							}
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]

                        if (this.current_page_6pl_products < this.getallProducts6PLists.last_page) {
                            this.loadMoreProducts6PL()
                        }

                        this.setAllOutboundProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
    },
    async mounted() {
        if (this.getCurrentOutboundTab !== 'undefined') {
            if (this.currentTab !== this.getCurrentOutboundTab) {
                this.currentTab = this.getCurrentOutboundTab
            }
        }let currentInventoryTabName = this.$router.history.current.query.tab

        if (this.$store.state.page.currentInventoryTab === 4 && 
            typeof currentInventoryTabName !== 'undefined' && 
            currentInventoryTabName === 'Outbound') {

        try {
            let dataWithPage = {
                id: this.currentWarehouseSelected.id,
                page: 1
            }
            await this.fetchPendingOutbounds(dataWithPage)
            await this.fetchFloorOutbounds(dataWithPage)
            await this.fetchCompletedOutbounds(dataWithPage)
            await this.fetchCancelledOutbounds(dataWithPage)
            this.fetchOutboundProductsAPiFunction('Outbound')
        } catch(e) {
            console.log(e);
        }
    }
    },
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/inventory/outbound/outboundMobileTable.scss';
.paddingZero{
	padding: 0 !important;
	border-left: none !important;
    width: 0  !important;
    min-width: 0 !important;
}
</style>