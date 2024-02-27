Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-departure-notice', require('./components/IndexField'))
  Vue.component('detail-shipment-departure-notice', require('./components/DetailField'))
  Vue.component('form-shipment-departure-notice', require('./components/FormField'))
})
