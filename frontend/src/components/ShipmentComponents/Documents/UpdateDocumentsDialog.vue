<template>
    <v-dialog v-model="dialog" max-width="560px" content-class="update-documents update-doc-dialog" v-resize="onResize" persistent>
        <v-card class="update-document">
            <v-card-title>
                <span class="headline">{{ formTitle }}</span>
                <button icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </button>
            </v-card-title>
            
            <v-card-text>
                <div class="update-documents-wrapper">
                    <div class="document-lists">
                        <div class="lists-items">
                            <div class="items">
                                <img src="@/assets/icons/shipment-document-blue.svg" width="45px" height="45px" class="mr-2" v-if="!isMobile">
                                <div>
                                    <div class="document-info">
                                        <div>
                                            <img src="@/assets/icons/shipment-document-blue.svg" width="45px" height="45px" class="mr-2" v-if="isMobile">
                                            <input v-model="documentItem.name" />
                                        </div>
                                        <div v-if="1==2">
                                            <div class="file-size-info">
                                                <span>[ 8 kb * pdf ]</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="document-info-below">
                                        <div class="document-info-content first-content">
                                            <span class="document-supplier-title" v-if="isMobile">Type</span>
                                            <div class="document-info-content">
                                                <v-select
                                                    class="select-product"
                                                    item-text="name"
                                                    :items="documentTypes"
                                                    item-value="name"
                                                    placeholder="Select Type"
                                                    outlined
                                                    v-model="documentItem.type"
                                                    hide-details="auto"
                                                    :menu-props="{ contentClass: 'supplier-lists-contents types fixedWidth', bottom: true, offsetY: true }">

                                                    <template v-slot:item="{ item }">
                                                        <div class="option-items">
                                                            <span>{{ item.name }}</span>

                                                            <img src="@/assets/icons/check-blue.svg" alt="">
                                                        </div>
                                                    </template>
                                                </v-select>
                                            </div>
                                        </div>

                                        <div class="document-info-content first-content"
                                            v-if="documentItem.type !== '' && documentItem.type !== 'Other' && documentItem.type !== 'Delivery Order'">
                                            <span class="document-supplier-title">Supplier</span>
                                            <div class="document-info-content">
                                                <v-select
                                                    class="select-product"
                                                    item-text="name"
                                                    item-value="id"
                                                    return-object
                                                    placeholder="Select Supplier"
                                                    outlined
                                                    :items="supplierOptions"
                                                    v-model="documentItem.supplier_ids"
                                                    hide-details="auto"
                                                    :menu-props="{ contentClass: 'supplier-lists-contents', bottom: true, offsetY: true }">

                                                    <!-- <template v-slot:selection="{ item, index }">
                                                        <span class="document-supplier-label" v-if="index === 0 && documentItem.supplier_ids.length === 1">
                                                            {{ item.name }}
                                                        </span>
                                                        <span v-if="index === 0 && documentItem.supplier_ids.length > 1" 
                                                            style="color: #4a4a4a;">
                                                            Multiple Suppliers
                                                        </span>
                                                    </template>
                                                    <template>
                                                        <v-list-item>
                                                            <v-list-item-action>
                                                                <v-checkbox></v-checkbox>
                                                            </v-list-item-action>

                                                            <v-list-item-content>
                                                                <v-list-item-title>
                                                                    <v-row no-gutters align="center">
                                                                        <span>static file name</span>
                                                                    </v-row>
                                                                </v-list-item-title>
                                                            </v-list-item-content>
                                                        </v-list-item>
                                                    </template> -->
                                                </v-select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </v-card-text>

            <v-card-actions>
                <v-btn class="btn-blue" @click="updateDocument" :disabled="getUpdateLoading">
                    {{ (getUpdateLoading) ? 'Updating...' : 'Update' }}
                </v-btn>
                <v-btn class="btn-white" @click="close" :disabled="getUpdateLoading">
                    Cancel
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import globalMethods from '../../../utils/globalMethods'

export default {
    name: 'UploadDocumentsDialog',
    props: ['dialogData', 'documentsData', 'shipment_id', 'editItem'],
    components: { },
    data: () => ({
        isMobile: false,
        documentFiles: [],
        documentTypes: [
            {
                id: 0,
                name: 'Commercial Invoice'
            },
            {
                id: 1,
                name: 'Packing List'
            },
            {
                id: 2,
                name: 'Invoice And Packing List'
            },
            {
                id: 3,
                name: 'OBL Document'
            },
            {
                id: 4,
                name: 'Other Commercial Docs'
            },
            {
                id: 5,
                name: 'Delivery Order'
            },
            {
                id: 6,
                name: 'Other'
            },
        ],
    }),
    computed: {
        ...mapGetters({
            getEditDocument: 'documents/getEditDocument',
            getUpdateLoading: 'documents/getUpdateLoading',
            getEditId: 'documents/getEditId',
            getShipmentSuppliers: 'getShipmentSuppliers'
        }),
        documentItem: {
            get() {
                let value = this.editItem

                if (value.type !== '' && (value.type === 'Hbl' || value.type === 'HBL' || value.type === 'hbl')) {
                    value.type = 'OBL Document'
                }

                return this.editItem
            },
            set(value) {
                this.$emit('update:editItem', value)
            }
        },
        formTitle () {
            return 'Update Document'
        },
        supplierOptions() {
            return (typeof this.getShipmentSuppliers !== 'undefined') ? this.getShipmentSuppliers : []
        },
        dialog: {
            get () {
                return this.dialogData
            },
        },
    },
    methods: {
        ...mapActions({
            updateNameType: 'documents/updateNameType',
            setEditItem: 'documents/setEditDocument'
        }),
        ...globalMethods,
        onResize() {
            if (window.innerWidth < 768) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },
        async updateDocument() {
            let { id, name, type, supplier_ids } = this.documentItem
            let { shipment_id } = this

            if (type === 'Delivery Order' || type === 'Other') {
                supplier_ids = []
            }

            if (type === 'OBL Document') {
                type = 'hbl'
            }

            await this.updateNameType({
                document_id: id,
                name,
                type,
                supplier_ids
            })

            this.notificationMessageCustomSuccess('The document was successfully updated.')
            this.$emit('fetchDocuments', {
                shipment_id
            })
            this.setEditItem({})
            this.close()
        },
        getFileSize(size) {
            let base = 1024
            let decimal = 2

            var kiloBytes = base
            var megaBytes = base * base
            var gigaBytes = base * base * base

            if(size < kiloBytes) {
                return size + " Bytes"
            }
            else if(size < megaBytes) {
                return (size / kiloBytes).toFixed(decimal) + " kb"
            } 
            else if(size < gigaBytes) {
                return (size / megaBytes).toFixed(decimal) + " mb"
            }
        },
        close() {
            this.$emit('close')
            this.documentFiles = []
            this.files = []
            //this.$refs.upload_documents_reference.value = ''
            this.type = ''
            this.selected_tags = []
        },
    },
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/shipment/shipmentDocumentsUpdate.scss';
</style>
