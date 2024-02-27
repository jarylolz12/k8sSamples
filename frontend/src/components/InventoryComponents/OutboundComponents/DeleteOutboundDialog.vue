<template>
    <v-dialog v-model="dialogCanceldelete" max-width="500px" content-class="item-delete-dialog">
        <v-card class="delete-dialog">
            <v-card-title class="headline">
                <div class="delete-icon mt-3 mb-1">
                    <img src="@/assets/icons/icon-delete.svg" alt="">
                </div>
            </v-card-title>

            <v-card-text style="padding-bottom: 15px;">
                <h2 v-if="!isWarehouseConnected">Delete Order</h2>
                <h2 v-else>Delete Outbound Order?</h2>
                <p v-if="!isWarehouseConnected">Are you sure you want to Delete order id 
                    {{ this.editedItem !== null && this.editedItem.order_id !== null ? 
                        this.editedItem.order_id : '--' 
                    }}?
                </p>  
                <p v-else>Do you want to delete the outbound order from draft? <br/> Once deleted, you can't recover it.</p> 
            </v-card-text>
            
            <v-card-actions class="delete-btn-wrapper">
                <v-btn v-if="!isWarehouseConnected" class="delete-btn" text @click="deleteConfirm">
                    <span v-if="loading">Deleting...</span>
                    <span v-if="!loading">Delete Order</span>
                </v-btn>
                <v-btn v-else class="delete-btn" text @click="deleteConfirm">
                    <span v-if="loading">Deleting...</span>
                    <span v-if="!loading">Delete</span>
                </v-btn>
                <v-btn v-if="!isWarehouseConnected" class="cancel-btn" text @click="close">Cancel</v-btn>
                <v-btn v-else class="cancel-btn" text @click="close">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'DeleteOutboundDialog',
    props: ['editedItemData', 'dialogData', 'loading','isWarehouseConnected'],
    mounted() {},
    updated() {},
    methods: {
        deleteConfirm() {
            this.$emit('cancel')
        },
        close() {
            this.$emit('close')
        },
    },
     computed: {
        dialogCanceldelete: {
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