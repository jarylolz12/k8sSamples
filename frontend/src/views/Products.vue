<template>
	<div class="products-wrapper" v-resize="onResize">
		<!-- Products Table Desktop View -->
		<ProductDesktopTable
			:searchVal.sync="search"
			:items="products"
			:categoryLists="categoryListData"
			@addProduct="addProduct"
			@import="importIsOpen = true"
			@editProduct="editProduct"
			@deleteProductItem="deleteProductItem"
			@viewProductItem="viewProductItem"
			:isMobile="isMobile"
			v-if="!isMobile"
			:productsNextPageLoading="productsNextPageLoading"
			@handlePageChange="handlePageChange" />

		<!-- Products Table Mobile View -->
		<ProductMobileTable
			:searchVal.sync="search"
			:items="products"
			:categoryLists="categoryListData"
			@addProduct="addProduct"
			@import="importIsOpen = true"
			@editProduct="editProduct"
			@deleteProductItem="deleteProductItem"
			@viewProductItem="viewProductItem"
			:isMobile="isMobile"
			v-if="isMobile"
			:productsNextPageLoading="productsNextPageLoading"
			@handlePageChange="handlePageChange" />

		<ProductViewDialog
			:editedItemData.sync="editedProductItem"
			:dialogViewData.sync="dialogViewProduct"
			:isMobile="isMobile"
			:categoryLists="categoryListData"
			@editItem="editProduct"
			@deleteItem="deleteProductItem"
			@close="closeViewProduct" />
					
        <ProductAddDialog 
            :dialog.sync="dialogAddProduct"
            :editedIndex.sync="editedIndexProduct"
			:defaultItem.sync="defaultProductItem"
			:editedItem.sync="editedProductItem"
			:categoryLists="categoryListData"
			@close="closeProduct"
			@setToDefault="setToDefault"
			:isMobile="isMobile"
			:isInventoryPage="false"
			:isWarehouse3PL="false"
			:isWarehouse3PLProvider="false"
			:searchVal="search"
			:productsData="products" />

		<ConfirmDialog :dialogData.sync="dialogDeleteProduct">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>
			
			<template v-slot:dialog_title>
				<h2> Delete Products </h2>
			</template>

			<template v-slot:dialog_content>
				<div v-if="currentProductToDelete !== null">
					<p v-if="typeof currentProductToDelete.isFor !== 'undefined' && currentProductToDelete.isFor === 'parent'"> 
						Do you want to delete SKU
						<span class="name">{{ currentProductToDelete !== null ? currentProductToDelete.category_sku : '' }}</span>? 
						All the related versions will also be deleted.
					</p>

					<p v-if="typeof currentProductToDelete.isFor !== 'undefined' && currentProductToDelete.isFor === 'child'"> 
						Do you want to delete the product version associated with 
						<span class="name" v-for="(i, index) in currentProductToDelete.product_contact" :key="index">
							<span v-if="index < 3">
								<span>{{ i.company_name }}</span>
								<span v-if="index+1 < currentProductToDelete.product_contact.length">, </span>
							</span>

							<span v-if="index === 3"> and
								<span> {{ currentProductToDelete.product_contact.length - 3 }} more </span>               
							</span>
						</span> 
						from SKU {{ currentProductToDelete.category_sku }}?
					</p>
				</div>				
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="deleteProductConfirm" text :disabled="getDeleteProductLoading">
					<span v-if="!getDeleteProductLoading">Delete</span>
                    <span v-if="getDeleteProductLoading">Deleting...</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeDeleteProduct" :disabled="getDeleteProductLoading">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

    	<ImportEntityDialog
			ref="importProductEntityDialog"
			:importFn="importProducts"
			:template-url="templateURL"
			:isOpen.sync="importIsOpen"/>
	</div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import ProductDesktopTable from '../components/Tables/Products/ProductDesktopTable.vue'
import ProductMobileTable from '../components/Tables/Products/ProductMobileTable.vue'
import ProductAddDialog from '../components/ProductComponents/Dialog/ProductAddDialog.vue'
import ProductViewDialog from '../components/ProductComponents/Dialog/ProductViewDialog.vue'
import ConfirmDialog from '../components/Dialog/GlobalDialog/ConfirmDialog.vue'
import globalMethods from '../utils/globalMethods'
import _ from 'lodash'
import ImportEntityDialog from "@/components/Dialog/ImportEntityDialog";

export default {
	name: "Products",
	components: {
		ImportEntityDialog,
		ProductDesktopTable,
		ProductMobileTable,
		ProductAddDialog,
		ProductViewDialog,
		ConfirmDialog
	},
	data: () => ({
		templateURL: `${process.env.VUE_APP_PO_URL}/products/export-template`,
		importIsOpen: false,
		search: '',
		dialogAddProduct: false,
		editedIndexProduct: -1,
		editedProductItem: {
			sku: null,
			name: '',
			category_id: null,
			description: '',
			units_per_carton: '',
			image: null,
			classification_code: '',
			additional_classification_code: '',
			duty_rate: '',
			unit_price: '',
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,			
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_weight: {
				value: '',
				unit: 'kg'
			},
			preferred_unit_quantity: '',
			warehouse_customer_id: null,
			product_contact: []
		},
		defaultProductItem: {
			sku: null,
			name: '',
			category_id: null,
			description: '',
			units_per_carton: '',
			image: null,
			classification_code: '',
			additional_classification_code: '',
			duty_rate: 0,
			unit_price: '',
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_weight: {
				value: '',
				unit: 'kg'
			},
			preferred_unit_quantity: '',
			warehouse_customer_id: null,
			product_contact: []
		},
		dialogViewProduct: false,
		dialogDeleteProduct: false,
		currentProductToDelete: null,		
		isMobile: false,
		categoryLists: [],
		productsNextPageLoading: false,
		categoryListData: [],
        current_page_is: 1,
        lastDataCheck: [],
	}),
	computed: {
		...mapGetters({
            getCategories: 'category/getCategories',
			getProducts: 'products/getProducts',
			getProductsLoading: 'products/getProductsLoading',
			getUser: 'getUser',
			getDeleteProductLoading: 'products/getDeleteProductLoading',
			getCategoriesDropdown: 'category/getCategoriesDropdown',
			getAllCategoriesDropdown: 'category/getAllCategoriesDropdown',
			getSearchedProducts: 'products/getSearchedProducts',
			getHas3PLProviderWarehouse: 'warehouseCustomers/getHas3PLProviderWarehouse',
			getProductContacts: 'products/getProductContacts'
        }),
		products() {
			let productItemLists = []

			if (typeof this.getSearchedProducts !== 'undefined' && this.getSearchedProducts !== null) {
                if (this.search !== '' && this.getSearchedProducts.tab === 'products') {
                    productItemLists = this.getSearchedProducts.products
                } else {
                    if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
						if (typeof this.getProducts.products !== 'undefined' && Array.isArray(this.getProducts.products) && this.getProducts.products.length > 0) {
							let productLists = this.getProducts.products

							productLists.map(product => {
								let { sku, category_id, carton_dimensions, unit_dimensions, unit_weight, ...otherItems } = product
								let sku_with_category = category_id + '-' + sku

								if (typeof carton_dimensions !== 'undefined') {
									if (typeof carton_dimensions.l === 'undefined' || carton_dimensions.l === 'undefined') {
										carton_dimensions.l = ''
									}

									if (typeof carton_dimensions.w === 'undefined' || carton_dimensions.w === 'undefined') {
										carton_dimensions.w = ''
									}

									if (typeof carton_dimensions.h === 'undefined' || carton_dimensions.h === 'undefined') {
										carton_dimensions.h = ''
									}

									if (typeof carton_dimensions.uom === 'undefined' || carton_dimensions.uom === 'undefined') {
										carton_dimensions.uom = 'cm'
									}
								}

								if (typeof unit_dimensions !== 'undefined') {
									if (typeof unit_dimensions.l === 'undefined' || unit_dimensions.l === 'undefined') {
										unit_dimensions.l = ''
									}

									if (typeof unit_dimensions.w === 'undefined' || unit_dimensions.w === 'undefined') {
										unit_dimensions.w = ''
									}

									if (typeof unit_dimensions.h === 'undefined' || unit_dimensions.h === 'undefined') {
										unit_dimensions.h = ''
									}

									if (typeof unit_dimensions.uom === 'undefined' || unit_dimensions.uom === 'undefined') {
										unit_dimensions.uom = 'cm'
									}
								}

								if (typeof unit_weight !== 'undefined') {
									if (typeof unit_weight.unit === 'undefined' || unit_weight.unit === 'undefined') {
										unit_weight.unit = 'kg'
									}

									if (typeof unit_weight.value === 'undefined' || unit_weight.value === 'undefined') {
										unit_weight.value = ''
									}
								}

								return {
									...otherItems,
									sku,
									category_id,
									sku_with_category,
									carton_dimensions,
									unit_dimensions,
									unit_weight
								}
							})
						
							productItemLists = productLists
						}
					}
                }
            } else {
                if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
					if (typeof this.getProducts.products !== 'undefined' && Array.isArray(this.getProducts.products) && this.getProducts.products.length > 0) {
						let productLists = this.getProducts.products

						productLists.map(product => {
							let { sku, category_id, carton_dimensions, unit_dimensions, unit_weight, ...otherItems } = product
							let sku_with_category = category_id + '-' + sku

							if (typeof carton_dimensions !== 'undefined') {
								if (typeof carton_dimensions.l === 'undefined' || carton_dimensions.l === 'undefined') {
									carton_dimensions.l = ''
								}

								if (typeof carton_dimensions.w === 'undefined' || carton_dimensions.w === 'undefined') {
									carton_dimensions.w = ''
								}

								if (typeof carton_dimensions.h === 'undefined' || carton_dimensions.h === 'undefined') {
									carton_dimensions.h = ''
								}

								if (typeof carton_dimensions.uom === 'undefined' || carton_dimensions.uom === 'undefined') {
									carton_dimensions.uom = 'cm'
								}
							}

							if (typeof unit_dimensions !== 'undefined') {
								if (typeof unit_dimensions.l === 'undefined' || unit_dimensions.l === 'undefined') {
									unit_dimensions.l = ''
								}

								if (typeof unit_dimensions.w === 'undefined' || unit_dimensions.w === 'undefined') {
									unit_dimensions.w = ''
								}

								if (typeof unit_dimensions.h === 'undefined' || unit_dimensions.h === 'undefined') {
									unit_dimensions.h = ''
								}

								if (typeof unit_dimensions.uom === 'undefined' || unit_dimensions.uom === 'undefined') {
									unit_dimensions.uom = 'cm'
								}
							}

							if (typeof unit_weight !== 'undefined') {
								if (typeof unit_weight.unit === 'undefined' || unit_weight.unit === 'undefined') {
									unit_weight.unit = 'kg'
								}

								if (typeof unit_weight.value === 'undefined' || unit_weight.value === 'undefined') {
									unit_weight.value = ''
								}
							}

							return {
								...otherItems,
								sku,
								category_id,
								sku_with_category,
								carton_dimensions,
								unit_dimensions,
								unit_weight
							}
						})
						
						productItemLists = productLists
					}
				}
            }

			return productItemLists
        },
	},
	watch: {
		dialog (val) {
			val || this.close()
		},
		dialogView (val) {
			val || this.closeView()
		},
	},
	created () {
	},
	methods: {
		...globalMethods,
		...mapActions({
            fetchCategories: 'category/fetchCategories',
			fetchProducts: 'products/fetchProducts',
			createProducts: 'products/createProducts',
			importProducts: 'products/importProducts',
			getCurrentCategory: 'category/getCurrentCategory',
			deleteProduct: 'products/deleteProduct',
			updateProducts: 'products/updateProducts',
			fetchCategoriesDropdown: 'category/fetchCategoriesDropdown',
			setAllCategoriesDropdown: 'category/setAllCategoriesDropdown',
			fetchSearchedProducts: 'products/fetchSearchedProducts',
			setSearchedProductsLoading: 'products/setSearchedProductsLoading',
			fetchWarehouseCustomersDropdown: 'warehouseCustomers/fetchWarehouseCustomersDropdown',
			checkWarehouse3PLProvider: 'warehouseCustomers/checkWarehouse3PLProvider',
			setWarehouseTypeHas3PL: 'warehouseCustomers/setWarehouseTypeHas3PL',
            fetchProductContacts: 'products/fetchProductContacts',
			setProductContactsLoading: 'products/setProductContactsLoading'
        }),
		onResize() {
            if (window.innerWidth < 1023) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },    		
        addProduct() {
			this.editedProductItem = {
				sku: null,
				name: '',
				category_id: null,
				description: '',
				units_per_carton: '',
				image: null,
				classification_code: '',
				additional_classification_code: '',
				duty_rate: '',
				unit_price: '',
				upc_number: '',
				carton_upc: '',
				is_for_classification_code: 1,			
				userClassification: 0,
				country_from: '',
				country_to: '',
				product_classification_description: '',
				carton_dimensions: {
					l: '',
					w: '',
					h: '',
					uom: 'cm'
				},
				unit_dimensions: {
					l: '',
					w: '',
					h: '',
					uom: 'cm'
				},
				unit_weight: {
					value: '',
					unit: 'kg'
				},
				preferred_unit_quantity: '',
				warehouse_customer_id: null,
				product_contact: []
			}

            this.dialogAddProduct = true
        },
        closeProduct() {
			this.$nextTick(() => {
				this.editedProductItem = Object.assign({}, this.defaultProductItem)
				this.editedIndexProduct = -1				
			})
			this.setToDefault()
			this.dialogAddProduct = false
        },
		setToDefault() {
			this.editedProductItem = {
				sku: null,
				name: '',
				category_id: null,
				description: '',
				units_per_carton: '',
				image: null,
				classification_code: '',
				additional_classification_code: '',
				duty_rate: '',
				unit_price: '',
				upc_number: '',
				carton_upc: '',
				is_for_classification_code: 1,			
				userClassification: 0,
				country_from: '',
				country_to: '',
				product_classification_description: '',
				carton_dimensions: {
					l: '',
					w: '',
					h: '',
					uom: 'cm'
				},
				unit_dimensions: {
					l: '',
					w: '',
					h: '',
					uom: 'cm'
				},
				unit_weight: {
					value: '',
					unit: 'kg'
				},
				preferred_unit_quantity: '',
				warehouse_customer_id: null,
				product_contact: []
			}
			this.editedIndexProduct = -1
			this.dialogAddProduct = true
        },
		editProduct(data) {
			let { isFor, product, index } = data

			if (isFor !== 'child') {
				this.isDuplicateEdit = false
				this.editedIndexProduct = this.products.indexOf(product)
				if (this.editedIndexProduct==-1) {
					if (typeof product.id!=='undefined') {
						this.editedIndexProduct = _.findIndex(this.products, e => (e.id == product.id))	
					}
				}
			} else {
				this.editedIndexProduct = index
				this.isDuplicateEdit = true
			}

			if (typeof index !== 'undefined') {
				this.editedIndexProduct = index
			}

			let tempProduct = { ...product }

			tempProduct.carton_dimensions = typeof tempProduct.carton_dimensions === 'string' 
				? JSON.parse(tempProduct.carton_dimensions) : tempProduct.carton_dimensions

			tempProduct.unit_dimensions = typeof tempProduct.unit_dimensions === 'string' 
				? JSON.parse(tempProduct.unit_dimensions) : tempProduct.unit_dimensions

			tempProduct.unit_weight = typeof tempProduct.unit_weight === 'string'
				? JSON.parse(tempProduct.unit_weight) : tempProduct.unit_weight

			
			if (!tempProduct.carton_dimensions) {
				tempProduct = {...tempProduct, carton_dimensions: {
					l: '',
					w: '',
					h: '',
					uom: 'cm'
				}}
			}

			if (!tempProduct.unit_dimensions) {
				tempProduct = {...tempProduct, unit_dimensions: {
					l: '',
					w: '',
					h: '',
					uom: 'cm'
				}}
			}

			if (!tempProduct.unit_weight) {
				tempProduct = {...tempProduct, unit_weight: {
					value: '',
					unit: 'kg'
				}}
			}

			if(!tempProduct.is_for_classification_code) {
				tempProduct = {...tempProduct, is_for_classification_code: 0, userClassification: 1}
			}

			if(!tempProduct.classification_code || tempProduct.classification_code === 'null') {
				tempProduct = {...tempProduct, classification_code: ''}
			} 

			if (!tempProduct.additional_classification_code || tempProduct.additional_classification_code === 'null') {
				tempProduct = {...tempProduct, additional_classification_code: ''}
			}

			tempProduct.isDuplicate = isFor == 'edit' ? false : isFor === 'child' ? false : true
			if (tempProduct.isDuplicate) {
				tempProduct.duplicate_contact = tempProduct.product_contact
				tempProduct.product_contact = []
			}
			this.editedProductItem = { ...tempProduct }
			this.dialogAddProduct = true
        },
		deleteProductItem(data) {
			let { product, isFor } = data
			this.dialogDeleteProduct = true
			this.currentProductToDelete = product
			this.currentProductToDelete.isFor = isFor
		},
		async deleteProductConfirm() {
			if (this.currentProductToDelete !== null) {
				try {
					let storePagination = this.$store.state.products.products
					let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1

					await this.deleteProduct(this.currentProductToDelete.id)

					if (this.search !== '') {
						let searchedPage = typeof this.getSearchedProducts !== 'undefined' ? this.getSearchedProducts.current_page : 1

						if (this.products.length === 1 && this.getSearchedProducts.current_page !== 1) {
							searchedPage = searchedPage - 1
						}
						
						let passedData = {
							method: "get",
							url: 'https://po.shifl.com/api/products',
							params: {
								search: this.search,
								page: searchedPage
							}
						}

						try {
							passedData.tab = 'products'
							await this.fetchSearchedProducts(passedData)
							this.notificationCustom('Product has been deleted.')
							this.closeDeleteProduct()

							page = 1
							await this.fetchProducts(page)
						} catch(e) {
							this.notificationError(e)
							this.setSearchedProductsLoading(false)
							console.log(e, 'Search error')
						}
					} else {
						if (this.products.length === 1 && storePagination.current_page !== 1) {
							page = page - 1
						}
						this.notificationCustom('Product has been deleted.')
						this.closeDeleteProduct()
						await this.fetchProducts(page)
					}
				} catch (e) {
					this.closeDeleteProduct()
					this.notificationError(e)
				}
			}
		},
		closeDeleteProduct() {
			this.dialogDeleteProduct = false
		},
		viewProductItem(data) {
			let { item, index, isFor } = data
			this.editedProductItem = Object.assign({}, item)
			this.editedProductItem.isFor = isFor
			
			if (typeof index !== 'undefined') {
				this.editedProductItem.currentProductChildIndex = index
			}
			this.dialogViewProduct = true
		},
		closeViewProduct() {
			this.$nextTick(() => {
				this.editedProductItem = Object.assign({}, this.defaultProductItem)
				this.editedIndexProduct = -1
			})

			this.dialogViewProduct = false
		},
		async handlePageChange(page) {
			if (page !== null) {
				let storePagination = this.$store.state.products.products

				try {
					if (storePagination.old_page !== page) {
						this.productsNextPageLoading = true
						await this.fetchProducts(page)
						storePagination.old_page = page
						this.productsNextPageLoading = false
					}
				} catch(e) {
					this.notificationError(e)
					console.log(e)
				}
			}
        },
		async loadMoreCategories() {
			if (this.current_page_is < this.getCategoriesDropdown.last_page) {
				this.current_page_is++

				try {
					await this.fetchCategoriesDropdown(this.current_page_is)

					if (typeof this.getCategoriesDropdown !== 'undefined' && this.getCategoriesDropdown !== null && 
						typeof this.getCategoriesDropdown.categories !== 'undefined' && 
						Array.isArray(this.getCategoriesDropdown.categories) && 
						this.getCategoriesDropdown.categories.length > 0) {

						let newloaddata = this.getCategoriesDropdown.categories.map((value) => {
							let nameWithId = value.name + ' (' + value.id + ')'

							return {
								id: value.id,
								name: value.name,
								nameWithId
							}
						})

						this.categoryListData = [...this.categoryListData, ...newloaddata]

						if (this.current_page_is < this.getCategoriesDropdown.last_page) {
							this.loadMoreCategories()
						}
						this.setAllCategoriesDropdown(this.categoryListData)
					} else {
						this.categoryListData
						this.setAllCategoriesDropdown(this.categoryListData)
					}
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
	},
	async mounted() {
		//set current page
		this.$store.dispatch("page/setPage","products")
		this.setProductContactsLoading(true)

		try {
			let storePagination = this.$store.state.products.products
			let currentPage = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
			let id = (typeof this.getUser=='string') ? 
				JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id

			await this.checkWarehouse3PLProvider(id).then(async (res) => {
				if (typeof res !== 'undefined' && res.exist) {
					this.setWarehouseTypeHas3PL(true)					
				} else {
					this.setWarehouseTypeHas3PL(false)
				}
			})

			await this.fetchProducts(currentPage)
            await this.fetchProductContacts()

			if (this.getAllCategoriesDropdown.length === 0) {
				await this.fetchCategoriesDropdown(1)
				
				if (typeof this.getCategoriesDropdown !== 'undefined' && this.getCategoriesDropdown !== null && 
					typeof this.getCategoriesDropdown.categories !== 'undefined' &&
					Array.isArray(this.getCategoriesDropdown.categories) &&
					this.getCategoriesDropdown.categories.length > 0) {

					this.categoryListData = this.getCategoriesDropdown.categories.map((value) => {
						let nameWithId = value.name + ' (' + value.id + ')'
						
						return {
							id: value.id,
							name: value.name,
							nameWithId
						}
					})

					this.lastDataCheck = this.categoryListData
									
					if (this.current_page_is < this.getCategoriesDropdown.last_page) {
						this.loadMoreCategories()
					}
				} else {
					this.categoryListData = []
					this.lastDataCheck = []
				}
			} else {
				this.categoryListData = this.getAllCategoriesDropdown				
			}

			this.setAllCategoriesDropdown(this.categoryListData)

			if (typeof this.getHas3PLProviderWarehouse !== 'undefined' && this.getHas3PLProviderWarehouse) {
				let parmsWarehouseCustomers = {
					id: (typeof this.getUser=='string') ? 
						JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
					page: 1,
				}
				await this.fetchWarehouseCustomersDropdown(parmsWarehouseCustomers)
			}
		} catch (e) {
			this.notificationError(e)
		}
	},
	updated() {}
}
</script>

<style lang="scss">
@import '../assets/scss/pages_scss/product/product.scss';
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../assets/scss/buttons.scss';
</style>
