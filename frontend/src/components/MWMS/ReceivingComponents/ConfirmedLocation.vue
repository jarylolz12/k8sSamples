<template>
<!-- 4 -->
    <div class="mwm-system-wrapper">
        <div class="mwm-system-content">
            <div class="mwms-receving-wrapper">
                <div class="receiving-header">
                    <div class="d-flex justify-space-between align-center" style="width: 100%;">
                        <div class="d-flex align-center">
                            <button class="d-flex"> 
                                <v-icon class="back mr-2" @click="onClickBack()">mdi-chevron-left</v-icon>
                            </button>

                            <p class="mwms-page-title font-semi-bold">Receiving (#1234)</p>
                        </div>

                        <v-icon class="back mr-2">mdi-alert-outline</v-icon>
                    </div>
                </div>

                <div class="receiving-body-wrapper" style="height: unset; overflow: unset;">
                    <div class="receiving-assigned">
                        <div class="assigned-lists-wrapper">
                            <div class="assigned-lists">
                                <div class="items" style="border: none;">
                                    <div class="item-blue-bg mb-0">
                                        <p class="location-title mb-2 font-medium">Receive To Location</p>
                                        <p class="location-name font-semi-bold mb-0">
                                            <!-- A23-R12-S04-B2 -->
                                            {{ $router.currentRoute.params.location }}
                                        </p>
                                    </div>                        
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="receiving-skus-wrapper" 
                        :style="`height: calc(100vh - ${updatedHeight}px); overflow-y: auto;`">
                        <div class="unreceived-products">
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
                                        <p style="font-size: 14px;" class="dc-darkgrey mb-0">Instruction: Please receive and palletize.</p>
                                    </div>

                                    <div class="items">
                                        <div class="assigned-order">
                                            <p class="font-semi-bold mb-0 p-16 dc-blue">SKU 1002</p>
                                            <p class="font-semi-bold mb-0 p-16 dc-deftext">12 Units</p>
                                        </div>
                                        <p class="mb-0 dc-deftext">Sitting room throwpillow</p>
                                        <p style="font-size: 14px;" class="dc-darkgrey mb-0">Instruction: Please handle the product with care.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="received-products" v-if="receivedProducts.length > 0">
                            <p class="receiving-body-title font-medium d-flex justify-space-between align-center">
                                Received <span class="dc-green font-medium">2/3</span>
                            </p>

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
                                        <p style="font-size: 14px;" class="dc-darkgrey mb-0">Instruction: Please receive and palletize.</p>
                                    </div>

                                    <div class="items">
                                        <div class="assigned-order">
                                            <p class="font-semi-bold mb-0 p-16 dc-blue">SKU 1002</p>
                                            <p class="font-semi-bold mb-0 p-16 dc-deftext">12 Units</p>
                                        </div>
                                        <p class="mb-0 dc-deftext">Sitting room throwpillow</p>
                                        <p style="font-size: 14px;" class="dc-darkgrey mb-0">Instruction: Please handle the product with care.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fixed-footer-scanner">
                    <div>
                        <v-text-field 
                            v-model="skuLabel" 
                            class="text-fields select-product scan"
                            placeholder="Scan or Enter SKU or UPC" 
                            append-icon="mdi-qrcode-scan"
                            @click:append="testingAction"
                            outlined
                            v-bind:style="{ 'width': 'auto' }"
                            hide-details="auto">
                        </v-text-field>
                        
                        <button class="btn-blue mt-2 btn-confirm" v-if="skuLabel !== ''" @click="confirmToReceiveProducts">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
// import { StreamBarcodeReader } from "vue-barcode-reader";

export default {
    name: "ConfirmedLocation",
    components: {
        // StreamBarcodeReader
    },
    data: () => ({
        skuLabel: "",
        id: null,
        isShowCheckBox: false,
        selected: [],
        isConfirmMultiple: false,
        updatedHeight: '325',
        receivedProducts: []
    }),
    computed: {
        ...mapGetters({}),
    },
    watch: {
        skuLabel(val) {
            if (val !== '') {
                let defaultToSubtract = 325
                this.updatedHeight = defaultToSubtract + 54
            } else
                this.updatedHeight = 325
        }
    },
    methods: {
        ...mapActions({}),
        onClickBack() {
            let is = this.$router.currentRoute.query.is
            let id = this.$router.currentRoute.params.id // current passed warehouse id
            let orders = this.$router.currentRoute.params.orders

            this.$router.push({
                name: 'SelectLocation', 
                params: { id, orders },
                query: { is }
            })
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
        confirmToReceiveProducts() {
            let id = this.$router.currentRoute.params.id // current passed warehouse id
            let orders = this.$router.currentRoute.params.orders
            let is = this.$router.currentRoute.query.is
            let location = this.$router.currentRoute.params.location
            let sku = '1001'

            this.$router.push({
                name: 'ReceivingInfo', 
                params: { id, orders, location, sku },
                query: { is }
            })
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage","mwms")

        let hasReceived = this.$router.currentRoute.params.received
        this.receivedProducts = 
            typeof hasReceived !== 'undefined' && hasReceived !== null && JSON.parse(hasReceived).length > 0 ? 
            JSON.parse(hasReceived) : []

        console.log(this.$router);
    }
};
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/mwms/mwms.scss';
</style>
