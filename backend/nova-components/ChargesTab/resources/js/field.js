import state from './store/state'
import actions from './store/actions'
import getters from './store/getters'
import mutations from './store/mutations'
import billCharges from './store/modules/billCharges'
import customer from './store/modules/customer'
import page from './store/modules/page'

//import vSelect from 'vue-select'
/* import { GridPlugin } from "@syncfusion/ej2-vue-grids";
import { DropDownListPlugin } from "@syncfusion/ej2-vue-dropdowns"; */

import { VTooltip, VPopover, VClosePopover } from 'v-tooltip'

Nova.booting((Vue, router, store) => {
    Vue.directive('tooltip', VTooltip)
    Vue.directive('close-popover', VClosePopover)
    Vue.component('v-popover', VPopover)
    Vue.component('index-charges-tab', require('./components/IndexField'))
    Vue.component('detail-charges-tab', require('./components/DetailField'))
    Vue.component('form-charges-tab', require('./components/FormField'))
    store.registerModule('chargesTab', {
        state,
        mutations,
        actions,
        getters,
    });
    
    store.registerModule('billCharges', billCharges)
    store.registerModule('customer', customer)
    store.registerModule('page', page)

  /* Vue.component(GridPlugin.name, GridPlugin)
  Vue.component('ejs-dropdownlist', DropDownListPlugin) */
  //Vue.component('v-select', vSelect)
})

/*
import vSelect from 'vue-select'
import ShipmentScheduleGroupSpecial from './components/SchedulesGroup/ShipmentScheduleGroup'
import ShipmentSupplierGroupSpecial from './components/SuppliersGroup/ShipmentSupplierGroup'
import ShipmentContainerGroupSpecial from './components/ShipmentContainerGroup/ShipmentContainerGroup'
import CustomFileField from './components/CustomFileField/CustomFileField'
import CustomSelectField from './components/CustomSelectField/CustomSelectField'

Nova.booting((Vue, router, store) => {
  Vue.component('index-shipment-departure-notice-save', require('./components/IndexField'))
  Vue.component('detail-shipment-departure-notice-save', require('./components/DetailField'))
  Vue.component('form-shipment-departure-notice-save', require('./components/FormField'))
  Vue.component('shipment-schedule-group', ShipmentScheduleGroupSpecial)
  Vue.component('shipment-supplier-group', ShipmentSupplierGroupSpecial)
  Vue.component('custom-file-field', CustomFileField)
  Vue.component('custom-select-field', CustomSelectField)
  Vue.component('shipment-container-group', ShipmentContainerGroupSpecial)
  Vue.component('v-select', vSelect)

})
*/
