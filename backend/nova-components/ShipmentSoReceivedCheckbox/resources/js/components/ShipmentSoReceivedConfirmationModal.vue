<template>
  <modal @modal-close="handleClose">
    <form class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 460px;">
      <div class="p-8">
            <heading :level="2" class="mb-6">{{ __('Alert') }}</heading>
            <div class="text-80 leading-normal" v-if="office_from.name === 'Shifl India'">
                <p class="mb-1">If you've received the Shipping Order, then the agent must have confirmed.</p>
                <p class="mb-3">Please mark the <strong>Agent Confirmation</strong> by the schedule on the booking tab before marking down that this shipment has SO received.</p>
                <p>यदि आपको शिपिंग ऑर्डर प्राप्त हो गया है, तो पहले बुकिंग टैब पर शेड्यूल द्वारा एजेंट पुष्टिकरण को चिह्नित करें.</p>
            </div>
            <div class="text-80 leading-normal" v-else-if="office_from.name === 'Shifl China'">
                <p class="mb-1">If you've received the Shipping Order, please mark Agent Confirmation by the schedule on the booking tab.</p>
                <p class="mb-3">Please mark the <strong>Agent Confirmation</strong> by the schedule on the booking tab before marking down that this shipment has SO received.</p>
                <p>如果您收到运输订单，请首先在“预订”选项卡上按时间表标记“代理确认</p>
            </div>
            <div class="text-80 leading-normal" v-else>
                <p class="mb-1">If you've received the Shipping Order, please mark Agent Confirmation by the schedule on the booking tab.</p>
                <p>Please mark the <strong>Agent Confirmation</strong> by the schedule on the booking tab before marking down that this shipment has SO received.</p>
            </div>
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
    props: ['office_from'],
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
