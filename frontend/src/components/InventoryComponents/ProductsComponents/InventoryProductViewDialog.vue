<template>
    <v-dialog v-model="dialogView" max-width="850px" :content-class="isWarehouse3PL == true ? 'inventory-product-dialog-view' : 'inventory-product-dialog-view custom-dialog-rightside-for-Inventory-products'"  :retain-focus="false" v-resize="onResize" scrollable>
        
        <v-card class="dialog-wrapper" :style="isWarehouse3PL ? '' : 'height:100%' ">
            <v-card-title>
                <span class="headline">
                    SKU #
                    <span class="po-num" v-if="editedItem.category_id !== null">{{ editedItem.category_id }}-</span>
                    <span class="po-num">{{ editedItem.product_sku !== null ? editedItem.product_sku : '' }}</span>
                    <!-- <button icon dark class="btn-close" @click="closeView" v-if="isMobile">
                        <v-icon>mdi-close</v-icon>
                    </button> -->
                </span>

                <div class="header-actions">
                    <div class="d-flex align-center">
                        <button @click.stop="editItem(editedItem)" class="btn-blue btn-edit" 
                            v-if="!isMobile && !isWarehouseConnected">
                            Edit
                        </button>

                        <button @click.stop="editPreferred(editedItem, false)" class="btn-white ml-2" v-if="!isMobile">
                            Edit Preferred Quantity
                        </button>
                    </div>
                    <!-- <div>
                        <button @click.stop="editItem(editedItem)" class="btn-blue btn-edit">
                            Edit
                        </button>
                    </div>

                    <button @click.stop="deleteItem(editedItem)" class="btn-white btn-delete">
                        <span v-if="!isMobile">Delete</span>
                        <span v-if="isMobile">
                            <img src="@/assets/icons/deleteIcon.svg" alt="">
                        </span>
                    </button> -->

                    <button icon dark class="btn-close" @click="closeView">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </div>
                <div class="close-btn-next-line my-1" v-if="isMobile">
                    <button @click.stop="editItem(editedItem)" class="btn-blue btn-edit">
                        Edit
                    </button>
                </div>
               
            </v-card-title>            

            <v-card-text class="mb-0">
                <div class="view-wrapper">
                    <div class="view-box-img">
                        <img :src="getImgUrl(editedItem.image)" v-bind:alt="editedItem.image">
                    </div>

                    <div class="view-box-info">
                        <div :class="isMobile ? '' : 'name-and-price-wrapper'" v-if="!isWarehouse3PLProvider">
                            <p class="item-name">{{ editedItem.name }}</p>
                            <p class="item-price" style="margin-bottom: 14px;">
                                ${{ getTotalPrice(editedItem) }}
                            </p>
                        </div>

                        <div v-if="isWarehouse3PLProvider">
                            <div class="name-and-price-wrapper">
                                <p class="item-name">{{ editedItem.name }}</p>
                                <p class="item-price">
                                    ${{ getTotalPrice(editedItem) }}
                                </p>
                            </div>

                            <p class="item-warehouse-customer">
                               <span style="color:#6D858F; font-family:'Inter-Medium', sans-serif;">Warehouse customer:</span>
                               <span style="color:#4A4A4A">{{ editedItem.warehouse_customer !== null ? editedItem.warehouse_customer : '--' }}</span>
                            </p>
                        </div>

                        <div class="view-box-other-data">
                            <v-row>
                                <v-col cols="12" sm="4" class="pr-2">
                                    <div class="item-info category">
                                        <p class="item-title">Category</p>
                                        <p class="item-data">
                                            <!-- {{ getCategoryName(editedItem.category_id) }} -->
                                            {{ editedItem.category_name }}
                                            <span v-if="editedItem.category_id !== null">({{ editedItem.category_id }})</span>
                                        </p>
                                    </div>
                                </v-col>

                                <v-col cols="12" sm="4" class="pr-2">
                                    <div class="item-info carton">
                                        <p class="item-title">In each carton</p>
                                        <p class="item-data">
                                            {{ editedItem.in_each_carton !== null ? editedItem.in_each_carton : 0 }} Units
                                        </p>
                                    </div>
                                </v-col>

                                <v-col cols="12" sm="4" class="pl-2">
                                    <div class="item-info dimensions">
                                        <p class="item-title">Carton Dimension</p>
                                        <div class="item-data-dimensions">
                                            <p>
                                                <span class="item-subtitle">L </span>
                                                <span class="item-subtitle-data">
                                                    {{
                                                        typeof editedItem.carton_dimensions.l !== 'undefined' &&
                                                        editedItem.carton_dimensions.l !== 'undefined' &&
                                                        editedItem.carton_dimensions.l !== '' && 
                                                        editedItem.carton_dimensions.l !== null ? 
                                                        editedItem.carton_dimensions.l : 0
                                                    }}{{ editedItem.carton_dimensions.uom === 'inch' ? 'in' : editedItem.carton_dimensions.uom }}
                                                </span>
                                            </p>

                                            <p>
                                                <span class="item-subtitle">W </span>
                                                <span class="item-subtitle-data">
                                                    {{
                                                        typeof editedItem.carton_dimensions.w !== 'undefined' &&
                                                        editedItem.carton_dimensions.w !== 'undefined' &&
                                                        editedItem.carton_dimensions.w !== '' && 
                                                        editedItem.carton_dimensions.w !== null ? 
                                                        editedItem.carton_dimensions.w : 0
                                                    }}{{ editedItem.carton_dimensions.uom === 'inch' ? 'in' : editedItem.carton_dimensions.uom }}
                                                </span>
                                            </p>

                                            <p>
                                                <span class="item-subtitle">H </span>
                                                <span class="item-subtitle-data">
                                                    {{
                                                        typeof editedItem.carton_dimensions.h !== 'undefined' && 
                                                        editedItem.carton_dimensions.h !== 'undefined' &&
                                                        editedItem.carton_dimensions.h !== '' &&
                                                        editedItem.carton_dimensions.h !== null ? 
                                                        editedItem.carton_dimensions.h : 0
                                                    }}{{ editedItem.carton_dimensions.uom === 'inch' ? 'in' : editedItem.carton_dimensions.uom }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </v-col>

                                <!-- <v-col cols="12" sm="2">
                                    <div class="item-info carton">
                                        <p class="item-title">Unit Weight</p>
                                        <p class="item-data">
                                            {{ typeof editedItem.unit_weight !== 'undefined' ? 
                                                (editedItem.unit_weight.value !== 'undefined' 
                                                ? editedItem.unit_weight.value : 0) : 0 
                                            }}

                                            {{ typeof editedItem.unit_weight !== 'undefined' ? 
                                                (editedItem.unit_weight.unit !== 'undefined' ? 
                                                editedItem.unit_weight.unit : 'kg') : 'kg' 
                                            }}
                                        </p>
                                    </div>
                                </v-col> -->
                            </v-row>
                        </div>
                    </div>
                </div>

                <div class="product-description-wrapper" :class="isWarehouse3PL ? 'warehouse-3pl' : ''">
                    <!-- <span>Product Description</span>
                    <p class="product-description"> {{ editedItem.description !== null ? editedItem.description : '--'}} </p> -->

                    <div class="view-box-other-data" 
                        :class="isWarehouse3PL ? getDeltaAmount(editedItem) : getDeltaAmount(editedItem)">
                        <v-row>
                            <v-col cols="6" :sm="isWarehouse3PL ? '2' : '2'" :class="isMobile ? 'py-0 my-0' :'' ">
                                <div :class="isMobile ? '' : 'item-info category'">
                                    <p class="item-title">Carton</p>
                                    <p class="item-data">
                                        {{ typeof editedItem.expected_carton_count !== 'undefined' ? getTotalCount(editedItem.expected_carton_count) : 0 }}
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="6" :sm="isWarehouse3PL ? '2' : '2'" :class="isMobile ? 'py-0 my-0' :'' ">
                                <div :class="isMobile ? '' : 'item-info carton'">
                                    <p class="item-title">Available Unit</p>
                                    <p class="item-data" style="font-family: 'Inter-SemiBold', sans-serif;">
                                        {{ typeof editedItem.total_unit !== 'undefined' ? getTotalCount(editedItem.total_unit) : 0 }}
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="6" :sm="isWarehouse3PL ? '2' : '2'" :class="isMobile ? 'py-0 my-0' :'' ">
                                <div :class="isMobile ? '' : 'item-info category'">
                                    <p class="item-title">Inbound</p>
                                    <p class="item-data">
                                        {{ typeof editedItem.inbound !== 'undefined' ? getTotalCount(editedItem.inbound) : 0 }}
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="6" :sm="isWarehouse3PL ? '2' : '2'" :class="isMobile ? 'py-0 my-0' :'' ">
                                <div :class="isMobile ? '' : 'item-info carton'">
                                    <p class="item-title">Allocated</p>
                                    <p class="item-data">
                                        {{ typeof editedItem.products_allocated !== 'undefined' ? getTotalCount(editedItem.products_allocated) : 0 }}
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="6" sm="2" :class="isMobile ? 'py-0 my-0' :'' ">
                                <div :class="isMobile ? '' : 'item-info carton'">
                                    <p class="item-title">Preferred</p>
                                    <p class="item-data d-flex align-center">
                                        {{ typeof editedItem.preferred !== 'undefined' ? getTotalCount(editedItem.preferred) : 0 }}
                                        
                                        <button class="btn-white item-preferred-button" 
                                            @click="editPreferred(editedItem, false)">
                                            <img src="@/assets/icons/edit-blue.svg" width="14px" height="14px" alt="Edit" title="Edit" >
                                        </button>
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="6" sm="2" :class="isMobile ? 'py-0 my-0' :'' ">
                                <div :class="isMobile ? '' : 'item-info carton'">
                                    <p  class="item-title">Delta</p>
                                    <p class="item-data" :class="getDeltaAmount(editedItem)">
                                        {{ typeof editedItem.delta !== 'undefined' ? getTotalCount(editedItem.delta) : 0 }}
                                    </p>
                                </div>
                            </v-col>
                        </v-row>
                    </div>
                </div>

                <v-tabs
                    v-if="!isWarehouse3PL"
                    class="inventory-breakdown-tabs mt-5"
                    v-model="currentTab">
                    <v-tab v-for="(detailorhistory,index) in InventoryBreakdownTab"
                    :key="index"
                    :class="index == 2 ? 'inventory-breakdown-inner-tab-last' : ''">
                       <span class="active-breakdown">{{detailorhistory}}</span>
                    </v-tab>
                </v-tabs>

                <div v-if="currentTab == 0 && !isWarehouse3PL" class="product-logs-wrapper">
                    <v-data-table
                        :headers="inventoryBreakdownHeader"
                        :items="InentoryBreakdownItems"
                        :items-per-page="5000"
                        @page-count="pageCount = $event"
                        mobile-breakpoint="769"
                        item-key="id"
                        class="inventory-table product-inventory-table elevation-1"
                        :class="InentoryBreakdownItems !== undefined && InentoryBreakdownItems.length === 0 ? 'no-data-table-logs' :''"
                        hide-default-footer
                        fixed-header>

                        <template v-slot:[`item.source`]="{ item }">
                            <div class="inventory-wrapper-products">
                                <div class="products-label-unit">
                                    {{ item.source !== null ? item.source : '--' }}
                                </div>
                            </div>
                        </template>

                        <template v-slot:[`item.reference`]="{ item }">
                            <div class="inventory-wrapper-products">
                                <div class="products-label-unit">
                                    {{ item.reference !== null ? item.reference : '--' }}
                                </div>
                            </div>
                        </template>

                        <template v-slot:[`item.carton_count`]="{ item }">
                            <div class="inventory-wrapper-products">
                                <div class="products-label-unit">
                                    {{ item.carton_count !== null ? item.carton_count : '--' }}
                                </div>
                            </div>
                        </template>

                        <template v-slot:[`item.total_unit`]="{ item }">
                            <div class="inventory-wrapper-products">
                                {{ item.total_unit }}
                            </div>
                        </template>

                        <template v-slot:no-data>
                            <div class="loading-wrapper" v-if="getProductInventoryBreakdownLoading">
                                <v-progress-circular
                                    :size="40"
                                    color="#0171a1"
                                    indeterminate>
                                </v-progress-circular>
                            </div>
                            <div class="no-data-wrapper" 
                                v-if="!getProductInventoryBreakdownLoading && InentoryBreakdownItems.length == 0">
                                <div class="no-data-heading">
                                    <p> This product has no inventory breakdown yet. </p>
                                </div>
                            </div>
                        </template>
                    </v-data-table>
                    <div class="button-actions-logs-wrapper">
                        <div class="button-load-more-wrapper" 
                            v-if="isShowLoadMoreInventoryBreakdown && !getProductInventoryBreakdownLoading">
                            <button class="btn-black" @click.stop="loadMoreInventoryBreakDowns">
                                Load More
                            </button>
                        </div>

                        <div class="button-load-more-wrapper" 
                            style="height: 41px;" v-if="isShowLoadMoreInventoryBreakdown && getProductInventoryBreakdownLoading">
                            <div class="loading-wrapper" v-if="getProductInventoryBreakdownLoading">
                                <v-progress-circular
                                    :size="25"
                                    color="#0171a1"
                                    indeterminate>
                                </v-progress-circular>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="currentTab == 1 || isWarehouse3PL == true" class="product-logs-wrapper">
                    <v-data-table
                        :headers="productLogHeaders"
                        :items="productsLogsItems"
                        :items-per-page="5000"
                        @page-count="pageCount = $event"
                        mobile-breakpoint="769"
                        item-key="id"
                        class="inventory-table product-inventory-table elevation-1"
                        :class="productsLogsItems !== 'undefined' && productsLogsItems.length === 0 ? 'no-data-table-logs' :''"
                        hide-default-footer
                        fixed-header>
                        <template v-slot:[`item.reference`]="{ item }">
                            <div class="inventory-wrapper-products">
                                <div class="products-label-unit">
                                    {{ item.reference !== null ? item.reference : '--' }}
                                </div>
                                <p>
                                    {{item.reason !== null && item.reason !== '' ? item.reason :'--'}}
                                </p>
                            </div>
                        </template>
                        

                        <template v-slot:[`item.created_at`]="{ item }">
                            <div class="inventory-wrapper-products">
                                <div class="products-label-unit">{{ formatDate(item.created_at) }}</div>
                            </div>
                        </template>

                        <template v-slot:[`item.carton_count`]="{ item }">
                            <div class="inventory-wrapper-products">
                                <div class="products-label-unit">
                                    {{ item.carton_count !== null ? item.carton_count : '--' }}
                                </div>
                            </div>
                        </template>

                        <template v-slot:[`item.total_unit`]="{ item }">
                            <div class="inventory-wrapper-products">
                                <div class="products-label-unit" 
                                    :class="item.color === 'green' ? 'color-green' : 'color-red'">
                                    {{ item.total_unit }}
                                </div>
                            </div>
                        </template>

                        <template v-slot:no-data>
                            <div class="loading-wrapper" v-if="getProductInventoryLogsLoading">
                                <v-progress-circular
                                    :size="40"
                                    color="#0171a1"
                                    indeterminate>
                                </v-progress-circular>
                            </div>

                            <div class="no-data-wrapper" 
                                v-if="!getProductInventoryLogsLoading && productsLogsItems.length == 0">
                                <div class="no-data-heading">
                                    <p> This product has no logs record yet. </p>
                                </div>
                            </div>
                        </template>
                    </v-data-table>

                    <div class="button-actions-logs-wrapper">
                        <div class="button-load-more-wrapper" 
                            v-if="isShowLoadMore && !getProductInventoryLogsLoading">
                            <button class="btn-black" @click.stop="loadMoreLogs">
                                Load More
                            </button>
                        </div>

                        <div class="button-load-more-wrapper" 
                            style="height: 41px;" v-if="isShowLoadMore && getProductInventoryLogsLoading">
                            <div class="loading-wrapper" v-if="getProductInventoryLogsLoading">
                                <v-progress-circular
                                    :size="25"
                                    color="#0171a1"
                                    indeterminate>
                                </v-progress-circular>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="currentTab == 2" class="product-logs-wrapper">
                    <v-data-table
                        :headers="inventoryActivityLogHeader"
                        :items="InventoryActivityLogsItems"
                        :items-per-page="5000"
                        @page-count="pageCount = $event"
                        mobile-breakpoint="769"
                        item-key="id"
                        class="inventory-table product-inventory-table elevation-1"
                        :class="InventoryActivityLogsItems !== 'undefined' && InventoryActivityLogsItems.length === 0 ? 'no-data-table-logs' :''"
                        hide-default-footer
                        fixed-header>
                        <template v-slot:[`item.updated_at`]="{ item }">
                            <div class="inventory-wrapper-products">
                                <div class="products-label-unit">{{ formatDateUpdatedAt(item.updated_at) }}</div>
                                <div style="color: #6D858F;">{{item.updated_by !== null ? item.updated_by :'--'}}</div>
                            </div>
                        </template>
                        <template v-slot:[`item.description`]="{ item }">
                            <div class="inventory-wrapper-products">
                                <div>{{item.description !== null ? item.description :'--'}}</div>
                            </div>
                        </template>

                        <template v-slot:no-data>
                            <div class="loading-wrapper" v-if="getInventoryActivityLogsLoading">
                                <v-progress-circular
                                    :size="40"
                                    color="#0171a1"
                                    indeterminate>
                                </v-progress-circular>
                            </div>

                            <div class="no-data-wrapper" 
                                v-if="!getInventoryActivityLogsLoading && InventoryActivityLogsItems.length == 0">
                                <div class="no-data-heading">
                                    <p> This product has no logs record yet. </p>
                                </div>
                            </div>
                        </template>
                    </v-data-table>
                    <div class="button-actions-logs-wrapper">
                        <div class="button-load-more-wrapper" 
                            v-if="isShowLoadMoreActivityLogs && !getInventoryActivityLogsLoading">
                            <button class="btn-black" @click.stop="loadMoreActivityLogs">
                                Load More
                            </button>
                        </div>

                        <div class="button-load-more-wrapper" 
                            style="height: 41px;" v-if="isShowLoadMoreActivityLogs && getInventoryActivityLogsLoading">
                            <div class="loading-wrapper" v-if="getInventoryActivityLogsLoading">
                                <v-progress-circular
                                    :size="25"
                                    color="#0171a1"
                                    indeterminate>
                                </v-progress-circular>
                            </div>
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>

        <ConfirmDialog :dialogData.sync="editPreferredQtyDialog" :className="'with-header-dialog'">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <h2 class="mb-0"> Edit Preferred Quantity </h2>
                    <v-btn icon dark class="btn-close" @click="closeEditPreferred">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </div>
            </template>

            <template v-slot:dialog_content>
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="preferred-qty-wrapper">
                        <p class="item-title">PREFERRED QUANTITY</p>
                        <v-text-field
                            type="text"
                            class="text-fields" 
                            placeholder="Enter preferred quantity" 
                            outlined
                            hide-details="auto"
                            :rules="rules"
                            v-model="preferredUnitQty" />
                    </div>
                </v-form>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="editPreferred(currentSelectedInventory, true)" text 
                    :disabled="getEditPreferredQtyLoading">
                    <span v-if="!getEditPreferredQtyLoading">Save</span>
                    <span v-if="getEditPreferredQtyLoading">Saving...</span>
                </v-btn>

                <v-btn class="btn-white" text @click="closeEditPreferred" :disabled="getEditPreferredQtyLoading">
                    Cancel
                </v-btn>
            </template>
        </ConfirmDialog> 
    </v-dialog>
</template>

<script>
import _ from 'lodash'
import { mapActions, mapGetters } from 'vuex'
import globalMethods from '../../../utils/globalMethods'
import moment from 'moment'
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue'

export default {
    name: 'InventoryProductViewDialog',
    props: [
        'editedItemData', 
        'dialogViewData', 
        'categoryLists', 
        'isWarehouse3PL', 
        'productInventoryLogs',
        'productInventoryBreakdown', 
        'isWarehouseConnected',
        'currentWarehouseSelected',
        'getCurrentPage',
        'activityLogsData'
    ],
    components: {
        ConfirmDialog
    },
    data: () => ({
        valid: true,
        productLogHeaders: [
            {
				text: 'DATE',
				align: 'start',
				sortable: false,
				value: 'created_at',
				fixed: true,
				width: "12%"
			},
			{ 
				text: 'REFERENCE',
				align: 'start',
				sortable: false,
				value: 'reference',
				fixed: true,
				width: "15%" 
			},
			{ 
				text: 'CARTON',
				align: 'end',
				sortable: false,
				value: 'carton_count',
				fixed: true,
				width: "12%" 
			},
			{ 
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "12%" 
			},
            { 
				text: 'BALANCE',
				align: 'end',
				sortable: false,
				value: 'balance',
				fixed: true,
				width: "12%" 
			},
        ],
        inventoryBreakdownHeader:[
            {
                text:'Source',
                align: 'start',
				sortable: false,
				value: 'source',
				fixed: true,
				width: "30%" 
            },
            {
                text:'Reference',
                align: 'end',
				sortable: false,
				value: 'reference',
				fixed: true,
				width: "25%" 
            },
            {
                text:'Carton',
                align: 'end',
				sortable: false,
				value: 'carton_count',
				fixed: true,
				width: "20%" 
            },
            {
                text:'Units',
                align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "20%" 
            },
            
        ],
        inventoryActivityLogHeader:[
            {
                text:'Updated At & By',
                align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: "25%" 
            },
            {
                text:'Update Type',
                align: 'start',
				sortable: false,
				value: 'update_type',
				fixed: true,
				width: "25%" 
            },
            {
                text:'Description',
                align: 'start',
				sortable: false,
				value: 'description',
				fixed: true,
				width: "50%" 
            },     
        ],
        InventoryBreakdownTab:['Inventory Breakdown','History','Activity Log'],
        currentTab: 0,
        isMobile: false,
        editPreferredQtyDialog: false,
        currentSelectedInventory: null,
        rules: [
            // (v) => !!v || "Input is required."
            (v) => v !== '' || "Input is required."
        ],
    }),
    computed: {
        ...mapGetters({
            getProducts: 'products/getProducts',
            getProductInventoryLogs: 'productInventories/getProductInventoryLogs',
            getProductInventoryLogsLoading: 'productInventories/getProductInventoryLogsLoading',
            getProductInventoryBreakdownLoading:'productInventories/getProductInventoryBreakdownLoading',
            getProductInventoryBreakdown:'productInventories/getProductInventoryBreakdown',
            getEditPreferredQtyLoading: 'productInventories/getEditPreferredQtyLoading',
            getInventoryActivityLogsLoading:'productInventories/getInventoryActivityLogsLoading',
            getInventoryActivityLogs:'productInventories/getInventoryActivityLogs'
        }),
        dialogView: {
            get () {
                return this.dialogViewData
            },
            set (value) {
                this.$emit('update:dialogViewData', value)
            }
        },
        editedItem: {
            get () {
                let value = { ...this.editedItemData }

                if (value !== null) {
                    if (value.carton_dimensions === null) {
                        value.carton_dimensions = {
                            l: 0,
                            h: 0,
                            w: 0,
                            uom: 'cm'
                        }
                    }
                }

                return value
                // return this.editedItemData
            },
            set (value) {
                console.log(value);
            }
        },
        productsLogsItems() {
            return this.productInventoryLogs
        },
        productLogsData() {
            let data = null

            if (typeof this.getProductInventoryLogs !== 'undefined' && this.getProductInventoryLogs !== null) {
                data = this.getProductInventoryLogs
            }

            return data
        },
        isShowLoadMore() {
            let show = false

            if (typeof this.productLogsData !== 'undefined' && this.productLogsData !== null) {
                if (typeof this.productLogsData.last_page !== 'undefined') {
                    if (this.productLogsData.current_page !== this.productLogsData.last_page) {
                        show = true
                    }
                }
            }

            return show
        },
        InentoryBreakdownItems() {
            return this.productInventoryBreakdown
        },
        InventoryBreakdownData() {
            let data = null

            if (typeof this.getProductInventoryBreakdown !== 'undefined' && this.getProductInventoryBreakdown !== null) {
                data = this.getProductInventoryBreakdown
            }

            return data
        },
        isShowLoadMoreInventoryBreakdown() {
            let show = false

            if (typeof this.InventoryBreakdownData !== 'undefined' && this.InventoryBreakdownData !== null) {
                if (typeof this.InventoryBreakdownData.last_page !== 'undefined') {
                    if (this.InventoryBreakdownData.current_page !== this.InventoryBreakdownData.last_page) {
                        show = true
                    }
                }
            }

            return show
        },
        activityLogsDataForShowMoreData() {
            let data = null

            if (typeof this.getInventoryActivityLogs !== 'undefined' && this.getInventoryActivityLogs !== null) {
                data = this.getInventoryActivityLogs
            }

            return data
        },
        isShowLoadMoreActivityLogs() {
            let show = false

            if (typeof this.activityLogsDataForShowMoreData !== 'undefined' && this.activityLogsDataForShowMoreData !== null) {
                if (typeof this.activityLogsDataForShowMoreData.last_page !== 'undefined') {
                    if (this.activityLogsDataForShowMoreData.current_page !== this.activityLogsDataForShowMoreData.last_page) {
                        show = true
                    }
                }
            }

            return show
        },
        InventoryActivityLogsItems(){
            return this.activityLogsData
        },
        isWarehouse3PLProvider() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 6) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        preferredUnitQty: {
            get() {
                let value = 0

                if (this.currentSelectedInventory !== null && this.currentSelectedInventory.preferred !== null && 
                    this.currentSelectedInventory.preferred !== '') {
                    value = this.currentSelectedInventory.preferred
                }

                return value
            },
            set(value) {
                this.currentSelectedInventory.preferred = value
            }
        }
    },
    methods: {
        ...mapActions({
            setProduct: 'products/setProduct',
            fetchProductInventoryLogs: 'productInventories/fetchProductInventoryLogs',
            updatePreferredQtyAction: 'productInventories/updatePreferredQtyAction',
            fetchProductInventories: 'productInventories/fetchProductInventories',
            fetchProductInventories3pl: 'productInventories/fetchProductInventories3pl',
        }),
        ...globalMethods,
        onResize() {
	  		if (window.innerWidth < 1024) {
				this.isMobile = true;
	  		} else {
				this.isMobile = false;
	  		}
		},
        formatDate(item) {
            if (typeof item !== 'undefined' && item !== null) {
                return moment(item).format('MMM DD, YYYY') 
            } else {
                return 'N/A'
            }
        },
        formatDateUpdatedAt(item){
            if (typeof item !== 'undefined' && item !== null) {
                return moment(item).format("hh:mm A, DD MM, YYYY") 
            } else {
                return 'N/A'
            }
        },
        closeView() {
            this.$emit('close')
            this.currentTab = 0
        },
        getImgUrl(pic) {
			if (pic !== 'undefined' && pic !== null) {
				return pic
			} else {
				return require('../../../assets/icons/default-product-icon.svg')
			}
		},
        getCategoryName(id) {
            if(this.categoryLists.length !== 0 && id) {
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
        editItem(item) {
            this.$emit('update:dialogViewData', false)
            setTimeout(() => {
                this.$emit('editItem', item)
            }, 100)
        },
        deleteItem(item) {
            this.$emit('update:dialogViewData', false)
            setTimeout(() => {
                this.$emit('deleteItem', item)
            },100)
        },
        parseAmount(amount) {
            if (amount !== 'undefined' && amount !== null && amount !== '') {
                return parseFloat(amount).toFixed(2) + '%'
            }
        },
        parseCodes(item) {
            if (item !== '' && item !== null) {
                let parsedCodes = JSON.parse(item)

                return parsedCodes
            } else {
                return '--'
            }
        },
        getTotalPrice(item) {
            if (typeof item.unit_price !== 'undefined' && item.unit_price !== null) {
                let total = (item.unit_price).toFixed(2)                

                return this.addCommaToNum(total)
            } else {
                return 0
            }
        },
        getTotalCount(item) {
            if (typeof item !== 'undefined' && item !== null) {
                return this.addCommaToNum(item)
            } else {
                return 0
            }
        },
        getDeltaAmount(item) {
            if (typeof item !== 'undefined' && item !== null && typeof item.delta !== 'undefined') {
                if (item.delta < 0) {
                    return 'bg-negative'
                } else if (item.delta > 0) {
                    return 'bg-warning'
                } else if (item.delta === 0) {
                    return 'bg-positive'
                }
            }
        },
        loadMoreLogs() {
            this.$emit('loadMoreLogs')
        },
        loadMoreInventoryBreakDowns(){
            this.$emit('loadMoreInventoryBreakDowns')
        },
        loadMoreActivityLogs(){
            this.$emit('loadMoreActivityLogs')
        },
        async editPreferred(item, isConfirm) {
            this.editPreferredQtyDialog = true

            if (!isConfirm) {             
                this.currentSelectedInventory = item
                this.closeView()
            } else {
                let payload = {
                    inventory_product_id: this.currentSelectedInventory.id,
                    preferred: parseInt(this.currentSelectedInventory.preferred)
                }

                try {
                    if (this.$refs.form.validate()) {
                        await this.updatePreferredQtyAction(payload)
                        this.notificationCustom('Preferred has been updated.')
                        this.closeEditPreferred()

                        let dataWithPage = { 
                            page: this.getCurrentPage,
                            id: this.currentWarehouseSelected.id
                        }

                        if (!this.isWarehouse3PL) {
                            await this.fetchProductInventories(dataWithPage)
                        } else {                  
                            await this.fetchProductInventories3pl(dataWithPage)
                            this.callProductsForAddInventory('Inventory')
                        }
                    }                    
                } catch(e) {
                    this.notificationCustom(e)
                }
            }            
        },
        closeEditPreferred() {
            this.editPreferredQtyDialog = false
            this.currentSelectedInventory = null
        }
    },
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/inventory/product/inventoryProductView.scss';
@import '../../../assets/scss/pages_scss/dialog/globalDialog.scss';
.custom-dialog-rightside-for-Inventory-products.v-dialog:not(.v-dialog--fullscreen) {
    max-height: 100%;
    height: 100%;
    right: 0 !important;
    margin: 0%;
    position: absolute !important;
    border-radius: 0 !important;
}
.theme--light.v-table tbody tr:not(:last-child) {
    border-bottom: none;
}
</style>
