<!-- @format -->

<template>
	<div class="po-desktop-wrapper">
		<div class="po-desktop-header">
			<h2>Sales Orders</h2>

			<v-spacer></v-spacer>

			<v-btn
				dark
				color="primary"
				class="btn-black ml-2"
				@click.stop="clearSelection"
				v-if="selected.length > 0"
			>
				Clear Selection
			</v-btn>

			<v-btn
				dark
				color="primary"
				class="btn-white download ml-2"
				@click.stop="downloadMultiple"
				v-if="selected.length > 0"
				:disabled="getDownloadLoading"
			>
				<img
					src="@/assets/icons/download.svg"
					class="mr-1"
					width="14px"
					height="14px"
				/>
				{{ getDownloadLoading ? "Downloading..." : "Download" }}
				<span class="ml-1">({{ selected.length }})</span>
			</v-btn>

			<v-menu
				offset-y
				bottom
				nudge-bottom="6"
				nudge-left="150"
				:close-on-content-click="false"
				:nudge-width="150"
			>
				<template v-slot:activator="{ on, attrs }">
					<v-btn class="btn-white ml-2" v-bind="attrs" v-on="on">
						Export
						<v-icon right class="export-icon-arrow my-1"
							>mdi-chevron-down</v-icon
						>
					</v-btn>
				</template>

				<v-card>
					<v-list>
						<v-list-item style="min-height: 35px;">
							<v-list-item-title
								style="cursor:pointer;color:#4A4A4A;font-size:14px !important;font-weight:400"
								class="text-start ml-3"
								@click="csvReportChangeFunction('all')"
								>All Data</v-list-item-title
							>
						</v-list-item>

						<v-list-item style="min-height: 35px;">
							<v-list-item-title
								style="cursor:pointer;color:#4A4A4A;font-size:14px !important;font-weight:400"
								class="text-start ml-3"
								@click="csvReportChangeFunction('single')"
								>Selected Tab</v-list-item-title
							>
						</v-list-item>
					</v-list>
				</v-card>
			</v-menu>

			<v-btn dark color="primary" class="btn-blue ml-2" @click.stop="createPo">
				+ Create Sales Order
			</v-btn>
		</div>

		<div class="po-desktop-body">
			<v-tabs class="po-tabs" @change="onTabChange" v-model="currentTab">
				<v-tab v-for="(n, i) in tabs" :key="i" @click="getCurrentTab(i)">
					{{ n.label }}

					<v-badge
						v-if="i === 1 || (i == 2 && currentTab == 2)"
						:color="currentTab === 1 || currentTab == 2 ? '#0171A1' : '#F93131'"
						class="customBadge ml-1"
						inline
						:content="getCurrentTabCounter(i)"
					>
					</v-badge>
				</v-tab>
			</v-tabs>

			<div class="po-tabs-search-wrapper">
				<div class="open-approval-tab" v-if="currentTabstate === null">
					<v-tabs @change="onAllTabChange" v-model="allCurrentTab" hide-slider>
						<v-tab
							v-for="(tab, index) in allSubTabs"
							:key="index"
							:class="
								index == 0
									? 'all'
									: index == 2
									? 'archived'
									: index == 1
									? 'active'
									: 'pending'
							"
							class="open-tabs"
						>
							{{ tab.label }}

							<!-- <v-badge
								v-if="index === allCurrentTab"
								color="#0171A1"
								class="customBadge ml-1"
								inline
								:content="getSalesOrderCount(index)"
							>
							</v-badge> -->
						</v-tab>
					</v-tabs>
				</div>

				<div class="open-approval-tab" v-if="currentTabstate === 'draft'">
					<v-tabs
						@change="onOpenTabChange"
						v-model="openCurrentTab"
						hide-slider
					>
						<v-tab
							v-for="(tab, index) in openSubTabs"
							:key="index"
							:class="
								index == 0
									? 'drafts'
									: index == 1
									? 'change-request'
									: 'pending'
							"
							class="open-tabs"
						>
							{{ tab.label }}

							<v-badge
								v-if="index === 1"
								:color="openCurrentTab === 1 ? '#0171A1' : '#F93131'"
								class="customBadge ml-1"
								inline
								:content="getOpenSubTabCounter(index)"
							>
							</v-badge>
						</v-tab>
					</v-tabs>
				</div>

				<div
					class="open-approval-tab"
					v-if="currentTabstate === 'requires_booking'"
				>
					<v-tabs
						@change="onBookingTabChange"
						v-model="bookingCurrentTab"
						hide-slider
					>
						<v-tab
							v-for="(tab, index) in bookingSubTabs"
							:key="index"
							:class="
								index == 0
									? 'requires-booking'
									: index == 3
									? 'awaiting-shipment'
									: index == 2
									? 'pending-approval'
									: 'pending-quote'
							"
							class="booking-tabs"
						>
							{{ tab.label }}

							<!-- <v-badge
								v-if="index === bookingCurrentTab"
								color="#0171A1"
								class="customBadge ml-1"
								inline
								:content="getSalesOrderCount(index)"
							>
							</v-badge> -->
						</v-tab>
					</v-tabs>
				</div>

				<div
					class="open-approval-tab"
					v-if="currentTabstate === 'all_in_progress'"
				>
					<v-tabs
						@change="onInProgressTabChange"
						v-model="inProgressCurrentTab"
						v-if="!isMobile"
						hide-slider
					>
						<v-tab
							v-for="(tab, index) in inProgressSubTabs"
							:key="index"
							:class="
								index == 0
									? 'all_in_progress'
									: index == 3
									? 'close_passed_crd'
									: index == 2
									? 'in-production'
									: 'approved'
							"
							class="in-progress-tabs"
						>
							{{ tab.label }}

							<v-badge
								v-if="index == 3"
								:color="inProgressCurrentTab === 3 ? '#0171A1' : '#819fb2'"
								class="customBadge ml-1"
								inline
								:content="getInProgressSubTabCounter(index)"
							>
							</v-badge>
						</v-tab>
					</v-tabs>
				</div>

				<Search
					@searchLocal="searchLocal"
					:currentTab="currentTab"
					placeholder="Search Order"
					className="search custom-search"
					:inputData.sync="search"
				/>
			</div>

			<SalesOrderListAllDesktopTable
				:items="posAll"
				:headers="dynamicHeader"
				:search.sync="search"
				:selectedPO.sync="selected"
				:currentTab="currentTabstate"
				:openCurrentTab="currentOpenTabstate"
				:bookingCurrentTab="currentBookingTabstate"
				@createPo="createPo"
				@editPo="editPo"
				@reviewOrder="reviewOrder"
				@email="email"
				@viewPo="viewPo"
				@deletePo="deletePo"
			/>
			<ConfirmDialog :dialogData.sync="csvReportDialog">
				<template v-slot:dialog_icon>
					<div
						v-if="getCsvReportLoading && !csvReportButtonShowCondition"
						class="header-wrapper-close"
					>
						<img
							src="@/assets/icons/progress.svg"
							style="transition-duration: 0.8s;transition-property: transform;"
							alt="alert"
						/>
					</div>
					<div v-else class="header-wrapper-close">
						<img src="@/assets/icons/report-generate-file.svg" alt="alert" />
						<v-btn class="ma-0 pa-0" @click="csvReportDialog = false" icon
							><v-icon color="#4A4A4A">mdi-close</v-icon></v-btn
						>
					</div>
				</template>

				<template v-slot:dialog_title>
					<h2 v-if="getCsvReportLoading && !csvReportButtonShowCondition">
						Preparing File...
					</h2>
					<div v-else>
						<h2 v-if="orderExportType == 'all'">
							Once file is ready you will get notification!
						</h2>
						<h2 v-else>File is ready!</h2>
					</div>
				</template>

				<template v-slot:dialog_content>
					<p v-if="getCsvReportLoading && !csvReportButtonShowCondition">
						We are preparing your requested file. Generating the report might
						take a few moments we will notify you once its ready.
					</p>
					<div v-else>
						<p v-if="orderExportType == 'all'">
							Your request for export order list, has been submitted. Once file
							is ready you will get notification.
						</p>
						<p v-else>
							We have prepared your requested file. Download will be started
							automatically in a moment.
						</p>
					</div>
				</template>

				<template v-slot:dialog_actions>
					<div v-if="getCsvReportLoading && !csvReportButtonShowCondition">
						<v-btn
							style="color: #4A4A4A !important;"
							class="btn-white"
							text
							@click="cancelCsvApi"
						>
							Cancel
						</v-btn>
					</div>
					<div v-else>
						<v-btn
							style="color: #4A4A4A !important;"
							class="btn-white"
							text
							@click="csvReportDialog = false"
						>
							Close
						</v-btn>
					</div>
				</template>
			</ConfirmDialog>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import Search from "@/components/Search.vue";
import moment from "moment";
import _ from "lodash";
import SalesOrderListAllDesktopTable from "../SalesOrdersTab/SalesOrdersListAll/SalesOrderListAllDesktopTable.vue";
import axios from "axios";
import {
	ALL,
	DRAFT,
	PENDING_QUOTE,
	APPROVED,
	PENDING_APPROVAL,
	REQUIRES_BOOKING,
	RECEIVED,
	ACTIVE,
	IN_TRANSIT,
	DELIVERED,
	ARCHIVED,
	BOOKING_PENDING_APPROVAL,
	AWAITING_SHIPMENT,
	IN_PRODUCTION,
	CLOSE_PASSED_CRD,
	ALL_IN_PROGRESS,
} from "../../../constants/states";
import ConfirmDialog from "../../Dialog/GlobalDialog/ConfirmDialog.vue";
let CancelToken = axios.CancelToken;
let cancel;

export default {
	name: "SalesOrdersDesktopTable",
	props: ["items", "isMobile", "selectedPO", "badges"],
	components: {
		Search,
		SalesOrderListAllDesktopTable,
		ConfirmDialog,
	},
	data: () => ({
		snackbar: true,
		page: 1,
		pageCount: 0,
		itemsPerPage: 0,
		search: "",
		typingTimeout: 0,
		order_no: [
			{
				text: "Order No",
				align: "start",
				sortable: false,
				value: "order_number",
				fixed: true,
				width: "7%",
				sort_order: 1,
			},
		],
		action: [
			{
				text: "",
				align: "end",
				value: "actions",
				sortable: false,
				fixed: true,
				width: "12%",
				sort_order: 10,
			},
		],
		header: [
			{
				text: "Buyer",
				align: "start",
				sortable: false,
				value: "buyer",
				fixed: true,
				width: "20%",
				sort_order: 4,
			},
			{
				text: "Ship To",
				align: "start",
				sortable: false,
				value: "ship_to",
				fixed: true,
				width: "20%",
				sort_order: 5,
			},
			{
				text: "Cargo Ready",
				align: "start",
				sortable: false,
				value: "cargo_ready_date",
				fixed: true,
				width: "12%",
				sort_order: 6,
			},
			{
				text: "Details",
				align: "end",
				sortable: false,
				value: "details",
				fixed: true,
				width: "12%",
				sort_order: 7,
			},
		],
		buyerAndShipTo: [
			{
				text: "Buyer & Ship To",
				align: "start",
				sortable: false,
				value: "buyer_ship_to",
				fixed: true,
				width: "20%",
				sort_order: 4,
			},
		],
		inProgressHeader: [
			{
				text: "Original CRD",
				align: "start",
				sortable: false,
				value: "cargo_ready_date",
				fixed: true,
				width: "12%",
				sort_order: 5,
			},
			{
				text: "Cartons & CBM",
				align: "end",
				sortable: false,
				value: "carton_cbm",
				fixed: true,
				width: "12%",
				sort_order: 6,
			},
			{
				text: "Production Status",
				align: "center",
				sortable: false,
				value: "production_status",
				fixed: true,
				width: "18%",
				sort_order: 7,
			},
			{
				text: "Estimated CRD",
				align: "start",
				sortable: false,
				value: "estimated_crd",
				fixed: true,
				width: "12%",
				sort_order: 8,
			},
		],
		tabs: [
			{
				label: "All",
				value: ALL,
				badge: "0",
			},
			{
				label: "Open",
				value: DRAFT,
				badge: "0",
			},
			{
				label: "In Progress",
				value: ALL_IN_PROGRESS,
				badge: "0",
			},
			{
				label: "Booking",
				value: REQUIRES_BOOKING,
				badge: "0",
			},
			{
				label: "In Transit",
				value: IN_TRANSIT,
				badge: "0",
			},
			{
				label: "Delivered",
				value: DELIVERED,
				badge: "0",
			},
		],
		allSubTabs: [
			{
				label: "All Orders",
				value: ALL,
				badge: "0",
			},
			{
				label: "Active",
				value: ACTIVE,
				badge: "0",
			},
			{
				label: "Archived",
				value: ARCHIVED,
				badge: "0",
			},
		],
		openSubTabs: [
			{
				label: "Drafts",
				value: DRAFT,
				badge: "0",
			},
			{
				label: "Requests Received",
				value: RECEIVED,
				badge: "0",
			},
			{
				label: "Pending Approval",
				value: PENDING_APPROVAL,
				badge: "0",
			},
		],
		bookingSubTabs: [
			{
				label: "Requires Booking",
				value: REQUIRES_BOOKING,
				badge: "0",
			},
			{
				label: "Pending Quote",
				value: PENDING_QUOTE,
				badge: "0",
			},
			{
				label: "Pending Approval",
				value: BOOKING_PENDING_APPROVAL,
				badge: "0",
			},
			{
				label: "Awaiting Shipment",
				value: AWAITING_SHIPMENT,
				badge: "0",
			},
		],
		inProgressSubTabs: [
			{
				label: "ALL",
				value: ALL_IN_PROGRESS,
				badge: "0",
			},
			{
				label: "Approved",
				value: APPROVED,
				badge: "0",
			},
			{
				label: "In Production",
				value: IN_PRODUCTION,
				badge: "0",
			},
			{
				label: "Passed CRD",
				value: CLOSE_PASSED_CRD,
				badge: "0",
			},
		],
		Issued_at_by: [
			{
				text: "Issued At & By",
				align: "start",
				sortable: false,
				value: "created_at",
				fixed: true,
				width: "17%",
				sort_order: 2,
			},
		],
		updated_at_by: [
			{
				text: "Updated At & By",
				align: "start",
				sortable: false,
				value: "updated_at_by",
				fixed: true,
				width: "17%",
				sort_order: 3,
			},
		],
		updated_at: [
			{
				text: "Updated At",
				align: "start",
				sortable: false,
				value: "updated_at",
				fixed: true,
				width: "18%",
				sort_order: 3,
			},
		],
		status: [
			{
				text: "Status",
				align: "center",
				sortable: false,
				value: "status",
				fixed: true,
				width: "12%",
				sort_order: 8,
			},
		],
		etd: [
			{
				text: "ETD",
				align: "start",
				sortable: false,
				value: "etd",
				fixed: true,
				width: "12%",
				sort_order: 8,
			},
		],
		inTransitStatus: [
			{
				text: "Status",
				align: "center",
				sortable: false,
				value: "status",
				fixed: true,
				width: "12%",
				sort_order: 10,
			},
		],
		est_crd: [
			{
				text: "Est. CRD",
				align: "start",
				sortable: false,
				value: "estimated_crd",
				fixed: true,
				width: "12%",
				sort_order: 5,
			},
		],
		deliveredDate: [
			{
				text: "Delivered At",
				align: "center",
				sortable: false,
				value: "deliver_date",
				fixed: true,
				width: "12%",
				sort_order: 10,
			},
		],
		bookingHeader: [
			{
				text: "Ref #",
				align: "start",
				sortable: false,
				value: "ref_no",
				fixed: true,
				width: "8%",
				sort_order: 6,
			},
			{
				text: "Forwarder",
				align: "start",
				sortable: false,
				value: "forwarder",
				fixed: true,
				width: "8%",
				sort_order: 7,
			},
			{
				text: "Mode & Volume",
				align: "start",
				sortable: false,
				value: "mode_volume",
				fixed: true,
				width: "12%",
				sort_order: 8,
			},
		],
		requireBookingHeader: [
			{
				text: "Forwarder",
				align: "start",
				sortable: false,
				value: "forwarder",
				fixed: true,
				width: "12%",
				sort_order: 7,
			},
			{
				text: "Mode & Volume",
				align: "start",
				sortable: false,
				value: "mode_volume",
				fixed: true,
				width: "16%",
				sort_order: 8,
			},
		],
		cargo: [
			{
				text: "Cargo",
				align: "end",
				sortable: false,
				value: "cargo",
				fixed: true,
				width: "12%",
				sort_order: 9,
			},
		],
		Progress: [
			{
				text: "Progress",
				align: "end",
				sortable: false,
				value: "progress",
				fixed: true,
				width: "12%",
				sort_order: 9,
			},
		],
		currentTab: 0,
		allCurrentTab: 0,
		openCurrentTab: 0,
		bookingCurrentTab: 0,
		inProgressCurrentTab: 0,
		currentTabstate: null,
		currentAllTabstate: null,
		currentOpenTabstate: "draft",
		currentBookingTabstate: "pending_quote",
		currentInProgressTabstate: ALL_IN_PROGRESS,
		csvReportButtonShowCondition: false,
		csvReportDialog: false,
		orderExportType: "single",
	}),
	computed: {
		...mapGetters({
			getPendingPage: "salesOrders/getPendingPage",
			getShippedPage: "salesOrders/getShippedPage",
			getCurrentPage: "salesOrders/getCurrentPage",
			getAllPo: "salesOrders/getAllPo",
			getVendorLists: "salesOrders/getVendorLists",
			getWarehouse: "warehouse/getWarehouse",
			getDownloadLoading: "salesOrders/getDownloadLoading",
			getPoDeleteLoading: "salesOrders/getPoDeleteLoading",
			getAllPoPending: "salesOrders/getAllPoPending",
			getAllPoShipped: "salesOrders/getAllPoShipped",
			getSoLocalQuery: "salesOrders/getSoLocalQuery",
			getAllPos: "salesOrders/getAllPos",
			getAllPoPage: "salesOrders/getAllPoPage",
			getCurrentSoTab: "salesOrders/getCurrentSoTab",
			getCurrentSoOpenTab: "salesOrders/getCurrentSoOpenTab",
			getCurrentSoAllTab: "salesOrders/getCurrentSoAllTab",
			getCurrentSoBookingTab: "salesOrders/getCurrentSoBookingTab",
			getCurrentSoInProgressTab: "salesOrders/getCurrentSoInProgressTab",
			getCsvReportLoading: "salesOrders/getCsvReportLoading",
			getSetCsvReportData: "salesOrders/getSetCsvReportData",
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
		selected: {
			get() {
				return this.selectedPO;
			},
			set(value) {
				this.$emit("update:selectedPO", value);
			},
		},
		dynamicHeader() {
			let ordersState = this.tabs[this.currentTab].label;
			let openOrderState = this.openSubTabs[this.openCurrentTab].label;
			let bookingOrderState = this.bookingSubTabs[this.bookingCurrentTab].label;
			let inProgressOrderState = this.inProgressSubTabs[
				this.inProgressCurrentTab
			].label;

			let dynamicHeader;

			if (
				this.currentTabstate != "draft" &&
				this.currentTabstate != REQUIRES_BOOKING
			) {
				if (ordersState == "All") {
					dynamicHeader = [
						...this.order_no,
						...this.header,
						...this.Issued_at_by,
						...this.status,
						...this.action,
					];
				} else if (ordersState == "In Progress") {
					dynamicHeader = [
						...this.order_no,
						...this.inProgressHeader,
						...this.buyerAndShipTo,
						...this.updated_at_by,
						...this.action,
					];
					if (inProgressOrderState === "Approved") {
						dynamicHeader = [
							...this.order_no,
							...this.header,
							...this.Issued_at_by,
							...this.updated_at_by,
							...this.action,
						];
					}
				} else if (ordersState === "In Transit") {
					dynamicHeader = [
						...this.order_no,
						...this.buyerAndShipTo,
						...this.bookingHeader,
						...this.cargo,
						...this.inTransitStatus,
						...this.Issued_at_by,
						...this.action,
					];
				} else if (ordersState === "Delivered") {
					dynamicHeader = [
						...this.order_no,
						...this.buyerAndShipTo,
						...this.bookingHeader,
						...this.deliveredDate,
						...this.Issued_at_by,
						...this.cargo,
						...this.action,
					];
				} else if (ordersState === "Archived") {
					dynamicHeader = [
						...this.order_no,
						...this.header,
						...this.Issued_at_by,
						...this.status,
						...this.action,
					];
				}
			} else if (this.currentTabstate === "draft") {
				if (openOrderState == "Drafts") {
					dynamicHeader = [
						...this.order_no,
						...this.header,
						...this.updated_at_by,
						...this.action,
					];
				} else if (openOrderState === "Requests Received") {
					dynamicHeader = [
						...this.order_no,
						...this.header,
						...this.updated_at,
						...this.status,
						...this.action,
					];
				} else if (openOrderState === "Pending Approval") {
					dynamicHeader = [
						...this.order_no,
						...this.header,
						...this.updated_at_by,
						...this.status,
						...this.action,
					];
				} else if (openOrderState === "Approved") {
					dynamicHeader = [
						...this.order_no,
						...this.header,
						...this.Issued_at_by,
						...this.updated_at_by,
						...this.action,
					];
				}
			} else if (this.currentTabstate === REQUIRES_BOOKING) {
				if (bookingOrderState == "Pending Quote") {
					dynamicHeader = [
						...this.order_no,
						...this.Issued_at_by,
						...this.buyerAndShipTo,
						...this.bookingHeader,
						...this.cargo,
						...this.action,
					];
				} else if (bookingOrderState === "Pending Approval") {
					dynamicHeader = [
						...this.order_no,
						...this.Issued_at_by,
						...this.buyerAndShipTo,
						...this.bookingHeader,
						...this.cargo,
						...this.action,
					];
				} else if (bookingOrderState === "Requires Booking") {
					dynamicHeader = [
						...this.order_no,
						...this.Issued_at_by,
						...this.buyerAndShipTo,
						...this.requireBookingHeader,
						...this.Progress,
						...this.action,
					];
				} else if (bookingOrderState === "Awaiting Shipment") {
					dynamicHeader = [
						...this.order_no,
						...this.Issued_at_by,
						...this.buyerAndShipTo,
						...this.bookingHeader,
						...this.Progress,
						...this.action,
					];
				}
			}
			return _.orderBy(dynamicHeader, ["sort_order"], ["asc"]);
		},
	},
	methods: {
		...mapActions({
			setCurrentSOTab: "salesOrders/setCurrentSOTab",
			fetchPoShippedNew: "salesOrders/fetchPoShippedNew",
			fetchPoPendingNew: "salesOrders/fetchPoPendingNew",
			setPoGlobalQuery: "salesOrders/setPoGlobalQuery",
			setSoLocalQuery: "salesOrders/setSoLocalQuery",
			setPoPendingLoading: "salesOrders/setPoPendingLoading",
			setPoShippedLoading: "salesOrders/setPoShippedLoading",
			setPoAllLoading: "salesOrders/setPoAllLoading",
			fetchPoAllNew: "salesOrders/fetchPoAllNew",
			setAllPage: "salesOrders/setAllPage",
			fetchOrderCounter: "salesOrders/fetchOrderCounter",
			fetchOrdersReport: "salesOrders/fetchOrdersReport",
		}),
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
		reviewOrder(item, change_request) {
			this.$emit("reviewOrder", item, change_request);
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
		onTabChange(i) {
			this.currentTab = i;
			this.selected = [];
			this.setCurrentSOTab(i);
		},
		onAllTabChange(i) {
			this.setSoLocalQuery("");
			this.setAllPage(1);
			this.allCurrentTab = i;
			this.search = "";
			this.currentAllTabstate = this.allSubTabs[i].value;

			let pathData =
				typeof this.$router.history.current.query.tab !== "undefined"
					? this.$router.history.current.query.tab
					: null;

			if (
				this.currentAllTabstate == ALL &&
				pathData !== null &&
				pathData !== "all"
			) {
				this.$router.push("?tab=all");
			} else if (pathData !== null && pathData !== this.currentAllTabstate) {
				this.$router.push(`?tab=${this.currentAllTabstate}`);
			}

			this.$store.dispatch(
				"salesOrders/setSoCurrentAllTab",
				this.allCurrentTab
			);
			this.$store.dispatch("salesOrders/clearAllSalesOrdersData");

			this.$emit("currentTab", this.allSubTabs[this.allCurrentTab].value);
		},
		onOpenTabChange(i) {
			this.setSoLocalQuery("");
			this.setAllPage(1);
			this.openCurrentTab = i;
			this.search = "";
			this.currentOpenTabstate = this.openSubTabs[i].value;

			let pathData =
				typeof this.$router.history.current.query.tab !== "undefined"
					? this.$router.history.current.query.tab
					: null;

			if (pathData !== null && pathData !== this.currentOpenTabstate) {
				this.fetchOrderCounter();
				this.$router.push(`?tab=${this.currentOpenTabstate}`);
			}

			this.$store.dispatch(
				"salesOrders/setSoCurrentOpenTab",
				this.openCurrentTab
			);
			this.$store.dispatch("salesOrders/clearAllSalesOrdersData");

			this.$emit("currentTab", this.openSubTabs[this.openCurrentTab].value);
		},
		onInProgressTabChange(i) {
			this.setSoLocalQuery("");
			this.setAllPage(1);
			this.inProgressCurrentTab = i;
			this.search = "";
			this.currentInProgressTabstate = this.inProgressSubTabs[i].value;

			let pathData =
				typeof this.$router.history.current.query.tab !== "undefined"
					? this.$router.history.current.query.tab
					: null;

			if (pathData !== null && pathData !== this.currentInProgressTabstate) {
				this.$router.push(`?tab=${this.currentInProgressTabstate}`);
			}

			this.$store.dispatch(
				"salesOrders/setSoCurrentInProgressTab",
				this.inProgressCurrentTab
			);
			this.$store.dispatch("salesOrders/clearAllSalesOrdersData");

			this.$emit("currentTab", this.currentInProgressTabstate);
		},
		onBookingTabChange(i) {
			this.setSoLocalQuery("");
			this.setAllPage(1);
			this.bookingCurrentTab = i;
			this.search = "";
			this.currentBookingTabstate = this.bookingSubTabs[i].value;

			let pathData =
				typeof this.$router.history.current.query.tab !== "undefined"
					? this.$router.history.current.query.tab
					: null;

			if (pathData !== null && pathData !== this.currentBookingTabstate) {
				this.$router.push(`?tab=${this.currentBookingTabstate}`);
			}

			this.$store.dispatch(
				"salesOrders/setSoCurrentBookingTab",
				this.bookingCurrentTab
			);
			this.$store.dispatch("salesOrders/clearAllSalesOrdersData");

			this.$emit(
				"currentTab",
				this.bookingSubTabs[this.bookingCurrentTab].value
			);
		},
		async getCurrentTab(i) {
			this.setSoLocalQuery("");
			this.setAllPage(1);
			this.search = "";
			let beforeTab = this.currentTab;
			this.currentTab = i;
			this.currentTabstate = this.tabs[i].value;
			this.setCurrentSOTab(i);

			let pathData =
				typeof this.$router.history.current.query.tab !== "undefined"
					? this.$router.history.current.query.tab
					: null;

			if (
				this.currentTabstate == ALL &&
				pathData !== null &&
				pathData !== "all"
			) {
				this.$store.dispatch("salesOrders/setSoCurrentAllTab", 1);
				this.$router.push(`?tab=${ACTIVE}`);
			} else if (
				pathData !== null &&
				pathData !== this.currentTabstate &&
				this.currentTabstate !== ALL
			) {
				this.$router.push(`?tab=${this.currentTabstate}`);
			}

			if (
				this.currentTabstate != "draft" &&
				this.currentTabstate != "pending_quote"
			) {
				this.$emit(
					"currentTab",
					this.tabs[this.currentTab].value === null
						? ACTIVE
						: this.tabs[this.currentTab].value
				);
			}

			if (this.currentTabstate === "draft" && beforeTab !== this.currentTab) {
				this.fetchOrderCounter();
				this.$store.dispatch("salesOrders/setSoCurrentOpenTab", 0);
				this.$emit("currentTab", DRAFT);
			}

			if (
				this.currentTabstate === ALL_IN_PROGRESS &&
				beforeTab !== this.currentTab
			) {
				this.$store.dispatch("salesOrders/setSoCurrentInProgressTab", 0);
				this.$emit("currentTab", ALL_IN_PROGRESS);
			}
			if (
				this.currentTabstate === REQUIRES_BOOKING &&
				beforeTab !== this.currentTab
			) {
				this.$store.dispatch("salesOrders/setSoCurrentBookingTab", 0);
				this.$emit("currentTab", REQUIRES_BOOKING);
			}
			this.$store.dispatch("salesOrders/clearAllSalesOrdersData");
		},
		getSalesOrderCount(tab) {
			let data = "0";

			if (tab == 0) {
				let allOrdersLength =
					typeof this.getAllPos.results !== "undefined"
						? this.getAllPos.results.total
						: 0;

				data = parseInt(allOrdersLength);
			} else {
				data =
					typeof this.getAllPos.results !== "undefined"
						? this.getAllPos.results.total
						: 0;
			}

			let finalData = data !== 0 ? data : "0";

			return finalData;
		},
		clearSelection() {
			this.$emit("clearSelectedPO");
		},
		downloadMultiple() {
			this.$emit("downloadMultiple");
		},
		searchLocal() {
			this.setPoAllLoading(true);

			clearTimeout(this.typingTimeout);
			this.typingTimeout = setTimeout(() => {
				if (cancel !== undefined) {
					cancel();
				}
				const cancelToken = new CancelToken(function executor(c) {
					cancel = c;
				});

				let searchSoQuery = this.getSoLocalQuery == "" ? this.getAllPoPage : 1;

				if (
					this.currentTabstate == IN_TRANSIT ||
					this.currentTabstate == DELIVERED
				) {
					let ordersState = this.tabs[this.currentTab].value;
					this.searchCommon(
						searchSoQuery,
						this.getSoLocalQuery,
						1,
						ordersState,
						cancelToken
					);
				}

				if (this.currentTabstate === ALL) {
					let ordersState = this.allSubTabs[this.allCurrentTab].value;
					this.searchCommon(
						searchSoQuery,
						this.getSoLocalQuery,
						1,
						ordersState,
						cancelToken
					);
				}

				if (this.currentTabstate === DRAFT) {
					let ordersState = this.openSubTabs[this.openCurrentTab].value;
					this.searchCommon(
						searchSoQuery,
						this.getSoLocalQuery,
						1,
						ordersState,
						cancelToken
					);
				}

				if (this.currentTabstate === ALL_IN_PROGRESS) {
					let ordersState = this.inProgressSubTabs[this.inProgressCurrentTab]
						.value;
					this.searchCommon(
						searchSoQuery,
						this.getSoLocalQuery,
						1,
						ordersState,
						cancelToken
					);
				}

				if (this.currentTabstate === REQUIRES_BOOKING) {
					let ordersState = this.bookingSubTabs[this.bookingCurrentTab].value;
					this.searchCommon(
						searchSoQuery,
						this.getSoLocalQuery,
						1,
						ordersState,
						cancelToken
					);
				}
			}, 800);
		},
		searchCommon(page, query, special, state, cancelToken) {
			this.fetchPoAllNew({
				page: page,
				query: query,
				special: special,
				state: state,
				cancelToken: cancelToken,
			});
		},
		getCurrentTabCounter(tab) {
			let returnVal = "0";

			if (this.badges !== "undefined" && this.badges !== "") {
				let { received, close_passed_crd } = this.badges;

				const label = this.tabs[tab].label;
				let badge = this.tabs[tab].badge;

				if (label == "Open") {
					badge = received;
				}

				if (label == "In Progress") {
					badge = close_passed_crd;
				}

				returnVal = badge !== 0 ? badge : "0";
			}

			return returnVal;
		},
		getOpenSubTabCounter(tab) {
			let returnVal = "0";

			if (this.badges !== "undefined" && this.badges !== "") {
				let { received } = this.badges;

				const label = this.openSubTabs[tab].label;
				let badge = this.openSubTabs[tab].badge;

				if (label == "Requests Received") {
					badge = received;
				}
				returnVal = badge !== 0 ? badge : "0";
			}

			return returnVal;
		},
		getInProgressSubTabCounter(tab) {
			let returnVal = "0";

			if (this.badges !== "undefined" && this.badges !== "") {
				let { close_passed_crd } = this.badges;

				const label = this.inProgressSubTabs[tab].label;
				let badge = this.inProgressSubTabs[tab].badge;

				if (label == "Passed CRD") {
					badge = close_passed_crd;
				}
				returnVal = badge !== 0 ? badge : "0";
			}

			return returnVal;
		},
		async csvReportChangeFunction(value) {
			if (value !== "") {
				this.orderExportType = value;
				try {
					this.csvReportButtonShowCondition = false;
					this.csvReportDialog = true;
					let dataWithPage = {
						cancelToken: new CancelToken(function executor(c) {
							cancel = c;
						}),
						state: this.$router.history.current.query.tab,
						exportType: value,
					};
					await this.fetchOrdersReport(dataWithPage);
					if (
						value != "all" &&
						typeof this.getSetCsvReportData !== "undefined" &&
						this.getSetCsvReportData !== undefined &&
						this.getSetCsvReportData !== null
					) {
						var url = window.URL.createObjectURL(
							new Blob([this.getSetCsvReportData])
						);
						this.downloadCsvReportFile("productReport.csv", url);
					}
					this.csvReportButtonShowCondition = true;
				} catch (e) {
					this.csvReportButtonShowCondition = false;
					if (this.cancelerrorIs) return (this.cancelerrorIs = false);
					this.notificationError(e);
				}
			}
		},
		downloadCsvReportFile(fileName, urlData) {
			if (
				typeof this.getSetCsvReportData !== "undefined" &&
				this.getSetCsvReportData !== undefined &&
				this.getSetCsvReportData !== null
			) {
				var aLink = document.createElement("a");
				aLink.download = fileName;
				aLink.href = urlData;
				document.body.appendChild(aLink);
				aLink.click();
				aLink.remove();
			}
		},
		cancelCsvApi() {
			this.csvReportDialog = false;
			this.csvReportButtonShowCondition = false;
			if (cancel !== undefined && this.getCsvReportLoading) {
				this.cancelerrorIs = true;
				cancel();
			}
		},
	},
	mounted() {
		// this.$emit('currentTab', this.tabs[this.currentTab].value, true);

		if (this.$router.history.current.query.tab == ACTIVE) {
			this.$store.dispatch("salesOrders/setCurrentSOTab", 0);
			this.$store.dispatch("salesOrders/setSoCurrentAllTab", 1);
		}

		//set tab
		if (this.$store.state.salesOrders.currentSoTab !== "undefined") {
			if (this.currentTab !== this.$store.state.salesOrders.currentSoTab) {
				this.currentTab = this.$store.state.salesOrders.currentSoTab;
				this.currentTabstate = this.tabs[this.currentTab].value;
				this.$emit("currentTab", this.currentTabstate, true);
			}
		}

		if (this.$store.state.salesOrders.currentSoAllTab !== "undefined") {
			if (
				this.allCurrentTab !== this.$store.state.salesOrders.currentSoAllTab
			) {
				this.allCurrentTab = this.$store.state.salesOrders.currentSoAllTab;
				this.$emit(
					"currentTab",
					this.allSubTabs[this.allCurrentTab].value,
					true
				);
			}
		}

		if (this.$store.state.salesOrders.currentSoOpenTab !== "undefined") {
			if (
				this.openCurrentTab !== this.$store.state.salesOrders.currentSoOpenTab
			) {
				this.openCurrentTab = this.$store.state.salesOrders.currentSoOpenTab;
				this.currentOpenTabstate = this.openSubTabs[this.openCurrentTab].value;

				this.$emit("currentTab", this.currentOpenTabstate, true);
			}
		}

		if (this.$store.state.salesOrders.currentSoBookingTab !== "undefined") {
			if (
				this.bookingCurrentTab !==
				this.$store.state.salesOrders.currentSoBookingTab
			) {
				this.bookingCurrentTab = this.$store.state.salesOrders.currentSoBookingTab;
				this.$emit(
					"currentTab",
					this.bookingSubTabs[this.bookingCurrentTab].value,
					true
				);
			}
		}

		if (this.$store.state.salesOrders.currentSoInProgressTab !== "undefined") {
			if (
				this.inProgressCurrentTab !==
				this.$store.state.salesOrders.currentSoInProgressTab
			) {
				this.inProgressCurrentTab = this.$store.state.salesOrders.currentSoInProgressTab;
				this.$emit(
					"currentTab",
					this.inProgressSubTabs[this.inProgressCurrentTab].value,
					true
				);
			}
		}
	},
	updated() {
		this.currentTab = this.getCurrentSoTab;
		this.currentTabstate = this.tabs[this.currentTab].value;

		this.openCurrentTab = this.getCurrentSoOpenTab;
		this.currentOpenTabstate = this.openSubTabs[this.openCurrentTab].value;

		this.allCurrentTab = this.getCurrentSoAllTab;

		this.bookingCurrentTab = this.getCurrentSoBookingTab;

		this.inProgressCurrentTab = this.getCurrentSoInProgressTab;
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/salesOrders/salesOrdersTable.scss";
@import "@/assets/scss/tables.scss";
</style>
