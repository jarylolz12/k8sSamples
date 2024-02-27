<template>
    <v-dialog v-model="dialogNew" max-width="980px" content-class="place-storable-dialog" :retain-focus="false" persistent scrollable>
        <v-card class="place-storable-dialog-card" v-if="!isMultiple">            
            <v-card-title>
                <span class="headline">Place Storable Unit</span>

                <button icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </button>
            </v-card-title>

            <v-card-text>
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="place-storable-unit-info">
                        <div class="left-column-wrapper">
                            <div class="storable-info">
                                <div class="storable-action mb-3">
                                    <p class="place-storable-title">LOCATION</p>
                                    <v-autocomplete
                                        class="text-fields select-items"
                                        :items="allWarehouseLocations"
                                        @change="updateStorableData"
                                        item-text="name"
                                        item-value="id"
                                        placeholder="Select Location"
                                        outlined
                                        hide-details="auto"
                                        v-model="placeStorable.location_id"
                                        :rules="rules"
                                        :menu-props="{ contentClass: 'place-storable-dialog-items', bottom: true, offsetY: true } ">

                                        <template v-slot:item="{ item }">
                                            <div class="option-items">
                                                <div class="name-item">
                                                    {{ item.name }}
                                                </div>

                                                <div class="count-item" v-if="item.storable_count !== 0">
                                                    {{ item.storable_count }} item<span v-if="item.storable_count > 1">s</span>
                                                </div>
                                            </div>
                                        </template>

                                        <template v-slot:no-data>
                                            <div class="option-items loading" v-if="fetchLocationsListsLoading"
                                                style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
                                                <div class="sku-item">
                                                    <v-progress-circular
                                                        :size="40"
                                                        color="#0171a1"
                                                        indeterminate>
                                                    </v-progress-circular>
                                                </div>
                                            </div>

                                            <div class="option-items no-data" v-if="!fetchLocationsListsLoading"
                                                style="min-height: 40px; padding: 12px; font-size: 14px; border-bottom: 1px solid #EBF2F5;">
                                                <div class="sku-item">
                                                    No available data
                                                </div>
                                            </div>
                                        </template>
                                    </v-autocomplete>
                                </div>

                                <div class="storable-label mb-3">
                                    <p class="place-storable-title">LABEL</p>
                                    <p class="place-storable-data mb-0">{{ placeStorableUnitItems.label !== '' && placeStorableUnitItems.label !== null ? placeStorableUnitItems.label : '--' }}</p>
                                </div>

                                <div class="storable-type mb-3">
                                    <p class="place-storable-title">TYPE</p>
                                    <p class="place-storable-data mb-0" style="text-transform: capitalize;">{{ placeStorableUnitItems.type }}</p>
                                </div>

                                <div class="storable-dimension mb-3">
                                    <p class="place-storable-title">DIMENSION</p>
                                    <p class="place-storable-data mb-0">
                                        {{ placeStorableUnitItems.dimension.l }}x{{ placeStorableUnitItems.dimension.w }}x{{ placeStorableUnitItems.dimension.h }}
                                        {{ placeStorableUnitItems.dimension.uom }}
                                    </p>
                                </div>

                                <div class="storable-weight mb-3">
                                    <p class="place-storable-title">WEIGHT</p>
                                    <p class="place-storable-data mb-0">{{ placeStorableUnitItems.weight.value }} {{ placeStorableUnitItems.weight.unit }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="right-column-wrapper">
                            <div class="place-unit-table-wrapper">
                                <v-data-table
                                    :headers="headers"
                                    :items="placeStorableUnitItems.products"
                                    class="elevation-1 products-storable-table"
                                    mobile-breakpoint="769"
                                    hide-default-footer>

                                    <template v-slot:[`item.sku`]="{ item }">
                                        <p class="mb-0">{{ item.sku !== '' ? item.sku : '--'}}</p>
                                    </template>

                                    <template v-slot:[`item.name`]="{ item }">
                                        <p class="mb-0">{{ typeof item.name !== 'undefined' && item.name !== '' ? item.name : '--'}}</p>
                                    </template>

                                    <template v-slot:[`item.carton_count`]="{ item }">
                                        <p class="mb-0" v-if="item.shipping_unit === 'carton'">
                                            {{ item.carton_count !== '' ? item.carton_count : '--'}}
                                        </p>

                                        <p class="mb-0" v-if="item.shipping_unit === 'single_item'">
                                            --
                                        </p>
                                    </template>

                                    <template v-slot:[`item.total_unit`]="{ item }">
                                        <p class="mb-0">{{ item.total_unit !== '' ? item.total_unit : '--'}}</p>
                                    </template>
                                </v-data-table>
                            </div>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="storable-button-actions">
                <button class="btn-blue" text @click="checkMarkingStorableUnit" :disabled="getPlaceStorableLoading">
                    <span v-if="getPlaceStorableLoading"> Marking... </span>
                    <span v-if="!getPlaceStorableLoading"> Mark Placed </span>
                </button>

                <button class="btn-white" text @click="close" :disabled="getPlaceStorableLoading">
                    Cancel
                </button>
            </v-card-actions>            
        </v-card>

        <v-card class="place-storable-dialog-card" v-if="isMultiple">            
            <v-card-title>
                <span class="headline">Place Storable Unit</span>

                <button icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </button>
            </v-card-title>

            <v-card-text>
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="place-storable-unit-info">
                        <div class="left-column-wrapper">
                            <div class="storable-info">
                                <div class="storable-action mb-3">
                                    <p class="place-storable-title">LOCATION</p>
                                    <v-autocomplete
                                        class="text-fields select-items"
                                        :items="allWarehouseLocations"
                                        @change="updateStorableData"
                                        item-text="name"
                                        item-value="id"
                                        placeholder="Select Location"
                                        outlined
                                        hide-details="auto"
                                        v-model="placeStorable.location_id"
                                        :rules="rules"
                                        :menu-props="{ contentClass: 'place-storable-dialog-items', bottom: true, offsetY: true } ">

                                    <template v-slot:item="{ item }">
                                            <div class="option-items">
                                                <div class="name-item">
                                                    {{ item.name }}
                                                </div>

                                                <div class="count-item" v-if="item.storable_count !== 0">
                                                    {{ item.storable_count }} item<span v-if="item.storable_count > 1">s</span>
                                                </div>
                                            </div>
                                        </template>
                                    </v-autocomplete>
                                </div>

                                <div class="storable-label mb-3">
                                    <p class="place-storable-title">STORABLE UNITS</p>
                                    <p class="mb-0">
                                        {{ placeStorableUnitItems.length }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="right-column-wrapper">
                            <div class="place-unit-table-wrapper" 
                                v-for="(storable, index) in placeStorableUnitItems" :key="index">
                                <v-row>
                                    <v-col cols="12" sm="3" class="pb-0">
                                        <div class="storable-label">
                                            <p class="place-storable-title">LABEL</p>
                                            <p class="mb-0">
                                                {{ storable.label !== '' && storable.label !== null ? storable.label : '--' }}
                                            </p>
                                        </div>
                                    </v-col>

                                    <v-col cols="12" sm="3" class="pb-0">
                                        <div class="storable-label">
                                            <p class="place-storable-title">TYPE</p>
                                            <p class="mb-0" style="text-transform: capitalize;">
                                                {{ storable.type }}
                                            </p>
                                        </div>
                                    </v-col>

                                    <v-col cols="12" sm="3" class="pb-0">
                                        <div class="storable-label">
                                            <p class="place-storable-title">DIMENSION</p>
                                            <p class="mb-0">
                                                {{ storable.dimension.l }}x
                                                {{ storable.dimension.w }}x
                                                {{ storable.dimension.h }}
                                                {{ storable.dimension.uom }}
                                            </p>
                                        </div>
                                    </v-col>

                                    <v-col cols="12" sm="3" class="pb-0">
                                        <div class="storable-label">
                                            <p class="place-storable-title">WEIGHT</p>
                                            <p class="mb-0">
                                                {{ storable.weight.value }} {{ storable.weight.unit }}
                                            </p>
                                        </div>
                                    </v-col>
                                </v-row>

                                <v-row class="mb-4">
                                    <v-col cols="12" sm="12">
                                        <v-data-table
                                            :headers="headers"
                                            :items="storable.products"
                                            class="elevation-1 products-storable-table"
                                            mobile-breakpoint="769"
                                            hide-default-footer>

                                            <template v-slot:[`item.sku`]="{ item }">
                                                <p class="mb-0">{{ item.sku !== '' ? item.sku : '--'}}</p>
                                            </template>

                                            <template v-slot:[`item.name`]="{ item }">
                                                <p class="mb-0">{{ typeof item.name !== 'undefined' && item.name !== '' ? item.name : '--'}}</p>
                                            </template>

                                            <template v-slot:[`item.carton_count`]="{ item }">
                                                <p class="mb-0" v-if="item.shipping_unit === 'carton'">
                                                    {{ item.carton_count !== '' ? item.carton_count : '--'}}
                                                </p>

                                                <p class="mb-0" v-if="item.shipping_unit === 'single_item'">
                                                    --
                                                </p>
                                            </template>

                                            <template v-slot:[`item.total_unit`]="{ item }">
                                                <p class="mb-0">{{ item.total_unit !== '' ? item.total_unit : '--'}}</p>
                                            </template>
                                        </v-data-table>
                                    </v-col>
                                </v-row>
                            </div>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="storable-button-actions">
                <button class="btn-blue" text @click="checkMarkingStorableUnit" :disabled="getPlaceMultipleStorableLoading">
                    <span v-if="getPlaceMultipleStorableLoading"> Marking... </span>
                    <span v-if="!getPlaceMultipleStorableLoading"> Mark Placed </span>
                </button>

                <button class="btn-white" text @click="close" :disabled="getPlaceMultipleStorableLoading">
                    Cancel
                </button>
            </v-card-actions>            
        </v-card>

        <PlaceStorableWarningDialog
            :dialogData.sync="dialogPlaceWarning"
            :storableData.sync="placeStorableUnitItems"
            @confirmPlacement="markPlacedStorable"
            @close="closeWarning" />
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import globalMethods from '../../../../utils/globalMethods';
import _ from 'lodash'
import PlaceStorableWarningDialog from './PlaceStorableWarningDialog.vue'

export default {
    name: 'PlaceStorableUnitDialog',
    props: ['dialog', 'editedIndex', 'categoryLists', 'isMobile', 'placeStorableUnitItems', 'defaultItem', 
        'productsData', 'linkData', 'isMultiple', 'inboundStatus', 'locationsLists', 'fetchLocationsListsLoading'],
    components: {
        PlaceStorableWarningDialog
    },
    data: () => ({
        valid: true,
        headers: [
			{
				text: 'SKU',
				align: 'start',
				sortable: false,
				value: 'sku',
				fixed: true,
				width: "20%"
			},
            {
				text: 'PRODUCT',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "50%"
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
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "15%"
			}
		],
        placeStorable: {
            location_id: null
        },
        rules: [
            (v) => !!v || "Location is required."
        ],
        dialogPlaceWarning: false,
        storableData: null,
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getNewStorableUnitLoading: 'inbound/getNewStorableUnitLoading',
            getAllWarehouseLocations: 'inbound/getAllWarehouseLocations',
            getPlaceStorableLoading: 'inbound/getPlaceStorableLoading',
            getPlaceMultipleStorableLoading: 'inbound/getPlaceMultipleStorableLoading',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
        }),
        dialogNew: {
            get() {
                return this.dialog
            },
            set(value) {
                this.$emit('update:dialog', value)
            }
        },
        allWarehouseLocations: {
            get() {
                return this.locationsLists
            }, 
            set(value) {
                this.$emit('update:locationsLists', value)
            }
        },
        placeStorableTemplate() {
            let { location_id } = this.placeStorable

            return {
                warehouse_id: this.placeStorableUnitItems.warehouse_id,
                inbound_id: this.placeStorableUnitItems.inbound_id,
                storable_id: this.placeStorableUnitItems.id,
                location_id
            }
        },
        placeMultipleStorableTemplate() {
            let { location_id } = this.placeStorable
            let storable_units = []

            if (this.placeStorableUnitItems.length !== 'undefined' && this.placeStorableUnitItems.length > 0) {
                this.placeStorableUnitItems.map(v => {
                    storable_units.push(v.id)
                })
            }

            return {
                warehouse_id: this.linkData.warehouse_id,
                inbound_id: this.linkData.inbound_id,
                storable_units,
                location_id
            }
        }
    },
    methods: {
        ...mapActions({ 
            setPlaceStorableUnit: 'inbound/setPlaceStorableUnit',
            fetchSingleInbound: 'inbound/fetchSingleInbound',
            getPlaceStorableLocations: 'inbound/getPlaceStorableLocations',
            setPlaceMultipleStorableUnit: 'inbound/setPlaceMultipleStorableUnit',
            fetchFloorInbounds: 'inbound/fetchFloorInbounds',
            fetchCompletedInbounds: 'inbound/fetchCompletedInbounds',
            fetchPendingReceivingInbounds: 'inbound/fetchPendingReceivingInbounds',
        }),
        ...globalMethods,
        close() {
            this.$emit('close')
            this.$refs.form.resetValidation()
            this.placeStorable.location_id = null
        },
        checkMarkingStorableUnit() {
            if (this.storableData !== null && this.storableData.storable_count > 0) {
                this.dialogPlaceWarning = true
            } else {
                this.markPlacedStorable()
            }
        },
        async callFloorOrCompletedInbound(id) {
            let dataWithPage = { id, page: 1 }

            if (this.inboundStatus === 'floor') {
                await this.fetchFloorInbounds(dataWithPage)
            } else if (this.inboundStatus === 'completed') {
                await this.fetchFloorInbounds(dataWithPage)
                await this.fetchCompletedInbounds(dataWithPage)
            } else if (this.inboundStatus === 'receive-pending') {
                await this.fetchPendingReceivingInbounds(dataWithPage)
            }
        },
        async markPlacedStorable() {
            this.$refs.form.validate()

            if (this.$refs.form.validate) {
                try {
                    if (!this.isMultiple) {
                        if (this.placeStorableTemplate.location_id !== null) {
                            await this.setPlaceStorableUnit(this.placeStorableTemplate)
                            this.notificationMessage('Storable Unit has been placed.')
                            this.close()                 

                            let payload = { wid: this.placeStorableUnitItems.warehouse_id, iid: this.placeStorableUnitItems.inbound_id }
                            await this.fetchSingleInbound(payload)
                            await this.getPlaceStorableLocations(this.placeStorableUnitItems.warehouse_id)
                            this.callFloorOrCompletedInbound(this.placeStorableUnitItems.warehouse_id)
                        }
                    } else {
                        if (this.placeStorableTemplate.location_id !== null) {
                            await this.setPlaceMultipleStorableUnit(this.placeMultipleStorableTemplate)
                            this.notificationMessage('Storable Units has been placed.')
                            this.close()                 

                            let payload = { 
                                wid: this.placeMultipleStorableTemplate.warehouse_id, 
                                iid: this.placeMultipleStorableTemplate.inbound_id 
                            }
                            await this.fetchSingleInbound(payload)
                            this.$emit('clearSelection')

                            await this.getPlaceStorableLocations(this.placeMultipleStorableTemplate.warehouse_id)
                            this.callFloorOrCompletedInbound(this.placeStorableUnitItems.warehouse_id)
                        }
                    }                    
                } catch(e) {
                    this.notificationError(e)
                } 
            }
        }, 
        updateStorableData(item) {
            if (typeof this.allWarehouseLocations !== 'undefined' && this.allWarehouseLocations !== null &&
                Array.isArray(this.allWarehouseLocations) && this.allWarehouseLocations.length > 0) {
                let findLocation = _.find(this.allWarehouseLocations, e => (item === e.id))
                this.storableData = findLocation
            }
        },
        closeWarning() {
            this.dialogPlaceWarning = false
            this.storableData = null
        },
    },
    mounted() {},
    updated() {},
}
</script>

<style lang="scss">
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/inventory/inbound/placeStorableDialog.scss';
</style>
