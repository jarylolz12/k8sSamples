import ToolStore from './store/index.js'

Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'shipment-operations',
      path: '/shipment-operations',
      component: require('./components/Tool.vue'),
    },
  ]),
  store.registerModule(
      'shipmentOperations', ToolStore
  )
})
