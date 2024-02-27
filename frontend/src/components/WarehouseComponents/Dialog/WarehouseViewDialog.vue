<template>
    <v-dialog v-model="dialogView" max-width="880px" content-class="view-warehouse" persistent :retain-focus="false">
        <v-card class="dialog-wrapper">
            <v-card-title>
                <span class="headline">
                    <span class="warehouse-title"> 
                        {{  currentWarehouse !== null && 
                            currentWarehouse.name !== "" && 
                            currentWarehouse.name !== null 
                            ? currentWarehouse.name : '--'
                        }}
                    </span>
                    <span class="warehouse-type ml-2"> 
                        {{  
                            !isWarehouseConnected ?
                                currentWarehouse !== null && 
                                getWarehouseType(currentWarehouse.warehouse_type) !== "" && 
                                getWarehouseType(currentWarehouse.warehouse_type) !== null 
                                ? getWarehouseType(currentWarehouse.warehouse_type) : '--'
                            : 'Connected 3PL'
                        }}
                    </span>
                </span>

                <div class="header-actions">
                    <button icon dark class="btn-close" @click="close">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </div>
            </v-card-title>

            <v-card-text>
                <div class="view-box-wrapper" v-if="!getWarehouse3PLDataLoading">
                    <div class="row">
                        <div class="col-sm-6 first-col">
                            <div class="box big">
                                <div class="box-info">
                                    <div class="box-title">Country</div>
                                    <div class="box-data">
                                        {{  currentWarehouse !== null && 
                                            currentWarehouse.country !== "" && 
                                            currentWarehouse.country !== null 
                                            ? currentWarehouse.country : '--'
                                        }}
                                    </div>
                                </div>

                                <div class="box-info">
                                    <div class="box-title">State</div>
                                    <div class="box-data">
                                        {{  currentWarehouse !== null && 
                                            currentWarehouse.state !== "" && 
                                            currentWarehouse.state !== null 
                                            ? currentWarehouse.state : '--'
                                        }}
                                    </div>
                                </div>

                                <!-- <div class="box-info">
                                    <div class="box-title">City</div>
                                    <div class="box-data">
                                        {{  currentWarehouse !== null && 
                                            currentWarehouse.city !== "" && 
                                            currentWarehouse.city !== null 
                                            ? currentWarehouse.city : '--'
                                        }}
                                    </div>
                                </div> -->

                                <div class="box-info">
                                    <div class="box-title">Address</div>
                                    <div class="box-data">
                                        {{  currentWarehouse !== null && 
                                            currentWarehouse.address !== "" && 
                                            currentWarehouse.address !== null 
                                            ? currentWarehouse.address : '--'
                                        }}
                                    </div>
                                </div>

                                <div class="box-info">
                                    <div class="box-title">Zipcode</div>
                                    <div class="box-data">
                                        {{  currentWarehouse !== null && 
                                            currentWarehouse.zipcode !== "" && 
                                            currentWarehouse.zipcode !== null 
                                            ? currentWarehouse.zipcode : '--'
                                        }}
                                    </div>
                                </div>

                                <div class="box-info">
                                    <div class="box-title">Phone</div>
                                    <div class="box-data">
                                        {{  currentWarehouse !== null && 
                                            currentWarehouse.phone !== "" && 
                                            currentWarehouse.phone !== null 
                                            ? currentWarehouse.phone : '--'
                                        }}
                                    </div>
                                </div>

                                <div class="box-info">
                                    <div class="box-title">Email</div>
                                    <div class="box-data" v-if="!isWarehouseConnected">
                                        <span class="mb-0" 
                                            v-for="(email, index) in currentWarehouse.email_address" 
                                            :key="index">

                                            <span>{{ email }}</span>
                                            <template v-if="index + 1 < currentWarehouse.email_address.length">, <br/></template>
                                        </span>
                                    </div>

                                    <div class="box-data" v-else>
                                        <span class="mb-0" 
                                            v-for="(email, index) in connectedWarehouseEmails" 
                                            :key="index">

                                            <span>{{ email }}</span>
                                            <template v-if="index + 1 < connectedWarehouseEmails.length">, <br/></template>
                                        </span>
                                    </div>
                                </div>

                                <div class="box-info" v-if="isWarehouseConnected">
                                    <div class="box-title">Description</div>
                                    <div class="box-data description">
                                        {{ connectedWarehouseDescription }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 second-col">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box small">
                                        <div class="box-info-next">
                                            <div class="box-title">Cartons</div>
                                            <div class="box-data" v-if="!isWarehouseConnected">
                                                {{  currentWarehouse !== null && 
                                                    currentWarehouse.total_cartons !== "" && 
                                                    currentWarehouse.total_cartons !== null 
                                                    ? currentWarehouse.total_cartons : '--'
                                                }}
                                            </div>

                                            <div class="box-data" v-else>
                                                {{  connectedCartonsCount }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="box small">
                                        <div class="box-info-next">
                                            <div class="box-title">Units</div>
                                            <div class="box-data" v-if="!isWarehouseConnected">
                                                {{  currentWarehouse !== null && 
                                                    currentWarehouse.total_units !== "" && 
                                                    currentWarehouse.total_units !== null 
                                                    ? currentWarehouse.total_units : '--'
                                                }}
                                            </div>

                                            <div class="box-data" v-else>
                                                {{  connectedUnitsCount }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box small">
                                        <div class="box-info-next">
                                            <div class="box-title">Products</div>
                                            <div class="box-data" v-if="!isWarehouseConnected">
                                                {{  currentWarehouse !== null && 
                                                    currentWarehouse.total_products !== "" && 
                                                    currentWarehouse.total_products !== null 
                                                    ? currentWarehouse.total_products : '--'
                                                }}
                                            </div>

                                            <div class="box-data" v-else>
                                                {{  connectedProductsCount }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="box small">
                                        <div class="box-info-next">
                                            <div class="box-title">Last Updated</div>
                                            <div class="box-data" v-if="!isWarehouseConnected">
                                                {{  currentWarehouse !== null && 
                                                    getDateFormat(currentWarehouse.updated_at) !== "" && 
                                                    getDateFormat(currentWarehouse.updated_at) !== null 
                                                    ? getDateFormat(currentWarehouse.updated_at) : '--'
                                                }}
                                            </div>

                                            <div class="box-data" v-else>
                                                {{ getDateFormat(connectedLastUpdatedDate) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 d-flex justify-center" style="min-height: 150px;" v-if="getWarehouse3PLDataLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate>
					</v-progress-circular>
				</div>
            </v-card-text>

            <v-card-actions v-if="!getWarehouse3PLDataLoading">
                <v-btn class="btn-blue" text @click="editItem(currentWarehouse)">
                    <span>
                        {{ !isWarehouseConnected ? 'Edit' : 'Edit Info' }}
                    </span>
                </v-btn>
                <v-btn class="btn-white btn-delete-view-dialog" text @click="deleteItem(currentWarehouse)" v-if="!isWarehouseConnected">
                    Delete
                </v-btn>
                <v-btn class="btn-white delete" text @click="disconnectWarehouse(currentWarehouse)" v-if="isWarehouseConnected">
                    Disconnect
                </v-btn>
            </v-card-actions>
        </v-card>

        <ConfirmDialog :dialogData.sync="showDisconnectDialog">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2> Disconnect Warehouse </h2>
            </template>

            <template v-slot:dialog_content>
                <p>
                    Do you want to disconnect 
                    <span style="font-family: 'Inter-SemiBold', sans-serif !important;">
                        '{{ warehouseDisconnectData !== null ? warehouseDisconnectData.name : '' }}'
                    </span> 
                    as a connected 3PL warehouse? If disconnected, you would need to ask the service provider to get reconnected.  
                </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-red" @click="confirmDisconnectWarehouse" text :disabled="getWarehouseDisconnectLoading">                   
                    {{ getWarehouseDisconnectLoading ? 'Disconnecting...' : 'Disconnect' }}
                </v-btn>

                <v-btn class="btn-white" text @click="cancelDisconnect" :disabled="getWarehouseDisconnectLoading">
                    Cancel
                </v-btn>
            </template>
        </ConfirmDialog>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import moment from 'moment' 
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue'
import globalMethods from '../../../utils/globalMethods'

export default {
    name: 'WarehouseViewDialog',
    props: ['dialog', 'currentWarehouse', 'editedIndex', 'isWarehouseConnected'],
    components: {
        ConfirmDialog
    },
    data: () => ({
        showDisconnectDialog: false,
        warehouseDisconnectData: null
    }),
    computed: {
        ...mapGetters({
            getWarehouseDeleteLoading: 'warehouse/getWarehouseDeleteLoading',
            getActiveId: 'warehouse/getActiveId',
            getWarehouse3PLData: 'warehouse/getWarehouse3PLData',
            getWarehouse3PLDataLoading: 'warehouse/getWarehouse3PLDataLoading',
            getWarehouseDisconnectLoading: 'warehouse/getWarehouseDisconnectLoading'
        }),
        dialogView: {
            get () {
                return this.dialog
            },
            set (value) {
                this.$emit('update:dialog', value)
            }
        },
        connectedWarehouseEmails() {
            let emails = []
            if (typeof this.getWarehouse3PLData !== 'undefined' && this.getWarehouse3PLData !== null) {
                if (typeof this.getWarehouse3PLData.data !== 'undefined') {
                    emails = JSON.parse(this.getWarehouse3PLData.data.emails)
                }
            }
            return emails
        },
        connectedWarehouseDescription() {
            let description = ''
            if (typeof this.getWarehouse3PLData !== 'undefined' && this.getWarehouse3PLData !== null) {
                if (typeof this.getWarehouse3PLData.data !== 'undefined') {
                    description = this.getWarehouse3PLData.data.description
                }
            }
            return description
        },
        connectedCartonsCount() {
            let total_cartons = 0
            if (typeof this.getWarehouse3PLData !== 'undefined' && this.getWarehouse3PLData !== null) {
                if (typeof this.getWarehouse3PLData.total_cartons !== 'undefined') {
                    total_cartons = this.getWarehouse3PLData.total_cartons
                }
            }
            return total_cartons
        },
        connectedProductsCount() {
            let total_products = 0
            if (typeof this.getWarehouse3PLData !== 'undefined' && this.getWarehouse3PLData !== null) {
                if (typeof this.getWarehouse3PLData.total_products !== 'undefined') {
                    total_products = this.getWarehouse3PLData.total_products
                }
            }
            return total_products
        },
        connectedUnitsCount() {
            let total_units = 0
            if (typeof this.getWarehouse3PLData !== 'undefined' && this.getWarehouse3PLData !== null) {
                if (typeof this.getWarehouse3PLData.total_units !== 'undefined') {
                    total_units = this.getWarehouse3PLData.total_units
                }
            }
            return total_units
        },
        connectedLastUpdatedDate() {
            let updated_at = ''
            if (typeof this.getWarehouse3PLData !== 'undefined' && this.getWarehouse3PLData !== null) {
                if (typeof this.getWarehouse3PLData.data !== 'undefined') {
                    updated_at = this.getWarehouse3PLData.data.updated_at
                }
            }
            return updated_at
        }
    },
    methods: {
        ...mapActions({
            disconnectConnectedWarehouse: 'warehouse/disconnectConnectedWarehouse',
            fetchWarehouse: 'warehouse/fetchWarehouse',
        }),
        ...globalMethods,
        deleteItem(warehouse) {
            this.$emit('deleteWarehouse', warehouse)
        },
        editItem(warehouse) {
            this.$emit('update:editedIndex', 1)
            this.$emit('editWarehouse', warehouse)
            this.close()
        },
        getWarehouseType(data) {
            if (data !== null) {
                if (data === 'own' || data === 'Private Warehouse') {
                    return 'Own Facility'
                } else if (data === '3PL Provider') {
                    return '3PL Service Provider'
                } else {
                    return 'Manual 3PL'
                }
            } else {
                // set to Own Facility as default
                return 'Own Facility'
            }
        },
        getDateFormat(date) {
            if (date !== null) {
                return moment(date).format('MM/DD/YYYY')
            } else {
                return ''
            }
        },
        close() {
            this.$emit('close')
        },
        closeDelete() {
            this.$emit('closeDelete')
        },
        disconnectWarehouse(item) {
            this.showDisconnectDialog = true
            this.warehouseDisconnectData = item
        },
        cancelDisconnect() {
            this.showDisconnectDialog = false
            this.warehouseDisconnectData = null
        },
        async confirmDisconnectWarehouse() {
            let payload = {
                id: this.warehouseDisconnectData.id,
                warehouse_cid: this.warehouseDisconnectData.warehouse_customer_id
            }

            try {
                await this.disconnectConnectedWarehouse(payload)
                this.notificationCustom('Warehouse has been disconnected.')

                await this.fetchWarehouse()
                await this.getFirstWarehouse()
                this.cancelDisconnect()
                this.close()
                this.$store.dispatch("page/setCurrentInventoryTab", 0)
            } catch(e) {
                this.notificationCustom(e)
                console.log(e)
            }
        },
        getFirstWarehouse() {
            this.$emit('getFirstWarehouse')
        }
    },
    updated() {}
}
</script>

<style lang="scss">
/* @import '../../assets/css/warehouse_styles/viewWarehouse.css'; */
@import '../../../assets/scss/pages_scss/warehouse/viewWarehouse.scss';
</style>
