Nova.booting((Vue, router, store) => {
    Vue.component("drag_and_drop", require("./components/DragAndDropInput"));
    Vue.component("netchb_entry_button", require("./components/Button"));
    Vue.component(
        "index-entry-netchb-field",
        require("./components/IndexField")
    );
    Vue.component(
        "detail-entry-netchb-field",
        require("./components/DetailField")
    );
    Vue.component("form-entry-netchb-field", require("./components/FormField"));
});
