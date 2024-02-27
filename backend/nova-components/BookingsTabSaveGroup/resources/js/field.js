import { vfmPlugin } from "vue-final-modal";
import VueLoaders from 'vue-loaders';
import ShipmentScheduleGroup from './components/SchedulesGroup/ShipmentScheduleGroup'
import ShipmentSupplierGroup from './components/SuppliersGroup/ShipmentSupplierGroup'
import ShipmentContainerLimitedGroup from './components/ShipmentContainerLimitedGroup/ShipmentContainerLimitedGroup'
import PurchaseOrdersTable from "./components/PurchaseOrders/PurchaseOrdersTable"
import PurchaseOrderProducts from "./components/PurchaseOrders/PurchaseOrderProducts"
import PurchaseOrderItems from "./components/PurchaseOrders/PurchaseOrderItems"

import shipment from './modules/shipment'
import { VTooltip, VPopover, VClosePopover } from 'v-tooltip'

Nova.booting((Vue, router, store) => {
  Vue.use(vfmPlugin);
  Vue.use(VueLoaders);
  Vue.directive('tooltip', VTooltip)
  Vue.directive('close-popover', VClosePopover)
  Vue.component('v-popover', VPopover)
  store.registerModule('shipment', shipment);
  Vue.component('index-bookings-tab-save-group', require('./components/IndexField'))
  Vue.component('detail-bookings-tab-save-group', require('./components/DetailField'))
  Vue.component('form-bookings-tab-save-group', require('./components/FormField'))
  Vue.component('shipment-schedule-multiple-group', ShipmentScheduleGroup)
  Vue.component('shipment-supplier-limited-group', ShipmentSupplierGroup)
  Vue.component('shipment-container-limited-group', ShipmentContainerLimitedGroup)
  Vue.component('purchase-orders-table', PurchaseOrdersTable)
  Vue.component('purchase-orders-products', PurchaseOrderProducts)
  Vue.component('purchase-orders-items', PurchaseOrderItems)
})
