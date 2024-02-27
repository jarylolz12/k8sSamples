<template>
    <v-dialog v-model="dialog" :max-width="isWarehouseConnected ? '1360px' : '1200px'" content-class="create-inbound-dialog" :retain-focus="false" persistent v-resize="onResize" scrollable>
        <v-card>            
            <v-card-title>
                <span class="headline">
                    {{ inboundItems.isDuplicate ? 'Create Inbound' : formTitle }}
                </span>
                <v-spacer></v-spacer>
                <v-btn icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="create-inbound-info-wrapper pa-0">
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="create-inbound-info">
                        <div class="create-inbound-first-column">
                            <div class="inbound-order mb-4">
                                <p class="inbound-title">Order Id</p>
                                <v-radio-group v-model="isCustom" mandatory hide-details="auto" v-if="editedIndex === -1">
                                    <v-radio label="System Generated" value="generated"></v-radio>
                                    <v-radio label="Custom Number" value="custom"></v-radio>

                                    <span class="custom-wrapper mb-1" v-if="isCustom == 'custom'">
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
                                            :rules="customSkuRules"
                                            hide-details="auto"
                                            @wheel="$event.target.blur()">
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
                                        :rules="customSkuRules"
                                        hide-details="auto"
                                        @wheel="$event.target.blur()">
                                    </v-text-field>
                                </div>
                            </div>

                            <div class="inbound-customer mt-1 mb-3" v-if="isWarehouse3PLProvider && !isWarehouseConnected">
                                <p class="inbound-title">WAREHOUSE CUSTOMER</p>
                                <v-autocomplete
                                    class="text-fields select-items"
                                    v-model="inboundItems.warehouse_customer_id"
                                    :items="warehouseCustomerLists"
                                    item-text="name"
                                    item-value="id"
                                    outlined
                                    hide-details="auto"
                                    placeholder="Select Warehouse Customer"
                                    :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true, closeOnContentClick: true }"
                                    clearable
                                    @change="clearValueOnChange"
                                    :disabled="
                                        checkIfProductsAreReceived(inboundProducts) || 
                                        inboundItems.is_from_connected_3pl === 1"
                                    >

                                    <template v-slot:item="{ item }">
                                        <div @click="callWarehouseCustomerProducts(item)" class="option-items" style="padding: 14px 0;">
                                            <div class="name-address-item">
                                                <p class="name mb-1" style="color: #4a4a4a;"> 
                                                    {{ item.name }} 
                                                </p>

                                                <p class="address mb-0" style="color: #6D858F; font-size: 12px;"> 
                                                    {{ item.address }}
                                                </p>
                                            </div>
                                        </div>
                                    </template>

                                    <template v-slot:no-data>
                                        <div class="option-items loading" 
                                            v-if="fetchWarehouseCustomersLoading"
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
                                            v-if="!fetchWarehouseCustomersLoading"
                                            style="min-height: 40px; padding: 12px; font-size: 14px; border-bottom: 1px solid #EBF2F5;">
                                            <div class="sku-item">
                                                No available data
                                            </div>
                                        </div>
                                    </template>
                                </v-autocomplete>
                            </div>

                            <div class="inbound-notes mb-3" v-if="isWarehouse3PLProvider && isWarehouseConnected">
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
                                <p class="inbound-title">Truck</p>
                                <v-text-field 
                                    placeholder="Enter Truck Name" 
                                    outlined 
                                    class="text-fields"
                                    v-model="inboundItems.name"
                                    hide-details="auto">
                                </v-text-field>
                            </div>
                                                        
                            <div class="inbound-date-and-time-wrapper">
                                <div class="inbound-eta mb-3">
                                    <p class="inbound-title">ETA <span>(Optional)</span></p>
                                    <v-text-field
                                        type="date"
                                        class="text-fields" 
                                        placeholder="Select Date" 
                                        outlined
                                        hide-details="auto"
                                        :min="currentDateFind"
                                        v-model="inboundItems.estimated_date"
                                        :disabled="isFieldsDisabled(inboundItems.inbound_status)" />
                                </div>

                                <div class="inbound-time mb-3">
                                    <p class="inbound-title">TIME <span>(Optional)</span></p>
                                        <v-text-field
                                            class="text-fields input-time" 
                                            placeholder="12:30:00"
                                            v-model="inboundItems.estimated_time"
                                            type="time"
                                            outlined
                                            hide-details="auto"
                                            hint="FORMAT: 12:00 AM"
                                            :disabled="isFieldsDisabled(inboundItems.inbound_status)">
                                        </v-text-field>
                                </div>
                            </div>

                            <div class="inbound-notes mb-3">
                                <p class="inbound-title" v-if="!isWarehouseConnected">Notes <span>(Optional)</span></p>
                                <p class="inbound-title" v-else>Special Instructions <span>(Optional)</span></p>
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
                            <div class="add-products-section-wrapper">
                                <h4>Products</h4>

                                <div class="inbound-tables products-section">
                                    <v-data-table
                                        :headers="headersComputed"
                                        :items="inboundProducts"
                                        class="elevation-1 add-products-inbound-table"
                                        mobile-breakpoint="769"
                                        hide-default-footer
                                        :items-per-page="500">                                        
                                    
                                        <template v-slot:item="{ item, index }">
                                            <tr class="inbound-row-data" v-if="!isMobile">
                                                <td class="inbound-col-data">
                                                    <div class="product-selection">
                                                        <v-autocomplete
                                                            :items="productsDropdown"
                                                            spellcheck="false" 
                                                            :filter="customFilter"
                                                            class="select-product shrink"
                                                            :class="isProductFieldError(item, index)"
                                                            item-text="name"
                                                            item-value="product_id"
                                                            :placeholder="fetchProductLoading ? 'Fetching products...' : 'Select Product'"
                                                            outlined 
                                                            v-model="item.product_id"
                                                            :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true, closeOnContentClick: true }"
                                                            hide-details="auto"
                                                            :rules="rules"
                                                            :disabled="(item.status === 'recieved' && editedInboundIndex > -1) || fetchProductLoading">

                                                            <template v-slot:item="{ item }">
                                                                <div class="option-items">
                                                                    <div class="sku-item">
                                                                        <div class="sku-details">
                                                                            <span>#{{ item.category_sku }}</span>
                                                                        </div>

                                                                        <p class="name" style="color: #4a4a4a !important;"> 
                                                                            {{ item.name }} 
                                                                        </p>

                                                                        <p class="name" style="font-size: 12px !important;"> 
                                                                            {{ item.units_per_carton }} Units/Carton
                                                                        </p>
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
                                                                                <img src="@/assets/icons/plus.svg"
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

                                                        <span class="error-message" 
                                                            style="font-size: 11px; color: white;" 
                                                            v-if="isQuantityError(item)">.</span>
                                                    </div>
                                                </td>

                                                <td class="inbound-col-data">
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
                                                            :rules="rules"
                                                            :disabled="isFieldsDisabledInProducts(item) || fetchProductLoading">
                                                        </v-select>

                                                        <span class="error-message" 
                                                            style="font-size: 11px; color: white;" 
                                                            v-if="isQuantityError(item)">.</span>
                                                    </div>
                                                </td>

                                                <td class="inbound-col-data text-end">
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
                                                            @wheel="$event.target.blur()"
                                                            :disabled="fetchProductLoading">
                                                        </v-text-field>

                                                        <span class="error-message mb-0 pt-1 text-right" 
                                                            style="font-size: 11px; color: #ff5252;" 
                                                            v-if="isQuantityError(item)">

                                                            <span v-if="!isWarehouse3PL">
                                                                Can't be below {{ 
                                                                    item.shipping_unit === 'carton' ?
                                                                    item.expected_carton_count :
                                                                    item.expected_total_unit
                                                                }}
                                                            </span>

                                                            <span v-else> Quantity can't be zero </span>
                                                        </span>
                                                    </div>
                                                </td>

                                                <td class="inbound-col-data" v-if="isWarehouseConnected">
                                                    <div>
                                                        <v-text-field 
                                                            placeholder="Type instructions here" 
                                                            type="text"
                                                            outlined                                
                                                            class="select-items shrink"
                                                            hide-details="auto"
                                                            v-model="item.instructions"
                                                            :disabled="fetchProductLoading">
                                                        </v-text-field>
                                                    </div>
                                                </td>

                                                <td class="inbound-col-data">
                                                    <v-btn
                                                        v-show="inboundProducts.length > 1"
                                                        icon
                                                        class="btn remove-btn"
                                                        @click="removeRow(item)"
                                                        :disabled="isFieldsDisabledInProducts(item)">
                                                        <v-icon>mdi-close</v-icon>
                                                    </v-btn>
                                                </td>
                                            </tr>

                                            <tr class="inbound-row-data" v-if="isMobile">
                                                <td class="inbound-col-data pb-0">
                                                    <div class="product-mobile-wrapper">
                                                        <div class="product-mobile-header-product">
                                                            <p>Product</p>

                                                            <v-btn
                                                                v-show="inboundProducts.length > 1"
                                                                icon
                                                                class="btn remove-btn"
                                                                @click="removeRow(item)"
                                                                :disabled="isFieldsDisabledInProducts(item)">
                                                                <v-icon>mdi-close</v-icon>
                                                            </v-btn>
                                                        </div>

                                                        <v-autocomplete
                                                            :items="productsDropdown"
                                                            spellcheck="false" 
                                                            :filter="customFilter"
                                                            class="select-product shrink"
                                                            :class="isProductFieldError(item, index)"
                                                            item-text="name"
                                                            item-value="product_id"
                                                            :placeholder="fetchProductLoading ? 'Fetching products...' : 'Select Product'"
                                                            outlined 
                                                            v-model="item.product_id"
                                                            :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true, closeOnContentClick: true }"
                                                            hide-details="auto"
                                                            :rules="rules"
                                                            :disabled="(item.status === 'recieved' && editedInboundIndex > -1) || fetchProductLoading">

                                                            <template v-slot:item="{ item }">
                                                                <div class="option-items">
                                                                    <div class="sku-item">
                                                                        <div class="sku-details">
                                                                            <span>#{{ item.category_sku }}</span>
                                                                        </div>

                                                                        <p class="name" style="color: #4a4a4a !important;"> 
                                                                            {{ item.name }} 
                                                                        </p>

                                                                        <p class="name" style="font-size: 12px !important;"> 
                                                                            {{ item.units_per_carton }} Units/Carton
                                                                        </p>
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
                                                                                <img src="@/assets/icons/plus.svg"
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
                                                    </div>
                                                </td>

                                                <td class="unit-qty-col-wrapper">
                                                    <div class="unit-qty-wrapper">
                                                        <td class="inbound-col-data shipping-unit-mobile">
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
                                                                    :rules="rules"
                                                                    :disabled="isFieldsDisabledInProducts(item) || fetchProductLoading">
                                                                </v-select>
                                                            </div>
                                                        </td>

                                                        <td class="inbound-col-data text-end">
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
                                                                    :class="isQuantityError(item) ? 'error-count' : ''"
                                                                    min="1"
                                                                    @keydown="restrictValues($event)"
                                                                    @wheel="$event.target.blur()"
                                                                    :disabled="fetchProductLoading">
                                                                </v-text-field>

                                                                <span class="error-message mb-0 pt-1 text-right" 
                                                                    style="font-size: 11px; color: #ff5252;" 
                                                                    v-if="isQuantityError(item)">

                                                                    <span v-if="!isWarehouse3PL">
                                                                        Can't be below {{ 
                                                                            item.shipping_unit === 'carton' ?
                                                                            item.expected_carton_count :
                                                                            item.expected_total_unit
                                                                        }}
                                                                    </span>

                                                                    <span v-else> Quantity can't be zero </span>
                                                                </span>
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
                                </div>

                                <div class="document-lists" v-if="files !== null && files.length !== 'undefined' && files.length > 0">
                                    <div class="lists-items" v-for="(file, index) in files" :key="index">
                                        <div class="items">
                                            <span class="d-flex align-center">
                                                <img src="@/assets/images/document.svg" width="14px" height="14px" class="mr-2">
                                                <span style="color: #4a4a4a;">{{ file.name }}</span>
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
                </v-form>
            </v-card-text>

            <v-card-actions class="inbound-button-actions">
                <div v-if="!isWarehouseConnected" class="d-flex align-center">
                    <button class="btn-blue" text @click="buildAndPrint" 
                        :disabled="getInboundCreateLoading || getInboundUpdateLoading">
                        <span v-if="editedIndex === -1 || inboundItems.isDuplicate">
                            {{ getInboundCreateLoading ? 'Creating...' : 'Create'}}
                        </span>

                        <span v-if="editedIndex > -1 && !inboundItems.isDuplicate">
                            {{ getInboundUpdateLoading ? 'Updating...' : 'Update'}}
                        </span>
                    </button>
                </div>

                <div v-else class="d-flex align-center">
                    <button class="btn-blue" text @click="buildAndPrint(true)" 
                        :disabled="getInboundCreateLoading || getInboundUpdateLoading || isCreateAndSubmitting || isSavingDraft">
                        <span v-if="editedIndex === -1">
                            {{ isCreateAndSubmitting ? 'Creating...' : 'Create And Submit'}}
                        </span>

                        <span v-if="editedIndex > -1">
                            {{ isUpdateAndSubmitting ? 'Saving...' : 'Save And Submit'}}
                        </span>
                    </button>

                    <button class="btn-white" text @click="buildAndPrint(false)"
                        :disabled="getInboundCreateLoading || getInboundUpdateLoading || isCreateAndSubmitting || isSavingDraft">
                        <span v-if="editedIndex === -1">
                            {{ isSavingDraft ? 'Saving...' : 'Save as Draft'}}
                        </span>

                        <span v-if="editedIndex > -1">
                            {{ isUpdatingDraft ? 'Saving...' : 'Save as Draft'}}
                        </span>
                    </button>
                </div>

                <button class="btn-white" style="color: #4a4a4a !important;" text 
                    @click="close"
                    :disabled="getInboundCreateLoading || getInboundUpdateLoading">
                    Cancel
                </button>
            </v-card-actions>            
        </v-card>

        <ProductAddDialog 
            :dialog.sync="addProductDialog"
            :editedIndex.sync="editedIndexProduct"
			:defaultItem.sync="defaultProductItem"
			:editedItem.sync="editedProductItem"
			:categoryLists="categoryListData"
			@close="closeProductDialog"
			@setToDefault="setToDefault"
			:isMobile="isMobile"
			:isInventoryPage="true"
            :isWarehouse3PL="isWarehouse3PL"
            :isWarehouse3PLProvider="isWarehouse3PLProvider"
            @callInboundProductsFor3PL="callInboundProductsFor3PL"
            @callWarehouseCustomerProducts="callWarehouseCustomerProducts"
            :inboundItems="inboundItems" />

        <ConfirmDialog :dialogData.sync="showWarningChangingWarehouseCustomer">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2> Update Warehouse Customer </h2>
            </template>

            <template v-slot:dialog_content>
                <p v-if="editedIndex === -1"> 
                    By changing the Warehouse Customer, the existing products will be removed. Do you want to continue?
                </p>

                <p v-if="editedIndex > -1"> 
                    By changing the Warehouse Customer, the existing products might be removed. Do you want to continue?
                </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="confirmUpdateWarehouseCustomer" text>
                    Yes
                </v-btn>

                <v-btn class="btn-white" text @click="discardConfirmation">
                    Discard
                </v-btn>
            </template>
        </ConfirmDialog>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import moment from 'moment'
import globalMethods from '../../../../utils/globalMethods'
import inventoryGlobalMethods from '../../../../utils/inventoryMethods/inventoryGlobalMethods'
import _ from 'lodash'
import ProductAddDialog from '../../../ProductComponents/Dialog/ProductAddDialog.vue'
import ConfirmDialog from '../../../Dialog/GlobalDialog/ConfirmDialog.vue'

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
        'productListsDropdownData',
        'fetchProductLoading',
        'searchVal',
        'currentWarehouseInboundsData',
        'fetchWarehouseCustomersLoading',
        'selectedWhCustomers',
        'selectedWhCustomersCopy',
        'isWarehouseConnected',
        'isWarehouseOwn'
    ],
    components: {
        ProductAddDialog,
        ConfirmDialog
    },
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
				text: 'SHIPPING UNIT',
				align: 'start',
				sortable: false,
				value: 'shipping_unit',
				fixed: true,
				width: "15%"
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
				text: 'INSTRUCTIONS',
				align: 'start',
				sortable: false,
				value: 'instructions',
				fixed: true,
				width: "35%"
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
            v => (parseFloat(v)>0) || 'Carton should be greater than 0',
        ],
        unitRules: [
            v => !!v || 'Unit is required',
            v => (parseFloat(v)>0) || 'Unit should be greater than 0',
        ],
        notesRules: [
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
            shipping_unit: '',
            status: '',
            inbound_product_id: null,
            instructions: ''
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
        hasProductDuplicates: false,
        timezone: '',
        quantityError: false,
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
        current_page_is: 1,
        showWarningChangingWarehouseCustomer: false, 
        updated_value_of_warehouse_customer: null,
        old_warehouse_customer_saved: '',
        customSkuRules: [
            (v) => !!v || "Input is required.",
            v => (v || '' ).length <= 50 || 'Only 50 maximum characters allowed.'
        ],
        isCreateAndSubmitting: false,
        isSavingDraft: false,
        isUpdateAndSubmitting: false,
        isUpdatingDraft: false
    }),
    computed: {
        ...mapGetters({
            getInboundCreateLoading: 'inbound/getInboundCreateLoading',
            getUser: 'getUser',
            getInboundUpdateLoading: 'inbound/getInboundUpdateLoading',
            getProducts: 'products/getProducts',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
            getSuppliers: "suppliers/getSuppliers",            
			getCategoriesDropdown: 'category/getCategoriesDropdown',
			getAllCategoriesDropdown: 'category/getAllCategoriesDropdown',
            getAllInboundProductsLists: 'inbound/getAllInboundProductsLists',
            getSearchedInbounds: 'inbound/getSearchedInbounds',
            poBaseUrlState: 'products/poBaseUrlState',
            getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            getPreviousSelectedCustomerWarehouseID: 'inbound/getPreviousSelectedCustomerWarehouseID',
            getfilteredInbounds:'inbound/getfilteredInbounds',
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
        formTitle () {
            return this.editedIndex === -1 ? 'Create Inbound' : 'Edit Inbound'
        },
        currentDateFind() {
            return new Date().toISOString().substr(0, 10)
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
        dialog: {
            get() {
                return this.dialogCreate
            },
            set(value) {
                this.$emit('update:dialogCreate', value)
            }
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
                return this.productListsDropdownData
            },
            set(value) {
                this.$emit('update:productListsDropdownData', value)
            }
        },
        addInboundTemplate() {
            let { order_id, name, ref_no, notes, estimated_date, estimated_time, warehouse_customer_id } = this.inboundItems

            let formatDate = (estimated_date !== '' && estimated_date !== null) ? 
                moment(estimated_date).format('MM/DD/YYYY') : ''
            let formatTime = (estimated_time !== '' && estimated_time !== null && estimated_time !== "null") ? estimated_time : ''

            let inboundDataPassed = {
                customer_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : '',
                customer_admin_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).id : '',
                customer_admin_name: (typeof this.getUser=='string') ? JSON.parse(this.getUser).name : '',
                name,
                ref_no,
                // shipping_from,
                notes,
                // supplier,
                estimated_date: formatDate,
                estimated_time: formatTime,
                products: [],
                documents: this.files.length > 0 ? this.files : [],
                warehouse_id: this.currentWarehouseSelected.id,
                warehouse_customer_id: warehouse_customer_id !== null ? warehouse_customer_id : '',
                is_from_connected_3pl: 0
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
                        let { product_id, quantity, shipping_unit, instructions } = item 

                        inboundDataPassed.products.push({
                            product_id,
                            quantity,
                            shipping_unit,
                            instructions
                        })
                    })
                } else {
                    inboundDataPassed.products = []
                }
            }

            // check if is warehouse connected
            if (this.isWarehouseConnected) {
                inboundDataPassed.inbound_status = 'draft'
                inboundDataPassed.shipping_from = this.inboundItems.shipping_from
                inboundDataPassed.is_from_connected_3pl = 1
                inboundDataPassed.warehouse_customer_id = this.currentWarehouseSelected.warehouse_customer_id
            }

            return inboundDataPassed
        },
        editInboundTemplate() {
            let { id, order_id, name, ref_no, notes, estimated_date, estimated_time, warehouse_customer_id } = this.inboundItems

            let formatDate = (estimated_date !== '' && estimated_date !== null) ? moment(estimated_date).format('MM/DD/YYYY') : ''
            let formatTime = (estimated_time !== '' && estimated_time !== null && estimated_time !== "null") ? estimated_time : ''

            let inboundDataPassed = {
                id,
                customer_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : '',
                updated_customer_admin_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).id : '',
                updated_customer_admin_name: (typeof this.getUser=='string') ? JSON.parse(this.getUser).name : '',
                name: name !== null || name !== 'null' ? name : '',
                order_id,
                ref_no: ref_no !== null || ref_no !== 'null' ? ref_no : '',
                // shipping_from,
                notes: notes !== null || notes !== 'null' ? notes : '',
                // supplier,
                estimated_date: formatDate,
                estimated_time: formatTime,
                products: [],
                documents: this.files.length > 0 ? this.files : [],
                warehouse_id: this.currentWarehouseSelected.id,
                inbound_id: id,
                _method: 'PUT',
                warehouse_customer_id: warehouse_customer_id !== null ? warehouse_customer_id : ''
            }

            // map products
            if (typeof this.inboundProducts !== 'undefined' && this.inboundProducts !== null) {
                if (this.inboundProducts[0].product_id !== '') {
                    this.inboundProducts.map(item => {
                        let { inbound_product_id, product_id, quantity, shipping_unit, instructions } = item 

                        inboundDataPassed.products.push({
                            product_id,
                            quantity,
                            shipping_unit,
                            inbound_product_id,
                            instructions
                        })
                    })
                } else {
                    inboundDataPassed.products = []
                }
            }

            // check if is warehouse connected
            if (this.isWarehouseConnected) {
                inboundDataPassed.inbound_status = 'draft'
                inboundDataPassed.shipping_from = this.inboundItems.shipping_from
            }

            return inboundDataPassed
        },
        warehouseCustomerLists() {
            let data = []

            if (typeof this.getWarehouseCustomersDropdown !== "undefined" && this.getWarehouseCustomersDropdown !== null) {
                if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
                    this.getWarehouseCustomersDropdown.data.length !== "undefined") {
                    data = this.getWarehouseCustomersDropdown.data

                    data.map((value) => {
                        if (value.address === null) {
                            value.address = ""
                        }

                        if (value.phone === null) {
                            value.phone = ""
                        }

                        if (value.emails === null) {
                            value.emails = ""
                        }
                    })
                }
            }

            return data
        },
        headersComputed() {
            let defaultHeaders = this.headers

            if (!this.isWarehouseConnected) {
                defaultHeaders = defaultHeaders.filter(v => {
                    return v.text !== 'INSTRUCTIONS'
                })
            } else {
                defaultHeaders = defaultHeaders.filter(v => {
                    if (v.text === 'SKU') {
                        v.width = '30%'
                    }

                    if (v.text === 'INSTRUCTIONS') {
                        v.width = '30%'
                    }

                    return defaultHeaders
                })
            }

            return defaultHeaders
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
            // 
            fetchCategoriesDropdown: 'category/fetchCategoriesDropdown',
			setAllCategoriesDropdown: 'category/setAllCategoriesDropdown',
            updateInboundFor3PL: 'inbound/updateInboundFor3PL',
            updateInboundFor3PLAddInventory: 'inbound/updateInboundFor3PLAddInventory',
            fetchSearchedInbounds: 'inbound/fetchSearchedInbounds',
            setSearchedInboundLoading: 'inbound/setSearchedInboundLoading',
            setAllInboundProductsLists: 'inbound/setAllInboundProductsLists',
            fetchDraftInbounds: 'inbound/fetchDraftInbounds',
            fetchPendingReceivingInbounds: 'inbound/fetchPendingReceivingInbounds',
            fetchPendingReceivingInboundsForWHCustomer: 'inbound/fetchPendingReceivingInboundsForWHCustomer',
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        restrictValues(e) {
            if(e.key=='-' && e.keyCode==189 || e.key=='+' && e.keyCode==187 ) {
                e.preventDefault()
            }
        },
        onResize() {
            if (window.innerWidth < 1024) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
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
                    shipping_unit: '',
                    status: '',
                    inbound_product_id: null,
                    instructions: ''
    
                }
            ]
            this.date = ''
            this.time = ''
            this.inboundItems = this.defaultInboundItems
            this.files = []
            this.$refs.documents_reference.value = ''
            this.$refs.form.resetValidation()
            this.quantityError = false
        },
        clearWarehouseCustomerProducts() {
            this.inboundProducts = [
                {
                    product: {
                        product_id: ''
                    },
                    product_id: '',
                    quantity: '',
                    shipping_unit: '',
                    status: '',
                    inbound_product_id: null,
                    instructions: ''
                }
            ]
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
        async callPendingInbound() {
            let storeInboundTab = this.$store.state.inbound
            let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1 }

            if (typeof this.getCurrentInboundTab !== 'undefined') {
                if (!this.isWarehouseConnected) {
                    if (!this.isWarehouse3PL) {
                        if (this.isWarehouse3PLProvider) {
                            if (this.getCurrentInboundTab === 0) {
                                dataWithPage.page = this.searchVal === '' ? storeInboundTab.pendingInboundPagination.current_page : 1
                                await this.fetchPendingInbounds(dataWithPage)

                            } else if (this.getCurrentInboundTab === 1) {
                                dataWithPage.page = this.searchVal === '' ? storeInboundTab.pendingReceiveInboundPagination.current_page : 1
                                await this.fetchPendingReceivingInbounds(dataWithPage)
                                
                            } else if (this.getCurrentInboundTab === 2) {
                                dataWithPage.page = this.searchVal === '' ? storeInboundTab.floorInboundPagination.current_page : 1
                                await this.fetchFloorInbounds(dataWithPage)
                            }
                        } else {
                            if (this.getCurrentInboundTab === 0) {
                                dataWithPage.page = this.searchVal === '' ? storeInboundTab.pendingInboundPagination.current_page : 1
                                await this.fetchPendingInbounds(dataWithPage)

                            } else if (this.getCurrentInboundTab === 1) {
                                dataWithPage.page = this.searchVal === '' ? storeInboundTab.floorInboundPagination.current_page : 1
                                await this.fetchFloorInbounds(dataWithPage)
                            }
                        }
                    } else {
                        if (this.getCurrentInboundTab === 0) {
                            dataWithPage.page = this.searchVal === '' ? storeInboundTab.pendingInboundPagination.current_page : 1
                            await this.fetchPendingInbounds(dataWithPage)

                        } else if (this.getCurrentInboundTab === 3) {
                            dataWithPage.page = this.searchVal === '' ? storeInboundTab.completedInboundPagination.current_page : 1
                            await this.fetchCompletedInbounds(dataWithPage)
                        }
                    }
                } else {
                    if (this.getCurrentInboundTab === 0) {
                        dataWithPage.page = this.searchVal === '' ? storeInboundTab.draftInboundPagination.current_page : 1
                        await this.fetchDraftInbounds(dataWithPage)
                    } else if (this.getCurrentInboundTab === 1) {                        
                        dataWithPage.page = this.searchVal === '' ? storeInboundTab.pendingInboundPagination.current_page : 1
                        await this.fetchPendingInbounds(dataWithPage)
                    }
                }
            }
        },
        async callOnSyncMethods() {
            if (!this.isWarehouse3PLProvider) {
                if (this.searchVal !== '') {
                    if (typeof this.getSearchedInbounds !== 'undefined') {
                        let searchedPage = typeof this.getSearchedInbounds.current_page !== 'undefined' ?
                            this.getSearchedInbounds.current_page : 1
                                            
                        await this.fetchSearchedInboundsAPI(searchedPage)
                    }
                } else {
                    this.callPendingInbound()
                } 
            } else {
                if (this.selectedWhCustomersCopy.length === 0) {
                    if (this.searchVal !== '') {
                        if (typeof this.getSearchedInbounds !== 'undefined') {
                            let searchedPage = typeof this.getSearchedInbounds.current_page !== 'undefined' ?
                                this.getSearchedInbounds.current_page : 1
                                                
                            await this.fetchSearchedInboundsAPI(searchedPage)
                        }
                    } else {
                        this.callPendingInbound()
                    }
                } else {
                    if (this.selectedWhCustomersCopy.length > 0) {
                        if (typeof this.getfilteredInbounds !== 'undefined') {
                            let searchedPage = typeof this.getfilteredInbounds.current_page !== 'undefined' ?
                                this.getfilteredInbounds.current_page : 1
                                                
                            await this.fetchSearchedInboundsAPI(searchedPage)
                        }
                        await this.callPendingInbound()
                    }
                }
            }
        },
        async fetchSearchedInboundsAPI(page) {
            let storePagination = this.$store.state.inbound.setCurrentTab
            let warehouse_id = this.currentWarehouseSelected.id

            var searchParams = new URLSearchParams()
            searchParams.append('page', page)
            searchParams.append('search', this.searchVal)

            let passedData = {
                method: "get",
                url: '',
                params: { search: this.searchVal, page }
            } 

            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/common`

            if (!this.isWarehouseConnected) { // id warehouse is not a connected 3pl
                if (storePagination === 0) {
                    passedData.tab = 'pending'
                    passedData.params.status = ['pending']

                    if (this.isWarehouseOwn) passedData.params.status.push('arrived')
                } else if (storePagination === 1) {
                    passedData.tab = 'floor'
                    passedData.params.status = ['floor']

                    if (this.isWarehouse3PLProvider) {
                        passedData.tab = 'receive_pending'
                        passedData.params.status = ['receive-pending', 'arrived']
                    }
                } else if (storePagination === 2) {
                    passedData.tab = 'completed'
                    passedData.params.status = ['completed']

                    if (this.isWarehouse3PLProvider) {
                        passedData.tab = 'floor'
                        passedData.params.status = ['floor']
                    }
                } else if (storePagination === 3) {
                    passedData.tab = 'cancelled'
                    passedData.params.status = ['cancelled']

                    if (this.isWarehouse3PLProvider) {
                        passedData.tab = 'completed'
                        passedData.params.status = ['completed']
                    }
                } else {
                    passedData.tab = 'cancelled'
                    passedData.params.status = ['cancelled', 'rejected']
                }
            } else { // if warehouse is connected 3pl
                if (storePagination === 0) {
                    passedData.tab = 'draft'
                    passedData.params.status = ['draft']
                } else if (storePagination === 1) {
                    passedData.tab = 'pending'
                    passedData.params.status = ['pending']
                } else if (storePagination === 2) {
                    passedData.tab = 'receive_pending_wh'
                    passedData.params.status =  ['receive-pending', 'arrived', 'floor']
                } else if (storePagination === 3) {
                    passedData.tab = 'completed'
                    passedData.params.status = ['completed']
                } else {
                    passedData.tab = 'cancelled'
                    passedData.params.status = ['cancelled', 'rejected']
                }
            }

            if (this.selectedWhCustomersCopy.length === 0) {
                if (passedData.url !== '') {
                    await this.fetchSearchedInbounds(passedData)

                    if (typeof this.currentWarehouseInboundsData !== 'undefined' && 
                        this.currentWarehouseInboundsData.length === 0 && page !== 1) {
                        passedData.params.page = page - 1
                        await this.fetchSearchedInbounds(passedData)
                    }

                    await this.callPendingInbound()
                }
            } else {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                await this.$emit('filterAllWarehouseCustomers', true)
            }
        },
        async buildAndPrint(isSubmit) {
            this.$refs.form.validate()
            this.checkIfInboundEditHasError()

            if (this.$refs.form.validate()) {
                try {
                    if (typeof this.addInboundTemplate !== 'undefined') {
                        if (!this.quantityError) {
                            if (this.editedIndex === -1) { // creating inbound
                                try {
                                    let addInboundTemplate = this.addInboundTemplate    
                                    addInboundTemplate.products = JSON.stringify(addInboundTemplate.products)                                    

                                    if (this.isWarehouseConnected) {
                                        // check if create and submit
                                        if (isSubmit) { 
                                            addInboundTemplate.inbound_status = 'pending'
                                            this.isCreateAndSubmitting = true
                                        } else {
                                            this.isSavingDraft = true
                                        }
                                    } else {
                                        if (this.isWarehouse3PLProvider) {
                                            addInboundTemplate.inbound_status = 'receive-pending'
                                        }
                                    }

                                    await this.createInbound(addInboundTemplate)

                                    if (this.isWarehouseConnected) {
                                        if (isSubmit) {
                                            this.notificationMessage('Inbound has been successfully created and submitted.')
                                        } else {
                                            this.notificationMessage('Inbound has been saved as draft.')
                                        }
                                    } else {
                                        this.notificationMessage('Inbound has been created.')
                                    }

                                    this.isCreateAndSubmitting = false
                                    this.isSavingDraft = false                                    
                                    this.callPendingInbound()                                    
                                    this.setCurrentInboundTab(0)
                                    this.close()
                                } catch(e) {
                                    this.notificationError(e)
                                    console.log(e);
                                } 
                            } else { // editing inbound
                                try {
                                    let editInboundTemplate = this.editInboundTemplate
                                    editInboundTemplate.products = JSON.stringify(editInboundTemplate.products)

                                    if (this.isWarehouseConnected) {
                                        // check if create and submit
                                        if (isSubmit) { 
                                            editInboundTemplate.inbound_status = 'pending'
                                            this.isUpdateAndSubmitting = true
                                        } else {
                                            this.isUpdatingDraft = true
                                        }
                                    }

                                    // call api's for update
                                    if (!this.isWarehouse3PL) {                                       
                                        await this.updateInbound(editInboundTemplate)
                                    } else {
                                        if (this.inboundItems.is_from_inventory === 0) {
                                            await this.updateInboundFor3PL(editInboundTemplate)
                                        } else {
                                            await this.updateInboundFor3PLAddInventory(editInboundTemplate)
                                        }
                                    }

                                    if (this.isWarehouseConnected) {
                                        if (isSubmit) {
                                            this.notificationMessage('Inbound has been successfully updated and submitted.')
                                        } else {
                                            this.notificationMessage('Inbound has been updated.')
                                        }
                                    } else {
                                        this.notificationMessage('Inbound has been updated.')
                                    }
                                    
                                    this.close()
                                    this.isUpdateAndSubmitting = false
                                    this.isUpdatingDraft = false
                                    
                                    if (this.isFetchSingleInbound) {
                                        let fetchSingleInboundPayload = { wid: this.currentWarehouseSelected.id, iid: this.linkData.inbound_id }
                                        await this.fetchSingleInbound(fetchSingleInboundPayload)
                                    } 

                                    this.callOnSyncMethods()
                                } catch(e) {
                                    this.notificationError(e)
                                    console.log(e);
                                    this.isCreateAndSubmitting = false
                                    this.isSavingDraft = false
                                    this.isUpdateAndSubmitting = false
                                    this.isUpdatingDraft = false
                                }
                            } 
                        }                       
                    } else {
                        this.notificationError("Something's wrong in creating an outbound. Please reload your page and try again.")
                    }
                    
                    // if (this.hasProductDuplicates) {
                    //     this.notificationError('Duplicate entry found.')
                    // } else {
                        
                    // }
                } catch(e) {
                    console.log(e);
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
                shipping_unit: '',
                status: '',
                inbound_product_id: null,
                instructions: ''
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
                let findSelectedOption =  _.findIndex(this.inboundProducts, e => (
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
            }
        },
        isProductFieldError(item, index) {
            if (item.product_id !== "") {
                let findSelectedOption =  _.findIndex(this.inboundProducts, e => (
                    (e.product_id == item.product_id) && 
                    (e.shipping_unit == item.shipping_unit) &&
                    (e.quantity === item.quantity)
                ))

                if (item.quantity !== '') {
                    item.quantity = parseInt(item.quantity)

                    if (findSelectedOption !== index) {
                        return ''
                    } else {
                        return ''
                    }
                } 
            }
        },
        isProductSelectedOtherFieldsError(item, index) {
            if (item.product_id !== "") {
                let findSelectedOption =  _.findIndex(this.inboundProducts, e => (
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
        isFieldsDisabled(itemStatus) {
            if (!this.isWarehouse3PL) {
                if (this.editedInboundIndex > -1) {
                    if (itemStatus === 'arrived' || itemStatus === 'floor') {
                        return true
                    } else {
                        return false
                    }
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isFieldsDisabledInProducts(item) {
            if (!this.isWarehouse3PL) {
                if (item.status === 'recieved' && this.editedInboundIndex > -1) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isQuantityError(item) {
            if (!this.isWarehouse3PL) {
                if (this.editedInboundIndex > -1 && item.status === 'recieved') {
                    if (item.shipping_unit === 'carton') {
                        if (item.quantity !== '' && item.expected_carton_count !== '') {
                            if (item.quantity < item.expected_carton_count) {
                                item.hasError = true
                                this.quantityError = true
                                return true
                            } else {
                                item.hasError = false
                                this.quantityError = false
                                return false
                            }
                        } else {
                            item.hasError = false
                            this.quantityError = false
                            return false
                        }
                    } else if (item.shipping_unit === 'single_item') {
                        if (item.quantity !== '' && item.expected_total_unit !== '') {
                            if (item.quantity < item.expected_total_unit) {
                                item.hasError = true
                                this.quantityError = true
                                return true
                            } else {
                                item.hasError = false
                                this.quantityError = false
                                return false
                            }
                        } else {
                            item.hasError = false
                            this.quantityError = false
                            return false
                        }
                    }
                } else {
                    item.hasError = false
                    this.quantityError = false
                    return false
                }
            } else {
                if (this.editedInboundIndex > -1 && item.status === 'recieved') {
                    item.quantity = parseInt(item.quantity)
                    if (item.quantity === 0) {
                        item.hasError = true
                        this.quantityError = true
                        return true
                    } else {
                        item.hasError = false
                        this.quantityError = false
                        return false
                    }
                }
            }
        },
        checkIfInboundEditHasError() {
            if (this.inboundProducts.length > 0) {
                let findError = _.findIndex(this.inboundProducts, e => (typeof e.hasError !== 'undefined' && e.hasError))

                if (findError > -1) {
                    this.quantityError = true
                } else {
                    this.quantityError = false
                }
            }
        },
        // add new product
        async openAddProductDialog() {
            this.addProductDialog = true

            if (this.getAllCategoriesDropdown.length === 0) {
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

					this.setAllCategoriesDropdown(this.categoryListData)
				} else {
					this.categoryListData = []
					this.lastDataCheck = []
				}
			} else {
				this.categoryListData = this.getAllCategoriesDropdown
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
                        this.setAllCategoriesDropdown(this.categoryListData)
                    } else {
                        this.categoryListData;
                        // this.setAllCategoriesDropdown(this.categoryListData)
                    }
                } catch (e) {
                    this.notificationError(e)
                }
            }
        },
        closeProductDialog() {
            this.$nextTick(() => {
				this.editedProductItem = Object.assign({}, this.defaultProductItem)
				this.editedIndexProduct = -1
            })

			this.addProductDialog = false
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
        },
        callInboundProductsFor3PL() {
            this.$emit('callInboundProductsFor3PL', 'Create-Inbound')
        },
        customFilter(item, queryText, itemText) {
            const text = '#' + item.category_sku.toLowerCase()
            const textOne = item.category_sku.toLowerCase()
            const textTwo = itemText.toLowerCase()
            const textThree = item.name !== null ? item.name.toLowerCase() : ''
            const searchText = queryText.toLowerCase()

            return text.indexOf(searchText) > -1 || textOne.indexOf(searchText) > -1 || textTwo.indexOf(searchText) > -1 || textThree.indexOf(searchText) > -1
        },
        checkIfProductsAreReceived(item) {
            if (this.editedInboundIndex > -1) {
                if (item !== null) {
                    let findIndex = _.findIndex(item, (e) => e.status === 'recieved')
                    if (findIndex === -1) {
                        return false
                    } else {
                        return true
                    }
                }
            }
        },
        // call warehouse customer products
        clearValueOnChange(product_value) {
            if(product_value == null) {
                let val = {
                    warehouse_customer_id: null
                }

                this.$emit('callWarehouseCustomerProducts', val)
                this.clearWarehouseCustomerProducts()
            }
        },
        async callWarehouseCustomerProducts(product) {
            this.old_warehouse_customer_saved = this.inboundItems.warehouse_customer_id
            product.warehouse_customer_id = product.id

            if (this.inboundProducts.some(val => 
                val.product_id !== '' && val.product_id !== null && product.warehouse_customer_id !== null)) {
                this.showWarningChangingWarehouseCustomer = true
                this.updated_value_of_warehouse_customer = product
            } else {
                this.$emit('callWarehouseCustomerProducts', product)
                this.clearWarehouseCustomerProducts()
            }
        },
        confirmUpdateWarehouseCustomer() {
            this.$emit('callWarehouseCustomerProducts', this.updated_value_of_warehouse_customer)
            this.showWarningChangingWarehouseCustomer = false
            this.clearWarehouseCustomerProducts()
        },
        discardConfirmation() {
            this.showWarningChangingWarehouseCustomer = false
            this.inboundItems.warehouse_customer_id = this.old_warehouse_customer_saved
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
