import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

Nova.booting((Vue, router, store) => {
  Vue.component('index-account-manager-special-select', require('./components/IndexField'))
  Vue.component('detail-account-manager-special-select', require('./components/DetailField'))
  Vue.component('form-account-manager-special-select', require('./components/FormField'))
  Vue.component('v-select', vSelect)
});