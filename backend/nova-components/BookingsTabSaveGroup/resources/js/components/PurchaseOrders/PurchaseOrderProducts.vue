<template>
    <div class="w-full h-screen ng-white flex justify-center items-center">
        <div v-if="supplier.supplier_id !== undefined && supplier.supplier_id !== null && supplier.supplier_id !== ''" class="bg-white p-2">
            <div class="flex justify-between items-center">
                <p class="po-title">{{ purchaseOrder.po_number }} - Items</p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-left">Name</th>
                        <th class="text-left">SKU</th>
                        <th class="text-left">Units/Carton</th>
                        <th class="text-left">Quantity (No. of Cartons)</th>
                        <th class="text-left">To Ship Cartons</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(product, index) in products"
                        :key="`products-order-${index}`"
                    >
                        <td>
                            <input
                                type="checkbox"
                                :checked="product.is_shipment"
                                @change="onSelectProduct(index)"
                            />
                        </td>
                        <td>{{ product.product? product.product.name : '-' }}</td>
                        <td>{{ product.sku }}</td>
                        <td>{{ product.product? product.product.units_per_carton : '-' }}</td>
                        <td>{{ product.quantity }}</td>
                        <td>
                            <input
                                type="number"
                                class="ship-cartons-text cartons-input-tag"
                                :value="product.ship_cartons"
                                @change="e => onChangeShipCartons(e, index, product.unship_cartons)"
                                min="0"
                                step="1"
                            />
                            <span :id="`carton-error-${index}`" class="text-danger display-none">
                                There is only {{product.unship_cartons}} quantity available..!
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-end items-center mt-3 mr-2">
                <button
                    class="btn-primary p-2 mr-1"
                    type="button"
                    @click="updateProducts"
                    :disabled="setPurchaseOrderBtnDisabled"
                >
                    Set Purchase Order
                </button>
                <button
                    class="btn-danger p-2"
                    type="button"
                    @click="closePOProductsTableModal"
                >
                    Cancel
                </button>
            </div>
        </div>
        <div v-if="supplier.buyer_id !== undefined && supplier.buyer_id !== null && supplier.buyer_id !== ''" class="bg-white p-2">
            <div class="flex justify-between items-center">
                <p class="po-title">{{ purchaseOrder.po_number }} - Items</p>
            </div>
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th class="text-left">Name</th>
                    <th class="text-left">SKU</th>
                    <th class="text-left">Units/Carton</th>
                    <th class="text-left">Quantity (No. of Cartons)</th>
                    <th class="text-left">To Ship Cartons</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(product, index) in products"
                    :key="`products-order-${index}`"
                >
                    <td>
                        <input
                            type="checkbox"
                            :checked="product.is_shipment"
                            @change="onSelectProduct(index)"
                        />
                    </td>
                    <td>{{ product.product? product.product.name : '-' }}</td>
                    <td>{{ product.sku }}</td>
                    <td>{{ product.product? product.product.units_per_carton : '-' }}</td>
                    <td>{{ product.quantity }}</td>
                    <td>
                        <input
                            type="number"
                            class="ship-cartons-text cartons-input-tag"
                            :value="product.ship_cartons"
                            @change="e => onChangeShipCartons(e, index, product.unship_cartons)"
                            min="0"
                            step="1"
                        />
                        <span :id="`carton-error-${index}`" class="text-danger display-none">
                                There is only {{product.unship_cartons}} quantity available..!
                            </span>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="flex justify-end items-center mt-3 mr-2">
                <button
                    class="btn-primary p-2 mr-1"
                    type="button"
                    @click="updateProducts"
                    :disabled="setPurchaseOrderBtnDisabled"
                >
                    Set Sales Order
                </button>
                <button
                    class="btn-danger p-2"
                    type="button"
                    @click="closePOProductsTableModal"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import instance from "../utils/instance";
import axios from "axios";
import "vue-loaders/dist/vue-loaders.css";

export default {
    props: [
        "purchaseOrder",
        "isOpenPurchaseOrdersProductsModal",
        "supplier",
        "supplierKey",
    ],
    data() {
        return {
            products: [],
            setPurchaseOrderBtnDisabled: false,
        };
    },
    mounted(){
        this.products = [...this.purchaseOrder.products]
    },
    watch: {
        // 'isOpenPurchaseOrdersProductsModal' : function(value){
        //     if(value){
        //         this.products = [...this.purchaseOrder.products]
        //     }
        // }
    },
    methods: {
        onSelectProduct: function(index) {
            const tempProduct = {...this.products[index]};
            tempProduct.is_shipment = !tempProduct.is_shipment;
            const tempProducts = [...this.products];
            tempProducts[index] = tempProduct;
            this.products = tempProducts;
        },
        onChangeShipCartons: function(e, index, unShipCartons) {
            const spanElement = $('#carton-error-'+ index);
            if (e.target.value < 0) {
                e.target.value = 0;
                spanElement.addClass('display-none');
                this.setPurchaseOrderBtnDisabled = false;
            }else if (unShipCartons < e.target.value) {
                if (spanElement.hasClass('display-none')) {
                    spanElement.removeClass('display-none');
                    this.setPurchaseOrderBtnDisabled = true;
                }
            } else {
                spanElement.addClass('display-none');
                this.setPurchaseOrderBtnDisabled = false;
                const tempProduct = {...this.products[index]};
                const tempProducts = [...this.products];

                if (e.target.value > (tempProduct.unship_cartons + tempProduct.initial_ship_cartons)) {
                    tempProduct.ship_cartons = tempProduct.unship_cartons + tempProduct.initial_ship_cartons;
                } else {
                    tempProduct.ship_cartons = e.target.value;
                }
                tempProducts[index] = tempProduct;
                this.products = tempProducts;
            }
        },
        closePOProductsTableModal: function() {
            this.products = [];
            this.$emit("closePOProductsTableModal", this.supplierKey);
        },
        updateProducts: async function() {
            this.$emit("updatePurchaseOrderProduct", {
                purchaseOrderId: this.purchaseOrder.id,
                products: [...this.products],
                key: this.supplierKey
            });
        }
    },

};
</script>

<style scoped>
table {
    width: 100%;
    border: 1px solid #e1ecf0;
}

th {
    padding: 20px;
}

table tbody td {
    padding: 20px;
    padding-top: 5px;
    padding-bottom: 5px;
}

.po-title {
    font-size: 2rem;
    margin-bottom: 0;
}

.ship-cartons-text {
    outline: none;
    border: 2px solid #6d858f;
    border-radius: 10px;
    padding: 5px;
}

.loader {
    padding: 0;
    margin: 0;
}
.cartons-input-tag{
    display: block;
    margin-bottom: 5px;
}
.display-none{
    display: none;
}
</style>
