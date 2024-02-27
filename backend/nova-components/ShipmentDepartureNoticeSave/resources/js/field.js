import vSelect from 'vue-select'
import ShipmentScheduleGroup from './components/SchedulesGroup/ShipmentScheduleGroup'
import ShipmentSupplierGroup from './components/SuppliersGroup/ShipmentSupplierGroup'
import ShipmentContainerGroup from './components/ShipmentContainerGroup/ShipmentContainerGroup'
import CustomFileField from './components/CustomFileField/CustomFileField'
import CustomSelectField from './components/CustomSelectField/CustomSelectField'
import MblCopySurrendered from './components/MblCopySurrendered/MblCopySurrendered'

import shipmentDeparture from './modules/shipmentDeparture'
import { VTooltip, VPopover, VClosePopover } from 'v-tooltip'

Nova.booting((Vue, router, store) => {

  Vue.component('index-shipment-departure-notice-save', require('./components/IndexField'))
  Vue.component('detail-shipment-departure-notice-save', require('./components/DetailField'))
  Vue.component('form-shipment-departure-notice-save', require('./components/FormField'))

  Vue.directive('tooltip', VTooltip)
  Vue.directive('close-popover', VClosePopover)
  Vue.component('v-popover', VPopover)
  
  store.registerModule('shipmentDeparture', shipmentDeparture);
  Vue.component('shipment-schedule-group', ShipmentScheduleGroup)
  Vue.component('shipment-supplier-group', ShipmentSupplierGroup)
  Vue.component('custom-file-field', CustomFileField)
  Vue.component('custom-select-field', CustomSelectField)
  Vue.component('shipment-container-group', ShipmentContainerGroup)
  Vue.component('v-select', vSelect)
  Vue.component('mbl-copy-surrendered', MblCopySurrendered)

})
