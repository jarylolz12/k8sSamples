<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <input
                :id="field.attribute"
                type="hidden"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value"
            />
            <div v-if="formGroups.length == 0">
                <div style="padding-top: 8px;">
                    No Schedule added yet to the Booking Tab.
                </div>
            </div>
            <div v-bind:key="`ssgd-${key}`" :id="`shipment-schedule-group-dept-${item.id}`" v-for="(item,key) in formGroups" class="shipment-schedule-group" style="border-bottom: 1px solid #eef1f4; padding-top: 4px;">
                <div>
                    <label>Location From</label>
                    <div>
                        <select :id="`shipment-schedule-terminal-region-from-select-dept-${key}`" v-model="item.location_from" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Select Location</option>
                            <option v-bind:key="`dntr-${ok}`" v-show="terminalRegions.length>0" v-for="(option,ok) in terminalRegions" :value="option.id">{{option.name}}</option>
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
                <div>
                    <label>Location To</label>
                    <div>
                        <select :id="`shipment-schedule-terminal-region-to-select-dept-${key}`" v-model="item.location_to" class="w-full form-control form-select form-select-bordered" @change="handleLocationToChange(key)">
                            <option value="">Select Location</option>
                            <option v-bind:key="`dnilt-${okk}`" v-show="terminalRegions.length>0" v-for="(option, okk) in terminalRegions" :value="option.id">{{option.name}}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label>
                        Estimated Time of Arrival
<!--                         <span class=" px-2" style="cursor: pointer" @click="toggleScacModal">
                          <svg  fill="rgb(41, 153, 241)" viewBox="0 0 24 24" width="20px" height="20px">
                            <path d="M 12 2 C 6.4889971 2 2 6.4889971 2 12 C 2 17.511003 6.4889971 22 12 22 C 17.511003 22 22 17.511003 22 12 C 22 6.4889971 17.511003 2 12 2 z M 12 4 C 16.430123 4 20 7.5698774 20 12 C 20 16.430123 16.430123 20 12 20 C 7.5698774 20 4 16.430123 4 12 C 4 7.5698774 7.5698774 4 12 4 z M 11 7 L 11 9 L 13 9 L 13 7 L 11 7 z M 11 11 L 11 17 L 13 17 L 13 11 L 11 11 z"/>
                          </svg>
                        </span> -->
                    </label>
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
                            <option v-bind:key="`dbmv-${omv}`" v-for="(option,omv) in mode_values" :value="option">{{option}}</option>
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
                    </div>
                </div>
                <div>
                    <label>Type</label>
                    <div>
                       <select v-model="item.type" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Type</option>
                            <option v-bind:key="`dntypes-${otk}`" v-for="(option,otk) in types" :value="option">{{ option }}</option>
                        </select>
                    </div>
                </div>
                <!-- start schedule leg-->
                <div v-bind:key="`dnslg-${keySecond}`" class="schedule-leg-group" v-for="(leg, keySecond) in item.legs">
                    <div>
                        <label>Location From</label>
                        <div>
                            <select :id="`shipment-schedule-legs-location-from-select-dept-${key}-${keySecond}`" v-model="leg.location_from" disabled class="w-full form-control form-select form-select-bordered" >
                                <option value="">Select Location</option>
                                <option v-bind:key="`dntrlf-${otrr}`" v-show="terminalRegions.length>0" v-for="(option,otrr) in terminalRegions" :value="option.id" >{{option.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label>Location To</label>
                        <div>
                            <select :id="`shipment-schedule-terminal-region-to-select-dept-${key}-${keySecond}`" v-model="leg.location_to" class="w-full form-control form-select form-select-bordered" @change="handleChangeLocationToLeg(key,keySecond)" >
                                <option value="">Select Location</option>
                                <option v-bind:key="`dnltlt-${otrkkk}`" v-show="terminalRegions.length>0" v-for="(option, otrkkk) in terminalRegions" :value="option.id">{{option.name}}</option>
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
                                <option v-bind:key="`dnm-${omv}`" v-for="(option, omv) in mode_values" :value="option">{{option}}</option>
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
                <div id="shipment-schedule-dept-group-submit-group">
                    <button v-show="formGroups.length>0" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addLeg(key, item.id)" >Add Leg</button>
                </div>
                <!-- end schedule leg-->
                <!-- start buy rates group -->
                <div v-bind:key="`dnbr-${brKey}`" style="width: 165%;" class="buy-rates-group" v-for="(buy_rate, brKey) in item.buy_rates">
                     <div class="border-b border-40" style="border-top: 1px solid #eef1f4; padding-bottom: 0px;">
                        <label style="font-weight: bold; line-height: 26px;">Buy Rate</label>
                        <div>
                        </div>
                    </div>
                    <div style="margin-top: 15px; display: flex; width: 100%;">
                        <div style="width: 14.28%;">
                            <select :id="`shipment-schedule-buy-service-to-select-${key}-${brKey}`" v-model="buy_rate.service_id" class="w-full form-control form-select form-select-bordered" >
                                <option value="">Select Service</option>
                                <option v-bind:key="`dnbrs-${os}`" v-show="services.length>0" v-for="(option,os) in services" :value="option.id">{{option.name}}</option>
                            </select>
                        </div>
                         <div style="width: 14.28%;line-height: 36px;text-align: center;">
                            Charge Per
                        </div>
                         <div style="width: 14.28%; padding-right: 5px;">
                            <select :id="`shipment-schedule-buy-container-size-to-select-${key}-${brKey}`" v-model="buy_rate.container_size_id" class="w-full form-control form-select form-select-bordered" >
                                <option v-bind:key="`dncp-${ors}`" v-show="sizes.length>0" v-for="(option,ors) in rateSizes" :value="option.id">{{option.name}}</option>
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
                         <div style="width: 14.28%; padding-right: 5px;">
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
                <div style="width: 165%;" v-if="typeof item.sell_rates!=='undefined' && item.sell_rates.length > 0">
                    <div style="width: 100%;" v-bind:key="`dnsssr-${srKey}`" class="sell-rates-group" v-for="(sell_rate, srKey) in item.sell_rates">
                        <div class="border-b border-40" style="border-top: 1px solid #eef1f4; padding-bottom: 0px;">
                            <label style="font-weight: bold; width: 100%; line-height: 26px;">Sell Rate</label>
                            <div>

                            </div>
                        </div>
                        <div style="margin-top: 15px; display: flex; width: 100%;">
                            <div style="width: 14.28%;">
                                <select :id="`shipment-schedule-sell-service-to-select-${key}-${srKey}`" v-model="sell_rate.service_id" class="w-full form-control form-select form-select-bordered" >
                                    <option value="">Select Service</option>
                                    <option v-bind:key="`dnsrsss-${os}`" v-show="services.length>0" v-for="(option,os) in services" :value="option.id">{{option.name}}</option>
                                </select>
                            </div>
                            <div style="width: 14.28%;line-height: 36px;text-align: center;">
                                Charge Per
                            </div>
                            <div style="width: 14.28%; padding-right: 5px;">
                                <select :id="`shipment-schedule-sell-container-size-to-select-${key}-${srKey}`" v-model="sell_rate.container_size_id" class="w-full form-control form-select form-select-bordered" >
                                    <option v-bind:key="`dncpper-${orsss}`" v-show="sizes.length>0" v-for="(option, orsss) in rateSizes" :value="option.id">{{option.name}}</option>
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
                </div>
                <div id="shipment-schedule-group-submit-group">
                    <button v-show="formGroups.length>0" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addSellRate(key, item.id)" >Add Sell Rate</button>
                </div>
                <!-- end sell rates group -->
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors, InteractsWithDates, Errors } from 'laravel-nova'
import jQuery from 'jquery'
import _ from 'lodash'

export default {
    mixins: [FormField, HandlesValidationErrors, InteractsWithDates],
    props: ['resourceName', 'resourceId', 'field', 'sizes', 'carriers', 'types', 'terminalRegions', 'services', 'rateSizes', 'vendors', 'agents'],
    data: function() {
        return {
            errors: {},
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
            formGroups: (this.field.value=='' || this.field.value==null) ? [] : this.field.value
        }
    },
    updated() {
        this.$emit('updateScheduleGroup', this.formGroups)
    },
    mounted() {
      var token = jQuery('meta[name="csrf-token"]').attr('content')
      var transitValue = "In Transit - Pending AN/Customs/Freight Payment"
      var transitValueBefore = "Pending DO"


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


      /* let pbValue = "Pending Billing"
      let pbValueBefore = "Pending Freights/Customs Release"

      //map each tabs
      let tabGroupButtons = [
        {
            className: 'delivery-order',
            label: 'Delivery Order'
        },
        {
            className: 'arrival-notice',
            label: 'Arrival Notice'
        },
        {
            className: 'header-information',
            label: 'Header Information'
        },
        {
            className: 'departure-notice',
            label: 'Departure Notice'
        }
      ]

      tabGroupButtons.map (tabGroupButton => {
        let divAppendContent = `<div class="tab-save flex border-b border-40 remove-bottom-border"><div class="w-1/5 px-8 py-6"><label for="containers_group" class="inline-block text-80 pt-2 leading-tight"></label></div> <div class="py-6 px-8 w-1/2"><div><button class="btn btn-default btn-primary inline-flex items-center relative mr-3 custom-save-shipment-${tabGroupButton.className}">Save</button></div><div class="help-text help-text mt-2"></div></div></div>`

        if (jQuery(`div[label="${tabGroupButton.label}"] > div`).find('.tab-save').length == 0 ) {
            jQuery(`div[label="${tabGroupButton.label}"] > div`).append(divAppendContent)
            jQuery(`.custom-save-shipment-${tabGroupButton.className}`).data('loading', false)
            jQuery(`.custom-save-shipment-${tabGroupButton.className}`).click(function(e) {
                e.preventDefault()

                var loading = jQuery(this).data('loading')
                var thisHere = this
                if (!loading) {
                    jQuery(this).html('Saving...')
                    jQuery(this).data('loading', true)
                    jQuery.ajax({
                    method: 'POST',
                    url: '/custom/shipment-tab-save',
                    data: {
                        _token: token,
                        tabLabel: tabGroupButton.className
                    }
                    }).done( response => {
                        jQuery(thisHere).html('Save')
                        jQuery(thisHere).data('loading', false)
                        console.log(response)
                    })
                }


            })
        }
      })




        */

    /*


      setInterval(() => {

        /*
        if (jQuery('div[label="Departure Notice"]').length >0 && jQuery('div[label="Departure Notice"]').is(':visible'))
        {

        } else {

        }


        //if delivered milestone is checked
        if(jQuery('#delivered').is(':checked')) {

            //show pending billing
            if (jQuery('#billed').is(':checked')) {
                jQuery('#shipment_status').find(`option[value="${pbValue}"]`).remove()
            } else {
                if (jQuery('#shipment_status').find(`option[value="${pbValue}"]`).length==0) {
                    jQuery(`<option value="${pbValue}">${pbValue}</option>`).insertBefore(`option[value="${pbValueBefore}"]`)
                }
            }

        } else {

            //show otherwise
            jQuery('#shipment_status').find(`option[value="${pbValue}"]`).remove()
        }

      }, 300) */
      /*
      setInterval(() => {
        if(jQuery('#freight_released_processed').is(':checked') && jQuery('#customs_processed').is(':checked')) {
          jQuery('#shipment_status').find(`option[value="${transitValue}"]`).remove()
        }else {
          if(jQuery('#shipment_status').find(`option[value="${transitValue}"]`).length==0)
            jQuery(`<option value="${transitValue}">${transitValue}</option>`).insertBefore(`option[value="${transitValueBefore}"]`)
        }
      },300)*/
        //set default selected schedules
       let getSchedules = (this.field.value=='' || this.field.value==null) ? [] : this.field.value
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
                if(status=='ok') {
                    this.default_schedule_etd = moment(result.etd).format(this.format)
                    this.default_schedule_eta = moment(result.eta).format(this.format)
                    if(getSchedules.length==0 && (result.etd!=null || result.eta!=null)) {

                      var setId = new Date().getTime()

                      this.formGroups.push({
                          id: setId,
                          eta: (result.etd!=null) ? this.default_schedule_eta : null,
                          etd: (result.eta!=null) ? this.default_schedule_etd : null,
                          etb: '',
                          cutoff: '',
                          location_from: '',
                          location_to: '',
                          mode: '',
                          etaError: new Error(),
                          etdError: new Error()
                      })
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
        updateSellCostLabel(i, key) {
            this.sell_cost_labels[key] = (typeof _.find(this.sizes, {
            id
            })!=='undefined') ? `Cost per ${_.find(this.sizes, {
                id
            }).name}` : ''
        },
        updateCostLabel(id, key) {
            this.cost_labels[key] = (typeof _.find(this.sizes, {
            id
            })!=='undefined') ? `Cost per ${_.find(this.sizes, {
                id
            }).name}` : ''

        },
        removeLeg(key, keySecond) {
            this.formGroups[key].legs.splice(keySecond, 1)
            // sync location to
            for(let i =0;i < this.formGroups[key].legs.length; i++){
                if(i==0){
                    this.formGroups[key].legs[i].location_from = this.formGroups[key].location_to
                }else{
                    this.formGroups[key].legs[i].location_from = this.formGroups[key].legs[i-1].location_to
                }
            }
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
        removeGroup(item,key) {
            jQuery(`#shipment-schedule-group-dept-${key}`).remove()
            var filterFormGroups = _.filter(this.formGroups,o => (o.id!=item.id))
            this.errors[key] = false
            this.formGroups = filterFormGroups
            this.cost_labels.splice(key,1)
            this.sell_cost_labels.splice(key,1)
            this.value = JSON.stringify(filterFormGroups)
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

        addGroup() {

            let setId = new Date().getTime()
            this.formGroups.push({
                id: setId,
                eta: '',
                etd: '',
                etb: '',
                cutoff: '',
                location_from: '',
                location_to: '',
                vendor_id: 0,
                agent_id: 0,
                mode: '',
                legs: [],
                type: null,
                carrier_name: {
                    id: 0
                },
                size_id: null,
                price: null,
                selling_size_id: null,
                selling_price: null,
                sell_rates: [],
                buy_rates: [],
                is_confirmed: 0,
                etaError: new Error(),
                etdError: new Error()
            })

            //this.current_id = parseInt(new Date().getTime())
            //let setId = (this.formGroups.length>0) ? this.formGroups[this.formGroups.length - 1].id + 1 : 0

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
        handleLocationToChange(key){
            if(this.formGroups[key].legs.length > 0){
                this.formGroups[key].legs[0].location_from = this.formGroups[key].location_to
            }
        },
        handleChangeLocationToLeg(key,keySecond){
            if(this.formGroups[key].legs.length > (keySecond + 1)){
                this.formGroups[key].legs[keySecond + 1].location_from = this.formGroups[key].legs[keySecond].location_to
            }
        }
    },
}
</script>
