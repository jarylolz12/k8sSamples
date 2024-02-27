<!-- @format -->

<template>
	<div
		:class="
			`shipment-tabs-table-wrapper-component ${
				activeShipmentTab === 0 ? 'shipment-tabs-pending-main-wrapper' : ''
			}`
		"
	>
		<!-- FOR DESKTOP -->
		<v-data-table
			:headers="tableHeaders"
			:items="getCurrentShipmentsData"
			mobile-breakpoint="769"
			:page="getCurrentShipmentPage"
			:items-per-page="getShipmentItemsPerPage"
			item-key="name"
			class="pending-header pending-header-quote"
			v-bind:class="{
				'no-data-table':
					typeof getCurrentShipmentsData !== 'undefined' &&
					getCurrentShipmentsData.length === 0,
				'no-current-pagination': getCurrentPaginationCountClass(),
			}"
			:id="getCurrentId"
			hide-default-footer
			style="box-shadow: none !important"
			@page-count="pageCount = $event"
			@click:row="handleClick"
			v-if="activeShipmentTab == 0 && !isMobile"
			ref="my-table"
		>
			<template v-slot:[`item.mode`]="{ item }">
				<div
					v-if="item.multipleModes.length > 0"
					class="pending-quote-actions-wrapper k-relative"
				>
					<!-- mode-wrapper class -->
					<div class="k-pr-5 k-flex k-items-center shipment-mode-type k-pl-12">
						<span
							class="k-flex k-mr-4"
							:key="k"
							v-for="(m, k) in item.multipleModes"
						>
							<!-- <custom-icon :marginLeft="`${(m==='ship') ? 14 : 0}`" :iconName="m" :width="`${(m==='ship') ? 20 : 16}`" :height="`${(m==='ship') ? 20 : 14}`" color="#819FB2"></custom-icon> -->

								<img
									v-if="m === 'ship'"
									src="@/assets/icons/ocean.svg"
									width="20px"
									height="20px"
								/>
								<img
									v-if="m === 'air'"
									src="@/assets/icons/airplane.svg"
									width="20px"
									height="20px"
								/>
								<img
									v-if="m === 'truck'"
									src="@/assets/icons/truck.svg"
									width="20px"
									height="20px"
								/>
						</span>

            <img v-if="item.type == 'LCL'" src="@/assets/images/small-container.svg" width="20px" height="20px" />
            <img v-if="item.type == 'Air'" src="@/assets/images/airplane-shipment.svg" width="20px" height="20px" />
            <img v-if="item.type == 'FCL'" src="@/assets/images/big-container.svg" width="20px" height="20px" />
            <span style="padding-bottom:2px;" v-if="item.type == 'FCL' && item.container_num_list.length !== 0">
                ({{ item.container_num_list.length }})
            </span>
					</div>
				</div>

				<div v-else class="k-flex k-items-center justify-center">
					<div class="k-flex">
						<span>N/A</span>
					</div>
				</div>
			</template>

			<template v-slot:no-data>
				<div
					class="no-data-preloader"
					v-if="
						getAllExpiredShipmentsLoading ||
							getAllSnoozeShipmentsLoading ||
							getAllPendingQuoteShipmentsLoading ||
							getAllPendingShipmentsLoading
					"
				>
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>
				<!-- <div v-if="(!getShipmentLoadingStatus && !getAllExpiredShipmentsLoading && !getAllPendingShipmentsLoading) && (pending.length == 0 || expired.length == 0)" class="no-data-wrapper"> -->
				<div
					v-if="
						!getAllExpiredShipmentsLoading &&
							!getAllPendingShipmentsLoading &&
							!getAllPendingQuoteShipmentsLoading &&
							!getAllSnoozeShipmentsLoading &&
							getCurrentShipmentsData.length === 0
					"
					class="no-data-wrapper"
				>
					<div class="no-data-icon">
						<img src="@/assets/icons/noShipmentData.svg" alt="" width="65px" />
					</div>

					<div class="no-data-heading" v-if="search === ''">
						<p v-if="activeShipmentTab == 0">
							<span v-if="pendingShipmentTab === 0">
								No Draft Shipments
							</span>
							<span v-if="pendingShipmentTab === 1">
								No Pending Quote Shipments
							</span>
							<span v-if="pendingShipmentTab === 2">
								No Pending Shipments
							</span>
							<span v-if="pendingShipmentTab === 3">
								No Snooze Shipments
							</span>
							<span v-if="pendingShipmentTab === 4">
								No Expired Shipments
							</span>
						</p>
					</div>
					<div class="no-data-heading" v-if="search !== ''">
						<p v-if="activeShipmentTab == 0">
							<span v-if="pendingShipmentTab === 0">
								No Draft Shipments found. Try searching another keyword.
							</span>
							<span v-if="pendingShipmentTab === 1">
								No Pending Quote Shipments found. Try searching another keyword.
							</span>
							<span v-if="pendingShipmentTab === 2">
								No Pending Shipments found. Try searching another keyword.
							</span>
							<span v-if="pendingShipmentTab === 3">
								No Snooze Shipments found. Try searching another keyword.
							</span>
							<span v-if="pendingShipmentTab === 4">
								No Expired Shipments found. Try searching another keyword.
							</span>
						</p>
					</div>
				</div>
			</template>

			<!-- Snooze until -->
			<template v-slot:[`item.snooze_date_at`]="{ item }">
				<div class="reference">
					<div class="snooze-items">
						{{ beautify(item.snooze_date_at) }}
					</div>
				</div>
			</template>

			<!-- Updated At -->
			<template v-slot:[`item.updated_at`]="{ item }">
				<div class="reference">
					<!-- static data only for name -->
					<span class="name">{{ item.updated_by_name ? item.updated_by_name : 'N/A'}}</span>
					<div class="pending-quote-items" style="color: #6D858F;">
						{{ beautify(item.updated_at) }}
					</div>
				</div>
			</template>

			<!-- Location From -->
			<template v-slot:[`item.location_from_name`]="{ item }">
				<div class="reference">
					<div class="pending-quote-items">
						{{
							item.location_from_name !== "Not specified" &&
							item.location_from_name !== "Not Specified"
								? item.location_from_name
								: "N/A"
						}}
					</div>
				</div>
			</template>

			<template v-slot:[`item.location_to_name`]="{ item }">
				<div class="reference">
					<div class="pending-quote-items">
						{{
							item.location_to_name !== "Not specified" &&
							item.location_to_name !== "Not Specified"
								? item.location_to_name
								: "N/A"
						}}
					</div>
				</div>
			</template>

			<!-- Reference ID -->
			<template v-slot:[`item.ReferenceID`]="{ item }">
				<div class="departure-content-wrapper">
					<div class="flag-wrapper">
						<p style="margin-bottom: 0px">
							{{ item.ReferenceID }}
						</p>
					</div>

					<div>
						<p style="color: #6D858F; margin-top: 2px;">
							{{
								item.customer_reference_number !== "null" &&
								item.customer_reference_number !== null
									? item.customer_reference_number
									: "N/A"
							}}
						</p>
					</div>
				</div>
			</template>

			<!-- Departure -->
			<template v-slot:[`item.Departure`]="{ item }">
				<div>
					<div class="flag-wrapper">
						<p style="margin-bottom: 0px">
							{{
								item.Departure.location !== "Not specified"
									? item.Departure.location
									: "N/A"
							}}
						</p>
					</div>

					<div>
						<p style="color: #0171A1; margin-top: 2px;">
							{{
								item.Departure.etd !== "Not Specified"
									? item.Departure.etd
									: "N/A"
							}}
						</p>
					</div>
				</div>
			</template>

			<!-- Arrival -->
			<template v-slot:[`item.Arrival`]="{ item }">
				<div class="arrival-wrapper">
					<div>
						<div class="flag-wrapper">
							<p style="margin-bottom: 0px">
								{{
									item.Arrival.location !== "Not specified"
										? item.Arrival.location
										: "N/A"
								}}
							</p>
						</div>
						<p style="color: #0171A1; margin-top: 2px;">
							{{
								item.Arrival.eta !== "Not Specified" ? item.Arrival.eta : "N/A"
							}}
						</p>
					</div>
				</div>
			</template>

			<!-- Suppiers -->
			<template v-slot:[`item.Suppliers`]="{ item }">
				<!-- <div class="supplier-desktop" :style="activeShipmentTab == 0 ? 'max-width: 350px;' : 'max-width: 200px;'"> -->

				<div
					class="supplier-desktop"
					:class="activeShipmentTab == 0 ? 'active-tab' : ''"
				>
					<span v-for="(name, index) in item.Suppliers" :key="index">
						<span>{{ name }}</span
						><span v-if="index + 1 < item.Suppliers.length">, </span>
					</span>

					<span v-if="item.Suppliers == null || item.Suppliers.length == 0">
						<span>N/A</span>
					</span>
				</div>
			</template>

			<!-- PO -->
			<template v-slot:[`item.PO`]="{ item }">
				<div class="po-num-desktop">
					<!-- <p style="max-width: 150px;"> -->
					<p>
						<span v-for="(name, index) in item.po_list" :key="index">
							{{ name
							}}<template v-if="index + 1 < item.po_list.length">, </template>
						</span>
					</p>
				</div>

				<div v-if="item.po_list == null">
					<p>N/A</p>
				</div>
			</template>

			<!-- CARGO DATES -->
			<template v-slot:[`item.cargo_ready_date`]="{ item }">
				<div class="po-num-desktop">
					<p>
						<span v-for="(date, index) in item.cargo_ready_date" :key="index">
							{{ date
							}}<template v-if="index + 1 < item.cargo_ready_date.length"
								>,
							</template>
						</span>
					</p>
				</div>

				<div v-if="item.cargo_ready_date == null">
					<p>N/A</p>
				</div>
			</template>

			<!-- Status -->
			<template v-slot:[`item.Status`]="{ item }">
				<div class="status" :class="getStatusClass(item.Status)">
					<v-chip v-html="getStatus(item.Status)"></v-chip>
				</div>
			</template>

			<template v-slot:[`item.actions`]="{ item, index }">
				<div
					class="request-schedules mr-1"
					v-if="item.Status === 'Expired' && 1 == 2"
				>
					<button
						class="btn-white expired"
						@click.stop="requestSchedule(item.id)"
						:disabled="loadingNewScheds && currentIdRequestScheds == item.id"
					>
						<span
							class="k-tracking-normal"
							v-if="item.is_schedule_requested === 0"
						>
							{{
								loadingNewScheds && currentIdRequestScheds == item.id
									? "Requesting..."
									: "Request Again"
							}}
						</span>

						<span v-if="item.is_schedule_requested === 1">
							{{
								loadingNewScheds && currentIdRequestScheds == item.id
									? "Requesting..."
									: "Schedule Requested"
							}}
						</span>
					</button>
				</div>
				<div
					class="pending-quote-actions-wrapper k-relative mr-1"
					style=""
					v-if="pendingShipmentTab == 0 || pendingShipmentTab == 4"
				>
					<!-- <div class="k-pr-5">
                        <v-btn @click.stop="handleShowSnoozeDialog(item)" class="k-p-0 pending-quote-actions-btn k-pl-4" text>
                            <custom-icon iconName="notification" marginTop=6 width="20" height="20" color="#0171A1"></custom-icon>
                        </v-btn>
                    </div> -->

					<!-- <div class="edit-shipment pr-0">
						<v-btn
							@click.stop="(e) => editShipment(item, e)"
							class="k-tracking-normal pending-quote-actions-btn edit"
							text
						>
							<span>Edit</span>
						</v-btn>
					</div> -->

					<div :class="`three-dots-wrapper-${index}`">
						<v-menu
							:close-on-click="closeOnClick"
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
								<!-- <v-list-item @click="handleView(item)">
                                    <v-list-item-title class="k-fw-sm k-font-inter-regular">
                                        View
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="handleShowSnoozeDialog(item)">
                                    <v-list-item-title class="k-fw-sm k-font-inter-regular">
                                        Snooze
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item v-if="1==2" @click="handleCancel(item)">
                                    <v-list-item-title class="k-fw-sm k-font-inter-regular k-red">
                                        Cancel
                                    </v-list-item-title>
                                </v-list-item> -->

								<v-list-item
									v-if="
										item.tab_status === 'pending_quote' &&
											currentUser !== null &&
											currentUser.default_customer_id == item.customer_id &&
											item.is_tracking_shipment == 0
									"
									@click="(e) => handleMarkBookedExternal(item, e)"
								>
									<v-list-item-title class="k-fw-sm k-font-inter-regular">
										Mark Booked External
									</v-list-item-title>
								</v-list-item>
								<v-list-item @click="handleShowSnoozeDialog(item)">
									<v-list-item-title class="k-fw-sm k-font-inter-regular">
										Snooze
									</v-list-item-title>
								</v-list-item>
								<v-list-item v-if="item.tab_status === 'pending_quote'">
									<v-list-item-title class="k-fw-sm k-font-inter-regular">
										Request External Quote
									</v-list-item-title>
								</v-list-item>
								<v-list-item>
									<v-list-item-title class="k-fw-sm k-font-inter-regular k-red" @click="cancelBooking(item.id)">
										Cancel Request
									</v-list-item-title>
								</v-list-item>
							</v-list>
						</v-menu>
					</div>
				</div>
				<div
					class="pending-quote-actions-wrapper k-relative mr-1"
					v-if="pendingShipmentTab == 1"
				>
					<!-- <div class="k-pr-5">
                        <v-btn @click.stop="handleView(item)" class="k-tracking-normal pending-quote-actions-btn view" text>
                            <span>View</span>
                        </v-btn>
                    </div>
                    <div class="k-pr-5">
                        <v-btn @click.stop="handleShowSnoozeDialog(item)" class="k-p-0 pending-quote-actions-btn k-pl-4" text>
                            <custom-icon marginTop=6 iconName="notification" width="20" height="20" color="#0171A1"></custom-icon>
                        </v-btn>
                    </div> -->

					<div
						v-if="
							1 == 2 &&
								currentUser !== null &&
								currentUser.default_customer_id == item.customer_id
						"
						class="edit-shipment pr-0"
					>
						<v-btn
							@click.stop="(e) => editShipment(item, e)"
							class="k-tracking-normal pending-quote-actions-btn edit"
							text
						>
							<span>Edit</span>
						</v-btn>
					</div>

					<div :class="`three-dots-wrapper-${index}`">
						<div class="k-relative">
							<v-menu
								:close-on-click="closeOnClick"
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
									<!-- <v-list-item @click="handleView(item)">
                                        <v-list-item-title class="k-fw-sm k-font-inter-regular">
                                            View
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item @click="handleShowSnoozeDialog(item)">
                                        <v-list-item-title class="k-fw-sm k-font-inter-regular">
                                            Snooze
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item v-if="1==2" @click="handleCancel(item)">
                                        <v-list-item-title class="k-fw-sm k-font-inter-regular k-red">
                                            Cancel
                                        </v-list-item-title>
                                    </v-list-item> -->

									<v-list-item
										@click="(e) => handleMarkBookedExternal(item, e)"
										v-if="
											item.tab_status === 'pending_quote' &&
												currentUser !== null &&
												currentUser.default_customer_id == item.customer_id &&
												item.is_tracking_shipment == 0
										"
									>
										<v-list-item-title class="k-fw-sm k-font-inter-regular">
											Mark Booked External
										</v-list-item-title>
									</v-list-item>
									<v-list-item @click="handleShowSnoozeDialog(item)">
										<v-list-item-title class="k-fw-sm k-font-inter-regular">
											Snooze
										</v-list-item-title>
									</v-list-item>
									<v-list-item>
										<v-list-item-title
											class="k-fw-sm k-font-inter-regular k-red" @click="cancelBooking(item.id)"
										>
											Cancel Request
										</v-list-item-title>
									</v-list-item>
								</v-list>
							</v-menu>
						</div>
					</div>
				</div>
				<div
					class="pending-quote-actions-wrapper k-relative mr-1"
					style=""
					v-if="pendingShipmentTab == 3"
				>
					<div class="pr-0">
						<v-btn
							@click.stop="requestSchedule(item.id)"
							class="pending-quote-actions-btn view"
							text
						>
							<span
								class="k-tracking-normal"
								v-if="item.is_schedule_requested === 0"
							>
								{{
									loadingNewScheds && currentIdRequestScheds == item.id
										? "Requesting..."
										: "Request Again"
								}}
							</span>

							<span
								class="k-tracking-normal"
								v-if="item.is_schedule_requested === 1"
							>
								{{
									loadingNewScheds && currentIdRequestScheds == item.id
										? "Requesting..."
										: "Schedule Requested"
								}}
							</span>
						</v-btn>
					</div>
					<div class="k-relative">
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
								<v-list-item @click="handleView(item)">
									<v-list-item-title class="k-fw-sm k-font-inter-regular">
										View
									</v-list-item-title>
								</v-list-item>
								<v-list-item v-if="1 == 2">
									<v-list-item-title class="k-fw-sm k-font-inter-regular k-red" @click="cancelBooking(item.id)">
										Cancel
									</v-list-item-title>
								</v-list-item>
							</v-list>
						</v-menu>
					</div>
				</div>
				<div
					class="pending-quote-actions-wrapper k-relative mr-1"
					style=""
					v-if="pendingShipmentTab == 2"
				>
					<div class="pr-0">
						<v-btn
							@click.stop="handleUnSnoozeShipment(item)"
							class="pending-quote-actions-btn unsnooze"
							text
							v-if="item.snooze_date_at !== null"
						>
							<span class="k-tracking-normal" v-if="!item.unsnoozing">
								{{ "Unsnooze" }}
							</span>
							<span class="k-tracking-normal" v-if="item.unsnoozing">
								{{ "Unsnoozing..." }}
							</span>
						</v-btn>
					</div>
					<div :class="`three-dots-wrapper-${index}`">
						<v-menu
							:close-on-click="closeOnClick"
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
								<v-list-item @click="handleView(item)">
									<v-list-item-title class="k-fw-sm k-font-inter-regular">
										View
									</v-list-item-title>
								</v-list-item>
								<v-list-item v-if="1 == 2" @click="handleCancel(item)">
									<v-list-item-title class="k-fw-sm k-font-inter-regular k-red" @click="cancelBooking(item.id)">
										Cancel Request
									</v-list-item-title>
								</v-list-item>
							</v-list>
						</v-menu>
					</div>
				</div>
			</template>
		</v-data-table>

		<!-- FOR MOBILE -->
		<v-data-table
			:headers="tableHeaders"
			:items="getCurrentShipmentsData"
			mobile-breakpoint="769"
			:page="getCurrentShipmentPage"
			:items-per-page="getShipmentItemsPerPage"
			item-key="name"
			class="table-mobile pending-header-mobile"
			v-bind:class="{
				'no-data-table':
					typeof getCurrentShipmentsData !== 'undefined' &&
					getCurrentShipmentsData.length === 0,
				'no-current-pagination': getCurrentPaginationCountClass(),
			}"
			:id="getCurrentId"
			hide-default-footer
			style="box-shadow: none !important"
			@page-count="pageCount = $event"
			@click:row="handleClick"
			v-if="activeShipmentTab == 0 && isMobile"
			ref="my-table"
		>
			<template v-slot:no-data>
				<div
					class="no-data-preloader"
					v-if="
						getAllExpiredShipmentsLoading ||
							getAllPendingShipmentsLoading ||
							getAllPendingQuoteShipmentsLoading ||
							getAllSnoozeShipmentsLoading
					"
				>
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>
				<div v-else class="no-data-wrapper">
					<div class="no-data-icon">
						<img src="@/assets/icons/noShipmentData.svg" alt="" width="65px" />
					</div>

					<div class="no-data-heading" v-if="search === ''">
						<p v-if="activeShipmentTab == 0">
							<span v-if="pendingShipmentTab === 0">
								No Pending Quote Shipments
							</span>
							<span v-if="pendingShipmentTab === 1">
								No Pending Shipments
							</span>
							<span v-if="pendingShipmentTab === 2">
								No Snooze Shipments
							</span>
							<span v-if="pendingShipmentTab === 3">
								No Expired Shipments
							</span>
							<span v-if="pendingShipmentTab === 4">
								No Draft Shipments
							</span>
						</p>
					</div>

					<div class="no-data-heading" v-if="search !== ''">
						<p v-if="activeShipmentTab == 0">
							<span v-if="pendingShipmentTab === 0">
								No Pending Quote Shipments found. <br />
								Try searching another keyword.
							</span>
							<span v-if="pendingShipmentTab === 1">
								No Pending Shipments found. <br />
								Try searching another keyword.
							</span>
							<span v-if="pendingShipmentTab === 2">
								No Snooze Shipments found. <br />
								Try searching another keyword.
							</span>
							<span v-if="pendingShipmentTab === 3">
								No Expired Shipments found. <br />
								Try searching another keyword.
							</span>
							<span v-if="pendingShipmentTab === 4">
								No Draft Shipments found. <br />
								Try searching another keyword.
							</span>
						</p>
					</div>
				</div>
			</template>

			<!-- Reference ID and Status -->
			<template v-slot:[`item.ReferenceID`]="{ item }">
				<div class="table-mobile-data mobile-reference">
					<div
						class="mobile-reference-content"
						:class="pendingShipmentTab === 2 ? 'snooze' : ''"
					>
						<span class="k-flex">{{ item.ReferenceID }}</span>
						<span
							v-if="item.multipleModes.length > 0"
							class="k-flex k-justify-center k-ml-4"
						>
							<span
								:key="k"
								v-for="(m, k) in item.multipleModes"
								:class="`k-flex k-ml-5 ${m == 'ship' ? 'k-mt-5' : ''}`"
							>
								<!-- <custom-icon :iconName="m" width="18" height="18" color="#819FB2"></custom-icon> -->

								<span class="mr-1">
									<img
										v-if="m === 'ship'"
										src="@/assets/icons/ocean.svg"
										width="20px"
										height="18px"
									/>
									<img
										v-if="m === 'air'"
										src="@/assets/icons/airplane.svg"
										width="20px"
										height="18px"
									/>
									<img
										v-if="m === 'truck'"
										src="@/assets/icons/truck.svg"
										width="20px"
										height="18px"
									/>
								</span>
							</span>
						</span>

						<img
							v-if="item.type == 'LCL'"
							src="@/assets/images/small-container.svg"
							width="20px"
							height="18px"
						/>
						<img
							v-if="item.type == 'Air'"
							src="@/assets/images/airplane-shipment.svg"
							width="20px"
							height="18px"
						/>
						<img
							v-if="item.type == 'FCL'"
							src="@/assets/images/big-container.svg"
							width="20px"
							height="18px"
						/>
						<span
							v-if="item.type == 'FCL' && item.container_num_list.length !== 0"
						>
							({{ item.container_num_list.length }})
						</span>

						<span class="is-tracking-shipment" v-if="item.is_tracking_shipment">
							<span>Tracking</span>
						</span>
					</div>

					<div class="k-flex ml-2">
						<span
							v-if="
								pendingShipmentTab == 0 ||
									pendingShipmentTab == 1 ||
									pendingShipmentTab == 3
							"
							style="color: #819FB2; font-family: 'Inter-Medium', sans-serif;"
							class="k-flex k-items-center k-mr-5 k-f-12"
						>
							{{ beautify(item.updated_at) }}</span
						>
						<span v-if="pendingShipmentTab == 2" class="k-flex k-items-center">
							<custom-icon
								iconName="notification"
								marginTop="2"
								width="20"
								height="20"
								color="#EB9B26"
							></custom-icon>
						</span>
						<span
							v-if="pendingShipmentTab == 2"
							class="k-flex k-f-12 k-items-center k-mr-5 k-orange"
						>
							{{ "Until " + beautify(item.snooze_date_at) }}
						</span>
						<span>
							<v-menu
								:close-on-click="closeOnClick"
								offset-y
								bottom
								left
								content-class="pending-dropdown-container"
							>
								<template v-slot:activator="{ on, attrs }">
									<button
										v-bind="attrs"
										v-on="on"
										class="three-dots-container k-h-26"
									>
										<custom-icon
											iconName="three-dots"
											color="#0171A1"
											width="11"
											height="3"
										/>
									</button>
								</template>
								<v-list>
									<v-list-item @click="handleView(item)">
										<v-list-item-title
											class="k-tracking-normal k-fw-sm k-font-inter-regular"
										>
											View
										</v-list-item-title>
									</v-list-item>
									<v-list-item
										v-if="pendingShipmentTab == 0 || pendingShipmentTab == 1"
										@click.stop="handleShowSnoozeDialog(item)"
									>
										<v-list-item-title
											class="k-fw-sm k-tracking-normal k-font-inter-regular"
										>
											Snooze
										</v-list-item-title>
									</v-list-item>
									<v-list-item
										v-if="
											pendingShipmentTab == 0 &&
												currentUser !== null &&
												currentUser.default_customer_id == item.customer_id &&
												item.is_tracking_shipment == 0
										"
									>
										<v-list-item-title class="k-fw-sm k-font-inter-regular">
											Mark Booked External
										</v-list-item-title>
									</v-list-item>
									<v-list-item
										v-if="pendingShipmentTab == 2"
										@click.stop="handleUnSnoozeShipment(item)"
									>
										<v-list-item-title
											class="k-tracking-normal k-fw-sm k-font-inter-regular"
										>
											{{ item.unsnoozing ? "Unsnoozing..." : "Unsnooze" }}
										</v-list-item-title>
									</v-list-item>
									<v-list-item
										v-if="pendingShipmentTab == 3"
										@click.stop="requestSchedule(item.id)"
									>
										<v-list-item-title
											class="k-tracking-normal k-fw-sm k-dark-blue k-font-inter-regular"
										>
											Request Again
										</v-list-item-title>
									</v-list-item>
									<v-list-item
										v-if="pendingShipmentTab == 0 || pendingShipmentTab == 1"
									>
										<v-list-item-title
											class="k-fw-sm k-font-inter-regular k-red"
										>
											Cancel Request
										</v-list-item-title>
									</v-list-item>

									<!-- <v-list-item @click="handleView(item)">
                                        <v-list-item-title class="k-tracking-normal k-fw-sm k-font-inter-regular">
                                            View
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item v-if="pendingShipmentTab==0 || pendingShipmentTab==1" @click.stop="handleShowSnoozeDialog(item)">
                                        <v-list-item-title class="k-fw-sm k-tracking-normal k-font-inter-regular">
                                            Snooze
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item v-if="pendingShipmentTab==2" @click.stop="handleUnSnoozeShipment(item)">
                                        <v-list-item-title class="k-tracking-normal k-fw-sm k-font-inter-regular">
                                            {{ (item.unsnoozing) ? 'Unsnoozing...' : 'Unsnooze' }}
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item v-if="pendingShipmentTab==3" @click.stop="requestSchedule(item.id)">
                                        <v-list-item-title class="k-tracking-normal k-fw-sm k-dark-blue k-font-inter-regular">
                                            Request Again
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item class="cancel-request-wrapper">
                                        <v-list-item-title class="k-tracking-normal k-fw-sm k-font-inter-regular">
                                            Cancel Request
                                        </v-list-item-title>
                                    </v-list-item> -->
								</v-list>
							</v-menu>
						</span>
					</div>
				</div>
			</template>

			<!-- Location From -->
			<template v-slot:[`item.location_from_name`]="{ item }">
				<div class="k-flex k-justify-between">
					<div class="k-grey">From</div>
					<div class="k-default-color">{{ item.location_from_name }}</div>
				</div>
			</template>

			<!-- Location To -->
			<template v-slot:[`item.location_to_name`]="{ item }">
				<div class="k-flex k-justify-between">
					<div class="k-grey">To</div>
					<div class="k-default-color">{{ item.location_to_name }}</div>
				</div>
			</template>

			<!-- PENDING TAB -->
			<!-- Departure, Mode and Arrival -->
			<template v-slot:[`item.PO`]="{ item }">
				<div v-if="1 == 2" class="mobile-pos">
					<div
						v-if="item.po_list !== null && item.po_list.length > 0"
						style="width: 100%; display:flex;"
					>
						<p>
							<span v-for="(name, index) in item.po_list" :key="index">
								PO #{{ name
								}}<template v-if="index + 1 < item.po_list.length">, </template>
							</span>
						</p>
					</div>

					<div v-if="item.po_list == null">
						<p>N/A</p>
					</div>
				</div>
				<div class="k-flex k-justify-between">
					<div class="k-grey">POs</div>
					<div class="k-default-color">
						{{
							item.po_list !== null && item.po_list.length > 0
								? item.po_list.join(", ")
								: "N/A"
						}}
					</div>
				</div>
			</template>
		</v-data-table>

		<!-- IF not search -->
		<div
			class="pagination-wrapper pt-3 k-relative"
			v-if="
				activeShipmentTab === 0 &&
					pendingShipmentTab === 1 &&
					pendingQuote !== 'undefined' &&
					search == '' &&
					pagination.pendingQuoteTab.total > 1
			"
		>
			<v-pagination
				v-model="pagination.pendingQuoteTab.current_page"
				:length="pagination.pendingQuoteTab.total"
				prev-icon="Previous"
				next-icon="Next"
				:total-visible="!isMobile ? '11' : '5'"
				@input="handlePageChangePendingQuote"
			>
			</v-pagination>
		</div>
		<div
			class="pagination-wrapper pt-3"
			v-if="
				activeShipmentTab === 0 &&
					pendingShipmentTab === 1 &&
					pending !== 'undefined' &&
					search == '' &&
					pagination.pendingTab.total > 1
			"
		>
			<v-pagination
				v-model="pagination.pendingTab.current_page"
				:length="pagination.pendingTab.total"
				prev-icon="Previous"
				next-icon="Next"
				:total-visible="!isMobile ? '11' : '5'"
				@input="handlePageChangePending"
			>
			</v-pagination>
		</div>
		<div
			class="pagination-wrapper pt-3"
			v-if="
				activeShipmentTab === 0 &&
					pendingShipmentTab === 2 &&
					snooze !== 'undefined' &&
					search == '' &&
					pagination.snoozeTab.total > 1
			"
		>
			<v-pagination
				v-model="pagination.snoozeTab.current_page"
				:length="pagination.snoozeTab.total"
				prev-icon="Previous"
				next-icon="Next"
				:total-visible="!isMobile ? '11' : '5'"
				@input="handlePageChangeSnooze"
			>
			</v-pagination>
		</div>
		<div
			class="pagination-wrapper pt-3"
			v-if="
				activeShipmentTab === 0 &&
					pendingShipmentTab === 3 &&
					expired !== 'undefined' &&
					search == '' &&
					pagination.expiredTab.total > 1
			"
		>
			<v-pagination
				v-model="pagination.expiredTab.current_page"
				:length="pagination.expiredTab.total"
				prev-icon="Previous"
				next-icon="Next"
				:total-visible="!isMobile ? '11' : '5'"
				@input="handlePageChangeExpired"
			>
			</v-pagination>
		</div>

		<div
			class="pagination-wrapper pt-3"
			v-if="
				activeShipmentTab === 0 &&
					pendingShipmentTab === 4 &&
					expired !== 'undefined' &&
					search == '' &&
					pagination.draftTab.total > 1
			"
		>
			<v-pagination
				v-model="pagination.drafTab.current_page"
				:length="pagination.drafTab.total"
				prev-icon="Previous"
				next-icon="Next"
				:total-visible="!isMobile ? '11' : '5'"
				@input="handlePageChangeDraft"
			>
			</v-pagination>
		</div>

		<!-- If search -->
		<div
			class="pagination-wrapper pt-3"
			v-if="
				activeShipmentTab === 0 &&
					pendingShipmentTab === 1 &&
					pending !== 'undefined' &&
					search !== '' &&
					getShipmentPagination > 1
			"
		>
			<v-pagination
				v-model="getCurrentShipmentPage"
				:length="getShipmentPagination"
				prev-icon="Previous"
				next-icon="Next"
				:total-visible="!isMobile ? '11' : '5'"
				@input="handlePageSearched"
			>
			</v-pagination>
		</div>
		<div
			class="pagination-wrapper pt-3"
			v-if="
				activeShipmentTab === 0 &&
					pendingShipmentTab === 0 &&
					pendingQuote !== 'undefined' &&
					search !== '' &&
					getShipmentPagination > 1
			"
		>
			<v-pagination
				v-model="getCurrentShipmentPage"
				:length="getShipmentPagination"
				prev-icon="Previous"
				next-icon="Next"
				:total-visible="!isMobile ? '11' : '5'"
				@input="handlePageSearched"
			>
			</v-pagination>
		</div>
		<div
			class="pagination-wrapper pt-3"
			v-if="
				activeShipmentTab === 0 &&
					pendingShipmentTab === 2 &&
					snooze !== 'undefined' &&
					search !== '' &&
					getShipmentPagination > 1
			"
		>
			<v-pagination
				v-model="getCurrentShipmentPage"
				:length="getShipmentPagination"
				prev-icon="Previous"
				next-icon="Next"
				:total-visible="!isMobile ? '11' : '5'"
				@input="handlePageSearched"
			>
			</v-pagination>
		</div>
		<div
			class="pagination-wrapper pt-3"
			v-if="
				activeShipmentTab === 0 &&
					pendingShipmentTab === 3 &&
					expired !== 'undefined' &&
					search !== '' &&
					getShipmentPagination > 1
			"
		>
			<v-pagination
				v-model="getCurrentShipmentPage"
				:length="getShipmentPagination"
				prev-icon="Previous"
				next-icon="Next"
				:total-visible="!isMobile ? '11' : '5'"
				@input="handlePageSearched"
			>
			</v-pagination>
		</div>
		<snooze-shipment-dialog
			:isMobile="isMobile"
			:show="showSnoozeShipmentDialog"
			@close="handleCloseSnoozeShipmentDialog"
			@reloadAllShipments="reloadAllShipments"
			:item="currentSnoozeItem"
		>
			<template v-slot:reference>
				<div class="snooze-shipment-title">
					{{ "Snooze Booking" }}
				</div>
			</template>
			<template v-slot:main-text>
				<div>
					{{
						"You can snooze a booking request and we will process your request after the snoozed period or remind you about the quote after the snoozed period."
					}}
				</div>
			</template>
			<template
				v-slot:actions="{
					actionItems: { handleClose, item, handleSnoozeShipment, date_save },
				}"
			>
				<v-btn
					:disabled="date_save == null"
					@click.stop="handleSnoozeShipment(item, date_save)"
					class="btn-blue"
					text
				>
					<span>{{
						item.snoozing ? "Snoozing Shipment..." : "Snooze Shipment"
					}}</span>
				</v-btn>
				<v-btn @click.stop="handleClose" class="btn-white" text>Cancel</v-btn>
			</template>
		</snooze-shipment-dialog>

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
				<v-btn class="btn-white" text @click="cancelBookingRequestDialog = false" :disabled="getCancelShipmentLoading">
					<span>Close</span>
				</v-btn>
			</template>
		</ConfirmDialog>

	</div>
</template>
<style lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap");
@import "../../../../assets/scss/pages_scss/shipment/pendingTabTable.scss";
@import "../../../../assets/scss/utilities.scss";
</style>
<script>
import { mapActions, mapGetters } from "vuex";
import globalMethods from "../../../../utils/globalMethods";
import moment from "moment";
import CustomIcon from "../../../Icons/CustomIcon";
import iziToast from "izitoast";
import SnoozeShipmentDialog from "../../../Dialog/SnoozeShipmentDialog";
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";

import _ from "lodash";

export default {
	props: [
		"pending",
		"pendingQuote",
		"snooze",
		"expired",
		"draft",
		"activeTab",
		"isMobile",
		"search",
		"pendingCurrentTab",
		"paginationData",
	],
	components: {
		SnoozeShipmentDialog,
		CustomIcon,
		ConfirmDialog
	},
	data: () => ({
		closeOnClick: true,
		documentScrollTop: 0,
		dynamicNum: 0,
		currentPendingQuoteItem: {},
		currentPendingQuoteItemIndex: 0,
		currentExpiredItem: {},
		currentExpiredItemIndex: 0,
		currentSnoozeItem: {},
		currentSnoozeItemIndex: 0,
		currentPendingItem: {},
		currentPendingItemIndex: 0,
		pageCount: 0,
		pendingQuoteData: [],
		headersSnooze: [
			{
				text: "Ref#/Cus Ref",
				align: "start",
				sortable: false,
				value: "ReferenceID",
				fixed: true,
				class: "pending-quote-table-header",
				// width: "15.20%"
				width: "12%",
			},
			{
				text: "Snooze Until",
				align: "start",
				sortable: false,
				value: "snooze_date_at",
				fixed: true,
				class: "pending-quote-table-header",
				// width: "15.20%"
				width: "12%",
			},
			{
				text: "From",
				value: "location_from_name",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				width: "12%",
			},
			{
				text: "To",
				value: "location_to_name",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				width: "12%",
			},
			{
				text: "Mode/Type",
				value: "mode",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				width: "6%",
				align: "center",
			},
			{
				text: "Suppliers",
				value: "Suppliers",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				// width: "15.64%"
				width: "18%",
			},
			{
				text: "PO",
				value: "PO",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				// width: "18.36%"
				width: "18%",
			},
			{
				text: "",
				align: "center",
				sortable: false,
				value: "actions",
				// width: "14.28%",
				width: "15%",
				fixed: true,
			},
		],
		headersPendingQuote: [
			{
				text: "Ref#/Cus Ref",
				align: "start",
				sortable: false,
				value: "ReferenceID",
				fixed: true,
				class: "pending-quote-table-header",
				// width: "15.20%"
				width: "12%",
			},
			{
				text: "Updated By & At",
				align: "start",
				sortable: false,
				value: "updated_at",
				fixed: true,
				class: "pending-quote-table-header",
				// width: "15.20%"
				width: "18%",
			},
			{
				text: "From",
				value: "location_from_name",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				width: "12%",
			},
			{
				text: "To",
				value: "location_to_name",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				width: "12%",
			},
			{
				text: "Mode/Type",
				align: "center",
				value: "mode",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				width: "6%",
			},
			{
				text: "Suppliers",
				value: "Suppliers",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				width: "18%",
			},
			{
				text: "PO",
				value: "PO",
				sortable: false,
				fixed: true,
				class: "pending-quote-table-header",
				width: "14%",
			},
			{
				text: "",
				align: "center",
				sortable: false,
				value: "actions",
				width: "5%",
				fixed: true,
			},
		],
		headersPending: [
			{
				text: "Ref#/Cus Ref",
				align: "start",
				sortable: false,
				value: "ReferenceID",
				width: "10%",
				fixed: true,
			},
			{
				text: "Suppliers",
				value: "Suppliers",
				sortable: false,
				width: "20%",
				fixed: true,
			},
			{
				text: "PO",
				value: "PO",
				sortable: false,
				width: "15%",
				fixed: true,
			},
			{
				text: "Cargo Ready Date",
				value: "cargo_ready_date",
				sortable: false,
				width: "15%",
				fixed: true,
			},
			{
				text: "Status",
				value: "Status",
				align: "center",
				sortable: false,
				width: "15%",
				fixed: true,
			},
			{
				text: "",
				align: "center",
				sortable: false,
				value: "actions",
				width: "20%",
				fixed: true,
			},
			{
				text: "Container",
				align: " d-none",
				sortable: false,
				value: "container_num_list",
				width: "0",
				fixed: true,
			},
		],
		loadingNewScheds: false,
		currentIdRequestScheds: null,
		showSnoozeShipmentDialog: false,
		cancelBookingRequestDialog: false,
		cancelBookingItemId: 0,
	}),
	computed: {
		currentPendingQuoteItemShow() {
			if (!this.currentPendingQuoteItem.show_tooltip) {
				return false;
			} else {
				return true;
			}
		},
		tableHeaders() {
			if (this.pendingShipmentTab !== 3) {
				if (this.isMobile) {
					if (
						this.pendingShipmentTab == 0 ||
						this.pendingShipmentTab == 1 ||
						this.pendingShipmentTab == 2 ||
						this.pendingShipmentTab == 4
					) {
						let newHeaders = [
							this.headersPendingQuote[0],
							this.headersPendingQuote[2],
							this.headersPendingQuote[3],
							this.headersPendingQuote[6],
						];
						return newHeaders;
					} else {
						return this.headersPendingQuote;
					}
				} else {
					return this.headersPendingQuote;
				}
			} else {
				if (this.isMobile) {
					let newHeaders = this.headersSnooze;
					if (newHeaders.length == 8) {
						newHeaders = _.filter(
							newHeaders,
							(e) =>
								e.value !== "mode" &&
								e.value !== "snooze_date_at" &&
								e.value !== "Suppliers"
						);
					}
					return newHeaders;
				} else {
					return this.headersSnooze;
				}
			}
			//return []
		},
		currentUser() {
			return JSON.parse(this.getUser);
		},
		...mapGetters([
			"getAllPendingShipments",
			"getAllPendingQuoteShipments",
			"getAllSnoozeShipmentsLoading",
			"getAllSnoozeShipments",
			"getAllExpiredShipments",
			"getShipmentLoadingStatus",
			"getAllPendingShipmentsLoading",
			"getAllPendingQuoteShipmentsLoading",
			"getAllExpiredShipmentsLoading",
			"getSearchedShipments",
			"getSearchedShipmentPagination",
			"getUser",
			"getCancelShipmentLoading",
		]),
		activeShipmentTab: {
			get() {
				return this.activeTab;
			},
			set(value) {
				this.$emit("update:activeTab", value);
			},
		},
		pendingShipmentTab: {
			get() {
				return this.pendingCurrentTab;
			},
			set(value) {
				this.$emit("update:pendingCurrentTab", value);
			},
		},
		page: {
			get() {
				return this.tablePage;
			},
			set(value) {
				this.$emit("update:tablePage", value);
			},
		},
		pagination: {
			get() {
				return this.paginationData;
			},
			set(value) {
				this.$emit("update:paginationData", value);
			},
		},
		getCurrentId() {
			if (this.pendingShipmentTab === 1 && this.pendingQuote.length === 0) {
				return "table-no-data";
			} else if (this.pendingShipmentTab === 2 && this.pending.length === 0) {
				return "table-no-data";
			} else if (this.pendingShipmentTab === 3 && this.snooze.length === 0) {
				return "table-no-data";
			} else if (this.pendingShipmentTab === 4 && this.expired.length === 0) {
				return "table-no-data";
			} else if (this.pendingShipmentTab === 0 && this.draft.length === 0) {
				return "table-no-data";
			} else {
				return "";
			}
		},
		getCurrentShipmentsData() {
			if (
				typeof this.getSearchedShipments !== "undefined" &&
				this.getSearchedShipments !== null
			) {
				if (
					this.search !== "" &&
					this.pendingShipmentTab === 1 &&
					this.getSearchedShipments.tab === "pendingQuote"
				) {
					return this.getSearchedShipments.shipments;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 3 &&
					this.getSearchedShipments.tab === "snooze"
				) {
					return this.getSearchedShipments.shipments;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 4 &&
					this.getSearchedShipments.tab === "expired"
				) {
					return this.getSearchedShipments.shipments;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 2 &&
					this.getSearchedShipments.tab === "pending"
				) {
					return this.getSearchedShipments.shipments;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 0 &&
					this.getSearchedShipments.tab === "draft"
				) {
					return this.getSearchedShipments.shipments;
				} else {
					if (this.pendingShipmentTab === 1) {
						return this.pendingQuote;
					} else if (this.pendingShipmentTab === 2) {
						return this.pending;
					} else if (this.pendingShipmentTab === 3) {
						return this.snooze;
					} else if (this.pendingShipmentTab === 4) {
						return this.expired;
					} else if (this.pendingShipmentTab === 0) {
						return this.draft;
					}
				}
			} else {
				if (this.pendingShipmentTab === 1) {
					return this.pendingQuote;
				} else if (this.pendingShipmentTab === 2) {
					return this.pending;
				} else if (this.pendingShipmentTab === 3) {
					return this.snooze;
				} else if (this.pendingShipmentTab === 4) {
					return this.expired;
				} else if (this.pendingShipmentTab === 0) {
					return this.draft;
				}
			}
			return "";
		},
		getCurrentShipmentPage: {
			get() {
				if (
					typeof this.getSearchedShipments !== "undefined" &&
					this.getSearchedShipments !== null
				) {
					if (
						this.search !== "" &&
						this.pendingShipmentTab === 2 &&
						this.getSearchedShipments.tab === "pending"
					) {
						return this.getSearchedShipmentPagination.current_page;
					} else if (
						this.search !== "" &&
						this.pendingShipmentTab === 1 &&
						this.getSearchedShipments.tab === "pendingQuote"
					) {
						return this.getSearchedShipmentPagination.current_page;
					} else if (
						this.search !== "" &&
						this.pendingShipmentTab === 3 &&
						this.getSearchedShipments.tab === "snooze"
					) {
						return this.getSearchedShipmentPagination.current_page;
					} else if (
						this.search !== "" &&
						this.pendingShipmentTab === 4 &&
						this.getSearchedShipments.tab === "expired"
					) {
						return this.getSearchedShipmentPagination.current_page;
					} else if (
						this.search !== "" &&
						this.pendingShipmentTab === 0 &&
						this.getSearchedShipments.tab === "draft"
					) {
						return this.getSearchedShipmentPagination.current_page;
					} else {
						if (this.pendingShipmentTab === 2) {
							return this.pagination.pendingTab.current_page;
						} else if (this.pendingShipmentTab === 1) {
							return this.pagination.pendingQuoteTab.current_page;
						} else if (this.pendingShipmentTab === 3) {
							return this.pagination.snoozeTab.current_page;
						} else if (this.pendingShipmentTab === 4) {
							return this.pagination.expiredTab.current_page;
						} else {
							return this.pagination.draftTab.current_page;
						}
					}
				} else {
					if (this.pendingShipmentTab === 2) {
						return this.pagination.pendingTab.current_page;
					} else {
						if (this.pendingShipmentTab === 1) {
							return this.pagination.pendingQuoteTab.current_page;
						} else if (this.pendingShipmentTab === 3) {
							return this.pagination.snoozeTab.current_page;
						} else if (this.pendingShipmentTab === 4) {
							return this.pagination.expiredTab.current_page;
						} else if (this.pendingShipmentTab === 0) {
							return this.pagination.draftTab.current_page;
						}
					}
					/*
                    if (this.pendingShipmentTab === 0) {
                        return this.pagination.pendingTab.current_page
                    } else {
                        return this.pagination.expiredTab.current_page
                    }*/
				}
				return "";
			},
			set(value) {
				if (
					typeof this.getSearchedShipments !== "undefined" &&
					this.getSearchedShipments !== null
				) {
					if (
						this.search !== "" &&
						this.pendingShipmentTab === 2 &&
						this.getSearchedShipments.tab === "pending"
					) {
						this.$store.state.shipment.searchedShipmentsPagination.current_page = value;
					} else if (
						this.search !== "" &&
						this.pendingShipmentTab === 4 &&
						this.getSearchedShipments.tab === "expired"
					) {
						this.$store.state.shipment.searchedShipmentsPagination.current_page = value;
					} else if (
						this.search !== "" &&
						this.pendingShipmentTab === 1 &&
						this.getSearchedShipments.tab === "pendingQuote"
					) {
						this.$store.state.shipment.searchedShipmentsPagination.current_page = value;
					} else if (
						this.search !== "" &&
						this.pendingShipmentTab === 3 &&
						this.getSearchedShipments.tab === "snooze"
					) {
						this.$store.state.shipment.searchedShipmentsPagination.current_page = value;
					} else if (
						this.search !== "" &&
						this.pendingShipmentTab === 0 &&
						this.getSearchedShipments.tab === "draft"
					) {
						this.$store.state.shipment.searchedShipmentsPagination.current_page = value;
					} else {
						if (this.pendingShipmentTab === 0) {
							this.$emit("update:paginationData", value);
						} else {
							this.$emit("update:paginationData", value);
						}
					}
				} else {
					if (this.pendingShipmentTab === 0) {
						this.$emit("update:paginationData", value);
					} else {
						this.$emit("update:paginationData", value);
					}
				}
			},
		},
		getShipmentItemsPerPage() {
			if (
				typeof this.getSearchedShipments !== "undefined" &&
				this.getSearchedShipments !== null
			) {
				if (
					this.search !== "" &&
					this.pendingShipmentTab === 2 &&
					this.getSearchedShipments.tab === "pending"
				) {
					return this.getSearchedShipmentPagination.per_page;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 1 &&
					this.getSearchedShipments.tab === "pendingQuote"
				) {
					return this.getSearchedShipmentPagination.per_page;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 3 &&
					this.getSearchedShipments.tab === "snooze"
				) {
					return this.getSearchedShipmentPagination.per_page;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 4 &&
					this.getSearchedShipments.tab === "expired"
				) {
					return this.getSearchedShipmentPagination.per_page;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 0 &&
					this.getSearchedShipments.tab === "draft"
				) {
					return this.getSearchedShipmentPagination.per_page;
				} else {
					if (this.pendingShipmentTab === 1) {
						return this.pagination.pendingQuoteTab.per_page;
					} else if (this.pendingShipmentTab === 2) {
						return this.pagination.pendingTab.per_page;
					} else if (this.pendingShipmentTab === 3) {
						return this.pagination.snoozeTab.per_page;
					} else if (this.pendingShipmentTab === 4) {
						return this.pagination.expiredTab.per_page;
					} else if (this.pendingShipmentTab === 0) {
						return this.pagination.draftTab.per_page;
					}
				}
				return "";
			} else {
				if (this.pendingShipmentTab === 1) {
					return this.pagination.pendingQuoteTab.per_page;
				} else if (this.pendingShipmentTab === 2) {
					return this.pagination.pendingTab.per_page;
				} else if (this.pendingShipmentTab === 3) {
					return this.pagination.snoozeTab.per_page;
				} else if (this.pendingShipmentTab === 4) {
					return this.pagination.expiredTab.per_page;
				} else if (this.pendingShipmentTab === 0) {
					return this.pagination.draftTab.per_page;
				}
				return "";
			}
		},
		getShipmentPagination() {
			if (
				typeof this.getSearchedShipments !== "undefined" &&
				this.getSearchedShipments !== null
			) {
				if (
					this.search !== "" &&
					this.pendingShipmentTab === 1 &&
					this.getSearchedShipments.tab === "pendingQuote"
				) {
					return this.getSearchedShipmentPagination.total;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 2 &&
					this.getSearchedShipments.tab === "pending"
				) {
					return this.getSearchedShipmentPagination.total;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 3 &&
					this.getSearchedShipments.tab === "snooze"
				) {
					return this.getSearchedShipmentPagination.total;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 4 &&
					this.getSearchedShipments.tab === "expired"
				) {
					return this.getSearchedShipmentPagination.total;
				} else if (
					this.search !== "" &&
					this.pendingShipmentTab === 0 &&
					this.getSearchedShipments.tab === "draft"
				) {
					return this.getSearchedShipmentPagination.total;
				} else {
					if (this.pendingShipmentTab === 1) {
						return this.pagination.pendingQuoteTab.total;
					} else if (this.pendingShipmentTab === 2) {
						return this.pagination.pendingTab.total;
					} else if (this.pendingShipmentTab === 3) {
						return this.pagination.snoozeTab.total;
					} else if (this.pendingShipmentTab === 4) {
						return this.pagination.expiredTab.total;
					} else if (this.pendingShipmentTab === 0) {
						return this.pagination.draftTab.total;
					}
				}
				return "";
			} else {
				if (this.pendingShipmentTab === 1) {
					return this.pagination.pendingQuoteTab.total;
				} else if (this.pendingShipmentTab === 2) {
					return this.pagination.pendingTab.total;
				} else if (this.pendingShipmentTab === 3) {
					return this.pagination.snoozeTab.total;
				} else if (this.pendingShipmentTab === 4) {
					return this.pagination.expiredTab.total;
				} else if (this.pendingShipmentTab === 0) {
					return this.pagination.draftTab.total;
				}
				return "";
			}
		},
	},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "shipments");
	},
	methods: {
		...mapActions([
			"requestNewSchedule",
			"fetchPendingShipments",
			"fetchPendingQuoteShipments",
			"fetchSnoozeShipments",
			"fetchExpiredShipments",
			"snoozeShipment",
			"unSnoozeShipment",
			"clearShipmentsData",
			"cancelShipment",
			"fetchDraftShipments"
		]),
		...globalMethods,
		setMarkingBookedExternal(b) {
			this.$store.dispatch("page/setMarkingBookedExternal", b);
		},
		setEditingShipment(b) {
			this.$store.dispatch("page/setEditingShipment", b);
		},
		setEditingDraftShipment(b) {
			this.$store.dispatch("page/setEditingDraftShipment", b);
		},
		setAddingShipmentTracking(b) {
			this.$store.dispatch("page/setAddingShipmentTracking", b);
		},
		setCreatingBookingRequest(b) {
			this.$store.dispatch("page/setAddingShipmentTracking", b);
		},
		editShipment(item, e) {
			//prevent parent event
			e.preventDefault();
			if (item.is_draft == 1) this.setEditingDraftShipment(true);

			this.setEditingShipment(false);
			this.setMarkingBookedExternal(false);
			this.setAddingShipmentTracking(false);
			this.setCreatingBookingRequest(false);

			this.$emit("showEditShipment", item);
		},
		getCurrentPaginationCountClass() {
			if (this.search === "") {
				if (this.pendingShipmentTab === 1) {
					return typeof this.pagination.pendingQuoteTab.total !== "undefined"
						? this.pagination.pendingQuoteTab.total <= 1
						: false;
				} else if (this.pendingShipmentTab === 2) {
					return typeof this.pagination.pendingTab.total !== "undefined"
						? this.pagination.pendingTab.total <= 1
						: false;
				} else if (this.pendingShipmentTab === 3) {
					return typeof this.pagination.snoozeTab.total !== "undefined"
						? this.pagination.snoozeTab.total <= 1
						: false;
				} else if (this.pendingShipmentTab === 4) {
					return typeof this.pagination.expiredTab.total !== "undefined"
						? this.pagination.expiredTab.total <= 1
						: false;
				} else if (this.pendingShipmentTab === 0) {
					return typeof this.pagination.draftTab.total !== "undefined"
						? this.pagination.draftTab.total <= 1
						: false;
				}
			} else {
				if (this.pendingShipmentTab === 1) {
					return typeof this.getShipmentPagination !== "undefined"
						? this.getShipmentPagination <= 1
						: false;
				} else if (this.pendingShipmentTab === 2) {
					return typeof this.getShipmentPagination !== "undefined"
						? this.getShipmentPagination <= 1
						: false;
				} else if (this.pendingShipmentTab === 3) {
					return typeof this.getShipmentPagination !== "undefined"
						? this.getShipmentPagination <= 1
						: false;
				} else if (this.pendingShipmentTab === 4) {
					return typeof this.getShipmentPagination !== "undefined"
						? this.getShipmentPagination <= 1
						: false;
				} else if (this.pendingShipmentTab === 0) {
					return typeof this.getShipmentPagination !== "undefined"
						? this.getShipmentPagination <= 1
						: false;
				}
			}
		},
		handleMarkBookedExternal(item, e) {
			//prevent parent event
			e.preventDefault();
			this.setEditingShipment(false);
			this.setMarkingBookedExternal(true);
			this.setAddingShipmentTracking(false);
			this.setAddingShipmentTracking(false);
			this.$emit("showEditShipment", item);
		},
		handleShowSnoozeDialog(item) {
			this.currentSnoozeItem = item;
			this.showSnoozeShipmentDialog = true;
		},
		handleCloseSnoozeShipmentDialog() {
			this.showSnoozeShipmentDialog = false;
		},
		reloadAllShipments() {
			this.clearShipmentsData({
				pending_quote: this.getAllPendingQuoteShipments,
				pending: this.getAllPendingShipments,
				expired: this.getAllExpiredShipments,
				snooze: this.getAllExpiredShipments,
			});

			this.fetchPendingQuoteShipments(1);
			this.fetchPendingShipments(1);
			this.fetchExpiredShipments(1);
			this.fetchSnoozeShipments(1);
		},
		notificationUnsnoozeSuccess() {
			iziToast.success({
				theme: "dark",
				message: `<h4 style="font-weight: 500 !important; color: #fff !important;">Successfully unsnooze the shipment.</h4>`,
				backgroundColor: "#16B442",
				messageColor: "#ffffff",
				iconColor: "#ffffff",
				progressBarColor: "#ADEEBD",
				displayMode: 1,
				position: "bottomCenter",
				timeout: 3000,
			});
		},
		notificationUnsnoozeError(message) {
			iziToast.error({
				title: "Error",
				message,
				displayMode: 1,
				position: "bottomCenter",
				timeout: 3000,
			});
		},
		async handleUnSnoozeShipment(item) {
			item.unsnoozing = true;
			try {
				await this.unSnoozeShipment({
					shipment_id: item.id,
				});
				//this.notificationUnsnoozeSuccess()
				this.notificationMessage("Successfully unsnooze the shipment.");
				//this.notificationUnsnoozeSuccess()
				item.unsnoozing = false;
				this.reloadAllShipments();
				//await this.fetchSnoozeShipments((this.pagination.snoozeTab.currentTab === 0 && this.pagination.snoozeTab.currentSubTab === 0) ? this.pagination.snoozeTab.current_page : 1)
			} catch (e) {
				item.unsnoozing = false;
				this.notificationError(e);
				//this.notificationUnsnoozeError(e)
				console.log(e);
			}
		},
		handleCancel(item) {
			item.show_tooltip = false;
		},
		handleView({ id }) {
			this.$router.push(`shipment/${id}`);
		},
		showTooltip(item, index, shipment_type) {
			if (shipment_type == "pendingQuote") {
				if (this.currentPendingQuoteItemIndex !== index) {
					item.show_tooltip = true;
				} else {
					item.show_tooltip = !item.show_tooltip;
				}
				this.currentPendingQuoteItem = item;
				this.currentPendingQuoteItemIndex = index;
			} else if (shipment_type == "expired") {
				if (this.currentExpiredItemIndex !== index) {
					item.show_tooltip = true;
				} else {
					item.show_tooltip = !item.show_tooltip;
				}
				this.currentExpiredItem = item;
				this.currentExpiredItemIndex = index;
			} else if (shipment_type == "snooze") {
				if (this.currentSnoozeItemIndex !== index) {
					item.show_tooltip = true;
				} else {
					item.show_tooltip = !item.show_tooltip;
				}
				this.currentSnoozeItem = item;
				this.currentSnoozeItemIndex = index;
			} else if (shipment_type == "pending") {
				if (this.currentPendingItemIndex !== index) {
					item.show_tooltip = true;
				} else {
					item.show_tooltip = !item.show_tooltip;
				}
				this.currentPendingItem = item;
				this.currentPendingItemIndex = index;
			} else if (shipment_type == "draft") {
				if (this.currentPendingItemIndex !== index) {
					item.show_tooltip = true;
				} else {
					item.show_tooltip = !item.show_tooltip;
				}
				this.currentDraftItem = item;
				this.currentDraftItemIndex = index;
			} else {
				if (this.currentPendingQuoteItemIndex !== index) {
					item.show_tooltip = true;
				} else {
					item.show_tooltip = !item.show_tooltip;
				}
				this.currentPendingQuoteItem = item;
				this.currentPendingQuoteItemIndex = index;
			}
		},
		beautify(date) {
			return date !== null ? moment.utc(date).format("MMM DD, YYYY") : "N/A";
		},
		handleClick(value) {
			this.$emit("handleClick", value);
		},
		getStatus(status) {
			if (status !== null) {
				return `<span class='chip-text'>${status}</span>`;
			}
		},
		getStatusClass(status) {
			return status;
		},
		async requestSchedule(id) {
			this.currentIdRequestScheds = id;
			let storePagination = this.$store.state.shipment.shipmentsPagination
				.expiredTab;

			try {
				this.loadingNewScheds = true;
				await this.requestNewSchedule(id);
				this.notificationMessage("Schedule has been requested.");
				this.loadingNewScheds = false;
				this.currentIdRequestScheds = null;
				await this.fetchExpiredShipments(storePagination.current_page);
			} catch (e) {
				this.loadingNewScheds = false;
				this.currentIdRequestScheds = null;
				this.notificationError(e);
			}
		},
		async handlePageChangeSnooze(page) {
			this.$emit("handlePageChangeSnooze", page);
			this.handleScrollToTop();
		},
		async handlePageChangePendingQuote(page) {
			this.$emit("handlePageChangePendingQuote", page);
			this.handleScrollToTop();
		},
		async handlePageChangePending(page) {
			this.$emit("handlePageChangePending", page);
			this.handleScrollToTop();
		},
		async handlePageChangeExpired(page) {
			this.$emit("handlePageChangeExpired", page);
			this.handleScrollToTop();
		},
		async handlePageChangeDraft(page) {
			this.$emit("handlePageChangeDraft", page);
			this.handleScrollToTop();
		},
		async handlePageSearched(page) {
			/*
            let data = { 
                page,
                tab: this.pendingShipmentTab === 0 ? 'pending' : 'expired'
            }*/
			let data = {
				page,
			};

			if (this.pendingShipmentTab === 1) {
				data.tab = "pendingQuote";
			} else if (this.pendingShipmentTab == 2) {
				data.tab = "pending";
			} else if (this.pendingShipmentTab == 3) {
				data.tab = "snooze";
			} else if (this.pendingShipmentTab == 0) {
				data.tab = "draft";
			} else {
				data.tab = "expired";
			}
			this.$emit("handlePageSearched", data);
			this.handleScrollToTop();
		},
		handleScrollToTop() {
			var table = this.$refs["my-table"];
			var wrapper = table.$el.querySelector("div.v-data-table__wrapper");

			this.$vuetify.goTo(table); // to table
			this.$vuetify.goTo(table, { container: wrapper }); // to header
		},
		cancelBooking(item) {
			this.cancelBookingItemId = item;
			this.cancelBookingRequestDialog = true;
		},
		submitCancelBooking() {
			if(this.cancelBookingItemId && this.cancelBookingItemId !== 0) {
				let payloadObject = {'shipmentId': this.cancelBookingItemId, 'type': 'booking'}
				this.cancelShipment(payloadObject)
					.then((response) => {
						this.cancelBookingRequestDialog = false;
						this.notificationErrorCustom(response.data.message);
						this.reloadAllShipments();
						this.fetchDraftShipments();
					})
					.catch((e) => {
						console.log(e);
						this.cancelBookingRequestDialog = false;
                        this.notificationErrorCustom('SOMETHING WENT WRONG!');
					})
			}
		},
	},
	updated() {},
};
</script>
