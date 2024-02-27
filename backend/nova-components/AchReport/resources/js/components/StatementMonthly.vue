<template>
    <div>
        <heading class="mb-6">Ach Monthly Statements</heading>
        <div class="bg-white p-2">
            <!-- <div class="table table-responsive table-striped w-full" style="margin-top:1rem;">
                <table class="table" id="datatable" />
            </div> -->
            <vue-good-table
                :columns="columns"
                :rows="rows"
                :pagination-options="{
                    enabled: true,
                    mode: 'records'
                }"
                theme="polar-bear"
                :search-options="{
                    enabled: true,
                    placeholder: 'Search By Statement Number',
                }">
                <template slot="table-row" slot-scope="props">

                    <div v-if="props.column.field == 'action'">
                        <div class="inline-flex items-center">

                            <span class="inline-flex">
                                <div @click="goToDetailView(props.row.statement_no)" class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center has-tooltip" data-testid="items-items-0-view-button" dusk="1-view-button"
                                    data-original-title="null">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 16" aria-labelledby="view" role="presentation" class="fill-current">
                                        <path
                                            d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z">
                                        </path>
                                    </svg>
                                </div>
                            </span>



                            <!---->
                            <!---->
                        </div>
                    </div>

                    <span v-else>
                        {{props.formattedRow[props.column.field]}}
                    </span>
                </template>

                <div slot="table-actions-bottom">
                    This will show up on the bottom of the table.
                </div>
            </vue-good-table>
        </div>
    </div>
</template>

<script>
export default {
    metaInfo() {
        return {
          title: 'Ach Monthly Statement',
        }
    },
    mounted() {
        //
    },
}
</script>

<script>

import axios from 'axios';
import 'jquery/dist/jquery.min.js';
import "datatables.net-dt/js/dataTables.dataTables"
import "datatables.net-dt/css/jquery.dataTables.min.css"
import $ from 'jquery';
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table'

export default {
    components: {
        VueGoodTable,
    },
    props: [],
    data() {
        return {
            searchText: "",
            axiosSource: {},
            isLoading: false,
            statements: null,
            columns: [
                {
                    label: 'Date',
                    field: 'statement_date',
                    sortable: true,
                    globalSearchDisabled: true,
                },
                {
                    label: 'Statement Number',
                    field: 'statement_no',
                    sortable: false,
                },
                {
                    label: 'Amount',
                    field: 'amount',
                    sortable: false,
                    globalSearchDisabled: true,
                },
                {
                    label: 'Action',
                    field: 'action',
                    sortable: false,
                    globalSearchDisabled: true,

                },
            ],
            rows: [],
        };
    },
    mounted() {},
    beforeDestroy() {},
    watch: {},
    methods: {
        fetchData() {
            axios.get('/nova-vendor/ach-report/customer-statements/'+this.$route.params.customer_id+'/monthly').then(res => {
            this.rows = res.data
            // this.statements = res.data

            //     $('#datatable').DataTable({
            //         destroy: true,
            //         data: this.statements,
            //         columns: [
            //             // { "title": "customer name", "data": function(x) {
            //             //         return `<a class="no-underline text-black" href="/administrator/ach-report/customer/`+x.id+`">`+x.company_name+`</a>`;
            //             //     }
            //             // },
            //             { "title" : "statement date", "data": "statement_date"},
            //             { "title" : "statement number", "data": "statement_no"},
            //             { "title" : "amount", "data": "amount"},
            //             { "title" : "action", "data": function(o) {
            //                 return `<a href="monthly-statement/`+o.statement_no+`" class="btn btn-outlined btn-theme btn-sm">View</a>`;
            //             }},
            //         ]
            //     });
            })
        },
        goToDetailView(statement_no) {
            this.$router.push({path:'/ach-report/monthly-statement/'+statement_no})
        },
    },
    created() {
        console.log('test');
        this.fetchData();
    },
};
</script>

<style scoped>
table {
    width: 100%;
    border: 1px solid #e1ecf0;
    margin-top: 15px;
}

th {
    padding: 20px;
}

table tbody td {
    padding: 20px;
    padding-top: 5px;
    padding-bottom: 5px;
}
</style>
