<template>
<!-- 2 -->
    <div class="mwm-system-wrapper">
        <div class="mwm-system-content">
            <div class="mwms-receving-wrapper">
                <div class="receiving-header">
                    <div class="d-flex align-center">
                        <button class="d-flex"> 
                            <v-icon class="back mr-2" @click="onClickBack()">mdi-chevron-left</v-icon>
                        </button>

                        <p class="mwms-page-title font-semi-bold">Confirm to Receive</p>
                    </div>
                </div>

                <div class="receiving-body-wrapper" style="height: calc(100vh - 256px);">
                    <div class="receiving-assigned">
                        <div class="assigned-lists-wrapper">
                            <div class="assigned-lists">
                                <div class="items">
                                    <div class="items-order-info mb-2 d-flex justify-start align-start" v-if="isConfirmMultiple">
                                        <p class="font-medium mb-0 item-order-title">Location</p>
                                        <p class="mb-0 item-order-data">A23-R12-S04-B1, A23-R12-S04-B2</p>
                                    </div>

                                    <div class="items-order-info mb-2 d-flex justify-start align-center">
                                        <p class="font-medium mb-0 item-order-title">
                                            {{ isConfirmMultiple ? 'No of Orders' : 'Order' }}
                                        </p>
                                        <p class="mb-0 item-order-data">3</p>
                                    </div>

                                    <div class="items-order-info mb-2 d-flex justify-start align-center" v-if="isConfirmMultiple">
                                        <p class="font-medium mb-0 item-order-title">No of SKUs</p>
                                        <p class="mb-0 item-order-data">9</p>
                                    </div>

                                    <div class="items-order-info mb-2 d-flex justify-start align-center">
                                        <p class="font-medium mb-0 item-order-title">WH Customer</p>
                                        <p class="mb-0 item-order-data">James Cooper Limited</p>
                                    </div>

                                    <div class="items-order-info mb-2 d-flex justify-start align-center" v-if="!isConfirmMultiple">
                                        <p class="font-medium mb-0 item-order-title">Arrived At</p>
                                        <p class="mb-0 item-order-data">25 Apr, 2022  1:30 AM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- for single orders -->
                        <div class="receiving-skus-wrapper" v-if="!isConfirmMultiple">
                            <p class="receiving-body-title font-medium">SKUs to Receive</p>
                            <div class="assigned-lists-wrapper">
                                <div class="assigned-lists">
                                    <div class="items">
                                        <div class="assigned-order">
                                            <p class="font-semi-bold mb-0 p-16 dc-blue">SKU 1001</p>
                                            <p class="font-semi-bold mb-0 p-16 dc-deftext">5 Cartons</p>
                                        </div>
                                        <p class="mb-0 dc-deftext">Official Paperclip</p>
                                    </div>

                                    <div class="items">
                                        <div class="assigned-order">
                                            <p class="font-semi-bold mb-0 p-16 dc-blue">SKU 1003</p>
                                            <p class="font-semi-bold mb-0 p-16 dc-deftext">15 Cartons</p>
                                        </div>
                                        <p class="mb-0 dc-deftext">Standing Ringlights </p>
                                    </div>

                                    <div class="items">
                                        <div class="assigned-order">
                                            <p class="font-semi-bold mb-0 p-16 dc-blue">SKU 1002</p>
                                            <p class="font-semi-bold mb-0 p-16 dc-deftext">12 Units</p>
                                        </div>
                                        <p class="mb-0 dc-deftext">Sitting room throwpillow</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- For multiple orders -->
                        <div v-if="isConfirmMultiple">
                            <div class="receiving-skus-wrapper" v-for="item in orderLists" :key="item.order_id">
                                <p class="receiving-body-title font-medium">SKUs of Order {{ item.order_id }}</p>
                                <div class="assigned-lists-wrapper">
                                    <div class="assigned-lists">
                                        <div class="items" v-for="product in item.skus" :key="product.id">
                                            <div class="assigned-order">
                                                <p class="font-semi-bold mb-0 p-16 dc-blue">
                                                    {{ product.name }}
                                                </p>
                                                <p class="font-semi-bold mb-0 p-16 dc-deftext" 
                                                    v-if="product.cartons !== 0">
                                                    {{ product.cartons }} Cartons
                                                </p>

                                                <p class="font-semi-bold mb-0 p-16 dc-deftext" 
                                                    v-if="product.units !== 0">
                                                    {{ product.units }} Units
                                                </p>
                                            </div>
                                            <p class="mb-0 dc-deftext">{{ product.description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fixed-footer-scanner">
                    <div class="d-flex justify-space-between align-center">
                        <button class="btn-white btn-more mr-2 font-medium" @click="selectMoreOrders" 
                            style="width: 150px; height: 45px !important;">
                            Select More
                        </button>

                        <v-text-field 
                            v-model="text" 
                            class="text-fields select-product scan"
                            placeholder="Scan More" 
                            append-icon="mdi-qrcode-scan"
                            @click:append="testingAction"
                            outlined
                            v-bind:style="{ 'width': 'auto' }"
                            hide-details="auto">
                        </v-text-field>
                    </div>

                    <button class="btn-blue btn-confirm" @click="startReceiving">
                        Start Receiving
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
// import { StreamBarcodeReader } from "vue-barcode-reader";

export default {
    name: "ConfirmReceiving",
    components: {
        // StreamBarcodeReader
    },
    data: () => ({
        text: "",
        id: null,
        isShowCheckBox: false,
        selected: [],
        isConfirmMultiple: false,
        orderLists: [
            {
                order_id: '1234',
                skus: [
                    {
                        id: 1,
                        name: 'SKU1001',
                        units: 0,
                        cartons: 5,
                        description: 'Official Paperclip'
                    },
                    {
                        id: 2,
                        name: 'SKU1002',
                        units: 0,
                        cartons: 15,
                        description: 'Standing Ringlights'
                    }
                ]
            },
            {
                order_id: '1237',
                skus: [
                    {
                        id: 3,
                        name: 'SKU1002',
                        cartons: 0,
                        units: 8,
                        description: 'Sitting room throwpillow'
                    },
                ]
            },
            {
                order_id: '1239',
                skus: [
                    {
                        id: 4,
                        name: 'SKU1025',
                        units: 0,
                        cartons: 12,
                        description: 'Test Description 1'
                    },
                    {
                        id: 5,
                        name: 'SKU1026',
                        units: 0,
                        cartons: 12,
                        description: 'Test Description 2'
                    },
                    {
                        id: 6,
                        name: 'SKU1027',
                        cartons: 0,
                        units: 11,
                        description: 'Test Description 3'
                    },
                ]
            }
        ]
    }),
    computed: {
        ...mapGetters({}),
    },
    methods: {
        ...mapActions({}),
        onClickBack() {
            let id = this.$router.currentRoute.params.id
            this.$router.push({ name: 'Receiving', params: { id }})
        },
        onDecode(a, b, c) {
            console.log(a, b, c);
            this.text = a;
            if (this.id) clearTimeout(this.id);
            this.id = setTimeout(() => {
                if (this.text === a) {
                    this.text = "";
                }
            }, 5000);
        },
        onLoaded() {
            console.log("load");
        },
        testingAction() {
            console.log("clicked!");
        },
        selectMoreOrders() {
            let id = this.$router.currentRoute.params.id
            let selectedOrders = this.$router.currentRoute.params.orders
            
            this.$router.push({
                name: 'Receiving', 
                params: { id, orders: selectedOrders }
            })
        },
        startReceiving() {
            let is = this.$router.currentRoute.query.is
            let id = this.$router.currentRoute.params.id // current passed warehouse id
            let orders = this.$router.currentRoute.params.orders

            this.$router.push({
                name: 'SelectLocation', 
                params: { id, orders },
                query: { is }
            })
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage","mwms")

        let is_confirm = new URL(location.href).searchParams.get('is')
        this.isConfirmMultiple = is_confirm !== null && is_confirm === 'multiple' ? true : false

        console.log(this.$router);
    }
};
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/mwms/mwms.scss';
</style>
