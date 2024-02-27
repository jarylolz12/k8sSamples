import Vue from 'vue'
import vSelect from 'vue-select'

Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-arrival-notice-save', require('./components/IndexField'))
  Vue.component('detail-shipment-arrival-notice-save', require('./components/DetailField'))
  Vue.component('form-shipment-arrival-notice-save', require('./components/FormField'))
  Vue.component('v-select', vSelect)
})
