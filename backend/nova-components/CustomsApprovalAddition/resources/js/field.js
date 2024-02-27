Nova.booting((Vue, router, store) => {
  Vue.component('index-customs-approval-addition', require('./components/IndexField'))
  Vue.component('detail-customs-approval-addition', require('./components/DetailField'))
  Vue.component('form-customs-approval-addition', require('./components/FormField'))
})
