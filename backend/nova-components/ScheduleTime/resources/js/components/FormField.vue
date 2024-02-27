<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <!-- <input
        :id="field.name"
        type="text"
        class="w-full form-control form-input form-input-bordered"
        :class="errorClasses"
        :placeholder="field.name"
        v-model="value"
      /> -->

      <vue-timepicker 
        class="schedule-time"
        format="h:mm a" 
        v-model="shipmentTime"
        close-on-complete
        @change="changeTime">
      </vue-timepicker>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import moment from 'moment'
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  components: {
        'vue-timepicker': VueTimepicker,
    },

    data(){
        return {
            shipmentTime: '',
            convertTimeToUtc: '',
            rules: [
                (v) => !!v || "Input is required."
            ],
            
        }
    },  
    mounted() {
       this.setTime(this.field.schedule.time)
    },  
 
  watch: {
    getTime: function(newValue) {
			this.shipmentTime = newValue
		},
  },
  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      // this.value = this.field.value || ''
      this.value = this.getTime() || ''
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append('time', this.convertTimeToUtc);
    },

    getTime: function(time){
            if(time !== ''){
                let getTime = moment.utc(time, 'HH:mm:ss')
                let finalTimeConverted = getTime.local().format('HH:mm:ss')
                time = finalTimeConverted;
            }
            return time
        },
    setTime: function(time){
      if(time !== ''){
          let getTime = moment.utc(time, 'hh:mm a')
          let finalTimeConverted = getTime.local().format('hh:mm a')
          this.shipmentTime = finalTimeConverted;
      }
    },

    changeTime: function(data){
      let selectedTime = moment(this.shipmentTime, ['hh:mm A']).format('HH:mm:ss')
       selectedTime = moment(selectedTime, 'HH:mm:ss')
			this.convertTimeToUtc = moment(selectedTime).utc().format('HH:mm:ss')
    }   
  },
}
</script>
<style>
  .vue__time-picker-dropdown ul li:not([disabled]).active,
  .vue__time-picker-dropdown ul li:not([disabled]).active:focus,
  .vue__time-picker-dropdown ul li:not([disabled]).active:hover,
  .vue__time-picker .dropdown ul li:not([disabled]).active,
  .vue__time-picker .dropdown ul li:not([disabled]).active:focus,
  .vue__time-picker .dropdown ul li:not([disabled]).active:hover {
    background-color: #4099DE !important;
      color: #fff;
  }
</style>
<style scoped>
  .select-list li.active {
    
    background: #4099DE !important;
  }

  </style>