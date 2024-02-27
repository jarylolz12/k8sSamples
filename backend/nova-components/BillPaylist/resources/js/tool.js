import billPaymentList from './modules/billPaymentList'
import account from './modules/account'

Nova.booting((Vue, router, store) => {


  //register bill payment list module
  store.registerModule('billPaymentList', billPaymentList);
  store.registerModule('account', account);
  
  router.addRoutes([
    {
      name: 'bill-paylist',
      path: '/bill-paylist',
      component: require('./components/Tool'),
    },
  ])
})
