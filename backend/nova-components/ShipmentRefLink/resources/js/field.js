Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-ref-link', require('./components/IndexField'))
  Vue.component('detail-shipment-ref-link', require('./components/DetailField'))
  Vue.component('form-shipment-ref-link', require('./components/FormField'))
})
