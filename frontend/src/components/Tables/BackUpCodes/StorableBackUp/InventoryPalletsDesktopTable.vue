<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="storableUnitItems"
            :search="search"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            item-key="id"
            class="inventory-table elevation-1"
            :class="(storableUnitItems !== null && 
                    storableUnitItems.length > 0) ? '' : 'no-data-table'"
            hide-default-footer
            fixed-header
            :expanded.sync="expanded"
            single-expand
            show-expand
            show-select
            :item-class="itemRowBackground">

            <template v-slot:top>
                <div class="inventory-search-wrapper" v-if="storableUnitItems !== null && storableUnitItems.length > 0">                

                    <v-spacer></v-spacer>

                    <!-- <v-btn dark color="primary" class="btn-white btn-filters mr-2">
                        <img src="@/assets/icons/filters.svg" class="mr-1"> Filters
                    </v-btn> -->

                    <Search 
                        placeholder="Search Storable Unit"
                        className="search custom-search"
                        :inputData.sync="search" />

                    <!-- <v-btn dark color="primary" class="btn-blue ml-2">
                        Merge
                    </v-btn> -->
                </div>
                
            </template>

            <!-- FOR OPENING MULTIPLE ROWS AT A TIME -->
            <!-- <template v-slot:[`item.data-table-expand`]="{ item, index }">                
                <div class="inventory-wrapper collapse-button-wrapper inventory-blue-text">
                    <div> {{ item.label }}</div>

                    <v-btn class="button-collapse" @click="clicked(item, index)">
                        <v-icon color="primary">
                            {{ item.isExpanded ? 'mdi-chevron-up' : 'mdi-chevron-down'}}
                        </v-icon>
                    </v-btn>
                </div>
            </template> -->

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
                            <div class="expanded-body-content" v-for="(v, index) in item.storable_unit_products" :key="index">
                                <div class="header-data w-80">
                                    #{{ v.product.sku }} {{ v.product.name }}
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
                <div class="inventory-wrapper">
                    <div style="text-transform: capitalize;"> {{ item.type !== null ? item.type : '--' }}</div>
                </div>
            </template>

            <template v-slot:[`item.label`]="{ item }">
                <div class="inventory-wrapper inventory-blue-text">
                    <div> {{ item.label !== null ? item.label : '--' }}</div>
                </div>
            </template>

            <template v-slot:[`item.spec`]="{ item }">
                <div class="storable-spec-wrapper">
                    <div class="inventory-wrapper">
                        <div> {{ item.parse_dimensions.l }}x{{ item.parse_dimensions.w }}x{{ item.parse_dimensions.h }} {{ item.parse_dimensions.uom }}</div>
                    </div>

                    <div class="weight">
                        <div> {{ item.parse_weight.value }} {{ item.parse_weight.unit }} </div>
                    </div>
                </div>
            </template>
            
            <template v-slot:[`item.location`]="{ item }">
                <div class="inventory-wrapper">
                    <div> {{ item.location !== null ? item.location.shelf + item.location.row + item.location.rack : '--'}} </div>
                </div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="inventory-wrapper">
                    <div> {{ formatDate(item.updated_at) }}</div>
                </div>
            </template>

            <template v-slot:[`item.no_sku`]="{ item }">
                <span>{{ item.no_of_sku }}</span>
            </template>

            <template v-slot:[`item.total_carton_count`]="{ item }">
                <span>{{ item.total_carton_count !== null ? item.total_carton_count : 0 }}</span>
            </template>

            <template v-slot:[`item.total_units`]="{ item }">
                <span>{{ item.total_units !== null ? item.total_units : 0 }}</span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions mr-2">                    
                    <div class="actions mr-2">
                        <v-menu bottom left content-class="outbound-lists-menu">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on">
                                    <v-icon>mdi-dots-horizontal</v-icon>
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists">
                                <v-list-item @click="vieStorableUnit(item)">
                                    <v-list-item-title>
                                        View
                                    </v-list-item-title>
                                </v-list-item>

                                <!-- <v-list-item>
                                    <v-list-item-title>
                                        Merge With
                                    </v-list-item-title>
                                </v-list-item> -->
                            </v-list>
                        </v-menu>
                    </div>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="getStorableUnitsLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper" v-if="!getStorableUnitsLoading && storableUnitItems.length == 0">
                    <div class="no-data-heading">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <h3> Empty Storable Unit </h3>
                        <p>
                            This warehouse doesn’t have any storable units in inventory yet.
                        </p>
                    </div>
                </div>
            </template>
        </v-data-table>

        <Pagination 
            v-if="storableUnitItems !== 'undefined' && storableUnitItems.length > 0"
            :pageData.sync="page"
            :lengthData.sync="pageCount"
            :isMobile="isMobile"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import Search from '../../../Search.vue'
import Pagination from '../../../Pagination'
import globalMethods from '../../../../utils/globalMethods'
// import _ from 'lodash'
import moment from 'moment'

export default {
    name: 'InventoryPalletsDesktopTable',
    props: ['currentWarehouseSelected', 'isMobile'],
    components: {
        Search,
        Pagination
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        expanded: [],
        headers: [
            {
				text: 'Label',
				align: 'start',
				sortable: false,
				value: 'label',
				fixed: true,
				width: "7%"
			},
            { text: '', value: 'data-table-expand' },
			{ 
				text: 'Type',
				align: 'start',
				sortable: false,
				value: 'type',
				fixed: true,
				width: "8%"
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
				text: 'Location',
				align: 'start',
				sortable: false,
				value: 'location',
				fixed: true,
				width: "12%"
			},
			{ 
				text: 'Updated At',
				align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: "20%"
			},
			{ 
				text: 'No of SKU',
				align: 'end',
				sortable: false,
				value: 'no_of_sku',
				fixed: true,
				width: "10%"
			},
            { 
				text: 'Carton',
				align: 'end',
				sortable: false,
				value: 'total_carton_count',
				fixed: true,
				width: "8%"
			},
            { 
				text: 'Unit',
				align: 'end',
				sortable: false,
				value: 'total_units',
				fixed: true,
				width: "8%"
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "18%"
			},
		]
    }),
    computed: {
        ...mapGetters({
            getCurrentWarehouse: 'warehouse/getCurrentWarehouse',
            getStorableUnits: 'storableUnit/getStorableUnits',
            getStorableUnitsLoading: 'storableUnit/getStorableUnitsLoading'
        }),
        storableUnitItems() {
            let units = []

            if (typeof this.getStorableUnits !== 'undefined' && this.getStorableUnits !== null && 
                this.getStorableUnits.data !== 'undefined' && Array.isArray(this.getStorableUnits.data)) {

                let items = this.getStorableUnits.data

                items.map(v => {
                    let  { dimensions, weight, storable_unit_products, ...otherItems } = v
                    let parse_dimensions = dimensions !== '' && dimensions !== null ? JSON.parse(dimensions) : null
                    let parse_weight = weight !== '' && weight !== null ? JSON.parse(weight) : null
                    let no_of_sku = storable_unit_products.length !== 'undefined' ? storable_unit_products.length : 0

                    units.push({
                        parse_dimensions,
                        parse_weight,
                        no_of_sku,
                        storable_unit_products,
                        ...otherItems
                    })
                })
            }

            return units            
        }
    },
    methods: {
        ...mapActions({
            fetchStorableUnits: 'storableUnit/fetchStorableUnits'
        }),
        ...globalMethods,
        clicked(value) {
            // code for opening multiple
            const index = this.expanded.indexOf(value)
            value.isExpanded = false

            if (index === -1) {
                this.expanded.push(value)
            } else {
                this.expanded.splice(index, 1)
            }
        },
        itemRowBackground(item) {
            return item.isExpanded ? 'background-light-blue' : ''
        },
        formatDate(date) {
            if (date !== '' && date !== null) {
                return moment(date).format('MMM DD, YYYY hh:mmA') 
            } else {
                return '--'
            }
        },
        vieStorableUnit(item) {
            console.log(item, 'view storable unit');
        }
    },
    async mounted() {
        try {
            await this.fetchStorableUnits(this.currentWarehouseSelected.id)
        } catch(e) {
            this.notificationError(e)
        }
    },
    updated() {}
}
</script>

<style lang="scss"></style>