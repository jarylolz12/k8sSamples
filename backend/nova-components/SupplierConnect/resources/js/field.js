Nova.booting((Vue, router, store) => {
  Vue.component('index-supplier-connect', require('./components/IndexField'))
  Vue.component('detail-supplier-connect', require('./components/DetailField'))
  Vue.component('form-supplier-connect', require('./components/FormField'))
})
