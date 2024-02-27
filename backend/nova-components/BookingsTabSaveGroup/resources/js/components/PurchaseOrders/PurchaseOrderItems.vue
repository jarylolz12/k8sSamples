<template>
    <div class="w-full h-screen ng-white flex justify-center items-center">
        <div v-if="ordersTitle === 'PO'" class="bg-white p-2">
            <div class="flex justify-between items-center border-bottom">
                <p class="po-title" v-if="isSetItems">
                    Set {{ (updatePurchaseOrderProductArray.length) + '/' + totalItems +' '+ ordersTitle + "'s" }}
                </p>
                <p class="po-title" v-else>Set {{ totalItems }} Items Now</p>
            </div>
            <tbody>
            <tr>
                <td class="purchase-order-table-first-td">
                    <table class="table table-bordered">
                        <tr :key="`selected-purchase-order-${itemIndex}`"
                            v-for="(purchaseOrder, itemIndex) in productItems">
                            <td class="p-2 cursor-pointer" @click="showProductTable(purchaseOrder,itemIndex)">{{purchaseOrder.po_number}}</td>
                            <td class="p-2">

                                <input
                                       v-if="purchaseOrder.greenClass"
                                       :checked="'true'"
                                       :class="purchaseOrder.greenClass ? 'green-checkboxes' : 'gray-checkboxes'"
                                       class="pointer-none"
                                       type="checkbox"/>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="purchase-order-table-second-td border-bottom">
                    <div class="mt-4">
                        <div v-if="products.length>0">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td>&nbsp;</td>
                                    <th style="padding: 0px 0px 0px 20px; background-color:white;" class="font-bold">{{po_number}} Order#</th>
                                    <th colspan="5" style="text-align: end; background-color:white; padding: 5px 20px;" class="font-bold">Shipping With {{shifl_ref}}</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Order No.</th>
                                    <th class="text-left">SKU</th>
                                    <th class="text-left">Units/Carton</th>
                                    <th class="text-left">Quantity (No. of Cartons)</th>
                                    <th class="text-left">To Ship Cartons</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr
                                    :key="`products-order-${index}`"
                                    v-for="(product, index) in products"
                                >
                                    <td>
                                        <input
                                            :checked="product.is_shipment"
                                            @change="onSelectProduct(index)"
                                            type="checkbox"
                                        />
                                    </td>
                                    <td>{{ product.product? product.product.name : '-' }}</td>
                                    <td>{{ po_number }}</td>
                                    <td>{{ product.product.sku }}</td>
                                    <td>{{ product.product? product.product.units_per_carton : '-' }}</td>
                                    <td>{{ product.unship_cartons }} / {{ product.quantity }}</td>
                                    <td>
                                        <input
                                            :value="product.ship_cartons"
                                            @change="e => onChangeShipCartons(e, index, product.unship_cartons, po_number)"
                                            class="ship-cartons-text cartons-input-tag"
                                            type="number"
                                            min="0"
                                            step="1"
                                        />
                                        <span class="text-danger" v-if="cartonError && product.ship_cartons == undefined"> Please select cartons.</span>
                                        <span class="text-danger display-none"
                                              :id="`carton-error-${po_number}-${index}`">
                                            There is only {{product.unship_cartons}} quantity available..!
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <button
                                            :disabled="setPurchaseOrderBtnDisabled"
                                            @click="updateProducts" class="btn-primary p-2 mb-1"
                                                style="float: right;"
                                                type="button" v-if="products.length>0">Set Purchase Order
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div v-else style="min-width: 700px;">
                            <table class="table table-bordered">
                                <tr class="flex justify-center">
                                    <td>Please confirm now..!</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
            <div class="flex justify-between items-center mt-3">
                <button @click="closePOItemsTableModal" class="btn-danger p-2" type="button">Cancel</button>
                <button :class="!confirmButton? 'pointer-none' : ''"
                        :disabled="!confirmButton"
                        @click="updatePurchaseOrderProductFunction"
                        class="btn-primary p-2"
                        type="button">
                    Confirm
                </button>
            </div>
        </div>
        <div v-if="ordersTitle === 'SO'" class="bg-white p-2">
            <div class="flex justify-between items-center border-bottom">
                <p class="po-title" v-if="isSetItems">
                    Set {{ (updatePurchaseOrderProductArray.length) + '/' + totalItems +' '+ ordersTitle + "'s" }}
                </p>
                <p class="po-title" v-else>Set {{ totalItems }} Items Now</p>
            </div>
            <tbody>
            <tr>
                <td class="purchase-order-table-first-td">
                    <table class="table table-bordered">
                        <tr :key="`selected-purchase-order-${itemIndex}`"
                            v-for="(purchaseOrder, itemIndex) in productItems">
                            <td class="p-2 cursor-pointer" @click="showProductTable(purchaseOrder,itemIndex)">{{purchaseOrder.po_number}}</td>
                            <td class="p-2">
                                <input
                                       v-if="purchaseOrder.greenClass"
                                       :checked="'true'"
                                       :class="purchaseOrder.greenClass ? 'green-checkboxes' : 'gray-checkboxes'"
                                       class="pointer-none"
                                       type="checkbox"/>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="purchase-order-table-second-td border-bottom">
                    <div class="mt-4">
                        <div v-if="products.length>0">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td>&nbsp;</td>
                                    <th style="padding: 0px 0px 0px 20px; background-color:white;" class="font-bold">{{po_number}} Order#</th>
                                    <th colspan="5" style="text-align: end; padding: 5px 20px; background-color:white;" class="font-bold">Shipping With {{shifl_ref}}</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Order No.</th>
                                    <th class="text-left">SKU</th>
                                    <th class="text-left">Units/Carton</th>
                                    <th class="text-left">Quantity (No. of Cartons)</th>
                                    <th class="text-left">To Ship Cartons</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr
                                    :key="`products-order-${index}`"
                                    v-for="(product, index) in products"
                                >
                                    <td>
                                        <input
                                            :checked="product.is_shipment"
                                            @change="onSelectProduct(index)"
                                            type="checkbox"
                                        />
                                    </td>
                                    <td>{{ product.product? product.product.name : '-' }}</td>
                                    <td>{{ po_number }}</td>
                                    <td>{{ product.product.sku }}</td>
                                    <td>{{ product.product? product.product.units_per_carton : '-' }}</td>
                                    <td>{{ product.unship_cartons }} / {{ product.quantity }}</td>
                                    <td>
                                        <input
                                            :value="product.ship_cartons"
                                            @change="e => onChangeShipCartons(e, index, product.unship_cartons, po_number)"
                                            class="ship-cartons-text cartons-input-tag"
                                            type="number"
                                            min="0"
                                            step="1"
                                        />
                                        <span class="text-danger" v-if="cartonError && product.ship_cartons == undefined"> Please select cartons.</span>
                                        <span class="text-danger display-none"
                                              :id="`carton-error-${po_number}-${index}`">
                                            There is only {{product.unship_cartons}} quantity available..!
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <button
                                            :disabled="setPurchaseOrderBtnDisabled"
                                            @click="updateProducts" class="btn-primary p-2 mb-1"
                                                style="float: right;"
                                                type="button" v-if="products.length>0">Set Sales Order
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div v-else style="min-width: 700px;">
                            <table class="table table-bordered">
                                <tr class="flex justify-center">
                                    <td>Please confirm now..!</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
            <div class="flex justify-between items-center mt-3">
                <button @click="closePOItemsTableModal" class="btn-danger p-2" type="button">Cancel</button>
                <button :class="!confirmButton? 'pointer-none' : ''"
                        :disabled="!confirmButton"
                        @click="updatePurchaseOrderProductFunction"
                        class="btn-primary p-2"
                        type="button">
                    Confirm
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
            "purchaseOrdersItems",
            "supplierKey",
            "shifl_ref"
        ],
        data() {
            return {
                products: [],
                productItems: [],
                totalItems: 0,
                purchaseOrder: [],
                po_number: null,
                viewIndex: null,
                isSetItems: false,
                confirmButton: false,
                updatePurchaseOrderProductArray: [],
                ordersTitle: "",
                setPurchaseOrderBtnDisabled: false,
                cartonError :false
            };
        },
        mounted() {
            this.productItems = [...this.purchaseOrdersItems]
            this.totalItems = this.purchaseOrdersItems.length
            this.onViewItem(this.productItems[0], 0);
            this.productItems.map(item => item.buyer_id == null && item.supplier_id !== null  ? this.ordersTitle = "PO" : this.ordersTitle = "SO")

        },
        methods: {
            onViewItem: function (po, index) {
                this.isSetItems = true
                this.products = po.products
                this.po_number = po.po_number
                this.purchaseOrder = po
                this.viewIndex = index
                this.productItems[index].greenClass = false
            },
            onSelectProduct: function (index) {
                const tempProduct = {...this.products[index]};
                tempProduct.is_shipment = !tempProduct.is_shipment;
                const tempProducts = [...this.products];
                tempProducts[index] = tempProduct;
                this.products = tempProducts;
            },
            onChangeShipCartons: function (e, index, unShipCartons, po_number) {
                const spanElement = $('#carton-error-' + po_number + '-' + index);
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
            closePOItemsTableModal: function () {
                this.products = [];
                this.$emit("closePOItemsTableModal", this.supplierKey);
            },
            updateProducts: async function () {
                this.products.filter(item => item.ship_cartons == undefined ? this.cartonError =true : this.cartonError= false);

                if(!this.cartonError){
                    const newIndex = (this.viewIndex + 1);
                    const newArray = {
                        purchaseOrderId: this.purchaseOrder.id,
                        products: [...this.products],
                        key: this.supplierKey,
                        productItemsCount: (this.productItems.length - newIndex),
                        totalProductItems: this.totalItems
                    };
                    this.updatePurchaseOrderProductArray.push(newArray);

                    this.productItems[this.viewIndex].greenClass = true;
                    if (this.viewIndex < this.productItems.length) {
                        if (newIndex === (this.productItems.length)) {
                            this.products = [];
                            this.confirmButton = true;
                        } else {
                            this.onViewItem(this.productItems[newIndex], newIndex);
                            this.viewIndex = newIndex;
                        }
                    }
                }
            },
            updatePurchaseOrderProductFunction: function () {
                for (let i = 0; i < this.updatePurchaseOrderProductArray.length; i++) {
                    this.$emit("updatePurchaseOrderProduct", this.updatePurchaseOrderProductArray[i]);
                }
            },
            showProductTable: function (po, index) {
                this.products      = po.products;
                this.po_number     = po.po_number;
                this.purchaseOrder = po;
                this.viewIndex = index;
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

    .purchase-order-table-first-td {
        padding: 0px;
        border-bottom: 1px solid;
        border-right: 1px solid;
    }

    purchase-order-table-second-td {
        padding: 0px;
        border-bottom: 1px solid;
    }

    .border-bottom {
        border-bottom: 1px solid;
    }

    .border-none {
        border: none;
    }

    .gray-checkboxes {
        accent-color: gray;
    }

    .green-checkboxes {
        accent-color: green;
    }

    .pointer-none {
        pointer-events: none;
    }
    .cartons-input-tag{
        display: block;
        margin-bottom: 5px;
    }
    .display-none{
        display: none;
    }
</style>
