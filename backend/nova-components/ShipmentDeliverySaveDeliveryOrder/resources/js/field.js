import Vue from 'vue'
import vSelect from 'vue-select'
import FieldWrapper from './components/BooleanField/FieldWrapper'
Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-delivery-save-delivery-order', require('./components/IndexField'))
  Vue.component('detail-shipment-delivery-save-delivery-order', require('./components/DetailField'))
  Vue.component('form-shipment-delivery-save-delivery-order', require('./components/FormField'))
  Vue.component('v-select', vSelect)
})