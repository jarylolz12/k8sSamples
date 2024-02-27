import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import office from './modules/office'

Nova.booting((Vue, router, store) => {
  store.registerModule('officeEmail', office)
  Vue.component('index-office-email-group', require('./components/IndexField'))
  Vue.component('detail-office-email-group', require('./components/DetailField'))
  Vue.component('form-office-email-group', require('./components/FormField'))
  Vue.component('v-select', vSelect)
})
