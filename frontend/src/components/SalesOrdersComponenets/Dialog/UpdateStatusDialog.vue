<!-- @format -->

<template>
	<v-dialog
		v-model="dialogCreate"
		max-width="1392px"
		content-class="update-status-dialog"
		:retain-focus="false"
		persistent
		scrollable
	>
		<v-card class="po-dialog-card">
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
				<button icon dark class="btn-close" @click="close">
					<v-icon>mdi-close</v-icon>
				</button>
			</v-card-title>

			<v-card-text>
				<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
					<div class="create-wrapper">
						<div class="po-info">
							<div class="update-status-section-1">
								<div class="po-number mb-3">
									<p class="po-title">Sales Order Number</p>
									<v-text-field
										class="select-items deposite"
										placeholder=""
										:value="poItem.po_number"
										outlined
										hide-details="auto"
										disabled
									>
									</v-text-field>
								</div>

								<div class="po-vendor mb-3 overall-status">
									<p class="po-title">Overall</p>
									<v-container fluid class="pl-0">
										<v-checkbox
											v-model="overallCheckbox"
											label="Same status & CRD for all SKUs"
											hide-details
										></v-checkbox>
									</v-container>
								</div>
							</div>
							<div class="update-status-section-2">
								<div class="po-cargo mb-3">
									<p class="po-title">Committed Cargo Ready Date</p>
									<v-text-field
										type="date"
										class="text-fields select-items"
										placeholder="Select Date"
										outlined
										hide-details="auto"
										v-model="poItem.committed_cargo_ready_date"
										disabled
									/>
								</div>

								<div class="po-vendor mb-3">
									<p class="po-title">Overall Product Status</p>
									<v-autocomplete
										class="select-items"
										:items="productStatus"
										v-model="overall_status"
										placeholder="Select Status"
										item-text="name"
										item-value="value"
										outlined
										hide-details="auto"
										:menu-props="{
											bottom: true,
											offsetY: true,
										}"
										:disabled="!overallCheckbox"
										:rules="overallCheckbox ? rules : []"
									>
									</v-autocomplete>
								</div>
							</div>

							<div class="update-status-section-3">
								<div class="deposite-status">
									<p class="po-title">Deposit Status</p>
									<v-radio-group row v-model="productionDepositStatus">
										<v-radio
											:label="
												connectedCustomer == 'vendor-connected'
													? 'Received'
													: 'Deposited'
											"
											value="received"
										></v-radio>
										<v-radio
											:label="
												connectedCustomer == 'vendor-connected'
													? 'Not Received'
													: 'Not Deposited'
											"
											value="not_received"
										></v-radio>
									</v-radio-group>
								</div>

								<div class="po-cargo mb-3">
									<p class="po-title">Expected Cargo Ready Date</p>
									<v-text-field
										type="date"
										class="text-fields select-items"
										placeholder="Select Date"
										outlined
										hide-details="auto"
										:min="currentDateFind"
										v-model="overall_expected_crd"
										:disabled="!overallCheckbox"
										:rules="overallCheckbox ? rules : []"
									/>
								</div>
							</div>

							<div class="update-status-section-4">
								<div class="po-notes">
									<p class="po-title">
										Notes
										<span style="text-transform: capitalize !important;"
											>(Optional)</span
										>
									</p>
									<v-textarea
										class="note"
										outlined
										v-model="productionNotes"
										name="input-7-4"
										placeholder="Type your notes here..."
										hide-details="auto"
									>
									</v-textarea>
								</div>
							</div>
						</div>

						<div class="product-info">
							<v-data-table
								:headers="headers"
								:items="poItem.po_products_splitting"
								:loading="poItem.loadingState"
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
													<!-- v-model="item.sku" -->
													<v-text-field
														:value="`#${item.sku}`"
														outlined
														class="text-fields select-items"
														hide-details="auto"
														disabled
														readonly
													>
													</v-text-field>
												</div>
											</td>

											<td>
												<div class="product-sku-name h-100">
													<v-text-field
														outlined
														:value="`#${item.description}`"
														readonly
														auto-grow
														rows="2"
														class="text-fields select-items"
														hide-details="auto"
														disabled
													>
													</v-text-field>
												</div>
											</td>

											<td>
												<div
													class="item-actions"
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : ''"
												>
													<div
														class="button-wrappers"
														:class="
															item.product_split.length > 1
																? 'border-right-add'
																: ''
														"
													>
														<button
															class="btn-icon"
															@click="splitProducts(product, index, i)"
															:disabled="
																splitButtonDisabled(product) || overallCheckbox
															"
														>
															<InfoTooltip
																:customClass="
																	overallCheckbox ? 'hide-tooltip' : ''
																"
															>
																<template v-slot:tooltip_icon>
																	<div class="header-wrapper-close">
																		<img
																			src="@/assets/icons/split-po-icon.svg"
																			alt="Split"
																			width="18px"
																			height="18px"
																		/>
																	</div>
																</template>
																<template v-slot:tooltip_info>
																	Split Production
																</template>
															</InfoTooltip>
														</button>
													</div>

													<div
														v-if="item.product_split.length > 1"
														class="button-wrappers border-right-add"
														:disabled="overallCheckbox"
													>
														<button
															class="btn-black"
															@click="mergeTop(product, index, i)"
															:disabled="i === 0 || overallCheckbox"
														>
															<div class="header-wrapper-close">
																<img
																	src="@/assets/icons/merge-top-icon.svg"
																	alt="Split"
																	width="18px"
																	height="18px"
																/>
															</div>
														</button>
													</div>

													<div
														v-if="item.product_split.length > 1"
														class="button-wrappers"
													>
														<button
															class="btn-black"
															@click="mergeBottom(product, index, i)"
															:disabled="
																item.product_split.length - 1 === i ||
																	overallCheckbox
															"
														>
															<div class="header-wrapper-close">
																<img
																	src="@/assets/icons/merge-bottom-icon.svg"
																	alt="Split"
																	width="18px"
																	height="18px"
																/>
															</div>
														</button>
													</div>
												</div>
											</td>

											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													v-bind:class="{
														'border-top-add': i !== 0,
														'error-outline':
															product.quantity > product.expected_qty,
													}"
												>
													<!-- 'error-outlined': checkErrorCarton(product) -->
													<v-text-field
														v-model="product.quantity"
														placeholder="0"
														outlined
														class="table-text-fields"
														hide-details="auto"
														min="1"
														@input="updateCartonCount(product, index)"
														type="number"
													>
													</v-text-field>
												</div>
											</td>

											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : ''"
												>
													<v-text-field
														@input="updateCartonCount(product, index)"
														v-model="product.units"
														placeholder="0"
														outlined
														class="table-text-fields"
														hide-details="auto"
														min="1"
														type="number"
													>
													</v-text-field>
												</div>
											</td>

											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : ''"
												>
													<v-autocomplete
														placeholder="Select Status"
														spellcheck="false"
														v-model="product.status"
														class="select-product"
														:class="item.id"
														:items="productStatus"
														outlined
														item-text="name"
														item-value="value"
														:menu-props="{ bottom: true, offsetY: true }"
														hide-details="auto"
														:disabled="overallCheckbox"
														:rules="!overallCheckbox ? rules : []"
													>
													</v-autocomplete>
												</div>
											</td>

											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : ''"
												>
													<v-text-field
														v-model="product.expected_crd"
														type="date"
														class="text-fields select-items"
														:class="product.expected_crd"
														placeholder="Select Date"
														outlined
														hide-details="auto"
														:disabled="overallCheckbox"
														:rules="!overallCheckbox ? rules : []"
														:min="currentDateFind"
													/>
												</div>
											</td>
										</tr>
									</tbody>
								</template>
							</v-data-table>
						</div>
					</div>
				</v-form>
			</v-card-text>

			<v-card-actions class="po-button-actions">
				<button
					class="btn-blue"
					@click="updateStatusAction"
					:disabled="loaderFlag"
				>
					Update Status
				</button>

				<button class="btn-white cancel" @click="close" :disabled="loaderFlag">
					Cancel
				</button>
				<v-overlay v-if="loaderFlag">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</v-overlay>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import InfoTooltip from "../../Tooltip/InfoTooltip.vue";
import globalMethods from "../../../utils/globalMethods";
import _ from "lodash";

export default {
	name: "POCreateDialog",
	props: [
		"dialog",
		"editedItems",
		"editedIndex",
		"isMobile",
		"connectedCustomer",
		"productionOverAllStatus",
		"productionExpectedCrd",
		"productDepositeStatus",
	],
	components: {
		InfoTooltip,
	},
	data: () => ({
		valid: true,
		current_page: 1,
		radioGroup1: "generated",
		headersMobile: [
			{
				text: "QUANTITY",
				align: "start",
				sortable: false,
				value: "quantity",
				fixed: true,
				width: "90%",
			},
			{
				text: "UNIT PRICE",
				align: "end",
				sortable: false,
				value: "unit_price",
				fixed: true,
				width: "90%",
			},
			{
				text: "AMOUNT",
				align: "end",
				sortable: false,
				value: "amount",
				fixed: true,
				width: "90%",
			},
		],
		headers: [
			{
				text: "SKU",
				align: "start",
				sortable: false,
				value: "sku",
				fixed: true,
				width: "10%",
			},
			{
				text: "Description",
				align: "start",
				sortable: false,
				value: "description",
				fixed: true,
				width: "20%",
			},
			{
				text: "Actions",
				align: "center",
				sortable: false,
				value: "actions",
				fixed: true,
				width: "8%",
			},
			{
				text: "Carton",
				align: "end",
				sortable: false,
				value: "quantity",
				fixed: true,
				width: "8%",
			},
			// {
			// 	text: "In Each",
			// 	align: "end",
			// 	sortable: false,
			// 	value: "in_each",
			// 	fixed: true,
			// 	width: "8%",
			// },
			{
				text: "Unit",
				align: "end",
				sortable: false,
				value: "units",
				fixed: true,
				width: "8%",
			},
			// {
			// 	text: "Volume",
			// 	align: "end",
			// 	sortable: false,
			// 	value: "volume",
			// 	fixed: true,
			// 	width: "8%",
			// },
			// {
			// 	text: "Weight",
			// 	align: "end",
			// 	sortable: false,
			// 	value: "weight",
			// 	fixed: true,
			// 	width: "8%",
			// },
			{
				text: "Production Status",
				align: "start",
				sortable: false,
				value: "production_status",
				fixed: true,
				width: "15%",
			},
			{
				text: "Exp CRD",
				align: "start",
				sortable: false,
				value: "exp_crd",
				fixed: true,
				width: "12%",
			},
		],
		poUpdateStatusProducts: [],
		rules: [(v) => !!v],
		productionDepositStatus: "received",
		productionNotes: "",
		overallCheckbox: "",
		overall_status: "",
		overall_expected_crd: "",
		productHasError: false,
		dialogOpen: false,
		loaderFlag: false,
	}),
	computed: {
		...mapGetters({
			getProductStatusValue: "orders/getProductStatusValue",
			getProductionStatusLoading: "orders/getProductionStatusLoading",
		}),
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
				tempItem.po_products_splitting = [];

				if (
					typeof tempItem.purchase_order_products !== "undefined" &&
					tempItem.purchase_order_products.length !== 0
				) {
					tempItem.po_products_splitting = tempItem.purchase_order_products.map(
						(v) => {
							let splitArray = [];

							if (
								v.productionStatusItems &&
								v.productionStatusItems.length > 0
							) {
								v.productionStatusItems.map((item) => {
									splitArray.push({
										original_qty_count: item.carton,
										expected_qty: item.carton,
										expected_units: item.unit,
										quantity: item.carton,
										units: item.unit,
										status: item.production_status,
										expected_crd: item.expected_cargo_ready_date,
										in_each: item.in_each,
										hasError: false,
									});
								});
							} else {
								splitArray = [
									{
										original_qty_count: v.quantity,
										expected_qty: v.quantity,
										expected_units: v.units,
										quantity: v.quantity,
										units: v.units,
										status: this.overallCheckbox ? this.overall_status : 2,
										expected_crd: this.overall_expected_crd,
										in_each: v.units_per_carton,
										hasError: false,
									},
								];
							}
							return {
								product: v.product,
								purchase_order_product_id: v.id,
								sku: v.product.sku,
								description: `${v.product.category_sku} ${v.product.name}`,
								product_split: splitArray,
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
			return "Update Status";
		},
		currentDateFind() {
			return new Date().toISOString().substr(0, 10);
		},
		productStatus() {
			return this.getProductStatusValue.map((value) => ({
				value: value.id,
				name: value.status,
			}));
		},
	},
	watch: {
		overall_status(val) {
			if (this.overallCheckbox) {
				this.poItem.po_products_splitting.map((item) => {
					item.product_split.map((v) => {
						v.status = val;
					});
				});
			}
		},
		overall_expected_crd(val) {
			if (this.overallCheckbox) {
				this.poItem.po_products_splitting.map((item) => {
					item.product_split.map((v) => {
						v.expected_crd = val;
					});
				});
			}
		},
	},
	methods: {
		...mapActions({
			updateProductionStatus: "orders/updateProductionStatus",
			getSalesOrdersDetails: "salesOrders/getPo",
			getPoDetails: "po/getPo",
			fetchPoHistory: "poDetails/fetchPoHistory",
		}),
		...globalMethods,
		close() {
			this.$refs.form.resetValidation();
			this.radioGroup1 = "generated";
			this.$emit("close");
			this.poUpdateStatusProducts = [];
			this.overallCheckbox = false;
			this.overall_status = "";
			this.overall_expected_crd = "";
			this.dialogOpen = false;
		},
		checkErrorCarton(product) {
			// product.quantity > product.expected_qty
			console.log(product);
		},
		generateErrorMessage() {
			let { po_products_splitting } = this.poItem;
			let productionData = [];

			po_products_splitting.map((item) => {
				let orderId = item.purchase_order_product_id;
				item.product_split.map((value) => {
					productionData.push({
						purchase_order_product_id: orderId,
						production_status: value.status,
						carton: value.quantity,
						unit: value.units,
						in_each: value.in_each,
						expected_cargo_ready_date: value.expected_crd
							? value.expected_crd
							: null,
						hasError: value.hasError,
					});
				});
			});

			let findProductIndex = _.findIndex(
				productionData,
				(e) => e.hasError === true
			);
			if (findProductIndex > -1) {
				this.productHasError = true;
			} else {
				this.productHasError = false;
			}
		},
		async updateStatusAction() {
			this.$refs.form.validate();
			this.generateErrorMessage();

			if (this.$refs.form.validate()) {
				if (!this.productHasError) {
					let { id, po_products_splitting } = this.poItem;

					let productionData = [];

					po_products_splitting.map((item) => {
						let orderId = item.purchase_order_product_id;
						item.product_split.map((value) => {
							productionData.push({
								purchase_order_product_id: orderId,
								production_status: value.status,
								carton: value.quantity,
								unit: value.units,
								in_each: value.in_each,
								expected_cargo_ready_date: value.expected_crd
									? value.expected_crd
									: null,
								po_product_id:value.po_product_id !== null ? value.po_product_id  :null
							});
						});
					});

					let payload = {
						id: id,
						production_deposit_status: this.productionDepositStatus,
						production_notes: this.productionNotes,
						production_overall: this.overallCheckbox,
						production_expected_crd: this.overallCheckbox
							? this.overall_expected_crd
							: null,
						production_overall_status: this.overallCheckbox
							? this.overall_status
							: null,
						products: productionData,
					};
					this.loaderFlag = true;
					await this.updateProductionStatus(payload);
					if (this.connectedCustomer == "vendor-connected") {
						await this.getSalesOrdersDetails(id);
					} else if (this.connectedCustomer == "vendor-not-connected") {
						await this.getPoDetails(id);
					}
					this.notificationMessage("Status has been updated.");
					this.loaderFlag = false;
					await this.close();
					await this.fetchPoHistory(id);
				} else {
					this.notificationErrorCustom("Total Cartoon count execeeded");
				}
			}
		},
		splitProducts(item, index, i) {
			let getItem = this.poItem.po_products_splitting[index].product_split;

			if (item !== null) {
				let new_qty = 0;
				let new_units = 0;
				let new_row = {};

				if (item.quantity > 0) {
					// check if qty is odd
					if (item.quantity % 2 !== 0) {
						// subtract 1 if the number is odd
						new_qty = (item.quantity - 1) / 2;
						new_units = new_qty * item.in_each;

						// add the remainder 1 to the previous index
						getItem[i].quantity = new_qty + 1;
						getItem[i].units = getItem[i].quantity * getItem[i].in_each;
						getItem[i].expected_qty = getItem[i].quantity;
						getItem[i].expected_units = getItem[i].units;
					} else {
						new_qty = item.quantity / 2;
						new_units = new_qty * item.in_each;
						item.quantity = new_qty;
						item.units = new_units;
						item.expected_qty = new_qty;
						item.expected_units = new_units;
					}
				} else {
					if (item.units % 2 !== 0) {
						new_units = (item.units - 1) / 2;

						getItem[i].quantity = new_qty;
						getItem[i].units = new_units + 1;
						getItem[i].expected_qty = new_qty;
						getItem[i].expected_units = getItem[i].units;
					} else {
						new_units = item.units / 2;
						item.quantity = new_qty;
						item.units = new_units;
						item.expected_qty = new_qty;
						item.expected_units = new_units;
					}
				}

				new_row = {
					quantity: new_qty,
					units: new_units,
					in_each: item.in_each,
					status: item.status,
					expected_crd: item.expected_crd,
					expected_qty: new_qty,
					expected_units: new_units,
				};

				getItem.splice(i + 1, 0, new_row);
			}

			this.poItem.po_products_splitting[index].product_split = getItem;
		},
		mergeTop(item, index, i) {
			let beforeItem = i - 1;
			let getItem = this.poItem.po_products_splitting[index].product_split;

			if (index !== -1) {
				let mergeQty =
					parseInt(item.quantity) + parseInt(getItem[beforeItem].quantity);
				let mergeUnits =
					parseInt(item.units) + parseInt(getItem[beforeItem].units);
				getItem[beforeItem].quantity = mergeQty;
				getItem[beforeItem].units = mergeUnits;
				// getItem[beforeItem].expected_qty = getItem[beforeItem].original_qty_count;
				getItem[beforeItem].expected_qty = mergeQty;

				let getIndex = this.poItem.po_products_splitting[
					index
				].product_split.indexOf(item);
				this.poItem.po_products_splitting[index].product_split.splice(
					getIndex,
					1
				);

				if (parseInt(item.quantity) > item.expected_qty) {
					item.hasError = true;
				} else {
					item.hasError = false;
				}
			}
		},
		mergeBottom(item, index, i) {
			let afterItem = i + 1;
			let getItem = this.poItem.po_products_splitting[index].product_split;

			if (afterItem < getItem.length) {
				let mergeQty =
					parseInt(item.quantity) + parseInt(getItem[afterItem].quantity);
				let mergeUnits =
					parseInt(item.units) + parseInt(getItem[afterItem].units);
				getItem[afterItem].quantity = mergeQty;
				getItem[afterItem].units = mergeUnits;
				// getItem[afterItem].expected_qty = getItem[afterItem].original_qty_count;
				getItem[afterItem].expected_qty = mergeQty;

				let getIndex = this.poItem.po_products_splitting[
					index
				].product_split.indexOf(item);
				this.poItem.po_products_splitting[index].product_split.splice(
					getIndex,
					1
				);

				if (parseInt(item.quantity) > item.expected_qty) {
					item.hasError = true;
				} else {
					item.hasError = false;
				}
			}
		},
		async updateCartonCount(item, index) {
			if (typeof item.in_each !== "undefined" && item.in_each !== null) {
				let total_units = 0;
				total_units = item.in_each * parseInt(item.quantity);

				this.poItem.po_products_splitting[
					index
				].product_split.units = total_units;
				item.units = total_units;

				if (parseInt(item.quantity) > item.expected_qty) {
					item.hasError = true;
				} else {
					item.hasError = false;
				}
			}
		},
		splitButtonDisabled(product) {
			if (product.quantity > 0) {
				return product.quantity <= 1 ? true : false;
			} else {
				return product.units <= 1 ? true : false;
			}
		},
	},
	async mounted() {
		if (
			typeof this.poItem !== "undefined" &&
			this.overallCheckbox !== this.poItem.production_overall &&
			this.overall_status !== this.poItem.production_overall_status &&
			this.overall_expected_crd !== this.poItem.committed_cargo_ready_date
		) {
			let overallCheckbox = !!this.poItem.production_overall;

			// set values
			this.overallCheckbox = overallCheckbox;
			this.overall_status = overallCheckbox
				? this.poItem.production_overall_status
				: null;
			this.overall_expected_crd = this.poItem.committed_cargo_ready_date;
		}
	},
	updated() {
		if (this.editedIndex === -1) {
			if (typeof this.$refs.form !== "undefined") {
				if (typeof this.$refs.form.resetValidation() !== "undefined") {
					this.$refs.form.resetValidation();
				}
			}
		}

		if (this.dialog && !this.dialogOpen) {
			this.dialogOpen = true;
			if (this.poItem.production_overall == 1) {
				let ECRD = null;
				this.poItem.purchase_order_products.map((item) => {
					item.productionStatusItems.map((v) => {
						ECRD = v.expected_cargo_ready_date;
					});
				});

				this.overallCheckbox = true;
				this.overall_status = this.poItem.production_overall_status;
				this.overall_expected_crd = ECRD;
			}
			this.productionDepositStatus =
				this.poItem.production_deposit_status !== null
					? this.poItem.production_deposit_status
					: "received";
			this.productionNotes = this.poItem.production_notes;
		}
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/salesOrders/updateStatusDialog.scss";
</style>
