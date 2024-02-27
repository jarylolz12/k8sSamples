import Select2 from 'v-select2-component'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import customer from './modules/customer'
import office from './modules/office'

Nova.booting((Vue, router, store) => {
    store.registerModule('customer', customer)
    store.registerModule('office', office)
    Vue.component('index-multiple-select-field', require('./components/IndexField'))
    Vue.component('detail-multiple-select-field', require('./components/DetailField'))
    Vue.component('form-multiple-select-field', require('./components/FormField'))
    Vue.component('Select2', Select2)
    Vue.component('v-select', vSelect)
})
