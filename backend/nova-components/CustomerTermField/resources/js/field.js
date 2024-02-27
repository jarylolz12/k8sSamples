Nova.booting((Vue, router, store) => {
  Vue.component('index-customer-term-field', require('./components/IndexField'))
  Vue.component('detail-customer-term-field', require('./components/DetailField'))
  Vue.component('form-customer-term-field', require('./components/FormField'))
})
