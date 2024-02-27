<template>
<!-- 5 -->
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

                <div class="receiving-body-wrapper" style="height: calc(100vh - 200px); background-color: #fff;">
                    <div class="receiving-assigned">
                        <div class="assigned-lists-wrapper">
                            <div class="assigned-lists">
                                <div class="items" style="border: none;">
                                    <div class="item-blue-bg">
                                        <p class="location-title mb-2 font-regular">Receive SKU 9876543</p>
                                        <p class="location-name font-semi-bold mb-0 dc-deftext">
                                            Ghost-Plas-GRN
                                        </p>
                                    </div>

                                    <div class="receiving-info-wrapper">
                                        <p class="receiving-amount font-semi-bold mb-0 dc-deftext">
                                            You should receive <br/> 10 cartons
                                        </p>
                                    </div>

                                    <div class="scan-location">
                                        <p class="mb-1 scan-location-title font-medium">Received Quantity</p>
                                        <div class="d-flex align-center">
                                            <div style="flex: 1;" class="receive-qty-buttons mr-2">
                                                <button class="btn-white minus font-medium" @click="onClickAdjustQty('decrease')" :disabled="receivedQty===1">-</button>
                                                <v-text-field 
                                                    class="text-fields select-product received"
                                                    placeholder="1" 
                                                    v-model="receivedQty"
                                                    outlined
                                                    v-bind:style="{ 'width': 'auto' }"
                                                    hide-details="auto">
                                                </v-text-field>
                                                <button class="btn-white plus font-medium" @click="onClickAdjustQty('increase')" :disabled="receivedQty===expectedQty">+</button>
                                            </div>

                                            <v-select
                                                :items="items"
                                                class="select-product shrink text-capitalize mt-0"
                                                item-text="name"
                                                item-value="value"
                                                placeholder="Carton"
                                                outlined
                                                v-model="unitType"
                                                hide-details="auto"
                                                v-bind:style="{ 'width': '120px' }"
                                                :menu-props="{ contentClass: 'product-lists-items unit-type-mobile', bottom: true, offsetY: true, closeOnContentClick: true }">
                                            </v-select>
                                        </div>

                                        <button class="btn-white font-semi-bold mt-5 mx-auto btn-receive" @click="onClickAdjustQty('all')">
                                            Receive All
                                        </button>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fixed-footer-scanner">
                    <button class="btn-blue btn-confirm mt-0" @click="confirmReceive">
                        Confirm
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
    name: "ReceivingInfo",
    components: {
        // StreamBarcodeReader
    },
    data: () => ({
        skuLabel: "",
        id: null,
        isShowCheckBox: false,
        selected: [],
        isConfirmMultiple: false,
        updatedHeight: '368',
        unitType: 'carton',
        items: [
            {
                value: 'carton',
                name: 'Carton'
            },
            {
                value: 'unit',
                name: 'Unit'
            }
        ],
        receivedQty: 1,
        expectedQty: 10
    }),
    computed: {
        ...mapGetters({}),
    },
    watch: {
        skuLabel(val) {
            if (val !== '') {
                let defaultToSubtract = 368
                this.updatedHeight = defaultToSubtract + 54
            } else
                this.updatedHeight = 368
        }
    },
    methods: {
        ...mapActions({}),
        onClickBack() {
            let id = this.$router.currentRoute.params.id // current passed warehouse id
            let orders = this.$router.currentRoute.params.orders
            let is = this.$router.currentRoute.query.is
            let location = 'A23-R12-S04-B2'

            this.$router.push({
                name: 'ConfirmedLocation', 
                params: { id, orders, location },
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
        onClickAdjustQty(isFor) {
            if (isFor === 'increase') {
                this.receivedQty++
            } else if (isFor === 'decrease') {
                if (this.receivedQty > 1) {
                    this.receivedQty--
                }
            } else {
                this.receivedQty = this.expectedQty
            }
        },
        confirmReceive() {
            let id = this.$router.currentRoute.params.id // current passed warehouse id
            let orders = this.$router.currentRoute.params.orders
            let is = this.$router.currentRoute.query.is
            let location = 'A23-R12-S04-B2'
            let received = [1]

            received = JSON.stringify(received)

            this.$router.push({
                name: 'ConfirmedLocation', 
                params: { id, orders, location, received },
                query: { is }
            })
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage","mwms")

        // let is_confirm = new URL(location.href).searchParams.get('is')
        // this.isConfirmMultiple = is_confirm !== null && is_confirm === 'multiple' ? true : false

        console.log(this.$router);
    }
};
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/mwms/mwms.scss';
</style>
