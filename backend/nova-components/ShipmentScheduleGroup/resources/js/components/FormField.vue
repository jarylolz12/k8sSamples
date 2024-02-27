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
            <portal
                to="modals"
                transition="fade-transition"
                v-if="scheduleModal"
              >
            <modal @modal-close="closeModal">
              <div class="p-8 bg-white rounded-lg shadow-lg overflow-hidden">
                <heading class="mb-6">Schedule Limited</heading>
                <p class="text-80 leading-normal">
                  You can only have two schedules.
                </p>
              </div>
                <div class="bg-30 px-6 py-3 flex" style="margin-top: -15px;border-bottom-right-radius: .5rem;border-bottom-left-radius: .5rem;">
                  <div class="ml-auto">
                    <button
                      type="button"
                      dusk="close-modal-button"
                      @click.prevent="closeModal"
                      class="btn btn-primary font-normal h-9 px-3 mr-3 btn-link"
                    >
                     Close
                    </button>
                  </div>
                </div>
              </modal>
            </portal>
            <div :id="`shipment-schedule-group-${item.id}`" v-for="(item,key) in formGroups" class="shipment-schedule-group">

                <div v-show="key==0">
                    <label>Location From</label>
                    <div>
                        <select :id="`shipment-schedule-terminal-region-from-select-${key}`" v-model="item.location_from" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Select Location</option>
                            <option v-show="terminal_regions_options.length>0" v-for="option in terminal_regions_options" :value="option.id">{{option.name}}</option>
                        </select>
                    </div>
                </div>
                <div v-show="key==0">
                    <label>Estimated Time of Departure</label>
                    <div>
                        <date-time-picker
                            class="w-full form-control form-input form-input-bordered mb-1"
                            :value="item.etd"
                            @change="(value) => {handleEtdChange(value, item);}"
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
                <!--<div style="display:none;" v-show="key==0 && typeof resourceId!='undefined'">
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
                            <option v-show="terminal_regions_options.length>0" v-for="option in terminal_regions_options" :value="option.id">{{option.name}}</option>
                        </select>
                    </div>
                </div>

                <!-- <div style="display:none;" v-show="key==0 && typeof resourceId!='undefined'">
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
                            @change="(value) => {handleEtaChange(value, item);}"
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
                <div>
                    <label>Mode</label>
                    <div>
                        <select v-model="item.mode" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Select Mode</option>
                            <option v-for="option in mode_values" :value="option">{{option}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="shipment-schedule-group-submit-group">
                <button v-show="formGroups.length==2" disabled="disabled" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addGroup()" >Add Leg</button>
                <button v-show="formGroups.length<=1 && formGroups.length>=0" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addGroup()" >Add Leg</button>
            </div>
            <div class="customErrorMessages">
                <div v-show="errorMessages['etd']">{{errorMessages['etd']}}</div>
                <div v-show="errorMessages['eta']">{{errorMessages['eta']}}</div>
            </div>

        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors, InteractsWithDates, Errors } from 'laravel-nova'
import jQuery from 'jquery';

export default {
    mixins: [FormField, HandlesValidationErrors, InteractsWithDates],
    props: ['resourceName', 'resourceId', 'field'],
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
            formGroups: (this.field.value=='' || this.field.value==null) ? [] : JSON.parse(this.field.value)
        }
    },
    mounted() {
      var token = jQuery('meta[name="csrf-token"]').attr('content')
      var transitValue = "In Transit - Pending AN/Customs/Freight Payment"
      var transitValueBefore = "Pending DO"

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

      setInterval(() => {

        /*
        if (jQuery('div[label="Departure Notice"]').length >0 && jQuery('div[label="Departure Notice"]').is(':visible')) 
        {   
           
               
            console.log('displayed')
        } else {
            console.log('awts')
        }*/


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

      // }, 300)
      
      setInterval(() => {
        if(jQuery('#freight_released_processed').is(':checked') && jQuery('#customs_processed').is(':checked')) {
          jQuery('#shipment_status').find(`option[value="${transitValue}"]`).remove();
        }else {
          if(jQuery('#shipment_status').find(`option[value="${transitValue}"]`).length==0)
            jQuery(`<option value="${transitValue}">${transitValue}</option>`).insertBefore(`option[value="${transitValueBefore}"]`);
        }
      },300);
      
        //set default selected schedules
       let getSchedules = (this.field.value=='' || this.field.value==null) ? [] : JSON.parse(this.field.value);
       this.is_create = (this.field.value=='' || this.field.value==null) ? true : false;

        if ( typeof this.resourceId!='undefined' ) {
            jQuery.ajax({
            method: 'GET',
            url: '/custom/shipment',
            data: {
                _token: token,
                id: this.resourceId
            }
            }).done( response => {
                let { status, result } = response;
                if(status=='ok') {
                    this.default_schedule_etd = moment(result.etd).format(this.format);
                    this.default_schedule_eta = moment(result.eta).format(this.format);

                    if(getSchedules.length==0 && (result.etd!=null || result.eta!=null)) {

                      var setId = new Date().getTime();

                      this.formGroups.push({
                          id: setId,
                          eta: (result.etd!=null) ? this.default_schedule_eta : null,
                          etd: (result.eta!=null) ? this.default_schedule_etd : null,
                          location_from: '',
                          location_to: '',
                          mode: '',
                          etaError: new Error(),
                          etdError: new Error()
                      });
                    }
                }
            });
        }

        jQuery.ajax({
            method: 'GET',
            url: '/custom/terminal-regions',
            data: {
                _token: token
            }

        }).done( response => {
            let { results } = response;
            if( results.length>0)  {
                this.terminal_regions_options = results;
            }
        });

       /*
       if ( typeof jquery=='undefined' ) {
        var jqueryLibraryUrl = 'https://code.jquery.com/jquery-3.4.1.min.js';
        s.setAttribute('src',jqueryLibraryUrl);
       }

       h.appendChild(s);
       s.addEventListener('load',() => {



        /*
        $('body').on('click','button[dusk="create-button"]',(e) => {

            /*
            if(!this.has_submitted) {
                e.preventDefault();
                alert('piskot');
                this.has_submitted = true;
            }
            else
            {

            }
            /*
            if(!this.has_submitted) {
                //e.preventDefault();
                this.value = JSON.stringify(this.formGroups);
                this.has_submitted = true;
                $('button[dusk="create-button"]').click();
            }
            else
            {
                $('button[dusk="create-button"]').click();
            }*/

       // });
        /*
        $('body').on('click','button[dusk="update-and-continue-editing-button"]',(e) => {
            if(!this.has_submitted) {
               // e.preventDefault();
                this.value = JSON.stringify(this.formGroups);
                this.has_submitted = true;
                $('button[dusk="update-and-continue-editing-button"]').click();
            }
            else
            {
                $('button[dusk="update-and-continue-editing-button"]').click();
            }
        });

        $('body').on('click','button[dusk="update-button"]',(e) => {
            if(!this.has_submitted) {
                //e.preventDefault();
                this.value = JSON.stringify(this.formGroups);
                this.has_submitted = true;
                $('button[dusk="update-button"]').click();
            }
            else
            {
                $('button[dusk="update-button"]').click();
            }
        });*/

     //  });




    },
    computed: {
        errorMessages() {

            return (typeof this.firstError!='undefined') ? JSON.parse(this.firstError) : [];
        },
        firstDayOfWeek() {
            return this.field.firstDayOfWeek || 0
        },
        schedule_count() {
            return (this.field.value!='' && this.field.value!=null) ? (JSON.parse(this.field.value)).length : (this.formGroups.length>0) ? this.formGroups.length : 0;
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
        removeGroup(item,key) {
            jQuery(`#shipment-schedule-group-${key}`).remove();
            var filterFormGroups = _.filter(this.formGroups,o => (o.id!=item.id));
            this.errors[key] = false;
            this.formGroups = filterFormGroups;
            this.value = JSON.stringify(filterFormGroups);
        },
        closeModal() {
          this.scheduleModal = false;
        },
        openModal() {
            this.scheduleModal = true;
        },
        handleEtaChange(value, item) {
           // this.field.value = value;
            item.eta = value;
        },
        handleEtdChange(value, item) {
            item.etd = value;
        },
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        addGroup() {
            //this.current_id = parseInt(new Date().getTime());
            //let setId = (this.formGroups.length>0) ? this.formGroups[this.formGroups.length - 1].id + 1 : 0;

            if(this.schedule_count<2) {

                let setId = new Date().getTime();

                this.formGroups.push({
                    id: setId,
                    eta: '',
                    etd: '',
                    location_from: '',
                    location_to: '',
                    mode: '',
                    etaError: new Error(),
                    etdError: new Error()
                });


                this.value = JSON.stringify(this.formGroups);

            } else {
              this.scheduleModal = true;
              //  alert('You are not allowed to add anymore schedule. Current limit is to 2.');
            }

        },
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
          //  formData.append(this.field.attribute, this.value || '')
            if(this.formGroups.length>0) {
                formData.append(this.field.attribute, JSON.stringify(this.formGroups));
            }else {
                formData.append(this.field.attribute, '[]');
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
