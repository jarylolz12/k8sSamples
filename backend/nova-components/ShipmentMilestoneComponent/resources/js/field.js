Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-milestone-component', require('./components/IndexField'))
  Vue.component('detail-shipment-milestone-component', require('./components/DetailField'))
  Vue.component('form-shipment-milestone-component', require('./components/FormField'))
})
