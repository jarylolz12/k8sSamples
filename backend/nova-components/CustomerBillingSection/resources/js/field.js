import customer from './modules/customer'

Nova.booting((Vue, router, store) => {
  store.registerModule('customer', customer)
  Vue.component('index-customer-billing-section', require('./components/IndexField'))
  Vue.component('detail-customer-billing-section', require('./components/DetailField'))
  Vue.component('form-customer-billing-section', require('./components/FormField'))
})
