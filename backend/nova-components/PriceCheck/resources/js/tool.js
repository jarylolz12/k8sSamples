import ToolStore from './store/index.js'

Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'price-check',
      path: '/price-check',
      component: require('./components/Tool.vue'),
    },
  ]),
  store.registerModule(
      'priceCheck', ToolStore
  )
})
