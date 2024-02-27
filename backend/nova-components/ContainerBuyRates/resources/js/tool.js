Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'container-buy-rates',
      path: '/container-buy-rates',
      component: require('./components/Tool'),
    },
  ])
})
