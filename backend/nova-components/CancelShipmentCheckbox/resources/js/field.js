Nova.booting((Vue, router, store) => {
  Vue.component('index-cancel-shipment-checkbox', require('./components/IndexField'))
  Vue.component('detail-cancel-shipment-checkbox', require('./components/DetailField'))
  Vue.component('form-cancel-shipment-checkbox', require('./components/FormField'))
})
