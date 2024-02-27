<template>
    <modal @modal-close="handleClose">
        <form
            slot-scope="props"
            class="bg-white rounded-lg shadow-lg overflow-hidden relative"
            style="width: 460px;"
        >
            <div class="p-8">
                <heading :level="2" class="mb-6">Please Select a Schedule.</heading>
                <a @click.prevent="handleClose" href="#" ref="closeButton" class="close-icon">X</a>
                <p class="text-80 leading-normal">
                    Please select the schedule that corresponds to this MBL#
                </p>
                <p class="text-80 leading-normal">
                    We will need a selected schedule in order to be able to save the MBL.
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
