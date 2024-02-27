<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div class="flex mb-2 flex-col" v-for="(v,k) in items">
        <div class="flex flex-row mb-4">
          <div class="w-1/4">
            <label class="inline-block text-80 pt-2 leading-tight w-full">Type</label>
          </div>
          <div class="w-1/2 flex flex-row">
            <select placeholder="Type" v-model="v.details.type" class="w-full form-control form-input form-input-bordered acc-type">
                <option v-for="v in types" :key="v" :value="v">{{ v }}</option>
            </select>
          </div>
          <div class="w-1/4">
            <a @click="removeItem(k)" class="vendor-remove-button block">X</a>
          </div>
        </div>
        <div class="flex flex-row mb-4">
          <div class="w-1/4">
            <label class="inline-block text-80 pt-2 leading-tight w-full">Routing Number</label>
          </div>
          <div class="w-1/2">
            <input v-model="v.details.routing_number" class=" w-full form-control form-input form-input-bordered" />
          </div>
        </div>
        <div class="flex flex-row mb-4">
          <div class="w-1/4">
            <label class="inline-block text-80 pt-2 leading-tight w-full">Account Number</label>
          </div>
          <div class="w-1/2">
            <input v-model="v.details.account_number" class=" w-full form-control form-input form-input-bordered" />
          </div>
        </div>
        <div class="flex flex-row mb-4">
          <div class="w-1/4">
            <label class="inline-block text-80 pt-2 leading-tight w-full">Address</label>
          </div>
          <div class="w-1/2 flex flex-col">
            <div class="w-full mb-4">
              <input v-model="v.details.address.line1" placeholder="Line 1" class=" w-full form-control form-input form-input-bordered" />
            </div>
            <div class="w-full mb-4">
              <input v-model="v.details.address.line2" placeholder="Line 2" class=" w-full form-control form-input form-input-bordered" />
            </div>
            <div class="w-full mb-4">
              <select placeholder="City" v-model="v.details.address.city" class="w-full form-control form-input form-input-bordered acc-city">
                <option v-for="v in cities" :key="`city-${v}`" :value="v">{{ v }}</option>
              </select>
            </div>
            <div class="w-full mb-4">
              <select placeholder="State" v-model="v.details.address.state" class="w-full form-control form-input form-input-bordered acc-state">
                <option v-for="v in states" :key="`state-${v}`" :value="v">{{ v }}</option>
              </select>
            </div>
            <div class="w-full mb-4">
              <select placeholder="Country" v-model="v.details.address.country" class="w-full form-control form-input form-input-bordered acc-country">
                <option v-for="v in countries" :key="`country-${v}`" :value="v">{{ v }}</option>
              </select>
            </div>
            <div class="w-full mb-4">
              <input v-model="v.details.address.postal_code" placeholder="Postal Code" class="w-full form-control form-input form-input-bordered" />
            </div>
            <div class="w-full mb-4">
              <input v-model="v.details.address.number" placeholder="Number" class="w-full form-control form-input form-input-bordered" />
            </div>
          </div>
        </div>
      </div>
      <div class="flex">
        <div :class="`${(items.length==0) ? 'w-1/2' : 'w-1/4'}`">
          <button v-if="items.length==0" v-on:click.stop.prevent="addItem" class="btn bg-success btn-default inline-flex items-center relative">
            <span class="text-white">
              Add Payment Account
            </span>
          </button>
        </div>
        <div :class="`${(items.length==0) ? 'w-1/2' : 'w-3/4'}`">
            <button v-if="items.length>0" v-on:click.stop.prevent="addItem" class="btn bg-success btn-default inline-flex items-center relative">
              <span class="text-white">
                Add Payment Account
              </span>
            </button>
        </div>
      </div>
    </template>
  </default-field>
</template>
<style type="text/css">
  .vendor-remove-button {
    vertical-align: 0px;
    padding: 4px 8px 3px 8px;
    left: 6px;
    cursor: pointer;
    text-align: center;
    background: #fa5661;
    font-size: 11px;
    font-family: monospace;
    color: #fff;
    text-shadow: 1px 1px #bf3039;
    border-radius: 14px;
    top: 4px;
    position: relative;
    height: 24px;
    width: 27px;
  }
</style>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import stateCitiesJson from '../state_cities.json'
import countriesJson from '../countries.json'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],
  data() {
    return {
      items: [],
      cities: [],
      states: [],
      countries: [],
      types: ['ACH','DOMESTIC_WIRE','CHEQUE']
    }
  },
  created() {

    this.items = JSON.parse(this.field.value) || []
    let stateCitiesData = stateCitiesJson
    let getKeys = Object.keys(stateCitiesData)
    
    getKeys.map(gk => {
      this.states.push(gk)
      stateCitiesData[gk].map(gd => {
        if (this.cities.indexOf(gd)==-1)
          this.cities.push(gd)
      })

    })

    let countriesData = countriesJson

    
    countriesData.map(c => {
      if (this.countries.indexOf(c.name)==-1)
        this.countries.push(c.name)
    })

  },
  methods: {
    removeItem(index) {
      this.items.splice(index, 1)
    },
    addItem() {
      this.items.push({
        details: {
          type: '',
          routing_number: '',
          account_number: '',
          address: {
            line1: '',
            line2: '',
            city: '',
            state: '',
            country: '',
            postal_code: '',
            number: ''
          }  
        }
      })
    },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = JSON.parse(this.field.value) || []
    },
    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.items) || JSON.stringify([]))
    },
  },
}
</script>
