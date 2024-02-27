<template>
    <div>
        <heading class="mb-6">Ach Daily Statements</heading>

        <div class="bg-white p-2" v-if="this.rows">
            <!-- <div class="table table-responsive table-striped w-full">
                <table class="table table-striped" id="datatable">
                    <thead>
                        <tr>
                            <td colspan="5">Statement For: {{ this.statements[0] && this.statements[0].company_name }}</td>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th scope="col" align="left">Date</th>
                            <th scope="col" align="left">SOA Number</th>
                            <th scope="col" align="left">Entry Number</th>
                            <th scope="col" align="left">Reference Number</th>
                            <th scope="col" align="right" style="padding-right:20px">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="this.statements && this.statements.length > 0">
                            <template v-for="data in statements">
                                <tr :key="data.id">
                                    <td>{{data.statement_date}}</td>
                                    <td>
                                        <a :href="'daily-statement/'+data.statement_no" class="btn btn-outlined btn-theme btn-sm">{{data.statement_no}}</a>
                                    </td>
                                    <td>{{data.entry_no}}</td>
                                    <td>{{data.reference_no}}</td>
                                    <td align="right" style="padding-right:20px;font-weight: 400;">{{data.amount | currency_format }}</td>
                                </tr>
                            </template>
                        </template>
                        <tr v-else>
                            <td colspan="5">No record found.</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="font-weight: 400;">Total:</td>
                            <td align="right" style="padding-right:20px;font-weight: 400;">{{ this.total_amount }}</td>
                        </tr>
                    </tfoot>
                </table>
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
                                <div @click="goToDetailView(props.row.id)" class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center has-tooltip" data-testid="items-items-0-view-button" dusk="1-view-button"
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
            </vue-good-table>
        </div>
    </div>
</template>

<script>
export default {
    metaInfo() {
        return {
          title: 'Ach Daily Statement',
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
// import "datatables.net-dt/js/dataTables.dataTables"
// import "datatables.net-dt/css/jquery.dataTables.min.css"
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
            axios.get('/nova-vendor/ach-report/customer-statements/statement-daily/customer/'+this.$route.params.customer_id).then(res => {
                this.rows = res.data
                // this.statements = res.data

                // $('#datatable').DataTable({
                //     destroy: true,
                //     data: this.statements,
                //     columns: [
                //         // { "title": "customer name", "data": function(x) {
                //         //         return `<a class="no-underline text-black" href="/administrator/ach-report/customer/`+x.id+`">`+x.company_name+`</a>`;
                //         //     }
                //         // },
                //         { "title" : "statement date", "data": "statement_date"},
                //         { "title" : "statement number", "data": "statement_no"},
                //         { "title" : "entry number", "data": "entry_no"},
                //         { "title" : "reference number", "data": "reference_no"},
                //         { "title" : "amount", "data": "amount"},
                //     ],
                // });

            })
        },
    },
    created() {
        this.fetchData();
    },
    computed: {
        total_amount: function(){
            let sum = 0;
            this.statements.forEach(function(item) {
                sum += parseFloat(item.amount);
            });

            return sum.toLocaleString('en-US',{ style: "currency", currency: "USD" });
        }
    },
    filters: {
        currency_format(value) {
            console.log(value)
            return value.toLocaleString('en-US',{ style: "currency", currency: "USD" });
        }
    }
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
