<template>
    <modal @modal-close="handleClose">
        <form
            @submit.prevent="handleConfirm"
            slot-scope="props"
            class="bg-white rounded-lg shadow-lg overflow-hidden"
            style="width: 460px;"
        >
            <div class="p-8">
                <heading :level="2" class="mb-6">Would you like to Proceed?</heading>
                <p class="text-80 leading-normal">
                    We cannot have two selected schedules.
                </p>
                <p class="text-80 leading-normal">
                    Therefore, selecting a new schedule will unschedule the existing schedule.
                </p>
            </div>

            <div class="bg-30 px-6 py-3 flex">
                <div class="ml-auto">
                    <button
                        id="confirm-delete-button"
                        ref="confirmButton"
                        data-testid="confirm-button"
                        :processing="working"
                        :disabled="working"
                        @click.prevent="handleConfirm"
                        class="btn btn-default"
                        style="box-shadow: none; color: #4099de; text-shadow: none;"
                    >
                        {{ __('Confirm') }}
                    </button>
                    <button type="button" ref="closeButton" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
    export default {
        data: () => ({
            working: false,
        }),
        methods: {
            handleClose() {
                this.$emit('close');
                this.working = false
            },
            handleConfirm() {
                this.$emit('confirm');
                this.working = true
            },
        },
        mounted() {
            this.$refs.closeButton.focus();
        },
    };
</script>
