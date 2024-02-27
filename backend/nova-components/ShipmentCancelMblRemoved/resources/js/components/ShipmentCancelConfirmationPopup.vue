<template>
    <modal
        data-testid="shipment-cancel-confirmation-popup"
        tabindex="-1"
        role="dialog"
        :closes-via-backdrop="canLeave"
        @modal-close="handleClose"
    >
        <form
            autocomplete="off"
            @keydown="handleKeydown"
            @submit.prevent.stop="handleConfirm"
            class="bg-white rounded-lg shadow-lg overflow-hidden w-action"
        >
            <div>
                <p class="text-80 px-8 my-8">
                    MBL Number Must Be Removed Before Canceling a Shipment
                </p>
            </div>

            <div class="bg-30 px-6 py-3 flex">
                <div class="flex items-center ml-auto">
                    <button
                        dusk="cancel-action-button"
                        type="button"
                        @click.prevent="handleClose"
                        class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6"
                    >
                        Back
                    </button>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
    export default {
        data: () => ({
            canLeave: true,
        }),

        methods: {
            /**
             * Stop propogation of input events unless it's for an escape or enter keypress
             */
            handleKeydown(e) {
                if (['Escape', 'Enter'].indexOf(e.key) !== -1) {
                    return
                }

                e.stopPropagation()
            },

            /**
             * Close the modal.
             */
            handleClose() {
                this.$emit('close')
            },
        },
    }
</script>
