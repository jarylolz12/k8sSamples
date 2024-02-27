<template>
    <div>
        <heading class="mb-6">Ach Daily Statements - Statement Number: {{this.$route.params.statement_no}}</heading>

        <div class="bg-white p-2" v-if="this.statements">
            <div class="table table-responsive table-striped w-full">
                <table class="table table-striped" id="datatable">
                    <thead>
                        <tr>
                            <td colspan="5">Statement For: {{ this.statements[0] && this.statements[0].company_name }}</td>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th scope="col" align="left">Date</th>
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
                                    <td>{{data.entry_no}}</td>
                                    <td>{{data.reference_no}}</td>
                                    <td align="right" style="padding-right:20px;font-weight: 400;">{{data.amount | currency_format}}</td>
                                </tr>
                            </template>
                        </template>
                        <tr v-else>
                            <td colspan="5">No record found.</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="font-weight: 400;">Total:</td>
                            <td align="right" style="padding-right:20px;font-weight: 400;">{{ this.total_amount }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
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

export default {

    props: [],
    data() {
        return {
            searchText: "",
            axiosSource: {},
            isLoading: false,
            statements: null,
        };
    },
    mounted() {},
    beforeDestroy() {},
    watch: {},
    methods: {
        fetchData() {
            axios.get('/nova-vendor/ach-report/customer-statements/statement-daily/'+this.$route.params.statement_no).then(res => {
                this.statements = res.data

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
