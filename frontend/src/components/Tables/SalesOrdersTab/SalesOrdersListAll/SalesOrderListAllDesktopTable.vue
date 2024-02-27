<!-- @format -->

<template>
	<div class="po-table-wrapper">
		<v-data-table
			:headers="headers"
			:items="posAll"
			:items-per-page="getItemPerPage()"
			@page-count="pageCount = $event"
			mobile-breakpoint="769"
			item-key="po_number"
			class="pos-table elevation-1"
			v-bind:class="{
				'no-data-table':
					typeof posAll !== 'undefined' &&
					posAll.length === 0 &&
					this.search === '',
				'no-current-pagination': posAll.length !== 0 && getTotalPage <= 1,
				'no-searched-data': getSearchedDataClass,
			}"
			hide-default-footer
			fixed-header
			v-model="selected"
			@click:row="viewPo"
			show-select
		>
			<!-- :class="posAll !== null && posAll.length > 0 ? '' : 'no-data-table'" -->
			<template v-slot:[`item.order_number`]="{ item }">
				<div class="inventory-wrapper">
					<div>{{ item.po_number }}</div>
				</div>
			</template>

			<template v-slot:[`item.created_at`]="{ item }">
				<div class="inventory-wrapper">
					<div class="noSpace">{{ formatDate(item.created_at) }}</div>
					<div class="order_type">
						{{ item.created_by_name !== null ? item.created_by_name : "--" }}
					</div>
				</div>
			</template>

			<template v-slot:[`item.updated_at_by`]="{ item }">
				<div class="inventory-wrapper">
					<div class="noSpace">
						{{ formatDate(item.updated_at, "date_time") }}
					</div>
					<div class="order_type">
						{{ item.updated_by_name !== null ? item.updated_by_name : "--" }}
					</div>
				</div>
			</template>

			<template v-slot:[`item.updated_at`]="{ item }">
				<div class="inventory-wrapper">
					<div class="noSpace">
						{{ formatDate(item.updated_at, "date_time") }}
					</div>
				</div>
			</template>

			<template v-slot:[`item.buyer`]="{ item }">
				<div class="inventory-wrapper">
					<div>
						{{
							item.buyer_company_name
								? item.buyer_company_name
								: item.customer_company_name
						}}
					</div>
				</div>
			</template>

			<template v-slot:[`item.ship_to`]="{ item }">
				<div class="inventory-wrapper">
					<div>
						{{
							item.ship_to !== "" && item.ship_to !== null ? item.ship_to : "--"
						}}
					</div>
				</div>
			</template>

			<template v-slot:[`item.buyer_ship_to`]="{ item }">
				<div>
					{{
						item.buyer_company_name
							? item.buyer_company_name
							: item.customer_company_name
					}}
				</div>
				<div class="inventory-wrapper">
					<div>
						{{
							item.ship_to !== "" && item.ship_to !== null ? item.ship_to : "--"
						}}
					</div>
				</div>
			</template>

			<template v-slot:[`item.carton_cbm`]="{ item }">
				<div :class="item.id">
					{{ item.total_quantity !== null ? item.total_quantity : 0 }} Cartons
				</div>
				<div class="inventory-wrapper">
					<div>{{ getTotalCBM(item.purchase_order_products) }} CBM</div>
				</div>
			</template>

			<template v-slot:[`item.cargo_ready_date`]="{ item }">
				<span class="noSpace">{{ formatDate(item.cargo_ready_date) }}</span>
			</template>

			<template v-slot:[`item.details`]="{ item }">
				<div>
					<span>${{ getTotalPrice(item) }}</span> <br />
					<span class="units" style="color: #6D858F;">
						{{ item.total_units !== null ? item.total_units : 0 }} Units
					</span>
				</div>
			</template>

			<template v-slot:[`item.status`]="{ item }">
				<div class="orderState">
					<div
						v-if="
							isMarkAsPaidStatus(item) &&
								($store.state.po.currentPoOpenTab == 1 ||
									$store.state.po.currentPoOpenTab == 2)
						"
					>
						<v-chip
							v-if="
								item.mark_as_paid.status == '4' &&
									$store.state.salesOrders.currentSoOpenTab == 2
							"
							:class="'cancelled'"
							>Payment Rejected
						</v-chip>
						<v-chip
							v-if="
								['1', '2'].includes(item.mark_as_paid.status) &&
									$store.state.salesOrders.currentSoOpenTab == 1
							"
							:class="'pending_approval'"
							>Payment Received
						</v-chip>
					</div>
					<div v-else>
						<v-chip
							v-if="!item.is_issuer && item.state !== 'in_progress'"
							:class="item.state"
						>
							{{
								item.state == "pending_approval"
									? "Received"
									: item.state == "performa_request"
									? "Proforma Sent"
									: stateStatus[item.state]
							}}
						</v-chip>
						<v-chip
							v-if="item.is_issuer && item.state !== 'in_progress'"
							:class="item.state"
						>
							{{ item.state !== null ? stateStatus[item.state] : "" }}
						</v-chip>
						<v-chip
							v-if="item.state == 'in_progress'"
							:class="productionStatusClass(item.production_overall_status)"
						>
							{{
								item.production_status_name !== null
									? item.production_status_name
									: ""
							}}
						</v-chip>
					</div>
				</div>
			</template>

			<template v-slot:[`item.production_status`]="{ item }">
				<div class="orderState">
					<v-chip
						:class="
							item.production_status_name !== null
								? productionStatusClass(item.production_overall_status)
								: item.state
						"
					>
						{{
							item.production_status_name !== null
								? item.production_status_name
								: stateStatus[item.state]
						}}
					</v-chip>
				</div>
			</template>

			<template v-slot:[`item.ref_no`]="{ item }">
				<span class="noSpace" :class="item.id">{{ item.shifl_ref }}</span>
			</template>

			<template v-slot:[`item.forwarder`]="{ item }">
				<span class="noSpace" :class="item.id">Shifl</span>
			</template>

			<template v-slot:[`item.mode_volume`]="{ item }">
				<div class="noSpace d-flex" :class="item.id">
					<img
						v-if="
							item.ship_via == 'Truck' && item.totalShippedCartonValue == 'Full'
						"
						src="@/assets/images/big-container-po.svg"
						width="20px"
					/>
					<img
						v-if="
							item.ship_via == 'Truck' && item.totalShippedCartonValue != 'Full'
						"
						src="@/assets/images/small-container-po.svg"
						width="20px"
					/>
					<img
						v-if="item.ship_via == 'Air'"
						src="@/assets/images/airplane.svg"
						width="20px"
					/>
					<img
						v-if="item.ship_via == 'Ocean'"
						src="@/assets/images/ship.svg"
						width="20px"
					/>
					<span class="noSpace ml-2" :class="item.id">
						{{ calculateVolume(item) }} CBM
					</span>
				</div>
			</template>

			<template v-slot:[`item.cargo`]="{ item }">
				<div :class="item.id">
					{{ item.total_quantity !== null ? item.total_quantity : 0 }} Cartons
				</div>
				<p class="mb-0">{{ item.totalShippedCartonValue }}</p>
			</template>
			<template v-slot:[`item.progress`]="{ item }">
				<div :class="item.id">
					{{ item.total_quantity !== null ? item.total_quantity : 0 }} Cartons
				</div>
				<p class="mb-0">{{ item.totalShippedCartonValue }}</p>
			</template>

			<template v-slot:[`item.estimated_crd`]="{ item }">
				<span class="noSpace" :class="item.id">{{
					formatDate(item.committed_cargo_ready_date)
				}}</span>
			</template>

			<template v-slot:[`item.deliver_date`]="{ item }">
				<span class="noSpace"
					>{{ item.deliver_date ? item.deliver_date : "N/A" }}
				</span>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="actions">
					<v-btn
						v-if="currentTab == 'draft' && openCurrentTab == 'draft'"
						dark
						color="primary"
						class="btn-white edit-btn mr-2"
						@click.stop="editPo(item)"
					>
						edit
					</v-btn>

					<v-menu bottom offset-y left content-class="po-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn color="btn-more elevation-0" v-bind="attrs" v-on="on">
								<img src="@/assets/icons/dots.svg" />
							</v-btn>
						</template>

						<v-list class="po-lists">
							<v-list-item
								v-if="item.state == 'draft'"
								@click="orderStateUpdate(item, 'pending_approval')"
							>
								<v-list-item-title class="open-state-action pt-2 pb-3">
									<!-- <div class="title-img">
										<img
											class="mr-2"
											src="@/assets/icons/arrow-right.svg"
											width="16px"
											height="16px"
										/>
									</div> -->

									<span class="text-blue">Submit SO</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								v-if="
									item.mark_as_paid &&
										(item.mark_as_paid.status == 1 ||
											item.mark_as_paid.status == 2)
								"
								@click="reviewPayment(item)"
							>
								<v-list-item-title class="open-state-action pt-2 pb-3">
									<div class="title-img">
										<img
											class="mr-2"
											src="@/assets/icons/invoice-icon.svg"
											width="16px"
											height="16px"
										/>
									</div>

									<span class="text-blue">Review Payment </span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								v-if="item.can_action && !cancelCrButton(item)"
								@click="reviewOrder(item)"
							>
								<v-list-item-title class="pt-2 pb-2">
									<div class="title-img">
										<img
											class="mr-2"
											src="@/assets/icons/checkMark-green.svg"
											width="16px"
											height="16px"
										/>
									</div>

									<span class="text-green">Accept</span>
								</v-list-item-title>
							</v-list-item>
							<v-list-item
								v-if="item.can_action && !cancelCrButton(item)"
								@click="orderRejectConfirmationCall(item)"
							>
								<v-list-item-title class="open-state-action pt-2 pb-3">
									<div class="title-img">
										<img
											class="mr-2"
											src="@/assets/icons/close-red.svg"
											width="16px"
											height="16px"
										/>
									</div>

									<span class="text-red">Reject</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								v-if="
									!item.connected_customer && item.state == 'pending_approval'
								"
								@click="orderStateUpdate(item, 'accept')"
							>
								<v-list-item-title class="py-2">
									<span class="text-green">Mark Accepted</span>
								</v-list-item-title>
							</v-list-item>
							<v-list-item
								v-if="
									!item.connected_customer && item.state == 'pending_approval'
								"
								@click="orderStateUpdate(item, 'reject')"
							>
								<v-list-item-title class="py-2">
									<span class="text-red">Mark Rejected</span>
								</v-list-item-title>
							</v-list-item>
							<v-list-item
								v-if="
									!item.connected_customer && item.state == 'pending_approval'
								"
								@click="cancelChangeRequestDialog(item)"
							>
								<v-list-item-title class="open-state-action pt-2 pb-2">
									<span class="text-black">Cancel Change Request</span>
								</v-list-item-title>
							</v-list-item>

							<!-- <v-list-item v-if="item.state == 'approved'">
								<v-list-item-title class="open-state-action pt-2 pb-3">
									<span class="text-green">Mark In Production</span>
								</v-list-item-title>
							</v-list-item> -->

							<v-list-item @click="viewPo(item)">
								<v-list-item-title>
									<span>View</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								@click="editPo(item)"
								v-if="
									item.status !== 'shipped' &&
										item.is_issuer &&
										item.state !== 'in_progress'
								"
							>
								<v-list-item-title>
									<span>Edit</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								v-if="
									item.status !== 'shipped' &&
										(item.state == 'approved' || item.state == 'in_progress')
								"
							>
								<v-list-item-title>
									<span :class="item.state == 'in_progress' ? 'text-blue' : ''"
										>Request Booking</span
									>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								v-if="
									item.status !== 'shipped' &&
										(item.state == 'approved' || item.state == 'in_progress')
								"
							>
								<v-list-item-title>
									<span>Add to New Shipment</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								v-if="
									item.status !== 'shipped' &&
										(item.state == 'approved' || item.state == 'in_progress')
								"
							>
								<v-list-item-title>
									<span>Add to Existing Shipment</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								@click="reviewOrder(item, true)"
								v-if="
									item.status !== 'shipped' &&
										item.can_action &&
										!cancelCrButton(item)
								"
							>
								<v-list-item-title>
									<span>Request Change</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								v-if="cancelCrButton(item)"
								@click="cancelChangeRequestDialog(item)"
							>
								<v-list-item-title>
									<span>Cancel Request</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item @click="download(item)">
								<v-list-item-title>
									<span>Download</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								@click="deletePo(item)"
								v-if="
									item.status === 'pending' &&
										item.state == 'draft' &&
										item.is_issuer
								"
							>
								<v-list-item-title>
									<span>Delete</span>
								</v-list-item-title>
							</v-list-item>

							<v-list-item
								@click="orderStateUpdate(item, 'cancelled')"
								v-if="
									item.status === 'pending' &&
										item.state != 'draft' &&
										openCurrentTab != 'received' &&
										item.is_issuer
								"
							>
								<v-list-item-title>
									<span class="text-red">Cancel SO</span>
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

				<div
					class="no-data-wrapper"
					v-if="!getAllPosLoading && posAll.length == 0"
				>
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
							Sorry! We could not find any purchase order that matches your
							search term.
						</p>

						<!-- <div class="mt-4">
                            <v-btn dark color="primary" class="btn-white" @click="clearSearchData">
                                Try different search
                            </v-btn>
                        </div> -->
					</div>
				</div>
			</template>
		</v-data-table>

		<Pagination
			v-if="
				typeof posAll !== 'undefined' && posAll.length > 0 && getTotalPage > 1
			"
			:pageData.sync="getCurrentPage"
			:lengthData.sync="getTotalPage"
			:isMobile="isMobile"
			@next-page="nextPage"
			@previous-page="prevPage"
			@goto-page="goToPage"
		/>

		<PaymentReviewDialog
			:dialog.sync="reviewPaymentDialog"
			@close="closeReviewPaymentDialog"
			:isMobile="isMobile"
			:orderList="orderList"
			requestForm="list"
		/>

		<ConfirmDialog :dialogData.sync="dialogCancelChangeRequest">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Cancel the change request?</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Do you want to cancel the change request? To proceed, please select
					one of the below options:
				</p>
				<v-radio-group
					v-model="cancelRequest"
					column
					hide-details
					class="po-so-order-details-cancel-radio"
				>
					<v-radio
						label="Cancel request & accept vendor’s terms"
						value="accept"
					></v-radio>
					<v-radio
						label="Cancel request & reject the orders"
						value="reject"
					></v-radio>
				</v-radio-group>
			</template>

			<template v-slot:dialog_actions>
				<v-btn
					class="btn-blue"
					text
					@click="confirmCancelRequest(cancelItem)"
					:disabled="getCancelChangeRequestLoading"
				>
					<span>Confirm</span>
				</v-btn>
				<v-btn
					class="btn-white"
					text
					@click="closeCancelChangeRequest"
					:disabled="getCancelChangeRequestLoading"
				>
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

		<ConfirmDialog :dialogData.sync="orderRejectConfirmationFlag">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2 v-if="rejectItem.cr_by != null">Reject Change Request</h2>
				<h2 v-else>Reject Order</h2>
			</template>

			<template v-slot:dialog_content>
				<p v-if="rejectItem.cr_by != null">
					Do you want to reject the changes requested? Once rejected, this order
					will be cancelled.
				</p>
				<p v-else>
					Do you want to reject the selected orders? Once rejected, this cannot
					be undone.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn
					class="btn-blue"
					@click="orderStateUpdate(rejectItem, 'reject')"
					text
					:disabled="getOrdersStateLoading"
				>
					<span v-if="rejectItem.cr_by != null">Reject Changes</span>
					<span v-else>Reject</span>
				</v-btn>
				<v-btn
					class="btn-white"
					text
					@click="orderRejectConfirmationDialogClose"
					:disabled="getOrdersStateLoading"
				>
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Pagination from "@/components/Pagination.vue";
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";
import _ from "lodash";
import moment from "moment";
import globalMethods from "@/utils/globalMethods";
import { STATES, CHANGE_REQUEST, APPROVED } from "../../../../constants/states";
import PaymentReviewDialog from "@/components/SalesOrdersComponenets/Dialog/PaymentReviewDialog.vue";

export default {
	name: "SalesOrderListAllDesktopTable",
	props: [
		"items",
		"currentWarehouseSelected",
		"isMobile",
		"search",
		"selectedPO",
		"headers",
		"bookingCurrentTab",
		"currentTab",
		"openCurrentTab",
	],
	components: {
		Pagination,
		ConfirmDialog,
		PaymentReviewDialog,
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		dialogDelete: false,
		dialogDeleteWarehouse: false,
		stateStatus: STATES,
		orderRejectConfirmationFlag: false,
		rejectItem: [],
		dialogCancelChangeRequest: false,
		cancelRequest: "accept",
		cancelItem: [],
		reviewPaymentDialog: false,
		orderList: null,
	}),
	computed: {
		...mapGetters({
			getAllPoPage: "salesOrders/getAllPoPage",
			getPendingPage: "salesOrders/getPendingPage",
			getCategories: "category/getCategories",
			getVendorLists: "salesOrders/getVendorLists",
			getPendingPOPagination: "salesOrders/getPendingPOPagination",
			getWarehouse: "warehouse/getWarehouse",
			getPoPendingLoading: "salesOrders/getPoPendingLoading",
			getSoLocalQuery: "salesOrders/getSoLocalQuery",
			getAllPosLoading: "salesOrders/getAllPosLoading",
			getAllPosPagination: "salesOrders/getAllPosPagination",
			getUser: "getUser",
			getOrdersStateLoading: "salesOrders/getOrdersStateLoading",
			getCancelChangeRequestLoading:
				"salesOrders/getCancelChangeRequestLoading",
		}),
		posAll: {
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
					this.getAllPosPagination.total / this.getAllPosPagination.per_page
				);
			},
		},
		getCurrentPage: {
			get() {
				return this.getAllPoPage;
				// return this.getPendingPOPagination.current_page
			},
			set() {
				return {};
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
		getSearchedDataClass() {
			if (this.posAll.length == 0 && this.search !== "") {
				return true;
			} else {
				return false;
			}
		},
	},
	methods: {
		...mapActions({
			downloadPo: "salesOrders/downloadPo",
			fetchPo: "salesOrders/fetchPo",
			fetchPoPending: "salesOrders/fetchPoPending",
			fetchPoPendingNew: "salesOrders/fetchPoPendingNew",
			setPendingPage: "salesOrders/setPendingPage",
			setSoLocalQuery: "salesOrders/setSoLocalQuery",
			fetchPoAllNew: "salesOrders/fetchPoAllNew",
			setAllPage: "salesOrders/setAllPage",
			setCurrentSOTab: "salesOrders/setCurrentSOTab",
			updateState: "salesOrders/updateState",
			updatePo: "salesOrders/updatePo",
			setSoCurrentOpenTab: "salesOrders/setSoCurrentOpenTab",
			setSoCurrentAllTab: "salesOrders/setSoCurrentAllTab",
			cancelChangeRequest: "salesOrders/cancelChangeRequest",
			setPoHistoryEmpty: "poDetails/setPoHistoryEmpty",
			setSoCurrentInProgressTab: "salesOrders/setSoCurrentInProgressTab",
			fetchOrderCounter: "salesOrders/fetchOrderCounter",
		}),
		...globalMethods,
		formatDate(date, date_type) {
			if (date !== null) {
				if (date_type == "date_time") {
					return moment(date).format(" h:mm A, MMM DD, YYYY");
				} else {
					return moment(date).format("MMM DD, YYYY");
				}
			} else {
				return "N/A";
			}
		},
		getItemPerPage() {
			return this.getAllPosPagination.per_page;
		},
		pathDataName() {
			return typeof this.$router.history.current.query.tab !== "undefined"
				? this.$router.history.current.query.tab
				: null;
		},
		checkStoreKey(pageNumber) {
			let poObject = this.$store.state.salesOrders.allSalesOrdersData;
			let pathData = this.pathDataName();
			let key = pathData + "_" + pageNumber;
			let checkPaginate = Object.prototype.hasOwnProperty.call(poObject, key);
			return checkPaginate;
		},
		nextPage() {
			if (this.getAllPosPagination.next_page_url !== null) {
				var queryParams = this.getAllPosPagination.next_page_url
					.split("?")[1]
					.replace(/[^0-9]/g, "");
				this.setAllPage(queryParams);
				try {
					let pathData = this.pathDataName();
					let checkPaginate = this.checkStoreKey(queryParams);

					let passedData = {
						page: queryParams,
						special: 1,
						state: pathData != "all" ? pathData : null,
						checkPaginate: checkPaginate,
					};
					if (this.getSoLocalQuery !== null && this.getSoLocalQuery !== "") {
						passedData["query"] = this.getSoLocalQuery;
					}
					this.fetchPoAllNew(passedData);
					//this.fetchPoPending(queryParams)
				} catch (e) {
					this.notificationError(e);
				}
			}
		},
		goToPage(pageNumber) {
			this.setAllPage(pageNumber);
			try {
				let pathData = this.pathDataName();
				let checkPaginate = this.checkStoreKey(pageNumber);

				let passedData = {
					page: pageNumber,
					special: 1,
					state: pathData != "all" ? pathData : null,
					checkPaginate: checkPaginate,
				};
				if (this.getSoLocalQuery !== null && this.getSoLocalQuery !== "") {
					passedData["query"] = this.getSoLocalQuery;
				}
				this.fetchPoAllNew(passedData);
				//this.fetchPoPending(pageNumber)
			} catch (e) {
				this.notificationError(e);
			}
		},
		prevPage() {
			if (this.getAllPosPagination.prev_page_url !== null) {
				var queryParams = this.getAllPosPagination.prev_page_url
					.split("?")[1]
					.replace(/[^0-9]/g, "");
				this.setAllPage(queryParams);
				try {
					let pathData = this.pathDataName();
					let checkPaginate = this.checkStoreKey(queryParams);

					let passedData = {
						page: queryParams,
						special: 1,
						state: pathData != "all" ? pathData : null,
						checkPaginate: checkPaginate,
					};
					if (this.getSoLocalQuery !== null && this.getSoLocalQuery !== "") {
						passedData["query"] = this.getSoLocalQuery;
					}

					this.fetchPoAllNew(passedData);
					//this.fetchPoPendingNew(queryParams)
					//this.fetchPoPending(queryParams)
				} catch (e) {
					this.notificationError(e);
				}
			}
		},
		createPo() {
			this.$emit("createPo");
		},
		editPo(po) {
			this.$emit("editPo", po);
		},
		reviewOrder(item, change_request) {
			this.$emit("reviewOrder", item, change_request);
		},
		viewPo(item) {
			this.$emit("viewPo", item);
			this.setPoHistoryEmpty([]);
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
		async orderStateUpdate(item, orderState) {
			const payload = {
				poNumber: item.po_number,
				orderAction: orderState,
			};

			await this.updateState(payload);
			this.orderRejectConfirmationFlag = false;

			if (orderState == "pending_approval") {
				this.setCurrentSOTab(1);
				this.setSoCurrentOpenTab(2);

				this.$router.push("?tab=pending_approval");
			} else if (orderState == "accept") {
				this.setCurrentSOTab(2);
				this.setSoCurrentInProgressTab(1);

				this.$router.push("?tab=approved");

				orderState = "approved";
			} else if (orderState == "reject" || orderState == "cancelled") {
				this.setCurrentSOTab(0);
				this.setSoCurrentAllTab(2);
				this.$router.push("?tab=archived");

				orderState = "archived";
			} else if (orderState == "change_request") {
				this.setCurrentSOTab(1);
				this.setSoCurrentOpenTab(2);
				this.$router.push("?tab=pending_approval");

				orderState = "pending_approval";
			}

			this.fetchOrderCounter();
			await this.fetchPoAllNew({
				page: 1,
				state: orderState,
			});
			this.dialogCancelChangeRequest = false;
		},
		cancelCrButton(item) {
			let defaultCustomerId =
				typeof this.getUser.default_customer_id !== "undefined"
					? this.getUser.default_customer_id
					: JSON.parse(this.getUser).default_customer_id;

			if (defaultCustomerId == item.cr_by && item.state == CHANGE_REQUEST) {
				return true;
			} else {
				return false;
			}
		},
		orderRejectConfirmationCall(item) {
			this.orderRejectConfirmationFlag = true;
			this.rejectItem = item;
		},
		orderRejectConfirmationDialogClose() {
			this.orderRejectConfirmationFlag = false;
			this.rejectItem = [];
		},
		cancelChangeRequestDialog(item) {
			this.dialogCancelChangeRequest = true;
			this.cancelItem = item;
		},
		closeCancelChangeRequest() {
			this.dialogCancelChangeRequest = false;
			this.cancelItem = [];
		},
		async confirmCancelRequest(item) {
			if (this.cancelRequest == "reject") {
				this.orderStateUpdate(item, this.cancelRequest);
			} else if (this.cancelRequest == "accept") {
				const payload = {
					id: item.id,
					action: this.cancelRequest,
				};
				await this.cancelChangeRequest(payload);
				this.setCurrentSOTab(2);
				this.setSoCurrentInProgressTab(1);

				this.$router.push("?tab=approved");
				this.fetchOrderCounter();
				await this.fetchPoAllNew({
					page: this.getAllPoPage,
					state: APPROVED,
				});
				this.dialogCancelChangeRequest = false;
			}
		},
		getTotalCBM(item) {
			let totalCbm = 0;
			totalCbm === 0 &&
				item.forEach((element) => {
					totalCbm += parseFloat(element.volume);
				});
			return totalCbm;
		},
		productionStatusClass(status) {
			return status == 1 ||
				status == 2 ||
				status == 3 ||
				status == 4 ||
				status == 5 ||
				status == 6
				? "yellow_badge"
				: status == 7
				? "green_badge"
				: "";
		},
		calculateVolume(itemRaw) {
			var total = 0;
			itemRaw.purchase_order_products.forEach(function(item) {
				total += item.volume ? parseFloat(item.volume) : parseFloat(0.0);
			});
			return this.nNumberDecimalFormat(total, 3);
		},
		reviewPayment(item) {
			this.orderList = item;
			this.reviewPaymentDialog = true;
		},
		closeReviewPaymentDialog() {
			this.orderList = null;
			this.reviewPaymentDialog = false;
		},
		isMarkAsPaidStatus(rowItem) {
			if (
				_.has(rowItem, "mark_as_paid") &&
				_.has(rowItem.mark_as_paid, "status") &&
				rowItem.mark_as_paid !== null &&
				["1", "2", "4"].includes(rowItem.mark_as_paid.status)
			) {
				return true;
			}
			return false;
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
