Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'quickbooks-audits',
      path: '/quickbooks-audits',
      component: require('./components/Tool'),
    },
  ])
})
