<template>
  <div class="flex border-b border-40" resource-id="resourceId">
    <div class="w-1/5 px-8 py-6">
      <label for="custom_notes" class="inline-block text-80 pt-2 leading-tight">
        Services
        <!---->
      </label>
    </div>
    <div class="py-6 px-8 w-4/5">
      <div class="flex">
        <div class="w-1/3">
          <input @click="tick('isf')" :checked="services_section.isf==1" type="checkbox" class="checkbox mx-1" />
          <label class="inline-block text-80 pt-2 leading-tight">
            ISF
          </label>
        </div>
        <div class="w-1/3">
          <input @click="tick('freight_forwarding')" :checked="services_section.freight_forwarding==1" type="checkbox" class="checkbox mx-1" />
          <label class="inline-block text-80 pt-2 leading-tight">
            Freight Forwarding
          </label>
        </div>
        <div class="w-1/3">
          <input @click="tick('customs')" :checked="services_section.customs==1" type="checkbox" class="checkbox mx-1" />
          <label class="inline-block text-80 pt-2 leading-tight">
            Customs
          </label>
        </div>
      </div>
      <div class="flex">
        <div class="w-1/3">
          <input @click="tick('duty_layout')" :checked="services_section.duty_layout==1" type="checkbox" class="checkbox mx-1" />
          <label class="inline-block text-80 pt-2 leading-tight">
            Duty Layout
          </label>
        </div>
        <div class="w-1/3">
          <input @click="tick('pier_pass')" :checked="services_section.pier_pass==1" type="checkbox" class="checkbox mx-1" />
          <label class="inline-block text-80 pt-2 leading-tight">
            Pier Pass
          </label>
        </div>
        <div class="w-1/3">
          <input @click="tick('trucking')" :checked="services_section.trucking==1" type="checkbox" class="checkbox mx-1" />
          <label class="inline-block text-80 pt-2 leading-tight">
            Trucking
          </label>
        </div>
      </div>
      <input type="hidden" id="services_section" dusk="services_section" :value="JSON.stringify(services_section)" />
    </div>
  </div>
</template>


<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'


/*
ISF
Freight forwarding
Customs 
Duty layout
Pier pass
Trucking
*/

export default {
  mixins: [FormField, HandlesValidationErrors],
  data() {
    return {
      services_section: (this.field.value=='' || this.field.value==null) ? {"isf": 0,"freight_forwarding": 0,"customs": 0,"duty_layout": 0, "pier_pass": 0, "trucking": 0} : JSON.parse(this.field.value)
    }
  },
  props: ['resourceName', 'resourceId', 'field'],
  mounted() {
  },
  methods: {
    tick(getKey) {
      this.services_section[getKey] = (this.services_section[getKey]==1) ? 0 : 1
    },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = (this.field.value=='' || this.field.value==null) ? {"isf": 0,"freight_forwarding": 0,"customs": 0,"duty_layout": 0, "pier_pass": 0, "trucking": 0} : JSON.parse(this.field.value)
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.services_section) || JSON.stringify({"isf": 0,"freight_forwarding": 0,"customs": 0,"duty_layout": 0, "pier_pass": 0, "trucking": 0}))
    },
  },
}
</script>
