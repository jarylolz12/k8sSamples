<template>
    <v-dialog v-model="dialog" max-width="560px" content-class="upload-documents" persistent>
        <v-card class="submit-document">
            <v-card-text>
                <img src="@/assets/icons/loading.svg" width="45" height="45">
                <h3>Submitting Documents...</h3>
                <p>Selected documents are being submitted to Shifl. Please wait a few moment.</p>
                <v-btn class="btn-white btn-cancel" text @click="cancelSubmission">
                    Cancel Submission
                </v-btn>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
//import { mapActions } from 'vuex';
import globalMethods from '../../../utils/globalMethods'

export default {
    name: 'SubmitDocumentsDialog',
    props: ['dialogData', 'submit_item', 'selected', 'shipment_id'],
    components: { },
    data: () => ({
        isMobile: false,
    }),
    computed: {
        dialog: {
            get () {
                return this.dialogData
            },
        },
    },
    mounted() {
    },
    methods: {
        ...globalMethods,
        cancelSubmission() {
            this.$emit('cancelSubmission')
            this.$emit('close')
        },
        close() {
            this.$emit('close')
        },
    },
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/shipment/shipmentSubmit.scss';
</style>
