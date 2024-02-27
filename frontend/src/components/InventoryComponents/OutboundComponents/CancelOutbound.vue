<template>
    <v-dialog v-model="dialogCancel" max-width="500px" content-class="item-delete-dialog">
        <v-card class="delete-dialog">
            <v-card-title class="headline">
                <div class="delete-icon mt-3 mb-1">
                    <img src="@/assets/icons/icon-delete.svg" alt="">
                </div>
            </v-card-title>

            <v-card-text style="padding-bottom: 15px;">
                <h2>Cancel Order</h2>
                <p>Are you sure you want to cancel order id 
                    {{ this.editedItem !== null && this.editedItem.order_id !== null ? 
                        this.editedItem.order_id : '--' 
                    }}?
                </p>   
            </v-card-text>
            
            <v-card-actions class="delete-btn-wrapper">
                <v-btn class="delete-btn" text @click="cancelConfirm">
                    <span v-if="loading">Cancelling...</span>
                    <span v-if="!loading">Cancel Order</span>
                </v-btn>
                <v-btn class="cancel-btn" text @click="close">Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'CancelDialog',
    props: ['editedItemData', 'dialogData', 'loading'],
    mounted() {},
    updated() {},
    methods: {
        cancelConfirm() {
            this.$emit('cancel')
        },
        close() {
            this.$emit('close')
        },
    },
     computed: {
        dialogCancel: {
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
        },
        editedItem: {
            get () {
                return this.editedItemData
            },
            set (value) {
                console.log(value);
            }
        }
    },
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/dialog/deleteDialog.scss';
</style>