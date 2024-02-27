<template>
    <div>
        <div v-if="displaySchedules.length > 0">
            <div style="border-bottom: 1px solid #c8c8c8;" :key="key"  v-for="(item,key) in displaySchedules">
                <div class="flex border-b border-40">
                    <div class="w-1/4 py-4">
                        <h4 class="font-normal text-80">Container Cut Off</h4>
                    </div>
                    <div class="w-3/4 py-4 break-words">
                        {{ formatDate(item.cutoff) }}
                    </div>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-1/4 py-4">
                        <h4 class="font-normal text-80">Region From</h4>
                    </div>
                    <div class="w-3/4 py-4 break-words">
                        {{ item.location_from_name ? item.location_from_name :'No region inputted in schedule selected'}}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex border-b border-40" v-else>
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">There is no selected schedule yet on this shipment. Please see booking tab.</h4>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">AMS Cut Off</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                {{formatDate(ams_cut_off)}}
            </div>
        </div>
        <div>
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Shipping Order (SO)</h4>
                </div>
                <div>
                    <p :key="key" class="py-1 break-words" v-for="(item,key) in soDocuments">
                            <span>{{ item.name ? item.name : 'N/A' }}
                                <span style="display: inline-block; position: relative; top: 2px;">
                                    <a :href="`/custom/download?link=${encodeURIComponent(item.path)}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
                                             class="fill-current mr-2">
                                            <path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z">
                                            </path>
                                        </svg>
                                        <span class="class flex items-center">Download</span>
                                    </a>
                                </span>
                            </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import jQuery from 'jquery';
    import moment from 'moment';
    import axios from 'axios';
    import _ from 'lodash'
    import {mapGetters, mapActions} from 'vuex'

    import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

    import DocumentModal
        from "../../../../BookingsTabSaveGroup/resources/js/components/SuppliersGroup/Modals/DocumentModal";

    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field'],
        components: {DocumentModal, FontAwesomeIcon},
        data: () => ({
            schedules: [],
            suppliers: [],
            current_supplier: {},
            loading: true,
            supplierDocumentsPayload: {},
            soDocuments: [],
            ams_cut_off: null,
            document_modal_show_data: false,
            displaySchedules: [],
        }),
        methods: {
            ...mapActions({
                setShipmentSupplierGroupField: 'shipment/setShipmentSupplierGroupField',
                fetchSuppliers: 'shipment/fetchShipmentSuppliers',
                uploadDocuments: 'shipment/uploadDocuments',
            }),
            addDocument(item, type) {
                item.special_type = type
                this.document_modal_show = true
                this.current_supplier = item
            },
            fetchSoDocuments: async function (shipmentID) {
                const {data} = await Nova.request().get(
                    `/custom/so-documents/${shipmentID}`
                );

                this.soDocuments = data.results
            },
            formatDate(date) {
                return date ? moment(date).format('YYYY-MM-DD') : 'N/A'
            },
            handleResponse(response) {
                return response.text().then(text => {
                    const data = text && JSON.parse(text)
                    return data
                })
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
            updateSupplierGroupSpecial(suppliersGroup) {
                const tempMultiPurchaseOrders = []
                for (const supplier of suppliersGroup) {
                    if (supplier.purchaseOrders) {
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
                console.log("payload", payload)
                console.log("payload.documents", payload.documents)
                if (typeof this.supplierDocumentsPayload.documents !== 'undefined') {

                    if (typeof payload.documents !== 'undefined') {
                        payload.documents.map(pd => {
                            let findDocumentIndex = _.findIndex(this.supplierDocumentsPayload.documents, {
                                supplier_temp_id: pd.supplier_temp_id,
                                type: pd.type
                            })
                            if (findDocumentIndex == -1) {
                                this.supplierDocumentsPayload.documents.push(pd)
                            } else {
                                this.supplierDocumentsPayload.documents[findDocumentIndex] = pd
                            }
                        })
                    }

                } else {
                    this.supplierDocumentsPayload = payload
                }


                return new Promise((resolve, reject) => {
                    let {
                        documents,
                        resourceId,
                        item,
                        url
                    } = this.supplierDocumentsPayload
                    item.is_loading = true
                    documents.map((df, k) => {
                        documents[k].typeError = false
                        documents[k].supplierError = false
                        documents[k].nameError = false
                        documents[k].fileError = false
                    })

                    this.uploadDocuments({
                        documents,
                        resourceId,
                        url: ''
                    }).then(() => {
                        item.is_loading = false
                        this.resetSuppliers()
                        this.fetchSoDocuments(this.resourceId)
                        resolve()
                    }).catch(e => {
                        item.is_loading = false
                        if (typeof e !== 'undefined') {
                            let error_messages = []
                            let error_message = ''

                            if (typeof e !== 'undefined' && typeof e.errors !== 'undefined') {

                                let getKeys = Object.keys(e.errors)

                                documents.map((df, k) => {
                                    documents[k].typeError = false
                                    documents[k].supplierError = false
                                    documents[k].nameError = false
                                    documents[k].fileError = false
                                })

                                getKeys.map(gk => {
                                    let splitKeys = gk.split('.')
                                    if (splitKeys[2] === 'file') {
                                        documents[parseInt(splitKeys[1])].fileError = true
                                    } else if (splitKeys[2] === 'type') {
                                        documents[parseInt(splitKeys[1])].typeError = true
                                    } else if (splitKeys[2] === 'name') {
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

        },
        computed: {
            ...mapGetters({
                getShipmentSupplierGroupField: 'shipment/getShipmentSupplierGroupField'
            }),
            csrfToken() {
                return jQuery('meta[name="csrf-token"]').attr('content');
            },
            document_modal_show: {
                get() {
                    return this.document_modal_show_data;
                },
                set(value) {
                    this.document_modal_show_data = value
                }
            },
        },
        mounted() {

            this.fetchSoDocuments(this.resourceId)
            this.ams_cut_off = this.field.shipment.ams_cut_off

            this.schedules = (typeof this.field.shipment.schedules_group_bookings !== 'undefined') ? JSON.parse(this.field.shipment.schedules_group_bookings) : []
            this.suppliers = (typeof this.field.shipment.suppliers_group_bookings !== 'undefined') ? JSON.parse(this.field.shipment.suppliers_group_bookings) : []

            this.setSuppliers()

            let scheduleIds = [];

            if (this.schedules.length > 0) {
                this.schedules.map(async (schedule, key) => {
                    if (typeof schedule.location_from !== 'undefined' && scheduleIds.indexOf(schedule.location_from) == -1) {
                        scheduleIds.push(schedule.location_from)
                    }
                })
            }

            if (this.schedules.length > 0) {
                this.displaySchedules = this.schedules.filter(schedule => schedule.is_confirmed == 1);
            }

            let tt = setInterval(() => {

                if (scheduleIds.length > 0) {
                    var fd = new FormData()
                    fd.append('_token', this.csrfToken)
                    fd.append('ids', JSON.stringify(scheduleIds))

                    //start
                    //get all terminal regions
                    fetch(`${this.field.baseUrl}/custom/terminal-regions/where-in`, {
                        body: fd,
                        method: 'POST'
                    }).then(this.handleResponse)
                        .then(response => {
                            let {results} = response
                            if (typeof results !== 'undefined') {

                                if (this.schedules.length > 0) {
                                    if (this.schedules && this.schedules.length > 0) {
                                        for (let key = 0; key < this.schedules.length; key++) {
                                            if (this.schedules[key].legs && this.schedules[key].legs.length > 0) {
                                                for (let keySecond = 0; keySecond < this.schedules[key].legs.length; keySecond++) {
                                                    if (this.schedules[key].legs[keySecond].location_from) continue;
                                                    this.schedules[key].legs[keySecond].location_from = (keySecond == 0) ? this.schedules[key].location_to : this.schedules[key].legs[keySecond - 1].location_to
                                                }
                                            }
                                        }
                                    }
                                    this.schedules.map((schedule, key) => {

                                        if (results.length > 0) {

                                            results.map(s => {
                                                if (s.id == schedule.location_from) {
                                                    this.schedules[key].location_from_name = s.name
                                                }

                                                if (s.id == schedule.location_to) {
                                                    this.schedules[key].location_to_name = s.name
                                                }

                                            })

                                            if (typeof schedule.legs !== 'undefined' && schedule.legs !== '') {
                                                schedule.legs.map((l, kk) => {

                                                    results.map(s => {
                                                        if (s.id == l.location_to) {
                                                            this.schedules[key].legs[kk].location_to_name = s.name
                                                        }
                                                        if (s.id == l.location_from) {
                                                            this.schedules[key].legs[kk].location_from_name = s.name
                                                        }
                                                    })
                                                })
                                            }

                                        }
                                    })
                                }
                            }
                        })

                    //end
                    clearInterval(tt)
                } else {
                    this.loading = false
                    clearInterval(tt)
                }
            }, 100)


        }
    }
</script>
