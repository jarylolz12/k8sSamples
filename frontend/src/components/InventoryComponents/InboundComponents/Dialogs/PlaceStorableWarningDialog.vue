<template>
    <v-dialog v-model="dialogWarning" max-width="500px" content-class="storable-warning-dialog" persistent>
        <v-card class="storable-warning-card">
            <v-card-title class="headline">
                <div class="warning-icon mt-3 mb-1">
                    <img src="@/assets/icons/icon-delete.svg" alt="">
                </div>
            </v-card-title>

            <v-card-text style="padding-bottom: 15px;">
                <h2>Confirm Placement</h2>
                <p>
                    The selected location already has some storable units in it. 
                    Do you want to place the storable unit ‘{{ storableData.label }}’ in the same location?
                </p>
            </v-card-text>

            <v-card-actions class="storable-warning-btn-wrapper">
                <v-btn class="btn-blue" text @click="confirmPlacement">
                    <span> Confirm Placement </span>
                </v-btn>

                <v-btn class="btn-white" text @click="close">
                    <span>Cancel</span>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
    name: 'DeleteDialog',
    props: ['dialogData', 'storableData'],
    mounted() {},
    methods: {
        confirmPlacement() {
            this.$emit('confirmPlacement', this.storableData)
            this.close()
        },
        close() {
            this.$emit('close')
        },
    },
    computed: {
        ...mapGetters({
            getPlaceStorableLoading: 'inbound/getPlaceStorableLoading'
        }),
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
.storable-warning-dialog {
    .storable-warning-card {
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

            &.storable-warning-btn-wrapper {
                border: none !important;
            }
        }
    }
}

@media screen and (max-width: 767px) {
    .v-dialog.storable-warning-dialog {
        .v-card {
            .v-card__text {
                padding: 6px 16px 16px !important;
            }
        }
    }
}
</style>