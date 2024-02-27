<template>
    <v-dialog v-model="dialog" max-width="1040px" content-class="create-inbound-dialog" :retain-focus="false" persistent v-resize="onResize">
        <v-card>
            <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                <v-card-title>
                    <span class="headline">
                        {{ inboundItems.isDuplicate ? 'Create Inbound' : formTitle }}
                    </span>

                    <v-spacer></v-spacer>						

                    <v-btn icon dark class="btn-close" @click="close">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text class="create-inbound-info-wrapper">
                    <div class="create-inbound-info">
                        <div class="create-inbound-first-column">
                            <div class="inbound-order mb-3">
                                <p class="inbound-title">Order Id</p>
                                <v-radio-group v-model="isCustom" mandatory hide-details="auto" v-if="editedIndex === -1">
                                    <v-radio label="System Generated" value="generated"></v-radio>
                                    <v-radio label="Custom Number" value="custom"></v-radio>

                                    <span class="custom-wrapper mb-3" v-if="isCustom == 'custom'">
                                        <img src="@/assets/icons/item-icon.svg" alt="" class="box-icon" />
                                        
                                        <v-text-field
                                            height="40px"
                                            color="#002F44"
                                            width="200px"
                                            type="text"
                                            dense
                                            class="text-fields custom"
                                            placeholder="Enter Order ID"
                                            outlined
                                            v-model="inboundItems.order_id"
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-text-field>
                                    </span>
                                </v-radio-group>

                                <div class="custom-wrapper ml-0 mb-2" v-if="editedIndex > -1">
                                    <img src="@/assets/icons/item-icon.svg" alt="" class="box-icon" />
                                        
                                    <v-text-field
                                        height="40px"
                                        color="#002F44"
                                        width="200px"
                                        type="text"
                                        dense
                                        class="text-fields custom"
                                        placeholder="Enter Order ID"
                                        outlined
                                        v-model="inboundItems.order_id"
                                        :rules="rules"
                                        hide-details="auto">
                                    </v-text-field>
                                </div>
                            </div>

                            <div class="inbound-customer mb-3">
                                <p class="inbound-title">Customer</p>
                                <v-text-field 
                                    placeholder="Enter Customer" 
                                    outlined 
                                    class="text-fields"
                                    v-model="inboundItems.customer"
                                    :rules="rules"
                                    hide-details="auto">
                                </v-text-field>
                            </div>

                            <div class="inbound-customer mb-3">
                                <p class="inbound-title">Supplier</p>
                                <v-autocomplete
                                    :items="supplierItems"
                                    class="select-product shrink"
                                    item-text="name"
                                    item-value="name"
                                    placeholder="Select Supplier"
                                    outlined 
                                    v-model="inboundItems.supplier"
                                    :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                    hide-details="auto"
                                    :rules="rules"
                                    :disabled="isFieldsDisabled(inboundItems.inbound_status)">
                                </v-autocomplete>
                            </div>

                            <div class="inbound-shipping mb-4">
                                <p class="inbound-title">Shipping From</p>
                                <v-textarea
                                    class="note"
                                    outlined
                                    v-model="inboundItems.shipping_from"
                                    name="input-7-4"
                                    placeholder="Enter Address"
                                    hide-details="auto">
                                </v-textarea>
                            </div>

                            <div class="inbound-reference mb-3">
                                <p class="inbound-title">Reference</p>
                                <v-text-field 
                                    placeholder="Enter Reference" 
                                    outlined 
                                    class="text-fields"
                                    v-model="inboundItems.ref_no"
                                    hide-details="auto">
                                </v-text-field>
                            </div>

                            <div class="inbound-truck mb-3">
                                <p class="inbound-title">Carrier</p>
                                <v-text-field 
                                    placeholder="Enter Truck" 
                                    outlined 
                                    class="text-fields"
                                    v-model="inboundItems.name"
                                    hide-details="auto">
                                </v-text-field>
                            </div>
                                                        
                            <div class="inbound-eta mb-3">
                                <p class="inbound-title">ETA</p>
                                <vc-date-picker 
                                    v-model="inboundItems.estimated_date" 
                                    mode="date"
                                    :min-date='new Date()' >

                                    <template v-slot="{ inputValue, inputEvents }">
                                        <div class="datetime-picker-new">
                                           <v-text-field
                                                class="text-fields" 
                                                placeholder="Select ETA" 
                                                :value="inputValue" 
                                                v-on="inputEvents" 
                                                append-icon="mdi-calendar"
                                                outlined
                                                hide-details="auto" />
                                        </div>
                                    </template>
                                </vc-date-picker>
                            </div>

                            <div class="inbound-time mb-3">
                                <p class="inbound-title">TIME <span>(Optional)</span></p>
                                <v-menu
                                    ref="menu"
                                    v-model="menu"
                                    content-class="clock-menu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="inboundItems.estimated_time"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="260px">

                                    <template v-slot:activator="{ on, attrs }">
                                        <!-- :value="convertTimeTo12(inboundItems.estimated_time)" -->
                                        <v-text-field
                                            append-icon="mdi-clock-time-four-outline"
                                            v-bind="attrs"
                                            placeholder="Select Arrival Time (Optional)"
                                            class="text-fields"
                                            readonly
                                            hide-details="auto"
                                            :value="convertTimeTo12(inboundItems.estimated_time)"
                                            outlined
                                            v-on="on"
                                        ></v-text-field>
                                    </template>

                                    <v-time-picker
                                        format='ampm'
                                        ampm-in-title
                                        v-if="menu"
                                        v-model="inboundItems.estimated_time"
                                        full-width>

                                        <v-spacer></v-spacer>

                                        <button class="btn-blue" 
                                            @click="$refs.menu.save(inboundItems.estimated_time)">
                                            Set Time
                                        </button>

                                        <button class="btn-white" @click="menu = false">
                                            Cancel
                                        </button>
                                    </v-time-picker>
                                </v-menu>
                            </div>

                            <div class="inbound-notes mb-3">
                                <p class="inbound-title">Notes</p>
                                <v-textarea
                                    class="note"
                                    outlined
                                    v-model="inboundItems.notes"
                                    name="input-7-4"
                                    placeholder="Type your notes here..."
                                    :rules="notesRules">
                                </v-textarea>
                            </div>
                        </div>

                        <div class="create-inbound-second-column">
                            <!-- <v-tabs class="inbound-inner-tab" @change="onTabChange" v-model="currentTab">
                                <v-tab v-for="(tab, index) in tabs" 
                                    :key="index" 
                                    :class="index == 2 ? 'inbound-inner-tab-last' : ''">
                                    {{ tab }}
                                </v-tab>
                            </v-tabs> -->

                            <div class="add-products-section-wrapper">
                                <h4>Products</h4>

                                <div class="inbound-tables products-section">
                                    <v-data-table
                                        :headers="headers"
                                        :items="inboundProducts"
                                        class="elevation-1 add-products-inbound-table"
                                        mobile-breakpoint="769"
                                        hide-default-footer>

                                        <!-- old codes without duplication entry of sku's -->
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
                                                    :rules="rules"
                                                    :disabled="isFieldsDisabledInProducts(item)">
                                                </v-select>

                                                <span class="error-message" style="color: white;">
                                                    {{ isProductSelectedOtherFieldsError(item, index) }}
                                                </span>

                                                <span class="error-message" 
                                                    style="font-size: 11px; color: white;" 
                                                    v-if="isQuantityError(item)">.</span>
                                            </div>

                                            <div class="product-mobile-wrapper" v-if="isMobile">
                                                <div class="product-mobile-header">
                                                    <p>Shipping unit</p>

                                                    <v-btn
                                                        v-show="inboundProducts.length > 1"
                                                        icon
                                                        class="btn remove-btn"
                                                        @click="removeRow(item)">
                                                        <v-icon>mdi-close</v-icon>
                                                    </v-btn>
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
                                                    :rules="rules"
                                                    :disabled="isFieldsDisabledInProducts(item)">
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
                                                    :rules="rules"
                                                    :disabled="item.status === 'recieved' && editedInboundIndex > -1">

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

                                                    <template v-slot:append-item>
                                                        <v-list-item @click="openAddProductDialog" v-if="!fetchProductLoading">
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
                                                
                                                <span class="error-message" style="font-size: 12px; color: #6D858F;">
                                                    {{ isProductSelected(item, index) }}
                                                </span>

                                                <span class="error-message" 
                                                    style="font-size: 11px; color: white;" 
                                                    v-if="isQuantityError(item)">.</span>
                                            </div>

                                            <div class="product-mobile-wrapper" v-if="isMobile">
                                                <div class="product-mobile-header">
                                                    <p>Product</p>
                                                </div>

                                                <v-autocomplete
                                                    :items="productsDropdown"
                                                    class="select-product shrink"
                                                    item-text="name"
                                                    item-value="product_id"
                                                    placeholder="Select Product"
                                                    outlined 
                                                    v-model="item.product_id"
                                                    :menu-props="{ contentClass: 'product-lists-items' }"
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
                                                    :class="isQuantityError(item) ? 'error-count' : ''"
                                                    min="1"
                                                    @keydown="restrictValues($event)"
                                                    @wheel="$event.target.blur()">
                                                </v-text-field>

                                                <span class="error-message" style="color: white;">
                                                    {{ isProductSelectedOtherFieldsError(item, index) }}
                                                </span>

                                                <span class="error-message" 
                                                    style="font-size: 11px; color: #ff5252;" 
                                                    v-if="isQuantityError(item)">

                                                    <span v-if="!isWarehouse3PL">
                                                        Can't be below {{ 
                                                            item.shipping_unit === 'carton' ?
                                                            item.expected_carton_count :
                                                            item.expected_total_unit
                                                        }}
                                                    </span>

                                                    <span v-else>
                                                        Quantity can't be zero
                                                    </span>
                                                </span>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.actions`]="{ item }">
                                            <v-btn
                                                v-show="inboundProducts.length > 1"
                                                icon
                                                class="btn remove-btn"
                                                @click="removeRow(item)"
                                                :disabled="isFieldsDisabledInProducts(item)">
                                                <v-icon>mdi-close</v-icon>
                                            </v-btn>
                                        </template> -->

                                        <template v-slot:[`item.shipping_unit`]="{ item, index }">
                                            <div class="product-selection">
                                                <v-select
                                                    :items="shippingUnit"
                                                    class="select-product shrink"
                                                    item-text="name"
                                                    item-value="shipping_unit"
                                                    placeholder="Select Unit"
                                                    outlined 
                                                    v-model="item.shipping_unit"
                                                    :menu-props="{ contentClass: 'product-lists-items' }"
                                                    hide-details="auto">
                                                </v-select>

                                                <span class="error-message" style="color: white;">
                                                    {{ isProductSelectedOtherFieldsError(item, index) }}
                                                </span>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.product_id`]="{ item, index }">
                                            <div class="product-selection" v-if="!isMobile">
                                                <v-select
                                                    :items="productsDropdown"
                                                    class="select-product shrink"
                                                    :class="isProductFieldError(item, index)"
                                                    item-text="name"
                                                    item-value="product_id"
                                                    placeholder="Select Product"
                                                    outlined 
                                                    v-model="item.product_id"
                                                    :menu-props="{ contentClass: 'product-lists-items' }"
                                                    hide-details="auto">
                                                </v-select>

                                                <span class="error-message" style="font-size: 12px; color: red;">
                                                    {{ isProductSelected(item, index) }}
                                                </span>
                                            </div>

                                            <div class="product-mobile-wrapper" v-if="isMobile">
                                                <div class="product-mobile-header">
                                                    <p>Product</p>

                                                    <v-btn
                                                        v-show="inboundProducts.length > 1"
                                                        icon
                                                        class="btn remove-btn"
                                                        @click="removeRow(item)">
                                                        <v-icon>mdi-close</v-icon>
                                                    </v-btn>
                                                </div>

                                                <v-select
                                                    :items="productsDropdown"
                                                    class="select-product shrink"
                                                    item-text="name"
                                                    item-value="product_id"
                                                    placeholder="Select Product"
                                                    outlined 
                                                    v-model="item.product_id"
                                                    :menu-props="{ contentClass: 'product-lists-items' }"
                                                    hide-details="auto">
                                                </v-select>
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
                                                    v-model="item.quantity">
                                                </v-text-field>

                                                <span class="error-message" style="color: white;">
                                                    {{ isProductSelectedOtherFieldsError(item, index) }}
                                                </span>
                                            </div>
                                        </template>

                                        <!-- <template v-slot:[`item.total_unit`]="{ item }">
                                            <div>
                                                <v-text-field 
                                                    placeholder="0" 
                                                    type="number" 
                                                    outlined 
                                                    class="table-text-fields itu-total-unit shrink"                          
                                                    hide-details="auto"
                                                    v-model="item.total_unit"
                                                    :rules="unitRules">
                                                </v-text-field>
                                            </div>
                                        </template> -->

                                        <template v-slot:[`item.actions`]="{ item }">
                                            <v-btn
                                                v-show="inboundProducts.length > 1"
                                                icon
                                                class="btn remove-btn"
                                                @click="removeRow(item)">
                                                <v-icon>mdi-close</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>

                                    <div class="add-row-wrapper">
                                        <button class="btn-white add-btn" @click="addRow">+ Add Product</button>
                                    </div>
                                </div>
                            </div>

                            <div class="documents-upload-wrapper">
                                <h4 class="mb-2">Documents</h4>

                                <div class="documents-section">
                                    <p class="inbound-title">ATTACHMENTS</p>
                                    <div id="documents-files-select-box-id" class="documents-files-select-box" 
                                        @click.stop="addDocuments()">
                                            Browse Files

                                            <button class="btn-white btn-upload" @click.stop="addDocuments()">
                                                <img src="@/assets/icons/upload.svg" width="16px" height="16px">
                                                Upload
                                            </button>
                                    </div>

                                    <input 
                                        ref="documents_reference" 
                                        type="file" 
                                        id="documents_files" 
                                        @change="(e) => inputChanged(e)"
                                        name="documents[]"                                         
                                        multiple />
                                        <!-- :accept="accept" -->
                                </div>

                                <div class="document-lists" v-if="files !== null && files.length !== 'undefined' && files.length > 0">
                                    <div class="lists-items" v-for="(file, index) in files" :key="index">
                                        <div class="items">
                                            <span>
                                                <img src="@/assets/images/document.svg" width="14px" height="14px" class="mr-2">
                                                {{ file.name }}
                                            </span>

                                            <button class="close" @click="remove(index)">
                                                <img src="@/assets/images/close.svg" width="18px" height="18px">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-card-text>

                <v-card-actions class="inbound-button-actions">
                    <button class="btn-blue" text 
                        @click="buildAndPrint" 
                        :disabled="getInboundCreateLoading || getInboundUpdateLoading">
                        <span v-if="editedIndex === -1 || inboundItems.isDuplicate">
                            {{ getInboundCreateLoading ? 'Creating...' : 'Create'}}
                        </span>

                        <span v-if="editedIndex > -1 && !inboundItems.isDuplicate">
                            {{ getInboundUpdateLoading ? 'Updating...' : 'Update'}}
                        </span>
                    </button>

                    <!-- <button class="btn-white" text @click="close">
                        Build
                    </button> -->

                    <button class="btn-white" text 
                        @click="close"
                        :disabled="getInboundCreateLoading || getInboundUpdateLoading">
                        Cancel
                    </button>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import moment from 'moment'
import globalMethods from '../../../../utils/globalMethods'
import _ from 'lodash'

export default {
    name: 'CreateInboundDialog',
    props: [
        'dialogCreate', 
        'editedInboundIndex', 
        'editedInboundItems', 
        'defaultInboundItems', 
        'currentWarehouseSelected', 
        'parentTab',
        'isFetchSingleInbound',
        'linkData',
        'productListsDropdownData'
    ],
    components: {},
    data: () => ({
        current_page: 1,
        valid: true,
        headers: [
            {
				text: 'SHIPPING UNIT',
				align: 'start',
				sortable: false,
				value: 'shipping_unit',
				fixed: true,
				width: "20%"
			},
			{
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'product_id',
				fixed: true,
				width: "45%"
			},
			{
				text: 'QUANTITY',
				align: 'end',
				sortable: false,
				value: 'quantity',
				fixed: true,
				width: "15%"
			},
			// {
			// 	text: 'UNIT',
			// 	align: 'end',
			// 	sortable: false,
			// 	value: 'total_unit',
			// 	fixed: true,
			// 	width: "100px"
			// },
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
            v => (parseFloat(v)>0) || 'Carton should be greater than 0',
        ],
        unitRules: [
            v => !!v || 'Unit is required',
            v => (parseFloat(v)>0) || 'Unit should be greater than 0',
        ],
        notesRules: [
            // (v) => /^.{1,255}$/.test(v) || 'Only 255 maximum characters allowed.'
            v => (v || '' ).length <= 255 || 'Only 255 maximum characters allowed.'
        ],
        menu: false,
        tabs: ['Products', 'Pallets', 'Document'],
        currentTab: 0,
        inboundProducts: [{
            product: {
                product_id: ''
            },
            product_id: '',
            quantity: '',
            shipping_unit: ''
            // total_unit: ''
        }],
        isCustom: 'generated',
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
        date: '',
        time: '',
        isMobile: false,
        files: [],
        accept: '.pdf, .docx',
        hasProductDuplicates: false
    }),
    watch: {},
    computed: {
        ...mapGetters({
            getInventory: 'inventory/getInventory',
            getInboundCreateLoading: 'inbound/getInboundCreateLoading',
            getUser: 'getUser',
            getInboundUpdateLoading: 'inbound/getInboundUpdateLoading',
            poBaseUrlState: 'products/poBaseUrlState',
            getProducts: 'products/getProducts',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
        }),
        formTitle () {
            return this.editedIndex === -1 ? 'Create Inbound' : 'Edit Inbound'
        },
        dialog: {
            get() {
                return this.dialogCreate
            },
            set(value) {
                this.$emit('update:dialogCreate', value)
            }
        },
        supplierItems() {
            let data = [];

			if (typeof this.getSuppliers !== "undefined" && this.getSuppliers !== null) {
				if (typeof this.getSuppliers.data !== "undefined" && this.getSuppliers.data.length !== "undefined") {
					data = this.getSuppliers.data;

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

			return data;
        },
        editedIndex: {
            get() {
                return this.editedInboundIndex
            },
            set(value) {
                this.$emit('update:editedInboundIndex', value)
            }
        },
        inboundItems: {
            get() {
                return this.editedInboundItems
            },
            set(value) {
                this.$emit('update:editedInboundItems', value)
            }
        },
        productsDropdown: {
            get() {
                let value = this.productListsDropdownData
                let finalValue = []

                if (Array.isArray(value) && value.length > 0) {
                    if (typeof this.getProducts!=='undefined' && Array.isArray(this.getProducts) && 
                        this.getProducts.length > 0) {
                        value.map ( v => {
                            let findProduct = _.findIndex(this.getProducts, e => (v.id === e.id))
                            if (findProduct !== -1) {
                                finalValue.push(v)
                            }
                        })

                        return finalValue

                    } else {
                        return value
                    }
                } else {
                    return value
                }              
            },
            set (value) {
                this.productListsDropdownData = value
            }
        },
        addInboundTemplate() {
            let { order_id, name, ref_no, shipping_from, notes, customer, estimated_date, estimated_time } = this.inboundItems

            let formatDate = (estimated_date !== '' && estimated_date !== null) ? moment(estimated_date).format('MM/DD/YYYY') : ''

            let inboundDataPassed = {
                customer_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : '',
                name,
                ref_no,
                shipping_from,
                notes,
                customer,
                estimated_date: formatDate,
                estimated_time,                            
                products: [],
                documents: this.files.length > 0 ? this.files : [],
                warehouse_id: this.currentWarehouseSelected.id
            }

            // check if inbound is custom or system generated
            if (this.isCustom == 'custom') {
                inboundDataPassed.order_id = order_id
            } else {
                inboundDataPassed.sys_gen = true
            }

            // map products
            if (typeof this.inboundProducts !== 'undefined' && this.inboundProducts !== null) {
                if (this.inboundProducts[0].product_id !== '') {
                    this.inboundProducts.map(item => {
                        let { product_id, quantity, shipping_unit } = item 

                        inboundDataPassed.products.push({
                            product_id,
                            quantity,
                            shipping_unit
                        })
                    })
                } else {
                    inboundDataPassed.products = []
                }
            }

            return inboundDataPassed
        },
        editInboundTemplate() {
            let { id, order_id, name, ref_no, shipping_from, notes, customer, estimated_date, estimated_time } = this.inboundItems

            let formatDate = (estimated_date !== '' && estimated_date !== null) ? moment(estimated_date).format('MM/DD/YYYY') : ''

            let inboundDataPassed = {
                id,
                customer_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : '',
                name: name !== null || name !== 'null' ? name : '',
                order_id,
                ref_no: ref_no !== null || ref_no !== 'null' ? ref_no : '',
                shipping_from,
                notes: notes !== null || notes !== 'null' ? notes : '',
                customer,
                estimated_date: formatDate,
                estimated_time,                            
                products: [],
                documents: this.files.length > 0 ? this.files : [],
                warehouse_id: this.currentWarehouseSelected.id,
                inbound_id: id,
                _method: 'PUT'
            }

            // old code for mapping products
            // if (typeof this.inboundProducts !== 'undefined' && this.inboundProducts !== null) {
            //     if (this.inboundProducts[0].product_id !== '') {
            //         let newItems = this.inboundProducts.map(item => {
            //             let { product_id, quantity, shipping_unit } = item

            //             return {
            //                 product_id,
            //                 quantity,
            //                 shipping_unit
            //             }
            //         })
            //         inboundDataPassed.products = newItems

            //         return {
            //             inboundDataPassed
            //         }
            //     } else {
            //         inboundDataPassed.products = []

            //         return {
            //             inboundDataPassed
            //         }
            //     }
            // } else {
            //     return {}
            // }

            // map products
            if (typeof this.inboundProducts !== 'undefined' && this.inboundProducts !== null) {
                if (this.inboundProducts[0].product_id !== '') {
                    this.inboundProducts.map(item => {
                        let { product_id, quantity, shipping_unit } = item 

                        inboundDataPassed.products.push({
                            product_id,
                            quantity,
                            shipping_unit
                        })
                    })
                } else {
                    inboundDataPassed.products = []
                }
            }

            return inboundDataPassed
        }
    },
    methods: {
        ...mapActions({
            createInbound: 'inbound/createInbound',
            updateInbound: 'inbound/updateInbound',
            fetchInboundInventories: 'inbound/fetchInboundInventories',
            fetchInventories: 'inventory/fetchInventories',
            // 
            fetchPendingInbounds: 'inbound/fetchPendingInbounds',
            fetchFloorInbounds: 'inbound/fetchFloorInbounds',
            fetchCompletedInbounds: 'inbound/fetchCompletedInbounds',
            fetchCancelledInbounds: 'inbound/fetchCancelledInbounds',
            setCurrentInboundTab: 'inbound/setCurrentInboundTab',
            fetchSingleInbound: 'inbound/fetchSingleInbound',
        }),
        ...globalMethods,
        onResize() {
            if (window.innerWidth < 1024) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
        },
        convertTimeTo12(time) {
            let finalTime = null

            if (time !== '' && time !== null) {
                finalTime = moment(time, ["HH:mm"]).format("hh:mm A");
            }     

            return finalTime
        },
        onTabChange(i) {
            this.currentTab = i
        },
        close() {
            this.$emit('close')
            this.inboundProducts = [
                {
                    product: {
                        product_id: ''
                    },
                    product_id: '',
                    quantity: '',
                    shipping_unit: ''
                }
            ],
            this.date = ''
            this.time = ''
            this.inboundItems = this.defaultInboundItems
            this.files = []
            this.$refs.documents_reference.value = ''
            this.$refs.form.resetValidation()
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
                return require('../../../../assets/icons/default-product-icon.svg')
            }
		},
        async buildAndPrint() {
            this.$refs.form.validate()

            if (this.$refs.form.validate()) {
                try {
                    if (this.hasProductDuplicates) {
                        this.notificationError('Duplicate products has been detected.')
                    } else {
                        if (typeof this.addInboundTemplate !== 'undefined') {
                            if (this.editedIndex === -1) { // creating inbound
                                let addInboundTemplate = this.addInboundTemplate    
                                addInboundTemplate.products = JSON.stringify(addInboundTemplate.products)
                                await this.createInbound(addInboundTemplate)
                                this.notificationMessage('Inbound has been created.')

                                try {
                                    let storeInboundTab = this.$store.state.inbound

                                    let dataWithPage = {
                                        id: this.currentWarehouseSelected.id,
                                        page: 1
                                    }

                                    if (typeof this.getCurrentInboundTab !== 'undefined') {
                                        dataWithPage.page = storeInboundTab.pendingInboundPagination.current_page
                                    }
                                    
                                    await this.fetchPendingInbounds(dataWithPage)
                                    this.setCurrentInboundTab(0)
                                    this.close()
                                } catch(e) {
                                    this.notificationError(e)
                                    console.log(e);
                                } 
                            } else { // editing inbound
                                let editInboundTemplate = this.editInboundTemplate
                                editInboundTemplate.products = JSON.stringify(editInboundTemplate.products)
                                await this.updateInbound(editInboundTemplate)
                                this.notificationMessage('Inbound has been updated.')

                                try {
                                    if (this.isFetchSingleInbound) {
                                        let fetchSingleInboundPayload = { wid: this.currentWarehouseSelected.id, iid: this.linkData.inbound_id }
                                        this.fetchSingleInbound(fetchSingleInboundPayload)
                                    } else {
                                        let storeInboundTab = this.$store.state.inbound

                                        let dataWithPage = {
                                            id: this.currentWarehouseSelected.id,
                                            page: 1
                                        }

                                        if (typeof this.getCurrentInboundTab !== 'undefined') {
                                            dataWithPage.page = storeInboundTab.pendingInboundPagination.current_page
                                        }
                                        
                                        this.fetchPendingInbounds(dataWithPage)
                                    }

                                    // if (this.searchVal !== '') {
                                    //     if (typeof this.getSearchedInbounds !== 'undefined') {
                                    //         let searchedPage = typeof this.getSearchedInbounds.current_page !== 'undefined' ?
                                    //             this.getSearchedInbounds.current_page : 1
                                                                
                                    //         await this.fetchSearchedInboundsAPI(searchedPage)
                                    //     }
                                    // } else {
                                    //     this.callPendingInbound()
                                    // }

                                    this.close()
                                } catch(e) {
                                    this.notificationError(e)
                                    console.log(e);
                                } 
                            }                        
                        } else {
                            this.notificationError("Something's wrong in creating an outbound. Please reload your page and try again.")
                        } 
                    }                
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        removeRow(item) {
            let getIndex = this.inboundProducts.indexOf(item)
            this.inboundProducts.splice(getIndex, 1)
        },
        addRow() {
            let getItem = this.inboundProducts

            getItem.push({
                product: {
                    product_id: '',
                },
                product_id: '',
                quantity: '',
                shipping_unit: ''
            })

            this.inboundItems.products = getItem
        },
        addDocuments() {
            this.$refs.documents_reference.click()
        },
        inputChanged() {
            let files = this.$refs.documents_reference.files;

            if (!files.length) {
                return false;
            }

            for (let i = 0; i < files.length; i++) {
                this.files.push(files[i])
            }
        },
        remove(index) {
            this.files.splice(index, 1)
            this.$refs.documents_reference.value = ''
        },
        isProductSelected(item, index) {
            if (item.product_id !== "") {
                let findSelectedOption = _.findIndex(
                    this.inboundProducts, e => (
                        (e.product_id == item.product_id) && (e.shipping_unit == item.shipping_unit)
                    ))

                if (findSelectedOption !== index) {
                    this.hasProductDuplicates = true
                    return 'This product has already been selected.'
                } else {
                    this.hasProductDuplicates = false
                }
            }
        },
        isProductFieldError(item, index) {
            if (item.product_id !== "") {
                let findSelectedOption = _.findIndex(
                    this.inboundProducts, e => (
                        (e.product_id == item.product_id) && (e.shipping_unit == item.shipping_unit)
                    ))

                if (findSelectedOption !== index) {
                    return 'has-duplicate'
                } else {
                    return ''
                }
            }
        },
        isProductSelectedOtherFieldsError(item, index) {
            if (item.product_id !== "") {
                let findSelectedOption = _.findIndex(
                    this.inboundProducts, e => (
                        (e.product_id == item.product_id) && (e.shipping_unit == item.shipping_unit)
                    ))

                if (findSelectedOption !== index) {
                    return 'has-duplicate'
                } else {
                    return ''
                }
            }
        },
    },
    mounted() {},
    updated() {
        if (typeof this.inboundItems !== 'undefined' && (this.editedInboundIndex > -1 || this.inboundItems.isDuplicate)) {
            if (this.inboundProducts !== this.inboundItems.inbound_products) {
                this.inboundProducts = this.inboundItems.inbound_products
            }

            if (this.files !== this.inboundItems.documents ) {
                this.files = this.inboundItems.documents
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
@import '@/assets/scss/pages_scss/inventory/inbound/inboundDialog.scss';
</style>
