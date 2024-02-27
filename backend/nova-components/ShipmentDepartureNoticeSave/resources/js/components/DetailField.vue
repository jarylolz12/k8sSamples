<template>
    <div>
    	<div class="flex border-b border-40">
    	    <div class="w-1/4 py-4">
    	      <slot>
    	        <h4 class="font-normal text-80">{{ "Schedules" }}</h4>
    	      </slot>
    	    </div>
    	    <div class="w-3/4 py-4 break-words pt-0">
    	    	<div style="border-bottom: 0px transparent;" v-show="(schedules.length==0 || schedules=='' || typeof schedules=='undefined' && !loadingSchedule)" class="shipment-schedule-group pt-0">
                    <div style="padding-top: 1rem;">
                        {{ "There is/are no added schedule yet on this shipment." }}
                    </div>
                </div>
                <div class="py-4" v-if="typeof schedules!=='undefined' && typeof schedules[0]!=='undefined' && selectedSchedule==null && !loadingSchedule">
                    {{ "There is no selected schedule yet on this shipment. Please see booking tab." }}
                </div>
                <div
                  v-if="loadingSchedule"
                  class="py-4"
                  style="min-height: 30px"
                >
                    <loader style="margin-left: 0px !important;margin-right: 0px !important;" class="text-60" />
                </div>
                <div>
                    <div v-show="(typeof item.is_confirmed!=='undefined' && item.is_confirmed==1)" v-bind:key="`dns-${key}`" v-for="(item,key) in schedulesDeptDisplay" style="border-bottom: 1px solid #eef1f4; padding-top: 9px;" class="shipment-schedule-group">
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
                            <span class="ml8 cursor-pointer" style="display:inline-block;margin-left:8px;" @click="$refs.ETALogStatusModal.$data.open = true">
                                <svg class="float-left" fill="rgb(41, 153, 241)" viewBox="0 0 24 24" width="20px" height="20px"><path d="M 12 2 C 6.4889971 2 2 6.4889971 2 12 C 2 17.511003 6.4889971 22 12 22 C 17.511003 22 22 17.511003 22 12 C 22 6.4889971 17.511003 2 12 2 z M 12 4 C 16.430123 4 20 7.5698774 20 12 C 20 16.430123 16.430123 20 12 20 C 7.5698774 20 4 16.430123 4 12 C 4 7.5698774 7.5698774 4 12 4 z M 11 7 L 11 9 L 13 9 L 13 7 L 11 7 z M 11 11 L 11 17 L 13 17 L 13 11 L 11 11 z"></path></svg>
                            </span>
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
                                    carrierName
                                }}
                            </span>
                        </div>
    <!--                    <div>-->
    <!--                        <label>Co Loader Field</label>-->
    <!--                        <span>-->
    <!--                            {{-->
    <!--                                (typeof item.vendor_name!=='undefined') ? item.vendor_name : ''-->
    <!--                            }}-->
    <!--                        </span>-->
    <!--                    </div>-->
                        <div v-show="item.mode == 'Ocean' || item.mode == 'Air'">
                            <label>Agent</label>
                            <span>
                                {{
                                    (typeof item.agent_name!=='undefined') ? item.agent_name : ''
                                }}
                            </span>
                        </div>
                        <div class="hidden">
                            <label>Type</label>
                            <span>{{ item.type }}</span>
                        </div>

                        <div style="display: block;" v-show="typeof item.legs!=='undefined' && typeof item.legs[0]!=='undefined'">
                            <div style="font-weight: bold;padding-bottom: 4px;" class="schedule-leg-wrapper border-b border-40">
                                Schedule Legs
                            </div>
                            <div v-bind:key="`dnl-${key}`" data-aos="fade-left" data-aos-once="true" data-aos-duration="500" class="schedule-legs-detail" v-for="(leg,key) in item.legs">
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
                            <div style="font-weight: bold;padding-bottom: 4px; border-top: 1px solid #eef1f4;line-height: 26px;" class="schedule-buy-rate-wrapper border-b border-40">
                                Buy Rates
                            </div>
                            <div v-bind:key="`dnbr-${key}`" data-aos="fade-left" data-aos-once="true" data-aos-duration="500" class="schedule-buy-rate-detail detail-table mb-4 mt-4" v-for="(buy_rate,key) in item.buy_rates">
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
                            <div v-bind:key="`dnsrsr-${key}`" data-aos="fade-left" data-aos-once="true" data-aos-duration="500" class="schedule-sell-rate-detail detail-table mb-4 mt-4" v-for="(sell_rate,key) in item.sell_rates">
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
                    </div>
                </div>
    	    </div>
      	</div>
        <div class="flex border-b border-40">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">Carrier</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <a v-if="selectedSchedule!=null && typeof selectedSchedule.carrier_name!=='undefined'" :href="currentCarrierLink" class="no-underline font-bold dim text-primary">
                    {{
                        carrierName
                    }}
                </a>
            </div>
        </div>
        <div class="flex border-b border-40">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">MBL - System Generated</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <a :href="`/mbl/download/${shipment_id}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center">
					<icon class="mr-2" type="download" view-box="0 0 24 24" width="16" height="16" />
					<span class="class mt-1">{{ __('Download') }}</span>
				</a>
            </div>
        </div>
        <ETALogStatusModal :terminal_etalogs="field.terminal_etalogs" :etalogs="field.etalogs" :title="field.shipment.shifl_ref+' ETA Logs Status'" ref="ETALogStatusModal" />
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
        color: white;
        padding-top: 5px;
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

    /* animation */
    .lds-facebook {
      display: inline-block;
      position: relative;
      width: 30px;
      height: 30px;

    }
    .lds-facebook div {
      display: inline-block;
      position: absolute;
      left: 8px;
      width: 16px;
      background: #b3f0c2;
      animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
    }
    .lds-facebook div:nth-child(1) {
      left: 8px;
      animation-delay: -0.24s;
    }
    .lds-facebook div:nth-child(2) {
      left: 32px;
      animation-delay: -0.12s;
    }
    .lds-facebook div:nth-child(3) {
      left: 56px;
      animation-delay: 0;
    }
    @keyframes lds-facebook {
      0% {
        top: 8px;
        height: 64px;
      }
      50%, 100% {
        top: 24px;
        height: 32px;
      }
    }

</style>
<script>
import _ from 'lodash'
import jQuery from 'jquery'
import moment from 'moment'
import AOS from 'aos'
import 'aos/dist/aos.css'
import ETALogStatusModal from './ETALogStatusModal.vue';

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    components: { ETALogStatusModal },
    computed: {
        token() {
            return jQuery('meta[name="csrf-token"]').attr('content')
        }
    },
    created() {
        AOS.init()
    },
    mounted() {
        let scheduleIds = []
        let containerSizeIds = []
        let serviceIds = []
        let carrierIds = []

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
            this.schedules.map( async (schedule, key) => {

                this.schedules[key].is_confirmed = (typeof schedule.is_confirmed=='undefined') ? 0 : parseInt(schedule.is_confirmed)

                if (typeof schedule.location_from!=='undefined' && scheduleIds.indexOf(schedule.location_from)==-1) {
                    scheduleIds.push(schedule.location_from)
                }

                if ( typeof schedule.vendor_id!=='undefined' ) {
                    this.schedules[key].vendor_name = ''

                    try {
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

                if (this.schedules[key].is_confirmed==1) {
                    this.selectedSchedule = schedule

                    if (typeof this.selectedSchedule.carrier_name!=='undefined' && this.selectedSchedule.carrier_name!==null && typeof this.selectedSchedule.carrier_name.id!=='undefined' && this.selectedSchedule.carrier_name.id!==null) {
                        this.carrierId = this.selectedSchedule.carrier_name.id
                        carrierIds.push(this.selectedSchedule.carrier_name.id)

                        this.currentCarrierLink = `${this.field.baseUrl}/resources/carriers/${this.carrierId}`

                    }
                }
            })
        }
        var fd = new FormData()
        let tt = setInterval(() => {
            if ( carrierIds.length > 0 ) {

                fd.append('_token', this.token)
                fd.append('ids', JSON.stringify(carrierIds))

                fetch(`${this.field.baseUrl}/custom/carriers/where-in`,{
                    body: fd,
                    method: 'POST'
                }).then(this.handleResponse)
                .then( response => {

                    let { results } = response
                    if (typeof results!=='undefined' && results.length > 0 && this.selectedSchedule!==null) {
                        results.map(r => {
                            if (r.id==this.selectedSchedule.carrier_name.id) {
                                this.carrierName = r.name
                            }
                        })

                    }

                })
                clearInterval(tt)
            } else {
                if (this.schedules.length == 0) {
                    clearInterval(tt)
                }
            }

        },100)


        fd = new FormData()
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

                            this.schedules.map((schedule, key) => {
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
                    this.loadingSchedule = false
                    this.schedulesDeptDisplay = this.schedules
                })
                //e
            })
            //e
        })
    },
    methods: {
        handleClose() {
            this.$emit('close')
        },
        ucWords(str) {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                return $1.toUpperCase()
            })
        },
        format(d) {
            if(d)
              return moment(moment.utc(d).format('YYYY-MM-DD')).isValid() ? moment.utc(d).format('YYYY-MM-DD') : d
            return ''
        },
        select(item) {

            this.schedules.map((schedule, key) => {
                if (item.id!=schedule.id) {
                    this.schedules[key].is_confirmed = 0
                } else {
                    this.schedules[key].is_confirmed = 1
                }
            })

            const token = jQuery('meta[name="csrf-token"]').attr('content')
            var fd = new FormData()
            fd.append('schedules_group_bookings', JSON.stringify(this.schedules) || [])
            fd.append('id', this.resourceId)

            fd.append('_token',token)

            const requestOptions = {
                method: 'POST',
                body: fd
            }

            fetch(`${this.field.baseUrl}/custom/bookings/select-schedule`, requestOptions)
            .then(this.handleResponse)
            .then(response => {
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
            selectedSchedule: null,
            carrierName: '',
            carrierId: '',
            loadingSchedule: true,
            loadingText: 'Saving',
            buttonText: 'Save',
            modalShow: false,
            currentSelected: null,
            currentCarrierLink: '',
            schedulesDeptDisplay: [],
            terminalRegions: null,
            //terminalRegions: this.field.terminalRegions,
            schedules: (typeof this.field.shipment.schedules_group!=='undefined') ? this.field.shipment.schedules_group : [],
            containers: (typeof this.field.shipment.containers_group_bookings!=='undefined') ? this.field.shipment.containers_group_bookings : [],
            shipmentScheduledGroupField: {
                attribute: 'schedules_group',
                name: 'Schedule Section',
                value: (typeof this.field.shipment.schedules_group!=='undefined') ? this.field.shipment.schedules_group : null
            },
            types: (typeof this.field.types!=='undefined') ? this.field.types : [],
            carriers: (typeof this.field.carriers!=='undefined') ? this.field.carriers : [],
            sizes: (typeof this.field.sizes!=='undefined') ? this.field.sizes : [],
            shipment_id: this.field.shipment ? this.field.shipment.id : '',
            carrierLists: []
        }
    }
}
</script>