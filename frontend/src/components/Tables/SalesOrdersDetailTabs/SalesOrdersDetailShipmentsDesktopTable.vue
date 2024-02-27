<!-- @format -->

<template>
	<div class="so-details-wrapper-table shipments">
		<div>
			<v-data-table
				:headers="headers"
				:items="soDetailsShipments"
				class="elevation-1"
				:class="
					soDetailsShipments !== null &&
					soDetailsShipments.length !== 'undefined' &&
					soDetailsShipments.length > 0
						? 'po-details-table'
						: 'no-data-table'
				"
				hide-default-footer
				v-if="!isMobile"
			>
				<template v-slot:body="{ items }">
					<tbody v-if="items.length > 0">
						<tr v-for="item in items" :key="item.id">
							<td :class="'multiple'">
								<div class="reference">
									<p class="mb-0 blue_txt">{{ item.shifl_ref }}</p>
									<p class="mb-0 mbl-num">{{ item.mbl_num }}</p>
								</div>
							</td>

							<td
								class="type-wrapper"
								:class="item.products.length > 1 ? 'multiple' : 'single'"
							>
								<div
									class="type"
									v-if="
										item.type !== null &&
											item.type !== '' &&
											item.type !== 'null'
									"
								>
									<img
										v-if="item.type == 'LCL'"
										src="../../../assets/images/small-container-po.svg"
									/>
									<img
										v-if="item.type == 'Air'"
										src="../../../assets/images/air-container-po.svg"
									/>
									<img
										v-if="item.type == 'FCL'"
										src="../../../assets/images/big-container-po.svg"
									/>
									<span class="ml-2">{{ getTotalCBM(item.products) }} CBM</span>
								</div>
								<div v-else>--</div>
							</td>

							<td :class="item.products.length > 1 ? 'multiple' : 'single'">
								<div class="noSpace">{{ formatDate(item.etd) }}</div>
							</td>

							<td :class="item.products.length > 1 ? 'multiple' : 'single'">
								<div class="noSpace">{{ formatDate(item.eta) }}</div>
							</td>

							<td :class="item.products.length > 1 ? 'multiple' : 'single'">
								<div class="cols-status">
									<span :class="item.shipment_status">{{
										item.shipment_status
									}}</span>
								</div>
							</td>

							<td
								class="td-for-products"
								:class="item.products.length > 1 ? 'multiple' : 'single'"
							>
								<div
									class="td-for-products-content"
									v-for="(p, i) in item.products"
									:key="i"
								>
									<p class="td-inside">
										<span v-if="p.category_id !== null"
											>{{ p.category_id }}-</span
										>
										<span>{{ p.sku }}</span>
									</p>
									<p class="td-inside name">{{ p.name }}</p>
								</div>
							</td>

							<td
								class="td-for-products"
								:class="item.products.length > 1 ? 'multiple' : 'single'"
							>
								<div
									class="td-for-products-content text-right"
									v-for="(p, i) in item.products"
									:key="i"
								>
									<td class="td-inside ">{{ p.carton }}</td>
								</div>
							</td>

							<td
								class="td-for-products"
								:class="item.products.length > 1 ? 'multiple' : 'single'"
							>
								<div
									class="td-for-products-content text-right"
									v-for="(p, i) in item.products"
									:key="i"
								>
									<td class="td-inside">{{ p.unit }}</td>
								</div>
							</td>
						</tr>
					</tbody>

					<tbody v-else>
						<tr>
							<td>
								<div
									class="no-data-preloader"
									v-if="getPoShipmentDetailsLoading"
								>
									<v-progress-circular :size="40" color="#0171a1" indeterminate>
									</v-progress-circular>
								</div>

								<div
									class="no-data-wrapper"
									v-if="
										!getPoShipmentDetailsLoading &&
											soDetailsShipments.length === 0
									"
								>
									<div class="no-data-heading">
										<img
											src="@/assets/icons/noShipmentData.svg"
											alt=""
											width="65px"
										/>

										<h3>No Shipments</h3>
										<p>
											There are no shipments associated in this Sales Orders
											yet.
										</p>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</template>
			</v-data-table>

			<div v-if="isMobile" class="mobile-table-details-shipment-wrapper">
				<v-data-table
					:headers="headers"
					:items="soDetailsShipments"
					class="mobile-podetails-shipment elevation-0"
					:class="
						soDetailsShipments !== null &&
						soDetailsShipments.length !== 'undefined' &&
						soDetailsShipments.length > 0
							? 'po-details-table'
							: 'no-data-table'
					"
					hide-default-footer
				>
					<template v-slot:[`item.shifl_ref`]="{ item }">
						<div class="mobile-shipment-item-wrapper">
							<div class="mobile-shipment-wrapper mb-2">
								<div class="blue_txt" style="font-size: 16px;">
									{{ item.shifl_ref }}
									<span>
										{{ item.mbl_num }}
									</span>
								</div>
								<div class="cols-status">
									<span :class="item.shipment_status">{{
										item.shipment_status
									}}</span>
								</div>
							</div>

							<div class="mobile-shipment-wrapper">
								<div class="dates-with-type">
									<div
										class="type"
										v-if="
											item.type !== null &&
												item.type !== '' &&
												item.type !== 'null'
										"
									>
										<img
											v-if="item.type == 'LCL'"
											src="../../../assets/images/small-container-po.svg"
										/>
										<img
											v-if="item.type == 'Air'"
											src="../../../assets/images/air-container-po.svg"
										/>
										<img
											v-if="item.type == 'FCL'"
											src="../../../assets/images/big-container-po.svg"
										/>
										<span class="ml-2"
											>{{ getTotalCBM(item.products) }} CBM</span
										>
									</div>

									<span class="mobile-titles">ETD:</span>
									<span>{{ formatDate(item.etd) }}</span>
								</div>
								<div class="dates">
									<span class="mobile-titles">ETA:</span>
									<span>{{ formatDate(item.eta) }}</span>
								</div>
							</div>

							<div class="mobile-shipment-wrapper-unset">
								<div
									class="shipment-products"
									v-for="(p, i) in item.products"
									:key="i"
								>
									<div class="mobile-shipment-separator"></div>
									<div class="product-sku">
										<span class="mobile-titles">SKU:</span>
										<span v-if="p.category_id !== null"
											>{{ p.category_id }}-</span
										>
										<span>{{ p.sku }}</span>
									</div>
									<div class="product-item">
										<span class="mobile-titles">Item:</span>
										<span>{{ p.name }}</span>
									</div>
									<div class="dFlex">
										<div>
											<span class="mobile-titles">Carton:</span>
											<span>{{ p.carton }}</span>
										</div>
										<div>
											<span class="mobile-titles">Unit:</span>
											<span>{{ p.unit }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</template>

					<template v-slot:no-data>
						<div class="no-data-preloader" v-if="getPoShipmentDetailsLoading">
							<v-progress-circular :size="40" color="#0171a1" indeterminate>
							</v-progress-circular>
						</div>

						<div
							class="no-data-wrapper"
							v-if="
								!getPoShipmentDetailsLoading && soDetailsShipments.length === 0
							"
						>
							<div class="no-data-heading">
								<img
									src="@/assets/icons/noShipmentData.svg"
									alt=""
									width="65px"
								/>

								<h3>No Shipments</h3>
								<p>
									There are no shipments associated in this Sales Orders yet.
								</p>
							</div>
						</div>
					</template>
				</v-data-table>
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import moment from "moment";
import _ from "lodash";

export default {
	components: {},
	props: ["isMobile"],
	data: () => ({
		headers: [
			{
				text: "Reference",
				align: "start",
				sortable: false,
				value: "shifl_ref",
				fixed: true,
				width: "9%",
			},
			{
				text: "Mode & Volume",
				align: "start",
				sortable: false,
				value: "type",
				fixed: true,
				width: "7%",
			},
			{
				text: "ETD",
				align: "start",
				sortable: false,
				value: "etd",
				fixed: true,
				width: "10%",
			},
			{
				text: "ETA",
				align: "start",
				sortable: false,
				value: "eta",
				fixed: true,
				width: "10%",
			},
			{
				text: "Status",
				align: "center",
				sortable: false,
				value: "shipment_status",
				fixed: true,
				width: "10%",
			},
			{
				text: "SKU",
				align: "start",
				sortable: false,
				value: "sku",
				fixed: true,
				width: "25%",
			},
			{
				text: "Carton",
				align: "end",
				sortable: false,
				value: "carton",
				fixed: true,
				width: "8%",
			},
			{
				text: "Unit",
				align: "end",
				sortable: false,
				value: "unit",
				fixed: true,
				width: "8%",
			},
		],
	}),
	methods: {
		...mapActions([]),
		formatDate(date) {
			if (date !== null) {
				return moment(date).format("MMM DD, YYYY");
			} else {
				return "N/A";
			}
		},
		getTotalCBM(item) {
			let totalCbm = 0;
			totalCbm === 0 &&
				item.forEach((element) => {
					totalCbm += parseFloat(element.volume ? element.volume : 0);
				});
			return totalCbm;
		},
	},
	computed: {
		...mapGetters({
			getPoDetail: "salesOrders/getPoDetail",
			getPoShipmentDetails: "salesOrders/getPoShipmentDetails",
			getPoShipmentDetailsLoading: "salesOrders/getPoShipmentDetailsLoading",
		}),
		soDetailsShipments() {
			let poShipments = [];

			if (
				typeof this.getPoShipmentDetails !== "undefined" &&
				this.getPoShipmentDetails !== null &&
				Array.isArray(this.getPoShipmentDetails.data) &&
				this.getPoShipmentDetails.data.length > 0
			) {
				this.getPoShipmentDetails.data.map((v) => {
					if (v.purchase_order_data !== null) {
						let products = v.purchase_order_data.purchase_order_products.map(
							(p) => {
								let { id, product, shipment_distribution, volume } = p;

								let findCarton = _.find(
									shipment_distribution,
									(e) => e.purchase_order_product_id === id
								);

								return {
									sku: product.sku,
									name: product.name,
									volume: volume,
									category_id: product.category_id,
									unit: product.shipped_units,
									carton: findCarton.ship_cartons,
								};
							}
						);

						poShipments.push({
							id: v.id,
							shifl_ref: v.shifl_ref,
							mbl_num: v.mbl_num,
							type: v.type,
							eta: v.eta,
							etd: v.etd,
							shipment_status: v.shipment_status,
							products,
						});
					}
				});
			}

			return poShipments;
		},
	},
	mounted() {},
	updated() {},
};
</script>

<style lang="scss"></style>
