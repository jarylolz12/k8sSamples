import ToolStore from './store/index.js'

Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'untracked-shipments',
      path: '/untracked-shipments',
      component: require('./components/Tool'),
    },
    {
      name: 'untracked-shipments-edit',
      path: '/untracked-shipments/:id/edit',
      component: require('./components/Edit'),
    },
    {
      name: 'untracked-shipments-detail',
      path: '/untracked-shipments/:id',
      component: require('./components/Detail'),
    },
  ])

  store.registerModule(
       'untracked-tool/store', ToolStore
   );
})
