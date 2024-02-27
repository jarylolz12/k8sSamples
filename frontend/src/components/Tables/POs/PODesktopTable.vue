<!-- @format -->

<template>
	<div class="po-desktop-wrapper">
		<div class="po-desktop-header">
			<h2>Purchase Orders</h2>

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

			<!-- <v-btn
				dark
				color="primary"
				class="btn-white ml-2"
				v-if="selected.length > 0"
				@click="selectCargo"
			>
				Request Booking
			</v-btn> -->

			<v-btn
				dark
				color="primary"
				class="btn-white ml-2"
				@click.stop="downloadMultiple"
				v-if="selected.length > 0"
			>
				<img
					src="@/assets/icons/download-po-blue.svg"
					class="mr-1"
					width="15px"
					height="15px"
				/>
				Download
				<span class="ml-1">({{ selected.length }})</span>
			</v-btn>

			<v-btn
				dark
				color="primary"
				class="btn-white delete ml-2"
				@click.stop="deleteMultiple"
				v-if="selected.length > 0 && currentTabstate !== 'pending_quote'"
			>
				<img
					src="@/assets/icons/delete-po.svg"
					class="mr-1"
					width="15px"
					height="15px"
				/>
				Delete
			</v-btn>
			<v-btn
				dark
				color="primary"
				class="btn-white ml-2"
				@click="$emit('import')"
			>
				Import
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
				+ Create PO
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
								:color="allCurrentTab === index ? '#0171A1' : '#819fb2'"
								class="customBadge ml-1"
								inline
								:content="getAllSubTabCounter(index)"
							>
							</v-badge> -->
						</v-tab>
					</v-tabs>
				</div>

				<div class="open-approval-tab" v-if="currentTabstate === 'draft'">
					<v-tabs
						v-if="!isMobile"
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
						v-if="!isMobile"
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
								:color="bookingCurrentTab === index ? '#0171A1' : '#819fb2'"
								class="customBadge ml-1"
								inline
								:content="getBookingSubTabCounter(index)"
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
					placeholder="Search PO"
					className="search custom-search"
					:inputData.sync="search"
				/>
			</div>

			<PoAllDesktopTable
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
				@selectCargo="selectCargo"
				@addToShipment="addToShipment"
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
import PoAllDesktopTable from "./POTabs/AllTab/PoAllDesktopTable.vue";
import globalMethods from "@/utils/globalMethods";
import ConfirmDialog from "../../Dialog/GlobalDialog/ConfirmDialog.vue";
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

import axios from "axios";
let CancelToken = axios.CancelToken;
let cancel;

export default {
	name: "PODesktopTable",
	props: ["items", "isMobile", "selectedPO", "badges"],
	components: {
		Search,
		PoAllDesktopTable,
		ConfirmDialog,
	},
	data: () => ({
		snackbar: true,
		page: 1,
		pageCount: 0,
		itemsPerPage: 0,
		search: "",
		typingTimeout: 0,
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
				value: null,
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
		po_no: [
			{
				text: "PO No",
				align: "start",
				sortable: false,
				value: "po_number",
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
				text: "Vendor",
				align: "start",
				sortable: false,
				value: "supplier_id",
				fixed: true,
				width: "20%",
				sort_order: 4,
			},
			{
				text: "Ship to",
				align: "start",
				sortable: false,
				value: "ship_to",
				fixed: true,
				width: "18%",
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
				width: "10%",
				sort_order: 7,
			},
		],
		Issued_at_by: [
			{
				text: "Issued At & By",
				align: "start",
				sortable: false,
				value: "created_at",
				fixed: true,
				width: "18%",
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
				width: "18%",
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
		vendorAndShipTo: [
			{
				text: "Vendor & Ship To",
				align: "start",
				sortable: false,
				value: "vendor_ship_to",
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
				text: "Mode & Volume",
				align: "start",
				sortable: false,
				value: "mode_volume",
				fixed: true,
				width: "12%",
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
			getPendingPage: "po/getPendingPage",
			getShippedPage: "po/getShippedPage",
			getCurrentPage: "po/getCurrentPage",
			getAllPo: "po/getAllPo",
			getVendorLists: "po/getVendorLists",
			getWarehouse: "warehouse/getWarehouse",
			getDownloadLoading: "po/getDownloadLoading",
			getPoDeleteLoading: "po/getPoDeleteLoading",
			getAllPoPending: "po/getAllPoPending",
			getAllPoShipped: "po/getAllPoShipped",
			getPoLocalQuery: "po/getPoLocalQuery",
			getAllPos: "po/getAllPos",
			getAllPoPage: "po/getAllPoPage",
			getCurrentPoTab: "po/getCurrentPoTab",
			getCurrentPoOpenTab: "po/getCurrentPoOpenTab",
			getCurrentPoAllTab: "po/getCurrentPoAllTab",
			getCurrentPoBookingTab: "po/getCurrentPoBookingTab",
			getOrderCounterValue: "po/getOrderCounterValue",
			getCurrentPoInProgressTab: "po/getCurrentPoInProgressTab",
			getCsvReportLoading: "po/getCsvReportLoading",
			getSetCsvReportData: "po/getSetCsvReportData",
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
		handleSearchComponent() {
			let isShow = true;

			if (this.currentTab === 0) {
				if (this.search == "" && this.posAll.length === 0) {
					isShow = false;
				} else if (this.search !== "" && this.posAll.length === 0) {
					isShow = true;
				}
			}

			return isShow;
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
						...this.po_no,
						...this.header,
						...this.Issued_at_by,
						...this.status,
						...this.action,
					];
				} else if (ordersState === "In Progress") {
					dynamicHeader = [
						...this.po_no,
						...this.inProgressHeader,
						...this.vendorAndShipTo,
						...this.updated_at_by,
						...this.action,
					];
					if (inProgressOrderState === "Approved") {
						dynamicHeader = [
							...this.po_no,
							...this.header,
							...this.Issued_at_by,
							...this.updated_at_by,
							...this.action,
						];
					}
				} else if (ordersState === "In Transit") {
					dynamicHeader = [
						...this.po_no,
						...this.vendorAndShipTo,
						...this.Issued_at_by,
						...this.bookingHeader,
						...this.cargo,
						...this.inTransitStatus,
						...this.action,
					];
				} else if (ordersState === "Delivered") {
					dynamicHeader = [
						...this.po_no,
						...this.vendorAndShipTo,
						...this.bookingHeader,
						...this.Issued_at_by,
						...this.cargo,
						...this.deliveredDate,
						...this.action,
					];
				} else if (ordersState === "Archived") {
					dynamicHeader = [
						...this.po_no,
						...this.header,
						...this.Issued_at_by,
						...this.status,
						...this.action,
					];
				}
			} else if (this.currentTabstate === "draft") {
				if (openOrderState == "Drafts") {
					dynamicHeader = [
						...this.po_no,
						...this.header,
						...this.updated_at_by,
						...this.action,
					];
				} else if (openOrderState === "Requests Received") {
					dynamicHeader = [
						...this.po_no,
						...this.header,
						...this.updated_at,
						...this.status,
						...this.action,
					];
				} else if (openOrderState === "Pending Approval") {
					dynamicHeader = [
						...this.po_no,
						...this.header,
						...this.updated_at_by,
						...this.status,
						...this.action,
					];
				} else if (openOrderState === "Approved") {
					dynamicHeader = [
						...this.po_no,
						...this.header,
						...this.Issued_at_by,
						...this.updated_at_by,
						...this.action,
					];
				}
			} else if (this.currentTabstate === REQUIRES_BOOKING) {
				if (bookingOrderState == "Pending Quote") {
					dynamicHeader = [
						...this.po_no,
						...this.Issued_at_by,
						...this.vendorAndShipTo,
						...this.bookingHeader,
						...this.est_crd,
						...this.cargo,
						...this.action,
					];
				} else if (bookingOrderState === "Pending Approval") {
					dynamicHeader = [
						...this.po_no,
						...this.Issued_at_by,
						...this.vendorAndShipTo,
						...this.bookingHeader,
						...this.est_crd,
						...this.cargo,
						...this.action,
					];
				} else if (bookingOrderState === "Requires Booking") {
					dynamicHeader = [
						...this.po_no,
						...this.Issued_at_by,
						...this.vendorAndShipTo,
						...this.requireBookingHeader,
						...this.est_crd,
						...this.Progress,
						...this.action,
					];
				} else if (bookingOrderState === "Awaiting Shipment") {
					dynamicHeader = [
						...this.po_no,
						...this.Issued_at_by,
						...this.vendorAndShipTo,
						...this.bookingHeader,
						...this.est_crd,
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
			// deleteMultiple: 'po/deleteMultiple',
			setCurrentPOTab: "po/setCurrentPOTab",
			fetchPoShippedNew: "po/fetchPoShippedNew",
			fetchPoPendingNew: "po/fetchPoPendingNew",
			setPoGlobalQuery: "po/setPoGlobalQuery",
			setPoLocalQuery: "po/setPoLocalQuery",
			setPoPendingLoading: "po/setPoPendingLoading",
			setPoShippedLoading: "po/setPoShippedLoading",
			setPoAllLoading: "po/setPoAllLoading",
			fetchPoAllNew: "po/fetchPoAllNew",
			setAllPage: "po/setAllPage",
			fetchOrderCounter: "po/fetchOrderCounter",
			fetchOrdersReport: "po/fetchOrdersReport",
		}),
		...globalMethods,
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

				let searchPoQuery = this.getPoLocalQuery == "" ? this.getAllPoPage : 1;

				if (
					this.currentTabstate == IN_TRANSIT ||
					this.currentTabstate == DELIVERED
				) {
					let ordersState = this.tabs[this.currentTab].value;
					this.searchCommon(
						searchPoQuery,
						this.getPoLocalQuery,
						1,
						ordersState,
						cancelToken
					);
				}

				if (this.currentTabstate === ALL) {
					let ordersState = this.allSubTabs[this.allCurrentTab].value;
					this.searchCommon(
						searchPoQuery,
						this.getPoLocalQuery,
						1,
						ordersState,
						cancelToken
					);
				}
				if (this.currentTabstate === DRAFT) {
					let ordersState = this.openSubTabs[this.openCurrentTab].value;
					this.searchCommon(
						searchPoQuery,
						this.getPoLocalQuery,
						1,
						ordersState,
						cancelToken
					);
				}
				if (this.currentTabstate === ALL_IN_PROGRESS) {
					let ordersState = this.inProgressSubTabs[this.inProgressCurrentTab]
						.value;
					this.searchCommon(
						searchPoQuery,
						this.getPoLocalQuery,
						1,
						ordersState,
						cancelToken
					);
				}

				if (this.currentTabstate === REQUIRES_BOOKING) {
					let ordersState = this.bookingSubTabs[this.bookingCurrentTab].value;
					this.searchCommon(
						searchPoQuery,
						this.getPoLocalQuery,
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
		selectCargo(item) {
			this.$emit("selectCargo", item);
		},
		addToShipment(item) {
			this.$emit("addToShipment", item);
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
			this.setCurrentPOTab(i);
		},
		onAllTabChange(i) {
			this.setPoLocalQuery("");
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

			this.$store.dispatch("po/setPoCurrentAllTab", this.allCurrentTab);
			this.$store.dispatch("po/clearAllPosData");

			this.$emit("currentTab", this.allSubTabs[this.allCurrentTab].value);
		},
		onOpenTabChange(i) {
			this.setPoLocalQuery("");
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

			this.$store.dispatch("po/setPoCurrentOpenTab", this.openCurrentTab);
			this.$store.dispatch("po/clearAllPosData");

			this.$emit("currentTab", this.openSubTabs[this.openCurrentTab].value);
		},
		onBookingTabChange(i) {
			this.setPoLocalQuery("");
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

			this.$store.dispatch("po/setPoCurrentBookingTab", this.bookingCurrentTab);
			this.$store.dispatch("po/clearAllPosData");

			this.$emit(
				"currentTab",
				this.bookingSubTabs[this.bookingCurrentTab].value
			);
		},
		onInProgressTabChange(i) {
			this.setPoLocalQuery("");
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
				"po/setPoCurrentInProgressTab",
				this.inProgressCurrentTab
			);
			this.$store.dispatch("po/clearAllPosData");

			this.$emit("currentTab", this.currentInProgressTabstate);
		},
		async getCurrentTab(i) {
			this.setPoLocalQuery("");
			this.setAllPage(1);
			this.search = "";
			let beforeTab = this.currentTab;
			this.currentTab = i;
			this.currentTabstate = this.tabs[i].value;
			this.setCurrentPOTab(i);

			let pathData =
				typeof this.$router.history.current.query.tab !== "undefined"
					? this.$router.history.current.query.tab
					: null;

			if (
				this.currentTabstate == ALL &&
				pathData !== null &&
				pathData !== "all"
			) {
				this.$store.dispatch("po/setPoCurrentAllTab", 1);
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
					this.currentTabstate === null
						? ACTIVE
						: this.tabs[this.currentTab].value
				);
			}

			if (this.currentTabstate === "draft" && beforeTab !== this.currentTab) {
				this.$store.dispatch("po/setPoCurrentOpenTab", 0);

				this.fetchOrderCounter();
				this.$emit("currentTab", DRAFT);
			}

			if (
				this.currentTabstate === ALL_IN_PROGRESS &&
				beforeTab !== this.currentTab
			) {
				this.$store.dispatch("po/setPoCurrentInProgressTab", 0);

				this.$emit("currentTab", ALL_IN_PROGRESS);
			}

			if (
				this.currentTabstate === REQUIRES_BOOKING &&
				beforeTab !== this.currentTab
			) {
				this.$store.dispatch("po/setPoCurrentBookingTab", 0);

				this.$emit("currentTab", REQUIRES_BOOKING);
			}
			this.$store.dispatch("po/clearAllPosData");
		},
		clearSelection() {
			this.$emit("clearSelectedPO");
		},
		downloadMultiple() {
			this.notificationCustom(
				"Files are ready. Download will start automatically."
			);
			this.$emit("downloadMultiple");
		},
		deleteMultiple() {
			this.$emit("deleteMultiple");
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
		// requestBooking() {
		// 	this.$emit("requestBooking");
		// },
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

		//clear search and search
		if (this.search !== "") {
			this.search = "";
		}
		if (this.$router.history.current.query.tab == ACTIVE) {
			this.$store.dispatch("po/setCurrentPOTab", 0);
			this.$store.dispatch("po/setPoCurrentAllTab", 1);
		}

		//set tab
		if (this.$store.state.po.currentPoTab !== "undefined") {
			if (this.currentTab !== this.$store.state.po.currentPoTab) {
				this.currentTab = this.$store.state.po.currentPoTab;
				this.currentTabstate = this.tabs[this.currentTab].value;
				this.$emit("currentTab", this.currentTabstate, true);
			}
		}

		if (this.$store.state.po.currentPoAllTab !== "undefined") {
			if (this.allCurrentTab !== this.$store.state.po.currentPoAllTab) {
				this.allCurrentTab = this.$store.state.po.currentPoAllTab;
				this.$emit(
					"currentTab",
					this.allSubTabs[this.allCurrentTab].value,
					true
				);
			}
		}

		if (this.$store.state.po.currentPoOpenTab !== "undefined") {
			if (this.openCurrentTab !== this.$store.state.po.currentPoOpenTab) {
				this.openCurrentTab = this.$store.state.po.currentPoOpenTab;
				this.currentOpenTabstate = this.openSubTabs[this.openCurrentTab].value;
				this.$emit("currentTab", this.currentOpenTabstate, true);
			}
		}

		if (this.$store.state.po.currentPoBookingTab !== "undefined") {
			if (this.bookingCurrentTab !== this.$store.state.po.currentPoBookingTab) {
				this.bookingCurrentTab = this.$store.state.po.currentPoBookingTab;
				this.$emit(
					"currentTab",
					this.bookingSubTabs[this.bookingCurrentTab].value,
					true
				);
			}
		}
		if (this.$store.state.po.currentPoInProgressTab !== "undefined") {
			if (
				this.inProgressCurrentTab !==
				this.$store.state.po.currentPoInProgressTab
			) {
				this.inProgressCurrentTab = this.$store.state.po.currentPoInProgressTab;
				this.$emit(
					"currentTab",
					this.inProgressSubTabs[this.inProgressCurrentTab].value,
					true
				);
			}
		}
	},
	updated() {
		this.currentTab = this.getCurrentPoTab;
		this.currentTabstate = this.tabs[this.currentTab].value;

		this.openCurrentTab = this.getCurrentPoOpenTab;
		this.currentOpenTabstate = this.openSubTabs[this.openCurrentTab].value;

		this.allCurrentTab = this.getCurrentPoAllTab;

		this.bookingCurrentTab = this.getCurrentPoBookingTab;

		this.inProgressCurrentTab = this.getCurrentPoInProgressTab;
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/po/poTable.scss";
@import "@/assets/scss/tables.scss";
</style>
