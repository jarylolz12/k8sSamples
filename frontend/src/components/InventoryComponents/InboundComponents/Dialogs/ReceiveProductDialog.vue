<template>
    <v-dialog v-model="dialog" max-width="1120px" content-class="receive-dialog" :retain-focus="false" persistent v-resize="onResize" scrollable>
        <v-card>            
            <v-card-title>
                <span class="headline"> {{ formTitle }} </span>
                <v-spacer></v-spacer>
                <v-btn icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="receive-info-wrapper">
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="receive-info">
                        <div class="receive-second-column">
                            <div class="receive-products-section-wrapper">
                                <div class="receive-products-tables-wrapper">
                                    <!-- :headers="!isWarehouse3PL ? headers : headers3pl" -->
                                    <v-data-table
                                        :headers="headers3pl"
                                        :items="receiveProductItems"
                                        class="elevation-1 receive-products-table"
                                        mobile-breakpoint="769"
                                        hide-default-footer>
                                        
                                        <!-- <template v-slot:[`item.product_id`]="{ item }">
                                            <div class="product-selection" v-if="!isMobile">
                                                <v-select
                                                    :items="productLists"
                                                    class="select-product shrink"
                                                    item-text="name"
                                                    item-value="product_id"
                                                    placeholder="Select Product"
                                                    outlined 
                                                    v-model="item.product_id"
                                                    :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                                    hide-details="auto">
                                                </v-select>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.carton_count`]="{ item }">
                                            <div>
                                                <v-text-field 
                                                    :placeholder="item.shipping_unit == 'single_item' ? '--' : '0'" 
                                                    type="number"
                                                    @input="isCartonCountError(item)"
                                                    :class="(isShowCartonError && hasCartonError) ? 'has-error' : ''"
                                                    outlined 
                                                    class="table-text-fields itu-total-unit shrink"                          
                                                    hide-details="auto"
                                                    v-model="item.carton_count"
                                                    :rules="item.shipping_unit === 'single_item' ? [] : unitRules"
                                                    :disabled="item.shipping_unit === 'single_item'"
                                                    v-on:input="fillCartonUnit(item, $event)"
                                                    min="1"
                                                    @keydown="restrictValues($event)"
                                                    @wheel="$event.target.blur()">
                                                </v-text-field>

                                                <span class="error-message" style="font-size: 11px; color: red;" v-if="isShowCartonError">
                                                    {{ cartonErrorMessage }}
                                                </span>

                                                <span class="error-message" style="font-size: 11px; color: white;" v-if="(isShowTotalUnitError && hasTotalUnitError)">
                                                    .
                                                </span>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.in_each_carton`]="{ item }">
                                            <div>
                                                <v-text-field 
                                                    :placeholder="item.shipping_unit === 'single_item' ? '--' : '0'" 
                                                    type="number" 
                                                    outlined 
                                                    class="table-text-fields itu-total-unit shrink"                          
                                                    hide-details="auto"
                                                    v-model="item.in_each_carton"
                                                    :rules="item.shipping_unit === 'single_item' ? [] : unitRules"
                                                    :disabled="item.shipping_unit === 'single_item'"
                                                    min="1"
                                                    @keydown="restrictValues($event)"
                                                    @wheel="$event.target.blur()">
                                                </v-text-field>

                                                <span class="error-message" style="font-size: 11px; color: white;"
                                                v-if="(isShowTotalUnitError && hasTotalUnitError) || 
                                                (isShowCartonError && hasCartonError)">
                                                    .
                                                </span>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.total_unit`]="{ item }">
                                            <div>
                                                <v-text-field 
                                                    placeholder="0" 
                                                    type="number" 
                                                    outlined 
                                                    class="table-text-fields itu-total-unit shrink"
                                                    @input="isTotalUnitError(item)"
                                                    :class="(isShowTotalUnitError && hasTotalUnitError) ? 'has-error' : ''"              
                                                    hide-details="auto"
                                                    v-model="item.total_unit"
                                                    :rules="unitRules"
                                                    min="1"
                                                    @keydown="restrictValues($event)"
                                                    @wheel="$event.target.blur()">
                                                </v-text-field>

                                                <span class="error-message" style="font-size: 11px; color: red;" v-if="isShowTotalUnitError">
                                                    {{ totalUnitErrorMessage }}
                                                </span>

                                                <span class="error-message" style="font-size: 11px; color: white;" v-if="(isShowCartonError && hasCartonError)">
                                                    .
                                                </span>
                                            </div>
                                        </template>
                                        
                                        <template v-slot:[`item.notes`]="{ item }">
                                            <div>
                                                <v-text-field 
                                                    placeholder="Type Note" 
                                                    type="test" 
                                                    outlined 
                                                    class="table-text-fields input-notes shrink"  
                                                    :class="item.noteError ? 'has-error' : ''"                 
                                                    hide-details="auto"
                                                    v-model="item.notes">
                                                </v-text-field>

                                                <span class="error-message" style="font-size: 11px; color: white;"
                                                v-if="(isShowTotalUnitError && hasTotalUnitError) || 
                                                (isShowCartonError && hasCartonError)">
                                                    .
                                                </span>
                                            </div>
                                        </template> -->
                                        
                                        <template v-slot:item="{ item }">
                                            <tr class="receive-products-row" v-if="!isMobile">
                                                <td class="receive-products-col">
                                                    <div class="product-selection">
                                                        {{ item.product_sku }}
                                                    </div>
                                                </td>

                                                <td class="receive-products-col">
                                                    <div class="product-selection">
                                                        {{ item.name }}
                                                    </div>
                                                </td>

                                                <td class="receive-products-col">
                                                    <div>
                                                        <v-text-field 
                                                            :placeholder="item.shipping_unit == 'single_item' ? '--' : '0'" 
                                                            type="number"
                                                            @input="isCartonCountError(item)"
                                                            :class="(isShowCartonError && hasCartonError) ? 'has-error' : ''"
                                                            outlined 
                                                            class="table-text-fields itu-total-unit shrink"                          
                                                            hide-details="auto"
                                                            v-model="item.carton_count"
                                                            :rules="item.shipping_unit === 'single_item' ? [] : unitRules"
                                                            :disabled="item.shipping_unit === 'single_item'"
                                                            v-on:input="fillCartonUnit(item, $event)"
                                                            min="1"
                                                            @keydown="restrictValues($event)"
                                                            @wheel="$event.target.blur()">
                                                        </v-text-field>

                                                        <span class="error-message" 
                                                            style="font-size: 11px; color: red;" 
                                                            v-if="isShowCartonError">
                                                            {{ cartonErrorMessage }}
                                                        </span>

                                                        <span class="error-message" 
                                                            style="font-size: 11px; color: white;" 
                                                            v-if="(isShowTotalUnitError && hasTotalUnitError)">
                                                            .
                                                        </span>
                                                    </div>
                                                </td>

                                                <td class="receive-products-col">
                                                    <div>
                                                        <v-text-field 
                                                            :placeholder="item.shipping_unit === 'single_item' ? '--' : '0'" 
                                                            type="number" 
                                                            outlined 
                                                            class="table-text-fields itu-total-unit shrink"                          
                                                            hide-details="auto"
                                                            v-model="item.in_each_carton"
                                                            :rules="item.shipping_unit === 'single_item' ? [] : unitRules"
                                                            :disabled="item.shipping_unit === 'single_item'"
                                                            min="1"
                                                            v-on:input="fillCartonUnit(item, $event)"
                                                            @keydown="restrictValues($event)"
                                                            @wheel="$event.target.blur()">
                                                        </v-text-field>

                                                        <span class="error-message" 
                                                            style="font-size: 11px; color: white;"
                                                            v-if="(isShowTotalUnitError && hasTotalUnitError) || 
                                                            (isShowCartonError && hasCartonError)">
                                                            .
                                                        </span>
                                                    </div>
                                                </td>

                                                <td class="receive-products-col">
                                                    <div>
                                                        <v-text-field 
                                                            placeholder="0" 
                                                            type="number" 
                                                            outlined 
                                                            class="table-text-fields itu-total-unit shrink"
                                                            @input="isTotalUnitError(item)"
                                                            :class="(isShowTotalUnitError && hasTotalUnitError) ? 'has-error' : ''"              
                                                            hide-details="auto"
                                                            v-model="item.total_unit"
                                                            :rules="unitRules"
                                                            min="1"
                                                            @keydown="restrictValues($event)"
                                                            @wheel="$event.target.blur()">
                                                        </v-text-field>

                                                        <span class="error-message" 
                                                            style="font-size: 11px; color: red;" v-if="isShowTotalUnitError">
                                                            {{ totalUnitErrorMessage }}
                                                        </span>

                                                        <span class="error-message" 
                                                            style="font-size: 11px; color: white;" 
                                                            v-if="(isShowCartonError && hasCartonError)">
                                                            .
                                                        </span>
                                                    </div>
                                                </td>

                                                <td class="receive-products-col">
                                                    <div>
                                                        <v-text-field 
                                                            placeholder="Type Note" 
                                                            type="test" 
                                                            outlined 
                                                            class="table-text-fields input-notes shrink"  
                                                            :class="item.noteError ? 'has-error' : ''"                 
                                                            hide-details="auto"
                                                            v-model="item.notes">
                                                        </v-text-field>

                                                        <span class="error-message" 
                                                            style="font-size: 11px; color: white;"
                                                            v-if="(isShowTotalUnitError && hasTotalUnitError) || 
                                                            (isShowCartonError && hasCartonError)">
                                                            .
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr class="receive-products-row mobile" v-if="isMobile">
                                                <td class="receive-products-col">
                                                    <div class="product-selection-mobile">
                                                        <div class="product-mobile-header">
                                                            <p>SKU</p>
                                                        </div>

                                                        {{ item.product_sku }}
                                                    </div>
                                                </td>

                                                <td class="receive-products-col product-name-col-wrapper">
                                                    <div class="product-name-wrapper">
                                                        <td class="receive-products-col-inside">
                                                            <div class="product-selection-mobile">
                                                                <div class="product-mobile-header">
                                                                    <p>Product</p>
                                                                </div>

                                                                {{ item.name }}
                                                            </div>
                                                        </td>
                                                    </div>
                                                </td>

                                                <td class="receive-products-col product-name-col-wrapper">
                                                    <div class="product-name-wrapper">
                                                        <td class="receive-products-col-inside">
                                                            <div class="product-selection-mobile mr-1">
                                                                <div class="product-mobile-header">
                                                                    <p>Carton</p>
                                                                </div>

                                                                <div>
                                                                    <v-text-field 
                                                                        :placeholder="item.shipping_unit == 'single_item' ? '--' : '0'" 
                                                                        type="number"
                                                                        @input="isCartonCountError(item)"
                                                                        :class="(isShowCartonError && hasCartonError) ? 'has-error' : ''"
                                                                        outlined 
                                                                        class="table-text-fields itu-total-unit shrink"                          
                                                                        hide-details="auto"
                                                                        v-model="item.carton_count"
                                                                        :rules="item.shipping_unit === 'single_item' ? [] : unitRules"
                                                                        :disabled="item.shipping_unit === 'single_item'"
                                                                        v-on:input="fillCartonUnit(item, $event)"
                                                                        min="1"
                                                                        @keydown="restrictValues($event)"
                                                                        @wheel="$event.target.blur()">
                                                                    </v-text-field>

                                                                    <span class="error-message" 
                                                                        style="font-size: 11px; color: red;" 
                                                                        v-if="isShowCartonError">
                                                                        {{ cartonErrorMessage }}
                                                                    </span>

                                                                    <span class="error-message" 
                                                                        style="font-size: 11px; color: white;" 
                                                                        v-if="(isShowTotalUnitError && hasTotalUnitError)">
                                                                        .
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="product-selection-mobile mr-1">
                                                                <div class="product-mobile-header">
                                                                    <p>In Each</p>
                                                                </div>

                                                                <div>
                                                                    <v-text-field 
                                                                        :placeholder="item.shipping_unit === 'single_item' ? '--' : '0'" 
                                                                        type="number" 
                                                                        outlined 
                                                                        class="table-text-fields itu-total-unit shrink"                          
                                                                        hide-details="auto"
                                                                        v-model="item.in_each_carton"
                                                                        :rules="item.shipping_unit === 'single_item' ? [] : unitRules"
                                                                        :disabled="item.shipping_unit === 'single_item'"
                                                                        min="1"
                                                                        @keydown="restrictValues($event)"
                                                                        @wheel="$event.target.blur()">
                                                                    </v-text-field>

                                                                    <span class="error-message" 
                                                                        style="font-size: 11px; color: white;"
                                                                        v-if="(isShowTotalUnitError && hasTotalUnitError) || 
                                                                        (isShowCartonError && hasCartonError)">
                                                                        .
                                                                    </span>
                                                                </div>
                                                            </div>                                                            

                                                            <div class="product-selection-mobile">
                                                                <div class="product-mobile-header">
                                                                    <p>Unit</p>
                                                                </div>

                                                                <v-text-field 
                                                                    placeholder="0" 
                                                                    type="number" 
                                                                    outlined 
                                                                    class="table-text-fields itu-total-unit shrink"
                                                                    @input="isTotalUnitError(item)"
                                                                    :class="(isShowTotalUnitError && hasTotalUnitError) ? 'has-error' : ''"              
                                                                    hide-details="auto"
                                                                    v-model="item.total_unit"
                                                                    :rules="unitRules"
                                                                    min="1"
                                                                    @keydown="restrictValues($event)"
                                                                    @wheel="$event.target.blur()">
                                                                </v-text-field>

                                                                <span class="error-message" 
                                                                    style="font-size: 11px; color: red;" v-if="isShowTotalUnitError">
                                                                    {{ totalUnitErrorMessage }}
                                                                </span>

                                                                <span class="error-message" 
                                                                    style="font-size: 11px; color: white;" 
                                                                    v-if="(isShowCartonError && hasCartonError)">
                                                                    .
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </div>
                                                </td>

                                                <td class="receive-products-col">
                                                    <div class="product-selection-mobile">
                                                        <div class="product-mobile-header">
                                                            <p>NOTES</p>
                                                        </div>

                                                        <div>
                                                            <v-text-field 
                                                                placeholder="Type Note" 
                                                                type="test" 
                                                                outlined 
                                                                class="table-text-fields input-notes shrink"  
                                                                :class="item.noteError ? 'has-error' : ''"                 
                                                                hide-details="auto"
                                                                v-model="item.notes">
                                                            </v-text-field>

                                                            <span class="error-message" 
                                                                style="font-size: 11px; color: white;"
                                                                v-if="(isShowTotalUnitError && hasTotalUnitError) || 
                                                                (isShowCartonError && hasCartonError)">
                                                                .
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </v-data-table>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="receive-button-actions">
                <button class="btn-blue" 
                :disabled="(getReceiveProductLoading || getReceiveProductLoading3pl)" 
                @click.stop="receiveProductAction" 
                v-if="!multiple">
                    <span v-if="!isWarehouse3PL ? !getReceiveProductLoading : !getReceiveProductLoading3pl">
                        {{
                            title === 'receive' ? 'Receive' : 'Update'
                        }}
                    </span>
                    <span v-if="!isWarehouse3PL ? getReceiveProductLoading : getReceiveProductLoading3pl">
                        {{
                            title === 'receive' ? 'Receiving...' : 'Updating...'
                        }}
                    </span>
                </button>

                <button class="btn-blue" 
                :disabled="getReceiveProductMultipleLoading || getReceiveProductMultipleLoading3pl" 
                @click.stop="receiveProductAction" 
                v-if="multiple">
                    <span v-if="!isWarehouse3PL ? !getReceiveProductMultipleLoading : !getReceiveProductMultipleLoading3pl">
                        Receive
                    </span>
                    <span v-if="!isWarehouse3PL ? getReceiveProductMultipleLoading : getReceiveProductMultipleLoading3pl">
                        Receiving...
                    </span>
                </button>

                <button class="btn-white" 
                :disabled="getReceiveProductLoading || getReceiveProductMultipleLoading || getReceiveProductLoading3pl || getReceiveProductMultipleLoading3pl" 
                text @click="close">
                    Cancel
                </button>
            </v-card-actions>

            <!-- <v-card-actions class="receive-button-actions">
                <button class="btn-blue" :disabled="getReceiveProductLoading" @click.stop="receiveProductAction" v-if="!multiple">
                    <span v-if="!getReceiveProductLoading">
                        {{
                            title === 'receive' ? 'Receive' : 'Update'
                        }}
                    </span>
                    <span v-if="getReceiveProductLoading">
                        {{
                            title === 'receive' ? 'Receiving...' : 'Updating...'
                        }}
                    </span>
                </button>

                <button class="btn-blue" :disabled="getReceiveProductMultipleLoading" @click.stop="receiveProductAction" v-if="multiple">
                    <span v-if="!getReceiveProductMultipleLoading">Receive</span>
                    <span v-if="getReceiveProductMultipleLoading">Receiving...</span>
                </button>

                <button class="btn-white" :disabled="getReceiveProductLoading || getReceiveProductMultipleLoading" text @click="close">
                    Cancel
                </button>
            </v-card-actions> -->
        </v-card>

        <UpdateReceiveProductErrorDialog 
            :dialogData.sync="dialogErrorUpdateCount"
            @close="closeWarning"
            @discardChanges="discardChanges" />
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
// import moment from 'moment'
import globalMethods from '../../../../utils/globalMethods'
import UpdateReceiveProductErrorDialog from './UpdateReceiveProductErrorDialog.vue'

export default {
    name: 'ReceiveProductDialog',
    props: [
        'dialogData', 
        'editedItemData', 
        'loading', 
        'productLists', 
        'linkData', 
        'multiple', 
        'title', 
        'inboundStatus', 
        'isWarehouse3PL',
        'undershipped',
        'isWarehouse3PLProvider'
    ],
    components: {
        UpdateReceiveProductErrorDialog
    },
    data: () => ({
        current_page: 1,
        isMobile: false,
        valid: true,
        headers: [
            {
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'product_sku',
				fixed: true,
				width: "10%"
			},
			{
				text: 'PRODUCT',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "25%"
			},
			{
				text: 'CARTON',
				align: 'end',
				sortable: false,
				value: 'carton_count',
				fixed: true,
				width: "15%"
			},
			{
				text: 'IN EACH',
				align: 'end',
				sortable: false,
				value: 'in_each_carton',
				fixed: true,
				width: "15%"
			},
            {
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "15%"
			},
		],
        headers3pl: [
            {
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'product_sku',
				fixed: true,
				width: "10%"
			},
			{
				text: 'PRODUCT',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "24%"
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
				value: 'total_unit',
				fixed: true,
				width: "12%"
			},
            {
				text: 'NOTE',
				align: 'start',
				sortable: false,
				value: 'notes',
				fixed: true,
				width: "20%"
			},
		],
        rules: [
            (v) => !!v || "Input is required."
        ],
        unitRules: [
            v => !!v || 'Input is required',
            v => (parseFloat(v) > 0) || 'Value should be greater than 0'
        ],
        hasCartonError: false,
        hasTotalUnitError: false,
        dialogErrorUpdateCount: false,
        cartonErrorMessage: '',
        isShowCartonError: false,
        totalUnitErrorMessage: '',
        isShowTotalUnitError: false,
        noteError: false
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
            getReceiveProductLoading: 'inbound/getReceiveProductLoading',
            getReceiveProductMultipleLoading: 'inbound/getReceiveProductMultipleLoading',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
            // 3pl
            getReceiveProductLoading3pl: 'inbound/getReceiveProductLoading3pl',
            getReceiveProductMultipleLoading3pl: 'inbound/getReceiveProductMultipleLoading3pl'
        }),
        dialog: {
            get() {
                return this.dialogData
            },
            set(value) {
                this.$emit('update:dialogData', value)
            }
        },
        receiveProductItems: {
            get() {
                let values = this.editedItemData

                // if (this.editedItemData !== 'undefined') {
                //     if (this.title === 'update' && this.inboundStatus === 'floor') {
                //         values.map(v => {
                //             v.carton_count = v.actual_carton_count
                //             v.total_unit = v.actual_total_unit
                //         })
                //     }
                // } 

                if (this.editedItemData !== 'undefined') {
                    if (this.title === 'update') {
                        if (!this.isWarehouse3PL) {
                            if (this.inboundStatus === 'floor') {
                                values.map(v => {
                                    v.carton_count = v.actual_carton_count === 0 ? null : v.actual_carton_count
                                    v.total_unit = v.actual_total_unit
                                })
                            }
                        } else {
                            if (this.inboundStatus === 'pending' || this.inboundStatus === 'completed') {
                                values.map(v => {
                                    v.carton_count = v.actual_carton_count === 0 ? null : v.actual_carton_count
                                    v.total_unit = v.actual_total_unit
                                })
                            }
                        }
                    }
                }
                
                return values
            },
            set(value) {
                this.$emit('update:editedItemData', value)
            }
        },
        formTitle() {
            if (this.title === 'receive') {
                return 'Receive Product'
            } else {
                return 'Update Receive Count'
            }
        }
    },
    methods: {
        ...mapActions({
            createInbound: 'inbound/createInbound',
            updateInbound: 'inbound/updateInbound',
            fetchInboundInventories: 'inbound/fetchInboundInventories',
            fetchInventories: 'inventory/fetchInventories',
            receiveProduct: 'inbound/receiveProduct',
            fetchSingleInbound: 'inbound/fetchSingleInbound',
            receiveProductMultiple: 'inbound/receiveProductMultiple',
            fetchPendingInbounds: 'inbound/fetchPendingInbounds',
            fetchFloorInbounds: 'inbound/fetchFloorInbounds', 
            fetchCompletedInbounds: 'inbound/fetchCompletedInbounds',           
            // 3pl
            receiveProduct3pl: 'inbound/receiveProduct3pl',
            receiveProductMultiple3pl: 'inbound/receiveProductMultiple3pl',
            fetchPendingReceivingInbounds: 'inbound/fetchPendingReceivingInbounds',
        }),
        ...globalMethods,
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
        close() {
            this.$emit('close')
            this.hasCartonError = false
            this.hasTotalUnitError = false
            this.isShowCartonError = false
            this.isShowTotalUnitError = false
            this.cartonErrorMessage = ""
            this.totalUnitErrorMessage = ""
            this.$emit('clearSelection')
            this.noteError = false
        },
        fillCartonUnit(item) {
            if (item !== null) {
                let cartonCount = parseInt(item.carton_count)
                item.total_unit = cartonCount * item.in_each_carton
            }
        },
        isCartonCountError(item) {
            item.carton_count = item.carton_count !== null ? parseInt(item.carton_count) : 0

            if (item.carton_count !== null && item.carton_count !== '') {
                let updated_carton_count = parseInt(item.carton_count)
                let total_allocated_carton_count = item.actual_carton_count - item.remaining_carton_count

                if (item.storable_units.length > 0) {
                    if (updated_carton_count < total_allocated_carton_count) {
                        this.hasCartonError = true
                        this.cartonErrorMessage = `Can't be below ${total_allocated_carton_count}`
                    } else {
                        this.hasCartonError = false
                        this.cartonErrorMessage = ""
                    }
                } else {
                    this.hasCartonError = false
                    this.cartonErrorMessage = ""
                }
            }
        },
        isTotalUnitError(item) {
            item.total_unit = item.total_unit !== null ? parseInt(item.total_unit) : 0

            if (item.total_unit !== null && item.total_unit !== '') {
                let updated_total_unit = parseInt(item.total_unit)
                let total_allocated_total_unit = item.actual_total_unit - item.remaining_total_unit

                if (item.storable_units.length > 0) {
                    if (updated_total_unit < total_allocated_total_unit) {
                        this.hasTotalUnitError = true
                        this.totalUnitErrorMessage = `Can't be below ${total_allocated_total_unit}`
                    } else {
                        this.hasTotalUnitError = false
                        this.totalUnitErrorMessage = ""
                    }
                } else {
                    this.hasTotalUnitError = false
                    this.totalUnitErrorMessage = ''
                }
            }
        },
        isNoteError(item) {
            if (item !== null) {
                if (item.shipping_unit === 'carton') {
                    if ((item.carton_count !== null && item.carton_count !== 0) || 
                        (item.total_unit !== null && item.total_unit !== 0)) {
                            
                        if ((item.total_unit < item.expected_total_unit) || 
                            (item.carton_count < item.expected_carton_count)) {
                            if (item.notes === '') {
                                item.noteError = true
                                this.noteError = true
                            } else {
                                item.noteError = false
                                this.noteError = false
                            }

                        } else if ((item.total_unit > item.expected_total_unit) || 
                            (item.carton_count > item.expected_carton_count)) {
                            if (item.notes === '') {
                                item.noteError = true
                                this.noteError = true
                            } else {
                                item.noteError = false
                                this.noteError = false
                            }

                        } else if ((item.total_unit === item.expected_total_unit) &&
                            (item.carton_count === item.expected_carton_count)) {
                            item.noteError = false
                            this.noteError = false
                        }
                    }
                } else {
                    if (item.total_unit !== null && item.total_unit !== 0) {
                        if (item.total_unit < item.expected_total_unit) {
                            if (item.notes === '') {
                                item.noteError = true
                                this.noteError = true
                            } else {
                                item.noteError = false
                                this.noteError = false
                            }
                        } else if (item.total_unit > item.expected_total_unit) {
                            if (item.notes === '') {
                                item.noteError = true
                                this.noteError = true
                            } else {
                                item.noteError = false
                                this.noteError = false
                            }
                        } else if (item.total_unit === item.expected_total_unit) {
                            item.noteError = false
                            this.noteError = false
                        }
                    }
                }
            }
        },
        async callPendingInbound() {
            let storeInboundTab = this.$store.state.inbound
            let dataWithPage = { id: this.linkData.warehouse_id, page: 1 }

            if (!this.isWarehouse3PL) {                
                if (this.isWarehouse3PLProvider) {
                    await this.fetchPendingReceivingInbounds(dataWithPage)
                } else {
                    await this.fetchFloorInbounds(dataWithPage)
                }
            } else {
                await this.fetchCompletedInbounds(dataWithPage)
            }

            dataWithPage.page = storeInboundTab.pendingInboundPagination.current_page                            
            await this.fetchPendingInbounds(dataWithPage)
        },
        async receiveProductAction() {
            this.$refs.form.validate()

            if (this.$refs.form.validate()) {
                if (!this.hasCartonError && !this.hasTotalUnitError) {
                    if (!this.multiple) {
                        if (this.receiveProductItems.length !== 'undefined' && this.receiveProductItems.length > 0) {
                            try {
                                let item = {
                                    warehouse_id: this.linkData.warehouse_id !== '' ? this.linkData.warehouse_id : null,
                                    inbound_id: this.linkData.inbound_id !== '' ? this.linkData.inbound_id : null,
                                    inbound_product_id: this.receiveProductItems[0].id,
                                    carton_count: this.receiveProductItems[0].carton_count,
                                    in_each_carton: this.receiveProductItems[0].in_each_carton,
                                    total_unit: this.receiveProductItems[0].total_unit,
                                    notes: this.receiveProductItems[0].notes,
                                }

                                let fetchSingleInboundPayload = { 
                                    wid: this.linkData.warehouse_id, 
                                    iid: this.linkData.inbound_id 
                                }

                                if (!this.isWarehouse3PL) {
                                    await this.receiveProduct(item)
                                    let message = this.title === 'receive' ? 'Product has been received.' : 
                                        'Product count has been updated.'
                                    this.notificationMessage(message)
                                    await this.fetchSingleInbound(fetchSingleInboundPayload)
                                    this.close()                                
                                    this.callPendingInbound()
                                } else {
                                    await this.receiveProduct3pl(item)
                                    let message = this.title === 'receive' ? 
                                        'Product has been received.' : 'Product count has been updated.'
                                    this.notificationMessage(message)
                                    await this.fetchSingleInbound(fetchSingleInboundPayload)
                                    this.close()                                    
                                    this.callPendingInbound()
                                }

                                // let message = this.title === 'receive' ? 'Product has been received.' : 'Product count has been updated.'
                                // this.notificationMessage(message)
                                // await this.fetchSingleInbound(fetchSingleInboundPayload)
                                // this.close()
                                
                                // this.callPendingInbound()
                            } catch(e) {
                                this.notificationError(e)
                                this.close()
                            }
                        }
                    } else {
                        if (this.receiveProductItems.length !== 'undefined' && this.receiveProductItems.length > 0) {
                            try {
                                let willReceiveMultipleProd = this.receiveProductItems.map(v => {
                                    return {
                                        inbound_product_id: v.id,
                                        carton_count: v.carton_count,
                                        in_each_carton: v.in_each_carton,
                                        total_unit: v.total_unit,
                                        notes: v.notes
                                    }
                                })

                                let payload = {
                                    warehouse_id: this.linkData.warehouse_id !== '' ? this.linkData.warehouse_id : null,
                                    inbound_id: this.linkData.inbound_id !== '' ? this.linkData.inbound_id : null,
                                    products: willReceiveMultipleProd
                                }

                                let fetchSingleInboundPayload = { 
                                    wid: this.linkData.warehouse_id, 
                                    iid: this.linkData.inbound_id 
                                }

                                if (!this.isWarehouse3PL) {
                                    await this.receiveProductMultiple(payload)
                                    this.notificationMessage('Products has been received.')
                                    this.$emit('clearSelection')
                                    this.close()
                                    await this.fetchSingleInbound(fetchSingleInboundPayload)
                                    this.callPendingInbound()
                                } else {                                
                                    await this.receiveProductMultiple3pl(payload)
                                    this.notificationMessage('Products has been received.')
                                    this.$emit('clearSelection')
                                    this.close()
                                    await this.fetchSingleInbound(fetchSingleInboundPayload)
                                    this.callPendingInbound()
                                }

                                // this.notificationMessage('Products has been received.')
                                // this.$emit('clearSelection')
                                // this.close()
                                // await this.fetchSingleInbound(fetchSingleInboundPayload)
                                // this.callPendingInbound()
                            } catch(e) {
                                this.notificationError(e)
                                this.close()
                            }
                        }
                    }
                } else {
                    this.dialogErrorUpdateCount = true
                }  
            }                   
        },
        closeWarning() {
            this.dialogErrorUpdateCount = false

            if (this.hasCartonError) {
                this.isShowCartonError = true
            } else {
                this.isShowCartonError = false
            }

            if (this.hasTotalUnitError) {
                this.isShowTotalUnitError = true
            } else {
                this.isShowTotalUnitError = false
            }
        },
        discardChanges() {
            if (this.receiveProductItems[0] !== 'undefined') {
                this.receiveProductItems[0].carton_count = this.receiveProductItems[0].actual_carton_count
                this.receiveProductItems[0].total_unit = this.receiveProductItems[0].actual_total_unit
                this.dialogErrorUpdateCount = false
                this.hasCartonError = false
                this.hasTotalUnitError = false
                this.isShowCartonError = false
                this.isShowTotalUnitError = false
                this.cartonErrorMessage = ""
                this.totalUnitErrorMessage = ""
            }
        }
    },
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/inbound/receiveProductDialog.scss';
</style>
