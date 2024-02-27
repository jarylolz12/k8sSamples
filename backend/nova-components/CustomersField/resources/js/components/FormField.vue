<template>
    <default-field
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
    >
        <template slot="field">
            <v-select
                :value="customers"
                :options="listOfCustomers"
                :reduce="customer => customer.id"
                label="name"
                multiple
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
            customers: []
        };
    },
    mounted() {
        this.getAllCustomers();
    },
    methods: {
        fill(formData) {
            if (this.customers.length > 0) {
                let i = 0;
                for (const customer of this.customers) {
                    formData.append("custom_customers[" + i + "]", customer);
                    i++;
                }
            } else {
                formData.append("custom_customers", null);
            }
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
                }
            } catch (err) {
                this.$toasted.show("Failed to get customers", {
                    type: "error"
                });
            }
        },

        setSelected: function(value) {
            this.customers = value;
        }
    }
};
</script>
