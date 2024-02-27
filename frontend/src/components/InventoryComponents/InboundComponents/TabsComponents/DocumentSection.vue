<template>
    <div class="inbound-pallet-section documents-tab">
        <v-data-table
            :headers="documentHeader"
            :items="documentData"
            item-key="id"
            class="elevation-1 inbound-view-pallets"
            :class="documentData.length == 0 ? 'no-data-table' : ''"
            mobile-breakpoint="769"
            hide-default-footer
            show-select
            :items-per-page="500">

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
                <div class="document-mobile-upload" v-if="isMobile">
                    <p class="mb-0">Documents</p>

                    <v-spacer></v-spacer>

                    <div class="inbound-multiple-selection">
                        <button class="btn-white receive" @click.stop="uploadDocs">
                            Upload
                        </button>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.original_name`]="{ item }">
                <div class="name-info" v-if="!isMobile">
                    <span>{{ item.original_name !== null ? item.original_name : '--'}}</span>
                </div>

                <div class="documents-mobile-data" v-if="isMobile">
                    <div>
                        <span>{{ item.original_name !== null ? item.original_name : '--'}}</span>
                    </div>

                    <div class="actions-wrapper">
                        <button class="btn-white" style="border: none !important;" @click.stop="downloadDoc(item)" :disabled="getDownloadDocLoading">
                            <img src="@/assets/icons/download.svg" alt="Download">
                        </button>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions-wrapper">
                    <button class="btn-white" style="border: none !important;" @click.stop="downloadDoc(item)" :disabled="getDownloadDocLoading">
                        <img src="@/assets/icons/download.svg" alt="Download">
                    </button>
                </div>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import globalMethods from '../../../../utils/globalMethods'

export default {
    name: 'DocumentSection',
    props: ['inboundProductsData', 'warehouse_id', 'linkData', 'isMobile', 'productLists'],
    components: {
        
    },
    data: () => ({
        documentHeader: [
            {
				text: 'Name',
				align: 'start',
				sortable: false,
				value: 'original_name',
				fixed: true,
				width: "80%"
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "20%"
			},
        ],
        dialogUploadDoc: false,
    }),
    computed: {
        ...mapGetters({ 
            getDownloadDocLoading: 'inbound/getDownloadDocLoading'
        }),
        documentData: {
            get() {
                let newDocumentData = []

                if (typeof this.inboundProductsData !== 'undefined' && this.inboundProductsData !== null) {
                    newDocumentData = this.inboundProductsData.documents
                }

                return newDocumentData
            },
            set(value) {
                this.$emit('update:inboundProductsData', value)
            }
        },
    },
    methods: {
        ...mapActions({ 
            downloadInboundDoc: 'inbound/downloadInboundDoc'
        }),
        ...globalMethods,
        async downloadDoc(item) {
            if (item !== null) {
                try {
                    let payload = {
                        warehouse_id: this.warehouse_id,
                        id: item.id,
                        inbound_id: item.inbound_id,
                        ...item
                    }

                    await this.downloadInboundDoc(payload)
                    this.notificationCustom('Document has been downloaded.')
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        uploadDocs() {
            this.$emit('uploadInboundDocument')
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
@import './scss/palletSection.scss';
</style>