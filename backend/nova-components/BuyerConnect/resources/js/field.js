Nova.booting((Vue, router, store) => {
  Vue.component('index-buyer-connect', require('./components/IndexField'))
  Vue.component('detail-buyer-connect', require('./components/DetailField'))
  Vue.component('form-buyer-connect', require('./components/FormField'))
})
