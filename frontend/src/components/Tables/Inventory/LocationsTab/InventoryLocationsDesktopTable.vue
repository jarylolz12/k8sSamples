<template>
    <div class="locations-desktop-wrapper">
        <div class="overlay" :class="getCurrentLoadingToDisplay ? 'show' : ''">  
            <div class="preloader" v-if="(getCurrentLoadingToDisplay)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>
        <v-data-table
            :headers="headersComputed"
            :items="currentSelectedLocations"
            :page.sync="getCurrentPage"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            class="inventory-table locations-table elevation-1"
            v-bind:class="{
                'no-data-table': (typeof currentSelectedLocations !== 'undefined' && currentSelectedLocations.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
                'warehouse-connected' : isWarehouseConnected && (typeof currentSelectedLocations !== 'undefined' && currentSelectedLocations.length > 0)
            }"
            hide-default-footer
            fixed-header
            show-select
            ref="my-table">

            <template v-slot:top>
                <div class="inventory-search-wrapper location-search">
                    <div class="d-flex">

                    
                    <v-tabs class="inventory-inner-tab" @change="onTabChange" 
                        v-model="$store.state.locations.locationsTab.typeSubTab"
                        v-if="!isWarehouseConnected && isWarehouse3PL">
                        <v-tab v-for="(tab, index) in tabs" 
                            :key="index">
                            {{ tab }}

                            <!-- the code for this is in the backup file -->
                            <!-- <small>{{ getTabCount(index) }}</small>  -->
                        </v-tab>
                    </v-tabs>

                    <!-- <v-tabs class="inventory-inner-tab second" @change="onTypeTabChange" 
                        v-model="$store.state.locations.locationsTab.typeTab">
                        <v-tab v-for="(tab, index) in typeTabs" 
                            :key="index" style="border-radius: 4px;">
                            {{ tab }}
                        </v-tab>
                    </v-tabs> -->
                    
                    <v-tabs v-if="!isWarehouseConnected && !isWarehouse3PL" class="inventory-inner-tab" @change="onTabChange" 
                        v-model="$store.state.locations.locationsTab.typeSubTab"
                       >
                        <v-tab v-for="(tab, index) in typeTabsOwnAndConnected" 
                            :key="index">
                            {{ tab }}

                            <!-- the code for this is in the backup file -->
                            <!-- <small>{{ getTabCount(index) }}</small>  -->
                        </v-tab>
                    </v-tabs>
                    <div class="state-for-tabs ml-2" v-if="!isWarehouseConnected && !isWarehouse3PL">
                        <v-select
                            placeholder="State"
                            :items="stateForTabs"
                            outlined
                            append-icon="mdi-chevron-down"
                            item-text="name"
                            class=""
                            v-model="selectedState"
                            :menu-props="{  bottom: true, offsetY: true }"
                            @change="fetchLocationsTabsAndTypes"
                            hide-details="auto">
                            <template v-slot:selection="{item}">    
                                <span style="color: #6D858F;" class="name d-flex">State
                                    <span style="color: #4A4A4A;font-weight: 500;margin-left:4px">{{item}}</span>
                                </span> 
                                   
                            </template>

                            <template v-slot:item="{ item, on, attrs }">
                                <v-list-item style="width:200px"  :style="selectedState == item ? 'background:#F0FBFF':''" v-on="on" v-bind="attrs">

                                    <v-list-item-content :style="selectedState == item ? 'background:#F0FBFF':''">
                                        <v-list-item-title class="d-flex justify-space-between" style="color: #4A4A4A;">           
                                            <p class="name mb-1 font-medium">{{ item }}</p>
                                            <span  v-if="item == 'All'">
                                                <v-icon small color="#0171A1;" class="address mb-1" style="color:#0171A1;">mdi-check</v-icon>
                                            </span>          
                                            <span style="background-color:#EBF2F5; font-size:12px; border-radius:12px; width:28px; height:20px; padding:3px 6px;" class="text-center" v-else>
                                                <span v-if="item == 'Filled'" class="font-medium">{{getCountForTabs.filled_count}}</span>
                                                <span v-if="item == 'Empty'" class="font-medium">{{getCountForTabs.empty_count}}</span>
                                            </span>
                                        </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-select>
                    </div>
                    </div>

                    <v-spacer></v-spacer>

                    <div class="search-and-filter">
                        <div class="filter-tags-input" v-if="(isWarehouse3PLProvider && !isWarehouseConnected) && handleFilterComponent">
                            <FilterCustomers 
                                @onClickFilter="onClickFilter"
                                @filterAllWarehouseCustomers="filterAllWarehouseCustomers"
                                @cancelSelectingWarehouseCustomers="cancelSelectingWarehouseCustomers"
                                @searchWarehouseCustomers="searchWarehouseCustomers"
                                @removeCustomerLists="removeCustomerLists"
                                @removeCustomerListsEmptyOnChange="removeCustomerListsEmptyOnChange"
                                :searchCustomerData.sync="searchCustomerData"
                                :menu.sync="filterMenu"
                                :selectedWhCustomersCopy.sync="selectedWhCustomersCopy"
                                :selectedWhCustomers.sync="selectedWhCustomers"
                                :warehouseCustomerLists.sync="warehouseCustomerLists"
                                :loading="fetchWarehouseCustomersLoading"
                                @clickOutside="clickOutsideFilter"
                                :isActiveClicked.sync="isActiveClicked"
                                @clearAllFilter="clearAllFilter"
                            />
                        </div>

                        <SearchComponent
                            placeholder="Search Locations"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />

                        <v-btn dark color="primary" class="btn-blue ml-2" @click.stop="addLocation" v-if="!isWarehouseConnected">
                            Add Location
                        </v-btn>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.shelf`]="{ item }">
                <div class="status"> {{ item.shelf !== null && item.shelf !== '' ? item.shelf : '--' }} </div>
            </template>

            <template v-slot:[`item.row`]="{ item }">
                <div class="status"> {{ item.row !== null && item.row !== '' ? item.row : '--' }} </div>
            </template>

            <template v-slot:[`item.rack`]="{ item }">
                <div class="status"> {{ item.rack !== null && item.rack !== '' ? item.rack : '--' }} </div>
            </template>

            <template v-slot:[`item.storable_unit`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="item" v-for="(unit, index) in item.storable_units" :key="index">
                        <div class="mb-0" style="color: #6D858F !important;" v-if="item.storable_units.length === 1"> 
                            <p class="mb-0">
                                {{ unit.label }}
                            </p> 
                            <p class="mb-0 text-capitalize" style="color: #6D858F !important;">
                                {{ unit.type }}
                            </p> 
                        </div>
                    </div>

                    <div class="item" v-if="item.storable_units.length > 1">
                        <p class="mb-0"> Multiple </p>
                        <p class="mb-0 text-capitalize" style="color: #6D858F !important;"> {{item.storable_units[0].type}} </p>
                    </div>

                    <div class="item red--text lighten-2" v-if="item.storable_units.length === 0">
                        <p class="mb-0"> Empty </p>
                        <p class="mb-0"> -- </p>
                    </div>
                </div>
            </template>
            <template v-slot:[`item.default_sku`]="{ item }">
                <div class="">
                    <div class="d-flex"  v-if="item.default_sku && typeof item.default_sku == 'string'">
                        <div class="item" v-for="(unit, index) in JSON.parse(item.default_sku)" :key="index" :class="index==2 ? 'd-flex align-center' : ''">
                            <span v-if="index == 0 || index == 1" class="mb-0"> 
                                #{{ unit}}<span class="mr-1" v-if="index == 0 && JSON.parse(item.default_sku).length > 1 ">,</span>
                            </span>
                            <span v-if="index == 2">
                                <v-menu
                                    open-on-hover
                                    bottom
                                    offset-x
                                    :nudge-width="220"
                                    :nudge-right="12">
                                    
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-hover v-slot="{ hover }">
                                            <v-btn
                                                style="border:none !important;height:20px !important;color: #0171A1 !important;text-transform: unset !important;padding:0 0 0 5px !important;"
                                                class="button-hover-default-sku"
                                                :style="{ 'background-color': hover ? 'transparent' : 'transparent' }"
                                                plain                                            
                                                v-bind="attrs"
                                                v-on="on"
                                                x-small>
                                                <span style="letter-spacing:0;" class="font-medium pr-2">
                                                    +{{JSON.parse(item.default_sku).length - 2 }} more
                                                </span>
                                            </v-btn>
                                        </v-hover>
                                    </template>
                                
                                    <v-list>
                                        <v-list-item
                                            v-for="(sku, index_sku) in JSON.parse(item.default_sku)" :key="index_sku">
                                            <v-list-item-title v-if="index > 1">
                                                <div class="mt-2 mb-1" style="color: #4A4A4A !important;font-size:14px">
                                                    #{{sku}}
                                                </div>
                                                <div style="font-size:12px;color: #819FB2;" class="font-medium mb-2">
                                                    {{ getSkuWithName(sku).name }}
                                                </div>
                                            </v-list-item-title>                                        
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </span>
                        </div>
                    </div>

                    <div v-else>
                        {{item.default_sku == null ? '--': '--'}}
                    </div>
                </div>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="type">
                        <span class="text-capitalize">{{ item.type }}</span>

                        <!-- {{ getTypeOfStorable(item.storable_unit, item.type) }} -->

                        <!-- <div class="type-content" v-if="item.type === 'storage'">
                            <div class="item" v-for="(unit, index) in item.storable_units" :key="index">
                                <p class="mb-0" v-if="item.storable_units.length === 1"> 
                                    {{ unit.type !== null ? unit.type : '--' }}
                                </p>
                            </div>

                            <p class="mb-0" v-if="item.storable_units.length > 1"> 
                                Multiple
                            </p>

                            <p class="mb-0 " v-if="item.storable_units.length === 0"> 
                                --
                            </p>
                        </div>

                        <div class="type-content" v-if="item.type === 'gate'">
                            <div class="item" v-for="(unit, index) in item.storable_units" :key="index">
                                <p class="mb-0" v-if="item.storable_units.length === 1"> 
                                    {{ unit.type !== null ? unit.type : 'N/A' }}
                                </p>
                            </div>

                            <p class="mb-0" v-if="item.storable_units.length > 1"> 
                                Multiple
                            </p>

                            <p class="mb-0" v-if="item.storable_units.length === 0"> 
                                N/A
                            </p>
                        </div> -->
                    </div>
                </div>
            </template>

            <template v-slot:[`item.ref_no`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="type-content">
                        <div class="item" v-for="(ref, index) in item.ref_no" :key="index">
                            <p class="mb-0" v-if="item.ref_no.length === 1"> 
                                {{ ref.ref_no !== null ? ref.ref_no : '--' }}
                            </p>
                        </div>

                        <p class="mb-0" v-if="item.ref_no.length > 1"> 
                            Multiple
                        </p>

                        <p class="mb-0" v-if="item.ref_no.length === 0"> 
                            --
                        </p>
                    </div>
                </div>
            </template>
            <template v-slot:[`item.code`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="type">
                        {{
                            item.code !== null ? 
                            item.code : '--'
                        }}
                    </div>
                </div>
            </template>

            <template v-slot:[`item.position`]="{ item }">
                <div class="inventory-wrapper justify-center">
                    <div class="type">
                        {{ 
                            item.position !== null ? item.position : '--'
                        }}
                    </div>
                </div>
            </template>

            <template v-slot:[`item.warehouse_customer`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="type" :class="item.warehouse_customer_id === null ? 'red--text lighten-2' : ''">
                        {{ 
                            item.warehouse_customer !== null && 
                            item.warehouse_customer.company_name !== null ? 
                            item.warehouse_customer.company_name : 'Empty'
                        }}
                    </div>
                </div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="inventory-wrapper">
                    <div> {{ formatDate(item.updated_at) }}</div>
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions mr-1" v-if="!isWarehouseConnected">
                    <button class="btn-edit inventory-btn-edit" @click.stop="editLocation(item)">
                        <img src="@/assets/icons/edit-inventory.svg" alt="" width="12px" height="12px"> Edit
                    </button>

                    <button v-if="!isWarehouse3PL" class="btn-edit inventory-btn-edit ml-2" @click="$emit('printLocationLabel',item)">
                        <img src="@/assets/icons/print-icon-blue.svg" alt="" width="12px" height="12px"> Print
                    </button>                    

                    <button class="btn-delete ml-2" @click="deleteLoc(item)"
                        v-if="item.storable_units.length === 0 && isWarehouse3PL">                        
                        <img src="@/assets/icons/delete-blue.svg" alt="">
                    </button>

                    <v-menu v-if="!isWarehouse3PL" bottom left content-class="outbound-lists-menu" offset-y>
						<template v-slot:activator="{ on, attrs }">
							<v-btn
                                class="ml-2"
								icon
								v-bind="attrs"
								v-on="on" >
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>
                        <v-list class="outbound-lists">
							<v-list-item class="">
								<v-list-item-title class="cancel" @click="deleteLoc(item)">
									Delete Location</v-list-item-title>
							</v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="currentLocationsLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper"
                    v-if="!currentLocationsLoading && currentSelectedLocations.length == 0">
                    <div class="no-data-heading" v-if="search === '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 0">
                            <h3> Add Location </h3>
                            <p>
                                Add Location for this warehouse.
                            </p>

                            <div class="d-flex justify-center align-center mt-4">
                                <v-btn color="primary" dark class="btn-blue" @click.stop="addLocation">
                                    Add Location
                                </v-btn>
                            </div>
                        </div>                 

                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 1">
                            <h3> No Pickable Locations </h3>
                            <p> There are no Pickable locations listed. </p>
                        </div>

                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 2">
                            <h3> No Staging Locations </h3>
                            <p> There are no Staging locations listed. </p>
                        </div>

                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 3">
                            <h3> No Packing Locations </h3>
                            <p> There are no Packing locations listed. </p>
                        </div>

                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 4">
                            <h3> No Special Locations </h3>
                            <p> There are no Special locations listed. </p>
                        </div>

                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 5">
                            <h3> No Others Locations </h3>
                            <p> There are no Others locations listed. </p>
                        </div>
                        <div v-if="$store.state.locations.locationsTab.typeSubTab === 6">
                            <h3> No Locations </h3>
                            <p> There are no locations listed. </p>
                        </div>

                    </div>

                    <div class="no-data-heading" v-if="search !== '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">
                        <div>                           
                            <h3> No Locations found. </h3>
                            <p> There are no locations listed. Try searching another keyword.</p>
                        </div>
                    </div>

                    <div v-if="selectedWhCustomers.length > 0">
						<img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">
                        <div>
                            <h3> No Locations found. </h3>
                            <p> There are no locations listed. Change another Customer.</p>
                        </div>
					</div>
                </div>
            </template>
        </v-data-table>

        <LocationAddDialog
            :dialogLocation.sync="dialogLocation"
            :editedLocationIndex.sync="editedLocationIndex"
            :editedLocationItems.sync="editedLocationItems"
            :defaultLocationItems.sync="defaultLocationItems"
            :currentWarehouseSelected="currentWarehouseSelected"
            :pagination.sync="getCurrentPage"
            :currentLocationTypeTab="$store.state.locations.locationsTab.typeTab === 0 ? 'storage' : 'gate'"
            @close="closeLocation"
            @setLocationToDefault="setLocationToDefault" 
            :searchVal="search"
            :currentSelectedLocations="currentSelectedLocations"
            :locationTypes="locationTypes"
            @filterAllWarehouseCustomers="filterAllWarehouseCustomers"
            :selectedWhCustomers.sync="selectedWhCustomers"
            :selectedWhCustomersCopy.sync="selectedWhCustomersCopy"
            :selectedState="selectedState"
            :productsListsData="productsListsData" />

        <ConfirmDialog :dialogData.sync="dialogLocationDelete">
            <template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2> Delete Locations </h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					Do you want to delete the location
					<span class="name">
						"{{ currentLocationToDelete !== null ? currentLocationToDelete.name : '' }}"
					</span>? 
					Once deleted, this cannot be undone.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="deleteLocationConfirm" text :disabled="getDeleteLocationLoading">
					<span v-if="!getDeleteLocationLoading">Delete</span>
                    <span v-if="getDeleteLocationLoading">Deleting...</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeLocationDelete" :disabled="getDeleteLocationLoading">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import SearchComponent from '../../../SearchComponent/SearchComponent.vue'
import LocationAddDialog from '../../../InventoryComponents/LocationComponents/LocationAddDialog.vue'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'
import ConfirmDialog from '../../../Dialog/GlobalDialog/ConfirmDialog.vue'
import FilterCustomers from '../../../InventoryComponents/FilterCustomers/FilterCustomers'
import globalMethods from '../../../../utils/globalMethods'
import inventoryGlobalMethods from '../../../../utils/inventoryMethods/inventoryGlobalMethods'
import _ from 'lodash'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'InventoryLocationsDesktopTable',
    props: ['currentWarehouseSelected', 'isMobile', 'isWarehouseConnected', 'isWarehouse3PLProvider','isWarehouse3PL'],
    components: {
        SearchComponent,
        LocationAddDialog,
        PaginationComponent,
        ConfirmDialog,
        FilterCustomers
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        headersDefault: [
            { 
                text: 'Label',
                align: 'start',
                sortable: false,
                value: 'label',
                fixed: true,
                width: "16%"
            },
            { 
                text: 'Row',
                align: 'center',
                sortable: false,
                value: 'row',
                fixed: true,
                width: "8%"
            },
            { 
                text: 'Gate Name',
                align: 'start',
                sortable: false,
                value: 'gate_name',
                fixed: true,
                width: ""
            },
            { 
                text: 'Rack',
                align: 'center',
                sortable: false,
                value: 'rack',
                fixed: true,
                width: "8%"
            },
            {
                text: 'Shelf',
                align: 'center',
                sortable: false,
                value: 'shelf',
                fixed: true,
                width: "8%"
            },
            { 
                text: 'Code',
                align: 'start',
                sortable: false,
                value: 'code',
                fixed: true,
                width: ""
            },
            { 
                text: 'Position',
                align: 'center',
                sortable: false,
                value: 'position',
                fixed: true,
                width: "8%"
            },
            { 
                text: 'Contains',
                align: 'start',
                sortable: false,
                value: 'storable_unit',
                fixed: true,
                width: "12%" 
            },
            { 
                text: 'Default sku’s',
                align: 'start',
                sortable: false,
                value: 'default_sku',
                fixed: true,
                width: "10%%"
            },
            { 
                text: 'Type',
                align: 'start',
                sortable: false,
                value: 'type',
                fixed: true,
                width: "10%%"
            },
            { 
                text: 'Reference',
                align: 'start',
                sortable: false,
                value: 'ref_no',
                fixed: true,
                width: "16%"
            },
            { 
                text: 'Truck',
                align: 'start',
                sortable: false,
                value: 'truck_name',
                fixed: true,
                width: ""
            },
            { 
                text: 'Updated At',
                align: 'start',
                sortable: false,
                value: 'updated_at',
                fixed: true,
                width: "16%"
            },
            { 
                text: 'Customer',
                align: 'start',
                sortable: false,
                value: 'warehouse_customer',
                fixed: true,
                width: "20%"
            },
            { 
                text: '', 
                align: 'end',
                value: 'actions', 
                sortable: false,
                fixed: true,
                width: "14%"
            },
        ],
        dialogLocation: false,
        editedLocationIndex: -1,
        editedLocationItems: {
            type: 'storage',
            shelf: '',
            row: '',
            rack: '',
            position: '',
            gate_name: '',
            storable_units: [],
            warehouse_customer_id: null,
            default_sku:[],
            code:'',
        },
        defaultLocationItems: {
            type: 'storage',
            shelf: '',
            row: '',
            rack: '',
            position: '',
            gate_name: '',
            storable_units: [],
            warehouse_customer_id: null,
            default_sku:[],
            code:'',
        },
        tabs: ["All", "Filled", "Empty"],
        typeTabs: ["Storage"],
        // typeTabs: ["Storage", 'Gates'],
        typeTabsOwnAndConnected :['Overstock','Pickable','Staging','Packing','Special','Others','All'],
        stateForTabs:['All','Filled','Empty'],
        currentTab: 0,
        currentTypeTab: 0,
        dialogLocationDelete: false,
        currentLocationToDelete: null,
        allStorageLocationsNextLoading: false,
        filledStorageLocationsNextLoading: false,
        emptyStorageLocationsNextLoading: false,
        allStockPickableStaggingLocationsNextLoading: false,
        packingStorageLocationsNextLoading:false,
        specialStorageLocationsNextLoading:false,
        othersStorageLocationsNextLoading:false,
        allGateLocationsNextLoading: false,
        filledGateLocationsNextLoading: false,
        emptyGateLocationsNextLoading: false,
        typingTimeout: 0,
        // warehouse customers data
		lastCheckWHData: [],
        selectedWhCustomers: [],
        selectedWhCustomersCopy: [],
        current_page_is_warehouse_products: 1,
		current_page_is_whcustomers: 1,
        searchCustomerData: '',
        warehouseCustomerListsData: [],
        warehouseCustomerLists: [],
        warehouseCustomerListsCopy: [],
        fetchWarehouseCustomersLoading: true,
        filterMenu: false,
        isActiveClicked: false,
        selectedWhCustomersReAssignValue:[],
        selectedState:'All',
        // Products Drop Down 
		lastDataCheck: [],
		productsListsData: [],
		current_page_is: 1
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getLocations: 'locations/getLocations',
            getLocationsLoading: 'locations/getLocationsLoading',
            getDeleteLocationLoading: 'locations/getDeleteLocationLoading',
            getStorageLocations: 'locations/getStorageLocations',
            getStorageLocationsLoading: 'locations/getStorageLocationsLoading',
            getGateLocations: 'locations/getGateLocations',
            getGateLocationsLoading: 'locations/getGateLocationsLoading',
            // storage locations
            getStorageAllPagination: 'locations/getStorageAllPagination',
            getStorageFilledPagination: 'locations/getStorageFilledPagination',
            getStorageEmptyPagination: 'locations/getStorageEmptyPagination',
            getAllStockPickableStaggingPagination:'locations/getAllStockPickableStaggingPagination',
            getStoragePackingPagination:'locations/getStoragePackingPagination',
            getStorageSpecialPagination:'locations/getStorageSpecialPagination',
            getStorageOthersPagination:'locations/getStorageOthersPagination',
            // gate locations
            getGateAllPagination: 'locations/getGateAllPagination',
            getGateFilledPagination: 'locations/getGateFilledPagination',
            getGateEmptyPagination: 'locations/getGateEmptyPagination',
            // storage loadings
            getStorageAllPaginationLoading: 'locations/getStorageAllPaginationLoading',
            getStorageFilledPaginationLoading: 'locations/getStorageFilledPaginationLoading',
            getStorageEmptyPaginationLoading: 'locations/getStorageEmptyPaginationLoading',
            getAllStockPickableStaggingLoading:'locations/getAllStockPickableStaggingLoading',
            getAllpackingLoading:'locations/getAllpackingLoading',
            getAllspecialLoading:'locations/getAllspecialLoading',
            getAllothersLoading:'locations/getAllothersLoading',
            // gate loadings
            getGateAllPaginationLoading: 'locations/getGateAllPaginationLoading',
            getGateFilledPaginationLoading: 'locations/getGateFilledPaginationLoading',
            getGateEmptyPaginationLoading: 'locations/getGateEmptyPaginationLoading',
            poBaseUrlState: 'products/poBaseUrlState',
            getSearchedLocations: 'locations/getSearchedLocations',
            getSearchedLocationsLoading: 'locations/getSearchedLocationsLoading',
            getSearchedLocationsItems: 'locations/getSearchedLocationsItems',
            getAllWarehouseCustomerListsData: 'warehouseCustomers/getAllWarehouseCustomerListsData',
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            // location with tab and type
            getAllLocationWithOverstock:'locations/getAllLocationWithOverstock',
            // filter and search
            getFilteredLocationsLoading:'locations/getFilteredLocationsLoading',
            getFilteredLocations: 'locations/getFilteredLocations',
            getLocationTypes: 'locations/getLocationTypes',
            getLocationTypesLoading: 'locations/getLocationTypesLoading',
            // location products for default sku values
            getLocationsProductsForDeafultSku:'locations/getLocationsProductsForDeafultSku',
            getLocationsProductsForDeafultSkuLoading:'locations/getLocationsProductsForDeafultSkuLoading',
            getAllLocationProductListsDropdownData:'locations/getAllLocationProductListsDropdownData'
        }), 
        getCountForTabs(){
            let count = {
                empty_count:0,
                filled_count:0

            }
            let storeLocationsItems = this.$store.state.locations.locationsTab
            let currentTypeSubTab = storeLocationsItems.typeSubTab
            if(currentTypeSubTab === 0){
                if(typeof this.getStorageAllPagination !== 'undefined' && this.getStorageAllPagination !== null && 
                    typeof this.getStorageAllPagination.empty_count !== 'undefined' && this.getStorageAllPagination.filled_count !== 'undefined'  ){
                    count.empty_count=this.getStorageAllPagination.empty_count,
                    count.filled_count=this.getStorageAllPagination.filled_count        
                }else{
                    count.empty_count=0
                    count.filled_count=0
                }
            }else if(currentTypeSubTab === 1){
                if(typeof this.getStorageFilledPagination !== 'undefined' && this.getStorageFilledPagination !== null && 
                    typeof this.getStorageFilledPagination.empty_count !== 'undefined' && this.getStorageFilledPagination.filled_count !== 'undefined'  ){
                    count.empty_count=this.getStorageFilledPagination.empty_count,
                    count.filled_count=this.getStorageFilledPagination.filled_count        
                }else{
                    count.empty_count=0
                    count.filled_count=0
                }
            }else if(currentTypeSubTab === 2){
                if(typeof this.getStorageEmptyPagination !== 'undefined' && this.getStorageEmptyPagination !== null && 
                    typeof this.getStorageEmptyPagination.empty_count !== 'undefined' && this.getStorageEmptyPagination.filled_count !== 'undefined'  ){
                    count.empty_count=this.getStorageEmptyPagination.empty_count,
                    count.filled_count=this.getStorageEmptyPagination.filled_count        
                }else{
                    count.empty_count=0
                    count.filled_count=0
                }
            }else if(currentTypeSubTab === 3){
                if(typeof this.getStoragePackingPagination !== 'undefined' && this.getStoragePackingPagination !== null && 
                    typeof this.getStoragePackingPagination.empty_count !== 'undefined' && this.getStoragePackingPagination.filled_count !== 'undefined'  ){
                    count.empty_count=this.getStoragePackingPagination.empty_count,
                    count.filled_count=this.getStoragePackingPagination.filled_count        
                }else{
                    count.empty_count=0
                    count.filled_count=0
                }
            }else if(currentTypeSubTab === 4){
                if(typeof this.getStorageSpecialPagination !== 'undefined' && this.getStorageSpecialPagination !== null && 
                    typeof this.getStorageSpecialPagination.empty_count !== 'undefined' && this.getStorageSpecialPagination.filled_count !== 'undefined'  ){
                    count.empty_count=this.getStorageSpecialPagination.empty_count,
                    count.filled_count=this.getStorageSpecialPagination.filled_count        
                }else{
                    count.empty_count=0
                    count.filled_count=0
                }
            }else if(currentTypeSubTab === 5){
                if(typeof this.getStorageOthersPagination !== 'undefined' && this.getStorageOthersPagination !== null && 
                    typeof this.getStorageOthersPagination.empty_count !== 'undefined' && this.getStorageOthersPagination.filled_count !== 'undefined'  ){
                    count.empty_count=this.getStorageOthersPagination.empty_count,
                    count.filled_count=this.getStorageOthersPagination.filled_count        
                }else{
                    count.empty_count=0
                    count.filled_count=0
                }
            }else if(currentTypeSubTab === 6){
                if(typeof this.getAllStockPickableStaggingPagination !== 'undefined' && this.getAllStockPickableStaggingPagination !== null && 
                    typeof this.getAllStockPickableStaggingPagination.empty_count !== 'undefined' && this.getAllStockPickableStaggingPagination.filled_count !== 'undefined'  ){
                    count.empty_count=this.getAllStockPickableStaggingPagination.empty_count,
                    count.filled_count=this.getAllStockPickableStaggingPagination.filled_count        
                }else{
                    count.empty_count=0
                    count.filled_count=0
                }
            }else {
                count.empty_count=0
                count.filled_count=0
            }
            
            return count
        },
        storageLocationsListsData() {
            let locationLists = {}
            let storeLocationsItems = this.$store.state.locations.locationsTab
            let currentTypeSubTab = storeLocationsItems.typeSubTab

            if (typeof this.getSearchedLocations !== 'undefined' && this.getSearchedLocations !== null && 
                this.getFilteredLocations !== 'undefined' && this.getFilteredLocations !== null) {
                if (!this.isWarehouseConnected) {
                    if (this.search !== '' && currentTypeSubTab === 0 && 
                        this.getSearchedLocations.currentTabName === 'storage-all') {
                        if (this.selectedWhCustomers.length === 0) {
                            locationLists = this.getSearchedLocations
                        } else {
                            locationLists = this.getFilteredLocations
                        }
                    } else if (this.search !== '' && currentTypeSubTab === 1 && 
                        this.getSearchedLocations.currentTabName === 'storage-filled') {
                        if (this.selectedWhCustomers.length === 0) {
                            locationLists = this.getSearchedLocations
                        } else {
                            locationLists = this.getFilteredLocations
                        }
                    } else if (this.search !== '' && currentTypeSubTab === 2 && 
                        this.getSearchedLocations.currentTabName === 'storage-empty') {
                        if (this.selectedWhCustomers.length === 0) {
                            locationLists = this.getSearchedLocations
                        } else {
                            locationLists = this.getFilteredLocations
                        }
                    } else if (this.search !== '' && currentTypeSubTab === 3 && 
                        this.getSearchedLocations.currentTabName === 'storage-packing') {
                        if (this.selectedWhCustomers.length === 0) {
                            locationLists = this.getSearchedLocations
                        } else {
                            locationLists = this.getFilteredLocations
                        }
                    }else if (this.search !== '' && currentTypeSubTab === 4 && 
                        this.getSearchedLocations.currentTabName === 'storage-special') {
                        if (this.selectedWhCustomers.length === 0) {
                            locationLists = this.getSearchedLocations
                        } else {
                            locationLists = this.getFilteredLocations
                        }
                    }else if (this.search !== '' && currentTypeSubTab === 5 && 
                        this.getSearchedLocations.currentTabName === 'storage-Others') {
                        if (this.selectedWhCustomers.length === 0) {
                            locationLists = this.getSearchedLocations
                        } else {
                            locationLists = this.getFilteredLocations
                        }
                    }else if (this.search !== '' && currentTypeSubTab === 6 && 
                        this.getSearchedLocations.currentTabName === 'storage-AllStockPickableStagging') {
                        if (this.selectedWhCustomers.length === 0) {
                            locationLists = this.getSearchedLocations
                        } else {
                            locationLists = this.getFilteredLocations
                        }
                    } else {
                        if (currentTypeSubTab === 0) {
                            if (this.selectedWhCustomers.length > 0 && 
                                this.getFilteredLocations.currentTabName === 'storage-all') {
                                locationLists = this.getFilteredLocations
                            } else {
                                locationLists = this.getStorageAllPagination
                            }
                        } else if (currentTypeSubTab == 1) {
                            if (this.selectedWhCustomers.length > 0 && 
                                this.getFilteredLocations.currentTabName === 'storage-filled') {
                                locationLists = this.getFilteredLocations
                            } else {
                                locationLists = this.getStorageFilledPagination
                            }
                        } else if (currentTypeSubTab == 2) {
                            if (this.selectedWhCustomers.length > 0 && 
                                this.getFilteredLocations.currentTabName === 'storage-empty') {
                                locationLists = this.getFilteredLocations
                            } else {
                                locationLists = this.getStorageEmptyPagination
                            }
                        }else if (currentTypeSubTab == 3){
                            if (this.selectedWhCustomers.length > 0 && 
                                this.getFilteredLocations.currentTabName === 'storage-packing') {
                                locationLists = this.getFilteredLocations
                            } else {
                                locationLists = this.getStoragePackingPagination
                            }
                        }else if (currentTypeSubTab == 4){
                            if (this.selectedWhCustomers.length > 0 && 
                                this.getFilteredLocations.currentTabName === 'storage-special') {
                                locationLists = this.getFilteredLocations
                            } else {
                                locationLists = this.getStorageSpecialPagination
                            }
                        }else if (currentTypeSubTab == 5){
                            if (this.selectedWhCustomers.length > 0 && 
                                this.getFilteredLocations.currentTabName === 'storage-Others') {
                                locationLists = this.getFilteredLocations
                            } else {
                                locationLists = this.getStorageOthersPagination
                            }
                        }else if (currentTypeSubTab == 6) {
                            if (this.selectedWhCustomers.length > 0 && 
                                this.getFilteredLocations.currentTabName === 'storage-AllStockPickableStagging') {
                                locationLists = this.getFilteredLocations
                            } else {
                                locationLists = this.getAllStockPickableStaggingPagination
                            }
                        }
                    }
                } else {
                    if (this.search !== '' && currentTypeSubTab === 1 && 
                        this.getSearchedLocations.currentTabName === 'storage-filled') {
                        locationLists = this.getSearchedLocations
                    } else {
                        if (this.selectedWhCustomers.length > 0 && this.getFilteredLocations.currentTabName === 'storage-filled') {
                            locationLists = this.getFilteredLocations
                        } else {
                            locationLists = this.getStorageFilledPagination
                        }
                    }
                }
            }

            return locationLists
        },
        // gateLocationsListsData() {},
		currentSelectedLocations() {
            // let getLocationsData = this.currentTab === 0 ? this.storageLocationsListsData : this.gateLocationsListsData
            let getLocationsData = this.storageLocationsListsData
            return getLocationsData.items
        },
        currentLocationsLoading() {
            if (!this.isWarehouseConnected) {
                if (this.$store.state.locations.locationsTab.typeTab == 0) {
                    if (this.$store.state.locations.locationsTab.typeSubTab == 0) {
                        return this.getStorageAllPaginationLoading
                    } else if (this.$store.state.locations.locationsTab.typeSubTab == 1) {
                        return this.getStorageFilledPaginationLoading
                    }else if (this.$store.state.locations.locationsTab.typeSubTab == 3) {
                        return this.getAllpackingLoading
                    }else if (this.$store.state.locations.locationsTab.typeSubTab == 4) {
                        return this.getAllspecialLoading
                    }else if (this.$store.state.locations.locationsTab.typeSubTab == 5) {
                        return this.getAllothersLoading
                    }else if (this.$store.state.locations.locationsTab.typeSubTab == 6) {
                        return this.getAllStockPickableStaggingLoading
                    } else {
                        return this.getStorageEmptyPaginationLoading
                    }
                } else {
                    if (this.$store.state.locations.locationsTab.typeSubTab == 0) {
                        return this.getGateAllPaginationLoading
                    } else if (this.$store.state.locations.locationsTab.typeSubTab == 1) {
                        return this.getGateFilledPaginationLoading
                    } else {
                        return this.getGateEmptyPaginationLoading
                    }
                }
            } else {
                return this.getStorageFilledPaginationLoading
            }
        },
        getTotalPage: {
            get() {
                // let getLocationsData = this.currentTab === 0 ? this.storageLocationsListsData : this.gateLocationsListsData
                let getLocationsData = this.storageLocationsListsData
                return getLocationsData.last_page
            }
        },
        getCurrentPage: {
            get() {
                // let getLocationsData = this.currentTab === 0 ? this.storageLocationsListsData : this.gateLocationsListsData
                let getLocationsData = this.storageLocationsListsData
                return getLocationsData.current_page
            },
            set() {
                return {}
            }
        }, 
        getCurrentLoadingToDisplay() {
            if (this.search === '' && this.selectedWhCustomers.length === 0) {
                return this.getHandlePageLoading
            }else if(this.selectedWhCustomers.length > 0) {
                return this.getFilteredLocationsLoading
            } else {
                return this.getSearchedLocationsLoading
            }
        },
        getHandlePageLoading() {
            let storeLocationsTab = this.$store.state.locations.locationsTab

            if (storeLocationsTab.typeTab === 0) {
                if (storeLocationsTab.typeSubTab === 0) {
                    return this.allStorageLocationsNextLoading
                } else if (storeLocationsTab.typeSubTab === 1) {
                    return this.filledStorageLocationsNextLoading
                } else if (storeLocationsTab.typeSubTab === 3) {
                    return this.packingStorageLocationsNextLoading
                } else if (storeLocationsTab.typeSubTab === 4) {
                    return this.specialStorageLocationsNextLoading
                } else if (storeLocationsTab.typeSubTab === 5) {
                    return this.othersStorageLocationsNextLoading
                }else if (storeLocationsTab.typeSubTab === 6) {
                    return this.allStockPickableStaggingLocationsNextLoading
                } else {
                    return this.emptyStorageLocationsNextLoading
                }
            } else {
                if (storeLocationsTab.typeSubTab === 0) {
                    return this.allGateLocationsNextLoading
                } else if (storeLocationsTab.typeSubTab === 1) {
                    return this.filledGateLocationsNextLoading
                } else {
                    return this.emptyGateLocationsNextLoading
                }
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.currentSelectedLocations.length === 0 && this.selectedWhCustomers.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentSelectedLocations.length === 0) {
                isShow = true
            } else if (this.selectedWhCustomers.length > 0 && this.currentSelectedLocations.length === 0) {
                isShow = false
            }

            return isShow
        },
        handleFilterComponent() {
            let isShow = true

            if (this.search == '' && this.selectedWhCustomers.length === 0 && this.currentSelectedLocations.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentSelectedLocations.length === 0) {
                isShow = true
            } else if (this.search !== '' || this.currentSelectedLocations.length > 0) {
				isShow = true
			}
            return isShow
        },
        headersComputed() {
            let defaultHeaders = this.headersDefault

            // if (this.isWarehouse3PLProvider) {
            if (this.isWarehouseConnected) {
                if (this.$store.state.locations.locationsTab.typeTab === 0) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        if (v.text === 'Contains') {
                            v.text = 'Storable Unit'
                            v.width = '15%'
                        }

                        if (v.text === 'Updated At') {
                            v.width = '18%'
                        }

                        if (v.text === '') {
                            v.width = '15%'
                        }

                        return v.text !== 'Label' && v.text !== 'Code' && v.text !== 'Gate Name' && v.text !== 'Position' && v.text !== 'Truck' && v.text !== 'Customer' && v.text !== 'Default sku’s'
                    })
                }
            } else {
                if (this.$store.state.locations.locationsTab.typeTab === 0) {
                    let storePagination = this.$store.state.locations

                    if (storePagination.locationsTab.typeSubTab == 0) {
                        defaultHeaders = defaultHeaders.filter(v => {
                            if (v.text === 'Contains') {
                                v.width = '12%'
                            }

                            if ( v.text === 'Updated At') {
                                v.width = '25%'
                            }

                            return v.text !== 'Code' && v.text !== 'Default sku’s' && v.text !== 'Gate Name' && v.text !== 'Truck' && v.text !=='Type' && v.text !== 'Reference' && v.text !== 'Customer'
                        })
                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        defaultHeaders = defaultHeaders.filter(v => {
                            if (v.text === 'Contains') {
                                v.width = '10%'
                            }

                            if ( v.text === 'Default sku’s') {
                                v.width = '15%'
                            }

                            return v.text !== 'Code' && v.text !== 'Gate Name' && v.text !== 'Truck' && v.text !=='Type' && v.text !== 'Reference' && v.text !== 'Customer'
                        })
                    } else if (storePagination.locationsTab.typeSubTab == 6) {
                        defaultHeaders = defaultHeaders.filter(v => {
                            if (v.text === 'Label') {
                                v.width = '18%'
                            }

                            if (v.text === 'Contains') {
                                v.width = '13%'
                            }

                            if (v.text === 'Updated At') {
                                v.width = '50%'
                            }

                            return  v.text !== 'Gate Name' &&  v.text !== 'Position' &&  v.text !== 'Default sku’s' &&  v.text !== 'Row' && v.text !== 'Shelf'  && v.text !== 'Truck' && v.text !=='Type' && v.text !== 'Reference' && v.text !== 'Customer' && v.text !== 'Code' && v.text !== 'Rack'
                        })
                    } else {
                        defaultHeaders = defaultHeaders.filter(v => {
                            if (v.text === 'Code') {
                                v.width = '12%'
                            }

                            if (v.text === 'Updated At') {
                                v.width = '42%'
                            }

                            return v.text !== 'Gate Name' && v.text !== 'Shelf' && v.text !== 'Row' && v.text !== 'Rack' && v.text !== 'Truck' && v.text !=='Type' && v.text !== 'Reference' && v.text !== 'Customer' && v.text !=='Default sku’s' 
                        })
                    }                        
                } 
            }        
                    
                // add the headers for gate type here
            // } 
            // else {
            //     if (this.$store.state.locations.locationsTab.typeTab === 0) {
            //         defaultHeaders = defaultHeaders.filter(v => {
            //             return v.text !== 'Gate Name' && v.text !== 'Position' && v.text !== 'Truck' && v.text !== 'Customer' && v.text !=='Type' && v.text !== 'Code'
            //         })
            //     }
                // add the headers for gate type here
            // }

            return defaultHeaders
        },
        locationTypes() {
            let locationTypes = []

            if (typeof this.getLocationTypes !== 'undefined' && this.getLocationTypes !== null) {
                if (typeof this.getLocationTypes.results !== 'undefined' && this.getLocationTypes.results.length > 0) {
                    locationTypes = this.getLocationTypes.results
                }
            }

            return locationTypes
        }
    },  
    methods: {
        ...mapActions({
            fetchLocations: 'locations/fetchLocations',
            deleteLocation: 'locations/deleteLocation',
            // storage locations
            fetchAllStorageLocations: 'locations/fetchAllStorageLocations',
            fetchFilledStorageLocations: 'locations/fetchFilledStorageLocations',
            fetchEmptyStorageLocations: 'locations/fetchEmptyStorageLocations',
            fetchAllStockPickableStaggingStorageLocations:'locations/fetchAllStockPickableStaggingStorageLocations',
            fetchSpecialStorageLocations:'locations/fetchSpecialStorageLocations',
            fetchPackingStorageLocations:'locations/fetchPackingStorageLocations',
            fetchOthersStorageLocations:'locations/fetchOthersStorageLocations',
            // gate locations
            fetchAllGateLocations: 'locations/fetchAllGateLocations',
            fetchFilledGateLocations: 'locations/fetchFilledGateLocations',
            fetchEmptyGateLocations: 'locations/fetchEmptyGateLocations',
            // tabs
            setCurrentLocationTypeTab: 'locations/setCurrentLocationTypeTab',
            setCurrentLocationTypeSubTab: 'locations/setCurrentLocationTypeSubTab',
            fetchSearchedLocation: 'locations/fetchSearchedLocation',
            setSearchedLocationsLoading: 'locations/setSearchedLocationsLoading',
            setLocationSearchedVal: 'locations/setLocationSearchedVal',
            setEmptyInventoryLocationsData: 'locations/setEmptyInventoryLocationsData',
            fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
			setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
            setLocationsFilteredVal: 'locations/setLocationsFilteredVal',
            setFilteredLocationsLoading: 'locations/setFilteredLocationsLoading',
            fetchFilterLocationsCustomers: 'locations/fetchFilterLocationsCustomers',
            fetchLocationsTypes: 'locations/fetchLocationsTypes',
            locationProductApi:'locations/locationProductApi',
            setAllLOcationProductsLists:'locations/setAllLOcationProductsLists'
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        setEmptyValues() {
            this.search = ''
            this.setLocationSearchedVal([])
            this.setLocationsFilteredVal([])
            this.selectedWhCustomers = []
            this.selectedWhCustomersCopy = []
            this.selectedWhCustomersReAssignValue = []
            this.setAllLOcationProductsLists([])
        },
        async onTypeTabChange(i) {
            this.setCurrentLocationTypeTab(i)
            this.setCurrentLocationTypeSubTab(0)
            this.setEmptyInventoryLocationsData([])
            this.setEmptyValues()

            try {
                if (this.source) this.source.cancel("cancel_previous_request")
                this.source = axios.CancelToken.source()

                let dataWithPage = { 
                    id: this.currentWarehouseSelected.id, 
                    page: 1,
                    cancelToken: this.source.token
                }
                
                if (this.$store.state.locations.locationsTab.typeTab == 0) {
                    await this.fetchAllStorageLocations(dataWithPage)
                    await this.fetchFilledStorageLocations(dataWithPage)
                    await this.fetchEmptyStorageLocations(dataWithPage)
                    await this.fetchAllStockPickableStaggingStorageLocations(dataWithPage)
                    await this.fetchPackingStorageLocations(dataWithPage)
                    await this.fetchSpecialStorageLocations(dataWithPage)
                    await this.fetchOthersStorageLocations(dataWithPage)
                } else {
                    await this.fetchAllGateLocations(dataWithPage)
                    await this.fetchFilledGateLocations(dataWithPage)
                    await this.fetchEmptyGateLocations(dataWithPage)
                }
            } catch (e) {
                if (e !== "cancel_previous_request") this.notificationError(e)
            }
        },
        async onTabChange(i) {
            this.handleScrollToTop()
            this.$store.state.locations.locationsTab.typeSubTab = i
            this.setCurrentLocationTypeSubTab(i)
            this.setEmptyValues()

            try {
                if (this.source) this.source.cancel("cancel_previous_request")
                this.source = axios.CancelToken.source()

                let storePagination = this.$store.state.locations
                let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1, cancelToken: this.source.token,tab:this.selectedState.toLowerCase(),type:'' }
                if (storePagination.locationsTab.typeTab == 0) {
                    if (storePagination.locationsTab.typeSubTab == 0) {
                        dataWithPage.page = storePagination.storageAllPagination.current_page
                        dataWithPage.type =  'overstock'
                        await this.fetchAllStorageLocations(dataWithPage)
                        storePagination.storageAllPagination.old_page = storePagination.storageAllPagination.current_page

                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        dataWithPage.page = storePagination.storageFilledPagination.current_page
                        dataWithPage.type =  'pickable'
                        await this.fetchFilledStorageLocations(dataWithPage)
                        storePagination.storageFilledPagination.old_page = storePagination.storageFilledPagination.current_page

                    } else if (storePagination.locationsTab.typeSubTab == 3) {
                        dataWithPage.page = storePagination.storagePackingPagination.current_page
                        dataWithPage.type =  'packing'
                        await this.fetchPackingStorageLocations(dataWithPage)
                        storePagination.storagePackingPagination.old_page = storePagination.storagePackingPagination.current_page

                    }else if (storePagination.locationsTab.typeSubTab == 4) {
                        dataWithPage.page = storePagination.storageSpecialPagination.current_page
                        dataWithPage.type =  'special'
                        await this.fetchSpecialStorageLocations(dataWithPage)
                        storePagination.storageSpecialPagination.old_page = storePagination.storageSpecialPagination.current_page

                    }else if (storePagination.locationsTab.typeSubTab == 5) {
                        dataWithPage.page = storePagination.storageOthersPagination.current_page
                        dataWithPage.type =  'others'
                        await this.fetchOthersStorageLocations(dataWithPage)
                        storePagination.storageOthersPagination.old_page = storePagination.storageOthersPagination.current_page

                    }else if (storePagination.locationsTab.typeSubTab == 6) {
                        dataWithPage.page = storePagination.allStockPickableStaggingPagination.current_page
                        dataWithPage.type =  'all'
                        await this.fetchAllStockPickableStaggingStorageLocations(dataWithPage)
                        storePagination.allStockPickableStaggingPagination.old_page = storePagination.allStockPickableStaggingPagination.current_page

                    } else {
                        dataWithPage.page = storePagination.storageEmptyPagination.current_page
                        dataWithPage.type =  'Staging'
                        await this.fetchEmptyStorageLocations(dataWithPage)
                        storePagination.storageEmptyPagination.old_page = storePagination.storageEmptyPagination.current_page
                    }                
                } else {
                    if (storePagination.locationsTab.typeSubTab == 0) {
                        dataWithPage.page = storePagination.gateAllPagination.current_page
                        await this.fetchAllGateLocations(dataWithPage)
                        storePagination.gateAllPagination.old_page = storePagination.gateAllPagination.current_page

                    } else if (storePagination.locationsTab.typeSubTab == 1) {
                        dataWithPage.page = storePagination.gateFilledPagination.current_page
                        await this.fetchFilledGateLocations(dataWithPage)
                        storePagination.gateFilledPagination.old_page = storePagination.gateFilledPagination.current_page

                    } else {
                        dataWithPage.page = storePagination.gateEmptyPagination.current_page
                        await this.fetchEmptyGateLocations(dataWithPage)
                        storePagination.gateEmptyPagination.old_page = storePagination.gateEmptyPagination.current_page
                    }
                }
            } catch (e) {
                if (e !== "cancel_previous_request") this.notificationError(e)
            }
        },
        formatDate(date) {
            return this.formatTimeAndDateTogether(date)
        },
        getTypeOfStorable(item, storableType) {
            let unitType = ""

            if (typeof item !== 'undefined' && item !== null) {
                if (typeof item.type !== 'undefined' && item.type !== null) {
                    unitType = item.type
                }
            }

            let finalType = 
                storableType === 'gate' ? (unitType !== '' ? unitType : 'N/A') : (unitType !== '' ? unitType : '--')            

            return finalType
        },
        async addLocation() {
            this.dialogLocation = true
            this.$nextTick(() => {
                this.editedLocationItems = Object.assign({}, this.defaultLocationItems)
                this.editedLocationIndex = -1;
            })

            if (this.locationTypes.length === 0) {
                await this.fetchLocationsTypes()
            }
        },
        closeLocation() {
            this.dialogLocation = false
            this.$nextTick(() => {
                this.editedLocationItems = Object.assign({}, this.defaultLocationItems)
                this.editedLocationIndex = -1;
            })
        },
        setLocationToDefault() {
            this.$nextTick(() => {
                this.editedLocationItems = Object.assign({}, this.defaultLocationItems)
                this.editedLocationIndex = -1;
            })
        },
        async editLocation(location) {
            this.editedLocationIndex = this.currentSelectedLocations.indexOf(location)
            
			this.editedLocationItems = Object.assign({}, this.currentSelectedLocations[this.editedLocationIndex])
            let editedLocationItems = this.editedLocationItems
            if (typeof editedLocationItems.default_sku == "string") {
				editedLocationItems.default_sku = JSON.parse(
				editedLocationItems.default_sku
				);
                editedLocationItems.default_sku = editedLocationItems.default_sku.map(sku => ({sku}))
			}
            if(editedLocationItems.label.length > 15){
                editedLocationItems.label = editedLocationItems.label.substring(0,15);
            }
            this.editedLocationItems = editedLocationItems
            this.dialogLocation = true

            if (this.locationTypes.length === 0) {
                await this.fetchLocationsTypes()
            }
        },
        deleteLoc(location) {
            this.dialogLocationDelete = true
            let storageName = location.row + location.rack + location.shelf
            this.currentLocationToDelete = {
                id: location.id,
                // name: location.type === 'storage' ? `${storageName}` : location.gate_name,
                name:storageName,
                wid: location.warehouse_id
            }
        },
        async deleteLocationConfirm() {
            if (this.currentLocationToDelete !== null) {
                try {
                    let payloadToDelete =  { location_id: this.currentLocationToDelete.id, wid: this.currentLocationToDelete.wid }
                    await this.deleteLocation(payloadToDelete)
                    this.notificationCustom('Location successfully deleted.')
                    this.closeLocationDelete()

                    // FOR STORAGE ONLY
                    let storePagination = this.$store.state.locations.locationsTab.typeSubTab === 0 ? 
                        this.$store.state.locations.storageAllPagination : 
                        (this.$store.state.locations.locationsTab.typeSubTab === 2 ? 
                        this.$store.state.locations.storageEmptyPagination : 1)

					let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
                    let dataWithPage = { id: this.currentWarehouseSelected.id, page: this.getCurrentPage,tab:this.selectedState.toLowerCase(),type:'' }

					if (storePagination.items.length === 1 && storePagination.current_page !== 1) {
						dataWithPage.page = page - 1
					}

                    if (this.search !== '') {
                        let searchedPage = typeof this.getSearchedLocations !== 'undefined' ? this.getSearchedLocations.current_page : 1

						if (this.currentSelectedLocations.length === 1 && this.getSearchedLocations.current_page !== 1) {
							searchedPage = searchedPage - 1
						}
						
						await this.fetchLocationSearchAPI(searchedPage)

                        // if current tab is all
                        if (this.$store.state.locations.locationsTab.typeSubTab === 0) {
                            dataWithPage.page = 1
                            dataWithPage.type = 'overstock'
                            await this.fetchAllStorageLocations(dataWithPage)                            
                            await this.fetchEmptyStorageLocations(dataWithPage)
                        // else if current tab is empty
                        } else if (this.$store.state.locations.locationsTab.typeSubTab === 2) {
                            dataWithPage.page = 1
                            dataWithPage.type = 'staging'
                            await this.fetchEmptyStorageLocations(dataWithPage)
                            dataWithPage.type = 'staging'                            
                            await this.fetchAllStorageLocations(dataWithPage)
                        }
                    } else {
                        // if current tab is all
                        if (this.$store.state.locations.locationsTab.typeSubTab === 0) {
                            await this.fetchAllStorageLocations(dataWithPage)

                            dataWithPage.page = 1
                            dataWithPage.type = 'overstock'
                            await this.fetchEmptyStorageLocations(dataWithPage)
                        // else if current tab is empty
                        } else if (this.$store.state.locations.locationsTab.typeSubTab === 2) {
                            dataWithPage.type = 'staging'
                            await this.fetchEmptyStorageLocations(dataWithPage)

                            dataWithPage.page = 1
                            dataWithPage.type = 'staging'
                            await this.fetchAllStorageLocations(dataWithPage)
                        }
                    }
                } catch(e) {
                    this.closeLocationDelete()
                    this.notificationError(e)
                }
            }
        },
        closeLocationDelete() {
            this.dialogLocationDelete = false
            this.currentLocationToDelete = null
        },
        clearSearched() {
            this.search = ''
            this.setLocationSearchedVal([])
            if (this.isWarehouse3PLProvider) {
				if(this.selectedWhCustomers.length > 0) {
					this.setLocationsFilteredVal([])
					this.filterAllWarehouseCustomers()
				}
			}
        },
        handleSearch() {
            if (cancel !== undefined) {
                cancel("cancel_previous_request")
            }
            this.setSearchedLocationsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { search: this.search }
                if (this.selectedWhCustomers.length > 0) {
                    return this.filterAllWarehouseCustomers()
                } else {
                    this.setSearchedLocationsLoading(true)
                    this.apiCall(data)
                }
            }, 500)
        },
        async callCertainAPIMethod(passedData, isFilter) {
            let storePagination = this.$store.state.locations
            let warehouse_id = this.currentWarehouseSelected.id

            if (storePagination.locationsTab.typeSubTab == 0) {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/common`
                passedData.tab = 'storage-all'
                passedData.params.type = 'overstock'

            } else if (storePagination.locationsTab.typeSubTab == 1) {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/common`
                passedData.tab = 'storage-filled'
                passedData.params.type = 'pickable'
            }else if (storePagination.locationsTab.typeSubTab == 3) {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/common`
                passedData.tab = 'storage-packing'
                passedData.params.type = 'packing'
            }else if (storePagination.locationsTab.typeSubTab == 4) {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/common`
                passedData.tab = 'storage-special'
                passedData.params.type = 'special'
            }else if (storePagination.locationsTab.typeSubTab == 5) {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/common`
                passedData.tab = 'storage-Others'
                passedData.params.type = 'others'
            } else if (storePagination.locationsTab.typeSubTab == 6) {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/common`
                passedData.tab = 'storage-AllStockPickableStagging'
                passedData.params.type = 'all'
            }  else {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/common`
                passedData.tab = 'storage-empty'
                passedData.params.type = 'staging'
            }
            if (!isFilter) {
                if (passedData.url !== '') {
                    try {
                        await this.fetchSearchedLocation(passedData)
                        this.setSearchedLocationsLoading(false)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedLocationsLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterLocationsCustomers(passedData)
                        this.setFilteredLocationsLoading(false)                   
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredLocationsLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            }
        },
        apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: "",
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: { 
                    search: this.search,
                    page: 1,
                    tab:this.selectedState.toLowerCase(),
                    type:'',
                    }
                }

                this.callCertainAPIMethod(passedData, false)
            } else {
                this.setLocationSearchedVal([])
            }
        },
        // if search is empty and click pagination
        async handlePageChange(page) {
            this.handleScrollToTop()
            if (page !== null) {
                let storeLocationsTab = this.$store.state.locations.locationsTab
                let storePagination = this.$store.state.locations
                let dataWithPage = { id: this.currentWarehouseSelected.id, page,tab:this.selectedState.toLowerCase(),type:''  }

                if (this.search == '' && this.selectedWhCustomers.length === 0) {
                    try {
                        if (storeLocationsTab.typeTab == 0) {
                            if (storeLocationsTab.typeSubTab === 0) {
                                if (storePagination.storageAllPagination.old_page !== page) {
                                    this.allStorageLocationsNextLoading = true
                                    dataWithPage.type ='overstock'
                                    await this.fetchAllStorageLocations(dataWithPage)
                                    storePagination.storageAllPagination.old_page = page
                                    this.allStorageLocationsNextLoading = false
                                }
                            } else if (storeLocationsTab.typeSubTab === 1) {
                                if (storePagination.storageFilledPagination.old_page !== page) {
                                    this.filledStorageLocationsNextLoading = true
                                    dataWithPage.type ='pickable'
                                    await this.fetchFilledStorageLocations(dataWithPage)
                                    storePagination.storageFilledPagination.old_page = page
                                    this.filledStorageLocationsNextLoading = false
                                }
                            }else if (storeLocationsTab.typeSubTab === 3) {
                                if (storePagination.storagePackingPagination.old_page !== page) {
                                    this.packingStorageLocationsNextLoading = true
                                    dataWithPage.type ='packing'
                                    await this.fetchPackingStorageLocations(dataWithPage)
                                    storePagination.storagePackingPagination.old_page = page
                                    this.packingStorageLocationsNextLoading = false
                                }
                            }else if (storeLocationsTab.typeSubTab === 4) {
                                if (storePagination.storageSpecialPagination.old_page !== page) {
                                    this.specialStorageLocationsNextLoading = true
                                    dataWithPage.type ='special'
                                    await this.fetchSpecialStorageLocations(dataWithPage)
                                    storePagination.storageSpecialPagination.old_page = page
                                    this.specialStorageLocationsNextLoading = false
                                }
                            }else if (storeLocationsTab.typeSubTab === 5) {
                                if (storePagination.storageOthersPagination.old_page !== page) {
                                    this.othersStorageLocationsNextLoading = true
                                    dataWithPage.type ='others'
                                    await this.fetchOthersStorageLocations(dataWithPage)
                                    storePagination.storageOthersPagination.old_page = page
                                    this.othersStorageLocationsNextLoading = false
                                }
                            } else if (storeLocationsTab.typeSubTab === 6) {
                                if (storePagination.allStockPickableStaggingPagination.old_page !== page) {
                                    this.allStockPickableStaggingLocationsNextLoading = true
                                    dataWithPage.type ='all'
                                    await this.fetchAllStockPickableStaggingStorageLocations(dataWithPage)
                                    storePagination.allStockPickableStaggingPagination.old_page = page
                                    this.allStockPickableStaggingLocationsNextLoading = false
                                }
                            } else {
                                if (storePagination.storageFilledPagination.old_page !== page) {
                                    this.emptyStorageLocationsNextLoading = true
                                    dataWithPage.type ='staging'
                                    await this.fetchEmptyStorageLocations(dataWithPage)
                                    storePagination.storageFilledPagination.old_page = page
                                    this.emptyStorageLocationsNextLoading = false
                                }
                            }
                        } else {
                            if (storeLocationsTab.typeSubTab === 0) {
                                if (storePagination.gateAllPagination.old_page !== page) {
                                    this.allGateLocationsNextLoading = true
                                    await this.fetchAllGateLocations(dataWithPage)
                                    storePagination.gateAllPagination.old_page = page
                                    this.allGateLocationsNextLoading = false
                                }
                            } else if (storeLocationsTab.typeSubTab === 1) {
                                if (storePagination.gateFilledPagination.old_page !== page) {
                                    this.filledGateLocationsNextLoading = true
                                    await this.fetchFilledGateLocations(dataWithPage)    
                                    storePagination.gateFilledPagination.old_page = page
                                    this.filledGateLocationsNextLoading = false
                                }
                            } else {
                                if (storePagination.gateFilledPagination.old_page !== page) {
                                    this.emptyGateLocationsNextLoading = true
                                    await this.fetchEmptyGateLocations(dataWithPage)
                                    storePagination.gateFilledPagination.old_page = page
                                    this.emptyGateLocationsNextLoading = false
                                }
                            }
                        }
                    } catch(e) {
                        this.notificationError(e)
                    }
                } else if (this.search !=='' && this.selectedWhCustomers.length === 0) {
                    let data = { search: this.search, page }
                    this.handlePageSearched(data)
                } else {
                    if (this.search !== '' && this.selectedWhCustomers.length > 0) {
                        let	data = {
							filter_data: this.selectedWhCustomers.map(val => val.id),
							search_data: this.search,
							wid: dataWithPage.id,
							page,
                            isPagination: true
						}
                        this.apiCallFilterAndSearchCustomer(data)
                    } else {
						if (this.search === '' && this.selectedWhCustomers.length > 0) {
							let	data = {
                                filter_data: this.selectedWhCustomers.map(val => val.id),
                                search_data: this.search,
                                wid: dataWithPage.id,
                                page,
                                isPagination: true
                            }
						    this.apiCallFilteredCustomerOnly(data)
						}
					}
                }
            }
        },
        // if search is not empty and click pagination
        async handlePageSearched(data) {
            this.handleScrollToTop()
            let searchedPagination = this.$store.state.locations.searchedLocations

            if (data !== null && this.search !== '') {
                if (searchedPagination.old_page !== data.page) {
                    let passedData = {
                        method: "get",
                        url: "",
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: { 
                            search: this.search, 
                            page: data.page,
                            tab:this.selectedState.toLowerCase(),
                            type:'',
                        }
                    }
                    
                    this.callCertainAPIMethod(passedData, false)
                }                
            } else {
                this.setLocationSearchedVal([])
            }
        },
        handleScrollToTop() {
            this.scrollTableToTop()
        },
        async fetchLocationSearchAPI(page) {
            let passedData = {
                method: "get",
                url: "",
                params: { 
                    search: this.search, 
                    page,
                    tab:this.selectedState.toLowerCase(),
                    type:'',
                }
            }

            this.callCertainAPIMethod(passedData, false)
        },        
        // call warehouse customers api
        async callWarehouseCustomerListsData(dataWithPage) {
			this.setAllWarehouseCustomerLists([])
            let parmsWarehouseCustomers = {
				id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
				page: 1,
                cancelToken: dataWithPage.cancelToken
			}

			try {				
				if (this.getAllWarehouseCustomerListsData.length === 0) {
					this.current_page_is_whcustomers = 1
					await this.fetchWarehouseCustomersDropdown(parmsWarehouseCustomers)

					if (typeof this.getWarehouseCustomersDropdown !== "undefined" && 
						this.getWarehouseCustomersDropdown !== null) {							
						if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
							this.getWarehouseCustomersDropdown.data.length !== "undefined") {
							let data = this.getWarehouseCustomersDropdown.data;
                            data.map(v => {
                                v.isChecked = false
                            })

							this.warehouseCustomerListsData = data
							this.warehouseCustomerLists = data
							this.warehouseCustomerListsCopy = data

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
                            this.fetchWarehouseCustomersLoading = false
						}
					} else {
                        this.fetchWarehouseCustomersLoading = false
						this.warehouseCustomerListsData = []
						this.warehouseCustomerLists = []
						this.warehouseCustomerListsCopy = []
					}
				}
			} catch(e) {
                this.fetchWarehouseCustomersLoading = false
				if (e !== "cancel_previous_request") this.notificationError(e)
			}
		},
		async loadMoreWarehouseCustomerData(parmsWarehouseCustomers) {
			if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
				this.current_page_is_whcustomers++
				parmsWarehouseCustomers.page = this.current_page_is_whcustomers

				try {
					await this.fetchWarehouseCustomersDropdown(parmsWarehouseCustomers)
					if (typeof this.getWarehouseCustomersDropdown !== "undefined" && 
						this.getWarehouseCustomersDropdown !== null) {							
						if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
							this.getWarehouseCustomersDropdown.data.length !== "undefined") {
							let data = this.getWarehouseCustomersDropdown.data;
                            data.map(v => {
                                v.isChecked = false
                            })

							this.warehouseCustomerListsData = [...this.warehouseCustomerListsData, ...data]
							this.warehouseCustomerLists =[...this.warehouseCustomerListsData, ...data]
							this.warehouseCustomerListsCopy =[...this.warehouseCustomerListsData, ...data]

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
						}
					} else {
						this.warehouseCustomerListsData
						this.warehouseCustomerListsCopy
						this.warehouseCustomerLists
					}
				} catch (e) {
					this.notificationError(e)
				}
			}
		},
        removeCustomerListsEmptyOnChange() {
            if (this.selectedWhCustomersCopy.length === 0) {
				this.setLocationsFilteredVal([])
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomers = []
                this.selectedWhCustomersReAssignValue = []
			}
            // this.warehouseCustomerListsCopy = this.warehouseCustomerLists
		},
        removeCustomerLists(item, removeIs) {
            if (removeIs === 'single') {
                // this.selectedWhCustomersCopy = this.selectedWhCustomersCopy.filter(val => val.id !== item.id)
                // let index = this.selectedWhCustomersCopy.indexOf(item)
                let indexLodash = _.findIndex(this.selectedWhCustomersCopy, e => (e.id == item.id))	
                
                if (indexLodash > -1){
					this.selectedWhCustomersCopy.splice(indexLodash, 1)
				}
            } else {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomersReAssignValue = []
                this.setLocationsFilteredVal([])

                if (this.search !== '') {
                    setTimeout(() => {
                        this.filterMenu = false
                        let data = { search: this.search } 
                        this.setSearchedLocationsLoading(true)
                        this.apiCall(data)  
                    }, 200);
                }
            }
            // this.selectedWhCustomers = this.selectedWhCustomersCopy
        },
        // for filter warehouse customers
        searchWarehouseCustomers() {
            if (this.searchCustomerData !== '') {
                this.warehouseCustomerLists = this.warehouseCustomerListsCopy.filter((customer) => {
                    return customer.name.toLowerCase().indexOf(this.searchCustomerData.toLowerCase()) > -1
                })
            } else {
                this.warehouseCustomerLists = this.warehouseCustomerListsCopy
                return this.warehouseCustomerLists
            }
        },
        /* eslint-disable */
        cancelSelectingWarehouseCustomers() {
            if (this.selectedWhCustomers.length === 0) {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomersReAssignValue = []
                this.setLocationsFilteredVal([])
            } else {
                // if (this.selectedWhCustomers !== this.selectedWhCustomersCopy) {
                //     this.selectedWhCustomersCopy = this.selectedWhCustomers
                // }
                this.selectedWhCustomersCopy = this.selectedWhCustomersReAssignValue.map(({dummy_value_Add_For_same_refrence,...rest}) => ({...rest}))
                this.selectedWhCustomers = this.selectedWhCustomersCopy
            }
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
            this.filterMenu = false
            this.isActiveClicked = false
        },
        /* eslint-enable */
        filterAllWarehouseCustomers(isFromDialog) {
            this.setFilteredLocationsLoading(false)            
            let data = { page: 1, isPagination: false }

            if (isFromDialog !== undefined && isFromDialog) {
                data.page = this.$store.state.locations.filteredLocations.current_page
            }

            if (this.selectedWhCustomersCopy.length > 0 && this.search === '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilteredCustomerOnly(data)
            } else if (this.selectedWhCustomersCopy.length > 0 && this.search !== '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilterAndSearchCustomer(data)
            }
            this.setLocationsFilteredVal([])
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
            this.filterMenu = false
            this.isActiveClicked = false
        },
        async apiCallFilteredCustomerOnly(data) {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)
            let searchedPagination = this.$store.state.locations.filteredLocations

            if (this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0) {
                let storePagination = this.$store.state.locations
                var searchParams = new URLSearchParams()
                searchParams.append('page', data.page)
                searchParams.append('filter', true)
                searchParams.append('tab', this.selectedState.toLowerCase())
                if(storePagination.locationsTab.typeSubTab == 0){
                    searchParams.append('type', 'overstock')   
                }else if(storePagination.locationsTab.typeSubTab == 1){
                    searchParams.append('type', 'pickable') 
                }else if(storePagination.locationsTab.typeSubTab == 6){
                    searchParams.append('type', 'all') 
                }else{
                    searchParams.append('type', 'staging')
                }
                for (var ser = 0 ; ser < final_selectedWhCustomers.length; ser++) {
					searchParams.append(`ids[]`, final_selectedWhCustomers[ser])
				}

                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }

                if (data.isPagination) {
                    if (searchedPagination.old_page !== data.page) {                        
                        this.setFilteredLocationsLoading(true)
                        this.callCertainAPIMethod(passedData, true)
                        searchedPagination.old_page = data.page
                    }
                } else {                    
                    this.setFilteredLocationsLoading(true)
                    this.callCertainAPIMethod(passedData, true)
                }
            } else {
                this.setLocationsFilteredVal([])
            }
        },
        async apiCallFilterAndSearchCustomer(data) {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)
            let searchedPagination = this.$store.state.locations.filteredLocations

            if (this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0 && this.search !== '') {
                let storePagination = this.$store.state.locations
                var searchParams = new URLSearchParams()
                searchParams.append('page', data.page)
                searchParams.append('filter', true)
                searchParams.append('search', this.search)
                searchParams.append('tab', this.selectedState.toLowerCase())
                if(storePagination.locationsTab.typeSubTab == 0){
                    searchParams.append('type', 'overstock')   
                }else if(storePagination.locationsTab.typeSubTab == 1){
                    searchParams.append('type', 'pickable') 
                }else if(storePagination.locationsTab.typeSubTab == 6){
                    searchParams.append('type', 'all') 
                }else{
                    searchParams.append('type', 'staging')
                }

                for (var ser = 0 ;ser < final_selectedWhCustomers.length; ser++) {
					searchParams.append(`ids[]`, final_selectedWhCustomers[ser])
				}

                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }

                if (data.isPagination) {
                    if (searchedPagination.old_page !== data.page) {                        
                        this.setFilteredLocationsLoading(true)
                        this.callCertainAPIMethod(passedData, true)
                        searchedPagination.old_page = data.page
                    }
                } else {                    
                    this.setFilteredLocationsLoading(true)
                    this.callCertainAPIMethod(passedData, true)
                }
            } else {
                this.setLocationsFilteredVal([])
            }
        },
        onClickFilter() {
            if (!this.filterMenu) {
                this.filterMenu = true
                var deepCopy = _.cloneDeep(this.selectedWhCustomersCopy);
				this.selectedWhCustomersReAssignValue = deepCopy
				this.selectedWhCustomersReAssignValue = this.selectedWhCustomersReAssignValue.map(v => ({ ...v, dummy_value_Add_For_same_refrence: v.name }))
            }
        },
        clickOutsideFilter() {
            if (this.isActiveClicked) {
                this.filterMenu = false
                this.cancelSelectingWarehouseCustomers()
                this.isActiveClicked = false
            }
        },
        clearAllFilter() {
            if (this.selectedWhCustomers.length > 0) {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
                this.setLocationsFilteredVal([])
            }
        },
        fetchLocationsTabsAndTypes(tabs){
            if(tabs){
                let dataWithPage = { 
                    id: this.currentWarehouseSelected.id,
                    page: 1, cancelToken: this.source.token,
                    tab:this.selectedState.toLowerCase(),
                    type:'' 
                }
                let storePagination = this.$store.state.locations
                
                if(storePagination.locationsTab.typeSubTab == 0){
                    dataWithPage.type = 'overstock'
                    this.fetchAllStorageLocations(dataWithPage)
                }else if(storePagination.locationsTab.typeSubTab == 1){
                   dataWithPage.type = 'pickable'
                   this.fetchFilledStorageLocations(dataWithPage)
                }else if(storePagination.locationsTab.typeSubTab == 3){
                   dataWithPage.type = 'packing'
                   this.fetchPackingStorageLocations(dataWithPage)
                }else if(storePagination.locationsTab.typeSubTab == 4){
                   dataWithPage.type = 'special'
                   this.fetchSpecialStorageLocations(dataWithPage)
                }else if(storePagination.locationsTab.typeSubTab == 5){
                   dataWithPage.type = 'others'
                   this.fetchOthersStorageLocations(dataWithPage)
                }else if(storePagination.locationsTab.typeSubTab == 6){
                   dataWithPage.type = 'all'
                   this.fetchAllStockPickableStaggingStorageLocations(dataWithPage)
                }else{
                    dataWithPage.type = 'staging'
                    this.fetchEmptyStorageLocations(dataWithPage)
                }
            }

        },
        async locationProductsForDefaultSku(dataWithPage){
            try{
                if(this.getAllLocationProductListsDropdownData.length == 0){
                this.current_page_is = 1
                let payload = {
                    page:this.current_page_is,
                    cancelToken:dataWithPage.cancelToken
                } 
                await this.locationProductApi(payload)
                if (typeof this.getLocationsProductsForDeafultSku !== 'undefined' && 
                        this.getLocationsProductsForDeafultSku !== null && 
                        typeof this.getLocationsProductsForDeafultSku.products !== 'undefined' && 
                        Array.isArray(this.getLocationsProductsForDeafultSku.products) &&
                        this.getLocationsProductsForDeafultSku.products.length > 0) {
                            
                        this.productsListsData = this.getLocationsProductsForDeafultSku.products.map(value => {
							return {
							    sku: value.sku,
                                id:value.id,
                                name:value.name
							}
                        })

                        this.lastDataCheck = this.productsListsData
                        if (this.current_page_is < this.getLocationsProductsForDeafultSku.last_page) {

                            this.loadMoreProducts(payload)
                        }
                        
                        this.setAllLOcationProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData = []
                        this.lastDataCheck = []
                    }
                }
            }catch(e){
                this.notificationError(e)
            }
        },
        async loadMoreProducts(payload){
            if (this.current_page_is < this.getLocationsProductsForDeafultSku.last_page) {
				this.current_page_is++
				let sendData = {
					page:this.current_page_is,
                    cancelToken:payload.cancelToken
				}
				try {
					await this.locationProductApi(sendData)

                    if (typeof this.getLocationsProductsForDeafultSku !== 'undefined' && 
                        this.getLocationsProductsForDeafultSku !== null && 
                        typeof this.getLocationsProductsForDeafultSku.products !== 'undefined' && Array.isArray(this.getLocationsProductsForDeafultSku.products) &&
                        this.getLocationsProductsForDeafultSku.products.length > 0) {
                            
                        let newloaddata = this.getLocationsProductsForDeafultSku.products.map(value => {
								return {
                                sku: value.sku,
                                id:value.id,
                                name:value.name
								}     
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]

                        if (this.current_page_is < this.getLocationsProductsForDeafultSku.last_page) {
                            this.loadMoreProducts(payload)
                        }

                        this.setAllLOcationProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        getSkuWithName(sku){
            let results = {} 
            if(this.productsListsData.length){
                this.productsListsData.filter(val => {
                if(val.sku == sku){
                    return results = val
                }
            })
            }
            
            return results
        },
    },
    async mounted() {
        let currentInventoryTabName = this.$router.history.current.query.tab
        let checkWarehouseTypeId = this.currentWarehouseSelected !== null ? this.currentWarehouseSelected.warehouse_type_id : null

        if (this.$store.state.page.currentInventoryTab === 2 && 
            typeof currentInventoryTabName !== 'undefined' && 
            currentInventoryTabName === 'Locations') {
                
            try {
                this.source = axios.CancelToken.source()
                let dataWithPage = { 
                    id: this.currentWarehouseSelected.id,
                    page: 1, 
                    tab:this.selectedState.toLowerCase(),
                    type:'overstock',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                }
                if (!this.isWarehouseConnected) {
                    this.setCurrentLocationTypeSubTab(0)
                    await this.fetchAllStorageLocations(dataWithPage)
                    this.setAllLOcationProductsLists([])
                    this.locationProductsForDefaultSku(dataWithPage)
                } else {
                    this.setCurrentLocationTypeSubTab(1)
                    await this.fetchFilledStorageLocations(dataWithPage)                    
                }
                if (checkWarehouseTypeId !== null && checkWarehouseTypeId === 6) {
                    if (!this.isWarehouseConnected) {
                        await this.callWarehouseCustomerListsData(dataWithPage)
                    }
                }
            } catch(e) {
                if (e !== "cancel_previous_request") this.notificationError(e)
            }            
        }
    },
    beforeDestroy(){
		if (cancel !== undefined) {
			cancel("cancel_previous_request")
		}
	},
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/location/locationTable.scss';

.button-hover-default-sku {
    .v-btn__content {
        opacity: 1 !important;
        font-size: 12px !important;
        color:#0171A1 !important;
    }
}
</style>