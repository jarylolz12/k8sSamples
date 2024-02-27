<!-- @format -->

<template>
	<div class="po-table-wrapper">
		<v-data-table
			:headers="headers"
			:items="posPending"
			:page.sync="page"
			:items-per-page="getItemPerPage()"
			@page-count="pageCount = $event"
			mobile-breakpoint="769"
			item-key="po_number"
			class="pos-mobile-table elevation-1"
			:class="
				posPending !== null && posPending.length > 0 ? '' : 'no-data-table'
			"
			hide-default-footer
			fixed-header
			@click:row="viewPo"
			show-select
		>
			<template v-slot:[`item.order_number`]="{ item }">
				<div class="po-mobile-item dFlex">
					<div class="po-num">
						Order# <span>{{ item.po_number }}</span>
					</div>
					<div class="po-amount">
						<span>${{ getTotalPrice(item) }}</span>
					</div>
				</div>
			</template>

			<template v-slot:[`item.date`]="{ item }">
				<div class="po-mobile-item">
					<div class="po-supplier">
						<span>{{ item.buyer_name ? item.buyer_name :item.customer_name }}</span>
					</div>

					<div class="po-address">
						<div>
							{{
								item.ship_to !== "" && item.ship_to !== null
									? item.ship_to
									: "--"
							}}
						</div>
					</div>
				</div>
			</template>

			<template v-slot:[`item.buyer`]="{ item }">
				<div class="po-mobile-item dFlex dates" :class="item.status === 'partially shipped' ? 'partially-shipped' : ''">
					<div class="po-date">
						DATE: <span class="mr-3">{{ formatDate(item.created_at) }}</span>
					</div>

					<div class="po-date">
						CRD: <span>{{ formatDate(item.cargo_ready_date) }}</span>
					</div>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper" v-if="getPoPendingLoading">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>

				<div
					class="no-data-wrapper"
					v-if="!getPoPendingLoading && posPending.length == 0"
				>
					<div class="no-data-heading">
						<img
							src="@/assets/icons/empty-po-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>

						<h3>
							{{
								getSoLocalQuery !== "" && getSoLocalQuery !== null
									? "No matching result"
									: "Create Sales Orders"
							}}
						</h3>
						<p v-if="getSoLocalQuery == null || getSoLocalQuery == ''">
							{{ "There is no sales order to show." }}
							<br />
							{{ "Let’s create a new sales order." }}
						</p>
						<p v-if="getSoLocalQuery !== null && getSoLocalQuery == ''">
							{{
								"Sorry! We could not find any sales order that matches your search term."
							}}
						</p>
						<div
							v-if="getSoLocalQuery == null || getSoLocalQuery == ''"
							class="po-table-button-empty-wrapper"
						>
							<v-btn dark color="primary" class="btn-white" @click="createPo">
								{{ "Create PO" }}
							</v-btn>
						</div>
						<div
							v-if="getSoLocalQuery !== null && getSoLocalQuery !== ''"
							class="mt-4"
						>
							<a dark color="primary" class="btn-white different-btn">
								{{ "Try Another Search" }}
							</a>
						</div>
					</div>
				</div>
			</template>

			<template v-slot:[`no-results`]>
				<div class="no-results">
					<div class="no-results-heading">
						<img
							src="@/assets/icons/empty-po-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>

						<h3>No matching result</h3>
						<p>
							Sorry! We could not find any sales order that matches your search
							term.
						</p>
					</div>
				</div>
			</template>
		</v-data-table>
		<Pagination
			v-if="typeof posPending !== 'undefined' && posPending.length > 0"
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
	name: "SalesOrdersPendingMobileTable",
	props: ["items", "currentWarehouseSelected", "isMobile", "search"],
	components: {
		Pagination,
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		headers: [
			{
				text: "Order Number",
				align: "start",
				sortable: false,
				value: "order_number",
				fixed: true,
				width: "8%",
			},
			{
				text: "Date",
				align: "start",
				sortable: false,
				value: "date",
				fixed: true,
				width: "12%",
			},
			{
				text: "Vendor",
				align: "start",
				sortable: false,
				value: "buyer",
				fixed: true,
				width: "25%",
			},
			{
				text: "Ship to",
				align: "start",
				sortable: false,
				value: "ship_to",
				fixed: true,
				width: "25%",
			},
			{
				text: "Cargo Ready",
				align: "start",
				sortable: false,
				value: "cargo_ready",
				fixed: true,
				width: "12%",
			},
			{
				text: "Details",
				align: "end",
				sortable: false,
				value: "details",
				fixed: true,
				width: "10%",
			},
			{
				text: "Status",
				align: "center",
				sortable: false,
				value: "status",
				fixed: true,
				width: "12%",
			},
			{
				text: "",
				align: "end",
				value: "actions",
				sortable: false,
				fixed: true,
				width: "12%",
			},
		],
		dialogDelete: false,
		dialogDeleteWarehouse: false,
	}),
	computed: {
		...mapGetters({
			getPendingPage: "salesOrders/getPendingPage",
			getCategories: "category/getCategories",
			getVendorLists: "salesOrders/getVendorLists",
			getWarehouse: "warehouse/getWarehouse",
			getPendingPOPagination: "salesOrders/getPendingPOPagination",
			getPoPendingLoading: "salesOrders/getPoPendingLoading",
			getSoLocalQuery: "salesOrders/getSoLocalQuery",
		}),
		posPending: {
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
					this.getPendingPOPagination.total /
						this.getPendingPOPagination.per_page
				);
			},
		},
		getCurrentPage: {
			get() {
				return this.getPendingPage;
				//return this.getPendingPOPagination.current_page
			},
		},
	},
	methods: {
		...mapActions({
			downloadPo: "salesOrders/downloadPo",
			fetchPoPending: "salesOrders/fetchPoPending",
			fetchPoPendingNew: "salesOrders/fetchPoPendingNew",
			setPendingPage: "salesOrders/setPendingPage",
		}),
		...globalMethods,
		formatDate(date) {
			if (date !== null) {
				return moment(date).format("MMM DD, YYYY");
			} else {
				return "N/A";
			}
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
		getItemPerPage() {
			return this.getPendingPOPagination.per_page;
		},
		nextPage() {
			if (this.getPendingPOPagination.next_page_url !== null) {
				var queryParams = this.getPendingPOPagination.next_page_url
					.split("?")[1]
					.replace(/[^0-9]/g, "");
				this.setPendingPage(queryParams);
				try {
					let passedData = {
						page: queryParams,
						special: 1,
					};
					if (this.getSoLocalQuery !== null && this.getSoLocalQuery !== "") {
						passedData["query"] = this.getSoLocalQuery;
					}
					this.fetchPoPendingNew(passedData);
					//this.fetchPoPending(queryParams)
				} catch (e) {
					this.notificationError(e);
				}
			}
			//var queryParams = this.getPendingPOPagination.next_page_url.split("?")[1].replace(/[^0-9]/g,'')

			/*
            try {
                this.fetchPoPending(queryParams)
            } catch (e) {
                this.notificationError(e)
            }*/
		},
		prevPage() {
			/*
            var queryParams = this.getPendingPOPagination.prev_page_url.split("?")[1].replace(/[^0-9]/g,'')
            try {
                this.fetchPoPending(queryParams)
            } catch (e) {
                this.notificationError(e)
            }*/
			if (this.getPendingPOPagination.prev_page_url !== null) {
				var queryParams = this.getPendingPOPagination.prev_page_url
					.split("?")[1]
					.replace(/[^0-9]/g, "");
				this.setPendingPage(queryParams);
				try {
					let passedData = {
						page: queryParams,
						special: 1,
					};
					if (this.getSoLocalQuery !== null && this.getSoLocalQuery !== "") {
						passedData["query"] = this.getSoLocalQuery;
					}

					this.fetchPoPendingNew(passedData);
					//this.fetchPoPendingNew(queryParams)
					//this.fetchPoPending(queryParams)
				} catch (e) {
					this.notificationError(e);
				}
			}
		},
		goToPage(pageNumber) {
			try {
				let passedData = {
					page: pageNumber,
					special: 1,
				};
				this.setPendingPage(pageNumber);
				if (this.getSoLocalQuery !== null && this.getSoLocalQuery !== "") {
					passedData["query"] = this.getSoLocalQuery;
				}
				this.fetchPoPendingNew(passedData);
				//this.fetchPoPending(pageNumber)
			} catch (e) {
				this.notificationError(e);
			}
			/*
            try {
                this.fetchPoPending(pageNumber)
            } catch (e) {
                this.notificationError(e)
            }*/
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
