Nova.booting((Vue, router, store) => {
   router.addRoutes([
      {
         name: "customer-statement",
         path: "/customer-statement",
         component: require("./components/Tool"),
      },
   ]);

   // Vue.directive('click-outside', {
   //   bind: (el, binding, vnode)=>{
   //     el.clickOutsideEvent = event=>{
   //       // here I check that click was outside the el and his children
   //       if (!(el == event.target || el.contains(event.target))) {
   //       // and if it did, call method provided in attribute value
   //         vnode.context[binding.expression](event);
   //       }
   //     };

   //     document.body.addEventListener('click', el.clickOutsideEvent)
   //   },
   //   unbind: el=>{
   //     document.body.removeEventListener('click', el.clickOutsideEvent)
   //   }
   // });

   Vue.mixin({
      methods: {
         moneyFormat(item,currency_code){
            item = !item || item == '' || item == null || item == 'null' ? 0 : item;

            item = parseFloat(item);

            if( ( item && currency_code == 'USD' ) || ( item == 0 && currency_code == 'USD') ){
               return item.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
            }else if( ( item && currency_code != 'USD' ) || ( item == 0 && currency_code != 'USD') ){
               return item.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
            }else{
               return ( currency_code == 'USD' ? '$' : '')+item;
            }
         },
         formatDateTime(d,f){
            return moment(d).format(f);
         },
         isEmpty(v) {
            let type = typeof v;
            if (type === "undefined") {
               return true;
            }
            if (type === "boolean") {
               return !v;
            }
            if (v === null) {
               return true;
            }
            if (v === undefined) {
               return true;
            }
            if (v instanceof Array) {
               if (v.length < 1) {
                  return true;
               }
            } else if (type === "string") {
               if (v.length < 1) {
                  return true;
               }
               if (v === "0") {
                  return true;
               }
            } else if (type === "object") {
               if (Object.keys(v).length < 1) {
                  return true;
               }
            } else if (type === "number") {
               if (v === 0) {
                  return true;
               }
            }
            return false;
         },
      },
   });
});
