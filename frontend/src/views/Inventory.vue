<template>
    <div class="inventory-body-container-wrapper" v-resize="onResize">
		<div class="inventory-content-wrapper">
			<div class="inventory-header">
				<div class="inventory-header-left">
					<v-menu
						v-model="menu"
						:nudge-width="320"
						offset-y
						content-class="warehouse-lists-dropdown"
						v-if="warehousesLists !== null && warehousesLists.length > 1"
						:close-on-content-click="isMenuClosed">

						<template v-slot:activator="{ on, attrs }">
							<v-btn color="primary" class="warehouse-menu-icon btn-white" v-bind="attrs" v-on="on" :disabled="getWarehouseLoading">
								<img src="@/assets/icons/warehouse-menu.svg" width="20px" height="20px" alt="">
							</v-btn>
						</template>

						<v-card>
							<div class="search-warehouse">
								<v-text-field
									height="40px"
									color="#002F44"
									dense
									class="search"
									placeholder="Search Warehouse"
									outlined
									id="search-input"
									prepend-inner-icon="mdi-magnify"
									v-model.trim="searchData"
									hide-details="auto"
									@click="isWarehouseSearchClick">
								</v-text-field>

								<div>
									<button class="btn-white" @click="addWarehouse">+ Warehouse</button>
								</div>
							</div>

							<div class="warehouse-body-lists">
								<div class="warehouse-lists" 
									v-if="filteredWarehouseLists !== null && filteredWarehouseLists.length > 0">
									<div class="warehouse-item" 
										:class="(getActiveId === warehouse.id) ? 'active' : ''"
										v-for="(warehouse, i) in filteredWarehouseLists" 
										:key="i"
										@click="currentWarehouseChanged(warehouse)">

										<div class="warehouse-name">
											<div class="warehouse-name-info">
												<div class="p-name"
												v-bind:class="{
													'third-party-warehouse': warehouse.warehouse_type_id === 3,
													'service-provider': warehouse.warehouse_type_id === 6,
												}">{{ warehouse.name }}
												</div>

												<span class="warehouse-type" 
													v-if="warehouse !== null && 
														warehouse.warehouse_type_id === 3">
														Manual 3PL
												</span>

												<span class="warehouse-type" 
													v-if="warehouse !== null && 
														warehouse.warehouse_type_id === 6 &&
														warehouse.is_own === 1">
														Service Provider
												</span>

												<span class="warehouse-type" 
													v-if="warehouse !== null && 
														warehouse.warehouse_type_id === 6 &&
														warehouse.is_own === 0">
														Connected 3PL
												</span>
											</div>
										</div>
										<div class="warehouse-address">
											{{ warehouse.address }}
										</div>
									</div>
								</div>

								<div class="warehouse-lists" 
									v-if="filteredWarehouseLists !== null &&filteredWarehouseLists.length == 0">
									<div class="no-warehouse-searched">
										<img src="@/assets/icons/empty-warehouse-icon.svg" width="30px" height="32px" alt="">

										<h3> No matching result </h3>
										<p class="text-center">
											We couldn’t find any warehouse that matches your search term. Have you spelled it correctly? 
										</p>

										<div class="d-flex justify-center align-center mt-4">
											<v-btn color="primary" dark class="btn-white" @click.stop="clearSearchedData">
												Try Different Search
											</v-btn>
										</div>
									</div>
								</div>
							</div>
						</v-card>
					</v-menu>

					<div class="warehouse-wrapper-name">
						<h2 class="warehouse-header-name" 
							:class="$store.state.warehouse.currentWarehouse !== null ? $store.state.warehouse.currentWarehouse.warehouse_type : ''"> 
							
							{{  getWarehouseLoading ? 'Loading...' : 
								$store.state.warehouse.currentWarehouse !== null ? 
								$store.state.warehouse.currentWarehouse.name : 'Inventory' 
							}}
						</h2>

						<span class="warehouse-type" 
							v-if="!getWarehouseLoading && 
								$store.state.warehouse.currentWarehouse !== null && 
								$store.state.warehouse.currentWarehouse.warehouse_type_id === 3">
								Manual 3PL
						</span>

						<span v-if="!getWarehouseLoading && $store.state.warehouse.currentWarehouse !== null && 
							$store.state.warehouse.currentWarehouse.warehouse_type_id === 6">
							<span class="warehouse-type" v-if="$store.state.warehouse.currentWarehouse.is_own === 1">
								3PL Service Provider
							</span>

							<span class="warehouse-type" v-if="$store.state.warehouse.currentWarehouse.is_own !== 1">
								Connected 3PL
							</span>
						</span>

						<!-- <span class="warehouse-type" 
							v-if="!getWarehouseLoading && 
								$store.state.warehouse.currentWarehouse !== null && 
								$store.state.warehouse.currentWarehouse.warehouse_type_id === 6 &&
								$store.state.warehouse.currentWarehouse.is_own === 1">
								3PL Service Provider
						</span>

						<span class="warehouse-type" 
							v-if="!getWarehouseLoading && 
								$store.state.warehouse.currentWarehouse !== null && 
								$store.state.warehouse.currentWarehouse.warehouse_type_id === 6 &&
								$store.state.warehouse.currentWarehouse.is_own !== 1">
								Connected 3PL
						</span> -->
					</div>
				</div>

				<div class="inventory-header-right">
					<v-btn dark color="primary" class="btn-white btn-inventory-report" @click="inventoryReport($store.state.warehouse.currentWarehouse)">
						<img src="@/assets/icons/reports-blue.svg" class="mr-1" width="18px" height="18px" />
						Inventory Report
					</v-btn>

					<v-btn dark color="primary" class="btn-white btn-warehouse-info" 
						@click.stop="viewWarehouse($store.state.warehouse.currentWarehouse)"
						v-if="warehousesLists !== null">
						<v-icon left>mdi-information-outline</v-icon>
						Warehouse Info
					</v-btn>
					
					<v-btn dark color="primary" class="btn-white btn-add-warehouse" @click.stop="addWarehouse" v-if="warehousesLists == null || (warehousesLists !== null && warehousesLists.length <= 1)">
						New Warehouse
					</v-btn>
				</div>
			</div>

			<div class="inventory-body-wrapper">
				<div class="inventory-body-load" v-if="getWarehouseLoading">
					<div class="inventory-body-loading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate
							v-if="getWarehouseLoading">
						</v-progress-circular>
					</div>
				</div>

				<div class="inventory-body" v-if="!getWarehouseLoading && warehousesLists !== null" 
				style="position: relative;">
					<WarehouseDesktopTable
						:currentWarehouseSelected="$store.state.warehouse.currentWarehouse" 
						:isMobile="isMobile"
						@editWarehouse="editWarehouse"
						@viewWarehouse="viewWarehouse"
						:productsListsDataForAddInventory="productsListsData"
						@callProductsForAddInventory="callProductsForAddInventory"
						:fetchProductLoading="fetchProductLoading" />
				</div>	

				<div class="inventory-body no-data" v-if="!getWarehouseLoading && warehousesLists == null">
					<div class="no-data-heading">
						<img src="../assets/icons/empty-warehouse-icon.svg" width="40px" height="42px" alt="">

						<h3> Add Warehouse </h3>
						<p>
							Let’s add your first warehouse on Shifl. <br>
							Warehouses are the location where you get your items shipped.
						</p>

						<div class="mt-4">
							<v-btn dark color="primary" class="btn-blue btn-add-warehouse" @click.stop="addWarehouse">
								Add Warehouse
							</v-btn>
						</div>
					</div>			
				</div>
			</div>
		</div>

		<WarehouseViewDialog 
			:dialog.sync="dialogViewWarehouse"
			@close="closeViewWarehouse" 
			:currentWarehouse.sync="addWarehouseItems"
			@editWarehouse="editWarehouse"
			@deleteWarehouse="deleteWarehouse"
			:editedIndex.sync="editedWarehouseIndex"
			@closeDelete="closeDelete"
			:isWarehouseConnected="isWarehouseConnected"
			@getFirstWarehouse="getFirstWarehouse" />

		<WarehouseAddDialog 
			:dialog.sync="dialogAddWarehouse"
			:editedIndex.sync="editedWarehouseIndex"
			@close="closeAddWarehouse"
			:addWarehouseItems.sync="addWarehouseItems"
			@getFirstWarehouse="getFirstWarehouse"
			:isMobile="isMobile"			
			@deleteWarehouse="deleteWarehouse"
			:warehousesLists.sync="warehousesLists"
			@setWarehouseToDefault="setWarehouseToDefault"
			@callWarehouseProducts="callWarehouseProducts"
			:isWarehouseConnected="isWarehouseConnected" />

		<ConfirmDialog :dialogData.sync="dialogWarehouseDelete">
            <template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2> Delete Warehouse </h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					Do you want to delete the warehouse
					<span class="name">"{{ currentWarehouseItemToDelete !== null ? currentWarehouseItemToDelete.name : '' }}"</span>?
                    Once deleted, this cannot be undone.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="deleteWarehouseConfirm" text :disabled="getWarehouseDeleteLoading">
					<span v-if="!getWarehouseDeleteLoading">Delete</span>
                    <span v-if="getWarehouseDeleteLoading">Deleting...</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeDelete" :disabled="getWarehouseDeleteLoading">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog> 
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import globalMethods from '../utils/globalMethods'
import _ from 'lodash'
import WarehouseAddDialog from '../components/WarehouseComponents/Dialog/WarehouseAddDialog.vue'
import WarehouseDesktopTable from '../components/Tables/Inventory/WarehouseDesktopTable.vue'
import WarehouseViewDialog from '../components/WarehouseComponents/Dialog/WarehouseViewDialog.vue'
import ConfirmDialog from '../components/Dialog/GlobalDialog/ConfirmDialog.vue'

export default {
    name: "Inventory",
	components: {
		WarehouseAddDialog,
		WarehouseDesktopTable,
		WarehouseViewDialog,
		ConfirmDialog
	},
	data: () => ({
		menu: false,
		isMobile: false,
		dialogAddWarehouse: false,
		editedWarehouseIndex: -1,
		currentWarehouseSelected: null,		
        addWarehouseItems: {
            name: '',
			email_address: null,
            phone: '',
            address: '',
            country: '',
            state: '',
            city: '',
            zipcode: '',
            products: [],
            warehouse_type: null,
			warehouse_type_id: null,
			description: ''
        },
        defaultWarehouseItems: {
            name: '',
			email_address: null,
            phone: '',
            address: '',
            country: '',
            state: '',
            city: '',
            zipcode: '',
            products: [],
            warehouse_type: null,
			warehouse_type_id: null,
			description: ''
        },
		dialogViewWarehouse: false,
		dialogWarehouseDelete: false,
		currentWarehouseItemToDelete: null,
		leftSideToggle: 1,
		searchData: '',
		isMenuClosed: false,
		// for 3pl
		productsListsData: [],
        lastDataCheckProducts: [],
        current_page_is_products: 1,
		fetchProductLoading: false
	}),
	computed: {
		...mapGetters({
			getUser: "getUser",
			getWarehouse: 'warehouse/getWarehouse',
			getWarehouseLoading: 'warehouse/getWarehouseLoading',
			getWarehouseDeleteLoading: 'warehouse/getWarehouseDeleteLoading',
			getActiveId: 'warehouse/getActiveId',
			getAllInboundProductsLists: 'inbound/getAllInboundProductsLists',
			getAllInboundProductsListsDropdownData: 'inbound/getAllInboundProductsListsDropdownData',
			getCountries: 'warehouse/getCountries',			
            getWarehouse3PLData: 'warehouse/getWarehouse3PLData'
		}),
		getAllWarehousesData() {
			return typeof this.getWarehouse !== 'undefined' && this.getWarehouse !== null ? this.getWarehouse.results : null
		},
		warehousesLists() {
			let warehouses = null

			if (typeof this.getAllWarehousesData !== 'undefined' && this.getAllWarehousesData !== null && 
				this.getAllWarehousesData.length !== 'undefined' && this.getAllWarehousesData.length !== 0) {

				warehouses = this.getAllWarehousesData
				let warehouseSort = this.$store.state.warehouse.currentSort

				if (warehouses !== null) {
					if ( warehouseSort==='name-asc') {
						warehouses = _.orderBy(warehouses, ['name'], ['asc']);
					} else if( warehouseSort=='name-desc') {
						warehouses = _.orderBy(warehouses, ['name'], ['desc']);
					} else if ( warehouseSort=='location-asc') {
						warehouses = _.orderBy(warehouses, ['address'], ['asc'])
					} else if ( warehouseSort=='location-desc') {
						warehouses = _.orderBy(warehouses, ['address'], ['desc'])
					} else if ( warehouseSort=='cartons-asc') {
						warehouses = _.orderBy(warehouses, ['total_cartons'], ['asc'])
					} else if ( warehouseSort=='cartons-desc') {
						warehouses = _.orderBy(warehouses, ['total_cartons'], ['desc'])
					} else if ( warehouseSort=='units-asc') {
						warehouses = _.orderBy(warehouses, ['total_units'], ['asc'])
					} else if ( warehouseSort=='units-desc') {
						warehouses = _.orderBy(warehouses, ['total_units'], ['desc'])
					} else {
						warehouses = _.orderBy(warehouses, ['name'], ['asc'])
					}
				}
			}

			return warehouses
		},
		filteredWarehouseLists() {
            //filter data based on the searched value
            let filteredItems = []
            
            if (this.searchData!=='') {
                filteredItems = _.filter(this.warehousesLists, e => (e.name.toLowerCase().indexOf(this.searchData.toLowerCase())!==-1))
            } else {
                filteredItems = this.warehousesLists
            }

            return filteredItems
        },
		isWarehouseConnected() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.is_own !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.is_own === 0) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
	},
	watch: {
		menu(value) {
			if (!value) {
				this.searchData = ''
			}
		}
	},
	methods: {
		...mapActions({
            fetchWarehouse: 'warehouse/fetchWarehouse',
			fetchCountries: 'warehouse/fetchCountries',
			deleteSelectedWarehouse: 'warehouse/deleteSelectedWarehouse',
            fetchStates: 'warehouse/fetchStates',
            fetchCities: 'warehouse/fetchCities',
			setActiveId: 'warehouse/setActiveId',
			// inbound inventory,
			fetchProductInventories: 'productInventories/fetchProductInventories',
			fetchInboundInventories: 'inbound/fetchInboundInventories',
			fetchOutboundInventories: 'outbound/fetchOutboundInventories',
			setEmptyInventoryInboundData: 'inbound/setEmptyInventoryInboundData',
			setEmptyInventoryLocationsData: 'locations/setEmptyInventoryLocationsData',
			setEmptyInventoryStorableUnitData: 'storableUnit/setEmptyInventoryStorableUnitData',
			setProductEmptyData: 'productInventories/setProductEmptyData',
			setEmptyInventoryOutboundData: 'outbound/setEmptyInventoryOutboundData',
			// set tabs back to 0
			setCurrentStorableUnitTab: 'storableUnit/setCurrentStorableUnitTab',
			setCurrentLocationTypeTab: 'locations/setCurrentLocationTypeTab',
			setCurrentLocationTypeSubTab: 'locations/setCurrentLocationTypeSubTab',
			setCurrentInboundTab: 'inbound/setCurrentInboundTab',
			setCurrentOutboundTab: "outbound/setCurrentOutboundTab",
			// 3pl
			fetchProductInventories3pl: 'productInventories/fetchProductInventories3pl',
			fetchInboundProducts: 'inbound/fetchInboundProducts',
			setAllInboundProductsLists: 'inbound/setAllInboundProductsLists',
			fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
			checkWarehouse3PLProvider: 'warehouseCustomers/checkWarehouse3PLProvider',
			setWarehouseTypeHas3PL: 'warehouseCustomers/setWarehouseTypeHas3PL',
			setAllOutboundProductsLists:'outbound/setAllOutboundProductsLists',
			fetchConnected3PLWarehouseData: 'warehouse/fetchConnected3PLWarehouseData',
			setConnected3PLWarehouseReset: 'warehouse/setConnected3PLWarehouseReset'
        }),
		onResize() {
            if (window.innerWidth < 769) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },
		...globalMethods,
		// warehouse
		async callWarehouseDefaultMethod() {
			// await this.checkWarehouseType3PLProvider()

			if (this.$store.state.warehouse.currentWarehouse == null) {
				await this.getFirstWarehouse()
			} else {
				let id = this.$store.state.warehouse.currentWarehouse.id
				this.setActiveId(id)

				let data = { id, page: 1, warehouse_type: this.$store.state.warehouse.currentWarehouse.warehouse_type_id }

				try {
					if (this.$router.currentRoute.query.tab === 'Products') {
						this.setAllInboundProductsLists([])
						await this.callWarehouseProducts(data)
					}					
				} catch(e) {
					this.notificationError(e)
				}
			}
		},
		isWarehouseSearchClick() {
			this.isMenuClosed = false
		},
		setAllTabsToDefault(id) {
			this.setActiveId(id)
			this.setProductEmptyData([])
			this.setEmptyInventoryStorableUnitData([])
			this.setEmptyInventoryLocationsData([])
			this.setEmptyInventoryInboundData([])
			this.setEmptyInventoryOutboundData([])
			this.setCurrentStorableUnitTab(0)
			this.setCurrentLocationTypeTab(0)
			this.setCurrentLocationTypeSubTab(0)
			this.setCurrentInboundTab(0)
			this.setCurrentOutboundTab(0)
			this.setAllInboundProductsLists([])
			this.setAllOutboundProductsLists([])
		},
		async getFirstWarehouse() {
			if (this.warehousesLists !== null) {
				this.$store.state.warehouse.currentWarehouse = this.warehousesLists[0]

				if (this.$store.state.warehouse.currentWarehouse !== null) {
					let id = this.$store.state.warehouse.currentWarehouse.id
					this.setAllTabsToDefault(id)

					let data = { id, page: 1, warehouse_type: this.$store.state.warehouse.currentWarehouse.warehouse_type_id }

					try {
						this.current_page_is_products = 1
						this.callWarehouseProducts(data)
					} catch (e) {
						console.log(e)
					}
				}
			} else {
				this.$store.state.warehouse.currentWarehouse = null
			}
		},
		async currentWarehouseChanged(warehouse) {
			this.$store.state.warehouse.currentWarehouse = warehouse
			this.$store.dispatch("page/setCurrentInventoryTab", 0)
			this.isMenuClosed = true
			this.setConnected3PLWarehouseReset(null)

			if (warehouse !== null) {
                let id = warehouse.id
				this.setAllTabsToDefault(id)
				this.$store.state.inbound.pendingInboundsLoading = true

				let data = { id, page: 1, warehouse_type: this.$store.state.warehouse.currentWarehouse.warehouse_type_id }
				try {
					this.current_page_is_products = 1
					this.callWarehouseProducts(data)
				} catch (e) {
					console.log(e)
				}
            }
		},
		async addWarehouse() {
			this.isMenuClosed = true
			this.dialogAddWarehouse = true
			this.$nextTick(() => {
				this.addWarehouseItems = Object.assign({}, this.defaultWarehouseItems)
				this.editedWarehouseIndex = -1
			})
			if (typeof this.getCountries !== 'undefined' && this.getCountries.length === 0) {
				await this.fetchCountries()
			}
		},
		editWarehouse(warehouse) {
			this.addWarehouseItems = Object.assign({}, warehouse)
			this.editedWarehouseIndex = _.findIndex(this.warehousesLists, e => (e.id == warehouse.id))
			if (typeof warehouse.country!=='undefined' && warehouse.country !== null && warehouse.country !== '') {
				this.fetchStates(warehouse.country)
				.then(response => {
					console.log(response)
					this.fetchCities({
						country: warehouse.country,
						states: warehouse.state
					})
				}).catch(e => {
					console.log(e)
				})	
			}

			if (this.isWarehouseConnected) {
				if (typeof this.getWarehouse3PLData !== 'undefined' && this.getWarehouse3PLData !== null && 
					typeof this.getWarehouse3PLData.data !== 'undefined') {
					this.addWarehouseItems.email_address = JSON.parse(this.getWarehouse3PLData.data.emails)
					this.addWarehouseItems.description = this.getWarehouse3PLData.data.description
					this.addWarehouseItems.edit_id_connected = this.getWarehouse3PLData.data.id
				} else {
					this.addWarehouseItems.email_address = []
					this.addWarehouseItems.description = ''
					this.addWarehouseItems.edit_id_connected = ''
				}
			}
			
			this.dialogAddWarehouse = true
		},
		closeAddWarehouse() {
			this.$nextTick(() => {
				this.addWarehouseItems = Object.assign({}, this.defaultWarehouseItems)
				this.editedWarehouseIndex = -1
			})
			this.dialogAddWarehouse = false
		},
		setWarehouseToDefault() {
			this.$nextTick(() => {
				this.addWarehouseItems = Object.assign({}, this.defaultWarehouseItems)
				this.editedWarehouseIndex = -1
			})
		},
		deleteWarehouse(item) {
			this.dialogWarehouseDelete = true
			this.currentWarehouseItemToDelete = item
		},
		async viewWarehouse(warehouse) {
			this.addWarehouseItems = Object.assign({}, warehouse)
			this.dialogViewWarehouse = true

			if (this.isWarehouseConnected) {
				let data = { warehouse_id: warehouse.id, wh_customer_id: warehouse.warehouse_customer_id }

				if (this.getWarehouse3PLData === null) {
					await this.fetchConnected3PLWarehouseData(data)
				} else {
					if (typeof this.getWarehouse3PLData !== 'undefined' && this.getWarehouse3PLData !== null &&
						this.getWarehouse3PLData.data !== 'undefined') {
						if (warehouse.id !== this.getWarehouse3PLData.data.warehouse_id) {
							await this.fetchConnected3PLWarehouseData(data)
						}
					}
				}
			}
			if (typeof this.getCountries !== 'undefined' && this.getCountries.length === 0) {
				await this.fetchCountries()
			}
		},
		closeViewWarehouse() {
			this.dialogViewWarehouse = false
		},
		async deleteWarehouseConfirm() {
			if (this.currentWarehouseItemToDelete !== null) {
                try {
                    await this.deleteSelectedWarehouse(this.currentWarehouseItemToDelete.id)
                    this.notificationCustom('Warehouse has been deleted.')
                    await this.fetchWarehouse()
					await this.getFirstWarehouse()
                    this.closeDelete()
					this.closeAddWarehouse()
					this.closeViewWarehouse()
					this.$store.dispatch("page/setCurrentInventoryTab", 0)
                } catch (e) {
                    this.notificationError(e)
                }
            }
		},
		closeDelete() {
			this.dialogWarehouseDelete = false
		},	
		clearSearchedData() {
			document.getElementById("search-input").focus()
            this.searchData = ''
		},
		async callProductsForAddInventory(from) {
			try {
				if (this.getAllInboundProductsListsDropdownData.length === 0) {
					this.current_page_is_products = 1
					await this.fetchInboundProducts(1)

					if (typeof this.getAllInboundProductsLists !== 'undefined' && 
						this.getAllInboundProductsLists !== null && 
						typeof this.getAllInboundProductsLists.products !== 'undefined' && 
						Array.isArray(this.getAllInboundProductsLists.products) &&
						this.getAllInboundProductsLists.products.length > 0) {
							
						this.productsListsData = this.getAllInboundProductsLists.products.map(value => {
							return {
								product_id: value.id,
								id: value.id,
								name: value.name,
								sku: value.sku,
								image: value.image,
								description: value.description,
								category_id: value.category_id,
								units_per_carton: value.units_per_carton,
								category_sku: value.category_sku
							}
						})

						this.lastDataCheckProducts = this.productsListsData

						if (this.current_page_is_products < this.getAllInboundProductsLists.last_page) {
							this.loadMoreProducts()
						}
						
						this.setAllInboundProductsLists(this.productsListsData)
						this.fetchProductLoading = false
					} else {
						this.fetchProductLoading = false
						this.productsListsData = []
						this.lastDataCheckProducts = []
					}
				} else {
					if (from === 'Inventory') {
                        this.productsListsData = this.getAllInboundProductsListsDropdownData
                        this.fetchProductLoading = false
                    } else {
						let last_page = this.getAllInboundProductsLists.last_page

						if (typeof last_page !== 'undefined') {
							this.current_page_is_products = last_page
							await this.fetchInboundProducts(last_page)

							if (typeof this.getAllInboundProductsLists !== 'undefined' && 
								this.getAllInboundProductsLists !== null && 
								typeof this.getAllInboundProductsLists.products !== 'undefined' && 
								Array.isArray(this.getAllInboundProductsLists.products) &&
								this.getAllInboundProductsLists.products.length > 0) {
									
								let newloaddata = this.getAllInboundProductsLists.products.map(value => {
									return {
										product_id: value.id,
										id: value.id,
										name: value.name,
										sku: value.sku,
										image: value.image,
										description: value.description,
										category_id: value.category_id,
										units_per_carton: value.units_per_carton,
										category_sku: value.category_sku
									}
								})

								this.productsListsData = [...this.productsListsData, ...newloaddata]

								if (this.current_page_is_products < this.getAllInboundProductsLists.last_page) {
									this.loadMoreProducts()
								}
								
								this.setAllInboundProductsLists(this.productsListsData)
								this.fetchProductLoading = false
							} else {
								this.fetchProductLoading = false
								this.productsListsData = []
								this.lastDataCheckProducts = []
							}
						}
					}
				}         
			} catch(e) {
				this.fetchProductLoading = false
				this.notificationError(e)
			}
		},
		async loadMoreProducts() {
            if (this.current_page_is_products < this.getAllInboundProductsLists.last_page) {
				this.current_page_is_products++

				try {
					await this.fetchInboundProducts(this.current_page_is_products)

                    if (typeof this.getAllInboundProductsLists !== 'undefined' && 
                        this.getAllInboundProductsLists !== null && 
                        typeof this.getAllInboundProductsLists.products !== 'undefined' && Array.isArray(this.getAllInboundProductsLists.products) &&
                        this.getAllInboundProductsLists.products.length > 0) {
                            
                        let newloaddata = this.getAllInboundProductsLists.products.map(value => {
                            return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
                                category_id: value.category_id,
                                units_per_carton: value.units_per_carton,
								category_sku: value.category_sku
                            }
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]

                        if (this.current_page_is_products < this.getAllInboundProductsLists.last_page) {
                            this.loadMoreProducts()
                        }

                        this.setAllInboundProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
		async callWarehouseProducts(data) {
			// this.setAllInboundProductsLists([])
			this.setProductEmptyData([])

			let newData = { id: data.id, page: data.page }
			if (data.warehouse_type !== 3) {
				await this.fetchProductInventories(newData)
			} else {				
				await this.fetchProductInventories3pl(newData)
				await this.callProductsForAddInventory('Inventory')
			}
		},
		async checkWarehouseType3PLProvider() {
			let id = (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id

			await this.checkWarehouse3PLProvider(id).then(async (res) => {
				if (typeof res !== 'undefined' && res.exist) {
					this.setWarehouseTypeHas3PL(true)					
				} else {
					this.setWarehouseTypeHas3PL(false)
				}
			})			
		},
		inventoryReport(item) {
			this.$router.push(`inventory/inventory-report?id=${item.id}`)
		}
	},
	async mounted() {
		//set current page
        this.$store.dispatch("page/setPage", "inventory")

		if (this.warehousesLists === null) {
			try {
				await this.fetchWarehouse()
				this.callWarehouseDefaultMethod()
			} catch(e) {
				this.notificationError(e)
			}
		} else {
			await this.callWarehouseDefaultMethod()
		}
		
		await this.checkWarehouseType3PLProvider()
	},
	updated() {}
};
</script>

<style lang="scss">
@import '../assets/scss/pages_scss/inventory/inventory.scss';
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../assets/scss/buttons.scss';
// @import '../assets/scss/inputs.scss';
@import "../assets/scss/tables.scss";
</style>
