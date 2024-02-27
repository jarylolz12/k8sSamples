import vSelect from "vue-select";

Nova.booting((Vue, router, store) => {
    Vue.component("index-customers-field", require("./components/IndexField"));
    Vue.component(
        "detail-customers-field",
        require("./components/DetailField")
    );
    Vue.component("form-customers-field", require("./components/FormField"));
    Vue.component("v-select", vSelect);
});
