Nova.booting((Vue, router, store) => {
  Vue.component('index-customer-quickbooks-field', require('./components/IndexField'))
  Vue.component('detail-customer-quickbooks-field', require('./components/DetailField'))
  Vue.component('form-customer-quickbooks-field', require('./components/FormField'))
})
