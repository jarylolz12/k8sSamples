Nova.booting((Vue, router, store) => {
  Vue.component('index-customer-default-requirements-field', require('./components/IndexField'))
  Vue.component('detail-customer-default-requirements-field', require('./components/DetailField'))
  Vue.component('form-customer-default-requirements-field', require('./components/FormField'))
})
