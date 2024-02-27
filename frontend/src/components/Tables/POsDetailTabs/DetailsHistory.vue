<!-- @format -->

<template>
	<div class="po-details-wrapper-table history">
		<v-data-table
            :headers="headers"
            :items="poHistoryItems"
            class="desktop-podetails-product elevation-0"
            v-bind:class="{
                'no-data-table': (poHistoryItems.length === 0)
            }"
            hide-default-footer
            mobile-breakpoint="1023"
            v-if="!isMobile"
            :items-per-page="500">

            <template v-slot:[`item.updated_at`]="{ item }">
                <span> {{ item.updated_at !== null ? formatDate(item.updated_at) : '--' }} </span>
            </template>

            <template v-slot:[`item.updated_by`]="{ item }">
                <span class="text-capitalize"> {{ item.updated_by !== null ? item.updated_by : '--' }} </span>
            </template>

            <template v-slot:[`item.update_type`]="{ item }">
                <span class="text-capitalize"> 
                    {{ item.update_type !== null ? replaceString(item.update_type) : '--' }} 
                </span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="po-history-button-wrapper">
                    <button class="btn-white po-view" @click="showHistoryDetail(item)">
                        View Details
                    </button>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper pt-5" v-if="getPoHistoryLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>

                <div class="no-data-wrapper" v-if="!getPoHistoryLoading && poHistoryItems.length == 0">
                    <div class="no-data-heading">
                        <p>No data available in history</p>
                    </div>
                </div>
            </template>
        </v-data-table>

        <PoHistoryPreviewDialog 
            :dialogData.sync="showHistoryDetailDialog" 
            @close="closeHistoryDetail"
            :from="from"
            :updatedType="updatedType"
        />
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import inventoryGlobalMethods from '../../../utils/inventoryMethods/inventoryGlobalMethods'
import PoHistoryPreviewDialog from '../../PosComponents/Dialog/PoHistoryPreviewDialog.vue'

export default {
	components: {
        PoHistoryPreviewDialog
    },
    name: 'DetailsHistory',
	props: ["isMobile", "from"],
	data: () => ({
		headers: [
			{
				text: "Updated at",
				align: "start",
				sortable: false,
				value: "updated_at",
				fixed: true,
				width: "18%",
			},
			{
				text: "Updated By",
				align: "start",
				sortable: false,
				value: "updated_by",
				fixed: true,
				width: "15%",
			},
			{
				text: "Update Type",
				align: "start",
				sortable: false,
				value: "update_type",
				fixed: true,
				width: "25%",
			},
			{
				text: "Actions",
				align: "end",
				sortable: false,
				value: "actions",
				fixed: true,
				width: "10%",
			},
		],
        showHistoryDetailDialog: false,
        updatedType:null
	}),
	methods: {
		...mapActions({
            fetchPoHistoryPreview: 'poDetails/fetchPoHistoryPreview'
        }),
        ...inventoryGlobalMethods,
        formatDate(date) {
            return this.formatTimeAndDateTogether(date)
        },
        replaceString(data) {
            if (data !== null) {
                if (data === 'so_created') {
                    data = 'SO_Created'
                } else if (data === 'po_created') {
                    data = 'PO_Created'
                }

                return data.replace(/_+/g, ' ');
            }
        },
        async showHistoryDetail(data) {
            this.showHistoryDetailDialog = true
            if (data !== null) {
                this.updatedType = data
                await this.fetchPoHistoryPreview(data.id)
            }
        },
        closeHistoryDetail() {
            this.showHistoryDetailDialog = false
            this.updatedType = null
        }
	},
	computed: {
		...mapGetters({
            getPoHistory: 'poDetails/getPoHistory',
            getPoHistoryLoading: 'poDetails/getPoHistoryLoading',
            getPoHistoryPreview: 'poDetails/getPoHistoryPreview'
            
        }),
        poHistoryItems() {
            let items = []

            if (typeof this.getPoHistory !== 'undefined' && this.getPoHistory !== null) {
                if (typeof this.getPoHistory.data !== 'undefined' && this.getPoHistory.data !== null) {
                    items = this.getPoHistory.data
                }
            }

            return items
        }
	},
    async mounted() {},
	updated() {},
};
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/po/poHistory.scss';
</style>
