<template>
  <div v-if="show" class="modal-backdrop">
    <div class="modal">
      <header class="modal-header">
        <slot name="header"></slot>
        <button
          type="button"
          class="btn-close"
          @click.stop="handleClose"
        >
          x
        </button>
      </header>
      <section class="modal-body">
        <slot name="body">
        </slot>
      </section>
      <footer class="modal-footer">
        <slot v-bind:footerProps="{ deleteItem: deleteItem, item: item, cancel: cancel, loading: getDeleteBillAttachmentLoading }" name="footer">
        </slot>
      </footer>
    </div>
  </div>
</template>
<style lang="scss" scoped>
@import './scss/DeleteAttachmentModal.scss';
</style>
<script>
import iziToast from 'izitoast'
import 'izitoast/dist/css/iziToast.min.css'
import {
  mapGetters,
  mapActions
} from 'vuex'

export default {
  props: [ 'show', 'item' ],
  computed: {
    ...mapGetters({
      getDeleteBillAttachmentLoading: 'billCharges/getDeleteBillAttachmentLoading'
    })
  },
  methods: {
    ...mapActions({
      deleteBillAttachment: 'billCharges/deleteBillAttachment',
      fetchBillAttachments: 'billCharges/fetchBillAttachments'
    }),
    cancel() {
      this.$emit('close')
    },
    deleteItem(item) {
      this.deleteBillAttachment(item).then( () => {
        iziToast.success({
          theme: 'dark',
          message: `<h4 style="font-weight: 500 !important; color: #fff !important;">Attachment deleted successfully.</h4>`,
          backgroundColor: '#16B442',
          messageColor: '#ffffff',
          iconColor: '#ffffff',
          progressBarColor: '#ADEEBD',
          displayMode: 1,
          position: 'bottomCenter',
          timeout: 3000,
        })

        //fetch bill attachments
        this.fetchBillAttachments({
          shipment_id: item.shipment_id,
          bill_id: item.bill_id
        })

        this.$emit('close')
      }).catch(e => {
        console.log('e')
        iziToast.error({
          title: 'An error occured',
          message: 'An error occured.',
          displayMode: 1,
          position: 'bottomCenter',
          timeout: 3000
        });
      })
      
    },
    handleClose(e) {
      this.$emit('close')
    },
  }
}
</script>