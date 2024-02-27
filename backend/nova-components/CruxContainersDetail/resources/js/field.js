Nova.booting((Vue, router, store) => {
  Vue.component('index-crux-containers-detail', require('./components/IndexField'))
  Vue.component('detail-crux-containers-detail', require('./components/DetailField'))
  Vue.component('form-crux-containers-detail', require('./components/FormField'))
})
