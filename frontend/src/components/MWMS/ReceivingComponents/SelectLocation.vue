<template>
<!-- 3 -->
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

                <div class="receiving-body-wrapper" :style="`height: calc(100vh - ${updatedHeight}px); background-color: #fff;`">
                    <div class="receiving-assigned">
                        <div class="assigned-lists-wrapper">
                            <div class="assigned-lists">
                                <div class="items" style="border: none;">
                                    <div class="item-blue-bg">
                                        <p class="location-title mb-2 font-regular">Where are you receiving?</p>
                                        <p class="location-name font-semi-bold mb-0 dc-deftext">
                                            Select Location
                                        </p>
                                    </div>

                                    <div class="scan-location">
                                        <p class="mb-1 scan-location-title font-medium">Scan/Enter Location</p>
                                        <v-text-field 
                                            v-model="locationLabel" 
                                            class="text-fields select-product scan"
                                            placeholder="Scan or Enter Location Label" 
                                            append-icon="mdi-qrcode-scan"
                                            @click:append="testingAction"
                                            outlined
                                            v-bind:style="{ 'width': 'auto' }"
                                            hide-details="auto">
                                        </v-text-field>

                                        <button class="btn-white font-semi-bold mt-5 mx-auto btn-receive" @click="confirmLocation('floor')">
                                            Receive on the Floor
                                        </button>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fixed-footer-scanner" v-if="locationLabel !== ''">
                    <button class="btn-blue btn-confirm mt-0" @click="confirmLocation('location')">
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
    name: "SelectLocation",
    components: {
        // StreamBarcodeReader
    },
    data: () => ({
        locationLabel: "",
        id: null,
        isShowCheckBox: false,
        selected: [],
        isConfirmMultiple: false,
        updatedHeight: '130'
    }),
    watch: {
        locationLabel(val) {
            if (val !== '') {
                let defaultToSubtract = 130
                this.updatedHeight = defaultToSubtract + 70
            } else
                this.updatedHeight = 130
        }
    },
    computed: {
        ...mapGetters({}),
    },
    methods: {
        ...mapActions({}),
        onClickBack() {
            let is = this.$router.currentRoute.query.is
            let id = this.$router.currentRoute.params.id // current passed warehouse id
            let orders = this.$router.currentRoute.params.orders

            this.$router.push({
                name: 'ConfirmReceiving', 
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
        confirmLocation(isFor) {
            let id = this.$router.currentRoute.params.id // current passed warehouse id
            let orders = this.$router.currentRoute.params.orders
            let is = this.$router.currentRoute.query.is
            let location = isFor === 'location' ? this.locationLabel : 'Floor'

            this.$router.push({
                name: 'ConfirmedLocation', 
                params: { id, orders, location },
                query: { is }
            })
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage","mwms")

        console.log(this.$router);
    }
};
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/mwms/mwms.scss';
</style>
