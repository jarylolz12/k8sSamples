Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-carriers-title', require('./components/IndexField'))
  Vue.component('detail-custom-carriers-title', require('./components/DetailField'))
  Vue.component('form-custom-carriers-title', require('./components/FormField'))
})
