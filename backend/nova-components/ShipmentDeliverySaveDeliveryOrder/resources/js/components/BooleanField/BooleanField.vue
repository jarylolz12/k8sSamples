<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">
      <div>wew</div>
    </template>
  </default-field>
</template>
<script>
import { Errors, FormField, HandlesValidationErrors } from 'laravel-nova'
export default {
  mixins: [HandlesValidationErrors, FormField],
  props: ['resourceName', 'resourceId', 'field','updateCopyCustomer'],
  data: () => ({
    value: false,
  }),

  updated() {
    this.updateCopyCustomer(this.value)
  },
  mounted() {
    this.value = this.field.value || false

    this.field.fill = formData => {
      formData.append(this.field.attribute, this.trueValue)
    }
  },

  methods: {
    toggle() {
      this.value = !this.value
    },
  },


  computed: {
    checked() {
      return Boolean(this.value)
    },

    trueValue() {
      return +this.checked
    },
  },
}
</script>
