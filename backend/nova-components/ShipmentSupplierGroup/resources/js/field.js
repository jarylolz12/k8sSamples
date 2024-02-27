import VueSingleSelect from "vue-single-select";
import vSelect from "vue-select"
import PurchaseOrderProducts from "./components/PurchaseOrders/PurchaseOrderProducts"
import { VTooltip, VPopover, VClosePopover } from 'v-tooltip'

Nova.booting((Vue, router, store) => {

  Vue.directive('tooltip', VTooltip)
  Vue.directive('close-popover', VClosePopover)
  Vue.component('v-popover', VPopover)
  
  Vue.component('index-shipment-supplier-group', require('./components/IndexField'))
  Vue.component('detail-shipment-supplier-group', require('./components/DetailField'))
  Vue.component('form-shipment-supplier-group', require('./components/FormField'))
  Vue.component('vue-single-select', VueSingleSelect)
  Vue.component('v-select', vSelect)
  Vue.component('purchase-orders-products-detail', PurchaseOrderProducts)
})
