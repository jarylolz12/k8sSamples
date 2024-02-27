<!-- @format -->

<template>
	<div class="sales-orders-wrapper" v-resize="onResize">
		<!-- Desktop -->
		<SalesOrdersDesktopTable
			:selectedPO.sync="selected"
			@createPo="createPo"
			@editPo="checkEditPo"
			@reviewOrder="checkReviewOrder"
			@email="email"
			@viewPo="openPoView"
			@deletePo="openDelete"
			@deleteMultiple="deleteMultiple"
			@downloadMultiple="downloadMultiple"
			@clearSelectedPO="clearSelectedPO"
			@currentTab="currentTab"
			:isMobile="isMobile"
			v-if="!isMobile"
			:badges="getOrderCounterValue"
		/>

		<!-- Mobile -->
		<SalesOrdersMobileTable
			:selectedPO.sync="selected"
			@createPo="createPo"
			@editPo="checkEditPo"
			@email="email"
			@viewPo="openPoView"
			@deletePo="openDelete"
			v-if="isMobile"
			:isMobile="isMobile"
		/>

		<SalesOrderCreateDialog
			:dialog.sync="dialogCreatePo"
			:editedIndex.sync="editedPoIndex"
			:editedItems.sync="editedPoItems"
			@close="closePoCreate"
			fromComponent="so-desktop"
			:isMobile="isMobile"
		/>

		<SalesOrderReviewDialog
			:dialog.sync="reviewDialogSo"
			:editedIndex.sync="editedPoIndex"
			:editedItems.sync="editedPoItems"
			@close="closeReviewPo"
			fromComponent="so-desktop"
			:isMobile="isMobile"
		/>

		<PoEmail
			:dialog.sync="dialogEmail"
			:editedItems.sync="editedEmailItem"
			:editedIndex.sync="editedIndexEmail"
			:isMobile="isMobile"
			@close="closeEmail"
		/>

		<DeleteDialog
			:dialogData.sync="dialogPoDelete"
			:editedItemData.sync="currentPoToDelete"
			:editedIndexWarehouse.sync="editedPoIndex"
			:defaultItemWarehouse.sync="defaultPoItems"
			@delete="deletePoConfirm"
			@close="closePoDelete"
			fromComponent="purchase order"
			:loadingDelete="getPoDeleteLoading"
			componentName="Purchase Orders"
		/>

		<POWarning
			:dialogData.sync="dialogWarning"
			:poData.sync="poData"
			@editPo="editPo"
			@close="closeWarning"
		/>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import SalesOrdersDesktopTable from "../components/Tables/SalesOrders/SalesOrdersDesktopTable.vue";
import SalesOrdersMobileTable from "../components/Tables/SalesOrders/SalesOrdersMobileTable.vue";
import SalesOrderCreateDialog from "../components/SalesOrdersComponenets/Dialog/SalesOrderCreateDialog";
import SalesOrderReviewDialog from "../components/SalesOrdersComponenets/Dialog/SalesOrderReviewDialog.vue";
import PoEmail from "../components/PosComponents/Dialog/PoEmail.vue";
import DeleteDialog from "../components/Dialog/DeleteDialog.vue";
import globalMethods from "../utils/globalMethods";
import axios from "axios";
import _ from "lodash";
import POWarning from "../components/PosComponents/Dialog/POWarning.vue";
import { ACTIVE } from "../constants/states";

export default {
	name: "SalesOrders",
	components: {
		SalesOrdersDesktopTable,
		SalesOrdersMobileTable,
		SalesOrderCreateDialog,
		PoEmail,
		DeleteDialog,
		POWarning,
		SalesOrderReviewDialog,
	},
	data: () => ({
		hasMounted: false,
		dialogCreatePo: false,
		editedPoIndex: -1,
		editedPoItems: {
			products: [
				{
					id: null,
					// quantity: 0,
					carton_count: 0,
					units: 0,
					unit_price: 0,
					amount: 0,
					units_per_carton: 0,
					volume: 0,
					weight: 0,
				},
			],
			po_number: "",
			is_system_generated: 1,
			buyer_id: "",
			customer_id: "",
			notes: "",
			created_by: "",
			tax: 0,
			warehouse_id: "",
			sub_total: "",
			shipping: 0,
			total: "",
			discount: 0,
			ship_to: "",
			unit_of_measurement: "CBM/KG",
		},
		defaultPoItems: {
			products: [
				{
					id: null,
					// quantity: 0,
					carton_count: 0,
					units: 0,
					unit_price: 0,
					amount: 0,
					units_per_carton: 0,
					volume: 0,
					weight: 0,
				},
			],
			po_number: "",
			is_system_generated: 1,
			buyer_id: "",
			customer_id: "",
			notes: "",
			created_by: "",
			tax: 0,
			warehouse_id: "",
			sub_total: "",
			shipping: 0,
			total: "",
			discount: 0,
			ship_to: "",
			unit_of_measurement: "CBM/KG",
		},
		dialogPoView: false,
		dialogPoDelete: false,
		currentPoToDelete: null,
		isMobile: false,
		dialogEmail: false,
		editedIndexEmail: -1,
		editedEmailItem: {
			emails: [],
			po: {},
		},
		defaultEmailItem: {
			emails: [],
			po: {},
		},
		selected: [],
		poIds: [],
		dialogWarning: false,
		poData: null,
		ordersState: null,
		reviewDialogSo: false,
	}),
	computed: {
		...mapGetters({
			getAllPoPage: "salesOrders/getAllPoPage",
			getPendingPage: "salesOrders/getPendingPage",
			getShippedPage: "salesOrders/getShippedPage",
			getAllPo: "salesOrders/getAllPo",
			getPoLoading: "salesOrders/getPoLoading",
			getPoDeleteLoading: "salesOrders/getPoDeleteLoading",
			poBaseUrlState: "products/poBaseUrlState",
			getVendorLists: "salesOrders/getVendorLists",
			getAllPos: "salesOrders/getAllPos",
			getAllPoPending: "salesOrders/getAllPoPending",
			getAllPoShipped: "salesOrders/getAllPoShipped",
			getOrderCounterValue: "salesOrders/getOrderCounterValue",
		}),
		posAll() {
			let posData = [];

			if (typeof this.getAllPos !== "undefined" && this.getAllPos !== null) {
				if (
					typeof this.getAllPos.results !== "undefined" &&
					this.getAllPos.results !== null
				) {
					posData = this.getAllPos.results.data;
				}
			}

			return posData;
		},
	},
	watch: {
		dialog(val) {
			val || this.close();
		},
		dialogView(val) {
			val || this.closeView();
		},
	},
	methods: {
		...mapActions({
			fetchWarehouse: "warehouse/fetchWarehouse",
			fetchProducts: "products/fetchProducts",
			deletePo: "salesOrders/deletePo",
			deleteMultiplePO: "salesOrders/deleteMultiplePO",
			downloadMultiplePO: "salesOrders/downloadMultiplePO",
			downloadPo: "salesOrders/downloadPo",
			fetchTerms: "fetchTerms",
			fetchPoPending: "salesOrders/fetchPoPending",
			fetchPoShipped: "salesOrders/fetchPoShipped",
			fetchPoShippedNew: "salesOrders/fetchPoShippedNew",
			fetchPoPendingNew: "salesOrders/fetchPoPendingNew",
			fetchBuyerLists: "salesOrders/fetchBuyerLists",
			fetchPoAllNew: "salesOrders/fetchPoAllNew",
			fetchImportName: "salesOrders/fetchImportName",
			fetchOrderCounter: "salesOrders/fetchOrderCounter",
			fetchSuppliersSku:"salesOrders/fetchSuppliersSku"
		}),
		...globalMethods,
		onResize() {
			if (window.innerWidth < 1023) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		async fetchSingleProduct(id) {
			try {
				const res = await axios.get(`${this.poBaseUrlState}/products/${id}`);
				if (res.status === 200) {
					if (typeof res.data !== "undefined") {
						if (
							typeof res.data.unit_price !== "undefined" &&
							res.data.unit_price !== "" &&
							res.data.unit_price !== null
						) {
							return Promise.resolve(res.data.unit_price);
						} else {
							return Promise.resolve(0);
						}
					} else {
						return Promise.resolve(0);
					}
				} else {
					return Promise.resolve(0);
				}
			} catch (e) {
				if (
					typeof e.message !== "undefined" &&
					e.message == "UnAuthenticated"
				) {
					this.$router.push("/login");
				} else {
					return Promise.reject(0);
				}
			}
		},
		createPo() {
			this.dialogCreatePo = true;
			this.editedPoItems.products = [
				{
					id: null,
					// quantity: 0,
					carton_count: 0,
					units: 0,
					unit_price: 0,
					amount: 0,
					units_per_carton: 0,
					volume: 0,
					weight: 0,
				},
			];
			this.editedPoIndex = -1;
		},
		processSingleProduct(getIndex, context, po) {
			if (po.purchase_order_products[getIndex]) {
				let ipp = po.purchase_order_products[getIndex];
				po.purchase_order_products[getIndex] = {
					id: ipp.product_id,
					// quantity: ipp.quantity,
					carton_count: ipp.quantity,
					units: ipp.units,
					amount: ipp.amount,
					product_id: ipp.product_id,
				};

				let unit_price = ipp.unit_price;
				let units_per_carton = ipp.units_per_carton;

				if (unit_price === null || unit_price === "" || ipp.unit_price == 0) {
					context
						.fetchSingleProduct(ipp.product_id)
						.then((data) => {
							unit_price =
								typeof data.unit_price !== "undefined"
									? data.unit_price
									: unit_price;
							unit_price =
								unit_price == null || unit_price == "" ? 0 : unit_price;
							po.purchase_order_products[getIndex].unit_price = unit_price;

							units_per_carton =
								typeof data.units_per_carton !== "undefined"
									? data.units_per_carton
									: units_per_carton;
							units_per_carton =
								units_per_carton == null || units_per_carton == ""
									? 0
									: units_per_carton;
							po.purchase_order_products[
								getIndex
							].units_per_carton = units_per_carton;

							this.processSingleProduct(++getIndex, context, po);
						})
						.catch((e) => {
							console.log(e);
							po.purchase_order_products[getIndex].unit_price = 0;
							po.purchase_order_products[getIndex].units_per_carton = 0;
							this.processSingleProduct(++getIndex, context, po);
						});
				} else {
					po.purchase_order_products[getIndex].unit_price =
						unit_price == null || unit_price == "" ? 0 : unit_price;
					po.purchase_order_products[getIndex].units_per_carton =
						units_per_carton == null || units_per_carton == ""
							? 0
							: units_per_carton;
					this.processSingleProduct(++getIndex, context, po);
				}
			} else {
				po.loadingState = false;
				po.products = po.purchase_order_products;
				this.editedPoItems = Object.assign({}, po);
			}
		},
		checkEditPo(po) {
			if (po.status === "partially shipped") {
				this.dialogWarning = true;
				this.poData = po;
			} else {
				this.editPo(po, true);
			}
		},
		async editPo(po, isEdit, changeRequest) {
			this.dialogCreatePo = true;
			this.editedPoIndex = _.findIndex(this.posAll, (e) => e.id === po.id);

			this.dialogCreatePo = isEdit ? isEdit : false;
			po.change_request_button = changeRequest;

			po.loadingState = true;
			po.products = [];
			let poProducts = po.purchase_order_products;

			if (
				typeof poProducts !== "undefined" &&
				Array.isArray(poProducts) &&
				poProducts.length > 0
			) {
				let newProducts = [];
				newProducts = poProducts.map((v) => {
					let {
						id,
						product_id,
						quantity,
						units,
						amount,
						unit_price,
						units_per_carton,
						volume,
						weight,
						product,
						other_party_product_id,
						other_party_product_sku,
						change_log,
						ship_date,
					} = v;

					quantity = typeof quantity !== "undefined" ? quantity : 0;
					unit_price =
						(typeof unit_price !== "undefined" && unit_price !== null) ||
						unit_price !== ""
							? unit_price
							: 0;
					units_per_carton =
						(typeof units_per_carton !== "undefined" &&
							units_per_carton !== null) ||
						units_per_carton !== ""
							? units_per_carton
							: 0;
					volume = typeof volume !== "undefined" ? volume : 0;
					weight = typeof weight !== "undefined" ? weight : 0;
					ship_date = ship_date == null ? '':ship_date
					return {
						id: product_id,
						carton_count: quantity,
						units: units,
						amount: amount,
						product_id: product_id,
						unit_price: unit_price,
						units_per_carton: units_per_carton,
						volume: volume,
						weight: weight,
						category_sku: product.category_sku,
						name: product.name,
						other_party_product_id: other_party_product_id || 0,
						other_party_product_sku: other_party_product_sku,
						change_log: change_log,
						ship_date:ship_date,
						row_id:id,
						po_product_id:id,
					};
				});

				po.products = newProducts;
				po.loadingState = false;

				this.editedPoItems = Object.assign({}, po);
			} else {
				po.products = poProducts;
				this.editedPoItems = Object.assign({}, po);
			}
			if(isEdit){
				if(this.editedPoItems.buyer_id !== null && this.editedPoItems.buyer_id !== undefined){
					try{
						await this.fetchSuppliersSku(this.editedPoItems.buyer_id)
					}catch(e){
						this.notificationError(e)
					}
				}
			}else{
				// if(this.editedPoItems.customer_id !== null && this.editedPoItems.customer_id !== undefined){
				// 	try{
				// 		await this.fetchSuppliersSku(this.editedPoItems.supplier_id)
				// 	}catch(e){
				// 		this.notificationError(e)
				// 	}
				// }
			}
		},
		checkReviewOrder(item, change_request) {
			this.reviewDialogSo = true;

			this.editPo(item, false, change_request);
		},
		closeReviewPo() {
			this.reviewDialogSo = false;
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems);
				this.editedPoIndex = -1;
			});
		},
		closePoCreate() {
			this.dialogCreatePo = false;
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems);
				this.editedPoIndex = -1;
			});

			if (this.poData !== null) {
				this.closeWarning();
			}
		},
		async openPoView(so) {
			let id = so.id;

			if (id !== "undefined" && id !== null) {
				this.$router.push(`/sales-orders-details/${id}`);
			}
		},
		closePoView() {
			this.dialogPoView = false;
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems);
				this.editedPoIndex = -1;
			});
		},
		email(po) {
			this.dialogEmail = true;
			this.editedPoIndex = -1;
			this.editedEmailItem.po = po;

			if (
				Array.isArray(this.getVendorLists) &&
				this.getVendorLists.length > 0
			) {
				let findVendor = _.find(
					this.getVendorLists,
					(e) => e.id === po.supplier_id
				);
				if (typeof findVendor !== "undefined") {
					this.editedEmailItem.emails = findVendor.emails;
				}
			}
		},
		closeEmail() {
			this.dialogEmail = false;
			this.editedPoIndex = -1;
			this.editedEmailItem = {
				emails: [],
				po: {},
			};
		},
		openDelete(item) {
			this.dialogPoDelete = true;
			this.currentPoToDelete = item;
			this.currentPoToDelete.name = item.po_number;
		},
		async deletePoConfirm() {
			if (this.currentPoToDelete !== null) {
				try {
					await this.deletePo(this.currentPoToDelete.id);
					this.notificationCustom("Purchase order successfully deleted.");
					//await this.fetchPoPending(1)
					//await this.fetchPoShipped(1)
					this.closePoDelete();
					this.closePoView();

					await this.fetchPoPendingNew({
						page: 1,
					});
					await this.fetchPoShippedNew({
						page: 1,
					});

					if (this.isMobile) {
						this.$router.push(`/pos`);
					}
				} catch (e) {
					this.closePoDelete();
					this.notificationError(e);
				}
			} else {
				if (this.poIds.length > 0) {
					try {
						await this.deleteMultiplePO(this.poIds);
						this.notificationCustom("Purchase Orders have been deleted.");
						//await this.fetchPoPending(1)
						//await this.fetchPoShipped(1)
						await this.fetchPoPendingNew({
							page: 1,
						});
						await this.fetchPoShippedNew({
							page: 1,
						});
						this.closePoDelete();
						this.closePoView();
						this.clearSelectedPO();
					} catch (e) {
						this.closePoDelete();
						this.notificationError(e);
						this.clearSelectedPO();
					}
				}
			}
		},
		closePoDelete() {
			this.dialogPoDelete = false;
			this.currentPoToDelete = null;
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems);
				this.editedPoIndex = -1;
				this.poIds = [];
			});
		},
		clearSelectedPO() {
			this.currentPoToDelete = null;
			this.selected = [];
			this.poIds = [];
		},
		deleteMultiple() {
			let ids = [];

			if (this.selected.length > 0) {
				ids = this.selected.map((v) => {
					return v.id;
				});
			}

			this.poIds = ids;
			this.dialogPoDelete = true;
		},
		async downloadMultiple() {
			// action for downloading multiple PO
			await this.downloadMultiplePO(this.selected);
		},
		closeWarning() {
			this.dialogWarning = false;
			this.poData = null;
		},
		async currentTab(tabIndex, isMountedCalled) {
			this.ordersState = tabIndex == null ? "" : tabIndex;
			if (!isMountedCalled) {
				if (this.source) this.source.cancel("cancel_previous_request");
				this.source = axios.CancelToken.source();

				await this.fetchPoAllNew({
					page: this.getAllPoPage,
					state: this.ordersState,
					cancelToken: this.source.token,
				});
			}
		},
	},
	async mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "sales-orders");
		this.hasMounted = true;

		try {
			this.source = axios.CancelToken.source();

			if (this.ordersState == null) {
				if (this.$router.history.current.query.tab != ACTIVE) {
					this.$router.push(`?tab=${ACTIVE}`);
					this.$store.dispatch("salesOrders/setCurrentSOTab", 0);
					this.$store.dispatch("salesOrders/setSoCurrentAllTab", 1);
				}
			}
			this.fetchOrderCounter();

			await this.fetchPoAllNew({
				page: this.getAllPoPage,
				state:
					this.$router.history.current.query.tab != "all"
						? this.$router.history.current.query.tab
						: ACTIVE,
				cancelToken: this.source.token,
			});

			await this.fetchBuyerLists();
			await this.fetchImportName();
			await this.fetchWarehouse();
			await this.fetchProducts();
			await this.fetchTerms();
		} catch (e) {
			if (e !== "cancel_previous_request") this.notificationError(e);
		}
	},
	updated() {},
};
</script>

<style lang="scss">
// @import "../assets/scss/pages_scss/po/po.scss";
@import "../assets/scss/pages_scss/salesOrders/salesOrders.scss";
@import "../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../assets/scss/buttons.scss";
@import "../assets/scss/inputs.scss";
</style>
