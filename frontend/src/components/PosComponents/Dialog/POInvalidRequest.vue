<template>
    <v-dialog v-model="dialogWarning" max-width="500px" content-class="po-invalid-warning-dialog" persistent>
        <v-card class="po-invalid-warning-card">
            <v-card-title class="headline">
                <div class="warning-icon mt-3 mb-1">
                    <img src="@/assets/icons/icon-delete.svg" alt="">
                </div>
            </v-card-title>

            <v-card-text style="padding-bottom: 15px;">
                <h2>Invalid Update Request</h2>
                <p>
                    Some of the products or units of products of this PO are already included in bookings. 
                    You can't update the count for those products below the booked amount.
                </p>
            </v-card-text>

            <v-card-actions class="po-invalid-warning-btn-wrapper">
                <v-btn class="btn-blue" text @click="close">
                    <span>Understood</span>
                </v-btn>

                <v-btn class="btn-white" text @click="discardChanges">
                    <span>Discard Changes</span>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'POInvalidRequest',
    props: ['dialogData', 'poData'],
    mounted() {},
    methods: {
        discardChanges() {
            this.$emit('discardChanges')
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
.po-invalid-warning-dialog {
    .po-invalid-warning-card {
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

            &.po-invalid-warning-btn-wrapper {
                border: none !important;
            }
        }
    }
}

@media screen and (max-width: 767px) {
    .v-dialog.po-invalid-warning-dialog {
        .v-card {
            .v-card__text {
                padding: 6px 16px 16px !important;
            }
        }
    }
}
</style>