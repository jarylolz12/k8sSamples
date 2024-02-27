<template>
    <div class="statement-content-wrapper">
        <v-data-table
            :headers="headers"
            :items="statements"
            mobile-breakpoint="769"
            class="statements-table-mobile elevation-1"
			:search="search"
			:page.sync="page"
            :items-per-page="itemsPerPage"
			@page-count="pageCount = $event"
			hide-default-footer>

            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Statements</v-toolbar-title>
					
                    <v-spacer></v-spacer>

                </v-toolbar>					

				<div class="search-component">
					<Search 
						placeholder="Search Statement"
						className="search custom-search"
						:inputData.sync="search" />
				</div>
            </template>	

            <template v-slot:no-data>
                <div class="no-data-preloader mt-4" v-if="getAchStatementsLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate
                        v-if="getAchStatementsLoading">
                    </v-progress-circular>
                </div>

                <div class="no-data-wrapper" v-if="!getAchStatementsLoading && statements !== null && statements.length !== 'undefined' && statements.length == 0">
                    <div class="no-data-heading">
                        <img src="../../../assets/icons/statement.svg" width="40px" height="42px" alt="">

                        <h3> Sign Up for ACH </h3>
                        <p>
                            Please contact Shifl representative to sign up  <br>
                            for ACH with customs.
                        </p>

                        <!-- <div class="mt-4">
                            <v-btn color="primary" class="btn-blue add-statement" @click.stop="addStatement">
                                Add Statement
                            </v-btn>
                        </div> -->
                    </div>
                </div>
            </template>
        </v-data-table>

		<Pagination 
            v-if="statements.length !== 0"
			:pageData.sync="page"
			:lengthData.sync="pageCount"
			:isMobile="isMobile"
		/>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import Search from '../../../components/Search.vue'
import Pagination from '../../../components/Pagination.vue'

export default {
    name: "StatementMobileTable",
    props: ['items'],
	components: {
		Search,
		Pagination,
	},
    data: () => ({
		page: 1,
        pageCount: 0,
        itemsPerPage: 15,
        headers: [
            {
                text: "Month",
                align: "start",
                sortable: false,
                value: "month_year",
                width: "25%", 
                fixed: true
            },
            {
                text: "Monthly Statement",
				align: "start",
                value: "statement_no",
                sortable: false,
                width: "20%", 
                fixed: true
            },
            {
                text: "Process Date",
				align: "start",
                value: "process_date",
                sortable: true,
                width: "25%", 
                fixed: true
            },
			{
                text: "Amount",
				align: "start",
                value: "amount",
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
		search: '',
		expanded: [],
        dailyStatementHeaders: [
            {
                text: "Daily Statement",
                align: "start",
                sortable: false,
                value: "statement_no",
                width: "25%", 
                fixed: true
            },
            {
                text: "Process Date",
				align: "start",
                value: "statement_date",
                sortable: false,
                width: "25%", 
                fixed: true
            },
            {
                text: "Amount",
				align: "start",
                value: "amount",
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
        dailyDetailsHeaders: [
            {
                text: "Entry No",
                align: "start",
                sortable: false,
                value: "entry_no",
                width: "25%", 
                fixed: true
            },
            {
                text: "Reference No",
                align: "start",
                sortable: false,
                value: "reference_no",
                width: "25%", 
                fixed: true
            },
            {
                text: "POs",
                align: "start",
                sortable: false,
                value: "reference_no",
                width: "25%", 
                fixed: true
            },
            {
                text: "Amount",
				align: "start",
                value: "amount",
                sortable: false,
                width: "20%", 
                fixed: true
            },
        ],
		isMobile: false
    }),
    computed: {
        ...mapGetters({
            getAchStatements: 'statements/getAchStatements',
            getUser: 'getUser',
            getAchStatementsLoading: 'statements/getAchStatementsLoading'
        }),
        formTitle() {
            return this.editedIndex === -1 ? "Add Statement" : "Edit Statement";
        },
        statements: {
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
            fetchStatements: 'statements/fetchStatements'
        }),
    },
    async mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "statements");  
    },
    updated() {}
};
</script>
