Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-containers-loaded-checkbox', require('./components/IndexField'))
  Vue.component('detail-shipment-containers-loaded-checkbox', require('./components/DetailField'))
  Vue.component('form-shipment-containers-loaded-checkbox', require('./components/FormField'))
})
