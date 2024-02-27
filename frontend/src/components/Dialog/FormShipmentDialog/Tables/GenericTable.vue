<template>
    <div class="generic-table-wrapper">
        <v-data-table 
            :headers="headers" 
            :items="documentFiles"
            mobile-breakpoint="769"
            :page="1"
            :items-per-page="100"
            hide-default-footer
            style="box-shadow: none !important"
            fixed-header
            hide-default-header>
            <template v-slot:header="{ props: { headers } }">
                <thead>
                    <tr>
                        <th v-for="(item, index) in headers" 
                            :key="index"
                            role="column-header"
                            :aria-label="item.text"
                            scope="col"
                            :style="`text-transform: uppercase !important;text-align: ${item.textAlign}; color: #6D858F;width: ${item.width}; padding-left: ${index == 0 ? '0px' : '12px'} !important; padding-right: ${index == 0 ? '0px' : '12px'} !important; height: 36px !important; background-color: ${headerBackground}; box-shadow: none !important;`"
                            >
                            {{ item.text }}
                        </th>
                    </tr>
                </thead>
            </template>
            <template v-slot:[`item.name`]="{ item }">
                <div class="d-flex">
                    <span :style="`color: ${textColor};`">{{ item.name }}</span>
                </div>
            </template>
            <template v-slot:[`item.type`]="{ item }">
                <div class="d-flex">
                    <input-select
                        :field.sync="item.type"
                        id="autocomplete-wrapper"
                        placeholder="Select Document Type"
                        :noBorder="true"
                        :typeOptions="documentTypes"
                        :selectedValue="item.type"
                    ></input-select>
                </div>
            </template>
            <template v-slot:[`item.document_beginning`]>
                <generic-icon iconName="document"></generic-icon>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
                <a @click.stop="removeFile(item)" style="cursor:pointer;">
                <generic-icon iconName="close"></generic-icon>
                </a>
            </template>
            <template v-slot:no-data>
                <div class="d-flex flex-center justify-center">
                    <a @click.stop="addDocuments" class="d-flex pom-upload-document" style="cursor:pointer">
                        <generic-icon iconName="upload"></generic-icon>
                        <span style="color: #0171A1; padding-left: 6px;">{{ "Upload Document" }}</span>
                    </a>
                    <input 
                        ref="upload_documents_reference" 
                        type="file" 
                        id="upload_documents" 
                        @change="(e) => inputChanged(e)" 
                        name="upload_po_documents"
                        accept=""
                        multiple
                        style="display: none;"
                    />
                </div>
            </template>
        </v-data-table>
        <div v-if="documentFiles.length > 0" class="d-flex upload-document-bottom-wrapper">
            <a @click.stop="addDocuments" class="d-flex" style="cursor:pointer">
                <generic-icon iconName="upload"></generic-icon>
                <span style="color: #0171A1; padding-left: 6px;">{{ "Upload Document" }}</span>
            </a>
            <input 
                ref="upload_documents_reference" 
                type="file" 
                id="upload_documents" 
                @change="(e) => inputChanged(e)" 
                name="upload_po_documents"
                accept=""
                multiple
                style="display: none;"
            />
        </div>
    </div>
</template>
<style lang="scss">
@import "./scss/genericTable.scss";
</style>
<script>
import GenericIcon from '../../../Icons/GenericIcon';
import InputSelect from '../InputSelects/InputSelect';
import { mapActions, mapGetters } from "vuex"

export default {
    components: {
        GenericIcon,
        InputSelect
    },
    data: () => ({
        documentFiles: [],
        files: [],
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
    }),
    methods: {
      ...mapActions({
        fetchShipmentCustomerDocuments: 'booking/fetchShipmentCustomerDocuments',
        deleteShipmentCustomerDocuments: 'booking/deleteShipmentCustomerDocuments'
      }),
      ...mapGetters({
        getShipmentCustomerDocuments: "booking/getShipmentCustomerDocuments",
        getDeleteShipmentCustomerDocumentsLoading: 'booking/getDeleteShipmentCustomerDocumentsLoading'
      }),
      getSelectedDocId(docType){
        const filtered = this.documentTypes.find((documentType) => {
          if(docType != ''){
            return documentType.name.toLowerCase() == docType.toLowerCase();
          }
          return false;
        });
        if(filtered != '' && filtered != null && filtered != 'undefined'){
          return filtered.id;
        }
      },
      addDocuments() {
            this.$refs.upload_documents_reference.click()
        },
        async removeFile(item) {
          if(item.isEdit == 1){
            await this.deleteShipmentCustomerDocuments(item.id);
          }

          let getIndex = this.documentFiles.indexOf(item)
            this.documentFiles.splice(getIndex, 1)
            this.files.splice(getIndex, 1)
        },
        inputChanged() {
            let files = this.$refs.upload_documents_reference.files
            if ( !files.length )
                return false
            
            for (let i = 0; i < files.length; i++) {
                this.documentFiles.push({
                    file: files[i],
                    supplier_id: [],
                    type: '',
                    actions: '',
                    name: files[i].name,
                    fileError: false
                })
                this.files.push(files[i])
            }
            this.$emit('update:items', this.documentFiles)
        }
    },
  async mounted() {
    if(this.shipmentId){
      await this.fetchShipmentCustomerDocuments(this.shipmentId)
      const shipmentDocs = this.getShipmentCustomerDocuments();

      shipmentDocs.map(shipmentDocs => {
        this.documentFiles.push({
          id: shipmentDocs.id,
          file: '',
          supplier_id: [],
          type: this.getSelectedDocId(shipmentDocs.type),
          actions: '',
          name: shipmentDocs.name,
          fileError: false,
          isEdit: 1,
        })
      })
      this.$emit('update:items', this.documentFiles)
    }
  },

  props: {
        headerBackground: {
            default: ''
        },
        pomKey: {
            default: 0
        },
        textColor: {
            default: ''
        },
        headers: {
            default: []
        },
        items: {
            default: []
        },
        shipmentId:{
          default: []
        }
  },
}
</script>