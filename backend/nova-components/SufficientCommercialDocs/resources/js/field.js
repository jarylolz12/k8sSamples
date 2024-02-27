Nova.booting((Vue, router, store) => {
  Vue.component('index-sufficient-commercial-docs', require('./components/IndexField'))
  Vue.component('detail-sufficient-commercial-docs', require('./components/DetailField'))
  Vue.component('form-sufficient-commercial-docs', require('./components/FormField'))
})
