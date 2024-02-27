Nova.booting((Vue, router, store) => {
  Vue.component('index-send_delivery_order', require('./components/IndexField'))
  Vue.component('detail-send_delivery_order', require('./components/DetailField'))
  Vue.component('form-send_delivery_order', require('./components/FormField'))
})
