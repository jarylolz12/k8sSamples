<template>
  <transition v-if="show" name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <slot v-bind:headerProps="{ close: close }" name="header"></slot>
          </div>
          <div class="modal-body mb-4">
            <slot v-bind:item="{item: item, selectFile: selectFile }" name="body">
            </slot>
            <input ref="supplierFileHolder" multiple="true" @change="(e) => fileChange(e)"  class="hidden" type="file" id="supplier-file-holder" />
            <div class="w-full flex flex-col mt-4">
              <div :class="`document-item flex flex-row justify-between mb-2 ${(document.fileError) ? 'has-error' : ''}`" v-for="(document, key) in documents">
                <div class="document-name">
                  {{
                    document.name
                  }}
                </div>
                <div>
                  {{
                    document.type
                  }}
                </div>
                <div>
                  <button @click.prevent="removeItem(key)">
                    <font-awesome-icon color="#0171a1" icon="fa-solid fa-xmark" />
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <slot v-bind:footerProps="{ close: close, upload: upload, item: item }" name="footer">
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
<style lang="scss">
@import './scss/DocumentModal.scss';
</style>
<script>
import axios from 'axios'
import iziToast from 'izitoast'
import { faArrowUp, faWindowClose, faXmark } from '@fortawesome/free-solid-svg-icons'
import 'izitoast/dist/css/iziToast.min.css'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { mapActions, mapGetters } from 'vuex'
import _ from 'lodash'

export default {
  props: ['show', 'item', 'resourceId', 'url', 'token', 'field', 'formGroups'],
  data: () => ({
    documents: [],
    files: []
  }),
  computed: {
    ...mapGetters({
       getShipmentSupplierGroupField: 'shipment/getShipmentSupplierGroupField'
    })
  },
  created() {
    library.add(faArrowUp)
    library.add(faWindowClose)
    library.add(faXmark)
  },
  mounted() {},
  components: {
    FontAwesomeIcon
  },
  methods: {
    ...mapActions({
      uploadDocuments: 'shipment/uploadDocuments',
      setShipmentSupplierGroupField: 'shipment/setShipmentSupplierGroupField',
      fetchSuppliers: 'shipment/fetchShipmentSuppliers'
    }),
    setDocuments(files) {
      this.files = []
      for ( var x=0; x<files.length; x++ ) {
        this.documents.push({
          file: files[x],
          supplier_id: [this.item.supplier_id],
          type: this.item.special_type,
          name: files[x].name,
          typeError: false,
          supplierError: false,
          nameError: false,
          fileError: false,
          supplier_temp_id: this.item.id
        })
        this.files.push(files[x])
      }
    },
    removeItem(key) {
      this.files.splice(key, 1)
      this.documents.splice(key, 1)
    },
    resetSuppliers() {
      //reset suppliers data
      let shipment_supplier_field = this.getShipmentSupplierGroupField
      shipment_supplier_field.value = this.field.value
      this.setShipmentSupplierGroupField(shipment_supplier_field)
      this.fetchSuppliers({
        shipment_supplier_field,
        base_url: this.url
      })
    },
    fileChange() {
      let files = this.$refs.supplierFileHolder.files;
      
      if (!files.length)
        return false

      this.setDocuments(files)
    },
    upload(item) {
      let {
        documents,
        resourceId,
        url,
        field
      } = this

      let suppliers = this.formGroups


      let findIndex = _.findIndex(suppliers, {
        id: item.id
      })
       
      if ( findIndex!==-1 ) {

        suppliers[findIndex].temp_hbl_copies = (typeof suppliers[findIndex].temp_hbl_copies=='undefined') ? [] : suppliers[findIndex].temp_hbl_copies
        suppliers[findIndex].temp_commercial_invoices = (typeof suppliers[findIndex].temp_commercial_invoices=='undefined') ? [] : suppliers[findIndex].temp_commercial_invoices
        suppliers[findIndex].temp_packing_lists = (typeof suppliers[findIndex].temp_packing_lists=='undefined') ? [] : suppliers[findIndex].temp_packing_lists
        suppliers[findIndex].temp_invoice_packing_lists = (typeof suppliers[findIndex].temp_invoice_packing_lists=='undefined') ? [] : suppliers[findIndex].temp_invoice_packing_lists
        suppliers[findIndex].temp_other_commercial_docs = (typeof suppliers[findIndex].temp_other_commercial_docs=='undefined') ? [] : suppliers[findIndex].temp_other_commercial_docs

        documents.map( d => {
          if ( d.type === 'Hbl')
            suppliers[findIndex].temp_hbl_copies.push(d.name)
          else if ( d.type === 'Commercial Invoice')
            suppliers[findIndex].temp_commercial_invoices.push(d.name)
          else if ( d.type === 'Packing List')
            suppliers[findIndex].temp_packing_lists.push(d.name)
          else if ( d.type === 'Invoice And Packing List')
            suppliers[findIndex].temp_invoice_packing_lists.push(d.name)
          else if ( d.type === 'Other Commercial Docs')
            suppliers[findIndex].temp_other_commercial_docs.push(d.name)
          
        })
      }

      this.$emit('setSupplierDocuments', {
        documents,
        resourceId,
        url,
        field,
        item
      })

      this.$emit('updateSupplierGroup', suppliers)
      this.$emit('update:show', false)

      this.documents = []
      this.files = []
      //this.close()
      /*
      item.is_loading = true
      let {
        documents,
        resourceId,
        url
      } = this

      this.documents.map((df,k) => {
        this.documents[k].typeError = false
        this.documents[k].supplierError = false
        this.documents[k].nameError = false
        this.documents[k].fileError = false
      })

      this.uploadDocuments({
        documents,
        resourceId,
        url
      }).then(() => {
        item.is_loading = false
        iziToast.success({
          message: 'Document(s) successfully uploaded.',
          displayMode: 1,
          position: "bottomRight",
          timeout: 3000,
        })
        this.close()
        this.resetSuppliers()
      }).catch(e => {
        item.is_loading = false
        if ( typeof e!=='undefined' ) {
          let error_messages = []
          let error_message = ''
  
          
          if ( typeof e !=='undefined' && typeof e.errors!=='undefined' ) {
            
            /*
            let {
              document_infos
            } = e.errors

            let get_keys = Object.keys(e.errors)

            get_keys.map(gk => {
              e.errors[gk].map( egk => {
                error_messages.push(egk)
              })
            })*/

            /*
            let getKeys = Object.keys(e.errors)

            this.documents.map((df,k) => {
              this.documents[k].typeError = false
              this.documents[k].supplierError = false
              this.documents[k].nameError = false
              this.documents[k].fileError = false
            })

            getKeys.map( gk =>{
              let splitKeys = gk.split('.')
              console.log('split keys', splitKeys)
              if ( splitKeys[2] === 'file') {
                this.documents[parseInt(splitKeys[1])].fileError = true  
              } else if ( splitKeys[2] === 'type') {
                this.documents[parseInt(splitKeys[1])].typeError = true  
              } else if ( splitKeys[2] === 'name') {
                this.documents[parseInt(splitKeys[1])].nameError = true  
              }
            })
            /*
            this.documents.map((df,k) => {
                this.documents[k].typeError = false
                this.documents[k].supplierError = false
                this.documents[k].nameError = false
                this.documents[k].fileError = false
            })

            getKeys.map((gk) => {
                let getKey = e[gk][0].split('.')[2].split(' ')[0]
                if ( getKey === 'supplier_id' ) {
                  this.documents[parseInt(e[gk][0].split('.')[1])].supplierError = true
                }

                if ( getKey === 'type' ) {
                  this.documents[parseInt(e[gk][0].split('.')[1])].typeError = true
                }

                if ( getKey === 'name' ) {
                  this.documents[parseInt(e[gk][0].split('.')[1])].nameError = true
                }

                if ( getKey === 'file' ) {
                  this.documents[parseInt(e[gk][0].split('.')[1])].fileError = true
                }
            })*/


            /*
            error_message = error_messages.join('<br/>')
            iziToast.error({
                title: 'Document Upload Error',
                message: error_messages,
            })*/


            /*
            document_infos_keys.map ( get_key => {
                let get_errors_keys = Object.keys(document_infos[get_key])
                /*
                get_errors_keys.map( gek => {
                    document_infos_keys[get_key][gek].map( em => {
                      error_messages.push(em)
                    })
                }))*/
                //console.log('error keys ', document_infos[get_key])
            //})
            //error_message = error_messages.join('<br/>')
            /*
            iziToast.error({
                title: 'Document Upload Error',
                message: error_messages,
            })
            console.log(e.errors)
            */
         // } 
          //console.log(e)

          /*
          iziToast.error({
            title: 'Error',
            message: JSON.stringify(e.errors),
          })*/
        /*
        } else {
          iziToast.error({
            title: 'Error',
            message: 'Please make sure to upload a document file.'
          })
        }*/
      //})
    },
    selectFile() {
      this.$refs.supplierFileHolder.click()
    },
    close() {
      this.documents = []
      this.files = []
      this.$emit('update:show', false)
    }
  }
}
</script>