<template>
    <div class="supplier-container" v-resize="onResize">
        
		<!-- DESKTOP -->
        <InvoiceDesktopTable 
            :items="suppliers"
            @addSupplier="addSupplier"
            @editSupplier="editSupplier"
            :success.sync="success"
            :alert.sync="alert"
            :isMobile="isMobile"
            v-if="!isMobile"
        />

		<!-- MOBILE -->
		<InvoiceMobileTable 
            :items="suppliers"
            @addSupplier="addSupplier"
            @editSupplier="editSupplier"
            :isMobile="isMobile"
            v-if="isMobile"
        />

        <InvoiceDialog 
            :editedIndexData.sync="editedIndex"
            :dialogData.sync="dialog"
            :editedItemData.sync="editedItem"
            :defaultItemData.sync="defaultItem"
            :success.sync="success"
            :alert.sync="alert"
            @setToDefault="setToDefault"
            @close="close" 
        />
    </div>
</template>

<script>
import InvoiceDialog from '../components/InvoiceComponents/InvoiceDialog.vue'
import InvoiceDesktopTable from '../components/Tables/Invoice/InvoiceDesktopTable.vue'
import InvoiceMobileTable from '../components/Tables/Invoice/InvoiceMobileTable.vue'

export default {
    name: "Invoices",
	components: {
		InvoiceDialog,
        InvoiceDesktopTable,
        InvoiceMobileTable,
	},
    data: () => ({
		page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        dialog: false,
        dialogDelete: false,
        headers: [
            {
                text: "Name",
                align: "start",
                sortable: false,
                value: "company_name",
                width: "25%", 
                fixed: true
            },
            {
                text: "Phone",
				align: "start",
                value: "phone",
                sortable: false,
                width: "20%", 
                fixed: true
            },
            {
                text: "Address",
				align: "start",
                value: "address",
                sortable: false,
                width: "25%", 
                fixed: true
            },
			{
                text: "Email",
				align: "start",
                value: "emails",
                sortable: false,
                width: "20%", 
                fixed: true
            },
			{
                text: "",
				align: "center",
                value: "actions",
                sortable: false,
                width: "10%", 
                fixed: true
            },
        ],
        editedIndex: -1,
        editedItem: {
            company_name: "shah",
            phone: "12345",
            address: "",
            emails: null
        },
        defaultItem: {
            company_name: "",
            phone: "",
            address: "",
            emails: null
        },
        alert: false,
        success: false,
		search: '',
		isMobile: false
    }),
    computed: {

    },
    watch: {
        dialog(val) {
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        },
    },
    created() {},
    methods: {
        dialogDelete(){
            this.dialogDelete=true;
        },
        addSupplier() {
            this.dialog = true;
        },
        editSupplier(item) {
            this.editedIndex = this.suppliers.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },

        close() {
            this.success=true
            this.alert=true

            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
            if(this.alert == true){
                window.setTimeout(() => {
                this.alert = false;
                console.log("Case 1: Time ends - hide alert");
                }, 2000);
            }else{
                console.log("Case 2: User Hide alert by click - stop setTimeout");
                this.resetTimer();
            }
        },

        
        deleteSupplier(item) {
            this.editedIndex = this.suppliers.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogDelete = true;
        },
        deleteSupplierConfirm() {
            this.suppliers.splice(this.editedIndex, 1);
            this.closeDelete();
        },
        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },
        setToDefault() {
            this.editedItem = this.defaultItem
            this.close()
            this.dialog = true
        }
    },
    async mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "invoices");       
    },
};
</script>

<style lang="scss">
@import '../assets/scss/pages_scss/invoice/invoice.scss';
@import '../assets/scss/buttons.scss';
@import '../assets/css/dialog_styles/dialogHeader.css';
@import '../assets/css/dialog_styles/dialogBody.css';
@import '../assets/css/dialog_styles/dialogFooter.css';
@import '../assets/css/dialog_styles/deleteDialog.css';
</style>
