<template>
    <v-dialog v-model="dialogWarning" max-width="500px" content-class="po-warning-dialog" persistent>
        <v-card class="po-warning-card">
            <v-card-title class="headline">
                <div class="warning-icon mt-3 mb-1">
                    <img src="../../../assets/icons/icon-delete.svg" alt="">
                </div>
            </v-card-title>

            <v-card-text style="padding-bottom: 15px;">
                <h2>Edit Partially Booked Order?</h2>
                <p>
                    Part of this purchase order has been partially booked. 
                    You can’t edit the order if the changes affect the booking. Do you want to proceed with editing?
                </p>
            </v-card-text>

            <v-card-actions class="po-warning-btn-wrapper">
                <v-btn class="btn-blue" text @click="allowEditPO">
                    <span>Edit PO</span>
                </v-btn>

                <v-btn class="btn-white" text @click="close">
                    <span>Dismiss</span>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'DeleteDialog',
    props: ['dialogData', 'poData'],
    mounted() {},
    methods: {
        allowEditPO() {
            this.$emit('editPo', this.poData)
        },
        close() {
            this.$emit('close')
        },
    },
    computed: {
        dialogWarning: {
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
    },
    updated() {}
}
</script>

<style lang="scss">
.po-warning-dialog {
    .po-warning-card {
        font-family: 'Inter-Regular', sans-serif;

        h2 {
            color: #4a4a4a; 
            font-size: 20px; 
            margin-bottom: 15px;
        }

        p {
            margin-bottom: 0; 
            font-size: 14px; 
            color: #4a4a4a;
            line-height: 20px;
        }

        .v-card__title {
            &.headline {
                border: none !important;
                padding-bottom: 12px !important;
            }
        }

        .v-card__text {
            margin-bottom: 0 !important;
        }

        .v-card__actions {
            position: unset !important;

            &.po-warning-btn-wrapper {
                border: none !important;
            }
        }
    }
}

@media screen and (max-width: 767px) {
    .v-dialog.po-warning-dialog {
        .v-card {
            .v-card__text {
                padding: 6px 16px 16px !important;
            }
        }
    }
}
</style>