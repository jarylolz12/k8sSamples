<template>
    <v-dialog v-model="dialog" max-width="500px" content-class="upload-documents delete-document" persistent>
        <v-card class="submit-document pt-0">
            <v-card-text>
                <img src="@/assets/icons/icon-delete.svg" width="45" height="45">
                <h3>Delete Document{{(delete_item.length==0) ? 's' : ''}}</h3>
                <p v-if="delete_item.length==0">Do you want to delete the selected documents?</p>
                <p v-if="delete_item.length>0">Do you want to delete this document?</p>

                <div class="action_btn">
                    <v-btn class="btn-blue" text @click.stop="deleteItems" :disabled="loading">
                        {{
                            loading ? 'Deleting...' : 'Delete'
                        }}
                    </v-btn> 
                    <v-btn class="btn-white" text @click="close" :disabled="loading">
                        Cancel
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import globalMethods from '../../../utils/globalMethods'
export default {
    name: 'DeleteDocumentsDialog',
    props: ['dialogData', 'selected', 'shipment_id', 'delete_item'],
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
        ...mapGetters({
            loading: 'documents/getDeleteMultipleLoading'
        })
    },
    mounted() {},
    methods: {
        ...mapActions({
            deleteDocuments: 'documents/deleteDocuments',
        }),
        ...globalMethods,
        async deleteItems() {
            let items = []

            if ( this.delete_item.length > 0 ) {
                this.delete_item.map(delete_item => {
                    items.push(delete_item.id)
                })
            } else {
                this.selected.map(s => {
                    items.push(s.id)
                })
            }
            
            let {
                shipment_id
            } = this
            try {
                await this.$store.dispatch('documents/deleteDocuments',items)
                this.notificationCustom('Documents were successfully deleted.')
                this.$emit('fetchDocuments', {
                    shipment_id
                })
                this.$store.dispatch('fetchShipmentDetails', shipment_id)
                this.close()
            } catch(e) {
                this.notificationError('An error occured while trying to delete the documents. Please refresh the page and try again.')
                console.log(e)
            }
            
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
