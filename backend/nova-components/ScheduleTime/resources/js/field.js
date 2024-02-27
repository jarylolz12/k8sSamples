Nova.booting((Vue, router, store) => {
  Vue.component('index-schedule-time', require('./components/IndexField'))
  Vue.component('detail-schedule-time', require('./components/DetailField'))
  Vue.component('form-schedule-time', require('./components/FormField'))
})
