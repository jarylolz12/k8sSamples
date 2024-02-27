import router from "./router";
import store from "./store";
// import accountingModule from '../shifl-vue-accounting-module/srac';

let accountingModule = {};

try{
    if(process.env.VUE_APP_ACCOUNTING_ENABLED === 'true'){
        accountingModule = require('../shifl-vue-accounting-module/src').default;
    }
   
} catch (error) {
    console.log(error);
}

const registerModule = (name, module) => {
    if(module.store){
        store.registerModule(name, module.store);
    }

    if(module.router){
        module.router(router);
    }
};

export const registerAccountingModule = function() {
    if(Object.keys(accountingModule).length > 0){
        registerModule('accounting', accountingModule);
    }else{
        registerModule('',accountingModule);
    }
};

// export const registerModules = modules => {
//     Object.keys(modules).forEach(moduleKey => {
//         const module = modules[moduleKey];
//         registerModule(moduleKey, module);
//     });
// };