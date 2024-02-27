<!-- @format -->

<template>
	<div class="purchase-wrapper" v-resize="onResize">
		<!-- Desktop -->
		<PODesktopTable
			:selectedPO.sync="selected"
			@createPo="createPo"
			@import="importDialogIsOpen = true"
			@editPo="checkEditPo"
			@reviewOrder="checkReviewOrder"
			@selectCargo="selectCargo"
			@addToShipment="addToShipment"
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

		<ImportEntityDialog
			:isOpen.sync="importDialogIsOpen"
			templateText="You can import PO by uploading Excel or CSV file. To ensure accurate import, download and use this template:"
			:template-url="templateURL"
			title="Upload PO"
			:import-fn="importPOs"
		/>

		<!-- Mobile -->
		<POMobileTable
			@import="importDialogIsOpen = true"
			:selectedPO.sync="selected"
			@createPo="createPo"
			@editPo="checkEditPo"
			@email="email"
			@viewPo="openPoView"
			@deletePo="openDelete"
			v-if="isMobile"
			:isMobile="isMobile"
		/>

		<POCreateDialog
			:dialog.sync="dialogCreatePo"
			:editedIndex.sync="editedPoIndex"
			:editedItems.sync="editedPoItems"
			@close="closePoCreate"
			fromComponent="po-desktop"
			:isMobile="isMobile"
		/>

		<POReviewOrder
			:dialog.sync="reviewDialogPo"
			:editedItems.sync="editedPoItems"
			@close="closeReviewPo"
			fromComponent="po-desktop"
			:isMobile="isMobile"
		/>
		<SelectCargoDialog
			:dialog.sync="selectCargoDialog"
			:editedItems.sync="editedPoItems"
			:selectShipment="selectedShipment"
			@close="closeSelectCargoDialog"
			fromComponent="po-desktop"
			:isMobile="isMobile"
		/>

		<PoEmail
			:dialog.sync="dialogEmail"
			:editedItems.sync="editedEmailItem"
			:editedIndex.sync="editedIndexEmail"
			:isMobile="isMobile"
			@close="closeEmail"
		/>

		<ConfirmDialog :dialogData.sync="dialogPoDelete">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>
					{{
						currentPoToDelete !== null
							? "Delete Purchase Order"
							: "Delete Purchase Orders"
					}}
				</h2>
			</template>

			<template v-slot:dialog_content>
				<p v-if="currentPoToDelete !== null && selected.length === 0">
					Do you want to delete purchase order
					<span class="name"
						>"{{
							currentPoToDelete !== null ? currentPoToDelete.po_number : ""
						}}"</span
					>? Once deleted, this cannot be undone.
				</p>

				<p v-if="currentPoToDelete == null && selected.length > 0">
					Do you want to delete the selected purchase orders? Once deleted, this
					cannot be undone.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn
					class="btn-blue"
					@click="deletePoConfirm"
					text
					:disabled="getPoDeleteLoading"
				>
					<span v-if="!getPoDeleteLoading">Delete</span>
					<span v-if="getPoDeleteLoading">Deleting...</span>
				</v-btn>

				<v-btn
					class="btn-white"
					text
					@click="closePoDelete"
					:disabled="getPoDeleteLoading"
				>
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

		<!-- FOR PO WARNING -->
		<ConfirmDialog :dialogData.sync="dialogWarning">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Edit Partially Booked Order?</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					This Purchase Order has been partially booked. You will not be able to
					make changes that will affect the booking. Do you want to proceed with
					Editing?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="editPo(poData)" text>
					<span>Edit PO</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeWarning">
					Dismiss
				</v-btn>
			</template>
		</ConfirmDialog>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import PODesktopTable from "../components/Tables/POs/PODesktopTable.vue";
import POMobileTable from "../components/Tables/POs/POMobileTable.vue";
import POCreateDialog from "../components/PosComponents/Dialog/POCreateDialog.vue";
import PoEmail from "../components/PosComponents/Dialog/PoEmail.vue";
import ConfirmDialog from "../components/Dialog/GlobalDialog/ConfirmDialog.vue";
import globalMethods from "../utils/globalMethods";
import axios from "axios";
import _ from "lodash";
import ImportEntityDialog from "@/components/Dialog/ImportEntityDialog";
import POReviewOrder from "@/components/PosComponents/Dialog/POReviewOrder.vue";
import SelectCargoDialog from "@/components/PosComponents/Dialog/SelectCargoDialog.vue";
import { ACTIVE } from "../constants/states";

export default {
	name: "POs",
	components: {
		ImportEntityDialog,
		PODesktopTable,
		POMobileTable,
		POCreateDialog,
		PoEmail,
		ConfirmDialog,
		POReviewOrder,
		SelectCargoDialog,
	},
	data: () => ({
		importDialogIsOpen: false,
		templateURL: `${process.env.VUE_APP_PO_URL}/orders/export-template`,
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
					unship_cartons: 0,
					shipped_cartons: 0,
					shipped_units: 0,
					unshipped_units: 0,
					volume: 0,
					weight: 0,
				},
			],
			po_number: "",
			is_system_generated: 1,
			supplier_id: "",
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
					unship_cartons: 0,
					shipped_cartons: 0,
					shipped_units: 0,
					unshipped_units: 0,
					volume: 0,
					weight: 0,
				},
			],
			po_number: "",
			is_system_generated: 1,
			supplier_id: "",
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
		reviewDialogPo: false,
		selectCargoDialog: false,
		selectedShipment: false,
	}),
	computed: {
		...mapGetters({
			getAllPoPage: "po/getAllPoPage",
			getPendingPage: "po/getPendingPage",
			getShippedPage: "po/getShippedPage",
			getAllPo: "po/getAllPo",
			getPoLoading: "po/getPoLoading",
			getPoDeleteLoading: "po/getPoDeleteLoading",
			poBaseUrlState: "products/poBaseUrlState",
			getVendorLists: "po/getVendorLists",
			getAllPoPending: "po/getAllPoPending",
			getAllPoShipped: "po/getAllPoShipped",
			getAllPos: "po/getAllPos",
			getCurrentPoTab: "po/getCurrentPoTab",
			getPoLocalQuery: "po/getPoLocalQuery",
			getCurrentPage: "page/getCurrentPage",
			getOrderCounterValue: "po/getOrderCounterValue",
		}),
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
			importPOs: "po/importPOs",
			fetchWarehouse: "warehouse/fetchWarehouse",
			fetchProducts: "products/fetchProducts",
			deletePo: "po/deletePo",
			deleteMultiplePO: "po/deleteMultiplePO",
			downloadMultiplePO: "po/downloadMultiplePO",
			downloadPo: "po/downloadPo",
			fetchTerms: "fetchTerms",
			fetchPoPending: "po/fetchPoPending",
			fetchPoShipped: "po/fetchPoShipped",
			fetchPoShippedNew: "po/fetchPoShippedNew",
			fetchPoPendingNew: "po/fetchPoPendingNew",
			fetchPoAllNew: "po/fetchPoAllNew",
			fetchVendorLists: "po/fetchVendorLists",
			setPoLocalQuery: "po/setPoLocalQuery",
			setPoAll: "po/setPoAll",
			setPoShipped: "po/setPoShipped",
			setPoPending: "po/setPoPending",
			setPoPendingLoading: "po/setPoPendingLoading",
			setPoShippedLoading: "po/setPoShippedLoading",
			setPoAllLoading: "po/setPoAllLoading",
			fetchImportName: "po/fetchImportName",
			fetchOrderCounter: "po/fetchOrderCounter",
			fetchVendorSku:"po/fetchVendorSku"
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
			(this.editedPoItems.products = [
				{
					id: null,
					// quantity: 0,
					carton_count: 0,
					units: 0,
					unit_price: 0,
					amount: 0,
					units_per_carton: 0,
					unship_cartons: 0,
					shipped_cartons: 0,
					shipped_units: 0,
					unshipped_units: 0,
					volume: 0,
					weight: 0,
				},
			]),
				(this.editedPoIndex = -1);
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
			if (
				po.status === "partially shipped" ||
				po.status === "partially_shipped"
			) {
				this.dialogWarning = true;
				this.poData = po;
			} else {
				this.editPo(po, true);
			}
		},
		async editPo(po, isEdit, changeRequest) {
			this.dialogCreatePo = true;
			this.editedPoIndex = 0;

			this.dialogCreatePo = isEdit ? isEdit : false;

			po.change_request_button = changeRequest;

			// if (this.getCurrentPoTab === 0) {
			// 	this.editedPoIndex = _.findIndex(this.posAll, (e) => e.id === po.id);
			// }
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
						unship_cartons,
						product,
						volume,
						weight,
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
					ship_date = ship_date == null ? '': ship_date
					return {
						id: product_id,
						carton_count: quantity,
						units: units,
						amount: amount,
						product_id: product_id,
						unit_price: unit_price,
						units_per_carton: units_per_carton,
						unship_cartons,
						shipped_cartons: quantity - unship_cartons,
						row_id:id,
						po_product_id:id,
						shipped_units:
							product !== null && typeof product.shipped_units !== "undefined"
								? product.shipped_units
								: 0,
						unshipped_units:
							product !== null && typeof product.unshipped_units !== "undefined"
								? product.unshipped_units
								: 0,
						hasCartonError: false,
						cartonErrorMessage: "",
						showErrorCarton: false,
						hasUnitError: false,
						unitErrorMessage: "",
						showErrorUnit: false,
						actual_carton_count: quantity,
						actual_units: units,
						volume: volume,
						weight: weight,
						category_sku: product.category_sku,
						name: product.name,
						other_party_product_id: other_party_product_id || 0,
						other_party_product_sku: other_party_product_sku,
						change_log: change_log,
						ship_date:ship_date
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
				if(this.editedPoItems.supplier_id !== null && this.editedPoItems.supplier_id !== undefined){
					try{
						await this.fetchVendorSku(this.editedPoItems.supplier_id)
					}catch(e){
						this.notificationError(e)
					}
				}
			}else{
				// if(this.editedPoItems.customer_id !== null && this.editedPoItems.customer_id !== undefined){
				// 	try{
				// 		await this.fetchVendorSku(this.editedPoItems.customer_id)
				// 	}catch(e){
				// 		this.notificationError(e)
				// 	}
				// }
			}
		},
		checkReviewOrder(item, change_request) {
			// console.log("change_request", change_request);
			this.reviewDialogPo = true;

			this.editPo(item, false, change_request);
		},
		closeReviewPo() {
			this.reviewDialogPo = false;
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
		async openPoView(po) {
			let id = po.id;

			if (id !== "undefined" && id !== null) {
				this.$router.push(`/po-details/${id}`);
			}
		},
		closePoView() {
			this.dialogPoView = false;
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems);
				this.editedPoIndex = -1;
			});

			if (this.poData !== null) {
				this.closeWarning();
			}
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
			let buildQuery = {};
			if (this.currentPoToDelete !== null && this.poIds.length === 0) {
				try {
					await this.deletePo(this.currentPoToDelete.id);
					this.notificationCustom("Purchase order have been deleted.");
					this.closePoDelete();
					this.closePoView();

					if (this.getPoLocalQuery !== "") {
						buildQuery["query"] = this.getPoLocalQuery;
						buildQuery["special"] = 1;
					}

					let storePagination = this.$store.state.po;

					if (this.getCurrentPoTab === 0) {
						storePagination = storePagination.allPOPagination;
						let page =
							typeof storePagination.current_page !== "undefined"
								? storePagination.current_page
								: 1;

						if (
							storePagination.data.length === 1 &&
							storePagination.current_page !== 1
						) {
							page = page - 1;
						}

						this.$store.state.po.allPage = page;

						buildQuery["page"] = page;
						await this.fetchPoAllNew(buildQuery);
						/*
						await this.fetchPoAllNew({
							page
						})*/
					} else if (this.getCurrentPoTab === 1) {
						storePagination = storePagination.pendingPOPagination;
						let page =
							typeof storePagination.current_page !== "undefined"
								? storePagination.current_page
								: 1;

						if (
							storePagination.data.length === 1 &&
							storePagination.current_page !== 1
						) {
							page = page - 1;
						}

						this.$store.state.po.pendingPage = page;

						buildQuery["page"] = page;
						await this.fetchPoPendingNew(buildQuery);
						/*
						await this.fetchPoPendingNew({
							page
						})*/
					}

					if (this.isMobile) {
						this.$router.push(`/pos`);
					}
				} catch (e) {
					this.closePoDelete();
					this.notificationError(e);
				}
			} else if (this.currentPoToDelete == null && this.poIds.length > 0) {
				try {
					await this.deleteMultiplePO(this.poIds);
					this.notificationCustom("Purchase Orders have been deleted.");
					this.closePoDelete();

					let storePagination = this.$store.state.po;

					if (this.getCurrentPoTab === 0) {
						storePagination = storePagination.allPOPagination;
						let page =
							typeof storePagination.current_page !== "undefined"
								? storePagination.current_page
								: 1;

						if (
							storePagination.data.length === this.poIds.length &&
							storePagination.current_page !== 1
						) {
							page = page - 1;
						}

						this.$store.state.po.allPage = page;

						buildQuery["page"] = page;
						await this.fetchPoAllNew(buildQuery);
						/*
						await this.fetchPoAllNew({
							page
						})*/
					} else if (this.getCurrentPoTab === 1) {
						storePagination = storePagination.pendingPOPagination;
						let page =
							typeof storePagination.current_page !== "undefined"
								? storePagination.current_page
								: 1;

						if (
							storePagination.data.length === this.poIds.length &&
							storePagination.current_page !== 1
						) {
							page = page - 1;
						}

						this.$store.state.po.pendingPage = page;
						/*
						await this.fetchPoPendingNew({
							page
						})*/

						buildQuery["page"] = page;
						await this.fetchPoPendingNew(buildQuery);
					}

					// if (this.getCurrentPoTab === 0) {
					//     await this.fetchPoAllNew({
					//         page: 1
					//     })
					// } else if (this.getCurrentPoTab === 1) {
					//     await this.fetchPoPendingNew({
					//         page: 1
					//     })
					// } else if (this.getCurrentPoTab === 2) {
					//     await this.fetchPoShippedNew({
					//         page: 1
					//     })
					// }
					this.closePoView();
					this.clearSelectedPO();
				} catch (e) {
					this.closePoDelete();
					this.notificationError(e);
					this.clearSelectedPO();
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
				this.setPoAllLoading(false);
			}
		},
		selectCargo(item) {
			this.selectCargoDialog = true;

			this.editedPoItems = Object.assign({}, item);
		},
		addToShipment(item) {
			this.selectCargoDialog = true;
			this.selectedShipment = true;
			this.editedPoItems = Object.assign({}, item);
		},
		closeSelectCargoDialog() {
			this.selectCargoDialog = false;
			this.selectedShipment = false;
			delete this.editedPoItems.addShipment;
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems);
			});
		},
	},
	async mounted() {
		//check previous page
		if (this.getCurrentPage !== "pos") {
			this.setPoAll([]);
			this.setPoPending([]);
			this.setPoShipped([]);
			this.setPoAllLoading(true);
		}
		//set current page
		this.$store.dispatch("page/setPage", "pos");
		this.hasMounted = true;
		try {
			this.setPoLocalQuery("");
			this.source = axios.CancelToken.source();
			if (this.ordersState == null) {
				if (this.$router.history.current.query.tab != ACTIVE) {
					this.$router.push(`?tab=${ACTIVE}`);
					this.$store.dispatch("po/setCurrentPOTab", 0);
					this.$store.dispatch("po/setPoCurrentAllTab", 1);
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
			this.setPoAllLoading(false);

			await this.fetchVendorLists();
			await this.fetchImportName();
			await this.fetchWarehouse();
			await this.fetchTerms();
		} catch (e) {
			if (e !== "cancel_previous_request") this.notificationError(e);
		}
	},
	updated() {},
};
</script>

<style lang="scss">
@import "../assets/scss/pages_scss/po/po.scss";
@import "../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../assets/scss/buttons.scss";
@import "../assets/scss/inputs.scss";
</style>
