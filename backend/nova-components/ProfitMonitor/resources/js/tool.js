Nova.booting((Vue, router, store) => {
   router.addRoutes([
      {
         name: "profit-monitor",
         path: "/profit-monitor",
         component: require("./components/Tool"),
      },
   ]);

   Vue.mixin({
      methods: {
         moneyFormat(item, currency_code) {
            item = !item || item == "" || item == null || item == "null" ? 0 : item;

            item = parseFloat(item);

            if ((item && currency_code == "USD") || (item == 0 && currency_code == "USD")) {
               return item.toLocaleString("en-US", { style: "currency", currency: "USD" });
            } else if ((item && currency_code != "USD") || (item == 0 && currency_code != "USD")) {
               return item.toLocaleString("en-US", { style: "currency", currency: "USD" });
            } else {
               return (currency_code == "USD" ? "$" : "") + item;
            }
         },
         formatDateTime(d, f) {
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
