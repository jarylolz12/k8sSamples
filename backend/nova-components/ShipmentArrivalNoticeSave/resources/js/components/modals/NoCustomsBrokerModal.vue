<template>
    <modal @modal-close="handleClose">
        <form
            @submit.prevent="handleConfirm"
            slot-scope="props"
            class="bg-white rounded-lg shadow-lg overflow-hidden"
            style="width: 460px"
        >
            <slot>
                <div class="p-8">
                    <heading :level="2" class="mb-6">{{ __('Customs broker not inputted.') }}</heading>
                    <p class="text-80 leading-normal">{{__('Please make sure to input the customs broker before sending the arrival notice.')}}</p>
                </div>
            </slot>

            <div class="bg-30 px-6 py-3 flex">
                <div class="ml-auto">
                    <button type="button" data-testid="cancel-button" dusk="cancel-general-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "NoCustomsBrokerModal",
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
        // this.$refs.confirmButton.focus()
    },
}
</script>

<style scoped>
.nova-button-shipment {
    border-block: aqua;
    border: var(--primary);
    border-style: solid;
    border-bottom-style: inherit;
    padding: 5px;
}
</style>
