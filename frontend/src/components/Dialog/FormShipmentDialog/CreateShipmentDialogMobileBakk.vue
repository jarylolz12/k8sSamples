<template>
<div style="border-bottom: 0px solid tranparent;" id="booking-shipment-dialog-mobile-wrapper" class="edit-shipment-mobile-wrapper slide-in-fwd-center" v-if="isMobile && show">
    <div style="display: none !important;" class="d-flex header-title-wrapper">
        <h2 style="float: left;" class="w-full">{{ "Create Booking Request" }}</h2>
        <button style="float: right;" icon dark class="btn-close btn-close-header" @click.stop="close">
            <v-icon>mdi-close</v-icon>
        </button>
        <div style="clear:both;"></div>
    </div>
    <div :style="`width: 100%;`">
        <div class="d-flex">
            <v-tabs
                height="50px"
                fixed-tabs
                active-class="edit-shipment-tab-active"
            >
                <v-tab
                    v-for="(si,key) in sidebarItemsFiltered"
                    v-bind:key="`esd-mobile-${key}`"
                    @click="selectPage(si)"
                    :id="`${si.reference}-id`"
                    :class="`${(windowWidth <= 412) ? 'mobile-412' : ''}`"
                >
                    {{ si.label }}
                </v-tab>
            </v-tabs>
        </div>
        <div style="position: relative;" class="d-flex flex-column w-full form-wrapper">
            <div style="height: 50vh;" class="preloader w-full justify-center align-center" v-if="!loaded">
                <v-progress-circular :size="40" color="#0171a1" indeterminate>
                </v-progress-circular>
            </div>
            <v-form style="min-height: 100vh;" v-if="loaded" id="bookingFormShipmentMobile" :class="`ref-${reference}`" :ref="`${reference}Mobile`" action="#" @submit.prevent="">
                <div style="padding: 16px !important;">
                    <div class="general-information" v-if="currentTab=='generalInformationSection'">
                        <div ref="generalInformationSectionMobile" v-if="loaded" class="content-title w-full">
                            General Information
                        </div>
                        <div class="form-label label-common-style">
                            <span>{{ "YOUR ROLE" }}</span>
                        </div>
                        <div class="d-flex radio-group-wrapper">
                            <div v-bind:key="`role-${key}`" v-for="(r,key) in roles" :class="`d-flex radio-item align-center ${(r === editItem.role) ? 'selected' : '' }`">
                                <label style="position: relative;" class="radio-label-wrapper">
                                    {{ ucFirst(r) }}
                                    <input name="role" @click.stop="selectRole(r)" :value="r" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.role" />
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <select-auto-complete
                            :content-class="`shipper-main-wrapper label-common-style ${isMobile ? 'main-wrapper-mobile' : ''}`"
                            label="SHIPPER"
                            :isMobile="isMobile"
                            :field.sync="editItem.shipper"
                            :items="filteredShipperOptions"
                            :menuProps="{ contentClass: 'po-lists-items',...menuProps}"
                            marginBottom="16px"
                            width="100%"
                            :background="`${editItem.role === 'shipper' ? 'selected' : 'not-selected'}`"
                        >
                            <template
                                v-slot:input="{ mainContent }">
                                <v-autocomplete
                                attach=".shipper-main-wrapper"
                                spellcheck="false"
                                :items="mainContent.items"
                                placeholder="Select Shipper"
                                outlined
                                @change="mainContent.updateValue"
                                item-text="name"
                                item-value="id"
                                :disabled="editItem.role=== 'shipper'"
                                :value="mainContent.field"
                                :height="40"
                                :menu-props="{...mainContent.menuProps}"
                                hide-details="auto"
                                clearable>
                                <template v-slot:item="{ item }">
                                    <div class="d-flex flex-column row-wrapper" style="width: 100%;">
                                        <div style="width: 100%;" class="d-flex first-row justify-space-between">
                                            <span style="color: #4A4A4A !important;">{{ item.name }}</span>
                                        </div>
                                    </div>
                                </template>
                                </v-autocomplete>
                            </template>
                        </select-auto-complete>
                        <div class="form-label label-common-style">
                            <span style="text-transform: uppercase;">{{ "Shipper’s details info" }}</span>
                        </div>
                        <div id="text-area-wrapper-5" class="text-field-wrapper input-text-wrapper textarea-wrapper">
                            <v-textarea
                                class="text-fields"
                                outlined
                                :height="76"
                                v-model="editItem.shipper_details_info"
                                placeholder="Shipper's info"
                                hide-details="auto"
                                autocomplete="off">
                            </v-textarea>
                        </div>
                        <select-auto-complete
                            :content-class="`consignee-main-wrapper label-common-style ${isMobile ? 'main-wrapper-mobile' : ''}`"
                            :isMobile="isMobile"
                            label="CONSIGNEE"
                            :field.sync="editItem.consignee"
                            :items="filteredConsigneeOptions"
                            :menuProps="menuProps"
                            marginBottom="16px"
                            :background="`${editItem.role === 'consignee' ? 'selected' : 'not-selected'}`"
                        >
                            <template
                                v-slot:input="{ mainContent }">
                                <v-autocomplete
                                attach=".consignee-main-wrapper"
                                spellcheck="false"
                                :items="mainContent.items"
                                :disabled="editItem.role=== 'consignee'"
                                placeholder="Select consignee"
                                outlined
                                @change="mainContent.updateValue"
                                item-text="name"
                                item-value="id"
                                :height="40"
                                :value="mainContent.field"
                                :menu-props="{...mainContent.menuProps}"
                                hide-details="auto"
                                clearable>
                                <template v-slot:item="{ item }">
                                    <div class="d-flex flex-column row-wrapper" style="width: 100%;">
                                        <div style="width: 100%;" class="d-flex first-row justify-space-between">
                                            <span>{{ item.name }}</span>
                                        </div>
                                        <div class="d-flex second-row" style="width: 100%; padding-bottom: 4px;">
                                            <span>
                                                {{
                                                    item.address
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </template>
                                </v-autocomplete>
                            </template>
                        </select-auto-complete>
                        <div class="form-label label-common-style">
                            <span style="text-transform: uppercase;">{{ "CONSIGNEE’S DETAILS INFO" }}</span>
                        </div>
                        <div style="width: 100% !important;" id="text-area-wrapper-3" class="text-field-wrapper input-text-wrapper textarea-wrapper">
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
                        <div class="checkbox-wrapper-create checkbox-wrapper-desktop checkbox-wrapper-notify-party checkbox-wrapper-notify-party-mobile">
                            <label :class="`${editItem.is_notify_party ? 'checked': ''} d-flex`" style="position: relative;">
                                <generic-icon :marginLeft="0" :iconName="`${(editItem.is_notify_party) ? 'checked' : 'not-checked'}`"></generic-icon>
                                <input @click.prevent="notifyParty(editItem)" style="position: absolute; opacity: 0;" type="checkbox" :checked="editItem.is_notify_party" class="" />
                                <span class="notify-party-span" style="color: #4A4A4A; padding-left: 12px !important;">Notify party is different from Consignee</span>
                            </label>
                        </div>
                        <custom-text-area
                            label="NOTIFY"
                            marginTop="0px"
                            :field.sync="editItem.notify_details_info"
                            labelColor="#819FB2"
                            :isMobile="isMobile"
                            id="text-area-wrapper-custom-3"
                            placeholderText="Same as Consignee"
                            :inputFontWeight="400"
                            marginBottom="19px"
                            :inputFontSize="14"
                        >
                            <template v-slot:label="{ label }">
                                <div class="form-label label-common-style">
                                    <span class="text-uppercase">{{ label }}</span>
                                </div>
                            </template>
                            <template v-slot:input="{ mainContent }">
                                <v-textarea
                                    :class="`text-fields custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize} ${editItem.is_notify_party ? 'notify-selected' : 'notify-not-selected'}`"
                                    outlined
                                    :height="76"
                                    :disabled="!editItem.is_notify_party"
                                    @change="mainContent.updateValue"
                                    :placeholder="mainContent.placeholderText"
                                    hide-details="auto"
                                    autocomplete="off">
                                </v-textarea>
                            </template>
                        </custom-text-area>
                        <select-auto-complete
                            :content-class="`location-from-main-wrapper label-common-style ${isMobile ? 'main-wrapper-mobile' : ''}`"
                            :isMobile="isMobile"
                            label="FROM"
                            :field.sync="editItem.location_from"
                            :items="filteredTerminalRegions"
                            :menuProps="{ contentClass: 'po-lists-items',...menuProps}"
                            marginBottom="16px"
                            width="100%"
                            :rules="locationFromRules"
                            background="not-selected"
                        >
                            <template
                                v-slot:input="{ mainContent }">
                                <v-autocomplete
                                attach=".location-from-main-wrapper"
                                spellcheck="false"
                                :rules="mainContent.rules"
                                :items="mainContent.items"
                                placeholder="Enter Location (Port, Warehouse etc.)"
                                outlined
                                @change="mainContent.updateValue"
                                :height="40"
                                :value="mainContent.field"
                                :menu-props="{...mainContent.menuProps}"
                                hide-details="auto"
                                clearable>
                                <template v-slot:item="{ item }">
                                    <div class="d-flex flex-column row-wrapper" style="width: 100%;">
                                        <div style="width: 100%;" class="d-flex first-row justify-space-between">
                                            <span style="color: #4A4A4A !important;">{{ item.text }}</span>
                                        </div>
                                    </div>
                                </template>
                                </v-autocomplete>
                            </template>
                        </select-auto-complete>
                        <select-auto-complete
                            :content-class="`location-to-main-wrapper label-common-style ${isMobile ? 'main-wrapper-mobile' : ''}`"
                            :isMobile="isMobile"
                            label="TO"
                            :field.sync="editItem.location_to"
                            :items="filteredTerminalRegions"
                            :menuProps="{ contentClass: 'po-lists-items',...menuProps}"
                            marginBottom="16px"
                            width="100%"
                            background="not-selected"
                            :rules="locationToRules"
                        >
                            <template
                                v-slot:input="{ mainContent }">
                                <v-autocomplete
                                attach=".location-to-main-wrapper"
                                spellcheck="false"
                                :items="mainContent.items"
                                placeholder="Enter Location (Port, Warehouse etc.)"
                                outlined
                                @change="mainContent.updateValue"
                                :height="40"
                                :rules="mainContent.rules"
                                :menu-props="{...mainContent.menuProps}"
                                hide-details="auto"
                                clearable>
                                <template v-slot:item="{ item }">
                                    <div class="d-flex flex-column row-wrapper" style="width: 100%;">
                                        <div style="width: 100%;" class="d-flex first-row justify-space-between">
                                            <span style="color: #4A4A4A !important;">{{ item.text }}</span>
                                        </div>
                                    </div>
                                </template>
                                </v-autocomplete>
                            </template>
                        </select-auto-complete>
                        <div class="form-label label-common-style">
                            <span>{{ "MODE" }}</span>
                        </div>
                        <div class="d-flex flex-column">
                            <div style="margin-bottom: 8px !important;" class="d-flex flex-row radio-group-wrapper">
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
                            <div style="margin-bottom: 5px !important;" class="form-label d-flex flex-row justify-space-between align-end label-common-style">
                                <span>{{ "RAIL" }}</span>
                            </div>
                            <div class="d-flex radio-group-wrapper">
                                <div v-bind:key="`mode-${key}`" v-for="(t,key) in anotherTypes" :class="`d-flex radio-item align-center ${(t === editItem.anotherType) ? 'selected' : '' }`">
                                    <v-radio-group v-model="editItem.anotherType">
                                        <v-radio color="primary" :label="t" :value="t"></v-radio>
                                    </v-radio-group>
                                    <kenetic-icon :marginLeft="8" :iconName="t.toLowerCase()"/>
                                </div>
                            </div>
                            <div class="form-label d-flex flex-row justify-space-between label-common-style">
                                <span>{{ "TYPE" }}</span>
                            </div>
                            <div class="d-flex radio-group-wrapper type-wrapper">
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
                            </div>
                            <div class="form-label d-flex flex-row justify-space-between label-common-style">
                                <span>{{ "INCOTERMS" }}</span>
                            </div>
                            <div class="d-flex radio-group-wrapper type-wrapper incoterms-mobile-wrapper">
                                <div v-bind:key="`incoterm-${key}`" v-for="(it,key) in filteredPaymentTerms" class="d-flex radio-item align-center">
                                    <label class="radio-label-wrapper">
                                        {{ it }}
                                        <input name="type" :value="it" class="custom-radio" style="position: absolute; opacity: 0;" type="radio" v-model="editItem.inco_term" />
                                        <span></span>
                                    </label>
                                    <kenetic-icon :marginLeft="8" :iconName="it.toLowerCase()"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="orders-section" v-if="currentTab=='purchaseOrderSection'">
                        <div ref="purchaseOrderSectionMobile" v-if="loaded" class="content-title w-full content-title-mobile d-flex">
                            {{ "Orders" }}
                        </div>
                        <div class="section-mobile">
                            <div v-bind:key="`poiii-mobile-${key}`" v-for="(poi, key) in filteredPurchaseOrderItems" :class="`purchase-order-table-wrapper-mobile d-flex flex-column ${(key == filteredPurchaseOrderItems.length - 1) ? 'last-child' : ''}`">
                                <div class="d-flex flex-row">
                                    <select-auto-complete
                                        v-if="poi.layout==='default'"
                                        content-class="po-wrapper-mobile"
                                        label=""
                                        :po="poi"
                                        :k="key"
                                        :filter="customFilterPo"
                                        :noLabel="true"
                                        @updateProducts="updateProducts"
                                        :isMobile="isMobile"
                                        :field.sync="poi.purchase_order_id"
                                        :items="purchaseOrderOptions"
                                        :menuProps="{ contentClass: 'po-lists-items po-lists-items-mobile',...menuProps}"
                                        marginBottom="16px"
                                        width="58%"
                                        marginTop="-18px"
                                        marginLeft="16px"
                                        background="not-selected"
                                    >
                                        <template
                                        v-slot:input="{ mainContent }">
                                        <v-autocomplete
                                        attach=".po-wrapper-mobile"
                                        spellcheck="false"
                                        :filter="mainContent.filter"
                                        :items="mainContent.items"
                                        placeholder="Enter SO"
                                        outlined
                                        @change="value => mainContent.updateProducts(mainContent.po, mainContent.key, value)"
                                        item-text="po_number"
                                        item-value="id"
                                        :value="mainContent.field"
                                        :height="40"
                                        :menu-props="{...mainContent.menuProps}"
                                        hide-details="auto"
                                        clearable>
                                            <template v-slot:item="{ item }">
                                                <div class="d-flex flex-column row-wrapper" style="width: 100%;">
                                                    <div style="width: 100%;" class="d-flex first-row justify-space-between">
                                                        <span style="color: #4A4A4A !important;">{{ item.po_number }}</span>
                                                    </div>
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
                                            </template>
                                        </v-autocomplete>
                                        </template>
                                    </select-auto-complete>
                                    <div v-if="poi.layout === 'manual'" style="margin-top:-18px; margin-left: 14px;">
                                        <input-text-mobile 
                                            placeholderText="Enter SO"
                                            textColor="black"
                                            contentClass="white"
                                            :field.sync="poi.purchase_order_number"
                                        />
                                    </div>
                                    <div style="margin-top: -18px;" class="delete-btn-wrapper">
                                        <v-btn style="padding: 10px !important; min-height: 0px !important;max-height: 36px !important; min-width: 36px !important;margin-left: 8px !important;" class="btn-white mr-4" @click="removePurchaseOrderItem(key)">
                                            <custom-icon iconName="trash-can" color="#ff5252"></custom-icon>
                                        </v-btn>
                                    </div>
                                </div>
                                <div v-if="poi.layout === 'default'" style="padding-left: 16px; padding-right: 16px;" class="d-flex flex-column">
                                    <div style="margin-bottom: 12px;" class="d-flex flex-row">
                                        <div class="checkbox-wrapper-create checkbox-wrapper-desktop checkbox-wrapper-notify-party checkbox-wrapper-mobile">
                                            <label :class="`${poi.selectAll ? 'checked': ''} d-flex flex-row`" style="position: relative;">
                                                <generic-icon :marginTop="3" :marginLeft="0" :iconName="`${(poi.selectAll) ? 'checked' : 'not-checked'}`"></generic-icon>
                                                <input @click="selectAllProducts(poi, key)" style="position: absolute; opacity: 0;" type="checkbox" :checked="poi.selectAll" class="" />
                                                <span class="checkbox-wrapper-mobile-label" style="padding-left: 12px;">
                                                    {{ "Select All Products" }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column po-product-item-mobile" v-bind:key="`poi-products-${kk}`" v-for="(product,kk) in poi.products">
                                        <div style="width: 100%;" class="d-flex flex-row justify-space-between">
                                            <div class="product-number">
                                                {{ "Product " + getProductNumber(kk) }}
                                            </div>
                                            <div @click.prevent="removeProductFromPurchaseOrders(kk,product)">
                                                <generic-icon iconName="close"></generic-icon>
                                            </div>
                                        </div>
                                        <div style="width: 100%;" class="d-flex flex-row align-center">
                                            <div class="item-label-common-style" style="width: 21%;">Product</div>
                                            <select-auto-complete
                                            content-class="po-product-wrapper-mobile"
                                            label=""
                                            :po="poi"
                                            :k="key"
                                            :product="product"
                                            :noLabel="true"
                                            :isMobile="isMobile"
                                            :field.sync="product.product_id"
                                            :items="poi.product_options"
                                            :menuProps="{ contentClass: 'po-lists-items po-lists-items-mobile',...menuProps}"
                                            width="79%"
                                            @updateProductPurchaseOrder="updateProductPurchaseOrder"
                                            marginTop="6px"
                                            marginLeft="16px"
                                            background="not-selected"
                                            >
                                            <template
                                            v-slot:input="{ mainContent }">
                                            <v-autocomplete
                                            attach=".po-product-wrapper-mobile"
                                            spellcheck="false"
                                            :items="mainContent.items"
                                            placeholder="Select Product"
                                            outlined
                                            :value="mainContent.field"
                                            @change="value => mainContent.updateProductPurchaseOrder(mainContent.po.product_options, mainContent.product, mainContent.key, value)"
                                            item-text="product.name"
                                            item-value="product_id"
                                            :height="40"
                                            :menu-props="{...mainContent.menuProps}"
                                            hide-details="auto"
                                            clearable>
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
                                            </template>
                                            </select-auto-complete>
                                        </div>
                                        <div style="width: 100%; margin-top: 3px;" class="d-flex flex-row align-center">
                                            <div style="width: 21% !important;"></div>
                                            <div style="width: 79% !important;" class="checkbox-wrapper-create checkbox-wrapper-desktop checkbox-wrapper-notify-party checkbox-wrapper-mobile">
                                                <label :class="`${product.addAll ? 'checked': ''} d-flex flex-row`" style="position: relative;">
                                                    <generic-icon :marginLeft="12" :iconName="`${(product.addAll) ? 'checked' : 'not-checked'}`"></generic-icon>
                                                    <input @click.prevent="addAllCartons(product, key)" style="position: absolute; opacity: 0;" type="checkbox" v-model="product.addAll" :checked="product.addAll" class="" />
                                                    <span class="checkbox-wrapper-mobile-label" style="padding-left: 12px;">Add all</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div style="width: 100%; margin-top: 3px;" class="d-flex flex-row align-center">
                                            <div class="item-label-common-style" style="width:21%;">Carton</div>
                                            <div style="width:29%; padding-left: 13px;">
                                                <input-text-mobile 
                                                    placeholderText=""
                                                    textColor="black"
                                                    :disabled="product.addAll"
                                                    :field.sync="product.carton"
                                                    fieldName="carton"
                                                />
                                            </div>
                                            <div class="item-label-common-style" style="width:10%; text-align: right;">Unit</div>
                                            <div style="width:40%; padding-left: 13px;">
                                                <input-text-mobile 
                                                    placeholderText=""
                                                    textColor="black"
                                                    :disabled="product.addAll"
                                                    :field.sync="product.unit"
                                                    fieldName="unit"
                                                />
                                            </div>
                                        </div>
                                        <div style="width: 100%; margin-top: 3px;" class="d-flex flex-row align-center">
                                            <div class="item-label-common-style" style="width:21%;">Volume</div>
                                            <div style="width:79%; padding-left: 13px;">
                                                <input-text-mobile 
                                                    placeholderText=""
                                                    textColor="black"
                                                    :field.sync="product.volume"
                                                    fieldName="volume"
                                                />
                                            </div>
                                        </div>
                                        <div style="width: 100%; margin-top: 3px;" class="d-flex flex-row align-center">
                                            <div class="item-label-common-style" style="width:21%;">Weight</div>
                                            <div style="width:79%; padding-left: 13px;">
                                                <input-text-mobile 
                                                    placeholderText=""
                                                    textColor="black"
                                                    :field.sync="product.weight"
                                                    fieldName="weight"
                                                />
                                            </div>
                                        </div>
                                        <div style="width: 100%; margin-top: 3px;" class="d-flex flex-row align-center">
                                            <div class="item-label-common-style" style="width:21%;">Unit Price</div>
                                            <div style="width:79%; padding-left: 13px;">
                                                <input-text-mobile 
                                                    placeholderText=""
                                                    textColor="black"
                                                    :field.sync="product.unit_price"
                                                    fieldName="unit_price"
                                                />
                                            </div>
                                        </div>
                                        <div style="width: 100%; margin-top: 3px;" class="d-flex flex-row align-center">
                                            <div class="item-label-common-style" style="width:21%;">Amount</div>
                                            <div style="width:79%; padding-left: 13px; text-align: right;">
                                                {{
                                                    calculateAmount(product)
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div style="width: 100%; margin-top: 3px; border-bottom: 1px solid #B4CFE0; margin-bottom: 12px; padding-bottom: 12px;" class="d-flex flex-row align-center">
                                        <div class="item-label-common-style" style="width:40%;">Cargo Ready Date</div>
                                        <div style="width:60%; padding-left: 13px;">
                                            <input-text-mobile 
                                                placeholderText=""
                                                textColor="black"
                                                :disabled="true"
                                                :field.sync="poi.cargo_ready_date"
                                            />
                                        </div>
                                    </div>
                                    <div style="width: 100%; border-bottom: 1px solid #B4CFE0; margin-bottom: 12px; padding-bottom: 12px;">
                                        <a @click.stop="addProductToPurchaseOrders(key)" class="add-item-mobile-link">+ Add Product</a>
                                    </div>
                                    <div class="product-item-totals-mobile" style="width: 100%; margin-bottom: 12px;">
                                        <div>Total Cartons: {{ calculateTotals(poi,'carton') }}, Total Volume:  {{ calculateTotals(poi,'volume') }}, Total Weight:  {{ calculateTotals(poi,'weight') }}</div>
                                    </div>
                                </div>
                                <div style="padding-left: 14px; padding-right: 14px;" v-if="poi.layout === 'manual'">
                                    <div style="width: 100%;" class="d-flex flex-row align-center">
                                        <div class="item-label-common-style" style="width: 21%;">Supplier</div>
                                        <select-auto-complete
                                        content-class="po-product-wrapper-mobile"
                                        label=""
                                        :noLabel="true"
                                        :isMobile="isMobile"
                                        :field.sync="poi.supplier_id"
                                        :items="manualSupplierOptions"
                                        :menuProps="{ contentClass: 'po-lists-items po-lists-items-mobile',...menuProps}"
                                        width="100%"
                                        marginTop="6px"
                                        marginLeft="16px"
                                        background="not-selected"
                                        >
                                            <template
                                            v-slot:input="{ mainContent }">
                                                <v-autocomplete
                                                attach=".po-product-wrapper-mobile"
                                                spellcheck="false"
                                                :items="mainContent.items"
                                                placeholder="Select Supplier"
                                                outlined
                                                :value="mainContent.field"
                                                @change="value => mainContent.updateValue(value)"
                                                item-text="name"
                                                item-value="id"
                                                :height="40"
                                                :menu-props="{...mainContent.menuProps}"
                                                hide-details="auto"
                                                clearable>
                                                    <template v-slot:item="{ item }">
                                                        <div class="d-flex flex-row justify-space-between product-item-wrapper">
                                                            <div style="width: 50%;" class="d-flex flex-column">
                                                                <div>
                                                                    <p> {{ item.name }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </v-autocomplete>
                                            </template>
                                        </select-auto-complete>
                                    </div>
                                    <div class="text-field-wrapper dates-wrapper d-flex flex-row align-center">
                                        <div class="item-label-common-style" style="width: 45%;">Cargo Ready Date</div>
                                        <v-menu
                                        v-model="poi.menuCargoReadyDate"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                            class="text-fields etd-field date-fields pom-cargo-ready-date" 
                                            placeholder="MM-DD-YYYY"
                                            outlined
                                            v-bind="attrs"
                                            v-on="on"
                                            type="text"
                                            hide-details="auto"
                                            clear-icon
                                            :height="40"
                                            v-model="poi.cargo_ready_date"
                                            @input="val => updateCargoReadyDateInput(poi,val)"
                                            append-icon="mdi-calendar"
                                            />
                                        </template>
                                        <v-date-picker v-model="poi.cargo_ready_date"></v-date-picker>
                                        </v-menu>
                                    </div>
                                    <div class="d-flex flex-row form-label label-common-little-style align-center">
                                        <div class="item-label-common-style" style="width: 40%;">{{ "Total Carton" }}</div>
                                        <input-text-mobile 
                                            placeholderText=""
                                            textColor="black"
                                            :field.sync="poi.total_cartons"
                                        />
                                    </div>
                                    <div class="d-flex flex-row form-label label-common-little-style align-center">
                                        <div class="item-label-common-style" style="width: 40%;">{{ "Total Volumes" }}</div>
                                        <input-text-mobile 
                                            placeholderText=""
                                            textColor="black"
                                            :field.sync="poi.total_volumes"
                                        />
                                    </div>
                                    <div style="border-bottom: 1px solid #B4CFE0 !important; margin-bottom: 12px; padding-bottom: 12px;" class="d-flex flex-row form-label label-common-little-style align-center">
                                        <div class="item-label-common-style" style="width: 40%;">{{ "Total Weights" }}</div>
                                        <input-text-mobile 
                                            placeholderText=""
                                            textColor="black"
                                            :field.sync="poi.total_weights"
                                        />
                                    </div>
                                    <div v-bind:key="`dom-manual-mobile-${kkk}`" v-for="(d,kkk) in poi.documents" class="d-flex flex-column document-items-mobile-wrapper">
                                        <div class="d-flex flex-row justify-space-between">
                                            <div class="d-flex flex=row">
                                                <generic-icon iconName="document"></generic-icon>
                                                <div class="document-label">{{ d.name }}</div>
                                            </div>
                                            <div style="width: 10%;">
                                                <a class="d-flex justify-end" @click.stop="removeFile(d)" style="cursor:pointer;">
                                                    <generic-icon iconName="close"></generic-icon>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row">
                                            <div class="item-label-common-style d-flex align-center" style="width: 40%;">{{ "Type" }}</div>
                                            <input-select
                                                customStyles="width: 60% !important;"
                                                :field.sync="d.type"
                                                id="autocomplete-wrapper"
                                                placeholder="Select Document Type"
                                                :noBorder="true"
                                                :typeOptions="documentTypes"
                                            ></input-select>
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 12px;" class="d-flex flex-row">
                                        <a @click.stop="addDocuments" class="d-flex pom-upload-document" style="cursor:pointer">
                                            <generic-icon iconName="upload"></generic-icon>
                                            <span class="d-flex align-center" style="color: #0171A1 !important; padding-left: 6px;">{{ "Upload Document" }}</span>
                                        </a>
                                        <input  
                                            type="file" 
                                            id="upload_documents_mobile" 
                                            @change="(e) => inputChanged(e, poi)" 
                                            name="upload_po_documents"
                                            accept=""
                                            multiple
                                            style="display: none;"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div style="padding-top: 8px; margin-bottom: 12px;" class="add-product-purchase-order-wrapper d-flex flex-row">
                                <v-btn class="btn-white mr-4 shipment-header-button-btn" @click="addPurchaseOrderItem">
                                    <span>{{ "+ Add Sales Order" }}</span>
                                </v-btn>
                                <v-btn class="btn-white mr-4 shipment-header-button-btn" @click="addManualPurchaseOrder">
                                    <span>{{ "+ Add Manual SO" }}</span>
                                </v-btn
                                >
                            </div>
                            <div class="d-flex flex-column cargo-details-title-mobile">
                                {{ "Cargo Details" }}
                            </div>
                            <div class="d-flex flex-column form-label label-common-little-style">
                                <span>{{ "Total Carton" }}</span>
                                <input-text-mobile 
                                    placeholderText=""
                                    textColor="black"
                                    :disabled="true"
                                    :static="true"
                                    :field="calculateOverAllTotal('carton')"
                                />
                            </div>
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column" style="width: 50%;">
                                    <div class="form-label label-common-little-style">
                                        <span>{{ "Total Volume" }}</span>
                                    </div>
                                    <div>
                                        <input-text-mobile 
                                            placeholderText=""
                                            textColor="black"
                                            :disabled="true"
                                            :static="true"
                                            :field="calculateOverAllTotal('volume')"
                                        />
                                    </div>
                                </div>
                                <div style="width: 50%;">
                                    <div style="padding-left: 16px;" class="form-label label-common-little-style">
                                        <span>{{ "Total Weight" }}</span>
                                    </div>
                                    <div style="padding-left: 16px;">
                                        <input-text-mobile 
                                            placeholderText=""
                                            textColor="black"
                                            :disabled="true"
                                            :static="true"
                                            :field="calculateOverAllTotal('weight')"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <custom-text-area
                                    label="Commodity, HS Code, material, usage"
                                    marginTop="0px"
                                    labelColor="#819FB2"
                                    :isMobile="isMobile"
                                    id="text-area-wrapper-custom-mobile"
                                    placeholderText="Enter Commodity, HS Code, Material, Usage"
                                    :inputFontWeight="400"
                                    marginBottom="19px"
                                    :inputFontSize="12"
                                >
                                    <template v-slot:label="{ label }">
                                        <div class="form-label label-common-little-style">
                                            <span class="text-uppercase">{{ label }}</span>
                                        </div>
                                    </template>
                                    <template v-slot:input="{ mainContent }">
                                        <v-textarea
                                            :class="`text-fields custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize} placeholder-black`"
                                            outlined
                                            :height="76"
                                            @change="mainContent.updateValue"
                                            :placeholder="mainContent.placeholderText"
                                            hide-details="auto"
                                            autocomplete="off">
                                        </v-textarea>
                                    </template>
                                </custom-text-area>
                            </div>
                        </div>
                    </div>
                    <div class="containers-section" v-if="currentTab=='containerSection'">
                        <div ref="containerSectionMobile" v-if="loaded" class="content-title w-full content-title-container content-title-mobile d-flex">
                            Containers
                        </div>
                        <div class="section-mobile">
                            <div class="item">
                                <div class="table-wrapper">

                                    <containers-table
                                        :headers="headersContainersMobile"
                                        :items.sync="containerNewItems"
                                        :isMobile="isMobile"
                                        headerBackground="#F7F7F7"
                                    >
                                    </containers-table>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="content-title">
                            {{
                                "Others"
                            }}
                        </div>
                        <div class="d-flex flex-column">
                            <custom-text-area
                                label="Marks"
                                marginTop="0px"
                                :field.sync="editItem.marks"
                                labelColor="#819FB2"
                                placeholderText="Enter Marks"
                                :inputFontWeight="400"
                                :inputFontSize="14"
                            >
                                <template v-slot:label="{ label }">
                                    <div class="form-label">
                                        <span class="text-uppercase">{{ label }}</span>
                                        <span class="optional-mobile" style="color: #819FB2 !important;"> {{ "(Optional)" }}</span>
                                    </div>
                                </template>
                                <template v-slot:input="{ mainContent }">
                                    <v-textarea
                                        :class="`text-fields custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize}`"
                                        outlined
                                        :height="74"
                                        @change="mainContent.updateValue"
                                        :placeholder="mainContent.placeholderText"
                                        hide-details="auto"
                                        autocomplete="off">
                                    </v-textarea>
                                </template>
                            </custom-text-area>
                            <custom-text-area
                                marginTop="16px"
                                marginBottom="12px"
                                label="Special Instructions"
                                id="text-area-wrapper-custom-2"
                                :field.sync="editItem.special_instructions"
                                labelColor="#819FB2"
                                placeholderText="Enter Special Instructions"
                                :inputFontWeight="400"
                                :inputFontSize="14"
                            >
                                <template v-slot:label="{ label }">
                                    <div class="form-label">
                                        <span class="text-uppercase">{{ label }}</span>
                                        <span class="optional-mobile" style="color: #819FB2 !important;"> {{ "(Optional)" }}</span>
                                    </div>
                                </template>
                                <template v-slot:input="{ mainContent }">
                                    <v-textarea
                                        outlined
                                        :height="74"
                                        @change="mainContent.updateValue"
                                        :placeholder="mainContent.placeholderText"
                                        :class="`text-fields custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize}`"
                                        hide-details="auto"
                                        autocomplete="off">
                                    </v-textarea>
                                </template>
                            </custom-text-area>
                        </div>
                    </div>
                    <div v-if="!showSubmitRequestModal" style="margin-top: 16px; position: absolute; bottom: -20px; " class="d-flex flex-row">
                        <v-btn style="margin-right: 16px;" v-if="currentPage==2" @click.stop="previous" class="btn-white shipment-header-button-btn">
                            <span>{{ "&lt;" }}</span>
                        </v-btn>
                        <button v-if="currentPage<=2" @click.stop="next" class="btn-blue mr-4">
                            <span>{{ "Next" }}</span>
                        </button>
                        <button v-if="currentPage==3" @click.stop="addShipment" class="btn-blue mr-4">
                            <span>{{ "Submit Request" }}</span>
                        </button>
                        <v-btn class="btn-white shipment-header-button-btn">
                            <span>{{ "Save as Draft" }}</span>
                        </v-btn>
                    </div>
                </div>
                
                <div v-if="1==2" class="d-flex flex-row btn-save-group-mobile">
                    <button @click.stop="addShipment" class="btn-blue mr-4">
                        <span>{{ "Save" }}</span>
                    </button>
                    
                    <v-btn @click.stop="close" class="btn-white shipment-header-button-btn">
                        <span>{{ "Cancel" }}</span>
                    </v-btn>
                </div>
            </v-form>
        </div>
    </div>
</div>
</template>
<style lang="scss">
@import "./scss/bookingShipmentDialogMobile.scss";
</style>
<script>
import KeneticIcon from '../../Icons/KeneticIcon'
import GenericIcon from '../../Icons/GenericIcon'
import CustomIcon from '../../Icons/CustomIcon'
import ContainersTable from './Tables/ContainersTable'
import SelectAutoComplete from "./SelectAutoCompletes/SelectAutoComplete";
import CustomTextArea from "./TextAreas/TextArea";
import InputTextMobile from "./InputTexts/InputTextMobile";
import globalMethods from '../../../utils/globalMethods'
import moment from 'moment'
import _ from 'lodash'
import { mapActions, mapGetters } from 'vuex'
import jQuery from 'jquery'
import InputSelect from './InputSelects/InputSelect';
import headers from './Datas/tableHeaders';

export default {
	props: ['sidebarItems', 'isMobile', 'show', 'loaded', 'valid', 'editItem', 'filteredTerminals', 'windowWidth', 'menuEta', 'modes', 'types', 'purchaseOrderItems', 'purchaseOrderOptions','menuProps','containerItems', 'headersContainers', 'roles', 'filteredShipperOptions', 'filteredConsigneeOptions', 'filteredTerminalRegions', 'anotherTypes', 'paymentTerms','paymentTermsOthers', 'locationFromRules', 'locationToRules', 'customFilterPo', 'manualSupplierOptions', 'containerNewItems', 'reference' ,'showSubmitRequestModal'],
    components: {
        GenericIcon,
        KeneticIcon,
        CustomIcon,
        SelectAutoComplete,
        CustomTextArea,
        InputSelect,
        ContainersTable,
        InputTextMobile,
    },
    updated() {

        let getOldPurchaseOrderItems = this.purchaseOrderItems

        this.filteredPurchaseOrderItems.map(fpo => {
            getOldPurchaseOrderItems.map((gp,key) => {
                if ( fpo.purchase_order_id === gp.purchase_order_id ) {
                    getOldPurchaseOrderItems[key] = fpo
                }
            })
        })

        //set container height
        this.setDomHeight()
        this.$emit('update:purchaseOrderItems', getOldPurchaseOrderItems)

    },
    mounted() {
        this.filteredPurchaseOrderItems = this.purchaseOrderItems
        this.filteredPurchaseOrderItems.map((filteredPurchaseOrderItems, key) => {
            let getProducts = this.filteredPurchaseOrderItems[key].products
            let newProducts = []
            getProducts.map((gp) => {
                if ( typeof gp.addSpecial=='undefined' && typeof gp.special == 'undefined') {
                    newProducts.push(gp)
                }
            })
            this.filteredPurchaseOrderItems[key].products = newProducts
        })
        this.setDomHeight()

        //insert header title into the first child of the body
        this.insertHeaderTitle()

    },
    data: () => ({
        documentFiles: [],
        files: [],
        domHeight: 100,
        documentTypes: [
            {
                id: 0,
                name: 'Commercial Invoice'
            },
            {
                id: 1,
                name: 'Packing List'
            },
            {
                id: 2,
                name: 'Invoice and Packing List'
            },
            {
                id: 3,
                name: 'OBL Document'
            },
            {
                id: 4,
                name: 'Other Commercial Docs'
            },
            {
                id: 5,
                name: 'Delivery Order'
            },
            {
                id: 6,
                name: 'Other'
            },
        ],
        filteredPurchaseOrderItems: [], 
        currentTab: 'generalInformationSection',
        currentPage: 1,
        pageMaps: ['generalInformationSection', 'purchaseOrderSection', 'containerSection'],
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
        headersContainersMobile: headers.containersMobile.data
    }),
	methods: {
        ...globalMethods,
        ...mapActions(['fetchCarriers', 'fetchTerminalRegions', 'fetchContainers', 'fetchShipmentDetails','createShipment']),
        selectRole(r) {
            this.$emit('selectRole', r)
        },
        insertHeaderTitle() {
            
            //use jquery to insert into DOM
            jQuery('.header-title-wrapper-main').remove()
            jQuery('body').prepend('<div class="d-flex header-title-wrapper header-title-wrapper-main">'+jQuery('.header-title-wrapper').html()+'</div')

            //add event listener 
            jQuery('.btn-close-header').click(e => {
                e.preventDefault()
                
                //close modal
                this.close()
                
                //remove from DOM too
                jQuery('.header-title-wrapper-main').remove()
            })
        },
        setDomHeight() {
            if ( this.currentPage === 1 ) {
                let height = parseInt(jQuery('.general-information').height())
                this.domHeight = height * 0.5
                
            } else if ( this.currentPage === 2) {
                let height = parseInt(jQuery('.orders-section').height())
                this.domHeight = height * 0.5
            } else {
                let height = parseInt(jQuery('.containers-section').height())
                this.domHeight = height * 0.5
            }
        },
        addDocuments() {
            jQuery('#upload_documents_mobile').click()
        },
        removeFile(item) {
            let getIndex = this.documentFiles.indexOf(item)
            this.documentFiles.splice(getIndex, 1)
            this.files.splice(getIndex, 1)
        },
        updateCargoReadyDateInput(item, value) {
            this.$emit('updateCargoReadyDateInput', item, value)
        },
        inputChanged(e, item) {
            let files = e.target.files
            if ( !files.length )
                return false
            
            for (let i = 0; i < files.length; i++) {
                this.documentFiles.push({
                    file: files[i],
                    supplier_id: [],
                    type: '',
                    actions: '',
                    name: files[i].name,
                    fileError: false
                })
                this.files.push(files[i])
            }
            item.documents = this.documentFiles
        },
        calculateOverAllTotal(entity) {

            //calculate only the default po
            let total = 0
            if ( this.purchaseOrderItems.length > 0 ) {
                this.purchaseOrderItems.map(poi => {
                    if ( poi.layout ==='default' ) {
                        let getProducts = poi.products
                        if ( getProducts.length > 0 ) {
                            getProducts.map(gp => {
                                total += parseInt(gp[entity])

                                if ( entity === 'volume') {
                                    total +=parseFloat(gp[entity])
                                }
                            })
                        }
                    }
                })
            }
            return isNaN(total) ? 0 : total
        },
        getProductNumber(k) {
            let num = k + 1
            return ( num < 10 ) ? '0' + num : num
        },
        addManualPurchaseOrder() {
            this.$emit('addManualPurchaseOrder')
        },
        calculateTotals(po, entity) {
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
            //if there are products then sum all entity
            if ( newProducts.length > 0 ) {
                newProducts.map(np => {
                    if ( entity === 'volume' ) {
                        total += parseFloat(np[entity]).toFixed(2)
                    } else {
                        total += parseInt(np[entity])
                    }
                    
                })
            }
            return isNaN(total) ? 0 : total
        },
        previous() {
            this.currentPage--
            this.currentTab = this.pageMaps[this.currentPage - 1]
            this.$emit('selectPage', this.sidebarItemsFiltered[this.currentPage - 1])
            jQuery(`#${this.sidebarItemsFiltered[this.currentPage - 1].reference}-id`).click()
        },
        next() {
            this.currentPage++
            this.currentTab = this.pageMaps[this.currentPage - 1]
            jQuery(`#${this.sidebarItemsFiltered[this.currentPage - 1].reference}-id`).click()
        },
        notifyParty(item) {
            this.$emit('notifyParty', item)
        },
        addShipment() {
            /*
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
            }*/
            
            this.$emit('addShipment')
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
        getImgUrl(i){
            this.$emit('getImgUrl',i)
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
        updateProducts(item, key) {
            this.$emit('updateProducts', item, key)
        },
		selectPage(si) {
            /*
            this.$refs[si.referenceMobile].scrollIntoView({
                block: 'start',
                behavior: 'smooth'
            })*/
            this.currentTab = si.reference
            this.currentPage = this.pageMaps.indexOf(si.reference) + 1
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
        getDomHeight() {
            let height = 0
            if ( this.currentPage === 1 ) {
                height = 600
                
            } else if ( this.currentPage === 2) {
                height = 400
            } else {
                height = 500
            }
            return height
        },
        filteredPaymentTerms() {
            let finalTerms = []
            this.paymentTerms.map(pt => {
                finalTerms.push(pt)
            })

            this.paymentTermsOthers.map(pto => {
                finalTerms.push(pto)
            })
            
            return finalTerms
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