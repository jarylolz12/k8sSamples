<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <select :placeholder="placeholder" v-model="value" :class="`w-full form-control form-input form-input-bordered ${field.data}`">
        <option :key="v" v-for="v in optionsFiltered" :value="v">{{ v }}</option>
      </select>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import stateCitiesJson from '../state_cities.json'
import countriesJson from '../countries.json'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],
  computed: {
    optionsFiltered() {
      let {
        options
      } = this
      let newOptions = []
      
      options.map(option => {
        if ( newOptions.indexOf(option)==-1 ) {
          newOptions.push(option)
        }
      })

      return newOptions
    }
  },
  created() {
      let stateCitiesData = stateCitiesJson
      let getKeys = Object.keys(stateCitiesData)
      
      getKeys.map(gk => {
        this.states.push(gk)
        stateCitiesData[gk].map(gd => {
          this.cities.push(gd)
        })
      });

      let countriesData = countriesJson

      countriesData.map(c => {
        this.countries.push(c.name)
      });

      switch(this.field.data) {
        case 'country':
          this.options = this.countries
          this.placeholder = 'Select Country'
          break;
        case 'city':
          this.options = this.cities
          this.placeholder = 'Select City'
          break;
        case 'state':
          this.options = this.states
          this.placeholder = 'Select State'
          break;
        default:
      
      }




  },
  data() {
      return {
        cities: [],
        states: [],
        countries: [],
        options: [],
        placeholder: ''
      }
  },
  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || ''
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || '')
    },
  },
}
</script>
