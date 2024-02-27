<template>
    <div class="shipment-info schedule-options" v-if="typeof getScheduleOptions.results!=='undefined' && getScheduleOptions.results.length > 0 && filteredScheduleOptions.length > 0">        
        <v-divider class="shipment-info-divider"></v-divider>
        <v-container fluid class="cont-fluid-wrapper">
            <h3 class="schedule-title">Scheduling Options</h3>

             <div class="no-data-preloader" v-if="getScheduleOptionsLoading">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate
                    v-if="getScheduleOptionsLoading">
                </v-progress-circular>
            </div>
            <div v-if="!getScheduleOptionsLoading && getScheduleOptions !== 'undefined' && getScheduleOptions !== null && getDetails.shipment_status !== 'Expired'">
                <!-- IF ALREADY SELECTED SCHEDULE -->
                <div v-if="getScheduleOptions.current_selected_schedule !== null">
                    <ShipmentScheduleView 
                        :selectedData="getScheduleOptions.current_selected_schedule" />
                </div>

                <!-- IF STILL NEEDS TO CHOOSE SCHEDULE -->
                <div v-if="getScheduleOptions.current_selected_schedule == null && getScheduleOptions.results !== null">
                    <ShipmentScheduleChoose 
                        :scheduleOptionsData="filteredScheduleOptions"
                        :shipmentId="shipment_id" />
                </div>
            </div>

            <div class="expired-shipment" v-if="!getScheduleOptionsLoading && getDetails.shipment_status == 'Expired'">
                <p>The schedules have expired. Please request again to get possible options.</p>

                <button class="btn-blue" @click.stop="requestSchedule(getDetails.id)" :disabled="getNewSchedulesLoading">
                    {{ getNewSchedulesLoading ? 'Requesting...' : 'Request Schedules' }}
                </button>
            </div>
        </v-container>
    </div>
</template>

<script>
import ShipmentScheduleChoose from '@/components/ShipmentScheduleChoose';
import ShipmentScheduleView from '@/components/ShipmentScheduleView';
import { mapActions, mapGetters } from "vuex";
import globalMethods from '../utils/globalMethods'

export default {
    name: 'ShipmentSchedulingOptions',
    props: ['getDetails', 'isMobile'],
    components: {
        ShipmentScheduleChoose,
        ShipmentScheduleView
    },
    data: () => ({
        shipment_id: null,
        loading: true,
        schedule_id: null,
        loadingNewScheds: false,
        currentIdRequestScheds: null
    }),
    methods: {
        ...mapActions(["fetchScheduleOptions", "requestNewSchedule"]),
        ...globalMethods,
        async requestSchedule(id) {
            this.currentIdRequestScheds = id

            try {
                this.loadingNewScheds = true
                await this.requestNewSchedule(id)
                this.notificationMessage('Schedule has been requested.')
                this.loadingNewScheds = false
                this.currentIdRequestScheds = null
            } catch (e) {
                this.loadingNewScheds = false
                this.currentIdRequestScheds = null
                this.notificationError(e)
            }
        }
    },
    async mounted() {       
        this.shipment_id = this.$route.params.id

        try {            
            await this.fetchScheduleOptions(this.shipment_id)
            this.loading = false
        } catch(e) {
            console.log(e)
            this.loading = false
        }
    },
    computed: {
        ...mapGetters(["getScheduleOptions", "getScheduleOptionsLoading", "getNewSchedulesLoading"]),
        filteredScheduleOptions() {

            if ( typeof this.getScheduleOptions.results!=='undefined' ) {
                
                //get schedules
                let scheduleOptions = this.getScheduleOptions.results
                let validSchedules = []
                scheduleOptions.map(so => {
                    if ( so.eta!=='' && so.etd!=='' && typeof so.sell_rates!=='undefined' && so.sell_rates!=='' && so.sell_rates!==null && so.sell_rates.length > 0) {
                        validSchedules.push(so)
                    }
                })
                return validSchedules

            } else
                return []
        }
    },
    updated() {}
}
</script>

<style lang="scss">
/* @import '../assets/css/shipments_styles/shipmentSchedule.css'; */
@import '../assets/scss/pages_scss/shipment/shipmentSchedule.scss';
</style>