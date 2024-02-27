<template>
  <modal @modal-close="handleClose">
    <form class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 460px;">
      <div class="p-8">
        <heading :level="2" class="mb-6">{{ __('Alert') }}</heading>
        <p class="text-80 leading-normal">
            MBL Number Must Be Removed Before Canceling a Shipment
        </p>
      </div>

      <div class="bg-30 px-6 py-3 flex">
        <div class="ml-auto">
          <button type="button" ref="closeButton" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">
              Back
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
    this.$refs.closeButton.focus();
  },
};
</script>
