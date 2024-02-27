<template>
<div>
    <heading class="mb-6"> Manual Tracking </heading>
    <card v-if="!loading" style="zoom: 0.75">

        <div class="pt-2 border-b border-50 px-2">
            <div class="flex justify-end pb-2">

                <div class="flex bg-10 rounded">

                    <!-- //// -->
                    <div >
                        <div class="p-2 pr-4 cursor-pointer" @click="clearFilters">
                            <img style="height: 35px;" src="data:image/png;base64,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">
                        </div>
                    </div>
                </div>

                <div class="flex bg-10 rounded">

                    <!-- //// -->
                    <div >
                        <div class="p-2">
                            <multiselect
                              class="cursor-pointer"
                              v-model="selectedExternalInternalFilter"
                              :options="optionsExternalInternalFilter"
                              :multiple="false"
                              :searchable="false"
                              placeholder="External/Internal Filter"
                              label="name"
                              track-by="name"
                              select-label="Click to select"
                              deselect-label="Click to deselect"
                              style="border: 1px solid #ddd; border-radius: 5px;min-width: 225px;"
                              @input="fetchShipment">
                            </multiselect>
                        </div>
                    </div>
                </div>

                <div class="flex bg-10 rounded">

                    <!-- //// -->
                    <div >
                        <div class="p-2">
                            <multiselect
                              class="cursor-pointer"
                              v-model="selectedOfficeFromFilter"
                              :options="optionsOfficeFromFilter"
                              :multiple="false"
                              :searchable="false"
                              placeholder="No Office From selected"
                              label="name"
                              track-by="name"
                              select-label="Click to select"
                              deselect-label="Click to deselect"
                              style="border: 1px solid #ddd; border-radius: 5px;min-width: 225px;"
                              @input="fetchShipment">
                            </multiselect>
                        </div>
                    </div>
                </div>

                <div class="flex bg-10 rounded">

                    <!-- //// -->
                    <div >
                        <div class="p-2">
                            <multiselect
                              class="cursor-pointer"
                              v-model="selectedMblFilter"
                              :options="optionsMblFilter"
                              :multiple="false"
                              :searchable="false"
                              placeholder="MBL Filter"
                              label="name"
                              track-by="name"
                              select-label="Click to select"
                              deselect-label="Click to deselect"
                              style="border: 1px solid #ddd; border-radius: 5px;min-width: 225px;"
                              @input="fetchShipment">
                            </multiselect>
                        </div>
                    </div>
                </div>

                <div class="flex bg-10 rounded">

                    <!-- //// -->
                    <div >
                        <div class="p-2">
                            <multiselect
                              class="cursor-pointer"
                              v-model="selectedModeFilter"
                              :options="optionsModeFilter"
                              :multiple="false"
                              :searchable="false"
                              placeholder="No Mode Selected"
                              label="name"
                              track-by="name"
                              select-label="Click to select"
                              deselect-label="Click to deselect"
                              style="border: 1px solid #ddd; border-radius: 5px;min-width: 225px;"
                              @input="fetchShipment">
                            </multiselect>
                        </div>
                    </div>
                </div>

                <div class="flex bg-10 rounded">

                    <!-- //// -->
                    <div >
                        <div class="p-2">
                            <multiselect
                              class="cursor-pointer"
                              v-model="stf"
                              :options="optionsTypeFilter"
                              :multiple="true"
                              :searchable="false"
                              placeholder="No Type Selected"
                              label="name"
                              track-by="name"
                              select-label="Click to select"
                              deselect-label="Click to deselect"
                              style="border: 1px solid #ddd; border-radius: 5px;min-width: 225px;"
                              @input="fetchShipment">
                                <template slot="selection" slot-scope="{ values, search, isOpen }" v-if="stf.length > 0"><span class="multiselect__single" > {{ stf.length }} Type Selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                </div>

                <div class="flex bg-10 rounded">

                    <!-- //// -->
                    <div >
                        <div class="p-2">
                            <multiselect
                              class="cursor-pointer"
                              v-model="selectedFilter"
                              :options="options"
                              :multiple="false"
                              :searchable="false"
                              :allow-empty="false"
                              placeholder="External/Internal Filter"
                              label="name"
                              track-by="name"
                              select-label=""
                              deselect-label=""
                              style="border: 1px solid #ddd; border-radius: 5px;min-width: 225px;"
                              @input="fetchShipment">
                            </multiselect>
                        </div>
                    </div>
                </div>

            </div>

            <vue-good-table :columns="columns" :rows="rows"
              :pagination-options="{
                  enabled: true,
                  mode: 'records',
                  perPageDropdown: [50, 100, 150],
                  dropdownAllowAll: false
              }"
              @on-page-change="onPageChange"
              @on-per-page-change="onPageChange"
              @on-sort-change="onSortChange" theme="polar-bear"
              :search-options="{
                   enabled: true,
                   searchFn: handleSearch,
                   placeholder: 'Search Shipments',
              }">
                <template slot="table-row" slot-scope="props">

                    <div v-if="props.column.field == 'shifl_ref_field'" >
                        <small style="color:red" v-if="props.row.isInDemurrage"> In Demurrage <br> </small>
                        <span> {{ props.row.shifl_ref }} <br> <span :class="props.row.is_tracking_shipment ? 'text-dark-yellow' : 'text-dark-blue'"> {{ props.row.is_tracking_shipment ? 'External' : 'Internal' }} </span> </span>
                    </div>
                    <div v-if="props.column.field == 'mbl_num_booking_number'" >
                      <div> {{ props.row.mbl_num ? props.row.mbl_num : '__' }}</div>
                      <div class="pt-1"> {{ props.row.booking_number ? props.row.booking_number : '__' }} </div>
                    </div>
                    <div v-if="props.column.field == 'customer_link'" >
                        <div v-if="props.row.customer" class="cursor-pointer dim inline-block text-primary font-bold" @click="goToCustomerView(props.row.customer.id)"> {{ props.row.customer.company_name }} </div>
                        <div v-else> -- </div>
                    </div>
                    <div v-if="props.column.field == 'mode_label'" >
                      {{ getMode(props.row.schedules_group_bookings) }}
                    </div>
                    <div v-if="props.column.field == 'container_numbers'" v-html="getContainers(props.row.containers_group_bookings)" >
                      <!-- // -->
                    </div>
                    <div v-if="props.column.field == 'firms_code'"  >
                      <!-- // -->
                       {{ getFirmsCode(props.row.terminal_id) }}
                    </div>

                    <div v-if="props.column.field == 'action'">
                        <div class="inline-flex items-center">
                            <!----> <span class="inline-flex">
                                <div @click="goToDetailView(props.row.id)" class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center has-tooltip" data-testid="items-items-0-view-button" dusk="1-view-button"
                                  data-original-title="null">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 16" aria-labelledby="view" role="presentation" class="fill-current">
                                        <path
                                          d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z">
                                        </path>
                                    </svg>
                                </div>
                            </span>

                            <span class="inline-flex">
                                <div @click="goToEditView(props.row.id)" class="inline-flex cursor-pointer text-70 hover:text-primary mr-3 has-tooltip" dusk="1-edit-button" data-original-title="null">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current">
                                        <path
                                          d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z">
                                        </path>
                                    </svg>
                                </div>
                            </span>
                            <span class="inline-flex">
                                <button class="btn-sm btn-default btn-primary inline-flex items-center relative " v-if=" selectedFilter.type == 'untracked' || ( selectedFilter.type == 'all' && shouldShowTrackedButton(props.row.tracked_up_to))" @click="moveToTracked(props.row.id)">
                                    Tracked
                                </button>
                            </span>


                            <!---->
                            <!---->
                        </div>
                    </div>

                    <span v-if="props.column.field != 'shifl_ref_field' && props.column.field != 'mbl_num_booking_number' && props.column.field != 'customer_link' && props.column.field != 'mode_label' && props.column.field != 'container_numbers' && props.column.field != 'action'">
                        {{ props.formattedRow[props.column.field] }}
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
import Multiselect from 'vue-multiselect'
import moment from 'moment'
import _ from 'lodash'

export default {
    metaInfo() {
        return {
            title: 'UntrackedShipments',
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
                    field: 'shifl_ref_field',
                    sortable: false,
                },
                {
                    label: 'Mbl# | Booking#',
                    field: 'mbl_num_booking_number',
                    sortable: true,

                },
                {
                    label: 'Customer',
                    field: 'customer_link',
                    sortable: false,

                },
                {
                    label: 'Etd',
                    field: 'etd',
                    type: 'date',
                    formatFn: function(value) {
                        return value != null ? new Date(value).toLocaleDateString() : "__"
                    },
                    globalSearchDisabled: true,

                },
                {
                    label: 'Eta',
                    field: 'eta',
                    type: 'date',
                    formatFn: function(value) {
                        return value != null ? new Date(value).toLocaleDateString() : "__"
                    },
                    globalSearchDisabled: true,

                },
                {
                    label: 'Mode',
                    field: 'mode_label',
                    sortable: false,
                    globalSearchDisabled: true,

                },
                {
                    label: 'Type',
                    field: 'type',
                    sortable: false,
                    globalSearchDisabled: true,

                },
                {
                    label: 'Container#',
                    field: 'container_numbers',
                    sortable: false,
                    globalSearchDisabled: true,

                },
                {
                    label: 'Firms',
                    field: 'firms_code',
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
            sort: {
                field: 'etd',
                type: 'desc'
            },
            options: [
                {
                    type: 'all',
                    name: 'All Shipments'
                },
                {
                    type: 'tracked',
                    name: 'Tracked Shipments'
                },
                {
                    type: 'untracked',
                    name: 'Untracking Shipments Feed'
                }
            ],
            optionsTypeFilter: [
                {
                    type: '',
                    name: 'No type'
                },
                {
                    type: 'all',
                    name: 'All type'
                },
                {
                    type: 'Air',
                    name: 'Air'
                },
                {
                    type: 'FCL',
                    name: 'FCL'
                },
                {
                    type: 'LCL',
                    name: 'LCL'
                }
            ],
            optionsModeFilter: [
                {
                    mode: 'ocean',
                    name: 'Ocean'
                },
                {
                    mode: 'air',
                    name: 'Air'
                },
                {
                    mode: 'rail',
                    name: 'Rail'
                },
                {
                    mode: 'truck',
                    name: 'Truck'
                }
            ],
            selectedFilter: {
                type: 'all',
                name: 'All Shipments'
            },
            optionsMblFilter: [
                {
                    name: 'YesMBL',
                    value: 'yes',
                },
                {
                    name: 'NoMBL',
                    value: 'no'
                }
            ],
            selectedMblFilter: null,
            optionsExternalInternalFilter: [
                {
                    name: 'External',
                    value: 'external'
                },{
                    name: 'Internal',
                    value: 'internal'
                }
            ],
            selectedExternalInternalFilter: null,
            optionsOfficeFromFilter: [],
            selectedOfficeFromFilter: null,
            stf: [],
            selectedModeFilter: null,
            terminals: [],
            currentPage: 1,
            perPage: 10,
        };
    },
    mounted() {
        console.log('v1.0.3');
    },
    created() {
        this.sort = this.$store.getters.getSort
        this.selectedFilter = this.$store.getters.getSelectedFilter
        this.currentPage = this.$store.getters.getCurrentPage
        this.perPage = this.$store.getters.getPerPage
        this.stf = this.$store.getters.getStf
        this.selectedMblFilter = this.$store.getters.getSelectedMblFilter
        this.selectedModeFilter = this.$store.getters.getSelectedModeFilter
        this.selectedOfficeFromFilter = this.$store.getters.getSelectedOfficeFromFilter
        this.selectedExternalInternalFilter = this.$store.getters.getSelectedExternalInternalFilter

        this.fetchShipment(true)

        axios.get("/nova-vendor/untracked-shipments/shifl-offices")
             .then(response => {
                if(response.data){
                    this.optionsOfficeFromFilter = response.data
                }
             }).catch(e => {
                console.log(e)
             })

        axios.get("/nova-vendor/untracked-shipments/terminals")
             .then(response => {
                if(response.data){
                    this.terminals = response.data
                }
             }).catch(e => {
                console.log(e)
             })
    },
    methods: {
        getFirmsCode(terminal_id){
            if(terminal_id){
                let terminal = this.terminals.find(e => e.id == terminal_id)
                if(terminal) return terminal.firms_code
            }
            return '__'
        },
        getContainers(containers){
            try{
                let container_numbers = ''
                if(!_.isEmpty(containers)){
                    containers = JSON.parse(containers)
                    if(!_.isEmpty(containers)){
                        for(let i = 0; i< containers.length; i++){
                            container_numbers +=  ((containers[i].container_num ? containers[i].container_num : '__') + ((i+1) == containers.length ? '' : ' <br>'))
                        }
                        if(container_numbers != '') return container_numbers
                    }
                }
            }catch(err){
                console.log(err)
            }
            return '__'
        },
        searchContainer(containers,searchTerm){
            try{
                if(!_.isEmpty(containers)){
                    containers = JSON.parse(containers)
                    if(!_.isEmpty(containers)){
                        for(let i =0; i< containers.length; i++){
                            if(!_.isEmpty(containers[i].container_num)){
                                if(containers[i].container_num.toLowerCase().includes(searchTerm.toLowerCase())) return true
                            }
                        }
                    }
                }
            }catch(err){
                console.log(err)
            }


            return false
        },
        getMode(schedules){
            try{
                if(!_.isEmpty(schedules)){
                    schedules = JSON.parse(schedules)
                    if(!_.isEmpty(schedules)){
                        for(let i =0;i<schedules.length;i++){
                            if(schedules[i].is_confirmed){
                                return schedules[i].mode ? schedules[i].mode : '__'
                            }
                        }
                    }
                }
            }catch(err){
                console.log(err)
            }

            return "__"
        },
        clearFilters(){
            this.selectedExternalInternalFilter = null
            this.selectedOfficeFromFilter = null
            this.selectedModeFilter = null
            this.selectedMblFilter = null
            this.selectedFilter = {
                type: 'all',
                name: 'All Shipments'
            }
            this.stf = []
            this.preserveFilters()
            this.fetchShipment()
        },
        preserveFilters(){
            this.$store.commit('SET_SORT',this.sort)
            this.$store.commit('SET_SELECTED_FILTER',this.selectedFilter)
            this.$store.commit('SET_CURRENT_PAGE',this.currentPage)
            this.$store.commit('SET_PER_PAGE',this.perPage)
            this.$store.commit('SET_STF',this.stf)
            this.$store.commit('SET_SELECTED_MBL_FILTER',this.selectedMblFilter)
            this.$store.commit('SET_SELECTED_MODE_FILTER',this.selectedModeFilter)
            this.$store.commit('SET_SELECTED_OFFICE_FROM_FILTER',this.selectedOfficeFromFilter)
            this.$store.commit('SET_SELECTED_EXTERNAL_INTERNAL_FILTER',this.selectedExternalInternalFilter)
        },
        shouldShowTrackedButton(tracked_up_to){
            return _.isEmpty(tracked_up_to) || moment.utc(tracked_up_to).isBefore(moment().utc())
        },
        handleSearch(row, col, cellValue, searchTerm){
            return (row.shifl_ref && row.shifl_ref.toLowerCase().includes(searchTerm.toLowerCase())) ||
                  (row.mbl_num && row.mbl_num.toLowerCase().includes(searchTerm.toLowerCase())) ||
                  this.searchContainer(row.containers_group_bookings,searchTerm) ||
                  this.searchCustomer(row.customer, searchTerm)
        },
        goToEditView(id) {
            this.preserveFilters()
            this.$router.push(`/untracked-shipments/${id}/edit`)
        },
        goToDetailView(id) {
            this.preserveFilters()
            this.$router.push(`/untracked-shipments/${id}`)
        },
        fetchShipment(booted) {
            let _this = this;

            this.loading = true;

            axios.get("/nova-vendor/untracked-shipments/shipments",
            {
                params: {
                    sortby: this.sort.field,
                    sorttype: this.sort.type,
                    filter : this.selectedFilter.type,
                    filterTypeV2: this.stf.map(e => e.type),
                    filterMode: _.isEmpty(this.selectedModeFilter) ? null : this.selectedModeFilter.mode,
                    filterMBL: _.isEmpty(this.selectedMblFilter) ? null : this.selectedMblFilter.value,
                    filterOfficeFrom: _.isEmpty(this.selectedOfficeFromFilter) ? null : this.selectedOfficeFromFilter.id,
                    filterExternalInternal: _.isEmpty(this.selectedExternalInternalFilter) ? null : this.selectedExternalInternalFilter.value,
                    booted: booted ? 1 : 0
                }
            })
            .then(res => {
                if (res.status == 200) {
                    this.rows = res.data;
                }
                window.scrollTo(0, 0);
                this.loading = false;
            }).catch(err => {
                console.log(err)
                this.laoding = false;
            })
        },
        sortData(data){
            const hasNumber = (myString)=>{
              return /\d/.test(myString);
            }

            // covert null to empty
            data = data.map( i =>{
                i.mbl_num = _.isEmpty(i.mbl_num) ?  '' : i.mbl_num;
                return  i;
            });

            let data2 = [];
            // get blanks first
            let a = data.filter( i => i.mbl_num.trim() == '' );

            // get with numbers
            let b = data.filter( i => hasNumber(i.mbl_num) );
            // get all not blank and non numbers
            let c = data.filter( i => i.mbl_num.trim() != '' && !hasNumber(i.mbl_num) );
            // sort the abc data
            c = c.sort((a, b) => (a.mbl_num > b.mbl_num) ? 1 : -1);
            // merge them all
            return a.concat(b,c);

        },
        onSortChange(params) {
            this.sort = {
                type: (this.sort.type == 'asc' ? 'desc' : 'asc'),
                field:  params[0].field == 'mbl_num_booking_number' ? 'mbl_num' : params[0].field
            }
            
            this.fetchShipment();
        },
        moveToTracked(id) {
            console.log(id)
            axios.post("/nova-vendor/untracked-shipments/shipments/" + id + '/move-to-tracked')
                .then(res => {
                    this.fetchShipment();
                    Nova.success("Moved to Tracked Shipments Successfully!")
                }).catch(err => {
                    console.log(err)
                })
        },
        onPageChange(params) {
            // params.currentPage - current page that pagination is at
            // params.prevPage - previous page
            // params.currentPerPage - number of items per page
            // params.total - total number of items in the table
            this.currentPage = params.currentPage
            this.perPage = params.currentPerPage
        },
        goToCustomerView(id){
          window.open(window.location.origin + '/administrator/resources/customers/' + id, '_blank')
        },
        searchCustomer(customer, searchTerm){
            if(!_.isEmpty(customer)){
                if(!_.isEmpty(customer.company_name)){
                    return customer.company_name.toLowerCase().includes(searchTerm.toLowerCase())
                }
            }
            return false
        }

    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
/* Scoped Styles */
.text-dark-blue{
  color: #00008b;
}
.text-dark-yellow{
  color: #B58B00;
}
</style>
