import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css'


Nova.booting((Vue, router, store) => {
  Vue.component('v-select', vSelect)
  Vue.component('index-vendor-payment-accounts', require('./components/IndexField'))
  Vue.component('detail-vendor-payment-accounts', require('./components/DetailField'))
  Vue.component('form-vendor-payment-accounts', require('./components/FormField'))
})
