<template>
  <div class="flex border-b border-40">
    <div class="w-1/5 px-8 py-6">
      <label for="freight_released_processed" class="inline-block text-80 pt-2 leading-tight">
        MBL Copy Surrendered/Released
        <!---->
      </label>
    </div>
    <div class="py-6 px-8 w-4/5">
      <div class="flex flex-row">
        <div class="mr-2">
          <a class="cursor-pointer block mt-2 text-primary">
          {{
            (field.value!==null && field.value!=='') ? field.value : 'No MBL Copy Surrendered Document yet.'
          }}
          </a>
        </div>
        <input type="hidden" id="mbl_copy_surrendered_name_bl" name="mbl_copy_surrendered_name_bl" value="" />
        <input type="hidden" id="mbl_copy_surrendered_path_bl" name="mbl_copy_surrendered_path_bl" value="" />
        <input type="hidden" id="remove_mbl_copy_surrendered_bl" value="" />
        <input @change="handleFileChange" type="file" class="hidden" name="mbl_copy_surrendered_bl" id="mbl_copy_surrendered_bl" />
        <button v-if="field.value!==null && field.value!==''" @click="remove" class="btn btn-default btn-danger flex flex-row">
          <div>Remove File</div>
        </button>
        <div v-if="field.value!==null && field.value!==''" class="p-2 text-80 font-bold">OR</div>
        <button @click="upload" :class="`btn btn-default btn-primary flex flex-row`">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
          </svg>
          <div>Upload Document</div>
        </button>
      </div>
    </div>
  </div>
</template>
<style type="text/css">
  #mbl_copy_surrendered {
    display:  none;
  }
</style>
<script>

import jQuery from 'jquery'
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ['resourceName', 'resourceId', 'field'],
  computed: {
    token() {
        return jQuery('meta[name="csrf-token"]').attr("content")
    }
  },
  methods: {
    remove() {
      let getField = this.field
      getField.value = ''
      getField.file = ''
      this.$emit('update:field', getField)
      jQuery('#mbl_copy_surrendered_name_bl').val('')
      jQuery('#remove_mbl_copy_surrendered_bl').val('1')
    },
    handleFileChange(event) {

      event.preventDefault()
      let {
        target
      } = event

      let getField = this.field
      getField.value = target.files[0].name
      getField.file = target.files[0]
      this.$emit('update:field', getField)
      jQuery('#mbl_copy_surrendered_name_bl').val(getField.value)
      jQuery('#remove_mbl_copy_surrendered_bl').val('0')
    },
    upload(e) {
      e.preventDefault()
      jQuery('#mbl_copy_surrendered_bl').click()
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
      if (this.field.file!=='') {
        formData.append('mbl_copy_surrendered_file_bl', this.field.file)
        formData.append('mbl_copy_surrendered_name_bl', this.field.value)
      }

      if (jQuery('#remove_mbl_copy_surrendered_bl').val()==1) {
        formData.append('mbl_copy_surrendered_remove_bl', 1)
      }
      //formData.append(this.field.attribute, this.value || '')
    },
  },
}
</script>
