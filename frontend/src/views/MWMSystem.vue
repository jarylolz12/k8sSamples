<template>
    <div class="mwm-system-wrapper">
        <div class="mwm-system-content">
            <div class="loading-wrapper" v-if="typeof warehouseLists === 'undefined' && fetchWarehousesLoading">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>

            <div class="mwm-system-body" 
                v-if="typeof warehouseLists !== 'undefined' && warehouseLists !== null && warehouseLists.length > 0">
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <h3 class="text-center name pt-1">Welcome {{ getCurrentEmployeeName }}!</h3>
                    <v-autocomplete
                        :items="warehouseLists"
                        spellcheck="false" 
                        class="select-product shrink employee mb-5"
                        item-text="name"
                        item-value="id"
                        placeholder="Select Warehouse"
                        outlined
                        v-model="selectedWarehouse"
                        :menu-props="{ contentClass: 'product-lists-items mwms', bottom: true, offsetY: true, closeOnContentClick: false }"
                        hide-details="auto"
                        v-if="typeof warehouseLists !== 'undefined' && warehouseLists !== null && warehouseLists.length > 1"
                        :rules="rules">

                        <template v-slot:item="{ item }">
                            <div class="location-item-wrapper">
                                <p class="location-name font-semi-bold mb-0">{{ item.name }}</p>
                                <p class="location-address mb-0 dc-darkgrey">{{ item.address }}</p>
                            </div>
                        </template>
                    </v-autocomplete>
                    <p class="text-center sub-title mb-3">Select a function to proceed</p>

                    <div class="mwm-function-boxes">
                        <v-row>
                            <v-col cols="12" sm="12">
                                <div class="boxes-wrapper">
                                    <button class="boxes" @click="toPage('receiving')">
                                        <img src="@/assets/icons/mwm-receiving.svg" width="50px" height="50px">
                                        <p class="boxes-title font-medium mb-0">Receiving</p>
                                    </button>

                                    <button class="boxes" >
                                        <img src="@/assets/icons/mwm-put-away.svg" width="50px" height="50px">
                                        <p class="boxes-title font-medium mb-0">Putaway</p>
                                    </button>
                                </div>
                            </v-col>

                            <v-col cols="12" sm="12">
                                <div class="boxes-wrapper pt-0">
                                    <button class="boxes">
                                        <img src="@/assets/icons/mwm-pick-order.svg" width="50px" height="50px">
                                        <p class="boxes-title font-medium mb-0">Pick Order</p>
                                    </button>

                                    <button class="boxes" @click="toPage('palletizing')">
                                        <img src="@/assets/icons/mwm-palletization.svg" width="60px" height="50px">
                                        <p class="boxes-title font-medium mb-0">Palletization</p>
                                    </button>
                                </div>
                            </v-col>

                            <v-col cols="12" sm="12">
                                <div class="boxes-wrapper pt-0">
                                    <button class="boxes">
                                        <img src="@/assets/icons/mwm-move-pallet.svg" width="50px" height="50px">
                                        <p class="boxes-title font-medium mb-0">Move Pallet</p>
                                    </button>

                                    <button class="boxes">
                                        <img src="@/assets/icons/mwm-history.svg" width="50px" height="50px">
                                        <p class="boxes-title font-medium mb-0">History</p>
                                    </button>
                                </div>
                            </v-col>
                        </v-row>
                    </div>
                </v-form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
    name: "MWMSystem",
    components: {},
    data: () => ({
        valid: true,
        selectedWarehouse: '',
        rules: [
            (v) => !!v || "Input is required."
        ],
        fetchWarehousesLoading: false
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getEmployeeWarehouses: 'mwms/getEmployeeWarehouses',
            getEmployeeWarehousesLoading: 'mwms/getEmployeeWarehousesLoading'
        }),
        warehouseLists() {
            let lists = []

            if (typeof this.getEmployeeWarehouses !== 'undefined' && this.getEmployeeWarehouses !== null) {
                lists = this.getEmployeeWarehouses.data
            }

            return lists
        },
        getCurrentEmployeeName() {
            let name = ''
            name = (typeof this.getUser=='string') ? JSON.parse(this.getUser).name : this.getUser.name
            return name
        },
        isWarehouseListsEmpty() {
            let is_empty = false

            if (typeof this.warehouseLists !== 'undefined' && this.warehouseLists !== null && this.warehouseLists.length === 0) {
                is_empty = true
            } else 
                is_empty = false

            return is_empty
        }
    },
    methods: {
        ...mapActions({
            fetchEmployeeWarehouses: 'mwms/fetchEmployeeWarehouses'
        }),
        toPage(page) {
            if (this.$refs.form.validate()) {
                if (page !== '') {
                    this.$router.push({ 
                        name: 'Receiving', 
                        params: { id: this.selectedWarehouse }
                    })
                }
            }
        }
    },
    async mounted() {
        //set current page
        this.$store.dispatch("page/setPage","mwms")

        let customer_id = (typeof this.getUser=='string') ? JSON.parse(this.getUser).id : this.getUser.id
        
        try {
            this.fetchWarehousesLoading = true
            await this.fetchEmployeeWarehouses(customer_id)
            this.fetchWarehousesLoading = false
        } catch (error) {
            console.log(error);
            this.fetchWarehousesLoading = false
        }
    },
    updated() {
        console.log(this.warehouseLists);
    }
};
</script>

<style lang="scss">
@import '../assets/scss/inputs.scss';
@import '../assets/scss/buttons.scss';
@import '../assets/scss/pages_scss/mwms/mwms.scss';
</style>
