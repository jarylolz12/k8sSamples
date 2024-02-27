<template>
    <default-field :field="field" :errors="errors" id="hide-account-manager-select-id" >
        <template slot="field">
            <div class="parent-selection">
                <v-select v-model="managers_lists" multiple @input="setSelected" :reduce="text => text.id" label="text" :options="managers"></v-select>
            </div>
            <div>
              {{
                (typeof errors!=='undefined' && typeof errors.managers_lists!=='undefined') ? errors.managers_lists : ''
              }}
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],
  data() {
    return {
      managers: [],
      base_url: this.field.baseUrl || '',
      managers_lists: JSON.parse(this.field.managers_lists_custom) || [],
    }
  },
  mounted() {
    this.getAllManagers()
  },
  methods: {
    setSelected(value) {
      this.value = value
      console.log(value)
    },
    getAllManagers() {
      fetch(`${this.base_url}/custom/account-managers`)
      .then(this.handleResponse)
      .then( response => {
          let { results } = response
          this.managers = results
         
      }).catch(e => {
          console.log(e)
      })
    },
    handleResponse(response) {
        return response.text().then(text => {
            const data = text && JSON.parse(text)
            return data
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
      formData.append(this.field.attribute, JSON.stringify(this.value) || JSON.stringify([]))
    },
  },
}
</script>
