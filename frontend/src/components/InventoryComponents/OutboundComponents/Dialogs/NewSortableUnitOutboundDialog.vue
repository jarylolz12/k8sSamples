<template>
    <v-dialog v-model="dialogNew" max-width="1020px" content-class="storable-dialog" :retain-focus="false" persistent scrollable>
        <v-card class="storable-dialog-card">            
            <v-card-title>
                    <span class="headline">{{ formTitle }}</span>

                <button icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </button>
            </v-card-title>

            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation action="#" @submit.prevent="">
                    <div class="storable-unit-info">
                        <div class="left-column-wrapper">
                            <div class="storable-info">
                                <!-- <div class="storable-action mb-3">
                                    <p class="storable-title">ACTION</p>
                                    <v-select
                                        class="text-fields select-items"
                                        :items="storableAction"
                                        v-model="editedItemStorableUnits.action"
                                        item-text="name"
                                        item-value="value"
                                        placeholder="Select Action"
                                        outlined
                                        hide-details="auto">
                                    </v-select>
                                </div> -->

                                <div class="storable-label mb-3">
                                    <p class="storable-title">TYPE</p>
                                    <v-select
                                        class="text-fields select-items"
                                        :items="storableType"
                                        v-model="editedItemStorableUnits.type"
                                        item-text="name"
                                        :rules="[v => !!v || 'Type is required.']"
                                        item-value="value"
                                        placeholder="Select Type"
                                        outlined
                                        hide-details="auto"
                                        :menu-props="{ bottom: true, offsetY: true }">
                                    </v-select>
                                    
                                </div>

                                <div class="storable-dimension mb-3">
                                    <p class="storable-title">CARTON DIMENSIONS</p>
                                    <div class="input-container">
                                        <v-text-field
                                            background-color="white"
                                            height="40px"
                                            color="#002F44"
                                            v-model="editedItemStorableUnits.dimension.l"
                                            dense
                                            class="text-fields storable-length mr-1"
                                            outlined
                                            min="0"
                                            type="number"
                                            prefix="L"
                                            placeholder="0"
                                            hide-details="auto"
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
                                            min="0"
                                            type="number"
                                            prefix="W"
                                            placeholder="0"
                                            hide-details="auto"
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
                                            min="0"
                                            type="number"
                                            prefix="H"
                                            placeholder="0"
                                            hide-details="auto"
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
                                            hide-details="auto">
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
                                            :min="minValue"
                                            placeholder="Enter weight"
                                            type="number"
                                            hide-details="auto"
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
                                            hide-details="auto">
                                        </v-select>
                                    </div>
                                </div>   
                                <div v-if="index == -1" class="storable-dimension mb-3">
                                    <p class="storable-title">Copies (Optional)</p>
                                    <div class="input-container">
                                        <v-text-field
                                            background-color="white"
                                            height="40px"
                                            color="#002F44"
                                            v-model="editedItemStorableUnits.copies"
                                            dense
                                            min="1"
                                            :rules="copiesRule"
                                            class="text-fields storable-height mr-1"
                                            outlined
                                            placeholder="Enter weight"
                                            type="number"
                                            hide-details="auto"
                                            @keydown="restrictValues($event)"
                                            @wheel="$event.target.blur()">
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
                                    <!-- <template v-slot:[`item.outbound_product_id`]="{ item,index }"> -->
                                        <!-- <div v-if="productsData && !isMobile" class="product-selection">
                                            <v-select           
                                                class="text-fields select-product shrink"
                                                :items="filterProductLists"
                                                item-text="name"
                                                item-value="outbound_product_id"
                                                placeholder="Select Product"
                                                outlined
                                                :cache-items="cache_items"
                                                :item-disabled="disabledITems"
                                                :rules="[v => !!v || 'Product is required.']"
                                                v-model="storableProducts[index].outbound_product_id"
                                                :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                hide-details="auto"
                                                @change="getSelectedProductShippingUnit(item)">
                                                <template v-slot:selection="{ item, index }">
                                                    <div class="v-select__selection v-select__selection--comma" :key="index">
                                                        #{{ item.outbound_id }} {{ item.name }}
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
                                        <div class="product-mobile-wrapper" v-if="isMobile && productsData">
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
                                                :cache-items="cache_items"
                                                :item-disabled="disabledITems"
                                                item-text="name"
                                                item-value="outbound_product_id"
                                                placeholder="Select Product"
                                                outlined
                                                :rules="[v => !!v || 'Product is required.']"
                                                v-model="item.outbound_product_id"
                                                :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                hide-details="auto"
                                                @change="getSelectedProductShippingUnit(item)"
                                                >
                                                <template v-slot:selection="{ item, index }">
                                                    <div class="v-select__selection v-select__selection--comma" :key="index">
                                                        #{{ item.outbound_id }} {{ item.name }}
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

                                    <template v-slot:[`item.carton_count`]="{ item }">
                                        <div class="item-carton-count-details">
                                            <v-tooltip left :content-class="getItemCountClass(item) ? 'has-selected right-arrow' : 'has-none'">
                                                <template v-slot:activator="{ on }">    
                                                            
                                                    <v-text-field
                                                        v-on:input="fillCartonUnit(item, $event)"
                                                        v-on="on"
                                                        :placeholder="item.shipping_unit == 'single_item' ? '--' : '0'"
                                                        id="carton-id"
                                                        type="number" 
                                                        outlined
                                                        :min="minValue"
                                                        v-model="item.carton_count"               
                                                        class="text-fields table-text-fields icc-carton-count shrink"
                                                        hide-details="auto"
                                                        :rules="item.shipping_unit !== 'single_item' ? cartonCountRule :[]"
                                                        :disabled="ShippingUnitRuleCHeck_for_Disable(item.shipping_unit)"
                                                        @keydown="restrictValues($event)"
                                                        @wheel="$event.target.blur()">
                                                    </v-text-field> -->
                                                    <!-- item.shipping_unit === 'single_item' -->
                                                    <!-- :rules="item.shipping_unit !== 'single_item' ? cartonCountRule :[]" -->
                                                <!-- </template>

                                                <span>{{ getItemCount(item, 'carton') }}</span>
                                            </v-tooltip>
                                        </div>
                                    </template>

                                    <template v-slot:[`item.total_unit`]="{ item }">
                                        <div class="item-total-unit-details">
                                            <v-tooltip left :content-class="getItemCountClass(item) ? 'has-selected right-arrow' : 'has-none'">
                                                <template v-slot:activator="{ on }">                                              
                                                    <v-text-field 
                                                        v-on="on"
                                                        placeholder="0" 
                                                        type="number" 
                                                        outlined 
                                                        :rules="[GetError_Value_exceed(item), UnitCountRule.required]"
                                                        :min="minValue"
                                                        v-model="item.total_unit"
                                                        class="text-fields table-text-fields itu-total-unit shrink"                          
                                                        hide-details="auto"
                                                        @keydown="restrictValues($event)"
                                                        @wheel="$event.target.blur()"
                                                        validate-on-blur
                                                        @blur="() => $refs.form.resetValidation()"
                                                        >
                                                    </v-text-field>
                                                </template>

                                                <span>{{ getItemCount(item, 'units') }}</span>
                                            </v-tooltip> -->
                                        <!-- <span class="error-message" style="font-size: 12px; color: red;">{{GetError_Value_exceed(item)}}</span> -->
                                        <!-- </div>
                                    </template>

                                    <template v-slot:[`item.actions`]="{ item }">
                                        <v-btn
                                            v-show="storableProducts.length > 1"
                                            icon
                                            class="btn remove-btn"
                                            @click="removeRow(item)">
                                            <v-icon>mdi-close</v-icon>
                                        </v-btn> -->
                                    <!-- </template> -->
                                    <template v-slot:item="{ item, index }">
                                        <tr class="storable-row-data">
                                            <td class="storable-col-data">
                                                <div v-if="productsData && !isMobile" class="product-selection">
                                                    <v-select           
                                                class="text-fields select-product shrink"
                                                :items="filterProductLists"
                                                item-text="name"
                                                item-value="outbound_product_id"
                                                placeholder="Select Product"
                                                outlined
                                                :cache-items="cache_items"
                                                :item-disabled="disabledITems"
                                                :rules="[v => !!v || 'Product is required.']"
                                                v-model="storableProducts[index].outbound_product_id"
                                                :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                hide-details="auto"
                                                @change="getSelectedProductShippingUnit(item)">
                                                <template v-slot:selection="{ item, index }">
                                                    <div class="v-select__selection v-select__selection--comma" :key="index">
                                                        #{{ item.outbound_id }} {{ item.name }}
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
                                        <div class="product-mobile-wrapper" v-if="isMobile && productsData">
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
                                                :cache-items="cache_items"
                                                :item-disabled="disabledITems"
                                                item-text="name"
                                                item-value="outbound_product_id"
                                                placeholder="Select Product"
                                                outlined
                                                :rules="[v => !!v || 'Product is required.']"
                                                v-model="item.outbound_product_id"
                                                :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                hide-details="auto"
                                                @change="getSelectedProductShippingUnit(item)"
                                                >
                                                <template v-slot:selection="{ item, index }">
                                                    <div class="v-select__selection v-select__selection--comma" :key="index">
                                                        #{{ item.outbound_id }} {{ item.name }}
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
                                            <v-tooltip left :content-class="getItemCountClass(item) ? 'has-selected right-arrow' : 'has-none'">
                                                <template v-slot:activator="{ on }">    
                                                            
                                                    <v-text-field
                                                        v-on:input="fillCartonUnit(item, $event)"
                                                        v-on="on"
                                                        :placeholder="item.shipping_unit == 'single_item' ? '--' : '0'"
                                                        id="carton-id"
                                                        type="number" 
                                                        outlined
                                                        :min="minValue"
                                                        v-model="item.carton_count"               
                                                        class="text-fields table-text-fields icc-carton-count shrink"
                                                        hide-details="auto"
                                                        :rules="item.shipping_unit !== 'single_item' ? [GetError_Value_exceed_Carton(item),cartonCountRule.greater,cartonCountRule.required] :[]"
                                                        :disabled="ShippingUnitRuleCHeck_for_Disable(item.shipping_unit)"
                                                        @keydown="restrictValues($event)"
                                                        @wheel="$event.target.blur()">
                                                    </v-text-field>
                                                    <!-- item.shipping_unit === 'single_item' -->
                                                    <!-- :rules="item.shipping_unit !== 'single_item' ? cartonCountRule :[]" -->
                                                </template>

                                                <span>{{ getItemCount(item, 'carton') }}</span>
                                            </v-tooltip>
                                        </div>
                                        </td>
                                        <td class="storable-col-data">
                                            <div class="item-total-unit-details">
                                            <v-tooltip left :content-class="getItemCountClass(item) ? 'has-selected right-arrow' : 'has-none'">
                                                <template v-slot:activator="{ on }">                                              
                                                    <v-text-field 
                                                        v-on="on"
                                                        placeholder="0" 
                                                        type="number" 
                                                        outlined 
                                                        :rules="item.shipping_unit !== 'single_item' ?[GetError_Value_exceed(item), UnitCountRule.required,UnitCountRule.greater]:[]"
                                                        min="1"
                                                        v-model="item.total_unit"
                                                        class="text-fields table-text-fields itu-total-unit shrink"                          
                                                        hide-details="auto"
                                                        @keydown="restrictValues($event)"
                                                        @wheel="$event.target.blur()"
                                                        >
                                                    </v-text-field>
                                                </template>

                                                <span>{{ getItemCount(item, 'units') }}</span>
                                            </v-tooltip>
                                        <!-- <span class="error-message" style="font-size: 12px; color: red;">{{GetError_Value_exceed(item)}}</span> -->
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

                                        <tr v-if="!GetError_Value_exceed_Carton(item) || !GetError_Value_exceed(item)">
                                            <td colspan="12" class="error-unit-carton-count">
                                                {{ !GetError_Value_exceed_Carton(item) || !GetError_Value_exceed(item) ? 
                                                    'Product does not have enough carton or unit' 
                                                    : ''
                                                }}
                                            </td>
                                        </tr>
                                    </template>

                                </v-data-table>

                                <div class="add-row-wrapper">
                                    <button :disabled="showItems.length==0" class="btn-white add-btn" @click="addRow">+ Add Product</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </v-form>
            </v-card-text>

            <v-card-actions class="storable-button-actions">
                <button :disabled="!valid || getNewStorableUnitLoading || getstorableUnitUpdateLoading" 
                    class="btn-blue" v-if="index === -1" text @click="build">
                    <span v-if="getNewStorableUnitLoading"> Building... </span>
                    <span v-if="!getNewStorableUnitLoading"> Build </span>
                </button>
                    <button  class="btn-blue" text @click="build" v-if="index > -1" 
                    :disabled="getNewStorableUnitLoading || getstorableUnitUpdateLoading">
                    <span v-if="getstorableUnitUpdateLoading"> Saving... </span>
                    <span v-if="!getstorableUnitUpdateLoading"> Save </span>
                </button>

                <button class="btn-white" text @click="close" 
                    :disabled="getNewStorableUnitLoading || getstorableUnitUpdateLoading">
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
    name: 'NewSortableUnitOutboundDialog',
    props: ['dialog', 'editedIndex', 'categoryLists', 'isMobile',
            'editedItem', 'defaultItem', 'productsData','index'],
    components: {
    },
    data: () => ({
        minValue:0,
        valid: true,
        cache_items:true,
        hide_selected:true,
           linkData:{
            wid:"",
            oid:""
        },
        headers: [
			{
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'outbound_product_id',
				fixed: true,
				width: "40%"
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
            outbound_product_id: '',
            carton_count: '',
            total_unit: '',
            shipping_unit: ''
        }],
        storableItems: {
            type: "",
            copies:1,
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
        cartonCountRule:{
                required: v => !!v || 'Carton is required',
                greater:  v => (parseFloat(v)>=0) || 'Carton should be minimum 0'
        },
         weightRule:[ v => (parseFloat(v)>=0) || 'minimum value is 0'],
         copiesRule: [
            v => !!v || 'Copies is required',
            v => (parseFloat(v)>0) || 'Copies should be greater than 0',
        ],
        UnitCountRule:{
                   required: v => !!v || 'Unit is required',
                   greater:  v => (parseFloat(v)>0) || 'Unit should be greater than 0'

           },
          hasProductDuplicates: false,
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getNewStorableUnitLoading: 'outbound/getNewStorableUnitLoading',
            getstorableUnitUpdateLoading:'outbound/getstorableUnitUpdateLoading',
            getSingleOutbound:"outbound/getSingleOutbound"
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
                let values = { ...this.editedItem }

                if (this.editedItem !== 'undefined') {
                    if (this.index > -1) {
                        if (values.products.length > 0) {
                            values.products.map(v => {
                                if (v.shipping_unit === 'single_item') {
                                    v.carton_count = ''
                                    //  v.carton_count 
                                }
                            })
                        }
                    }
                }

                return values
            },
            set(value) {
                this.$emit('update:editedItem', value)
            }
        },    
        inboundProductsLists() {
            let products = []

            if (this.productsData !== 'undefined' && this.productsData !== null && this.getSingleOutbound !==null ) {
                if (this.productsData.outbound_products !== 'undefined' && this.productsData.outbound_products !== null && this.productsData.outbound_products.length > 0) {
                   
                   let items = this.productsData.outbound_products.filter(val=>{
                       return val.status == 'picked'
                    })
                    
                    items.map(v => {
                        products.push({
                            outbound_product_id: v.id,
                            name: v.product.name,
                            sku: v.product.sku,
                            carton: v.carton_count  !==null && v.carton_count !=='' ? v.carton_count:0,
                            units: v.total_unit !==null && v.total_unit !=='' ? v.total_unit:0,
                            remaining_carton_count: v.remaining_carton_count,
                            remaining_total_unit: v.remaining_total_unit,
                            shipping_unit: v.shipping_unit,
                            outbound_id:v.outbound_id,
                            image:v.product.image
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
                            if ( item.remaining_total_unit !== 0) {
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
                lists = this.inboundProductsLists
            }
            return lists
        },
        showItems(){
           return this.filterProductLists.filter(ele=>{
               let found = this.storableProducts.find(o =>o.outbound_product_id ==ele.outbound_product_id)
               if(!found){
                   return true
               }else{
                   return false
               }
           })
        },
        addNewStorableUnitTemplate() {
            let {  type, dimension, weight,copies} = this.editedItemStorableUnits

            return {
                type,
                copies,
                dimension: JSON.stringify(dimension),
                weight: JSON.stringify(weight),
                products: this.storableProducts,
                customer_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : '',
                warehouse_id: this.linkData.wid,
                outbound_id: this.linkData.oid
            }
        }
    },
    methods: {
        ...mapActions({
            newStorableUnitOutbound: 'outbound/newStorableUnitOutbound',
            updateStorableUnit:'outbound/updateStorableUnit',
            fetchSingleOutbound: 'outbound/fetchSingleOutbound',
        }),
        ...globalMethods,
        addRow() {
            let getItem = this.storableProducts

            getItem.push({
                outbound_product_id: '',
                carton_count: '',
                total_unit: '',
                shipping_unit:''
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
        removeRow(item) {
            let getIndex = this.storableProducts.indexOf(item)
            this.storableProducts.splice(getIndex, 1)
        },
        close() {
            this.$refs.form.resetValidation()
            this.storableProducts = [{
                outbound_product_id: '',
                carton_count: '',
                total_unit: '',
                shipping_unit:''
            }]

            this.editedItemStorableUnits = {
                type: "",
                customer_id: null,
                copies:1,
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
            }

            this.$emit('close')
        },
        async build() {
            if(!this.$refs.form.validate()) return
            if (this.index === -1) {
            try {
                await this.newStorableUnitOutbound(this.addNewStorableUnitTemplate)
                this.notificationMessage(`Storable Unit has been created successfully.`)
                this.close()
                  this.$refs.form.resetValidation()
                let payload = { wid: this.linkData.wid, oid: this.linkData.oid }
                await this.fetchSingleOutbound(payload)
                
            } catch (e) {
                this.notificationError(e)
            }
            }else {                    
                    try {
                        let editStorableUnit = this.addNewStorableUnitTemplate
                        editStorableUnit.storable_id = this.editedItemStorableUnits.storable_id
                        editStorableUnit.label = this.editedItemStorableUnits.label
                        // console.log("send data",this.addNewStorableUnitTemplate)
                        await this.updateStorableUnit(this.addNewStorableUnitTemplate)
                        this.notificationMessage(`Storable Unit has been updated.`)
                        this.close()
                        let payload = { wid: this.linkData.wid, oid: this.linkData.oid }
                        await this.fetchSingleOutbound(payload)
                    } catch (e) {
                        this.notificationError(e)
                    }
                }
        },
        inputClick(e, item) {
            if (item.outbound_product_id !== '' && item.outbound_product_id !== null) {
                this.showTooltip = true
            } else {
                this.showTooltip - false
            }
        },
        getItemCount(item, forItem) {
            if (item.outbound_product_id !== '' && item.outbound_product_id !== null ) {
                let findItem = _.find(this.inboundProductsLists, (e) => e.outbound_product_id === item.outbound_product_id)
               
                if (findItem !== 'undefined' && findItem !== undefined) {
                    if (forItem === 'carton') {
                        return `Available ${findItem.remaining_carton_count} Cartons`
                    } else if (forItem === 'units') {
                        return `Available ${findItem.remaining_total_unit} Units`
                    }
                }
            }
        },
        getItemCountClass(item) {
            if (item.outbound_product_id !== '' && item.outbound_product_id !== null)              
                return true
            else
                return false
        },
        fillCartonUnit(item, value) {
            let findItem = _.find(this.productsData.outbound_products, (e) => e.id === item.outbound_product_id)

            if (findItem !== undefined) {
                item.total_unit = value * findItem.in_each_carton
            }
        },
        getSelectedProductShippingUnit(item) {
            if (item.outbound_product_id !== "") {
                let findInboundItem = _.find(this.inboundProductsLists, e => e.outbound_product_id === item.outbound_product_id)
                if (findInboundItem !== 'undefined') {
                    let shippingUnit = findInboundItem.shipping_unit
                    item.shipping_unit = shippingUnit
                }
            }
        },
         isProductSelected(item, index) {
            if (item.outbound_product_id !== "") {
                let findSelectedOption = _.findIndex(
                    this.storableProducts, e => (
                        (e.outbound_product_id == item.outbound_product_id) && (e.shipping_unit == item.shipping_unit)
                    ))

                if (findSelectedOption !== index) {
                    this.hasProductDuplicates = true
                    return 'This product has already been selected.'
                } else {
                    this.hasProductDuplicates = false
                }
            }
        },
        ShippingUnitRuleCHeck_for_Disable(shipping_unit){
            return shipping_unit === 'single_item'
        },
        GetError_Value_exceed(item){
                if (item.outbound_product_id !== '' && item.outbound_product_id !== null ) {
                let findItem = _.find(this.inboundProductsLists, (e) => e.outbound_product_id === item.outbound_product_id)
               
                if (findItem !== 'undefined' && findItem !== undefined ) {
                     if (this.index === -1) {
                        if(item.total_unit>findItem.remaining_total_unit){
                            return false
                        }else{
                            return true
                        }
                     }else{
                        if(item.total_unit>findItem.units){
                            return false
                        }else{
                            return true
                        }
                     }
                    }
                    else{
                        return true
                    }
                }else{
                    return true
                }
            },
            GetError_Value_exceed_Carton(item){
                if (item.outbound_product_id !== '' && item.outbound_product_id !== null ) {
                let findItem = _.find(this.inboundProductsLists, (e) => e.outbound_product_id === item.outbound_product_id)
               
                if (findItem !== 'undefined' && findItem !== undefined) {
                     if (this.index === -1) {
                        if( item.carton_count>findItem.remaining_carton_count){
                            return false
                        }else{
                            return true
                        }
                     }else{
                        if (item.carton_count > findItem.carton) {
                            return false
                        }else{
                            return true
                        }
                     }
                        
                    }
                    else{
                        return true
                    }
                }else{
                    return true
                }
            },
            disabledITems(item){
             let findInboundItem = _.find(this.storableProducts, e => e.outbound_product_id === item.outbound_product_id)
             if (findInboundItem !== 'undefined' && findInboundItem !==undefined) {
            return (item.outbound_product_id ==findInboundItem.outbound_product_id)
             }
            },
            restrictValues(e) {

            if(e.key=='-' && e.keyCode==189 || e.key=='+' && e.keyCode==187 ) {
                e.preventDefault()
            }
        },
    },
 mounted() {
         let wid = new URL(location.href).searchParams.get('wid')
         let oid = new URL(location.href).searchParams.get('id')
         this.linkData = { oid, wid } 
    },
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
@import '@/assets/scss/pages_scss/inventory/outbound/storableUnitModel.scss';
.myBox {
    width: 6px;
    height: 6px;
    border: 1px solid #B4CFE0 ;
    background-color: #B4CFE0;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
