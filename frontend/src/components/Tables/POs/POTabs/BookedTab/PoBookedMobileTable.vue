<template>
    <div class="po-table-wrapper">
        <v-data-table
            :headers="headers"
            :items="posBooked"
            :page.sync="page"
            :items-per-page="getItemPerPage()"
            @page-count="pageCount = $event"
            mobile-breakpoint="1023"
            item-key="po_number"
            class="pos-mobile-table elevation-1"
            v-bind:class="{
                'no-data-table' : (typeof posBooked !== 'undefined' && posBooked.length === 0 && this.search === ''),
                'no-current-pagination' : (posBooked.length !== 0 && getTotalPage <= 1),
                'no-searched-data' : (getSearchedDataClass)
            }"
            hide-default-footer
            fixed-header
            @click:row="viewPo"
            show-select>

            <template v-slot:[`item.po_number`]="{ item }">
                <div class="po-mobile-item dFlex">
                    <div class="po-num">
                        PO# <span>{{ item.po_number }}</span>
                    </div>

                    <div class="po-amount">
                        <span>${{ getTotalPrice(item) }}</span>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.created_at`]="{ item }">
                <div class="po-mobile-item">
                    <div class="po-supplier">
                        <span>{{ item.supplier_id !== null ? getSupplierName(item.supplier_id) : item.customer_name }}</span>
                    </div>

                    <div class="po-address">
                        <!-- <span v-html="getWarehouseAddress(item.warehouse_id)"></span> -->
                        <div>{{  item.ship_to !== '' && item.ship_to !== null ? item.ship_to : '--' }}</div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.supplier_id`]="{ item }">
                <div class="po-mobile-item dFlex dates">
                    <div class="po-date">
                        DATE: <span class="mr-3">{{ formatDate(item.created_at) }}</span>
                    </div>

                    <div class="po-date">
                        ETA: <span>{{ formatDate(item.eta) }}</span>
                    </div>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="getPoShippedLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>

                <div class="no-data-wrapper" v-if="!getPoShippedLoading && posBooked.length == 0">
                    <div class="no-data-heading">
                        <img src="@/assets/icons/empty-po-icon.svg" width="40px" height="42px" alt="">

                        <h3> {{(getPoLocalQuery=='' || getPoLocalQuery==null) ? 'No Booked Purchase Orders' : 'No matching result'}}</h3>
                        <p v-if="(getPoLocalQuery!=='' && getPoLocalQuery!==null)">
                            {{ "Sorry! We could not find any purchase order that matches your search term." }} <br>
                        </p>
                        <p v-if="(getPoLocalQuery=='' || getPoLocalQuery==null)">
                            {{ "There are no purchase order to show in booked." }} <br>
                        </p>
                        <div v-if="(getPoLocalQuery!==null && getPoLocalQuery!=='')" class="mt-4">
                            <a dark color="primary" class="btn-white different-btn" >
                                Try Different Search
                            </a>
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`no-results`]>
                <div class="no-results">
                    <div class="no-results-heading">
                        <img src="@/assets/icons/empty-po-icon.svg" width="40px" height="42px" alt="">

                        <h3> No matching result </h3>
                        <p>
                            Sorry! We could not find any purchase order that matches your search term.
                        </p>
                    </div>
                </div>
            </template>
        </v-data-table>

        <Pagination v-if="typeof posBooked !== 'undefined' && posBooked.length > 0 && getTotalPage > 1"
            :pageData.sync="getCurrentPage"
            :lengthData.sync="getTotalPage"
            :isMobile="isMobile"
            @next-page="nextPage"
            @previous-page="prevPage"
            @goto-page="goToPage"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import Pagination from '@/components/Pagination.vue'
import _ from 'lodash'
import moment from 'moment'
import globalMethods from '@/utils/globalMethods'
//import jQuery from 'jquery'

export default {
    name: 'PoBookedMobileTable',
    props: ['items', 'currentWarehouseSelected', 'isMobile', 'search'],
    components: {
        Pagination
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        headers: [
			{
				text: 'PO NO',
				align: 'start',
				sortable: false,
				value: 'po_number',
				fixed: true,
				width: "8%"
			},
			{
				text: 'Date',
				align: 'start',
				sortable: false,
				value: 'created_at',
				fixed: true,
				width: "12%"
			},
			{
				text: 'Vendor',
				align: 'start',
				sortable: false,
				value: 'supplier_id',
				fixed: true,
				width: "25%"
			},
			{
				text: 'Ship to',
				align: 'start',
				sortable: false,
				value: 'ship_to',
				fixed: true,
				width: "25%"
			},
			{
				text: 'ETA',
				align: 'start',
				sortable: false,
				value: 'eta',
				fixed: true,
				width: "12%"
			},
            {
				text: 'Details',
				align: 'end',
				sortable: false,
				value: 'details',
				fixed: true,
				width: "10%"
			},
            {
				text: 'Status',
				align: 'center',
				sortable: false,
				value: 'status',
				fixed: true,
				width: "12%"
			},
			{
				text: '',
				align: 'end',
				value: 'actions',
				sortable: false,
				fixed: true,
				width: "12%"
			},
		],
        dialogDelete: false,
        dialogDeleteWarehouse: false,
    }),
    computed: {
        ...mapGetters({
            getShippedPage: 'po/getShippedPage',
            getCategories: 'category/getCategories',
            getVendorLists: 'po/getVendorLists',
            getWarehouse: 'warehouse/getWarehouse',
            getPoShippedLoading: 'po/getPoShippedLoading',
            getShippedPOPagination: 'po/getShippedPOPagination',
            getPoLocalQuery: 'po/getPoLocalQuery'
        }),
        posBooked: {
            get() {
                return this.items
            },
            set(value) {
                this.$emit('update:items', value)
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
        getTotalPage: {
            get() {
                return Math.ceil(this.getShippedPOPagination.total / this.getShippedPOPagination.per_page)
            }
        },
        getCurrentPage: {
            get() {
                return this.getShippedPage
            },
            set() {
                return {}
            }
        },
        getSearchedDataClass() {
            if (this.posBooked.length == 0 && this.search !== '') {
                return true
            } else {
                return false
            }
        },
    },
    methods: {
        ...mapActions({
            downloadPo: 'po/downloadPo',
            fetchPoShipped: 'po/fetchPoShipped',
            fetchPoShippedNew: 'po/fetchPoShippedNew',
            setShippedPage: 'po/setShippedPage'
        }),
        ...globalMethods,
        formatDate(date) {
            if (date !== null) {
                return moment(date).format('MMM DD, YYYY')
            } else {
                return 'N/A'
            }
        },
        createPo() {
            this.$emit('createPo')
        },
        editPo(po) {
            this.$emit('editPo', po)
        },
        viewPo(item) {
            this.$emit('viewPo', item)
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
        getSupplierName(id) {
            if ( Array.isArray(this.getVendorLists) && this.getVendorLists.length > 0) {
                let findVendor = _.find(this.getVendorLists, (e => e.id ===id))
                if ( typeof findVendor!=='undefined' ) {
                    return findVendor.company_name
                }
            }
            return '--'
        },
        getWarehouseAddress(id) {
            if (typeof this.getWarehouse !== 'undefined' && this.getWarehouse !== null &&
                typeof this.getWarehouse.results !== 'undefined' && this.getWarehouse.results !== null &&
                this.getWarehouse.results.length !== 'undefined' && this.getWarehouse.results.length !== null) {

                let getData = this.getWarehouse.results
                let findSizeValue = id !== 'undefined' ? _.find(getData, (e) => (e.id == id)) : ''

                if (typeof findSizeValue !== 'undefined') {
                    if (findSizeValue.address !== 'undefined') {
                        return `<span>${findSizeValue.address}</span>`
                        // return `<span>${findSizeValue.name},</span><br/><span>${findSizeValue.address}</span>`;
                    }
                } else {
                    return '--'
                }
            }
        },
        async download(item) {
            try {
                this.notificationCustom('Preparing to download PO...')
                await this.downloadPo(item)
                this.notificationCustom('Files has been downloaded.')
            } catch(e) {
                this.notificationError(e)
                console.log(e)
            }
        },
        email(po) {
            this.$emit('email', po)
        },
        deletePo(item) {
            this.$emit('deletePo', item)
        },
        getItemPerPage() {
            return this.getShippedPOPagination.per_page
        },
        nextPage() {

            if (this.getShippedPOPagination.next_page_url!==null) {
                var queryParams = this.getShippedPOPagination.next_page_url.split("?")[1].replace(/[^0-9]/g,'')

                this.setShippedPage(queryParams)
                try {
                    let passedData = {
                        page: queryParams,
                        special: 1,
                    }
                    if (this.getPoLocalQuery!==null && this.getPoLocalQuery!=='') {
                        passedData['query'] = this.getPoLocalQuery
                    }
                    this.fetchPoShippedNew(passedData)
                    //this.fetchPoPending(queryParams)
                } catch (e) {
                    this.notificationError(e)
                }
            }
            /*
            var queryParams = this.getShippedPOPagination.next_page_url.split("?")[1].replace(/[^0-9]/g,'')

            try {
                this.fetchPoShipped(queryParams)
            } catch (e) {
                this.notificationError(e)
            }*/
        },
        prevPage() {
            /*
            var queryParams = this.getShippedPOPagination.prev_page_url.split("?")[1].replace(/[^0-9]/g,'')
            try {
                this.fetchPoShipped(queryParams)
            } catch (e) {
                this.notificationError(e)
            }*/

            if (this.getShippedPOPagination.prev_page_url!==null) {
                var queryParams = this.getShippedPOPagination.prev_page_url.split("?")[1].replace(/[^0-9]/g,'')
                this.setShippedPage(queryParams)
                try {
                    let passedData = {
                        page: queryParams,
                        special: 1,
                    }
                    if (this.getPoLocalQuery!==null && this.getPoLocalQuery!=='') {
                        passedData['query'] = this.getPoLocalQuery
                    }
                    this.fetchPoShippedNew(passedData)
                    //this.fetchPoPending(queryParams)
                } catch (e) {
                    this.notificationError(e)
                }
            }
        },
        goToPage(pageNumber) {
            try {
                this.setShippedPage(pageNumber)
                let passedData = {
                    page: pageNumber,
                    special: 1,
                }
                if (this.getPoLocalQuery!==null && this.getPoLocalQuery!=='') {
                    passedData['query'] = this.getPoLocalQuery
                }
                this.fetchPoShippedNew(passedData)
                //this.fetchPoPending(queryParams)
            } catch (e) {
                this.notificationError(e)
            }
            /*
            try {
                this.fetchPo(pageNumber)
            } catch (e) {
                this.notificationError(e)
            }*/
        },
        getTotalPrice(item) {
            if (typeof item.total !== 'undefined' && item.total !== null) {
                let total = (item.total).toFixed(2)

                return this.addCommaToNum(total)
            } else {
                return 0
            }
        }
    },
    mounted() {},
    updated() {}
}
</script>

<style scoped>
    .different-btn {
        cursor: default !important;
    }
</style>