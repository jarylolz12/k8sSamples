<template>
    <div>
        <div class="flex border-b border-40" style="display: none;">
            <div class="w-1/5 px-8 py-6">
                <label
                    for="booking_confirmed"
                    class="inline-block text-80 pt-2 leading-tight"
                >
                    Booking Confirmed
                    <!---->
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <!-- <input type="checkbox" class="checkbox mt-2" id="booking_confirmed" name="Booking Confirmed"> -->
                <input
                    v-model="bookingConfirmedModel"
                    type="checkbox"
                    :class="`checkbox mt-2`"
                    true-value="1"
                    false-value="0"
                />
            </div>
        </div>
        <shipment-schedule-group
            :field="shipmentScheduledGroupField"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :carriers="carriers"
            :vendors="vendors"
            :agents="agents"
            :types="types"
            :services="services"
            :terminalRegions="terminalRegions"
            :sizes="sizes"
            :rateSizes="rateSizes"
            @updateScheduleGroup="updateScheduleGroup"
        />
        <form-text-field
            v-if="typeof resourceId !== 'undefined' && resourceId !== null"
            :field="vessel"
            :value="vesselModel"
            v-model="vesselModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />

        <form-text-field
            v-if="typeof resourceId !== 'undefined' && resourceId !== null"
            :field="voyage_number"
            v-model="voyageNumberModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />

        <form-text-field
            v-if="typeof resourceId !== 'undefined' && resourceId !== null"
            :field="booking_number"
            v-model="bookingNumberModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />

        <form-text-field
            v-if="typeof resourceId !== 'undefined' && resourceId !== null"
            :field="mbl_num"
            v-model="mblNumModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />

        <form-text-field
            :field="mbl_shipper"
            v-model="mblShipperModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />
        <form-textarea-field
            :field="mbl_shipper_address"
            v-model="mblShipperAddressModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />
        <form-text-field
            :field="mbl_shipper_phone_number"
            v-model="mblShipperPhoneNumberModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />

        <form-text-field
            :field="mbl_consignee"
            v-model="mblConsigneeModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />
        <form-textarea-field
            :field="mbl_consignee_address"
            v-model="mblConsigneeAddressModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />
        <form-text-field
            :field="mbl_consignee_phone_number"
            v-model="mblConsigneePhoneNumberModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />

        <form-text-field
            :field="mbl_notify"
            v-model="mblNotifyModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />
        <form-textarea-field
            :field="mbl_notify_address"
            v-model="mblNotifyAddressModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />
        <form-text-field
            :field="mbl_notify_phone_number"
            v-model="mblNotifyPhoneNumberModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />
        <form-textarea-field
            :field="mbl_description"
            v-model="mblDescriptionModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />
        <form-textarea-field
            :field="mbl_marks"
            v-model="mblMarksModel"
            :resourceName="resourceName"
            :resourceId="resourceId"
        />

        <custom-file-field
            v-if="typeof resourceId !== 'undefined' && resourceId !== null"
            :field="mblCopyField"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :updateParentValue="
                (getValue, fieldName) => {
                    this.updateMblCopy(getValue, fieldName);
                }
            "
        />

        <custom-file-field
            v-if="typeof resourceId !== 'undefined' && resourceId !== null"
            :field="mblCopySurrendered"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :updateParentValue="
                (getValue, fieldName) => {
                    this.updateMblCopySurrendered(getValue, fieldName);
                }
            "
        />

        <custom-file-field
            v-if="typeof resourceId !== 'undefined' && resourceId !== null"
            :field="debitNoteField"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :updateParentValue="
                (getValue, fieldName) => {
                    this.updateDebitNote(getValue, fieldName);
                }
            "
        />
        <shipment-container-group
            v-show="resourceId != '' && resourceId != null"
            v-if="typeof resourceId !== 'undefined' && resourceId !== null"
            :field="shipmentContainerGroupField"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :sizes="sizes"
            :updateContainerGroup="
                containerGroup => {
                    this.updateContainerGroup(containerGroup);
                }
            "
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
        <shipment-supplier-group
            v-if="!getShipmentSupplierGroupField.loading && (typeof resourceId
        !=='undefined' && resourceId!==null && resourceId!=='')"
            :field="getShipmentSupplierGroupField"
            :resourceName="resourceName"
            :purchaseOrderOptions="purchaseOrderOptions"
            :supplierOptions="supplierOptions"
            :BuyerOptions="BuyerOptions"
            :incoTermValues="incoTermValues"
            :resourceId="resourceId"
            :baseUrl="field.baseUrl"
            @updateSupplierGroup="updateSupplierGroup"
            :containers="containers"
            :shipment="field.shipment"
            @setSuppliers="setSuppliers"
            :supplierDocumentsPayload="supplierDocumentsPayload"
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
                    v-show="!loading"
                    @click="e => saveDepartureNotice(e)"
                    class="btn btn-default btn-primary inline-flex items-center relative"
                    dusk="create-button"
                >
                    <span class="">
                        Save
                    </span>
                </button>
            </div>
        </div>
        <mbl-entry-modal
            v-if="showMblEntryModal"
            @close="showMblEntryModal = false"
        />
    </div>
</template>
<style type="text/css">
.border-custom-danger {
    border: 1px solid var(--danger) !important;
    border-radius: 0.5rem;
}
</style>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import jQuery from 'jquery'
import _ from 'lodash'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import MblEntryModal from '../models/MblEntryModal'
import {
    mapGetters,
    mapActions
} from 'vuex'


export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ["resourceName", "resourceId", "field", "panels"],
    components: {
        MblEntryModal,
    },
    data() {
        return {
            showMblEntryModal: false,
            current_supplier: {},
            current_document_delete_item: {},
            supplierDocumentsPayload: {},
            loading: false,
            resourceUrlName: null,
            vendors: [],
            agents: [],
            loadingText: "Saving",
            buttonText: "Save",
            services: null,
            //services: (typeof this.field.services!=='undefined') ? this.field.services : null,
            schedules:
                typeof this.field.shipment.schedules_group !== "undefined"
                    ? this.field.shipment.schedules_group
                    : null,
            suppliers:
                typeof this.field.shipment.suppliers_group !== "undefined"
                    ? this.field.shipment.suppliers_group
                    : null,
            containers:
                typeof this.field.shipment.containers_group !== "undefined"
                    ? this.field.shipment.containers_group
                    : null,
            shipmentSupplierGroupField: {
                attribute: "suppliers_group",
                name: "HBL Section",
                value:
                    typeof this.field.shipment.suppliers_group !== "undefined"
                        ? this.field.shipment.suppliers_group
                        : null,
            },
            terminalRegions: null,
            //terminalRegions: (typeof this.field.terminalRegions!=='undefined') ? this.field.terminalRegions : null,
            shipmentContainerGroupField: {
                attribute: "containers_group",
                name: "Container Section",
                value:
                    typeof this.field.shipment.containers_group !== "undefined"
                        ? this.field.shipment.containers_group
                        : null
            },
            mblCopySurrendered: {
                attribute: "mbl_copy_surrendered",
                name: "MBL Copy Surrendered/Released",
                file: "",
                value: typeof this.field.shipment.mbl_copy_surrendered !== "undefined"
                        ? this.field.shipment.mbl_copy_surrendered
                        : null
            },
            shipmentScheduledGroupField: {
                attribute: "schedules_group",
                name: "Schedule Section",
                value:
                    typeof this.field.shipment.schedules_group !== "undefined"
                        ? this.field.shipment.schedules_group
                        : null
            },
            mblNumModel:
                typeof this.field.shipment.mbl_num !== "undefined"
                    ? this.field.shipment.mbl_num
                    : null,

            mblShipperModel:
                typeof this.field.shipment.mbl_shipper !== "undefined"
                    ? this.field.shipment.mbl_shipper
                    : null,
            mblShipperAddressModel:
                typeof this.field.shipment.mbl_shipper_address !== "undefined"
                    ? this.field.shipment.mbl_shipper_address
                    : null,
            mblShipperPhoneNumberModel:
                typeof this.field.shipment.mbl_shipper_phone_number !==
                "undefined"
                    ? this.field.shipment.mbl_shipper_phone_number
                    : null,

            mblConsigneeModel:
                typeof this.field.shipment.mbl_consignee !== "undefined"
                    ? this.field.shipment.mbl_consignee
                    : null,
            mblConsigneeAddressModel:
                typeof this.field.shipment.mbl_consignee_address !== "undefined"
                    ? this.field.shipment.mbl_consignee_address
                    : null,
            mblConsigneePhoneNumberModel:
                typeof this.field.shipment.mbl_consignee_phone_number !==
                "undefined"
                    ? this.field.shipment.mbl_consignee_phone_number
                    : null,

            mblNotifyModel:
                typeof this.field.shipment.mbl_notify !== "undefined"
                    ? this.field.shipment.mbl_notify
                    : null,
            mblNotifyAddressModel:
                typeof this.field.shipment.mbl_notify_address !== "undefined"
                    ? this.field.shipment.mbl_notify_address
                    : null,
            mblNotifyPhoneNumberModel:
                typeof this.field.shipment.mbl_notify_phone_number !==
                "undefined"
                    ? this.field.shipment.mbl_notify_phone_number
                    : null,

            mblDescriptionModel:
                typeof this.field.shipment.mbl_description !== "undefined"
                    ? this.field.shipment.mbl_description
                    : null,
            mblMarksModel:
                typeof this.field.shipment.mbl_marks !== "undefined"
                    ? this.field.shipment.mbl_marks
                    : null,

            vesselModel:
                typeof this.field.shipment.vessel !== "undefined"
                    ? this.field.shipment.vessel
                    : null,
            vessel: {
                attribute: "vessel",
                name: "Vessel",
                value:
                    typeof this.field.shipment.vessel !== "undefined"
                        ? this.field.shipment.vessel
                        : null
            },
            voyageNumberModel:
                typeof this.field.shipment.vessel !== "undefined"
                    ? this.field.shipment.vessel
                    : null,
            voyage_number: {
                attribute: "voyage_number",
                name: "Voyage Number",
                value:
                    typeof this.field.shipment.voyage_number !== "undefined"
                        ? this.field.shipment.voyage_number
                        : null
            },
            bookingNumberModel:
                typeof this.field.shipment.booking_number !== "undefined"
                    ? this.field.shipment.booking_number
                    : null,
            booking_number: {
                attribute: "booking_number",
                name: "Booking Number",
                value:
                    typeof this.field.shipment.booking_number !== "undefined"
                        ? this.field.shipment.booking_number
                        : null,
                max: 30
            },
            carrierModel:
                typeof this.field.shipment.carrier_id !== "undefined"
                    ? this.field.shipment.carrier_id
                    : null,
            id: null,
            bookingConfirmedModel:
                typeof this.field.shipment.booking_confirmed !== "undefined"
                    ? this.field.shipment.booking_confirmed
                    : null,
            id: null,
            typeModel:
                typeof this.field.shipment.type !== "undefined"
                    ? this.field.shipment.type
                    : null,
            mbl_num: {
                attribute: "mbl_num",
                name: "MBL #",
                value:
                    typeof this.field.shipment.mbl_num !== "undefined"
                        ? this.field.shipment.mbl_num
                        : null
            },
            mbl_shipper: {
                attribute: "mbl_shipper",
                name: "MBL Shipper",
                value:
                    typeof this.field.shipment.mbl_shipper !== "undefined"
                        ? this.field.shipment.mbl_shipper
                        : null
            },
            mbl_shipper_address: {
                attribute: "mbl_shipper_address",
                name: "MBL Shipper's Address",
                value:
                    typeof this.field.shipment.mbl_shipper_address !==
                    "undefined"
                        ? this.field.shipment.mbl_shipper_address
                        : null
            },
            mbl_shipper_phone_number: {
                attribute: "mbl_shipper_phone_number",
                name: "MBL Shipper's Phone Number",
                value:
                    typeof this.field.shipment.mbl_shipper_phone_number !==
                    "undefined"
                        ? this.field.shipment.mbl_shipper_phone_number
                        : null
            },
            mbl_consignee: {
                attribute: "mbl_consignee",
                name: "MBL Consignee",
                value:
                    typeof this.field.shipment.mbl_consignee !== "undefined"
                        ? this.field.shipment.mbl_consignee
                        : null
            },
            mbl_consignee_address: {
                attribute: "mbl_consignee_address",
                name: "MBL Consignee's Address",
                value:
                    typeof this.field.shipment.mbl_consignee_address !==
                    "undefined"
                        ? this.field.shipment.mbl_consignee_address
                        : null
            },
            mbl_consignee_phone_number: {
                attribute: "mbl_consignee_phone_number",
                name: "MBL Consignee's Phone Number",
                value:
                    typeof this.field.shipment.mbl_consignee_phone_number !==
                    "undefined"
                        ? this.field.shipment.mbl_consignee_phone_number
                        : null
            },
            mbl_notify: {
                attribute: "mbl_notify",
                name: "MBL Notify",
                value:
                    typeof this.field.shipment.mbl_notify !== "undefined"
                        ? this.field.shipment.mbl_notify
                        : null
            },
            mbl_notify_address: {
                attribute: "mbl_notify_address",
                name: "MBL Notify's Address",
                value:
                    typeof this.field.shipment.mbl_notify_address !==
                    "undefined"
                        ? this.field.shipment.mbl_notify_address
                        : null
            },
            mbl_notify_phone_number: {
                attribute: "mbl_notify_phone_number",
                name: "MBL Notify's Phone Number",
                value:
                    typeof this.field.shipment.mbl_notify_phone_number !==
                    "undefined"
                        ? this.field.shipment.mbl_notify_phone_number
                        : null
            },
            mbl_description: {
                attribute: "mbl_description",
                name: "MBL Description",
                value:
                    typeof this.field.shipment.mbl_description !== "undefined"
                        ? this.field.shipment.mbl_description
                        : null
            },
            mbl_marks: {
                attribute: "mbl_marks",
                name: "MBL Marks",
                value:
                    typeof this.field.shipment.mbl_marks !== "undefined"
                        ? this.field.shipment.mbl_marks
                        : null
            },
            type: {
                attribute: "type",
                name: "Type",
                value:
                    typeof this.field.shipment.type !== "undefined"
                        ? this.field.shipment.type
                        : null,
                searchable: true,
                options: [
                    {
                        label: "LCL",
                        value: "LCL"
                    },
                    {
                        label: "FCL",
                        value: "FCL"
                    },
                    {
                        label: "Air",
                        value: "Air"
                    }
                ],
                placeholder: "Select a Type"
            },
            debitNoteField: {
                attribute: "debit_note",
                name: "Debit Note",
                value: this.field.shipment.debit_note
            },
            mblCopyField: {
                attribute: "mbl_copy",
                name: "MBL Copy",
                value: this.field.shipment.mbl_copy
            },
            mbl_copy_surrendered: typeof this.field.shipment.mbl_copy_surrendered !== "undefined"
                    ? this.field.shipment.mbl_copy_surrendered
                    : null,
            debit_note:
                typeof this.field.shipment.debit_note !== "undefined"
                    ? this.field.shipment.debit_note
                    : null,
            mbl_copy:
                typeof this.field.shipment.mbl_copy !== "undefined"
                    ? this.field.shipment.mbl_copy
                    : null,
            carriers: [],
            //carrierOptions: (typeof this.field.carriers!=='undefined') ? this.field.carriers : [],
            //supplierOptions: (typeof this.field.suppliers!=='undefined') ? this.field.suppliers : null,
            supplierOptions: [],
            BuyerOptions: [],
            //incoTermValues: (typeof this.field.incoTerms!=='undefined') ? this.field.incoTerms : null,
            incoTermValues: null,
            //purchaseOrderOptions: (typeof this.field.purchaseOrders!=='undefined') ? this.field.purchaseOrders : null,
            purchaseOrderOptions: [],
            suppliersGroupCopy: null,
            types:
                typeof this.field.types !== "undefined" ? this.field.types : [],
            sizes: [],
            //sizes: (typeof this.field.sizes!=='undefined') ? this.field.sizes : [],
            rateSizes:
                typeof this.field.rateSizes !== "undefined"
                    ? this.field.rateSizes
                    : [],
            errors: {
                carrier: []
            }
        };
    },
    updated() {},
    beforeDestroy() {
        Nova.$off("on-customer-shipment-select");
    },
    mounted() {

        this.setSuppliers()
        let arrPath = window.location.pathname.split("/");
        this.resourceUrlName = arrPath[3];

        Nova.$on("on-customer-shipment-select", value => {
            this.fetchCustomerSuppliers(value);
            this.fetchCustomerBuyers(value)
        });


        //set vendors
        this.setVendors()

        //set agents
        this.setAgents()

        //set terminal regions
        this.setTerminalRegions()

        //get all incoterms
        this.setIncoTerms()


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

        //get all suppliers
        // fetch(`${this.field.baseUrl}/custom/suppliers`, {
        //     token: this.token
        // })
        //     .then(this.handleResponse)
        //     .then(response => {
        //         let { results } = response;
        //         if (typeof results !== "undefined") {
        //             this.supplierOptions = results;
        //         }
        //     });

        //get all incoterms
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
                            typeof this.field.shipment.schedules_group !==
                            "undefined"
                                ? this.field.shipment.schedules_group
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
                                //e
                            }
                            this.schedules = schedules_group_bookings;
                            this.shipmentScheduledGroupField.value = schedules_group_bookings;
                        }
                    }
                }
            });

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

        //get all purchase orders
        fetch(`${this.field.baseUrl}/ `, {
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
                            typeof this.field.shipment.schedules_group !==
                            "undefined"
                                ? this.field.shipment.schedules_group
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

                            this.schedules = schedules_group_bookings;
                            this.shipmentScheduledGroupField.value = schedules_group_bookings;
                        }
                    }
                }
            });
    },
    computed: {
        ...mapGetters({
            getShipmentSupplierGroupField: 'shipmentDeparture/getShipmentSupplierGroupField'
        }),
        delete_document_modal_show: {
            set(value) {
              this.delete_document_modal_show_data = value
            },
            get() {
              return this.delete_document_modal_show_data
            }
        },
        token() {
            return jQuery('meta[name="csrf-token"]').attr("content");
        },

        document_modal_show: {
            set(value) {
              this.document_modal_show_data = value
            },
            get() {
              return this.document_modal_show_data
            }
        },
    },
    methods: {
        ...mapActions({
            setShipmentSupplierGroupField: 'shipmentDeparture/setShipmentSupplierGroupField',
            fetchSuppliers: 'shipmentDeparture/fetchShipmentSuppliers',
            uploadDocuments: 'shipmentDeparture/uploadDocuments'
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
                shipment_supplier_field,
                shipment_id: this.resourceId,
                base_url: url
            })
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
                shipment_supplier_field,
                base_url: this.field.baseUrl,
                shipment_id: this.resourceId
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
        updateType(getValue) {
            this.type.value = getValue;
        },
        updateMblCopySurrendered(getValue, getFieldName) {
            this.mbl_copy_surrendered = getValue;
        },
        updateMblCopy(getValue, getFieldName) {
            this.mbl_copy = getValue;
        },
        updateDebitNote(getValue, getFieldName) {
            this.debit_note = getValue;
        },
        updateScheduleGroup(schedulesGroup) {
            this.schedules = JSON.stringify(schedulesGroup);
        },
        updateContainerGroup(containerGroup) {
            this.containers = JSON.stringify(containerGroup);
        },
        updateSupplierGroup(suppliersGroup) {
            this.suppliersGroupCopy = suppliersGroup;
            this.suppliers = JSON.stringify(suppliersGroup);
        },
        saveDepartureNoticeBak(e) {
            e.preventDefault();
            console.log(jQuery("#vessel").val() || "");
            console.log(jQuery("#mbl_num").val() || "");
            //console.log(this.suppliersGroupCopy)
        },
        saveDepartureNotice(e) {
            e.preventDefault();
            /*
            // type is required
            if(_.isEmpty(this.type.value) || this.type.value == "null" || typeof this.type.value == "undefined"){
                console.log(this.type.value)
                    Nova.error(
                            this.__("Type field is Required")
                        );
                return
            }else {
                console.log(this.type.value)
            }*/

            if (!this.loading) {
                this.loading = true;
                this.buttonText = "Saving...";
                //const token = jQuery('meta[name="csrf-token"]').attr('content')

                var fd = new FormData();

                let suppliersGroup = this.suppliersGroupCopy;
                if (
                    typeof suppliersGroup.length !== "undefined" &&
                    suppliersGroup.length > 0
                ) {
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
                            typeof suppliersGroup[x].hbl_copy != "undefined" &&
                            suppliersGroup[x].hbl_copy !== null
                        )
                            fd.append(
                                `hbl_copy_${x + 1}`,
                                suppliersGroup[x].hbl_copy
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

                this.schedules = Array.isArray(this.schedules)
                ? JSON.stringify(this.schedules)
                : this.schedules;
                fd.append("schedules_group_bookings", this.schedules);

                //formData.append('schedules_group_bookings', this.schedules || JSON.stringify([]))
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
                            temp_hbl_copies,
                            temp_commercial_invoices,
                            temp_packing_lists,
                            temp_invoice_packing_lists,
                            temp_other_commercial_docs,
                            is_loading,
                            ...otherProps
                        } = ss

                        new_suppliers.push({
                            ...otherProps
                        })
                    })
                }

                new_suppliers = JSON.stringify(new_suppliers)

                /*this.suppliers = Array.isArray(this.suppliers)
                    ? JSON.stringify(this.suppliers)
                    : this.suppliers;*/

                fd.append("suppliers_group_bookings", new_suppliers || []);
                //fd.append("suppliers_group_bookings", this.suppliers);

                this.containers = Array.isArray(this.containers)
                    ? JSON.stringify(this.containers)
                    : this.containers;
                fd.append("containers_group_bookings", this.containers)

                //fd.append("schedules_group_bookings", this.schedules || []);
                //fd.append("suppliers_group_bookings", this.suppliers || []);
                fd.append("id", this.field.shipment.id || "");
                //fd.append("containers_group_bookings", this.containers || []);
                fd.append("memo_customer", jQuery("#memo_customer").val());
                fd.append("vessel", jQuery("#vessel").val() || "");
                fd.append("voyage_number", jQuery("#voyage_number").val() || "");
                fd.append("booking_number", jQuery("#booking_number").val() || "");
                fd.append("mbl_num", jQuery("#mbl_num").val() || "");
                /*
                fd.append(
                    "type",
                    typeof this.type.value !== "undefined"
                        ? this.type.value
                        : ""
                );*/
                fd.append("mbl_shipper", jQuery("#mbl_shipper").val() || "");
                fd.append(
                    "mbl_shipper_address",
                    jQuery("#mbl_shipper_address").val() || ""
                );
                fd.append(
                    "mbl_shipper_phone_number",
                    jQuery("#mbl_shipper_phone_number").val() || ""
                );
                fd.append(
                    "mbl_consignee",
                    jQuery("#mbl_consignee").val() || ""
                );
                fd.append(
                    "mbl_consignee_address",
                    jQuery("#mbl_consignee_address").val() || ""
                );
                fd.append(
                    "mbl_consignee_phone_number",
                    jQuery("#mbl_consignee_phone_number").val() || ""
                );
                fd.append("mbl_notify", jQuery("#mbl_notify").val() || "");
                fd.append(
                    "mbl_notify_address",
                    jQuery("#mbl_notify_address").val() || ""
                );
                fd.append(
                    "mbl_notify_phone_number",
                    jQuery("#mbl_notify_phone_number").val() || ""
                );
                fd.append("mbl_copy", this.mbl_copy);
                fd.append("mbl_copy_surrendered", this.mbl_copy_surrendered)

                fd.append(
                    "mbl_description",
                    jQuery("#mbl_description").val() || ""
                );
                fd.append("mbl_marks", jQuery("#mbl_marks").val() || "");

                fd.append("debit_note", this.debit_note);

                /*
                if ( jQuery('#remove_mbl_copy_surrendered_bl').val()=="0" ) {
                    if (jQuery('#mbl_copy_surrendered_name_bl').val()!=='') {
                        fd.append('mbl_copy_surrendered_file', document.getElementById('mbl_copy_surrendered_bl').files[0])
                        fd.append('mbl_copy_surrendered_name', jQuery('#mbl_copy_surrendered_name_bl').val())
                        fd.append('mbl_copy_surrendered', jQuery('#mbl_copy_surrendered_name_bl').val())
                    }
                } else if(jQuery('#remove_mbl_copy_surrendered_bl').val()=="1") {
                    fd.append('mbl_copy_surrendered_remove', 1)
                }*/

                //temporarily disable
                //fd.append('booking_confirmed', jQuery('#booking_confirmed_booking').val())

                fd.append("_token", this.token);

                //indicator that it is being save in the departure notice
                fd.append("departure", 1)

                const requestOptions = {
                    method: "POST",
                    body: fd
                };

                const filteredSchedule = this.field.shipment.schedules_group.filter(schedule => schedule.is_confirmed == 1);
                if(filteredSchedule.length == 0 && jQuery("#mbl_num").val() != ''){
                    this.showMblEntryModal = true;
                    this.loading = false;
                    this.buttonText = "Save";
                } else {
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
                                                `/resources/${this.resourceUrlName}/${this.resourceId}?tab=bls`
                                            );
                                        }
                                        /*
                                        Nova.success(
                                            "Bookings successfully updated."
                                        );

                                        this.$router.push(
                                            `/resources/${this.resourceUrlName}/${this.resourceId}`
                                        );*/
                                        //this.$router.push('/resources/shipments')
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

            }
            /*
            if (!this.loading) {

                this.errors = {
                    carrier: []
                }
                this.loading = true
                this.buttonText = 'Saving...'
                const token = jQuery('meta[name="csrf-token"]').attr('content')

                var fd = new FormData()

                let suppliersGroup = this.suppliersGroupCopy
                if (suppliersGroup.length > 0) {
                    var fileContainer = [];
                    for (var x = 0; x < suppliersGroup.length; x++) {
                        if (typeof suppliersGroup[x].hbl_copy != 'undefined' && suppliersGroup[x].hbl_copy!==null)
                            fd.append(`hbl_copy_${(x + 1)}`, suppliersGroup[x].hbl_copy);
                        if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].packing_list!==null)
                            fd.append(`packing_list_${(x + 1)}`, suppliersGroup[x].packing_list);
                        if (typeof suppliersGroup[x].commercial_documents != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                            fd.append(`commercial_documents_${(x + 1)}`, suppliersGroup[x].commercial_documents);
                        if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                            fd.append(`commercial_invoice_${(x + 1)}`, suppliersGroup[x].commercial_invoice);

                    }

                }

                //alert(this.carrierModel)
                //fd.append('carrier', jQuery('#carrier').val() || '')
                fd.append('carrier', this.carrierModel || '')
                fd.append('booking_confirmed', this.bookingConfirmedModel || '')
                fd.append('vessel',jQuery('#vessel').val() || '')
                fd.append('type', this.type.value || '')
                fd.append('mbl_num', jQuery('#mbl_num').val() || '')
                fd.append('schedules_group', this.schedules || '')
                fd.append('suppliers_group', this.suppliers || '')
                fd.append('containers_group', this.containers || '')
                fd.append('mbl_copy', this.mbl_copy || '')
                fd.append('debit_note', this.debit_note || '')
                fd.append('id', this.field.shipment.id || '')
                fd.append('_token',token)

                const requestOptions = {
                    method: 'POST',
                    body: fd
                }

                fetch(`${this.field.baseUrl}/custom/departure-notice/save`, requestOptions)
                .then(this.handleResponse)
                .then(response => {

                    if (typeof response!=='undefined') {
                        let { status, messages } = response
                        if ( status == 'ok' ) {
                            Nova.success(
                                'Departure Notice successfully updated.'
                            )

                        } else {
                            Nova.error(this.__('There was a problem submitting the form.'))
                            this.errors = messages
                        }
                        this.loading = false
                        this.buttonText = 'Save'
                    }

                }).catch(e => {
                    Nova.error(this.__('There was a problem submitting the form.' + JSON.stringify(e)))
                    console.log(e)
                    this.errors = JSON.stringify(e)
                    this.loading = false
                    this.buttonText = 'Save'

                })
            } */
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text);
                return data;
            });
        },
        fetchOptions(search, loading) {
            loading(true);
            const requestOptions = {
                method: "GET",
                headers: { "Content-Type": "application/json" }
            };
            const token = jQuery('meta[name="csrf-token"]').attr("content");
            fetch(
                `${this.field.baseUrl}/custom/carriers/search?search=${search}&token=${token}`,
                requestOptions
            )
                .then(this.handleResponse)
                .then(response => {
                    loading(false);
                    //  this.carrierOptions = response.results
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
            formData.append("memo_customer", jQuery("#memo_customer").val());
            formData.append("vessel", jQuery("#vessel").val() || "");

            /*
            if (typeof suppliersGroup!==null && suppliersGroup.length > 0) {
                console.log('here')
            } else {
                console.log('not here')
                console.log(suppliersGroup)
            }*/

            /*

            let suppliersGroup = this.suppliersGroupCopy
            if (suppliersGroup!==null) {

                if (suppliersGroup.length > 0) {
                    var fileContainer = [];
                    for (var x = 0; x < suppliersGroup.length; x++) {
                        if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].packing_list!==null)
                            formData.append(`bookings_packing_list_${(x + 1)}`, suppliersGroup[x].packing_list);


                        if (typeof suppliersGroup[x].hbl_copy != 'undefined' && suppliersGroup[x].hbl_copy!==null)
                            formData.append(`hbl_copy_${(x + 1)}`, suppliersGroup[x].hbl_copy);


                        if (typeof suppliersGroup[x].commercial_documents != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                            formData.append(`bookings_commercial_documents_${(x + 1)}`, suppliersGroup[x].commercial_documents);
                        if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                            formData.append(`bookings_commercial_invoice_${(x + 1)}`, suppliersGroup[x].commercial_invoice);
                    }
                }

            }


            this.schedules = (Array.isArray(this.schedules)) ? JSON.stringify(this.schedules) : this.schedules
            formData.append('schedules_group',this.schedules)

            //formData.append('schedules_group_bookings', this.schedules || JSON.stringify([]))

            this.suppliers = (Array.isArray(this.suppliers)) ? JSON.stringify(this.suppliers) : this.suppliers
            formData.append('suppliers_group', this.suppliers)

            this.containers = (Array.isArray(this.containers)) ? JSON.stringify(this.containers) : this.containers*/

            //formData.append('containers_group', this.containers)
            //formData.append('id', '')
            //formData.append('memo_customer', jQuery('#memo_customer').val())
            //formData.append('vessel', jQuery('#vessel').val() || '')
            //formData.append('mbl_num', jQuery('#mbl_num').val() || '')
            //formData.append('mbl_copy', this.mbl_copy)
            //formData.append('debit_note', this.debit_note)
            // console.log(formData)
            /*
            let suppliersGroup = this.suppliersGroupCopy
            if (suppliersGroup.length > 0) {
                var fileContainer = [];
                for (var x = 0; x < suppliersGroup.length; x++) {
                    if (typeof suppliersGroup[x].hbl_copy != 'undefined' && suppliersGroup[x].hbl_copy!==null)
                        formData.append(`hbl_copy_${(x + 1)}`, suppliersGroup[x].hbl_copy);
                    if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].packing_list!==null)
                        formData.append(`packing_list_${(x + 1)}`, suppliersGroup[x].packing_list);
                    if (typeof suppliersGroup[x].commercial_documents != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                        formData.append(`commercial_documents_${(x + 1)}`, suppliersGroup[x].commercial_documents);
                    if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                        formData.append(`commercial_invoice_${(x + 1)}`, suppliersGroup[x].commercial_invoice);

                }
            }


            //formData.append('carrier', jQuery('#carrier').val() || '')
            formData.append('carrier', this.carrierModel || '')
            formData.append('booking_confirmed', this.bookingConfirmedModel || '')
            formData.append('vessel',jQuery('#vessel').val() || '')
            formData.append('type', this.type.value || '')
            formData.append('mbl_num', jQuery('#mbl_num').val() || '')
            formData.append('schedules_group', this.schedules || '')
            formData.append('suppliers_group', this.suppliers || '')
            formData.append('mbl_copy', this.mbl_copy || '')
            formData.append('debit_note', this.debit_note || '')
            formData.append('containers_group', this.containers || '')

            */
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
