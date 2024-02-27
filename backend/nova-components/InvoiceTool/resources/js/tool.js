Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'invoice-tool',
      path: '/invoice-tool',
      component: require('./components/Tool'),
    },
  ])
})
