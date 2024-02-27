<template>
    <div>
        <heading class="mb-6">Ach Report</heading>
        <div class="bg-white p-2" v-if="this.statements">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td colspan="3">Statement For: {{ this.statements[0] && this.statements[0].company_name }}</td>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th scope="col" align="left">Date</th>
                        <th scope="col" align="left">Entry Number</th>
                        <th scope="col" align="left">Reference Number</th>
                        <th scope="col" align="right" style="padding-right:20px">Total Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="this.statements && this.statements.length > 0">
                        <template v-for="data in statements">
                            <tr @click="toggle(data.id)" :class="{ opened: opened.includes(data.id) }" :key="data.id+'dt'">
                                <td colspan="3" style="font-weight: bold;">Statement Date: {{data.pres_date}}  |  Statement No: {{data.statement_no}}</td>
                                <!-- <td>{{data.entry_no}}</td> -->
                                <!-- <td>{{data.reference_no}}</td> -->
                                <td align="right" style="padding-right:20px;font-weight: 400;">{{data.amount}}</td>
                                <td>
                                    <button type="button" class="btn btn-outlined btn-theme btn-sm" @click="generateReport(data.statement_no)">Generate Report</button>
                                </td>
                            </tr>
                            <template v-if="opened.includes(data.id)">
                                <template v-for="ds in daily_statements">
                                    <tr v-if="data.daily_statement_no == ds.statement_no" :key="ds.id+'ds'">
                                        <td>{{ds.statement_date}}</td>
                                        <td>{{ds.entry_no}}</td>
                                        <td>{{ds.reference_no}}</td>
                                        <td align="right" style="padding-right:20px">{{ds.amount}}</td>
                                        <td></td>
                                    </tr>
                                </template>
                            </template>
                        </template>
                    </template>
                    <tr v-else>
                        <td colspan="4">No record found.</td>
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
</template>

<script>

export default {
    metaInfo() {
        return { }
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
// import $ from 'jquery';

export default {

    props: [],
    data() {
        return {
            searchText: "",
            axiosSource: {},
            isLoading: false,
            statements: [],
            opened: [],
            daily_statements: [],
        };
    },
    mounted() {},
    beforeDestroy() {},
    watch: {},
    methods: {
        fetchData() {
            axios.get('/nova-vendor/ach-report/customer-statements/'+this.$route.params.customer_id).then(res => {
                this.statements = res.data
                if(this.statements.length==0) {
                    this.$router.push({name:'ach-report'})
                }
            })
        },
        fetchDailyStatements() {
            axios.get('/nova-vendor/ach-report/customer-statements/daily/'+this.$route.params.customer_id).then(res => {
                this.daily_statements = res.data
                console.log(res.data)
            })
        },
        toggle(id) {
            const index = this.opened.indexOf(id);
            if(index > -1) {
                this.opened.splice(index, 1)
            } else {
                this.opened.push(id)
            }
        },
        generateReport(statement_no) {
            window.open("/nova-vendor/ach-report/report/"+this.$route.params.customer_id+"/"+statement_no)
        }
    },
    created() {
        this.fetchData();
        this.fetchDailyStatements();
    },
    computed: {
        total_amount: function(){
            let sum = 0;
            this.statements.forEach(function(item) {
                sum += parseFloat(item.amount);
            });

            return sum.toLocaleString('en-US',{ style: "currency", currency: "USD" });
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

.btn {
	letter-spacing: 1px;
	text-decoration: none;
	background: none;
    -moz-user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 0;
    cursor: pointer;
    display: inline-block;
    margin-bottom: 0;
    vertical-align: middle;
    white-space: nowrap;
	font-size:14px;
	line-height:20px;
	font-weight:700;
	text-transform:uppercase;
	border: 3px solid;
	padding:8px 20px;
}

.btn-outlined {
    border-radius: 0;
    -webkit-transition: all 0.3s;
       -moz-transition: all 0.3s;
            transition: all 0.3s;
}

.btn-outlined.btn-theme {
    background: none;
    color: #252d37;
	border-color: #252d37;
}

.btn-outlined.btn-theme:hover,
.btn-outlined.btn-theme:active {
    color: #FFF;
    background: #252d37;
    border-color: #252d37;
}

.btn-sm{
	font-size:12px;
	line-height:16px;
	border: 2px solid;
	padding:8px 15px;
    height: auto;
}


</style>
