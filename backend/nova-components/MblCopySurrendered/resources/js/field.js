Nova.booting((Vue, router, store) => {
  Vue.component('index-mbl-copy-surrendered', require('./components/IndexField'))
  Vue.component('detail-mbl-copy-surrendered', require('./components/DetailField'))
  Vue.component('form-mbl-copy-surrendered', require('./components/FormField'))

})
