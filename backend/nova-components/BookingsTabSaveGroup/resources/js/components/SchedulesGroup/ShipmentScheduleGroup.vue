<template>
    <default-field :field="field">
        <template slot="field">
            <input
                :id="field.attribute"
                type="hidden"
                class="w-full form-control form-input form-input-bordered"
                :placeholder="field.name"
                v-model="value"
            />
            <div v-bind:key="`btsg-${key}`" :id="`shipment-schedule-group-${item.id}`" v-for="(item,key) in formGroups" class="shipment-schedule-group" style="border-bottom: 1px solid #eef1f4; padding-top: 4px;">
                <div>
                    <label>Location From</label>
                    <div>
                        <select :id="`shipment-schedule-terminal-region-from-select-${key}`" v-model="item.location_from" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Select Location</option>
                            <option v-bind:key="`btlf-${otr}`" v-show="terminalRegions.length>0" v-for="(option,otr) in terminalRegions" :value="option.id">{{option.name}}</option>
                        </select>
                    </div>
                </div>
                <div v-if="item.mode == 'Ocean' || item.mode == 'Air'">
                    <label>Cut Off</label>
                    <div>
                        <date-time-picker
                            class="w-full form-control form-input form-input-bordered mb-1"
                            :value="item.cutoff"
                            @change="(value) => {handleFieldChange(value, item, 'cutoff')}"
                            :dateFormat="pickerFormat"
                            :placeholder="placeholder"
                            :enable-time="false"
                            :enable-seconds="false"
                            :first-day-of-week="firstDayOfWeek"
                            :class="`${ (typeof errorMessages.cutoff!='undefined') ? 'form-input-bordered border-danger' : ''}`"
                          />
                        <div v-show="(typeof errorMessages.cutoff!='undefined')" class="help-text error-text text-danger mt-2">
                          {{errorMessages['cutoff']}}
                        </div>
                    </div>
                </div>
                <div>
                    <label>Estimated Time of Departure</label>
                    <div>
                        <date-time-picker
                            class="w-full form-control form-input form-input-bordered mb-1"
                            :value="item.etd"
                            @change="(value) => {handleEtdChange(value, item)}"
                            :dateFormat="pickerFormat"
                            :placeholder="placeholder"
                            :enable-time="false"
                            :enable-seconds="false"
                            :first-day-of-week="firstDayOfWeek"
                            :class="`${ (typeof errorMessages.etd!='undefined') ? 'form-input-bordered border-danger' : ''}`"
                          />
                        <div v-show="(typeof errorMessages.etd!='undefined')" class="help-text error-text text-danger mt-2">
                          {{errorMessages['etd']}}
                        </div>
                    </div>
                </div>
                <!--<div style="display:none" v-show="key==0 && typeof resourceId!='undefined'">
                    <label>Estimated Time of Departure</label>
                    <date-time-picker
                        class="w-full form-control form-input form-input-bordered"
                        :value="default_schedule_etd"
                        :dateFormat="pickerFormat"
                        :placeholder="placeholder"
                        :enable-time="false"
                        :enable-seconds="false"
                        :first-day-of-week="firstDayOfWeek"
                        :class="errorClasses"
                      />
                </div>-->
                <div>
                    <label>Location To</label>
                    <div>
                        <select :id="`shipment-schedule-terminal-region-to-select-${key}`" v-model="item.location_to" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Select Location</option>
                            <option v-bind:key="`btlt-${otr}`" v-show="terminalRegions.length>0" v-for="(option,otr) in terminalRegions" :value="option.id">{{option.name}}</option>
                        </select>
                    </div>
                </div>
                <!-- <div style="display:none" v-show="key==0 && typeof resourceId!='undefined'">
                    <label>Estimated Time of Arrival</label>
                    <date-time-picker
                        class="w-full form-control form-input form-input-bordered"
                        :value="default_schedule_eta"
                        :dateFormat="pickerFormat"
                        :placeholder="placeholder"
                        :enable-time="false"
                        :enable-seconds="false"
                        :first-day-of-week="firstDayOfWeek"
                        :class="errorClasses"
                      />
                </div> -->
                <div>
                    <label>Estimated Time of Arrival</label>
                    <div>
                        <date-time-picker
                            class="w-full form-control form-input form-input-bordered mb-1"
                            :value="item.eta"
                            :dateFormat="pickerFormat"
                            @change="(value) => {handleEtaChange(value, item)}"
                            :placeholder="placeholder"
                            :enable-time="false"
                            :enable-seconds="false"
                            :first-day-of-week="firstDayOfWeek"
                            :disabled="isReadonly"
                            :class="`${ (typeof errorMessages.eta!='undefined') ? 'form-input-bordered border-danger' : ''}`"
                          />
                        <div v-show="(typeof errorMessages.eta!='undefined')" class="help-text error-text text-danger mt-2">
                          {{errorMessages['eta']}}
                        </div>
                    </div>
                </div>

                <div v-if="item.mode == 'Ocean'">
                    <label>Estimated Time of Berthing</label>
                    <div>
                        <date-time-picker
                            class="w-full form-control form-input form-input-bordered mb-1"
                            :value="item.etb"
                            :dateFormat="pickerFormat"
                            @change="(value) => {handleFieldChange(value, item, 'etb')}"
                            :placeholder="placeholder"
                            :enable-time="false"
                            :enable-seconds="false"
                            :first-day-of-week="firstDayOfWeek"
                            :disabled="isReadonly"
                            :class="`${ (typeof errorMessages.etb!='undefined') ? 'form-input-bordered border-danger' : ''}`"
                          />
                        <div v-show="(typeof errorMessages.etb!='undefined')" class="help-text error-text text-danger mt-2">
                          {{errorMessages['etb']}}
                        </div>
                    </div>
                </div>

                <div>
                    <label>Mode</label>
                    <div>
                        <select v-model="item.mode" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Select Mode</option>
                            <option v-bind:key="`mv-${mk}`" v-for="(option,mk) in mode_values" :value="option">{{option}}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label>Carrier</label>
                    <div>
                        <v-select :id="`schedule-carrier-${key}`" v-model="item.carrier_name.id" :reduce="name => name.id" label="name" :options="carriers.filter(e => !item.mode || item.mode == 'Rail' || item.mode == 'Truck' || (e.type != null && e.type.toLowerCase() == item.mode.toLowerCase()))">
                        </v-select>
                    </div>
                </div>
<!--                <div>-->
<!--                    <label>Co Loader Field</label>-->
<!--                    <div>-->
<!--                        <v-select :id="`coloader-${key}`" v-model="item.vendor_id" :options="vendors" :reduce="company_name => company_name.id" label="company_name" />-->
<!--                    </div>-->
<!--                </div>-->
                <div v-show="item.mode == 'Air' || item.mode == 'Ocean'">
                    <label>Agent</label>
                    <div>
                        <v-select :id="`agent-${key}`" v-model="item.agent_id" :options="agents" :reduce="name => name.id" label="name" />
                        <input v-if="!item.agent_booking_sent" type="hidden" v-model="item.agent_booking_sent=false">
                    </div>
                </div>
                <div>
                    <label>Type</label>
                    <div>
                       <select v-model="item.type" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Type</option>
                            <option v-bind:key="`bttt-${ot}`" v-for="(option,ot) in types" :value="option">{{ option }}</option>
                        </select>
                    </div>
                </div>
                <!-- start schedule leg-->
                <div v-bind:key="`btslg-${keySecond}`" class="schedule-leg-group" v-for="(leg, keySecond) in item.legs">
                    <div class="border-b border-40" style="padding-bottom: 4px;">
                        <label style="font-weight: bold; width: 100%;">Schedule Leg</label>
                        <div>

                        </div>
                    </div>
                    <div>
                        <label>Location From</label>
                        <div>
                            <select :id="`shipment-schedule-legs-location-from-select-dept-${key}-${keySecond}`" v-model="leg.location_from" disabled class="w-full form-control form-select form-select-bordered" >
                                <option value="">Select Location</option>
                                <option v-bind:key="`btlftr-${otr}`" v-show="terminalRegions.length>0" v-for="(option,otr) in terminalRegions" :value="option.id" >{{option.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: 15px;">
                        <label>Location To</label>
                        <div>
                            <select :id="`shipment-schedule-terminal-region-to-select-${key}-${keySecond}`" v-model="leg.location_to" class="w-full form-control form-select form-select-bordered" >
                                <option value="">Select Location</option>
                                <option v-bind:key="`btlttr-${otr}`" v-show="terminalRegions.length>0" v-for="(option,otr) in terminalRegions" :value="option.id">{{option.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label>Estimated Time of Departure</label>
                        <div>
                            <date-time-picker
                                class="w-full form-control form-input form-input-bordered mb-1"
                                :value="leg.etd"
                                :dateFormat="pickerFormat"
                                @change="(value) => {handleEtdChange(value, leg)}"
                                :placeholder="placeholder"
                                :enable-time="false"
                                :enable-seconds="false"
                                :first-day-of-week="firstDayOfWeek"
                                :disabled="isReadonly"
                            />
                        </div>
                    </div>

                    <div>
                        <label>Estimated Time of Arrival</label>
                        <div>
                            <date-time-picker
                                class="w-full form-control form-input form-input-bordered mb-1"
                                :value="leg.eta"
                                :dateFormat="pickerFormat"
                                @change="(value) => {handleEtaChange(value, leg)}"
                                :placeholder="placeholder"
                                :enable-time="false"
                                :enable-seconds="false"
                                :first-day-of-week="firstDayOfWeek"
                                :disabled="isReadonly"
                            />
                        </div>
                    </div>
                    <div v-if="leg.mode == 'Ocean'">
                        <label>Estimated Time of Berthing</label>
                        <div>
                            <date-time-picker
                                class="w-full form-control form-input form-input-bordered mb-1"
                                :value="leg.etb"
                                :dateFormat="pickerFormat"
                                @change="(value) => {handleFieldChange(value, leg, 'etb')}"
                                :placeholder="placeholder"
                                :enable-time="false"
                                :enable-seconds="false"
                                :first-day-of-week="firstDayOfWeek"
                                :disabled="isReadonly"
                            />
                        </div>
                    </div>

                    <div>
                        <label>Mode</label>
                        <div>
                            <select v-model="leg.mode" class="w-full form-control form-select form-select-bordered" >
                                <option value="">Select Mode</option>
                                <option v-bind:key="`btm-${omv}`" v-for="(option,omv) in mode_values" :value="option">{{option}}</option>
                            </select>
                        </div>
                    </div>
                    <div v-if="['Ocean','Air','Rail'].includes(leg.mode)">
                        <label>Carrier</label>
                        <div>
                            <v-select :id="`schedule-carrier-${key}`" v-model="leg.carrier" :reduce="name => name.id" label="name" :options="carriers.filter(e => leg.mode == 'Rail' || (e.type != null && e.type.toLowerCase() == leg.mode.toLowerCase()))">
                            </v-select>
                        </div>
                    </div>

                    <div v-show="typeof item.legs!=='undefined' && item.legs.length > 0">
                        <button class="btn btn-default btn-danger inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="removeLeg(key,keySecond)" >Remove Leg</button>
                    </div>
                </div>
                <div id="shipment-schedule-group-submit-group">
                    <button v-show="formGroups.length>0" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addLeg(key, item.id)" >Add Leg</button>
                </div>
                <!-- end schedule leg-->
                <!-- start buy rates group -->
                <div v-bind:key="`btbrgbr-${brKey}`" style="width: 165%;" class="buy-rates-group" v-for="(buy_rate, brKey) in item.buy_rates">
                     <div class="border-b border-40" style="border-top: 1px solid #eef1f4; padding-bottom: 0px;">
                        <label style="font-weight: bold; line-height: 26px;">Buy Rate</label>
                        <div>
                        </div>
                    </div>
                    <div style="margin-top: 15px; display: flex; width: 100%;">
                        <div style="width: 14.28%; padding-right: 5px;">
                            <select :id="`shipment-schedule-buy-service-to-select-${key}-${brKey}`" v-model="buy_rate.service_id" class="w-full form-control form-select form-select-bordered" >
                                <option value="">Select Service</option>
                                <option v-bind:key="`btss-${os}`" v-show="services.length>0" v-for="(option,os) in services" :value="option.id">{{option.name}}</option>
                            </select>
                        </div>
                        <div style="width: 14.28%;line-height: 36px;text-align: center; padding-right: 5px;">
                            Charge Per
                        </div>
                        <div style="width: 14.28%; padding-right: 5px;">
                            <select :id="`shipment-schedule-buy-container-size-to-select-${key}-${brKey}`" v-model="buy_rate.container_size_id" class="w-full form-control form-select form-select-bordered" >
                                <option v-bind:key="`btrsz-${rs}`" v-show="sizes.length>0" v-for="(option,rs) in rateSizes" :value="option.id">{{option.name}}</option>
                            </select>
                        </div>
                        <div style="width: 14.28%; padding-right: 5px;">
                            <input
                                :id="`buy-rate-amount-${brKey}`"
                                type="text"
                                class="w-full form-control form-input form-input-bordered"
                                placeholder="Amount"
                                v-model="buy_rate.amount"
                            />
                        </div>
                        <div style="width: 14.28%;padding-right: 5px;">
                            <input
                                :id="`buy-rate-quantity-${brKey}`"
                                type="text"
                                class="w-full form-control form-input form-input-bordered"
                                placeholder="Quantity"
                                v-model="buy_rate.qty"
                            />
                        </div>
                        <div style="width: 14.28%; padding-right: 5px; display: none !important;">
                            <date-time-picker
                            v-model="buy_rate.valid_from"
                            class="w-full form-control form-input form-input-bordered mb-1"
                            :dateFormat="pickerFormat"
                            @change="(value) => {handleBuyRateValidFromChange(value, item, key, brKey, buy_rate)}"
                            placeholder="Valid From"
                            :enable-time="false"
                            :enable-seconds="false"
                            :first-day-of-week="firstDayOfWeek"
                            :disabled="isReadonly"
                          />
                        </div>
                        <div style="width: 14.28%;">
                            <date-time-picker
                            v-model="buy_rate.valid_to"
                            class="w-full form-control form-input form-input-bordered mb-1"
                            :dateFormat="pickerFormat"
                            @change="(value) => {handleBuyRateValidToChange(value, item, key, brKey, buy_rate)}"
                            placeholder="Valid To"
                            :enable-time="false"
                            :enable-seconds="false"
                            :first-day-of-week="firstDayOfWeek"
                            :disabled="isReadonly"
                          />
                        </div>
                    </div>
                   <div v-show="typeof item.buy_rates!=='undefined' && item.buy_rates.length > 0">
                        <button class="btn btn-default btn-danger inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="removeBuyRate(key,brKey)" >Remove Buy Rate</button>
                    </div>
                </div>
                <div id="shipment-schedule-group-submit-group">
                    <button v-show="formGroups.length>0" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addBuyRate(key, item.id)" >Add Buy Rate</button>
                </div>
                <!-- end buy rates group -->
                <!-- start sell rates group -->
                <div v-if="typeof item.sell_rates!=='undefined' && item.sell_rates.length > 0">
                </div>
                <div v-bind:key="`btsrg-${srKey}`" style="width: 165%;"  class="sell-rates-group" v-for="(sell_rate, srKey) in item.sell_rates">
                    <div class="border-b border-40" style="border-top: 1px solid #eef1f4; padding-bottom: 0px;">
                        <label style="font-weight: bold; width: 100%; line-height: 26px;">Sell Rate</label>
                        <div>

                        </div>
                    </div>
                    <div style="margin-top: 15px; display: flex; width: 100%;">
                        <div style="width: 14.28%;">
                            <select :id="`shipment-schedule-sell-service-to-select-${key}-${srKey}`" v-model="sell_rate.service_id" class="w-full form-control form-select form-select-bordered" >
                                <option value="">Select Service</option>
                                <option v-bind:key="`btsssr-${oss}`" v-show="services.length>0" v-for="(option,oss) in services" :value="option.id">{{option.name}}</option>
                            </select>
                        </div>
                        <div style="width: 14.28%;line-height: 36px;text-align: center;">
                            Charge Per
                        </div>
                        <div style="width: 14.28%; padding-right: 5px;">
                            <select id="`shipment-schedule-sell-container-size-to-select-${key}-${srKey}`" v-model="sell_rate.container_size_id" class="w-full form-control form-select form-select-bordered" >
                                <option v-bind:key="`btsscp-${rszs}`" v-show="sizes.length>0" v-for="(option,rszs) in rateSizes" :value="option.id">{{option.name}}</option>
                            </select>
                        </div>
                        <div style="width: 14.28%; padding-right: 5px;">
                            <input
                                :id="`sell-rate-amount-${srKey}`"
                                type="text"
                                class="w-full form-control form-input form-input-bordered"
                                placeholder="Amount"
                                v-model="sell_rate.amount"
                            />
                        </div>
                        <div style="width: 14.28%; padding-right: 5px;">
                            <input
                                :id="`sell-rate-quantity-${srKey}`"
                                type="text"
                                class="w-full form-control form-input form-input-bordered"
                                placeholder="Quantity"
                                v-model="sell_rate.qty"
                            />
                        </div>
                        <div style="width: 14.28%; padding-right: 5px; display: none !important;">
                            <date-time-picker
                            v-model="sell_rate.valid_from"
                            class="w-full form-control form-input form-input-bordered mb-1"
                            :dateFormat="pickerFormat"
                            @change="(value) => {handleSellRateValidFromChange(value, item, key, srKey, sell_rate)}"
                            placeholder="Valid From"
                            :enable-time="false"
                            :enable-seconds="false"
                            :first-day-of-week="firstDayOfWeek"
                            :disabled="isReadonly"
                          />
                        </div>
                        <div style="width: 14.28%;">
                            <date-time-picker
                            v-model="sell_rate.valid_to"
                            class="w-full form-control form-input form-input-bordered mb-1"
                            :dateFormat="pickerFormat"
                            @change="(value) => {handleSellRateValidToChange(value, item, key, srKey, sell_rate)}"
                            placeholder="Valid To"
                            :enable-time="false"
                            :enable-seconds="false"
                            :first-day-of-week="firstDayOfWeek"
                            :disabled="isReadonly"
                          />
                        </div>
                    </div>
                    <button class="btn btn-default btn-danger inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="removeSellRate(key,srKey)" >Remove Sell Rate</button>
                </div>
                <div id="shipment-schedule-group-submit-group">
                    <button v-show="formGroups.length>0" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addSellRate(key, item.id)" >Add Sell Rate</button>
                </div>
                <!-- end sell rates group -->
                <div>
                    <label>Agent Booking Notes:</label>
                    <div>
                        <textarea
                            v-model="item.agent_booking_notes"
                            @change="(value) => {handleFieldChange(item.agent_booking_notes, item, 'agent_booking_notes')}"
                            :id="`shipment-schedule-agent-booking-notes-${key}`"
                            rows="5"
                            placeholder="Agent Booking Notes"
                            class="w-full form-control form-input form-input-bordered py-3 h-auto"
                            maxlength="1800"
                        >
                        </textarea>
                    </div>
                </div>
                <div>
                    <button class="btn btn-default btn-danger inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="removeGroup(key)" >Remove Schedule</button>
                </div>
            </div>
            <div id="shipment-schedule-group-submit-group">
                <button v-show="formGroups.length>=0" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addGroup()" >Add Schedule</button>
            </div>
            <div class="customErrorMessages">
                <div v-show="errorMessages['etd']">{{errorMessages['etd']}}</div>
                <div v-show="errorMessages['eta']">{{errorMessages['eta']}}</div>
            </div>

        </template>
    </default-field>
</template>

<script>
import { FormField, InteractsWithDates } from 'laravel-nova'
import jQuery from 'jquery'
import './sass/field.scss'
import _ from 'lodash'
import moment from 'moment'

export default {
    mixins: [FormField, InteractsWithDates],
    props: ['resourceName', 'resourceId', 'field', 'shipmentSupplierGroupField', 'supplierOptions', 'incoTermValues', 'sizes', 'carriers', 'types', 'services', 'terminalRegions', 'rateSizes', 'vendors', 'agents'],
    data: function() {
        return {
            error_messages: [],
            is_create: true,
            scheduleModal: false,
            has_submitted: false,
            selected_schedules: [],
            terminal_regions_options: [],
            default_schedule_etd: null,
            default_schedule_eta: null,
            mode_values: [
                'Ocean',
                'Air',
                'Rail',
                'Truck'
            ],
            sell_cost_labels: [],
            cost_labels: [],
            selected_suppliers: [],
            formGroups: (this.field.value=='' || this.field.value==null) ? [] : JSON.parse(this.field.value)
        }
    },
    updated() {
        this.$emit('updateScheduleGroup', this.formGroups)
    },
    async mounted() {
        if(this.formGroups && this.formGroups.length > 0){
            for(let key = 0; key < this.formGroups.length; key++){
                if(this.formGroups[key].legs && this.formGroups[key].legs.length > 0){
                    for(let keySecond = 0; keySecond < this.formGroups[key].legs.length; keySecond++){
                        if(this.formGroups[key].legs[keySecond].location_from) continue;
                        this.formGroups[key].legs[keySecond].location_from = (keySecond == 0) ? this.formGroups[key].location_to : this.formGroups[key].legs[keySecond - 1].location_to
                    }
                }
            }
        }
      /*
      var token = jQuery('meta[name="csrf-token"]').attr('content')
      var transitValue = "In Transit - Pending AN/Customs/Freight Payment"
      var transitValueBefore = "Pending DO"

        //set default selected schedules
       let getSchedules = (this.field.value=='' || this.field.value==null) ? [] : JSON.parse(this.field.value)
       this.is_create = (this.field.value=='' || this.field.value==null) ? true : false

        if ( typeof this.resourceId!='undefined' ) {
            jQuery.ajax({
            method: 'GET',
            url: '/custom/shipment',
            data: {
                _token: token,
                id: this.resourceId
            }
            }).done( response => {
                let { status, result } = response

                if (typeof status!=='undefined') {
                    if(status=='ok') {
                        this.default_schedule_etd = moment(result.etd).format(this.format)
                        this.default_schedule_eta = moment(result.eta).format(this.format)

                        if(getSchedules.length==0 && (result.etd!=null || result.eta!=null)) {

                          var setId = new Date().getTime()

                          console.log('goes here')
                          this.formGroups.push({
                              id: setId,
                              eta: (result.etd!=null) ? this.default_schedule_eta : null,
                              etd: (result.eta!=null) ? this.default_schedule_etd : null,
                              location_from: '',
                              location_to: '',
                              legs: [],
                              type: null,
                              carrier_name: null,
                              mode: '',
                              price: null,
                              size_id: null,
                              selling_size_id: null,
                              selling_price: null,
                              sell_rates: [],
                              buy_rates: [],
                              is_confirmed: 0,
                              etaError: new Error(),
                              etdError: new Error()
                          })



                        }
                    }
                }

            })
        }

        /*
        jQuery.ajax({
            method: 'GET',
            url: '/custom/terminal-regions',
            data: {
                _token: token
            }

        }).done( response => {
            let { results } = response
            if( results.length>0)  {
                this.terminal_regions_options = results
            }
        })*/

        /*
        if (typeof this.formGroups[0]!=='undefined') {
            if (typeof this.formGroups[0].suppliers_group_bookings=='undefined')
                this.formGroups[0].suppliers_group_bookings = []
        }*/


    },
    computed: {
        errorMessages() {
            return (typeof this.firstError!='undefined') ? JSON.parse(this.firstError) : []
        },
        firstDayOfWeek() {
            return this.field.firstDayOfWeek || 0
        },
        schedule_count() {
            return (this.field.value!='' && this.field.value!=null) ? (JSON.parse(this.field.value)).length : (this.formGroups.length>0) ? this.formGroups.length : 0
        },
        placeholder() {
          return this.field.placeholder || moment().format(this.format)
        },

        format() {
          return this.field.format || 'YYYY-MM-DD'
        },

        pickerFormat() {
          return this.field.pickerFormat || 'Y-m-d'
        },
    },
    methods: {

        filename(file) {
            // console.log(this.path)
            // return file
            // const path = require('path')
            // return path
            //  .parse(file)
            //  .base
            return file.split('\\')
                .pop()
                .split('/')
                .pop()

        },
        fileChange(event, key, keySecond, fieldName) {

            var {
                target
            } = event
            var path = target.value

            switch (fieldName) {
                case 'packing_list':
                    this.formGroups[key].suppliers_group_bookings[keySecond].packing_list = target.files[0]
                    break
                case 'commercial_documents':
                    this.formGroups[key].suppliers_group_bookings[keySecond].commercial_documents = target.files[0]
                    break
                case 'commercial_invoice':
                    this.formGroups[key].suppliers_group_bookings[keySecond].commercial_invoice = target.files[0]
                    break
                default:
                    this.formGroups[key].suppliers_group_bookings[keySecond].packing_list = target.files[0]
            }

        },
        openPackingListRemoveModal(key, keySecond) {
            this.formGroups[key].suppliers_group_bookings[keySecond].packingListRemoveModal = true
        },
        openCommercialDocumentsRemoveModal(key, keySecond) {
            this.formGroups[key].suppliers_group_bookings[keySecond].commercialDocumentsRemoveModal = true
        },
        openCommercialInvoiceRemoveModal(key, keySecond) {
            this.formGroups[key].suppliers_group_bookings[keySecond].commercialInvoiceRemoveModal = true
        },
        selectSupplier(id, key) {

            //var findExisting = _.findIndex( this.selected_suppliers, o => (o.key!=key && o.value==id))
            this.selected_suppliers.push({
                key,
                value: id
            })
        },
        removeGroup(key) {
            /*
            jQuery(`#shipment-schedule-group-${key}`).remove()
            var filterFormGroups = _.filter(this.formGroups,o => (o.id!=item.id))
            this.errors[key] = false
            this.formGroups = filterFormGroups
            this.value = JSON.stringify(filterFormGroups)*/
            this.formGroups.splice(key,1)
            this.cost_labels.splice(key,1)
            this.sell_cost_labels.splice(key,1)
        },
        closeModal() {
          this.scheduleModal = false
        },
        openModal() {
            this.scheduleModal = true
        },
        handleBuyRateValidToChange(value, item, key, keySecond, br) {
            this.formGroups[key].buy_rates[keySecond].valid_to = value
            br.valid_to = value
        },
        handleBuyRateValidFromChange(value, item, key, keySecond, br) {
            this.formGroups[key].buy_rates[keySecond].valid_from = value
            br.valid_from = value
        },
        handleSellRateValidToChange(value, item, key, keySecond, sr) {
            this.formGroups[key].sell_rates[keySecond].valid_to = value
            sr.valid_to = value
        },
        handleSellRateValidFromChange(value, item, key, keySecond, sr) {
            this.formGroups[key].sell_rates[keySecond].valid_from = value
            sr.valid_from = value
        },
        handleEtaChange(value, item) {
           // this.field.value = value
            item.eta = value
        },
        handleCANEtaChange(value, item){
            item.carrier_arrival_notice_eta = value
        },


        handleEtdChange(value, item) {
            item.etd = value
        },
        handleFieldChange(value, item, field) {
            let check = true;
            if(item[field]) check = false;
            item[field] = value
            if(check)
              this.$emit('updateScheduleGroup', this.formGroups)
        },
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },
        updateSellCostLabel(i, key) {
            this.sell_cost_labels[key] = (typeof _.find(this.sizes, {
            id
            })!=='undefined') ? `Charge per ${_.find(this.sizes, {
                id
            }).name}` : ''
        },
        updateCostLabel(id, key) {
            this.cost_labels[key] = (typeof _.find(this.sizes, {
            id
            })!=='undefined') ? `Charge per ${_.find(this.sizes, {
                id
            }).name}` : ''

        },
        removeLeg(key, keySecond) {
            this.formGroups[key].legs.splice(keySecond, 1)
        },
        removeSellRate(key, keySecond) {
          this.formGroups[key].sell_rates.splice(keySecond, 1)
        },
        removeBuyRate(key, keySecond) {
          this.formGroups[key].buy_rates.splice(keySecond, 1)
        },
        addBuyRate(key, id) {
            let setId = new Date().getTime()
            this.formGroups[key].buy_rates.push({
                id: setId,
                shipment_schedules_id: id,
                service_id: null,
                amount: null,
                qty: null,
                container_size_id: null,
                valid_from: null,
                valid_to: null
            })
        },
        addSellRate(key, id) {
            let setId = new Date().getTime()
            this.formGroups[key].sell_rates.push({
                id: setId,
                shipment_schedules_id: id,
                service_id: null,
                amount: null,
                qty: null,
                container_size_id: null,
                valid_from: null,
                valid_to: null
            })
        },
        addLeg(key, id) {
            let setId = new Date().getTime()
            let newLeg = {
                id: setId,
                schedule_id: id,
                mode: '',
                etd: null,
                eta: null,
                etb: null,
                location_to: null,
                carrier: null
            }
            newLeg.location_from = this.formGroups[key].legs.length == 0 ? this.formGroups[key].location_to : this.formGroups[key].legs[this.formGroups[key].legs.length-1].location_to
            this.formGroups[key].legs.push(newLeg)
        },
        addSupplierGroup(key, id) {

            let setId = new Date().getTime()

            this.formGroups[key].suppliers_group_bookings.push({
                id: setId,
                schedule_id: id,
                hbl_copy: null,
                packing_list: null,
                commercial_documents: null,
                commercial_invoice: null,
                po_num: '',
                volume: '',
                supplier_id: '',
                kg: '',
                cbm: '',
                incoterm_id: '',
                hbl_num: '',
                product_description: '',
                //total_weight: '',
                total_cartons: '',
                bl_status: 'Pending',
                ams_num: '',
                packingListRemoveModal: []

            })
        },
        addGroup() {
            //this.current_id = parseInt(new Date().getTime())
            //let setId = (this.formGroups.length>0) ? this.formGroups[this.formGroups.length - 1].id + 1 : 0

            let setId = new Date().getTime()
            this.formGroups.push({
                 id: setId,
                    eta: '',
                    etd: '',
                    etb: '',
                    cutoff: '',
                    location_from: '',
                    location_to: '',
                    mode: '',
                    legs: [],
                    type: null,
                    carrier_name: {
                        id: 0
                    },
                    //carrier_name: null,
                    size_id: null,
                    price: null,
                    selling_size_id: null,
                    selling_price: null,
                    sell_rates: [],
                    buy_rates: [],
                    is_confirmed: 0,
                    vendor_id: 0,
                    etaError: new Error(),
                    etdError: new Error(),
                    agent_booking_notes: ''
            })
            /*
            if(this.schedule_count<2) {

                let setId = new Date().getTime()

                this.formGroups.push({
                    id: setId,
                    eta: '',
                    etd: '',
                    location_from: '',
                    location_to: '',
                    mode: '',
                    etaError: new Error(),
                    etdError: new Error()
                })


                this.value = JSON.stringify(this.formGroups)

            } else {
              this.scheduleModal = true
              //  alert('You are not allowed to add anymore schedule. Current limit is to 2.')
            }*/

        },
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
          //  formData.append(this.field.attribute, this.value || '')
            if(this.formGroups.length>0) {
                formData.append(this.field.attribute, JSON.stringify(this.formGroups))
            }else {
                formData.append(this.field.attribute, '[]')
            }
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
    },
}
</script>
