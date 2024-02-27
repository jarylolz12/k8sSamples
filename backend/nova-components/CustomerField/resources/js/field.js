import vSelect from "vue-select";

Nova.booting((Vue, router, store) => {
    Vue.component("index-customer-field", require("./components/IndexField"));
    Vue.component("detail-customer-field", require("./components/DetailField"));
    Vue.component("form-customer-field", require("./components/FormField"));
    Vue.component("v-select", vSelect);
});
