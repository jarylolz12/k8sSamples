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
            min-width="360px !important;"
            style="min-width: 360px !important;"
            @click="active = true">

            <template v-slot:activator="{ attrs }">
                <v-btn class="btn-white filters" v-bind="attrs" @click="showOrHideOption">
                    <!-- <p v-if="selectedWhCustomersLists.length > 0" style="width: 100%; max-width: 90%;">
                        <span v-for="(item, index) in selectedWhCustomersLists" :key="item.id">
                            <span class="caption" v-if="index === 0" style="color:#819FB2 !important">
                                Customer:
                            </span>

                            <v-chip v-if="index === 0" :class="selectedWhCustomersLists.length > 1 ? 'multiple-item' : 'single-item'">
                                <span>{{ item.name }}</span>
                            </v-chip>

                            <span v-if="index === 1" class="other-count">
                                <v-chip small class="count">+{{ selectedWhCustomersLists.length - 1 }}</v-chip> 
                            </span>

                            <button v-if="index === 0" @click="removeCustomerLists(item, 'all')" class="btn-icon-wrapper">
                                <v-icon>mdi-close</v-icon>
                            </button>
                        </span>
                    </p>

                    <span v-else style="color: #B4CFE0; font-size: 14px;">
                        Filter Warehouse Customer
                    </span> -->

                    <img src="@/assets/icons/filters.svg" alt="Filters" width="16px" height="16px">
                    <span class="ml-1" v-if="selectedWhCustomersLists.length > 0">
                        ({{ selectedWhCustomersLists.length }})
                    </span>
                </v-btn>
            </template>
            
            <v-card class="filters-card-wrapper" v-click-outside="clickOutsideFilter">
                <div class="filter-component-body" style="padding: 8px 0 !important;">
                    <v-list>
                        <v-list-item v-if="!loading" @click="setActiveTrue()">
                            <v-list-item-content class="pa-0">
                                <div class="second-filter-item-contents">
                                    <span v-for="(item, index) in selectedWhCustomersLists" 
                                        :key="index" class="wh-content">
                                        
                                        <span class="caption" style="color:#819FB2 !important" 
                                            v-if="index === 0">Customer:
                                        </span>
                                        
                                        <v-chip>
                                            <span class="name">{{ item.name }}</span>
                                            <button @click="removeCustomerLists(item, 'single')" class="btn-icon-wrapper">
                                                <v-icon>mdi-close</v-icon>
                                            </button>
                                        </v-chip>
                                    </span>

                                    <v-text-field 
                                        v-model="search" 
                                        placeholder="Search Warehouse Customer"
                                        @input="searchWarehouseCustomers"
                                        hide-details="auto"
                                        class="text-fields select-items inbound-search-filter"
                                        outlined
                                        autofocus>
                                    </v-text-field>
                                </div>
                            </v-list-item-content>
                        </v-list-item>

                        <v-divider v-if="!loading" class="warehouse-customer-divider"></v-divider>

                        <v-list-item v-for="(item) in warehouseCustomerItems" :key="item.id" @click="setActiveTrue()" style="min-height:60px;">
                            <v-list-item-content class="option-items" style="align-self:unset; padding:0; border-bottom:none;">
                                <v-checkbox
                                    v-model="selectedWhCustomersLists"
                                    hide-details="auto"
                                    class="mt-0"
                                    :value="item">
                                    
                                    <template v-slot:label>
                                        <p class="name mb-1" style="max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #4a4a4a;">{{ item.name }}</p>
                                    </template>
                                </v-checkbox>

                                <p style="color:#6d858f; font-size:12px;" class="ml-4 pl-4 mb-1">
                                    {{ item.address}}
                                </p>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item v-if="!loading && warehouseCustomerItems.length === 0" @click="setActiveTrue()" style="min-height:100px;">
                            <v-list-item-content class="option-items" style="align-self:unset; padding:0; border-bottom:none;">
                                <p style="color:#4a4a4a; font-size:14px;" class="mb-0 text-center">
                                    No available data
                                </p>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item v-if="loading" style="min-height:100px;" @click="setActiveTrue()">
                            <v-list-item-content class="option-items loading" style="padding:0; border-bottom:none;">
                                <div class="d-flex justify-center align-center">
                                    <v-progress-circular
                                        :size="40"
                                        color="#0171a1"
                                        indeterminate>
                                    </v-progress-circular>
                                </div>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>

                <!-- <v-autocomplete
                        class="text-fields select-items"
                        v-model="selectedWhCustomersLists"
                        :items="warehouseCustomerItems"
                        item-text="name"
                        item-value="id"
                        outlined
                        hide-details="auto"
                        placeholder="Select Warehouse Customer"
                        :menu-props="{ contentClass: 'product-lists-items filter-warehouse', bottom: true, offsetY: true }"
                        chips
                        deletable-chips
                        multiple>

                        <template v-slot:item="{ item }">
                            <v-list-item style="min-height:60px;">
                                <v-list-item-content class="option-items" 
                                    style="align-self:unset; padding:0; border-bottom:none;">
                                    <v-checkbox class="mt-0" 
                                        v-model="selectedWhCustomersLists" 
                                        hide-details="auto" 
                                        :value="item">     

                                        <template v-slot:label>
                                            <p class="name mb-1" style="max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #4a4a4a;">{{ item.name }}</p>
                                        </template>
                                    </v-checkbox>

                                    <p style="color:#6d858f; font-size:12px;" class="ml-4 pl-4 mb-1">
                                        {{ item.address}}
                                    </p>
                                </v-list-item-content>
                            </v-list-item>
                        </template>

                        <template v-slot:no-data>
                            <div class="option-items loading" v-if="loading"
                            style="
                                min-height: 100px; 
                                padding: 12px; 
                                display: flex; 
                                justify-content: center; 
                                align-items: center;
                            ">
                                <div class="sku-item">
                                    <v-progress-circular
                                        :size="40"
                                        color="#0171a1"
                                        indeterminate>
                                    </v-progress-circular>
                                </div>
                            </div>

                            <div class="option-items no-data" v-if="!loading" 
                                style="
                                    min-height: 40px; 
                                    padding: 12px; 
                                    font-size: 14px;
                                ">
                                <div class="sku-item"> No available data </div>
                            </div>
                        </template>
                    </v-autocomplete> -->
                </div>

                <v-card-actions v-if="warehouseCustomerItems.length > 0"
                    class="filters-card-actions d-flex align-center append-filter-button-inbound">
                    <v-btn class="btn-apply btn-blue"  @click="filterAllWarehouseCustomers">
                        Apply
                    </v-btn>

                    <v-btn class="btn-cancel btn-white" @click="cancelSelectingWarehouseCustomers">
                        Cancel
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-menu>
    </div>
</template>

<script>

export default {
    name: "FilterCustomers",
    props: ['menu', 'isMobile', 'customClass', 'selectedWhCustomersCopy', 'searchCustomerData', 'warehouseCustomerLists', 'loading', 'isActiveClicked'],
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
        }
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
        }
    },
    data: () => ({
        
    }),
    updated() {}
}
</script>

<style lang="scss">
// .filter-wrapper-dialog {
//     margin: 0;
//     height: 100%;
//     max-height: 100% !important;
//     background-color: #fff;

//     .v-card {
//         &.filter-dialog {
//             box-shadow: none !important;

//             .v-card__title {
//                 padding: 14px 16px !important;

//                 .headline {
//                     font-size: 16px !important;
//                 }
//             }

//             .v-card__text {
//                 padding: 14px 16px !important;
//                 margin-bottom: 65px;

//                 .filter-menu-title {
//                     margin-bottom: 5px;
//                     color: #819FB2;
//                     font-size: 10px;
//                     text-transform: uppercase;
//                     font-family: 'Inter-SemiBold', sans-serif;
//                 }

//                 .filter-component-body {
//                     .filter-radio-buttons {
//                         display: flex;
//                         justify-content: space-between;
//                         align-items: center;
//                         margin-bottom: 2px;

//                         label {
//                             font-size: 14px;
//                             color: #4a4a4a;
//                         }

//                         .filter-sorting-icons {
//                             i {
//                                 font-size: 20px;
//                             }

//                             button {
//                                 height: 25px;
//                                 width: 25px;

//                                 &.active {
//                                     color: #0171A1 !important;
//                                 }
                                
//                                 &:before {
//                                     display: none;
//                                 }
//                             }
//                         }
//                     }
//                 }

//                 .filter-component-sub-body {
//                     .col-sm-12.col-md-12.col-12 {
//                         .col-sm-12.col-md-6.col-12 {
//                             &:first-child {                        
//                                 padding-right: 4px !important;
//                             }

//                             &:last-child {                        
//                                 padding-left: 4px !important;
//                             }
//                         }

//                         .menu-subtitle {
//                             margin-bottom: 5px;
//                             color: #819FB2;
//                             font-size: 10px;
//                             text-transform: capitalize;
//                         }
//                     }
//                 }
//             }
//         }        
//     }
// }

.filter-content-menu {
    &.from-inbound-outbound-inventory {
        min-width: 360px !important;

        .filters-card-wrapper {
            .filter-component-body {
                padding: 8px 12px !important;

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
