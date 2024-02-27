<!-- @format -->

<template>
	<v-dialog
		v-model="dialogAdd"
		max-width="1344px"
		content-class="product-dialog"
		:retain-focus="false"
		persistent
		v-resize="onResize"
	>
		<v-card class="product-dialog-card">
			<v-card-title>
				<span class="headline" v-if="!isMobile"
					>Purchase Order #{{ productData.po_number }}
					<span
						:class="{
							'product-type-full': productData.status == 'shipped',
							'product-type-partial': productData.status == 'partial_shipped',
						}"
						class="ml-1"
					>
						{{ productData.status == "shipped" ? "Full" : "" }}
						{{ productData.status == "partial_shipped" ? "Partial" : "" }}
					</span></span
				>
				<!-- <span class="headline" v-if="isMobile">Edit Purchase Order</span> -->
				<span class="headline" v-if="isMobile"
					>Purchase Order #{{ productData.po_number }}</span
				>
				<div>
					<button icon dark class="btn-close" @click="close" v-if="isMobile">
						<v-icon>mdi-close</v-icon>
					</button>
				</div>
				<div class="product-actions px-0" v-if="!isMobile">
					<div class="d-flex mr-5">
						<!-- <button class="btn-blue mr-3">
							<span>
								<span>Edit</span>
							</span>
						</button> -->

						<button
							class="btn-white"
							@click="downloadPo(productData)"
							:disabled="getShipmentProductsDownloadLoading"
						>
							Download
						</button>
					</div>

					<div>
						<button icon dark class="btn-close" @click="close">
							<v-icon>mdi-close</v-icon>
						</button>
					</div>
				</div>
			</v-card-title>
			<v-card-actions class="product-actions " v-if="isMobile">
				<div class="d-flex ">
					<!-- <button class="btn-blue">
						<span>
							<span>Edit</span>
						</span>
					</button> -->

					<button
						class="btn-white"
						@click="downloadPo(productData)"
						:disabled="getShipmentProductsDownloadLoading"
					>
						Download
					</button>
				</div>
			</v-card-actions>
			<v-card-text>
				<div class="add-wrapper">
					<div class="product-info">
						<div class="product-number">
							<p class="product-title">PO Number</p>
							<p class="product-value">{{ productData.po_number }}</p>
						</div>

						<div class="product-name">
							<p class="product-title">Vendor</p>
							<p class="product-value">
								{{ productData.vendor_name }}
							</p>
						</div>

						<div class="product-category">
							<p class="product-title">Ship To</p>
							<p class="product-value">
								{{
									productData.ship_to !== null && productData.ship_to !== ""
										? productData.ship_to
										: "N/A"
								}}
							</p>
						</div>

						<div class="product-category">
							<p class="product-title">Ship Via</p>
							<p class="product-value">
								{{
									productData.ship_via !== null ? productData.ship_via : "N/A"
								}}
							</p>
						</div>

						<div class="product-date">
							<p class="product-title">Shipping Term</p>
							<p class="product-value">
								{{ productData.terms !== null ? productData.terms : "N/A" }}
							</p>
						</div>

						<div class="carton-date ">
							<p class="product-title">Cargo Ready Date</p>
							<p class="product-value">{{ cargoDateFormat }}</p>
						</div>

						<div class="product-note">
							<p class="product-title">Notes</p>
							<p class="product-value">
								{{ productData.notes !== null ? productData.notes : "N/A" }}
							</p>
						</div>
					</div>

					<div class="product-table">
						<v-data-table
							:headers="headers"
							:items="productData.purchaseProduct"
							class="desktop-podetails-product elevation-0"
							hide-default-footer
							v-if="!isMobile"
						>
							<template v-slot:[`item.product`]="{ item }">
								<div>
									<p class="mb-0">#{{ item.sku }}</p>
									<p class="mb-0">{{ item.description }}</p>
								</div>
							</template>

							<template v-slot:[`item.cartons`]="{ item }">
								<div
									v-if="
										productData.status == 'partial_shipped' ||
											productData.status == 'shipped'
									"
								>
									{{ item.ship_cartons }}
								</div>
								<div v-else>
									{{ item.unship_cartons }}
								</div>
							</template>

							<template v-slot:[`item.units`]="{ item }">
								<div
									v-if="
										productData.status == 'partial_shipped' ||
											productData.status == 'shipped'
									"
								>
									{{ item.shipped_units }}
								</div>
								<div v-else>
									{{ item.unshipped_units }}
								</div>
							</template>

							<template v-slot:[`item.unitPrice`]="{ item }">
								${{ localeStringFormat(item.unitPrice) }}
							</template>

							<template v-slot:[`item.amount`]="{ item }">
								<div
									v-if="
										productData.status == 'partial_shipped' ||
											productData.status == 'shipped'
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

						<div class="px-6 poDetail" v-if="!isMobile">
							<div class="poDetail-total po-subtotal">
								<div class="py-2 poDetail-label">Subtotal</div>
								<div class="py-2 pr-4 poDetail-value">
									${{
										getSubTotalAmount(
											productData.purchaseProduct,
											productData.status
										).toLocaleString("en-US", {
											minimumFractionDigits: 2,
										})
									}}
								</div>
							</div>

							<div class="poDetail-total po-charge">
								<div class="py-2 poDetail-label">Tax</div>
								<div class="py-2 pr-4 poDetail-value">
									${{ productData.tax }}
								</div>
							</div>

							<div class="poDetail-total po-charge">
								<div class="py-2 poDetail-label">Shipping</div>
								<div class="py-2 pr-4 poDetail-value">
									${{ productData.shipping }}
								</div>
							</div>

							<div class="poDetail-total po-charge">
								<div class="py-2 poDetail-label">Discount</div>
								<div class="py-2 pr-4 poDetail-value">
									${{ productData.discount }}
								</div>
							</div>

							<div class="poDetail-total no-border po-total">
								<div class="py-2 poDetail-label ">
									Total
								</div>
								<div class="py-2 pr-4 poDetail-value">
									${{ getTotalAmount() }}
								</div>
							</div>
						</div>
						<v-row class="table-row" v-if="isMobile">
							<v-col>
								<div class="table-header">
									<p>Description</p>
									<p>Amount</p>
								</div>

								<div class="view-table-wrapper">
									<v-data-table
										:headers="headers"
										:items="productData.purchaseProduct"
										class="elevation-1 view-product-table"
										hide-default-footer
										mobile-breakpoint="769"
									>
										<template v-slot:[`item.product`]="{ item }">
											<div class="layout-flex">
												<div class="desc-wrapper">
													<p class="mb-0">#{{ item.sku }}</p>
													<p class="mb-0">{{ item.description }}</p>
													<p
														class="mb-0"
														v-if="
															productData.status == 'partial_shipped' ||
																productData.status == 'shipped'
														"
													>
														{{ item.ship_cartons }}
														Cartons
													</p>
													<p class="mb-0" v-else>
														{{ item.unship_cartons }} Cartons
													</p>
													<p
														class="mb-0 font-weight-medium"
														v-if="
															productData.status == 'partial_shipped' ||
																productData.status == 'shipped'
														"
													>
														{{ item.shipped_units }}
														Units x ${{ localeStringFormat(item.unitPrice) }}
													</p>
													<p class="mb-0 font-weight-medium" v-else>
														{{ item.unshipped_units }} Units x ${{
															localeStringFormat(item.unitPrice)
														}}
													</p>
												</div>

												<div class="desc-amount">
													<p
														class="mb-0 font-weight-medium"
														v-if="
															productData.status == 'partial_shipped' ||
																productData.status == 'shipped'
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

						<div class="px-6 py-1 poDetail" v-if="isMobile">
							<div class="poDetail-total po-subtotal">
								<div class="py-2 poDetail-label font-weight-bold">Subtotal</div>
								<div class="py-2 font-weight-bold poDetail-value">
									${{
										getSubTotalAmount(
											productData.purchaseProduct,
											productData.status
										).toLocaleString("en-US", {
											minimumFractionDigits: 2,
										})
									}}
								</div>
							</div>

							<div class="poDetail-total po-charge">
								<div class="py-2 poDetail-label">Tax</div>
								<div class="py-2 poDetail-value">${{ productData.tax }}</div>
							</div>

							<div class="poDetail-total po-charge">
								<div class="py-2 poDetail-label">Shipping</div>
								<div class="py-2 poDetail-value">
									${{ productData.shipping }}
								</div>
							</div>

							<div class="poDetail-total no-border po-total">
								<div class="py-2 poDetail-label ">
									Total
								</div>
								<div class="py-2 poDetail-value">${{ getTotalAmount() }}</div>
							</div>
						</div>
					</div>

					<!-- <div class="total">
						<div class="balance">Total</div>
						<div class="amount">$18,912.58</div>
					</div> -->
				</div>
			</v-card-text>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import moment from "moment";

export default {
	name: "ViewProductDialog",
	props: ["productDialog", "editedItemData"],
	data: () => ({
		isMobile: false,
		headers: [
			{
				text: "Product",
				align: "start",
				sortable: false,
				value: "product",
				fixed: true,
				width: "40%",
			},
			{
				text: "Carton",
				align: "end",
				sortable: false,
				value: "cartons",
				fixed: true,
				width: "15%",
			},
			{
				text: "Unit",
				align: "end",
				sortable: false,
				value: "units",
				fixed: true,
				width: "15%",
			},
			{
				text: "Unit Price",
				align: "end",
				sortable: false,
				value: "unitPrice",
				fixed: true,
				width: "15%",
			},
			{
				text: "Amount",
				align: "end",
				sortable: false,
				value: "amount",
				fixed: true,
				width: "15%",
			},
		],
	}),
	computed: {
		...mapGetters({
			getShipmentProductsDownloadLoading: "getShipmentProductsDownloadLoading",
		}),
		dialogAdd: {
			get() {
				return this.productDialog;
			},
			set(value) {
				this.$emit("update:productDialog", value);
			},
		},
		productData: {
			get() {
				return this.editedItemData;
			},
			set(value) {
				this.$emit("update:editedItemData", value);
			},
		},
		cargoDateFormat() {
			if (this.productData.cargo_ready_date !== null) {
				return moment(this.productData.cargo_ready_date).format("DD-MM-YYYY");
			} else {
				return "N/A";
			}
		},
	},
	methods: {
		...mapActions({
			downloadOrder: "downloadOrder",
		}),
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		getSubTotalAmount(items, status) {
			if (status !== undefined && items !== undefined) {
				if (status == "shipped" || status == "partial_shipped") {
					return items.reduce(
						(total, item) => item.unitPrice * item.shipped_units + total,
						0
					);
				} else {
					return items.reduce(
						(total, item) => item.unitPrice * item.unshipped_units + total,
						0
					);
				}
			} else {
				return 0;
			}
		},
		getTotalAmount() {
			let total =
				this.getSubTotalAmount(
					this.productData.purchaseProduct,
					this.productData.status
				) +
				parseFloat(this.productData.tax) +
				parseFloat(this.productData.shipping) -
				parseFloat(this.productData.discount);

			return total.toLocaleString("en-US", {
				minimumFractionDigits: 2,
			});
		},
		close() {
			this.$emit("close");
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
@import "../../../assets/scss/pages_scss/shipment/viewProductDialog.scss";
</style>
