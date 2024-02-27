import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'


Nova.booting((Vue, router, store) => {
  Vue.component('index-account-manager-office-field', require('./components/IndexField'))
  Vue.component('detail-account-manager-office-field', require('./components/DetailField'))
  Vue.component('form-account-manager-office-field', require('./components/FormField'))
  Vue.component('v-select', vSelect)
})
