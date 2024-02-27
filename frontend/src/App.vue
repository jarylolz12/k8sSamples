<template>
    <v-app class="v-app-main-wrapper" id="inspire" v-resize="onResize">
        <special-dialog
            :title="specialNotificationTitle"
            :message="specialNotificationMessage"
            :show="showSpecialDialog"
            @close="closeSpecialDialog"
        />
        <v-navigation-drawer v-model="drawer" app v-if="getUserToken !== '' && getUser !== null && !hideSidebarAndHeader">
            <DrawerMenu v-bind:isMobile="isMobile" />
        </v-navigation-drawer>
                           
        <ul v-if="isTablet && !hideSidebarAndHeader" class="tablet-header-menu"
            :class="getUserToken !== '' && getUser !== null && !hideSidebarAndHeader ? 'authenticated' : ''">
            <li v-if="!drawer">
                <v-app-bar-nav-icon
                    @click="drawer = !drawer"
                    color="white">
                </v-app-bar-nav-icon>
            </li>
            <li style="width: 36px; height: 40px;" v-if="drawer" @click="drawer = !drawer">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4412 3.55945C16.7419 3.86008 16.7419 4.34748 16.4412 4.6481L11.0887 10.0006L16.4412 15.3532C16.7419 15.6538 16.7419 16.1412 16.4412 16.4418C16.1406 16.7425 15.6532 16.7425 15.3526 16.4418L10 11.0893L4.64749 16.4418C4.34687 16.7425 3.85946 16.7425 3.55884 16.4418C3.25822 16.1412 3.25822 15.6538 3.55884 15.3532L8.91139 10.0006L3.55884 4.6481C3.25822 4.34748 3.25822 3.86008 3.55884 3.55945C3.85947 3.25883 4.34687 3.25883 4.6475 3.55945L10 8.912L15.3526 3.55945C15.6532 3.25883 16.1406 3.25883 16.4412 3.55945Z" fill="white"/>
                </svg>
            </li>
            <li>
                <div class="logo">
                    <router-link to="/shipments" class="shipment-logo">
                        <img src="@/assets/images/logo.png" alt="" />
                    </router-link>
                </div>
            </li>
        </ul>

        <v-app-bar
            app
            height="64px"
            v-bind:style="{ background: activeColor }"
            v-if="getUserToken !== '' && getUser !== null && !isMobile && !hideSidebarAndHeader">

            <v-toolbar-title>
                <div class="forFelx" :class="isMobile ? 'mobile' : 'desktop'">
                    <SearchGetHelp v-bind:isMobile="isMobile" />
                    <div class="share-feedback-btn mr-5"  @click="openFeedback = true">
                        <span class="share-feedback-text">
                            Share Feedback
                        </span>
                    </div>
                    <div class="d-flex align-center">
                      <notifications/>
                      <Notification v-bind:isMobile="isMobile" />
                    </div>
                </div>
            </v-toolbar-title>
        </v-app-bar>

		<nav class="header-menu" id="header-id" v-if="getUserToken !== '' && getUser !== null && isMobile && !hideSidebarAndHeader">
			<span class="extend-search" id="search-icon-extend" v-if="isInputExpanded">
				<img src="@/assets/images/search-icon.svg" alt="" />
                
                <button v-if="searchData !== ''" class="close-btn" @click="clearInput">
                    <img src="@/assets/images/close.svg" alt="" width="18px">
                </button>
			</span>

			<ul class="search-wrapper">
				<li v-if="!drawer">
					<v-app-bar-nav-icon
						v-if="isMobile"
						@click="drawer = !drawer"
						color="white">
					</v-app-bar-nav-icon>
				</li>
                <li style="width:36px;height: 40px;" v-if="drawer" @click="drawer = !drawer">
                    <svg width="36" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4412 3.55945C16.7419 3.86008 16.7419 4.34748 16.4412 4.6481L11.0887 10.0006L16.4412 15.3532C16.7419 15.6538 16.7419 16.1412 16.4412 16.4418C16.1406 16.7425 15.6532 16.7425 15.3526 16.4418L10 11.0893L4.64749 16.4418C4.34687 16.7425 3.85946 16.7425 3.55884 16.4418C3.25822 16.1412 3.25822 15.6538 3.55884 15.3532L8.91139 10.0006L3.55884 4.6481C3.25822 4.34748 3.25822 3.86008 3.55884 3.55945C3.85947 3.25883 4.34687 3.25883 4.6475 3.55945L10 8.912L15.3526 3.55945C15.6532 3.25883 16.1406 3.25883 16.4412 3.55945Z" fill="white"/>
                    </svg>
                </li>
				<li>
					<div class="logo">
						<router-link to="/shipments" class="shipment-logo">
                            <img src="@/assets/images/logo.png" alt="" />
                        </router-link>
					</div>
				</li>


				<li class="search-wrap">
					<img src="@/assets/images/search-icon.svg" alt="" class="mobile-search-icon" />

					<div>
						<Notification v-bind:isMobile="isMobile" />
						<input
							class="search"
							:class="isInputExpanded ? 'expanded' : ''"
							type="text"
							id="search-input"
							v-on:click="inputClick"
							v-model.trim="searchData"
							placeholder="Search anything..."
                            @input="handleSearch"
							autocomplete="off" />
							<!-- @keyup.stop="handleSearch" -->
					</div>

					<button v-if="searchData !== ''" class="close-btn" @click="clearInput">
						<img src="./assets/images/close.svg" alt="" width="18px">
					</button>
				</li>
        <li>
          <notifications/>
        </li>
			</ul>

			<div class="search-results" :class="isOpen ? 'open-dropdown' : ''" >
                <ul class="loading-search-wrapper" v-if="masterSearchLoading">
                    <div class="loading-search">
                        <v-progress-circular
                            :size="30"
                            color="#0171a1"
                            indeterminate>
                        </v-progress-circular>
                    </div>
                </ul>

                <!-- PO SEARCH GLOBAL -->
                <ul class="has-data" v-if="isPoResultShow && !masterSearchLoading && poResults.length > 0">
                    <div v-if="typeof poResults !== 'undefined' && poResults.length > 0">
                        <p class="dropdown-title"> Purchase Orders </p>
                        <div v-for="(data, index) in poResults"
                            :key="index">

                            <li class="dropdown-data" >
                                <router-link :to="`/po-details/${data.id}`">
                                    <img src="./assets/images/search-shipment-icon.svg" alt="">
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

                <!-- SHIPMENTS SEARCH GLOBAL -->
                <ul class="has-data" v-if="isShipmentResultShow && !masterSearchLoading && searchResults.shipments !== null">
                    <div v-if="isShipmentResultShow && searchResults.shipments !== 'undefined' && searchResults.shipments !== null">
                        <p class="dropdown-title"> Shipments </p>

                        <div v-for="(data, index) in searchResults.shipments" :key="index">

                            <li class="dropdown-data" v-on:click="goToShipmentDetails(data)">
                                <router-link :to="`/shipment/${data.id}`">
                                    <img src="./assets/images/search-shipment-icon.svg" alt="">

                                    <!-- if status is pending approval, cancel or pending -->
                                    <div v-if="data.Status == 'Pending Approval' || data.Status == 'Cancelled' || data.Status == 'Pending'">
                                        <p class="data-ref">{{ data.ReferenceID }}</p>
                                        <p class="data-location">
                                            {{ data.Departure.location !== 'undefined'
                                                || data.Departure.location !== null
                                                || typeof typeof d.Departure.location == 'undefined'
                                                    ? data.Departure.location + ' - '
                                                    : 'Not Specified'
                                            }}

                                            {{ data.Arrival.location !== 'undefined' && data.location !== null
                                                ? data.Arrival.location
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
                                                || typeof typeof d.Departure.location == 'undefined'
                                                    ? data.Departure.location
                                                    : 'Not Specified'
                                            }}

                                            {{ data.Departure.etd !== 'undefined' && data.Departure.etd !== null && data.Departure.etd !== 'Not Specified'
                                                ?  `(${date(data.Departure.etd)})` + ' - '
                                                : '(Not Specified) - '
                                            }}

                                            {{ data.Arrival.location !== 'undefined' && data.location !== null
                                                ? data.Arrival.location
                                                : 'Not Specified'
                                            }}

                                            {{ data.Arrival.eta !== 'undefined' && data.Arrival.eta !== null && data.Arrival.eta !== 'Not Specified'
                                                ?  `(${date(data.Arrival.eta)})`
                                                : '(Not Specified)'
                                            }}
                                        </p>
                                    </div>
                                </router-link>
                            </li>
                        </div>
                    </div>
                </ul>

                <!-- PRODUCTS SEARCH GLOBAL -->
                <ul class="has-data" v-if="isProductResultShow && !masterSearchLoading && productsData.length > 0">
                    <div v-if="typeof productsData !== 'undefined' && productsData.length > 0">
                        <p class="dropdown-title"> Products </p>

                        <div v-for="(data, index) in productsData" :key="index">
                            <li class="dropdown-data" v-on:click="openProductViewDialog(data)">
                                <router-link :to="`#`">
                                    <img src="./assets/images/empty-product-icon.svg" alt="" width="35px" height="35px" class="product-img">
                                     <div>
                                        <p class="data-ref">{{ "SKU #" + data.category_sku }}</p>
                                        <p class="data-location"> {{ data.name }}</p>
                                    </div>
                                </router-link>
                            </li>
                        </div>
                    </div> 
                </ul>

                <!-- SEARCH GLOBAL NO DATA -->
                <ul class="no-match-data products" v-if="isShowEmptyResults"> 

                    <div>
                        <img src="./assets/images/no-data-found.svg" alt="" srcset="">
                        <h4>No Match Found!</h4>

                        <p>We couldn’t find any result that matches your search. 
                            <br/>Can you please check the spelling or try different search?
                        </p>
                    </div>
                </ul>
            </div>
		</nav>

        <v-main :class="getUserToken !== '' && getUser !== null && !hideSidebarAndHeader ? 'authenticated' : ''">
            <router-view></router-view>
        </v-main>

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

        <FeedbackDialog
            :openFeedback="openFeedback"
            @closeDialog="openFeedback = false"
        />
    </v-app>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import DrawerMenu from "./components/DrawerMenu.vue";
import SearchGetHelp from "./components/SearchGetHelp.vue";
import Notification from "./components/Notification.vue";
import axios from "axios"
import _ from 'lodash'
import moment from 'moment'
import ProductViewDialog from './components/ProductComponents/Dialog/ProductViewDialog.vue'
import ProductAddDialog from './components/ProductComponents/Dialog/ProductAddDialog.vue'
import DeleteDialog from './components/Dialog/DeleteDialog.vue'
import globalMethods from './utils/globalMethods'
import { getMessaging, getToken } from 'firebase/messaging'
import firebaseApp from './utils/firebase'
import SpecialDialog from './components/Dialog/SpecialDialog'
import Notifications from "@/components/Notifications/Notifications";
import {connectToPONotificationChannel, leavePONotificationChannel} from "@/configs/poEcho";
import FeedbackDialog from "@/components/FeedbackDialog.vue";
import store from "@/store";

var cancel;
var CancelToken = axios.CancelToken;
var messaging = null

export default {
	name: "App",
	components: {
    Notifications,
		DrawerMenu,
		SearchGetHelp,
		Notification,
        ProductViewDialog,
        ProductAddDialog,
        SpecialDialog,
        DeleteDialog,
        FeedbackDialog,
	},
	created() {
		const currentPath = store.getters['page/getCurrentPath']
        if(_.get(currentPath, 'currentPathName') == 'BookingRequestForm'){
        axios.post(`generate-invite-token`, {
            tokenKey: _.get(currentPath, 'token')
        })
            .then(response => {
                localStorage.setItem('setBookingInviteToken', response.data.data)
                console.log('App.vue')
                this.fetchUser();
            })
            .catch(error => {
                console.log('error', error)
            });
        axios.interceptors.request.use(function (config) {
            const token =  "Bearer " + localStorage.getItem('setBookingInviteToken');
            config.headers.Authorization = token;
            return config;
        });
        } else {
        localStorage.removeItem('setBookingInviteToken')
        axios.interceptors.request.use(function (config) {
            const token = "Bearer " + store.getters.getUserToken
            config.headers.Authorization = token;
            return config;
        });
        this.fetchUser();
        }
	},
	data: () => ({
		//
		drawer: null,
		isMobile: false,
        hasSetNotificationToken: false,
        poResults: [],
        windowTabShow: true,
		activeColor: "white",
		searchResults: {
            customers: null,
            shipments: null
        },
        specialNotificationTitle: "Special Notification",
        specialNotificationMessage: "Refreshing the page in 5 seconds in order to update changes in your account.",
		noResults: false,
		searching: false,
		searchData: '',
		isOpen: false,
		isInputExpanded: false,
        isTablet: false,
        currentWidth: window.innerWidth,
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
        openFeedback: false,
    hideSidebarAndHeaderPages:['BookingRequestForm', 'BookingRequestSubmitted']
	}),
	watch: {
		isInputExpanded(dropOpen) {
            if (dropOpen) {
                document.addEventListener('click', this.closeIfClickedOutsideMobile)
            }
        },
    getUser(newUserValue){
      if (newUserValue){
        const userId = JSON.parse(newUserValue).id
        connectToPONotificationChannel(userId);
      }
      else leavePONotificationChannel();
    },
    },
	methods: {
        ...mapActions({
            logout: 'logout',
            fetchUser: 'fetchUser',
            fetchMasterSearch: 'fetchMasterSearch',
            fetchShipmentDetails: 'fetchShipmentDetails',
            setMasterSearchLoading: 'setMasterSearchLoading',
            fetchProducts: 'products/fetchProducts',
            deleteProduct: 'products/deleteProduct',
            updateNotificationToken: 'updateNotificationToken'
        }),
        ...globalMethods,
        ucWords(str) {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                return $1.toUpperCase()
            })
        },
        _processMessaging() {
            if ( !this.hasSetNotificationToken ) {
                this.hasSetNotificationToken = true
                this._getMessaging()
            }
        },
        _handleVisibilityChange() {
            
            if ( document.visibilityState === 'hidden' ) {
                this.windowTabShow = false
            } else {
                if ( !this.windowTabShow ) {
                    this.windowTabShow = true
                    this.$store.dispatch('checkCurrentAccount')
                }
            }
        },
        closeSpecialDialog() {
            this.$store.dispatch('closeSpecialDialog')
        },
        _getMessaging() {

            //configure messaging
            messaging = getMessaging(firebaseApp)
            
            //event handler for receiving push notification
            messaging.onMessageHandler = () => {

                this.$store.dispatch('openSpecialDialog')
                
                setTimeout(() => {
                    let currentUrl = window.location.pathname
                    window.location.href = currentUrl
                },5000)
            }
            
            //retrieve token
            getToken(messaging, { vapidKey: 'BCgjgo7bH1LxtAyIdHgBxcQO7kSTUkHfiNWPF8tRSwBSckmUPSJg2te9Xqg91gOnEzxAI7jUKosruJHTiWeMlHo' }).then(currentToken => {
                //register token to the server
                if (currentToken)
                    this.updateNotificationToken(currentToken)

            }).catch((err) => {
              console.log('An error occurred while retrieving token. ', err)
            })
        },
		onResize() {
			if (window.innerWidth < 1024) {
				this.isMobile = true;
				this.activeColor = "#0171A1";
			} else {
				this.isMobile = false;
				this.activeColor = "white";
			}

            if (window.innerWidth > 1023 && window.innerWidth < 1201) {
                this.isTablet = true
            } else {
                this.isTablet = false
            }
		},
		inputClick() {
			this.isInputExpanded = true
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
				this.isInputExpanded = true
                document.getElementById("search-input").focus()
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
        pgtDebounce(func, delay) {
            let debounceTimer;

            return function() {
                console.log("debouncing call..");
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
                console.log("..done");
            };
        },
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
                if ( this.currentRouteName === 'POs' || this.currentRouteName === 'PO Details') {
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
        closeIfClickedOutsideMobile(event) {
            if (document.getElementById('search-input').contains(event.target) !== null) {
                if (!document.getElementById('search-input').contains(event.target)) {
                    // document.getElementById("search-input").focus()
                    document.removeEventListener('click', this.closeIfClickedOutsideMobile)

                    this.isOpen = false
                    this.searchData = ''
					this.isInputExpanded = false
                }
            }
        },
        async goToShipmentDetails(data) {
            try {
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
					// this.notificationError(e)
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
            getUserToken: 'getUserToken',
            getUser: 'getUser',
            getAllMasterSearch: 'getAllMasterSearch',
            masterSearchLoading: 'masterSearchLoading',
            getDeleteProductLoading: 'products/getDeleteProductLoading',
            getAllCategoriesDropdown: 'category/getAllCategoriesDropdown',
            getLoadingUserDetails: 'getLoadingUserDetails'
        }),
    hideSidebarAndHeader(){
      if(this.currentRouteName){
        return this.hideSidebarAndHeaderPages.includes(this.currentRouteName)
      } else {
        return true;
      }
    },
        showSpecialDialog() {
            return this.$store.getters.getSpecialDialog
        },
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
    mounted() {
        this._processMessaging()
        document.addEventListener('visibilitychange', this._handleVisibilityChange, false);
        document.title = "Shifl Apptest";
    },
	updated() {

        if (window.innerWidth !== this.currentWidth) {
            this.currentWidth = window.innerWidth
        }

        if (this.currentRouteName === 'Products') {
            if (typeof this.getAllCategoriesDropdown !== 'undefined') {
                this.categoryListData = this.getAllCategoriesDropdown
            }
        } 
    }
};

</script>

<style lang="scss">
@import './assets/scss/app.scss';
</style>
<style scoped>
    
    @media (min-width: 10px) {
        .v-navigation-drawer {
            width: 100% !important;
        }
    }

    @media (min-width: 415px) {
        .v-navigation-drawer {
            width: 216px !important;
        }
    }
</style>
