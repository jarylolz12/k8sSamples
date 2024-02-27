Nova.booting((Vue, router, store) => {
  Vue.component('index-milestone-notes-update', require('./components/IndexField'))
  Vue.component('detail-milestone-notes-update', require('./components/DetailField'))
  Vue.component('form-milestone-notes-update', require('./components/FormField'))
})
