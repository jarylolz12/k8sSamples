<template>
    <div>
        <v-menu
            v-model="menuModel"
            :close-on-content-click="false"
            :nudge-width="200"
            :content-class="`filter-content-menu from-inbound-outbound-inventory product-lists-items warehouse_customers`"
            offset-y
            right bottom
            v-if="!isMobile"
            min-width="330px !important;"
            style="min-width: 330px !important;"
            @click="active = true">

            <template v-slot:activator="{ attrs }">
                <v-btn class="btn-white filters" v-bind="attrs" @click="showOrHideOption">
                    <img src="@/assets/icons/filters.svg" alt="Filters" width="16px" height="16px">
                    <span class="ml-1" v-if="selectedWhCustomersLists2.length > 0" 
                        style="color: #0084BD; font-family: 'Inter-Medium', sans-serif;">
                        {{ selectedWhCustomersLists2.length }}+
                    </span>
                </v-btn>
            </template>
            
            <v-card class="filters-card-wrapper" v-click-outside="clickOutsideFilter">
                <div class="filter-component-title">
                    <h3>Filters</h3>
                    <button class="btn-white cancel-close-dropdown" @click="cancelSelectingWarehouseCustomers">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </div>

                <div class="filter-component-body">
                    <div class="filter-customer-wrapper">
                        <p class="filter-body-title">Filter with Customer</p>
                        <v-autocomplete
                            v-model="selectedWhCustomersLists"
                            :items="warehouseCustomerItems"
                            outlined
                            item-text="name"
                            item-value="id"
                            return-object
                            chips
                            deletable-chips
                            multiple
                            :menu-props="{ contentClass: 'filter-warehouse', bottom: true, offsetY: true }"
                            hide-details="auto">

                            <template v-slot:selection="{ item }">
                                <v-chip>
                                    <span class="name">{{ item.name }}</span>
                                    <button @click.stop="removeCustomerLists(item, 'single')" class="btn-icon-wrapper">
                                        <v-icon>mdi-close</v-icon>
                                    </button>
                                </v-chip>
                            </template>

                            <template v-slot:item="{ item, on, attrs }">
                                <v-list-item v-on="on" v-bind="attrs" #default="{ active }">
                                    <v-list-item-action>
                                        <v-checkbox :input-value="active"></v-checkbox>
                                    </v-list-item-action>

                                    <v-list-item-content>
                                        <v-list-item-title>           
                                            <p class="name mb-1 font-medium">{{ item.name }}</p>           
                                            <p class="address mb-1">{{ item.address}}</p>
                                        </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-autocomplete>
                    </div>
                </div>

                <v-card-actions v-if="warehouseCustomerItems.length > 0"
                    class="filters-card-actions d-flex align-center append-filter-button-inbound">
                    <v-btn class="btn-apply btn-blue"  @click="filterAllWarehouseCustomers" :disabled="selectedWhCustomersLists.length === 0">
                        Apply Filters
                    </v-btn>

                    <v-btn class="btn-cancel btn-white" @click="clearAll">
                        Clear All
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-menu>
    </div>
</template>

<script>

export default {
    name: "FilterCustomers",
    props: ['menu', 'isMobile', 'customClass', 'selectedWhCustomersCopy', 'searchCustomerData', 'warehouseCustomerLists', 'loading', 'isActiveClicked', 'selectedWhCustomers'],
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
        selectedWhCustomersLists: {
            get() {
                return this.selectedWhCustomersCopy
            },
            set(value) {
                this.$emit('update:selectedWhCustomersCopy', value)
            }
        },
        search: {
            get() {
                return this.searchCustomerData
            },
            set(value) {
                this.$emit('update:searchCustomerData', value)
            }
        },
        warehouseCustomerItems: {
            get() {
                return this.warehouseCustomerLists
            },
            set(value) {
                this.$emit('update:warehouseCustomerLists', value)
            }
        },
        active: {
            get() {
                return this.isActiveClicked
            }, 
            set(value) {
                this.$emit('update:isActiveClicked', value)
            }
        },
        selectedWhCustomersLists2: {
            get() {
                return this.selectedWhCustomers
            },
            set(value) {
                this.$emit('update:selectedWhCustomers', value)
            }
        },
    },
    methods: {
        showOrHideOption() {
            this.$emit('onClickFilter')
            this.removeCustomerListsEmptyOnChange()
        },
        filterAllWarehouseCustomers() {
            this.$emit('filterAllWarehouseCustomers')
        },
        cancelSelectingWarehouseCustomers() {
            this.$emit('cancelSelectingWarehouseCustomers')
        },
        searchWarehouseCustomers() {
            this.$emit('searchWarehouseCustomers')
        },
        removeCustomerLists(item, removeIs) {
            this.$emit('removeCustomerLists', item, removeIs)
        },
        removeCustomerListsEmptyOnChange() {
            this.$emit('removeCustomerListsEmptyOnChange')
        },        
        clickOutsideFilter() {
            this.$emit('clickOutside')
        },
        setActiveTrue() {
            this.active = true
            this.removeCustomerListsEmptyOnChange()
        },
        clearAll() {
            this.$emit('clearAllFilter')
            this.selectedWhCustomersLists = []
            this.selectedWhCustomersLists2 = []
        }
    },
    data: () => ({ }),
    updated() {}
}
</script>

<style lang="scss">
.filter-content-menu {
    &.from-inbound-outbound-inventory {
        min-width: 360px !important;

        .filters-card-wrapper {
            .filter-component-title {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 16px;
                border-bottom: 1px solid #F1F6FA;

                h3 {
                    margin-bottom: 0;
                    font-size: 16px;
                    color: #4a4a4a !important;
                }

                button {
                    padding: 0 !important;
                    border: none !important;
                    
                    i {
                        color: #0171A1 !important;
                    }
                }
            }

            .filter-component-body {
                padding: 16px !important;

                .filter-body-title {
                    font-size: 10px;
                    color: #819FB2;
                    font-family: "Inter-SemiBold", sans-serif;
                    text-transform: uppercase;
                    margin-bottom: 5px;
                }

                .v-input {
                    .v-input__control {
                        .v-input__slot {
                            .v-select__slot {
                                .v-select__selections {
                                    min-height: 48px;

                                    .v-chip {
                                        background: #f7f7f7;
                                        background-color: #f7f7f7;
                                        height: 24px;
                                        border-radius: 4px;
                                        border: 1px solid #EBF2F5;
                                        padding: 0 8px;
                                        font-size: 12px;
                                        margin: 2px 4px 4px 0;

                                        .v-chip__content {
                                            color: #4a4a4a;

                                            .name {
                                                white-space: nowrap;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                                max-width: 275px;
                                            }

                                            .btn-icon-wrapper {
                                                height: auto;
                                                width: auto;
                                                min-width: unset;
                                                box-shadow: none;
                                                padding: 0;
                                                padding-left: 6px;
                                                display: flex;

                                                i {
                                                    font-size: 18px;
                                                    color: #819FB2;
                                                }
                                            }
                                        }
                                    }   

                                    .mdi-close-circle::before {
                                        content: "\F0156";
                                        color: #6D858F;
                                        font-size: 16px;
                                    }
                                }

                                .v-input__append-inner {
                                    display: none;
                                }
                            }
                        }
                    }

                    &.v-autocomplete:not(.v-input--is-focused).v-select--chips input { 
                        max-height: 25px !important; 
                    }
                }

                .v-list {
                    .v-list-item{
                        &:hover {
                            &::before {
                                opacity: 0;
                            }
                        }
                    }
                }

                label {
                    color: #4a4a4a;
                    font-size: 14px;
                }

                .v-input {
                    margin-bottom: 4px;

                    i {
                        &.mdi-checkbox-blank-outline {
                            width: 26px !important;
                            // height: 26px !important;
                            padding: 3px !important;
    
                            &::before {
                                content: '' !important;
                                background-image: url('/checkbox-empty-icon-1.svg') !important;
                                background-position: center !important;
                                background-repeat: no-repeat !important;
                                background-size: cover !important;
                                width: 16px !important;
                                height: 16px !important;
                            }
                        }
    
                        &.mdi-checkbox-marked {
                            width: 26px !important;
                            // height: 26px !important;
                            padding: 3px !important;
    
                            &::before {
                                content: '' !important;
                                background-image: url('/checkbox-filled-icon-1.svg') !important;
                                background-position: center !important;
                                background-repeat: no-repeat !important;
                                background-size: cover !important;
                                width: 16px !important;
                                height: 16px !important;
                            }
                        }
                    }

                    &.v-input--is-disabled {
                        i {        
                            &.mdi-checkbox-marked {        
                                &::before {
                                    content: '' !important;
                                    background-image: url('/checkbox-checked-disabled.svg') !important;
                                    background-position: center !important;
                                    background-repeat: no-repeat !important;
                                    background-size: cover !important;
                                    width: 16px !important;
                                    height: 16px !important;
                                }
                            }
                        }
                    }
                }
            } 
            
            .filters-card-actions {
                padding: 12px 16px !important;

                button {
                    height: 35px !important;
                }

                &.append-filter-button-inbound {
                    position: sticky;
                    bottom: 0;
                    width: 100%;
                    display: flex;
                    justify-content: flex-start;
                    background-color:#fff;  
                    padding: 8px 16px;
                    border-top: 1px solid #F1F6FA;
                }
            }

            .v-ripple__container {
                display: none;
            }
        }

        .v-divider {
            border-color: #EBF2F5 !important;
        }
    }    
}
</style>
