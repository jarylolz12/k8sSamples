<template>
    <div>
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
                    @change="setSelected"
                    @input="setSelected">
                </v-select>
             </template>
        </default-field>
        <div v-show="bShow" class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">
                <label for="import_name" class="inline-block text-80 pt-2 leading-tight">
                Import Names
                <span class="text-danger text-sm">*</span>
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <v-select
                    :value="import_name"
                    :options="listOfImportNames"
                    :reduce="import_name => import_name.id"
                    label="name"
                    id="importName"
                    @input="setSelectedImport">
                </v-select>
            </div>
        </div>
        <confirmation-modal v-if="confirmationDialog" @close="closeConfirmationDialog" @confirm="confirm"/>
    </div>


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
            value: {},
            listOfImportNames: [],
            import_name: {},
            bShow: false,
            confirmationDialog:false,
            changeCustomerValue: null,
        };
    },
    mounted() {
        this.getAllCustomers();
        this.getImportNameByCustomerId(this.field.defaultFields.customer.id);
    },
    methods: {
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append("custom_customer", this.value || "");
            formData.append("import_name_id", this.import_name || "");

        },
        getImportNameByCustomerId: async function(customerID) {

            try {
                const  aData = await Nova.request().get(`/custom/import-names/${customerID}`);

                if(aData.data.results.length > 0)    {
                    const tempImports = aData.data.results.map(result => {
                        return {
                            id: result.id,
                            name: result.import_name
                        };
                    });

                    this.listOfImportNames = tempImports;
                    this.bShow = true;
                    if (this.field.defaultFields.import_name) {
                        const tempImport = tempImports.find(
                            importName =>
                                importName.id === this.field.defaultFields.import_name.id
                        );
                        this.import_name = tempImport ? tempImport.id : '';
                        Nova.$emit("on-customer-import-name-select", this.import_name);
                    }else{
                        this.import_name = '';
                    }
                    Nova.$emit("on-customer-import-name-select-loaded", true);

                }else{
                    this.bShow = false;
                }

            } catch (error) {
                this.$toasted.show("Failed to get customers", {
                    type: "error"
                });
            }
        },
        getAllCustomers: async function() {

            try {
                const { data } = await Nova.request().get("/custom/customers");

                if (data.results.length > 0) {
                    const tempCustomers = data.results.map(result => {
                        return {
                            id: result.id,
                            name: result.company_name,
                        };
                    });
                    this.listOfCustomers = tempCustomers;

                    if (this.field.defaultFields.customer) {

                        const tempCustomer = tempCustomers.find(
                            customer =>
                                customer.id === this.field.defaultFields.customer.id
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

        setSelected: function (value) {
            if (this.field.defaultFields.customer && this.field.defaultFields.customer.id && this.field.defaultFields.customer.id !== value) {
                this.changeCustomerValue = value;
                this.confirmationDialog = true;
                Nova.$emit("on-customer-shipment-select", value);
                this.getCustomerUpdatedValue(this.$parent, '');
                this.getImportNameByCustomerId(value);
            } else {
                Nova.$emit("on-customer-shipment-select", value);
                this.value = value;
                this.getCustomerUpdatedValue(this.$parent, '');
                this.getImportNameByCustomerId(value);
            }
        },
        setSelectedImport: function(val){
            Nova.$emit("on-customer-import-name-select", val);
            this.import_name = val;
            this.getCustomerUpdatedValue(this.$parent, val);
        },
        getCustomerUpdatedValue(root, val){
            root.$children.forEach(component => {
                if(component._props.field != undefined){
                    if(component._props.field.attribute == 'import_name_id'){
                        component.value = val ? val : '';
                        return;
                    }
                }
            });
        },
        closeConfirmationDialog(){
            this.confirmationDialog = false;
        },
        confirm(){
            this.value = this.changeCustomerValue;
            this.confirmationDialog = false;
        }

    }
};
</script>
