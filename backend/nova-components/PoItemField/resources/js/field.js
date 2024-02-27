import vSelect from "vue-select"
import Multiselect from 'vue-multiselect'
Nova.booting((Vue, router, store) => {
  Vue.component('index-po-item-field', require('./components/IndexField'))
  Vue.component('detail-po-item-field', require('./components/DetailField'))
  Vue.component('form-po-item-field', require('./components/FormField'))
  Vue.component('v-select', vSelect)
  Vue.component('multiselect', Multiselect)
})
