<template>
    <div class="supplier-and-customer-detail-wrapper" v-resize="onResize">
        <div class="supplier-and-customer-detail-content">
            <div class="details-breadcrumbs">
                <router-link to="/contact" class="page-name"> Contact </router-link>

                <span class="right-chevron">
                    <img src="@/assets/images/right_chevron.svg" alt="" srcset="" width="7px">
                </span>

                <router-link :to="`/contact?${isDetailForVendor ? 'vendor' : 'customer'}`" class="page-name">
                    {{ isDetailForVendor ? 'Vendor' : 'Customer' }}
                </router-link>

                <span class="right-chevron">
                    <img src="@/assets/images/right_chevron.svg" alt="" srcset="" width="7px">
                </span>

                <p class="details"> Details </p>
            </div>

            <div class="detail-content-header-wrapper">
                <div class="detail-content-info">
                    <div class="info-wrapper">
                        <div class="contact-information">
                            <h2> Springworks International AB </h2>

                            <span class="status pending"> Invitation Pending </span>

                            <!-- EXAMPLE FOR CONNECTED STATUS (UNCOMMENT TO SEE UI) -->
                            <!-- <span class="status connected"> Connected </span> -->
                        </div>

                        <div class="contact-address-info">
                            <div class="address">
                                <img src="@/assets/icons/map-pin.svg" width="16px" height="16px" class="mr-1">
                                <span>1901 Thornridge Cir. Shiloh, Hawaii 81063</span>
                            </div>

                            <div class="phone">
                                <img src="@/assets/icons/phone.svg" width="16px" height="16px" class="mr-1">
                                <span>PHONE: </span> <small>+1-202-55-0135</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-table-wrapper">
                <div class="detail-table-contents">
                    <v-tabs class="details-tabs" @change="onTabChange" v-model="currentTab">
                        <v-tab v-for="(n, i) in tabs" :key="i">
                            <span class="tab-name">{{ n }}</span>

                            <v-badge
                                color="#819FB2"
                                class="customBadge"
                                bordered
                                v-if="i !== 2"
                                :content="getItemPerTabCounts(i)">
                            </v-badge>
                        </v-tab>
                    </v-tabs>

                    <div class="vendor-purchase-order-table" v-if="currentTab == 0">
                        <v-data-table
                            :headers="vendorPoHeader"
                            mobile-breakpoint="769"
                            :items="purchaseOrderSampleData"
                            class="po-vendor-table elevation-1"
                            :class="purchaseOrderSampleData !== null && purchaseOrderSampleData.length !== 'undefined' && purchaseOrderSampleData.length !== 0 ? '' : 'no-data-table'"
                            fixed-header
                            hide-default-footer
                            show-select
                            :page.sync="page"
                            :items-per-page="itemsPerPage"
                            @page-count="pageCount = $event">

                            <template v-slot:top>
                                <v-toolbar flat>                                
                                    <v-spacer></v-spacer>

                                    <Search 
                                        placeholder="Search PO's"
                                        className="search custom-search"
                                        :inputData.sync="search" />
                                </v-toolbar>
                            </template>

                            <template v-slot:[`item.po_num`]="{ item }">
                                <p class="mb-0" style="color: #0171A1;">{{ item.po_num !== '' ? item.po_num : '--' }}</p>
                            </template> 

                            <template v-slot:[`item.details`]="{ item }">
                                <div class="item-details">
                                    <p class="mb-0">${{ item.unit_price }}</p>
                                    <span>{{ item.total_units }} Units</span>
                                </div>
                            </template> 

                            <template v-slot:[`item.status`]="{ item }">
                                <div class="item-status" v-if="isDetailForVendor">
                                    <span class="status" :class="item.status">
                                        {{ item.status === 'change' ? 'Change Request Sent' : item.status }}
                                    </span>
                                </div>

                                <div class="item-status" v-if="!isDetailForVendor">
                                    <v-menu bottom left content-class="outbound-lists-menu" v-if="item.status == 'pending'">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn class="status" :class="item.status" icon v-bind="attrs" v-on="on">
                                                <span>Pending</span>
                                                <v-icon>mdi-chevron-down</v-icon>
                                            </v-btn>
                                        </template>

                                        <v-list class="outbound-lists">
                                            <v-list-item>
                                                <v-list-item-title style="color: #0171A1;">
                                                    Accept
                                                </v-list-item-title>
                                            </v-list-item>

                                            <v-list-item>
                                                <v-list-item-title>
                                                    Reject
                                                </v-list-item-title>
                                            </v-list-item>

                                            <v-list-item>
                                                <v-list-item-title>
                                                    Request Changes
                                                </v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>

                                    <span class="status customer" :class="item.status" v-if="item.status !== 'pending'">
                                        {{ item.status === 'change' ? 'Change Request Sent' : item.status }}
                                    </span>
                                </div>
                            </template> 

                            <!-- Uncomment if already connected to the API, change getSuppliersLoading -->
                            <!-- <template v-slot:no-data>
                                <div class="no-data-preloader mt-4" v-if="getSuppliersLoading">
                                    <v-progress-circular
                                        :size="40"
                                        color="#0171a1"
                                        indeterminate
                                        v-if="getSuppliersLoading">
                                    </v-progress-circular>
                                </div>

                                <div class="no-data-wrapper"  v-if="!getSuppliersLoading && purchaseOrderSampleData !== null && 
                                        purchaseOrderSampleData.length !== 'undefined' 
                                        && purchaseOrderSampleData.length == 0">

                                    <div class="no-data-heading">
                                        <img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="">
                                        <h3> No Purchase Order yet. </h3>
                                    </div>
                                </div>
                            </template> -->
                        </v-data-table>

                        <Pagination 
                            v-if="purchaseOrderSampleData.length !== 0"
                            :pageData.sync="page"
                            :lengthData.sync="pageCount"
                            :isMobile="isMobile" />
                    </div>

                    <div class="vendor-connection-table" v-if="currentTab == 1">
                        <v-data-table
                            :headers="vendorConnectionHeader"
                            mobile-breakpoint="769"
                            :items="connectionsSampleData"
                            class="po-vendor-table elevation-1"
                            :class="connectionsSampleData !== null && connectionsSampleData.length !== 'undefined' && connectionsSampleData.length !== 0 ? '' : 'no-data-table'"
                            fixed-header
                            hide-default-footer
                            :page.sync="page"
                            :items-per-page="itemsPerPage"
                            @page-count="pageCount = $event">

                            <template v-slot:top>
                                <v-toolbar flat>                                
                                    <v-spacer></v-spacer>

                                    <Search 
                                        placeholder="Search connection"
                                        className="search custom-search"
                                        :inputData.sync="search" />
                                </v-toolbar>
                            </template>

                            <template v-slot:[`item.name`]="{ item }">
                                <p class="mb-0" style="color: #0171A1;">{{ item.name !== '' ? item.name : '--' }}</p>
                            </template> 

                            <template v-slot:[`item.emails`]="{ item }">
                                <div class="email-wrapper" v-if="item.emails !== ''">
                                    <div class="email-container" v-for="(email, index) in item.emails" :key="index">
                                        <p class="mb-0">{{ email }}</p>
                                    </div>
                                </div>

                                <div v-if="item.emails == ''">
                                    <p class="mb-0">--</p>
                                </div>
                            </template>

                            <template v-slot:[`item.actions`]="{ item }">
                                <div class="item-button">
                                    <button class="btn-white invite" v-if="item.status == ''">
                                        <img src="@/assets/icons/invite-arrow.svg" height="14px" width="14px">
                                        <p>Invite to Shifl</p>
                                    </button>

                                    <button class="btn-white connected" v-if="item.status == 'connected'">
                                        <img src="@/assets/icons/checkMark.png" height="14px" width="14px">
                                        <p>Connected</p>
                                    </button>

                                    <button class="btn-white pending" v-if="item.status == 'pending'">
                                        <img src="@/assets/icons/clock.svg" height="14px" width="14px">
                                        <p>Invitation Pending</p>
                                    </button>
                                </div>
                            </template>

                            <!-- Uncomment if already connected to the API, change getSuppliersLoading -->
                            <!-- <template v-slot:no-data>
                                <div class="no-data-preloader mt-4" v-if="getSuppliersLoading">
                                    <v-progress-circular
                                        :size="40"
                                        color="#0171a1"
                                        indeterminate
                                        v-if="getSuppliersLoading">
                                    </v-progress-circular>
                                </div>

                                <div class="no-data-wrapper"  v-if="!getSuppliersLoading && connectionsSampleData !== null && 
                                        connectionsSampleData.length !== 'undefined' 
                                        && connectionsSampleData.length == 0">

                                    <div class="no-data-heading">
                                        <img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="">
                                        <h3> No Purchase Order yet. </h3>
                                    </div>
                                </div>
                            </template> -->
                        </v-data-table>

                        <Pagination 
                            v-if="connectionsSampleData.length !== 0"
                            :pageData.sync="page"
                            :lengthData.sync="pageCount"
                            :isMobile="isMobile" />
                    </div>

                    <div class="vendor-payment-methods" v-if="currentTab == 2">
                        <v-data-table
                            :headers="vendorPaymentMethodsHeader"
                            mobile-breakpoint="769"
                            :items="paymentMethodsSampleData"
                            class="po-vendor-table elevation-1"
                            :class="paymentMethodsSampleData !== null && paymentMethodsSampleData.length !== 'undefined' && paymentMethodsSampleData.length !== 0 ? '' : 'no-data-table'"
                            fixed-header
                            hide-default-footer
                            :page.sync="page"
                            :items-per-page="itemsPerPage"
                            @page-count="pageCount = $event">

                            <template v-slot:top>
                                <v-toolbar flat>
                                    <button class="btn-blue">+ Add Payment Method</button>
                                </v-toolbar>
                            </template>

                            <template v-slot:[`item.method`]="{ item }">
                                <div class="details-payment-methods">
                                    <img src="@/assets/icons/mastercard.svg" width="56px" height="40px" v-if="item.card_type == 'mastercard'">
                                    <img src="@/assets/icons/visa.svg" width="56px" height="40px" v-if="item.card_type == 'visa'">
                                    <img src="@/assets/icons/amex.svg" width="56px" height="40px" v-if="item.card_type == 'american-express'">

                                    <div class="card-info">
                                        <p>{{ item.card_number }}</p>
                                        <span>{{ item.name }}, {{ item.expiration }}</span>
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.type`]="{ item }">
                                <p class="mb-0" style="text-transform: capitalize;">{{ item.type !== '' ? item.type : '-'}}</p>
                            </template>

                            <template v-slot:[`item.actions`]="{ item }">
                                <div class="item-button methods">
                                    <button class="btn-white" v-if="item.type !== 'default'">
                                        <p>Set as default</p>
                                    </button>

                                    <button class="btn-white" @click.stop="deletePaymentMethod(item)">
                                        <img src="@/assets/icons/delete-blue.svg" height="16px" width="16px">
                                    </button>
                                </div>
                            </template>

                            <!-- Uncomment if already connected to the API, change getSuppliersLoading -->
                            <!-- <template v-slot:no-data>
                                <div class="no-data-preloader mt-4" v-if="getSuppliersLoading">
                                    <v-progress-circular
                                        :size="40"
                                        color="#0171a1"
                                        indeterminate
                                        v-if="getSuppliersLoading">
                                    </v-progress-circular>
                                </div>

                                <div class="no-data-wrapper"  v-if="!getSuppliersLoading && connectionsSampleData !== null && 
                                        connectionsSampleData.length !== 'undefined' 
                                        && connectionsSampleData.length == 0">

                                    <div class="no-data-heading">
                                        <img src="@/assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="">
                                        <h3> No Purchase Order yet. </h3>
                                    </div>
                                </div>
                            </template> -->
                        </v-data-table>
                    </div>

                    <DeleteDialog
                        :dialogData.sync="dialogDelete"
                        :editedItemData.sync="currentItemToDelete"
                        :editedIndexWarehouse.sync="editedIndex"
                        :defaultItemWarehouse.sync="defaultItem"
                        @delete="deletePaymentMethodConfirm"
                        @close="closeDelete"
                        fromComponent="card"
                        :loadingDelete="false"
                        componentName="Payment Method"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import Search from '@/components/Search.vue'
import Pagination from '@/components/Pagination.vue'
import DeleteDialog from "@/components/Dialog/DeleteDialog.vue";

export default {
    name: 'SupplierCustomerDetailsPage',
    props: [],
    components: {
        Search,
        Pagination,
        DeleteDialog
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        isMobile: false,
        isDetailForVendor: false,
        currentTab: 0,
        tabs: ['Purchase Orders', 'Connection', 'Payment Method'],
        vendorPoHeader: [
            {
                text: "PO NO",
                align: "start",
                sortable: false,
                value: "po_num",
                width: "12%", 
                fixed: true
            },
            {
                text: "DATE",
                align: "start",
                sortable: false,
                value: "date",
                width: "12%", 
                fixed: true
            },
            {
                text: "SHIP TO",
				align: "start",
                value: "ship_to",
                sortable: false,
                width: "34%", 
                fixed: true
            },
            {
                text: "CARGO READY",
				align: "start",
                value: "cargo_ready_date",
                sortable: false,
                width: "12%", 
                fixed: true
            },
			{
                text: "DETAILS",
				align: "end",
                value: "details",
                sortable: false,
                width: "10%", 
                fixed: true
            },
            {
                text: "STATUS",
				align: "center",
                value: "status",
                sortable: false,
                width: "20%", 
                fixed: true
            },
        ],
        vendorConnectionHeader: [
            {
                text: "NAME",
                align: "start",
                sortable: false,
                value: "name",
                width: "30%", 
                fixed: true
            },
            {
                text: "EMAIL",
                align: "start",
                sortable: false,
                value: "emails",
                width: "25%", 
                fixed: true
            },
            {
                text: "",
				align: "center",
                value: "actions",
                sortable: false,
                width: "45%", 
                fixed: true
            },
        ],
        vendorPaymentMethodsHeader: [
            {
                text: "METHOD",
                align: "start",
                sortable: false,
                value: "method",
                width: "25%", 
                fixed: true
            },
            {
                text: "DATE ADDED",
                align: "start",
                sortable: false,
                value: "date_added",
                width: "15%", 
                fixed: true
            },
            {
                text: "LAST USED",
                align: "start",
                sortable: false,
                value: "last_used",
                width: "15%", 
                fixed: true
            },
            {
                text: "TYPE",
                align: "center",
                sortable: false,
                value: "type",
                width: "15%", 
                fixed: true
            },
            {
                text: "",
				align: "end",
                value: "actions",
                sortable: false,
                width: "30%", 
                fixed: true
            },
        ],
        purchaseOrderSampleData: [
            {
                po_num: '123456',
                date: 'Mar 01, 2021',
                ship_to: '2972 Westheimer Rd. Santa Ana, Illinois 85486',
                cargo_ready_date: 'Jun 13, 2021',
                unit_price: '3,959.76',
                total_units: '300',
                status: 'pending'
            },
            {
                po_num: '123454',
                date: 'Apr 01, 2021',
                ship_to: '8502 Preston Rd. Inglewood',
                cargo_ready_date: 'May 31, 2021',
                unit_price: '4,984.00',
                total_units: '120',
                status: 'accepted'
            },
            {
                po_num: '123432',
                date: 'Apr 09, 2021',
                ship_to: '2464 Royal Ln. Mesa, New Jersey 45463',
                cargo_ready_date: 'May 21, 2021',
                unit_price: '19,984.00',
                total_units: '386',
                status: 'rejected'
            },
            {
                po_num: '123430',
                date: 'Mar 29, 2021',
                ship_to: '1901 Thornridge Cir. Shiloh',
                cargo_ready_date: 'May 16, 2021',
                unit_price: '18,984.00',
                total_units: '386',
                status: 'change'
            },
        ],
        connectionsSampleData: [
            {
                name: 'Glean McGrath Corporation',
                emails: ['jean@gmcgrath.com', 'logistics@gmcgrath.com'],
                status: 'connected'
            },
            {
                name: 'Homecare Medical',
                emails: ['feeney.jamarcus@gmail.com', 'xrobel@mann.com'],
                status: 'connected'
            },
            {
                name: 'Elite Assistans',
                emails: ['mkling@wilkinson.biz'],
                status: ''
            },
            {
                name: 'Gina Tricot Sweden',
                emails: ['fbuckridge@schuster.com', 'xhansen@hotmail.com'],
                status: 'pending'
            }          
        ],
        paymentMethodsSampleData: [
            {
                card_number: '**** **** **** 3840',
                name: 'Alfraid Johson',
                expiration: '06/24',
                date_added: 'Mar 10, 2020',
                last_used: 'Apr 27, 2021',
                type: 'default',
                card_type: 'mastercard'
            },
            {
                card_number: '**** **** **** 3840',
                name: 'Alfraid Johson',
                expiration: '06/24',
                date_added: 'Jan 31, 2020',
                last_used: 'Aug 03, 2020',
                type: '',
                card_type: 'visa'
            },
            {
                card_number: '**** **** **** 3840',
                name: 'Alfraid Johson',
                expiration: '06/24',
                date_added: 'Dec 23, 2019',
                last_used: 'Jun 27, 2020',
                type: '',
                card_type: 'american-express'
            }
        ],
        dialogDelete: false,
        currentItemToDelete: null,
        editedIndex: -1,
		editedItem: {
			card_number: "",
			expiration: "",
			cvc: "",
			name_on_card: "",
			country: "",
			default: false,
		},
		defaultItem: {
			card_number: "",
			expiration: "",
			cvc: "",
			name_on_card: "",
			country: "",
			default: false,
		},
    }),
    computed: {
        ...mapGetters({}),
    },
    methods: {
        ...mapActions({}),
        onTabChange(i) {
            console.log(i);
        },
        getItemPerTabCounts(i) {
            let count = '0'

            if (i == 0) {
                count = '4'
            } else {
                count = 3
            }

            return count
        },
        onResize() {
            if (window.innerWidth < 769) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },
        checkUrlParamsFor() {
            let queryString = window.location.search
            const urlParams = new URLSearchParams(queryString)
            
            if (urlParams.has('vendor')) {
                this.isDetailForVendor = true
            } else {
                this.isDetailForVendor = false
            }
        },
        deletePaymentMethod(item) {
			this.dialogDelete = true;
			this.currentItemToDelete = item;
		},
		async deletePaymentMethodConfirm() {
			// if (this.currentItemToDelete !== null) {
			// 	this.getDeletePaymentMethod = true;
			// 	const params ={
			// 		card_id: this.currentItemToDelete.card_id
			// 	}
			// 	await this.removePaymentMethod(params)
			// 	this.fetchPaymentMethods();
			// 	setTimeout(() => {
			// 		this.notificationMessage("Payment method has been deleted.");
			// 		this.getDeletePaymentMethod = false;
			// 		this.closeDelete();
			// 	}, 3000);
			// }
            console.log(this.currentItemToDelete);
		},
		closeDelete() {
			this.dialogDelete = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},
    },
    mounted() {
        this.checkUrlParamsFor()
        this.$store.dispatch("page/setPage", "contact")
    },
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/supplier/vendorDetailsPage.scss';
@import '@/assets/scss/buttons.scss';
</style>
