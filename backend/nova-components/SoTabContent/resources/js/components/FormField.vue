<template>
    <div>
        <div class="flex border-b border-40">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">AMS Cut Off
                </h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <date-time-picker
                    :dateFormat="'Y-m-d'"
                    :enable-seconds="false"
                    :enable-time="false"
                    :first-day-of-week="0"
                    :placeholder="'YYYY-MM-DD'"
                    @change="changedDate"
                    class="form-control form-input form-input-bordered mb-1"
                    v-model="ams_cut_off"
                />
            </div>
        </div>

        <div :key="key" v-for="(item,key) in suppliers">
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Shipping Order (SO)</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    <div v-if="!docSupplierIds.includes(item.supplier_id)" >
                        <button @click.prevent="addDocument(item,'SO Document')" class="btn btn-default btn-primary mb-2">Add
                            Document+
                        </button>
                    </div>

                    <div v-if="soDocuments.length > 0" >
                        <div :key="key" class="py-1 break-words" v-for="(docItem,key) in soDocuments">
                            <div v-if="docItem.supplier_ids.includes(item.supplier_id)">
                                <span>{{ docItem.name ? docItem.name : 'N/A' }}
                                    <button @click="openSoDocumentRemoveModal(docItem.id)" type="button" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center ml-auto" dusk="mbl_copy-internal-delete-link">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                                            <path fill-rule="nonzero"
                                                  d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z">
                                            </path>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4">

            </div>
            <div class="w-3/4 py-4 break-words">
                <button @click="e => SaveDataInfo(e)" class="btn btn-default btn-primary">Save
                </button>
            </div>
        </div>

        <modal @modal-close="closeSoDocumentModal" v-if="soDocumentRemoveModal">
            <div class="p-8 bg-white rounded-lg shadow-lg overflow-hidden">
                <heading class="mb-6">Are you sure?</heading>
                <p class="text-80 leading-normal">
                    Are you sure want to delete this document?
                </p>
            </div>
            <div class="bg-30 px-6 py-3 flex" style="margin-top: -15px;border-bottom-right-radius: .5rem;border-bottom-left-radius: .5rem;">
                <div class="ml-auto">
                    <button type="button" dusk="close-modal-button" @click.prevent="closeSoDocumentModal" class="btn btn-default font-normal h-9 px-3 mr-3 btn-link">
                        Cancel
                    </button>
                    <button type="button" @click="deleteDocumentProcess" class="btn btn-primary font-normal h-9 px-3 mr-3 btn-link">
                        <span>{{this.deleteModalText}}</span>
                    </button>
                </div>
            </div>
        </modal>

        <document-modal
            :baseUrl="field.baseUrl"
            :field.sync="field"
            :item="current_supplier"
            :resourceId="resourceId"
            :show.sync="document_modal_show"
            :supplierDocumentsPayload="supplierDocumentsPayload"
            :token="csrfToken"
            @setSupplierDocuments="setSupplierDocuments"
            @setSuppliers="setSuppliers"
            @updateSupplierGroup="updateSupplierGroupSpecial"
            :multipleAllow="false"
        >
            <template v-slot:header="{ headerProps }">
                <div class="flex w-full justify-between">
                    <h3>Upload Documents</h3>
                    <div>
                        <button @click.prevent="headerProps.close">
                            <font-awesome-icon color="#0171a1" icon="fa-solid fa-xmark"/>
                        </button>
                    </div>
                </div>
            </template>
            <template v-slot:body="{ item }">
                <div class="flex w-full justify-center items-center flex-col py-4">
                    <div class="w-full text-center">Browse Files Here</div>
                    <button @click.prevent="item.selectFile"
                            class="flex flex-row text-center justify-center items-center mt-2">
                        <font-awesome-icon color="#0171a1" icon="fa-solid fa-arrow-up"/>
                        <div class="ml-2">Upload</div>
                    </button>
                </div>
            </template>
            <template v-slot:footer="{ footerProps }">
                <div class="flex w-full flex-row">
                    <button @click.prevent="footerProps.upload(footerProps.item)" class="btn-default btn btn-primary">
                        Set Document(s)
                    </button>
                    <button @click.prevent="footerProps.close" class="ml-2 btn-default btn btn-primary">
                        Cancel
                    </button>
                </div>
            </template>
        </document-modal>
    </div>

</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'
    import _ from "lodash";
    import jQuery from 'jquery';
    import DocumentModal
        from "../../../../BookingsTabSaveGroup/resources/js/components/SuppliersGroup/Modals/DocumentModal";
    import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
    import {mapActions, mapGetters} from "vuex";
    import moment from "moment";

    export default {
        mixins: [FormField, HandlesValidationErrors],
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
            resourceUrlName: null,
            soDocumentRemoveModal: false,
            deleteReadyDocument: 0,
            deleteModalText: 'Confirm Delete',
            docSupplierIds: []
        }),
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
            this.ams_cut_off = this.field.shipment.ams_cut_off;
            let arrPath = window.location.pathname.split("/");
            this.resourceUrlName = arrPath[3];

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


        },
        methods: {
            /*
             * Set the initial, internal value for the field.
             */
            ...mapActions({
                setShipmentSupplierGroupField: 'shipment/setShipmentSupplierGroupField',
                fetchSuppliers: 'shipment/fetchShipmentSuppliers',
                uploadDocuments: 'shipment/uploadDocuments',
            }),
            openSoDocumentRemoveModal(id) {
                this.deleteReadyDocument = id;
                this.soDocumentRemoveModal = true;
            },
            closeSoDocumentModal(){
                this.deleteReadyDocument = 0;
                this.soDocumentRemoveModal = false;
            },
            deleteDocumentProcess: async function () {

                this.deleteModalText = 'Deleting...';
                const token = jQuery('meta[name="csrf-token"]').attr('content')
                const requestOptions = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ids: [this.deleteReadyDocument],
                        _token: token,
                        id: this.resourceId
                    })
                }

                fetch(`${this.field.baseUrl}/custom/delete-multiple-documents-admin`, requestOptions)
                    .then(this.handleResponse)
                    .then(response => {
                        if (response.status == 'ok') {
                            Nova.success(
                                'Document deleted successfully.'
                            )

                            this.fetchSoDocuments(this.resourceId);
                        } else {
                            Nova.error(this.__('There was a problem submitting the form.'))
                            this.errors = messages
                        }

                        this.soDocumentRemoveModal = false;
                        this.deleteModalText = 'Confirm Delete';
                    })
            },
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

                    .log(data.results);
                const suppIds = [];
                _.forEach(data.results, function(value, key) {
                    if(value.supplier_ids != undefined){
                        try {
                            let dParse =  JSON.parse(value.supplier_ids);
                            suppIds.push(parseInt(dParse[0]));
                        } catch (e) {
                            return false;
                        }
                    }
                });
                this.docSupplierIds = suppIds;
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
                console.log('set Supplier');
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

                console.log('this.suppliers', this.suppliers);
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
                if(_.has(this.supplierDocumentsPayload, 'documents')){
                    const tempSupplierDocumentsPayload = this.supplierDocumentsPayload;

                    if (typeof tempSupplierDocumentsPayload.documents !== 'undefined') {
                        tempSupplierDocumentsPayload.documents.forEach((v,k) => {
                            this.suppliers.forEach((i,j) => {
                                if(v.supplier_id.includes(i.supplier_id)){
                                    delete this.supplierDocumentsPayload.documents[k];
                                }
                            });
                        });
                    }
                }
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
                        item.loading = false
                        this.resetSuppliers()
                        this.fetchSoDocuments(this.resourceId)
                        resolve()
                    }).catch(e => {
                        item.loading = false
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
            setInitialValue() {
                this.value = this.field.value || ''
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },
            changedDate(date) {
                this.ams_cut_off = date
            },
            SaveDataInfo(e) {
                e.preventDefault();
                const token = jQuery('meta[name="csrf-token"]').attr('content')
                const requestOptions = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ams_cut_off: this.ams_cut_off,
                        _token: token,
                        id: this.resourceId
                    })
                }

                fetch(`${this.field.baseUrl}/custom/ams-cut-off/save`, requestOptions)
                    .then(this.handleResponse)
                    .then(response => {
                        Nova.success(
                            'Date successfully updated.'
                        );
                        this.$router.push(`/resources/${this.resourceUrlName}/${this.resourceId}?tab=so`)
                    })
            }
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
    }
</script>
