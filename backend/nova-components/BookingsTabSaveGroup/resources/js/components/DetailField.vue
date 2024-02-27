<template>
    <div>
    	<div class="flex border-b border-40">
    	    <div class="w-1/4 py-4">
    	      <slot>
    	        <h4 class="font-normal text-80">Schedules</h4>
    	      </slot>
    	    </div>
    	    <div class="w-3/4 py-4 break-words" style="padding-top: 0px;">
    	    	<div style="border-bottom: 0px transparent;padding-top: 0px;" v-show="(schedules.length==0 || schedules=='' || typeof schedules=='undefined' && loading==false)" class="shipment-schedule-group">
                    <div class="mt-4">
                        No Schedule added yet to this Shipment.
                    </div>
                </div>
                <div
                  v-if="loading"
                  class="py-4"
                  style="min-height: 30px"
                >
                    <loader style="margin-left: 0px !important;margin-right: 0px !important;" class="text-60" />
                </div>
                <div v-if="loading==false">
                    <div v-bind:key="`bsch-${key}`" v-for="(item,key) in schedules" style="border-bottom: 1px solid #eef1f4; padding-top: 11px;" class="shipment-schedule-group">
                        <div v-if="(item.mode == 'Ocean' || item.mode == 'Air')">
                            <label>Cut Off</label>
                            <span>{{ format(item.cutoff) }}</span>
                        </div>
                        <div>
                            <label>Estimated Time of Departure</label>
                            <span>{{ format(item.etd) }}</span>
                        </div>
                        <div>
                            <label>Estimated Time of Arrival</label>
                            <span>{{ format(item.eta) }}</span>
                        </div>
                        <div v-if="item.mode == 'Ocean'">
                            <label>Estimated Time of Berthing</label>
                            <span>{{ format(item.etb) }}</span>
                        </div>
                        <div>
                            <label>Location From</label>
                            <span>{{ item.location_from_name}}</span>
                        </div>
                        <div>
                            <label>Location To</label>
                            <span>{{ item.location_to_name}}</span>
                        </div>
                        <div>
                            <label>Mode</label>
                            <span>{{ item.mode}}</span>
                        </div>
                        <div>
                            <label>Carrier</label>
                            <span>
                                {{
                                    item.carrier_name_label
                                }}
                            </span>
                        </div>
                        <div v-show="item.mode == 'Ocean' || item.mode == 'Air'">
                            <label>Agent</label>
                            <span>
                                {{
                                    (typeof item.agent_name!=='undefined') ? item.agent_name : ''
                                }}
                            </span>
                        </div>
                        <div>
                            <label>Type</label>
                            <span>{{ item.type }}</span>
                        </div>
                        <div style="display: block;" v-show="typeof item.legs!=='undefined' && typeof item.legs[0]!=='undefined'">
                            <div style="font-weight: bold;padding-bottom: 4px;" class="schedule-buy-rate-wrapper border-b border-40">
                                Schedule Legs
                            </div>
                            <div data-aos="fade-left" data-aos-once="true" data-aos-duration="500" class="schedule-legs-detail" v-for="(leg,key) in item.legs">
                                <div>
                                    <label>Location From</label>
                                    <span>{{ leg.location_from_name }}</span>
                                </div>
                                <div>
                                    <label>Location To</label>
                                    <span>{{ leg.location_to_name }}</span>
                                </div>
                                <div>
                                    <label>Estimated Time of Departure</label>
                                    <span>{{ leg.etd }}</span>
                                </div>
                                <div>
                                    <label>Estimated Time of Arrival</label>
                                    <span>{{ leg.eta }}</span>
                                </div>
                                <div v-if="leg.mode == 'Ocean'">
                                    <label>Estimated Time of Berthing</label>
                                    <span>{{ leg.etb }}</span>
                                </div>
                                <div>
                                    <label>Mode</label>
                                    <span>{{ leg.mode }}</span>
                                </div>
                                <div v-if="['Ocean','Air','Rail'].includes(leg.mode || '')">
                                    <label> Carrier </label>
                                    <span> {{ getCarrierName(leg.carrier) }} </span>
                                </div>
                            </div>
                        </div>
                        <div style="display: block;" v-if="typeof item.buy_rates!=='undefined' && typeof item.buy_rates[0]!=='undefined'">
                            <div style="font-weight: bold; border-top: 1px solid #eef1f4;line-height: 26px;" class="schedule-buy-rate-wrapper border-b border-40">
                                Buy Rates
                            </div>
                            <div v-bind:key="`sbr-${key}`" data-aos="fade-left" data-aos-once="true" data-aos-duration="500" class="schedule-buy-rate-detail detail-table mb-4 mt-4" v-for="(buy_rate,key) in item.buy_rates">
                                <div class="service-tab-header px-2">
                                    <label>Service</label>
                                    <span>{{ buy_rate.service_name }}</span>
                                </div>
                                <div class="px-2">
                                    <label>Charge Per</label>
                                    <span>{{ buy_rate.container_size_name }}</span>
                                </div>
                                <div class="px-2">
                                    <label>Amount</label>
                                    <span>{{ buy_rate.amount }}</span>
                                </div>
                                <div class="px-2">
                                    <label>Quantity</label>
                                    <span>{{ buy_rate.qty }}</span>
                                </div>
                                <div class="px-2">
                                    <label>Valid To</label>
                                    <span>{{ buy_rate.valid_to }}</span>
                                </div>
                            </div>
                        </div>
                        <div style="display: block;" v-if="typeof item.sell_rates!=='undefined' && typeof item.sell_rates[0]!=='undefined'">
                            <div style="font-weight: bold; border-top: 1px solid #eef1f4; line-height: 26px;" class="schedule-sell-rate-wrapper border-b border-40">
                                Sell Rates
                            </div>
                            <div v-bind:key="`bsr-${key}`" data-aos="fade-left" data-aos-once="true" data-aos-duration="500" class="schedule-sell-rate-detail detail-table mb-4 mt-4" v-for="(sell_rate,key) in item.sell_rates">
                                <div class="service-tab-header px-2">
                                    <label>Service</label>
                                    <span>{{ sell_rate.service_name }}</span>
                                </div>
                                <div class="px-2">
                                    <label>Charge Per</label>
                                    <span>{{ sell_rate.container_size_name }}</span>
                                </div>
                                <div class="px-2">
                                    <label>Amount</label>
                                    <span>{{ sell_rate.amount }}</span>
                                </div>
                                <div class="px-2">
                                    <label>Quantity</label>
                                    <span>{{ sell_rate.qty }}</span>
                                </div>
                                <div class="px-2">
                                    <label>Valid To</label>
                                    <span>{{ sell_rate.valid_to }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label>Agent Booking Notes:</label>
                            <span>{{ item.agent_booking_notes }}</span>
                        </div>
                        <button :disabled="activeKey===key" @click="onAddEmails(item, key)" :class="item.agent_booking_sent ? 'nova-button-shipment btn-success text-success': 'btn-primary shipment-add-group'" class="btn btn-default inline-flex items-center relative mr-3 mb-4">
                            <vue-loaders
                                v-show="activeKey===key"
                                name="line-spin-fade-loader"
                                color="black"
                                scale="0.3"
                            /> {{ item.agent_booking_sent ? 'Resend' : 'Send' }} Booking To Agent
                        </button>

                        <div class="schedule-confirm-container border-b border-40">
                            <input type="radio" @click="select(item)" :id="`schedule-confirm-${item.id}`" :name="`schedule-confirm-${item.id}`" :value="item.id" v-model="currentSelected"/>
                            <label :for="`schedule-confirm-${item.id}`" id="`schedule-confirm-label-${item.id}`">Selected Schedule/Customer Approved</label>
                            <button v-if="item.is_confirmed==1" id="`cancel-schedule-${item.id}`" @click="cancel(item)" style="padding: 1rem; margin-top: -12px;" class="btn btn-danger">Cancel</button>
                        </div>

                        <div class="schedule-confirm-container border-b border-40 agent-confirm-border">
                            <input type="radio" @click="selectAgentConfirm(item)" :id="`agent-confirm-${item.id}`" :disabled="item.is_confirmed != 1" :name="`agent-confirm-${item.id}`" :value="item.id" v-model="currentSelectedAgentConfirm"/>
                            <label :for="`agent-confirm-${item.id}`" id="`agent-confirm-label-${item.id}`">Agent Confirmation</label>
                            <button v-if="item.is_agent_booking_confirm==1" id="`cancel-agent-confirm-${item.id}`" @click="cancelAgentConfirm(item)" style="padding: 1rem; margin-top: -12px;" class="btn btn-danger">Cancel</button>
                        </div>

                    </div>
                </div>
                <div v-show="typeof modalItems!=='undefined'">
                    <add-emails-modal
                        v-show="modalOpen"
                        :modal.sync="modalOpen"
                        @confirm="confirmModal"
                        @close="closeModal"
                        :currentSelected="modalItems.is_confirmed"
                        :freightPortToPort="modalItems.buy_rates"
                        :shiflOfficeFrom="shiflOfficeFrom"
                        :userEmail="userEmail"
                        :item="modalItems"
                        :baseUrl="field.baseUrl"
                    />
                </div>
            </div>
            <select-schedule-confirm-modal
                v-if="showSelectScheduleConfirmModal"
                @close="handleScheduleConfirmationModal"
                @confirm="proceedSelectedScheduleFun()"
            />
            <schedule-removal-alert-modal
                v-if="showScheduleRemovalAlertModal"
                @close="showScheduleRemovalAlertModal = false"
            />
        </div>
    </div>
</template>
<style type="text/css" scoped>
    .detail-table {
        box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
        border-top-right-radius: 8px;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .service-tab-header {
        background-image: -webkit-gradient(linear, left bottom, left top, from(#7e8ea1), to(#3c4655));
        background-image: linear-gradient(0deg, #7e8ea1 0%, #3c4655 100%);
        background-attachment: fixed;
        border-width: 0;
        border-style: solid;
        border-color: #22292f;
        padding-top: 5px;
        color: white;
        border-top-right-radius: 8px;
        border-top-left-radius: 8px;
    }

    .schedule-buy-rate-detail,
    .schedule-sell-rate-detail {
        width: 100% !important;
    }

    .schedule-buy-rate-detail > div,
    .schedule-sell-rate-detail > div {
        display: flex;
        padding-bottom: 15px;
    }

    .schedule-buy-rate-detail > div > label,
    .schedule-sell-rate-detail > div > label,
    .schedule-legs-detail > div > label {
        width: 40%;
        display: block;
        padding-top: 5px;
    }

    .schedule-buy-rate-detail > div > span,
    .schedule-sell-rate-detail > div > span,
    .schedule-legs-detail > div > span {
        padding-top: 5px;
    }
    .agent-confirm-border{
        padding-top: 20px;
    }

</style>
<script>
import _ from 'lodash'
import axios from 'axios';
import jQuery from 'jquery'
import moment from 'moment'
import AOS from 'aos'
import 'aos/dist/aos.css'
import "vue-loaders/dist/vue-loaders.css";
import iziToast from 'izitoast'
import AddEmailsModal from './modals/AddEmailsModal'
import SelectScheduleConfirmModal from './Modals/SelectScheduleConfirmModal'
import ScheduleRemovalAlertModal from './Modals/ScheduleRemovalAlertModal'
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    computed: {
        token() {
            return jQuery('meta[name="csrf-token"]').attr('content')
        }
    },
    components: {
        AddEmailsModal,
        SelectScheduleConfirmModal,
        ScheduleRemovalAlertModal
    },
    created() {
        AOS.init()
    },
    mounted() {
        console.log('merge 03-24-2023');
        console.log('v1.0.0');
        let scheduleIds = []
        let containerSizeIds = []
        let serviceIds = []
        let carrierIds = []
        setInterval(() => {
            this.getSuppliersfromCache();
            this.getContainersfromCache();
        }, 4000)

        //get all carriers
        fetch(`${this.field.baseUrl}/custom/carriers`, {
            token: this.token
        })
        .then(this.handleResponse)
        .then(response => {
            let { results } = response;
            if (typeof results !== "undefined") {
                this.carrierLists = results;
            }
        });



        if (this.schedules.length > 0) {
            this.schedules.map(async (schedule, key) => {
                this.schedules[key].is_confirmed = (typeof schedule.is_confirmed=='undefined') ? 0 : parseInt(schedule.is_confirmed)

                if ( typeof schedule.vendor_id!=='undefined' ) {
                    this.schedules[key].vendor_name = ''
                    try {

                        fetch(`${this.field.baseUrl}/custom/vendor/${schedule.vendor_id}`, {
                            token: this.token
                        }).then(this.handleResponse)
                        .then(response => {
                            if ( typeof response.item!=='undefined' ) {
                                let {
                                    item
                                } = response
                                setTimeout(() => {
                                    this.schedules[key].vendor_name = (item!==null) ? item.company_name : ''
                                },100)
                            }
                        })

                        /*
                        let response = await fetch(`${this.field.baseUrl}/custom/vendor/${schedule.vendor_id}`, {
                            token: this.token
                        })

                        let final_response = await response.text()

                        if (JSON.parse(final_response)) {
                            let { item } = JSON.parse(final_response)
                            if ( typeof item!=='undefined' ) {
                                this.schedules[key].vendor_name = item.company_name
                            }
                        }*/

                    } catch(e) {
                        console.log('error',e)
                    }

                }

                if ( typeof schedule.agent_id!=='undefined' ) {
                    this.schedules[key].agent_name = ''
                    try {

                        fetch(`${this.field.baseUrl}/custom/agent/${schedule.agent_id}`, {
                            token: this.token
                        }).then(this.handleResponse)
                            .then(response => {
                                if ( typeof response.item!=='undefined' ) {
                                    let {
                                        item
                                    } = response
                                    setTimeout(() => {
                                        this.schedules[key].agent_name = (item!==null) ? item.name : ''
                                    },100)
                                }
                            })

                    } catch(e) {
                        console.log('error',e)
                    }

                }

                if (typeof schedule.location_from!=='undefined' && scheduleIds.indexOf(schedule.location_from)==-1) {
                    scheduleIds.push(schedule.location_from)
                }

                if (typeof schedule.location_to!=='undefined' && scheduleIds.indexOf(schedule.location_to)==-1) {
                    scheduleIds.push(schedule.location_to)
                }

                if (typeof schedule.legs!=='undefined' && schedule.legs!=='') {
                    schedule.legs.map(l => {
                        if (typeof l.location_to!=='undefined' && scheduleIds.indexOf(l.location_to)==-1) {
                            scheduleIds.push(l.location_to)
                        }
                    })
                }

                if (typeof schedule.selling_size_id!=='undefined' && schedule.selling_size_id!==null) {

                    if (containerSizeIds.indexOf(schedule.selling_size_id)==-1) {
                        containerSizeIds.push(schedule.selling_size_id)
                    }

                }

                if (typeof schedule.size_id!=='undefined' && schedule.size_id!==null) {
                    if (containerSizeIds.indexOf(schedule.size_id)==-1) {
                        containerSizeIds.push(schedule.size_id)
                    }
                }



                if (typeof schedule.sell_rates!=='undefined' && schedule.sell_rates.length > 0) {

                    schedule.sell_rates.map(sr => {
                        if (typeof sr.container_size_id!=='undefined' && sr.container_size_id!==null && containerSizeIds.indexOf(sr.container_size_id)==-1) {
                            containerSizeIds.push(sr.container_size_id)
                        }

                        if (typeof sr.service_id!=='undefined' && serviceIds.indexOf(sr.service_id)==-1) {
                            serviceIds.push(sr.service_id)
                        }

                    })

                }

                if (typeof schedule.buy_rates!=='undefined' && schedule.buy_rates.length > 0) {

                    schedule.buy_rates.map(br => {
                        if (containerSizeIds.indexOf(br.container_size_id)==-1) {
                            containerSizeIds.push(br.container_size_id)
                        }

                        if (serviceIds.indexOf(br.service_id)==-1) {
                            serviceIds.push(br.service_id)
                        }
                    })
                }

                if (typeof schedule.carrier_name!=='undefined' && schedule.carrier_name!==null && typeof schedule.carrier_name.id!=='undefined') {
                    var ids = []
                    var fd = new FormData()
                    ids.push(schedule.carrier_name.id)
                    fd.append('_token', this.token)
                    fd.append('ids', JSON.stringify(ids))
                    fetch(`${this.field.baseUrl}/custom/carriers/where-in`,{
                        body: fd,
                        method: 'POST'
                    }).then(this.handleResponse)
                    .then( response => {
                        let { results } = response
                        if (typeof results!=='undefined' && results.length > 0) {
                            this.schedules[key].carrier_name_label = results[0].name
                        }

                    })

                } else {
                    this.schedules[key].carrier_name_label = ''
                }


                if (this.schedules[key].is_confirmed==1)
                    this.currentSelected = schedule.id
                if (this.schedules[key].is_agent_booking_confirm==1)
                    this.currentSelectedAgentConfirm = schedule.id
            })
        }

        let tt = setInterval(() => {

            if ( scheduleIds.length > 0 ) {
                var fd = new FormData()
                fd.append('_token', this.token)
                fd.append('ids', JSON.stringify(scheduleIds))

                //start
                //get all terminal regions
                fetch(`${this.field.baseUrl}/custom/terminal-regions/where-in`,{
                    body: fd,
                    method: 'POST'
                }).then(this.handleResponse)
                .then( response => {
                    let { results } = response
                    if (typeof results!=='undefined') {

                        if (this.schedules.length > 0 ) {
                            if(this.schedules && this.schedules.length > 0){
                                for(let key = 0; key < this.schedules.length; key++){
                                    if(this.schedules[key].legs && this.schedules[key].legs.length > 0){
                                        for(let keySecond = 0; keySecond < this.schedules[key].legs.length; keySecond++){
                                            if(this.schedules[key].legs[keySecond].location_from) continue;
                                            this.schedules[key].legs[keySecond].location_from = (keySecond == 0) ? this.schedules[key].location_to : this.schedules[key].legs[keySecond - 1].location_to
                                        }
                                    }
                                }
                            }
                            this.schedules.map( (schedule, key) => {

                                if (results.length > 0) {

                                    results.map(s => {
                                        if (s.id==schedule.location_from) {
                                            this.schedules[key].location_from_name = s.name
                                        }

                                        if (s.id==schedule.location_to) {
                                            this.schedules[key].location_to_name = s.name
                                        }

                                    })

                                    if (typeof schedule.legs!=='undefined' && schedule.legs!=='') {
                                        schedule.legs.map((l, kk) => {

                                            results.map(s => {
                                                if (s.id==l.location_to) {
                                                    this.schedules[key].legs[kk].location_to_name = s.name
                                                }
                                                if(s.id == l.location_from){
                                                    this.schedules[key].legs[kk].location_from_name = s.name
                                                }
                                            })
                                        })
                                    }

                                }
                            })
                        }
                    }
                    //s
                    fd = new FormData()
                    fd.append('_token', this.token)
                    fd.append('ids', JSON.stringify(containerSizeIds))

                    //get all sizes
                    fetch(`${this.field.baseUrl}/custom/container-sizes/where-in`,{
                        body: fd,
                        method: 'POST'
                    }).then(this.handleResponse)
                    .then( response => {

                        let { results } = response

                        if (typeof results!=='undefined' && results.length > 0) {

                            this.sizes = results
                            if (typeof this.resourceId!=='undefined' && this.resourceId!==null && this.resourceId!=='') {

                                let schedules_group_bookings = this.schedules

                                if (schedules_group_bookings.length > 0) {

                                    this.schedules.map( (schedule, key) => {
                                        if (typeof schedule.selling_size_id!=='undefined' && schedule.selling_size_id!==null) {

                                            results.map(s => {
                                                if (s.id==schedule.selling_size_id) {
                                                    this.schedules[key].selling_size_name = s.name
                                                }
                                            })
                                        }

                                        if (typeof schedule.size_id!=='undefined' && schedule.size_id!==null) {

                                            results.map(s => {
                                                if (s.id==schedule.size_id) {
                                                    this.schedules[key].size_name = s.name
                                                }
                                            })
                                        }

                                        if (typeof schedule.sell_rates!=='undefined' && schedule.sell_rates.length > 0) {

                                            schedule.sell_rates.map((sr,ksr)=> {
                                                if (typeof sr.container_size_id!=='undefined' && sr.container_size_id!==null) {
                                                    results.map(s => {
                                                        if (s.id==sr.container_size_id) {
                                                            this.schedules[key].sell_rates[ksr].container_size_name = s.name
                                                        }
                                                    })
                                                }

                                            })

                                        }

                                        if (typeof schedule.buy_rates!=='undefined' && schedule.buy_rates.length > 0) {

                                            schedule.buy_rates.map((br,kbr) => {
                                                if (typeof br.container_size_id!=='undefined' && br.container_size_id!==null) {
                                                    /*
                                                    this.schedules[key].buy_rates[kbr].container_size_name = _,find(results,{
                                                            id: br.container_size_id
                                                        }).name*/
                                                    results.map(s => {
                                                        if (s.id==br.container_size_id) {
                                                            this.schedules[key].buy_rates[kbr].container_size_name = s.name
                                                        }
                                                    })

                                                }
                                            })

                                        }

                                    })
                                }

                            }


                        }

                        //s
                        fd = new FormData()
                        fd.append('_token', this.token)
                        fd.append('ids', JSON.stringify(serviceIds))


                        //get all services
                        fetch(`${this.field.baseUrl}/custom/services/where-in`,{
                            method: 'POST',
                            body: fd
                        }).then(this.handleResponse)
                        .then( response => {
                            let { results } = response
                            if (typeof results!=='undefined' && results.length > 0) {

                                if (typeof this.resourceId!=='undefined' && this.resourceId!==null && this.resourceId!=='') {

                                    let schedules_group_bookings = this.schedules

                                    if (schedules_group_bookings.length > 0) {

                                        this.schedules.map((schedule, key) => {

                                            if (typeof schedule.sell_rates!=='undefined' && schedule.sell_rates.length > 0) {

                                                schedule.sell_rates.map((sr,ksr)=> {
                                                    if (typeof sr.service_id!=='undefined' && sr.service_id!==null) {
                                                        results.map(s => {
                                                            if (s.id==sr.service_id) {
                                                                this.schedules[key].sell_rates[ksr].service_name = s.name
                                                            }
                                                        })
                                                    }

                                                })

                                            }

                                            if (typeof schedule.buy_rates!=='undefined' && schedule.buy_rates.length > 0) {

                                                schedule.buy_rates.map((br,kbr) => {
                                                   if (typeof br.service_id!=='undefined' && br.service_id!==null) {
                                                        results.map(s => {
                                                            if (s.id==br.service_id) {
                                                                this.schedules[key].buy_rates[kbr].service_name = s.name
                                                            }
                                                        })
                                                    }
                                                })
                                            }

                                        })

                                    }

                                }

                            }
                            this.loading = false
                            this.schedulesDisplay = this.schedules
                        })
                        //e
                    })
                    //e
                })


                //end
                clearInterval(tt)
            } else {
                this.loading = false
                clearInterval(tt)
            }
        },100)

        this.shiflOfficeFrom = this.field.shiflOffices.find(office => office.id == this.field.shipment.shifl_office_origin_from_id);

    },
    methods: {
        handleScheduleConfirmationModal(){
            const selectedItem = this.currentSelectedSchedule;
            const filteredSchedule = this.schedules.filter(schedule => schedule.is_confirmed == 1);
            jQuery(`#schedule-confirm-${filteredSchedule[0].id}`).prop("checked", true)
            jQuery(`#schedule-confirm-${selectedItem.id}`).prop("checked", false)

            this.showSelectScheduleConfirmModal = false
        },
        getSuppliersfromCache() {
            this.cacheSuppliers = JSON.parse(localStorage.getItem(`shipment_suppliers${this.resourceId}`))
        },
        getContainersfromCache() {
            this.cacheContainers = JSON.parse(localStorage.getItem(`shipment_containers${this.resourceId}`))
        },
        confirmModal(data) {
            this.modalOpen = false;
            this.sendBookingToAgent(this.modalItems, this.modalKey, data)
        },
        closeModal() {
            this.modalOpen = false;
        },
        onAddEmails(item, key) {
            this.modalOpen  = true;
            this.modalItems = item
            this.modalKey   = key
        },
        async sendBookingToAgent(item, key, data)  {
            this.activeKey = key
            try {
                let fd = new FormData()

                let attributes = {
                    shipment_id: this.field.shipment.id,
                    items: item,
                    itemIndex: key
                }
                if (data) {
                    attributes.emails = data.emails,
                    attributes.userEmail =  data.userEmail,
                    attributes.agentEmail = data.agentEmail,
                    attributes.shiflOfficeFromDisplay = data.shiflOfficeFromDisplay
                    attributes.ccEmails = data.ccEmails
                    attributes.agent_booking_sent = this.schedules[key].agent_booking_sent ? true : false
                    attributes.agent_booking_sent_at = new Date(Date.now()).toISOString()
                    attributes.suppliers_group = this.cacheSuppliers
                    attributes.container_group = this.cacheContainers
                }

                fd.append('_token', this.token)
                fd.append('item',  JSON.stringify(attributes))

                if(data.attachments.files && data.attachments.files.length > 0){
                    let i = 0;
                    for(const file of data.attachments.files) {
                        fd.append("file["+i+"]",file);
                        i++;
                    };
                }

                axios.post(`${this.field.baseUrl}/custom/send-booking-agent`,
                    fd,
                    { headers: {"Content-Type": "multipart/form-data"} }
                ).then( response => {
                        this.activeKey = undefined
                        if (response.status == 200 && response.data == 'Success') {
                            let message = "Booking sent to agent successfully.";
                            iziToast.success({
                                theme: 'dark',
                                message,
                                backgroundColor: '#16B442',
                                messageColor: '#ffffff',
                                iconColor: '#ffffff',
                                progressBarColor: '#ADEEBD',
                                displayMode: 1,
                                position: 'topRight',
                                timeout: 3000,
                            })
                            this.schedules[key].agent_booking_sent = true
                        } else {
                            let message = response;
                            iziToast.error({
                                theme: 'dark',
                                message,
                                backgroundColor: '#FF0000',
                                messageColor: '#ffffff',
                                iconColor: '#ffffff',
                                progressBarColor: '#ADEEBD',
                                displayMode: 1,
                                position: 'topRight',
                                timeout: 3000,
                            })
                        }
                    })

            } catch(e) {
                console.log('error',e)
            }
        },
        handleClose() {
            this.$emit('close')
        },
        format(d) {
            if(d)
              return moment(moment.utc(d).format('YYYY-MM-DD')).isValid() ? moment.utc(d).format('YYYY-MM-DD') : d
            return ''
        },
        cancel(item) {

            // console.log(this.field.shipment)
            if(this.field.shipment.mbl_num == '' || this.field.shipment.mbl_num == null){
                jQuery(`#cancel-schedule-${item.id}`).html('Cancelling...')

                this.schedules.map((schedule, key) => {
                    if (item.id==schedule.id) {
                        this.schedules[key].is_confirmed = 0
                    }
                })

                //const token = jQuery('meta[name="csrf-token"]').attr('content')
                var fd = new FormData()
                fd.append('schedules_group_bookings', JSON.stringify(this.schedules) || [])
                fd.append('id', this.resourceId)
                fd.append('_token',this.token)

                const requestOptions = {
                    method: 'POST',
                    body: fd
                }

                fetch(`${this.field.baseUrl}/custom/bookings/cancel-schedule`, requestOptions)
                    .then(this.handleResponse)
                    .then(response => {
                        jQuery(`#cancel-schedule-${item.id}`).html('Cancel')
                        window.location.href = `/administrator/resources/shipments/${this.resourceId}?tab=booking`
                    })
            } else {
                this.showScheduleRemovalAlertModal = true;
            }


        },
        select(item) {
            this.currentSelectedSchedule = item
            const filteredSchedule = this.schedules.filter(schedule => schedule.is_confirmed == 1);
            if(filteredSchedule.length == 0){
                this.proceedSelectedScheduleFun()
            } else if(filteredSchedule.length > 0 && item.is_confirmed == 0){
                this.showSelectScheduleConfirmModal = true;
            }
        },
        proceedSelectedScheduleFun() {
            const item = this.currentSelectedSchedule
            jQuery(`#schedule-confirm-label-${item.id}`).html('Setting Schedule...')
            console.log('setting schedule' + `#schedule-confirm-label-${item.id}`)
            this.schedules.map((schedule, key) => {
                if (item.id!=schedule.id) {
                    this.schedules[key].is_confirmed = 0
                    this.schedules[key].booking_confirmed_at = ''
                } else {
                    this.schedules[key].is_confirmed = 1
                    this.schedules[key].booking_confirmed_at = new Date(Date.now()).toISOString()
                }
            })

            var fd = new FormData()
            fd.append('schedules_group_bookings', JSON.stringify(this.schedules) || [])
            fd.append('id', this.resourceId)

            fd.append('_token',this.token)

            const requestOptions = {
                method: 'POST',
                body: fd
            }

            fetch(`${this.field.baseUrl}/custom/bookings/select-schedule`, requestOptions)
                .then(this.handleResponse)
                .then(response => {
                    jQuery(`#schedule-confirm-label-${item.id}`).html('Selected Schedule/Customer Approved')
                    this.showSelectScheduleConfirmModal = false
                    window.location.href = `/administrator/resources/shipments/${this.resourceId}?tab=booking`
                })
            this.showSelectScheduleConfirmModal = false
        },
        selectAgentConfirm(item) {
            jQuery(`#agent-confirm-label-${item.id}`).html('Setting Agent Confirmation...')
            console.log('setting schedule' + `#agent-confirm-label-${item.id}`)
            this.schedules.map((schedule, key) => {
                if (item.id!=schedule.id) {
                    this.schedules[key].is_agent_booking_confirm = 0
                    this.schedules[key].agent_booking_confirmed_at = ''
                } else {
                    this.schedules[key].is_agent_booking_confirm = 1
                    this.schedules[key].agent_booking_confirmed_at = new Date(Date.now()).toISOString()
                }
            })

            //const token = jQuery('meta[name="csrf-token"]').attr('content')
            var fd = new FormData()
            fd.append('schedules_group_bookings', JSON.stringify(this.schedules) || [])
            fd.append('id', this.resourceId)

            fd.append('_token',this.token)

            const requestOptions = {
                method: 'POST',
                body: fd
            }

            fetch(`${this.field.baseUrl}/custom/bookings/select-agent-confirmation`, requestOptions)
                .then(this.handleResponse)
                .then(response => {
                    jQuery(`#agent-confirm-label-${item.id}`).html('Agent Confirmation')
                    window.location.href = `/administrator/resources/shipments/${this.resourceId}?tab=booking`
                })

        },
        cancelAgentConfirm(item) {

            jQuery(`#cancel-agent-confirm-${item.id}`).html('Cancelling...')

            this.schedules.map((schedule, key) => {
                if (item.id==schedule.id) {
                    this.schedules[key].is_agent_booking_confirm = 0
                }
            })

            var fd = new FormData()
            fd.append('schedules_group_bookings', JSON.stringify(this.schedules) || [])
            fd.append('id', this.resourceId)
            fd.append('_token',this.token)

            const requestOptions = {
                method: 'POST',
                body: fd
            }

            fetch(`${this.field.baseUrl}/custom/bookings/cancel-agent-confirmation`, requestOptions)
                .then(this.handleResponse)
                .then(response => {
                    jQuery(`#cancel-schedule-${item.id}`).html('Cancel')
                    window.location.href = `/administrator/resources/shipments/${this.resourceId}?tab=booking`
                })
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
        getCarrierName(id){
            if(this.carrierLists.length > 0){
                let carrier = this.carrierLists.find(e => e.id == id)
                if(carrier){
                    return carrier.name
                }
            }
            return ''
        }
    },
    data() {
    	return {
            modalOpen: false,
            activeKey: undefined,
            modalKey: undefined,
            modalItems: [],
            additionalEmails: [],
            carriersObject: {},
            currentCarrierLink: '',
            carrierName: '',
            loading: true,
            loadingText: 'Saving',
            buttonText: 'Save',
            cancelSchedule: {
                loading: false,
                loadingText: 'Cancelling...',
                defaultValue: 'Cancel',
                value: 'Cancel'
            },
            schedulesDisplay: [],
            modalShow: false,
            currentSelected: null,
            currentSelectedAgentConfirm: null,
            terminalRegions: null,
            //terminalRegions: this.field.terminalRegions,
            schedules: (typeof this.field.shipment.schedules_group_bookings!=='undefined') ? JSON.parse(this.field.shipment.schedules_group_bookings) : [],
            containers: (typeof this.field.shipment.containers_group_bookings!=='undefined') ? this.field.shipment.containers_group_bookings : [],
            shipmentScheduledGroupField: {
                attribute: 'schedules_group',
                name: 'Schedule Section',
                value: (typeof this.field.shipment.schedules_group_bookings!=='undefined') ? this.field.shipment.schedules_group_bookings : null
            },
            shipmentContainerGroupField: {
                attribute: 'containers_group',
                name: 'Container Section',
                value: (typeof this.field.shipment.containers_group_bookings!=='undefined') ? this.field.shipment.containers_group_bookings : null
            },
            shipmentSupplierGroupField: {
                attribute: 'suppliers_group',
                name: 'Suppliers Section',
                value: (typeof this.field.shipment.suppliers_group_bookings!=='undefined') ? this.field.shipment.suppliers_group_bookings : null
            },
            booking_confirmed: (typeof this.field.shipment.booking_confirmed!=='undefined' && this.field.shipment.booking_confirmed==1) ? true : false,
            memo_customer: {
                attribute: 'memo_customer',
                name: 'Memo',
                value: (typeof this.field.shipment.memo_customer!=='undefined') ? this.field.shipment.memo_customer : null
            },
            types: (typeof this.field.types!=='undefined') ? this.field.types : [],
            carriers: (typeof this.field.carriers!=='undefined') ? this.field.carriers : [],
            sizes: (typeof this.field.sizes!=='undefined') ? this.field.sizes : [],
            schedulesGroupCopy: null,
            suppliersGroupCopy: null,

            suppliers: (typeof this.field.shipment.suppliers_group_bookings!=='undefined') ? this.field.shipment.suppliers_group_bookings : [],
            supplierOptions: (typeof this.field.suppliers!=='undefined') ? this.field.suppliers : null,
            incoTermValues: (typeof this.field.incoTerms!=='undefined') ? this.field.incoTerms : null,
            shiflOfficeFrom: {},
            userEmail: this.field.userEmail,
            carrierLists: [],
            cacheSuppliers: [],
            cacheContainers: [],
            showSelectScheduleConfirmModal: false,
            currentSelectedSchedule: {},
            showScheduleRemovalAlertModal: false
        }
    }
}
</script>
