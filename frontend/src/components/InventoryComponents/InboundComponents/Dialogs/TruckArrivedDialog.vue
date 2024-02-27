<template>
    <v-dialog v-model="dialogArrived" max-width="500px" content-class="dialog-truck-confirm" :retain-focus="false" persistent>
        <v-card>
            <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                <!-- <v-card-title>
                     <span class="headline"> Confirm Arrival </span>
                    <v-spacer></v-spacer>
                    <v-btn icon dark class="btn-close" @click="close">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>                    
                </v-card-title> --> 

                <v-card-text>
                    <div class="truck-arrived-column">
                        <!-- <div class="truck-arrived-customer mb-3">
                            <p class="truck-arrived-title">TRUCK</p>
                            <v-text-field 
                                placeholder="Enter Truck Name" 
                                outlined 
                                class="text-fields"
                                v-model="editedItem.name"
                                :rules="rules"
                                hide-details="auto">
                            </v-text-field>
                        </div>

                        <div class="truck-arrived-customer mb-3">
                            <p class="truck-arrived-title">REFERENCE</p>
                            <v-text-field 
                                placeholder="Enter Reference" 
                                outlined 
                                class="text-fields"
                                v-model="editedItem.ref_no"
                                :rules="rules"
                                hide-details="auto">
                            </v-text-field>
                        </div> -->

                        <div class="info-icon-truck mt-2 mb-3">
                            <img src="@/assets/icons/question-blue.svg" width="48px" height="48px">
                        </div>
                        
                        <div class="new-truck-arrival-wrapper">
                            <p>Confirm Arrival</p>
                            <span>Do you confirm that the truck has arrived at the warehouse?</span>
                        </div>
                    </div>
                </v-card-text>
                
                <v-card-actions>
                    <button class="btn-blue" @click.stop="confirmTruckArrived" text :disabled="loading">
                        <span>{{ loading ? 'Marking...' : 'Mark Arrived'}}</span>
                    </button>

                    <button class="btn-white" text @click="close" :disabled="loading">
                        Cancel
                    </button>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import globalMethods from '../../../../utils/globalMethods'

export default {
    name: 'TruckArrivedDialog',
    props: [
        'editedItemData', 
        'dialogData', 
        'loading', 
        'linkData', 
        'isFetchSingleInbound', 
        'isWarehouse3PLProvider'
    ],
    data: () => ({
        valid: true,
        rules: [
            (v) => !!v || "Input is required."
        ],
    }),
    computed: {
         ...mapGetters({
            getTruckArrivedLoading: 'inbound/getTruckArrivedLoading',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab'
        }),
        dialogArrived: {
            get () {
                return this.dialogData
            },
            set (value) {
                if (!value) {
                    this.$emit('update:dialogData', false)
                } else {
                    this.$emit('update:dialogData', true)
                }
            }
        },
        editedItem: {
            get () {
                return this.editedItemData
            },
            set (value) {
                console.log(value);
            }
        },
    },
    mounted() {},
    updated() {},
    methods: {
        ...mapActions({
            fetchSingleInbound: 'inbound/fetchSingleInbound',
            truckArrived: 'inbound/truckArrived',
            fetchPendingInbounds: 'inbound/fetchPendingInbounds',
            fetchPendingReceivingInbounds: 'inbound/fetchPendingReceivingInbounds',
        }),
        ...globalMethods,
        close() {
            this.$emit('close')
            this.$refs.form.resetValidation()
            // this.editedItem.truck = ''
            // this.editedItem.ref_no = ''
        },
        async callPendingInbound() {
            let storeInboundTab = this.$store.state.inbound
            let dataWithPage = { id: this.linkData.warehouse_id, page: 1 }

            if (typeof this.getCurrentInboundTab !== 'undefined') {
                if (this.isWarehouse3PLProvider) {
                    dataWithPage.page = storeInboundTab.pendingReceiveInboundPagination.current_page
                    await this.fetchPendingReceivingInbounds(dataWithPage)                    
                } else {
                    dataWithPage.page = storeInboundTab.pendingInboundPagination.current_page
                    await this.fetchPendingInbounds(dataWithPage)
                }
            }            
        },
        async confirmTruckArrived() {
            try {
                let payload = {
                    // name: this.editedItem.name,
                    // ref_no: this.editedItem.ref_no,
                    warehouse_id: this.linkData.warehouse_id !== '' ? this.linkData.warehouse_id : null,
                    inbound_id: this.linkData.inbound_id !== '' ? this.linkData.inbound_id : null
                }

                let fetchSingleInboundPayload = { wid: this.linkData.warehouse_id, iid: this.linkData.inbound_id }

                await this.truckArrived(payload).then(async () => {
                    this.notificationMessage('Truck Arrived Successfully.')                        
                    this.close() 

                    if (this.isFetchSingleInbound) {                            
                        await this.fetchSingleInbound(fetchSingleInboundPayload)
                        await this.callPendingInbound()
                    } else {
                        this.callPendingInbound()
                    }
                })                       
            } catch (e) {
                this.notificationError(e)
                this.close()
            }           
        },
    },
     
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/inbound/truckArrivedDialog.scss';
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';
</style>