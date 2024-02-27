<template>
    <v-dialog v-model="dialog" max-width="560px" content-class="upload-documents upload-doc-dialog" v-resize="onResize" persistent>
        <v-card class="create-shipment-card upload-doc-card">
            <v-card-title>
                <span class="headline">Upload Documents</span>
                <button icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </button>
            </v-card-title>

            <v-card-text>                
                <div class="upload-documents-wrapper">
                    <!-- <div class="doc-if-empty" v-if="files == false && documentFiles.length == 0 && !dialogMessage">
                        <img src="@/assets/icons/info-blue.svg" alt="" width="20px" height="20px">
                        <p >Please upload Commercial Invoice and Packing List</p>
                    </div> -->

					<div class="red lighten-5 body-2 d-flex align-center pa-2 mb-4" v-if="dialogMessage">
                        <div class="d-flex">
                            <img src="../../../assets/icons/alert.svg" alt="" width="16px" height="16px" class="mr-3"> 
                        </div>
                        <span style="color: #4a4a4a;">Please upload the Commercial Invoice and the Packing List for each of the supplier.</span>
                    </div>

                    <div class="upload-documents">
                        <div>
                            <div id="upload-documents-select-box-id"  class="upload-documents-select-box" 
                                @click.stop="addDocuments">
                                <span>Browse files here</span>

                                <button class="btn-white" @click.stop="addDocuments">
                                    <img src="@/assets/icons/upload.svg" width="16px" height="16px">
                                    Upload
                                </button>
                            </div>
                            <input 
                                ref="upload_documents_reference" 
                                type="file" 
                                id="upload_documents" 
                                @change="(e) => inputChanged(e)" 
                                name="upload_documents"
                                accept=""
                                multiple />
                        </div>

                        <div class="document-lists" v-if="documentFiles.length > 0">
                            <div class="lists-items" v-for="(documentFile, index) in documentFiles" :key="`d-files-${index}`">
                                <div class="items">
                                    <img src="@/assets/icons/shipment-document-blue.svg" width="45px" height="45px" class="mr-2" v-if="!isMobile">
                                    <div>
                                        <div :class="`document-info ${((documentFile.nameError && (documentFile.name === '' || documentFile.name === null)) || documentFile.fileError) ? 'div-has-error' : ''}`">
                                            <div>
                                                <img src="@/assets/icons/shipment-document-blue.svg" width="45px" height="45px" class="mr-2" v-if="isMobile">
                                                <input v-model="documentFile.name" />
                                            </div>
                                            <div v-if="!isMobile">
                                                <div class="file-size-info">
                                                    <span>[ {{ getFileSize(documentFile.file.size) }} * {{ documentFile.file.name.split('.').pop() }} ]</span>
                                                </div>
                                                <a href="#" class="file-remove" @click="removeFile(index)">
                                                    <img src="@/assets/icons/close-red.svg" width="16px" height="16px">
                                                </a>
                                            </div>
                                        </div>

                                        <div class="document-info-below">
                                            <div class="document-info-content first-content">
                                                <span class="document-supplier-title" v-if="isMobile">Type</span>
                                                <div class="document-info-content">
                                                    <div :class="(documentFile.typeError && (documentFile.type === '' || documentFile.type === null)) ? 'has-error w-100' : 'w-100'">
                                                        <v-select
                                                            class="select-product"
                                                            :class="type !== '' ? 'has-value' : ''"
                                                            :items="!dialogMessage ? documentTypes : documentTypesToDos"
                                                            item-text="name"
                                                            item-value="name"
                                                            placeholder="Select Type"
                                                            outlined
                                                            v-model="documentFile.type"
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
                                            </div>

                                            <div class="document-info-content first-content" 
                                                v-if="documentFile.type !== '' && 
                                                documentFile.type !== 'Other' && 
                                                documentFile.type !== 'Delivery Order'">

                                                <span class="document-supplier-title">Supplier</span>
                                                <div class="document-info-content">
                                                    <div :class="(documentFile.supplierError && (documentFile.supplier_id.length === 0)) ? 'has-error w-100' : 'w-100'">
                                                        <v-select
                                                            class="select-product"
                                                            :class="documentFile.supplier_id.length > 0 ? 'has-value' : ''"
                                                            :items="supplierOptions"
                                                            item-text="name"
                                                            item-value="id"
                                                            return-object
                                                            placeholder="Select Supplier"
                                                            outlined
                                                            v-model="documentFile.supplier_id"
                                                            hide-details="auto"
                                                            :menu-props="{ contentClass: 'supplier-lists-contents', bottom: true, offsetY: true }">
                                                            <!-- multiple -->

                                                            <!-- <template v-slot:selection="{ item, index }">
                                                                <span v-if="index === 0 && documentFile.supplier_id.length === 1">
                                                                    {{ item.name }}
                                                                </span>

                                                                <span v-if="index === 0 && documentFile.supplier_id.length > 1" 
                                                                    style="color: #4a4a4a;">
                                                                    Multiple Suppliers
                                                                </span>
                                                            </template>
                                                            <template v-slot:item="{ item, attrs, on }">
                                                                <v-list-item v-on="on" v-bind="attrs" #default="{ active }">
                                                                    <v-list-item-action>
                                                                        <v-checkbox :input-value="active"></v-checkbox>
                                                                    </v-list-item-action>

                                                                    <v-list-item-content>
                                                                        <v-list-item-title>
                                                                            <v-row no-gutters align="center">
                                                                                <span>{{ item.name }}</span>
                                                                            </v-row>
                                                                        </v-list-item-title>
                                                                    </v-list-item-content>
                                                                </v-list-item>
                                                            </template> -->
                                                        </v-select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="isMobile" class="document-file-size-wrapper">
                                                <div class="file-size-info">
                                                    <span>[ {{ getFileSize(documentFile.file.size) }} * {{ documentFile.file.name.split('.').pop() }} ]</span>
                                                </div>
                                                <a href="#" class="file-remove" @click="removeFile(index)">
                                                    <img src="@/assets/icons/close-red.svg" width="16px" height="16px">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </v-card-text>

            <v-card-actions v-if="files.length !== 0">
                <v-btn class="btn-blue" text @click="confirmUpload" 
                    :disabled="files.length == 0 || getShipmentDocumentsUploadLoading">
                    <!-- IF NOT -->
                    {{ getShipmentDocumentsUploadLoading ? 'Uploading...' : 'Confirm Upload' }}

                    <!-- IF LOADING -->
                    <!-- Uploading (percentage here)% -->
                </v-btn>
                <v-btn class="btn-white" text @click="close" v-if="!isMobile" :disabled="getShipmentDocumentsUploadLoading">
                    Cancel
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import globalMethods from '../../../utils/globalMethods'
import { mapActions, mapGetters } from "vuex"

export default {
    name: 'UploadDocumentsDialog',
    props: ['editedItemData', 'dialogData', 'documentsData', 'shipment_id','dialogMessage'],
    components: { },
    data: () => ({
        isMobile: false,
        files: [],
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
                name: 'Invoice and Packing List'
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
        documentTypesToDos: [
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
                name: 'Invoice and Packing List'
            },
            {
                id: 3,
                name: 'OBL Document'
            },
            {
                id: 4,
                name: 'Other Commercial Docs'
            },
        ],
        selected_tags: [],
        type: ''
    }),
    computed: {
        ...mapGetters([
            'getShipmentDocumentsUploadLoading',
            'getShipmentSuppliers'
        ]),
        supplierOptions() {
            return (typeof this.getShipmentSuppliers !== 'undefined') ? this.getShipmentSuppliers : []
        },
        formTitle () {
            return this.editedIndexData === -1 ? 'Create Shipment' : 'Edit Shipment'
        },
        dialog: {
            get () {
                return this.dialogData
            },
            set (value) {
                if (!value) {
                    this.$emit('update:dialogData', false)
                } else {
                    this.$emit('update:dialogData', true)
                }
            }
        },
    },
    watch: {
        // selected_tags(value) {
        //     console.log(value);

        //     if (value.length > 1) {

        //     }
        // }
    },
    mounted() {},
    methods: {
        ...globalMethods,
        ...mapActions([
            'uploadShipmentDocuments',
            'fetchShipmentDetails'
        ]),
        close() {
            this.$emit('close')
            this.files = []
            this.$refs.upload_documents_reference.value = ''
            this.type = ''
            this.selected_tags = []
            this.documentFiles = []
        },
        onResize() {
            if (window.innerWidth < 768) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },
        addDocuments() {
            this.$refs.upload_documents_reference.click()
        },
        inputChanged() {
            let files = this.$refs.upload_documents_reference.files;
            
            if (!files.length) {
                return false;
            }

            for (let i = 0; i < files.length; i++) {
                this.documentFiles.push({
                    file: files[i],
                    supplier_id: [],
                    type: '',
                    name: files[i].name,
                    typeError: false,
                    supplierError: false,
                    nameError: false,
                    fileError: false
                })
                this.files.push(files[i])
            }
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
        removeFile(index) {
            this.documentFiles.splice(index, 1)
            this.files.splice(index, 1)
        },
        async confirmUpload() {
            let {
                shipment_id
            } = this
            let finalDocuments = this.documentFiles
            if ( finalDocuments.length > 0 ) {
                
                let finalData = {
                    documents: finalDocuments,
                    shipment_id
                }

                try {
                    finalData.documents.map(v => {
                        if (v.type === 'Delivery Order' || v.type === 'Other') {
                            v.supplier_id = []
                        } 
                        
                        if (v.type === 'OBL Document') {
                            v.type = 'hbl'
                        }
                    })

                    await this.uploadShipmentDocuments(finalData)
                    
                    this.$emit('fetchDocuments', {
                        shipment_id,
                        page: 1
                    })
                    this.fetchShipmentDetails(shipment_id)
                    this.close()
                    this.notificationMessageCustomSuccess('Documents have successfully been uploaded. Reloading page to reset shipment\'s data.')

                } catch(e) {
                    if ( typeof e == 'object' ) {
                        let getKeys = Object.keys(e)
                        this.documentFiles.map((df,k) => {
                            this.documentFiles[k].typeError = false
                            this.documentFiles[k].supplierError = false
                            this.documentFiles[k].nameError = false
                            this.documentFiles[k].fileError = false
                        })

                        getKeys.map((gk) => {
                            let getKey = e[gk][0].split('.')[2].split(' ')[0]
                            if ( getKey === 'supplier_id' ) {
                                this.documentFiles[parseInt(e[gk][0].split('.')[1])].supplierError = true
                            }

                            if ( getKey === 'type' ) {
                                this.documentFiles[parseInt(e[gk][0].split('.')[1])].typeError = true
                            }

                            if ( getKey === 'name' ) {
                                this.documentFiles[parseInt(e[gk][0].split('.')[1])].nameError = true
                            }

                            if ( getKey === 'file' ) {
                                this.documentFiles[parseInt(e[gk][0].split('.')[1])].fileError = true
                            }
                        })
                    } else {
                        //errorMessageDisplay = e
                    }
                }

                // try {
                //     await this.uploadShipmentDocuments(finalData)
                //     this.close()
                //     this.$emit('fetchDocuments',{
                //         shipment_id
                //     })
                //     this.notificationMessageCustomSuccess('Documents have successfully been uploaded.')
                // } catch(e) {
                //     //let errorMessages = []
                //     //let errorMessageDisplay = ''
                //     if ( typeof e== 'object' ) {
                //         let getKeys = Object.keys(e)
                //         this.documentFiles.map((df,k) => {
                //             this.documentFiles[k].typeError = false
                //             this.documentFiles[k].supplierError = false
                //             this.documentFiles[k].nameError = false
                //             this.documentFiles[k].fileError = false
                //         })

                //         getKeys.map((gk) => {

                //             let getKey = e[gk][0].split('.')[2].split(' ')[0]
                //             if ( getKey=== 'supplier_id' ) {
                //                 this.documentFiles[parseInt(e[gk][0].split('.')[1])].supplierError = true
                //             }

                //             if ( getKey === 'type' ) {
                //                 this.documentFiles[parseInt(e[gk][0].split('.')[1])].typeError = true
                //             }

                //             if ( getKey === 'name' ) {
                //                 this.documentFiles[parseInt(e[gk][0].split('.')[1])].nameError = true
                //             }

                //             if ( getKey === 'file' ) {
                //                 this.documentFiles[parseInt(e[gk][0].split('.')[1])].fileError = true
                //             }


                //            // errorMessages.push(`Field ${parseInt(e[gk][0].split('.')[1]) + 1}: ` + e[gk][0].split('.')[2])
                //         })
                //         //errorMessageDisplay = errorMessages.join('<br/>')
                //     } else {
                //         //errorMessageDisplay = e
                //     }
                //     //this.notificationError(`An error occured while trying to upload the document(s): <br/> ${errorMessageDisplay}`)
                // }
            }
        }
    },
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/shipment/shipmentDocumentsUpload.scss';
</style>