<template>
    <div class="statement-content-wrapper">
        <v-data-table
            :headers="headers"
            mobile-breakpoint="769"
            :items="statements"
            class="statements-table elevation-1"
            v-bind:class="{
                'no-data-table': (statements !== null && statements.length !== 'undefined' && statements.length === 0),
                'no-current-pagination' : statements.length <= 35,
            }"
			:search="search"
			:page.sync="page"
            :items-per-page="itemsPerPage"
			@page-count="pageCount = $event"
            item-key="statement_no"
			hide-default-footer
            fixed-header
            show-expand
            :single-expand="true">
            
            <!-- :class="statements !== null && statements.length !== 'undefined' && statements.length !== 0 ? '' : 'no-data-table'" -->

            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>ACH Statements</v-toolbar-title>
					
                    <v-spacer></v-spacer>

                    <!-- <v-btn color="primary" class="btn-white mr-2" @click="showReportSchedule"
                        v-if="statements !== null && statements.length !== 'undefined' && statements.length !== 0">
                        <img src="../../../assets/icons/settings.svg" class="mr-1" alt=""/>
                        Report Schedule
                    </v-btn> -->

					<!-- <Search 
						placeholder="Search Statement"
						className="search custom-search"
						:inputData.sync="search" 
                        v-if="statements !== null && statements.length !== 'undefined' && statements.length !== 0"/> -->

                </v-toolbar>
            </template>

            <!-- <template v-slot:[`item.actions`]="{ item }">				
                <template v-if="item.statement_no !== null">
                    <v-btn color="primary" class="btn-white" @click="downloadStatement(item)">
                        <img src="../../../assets/icons/download.svg" class="mr-1" alt="">
                            Download
                    </v-btn>    
                </template>
                
                <template v-else>
                    <v-btn color="primary" class="btn-white" disabled>
                        <img src="../../../assets/icons/download.svg" class="mr-1" alt="">
                            Download
                    </v-btn>
                </template>
            </template> -->

            <template v-slot:[`item.month_year`]="{ item }">				
				{{item.month_year | current}}
            </template>

            <template v-slot:[`item.statement_no`]="{ item }">				
				{{item.statement_no || '--'}}
            </template>

            <template v-slot:[`item.process_date`]="{ item }">				
				{{item.process_date | format_date}}
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

                <div class="no-data-wrapper" 
                    v-if="!getAchStatementsLoading && statements !== null && statements.length !== 'undefined' && statements.length == 0">
                    <div class="no-data-heading">
                        <img src="../../../assets/icons/statement.svg" width="40px" height="42px" alt="">

                        <h3> Sign Up for ACH </h3>
                        <p>
                            Please contact Shifl representative to sign up  <br>
                            for ACH with customs.
                        </p>                        
                    </div>
                </div>
            </template>

            <template v-slot:expanded-item="{ headers, item }">
                <td colspan="2"></td>
                <td :colspan="headers.length-2">
                    <v-data-table 
                        :headers="dailyStatementHeaders"
                        mobile-breakpoint="769"
                        :items="item.daily_statements"
                        class="statements-table expanded-item-statements elevation-1"
                        :class="item.daily_statements !== null && item.daily_statements.length !== 'undefined' && item.daily_statements.length !== 0 ? '' : 'no-data-table child-table'"
                        hide-default-footer
                        :items-per-page="300"
                        fixed-header
                        show-expand
                        :single-expand="true"
                        item-key="item.statement_no"
                        v-if="item.statement_no">

                        <template v-slot:[`item.statement_date`]="{ item }">				
                            {{item.statement_date | format_date}}
                        </template>

                        <template v-slot:expanded-item="{ headers, item }">
                            <td></td>
                            <td :colspan="headers.length-1">
                                <v-data-table 
                                    :headers="dailyDetailsHeaders"
                                    mobile-breakpoint="769"
                                    :items="item.details"
                                    class="statements-table-inner-content elevation-1"
                                    :class="item.details !== null && item.details.length !== 'undefined' && item.details.length !== 0 ? '' : 'no-data-table'"
                                    hide-default-footer
                                    fixed-header>

                                    <template v-slot:[`item.reference_no`]="{ item }">
                                        <span class="ref-no" >{{item.reference_no}}</span>                                        
                                    </template> 
                                </v-data-table>
                            </td>
                        </template>                        
                    </v-data-table>

                    <v-data-table 
                        :headers="dailyDetailsHeaders"
                        mobile-breakpoint="769"
                        :items="item.daily_statements"
                        class="statements-table expanded-item-statements elevation-1"
                        :class="item.daily_statements !== null && item.daily_statements.length !== 'undefined' && item.daily_statements.length !== 0 ? '' : 'no-data-table child-table'"
                        hide-default-footer
                        :items-per-page="300"
                        fixed-header
                        v-else>

                        <template v-slot:[`item.reference_no`]="{ item }">
                            <a style="text-decoration: none!important;" href="">{{item.reference_no}}</a>                                        
                        </template> 
                    </v-data-table>
                </td>
            </template>
        </v-data-table>

		<Pagination 
            v-if="statements.length > 35"
			:pageData.sync="page"
			:lengthData.sync="pageCount"
			:isMobile="isMobile"
		/>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
// import Search from '../../../components/Search.vue'
import Pagination from '../../../components/Pagination.vue'
import moment from 'moment'

export default {
    name: "StatementDesktopTable",
    props: ['items', 'isMobile'],
	components: {
		// Search,
		Pagination,
	},
    data: () => ({
		page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        headers: [
            {
                text: "Month",
                align: "start",
                sortable: false,
                value: "month_year",
                width: "15%", 
                fixed: false
            },
            {
                text: "Monthly Statement",
				align: "start",
                value: "statement_no",
                sortable: false,
                width: "25%", 
                fixed: false
            },
            {
                text: "Process Date",
				align: "start",
                value: "process_date",
                sortable: true,
                width: "40%", 
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
			// {
            //     text: "",
			// 	align: "center",
            //     value: "actions",
            //     sortable: false,
            //     width: "25%", 
            //     fixed: true
            // },
        ],
		search: '',
		expanded: [],
        dailyStatementHeaders: [
            {
                text: "Daily Statement",
                align: "start",
                sortable: false,
                value: "statement_no",
                width: "20%", 
                fixed: false
            },
            {
                text: "Process Date",
				align: "start",
                value: "statement_date",
                sortable: false,
                width: "40%", 
                fixed: true
            },
            {
                text: "Amount",
				align: "start",
                value: "amount",
                sortable: false,
                width: "40%", 
                fixed: true
            },
        ],
        dailyDetailsHeaders: [
            {
                text: "Entry No",
                align: "start",
                sortable: false,
                value: "entry_no",
                width: "20%", 
                fixed: true
            },
            {
                text: "Reference No",
                align: "start",
                sortable: false,
                value: "reference_no",
                width: "20%", 
                fixed: true
            },
            {
                text: "POs",
                align: "start",
                sortable: false,
                value: "shipment.po_num",
                width: "20%", 
                fixed: true
            },
            {
                text: "Amount",
				align: "start",
                value: "amount",
                sortable: false,
                width: "40%", 
                fixed: true
            },
        ],
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
        showReportSchedule() {
            this.$parent.showReportSchedule()
        },
    },
    filters: {
        format_date(value) {
            if(!value) {
                return '--';
            }
            return moment(value).format('ll');
        },
        current(value) {
            if(!value) {
                return moment().format('MMM')+' '+moment().get('year');
            }

            return value
        }
    },
    async mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "statements");  
    },
    updated() {}
};
</script>
