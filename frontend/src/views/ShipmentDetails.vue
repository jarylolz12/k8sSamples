<!-- @format -->

<template>
	<v-container fluid>
		<div class="shipment-details-wrapper">
			<div class="preloader" v-if="detailsLoading">
				<v-progress-circular :size="40" color="#0171a1" indeterminate>
				</v-progress-circular>
			</div>

			<v-row v-if="!detailsLoading">
				<v-col class="first-col" sm="12" md="8">
					<div class="details-breadcrumbs"
						:class="getShipmentDetails.is_tracking_shipment === 1 ? 'tracking' : ''">

						<div class="navigation-back">
							<router-link to="/shipments" class="shipment-link">
								Shipments
							</router-link>

							<span class="right-chevron">
								<img src="../assets/images/right_chevron.svg" alt="" srcset="" width="7px" />
							</span>

							<p class="shipment-ref"
								v-if="
									getShipmentDetails.shifl_ref !== 'undefined' &&
									getShipmentDetails.shifl_ref !== null
								">
								{{ getShipmentDetails.shifl_ref }}
							</p>
						</div>

						<!-- <div class="button-delete-tracking-shipment" v-if="getShipmentDetails.is_tracking_shipment === 1">
							<button class="btn-white btn-delete">
								<img src="../assets/icons/delete-po.svg" width="16px" height="16px" class="mr-1">
								Delete Tracking Shipment
							</button>
						</div> -->
					</div>

					<div id="top-header" v-resize="onResize">
						<div class="reference-status">
							<div :class="`${isMobile ? 'reference-container-mobile' : ''}`">
								<h2 v-if="
										getShipmentDetails.shifl_ref !== 'undefined' &&
										getShipmentDetails.shifl_ref !== null
									">
									Ref #{{ getShipmentDetails.shifl_ref }}

									<!-- <span class="is-shipment-tracking"
										v-if="
											getFilteredShipmentDetails.external_shipment == 1 &&
											!getMilestonesLoading &&
											getMilestonesAttributes !== null &&
											!isMobile
										">
										Tracking
									</span>

									<span style="display: none;" class="is-not-tracking-shipment-details"
										v-if="
											getFilteredShipmentDetails.external_shipment == 1 &&
											!getMilestonesLoading &&
											getMilestonesAttributes == null &&
											!isMobile
										">
										Not Tracking (temp hide)
									</span> -->

									<div class="d-flex t-shipment-status-wrapper" v-if="!isMobile">
										<div style="margin-right: 6px;" :class="getShipmentStatusClass(getShipmentDetails, 'tracking')" 
											v-if="getShipmentDetails.tracking_status !== ''" class="font-medium">
											{{ getShipmentDetails.tracking_status }}
										</div>

										<div style="margin-right: 2px !important;" class="font-medium" 
											v-if="shipment_status==='In transit - hold'">
											{{ "In Transit - Pending Release"}}
										</div>
										<div style="margin-right: 2px !important;" class="font-medium Partial-released" 
											v-else-if="shipment_status==='Partially discharged - released'">
											<span style="color: #4A4A4A;" class="font-medium pr-1">{{ "Partial Discharged - " }}</span>
											<span style="color: #16B442;" class="font-medium">{{ "Released" }}</span>
										</div>
										<div style="margin-right: 2px; !important" class="font-medium Partial-hold" 
											v-else-if="shipment_status==='Partially discharged - hold'">
											<span style="color: #4A4A4A;" class="font-medium pr-1">{{ "Partial Discharged - " }}</span>
											<span style="color: #F93131;" class="font-medium">{{ "Hold" }}</span>
										</div>
										<div style="margin-right: 2px !important;" class="font-medium" 
											v-else :class="getShipmentStatusClass(getShipmentDetails,'default')">
											{{ ucFirst(shipment_status.toLowerCase()) }}
										</div>
									</div>
								</h2>

								<div v-if="isMobile" class="d-flex flex-row" style="position: relative;">
									<div v-if="getFilteredShipmentDetails.external_shipment == 1" :class="`edit-shipment-btn-wrapper ${(isMobile) ? 'mobile' : ''}`">
										<button v-if="!getShipmentDetailsLoading" @click.stop="showEditShipmentDialog" class="btn-blue edit-shipment-btn d-flex">
											<kenetic-icon iconName="edit" />	
											<span>Edit</span>
										</button>
									</div>
									<v-menu
										offset-y
										v-if="getShipmentDetails.is_tracking_shipment == 1"
										bottom
										left
										content-class="delete-shipment-options-container">
										<template v-slot:activator="{ on, attrs }">
											<button
												v-bind="attrs"
												v-on="on"
												class="three-dots-container">
												<custom-icon
													iconName="three-dots"
													color="#0171A1"
													width="11"
													height="3"/>
											</button>
										</template>
										<v-list class="delete-shipment-options-container-list">
											<v-list-item @click.stop="showDeleteShipmentDialog">
												<v-list-item-title>
													Delete Shipment
												</v-list-item-title>
											</v-list-item>
											<v-list-item v-if="1==2" @click.stop="showAddShipmentDialog">
												<v-list-item-title>
													Create Shipment
												</v-list-item-title>
											</v-list-item>
										</v-list>

									</v-menu>
								</div>
							</div>

							<div style="position: relative;">
								<button
									class="btn btn-status-white shipment-status"
									v-if="shipment_status !== null && 1==2"
								>
									{{ shipment_status }}
								</button>

								<!-- <div v-if="!isMobile" :class="`edit-shipment-btn-wrapper ${(isMobile) ? 'mobile' : ''}`">
									<v-btn v-if="!getShipmentDetailsLoading && getFilteredShipmentDetails.is_tracking_shipment == 1" @click.stop="showEditShipmentDialog" class="btn-blue edit-shipment-btn d-flex">
										<kenetic-icon iconName="edit" />	
										<span>Edit Shipment</span>
									</v-btn>
								</div> -->
								<div v-if="!isMobile" :class="`edit-shipment-btn-wrapper ${(isMobile) ? 'mobile' : ''}`">
									<v-btn v-if="!getShipmentDetailsLoading && getFilteredShipmentDetails.is_draft == 1" @click.stop="EditBookingShipmentDialog" class="btn-blue edit-shipment-btn d-flex">
										<kenetic-icon iconName="edit" />	
										<span>Edit Draft Shipment</span>
									</v-btn>
								</div>
								<div>
									<v-menu
										offset-y
										bottom
										left
										content-class="pending-dropdown-container"
									>
										<template v-slot:activator="{ on, attrs }">
											<button v-bind="attrs" v-on="on" class="three-dots-container">
												<custom-icon
													iconName="three-dots"
													color="#0171A1"
													width="11"
													height="3"
												/>
											</button>
										</template>
										<v-list>
											<v-list-item>
												<v-list-item-title class="k-fw-sm k-font-inter-regular k-red" style="cursor: pointer;" @click="cancelBooking()">
													Cancel Request
												</v-list-item-title>
											</v-list-item>
										</v-list>
									</v-menu>
								</div>
								<!-- <span
									class="is-shipment-tracking is-shipment-tracking-mobile"
									v-if="
										getFilteredShipmentDetails.external_shipment == 1 &&
											!getMilestonesLoading &&
											getMilestonesAttributes !== null &&
											isMobile
									"
								>
									Tracking
								</span>
								<span
									style="display: none;"
									v-if="
										getFilteredShipmentDetails.external_shipment == 1 &&
											!getMilestonesLoading &&
											getMilestonesAttributes === null &&
											isMobile
									"
									class="is-not-tracking-shipment-details is-not-tracking-shipment-details-mobile"
								>
									Not Tracking (temp hide)
								</span> -->
							
								<!-- tracking_status -->
								<div class="d-flex t-shipment-status-wrapper mobile-status-multiple" v-if="isMobile">
									<div style="margin-right: 6px;" :class="getShipmentStatusClass(getShipmentDetails, 'tracking')" 
										v-if="getShipmentDetails.tracking_status !== ''" class="font-medium">
										{{ getShipmentDetails.tracking_status }}
									</div>

									<div style="margin-right: 2px !important;" class="font-medium" 
										v-if="shipment_status==='In transit - hold'">
										{{ "In Transit - Pending Release"}}
									</div>
									<div style="margin-right: 2px !important;" class="font-medium Partial-released" 
										v-else-if="shipment_status==='Partially discharged - released'">
										<span style="color: #4A4A4A;" class="font-medium pr-1">{{ "Partial Discharged - " }}</span>
										<span style="color: #16B442;" class="font-medium">{{ "Released" }}</span>
									</div>
									<div style="margin-right: 2px; !important" class="font-medium Partial-hold" 
										v-else-if="shipment_status==='Partially discharged - hold'">
										<span style="color: #4A4A4A;" class="font-medium pr-1">{{ "Partial Discharged - " }}</span>
										<span style="color: #F93131;" class="font-medium">{{ "Hold" }}</span>
									</div>
									<div style="margin-right: 2px !important;" class="font-medium" 
										v-else :class="getShipmentStatusClass(getShipmentDetails,'default')">
										{{ ucFirst(shipment_status.toLowerCase()) }}
									</div>
								</div>

								<!-- <v-menu
									bottom
									left
									nudge-left
									nudge-bottom
									offset-y
									v-if="
										getShipmentDetails.is_tracking_shipment == 1 && !isMobile
									"
									content-class="delete-shipment-options-container"
								>
									<template v-slot:activator="{ on, attrs }">
										<button
											v-bind="attrs"
											v-on="on"
											class="three-dots-container"
										>
											<custom-icon
												iconName="three-dots"
												color="#0171A1"
												width="11"
												height="3"
											/>
										</button>
									</template>
									<v-list class="delete-shipment-options-container-list">
										<v-list-item @click.stop="showDeleteShipmentDialog">
											<v-list-item-title class="delete-shipment-item-title">
												Delete Shipment
											</v-list-item-title>
										</v-list-item>
										<v-list-item v-if="1==2" @click.stop="showAddShipmentDialog">
											<v-list-item-title>
												Create Shipment
											</v-list-item-title>
										</v-list-item>
									</v-list>
								</v-menu> -->
							</div>
						</div>

						<div :class="`d-flex shipment-tracking-label ${(isMobile) ? 'mobile' : ''}`">
							<div :class="`${(isMobile) ? 'mobile mb-2' : ''}`">
								{{ getShipmentDetails.customer !== 'undefined' && getShipmentDetails.customer !== null && getShipmentDetails.customer.company_name !== 'undefined' ? getShipmentDetails.customer.company_name : ''
								}}
							</div>
							<div class="updated-at-content">
								<div class="mr-2 updated-title">Updated At</div>
								<div>{{ formatDate(getShipmentDetails.updated_at) }}</div>
							</div>
						</div>

						<div v-if="1 == 2"
							:class="
								`${isMobile ? 'is-not-tracking-shipment-details-ttc-mobile' : ''} is-not-tracking-shipment-details-ttc`">
							<div>
								<div> Type: </div>
								<div> N/A </div>
							</div>
							<div>
								<div> Terminal: </div>
								<div> N/A </div>
							</div>
							<div>
								<div> Carrier: </div>
								<div v-if="getShipmentDetails.is_tracking_shipment === 1">
									{{ getMilestonesAttributes.shipping_line_short_name !==
											"undefined" &&
										getMilestonesAttributes.shipping_line_short_name !== ""
											? getMilestonesAttributes.shipping_line_short_name
											: "N/A"
									}}
								</div>
								<div v-if="getShipmentDetails.is_tracking_shipment === 0">
									{{ getShipmentDetails.carrier !== "undefined" &&
										getShipmentDetails.carrier !== "" &&
										getShipmentDetails.carrier !== null
											? typeof getShipmentDetails.carrier === "String"
												? getShipmentDetails.carrier
												: getShipmentDetails.carrier.name
											: "N/A"
									}}
								</div>
							</div>
						</div>

						<div class="place-wrapper"
							style="height:30px"
							v-if="getShipmentDetails.is_tracking_shipment === 0 && getShipmentDetails.shipment_status !='Pending Approval'"
						>
							<div class="place-content">
								<p class="heading"
									style="margin-right: 25px"
									v-if="shipment_type !== '' && shipment_type !== null">
									<span class="info-title">Type: </span>

									<img
										v-if="shipment_type == 'LCL'"
										src="../assets/images/small-container.svg" />

									<img
										v-if="shipment_type == 'Air' || shipment_type == 'AIR'"
										src="../assets/images/airplane-shipment.svg" />

									<img
										v-if="shipment_type == 'FCL'"
										src="../assets/images/big-container.svg" />

									{{ shipment_type }}
								</p>
								<div
									class="carrier-wrapper"
									v-if="
										getShipmentDetails.carrier !== 'undefined' &&
										getShipmentDetails.carrier !== '' &&
										getShipmentDetails.carrier !== null &&
										1 == 2">
									<p class="heading">
										<span class="info-title">Carrier: </span>
										{{ typeof getShipmentDetails.carrier === "String"
												? getShipmentDetails.carrier
												: getShipmentDetails.carrier.name
										}}
									</p>
								</div>
							</div>
							<p
								class="heading"
								v-if="!isMobile && location_to_with_details !== null"
							>
								<span v-if="getShipmentDetails.shipment_status !='Pending Approval'" style="color: #4A4A4A !important;">
									{{ location_to_with_details }}
								</span>
							</p>
						</div>

						<div class="place-wrapper" style="height:30px"
							v-if="
								getShipmentDetails.is_tracking_shipment === 1 &&
								getMilestonesAttributes !== null
							">
							<div class="place-content">
								<p class="heading"
									style="margin-right: 25px"
									v-if="shipment_type !== '' && shipment_type !== null">
									<span class="info-title">Type: </span>

									<img
										v-if="shipment_type == 'LCL'"
										src="../assets/images/small-container.svg" />

									<img
										v-if="shipment_type == 'Air' || shipment_type == 'AIR'"
										src="../assets/images/airplane-shipment.svg" />

									<img
										v-if="shipment_type == 'FCL'"
										src="../assets/images/big-container.svg" />

									{{ shipment_type }}
								</p>

								<div class="carrier-wrapper"
									v-if="
										getMilestonesAttributes.shipping_line_short_name !==
										'undefined' &&
										getMilestonesAttributes.shipping_line_short_name !== ''
									">
									<p class="heading">
										<span class="info-title">Carrier: </span>
										{{ getMilestonesAttributes.shipping_line_short_name }}
									</p>
								</div>
							</div>
							<p
								class="heading"
								v-if="
									!isMobile &&
										getMilestonesAttributes.port_of_discharge_name !== null &&
										(getMilestonesAttributes.pod_eta_at !== null || getMilestonesAttributes.pod_ata_at!==null )
								"
							>
								<span style="color: #4A4A4A !important;">
									{{
										getTrackingDetailsLocationWithDetails(
											getMilestonesAttributes
										)
									}}
								</span>
							</p>
						</div>

						<div class="mobile-status"
							v-if="
								isMobile &&
								getMilestonesAttributes !== null &&
								getMilestonesAttributes.port_of_discharge_name !== null &&
								(getMilestonesAttributes.pod_eta_at !== null || getMilestonesAttributes.pod_ata_at !== null )
							">
							<p class="heading">
								<span style="color: #4A4A4A !important;">
									{{ getTrackingDetailsLocationWithDetails(getMilestonesAttributes)}}
								</span>
							</p>
						</div>
					</div>

					<div class="to-dos"
						v-if="
							!isMobile &&
							(oblRequired.length > 0 || commercialDocuments.length > 0) &&
							(oblRequired.includes(false) ||
							commercialDocuments.includes(false))
						">
						<v-list two-line>
							<h3 class="to-dos-title">To-Dos</h3>
							<v-list-item v-show="oblRequired.includes(false)">
								<v-list-item-avatar class="my-0">
									<v-img src="../assets/images/red-document.svg"></v-img>
								</v-list-item-avatar>
								<v-list-item-content class="pa-1 mt-1">
									<p class="mb-1">OBL Required</p>
									<ul>
										<li class="supplier-list"
											v-for="(supplier,
											i) in getShipmentDetails.shipment_suppliers"
											:key="i"
											v-show="supplier.obl_filled === false">
											{{ supplier.name }}
										</li>
									</ul>
								</v-list-item-content>
							</v-list-item>

							<v-list-item v-show="commercialDocuments.includes(false)">
								<v-list-item-avatar class="my-0">
									<v-img src="../assets/images/red-document.svg"></v-img>
								</v-list-item-avatar>

								<v-list-item-content class="pa-1 mt-2">
									<h5 class="mb-1">Commercial Documents Required</h5>
									<ul>
										<li
											class="supplier-list"
											v-for="(supplier,
											i) in getShipmentDetails.shipment_suppliers"
											:key="i"
											v-show="supplier.commercial_documents_filled === false">
											{{ supplier.name }}
											{{ supplier.hbl_num ? `(HBL# ${supplier.hbl_num})` : "" }}
										</li>
									</ul>
								</v-list-item-content>
								<v-btn
									class="btn btn-blue"
									width="150px"
									@click="openUploadDialog">
									Upload Documents
								</v-btn>
							</v-list-item>

							<!-- <v-list-item>
								<v-list-item-avatar class="my-0">
									<v-img src="../assets/images/red-freight.svg"></v-img>
								</v-list-item-avatar>

								<v-list-item-content class="pa-1">
									<h5 class="mb-1">Payment Required</h5>
									<v-list-item-subtitle></v-list-item-subtitle>
								</v-list-item-content>
								<v-btn class="btn btn-blue" width="150px">
									Make Payment
								</v-btn>
							</v-list-item> -->
						</v-list>
					</div>

					<div
						class="to-dos mt-1"
						v-if="
							isMobile &&
								(oblRequired.length > 0 || commercialDocuments.length > 0) &&
								(oblRequired.includes(false) ||
									commercialDocuments.includes(false))
						"
					>
						<v-list two-line>
							<h3 class="to-dos-title">To-Dos</h3>
							<v-list-item v-show="oblRequired.includes(false)">
								<v-list-item-avatar class="my-0">
									<v-img src="../assets/images/red-document.svg"></v-img>
								</v-list-item-avatar>

								<v-list-item-content class="pa-1">
									<p class="mb-1 mt-1">OBL Required</p>
									<ul>
										<li
											class="supplier-list"
											v-for="(supplier,
											i) in getShipmentDetails.shipment_suppliers"
											:key="i"
											v-show="supplier.obl_filled === false"
										>
											{{ supplier.name }}
										</li>
									</ul>
								</v-list-item-content>
							</v-list-item>

							<v-list-item v-show="commercialDocuments.includes(false)">
								<v-list-item-avatar class="my-0">
									<v-img src="../assets/images/red-document.svg"></v-img>
								</v-list-item-avatar>

								<v-list-item-content class="pa-1">
									<h5 class="mb-1 mt-1">Commercial Documents Required</h5>
									<ul>
										<li
											class="supplier-list"
											v-for="(supplier,
											i) in getShipmentDetails.shipment_suppliers"
											:key="i"
											v-show="supplier.commercial_documents_filled === false"
										>
											{{ supplier.name }}
											{{ supplier.hbl_num ? `(HBL# ${supplier.hbl_num})` : "" }}
										</li>
									</ul>
								</v-list-item-content>
							</v-list-item>
							<v-btn
								class="btn btn-blue list-btn mt-1"
								width="150px"
								@click="openUploadDialog"
							>
								Upload Documents
							</v-btn>

							<!-- <v-list-item>
								<v-list-item-avatar class="my-0">
									<v-img src="../assets/images/red-freight.svg"></v-img>
								</v-list-item-avatar>

								<v-list-item-content class="pa-1">
									<h5 class="mb-1">Payment Required</h5>
									<v-list-item-subtitle
										class="list-subtitle"
									></v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
							<v-btn class="btn btn-blue list-btn" width="150px"
								>Make Payment</v-btn -->
						</v-list>
					</div>

					<div
						class="documents-wrapper"
						style="background:white; margin: 16px 0 0; border: 1px solid #EBF2F5; position:relative; border-radius: 4px;"
					>
						<v-tabs
							mobile-breakpoint="500"
							:class="`customTab ${isMobile ? 'customTab-mobile' : ''}`"
							height="50px"
						>
							<v-tab
								v-for="n in tabs"
								:key="n"
								v-on:click="setMainTab(n)"
								:class="n"
							>
								{{ n }}
							</v-tab>
						</v-tabs>

						<UploadDocumentsDialog
							:dialogData.sync="dialogUploadDocs"
							:dialogMessage.sync="dialogMessage"
							@fetchDocuments="fetchShipmentDocumentsAfter"
							@close="closeUploadDialog"
							:shipment_id="shipment_id"
						/>

						<div v-if="currentTab == 'Shipment Info'">
							<ShipmentInfo
								:getDetails="getShipmentDetails"
								:isMobile="isMobile"
								:shipment_status="shipment_status"
								:getFilteredShipmentDetails="getFilteredShipmentDetails"
							/>
						</div>
						<div v-if="currentTab == 'Products'">
							<ShipmentProducts :id="getShipmentDetails.id" />
						</div>
						<div v-if="currentTab == 'Documents'">
							<ShipmentDocuments
								:shipmentsLoading.sync="shipmentDocumentsLoading"
								@handleDocumentsChange="handleDocumentsChange"
								:getDetails="getShipmentDetails"
								:id="shipment_id"
							/>
						</div>
						<div v-if="currentTab == 'Bills'">
							<ShipmentBills :isMobile="isMobile" />
						</div>
						<div v-if="currentTab == 'Notes'">
							<ShipmentNotes :id="shipment_id" />
						</div>
						<div v-if="isMobile && currentTab == 'Milestones'">
							<Map
								v-show="this.getShipmentDetails.ais_link !== ''"
								:getDetails="getShipmentDetails"
							/>

							<Milestones
								:loading="getMilestonesNewLoading"
								:getDetails="getShipmentDetails"
								:status="shipment_status"
								:departedLocation="getShipmentDetails.location_from_name"
								:arrivedLocation="getShipmentDetails.location_to_name"
							/>
						</div>
					</div>
				</v-col>

				<v-col class="second-col" sm="12" md="4" v-if="!isMobile">
					<Map
						v-show="this.getShipmentDetails.ais_link !== ''"
						:getDetails="getShipmentDetails"
					/>

					<v-card class="pa-5" outlined tile :class="this.getShipmentDetails.ais_link !== '' ? 'has-ais' : ''">
						<div class="milestone-content-wrapper">
							<h2 class="milestone-name">Milestones</h2>

							<Milestones
								:loading="getMilestonesNewLoading"
								:getDetails="getShipmentDetails"
								:status="shipment_status"
								:departedLocation="getShipmentDetails.location_from_name"
								:arrivedLocation="getShipmentDetails.location_to_name"
							/>
						</div>
					</v-card>
				</v-col>
			</v-row>
		</div>

		<create-shipment-dialog
            reference="formEditShipment"
            v-if="editShipmentDialogView"
            className="edit-shipment-dialog-wrapper"
            :show="editShipmentDialogView"
            :isMobile="isMobile"
            :item="getShipmentDetails"
            :rules="editShipmentRules"
            :windowWidth="windowWidth"
            :addShipmentDialogModalView.sync="addShipmentDialogModalView"
            @close="handleCloseEditShipmentDialog">

            <template v-slot:title>
                <div id="headline-custom-wrapper">
                    <span class="headline-custom">{{ "Edit Shipment" }}</span>
                    <!-- <span class="headline-custom-track">Track Shipment</span> -->
                </div>
            </template>

            <template v-slot:sidebar="{ mainContent }">
                <div class="d-flex flex-column first-column sidebar-item-main-wrapper">
                    <div class="d-flex align-center sidebar-items-wrapper" v-bind:key="`si-${key}`" v-for="(sidebarItem, key) in mainContent.sidebarItems">
                        <a @click.stop="mainContent.selectPage(sidebarItem)" :class="`d-flex sidebar-item align-center ${sidebarItem.selected ? 'selected' : ''}`">
                            <kenetic-icon :paddingTop="`${sidebarItem.icon==='general' ? 6: 0}`" :color="`${(sidebarItem.selected) ? '#0171A1' : '#4A4A4A'}`" v-if="sidebarItem.icon!==''" :iconName="sidebarItem.icon" :width="sidebarItem.width" :height="sidebarItem.height" />
                            <div style="margin-left: 13px;" class="sidebar-label">
                                {{ sidebarItem.label }}
                            </div>
                        </a>
                    </div>
                </div>
            </template>

            <template v-slot:actions="{ footer }">
                <div class="d-flex footer">
                    <v-btn :disabled="getEditShipmentLoading" @click.stop="footer.updateShipment" class="save-btn btn-blue" text>
                        <span >{{ "Save" }}</span>
                    </v-btn>
                    <v-btn class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click="footer.close">
                        <span>{{ "Cancel" }}</span>
                    </v-btn>
                </div>
            </template>
        </create-shipment-dialog>

		<delete-shipment-dialog
			:show="deleteShipmentDialogShow"
			title="Shipment"
			:deleting="getTrackingShipmentDeleteLoading"
			@close="closeDeleteShipmentDialog"
			@deleteShipment="deleteShipment"
			:ref_num="getShipmentDetails.shifl_ref"
			:id="getShipmentDetails.id">
			<template v-slot:message="{ ref_num }">
				<p>
					Do you want to delete the shipment ‘Ref#{{ ref_num }}’? Deleted
					shipment cannot be retrieved later.
				</p>
			</template>
		</delete-shipment-dialog>

		<booking-shipment-dialog
            :reference="`${getEditingDraftShipment ? 'formEditShipment' : 'formBookingShipment'}`"
            v-if="bookingShipmentDialogView"
            className="edit-shipment-dialog-wrapper"
            :show.sync="bookingShipmentDialogView"
            :bookingShipmentDialogView.sync="bookingShipmentDialogView"
            :isMobile="isMobile"
            :submitRequestModalView.sync="submitRequestModalView"
            :bookingRequestSubmittedModalView.sync="bookingRequestSubmittedModalView"
            :item="editedItem"
            :rules="editShipmentRules"
            :windowWidth="windowWidth"
            :isEdit.sync="isEdit"
            :addShipmentDialogModalView.sync="addShipmentDialogModalView"
            @close="handleCloseBookingShipmentDialog"
			@reloadShipments="callShipmentsAPI"
        >
            <template v-slot:title>
                <div id="headline-custom-wrapper">
                    <span v-if="1==2" class="headline-custom">{{ "Add Shipment" }}</span>
                    <span class="headline-custom-track booking">{{ isEdit ? "Edit Shipment" : "Create Booking Request" }}</span>
                </div>
            </template>
            <template v-slot:sidebar="{ mainContent }">
                <div style="padding-top: 16px !important;" class="d-flex flex-column first-column sidebar-item-main-wrapper">
                    <div :class="`d-flex align-center sidebar-items-wrapper ${sidebarItem.selected ? 'selected' : ''}`" v-bind:key="`si-${key}`" v-for="(sidebarItem, key) in mainContent.sidebarItems">
                        <a @click.stop="mainContent.selectPage(sidebarItem)" :class="`d-flex sidebar-item align-center ${sidebarItem.selected ? 'selected' : ''}`">
                            <kenetic-icon :paddingTop="`${sidebarItem.icon==='general' ? 6: 0}`" :color="`${(sidebarItem.selected) ? '#0171A1' : '#4A4A4A'}`" v-if="sidebarItem.icon!==''" :iconName="sidebarItem.icon" :width="sidebarItem.width" :height="sidebarItem.height" />
                            <div style="margin-left: 13px;" class="sidebar-label">
                                {{
                                    sidebarItem.label
                                }}
                            </div>
                        </a>
                    </div>
                </div>
            </template>
            <template v-slot:actions="{ footer }">
                <div class="d-flex footer">
                    <v-btn v-if="!getEditingShipment" style="margin-right: 8px;" :disabled="footer.createLoading" @click.stop="footer.createShipment" class="save-btn btn-blue" text >
                        <span >{{ "Submit Request" }}</span>
                    </v-btn>
                    <v-btn v-if="getEditingShipment" style="margin-right: 8px;" :disabled="footer.createLoading" @click.stop="footer.updateShipment" class="save-btn btn-blue" text >
                        <span >{{ "Save" }}</span>
                    </v-btn>
                    <v-btn v-if="!getEditingShipment" :disabled="footer.submitLoading" style="margin-right: 8px;" class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click="footer.saveAsDraft">
                        <span style="color: #0171A1;">{{ "Save as Draft" }}</span>
                    </v-btn>
                    <v-btn class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click="footer.close">
                        <span>{{ "Cancel" }}</span>
                    </v-btn>
                </div>
            </template>
        </booking-shipment-dialog>

		<ConfirmDialog :dialogData.sync="cancelBookingRequestDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Cancel Booking Request</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Are you sure you want to cancel the booking request?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="submitCancelBooking()" :disabled="getCancelShipmentLoading">
					<span v-if="getCancelShipmentLoading">Cancel Request Processing...</span>
					<span v-else>Cancel Request</span>
				</v-btn>
				<v-btn class="btn-white" text @click="cancelBookingRequestDialog = false">
					<span>Close</span>
				</v-btn>
			</template>
		</ConfirmDialog>
	</v-container>

</template>

<script>
import Milestones from "@/components/ShipmentComponents/Milestones/Milestones.vue";
import ShipmentInfo from "@/components/ShipmentInfo.vue";
import ShipmentDocuments from "@/components/ShipmentDocuments.vue";
import ShipmentBills from "@/components/ShipmentBill.vue";
import UploadDocumentsDialog from "../components/ShipmentComponents/Documents/UploadDocumentsDialog.vue";
import Map from "@/components/Map.vue";
import { mapActions, mapGetters } from "vuex";
import moment from "moment";
import _ from "lodash";
import CustomIcon from "@/components/Icons/CustomIcon";
import DeleteShipmentDialog from "@/components/Dialog/DeleteShipmentDialog";
import ShipmentProducts from "@/components/ShipmentProducts.vue";
import ShipmentNotes from "@/components/ShipmentNotes.vue";
import globalMethods from "../utils/globalMethods";
import CreateShipmentDialog from "@/components/Dialog/FormShipmentDialog/CreateShipmentDialog";
import KeneticIcon from "@/components/Icons/KeneticIcon";
import BookingShipmentDialog from '@/components/Dialog/FormShipmentDialog/BookingShipmentDialog'
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";

export default {
	components: {
		CreateShipmentDialog,
		KeneticIcon,
		Milestones,
		ShipmentInfo,
		ShipmentDocuments,
		Map,
		CustomIcon,
		DeleteShipmentDialog,
		UploadDocumentsDialog,
		ShipmentNotes,
		ShipmentProducts,
		ShipmentBills,
		BookingShipmentDialog,
		ConfirmDialog,
	},
	data: () => ({
		windowWidth: 0,
		tabs: ["Shipment Info","Products", "Documents", "Milestones", "Bills", "Notes"],
		tabs2: ["Shipments"],
		currentTab: "Shipment Info",
		currentTabMileStone: "Shipments",
		isMobile: false,
		addShipmentDialogModalView: false,
		editShipmentRules: {
			mbl_num: [
				(v) => !!v || "MBL number is required."
			],
			etd: [
				(v) => !!v || "ETD is required."
			],
			eta: [
				(v) => !!v || "ETA is required."
			],
			contact_number: [
				(v) => !!v || "Contact Number is required."
			]
		},
		deleteShipmentDialogShow: false,
		shipment_id: null,
		deletingShipment: false,
		title: "",
		detailsLoading: true,
		location_to_with_details: null,
		shipment_status: null,
		term_id: null,
		createShipmentDialogView: false,
		editShipmentDialogView: false,
		isEditShipment: false,
		deleteShipmentTooltipShow: false,
		shipmentDocumentsLoading: true,
		items: [
			{
				text: "Shipments",
				disabled: false,
				href: "/shipments",
			},
			{
				text: "#",
				disabled: true,
				href: "breadcrumbs_link_1",
			},
		],
		fetchMilestoneLoading: true,
		mbl_num: "",
		shipment_mode: "",
		shipment_type: "",
		isComponentLoading: false,
		dialogUploadDocs: false,
		dialogMessage: false,
		// 
		trackingStatusMap: [
            {
                class: 'manual-tracking',
                value: 'Manual Tracking'
            },
            {
                class: 'auto-tracking',
                value: 'Auto Tracking'
            },
            {
                class: 'auto-tracked',
                value: 'Auto Tracked'
            },
            {
                class: 'manually-tracked',
                value: 'Manually Tracked'
            },
            {
                class: 'not-tracking',
                value: 'Not Tracking'
            },
            {
                class: 'past-last-free-day',
                value: 'Past Last Free Day'
            },
            {
                class: 'discharged-released',
                value: 'Discharged - released'
            },
            {
                class: 'in-transit-hold',
                value: 'In transit - hold'
            },
            {
                class: 'in-transit-released',
                value: 'In transit - released'
            },
            {
                class: 'discharged-hold',
                value: 'Discharged - Hold'
            },
            {
                class: 'partially-discharged',
                value: 'Partially discharged'
            }
        ],
		bookingShipmentDialogView: false,
		submitRequestModalView: false,
		bookingRequestSubmittedModalView: false,
		isEdit: false,
		editedItem: {
            Departure: "",
            Arrival: "",
            Suppliers: "",
            PO: "",
            Status: "",
            id: "",
            mode: "",
            container_num_list: ""
        },
		cancelBookingRequestDialog: false,
	}),
	methods: {
		...mapActions([
			"fetchShipmentDetails",
			"fetchMilestones",
			"setMilestonesNewLoading",
			"setMilestonesOtherEvents",
			"setShipmentLoading",
			"fetchShipmentDocuments",
			"clearShipmentDocuments",
			"fetchShipmentSuppliers",
			"fetchTerms",
			"fetchContainers",
			"fetchScheduleOptions",
			"cancelShipment"
		]),
		...globalMethods,
		ucFirst(str) {
            let pieces = str.split(" ")
            for ( let i = 0; i < pieces.length; i++ ) {
                let j = pieces[i].charAt(0).toUpperCase()
                pieces[i] = j + pieces[i].substr(1)
            }
            return pieces.join(" ")
        },
		handleCloseCreateShipmentDialog() {
            this.createShipmentDialogView = false
        },
		handleCloseEditShipmentDialog() {
			this.isEditShipment = false
			this.editShipmentDialogView = false
			this.createShipmentDialogView = false
		},
		formatDate(value) {
			// let setDate = moment.utc(value).local().format('h:mm:ssA, MMM DD, YYYY')
			let setDate = moment.utc(value).local().format('h:mmA, MMM DD, YYYY')
			return ( setDate ==='Invalid date') ? 'N/A' : setDate
		},
		async handleDocumentsChange(page) {
			try {
				this.shipmentDocumentsLoading = true;
				await this.fetchShipmentDocuments({
					shipment_id: this.shipment_id,
					page,
				});
				this.shipmentDocumentsLoading = false;
			} catch (e) {
				console.log(e);
			}
		},
		toggleDeleteShipmentTooltip() {
			this.deleteShipmentTooltipShow = !this.deleteShipmentTooltipShow;
		},
		async deleteShipment(id) {
			try {
				await this.$store.dispatch("trackingShipment/delete", id);
				this.closeDeleteShipmentDialog();
				this.setShipmentLoading(true);
				this.$router.push("/shipments");
				setTimeout(() => {
					this.notificationGlobal({
						className: "product-toast shipment-delete-toast",
						title: "",
						icon: "trash-can",
						message: "Shipment has been deleted.",
						position: "bottomCenter",
						timeout: 5000,
						is_success: true,
					});
				}, 2000);
			} catch (e) {
				this.notificationError("An error occured while deleting shipment.");
			}
		},
		closeDeleteShipmentDialog() {
			this.deleteShipmentDialogShow = false;
		},
		showAddShipmentDialog() {
			this.isEditShipment = false
			this.createShipmentDialogView = true
			this.editShipmentDialogView = false
		},
		showEditShipmentDialog() {
			this.isEditShipment = true
			this.createShipmentDialogView = false
			this.editShipmentDialogView = true
		},
		showDeleteShipmentDialog() {
			this.deleteShipmentDialogShow = true;
			this.deleteShipmentTooltipShow = false;
		},
		openUploadDialog() {
			this.dialogUploadDocs = true;
			this.dialogMessage = true;
		},
		closeUploadDialog() {
			this.dialogUploadDocs = false;
		},
		setMainTab(value) {
			this.currentTab = value;
		},
		setMilestoneTab(value) {
			this.currentTabMileStone = value;
		},
		onResize() {
			this.windowWidth = window.innerWidth
			if (window.innerWidth < 960) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		async loadShipmentMetaData() {
			this.setMilestonesOtherEvents([]);
			this.setMilestonesNewLoading(true);

			//synchronous call for fetching terms and fetching containers
			if (typeof this.getShipmentTerms !== "undefined" &&
				this.getShipmentTerms.length === 0)
				this.fetchTerms()

			if (typeof this.getShipmentContainerSizes !== "undefined" &&
				this.getShipmentContainerSizes.length === 0)
				this.fetchContainers()

			try {
				await this.fetchShipmentDetails(this.shipment_id)
				this.fetchMilestones(this.getShipmentDetails.mbl_num)
				.then(() => {
					this.fetchMilestoneLoading = false
				})
				.catch((e) => {
					this.fetchMilestoneLoading = false
					console.log(e)
				})

				this.mbl_num = this.getShipmentDetails.mbl_num
				this.detailsLoading = false;
				this.title = this.getShipmentDetails.shifl_ref;

				let etaDetails =
					this.getShipmentDetails.eta !== null
						? ", ETA " +
							moment.utc(this.getShipmentDetails.eta).format("MMM DD, YYYY")
						: "";
				let to_name =
					this.getShipmentDetails.location_to_name !== null &&
					this.getShipmentDetails.location_to_name !== ""
						? this.getShipmentDetails.location_to_name
						: "";

				this.location_to_with_details =
					to_name !== "" ? `${to_name}${etaDetails}` : "";

				// get shipment mode
				this.getShipmentDetails.schedules_group_bookings =
					typeof this.getShipmentDetails.schedules_group_bookings !==
						"undefined" &&
					this.getShipmentDetails.schedules_group_bookings !== "" &&
					this.getShipmentDetails.schedules_group_bookings !== null &&
					JSON.parse(this.getShipmentDetails.schedules_group_bookings.length) >
						0
						? JSON.parse(this.getShipmentDetails.schedules_group_bookings)
						: [];

				if (this.getShipmentDetails.schedules_group_bookings !== "undefined" &&
					this.getShipmentDetails.schedules_group_bookings !== "") {
					this.shipment_mode =
						typeof _.find(
							this.getShipmentDetails.schedules_group_bookings,
							(e) => e.is_confirmed == 1
						) !== "undefined"
							? _.find(
									this.getShipmentDetails.schedules_group_bookings,
									(e) => e.is_confirmed == 1
								).mode
							: null;

					this.shipment_type =
						typeof _.find(
							this.getShipmentDetails.schedules_group_bookings,
							(e) => e.is_confirmed == 1
						) !== "undefined"
							? _.find(
									this.getShipmentDetails.schedules_group_bookings,
									(e) => e.is_confirmed == 1
								).type
							: (this.getShipmentDetails.selected_schedule_type != undefined && this.getShipmentDetails.selected_schedule_type != '') ? this.getShipmentDetails.selected_schedule_type : '';
				}

				// new shipment status - show status_v2 if not empty, otherwise show shipment_status
				this.shipment_status = 
					this.getShipmentDetails.status_v2 !== '' && this.getShipmentDetails.status_v2 !== null 
						? this.getShipmentDetails.status_v2 
						: this.getShipmentDetails.shipment_status;
			} catch (e) {
				console.log(e);
				this.detailsLoading = false;
			}

			//set current page
			this.$store.dispatch("page/setPage", "shipments");
		},
		getTrackingDetailsLocationWithDetails(details) {
			if (details !== null) {
				let location_to =
					details.port_of_discharge_name !== null
						? details.port_of_discharge_name
						: null;
				//let eta = details.pod_eta_at !== null ? details.pod_eta_at : null;
				let eta = details.pod_eta_at !==null ? details.pod_eta_at : details.pod_ata_at
				let etaDetails =
					eta !== null ? ", ETA " + moment.utc(eta).format("MMM DD, YYYY") : "";
				let finalLocationWithInfo =
					location_to !== null ? `${location_to}${etaDetails}` : "";

				return finalLocationWithInfo;
			}
		},
		async fetchShipmentDocumentsAfter(payload) {
			this.clearShipmentDocuments();
			this.shipmentDocumentsLoading = true;
			await this.fetchShipmentDocuments(payload);
			this.selected = [];
			this.shipmentDocumentsLoading = false;
		},
		getShipmentStatusClass(item, type) {
            let { tracking_status } = item

            let setValue = (type === 'tracking') ? tracking_status : this.shipment_status
            let setClass = _.find(this.trackingStatusMap, e => e.value === setValue)
            setClass = (typeof setClass!=='undefined') ? setClass.class : this.shipment_status
            
            if (setClass === 'not-tracking' && this.shipment_status === 'Past last free day')
                setClass = 'not-tracking-past'
            return setClass
        },
		EditBookingShipmentDialog(){
			this.$store.dispatch("page/setEditingDraftShipment", true);
			this.editedItem = Object.assign({}, this.getShipmentDetails);
			this.isEdit = true
			this.bookingShipmentDialogView = true;
		},
		handleCloseBookingShipmentDialog() {
            this.bookingShipmentDialogView = false
            this.$store.dispatch('page/setEditingDraftShipment', false)
        },
		async callShipmentsAPI(){
			// await this.fetchShipmentDetails(this.shipment_id)
			// await this.fetchScheduleOptions(this.shipment_id)
			// await this.fetchShipmentSuppliers(this.shipment_id)
			this.$router.go();
		},
		cancelBooking() {
			console.log('cancel booking function called');
			this.cancelBookingRequestDialog = true;
		},
		submitCancelBooking() {
			if(this.shipment_id && this.shipment_id !== 0) {
				let payloadObject = {'shipmentId': this.shipment_id, 'type': 'booking'}
				this.cancelShipment(payloadObject)
					.then((response) => {
						this.cancelBookingRequestDialog = false;
						this.notificationErrorCustom(response.data.message);
					})
					.catch((e) => {
						console.log(e);
						this.cancelBookingRequestDialog = false;
                        this.notificationErrorCustom('SOMETHING WENT WRONG!');
					})
			}
		}
	},
	async mounted() {
		this.windowWidth = window.innerWidth
		// console.log(this.windowWidth)
		this.shipment_id = this.$route.params.id;
		//synchronous call for loading shipment meta and suppliers
		this.loadShipmentMetaData()
		this.fetchShipmentSuppliers(this.shipment_id)

		// this.$store.dispatch("suppliers/fetchSuppliers")
	},
	async updated() {
		if (this.shipment_id !== this.$route.params.id) {
			this.shipment_id = this.$route.params.id;
			this.loadShipmentMetaData();
		}
	},
	created() {},
	computed: {
		...mapGetters([
			"getShipmentDocumentsPage",
			"getShipmentDocumentsLoading",
			"getShipmentDetailsLoading",
			"getShipmentDetails",
			"getMilestonesNewLoading",
			"getMilestonesLoading",
			"getMilestonesAttributes",
			"getShipmentContainerSizes",
			"getShipmentTerms",
			"getEditShipmentLoading",
			"getCancelShipmentLoading"
		]),
		minDate() {
			return moment().format('YYYY-MM-DD')
		},
		oblRequired() {
			return this.getShipmentDetails.shipment_suppliers?.map((item) => {
				return item.obl_filled === false ? false : true;
			});
		},
		commercialDocuments() {
			return this.getShipmentDetails.shipment_suppliers?.map((item) => {
				return item.commercial_documents_filled === false ? false : true;
			});
		},
		getTrackingShipmentDeleteLoading() {
			return this.$store.getters["trackingShipment/getDeleteLoading"];
		},
		getFilteredShipmentDetails() {
			let shipmentDetails = this.$store.getters.getShipmentDetails;
			shipmentDetails.external_shipment = 0;
			shipmentDetails.external_shipment_tracking = 0;
			if (!Array.isArray(shipmentDetails.po_list)) {
				shipmentDetails.po_list = [];
			}
			if (shipmentDetails.is_tracking_shipment == 1) {
				shipmentDetails.external_shipment = 1;
			}

			if (typeof shipmentDetails.terminal_fortynine !== "undefined" &&
				shipmentDetails.terminal_fortynine !== null &&
				shipmentDetails.terminal_fortynine.attributes !== "undefined") {
					
				let getAttributes = shipmentDetails.terminal_fortynine.attributes;
				let newAttributes = {};
				if (getAttributes !== null && getAttributes !== "") {
					try {
						newAttributes = JSON.parse(getAttributes);
					} catch (e) {
						console.log(e);
					}
				}

				if (typeof newAttributes.created_at !== "undefined" &&
					shipmentDetails.is_tracking_shipment == 1) {
					shipmentDetails.external_shipment_tracking = 1;
				}
			}

			return shipmentDetails;
		},
		getEditingDraftShipment(){
          return this.$store.getters['page/getEditingDraftShipment']
        },
		getEditingShipment() {
            return this.$store.getters['page/getEditingShipment']
        },
	},
	watch: {
		title() {
			this.items[1].text = this.title;
		},
	},
};
</script>

<style lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap");
@import "../assets/scss/pages_scss/shipment/shipmentDetails.scss";
@import "../assets/scss/buttons.scss";
</style>
