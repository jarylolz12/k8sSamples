Nova.booting((Vue, router, store) => {
  Vue.component('index-ShipmentButtonTabSave', require('./components/IndexField'))
  Vue.component('detail-ShipmentButtonTabSave', require('./components/DetailField'))
  Vue.component('form-ShipmentButtonTabSave', require('./components/FormField'))
})
