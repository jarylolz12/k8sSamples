Nova.booting((Vue, router, store) => {
  Vue.component('index-manual-tracking-tab', require('./components/IndexField'))
  Vue.component('detail-manual-tracking-tab', require('./components/DetailField'))
  Vue.component('form-manual-tracking-tab', require('./components/FormField'))
})
