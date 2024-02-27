Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'profit-breakdown',
      path: '/profit-breakdown',
      component: require('./components/Tool'),
    },
  ])
})
