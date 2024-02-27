Nova.booting((Vue, router, store) => {
  Vue.component('index-so-tab-content', require('./components/IndexField'))
  Vue.component('detail-so-tab-content', require('./components/DetailField'))
  Vue.component('form-so-tab-content', require('./components/FormField'))
})
