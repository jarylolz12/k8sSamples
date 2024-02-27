Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-file-field', require('./components/IndexField'))
  Vue.component('detail-custom-file-field', require('./components/DetailField'))
  Vue.component('form-custom-file-field', require('./components/FormField'))
})
