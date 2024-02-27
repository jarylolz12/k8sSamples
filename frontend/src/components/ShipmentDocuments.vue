<template>
    <div id="documents" v-resize="onResize">
        <v-snackbar v-model="shipmentDocumentSubmitted" class="shipmentDocumentSubmitted" timeout="20000">
            <span><img src="@/assets/icons/check-white.svg" width="16px" height="16px"> Documents have successfully been submitted</span>
        </v-snackbar>
    
        <div class="documents-wrapper-header" :class="selected.length !== 0 ? 'has-selected' : ''" 
            v-if="getShipmentDocuments.length !== 0">
            <div class="documents-title-wrapper">
                <h3 v-if="selected.length == 0">Documents</h3>

                <h3 v-if="selected.length !== 0 && !isMobile">{{ selected.length }} items selected</h3>
            </div>

            <div class="button-wrapper-header">
                <div class="button-wrapper-content">
                    <!-- <button :class="selected.length !== 0 ? 'btn-blue' : 'btn-black'" @click.stop="openSubmitDialog" 
                        v-if="getShipmentDocuments.length > 0">
                        <img src="@/assets/icons/submit.svg" width="16px" height="16px" v-if="selected.length == 0">
                        <img src="@/assets/icons/submit-blue.svg" width="16px" height="16px" v-if="selected.length !== 0">
                        Submit
                    </button> -->

                    <button class="btn-white mr-0" @click.stop="openUploadDialog" v-if="selected.length == 0">
                        <img src="@/assets/icons/upload.svg" width="16px" height="16px">
                        Upload
                    </button>
                </div>
                
                <div class="button-wrapper-content" v-if="!isMobile">
                    <button class="btn-white" @click="downloadDocuments()" v-if="selected.length !== 0">
                        <img src="../assets/icons/download.svg" alt="" width="16px" height="16px">
                        Download
                    </button>
                    <button class="btn-white" @click.stop="openDeleteDialog" v-if="selected.length !== 0">
                        <img src="../assets/icons/delete-po.svg" alt="" width="16px" height="16px">
                        Delete
                    </button>

                    <button class="btn-white btn-gray mr-0" @click.stop="cancel()" v-if="selected.length !== 0">
                        Cancel
                    </button>
                </div>
            </div>

            <div class="mobile-buttons-wrapper" v-if="isMobile && selected.length !== 0">
                <div class="mobile-buttons-actions" v-if="selected.length !== 0"> 
                    <button class="btn-white" @click="downloadDocuments()" v-if="selected.length !== 0">
                        <img src="../assets/icons/download.svg" alt="" width="16px" height="16px">
                    </button>
                    <button class="btn-white" @click.stop="openDeleteDialog" v-if="selected.length !== 0">
                        <img src="../assets/icons/delete-po.svg" alt="" width="16px" height="16px">
                    </button>

                    <button class="btn-white mr-0" @click.stop="cancel()" v-if="selected.length !== 0">
                        <img src="../assets/icons/documents-close.svg" alt="" width="16px" height="16px">
                    </button>
                </div>

                <!-- <v-menu bottom left offset-y content-class="outbound-lists-menu" v-if="selected.length !== 0">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="btn-white btn-more" icon v-bind="attrs" v-on="on">
                            <v-icon>mdi-dots-horizontal</v-icon>
                        </v-btn>
                    </template>

                    <v-list class="outbound-lists">                        
                        <v-list-item @click="downloadDocuments()" v-if="selected.length !== 0">
                            <v-list-item-title>
                                Download
                            </v-list-item-title>
                        </v-list-item>

                        <v-list-item @click.stop="openDeleteDialog" v-if="selected.length !== 0">
                            <v-list-item-title class="cancel">
                                Delete
                            </v-list-item-title>
                        </v-list-item>

                        <v-list-item @click.stop="cancel()" v-if="selected.length !== 0">
                            <v-list-item-title>
                                Cancel
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu> -->
            </div>
        </div>

        <v-data-table
            v-model="selected"
            :headers="headers"
            :items="getShipmentDocuments"
            :single-select="singleSelect"
            item-key="id"
            :items-per-page="documentItemsPerPage"
            show-select
            class="elevation-1 documents-table"
            :class="getShipmentDocuments.length == 0 ? 'no-data-table' : ''"
            hide-default-footer 
            v-if="!isMobile">

            <template v-slot:[`item.name`]="{ item }">
                <div class="document-name-info">
                    <p>{{ item.name }}</p>
                    <p class="updated-by-name-date" v-if="item.updated_by_name">{{ item.updated_by_name ? item.updated_by_name.company_name : 'N/A' }}, <span>{{dateFormat(item.updated_at)}}</span></p>
                </div>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="document-type-info">
                    <p>
                        {{ 
                            typeof item.type !== 'undefined' ? 
                            (item.type === 'Hbl' ? 'OBL Document' : item.type) 
                            : '--' 
                        }}
                    </p>

                    <!-- <p class="supplier-name" v-if="item.type !== 'Delivery Order' && item.type !== 'Other'">
                        Supplier:
                        <span v-for="(supplier, index) in item.supplier_ids" :key="index">
                            {{ supplier.company_name }}
                        </span>
                    </p> -->

                    <p class="supplier-name" v-if="item.type !== 'Delivery Order' && item.type !== 'Other'">
                        Supplier:
                        
                        <span v-if="item.supplier_ids.length === 1">
                            <span v-for="(supplier, index) in item.supplier_ids" :key="index">
                                {{ supplier.company_name }}
                            </span>
                        </span>

                        <span v-if="item.supplier_ids.length > 1">
                            <span> Multiple </span>
                        </span>
                    </p>
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions">
					<button class="btn-white" @click="viewDocument(item)" :disabled="checkFileExtension(item.name)">
						<v-icon>mdi-eye</v-icon>
					</button>

                    <button class="btn-white" @click="download(item.url)">
                        <img src="../assets/icons/download.svg" alt="">
                    </button>
                    
                    <v-menu bottom left offset-y content-class="outbound-lists-menu">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn class="btn-white" icon v-bind="attrs" v-on="on">
                                <v-icon>mdi-dots-horizontal</v-icon>
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <!-- <v-list-item @click="viewDocument(item)">
                                <v-list-item-title>
                                    View
                                </v-list-item-title>
                            </v-list-item> -->

                            <v-list-item @click.stop="openSubmitItemDialog(item)" 
                                v-if="item.type !== 'Delivery Order' && item.type !== 'Other'">
                                <v-list-item-title>
                                    Submit
                                </v-list-item-title>
                            </v-list-item>

                            <v-list-item @click.stop="openUpdateDialog(item)">
                                <v-list-item-title>
                                    Update Name & Type
                                </v-list-item-title>
                            </v-list-item>

                            <v-list-item @click.stop="openDeleteItemDialog(item)">
                                <v-list-item-title class="cancel">
                                    Delete
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="no-data-preloader" v-if="shipmentsLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                    <p class="k-mt-8 pt-4">Fetching shipment documents...</p>
                </div>

                <div class="no-data-wrapper" v-if="getShipmentDocuments.length == 0 && !shipmentsLoading">
                    <div class="no-data-heading">
                        <div>
                            <h3> No Documents </h3>
                            <p class="k-col-tablet-63 k-col-mobile-90"> No document have been uploaded for this shipment. </p>

                            <div class="d-flex justify-center align-center mt-4">
                                <v-btn color="primary" dark class="btn-white" @click.stop="openUploadDialog">
                                    Upload Documents
                                </v-btn>
                            </div>
                        </div> 
                    </div>
                </div>
            </template>
        </v-data-table>

        <v-data-table
            :items="getShipmentDocuments"
            v-model="selected"
            :headers="headersMobile"
            :single-select="singleSelect"
            item-key="id"
            :items-per-page="documentItemsPerPage"
            show-select
            class="elevation-1 documents-table"
            :class="getShipmentDocuments.length == 0 ? 'no-data-table' : ''"
            hide-default-footer
            mobile-breakpoint="0"
            v-if="isMobile">

            <template v-slot:item="props">
                <tr>
                    <td>
                        <div class="document-name-info">
                            <div class="document-name-checkbox">
                                <v-checkbox :input-value="props.isSelected" @change="props.select($event)"></v-checkbox>
                                <div class="doc-name">
                                    <p>{{ props.item.name }}</p>

                                    <div class="mobile-document-type-info">
                                        <p class="document-types">
                                            {{ 
                                                typeof props.item.type !== 'undefined' ? 
                                                (props.item.type === 'Hbl' ? 'OBL Document' : props.item.type) 
                                                : '--' 
                                            }}
                                        </p>

                                        <p class="supplier-name" v-if="props.item.type !== 'Delivery Order' && props.item.type !== 'Other'">
                                            Supplier:
                                            
                                            <span v-if="props.item.supplier_ids.length === 1">
                                                <span v-for="(supplier, index) in props.item.supplier_ids" :key="index">
                                                    {{ supplier.company_name }}
                                                </span>
                                            </span>

                                            <span v-if="props.item.supplier_ids.length > 1">
                                                <span> Multiple </span>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div> 

                            <v-menu bottom left offset-y content-class="outbound-lists-menu outbound-lists-menu-mobile">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn class="btn-white" icon v-bind="attrs" v-on="on">
                                        <v-icon>mdi-dots-horizontal</v-icon>
                                    </v-btn>
                                </template>

                                <v-list class="outbound-lists">
                                    <v-list-item @click.stop="openSubmitDialog()" 
                                        v-if="props.item.type !== 'Delivery Order' && props.item.type !== 'Other'">
                                        <v-list-item-title>
                                            Submit
                                        </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item @click.stop="openUpdateDialog(props.item)">
                                        <v-list-item-title>
                                            Update Name & Type
                                        </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item @click.stop="openDeleteItemDialog(props.item)">
                                        <v-list-item-title class="cancel">
                                            Delete
                                        </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>
                    </td>
                </tr>
            </template>

            <template v-slot:no-data>
                <div class="no-data-preloader no-data-preloader-mobile" v-if="shipmentsLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                    <div class="k-mt-8 k-border-b-0 loading-text pt-4">Fetching shipment documents...</div>
                </div>
                <div class="no-data-wrapper" v-if="getShipmentDocuments.length == 0 && !shipmentsLoading">
                    <div class="no-data-heading">
                        <div>
                            <h3> No Documents </h3>
                            <p class="k-col-tablet-63 k-col-mobile-90"> No document have been uploaded for this shipment. </p>

                            <div class="d-flex justify-center align-center mt-4">
                                <v-btn color="primary" dark class="btn-white" @click.stop="openUploadDialog">
                                    Upload Documents
                                </v-btn>
                            </div>
                        </div> 
                    </div>
                </div>
            </template>
        </v-data-table>

        <div v-if="getShipmentDocuments.length > 0 && 
            getShipmentDocumentsPage.last_page > 1" 
            class="pagination-wrapper pt-3" >

            <v-pagination
                v-model="getShipmentDocumentsPage.current_page"
                :length="getShipmentDocumentsPage.last_page"
                prev-icon="Previous"
                next-icon="Next"
                :total-visible="!isMobile ? '11' : '5' "
                @input="handleDocumentsPageChange">
            </v-pagination>
        </div>

        <UploadDocumentsDialog 
            :documentsData="documents"
            :shipment_id="id"
            :dialogData.sync="dialogUploadDocs"
            @fetchDocuments="fetchShipmentDocumentsAfter"
            @close="closeUploadDialog" />

        <SubmitDocumentsDialog
            :selected="selected"
            :shipment_id="id"
            :submit_item="submitItem"
            :documentsData="documents"
            :dialogData.sync="dialogSubmitDocs"
            @cancelSubmission="cancelSubmission"
            @close="closeSubmitDialog" />

        <DeleteDocumentsDialog 
            :selected="selected"
            :shipment_id="id"
            :delete_item="deleteItem"
            :documentsData="documents"
            :dialogData.sync="dialogDeleteDocs"
            @close="closeDeleteDialog" 
            @fetchDocuments="fetchShipmentDocumentsAfter" />

        <UpdateDocumentsDialog 
            :documentsData="documents"
            :shipment_id="id"
            :editItem.sync="editItem"
            :dialogData.sync="dialogUpdateDocs"
            @fetchDocuments="fetchShipmentDocumentsAfter"
            @close="closeUpdateDialog" />

		<ViewDocumentsDialog
			:dialogData.sync="ViewDocumentDialog"
			:documentData="documentData"
			@close="closeViewDocumentDialog"
		/>
    </div>
</template>

<script>
import UploadDocumentsDialog from '../components/ShipmentComponents/Documents/UploadDocumentsDialog.vue'
import { mapActions, mapGetters } from "vuex";
import globalMethods from '../utils/globalMethods'
import DeleteDocumentsDialog from '../components/ShipmentComponents/Documents/DeleteDocumentsDialog.vue'
import SubmitDocumentsDialog from '../components/ShipmentComponents/Documents/SubmitDocumentsDialog.vue'
import UpdateDocumentsDialog from './ShipmentComponents/Documents/UpdateDocumentsDialog.vue'
import ViewDocumentsDialog from "./ShipmentComponents/Documents/ViewDocumentsDialog.vue";
import axios from 'axios'
import moment from 'moment'

//const source = cancelToken.source()

export default {
    props: ['getDetails', 'id', 'shipmentsLoading'],
    components: {
        UploadDocumentsDialog,
        SubmitDocumentsDialog,
        DeleteDocumentsDialog,
        UpdateDocumentsDialog,
        ViewDocumentsDialog,
    },
    data: () => ({
        page: 1,
        editItem: {
            id: 0,
            name: '',
            type: '',
            supplier_ids: []
        },
        deleteItem: [],
        currentCancelToken: {},
        source: {},
        submitItem: [],
        pageCount: 0,
        documentItemsPerPage: 35,
        itemsPerPage: 10,
        isMobile: false,
        singleSelect: false,
        cancelOperation: false,
        selected: [],
        supplierOptions: [],
        headers: [
            {
                text: 'NAME',
                align: 'start',
                sortable: true,
                value: 'name',
                width: '45%',
                fixed: true
            },
            {
                text: 'Type & Tag',
                align: 'start',
                sortable: true,
                value: 'type',
                width: '30%',
                fixed: true
            },
            { 
                text: '', 
                value: 'actions',
                sortable: false,
                fixed: true,
                width: '20%'
            }
        ],
        headersMobile: [
            { text: '', value: 'data-table-select', width: "1%"},
            {
                text: 'NAME, TYPE & TAG',
                align: 'start',
                sortable: true,
                value: 'name',
                width: '45%',
                fixed: true
            }
        ],
        documents: [],
        dialogUploadDocs: false,
        dialogSubmitDocs: false,
        dialogDeleteDocs: false,
        dialogUpdateDocs: false,
        editedDocumentFiles: [],
        sampleData: [
          {
            name: '12348-invoice.pdf',
            type: 'Commercial Invoice',
            supplier: 'Luice Juice Maker Pvt Ltd.',
          },
          {
            name: 'Supplier 2 - pl.csv',
            type: 'Packing List',
            supplier: 'Dennim & Fashion Corp...',
          },
          {
            name: 'Supplier3_docs.csv',
            type: 'Invoice & Packing List',
            supplier: 'Downson Industrial Prod...',
          },
          {
            name: 'Entry Summary.xlxs',
            type: 'Others',
            supplier: '',
          },
          {
            name: 'IMG_23221.jpeg',
            type: 'Delivery Order',
            supplier: '',
          },
        ],
        shipmentDocumentSubmitted: false,
        ViewDocumentDialog: false,
        documentData: '',
    }),
    async mounted() {
        let { id } = this

        this.documents = this.getShipmentDocuments

        if (this.getShipmentDocuments.length == 0 ) {
            this.$emit('update:shipmentsLoading', true)   
        }

        await this.fetchShipmentDocuments({
            shipment_id: id,
            page: 1
        })
        this.$emit('update:shipmentsLoading',false)
        await this.fetchShipmentSuppliers(this.id)
        // this.$store.dispatch("suppliers/fetchSuppliers")
        if(this.getDetails !== 'undefined') {            
            this.documents = this.getShipmentDocuments
        }
    },
    computed: {
        ...mapGetters([
            'getShipmentDocumentsLoading',
            'getShipmentDocuments',
            'getShipmentDocumentsSubmitting',
            'getShipmentDocumentsPage'
        ]),
        getBetaUrl() {
            let betaUrl = this.$store.getters['page/getBetaUrl']
            betaUrl = betaUrl.replace('api','')
            return betaUrl
        }
    },
    methods: {
        ...globalMethods,
        ...mapActions([
            'fetchShipmentDocuments',
            'submitShipmentDocuments',
            'clearShipmentDocuments',            
            'fetchShipmentSuppliers'
        ]),
        cancelSubmission() {
            this.cancelOperation = true
            this.source.cancel('Submission of token cancelled.')
        },
        async handleDocumentsPageChange(page) {
            console.log('loading true')
            this.$emit('update:shipmentsLoading',true)
            this.$emit('handleDocumentsChange', page)
        },
        async fetchShipmentDocumentsAfter(payload) {
            this.clearShipmentDocuments()
            this.$emit('update:shipmentsLoading',true)
            await this.fetchShipmentDocuments(payload)
            this.selected = []
            this.$emit('update:shipmentsLoading',false)
        },
        onResize() {
            if (window.innerWidth < 769) {
                this.isMobile = true;
            } else {
                this.isMobile = false;
            }
        },
        download(url) {
            //window.location.href = `https://beta.shifl.com/download?link=${encodeURIComponent(url)}`
            window.location.href = `${this.getBetaUrl}download?link=${encodeURIComponent(url)}`
        },
        iterateDocument(index) {            
            if (this.selected.length > 0 && typeof this.selected[index]!=='undefined') {
                window.location.href = `${this.getBetaUrl}download?link=${encodeURIComponent(this.selected[index].url)}`
                index++
                setTimeout(() => {
                    this.iterateDocument(index)
                }, 3000)
            }
        },
        downloadDocuments() {   
            let startIndex = 0      
            this.iterateDocument(startIndex)   
        },
        openUploadDialog() {
            this.dialogUploadDocs = true
        },
        closeUploadDialog() {
            this.dialogUploadDocs = false
        },
        async openSubmitItemDialog(item) {
            try {
                this.dialogSubmitDocs = true
                this.source = axios.CancelToken.source()
                await this.submitShipmentDocuments({
                    shipment_id: this.id,
                    document_ids: [item.id],
                    cancelToken: this.source.token
                })
                this.notificationMessageCustomSuccess('Documents have successfully been submitted.')
                this.dialogSubmitDocs = false
            } catch(e) {
                if (this.cancelOperation) {
                    this.notificationError('Submission cancelled')
                    this.cancelOperation = false
                } else {
                    this.notificationError('An error occured. Please refresh the page and try again.')
                    console.log(e)    
                }

                this.dialogSubmitDocs = false
                this.dialogSubmitDocs = false
            }
        },
        async openSubmitDialog() {
            try {
                let selected_items = []
                if ( this.selected.length > 0 ) {
                    this.dialogSubmitDocs = true
                    this.selected.map( s => {
                        selected_items.push(s.id)
                    })
                    this.source = axios.CancelToken.source()
                    await this.submitShipmentDocuments({
                        shipment_id: this.id,
                        document_ids: selected_items,
                        cancelToken: this.source.token
                    })
                    this.notificationMessageCustomSuccess('Documents have successfully been submitted.')
                    this.dialogSubmitDocs = false
                    this.selected = [] 
                } else {
                    this.notificationError('Make sure to select first a document to submit.')
                }
                
            } catch(e) {
                if (this.cancelOperation) {
                    this.notificationError('Submission cancelled')
                    this.cancelOperation = false
                } else {
                    this.notificationError('An error occured. Please refresh the page and try again.')  
                }
                this.dialogSubmitDocs = false
            }
        },
        openDeleteItemDialog(item) {
            this.deleteItem = [item]
            this.dialogDeleteDocs = true
        },
        openDeleteDialog() {
            this.deleteItem = []
            this.dialogDeleteDocs = true
        },
        closeSubmitDialog() {
            this.dialogSubmitDocs = false
        },
        closeDeleteDialog() {
            this.dialogDeleteDocs = false
        },
        openUpdateDialog(item) {
            this.$store.dispatch('documents/setEditId', item.id)
            let getItem = item
            this.editItem = {
                id: getItem.id,
                name: getItem.name,
                // supplier_ids: getItem.supplier_ids,
                supplier_ids: getItem.supplier_ids[0],
                shipment_id: getItem.shipment_id,
                type: getItem.type
            }
            //this.editItem = getItem
            this.$store.dispatch('documents/setEditDocument', getItem)
            this.dialogUpdateDocs = true
        },
        closeUpdateDialog() {
            this.dialogUpdateDocs = false
        },
        cancel() {
            this.selected = [];
        },
        async submitDocs() {
            try {
                await this.submitShipmentDocuments({
                    shipment_id: this.id
                })
                this.notificationMessageCustomSuccess('Documents have successfully been submitted.')
            } catch(e) {
                this.notificationError('Make sure to select first a document to submit.')
                console.log(e)
            }
        },
        viewDocument(item) {
			this.ViewDocumentDialog = true;
			this.documentData = item;
		},
		closeViewDocumentDialog() {
			this.ViewDocumentDialog = false;
		},
        checkFileExtension(filename) {
            var parts = filename.split('.');
            let fileType = parts[parts.length - 1];
            return fileType.toLowerCase() !== 'pdf' ? true : false
        },
        dateFormat(date){
            return moment(date).format("h:mm A, DD/MM/YY");
        }
        // submitDocs() {

        // },
    },
    updated() {}
}
</script>

<style lang="scss">
/* @import '../assets/css/shipments_styles/shipmentDocuments.css'; */
@import '../assets/scss/pages_scss/shipment/shipmentDocuments.scss';
@import '../assets/scss/utilities.scss';
</style>
