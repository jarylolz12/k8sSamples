<!-- @format -->

<template>
	<div class="po-details-wrapper-table products">
		<div>
			<v-data-table
				:headers="dynamicHeader"
				:items="finalsoDetailsProducts"
				class="desktop-podetails-product elevation-0"
				hide-default-footer
				v-if="!isMobile"
			>
				<template v-slot:body="{ items }">
					<tbody>
						<tr v-for="(item, i) in items" :key="i">
							<td>
								<!-- v-if="getPoDetail.is_issuer" -->
								<div v-for="(ite,index) in item.duplicate" :key="ite.id">
									<span v-if="index == 0">{{
										ite.product.category_sku !== null
											? "#" + ite.product.category_sku
											: "--"
									}}</span>
									<p v-if="index == 0" class="mb-0" style="color: #6D858F;">
										{{ ite.product.name }}
									</p>
								</div>
								<!-- <div v-else>
									<p class="mb-0" style="color: #6D858F;">
										{{
											item.other_party_product_sku
												? "#" + item.other_party_product_sku
												: "--"
										}}
									</p>
								</div> -->
							</td>

							<!-- <td>
								<div>
									<p class="mb-0" v-if="getPoDetail.is_issuer">
										{{
											item.other_party_product_sku
												? "#" + item.other_party_product_sku
												: "--"
										}}
									</p>
									<span v-else>{{
										item.product.category_sku !== null
											? "#" + item.product.category_sku
											: "--"
									}}</span>
								</div>
							</td> -->

							<td>
								<div v-for="ite in item.duplicate" :key="ite.id" class="po-tb-set po-tb-tr-td-div-padding" >
									{{ite.ship_date}}
								</div>
							</td>

							<td
								
							>
								<div class="po-tb-set po-tb-tr-td-div-padding"
								v-for="(ite,index) in item.duplicate" 
								:key="ite.id + index"
								:class="
									checkFeildExists('quantity', ite.change_log)
										? 'change-log'
										: ''
								">
									<div
										class="mb-0 text-right"
										v-if="
											!checkFeildExists('quantity', ite.change_log) &&
												ite.productionStatusItems.length == 0
										"
									>
										{{ ite.quantity }}
									</div>
									<div
										class="mb-0 text-right"
										v-if="
											ite.productionStatusItems &&
												ite.productionStatusItems.length > 0
										"
									>
										<p
											class="mb-0"
											v-for="(value, i) in ite.productionStatusItems"
											:key="i"
										>
											{{ value.carton }}
										</p>
									</div>
									<div v-for="(changedData, i) in ite.change_log" :key="i">
										<div
											class="changed-value"
											v-if="changedData.field == 'quantity'"
										>
											<p class="mb-0 item-danger">
												{{ changedData.new_value }}
											</p>
											<p class="mb-0 item-cancel">
												{{ changedData.old_value }}
											</p>
										</div>
									</div>
								</div>
							</td>

							<td
								
							>
								<div
								class="po-tb-set po-tb-tr-td-div-padding"
								v-for="(ite,index) in item.duplicate"
								:key="ite.id + index"
								:class="
									checkFeildExists('units_per_carton', ite.change_log)
										? 'change-log'
										: ''
								">
									<div>
										<p
											class="mb-0 text-right"
											v-if="
												!checkFeildExists(
													'units_per_carton',
													ite.change_log
												) && ite.productionStatusItems.length == 0
											"
										>
											<span style="color: #6D858F;"
												>{{ ite.units_per_carton }} Each</span
											>
										</p>
									</div>
									<div
										class="mb-0 text-right"
										v-if="
											ite.productionStatusItems &&
												ite.productionStatusItems.length > 0
										"
									>
										<p
											class="mb-0"
											v-for="(value, i) in ite.productionStatusItems"
											:key="i"
										>
											{{ value.in_each }}
										</p>
									</div>

									<div v-for="(changedData, i) in ite.change_log" :key="i">
										<div
											class="changed-value"
											v-if="changedData.field == 'units_per_carton'"
										>
											<p class="mb-0 item-danger">
												{{ changedData.new_value }} Each
											</p>
											<p class="mb-0 item-cancel ">
												{{ changedData.old_value }} Each
											</p>
										</div>
									</div>
								</div>
							</td>

							<td
								
							>
								<div 
								class="po-tb-set po-tb-tr-td-div-padding"
								 	v-for="(ite,index) in item.duplicate"
									:key="ite.id + index"
									:class="
									checkFeildExists('units', ite.change_log) ? 'change-log' : ''
								">

								<div
									class="mb-0 text-right"
									v-if="
										ite.productionStatusItems &&
											ite.productionStatusItems.length > 0
									"
								>
									<p
										class="mb-0"
										v-for="(value, i) in ite.productionStatusItems"
										:key="i"
									>
										{{ value.unit }}
									</p>
								</div>
								<div>
									<p
										class="mb-0 text-right"
										v-if="
											!checkFeildExists('units', ite.change_log) &&
												ite.productionStatusItems.length == 0
										"
									>
										{{ ite.units }}
									</p>
								</div>
								<div v-for="(changedData, i) in ite.change_log" :key="i">
									<div
										class="changed-value"
										v-if="changedData.field == 'units'"
									>
										<p class="mb-0 item-danger">{{ changedData.new_value }}</p>
										<p class="mb-0 item-cancel ">{{ changedData.old_value }}</p>
									</div>
								</div>
								</div>
							</td>

							<td
								
								v-if="
									getPoDetail.state !== 'in_progress' &&
										getPoDetail.state !== 'approved'
								"
							>
							<div
							v-for="(ite,index) in item.duplicate"
									:key="ite.id + index"
							:class="
									checkFeildExists('volume', ite.change_log)
										? 'change-log'
										: ''
								"
								class="po-tb-set po-tb-tr-td-div-padding"
									>

								<div v-if="!checkFeildExists('volume', ite.change_log)">
									<p class="mb-0 text-right">
										<!-- {{ parseFloat(item.volume).toFixed(2) }} CBM -->
										{{ ite.volume !== null ? ite.volume : 0 }}
										CBM
									</p>
								</div>
								<div v-for="(changedData, i) in ite.change_log" :key="i">
									<div
										class="changed-value"
										v-if="changedData.field == 'volume'"
									>
										<p class="mb-0 item-danger">
											{{ changedData.new_value }}CBM
										</p>
										<p class="mb-0 item-cancel">
											{{ changedData.old_value }}CBM
										</p>
									</div>
								</div>
							</div>
							</td>

							<td
								v-if="
									getPoDetail.state !== 'in_progress' &&
										getPoDetail.state !== 'approved'
								"
							>
								<div 
								class="po-tb-set po-tb-tr-td-div-padding"
									v-for="(ite,index) in item.duplicate"
									:key="ite.id + index"
									:class="
									checkFeildExists('weight', ite.change_log)
										? 'change-log'
										: ''
								">

								<div v-if="!checkFeildExists('weight', ite.change_log)">
									<p class="mb-0 text-right">
										{{
											ite.weight !== null
												? parseFloat(ite.weight).toFixed(2)
												: 0
										}}
										KG
									</p>
								</div>
								<div v-for="(changedData, i) in ite.change_log" :key="i">
									<div
										class="changed-value"
										v-if="changedData.field == 'weight'"
									>
										<p class="mb-0 item-danger">
											{{ parseFloat(changedData.new_value).toFixed(2) }} KG
										</p>
										<p class="mb-0 item-cancel">
											{{ parseFloat(changedData.old_value).toFixed(2) }} KG
										</p>
									</div>
								</div>
								</div>
							</td>

							<td
								v-if="
									getPoDetail.state == 'in_progress' ||
										getPoDetail.state == 'approved'
								"
							>
								<div
								class="po-tb-set po-tb-tr-td-div-padding"
									v-for="(ite,index) in item.duplicate"
									:key="ite.id + index">

								<div v-if="ite.productionStatusItems.length == 0">
									<p class="mb-0 text-right">
										{{ ite.volume !== null ? ite.volume : 0 }}
										CBM
									</p>
									<p class="mb-0 text-right">
										{{
											ite.weight !== null
												? parseFloat(ite.weight).toFixed(2)
												: 0
										}}
										KG
									</p>
								</div>
								<div
									class="mb-0 text-right"
									v-if="
										ite.productionStatusItems &&
											ite.productionStatusItems.length > 0
									"
								>
									<div
										v-for="(value, i) in ite.productionStatusItems"
										:key="i"
									>
										<p class="mb-0">
											{{
												nNumberDecimalFormat(
													value.unit * calculateVolume(ite),
													5
												)
											}}
											CBM
										</p>
										<p class="mb-1">
											{{
												parseFloat(value.unit * calculateWeight(ite)).toFixed(
													2
												)
											}}
											KG
										</p>
									</div>
								</div>
								</div>
							</td>

							<td
								v-if="
									getPoDetail.state == 'in_progress' ||
										getPoDetail.state == 'approved'
								"
							>
							<div v-for="(ite,index) in item.duplicate" 
								:key="ite.id + index">
								<div v-if="ite.productionStatusItems.length == 0">
									<p class="mb-0" style="color: #D68A1A;">
										{{
											ite.productionStatusName
												? ite.productionStatusName
												: "Pending"
										}}
									</p>
								</div>
								<div
									class="mb-0 text-start"
									v-if="
										ite.productionStatusItems &&
											ite.productionStatusItems.length > 0
									"
								>
									<p
										class="mb-0"
										v-for="(value, i) in ite.productionStatusItems"
										:key="i"
										style="color: #D68A1A;"
									>
										{{ value.production_status_name }}
									</p>
								</div>
							</div>
							</td>

							<td
								v-if="
									getPoDetail.state == 'in_progress' ||
										getPoDetail.state == 'approved'
								"
							>
								<div class="po-tb-set po-tb-tr-td-div-padding" v-for="(ite,index) in item.duplicate"
								:key="ite.id + index">
								<div v-if="ite.productionStatusItems.length == 0">
									<p class="mb-0">
										{{ formatDate(ite.productionECRD) }}
									</p>
								</div>
								<div
									class="mb-0"
									v-if="
										ite.productionStatusItems &&
											ite.productionStatusItems.length > 0
									"
								>
									<p
										class="mb-0"
										v-for="(value, i) in ite.productionStatusItems"
										:key="i"
									>
										{{ formatDate(value.expected_cargo_ready_date) }}
									</p>
								</div>
								</div>
							</td>

							<td>
								<div
								class="po-tb-set po-tb-tr-td-div-padding" 
									v-for="(ite,index) in item.duplicate"
									:key="ite.id + index"
								:class="
									checkFeildExists('unit_price', ite.change_log)
										? 'change-log'
										: ''
								">

								<p
									class="mb-0 text-right"
									v-if="!checkFeildExists('unit_price', ite.change_log)"
								>
									${{ ite.unit_price }}
								</p>
								<div v-for="(changedData, i) in ite.change_log" :key="i">
									<div
										class="changed-value"
										v-if="changedData.field == 'unit_price'"
									>
										<p class="mb-0 item-danger">${{ changedData.new_value }}</p>

										<p class="mb-0 item-cancel ">
											${{ changedData.old_value }}
										</p>
									</div>
								</div>
								</div>
							</td>

							<td>
								<div
								class="po-tb-set po-tb-tr-td-div-padding"
								 	v-for="(ite,index) in item.duplicate"
									:key="ite.id + index"
								:class="
									checkFeildExists('amount', ite.change_log)
										? 'change-log'
										: ''
								">

								<p
									class="mb-0 text-right"
									v-if="!checkFeildExists('amount', ite.change_log)"
								>
									${{
										ite.amount !== null
											? parseFloat(ite.amount).toFixed(2)
											: 0
									}}
								</p>
								<div v-for="(changedData, i) in ite.change_log" :key="i">
									<div
										class="changed-value"
										v-if="changedData.field == 'amount'"
									>
										<p class="mb-0 item-danger">${{ changedData.new_value }}</p>

										<p class="mb-0 item-cancel ">
											${{ changedData.old_value }}
										</p>
									</div>
								</div>
								</div>
							</td>
						</tr>
					</tbody>
				</template>
			</v-data-table>

			<div v-if="isMobile" class="mobile-table-details-product-wrapper">
				<div class="mobile-products-header">
					<div class="header-title">DESCRIPTION</div>
					<div class="header-title">AMOUNT</div>
				</div>

				<v-data-table
					:headers="headers"
					:items="soDetailsProducts"
					class="mobile-podetails-product elevation-0"
					hide-default-footer
				>
					<template v-slot:[`item.sku`]="{ item }">
						<div class="mobile-description">
							<p>
								SKU
								<span>
									<span>{{
										item.product.category_sku !== null
											? item.product.category_sku
											: "--"
									}}</span>
								</span>
							</p>
							<p>
								{{
									item.product.description !== "undefined" &&
									item.product.description !== null
										? item.product.description
										: "--"
								}}
							</p>
							<p>
								{{ item.units !== null ? item.units : "0" }} Units x ${{
									item.unit_price !== null
										? parseFloat(item.unit_price).toFixed(2)
										: 0
								}}
							</p>
						</div>

						<div>{{ item.amount }}</div>
					</template>
				</v-data-table>
			</div>

			<div class="poDetail-total">
				<div></div>
				<div></div>
				<div></div>
				<div class="py-3 font-weight-bold">Subtotal</div>
				<div
					class="py-3 font-weight-bold"
					:class="
						checkFeildExists('sub_total', getPoDetail.change_log)
							? 'change-log'
							: ''
					"
				>
					${{
						getPoDetail.sub_total !== null
							? parseFloat(getPoDetail.sub_total).toFixed(2)
							: 0
					}}
				</div>
			</div>

			<div class="poDetail-total">
				<div></div>
				<div></div>
				<div></div>
				<div class="py-3">Tax</div>
				<div
					class="py-3"
					:class="
						checkFeildExists('tax', getPoDetail.change_log) ? 'change-log' : ''
					"
				>
					${{
						getPoDetail.tax !== null
							? parseFloat(getPoDetail.tax).toFixed(2)
							: 0
					}}
				</div>
			</div>

			<div class="poDetail-total">
				<div></div>
				<div></div>
				<div></div>
				<div class="py-3">Shipping</div>
				<div
					class="py-3"
					:class="
						checkFeildExists('shipping', getPoDetail.change_log)
							? 'change-log'
							: ''
					"
				>
					${{
						getPoDetail.shipping !== null
							? parseFloat(getPoDetail.shipping).toFixed(2)
							: 0
					}}
				</div>
			</div>

			<div class="poDetail-total">
				<div></div>
				<div></div>
				<div></div>
				<div class="py-3 border2">Discount</div>
				<div
					class="py-3 border2"
					:class="
						checkFeildExists('discount', getPoDetail.change_log)
							? 'change-log'
							: ''
					"
				>
					${{
						getPoDetail.discount !== null
							? parseFloat(getPoDetail.discount).toFixed(2)
							: 0
					}}
				</div>
			</div>

			<div class="poDetail-total">
				<div></div>
				<div></div>
				<div></div>
				<div class="py-3 font-weight-bold no-border">Total</div>
				<div
					class="py-3 font-weight-bold no-border"
					:class="
						checkFeildExists('total', getPoDetail.change_log)
							? 'change-log'
							: ''
					"
				>
					${{
						getPoDetail.total !== null
							? parseFloat(getPoDetail.total).toFixed(2)
							: 0
					}}
				</div>
			</div>

			<div class="po-notes-wrapper">
				<div class="po-notes">
					<h4>Notes</h4>
					<p>
						{{ getPoDetail.notes !== null ? getPoDetail.notes : "--" }}
					</p>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import _ from "lodash";
import inventoryGlobalMethods from "../../../utils/inventoryMethods/inventoryGlobalMethods";
import globalMethods from "../../../utils/globalMethods";

export default {
	components: {},
	props: ["isMobile"],
	data: () => ({
		headers: [
			{
				text: "SKU & Description",
				align: "start",
				sortable: false,
				value: "sku",
				fixed: true,
				width: "20%",
				sort_order: 1,
			},
			// {
			// 	text: "Buyer's SKU",
			// 	align: "start",
			// 	sortable: false,
			// 	value: "buyer_sku",
			// 	fixed: true,
			// 	width: "30%",
			// },
			{
				text: "Ship Date",
				align: "start",
				sortable: false,
				value: "ship_date",
				fixed: true,
				width: "8%",
				sort_order: 2,
			},
			{
				text: "Carton",
				align: "end",
				sortable: false,
				value: "carton",
				fixed: true,
				width: "8%",
				sort_order: 2,
			},
			{
				text: "In Each",
				align: "end",
				sortable: false,
				value: "in_each",
				fixed: true,
				width: "8%",
				sort_order: 3,
			},
			{
				text: "Unit",
				align: "end",
				sortable: false,
				value: "units",
				fixed: true,
				width: "8%",
				sort_order: 4,
			},
			{
				text: "Unit Price",
				align: "end",
				sortable: false,
				value: "unit_price",
				fixed: true,
				width: "10%",
				sort_order: 8,
			},
			{
				text: "Amount",
				align: "end",
				sortable: false,
				value: "amount",
				fixed: true,
				width: "14%",
				sort_order: 9,
			},
		],
		volume: [
			{
				text: "Volume",
				align: "end",
				sortable: false,
				value: "volume",
				fixed: true,
				width: "12%",
				sort_order: 5,
			},
		],
		weight: [
			{
				text: "Weight",
				align: "end",
				sortable: false,
				value: "weight",
				fixed: true,
				width: "12%",
				sort_order: 6,
			},
		],
		volumeWeight: [
			{
				text: "CBM & Weight",
				align: "end",
				sortable: false,
				value: "volume",
				fixed: true,
				width: "14%",
				sort_order: 5,
			},
		],
		productStatus: [
			{
				text: "Product Status",
				align: "start",
				sortable: false,
				value: "production_status",
				fixed: true,
				width: "15%",
				sort_order: 6,
			},
		],
		expCrd: [
			{
				text: "Exp CRD",
				align: "start",
				sortable: false,
				value: "exp_crd",
				fixed: true,
				width: "13%",
				sort_order: 7,
			},
		],
	}),
	methods: {
		...mapActions([]),
		...inventoryGlobalMethods,
		...globalMethods,
		checkFeildExists(field, changedData) {
			if (changedData) {
				let data = changedData.find((item) => {
					return item.field && item.field === field;
				});
				let check = data && data.field == field ? true : false;
				return check;
			}
		},
		formatDate(date) {
			return this.formatDateOnly(date);
		},
		calculateVolume(item) {
			return item.volume ? (parseFloat(item.volume) * 1) / item.units : 0;
		},
		calculateWeight(item) {
			return item.weight ? (parseFloat(item.weight) * 1) / item.units : 0;
		},
	},
	computed: {
		...mapGetters({
			getPoDetail: "salesOrders/getPoDetail",
		}),
		soDetailsProducts() {
			let poProducts = [];

			if (
				typeof this.getPoDetail !== "undefined" &&
				this.getPoDetail !== null &&
				Array.isArray(this.getPoDetail.purchase_order_products) &&
				this.getPoDetail.purchase_order_products.length > 0
			) {
				let duplicate = this.getPoDetail.purchase_order_products.map((v) =>{
					let duplicate = [
						{
							amount:v.amount,
							change_log:v.change_log,
							classification_code:v.classification_code,
							created_at:v.created_at,
							current_order_history_id:v.current_order_history_id,
							customer_id:v.customer_id,
							deleted_at:v.deleted_at,
							duty_rate:v.duty_rate,
							id:v.id,
							product:v.product,
							product_id:v.product_id,
							productionECRD:v.productionECRD,
							productionStatusItems:v.productionStatusItems,
							productionStatusName:v.productionStatusName,
							purchase_order_id:v.purchase_order_id,
							quantity:v.quantity,
							ship_date:v.ship_date,
							sku:v.sku,
							unit_price:v.unit_price,
							units:v.units,
							units_per_carton:v.units_per_carton,
							unship_cartons:v.unship_cartons,
							updated_at:v.updated_at,
							volume:v.volume,
							weight:v.weight,
						}
					]
					return {duplicate_id:v.product_id,duplicate:duplicate}
				})
				// poProducts = this.getPoDetail.purchase_order_products;
				poProducts = duplicate
			}

			return poProducts;
		},
		finalsoDetailsProducts(){
			const arrayHashmap = this.soDetailsProducts.reduce((obj, item) => {
  				obj[item.duplicate_id] ? obj[item.duplicate_id].duplicate.push(...item.duplicate) : (obj[item.duplicate_id] = { ...item });
  				return obj;
			}, {});
			const mergedArray = Object.values(arrayHashmap);
			return mergedArray
		},
		dynamicHeader() {
			let { state } = this.getPoDetail;
			let dynamicHeader;
			if (state == "approved" || state == "in_progress") {
				dynamicHeader = [
					...this.headers,
					...this.volumeWeight,
					...this.productStatus,
					...this.expCrd,
				];
			} else {
				dynamicHeader = [...this.headers, ...this.volume, ...this.weight];
			}
			return _.orderBy(dynamicHeader, ["sort_order"], ["asc"]);
		},
	},
	updated() {},
};
</script>

<style lang="scss">
.ptoduct-list-p {
	color: #6d858f;
}
.item-danger {
	color: #f93131;
	line-height: 20px;
}
.item-cancel {
	text-decoration-line: line-through;
	line-height: 20px;
	color: #6d858f;
}
.change-log {
	background-color: #fff2f2 !important;
	.changed-value {
		p {
			text-align: right;
		}
	}
}
.po-details-wrapper .po-details-wrapper-content .po-detail-body-contents .po-details-wrapper-table .v-data-table .v-data-table__wrapper tbody tr td {
    padding: 8px 0 !important;
    border-bottom: 1px solid #ebf2f5 !important;
    vertical-align: middle !important;
    color: #4a4a4a;
	margin: 0 !important;
}
.po-tb-set:not(:last-child){
	border-bottom: 1px solid #ebf2f5 !important;
}
.po-tb-tr-td-div-padding{
	padding:12px 12px;
}
</style>
