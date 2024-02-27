//import datastore from './store/datastore'

Nova.booting((Vue, router, store) => {
  /* store.registerModule(
    'thond1st/infoSaveButton', datastore
  ); */

  Vue.component('index-info-save-button', require('./components/IndexField'))
  Vue.component('detail-info-save-button', require('./components/DetailField'))
  Vue.component('form-info-save-button', require('./components/FormField'))
})
