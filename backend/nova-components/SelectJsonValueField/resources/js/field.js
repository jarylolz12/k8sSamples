import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css'


Nova.booting((Vue, router, store) => {
  Vue.component('v-select', vSelect)
  Vue.component('index-select-json-value-field', require('./components/IndexField'))
  Vue.component('detail-select-json-value-field', require('./components/DetailField'))
  Vue.component('form-select-json-value-field', require('./components/FormField'))
})
