<template>
    <div class="pos">
        <div class="searchHelp" id="dropdown" v-if="!isMobile">
            <img src="@/assets/images/search-icon.svg" alt="" class="search-icon" />

            <input
                :class="isOpen ? 'open' : 'close'"
                class="text-autocomplete"
                type="text"
                @input="handleSearch"
                id="input-search-data"
                v-model.trim="searchData"
                placeholder="Search anything..."
                autocomplete="off" />
            <br />

            <button v-if="searchData !== ''" class="close-btn" @click="clearInput">
                <img src="../assets/images/close.svg" alt="" width="18px">
            </button>

            <div class="search-results" :class="isOpen ? 'open-dropdown' : 'close-dropdown'" >
                <ul class="loading-search-wrapper" v-if="masterSearchLoading">
                    <div class="loading-search">
                        <v-progress-circular
                            :size="30"
                            color="#0171a1"
                            indeterminate>
                        </v-progress-circular>
                    </div>
                </ul>
                <!-- FOR PO Search Results -->
                <ul class="has-data" v-if="isPoResultShow && !masterSearchLoading && poResults.length > 0">
                    <div v-if="typeof poResults !== 'undefined' && poResults.length > 0">
                        <p class="dropdown-title"> Purchase Orders </p>
                        <div v-for="(data, index) in poResults" 
                            :key="index">

                            <li class="dropdown-data" >
                                <router-link :to="`/po-details/${data.id}`">
                                    <img src="../assets/images/search-shipment-icon.svg" alt="">
                                    <div>
                                        <p class="data-ref">{{ "PO # " + data.po_number }}</p>
                                        <p class="data-location">
                                            {{ (typeof data.status!=='undefined' && data.status!=='' && data.status!==null) ? ucWords(data.status) : 'No status' }}
                                        </p>
                                    </div>
                                </router-link>
                            </li>
                        </div>
                    </div> 
                </ul>

                <!-- FOR Products Search Results -->
                <ul class="has-data" v-if="isProductResultShow && !masterSearchLoading && productsData.length > 0">

                    <div v-if="typeof productsData !== 'undefined' && productsData.length > 0">
                        <p class="dropdown-title"> Products </p>

                        <div v-for="(data, index) in productsData" :key="index">
                            <li class="dropdown-data" v-on:click="openProductViewDialog(data)">
                                <router-link :to="`#`">
                                    <img src="../assets/images/empty-product-icon.svg" alt="" width="35px" height="35px" class="product-img">
                                     <div>
                                        <p class="data-ref">{{ "SKU #" + data.category_sku }}</p>
                                        <p class="data-location"> {{ data.name }}</p>
                                    </div>
                                </router-link>
                            </li>
                        </div>
                    </div> 
                </ul>

                <!-- FOR Shipments Search Results -->
                <ul class="has-data" v-if="isShipmentResultShow && !masterSearchLoading && searchResults.shipments !== null">
                    <div v-if="isShipmentResultShow && searchResults.shipments !== 'undefined' && searchResults.shipments !== null">
                        <p class="dropdown-title"> Shipments </p>

                        <div v-for="(data, index) in searchResults.shipments" :key="index">

                            <li class="dropdown-data" v-on:click="goToShipmentDetails(data)">
                                <router-link :to="`/shipment/${data.id}`">
                                    <img src="../assets/images/search-shipment-icon.svg" alt="">
                        
                                    <!-- if status is pending approval, cancel or pending -->
                                    <div v-if="data.Status == 'Pending Approval' || data.Status == 'Cancelled' || data.Status == 'Pending'">
                                        <p class="data-ref">{{ data.ReferenceID }}</p>
                                        <p class="data-location">
                                            {{ data.Departure.location !== 'undefined' 
                                                || data.Departure.location !== null 
                                                || typeof data.Departure.location == 'undefined'
                                                    ? data.Departure.location.toLowerCase() !=='not specified' ? `${data.Departure.location}(${data.Departure.etd}) - ` : data.search_contain_tracking ? `${data.search_tracking_from}(${data.search_tracking_etd}) - ` : 'Not Specified - ' : 'Not Specified'
                                            }}

                                            {{ data.Arrival.location !== 'undefined' && data.location !== null
                                                ? data.Arrival.location.toLowerCase() !=='not specified' ? data.Arrival.location : data.search_contain_tracking ? `${data.search_tracking_to} (${data.search_tracking_eta})`: 'Not Specified'
                                                : 'Not Specified'
                                            }}
                                        </p>
                                    </div>

                                    <!-- if status is completed, in transit or awaiting departure -->
                                    <div v-else>
                                        <p class="data-ref">{{ data.ReferenceID }}</p>
                                        <p class="data-location">
                                            {{ data.Departure.location !== 'undefined' 
                                                || data.Departure.location !== null 
                                                || typeof data.Departure.location == 'undefined'
                                                    ? data.Departure.location.toLowerCase()!=='not specified' ? data.Departure.location : data.search_contain_tracking ? data.search_tracking_from
                                                    : 'Not Specified' : 'Not Specified'
                                            }}

                                            {{ data.Departure.etd !== 'undefined' && data.Departure.etd !== null  
                                                ? data.Departure.etd.toLowerCase() !== 'not specified' ? `(${date(data.Departure.etd)}) - `
                                                : data.search_contain_tracking ? `${data.search_tracking_etd} - `
                                                    : '(Not Specified) - ' : '(Not Specified) - '
                                            }}

                                            {{ data.Arrival.location !== 'undefined' && data.location !== null
                                                ? data.Arrival.location.toLowerCase()!=='not specified' ? data.Arrival.location : data.search_contain_tracking ? data.search_tracking_to
                                                    : 'Not Specified' : 'Not Specified'
                                            }}

                                            {{ data.Arrival.eta !== 'undefined' && data.Arrival.eta !== null 
                                                ? data.Arrival.eta.toLowerCase() !== 'not specified' ? `(${date(data.Arrival.eta)})` : data.search_contain_tracking ? `(${data.search_tracking_eta})` : '(Not Specified)' : '(Not Specified)'
                                            }}
                                        </p>
                                    </div>
                                </router-link>
                            </li>
                        </div>
                    </div> 
                </ul>

                <!-- FOR Empty Search Results -->
                <ul class="no-match-data shipments" v-if="isShowEmptyResults"> 
                    <div>
                        <img src="../assets/images/no-data-found.svg" alt="" srcset="">
                        <h4>No Match Found!</h4>
                        <p>We couldn’t find any result that matches your search. 
                        <br/>Can you please check the spelling or try different search?
                        </p>
                    </div>
                </ul>
            </div>        
        </div>

        <div class="mobile-search-wrapper" v-if="isMobile">
            <img src="@/assets/images/search-icon.svg" alt="" class="search-icon" />
        </div>

        <ProductViewDialog
			:editedItemData.sync="editedProductItem"
			:dialogViewData.sync="dialogViewProduct"
			:isMobile="isMobile"
			:categoryLists="categoryListData"
			@editItem="editProduct"
			@deleteItem="deleteProductItem"
			@close="closeProductViewDialog" />

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
            :isWarehouse3PLProvider="false" />

        <DeleteDialog 
            :dialogData.sync="dialogDeleteProduct"
            :editedItemData.sync="currentProductToDelete"
            :editedIndexWarehouse.sync="editedIndexProduct"
            :defaultItemWarehouse.sync="defaultProductItem"
            @delete="deleteProductConfirm"
            @close="closeDeleteProduct"
            fromComponent="products"
            :loadingDelete="getDeleteProductLoading"
            componentName="Products" />
    </div>
</template>

<script>
import axios from "axios"
import _ from 'lodash'
import moment from 'moment'
import { mapActions, mapGetters } from "vuex";
import globalMethods from '../utils/globalMethods'
import ProductViewDialog from '../components/ProductComponents/Dialog/ProductViewDialog.vue'
import ProductAddDialog from '../components/ProductComponents/Dialog/ProductAddDialog.vue'
import DeleteDialog from '../components/Dialog/DeleteDialog.vue'

var cancel;
var CancelToken = axios.CancelToken;

export default {
    props: ["isMobile"],
    components: {
        ProductViewDialog,
        ProductAddDialog,
        DeleteDialog
    },
    data: () => ({
        loading: false,
        poResults: [],
        searchResults: {
            customers: null,
            shipments: null
        },
        noResults: false,
        searching: false,
        searchData: '',
        isOpen: false,
        productsSearchResults: [],
        productsData: [],
        productsCurrentPage: 1,
        dialogViewProduct: false,
        dialogAddProduct: false,
        dialogDeleteProduct: false,
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
			unit_price: 0,
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,			
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: 0,
				w: 0,
				h: 0,
				uom: 'cm'
			},
			unit_dimensions: {
				l: 0,
				w: 0,
				h: 0,
				uom: 'cm'
			},
			unit_weight: {
				value: 0,
				unit: 'kg'
			}
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
			unit_price: 0,
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: 0,
				w: 0,
				h: 0,
				uom: 'cm'
			},
			unit_dimensions: {
				l: 0,
				w: 0,
				h: 0,
				uom: 'cm'
			},
			unit_weight: {
				value: 0,
				unit: 'kg'
			}
		},
        currentProductToDelete: null,
        categoryListData: [],
        editedIndexProduct: 0,
    }),
    watch: {
        isOpen(dropOpen) {
            if (dropOpen) {
                document.addEventListener('click', this.closeIfClickedOutside);
            }
        },
    },
    methods: {
        ...mapActions({
            fetchMasterSearch: 'fetchMasterSearch',
            fetchShipmentDetails: 'fetchShipmentDetails',
            setMasterSearchLoading: 'setMasterSearchLoading',
            fetchProducts: 'products/fetchProducts',
            deleteProduct: 'products/deleteProduct'
        }),
        ...globalMethods,
        ucWords(str) {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                return $1.toUpperCase()
            })
        },
        trackingDateConvert(date) {
            return date !== null ?  moment.utc(date).format('MMM DD, YYYY') : null
        },
        clearInput() {
			setTimeout(() => {
				this.searching = false
				this.searchResults ={
                    customers: null,
                    shipments: null
                },
				this.searchData = ''
				this.isOpen = false
                document.getElementById("input-search-data").focus()
			}, 1)
		},
        clear() {
            this.searching = false
            this.searchResults = {
                customers: null,
                shipments: null
            },
            this.searchData = ''
            this.isOpen = false
        },
        // using lodash
        handleSearch: _.debounce(function() {
            this.preApiCall()
        }, 300),
        preApiCall() {
            if (cancel !== undefined) {
                cancel()
                console.log("cancelled");
            }
            this.apiCall(this.searchData)
        },
        async apiCall(searchData) {
            this.isOpen = true
            if ( searchData !== "undefined" && searchData !== "" ) {
                let passedData = {}
                if ( this.currentRouteName==='POs' || this.currentRouteName=='PO Details') {
                    passedData = {
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            s: searchData
                        }
                    }
                } else if (this.currentRouteName === 'Products') {
                    passedData = {
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: searchData
                        }
                    }
                } else {
                    passedData = {
                        method: "post",
                        url: "/search",
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search_text: searchData
                        }
                    }
                }
                try {
                    if ( this.currentRouteName==='POs' || this.currentRouteName=='PO Details') {
                        this.setMasterSearchLoading(true)
                        this.poResults = await this.$store.dispatch('po/fetchPoGlobalSearch', passedData)
                        this.setMasterSearchLoading(false)

                    } else if (this.currentRouteName === 'Products') {
                        this.productsCurrentPage = 1
                        this.setMasterSearchLoading(true)
                        this.productsSearchResults = await this.$store.dispatch('products/fetchProductsSearchGlobal', passedData)
                        this.setMasterSearchLoading(false)

                        if (typeof this.productsSearchResults !== 'undefined' && this.productsSearchResults !== null) {
                            if (typeof this.productsSearchResults.data !== 'undefined' && this.productsSearchResults.data !== null 
                                && Array.isArray(this.productsSearchResults.data)) {
                                this.productsData = this.productsSearchResults.data

                                await this.loadMoreProducts()
                            }                            
                        }
                    } else {
                        await this.fetchMasterSearch(passedData)
                        if (this.getAllMasterSearch !== 'undefined' && this.getAllMasterSearch !== null) {
                            if (this.getAllMasterSearch.shipments !== 'undefined' && this.getAllMasterSearch.shipments !== null) {
                                if (this.getAllMasterSearch.shipments.length !== 'undefined' && this.getAllMasterSearch.shipments.length !== 0) {           
                                    this.searchResults.shipments = this.getAllMasterSearch.shipments

                                    if ( this.searchResults.shipments.length > 0 ) {
                                        this.searchResults.shipments.map( (sr, key) => {
                                            this.searchResults.shipments[key].search_contain_tracking = false
                                            if ( sr.terminal_fortynine !== null ) {
                                                if ( typeof sr.terminal_fortynine!=='undefined' && typeof sr.terminal_fortynine.id!=='undefined' ) {
                                                    
                                                    this.searchResults.shipments[key].search_tracking_from = JSON.parse(sr.terminal_fortynine.attributes).port_of_lading_name
                                                    this.searchResults.shipments[key].search_tracking_to = JSON.parse(sr.terminal_fortynine.attributes).port_of_discharge_name
                                                    this.searchResults.shipments[key].search_tracking_eta = this.trackingDateConvert(sr.terminal_fortynine.eta)
                                                    this.searchResults.shipments[key].search_tracking_etd = this.trackingDateConvert(sr.terminal_fortynine.etd)
                                                    this.searchResults.shipments[key].search_contain_tracking = true
                                                }    
                                            }
                                        })
                                    }

                                } else {
                                    this.searchResults.shipments = null
                                }
                            }
                        } else {
                            this.searchResults = {
                                customers: null,
                                shipments: null
                            }
                        }    
                    }
                    
                } catch (e) {
                    throw Error(e)
                }
            } else {
                this.clear()
            }
        },
        closeIfClickedOutside(event) {
            if (typeof event !== 'undefined' && event !== 'undefined' && event !== null) {
                if (document.getElementById('dropdown').contains(event.target) !== null) {
                    if (!document.getElementById('dropdown').contains(event.target)) {
                        // document.getElementById("input-search-data").focus()
                        document.removeEventListener('click', this.closeIfClickedOutside)

                        this.isOpen = false
                        this.searchData = ''                        
                    }
                }
            }
        },
        async goToShipmentDetails(data) {
            try {
                this.isOpen = false
                this.searchData = ''
                await this.fetchShipmentDetails(data.id)
                this.clear()
            } catch (e) {
                console.log(e);
            }
        },
        date(date) {
            return moment(date).format('MM/DD/YYYY');
        },
        async loadMoreProducts() {
            if (this.productsCurrentPage < this.productsSearchResults.last_page ){ 
				this.productsCurrentPage++

				try {
                    let passedData = {
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: this.searchData,
                            page: this.productsCurrentPage
                        }
                    }

                    let loadProducts = await this.$store.dispatch('products/fetchProductsSearchGlobal', passedData)

					if (typeof loadProducts !== 'undefined' && loadProducts !== null && 
						typeof loadProducts.data !== 'undefined' && Array.isArray(loadProducts.data) && 
						loadProducts.data.length > 0) {

                        let newloaddata = loadProducts.data

						this.productsData = [...this.productsData, ...newloaddata]

						if (this.productsCurrentPage < this.productsSearchResults.last_page) {
							this.loadMoreProducts()
						}
					} else {
						this.productsData;
					}
				} catch (e) {
                    console.log(e);
				}
			}
        },
        openProductViewDialog(data) {
            let products = { ...data }        

            if (!products.carton_dimensions) {
                products = {...products, carton_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				}}
            } else {
                let parseDimensions = JSON.parse(products.carton_dimensions)
                products = {...products, carton_dimensions: parseDimensions }
            }

            if (!products.unit_dimensions) {
                products = {...products, unit_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				}}
            } else {
                let parseDimensions = JSON.parse(products.unit_dimensions)
                products = {...products, unit_dimensions: parseDimensions }
            }

            if (!products.unit_weight) {
                products = {...products, unit_weight: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				}}
            } else {
                let parseDimensions = JSON.parse(products.unit_weight)
                products = {...products, unit_weight: parseDimensions }
            }

            this.editedProductItem = Object.assign({}, products)
			this.dialogViewProduct = true
            this.isOpen = false
            this.searchData = ''
        },
        closeProductViewDialog() {
            this.$nextTick(() => {
				this.editedProductItem = Object.assign({}, this.defaultProductItem)
				this.editedIndexProduct = 0
			})

			this.dialogViewProduct = false
        },
        editProduct(product) {
			this.editedIndexProduct = this.productsData.indexOf(product)
            
			if (this.editedIndexProduct==-1) {
				if (typeof product.id!=='undefined') {
					this.editedIndexProduct = _.findIndex(this.productsData, e => (e.id == product.id))	
				}
			}

			let tempProduct = {...product}
			if(!tempProduct.carton_dimensions){
				tempProduct = {...tempProduct, carton_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				}}
			}

			if(!tempProduct.unit_dimensions){
				tempProduct = {...tempProduct, unit_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				}}
			}

			if(!tempProduct.unit_weight){
				tempProduct = {...tempProduct, unit_weight: {
					value: 0,
					unit: 'kg'
				}}
			}

			if(!tempProduct.is_for_classification_code){
				tempProduct = {...tempProduct, is_for_classification_code: 0, userClassification: 1}
			}

			if(!tempProduct.classification_code || tempProduct.classification_code === 'null'){
				tempProduct = {...tempProduct, classification_code: ''}
			} 

			if (!tempProduct.additional_classification_code || tempProduct.additional_classification_code === 'null') {
				tempProduct = {...tempProduct, additional_classification_code: ''}
			}
			
			this.editedProductItem = { ...tempProduct }
			this.dialogAddProduct = true
        },
        closeProduct() {
            this.$nextTick(() => {
				this.editedProductItem = Object.assign({}, this.defaultProductItem)
				this.editedIndexProduct = 0				
			})
			this.dialogAddProduct = false
        },
        deleteProductItem(item) {
			this.dialogDeleteProduct = true
			this.currentProductToDelete = item
		},
		async deleteProductConfirm() {
			if (this.currentProductToDelete !== null) {
				try {
					let storePagination = this.$store.state.products.products
					let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1

					await this.deleteProduct(this.currentProductToDelete.id)

					if (storePagination.products.length === 1 && storePagination.current_page !== 1) {
						page = page - 1
					}

					this.notificationCustom('Product has been deleted.')
					this.closeDeleteProduct()

					await this.fetchProducts(page)
				} catch (e) {
					this.closeDeleteProduct()
					this.notificationError(e)
				}
			}
		},
        closeDeleteProduct() {
			this.dialogDeleteProduct = false
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
				unit_price: 0,
				upc_number: '',
				carton_upc: '',
				is_for_classification_code: 1,			
				userClassification: 0,
				country_from: '',
				country_to: '',
				product_classification_description: '',
				carton_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				},
				unit_dimensions: {
					l: 0,
					w: 0,
					h: 0,
					uom: 'cm'
				},
				unit_weight: {
					value: 0,
					unit: 'kg'
				},
			}
        },
    },
    computed: {
        ...mapGetters({
            getAllMasterSearch: 'getAllMasterSearch',
            masterSearchLoading: 'masterSearchLoading',
            getDeleteProductLoading: 'products/getDeleteProductLoading',
            getAllCategoriesDropdown: 'category/getAllCategoriesDropdown'
        }),
        currentRouteName() {
            return this.$route.name
        },
        poGlobalSearchLoading() {
            return this.$store.getters['po/getPoGlobalSearchLoading']
        },
        isPoResultShow() {
            let isShow = false

            if (this.currentRouteName === 'POs' || this.currentRouteName === 'PO Details') {
                isShow = true
            }

            return isShow
        },
        isShipmentResultShow() {
            let isShow = false

            if (this.currentRouteName !== 'POs' || this.currentRouteName !== 'PO Details' || this.currentRouteName !== 'Products') {
                isShow = true
            }

            return isShow
        },
        isProductResultShow() {
            let isShow = false

            if (this.currentRouteName === 'Products') {
                isShow = true
            }

            return isShow
        },
        isShowEmptyResults() {
            let isShow = false

            if (!this.masterSearchLoading) {
                if ((this.currentRouteName === 'POs' || this.currentRouteName === 'PO Details') && this.poResults.length === 0) {
                    isShow = true
                } else if (this.currentRouteName === 'Products' && this.productsData.length === 0) {
                    isShow = true
                } else if ((this.currentRouteName !== 'POs' && 
                        this.currentRouteName !== 'PO Details' && 
                        this.currentRouteName !== 'Products') && 
                        this.searchResults.customers === null && 
                        this.searchResults.shipments === null) {
                    isShow = true
                }
            }

            return isShow
        }
    },
    mounted() {},
    created() {},
    updated() {
        if (this.currentRouteName === 'Products') {
            if (typeof this.getAllCategoriesDropdown !== 'undefined') {
                this.categoryListData = this.getAllCategoriesDropdown
            }
        }    
    }
};
</script>

<style>
@import '../assets/css/shipments_styles/searchGetHelp.css';
</style>
