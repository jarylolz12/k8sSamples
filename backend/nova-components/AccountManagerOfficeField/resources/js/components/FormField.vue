<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div class="help-text help-text mt-2">
        <v-select v-model="office.id" :reduce="label => label.value" label="label" @search="search" :options="options" @input="setSelected"></v-select>
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ['resourceName', 'resourceId', 'field'],
  mounted() {
    this.loadSelectedOffice()
  },
  data() {
    return {
      base_url: this.field.baseUrl || '',
      office: this.field.office || '',
      options: []
    }
  },
  methods: {
    setSelected(value) {
      this.value = value
    },
    handleResponse(response) {
        return response.text().then(text => {
            const data = text && JSON.parse(text)
            return data
        })
    },
    loadSelectedOffice() {
      fetch(`${this.base_url}/custom/shifl-office/find-by-id?id=${this.office.id}`).
      then(this.handleResponse)
      .then( response => {
        let { result, status } = response
        if (status=='ok') {
          this.options = result
        }
      })
    },
    search(search, loading) {
      loading(true)
      fetch(`${this.base_url}/custom/shifl-office/search?query=${search}`).
      then(this.handleResponse)
      .then( response => {
        loading(false)
        let { results, status } = response
        if (status=='ok') {
          this.options = results
        }
      }).catch(e => {
        loading(false)
        console.log(e)
      })


    },
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
      //formData.append(this.field.attribute, this.value || '')
      //formData.append('shifl_office_id', this.value || '')
    },
  },
}
</script>
