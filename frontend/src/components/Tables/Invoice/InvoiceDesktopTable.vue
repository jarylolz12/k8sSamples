<template>
    <div class="supplier-content-wrapper">
        <v-data-table
            v-model="selected"
            :headers="headers"
            mobile-breakpoint="769"
            :items="invoices"
            :single-select="singleSelect"
            item-key="name"
            show-select
            class="suppliers-table elevation-1"
            :class="invoices !== null && invoices.length !== 'undefined' && invoices.length !== 0 ? '' : 'no-data-table'"
			:search="search"
            :custom-filter="customFilter"
			:page.sync="page"
            :items-per-page="itemsPerPage"
			@page-count="pageCount = $event"
            @click:row="handleClick"
			hide-default-footer
            fixed-header>
            <template v-slot:top>
                <v-toolbar short>
                    <v-toolbar-title>Invoices</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <Filters
                    />
					<Search
						placeholder="Search Invoice"
						className="search custom-search"
						:inputData.sync="search"/>
                    <v-btn color="primary" outlined class="btn-blue add-supplier" @click.stop="addSupplier">
                        Create Invoice
                    </v-btn>

                    <!-- <v-btn text class="text-capitalize" @click.stop="addSupplier">
                        <div class="black--text text-capitalize">
                            Clear Selection
                        </div>
                    </v-btn>
                    <v-btn color="#B4CFE0" outlined class="add-supplier mx-1" @click.stop="addSupplier">
                        <div class="black--text text-capitalize">
                            Send Reminder
                        </div>
                    </v-btn>
                    <v-btn color="#B4CFE0" outlined class="add-supplier mx-1" @click.stop="addSupplier">
                        <v-icon left color="primary">
                                mdi-printer-outline
                        </v-icon>
                        <div class="primary--text text-capitalize">
                            Print
                        </div>
                    </v-btn>
                    <v-btn color="#B4CFE0" outlined class="add-supplier mx-1" @click.stop="addSupplier">
                        <div class="red--text text-capitalize">
                            Delete
                        </div>
                    </v-btn> -->
                </v-toolbar>
                <v-toolbar>
                    <v-tabs class="customTab" v-model="tab">
                        <v-tab  @click="changeTabValue(item.tab)"
                            v-for="item in tabitems"
                            :key="item.tab"
                        ><div class="">
                            {{ item.tab }}
                        </div>
                        </v-tab>
                    </v-tabs>
                </v-toolbar>
            </template>
			<template v-slot:[`item.name`]="{ item }">
                <div style="white-space: nowrap" v-if="item.name !== ''">
                    <p class="mb-0">{{ item.name }}</p>
                </div>

                <!-- <div v-if="item.customer == ''">
                    <p class="mb-0" style="color: #4a4a4a;">--</p>
                </div> -->
            </template>
            <template v-slot:[`item.invoice`]="{ item }">
                <div style="white-space: nowrap" v-if="item.invoice !== ''">
                    <p class="mb-0">{{ item.invoice }}</p>
                </div>

                <!-- <div v-if="item.customer == ''">
                    <p class="mb-0" style="color: #4a4a4a;">--</p>
                </div> -->
            </template>
			<template v-slot:[`item.customer`]="{ item }">
                <div style="white-space: nowrap" v-if="item.customer !== ''">
                    <p class="mb-0">{{ item.customer }}</p>
                </div>


                <!-- <div v-if="item.customer == ''">
                    <p class="mb-0" style="color: #4a4a4a;">--</p>
                </div> -->
            </template>
            <template v-slot:[`item.reference`]="{ item }">
                <p style="color: #0171A1; white-space: nowrap" class="mb-0 table-wrapper">{{ item.reference !== '' ? item.reference : '--' }}</p>
            </template>
            <template v-slot:[`item.duedate`]="{ item }">
                <p style="white-space: nowrap" class="mb-0" v-if="item.duedate !== ''">{{item.duedate}}</p>
                <p style="white-space: nowrap" class="mb-0" v-if="item.duedate == ''" >{{ '--' }}</p>
            </template>
            <template v-slot:[`item.amount`]="{ item }">
                    <!-- <p style="white-space: nowrap" class="mb-0">{{ item.amount !== '' ? item.amount : '--' }}</p> -->
                    <p style="white-space: nowrap" class="mb-0" v-if="item.amount !== ''">{{item.amount}}</p>
                <p style="white-space: nowrap" class="mb-0 text-center" v-if="item.amount == ''" >{{ '--' }}</p>
            </template>
            <template v-slot:[`item.status`]="{ item }"
            >
                        <div v-if="item.status == 'paid'">
                            <p style="margin-bottom: 0px; color: #49AF41; white-space: nowrap">
                                {{ item.status }}
                            </p>
                            <p style="color: grey; white-space: nowrap">
                                On {{ item.paid_date }}
                            </p>
                        </div>
                        <div v-if="item.status == 'Overdue'">
                            <p style="margin-bottom: 0px; color: red; white-space: nowrap">
                                {{ item.status }}
                            </p>
                            <p v-if="item.status !== ''" style="color: grey; white-space: nowrap">
                                Partially paid, {{ item.paid }}
                            </p>
                        </div>
                        <div v-if="item.status == 'Partially Paid'">
                            <p style="margin-bottom: 0px; color: #2DC48E; white-space: nowrap">
                                {{ item.status }}
                            </p>
                            <p v-if="item.paid !== ''" style="color: grey; white-space: nowrap">
                                paid, {{ item.paid }}
                            </p>
                        </div>
                        <div v-if="item.status == 'Open'">
                            <p style="margin-bottom: 0px; color: #0171A1; white-space: nowrap">
                                {{ item.status }}
                            </p>
                        </div>
                        <div v-if="item.status == 'Draft'">
                            <p style="margin-bottom: 0px; color: grey; white-space: nowrap">
                                {{ item.status }}
                            </p>
                        </div>
                        <div v-if="item.status == 'Sent'">
                            <p style="margin-bottom: 0px; white-space: nowrap">
                                {{ item.status }}
                            </p>
                        </div>

            </template>
            <template v-slot:[`item.actions`]="{ item }">
                <div class="text-center">
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <div class="d-flex">
                                <v-btn
                                    class="mx-2 rounded"
                                    fab
                                    dark
                                    small
                                    outlined
                                    tile
                                    color="primary"
                                    v-bind="attrs"
                                    v-on="on"
                                    >
                                    <img src="../../../assets/icons/view-blue.svg" alt="">
                                </v-btn>
                                <v-btn
                                    class="mx-2 rounded"
                                    fab
                                    dark
                                    small
                                    outlined
                                    tile
                                    color="primary"
                                    v-bind="attrs"
                                    v-on="on"
                                    >
                                    <img src="../../../assets/icons/more.svg" alt="">
                                </v-btn>
                            </div>
                        </template>
                        <v-list class="py-0"
                        style="width: 150px; overflow-x: visible"
                        >
                            <v-list-item  v-if="item.status.status == 'Partially Paid'"
                            style="min-height: 30px" @click="editSupplier(item)"
                            >
                            <small style="color: #0171A1"> Recieve Payment </small>
                            </v-list-item>
                            <v-list-item v-if="item.status.status !== 'Partially Paid'"
                            style="min-height: 30px" @click="editSupplier(item)"
                            >
                            <small style="color: #0171A1"> Print </small>
                            </v-list-item>
                            <v-list-item
                            style="min-height: 30px" @click="editSupplier(item)"
                            >
                            <small> View </small>
                            </v-list-item>
                            <v-list-item
                            style="min-height: 30px" @click="editSupplier(item)"
                            >
                            <small> Edit </small>
                            </v-list-item>
                            <v-list-item
                            style="min-height: 30px" @click="editSupplier(item)"
                            >
                            <small> Duplicate </small>
                            </v-list-item>
                            <v-list-item
                            style="min-height: 30px" @click="editSupplier(item)"
                            >
                            <small> Send </small>
                            </v-list-item>
                            <v-list-item v-if="item.status.status == 'Partially Paid'"
                            style="min-height: 30px" @click="editSupplier(item)"
                            >
                            <small> Send Reminder</small>
                            </v-list-item>
                            <v-list-item
                            style="min-height: 30px" @click="deleteInvoice"
                            >
                            <small style="color: red"> Delete </small>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>
<!-- <div class="item-button" @click="editSupplier(item)">
					<img src="../../../assets/icons/edit-blue.svg" alt="">
				</div> -->

             <template slot="no-results">
                <div class="no-data-wrapper">
                    <div class="pa-15">
                        <img src="../../../assets/images/search-icon.svg" width="40px" height="42px" alt="">

                        <h3> No matching result </h3>
                        <p>
                            Sorry! We could not find any invoice that matches<br>
                            your search term.
                        </p>

                        <div class="mt-4">
                            <v-btn color="primary" outlined class="add-supplier">
                                Try Different Search
                            </v-btn>
                        </div>
                    </div>
                    <div v-if="tabs==2" class="no-data-heading">
                        <img src="../../../assets/icons/freightCompleted.svg" width="40px" height="42px" alt="">

                        <h3> No paid Bills </h3>
                        <p>
                            There is no invoice on Paid Status
                        </p>

                        <div class="mt-4">
                            <v-btn color="primary" outlined class="btn-blue add-supplier" @click.stop="addSupplier">
                                Create Invoice
                            </v-btn>
                        </div>
                    </div>
                    <div v-if="tabs==3" class="no-data-heading">
                        <img src="../../../assets/icons/freightCompleted.svg" width="40px" height="42px" alt="">

                        <h3> No Unpaid Bills </h3>
                        <p>
                            There is no invoice on unpaid status
                        </p>

                        <div class="mt-4">
                            <v-btn color="primary" outlined class="btn-blue add-supplier" @click.stop="addSupplier">
                                Create Invoice
                            </v-btn>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:no-data>
                <div class="no-data-preloader mt-4" v-if="getinvoiceloading==false">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate
                        v-if="getinvoiceloading==false">
                    </v-progress-circular>
                </div>

                <div class="no-data-wrapper" v-if="getinvoiceloading==true && invoices.length == 0">
                    <div v-if="tabs==1" class="no-data-heading">
                        <img src="../../../assets/icons/freightCompleted.svg" width="40px" height="42px" alt="">

                        <h3> Create Invoices </h3>
                        <p>
                            There is no invoice to view <br>
                            Let's create a new Invoice.
                        </p>

                        <div class="mt-4">
                            <v-btn color="primary" outlined class="btn-blue add-supplier" @click.stop="addSupplier">
                                Create Invoice
                            </v-btn>
                        </div>
                    </div>
                    <div v-if="tabs==2" class="no-data-heading">
                        <img src="../../../assets/icons/freightCompleted.svg" width="40px" height="42px" alt="">

                        <h3> No paid Bills </h3>
                        <p>
                            There is no invoice on Paid Status
                        </p>

                        <div class="mt-4">
                            <v-btn color="primary" outlined class="btn-blue add-supplier" @click.stop="addSupplier">
                                Create Invoice
                            </v-btn>
                        </div>
                    </div>
                    <div v-if="tabs==3" class="no-data-heading">
                        <img src="../../../assets/icons/freightCompleted.svg" width="40px" height="42px" alt="">

                        <h3> No Unpaid Bills </h3>
                        <p>
                            There is no invoice on unpaid status
                        </p>

                        <div class="mt-4">
                            <v-btn color="primary" outlined class="btn-blue add-supplier" @click.stop="addSupplier">
                                Create Invoice
                            </v-btn>
                        </div>
                    </div>
                </div>
            </template>
        </v-data-table>

		<Pagination
            v-if="invoices.length !== 0"
			:pageData.sync="page"
			:lengthData.sync="pageCount"
			:isMobile="isMobile"
		/>
        <v-dialog
            v-model="alert"
            hide-overlay
        >
            <v-alert
                origin="bottom"
                transition="scale-transition"
                type="dark"
                color="grey darken-3"
                max-width="500"
                >
                <!-- Invoice #Example has been created -->
                Invoice #Example Saved as Draft
            </v-alert>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px" content-class="item-delete-dialog">
            <v-card class="delete-dialog">
                <v-card-title class="headline">
                    <div class="delete-icon mt-3 mb-1">
                        <img src="../../../assets/icons/icon-delete.svg" alt="">
                    </div>
                </v-card-title>

                <v-card-text style="padding-bottom: 15px;">
                    <h2>
                        <!-- Delete Invoice -->
                        Delete failed
                    </h2>
                    <p v-if="fromComponent !== 'card'">
                        <!-- Do you want to delete the Invoice #example -->
                        You can not delete an invoice that is partially Paid. you need to update the payment first.
                    </p>

                    <p v-else>
                        Do you want to delete the                    </p>
                </v-card-text>

                <v-card-actions class="delete-btn-wrapper">
                    <v-btn class="delete-btn" text @click="deleteItemConfirm">
                        <span v-if="loadingDelete">Deleting...</span>
                        <span v-if="!loadingDelete">Delete</span>
                    </v-btn>
                    <v-btn class="cancel-btn" text @click="closeDelete">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>

</template>


<script>
import { mapActions, mapGetters } from 'vuex'
import Search from '../../Search.vue'
import Pagination from '../../Pagination.vue'
import Filters from '../../Filters.vue'

export default {
    name: "InvoiceDesktopTable",
    props: ['items', 'isMobile', 'success', 'alert'],
	components: {
		Search,
		Pagination,
        Filters,
	},
    data: () => ({
        getinvoiceloading: false,
        tabs: 1,
		page: 1,
        pageCount: 0,
        itemsPerPage: 10,
        singleSelect: false,
        dialogDelete: false,
        // selected: [],
        filters: {
            paid: '',
            Overdue: '',
            Partially_Paid: '',
            Open: '',
            Draft: '',
            Sent: ''
        },
        headers: [
          {
            text: 'Invoice Date',
            align: 'start',
            sortable: false,
            value: 'name',
            width: "12%"
          },
          { text: 'Invoice No', value: 'invoice', width: "10%", align: 'start',},
          { text: 'Customer', value: 'customer', width: "16%", align: 'start',},
          { text: 'Reference', value: 'reference', align: 'start', },
          { text: 'Due Date', value: 'duedate', align: 'end',},
          { text: 'Amount', value: 'amount' , width: "10%", align: 'end',},
          { text: 'Status', value: 'status' , width: "15%", align: 'end',},
          { text: '', value: 'actions' , width: "13%", align: 'end',},
        ],
        invoices: [
          {
            name: 'May 10, 2022',
            invoice: 10000145,
            customer: 'Syndicated service',
            reference: '#SHIFL32451',
            duedate: 'Jun 5, 2022',
            amount: '$5,830.50',
            // status: {status:'paid', paid_date: 'Jun 4, 2021', paid:''},
            status:'paid',
            paid_date: 'Jun 4, 2021',
            paid:'',
          },
          {
            name: 'Jun 15, 2022',
            invoice: 10003456,
            customer: 'Sensational Service',
            reference: '#SHIFL32452',
            duedate: 'Jul 10, 2022',
            amount: '$3,250.00',
            // status: {status:'Overdue', Paid_date: '', paid:'$1000'},
            status:'Overdue',
            paid_date: 'Jun 4, 2021',
            paid:'$1000',
          },
          {
            name: 'Jun 18, 2022',
            invoice: 10003457,
            customer: 'New Service Int.',
            reference: '#SHIFL32453',
            duedate: 'Jul 15, 2022',
            amount: '$6420.00',
            // status: {status:'Partially Paid', Paid_date: '', paid:'$2000'},
            status:'Partially Paid',
            paid_date: 'Jun 4, 2021',
            paid:'$2000',
          },
          {
            name: 'Jun 10, 2022',
            invoice: 10003458,
            customer: 'White Glove Service',
            reference: '#SHIFL32456',
            duedate: 'Jul 16, 2022',
            amount: '',
            // status: {status:'Overdue', Paid_date: '', paid:''},
            status:'Overdue',
            paid_date: 'Jun 4, 2021',
            paid:'',
          },
          {
            name: 'Jun 5,2022',
            invoice: 10003459,
            customer: 'Opulent Operators',
            reference: '#SHIFL32455',
            duedate: 'Jul 10, 2022',
            amount: '',
            // status: {status:'Open', Paid_date: '', paid:''},
            status:'Open',
            paid_date: 'Jun 4, 2021',
            paid:'',
          },
          {
            name: 'Jul 14, 2022',
            invoice: 10003465,
            customer: 'Customer Companions',
            reference: '#SHIFL32457',
            duedate: 'Jul 18, 2022',
            amount: '',
            // status: {status:'Draft', Paid_date: '', paid:''},
            status:'Draft',
            paid_date: 'Jun 4, 2021',
            paid:'',
          },
          {
            name: 'Jul 16, 2022',
            invoice: 10003460,
            customer: 'Syndicated Service',
            reference: '#SHIFL32458',
            duedate: 'Jul 20, 2022',
            amount: '',
            // status: {status:'Sent', Paid_date: '', paid:''},
            status:'Sent',
            paid_date: 'Jun 4, 2021',
            paid:'',
          },
          {
            name: 'Jun 7, 2022',
            invoice: 10003461,
            customer: 'Sensational service',
            reference: '#SHIFL32459',
            duedate: 'Jul 10, 2022',
            amount: '',
            // status: {status:'Sent', Paid_date: '', paid:''},
            status:'Sent',
            paid_date: 'Jun 4, 2021',
            paid:'',
          },
          {
            name: 'Aug 13, 2022',
            invoice: 10003462,
            customer: 'New Service Int.',
            reference: '#SHIFL32460',
            duedate: 'Aug 20, 2022',
            amount: '',
            // status: {status:'Sent', Paid_date: '', paid:''},
            status:'Sent',
            paid_date: 'Jun 4, 2021',
            paid:'',
          },
          {
            name: 'Jun 2, 2022',
            invoice: 10003463,
            customer: 'Syndicated Service',
            reference: '#SHIFL32461',
            duedate: 'Jul 10, 2022',
            amount: '',
            // status: {status:'Sent', Paid_date: '', paid:''},
            status:'Sent',
            paid_date: 'Jun 4, 2021',
            paid:'',
          },
          {
            name: 'Jun 4, 2022',
            invoice: 10003464,
            customer: 'Opulent service',
            reference: '#SHIFL32462',
            duedate: 'Jul 10, 2022',
            amount: '',
            // status: {status:'Sent', Paid_date: '', paid:''},
            status:'Sent',
            paid_date: 'Jun 4, 2021',
            paid:'',
          },
        ],

		search: '',
        tab: null,
        tabitems: [
          { tab: 'All', content: 'Tab 1 Content' },
          { tab: 'Paid', content: 'Tab 2 Content' },
          { tab: 'Unpaid', content: 'Tab 3 Content' },
        ],
        ditems: [
        { title: 'Click Me' },
        { title: 'Click Me' },
        { title: 'Click Me' },
        { title: 'Click Me 2' },
        ],
    }),
    computed: {
        ...mapGetters({
            getSuppliers: 'suppliers/getSuppliers',
            getUser: 'getUser',
            getSuppliersLoading: 'suppliers/getSuppliersLoading'
        }),
        formTitle() {
            return this.editedIndex === -1 ? "Add Supplier" : "Edit Supplier";
        },
        suppliers: {
            get() {
                return this.items
            },
            set(value) {
                this.$emit('items', value)
            }
        }
    },
    watch: {},
    created() {
    },
    methods: {
     changeTabValue(tab){
            if(tab=='Paid'){
                this.tabs=2
                this.search='paid'
                // this.filters.paid='paid'
                // this.filters.Partially_Paid="Partially Paid"
            }
            else if (tab=="Unpaid"){
                this.tabs=3
                this.search="Overdue"
                // this.filters.Overdue="Overdue"
        //         filters: {
        //     paid: '',
        //     Overdue: '',
        //     Partially_Paid: '',
        //     Open: '',
        //     Draft: '',
        //     Sent: ''
        // },

            } else if (tab=="All"){
                this.tabs=1
                this.search=""
            }
        },
        handleClick() {
            // this.$router.push(`shipment/${value.id}?status=${value.Status}`)
            // this.$router.push(`invoice/${value.id}`)
            this.$router.push(`details`)

        },
        ...mapActions({
            fetchSuppliers: 'suppliers/fetchSuppliers'
        }),
        addSupplier() {
            this.$emit('addSupplier')
        },
        editSupplier(item) {
            this.$emit('editSupplier', item)
        },
        deleteInvoice() {
            console.log('delete');
            // this.$emit('dialogDelete')
            this.dialogDelete=true
            console.log('deleted')
        }
    },
    async mounted() {
        //set current page
        // this.$store.dispatch("page/setPage", "suppliers");
    },
    updated() {}
};
</script>

<style scoped lang="scss">
    // .customTab {
    //     border-top-left-radius: 4px;
    //     border-top-right-radius: 4px;
    // }

    .v-tab {
        padding: 12px 8px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        color: #4a4a4a !important;
    }
    .v-tab--active {
        color: #0171a1 !important;
    }
    // .v-tab:before,
    // .v-tab:focus,
    // .v-tab:hover {
    //     background-color: transparent !important;
    // }
    .table-wrapper {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    white-space: nowrap;
    }
    .table-container .p {
    flex: 1 1 100%;
    min-width: 0;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    }
    .v-alert {
        margin-bottom: 0px !important;
        bottom: 5vh !important;
        right: 25vw !important;
        position: absolute !important;
    }
    .v-icon.notranslate.mdi.mdi-checkbox-blank-outline.theme--light {
    color: red !important;
}
    // .v-dialog{
    //     align-self: flex-end !important;
    // }
@import '../../../assets/css/suppliers_styles/suppliers.css';
// @import '../../../assets/css/dialog_styles/dialogHeader.css';
// @import '../../../assets/css/dialog_styles/dialogBody.css';
// @import '../../../assets/css/dialog_styles/dialogFooter.css';

</style>