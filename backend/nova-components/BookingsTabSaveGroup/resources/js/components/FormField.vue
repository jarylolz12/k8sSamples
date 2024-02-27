<template>
    <div>
        <shipment-schedule-multiple-group
            :terminalRegions="terminalRegions"
            :field="shipmentScheduledGroupField"
            :supplierOptions="supplierOptions"
            :incoTermValues="incoTermValues"
            :shipmentSupplierGroupField="shipmentSupplierGroupField"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :vendors="vendors"
            :agents="agents"
            :services="services"
            :carriers="carriers"
            :sizes="sizes"
            :rateSizes="rateSizes"
            :types="types"
            @updateScheduleGroup="updateScheduleGroup"
        />
        <div class="flex border-b border-40 remove-bottom-border">
            <div class="w-1/5 px-8 py-6">
                <label
                    for="memo_customer"
                    class="inline-block text-80 pt-2 leading-tight"
                >
                    Memo
                </label>
            </div>
            <div class="py-6 px-8 w-4/5">
                <textarea
                    v-model="memo_customer_model"
                    id="memo_customer"
                    rows="5"
                    placeholder="Memo"
                    class="w-full form-control form-input form-input-bordered py-3 h-auto"
                >
                </textarea>
                <div class="help-text help-text mt-2"></div>
            </div>
        </div>

        <shipment-container-limited-group
            :field="shipmentContainerGroupField"
            :resourceName="resourceName"
            :sizes="sizes"
            :resourceId="resourceId"
            @updateContainerGroup="updateContainerGroup"
        />
        <div v-if="!getShipmentSupplierGroupField.loading && (typeof resourceId
        =='undefined' || resourceId==null || resourceId=='')" class="flex border-b border-40 remove-bottom-border">
            <div class="w-1/5 px-8 py-6">
                <label
                    class="inline-block text-80 pt-2 leading-tight"
                >
                    Suppliers Section
                </label>
            </div>
            <div class="py-6 px-8 flex items-center w-4/5">
                You can only add supplier after creating this shipment.
            </div>
        </div>
        <shipment-supplier-limited-group
            v-if="!getShipmentSupplierGroupField.loading && (typeof resourceId
        !=='undefined' && resourceId!==null && resourceId!=='')"
            :shifl_ref="field.shifl_ref"
            :BuyerOptions="BuyerOptions"
            :shipment="field.shipment"
            :multiPurchaseOrders="multiPurchaseOrders"
            :customerId="customerId"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :containers="containers"
            :baseUrl="field.baseUrl"
            @setSuppliers="setSuppliers"
            :field="getShipmentSupplierGroupField"
            :purchaseOrderOptions="purchaseOrderOptions"
            :supplierOptions="supplierOptions"
            :incoTermValues="incoTermValues"
            @updateSupplierGroup="updateSupplierGroup"
            :supplierDocumentsPayload="supplierDocumentsPayload"
            @updateMultiPurchaseOrders="updateMultiPurchaseOrders"
            @setSupplierDocuments="setSupplierDocuments"
        />
        <div
            v-show="resourceId != '' && resourceId != null"
            class="flex border-b border-40"
        >
            <div class="w-1/5 px-8 py-6"></div>
            <div class="py-6 px-8 w-1/2">
                <button
                    v-show="loading"
                    disabled
                    class="btn btn-default btn-primary inline-flex items-center relative"
                    dusk="create-button"
                >
                    <span class="">
                        Saving...
                    </span>
                </button>
                <button
                    id="save-bookings-tab"
                    v-show="!loading"
                    @click="e => saveBookingsTab(e)"
                    class="btn btn-default btn-primary inline-flex items-center relative"
                    dusk="create-button"
                >
                    <span class="">
                        Save
                    </span>
                </button>
            </div>
        </div>

    </div>
</template>
<style type="text/css">
.border-custom-danger {
    border: 1px solid var(--danger) !important;
    border-radius: 0.5rem;
}
</style>
<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import jQuery from "jquery";
import _ from "lodash";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { mapGetters, mapActions } from 'vuex'
import iziToast from 'izitoast'
import 'izitoast/dist/css/iziToast.min.css'

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ["resourceName", "resourceId", "field", "panels"],
    data() {
        return {
            suppliersLoaded: false,
            multiPurchaseOrders: [],
            resourceUrlName: null,
            customerId: '',
            supplierDocumentsPayload: {},
            vendors: [],
            agents: [],
            loading: false,
            loadingText: "Saving",
            buttonText: "Save",
            terminalRegions: null,
            //terminalRegions: (typeof this.field.terminalRegions!=='undefined') ? this.field.terminalRegions : null,
            schedules:
                typeof this.field.shipment.schedules_group_bookings !==
                "undefined"
                    ? this.field.shipment.schedules_group_bookings
                    : [],
            containers:
                typeof this.field.shipment.containers_group_bookings !==
                "undefined"
                    ? this.field.shipment.containers_group_bookings
                    : [],
            shipmentScheduledGroupField: {
                attribute: "schedules_group",
                name: "Schedule Section",
                value:
                    typeof this.field.shipment.schedules_group_bookings !==
                    "undefined"
                        ? this.field.shipment.schedules_group_bookings
                        : null
            },
            shipmentContainerGroupField: {
                attribute: "containers_group",
                name: "Container Section",
                value:
                    typeof this.field.shipment.containers_group_bookings !==
                    "undefined"
                        ? this.field.shipment.containers_group_bookings
                        : null
            },
            shipmentSupplierGroupField: {
                attribute: "suppliers_group",
                name: "Suppliers Section",
                value:
                    typeof this.field.shipment.suppliers_group_bookings !==
                    "undefined"
                        ? this.field.shipment.suppliers_group_bookings
                        : null
            },
            booking_confirmed:
                typeof this.field.shipment.booking_confirmed !== "undefined" &&
                this.field.shipment.booking_confirmed == 1
                    ? true
                    : false,
            memo_customer: {
                attribute: "memo_customer",
                name: "Memo",
                value:
                    typeof this.field.shipment.memo_customer !== "undefined"
                        ? this.field.shipment.memo_customer
                        : null
            },
            memo_customer_model:
                typeof this.field.shipment.memo_customer !== "undefined"
                    ? this.field.shipment.memo_customer
                    : "",
            types:
                typeof this.field.types !== "undefined" ? this.field.types : [],
            carriers: [],
            //carriers: (typeof this.field.carriers!=='undefined') ? this.field.carriers : [],
            //services: (typeof this.field.services!=='undefined') ? this.field.services: [],
            services: [],
            sizes: [],
            //sizes: (typeof this.field.sizes!=='undefined') ? this.field.sizes : [],
            rateSizes:
                typeof this.field.rateSizes !== "undefined"
                    ? this.field.rateSizes
                    : [],
            schedulesGroupCopy: null,
            suppliersGroupCopy: null,
            suppliers:
                typeof this.field.shipment.suppliers_group_bookings !==
                "undefined"
                    ? this.field.shipment.suppliers_group_bookings
                    : [],
            //supplierOptions: (typeof this.field.suppliers!=='undefined') ? this.field.suppliers : null,
            //incoTermValues: (typeof this.field.incoTerms!=='undefined') ? this.field.incoTerms : null,
            incoTermValues: null,
            purchaseOrderOptions: [],
            //purchaseOrderOptions: (typeof this.field.purchaseOrders!=='undefined') ? this.field.purchaseOrders : null,
            supplierOptions: [],
            BuyerOptions: []
        };
    },
    updated() {
    },
    beforeDestroy() {
        Nova.$off("on-customer-shipment-select");
    },
    computed: {
        ...mapGetters({
            getShipmentSupplierGroupField: 'shipment/getShipmentSupplierGroupField'
        }),
        token() {
            return jQuery('meta[name="csrf-token"]').attr("content");
        }
    },
    beforeMount() {
        //get all carriers
        fetch(`${this.field.baseUrl}/custom/carriers`, {
            token: this.token
        })
        .then(this.handleResponse)
        .then(response => {
            let { results } = response;
            if (typeof results !== "undefined") {
                this.carriers = results;
            }
        });
    },
    mounted() {

        //initialize suppliers
        this.setSuppliers()

        let arrPath = window.location.pathname.split("/");
        this.resourceUrlName = arrPath[3];

        Nova.$on("on-customer-shipment-select", value => {
            this.customerId = value
            this.fetchCustomerSuppliers(value);
            this.fetchCustomerBuyers(value);
        });

        //set vendors
        this.setVendors()

        //set agents
        this.setAgents()

        //set terminal regions
        this.setTerminalRegions()

        //get all incoterms
        this.setIncoTerms()

        //get all sizes
        fetch(`${this.field.baseUrl}/custom/container-sizes`, {
            token: this.token
        })
            .then(this.handleResponse)
            .then(response => {
                let { results } = response;
                if (typeof results !== "undefined") {
                    this.sizes = results;
                    if (
                        typeof this.resourceId !== "undefined" &&
                        this.resourceId !== null &&
                        this.resourceId !== ""
                    ) {
                        let schedules_group_bookings =
                            typeof this.field.shipment
                                .schedules_group_bookings !== "undefined"
                                ? JSON.parse(
                                      this.field.shipment
                                          .schedules_group_bookings
                                  )
                                : [];

                        if (schedules_group_bookings.length > 0) {
                            for (
                                var y = 0;
                                y < schedules_group_bookings.length;
                                y++
                            ) {
                                //s
                                if (this.sizes.length > 0) {
                                    for (
                                        var x = 0;
                                        x < this.sizes.length;
                                        x++
                                    ) {
                                        if (
                                            typeof schedules_group_bookings[y]
                                                .selling_size_id !== "undefined"
                                        ) {
                                            if (
                                                this.sizes[x].id ==
                                                schedules_group_bookings[y]
                                                    .selling_size_id
                                            ) {
                                                schedules_group_bookings[
                                                    y
                                                ].selling_size_name = this.sizes[
                                                    x
                                                ].name;
                                            }
                                        }

                                        if (
                                            typeof schedules_group_bookings[y]
                                                .size_id !== "undefined"
                                        ) {
                                            if (
                                                this.sizes[x].id ==
                                                schedules_group_bookings[y]
                                                    .size_id
                                            ) {
                                                schedules_group_bookings[
                                                    y
                                                ].size_name = this.sizes[
                                                    x
                                                ].name;
                                            }
                                        }

                                        if (
                                            typeof schedules_group_bookings[y]
                                                .sell_rates !== "undefined" &&
                                            schedules_group_bookings[y]
                                                .sell_rates.length > 0
                                        ) {
                                            for (
                                                var z = 0;
                                                z <
                                                schedules_group_bookings[y]
                                                    .sell_rates.length;
                                                z++
                                            ) {
                                                if (
                                                    schedules_group_bookings[y]
                                                        .sell_rates[z]
                                                        .container_size_id ==
                                                    this.sizes[x].id
                                                ) {
                                                    schedules_group_bookings[
                                                        y
                                                    ].sell_rates[
                                                        z
                                                    ].container_size_name = this.sizes[
                                                        x
                                                    ].name;
                                                }
                                            }
                                        }

                                        if (
                                            typeof schedules_group_bookings[y]
                                                .buy_rates !== "undefined" &&
                                            schedules_group_bookings[y]
                                                .buy_rates.length > 0
                                        ) {
                                            for (
                                                var z = 0;
                                                z <
                                                schedules_group_bookings[y]
                                                    .buy_rates.length;
                                                z++
                                            ) {
                                                if (
                                                    schedules_group_bookings[y]
                                                        .buy_rates[z]
                                                        .container_size_id ==
                                                    this.sizes[x].id
                                                ) {
                                                    schedules_group_bookings[
                                                        y
                                                    ].buy_rates[
                                                        z
                                                    ].container_size_name = this.sizes[
                                                        x
                                                    ].name;
                                                }
                                            }
                                        }
                                    }
                                }
                                //eschedules_group_bookings
                            }

                            this.schedules = JSON.stringify(
                                schedules_group_bookings
                            );
                            this.shipmentScheduledGroupField.value = JSON.stringify(
                                schedules_group_bookings
                            );
                        }
                    }
                }
            });

        //get all purchase orders
        fetch(`${this.field.baseUrl}/custom/purchase-orders`, {
            token: this.token
        })
        .then(this.handleResponse)
        .then(response => {
            let { results } = response;
            if (typeof results !== "undefined") {
                this.purchaseOrderOptions = results;
            }
        });

        //get all services
        fetch(`${this.field.baseUrl}/custom/services`, {
            token: this.token
        })
        .then(this.handleResponse)
        .then(response => {
            let { results } = response;
            if (typeof results !== "undefined") {
                this.services = results;

                if (
                    typeof this.resourceId !== "undefined" &&
                    this.resourceId !== null &&
                    this.resourceId !== ""
                ) {
                    let schedules_group_bookings =
                        typeof this.field.shipment
                            .schedules_group_bookings !== "undefined"
                            ? JSON.parse(
                                  this.field.shipment
                                      .schedules_group_bookings
                              )
                            : [];

                    if (schedules_group_bookings.length > 0) {
                        for (
                            var y = 0;
                            y < schedules_group_bookings.length;
                            y++
                        ) {
                            if (this.services.length > 0) {
                                for (
                                    var x = 0;
                                    x < this.services.length;
                                    x++
                                ) {
                                    if (
                                        typeof schedules_group_bookings[y]
                                            .sell_rates !== "undefined" &&
                                        schedules_group_bookings[y]
                                            .sell_rates.length > 0
                                    ) {
                                        for (
                                            var z = 0;
                                            z <
                                            schedules_group_bookings[y]
                                                .sell_rates.length;
                                            z++
                                        ) {
                                            if (
                                                schedules_group_bookings[y]
                                                    .sell_rates[z]
                                                    .service_id ==
                                                this.services[x].id
                                            ) {
                                                schedules_group_bookings[
                                                    y
                                                ].sell_rates[
                                                    z
                                                ].service_name = this.services[
                                                    x
                                                ].name;
                                            }
                                        }
                                    }

                                    if (
                                        typeof schedules_group_bookings[y]
                                            .buy_rates !== "undefined" &&
                                        schedules_group_bookings[y]
                                            .buy_rates.length > 0
                                    ) {
                                        for (
                                            var z = 0;
                                            z <
                                            schedules_group_bookings[y]
                                                .buy_rates.length;
                                            z++
                                        ) {
                                            if (
                                                schedules_group_bookings[y]
                                                    .buy_rates[z]
                                                    .service_id ==
                                                this.services[x].id
                                            ) {
                                                schedules_group_bookings[
                                                    y
                                                ].buy_rates[
                                                    z
                                                ].service_name = this.services[
                                                    x
                                                ].name;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        //this.schedules_group_bookings = schedules_group_bookings
                        this.schedules = JSON.stringify(
                            schedules_group_bookings
                        );
                        this.shipmentScheduledGroupField.value = JSON.stringify(
                            schedules_group_bookings
                        );
                    }
                }
            }
        });
    },
    methods: {
        ...mapActions({
            setShipmentSupplierGroupField: 'shipment/setShipmentSupplierGroupField',
            fetchSuppliers: 'shipment/fetchShipmentSuppliers',
            uploadDocuments: 'shipment/uploadDocuments',
        }),
        resetSuppliers() {
            let {
                field,
                url
            } = this.supplierDocumentsPayload
            //reset suppliers data
            let shipment_supplier_field = this.getShipmentSupplierGroupField
            shipment_supplier_field.value = this.field.value
            this.setShipmentSupplierGroupField(shipment_supplier_field)
            this.fetchSuppliers({
                shipment_id: this.resourceId,
                shipment_supplier_field,
                base_url: url
            })
        },
        setSupplierDocuments(payload) {
            if ( typeof this.supplierDocumentsPayload.documents!=='undefined' ) {

                if ( typeof payload.documents!=='undefined' ) {
                    payload.documents.map(pd => {
                        let findDocumentIndex = _.findIndex(this.supplierDocumentsPayload.documents, {
                            supplier_temp_id: pd.supplier_temp_id,
                            type: pd.type
                        })
                        if ( findDocumentIndex == -1 ) {
                            this.supplierDocumentsPayload.documents.push(pd)
                        } else {
                            this.supplierDocumentsPayload.documents[findDocumentIndex] = pd
                        }
                    })
                }

            } else {
                this.supplierDocumentsPayload = payload
            }
        },
        setSuppliers() {
            let value = typeof this.field.shipment.suppliers_group_bookings !== "undefined" ? this.field.shipment.suppliers_group_bookings
                    : null
            let shipment_supplier_field = this.getShipmentSupplierGroupField
            shipment_supplier_field.value = value
            this.setShipmentSupplierGroupField(shipment_supplier_field)
            this.fetchSuppliers({
                shipment_id: this.resourceId,
                shipment_supplier_field,
                base_url: this.field.baseUrl
            })
        },
        setIncoTerms() {
            //set incoterms
            fetch(`${this.field.baseUrl}/custom/incoterms`, {
                token: this.token
            })
            .then(this.handleResponse)
            .then(response => {
                let { results } = response;
                if (typeof results !== "undefined") {
                    this.incoTermValues = results;
                }
            });
        },
        setTerminalRegions() {
            //get all terminal regions
            fetch(`${this.field.baseUrl}/custom/terminal-regions`, {
                token: this.token
            })
            .then(this.handleResponse)
            .then(response => {
                let { results } = response;
                if (typeof results !== "undefined") {
                    let newTerminalRegions = [];

                    for (var x = 0; x < results.length; x++) {
                        newTerminalRegions.push({
                            name: results[x].name,
                            id: results[x].id
                        });
                    }
                    this.terminalRegions = newTerminalRegions;
                }
            });
        },
        setVendors() {
            //set vendors
            fetch(`${this.field.baseUrl}/custom/vendors`, {
                token: this.token
            })
            .then(this.handleResponse)
            .then(response => {
                let { items } = response
                this.vendors = items
            })
        },
        setAgents() {
            //set agents
            fetch(`${this.field.baseUrl}/custom/agents`, {
                token: this.token
            })
                .then(this.handleResponse)
                .then(response => {
                    let { items } = response
                    this.agents   = items
                })
        },
        updateMultiPurchaseOrders: function(purchaseOrders){
            this.multiPurchaseOrders = [...purchaseOrders]
        },
        fetchCustomerSuppliers: async function(customerID) {
            const { data } = await Nova.request().get(
                `/custom/customers/${customerID}`
            );
            this.supplierOptions = data.suppliers;
        },
        fetchCustomerBuyers: async function(customerID) {
            const { data } = await Nova.request().get(
                `/custom/buyers/${customerID}`
            );
            this.BuyerOptions = data.buyer;
        },
        updateScheduleGroup(schedulesGroup) {
            this.schedulesGroupCopy = schedulesGroup;
            this.schedules = JSON.stringify(schedulesGroup);
        },
        updateContainerGroup(containerGroup) {
            this.containers = JSON.stringify(containerGroup);
        },
        updateSupplierGroup(suppliersGroup) {

            const tempMultiPurchaseOrders = []
            for(const supplier of suppliersGroup) {
                if(supplier.purchaseOrders) {
                    tempMultiPurchaseOrders.push({
                        buyerId: supplier.buyer_id,
                        supplierId: supplier.supplier_id,
                        purchaseOrders: supplier.purchaseOrders
                    })
                }
            }
            this.multiPurchaseOrders = tempMultiPurchaseOrders

            const tempSuppliersGroup = suppliersGroup.map(supplier => {
                const tempObject = {...supplier}
                delete tempObject.isOpenAddPurchaseOrders
                delete tempObject.isOpenPurchaseOrdersModal
                delete tempObject.isOpenPurchaseOrdersProductsModal
                delete tempObject.purchaseOrder
                delete tempObject.purchaseOrders
                return tempObject
            })

            this.suppliersGroupCopy = tempSuppliersGroup;
            this.suppliers = JSON.stringify(tempSuppliersGroup);

        },
        uploadSupplierDocuments() {
            return new Promise((resolve, reject) => {
                let {
                    documents,
                    resourceId,
                    item,
                    url
                } = this.supplierDocumentsPayload
                item.is_loading = true
                documents.map((df,k) => {
                    documents[k].typeError = false
                    documents[k].supplierError = false
                    documents[k].nameError = false
                    documents[k].fileError = false
                })

                this.uploadDocuments({
                    documents,
                    resourceId,
                    url
                }).then(() => {
                    item.is_loading = false
                    this.resetSuppliers()
                    resolve()
                }).catch(e => {
                    item.is_loading = false
                    if ( typeof e!=='undefined' ) {
                      let error_messages = []
                      let error_message = ''

                      if ( typeof e !=='undefined' && typeof e.errors!=='undefined' ) {

                        let getKeys = Object.keys(e.errors)

                        documents.map((df,k) => {
                          documents[k].typeError = false
                          documents[k].supplierError = false
                          documents[k].nameError = false
                          documents[k].fileError = false
                        })

                        getKeys.map( gk =>{
                          let splitKeys = gk.split('.')
                          if ( splitKeys[2] === 'file') {
                            documents[parseInt(splitKeys[1])].fileError = true
                          } else if ( splitKeys[2] === 'type') {
                            documents[parseInt(splitKeys[1])].typeError = true
                          } else if ( splitKeys[2] === 'name') {
                            documents[parseInt(splitKeys[1])].nameError = true
                          }
                        })

                      }

                    } else {
                    }
                    reject(e)
                })
            })

        },
        saveBookingsTab(e) {
            e.preventDefault();

            if (!this.loading) {
                this.loading = true;
                this.buttonText = "Saving...";
                //const token = jQuery('meta[name="csrf-token"]').attr('content')

                var fd = new FormData();

                let suppliersGroup = this.suppliersGroupCopy;
                if (suppliersGroup !== null && suppliersGroup.length > 0) {
                    var fileContainer = [];
                    for (var x = 0; x < suppliersGroup.length; x++) {
                        if (
                            typeof suppliersGroup[x].packing_list !=
                                "undefined" &&
                            suppliersGroup[x].packing_list !== null
                        )
                            fd.append(
                                `bookings_packing_list_${x + 1}`,
                                suppliersGroup[x].packing_list
                            );
                        if (
                            typeof suppliersGroup[x].commercial_documents !=
                                "undefined" &&
                            suppliersGroup[x].commercial_documents !== null
                        )
                            fd.append(
                                `bookings_commercial_documents_${x + 1}`,
                                suppliersGroup[x].commercial_documents
                            );

                        if (
                            typeof suppliersGroup[x].hbl_copy !=
                                "undefined" &&
                            suppliersGroup[x].hbl_copy !== null
                        )
                            fd.append(
                                `hbl_copy_${x + 1}`,
                                suppliersGroup[x].hbl_copy
                            );

                        if (
                            typeof suppliersGroup[x].commercial_invoice !=
                                "undefined" &&
                            suppliersGroup[x].commercial_invoice !== null
                        )
                            fd.append(
                                `bookings_commercial_invoice_${x + 1}`,
                                suppliersGroup[x].commercial_invoice
                            );
                    }
                }

                let set_suppliers = JSON.parse(this.suppliers)
                let new_suppliers = []
                if ( set_suppliers.length > 0 ) {
                    set_suppliers.map ( ss => {
                        let {
                            commercial_documents_customer,
                            packing_list_customer,
                            invoice_packing_list_customer,
                            commercial_invoice_customer,
                            delivery_order_customer,
                            hbl_customer,
                            is_loading,
                            temp_hbl_copies,
                            temp_commercial_invoices,
                            temp_packing_lists,
                            temp_invoice_packing_lists,
                            temp_other_commercial_docs,
                            ...otherProps
                        } = ss

                        new_suppliers.push({
                            ...otherProps
                        })
                    })
                }

                new_suppliers = JSON.stringify(new_suppliers)
                fd.append("schedules_group_bookings", this.schedules || []);
                fd.append("suppliers_group_bookings", new_suppliers || []);
                //fd.append("suppliers_group_bookings", this.suppliers || []);
                fd.append("id", this.field.shipment.id || "");
                fd.append("containers_group_bookings", this.containers || []);
                fd.append("memo_customer", this.memo_customer_model);
                fd.append("multi_purchase_orders", JSON.stringify(this.multiPurchaseOrders));

                //temporarily disable
                //fd.append('booking_confirmed', jQuery('#booking_confirmed_booking').val())

                fd.append("_token", this.token);

                const requestOptions = {
                    method: "POST",
                    body: fd
                };

                fetch(
                    `${this.field.baseUrl}/custom/bookings/save`,
                    requestOptions
                )
                .then(this.handleResponse)
                .then(response => {
                    if (typeof response !== "undefined") {
                        let { status, messages } = response;

                        if (typeof status !== "undefined") {
                            if (status == "ok") {

                                if ( typeof this.supplierDocumentsPayload.documents!=='undefined' && this.supplierDocumentsPayload.documents.length > 0 ) {
                                    this.uploadSupplierDocuments().then(() => {
                                        Nova.success(
                                            "Bookings successfully updated."
                                        );
                                        //this.$router.push('/resources/shipments')
                                        this.$router.push(
                                            `/resources/${this.resourceUrlName}/${this.resourceId}`
                                        );
                                    }).catch(e => {
                                        Nova.error(
                                            this.__(
                                                "There was a problem submitting the form."
                                            )
                                        );
                                    })
                                } else {
                                    Nova.success(
                                        "Bookings successfully updated."
                                    );
                                    //this.$router.push('/resources/shipments')
                                    this.$router.push(
                                        `/resources/${this.resourceUrlName}/${this.resourceId}`
                                    );
                                }


                                /*
                                Nova.success(
                                    "Bookings successfully updated."
                                );
                                //this.$router.push('/resources/shipments')
                                this.$router.push(
                                    `/resources/${this.resourceUrlName}/${this.resourceId}`
                                );*/
                            }
                        } else {
                            Nova.error(
                                this.__(
                                    "There was a problem submitting the form."
                                )
                            );
                            this.errors = messages;
                        }
                        this.loading = false;
                        this.buttonText = "Save";
                    }
                })
                .catch(e => {
                    Nova.error(
                        this.__(
                            "There was a problem submitting the form." +
                                JSON.stringify(e)
                        )
                    );
                    console.log(e);
                    this.errors = JSON.stringify(e);
                    this.loading = false;
                    this.buttonText = "Save";
                });
            }
            /*
            var fd = new FormData()
            fd.append('schedules_group_bookings', JSON.stringify(this.schedules))
            fd.append('suppliers_group_bookings', JSON.stringify(this.suppliers))
            fd.append('containers_group_bookings', JSON.stringify(this.containers))

            */
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text);
                return data;
            });
        },
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || "";
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            /*
            if (typeof this.schedulesGroupCopy!=='undefined' && this.schedulesGroupCopy.length > 0) {
                for (var y = 1; y< this.schedulesGroupCopy.length;y++) {

                    if (typeof schedulesGroupCopy[y].suppliers_group_bookings!=='undefined' && schedulesGroupCopy[y].suppliers_group_bookings!==null && schedulesGroupCopy[y].suppliers_group_bookings.length > 0) {
                        for (var x=0;x<this.schedulesGroupCopy[y].suppliers_group_bookings.length; x++) {
                            formData.append(`packing_list_${y}_${x}`, this.schedulesGroupCopy[y].suppliers_group_bookings[x].packing_list)
                            formData.append(`commercial_documents_${y}_${x}`, this.schedulesGroupCopy[y].suppliers_group_bookings[x].commercial_documents)
                            formData.append(`commercial_invoice_${y}_${x}`, this.schedulesGroupCopy[y].suppliers_group_bookings[x].commercial_invoice)
                        }
                    }
                }
            }*/

            let suppliersGroup = this.suppliersGroupCopy;
            if (suppliersGroup !== null && suppliersGroup.length > 0) {
                var fileContainer = [];
                for (var x = 0; x < suppliersGroup.length; x++) {
                    if (
                        typeof suppliersGroup[x].packing_list != "undefined" &&
                        suppliersGroup[x].packing_list !== null
                    )
                        formData.append(
                            `bookings_packing_list_${x + 1}`,
                            suppliersGroup[x].packing_list
                        );
                    if (
                        typeof suppliersGroup[x].commercial_documents !=
                            "undefined" &&
                        suppliersGroup[x].commercial_documents !== null
                    )
                        formData.append(
                            `bookings_commercial_documents_${x + 1}`,
                            suppliersGroup[x].commercial_documents
                        );
                    if (
                        typeof suppliersGroup[x].commercial_invoice !=
                            "undefined" &&
                        suppliersGroup[x].commercial_invoice !== null
                    )
                        formData.append(
                            `bookings_commercial_invoice_${x + 1}`,
                            suppliersGroup[x].commercial_invoice
                        );
                }
            }

            this.schedules = Array.isArray(this.schedules)
                ? JSON.stringify(this.schedules)
                : this.schedules;
            formData.append("schedules_group_bookings", this.schedules);

            //formData.append('schedules_group_bookings', this.schedules || JSON.stringify([]))

            this.suppliers = Array.isArray(this.suppliers)
                ? JSON.stringify(this.suppliers)
                : this.suppliers;
            formData.append("suppliers_group_bookings", this.suppliers);

            this.containers = Array.isArray(this.containers)
                ? JSON.stringify(this.containers)
                : this.containers;
            formData.append("containers_group_bookings", this.containers);
            formData.append("memo_customer", this.memo_customer_model);

            formData.append("multi_purchase_orders", JSON.stringify(this.multiPurchaseOrders));

            // formData.append('id', '')
            //try {
            /*
                if (typeof this.suppliersGroupCopy!=='undefined' && this.suppliersGroupCopy!==null && this.suppliersGroupCopy.length > 0) {
                    let suppliersGroup = this.suppliersGroupCopy
                    if (suppliersGroup.length > 0) {
                        for (var x = 0; x < suppliersGroup.length; x++) {
                            if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].packing_list!==null)
                                formData.append(`bookings_packing_list_${(x + 1)}`, suppliersGroup[x].packing_list);
                            if (typeof suppliersGroup[x].commercial_documents != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                                formData.append(`bookings_commercial_documents_${(x + 1)}`, suppliersGroup[x].commercial_documents);
                            if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                                formData.append(`bookings_commercial_invoice_${(x + 1)}`, suppliersGroup[x].commercial_invoice);
                        }
                    }
                }*/

            //formData.append('schedules_group_bookings', this.schedules || [])
            //formData.append('suppliers_group_bookings', this.suppliers || [])
            //fd.append('containers_group_bookings', this.containers || [])
            //fd.append('memo_customer', this.memo_customer_model)
            // }catch(e) {
            //   alert(JSON.stringify(e))
            //    console.log('error', e)
            //}

            //formData.append('memo_customer',jQuery('#memo_customer').val())
            //formData.append('booking_confirmed', jQuery('#booking_confirmed_booking').val())

            //formData.append('schedules_group', this.schedules || '')

            //formData.append('shifl_ref', this.shifl_ref)
            //let truckerValue = (this.trucker==null) ? 0 : this.trucker
            //formData.append('trucker_custom_note', this.trucker_custom_note || '')
            //formData.append('trucker', truckerValue)
            // formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            // this.value = value
        }
    }
};
</script>
