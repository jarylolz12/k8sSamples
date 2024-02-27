<template>
    <v-dialog 
        v-if="isMobile" 
        content-class="edit-shipment-mobile-wrapper-dialog" 
        v-model="show" 
        :retain-focus="false" 
        persistent 
        scrollable>  

        <v-card>            
            <v-card-title>
                <span class="headline"> Add Shipment </span>
                <v-spacer></v-spacer>
                <v-btn icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text>
                <div class="edit-shipment-mobile-wrapper">
                    <div class="d-flex edit-shipment-mobile-tabs-fixed" v-if="loaded">
                        <v-tabs
                            height="50px"
                            fixed-tabs
                            active-class="edit-shipment-tab-active">
                            <v-tab
                                v-for="(si,key) in sidebarItemsFiltered"
                                v-bind:key="`esd-mobile-${key}`"
                                @click="selectPage(si)"
                                :class="`${(windowWidth <= 412) ? 'mobile-412' : ''}`">
                                {{ si.label }}
                            </v-tab>
                        </v-tabs>
                    </div>

                    <div class="d-flex flex-column w-full form-wrapper">
                        <div class="preloader w-full" v-if="!loaded">
                            <v-progress-circular :size="40" color="#0171a1" indeterminate>
                            </v-progress-circular>
                        </div>

                        <v-form v-if="loaded" class="" ref="formCreateShipment" action="#" @submit.prevent="">
                            <div style="padding: 16px !important;">
                                <div ref="generalInformationSectionMobile" v-if="loaded" class="content-title w-full pb-4">
                                    General Information
                                </div>

                                <div class="form-label">
                                    <span>{{ "MBL #" }}</span>
                                    <span>{{ " (Required)"}}</span>
                                </div>
                                <div class="text-field-wrapper">
                                    <v-text-field
                                        height="40px"
                                        color="#002F44"
                                        v-model="editItem.mbl_num"
                                        type="text"
                                        dense
                                        class=""
                                        :rules="rules['mbl_num']"
                                        placeholder="MBL Number"
                                        outlined
                                        hide-details="auto">
                                    </v-text-field>
                                </div>

                                <div class="form-label">
                                    <span class="text-uppercase font-10">{{ "Booking Number" }}</span>
                                </div>
                                <div class="text-field-wrapper">
                                    <v-text-field
                                        height="40px"
                                        color="#002F44"
                                        width="200px"
                                        v-model="editItem.booking_num"
                                        type="text"
                                        dense
                                        class=""
                                        placeholder="Booking Number"
                                        outlined
                                        hide-details="auto">
                                    </v-text-field>
                                </div>

                                <div class="form-label">
                                    <span class="text-uppercase font-10">{{ "From" }}</span>
                                </div>
                                <div class="text-field-wrapper">
                                    <v-select
                                        class="text-fields select-items carton-uom"
                                        :items="filteredTerminals"
                                        height='40px'
                                        outlined
                                        v-model="editItem.location_from"
                                        hide-details="auto"
                                        :menu-props="{ bottom: true, offsetY: true }"
                                        placeholder="Select From">
                                    </v-select>
                                </div>

                                <div class="form-label">
                                    <span class="text-uppercase font-10">{{ "To" }}</span>
                                </div>
                                <div class="text-field-wrapper">
                                    <v-select
                                        class="text-fields select-items carton-uom"
                                        :items="filteredTerminals"
                                        height='40px'
                                        outlined
                                        v-model="editItem.location_to"
                                        hide-details="auto"
                                        :menu-props="{ bottom: true, offsetY: true }"
                                        placeholder="Select To">
                                    </v-select>
                                </div>

                                <div class="form-label">
                                    <span>{{ "ETD" }}</span>
                                    <span>{{ " (Required)" }}</span>
                                </div>
                                <div class="text-field-wrapper dates-wrapper">
                                    <v-menu
                                        ref="menuEtd"
                                        v-model="menuEtd"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                class="text-fields" 
                                                placeholder="Select a date"
                                                outlined
                                                v-bind="attrs"
                                                v-on="on"
                                                type="date"
                                                clear-icon
                                                height="40px"
                                                readonly
                                                @input="menuEtd = false"
                                                v-model="editItem.etd"
                                                append-icon="mdi-calendar"
                                            />
                                        </template>
                                        <v-date-picker @input="menuEtd = false" v-model="editItem.etd"></v-date-picker>
                                    </v-menu>
                                </div>

                                <div class="form-label eta-label">
                                    <span>{{ "ETA" }}</span>
                                    <span>{{ " (Required)" }}</span>
                                </div>
                                <div class="text-field-wrapper dates-wrapper">
                                    <v-menu
                                        ref="menuEta"
                                        v-model="menuEta"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                class="text-fields" 
                                                placeholder="Select a date"
                                                outlined
                                                @input="menuEta = false"
                                                v-bind="attrs"
                                                v-on="on"
                                                type="date"
                                                readonly
                                                clear-icon
                                                height="40px"
                                                v-model="editItem.eta"
                                                append-icon="mdi-calendar"
                                            />
                                        </template>
                                        <v-date-picker @input="menuEta = false" v-model="editItem.eta"></v-date-picker>
                                    </v-menu>
                                </div>

                                <div class="form-label">
                                    <span>{{ "Mode" }}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-row radio-group-wrapper">
                                        <div v-bind:key="`mode-${key}`" v-for="(m,key) in modesFiltered" :class="`d-flex radio-item align-center ${(m === editItem.mode) ? 'selected' : '' }`">
                                            <label class="radio-label-wrapper">
                                                {{ m }}
                                                <input name="mode" :value="m" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.mode" />
                                                <span></span>
                                            </label>
                                            <v-radio-group v-if="1==2" v-model="editItem.mode">
                                                <v-radio color="primary" :label="m" :value="m"></v-radio>
                                            </v-radio-group>
                                            <kenetic-icon :marginLeft="8" :iconName="m.toLowerCase()"/>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row radio-group-wrapper">
                                        <div v-bind:key="`mode-air`" :class="`d-flex radio-item align-center ${(editItem.mode === 'Air') ? 'selected' : '' }`">
                                            <label class="radio-label-wrapper">
                                                {{ "Air" }}
                                                <input name="mode" value="Air" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.mode" />
                                                <span></span>
                                            </label>
                                            <kenetic-icon :marginLeft="8" iconName="air"/>
                                        </div>
                                    </div>

                                    <div class="form-label d-flex flex-row justify-space-between mt-3">
                                        <span>{{ "Type" }}</span>
                                    </div>
                                    <div :class="`d-flex radio-group-wrapper type-wrapper ${editItem.type =='' ? 'unselected' : ''}`">
                                        <div v-bind:key="`mode-${key}`" v-for="(t,key) in filteredTypes" :class="`d-flex radio-item align-center ${(t==='LCL') ? 'mr-8' : ''} ${(t === editItem.type) ? 'selected' : '' }`">
                                            <label style="position: relative;" class="radio-label-wrapper">
                                                {{ t }}
                                                <input name="type" :value="t" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.type" />
                                                <span></span>
                                            </label>
                                            <v-radio-group v-if="1==2" v-model="editItem.type">
                                                <v-radio color="primary" :label="t" :value="t"></v-radio>
                                            </v-radio-group>
                                            <kenetic-icon :color="`${editItem.type === '' ? '#B4CFE0' : '#6D858F'}`" :marginLeft="8" :iconName="t.toLowerCase()"/>
                                        </div>
                                    </div>
                                    <!-- <div class="d-flex radio-group-wrapper type-wrapper">
                                        <div v-bind:key="`mode-${key}`" v-for="(t,key) in types" :class="`d-flex radio-item align-center ${(t==='LCL') ? 'mr-8' : ''} ${(t === editItem.type) ? 'selected' : '' }`">
                                            <label class="radio-label-wrapper">
                                                {{ t }}
                                                <input name="type" :value="t" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.type" />
                                                <span></span>
                                            </label>
                                            <v-radio-group v-if="1==2" v-model="editItem.type">
                                                <v-radio color="primary" :label="t" :value="t"></v-radio>
                                            </v-radio-group>
                                            <kenetic-icon :marginLeft="8" :iconName="t.toLowerCase()"/>
                                        </div>
                                    </div> -->

                                    <div class="form-label">
                                        <span class="font-10 text-uppercase">{{ "Terminal" }}</span>
                                    </div>
                                    <div class="text-field-wrapper d-flex">
                                        <v-select
                                            class="text-fields select-items carton-uom"
                                            :items="filteredTerminals"
                                            height='40px'
                                            outlined
                                            v-model="editItem.location_to"
                                            hide-details="auto"
                                            :menu-props="{ bottom: true, offsetY: true }"
                                            placeholder="Select Terminal">
                                        </v-select>
                                    </div>

                                    <div class="form-label">
                                        <span class="font-10 text-uppercase">{{ "Carrier" }}</span>
                                    </div>                                    
                                    <div class="text-field-wrapper d-flex">
                                        <v-select
                                            class="text-fields select-items carton-uom"
                                            height='40px'
                                            outlined
                                            :items="filteredCarriers"
                                            v-model="editItem.carrier_id"
                                            hide-details="auto"
                                            :menu-props="{ bottom: true, offsetY: true }"
                                            placeholder="Select Carrier">
                                        </v-select>
                                    </div>

                                    <div class="form-label">
                                        <span class="font-10 text-uppercase">{{ "Vessel" }}</span>
                                    </div>
                                    <div class="text-field-wrapper">
                                        <v-text-field
                                            v-model="editItem.vessel"
                                            height="40px"
                                            color="#002F44"
                                            width="200px"
                                            type="text"
                                            dense
                                            class="text-fields"
                                            placeholder="Vessel"
                                            outlined
                                            hide-details="auto">
                                        </v-text-field>
                                    </div>
                                    
                                    <div class="form-label">
                                        <span class="font-10 text-uppercase">{{ "Voyage Nummber" }}</span>
                                    </div>
                                    <div class="text-field-wrapper">
                                        <v-text-field
                                            v-model="editItem.voyage_number"
                                            height="40px"
                                            color="#002F44"
                                            width="200px"
                                            type="text"
                                            dense
                                            class="text-fields"
                                            placeholder="Voyage Number"
                                            outlined
                                            hide-details="auto">
                                        </v-text-field>
                                    </div>
                                </div>

                                <div ref="purchaseOrderSectionMobile" v-if="loaded" class="content-title w-full content-title-mobile d-flex pb-0 mt-3">
                                    Purchase Orders
                                </div>
                                <div class="section-mobile">
                                    <div v-bind:key="`poi-mobile-${key}`" v-for="(poi, key) in purchaseOrderItems" class="purchase-order-item-mobile">
                                        <div class="select-purchase-order-wrapper d-flex" style="position:relative;">
                                            <v-autocomplete
                                                :filter="customFilterPo"
                                                v-model="poi.purchase_order_id"
                                                spellcheck="false"
                                                class="select-purchase-order select-purchase-order-mobile"
                                                :items="purchaseOrderOptions"
                                                placeholder="Select PO"
                                                outlined
                                                item-text="po_number"
                                                item-value="id"
                                                :menu-props="{ contentClass: 'po-lists-items mobile', ...menuProps}"
                                                hide-details="auto"
                                                @change="updateProducts(poi, key)"
                                                clearable>

                                                <template v-slot:item="{ item }">
                                                    <div class="d-flex flex-column po-mobile-dropdown-wrapper" style="width: 100%;">
                                                        <div style="width: 100%;" class="d-flex first-row justify-space-between pt-2 pb-2">
                                                            <div class="po-number-header-mobile">
                                                                <span>PO#</span>
                                                                <span class="font-medium">{{ " " +item.po_number }}</span>
                                                            </div>
                                                            <div style="font-size: 16px !important;">
                                                                {{ currencyNumberFormat(item.total) }}
                                                            </div>
                                                        </div>
                                                        <!-- <div class="d-flex second-row" style="width: 100%; padding-bottom: 4px;">
                                                            <p class="po-warehouse-name">{{ getWarehouseName(item) }}</p>
                                                        </div> -->
                                                        <div class="d-flex third-row">
                                                            <p class="po-ship-to mb-2">{{ item.ship_to }}</p>
                                                        </div>
                                                        <div style="width: 100%; padding-bottom: 8px;" class="d-flex fourth-row flex-row justify-space-between">
                                                            <div class="po-date">
                                                                <span class="po-mobile-title">Date:</span>
                                                                <span>{{ ' ' + formatDate(item.created_at) }}</span>
                                                            </div>
                                                            <div class="po-crd">
                                                                <span class="po-mobile-title">CRD:</span>
                                                                <span>{{ ' ' + formatDate(item.cargo_ready_date) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex last-row" 
                                                            style="width: 100%; padding-bottom: 16px;">
                                                            <a class="last-row-status">
                                                                {{ getPoStatus(item.status) }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-autocomplete>
                                            <div class="delete-btn-wrapper delete-btn-wrapper-mobile">
                                                <v-btn class="btn-white mr-4" @click="removePurchaseOrderItem(key)">
                                                    <!-- <custom-icon iconName="trash-can" color="#ff5252"></custom-icon> -->
                                                    <img src="@/assets/icons/delete-po.svg" alt="Remove" width="18px" height="18px">
                                                </v-btn>
                                                <button v-if="1==2" @click.stop="removePurchaseOrderItem(key)" class="btn-white">
                                                    <custom-icon iconName="trash-can" color="#ff5252"></custom-icon>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="select-all-wrapper d-flex">
                                            <div class="checkbox-wrapper">
                                                <!-- <label :class="`${poi.selectAll ? 'checked': ''}`" 
                                                    style="position: relative;">
                                                    <input @click="selectAllProducts(poi, key)" 
                                                    type="checkbox" 
                                                    :checked="poi.selectAll" class="" />
                                                    <span>Select All Products</span>
                                                </label> -->

                                                <label :class="`${poi.selectAll ? 'checked': ''}`" class="label-wrapper-check" style="position: relative;">
                                                    <generic-icon :marginLeft="0" :iconName="`${(poi.selectAll) ? 'checked' : 'not-checked'}`"></generic-icon>
                                                    <input  @click.prevent="selectAllProducts(poi, key)" style="position: absolute; opacity: 0;" type="checkbox" :checked="poi.selectAll" class="" :disabled="purchaseOrderItems.length == 0" />
                                                    <span style="color: #4A4A4A; font-weight: 400; font-size: 14px;">Select All Products</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="table-wrapper">
                                            <v-data-table 
                                                :headers="headersProductsMobile" 
                                                :items="poi.products"
                                                mobile-breakpoint="769"
                                                :page="1"
                                                :items-per-page="100"
                                                class="purchase-orders-table table-mobile"
                                                hide-default-footer
                                                style="box-shadow: none !important"
                                                fixed-header
                                                hide-default-header
                                                ref="create-shipment-purchase-order-table create-shipment-purchase-order-table-mobile">
                                                
                                                <template v-slot:[`item.product_id`]="{ item }">
                                                    <div class="mobile-items-wrapper">
                                                        <div v-if="typeof item.addSpecial=='undefined' && typeof item.special=='undefined'" style="padding-bottom: 8px;" class="d-flex justify-space-between">
                                                            <div style="justify-content: right !important;" class="d-flex product-label product-title">{{ `Product ${item.index}` }}</div>
                                                            <div @click.prevent="removeProductFromPurchaseOrders(key,item)">
                                                                <generic-icon iconName="close"></generic-icon>
                                                            </div>
                                                        </div>

                                                        <div  v-if="typeof item.addSpecial=='undefined' && typeof item.special=='undefined'" style="width: 100%;" class="d-flex flex-column">
                                                            <div class="d-flex" style="width: 100%;">
                                                                <div style="width: 25%;" class="align-self-center d-flex product-label">Product</div>
                                                                <div style="width: 75%;">
                                                                    <v-autocomplete
                                                                        v-if="typeof item.addSpecial=='undefined' && typeof item.special=='undefined'"
                                                                        :filter="customFilterProduct"
                                                                        v-model="item.product_id"
                                                                        spellcheck="false"
                                                                        class="select-product select-purchase-order"
                                                                        :class="validateProduct(item, key)"
                                                                        :items="poi.product_options"
                                                                        @change="updateProductPurchaseOrder(poi.product_options,item, key)"
                                                                        placeholder="Select Product"
                                                                        outlined
                                                                        item-text="product.name"
                                                                        item-value="product_id"
                                                                        :menu-props="{ contentClass: 'po-lists-items products', ...menuProps }"
                                                                        hide-details="auto"
                                                                        clearable>

                                                                        <template v-slot:item="{ item }">
                                                                            <div class="d-flex flex-row justify-space-between product-item-wrapper">
                                                                                <div style="width: 75%;" class="d-flex flex-column">
                                                                                    <div>
                                                                                        <span class="product-category-sku">#{{ item.product.category_sku }}</span>
                                                                                    </div>
                                                                                    <div>
                                                                                        <p class="mb-3"> {{ item.product.name }} </p>
                                                                                        <p class="product-unit-price">
                                                                                            ${{ item.product.unit_price !== null ? item.product.unit_price.toFixed(2) : '0.00' }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div style="width: 25%;" class="d-flex justify-end">
                                                                                    <img class="product-image" :src="getImgUrl(item.product.image)" alt="" style="border-radius: 4px;">
                                                                                </div>
                                                                            </div>
                                                                        </template>
                                                                    </v-autocomplete>
                                                                </div>
                                                            </div>
                                                            <div v-if="validateProduct(item, key) === 'error'" class="d-flex error-message" style="width: 100%;">
                                                                <div style="width: 25%;"></div>
                                                                <div class="pt-1" style="width: 75%; font-size: 12px; color:red; text-align: start;">Product has already been selected.</div>
                                                            </div>
                                                            <div class="d-flex" style="width: 100%;">
                                                                <div style="width: 25%;" class="align-self-center d-flex">
                                                                </div>
                                                                <div class="checkbox-wrapper py-3" style="width: 75%;">
                                                                    <!-- <label :class="`${item.addAll ? 'checked': ''}`" style="position: relative;">
                                                                        <input @click.prevent="addAllCartons(item, key)" style="position: absolute; opacity: 0;" type="checkbox" v-model="item.addAll" :checked="item.addAll" class="" />
                                                                        <span>Add All</span>
                                                                    </label> -->

                                                                    <label :class="`${item.addAll ? 'checked': ''}`" style="position: relative;" class="label-wrapper-check">
                                                                        <generic-icon :iconName="`${(item.addAll) ? 'checked' : 'not-checked'}`"></generic-icon>
                                                                        <input @click.prevent="addAllCartons(item, key)" style="position: absolute; opacity: 0;" type="checkbox" v-model="item.addAll" :checked="item.addAll" class="" />
                                                                        <span>Add All</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex" style="width: 100%; padding-bottom: 8px;">
                                                                <div style="width: 25%;" class="d-flex">
                                                                    <div style="width: 100%;" class="d-flex align-self-center product-label">
                                                                        Carton
                                                                    </div>
                                                                </div>
                                                                <div style="width: 75%;" class="d-flex">
                                                                    <div style="width: 50%;" class="d-flex align-self-center">
                                                                        <v-text-field
                                                                        background-color="#ffffff"
                                                                        height="40px"
                                                                        color="#002F44"
                                                                        width="200px"
                                                                        :disabled="item.product_id==''"
                                                                        v-model="item.carton"
                                                                        type="number"
                                                                        dense
                                                                        :rules="cartonRules"
                                                                        class="carton carton-mobile"
                                                                        :class="item.product_id!=='' && (item.carton == 0 || item.carton == '0') ? 'error-input' : ''"
                                                                        placeholder=""
                                                                        @keyup="updateUnit(item, key)"
                                                                        outlined
                                                                        hide-details="auto">
                                                                        </v-text-field>
                                                                    </div>
                                                                    <div style="padding-left: 8px; width: 25%;" class="d-flex align-self-center product-label justify-end">
                                                                        Unit
                                                                    </div>
                                                                    <div style="width: 50%;" class="d-flex align-self-center">
                                                                    <v-text-field
                                                                        background-color="#ffffff"
                                                                        height="40px"
                                                                        color="#002F44"
                                                                        width="200px"
                                                                        :disabled="item.product_id==''"
                                                                        v-model="item.unit"
                                                                        @keyup="updateCarton(item, key)"
                                                                        type="number"
                                                                        dense
                                                                        class="unit unir-mobile"
                                                                        placeholder="Unit"
                                                                        outlined
                                                                        hide-details="auto">
                                                                        </v-text-field>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex" style="width: 100%; padding-bottom: 8px;">
                                                                <div style="width: 25%;" class="d-flex align-self-center product-label">
                                                                    Unit Price
                                                                </div>
                                                                <div style="width: 75%;" class="d-flex align-self-center">
                                                                    <v-text-field
                                                                    background-color="#ffffff"
                                                                    height="40px"
                                                                    color="#002F44"
                                                                    width="200px"
                                                                    v-model="item.unit_price"
                                                                    type="number"
                                                                    dense
                                                                    class="unit-price unit-price-mobile"
                                                                    placeholder="Unit"
                                                                    outlined
                                                                    hide-details="auto">
                                                                    </v-text-field>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex" style="width: 100%: ">
                                                                <div style="width: 25%;" class="d-flex align-self-center product-label">
                                                                    Amount
                                                                </div>
                                                                <div style="width: 75%;" class="d-flex align-self-center">
                                                                    <div v-if="typeof item.special=='undefined' && typeof item.addSpecial=='undefined'" class="d-flex amount text-right w-full justify-end">
                                                                        {{ '$'+calculateAmount(item) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-data-table>
                                        </div>
                                        <div v-if="poi.purchase_order_id!==''" class="add-product-purchase-order-wrapper add-product-wrapper button-add">
                                            <a style="cursor: pointer; display: flex; flex-direction: row; padding-bottom: 8px; padding-top: 8px;" @click="addProductToPurchaseOrders(key)">
                                                <span style="margin-right: 6px;">
                                                    <generic-icon iconName="plus"></generic-icon>
                                                </span>
                                                <span>{{ "Add Product" }}</span>
                                            </a>
                                        </div>
                                        <div v-if="poi.purchase_order_id!==''" class="add-product-purchase-order-wrapper add-product-wrapper d-flex product-total mt-2">
                                            <div class="text-right" style="width: 70%;">
                                                Total
                                            </div>
                                            <div class="text-right" style="width: 30%;">
                                                {{
                                                    calculateTotal(key)
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-product-purchase-order-wrapper mt-3 mb-6">
                                        <v-btn class="btn-white mr-4 shipment-header-button-btn" @click="addPurchaseOrderItem">
                                            <span>{{ "+ Add Purchase Order" }}</span>
                                        </v-btn>
                                    </div>
                                </div>

                                <div ref="containerSectionMobile" v-if="loaded" class="content-title w-full content-title-container content-title-mobile d-flex">
                                    Containers
                                </div>
                                <div class="section-mobile">
                                    <div class="item">
                                        <div class="table-wrapper">
                                            <v-data-table 
                                            :headers="headersContainersMobile" 
                                            :items="containerItems"
                                            mobile-breakpoint="769"
                                            :page="1"
                                            :items-per-page="100"
                                            class="table-mobile"
                                            hide-default-footer
                                            style="box-shadow: none !important"
                                            fixed-header
                                            hide-default-header
                                            ref="create-shipment-container-table">

                                                <template v-slot:[`item.container_num`]="{ item }">
                                                    <div class="d-flex flex-column" style="width: 100%; padding-bottom: 8px;">
                                                        <div style="width: 100%; padding-bottom: 6px; padding-top: 6px;" class="d-flex">
                                                            <div style="width: 25%; justify-content: left;" class="d-flex align-self-center product-label">
                                                                Container #
                                                            </div>
                                                            <div style="width: 65%;" class="d-flex align-self-center">
                                                                <v-text-field
                                                                background-color="#ffffff"
                                                                height="40px"
                                                                color="#002F44"
                                                                width="200px"
                                                                v-model="item.container_num"
                                                                type="text"
                                                                dense
                                                                class="container-num container-field text-fields"
                                                                placeholder="Container #"
                                                                outlined
                                                                hide-details="auto">
                                                                </v-text-field>
                                                            </div>
                                                            <div style="width: 10%;">
                                                            <a @click.stop="removeContainer(item)" style="cursor: pointer; height: 40px;" class="d-flex w-full align-self-center justify-center align-center">
                                                                <span style="color: red;" class="d-flex align-center">
                                                                    <img src="@/assets/icons/close-red.svg" alt="close" width="16px" height="16px">
                                                                </span>
                                                            </a>
                                                            </div>
                                                        </div>
                                                        <div style="width: 100%; padding-bottom: 6px;" class="d-flex">
                                                            <div style="width: 25%; justify-content: left;" class="d-flex align-self-center product-label">
                                                                Size
                                                            </div>
                                                            <div style="width: 75%;" class="d-flex align-self-center">
                                                                <v-select
                                                                class="text-fields select-items select-items-general size"
                                                                    :items="filteredContainerSizes"
                                                                    height='40px'
                                                                    outlined
                                                                    v-model="item.size"
                                                                    hide-details="auto"
                                                                    :menu-props="{ bottom: true, offsetY: true }"
                                                                    placeholder="Select size">
                                                                </v-select>
                                                            </div>
                                                        </div>
                                                        <div style="width: 100%; padding-bottom: 6px;" class="d-flex">
                                                            <div style="width: 25%; justify-content: left;" class="d-flex align-self-center product-label">
                                                                Volume
                                                            </div>
                                                            <div style="width: 75%;" class="d-flex align-self-center">
                                                                <v-text-field
                                                                background-color="#ffffff"
                                                                height="40px"
                                                                color="#002F44"
                                                                width="200px"
                                                                v-model="item.cbm"
                                                                type="text"
                                                                dense
                                                                class="container-field text-fields"
                                                                placeholder="Volume"
                                                                outlined
                                                                hide-details="auto">
                                                                </v-text-field>
                                                            </div>
                                                        </div>
                                                        <div style="width: 100%; padding-bottom: 6px;" class="d-flex">
                                                            <div style="width: 25%; justify-content: left;" class="d-flex align-self-center product-label">
                                                                Weight
                                                            </div>
                                                            <div style="width: 75%;" class="d-flex align-self-center">
                                                                <v-text-field
                                                                background-color="#ffffff"
                                                                height="40px"
                                                                color="#002F44"
                                                                width="200px"
                                                                v-model="item.kg"
                                                                type="text"
                                                                dense
                                                                class="container-field text-fields"
                                                                placeholder="Weight"
                                                                outlined
                                                                hide-details="auto">
                                                                </v-text-field>
                                                            </div>
                                                        </div>
                                                        <div style="width: 100%; padding-bottom: 6px;" class="d-flex">
                                                            <div style="width: 25%; justify-content: left;" class="d-flex align-self-center product-label">
                                                                Cartons
                                                            </div>
                                                            <div style="width: 75%;" class="d-flex align-self-center">
                                                                <v-text-field
                                                                background-color="#ffffff"
                                                                height="40px"
                                                                color="#002F44"
                                                                width="200px"
                                                                v-model="item.cartons"
                                                                type="text"
                                                                dense
                                                                class="container-field text-fields"
                                                                placeholder="Cartons"
                                                                outlined
                                                                hide-details="auto">
                                                                </v-text-field>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-data-table>
                                        </div>
                                    </div>
                                    <div style="padding-top: 12px;" class="add-product-purchase-order-wrapper add-container-btn-wrapper">
                                        <v-btn class="btn-white mr-4 shipment-header-button-btn" @click="addContainer">
                                            <span>{{ "+ Add Container" }}</span>
                                        </v-btn>
                                    </div>
                                </div>
                            </div>
                        </v-form>                    
                    </div>
                </div>
            </v-card-text>

            <v-card-actions v-if="loaded">
                <div class="d-flex flex-row btn-save-group-mobile">
                    <button @click.stop="addShipment" class="btn-blue mr-2">
                        <span>{{ "Save" }}</span>
                    </button>
                    <v-btn @click.stop="close" class="btn-white shipment-header-button-btn">
                        <span>{{ "Cancel" }}</span>
                    </v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style lang="scss">
@import "./scss/bookingShipmentDialogMobile.scss";

.v-dialog {
    &.edit-shipment-mobile-wrapper-dialog {
        margin: 0;
        height: 100%;
        max-height: 100% !important;

        .v-card__text {
            padding: 0 !important;

            .edit-shipment-mobile-wrapper {
                .edit-shipment-mobile-tabs-fixed {
                    position: fixed;
                    background-color: #fff;
                    z-index: 1;
                }

                .form-wrapper {
                    padding-top: 50px;

                    .preloader {
                        padding-top: 75px;
                    }

                    .v-input {
                        &.text-fields {
                            .v-input__control {
                                .v-input__slot {
                                    min-height: 40px !important;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

.po-lists-items {
    &.mobile {
        max-width: 310px;

        .v-list {
            .v-list-item {
                border-bottom: 1px solid #EBF2F5;
                padding: 0 14px;

                .po-mobile-dropdown-wrapper {
                    .po-number-header-mobile {
                        color: #0171A1;
                        font-size: 16px;
                    }

                    .po-ship-to,
                    .po-mobile-title {
                        color: #6D858F;
                    }
                    
                    .po-date,
                    .po-crd {
                        font-size: 14px;
                    }
                }    
            }
        }        
    }

    &.products {
        min-width: 310px !important;

        .v-list {
            .v-list-item {
                border-bottom: 1px solid #EBF2F5;
                padding: 8px 14px;
            }
        }   
    }
}


</style>

<script>
import KeneticIcon from '../../Icons/KeneticIcon'
import GenericIcon from '../../Icons/GenericIcon'
import CustomIcon from '../../Icons/CustomIcon'
import globalMethods from '../../../utils/globalMethods'
import moment from 'moment'
import _ from 'lodash'
import { mapActions, mapGetters } from 'vuex'
import jQuery from 'jquery'

export default {
	props: ['sidebarItems', 'isMobile', 'show', 'loaded', 'valid', 'editItem', 'rules', 'filteredTerminals', 'windowWidth', 'menuEtd', 'menuEta', 'modes', 'types', 'filteredCarriers','customFilterPo', 'purchaseOrderItems', 'purchaseOrderOptions','menuProps', 'headersProducts','cartonRules', 'containerItems', 'headersContainers', 'filteredContainerSizes'],
    components: {
        GenericIcon,
        KeneticIcon,
        CustomIcon
    },
    data: () => ({
        productDuplicate: false,
        headersProductsMobile: [{
            text: "Product",
            align: "start",
            sortable: false,
            value: "product_id",
            fixed: true,
            width: '20%',
            textAlign: "left"
        }],
        headersContainersMobile: [{
            text: "Container #",
            align: "start",
            sortable: false,
            value: "container_num",
            fixed: true,
            width: '16.66%'
        }]
    }),
    mounted() {

        //insert header title into the first child of the body
        if ( this.isMobile ) {
            this.insertHeaderTitle()
        }
    },
	methods: {
        ...globalMethods,
        ...mapActions(['fetchCarriers', 'fetchTerminalRegions', 'fetchContainers', 'fetchShipmentDetails','createShipment']),
        customFilterProduct(item, queryText, itemText) {
            this.$emit('customFilterProduct', item, queryText, itemText)
        },
        insertHeaderTitle() {
            
            //use jquery to insert into DOM
            jQuery('.header-title-wrapper-main').remove()
            jQuery('body').prepend('<div class="d-flex header-title-wrapper header-title-wrapper-main">'+jQuery('.header-title-wrapper-add-shipment').html()+'</div')

            //add event listener 
            jQuery('.btn-close-header').click(e => {
                e.preventDefault()
                
                //close modal
                this.close()
                
                //remove from DOM too
                jQuery('.header-title-wrapper-main').remove()
            })
        },
        addShipment() {
            if ( this.$refs['formCreateShipment'].validate() && !this.productDuplicate ) {
                let payload = this.editItem
                payload.purchase_orders = this.purchaseOrderItems
                payload.containers = this.containerItems
                let supplier_timestamps = []
                if ( payload.purchase_orders.length > 0 ) {
                    payload.purchase_orders.map(() => {
                        supplier_timestamps.push(new Date().getTime())
                    })
                }

                payload.date_id = new Date().getTime()
                payload.supplier_timestamps = supplier_timestamps

                this.createShipment(payload).then(() => {
                    this.close()
                    this.$emit('addShipmentSuccess')                   
                    this.$emit('reloadShipments')

                }).catch(e => {
                    console.log(e)
                })
            }
        },
        removeContainer(item) {
            this.$emit('removeContainer', item)
        },
        addContainer(){
            this.$emit('addContainer')
        },
        selectAllProducts(item, key) {
            this.$emit('selectAllProducts', item, key)
        },
        calculateAmount(item) {
            let total = parseFloat(item.unit_price) * parseFloat(item.unit)
            item.amount = total
            return `${isNaN(total) ? '0.00' : total.toFixed(2)}`
        },
        removeProductFromPurchaseOrders(key, item) {
            this.$emit('removeProductFromPurchaseOrders', key, item)
        },
        updateCarton(item, key) {
            this.$emit('updateCarton', item, key)
        },
        updateUnit(item, key) {
            this.$emit('updateUnit', item, key)
        },
        formatDate(value) {
            return moment(value).format('MMM DD, YYYY')
        },
        addAllCartons(item, key) {
            this.$emit('addAllCartons', item, key)
        },
        addProductToPurchaseOrders(key) {
            this.$emit('addProductToPurchaseOrders', key, 1)
        },
        removePurchaseOrderItem(key) {
            this.$emit('removePurchaseOrderItem', key)
        },
        getImgUrl(pic){
            // this.$emit('getImgUrl',pic)

            //get image url directory from po online
            let imageUrl = 'https://po.shifl.com/storage/'

            //if pic is not null and defined
            if (typeof pic !== 'undefined' && pic !== null) {
                if (pic.includes(imageUrl) !== 'undefined' && !pic.includes(imageUrl)) {
                    //concatonate the imageurl with the pic
                    let newImage = `${imageUrl}${pic}`
                    return newImage
                } else
                    return pic
            } else
                return require('@/assets/icons/default-product-icon.svg')
        },
        addPurchaseOrderItem() {
            this.$emit('addPurchaseOrderItem')
        },
        calculateTotal(key) {
            let total = 0
            this.purchaseOrderItems[key].products.map(p => {
                total += parseFloat(p.unit_price) * parseFloat(p.unit)
            })
            return `${isNaN(total) ? '$0.00' : '$'+total.toFixed(2)}`
        },
        validateProduct(item, key) {
            this.productDuplicate = false
            let returnClass = ''
            if ( item.product_id !== null && item.product_id !=='' ) {
                let productKey = this.purchaseOrderItems[key].products.indexOf(item)
                let findSelectedOption = _.findIndex(this.purchaseOrderItems[key].products, e => (e.product_id == item.product_id))
                if ( findSelectedOption !== productKey ) {
                    returnClass = 'error'
                    this.productDuplicate = true
                }
            }
            return returnClass
        },
        updateProductPurchaseOrder(options, item, key) {
            this.$emit('updateProductPurchaseOrder',options, item, key)
        },
        getWarehouseName(item) {
            this.$emit('getWarehouseName', item)
        },
        getPoStatus(status) {
            this.$emit('getPoStatus', status)
        },
        updateProducts(item, key) {
            this.$emit('updateProducts', item, key)
        },
		selectPage(si) {
            this.$refs[`${si.reference}Mobile`].scrollIntoView({
                block: 'start',
                behavior: 'smooth'
            })   
			this.$emit('selectPage', si)
		},
        close() {
            this.$emit('close')
        },
        ucFirst(str) {
            let pieces = str.split(" ")
            for ( let i = 0; i < pieces.length; i++ ) {
                let j = pieces[i].charAt(0).toUpperCase()
                pieces[i] = j + pieces[i].substr(1)
            }
            return pieces.join(" ")
        },
	},
    computed: {
        ...mapGetters([
            'getCarriers',
            'getTerminalRegions',
            'getCreateShipmentLoading',
            'getShipmentContainerSizes',
            'getUser'
        ]),
        filteredTypes() {
            //map types
            let types = [{
                label: 'Truck',
                options: ['FTL','LTL']
            },
            {
                label: 'Air',
                options: ['FCL','LCL']
            },
            {
                label: 'Ocean',
                options: ['FCL','LCL']
            }]
            
            let setMode = this.editItem.mode
            if ( this.editItem.mode === '')
                setMode = 'Ocean'

            //return options
            let getOptions = _.find(types, e => (e.label === setMode))
            return getOptions.options

        },
        modesFiltered() {
            let filteredItems = []
            this.modes.map( m => {
                if ( m!=='Air' ) {
                    filteredItems.push(m)
                }
            })
            return filteredItems
        },
        sidebarItemsFiltered() {
            let filteredItems = []
            this.sidebarItems.map( sbi => {
                let {
                    label,
                    ...otherProps
                } = sbi
                label = this.ucFirst(label.toLowerCase())
                filteredItems.push({
                    ...otherProps,
                    label
                })
            })
            return filteredItems
        }
    }
}
</script>