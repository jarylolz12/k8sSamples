<template>
    <div class="flex border-b border-40">
        <div class="w-1/4 py-4">
            <h4 class="font-normal text-80 flex">
                {{ field.name }}
            </h4>
        </div>
        <div class="w-3/4 py-4 break-words flex relative">
            <div class="field-container mr-3">
                <svg
                    v-if="hasSufficientCommercialDocs.length > 0 && hasSufficientCommercialDocs.length == suppliers.length"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    aria-labelledby="check-circle"
                    role="presentation"
                    class="fill-current text-success"
                >
                    <path
                        d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"
                    ></path>
                </svg>
                <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    aria-labelledby="x-circle"
                    role="presentation"
                    class="fill-current text-danger"
                >
                    <path
                        d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"
                    ></path>
                </svg>

            </div>
        </div>
    </div>
</template>

<script>
import jQuery from "jquery";
import _ from "lodash";
import "vue-awesome/icons";
import Icon from "vue-awesome/components/Icon";

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data: function() {
        return {
            suppliersDisplay: [],
            schedule: (typeof this.field.shipment.schedules_group_bookings!=='undefined') ? JSON.parse(this.field.shipment.schedules_group_bookings) : [],
            suppliers: (typeof this.field.shipment.suppliers_group!=='undefined') ? JSON.parse(this.field.shipment.suppliers_group) : [],
            containers: (typeof this.field.shipment.containers_group!=='undefined') ? this.field.shipment.containers_group : null,
            hasSufficientCommercialDocs: [],
        };
    },
    components: {
        'v-icon': Icon
    },
    computed: {
        token() {
            return jQuery('meta[name="csrf-token"]').attr('content')
        },
        dateDifference() {
            return parseInt(moment(this.field.shipment.created_at).diff(moment('2022-07-31'), 'days').toString())
        },
        label() {
            return 'Sufficient Commercial Docs'
        }
    },
    mounted() {
        Nova.$on('update-documents-customer', value => {
            this.getSuppliers()
        })

        //get all suppliers
        this.getSuppliers()
    },
    methods: {
        getSuppliers() {
            var formData = new FormData()
            let supplierIds = []
            let buyerIds = []
            let incoTermIds = []
            let purchaseOrderIds = []
            let containerIds = []

            if (this.suppliers.length > 0) {
                for (var x =0; x< this.suppliers.length; x++) {
                    this.suppliers[x].buyer_id !== null ? buyerIds.push(this.suppliers[x].buyer_id) : buyerIds.push(0);
                    this.suppliers[x].supplier_id !== null ? supplierIds.push(this.suppliers[x].supplier_id) : supplierIds.push(0);
                    incoTermIds.push(this.suppliers[x].incoterm_id)
                    purchaseOrderIds.push(this.suppliers[x].po_id)
                    if (typeof this.suppliers[x].containers!=='undefined' && this.suppliers[x].containers!=='') {

                        let tempContainers = []
                        tempContainers = this.suppliers[x].containers

                        if (tempContainers.length > 0) {
                            tempContainers.map( tc => {
                                containerIds.push(tc.container_id)
                            })
                        }

                    }
                }
            }

            formData.append('ids', JSON.stringify(buyerIds))
            formData.append('_token', this.token)

            fetch(`${this.field.baseUrl}/custom/buyers/where-in`,{
                token: this.token,
                method: 'POST',
                body: formData
            })
                .then(this.handleResponse)
                .then( response => {

                    var { results } = response

                    if (typeof results!=='undefined') {
                        let fetchBuyers = results

                        if ( this.suppliers.length > 0 && fetchBuyers.length > 0) {
                            for (var y=0;y<this.suppliers.length;y++) {
                                if(this.suppliers[y].buyer_id !== null && !this.suppliers[y].company_name) {
                                    let buyer_details = _.find(results, {
                                        id: this.suppliers[y].buyer_id
                                    });

                                    this.suppliers[y].company_name = !!buyer_details ? buyer_details.company_name : '';
                                }
                            }
                        }
                    }
                });

            formData.append('ids', JSON.stringify(supplierIds))
            formData.append('_token', this.token)
            formData.append('shipment_id', this.resourceId)
            fetch(`${this.field.baseUrl}/custom/suppliers/where-in-custom`,{
                token: this.token,
                method: 'POST',
                body: formData
            })
                .then(this.handleResponse)
                .then( response => {

                    let { results } = response

                    if (typeof results!=='undefined') {
                        let fetchSuppliers = results
                        if ( this.suppliers.length > 0 && fetchSuppliers.length > 0) {
                            let filteredSchedule =  this.schedule.filter(schedule => schedule.is_confirmed == true)
                            for (var y=0;y<this.suppliers.length;y++) {
                                if(this.suppliers[y].supplier_id !== null && !this.suppliers[y].company_name) {
                                    let supplier_detail = _.find(results, {
                                        id: this.suppliers[y].supplier_id
                                    })
                                    this.suppliers[y].company_name = !!supplier_detail ? supplier_detail.company_name : '';


                                    let supplier_documents = _.find(results, {
                                        id: this.suppliers[y].supplier_id,
                                    })

                                    supplier_documents = typeof supplier_documents!=='undefined' ? supplier_documents.new_documents : []

                                    this.suppliers[y].commercial_invoice_customer = []
                                    this.suppliers[y].invoice_packing_list = []
                                    this.suppliers[y].others_commercial_documents = []
                                    this.suppliers[y].packing_list_customer = []
                                    this.suppliers[y].hbl_customer =  []

                                    if ( supplier_documents.length > 0 ) {

                                        supplier_documents.map( ({ type, shipment_id }, key) => {
                                            if ( type === 'Commercial Invoice' ) {
                                                this.suppliers[y].commercial_invoice_customer.push(supplier_documents[key])
                                            } else if ( type === 'Other Commercial Docs') {
                                                this.suppliers[y].others_commercial_documents.push(supplier_documents[key])
                                            } else if ( type === 'Hbl' ) {
                                                this.suppliers[y].hbl_customer.push(supplier_documents[key])
                                            } else if ( type === 'Invoice And Packing List' ) {
                                                this.suppliers[y].invoice_packing_list.push(supplier_documents[key])
                                            } else if ( type === 'Packing List' ) {
                                                this.suppliers[y].packing_list_customer.push(supplier_documents[key])
                                            }
                                        })
                                    }
                                }

                            }
                            if(filteredSchedule.length > 0){
                                this.hasSufficientCommercialDocs = this.suppliers.filter( (supplierRaw, key) => {
                                    if(((supplierRaw.commercial_invoice != null || supplierRaw.commercial_invoice_customer.length > 0) && (supplierRaw.packing_list != null || supplierRaw.packing_list_customer.length > 0)) || supplierRaw.invoice_packing_list.length > 0){
                                        return true;
                                    } return false;
                                });
                            }

                            this.suppliersDisplay = this.suppliers
                        }
                    }
                })
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
    }
}
</script>
