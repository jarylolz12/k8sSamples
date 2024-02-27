<template>
  <div v-if="show" id="global-modal-id" class="modal-backdrop">
    <div class="flex flex-row fixed global-main-wrapper">
      <div v-if="getShowNotes" style="width: 30% !important;" class="in-slide-in-left w-1/2 notes-customer-wrapper mr-2">
        <slot name="notes"></slot>
      </div>
      <div v-if="!getShowNotes" style="width: 30%; height: auto;"></div>
      <div v-if="" style="position: unset !important; width: 70% !important;" class="modal w-1/2" data-aos="zoom-in" data-aos-once="true" data-aos-duration="500">
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
          <slot name="body" v-bind:props="{ servicesSectionValue: servicesSectionValue, qboCustomerInfo: qboCustomerInfo, isAddInvoice: isAddInvoice }">
          </slot>
        </section>
        <footer class="modal-footer">
          <slot name="footer"></slot>
        </footer>
      </div>
    </div>
  </div>
</template>
<style lang="scss" scoped>
@import './scss/GlobalModal.scss';
</style>
<script>
import AOS from 'aos'
import 'aos/dist/aos.css'
import "vue-loaders/dist/vue-loaders.css";
import {
  mapGetters,
  mapActions
} from 'vuex'

export default {
  props: [ 'show', 'item', 'servicesSectionValue', 'qboCustomerInfo', 'isAddInvoice'],
  created() {
    AOS.init()
  },
  computed: {
    ...mapGetters({
      getShowNotes: 'page/getShowNotes'
    })
  },
  methods: {
    handleClose(e) {
      this.$emit('close')
    },
  }
}
</script>