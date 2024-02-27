import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

Nova.booting((Vue, router, store) => {
  Vue.component('index-office-managers-field', require('./components/IndexField'))
  Vue.component('detail-office-managers-field', require('./components/DetailField'))
  Vue.component('form-office-managers-field', require('./components/FormField'))
  Vue.component('v-select', vSelect)
})
