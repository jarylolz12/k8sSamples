<template>
    <v-dialog v-model="dialog" :max-width="typeof customWidth !== 'undefined' ? customWidth : '500px'" scrollable
        :content-class="className !== undefined ? `confirmation-wrapper-dialog ${className}` : 'confirmation-wrapper-dialog'" persistent>
        <v-card class="confirmation-dialog">
            <v-card-title class="headline">
                <div class="confirmation-icon mt-2">
                    <slot name="dialog_icon"></slot>
                </div>
            </v-card-title>

            <v-card-text style="padding-bottom: 15px;">
                <slot name="dialog_title"></slot>
                <slot name="dialog_content"></slot>
            </v-card-text>
            
            <v-card-actions class="confirmation-btn-wrapper">
                <slot name="dialog_actions"></slot>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'ConfirmDialog',
    props: ['dialogData', 'className', 'customWidth'],
    computed: {
        dialog: {
            get () {
                return this.dialogData
            },
            set (value) {
                if (!value) {
                    this.$emit('update:dialogData', false)
                } else {
                    this.$emit('update:dialogData', true)
                }
            }
        }
    },
    methods: {},
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';
@import '@/assets/scss/pages_scss/dialog/confirmationDialog.scss';
</style>