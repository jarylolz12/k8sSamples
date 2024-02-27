<!-- @format -->

<template>
	<div v-resize="onResize" class="shipment-products">
		<div
			class="po-counter"
			v-if="getShipmentProducts && getShipmentProducts.length > 0"
		>
			<h2>{{ getShipmentProducts.length }} Purchase Orders</h2>
		</div>
		<div
			class="shipment-products-wrapper py-1"
			v-for="(product, i) in productsDetails"
			:key="i"
		>
			<div class="po-details">
				<div class="products-header-wrapper" v-if="!isMobile">
					<div class="products-number">
						PO #{{ product.po_number }}
						<span
							:class="{
								'product-type-full': product.status == 'shipped',
								'product-type-partial': product.status == 'partial_shipped',
							}"
						>
							{{ product.status == "shipped" ? "Full" : "" }}
							{{ product.status == "partial_shipped" ? "Partial" : "" }}
						</span>
					</div>
					<div class="product-date">
						<div>
							Issue Date: <span>{{ dateFormat(product.created_at) }}</span>
						</div>
						<div>
							Cargo Ready:
							<span>{{
								product.cargo_ready_date !== null
									? dateFormat(product.cargo_ready_date)
									: "N/A"
							}}</span>
						</div>
						<v-menu bottom left offset-y content-class="outbound-lists-menu">
							<template v-slot:activator="{ on, attrs }">
								<v-btn class="btn-white menu-btn" icon v-bind="attrs" v-on="on">
									<v-icon>mdi-dots-horizontal</v-icon>
								</v-btn>
							</template>

							<v-list class="outbound-lists">
								<v-list-item>
									<v-list-item-title @click="viewPo(product)">
										<img
											class="mr-2"
											src="@/assets/icons/visibility-po.svg"
											width="16px"
											height="16px"
										/>
										<span>View</span>
									</v-list-item-title>
								</v-list-item>
								<!-- <v-list-item>
								<v-list-item-title>
									<img
										class="mr-2"
										src="@/assets/icons/edit-po.svg"
										width="16px"
										height="16px"
									/>
									<span>Edit Contents</span>
								</v-list-item-title>
							</v-list-item> -->

								<v-list-item>
									<v-list-item-title @click="downloadPo(product)">
										<img
											class="mr-2"
											src="@/assets/icons/download-po.svg"
											width="16px"
											height="16px"
										/>
										<span>Download</span>
									</v-list-item-title>
								</v-list-item>
							</v-list>
						</v-menu>
					</div>
				</div>

				<div class="products-header-wrapper" v-if="isMobile">
					<div class="products-number">
						<p>
							PO #{{ product.po_number }}
							<span
								:class="{
									'product-type-full': product.status == 'shipped',
									'product-type-partial': product.status == 'partial_shipped',
								}"
							>
								{{ product.status == "shipped" ? "Full" : "" }}
								{{ product.status == "partial_shipped" ? "Partial" : "" }}
							</span>
						</p>
						<v-menu bottom left offset-y content-class="outbound-lists-menu">
							<template v-slot:activator="{ on, attrs }">
								<v-btn class="btn-white menu-btn" icon v-bind="attrs" v-on="on">
									<v-icon>mdi-dots-horizontal</v-icon>
								</v-btn>
							</template>

							<v-list class="outbound-lists">
								<v-list-item @click="viewPo(product)">
									<v-list-item-title>
										<img
											class="mr-2"
											src="@/assets/icons/visibility-po.svg"
											width="16px"
											height="16px"
										/>
										<span>View</span>
									</v-list-item-title>
								</v-list-item>
								<!-- <v-list-item>
								<v-list-item-title>
									<img
										class="mr-2"
										src="@/assets/icons/edit-po.svg"
										width="16px"
										height="16px"
									/>
									<span>Edit Contents</span>
								</v-list-item-title>
							</v-list-item> -->

								<v-list-item>
									<v-list-item-title @click="downloadPo(product)">
										<img
											class="mr-2"
											src="@/assets/icons/download-po.svg"
											width="16px"
											height="16px"
										/>
										<span>Download</span>
									</v-list-item-title>
								</v-list-item>
							</v-list>
						</v-menu>
					</div>
					<div class="product-date">
						<p>
							Issue: <span>{{ dateFormat(product.created_at) }}</span>
						</p>
						<p>
							CRD:
							<span>{{
								product.cargo_ready_date !== null
									? dateFormat(product.cargo_ready_date)
									: "N/A"
							}}</span>
						</p>
					</div>
				</div>

				<div class="products-content-wrapper" v-if="!isMobile">
					<v-container class="container-wrapper">
						<v-row class="product-desktop-row">
							<div class="">
								<p class="heading-product">Vendor</p>
								<p class="heading-content">
									{{ product.vendor_name }}
								</p>
							</div>

							<div class="mr-5">
								<p class="heading-product">Ship To</p>
								<p class="heading-content">
									{{ product.ship_to }}
								</p>
							</div>

							<div class="mr-5 product-col">
								<p class="heading-product">Cartons</p>
								<p class="heading-content ">
									{{ getTotalCartons(product.purchaseProduct, product.status) }}
									/ {{ totalCartons(product.purchaseProduct) }}
								</p>
							</div>

							<!-- <div class="mr-5 product-col">
								<p class="heading-product">Pallets</p>
								<p class="heading-content">
									4
								</p>
							</div> -->

							<div class="mr-5 product-col">
								<p class="heading-product">Units</p>
								<p class="heading-content">
									{{
										getTotalQuantity(product.purchaseProduct, product.status)
									}}
									/ {{ totalUnits(product.purchaseProduct) }}
								</p>
							</div>

							<div class="product-col">
								<p class="heading-product">Amount</p>
								<p class="heading-content amount">
									${{
										getTotalAmount(
											product.purchaseProduct,
											product.status
										).toLocaleString("en-US", {
											minimumFractionDigits: 2,
										})
									}}
								</p>
							</div>
						</v-row>
					</v-container>
					<div class="product-table-wrapper">
						<v-data-table
							:page.sync="page"
							:headers="headers"
							:items="product.purchaseProduct"
							:items-per-page="itemsPerPage"
							hide-default-footer
							mobile-breakpoint="769"
							class="elevation-1 product-table"
						>
							<template v-slot:[`item.cartons`]="{ item }">
								<div
									v-if="
										product.status == 'partial_shipped' ||
											product.status == 'shipped'
									"
								>
									{{ item.ship_cartons }}
									/ {{ item.quantity }}
								</div>
								<div v-else>
									{{ item.unship_cartons }} / {{ item.quantity }}
								</div>
							</template>

							<template v-slot:[`item.units`]="{ item }">
								<div
									v-if="
										product.status == 'partial_shipped' ||
											product.status == 'shipped'
									"
								>
									{{ item.shipped_units }}
									/ {{ item.units }}
								</div>
								<div v-else>{{ item.unshipped_units }} / {{ item.units }}</div>
							</template>

							<template v-slot:[`item.unitPrice`]="{ item }">
								${{ localeStringFormat(item.unitPrice) }}
							</template>
							<template v-slot:[`item.amount`]="{ item }">
								<div
									v-if="
										product.status == 'partial_shipped' ||
											product.status == 'shipped'
									"
								>
									${{
										(item.unitPrice * item.shipped_units).toLocaleString(
											"en-US",
											{
												minimumFractionDigits: 2,
											}
										)
									}}
								</div>
								<div v-else>
									${{
										(item.unitPrice * item.unshipped_units).toLocaleString(
											"en-US",
											{
												minimumFractionDigits: 2,
											}
										)
									}}
								</div>
							</template>
						</v-data-table>

						<div class="total">
							<div class="balance">Total</div>
							<div class="amount">
								<span class="mr-2"
									>${{
										getTotalAmount(
											product.purchaseProduct,
											product.status
										).toLocaleString("en-US", {
											minimumFractionDigits: 2,
										})
									}}</span
								>
							</div>
						</div>
					</div>
				</div>

				<div class="pb-1 products-content-wrapper" v-if="isMobile">
					<v-container class="container-wrapper">
						<v-row class="product-desktop-row">
							<div class="product-heading-detail">
								<p class="heading-product">Vendor</p>
								<p class="heading-content">
									{{ product.vendor_name }}
								</p>
							</div>

							<div class="product-heading-detail">
								<p class="heading-product">Ship To</p>
								<p class="heading-content">
									{{ product.ship_to }}
								</p>
							</div>

							<div class="product-heading-content">
								<div class="product-col">
									<p class="heading-product">Cartons</p>
									<p class="heading-content ">
										{{
											getTotalCartons(product.purchaseProduct, product.status)
										}}
										/ {{ totalCartons(product.purchaseProduct) }}
									</p>
								</div>

								<!-- <div class="product-col">
									<p class="heading-product">Pallets</p>
									<p class="heading-content">
										4
									</p>
								</div> -->
							</div>

							<div class="product-heading-content">
								<div class="product-col">
									<p class="heading-product">Units</p>
									<p class="heading-content">
										{{
											getTotalQuantity(product.purchaseProduct, product.status)
										}}
										/ {{ totalUnits(product.purchaseProduct) }}
									</p>
								</div>

								<div class="product-col">
									<p class="heading-product">Amount</p>
									<p class="heading-amount">
										${{
											getTotalAmount(
												product.purchaseProduct,
												product.status
											).toLocaleString("en-US", {
												minimumFractionDigits: 2,
											})
										}}
									</p>
								</div>
							</div>
						</v-row>
					</v-container>
					<div class="product-table-wrapper">
						<v-row class="table-row">
							<v-col>
								<div class="table-header">
									<p>Description</p>
									<p>Amount</p>
								</div>

								<div class="view-table-wrapper">
									<v-data-table
										:headers="headers"
										:items="product.purchaseProduct"
										class="elevation-1 view-product-table"
										hide-default-footer
										mobile-breakpoint="769"
									>
										<template v-slot:[`item.sku`]="{ item }">
											<div class="layout-flex">
												<div class="desc-wrapper">
													<p class="mb-0">{{ item.sku }}</p>
													<p class="mb-0">{{ item.description }}</p>
													<p
														class="mb-0"
														v-if="
															product.status == 'partial_shipped' ||
																product.status == 'shipped'
														"
													>
														{{ item.ship_cartons }} /
														{{ item.quantity }} Cartons
													</p>
													<p class="mb-0" v-else>
														{{ item.unship_cartons }} /
														{{ item.quantity }} Cartons
													</p>
													<p
														class="mb-0 font-weight-medium"
														v-if="
															product.status == 'partial_shipped' ||
																product.status == 'shipped'
														"
													>
														{{ item.shipped_units }}
														/ {{ item.units }} Units x ${{
															localeStringFormat(item.unitPrice)
														}}
													</p>
													<p class="mb-0 font-weight-medium" v-else>
														{{ item.unshipped_units }} / {{ item.units }} Units
														x ${{ localeStringFormat(item.unitPrice) }}
													</p>
												</div>

												<div class="desc-amount">
													<p
														class="mb-0 font-weight-medium"
														v-if="
															product.status == 'partial_shipped' ||
																product.status == 'shipped'
														"
													>
														${{
															(
																item.shipped_units * item.unitPrice
															).toLocaleString("en-US", {
																minimumFractionDigits: 2,
															})
														}}
													</p>
													<p class="mb-0 font-weight-medium" v-else>
														${{
															(
																item.unshipped_units * item.unitPrice
															).toLocaleString("en-US", {
																minimumFractionDigits: 2,
															})
														}}
													</p>
												</div>
											</div>
										</template>
									</v-data-table>
								</div>
							</v-col>
						</v-row>
						<div class="total">
							<div class="balance">Total</div>
							<div class="amount">
								${{ getTotalAmount(product.purchaseProduct, product.status) }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div
			class="pa-5 no-products-wrapper"
			v-if="getShipmentProducts.length == 0"
		>
			<div
				class="loading-wrapper"
				v-if="getShipmentProductsLoading && getShipmentProducts.length == 0"
			>
				<v-progress-circular :size="40" color="#0171a1" indeterminate>
				</v-progress-circular>
			</div>
			<div
				v-if="!getShipmentProductsLoading && getShipmentProducts.length == 0"
				class="no-product-heading"
			>
				<h3>No Products</h3>
				<p class="text-center mb-0">
					This shipment doesn't have any products yet.
				</p>
			</div>
		</div>

		<ViewProductDialog
			:productDialog="productDialog"
			:editedItemData.sync="editedItem"
			@close="close"
		/>
	</div>
</template>
<script>
import ViewProductDialog from "../components/ShipmentComponents/Products/ViewProductDialog.vue";
import globalMethods from "@/utils/globalMethods";
import { mapActions, mapGetters } from "vuex";
import moment from "moment";
export default {
	name: "ShipmentProduct",
	components: {
		ViewProductDialog,
	},
	props: ["id"],
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		headers: [
			{
				text: "SKU",
				align: "start",
				sortable: false,
				value: "sku",
				width: "12%",
				fixed: true,
			},
			{
				text: "Description",
				align: "start",
				sortable: false,
				value: "description",
				width: "30%",
				fixed: true,
			},
			{
				text: "Cartons",
				align: "end",
				value: "cartons",
				sortable: false,
				width: "16%",
				fixed: true,
			},
			{
				text: "Units",
				align: "end",
				value: "units",
				sortable: false,
				width: "16%",
				fixed: true,
			},
			{
				text: "Unit Price",
				align: "end",
				value: "unitPrice",
				sortable: false,
				width: "13%",
				fixed: true,
			},
			{
				text: "Amount",
				align: "end",
				value: "amount",
				sortable: false,
				width: "13%",
				fixed: true,
			},
		],
		productDialog: false,
		isMobile: false,
		shipmentProducts: [],
		editedItem: {
			po_number: "",
			vendor_name: "",
			ship_to: "",
			ship_via: "",
			terms: "",
			cargo_ready_date: "",
			notes: "",
		},
		defaultItem: {
			po_number: "",
			vendor_name: "",
			ship_to: "",
			ship_via: "",
			terms: "",
			cargo_ready_date: "",
			notes: "",
		},
	}),
	mounted() {
		this.fetchShipmentProducts(this.id);
	},
	computed: {
		...mapGetters({
			getShipmentProducts: "getShipmentProducts",
			getShipmentProductsLoading: "getShipmentProductsLoading",
			getShipmentDetails: "getShipmentDetails",
		}),
		productsDetails() {
			let product = this.getShipmentProducts.map((item) => {
				return {
					id: item.id,
					po_number: item.po_number,
					status: item.status,
					created_at: item.created_at,
					cargo_ready_date: item.cargo_ready_date,
					vendor_name: item.vendor_name != "N/A" ? item.vendor_name : this.getShipmentDetails.suppliers_name[0],
					ship_to: item.ship_to,
					ship_via: item.ship_via,
					terms: item.terms,
					notes: item.notes,
					sub_total: item.sub_total.toLocaleString("en-US", {
						minimumFractionDigits: 2,
					}),
					tax: item.tax != null ? item.tax.toLocaleString("en-US", {minimumFractionDigits: 2,}) : 0,
					shipping: item.shipping != null ? item.shipping.toLocaleString("en-US", {minimumFractionDigits: 2,}) : 0,
					discount: item.discount != null ? item.discount.toLocaleString("en-US", {minimumFractionDigits: 2,}) : 0.00,
					total: this.currencyNumberFormat(item.total),
					purchaseProduct: item.purchase_order_products.map((item) => {
						return {
							sku: item.product ? item.product.sku : 'N/A',
                            description: item.product && item.product.description !== '' ? item.product.description : 'N/A',
                            ship_cartons: item.ship_cartons !== undefined ? item.ship_cartons : 0,
                            unship_cartons: item.unship_cartons !== undefined ? item.unship_cartons : 0,
                            shipped_units: item.product && item.product.shipped_units !== undefined ? item.product.shipped_units : 0,
                            unshipped_units: item.product && item.product.unshipped_units !== undefined ? item.product.unshipped_units : 0,
							units: item.units,
							quantity: item.quantity,
							unitPrice: parseFloat(item.unit_price),
							amount: item.amount.toLocaleString("en-US", {
								minimumFractionDigits: 2,
							}),
						};
					}),
				};
			});
			return product;
		},
	},
	methods: {
		...globalMethods,
		...mapActions({
			fetchShipmentProducts: "fetchShipmentProducts",
			downloadOrder: "downloadOrder",
		}),
		totalUnits(items) {
			return items.reduce((total, item) => item.units + total, 0);
		},
		totalCartons(items) {
			return items.reduce((total, item) => item.quantity + total, 0);
		},
		getTotalQuantity(items, status) {
			if (status == "shipped" || status == "partial_shipped") {
				return items.reduce((total, item) => item.shipped_units + total, 0);
			} else {
				return items.reduce((total, item) => item.unshipped_units + total, 0);
			}
		},
		getTotalCartons(items, status) {
			if (status == "shipped" || status == "partial_shipped") {
				return items.reduce((total, item) => item.ship_cartons + total, 0);
			} else {
				return items.reduce((total, item) => item.unship_cartons + total, 0);
			}
		},
		getTotalAmount(items, status) {
			if (status == "shipped" || status == "partial_shipped") {
				let total = items.reduce(
					(total, item) => item.unitPrice * item.shipped_units + total,
					0
				);
				return total.toLocaleString("en-US", {
					minimumFractionDigits: 2,
				});
			} else {
				return items.reduce(
					(total, item) => item.unitPrice * item.unshipped_units + total,
					0
				);
			}
		},
		dateFormat(date) {
			return moment(date).format("MMM DD, YYYY");
		},
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		viewPo(item) {
			this.editedItem = Object.assign({}, item);
			this.productDialog = true;
		},
		close() {
			this.productDialog = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
			});
		},
		downloadPo(item) {
			this.downloadOrder(item);
		},
		localeStringFormat(item) {
			return item.toLocaleString("en-US", {
				minimumFractionDigits: 2,
			});
		},
	},
};
</script>
<style lang="scss">
@import "../assets/scss/pages_scss/shipment/shipmentProducts.scss";
</style>
