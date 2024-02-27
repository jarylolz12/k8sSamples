<template>
    <div>
        <heading class="mb-6">Ach Statements</heading>
        <div class="bg-white p-2">
            <h4>Upload Report</h4>
            <div class="form-group mt-3 mb-4">
                <label for="type">Statement Type:</label>
                <select name="type" v-model="type">
                    <option value="">Select Statement Type</option>
                    <option value="daily">Daily Statement</option>
                    <option value="monthly">Monthly Statement</option>
                </select>
                <input type="file" @change="processFile($event)" class="form-control-file" id="my_report">
                <button type="button" class="btn btn-outlined btn-theme btn-sm" @click="submit">Upload</button>
            </div>
            <div class="table table-responsive table-striped w-full" style="margin-top:1rem;">
                <label for="type">Display Statement</label>
                <select name="display" v-model="display" @change="fetchData">
                    <option value="daily">Daily Statement</option>
                    <option value="monthly">Monthly Statement</option>
                </select>
                <!-- <table class="table" id="datatable" /> -->
            </div>
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
                    placeholder: 'Search By Company Name ',
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
          title: 'AchReport',
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
            showModal: true,
            file_upload: null,
            type: "",
            display: "monthly",
            columns: [
                {
                    label: 'Company Name',
                    field: 'company_name',
                    sortable: false,
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
        submit() {
            if(this.file_upload) {
                let formData = new FormData();
                formData.append('file', this.file_upload);

                let config = { headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }

                let url = "";
                if(this.type == "daily") {
                    url = '/nova-vendor/ach-report/preliminary-statement';
                } else if (this.type == "monthly") {
                    url = '/nova-vendor/ach-report/monthly-statement';
                } else {
                    Nova.error("Select statement type.")
                    return;
                }

                axios.post(url, formData, config ).then(res => {
                    if(res.data.status == 'error') {
                        Nova.error(res.data.message)
                    } else {
                        Nova.success('Report uploaded successfully!');
                        $("#my_report").val('')
                        this.file_upload = null
                        this.fetchData()
                    }
                })
            } else {
                Nova.error("Select file.")
            }
        },
        processFile(event) {
            this.file_upload = event.target.files[0]
        },
        fetchData() {

            let url = '';
            // let columns = [];
            if(this.display == 'monthly') {
                url = '/nova-vendor/ach-report/customer-statements';
            //     columns = [
            //                 { "title": "customer name", "data": function(x) {
            //                     return `<a class="no-underline text-black" href="/administrator/ach-report/customer/`+x.id+`/monthly-statement">`+x.company_name+`</a>`;
            //                 }
            //             },
            //         ]
            } else if(this.display == 'daily') {
                url = '/nova-vendor/ach-report/customer-statements-daily';
            //     columns = [
            //                 { "title": "customer name", "data": function(x) {
            //                     return `<a class="no-underline text-black" href="/administrator/ach-report/customer/`+x.id+`/daily-statement">`+x.company_name+`</a>`;
            //                 }
            //             },
            //         ]
            }

            axios.get(url).then(res => {
                this.rows = res.data
                // this.statements = res.data

                // $('#datatable').DataTable({
                //     destroy: true,
                //     data: this.statements,
                //     columns: columns
                // });
            })
        },
        goToDetailView(id) {
            if(this.display == 'monthly') {
                window.location.href = "/administrator/ach-report/customer/"+id+"/monthly-statement"
            } else if(this.display == 'daily') {
                window.location.href = "/administrator/ach-report/customer/"+id+"/daily-statement"
            }
        },
    },
    created() {
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

.file-upload .input-wrapper {
    height: 50px!important;
}

.file-upload-icon {
    display: none!important;
}


</style>
