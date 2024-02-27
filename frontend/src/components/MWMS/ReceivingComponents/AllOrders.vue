<template>
<!-- 1 -->
    <div class="mwm-system-wrapper">
        <div class="mwm-system-content">
            <div class="loading-wrapper" v-if="getEmployeeOrdersLoading">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>
            
            <div class="mwms-receving-wrapper" v-if="!getEmployeeOrdersLoading">
                <div class="receiving-header">
                    <div class="d-flex align-center">
                        <button class="d-flex"> 
                            <v-icon class="back mr-2" @click="onClickBack()" v-if="!isShowCheckBox">mdi-chevron-left</v-icon>
                            <v-icon class="back mr-2" @click="updateShowMultiple()" v-if="isShowCheckBox">mdi-close</v-icon> 
                        </button>

                        <p class="mwms-page-title font-semi-bold">Receiving {{ isShowCheckBox ? 'Multiple' : ''}}</p>
                    </div>

                    <button class="select-multiple font-medium" @click="updateShowMultiple()" v-if="!isShowCheckBox">
                        Select Multiple
                    </button>
                </div>

                <div class="receiving-body-wrapper" :style="`height: calc(100vh - ${updatedHeight}px);`">
                    <!-- SHOW ALL ASSIGNED ORDERS -->
                    <div class="receiving-assigned">
                        <p class="receiving-body-title font-medium">Assigned Orders</p>

                        <div class="assigned-lists-wrapper">
                            <div class="assigned-lists">
                                <div v-if="!isShowCheckBox">
                                    <div class="items" v-for="item in assignedOrders" :key="item.id">
                                        <div class="assigned-order">
                                            <p class="font-semi-bold mb-0 p-16 d-flex align-center">Order #{{item.order_id}} 
                                                <button class="d-flex align-center">
                                                    <v-icon class="back mr-2" color="#6D858F" @click="confirmReceiving('single', item)">mdi-chevron-right</v-icon>
                                                </button>
                                            </p>
                                            <p class="mb-0 p-16">{{item.total_cartons}} Cartons</p>
                                        </div>
                                        <p class="mb-0">
                                            {{item.sku_count}} {{ item.sku_count > 1 ? 'SKUs' : 'SKU'}}<span v-if="item.supplier !== null">, {{ item.supplier }}</span><span v-if="item.warehouse_customer !== null">, {{ item.warehouse_customer.company_name }}</span>
                                        </p>
                                    </div>
                                </div>

                                <v-list v-if="isShowCheckBox" class="pa-0">
                                    <v-list-item class="items d-flex align-start pl-3" v-for="item in assignedOrders" :key="item.id">
                                        <v-list-item-action class="mr-2 mt-0 mb-5">
                                            <v-checkbox class="mt-0 pt-0 mobile-select-receiving" hide-details="auto" v-model="selected" multiple :value="item.id" />
                                        </v-list-item-action>
                                        <v-list-item-content style="width: 100%; padding: 0;">
                                            <div style="width: 100%;">
                                                <div class="assigned-order mb-2">
                                                    <p class="font-semi-bold mb-0 p-16 d-flex align-center">
                                                        Order #{{item.order_id}}
                                                    </p>
                                                    <p class="mb-0 p-16">{{item.total_cartons}} Cartons</p>
                                                </div>
                                                <p class="mb-0">
                                                    {{item.sku_count}} {{ item.sku_count > 1 ? 'SKUs' : 'SKU'}}<span v-if="item.supplier !== null">, {{ item.supplier }}</span><span v-if="item.warehouse_customer !== null">, {{ item.warehouse_customer.company_name }}</span>
                                                </p>
                                            </div>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </div>
                        </div>
                    </div>

                    <!-- SHOW ALL UNASSIGNED ORDERS-->
                    <div class="receiving-unassigned">
                        <p class="receiving-body-title font-medium">Other Orders</p>

                        <div class="assigned-lists-wrapper">
                            <div class="assigned-lists">
                                <div v-if="!isShowCheckBox">
                                    <div class="items" v-for="item in unassignedOrders" :key="item.id">
                                        <div class="assigned-order">
                                            <p class="font-semi-bold mb-0 p-16 d-flex align-center">Order #{{item.order_id}} 
                                                <button class="d-flex align-center">
                                                    <v-icon class="back mr-2" color="#6D858F" @click="confirmReceiving('single')">mdi-chevron-right</v-icon>
                                                </button>
                                            </p>
                                            <p class="mb-0 p-16">{{item.total_cartons}} Cartons</p>
                                        </div>
                                        <p class="mb-0">
                                            {{item.sku_count}} {{ item.sku_count > 1 ? 'SKUs' : 'SKU'}}<span v-if="item.supplier !== null">, {{ item.supplier }}</span><span v-if="item.warehouse_customer !== null">, {{ item.warehouse_customer.company_name }}</span>
                                        </p>
                                    </div>
                                </div>

                                <v-list v-if="isShowCheckBox" class="pa-0">
                                    <v-list-item class="items d-flex align-start pl-3" v-for="item in unassignedOrders" :key="item.id">
                                        <v-list-item-action class="mr-2 mt-0 mb-5">
                                            <v-checkbox class="mt-0 pt-0 mobile-select-receiving" hide-details="auto" v-model="selected" multiple :value="item.id" />
                                        </v-list-item-action>
                                        <v-list-item-content style="width: 100%; padding: 0;">
                                            <div style="width: 100%;">
                                                <div class="assigned-order mb-2">
                                                    <p class="font-semi-bold mb-0 p-16 d-flex align-center">
                                                        Order #{{item.order_id}}
                                                    </p>
                                                    <p class="mb-0 p-16">{{item.total_cartons}} Cartons</p>
                                                </div>
                                                <p class="mb-0">
                                                    {{item.sku_count}} {{ item.sku_count > 1 ? 'SKUs' : 'SKU'}}<span v-if="item.supplier !== null">, {{ item.supplier }}</span><span v-if="item.warehouse_customer !== null">, {{ item.warehouse_customer.company_name }}</span>
                                                </p>
                                            </div>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fixed-footer-scanner">
                    <v-text-field 
                        v-model="enteredCode" 
                        class="text-fields select-product scan"
                        placeholder="Scan or Enter Order" 
                        append-icon="mdi-qrcode-scan"
                        @click:append="onClickScanner"
                        outlined
                        hide-details="auto">
                    </v-text-field>

                    <button class="btn-blue btn-confirm" @click="confirmReceiving('multiple')" v-if="isShowCheckBox && selected.length > 0">
                        Confirm Selection ({{ selected.length }})
                    </button>
                </div>

                <ConfirmDialog :dialogData.sync="dialogScanner" :className="'scanner-dialog-mwms'">
                    <template v-slot:dialog_title>
                        <div class="d-flex justify-space-between align-center pa-2 pb-3 pr-0">
                            <p class="mb-0 font-semi-bold" style="font-size: 22px;"> Scan </p>
                            <v-spacer></v-spacer>
                            <v-btn icon dark class="btn-close" @click="closeScanner" color="#4a4a4a">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </div>
                    </template>

                    <template v-slot:dialog_content>
                        <div class="px-2 pb-2">
                            <StreamBarcodeReader
                                ref="scanner"
                                @decode="onDecode"
                                @loaded="onLoaded">
                            </StreamBarcodeReader>
                        </div>
                    </template>
                </ConfirmDialog>   
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue';
import { StreamBarcodeReader } from "vue-barcode-reader";
// import { ImageBarcodeReader } from "vue-barcode-reader";

export default {
    name: "Receiving",
    components: {
        ConfirmDialog,
        StreamBarcodeReader
    },
    data: () => ({
        enteredCode: '',
        orders: [
            {
                id: 1,
                order_id: '1234',
                cartons: 15,
                warehouse_name: '3 SKUs'
            },
            {
                id: 2,
                order_id: '1235',
                cartons: 25,
                warehouse_name: '5 SKUs, Fabric Innovation ltd.'
            },
            {
                id: 3,
                order_id: '1236',
                cartons: 40,
                warehouse_name: '4 SKUs'
            },
            {
                id: 4,
                order_id: '1237',
                cartons: 38,
                warehouse_name: '2 SKUs, MTN Limited'
            },
            {
                id: 5,
                order_id: '1238',
                cartons: 32,
                warehouse_name: '4 SKUs, Shopify '
            }
        ],
        unassigned: [
            {
                id: 6,
                order_id: '1239',
                cartons: 15,
                warehouse_name: '4 SKUs, Panasonic'
            },
            {
                id: 7,
                order_id: '1240',
                cartons: 25,
                warehouse_name: '8 SKUs'
            },
            {
                id: 8,
                order_id: '1241',
                cartons: 40,
                warehouse_name: '4 SKUs'
            },
            {
                id: 9,
                order_id: '1242',
                cartons: 38,
                warehouse_name: '2 SKUs, MTN Limited'
            },
        ],
        isShowCheckBox: false,
        selected: [],
        updatedHeight: '',
        dialogScanner: false,
        hasLoaded: false
    }),
    watch: {
        selected(val) {
            if (val.length > 0) {
                if (this.isShowCheckBox) {
                    let defaultToSubtract = 200
                    this.updatedHeight = defaultToSubtract + 56
                } else 
                    this.updatedHeight = 200
            } else
                this.updatedHeight = 200
        }
    },
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getEmployeeOrders: 'mwms/getEmployeeOrders',
            getEmployeeOrdersLoading: 'mwms/getEmployeeOrdersLoading'
        }),
        assignedOrders() {
            let orders = []

            if (typeof this.getEmployeeOrders !== 'undefined' && this.getEmployeeOrders !== null) {
                orders = this.getEmployeeOrders.assigned_orders
            }

            return orders
        },
        unassignedOrders() {
            let orders = []

            if (typeof this.getEmployeeOrders !== 'undefined' && this.getEmployeeOrders !== null) {
                orders = this.getEmployeeOrders.other_orders
            }

            return orders
        }
    },
    methods: {
        ...mapActions({
            fetchEmployeeOrders: 'mwms/fetchEmployeeOrders'
        }),
        onClickBack() {
            this.$router.push('/mwms')
        },
        onClickScanner() {
            this.dialogScanner = true

            if (this.$refs.scanner !== undefined) {
                this.$refs.scanner.start()
                this.enteredCode = ''
            }
        },
        closeScanner() {
            this.dialogScanner = false
            this.$refs.scanner.codeReader.stream.getTracks().forEach(function (track) { track.stop() })
        },
        updateShowMultiple() {
            this.isShowCheckBox = !this.isShowCheckBox

            if (!this.isShowCheckBox) {
                this.updatedHeight = 200
                this.selected = []
            }
        },
        confirmReceiving(is, item) {
            let id = this.$router.currentRoute.params.id // current passed warehouse id
            let orders = item === undefined ? JSON.stringify(this.selected) : item.id 

            this.$router.push({
                name: 'ConfirmReceiving', 
                params: { id, orders },
                query: { is }
            })
        },
        onDecode(result) {
            this.enteredCode = result

            if (this.enteredCode !== '') {
                this.closeScanner()
            }
        },
        onLoaded() {
            console.log("load");
            this.hasLoaded = true
        },
    },
    async mounted() {
        //set current page
        this.$store.dispatch("page/setPage","mwms")
        console.log(this.$router.currentRoute);

        let ordersFromConfirm = this.$router.currentRoute.params.orders

        if (ordersFromConfirm !== undefined) {
            this.isShowCheckBox = true
            let parseOrder = JSON.parse(this.$router.currentRoute.params.orders)

            if (typeof parseOrder === 'number')
                this.selected.push(parseOrder)
            else 
                this.selected = parseOrder
        }

        let customer_id = (typeof this.getUser=='string') ? JSON.parse(this.getUser).id : this.getUser.id        
        let payload = {
            warehouse_id: this.$router.currentRoute.params.id,
            customer_id,
        }

        await this.fetchEmployeeOrders(payload)
    },
    updated() {}
};
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/mwms/mwms.scss';
</style>
