<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <textarea
        :id="field.name" 
        class=" w-full form-control form-input form-input-bordered py-3 h-auto" 
        :class="errorClasses" 
        :placeholder="field.name" 
        rows="10" 
        :name="field.name.toLowerCase()" 
        v-model="value" 
        v-if="field.field_type=='textarea'" 
      ></textarea>
      <input
        :id="field.name" 
        type="text" 
        :name="field.name.toLowerCase()" 
        class="w-full form-control form-input form-input-bordered" 
        :class="errorClasses" 
        :placeholder="field.name" 
        v-model="value" 
        v-if="field.field_type=='text'" 
      />
    </template>
  </default-field>

</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

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
