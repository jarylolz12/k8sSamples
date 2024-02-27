Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-schedule-group', require('./components/IndexField'))
  Vue.component('detail-shipment-schedule-group', require('./components/DetailField'))
  Vue.component('form-shipment-schedule-group', require('./components/FormField'))
})
