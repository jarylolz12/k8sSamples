<template>
    <div class="inbound-pallet-section">
        <v-data-table
            :headers="palletsHeader"
            :items="storableData.inbound_storable_units"
            item-key="label"
            :items-per-page="itemsPerPage"
            class="elevation-1 inbound-view-pallets"
            v-bind:class="{
                'no-data-table': (typeof storableData.inbound_storable_units !== 'undefined' && storableData.inbound_storable_units.length !== null && storableData.inbound_storable_units.length === 0),
                'no-checkbox': !removeCheckboxIfAllIsReceived
            }"
            mobile-breakpoint="769"
            hide-default-footer
            :show-select="removeCheckboxIfAllIsReceived"
            fixed-header
            :expanded.sync="expanded"
            single-expand
            show-expand
            v-model="storableSelected">

            <template v-slot:no-data>
                <div class="no-data-wrapper" v-if="storableData.inbound_storable_units.length == 0">
                    <div class="no-data-heading">
                        <div>
                            <h3> No Storable Units </h3>
                            <p> You will be able to add storable units once the product has been received. </p>
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.label`]="{ item }">
                <div class="label-info" v-if="!isMobile">
                    <span>{{ item.label !== null ? item.label : '--'}}</span>
                    <p class="mb-0" v-if="item.action === 'recieve'">(Received)</p>
                </div>

                <div class="mobile-content-wrapper" v-if="isMobile">
                    <div class="mobile-content">
                        <span>
                            {{ item.label !== null ? item.label : '--'}}                                                  

                            <span 
                                style="text-transform: capitalize; font-family: 'Inter-Medium', sans-serif; color: #6D858F;">
                                - {{ item.type !== null ? item.type : '--'}}
                            </span>
                        </span>
                    </div>

                    <div class="actions-wrapper">
                        <button class="btn-status btn-white" v-if="item.status !== 'placed'" @click.stop="openPlaceStorableDialog(item)">
                            <span class="btn-content">
                                <span>Placed</span>
                            </span>
                        </button>

                        <button class="btn-status btn-white placed" v-if="item.status == 'placed'">
                            <img src="../../../../assets/icons/checkMark.png" class="mr-1" width="12px" height="12px">
                            <span class="btn-content">
                                <span>Placed</span>
                            </span>
                        </button>

                        <v-menu bottom left offset-y content-class="outbound-lists-menu" v-if="item.status !== 'placed'">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn class="btn-white" icon v-bind="attrs" v-on="on" :disabled="inboundStatus === 'completed'">
                                    <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists">
                                <v-list-item @click.stop="editStorableUnit(item)">
                                    <v-list-item-title>
                                        Edit
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>
                </div>
            </template>

            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">
                    <div class="expanded-item-info">
                        <div class="expanded-header-wrapper">
                            <div class="expanded-header-content">
                                <div class="header-title w-80">SKU</div>
                                <div class="header-title w-10">CARTON</div>
                                <div class="header-title w-10">UNIT</div>
                            </div>
                        </div>

                        <div class="expanded-body-wrapper">
                            <div class="expanded-body-content" 
                                v-for="(v, index) in item.inbound_storable_products" :key="index">

                                <div class="header-data w-80">
                                    #{{ v.category_sku !== '' && v.category_sku !== null ? v.category_sku : '' }} 
                                    {{  (v.inbound_product !== null && v.inbound_product.product_name !== null) ? v.inbound_product.product_name : ''}}
                                </div>
                                <div class="header-data w-10">
                                    {{ v.carton_count }}
                                </div>
                                <div class="header-data w-10">
                                    {{ v.total_unit }}
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="type-info" v-if="!isMobile">
                    <span>{{ item.type !== null ? item.type : '--'}}</span>
                </div>

                <div class="mobile-content-wrapper" v-if="isMobile">                    
                    <div class="mobile-content">                        
                        <div class="unit-weight-mobile mb-1">
                            <span>{{ getLocationName(item) }}</span>
                        </div>
                        
                        <div class="location-info-mobile">
                            <span>
                                {{ typeof item.dimension.l !== 'undefined' && item.dimension.l !== '' ? item.dimension.l : 0 }} x
                            </span>
                            <span>
                                {{ typeof item.dimension.w !== 'undefined' && item.dimension.w !== '' ? item.dimension.w : 0 }} x
                            </span>
                            <span>
                                {{ typeof item.dimension.h !=='undefined' && item.dimension.h !== '' ? item.dimension.h : 0}}
                            </span>
                            <span>{{ item.dimension.uom }}</span>
                        </div>

                        <div class="unit-weight-mobile"> {{ item.weight.value }} {{ item.weight.unit }} </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.spec`]="{ item }">
                <div class="spec-info" v-if="!isMobile">
                    <p>
                        <span>
                            {{ typeof item.dimension.l !== 'undefined' && item.dimension.l !== '' ? item.dimension.l : 0 }} x
                        </span>
                        <span>
                            {{ typeof item.dimension.w !== 'undefined' && item.dimension.w !== '' ? item.dimension.w : 0 }} x
                        </span>
                        <span>
                            {{ typeof item.dimension.h !=='undefined' && item.dimension.h !== '' ? item.dimension.h : 0}}
                        </span>
                        <span>{{ item.dimension.uom }}</span>
                    </p>

                    <p class="unit-weight"> {{ item.weight.value }} {{ item.weight.unit }} </p>
                </div>

                <div class="cartons-separator" v-if="isMobile">
                    <p>{{ item.inbound_storable_products !== null && item.inbound_storable_products.length > 0 ? item.inbound_storable_products.length : 0 }} sku</p>
                    <span class="separator"></span>
                    <p>{{ getTotalCartonsAndUnits(item, 'carton') }} cartons</p>
                    <span class="separator"></span>
                    <p> {{ getTotalCartonsAndUnits(item, 'unit') }} units </p>
                </div>
            </template>

            <template v-slot:[`item.location`]="{ item }">
                <div class="location-info">
                    <span>{{ getLocationName(item) }}</span>
                </div>
            </template>

            <template v-slot:[`item.no_of_sku`]="{ item }">
                <div class="sku-info">
                    <p>{{ item.inbound_storable_products !== null && item.inbound_storable_products.length > 0 ? item.inbound_storable_products.length : 0 }}</p>
                </div>
            </template>

            <template v-slot:[`item.carton_count`]="{ item }">
                <div class="carton-info">
                    <span>{{ getTotalCartonsAndUnits(item, 'carton') }}</span>
                </div>
            </template>

            <template v-slot:[`item.units`]="{ item }">
                <div class="units-info">
                    <span>{{ getTotalCartonsAndUnits(item, 'unit') }}</span>
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions-wrapper mr-2" v-if="!isWarehouseConnected">
                    <button class="btn-status btn-white not-placed" 
                    v-if="item.status !== 'placed'" @click.stop="openPlaceStorableDialog(item)"
                    :disabled="inboundStatus === 'cancelled'">
                        <span class="btn-content">
                            <span>Placed</span>
                        </span>
                    </button>

                    <button class="btn-status btn-white placed" v-if="item.status == 'placed'"
                    :disabled="inboundStatus === 'cancelled'">
                        <img src="../../../../assets/icons/checkMark.png" class="mr-1" width="12px" height="12px">
                        <span class="btn-content">
                            <span>Placed</span>
                        </span>
                    </button>

                    <v-menu bottom left offset-y content-class="outbound-lists-menu" v-if="item.status !== 'placed'">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn class="btn-white pallet-menu" icon v-bind="attrs" v-on="on" :disabled="inboundStatus === 'completed' || inboundStatus === 'cancelled'">
                                <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <v-list-item @click.stop="editStorableUnit(item)">
                                <v-list-item-title>
                                    Edit
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>
        </v-data-table>

        <PlaceStorableUnitDialog 
            :dialog.sync="dialogPlaceStorable"
            :placeStorableUnitItems.sync="placeStorableUnitItems"
            @close="closePlaceStorableDialog"
            :isMultiple="false"
            :linkData="linkData"
            :inboundStatus="inboundStatus"
            :locationsLists="locationsLists"
            :fetchLocationsListsLoading="fetchLocationsListsLoading"
            @clickFetchLocations="clickFetchLocations" />

        <NewStorableUnitDialog 
            :dialog.sync="dialogNewStorableUnit" 
            :editedItem.sync="storableItemsData"
            :productsData="inboundProductsData"
            :linkData="linkData"
            @close="closeStorableUnit"
            :index="index" />  
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import moment from 'moment'
import PlaceStorableUnitDialog from '../Dialogs/PlaceStorableUnitDialog.vue'
import NewStorableUnitDialog from '../Dialogs/NewStorableUnitDialog.vue'
import _ from 'lodash'

export default {
    name: 'PalletSection',
    props: [
        'inboundProductsData', 
        'productLists', 
        'linkData', 
        'isMobile', 
        'warehouse_id', 
        'selected', 
        'locationsLists', 
        'fetchLocationsListsLoading',
        'isWarehouseConnected'
    ],
    components: {
        PlaceStorableUnitDialog,
        NewStorableUnitDialog
    },
    data: () => ({
        palletsHeader: [
            {
				text: 'Label',
				align: 'start',
				sortable: false,
				value: 'label',
				fixed: true,
				width: "75px"
			},
            { text: '', value: 'data-table-expand', width: "5%"},
			{ 
				text: 'Type',
				align: 'start',
				sortable: false,
				value: 'type',
				fixed: true,
				width: "10%"
			},
            { 
				text: 'Spec',
				align: 'start',
				sortable: false,
				value: 'spec',
				fixed: true,
				width: "15%"
			},
			{ 
				text: 'LOCATION',
				align: 'start',
				sortable: false,
				value: 'location',
				fixed: true,
				width: "15%"
			},
            { 
				text: 'No of sku',
				align: 'end',
				sortable: false,
				value: 'no_of_sku',
				fixed: true,
				width: "12%"
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
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'units',
				fixed: true,
				width: "12%"
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "18%"
			},
        ],
        dialogPlaceStorable: false,
        placeStorableUnitItems: {
            location: '',
            label: '',
            type: '',
            dimension: '',
            weight: '',
            products: []
        },
        itemsPerPage: 500,
        index: 0,
        dialogNewStorableUnit: false,
        storableItemsData: {
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
            products: []
        },
        expanded: [],
    }),
    computed: {
        ...mapGetters({}),
        storableData: {
            get() {
                let newStorableData = []

                if (typeof this.inboundProductsData !== 'undefined' && this.inboundProductsData !== null) {
                    newStorableData = this.inboundProductsData
                }

                return newStorableData
            },
            set(value) {
                this.$emit('update:inboundProductsData', value)
            }
        },
        inboundStatus: {
            get() {
                let inbound_status = ''

                if (typeof this.inboundProductsData !== 'undefined' && this.inboundProductsData !== null) {
                    inbound_status = this.inboundProductsData.inbound_status
                }

                return inbound_status
            },
            set(value) {
                this.$emit('update:inboundProductsData', value)
            }
        },
        storableSelected: {
            get() {
                return this.selected
            },
            set(value) {
                this.$emit('update:selected', value)
            }
        },
        removeCheckboxIfAllIsReceived() {
            let show = false

            if (this.inboundStatus !== 'cancelled' && this.inboundStatus !== 'rejected') {
                if (!this.isWarehouseConnected) {
                    if (typeof this.storableData !== 'undefined' && this.storableData !== null) {
                        if (typeof this.storableData.inbound_storable_units !== 'undefined' && 
                            this.storableData.inbound_storable_units.length > 0) {
                            let findProductIndex = _.findIndex(this.storableData.inbound_storable_units, e => (e.status !== 'placed'))
                            if (findProductIndex > -1) {
                                show = true
                            } else {
                                show = false
                            }
                        }
                    }
                }
            }

            return show
        },
    },
    methods: {
        ...mapActions({
        }),
        formatDate(date) {
            if (date !== null) {
                return moment(date).format('h:mm MM/DD/YYYY')
            } else {
                return 'N/A'
            }
        },
        getTotalCartonsAndUnits(data, forItem) {
            let total = 0

            if (data !== 'undefined' && data !== null && data.inbound_storable_products !== 'undefined' 
                && Array.isArray(data.inbound_storable_products) && data.inbound_storable_products.length > 0) {
                let arr = data.inbound_storable_products

                if (forItem === 'carton') {
                    arr.forEach(function(value) {
                        total += value.carton_count                       
                    })
                } else if (forItem === 'unit') {
                    arr.forEach(function(value) {
                        total += value.total_unit
                    })
                }
            }

            total = total === 0 ? '--' : total

            return total
        },        
        openPlaceStorableDialog(item) {
            this.dialogPlaceStorable = true

            let tempProd = { ...item }
            let products = []

            if (item.inbound_storable_products !== 'undefined' && item.inbound_storable_products.length > 0) {
                item.inbound_storable_products.map(v => {
                    products.push({
                        inbound_storable_unit_id: v.inbound_storable_unit_id,
                        sku: v.inbound_product.sku,
                        name: v.inbound_product.product_name,
                        carton_count: v.carton_count,
                        total_unit: v.total_unit,
                        shipping_unit: v.inbound_product.shipping_unit
                    })
                })
            }

            tempProd.products = products

            this.placeStorableUnitItems = Object.assign({}, tempProd);
            this.clickFetchLocations()
        },
        closePlaceStorableDialog() {
            this.dialogPlaceStorable = false
        },
        editStorableUnit(item) {
            this.dialogNewStorableUnit = true
            this.index = _.findIndex(this.storableData.inbound_storable_units, (e) => e.id == item.id)

            let productLists = item.inbound_storable_products

            let newProductLists = productLists.map(v => {
                let newItem = {}

                newItem = {
                    inbound_product_id: v.inbound_product_id,
                    carton_count: v.carton_count,
                    total_unit: v.total_unit,
                    shipping_unit: v.inbound_product.shipping_unit
                }

                return newItem
            })

            if (item !== null) {
                this.storableItemsData = {
                    action: item.action !== null ? item.action : '',
                    type: item.type !== null ? item.type : '',
                    customer_id: item.customer_id !== null ? item.customer_id : '',
                    dimension: item.dimension,
                    weight: item.weight,
                    products: newProductLists,
                    storable_id: item.id
                }
            }
        },
        closeStorableUnit() {
            this.dialogNewStorableUnit = false
            this.storableItemsData = {
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
                products: [] 
            }
        },
        getLocationName(item) {
            let val = ''

            if (item !== 'undefined' && item !== null && item.location !== null) {
                if (item.location.type === 'storage') {
                    val = item.location.row + item.location.rack + item.location.shelf
                } else {
                    val = item.location.gate_name
                }
            } else {
                val = '--'
            }

            return val            
        },
        clickFetchLocations() {
            this.$emit('clickFetchLocations')
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "inventory");
    },
    updated() {}
}
</script>

<style lang="scss">
@import './scss/palletSection.scss';
</style>