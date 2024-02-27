Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-schedule-multiple-group', require('./components/IndexField'))
  Vue.component('detail-shipment-schedule-multiple-group', require('./components/DetailField'))
  Vue.component('form-shipment-schedule-multiple-group', require('./components/FormField'))
})
