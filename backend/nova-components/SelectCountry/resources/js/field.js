Nova.booting((Vue, router, store) => {
  Vue.component('index-select-country', require('./components/IndexField'))
  Vue.component('detail-select-country', require('./components/DetailField'))
  Vue.component('form-select-country', require('./components/FormField'))
})
