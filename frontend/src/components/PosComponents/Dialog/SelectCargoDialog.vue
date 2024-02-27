<!-- @format -->

<template>
	<v-dialog
		v-model="dialogCreate"
		max-width="1176px"
		content-class="select-cargo-dialog"
		:retain-focus="false"
		persistent
		scrollable
	>
		<v-card class="cargo-dialog-card">
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
				<button icon dark class="btn-close" @click="close">
					<v-icon>mdi-close</v-icon>
				</button>
			</v-card-title>

			<v-card-text>
				<div class="po-booking-shipment">
					<h3 class="po-booking-counter" v-if="!selectShipment">
						<span class="po-count">02 POs</span> are selected for booking
						request
					</h3>
					<h3 class="po-booking-counter" v-if="selectShipment">
						<span class="po-count">02 POs</span> is selected for shipment
					</h3>
					<v-autocomplete
						spellcheck="false"
						class="select-shipment"
						:items="shipmentListsDropDown"
						placeholder="Select Shipment"
						outlined
						item-text="name"
						item-value="id"
						:menu-props="{
							contentClass: 'shipment-lists-items',
							bottom: true,
							offsetY: true,
						}"
						hide-details="auto"
						clearable
						v-if="selectShipment"
					>
						<template v-slot:selection="{ item, index }">
							<div
								class="v-select__selection v-select__selection--comma"
								:key="index"
							>
								#<span>{{ item.name }}</span>
							</div>
						</template>

						<template v-slot:item="{ item }">
							<div class="option-items" :id="item.id">
								<div class="shipment-info">
									<p class="shipment-no">Ref # {{ item.name }}</p>

									<p class="mbl-no">MBL #{{ item.mbl_no }}</p>

									<p class="mb-0 shipment-date">
										<span> ETD: {{ item.etd }} </span>
										<span> ETA: {{ item.eta }} </span>
									</p>
								</div>
							</div>
						</template>
					</v-autocomplete>
				</div>
				<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
					<fieldset class="mt-4">
						<legend class="po-num-product">
							<v-text-field
								:value="`PO ${poItem.po_number}`"
								outlined
								class="text-fields select-items product-desc"
								hide-details="auto"
								readonly
								disabled
							>
							</v-text-field>
							<v-checkbox
								class="mt-0 product-checkbox shrink"
								label="Select All Products"
								hide-details
							></v-checkbox>
							<button class="btn-delete">
								<img src="@/assets/icons/delete-po.svg" alt="" />
							</button>
						</legend>

						<div class="create-wrapper">
							<div class="product-info">
								<v-data-table
									:headers="headers"
									:items="poItem.poProductData"
									class="elevation-1 add-table"
									mobile-breakpoint="1023"
									hide-default-footer
									:items-per-page="500"
								>
									<template v-slot:body="{ items }">
										<tbody v-if="items.length > 0">
											<tr v-for="(item, index) in items" :key="item.id">
												<td>
													<div class="h-100">
														<v-row align="center">
															<v-checkbox
																hide-details
																class="shrink mt-0"
															></v-checkbox>
															<v-text-field
																:value="`#${item.productDescription}`"
																outlined
																class="text-fields select-items product-desc"
																hide-details="auto"
																readonly
															>
															</v-text-field>
														</v-row>
													</div>
												</td>

												<td>
													<div class="product-sku-name h-100">
														<v-row align="center">
															<v-checkbox
																hide-details
																class="shrink mt-0"
															></v-checkbox>
															<v-text-field
																outlined
																:value="`#${poItem.total_quantity} Cartons`"
																readonly
																auto-grow
																rows="2"
																class="text-fields select-items total-cartons"
																hide-details="auto"
															>
															</v-text-field>
														</v-row>
													</div>
												</td>

												<td>
													<div>
														<v-text-field
															v-model="item.quantity"
															placeholder="0"
															outlined
															class="text-fields select-items text-align"
															hide-details="auto"
															min="1"
															@input="updateCartonCount(product, index)"
															type="number"
														>
														</v-text-field>
													</div>
												</td>

												<td>
													<div>
														<v-text-field
															@input="updateCartonCount(product, index)"
															v-model="item.units"
															placeholder="0"
															outlined
															class="text-fields select-items text-align"
															hide-details="auto"
															min="1"
															type="number"
														>
														</v-text-field>
													</div>
												</td>
												<td>
													<div>
														<v-text-field
															v-model="item.volume"
															placeholder="0"
															outlined
															class="text-fields select-items text-align"
															hide-details="auto"
															min="1"
															suffix="CBM"
														>
														</v-text-field>
													</div>
												</td>

												<td>
													<div>
														<v-text-field
															v-model="item.weight"
															placeholder="0"
															outlined
															class="text-fields select-items text-align"
															hide-details="auto"
															min="1"
															type="number"
															suffix="KG"
														>
														</v-text-field>
													</div>
												</td>

												<td>
													<div>
														<v-text-field
															v-model="poItem.cargo_ready_date"
															type="date"
															class="text-fields select-items text-align"
															placeholder="Select Date"
															outlined
															hide-details="auto"
															disabled
														/>
													</div>
												</td>
											</tr>
										</tbody>
									</template>
								</v-data-table>
								<div class="total-product-desc">
									<div class="total">
										<p>Total Cartons: <span>20</span></p>
									</div>
									<div class="total">
										<p>Total Volume: <span>60.55 CBM</span></p>
									</div>
									<div class="total">
										<p>Total Weight: <span>6,220.500 KG</span></p>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</v-form>
			</v-card-text>

			<v-card-actions class="cargo-button-actions">
				<div class="booking-request" v-if="!selectShipment">
					<button class="btn-blue">
						Proceed
					</button>
				</div>
				<div class="add-shipment" v-else>
					<button class="btn-blue">
						Add to Shipment
					</button>
				</div>
				<button class="btn-white cancel" @click="close">
					Cancel
				</button>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "SelectCargoDialog",
	props: ["dialog", "editedItems", "editedIndex", "isMobile", "selectShipment"],
	components: {},
	data: () => ({
		valid: true,
		headers: [
			{
				text: "Product",
				align: "start",
				sortable: false,
				value: "product",
				fixed: true,
				width: "30%",
			},
			{
				text: "Add All",
				align: "start",
				sortable: false,
				value: "in_each",
				fixed: true,
				width: "15%",
			},
			{
				text: "Carton",
				align: "end",
				sortable: false,
				value: "carton_count",
				fixed: true,
				width: "11%",
			},
			{
				text: "Unit",
				align: "end",
				sortable: false,
				value: "units",
				fixed: true,
				width: "11%",
			},
			{
				text: "Volume",
				align: "end",
				sortable: false,
				value: "volume",
				fixed: true,
				width: "11%",
			},
			{
				text: "Weight",
				align: "end",
				sortable: false,
				value: "weight",
				fixed: true,
				width: "11%",
			},
			{
				text: "Cargo ready",
				align: "end",
				sortable: false,
				value: "cargo_ready",
				fixed: true,
				width: "11%",
			},
		],
		shipmentLists: [
			{
				id: 1,
				ref_no: "123456",
				mbl_no: "MBL#abcde1234567890",
				etd: "Jun 15, 2022",
				eta: "July 07, 2022",
			},
			{
				id: 2,
				ref_no: "784512",
				mbl_no: "MBL#ofdjvkdmiuncdd",
				etd: "Oct 22, 2022",
				eta: "Nov 23, 2022",
			},
			{
				id: 3,
				ref_no: "35154",
				mbl_no: "MBL#piojdicdddcdedcf",
				etd: "Nov 09, 2022",
				eta: "DEC 30, 2022",
			},
		],
	}),
	computed: {
		...mapGetters({}),
		dialogCreate: {
			get() {
				return this.dialog;
			},
			set(value) {
				this.$refs.form.resetValidation();
				this.$emit("update:dialog", value);
			},
		},
		poItem: {
			get() {
				let tempItem = { ...this.editedItems };
				tempItem.poProductData = [];

				if (
					typeof tempItem.purchase_order_products !== "undefined" &&
					tempItem.purchase_order_products.length !== 0
				) {
					tempItem.poProductData = tempItem.purchase_order_products.map(
						(value) => {
							return {
								purchase_order_product_id: value.id,
								productDescription: `${value.product.category_sku} ${value.product.name}`,
								carton: value.quantity,
								units: value.units,
								volume: value.volume,
								weight: value.weight,
							};
						}
					);
				}

				return tempItem;
			},
			set(value) {
				this.$emit("update:editedItems", value);
			},
		},
		formTitle() {
			return "Select Cargo";
		},
		shipmentListsDropDown() {
			return this.shipmentLists.map((value) => ({
				value: value.id,
				name: value.ref_no,
				mbl_no: value.mbl_no,
				etd: value.etd,
				eta: value.eta,
			}));
		},
	},
	methods: {
		...mapActions({
			fetchPoAllNew: "po/fetchPoAllNew",
		}),
		...globalMethods,
		close() {
			this.$refs.form.resetValidation();
			this.$emit("close");
		},
		async updateCartonCount(item, index) {
			if (typeof item.in_each !== "undefined" && item.in_each !== null) {
				let total_units = 0;
				total_units = item.in_each * parseInt(item.quantity);

				this.poItem.po_products_splitting[
					index
				].product_split.units = total_units;
				item.units = total_units;
			}
		},
	},
	async mounted() {},
	updated() {},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/po/selectCargoDialog.scss";
</style>
