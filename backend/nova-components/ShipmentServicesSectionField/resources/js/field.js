Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-services-section-field', require('./components/IndexField'))
  Vue.component('detail-shipment-services-section-field', require('./components/DetailField'))
  Vue.component('form-shipment-services-section-field', require('./components/FormField'))
})
