<template>
    <div>
        <div class="btn-headerMenus mt-n1" v-if="!isMobile">
            <v-btn v-if="outboundProductsData.outbound_status!=='cancelled'" small @click="uploaddialog=true" class="btn-whiteEE" >
              Upload
            </v-btn>
        </div>
   

        <v-data-table
            :headers="productsHeader"
            :items="documentData"
            item-key="id"
            class="elevation-1 outbound-view-document"
            :class="(typeof documentData !== 'undefined' && documentData !== null && documentData.length > 0) ? '' : 'no-data-table'"
            mobile-breakpoint="769"
            hide-default-footer
            :single-select="documertnsval"
            v-model="selectedDocuments"
            show-select>
            <template v-slot:no-data>            
                <div class="no-data-wrapper" v-if="documentData.length == 0">
                    <div class="no-data-heading">
                        <div>
                            <h3> No Documents </h3>
                            <p> You can start uploading documents. </p>
                        </div>
                    </div>
                </div>
            </template>
             <template v-slot:top>
                <div class="document-mobile-upload d-flex justify-space-between ma-3" v-if="isMobile">
                    <p class="mb-0 mt-2">Documents</p>

                    <v-spacer></v-spacer>

                    <div class="inbound-multiple-selection">
                        <button v-if="outboundProductsData.outbound_status!=='cancelled'" class="btn-white receive" @click.stop="uploaddialog=true">
                            Upload
                        </button>
                    </div>
                </div>
                <v-divider v-if="isMobile"></v-divider>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p>
                        <img src="@/assets/icons/document.svg" width="15" class="mr-1" /> 
                        {{ item.original_name !== '' && item.original_name !== null ? item.original_name : '--'}}
                    </p>
                </div>
                   <div class="documents-mobile-data d-flex justify-space-between" v-if="isMobile">
                    <div>
                        <span>{{ item.original_name !== null ? item.original_name : '--'}}</span>
                    </div>

                    <div class="actions-wrapper">
                        <button class="btn-white" style="border: none !important;" @click.stop="downloadDoc(item)" :disabled="getDownloadDocOutboundLoading">
                            <img src="@/assets/icons/download.svg" alt="Download">
                        </button>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div v-if="!isMobile" class="actions-wrapper mr-2">
                    <button class="btn-download px-2 white pt-2" 
                        @click.stop="downloadDoc(item)"
                        :disabled="getDownloadDocOutboundLoading" :class="item.status">

                        <span class="btn-content">
                            <img src="@/assets/icons/download.svg">
                        </span>
                    </button>
                </div>
            </template>
        </v-data-table>

        <v-dialog v-model="dialogPick" width="500" content-class="outbound-dialog-pick">
            <v-card>
                <v-card-title class="headline">
                    <div class="question-icon mt-3 mb-1">
                        <img src="@/assets/icons/question.svg" alt="" width="48px" height="48px">
                    </div>
                </v-card-title>

                <v-card-text style="padding-bottom: 15px;">
                    <h2>Confirm Pick</h2>
                    <p v-if="currentPickedProduct !== null">
                        Have you picked <span class="color-blue-default">{{ currentPickedProduct.carton_count }} Cartons 
                        </span> with {{ currentPickedProduct.units }} Units of 
                        <span class="color-blue-default">SKU {{ currentPickedProduct.sku }}</span> 
                        <span v-if="currentPickedProduct.pallet !== ''"> from Pallet {{ currentPickedProduct.pallet }}</span>?
                    </p>
                </v-card-text>

                <v-card-actions>
                    <button class="btn-blue confirm-pick" @click="closePickDialog">
                        Confirm Pick
                    </button>

                    <button class="btn-white cancel-pick" @click="closePickDialog">
                        Cancel
                    </button>
                </v-card-actions>
            </v-card>
        </v-dialog>

         <UploadOutboundDialog 
            :dialogData.sync="uploaddialog"
            @close="closeOutboundDocument" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import UploadOutboundDialog from '../Dialogs/UploadOutboudDialog.vue'
import globalMethods from "../../../../utils/globalMethods"

export default {
    name: 'ProductSection',
    props: ["outboundProductsData", "isMobile"],
    components: {
        UploadOutboundDialog
    },
    data: () => ({
        uploaddialog:false,
        productsHeader: [
            {
				text: 'Name',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: ""
			},
			{ 
				text: '', 
				align: 'start',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "50px"
			},
        ],
        productItems: [
            {
                name: 'Bill of Lading',
            },
            {
                name: 'Delivery Order',
            },
            {
                name: 'Commercial Invoice',
            },
            {
                name: 'Entry Summary',
            },
        ],
        dialogPick: false,
        currentPickedProduct: null,
        documertnsval:false,
        selectedDocuments:[]
    }),
    computed: {
        ...mapGetters({getDownloadDocOutboundLoading:'outbound/getDownloadDocOutboundLoading' }),
        documentData: {
            get() {
                let newDocumentData = []

                if (typeof this.outboundProductsData !== 'undefined' && this.outboundProductsData !== null) {
                    if (this.outboundProductsData.outbound_documents !== 'undefined' 
                        && this.outboundProductsData.outbound_documents.length > 0) {
                        
                        newDocumentData = this.outboundProductsData.outbound_documents
                    }
                }

                return newDocumentData
            },
            set(value) {
                this.$emit('update:outboundProductsData', value)
            }
        }
    },
    methods: {
        ...mapActions({  downloadOutboundDoc: 'outbound/downloadOutboundDoc'}),
         ...globalMethods,
        pickProduct(item) {
            this.dialogPick = true
            this.currentPickedProduct = item
        },
        closePickDialog() {
            this.dialogPick = false
        },
          async downloadDoc(item) {
            if (item !== null) {
                try {
                    let payload = {
                        warehouse_id: new URL(location.href).searchParams.get('wid'),
                        id: item.id,
                        outbound_id: item.outbound_id,
                        ...item
                    }

                    await this.downloadOutboundDoc(payload)
                    this.notificationMessage('Document has been downloaded.')
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        closeOutboundDocument(){
            this.uploaddialog=false
        }

    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "inventory");
    },
    updated() {}
}
</script>

<style lang="scss">
//  @import '../../../assets/scss/buttons.scss';
// @import '../../../assets/scss/pages_scss/outbound/outboundView.scss'; 
@import './scss/productSection.scss';
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';

.outbound-view-document {
    table {
        tbody {
            tr {
                td {
                    .name-wrapper {
                        p {
                            display: flex;
                            justify-content: flex-start;
                            align-items: center;
                        }
                    }
                }
            }
        }
    }
}

.btn-whiteEE {
    background-color: $white !important;
    color: $dark-blue !important;
    border: 1px solid $light-grey !important;
    padding: 10px 16px !important;
    font-size: 14px;
    height: 34px !important;
    text-transform: capitalize;
    letter-spacing: 0;
    box-shadow: none !important;
    border-radius: 4px;
    font-family: 'Inter-Regular', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;

    &:disabled {
        color: $dark-blue !important;
    }

    &.delete {
        color: $red !important;
    }
}
      
</style>