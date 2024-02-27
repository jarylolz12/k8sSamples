Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'lfd-tool',
      path: '/lfd-tool',
      component: require('./components/Tool'),
    },
  ])
})
