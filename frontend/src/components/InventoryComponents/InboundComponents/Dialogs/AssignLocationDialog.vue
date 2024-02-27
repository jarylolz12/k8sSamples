<template>
    <v-dialog 
        v-model="dialog" max-width="470" 
        content-class="assign-dialog location-dialog" 
        :retain-focus="false" 
        v-resize="onResize" 
        scrollable
        @click:outside="close">

        <v-card>
            <v-card-title>
                <span class="headline">
                    {{ isHasLocationAssigned ? `Update Location (${fromComponent})` : `Assign to Location (${fromComponent})`}}
                </span>

                <v-spacer></v-spacer>
                <v-btn icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="assign-info-wrapper">
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="assign-info">
                        <div class="assign-second-column">
                            <div class="assign-employee-section-wrapper">
                                <div class="assign-employee-header mb-5" v-if="editedItem.length > 0">
                                    <v-row v-if="editedItem.length === 1">
                                        <v-col cols="6">
                                            <div class="assign-div">
                                                <div class="assign-title font-medium">ORDER ID</div>
                                                <div class="assign-data">{{ editedItem[0].order_id }}</div>
                                            </div>

                                            <div class="assign-div mt-1">
                                                <div class="assign-title font-medium">REFERENCE</div>
                                                <div class="assign-data">
                                                    {{ editedItem[0].ref_no !== null ? editedItem[0].ref_no : '--' }}
                                                </div>
                                            </div>

                                            <div class="assign-div mt-1">
                                                <div class="assign-title font-medium">TRUCK</div>
                                                <div class="assign-data">
                                                    {{ editedItem[0].name !== null ? editedItem[0].name : '--' }}
                                                </div>
                                            </div>
                                        </v-col>

                                        <v-col cols="6">
                                            <div class="assign-div">
                                                <div class="assign-title font-medium">NO OF SKU's</div>
                                                <div class="assign-data">{{ editedItem[0].no_of_sku }}</div>
                                            </div>

                                            <div class="assign-div mt-1">
                                                <div class="assign-title font-medium">CARTON</div>
                                                <div class="assign-data">{{ editedItem[0].no_of_cartons }}</div>
                                            </div>

                                            <div class="assign-div mt-1">
                                                <div class="assign-title font-medium">UNITS</div>
                                                <div class="assign-data">{{ editedItem[0].no_of_units }}</div>
                                            </div>
                                        </v-col>
                                    </v-row>

                                    <div v-if="editedItem.length > 1">
                                        <v-row>
                                            <v-col cols="6">
                                                <div class="assign-div">
                                                    <div class="assign-title font-medium">NO OF ORDERS</div>
                                                    <div class="assign-data">{{ editedItem.length }}</div>
                                                </div>

                                                <div class="assign-div mt-1">
                                                    <div class="assign-title font-medium">CARTON</div>
                                                    <div class="assign-data">{{ getTotalDisplay(editedItem, 'carton') }}</div>
                                                </div>
                                            </v-col>

                                            <v-col cols="6">
                                                <div class="assign-div">
                                                    <div class="assign-title font-medium">NO OF SKU's</div>
                                                    <div class="assign-data">{{ getTotalDisplay(editedItem, 'skus') }}</div>
                                                </div>

                                                <div class="assign-div mt-1">
                                                    <div class="assign-title font-medium">UNITS</div>
                                                    <div class="assign-data">{{ getTotalDisplay(editedItem, 'units') }}</div>
                                                </div>
                                            </v-col>
                                        </v-row>

                                        <v-row class="mt-1">
                                            <v-col cols="12" sm="12" class="pt-0">
                                                <div class="assign-div">
                                                    <div class="assign-title font-medium" style="width: 25%;">ORDER LIST</div>
                                                    <div class="assign-data" style="width: 75%;">
                                                        <span v-for="(item, index) in editedItem" :key="index">
                                                            <span>{{ item.order_id }}</span>
                                                            <span v-if="index+1 < editedItem.length">, </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </v-col>
                                        </v-row>
                                    </div>
                                </div>

                                <div class="assign-employee-tables-wrapper warehouse-inbound-locations">
                                    <v-autocomplete
                                        :items="warehouseLocations"
                                        v-model="assignToLocation.warehouse_location"
                                        class="select-product shrink employee"
                                        item-text="label"
                                        item-value="id"
                                        :placeholder="getStagingLocationAssignLoading ? 'Fetching locations...' : 'Select warehouse locations'"
                                        outlined
                                        :menu-props="{ contentClass: 'product-lists-items assign-employee-lists assign-location', bottom: true, offsetY: true, closeOnContentClick: false }"
                                        hide-details="auto"
                                        :disabled="getStagingLocationAssignLoading">

                                        <!-- <template v-slot:prepend-item>
                                            <v-list-item class="search-input-field pa-0">
                                                <v-text-field 
                                                    v-model="searchWarehouseLocations" 
                                                    placeholder="Search warehouse locations"
                                                    hide-details="auto"
                                                    class="text-fields select-items product-search-contact-field"
                                                    outlined
                                                    prepend-inner-icon="mdi-magnify"
                                                    @input="handleSearchWarehouseLocations">
                                                </v-text-field>
                                            </v-list-item>
                                        </template> -->

                                        <template v-slot:item="{ item }">
                                            <div class="location-item-wrapper">
                                                <p class="location-name mb-0">{{ item.label }}</p>
                                            </div>
                                        </template>
                                    </v-autocomplete>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="receive-button-actions">
                <button class="btn-blue" text @click="assignAction" :disabled="getAssignLocationLoading">
                    <span v-if="!isHasLocationAssigned">{{ getAssignLocationLoading ? 'Assigning...' : 'Assign' }}</span>
                    <span v-if="isHasLocationAssigned">{{ getAssignLocationLoading ? 'Updating...' : 'Update' }}</span>
                </button>

                <button class="btn-white" text @click="close" :disabled="getAssignLocationLoading">
                    Cancel
                </button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import globalMethods from '../../../../utils/globalMethods'
import _ from 'lodash'

export default {
    name: 'AssignLocationDialog',
    props: [
        'dialogData', 
        'editedItemData',
        'fromComponent',
        'isHasLocationAssigned'
    ],
    components: {},
    data: () => ({
        current_page: 1,
        isMobile: false,
        valid: true,
        searchWarehouseLocations: '',
        assignToLocation: {
            warehouse_location: ''
        },
        allLocations: [],
        allLocationsCopy: []
    }),
    watch: {
        dialog(value, oldValue) {
			if (!value) {
				this.assignToLocation = {
                    warehouse_location: ''
                }
			} else if (value && !oldValue) {
				if (this.editedItem.location === null) {
					this.assignToLocation = {
                        warehouse_location: ''
                    }
				} 
                // else {
					// let employeeListsNames = []

                    // if (this.editedItem.length === 1) {
                    //     let current_task = this.editedItem[0]

                    //     this.assignEmployeeData.forEach((item) => { 
                    //         item.value !== current_task.tasktype ? item.isShow = false : item.isShow = true
                    //     })

                    //     current_task.task_employees.map(v => {
                    //         employeeListsNames.push({ ...v })
                    //     })

                    //     let findCurrentTaskSelected = _.find(this.assignEmployeeData, e => current_task.tasktype === e.value)
                    //     if (findCurrentTaskSelected !== undefined) {
                    //         findCurrentTaskSelected.employees = employeeListsNames
                    //     }
                    // }

                    // console.log(this.editedItem);
				// }
			}
        },
    },
    computed: {
        ...mapGetters({ 
            getStagingLocationAssign: 'employees/getStagingLocationAssign',
            getStagingLocationAssignLoading: 'employees/getStagingLocationAssignLoading',
            getAssignLocationLoading: 'employees/getAssignLocationLoading',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
        }),
        dialog: {
            get() {
                return this.dialogData
            },
            set(value) {
                this.$emit('update:dialogData', value)
            }
        },
        editedItem: {
            get() {
                let value = this.editedItemData
                // remove duplicate items in array
                value = _.uniqBy(value, 'id')
                return value
            },
            set(value) {
                this.$emit('update:editedItemData', value)
            }
        },
        warehouseLocations() {
            let locations = []

            if (typeof this.getStagingLocationAssign !== 'undefined' && this.getStagingLocationAssign !== null) {
                locations = this.getStagingLocationAssign.data
            }

            return locations
        },
        assignLocationTemplate() {
            if (this.editedItem.length !== 0) {
                let inbounds = []
                this.editedItem.map(v => { inbounds.push(v.id) })
                
                return {
                    inbounds,
                    location_id: this.assignToLocation.warehouse_location
                }
            } else {
                return {}
            }
        },
        isWarehouse3PLProvider() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 6) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouseConnected() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.is_own !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.is_own === 0) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouseOwn() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 1) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouse3PL() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 3) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
    },
    methods: {
        ...mapActions({ 
            assignLocation: 'employees/assignLocation',
            fetchSingleInbound: 'inbound/fetchSingleInbound',
            fetchPendingInbounds: 'inbound/fetchPendingInbounds',
            fetchFloorInbounds: 'inbound/fetchFloorInbounds',
            fetchPendingReceivingInbounds: 'inbound/fetchPendingReceivingInbounds',
            setCurrentInboundTab: 'inbound/setCurrentInboundTab',
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
            this.assignToLocation = {
                warehouse_location: ''
            }
        },
        getTotalDisplay(items, isFor) {
            let total = 0

            if (isFor === 'carton') {
                total = items.reduce((a, val) => a + val.no_of_cartons, 0)
            } else if (isFor === 'skus') {
                total = items.reduce((a, val) => a + val.no_of_sku, 0)
            } else if (isFor === 'units') {
                total = items.reduce((a, val) => a + val.no_of_units, 0)
            }

            return total
        },
        async callInboundAPIAccordingToStatus(status, i) {
            let warehouse = this.$store.state.warehouse.currentWarehouse
            let storeInboundTab = this.$store.state.inbound
            let dataWithPage = { id: warehouse.id, page: 1 } 

            try {
                if (status === 'pending') { // call pending API
                    dataWithPage.page = storeInboundTab.pendingInboundPagination.current_page
                    await this.fetchPendingInbounds(dataWithPage)
                    storeInboundTab.pendingInboundPagination.old_page = storeInboundTab.pendingInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                    
                } else if (status === 'floor') { // call floor api here
                    dataWithPage.page = storeInboundTab.floorInboundPagination.current_page
                    await this.fetchFloorInbounds(dataWithPage)
                    storeInboundTab.floorInboundPagination.old_page = storeInboundTab.floorInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                    
                } else if (status === 'receive_pending') { // call pending receive api here
                    dataWithPage.page = storeInboundTab.pendingReceiveInboundPagination.current_page
                    await this.fetchPendingReceivingInbounds(dataWithPage)
                    storeInboundTab.pendingReceiveInboundPagination.old_page = storeInboundTab.pendingReceiveInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                }
            } catch(e) {
                this.notificationError(e)
            }
        },
        processAPIInboundFetch() {
            if (this.isWarehouse3PLProvider) {
                if (this.getCurrentInboundTab === 1) {
                    this.callInboundAPIAccordingToStatus('receive_pending', 1)
                } else if (this.getCurrentInboundTab === 2) {
                    this.callInboundAPIAccordingToStatus('floor', 2)
                }
            } else {
                if (this.isWarehouseOwn) {
                    if (this.getCurrentInboundTab === 0) {
                        this.callInboundAPIAccordingToStatus('pending', 0)
                    } else if (this.getCurrentInboundTab === 1) {
                        this.callInboundAPIAccordingToStatus('floor', 1)
                    }
                }
            }
        },
        async assignAction() {
            if (this.assignToLocation.warehouse_location !== '') {
                await this.assignLocation(this.assignLocationTemplate)
                let message = this.isHasLocationAssigned ? 'Location has been updated.' : 'Location has been assigned.'
                this.notificationMessage(message)
                this.close()

                let inbound_id = new URL(location.href).searchParams.get('inboundid')
                let warehouse_id = new URL(location.href).searchParams.get('warehouseid')
                
                if (inbound_id !== null && warehouse_id !== null) {
                    let singlePayload = { wid: warehouse_id, iid: inbound_id }
                    await this.fetchSingleInbound(singlePayload)
                }

                this.processAPIInboundFetch()
            }
        }
    },
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/inbound/assignEmployeeDialog.scss';
</style>
