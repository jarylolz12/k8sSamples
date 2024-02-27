<template>
    <v-dialog v-model="dialog" max-width="560px" persistent content-class="upload-documents" v-resize="onResize">
        <v-card class="create-shipment-card">
            <v-card-title>
                <span class="headline">Upload Documents</span>

                <button icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </button>
            </v-card-title>

            <v-card-text>
                <div class="upload-documents-wrapper">
                    <div class="upload-documents">
                        <div>
                            <div id="upload-documents-select-box-id"  class="upload-documents-select-box" 
                                @click.stop="addDocuments">
                                <span>Browse Files here</span>

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
                                name="documents[]"
                                accept=".pdf,.docx"
                                multiple />
                        </div>

                        <div class="document-lists" v-if="files !== null && files.length !== 'undefined' && files.length > 0">
                            <div class="lists-items" v-for="(file, index) in files" :key="index">
                                <div class="items">
                                    <img src="@/assets/icons/shipment-document-icon-blue.svg" width="45px" height="45px" class="mr-2">

                                    <div class="filename-size-wrapper">
                                        <span class="file-name"> {{ getNameWithoutExt(file.name) }} </span>
                                        
                                        <div class="file-size-info">
                                            [<span> {{ getFileSize(file.size) }}</span>
                                            <div class="separator"></div>
                                            <span>{{ getFileType(file.name) }} </span>]

                                            <button class="btn-white btn-no-border" @click="removeItem(index)">
                                                <img src="@/assets/icons/delete-po.svg" class="ml-3" width="16px" height="16px">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </v-card-text>

            <v-card-actions>
                <v-btn class="btn-blue" text @click="uploadDocumentFiles" :disabled="isUploading || files.length === 0">                   
                    <span v-if="!isUploading">Upload</span>
                    <span v-if="isUploading">Uploading {{ this.progress }}%...</span>
                </v-btn>

                <v-btn class="btn-white" text @click="close" v-if="!isMobile" :disabled="isUploading">
                    Cancel
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import globalMethods from '../../../../utils/globalMethods'
import axios from 'axios'

export default {
    name: 'UploadInboundDialog',
    props: ['editedItemData', 'dialogData', 'linkData'],
    components: { },
    data: () => ({
        isMobile: false,
        files: [],
        progress: 0,
        isUploading: false
    }),
    computed: {
        ...mapGetters({
            poBaseUrlState: 'products/poBaseUrlState',
            getUploadDocLoading: 'inbound/getUploadDocLoading',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
        }),
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
        dialog(val) {
            if (val) {
                setTimeout(() => {
                    this.$refs.upload_documents_reference.click()
                }, 200);
            }                
        }
    },
    mounted() {
    },
    methods: {
        ...mapActions({
            uploadInboundDocs: 'inbound/uploadInboundDocs',
            fetchSingleInbound: 'inbound/fetchSingleInbound',
            fetchPendingInbounds: 'inbound/fetchPendingInbounds',
            fetchFloorInbounds: 'inbound/fetchFloorInbounds',
        }),
        ...globalMethods,
        close() {
            this.files = []
            this.$refs.upload_documents_reference.value = ''
            this.$emit('close')
        },
        onResize() {
            if (window.innerWidth < 769) {
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
        getFileType(type) {
            if (type !== null && type !== '') {
                // return type.replace(/(.*)\//g, '')
                return type.substr(type.lastIndexOf('.') + 1);
            }
        },
        getNameWithoutExt(name) {
            if (name !== null && name !== '') {
                let filter = name.lastIndexOf('.');
                return name.substring(0, filter)
            } else {
                return '--'
            }
        },
        removeItem(index) {
            if (index !== null) {
                this.files.splice(index, 1)
            }
        },
        async callPendingOrFloorInbound() {
            let dataWithPage = { id: this.linkData.warehouse_id, page: 1 }

            let storePagination = this.getCurrentInboundTab === 0 ? 
                this.$store.state.inbound.pendingInboundPagination : 
                (this.getCurrentInboundTab === 1 ? this.$store.state.inbound.floorInboundPagination : 1)
                
            let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
            dataWithPage.page = page

            if (this.inboundStatus === 'pending' || this.inboundStatus === 'arrived') {
                await this.fetchPendingInbounds(dataWithPage)
            } else if (this.inboundStatus === 'floor') {
                await this.fetchFloorInbounds(dataWithPage)
            }
        },
        async uploadDocumentFiles() {
            if (this.files.length !== 0) {
                this.progress = 0
                let singlePayload = { wid: this.linkData.warehouse_id, iid: this.linkData.inbound_id }
                this.isUploading = true

                let bodyFormData = new FormData()
				for (var i = 0; i < this.files.length; i++) {
					bodyFormData.append('documents[]', this.files[i]);
				}

                axios.post(`${this.poBaseUrlState}/warehouse/${this.linkData.warehouse_id}/inbound/${this.linkData.inbound_id}/upload-documents`, bodyFormData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function( progressEvent ) {
                        this.progress = parseInt( Math.round((progressEvent.loaded / progressEvent.total) * 100 ));
                    }.bind(this)
                }).then(async (res) => {
                    if (res.status === 200) {
                        this.notificationMessage('Documents uploaded successfully!')
                        this.close()
                        this.isUploading = false

                        await this.fetchSingleInbound(singlePayload)
                    } 
                }).catch(e => {
                    this.isUploading = false

                    if (typeof e.message !== 'undefined' && 
                        e.message == "Cannot read properties of undefined (reading 'status')") {
                        this.notificationError('Token has been expired. Please reload the page.')
                    } else {
                        this.notificationError(e)
                    }
                })
            }
        }
    },
    updated() {},
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/inventory/inbound/uploadInbound.scss';
</style>
