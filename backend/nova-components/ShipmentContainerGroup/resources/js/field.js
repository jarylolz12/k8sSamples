Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-container-group', require('./components/IndexField'))
  Vue.component('detail-shipment-container-group', require('./components/DetailField'))
  Vue.component('form-shipment-container-group', require('./components/FormField'))
})
