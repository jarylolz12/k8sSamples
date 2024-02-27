<template>
    <v-data-table
        :headers="palletsHeader"
        :items="palletItems"
        class="elevation-1 outbound-view-products"
        item-key="label"
        mobile-breakpoint="769"
        hide-default-footer
        show-select>

        <template v-slot:[`item.actions`]="{ item }">
            <div class="actions-wrapper">
                <button class="btn-status btn-white" :class="item.status">
                    <span class="btn-content">
                        <img src="../../../../assets/icons/checkMark.png" alt="" class="mr-1" v-if="item.status == 'done'" width="12px" height="12px">

                        <span v-if="item.status == 'pending'">Mark Done</span>
                        <span v-else>{{ item.status }}</span>
                    </span>
                </button>

                <button class="btn-print btn-white">
                    <span class="btn-content">
                        <img src="../../../../assets/icons/print.svg" alt="" width="12px" height="12px">
                    </span>
                </button>
            </div>
        </template>
    </v-data-table>
</template>

<script>
import { mapActions, mapGetters } from "vuex"

export default {
    name: 'PalletSection',
    props: [],
    components: {
    },
    data: () => ({
        palletsHeader: [
            {
				text: 'Label',
				align: 'start',
				sortable: false,
				value: 'label',
				fixed: true,
				width: "10%"
			},
			{ 
				text: 'Type',
				align: 'start',
				sortable: false,
				value: 'type',
				fixed: true,
				width: "12%"
			},
			{ 
				text: 'WORK ORDER',
				align: 'start',
				sortable: false,
				value: 'work_order',
				fixed: true,
				width: "12%"
			},
            { 
				text: 'Updated at',
				align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: "18%"
			},
            { 
				text: 'No of sku',
				align: 'start',
				sortable: false,
				value: 'no_of_sku',
				fixed: true,
				width: "10%"
			},
			{ 
				text: 'CARTON',
				align: 'end',
				sortable: false,
				value: 'carton_count',
				fixed: true,
				width: "12%"
			},
            { 
				text: 'UNIT',
				align: 'end',
				sortable: false,
				value: 'units',
				fixed: true,
				width: "12%"
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "15%"
			},
        ],
        palletItems: []
        // palletItems: [
        //     {
        //         label: '558612',
        //         type: 'Regular',
        //         updated_at: '--',
        //         no_of_sku: '1',
        //         work_order: 'WO19293',
        //         carton_count: '80',
        //         units: '615',
        //         status: 'pending'
        //     },
        //     {
        //         label: '892816',
        //         type: 'Drum',
        //         updated_at: '--',
        //         no_of_sku: '3',
        //         work_order: 'WO19294',
        //         carton_count: '40',
        //         units: '428',
        //         status: 'pending'
        //     },
        //     {
        //         label: '827302',
        //         type: 'Regular',
        //         updated_at: '01:47PM, Aug 16, 2021',
        //         no_of_sku: '2',
        //         work_order: 'WO19295',
        //         carton_count: '100',
        //         units: '640',
        //         status: 'done'
        //     }
        // ]
    }),
    computed: {
        ...mapGetters({
            
        }),
    },
    methods: {
        ...mapActions({
            
        }),
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "inventory");
    },
    updated() {}
}
</script>

<style lang="scss">

</style>