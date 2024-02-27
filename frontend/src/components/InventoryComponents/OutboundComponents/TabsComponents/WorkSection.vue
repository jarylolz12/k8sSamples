<template>
    <div>
        <v-data-table
            :headers="productsHeader"
            :items="productItems"
            item-key="sku"
            class="elevation-1 outbound-view-work"
            :class="(typeof productsData !== 'undefined' && productsData !== null && productsData.length > 0) ? '' : 'no-data-table'"
            mobile-breakpoint="769"
            hide-default-footer
            show-select>

            <template v-slot:[`item.order_no`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p>{{ item.order_no !== '' && item.order_no !== null ? item.order_no : '--'}}</p>
                </div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p>{{ item.updated_at !== '' && item.updated_at !== null ? item.updated_at : '--'}}</p>
                </div>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p>{{ item.type !== '' && item.type !== null ? item.type : '--'}}</p>
                </div>
            </template>

            <template v-slot:[`item.priority`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p class="redTxt">{{ item.priority !== '' && item.priority !== null ? item.priority : '--'}}</p>
                </div>
            </template>

            <template v-slot:[`item.assignee`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p>{{ item.assignee !== '' && item.assignee !== null ? item.assignee : '--'}}</p>
                </div>
            </template>

            <template v-slot:[`item.status`]="{ item }">                
                <div class="status">
                    <span :class="item.status">{{ item.status }}</span>
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions-wrapper">
                    <button class="btn-status btn-action" :class="item.action" @click.stop="receiveProduct(item)"
                        :disabled="productsData.action == 'pending'">
                        <span class="btn-content">
                            <span class="completed"><img src="@/assets/icons/checkMark-green.svg" width="13"> {{ item.action }}</span>
                        </span>
                    </button>

                    <button class="btn-status btn-white btn-dotted" :class="item.status"
                        :disabled="productsData.status == 'pending'">
                        <span class="btn-content">
                            <img src="@/assets/icons/dots.svg" width="13">
                        </span>
                    </button>
                </div>
            </template>

        </v-data-table>

        <v-dialog v-model="dialogPick" width="500" content-class="outbound-dialog-pick">
            <v-card>
                <v-card-title class="headline">
                    <div class="question-icon mt-3 mb-1">
                        <img src="@/assets/icons/question.svg" alt="" width="48px" height="48px">
                    </div>
                </v-card-title>

                <v-card-text style="padding-bottom: 15px;">
                    <h2>Confirm Pick</h2>
                    <p v-if="currentPickedProduct !== null">
                        Have you picked <span class="color-blue-default">{{ currentPickedProduct.carton_count }} Cartons 
                        </span> with {{ currentPickedProduct.units }} Units of 
                        <span class="color-blue-default">SKU {{ currentPickedProduct.sku }}</span> 
                        <span v-if="currentPickedProduct.pallet !== ''"> from Pallet {{ currentPickedProduct.pallet }}</span>?
                    </p>
                </v-card-text>

                <v-card-actions>
                    <button class="btn-blue confirm-pick" @click="closePickDialog">
                        Confirm Pick
                    </button>

                    <button class="btn-white cancel-pick" @click="closePickDialog">
                        Cancel
                    </button>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"

export default {
    name: 'ProductSection',
    props: ["outboundProductsData", "isMobile"],
    components: {
    },
    data: () => ({
        productsHeader: [
            {
				text: 'Order No',
				align: 'start',
				sortable: false,
				value: 'order_no',
				fixed: true,
				width: "14%"
			},
			{ 
				text: 'Updated At',
				align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: "20%"
			},
            { 
				text: 'Type',
				align: 'start',
				sortable: false,
				value: 'type',
				fixed: true,
				width: "10%"
			},
			{ 
				text: 'Priority',
				align: 'start',
				sortable: false,
				value: 'priority',
				fixed: true,
				width: "10%"
			},
            { 
				text: 'Assignee',
				align: 'start',
				sortable: false,
				value: 'assignee',
				fixed: true,
				width: "15%"
			},
            { 
				text: 'Status',
				align: 'start',
				sortable: false,
				value: 'status',
				fixed: true,
				width: "10%"
			},
			{ 
				text: '', 
				align: 'center',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "21%"
			},
        ],
        productItems: [
            {
                order_no: 'WO19836',
                updated_at: '12:26PM, Jul 01, 2020',
                type: 'Pick',
                priority: 'High',
                assignee: 'David De Gea',
                status: 'Completed',
                action: 'Picked',
            },
            {
                order_no: 'WO19836',
                updated_at: '12:26PM, Jul 01, 2020',
                type: 'Pick',
                priority: 'High',
                assignee: 'David De Gea',
                status: 'Completed',
                action: 'Built',
            },
            {
                order_no: 'WO19836',
                updated_at: '12:26PM, Jul 01, 2020',
                type: 'Pick',
                priority: 'High',
                assignee: 'David De Gea',
                status: 'Completed',
                action: 'Loaded',
            },
        ],
        dialogPick: false,
        currentPickedProduct: null
    }),
    computed: {
        ...mapGetters({ }),
        productsData: {
            get() {
                let newProductsData = []

                if (typeof this.outboundProductsData !== 'undefined' && this.outboundProductsData !== null) {
                    if (this.outboundProductsData.outbound_products !== 'undefined' 
                        && this.outboundProductsData.outbound_products.length > 0) {
                        
                        newProductsData = this.outboundProductsData.outbound_products
                    }
                }

                return newProductsData
            },
            set(value) {
                this.$emit('update:outboundProductsData', value)
            }
        }
    },
    methods: {
        ...mapActions({ }),
        pickProduct(item) {
            this.dialogPick = true
            this.currentPickedProduct = item
            // console.log(item);
        },
        closePickDialog() {
            this.dialogPick = false
        },
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "inventory");
    },
    updated() {
        // console.log(this.productsData);
    }
}
</script>

<style lang="scss">
/* @import '../../../assets/scss/buttons.scss';
@import '../../../assets/scss/pages_scss/outbound/outboundView.scss'; */
@import './scss/workSection.scss';
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';
</style>