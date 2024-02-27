<template>
    <div>
        <shipment-schedule-multiple-group
            :field="shipmentScheduledGroupField"
            :supplierOptions="supplierOptions"
            :incoTermValues="incoTermValues"
            :shipmentSupplierGroupField="shipmentSupplierGroupField"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :updateSupplierGroup="(suppliersGroup) => {
                this.updateSupplierGroup(suppliersGroup)
            }"
            :updateScheduleGroup="(schedulesGroup) => {
                  this.updateScheduleGroup(schedulesGroup)
            }"
        />
        <div class="flex border-b border-40" resource-id="37">
            <div class="w-1/5 px-8 py-6">
                <label for="booking_confirmed" class="inline-block text-80 pt-2 leading-tight">
                    Booking Confirmed
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <input v-model="booking_confirmed" type="checkbox" class="checkbox mt-2" id="booking_confirmed_booking" name="Booking Confirmed"> <div class="help-text help-text mt-2">
                </div>
        </div>
            </div>
            <div v-show="resourceId!='' && resourceId!=null" class="flex border-b border-40">
                <div class="w-1/5 px-8 py-6">
                </div>
                <div class="py-6 px-8 w-1/2">
                    <button v-show="loading" disabled class="btn btn-default btn-primary inline-flex items-center relative" dusk="create-button">
                        <span class="">
                            Saving...
                        </span>
                    </button>
                   <button v-show="!loading" @click="e => saveBookingsTab(e)" class="btn btn-default btn-primary inline-flex items-center relative" dusk="create-button">
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
        border:1px solid var(--danger) !important;
        border-radius: .5rem;
    }
</style>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import jQuery from 'jquery'
import _ from 'lodash'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field', 'panels'],
    data() {

        return {
            loading: false,
            loadingText: 'Saving',
            buttonText: 'Save',
            schedules: (typeof this.field.shipment.schedules_group_bookings!=='undefined') ? this.field.shipment.schedules_group_bookings : null,
            containers: (typeof this.field.shipment.containers_group_bookings!=='undefined') ? this.field.shipment.containers_group_bookings : null,
            shipmentScheduledGroupField: {
                attribute: 'schedules_group',
                name: 'Schedule Section',
                value: (typeof this.field.shipment.schedules_group_bookings!=='undefined') ? this.field.shipment.schedules_group_bookings : null
            },
            shipmentContainerGroupField: {
                attribute: 'containers_group',
                name: 'Container Section',
                value: (typeof this.field.shipment.containers_group_bookings!=='undefined') ? this.field.shipment.containers_group_bookings : null
            },
            shipmentSupplierGroupField: {
                attribute: 'suppliers_group',
                name: 'Suppliers Section',
                value: (typeof this.field.shipment.suppliers_group_bookings!=='undefined') ? this.field.shipment.suppliers_group_bookings : null
            },
            booking_confirmed: (typeof this.field.shipment.booking_confirmed!=='undefined' && this.field.shipment.booking_confirmed==1) ? true : false,
            memo_customer: {
                attribute: 'memo_customer',
                name: 'Memo',
                value: (typeof this.field.shipment.memo_customer!=='undefined') ? this.field.shipment.memo_customer : null
            },
            schedulesGroupCopy: null,
            suppliersGroupCopy: null,
            suppliers: (typeof this.field.shipment.suppliers_group_bookings!=='undefined') ? this.field.shipment.suppliers_group_bookings : null,
            supplierOptions: (typeof this.field.suppliers!=='undefined') ? this.field.suppliers : null,
            incoTermValues: (typeof this.field.incoTerms!=='undefined') ? this.field.incoTerms : null,
            errors: {
                carrier: []
            }
        }
    },
    updated() {
       // console.log(this.shipmentScheduledGroupField.value)
    },
    async mounted() {

    },
    methods: {
        updateScheduleGroup(schedulesGroup) {
            this.schedulesGroupCopy = schedulesGroup
            this.schedules = JSON.stringify(schedulesGroup)
        },
        updateContainerGroup(containerGroup) {
            this.containers = JSON.stringify(containerGroup)
        },
        updateSupplierGroup(suppliersGroup) {
            this.suppliersGroupCopy = suppliersGroup
            this.suppliers = JSON.stringify(suppliersGroup)
        },
        saveBookingsTab(e) {  
            e.preventDefault()
            
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
                        if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].packing_list!==null)
                            fd.append(`packing_list_${(x + 1)}`, suppliersGroup[x].packing_list);
                        if (typeof suppliersGroup[x].commercial_documents != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                            fd.append(`commercial_documents_${(x + 1)}`, suppliersGroup[x].commercial_documents);
                        if (typeof suppliersGroup[x].packing_list != 'undefined' && suppliersGroup[x].commercial_documents!==null)
                            fd.append(`commercial_invoice_${(x + 1)}`, suppliersGroup[x].commercial_invoice);
                    }
                }
                //schedules_group_bookings
                /*
                {
                    id: setId,
                    eta: '',
                    etd: '',
                    location_from: '',
                    location_to: '',
                    mode: '',
                    etaError: new Error(),
                    etdError: new Error(),

                    {
                id: setId,
                hbl_copy: null,
                packing_list: null,
                commercial_documents: null,
                commercial_invoice: null,
                po_num: '',
                volume: '',
                supplier_id: '',
                kg: '',
                cbm: '',
                incoterm_id: '',
                hbl_num: '',
                product_description: '',
                //total_weight: '',
                total_cartons: '',
                bl_status: 'Pending',
                ams_num: '',

            }


                } */


                fd.append('schedules_group_bookings', this.schedules || '')
                fd.append('suppliers_group_bookings', this.suppliers || '')
                fd.append('id', this.field.shipment.id || '')
                fd.append('containers_group_bookings', this.containers || '')
                fd.append('memo_customer', jQuery('#memo_customer').val())
                fd.append('booking_confirmed', jQuery('#booking_confirmed_booking').val())
                fd.append('_token',token)

                const requestOptions = {
                    method: 'POST',
                    body: fd
                }

                fetch(`${this.field.baseUrl}/custom/bookings/save`, requestOptions)
                .then(this.handleResponse)
                .then(response => {
                    let { status, messages } = response
                    if ( status == 'ok' ) {
                        Nova.success(
                            'Bookings successfully updated.'
                        )

                    } else {
                        Nova.error(this.__('There was a problem submitting the form.'))
                        this.errors = messages
                    }
                    this.loading = false
                    this.buttonText = 'Save'
                }).catch(e => {
                    Nova.error(this.__('There was a problem submitting the form.' + JSON.stringify(e)))
                    console.log(e)
                    this.errors = JSON.stringify(e)
                    this.loading = false
                    this.buttonText = 'Save'

                })
            }


        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },      
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {


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
            }

            formData.append('schedules_group_bookings', this.schedules || '')
            formData.append('memo_customer',jQuery('#memo_customer').val())
            formData.append('booking_confirmed', jQuery('#booking_confirmed_booking').val())

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
        },


    },
}
</script>
