Nova.booting((Vue, router, store) => {
  Vue.component('index-announcement', require('./components/IndexField'))
  Vue.component('detail-announcement', require('./components/DetailField'))
  Vue.component('form-announcement', require('./components/FormField'))
})
