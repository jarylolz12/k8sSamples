<template>
    <modal @modal-close="handleClose" role="dialog" tabindex="-1">
        <form @keydown="handleKeydown" @submit.prevent.stop="handleConfirm"
              class="bg-white rounded-lg shadow-lg overflow-hidden w-action">
            <div>
                <heading :level="2" class="border-b border-40 py-4 px-4">Confirm action</heading>
                <p class="text-80 px-4 my-4">Changing customer will remove associated PO/SO with this shipment, are you
                    sure you want to proceed? </p>
            </div>
            <div class="bg-30 px-6 py-3 flex">
                <div class="flex items-center ml-auto">
                    <button @click.prevent="handleClose" class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6"
                            type="button">
                        Cancel
                    </button>
                    <button class="btn btn-default btn-primary" type="submit">
                        Yes
                    </button>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
    export default {
        methods: {
            handleConfirm() {
                this.$emit('confirm')
            },
            handleClose() {
                this.$emit('close');
            },
        },
    }
</script>
