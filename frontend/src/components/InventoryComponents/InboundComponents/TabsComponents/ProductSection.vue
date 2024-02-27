<template>
    <div class="inbound-product-section">
        <!-- :headers="!isWarehouse3PL ? productsHeader : productsHeader3PL" -->
        <v-data-table
            :headers="headersComputed"
            :items="productsData.inbound_products"
            item-key="id"
            :items-per-page="itemsPerPage"
            class="elevation-1 inbound-view-products"
            v-bind:class="{
                'no-data-table': (typeof productsData.inbound_products !== 'undefined' && productsData.inbound_products !== null && productsData.inbound_products.length === 0),
                'no-checkbox': !removeCheckboxIfAllIsReceived
            }"
            mobile-breakpoint="769"
            hide-default-footer
            :show-select="removeCheckboxIfAllIsReceived"
            v-model="productsSelected"
            fixed-header>
            <!-- :class="(typeof productsData.inbound_products !== 'undefined' && productsData.inbound_products !== null && productsData.inbound_products.length > 0) ? '' : 'no-data-table'" -->

            <template v-slot:no-data>
                <div class="no-data-wrapper" 
                v-if="typeof productsData.inbound_products !== 'undefined' && 
                    productsData.inbound_products !== null && 
                    productsData.inbound_products.length == 0">

                    <div class="no-data-heading">
                        <div>
                            <h3> No Products </h3>
                            <p> You can start adding products by editing this Inbound. </p>
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.product.category_sku`]="{ item }">
                <div v-if="!isMobile">
                    <p class="mb-0">
                        {{ 
                            item.product.category_sku !== '' && 
                            item.product.category_sku !== null ? 
                            item.product.category_sku : '--'
                        }}
                    </p>

                    <p class="mb-0" style="color: #6D858F;">
                        {{ item.product_name }}
                    </p>
                </div>

                <div class="mobile-content-wrapper" v-if="isMobile">
                    <div class="mobile-content">
                        <span>SKU# {{ 
                            item.product.category_sku !== '' && 
                            item.product.category_sku !== null ? 
                            item.product.category_sku : '--'
                        }}</span>
                    </div>

                    <div class="actions-wrapper">
                        <button class="btn-status btn-white receive-buttons" 
                            :class="item.status" 
                            @click.stop="receiveProduct(item, 'receive')"
                            v-if="item.status === null" 
                            :disabled="(inboundStatus === 'pending' && !isWarehouse3PL) || inboundStatus === 'cancelled'">
                            <span class="btn-content">
                                <span>Receive</span>
                            </span>                        
                        </button>

                        <button class="btn-status btn-white" :class="item.status" v-if="isShowReceivedWithCheck(item)">
                            <img src="../../../../assets/icons/checkMark.png" class="mr-1" width="12px" height="12px">
                            <span class="btn-content">
                                <span>Received</span>
                            </span>                        
                        </button>

                        <v-menu bottom left offset-y content-class="outbound-lists-menu" v-if="getCurrentInboundStatusItem(item)">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn class="btn-white" icon v-bind="attrs" v-on="on">
                                    <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists">
                                <v-list-item @click="receiveProduct(item, 'update')">
                                    <v-list-item-title>
                                        Update Count
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.storable_unit`]="{ item }">
                <div>
                    <div class="item-information">
                        <div class="item storable-units">
                            <span class="mb-0" v-for="(unit, index) in item.storable_units" :key="index">
                                <span>{{ unit.label }}</span>
                                <template v-if="index + 1 < item.storable_units.length">, </template>
                            </span>
                        </div>
                    </div>

                    <div class="item-information">
                        <div class="mb-0" style="color: #6D858F;">
                            {{ getLocation(item.storable_units) }}
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.carton_count`]="{ item }">
                <div class="item-information counts" v-if="!isMobile">
                    <div class="item">
                        <span v-if="item.shipping_unit === 'carton'">                            
                            <span v-if="item.status !== 'recieved'">
                                {{ typeof item.expected_carton_count !== 'undefined' && item.expected_carton_count !== null 
                                    ? item.expected_carton_count : 0 
                                }}
                            </span>

                            <span v-if="item.status === 'recieved'">
                                {{ typeof item.actual_carton_count !== 'undefined' && item.actual_carton_count !== null 
                                    ? item.actual_carton_count : 0 
                                }}
                            </span>
                        </span>

                        <span v-if="item.shipping_unit === 'single_item'"> -- </span>

                        <div v-if="item.shipping_unit === 'carton'">
                            <div class="item-information-images" v-if="item.status === 'recieved'">
                                <v-tooltip bottom open-delay="300" content-class="over-undershipped-tip top-arrow"
                                    v-if="item.actual_carton_count !== item.expected_carton_count">

                                    <template v-slot:activator="{ on }">
                                        <img src='@/assets/icons/undershipped-icon.svg' width='18px' height='18px' v-on="on">
                                    </template>

                                    <span>
                                        {{ 'Expected Cartons: ' + item.expected_carton_count }}
                                    </span>
                                </v-tooltip>
                                

                                <img src='@/assets/icons/circle-products-filled.svg' width='18px' height='18px'
                                    v-if="item.actual_carton_count === item.expected_carton_count">
                            </div>

                            <div class="item-information-images" v-else>
                                <img src='@/assets/icons/circle-products-unfilled.svg' width='18px' height='18px'>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mobile-content-wrapper dgrid" v-if="isMobile">
                    <div class="item-carton-mobile">
                        <p>CARTON</p>

                        <div class="item-carton-mobile-content">
                            <span v-if="item.shipping_unit === 'carton'">                            
                                <span v-if="item.status !== 'recieved'">
                                    {{ typeof item.expected_carton_count !== 'undefined' && item.expected_carton_count !== null 
                                        ? item.expected_carton_count : 0 
                                    }}
                                </span>

                                <span v-if="item.status === 'recieved'">
                                    {{ typeof item.actual_carton_count !== 'undefined' && item.actual_carton_count !== null 
                                        ? item.actual_carton_count : 0 
                                    }}
                                </span>
                            </span>

                            <span v-if="item.shipping_unit === 'single_item'"> -- </span>

                            <div v-if="item.shipping_unit === 'carton'">
                                <div class="item-information-images" v-if="item.status === 'recieved'">
                                    <v-tooltip bottom open-delay="300" content-class="over-undershipped-tip top-arrow"
                                        v-if="item.actual_carton_count !== item.expected_carton_count">

                                        <template v-slot:activator="{ on }">
                                            <img src='@/assets/icons/undershipped-icon.svg' width='18px' height='18px' v-on="on">
                                        </template>

                                        <span>
                                            {{ 'Expected Cartons: ' + item.expected_carton_count }}
                                        </span>
                                    </v-tooltip>
                                    

                                    <img src='@/assets/icons/circle-products-filled.svg' width='18px' height='18px'
                                        v-if="item.actual_carton_count === item.expected_carton_count">
                                </div>

                                <div class="item-information-images" v-else>
                                    <img src='@/assets/icons/circle-products-unfilled.svg' width='18px' height='18px'>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="item-carton-mobile">
                        <p>IN EACH</p>

                        <div class="item-carton-mobile-content">
                            <span v-if="item.shipping_unit === 'carton'">
                                <span v-if="item.status !== 'recieved'">
                                    {{ typeof item.in_each_carton !== 'undefined' && item.in_each_carton !== null 
                                        ? item.in_each_carton : 0 
                                    }}
                                </span>

                                <span v-if="item.status === 'recieved'">
                                    {{ typeof item.actual_in_each_carton !== 'undefined' && item.actual_in_each_carton !== null 
                                        ? item.actual_in_each_carton : 0 
                                    }}
                                </span>
                            </span>

                            <span v-if="item.shipping_unit === 'single_item'"> -- </span>
                            
                            <div v-if="item.shipping_unit === 'carton'">
                                <div class="item-information-images" v-if="item.status === 'recieved'">                            
                                    <img src='@/assets/icons/undershipped-icon.svg' width='18px' height='18px' 
                                        v-if="item.in_each_carton !== item.actual_in_each_carton">

                                    <img src='@/assets/icons/circle-products-filled.svg' width='18px' height='18px'
                                        v-if="item.in_each_carton === item.actual_in_each_carton">
                                </div>

                                <div class="item-information-images" v-else>
                                    <img src='@/assets/icons/circle-products-unfilled.svg' width='18px' height='18px'>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="item-carton-mobile">
                        <p>UNIT</p>

                        <div class="item-carton-mobile-content">
                            <span v-if="item.status !== 'recieved'">
                                {{ typeof item.expected_total_unit !== 'undefined' && item.expected_total_unit !== null 
                                    ? item.expected_total_unit : 0 
                                }}
                            </span>

                            <span v-if="item.status === 'recieved'">
                                {{ typeof item.actual_total_unit !== 'undefined' && item.actual_total_unit !== null 
                                    ? item.actual_total_unit : 0 
                                }}
                            </span>

                            <div class="item-information-images" v-if="item.status === 'recieved'">
                                <v-tooltip bottom open-delay="300" content-class="over-undershipped-tip top-arrow" 
                                    v-if="item.actual_total_unit !== item.expected_total_unit">
                                    <template v-slot:activator="{ on }">
                                        <img src='@/assets/icons/undershipped-icon.svg' width='18px' height='18px' v-on="on">
                                    </template>

                                    <span>
                                        {{ 'Expected Units: ' + item.expected_total_unit }}
                                    </span>
                                </v-tooltip>

                                <img src='@/assets/icons/circle-products-filled.svg' width='18px' height='18px'
                                    v-if="item.actual_total_unit === item.expected_total_unit">
                            </div>

                            <div class="item-information-images" v-else>
                                <img src='@/assets/icons/circle-products-unfilled.svg' width='18px' height='18px'>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.in_each_carton`]="{ item }">
                <div class="item-information counts">
                    <div class="item">
                        <span v-if="item.shipping_unit === 'carton'">
                            <span v-if="item.status !== 'recieved'">
                                {{ typeof item.in_each_carton !== 'undefined' && item.in_each_carton !== null 
                                    ? item.in_each_carton : 0 
                                }}
                            </span>

                            <span v-if="item.status === 'recieved'">
                                {{ typeof item.actual_in_each_carton !== 'undefined' && item.actual_in_each_carton !== null 
                                    ? item.actual_in_each_carton : 0 
                                }}
                            </span>
                        </span>

                        <span v-if="item.shipping_unit === 'single_item'"> -- </span>
                        
                        <div v-if="item.shipping_unit === 'carton'">
                            <div class="item-information-images" v-if="item.status === 'recieved'">                            
                                <img src='@/assets/icons/undershipped-icon.svg' width='18px' height='18px' 
                                    v-if="item.in_each_carton !== item.actual_in_each_carton">

                                <img src='@/assets/icons/circle-products-filled.svg' width='18px' height='18px'
                                    v-if="item.in_each_carton === item.actual_in_each_carton">
                            </div>

                            <div class="item-information-images" v-else>
                                <img src='@/assets/icons/circle-products-unfilled.svg' width='18px' height='18px'>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.units`]="{ item }">
                <div class="item-information counts">
                    <div class="item">
                        <span v-if="item.status !== 'recieved'">
                            {{ typeof item.expected_total_unit !== 'undefined' && item.expected_total_unit !== null 
                                ? item.expected_total_unit : 0 
                            }}
                        </span>

                        <span v-if="item.status === 'recieved'">
                            {{ typeof item.actual_total_unit !== 'undefined' && item.actual_total_unit !== null 
                                ? item.actual_total_unit : 0 
                            }}
                        </span>

                        <div class="item-information-images" v-if="item.status === 'recieved'">
                            <v-tooltip bottom open-delay="300" content-class="over-undershipped-tip top-arrow" 
                                v-if="item.actual_total_unit !== item.expected_total_unit">
                                <template v-slot:activator="{ on }">
                                    <img src='@/assets/icons/undershipped-icon.svg' width='18px' height='18px' v-on="on">
                                </template>

                                <span>
                                    {{ 'Expected Units: ' + item.expected_total_unit }}
                                </span>
                            </v-tooltip>

                            <img src='@/assets/icons/circle-products-filled.svg' width='18px' height='18px'
                                v-if="item.actual_total_unit === item.expected_total_unit">
                        </div>

                        <div class="item-information-images" v-else>
                            <img src='@/assets/icons/circle-products-unfilled.svg' width='18px' height='18px'>
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.notes`]="{ item }">
                <div v-if="isWarehouse3PLProvider">
                    <div v-if="item.instructions !== null && item.notes === null">
                        {{ item.instructions }}
                    </div>

                    <div v-if="item.instructions !== null && item.notes !== null">
                        {{ item.notes }}
                    </div>
                </div>

                <div v-else> {{ item.notes }} </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions-wrapper mr-2" v-if="!isWarehouseConnected">
                    <button class="btn-status btn-white receive-buttons" 
                        :class="item.status" 
                        @click.stop="receiveProduct(item, 'receive')"
                        v-if="item.status === null && inboundStatus !== 'cancelled' && inboundStatus !== 'rejected'" 
                        :disabled="
                            (inboundStatus === 'pending' && !isWarehouse3PL) || 
                            (inboundStatus === 'receive-pending' && isWarehouse3PLProvider)">
                        <span class="btn-content">
                            <span>Receive</span>
                        </span>                        
                    </button>

                    <button class="btn-status btn-white" :class="item.status" v-if="isShowReceivedWithCheck(item)">
                        <img src="../../../../assets/icons/checkMark.png" class="mr-1" width="12px" height="12px">
                        <span class="btn-content">
                            <span>Received</span>
                        </span>                        
                    </button>

                    <v-menu bottom left offset-y content-class="outbound-lists-menu" v-if="getCurrentInboundStatusItem(item)">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn class="btn-white" icon v-bind="attrs" v-on="on">
                                <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <v-list-item @click="receiveProduct(item, 'update')">
                                <v-list-item-title>
                                    Update Count
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>
        </v-data-table>

        <ReceiveProductDialog 
            :dialogData.sync="dialogReceiveProduct"
            :editedItemData.sync="receiveProductData"
            @close="closeProductReceive"
            :loading="false"
            :productLists="productLists"
            :linkData="linkData"
            :multiple="false"
            :title="modal_title"
            :inboundStatus="inboundStatus"
            :isWarehouse3PL="isWarehouse3PL"
            :undershipped="undershipped"
            :isWarehouse3PLProvider="isWarehouse3PLProvider" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import _ from 'lodash'
import ReceiveProductDialog from '../Dialogs/ReceiveProductDialog.vue'
import globalMethods from '../../../../utils/globalMethods'

export default {
    name: 'ProductSection',
    props: [
        'inboundProductsData', 
        'isMobile', 
        'warehouse_id', 
        'productLists', 
        'linkData', 
        'selected', 
        'undershipped_val', 
        'isWarehouse3PL',
        'isFromInventory',
        'isWarehouseConnected',
        'isWarehouse3PLProvider'
    ],
    components: {
        ReceiveProductDialog
    },
    data: () => ({
        defaultProductsHeader: [
            {
				text: 'SKU & NAME',
				align: 'start',
				sortable: false,
				value: 'product.category_sku',
				fixed: true,
				width: "25%"
			},
            { 
				text: 'STORABLE UNIT',
				align: 'start',
				sortable: false,
				value: 'storable_unit',
				fixed: true,
				width: "25%"
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
				text: 'IN EACH',
				align: 'end',
				sortable: false,
				value: 'in_each_carton',
				fixed: true,
				width: "12%"
			},
            { 
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'units',
				fixed: true,
				width: "12%"
			},
            { 
				text: 'NOTE',
				align: 'start',
				sortable: false,
				value: 'notes',
				fixed: true,
				width: "25%"
			},
			{ 
				text: '', 
				align: 'center',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "14%"
			},
        ],
        current_page: 1,
        dialogReceiveProduct: false,
        receiveProductData: [],
        modal_title: 'receive',
        itemsPerPage: 500
    }),
    computed: {
        ...mapGetters({
            getCategories: 'category/getCategories',
            getProducts: 'products/getProducts',
            poBaseUrlState: 'products/poBaseUrlState',
        }),
        productsData: {
            get() {
                let newProductsData = []

                if (typeof this.inboundProductsData !== 'undefined' && this.inboundProductsData !== null) {
                    newProductsData = this.inboundProductsData
                }

                return newProductsData
            },
            set(value) {
                this.$emit('update:inboundProductsData', value)
            }
        },
        inboundStatus: {
            get() {
                let inbound_status = ''

                if (typeof this.inboundProductsData !== 'undefined' && this.inboundProductsData !== null) {
                    inbound_status = this.inboundProductsData.inbound_status
                }

                return inbound_status
            },
            set(value) {
                this.$emit('update:inboundProductsData', value)
            }
        },
        productsSelected: {
            get() {
                return this.selected
            },
            set(value) {
                this.$emit('update:selected', value)
            }
        },
        undershipped() {
            let val = 0

            if (typeof this.inboundProductsData !== 'undefined' && this.inboundProductsData !== null) {
                let undershipped_val = this.inboundProductsData.is_undershipped

                val = undershipped_val
            }

            return val
        },
        removeCheckboxIfAllIsReceived() {
            let show = false

            if (this.inboundStatus !== 'cancelled' && this.inboundStatus !== 'rejected') {
                if (!this.isWarehouseConnected) {
                    if (typeof this.productsData !== 'undefined' && this.productsData !== null) {
                        if (typeof this.productsData.inbound_products !== 'undefined' &&  this.productsData.inbound_products.length > 0) {
                            let findProductIndex = _.findIndex(this.productsData.inbound_products, e => (e.status !== 'recieved'))
                            if (findProductIndex > -1) {
                                show = true
                            } else {
                                show = false
                            }
                        }
                    }
                }
            }

            return show
        },
        headersComputed() {
            let defaultHeaders = this.defaultProductsHeader

            if (this.isWarehouse3PL) {
                defaultHeaders = defaultHeaders.filter(v => {
                    return v.text !== 'STORABLE UNIT'
                })
            } else {
                if (this.isWarehouse3PLProvider) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        if (v.text === 'STORABLE UNIT') {
                            v.width = '15%'
                        }

                        if (v.text === 'NOTE') {
                            v.text = 'INSTRUCTIONS'
                        }
                        
                        return defaultHeaders
                    })
                } else {
                    defaultHeaders = defaultHeaders.filter(v => {
                        if (v.text === 'STORABLE UNIT') {
                            v.width = '15%'
                        }
                        return defaultHeaders
                    })
                }
            }

            return defaultHeaders
        }
    },
    methods: {
        ...mapActions({}),
        ...globalMethods,
        getImgUrl(pic) {
            let imageUrl = 'https://po.shifl.com/storage/'

            if (pic !== 'undefined' && pic !== null) {
                if (pic.includes(imageUrl) !== 'undefined' && !pic.includes(imageUrl)) {
                    let newImage = imageUrl + pic
                    return newImage
                } else {
                    return pic
                }
            } else {
                return require('@/assets/icons/circle-products-unfilled.svg')
            }
        },
        getCategoryName(id) {
            if(typeof this.getCategories !== 'undefined' && this.getCategories !== null 
                && this.getCategories.length !== 'undefined' && this.getCategories.length !== 0 && id) {
                let findSizeValue = _.find(this.getCategories, (e) => (e.id == id))

                if (typeof findSizeValue !== 'undefined') {
                    if (findSizeValue.name !== 'undefined') {
                        return findSizeValue.name
                    }
                } else {
                    return ''
                }
            }
		},
        receiveProduct(item, toDo) {
            this.dialogReceiveProduct = true

            let findProduct = _.findIndex(this.receiveProductData, e => (item.id === e.id))  

            if (findProduct === -1) {
                this.receiveProductData.push({
                    id: item.id,
                    product_id: item.product_id,
                    name: item.product.name,
                    shipping_unit: item.shipping_unit,
                    carton_count: item.shipping_unit == 'single_item' ? null : item.expected_carton_count,
                    total_unit: item.expected_total_unit,
                    expected_carton_count: item.shipping_unit == 'single_item' ? null : item.expected_carton_count,
                    expected_total_unit: item.expected_total_unit,
                    in_each_carton: item.in_each_carton,
                    product_sku: item.product.sku,
                    actual_carton_count: item.actual_carton_count,
                    actual_total_unit: item.actual_total_unit,
                    storable_units: item.storable_units,
                    remaining_carton_count: item.remaining_carton_count,
                    remaining_total_unit: item.remaining_total_unit,
                    notes: item.notes !== null ? item.notes : '',
                    noteError: false
                })                
            }

            this.modal_title = toDo
        },
        closeProductReceive() {
            this.dialogReceiveProduct = false
            this.receiveProductData = []
        },
        getLocation(item) {
            let val = ''

            if (typeof item !== 'undefined' && item !== null) {
                if (item.length !== 'undefined' && Array.isArray(item) && item.length > 1) {
                    val = 'Multiple'
                } else if (item.length === 1) {
                    item.map(v => {
                        let location = v.location

                        if (location !== null) {
                            if (location.type === 'storage') {
                                let shelf = typeof location.shelf !== 'undefined' && location.shelf !== null ? location.shelf : ''
                                let row = typeof location.row !== 'undefined' && location.row !== null ? location.row : ''
                                let rack = typeof location.rack !== 'undefined' && location.rack !== null? location.rack : ''

                                val = row + rack + shelf
                            } else {
                                val = location.gate_name
                            }
                        } else {
                            val = '--'
                        }
                    })
                } else {
                    val = '--'
                }
            } else {
                val = '--'
            }

            return val
        },
        checkStorableUnitsData(item, isShow) {
            if (item.storable_units !== 'undefined' && item.storable_units !== null) {
                if (Array.isArray(item.storable_units) && item.storable_units.length > 0) {
                    if (isShow) {
                        this.notificationError('This product has already been used to build a storable unit.')
                    } else {
                        return true
                    }
                }
            }
        },
        getCurrentInboundStatusItem(item) {
            let show = false

            if (!this.isWarehouse3PL) {
                if (item !== null) {
                    if (item.status !== null && item.status !== 'pending' && 
                        this.inboundStatus !== 'completed' && this.inboundStatus !== 'cancelled') {
                        show = true
                    }
                }
            } else {
                if (this.inboundStatus !== 'cancelled') {
                    if (!this.isFromInventory) {
                        if (item.status !== null) {
                            show = true
                        }
                    } else {
                        show = false
                    }                    
                } else {
                    show = false
                }
            }

            return show
        },
        isShowReceivedWithCheck(item) {
            let show = false

            if (!this.isWarehouse3PL) {
                if (item.status === 'recieved') {
                    show = true
                }
            } else {
                if (item.status === 'recieved') {
                    if (this.inboundStatus === 'completed') {
                        show = false
                    } else {
                        show = true
                    }
                }
            }

            return show
        },
        // if product count will not allow to be updated once one of the storable units has been placed
        getCurrentInboundDisabled(item) {
            let show = false

            if (item !== null) {
                if (item.storable_units !== null && item.storable_units.length !== 0) {
                    let findPlacedStorable = _.findIndex(item.storable_units, e => (e.status === 'placed'))

                    if (findPlacedStorable > -1) {
                        show = true
                    } else {
                        show = false
                    }
                } else {
                    show = false
                }
            } else {
                show = false
            }

            return show
        },
    },
    async mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "inventory");
    },
    updated() {}
}
</script>

<style lang="scss">
@import './scss/productSection.scss';

/* @import '../../../assets/scss/buttons.scss';
@import '../../../assets/scss/pages_scss/inbound/inboundView.scss'; */

.v-tooltip__content.over-undershipped-tip {
    font-size: 12px;

    &.has-none {
        display: none !important;
    }    

    &.menuable__content__active {
        background-color: #4A4A4A !important;

        &.top-arrow {
            overflow: inherit !important;
            z-index: 20;
            opacity: 1 !important;

            &::before {
                content: "";
                position: absolute;
                width: 0;
                height: 0;
                border-left: 10px solid transparent;
                border-right: 10px solid transparent;
                border-bottom: 10px solid #4A4A4A;
                top: -10px;
                left: 45%;
            }            
        }
    }
}
</style>