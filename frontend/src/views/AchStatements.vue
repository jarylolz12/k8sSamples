<template>
    <div class="statement-container" v-resize="onResize">
		<!-- DESKTOP -->
        <AchStatementDesktopTable 
            :items="statements"
            :isMobile="isMobile"
            :showReportSchedule="showReportSchedule"
            v-if="!isMobile"
        />

		<!-- MOBILE -->
		<!-- <AchStatementMobileTable 
            :items="statements"
            :isMobile="isMobile"
            v-if="isMobile"
        /> -->

        <AchStatementDialog 
            :editedIndexData.sync="editedIndex"
            :dialogData.sync="dialog"
            :editedItemData.sync="editedItem"
            :defaultItemData.sync="defaultItem"
            @setToDefault="setToDefault"
            @close="close" 
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import AchStatementDialog from '../components/AchStatementComponents/AchStatementDialog.vue'
import AchStatementDesktopTable from '../components/Tables/AchStatement/AchStatementDesktopTable.vue'
// import AchStatementMobileTable from '../components/Tables/AchStatement/AchStatementMobileTable.vue'

export default {
    name: "AchStatements",
	components: {
		AchStatementDialog,
        AchStatementDesktopTable,
        // AchStatementMobileTable
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
            company_name: "",
            phone: "",
            address: "",
            emails: null
        },
        defaultItem: {
            company_name: "",
            phone: "",
            address: "",
            emails: null
        },
		search: '',
		isMobile: false
    }),
    computed: {
        ...mapGetters({
            getAchStatements: 'statements/getAchStatements',
            getUser: 'getUser',
            getAchStatementsLoading: 'statements/getAchStatementsLoading'
        }),
        statements() {
            let data = []

            if (typeof this.getAchStatements !== 'undefined' && this.getAchStatements !== null) {

                if (typeof this.getAchStatements.data !== 'undefined' && this.getAchStatements.data.length !== 'undefined') {
                    data = this.getAchStatements.data

                    // data.map((value) => {
                    // })
                }
            }

            return data
        }
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
        ...mapActions({
            fetchAchStatements: 'statements/fetchAchStatements'
        }),
        showReportSchedule() {
            this.dialog = true;
        },
        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },
		onResize() {
            if (window.innerWidth < 769) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },
        setToDefault() {
            this.editedItem = this.defaultItem
            this.close()
            this.dialog = true
        }
    },
    async mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "statements");
        await this.fetchAchStatements()        
    },
    updated() {}
};
</script>

<style lang="scss">
@import '../assets/scss/pages_scss/statement/statement.scss';
@import '../assets/scss/buttons.scss';
@import '../assets/css/dialog_styles/dialogHeader.css';
@import '../assets/css/dialog_styles/dialogBody.css';
@import '../assets/css/dialog_styles/dialogFooter.css';
</style>
