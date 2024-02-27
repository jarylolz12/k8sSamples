<template>
    <div class="shipment-tabs-table-wrapper-component">
        <!-- FOR DESKTOP -->
        <v-data-table 
            :headers="headersShipment" 
            :items="getCurrentShipmentsData"
            mobile-breakpoint="769"
            :page="getCurrentShipmentPage"
            :items-per-page="getShipmentItemsPerPage"
            item-key="name"
            class="shipments-table"
            :id="completed.length !== 0 ? '' : 'table-no-data'"
            v-bind:class="{
                'no-data-table': (typeof getCurrentShipmentsData !== 'undefined' && getCurrentShipmentsData.length === 0),
                'no-current-pagination' : getCurrentPaginationCountClass(),
                'has-searched-data' : search !== '' && getCurrentShipmentsData.length === 0
            }"
            hide-default-footer
            style="box-shadow: none !important"
            @page-count="pageCount = $event"
            @click:row="handleClick"
            :item-class="itemRowBackground"
            v-if="activeShipmentTab == 2 && !isMobile"
            hide-default-header
            fixed-header
            ref="my-table">

            <template v-slot:header="{ props: { headers } }">
                <thead>
                    <tr>
                        <th v-for="(item, index) in headers" 
                            :key="index"
                            role="columnheader"
                            :aria-label="item.text"
                            scope="col"
                            v-bind:class="
                                [item.sortable ? 'sortable' : '',
                                item.align == ' d-none' ? 'd-none' : `text-${item.align}`]"
                            @click="item.sortable ? changeSort(item.value) : ''"
                            v-bind:style="`width: ${item.width}; min-width: ${item.width};`">

                            {{ item.text }}

                            <v-icon v-if="item.sortable" small>{{ completedIconSort }}</v-icon>
                        </th>
                    </tr>  
                </thead>
            </template>
            
            <template v-slot:no-data>
                <div class="no-data-preloader" v-if="getAllCompletedShipmentsLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>

                <div v-if="(!getAllCompletedShipmentsLoading) && getCurrentShipmentsData.length == 0" class="no-data-wrapper">
                    <div class="no-data-icon">
                        <img src="@/assets/icons/noShipmentData.svg" alt="" width="65px">
                    </div>

                    <div class="no-data-heading">
                        <p> No Completed Shipments </p>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.ReferenceID`]="{ item }">
              <div class="departure-content-wrapper">
                <div class="flag-wrapper">
                  <p style="margin-bottom: 0px">
                    {{ item.ReferenceID }}
                  </p>
                </div>

                <div>
                  <p style="color: #6D858F; margin-top: 2px;">
                    {{ item.customer_reference_number !== "null" && item.customer_reference_number !== null ? item.customer_reference_number :'N/A' }}
                  </p>
                </div>
              </div>
            </template>

            <template v-slot:[`item.Departure`]="{ item }">
                <div>
                    <div class="flag-wrapper">
                        <p style="margin-bottom: 0px">
                            {{ item.Departure.location !== 'Not specified' ? item.Departure.location : 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p style="color: #0171A1;">
                            {{ item.Departure.etd !== 'Not Specified' ? item.Departure.etd : 'N/A' }}
                        </p>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.mode`]="{ item }">
                <div class="shipment-type" v-if="item.type !== null && item.type !== '' && item.type !== 'null'">
                    <span class="d-flex align-center mr-1">
                        <img v-if="item.mode == 'Ocean'" src="@/assets/icons/ocean.svg" width="20px" height="20px" />
                        <img v-if="item.mode == 'Air'" src="@/assets/icons/airplane.svg" width="20px" height="20px" />
                        <img v-if="item.mode == 'Truck'" src="@/assets/icons/truck.svg" width="20px" height="20px" />
                    </span>

                    <img v-if="item.type == 'LCL'" src="@/assets/images/small-container.svg" />
                    <img v-if="item.type == 'Air'" src="@/assets/images/airplane-shipment.svg" />
                    <img v-if="item.type == 'FCL'" src="@/assets/images/big-container.svg" />

                    <span style="padding-bottom:2px;" v-if="item.type == 'FCL' && item.container_num_list.length !== 0"> 
                        ({{ item.container_num_list.length }})
                    </span>
                </div>

                <div class="shipment-type" v-if="item.external_shipment_tracking===1 && item.external_shipment_containers.length > 0 && (item.type == null || item.type == '' || item.type == 'null')">
                    <img src="@/assets/images/big-container.svg" />
                    <span style="padding-bottom:2px;">({{ item.external_shipment_containers.length }})</span>
                </div>

                <div class="no-shipment-type" v-if="(item.type == null || item.type == '' || item.type == 'null') && item.external_shipment_tracking==0">
                    N/A
                </div>

            </template>

            <template v-slot:[`item.Arrival`]="{ item }">
                <div class="arrival-wrapper">
                    <div>
                        <div class="flag-wrapper">
                            <p style="margin-bottom: 0px">
                                {{ item.Arrival.location !== 'Not specified' ? item.Arrival.location : 'N/A' }}
                            </p>
                        </div>
                        <p style="color: #0171A1;">
                            {{ item.Arrival.eta !== 'Not Specified' ? item.Arrival.eta : 'N/A' }}
                        </p>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.Suppliers`]="{ item }">
                <div class="supplier-desktop" :class="activeShipmentTab == 0 ? 'active-tab' : ''">
                    <span>{{ checkSuppliersName(item.Suppliers) }}</span>
                    <span v-if="item.Suppliers == null || item.Suppliers.length == 0">
                        <span>{{ item.is_tracking_shipment ? '' : 'N/A'}}</span>
                    </span>
                </div>
            </template>   

            <template v-slot:[`item.PO`]="{ item }">
                <div class="po-num-desktop">
                    <p>
                        <span v-for="(name, index) in item.po_list" :key="index">
                            {{ name }}<template v-if="index + 1 < item.po_list.length">, </template>
                        </span>
                    </p>
                </div>

                <div v-if="item.po_list == null">
                    <span>{{ item.is_tracking_shipment ? '' : 'N/A'}}</span>
                </div>
            </template>

            <template v-slot:[`item.Status`]="{ item }">
                <div class="status d-flex" :class="getStatusClass(item.Status)">
                    <div class="d-flex t-shipment-status-wrapper">
                        <div style="margin-right: 6px;" :class="getShipmentStatusClass(item,'tracking')" 
                            v-if="typeof item.tracking_status !== 'undefined' && item.tracking_status !== ''" class="font-medium">
                            {{ item.tracking_status }}
                        </div>
                    </div>

                    <v-chip class="pa-0" v-html="getStatus(item.Status)"></v-chip>
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="shipment-action-button d-flex align-end justify-end mr-1">
                    <button class="btn-white" @click="handleClick(item)">
                        <img src="../../../../assets/icons/visibility-po.svg" alt="" height="16px" width="16px">
                    </button>
                </div>     
            </template>
        </v-data-table> 

        <!-- FOR MOBILE -->
        <v-data-table 
            :headers="headersShipment" 
            :items="getCurrentShipmentsData"
            mobile-breakpoint="769"
            :page="getCurrentShipmentPage"
            :items-per-page="getShipmentItemsPerPage"
            item-key="name"
            class="table-mobile shipments-table-mobile"
            :id="getCurrentShipmentsData.length !== 0 ? '' : 'table-no-data'"
            v-bind:class="{
                'no-data-table': (typeof getCurrentShipmentsData !== 'undefined' && getCurrentShipmentsData.length === 0),
                'no-current-pagination' : getCurrentPaginationCountClass(),
                'has-searched-data' : search !== '' && getCurrentShipmentsData.length === 0
            }"
            hide-default-footer
            style="box-shadow: none !important"
            @page-count="pageCount = $event"
            @click:row="handleClick"
            :item-class="itemRowBackground"
            v-if="activeShipmentTab == 2 && isMobile"
            hide-default-header
            fixed-header
            ref="my-table">
            
            <template v-slot:no-data>
                <div class="no-data-preloader" v-if="getAllCompletedShipmentsLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>

                <div v-if="(!getAllCompletedShipmentsLoading) && getCurrentShipmentsData.length == 0" class="no-data-wrapper">
                    <div class="no-data-icon">
                        <img src="@/assets/icons/noShipmentData.svg" alt="" width="65px">
                    </div>

                    <div class="no-data-heading">
                        <p> No Completed Shipments </p>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.ReferenceID`]="{ item, index }">
                <div class="table-mobile-data mobile-reference">
                    <div class="mobile-reference-content k-flex k-flex-col k-items-start">
                        <span class="k-flex k-items-start">
                            <span class="mr-2">{{ item.ReferenceID }}</span>

                            <div class="mobile-mode d-flex align-center">
                                <span class="mr-2 d-flex align-center">
                                    <img v-if="item.mode == 'Ocean'" src="@/assets/icons/ocean.svg" width="20px" height="18px" />
                                    <img v-if="item.mode == 'Air'" src="@/assets/icons/airplane.svg" width="20px" height="18px" />
                                    <img v-if="item.mode == 'Truck'" src="@/assets/icons/truck.svg" width="20px" height="18px" />
                                </span>

                                <div class="d-flex align-center mr-1" 
                                    v-if="item.type !== null && item.type !== '' && item.type !== 'null'">                                    
                                    <img v-if="item.type == 'LCL'" src="@/assets/images/small-container.svg" width="20px" height="18px" />
                                    <img v-if="item.type == 'Air'" src="@/assets/images/airplane-shipment.svg" width="20px" height="18px" />
                                    <img v-if="item.type == 'FCL'" src="@/assets/images/big-container.svg" width="20px" height="18px" />
                                    <span style="margin-left: 6px; font-weight: 500;" class="font-regular"
                                        v-if="item.type == 'FCL' && item.container_num_list.length !== 0"> 
                                        ({{ item.container_num_list.length }})
                                    </span>
                                </div>
                            </div>
                        </span>
                    </div>

                    <div class="shipment-item-todos-mobile d-flex align-center justify-center" 
                        v-if="index !== 1 && index !== 2">
                        <img src="../../../../assets/icons/to-dos-blue.svg" alt="" height="12px" width="12px"> 
                        <span class="todos-count ml-2 font-medium" style="color: #0171A1;">(1)</span>
                    </div> 

                    <!-- static only for viewing purposes -->
                    <div class="shipment-item-todos-mobile to-dos-red d-flex align-center justify-center" v-if="index === 1">
                        <img src="../../../../assets/icons/to-dos-red.svg" alt="" height="12px" width="12px"> 
                        <span class="todos-count ml-2 font-medium" style="color: #F93131;">(1)</span>
                    </div> 

                    <div class="shipment-item-todos-mobile d-flex align-center justify-center" v-if="index === 2">
                        <img src="../../../../assets/icons/to-dos-orange.svg" alt="" height="12px" width="12px"> 
                        <span class="todos-count ml-2 font-medium" style="color: #D68A1A;">(1)</span>
                    </div> 
                </div>
            </template>

            <template v-slot:[`item.Departure`]="{ item }">
                <div class="table-mobile-data-content">
                    <div class="table-mobile-data mb-2">
                        <div class="mobile-departure-wrapper">
                            <span style="color: #6D858F;">Departure</span>
                        </div>

                        <div class="mobile-departure-wrapper" style="text-align: left !important;">
                            <div class="flag-wrapper">
                                <p style="margin-bottom: 0px;">
                                    <span>
                                        {{ item.Departure.location !== 'Not specified' ? item.Departure.location : 'N/A' }}

                                        <span class="departure-date ml-1" style="color: #0171A1;">
                                            {{ item.Departure.etd !== 'Not Specified' ? item.Departure.etd : 'N/A' }}
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="table-mobile-data mb-2">
                        <div class="mobile-arrival-wrapper">
                            <span style="color: #6D858F;">Arrival</span>
                        </div>

                        <div class="arrival-wrapper" style="text-align: left !important;">
                            <div class="flag-wrapper">
                                <p style="margin-bottom: 0px;">
                                    <span>
                                        {{ item.Arrival.location !== 'Not specified' ? item.Arrival.location : 'N/A' }}

                                        <span class="arrival-date ml-1" style="color: #0171A1;">
                                            {{ item.Arrival.eta !== 'Not Specified' ? item.Arrival.eta : 'N/A' }}
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="table-mobile-data mb-2">
                        <div class="mobile-pos-wrapper">
                            <span style="color: #6D858F;">POs</span>
                        </div>
                        
                        <div class="po-num-mobile">
                            <p>
                                <span v-for="(name, index) in item.po_list" :key="index">
                                    <span v-if="index === 0 || index === 1">
                                        {{ name }}<template v-if="index + 1 < item.po_list.length">, </template>
                                    </span>
                                </span>

                                <span v-if="item.po_list.length > 2" style="color: #0171A1;">
                                    +{{ item.po_list.length - 2 }} Other
                                </span>
                            </p>
                        </div>

                        <div v-if="item.po_list == null || item.po_list.length == 0">
                            <p> {{ item.is_tracking_shipment ? '' : 'N/A'}} </p>
                        </div>
                    </div>
                </div>                
            </template>

            <template v-slot:[`item.mode`]="{ item }">
                <div class="k-flex mb-1">
                    <div class="d-flex t-shipment-status-wrapper">
                        <div style="margin-right: 4px !important;" :class="getShipmentStatusClass(item,'tracking')" 
                            v-if="typeof item.tracking_status !== 'undefined' && item.tracking_status !== ''" class="font-medium">
                            {{ item.tracking_status }}
                        </div>
                    </div>
                    
                    <div class="status-mobile k-flex mr-1" :class="getStatusClass(item.Status)">
                        <v-chip v-html="getStatus(item.Status)"></v-chip>
                    </div>
                </div>
            </template>
        </v-data-table>

        <div class="pagination-wrapper pt-3" 
            v-if="activeShipmentTab === 2 && getCurrentShipmentsData !== 'undefined' && 
            search == '' && pagination.completedTab.total > 1">
            <v-pagination
                v-model="pagination.completedTab.current_page"
                :length="pagination.completedTab.total"
                prev-icon="Previous"
                next-icon="Next"
                :total-visible="!isMobile ? '11' : '5' "
                @input="handlePageChange">
            </v-pagination>
        </div> 
        <!-- v-if="activeShipmentTab === 2 && getCurrentShipmentsData !== 'undefined' && getCurrentShipmentsData.length > 0 && search == ''" -->

        <div class="pagination-wrapper pt-3" 
            v-if="activeShipmentTab === 2 && getCurrentShipmentsData !== 'undefined' && 
            search !== '' && getShipmentPagination > 1">
            <v-pagination
                v-model="getCurrentShipmentPage"
                :length="getShipmentPagination"
                prev-icon="Previous"
                next-icon="Next"
                :total-visible="!isMobile ? '11' : '5' "
                @input="handlePageSearched">
            </v-pagination>
        </div> 
        <!-- v-if="activeShipmentTab === 2 && getCurrentShipmentsData !== 'undefined' && getCurrentShipmentsData.length > 0 && search !== ''" -->
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import globalMethods from '../../../../utils/globalMethods'
import _ from 'lodash'

export default {
    props: ['completed', 'activeTab', 'isMobile', 'search', 'paginationData', 'completedIconSort'],
    components: {},
    data: () => ({
        pageCount: 0,
        trackingStatusMap: [
            {
                class: 'manual-tracking',
                value: 'Manual Tracking'
            },
            {
                class: 'auto-tracking',
                value: 'Auto Tracking'
            },
            {
                class: 'auto-tracked',
                value: 'Auto Tracked'
            },
            {
                class: 'manually-tracked',
                value: 'Manually Tracked'
            },
            {
                class: 'not-tracking',
                value: 'Not Tracking'
            },
            {
                class: 'past-last-free-day',
                value: 'Past Last Free Day'
            },
            {
                class: 'discharged-released',
                value: 'Discharged - released'
            },
            {
                class: 'in-transit-hold',
                value: 'In transit - hold'
            },
            {
                class: 'in-transit-released',
                value: 'In transit - released'
            },
            {
                class: 'discharged-hold',
                value: 'Discharged - hold'
            },
            {
                class: 'partially-discharged',
                value: 'Partially discharged'
            }
        ],
        headersShipment: [
            {
                text: "Ref#/Cus Ref",
                align: "start",
                sortable: false,
                value: "ReferenceID",
                width: "10%",
                fixed: true
            },
            {
                text: "Departure",
                align: "start",
                value: "Departure",
                sortable: false,
                width: "12%", 
                fixed: true
            },
            {
                text: "Mode/Type",
                align: "start",
                value: "mode",
                sortable: false,
                width: "8%",
                fixed: true
            },
            {
                text: "Arrival",
                align: "start",
                value: "Arrival",
                sortable: true,
                width: "15%", 
                fixed: true
            },
            {
                text: "Suppliers",
                align: "start",
                value: "Suppliers",
                sortable: false,
                width: "20%",
                fixed: true
            },
            {
                text: "PO",
                align: "start",
                value: "PO",
                sortable: false,
                width: "12%",
                fixed: true
            },
            {
                text: "Status",
                value: "Status",
                align: 'start',
                sortable: false,
                width: "18%",
                fixed: true
            },
            {
                text: "",
                value: "actions",
                align: 'end',
                sortable: false,
                width: "5%",
                fixed: true
            },
            {
                text: "Container",
                align: ' d-none',
                sortable: false,
                value: "container_num_list",
                width: "0",
                fixed: true
            },
            {
                text: "Mbl",
                align: ' d-none',
                sortable: false,
                value: "mbl_num",
                width: "0",
                fixed: true
            },
            {
                text: "Hbl",
                align: ' d-none',
                sortable: false,
                value: "hbl_num",
                width: "0",
                fixed: true
            },
            {
                text: "Ams",
                align: ' d-none',
                sortable: false,
                value: "ams_num",
                width: "0",
                fixed: true
            },
        ],
    }),
    computed: {
        ...mapGetters([
            "getShipmentLoadingStatus",
            "getAllCompletedShipmentsLoading",
            "getSearchedShipments",
            "getSearchedShipmentPagination"
        ]),
        activeShipmentTab: {
            get() {
                return this.activeTab
            },
            set(value) {
                this.$emit('update:activeTab', value)
            }
        },
        pagination: {
            get() {
                return this.paginationData
            },
            set(value) {
                this.$emit('update:paginationData', value)
            }
        },
        getCurrentShipmentsData() {
            let filteredShipments = []

            if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                if (this.search !== '' && this.getSearchedShipments.tab === 'completed') {
                    filteredShipments = this.getSearchedShipments.shipments
                } else {
                    filteredShipments = this.completed
                }
            } else {
                filteredShipments = this.completed
            }


            if ( filteredShipments.length > 0) {
                filteredShipments.map((fs, key ) => {
                    filteredShipments[key].external_shipment = 0
                    filteredShipments[key].external_shipment_tracking = 0

                    if ( !Array.isArray(fs.po_list)) {
                        filteredShipments[key].po_list = []
                    }

                    if (fs.is_tracking_shipment==1) {
                        filteredShipments[key].external_shipment = 1
                    }
                    if (typeof fs.terminal_fortynine!=='undefined' && fs.terminal_fortynine!==null && typeof fs.terminal_fortynine.attributes!=='undefined') {
                        
                        let getAttributes = fs.terminal_fortynine.attributes
                        let getContainers = fs.terminal_fortynine.containers
                        if ( getAttributes !=='' && getAttributes!==null && fs.is_tracking_shipment == 1) {
                            filteredShipments[key].external_shipment_tracking = 1
                            filteredShipments[key].external_shipment_containers = []
                        }

                        if ( getContainers!=='undefined' ) {
                            getContainers = JSON.parse(getContainers)
                            filteredShipments[key].external_shipment_containers = getContainers
                        }

                    }

                })
            }

            return filteredShipments
        },
        getCurrentShipmentsDataBak() {
            if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                if (this.search !== '' && this.getSearchedShipments.tab === 'completed') {
                    return this.getSearchedShipments.shipments
                } else {
                    return this.completed
                }
            } else {
                return this.completed
            }
        },
        getCurrentShipmentPage: {
            get() {
                if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                    if (this.search !== '' && this.getSearchedShipments.tab === 'completed') {
                        return this.getSearchedShipmentPagination.current_page
                    } else {
                        return this.pagination.completedTab.current_page
                    }
                } else {
                    return this.pagination.completedTab.current_page
                }
            },
            set(value) {
                if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                    if (this.search !== '' && this.getSearchedShipments.tab === 'completed') {
                        this.$store.state.shipment.searchedShipmentsPagination.current_page = value
                    } else {
                        this.$emit('update:paginationData', value)

                    }
                } else {
                    this.$emit('update:paginationData', value)
                }
            }
        },
        getShipmentItemsPerPage() {
            if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                if (this.search !== '' && this.getSearchedShipments.tab === 'completed') {
                    return this.getSearchedShipmentPagination.per_page
                } else {
                    return this.pagination.completedTab.per_page
                }
            } else {
                return this.pagination.completedTab.per_page
            }
        },
        getShipmentPagination() {
            if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                if (this.search !== '' && this.getSearchedShipments.tab === 'completed') {
                    return this.getSearchedShipmentPagination.total
                } else {
                    return this.pagination.completedTab.total
                }
            } else {
                return this.pagination.completedTab.total
            }
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "shipments")
    },
    methods: {
        ...mapActions([
            "fetchShipments",
        ]),
        ...globalMethods,
        getCurrentPaginationCountClass() {
            if (this.search === '') {
                return typeof this.pagination.completedTab.total !== 'undefined' ? this.pagination.completedTab.total <= 1 : false
            } else {
                return typeof this.getShipmentPagination !== 'undefined' ? this.getShipmentPagination <= 1 : false
            }
        },
        handleClick(value) {
            this.$emit('handleClick', value)
        },
        async changeSort() {
            this.$emit('sort', 2)
        },
        itemRowBackground: function (item) {
            return item.Status == 'Past last free day' ? 'light-red' : ''
        },
        getStatus(status) {
            if (status !== null) {
                if (status == 'In transit - hold') {
                    return "<span class='chip-text'>In Transit - <span>Pending Release</span><span>"

                } else if (status == 'In Transit - Released') {
                    return "<span class='chip-text'>In Transit - <span class='green--text'>Released</span><span>"

                } else if (status == 'Partially discharged - hold') {
                    return "<span class='chip-text'>Partially Discharged - <span class='red--text'>Hold</span><span>"

                } else if (status == 'Partially discharged - released') {
                    return "<span class='chip-text'>Partially Discharged - <span class='green--text'>Released</span><span>"

                } else if (status == 'Discharged - Hold') {
                    return `<span class='chip-text red--text'>${status}<span>`

                } else if (status == 'Completed' || status == 'Empty In' || status == 'Discharged - released') {
                    return `<span class='chip-text green--text completed'>${status}<span>`

                } else if (status == 'Past last free day') {
                    return `<span class='chip-text red--text'>${status}<span>`

                } else {
                    return `<span class='chip-text'>${status}</span>`
                }
            }

        },
        getStatusClass(status) {
            if (status == 'Past last free day') {
                return 'Past-day'
            } else if (status == 'Empty In') {
                return 'Empty-in'
            } else if (status == 'Full Out') {
                return 'Full-out'
            } else if (status == 'Discharged - Hold') {
                return 'Discharged-hold'
            } else if (status == 'Discharged - released') {
                return 'Discharged-released'
            } else if (status == 'Partially discharged - hold') {
                return 'Partial-hold'
            } else if (status == 'Partially discharged - released') {
                return 'Partial-released'
            } else {
                return status
            }
        },
        getShipmentStatusClass(item, type) {
            let {
                tracking_status,
                Status
            } = item

            let setValue = (type ==='tracking') ? tracking_status : Status
            let setClass = _.find(this.trackingStatusMap, e => e.value === setValue)
            setClass = (typeof setClass!=='undefined') ? setClass.class : ''
            
            if ( setClass === 'not-tracking' && item.Status === 'Past last free day')
                setClass = 'not-tracking-past'
            return setClass
        },
        async handlePageChange(page) {
            this.$emit('handlePageChange', page)
            this.handleScrollToTop()
        },
        async handlePageSearched(page) {
            let data = { 
                page,
                tab: 'completed'
            }

            this.$emit('handlePageSearched', data)
            this.handleScrollToTop()
        },
        handleScrollToTop() {
            var table = this.$refs['my-table'];
            var wrapper = table.$el.querySelector('div.v-data-table__wrapper');
            
            this.$vuetify.goTo(table); // to table
            this.$vuetify.goTo(table, {container: wrapper}); // to header
        },
        checkSuppliersName(suppliers){
            if(Array.isArray(suppliers) && suppliers !== null){
                return suppliers.filter(item => item !== '').join(', ');
            }else{
                return '';
            }
        }
    },
    updated() {}
}
</script>

<style></style>
