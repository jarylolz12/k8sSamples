Nova.booting((Vue, router, store) => {
  Vue.component('index-sale-agent-select', require('./components/IndexField'))
  Vue.component('detail-sale-agent-select', require('./components/DetailField'))
  Vue.component('form-sale-agent-select', require('./components/FormField'))
})
