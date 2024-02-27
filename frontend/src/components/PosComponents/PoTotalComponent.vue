<!-- @format -->

<template>
	<div class="total-wrapper" :class="className">
		<div class="view-total">
			<v-container class="pa-0">
				<v-row no-gutters class="ma-0">
					<v-col cols="12" sm="5">
						<v-card class="pa-2 sub-total font-weight-medium text-right" tile>
							Subtotal
						</v-card>
					</v-col>
					<v-col cols="12" sm="7">
						<v-card
							class="pa-2 sub-total font-weight-medium text-right pr-3"
							:class="
								checkFeildExists('sub_total', changeLog) ? 'change-log' : ''
							"
							tile
						>
							$ {{ subTotal }}
						</v-card>
					</v-col>
				</v-row>

				<v-row no-gutters class="ma-0">
					<v-col cols="12" sm="5">
						<v-card class="px-3 py-2 text-right" outlined tile>
							<p class="ma-0">Tax</p>
						</v-card>
					</v-col>

					<v-col cols="12" sm="7">
						<v-card class="pa-0" outlined tile>
							<!-- <p  class="mb-6">${{ tax }}</p> -->
							<v-text-field
								type="number"
								class="text-fields select-items field-amount"
								hide-details="auto"
								:value="tax"
								readonly
								outlined
								prefix="$"
								v-if="reviewOrder"
								:class="checkFeildExists('tax', changeLog) ? 'change-log' : ''"
							>
							</v-text-field>
							
							<v-text-field
								type="number"
								class="text-fields select-items field-amount"
								hide-details="auto"
								v-model="taxMutable"
								outlined
								prefix="$"
								@change="getTotalAmount"
								v-else
								@input="changeRequest"
							>
							</v-text-field>
						</v-card>
					</v-col>
				</v-row>

				<v-row no-gutters class="ma-0">
					<v-col cols="12" sm="5">
						<v-card class="px-3 py-2 text-right" outlined tile>
							<p class="ma-0">Shipping</p>
						</v-card>
					</v-col>
					<v-col cols="12" sm="7">
						<v-card class="pa-0" outlined tile>
							<!-- <p v-if="reviewOrder">${{ shipping }}</p> -->
							<v-text-field
								type="number"
								class="text-fields select-items field-amount"
								hide-details="auto"
								:value="shipping"
								readonly
								outlined
								prefix="$"
								v-if="reviewOrder"
								:class="
									checkFeildExists('shipping', changeLog) ? 'change-log' : ''
								"
							>
							</v-text-field>
							<v-text-field
								type="number"
								class="text-fields select-items field-amount"
								outlined
								hide-details="auto"
								v-model="shippingMutable"
								prefix="$"
								@change="getTotalAmount"
								v-else
								@input="changeRequest"
							>
							</v-text-field>
						</v-card>
					</v-col>
				</v-row>

				<v-row no-gutters class="ma-0">
					<v-col cols="12" sm="5">
						<v-card class="px-3 py-2 text-right" outlined tile>
							<p class="ma-0">Discount</p>
						</v-card>
					</v-col>
					<v-col cols="12" sm="7">
						<v-card class="pa-0" outlined tile>
							<!-- <p v-if="reviewOrder">${{ discount }}</p> -->
							<v-text-field
								class="text-fields select-items field-amount"
								hide-details="auto"
								:value="discount"
								readonly
								outlined
								prefix="$"
								v-if="reviewOrder"
								:class="
									checkFeildExists('discount', changeLog) ? 'change-log' : ''
								"
							>
							</v-text-field>
							<v-text-field
								type="number"
								class="text-fields select-items"
								outlined
								hide-details="auto"
								v-model="discountMutable"
								prefix="$"
								@change="getTotalAmount"
								v-else
								@input="changeRequest"
							>
							</v-text-field>
						</v-card>
					</v-col>
				</v-row>
				
				<v-row no-gutters class="ma-0">
					<v-col cols="12" sm="5">
						<v-card class="pa-2 total font-weight-bold text-right" outlined tile>
							Total
						</v-card>
					</v-col>
					<v-col cols="12" sm="7">
						<v-card
							class="pa-2 total font-weight-bold text-right pr-3"
							:class="checkFeildExists('total', changeLog) ? 'change-log' : ''"
							outlined
							tile
						>
							$
							{{
								totalAmount.toLocaleString("en-US", {
									minimumFractionDigits: 2,
								})
							}}
						</v-card>
					</v-col>
				</v-row>
			</v-container>
		</div>
	</div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
	name: "PoTotalComponent",
	props: [
		"subTotal",
		"tax",
		"shipping",
		"discount",
		"total",
		"reviewOrder",
		"orderType",
		"changeLog",
		"fromComponent",
		"items",
		"poItemData",
		"soItemData",
		"dialog",
		"className"
	],
	data() {
		return {
			taxMutable: this.tax,
			shippingMutable: this.shipping,
			discountMutable: this.discount,
		};
	},
	computed: {
		...mapGetters({
			poDetails: "po/getPoDetail",
			salesOrdersDetails: "salesOrders/getPoDetail",
		}),
		totalAmount() {
			let total =
				parseFloat(this.subTotal) +
				parseFloat(
					this.taxMutable && this.taxMutable != "" ? this.taxMutable : 0
				) +
				parseFloat(
					this.shippingMutable && this.shippingMutable != ""
						? this.shippingMutable
						: 0
				) -
				parseFloat(
					this.discountMutable && this.discountMutable != ""
						? this.discountMutable
						: 0
				);

			return total;
		},
	},
	methods: {
		getTotalAmount() {
			let data = {
				tax: this.taxMutable == "" ? 0 : this.taxMutable,
				shipping: this.shippingMutable == "" ? 0 : this.shippingMutable,
				discount: this.discountMutable == "" ? 0 : this.discountMutable,
				total: this.totalAmount,
			};

			this.$emit("totalAmount", data);
		},
		changeRequest() {
			let changeButtonFlag = false;
			if (this.dialog == "reviewDialog") {
				if (
					this.fromComponent == "po-details-page" ||
					this.fromComponent == "so-details-page"
				) {
					const itemRaw =
						this.orderType == "PO" ? this.poDetails : this.salesOrdersDetails;

					if (itemRaw.tax != this.taxMutable) {
						changeButtonFlag = true;
					} else if (itemRaw.shipping != this.shippingMutable) {
						changeButtonFlag = true;
					} else if (itemRaw.discount != this.discountMutable) {
						changeButtonFlag = true;
					}
				} else {
					let data;
					if (this.fromComponent == "po-desktop") {
						data = this.items.find((item) => {
							return item.id && item.id === this.poItemData.id;
						});
					} else {
						data = this.items.find((item) => {
							return item.id && item.id === this.soItemData.id;
						});
					}
					let { tax, shipping, discount } = data;
					if (tax != this.taxMutable) {
						changeButtonFlag = true;
					} else if (shipping != this.shippingMutable) {
						changeButtonFlag = true;
					} else if (discount != this.discountMutable) {
						changeButtonFlag = true;
					}
				}
				this.$emit("changeButton", changeButtonFlag);
			}
		},
		checkFeildExists(field, changedData) {
			if (changedData) {
				let data = changedData.find((item) => {
					return item.field && item.field === field;
				});
				let check = data && data.field == field ? true : false;
				return check;
			}
		},
	},
	watch: {
		shipping() {
			this.shippingMutable = this.$props.shipping;
		},
		tax() {
			this.taxMutable = this.$props.tax;
		},
		discount() {
			this.discountMutable = this.$props.discount;
		},
	},
};
</script>
