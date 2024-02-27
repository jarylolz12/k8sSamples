<template>
  <modal @modal-close="handleClose">
    <slot >
      <div class="p-8">
        <heading class="mb-6">Heading</heading>
        <p class="text-80 leading-normal">
          wew
        </p>
      </div>
    </slot>
    <div class="bg-30 px-6 py-3 flex">
      <div class="ml-auto">
        <button
          type="button"
          data-testid="cancel-button"
          dusk="cancel-delete-button"
          @click.prevent="handleClose"
          class="btn text-80 font-normal h-9 px-3 mr-3 btn-link"
        >
         sdf
        </button>
        <button
          id="confirm-delete-button"
          ref="confirmButton"
          data-testid="confirm-button"
          type="submit"
          class="btn btn-default btn-danger"
        >
         sdfdsf
        </button>
      </div>
    </div>
  </modal>
</template>

<script>
export default {
  name: 'DuplicateModal',
  methods: {
    handleClose() {
      this.$emit('close')
    },

    handleConfirm() {
      this.$emit('confirm')
    },
  },

  /**
   * Mount the component.
   */
  mounted() {
    this.$refs.confirmButton.focus()
  },
}
</script>
