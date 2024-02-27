<template>
    <div class="w-full h-screen ng-white flex justify-center items-center">
        <div class="bg-white p-2">
            <div class="flex justify-between items-center">
                <p class="po-title">{{ purchaseOrder.po_number }} - Items</p>
            </div>
            <table>
                <thead>
                    <tr>
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
                        <td>{{ product.product.name }}</td>
                        <td>{{ product.sku }}</td>
                        <td>{{ product.product.units_per_carton }}</td>
                        <td>{{ product.quantity }} <small>Remaning: {{ product.unship_cartons }}</small></td>
                        <td>{{ product.ship_cartons }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-end items-center mt-3 mr-2">
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
import "vue-loaders/dist/vue-loaders.css";

export default {
    props: [
        "purchaseOrder",
        "isOpenPurchaseOrdersProductsModal",
        "supplierKey"
    ],
    data() {
        return {
            products: []
        };
    },
    watch: {
        'isOpenPurchaseOrdersProductsModal' : function(value){
            if(value){
                this.products = [...this.purchaseOrder.products]
            }
        }
    },
    methods: {
        onSelectProduct: function(index) {
            const tempProduct = {...this.products[index]};
            tempProduct.is_shipment = !tempProduct.is_shipment;
            const tempProducts = [...this.products];
            tempProducts[index] = tempProduct;
            this.products = tempProducts;
        },
        closePOProductsTableModal: function() {
            this.products = [];
            this.$emit("closePOProductsTableModal");
        },
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
</style>
