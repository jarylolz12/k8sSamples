<template>
	<div style="position: relative;">

		<v-dialog
			v-model="show"
			:content-class="`${className} create-shipment-ultimate-wrapper`"
			width="auto"
			persistent
		>
			<v-card id="edit-shipment-dialog-id" class="edit-shipment-dialog">
				<v-card-title class="pa-0">
					<h4>Add More Shipment Info</h4>
					<button icon dark class="btn-close" @click.stop="close()" :disabled="getUpdateShipmentInfoLoading">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>

				<v-card-text class="pb-0">
					<div
						style="min-height: 0px !important;"
						id="card-text-wrapper"
						ref="cardTextWrapper"
						class="d-flex flex-row"
					>
						<div class="d-flex flex-column first-column sidebar-item-main-wrapper pt-4">
							<div
								:class="
									`d-flex align-center sidebar-items-wrapper ${
										sidebarItem.selected ? 'selected' : ''
									}`
								"
								v-bind:key="`si-${key}`"
								v-for="(sidebarItem, key) in sidebarItems"
							>
								<a
									@click.stop="selectPage(sidebarItem)"
									:class="
										`d-flex sidebar-item align-center ${
											sidebarItem.selected ? 'selected' : ''
										}`
									"
								>
									<KeneticIcon
										:paddingTop="`${sidebarItem.icon === 'general' ? 6 : 0}`"
										:color="`${sidebarItem.selected ? '#0171A1' : '#4A4A4A'}`"
										v-if="sidebarItem.icon !== ''"
										:iconName="sidebarItem.icon"
										:width="sidebarItem.width"
										:height="sidebarItem.height"
									/>
									<div class="sidebar-label ml-3">
										{{ sidebarItem.label }}
									</div>
								</a>
							</div>
						</div>

						<div
							class="d-flex flex-column edit-shipment-dialog-main-content second-column add-shipment-tracking"
							style="width: 100%;"
						>
							<v-form
								:ref="reference"
							>
								<div class="form-wrapper">
									<v-row class="header-row ml-4">
										<v-col class="pa-3" md="6" v-if="createdShipmentType == 'withValidMBL'">
											<div class="d-flex">
												<v-col class="pa-0" md="3">
													<span style="font-weight: 500; size: 14px; color: #6D858F;">MBL Number </span>
												</v-col>
												<v-col class="pa-0" md="9">
													<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ getShipmentDetailsToEditComp.mbl_num }}</span>
												</v-col>
											</div>
										</v-col>
										<v-col class="pa-3" md="6">
											<div class="d-flex">
												<v-col class="pa-0" md="3">
													<span style="font-weight: 500; size: 14px; color: #6D858F;">Booking Number </span>
												</v-col>
												<v-col class="pa-0" md="9">
													<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ getShipmentDetailsToEditComp.booking_number }}</span>
												</v-col>
											</div>
										</v-col>
										<v-col class="pa-3" md="6">
											<div class="d-flex">
												<v-col class="pa-0" md="3">
													<span style="font-weight: 500; size: 14px; color: #6D858F;">Customer Ref </span>
												</v-col>
												<v-col class="pa-0" md="9">
													<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ getShipmentDetailsToEditComp.customer_reference_number }}</span>
												</v-col>
											</div>
										</v-col>
									</v-row>
									<div class="dialog-content ml-4">
										<h3 class="mt-8 mb-4">General Information</h3>
										<v-row>
											<v-col class="pa-3 pb-1" md="6">
												<div class="d-flex">
													<v-col class="pa-0" md="12">
														<div class="text-field-wrapper">
															<span class="text-field-label">FROM</span>
															<v-autocomplete
																:height="40"
																color="#002F44"
																:items="filteredTerminalRegions"
																item-text="text"
																item-value="value"
																v-model="editItem.location_from"
																:disabled="isShipmentDetailExist.isLocationFromExist ? true : false"
																dense
																outlined
																hide-details="auto"
																placeholder="Enter Location (Port, Warehouse etc.)"
																spellcheck="false"
																:menuProps="{
																	contentClass: 'po-lists-items',
																	...menuProps,
																}"
																clearable
															/>
														</div>
													</v-col>
												</div>
											</v-col>
											<v-col class="pa-3 pb-1" md="6">
												<span class="text-field-label">ETD</span>
												<div class="d-flex">
													<v-col class="pa-0" md="12">
														<template>
															<v-menu
																:close-on-content-click="false"
																transition="scale-transition"
																offset-y
																min-width="auto"
															>
																<template v-slot:activator="{ on, attrs }">
																	<v-text-field
																		type="date"
																		v-on="on"
																		v-bind="attrs"
																		v-model="editItem.etd"
																		:disabled="isShipmentDetailExist.isEtdExist ? true : false"
																		placeholder="MM-DD-YYYY"
																		outlined
																		class="text-fields etd-field date-fields pom-cargo-ready-date"
																		hide-details="auto"
																	>
																	</v-text-field>
																</template>
															</v-menu>
														</template>
													</v-col>
												</div>
											</v-col>
											<v-col class="pa-3 pt-0 pb-1" md="6">
												<div class="d-flex">
													<v-col class="pa-0" md="12">
														<div class="text-field-wrapper">
															<span class="text-field-label">TO</span>
															<v-autocomplete
																:height="40"
																color="#002F44"
																:items="filteredTerminalRegions"
																item-text="text"
																item-value="value"
																v-model="editItem.location_to"
																:disabled="isShipmentDetailExist.isLocationToExist ? true : false"
																dense
																outlined
																hide-details="auto"
																placeholder="Enter Location (Port, Warehouse etc.)"
																spellcheck="false"
																:menuProps="{
																	contentClass: 'po-lists-items',
																	...menuProps,
																}"
																clearable
															/>
														</div>												
													</v-col>
												</div>
											</v-col>
											<v-col class="pa-3 pt-0 pb-1" md="6">
												<span class="text-field-label">ETA</span>
												<div class="d-flex">
													<v-col class="pa-0" md="12">
														<template>
															<v-menu
																:close-on-content-click="false"
																transition="scale-transition"
																offset-y
																min-width="auto"
															>
																<template v-slot:activator="{ on, attrs }">
																	<v-text-field
																		type="date"
																		v-on="on"
																		v-bind="attrs"
																		v-model="editItem.eta"
																		:disabled="isShipmentDetailExist.isEtaExist ? true : false"
																		placeholder="MM-DD-YYYY"
																		outlined
																		class="text-fields etd-field date-fields pom-cargo-ready-date"
																		hide-details="auto"
																	>
																	</v-text-field>
																</template>
															</v-menu>
														</template>
													</v-col>
												</div>
											</v-col>
											<v-col class="pa-3 pt-0 pb-0" md="6">
												<span class="text-field-label">MODE</span>
												<!-- <div class="d-flex" v-if="createdShipmentType == 'withValidMBL'">
													<v-col class="pa-0" md="12">
														<div class="d-flex mt-2" style="background: #EBF2F5; width: fit-content; padding: 7px; border-radius: 5px;">
															<span>Ocean</span><span class="ml-2"><img src="../../../assets/icons/ocean-mode.svg" width="20px" height="20px"/></span>
														</div>
													</v-col>
												</div> -->
												<!-- <div
													v-else
													class="d-flex radio-group-wrapper radio-group-wrapper-web"
												> -->
												<div
													class="d-flex radio-group-wrapper radio-group-wrapper-web"
												>
													<div
														v-bind:key="`mode-${key}`"
														v-for="(m, key) in modes"
														:class="
															`d-flex radio-item align-center ${
																m === editItem.mode ? 'selected' : ''
															}`
														"
													>
														<label
															style="position: relative;"
															class="radio-label-wrapper"
														>
															{{ m }}
															<input
																name="mode"
																:value="m"
																class="custom-radio"
																type="radio"
																style="position: absolute; opacity: 0;"
																v-model="editItem.mode"
															/>
															<span></span>
														</label>
														<KeneticIcon
															:marginLeft="8"
															:iconName="m.toLowerCase()"
														/>
													</div>
												</div>
											</v-col>
											<v-col class="pa-3 pt-0 pb-0" md="6">
												<!-- <span class="text-field-label" v-if="createdShipmentType == 'withValidMBL'">TYPE</span> -->
												<!-- <div class="d-flex" v-if="createdShipmentType == 'withValidMBL'">
													<v-col class="pa-0" md="12">
														<div class="d-flex mt-2" style="background: #EBF2F5; width: fit-content; padding: 7px; border-radius: 5px;">
															<span>FCL</span><span class="ml-2"><img src="../../../assets/icons/ocean-mode.svg" width="20px" height="20px"/></span>
														</div>
													</v-col>
												</div> -->
												<!-- <template v-if="createdShipmentType !== 'withValidMBL'"> -->
												<template>
													<div
														class="form-label mb-1"
														v-if="filteredTypes.length > 0"
													>
														<span
															:class="
																`${
																	editItem.type == '' && shipmentMode == ''
																		? 'unselected'
																		: 'selected'
																}`
															"
															>TYPE</span
														>
													</div>
													<div
														style="width: 100% !important;"
														:class="
															`d-flex radio-group-wrapper ${
																editItem.type == '' && shipmentMode == ''
																	? 'unselected'
																	: ''
															}`
														"
													>
														<div
															v-bind:key="`type-${key}`"
															v-for="(t, key) in filteredTypes"
															:class="
																`d-flex radio-item align-center ${
																	t === 'LTL' ? 'mr-8' : ''
																} ${t === editItem.type ? 'selected' : ''}`
															"
														>
															<v-radio-group v-model="editItem.type">
																<v-radio
																	color="primary"
																	:label="t"
																	:value="t"
																></v-radio>
															</v-radio-group>
															<KeneticIcon
																:color="
																	`${
																		editItem.type === '' && shipmentMode === ''
																			? '#B4CFE0'
																			: '#6D858F'
																	}`
																"
																:marginLeft="8"
																:iconName="t.toLowerCase()"
															/>
														</div>
													</div>
												</template>
											</v-col>
											<v-col class="pa-3 pb-1 pt-0" md="6">
												<span class="text-field-label">Incoterms</span>
												<!-- <div class="d-flex" v-if="createdShipmentType == 'withValidMBL'">
													<v-col class="pa-0 mt-3" md="12">
														<span style="background: #EBF2F5; width: fit-content; padding: 7px; border-radius: 5px;">FOB</span>
													</v-col>
												</div>
												<div
													v-else
													class="radio-group-wrapper radio-group-wrapper-incoterms"
												> -->
												<div
													class="radio-group-wrapper radio-group-wrapper-incoterms"
												>
													<div
														style="float: left;"
														v-bind:key="`incoterm-${key}`"
														v-for="(m, key) in paymentTerms"
														:class="
															`d-flex radio-item align-center ${
																m === editItem.inco_term ? 'selected' : ''
															}`
														"
													>
														<label
															style="position: relative;"
															:class="`radio-label-wrapper`"
														>
															{{ m }}
															<input
																name="inco_term"
																:value="m"
																class="custom-radio"
																style="position: absolute; opacity: 0;"
																type="radio"
																v-model="editItem.inco_term"
															/>
															<span></span>
														</label>
													</div>
												</div>
											</v-col>
											<v-col class="pa-3 pb-1 pt-0" md="6">
												<div class="d-flex">
													<v-col class="pa-0" md="12">
														<div class="text-field-wrapper" style="width: 100%">
															<span class="text-field-label">Terminal</span>
															<template v-if="getMBLNumberData && getMBLNumberData.terminal_name">
																<v-text-field
																	type="text"
																	v-model="getMBLNumberData.terminal_name"
																	disabled
																	placeholder="Enter Terminal"
																	outlined
																	class="text-fields etd-field date-fields pom-cargo-ready-date"
																	hide-details="auto"
																>
																</v-text-field>
															</template>
															<template v-else>
																<v-autocomplete
																	:height="40"
																	color="#002F44"
																	:items="filteredTerminals"
																	item-text="text"
																	item-value="value"
																	v-model="editItem.terminal"
																	dense
																	outlined
																	hide-details="auto"
																	placeholder="Select Terminal"
																	spellcheck="false"
																	:menuProps="{
																		contentClass: 'po-lists-items',
																		...menuProps,
																	}"
																	clearable
																/>
															</template>
														</div>
													</v-col>
												</div>
											</v-col>
											<v-col class="pa-3 pt-3 pb-0" md="6">
												<div class="d-flex">
													<v-col class="pa-0" md="12">
														<div class="text-field-wrapper">
															<span class="text-field-label">Carrier</span>
															<template v-if="isShipmentDetailExist.isCarrierExist">
																<v-text-field
																	type="text"
																	v-model="editItem.carrier_id"
																	disabled
																	outlined
																	class="text-fields etd-field date-fields pom-cargo-ready-date"
																	hide-details="auto"
																>
																</v-text-field>
															</template>
															<template v-else>
																<v-autocomplete
																	:height="40"
																	color="#002F44"
																	:items="filteredCarriers"
																	item-text="text"
																	item-value="value"
																	v-model="editItem.carrier_id"
																	dense
																	outlined
																	hide-details="auto"
																	placeholder="Select Carrier"
																	spellcheck="false"
																	:menuProps="{
																		contentClass: 'po-lists-items',
																		...menuProps,
																	}"
																	clearable
																/>
															</template>
														</div>
													</v-col>
												</div>
											</v-col>
											<v-col class="pa-3 pt-2 pb-0" md="6">
												<span class="text-field-label">Vessel</span>
												<div class="d-flex">
													<v-col class="pa-0" md="12">
														<v-text-field
															type="text"
															v-model="editItem.vessel"
															placeholder="Enter Vessel"
															outlined
															class="text-fields etd-field"
															hide-details="auto"
														>
														</v-text-field>
													</v-col>
												</div>
											</v-col>
											<v-col class="pa-3 pt-0 pb-1" md="6">
												<span class="text-field-label">Voyage</span>
												<div class="d-flex">
													<v-col class="pa-0" md="12">
														<div class="text-field-wrapper">
															<template>
																<v-menu
																	:close-on-content-click="false"
																	transition="scale-transition"
																	offset-y
																	min-width="auto"
																>
																	<template v-slot:activator="{ on, attrs }">
																		<v-text-field
																			type="text"
																			v-on="on"
																			v-bind="attrs"
																			v-model="editItem.voyage_number"
																			placeholder="Enter Voyage"
																			outlined
																			class="text-fields etd-field date-fields pom-cargo-ready-date"
																			hide-details="auto"
																		>
																		</v-text-field>
																	</template>
																</v-menu>
															</template>
														</div>
													</v-col>
												</div>
											</v-col>
										</v-row>

										<div
											id="purchaseOrderSection"
											ref="purchaseOrderSection"
											class="d-flex flex-column purchase-orders-section mt-6"
										>
											<div style="padding-bottom: 8px;" class="content-title orders-title">Orders</div>
											<div
												v-bind:key="`poipom-${key}`"
												v-for="(poi, key) in purchaseOrderItems"
											>
												<div
													v-if="poi.layout === 'default'"
													style="margin-bottom: 16px;"
													class="purchase-order-wrapper"
												>
													<div
														id="select-autocomplete-2"
														:class="
															`select-items-wrapper select-autocomplete-wrapper select-autocomplete-wrapperpoi-${key}`
														"
													>
														<v-autocomplete
															:filter="customFilterPo"
															v-model="poi.purchase_order_id"
															spellcheck="false"
															:attach="`.select-autocomplete-wrapperpoi-${key}`"
															class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
															:items="poi.purchase_order_options"
															placeholder="Enter PO"
															outlined
															item-text="po_number"
															item-value="id"
															:menu-props="{
																contentClass: 'po-lists-items with-quick-btn',
																...menuProps,
															}"
															hide-details="auto"
															@change="updateProducts(poi, key)"
															@click="ordersSearchText = ''"
															clearable
														>
															<template v-slot:item="{ item }">
																<div
																	class="d-flex flex-column"
																	style="width: 100%;"
																>
																	<div
																		style="width: 100%; padding-bottom: 12px;"
																		class="d-flex first-row justify-space-between"
																	>
																		<div class="d-flex flex-row align-center">
																			<span>{{
																				editItem.role === "shipper" ? "SO#" : "PO#"
																			}}</span>
																			<span style="padding-left: 2px;">{{
																				item.po_number
																			}}</span>
																		</div>
																		<div style="font-size: 14px !important;">
																			{{ currencyNumberFormat(item.total) }}
																		</div>
																	</div>
																	<div
																		class="d-flex second-row"
																		style="width: 100%; padding-bottom: 4px;"
																	>
																		{{ getWarehouseName(item) }}
																	</div>
																	<div class="d-flex third-row">
																		{{ item.ship_to }}
																	</div>
																	<div
																		style="width: 100%; padding-bottom: 8px;"
																		class="d-flex fourth-row flex-row justify-space-between"
																	>
																		<div>
																			<span>Date:</span>
																			<span>{{
																				" " + formatDate(item.created_at)
																			}}</span>
																		</div>
																		<div>
																			<span>CRD:</span>
																			<span>{{
																				" " + formatDate(item.cargo_ready_date)
																			}}</span>
																		</div>
																	</div>
																	<div class="d-flex last-row" style="width: 100%;">
																		<a class="last-row-status">
																			{{
																				item.status === "pending"
																					? "Pending"
																					: "Partially Booked"
																			}}
																		</a>
																		<span
																			class="production-status-label ml-1"
																			v-if="item.production_status_name"
																			>{{ item.production_status_name }}</span
																		>
																	</div>
																</div>
															</template>
															<template v-slot:no-data>
																<div
																	class="no-data-found"
																	v-if="searchOrders && !ordersSearchText"
																>
																	<img
																		src="@/assets/icons/empty-document-blue.svg"
																		width="24px"
																		height="24px"
																		alt=""
																	/>
																	<h3 class="text-center">No Purchase Order</h3>
																	<p class="mb-0 text-center">
																		You have not added any purchase order yet.
																		Quickly add one here for this tracking the shipment.
																	</p>
																</div>
																<div class="no-data-found" v-else>
																	<img
																		src="@/assets/icons/empty-document-blue.svg"
																		width="24px"
																		height="24px"
																		alt=""
																	/>
																	<h3 class="text-center">No Match Found</h3>
																	<p class="mb-0 text-center">
																		There is no PO with ‘{{ ordersSearchText }}’ as
																		order ID.
																	</p>
																</div>
															</template>
														</v-autocomplete>
														<div
															class="checkbox-wrapper-create checkbox-wrapper-desktop"
														>
															<label
																:class="`${poi.selectAll ? 'checked' : ''}`"
																style="position: relative;"
															>
																<generic-icon
																	:marginLeft="0"
																	:iconName="
																		`${poi.selectAll ? 'checked' : 'not-checked'}`
																	"
																></generic-icon>
																<input
																	@click.prevent="selectAllProducts(poi, key)"
																	style="position: absolute; opacity: 0;"
																	type="checkbox"
																	:checked="poi.selectAll"
																	class=""
																	:disabled="
																		purchaseOrderItems.length == 0 || poi.loading
																	"
																/>
																<span
																	style="color: #4A4A4A; font-weight: 400; font-size: 14px;"
																	>Select All Products</span
																>
															</label>
														</div>
														<div class="delete-btn-wrapper">
															<v-btn
																class="btn-white mr-4"
																@click="removePurchaseOrderItem(key)"
															>
																<custom-icon
																	marginLeft="3px"
																	iconName="trash-can"
																	color="#ff5252"
																></custom-icon>
															</v-btn>
														</div>
													</div>
													<div class="table-wrapper">
														<v-data-table
															:headers="headersProducts"
															:items="filterProducts(poi)"
															mobile-breakpoint="769"
															:page="1"
															:item-class="getItemClass"
															:items-per-page="100"
															class="purchase-orders-table"
															hide-default-footer
															style="box-shadow: none !important"
															fixed-header
															hide-default-header
															ref="create-shipment-purchase-order-table"
														>
															<template v-slot:header="{ props: { headers } }">
																<thead>
																	<tr>
																		<th
																			v-for="(item, index) in headers"
																			:key="index"
																			class="op-"
																			role="column-header"
																			:aria-label="item.text"
																			:style="
																				`color: #6D858F !important;padding-right: 12px;padding-left: 12px !important;width: ${item.width};text-align: ${item.textAlign}`
																			"
																			scope="col"
																		>
																			{{ item.text }}
																		</th>
																	</tr>
																</thead>
															</template>

															<template v-slot:[`item.product_id`]="{ item }">
																<div
																	id="select-autocomplete-products"
																	@click="selectOpenedProductBox(item)"
																>
																	<div>
																		<v-autocomplete
																			v-if="
																				typeof item.addSpecial == 'undefined' &&
																					typeof item.special == 'undefined'
																			"
																			:filter="customFilterProduct"
																			v-model="item.product_id"
																			spellcheck="false"
																			class="select-product select-purchase-order"
																			:items="poi.product_options"
																			@change="
																				updateProductPurchaseOrder(
																					poi.product_options,
																					item,
																					key
																				)
																			"
																			placeholder="Select Product"
																			outlined
																			item-text="product.name"
																			item-value="product_id"
																			:menu-props="{
																				contentClass:
																					'po-lists-items order-items-lists',
																				...menuProps,
																			}"
																			hide-details="auto"
																			clearable
																			@click="orderProductsSearchText = ''"
																		>
																			<template v-slot:item="{ item }">
																				<div
																					class="d-flex flex-row justify-space-between product-item-wrapper"
																				>
																					<div
																						style="width: 50%;"
																						class="d-flex flex-column"
																					>
																						<div>
																							<span class="product-category-sku"
																								>#{{
																									item.product.category_sku
																								}}</span
																							>
																						</div>
																						<div>
																							<p>{{ item.product.name }}</p>
																							<p class="product-unit-price">
																								${{
																									item.product.unit_price !== null
																										? item.product.unit_price.toFixed(
																												2
																											)
																										: "0.00"
																								}}
																							</p>
																						</div>
																					</div>
																					<div
																						style="width: 50%;"
																						class="d-flex justify-end"
																					>
																						<img
																							class="product-image"
																							:src="getImgUrl(item.product.image)"
																							alt=""
																						/>
																					</div>
																				</div>
																			</template>
																			<template v-slot:no-data>
																				<div
																					class="no-data-found"
																					v-if="
																						searchProducts &&
																							!orderProductsSearchText
																					"
																				>
																					<img
																						src="@/assets/icons/empty-product-icon.svg"
																						width="24px"
																						height="24px"
																						alt=""
																					/>
																					<h3 class="text-center">No Product</h3>
																					<p class="mb-0 text-center">
																						You have not added any product yet.
																						Quickly add one here for this order.
																					</p>
																				</div>
																				<div class="no-data-found" v-else>
																					<img
																						src="@/assets/icons/empty-product-icon.svg"
																						width="24px"
																						height="24px"
																						alt=""
																					/>
																					<h3 class="text-center">
																						No Match Found
																					</h3>
																					<p class="mb-0 text-center">
																						There is no product with ‘{{
																							orderProductsSearchText
																						}}’ as SKU ID.
																					</p>
																				</div>
																			</template>
																		</v-autocomplete>
																	</div>
																	<div
																		class="product-error-message red--text"
																		v-if="
																			typeof item.addSpecial == 'undefined' &&
																				typeof item.special == 'undefined' &&
																				validateProduct(item, key) === 'error'
																		"
																	>
																		This product has already been selected.
																	</div>

																	<div
																		v-if="
																			item.purchase_order_id !== '' &&
																				typeof item.addSpecial !== 'undefined'
																		"
																		class="add-product-purchase-order-wrapper add-product-item"
																	>
																		<a
																			style="cursor: pointer; display: flex; flex-direction: row;"
																			@click="addProductToPurchaseOrders(key)"
																		>
																			<span style="margin-right: 6.3px;">
																				<generic-icon
																					color="#0171A1"
																					iconName="plus"
																				></generic-icon>
																			</span>
																			<span>{{ "Add Product" }}</span>
																		</a>
																	</div>
																</div>
															</template>

															<template
																v-slot:[`item.product_description`]="{ item }"
															>
																<div
																	v-if="
																		typeof item.special == 'undefined' &&
																			typeof item.addSpecial == 'undefined'
																	"
																>
																	<div
																		style="text-align: right !important;"
																	>
																		<v-text-field
																			height="40px"
																			color="#002F44"
																			width="200px"
																			v-model="item.description"
																			dense
																			class="unit"
																			placeholder="Product Description"
																			outlined
																			hide-details="auto"
																			readonly
																		/>
																	</div>
																</div>
															</template>

															<template v-slot:[`item.addAll`]="{ item }">
																<div
																	v-if="typeof item.special == 'undefined' && typeof item.addSpecial == 'undefined'"
																	class="d-flex product-add-all-wrapper"
																>
																	<label
																		:class="`${item.addAll ? 'checked' : ''}`"
																		style="position: relative;"
																	>
																		<generic-icon
																			:iconName="
																				`${item.addAll ? 'checked' : 'not-checked'}`
																			"
																		></generic-icon>
																		<input
																			@click.prevent="addAllCartons(item, key)"
																			style="position: absolute; opacity: 0;"
																			type="checkbox"
																			v-model="item.addAll"
																			:checked="item.addAll"
																		/>
																	</label>
																</div>
															</template>

															<template v-slot:[`item.carton`]="{ item }">
																<v-tooltip
																	left
																	:content-class="
																		`carton-tooltip ${cartonTooltipClass(item)}`
																	"
																>
																	<template v-slot:activator="{ on, attrs }">
																		<div
																			v-on="on"
																			v-bind="attrs"
																			:class="
																				`${
																					cartonTooltipClass(item) ===
																					'tooltip-has-error'
																						? 'carton-wrapper-error'
																						: ''
																				}`
																			"
																		>
																			<v-text-field
																				v-if="
																					typeof item.special == 'undefined' &&
																						typeof item.addSpecial == 'undefined'
																				"
																				height="40px"
																				color="#002F44"
																				width="200px"
																				:disabled="item.addAll"
																				v-model="item.carton"
																				type="number"
																				dense
																				:rules="[
																					(v) => !!v || 'Carton is required.',
																					cartonRules(item),
																				]"
																				class="carton"
																				placeholder=""
																				@change="updateUnit(item, key)"
																				outlined
																				:max="item.unship_cartons"
																				min="1"
																				hide-details="auto"
																			>
																			</v-text-field>
																		</div>
																	</template>
																	<span>
																		{{
																			cartonTooltipMessage(item, poi.quickAddOrder)
																		}}
																	</span>
																</v-tooltip>
															</template>

															<template v-slot:[`item.volume`]="{ item }">
																<v-text-field
																	v-if="
																		typeof item.special == 'undefined' &&
																			typeof item.addSpecial == 'undefined'
																	"
																	height="40px"
																	color="#002F44"
																	width="200px"
																	v-model="item.volume"
																	type="number"
																	dense
																	class="unit"
																	placeholder="Volume"
																	outlined
																	hide-details="auto"
																>
																</v-text-field>
															</template>

															<template v-slot:[`item.weight`]="{ item }">
																<v-text-field
																	v-if="
																		typeof item.special == 'undefined' &&
																			typeof item.addSpecial == 'undefined'
																	"
																	height="40px"
																	color="#002F44"
																	width="200px"
																	v-model="item.weight"
																	type="number"
																	dense
																	class="unit"
																	placeholder="0.00"
																	outlined
																	hide-details="auto"
																>
																</v-text-field>
															</template>

															<template v-slot:[`item.unit`]="{ item }">
																<v-text-field
																	v-if="
																		typeof item.special == 'undefined' &&
																			typeof item.addSpecial == 'undefined'
																	"
																	height="40px"
																	color="#002F44"
																	width="200px"
																	:disabled="!item.quickAddProduct"
																	v-model="item.unit"
																	@keyup="updateCarton(item, key)"
																	type="number"
																	dense
																	class="unit"
																	placeholder="Unit"
																	outlined
																	hide-details="auto"
																	min="1"
																>
																</v-text-field>
															</template>

															<template v-slot:[`item.cargo_ready_date`]="{ item }">
																<div style="text-align: right !important;">
																	{{
																		item.cargo_ready_date == null ||
																		item.cargo_ready_date == ""
																			? "--"
																			: item.cargo_ready_date
																	}}
																</div>
															</template>

															<template v-slot:[`item.unit_price`]="{ item }">
																<div
																	v-if="
																		typeof item.special == 'undefined' &&
																			typeof item.addSpecial == 'undefined'
																	"
																>
																	<v-text-field
																		height="40px"
																		color="#002F44"
																		width="200px"
																		v-model="item.unit_price"
																		type="number"
																		dense
																		class="unit"
																		placeholder="0"
																		outlined
																		hide-details="auto"
																		:disabled="!item.quickAddProduct"
																	/>
																</div>
															</template>

															<template v-slot:[`item.amount`]="{ item }">
																<div>
																	<div
																		v-if="
																			typeof item.special == 'undefined' &&
																				typeof item.addSpecial == 'undefined'
																		"
																		class="d-flex amount"
																	>
																		{{ "$" + calculateAmount(item) }}
																	</div>
																</div>
															</template>

															<template
																v-slot:[`item.commodity_description`]="{ item }"
															>
																<div
																	style="text-align: right !important;"
																	class="product-classification-description"
																	v-if="
																		typeof item.special == 'undefined' &&
																			typeof item.addSpecial == 'undefined'
																	"
																>
																	<v-text-field
																		height="40px"
																		color="#002F44"
																		width="200px"
																		v-model="
																			item.product_classification_description
																		"
																		type="text"
																		dense
																		class="unit"
																		placeholder="Enter Description"
																		outlined
																		hide-details="auto"
																		:disabled="!item.quickAddProduct"
																	/>
																</div>
															</template>

															<template v-slot:[`item.hs_code`]="{ item }">
																<div
																	style="text-align: right !important;"
																	v-if="
																		typeof item.special == 'undefined' &&
																			typeof item.addSpecial == 'undefined'
																	"
																>
																	<v-text-field
																		height="40px"
																		color="#002F44"
																		width="200px"
																		v-model="item.classification_code"
																		type="text"
																		dense
																		class="unit"
																		placeholder="Enter Code"
																		outlined
																		hide-details="auto"
																		:disabled="!item.quickAddProduct"
																	/>
																</div>
															</template>

															<template v-slot:[`item.action`]="{ item }">
																<div
																	:class="
																		`action-wrapper ${
																			typeof item.special == 'undefined' &&
																			typeof item.addSpecial == 'undefined'
																				? 'x-wrapper'
																				: ''
																		}`
																	"
																>
																	<a
																		v-if="
																			typeof item.special == 'undefined' &&
																				typeof item.addSpecial == 'undefined'
																		"
																		@click.stop="
																			removeProductFromPurchaseOrders(key, item)
																		"
																		style="cursor: pointer;"
																		class="d-flex"
																	>
																		<generic-icon
																			:color="
																				item.product_id == ''
																					? '#B4CFE0'
																					: '#F93131'
																			"
																			iconName="trash-product-light"
																		></generic-icon>
																	</a>
																</div>
															</template>
														</v-data-table>
														<div
															v-if="filterProducts(poi).length > 0"
															class="d-flex flex-row order-total-wrapper"
														>
															<div class="order-total-item">
																<p>
																	Total Cartons:
																	{{ calculateTotals(poi, "carton") }}
																</p>
																<p>
																	Total Volume: {{ calculateTotals(poi, "volume") }}
																</p>
																<p>
																	Total Weight: {{ calculateTotals(poi, "weight") }}
																</p>
																<p>
																	Cargo Ready Date:
																	{{
																		poi.cargo_ready_date
																			? poi.cargo_ready_date
																			: "N/A"
																	}}
																</p>
															</div>
														</div>
													</div>
												</div>
												<div
													v-if="poi.layout === 'manual'"
													style="margin-bottom: 16px;"
													class="purchase-order-wrapper"
												>
													<div
														id="select-autocomplete-4"
														:class="
															`select-items-wrapper select-autocomplete-wrapper select-autocomplete-wrapperpom-${key}`
														"
													>
														<div style="margin-right: 8px;">
															<input-text
																:noLabel="true"
																:field.sync="poi.purchase_order_number"
																labelColor="#819FB2"
																:inputFontWeight="400"
																:inputFontSize="14"
																:placeholderText="
																	`${
																		editItem.role === 'shipper'
																			? 'Enter SO'
																			: 'Enter PO'
																	}`
																"
															>
																<template v-slot:input="{ mainContent }">
																	<v-text-field
																		:value="poi.purchase_order_number"
																		:height="40"
																		:color="mainContent.textColor"
																		width="200px"
																		type="text"
																		dense
																		:class="
																			`custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize}`
																		"
																		@change="mainContent.updateValue"
																		:placeholder="mainContent.placeholderText"
																		outlined
																		hide-details="auto"
																		:rules="[
																			(v) => !!v || 'Order number is required',
																		]"
																		class="manual-order-number"
																	>
																	</v-text-field>
																</template>
															</input-text>
														</div>
														<div class="text-field-wrapper dates-wrapper">
															<v-menu
																v-model="poi.menuCargoReadyDate"
																:close-on-content-click="true"
																transition="scale-transition"
																offset-y
																min-width="auto"
															>
																<template v-slot:activator="{ on, attrs }">
																	<v-text-field
																		class="text-fields etd-field date-fields pom-cargo-ready-date"
																		placeholder="MM-DD-YYYY"
																		outlined
																		v-bind="attrs"
																		v-on="on"
																		type="text"
																		hide-details="auto"
																		clear-icon
																		:height="40"
																		v-model="poi.cargo_ready_date"
																		@input="
																			(val) => updateCargoReadyDateInput(poi, val)
																		"
																		append-icon="mdi-calendar"
																	/>
																</template>
																<v-date-picker
																	v-model="poi.cargo_ready_date"
																></v-date-picker>
															</v-menu>
														</div>
														<div class="delete-btn-wrapper">
															<v-btn
																class="btn-white mr-4"
																@click="removePurchaseOrderManualItem(key)"
															>
																<custom-icon
																	marginLeft="3px"
																	iconName="trash-can"
																	color="#ff5252"
																></custom-icon>
															</v-btn>
														</div>
													</div>
													<div
														style="margin-top: 35px; padding-left: 16px; padding-right: 16px;"
														class="d-flex flex-row"
													>
														<div style="width: 33%;margin-right: 16px;">
															<input-text
																:noLabel="false"
																label="Total Carton"
																:field.sync="poi.total_cartons"
																labelColor="#819FB2"
																:inputFontWeight="400"
																:inputFontSize="14"
																placeholderText="Enter Total Carton"
															>
																<template v-slot:input="{ mainContent }">
																	<v-text-field
																		:height="40"
																		:color="mainContent.textColor"
																		width="200px"
																		type="text"
																		dense
																		:class="
																			`custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize}`
																		"
																		v-model="poi.total_cartons"
																		:placeholder="mainContent.placeholderText"
																		outlined
																		hide-details="auto"
																		:rules="[
																			(v) => !!v || 'Total Carton is required',
																		]"
																	>
																	</v-text-field>
																</template>
															</input-text>
														</div>
														<div style="width: 33%;margin-right: 16px;">
															<input-text
																label="Total Volume"
																:field.sync="poi.total_volumes"
																labelColor="#819FB2"
																:inputFontWeight="400"
																:inputFontSize="14"
																placeholderText="Enter Total Volume"
															>
																<template v-slot:input="{ mainContent }">
																	<v-text-field
																		:height="40"
																		:color="mainContent.textColor"
																		width="200px"
																		type="text"
																		dense
																		:class="
																			`custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize}`
																		"
																		v-model="poi.total_volumes"
																		:placeholder="mainContent.placeholderText"
																		outlined
																		hide-details="auto"
																		:rules="[
																			(v) => !!v || 'Total Volume is required',
																		]"
																	>
																	</v-text-field>
																</template>
															</input-text>
														</div>
														<div style="width: 33%;">
															<input-text
																label="Total Weight"
																:field.sync="poi.total_weights"
																labelColor="#819FB2"
																placeholderText="Enter Total Weight"
																:inputFontWeight="400"
																:inputFontSize="14"
															>
																<template v-slot:input="{ mainContent }">
																	<v-text-field
																		:height="40"
																		:color="mainContent.textColor"
																		width="200px"
																		type="text"
																		dense
																		:class="
																			`custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize}`
																		"
																		v-model="poi.total_weights"
																		:placeholder="mainContent.placeholderText"
																		outlined
																		hide-details="auto"
																		:rules="[
																			(v) => !!v || 'Total Weight is required',
																		]"
																	>
																	</v-text-field>
																</template>
															</input-text>
														</div>
													</div>
													<div
														style="padding-left: 16px; padding-right: 16px; margin-top: 16px;"
														class="flex-row"
													>
														<generic-table
															:headers="headersDocuments"
															:items.sync="poi.documents"
															:pomKey="key"
															:shipmentId="createSingleShipmentId"
															textColor="#4A4A4A"
															headerBackground="#F7F7F7"
														>
														</generic-table>
													</div>
												</div>
											</div>
											<div
												style="padding-left: 0px !important;"
												class="d-flex add-product-purchase-order-wrapper add-purchase-order-wrapper flex-row"
											>
												<v-btn
													class="btn-white mr-4 shipment-header-button-btn add-purchase-order-btn-wrapper btn-white-custom"
													@click="addPurchaseOrderItem()"
													:disabled="defaultBtnDisable"
												>
													<span class="add-purchase-order-span">+ Add Order</span>
												</v-btn>
												<v-btn
													class="btn-white mr-4 shipment-header-button-btn add-purchase-order-btn-wrapper btn-white-custom"
													@click="addManualPurchaseOrder()"
													:disabled="manualBtnDisable"
												>
													<span class="add-purchase-order-span add-manual-po-span">Add Manual Order</span>
												</v-btn>
											</div>
										</div>

										<div
											id="purchaseOrderSection"
											ref="purchaseOrderSection"
											class="d-flex flex-column purchase-orders-section mt-6"
										>
											<div style="padding-bottom: 8px;" class="content-title orders-title">Cargo Details</div>
											<v-row class="mt-1">
												<v-col class="pa-3 pl-0 pl-3" md="4">
													<span style="color: #6D858F">Total Cartons</span>
													<div class="d-flex">
														<v-col class="pa-0 mt-3" md="12">
															<template>
																<v-menu
																	:close-on-content-click="false"
																	transition="scale-transition"
																	offset-y
																	min-width="auto"
																>
																	<template v-slot:activator="{ on, attrs }">
																		<v-text-field
																			type="text"
																			v-on="on"
																			v-bind="attrs"
																			v-model="editItem.total_cartons"
																			placeholder="Enter Carton Count"
																			outlined
																			class="text-fields etd-field date-fields pom-cargo-ready-date"
																			hide-details="auto"
																		>
																		</v-text-field>
																	</template>
																</v-menu>
															</template>
														</v-col>
													</div>
												</v-col>
												<v-col class="pa-3 pl-0" md="4">
													<span style="color: #6D858F">Total Volume</span>
													<div class="d-flex">
														<v-col class="pa-0 mt-3" md="12">
															<template>
																<v-menu
																	:close-on-content-click="false"
																	transition="scale-transition"
																	offset-y
																	min-width="auto"
																>
																	<template v-slot:activator="{ on, attrs }">
																		<v-text-field
																			type="text"
																			v-on="on"
																			v-bind="attrs"
																			v-model="editItem.total_volume"
																			placeholder="Enter Volume"
																			outlined
																			class="text-fields etd-field date-fields pom-cargo-ready-date"
																			hide-details="auto"
																		>
																		</v-text-field>
																	</template>
																</v-menu>
															</template>
														</v-col>
													</div>
												</v-col>
												<v-col class="pa-3 pl-0" md="4">
													<span style="color: #6D858F">Total Weight</span>
													<div class="d-flex">
														<v-col class="pa-0 mt-3" md="12">
															<template>
																<v-menu
																	:close-on-content-click="false"
																	transition="scale-transition"
																	offset-y
																	min-width="auto"
																>
																	<template v-slot:activator="{ on, attrs }">
																		<v-text-field
																			type="text"
																			v-on="on"
																			v-bind="attrs"
																			v-model="editItem.total_weight"
																			placeholder="Enter Weight"
																			outlined
																			class="text-fields etd-field date-fields pom-cargo-ready-date"
																			hide-details="auto"
																		>
																		</v-text-field>
																	</template>
																</v-menu>
															</template>
														</v-col>
													</div>
												</v-col>
											</v-row>

										</div>

									</div>
								</div>
							</v-form>
						</div>
					</div>
				</v-card-text>
				<v-card-actions>
					<div class="d-flex footer">
						<v-btn class="save-btn btn-blue" text @click.stop="updateShipment()" :disabled="getUpdateShipmentInfoLoading">
							<span v-if="getUpdateShipmentInfoLoading">Shipment Updating...</span>
							<span v-else>Save Information</span>
						</v-btn>
						<v-btn class="save-btn btn-white" text style="margin-left: 1%;" @click.stop="close()" :disabled="getUpdateShipmentInfoLoading">
							<span>Cancel</span>
						</v-btn>
					</div>
				</v-card-actions>
			</v-card>
		</v-dialog>

	</div>
</template>

<style lang="scss">
@import "./scss/addMoreShipmentInfoDialog.scss";
@import "./scss/fields.scss";
</style>

<script>
import GenericIcon from "../../Icons/GenericIcon";
import _ from "lodash";
// import BookingItem from "./Structures/BookingItem";
import { mapGetters, mapActions } from "vuex";
import headers from "./Datas/tableHeaders";
import CustomIcon from "../../Icons/CustomIcon";
import KeneticIcon from "@/components/Icons/KeneticIcon";
import InputText from "./InputTexts/InputText";
import GenericTable from "./Tables/GenericTable";
import moment from "moment";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "AddMoreShipmentInfoDialog",
	props: [
		"show",
		"reference",
		"className",
		"createSingleShipmentId",
		"createdShipmentType",
		"getShipmentDetailsToEditComp",
		"editItem",
		"isShipmentDetailExist",
	],
	data: () => ({
		isAddMoreInfo: false,
		sidebarItems: [
			{
				icon: "general",
				label: "General Information",
				width: 20,
				selected: true,
				id: "generalInformationSection",
				reference: "generalInformationSection",
				height: 20,
			},
			{
				icon: "po-icon",
				label: "Purchase Orders",
				width: 20,
				selected: false,
				id: "purchaseOrderSection",
				reference: "purchaseOrderSection",
				height: 20,
			},
		],
		loaded: true,
		// editItem: BookingItem,
		menuProps: {
			bottom: true,
			offsetY: true,
		},
		resourcesLoaded: 0,
		resourcesLimit: 6,
		manualBtnDisable: false,
		defaultBtnDisable: false,
		purchaseOrderItems: [],
		headersProducts: headers.shipmentTrackingProducts.data,
		searchOrders: true,
		headersDocuments: headers.documents.data,
		modes: ["Ocean", "Air"],
		shipmentMode: "",
		types: ["FCL", "LTL"],
		paymentTerms: ["FOB", "CIF", "DDU", "DDP", "EXW", "FCA", "DAP"],
		shipmentIncoTerm: "",
		userDetails: {},
		searchProducts: true,
		orderProductsSearchText: "",
		cartonRules(i) {
			return (v) => {
				if (parseInt(v) == 0) {
					return "Carton should be greater than 0";
				} else {
					if (parseFloat(v) > i.unship_cartons) {
						return "Limit only";
					} else {
						return true;
					}
				}
			};
		},
		// containerItems: [],
	}),
	components: {
		GenericIcon,
		CustomIcon,
		KeneticIcon,
		InputText,
		GenericTable,
	},
	computed: {
		...mapGetters({getTerminalRegions: "getTerminalRegions", getUser: "getUser", getOrders: "getOrders", getTerminals: "getTerminals", getCarriers: "getCarriers", getMBLNumberData: "booking/getMBLNumberData", getUpdateShipmentInfoLoading: "getUpdateShipmentInfoLoading"}),
		filteredTerminalRegions() {
			let newTerminalRegions = [];
			if (this.loaded) {
				this.getTerminalRegions.map((gt) => {
					newTerminalRegions.push({
						text: gt.name,
						value: gt.id,
					});
				});
			}
			return newTerminalRegions;
		},
		purchaseOrderOptions() {
			let options = this.$store.getters["getOrders"];
			options = _.filter(
				options,
				(e) => e.status === "pending" || e.status === "partial_shipped"
			);
			return options;
		},
		filteredTypes() {
			//map types
			let types = [
				{
					label: "Air",
					options: [],
				},
				{
					label: "Ocean",
					options: ["FCL", "LCL"], //"Consolidated"
				},
			];

			let setMode = this.shipmentMode;
			if (this.shipmentMode === "" || this.shipmentMode == "N/A")
				setMode = "Ocean";

			//return options
			let getOptions = _.find(types, (e) => e.label === setMode);
			return getOptions.options;
		},
		filteredTerminals() {
			let newTerminals = [];
			if (this.loaded) {
				this.getTerminals.map((gt) => {
					newTerminals.push({
						text: gt.name,
						value: gt.id,
					});
				});
			}
			return newTerminals;
		},
		filteredCarriers() {
			let newCarriers = [];
			if (this.loaded) {
				this.getCarriers.map((gc) => {
					newCarriers.push({
						text: gc.name,
						value: gc.id,
					});
				});
			}
			return newCarriers;
		},
	},
	beforeMount() {
		this.$store.dispatch("fetchTerminalRegions", { is_paginate: 0 })
		.then(() => {
			this.resourcesLoaded++;
			if (this.resourcesLoaded === this.resourcesLimit) {
				this.loaded = true;
			}
		})
		.catch((e) => {
			this.resourcesLoaded++;
			if (this.resourcesLoaded === this.resourcesLimit) {
				this.loaded = true;
			}
			console.log(e);
		});

		// get user details...
		try {
			this.userDetails = JSON.parse(this.getUser);
		} catch (e) {
			this.userDetails = this.getUser;
		}

		let payload = {
			orderType: "PO",
			customer_id: this.userDetails.default_customer.id,
		};
		
		// get fetch orders api...
		this.fetchOrders(payload);

		this.purchaseOrderItems.map((poi, key) => {
			this.purchaseOrderItems[key].purchase_order_id = poi.id;
			this.purchaseOrderItems[key].products.map((po, keySecond) => {
				this.purchaseOrderItems[key].products[keySecond].cargo_ready_date =
					poi.cargo_ready_date;
			});
			this.purchaseOrderItems[key].purchase_order_id = poi.id;
			if (!this.getMarkingBookedExternal || !this.getEditingShipment) {
				this.purchaseOrderOptions = _.filter(
					this.purchaseOrderOptions,
					(e) => e.status === "pending" || e.status === "partially_shipped"
				);
			}

			this.purchaseOrderItems[key].purchase_order_options = this.getOrders;
			this.ordersLoading = false;
		});
	},
	async mounted() {

		await this.fetchCarriers()
			.then(() => {
				this.resourcesLoaded++;
				if (this.resourcesLoaded === this.resourcesLimit) {
					this.loaded = true;
				}
			})
			.catch((e) => {
				this.resourcesLoaded++;
				if (this.resourcesLoaded === this.resourcesLimit) {
					this.loaded = true;
				}
				console.log(e);
			});

		await this.fetchTerminals({ is_paginate: 0 })
			.then(() => {
				this.resourcesLoaded++;
				if (this.resourcesLoaded === this.resourcesLimit) {
					this.loaded = true;
				}
			})
			.catch((e) => {
				this.resourcesLoaded++;
				if (this.resourcesLoaded === this.resourcesLimit) {
					this.loaded = true;
				}
				console.log(e);
			});

		//check containers
		// let containers = this.editItem.containers_group;

		// containers = Array.isArray(containers)
		// 	? containers
		// 	: JSON.parse(containers);

		// if (containers.length > 0) {
		// 	containers.map((c, kk) => {
		// 		containers[kk].size = parseInt(c.size);
		// 	});
		// }
		// this.containerItems = containers === null ? [] : containers;

	},
	methods: {
		...mapActions([
			"fetchOrders",
			"fetchCarriers",
			"fetchTerminals",
			"updateShipmentInfo"
		]),
		...globalMethods,
		close() {
			this.$emit("close");
		},
		selectPage(item) {
			let findIndex = _.findIndex(this.sidebarItems, { icon: item.icon });
			this.sidebarItems.map((sidebarItem, key) => {
				this.sidebarItems[key].selected = false;
			});
			this.sidebarItems[findIndex].selected = true;
			if (!this.isMobile) {
				this.$refs[this.sidebarItems[findIndex].reference].scrollIntoView({
					block: "start",
					behavior: "smooth",
				});
			}
		},
		addPurchaseOrderItem() {
			let newOptions = this.setPurchaseOrderOptions();
			this.purchaseOrderItems.push({
				id: new Date().getTime(),
				products: [],
				purchase_order_id: "",
				product_options: [],
				purchase_order_options: newOptions,
				selectAll: false,
				layout: "default",
				quickAddOrder: false,
				cargo_ready_date: "",
				order_number: "",
			});
			this.ordersSearchText = "";
			this.checkOrdersButton();
		},
		setPurchaseOrderOptions() {
			let { selectedOrderIds } = this;
			this.purchaseOrderId++;

			selectedOrderIds = [];

			let items = this.purchaseOrderItems;
			let newOptions = [];

			if (items.length > 0) {
				items.map((i) => {
					if (selectedOrderIds.indexOf(i.purchase_order_id) == -1)
						selectedOrderIds.push(i.purchase_order_id);
				});
			}

			this.purchaseOrderOptions.map((i) => {
				if (selectedOrderIds.length > 0) {
					if (selectedOrderIds.indexOf(i.id) == -1) {
						newOptions.push(i);
					}
				} else {
					newOptions.push(i);
				}
			});

			this.selectedOrderIds = selectedOrderIds;
			return newOptions;
		},
		checkOrdersButton() {
			if (this.purchaseOrderItems.length > 0) {
				let _this = this;
				_this.purchaseOrderItems.forEach(function(item) {
					if (item.layout === "default") {
						_this.manualBtnDisable = true;
						_this.defaultBtnDisable = false;
					} else {
						_this.manualBtnDisable = false;
						_this.defaultBtnDisable = true;
					}
				});
			} else {
				this.defaultBtnDisable = false;
				this.manualBtnDisable = false;
			}
		},
		customFilterPo(item, queryText, itemText) {
			const data = item.po_number + item.ship_to.toLowerCase() + itemText.toLocaleLowerCase();
			this.ordersSearchText = queryText;
			this.searchOrders = data.indexOf(queryText.toLowerCase()) > -1;
			return this.searchOrders;
		},
		filterProducts(po) {
			let { products } = po;

			let newProducts = products;
			let validProducts = _.filter(
				products,
				(e) =>
					typeof e.addSpecial == "undefined" && typeof e.special == "undefined"
			);
			if (po.selectAll || (validProducts.length > 0 && po.product_options.length > 0 && validProducts.length == po.product_options.length)) {
				po.selectAll = true;
			}
			return newProducts;
		},
		getItemClass(item) {
			if (typeof item.addSpecial == "undefined" && typeof item.special === "undefined") {
				if (item.addAll) {
					return "item-row item-row-add";
				} else {
					return "item-row";
				}
			} else {
				return "item-row-special";
			}
		},
		removePurchaseOrderItem(key) {
			this.purchaseOrderItems.splice(key, 1);
			this.getProductsDescriptions();
			this.checkOrdersButton();
		},
		addManualPurchaseOrder() {
			this.purchaseOrderItems.push({
				id: new Date().getTime(),
				products: [],
				supplier_id: 0,
				documents: [],
				cargo_ready_date: "",
				menuCargoReadyDate: false,
				selectAll: false,
				containers: [],
				purchase_order_number: "",
				total_cartons: 0,
				total_volumes: 0,
				total_weights: 0,
				layout: "manual",
				buyer_id: 0,
			});
			this.checkOrdersButton();
		},
		getProductsDescriptions() {
			let productList = this.purchaseOrderItems.map(function(item) {
				return item.products.map((pr) => pr.product_id);
			});
			productList = _.uniq(_.compact(productList.flat()));

			let productOptions = [];
			this.purchaseOrderItems.forEach(function(item) {
				productOptions.push(item.product_options);
			});

			productOptions = productOptions.flat();

			let productsDescription = [];

			productList.forEach((element) => {
				let findDescription = _.find(
					productOptions,
					(e) => e.product_id == element
				);
				findDescription?.product?.product_classification_description
					? productsDescription.push(
							findDescription?.product?.product_classification_description
					  )
					: [];
			});

			if (productsDescription.length > 0) {
				this.editItem.commodities_info = productsDescription.join(", ");
			} else {
				this.editItem.commodities_info = "";
			}
		},
		removePurchaseOrderManualItem(key) {
			this.purchaseOrderItems.splice(key, 1);
			this.checkOrdersButton();
		},
		selectAllProducts(item, key) {
			if (item.purchase_order_id !== "") {
				this.purchaseOrderItems[key].products = [];

				if (!item.selectAll) {
					this.purchaseOrderItems[key].product_options.map((poi) => {
						let carton = 1;
						let unit = poi.units_per_carton * carton;
						let volume;

						if (poi.product.volume !== null) {
							volume = poi.product.volume * unit;

							volume =
								this.countShipmentsDecimals(volume) > 5
									? volume.toFixed(5)
									: volume;
							volume = volume == 0 ? 0.00001 : volume;
						} else {
							volume = 0.00001;
						}

						let weight =
							poi.product.weight !== null ? poi.product.weight * unit : 0;

						this.purchaseOrderItems[key].products.push({
							product_id: poi.product_id,
							addAll: false,
							cartonShow: true,
							meta: poi,
							carton: carton,
							unit: unit,
							weight: weight,
							volume: volume,
							unit_price: poi.unit_price,
							units_per_carton: poi.units_per_carton,
							amount: 0,
							unship_cartons: poi.unship_cartons,
							cargo_ready_date: item.cargo_ready_date,
							action: "",
							classification_code: poi.product.classification_code,
							product_classification_description:
								poi.product.product_classification_description,
							description: poi.product.description,
						});
					});

					if (!this.isMobile) {
						
						let getProducts = this.purchaseOrderItems[key].products;
						getProducts = _.filter(
							getProducts,
							(e) => typeof e.addSpecial == "undefined"
						);
						getProducts.push({
							addSpecial: true,
							product_id: "",
							amount: 0,
							carton: 0,
							volume: 0,
							weight: 0,
							unit: 0,
							units_per_carton: 0,
							unit_price: 0,
						});

						getProducts = _.filter(
							getProducts,
							(e) => typeof e.special == "undefined"
						);

						this.purchaseOrderItems[key].products = getProducts;
					}
				} else {
					this.purchaseOrderItems[key].products = [];

					if (!this.isMobile) {
						this.purchaseOrderItems[key].products.push({
							addSpecial: true,
							product_id: "",
							amount: 0,
							carton: 0,
							volume: 0,
							unit: 0,
							weight: 0,
							units_per_carton: 0,
							unit_price: 0,
						});
					}
				}

				this.getProductsDescriptions();

				this.purchaseOrderItems[key].products.map((p, k) => {
					let setIndex = parseInt(k) + 1;
					this.purchaseOrderItems[key].products[k].index =
						setIndex >= 10 ? setIndex : `0${setIndex}`;
				});

				this.purchaseOrderItems[key].selectAll = !item.selectAll;
			}
		},
		countShipmentsDecimals(value) {
			value = Number(value);
			if (Number.isInteger(value)) {
				return 0;
			} else {
				return value.toString().split(".")[1].length;
			}
		},
		updateProducts(item, key) {
			this.purchaseOrderItems[key].loading = true;

			let otherOptions = _.find(
				this.purchaseOrderOptions,
				(e) => e.id == item.purchase_order_id
			);

			if (typeof otherOptions !== "undefined") {
				let supplier_id = otherOptions.supplier_id;
				let buyer_id = otherOptions.buyer_id;

				this.$store
					.dispatch("po/fetchPurchaseOrderProducts", item.purchase_order_id)
					.then((res) => {
						this.purchaseOrderItems[key].loading = false;
						this.purchaseOrderItems[key].product_options = res.data;
						this.purchaseOrderItems[key].selectAll = false;
						this.purchaseOrderItems[key].supplier_id = supplier_id;
						this.purchaseOrderItems[key].buyer_id = buyer_id;
						this.purchaseOrderItems[key].cargo_ready_date =
							otherOptions.cargo_ready_date;
						this.productId++;
						if (!this.isMobile) {
							this.purchaseOrderItems[key].products = [
								{
									addSpecial: true,
									product_id: "",
									amount: 0,
									unit_price: 0,
									weight: 0,
									volume: 0,
									carton: 0,
									cargo_ready_date: "",
									description: "",
									classification_code: "",
									product_classification_description: "",
								},
							];
						}
					})
					.catch((e) => {
						console.log(e);
						this.purchaseOrderItems[key].loading = false;
					});
			} else {
				this.purchaseOrderItems[key].loading = false;
				this.purchaseOrderItems[key].product_options = [];
				this.purchaseOrderItems[key].products = [];
			}
		},
		getWarehouseName(item) {
			return typeof item.warehouse !== "undefined" && item.warehouse !== null && typeof item.warehouse.name !== "undefined" ? item.warehouse.name : "N/A";
		},
		formatDate(value) {
			return moment(value).format("MMM DD, YYYY");
		},
		calculateTotals(po, entity) {
			let { products } = po;
			let total = 0;
			let newProducts = products;

			let validProducts = _.filter(
				products,
				(e) =>
					typeof e.addSpecial == "undefined" && typeof e.special == "undefined"
			);

			if (po.selectAll || (validProducts.length > 0 && po.product_options.length > 0 && validProducts.length == po.product_options.length)) {
				newProducts = _.filter(
					products,
					(e) => typeof e.addSpecial == "undefined"
				);
				po.selectAll = true;
			}
			
			if (newProducts.length > 0) {
				newProducts.map((np) => {
					if (entity === "volume") {
						total = parseFloat(total) + parseFloat(np[entity]);
					} else {
						total = parseInt(total) + parseInt(np[entity]);
					}
				});
			}

			if (total === "00.00") total = "0.00";

			if (entity === "volume") {
				total = parseFloat(total).toFixed(2);
			}
			return isNaN(total) ? (entity === "volume" ? "0.00" : 0) : total;
		},
		cartonTooltipClass(item) {
			if (typeof item.unship_cartons !== "undefined" && parseFloat(item.carton) > parseFloat(item.unship_cartons)) {
				return "tooltip-has-error";
			} else {
				if (parseInt(item.carton) == 0) {
					return "tooltip-has-error";
				} else {
					return "";
				}
			}
		},
		cartonTooltipMessage(item) {
			if (typeof item.unship_cartons !== "undefined" && parseFloat(item.carton) > parseFloat(item.unship_cartons)) {
				return `You can only enter a maximum of ${item.unship_cartons} carton${item.unship_cartons > 1 ? "s" : ""}.`;
			} else {
				if (parseInt(item.carton) == 0) {
					return `Please enter carton value that is more than 0. Available carton is ${
						item.unship_cartons
					} carton${item.unship_cartons > 1 ? "s" : ""}.`;
				} else {
					return `Available ${item.unship_cartons} carton${
						item.unship_cartons > 1 ? "s" : ""
					}.`;
				}
			}
		},
		customFilterProduct(item, queryText, itemText) {
			const data = item.product.category_sku + item.product.category_id + item.product.name.toLowerCase() + item.product.sku + itemText.toLocaleLowerCase();
			const searchText = queryText.toLowerCase();
			return data.indexOf(searchText) > -1;
		},
		validateProduct(item, key) {
			let returnClass = "";
			if (item.product_id !== null && item.product_id !== "") {
				let productKey = this.purchaseOrderItems[key].products.indexOf(item);
				let findSelectedOption = _.findIndex(
					this.purchaseOrderItems[key].products,
					(e) => e.product_id == item.product_id
				);
				if (findSelectedOption !== productKey) {
					returnClass = "error";
				}
			}
			return returnClass;
		},
		calculateAmount(item) {
			let total = parseFloat(item.unit_price) * parseFloat(item.unit);
			item.amount = total;
			return `${isNaN(total) ? "0.00" : total.toFixed(2)}`;
		},
		addAllCartons(item, key) {
			let productKey = this.purchaseOrderItems[key].products.indexOf(item);

			if (!item.addAll) {
				this.purchaseOrderItems[key].products[productKey].carton =
					item.unship_cartons;
			} else {
				this.purchaseOrderItems[key].products[productKey].carton = 1;
			}
			this.updateUnit(item, key);
			this.purchaseOrderItems[key].products[productKey].addAll = !item.addAll;
		},
		updateUnit(item, key) {
			let productKey = this.purchaseOrderItems[key].products.indexOf(item);
			this.purchaseOrderItems[key].products[productKey].unit = item.carton * item.units_per_carton;

			let volume_original = 0;
			let weight_original = 0;

			if (this.purchaseOrderItems[key].quickAddOrder) {
				volume_original = item?.meta?.volume;
				weight_original = item?.meta?.weight;
			} else {
				volume_original = item?.meta?.product?.volume;
				weight_original = item?.meta?.product?.weight;
			}

			volume_original = volume_original ? volume_original : 0;
			weight_original = weight_original ? weight_original : 0;

			this.purchaseOrderItems[key].products[productKey].volume =
				volume_original *
				this.purchaseOrderItems[key].products[productKey].unit;

			this.purchaseOrderItems[key].products[productKey].volume =
				this.countShipmentsDecimals(
					this.purchaseOrderItems[key].products[productKey].volume
				) > 5
					? this.purchaseOrderItems[key].products[productKey].volume.toFixed(5)
					: this.purchaseOrderItems[key].products[productKey].volume;

			this.purchaseOrderItems[key].products[productKey].volume =
				this.purchaseOrderItems[key].products[productKey].volume == 0
					? 0.00001
					: this.purchaseOrderItems[key].products[productKey].volume;

			this.purchaseOrderItems[key].products[productKey].weight =
				weight_original *
				this.purchaseOrderItems[key].products[productKey].unit;
		},
		updateProductPurchaseOrder(options, item, key, quickOrderAutoProduct = false) {
			let findIndex = this.purchaseOrderItems[key].products.indexOf(item);
			let findProduct = quickOrderAutoProduct ? _.find(options, (e) => e.id == item.product_id) : _.find(options, (e) => e.product_id == item.product_id);

			if (typeof findProduct !== "undefined") {
				this.purchaseOrderItems[key].products[findIndex].carton = 1;
				this.purchaseOrderItems[key].products[findIndex].units_per_carton = findProduct.units_per_carton;
				this.purchaseOrderItems[key].products[findIndex].unit = this.purchaseOrderItems[key].products[findIndex].carton * findProduct.units_per_carton;

				this.purchaseOrderItems[key].products[findIndex].weight = findProduct.product.weight == null ? 0 : findProduct.product.weight * this.purchaseOrderItems[key].products[findIndex].unit;
				this.purchaseOrderItems[key].products[findIndex].volume = findProduct.product.volume == null ? 0.00001 : findProduct.product.volume * this.purchaseOrderItems[key].products[findIndex].unit;
				this.purchaseOrderItems[key].products[findIndex].unship_cartons = findProduct.unship_cartons;
				this.purchaseOrderItems[key].products[findIndex].classification_code = findProduct.product.classification_code;
				this.purchaseOrderItems[key].products[findIndex].product_classification_description = findProduct.product.product_classification_description;
				this.purchaseOrderItems[key].products[findIndex].description = findProduct.product.description;

				this.purchaseOrderItems[key].products[findIndex].unit_price = findProduct.unit_price;
				this.purchaseOrderItems[key].products[findIndex].meta = findProduct;
				this.purchaseOrderItems[key].products[findIndex].cargo_ready_date = this.purchaseOrderItems[key].cargo_ready_date;
				this.purchaseOrderItems[key].products[findIndex].volume =this.countShipmentsDecimals(this.purchaseOrderItems[key].products[findIndex].volume) > 5 ? this.purchaseOrderItems[key].products[findIndex].volume.toFixed(5) : this.purchaseOrderItems[key].products[findIndex].volume;

				this.purchaseOrderItems[key].products[findIndex].volume = this.purchaseOrderItems[key].products[findIndex].volume == 0 ? 0.00001 : this.purchaseOrderItems[key].products[findIndex].volume;
				this.getProductsDescriptions();
			}
		},
		addProductToPurchaseOrders(key) {
			this.productId++;
			this.purchaseOrderItems[key].products.push({
				product_id: "",
				addAll: false,
				carton: 0,
				unit: 0,
				volume: 0,
				weight: 0,
				unit_price: 0,
				units_per_carton: 0,
				amount: 0,
				id: this.productId,
				action: "",
				description: "",
				classification_code: "",
				product_classification_description: "",
				quickAddProduct: false,
				isEditingField: false,
				product_number: "",
			});

			if (!this.isMobile) {
				let getProducts = this.purchaseOrderItems[key].products;
				getProducts = _.filter(
					getProducts,
					(e) => typeof e.addSpecial == "undefined"
				);
				getProducts.push({
					addSpecial: true,
					product_id: "",
					amount: 0,
					carton: 0,
					unit: 0,
					weight: 0,
					volume: 0,
					units_per_carton: 0,
					unit_price: 0,
					description: "",
					classification_code: "",
					product_classification_description: "",
				});
				this.purchaseOrderItems[key].products = getProducts;
			}

			this.purchaseOrderItems[key].products.map((p, k) => {
				let setIndex = parseInt(k) + 1;
				this.purchaseOrderItems[key].products[k].index =
					setIndex >= 10 ? setIndex : `0${setIndex}`;
			});
		},
		removeProductFromPurchaseOrders(key, item) {
			this.orderProductsSearchText = "";
			let getIndex = this.purchaseOrderItems[key].products.indexOf(item);
			this.purchaseOrderItems[key].selectAll = false;
			this.purchaseOrderItems[key].products.splice(getIndex, 1);

			this.getProductsDescriptions();
		},
		selectOpenedProductBox(item) {
			let key = parseInt(item.index) - 1;
			this.selectedProductForQuickAdd = key;
		},
		getImgUrl(pic) {
			let url = process.env.VUE_APP_PO_URL.slice(0, -3);
			let imageUrl = `${url}storage/`;
			if (typeof pic !== "undefined" && pic !== null) {
				if (pic.includes(imageUrl) !== "undefined" && !pic.includes(imageUrl)) {
					let newImage = `${imageUrl}${pic}`;
					return newImage;
				} else 
					return pic;
			} else return require("../../../assets/icons/default-product-icon.svg");
		},
		updateShipment() {
			let payload = this.editItem;
			payload.date_id = new Date().getTime();
			payload.purchase_orders = this.purchaseOrderItems;
			// payload.containers = this.containerItems;
			payload.containers = {};
			payload.shipment_id = this.createSingleShipmentId;
			let supplier_timestamps = [];
			if (payload.purchase_orders.length > 0) {
				payload.purchase_orders.map(() => {
					supplier_timestamps.push(new Date().getTime());
				});
			}
			payload.supplier_timestamps = supplier_timestamps;

			this.updateShipmentInfo(payload)
				.then((response) => {
					this.close();
					this.shiflShipmentRef = response.data.shipment ? response.data.shipment.shifl_ref : '';
					this.reloadShipments();
					this.$emit("shipmentInfoUpdatedSuccess")
					this.$emit("update:createSingleShipmentId", response.data.shipment_id);
				})
				.catch((e) => {
					console.log(e);
				});
		},
		reloadShipments() {
			this.$emit("reloadShipments");
		},
	}
};
</script>

