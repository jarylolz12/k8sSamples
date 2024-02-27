<template>
    <v-dialog v-model="dialog" max-width="940px" content-class="create-order-dialog" :retain-focus="false" @click:outside="close">
        <v-card>
            <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                <v-card-title>
                    <span class="headline">
                        {{ formTitle }} 
                    </span>

                    <v-spacer></v-spacer>						

                    <v-btn icon dark class="btn-close" @click="close">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text class="create-order-info-wrapper">
                    <div class="create-order-info">
                        <v-row>
                            <v-col cols="12" sm="4">
                                <div class="work-order-no">
                                    <p class="order-title">Work Order No</p>
                                    <p class="dynamic-content">
                                        {{ 
                                            outboundDataFromView !== null ? outboundDataFromView.order_id : '--'
                                        }}
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <div class="work-order-ref">
                                    <p class="order-title">Order Reference</p>
                                    <p class="dynamic-content">Outbound 
                                        {{ 
                                            outboundDataFromView !== null ? outboundDataFromView.ref_no : '--'
                                        }}
                                    </p>
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" sm="4">
                                <div class="work-order-type">
                                    <p class="order-title">Type</p>
                                    <p class="order-content">
                                        <v-select
                                            :items="typeDropdown"
                                            class="select-type text-fields"
                                            item-text="name"
                                            item-value="id"
                                            return-object
                                            placeholder="Select Type"
                                            outlined
                                            hide-details="auto">
                                        </v-select>
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <div class="work-order-no">
                                    <p class="order-title">Priority</p>
                                    <p class="order-content">
                                        <v-select
                                            :items="customerDropdown"
                                            class="select-assigned text-fields"
                                            item-text="name"
                                            item-value="id"
                                            return-object
                                            placeholder="Select Priority"
                                            outlined
                                            hide-details="auto">
                                        </v-select>
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <div class="work-order-no">
                                    <p class="order-title">Assigned To</p>
                                    <p class="order-content">
                                        <v-select
                                            :items="customerDropdown"
                                            class="select-assigned text-fields"
                                            item-text="name"
                                            item-value="id"
                                            return-object
                                            placeholder="Select Assigned"
                                            outlined
                                            hide-details="auto">
                                        </v-select>
                                    </p>
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" sm="4">
                                <div class="work-order-type">
                                    <p class="order-title">Gate</p>
                                    <p class="order-content">
                                        <v-select
                                            :items="bundleType"
                                            class="select-type text-fields"
                                            item-text="name"
                                            item-value="id"
                                            return-object
                                            placeholder="Select Gate"
                                            outlined
                                            hide-details="auto">
                                        </v-select>
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <div class="work-order-type">
                                    <p class="order-title">Item Type</p>
                                    <p class="order-content">
                                        <v-select
                                            :items="bundleType"
                                            class="select-type text-fields"
                                            item-text="name"
                                            item-value="id"
                                            return-object
                                            placeholder="Select Type"
                                            outlined
                                            hide-details="auto">
                                        </v-select>
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <div class="work-order-type">
                                    <p class="order-title">Vehicle</p>
                                    <p class="order-content">
                                        <v-select
                                            :items="bundleType"
                                            class="select-type text-fields"
                                            item-text="name"
                                            item-value="id"
                                            return-object
                                            placeholder="Select Vehicle"
                                            outlined
                                            hide-details="auto">
                                        </v-select>
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="12" sm="12">
                                <div class="work-order-type">
                                    <p class="order-title">Note <span>(Optional)</span></p>
                                    <p class="order-content">
                                        <v-textarea
                                            height="76px"
                                            color="#002F44"
                                            type="text"
                                            dense
                                            class="text-fields custom"
                                            placeholder="Type your notes here..."
                                            outlined
                                            hide-details="auto">
                                        </v-textarea>
                                    </p>
                                </div>
                            </v-col>

                            <!-- <v-col cols="12" sm="4">
                                <div class="work-order-carton">
                                    <p class="order-title">Carton</p>
                                    <p class="order-content">
                                        <v-text-field 
                                            placeholder="0" 
                                            outlined 
                                            class="text-fields text-right"
                                            hide-details="auto">
                                        </v-text-field>
                                    </p>
                                </div>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <div class="work-order-each">
                                    <p class="order-title">In Each Carton</p>
                                    <p class="order-content">
                                        <v-text-field 
                                            placeholder="0 Units" 
                                            outlined 
                                            class="text-fields text-right"
                                            hide-details="auto">
                                        </v-text-field>
                                    </p>
                                </div>
                            </v-col> -->
                        </v-row>

                        <v-row>
                            <v-col cols="12" sm="12">
                                <div class="work-order-type-bundle">
                                    <p class="order-title big">Item Details</p>
                                    <p class="order-description">Things to be loaded into the truck</p>
                                    <v-data-table
                                        :headers="bundleHeaders"
                                        :items="budleTypeProducts"
                                        class="elevation-1 add-products-bundle-table"
                                        mobile-breakpoint="769"
                                        hide-default-footer>

                                        <template v-slot:[`item.storable_unit`]="{  }">
                                            <div class="product-selection">
                                                <v-select
                                                    :items="typeDropdown"
                                                    class="select-product"
                                                    item-text="name"
                                                    item-value="id"
                                                    return-object
                                                    placeholder="Select Product"
                                                    outlined 
                                                    hide-details="auto">
                                                </v-select>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.terminal`]="{  }">
                                            <div class="product-selection">
                                                <v-select
                                                    :items="typeDropdown"
                                                    class="select-product"
                                                    item-text="name"
                                                    item-value="id"
                                                    return-object
                                                    placeholder="Select Product"
                                                    outlined 
                                                    hide-details="auto">
                                                </v-select>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.carton`]="{  }">
                                            <div class="product-selection">
                                                <v-text-field 
                                                    placeholder="0" 
                                                    type="number" 
                                                    outlined 
                                                    class="table-text-fields itu-total-unit"                          
                                                    hide-details="auto">
                                                </v-text-field>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.unit`]="{  }">
                                            <div class="product-selection">
                                                <v-text-field 
                                                    placeholder="0" 
                                                    type="number" 
                                                    outlined 
                                                    class="table-text-fields itu-total-unit"                          
                                                    hide-details="auto">
                                                </v-text-field>
                                            </div>
                                        </template>

                                        <!-- <template v-slot:[`item.total_unit`]="{  }">
                                            <div>
                                                <v-text-field 
                                                    placeholder="0" 
                                                    type="number" 
                                                    outlined 
                                                    class="table-text-fields itu-total-unit"                          
                                                    hide-details="auto">
                                                </v-text-field>
                                            </div>
                                        </template> -->

                                        <template v-slot:[`item.actions`]="{ item }">
                                            <v-btn
                                                v-show="budleTypeProducts.length > 1"
                                                icon
                                                class="btn remove-btn"
                                                @click="removeRow(item)">
                                                <v-icon>mdi-close</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>

                                    <div class="add-row-wrapper">
                                        <button class="btn-white add-btn" @click="addRow">+ Add Line</button>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>

                        <!-- <v-row>
                            <v-col cols="12" sm="12">
                                <div class="work-order-details">
                                    <p class="order-title big mt-2">Pick Details</p>
                                    <v-data-table
                                        :headers="detailsHeaders"
                                        :items="detailsProducts"
                                        class="elevation-1 add-products-bundle-table"
                                        mobile-breakpoint="769"
                                        hide-default-footer>

                                        <template v-slot:[`item.product_id`]="{  }">
                                            <div class="product-selection">
                                                <v-select
                                                    :items="typeDropdown"
                                                    class="select-product"
                                                    item-text="name"
                                                    item-value="id"
                                                    return-object
                                                    placeholder="Select Product"
                                                    outlined 
                                                    hide-details="auto">
                                                </v-select>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.pallet`]="{  }">
                                            <div class="product-selection">
                                                <v-select
                                                    :items="palletDropdown"
                                                    class="select-product"
                                                    item-text="name"
                                                    item-value="id"
                                                    return-object
                                                    placeholder="Select Pallet"
                                                    outlined 
                                                    hide-details="auto">
                                                </v-select>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.carton_count`]="{  }">
                                            <div>
                                                <v-text-field 
                                                    placeholder="0" 
                                                    type="number" 
                                                    outlined 
                                                    class="table-text-fields itu-total-unit"                          
                                                    hide-details="auto">
                                                </v-text-field>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.total_unit`]="{  }">
                                            <div>
                                                <v-text-field 
                                                    placeholder="0" 
                                                    type="number" 
                                                    outlined 
                                                    class="table-text-fields itu-total-unit"                          
                                                    hide-details="auto">
                                                </v-text-field>
                                            </div>
                                        </template>

                                        <template v-slot:[`item.actions`]="{ item }">
                                            <v-btn
                                                v-show="detailsProducts.length > 1"
                                                icon
                                                class="btn remove-btn"
                                                @click="removeDetailsRow(item)">
                                                <v-icon>mdi-close</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>

                                    <div class="add-row-wrapper">
                                        <button class="btn-white add-btn" @click="addDetailsRow">+ Add Product</button>
                                    </div>
                                </div>
                            </v-col>
                        </v-row> -->
                    </div>
                </v-card-text>

                <v-card-actions class="order-button-actions">
                    <button class="btn-blue" @click="saveOrder">
                        <span>Create</span>
                    </button>

                    <button class="btn-white" text @click="close">
                        Save & Create Another
                    </button>

                    <button class="btn-white" text @click="close">
                        Cancel
                    </button>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
// import _ from 'lodash'
// import moment from 'moment'
import globalMethods from '../../../utils/globalMethods'

export default {
    name: 'CreateWorkOrderDialog',
    props: ['dialogCreate', 'editedOrderIndex', 'editedOrderItems', 'defaultOrderItems', 'outboundData'],
    components: { },
    data: () => ({
        current_page: 1,
        valid: true,
        detailsHeaders: [
			{
				text: 'PRODUCT',
				align: 'start',
				sortable: false,
				value: 'product_id',
				fixed: true,
				width: "35%"
			},
            {
				text: 'PALLET',
				align: 'start',
				sortable: false,
				value: 'pallet',
				fixed: true,
				width: "30%"
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
        bundleHeaders: [
            {
				text: 'Storable Unit',
				align: 'start',
				sortable: false,
				value: 'storable_unit',
				fixed: true,
				width: "33%"
			},
			{
				text: 'Terminal',
				align: 'start',
				sortable: false,
				value: 'terminal',
				fixed: true,
				width: "22%"
			},
            {
				text: 'Carton',
				align: 'end',
				sortable: false,
				value: 'carton',
				fixed: true,
				width: "20%"
			},
			{
				text: 'Unit',
				align: 'end',
				sortable: false,
				value: 'unit',
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
        counter: 0,
        selected: null,
        rules: [
            (v) => !!v || "Input is required."
        ],
        detailsProducts: [
            {
                product_id: '',
                pallet: '',
                carton_count: 0,
                total_unit: 0
            }
        ],
        budleTypeProducts: [
            {
                storable_unit: '',
                terminal: 0,
                carton: 0,
                unit: 0
            }
        ],
        isCustom: 'generated',
        typeDropdown: ['Single', 'Build Bundle'],
        customerDropdown: ['John Smith', 'John Doe'],
        bundleType: ['Build Bundle', 'Own Bundle'],
        palletDropdown: ['Regular', 'Drum']
    }),
    watch: {
    },
    computed: {
        ...mapGetters({
            
        }),
        formTitle () {
            return this.editedIndex === -1 ? 'Create Work Order' : 'Edit Work Order'
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
                return this.editedOrderIndex
            },
            set(value) {
                this.$emit('update:editedOrderIndex', value)
            }
        },
        outboundDataFromView: {
            get() {
                let outboundFromView = null

                if (typeof this.outboundData !== 'undefined' && this.outboundData !== null && 
                    this.outboundData !== 'undefined' && this.outboundData.data !== undefined) {
                    outboundFromView = this.outboundData.data
                }

                return outboundFromView
            },
            set(value) {
                this.$emit('update:outboundData', value)
            }
        }
    },
    methods: {
        ...mapActions({ 
            
        }),
        ...globalMethods,
        saveOrder() {
            this.$refs.form.validate()
        },
        close() {
            this.$emit('close')
        },
        addRow() {
            let getItem = this.budleTypeProducts

            getItem.push({
                product_id: '',
                total_unit: 0
            })

            // this.outboundItems.outbound_products = getItem
        },
        removeRow(item) {
            let getIndex = this.budleTypeProducts.indexOf(item)
            this.budleTypeProducts.splice(getIndex, 1)
        },
        addDetailsRow() {
            let getItem = this.detailsProducts

            getItem.push({
                product_id: '',
                pallet: '',
                carton_count: 0,
                total_unit: 0
            })

            // this.outboundItems.outbound_products = getItem
        },
        removeDetailsRow(item) {
            let getIndex = this.detailsProducts.indexOf(item)
            this.detailsProducts.splice(getIndex, 1)
        }
    },
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/outbound/createWorkOrder.scss';
</style>
