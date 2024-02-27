Nova.booting((Vue, router, store) => {
  Vue.component('index-select-city', require('./components/IndexField'))
  Vue.component('detail-select-city', require('./components/DetailField'))
  Vue.component('form-select-city', require('./components/FormField'))
})
