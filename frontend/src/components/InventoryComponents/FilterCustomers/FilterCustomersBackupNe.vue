<template>
    <div>
        <v-menu
            v-model="menuModel"
            :close-on-content-click="false"
            :nudge-width="220"
            :content-class="`filter-content-menu from-inbound-outbound-inventory product-lists-items warehouse_customers `"
            offset-y
            right bottom
            v-if="!isMobile"
            min-width="350px !important;"
            style="min-width: 350px !important;"
            @click="active = true">

            <template v-slot:activator="{ attrs }">
                <v-btn class="btn-white filters" v-bind="attrs" @click="showOrHideOption">
                    <img src="@/assets/icons/filters.svg" alt="Filters" width="16px" height="16px">
                    <span class="ml-1" v-if="selectedWhCustomersLists.length > 1" 
                        style="color: #0084BD; font-family: 'Inter-Medium', sans-serif;">
                        {{ selectedWhCustomersLists.length -1}}+
                    </span>
                    <span class="ml-1" v-if="selectedWhCustomersLists.length == 1" 
                        style="color: #0084BD; font-family: 'Inter-Medium', sans-serif;">
                        {{ selectedWhCustomersLists.length }}
                    </span>
                </v-btn>
            </template>
            
            <v-card scrollable  class="filters-card-wrapper elevation-0" v-click-outside="clickOutsideFilter" >
                <div class="filter-component-title">
                    <h3>Filters</h3>
                    <button class="btn-white cancel-close-dropdown" @click="cancelSelectingWarehouseCustomers">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </div>

                <div class="filter-component-body">
                    <div class="filter-customer-wrapper">
                        <p class="filter-body-title">Filter with Customer</p>
                        <div v-click-outside="clickOutsideFilterDropDown">
						<div class="border-around-text" @click="setActiveTrue()">
							<v-text-field
                                class="text-fields"
								@input="searchWarehouseCustomers"
								@focus="opendialog"
								v-model="search"
            				>
              					<template v-slot:prepend-inner>
                                        <v-chip-group
                                            column
                                        >
              				    		    <v-chip v-for="(item , index) in selectedWhCustomersLists" :key="index">
                                                <span class="name">{{ item.name }}</span>
                                                <button @click="removeCustomerLists(item, 'single')" class="btn-icon-wrapper">
                                                    <v-icon>mdi-close</v-icon>
                                                </button>
                                            </v-chip>
                                        </v-chip-group>
									
              					</template>
                            </v-text-field>
						</div>
						<div  v-if="opendropdowndialog">
                            <v-card-text style="height: 350px; overflow-x: scroll;padding:0;margin:0">
							<v-list class="elevation-0">
							<v-list-item v-for="(item) in warehouseCustomerLists" :key="item.id" @click="setActiveTrue()" style="min-height:60px;">
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
							</v-list>
                            </v-card-text>
						</div>
					</div>
                    </div>
                </div>

                <v-card-actions v-if="warehouseCustomerItems.length > 0 && !opendropdowndialog"
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
    props: ['menu', 'isMobile', 'customClass', 'selectedWhCustomersCopy', 'searchCustomerData', 'warehouseCustomerLists', 'loading', 'isActiveClicked', 'selectedWhCustomers','openDialog'],
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
        opendropdowndialog:{
            get() {
                return this.openDialog
            }, 
            set(value) {
                this.$emit('update:openDialog', value)
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
        },
        opendialog(){
            this.$emit('opendialog')
        },
        clickOutsideFilterDropDown(){
            this.opendropdowndialog =false
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
        overflow: hidden !important;

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
                padding: 16px 16px 0 16px !important;

                .filter-body-title {
                    font-size: 10px;
                    color: #819FB2;
                    font-family: "Inter-SemiBold", sans-serif;
                    text-transform: uppercase;
                    margin-bottom: 5px;
                }
                
                
                
                .border-around-text {

                .v-input {
                    .v-input__control {
                        .v-input__slot {
                                    
                                    border:  1px solid #819FB2;
                                    border-radius:4px ;
                                    min-height: 55px ;
                                    
                                    .v-chip {
                                        background: #f7f7f7;
                                        background-color: #f7f7f7;
                                        height: 24px;
                                        border-radius: 4px;
                                        border: 1px solid #EBF2F5;
                                        padding: 0 8px ;
                                        font-size: 12px;
                                        margin: 8px 4px 4px 4px;

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
                               

                                .v-input__append-inner {
                                    display: none;
                                }
                       
                        }
                    }

                    &.v-autocomplete:not(.v-input--is-focused).v-select--chips input { 
                        max-height: 25px !important; 
                    }
                }
                 }

                .v-list {
                    // position: absolute;
                    .v-list-item{
                        padding: 0;
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

       
    }    
}
</style>
