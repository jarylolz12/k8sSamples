Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'ach-report',
      path: '/ach-report',
      component: require('./components/Tool'),
    },
    // {
    //     name: 'ach-report-statement',
    //     path: '/ach-report/customer/:customer_id',
    //     component: require('./components/Statement'),
    // },
    {
        name: 'ach-report-monthly-statement',
        path: '/ach-report/customer/:customer_id/monthly-statement',
        component: require('./components/StatementMonthly'),
    },
    {
        name: 'ach-report-monthly-statement-details',
        path: '/ach-report/monthly-statement/:statement_no',
        component: require('./components/ViewMonthlyStatementDetails'),
    },
    {
        name: 'ach-report-daily-statement-details',
        path: '/ach-report/daily-statement/:statement_no',
        component: require('./components/StatementDaily'),
    },
    {
        name: 'ach-report-daily-statement',
        path: '/ach-report/customer/:customer_id/daily-statement',
        component: require('./components/StatementDailyList'),
    },
    {
        name: 'details',
        path: '/ach-report/daily-statement/:id',
        component: require('./components/StatementDaily'),
    },
  ])
})
