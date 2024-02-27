Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-notes', require('./components/IndexField'))
  Vue.component('detail-shipment-notes', require('./components/DetailField'))
  Vue.component('form-shipment-notes', require('./components/FormField'))
})
