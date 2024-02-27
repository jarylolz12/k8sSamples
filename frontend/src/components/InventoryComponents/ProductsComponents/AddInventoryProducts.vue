<template>
    <v-dialog v-model="dialog" max-width="1200px" content-class="add-inventory-dialog-wrapper" :retain-focus="false" persistent v-resize="onResize" scrollable>
        <v-card>            
            <v-card-title>
                <span class="headline"> {{ formTitle }} </span>

                <v-spacer></v-spacer>						

                <v-btn icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="add-inventory-info-wrapper pa-0">
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="add-inventory-info">
                        <div class="add-inventory-first-column">                                                        
                            <div class="inbound-date-and-time-wrapper">
                                <div class="inbound-eta mb-3">
                                    <p class="inbound-title">Inventory As Of</p>
                                    <v-text-field
                                        type="date"
                                        class="text-fields" 
                                        placeholder="Select Date" 
                                        outlined
                                        hide-details="auto"
                                        v-model="editedAddProductItems.inventory_as_of" />
                                </div>
                            </div>

                            <div class="inbound-notes mb-3">
                                <p class="inbound-title">Notes <span>(Optional)</span></p>
                                <v-textarea
                                    class="note"
                                    outlined
                                    name="input-7-4"
                                    placeholder="Type your notes here..."
                                    v-model="editedAddProductItems.notes"
                                    :rules="notesRules">
                                </v-textarea>
                            </div>
                        </div>

                        <div class="add-inventory-second-column">
                            <div class="add-products-section-wrapper">
                                <h4>Products</h4>

                                <div class="inbound-tables products-section">
                                    <v-data-table
                                        :headers="headers"
                                        :items="inventoryProducts3pl"
                                        class="elevation-1 add-products-inbound-table"
                                        mobile-breakpoint="769"
                                        hide-default-footer
                                        :items-per-page="100">

                                        <!-- <template v-slot:[`item.shipping_unit`]="{ item, index }">
                                            <div class="product-selection" v-if="!isMobile">
                                                <v-select
                                                    :items="shippingUnit"
                                                    class="select-product shrink"
                                                    item-text="name"
                                                    item-value="shipping_unit"
                                                    placeholder="Select Unit"
                                                    outlined 
                                                    v-model="item.shipping_unit"
                                                    :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                    hide-details="auto"
                                                    :rules="rules">
                                                </v-select>

                                                <span class="error-message" style="color: white;">
                                                    {{ isProductSelectedOtherFieldsError(item, index) }}
                                                </span>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.product_id`]="{ item, index }">
                                            <div class="product-selection" v-if="!isMobile">
                                                <v-autocomplete
                                                    :items="productsDropdown"
                                                    class="select-product shrink"
                                                    :class="isProductFieldError(item, index)"
                                                    item-text="name"
                                                    item-value="product_id"
                                                    placeholder="Select Product"
                                                    outlined 
                                                    v-model="item.product_id"
                                                    :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                    hide-details="auto"
                                                    :rules="rules">

                                                    <template v-slot:item="{ item }">
                                                        <div class="option-items">
                                                            <div class="sku-item">
                                                                <div class="sku-details">
                                                                    <span>
                                                                        #
                                                                        <span v-if="item.category_id !== null">
                                                                            {{ item.category_id }}-
                                                                        </span>
                                                                        <span>{{ item.sku }}</span>
                                                                    </span>
                                                                </div>

                                                                <div>
                                                                    <p class="name" style="color: #4a4a4a !important;"> 
                                                                        {{ item.name }} 
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="image-item">
                                                                <img :src="getImgUrl(item.image)" height="60px" width="60px" alt="">
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <template v-slot:append-item>
                                                        <v-list-item @click="openAddProductDialog">
                                                            <v-list-item-content>
                                                                <v-list-item-title>
                                                                    <div class="d-flex align-center">
                                                                        <img
                                                                            src="@/assets/icons/plus.svg"
                                                                            class="mr-2"
                                                                            width="12px"
                                                                            height="12px"
                                                                            alt=""/>
                                                                        <span style="font-family: 'Inter-Medium', sans-serif; color: #0171A1;">
                                                                            Add New Product
                                                                        </span>
                                                                    </div>
                                                                </v-list-item-title>
                                                            </v-list-item-content>
                                                        </v-list-item>
                                                    </template>

                                                    <template v-slot:no-data>
                                                        <div class="option-items loading" 
                                                            v-if="fetchProductLoading"
                                                            style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
                                                            <div class="sku-item">
                                                                <v-progress-circular
                                                                    :size="40"
                                                                    color="#0171a1"
                                                                    indeterminate>
                                                                </v-progress-circular>
                                                            </div>
                                                        </div>

                                                        <div class="option-items no-data" 
                                                            v-if="!fetchProductLoading"
                                                            style="min-height: 40px; padding: 12px; font-size: 14px; border-bottom: 1px solid #EBF2F5;">
                                                            <div class="sku-item">
                                                                No available data
                                                            </div>
                                                        </div>
                                                    </template>
                                                </v-autocomplete>

                                                <span class="error-message" style="font-size: 12px; color: red;">
                                                    {{ isProductSelected(item, index) }}
                                                </span>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.quantity`]="{ item, index }">
                                            <div>
                                                <v-text-field 
                                                    placeholder="0" 
                                                    type="number" 
                                                    outlined                                
                                                    class="table-text-fields icc-carton-count shrink"
                                                    hide-details="auto"
                                                    v-model="item.quantity"
                                                    :rules="rules"
                                                    min="1"
                                                    @keydown="restrictValues($event)"
                                                    @wheel="$event.target.blur()">
                                                </v-text-field>

                                                <span class="error-message" style="color: white;">
                                                    {{ isProductSelectedOtherFieldsError(item, index) }}
                                                </span>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.actions`]="{ item }">
                                            <v-btn
                                                v-show="inventoryProducts3pl.length > 1"
                                                icon
                                                class="btn remove-btn"
                                                @click="removeRow(item)">
                                                <v-icon>mdi-close</v-icon>
                                            </v-btn>
                                        </template> -->
                                        
                                        <template v-slot:item="{ item, index }">
                                            <tr class="add-inventory-row-data" v-if="!isMobile">
                                                <td class="add-inventory-col-data">
                                                    <div class="product-selection">
                                                        <v-autocomplete
                                                            :items="productsDropdown"
                                                            spellcheck="false" 
                                                            :filter="customFilter"
                                                            class="select-product shrink"
                                                            :class="isProductFieldError(item, index)"
                                                            item-text="name"
                                                            item-value="product_id"
                                                            placeholder="Select Product"
                                                            outlined 
                                                            v-model="item.product_id"
                                                            :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true, closeOnContentClick: true }"
                                                            hide-details="auto"
                                                            :rules="rules"
                                                            :disabled="item.status === 'recieved' && editedProductIndex > -1">

                                                            <template v-slot:item="{ item }">
                                                                <div class="option-items">
                                                                    <div class="sku-item">
                                                                        <div class="sku-details">
                                                                            <span>
                                                                                #
                                                                                <span v-if="item.category_id !== null">
                                                                                    {{ item.category_id }}-
                                                                                </span>
                                                                                <span>{{ item.sku }}</span>
                                                                            </span>
                                                                        </div>

                                                                        <div>
                                                                            <p class="name" style="color: #4a4a4a !important;"> 
                                                                                {{ item.name }} 
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="image-item">
                                                                        <img :src="getImgUrl(item.image)" height="60px" width="60px" alt="">
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <template v-slot:append-item>
                                                                <v-list-item @click="openAddProductDialog">
                                                                    <v-list-item-content>
                                                                        <v-list-item-title>
                                                                            <div class="d-flex align-center">
                                                                                <img
                                                                                    src="@/assets/icons/plus.svg"
                                                                                    class="mr-2"
                                                                                    width="12px"
                                                                                    height="12px"
                                                                                    alt=""/>
                                                                                <span style="font-family: 'Inter-Medium', sans-serif; color: #0171A1;">
                                                                                    Add New Product
                                                                                </span>
                                                                            </div>
                                                                        </v-list-item-title>
                                                                    </v-list-item-content>
                                                                </v-list-item>
                                                            </template>

                                                            <template v-slot:no-data>
                                                                <div class="option-items loading" 
                                                                    v-if="fetchProductLoading"
                                                                    style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
                                                                    <div class="sku-item">
                                                                        <v-progress-circular
                                                                            :size="40"
                                                                            color="#0171a1"
                                                                            indeterminate>
                                                                        </v-progress-circular>
                                                                    </div>
                                                                </div>

                                                                <div class="option-items no-data" 
                                                                    v-if="!fetchProductLoading"
                                                                    style="min-height: 40px; padding: 12px; font-size: 14px; border-bottom: 1px solid #EBF2F5;">
                                                                    <div class="sku-item">
                                                                        No available data
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </v-autocomplete>

                                                        <!-- <span class="error-message" style="font-size: 12px; color: red;">
                                                            {{ isProductSelected(item, index) }}
                                                        </span> -->
                                                    </div>
                                                </td>

                                                <td class="add-inventory-col-data">
                                                    <div class="product-selection">
                                                        <v-select
                                                            :items="shippingUnit"
                                                            class="select-product shrink"
                                                            item-text="name"
                                                            item-value="shipping_unit"
                                                            placeholder="Select Unit"
                                                            outlined 
                                                            v-model="item.shipping_unit"
                                                            :menu-props="{ bottom: true, offsetY: true }"
                                                            hide-details="auto"
                                                            :rules="rules">
                                                        </v-select>

                                                        <!-- <span class="error-message" style="color: white;">
                                                            {{ isProductSelectedOtherFieldsError(item, index) }}
                                                        </span> -->
                                                    </div>
                                                </td>

                                                <td class="add-inventory-col-data">
                                                    <div>
                                                        <v-text-field 
                                                            placeholder="0" 
                                                            type="number" 
                                                            outlined                                
                                                            class="table-text-fields icc-carton-count shrink"
                                                            hide-details="auto"
                                                            v-model="item.quantity"
                                                            :rules="rules"
                                                            min="1"
                                                            @keydown="restrictValues($event)"
                                                            @wheel="$event.target.blur()">
                                                        </v-text-field>

                                                        <!-- <span class="error-message" style="color: white;">
                                                            {{ isProductSelectedOtherFieldsError(item, index) }}
                                                        </span> -->
                                                    </div>
                                                </td>

                                                <td class="add-inventory-col-data">
                                                    <v-btn
                                                        v-show="inventoryProducts3pl.length > 1"
                                                        icon
                                                        class="btn remove-btn"
                                                        @click="removeRow(item)">
                                                        <v-icon>mdi-close</v-icon>
                                                    </v-btn>
                                                </td>
                                            </tr>

                                            <tr class="add-inventory-row-data" v-if="isMobile">
                                                <td class="add-inventory-col-data pb-0">
                                                    <div class="product-mobile-wrapper">
                                                        <div class="product-mobile-header-product">
                                                            <p>Product</p>

                                                            <v-btn
                                                                v-show="inventoryProducts3pl.length > 1"
                                                                icon
                                                                class="btn remove-btn"
                                                                @click="removeRow(item)">
                                                                <v-icon>mdi-close</v-icon>
                                                            </v-btn>
                                                        </div>

                                                        <v-autocomplete
                                                            :items="productsDropdown"
                                                            class="select-product shrink"
                                                            item-text="name"
                                                            item-value="product_id"
                                                            placeholder="Select Product"
                                                            outlined 
                                                            v-model="item.product_id"
                                                            :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true, closeOnContentClick: true }"
                                                            hide-details="auto"
                                                            :rules="rules"
                                                            :disabled="editedProductIndex > -1">

                                                            <template v-slot:item="{ item }">
                                                                <div class="option-items">
                                                                    <div class="sku-item">
                                                                        <div class="sku-details">
                                                                            <span>
                                                                                #
                                                                                <span v-if="item.category_id !== null">
                                                                                    {{ item.category_id }}-
                                                                                </span>
                                                                                <span>{{ item.sku }}</span>
                                                                            </span>
                                                                        </div>

                                                                        <div>
                                                                            <p class="name" style="color: #4a4a4a !important;"> 
                                                                                {{ item.name }} 
                                                                            </p>
                                                                        </div>

                                                                        <div>
                                                                            <p class="name" style="font-size: 12px !important;"> 
                                                                                {{ item.units_per_carton }} Units/Carton
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="image-item">
                                                                        <img :src="getImgUrl(item.image)" height="60px" width="60px" alt="">
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <template v-slot:no-data>
                                                                <div class="option-items loading" 
                                                                    v-if="fetchProductLoading"
                                                                    style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
                                                                    <div class="sku-item">
                                                                        <v-progress-circular
                                                                            :size="40"
                                                                            color="#0171a1"
                                                                            indeterminate>
                                                                        </v-progress-circular>
                                                                    </div>
                                                                </div>

                                                                <div class="option-items no-data" 
                                                                    v-if="!fetchProductLoading"
                                                                    style="min-height: 40px; padding: 12px; font-size: 14px;">
                                                                    <div class="sku-item">
                                                                        No available data
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </v-autocomplete>
                                                    </div>
                                                </td>

                                                <td class="unit-qty-col-wrapper">
                                                    <div class="unit-qty-wrapper">
                                                        <td class="add-inventory-col-data shipping-unit-mobile">
                                                            <div class="product-mobile-wrapper">
                                                                <div class="product-mobile-header">
                                                                    <p>Shipping unit</p>
                                                                </div>

                                                                <v-select
                                                                    :items="shippingUnit"
                                                                    class="select-product shrink"
                                                                    item-text="name"
                                                                    item-value="shipping_unit"
                                                                    placeholder="Select Unit"
                                                                    outlined 
                                                                    v-model="item.shipping_unit"
                                                                    :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                                    hide-details="auto"
                                                                    :rules="rules">
                                                                </v-select>
                                                            </div>
                                                        </td>

                                                        <td class="add-inventory-col-data">
                                                            <div class="product-mobile-wrapper">
                                                                <div class="product-mobile-header">
                                                                    <p class="text-end">Quantity</p>
                                                                </div>

                                                                <v-text-field 
                                                                    placeholder="0" 
                                                                    type="number" 
                                                                    outlined                                
                                                                    class="table-text-fields icc-carton-count shrink"
                                                                    hide-details="auto"
                                                                    v-model="item.quantity"
                                                                    :rules="rules"
                                                                    min="1"
                                                                    @keydown="restrictValues($event)"
                                                                    @wheel="$event.target.blur()">
                                                                </v-text-field>
                                                            </div>
                                                        </td>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr v-if="isProductSelected(item, index)">
                                                <td colspan="12" class="error-skus-duplication">
                                                    {{ isProductSelected(item, index) }}
                                                </td>
                                            </tr>
                                        </template>
                                    </v-data-table>

                                    <div class="add-row-wrapper">
                                        <button class="btn-white add-btn" @click.stop="addRow">+ Add Product</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="inbound-button-actions">
                <button class="btn-blue" text 
                    @click="addInventoryProductsAction" 
                    :disabled="getAddInventoryProductsLoading || getInboundUpdateLoading">
                    
                    <span v-if="editedProductIndex === -1">
                        {{ getAddInventoryProductsLoading ? 'Adding...' : 'Add Inventory'}}
                    </span>

                    <span v-if="editedProductIndex > -1">
                        {{ getInboundUpdateLoading ? 'Saving...' : 'Save Edits'}}
                    </span>
                </button>

                <button class="btn-white" text 
                    @click="close"
                    :disabled="getAddInventoryProductsLoading || getInboundUpdateLoading">
                    Cancel
                </button>
            </v-card-actions>            
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import moment from 'moment'
import globalMethods from '../../../utils/globalMethods'
import _ from 'lodash'

export default {
    name: 'AddInventoryProductsDialog',
    props: [
        'dialogAdd', 
        'editedProductIndex', 
        'editedProductItems', 
        'defaultProductItems', 
        'currentWarehouseSelected', 
        'parentTab',
        'isFetchSingleInbound',
        'linkData',
        'productListsDropdownData',
        'fetchProductLoading'
    ],
    components: {},
    data: () => ({
        current_page: 1,
        valid: true,
        headers: [
			{
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'product_id',
				fixed: true,
				width: "45%"
			},
            {
				text: 'UNIT',
				align: 'start',
				sortable: false,
				value: 'shipping_unit',
				fixed: true,
				width: "20%"
			},
			{
				text: 'QUANTITY',
				align: 'end',
				sortable: false,
				value: 'quantity',
				fixed: true,
				width: "15%"
			},
            {
				text: '',
				align: 'end',
				sortable: false,
				value: 'actions',
				fixed: true,
				width: "5%"
			},
		],
        counter: 0,
        selected: null,
        rules: [
            (v) => !!v || "Input is required."
        ],
        cartonRules: [
            v => !!v || 'Carton is required',
            v => (parseFloat(v) > 0) || 'Carton should be greater than 0',
        ],
        unitRules: [
            v => !!v || 'Unit is required',
            v => (parseFloat(v)>0) || 'Unit should be greater than 0',
        ],
        notesRules: [
            v => (v || '' ).length <= 255 || 'Only 255 maximum characters allowed.'
        ],
        inventoryProducts3pl: [{
            product: {
                product_id: ''
            },
            product_id: '',
            quantity: '',
            shipping_unit: '',
            status: '',
            inbound_product_id: null
        }],
        shippingUnit: [
            {
                name: "Carton",
                shipping_unit: "carton"
            },
            {
                name: "Single Item",
                shipping_unit: "single_item"
            },
            // {
            //     name: "Bundle Item",
            //     shipping_unit: "bundle"
            // },
        ],
        isMobile: false,
        hasProductDuplicates: false,
        // for adding new product
        addProductDialog: false,
        editedIndexProduct: -1,
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
        categoryListData: [],
        lastDataCheck: [],
        current_page_is: 1
    }),
    watch: {},
    computed: {
        ...mapGetters({
            getAddInventoryProductsLoading: 'productInventories/getAddInventoryProductsLoading',
            getInboundUpdateLoading: 'inbound/getInboundUpdateLoading',
        }),
        formTitle () {
            return this.editedProductIndex === -1 ? 'Add Inventory' : 'Edit Inventory'
        },
        dialog: {
            get() {
                return this.dialogAdd
            },
            set(value) {
                this.$emit('update:dialogAdd', value)
            }
        },        
        editedAddProductItems: {
            get() {
                let value = this.editedProductItems
                if (value.inventory_as_of === '') {
                    value.inventory_as_of = new Date().toISOString().substr(0, 10)
                }
                return value
            },
            set(value) {
                this.$emit('update:editedProductItems', value)
            }
        },
        productsDropdown: {
            get() {
                return this.productListsDropdownData
            },
            set(value) {
                this.productListsDropdownData = value
            }
        },
        addInventoryTemplate() {
            let { notes, inventory_as_of } = this.editedAddProductItems

            let formatDate = (inventory_as_of !== '' && inventory_as_of !== null) ? 
                moment(inventory_as_of).format('MM/DD/YYYY') : ''

            let inventoryDataPassed = {
                notes,
                inventory_as_of: formatDate,
                products: [],
                warehouse_id: this.currentWarehouseSelected.id
            }

            // map products
            if (typeof this.inventoryProducts3pl !== 'undefined' && this.inventoryProducts3pl !== null) {
                if (this.inventoryProducts3pl[0].product_id !== '') {
                    this.inventoryProducts3pl.map(item => {
                        let { product_id, quantity, shipping_unit } = item 

                        inventoryDataPassed.products.push({
                            product_id,
                            quantity: quantity !== null && quantity !== '' ? parseInt(quantity) : 0,
                            shipping_unit
                        })
                    })
                } else {
                    inventoryDataPassed.products = []
                }
            }

            return inventoryDataPassed
        },
        editInventoryTemplate() {
            let { id, notes, inventory_as_of } = this.editedAddProductItems

            let formatDate = (inventory_as_of !== '' && inventory_as_of !== null) ? 
                moment(inventory_as_of).format('MM/DD/YYYY') : ''

            let inventoryDataPassed = {
                notes: notes !== null || notes !== 'null' ? notes : '',
                inventory_as_of: formatDate,
                documents: [],
                products: [],
                warehouse_id: this.currentWarehouseSelected.id,
                inbound_id: id,
                _method: 'PUT'
            }

            // map products
            if (typeof this.inventoryProducts3pl !== 'undefined' && this.inventoryProducts3pl !== null) {
                if (this.inventoryProducts3pl[0].product_id !== '') {
                    this.inventoryProducts3pl.map(item => {
                        let { inbound_product_id, product_id, quantity, shipping_unit } = item 

                        inventoryDataPassed.products.push({
                            product_id,
                            quantity: quantity !== null && quantity !== '' ? parseInt(quantity) : 0,
                            shipping_unit,
                            inbound_product_id
                        })
                    })
                } else {
                    inventoryDataPassed.products = []
                }
            }

            return inventoryDataPassed
        },
    },
    methods: {
        ...mapActions({
            addProductsInventories: 'productInventories/addProductsInventories',
            fetchProductInventories3pl : 'productInventories/fetchProductInventories3pl',
            updateInboundFor3PLAddInventory: 'inbound/updateInboundFor3PLAddInventory',
            fetchCompletedInbounds: 'inbound/fetchCompletedInbounds',
            fetchSingleInbound: 'inbound/fetchSingleInbound',
        }),
        ...globalMethods,
        restrictValues(e) {
            if(e.key=='-' && e.keyCode==189 || e.key=='+' && e.keyCode==187 ) {
                e.preventDefault()
            }
        },
        close() {
            this.$refs.form.resetValidation()
            this.$emit('close')
            this.inventoryProducts3pl= [{
                product: {
                    product_id: ''
                },
                product_id: '',
                quantity: '',
                shipping_unit: '',
                status: '',
                inbound_product_id: null
            }]
        },
        onResize() {
            if (window.innerWidth < 1024) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
        },
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
                return require('@/assets/icons/default-product-icon.svg')
            }
		},
        removeRow(item) {
            let getIndex = this.inventoryProducts3pl.indexOf(item)
            this.inventoryProducts3pl.splice(getIndex, 1)
        },
        addRow() {
            let getItem = this.inventoryProducts3pl

            getItem.push({
                product: {
                    product_id: '',
                },
                product_id: '',
                quantity: '',
                shipping_unit: '',
                status: '',
                inbound_product_id: null
            })

            this.inventoryProducts3pl.products = getItem
        },
        isProductSelected(item, index) {
            // if (item.product_id !== "") {
            //     let findSelectedOption = _.findIndex(
            //         this.inventoryProducts3pl, e => (
            //             (e.product_id == item.product_id) && (e.shipping_unit == item.shipping_unit)
            //         ))

            //     if (findSelectedOption !== index) {
            //         this.hasProductDuplicates = true
            //         return 'This product has already been selected.'
            //     } else {
            //         this.hasProductDuplicates = false
            //     }
            // }

            if (item.product_id !== "") {
                let findSelectedOption =  _.findIndex(this.inventoryProducts3pl, e => (
                    (e.product_id == item.product_id) && 
                    (e.shipping_unit == item.shipping_unit) &&
                    (e.quantity === item.quantity)
                ))                

                if (item.quantity !== '') {
                    item.quantity = parseInt(item.quantity)

                    if (findSelectedOption !== index) {
                        this.hasProductDuplicates = true
                        return 'Duplicate entry found. You have already added this SKU with same specification.'
                    } else {
                        this.hasProductDuplicates = false
                        return ''
                    }
                } else {
                    this.hasProductDuplicates = false
                    return ''
                }

                // if (findSelectedOption !== index) {
                //     this.hasProductDuplicates = true
                //     return 'Duplicate entry found. You have already added this SKU with same specification.'
                // } else {
                //     this.hasProductDuplicates = false
                //     return ''
                // }
            }
        },
        isProductFieldError(item, index) {
            if (item.product_id !== "") {
                let findSelectedOption =  _.findIndex(this.inventoryProducts3pl, e => (
                    (e.product_id == item.product_id) && 
                    (e.shipping_unit == item.shipping_unit) &&
                    (e.quantity === item.quantity)
                ))

                if (item.quantity !== '') {
                    item.quantity = parseInt(item.quantity)

                    if (findSelectedOption !== index) {
                        return 'has-duplicate'
                    } else {
                        return ''
                    }
                }                
            }
        },
        isProductSelectedOtherFieldsError(item, index) {
            if (item.product_id !== "") {
                let findSelectedOption =  _.findIndex(this.inventoryProducts3pl, e => (
                    (e.product_id == item.product_id) && 
                    (e.shipping_unit == item.shipping_unit) &&
                    (e.quantity === item.quantity)
                ))

                if (item.quantity !== '') {
                    item.quantity = parseInt(item.quantity)
                    
                    if (findSelectedOption !== index) {
                        return 'has-duplicate'
                    } else {
                        return ''
                    }
                } 
            }
        },
        openAddProductDialog() {
            this.$emit('openAddProductDialog')
        },
        // 
        async addInventoryProductsAction() {
            this.$refs.form.validate()
            
            if (this.$refs.form.validate()) {
                if (typeof this.addInventoryTemplate !== 'undefined') {
                    try {
                        let storeInboundTab = this.$store.state.inbound
                        let data = {
                            id: this.currentWarehouseSelected.id,
                            page: 1
                        }

                        if (this.editedProductIndex === -1) {
                            let addInventoryTemplate = this.addInventoryTemplate
                            await this.addProductsInventories(addInventoryTemplate)
                            this.notificationMessage('Product Inventories has been added.')                           
                            await this.fetchProductInventories3pl(data)                        
                            this.close()
                        } else {
                            let editInventoryTemplate = this.editInventoryTemplate
                            editInventoryTemplate.products = JSON.stringify(editInventoryTemplate.products)
                            await this.updateInboundFor3PLAddInventory(editInventoryTemplate)
                            this.notificationMessage('Inventories has been updated.')
                            this.close()

                            if (this.isFetchSingleInbound) {
                                let fetchSingleInboundPayload = { 
                                    wid: this.currentWarehouseSelected.id, 
                                    iid: editInventoryTemplate.inbound_id 
                                }
                                await this.fetchSingleInbound(fetchSingleInboundPayload)
                            }

                            data.page = storeInboundTab.completedInboundPagination.current_page
                            await this.fetchCompletedInbounds(data)
                        }
                    } catch(e) {
                        this.notificationError(e)
                        console.log(e);
                    } 
                    
                    // if (this.hasProductDuplicates) {
                    //     this.notificationError('Duplicate products has been detected.')
                    // } else {
                    //     try {
                    //         let addInventoryTemplate = this.addInventoryTemplate
                    //         await this.addProductsInventories(addInventoryTemplate)
                    //         this.notificationMessage('Product Inventories has been added.')

                    //         let data = {
                    //             id: this.currentWarehouseSelected.id,
                    //             page: 1
                    //         }

                    //         await this.fetchProductInventories3pl(data)                        
                    //         this.close()
                    //     } catch(e) {
                    //         this.notificationError(e)
                    //         console.log(e);
                    //     } 
                    // }                                          
                } else {
                    this.notificationError("Something's wrong in creating an outbound. Please reload your page and try again.")
                } 
            }
        },
        customFilter(item, queryText, itemText) {
            const data = item.category_sku + item.category_id + item.name.toLowerCase() + item.sku + itemText.toLocaleLowerCase()
            const searchText = queryText.toLowerCase()
            return data.indexOf(searchText) > -1 
        },
    },
    mounted() {},
    updated() {
        if (typeof this.editedProductItems !== 'undefined' && this.editedProductIndex > -1) {
            if (this.inventoryProducts3pl !== this.editedProductItems.inbound_products) {
                this.inventoryProducts3pl = this.editedProductItems.inbound_products
            }
        }

        if (this.editedIndex === -1) {
            if (typeof this.$refs.form !== 'undefined') {
                if (typeof this.$refs.form.resetValidation() !== 'undefined') {
                    this.$refs.form.resetValidation()
                }
            }
        }
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/product/addInventoryDialog.scss';
</style>
