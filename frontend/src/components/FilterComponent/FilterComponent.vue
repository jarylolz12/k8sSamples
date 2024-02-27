<template>
    <div>
        <v-menu
            v-model="menuModel"
            :close-on-content-click="false"
            :nudge-width="200"
            offset-y
            bottom 
            :left="alignment === 'left' ? true : false"
            :right="alignment === 'right' ? true : false"
            :content-class="`filter-content-menu ${customClass}`"
            v-if="!isMobile"
            @click="setActiveCustomized()">

            <template v-slot:activator="{ attrs }">
                <v-btn class="btn-white filters mr-0" v-bind="attrs" @click="showOrHideOption">
                    <slot name="filter_title"></slot>
                </v-btn>
            </template>
            
            <v-card class="filters-card-wrapper" v-click-outside="onClickOutsideCustomize">
                <slot name="filter_body"></slot>

                <v-divider></v-divider>

                <v-card-actions class="filters-card-actions">
                    <slot name="filter_actions"></slot>
                </v-card-actions>
            </v-card>
        </v-menu>

        <div v-if="isMobile">
            <v-btn class="btn-white filters mr-0" @click="showOrHideOption">
                <slot name="filter_title"></slot>
            </v-btn>

            <v-dialog v-model="menuModel" max-width="500px" :content-class="`filter-wrapper-dialog ${customClass}`">
                <v-card class="filter-dialog">
                    <v-card-title>                        
                        <slot name="filter_title_mobile"></slot>
                    </v-card-title>

                    <v-card-text style="padding-bottom: 15px;">
                        <slot name="filter_body"></slot>
                    </v-card-text>
                    
                    <v-card-actions class="filter-btn-wrapper">
                        <slot name="filter_actions"></slot>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>        
    </div>
</template>

<script>

export default {
    name: "FilterComponent",
    props: ['menu', 'isMobile', 'customClass', 'isCustomizedClicked', 'alignment'],
    components:{},
    computed: {
        menuModel: {
            get() {
                return this.menu
            },
            set(value) {
                this.$emit('update:menu', value)
            }
        },
        active: {
            get() {
                return this.isCustomizedClicked
            }, 
            set(value) {
                this.$emit('update:isCustomizedClicked', value)
            }
        }
    },
    methods: {
        showOrHideOption() {
            this.$emit('onClickCustomize')
        },
        onClickOutsideCustomize() {
            this.$emit('onClickOutsideCustomize')
        },
        setActiveCustomized() {
            this.active = true
        }
    },
    data: () => ({ }),
    updated() {}
}
</script>

<style lang="scss">
.filter-content-menu {
    max-width: 320px !important;
    min-width: unset !important;

    .filters-card-wrapper {
        .filter-menu-title {
            margin-bottom: 5px;
            color: #819FB2;
            font-size: 10px;
            text-transform: uppercase;
            font-family: 'Inter-SemiBold', sans-serif;
        }

        .filter-component-body {
            padding: 16px 16px 0;

            .filter-radio-buttons {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2px;

                label {
                    font-size: 14px;
                    color: #4a4a4a;
                }

                .filter-sorting-icons {
                    i {
                        font-size: 20px;
                    }

                    button {
                        height: 25px;
                        width: 25px;

                        &.active {
                            color: #0171A1 !important;
                        }
                        
                        &:before {
                            display: none;
                        }
                    }
                }
            }
        }

        .filter-component-sub-body {
            padding: 0 16px 16px;

            .col-sm-12.col-md-12.col-12 {
                .col-sm-12.col-md-6.col-12 {
                    &:first-child {                        
                        padding-right: 4px !important;
                    }

                    &:last-child {                        
                        padding-left: 4px !important;
                    }
                }

                .menu-subtitle {
                    margin-bottom: 5px;
                    color: #819FB2;
                    font-size: 10px;
                    text-transform: capitalize;
                }
            }
        }

        .v-input {
            .v-input__control {
                .v-input__slot {
                    background-color: #fff !important;
                }

                .v-input__append-inner {
                    .v-input__icon {
                        button {
                            font-size: 18px;
                            color: #F93131 !important;
                        }
                    }
                }
            }
        }

        .filters-card-actions {
            padding: 12px 16px;
        }
    }
}

.filter-wrapper-dialog {
    margin: 0;
    height: 100%;
    max-height: 100% !important;
    background-color: #fff;

    .v-card {
        &.filter-dialog {
            box-shadow: none !important;

            .v-card__title {
                padding: 14px 16px !important;

                .headline {
                    font-size: 16px !important;
                }
            }

            .v-card__text {
                padding: 14px 16px !important;
                margin-bottom: 65px;

                .filter-menu-title {
                    margin-bottom: 5px;
                    color: #819FB2;
                    font-size: 10px;
                    text-transform: uppercase;
                    font-family: 'Inter-SemiBold', sans-serif;
                }

                .filter-component-body {
                    .filter-radio-buttons {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 2px;

                        label {
                            font-size: 14px;
                            color: #4a4a4a;
                        }

                        .filter-sorting-icons {
                            i {
                                font-size: 20px;
                            }

                            button {
                                height: 25px;
                                width: 25px;

                                &.active {
                                    color: #0171A1 !important;
                                }
                                
                                &:before {
                                    display: none;
                                }
                            }
                        }
                    }
                }

                .filter-component-sub-body {
                    .col-sm-12.col-md-12.col-12 {
                        .col-sm-12.col-md-6.col-12 {
                            &:first-child {                        
                                padding-right: 4px !important;
                            }

                            &:last-child {                        
                                padding-left: 4px !important;
                            }
                        }

                        .menu-subtitle {
                            margin-bottom: 5px;
                            color: #819FB2;
                            font-size: 10px;
                            text-transform: capitalize;
                        }
                    }
                }
            }
        }        
    }
}
</style>
