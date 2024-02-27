Nova.booting((Vue, router, store) => {
  Vue.component('index-customer-telex', require('./components/IndexField'))
  Vue.component('detail-customer-telex', require('./components/DetailField'))
  Vue.component('form-customer-telex', require('./components/FormField'))
})
