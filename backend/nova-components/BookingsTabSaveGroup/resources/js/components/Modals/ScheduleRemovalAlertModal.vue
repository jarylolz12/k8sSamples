<template>
    <modal @modal-close="handleClose">
        <form
            slot-scope="props"
            class="bg-white rounded-lg shadow-lg overflow-hidden relative"
            style="width: 460px;"
        >
            <div class="p-8">
                <heading :level="2" class="mb-6">Please Remove the MBL Number</heading>
                <a @click.prevent="handleClose" href="#" ref="closeButton" class="close-icon">X</a>
                <p class="text-80 leading-normal">
                    Please remove the MBL Number.
                </p>
                <p class="text-80 leading-normal">
                    In order to deselect this schedule, the MBL must be removed first.
                </p>
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
        },
        mounted() {
            this.$refs.closeButton.focus();
        },
    };
</script>
<style type="text/css" scoped>
    .close-icon{
        position: absolute;
        top: 20px;
        right: 20px;
        color: #000;
        text-decoration: none;
    }
</style>
