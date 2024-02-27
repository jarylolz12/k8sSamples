<!-- @format -->

<template>
	<div class="supplier-container" v-resize="onResize">
		<div class="supplier-container-contents">
			<v-tabs
				class="supplier-custom-tabs"
				:height="!isMobile ? '50px' : '48px'"
				@change="onTabChange"
				v-model="activeTab">

				<v-tab v-for="(n, i) in tabsComputed" :key="i">
					<span class="tab-name"> {{ n }} </span>

					<v-badge
						color="#819FB2"
						class="customBadge"
						bordered
						:content="getItemPerTabCounts(i)">
					</v-badge>
				</v-tab>
			</v-tabs>

			<!-- VENDOR TABLES -->
			<VendorsDesktopTable
				:items="suppliers"
				@addSupplier="addSupplier"
				@editSupplier="editSupplier"
				:isMobile="isMobile"
				v-if="!isMobile && activeTab == 0"
				:suppliersPagination="suppliersPagination"
				:searchVal.sync="searchVendors" />

			<VendorsMobileTable
				:items="suppliers"
				@addSupplier="addSupplier"
				@editSupplier="editSupplier"
				:isMobile="isMobile"
				v-if="isMobile && activeTab == 0"
				:suppliersPagination="suppliersPagination"
				:searchVal.sync="searchVendors" />

			<!-- CUSTOMER TABLES -->
			<CustomersDesktopTable
				:items="customers"
				@addCustomer="addCustomer"
				@editCustomer="editCustomer"
				@deleteCustomer="deleteCustomer"
				:customerPagination="customerPagination"
				:isMobile="isMobile"
				v-if="!isMobile && activeTab == 1"
				:searchVal.sync="searchCustomers" />

			<CustomersMobileTable
				:items="customers"
				@addCustomer="addCustomer"
				@editCustomer="editCustomer"
				:customerPagination="customerPagination"
				:isMobile="isMobile"
				v-if="isMobile && activeTab == 1"
				:searchVal.sync="searchCustomers" />

			<!-- WAREHOUSE CUSTOMERS TABLE -->
			<WarehouseCustomersDesktopTable
				:items="warehouseCustomers"
				@addWarehouseCustomer="addWarehouseCustomer"
				@editWarehouseCustomer="editWarehouseCustomer"
				@deleteWarehouseCustomer="deleteWarehouseCustomer"
				:warehouseCustomersPagination="warehouseCustomersPagination"
				:isMobile="isMobile"
				v-if="!isMobile && activeTab == 2"
				:searchVal.sync="searchWarehouseCustomers" />

			<WarehouseCustomersMobileTable
				:items="warehouseCustomers"
				@addWarehouseCustomer="addWarehouseCustomer"
				@editWarehouseCustomer="editWarehouseCustomer"
				@deleteWarehouseCustomer="deleteWarehouseCustomer"
				:warehouseCustomersPagination="warehouseCustomersPagination"
				:isMobile="isMobile"
				v-if="isMobile && activeTab == 2"
				:searchVal.sync="searchWarehouseCustomers" />
		</div>

		<SupplierDialog
			:editedIndexData.sync="editedIndex"
			:dialogData.sync="dialog"
			:editedItemData.sync="editedItem"
			:defaultItemData.sync="defaultItem"
			@setToDefault="setToDefault"
			:customerPagination="customerPagination"
			:suppliersPagination="suppliersPagination"
			@close="close"
			:searchVendors.sync="searchVendors"
			:searchCustomers.sync="searchCustomers"
			:suppliersData="suppliers"
			:customersData="customers" />

		<WarehouseCustomersDialog
			:editedIndexData.sync="editedIndex"
			:dialogData.sync="dialogWarehouseCustomer"
			:editedItemData.sync="editedItem"
			:defaultItemData.sync="defaultItem"
			:warehouseCustomersPagination="warehouseCustomersPagination"
			@close="closeWarehouseCustomer"
			:searchWarehouseCustomers.sync="searchWarehouseCustomers"
			:warehouseCustomersData="warehouseCustomers" />
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import SupplierDialog from "../components/SupplierComponents/SupplierDialog.vue"
import VendorsDesktopTable from "../components/Tables/Supplier/Contact/Vendors/VendorsDesktopTable.vue"
import VendorsMobileTable from "../components/Tables/Supplier/Contact/Vendors/VendorsMobileTable.vue"
import CustomersDesktopTable from "../components/Tables/Supplier/Contact/Customers/CustomersDesktopTable.vue"
import CustomersMobileTable from "../components/Tables/Supplier/Contact/Customers/CustomersMobileTable.vue"
import WarehouseCustomersDesktopTable from '../components/Tables/Supplier/Contact/WarehouseCustomers/WarehouseCustomersDesktopTable.vue'
import WarehouseCustomersMobileTable from '../components/Tables/Supplier/Contact/WarehouseCustomers/WarehouseCustomersMobileTable.vue'
import WarehouseCustomersDialog from '../components/SupplierComponents/WarehouseCustomersDialog.vue'
import globalMethods from "../utils/globalMethods"

export default {
	name: "Contact",
	components: {
		SupplierDialog,
		VendorsDesktopTable,
		VendorsMobileTable,
		CustomersDesktopTable,
		CustomersMobileTable,
		WarehouseCustomersDesktopTable,
		WarehouseCustomersMobileTable,
		WarehouseCustomersDialog
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 3,
		dialog: false,
		dialogDelete: false,
		headers: [
			{
				text: "Name",
				align: "start",
				sortable: false,
				value: "name",
				width: "20%",
				fixed: true,
			},
			{
				text: "Phone",
				align: "start",
				value: "phone",
				sortable: false,
				width: "20%",
				fixed: true,
			},
			{
				text: "Address",
				align: "start",
				value: "address",
				sortable: false,
				width: "25%",
				fixed: true,
			},
			{
				text: "Email",
				align: "start",
				value: "emails",
				sortable: false,
				width: "20%",
				fixed: true,
			},
			{
				text: "",
				align: "center",
				value: "actions",
				sortable: false,
				width: "10%",
				fixed: true,
			},
		],
		editedIndex: -1,
		editedItem: {
			name: "",
			phone: "",
			address: "",
			emails: null,
			isSupplier: true,
			company_key: "",
			warehouses: []
		},
		defaultItem: {
			name: "",
			phone: "",
			address: "",
			emails: null,
			isSupplier: true,
			company_key: "",
			warehouses: []
		},
		searchVendors: "",
		searchCustomers: "",
		searchWarehouseCustomers: '',
		isMobile: false,
		activeTab: 0,
		tabsDefault: ["Vendors", "Buyers"],
		tabs: ["Vendors", "Buyers", "Warehouse Customers"],
		dialogWarehouseCustomer: false,
		hasWarehouse3PLProvider: false
	}),
	computed: {
		...mapGetters({
			getSuppliers: "suppliers/getSuppliers",
			getUser: "getUser",
			getSuppliersLoading: "suppliers/getSuppliersLoading",
			getCustomers: "customers/getCustomers",
			getSearchedSuppliers: "suppliers/getSearchedSuppliers",
			getSearchedSuppliersLoading: "suppliers/getSearchedSuppliersLoading",
			getSearchedCustomer: 'customers/getSearchedCustomer',
			getSearchedCustomerLoading: 'customers/getSearchedCustomerLoading',
			// warehouse customers
			getWarehouseCustomers: 'warehouseCustomers/getWarehouseCustomers',
			getWarehouseCustomersLoading: 'warehouseCustomers/getWarehouseCustomersLoading',
			getSearchedWarehouseCustomers: 'warehouseCustomers/getSearchedWarehouseCustomers',
			getHas3PLProviderWarehouse: 'warehouseCustomers/getHas3PLProviderWarehouse'
		}),
		suppliers() {
			let data = []

			if (typeof this.getSearchedSuppliers !== 'undefined' && this.getSearchedSuppliers !== null) {
                if (this.searchVendors !== '' && this.getSearchedSuppliers.tab === 'vendors') {
                    if (typeof this.getSearchedSuppliers.data !== "undefined" &&
						this.getSearchedSuppliers.data.length !== "undefined") {
						data = this.getSearchedSuppliers.data
					}
                } else {
                    if (typeof this.getSuppliers !== "undefined" && this.getSuppliers !== null) {
						if (typeof this.getSuppliers.data !== "undefined" &&
							this.getSuppliers.data.length !== "undefined") {
							data = this.getSuppliers.data
						}
					}
                }
            } else {
                if (typeof this.getSuppliers !== "undefined" && this.getSuppliers !== null) {
					if (typeof this.getSuppliers.data !== "undefined" &&
						this.getSuppliers.data.length !== "undefined") {
						data = this.getSuppliers.data
					}
				}
            }

			if (data.length > 0) {
				data.map((value) => {
					if (value.address === null) {
						value.address = ""
					}

					if (value.phone === null) {
						value.phone = ""
					}

					if (value.emails === null) {
						value.emails = ""
					}
				})
			}

			return data
		},
		customers() {
			let customerData = []

			if (typeof this.getSearchedCustomer !== 'undefined' && this.getSearchedCustomer !== null) {
                if (this.searchCustomers !== '' && this.getSearchedCustomer.tab === 'customers') {
                    if (typeof this.getSearchedCustomer.data !== "undefined" &&
						this.getSearchedCustomer.data.length !== "undefined") {
						customerData = this.getSearchedCustomer.data
					}
                } else {
                    if (typeof this.getCustomers !== "undefined" && this.getCustomers !== null) {
						if (typeof this.getCustomers.data !== "undefined" &&
							this.getCustomers.data.length !== "undefined") {
							customerData = this.getCustomers.data
						}
					}
                }
            } else {
                if (typeof this.getCustomers !== "undefined" && this.getCustomers !== null) {
					if (typeof this.getCustomers.data !== "undefined" &&
						this.getCustomers.data.length !== "undefined") {
						customerData = this.getCustomers.data
					}
				}
            }

			if (customerData.length > 0) {
				customerData.map((value) => {
					value.name = value.company_name

					if (value.address !== null) {
						value.address = value.address.replace(/,(?=[^\s])/g, ", ")
					} else {
						value.address = ""
					}

					if (value.phone == null) {
						value.phone = ""
					}

					if (value.emails == null) {
						value.emails = ""
					}
				})
			}

			return customerData
		},
		warehouseCustomers() {
			let data = []

			if (typeof this.getSearchedWarehouseCustomers !== 'undefined' && this.getSearchedWarehouseCustomers !== null) {
                if (this.searchWarehouseCustomers !== '' && this.getSearchedWarehouseCustomers.tab === 'warehouse_customers') {
                    if (typeof this.getSearchedWarehouseCustomers.data !== "undefined" &&
						this.getSearchedWarehouseCustomers.data.length !== "undefined") {
						data = this.getSearchedWarehouseCustomers.data
					}
                } else {
                    if (typeof this.getWarehouseCustomers !== "undefined" && this.getWarehouseCustomers !== null) {
						if (typeof this.getWarehouseCustomers.data !== "undefined" &&
							this.getWarehouseCustomers.data.length !== "undefined") {
							data = this.getWarehouseCustomers.data
						}
					}
                }
            } else {
                if (typeof this.getWarehouseCustomers !== "undefined" && this.getWarehouseCustomers !== null) {
					if (typeof this.getWarehouseCustomers.data !== "undefined" &&
						this.getWarehouseCustomers.data.length !== "undefined") {
						data = this.getWarehouseCustomers.data
					}
				}
            }

			return data
		},
		customerPagination() {
			if (this.searchCustomers === '') {
				return this.getCustomers
			} else {
				return this.getSearchedCustomer
			}
		},
		suppliersPagination() {
			if (this.searchVendors === '') {
				return this.getSuppliers
			} else {
				return this.getSearchedSuppliers
			}
		},
		warehouseCustomersPagination() {
			if (this.searchWarehouseCustomers === '') {
				return this.getWarehouseCustomers
			} else {
				return this.getSearchedWarehouseCustomers
			}
		},
		tabsComputed() {
			let final_header = this.tabsDefault

			if (!this.getHas3PLProviderWarehouse) {
				final_header = this.tabsDefault
			} else {
				final_header = this.tabs
			}

			return final_header
		}
	},
	watch: {
		dialog(val) {
			val || this.close()
		},
		dialogDelete(val) {
			val || this.closeDelete()
		},
	},
	created() {},
	methods: {
		...mapActions({
			fetchSuppliers: "suppliers/fetchSuppliers",
			fetchCustomers: "customers/fetchCustomers",
			setSearchedVendorsVal: 'suppliers/setSearchedVendorsVal',
			setSearchedCustomerVal: 'customers/setSearchedCustomerVal',
			// warehouse customers
			fetchWarehouseCustomers: 'warehouseCustomers/fetchWarehouseCustomers',
			checkWarehouse3PLProvider: 'warehouseCustomers/checkWarehouse3PLProvider',
			setWarehouseTypeHas3PL: 'warehouseCustomers/setWarehouseTypeHas3PL',
			setSearchedWarehouseCustomerVal: 'warehouseCustomers/setSearchedWarehouseCustomerVal'
		}),
		...globalMethods,
		onTabChange(i) {
			this.searchVendors = ""
			this.searchCustomers = ""
			this.searchWarehouseCustomers = ""

			if (i === 0) {
				this.setSearchedVendorsVal([])
				this.editedItem.isSupplier = true
			} else if (i === 1) {
				this.setSearchedCustomerVal([])
				this.editedItem.isSupplier = false
			} else {
				this.setSearchedWarehouseCustomerVal([])
			}
		},
		getItemPerTabCounts(i) {
			let count = "0"

			if (i === 0) {
				if (typeof this.getSearchedSuppliers !== 'undefined' && this.getSearchedSuppliers !== null) {
					if (this.searchVendors !== '' && this.getSearchedSuppliers.tab === 'vendors') {
						count = this.getSearchedSuppliers.total
					} else {
						count = this.getSuppliers.total
					}
				} else {
					count = this.getSuppliers.total
				}
			} else if (i === 1) {
				if (typeof this.getSearchedCustomer !== 'undefined' && this.getSearchedCustomer !== null) {
					if (this.searchCustomers !== '' && this.getSearchedCustomer.tab === 'customers') {
						count = this.getSearchedCustomer.total
					} else {
						count = this.getCustomers.total
					}
				} else {
					count = this.getCustomers.total
				}
			} else if (i === 2) {
				if (typeof this.getSearchedWarehouseCustomers !== 'undefined' && this.getSearchedWarehouseCustomers !== null) {
					if (this.searchWarehouseCustomers !== '' && this.getSearchedWarehouseCustomers.tab === 'warehouse_customers') {
						count = this.getSearchedWarehouseCustomers.total
					} else {
						count = this.getWarehouseCustomers.total
					}
				} else {
					count = this.getWarehouseCustomers.total
				}
			}

			let finalCount = count === undefined || count === 0 || count === "0" ? "0" : count

			return finalCount
		},
		addSupplier() {
			this.dialog = true
			this.editedItem.isSupplier = true
		},
		editSupplier(item) {
			this.editedIndex = this.suppliers.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.editedItem.isSupplier = true
			this.dialog = true
		},
		addCustomer() {
			this.dialog = true
			this.editedItem.isSupplier = false
		},
		editCustomer(item) {
			this.editedIndex = this.customers.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.editedItem.isSupplier = false
			this.dialog = true
		},
		addWarehouseCustomer() {
			this.dialogWarehouseCustomer = true
		},
		editWarehouseCustomer(item) {
			this.editedIndex = this.warehouseCustomers.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.editedItem.warehouse_customer_company_id = item.warehouse_customer_company_id
			this.editedItem.isSupplier = false
			this.dialogWarehouseCustomer = true
		},
		closeWarehouseCustomer() {
			this.dialogWarehouseCustomer = false
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem)
				this.editedIndex = -1
			})
		},
		close() {
			this.dialog = false
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem)
				this.editedIndex = -1
			})
		},
		deleteSupplier(item) {
			this.editedIndex = this.suppliers.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialogDelete = true
		},
		deleteSupplierConfirm() {
			this.suppliers.splice(this.editedIndex, 1)
			this.closeDelete()
		},
		deleteCustomer(item) {
			this.editedIndex = this.suppliers.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialogDelete = true
		},
		closeDelete() {
			this.dialogDelete = false
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem)
				this.editedIndex = -1
			})
		},
		deleteWarehouseCustomer() {},
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true
			} else {
				this.isMobile = false
			}
		},
		setToDefault() {
			this.editedItem = this.defaultItem
			this.close()
			this.dialog = true
		},
	},
	async mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "contact")
		
		let parms = {
			itemsPerPage: this.itemsPerPage,
			pageNumber: this.page
		}

		let parmsWarehouseCustomers = {
			id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
			page: this.page,
		}
		
		let id = (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id

		try {
			await this.checkWarehouse3PLProvider(id).then(async (res) => {
				if (typeof res !== 'undefined' && res.exist) {
					this.setWarehouseTypeHas3PL(true)
				} else {
					this.setWarehouseTypeHas3PL(false)
				}
			})
			await this.fetchSuppliers(parms)
			await this.fetchCustomers(parms)
			await this.fetchWarehouseCustomers(parmsWarehouseCustomers)			
		} catch (e) {
			this.notificationError(e)
		}
	},
	updated() {},
}
</script>

<style lang="scss">
@import "../assets/scss/pages_scss/supplier/supplier.scss";
@import "../assets/scss/buttons.scss";
@import "../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../assets/scss/inputs.scss";
</style>
