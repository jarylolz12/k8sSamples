<!-- @format -->

<template>
	<div class="po-table-wrapper">
		<v-data-table
			:headers="headers"
			:items="posShipped"
			:items-per-page="getItemPerPage()"
			@page-count="pageCount = $event"
			:page.sync="page"
			mobile-breakpoint="769"
			item-key="order_number"
			class="pos-table elevation-1"
			:class="
				posShipped !== null && posShipped.length > 0 ? '' : 'no-data-table'
			"
			hide-default-footer
			fixed-header
			v-model="selected"
			show-select
			@click:row="viewPo"
		>
			<template v-slot:[`item.order_number`]="{ item }">
				<div class="inventory-wrapper">
					<div>{{ item.po_number }}</div>
				</div>
			</template>

			<template v-slot:[`item.created_at`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="noSpace"> {{ formatDate(item.created_at) }}</div>
                    <div class="order_type"> {{item.order_type == "PO" ? "Buyer" : "Vendor"}}</div>
                </div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="inventory-wrapper">
                    <div class="noSpace"> {{ formatDate(item.updated_at, "date_time") }}</div>
                    <div class="order_type"> {{ item.customer_name }}</div>
                </div>
            </template>

			<template v-slot:[`item.buyer`]="{ item }">
				<div class="inventory-wrapper">
					<div>{{ item.buyer_name  ?  item.buyer_name : item.customer_name }}</div>
				</div>
			</template>

			<template v-slot:[`item.ship_to`]="{ item }">
				<div class="inventory-wrapper">
					<!-- <div v-html="getWarehouseAddress(item.warehouse_id)"></div> -->
					<div>
						{{
							item.ship_to !== "" && item.ship_to !== null ? item.ship_to : "--"
						}}
					</div>
				</div>
			</template>

			<template v-slot:[`item.eta`]="{ item }">
				<span class="noSpace">{{formatDate( item.eta) }}</span>
			</template>

			<template v-slot:[`item.details`]="{ item }">
				<div>
					<span>${{ getTotalPrice(item) }}</span> <br/>
                    <span class="units" style="color: #6D858F;">
                        {{ item.total_units !== null ? item.total_units : 0 }} Units
                    </span>
				</div>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="actions">
					<v-menu bottom left offset-y content-class="po-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn color="btn-more elevation-0" v-bind="attrs" v-on="on">
								<img src="@/assets/icons/dots.svg" />
							</v-btn>
						</template>

						<v-list class="po-lists">
							<v-list-item @click="viewPo(item)">
								<v-list-item-title>
									<div class="title-img">
										<img
											class="mr-2"
											src="@/assets/icons/visibility-po.svg"
											width="16px"
											height="16px"
										/>
									</div>

									<span>View</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item @click="download(item)">
								<v-list-item-title>
									<div class="title-img">
										<img
											class="mr-2"
											src="@/assets/icons/download-po.svg"
											width="16px"
											height="16px"
										/>
									</div>

									<span>Download</span>
								</v-list-item-title>
							</v-list-item>
						</v-list>
					</v-menu>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper" v-if="getAllPosLoading">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>

				<div class="no-data-wrapper" v-if="!getAllPosLoading && posShipped.length == 0">
					<div class="no-data-heading">
						<img
							src="@/assets/images/salesOrdersBlue.svg"
							width="40px"
							height="42px"
							alt=""
						/>

						<h3>No Sales Order</h3>
						<p>Sales orders for your customers will be shown here.</p>
					</div>
				</div>
			</template>
		</v-data-table>

		<Pagination
			v-if="typeof posShipped !== 'undefined' && posShipped.length > 0"
			:pageData.sync="getCurrentPage"
			:lengthData.sync="getTotalPage"
			:isMobile="isMobile"
			@next-page="nextPage"
			@previous-page="prevPage"
			@goto-page="goToPage"
		/>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Pagination from "../../../Pagination.vue";
import _ from "lodash";
import moment from "moment";
import globalMethods from "../../../../utils/globalMethods";
//import jQuery from 'jquery'
export default {
	name: "SalesOrderBookedDesktopTable",
	props: [
		"items",
		"currentWarehouseSelected",
		"isMobile",
		"search",
		"selectedPO",
	],
	components: {
		Pagination,
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		headers: [
			{
				text: "Order No",
				align: "start",
				sortable: false,
				value: "order_number",
				fixed: true,
				width: "8%",
			},
			{
				text: "Issued At & By",
				align: "start",
				sortable: false,
				value: "created_at",
				fixed: true,
				width: "12%",
			},
			{
				text: 'Updated At & By',
				align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: "15%"
			},
			{
				text: "Buyer",
				align: "start",
				sortable: false,
				value: "buyer",
				fixed: true,
				width: "22%",
			},
			{
				text: "Ship To",
				align: "start",
				sortable: false,
				value: "ship_to",
				fixed: true,
				width: "22%",
			},
			{
				text: "ETD",
				align: "start",
				sortable: false,
				value: "eta",
				fixed: true,
				width: "12%",
			},
			{
				text: "Details",
				align: "end",
				sortable: false,
				value: "details",
				fixed: true,
				width: "15%",
			},
			{
				text: "",
				align: "end",
				value: "actions",
				sortable: false,
				fixed: true,
				width: "14%",
			},
		],
		dialogDelete: false,
		dialogDeleteWarehouse: false,
	}),
	computed: {
		...mapGetters({
			getShippedPage: "salesOrders/getShippedPage",
			getCategories: "category/getCategories",
			getVendorLists: "salesOrders/getVendorLists",
			getShippedPOPagination: "salesOrders/getShippedPOPagination",
			getWarehouse: "warehouse/getWarehouse",
			getAllPosLoading: "salesOrders/getAllPosLoading",
			getSoLocalQuery: "salesOrders/getSoLocalQuery",
		}),
		posShipped: {
			get() {
				return this.items;
			},
			set(value) {
				this.$emit("update:items", value);
			},
		},
		categoryLists() {
			let categoryListsData = [];

			if (
				typeof this.getCategories !== "undefined" &&
				this.getCategories !== null &&
				this.getCategories.length > 0
			) {
				categoryListsData = this.getCategories.map((value) => {
					return {
						id: value.id,
						name: value.name,
					};
				});
			}

			return categoryListsData;
		},
		getTotalPage: {
			get() {
				return Math.ceil(
					this.getShippedPOPagination.total /
						this.getShippedPOPagination.per_page
				);
			},
		},
		getCurrentPage: {
			get() {
				return this.getShippedPage;
				//return this.getShippedPOPagination.current_page
			},
		},
		selected: {
			get() {
				return this.selectedPO;
			},
			set(value) {
				this.$emit("update:selectedPO", value);
			},
		},
	},
	methods: {
		...mapActions({
			downloadPo: "salesOrders/downloadPo",
			fetchPoShipped: "salesOrders/fetchPoShipped",
			fetchPoShippedNew: "salesOrders/fetchPoShippedNew",
			setShippedPage: "salesOrders/setShippedPage",
		}),
		...globalMethods,
		formatDate(date) {
			if (date !== null) {
				return moment(date).format("MMM DD, YYYY");
			} else {
				return "N/A";
			}
		},
		getItemPerPage() {
			return this.getShippedPOPagination.per_page;
		},
		nextPage() {
			if (this.getShippedPOPagination.next_page_url !== null) {
				var queryParams = this.getShippedPOPagination.next_page_url
					.split("?")[1]
					.replace(/[^0-9]/g, "");
				this.setShippedPage(queryParams);
				try {
					let passedData = {
						page: queryParams,
						special: 1,
					};
					if (this.getSoLocalQuery !== null && this.getSoLocalQuery !== "") {
						passedData["query"] = this.getSoLocalQuery;
					}
					this.fetchPoShippedNew(passedData);
				} catch (e) {
					this.notificationError(e);
				}
			}
			/*
            var queryParams = this.getShippedPOPagination.next_page_url.split("?")[1].replace(/[^0-9]/g,'')

            try {
                this.fetchPoShipped(queryParams)
            } catch (e) {
                this.notificationError(e)
            }*/
		},
		prevPage() {
			if (this.getShippedPOPagination.prev_page_url !== null) {
				var queryParams = this.getShippedPOPagination.prev_page_url
					.split("?")[1]
					.replace(/[^0-9]/g, "");
				this.setShippedPage(queryParams);
				try {
					let passedData = {
						page: queryParams,
						special: 1,
					};
					if (this.getSoLocalQuery !== null && this.getSoLocalQuery !== "") {
						passedData["query"] = this.getSoLocalQuery;
					}

					this.fetchPoShippedNew(passedData);
					//this.fetchPoPendingNew(queryParams)
					//this.fetchPoPending(queryParams)
				} catch (e) {
					this.notificationError(e);
				}
			}
			/*
            var queryParams = this.getShippedPOPagination.prev_page_url.split("?")[1].replace(/[^0-9]/g,'')
            try {
                this.fetchPoShipped(queryParams)
            } catch (e) {
                this.notificationError(e)
            }*/
		},
		goToPage(pageNumber) {
			this.setShippedPage(pageNumber);
			try {
				let passedData = {
					page: pageNumber,
					special: 1,
				};
				if (this.getSoLocalQuery !== null && this.getSoLocalQuery !== "") {
					passedData["query"] = this.getSoLocalQuery;
				}
				this.fetchPoShippedNew(passedData);
				//this.fetchPoPending(pageNumber)
			} catch (e) {
				this.notificationError(e);
			}

			/*
            try {
                this.fetchPoShipped(pageNumber)
            } catch (e) {
                this.notificationError(e)
            }*/
		},
		createPo() {
			this.$emit("createPo");
		},
		editPo(po) {
			this.$emit("editPo", po);
		},
		viewPo(item) {
			this.$emit("viewPo", item);
		},
		getImgUrl(pic) {
			if (pic !== "undefined" && pic !== null) {
				return pic;
			} else {
				return require("@/assets/icons/default-product-icon.svg");
			}
		},
		getCategoryName(id) {
			if (this.categoryLists.length !== 0 && id) {
				let findSizeValue = _.find(this.categoryLists, (e) => e.id == id);

				if (typeof findSizeValue !== "undefined") {
					if (findSizeValue.name !== "undefined") {
						return findSizeValue.name;
					}
				} else {
					return "";
				}
			}
		},
		getSupplierName(id) {
			if (
				Array.isArray(this.getVendorLists) &&
				this.getVendorLists.length > 0
			) {
				let findVendor = _.find(this.getVendorLists, (e) => e.id === id);
				if (typeof findVendor !== "undefined") {
					return findVendor.company_name;
				}
			}
			return "--";
		},
		getWarehouseAddress(id) {
			if (
				typeof this.getWarehouse !== "undefined" &&
				this.getWarehouse !== null &&
				typeof this.getWarehouse.results !== "undefined" &&
				this.getWarehouse.results !== null &&
				this.getWarehouse.results.length !== "undefined" &&
				this.getWarehouse.results.length !== null
			) {
				let getData = this.getWarehouse.results;
				let findSizeValue =
					id !== "undefined" ? _.find(getData, (e) => e.id == id) : "";

				if (typeof findSizeValue !== "undefined") {
					if (findSizeValue.address !== "undefined") {
						return `<span>${findSizeValue.address}</span>`;
						// return `<span>${findSizeValue.name},</span><br/><span>${findSizeValue.address}</span>`;
					}
				} else {
					return "--";
				}
			}
		},
		async download(item) {
			try {
				this.notificationCustom("Preparing to download SO...");
				await this.downloadPo(item);
				this.notificationCustom("Files has been downloaded.");
			} catch (e) {
				this.notificationError(e);
				console.log(e);
			}
		},
		email(po) {
			this.$emit("email", po);
		},
		deletePo(item) {
			this.$emit("deletePo", item);
		},
		getTotalPrice(item) {
			if (typeof item.total !== "undefined" && item.total !== null) {
				let total = item.total.toFixed(2);

				return this.addCommaToNum(total);
			} else {
				return 0;
			}
		},
	},
	mounted() {},
	updated() {},
};
</script>

<style scoped>
.different-btn {
	cursor: default !important;
}
</style>
