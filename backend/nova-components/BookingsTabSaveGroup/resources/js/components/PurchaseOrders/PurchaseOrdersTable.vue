<template>
    <div class="w-full h-screen ng-white flex justify-center items-center">
        <div v-if="supplier.supplier_id !== undefined && supplier.supplier_id !== null && supplier.supplier_id !== ''" class="bg-white p-2">
            <div class="flex justify-between items-center">
                <p class="po-title">Purchase Orders</p>
                <div class="flex items-center">
                    <input
                        class="search-text"
                        type="text"
                        name="searchText"
                        placeholder="Enter PO Number"
                        v-model="searchText"
                    />
                </div>
            </div>
            <div>
                <small>Use the search bar to show other PO's</small>
            </div>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-left">Order Number</th>
                        <th class="text-left">Supplier</th>
                        <th class="text-left">Customer</th>
                        <th class="text-left">Warehouse</th>
                        <th class="text-left">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(purchaseOrder, index) in tempPurchaseOrders"
                        :key="`purchase-order-${index}`"
                    >
                        <td>
                            <input
                                type="checkbox"
                                :checked="purchaseOrder.isSelected"
                                @change="onSelectPurchaseOrder(index)"
                            />
                        </td>
                        <td>{{ purchaseOrder.po_number }}</td>
                        <td>{{ purchaseOrder.supplier ? purchaseOrder.supplier : purchaseOrder.customer ?
                            purchaseOrder.customer : 'N/A' }}
                        </td>
                        <td>{{ purchaseOrder.supplier ? purchaseOrder.customer : purchaseOrder.buyer}}</td>
                        <td>{{ purchaseOrder.warehouse.name }}</td>
                        <td>{{ purchaseOrder.status }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-3">
                <span
                    class="po-label"
                    v-for="(po, index) in selectedPurchaseOrders"
                    :key="`po-number-${index}`"
                >
                    {{ po.po_number }}
                    <button
                        class="po-label-btn"
                        type="button"
                        @click="removePOFromSelected(po)"
                    >
                        X
                    </button>
                </span>
            </div>
            <div class="flex justify-end items-center mt-3 mr-2">
                <vue-loaders
                    v-show="isLoading"
                    name="line-spin-fade-loader"
                    color="black"
                    scale="0.3"
                >
                </vue-loaders>
                <button
                    class="btn-primary p-2 mr-1"
                    type="button"
                    @click="onAddPOs"
                    :disabled="selectedPurchaseOrders.length === 0 || isLoading"
                >
                    Add PO's
                </button>
                <button
                    class="btn-danger p-2"
                    type="button"
                    @click="closePOTableModal"
                >
                    Cancel
                </button>
            </div>
        </div>
        <div v-if="supplier.buyer_id !== undefined && supplier.buyer_id !== null && supplier.buyer_id !== ''" class="bg-white p-2">
            <div class="flex justify-between items-center">
                <p class="po-title">Sales Orders</p>
                <div class="flex items-center">
                    <input
                        class="search-text"
                        name="searchText"
                        placeholder="Enter SO Number"
                        type="text"
                        v-model="searchText"
                    />
                </div>
            </div>
            <div>
                <small>Use the search bar to show other SO's</small>
            </div>
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th class="text-left">Order Number</th>
                    <th class="text-left">Buyer</th>
                    <th class="text-left">Customer</th>
                    <th class="text-left">Warehouse</th>
                    <th class="text-left">Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr
                    :key="`sales-order-${index}`"
                    v-for="(salesOrder, index) in tempSalesOrders"
                >
                    <td>
                        <input
                            :checked="salesOrder.isSelected"
                            @change="onSelectSalesOrder(index)"
                            type="checkbox"
                        />
                    </td>
                    <td>{{ salesOrder.po_number }}</td>
                    <td>{{ salesOrder.buyer ? salesOrder.buyer :salesOrder.customer ? salesOrder.customer: 'N/A' }}</td>
                    <td>{{ salesOrder.buyer? salesOrder.customer : salesOrder.supplier}}</td>
                    <td>{{ salesOrder.warehouse.name }}</td>
                    <td>{{ salesOrder.status }}</td>
                </tr>
                </tbody>
            </table>
            <div class="mt-3">
                <span
                    :key="`po-number-${index}`"
                    class="po-label"
                    v-for="(po, index) in selectedSalesOrders"
                >
                    {{ po.po_number }}
                    <button
                        @click="removeSOFromSelected(po)"
                        class="po-label-btn"
                        type="button"
                    >
                        X
                    </button>
                </span>
            </div>
            <div class="flex justify-end items-center mt-3 mr-2">
                <vue-loaders
                    color="black"
                    name="line-spin-fade-loader"
                    scale="0.3"
                    v-show="isLoading"
                >
                </vue-loaders>
                <button
                    :disabled="selectedSalesOrders.length === 0 || isLoading"
                    @click="onAddSOs"
                    class="btn-primary p-2 mr-1"
                    type="button"
                >
                    Add SO's
                </button>
                <button
                    @click="closePOTableModal"
                    class="btn-danger p-2"
                    type="button"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import lscache from "lscache";
import "vue-loaders/dist/vue-loaders.css";
import jQuery from "jquery";

lscache.setExpiryMilliseconds(1000);
export default {
    props: [
        "customerId",
        "purchaseOrders",
        "isOpenPurchaseOrdersModal",
        "supplier",
        "supplierKey"
    ],
    data() {
        return {
            timeoutRef: null,
            searchText: "",
            tempPurchaseOrders: [],
            tempSalesOrders: [],
            selectedPurchaseOrders: this.purchaseOrders || [],
            selectedSalesOrders: this.purchaseOrders || [],
            axiosSource: {},
            isLoading: false
        };
    },
    mounted(){
        this.supplier.supplier_id !== null ?this.getPurchaseOrders(this.customerId) : this.getSalesOrders(this.customerId);

    },
    watch: {
        searchText: function(value) {
            if (this.timeoutRef) {
                clearTimeout(this.timeoutRef);
            }
            if (this.supplier.supplier_id !== null) {
                this.timeoutRef = setTimeout(() => {
                    this.getPurchaseOrders(this.customerId, value);
                }, 800);
            } else {
                this.timeoutRef = setTimeout(() => {
                    this.getSalesOrders(this.customerId, value);
                }, 800);
            }

        },
        // isOpenPurchaseOrdersModal: function(value) {
        //     console.log("What is this shit", value)
        //     if(value){
        //          this.selectedPurchaseOrders = this.purchaseOrders
        //         if(!this.customerId){
        //             return this.$toasted.show(
        //                 "Select a customer first.",
        //                 { type: "error" }
        //             );
        //         } else {
        //             this.getPurchaseOrders(this.customerId);
        //         }
        //     }

        //     // if (value) {
        //     //     if (!this.customerId) {
        //     //         return this.$toasted.show(
        //     //             "Select a customer first.",
        //     //             { type: "error" }
        //     //         );
        //     //     }
        //     //     this.getPurchaseOrders(this.customerId);
        //     // }
        // }
    },
    computed: {
        csrfToken() {
            return jQuery('meta[name="csrf-token"]').attr('content');
        },
    },
    methods: {
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
        getPurchaseOrders: async function(customerId, searchText = "") {
            this.isLoading = true;
            const CancelToken = axios.CancelToken;
            const source = CancelToken.source();
            this.axiosSource = source;

            try {
                let buyerIds, conneCustId = [];
                let supplierId = this.supplier.supplier_id;

                await fetch(`/custom/connected-customer/${customerId}/buyer/${supplierId}`, {
                    token: this.csrfToken
                })
                    .then(this.handleResponse)
                    .then(response => {
                        buyerIds = response.results.ids;
                        conneCustId = response.results.connected_customer;
                    })

                const result = await Nova.request().get(
                    `/custom/po/customers/${customerId}/purchase-orders`,
                    {
                        params: {
                            searchText: searchText,
                            supplierId: supplierId,
                            buyerIds: buyerIds,
                            conneCustId: conneCustId,
                        },
                        cancelToken: this.axiosSource.token
                    }
                );
                this.tempPurchaseOrders = result.data;

                const tempPurchaseOrders = [];
                for (const purchaseOrder of this.tempPurchaseOrders) {
                    const customer = await this.getCustomer(
                        purchaseOrder.customer_id
                    );
                    let buyer ,supplier;
                    if (purchaseOrder.buyer_id) {
                         buyer = await this.getBuyer(
                            purchaseOrder.buyer_id
                        );
                    } else if (purchaseOrder.supplier_id) {
                         supplier = await this.getSupplier(
                            purchaseOrder.supplier_id
                        );
                    }

                    const checkIfPOisSelected = this.selectedPurchaseOrders.find(
                        tempPO => tempPO.id === purchaseOrder.id
                    );
                    if (checkIfPOisSelected) {
                        tempPurchaseOrders.push({
                            ...purchaseOrder,
                            customer,
                            supplier,
                            buyer,
                            isSelected: true
                        });
                    } else {
                        tempPurchaseOrders.push({
                            ...purchaseOrder,
                            customer,
                            supplier,
                            buyer,
                            isSelected: false
                        });
                    }
                }

                this.tempPurchaseOrders = tempPurchaseOrders;
            } catch (err) {
                this.$toasted.show(
                    "Unable to fetch customer purchase orders.",
                    { type: "error" }
                );
            }

            this.isLoading = false;
        },
        getSalesOrders: async function(customerId, searchText = "") {
            this.isLoading = true;
            const CancelToken = axios.CancelToken;
            const source = CancelToken.source();
            this.axiosSource = source;

            try {
                let supplierIds, conneCustId = [];
                let buyerId = this.supplier.buyer_id;
                await fetch(`/custom/connected-customer/${customerId}/supplier/${buyerId}`, {
                    token: this.csrfToken
                })
                    .then(this.handleResponse)
                    .then(response => {
                        supplierIds = response.results.ids;
                        conneCustId = response.results.connected_customer;
                    })

                const result = await Nova.request().get(
                    `/custom/po/customers/${customerId}/sales-orders`,
                    {
                        params: {
                            searchText: searchText,
                            buyerId: buyerId,
                            supplierIds: supplierIds,
                            conneCustId:conneCustId
                        },
                        cancelToken: this.axiosSource.token
                    }
                );
                this.tempSalesOrders = result.data;

                const tempSalesOrders = [];
                for (const purchaseOrder of this.tempSalesOrders) {
                    const customer = await this.getCustomer(
                        purchaseOrder.customer_id
                    );
                    let buyer ,supplier;
                    if (purchaseOrder.buyer_id) {
                        buyer = await this.getBuyer(
                            purchaseOrder.buyer_id
                        );
                    } else if (purchaseOrder.supplier_id) {
                        supplier = await this.getSupplier(
                            purchaseOrder.supplier_id
                        );
                    }

                    const checkIfSOisSelected = this.selectedSalesOrders.find(
                        tempPO => tempPO.id === purchaseOrder.id
                    );
                    if (checkIfSOisSelected) {
                        tempSalesOrders.push({
                            ...purchaseOrder,
                            customer,
                            buyer,
                            supplier,
                            isSelected: true
                        });
                    } else {
                        tempSalesOrders.push({
                            ...purchaseOrder,
                            customer,
                            buyer,
                            supplier,
                            isSelected: false
                        });
                    }
                }

                this.tempSalesOrders = tempSalesOrders;
            } catch (err) {
                this.$toasted.show(
                    "Unable to fetch customer sales orders.",
                    { type: "error" }
                );
            }

            this.isLoading = false;
        },

        getCustomer: async function(value) {
            const customer = lscache.get(`customer_id_${value}`);
            if (!customer) {
                const result = await Nova.request().get(
                    `/custom/customers/${value}`
                );
                lscache.set(
                    `customer_id_${value}`,
                    result.data.company_name,
                    21600
                );
                return result.data.name;
            }
            return customer;
        },

        getSupplier: async function(value) {
            const supplier = lscache.get(`supplier_id_${value}`);
            if (!supplier) {
                const result = await Nova.request().get(
                    `/custom/suppliers/${value}`
                );
                lscache.set(
                    `supplier_id_${value}`,
                    result.data.company_name,
                    21600
                );
                return result.data.name;
            }
            return supplier;
        },

        getBuyer: async function(value) {
            const buyer = lscache.get(`buyer_id_${value}`);
            if (!buyer) {
                const result = await Nova.request().get(
                    `/custom/customer-buyers/${value}`
                );
                lscache.set(
                    `buyer_id_${value}`,
                    result.data.company_name,
                    21600
                );
                return result.data.company_name;
            }
            return buyer;
        },

        onSelectPurchaseOrder: function(index) {
            const tempPurchaseOrders = [...this.tempPurchaseOrders];
            const purchaseOrder = this.tempPurchaseOrders.find(
                (po, idx) => idx === index
            );
            purchaseOrder.isSelected = !purchaseOrder.isSelected;
            tempPurchaseOrders[index] = purchaseOrder;
            this.tempPurchaseOrders = [...tempPurchaseOrders];

            if (purchaseOrder.isSelected) {
                this.selectedPurchaseOrders = [
                    ...this.selectedPurchaseOrders,
                    purchaseOrder
                ];
            } else {
                const selectedPurchaseOrders = this.selectedPurchaseOrders.filter(
                    (po, index) => po.id !== purchaseOrder.id
                );
                this.selectedPurchaseOrders = selectedPurchaseOrders;
            }
        },
        onSelectSalesOrder: function(index) {
            const tempSalesOrders = [...this.tempSalesOrders];
            const purchaseOrder = this.tempSalesOrders.find(
                (po, idx) => idx === index
            );
            purchaseOrder.isSelected = !purchaseOrder.isSelected;
            tempSalesOrders[index] = purchaseOrder;
            this.tempSalesOrders = [...tempSalesOrders];

            if (purchaseOrder.isSelected) {
                this.selectedSalesOrders = [
                    ...this.selectedSalesOrders,
                    purchaseOrder
                ];
            } else {
                const selectedSalesOrders = this.selectedSalesOrders.filter(
                    (po, index) => po.id !== purchaseOrder.id
                );
                this.selectedSalesOrders = selectedSalesOrders;
            }
        },
        removePOFromSelected: function(po) {
            const temp = this.selectedPurchaseOrders.filter(
                poTemp => poTemp.id !== po.id
            );
            this.selectedPurchaseOrders = temp;

            this.tempPurchaseOrders = this.tempPurchaseOrders.map(poTemp => {
                if (poTemp.id === po.id) {
                    return {
                        ...poTemp,
                        isSelected: false
                    };
                } else {
                    return poTemp;
                }
            });
        },
        removeSOFromSelected: function(po) {
            const temp = this.selectedSalesOrders.filter(
                poTemp => poTemp.id !== po.id
            );
            this.selectedSalesOrders = temp;

            this.tempSalesOrders = this.tempSalesOrders.map(poTemp => {
                if (poTemp.id === po.id) {
                    return {
                        ...poTemp,
                        isSelected: false
                    };
                } else {
                    return poTemp;
                }
            });
        },
        closePOTableModal: function() {
            this.$emit("closePOTableModal", this.supplierKey);
        },
        onAddPOs: function() {
            this.isLoading = true
            this.$emit("onAddPOs", {
                selectedPurchaseOrders: this.selectedPurchaseOrders,
                key: this.supplierKey
            });
        },
        onAddSOs: function() {
            this.isLoading = true
            this.$emit("onAddSOs", {
                selectedSalesOrders: this.selectedSalesOrders,
                key: this.supplierKey
            });
        }
    }
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

.po-label {
    background: #e1ecf0;
    border-radius: 90px;
    padding: 5px;
    padding-left: 6px;
    padding-right: 6px;
    margin-right: 3px;
}

.po-label-btn {
    outline: none;
}

.search-text {
    outline: none;
    border: 2px solid #6d858f;
    border-radius: 10px;
    padding: 5px;
}
</style>
