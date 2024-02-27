<template>
    <div class="shipment-table-wrapper mobile" :class="activeShipmentTab === 0 ? 'pending' : ''">
        <!-- FOR PENDING TABS TABLE -->
        <div class="overlay" :class="search == '' && (loadingPendingData || loadingExpiredData || loadingPendingQuoteData || loadingSnoozeData) ? 'show' : ''" v-if="activeShipmentTab == 0">
            <div class="preloader" v-if="search == '' && (loadingPendingData || loadingExpiredData || loadingPendingQuoteData || loadingSnoozeData)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>
        </div>

        <LoadingSearch 
            :search="search"
            :loading="getSearchedShipmentLoading"
            :className="'overlay search pending'"
            v-if="activeShipmentTab == 0" />
        
        <PendingTabTable 
            :pending="pending"
            :expired="expired"
            :snooze="snooze"
            :pendingQuote="pendingQuote"
            :isMobile="isMobile"
            :activeTab.sync="activeShipmentTab"
            :pendingCurrentTab.sync="pendingShipmentTab"
            :search.sync="search"
            :paginationData.sync="pagination"
            @handleClick="handleClick"
            @handlePageChangeSnooze="handlePageChangeSnooze"
            @handlePageChangePending="handlePageChangePending"
            @handlePageChangePendingQuote="handlePageChangePendingQuote"
            @handlePageChangeExpired="handlePageChangeExpired"
            v-if="activeShipmentTab === 0" 
            @handlePageSearched="handlePageSearched"/> 

        <!-- FOR SHIPMENT TABS TABLE -->        
        <div class="overlay"
            :class="(search == '' && loadingShipmentsData) ? 'show' : ''" v-if="activeShipmentTab == 1">
            <div class="preloader" v-if="(search == '' && loadingShipmentsData)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>
        </div> 

        <LoadingSearch 
            :search="search"
            :loading="getSearchedShipmentLoading"
            :className="'overlay search'"
            v-if="activeShipmentTab == 1" />

        <ShipmentsTabTable 
            :shipments="shipments"
            :isMobile="isMobile"
            :activeTab.sync="activeShipmentTab"
            :search.sync="search"
            :paginationData.sync="pagination"
            @handleClick="handleClick"
            @handlePageChange="handlePageChangeShipments"
            @sort="changeSort"
            :shipmentIcon="shipmentIconSort"
            v-if="activeShipmentTab === 1"
            @handlePageSearched="handlePageSearched" />

        <!-- FOR COMPLETED TAB ONLY -->
        <div class="overlay" :class="(search == '' && loadingCompletedData) ? 'show' : ''" v-if="activeShipmentTab == 2">
            <div class="preloader" v-if="(search == '' && loadingCompletedData)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>
        </div> 

        <LoadingSearch 
            :search="search"
            :loading="getSearchedShipmentLoading"
            :className="'overlay search'"
            v-if="activeShipmentTab == 2" />

        <CompletedTabTable 
            :completed="completed"
            :isMobile="isMobile"
            :activeTab.sync="activeShipmentTab"
            :search.sync="search"
            :paginationData.sync="pagination"
            @handleClick="handleClick"
            @handlePageChange="handlePageChangeCompleted"
            @sort="changeSort"
            :completedIconSort="completedIconSort"
            v-if="activeShipmentTab === 2" 
            @handlePageSearched="handlePageSearched"/>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import globalMethods from '../../../utils/globalMethods'
import PendingTabTable from './Tabs/PendingTabTable.vue'
import ShipmentsTabTable from './Tabs/ShipmentsTabTable.vue'
import CompletedTabTable from './Tabs/CompletedTabTable.vue'
import LoadingSearch from './LoadingSearch.vue'

export default {
    props: ['shipments', 'pending', 'pendingQuote', 'snooze','expired', 'completed', 'editedItem', 'editedIndex', 'activeTab', 'isMobile', 'tablePage', 'search', 'pendingCurrentTab', 'paginationData'],
    components: {
        ShipmentsTabTable,
        PendingTabTable,
        CompletedTabTable,
        LoadingSearch
    },
    data: () => ({
        loadingShipmentsData: false,
        loadingPendingData: false,
        loadingPendingQuoteData: false,
        loadingExpiredData: false,
        loadingCompletedData: false,
        loadingSnoozeData: false,
        shipmentIconSort: 'mdi-arrow-up',
        completedIconSort: 'mdi-arrow-down'
    }),
    computed: {
        ...mapGetters([
            "getSearchedShipments",
            "getSearchedShipmentLoading"
        ]),
        formTitle() {
            return this.editedIndex === -1 ? "New Item" : "Edit Item"
        },
        activeShipmentTab: {
            get() {
                return this.activeTab
            },
            set(value) {
                this.$emit('update:activeTab', value)
            }
        },
        pendingShipmentTab: {
            get() {
                return this.pendingCurrentTab
            },
            set(value) {
                this.$emit('update:pendingCurrentTab', value)
            }
        },
        page: {
            get() {
                return this.tablePage
            },
            set(value) {
                this.$emit('update:tablePage', value)
            }
        },
        pagination: {
            get() {
                return this.paginationData
            },
            set(value) {
                this.$emit('update:paginationData', value)
            }
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "shipments")

        // set current shipment page
        if (this.$store.state.page.currentShipmentPage !== 'undefined') {
            if (this.page !== this.$store.state.page.currentShipmentPage) {
                this.page = this.$store.state.page.currentShipmentPage
            }
        }
    },
    methods: {
        ...mapActions([
            "fetchShipments", 
            "requestNewSchedule",
            "fetchPendingShipments",
            "fetchPendingQuoteShipments",
            "fetchSnoozeShipments",
            "fetchExpiredShipments",
            "fetchCompletedShipments",
            "setShipmentOrder",
            "setCompletedOrder",
            "fetchShipmentsSearched"
        ]),
        ...globalMethods,
        handleClick(value) {
            this.$router.push(`shipment/${value.id}`)
        },
        async handlePageChangePendingQuote(page) {
            let storePagination = this.$store.state.shipment.shipmentsPagination.pendingQuoteTab

            if (page !== null) {
                if (storePagination.old_page !== page) {  
                    storePagination.old_page = page                      
                    this.loadingPendingQuoteData = true
                    await this.fetchPendingQuoteShipments(page)
                    this.loadingPendingQuoteData = false
                }
            }
        },
        async handlePageChangeSnooze(page) {
            let storePagination = this.$store.state.shipment.shipmentsPagination.snoozeTab

            if (page !== null) {
                if (storePagination.old_page !== page) {  
                    storePagination.old_page = page                      
                    this.loadingPendingData = true
                    await this.fetchSnoozeShipments(page)
                    this.loadingSnoozeData = false
                }
            }
        },
        async handlePageChangePending(page) {
            let storePagination = this.$store.state.shipment.shipmentsPagination.pendingTab

            if (page !== null) {
                if (storePagination.old_page !== page) {  
                    storePagination.old_page = page                      
                    this.loadingPendingData = true
                    await this.fetchPendingShipments(page)
                    this.loadingPendingData = false
                }
            }
        },
        async handlePageChangeExpired(page) {
            let storePagination = this.$store.state.shipment.shipmentsPagination.expiredTab

            if (page !== null) {
                if (storePagination.old_page !== page) {
                    storePagination.old_page = page                        
                    this.loadingExpiredData = true
                    await this.fetchExpiredShipments(page)
                    this.loadingExpiredData = false
                }
            }
        },
        async handlePageChangeShipments(page) {
            let storePagination = this.$store.state.shipment.shipmentsPagination.shipmentTab
            let storeShipmentTabData = this.$store.state.shipment.shipmentOrder
            
            if (page !== null) {
                if (storePagination.old_page !== page) {
                    storePagination.old_page = page
                    let payloadShipments = {
                        page: page,
                        order: storeShipmentTabData.order
                    }
                        
                    this.loadingShipmentsData = true
                    await this.fetchShipments(payloadShipments)
                    this.loadingShipmentsData = false
                }
            }
        },
        async handlePageChangeCompleted(page) {
            let storePagination = this.$store.state.shipment.shipmentsPagination.completedTab
            let storeShipmentTabData = this.$store.state.shipment.completedOrder   
            
            if (page !== null) {
                if (storePagination.old_page !== page) {
                    storePagination.old_page = page
                    let payloadShipments = {
                        page: page,
                        order: storeShipmentTabData.order
                    }
                        
                    this.loadingCompletedData = true
                    await this.fetchCompletedShipments(payloadShipments)
                    this.loadingCompletedData = false
                }
            }
        },
        async changeSort(tab) {
            let storePagination = this.$store.state.shipment.shipmentsPagination
            let storeShipmentTabData = this.$store.state.shipment
            
            try {
                if (tab === 1) {
                    if (storeShipmentTabData.shipmentOrder.order === 'asc') {
                        this.setShipmentOrder({order: 'desc', field: 'eta'})
                        this.shipmentIconSort = 'mdi-arrow-down'
                        
                        let payloadShipments = {
                            page: storePagination.shipmentTab.current_page,
                            order: storeShipmentTabData.shipmentOrder.order
                        }

                        this.loadingShipmentsData = true
                        await this.fetchShipments(payloadShipments)
                        this.loadingShipmentsData = false
                    } else {
                        this.setShipmentOrder({order: 'desc', field: 'eta'})
                        this.shipmentIconSort = 'mdi-arrow-up'

                        let payloadShipments = {
                            page: storePagination.shipmentTab.current_page,
                            order: storeShipmentTabData.shipmentOrder.order
                        }

                        this.loadingShipmentsData = true
                        await this.fetchShipments(payloadShipments)
                        this.loadingShipmentsData = false
                    }
                } else if (tab === 2) {
                    if (storeShipmentTabData.completedOrder.order === 'asc') {
                        this.setCompletedOrder('desc')
                        this.completedIconSort = 'mdi-arrow-down'
                        
                        let payloadShipments = {
                            page: storePagination.completedTab.current_page,
                            order: storeShipmentTabData.completedOrder.order
                        }

                        this.loadingCompletedData = true
                        await this.fetchCompletedShipments(payloadShipments)
                        this.loadingCompletedData = false
                    } else {
                        this.setCompletedOrder('asc')
                        this.completedIconSort = 'mdi-arrow-up'

                        let payloadShipments = {
                            page: storePagination.completedTab.current_page,
                            order: storeShipmentTabData.completedOrder.order
                        }

                        this.loadingCompletedData = true
                        await this.fetchCompletedShipments(payloadShipments)
                        this.loadingCompletedData = false
                    }
                }
            } catch (e) {
                this.loadingShipmentsData = false
                this.loadingCompletedData = false
                console.log(e, 'something wrong with sorting');
            }
        },
        async handlePageSearched(data) {
            let storePagination = this.$store.state.shipment.searchedShipmentsPagination
            
            if (data !== null && this.search !== '') {
                if (storePagination.old_page !== data.page) {
                    storePagination.old_page = data.page

                    let passedData = {
                        method: "get",
                        url: "/v2/shipments/search",
                        params: {
                            q: this.search,
                            tab: data.tab,
                            page: data.page
                        }
                    }

                    // if (data.tab === 'shipments' || data.tab === 'shipments-completed-merge') {
                        
                    if (data.tab === 'shipments') {
                        passedData.params.sort = 'eta'
                        passedData.params.order = this.$store.state.shipment.shipmentOrder.order
                    } else if (data.tab === 'completed') {
                        passedData.params.sort = 'eta'
                        passedData.params.order = this.$store.state.shipment.completedOrder.order
                    }

                    passedData.params.for_testing = 1
                    try {
                        await this.fetchShipmentsSearched(passedData)
                    } catch (e) {
                        let errorMessage = (typeof e === String) ? e : JSON.stringify(e)
                        this.notificationError(`An error occured while trying to search. ${errorMessage}`)
                        console.log('Error in searching', e);
                    }
                }
            }
        },
    },
    updated() {}
}
</script>

<style></style>