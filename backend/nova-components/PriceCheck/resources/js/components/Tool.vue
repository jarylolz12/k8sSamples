<template>
    <div>
        <heading class="mb-6">Price Check Shipments</heading>
        <card v-if="!itemsLoading">

            <div class="pt-2 border-b border-50">
                <div class="flex justify-between pb-2">
                    <div class="flex bg-10 rounded">
                        <div>
                            <div class="p-2">
                                <button type="submit" class="btn btn-default btn-primary" @click="confirmUpdateRC()">
                                    {{ __('Update Rate Confirmed') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex bg-10 rounded">
                        <div>
                            <div class="p-2">
                                <select dusk="Filter by Shifl Office From-filter-select" class="block w-full form-control form-select cursor-pointer" @change="(e)=>{ fetchFilterItems(e, 'shiflOfficeFrom') }">
                                    <option v-for="option in shiflOfficeList" :selected="selectedFilterValue['shiflOfficeFrom'] == option.id" :value="option.id" :key="option.id"> {{ option.name }} </option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="p-2">
                                <select dusk="Filter by Rate Confirm-filter-select" class="block w-full form-control form-select cursor-pointer" @change="(e)=>{ fetchFilterItems(e, 'rateConfirmed') }">
                                    <option v-for="option in rateConfirmFilterOptions" :selected="selectedFilterValue['rateConfirmed'] == option.type" :value="option.type" :key="option.type"> {{ option.name }} </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <vue-good-table
                    mode="remote"
                    :columns="columns"
                    :rows="itemsFiltered"
                    @on-sort-change="onSortChange"
                    :totalRows="total"
                    theme="vgt-table striped"
                >
                    <template slot="table-row" slot-scope="props">

                        <div v-if="props.column.field == 'customerName_field'" >
                            <a class="no-underline dim text-primary font-bold" :href="`/administrator/resources/customers/${props.row.customerId}`"> {{ props.row.customerName }} </a>
                        </div>

                        <div v-else-if="props.column.field == 'rate_confirmed'" >
                            <div v-if="props.row.rate_confirmed == 1" class="text-center text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="check-circle" role="presentation" class="fill-current text-success"><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"></path></svg></div>
                            <div v-else class="text-center text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="x-circle" role="presentation" class="fill-current text-danger"><path d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"></path></svg></div>
                        </div>

                        <div v-else-if="props.column.field == 'select_rate_confirmed'" >
                            <input type="checkbox" class="checkbox" :id="props.row.shipment_id" :value="props.row.shipment_id" v-model="checkedShipmentIds">
                        </div>

                        <div style="padding-bottom: 15px;" v-else-if="typeof props.row.buy_rate_field!=='undefined' && typeof props.row.buy_rate_field!=='undefined' && props.column.field == 'buy_rate_field'" v-for="(buy_rate,key) in props.row.buy_rate_field" >
                            <div class="px-2">
                                <label>Charge Per: </label>
                                <span>{{ buy_rate.container_size_name }}</span>
                            </div>
                            <div class="px-2">
                                <label>Amount: </label>
                                <span>{{ buy_rate.amount }}</span>
                            </div>
                            <div class="px-2">
                                <label>Quantity: </label>
                                <span>{{ buy_rate.qty }}</span>
                            </div>
                            <div class="px-2">
                                <label>Valid To: </label>
                                <span>{{ buy_rate.valid_to != null ? new Date(buy_rate.valid_to).toLocaleDateString() : "__" }}</span>
                            </div>
                        </div>

                        <div style="padding-bottom: 15px;" v-else-if="typeof props.row.sell_rates_field!=='undefined' && typeof props.row.sell_rates_field!=='undefined' && props.column.field == 'sell_rates_field'" v-for="(sell_rate,key) in props.row.sell_rates_field" >
                            <div class="px-2">
                                <label>Charge Per: </label>
                                <span>{{ sell_rate.container_size_name }}</span>
                            </div>
                            <div class="px-2">
                                <label>Amount: </label>
                                <span>{{ sell_rate.amount }}</span>
                            </div>
                            <div class="px-2">
                                <label>Quantity: </label>
                                <span>{{ sell_rate.qty }}</span>
                            </div>
                            <div class="px-2">
                                <label>Valid To: </label>
                                <span>{{ sell_rate.valid_to != null ? new Date(sell_rate.valid_to).toLocaleDateString() : "__" }}</span>
                            </div>
                        </div>

                        <span v-else>
                            {{props.formattedRow[props.column.field]}}
                        </span>
                    </template>
                </vue-good-table>

            </div>
            <pagination
                :items="items"
                :from="from"
                :to="to"
                :total="total"
                :firstLoad="firstLoad"
                :nextPageUrl="nextPageUrl"
                :prevPageUrl="prevPageUrl"
                @nextPage="nextPage"
                @prevPage="prevPage"
            >
                <template slot="previous">
                    <span>Previous</span>
                </template>
                <template slot="next">
                    <span>Next</span>
                </template>
            </pagination>
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
    import { mapActions, mapGetters } from 'vuex'
    import Pagination from './Pagination/Pagination'

    export default {
        metaInfo() {
            return {
                title: 'PriceCheck',
            }
        },
        // add to component
        components: {
            VueGoodTable,
            Pagination
        },
        data() {
            return {
                checkedShipmentIds: [],
                shiflOfficeList:[],
                rateConfirmFilterOptions:[
                    {
                        'type' : 'all',
                        'name' : 'All(Rate Confirmed)'
                    },
                    {
                        'type' : 1,
                        'name' : 'Not Confirmed'
                    },
                    {
                        'type' : 2,
                        'name' : 'Confirmed'
                    }
                ],
                selectedFilterValue: { 'rateConfirm' : 'all', 'shiflOfficeFrom': 'all' },
                columns: [{
                    label: 'Shifl Ref#',
                    field: 'shifl_ref',
                    sortable: false,
                },
                    {
                        label: 'Carrier',
                        field: 'carrierName',
                        sortable: false,

                    },
                    {
                        label: 'Shifl Office From',
                        field: 'shifl_office_from',
                        sortable: false,
                    },
                    {
                        label: 'Customer Name',
                        field: 'customerName_field',
                        sortable: false,

                    },
                    {
                        label: 'RC',
                        field: 'rate_confirmed',
                        sortable: false,

                    },
                    {
                        label: 'Select Rate Confirmed',
                        field: 'select_rate_confirmed',
                        sortable: false,
                    },
                    {
                        label: 'Buy Rates(Freight (port to port))',
                        field: 'buy_rate_field',
                        sortable: false,
                    },
                    {
                        label: 'Sell Rates(Freight (port to port))',
                        field: 'sell_rates_field',
                        sortable: false,
                        html: true,
                    },
                    {
                        label: 'ETD',
                        field: 'etd',
                        type: 'date',
                        formatFn: function(value) {
                            return value != null ? new Date(value).toLocaleDateString() : "__"
                        },
                        sortable: true,
                    },
                ],
                serverParams: {
                    columnFilters: {
                    },
                    sort: [
                        {
                            field: 'etd',
                            type: 'desc'
                        }
                    ],
                    page: 1,
                    perPage: 50
                },
                firstLoad: false,
            };
        },
        computed: {
            ...mapGetters({
                items: 'priceCheck/getItems',
                itemsLoading: 'priceCheck/getItemsLoading',
                currentPage: 'priceCheck/getCurrentPage',
                nextPageUrl: 'priceCheck/getNextPageUrl',
                prevPageUrl: 'priceCheck/getPrevPageUrl',
                from: 'priceCheck/getFrom',
                to: 'priceCheck/getTo',
                total: 'priceCheck/getTotal',
                selectedFilters: 'priceCheck/getSelectedFilter',
            }),
            itemsFiltered() {
                let items = this.items
                let newItems = []
                if (items.length > 0) {
                    items.map( item => {
                        item.buy_rate_field = this.displayBuyRates('charge_per', item.schedules_group_bookings)
                        item.sell_rates_field = this.displaySellRates('charge_per', item.schedules_group_bookings)
                        newItems.push(item)
                    })
                }
                return newItems
            },
        },
        async mounted() {
            try {
                await this.fetchItems({params: this.serverParams})
                await this.fetchShiflOffices({params: this.serverParams})
                    .then((res) => {
                        this.shiflOfficeList = res.data.results;
                        this.shiflOfficeList.unshift({ 'id': 'all', 'name': 'All Shifl Offices'})
                    })
                this.firstLoad = true
            } catch(e) {
                this.setItemsLoading(false)
                console.log(e)
            }

        },
        methods: {
            ...mapActions({
                fetchItems: 'priceCheck/fetchItems',
                setPage: 'priceCheck/setPage',
                fetchPage: 'priceCheck/fetchPage',
                setItemsLoading: 'priceCheck/setItemsLoading',
                setSelectedFilter: 'priceCheck/setSelectedFilter',
                updateRateConfirmed: 'priceCheck/updateRateConfirmed',
                fetchShiflOffices: 'priceCheck/fetchShiflOffices',
            }),
            updateParams(newProps) {
                this.serverParams = Object.assign({}, this.serverParams, newProps);
            },
            async onSortChange(params) {
                this.updateParams({
                    sort: [{
                        type: this.serverParams.sort[0].type == 'asc' ? 'desc' : 'asc',
                        field: params[0].field,
                    }],
                })
                await this.fetchItems({params: this.serverParams})
            },
            async confirmUpdateRC(){
                await this.updateRateConfirmed({ params: this.checkedShipmentIds })
                    .then(async () => {
                        this.checkedShipmentIds = [];
                        await this.fetchItems({ params: this.serverParams });
                        Nova.success('Shipments updated successfully.');
                    }).catch(() => {
                        this.setItemsLoading(false);
                        Nova.error(this.__('There was a problem updating the data.'));
                    })
            },
            isAvailable(parent, child) {
                try {
                    const childArray = String(child).split('.');
                    let evaluted = parent;
                    childArray.forEach((x) => {
                        evaluted = evaluted[x];
                    });
                    return !!evaluted;
                } catch(err)  {
                    return false;
                }
            },
            displayBuyRates(field, data){
                if(data !== undefined){
                    const jsonObj = data;
                    if(jsonObj){
                        if(this.isAvailable(jsonObj[0], 'buy_rates')){
                            if(jsonObj[0].buy_rates.length > 0){
                                const freightBuyRates =  this.searchByKey("Freight (port to port)", jsonObj[0].buy_rates);
                                return freightBuyRates;
                            }
                        }
                    }
                }
                return '';
            },
            displaySellRates(field, data){
                if(data !== undefined){
                    const jsonObj = data;
                    if(jsonObj){
                        if(this.isAvailable(jsonObj[0], 'sell_rates')){
                            if(jsonObj[0].buy_rates.length > 0){
                                const freightSellRates =  this.searchByKey("Freight (port to port)", jsonObj[0].sell_rates);
                                return freightSellRates;
                            }
                        }
                    }
                }
                return '';
            },
            searchByKey(key, inputArray) {
                const newArray = [];
                for (let i=0; i < inputArray.length; i++) {
                    if (inputArray[i].service_name === key) {
                        newArray.push(inputArray[i]);
                    }
                }
                return newArray;
            },
            async nextPage() {
                this.serverParams.page++
                this.serverParams.columnFilters.rateConfirmed = this.selectedFilterValue
                await this.fetchPage({url: this.nextPageUrl, params: this.serverParams})

            },
            async prevPage() {
                this.serverParams.page--
                this.serverParams.columnFilters.rateConfirmed = this.selectedFilterValue
                await this.fetchPage({url: this.prevPageUrl, params: this.serverParams})
            },
            async fetchFilterItems(e, type){
                this.selectedFilterValue[type] = await e.target.value;
                this.setSelectedFilter(this.selectedFilterValue)
                await this.fetchItems({params: this.serverParams})
            }
        }
    }
</script>
