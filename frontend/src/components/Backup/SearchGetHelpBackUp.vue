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
                <!-- @keyup.stop="handleSearch" -->

            <br />

            <button v-if="searchData !== ''" class="close-btn" @click="clearInput">
                <img src="../assets/images/close.svg" alt="" width="18px">
            </button>

            <div class="search-results"
                :class="isOpen ? 'open-dropdown' : 'close-dropdown'" >
                <ul class="loading-search-wrapper" v-if="masterSearchLoading">
                    <div class="loading-search">
                        <v-progress-circular
                            :size="30"
                            color="#0171a1"
                            indeterminate>
                        </v-progress-circular>
                    </div>
                </ul>

                <!-- FOR PO's -->
                <ul class="has-data" 
                v-if="(currentRouteName=='POs' || currentRouteName=='PO Details') && !masterSearchLoading && poResults.length > 0">
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

                <ul class="no-match-data po"
                v-if="(currentRouteName=='POs' || currentRouteName=='PO Details') && !masterSearchLoading && poResults.length == 0"> 
                    <div>
                        <img src="../assets/images/no-data-found.svg" alt="" srcset="">
                        <h4>No Match Found!</h4>
                        <p>We couldn’t find any result that matches your search. 
                        <br/>Can you please check the spelling or try different search?
                        </p>
                    </div>
                </ul>
                <!-- END PO's -->

                <!-- FOR Products -->
                <ul class="has-data" v-if="(currentRouteName=='Products') && 
                    !masterSearchLoading && productsSearchResults.length > 0">

                    <div v-if="typeof productsSearchResults !== 'undefined' && productsSearchResults.length > 0">
                        <p class="dropdown-title"> Products </p>

                        <div v-for="(data, index) in productsSearchResults" :key="index">
                            <li class="dropdown-data">
                                <router-link :to="`#`">
                                    <img src="../assets/images/empty-product-icon.svg" alt="" width="30px" height="30px">
                                     <div>
                                        <p class="data-ref">{{ "SKU #" + data.category_sku }}</p>
                                        <p class="data-location"> {{ data.name }}</p>
                                    </div>
                                </router-link>
                            </li>
                        </div>
                    </div> 
                </ul>

                <ul class="no-match-data products" 
                    v-if="(currentRouteName=='Products') && !masterSearchLoading && productsSearchResults.length == 0"> 

                    <div>
                        <img src="../assets/images/no-data-found.svg" alt="" srcset="">
                        <h4>No Match Found!</h4>

                        <p>We couldn’t find any result that matches your search. 
                            <br/>Can you please check the spelling or try different search?
                        </p>
                    </div>
                </ul>
                <!-- END PRODUCTS -->

                <!-- FOR Shipments -->
                <ul class="has-data" 
                v-if="(currentRouteName!=='POs' && currentRouteName!=='PO Details' && currentRouteName!=='Products') && !masterSearchLoading && searchResults.shipments !== null">
                    <!-- <div v-if="searchResults.customers !== 'undefined' && searchResults.customers !== null">
                        <p class="dropdown-title"> Customers </p>

                        <div v-for="(data, index) in searchResults.customers" 
                            :key="index">

                            <li class="dropdown-data customer">
                                {{ data.company_name }}
                            </li>
                        </div>                        
                    </div> -->

                    <div v-if="(currentRouteName!=='POs' && currentRouteName!=='PO Details' && currentRouteName!=='Products') && searchResults.shipments !== 'undefined' && searchResults.shipments !== null">
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

                <ul class="no-match-data shipments" 
                    v-if="(currentRouteName!=='POs' && currentRouteName!=='PO Details' && currentRouteName!=='Products') && (!masterSearchLoading && searchResults.customers == null && searchResults.shipments == null)"> 
                    <div>
                        <img src="../assets/images/no-data-found.svg" alt="" srcset="">
                        <h4>No Match Found!</h4>
                        <p>We couldn’t find any result that matches your search. 
                        <br/>Can you please check the spelling or try different search?
                        </p>
                    </div>
                </ul>
                <!-- END SHIPMENTS -->
            </div>        
        </div>

        <div class="mobile-search-wrapper" v-if="isMobile">
            <img src="@/assets/images/search-icon.svg" alt="" class="search-icon" />
        </div>
    </div>
</template>

<script>
import axios from "axios"
import _ from 'lodash'
import moment from 'moment'
import { mapActions, mapGetters } from "vuex";

var cancel;
var CancelToken = axios.CancelToken;

export default {
    props: ["isMobile"],
    data() {
        return {
            loading: false,
            items: [],
            poResults: [],
            search: null,
            select: null,
            states: [
                "Alabama",
                "Alaska",
                "Marshall Islands",
                "Maryland",
                "Massachusetts",
                "Michigan",
                "Minnesota",
                "Mississippi",
                "Missouri",
                "Montana",
                "Nebraska",
                "Nevada",
                "New Hampshire",
                "New Jersey",
                "New Mexico",
                "New York",
                "North Carolina",
                "North Dakota",
                "Northern Mariana Islands",
            ],
            searchResults: {
                customers: null,
                shipments: null
            },
            noResults: false,
            searching: false,
            searchData: '',
            isOpen: false,
            productsSearchResults: []
        };
    },
    watch: {
        search(val) {
            val && val !== this.select && this.querySelections(val);
        },
        isOpen(dropOpen) {
            if (dropOpen) {
                document.addEventListener('click', this.closeIfClickedOutside);
            }
        }
    },
    methods: {
        ...mapActions(["fetchMasterSearch", "fetchShipmentDetails", "setMasterSearchLoading"]),
        ucWords(str) {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                return $1.toUpperCase()
            })
        },
        querySelections(v) {
            this.loading = true;
            // Simulated ajax query
            setTimeout(() => {
            this.items = this.states.filter((e) => {
                return (e || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1;
            });
            this.loading = false
            }, 500);
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
        pgtDebounce(func, delay) {
            let debounceTimer;

            return function() {
                console.log("debouncing call..")
                const context = this
                const args = arguments
                clearTimeout(debounceTimer)
                debounceTimer = setTimeout(() => func.apply(context, args), delay)
                console.log("..done")
            }
        },  
        // handleSearch() {
        //     this.pgtDebounce(this.preApiCall(), 300)
        // },
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
            // this.searching = this.masterSearchLoading
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

                    } else if (this.currentRouteName==='Products') {
                        console.log('here');
                        this.setMasterSearchLoading(true)
                        this.productsSearchResults = await this.$store.dispatch('products/fetchProductsSearchGlobal', passedData)
                        this.setMasterSearchLoading(false)   

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
            /*
            if (searchData !== "undefined" && searchData !== "") {
                let passedData = {
                    method: "post",
                    url: "/search",
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        search_text: searchData
                    }
                }

                try {
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
                } catch (e) {
                    throw Error(e)
                }
            } else {
                this.clear()
            }*/
        },
        closeIfClickedOutside(event) {
            if (event !== 'undefined' && event !== null) {
                if (document.getElementById('dropdown').contains(event.target) !== null) {
                    if (!document.getElementById('dropdown').contains(event.target)) {
                        document.getElementById("input-search-data").focus()

                        this.isOpen = false
                        this.searchData = ''

                        document.removeEventListener('click', this.closeIfClickedOutside)
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
        date: function (date) {
            return moment(date).format('MM/DD/YYYY');
        }
    },
    computed: {
        ...mapGetters(["getAllMasterSearch", "masterSearchLoading"]),
        currentRouteName() {
            return this.$route.name
        },
        poGlobalSearchLoading() {
            return this.$store.getters['po/getPoGlobalSearchLoading']
        }

    },
    mounted() {},
    created() {},
    updated() {
        console.log(this.productsSearchResults);
    }
};
</script>

<style>
@import '../assets/css/shipments_styles/searchGetHelp.css';
</style>
