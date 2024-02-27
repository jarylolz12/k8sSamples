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
            :class="suppliers !== null && suppliers.length !== 'undefined' && suppliers.length !== 0 ? '' : 'no-data-table'"
			:search="search"
			:page.sync="page"
            :items-per-page="itemsPerPage"
			@page-count="pageCount = $event"
			hide-default-footer
            fixed-header>
            <template v-slot:top>
                <v-toolbar short>
                    <v-toolbar-title>Invoices</v-toolbar-title>
					
                    <v-spacer></v-spacer>

					<Search 
						placeholder="Search Invoice"
						className="search custom-search"
						:inputData.sync="search" />

                    <v-btn color="primary" class="btn-blue add-supplier" @click.stop="addSupplier">
                        Create Invoice
                    </v-btn>
                </v-toolbar>
                <v-toolbar flat>
                   <strong>Unpaid</strong>
                </v-toolbar>
            </template>

			<template v-slot:[`item.emails`]="{ item }">
                <div class="email-wrapper" v-if="item.emails !== ''">
                    <div class="email-container" v-for="(email, index) in item.emails" :key="index">
                        <p class="mb-0" style="color: #0171A1;">{{ email }}</p>
                    </div>
                </div>

                <div v-if="item.emails == ''">
                    <p class="mb-0" style="color: #4a4a4a;">--</p>
                </div>
            </template>

            <template v-slot:[`item.phone`]="{ item }">
                <p class="mb-0">{{ item.phone !== '' ? item.phone : '--' }}</p>
            </template> 

            <template v-slot:[`item.address`]="{ item }">
                <p class="mb-0">{{ item.address !== '' ? item.address : '--' }}</p>
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
                    style="width: 120px; overflow-x: visible"
                    >
                        <v-list-item
                        class="" @click="editSupplier(item)"
                        >
                        <small> Print </small>
                        </v-list-item>
                        <v-list-item
                        class="" @click="editSupplier(item)"
                        >
                        <small> Print </small>
                        </v-list-item>
                        <v-list-item
                        class="" @click="editSupplier(item)"
                        >
                        <small> Print </small>
                        </v-list-item>
                        <v-list-item
                        class="" @click="editSupplier(item)"
                        >
                        <small> Print </small>
                        </v-list-item>
                        <v-list-item
                        class=" text-sm " @click="editSupplier(item)"
                        >
                        <small> Print </small>
                        </v-list-item>
                        <v-list-item
                        class="" @click="editSupplier(item)"
                        >
                        <small> Print </small>
                        </v-list-item>
                    </v-list>
                    </v-menu>
                </div>
            </template>
<!-- <div class="item-button" @click="editSupplier(item)">
					<img src="../../../assets/icons/edit-blue.svg" alt="">
				</div> -->

            <template v-slot:no-data>
                <div class="no-data-preloader mt-4" v-if="getSuppliersLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate
                        v-if="getSuppliersLoading">
                    </v-progress-circular>
                </div>

                <div class="no-data-wrapper" v-if="!getSuppliersLoading && suppliers !== null && suppliers.length !== 'undefined' && suppliers.length == 0">
                    <div class="no-data-heading">
                        <img src="../../../assets/icons/empty-supplier-icon.svg" width="40px" height="42px" alt="">

                        <h3> Add Supplier </h3>
                        <p>
                            Let’s add the first supplier on Shifl. <br>
                            You can use this supplier list for creating Purchase Orders.
                        </p>

                        <div class="mt-4">
                            <v-btn color="primary" class="btn-blue add-supplier" @click.stop="addSupplier">
                                Add Supplier
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
    </div>

</template>


<script>
import { mapActions, mapGetters } from 'vuex'
import Search from '../../Search.vue'
import Pagination from '../../Pagination.vue'

export default {
    name: "InvoiceDesktopTable",
    props: ['items', 'isMobile'],
	components: {
		Search,
		Pagination,
	},
    data: () => ({
		page: 1,
        pageCount: 0,
        itemsPerPage: 10,
        singleSelect: false,
        selected: [],
        headers: [
          {
            text: 'invoice Date',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'Calories', value: 'calories' },
          { text: 'Fat (g)', value: 'fat' },
          { text: 'Carbs (g)', value: 'carbs' },
          { text: 'Protein (g)', value: 'protein' },
          { text: 'Iron (%)', value: 'iron' },
          { text: 'Actions', value: 'actions' },
        ],
        invoices: [
          {
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            iron: '1%',
          },
          {
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            iron: '1%',
          },
          {
            name: 'Ice cream sandwich',
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3,
            iron: '1%',
          },
          {
            name: 'Eclair',
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0,
            iron: '7%',
          },
          {
            name: 'Cupcake',
            calories: 305,
            fat: 3.7,
            carbs: 67,
            protein: 4.3,
            iron: '8%',
          },
          {
            name: 'Gingerbread',
            calories: 356,
            fat: 16.0,
            carbs: 49,
            protein: 3.9,
            iron: '16%',
          },
          {
            name: 'Jelly bean',
            calories: 375,
            fat: 0.0,
            carbs: 94,
            protein: 0.0,
            iron: '0%',
          },
          {
            name: 'Lollipop',
            calories: 392,
            fat: 0.2,
            carbs: 98,
            protein: 0,
            iron: '2%',
          },
          {
            name: 'Honeycomb',
            calories: 408,
            fat: 3.2,
            carbs: 87,
            protein: 6.5,
            iron: '45%',
          },
          {
            name: 'Donut',
            calories: 452,
            fat: 25.0,
            carbs: 51,
            protein: 4.9,
            iron: '22%',
          },
          {
            name: 'KitKat',
            calories: 518,
            fat: 26.0,
            carbs: 65,
            protein: 7,
            iron: '6%',
          },
        ],
        // headers: [
        //     {
        //         text: "Name",
        //         align: "start",
        //         sortable: false,
        //         value: "company_name",
        //         width: "25%", 
        //         fixed: true
        //     },
        //     {
        //         text: "Phone",
		// 		align: "start",
        //         value: "phone",
        //         sortable: false,
        //         width: "20%", 
        //         fixed: true
        //     },
        //     {
        //         text: "Address",
		// 		align: "start",
        //         value: "address",
        //         sortable: false,
        //         width: "25%", 
        //         fixed: true
        //     },
		// 	{
        //         text: "Email",
		// 		align: "start",
        //         value: "emails",
        //         sortable: false,
        //         width: "20%", 
        //         fixed: true
        //     },
		// 	{
        //         text: "",
		// 		align: "center",
        //         value: "actions",
        //         sortable: false,
        //         width: "10%", 
        //         fixed: true
        //     },
        // ],
		search: '',
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
    created() {},
    methods: {
        ...mapActions({
            fetchSuppliers: 'suppliers/fetchSuppliers'
        }),
        addSupplier() {
            this.$emit('addSupplier')
        },
        editSupplier(item) {
            this.$emit('editSupplier', item)
        },
        deleteSupplier() {
            console.log('delete');
        }
        // editItem(item) {
        //     this.editedIndex = this.suppliers.indexOf(item);
        //     this.editedItem = Object.assign({}, item);
        //     this.dialog = true;
        // },
        // deleteItem(item) {
        //     this.editedIndex = this.suppliers.indexOf(item);
        //     this.editedItem = Object.assign({}, item);
        //     this.dialogDelete = true;
        // },
        // deleteItemConfirm() {
        //     this.suppliers.splice(this.editedIndex, 1);
        //     this.closeDelete();
        // },
        // close() {
        //     this.dialog = false;
        //     this.$nextTick(() => {
        //         this.editedItem = Object.assign({}, this.defaultItem);
        //         this.editedIndex = -1;
        //     });
        // },
        // closeDelete() {
        //     this.dialogDelete = false;
        //     this.$nextTick(() => {
        //         this.editedItem = Object.assign({}, this.defaultItem);
        //         this.editedIndex = -1;
        //     });
        // },
		// onResize() {
        //     if (window.innerWidth < 769) {
        //         this.isMobile = true
        //     } else {
        //         this.isMobile = false
        //     }
        // },
        // setToDefault() {
        //     this.editedItem = this.defaultItem
        //     this.close()
        //     this.dialog = true
        // }
    },
    async mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "suppliers");  
    },
    updated() {}
};
</script>

<style>
/* @import '../../../assets/css/suppliers_styles/suppliers.css';
@import '../../../assets/css/dialog_styles/dialogHeader.css';
@import '../../../assets/css/dialog_styles/dialogBody.css';
@import '../../../assets/css/dialog_styles/dialogFooter.css'; */

</style>