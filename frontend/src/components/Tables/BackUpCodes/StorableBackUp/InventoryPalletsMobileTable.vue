<template>
    <div class="pallets-mobile">
        <v-data-table
            :headers="headers"
            :items="storableUnitsExampleData"
            :search="search"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"
            class="inventory-table elevation-1"
            :class="(storableUnitsExampleData !== null && 
                    storableUnitsExampleData.length > 0) ? '' : 'no-data-table'"
            hide-default-footer
            fixed-header
            show-select >

            <template v-slot:top>
                <div class="inventory-search-wrapper" v-if="storableUnitsExampleData !== null && storableUnitsExampleData.length > 0">
                    <v-spacer></v-spacer>

                    <v-btn dark color="primary" class="btn-white btn-filters">
                        <img src="@/assets/icons/filters.svg" alt=""> Filters
                    </v-btn>

                    <div class="search-and-filter">
                        <Search 
                            placeholder="Search Pallets"
                            className="search custom-search"
                            :inputData.sync="search" />

                        <v-btn dark color="primary" class="btn-blue ml-2">
                            Merge
                        </v-btn>
                    </div>
                </div>
                
            </template>

            <template v-slot:[`item.label`]="{ item }">                
                <div class="inventory-wrapper inventory-blue-text">
                    <div> {{ item.label }}</div>
                </div>
            </template>

            <template v-slot:[`item.type`]="{ item }">                
                <div class="inventory-wrapper">
                    <div> {{ item.type }}</div>
                </div>
            </template>
            
            <template v-slot:[`item.location`]="{ item }">                
                <div class="inventory-wrapper">
                    <div> {{ item.location }}</div>
                </div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">                
                <div class="inventory-wrapper">
                    <div> {{ item.updated_at }}</div>
                </div>
            </template>

            <template v-slot:[`item.no_sku`]="{ item }">                
                <span>{{ item.no_sku !== null ? item.no_sku : 0 }}</span>
            </template>

            <template v-slot:[`item.carton`]="{ item }">                
                <span>{{ item.carton !== null ? item.carton : 0 }}</span>
            </template>

            <template v-slot:[`item.unit`]="{ item }">                
                <span>{{ item.unit !== null ? item.unit : 0 }}</span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions">								
                    <button class="btn-edit mr-2 inventory-btn-edit">
                        <img src="@/assets/icons/edit-inventory.svg" alt="" width="10px" height="10px"> Edit
                    </button>
                    <button class="btn-print mr-2">
                        <img src="@/assets/icons/print-icon.svg" alt="">
                    </button>
                    <button class="btn-more" @click.stop="editInventory(item)">
                        <img src="@/assets/icons/dots-black.svg" alt="">
                    </button>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="getInventoryLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper" v-if="!getInventoryLoading && storableUnitsExampleData.length == 0">
                    <div class="no-data-heading">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <h3> Empty Pallets </h3>
                        <p>
                            This warehouse doesn’t have any pallets in inventory yet.
                        </p>
                    </div>
                </div>
            </template>
        </v-data-table>

        <Pagination 
            v-if="storableUnitsExampleData !== 'undefined' && storableUnitsExampleData.length > 0"
            :pageData.sync="page"
            :lengthData.sync="pageCount"
            :isMobile="isMobile"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import Search from '../../../Search.vue'
import Pagination from '../../../Pagination'
import _ from 'lodash'

export default {
    name: 'InventoryPalletsMobileTable',
    props: ['currentWarehouseSelected', 'isMobile'],
    components: {
        Search,
        Pagination
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 15,
        search: '',
        headers: [
			{
				text: 'Label',
				align: 'start',
				sortable: false,
				value: 'label',
				fixed: true,
				width: "10%"
			},
			{ 
				text: 'Type',
				align: 'start',
				sortable: false,
				value: 'type',
				fixed: true,
				width: "10%"
			},
			{ 
				text: 'Location',
				align: 'start',
				sortable: false,
				value: 'location',
				fixed: true,
				width: "15%"
			},
			{ 
				text: 'Updated At',
				align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: "15%"
			},
			{ 
				text: 'No of SKU',
				align: 'end',
				sortable: false,
				value: 'no_sku',
				fixed: true,
				width: "10%"
			},
            { 
				text: 'Carton',
				align: 'end',
				sortable: false,
				value: 'carton',
				fixed: true,
				width: "10%"
			},
            { 
				text: 'Unit',
				align: 'end',
				sortable: false,
				value: 'unit',
				fixed: true,
				width: "12%"
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "18%"
			},
		],
        dialogDelete: false,
        dialogDeleteWarehouse: false,       
        // inventory 
        currentSelectedInventoryDelete: null,
		dialogAddInventory: false,
		editedInventoryIndex: -1,              
        addInventoryItems: {
			product: {
				id: '',
                units: ''
			},
			carton_count: '',
			in_each_carton: '',
			total_unit: ''
		},
		defaultInventoryItems: {
			product: {
				id: '',
                units: ''
			},
			carton_count: '',
			in_each_carton: '',
			total_unit: ''
		},
        // storable units
        storableUnitsExampleData: [
            {
                label: '558612',
                type: 'Pallet',
                spec: '20x13x12.5 in',
                weight: '532.453 kg',
                location: 'F03R12C33',
                updated_at: '01:47PM, Aug 16, 2021',
                no_of_sku: 2,
                carton_count: 57,
                total_unit: 41815,
                products: [
                    {
                        sku: '124563',
                        name: 'Product 1',
                        description: '#2345 PS4 Slim 500GB Fifa Edition',
                        carton_count: 130,
                        in_each_carton: 13000 
                    },
                    {
                        sku: '124562',
                        name: 'Product 2',
                        description: '#3425 Printer Tonner Red',
                        carton_count: 220,
                        in_each_carton: 4400 
                    }
                ]
            },
            {
                label: '449003',
                type: 'Pallet',
                spec: '20x13x12.5 in',
                weight: '532.453 kg',
                location: 'F03R12C33',
                updated_at: '01:47PM, Aug 16, 2021',
                no_of_sku: 1,
                carton_count: 360,
                total_unit: 17400,
                products: [
                    {
                        sku: '124563',
                        name: 'Product 1',
                        description: '#2345 PS4 Slim 500GB Fifa Edition',
                        carton_count: 130,
                        in_each_carton: 13000 
                    },
                ]
            }
        ],
    }),
    computed: {
        ...mapGetters({
            getInventory: 'inventory/getInventory',
            getInventoryLoading: 'inventory/getInventoryLoading',
            getCategories: 'category/getCategories',
            getEditInventory: 'inventory/editInventory',
            getDeleteInventoryLoading: 'inventory/getDeleteInventoryLoading',
            getCurrentWarehouse: 'warehouse/getCurrentWarehouse'
        }),
        items: {
            get() {
                return this.getEditInventory
            },
            set (value) {
                this.setEditInventory(value)
            }
        },
        categoryLists() {
            let categoryListsData = []

            if (typeof this.getCategories !== 'undefined' && this.getCategories!==null && this.getCategories.length > 0) {
				categoryListsData = this.getCategories.map((value) => {
					return {
						id: value.id,
						name: value.name
					}
				})
			} 

            return categoryListsData
        },      
		currentWarehouseProducts() {
            let inventoryProducts = []

            if (typeof this.getInventory !== 'undefined' && this.getInventory !== null) {
                if (typeof this.getInventory.results !== 'undefined' && this.getInventory.results !== null) {
                    if (this.getInventory.results.length !== 'undefined') {
                        inventoryProducts = this.getInventory.results
                        

                        inventoryProducts = inventoryProducts.map ( item => {
                            let newItem = {}
                            let {
                                product,
                                ...otherItems
                            } = item

                            newItem = {
                                name: product!==null ? product.name : '',
                                sku: product!==null ? product.sku : '',
                                category_id: product!==null ? product.category_id : '',
                                image: product!==null ? product.image : null,
                                product_in_each_carton: product !== null ? product.units_per_carton : null,
                                ...otherItems
                            }

                            return newItem
                        })                            
                    }
                }
            }
            
            return inventoryProducts
        },
    },
    methods: {
        ...mapActions({
            fetchSingleProduct: 'products/fetchSingleProduct',
            setEditInventory: 'inventory/setEditInventory',
            deleteInventory: 'inventory/deleteInventory',
            fetchInventories: 'inventory/fetchInventories',
            setIsEditing: 'inventory/setIsEditing',
        }),
        //...globalMethods,
        addInventory() {
            this.dialogAddInventory = true
        },
        closeInventory() {
            this.dialogAddInventory = false
            this.editedInventoryIndex = -1
        },
        editInventory(item) {
            let buildEditItem = [
                {
                    product: {
                        id: item.product_id,
                    },
                    carton_count: item.carton_count,
                    in_each_carton: item.in_each_carton,
                    total_unit: item.total_unit,
                    id: item.id
                }
            ]

            this.setEditInventory(buildEditItem)
			this.setIsEditing(true)
            this.editedInventoryIndex = this.currentWarehouseProducts.indexOf(item)
			this.addInventoryItems = Object.assign({}, this.currentWarehouseProducts[this.editedInventoryIndex])

            this.dialogAddInventory = true
        },
        getImgUrl(pic) {
            if (pic !== 'undefined' && pic !== null) {
				return pic
			} else {
				return require('@/assets/icons/default-product-icon.svg')
			}
        },
        getCategoryName(id) {
			if(this.categoryLists.length !== 0 && id) {
                let findSizeValue = _.find(this.categoryLists, (e) => (e.id == id))

                if (typeof findSizeValue !== 'undefined') {
                    if (findSizeValue.name !== 'undefined') {
                        return findSizeValue.name
                    }
                } else {
                    return ''
                }
            }
		},
    },
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
</style>