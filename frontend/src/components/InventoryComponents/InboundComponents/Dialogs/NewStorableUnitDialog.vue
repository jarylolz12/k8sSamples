<template>
    <v-dialog v-model="dialogNew" max-width="1020px" content-class="storable-dialog" :retain-focus="false" persistent scrollable>
        <v-card class="storable-dialog-card" :loading="getNewStorableUnitLoading">
          <template v-slot:progress>
              <v-progress-linear
                  active
                  indeterminate
              ></v-progress-linear>
          </template>
            <v-card-title>
                <span class="headline">{{ formTitle }}</span>
                <button icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </button>
            </v-card-title>

            <v-card-text>
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="storable-unit-info">
                        <div class="left-column-wrapper">
                            <div class="storable-info">
                                <div class="storable-action mb-3">
                                    <p class="storable-title">ACTION</p>
                                    <v-select
                                        class="text-fields select-items"
                                        :items="storableAction"
                                        v-model="editedItemStorableUnits.action"
                                        item-text="name"
                                        item-value="value"
                                        placeholder="Select Action"
                                        outlined
                                        hide-details="auto"
                                        :rules="rules"
                                        :menu-props="{ bottom: true, offsetY: true }">
                                    </v-select>
                                </div>

                                <div class="storable-label mb-3">
                                    <p class="storable-title">TYPE</p>
                                    <v-select
                                        class="text-fields select-items"
                                        :items="storableType"
                                        v-model="editedItemStorableUnits.type"
                                        item-text="name"
                                        item-value="value"
                                        placeholder="Select Type"
                                        outlined
                                        hide-details="auto"
                                        :menu-props="{ bottom: true, offsetY: true }">
                                    </v-select>
                                </div>

                                <div class="storable-dimension mb-3">
                                    <!-- <p class="storable-title">CARTON DIMENSIONS</p> -->
                                    <p class="storable-title">UNIT DIMENSIONS</p>
                                    <div class="input-container">
                                        <v-text-field
                                            background-color="white"
                                            height="40px"
                                            color="#002F44"
                                            v-model="editedItemStorableUnits.dimension.l"
                                            dense
                                            class="text-fields storable-length mr-1"
                                            outlined
                                            type="number"
                                            prefix="L"
                                            placeholder="0"
                                            hide-details="auto"
                                            min="0"
                                            @keydown="restrictValues($event)"
                                            @wheel="$event.target.blur()">
                                        </v-text-field>

                                        <v-text-field
                                            background-color="white"
                                            height="40px"
                                            color="#002F44"
                                            v-model="editedItemStorableUnits.dimension.w"
                                            dense
                                            class="text-fields storable-width mr-1"
                                            outlined
                                            type="number"
                                            prefix="W"
                                            placeholder="0"
                                            hide-details="auto"
                                            min="0"
                                            @keydown="restrictValues($event)"
                                            @wheel="$event.target.blur()">
                                        </v-text-field>

                                        <v-text-field
                                            background-color="white"
                                            height="40px"
                                            color="#002F44"
                                            v-model="editedItemStorableUnits.dimension.h"
                                            dense
                                            class="text-fields storable-height mr-1"
                                            outlined
                                            type="number"
                                            prefix="H"
                                            placeholder="0"
                                            hide-details="auto"
                                            min="0"
                                            @keydown="restrictValues($event)"
                                            @wheel="$event.target.blur()">
                                        </v-text-field>

                                        <v-select
                                            class="text-fields select-items storable-uom"
                                            :items="['cm', 'in']"
                                            v-model="editedItemStorableUnits.dimension.uom"
                                            item-text="name"
                                            item-value="id"
                                            height='40px'
                                            outlined
                                            placeholder="cm"
                                            hide-details="auto"
                                            :menu-props="{ bottom: true, offsetY: true }">
                                        </v-select>
                                    </div>
                                </div>

                                <div class="storable-dimension mb-3">
                                    <p class="storable-title">WEIGHT</p>
                                    <div class="input-container">
                                        <v-text-field
                                            background-color="white"
                                            height="40px"
                                            color="#002F44"
                                            v-model="editedItemStorableUnits.weight.value"
                                            dense
                                            class="text-fields storable-height mr-1"
                                            outlined
                                            placeholder="Enter weight"
                                            type="number"
                                            hide-details="auto"
                                            min="0"
                                            @keydown="restrictValues($event)"
                                            @wheel="$event.target.blur()">
                                        </v-text-field>

                                        <v-select
                                            class="text-fields select-items storable-uom"
                                            :items="['KG', 'LB', 'G']"
                                            item-text="name"
                                            item-value="id"
                                            height='40px'
                                            outlined
                                            v-model="editedItemStorableUnits.weight.unit"
                                            placeholder="KG/LB"
                                            hide-details="auto"
                                            :menu-props="{ bottom: true, offsetY: true }">
                                        </v-select>
                                    </div>
                                </div>

                                <div class="storable-copies mb-3" v-if="index === -1">
                                    <p class="storable-title">COPIES (Optional)</p>
                                    <div class="input-container">
                                        <v-text-field
                                            background-color="white"
                                            height="40px"
                                            color="#002F44"
                                            v-model="editedItemStorableUnits.copies"
                                            dense
                                            class="text-fields storable-height mr-1"
                                            outlined
                                            placeholder="Enter weight"
                                            type="number"
                                            hide-details="auto"
                                            min="1"
                                            @keydown="restrictValues($event)"
                                            :rules="copyRules">
                                        </v-text-field>
                                    </div>
                                </div>     
                            </div>
                        </div>

                        <div class="right-column-wrapper">
                            <div class="storable-unit-table-wrapper">
                                <v-data-table
                                    :headers="headers"
                                    :items="storableProducts"
                                    class="elevation-1 add-products-storable-table"
                                    mobile-breakpoint="769"
                                    hide-default-footer>

                                    <!-- old codes without showing the errors -->
                                    <!-- <template v-slot:[`item.inbound_product_id`]="{ item, index }">
                                        <div class="product-selection" v-if="!isMobile">
                                            <v-select
                                                class="text-fields select-product shrink"
                                                :items="filterProductLists"
                                                item-text="name"
                                                item-value="inbound_product_id"
                                                placeholder="Select Product"
                                                outlined
                                                v-model="item.inbound_product_id"
                                                :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                hide-details="auto"
                                                @change="getSelectedProductShippingUnit(item)"
                                                :rules="rules">

                                                <template v-slot:selection="{ item, index }">
                                                    <div class="v-select__selection v-select__selection--comma" :key="index">
                                                        #{{ item.sku }} {{ item.name }}
                                                    </div>
                                                </template>

                                                <template v-slot:item="{ item }">
                                                    <div class="option-items">
                                                        <div class="sku-item">
                                                            <div class="sku-details">
                                                                <span>#{{ item.sku }}</span>
                                                            </div>

                                                            <div>
                                                                <p class="name"> {{ item.name }} </p>
                                                            </div>

                                                            <div class="carton-units-wrapper">
                                                                <p> {{ item.carton }} cartons </p>
                                                                <p> {{ item.units }} units </p>
                                                            </div>
                                                        </div>

                                                        <div class="image-item">
                                                            <img :src="getImgUrl(item.image)" height="60px" width="60px" alt="">
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-select>

                                            <span class="error-message" style="font-size: 12px; color: red;">
                                                {{ isProductSelected(item, index) }}
                                            </span>
                                        </div>

                                        <div class="product-mobile-wrapper" v-if="isMobile">
                                            <div class="product-mobile-header">
                                                <p>SKU</p>

                                                <v-btn
                                                    v-show="storableProducts.length > 1"
                                                    icon
                                                    class="btn remove-btn"
                                                    @click="removeRow(item)">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                            </div>

                                            <v-select
                                                class="text-fields select-product shrink"
                                                :items="filterProductLists"
                                                item-text="name"
                                                item-value="inbound_product_id"
                                                placeholder="Select Product"
                                                outlined
                                                v-model="item.inbound_product_id"
                                                :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                hide-details="auto"
                                                @change="getSelectedProductShippingUnit(item)"
                                                :rules="rules">

                                                <template v-slot:selection="{ item, index }">
                                                    <div class="v-select__selection v-select__selection--comma" :key="index">
                                                        #{{ item.sku }} {{ item.name }}
                                                    </div>
                                                </template>

                                                <template v-slot:item="{ item }">
                                                    <div class="option-items">
                                                        <div class="sku-item">
                                                            <div class="sku-details">
                                                                <span>#{{ item.sku }}</span>
                                                            </div>

                                                            <div>
                                                                <p class="name"> {{ item.name }} </p>
                                                            </div>

                                                            <div class="carton-units-wrapper">
                                                                <p> {{ item.carton }} cartons </p>
                                                                <p> {{ item.units }} units </p>
                                                            </div>
                                                        </div>

                                                        <div class="image-item">
                                                            <img :src="getImgUrl(item.image)" height="60px" width="60px" alt="">
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-select>

                                            <span class="error-message" style="font-size: 12px; color: red;">
                                                {{ isProductSelected(item, index) }}
                                            </span>
                                        </div>
                                    </template>

                                    <template v-slot:[`item.carton_count`]="{ item, index }">
                                        <div class="item-carton-count-details">
                                            <v-tooltip left 
                                                open-delay="300" 
                                                :open-on-focus="true" 
                                                :open-on-click="false" 
                                                :open-on-hover="false"
                                                :content-class="getItemCountClass(item) ? 'has-selected right-arrow' : 'has-none'">

                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                        v-on:input="fillCartonUnit(item, $event)"
                                                        v-on="on"
                                                        :placeholder="item.shipping_unit == 'single_item' ? '--' : '0'"
                                                        id="carton-id"
                                                        type="number" 
                                                        outlined
                                                        v-model="item.carton_count"               
                                                        class="text-fields table-text-fields icc-carton-count shrink"
                                                        hide-details="auto"
                                                        :disabled="item.shipping_unit === 'single_item'"
                                                        :rules="item.shipping_unit !== 'single_item' ? [getExceededCartonCount(item), cartonCountRule.required, cartonCountRule.greater] : []"
                                                        min="1"
                                                        @keydown="restrictValues($event)"
                                                        @wheel="$event.target.blur()">
                                                    </v-text-field>
                                                </template>

                                                <span>{{ getItemCount(item, 'carton') }}</span>
                                            </v-tooltip>

                                            <span class="error-message" style="color: white;" v-if="!isMobile">
                                                {{ isProductSelectedOtherFieldsError(item, index) }}
                                            </span>
                                        </div>
                                    </template>

                                    <template v-slot:[`item.total_unit`]="{ item, index }">
                                        <div class="item-total-unit-details">
                                            <v-tooltip left
                                                open-delay="300" 
                                                :open-on-focus="true" 
                                                :open-on-click="false" 
                                                :open-on-hover="false"
                                                :content-class="getItemCountClass(item) ? 'has-selected right-arrow' : 'has-none'">

                                                <template v-slot:activator="{ on }">                                              
                                                    <v-text-field 
                                                        v-on="on"
                                                        placeholder="0" 
                                                        type="number" 
                                                        outlined 
                                                        v-model="item.total_unit"
                                                        class="text-fields table-text-fields itu-total-unit shrink"                          
                                                        hide-details="auto"
                                                        :rules="item.shipping_unit !== 'single_item' ? [getExceededUnitCount(item), unitCountRule.required, unitCountRule.greater] : []"
                                                        min="1"
                                                        @keydown="restrictValues($event)"
                                                        @wheel="$event.target.blur()">
                                                    </v-text-field>
                                                </template>                                            

                                                <span>{{ getItemCount(item, 'units') }}</span>
                                            </v-tooltip>

                                            <span class="error-message" style="color: white;" v-if="!isMobile">
                                                {{ isProductSelectedOtherFieldsError(item, index) }}
                                            </span>
                                        </div>
                                    </template>

                                    <template v-slot:[`item.actions`]="{ item }">
                                        <v-btn
                                            v-show="storableProducts.length > 1"
                                            icon
                                            class="btn remove-btn"
                                            @click="removeRow(item)">
                                            <v-icon>mdi-close</v-icon>
                                        </v-btn>
                                    </template> -->

                                    <template v-slot:item="{ item, index }">
                                        <tr class="storable-row-data" v-if="!isMobile">
                                            <td class="storable-col-data">
                                                <div class="product-selection">
                                                    <!-- :items="inboundProductsLists" -->
                                                    <v-select
                                                        class="text-fields select-product shrink"
                                                        :items="filterProductLists"
                                                        :item-disabled="disabledItems"
                                                        item-text="name"
                                                        item-value="inbound_product_id"
                                                        placeholder="Select Product"
                                                        outlined
                                                        v-model="item.inbound_product_id"
                                                        :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                        hide-details="auto"
                                                        @change="getSelectedProductShippingUnit(item)"
                                                        :rules="rules">

                                                        <template v-slot:selection="{ item, index }">
                                                            <div class="v-select__selection v-select__selection--comma" :key="index">
                                                                #{{ item.sku }} {{ item.name }}
                                                            </div>
                                                        </template>

                                                        <template v-slot:item="{ item }">
                                                            <div class="option-items">
                                                                <div class="sku-item">
                                                                    <div class="sku-details">
                                                                        <span>#{{ item.sku }}</span>
                                                                    </div>

                                                                    <div>
                                                                        <p class="name"> {{ item.name }} </p>
                                                                    </div>

                                                                    <div class="carton-units-wrapper">
                                                                        <p> {{ item.carton }} cartons </p>
                                                                        <p> {{ item.units }} units </p>
                                                                    </div>
                                                                </div>

                                                                <div class="image-item">
                                                                    <img :src="getImgUrl(item.image)" height="60px" width="60px" alt="">
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </v-select>

                                                    <span class="error-message" style="font-size: 12px; color: red;">
                                                        {{ isProductSelected(item, index) }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="storable-col-data">
                                                <div class="item-carton-count-details">
                                                    <v-tooltip left 
                                                        open-delay="300" 
                                                        :open-on-focus="true" 
                                                        :open-on-click="false" 
                                                        :open-on-hover="false"
                                                        :content-class="getItemCountClass(item) ? 'has-selected right-arrow' : 'has-none'">

                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field
                                                                v-on:input="fillCartonUnit(item, $event)"
                                                                v-on="on"
                                                                :placeholder="item.shipping_unit == 'single_item' ? '--' : '0'"
                                                                id="carton-id"
                                                                type="number" 
                                                                outlined
                                                                v-model="item.carton_count"               
                                                                class="text-fields table-text-fields icc-carton-count shrink"
                                                                hide-details="auto"
                                                                :disabled="item.shipping_unit === 'single_item'"
                                                                :rules="item.shipping_unit !== 'single_item' ? [getExceededCartonCount(item), cartonCountRule.required] : []"
                                                                min="0"
                                                                @keydown="restrictValues($event)"
                                                                @wheel="$event.target.blur()">
                                                                <!-- :rules="item.shipping_unit !== 'single_item' ? cartonRules : []" -->
                                                            </v-text-field>
                                                        </template>

                                                        <span>{{ getItemCount(item, 'carton') }}</span>
                                                    </v-tooltip>

                                                    <span class="error-message" style="color: white;" v-if="!isMobile">
                                                        {{ isProductSelectedOtherFieldsError(item, index) }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="storable-col-data"> 
                                                <div class="item-total-unit-details">
                                                    <v-tooltip left
                                                        open-delay="300" 
                                                        :open-on-focus="true" 
                                                        :open-on-click="false" 
                                                        :open-on-hover="false"
                                                        :content-class="getItemCountClass(item) ? 'has-selected right-arrow' : 'has-none'">

                                                        <template v-slot:activator="{ on }">                                              
                                                            <v-text-field 
                                                                v-on="on"
                                                                placeholder="0" 
                                                                type="number" 
                                                                outlined 
                                                                v-model="item.total_unit"
                                                                class="text-fields table-text-fields itu-total-unit shrink"                          
                                                                hide-details="auto"
                                                                :rules="item.shipping_unit !== 'single_item' ? [getExceededUnitCount(item), unitCountRule.required, unitCountRule.greater] : []"
                                                                min="1"
                                                                @keydown="restrictValues($event)"
                                                                @wheel="$event.target.blur()">
                                                            </v-text-field>
                                                        </template>                                            

                                                        <span>{{ getItemCount(item, 'units') }}</span>
                                                    </v-tooltip>

                                                    <span class="error-message" style="color: white;" v-if="!isMobile">
                                                        {{ isProductSelectedOtherFieldsError(item, index) }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="storable-col-data">
                                                <v-btn
                                                    v-show="storableProducts.length > 1"
                                                    icon
                                                    class="btn remove-btn"
                                                    @click="removeRow(item)">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                            </td>
                                        </tr>

                                        <tr class="storable-row-data" v-if="isMobile">
                                            <td class="storable-col-data">
                                                <div class="product-mobile-wrapper">
                                                    <div class="product-mobile-header-sku">
                                                        <p>SKU</p>

                                                        <v-btn
                                                            v-show="storableProducts.length > 1"
                                                            icon
                                                            class="btn remove-btn"
                                                            @click="removeRow(item)">
                                                            <v-icon>mdi-close</v-icon>
                                                        </v-btn>
                                                    </div>

                                                    <v-select
                                                        class="text-fields select-product shrink"
                                                        :items="filterProductLists"
                                                        item-text="name"
                                                        item-value="inbound_product_id"
                                                        placeholder="Select Product"
                                                        outlined
                                                        v-model="item.inbound_product_id"
                                                        :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                        hide-details="auto"
                                                        @change="getSelectedProductShippingUnit(item)"
                                                        :rules="rules">

                                                        <template v-slot:selection="{ item, index }">
                                                            <div class="v-select__selection v-select__selection--comma" :key="index">
                                                                #{{ item.sku }} {{ item.name }}
                                                            </div>
                                                        </template>

                                                        <template v-slot:item="{ item }">
                                                            <div class="option-items">
                                                                <div class="sku-item">
                                                                    <div class="sku-details">
                                                                        <span>#{{ item.sku }}</span>
                                                                    </div>

                                                                    <div>
                                                                        <p class="name"> {{ item.name }} </p>
                                                                    </div>

                                                                    <div class="carton-units-wrapper">
                                                                        <p> {{ item.carton }} cartons </p>
                                                                        <p> {{ item.units }} units </p>
                                                                    </div>
                                                                </div>

                                                                <div class="image-item">
                                                                    <img :src="getImgUrl(item.image)" height="60px" width="60px" alt="">
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </v-select>

                                                    <span class="error-message" style="font-size: 12px; color: red;">
                                                        {{ isProductSelected(item, index) }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="storable-col-data">
                                                <div class="storable-col-data-wrapper">
                                                    <td class="storable-col-data-inside">
                                                        <div class="product-mobile-wrapper mr-1">
                                                            <div class="product-mobile-header">
                                                                <p>CARTON</p>
                                                            </div>
                                                            
                                                            <div class="item-carton-count-details">
                                                                <v-tooltip bottom 
                                                                    open-delay="300" 
                                                                    :open-on-focus="true" 
                                                                    :open-on-click="false" 
                                                                    :open-on-hover="false"
                                                                    :content-class="getItemCountClass(item) ? 'has-selected top-arrow-storable' : 'has-none'">

                                                                    <template v-slot:activator="{ on }">
                                                                        <v-text-field
                                                                            v-on:input="fillCartonUnit(item, $event)"
                                                                            v-on="on"
                                                                            :placeholder="item.shipping_unit == 'single_item' ? '--' : '0'"
                                                                            id="carton-id"
                                                                            type="number" 
                                                                            outlined
                                                                            v-model="item.carton_count"               
                                                                            class="text-fields table-text-fields icc-carton-count shrink"
                                                                            hide-details="auto"
                                                                            :disabled="item.shipping_unit === 'single_item'"
                                                                            :rules="item.shipping_unit !== 'single_item' ? [getExceededCartonCount(item), cartonCountRule.required] : []"
                                                                            min="0"
                                                                            @keydown="restrictValues($event)"
                                                                            @wheel="$event.target.blur()">
                                                                            <!-- :rules="item.shipping_unit !== 'single_item' ? cartonRules : []" -->
                                                                        </v-text-field>
                                                                    </template>

                                                                    <span>{{ getItemCount(item, 'carton') }}</span>
                                                                </v-tooltip>

                                                                <span class="error-message" style="color: white;" v-if="!isMobile">
                                                                    {{ isProductSelectedOtherFieldsError(item, index) }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="product-mobile-wrapper">
                                                            <div class="product-mobile-header">
                                                                <p>UNIT</p>
                                                            </div>
                                                            
                                                            <div class="item-total-unit-details">
                                                                <v-tooltip bottom
                                                                    open-delay="300" 
                                                                    :open-on-focus="true" 
                                                                    :open-on-click="false" 
                                                                    :open-on-hover="false"
                                                                    :content-class="getItemCountClass(item) ? 'has-selected top-arrow-storable' : 'has-none'">

                                                                    <template v-slot:activator="{ on }">                                              
                                                                        <v-text-field 
                                                                            v-on="on"
                                                                            placeholder="0" 
                                                                            type="number" 
                                                                            outlined 
                                                                            v-model="item.total_unit"
                                                                            class="text-fields table-text-fields itu-total-unit shrink"                          
                                                                            hide-details="auto"
                                                                            :rules="item.shipping_unit !== 'single_item' ? [getExceededUnitCount(item), unitCountRule.required, unitCountRule.greater] : []"
                                                                            min="1"
                                                                            @keydown="restrictValues($event)"
                                                                            @wheel="$event.target.blur()">
                                                                        </v-text-field>
                                                                    </template>                                            

                                                                    <span>{{ getItemCount(item, 'units') }}</span>
                                                                </v-tooltip>

                                                                <span class="error-message" style="color: white;" v-if="!isMobile">
                                                                    {{ isProductSelectedOtherFieldsError(item, index) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr v-if="!getExceededCartonCount(item) || !getExceededUnitCount(item)">
                                            <td colspan="12" class="error-unit-carton-count">
                                                {{ !getExceededCartonCount(item) || !getExceededUnitCount(item) ? 
                                                    'Inbound order does not have enough unit of this product.'
                                                    : ''
                                                }}
                                            </td>
                                        </tr>
                                    </template>
                                </v-data-table>

                                <div class="add-row-wrapper">
                                    <button :disabled="showItems.length == 0" 
                                        class="btn-white add-btn" 
                                        @click="addRow">
                                        + Add Product
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="storable-button-actions">
                <button class="btn-blue" text @click="build" v-if="index === -1" 
                    :disabled="getNewStorableUnitLoading || getEditStorableUnitLoading || buildAndPrintLoading">
                    <span v-if="getNewStorableUnitLoading && !buildAndPrintLoading">
                        {{ editedItemStorableUnits.action === 'recieve' ? 'Receiving...' : 'Building...'}}
                    </span>
                    <span v-if="!getNewStorableUnitLoading || buildAndPrintLoading">
                        {{ editedItemStorableUnits.action === 'recieve' ? 'Receive' : 'Build'}}
                    </span>
                </button>

                <button class="btn-blue" text @click="buildAndPrint" v-if="index === -1"
                    :disabled="buildAndPrintLoading || getNewStorableUnitLoading">
                    <span v-if="buildAndPrintLoading">
                        {{ editedItemStorableUnits.action === 'recieve' ? 'Receiving...' : 'Building...'}}
                    </span>
                    <span v-if="!buildAndPrintLoading">
                        {{ editedItemStorableUnits.action === 'recieve' ? 'Receive' : 'Build'}} and Print
                    </span>
                </button>

                <button class="btn-blue" text @click="build" v-if="index > -1" 
                    :disabled="getNewStorableUnitLoading || getEditStorableUnitLoading">
                    <span v-if="getEditStorableUnitLoading"> Saving... </span>
                    <span v-if="!getEditStorableUnitLoading"> Save </span>
                </button>

                <button class="btn-white" text @click="close" 
                    :disabled="getNewStorableUnitLoading || getEditStorableUnitLoading || buildAndPrintLoading">
                    Cancel
                </button>
            </v-card-actions>            
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import globalMethods from '../../../../utils/globalMethods';
import _ from 'lodash'

export default {
    name: 'NewStorableUnitDialog',
    props: ['dialog', 'editedIndex', 'categoryLists', 'isMobile',
            'editedItem', 'defaultItem', 'productsData', 'linkData', 'index'],
    components: {
    },
    data: () => ({
        buildAndPrintLoading: false,
        valid: true,
        cache_items: true,
        headers: [
			{
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'inbound_product_id',
				fixed: true,
				width: "150px"
			},
			{
				text: 'CARTON',
				align: 'end',
				sortable: false,
				value: 'carton_count',
				fixed: true,
				width: "20%"
			},
			{
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "20%"
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
        storableAction: [
            {
                value: 'recieve',
                name: 'Receive Storable Unit'
            },
            {
                value: 'build',
                name: 'Build Storable Unit'
            }
        ],
        storableType: [
            {
                value: 'pallet',
                name: 'Pallet'
            },
            {
                value: 'drum',
                name: 'Drum'
            },
            {
                value: 'box',
                name: 'Box'
            },
            {
                value: 'loose box',
                name: 'Loose Box'
            }
        ],
        storableProducts: [{
            inbound_product_id: '',
            carton_count: '',
            total_unit: '',
            shipping_unit: ''
        }],
        storableItems: {
            action: "",
            type: "",
            customer_id: null,
            dimension: {
                l: 0,
                w: 0,
                h: 0,
                uom: 'cm'
            },
            weight: {
                value: '',
                unit: 'cm'
            },
            products:[] 
        },
        showTooltip: false,
        rules: [(v) => !!v || "Input is required."],
        hasProductDuplicates: false,
        cartonRules: [
            v => !!v || 'Carton is required',
            v => (parseFloat(v)>0) || 'Carton should be greater than 0',
        ],
        unitRules: [
            v => !!v || 'Unit is required',
            v => (parseFloat(v)>0) || 'Unit should be greater than 0',
        ],
        copyRules: [
            v => !!v || 'Copies is required',
            v => (parseFloat(v)>0) || 'Copies should be greater than 0',
        ],
        // multiple rule
        cartonCountRule: {
            required: v => !!v || 'Carton is required',
            // greater: v => (parseFloat(v)>0) || 'Carton should be greater than 0',
        },
        unitCountRule: {
            required: v => !!v || 'Unit is required',
            greater: v => (parseFloat(v)>0) || 'Unit should be greater than 0',
        }
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getNewStorableUnitLoading: 'inbound/getNewStorableUnitLoading',
            getEditStorableUnitLoading: 'inbound/getEditStorableUnitLoading'
        }),
        formTitle() {
            return this.index === -1 ? 'New Storable Unit' : 'Edit Storable Unit'
        },
        dialogNew: {
            get() {
                return this.dialog
            },
            set(value) {
                this.$emit('update:dialog', value)
            }
        },
        editedItemStorableUnits: {
            get() {                            
                if (this.editedItem !== 'undefined') {
                    if (this.index > -1) {
                        if (this.editedItem.products.length > 0) {
                            this.editedItem.products.map(v => {
                                if (v.shipping_unit === 'single_item') {
                                    v.carton_count = ''
                                }
                            })
                        }
                    }
                }

                return this.editedItem
            },
            set(value) {
                this.$emit('update:editedItem', value)
            }
        },   
        inboundProductsLists() {
            let products = []

            if (typeof this.productsData !== 'undefined' && this.productsData !== null) {
                if (typeof this.productsData.inbound_products !== 'undefined' && 
                    this.productsData.inbound_products !== null && 
                    this.productsData.inbound_products.length > 0) {
                    let items = this.productsData.inbound_products

                    items.map(v => {
                        products.push({
                            inbound_product_id: v.id,
                            name: v.product.name,
                            sku: v.product.sku,
                            image: v.product.image,
                            carton: v.actual_carton_count,
                            units: v.actual_total_unit,
                            remaining_carton_count: v.remaining_carton_count,
                            remaining_total_unit: v.remaining_total_unit,
                            shipping_unit: v.shipping_unit,
                            status: v.status
                        })
                    })
                }
            }

            return products
        },
        filterProductLists() {
            let lists = []

            if (this.index === -1) {
                if (this.inboundProductsLists.length > 0) {
                    this.inboundProductsLists.map(v => {
                        let item = { ...v }

                        if (item.shipping_unit === 'carton') {
                            if (item.remaining_carton_count !== 0 && item.remaining_total_unit !== 0) {
                                lists.push(item)
                            }
                        } else {
                            if (item.remaining_total_unit !== 0) {
                                lists.push(item)
                            }
                        }
                    })
                }
            } else {
                // lists = this.inboundProductsLists

                if (this.inboundProductsLists.length > 0) {
                    this.inboundProductsLists.map(v => {
                        let item = { ...v }

                        if (item.status === 'recieved') {
                            lists.push(item)
                        }
                    })
                }
            }

            return lists
        },        
        showItems() {
            return this.filterProductLists.filter(ele => {
                let found = this.storableProducts.find(i => i.inbound_product_id == ele.inbound_product_id)
                if(!found) {
                    return true
                } else {
                    return false
                }
            })
        },
        addNewStorableUnitTemplate() {
            let { action, type, dimension, weight, copies } = this.editedItemStorableUnits

            return {
                action,
                type,
                dimension: JSON.stringify(dimension),
                weight: JSON.stringify(weight),
                products: this.storableProducts,
                customer_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : '',
                warehouse_id: this.linkData.warehouse_id,
                inbound_id: this.linkData.inbound_id,
                copies: typeof copies === 'string' ? parseInt(copies) : copies
            }
        },
    },
    methods: {
        ...mapActions({
            newStorableUnit: 'inbound/newStorableUnit',
            fetchSingleInbound: 'inbound/fetchSingleInbound',
            updateStorableUnit: 'inbound/updateStorableUnit'
        }),
        ...globalMethods,
        addRow() {
            let getItem = this.storableProducts

            getItem.push({
                inbound_product_id: '',
                carton_count: '',
                total_unit: ''
            })
        },
        getImgUrl(pic) {
            // if returned image from the API has no https://po.shifl.com/storage/
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
        restrictValues(e) {
            // if(!((e.keyCode > 95 && e.keyCode < 106) || (e.keyCode > 47 && e.keyCode < 58) || e.keyCode == 8)) {
            //     e.preventDefault()
            //     return false
            // }

            if(e.key=='-' && e.keyCode==189 || e.key=='+' && e.keyCode==187 ) {
                e.preventDefault()
            }
        },
        removeRow(item) {
            let getIndex = this.storableProducts.indexOf(item)
            this.storableProducts.splice(getIndex, 1)
        },
        close() {
            this.$refs.form.resetValidation()
            this.storableProducts = [{
                inbound_product_id: '',
                carton_count: '',
                total_unit: ''
            }]

            this.editedItemStorableUnits = {
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
            }

            this.$emit('close')
        },
        async buildAndPrint() {
            this.buildAndPrintLoading = true;
            const label = await this.build(false);
            this.$emit("print", label);
            this.notificationCustom('Generating label...')
            this.close()
            this.buildAndPrintLoading = false;
        },
        async build(doClose = true) {
            this.$refs.form.validate()

            if (this.$refs.form.validate() || (()=> true)()) {
                let newStorage = null;
                if (this.index === -1) {
                    try {
                        newStorage = await this.newStorableUnit(this.addNewStorableUnitTemplate)
                        this.notificationMessage(`Storable Unit has been created successfully.`)
                        if (doClose) this.close();

                        let payload = { wid: this.linkData.warehouse_id, iid: this.linkData.inbound_id }
                        await this.fetchSingleInbound(payload)
                    } catch (e) {
                        this.notificationError(e)
                    }
                    return newStorage
                } else {                    
                    try {
                        let editStorableUnit = this.addNewStorableUnitTemplate
                        editStorableUnit.storable_id = this.editedItemStorableUnits.storable_id

                        await this.updateStorableUnit(this.addNewStorableUnitTemplate)
                        this.notificationMessage(`Storable Unit has been updated.`)
                        if (doClose) this.close();

                        let payload = { wid: this.linkData.warehouse_id, iid: this.linkData.inbound_id }
                        await this.fetchSingleInbound(payload)
                    } catch (e) {
                        this.notificationError(e)
                    }
                }
            }
        },
        inputClick(e, item) {
            if (item.inbound_product_id !== '') {
                this.showTooltip = true
            } else {
                this.showTooltip = false
            }
        },
        getItemCount(item, forItem) {
            if (item.inbound_product_id !== '') {
                let findItem = _.find(this.inboundProductsLists, (e) => e.inbound_product_id === item.inbound_product_id)
                
                if (findItem !== 'undefined') {
                    if (forItem === 'carton') {
                        return `Available ${findItem.remaining_carton_count} Cartons`
                    } else if (forItem === 'units') {
                        return `Available ${findItem.remaining_total_unit} Units`
                    }
                }
            }
        },
        getItemCountClass(item) {
            if (item.inbound_product_id !== '')              
                return true
            else
                return false
        },
        fillCartonUnit(item, value) {
            let findItem = _.find(this.productsData.inbound_products, (e) => e.id === item.inbound_product_id)
            
            if (findItem !== undefined) {
                item.total_unit = value * findItem.in_each_carton
            }
        },
        getSelectedProductShippingUnit(item) {
            if (item.inbound_product_id !== "") {
                let findInboundItem = _.find(this.inboundProductsLists, e => e.inbound_product_id === item.inbound_product_id)

                if (findInboundItem !== 'undefined') {
                    let shippingUnit = findInboundItem.shipping_unit
                    item.shipping_unit = shippingUnit
                }
            }
        },
        isProductSelected(item, index) {
            if (item.inbound_product_id !== "") {
                let findSelectedOption = _.findIndex(
                    this.storableProducts, e => (
                        (e.inbound_product_id == item.inbound_product_id) && (e.shipping_unit == item.shipping_unit)
                    ))

                if (findSelectedOption !== index) {
                    this.hasProductDuplicates = true
                    return 'This product has already been selected.'
                } else {
                    this.hasProductDuplicates = false
                }
            }
        },
        isProductSelectedOtherFieldsError(item, index) {
            if (item.inbound_product_id !== "") {
                let findSelectedOption = _.findIndex(
                    this.storableProducts, e => (
                        (e.inbound_product_id == item.inbound_product_id) && (e.shipping_unit == item.shipping_unit)
                    ))

                if (findSelectedOption !== index) {
                    return 'has-duplicate'
                } else {
                    return ''
                }
            }
        },
        disabledItems(item) {
            let findInboundItem = _.find(this.storableProducts, e => e.inbound_product_id === item.inbound_product_id)
            if (findInboundItem !== 'undefined' && findInboundItem !== undefined) {
                return (item.inbound_product_id == findInboundItem.inbound_product_id)
            }
        },
        getExceededCartonCount(item) {
            if (item.inbound_product_id !== '' && item.inbound_product_id !== null) {
                let findItem = _.find(this.inboundProductsLists, (e) => e.inbound_product_id === item.inbound_product_id)
                
                if (findItem !== 'undefined' && findItem !== undefined) {
                    if (this.index === -1) {
                        if (item.carton_count > findItem.remaining_carton_count) {
                            return false
                        } else {
                            return true
                        }
                    } else {
                        if (item.carton_count > findItem.carton) {
                            return false
                        } else {
                            return true
                        }
                    }
                } else {
                    return true
                }
            } else {
                return true
            }
        },        
        getExceededUnitCount(item) {
            if (item.inbound_product_id !== '' && item.inbound_product_id !== null) {
                let findItem = _.find(this.inboundProductsLists, (e) => e.inbound_product_id === item.inbound_product_id)
                
                if (findItem !== 'undefined' && findItem !== undefined) {
                    if (this.index === -1) {
                        if (item.total_unit > findItem.remaining_total_unit) {
                            return false
                        } else{
                            return true
                        }
                    } else {
                        if (item.total_unit > findItem.units) {
                            return false
                        } else {
                            return true
                        }
                    }
                } else {
                    return true
                }
            } else {
                return true
            }
        },
    },
    mounted() {},
    updated() {
        if (typeof this.editedItemStorableUnits !== 'undefined' && this.index > -1) {
            if (this.storableProducts !== this.editedItemStorableUnits.products) {
                this.storableProducts = this.editedItemStorableUnits.products
            }
        }
    },
}
</script>

<style lang="scss">
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/inventory/inbound/storableUnitDialog.scss';
</style>
