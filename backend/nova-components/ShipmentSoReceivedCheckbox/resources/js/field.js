Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-so-received-checkbox', require('./components/IndexField'))
  Vue.component('detail-shipment-so-received-checkbox', require('./components/DetailField'))
  Vue.component('form-shipment-so-received-checkbox', require('./components/FormField'))
})
