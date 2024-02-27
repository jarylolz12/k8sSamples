<template>
  <div class="flex border-b border-40">
    <div class="px-8 py-6 w-1/5">
      <label for="confirmed_default_requirements" class="inline-block text-80 pt-2 leading-tight">
        {{
          field.name
        }}
      </label>
    </div>
    <div class="py-6 px-8 w-4/5 flex flex-col">
      <div class="flex flex-row py-4">
        <label class="w-1/4 content-center flex flex-wrap">
          Billing Notes
        </label>
        <textarea rows="5" class="w-1/2 form-control form-input form-input-bordered py-3 h-auto" v-model="billing_notes" />
      </div>
    </div>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import {
  mapActions,
  mapGetters
} from 'vuex'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],
  data:() => ({
    billing_requirements: '',
    billing_notes: ''
  }),
  watch: {
    billing_notes: function(v,pv) {
      if (v.length > 4000 )
        this.billing_notes = v.substr(0,4000)
    }
  },
  computed: {
    ...mapGetters({
      getItem: 'customer/getItem'
    })
  },
  mounted() {
    try {
      if ( this.resourceId!=null ) {
        /*
        await this.fetchCustomerItem({
          id: this.resourceId
        })*/
        //this.billing_requirements = (this.getItem!=null) ? this.getItem.billing_requirements : ''
        //this.billing_notes = (this.getItem!=null) ? this.getItem.billing_notes : ''  
      }
      
    } catch(e) {
      console.log(e)
    }
  },
  methods: {
    ...mapActions({
      fetchCustomerItem: 'customer/fetchItem'
    }),
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      //this.billing_requirements = this.field.billing_requirements
      this.billing_notes = this.field.billing_notes
      //this.billing_requirements = (this.getItem!=null) ? this.getItem.billing_requirements : ''
      //this.billing_notes = (this.getItem!=null) ? this.getItem.billing_notes : ''
      this.value = this.field.value || ''
    },
    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      //formData.append('billing_requirements', this.billing_requirements)
      formData.append('billing_notes', this.billing_notes)
      formData.append(this.field.attribute, this.value || '')
    },
  },
}
</script>
