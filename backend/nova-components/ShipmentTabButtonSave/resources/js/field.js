
import Vue from 'vue'
import vSelect from 'vue-select'


Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-tab-button-save', require('./components/IndexField'))
  Vue.component('detail-shipment-tab-button-save', require('./components/DetailField'))
  Vue.component('form-shipment-tab-button-save', require('./components/FormField'))
  Vue.component('v-select', vSelect)
  Vue.component('dummy-field-save', require('./components/DummyField'))
})
