<template>
    <div>
        <div class="flex justify-between">
          <heading class="mb-6">LFD Tool</heading>
          <button v-if="!loading" class="btn btn-default btn-primary ml-2" style="font-size: 14px; line-height: 2rem; zoom: 0.90" @click="csvExport">
              <span>
                  Export
              </span>

          </button>
        </div>
        <card v-if="!loading" style="zoom: 0.75">
            <modal class="bg-none" v-if="showNotes" >
                <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="min-width: 50vw; padding: 20px 30px">
                    <div class="container">

                        <div class="flex border-b border-40">
                            <div class="w-1/4 px-8 py-4">
                                <label class="inline-block text-80 pt-2 leading-tight">
                                    Shifl Ref#
                                </label>
                            </div>
                            <div class="py-4 px-8 w-3/4">
                                <div class="pt-2">
                                    {{ note.shifl_ref || 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex border-b border-40">
                            <div class="w-1/4 px-8 py-4">
                                <label class="inline-block text-80 pt-2 leading-tight">
                                    MBL#
                                </label>
                            </div>
                            <div class="py-4 px-8 w-3/4">
                                <div class="pt-2">
                                    {{ note.mbl_num || 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex border-b border-40">
                            <div class="w-1/4 px-8 py-4">
                                <label class="inline-block text-80 pt-2 leading-tight">
                                    Container#
                                </label>
                            </div>
                            <div class="py-4 px-8 w-3/4">
                                <div class="pt-2">
                                    {{ note.container_num || 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex border-b border-40">
                            <div class="w-1/4 px-8 py-4">
                                <label class="inline-block text-80 pt-2 leading-tight">
                                    Notes
                                </label>
                            </div>
                            <div class="py-4 px-8 w-3/4">
                                <div class="pt-2">
                                    <textarea rows="5" placeholder="Notes" class="w-full form-control form-input form-input-bordered py-3 h-auto" spellcheck="false" v-model="note.notes"></textarea>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end" class="px-8">
                            <button  class="btn-sm btn-default btn-info ml-2" style="font-size: 14px; line-height: 2rem; background: #e3e8ee;" @click="showNotes = false">
                    <span style="color: red">
                        Cancle
                    </span>
                            </button>
                            <button class="btn-sm btn-default btn-primary ml-2" style="font-size: 14px; line-height: 2rem;"  align="right" @click="saveNotes()">
                    <span>
                      {{ saveText }}
                    </span>
                            </button>
                        </div>

                    </div>
                </div>
            </modal>
            <div class="pt-2 border-b border-50">

                <div class="pt-2 border-b border-50">
                    <div class="flex justify-end pb-2">
                        <div class="flex bg-10 rounded">

                            <!-- //// -->
                            <div>
                                <div class="p-2">
                                    <select dusk="Filter by Account Manager-filter-select" class="block w-full form-control form-select cursor-pointer" v-model="selectedFilter">
                                        <option v-for="option in options" :value="option.type" :key="option.type"> {{ option.name }} </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex bg-10 rounded">

                            <!-- //// -->
                            <div>
                                <div class="p-2">
                                    <select dusk="Filter by Account Manager-filter-select" class="block w-full form-control form-select cursor-pointer" v-model="selectedTrackingSourceFilter" >
                                        <option v-for="option in trackingSourceOptions" :value="option.type" :key="option.type"> {{ option.name }} </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <vue-good-table
                    :columns="columns"
                    :rows="filtered_rows"
                    :pagination-options="{
                    enabled: true,
                    mode: 'records',
                    perPageDropdown: [50, 100, 150],
                    dropdownAllowAll: false
                }"
                    theme="polar-bear"
                    :search-options="{
                     enabled: true,
                     placeholder: 'Search By REF#, MBL#, Container# ',
                   }"
                    :sort-options="{
                   enabled: true,
                 }"
                >

                    <template slot="table-row" slot-scope="props">


                        <div v-if="props.column.field == 'isreviewed'">
                            <div class="inline-flex items-center">

                            <span class="inline-flex">
                                <span v-if="props.row.is_reviewed">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="check-circle" role="presentation" class="fill-current text-success">
                                    <path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z">
                                    </path>
                                  </svg>
                                </span>
                                <span v-else>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="x-circle" role="presentation" class="fill-current text-danger">
                                    <path d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z">
                                    </path>
                                  </svg>
                                </span>
                            </span>

                            </div>
                        </div>


                        <div v-if="props.column.field == 'action'">
                            <div class="inline-flex items-center">

                            <span class="inline-flex">
                                <div @click="goToDetailView(props.row)" class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center has-tooltip" data-testid="items-items-0-view-button" dusk="1-view-button"
                                     data-original-title="null">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 16" aria-labelledby="view" role="presentation" class="fill-current">
                                        <path
                                            d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z">
                                        </path>
                                    </svg>
                                </div>

                                <button  class="btn-sm btn-default btn-info ml-2" style="font-size: 14px; line-height: 2rem; background: #e3e8ee;" @click="addNotes(props.row)">
                                  <span style="color: #444">
                                      Add note
                                  </span>
                                </button>

                                <button v-if="!props.row.is_reviewed" class="btn-sm btn-default btn-primary ml-2" style="font-size: 14px; line-height: 2rem" @click="markAsReviewed(props.row)">
                                    <span>
                                      Mark As Reviewed
                                    </span>
                                </button>

                                <button class="btn-sm btn-default btn-primary ml-2" style="font-size: 14px; line-height: 2rem" @click="notifyDemurrage(props.row.id)">
                                    <span>
                                      Notify
                                    </span>
                                </button>
                            </span>

                            </div>
                        </div>

                        <span v-else>
                        {{props.formattedRow[props.column.field]}}
                    </span>
                    </template>
                </vue-good-table>

            </div>
        </card>

        <card v-else class="flex border-b border-40 p-8">
            <loader class="text-60 text-center" />
        </card>

    </div>
</template>

<script>
import 'vue-good-table/dist/vue-good-table.css'
import {
    VueGoodTable
} from 'vue-good-table'

import axios from 'axios'


export default {
    metaInfo() {
        return {
            title: 'LfdTool',
        }
    },
    // add to component
    components: {
        VueGoodTable,
    },
    data() {
        return {
            columns: [{
                    label: 'Shifl Ref#',
                    field: 'shifl_ref',
                    sortable: false,
                },
                {
                    label: 'Mbl#',
                    field: 'mbl_num',
                    sortable: false,

                },
                {
                    label: 'Container#',
                    field: 'container_num',
                    sortable: false,
                },
                {
                    label: 'Firms Code',
                    field: 'terminal.firms_code',
                    sortable: false,
                },
                {
                    label: 'Notes',
                    field: (row) => {
                        return this.strLimit(row.lfd_notes,30,'...')
                    },
                    sortable: false,
                },
                {
                    label: 'Last Free Day',
                    field: 'pickup_lfd',
                    type: 'date',
                    formatFn: function(value) {
                        return value != null ? new Date(value).toLocaleDateString() : "__"
                    },
                    globalSearchDisabled: true,
                    sortable: true,
                    sortFn: this.sortLfd,
                },
                {
                    label: 'Tracking',
                    field: 'tracking_source',
                    sortable: false,
                },
                {
                    label: 'Reviewed Today',
                    field: 'isreviewed',
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
            loading: false,
            selectedFilter: 'all',
            options: [
                {
                    type: 'all',
                    name: 'All'
                },
                {
                    type: 'reviewed',
                    name: 'Reviewed Containers'
                },
                {
                    type: 'not_reviewed',
                    name: 'Unreviewed Shipments'
                }
            ],
            selectedTrackingSourceFilter: 'all',
            trackingSourceOptions: [
                {
                    type: 'all',
                    name: 'All'
                },
                {
                    type: 'Terminal49',
                    name: 'Terminal49'
                },
                {
                    type: 'Manual',
                    name: 'Manual Tracking'
                }
            ],
            note:{
                notes : '',
                shifl_ref: '',
                container_num: '',
                mbl_num : ''
            },
            showNotes : false,
            saveText: 'Save'

        };
    },
    computed:{
        filtered_rows(){
            if(this.selectedFilter == 'all'){
                if(this.selectedTrackingSourceFilter == 'all') return this.rows
                return this.rows.filter(row => row.tracking_source == this.selectedTrackingSourceFilter)
            }
            return this.rows.filter(row => (this.selectedFilter == 'reviewed' ? row.is_reviewed : !row.is_reviewed) &&
                            (this.selectedTrackingSourceFilter == 'all' ? true : row.tracking_source == this.selectedTrackingSourceFilter)
            )
        }
    },
    mounted() {
        //
    },
    created() {
        this.loadData()
    },
    methods: {
        async loadData() {
            this.loading = true
            await axios.get("/nova-vendor/lfd-tool/shipments")
                .then(res => {
                    console.log(res.data)
                    this.rows = res.data
                    this.loading = false
                }).catch(err => {
                    console.log(err)
                    this.loading = false
                })
        },
        goToDetailView(row) {
            this.$router.push(
                `/resources/shipments/${row.id}?tab=` + (row.tracking_source == 'Manual'? 'manual-tracking-tab' : 't49-tracking')
            );
        },
        sortLfd(x, y, col, rowX, rowY) {
            // x - row1 value for column
            // y - row2 value for column
            // col - column being sorted
            // rowX - row object for row1
            // rowY - row object for row2
            // Date.parse(b) - Date.parse(a)
            // return (x < y ? -1 : (x > y ? 1 : 0));3
            return Date.parse(x) - Date.parse(y);
        },
        async markAsReviewed(row){
            await axios.post("/nova-vendor/lfd-tool/shipments/mark-as-reviewed/"+row.mbl_num+'/'+row.container_num, {
                    shifl_ref : row.shifl_ref,
                    tracking_source : row.tracking_source
                })
                .then(res => {
                    this.loadData();
                    Nova.success(
                        "Container#"+container_num+" has been marked as Reviewed!"
                    )
                }).catch(err => {
                    console.log(err)

                })
        },
        addNotes(row){
            this.note ={
                shifl_ref : row.shifl_ref,
                container_num : row.container_num,
                mbl_num : row.mbl_num,
                notes : row.lfd_notes,
                tracking_source: row.tracking_source
            }

            this.showNotes = true
        },
        async saveNotes(){
            this.saveText = 'Saving...'
            await axios.post("/nova-vendor/lfd-tool/shipments/add-notes",this.note)
                .then(res => {
                    this.loadData();
                    Nova.success(
                        "Notes Saved!"
                    )
                    this.showNotes = false
                    this.saveText = 'Save'
                }).catch(err => {
                    console.log(err)
                    this.showNotes = false
                    this.saveText = 'Save'
                })

        },
        async notifyDemurrage(id){
            this.loading = true
            await axios.post("/nova-vendor/lfd-tool/shipments/notify-demurrage/"+id)
                .then(res => {
                    this.loading = false

                    Nova.success(
                        res.data.message
                    )
                }).catch(err => {
                    console.log(err)
                    this.showNotes = false
                    this.saveText = 'Save'
                })

        },
        strLimit(text, count, suffix){
            return text.slice(0, count) + (text.length > count ? suffix : "");
        },
        csvExport() {
          let rows = [
              ['Shifl Ref' ,'MBL', 'Container', 'Last free day'],
              ...this.rows.map(row => {
                  return [
                       row.shifl_ref,
                       row.mbl_num,
                       row.container_num,
                       row.pickup_lfd,
                  ]
              })
            ]

          let csvContent = "data:text/csv;charset=utf-8,"
              + rows.map(e => e.join(",")).join("\n");

          let encodedUri = encodeURI(csvContent);
          let link = document.createElement("a");
          link.setAttribute("href", encodedUri);
          let today = new Date();
          let date = today.getFullYear()+'_'+(today.getMonth()+1)+'_'+today.getDate();
          link.setAttribute("download", "lfd_report_"+date+".csv");
          document.body.appendChild(link);
          link.click();
      }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
