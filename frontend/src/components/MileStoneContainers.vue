<template>
	<div class="shipment-info milestone-container-wrapper">
		<v-container fluid class="cont-fluid-wrapper" 
            v-if="isTrackingShipment === 0 && getDetails.containers !== 'undefined' && 
                  getDetails.containers !== null && 
                  getDetails.containers.length > 0 || (isTrackingShipment==1 && !getMilestonesLoading && getMilestonesAttributes==null && getDetails.containers !== 'undefined' && 
                  getDetails.containers !== null && 
                  getDetails.containers.length > 0)">
            <h3 class="container-title">Containers</h3>
            <div class="container-grid" v-if="!isMobile">
                <v-data-table
                    :page.sync="page"
                    :headers="containerHeader"
                    :items="itemsWithIndex"
                    :items-per-page="itemsPerPage"
                    hide-default-footer
                    class="elevation-1 container-table">

                    <template v-slot:[`item.size`]="{ item }">
                        <span>{{ getContainerSizes(item.size) }}</span>
                    </template>

                    <template v-slot:[`item.cbm`]="{ item }">
                        <span>{{ item.cbm }} CBM</span>
                    </template>

                    <template v-slot:[`item.kg`]="{ item }">
                        <span>{{ item.kg }} KG</span>
                    </template>
                </v-data-table>
            </div>

            <div class="container-grid-mobile" v-if="isMobile">
                <div class="container-items-mobile" v-for="(item, index) in getDetails.containers" :key="index">
                    <div class="ribbon-container" v-if="getDetails.containers.length > 0">
                        {{ getDetails.containers.length > 9 
                            ? `${index + 1}` 
                            : `0${index + 1}`
                        }}
                    </div>

                    <div class="container-data-wrapper">
                        <h3 class="supplier-name" style="margin-bottom: 10px; margin-right: 15%;" v-if="item.container_num !== 'undefined'"> 
                            {{ item.container_num !== 'undefined' ? item.container_num : "Not specified" }} 
                        </h3>

                        <v-row style="margin-top: 0">
                            <div class="container-data" v-if="item.size !== 'undefined' && item.size !== null && item.size !== 0">
                                <p class="heading">SIZE</p>
                                <p class="heading-content">
                                    {{ getContainerSizes(item.size) }}
                                </p>
                            </div>
                            <div class="container-data" v-if="item.cbm !== 'undefined' && item.cbm !== null">
                                <p class="heading">Volume</p>
                                <p class="heading-content">
                                    {{ item.cbm + ' CBM' }}
                                </p>
                            </div>
                            <div class="container-data" v-if="item.kg !== 'undefined' && item.kg !== null">
                                <p class="heading">Weight</p>
                                <p class="heading-content">
                                    {{ item.kg + ' KG' }}
                                </p>
                            </div>
                            <div class="container-data" v-if="item.cartons !== 'undefined' && item.cartons !== null ">
                                <p class="heading">Carton Count</p>
                                <p class="heading-content">
                                    {{ item.cartons }}
                                </p>
                            </div>
                        </v-row>
                    </div>
                </div>
            </div>
        </v-container>

        <!-- FOR TRACKING SHIPMENTS CONTAINERS - To be updated from milestones -->
        <div v-if="isTrackingShipment === 1" class="tracking-containers">
            <div v-if="getMilestonesLoading">                
                <v-divider class="shipment-info-divider"></v-divider>
                <div class="preloader">
                    <v-progress-circular :size="40" color="#0171a1" indeterminate>
                    </v-progress-circular>

                    <p>Fetching tracking container details...</p>
                </div>
            </div>            

            <div v-if="!getMilestonesLoading">
                <div v-if="getTrackingContainerDetails !== 'undefined' && 
                    getTrackingContainerDetails !== null && 
                    getTrackingContainerDetails.length > 0" class="has-tracking-containers">
                    
                    <v-divider class="shipment-info-divider"></v-divider>
                    <h3 class="container-title" >Containers</h3>

                    <v-container fluid class="cont-fluid-wrapper">                
                        <div class="container-grid" v-if="!isMobile">            
                            <v-data-table
                                :page.sync="page"
                                :headers="trackingContainerHeader"
                                :items="trackingItemsWithIndex"
                                :items-per-page="itemsPerPage"
                                hide-default-footer
                                class="elevation-1 container-table">

                                <template v-slot:[`item.size`]="{ item }">
                                    <span>{{ getTrackingSize(item) }}</span>
                                </template>

                                <!-- <template v-slot:[`item.cbm`]="{ item }">
                                    <span>{{ item.volume }} CBM</span>
                                </template> -->

                                <template v-slot:[`item.kg`]="{ item }">
                                    <span>{{ convertlbstokg(item.weight) }} KG</span>
                                </template>
                            </v-data-table>
                        </div>

                        <div class="container-grid-mobile" v-if="isMobile">
                            <div class="container-items-mobile" v-for="(item, index) in trackingItemsWithIndex" :key="index">
                                <div class="ribbon-container" v-if="trackingItemsWithIndex.length > 0">
                                    {{ trackingItemsWithIndex.length > 9 
                                        ? `${index + 1}` 
                                        : `0${index + 1}`
                                    }}
                                </div>

                                <div class="container-data-wrapper">
                                    <h3 class="supplier-name" style="margin-bottom: 10px; margin-right: 15%;" v-if="item.container_num !== 'undefined'"> 
                                        {{ item.container_num !== 'undefined' ? item.container_num : "Not specified" }} 
                                    </h3>

                                    <v-row style="margin-top: 0">
                                        <div class="container-data" v-if="item.size !== 'undefined' && item.size !== null">
                                            <p class="heading">SIZE</p>
                                            <p class="heading-content">
                                                <span>{{ getTrackingSize(item) }}</span>
                                            </p>
                                        </div>
                                        <!-- <div class="container-data" v-if="item.volume !== 'undefined' && item.volume !== null">
                                            <p class="heading">Volume</p>
                                            <p class="heading-content">
                                                <span>{{ item.volume }} CBM</span>
                                            </p>
                                        </div> -->
                                        <div class="container-data" v-if="item.weight !== 'undefined' && item.weight !== null">
                                            <p class="heading">Weight</p>
                                            <p class="heading-content">
                                                <span>{{ convertlbstokg(item.weight) }} KG</span>
                                            </p>
                                        </div>
                                        <!-- <div class="container-data" v-if="item.cartons !== 'undefined' && item.cartons !== null ">
                                            <p class="heading">Carton Count</p>
                                            <p class="heading-content">
                                                {{ item.cartons }}
                                            </p>
                                        </div> -->
                                    </v-row>
                                </div>
                            </div>
                        </div>
                    </v-container>
                </div>                
            </div>
        </div>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import _ from 'lodash'

export default {
	props: ['getDetails', 'isMobile'],
	data: () => ({
		page: 1,
        pageCount: 0,
        itemsPerPage: 35,
		items: [],
        trackingItems: [],
        container_sizes: [],
        containerHeader: [
            {
                text: "#SL",
                align: "start",
                sortable: false,
                value: "index",
                width: "6%", 
                fixed: true
            },
            {
                text: "NUMBER",
                value: "container_num",
                align: "start",
                sortable: false,
                width: "20%", 
                fixed: true
            },
            {
                text: "SIZE",
                value: "size",
                align: "right",
                sortable: false,
                width: "15%", 
                fixed: true
            },
            {
                text: "VOLUME",
                value: "cbm",
                align: "right",
                sortable: false,
                width: "18%", 
                fixed: true
            },
            {
                text: "WEIGHT",
                value: "kg",
                align: 'right',
                sortable: false,
                width: "18%", 
                fixed: true
            },
            {
                text: "CARTON COUNT",
                value: "cartons",
                align: 'right',
                sortable: false,
                width: "18%", 
                fixed: true
            },
        ],
        trackingContainerHeader: [
            {
                text: "#SL",
                align: "start",
                sortable: false,
                value: "index",
                width: "6%", 
                fixed: true
            },
            {
                text: "NUMBER",
                value: "container_num",
                align: "start",
                sortable: false,
                width: "20%", 
                fixed: true
            },
            {
                text: "SIZE",
                value: "size",
                align: "right",
                sortable: false,
                width: "15%", 
                fixed: true
            },
            {
                text: "WEIGHT",
                value: "kg",
                align: 'right',
                sortable: false,
                width: "18%", 
                fixed: true
            },
        ],
        isTrackingShipment: 0,
	}),
	methods: {
        ...mapActions(['fetchTerms', 'fetchContainers']),
		getContainerSizes(id) {
            if(id) {
                let findSizeValue = _.find(this.container_sizes, (e) => (e.id == id))

                if (typeof findSizeValue !== 'undefined') {
                    if (findSizeValue.name !== 'undefined') {
                        return findSizeValue.name
                    }
                } else {
                    return ''
                }
            }
        },
        convertlbstokg(value) {
            if (value !== null || value !== '') {
                return (value / 2.2046).toFixed(2)
            } else {
                return '--'
            }
        },
        getTrackingSize(item) {
            let size = '0'

            if(item !== null && item.size !== null) {

                if (item.height == 'standard') {
                    size = item.size + "'GP"
                } else {
                    size = item.size + "'HQ"
                }
            }

            return size
        }
    },
    created() {},
    computed: {
        ...mapGetters([
            'getTrackingContainerDetails', 
            'getMilestonesLoading', 
            'getMilestonesAttributes',
            "getShipmentContainerSizes",
        ]),
        itemsWithIndex() {
            return this.items.map((items, index) => ({
                ...items,
                index: index >= 9 ? index + 1 : `0${index + 1}`
            }))
        },
        trackingItemsWithIndex() {
            let data = []

            if (this.getTrackingContainerDetails !== 'undefined' && 
                Array.isArray(this.getTrackingContainerDetails) && 
                this.getTrackingContainerDetails.length > 0) {

                data = this.getTrackingContainerDetails.map((items, index) => ({
                    ...items,
                    index: index >= 9 ? index + 1 : `0${index + 1}`,
                    // index: items.seal_number,
                    weight: items.weight_in_lbs,
                    volume: 0,
                    size: items.equipment_length,
                    height: items.equipment_height,
                    cartons: 0
                }))
            }

            return data
        }
    },
    async mounted() {
		this.items = this.getDetails.containers
        this.isTrackingShipment = this.getDetails.is_tracking_shipment
		
		// get all container values
        try {          
            // await this.fetchContainers()
            if (this.getShipmentContainerSizes !== 'undefined' && this.getShipmentContainerSizes.length > 0) {
                this.getShipmentContainerSizes.map((value) => {
                    this.container_sizes.push({ 
                        id: value.id,
                        name: value.name
                    })
                })
            }

        } catch(e) {
            console.log(e)
        }

        //set current page
        this.$store.dispatch("page/setPage", "shipments")
    },
}
</script>

<style lang="scss">
/* @import '../assets/css/shipments_styles/shipmentInfo.css'; */
@import '../assets/scss/pages_scss/shipment/shipmentContainers.scss';
</style>
