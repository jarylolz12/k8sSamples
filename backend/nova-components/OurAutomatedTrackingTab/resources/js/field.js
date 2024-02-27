Nova.booting((Vue, router, store) => {
  Vue.component('index-our-automated-tracking-tab', require('./components/IndexField'))
  Vue.component('detail-our-automated-tracking-tab', require('./components/DetailField'))
  Vue.component('form-our-automated-tracking-tab', require('./components/FormField'))
})
