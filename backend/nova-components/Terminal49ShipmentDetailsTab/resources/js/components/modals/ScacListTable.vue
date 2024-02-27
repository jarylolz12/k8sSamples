<template>
    <modal>
        <card class="p-4">
            <span style="color: red; float: right; padding-top: 10px; padding-right: 15px; cursor: pointer" class="pb-4" @click="close">
              <b>Close</b>
            </span>
            <div class="flex justify-between">
              <heading class="">List of SCAC supported by Terminal49</heading>
            </div>

            <card v-if="!loading">
                <div class="pt-2 border-b border-50">

                    <vue-good-table
                        :columns="columns"
                        :rows="rows"
                        :pagination-options="{
                          enabled: false,
                        }"
                        theme="polar-bear"
                        :sort-options="{
                           enabled: true,
                        }"
                    >
                    <template slot="table-row" slot-scope="props">
                        <div v-if="props.column.field == 'bill_of_lading_tracking_support'">
                            <span v-if="props.row.bill_of_lading_tracking_support">
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

                        </div>
                    </template>

                    </vue-good-table>

                </div>
            </card>

            <card v-else class="flex border-b border-40 p-8">
                <loader class="text-60 text-center" />
            </card>
      </card>

    </modal>
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
            title: 'ScacListTable',
        }
    },
    props: ['rows'],
    // add to component
    components: {
        VueGoodTable,
    },
    data() {
        return {
            columns: [
                {
                    label: 'SCAC',
                    field: 'scac',
                    sortable: false,
                },
                {
                    label: 'Name',
                    field: 'name',
                    sortable: false,

                },
                {
                    label: 'Short Name',
                    field: 'short_name',
                    sortable: false,
                },
                {
                    label: 'BOL Tracking Support',
                    field: 'bill_of_lading_tracking_support',
                    sortable: false,
                },

            ],
        };
    },
    computed:{

    },
    mounted() {
        //
    },
    created() {
    },
    methods: {
      close(){
        this.$emit('toggleScacModal')
      }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
