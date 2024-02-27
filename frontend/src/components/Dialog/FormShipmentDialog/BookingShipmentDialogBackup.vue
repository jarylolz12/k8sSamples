<template>
    <div style="position: relative;">
        <add-manual-po-modal
            v-if="addManualPoModalView"
            :show="addManualPoModalView"
            :item="editItem"
            @close="addManualPoModalView = false"
            className="add-manual-po-dialog-wrapper"
            id="add-manual-po-dialog-ultimate-wrapper"
        >
            <template v-slot:title>
                <h2>
                    {{ "Add Manual PO" }}
                </h2>
            </template>
            <template v-slot:actions="{ footer }">
                <div class="d-flex footer">
                    <v-btn style="margin-right: 8px;" :disabled="footer.addPoLoading" @click.stop="footer.addPo"  class="save-btn btn-blue" text >
                        <span >{{ "Add PO" }}</span>
                    </v-btn>
                    <v-btn class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click="footer.close">
                        <span>{{ "Cancel" }}</span>
                    </v-btn>
                </div>
            </template>
        </add-manual-po-modal>
        <v-dialog v-if="!isMobile" @click:outside="clickOutside" v-model="show" max-width="1376px" :content-class="`${className} create-shipment-ultimate-wrapper`">
            <v-card id="edit-shipment-dialog-id" class="edit-shipment-dialog">
                <v-card-title class="headline">
                    <slot name="title"></slot>
                    <button icon dark class="btn-close" @click.stop="close">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </v-card-title>
                <v-card-text class="pb-0">
                    <div id="card-text-wrapper" ref="cardTextWrapper" class="d-flex flex-row">
                        <slot v-bind:mainContent="{ sidebarItems: sidebarItems, selectPage: selectPage, loaded: loaded }" name="sidebar"></slot>
                        <div class="preloader d-flex w-full justify-center align-center pt-4 pb-4" v-if="!loaded">
                            <v-progress-circular :size="40" color="#0171a1" indeterminate>
                            </v-progress-circular>
                        </div>
                        <div v-if="loaded" class="d-flex flex-column edit-shipment-dialog-main-content second-column">
                            <div id="generalInformationSection" ref="generalInformationSection" class="d-flex flex-row justify-space-between">
                                <div v-if="loaded" class="content-title">
                                {{ "General Information" }}
                                </div>
                            </div>
                            <v-form v-if="loaded" class="" :ref="reference" action="#" @submit.prevent="">
                                <div class="d-flex flex-row">
                                    <div class="form-edit-shipment-form-first-column form-wrapper">
                                        <div class="d-flex flex-row">
                                            <div class="d-flex flex-column">
                                                <div class="form-label">
                                                    <span>{{ "YOUR ROLE" }}</span>
                                                </div>
                                                <div class="d-flex radio-group-wrapper">
                                                    <div v-bind:key="`role-${key}`" v-for="(r,key) in roles" :class="`d-flex radio-item align-center ${(r === editItem.role) ? 'selected' : '' }`">
                                                        <label style="position: relative;" class="radio-label-wrapper">
                                                            {{ ucFirst(r) }}
                                                            <input name="role" :value="r" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.role" />
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-label">
                                            <span>{{ "SHIPPER" }}</span>
                                        </div>
                                        <div id="select-autocomplete-3" class="select-items-wrapper select-autocomplete">
                                            <v-autocomplete
                                                v-model="editItem.shipper"
                                                spellcheck="false"
                                                :items="shippers"
                                                placeholder="Select shipper"
                                                outlined
                                                item-text="name"
                                                item-value="id"
                                                :height="40"
                                                :menu-props="{ contentClass: 'po-lists-items',...menuProps}"
                                                hide-details="auto"
                                                clearable>
                                                <template v-slot:item="{ item }">
                                                    <div class="d-flex flex-column" style="width: 100%;">
                                                        <div style="width: 100%;" class="d-flex first-row justify-space-between">
                                                            <div>
                                                                <span>{{ item.name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-autocomplete>
                                        </div>
                                        <div class="form-label">
                                            <span style="text-transform: uppercase;">{{ "Shipper’s details info" }}</span>
                                        </div>
                                        <div id="text-area-wrapper-5" class="text-field-wrapper input-text-wrapper textarea-wrapper">
                                            <v-textarea
                                                class="text-fields"
                                                outlined
                                                :height="76"
                                                v-model="editItem.shippers_details_info"
                                                placeholder="Shipper's info"
                                                hide-details="auto"
                                                autocomplete="off">
                                            </v-textarea>
                                        </div>
                                        <div class="form-label">
                                            <span>{{ "FROM" }}</span>
                                        </div>
                                        <div id="dropdown-wrapper-5" class="text-field-wrapper dropdown-wrapper">
                                            <v-select
                                                attach="#dropdown-wrapper-5"
                                                class="text-fields select-items carton-uom"
                                                :items="filteredTerminalRegions"
                                                height='40px'
                                                outlined
                                                placeholder="Enter Location (Port, Warehouse etc.) "
                                                v-model="editItem.location_from"
                                                hide-details="auto"
                                                :menu-props="{ bottom: true, offsetY: true }">
                                            </v-select>
                                        </div>
                                        <div class="form-label">
                                            <span>{{ "TO" }}</span>
                                        </div>
                                        <div id="dropdown-wrapper-4" class="text-field-wrapper dropdown-wrapper">
                                            <v-select
                                                attach="#dropdown-wrapper-4"
                                                class="text-fields select-items carton-uom"
                                                :items="filteredTerminalRegions"
                                                height='40px'
                                                outlined
                                                placeholder="Enter Location (Port, Warehouse etc.) "
                                                v-model="editItem.location_to"
                                                hide-details="auto"
                                                :menu-props="{ bottom: true, offsetY: true }">
                                            </v-select>
                                        </div>
                                        <div style="margin-bottom: 5px !important" class="form-label d-flex flex-row justify-space-between align-end">
                                            <span>{{ "Mode" }}</span>
                                        </div>
                                        <div class="d-flex radio-group-wrapper radio-group-wrapper-web">
                                            <div v-bind:key="`mode-${key}`" v-for="(m,key) in modes" :class="`d-flex radio-item align-center ${(m === editItem.mode) ? 'selected' : '' }`">
                                                <label style="position: relative;" class="radio-label-wrapper">
                                                    {{ m }}
                                                    <input name="mode" :value="m" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.mode" />
                                                    <span></span>
                                                </label>
                                                <kenetic-icon :marginLeft="8" :iconName="m.toLowerCase()"/>
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 5px !important;" class="form-label d-flex flex-row justify-space-between align-end">
                                            <span>{{ "RAIL" }}</span>
                                        </div>
                                        <div class="d-flex radio-group-wrapper">
                                            <div v-bind:key="`mode-${key}`" v-for="(t,key) in anotherTypes" :class="`d-flex radio-item align-center ${(t === type) ? 'selected' : '' }`">
                                                <v-radio-group v-model="type">
                                                    <v-radio color="primary" :label="t" :value="t"></v-radio>
                                                </v-radio-group>
                                                <kenetic-icon :marginLeft="8" :iconName="t.toLowerCase()"/>
                                            </div>
                                        </div>
                                        <div class="checkbox-wrapper-create checkbox-wrapper-desktop checkbox-wrapper-notify-party">
                                            <label :class="`${editItem.is_notify_party ? 'checked': ''}`" style="position: relative;">
                                                <generic-icon :marginLeft="0" :iconName="`${(editItem.is_notify_party) ? 'checked' : 'not-checked'}`"></generic-icon>
                                                <input @click.prevent="notifyParty(editItem)" style="position: absolute; opacity: 0;" type="checkbox" :checked="editItem.is_notify_party" class="" />
                                                <span style="color: #4A4A4A; padding-left: 12px !important;">Notify party is different from Consignee</span>
                                            </label>
                                        </div>
                                        <div class="form-label">
                                            <span style="text-transform: uppercase; color: #B4CFE0 !important;">{{ "NOTIFY" }}</span>
                                        </div>
                                        <div id="text-area-wrapper-6" class="text-field-wrapper input-text-wrapper textarea-wrapper">
                                            <v-textarea
                                                class="text-fields"
                                                outlined
                                                :height="76"
                                                v-model="editItem.notify_details_info"
                                                placeholder="Select Consignee for notify details"
                                                hide-details="auto"
                                                autocomplete="off">
                                            </v-textarea>
                                        </div>
                                    </div>
                                    <div style="padding-top: 72px;" class="form-edit-shipment-form-second-column form-wrapper">
                                        <div class="form-label">
                                            <span>{{ "CONSIGNEE" }}</span>
                                        </div>
                                        <div id="select-autocomplete-1" class="select-items-wrapper select-autocomplete consignee-wrapper">
                                            <v-autocomplete
                                                attach="#select-autocomplete-1"
                                                v-model="editItem.consignee_id"
                                                spellcheck="false"
                                                :items="consignees"
                                                placeholder="Select consignee"
                                                outlined
                                                item-text="name"
                                                item-value="id"
                                                :height="40"
                                                :menu-props="{ contentClass: 'po-lists-items',...menuProps}"
                                                hide-details="auto"
                                                clearable>
                                                <template v-slot:item="{ item }">
                                                    <div class="d-flex flex-column" style="width: 100%;">
                                                        <div style="width: 100%; padding-bottom: 4px;" class="d-flex first-row justify-space-between">
                                                            <div class="consignee-name">
                                                                <span style="line-height: 20px;">{{ item.name }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex third-row consignee-address">
                                                            <span style="line-height:20px;">
                                                                {{
                                                                    item.address
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-autocomplete>
                                        </div>
                                        <div class="form-label">
                                            <span style="text-transform: uppercase;">{{ "CONSIGNEE’S DETAILS INFO" }}</span>
                                        </div>
                                        <div id="text-area-wrapper-3" class="text-field-wrapper input-text-wrapper textarea-wrapper">
                                            <v-textarea
                                                class="text-fields"
                                                outlined
                                                :height="76"
                                                v-model="editItem.consignee_details_info"
                                                placeholder="Enter consignees details info"
                                                hide-details="auto"
                                                autocomplete="off">
                                            </v-textarea>
                                        </div>
                                        <div style="margin-bottom: 5px !important;" class="form-label d-flex flex-row justify-space-between align-end">
                                            <span>{{ "Type" }}</span>
                                        </div>
                                        <div class="d-flex radio-group-wrapper">
                                            <div v-bind:key="`type-${key}`" v-for="(t,key) in types" :class="`d-flex radio-item align-center ${(t==='LTL') ? 'mr-8' : ''} ${(t === type) ? 'selected' : '' }`">
                                                <v-radio-group v-model="type">
                                                    <v-radio color="primary" :label="t" :value="t"></v-radio>
                                                </v-radio-group>
                                                <kenetic-icon :marginLeft="8" :iconName="t.toLowerCase()"/>
                                            </div>
                                        </div>
                                        <div class="form-label d-flex incoterms-wrapper">
                                            <span class="d-flex align-end">{{ "INCOTERMS" }}</span>
                                            <span id="incoterms-tooltip-wrapper">
                                                <v-tooltip attach="#incoterms-tooltip-wrapper" left content-class="incoterms-tooltip">
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <div
                                                            v-on="on"
                                                            v-bind="attrs"
                                                        >
                                                            <generic-icon iconName="info-icon"></generic-icon>
                                                        </div>
                                                    </template>
                                                     <div>
                                                        {{
                                                            "International Commercial Terms, are a set of international rules produced by the International Chamber of Commerce (ICC)."
                                                        }}
                                                    </div>
                                                </v-tooltip>
                                            </span>
                                        </div>
                                        <div class="radio-group-wrapper radio-group-wrapper-incoterms">
                                            <div style="float: left;" v-bind:key="`incoterm-${key}`" v-for="(m,key) in paymentTerms" :class="`d-flex radio-item align-center ${(m === editItem.inco_term) ? 'selected' : '' }`">
                                                <label style="position: relative;" class="radio-label-wrapper">
                                                    {{ m }}
                                                    <input name="inco_term" :value="m" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.inco_term" />
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div style="float: left;" :class="`d-flex radio-item align-center ${( editItem.inco_term === 'Other') ? 'selected' : '' }`">
                                                <label style="position: relative;" class="d-flex  flex-row radio-label-wrapper">
                                                    {{ 'Other' }}
                                                    <input name="inco_term" :value="m" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.inco_term" />
                                                    <span></span>
                                                    <div class="d-flex justify-center align-center">
                                                        <generic-icon :marginLeft="0" iconName="chevron-down-radio"></generic-icon>
                                                    </div>
                                                </label>
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>
                                        <div v-if="1==2" class="form-label d-flex align-end">
                                            <span>{{ "MODE" }}</span>
                                        </div>
                                        <div v-if="1==2" class="d-flex radio-group-wrapper">
                                            <div v-bind:key="`mode-${key}`" v-for="(m,key) in modes" :class="`d-flex radio-item align-center ${(m === editItem.mode) ? 'selected' : '' }`">
                                                <label style="position: relative;" class="radio-label-wrapper">
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
                                    </div>
                                </div>
                                <div id="purchaseOrderSection" ref="purchaseOrderSection" class="d-flex flex-column purchase-orders-section">
                                    <div style="padding-bottom: 8px;" class="content-title">
                                        {{
                                            "Orders"
                                        }}
                                    </div>
                                    <div style="margin-bottom: 16px;" v-bind:key="`poi-${key}`" v-for="(poi, key) in purchaseOrderItems" class="purchase-order-wrapper">
                                        <div id="select-autocomplete-2" class="select-items-wrapper">
                                            <v-autocomplete
                                                :filter="customFilterPo"
                                                v-model="poi.purchase_order_id"
                                                spellcheck="false"
                                                attach="#select-autocomplete-2"
                                                class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
                                                :items="purchaseOrderOptions"
                                                placeholder="Enter SO"
                                                outlined
                                                item-text="po_number"
                                                item-value="id"
                                                :menu-props="{ contentClass: 'po-lists-items',...menuProps}"
                                                hide-details="auto"
                                                @change="updateProducts(poi, key)"
                                                clearable
                                            >
                                                <template v-slot:item="{ item }">
                                                    <div class="d-flex flex-column" style="width: 100%;">
                                                        <div style="width: 100%; padding-bottom: 12px;" class="d-flex first-row justify-space-between">
                                                            <div>
                                                                <span>PO#</span>
                                                                <span>{{ " " +item.po_number }}</span>
                                                            </div>
                                                            <div style="font-size: 14px !important;">
                                                                {{
                                                                    currencyNumberFormat(item.total)
                                                                }}
                                                            </div>
                                                        </div>
                                                        <div class="d-flex second-row" style="width: 100%; padding-bottom: 4px;">
                                                            {{
                                                                getWarehouseName(item)
                                                            }}
                                                        </div>
                                                        <div class="d-flex third-row">
                                                            {{
                                                                item.ship_to
                                                            }}
                                                        </div>
                                                        <div style="width: 100%; padding-bottom: 8px;" class="d-flex fourth-row flex-row justify-space-between">
                                                            <div>
                                                                <span>Date:</span>
                                                                <span>{{ ' ' + formatDate(item.created_at) }}</span>
                                                            </div>
                                                            <div>
                                                                <span>CRD:</span>
                                                                <span>{{ ' ' + formatDate(item.cargo_ready_date) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex last-row" style="width: 100%; padding-bottom: 24px;">
                                                            <a class="last-row-status">
                                                            {{
                                                                getPoStatus(item.status)
                                                            }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-autocomplete>
                                            <div class="checkbox-wrapper-create checkbox-wrapper-desktop">
                                                <label :class="`${poi.selectAll ? 'checked': ''}`" style="position: relative;">
                                                    <generic-icon :marginLeft="0" :iconName="`${(poi.selectAll) ? 'checked' : 'not-checked'}`"></generic-icon>
                                                    <input  @click.prevent="selectAllProducts(poi, key)" style="position: absolute; opacity: 0;" type="checkbox" :checked="poi.selectAll" class="" :disabled="purchaseOrderItems.length == 0" />
                                                    <span style="color: #4A4A4A; font-weight: 400; font-size: 14px;">Select All Products</span>
                                                </label>
                                            </div>
                                            <div class="delete-btn-wrapper">
                                                <v-btn class="btn-white mr-4" @click="removePurchaseOrderItem(key)">
                                                    <custom-icon marginLeft="3px" iconName="trash-can" color="#ff5252"></custom-icon>
                                                </v-btn>
                                                <button v-if="1==2" @click.stop="removePurchaseOrderItem(key)" class="btn-white">
                                                    <custom-icon iconName="trash-can" color="#ff5252"></custom-icon>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="table-wrapper">
                                            <v-data-table 
                                            :headers="headersProducts" 
                                            :items="filterProducts(poi)"
                                            mobile-breakpoint="769"
                                            :page="1"
                                            :item-class="getItemClass"
                                            :items-per-page="100"
                                            class="purchase-orders-table"
                                            hide-default-footer
                                            style="box-shadow: none !important"
                                            fixed-header
                                            hide-default-header
                                            ref="create-shipment-purchase-order-table">
                                                <template v-slot:header="{ props: { headers } }">
                                                    <thead>
                                                        <tr>
                                                            <th v-for="(item, index) in headers" 
                                                                :key="index"
                                                                class="op-"
                                                                role="column-header"
                                                                :aria-label="item.text"
                                                                :style="`color: #6D858F !important;padding-right: 12px;padding-left: 12px !important;width: ${item.width};text-align: ${item.textAlign}`"
                                                                scope="col">
                                                                {{ item.text }}
                                                            </th>
                                                        </tr>  
                                                    </thead>
                                                </template>
                                                <template v-slot:[`item.product_id`]="{ item }">
                                                    <div>
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
                                                            :menu-props="{ contentClass: 'po-lists-items',...menuProps }"
                                                            hide-details="auto"
                                                            clearable
                                                        >
                                                            <template v-slot:item="{ item }">
                                                                <div class="d-flex flex-row justify-space-between product-item-wrapper">
                                                                    <div style="width: 50%;" class="d-flex flex-column">
                                                                        <div>
                                                                            <span class="product-category-sku">#{{ item.product.category_sku }}</span>
                                                                        </div>
                                                                        <div>
                                                                            <p> {{ item.product.name }} </p>
                                                                            <p class="product-unit-price">
                                                                                ${{ item.product.unit_price !== null ? item.product.unit_price.toFixed(2) : '0.00' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div style="width: 50%;" class="d-flex justify-end">
                                                                        <img class="product-image" :src="getImgUrl(item.product.image)" alt="">
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </v-autocomplete>
                                                        <div v-if="1==2" class="error-message" style="font-size: 12px; color: red; height: 30px; padding-top: 19px;">
                                                        {{ (typeof item.addSpecial=='undefined' && typeof item.special=='undefined' && validateProduct(item, key) ==='error') ? 'This product has already been selected.' : ''}}
                                                        </div>
                                                        <div v-if="item.purchase_order_id!=='' && typeof item.addSpecial!=='undefined'" class="add-product-purchase-order-wrapper add-product-item">
                                                            <a style="cursor: pointer; display: flex; flex-direction: row;" @click="addProductToPurchaseOrders(key)">
                                                                <span style="margin-right: 6.3px;">
                                                                    <generic-icon color="#0171A1" iconName="plus"></generic-icon>
                                                                </span>
                                                                <span>{{ "Add Product" }}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template v-slot:[`item.addAll`]="{ item }">
                                                    <div v-if="typeof item.special=='undefined' && typeof item.addSpecial=='undefined'" class="d-flex product-add-all-wrapper">
                                                        <label :class="`${item.addAll ? 'checked': ''}`" style="position: relative;">
                                                            <generic-icon :iconName="`${(item.addAll) ? 'checked' : 'not-checked'}`"></generic-icon>
                                                            <input @click.prevent="addAllCartons(item, key)" style="position: absolute; opacity: 0;" type="checkbox" v-model="item.addAll" :checked="item.addAll" class="" />
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </template>
                                                <template v-slot:[`item.carton`]="{ item }">
                                                    <v-tooltip left content-class="carton-tooltip">
                                                      <template v-slot:activator="{ on, attrs }">
                                                        <div 
                                                            v-on="on"
                                                            v-bind="attrs"
                                                        >
                                                        <v-text-field
                                                        v-if="typeof item.special=='undefined' && typeof item.addSpecial=='undefined'"
                                                        height="40px"
                                                        color="#002F44"
                                                        width="200px"
                                                        :disabled="item.product_id==''"
                                                        v-model="item.carton"
                                                        type="number"
                                                        dense
                                                        :rules="cartonRules"
                                                        class="carton"
                                                        placeholder=""
                                                        @keyup="updateUnit(item, key)"
                                                        outlined
                                                        hide-details="auto">
                                                        </v-text-field>
                                                        </div>
                                                      </template>
                                                      <span v-if="typeof item.unship_cartons!=='undefined'">
                                                        {{ "Available " + item.unship_cartons + " Cartons" }}
                                                      </span>
                                                      <span v-if="typeof item.unship_cartons=='undefined'">
                                                        {{ "Select first a product." }}
                                                      </span>
                                                    </v-tooltip>
                                                </template>
                                                <template v-slot:[`item.volume`]="{ item }">
                                                   <v-text-field
                                                    v-if="typeof item.special=='undefined' && typeof item.addSpecial=='undefined'"
                                                    height="40px"
                                                    color="#002F44"
                                                    width="200px"
                                                    v-model="item.volume"
                                                    type="number"
                                                    dense
                                                    class="unit"
                                                    placeholder="Volume"
                                                    outlined
                                                    hide-details="auto">
                                                    </v-text-field>
                                                </template>
                                                <template v-slot:[`item.weight`]="{ item }">
                                                   <v-text-field
                                                    v-if="typeof item.special=='undefined' && typeof item.addSpecial=='undefined'"
                                                    height="40px"
                                                    color="#002F44"
                                                    width="200px"
                                                    disabled
                                                    v-model="item.weight"
                                                    type="number"
                                                    dense
                                                    class="unit"
                                                    placeholder="Weight"
                                                    outlined
                                                    hide-details="auto">
                                                    </v-text-field>
                                                </template>
                                                <template v-slot:[`item.unit`]="{ item }">
                                                   <v-text-field
                                                    v-if="typeof item.special=='undefined' && typeof item.addSpecial=='undefined'"
                                                    height="40px"
                                                    color="#002F44"
                                                    width="200px"
                                                    disabled
                                                    v-model="item.unit"
                                                    @keyup="updateCarton(item, key)"
                                                    type="number"
                                                    dense
                                                    class="unit"
                                                    placeholder="Unit"
                                                    outlined
                                                    hide-details="auto">
                                                    </v-text-field>
                                                </template>
                                                <template v-slot:[`item.cargo_ready_date`]="{ item }">
                                                    <div style="text-align: right !important;">
                                                        {{
                                                            item.cargo_ready_date == null || item.cargo_ready_date=='' ? '--' : item.cargo_ready_date
                                                        }}
                                                    </div>
                                                </template>
                                                <template v-slot:[`item.unit_price`]="{ item }">
                                                    <div>
                                                       <div v-if="typeof item.special=='undefined' && typeof item.addSpecial=='undefined'" class="d-flex unit-price">
                                                            {{
                                                                '$' + item.unit_price
                                                            }}
                                                        </div>
                                                        <div class="total-label-product" style="width: 100%; text-align: right;" v-if="typeof item.special!=='undefined' && typeof item.addSpecial=='undefined' && 1==2">
                                                            {{ "Total" }}
                                                        </div>
                                                    </div>
                                                </template>
                                                <template v-slot:[`item.amount`]="{ item }">
                                                    <div>
                                                        <div v-if="typeof item.special=='undefined' && typeof item.addSpecial=='undefined'" class="d-flex amount">
                                                            {{
                                                                '$'+calculateAmount(item)
                                                            }}
                                                        </div>
                                                        <a style="display: block; text-align: right; width: 100% !important; padding-right: 8px !important;" class="total-label-product-value" v-if="typeof item.special!=='undefined' && typeof item.addSpecial=='undefined'">{{ calculateTotal(key) }}</a>
                                                    </div>
                                                </template>
                                                <template v-slot:[`item.action`]="{ item }">
                                                    <div :class="`action-wrapper ${(typeof item.special=='undefined' && typeof item.addSpecial=='undefined') ? 'x-wrapper' : ''}`">
                                                        <a v-if="typeof item.special=='undefined' && typeof item.addSpecial=='undefined'" @click.stop="removeProductFromPurchaseOrders(key,item)" style="cursor: pointer;" class="d-flex">
                                                            <generic-icon :color="item.product_id == '' ? '#B4CFE0' : '#F93131'" iconName="trash-product-light"></generic-icon>
                                                        </a>
                                                    </div>
                                                </template>
                                            </v-data-table>
                                            <div v-if="filterProducts(poi).length > 0" class="d-flex flex-row po-total-wrapper">
                                                <div class="po-total-item">
                                                    Total Cartons: {{ calculateTotalCartons(poi) }}
                                                </div>
                                                <div class="po-total-item">
                                                    Total Volume: 0
                                                </div>
                                                <div class="po-total-item">
                                                    Total Weight: 0
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 16px;" v-bind:key="`pom-${key}`" v-for="(pom, key) in purchaseOrderManualItems" class="purchase-order-wrapper">
                                        <div id="select-autocomplete-4" class="select-items-wrapper">
                                            <v-autocomplete
                                                :filter="customFilterPo"
                                                v-model="pom.purchase_order_id"
                                                spellcheck="false"
                                                attach="#select-autocomplete-4"
                                                class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
                                                :items="purchaseOrderOptions"
                                                placeholder="Enter SO"
                                                outlined
                                                item-text="po_number"
                                                item-value="id"
                                                :menu-props="{ contentClass: 'po-lists-items',...menuProps}"
                                                hide-details="auto"
                                                @change="updateProductsManual(pom, key)"
                                                clearable
                                            >
                                                <template v-slot:item="{ item }">
                                                    <div class="d-flex flex-column" style="width: 100%;">
                                                        <div style="width: 100%; padding-bottom: 12px;" class="d-flex first-row justify-space-between">
                                                            <div>
                                                                <span>PO#</span>
                                                                <span>{{ " " + item.po_number }}</span>
                                                            </div>
                                                            <div style="font-size: 14px !important;">
                                                                {{
                                                                    currencyNumberFormat(item.total)
                                                                }}
                                                            </div>
                                                        </div>
                                                        <div class="d-flex second-row" style="width: 100%; padding-bottom: 4px;">
                                                            {{
                                                                getWarehouseName(item)
                                                            }}
                                                        </div>
                                                        <div class="d-flex third-row">
                                                            {{
                                                                item.ship_to
                                                            }}
                                                        </div>
                                                        <div style="width: 100%; padding-bottom: 8px;" class="d-flex fourth-row flex-row justify-space-between">
                                                            <div>
                                                                <span>Date:</span>
                                                                <span>{{ ' ' + formatDate(item.created_at) }}</span>
                                                            </div>
                                                            <div>
                                                                <span>CRD:</span>
                                                                <span>{{ ' ' + formatDate(item.cargo_ready_date) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex last-row" style="width: 100%; padding-bottom: 24px;">
                                                            <a class="last-row-status">
                                                            {{
                                                                getPoStatus(item.status)
                                                            }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-autocomplete>
                                            <v-autocomplete
                                                v-model="pom.supplier_id"
                                                spellcheck="false"
                                                attach="#select-autocomplete-4"
                                                class="select-purchase-order-po select-purchase-order select-purchase-order-mobile select-purchase-order-po-supplier"
                                                :items="editItem.poManual.suppliersOptions"
                                                placeholder="Select Supplier"
                                                outlined
                                                item-text="name"
                                                item-value="id"
                                                :menu-props="{ contentClass: 'po-lists-items',...menuProps}"
                                                hide-details="auto"
                                                clearable
                                            >
                                                <template v-slot:item="{ item }">
                                                    <div class="d-flex flex-column" style="width: 100%;">
                                                        <div style="width: 100%; padding-bottom: 12px;" class="d-flex first-row justify-space-between">
                                                            <div>
                                                                {{
                                                                    item.name
                                                                }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-autocomplete>
                                            <div class="text-field-wrapper dates-wrapper">
                                                <v-menu
                                                v-model="pom.menuCargoReadyDate"
                                                :close-on-content-click="false"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="auto">
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-text-field
                                                    class="text-fields etd-field date-fields" 
                                                    placeholder="MM-DD-YYYY"
                                                    outlined
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    type="text"
                                                    hide-details="auto"
                                                    clear-icon
                                                    :height="40"
                                                    v-model="pom.cargo_ready_date"
                                                    @input="val => updateCargoReadyDateInput(pom,val)"
                                                    append-icon="mdi-calendar"
                                                    />
                                                </template>
                                                <v-date-picker v-model="pom.cargo_ready_date"></v-date-picker>
                                                </v-menu>
                                            </div>
                                            <div class="delete-btn-wrapper">
                                                <v-btn class="btn-white mr-4" @click="removePurchaseOrderManualItem(key)">
                                                    <custom-icon marginLeft="3px" iconName="trash-can" color="#ff5252"></custom-icon>
                                                </v-btn>
                                            </div>
                                        </div>
                                        <div style="margin-top: 35px; padding-left: 16px; padding-right: 16px;" class="d-flex flex-row">
                                            <div style="width: 33%;margin-right: 16px;">
                                                <input-text
                                                    label="Total Carton"
                                                    :field.sync="pom.total_cartons"
                                                    labelColor="#819FB2"
                                                    placeholderText="Enter Total Carton"
                                                >
                                                    <template v-slot:input="{ mainContent }">
                                                        <v-text-field
                                                            :height="40"
                                                            :color="textColor"
                                                            width="200px"
                                                            type="text"
                                                            dense
                                                            class=""
                                                            @change="mainContent.updateValue"
                                                            :placeholder="mainContent.placeholderText"
                                                            outlined
                                                            hide-details="auto">
                                                        </v-text-field>
                                                    </template>
                                                </input-text>
                                            </div>
                                            <div style="width: 33%;margin-right: 16px;">
                                                <input-text
                                                    label="Total Volume"
                                                    :field.sync="pom.total_volumes"
                                                    labelColor="#819FB2"
                                                    placeholderText="Enter Total Volume"
                                                >
                                                    <template v-slot:input="{ mainContent }">
                                                        <v-text-field
                                                            :height="40"
                                                            :color="textColor"
                                                            width="200px"
                                                            type="text"
                                                            dense
                                                            class=""
                                                            @change="mainContent.updateValue"
                                                            :placeholder="mainContent.placeholderText"
                                                            outlined
                                                            hide-details="auto">
                                                        </v-text-field>
                                                    </template>
                                                </input-text>
                                            </div>
                                            <div style="width: 33%;">
                                                <input-text
                                                    label="Total Weight"
                                                    :field.sync="pom.total_weights"
                                                    labelColor="#819FB2"
                                                    placeholderText="Enter Total Weight"
                                                >
                                                    <template v-slot:input="{ mainContent }">
                                                        <v-text-field
                                                            :height="40"
                                                            :color="textColor"
                                                            width="200px"
                                                            type="text"
                                                            dense
                                                            class=""
                                                            @change="mainContent.updateValue"
                                                            :placeholder="mainContent.placeholderText"
                                                            outlined
                                                            hide-details="auto">
                                                        </v-text-field>
                                                    </template>
                                                </input-text>
                                            </div>
                                        </div>
                                        <div style="padding-left: 16px; padding-right: 16px; margin-top: 16px;" class="flex-row">
                                            <generic-table
                                                :headers="headersDocuments"
                                                :items.sync="pom.documents"
                                                :pomKey="key"
                                                textColor="#4A4A4A"
                                                headerBackground="#F7F7F7"
                                            >
                                            </generic-table>
                                        </div>
                                    </div>                          
                                    <div style="padding-left: 0px !important;" class="d-flex add-product-purchase-order-wrapper add-purchase-order-wrapper flex-row">
                                        <v-btn class="btn-white mr-4 shipment-header-button-btn add-purchase-order-btn-wrapper btn-white-custom" @click="addPurchaseOrderItem()">
                                            <span class="add-purchase-order-span">{{ "+ Add Sales Order" }}</span>
                                        </v-btn>
                                        <v-btn class="btn-white mr-4 shipment-header-button-btn add-purchase-order-btn-wrapper btn-white-custom" @click="addManualPurchaseOrder()">
                                            <span class="add-purchase-order-span add-manual-po-span">{{ "+ Add Manual SO" }}</span>
                                        </v-btn>
                                    </div>
                                </div>
                                <div id="cargoDetailsSection" ref="cargoDetailsSection" class="d-flex flex-column cargo-details-section">
                                    <div class="content-title">
                                        {{
                                            "Cargo Details"
                                        }}
                                    </div>
                                    <div class="d-flex total-cartons">
                                        <div class="d-flex flex-column" style="width: 50%;">
                                            <div class="form-label">
                                                <span class="text-uppercase">{{ "Total Cartons" }}</span>
                                            </div>
                                            <div class="text-field-wrapper input-text-wrapper">
                                                <v-text-field
                                                height="40px"
                                                color="#002F44"
                                                width="200px"
                                                v-model="editItem.total_cartons"
                                                type="number"
                                                dense
                                                class="unit"
                                                placeholder="Total cartons"
                                                outlined
                                                hide-details="auto">
                                                </v-text-field>
                                            </div>
                                            <div id="cargo-details-volume-weight" style="width: 93%;" class="d-flex">
                                                <div class="d-flex flex-column" style="width: 50%; margin-right: 16px;">
                                                    <div class="form-label">
                                                        <span class="text-uppercase">{{ "Total Volume" }}</span>
                                                    </div>
                                                    <div class="text-field-wrapper input-text-wrapper">
                                                        <v-text-field
                                                        height="40px"
                                                        color="#002F44"
                                                        width="200px"
                                                        v-model="editItem.total_volume"
                                                        type="text"
                                                        dense
                                                        class="unit"
                                                        placeholder="Total volume"
                                                        outlined
                                                        hide-details="auto">
                                                        </v-text-field>
                                                    </div>
                                                </div>
                                                <div style="width: 50%;">
                                                    <div class="form-label">
                                                        <span class="text-uppercase">{{ "Total Weight" }}</span>
                                                    </div>
                                                    <div class="text-field-wrapper input-text-wrapper">
                                                        <v-text-field
                                                        height="40px"
                                                        color="#002F44"
                                                        width="200px"
                                                        v-model="editItem.total_weight"
                                                        type="text"
                                                        dense
                                                        class="unit"
                                                        placeholder="Total weight"
                                                        outlined
                                                        hide-details="auto">
                                                        </v-text-field>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column" style="width: 50%;">
                                            <div class="form-label">
                                                <span class="text-uppercase">{{ "Commodity, hs code, material, Usage" }}</span>
                                            </div>
                                            <div id="text-area-wrapper-7" class="text-field-wrapper input-text-wrapper textarea-wrapper commodity-other-info-wrapper">
                                                <v-textarea
                                                class="text-fields"
                                                outlined
                                                v-model="editItem.commodities_info"
                                                placeholder="Select Consignee for notify details"
                                                hide-details="auto"
                                                :height="600"
                                                autocomplete="off">
                                                </v-textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="containerSection" ref="containerSection" class="d-flex flex-column containers-section">
                                    <div class="content-title">
                                        {{
                                            "Containers"
                                        }}
                                    </div>
                                    <div class="table-wrapper container-wrapper">
                                        <v-data-table 
                                            :headers="headersContainers" 
                                            :items="containerItems"
                                            mobile-breakpoint="769"
                                            :page="1"
                                            :items-per-page="100"
                                            class="purchase-orders-table"
                                            hide-default-footer
                                            style="box-shadow: none !important"
                                            fixed-header
                                            hide-default-header
                                            ref="create-shipment-container-table">
                                            <template v-slot:header="{ props: { headers } }">
                                                <thead>
                                                    <tr>
                                                        <th v-for="(item, index) in headers" 
                                                            :key="index"
                                                            role="column-header"
                                                            :aria-label="item.text"
                                                            scope="col"
                                                            :style="`text-align: ${item.textAlign}; color: #6D858F;width: ${item.width};`"
                                                            >
                                                            {{ item.text }}
                                                        </th>
                                                    </tr>  
                                                </thead>
                                            </template>
                                            <template v-slot:[`item.cartons`]="{ item }">
                                                <div style="height: 40px;">
                                                    <v-text-field
                                                    height="40px"
                                                    color="#002F44"
                                                    width="200px"
                                                    v-model="item.cartons"
                                                    type="text"
                                                    dense
                                                    class="container-cartons"
                                                    placeholder="Carton"
                                                    outlined
                                                    hide-details="auto">
                                                    </v-text-field>
                                                </div>
                                            </template>
                                            <template v-slot:[`item.action`]="{ item }">
                                                <a @click.stop="removeContainer(item)" style="cursor: pointer; height: 30px; margin-top: 10px;" class="d-flex">
                                                    <generic-icon color="#F93131" iconName="trash-product-light"></generic-icon>
                                                </a>
                                            </template>
                                            <template v-slot:[`item.size`]="{ item }">
                                                <v-select
                                                class="text-fields select-items select-items-general container-size"
                                                    :items="filteredContainerSizes"
                                                    height='40px'
                                                    outlined
                                                    v-model="item.size"
                                                    hide-details="auto"
                                                    :menu-props="{ bottom: true, offsetY: true }">
                                                </v-select>
                                            </template>
                                            <template v-slot:[`item.cbm`]="{ item }">
                                                <div style="width: 100%;">
                                                    <v-text-field
                                                    background-color="#ffffff"
                                                    height="40px"
                                                    color="#002F44"
                                                    width="200px"
                                                    v-model="item.cbm"
                                                    type="text"
                                                    dense
                                                    class="container-volume container-cartons"
                                                    placeholder="Volume"
                                                    outlined
                                                    hide-details="auto">
                                                    </v-text-field>
                                                </div>
                                            </template>
                                            <template v-slot:[`item.kg`]="{ item }">
                                                <v-text-field
                                                background-color="#ffffff"
                                                height="40px"
                                                color="#002F44"
                                                width="200px"
                                                v-model="item.kg"
                                                type="text"
                                                dense
                                                class="container-weight container-cartons"
                                                placeholder="Weight"
                                                outlined
                                                hide-details="auto">
                                                </v-text-field>
                                            </template>
                                            <template v-slot:[`item.container_num`]="{ item }">
                                                <v-text-field
                                                background-color="#ffffff"
                                                height="40px"
                                                color="#002F44"
                                                width="200px"
                                                v-model="item.container_num"
                                                type="text"
                                                dense
                                                class="container-num"
                                                placeholder="Type container number"
                                                outlined
                                                hide-details="auto">
                                                </v-text-field>
                                            </template>
                                        </v-data-table>
                                        <div style="padding-left: 0px !important;" class="add-container-wrapper">
                                            <v-btn class="btn-white mr-4 shipment-header-button-btn" @click="addContainer">
                                                <span class="add-container-span">{{ "+ Add Container" }}</span>
                                            </v-btn>
                                        </div>
                                    </div>
                                </div>
                            </v-form>
                        </div>
                        <slot v-bind:mainContent="{loaded: loaded, item: editItem, sidebarItems: sidebarItems, rules: rules }" name="content"></slot>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <slot v-bind:footer="{ close: close, createShipment: addShipment, createLoading: getCreateShipmentLoading, updateShipment: updateShipment }" name="actions"></slot>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<style lang="scss">
@import "./scss/createShipmentDialog.scss";
@import  "./scss/fields.scss";
</style>
<script>
import _ from 'lodash'
import moment from 'moment'
import KeneticIcon from '../../Icons/KeneticIcon'
import CustomIcon from '../../Icons/CustomIcon'
import GenericIcon from '../../Icons/GenericIcon'
import globalMethods from "../../../utils/globalMethods";
import AddManualPoModal from "./Modals/AddManualPoModal";
import InputText from "./InputTexts/InputText";
import GenericTable from "./Tables/GenericTable";

import { mapGetters, mapActions }  from 'vuex'
import iziToast from 'izitoast'
export default {
    name: 'BookingShipmentDialog',
    props: ['reference','show', 'className', 'rules', 'isMobile', 'windowWidth', 'addShipmentDialogModalView', 'item'],
    data: (vv) => ({
        addManualPoModalView: false,
        port_of_discharge_error: false,
        port_of_discharge_dirty: false,
        bookingFors: ['myself', 'others party'],
        consignees: [{
            id: 1,
            name: 'Duvav Trading LLC',
            description: 'Test description',
            address: '153 Boston Post Road #1051, East Lyme, CT 06333, Connecticut 0633, U...'
        }],
        shippers: [
        {
            id: 1,
            name: 'Gilberties Fashion',
            description: 'Gilberties Fashion'
        }
        ],
        cartonRules: [
            (v) => !!v || 'Carton is required.',
            (v) => (parseFloat(v)>0) || 'Carton should be greater than 0',
        ],
        etaRules: [
            (v) => !!v || 'ETA is required.',
            (v) => moment(v).isValid() || 'ETA is invalid. Format should be MM-DD-YYYY.'
        ],
        portDischargeRules: [
            (v) => {
                console.log('v', v)
                if ( !v ) {
                    if ( typeof vv!=='undefined' )
                        vv.port_of_discharge_error = true
                    return 'Port of Discharge is required.'

                } else {
                    if ( typeof vv!=='undefined' )
                        vv.port_of_discharge_error = false
                    return true
                }
            }
        ],
        cargoReadyDateRules: [
            (v) => !!v || 'Cargo Ready Date is required.',
            (v) => moment(v).isValid() || 'Cargo Ready Date is invalid. Format should be MM-DD-YYYY.'
        ],
        etdRules: [
            (v) => !!v || 'ETD is required.',
            (v) => moment(v).isValid() || 'ETD is invalid. Format should be MM-DD-YYYY.'
        ],
        roles: ['shipper', 'consignee'],
        containerSizes: [],
        containerVolumes: [],
        containerWeights: [],
        selectAll: false,
        menuProps: {
            bottom: true,
            offsetY: true
        },
        discharges: [],
        fetchSinglePurchasOrderLoading: false,
        products: [{
            text: 'Test',
            value: 'Test'
        }],
        purchaseOrderItems: [],
        purchaseOrderManualItems: [],
        purchaseOrderProductItems: [],
        containerItems: [],
        headersDocuments: [
        {
            text: "",
            align: "start",
            sortable: false,
            value: "document_beginning",
            fixed: true,
            textAlign: 'left',
            width: '3.42%'
        },
        {
            text: "Document Name",
            align: "start",
            sortable: false,
            value: "name",
            fixed: true,
            textAlign: 'left',
            width: '57.57%'
        },
        {
            text: "Document Type",
            align: "start",
            sortable: false,
            value: "type",
            fixed: true,
            textAlign: 'left',
            width: '37.88%'
        },
        {
            text: "",
            align: "start",
            sortable: false,
            value: "actions",
            fixed: true,
            textAlign: 'left',
            width: '4.54%'
        },
        ],
        headersContainers: [{
            text: "Container #",
            align: "start",
            sortable: false,
            value: "container_num",
            fixed: true,
            textAlign: 'left',
            width: '28.73%'
        },
        {
            text: "Size",
            align: "start",
            sortable: false,
            value: "size",
            fixed: true,
            textAlign: 'left',
            width: '11.19%'
        },
        {
            text: "Volume",
            align: "start",
            sortable: false,
            textAlign: 'right',
            value: "cbm",
            fixed: true,
            width: '18.65%'
        },
        {
            text: "Weight",
            align: "start",
            sortable: false,
            value: "kg",
            fixed: true,
            textAlign: 'right',
            width: '18.65%'
        },
        {
            text: "Carton",
            align: "start",
            sortable: false,
            value: "cartons",
            textAlign: 'right',
            fixed: true,
            width: '18.65%'
        },
        {
            text: "",
            align: "start",
            textAlign: 'left',
            sortable: false,
            value: "action",
            fixed: true,
            width: '4.10%'
        }
        ],
        headersProducts: [{
            text: "Product",
            align: "start",
            sortable: false,
            value: "product_id",
            fixed: true,
            width: '28.77%',
            textAlign: "left"
        },
        {
            text: "Add All",
            align: "start",
            sortable: false,
            value: "addAll",
            fixed: true,
            width: '7.54%',
            textAlign: "center"
        },
        {
            text: "Carton",
            align: "start",
            sortable: false,
            value: "carton",
            width: '11.32%',
            fixed: true,
            textAlign: "right"
        },
        {
            text: "Unit",
            align: "start",
            sortable: false,
            value: "unit",
            fixed: true,
            width: '11.32%',
            textAlign: "right"
        },
        {
            text: "Volume",
            align: "start",
            sortable: false,
            value: "volume",
            fixed: true,
            width: '11.32%',
            textAlign: "right"
        },
        {
            text: "Weight",
            align: "start",
            sortable: false,
            value: "weight",
            fixed: true,
            width: '11.32%',
            textAlign: "right"
        },
        /*
        {
            text: "Unit Price",
            align: "start",
            sortable: false,
            value: "unit_price",
            fixed: true,
            width: '13.85%',
            textAlign: "right"
        },*/
        {
            text: "Cargo Ready",
            align: "start",
            sortable: false,
            value: "cargo_ready_date",
            fixed: true,
            width: '14.15%',
            textAlign: "right"
        },
        /*
        {
            text: "Amount",
            align: "start",
            sortable: false,
            width: '13.07%',
            value: "amount",
            fixed: true,
            textAlign: "right"
        },*/
        {
            text: "",
            width: '4.24%',
            align: "start",
            sortable: false,
            value: "action",
            textAlign: "right",
            fixed: true
        }
        ],
        headersPurchaseOrders: [{
            text: "Product",
            align: "start",
            sortable: false,
            value: "product",
            fixed: true
        },
        {
            text: "Add All",
            align: "start",
            sortable: false,
            value: "addAll",
            fixed: true
        },
        {
            text: "Carton",
            align: "start",
            sortable: false,
            value: "carton",
            fixed: true
        },
        {
            text: "Unit",
            align: "start",
            sortable: false,
            value: "unit",
            fixed: true
        },
        {
            text: "Unit Price",
            align: "start",
            sortable: false,
            value: "unit_price",
            fixed: true
        },
        {
            text: "Amount",
            align: "start",
            sortable: false,
            value: "amount",
            fixed: true
        },
        {
            text: "",
            align: "start",
            sortable: false,
            value: "action",
            fixed: true
        }
        ],
        dynamicHeight: 0,
        purchaseOrderId: 0,
        productId: 0,
        containerId: 0,
        editItem: {
            is_notify_party: false,
            inco_term: 0,
            contact: '',
            total_cartons: 0,
            total_volume: 0,
            total_weight: 0,
            commodity_other_infos: '',
            contact_number: '',
            port_of_departure: 0,
            poManual: {
                po_number: '',
                cargoReadyDate: '',
                cargoReady: '',
                volume: 0,
                cartons: 0,
                weights: 0,
                suppliersOptions: [],
                supplier_id: 0
            },
            port_of_destination: 0,
            port_of_discharge: 0,
            purchaseOrders: [],
            consignee_id: 0,
            shipper: 0,
            shippers_details_info: '',
            commodities_details_info: '',
            consignee_details_info: '',
            booking_for: 0,
            mbl_num: '',
            vessel: '',
            role: 0,
            marks: '',
            consignees_details_info: '',
            voyage_number: '',
            details_info: '',
            commodities_info: '',
            eta: '',
            etd: '',
            etaDate: '',
            etdDate: '',
            mode: '',
            type: '',
            notify_details_info: '',
            carrier_id: '',
            location_from: '',
            location_to: '',
            payment_terms: '',
            discharge: '',
            booking_num: '',
            terminal: ''
        },
        attrs: {
            class: 'mb-6',
            boilerplate: true,
            elevation: 2,
        },
        resourcesLoaded: 0,
        resourcesLimit: 6,
        valid: true,
        modeGroup: 1,
        loaded: false,
        mode: '',
        from: '',
        to: '',
        carriers: [],
        menuEtd: false,
        menuEta: false,
        etaField: null,
        etaDate: null,
        etdField: null,
        etaSample: null,
        etdSample: null,
        modes: ['Ocean', 'Truck', 'Air'],
        paymentTerms: ['FOB', 'EXW', 'DAP', 'FCA'],
        paymentTermsOthers: ['CNF', 'CIF','DAT'],
        type: '',
        anotherTypes: ['Rail'],
        types: ['FCL', 'LTL'],
        sidebarItems: [{
            icon: 'general',
            label: 'General Information',
            width: 20,
            selected: true,
            id: 'generalInformationSection',
            reference: 'generalInformationSection',
            height: 20
        },
        {
            icon: 'po-icon',
            label: 'Orders',
            width: 20,
            selected: false,
            id: 'purchaseOrderSection',
            reference: 'purchaseOrderSection',
            height: 20
        },
        {
            icon: 'container-icon',
            label: 'Container & Others',
            width: 20,
            selected: false,
            reference: 'containerSection',
            height: 20
        }
        ]
    }),
    components: {
        KeneticIcon,
        CustomIcon,
        GenericIcon,
        AddManualPoModal,
        GenericTable,
        InputText
    },
    mounted() {
        if ( this.reference !== 'formBookingShipment' ) {
            this.editItem = this.item
            this.editItem.eta = moment().format('MM-DD-YYYY')
            this.editItem.etd = moment().format('MM-DD-YYYY')

            this.editItem.booking_num = this.editItem.booking_number

            //check schedules
            let schedules = this.editItem.schedules_group_bookings
            schedules = Array.isArray(schedules) ? schedules : JSON.parse(schedules)
            let findScheduleIndex = -1
            this.editItem.location_from = ''
            this.editItem.location_to = ''
            this.editItem.terminal = this.editItem.terminal_id

            //check containers
            let containers = this.editItem.containers_group
            containers = Array.isArray(containers) ? containers : JSON.parse(containers)
            this.containerItems = containers === null ? [] : containers

            if ( schedules.length > 0 ) {

                this.editItem.eta = moment(schedules[0].eta).format('MM-DD-YYYY')
                this.editItem.etd = moment(schedules[0].etd).format('MM-DD-YYYY')
                this.editItem.etaDate = moment(schedules[0].eta).format('YYYY-MM-DD')
                this.editItem.etdDate = moment(schedules[0].etd).format('YYYY-MM-DD')

                this.editItem.location_from = schedules[0].location_from
                this.editItem.location_to = schedules[0].location_to

                findScheduleIndex = 0;
                //findScheduleIndex = _.findIndex(schedules, { is_confirmed: 1})
                if ( findScheduleIndex!==-1) {
                    this.editItem.mode = (schedules[findScheduleIndex].mode!==null && schedules[findScheduleIndex].mode!=='' ) ? this.ucFirst(schedules[findScheduleIndex].mode.toLowerCase()) : ''
                    this.editItem.type = schedules[findScheduleIndex].type

                    if ( typeof schedules[findScheduleIndex].carrier_name!=='undefined' && schedules[findScheduleIndex].carrier_name!==null && schedules[findScheduleIndex].carrier_name!=='' && typeof schedules[findScheduleIndex].carrier_name.id!=='undefined') {
                        this.editItem.carrier_id = parseInt(schedules[findScheduleIndex].carrier_name.id)
                    }
                }
            }
            this.editItem.mbl_num = (typeof this.editItem.mbl_num!=='undefined' && this.editItem.mbl_num!==null && this.editItem.mbl_num!=='') ? this.editItem.mbl_num : ''
            this.editItem.vessel = (typeof this.editItem.vessel!=='undefined' && this.editItem.vessel!==null && this.editItem.vessel!=='') ? this.editItem.vessel : ''

            this.editItem.voyage_number = (typeof this.editItem.voyage_number!=='undefined' && this.editItem.voyage_number!==null && this.editItem.voyage_number!=='') ? this.editItem.voyage_number : ''
        } else {
            this.editItem.mbl_num = ''
            this.editItem.vessel = ''
            this.editItem.voyage_number = ''
            this.editItem.mode = ''
            this.editItem.type = ''
            this.editItem.carrier_id = ''
            this.editItem.location_from = ''
            this.editItem.location_to = ''
            this.editItem.carrier_id = ''
            this.editItem.booking_num = ''
        }
        
        //load terminals
        this.fetchTerminals({is_paginate: 0}).then(() => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
        }).catch(e => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
            console.log(e)
        })


        //load carriers
        this.fetchCarriers().then(() => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
        }).catch(e => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
            console.log(e)
        })

        //load terminal regions
        this.fetchTerminalRegions({is_paginate: 0}).then(() => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
        }).catch(e => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
            console.log(e)
        })

        //load container sizes
        this.fetchContainers().then(() => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
        }).catch(e => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
            console.log(e)
        })

        //fetch purchase orders options
        this.fetchPurchaseOrderOptions().then(() => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
        }).catch(e => {
            console.log(e)
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
            }
        })


        //load suppliers
        this.fetchSuppliers().then(() => {
            this.resourcesLoaded++
            if ( this.resourcesLoaded === this.resourcesLimit ){
                this.loaded = true
                if ( typeof this.getSuppliers!=='undefined' ) {
                    this.editItem.poManual.suppliersOptions = this.getSuppliers.data
                    console.log(this.editItem.poManual.suppliersOptions)
                }
            }
        })
    },
    computed: {
        minDate() {
            return moment().format('MM-DD-YYYY')
        },
        filteredPaymentTerms() {
            let newTerms = []
            this.paymentTerms.map((pt, key) =>{
                if ( key == (this.paymentTerms.length - 1 )) {
                    console.log('t')
                } else {
                    newTerms.push(pt)
                }
            })
            return newTerms
        },
        ...mapGetters([
            'getCarriers',
            'getTerminalRegions',
            'getTerminals',
            'getCreateShipmentLoading',
            'getShipmentContainerSizes',
            'getUser',
        ]),
        getSuppliers() {
            return this.$store.getters['suppliers/getSuppliers']
        },
        purchaseOrderOptions() {
            let options = this.$store.getters['po/getPurchaseOrderOptions']
            options = _.filter(options, e => (e.status ==='pending' || e.status === 'partially_shipped'))
            return options
        },
        filteredContainerSizes() {
            let newContainerSizes = []
            if ( this.loaded ) {
                this.getShipmentContainerSizes.map( gcs => {
                    newContainerSizes.push({
                        text: gcs.name,
                        value: gcs.id
                    })
                })
            }
            return newContainerSizes
        },
        filteredTerminalRegions() {
            let newTerminalRegions = []
            if ( this.loaded ) {
                this.getTerminalRegions.map( gt => {
                    newTerminalRegions.push({
                        text: gt.name,
                        value: gt.id
                    })
                })
            }
            return newTerminalRegions
        },
        filteredTerminals() {
            let newTerminals = []
            if ( this.loaded ) {
                this.getTerminals.map( gt => {
                    newTerminals.push({
                        text: gt.name,
                        value: gt.id
                    })
                })
            }
            return newTerminals
        },
        filteredCarriers() {
            let newCarriers = []
            if ( this.loaded ) {
                this.getCarriers.map( gc => {
                    newCarriers.push({
                        text: gc.name,
                        value: gc.id
                    })
                })
            }
            return newCarriers
        },
        selectedPage() {
            return _.find(this.sidebarItems,{ selected: true}).icon
        },
    },
    methods: {
        ...mapActions(['fetchCarriers', 'fetchTerminalRegions', 'createShipment', 'fetchContainers', 'fetchShipmentDetails', 'editShipment', 'fetchTerminals']),
        ...globalMethods,
        updateCargoReadyDateInput(pom, value) {
            
            if ( moment(value).isValid() )
                pom.cargo_ready_date = moment(value).format('YYYY-MM-DD')
            else
                pom.cargo_ready_date = ''
            pom.menuCargoReadyDate = false
            console.log('udpate', value)
        },
        fetchSuppliers() {
            return new Promise((resolve) => {
                this.$store.dispatch('suppliers/fetchSuppliers', {
                    pageNumber: 1
                }).then(() => {
                    resolve('ok')
                })
            })
        },
        calculateTotalCartons(po) {
            
            //extract products from purchase order item
            let {
                products
            } = po

            //initialize total
            let total = 0

            //assign products origin value to newProducts
            let newProducts = products
            let validProducts = _.filter(products, e => (typeof e.addSpecial=='undefined' && typeof e.special=='undefined'))
            
            if ( po.selectAll || (validProducts.length > 0 && po.product_options.length > 0 && validProducts.length == po.product_options.length)) {
                newProducts = _.filter(products, e => (typeof e.addSpecial=='undefined'))
                po.selectAll = true
            }
            //if there are products then sum all cartons
            if ( newProducts.length > 0 ) {
                newProducts.map(np => {
                    total += parseInt(np.carton)
                })
            }
            return total

        },
        notifyParty(item) {
            item.is_notify_party = !item.is_notify_party
        },
        setDirty(key) {
            this[key] = true
            this.port_of_discharge_dirty = true
            console.log('blur')
        },
        getItemClass(item) {
            if ( typeof item.addSpecial=='undefined' && typeof item.special === 'undefined') {
                if ( item.addAll ) {
                    return 'item-row item-row-add'
                } else {
                    return 'item-row'
                }
            } else {
                return 'item-row-special'
            }
        },
        updateEtdInput(value) {
            if ( moment(value).isValid() )
                this.editItem.etdDate = moment(value).format('YYYY-MM-DD')
            else
                this.editItem.etdDate = ''
            this.menuEtd = false
        },
        updateEtaInput(value) {
            if ( moment(value).isValid() )
                this.editItem.etaDate = moment(value).format('YYYY-MM-DD')
            else
                this.editItem.etdDate = ''
            this.menuEta = false
        },
        updateEtd(value) {
            this.editItem.etd = moment(value).format('MM-DD-YYYY')
            this.menuEtd = false
        },
        updateEta(value) {
            this.editItem.eta = moment(value).format('MM-DD-YYYY')
            this.menuEta = false
        },
        filterProducts(po) {
            let {
                products
            } = po

            let newProducts = products
            let validProducts = _.filter(products, e => (typeof e.addSpecial=='undefined' && typeof e.special=='undefined'))
            if ( po.selectAll || (validProducts.length > 0 && po.product_options.length > 0 && validProducts.length == po.product_options.length)) {
                newProducts = _.filter(products, e => (typeof e.addSpecial=='undefined'))
                po.selectAll = true
            }
            return newProducts
        },
        updateShipment() {
            if ( this.$refs['formEditShipment'].validate() ) {
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
            
                this.editShipment(payload).then(() => {
                    this.notificationSuccess('Shipment was edited successfully.')   
                    this.fetchShipmentDetails()                 
                    setTimeout(() => {
                        window.location.reload()
                        this.close()
                    },4000)

                }).catch(e => {
                    console.log(e)
                })
            }
        },
        addAllCartons(item, key) {
            //set product key of set carton
            let productKey = this.purchaseOrderItems[key].products.indexOf(item)
            
            if ( !item.addAll ) {
                this.purchaseOrderItems[key].products[productKey].carton = item.unship_cartons
            } else {
                this.purchaseOrderItems[key].products[productKey].carton = 0
            }
            //update unit according to carton logic
            this.updateUnit(item, key)
            
            //toggle add all option of purchase order
            this.purchaseOrderItems[key].products[productKey].addAll = !item.addAll
        },
        selectAlllProductsManual(item, key) {
            //if there is selected purchase order then add all the products under that po
            if ( item.purchase_order_id!=='') {
                this.purchaseOrderManualItems[key].products = []
                
                //it means that user select all the purchase order
                if ( !item.selectAll ) {

                    this.purchaseOrderManualItems[key].product_options.map(poi => {
                        this.purchaseOrderManualItems[key].products.push({
                            product_id: poi.product_id,
                            addAll: false,
                            cartonShow: true,
                            carton: 0,
                            unit: 0,
                            weight: poi.weight == null ? 0 : poi.weight,
                            volume: poi.volume == null ? 0 : poi.volume,
                            unit_price: poi.unit_price,
                            units_per_carton: poi.units_per_carton,
                            amount: 0,
                            unship_cartons: poi.unship_cartons,
                            cargo_ready_date: item.cargo_ready_date,
                            action: ''
                        })
                    })
                    
                    if ( !this.isMobile ) {

                        //take out products with add special
                        let getProducts = this.purchaseOrderManualItems[key].products
                        getProducts = _.filter(getProducts, e => (typeof e.addSpecial=='undefined'))
                        getProducts.push({
                            addSpecial: true,
                            product_id: '',
                            amount: 0,
                            carton: 0,
                            weight: 0,
                            unit: 0,
                            units_per_carton: 0,
                            unit_price: 0
                        })

                        //take out products with special
                        getProducts = _.filter(getProducts, e => (typeof e.special=='undefined'))
                        /*
                        getProducts.push({
                            special: true,
                            action: '',
                            amount: 0,
                            carton: 0,
                            unit: 0,
                            units_per_carton: 0,
                            unit_price: 0
                        })*/
                        //set purchase order's products
                        this.purchaseOrderManualItems[key].products = getProducts    
                    }
                        
                } else {

                    //reset to default
                    //clear products
                    this.purchaseOrderManualItems[key].products = []

                    //if not mobile then do the logic
                    //add the add products button and total section
                    if ( !this.isMobile ) {
                        this.purchaseOrderManualItems[key].products.push({
                            addSpecial: true,
                            product_id: '',
                            amount: 0,
                            carton: 0,
                            unit: 0,
                            weight: 0,
                            units_per_carton: 0,
                            unit_price: 0
                        },
                        /*
                        {
                            special: true,
                            action: '',
                            amount: 0,
                            carton: 0,
                            unit: 0,
                            units_per_carton: 0,
                            unit_price: 0
                        }*/)    
                    }
                }

                //set product label numbering
                this.purchaseOrderManualItems[key].products.map((p,k) => {
                    let setIndex = parseInt(k) + 1
                    this.purchaseOrderItems[key].products[k].index = (setIndex >=10 ) ? setIndex: `0${setIndex}`
                })

                //toggle select all
                this.purchaseOrderManualItems[key].selectAll = !item.selectAll
                
            }
        },
        selectAllProducts(item, key) {

            //if there is selected purchase order then add all the products under that po
            if ( item.purchase_order_id!=='') {
                this.purchaseOrderItems[key].products = []

                //it means that user select all the purchase order
                if ( !item.selectAll ) {

                    this.purchaseOrderItems[key].product_options.map(poi => {
                        this.purchaseOrderItems[key].products.push({
                            product_id: poi.product_id,
                            addAll: false,
                            cartonShow: true,
                            carton: 0,
                            unit: 0,
                            weight: poi.weight == null ? 0 : poi.weight,
                            volume: poi.volume == null ? 0 : poi.volume,
                            unit_price: poi.unit_price,
                            units_per_carton: poi.units_per_carton,
                            amount: 0,
                            unship_cartons: poi.unship_cartons,
                            cargo_ready_date: item.cargo_ready_date,
                            action: ''
                        })
                    })
                    
                    if ( !this.isMobile ) {

                        //take out products with add special
                        let getProducts = this.purchaseOrderItems[key].products
                        getProducts = _.filter(getProducts, e => (typeof e.addSpecial=='undefined'))
                        getProducts.push({
                            addSpecial: true,
                            product_id: '',
                            amount: 0,
                            carton: 0,
                            weight: 0,
                            unit: 0,
                            units_per_carton: 0,
                            unit_price: 0
                        })

                        //take out products with special
                        getProducts = _.filter(getProducts, e => (typeof e.special=='undefined'))
                        /*
                        getProducts.push({
                            special: true,
                            action: '',
                            amount: 0,
                            carton: 0,
                            unit: 0,
                            units_per_carton: 0,
                            unit_price: 0
                        })*/
                        //set purchase order's products
                        this.purchaseOrderItems[key].products = getProducts    
                    }
                        
                } else {

                    //reset to default
                    //clear products
                    this.purchaseOrderItems[key].products = []

                    //if not mobile then do the logic
                    //add the add products button and total section
                    if ( !this.isMobile ) {
                        this.purchaseOrderItems[key].products.push({
                            addSpecial: true,
                            product_id: '',
                            amount: 0,
                            carton: 0,
                            unit: 0,
                            weight: 0,
                            units_per_carton: 0,
                            unit_price: 0
                        },
                        /*
                        {
                            special: true,
                            action: '',
                            amount: 0,
                            carton: 0,
                            unit: 0,
                            units_per_carton: 0,
                            unit_price: 0
                        }*/)    
                    }
                }

                //set product label numbering
                this.purchaseOrderItems[key].products.map((p,k) => {
                    let setIndex = parseInt(k) + 1
                    this.purchaseOrderItems[key].products[k].index = (setIndex >=10 ) ? setIndex: `0${setIndex}`
                })

                //toggle select all
                this.purchaseOrderItems[key].selectAll = !item.selectAll
                
            }
        },
        validateProduct(item, key) {

            //run validation against the selected product using the product_id
            //if the product is already used then return an error class
            let returnClass = ''
            if ( item.product_id !== null && item.product_id !=='' ) {
                let productKey = this.purchaseOrderItems[key].products.indexOf(item)
                let findSelectedOption = _.findIndex(this.purchaseOrderItems[key].products, e => (e.product_id == item.product_id))
                if ( findSelectedOption !== productKey ) {
                    returnClass = 'error'
                }
            }
            return returnClass
        },
        updateUnit(item, key) {

            //update unit
            //formula: item's carton * item's unit per carton
            let productKey = this.purchaseOrderItems[key].products.indexOf(item)
            this.purchaseOrderItems[key].products[productKey].unit = item.carton * item.units_per_carton
        },
        updateCarton(item, key) {
            //get product key against the products list of the selected purchase order
            let productKey = this.purchaseOrderItems[key].products.indexOf(item)

            //divide the carton
            let divide_carton = parseInt( item.unit / item.units_per_carton)

            //see if there is remainder
            //if there is then add 1 carton
            if ( item.unit % item.units_per_carton !==0) {
                divide_carton++
            }
            //set now the carton
            this.purchaseOrderItems[key].products[productKey].carton = divide_carton
        },
        calculateAmount(item) {
            //return this.currencyNumberFormat(item.unit_price * item.carton)
            //use parse float to format to decimal digit
            let total = parseFloat(item.unit_price) * parseFloat(item.unit)
            //assign now the total to item's amount
            item.amount = total
            //fixed to 2 decimal point
            return `${isNaN(total) ? '0.00' : total.toFixed(2)}`
        },
        calculateTotal(key) {
            
            //calculate total
            let total = 0

            //by iterating the purchase order products we multiply each of them using the formula
            // purchase order's unit price * purchase order's number of units
            this.purchaseOrderItems[key].products.map(p => {
                total += parseFloat(p.unit_price) * parseFloat(p.unit)
            })
            //prepend $ for display usage
            return `${isNaN(total) ? '$0.00' : '$'+total.toFixed(2)}`
        },
        customFilterPo(item, queryText, itemText) {
            //funtion for searching po
            //append the 3 data and then store them in data variable
            //convert them all to lowercase
            const data = item.po_number + item.ship_to.toLowerCase() + itemText.toLocaleLowerCase()
            
            //let's also convert search text to lowercase to match case with the data to search with
            const searchText = queryText.toLowerCase()

            //do the searching and return the index of the result
            //if no result then return with -1
            return data.indexOf(searchText) > -1 
        },
        customFilterProduct(item, queryText, itemText) {
            const data = item.product.category_sku + item.product.category_id + item.product.name.toLowerCase() + item.product.sku + itemText.toLocaleLowerCase()
            const searchText = queryText.toLowerCase()
            return data.indexOf(searchText) > -1 
        },
        getImgUrl(pic) {

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
                return require('../../../assets/icons/default-product-icon.svg')
        },
        fetchPurchaseOrderOptions(qry) {
            //fetch purchase order options
            // we did not pass query here as we will fetch purchase order options
            // based on the current login customer
            return new Promise((resolve) => {
                this.$store.dispatch('po/fetchPurchaseOrderOptions',qry)
                resolve()
            })
        },
        updateProductPurchaseOrder(options, item, key) {

            //if we select a product from product options
            //then let's set it's meta values to other corresponding field in the row of product table
            //getting the index of the selected purchase order product
            let findIndex = this.purchaseOrderItems[key].products.indexOf(item)

            //check against the available product options and then match it against the item's product id
            let findProduct = _.find(options, e => (e.product_id == item.product_id))

            //if we found it against the purchase order products options then let set the other relevant data now to other row item of that product 
            // e.g carton, unit, units per carton,
            if ( typeof findProduct!=='undefined' ) {
                this.purchaseOrderItems[key].products[findIndex].carton = 0
                this.purchaseOrderItems[key].products[findIndex].unit = 0
                this.purchaseOrderItems[key].products[findIndex].units_per_carton = findProduct
                .units_per_carton
                this.purchaseOrderItems[key].products[findIndex].weight = findProduct.weight == null ? 0 : findProduct.weight
                this.purchaseOrderItems[key].products[findIndex].volume = findProduct.volume == null ? 0 : findProduct.volume
                this.purchaseOrderItems[key].products[findIndex].unship_cartons = findProduct.unship_cartons
                //this.purchaseOrderItems[key].products[findIndex].unit = findProduct.units
                this.purchaseOrderItems[key].products[findIndex].unit_price = findProduct.unit_price
                this.purchaseOrderItems[key].products[findIndex].meta = findProduct
                this.purchaseOrderItems[key].products[findIndex].cargo_ready_date = this.purchaseOrderItems[key].cargo_ready_date
            }  
        },
        updateProductsManual(item, key) {
            this.purchaseOrderManualItems[key].selectAll = false
            //if purchase order is unselected for some reason then clear all product options, products
            let otherOptions = _.find(this.purchaseOrderOptions, e => (e.id == item.purchase_order_id))

            if ( typeof otherOptions !== 'undefined' ) {

                //fetch purchase order products when a po is selected then update the product options of the selected po
                this.$store.dispatch('po/fetchPurchaseOrderProducts', item.purchase_order_id).then( res => {
                    this.purchaseOrderManualItems[key].product_options = res.data
                    //this.purchaseOrderManualItems[key].supplier_id = supplier_id
                    this.purchaseOrderManualItems[key].cargo_ready_date = otherOptions.cargo_ready_date
                    this.productId++
                    if ( !this.isMobile ) {
                        this.purchaseOrderManualItems[key].products = [
                        {
                            addSpecial: true,
                            product_id: '',
                            amount: 0,
                            unit_price: 0,
                            weight: 0,
                            volume: 0,
                            carton: 0,
                            cargo_ready_date: null
                        },
                        /*
                        {
                            special: true,
                            action: '',
                            amount: 0,
                            unit_price: 0,
                            carton: 0
                        }*/]    
                    }
                    

                }).catch(e => {
                    console.log(e)
                })    
            } else {
                this.purchaseOrderManualItems[key].product_options = []
                this.purchaseOrderManualItems[key].products = []
                
            }

            //always clear the checkbox when channging po
            this.purchaseOrderManualItems[key].selectAll = false
        },
        updateProducts(item, key) {

            this.purchaseOrderItems[key].selectAll = false
            //if purchase order is unselected for some reason then clear all product options, products
            let otherOptions = _.find(this.purchaseOrderOptions, e => (e.id == item.purchase_order_id))

            if ( typeof otherOptions !== 'undefined' ) {

                let supplier_id = otherOptions.supplier_id

                //fetch purchase order products when a po is selected then update the product options of the selected po
                this.$store.dispatch('po/fetchPurchaseOrderProducts', item.purchase_order_id).then( res => {
                    this.purchaseOrderItems[key].product_options = res.data
                    this.purchaseOrderItems[key].supplier_id = supplier_id
                    this.purchaseOrderItems[key].cargo_ready_date = otherOptions.cargo_ready_date
                    this.productId++
                    if ( !this.isMobile ) {
                        this.purchaseOrderItems[key].products = [
                        {
                            addSpecial: true,
                            product_id: '',
                            amount: 0,
                            unit_price: 0,
                            weight: 0,
                            volume: 0,
                            carton: 0,
                            cargo_ready_date: ''
                        },
                        /*
                        {
                            special: true,
                            action: '',
                            amount: 0,
                            unit_price: 0,
                            carton: 0
                        }*/]    
                    }
                    

                }).catch(e => {
                    console.log(e)
                })    
            } else {
                this.purchaseOrderItems[key].product_options = []
                this.purchaseOrderItems[key].products = []
                
            }

            //always clear the checkbox when channging po
            this.purchaseOrderItems[key].selectAll = false
        },
        getWarehouseName(item) {
            return typeof item.warehouse!=='undefined' && item.warehouse!==null && typeof item.warehouse.name!=='undefined' ? item.warehouse.name : 'N/A'
        },
        updatePurchaseOrder(index) {
            this.purchaseOrderItems[index].products = []
            //this.fetchSinglePurchasOrderLoading = true
            //this.fetchPurchaseOrderOptions()
        },
        removeContainer(item) {
            let getIndex = this.containerItems.indexOf(item)
            this.containerItems.splice(getIndex, 1)
        },
        addContainer() {
            this.containerItems.push({
                id: new Date().getTime(),
                container_num: '',
                size: '',
                cbm: null,
                kg: null,
                cartons: 0,
                seal_num: null
            })
        },
        formatDate(value) {
            return moment(value).format('MMM DD, YYYY')
        },
        getPoStatus(value) {
            return value === 'pending' ? 'Pending' : 'Partially Booked'
        },
        removePurchaseOrderManualItem(key) {
            this.purchaseOrderManualItems.splice(key, 1)            
        },
        removePurchaseOrderItem(key) {
            this.purchaseOrderItems.splice(key, 1)            
        },
        addManualPurchaseOrder() {
            //this.addManualPoModalView = true
            this.purchaseOrderManualItems.push({
                products: [],
                supplier_id: 0,
                cargo_ready_date: '',
                /*
                documents: [{
                    name: '832049-commercial-invoice.docs',
                    type: 'Commercial Invoice',
                    document_beginning: ''
                }],*/
                documents: [],
                menuCargoReadyDate: false,
                selectAll: false,
                total_cartons: 0,
                total_volumes: 0,
                total_weights: 0,
            })
        },
        addPurchaseOrderItem() {
            this.purchaseOrderId++
            this.purchaseOrderItems.push({
                id: this.purchaseOrderId,
                products: [],
                purchase_order_id: '',
                product_options: [],
                selectAll: false
            })
        },
        removeProductFromPurchaseOrders(key,item) {
            let getIndex = this.purchaseOrderItems[key].products.indexOf(item)
            this.purchaseOrderItems[key].selectAll = false
            this.purchaseOrderItems[key].products.splice(getIndex, 1)
        },
        addProductToPurchaseOrders( key ) {
            this.productId++
            this.purchaseOrderItems[key].products.push({
                product_id: '',
                addAll: false,
                carton: 0,
                unit: 0,
                volume: 0,
                weight: 0,
                unit_price: 0,
                units_per_carton: 0,
                amount: 0,
                id: this.productId,
                action: ''
            })

            if ( !this.isMobile ) {
                let getProducts = this.purchaseOrderItems[key].products
                getProducts = _.filter(getProducts, e => (typeof e.addSpecial=='undefined'))
                getProducts.push({
                    addSpecial: true,
                    product_id: '',
                    amount: 0,
                    carton: 0,
                    unit: 0,
                    weight: 0,
                    volume: 0,
                    units_per_carton: 0,
                    unit_price: 0
                })
                /*
                getProducts = _.filter(getProducts, e => (typeof e.special=='undefined'))
                getProducts.push({
                    special: true,
                    action: '',
                    amount: 0,
                    carton: 0,
                    unit: 0,
                    units_per_carton: 0,
                    unit_price: 0
                })*/
                this.purchaseOrderItems[key].products = getProducts    
            }

            this.purchaseOrderItems[key].products.map((p,k) => {
                let setIndex = parseInt(k) + 1
                this.purchaseOrderItems[key].products[k].index = (setIndex >=10 ) ? setIndex: `0${setIndex}`
            })
            
        },
        addShipmentSuccess() {
            this.$emit('update:addShipmentDialogModalView', true)
        },
        notificationSuccess(message) {
            iziToast.success({
                theme: 'dark',
                message: `<h4 style="font-weight: 500 !important; color: #fff !important;">${message}</h4>`,
                backgroundColor: '#16B442',
                messageColor: '#ffffff',
                iconColor: '#ffffff',
                progressBarColor: '#ADEEBD',
                displayMode: 1,
                position: 'bottomCenter',
                timeout: 3000,
            });
        },
        notificationError() {
            iziToast.error({
                title: "Error",
                message: 'An unexpected error occured. Please try again.',
                displayMode: 1,
                position: 'bottomCenter',
                timeout: 3000
            });
        },
        reloadShipments(){
            this.$emit('reloadShipments')
        },
        addShipment() {
            this.notificationSuccess('This is still in progress.')
            /*
            if ( this.$refs['formCreateShipment'].validate() ) {
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
                    this.addShipmentSuccess()                   
                    this.reloadShipments()
                }).catch(e => {
                    console.log(e)
                })

            }*/

        },
        ucFirst(str) {
            let pieces = str.split(" ")
            for ( let i = 0; i < pieces.length; i++ ) {
                let j = pieces[i].charAt(0).toUpperCase()
                pieces[i] = j + pieces[i].substr(1)
            }
            return pieces.join(" ")
        },
        selectPage(item) {
            let findIndex = _.findIndex(this.sidebarItems, { icon: item.icon })
            this.sidebarItems.map((sidebarItem, key) => {
                this.sidebarItems[key].selected = false
            })
            this.sidebarItems[findIndex].selected = true
            if ( !this.isMobile ) {
                this.$refs[this.sidebarItems[findIndex].reference].scrollIntoView({block: 'start', behavior: 'smooth'})   
            }            
        },
        clickOutside() {
            this.$emit('close')
        },
        close() {
            this.$emit('close')
        },
    },
}
</script>