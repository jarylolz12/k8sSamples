Nova.booting((Vue, router, store) => {
  Vue.component('index-booking-sent-status-milestone', require('./components/IndexField'))
  Vue.component('detail-booking-sent-status-milestone', require('./components/DetailField'))
  Vue.component('form-booking-sent-status-milestone', require('./components/FormField'))
})
