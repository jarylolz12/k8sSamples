Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-container-limited-group', require('./components/IndexField'))
  Vue.component('detail-shipment-container-limited-group', require('./components/DetailField'))
  Vue.component('form-shipment-container-limited-group', require('./components/FormField'))
})
