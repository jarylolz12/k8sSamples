<template>
  <modal @modal-close="handleClose">
    <form @submit.prevent="handleConfirm" class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 460px;">
      <div class="p-8">
        <heading :level="2" class="mb-6">{{ __('Delete Note') }}</heading>
        <p class="text-80 leading-normal">
            Are you sure you want to delete this note?
        </p>
      </div>

      <div class="bg-30 px-6 py-3 flex">
        <div class="ml-auto">
          <button type="button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">
              Cancel
          </button>

          <button id="confirm-delete-button" ref="confirmButton" type="submit" class="btn btn-default btn-danger">
              Delete
          </button>
        </div>
      </div>
    </form>
  </modal>
</template>

<script>
export default {
  methods: {
    handleClose() {
      this.$emit('close');
    },
    handleConfirm() {
      this.$emit('confirm');
    },
  },
  mounted() {
    this.$refs.confirmButton.focus();
  },
};
</script>
