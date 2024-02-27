Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-button', require('./components/IndexField'))
  Vue.component('detail-custom-button', require('./components/DetailField'))
  Vue.component('form-custom-button', require('./components/FormField'))
})
