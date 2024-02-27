<!-- @format -->

<template>
	<div class="po-mobile-wrapper">
		<div class="po-mobile-header">
			<h2>Sales Orders</h2>
			<div class="ml-2">
				<v-menu bottom offset-y left content-class="outbound-lists-menu">
					<template v-slot:activator="{ on, attrs }">
						<v-btn class="btn-white" icon v-bind="attrs" v-on="on">
							<v-icon>mdi-chevron-down</v-icon>
						</v-btn>
					</template>

					<v-list class="outbound-lists">
						<v-list-item v-for="(item, i) in tabs" :key="i">
							<v-list-item-title @click="getCurrentTab(i, item)">{{
								item
							}}</v-list-item-title>
						</v-list-item>
					</v-list>
				</v-menu>
			</div>
			<v-spacer></v-spacer>
			<v-btn dark color="primary" class="btn-blue ml-2" @click.stop="createPo">
				Create Sales Orders
			</v-btn>
		</div>

		<div class="search-mobile-wrapper">
			<Search
				placeholder="Search Order"
				className="search custom-search"
				:inputData.sync="search"
			/>

			<h4 class="pt-4 pl-1">{{ selectedTab }}</h4>
		</div>

		<div class="po-mobile-body">
			<v-tabs
				class="po-tabs"
				@change="onOpenTabChange"
				v-model="openCurrentTab"
				v-if="currentTab === 2"
			>
				<v-tab v-for="(n, i) in openSubTabs" :key="i">
					{{ n }}
				</v-tab>
			</v-tabs>
			<v-tabs
				class="po-tabs"
				@change="onBookingTabChange"
				v-model="bookingCurrentTab"
				v-if="currentTab === 4"
			>
				<v-tab v-for="(n, i) in bookingSubTabs" :key="i">
					{{ n }}
				</v-tab>
			</v-tabs>
			<!-- <v-tabs class="po-tabs" @change="onTabChange" v-model="currentTab">
				<v-tab v-for="(n, i) in tabs" :key="i" @click="getCurrentTab(i)">
					{{ n }}
				</v-tab>
			</v-tabs> -->

			<SalesOrderListAllMobileTable
				:items="posAll"
				v-if="currentTab === 0"
				:search.sync="search"
				@createPo="createPo"
				@editPo="editPo"
				@email="email"
				@viewPo="viewPo"
				@deletePo="deletePo"
			/>

			<SalesOrdersPendingMobileTable
				:items="posPending"
				v-if="currentTab === 2"
				:search.sync="search"
				@createPo="createPo"
				@editPo="editPo"
				@email="email"
				@viewPo="viewPo"
				@deletePo="deletePo"
			/>

			<SalesOrderBookedMobileTable
				:items="posShipped"
				v-if="currentTab === 4"
				:search.sync="search"
				@createPo="createPo"
				@editPo="editPo"
				@email="email"
				@viewPo="viewPo"
				@deletePo="deletePo"
			/>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import Search from "@/components/Search.vue";
import SalesOrderListAllMobileTable from "../SalesOrdersTab/SalesOrdersListAll/SalesOrderListAllMobileTable.vue";
import SalesOrdersPendingMobileTable from "../SalesOrdersTab/PendingTab/SalesOrdersPendingMobileTable.vue";
import SalesOrderBookedMobileTable from "../SalesOrdersTab/BookedTab/SalesOrderBookedMobileTable.vue";
import moment from "moment";
import _ from "lodash";
import axios from "axios";
let CancelToken = axios.CancelToken;
let cancel;

export default {
	name: "SalesOrdersMobileTable",
	props: ["items", "isMobile"],
	components: {
		Search,
		SalesOrderListAllMobileTable,
		SalesOrdersPendingMobileTable,
		SalesOrderBookedMobileTable,
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 15,
		typingTimeout: 0,
		search: "",
		tabs: [
			"All",
			"Active",
			"Open",
			"In Progress",
			"Booking",
			"In Transit",
			"Delivered",
		],
		openSubTabs: ["Drafts", "Change Requests", "Pending Approval", "Approved"],
		bookingSubTabs: [
			"Pending Quote",
			"Pending Approval",
			"Booked",
			"Awaiting Shipment",
		],
		currentTab: 0,
		openCurrentTab: 0,
		bookingCurrentTab: 0,
		headers: [
			{
				text: "Po No",
				align: "start",
				sortable: false,
				value: "po_number",
				width: "12%",
				fixed: true,
			},
			{
				text: "Date",
				align: "start",
				sortable: false,
				value: "created_at",
				width: "12%",
				fixed: true,
			},
			{
				text: "Vendor",
				align: "start",
				sortable: false,
				value: "supplier_id",
				width: "25%",
				fixed: true,
			},
			{
				text: "Ship To",
				align: "start",
				sortable: false,
				value: "ship_to",
				width: "25%",
				fixed: true,
			},
			{
				text: "Details",
				align: "start",
				sortable: false,
				value: "total",
				width: "12%",
				fixed: true,
			},
			{
				text: "",
				align: "center",
				sortable: false,
				value: "actions",
				width: "14%",
				fixed: true,
			},
		],
		selectedTab: "All",
	}),
	computed: {
		...mapGetters({
			getAllPos: "salesOrders/getAllPos",
			getVendorLists: "salesOrders/getVendorLists",
			getWarehouse: "warehouse/getWarehouse",
			getAllPoPending: "salesOrders/getAllPoPending",
			getAllPoShipped: "salesOrders/getAllPoShipped",
			getPendingPage: "salesOrders/getPendingPage",
			getShippedPage: "salesOrders/getShippedPage",
			getSoLocalQuery: "salesOrders/getSoLocalQuery",
			getAllPoPage: "salesOrders/getAllPoPage",
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
		posPending() {
			let posData = [];

			if (
				typeof this.getAllPoPending !== "undefined" &&
				this.getAllPoPending !== null
			) {
				if (
					typeof this.getAllPoPending.results !== "undefined" &&
					this.getAllPoPending.results !== null
				) {
					posData = this.getAllPoPending.results.data;
				}
			}
			return posData;
		},
		posShipped() {
			let posData = [];

			if (
				typeof this.getAllPoShipped !== "undefined" &&
				this.getAllPoShipped !== null
			) {
				if (
					typeof this.getAllPoShipped.results !== "undefined" &&
					this.getAllPoShipped.results !== null
				) {
					posData = this.getAllPoShipped.results.data;
				}
			}
			return posData;
		},
	},
	methods: {
		...mapActions({
			setCurrentSOTab: "salesOrders/setCurrentSOTab",
			fetchPoShippedNew: "salesOrders/fetchPoShippedNew",
			fetchPoPendingNew: "salesOrders/fetchPoPendingNew",
			setSoLocalQuery: "salesOrders/setSoLocalQuery",
			setPoShippedLoading: "salesOrders/setPoShippedLoading",
			setPoPendingLoading: "salesOrders/setPoPendingLoading",
			fetchPoAllNew: "salesOrders/fetchPoAllNew",
		}),
		onTabChange(i) {
			this.currentTab = i;
			this.setCurrentSOTab(i);
		},
		onOpenTabChange(i) {
			this.openCurrentTab = i;
		},
		onBookingTabChange(i) {
			this.bookingCurrentTab = i;
		},
		async getCurrentTab(i, tab_name) {
			this.selectedTab = tab_name;
			this.setSoLocalQuery("");
			this.search = "";
			let beforeTab = this.currentTab;
			this.currentTab = i;
			this.setCurrentSOTab(i);

			if (this.currentTab === 0 && beforeTab !== this.currentTab) {
				await this.fetchPoAllNew({
					page: this.getAllPoPage,
				});
			}
			if (this.currentTab === 2 && beforeTab !== this.currentTab) {
				await this.fetchPoPendingNew({
					page: this.getPendingPage,
				});
			}

			if (this.currentTab === 4 && beforeTab !== this.currentTab) {
				await this.fetchPoShippedNew({
					page: this.getShippedPage,
				});
			}
		},
		getDateFormat(date) {
			return moment(date).format("MMM DD, YYYY");
		},
		getWarehouseAddress(id) {
			if (
				typeof this.getWarehouse !== "undefined" &&
				this.getWarehouse !== null &&
				typeof this.getWarehouse.results !== "undefined" &&
				this.getWarehouse.results !== null &&
				typeof this.getWarehouse.results !== "undefined" &&
				this.getWarehouse.results !== null &&
				typeof this.getWarehouse.results.length !== "undefined" &&
				this.getWarehouse.results.length !== null
			) {
				let getData = this.getWarehouse.results;
				let findSizeValue =
					id !== "undefined" ? _.find(getData, (e) => e.id == id) : "";

				if (typeof findSizeValue !== "undefined") {
					if (typeof findSizeValue.address !== "undefined") {
						return findSizeValue.address;
					}
				} else {
					return "--";
				}
			}
		},
		getVendor(id) {
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
		createPo() {
			this.$emit("createPo");
		},
		editPo(item) {
			this.$emit("editPo", item);
		},
		viewPo(item) {
			this.$emit("viewPo", item);
		},
		email(item) {
			this.$emit("email", item);
		},
		deletePo(item) {
			this.$emit("deletePo", item);
		},
	},
	mounted() {
		//set tab
		if (this.$store.state.salesOrders.currentSoTab !== "undefined") {
			if (this.currentTab !== this.$store.state.salesOrders.currentSoTab) {
				this.currentTab = this.$store.state.salesOrders.currentSoTab;
			}
		}
	},
	watch: {
		search(query, oldQuery) {
			if (oldQuery !== "" && query == "") {
				if (this.currentTab == 1) {
					this.setPoShippedLoading(true);
				}
				if (this.currentTab == 0) {
					this.setPoPendingLoading(true);
				}

				clearTimeout(this.typingTimeout);
				this.typingTimeout = setTimeout(() => {
					if (cancel !== undefined) {
						cancel();
					}

					if (this.currentTab == 1) {
						this.fetchPoShippedNew({
							page: 1,
							query: this.getSoLocalQuery,
							special: 1,
							cancelToken: new CancelToken(function executor(c) {
								cancel = c;
							}),
						});
					} else if (this.currentTab == 0) {
						this.fetchPoPendingNew({
							page: 1,
							query: this.getSoLocalQuery,
							special: 1,
							cancelToken: new CancelToken(function executor(c) {
								cancel = c;
							}),
						});
					}
				}, 800);
			}

			if (query !== "" && query !== null) {
				if (this.currentTab == 1) {
					this.setPoShippedLoading(true);
				}
				if (this.currentTab == 0) {
					this.setPoPendingLoading(true);
				}

				clearTimeout(this.typingTimeout);
				this.typingTimeout = setTimeout(() => {
					if (cancel !== undefined) {
						cancel();
					}
					if (this.currentTab == 1) {
						this.setPoShippedLoading(false);
						this.fetchPoShippedNew({
							page: 1,
							query: this.getSoLocalQuery,
							special: 1,
							cancelToken: new CancelToken(function executor(c) {
								cancel = c;
							}),
						});
					} else if (this.currentTab == 0) {
						this.setPoPendingLoading(false);
						this.fetchPoPendingNew({
							page: 1,
							query: this.getSoLocalQuery,
							special: 1,
							cancelToken: new CancelToken(function executor(c) {
								cancel = c;
							}),
						});
					}
				}, 800);
			}
		},
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/salesOrders/salesOrdersTable.scss";
</style>
