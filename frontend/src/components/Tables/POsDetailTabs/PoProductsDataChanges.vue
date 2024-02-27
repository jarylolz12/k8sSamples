<!-- @format -->

<template>
	<div class="po-details-wrapper-table products">
		<div>
			<v-data-table
				:headers="headers"
				:items="poDetailsProducts"
				class="desktop-podetails-product elevation-0"
				hide-default-footer
				mobile-breakpoint="1023"
			>
				<template v-slot:body="{ items }">
					<thead v-if="isMobile" class="mobile-header">
						<td class="pl-2">Fields</td>
						<td class="text-right">Original</td>
						<td class="text-right">Updated</td>
					</thead>
					<tbody>
						<tr v-if="orderInfoChanged && orderInfoChanged.length > 0">
							<td class="product-detail">
								<div class="product-changed-field ml-4">
									<div v-for="(changedField, i) in orderInfoChanged" :key="i">
										<p class="mb-0 py-1 text-capitalize">
											{{ fieldName(changedField.field) }}
										</p>
									</div>
								</div>
							</td>

							<td class="orignal-order-info">
								<div v-for="(changedField, i) in orderInfoChanged" :key="i">
									<p class="mb-0 text-right py-1">
										{{ changedField.old_value ? changedField.old_value : "--" }}
									</p>
								</div>
							</td>
							<td
								class="updated-order-info"
								:class="
									changeRequestBy(getPoDetail)
										? 'issuer-changed'
										: 'other-party-changed'
								"
							>
								<div v-for="(changedField, i) in orderInfoChanged" :key="i">
									<p class="mb-0 text-right py-1">
										{{ changedField.new_value }}
									</p>
								</div>
							</td>
						</tr>
						<tr v-for="(item, i) in items" :key="i">
							<td class="product-detail">
								<div class="product-description">
									<p class="mb-0 product-sku">
										{{
											item.product.category_sku !== null
												? "SKU #" + item.product.category_sku
												: "--"
										}}
									</p>
									<p class="mb-0 product-name">{{ item.product.name }}</p>
								</div>
								<div class="product-changed-field ml-4">
									<div v-for="(changedField, i) in item.change_log" :key="i">
										<p class="mb-0 py-1 text-capitalize">
											{{ changedField.field }}
										</p>
									</div>
								</div>
								<p class="mt-2">
									{{item.ship_date !== null ? 'Ship Date' :''}}
								</p>
							</td>

							<td class="orignal-product-value">
								<div v-for="(changedField, i) in item.change_log" :key="i">
									<p class="mb-0 text-right py-1">
										{{
											changedField.field == "volume" ||
											changedField.field == "weight"
												? parseFloat(changedField.old_value).toFixed(2)
												: changedField.old_value
										}}
										<span v-if="changedField.field == 'volume'">CBM</span>
										<span v-if="changedField.field == 'weight'">KG</span>
									</p>
								</div>
							</td>
							<td
								class="updated-product-value"
								:class="
									changeRequestBy(getPoDetail)
										? 'issuer-changed'
										: 'other-party-changed'
								"
							>
								<div v-for="(changedField, i) in item.change_log" :key="i">
									<p class="mb-0 text-right py-1">
										{{
											changedField.field == "volume" ||
											changedField.field == "weight"
												? parseFloat(changedField.new_value).toFixed(2)
												: changedField.new_value
										}}
										<span v-if="changedField.field == 'volume'">CBM</span>
										<span v-if="changedField.field == 'weight'">KG</span>
									</p>
								</div>
								<p class="text-end">{{item.ship_date !== null ? dateFormat(item.ship_date) :''}}</p>
							</td>
						</tr>
					</tbody>
				</template>
			</v-data-table>
		</div>
	</div>
</template>

<script>
import { mapGetters } from "vuex";
import moment from 'moment';
export default {
	components: {},
	props: ["isMobile"],
	data: () => ({
		headers: [
			{
				text: "Fields",
				align: "start",
				sortable: false,
				value: "fields",
				fixed: true,
				width: "70%",
			},
			{
				text: "Original",
				align: "end",
				sortable: false,
				value: "orignal_value",
				fixed: true,
				width: "15%",
			},
			{
				text: "Updated",
				align: "end",
				sortable: false,
				value: "updated_value",
				fixed: true,
				width: "15%",
			},
		],
	}),
	methods: {
		changeRequestBy(item) {
			let defaultCustomerId =
				typeof this.getUser.default_customer_id !== "undefined"
					? this.getUser.default_customer_id
					: JSON.parse(this.getUser).default_customer_id;

			if (defaultCustomerId == item.cr_by) {
				return true;
			} else {
				return false;
			}
		},
		fieldName(field) {
			return field.replaceAll("_", " ");
		},
		dateFormat(value){
			return moment(value).format("MMM Do YY");
		}
	},
	computed: {
		...mapGetters({
			getPoDetail: "po/getPoDetail",
			getUser: "getUser",
		}),
		poDetailsProducts() {
			let poProducts = [];

			if (
				typeof this.getPoDetail !== "undefined" &&
				this.getPoDetail !== null &&
				Array.isArray(this.getPoDetail.purchase_order_products) &&
				this.getPoDetail.purchase_order_products.length > 0
			) {
				poProducts = this.getPoDetail.purchase_order_products;
			}
			return poProducts;
		},
		orderInfoChanged() {
			let poInfo = [];

			if (
				typeof this.getPoDetail !== "undefined" &&
				this.getPoDetail !== null &&
				Array.isArray(this.getPoDetail.change_log) &&
				this.getPoDetail.change_log.length > 0
			) {
				poInfo = this.getPoDetail.change_log.filter(
					(item) =>
						item.field !== "cr_by" &&
						item.field !== "updated_by" &&
						item.field !== "signature_by"
				);
			}
			return poInfo;
		},
	},
};
</script>

<style lang="scss">
.v-data-table {
	table {
		thead {
			&.v-data-table-header-mobile {
				display: none;
			}
		}
		tbody {
			tr {
				td {
					.product-description {
						.product-sku {
							line-height: 20px;
						}
						.product-name {
							line-height: 20px;
							color: #6d858f;
						}
					}

					&.orignal-order-info,
					&.updated-order-info {
						border-left: 2px solid #ebf2f5;
					}

					&.orignal-product-value,
					&.updated-product-value {
						vertical-align: bottom !important;
						border-left: 2px solid #ebf2f5;
					}

					&.updated-product-value,
					&.updated-order-info {
						&.issuer-changed {
							background-color: #fff9f0;
						}
						&.other-party-changed {
							background-color: #fff2f2;
						}
						p {
							font-weight: 400;
							font-size: 14px;
							line-height: 20px;
							color: #f93131;
						}
					}
				}
			}
		}
	}
}
</style>
