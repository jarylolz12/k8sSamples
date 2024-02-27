<template>
    <div class="archived-products-container-wrapper" v-resize="onResize">
        <div class="archived-products-content-wrapper">
            <div class="overlay" :class="handleLoadingShow ? 'show' : ''">  
                <div class="preloader" v-if="(handleLoadingShow)">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>       
            </div>

            <div class="archived-products-main-header">
                <button icon dark class="btn-back-report" style="color: #0171A1;" @click="clickBack">
                    <v-icon>mdi-chevron-left</v-icon>
                    <span>Go Back</span>
                </button>
                <div class="archived-products-header mt-2">
                    <h3 class="archived-products-title">Archived Products</h3>
                </div>
            </div>

            <div class="archived-products-table-content">
                <v-data-table
                    :headers="headersDefault"
                    :items="getArchivedProductsLists"
                    :page.sync="getCurrentPage"
                    :items-per-page="getItemsPerPage"
                    hide-default-footer
                    mobile-breakpoint="1023"
                    @page-count="pageCount = $event"
                    class="product-table elevation-0"
                    v-bind:class="{
                        'no-data-table' : 
                            (typeof getArchivedProductsLists !== 'undefined' && getArchivedProductsLists.length === 0),
                        'no-current-pagination' : (getTotalPage <= 1),
                        'no-searched-data' : getSearchedDataClass,
                    }"
                    fixed-header
                    show-select
                    ref="my-table">
                    
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-spacer></v-spacer>

                            <div class="search-component d-flex">
                                <SearchComponent
                                    placeholder="Search Products"
                                    :searchValue.sync="search"
                                    :handleSearchComponent="handleSearchComponent"
                                    @handleSearch="handleSearch"
                                    @clearSearched="clearSearched" />
                            </div>

                            <!-- <v-btn color="primary" class="btn-white add-product-button">
                                Customize Table
                            </v-btn> -->
                        </v-toolbar>
                    </template>

                    <template v-slot:no-data>
                        <div class="no-data-preloader mt-4" v-if="getArchivedProductsLoading">
                            <v-progress-circular
                                :size="40"
                                color="#0171a1"
                                indeterminate
                                v-if="getArchivedProductsLoading">
                            </v-progress-circular>
                        </div>
                                        
                        <div class="no-data-wrapper" 
                            v-if="!getArchivedProductsLoading && getArchivedProductsLists.length == 0 && search === ''">
                            <div class="no-data-heading">
                                <img src="../assets/icons/empty-product-icon.svg" width="40px" height="42px" alt="">

                                <h3> No Archived Products </h3>
                                <p> No archived products listed. </p>
                            </div>
                        </div>

                        <div class="no-data-wrapper" 
                            v-if="!getArchivedProductsLoading && getArchivedProductsLists.length == 0 && search !== ''">
                            <div class="no-data-heading">
                                <img src="../assets/icons/empty-product-icon.svg" width="40px" height="42px" alt="">

                                <div>
                                    <h3> No matching result </h3>
                                    <p> We couldn’t find any product that matches your search term.
                                        <br/> Have you spelled it correctly? 
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-slot:[`item.sku`]="{ item }">
                        <p class="mb-0 product-sku-text">
                            <span v-if="item.category_id !== null">{{ item.category_id }}-</span>
                            <span>{{ (item.sku !== null && item.sku !== '' ? item.sku : '--') }}</span>
                        </p>
                    </template>

                    <template v-slot:[`item.name`]="{ item }">
                        <div class="description-wrapper">
                            <div class="description-img">
                                <img :src="getImgUrl(item.image)" v-bind:alt="item.image" width="50px" height="50px">
                            </div>
                            <div class="info-wrapper">
                                <p class="desc-info">{{ item.name }}</p>
                                <p class="category">{{ item.category_name }}</p>
                            </div>
                        </div>
                    </template>

                    <template v-slot:[`item.contact`]="{ item }">
                        <p class="mb-0 product-sku-text">
                            {{ (typeof item.contact !== 'undefined' && item.contact !== '' ? item.contact : '--') }}
                        </p>
                    </template>

                    <template v-slot:[`item.total_inventory`]="{ item }">
                        <p class="mb-0 product-sku-text">
                            {{ (typeof item.total_inventory !== 'undefined' && item.total_inventory !== '' ? item.total_inventory : '--') }}
                        </p>
                    </template>

                    <template v-slot:[`item.available`]="{ item }">
                        <p class="mb-0 product-sku-text">
                            {{ (typeof item.available !== 'undefined' && item.available !== '' ? item.available : '0') }}
                        </p>
                    </template>
                    
                    <template v-slot:[`item.units_per_carton`]="{ item }">
                        <span>{{ item.units_per_carton }} Units</span>
                    </template>

                    <template v-slot:[`item.unit_price`]="{ item }">
                        <span>${{ item.unit_price !== null && item.unit_price !== '' ? parseFloat(item.unit_price).toFixed(2) : 0 }}</span>
                    </template>

                    <template v-slot:[`item.actions`]="{ item }">
                        <div class="actions mr-2">
                            <button class="btn-white" @click.stop="unarchiveProduct(item, false)">                    
                                <img src="@/assets/icons/archive-blue.svg" class="mr-1" width="14px" height="14px">
                                <span style="font-size:12px;" class="font-medium">Unarchive</span>
                            </button>
                        </div>
                    </template>
                </v-data-table>

                <PaginationComponent 
                    :totalPage.sync="getTotalPage"
                    :currentPage.sync="getCurrentPage"
                    @handlePageChange="handlePageChange"
                    :isMobile="isMobile" />
            </div>
        </div>

        <ConfirmDialog :dialogData.sync="showUnarchiveDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2>Unarchive Product</h2>
			</template>

			<template v-slot:dialog_content>
				<p>Are you sure you want to unarchive this product? Once you confirm it will only be accessible through the products page.</p>
			</template>

			<template v-slot:dialog_actions>
                <v-btn class="btn-blue" text @click="unarchiveProduct(currentSelectedProduct, true)" :disabled="getDoUnarchivedProductsLoading">
					{{ getDoUnarchivedProductsLoading ? 'Unarchiving...' : 'Unarchive Product' }}
				</v-btn>

				<v-btn class="btn-white" text @click="closeUnarchiveProduct" :disabled="getDoUnarchivedProductsLoading">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
// import _ from 'lodash'
import SearchComponent from '../components/SearchComponent/SearchComponent.vue'
import PaginationComponent from '../components/PaginationComponent/PaginationComponent'
import ConfirmDialog from '../components/Dialog/GlobalDialog/ConfirmDialog.vue'
import globalMethods from '../utils/globalMethods'
import inventoryGlobalMethods from '../utils/inventoryMethods/inventoryGlobalMethods'

import axios from 'axios'
var cancel
var CancelToken = axios.CancelToken

export default {
    name: "ArchivedProducts",
    props: [],
	components: { 
        SearchComponent,
        PaginationComponent,
        ConfirmDialog
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        headersDefault: [
			{
				text: "Sku",
                align: "start",
                sortable: false,
                value: "sku",
                width: "12%", 
                fixed: true
			},
			{
				text: "Name & Category",
                align: "start",
                sortable: false,
                value: "name",
                width: "20%", 
                fixed: true
			},
            {
				text: "Contact",
                align: "start",
                sortable: false,
                value: "contact",
                width: "12%", 
                fixed: true
			},
            {
				text: "Total Inventory",
                align: "start",
                sortable: false,
                value: "total_inventory",
                width: "", 
                fixed: true
			},
            {
				text: "Available",
                align: "end",
                sortable: false,
                value: "available",
                width: "", 
                fixed: true
			},
			{
				text: "In Each",
                align: "end",
                sortable: false,
                value: "units_per_carton",
                width: "", 
                fixed: true
			},
            {
				text: "Unit Price",
                align: "end",
                sortable: false,
                value: "unit_price",
                width: "", 
                fixed: true
			},
			{
				text: "",
                align: "end",
                sortable: false,
                value: "actions",
                width: "12%", 
                fixed: true
			},
		],
        search: '',
        isMobile: false,
        showUnarchiveDialog: false,
        currentSelectedProduct: null,
        archivedProductsNextPageLoading: false,
        typingTimeout: 0
	}),
	computed: {
		...mapGetters({
            getArchivedProducts: 'products/getArchivedProducts',
            getArchivedProductsLoading: 'products/getArchivedProductsLoading',
            getDoUnarchivedProductsLoading: 'products/getDoUnarchivedProductsLoading',
            getSearchedArchivedProducts: 'products/getSearchedArchivedProducts',
            getSearchedArchivedProductsLoading: 'products/getSearchedArchivedProductsLoading'
        }),
        getArchivedProductsLists() {
            let items = []

            if (typeof this.getSearchedArchivedProducts !== 'undefined' && this.getSearchedArchivedProducts !== null) {
                if (this.search !== '' && this.getSearchedArchivedProducts.tab === 'archived-products') {
                    items = this.getSearchedArchivedProducts.data
                } else {
                    if (typeof this.getArchivedProducts !== 'undefined' && this.getArchivedProducts !== null) {
                        if (typeof this.getArchivedProducts.data !== 'undefined' && this.getArchivedProducts.data !== null) {
                            items = this.getArchivedProducts.data
                        }
                    }
                }
            } else {
                if (typeof this.getArchivedProducts !== 'undefined' && this.getArchivedProducts !== null) {
                    if (typeof this.getArchivedProducts.data !== 'undefined' && this.getArchivedProducts.data !== null) {
                        items = this.getArchivedProducts.data
                    }
                }
            }

            return items
        },
        getTotalPage: {
            get() {
				let totalPage = 1                

                if (typeof this.getSearchedArchivedProducts !== 'undefined' && this.getSearchedArchivedProducts !== null) {
                    if (this.search !== '' && this.getSearchedArchivedProducts.tab === 'archived-products') {
                        if (typeof this.getSearchedArchivedProducts.last_page !== 'undefined') {
                            totalPage = this.getSearchedArchivedProducts.last_page
                        }
                    } else {
                        if (typeof this.getArchivedProducts !== 'undefined' && this.getArchivedProducts !== null) {
                            if (typeof this.getArchivedProducts.last_page !== 'undefined') {
                                totalPage = this.getArchivedProducts.last_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getArchivedProducts !== 'undefined' && this.getArchivedProducts !== null) {
                        if (typeof this.getArchivedProducts.last_page !== 'undefined') {
                            totalPage = this.getArchivedProducts.last_page
                        }
                    }
                }

				return totalPage
            }
        },
        getCurrentPage: {
            get() {
                let currentPage = 1

                if (typeof this.getSearchedArchivedProducts !== 'undefined' && this.getSearchedArchivedProducts !== null) {
                    if (this.search !== '' && this.getSearchedArchivedProducts.tab === 'archived-products') {
                        if (typeof this.getSearchedArchivedProducts.current_page !== 'undefined') {
                            currentPage = this.getSearchedArchivedProducts.current_page
                        }
                    } else {
                        if (typeof this.getArchivedProducts !== 'undefined' && this.getArchivedProducts !== null) {
                            if (typeof this.getArchivedProducts.current_page !== 'undefined') {
                                currentPage = this.getArchivedProducts.current_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getArchivedProducts !== 'undefined' && this.getArchivedProducts !== null) {
                        if (typeof this.getArchivedProducts.current_page !== 'undefined') {
                            currentPage = this.getArchivedProducts.current_page
                        }
                    }
                }

				return currentPage
            },
            set() {
                return {}
            }
        }, 
		getItemsPerPage: {
            get() {
				let itemsPerPage = 1
                
                if (typeof this.getSearchedArchivedProducts !== 'undefined' && this.getSearchedArchivedProducts !== null) {
                    if (this.search !== '' && this.getSearchedArchivedProducts.tab === 'archived-products') {
                        if (typeof this.getSearchedArchivedProducts.per_page !== 'undefined') {
                            itemsPerPage = this.getSearchedArchivedProducts.per_page
                        }
                    } else {
                        if (typeof this.getArchivedProducts !== 'undefined' && this.getArchivedProducts !== null) {
                            if (typeof this.getArchivedProducts.per_page !== 'undefined') {
                                itemsPerPage = this.getArchivedProducts.per_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getArchivedProducts !== 'undefined' && this.getArchivedProducts !== null) {
                        if (typeof this.getArchivedProducts.per_page !== 'undefined') {
                            itemsPerPage = this.getArchivedProducts.per_page
                        }
                    }
                }

				return itemsPerPage
            }
        },
        handleLoadingShow() {
            if (this.search === '') {
                return this.archivedProductsNextPageLoading
            } else {
                return this.getSearchedArchivedProductsLoading
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.getArchivedProductsLists.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.getArchivedProductsLists.length === 0) {
                isShow = true
            }

            return isShow
        },
        getSearchedDataClass() {
            if (this.getArchivedProductsLists.length == 0 && this.search !== '') {
                return true
            } else {
                return false
            }
        },
	},
	created () {
	},
	methods: {
		...mapActions({ 
            fetchArchivedProducts: 'products/fetchArchivedProducts',
            setUnarchiveProduct: 'products/setUnarchiveProduct',
            fetchSearchedArchiveProducts: 'products/fetchSearchedArchiveProducts',
            setSearchedArchivedProductsLoading: 'products/setSearchedArchivedProductsLoading',
            setSearchedArchivedProductsVal: 'products/setSearchedArchivedProductsVal',
            setArchivedProductsVal: 'products/setArchivedProductsVal',
            fetchProducts: 'products/fetchProducts',
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        onResize() {
            if (window.innerWidth < 1023) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },
		getImgUrl(pic) {
			if (pic !== 'undefined' && pic !== null) {
				return pic				
			} else {
				return require('../assets/icons/default-product-icon.svg')
			}
		},
        clickBack() {
            this.$router.push(`/products`)
            this.setSearchedArchivedProductsVal([])
            this.setArchivedProductsVal([])
        },
        async unarchiveProduct(item, isConfirm) {
            this.showUnarchiveDialog = true

            if (!isConfirm) {
                this.currentSelectedProduct = item
            } else {
                try {
                    await this.setUnarchiveProduct(item.id)
                    this.notificationCustom('Product has been marked unarchived.')
                    this.closeUnarchiveProduct()

                    let page = this.getCurrentPage

                    if (this.search === '') {
                        if (this.getArchivedProductsLists.length === 1 && this.getCurrentPage !== 1) {
							page = page - 1
						}
                    } else {
                        let searchedPage = typeof this.getSearchedArchivedProducts !== 'undefined' ? this.getSearchedArchivedProducts.current_page : 1

						if (this.getArchivedProductsLists.length === 1 && this.getSearchedArchivedProducts.current_page !== 1) {
							searchedPage = searchedPage - 1
						}
						
						let passedData = {
							method: "get",
							url: 'https://po.shifl.com/api/archive-products',
							params: {
								search: this.search,
								page: searchedPage
							}
						}

						try {
							passedData.tab = 'archived-products'
							await this.fetchSearchedArchiveProducts(passedData)
							page = 1
						} catch(e) {
							this.notificationError(e)
							this.setSearchedArchivedProductsLoading(false)
						}
                    }

                    await this.fetchArchivedProducts(page)
                    await this.fetchProducts(1)
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        closeUnarchiveProduct() {
            this.currentSelectedProduct = null
            this.showUnarchiveDialog = false
        },
        handleSearch() {
            if (cancel !== undefined) {
                cancel()
            }            
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { search: this.search }
                this.setSearchedArchivedProductsLoading(true)
                this.apiCall(data)
            }, 500)
        },
        apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: `${process.env.VUE_APP_PO_URL}/archive-products`,
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: { search: this.search, page: 1 }
                }

                try {
                    passedData.tab = 'archived-products'
                    this.fetchSearchedArchiveProducts(passedData)
                } catch(e) {
                    this.notificationError(e)
                    this.setSearchedArchivedProductsLoading(false)
                    console.log(e, 'Search error')
                }
            } else {
                this.setSearchedArchivedProductsVal([])
            }
        },
        clearSearched() {
            this.search = ''
            this.setSearchedArchivedProductsVal([])
        },
        async handlePageChange(page) {
            if (this.search === '') {
                if (page !== null) {
                    let storePagination = this.$store.state.products.archivedProducts

                    try {
                        if (storePagination.old_page !== page) {
                            this.archivedProductsNextPageLoading = true
                            await this.fetchArchivedProducts(page)
                            storePagination.old_page = page
                            this.archivedProductsNextPageLoading = false
                        }
                    } catch(e) {
                        this.notificationError(e)
                    }
                }
            } else {
                let data = { search: this.search, page }
                this.handlePageSearched(data)
            }

            this.handleScrollToTop()
        },
        async handlePageSearched(data) {
            let storePagination = this.$store.state.products.searchedArchivedProducts

            if (data !== null && this.search !== '') {
                if (storePagination.old_page !== data.page) {
                    storePagination.old_page = data.page

                    let passedData = {
                        method: "get",
                        url: `${process.env.VUE_APP_PO_URL}/archive-products`,
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: { search: this.search, page: data.page }
                    }

                    try {
                        passedData.tab = 'archived-products'
                        this.fetchSearchedArchiveProducts(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedArchivedProductsLoading(false)
                    }
                }                
            } else {
                this.setSearchedProductsVal([])
            }

            this.handleScrollToTop()
        },
        handleScrollToTop() {
            this.scrollTableToTop()
        },
	},
    async mounted() {
        //set current page
		this.$store.dispatch("page/setPage","products")

        try {
            await this.fetchArchivedProducts(1)
        } catch(e) {
            this.notificationError(e)
        }
    },
	updated() {}
}
</script>

<style lang="scss">
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../assets/scss/buttons.scss';
@import '../assets/scss/tables.scss';
@import '../assets/scss/pages_scss/product/archivedProducts.scss';
</style>
