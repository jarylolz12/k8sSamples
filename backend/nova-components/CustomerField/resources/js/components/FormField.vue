<template>
    <default-field
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
    >
        <template slot="field">
            <v-select
                :value="value"
                :options="listOfCustomers"
                :reduce="customer => customer.id"
                label="name"
                @input="setSelected"
            ></v-select>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import "vue-select/dist/vue-select.css";

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ["resourceName", "resourceId", "field"],
    data() {
        return {
            listOfCustomers: [],
            value: {}
        };
    },
    mounted() {
        this.getAllCustomers();
        //this.$nextTick(function () {
        //Nova.$emit("on-customer-shipment-select-loaded", false);
        //})
    },
    methods: {
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append("custom_customer", this.value || "");
        },

        getAllCustomers: async function() {
            try {
                const { data } = await Nova.request().get("/custom/customers");

                if (data.results.length > 0) {
                    const tempCustomers = data.results.map(result => {
                        return {
                            id: result.id,
                            name: result.company_name
                        };
                    });
                    this.listOfCustomers = tempCustomers;

                    if (this.field.defaultCustomer) {
                        const tempCustomer = tempCustomers.find(
                            customer =>
                                customer.id === this.field.defaultCustomer.id
                        );
                        this.value = tempCustomer.id;
                        Nova.$emit("on-customer-shipment-select", this.value);
                    }
                    Nova.$emit("on-customer-shipment-select-loaded", true);
                }
            } catch (err) {
                this.$toasted.show("Failed to get customers", {
                    type: "error"
                });
            }
        },

        setSelected: function(value) {
            Nova.$emit("on-customer-shipment-select", value);
            this.value = value;
        }
    }
};
</script>
