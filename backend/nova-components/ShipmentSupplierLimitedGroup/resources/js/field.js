import VueSingleSelect from "vue-single-select";
import vSelect from "vue-select"
Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-supplier-limited-group', require('./components/IndexField'))
  Vue.component('detail-shipment-supplier-limited-group', require('./components/DetailField'))
  Vue.component('form-shipment-supplier-limited-group', require('./components/FormField'))
  Vue.component('vue-single-select', VueSingleSelect)
  Vue.component('v-select', vSelect)
})
