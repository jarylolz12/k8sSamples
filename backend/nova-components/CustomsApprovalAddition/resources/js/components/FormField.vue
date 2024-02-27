<template>
  <div class="flex border-b border-40">
    <div class="px-8 py-6 w-1/5">
      <label :for="field.attribute" class="inline-block text-80 pt-2 leading-tight">
          {{
            field.name
          }}
      </label>
    </div>
    <div class="py-6 px-8 w-4/5 flex flex-col">
      <div class="w-full flex flex-row py-2">
        <div class="w-1/4 flex content-center flex-wrap">
            Get Documents From
        </div>
        <div class="w-1/2">
            <select v-model="documents_from" class="w-full form-control form-input form-input-bordered">
              <option value=0 v-selected="documents_from == 0">Select Option</option>
              <option v-for="o in documents_from_options" :value="o.id">
                {{
                  o.label
                }}
              </option>
            </select>
        </div>
      </div>
      <div class="w-full flex flex-row py-2">
        <div class="w-1/4 flex content-center flex-wrap">
            Approval Requirements
        </div>
        <div class="w-1/2">
            <select v-model="approval_requirements" class="w-full form-control form-input form-input-bordered">
              <option value=0 v-selected="approval_requirements == 0">Select Option</option>
              <option v-for="o in approval_requirements_options" :value="o.id">
                {{
                  o.label
                }}
              </option>
            </select>
        </div>
      </div>
      <div class="w-full flex flex-row py-2">
        <div class="w-1/4 flex content-center flex-wrap">
            Approved HS Codes
        </div>
        <div class="w-1/2">
            <textarea rows="5" class="w-full form-control form-input form-input-bordered py-3 h-auto" v-model="approved_hs_codes">
            </textarea>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],
  data:() => ({
      documents_from_options: [],
      approval_requirements_options: [],
      documents_from: null,
      approval_requirements: null,
      approved_hs_codes: null
  }),
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
      if ( this.approval_requirements !=null )
        formData.append('approval_requirements', this.approval_requirements)

      if ( this.documents_from !=null )
        formData.append('documents_from', this.documents_from)

      if ( this.approved_hs_codes !=null )
        formData.append('approved_hs_codes', this.approved_hs_codes)

    }
  },
  mounted() {
    this.approval_requirements_options = this.field.approval_requirements_options
    this.documents_from_options = this.field.documents_from_options
    this.documents_from = this.field.data.documents_from
    this.approval_requirements = this.field.data.approval_requirements
    this.approved_hs_codes = this.field.data.approved_hs_codes
  }
}
</script>
