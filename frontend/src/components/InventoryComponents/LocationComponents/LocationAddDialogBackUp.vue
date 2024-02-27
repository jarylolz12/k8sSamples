<template>
    <v-dialog v-model="dialog" max-width="440px" content-class="location-dialog" :retain-focus="false" persistent scrollable>
        <v-card>            
            <v-card-title>
                <span class="headline">
                    {{ formTitle }} 
                </span>

                <v-spacer></v-spacer>						

                <v-btn icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="location-info-wrapper">
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="location-info">
                        <div class="location-first-column">
                            <v-row>
                                <v-col cols="12" sm="12" class="pb-0">
                                    <div class="location-floor mb-1">
                                        <p class="location-title">Type</p>
                                        <v-select
                                            :items="allLocationTypes"
                                            class="select-product shrink text-capitalize"
                                            item-text="type"
                                            item-value="type"
                                            placeholder="Select Location Type"
                                            outlined
                                            v-model="locationItems.type"
                                            hide-details="auto"
                                            :menu-props="{ contentClass: 'product-lists-items location-lists', bottom: true, offsetY: true, closeOnContentClick: true }">>
                                        </v-select>
                                    </div>
                                </v-col>

                                <v-col cols="12" sm="12" class="pb-0" v-if="locationItems.type !== 'gate'">
                                    <div class="location-floor mb-1">
                                        <p class="location-title">ROW/AISLE</p>
                                        <v-text-field 
                                            placeholder="Enter Aisle Number" 
                                            outlined 
                                            type="text"
                                            v-model="locationItems.row"
                                            class="text-fields"
                                            hide-details="auto"
                                            @keydown="inputRestrictSpecialChar($event)"
                                            :rules="rules">
                                        </v-text-field>
                                    </div>
                                </v-col>

                                <v-col cols="12" sm="12" class="pb-0" v-if="locationItems.type !== 'gate'">
                                    <div class="location-floor mb-1">
                                        <p class="location-title">RACK/COLUMN</p>
                                        <v-text-field 
                                            placeholder="Enter Rack Number" 
                                            outlined 
                                            type="text"
                                            v-model="locationItems.rack"
                                            class="text-fields"
                                            hide-details="auto"
                                            @keydown="inputRestrictSpecialChar($event)"
                                            :rules="rules">
                                        </v-text-field>
                                    </div>
                                </v-col>

                                <v-col cols="12" sm="12" class="pb-0" v-if="locationItems.type !== 'gate'">
                                    <div class="location-floor" :class="isWarehouse3PLProvider ? 'mb-1' : 'mb-4'">
                                        <p class="location-title">SHELF/FLOOR</p>
                                        <v-text-field 
                                            placeholder="Enter Shelf Number" 
                                            outlined 
                                            type="text"
                                            class="text-fields"
                                            v-model="locationItems.shelf"
                                            :rules="rules"
                                            hide-details="auto"
                                            @keydown="inputRestrictSpecialChar($event)">
                                        </v-text-field>
                                    </div>
                                </v-col>

                                <v-col cols="12" sm="12" class="pb-0" v-if="locationItems.type == 'gate'">
                                    <div class="location-floor mb-1">
                                        <p class="location-title">GATE NAME</p>
                                        <v-text-field 
                                            placeholder="Enter Gate Name" 
                                            outlined 
                                            type="text"
                                            class="text-fields"
                                            v-model="locationItems.gate_name"
                                            :rules="rules"
                                            hide-details="auto"
                                            @keydown="inputRestrictSpecialChar($event)">
                                        </v-text-field>
                                    </div>
                                </v-col>

                                <v-col cols="12" sm="12" class="pb-0" v-if="locationItems.type == 'gate'">
                                    <div class="location-floor mb-3">
                                        <p class="location-title"> POSITION </p>
                                        <v-text-field 
                                            placeholder="Enter Position Number" 
                                            outlined 
                                            type="text"
                                            class="text-fields"
                                            v-model="locationItems.position"
                                            :rules="rules"
                                            hide-details="auto"
                                            @keydown="inputRestrictSpecialChar($event)">
                                        </v-text-field>
                                    </div>
                                </v-col>

                                <v-col cols="12" sm="12" v-if="isWarehouse3PLProvider">
                                    <div class="location-floor mb-3">
                                        <p class="location-title">Warehouse Customer <span>(Optional)</span></p>
                                        <v-autocomplete
                                            class="text-fields select-items"
                                            v-model="locationItems.warehouse_customer_id"
                                            :items="warehouseCustomerLists"
                                            item-text="name"
                                            item-value="id"
                                            outlined
                                            hide-details="auto"
                                            placeholder="Select Warehouse Customer"
                                            :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true, closeOnContentClick: true }"
                                            clearable>

                                            <template v-slot:item="{ item }">
                                                <div class="option-items" style="padding: 14px 0;">
                                                    <div class="name-address-item">
                                                        <p class="name mb-1" style="color: #4a4a4a;"> 
                                                            {{ item.name }} 
                                                        </p>

                                                        <p class="address mb-0" style="color: #6D858F; font-size: 12px;"> 
                                                            {{ item.address }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </template>
                                        </v-autocomplete>
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="location-button-actions">
                <div>
                    <button class="btn-blue" text @click="save" :disabled="loading || loadingAndAdd">
                        <span v-if="editedIndex === -1">{{ loading ? 'Adding...' : 'Add Location'}}</span>
                        <span v-if="editedIndex > -1">{{ loading ? 'Updating...' : 'Update'}}</span>
                    </button>
                </div>

                <!-- <button class="btn-blue" text @click="save" :disabled="loading || loadingAndAdd">
                    <span v-if="editedIndex === -1">{{ loading ? 'Adding...' : 'Add Location'}}</span>

                    <span v-if="editedIndex > -1">{{ loading ? 'Updating...' : 'Update'}}</span>
                </button> -->

                <button class="btn-white" 
                    v-if="editedIndex === -1" 
                    text 
                    @click="saveAndAdd" 
                    :disabled="loadingAndAdd || loading">
                    
                    <span>
                        {{ loadingAndAdd ? 'Saving...' : 'Save & Add Another'}}                            
                    </span>
                </button>

                <button class="btn-white" text @click="close"
                    style="margin-right: 0 !important;" 
                    :disabled="loading || loadingAndAdd">
                    Cancel
                </button>
            </v-card-actions>            
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
// import moment from 'moment'
import globalMethods from '../../../utils/globalMethods'
// import _ from 'lodash'
// import axios from "axios"

export default {
    name: 'LocationAddDialog',
    props: [
        'dialogLocation', 
        'editedLocationIndex', 
        'editedLocationItems', 
        'currentWarehouseSelected', 
        'pagination', 
        'currentLocationTypeTab', 
        'searchVal', 
        'currentSelectedLocations', 
        'locationTypes',
        'selectedWhCustomersCopy',
        'selectedWhCustomers'
    ],
    components: {},
    data: () => ({
        current_page: 1,
        valid: true,
        headers: [
			{
				text: 'PRODUCT',
				align: 'start',
				sortable: false,
				value: 'product_id',
				fixed: true,
				width: "200px"
			},
			{
				text: 'CARTON',
				align: 'end',
				sortable: false,
				value: 'carton_count',
				fixed: true,
				width: "100px"
			},
			{
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "100px"
			},
            {
				text: '',
				align: 'end',
				sortable: false,
				value: 'actions',
				fixed: true,
				width: "30px"
			},
		],
        rules: [
            (v) => !!v || "Input is required."
        ],
        loading: false,
        loadingAndAdd: false,
    }),
    watch: {},
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getCreateLocationsLoading: 'locations/getCreateLocationsLoading',
            getLocation: 'locations/getLocation',
            poBaseUrlState: 'products/poBaseUrlState',
            getSearchedLocations: 'locations/getSearchedLocations',
            getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            getFilteredLocations: 'locations/getFilteredLocations',
        }),
        isWarehouse3PLProvider() {
            if (this.currentWarehouseSelected !== null) {
                if (typeof this.currentWarehouseSelected.warehouse_type_id !== 'undefined' && 
                    this.currentWarehouseSelected.warehouse_type_id === 6) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        formTitle () {
            return this.editedIndex === -1 ? 'Add Location' : 'Edit Location'
        },
        dialog: {
            get() {
                return this.dialogLocation
            },
            set(value) {
                this.$emit('update:dialogLocation', value)
            }
        },
        editedIndex: {
            get() {
                return this.editedLocationIndex
            },
            set(value) {
                this.$emit('update:editedLocationIndex', value)
            }
        },
        locationItems: {
            get() {
                return this.editedLocationItems
            },
            set(value) {
                this.$emit('update:editedLocationItems', value)
            }
        },
        locationAddTemplate() {
            let { type, shelf, row, rack, gate_name, position, warehouse_customer_id } = this.locationItems

            if (type !== 'gate') {
                return {
                    // type: 'storage',
                    type,
                    shelf: shelf !== null ? shelf : '',
                    row: row !== null ? row : '',
                    rack: rack !== null ? rack : '',
                    customer_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : '',
                    warehouse_id: this.currentWarehouseSelected.id,
                    warehouse_customer_id
                }
            } else {
                return {
                    type,
                    gate_name: gate_name !== null ? gate_name : '',
                    position: position !== null ? position : '',
                    customer_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : '',
                    warehouse_id: this.currentWarehouseSelected.id,
                    warehouse_customer_id
                }
            }
        },
        currentPagination: {
            get() {
                return this.pagination
            },
            set(value) {
                this.$emit('update:pagination', value)
            }
        },
        warehouseCustomerLists() {
            let data = []

            if (typeof this.getWarehouseCustomersDropdown !== "undefined" && this.getWarehouseCustomersDropdown !== null) {
                if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
                    this.getWarehouseCustomersDropdown.data.length !== "undefined") {
                    data = this.getWarehouseCustomersDropdown.data;

                    data.map((value) => {
                        if (value.address === null) {
                            value.address = "";
                        }

                        if (value.phone === null) {
                            value.phone = "";
                        }

                        if (value.emails === null) {
                            value.emails = "";
                        }
                    });
                }
            }

            return data
        },
        allLocationTypes: {
            get() {
                return this.locationTypes
            },
            set() {
                return {}
            }
        },
        currentLocationTypeSelected: {
            get() {
                return this.currentLocationTypeTab
            },
            set(value) {
                this.$emit('update:currentLocationTypeTab', value)
            }
        }
    },
    methods: {
        ...mapActions({
            createLocations: 'locations/createLocations',
            fetchLocations: 'locations/fetchLocations',
            updateLocation: 'locations/updateLocation',
            fetchAllStorageLocations: 'locations/fetchAllStorageLocations',
            fetchFilledStorageLocations: 'locations/fetchFilledStorageLocations',
            fetchEmptyStorageLocations: 'locations/fetchEmptyStorageLocations',
            // gate locations
            fetchAllGateLocations: 'locations/fetchAllGateLocations',
            fetchFilledGateLocations: 'locations/fetchFilledGateLocations',
            fetchEmptyGateLocations: 'locations/fetchEmptyGateLocations',
            // tabs
            setCurrentLocationTypeTab: 'locations/setCurrentLocationTypeTab',
            setCurrentLocationTypeSubTab: 'locations/setCurrentLocationTypeSubTab',
            fetchSearchedLocation: 'locations/fetchSearchedLocation',
            setSearchedLocationsLoading: 'locations/setSearchedLocationsLoading',
            setFilteredLocationsLoading: 'locations/setFilteredLocationsLoading',
            fetchFilterLocationsCustomers: 'locations/fetchFilterLocationsCustomers',
        }),
        ...globalMethods,
        inputRestrictSpecialChar(e) {
            if (/^\W$/.test(e.key)) {
                if (e.key !== '-' && e.key !== ' ') {
                    e.preventDefault();
                }
            }
        },
        async save() {
            this.$refs.form.validate()

            if (this.$refs.form.validate()) {
                let storeLocationsTab = this.$store.state.locations.locationsTab
                
                try {
                    this.loading = true
                    let dataWithPage = {
						id: this.currentWarehouseSelected.id,
						page: this.currentPagination
					}

                    if (this.editedIndex === -1) {
                        await this.createLocations(this.locationAddTemplate)
                        this.notificationMessage('Location has been added.')
                        this.loading = false
                        
                        this.setCurrentLocationTypeTab(storeLocationsTab.typeTab)
                        // this.setCurrentLocationTypeSubTab(0)
                        
                        if (storeLocationsTab.typeTab === 0) { // if current type tab is storage tab
                            if (storeLocationsTab.typeSubTab == 0 || storeLocationsTab.typeSubTab == 1) { 
                                // if current sub tab is all or filled
                                await this.fetchAllStorageLocations(dataWithPage)

                                dataWithPage.page = 1
                                await this.fetchEmptyStorageLocations(dataWithPage)                        
                            } else if (storeLocationsTab.typeSubTab == 2) { // if current sub tab is empty
                                await this.fetchEmptyStorageLocations(dataWithPage)

                                dataWithPage.page = 1
                                await this.fetchAllStorageLocations(dataWithPage)
                            }
                        } // add code here for gate v2

                        this.close()
                    } else {
                        let updateTemplate = this.locationAddTemplate
                        updateTemplate.location_id = this.locationItems.id
                        await this.updateLocation(updateTemplate)
                        this.notificationMessage('Location has been updated.')
                        this.loading = false
                        this.close()

                        if (storeLocationsTab.typeTab === 0) { // if current type tab is storage tab
                            await this.callOnSyncMethods(dataWithPage)
                            
                            if (storeLocationsTab.typeSubTab == 0) { // if current tab is all                                
                                await this.fetchAllStorageLocations(dataWithPage)
                                
                                dataWithPage.page = 1
                                if (this.locationItems.storable_units.length !== 0) {
                                    await this.fetchFilledStorageLocations(dataWithPage)
                                } else {
                                    await this.fetchEmptyStorageLocations(dataWithPage)
                                }
                            } else if (storeLocationsTab.typeSubTab == 1) { // if current sub tab is filled
                                await this.fetchFilledStorageLocations(dataWithPage)
                                
                                dataWithPage.page = 1                                
                                await this.fetchAllStorageLocations(dataWithPage)
                            } else if (storeLocationsTab.typeSubTab == 2) { // if current sub tab is empty
                                await this.fetchEmptyStorageLocations(dataWithPage)
                                
                                dataWithPage.page = 1                                
                                await this.fetchAllStorageLocations(dataWithPage)
                            }
                        } // add code here for gate v2
                    }
                } catch(e) {
                    this.loading = false
                    this.notificationError(e)
                    console.log(e);
                }
            }
        },
        async saveAndAdd() {
            this.$refs.form.validate()

            if (this.$refs.form.validate()) {
                let storeLocationsTab = this.$store.state.locations.locationsTab

                try {
                    this.loadingAndAdd = true
                    let dataWithPage = {
						id: this.currentWarehouseSelected.id,
						page: this.currentPagination
					}

                    if (this.editedIndex === -1) {
                        await this.createLocations(this.locationAddTemplate)
                        this.loadingAndAdd = false
                        this.notificationMessage('Location has been added.')
                        this.setLocationToDefault()

                        if (storeLocationsTab.typeTab === 0) { // if current type tab is storage tab
                            if (storeLocationsTab.typeSubTab == 0 || storeLocationsTab.typeSubTab == 1) { 
                                // if current sub tab is all or filled
                                await this.fetchAllStorageLocations(dataWithPage)

                                dataWithPage.page = 1
                                await this.fetchEmptyStorageLocations(dataWithPage)                        
                            } else if (storeLocationsTab.typeSubTab == 2) { // if current sub tab is empty
                                await this.fetchEmptyStorageLocations(dataWithPage)

                                dataWithPage.page = 1
                                await this.fetchAllStorageLocations(dataWithPage)
                            }
                        } // add code here for gate v2
                    }
                } catch (e) {
                    this.loadingAndAdd = false
                    this.notificationError(e)
                }
            }            
        },
        close() {
            if (typeof this.$refs.form !== 'undefined') {
                if (typeof this.$refs.form.resetValidation() !== 'undefined') {
                    this.$refs.form.resetValidation()
                }
            }

            this.$emit('close')
        },
        setLocationToDefault() {
            this.$emit('setLocationToDefault')

            if (typeof this.$refs.form !== 'undefined') {
                if (typeof this.$refs.form.resetValidation() !== 'undefined') {
                    this.$refs.form.resetValidation()
                }
            }
        },
        async callOnSyncMethods(dataWithPage) {
            if (!this.isWarehouse3PLProvider) {
                if (typeof this.searchVal !== 'undefined' && this.searchVal !== '') { // if search is not empty
                    if (typeof this.getSearchedLocations !== 'undefined') {
                        let searchedPage = typeof this.getSearchedLocations.current_page !== 'undefined' ? 
                            this.getSearchedLocations.current_page : 1

                        if (this.currentSelectedLocations.length === 1 && searchedPage !== 1) {
                            searchedPage = searchedPage - 1
                        }

                        await this.fetchLocationSearchAPI(searchedPage)
                        dataWithPage.page = 1
                    }
                } 
            } else {
                if (this.selectedWhCustomersCopy.length === 0) {
                    if (typeof this.searchVal !== 'undefined' && this.searchVal !== '') { // if search is not empty
                        if (typeof this.getSearchedLocations !== 'undefined') {
                            let searchedPage = typeof this.getSearchedLocations.current_page !== 'undefined' ? 
                                this.getSearchedLocations.current_page : 1

                            if (this.currentSelectedLocations.length === 1 && searchedPage !== 1) {
                                searchedPage = searchedPage - 1
                            }

                            await this.fetchLocationSearchAPI(searchedPage)
                            dataWithPage.page = 1
                        }
                    }
                } else {
                    if (this.selectedWhCustomersCopy.length > 0) {
                        if (typeof this.getFilteredLocations !== 'undefined') {
                            let searchedPage = typeof this.getFilteredLocations.current_page !== 'undefined' ? 
                                this.getFilteredLocations.current_page : 1

                            if (this.currentSelectedLocations.length === 1 && searchedPage !== 1) {
                                searchedPage = searchedPage - 1
                            }

                            await this.fetchLocationSearchAPI(searchedPage)
                            dataWithPage.page = 1
                        }
                    }
                }
            }
        },
        async fetchLocationSearchAPI(page) {
            let storePagination = this.$store.state.locations
            let warehouse_id = this.currentWarehouseSelected.id

            var searchParams = new URLSearchParams()

            searchParams.append('page', page)
            searchParams.append('search', this.searchVal)

            let passedData = {
                method: "get",
                url: "",
                params: searchParams
            }            

            if (storePagination.locationsTab.typeSubTab == 0) {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/all`
                passedData.tab = 'storage-all'
            } else if (storePagination.locationsTab.typeSubTab == 1) {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/filled`
                passedData.tab = 'storage-filled'
            } else {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/locations/empty`
                passedData.tab = 'storage-empty'
            }
            
            if (this.selectedWhCustomersCopy.length === 0) {
                if (passedData.url !== '') {
                    try {
                        await this.fetchSearchedLocation(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedLocationsLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.$emit('filterAllWarehouseCustomers')
            }
        }
    },
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/location/addLocationDialog.scss';
</style>
