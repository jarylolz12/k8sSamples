import vSelect from "vue-select";

Nova.booting((Vue, router, store) => {
  Vue.component('index-customer-import-name', require('./components/IndexField'))
  Vue.component('detail-customer-import-name', require('./components/DetailField'))
  Vue.component('form-customer-import-name', require('./components/FormField'))
  Vue.component('confirmation-modal', require('./components/ConfirmationModal'))
})
