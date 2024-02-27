<!-- @format -->

<template>
	<div class="po-details-wrapper">
		<div class="preloader" v-if="getPoDetailLoading">
			<v-progress-circular :size="40" color="#0171a1" indeterminate>
			</v-progress-circular>
		</div>

		<div class="po-details-wrapper-content" v-if="!getPoDetailLoading">
			<div class="po-detail-headers">
				<div class="details-breadcrumbs">
					<router-link to="/sales-orders?tab=all" class="po-link">
						Sales Orders
					</router-link>
					<span class="right-chevron">
						<img src="../assets/images/right_chevron.svg" />
					</span>

					<span class="po-ref">{{ getPoDetail.po_number }}</span>
				</div>

				<div id="top-header" v-resize="onResize">
					<div class="reference-status">
						<div class="d-flex align-center">
							<h2>Order #{{ getPoDetail.po_number }}</h2>

							<span
								class="btn poDetail-status ml-2"
								:class="getPoDetail.state"
								v-if="
									getPoDetail.supplier_id !== null &&
										getPoDetail.state !== 'in_progress'
								"
							>
								{{
									getPoDetail.state == "pending_approval"
										? "Received"
										: getPoDetail.state == "performa_request" &&
										  !getPoDetail.is_issuer
										? "Proforma Sent"
										: stateStatus[getPoDetail.state]
								}}
							</span>
							<span
								class="btn poDetail-status ml-2"
								:class="getPoDetail.state"
								v-if="
									getPoDetail.supplier_id == null &&
										getPoDetail.state !== 'in_progress'
								"
							>
								{{
									getPoDetail.state !== null
										? stateStatus[getPoDetail.state]
										: ""
								}}
							</span>
							<span
								class="btn poDetail-status ml-2"
								:class="
									productionStatusClass(getPoDetail.production_overall_status)
								"
								v-if="getPoDetail.state == 'in_progress'"
							>
								{{
									getPoDetail.production_status_name !== null
										? getPoDetail.production_status_name
										: ""
								}}
							</span>
							<span
								class="btn poDetail-status booking-status ml-2"
								v-if="
									getPoDetail.state == 'in_progress' &&
										getPoDetail.status !== 'pending'
								"
							>
								{{
									getPoDetail.status == "shipped"
										? "Booked"
										: getPoDetail.status == "partial_shipped"
										? "Partially Booked"
										: ""
								}}
							</span>

							<v-menu
								offset-y
								right
								class="mark-as-paid-status-menu"
								v-if="
									getPoDetail.mark_as_paid &&
										['3', '4'].includes(getPoDetail.mark_as_paid.status)
								"
							>
								<template v-slot:activator="{ on, attrs }">
									<v-btn
										class="ml-2 mark-as-paid-status-btn"
										v-bind="attrs"
										v-on="on"
									>
										<div
											class="payment-accept"
											v-if="getPoDetail.mark_as_paid.status == 3"
										>
											<img src="@/assets/icons/checkMark-green.svg" />
											<span class="text-capitalize">Payment Accepted</span>
										</div>

										<div
											class="payment-rejected"
											v-if="getPoDetail.mark_as_paid.status == 4"
										>
											<img src="@/assets/icons/close-red.svg" />
											<span class="text-capitalize">Payment Rejected</span>
										</div>
									</v-btn>
								</template>

								<v-list class="review-mark-as-paid-details">
									<div v-if="getPoDetail.mark_as_paid.status == 4">
										<v-list-item>
											<span class="text-red">Payment Rejected</span>
										</v-list-item>
										<p
											class="mb-3 text-document"
											v-if="getPoDetail.mark_as_paid.reject_note"
										>
											{{ getPoDetail.mark_as_paid.reject_note }}
										</p>
										<p class="sub-title">
											Reviewed at
											{{
												getDateFormat(
													getPoDetail.mark_as_paid.updated_at,
													"time_date_formate"
												)
											}}
										</p>
									</div>

									<p
										class="sub-title"
										v-if="getPoDetail.mark_as_paid.status == 3"
									>
										Payment accepted at
										{{
											getDateFormat(
												getPoDetail.mark_as_paid.updated_at,
												"time_date_formate"
											)
										}}
										<span
											class="mb-3 text-document d-block black--text"
											v-if="getPoDetail.mark_as_paid.note"
										>
											{{ getPoDetail.mark_as_paid.note }}
										</span>
									</p>

									<v-divider class="mark-as-paid-v-list-devider"></v-divider>
									<v-list-item class="re-upload" @click="viewProof()">
										<span>View Proof</span>
									</v-list-item>
								</v-list>
							</v-menu>
						</div>

						<div class="place-wrapper mt-2" v-if="isMobile">
							<div class="place-content">
								<p class="heading">
									<span class="info-title">ISSUED AT: </span
									>{{ getDateFormat(getPoDetail.created_at) }}
								</p>

								<div class="carrier-wrapper">
									<p class="heading">
										<span class="info-title">UPDATED AT: </span
										>{{ getDateFormat(getPoDetail.updated_at) }}
									</p>
								</div>
							</div>
						</div>

						<div class="header-buttons">
							<div
								class="d-flex"
								v-if="
									!getPoDetail.connected_customer &&
										getPoDetail.state == 'pending_approval'
								"
							>
								<button
									:disabled="getOrdersStateLoading"
									text
									class="btn-green"
									@click="orderStateUpdate('accept')"
									v-if="getPoDetail.status !== 'shipped'"
								>
									Mark Accepted
								</button>

								<button
									:disabled="getOrdersStateLoading"
									class="btn-white button-red"
									color="red"
									@click="orderStateUpdate('reject')"
									v-if="getPoDetail.status !== 'shipped'"
								>
									Mark Rejected
								</button>
							</div>

							<div
								class="d-flex"
								v-if="
									(getPoDetail.state == 'approved' ||
										getPoDetail.state == 'in_progress') &&
										getPoDetail.production_status_name !== 'Completed'
								"
							>
								<button
									:disabled="getOrdersStateLoading"
									class="btn-green"
									@click="updateStatus(getPoDetail)"
									v-if="
										getPoDetail.status !== 'shipped' &&
											!getPoDetail.connected_customer
									"
								>
									Mark In Production
								</button>

								<button
									:disabled="getOrdersStateLoading"
									@click="updateStatus(getPoDetail)"
									class="btn-blue"
									v-if="
										getPoDetail.status !== 'shipped' &&
											getPoDetail.connected_customer
									"
								>
									Update Status
								</button>

								<button
									:disabled="getOrdersStateLoading"
									class="btn-white"
									color="blue"
									v-if="getPoDetail.status !== 'shipped'"
								>
									Request Booking
								</button>
							</div>

							<div
								class="d-flex"
								v-if="getPoDetail.can_action && !cancelCrButton"
							>
								<button
									class="btn-green"
									@click="reviewOrder()"
									v-if="getPoDetail.status !== 'shipped'"
								>
									<span v-if="!isMobile">Accept</span>
									<span v-if="isMobile">
										<img
											src="@/assets/icons/check-white.svg"
											width="16px"
											height="16px"
											class="mr-1"
										/>
									</span>
								</button>
								<button
									class="btn-white button-red"
									:disabled="getOrdersStateLoading"
									@click="orderRejectConfirmationCall()"
									color="red"
									v-if="getPoDetail.status !== 'shipped'"
								>
									<span v-if="!isMobile">Reject</span>
									<span v-if="isMobile">
										<img
											src="@/assets/icons/close-red.svg"
											width="16px"
											height="16px"
											class="mr-1"
										/>
									</span>
								</button>
								<button
									class="btn-white"
									color="blue"
									@click="reviewOrder(true)"
									v-if="getPoDetail.status !== 'shipped'"
								>
									<img
										src="@/assets/icons/edit-blue.svg"
										width="16px"
										height="16px"
										class="mr-1"
									/>
									Request Change
								</button>
							</div>

							<div v-if="cancelCrButton">
								<button
									class="btn-white"
									v-if="getPoDetail.status !== 'shipped'"
									@click="cancelChangeRequestDialog()"
								>
									<span class="black--text">Cancel Request</span>
								</button>
							</div>

							<div
								v-if="
									getPoDetail.mark_as_paid &&
										getPoDetail.mark_as_paid.status != 3 &&
										getPoDetail.mark_as_paid.status != 4
								"
							>
								<button class="btn-white" @click="reviewPayment()">
									<span>Review Payment</span>
								</button>
							</div>

							<button
								class="btn-blue"
								@click="checkEditPo(getPoDetail)"
								v-if="
									getPoDetail.status !== 'shipped' &&
										getPoDetail.is_issuer &&
										!getPoDetail.can_action &&
										getPoDetail.state !== 'in_progress'
								"
							>
								<img
									src="@/assets/icons/edit-white.svg"
									width="16px"
									height="16px"
									class="mr-1"
								/>
								Edit
							</button>

							<!-- <button class="btn-white email" @click="emailPo(getPoDetail)">
								<img src="@/assets/icons/email-blue.svg" width="16px" height="16px" class="mr-1"/> Email
							</button> -->

							<button
								class="btn-white"
								@click="download(getPoDetail)"
								:disabled="getDownloadLoading"
								v-if="getPoDetail.state == 'pending_approval'"
							>
								<img
									src="@/assets/icons/download.svg"
									width="14px"
									height="14px"
								/>
							</button>

							<v-menu
								offset-y
								left
								content-class="po-details-more"
								v-if="getPoDetail.status === 'pending'"
							>
								<template v-slot:activator="{ on, attrs }">
									<v-btn class="btn-white dots" v-bind="attrs" v-on="on">
										<img src="@/assets/icons/dots.svg" />
									</v-btn>
								</template>

								<v-list class="po-details-lists">
									<v-list-item
										@click="checkEditPo(getPoDetail)"
										v-if="getPoDetail.is_issuer && !getPoDetail.can_action"
									>
										<v-list-item-title>
											<img
												src="@/assets/icons/edit-po.svg"
												width="14px"
												height="14px"
												class="mr-1"
											/>
											Edit SO
										</v-list-item-title>
									</v-list-item>
									<v-list-item @click="download(getPoDetail)">
										<v-list-item-title>
											<img
												src="@/assets/icons/download-po.svg"
												width="14px"
												height="14px"
												class="mr-1"
											/>
											Download
										</v-list-item-title>
									</v-list-item>

									<v-list-item
										@click="orderStateUpdate('cancelled')"
										v-if="getPoDetail.is_issuer"
									>
										<v-list-item-title>
											<img src="@/assets/icons/deleteIcon.svg" />
											Cancel SO
										</v-list-item-title>
									</v-list-item>
								</v-list>
							</v-menu>
						</div>
					</div>

					<div class="place-wrapper" v-if="!isMobile">
						<div class="place-content">
							<p class="heading">
								<span class="info-title">ISSUED AT: </span
								>{{ getDateFormat(getPoDetail.created_at) }}
							</p>

							<div class="carrier-wrapper">
								<p class="heading">
									<span class="info-title">Updated By & At </span>
									{{
										getPoDetail.updated_by_name !== null
											? getPoDetail.updated_by_name
											: "--"
									}}
									at
									{{ getDateFormat(getPoDetail.updated_at, "date_time") }}
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="po-detail-body-contents">
				<div class="poDetail-top-content">
					<v-row>
						<v-col cols="12" sm="4">
							<div class="po-info po-vendor mb-3">
								<p class="po-title">
									Buyer
									<span
										class="is-user"
										v-if="
											!getPoDetail.is_issuer && getPoDetail.order_type == 'PO'
										"
										>Issuer</span
									>
								</p>
								<p class="po-data">
									{{
										getPoDetail.buyer_id !== null
											? getBuyer(getPoDetail.buyer_id)
											: getPoDetail.customer_company_name
									}}
								</p>
							</div>

							<div class="po-info po-buyer">
								<p class="po-title">
									Vendor
									<span
										class="is-user"
										v-if="
											getPoDetail.is_issuer && getPoDetail.order_type == 'SO'
										"
										>Issuer</span
									>
								</p>
								<p class="po-data">
									{{
										getPoDetail.buyer_id !== null
											? getUserId(getPoDetail.customer_id)
											: getPoDetail.supplier_company_name
									}}
								</p>
							</div>
						</v-col>

						<v-col cols="12" sm="4">
							<div class="po-info po-ship-to mb-3">
								<p class="po-title">Import Name</p>
								<p class="po-data">
									{{
										getPoDetail.import_name ? getPoDetail.import_name : "N/A"
									}}
								</p>
							</div>
							<div class="po-info po-ship-to mb-3">
								<p class="po-title">Ship To</p>

								<div
									v-if="!checkFeildExists('ship_to', getPoDetail.change_log)"
								>
									<p class="po-data">
										{{
											getPoDetail.ship_to !== "" ? getPoDetail.ship_to : "N/A"
										}}
									</p>
								</div>
								<div
									v-for="(changedData, i) in checkChangeLog('ship_to')"
									:key="i"
								>
									<div>
										<p class="mb-0 item-danger">
											{{ changedData.new_value }}
											<span class="mb-0 item-cancel">{{
												changedData.old_value
											}}</span>
										</p>
									</div>
								</div>
							</div>
						</v-col>

						<v-col cols="12" sm="4">
							<div class="po-info po-ship-via dFlex mb-1">
								<p class="po-title">Ship Via</p>
								<div
									class="po-data-wrapper"
									v-if="!checkFeildExists('ship_via', getPoDetail.change_log)"
								>
									<p class="po-data">
										{{
											getPoDetail.ship_via !== null
												? getPoDetail.ship_via
												: "N/A"
										}}
									</p>
								</div>
								<div
									class="po-data-wrapper"
									v-for="(changedData, i) in checkChangeLog('ship_via')"
									:key="i"
								>
									<div>
										<p class="mb-0 item-danger">
											{{ changedData.new_value }}
											<span class="mb-0 item-cancel">{{
												changedData.old_value
											}}</span>
										</p>
									</div>
								</div>
							</div>

							<div class="po-info po-method dFlex mb-1">
								<p class="po-title">Incoterm</p>

								<div
									class="po-data-wrapper"
									v-if="!checkFeildExists('terms', getPoDetail.change_log)"
								>
									<p class="po-data">
										{{ getPoDetail.terms !== null ? getPoDetail.terms : "N/A" }}
									</p>
								</div>
								<div
									class="po-data-wrapper"
									v-for="(changedData, i) in checkChangeLog('terms')"
									:key="i"
								>
									<div>
										<p class="mb-0 item-danger">
											{{ changedData.new_value }}
											<span class="mb-0 item-cancel">{{
												changedData.old_value
											}}</span>
										</p>
									</div>
								</div>
							</div>

							<div class="po-info po-method dFlex mb-1">
								<p class="po-title">Payment Terms</p>
								<div
									class="po-data-wrapper"
									v-if="
										!checkFeildExists('payment_term', getPoDetail.change_log)
									"
								>
									<p class="po-data">
										{{
											getPoDetail.payment_term !== null
												? getPoDetail.payment_term
												: "N/A"
										}}
									</p>
								</div>
								<div
									class="po-data-wrapper"
									v-for="(changedData, i) in checkChangeLog('payment_term')"
									:key="i"
								>
									<div>
										<p class="mb-0 item-danger">
											{{ changedData.new_value }}
											<span class="mb-0 item-cancel">{{
												changedData.old_value
											}}</span>
										</p>
									</div>
								</div>
							</div>

							<div class="po-info po-method dFlex mb-1">
								<p class="po-title">Cargo Ready</p>
								<div
									class="po-data-wrapper"
									v-if="
										!checkFeildExists(
											'cargo_ready_date',
											getPoDetail.change_log
										)
									"
								>
									<p class="po-data">
										{{ getDateFormat(getPoDetail.cargo_ready_date) }}
									</p>
								</div>
								<div
									class="po-data-wrapper"
									v-for="(changedData, i) in checkChangeLog('cargo_ready_date')"
									:key="i"
								>
									<div>
										<p class="mb-0 item-danger">
											{{ getDateFormat(changedData.new_value) }}
											<span class="mb-0 item-cancel">{{
												getDateFormat(changedData.old_value)
											}}</span>
										</p>
									</div>
								</div>
							</div>

							<div
								class="po-info po-method dFlex mb-1"
								v-if="getPoDetail.must_arrive_by"
							>
								<p class="po-title">Must Arrive By</p>
								<div
									class="po-data-wrapper"
									v-if="
										!checkFeildExists('must_arrive_by', getPoDetail.change_log)
									"
								>
									<p class="po-data">
										{{ getDateFormat(getPoDetail.must_arrive_by) }}
									</p>
								</div>
								<div
									class="po-data-wrapper"
									v-for="(changedData, i) in checkChangeLog('must_arrive_by')"
									:key="i"
								>
									<div>
										<p class="mb-0 item-danger">
											{{ getDateFormat(changedData.new_value) }}
											<span class="mb-0 item-cancel">{{
												getDateFormat(changedData.old_value)
											}}</span>
										</p>
									</div>
								</div>
							</div>

							<div
								class="po-info po-method dFlex mb-1"
								v-if="getPoDetail.committed_cargo_ready_date"
							>
								<p class="po-title">Committed CRD</p>
								<div
									class="po-data-wrapper"
									v-if="
										!checkFeildExists(
											'committed_cargo_ready_date',
											getPoDetail.change_log
										)
									"
								>
									<p class="po-data">
										{{ getDateFormat(getPoDetail.committed_cargo_ready_date) }}
									</p>
								</div>
								<div
									class="po-data-wrapper"
									v-for="(changedData, i) in checkChangeLog(
										'committed_cargo_ready_date'
									)"
									:key="i"
								>
									<div>
										<p class="mb-0 item-danger">
											{{ getDateFormat(changedData.new_value) }}
											<span class="mb-0 item-cancel">{{
												getDateFormat(changedData.old_value)
											}}</span>
										</p>
									</div>
								</div>
							</div>

							<div
								class="po-info po-method dFlex mb-1"
								v-if="getPoDetail.required_deposit_value"
							>
								<p class="po-title">Required Deposite</p>
								<div class="d-flex align-center po-data-wrapper">
									<div
										v-if="getPoDetail.production_deposit_status == 'received'"
										class="d-flex align-center"
									>
										<img
											src="@/assets/icons/check-icon-header.svg"
											alt=""
											width="16"
											height="16"
											class="mr-2"
										/>
									</div>
									<div
										v-if="
											!checkFeildExists(
												'required_deposit_value',
												getPoDetail.change_log
											)
										"
									>
										<p class="po-data required-deposite">
											{{
												amountNotationSign(
													getPoDetail.required_deposit_value,
													getPoDetail.required_deposit_type
												)
											}}
										</p>
									</div>
									<div
										v-for="(changedData, i) in checkChangeLog(
											'required_deposit_value'
										)"
										:key="i"
									>
										<div>
											<p class="mb-0 item-danger">
												{{
													amountNotationSign(
														changedData.new_value,
														getPoDetail.required_deposit_type
													)
												}}
												<span class="mb-0 item-cancel">{{
													amountNotationSign(
														changedData.old_value,
														getPoDetail.required_deposit_type
													)
												}}</span>
											</p>
										</div>
									</div>
								</div>
							</div>
						</v-col>
					</v-row>
				</div>

				<div class="po-details-tabs">
					<v-tabs @change="onTabChange" v-model="currentTab">
						<v-tab
							v-for="(n, i) in dynamicTabs"
							:key="i"
							@click="getCurrentTab(i)"
						>
							{{ n.label }}
						</v-tab>
					</v-tabs>
				</div>

				<!-- TABS COMPONENTS -->
				<SalesOrdersDetailProductsDesktopTable
					v-if="tabLabel === 'Products'"
					:isMobile="isMobile"
				/>
				<DetailsHistory
					v-if="tabLabel === 'History' && getPoDetail.state !== 'draft'"
					:isMobile="isMobile"
					from="SO"
				/>
				<ActivityLog
					v-if="tabLabel === 'Activity Logs'"
					:isMobile="isMobile"
					from="SO"
					:vendorName="
						getPoDetail.buyer_id !== null
							? getUserId(getPoDetail.customer_id)
							: getPoDetail.supplier_company_name
					"
					:buyerName="
						getPoDetail.buyer_id !== null
							? getBuyer(getPoDetail.buyer_id)
							: getPoDetail.customer_company_name
					"
				/>
				<SalesOrderProductsDataChanges
					v-if="tabLabel === 'Changes'"
					:isMobile="isMobile"
				/>
				<SalesOrdersDetailShipmentsDesktopTable
					v-if="tabLabel === 'Shipments'"
					:isMobile="isMobile"
				/>
				<NotesSalesOrder
					v-if="tabLabel === 'Notes'"  />
			</div>
		</div>

		<SalesOrderCreateDialog
			:dialog.sync="dialogEditPo"
			:editedIndex.sync="editedPoIndex"
			:editedItems.sync="editedPoItems"
			@close="closePoEdit"
			fromComponent="so-details-page"
			:isMobile="isMobile"
		/>

		<SalesOrderReviewDialog
			:dialog.sync="reviewDialogSo"
			:editedIndex.sync="editedPoIndex"
			:editedItems.sync="editedPoItems"
			@close="closeReviewPo"
			:isMobile="isMobile"
			fromComponent="so-details-page"
		/>

		<UpdateStatusDialog
			:dialog.sync="updateStatusDialog"
			:editedItems.sync="editedPoItems"
			@close="closeUpdateStatusDialog"
			connectedCustomer="vendor-connected"
			:isMobile="isMobile"
		/>

		<PaymentReviewDialog
			:dialog.sync="reviewPaymentDialog"
			@close="closeReviewPaymentDialog"
			dialogType="review_payment"
			:isMobile="isMobile"
		/>

		<ViewProofDialog
			:dialog.sync="viewPaymentProofDialog"
			@close="closeViewProofDialog"
			dialogType="view_proof"
			showProof="show_proof"
			:isMobile="isMobile"
		/>

		<!-- <UpdateStatusDialog
			:dialog.sync="updateStatusDialog"
			:editedItems.sync="editedPoItems"
			:overAllStatus.sync="editedPoItems.production_overall"
			:productionOverAllStatus.sync="editedPoItems.production_overall_status"
			:productionExpectedCrd.sync="editedPoItems.committed_cargo_ready_date"
			:productionNotes.sync="editedPoItems.production_notes"
			:productDepositeStatus.sync="editedPoItems.production_deposit_status"
			@close="closeUpdateStatusDialog"
			connectedCustomer="vendor-connected"
			:isMobile="isMobile"
		/> -->

		<SalesOrderEmail
			:currentpo_id.sync="po_id"
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
		<ConfirmDialog :dialogData.sync="orderRejectConfirmationFlag">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2 v-if="getPoDetail.cr_by != null">Reject Change Request</h2>
				<h2 v-else>Reject Order</h2>
			</template>

			<template v-slot:dialog_content>
				<p v-if="getPoDetail.cr_by != null">
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
					@click="orderStateUpdate('reject')"
					text
					:disabled="getOrdersStateLoading"
				>
					<span v-if="getPoDetail.cr_by != null">Reject Changes</span>
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
					@click="confirmCancelRequest(getPoDetail)"
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
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import moment from "moment";
import SalesOrdersDetailProductsDesktopTable from "../components/Tables/SalesOrdersDetailTabs/SalesOrdersDetailProductsDesktopTable.vue";
import SalesOrdersDetailShipmentsDesktopTable from "../components/Tables/SalesOrdersDetailTabs/SalesOrdersDetailShipmentsDesktopTable.vue";
import SalesOrderCreateDialog from "../components/SalesOrdersComponenets/Dialog/SalesOrderCreateDialog.vue";
import SalesOrderReviewDialog from "../components/SalesOrdersComponenets/Dialog/SalesOrderReviewDialog.vue";
import UpdateStatusDialog from "../components/SalesOrdersComponenets/Dialog/UpdateStatusDialog.vue";
import SalesOrderEmail from "../components/SalesOrdersComponenets/Dialog/SalesOrderEmail.vue";
import DeleteDialog from "../components/Dialog/DeleteDialog.vue";
import ConfirmDialog from "../components/Dialog/GlobalDialog/ConfirmDialog.vue";
import _ from "lodash";
import axios from "axios";
import globalMethods from "../utils/globalMethods";
import POWarning from "../components/PosComponents/Dialog/POWarning.vue";
import { STATES, CHANGE_REQUEST } from "../constants/states";
import SalesOrderProductsDataChanges from "@/components/Tables/SalesOrdersDetailTabs/SalesOrderProductsDataChanges.vue";
import DetailsHistory from "../components/Tables/POsDetailTabs/DetailsHistory.vue";
import ActivityLog from "../components/Tables/POsDetailTabs/ActivityLog.vue";
import PaymentReviewDialog from "../components/SalesOrdersComponenets/Dialog/PaymentReviewDialog.vue";
import ViewProofDialog from "@/components/SalesOrdersComponenets/Dialog/PaymentReviewDialog.vue";
import NotesSalesOrder from '../components/Tables/SalesOrdersDetailTabs/NotesSalesOrder.vue';

export default {
	components: {
		SalesOrdersDetailProductsDesktopTable,
		SalesOrdersDetailShipmentsDesktopTable,
		SalesOrderCreateDialog,
		SalesOrderReviewDialog,
		SalesOrderEmail,
		DeleteDialog,
		POWarning,
		ConfirmDialog,
		UpdateStatusDialog,
		SalesOrderProductsDataChanges,
		DetailsHistory,
		ActivityLog,
		PaymentReviewDialog,
		ViewProofDialog,
		NotesSalesOrder
	},
	data: () => ({
		tabs: [
			{ label: "Products", sort_order: 1 },
			{ label: "History", sort_order: 3 },
			{ label: "Shipments", sort_order: 4 },
			{ label: "Activity Logs", sort_order: 5 },
			{ label: "Notes", sort_order: 6 },
		],
		change: [{ label: "Changes", sort_order: 2 }],
		currentTab: 0,
		isMobile: false,
		po_id: null,
		detailsLoading: true,
		items: [
			{
				text: "Po",
				disabled: false,
				href: "/pos",
			},
			{
				text: "#",
				disabled: true,
				href: "breadcrumbs_link_1",
			},
		],
		headers: [
			{
				text: "SKU",
				align: "start",
				sortable: false,
				value: "sku",
				fixed: true,
				width: "15%",
			},
			{
				text: "Description",
				align: "start",
				sortable: false,
				value: "description",
				fixed: true,
				width: "40%",
			},
			{
				text: "Quantity",
				align: "end",
				sortable: false,
				value: "quality",
				fixed: true,
				width: "15%",
			},
			{
				text: "Unit Price",
				align: "end",
				sortable: false,
				value: "unit_price",
				fixed: true,
				width: "15%",
			},
			{
				text: "Amount",
				align: "end",
				sortable: false,
				value: "amount",
				fixed: true,
				width: "15%",
			},
		],
		dialogEditPo: false,
		editedPoIndex: 0,
		editedPoItems: {},
		defaultPoItems: {},
		dialogPoDelete: false,
		currentPoToDelete: null,
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
		dialogWarning: false,
		poData: null,
		stateStatus: STATES,
		reviewDialogSo: false,
		orderRejectConfirmationFlag: false,
		dialogCancelChangeRequest: false,
		cancelRequest: "accept",
		updateStatusDialog: false,
		tabLabel: "Products",
		reviewPaymentDialog: false,
		viewPaymentProofDialog: false,
	}),
	computed: {
		...mapGetters({
			getAllPo: "salesOrders/getAllPo",
			getPoDetailLoading: "salesOrders/getPoDetailLoading",
			getPoDetail: "salesOrders/getPoDetail",
			getWarehouse: "warehouse/getWarehouse",
			getBuyerLists: "salesOrders/getBuyerLists",
			getUser: "getUser",
			getDownloadLoading: "salesOrders/getDownloadLoading",
			getPoDeleteLoading: "salesOrders/getPoDeleteLoading",
			getAllPoPending: "salesOrders/getAllPoPending",
			getPendingPage: "salesOrders/getPendingPage",
			getOrdersStateLoading: "salesOrders/getOrdersStateLoading",
			getCurrentSoOpenTab: "salesOrders/getCurrentSoOpenTab",
			getCancelChangeRequestLoading:
				"salesOrders/getCancelChangeRequestLoading",
		}),
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
		cancelCrButton() {
			let defaultCustomerId = this.defaultCustomerId();
			let { cr_by, state } = this.getPoDetail;
			if (defaultCustomerId == cr_by && state == CHANGE_REQUEST) {
				return true;
			} else {
				return false;
			}
		},
		dynamicTabs() {
			let checkProductChangeLog = false;
			let checkInfoChangeLog = false;

			let { purchase_order_products, change_log, state } = this.getPoDetail;

			let dataInfo =
				change_log &&
				change_log.filter(
					(item) =>
						item.field !== "cr_by" &&
						item.field !== "updated_by" &&
						item.field !== "signature_by"
				);

			checkInfoChangeLog = dataInfo && dataInfo.length > 0 ? true : false;

			purchase_order_products &&
				purchase_order_products.map((item) => {
					checkProductChangeLog =
						item.change_log && item.change_log.length > 0 ? true : false;
				});

			let dynamicTab;

			if (
				(checkProductChangeLog || checkInfoChangeLog) &&
				state == "change_request"
			) {
				dynamicTab = [...this.tabs, ...this.change];
			} else {
				dynamicTab = [...this.tabs];

				if (state === "draft") {
					dynamicTab = dynamicTab.filter((v) => {
						return v !== "History";
					});
				}
			}
			return _.orderBy(dynamicTab, ["sort_order"], ["asc"]);
		},
	},
	methods: {
		...mapActions({
			getPo: "salesOrders/getPo",
			fetchWarehouse: "warehouse/fetchWarehouse",
			fetchBuyerLists: "salesOrders/fetchBuyerLists",
			downloadPo: "salesOrders/downloadPo",
			deletePo: "salesOrders/deletePo",
			fetchPoShipmentDetails: "salesOrders/fetchPoShipmentDetails",
			fetchTerms: "fetchTerms",
			fetchPoPendingNew: "salesOrders/fetchPoPendingNew",
			updateState: "salesOrders/updateState",
			setCurrentSOTab: "salesOrders/setCurrentSOTab",
			setSoCurrentOpenTab: "salesOrders/setSoCurrentOpenTab",
			setSoCurrentAllTab: "salesOrders/setSoCurrentAllTab",
			fetchImportName: "salesOrders/fetchImportName",
			cancelChangeRequest: "salesOrders/cancelChangeRequest",
			fetchProductStatus: "orders/fetchProductStatus",
			fetchPoHistory: "poDetails/fetchPoHistory",
			fetchPoActivityLog: "poDetails/fetchPoActivityLog",
			fetchSuppliersSku:"salesOrders/fetchSuppliersSku"
		}),
		...globalMethods,
		onResize() {
			if (window.innerWidth < 960) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		async loadPoMetaData() {
			try {
				await this.getPo(this.po_id);

				if (
					typeof this.getPoDetail !== "undefined" &&
					this.getPoDetail !== null
				) {
					let { buyer_id, customer_id, id, po_number } = this.getPoDetail;
					let payload = { po_id: id, buyer_id, customer_id, po_number };
					await this.fetchPoShipmentDetails(payload);
				}

				await this.fetchWarehouse();
				await this.fetchImportName();
				await this.fetchBuyerLists();
				this.detailsLoading = false;
			} catch (e) {
				console.log(e);
				this.detailsLoading = false;
			}
		},
		getDateFormat(date, date_type) {
			if (date !== null && date !== "") {
				if (date_type == "date_time") {
					return moment(date).format(" h:mm A, MMM DD, YYYY");
				} else if (date_type == "time_date_formate") {
					return moment(date).format(" h:mm A, DD/MM/YY");
				} else {
					return moment(date).format("MMM DD, YYYY");
				}
			} else {
				return "N/A";
			}
		},
		async onTabChange(i) {
			this.currentTab = i;
			this.tabLabel = this.dynamicTabs[i].label;

			if (this.tabLabel === "History") {
				await this.fetchPoHistory(this.po_id);
			}
			if (this.tabLabel === "Activity Logs") {
				await this.fetchPoActivityLog(this.po_id);
			}
		},
		getCurrentTab(i) {
			this.currentTab = i;
		},
		getBuyer(id) {
			if (Array.isArray(this.getBuyerLists) && this.getBuyerLists.length > 0) {
				let findBuyer = _.find(this.getBuyerLists, (e) => e.id === id);
				if (typeof findBuyer !== "undefined") {
					return findBuyer.company_name;
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
				this.getWarehouse.results.length !== "undefined"
			) {
				let getData = this.getWarehouse.results;
				let findSizeValue =
					id !== "undefined" ? _.find(getData, (e) => e.id == id) : "";

				if (typeof findSizeValue !== "undefined") {
					if (findSizeValue.address !== "undefined") {
						return `<span>${findSizeValue.name}</span><br/><span>${findSizeValue.address}</span>`;
					}
				} else {
					return "--";
				}
			}
		},
		getUserId(id) {
			if (
				typeof this.getUser !== "undefined" &&
				typeof this.getUser == "string"
			) {
				let parsedData = JSON.parse(this.getUser);

				if (
					parsedData.customers_api !== "undefined" &&
					Array.isArray(parsedData.customers_api) &&
					parsedData.customers_api.length > 0
				) {
					let findCustomer = _.find(
						parsedData.customers_api,
						(e) => e.id === id
					);
					if (typeof findCustomer !== "undefined") {
						return findCustomer.company_name;
					}
				}
			}

			return "--";
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

							this.processSingleProduct(++getIndex, context, po);
						})
						.catch((e) => {
							console.log(e);
							po.purchase_order_products[getIndex].unit_price = 0;
							this.processSingleProduct(++getIndex, context, po);
						});
				} else {
					po.purchase_order_products[getIndex].unit_price =
						unit_price == null || unit_price == "" ? 0 : unit_price;
					this.processSingleProduct(++getIndex, context, po);
				}
			} else {
				po.loadingState = false;
				po.products = po.purchase_order_products;
				this.editedPoItems = Object.assign({}, po);
			}
		},
		checkEditPo(po) {
			if (po.status === "partial_shipped") {
				this.dialogWarning = true;
				this.poData = po;
			} else {
				this.editPo(true);
			}
		},
		async editPo(isEdit, changeRequest) {
			let po = this.getPoDetail;
			this.dialogEditPo = isEdit ? isEdit : false;
			if (this.posPending.length > 0) {
				this.editedPoIndex = _.findIndex(
					this.posPending,
					(e) => e.id === po.id
				);
			}

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

					po.change_request_button = changeRequest;

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
						volume: volume,
						weight: weight,
						category_sku: product.category_sku,
						name: product.name,
						other_party_product_id: other_party_product_id || 0,
						other_party_product_sku: other_party_product_sku,
						change_log: change_log,
						ship_date,
						row_id:id,
						po_product_id:id
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
				// 		await this.fetchSuppliersSku(this.editedPoItems.customer_id)
				// 	}catch(e){
				// 		this.notificationError(e)
				// 	}
				// }
			}
		},
		closePoEdit() {
			this.dialogEditPo = false;
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.getPoDetail);
				this.editedPoIndex = 0;
			});

			if (this.poData !== null) {
				this.closeWarning();
			}
		},
		emailPo(po) {
			this.dialogEmail = true;
			this.editedPoIndex = -1;
			this.editedEmailItem.po = po;

			if (Array.isArray(this.getBuyerLists) && this.getBuyerLists.length > 0) {
				let findBuyer = _.find(this.getBuyerLists, (e) => e.id === po.buyer_id);
				if (typeof findBuyer !== "undefined") {
					this.editedEmailItem.emails = findBuyer.emails;
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
		async download(po) {
			try {
				await this.downloadPo(po);
				this.notificationCustom("File has been downloaded.");
			} catch (e) {
				this.notificationError(e);
			}
		},
		deletePO(po) {
			this.dialogPoDelete = true;
			this.currentPoToDelete = po;
			this.currentPoToDelete.name = po.po_number;
		},
		async deletePoConfirm() {
			try {
				await this.deletePo(this.currentPoToDelete.id);
				this.notificationCustom("Purchase order successfully deleted.");
				this.closePoDelete();
				this.$router.push(`/pos`);

				await this.fetchPoPendingNew({
					page: this.getPendingPage,
				});
			} catch (e) {
				this.closePoDelete();
				this.notificationError(e);
			}
		},
		closePoDelete() {
			this.dialogPoDelete = false;
			this.currentPoToDelete = null;
		},
		closeWarning() {
			this.dialogWarning = false;
			this.poData = null;
		},
		async orderStateUpdate(orderState) {
			const payload = {
				poNumber: this.getPoDetail.po_number,
				orderAction: orderState,
			};
			await this.updateState(payload);
			await this.getPo(this.po_id);
			this.orderRejectConfirmationFlag = false;
			this.dialogCancelChangeRequest = false;
		},
		reviewOrder(change_request) {
			this.reviewDialogSo = true;

			this.editPo(false, change_request);
		},
		closeReviewPo() {
			this.reviewDialogSo = false;
		},
		orderRejectConfirmationCall() {
			this.orderRejectConfirmationFlag = true;
		},
		orderRejectConfirmationDialogClose() {
			this.orderRejectConfirmationFlag = false;
		},
		cancelChangeRequestDialog() {
			this.dialogCancelChangeRequest = true;
		},
		closeCancelChangeRequest() {
			this.dialogCancelChangeRequest = false;
		},
		async confirmCancelRequest(item) {
			if (this.cancelRequest == "reject") {
				this.orderStateUpdate(this.cancelRequest);
			} else if (this.cancelRequest == "accept") {
				const payload = {
					id: item.id,
					action: this.cancelRequest,
				};
				await this.cancelChangeRequest(payload);
				await this.getPo(this.po_id);

				this.dialogCancelChangeRequest = false;
			}
		},
		checkChangeLog(field) {
			return (
				this.getPoDetail.change_log &&
				this.getPoDetail.change_log.filter((item) => item?.field == field)
			);
		},
		checkFeildExists(field, changedData) {
			if (changedData) {
				let data = changedData.find((item) => {
					return item.field && item.field === field;
				});
				let check = data && data.field == field ? true : false;
				return check;
			}
		},
		updateStatus(item) {
			this.updateStatusDialog = true;
			this.editedPoItems = Object.assign({}, item);
		},
		closeUpdateStatusDialog() {
			this.updateStatusDialog = false;
			this.$nextTick(() => {
				this.editedPoItems = Object.assign({}, this.defaultPoItems);
			});
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
		reviewPayment() {
			this.reviewPaymentDialog = true;
		},
		closeReviewPaymentDialog() {
			this.reviewPaymentDialog = false;
		},
		viewProof() {
			this.viewPaymentProofDialog = true;
		},
		closeViewProofDialog() {
			this.viewPaymentProofDialog = false;
		},
	},
	async mounted() {
		this.$store.dispatch("page/setPage", "sales-orders");
		this.po_id = this.$route.params.id;
		this.fetchProductStatus();
		this.loadPoMetaData();
		await this.fetchTerms();
	},
	async updated() {
		if (this.po_id !== this.$route.params.id) {
			this.po_id = this.$route.params.id;
			this.loadPoMetaData();
		}
	},
};
</script>

<style lang="scss">
@import "../assets/scss/pages_scss/salesOrders/salesOrdersDetails.scss";
@import "../assets/scss/buttons.scss";
@import "../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../assets/scss/pages_scss/salesOrders/salesOrdersEmailDialog.scss";
@import "../assets/scss/inputs.scss";
</style>
