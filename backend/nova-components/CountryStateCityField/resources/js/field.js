import vSelect from "vue-select";

Nova.booting((Vue, router, store) => {
  Vue.component('index-country-state-city-field', require('./components/IndexField'))
  Vue.component('detail-country-state-city-field', require('./components/DetailField'))
  Vue.component('form-country-state-city-field', require('./components/FormField'))
  Vue.component("v-select", vSelect)
})
