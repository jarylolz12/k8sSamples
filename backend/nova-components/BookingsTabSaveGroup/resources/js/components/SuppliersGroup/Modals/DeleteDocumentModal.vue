<template>
	<transition v-if="delete_document_modal_show" name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <slot v-bind:headerProps="{ close: close }" name="header">Delete Document</slot>
          </div>
          <div class="modal-body mb-4">
            <slot v-bind:item="item" name="body">
            </slot>
          </div>
          <div class="modal-footer">
            <slot v-bind:footerProps="{ close: close, item: item, deleteItem }" name="footer">
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
import 'izitoast/dist/css/iziToast.min.css'
import {
  mapGetters,
  mapActions
} from 'vuex'

export default {
  props: ['item', 'delete_document_modal_show', 'url', 'token', 'supplier_updated', 'field'],
	data: () => ({}),
  mounted() {
  },
  computed: {
    ...mapGetters({
      getShipmentSupplierGroupField: 'shipment/getShipmentSupplierGroupField'
    })
  },
  methods: {
    ...mapActions({
      uploadDocuments: 'shipment/uploadDocuments',
      setShipmentSupplierGroupField: 'shipment/setShipmentSupplierGroupField',
      fetchSuppliers: 'shipment/fetchShipmentSuppliers',
      deleteDocument: 'shipment/deleteDocument',
    }),
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
    deleteItem(item) {
      item.is_deleting_loading = true
      let {
        url
      } = this
      this.deleteDocument({
        item,
        url
      }).then(() => {
        iziToast.success({
          message: 'Document(s) successfully deleted.',
          displayMode: 1,
          position: "bottomRight",
          timeout: 3000,
        })
        this.close()
        this.resetSuppliers()
        
      }).catch(e => {
        iziToast.error({
          title: 'Error',
          message: 'An error occured while trying to delete the document.'
        })
      })

    },
    close() {
      this.$emit('update:delete_document_modal_show', false)
    }
  }
}
</script>