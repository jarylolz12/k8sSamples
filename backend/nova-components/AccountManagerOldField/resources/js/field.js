Nova.booting((Vue, router, store) => {
  Vue.component('index-account-manager-old-field', require('./components/IndexField'))
  Vue.component('detail-account-manager-old-field', require('./components/DetailField'))
  Vue.component('form-account-manager-old-field', require('./components/FormField'))
})
