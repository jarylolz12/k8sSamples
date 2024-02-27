<!-- @format -->

<template>
	<div>
		<div
			class="booking-request-form-wrapper"
			id="shipment-booking-request-form"
			v-resize="onResize"
		>
			<div class="booking-shipment-header">
				<img src="@/assets/images/logo.svg" alt="" class="logo" />

				<p class="mb-0">
					Want to join <strong>Shifl’s</strong> supply chain platform?
					<span class="sign-up">
						<a class="text-decoration-none" href="https://shifl.com/register"
							><strong> Sign Up </strong></a
						></span
					>
				</p>
			</div>

			<v-card class="booking-shipment-form">
				<v-card-title>
					<span class="headline">Create Booking Request</span>
				</v-card-title>

				<v-card-text class="pb-0">
					<div
						id="card-text-wrapper"
						ref="cardTextWrapper"
						class="d-flex flex-row booking-shipment-form-content"
					>
						<div
							class="d-flex flex-column first-column sidebar-item-main-wrapper pt-4"
						>
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
							id="second-column-id"
							class="d-flex flex-column booking-shipment-request-content-details second-column"
						>
							<div class="content-title general-title">
								General Information
							</div>
							<v-form :ref="reference" action="#" @submit.prevent="">
								<div class="d-flex flex-row form-details-wrapper">
									<div
										class="form-edit-shipment-form-first-column form-wrapper"
									>
										<div class="d-flex flex-row">
											<div style="width: 100%;" class="d-flex flex-column">
												<div class="form-label">
													<span>YOUR ROLE</span>
													<span class="red--text ml-1">*</span>
												</div>
												<div class="d-flex radio-group-wrapper mb-4">
													<div
														v-bind:key="`role-${key}`"
														v-for="(r, key) in roles"
														:class="
															`d-flex radio-item align-center ${
																r === editItem.role ? 'selected' : ''
															}`
														"
														class="role-radio"
													>
														<label
															style="position: relative;"
															class="radio-label-wrapper"
														>
															{{ ucFirst(r) }}
															<input
																name="role"
																:value="r"
																class="custom-radio"
																style="position: absolute; opacity: 0;"
																type="radio"
																v-model="userRole"
																disabled
															/>
															<span></span>
														</label>
													</div>
												</div>
												<div></div>
											</div>
										</div>

										<div class="text-field-wrapper">
											<span class="text-field-label">Shipper</span>
											<v-text-field
												:height="40"
												color="#002F44"
												width="200px"
												v-model="shipper_company_name"
												type="text"
												dense
												outlined
												hide-details="auto"
												disabled
											/>
										</div>

										<div class="text-field-wrapper text-area-wrapper-1">
											<span class="text-field-label"
												>Shipper’s details info</span
											>
											<v-textarea
												class="text-fields"
												outlined
												v-model="editItem.shipper_details_info"
												placeholder="Shipper's info"
												hide-details="auto"
												autocomplete="off"
											/>
										</div>

										<div class="text-field-wrapper">
											<span class="text-field-label">FROM</span>
											<v-autocomplete
												:height="40"
												color="#002F44"
												:items="filteredTerminalRegions"
												item-text="text"
												item-value="value"
												v-model="editItem.location_from"
												dense
												outlined
												hide-details="auto"
												placeholder="Enter Location (Port, Warehouse etc.)"
												:rules="locationFromRules"
												spellcheck="false"
												:menuProps="{
													contentClass: 'po-lists-items',
													...menuProps,
												}"
												clearable
											/>
										</div>

										<div class="text-field-wrapper">
											<span class="text-field-label">TO</span>
											<v-autocomplete
												:height="40"
												color="#002F44"
												:items="filteredTerminalRegions"
												item-text="text"
												item-value="value"
												v-model="editItem.location_to"
												dense
												outlined
												hide-details="auto"
												placeholder="Enter Location (Port, Warehouse etc.)"
												:rules="locationFromRules"
												spellcheck="false"
												:menuProps="{
													contentClass: 'po-lists-items',
													...menuProps,
												}"
												clearable
											/>
										</div>

										<div class="form-label mb-2">
											<span>MODE</span>
											<span class="red--text pl-1">*</span>
										</div>
										<div
											class="d-flex radio-group-wrapper radio-group-wrapper-web"
										>
											<div
												v-bind:key="`mode-${key}`"
												v-for="(m, key) in modes"
												:class="
													`d-flex radio-item align-center ${
														m === shipmentMode ? 'selected' : ''
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
														style="position: absolute; opacity: 0;"
														type="radio"
														v-model="shipmentMode"
													/>
													<span></span>
												</label>
												<KeneticIcon
													:marginLeft="8"
													:iconName="m.toLowerCase()"
												/>
											</div>
										</div>

										<div class="text-field-wrapper text-area-wrapper-1">
											<span class="text-field-label"
												>Final delivery address</span
											>
											<v-textarea
												class="text-fields"
												outlined
												v-model="editItem.final_address"
												placeholder="Enter Final Address"
												hide-details="auto"
												autocomplete="off"
											/>
										</div>
									</div>

									<div
										class="form-edit-shipment-form-second-column form-wrapper"
									>
										<div
											v-if="filteredTypes.length > 0"
											class="form-label mb-1"
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
											<span class="red--text ml-1">*</span>
										</div>
										<div
											v-if="filteredTypes.length > 0"
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

										<div class="text-field-wrapper ">
											<span class="text-field-label">CONSIGNEE</span>
											<v-text-field
												:height="40"
												color="#002F44"
												width="200px"
												v-model="consignee_company_name"
												type="text"
												dense
												outlined
												hide-details="auto"
												disabled
											/>
										</div>

										<div class="text-field-wrapper text-area-wrapper-1">
											<span class="text-field-label"
												>CONSIGNEE’S DETAILS INFO</span
											>
											<v-textarea
												class="text-fields"
												outlined
												v-model="editItem.consignee_details_info"
												placeholder="Enter consignees details info"
												hide-details="auto"
												autocomplete="off"
											/>
										</div>

										<div class="checkbox-wrapper-desktop mb-4">
											<label :class="`${is_notify_party ? 'checked' : ''}`">
												<GenericIcon
													:marginLeft="0"
													:iconName="
														`${is_notify_party ? 'checked' : 'not-checked'}`
													"
												/>
												<input
													@click.prevent="notifyParty()"
													type="checkbox"
													:checked="is_notify_party"
													v-model="is_notify_party"
												/>
												<span>Notify party is different from Consignee</span>
											</label>
										</div>

										<div class="text-field-wrapper text-area-wrapper-1">
											<span class="text-field-label">NOTIFY</span>
											<v-textarea
												:disabled="!is_notify_party"
												class="text-fields"
												outlined
												v-model="editItem.notify_details_info"
												placeholder="Select Consignee for notify details"
												hide-details="auto"
												autocomplete="off"
											/>
										</div>

										<div class="form-label d-flex tooltip-wrappers mb-2">
											<span :class="`d-flex align-end`"
												>INCOTERMS <span class="red--text ml-1">*</span></span
											>
											<span class="booking-tooltip-wrapper">
												<v-tooltip
													attach=".booking-tooltip-wrapper"
													left
													content-class="booking-tooltip"
												>
													<template v-slot:activator="{ on, attrs }">
														<div
															style="position: relative; top: 2px;"
															v-on="on"
															v-bind="attrs"
														>
															<GenericIcon
																color="#4A4A4A"
																iconName="info-icon"
															/>
														</div>
													</template>
													<div>
														International Commercial Terms, are a set of
														international rules produced by the International
														Chamber of Commerce (ICC).
													</div>
												</v-tooltip>
											</span>
										</div>
										<div
											class="radio-group-wrapper radio-group-wrapper-incoterms"
										>
											<div
												style="float: left;"
												v-bind:key="`incoterm-${key}`"
												v-for="(m, key) in paymentTerms"
												:class="
													`d-flex radio-item align-center ${
														m === shipmentIncoTerm ? 'selected' : ''
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
														v-model="shipmentIncoTerm"
													/>
													<span></span>
												</label>
											</div>
										</div>

										<div class="text-field-wrapper tooltip-wrappers">
											<span class="text-field-label">CUSTOM REFERENCE</span>
											<v-text-field
												:height="40"
												color="#002F44"
												width="200px"
												v-model="editItem.customer_reference_number"
												type="text"
												dense
												placeholder="Enter Custom Reference Number"
												outlined
												hide-details="auto"
											/>
										</div>
									</div>
								</div>

								<div
									id="purchaseOrderSection"
									ref="purchaseOrderSection"
									class="d-flex flex-column purchase-orders-section"
								>
									<div class="content-title orders-title mb-2">
										{{
											editItem.role === "shipper" ? "Orders" : "Purchase Orders"
										}}
									</div>
									<div
										v-bind:key="`poipom-${key}`"
										v-for="(poi, key) in purchaseOrderItems"
									>
										<div
											v-if="poi.layout === 'default'"
											class="purchase-order-wrapper mb-4"
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
													:placeholder="
														`${
															editItem.role === 'shipper'
																? 'Enter SO'
																: 'Enter PO'
														}`
													"
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
													v-if="!poi.quickAddOrder"
												>
													<template v-slot:item="{ item }">
														<div
															class="d-flex flex-column"
															style="width: 100%;"
														>
															<div
																style="width: 100%;"
																class="d-flex first-row justify-space-between pb-3"
															>
																<div class="d-flex flex-row align-center">
																	<span>{{
																		editItem.role === "shipper" ? "SO#" : "PO#"
																	}}</span>
																	<span class="pl-1">{{ item.po_number }}</span>
																</div>
																<div style="font-size: 14px !important;">
																	{{ currencyNumberFormat(item.total) }}
																</div>
															</div>
															<div
																class="d-flex second-row pb-1"
																style="width: 100%;"
															>
																{{ getWarehouseName(item) }}
															</div>
															<div class="d-flex third-row">
																{{ item.ship_to }}
															</div>
															<div
																style="width: 100%;"
																class="d-flex fourth-row flex-row justify-space-between pb-2"
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
													class="select-product-checkbox checkbox-wrapper-desktop select-all-box"
												>
													<label
														:class="`${poi.selectAll ? 'checked' : ''}`"
														class="product-checkbox"
													>
														<GenericIcon
															:marginLeft="0"
															:iconName="
																`${poi.selectAll ? 'checked' : 'not-checked'}`
															"
														/>
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
														<span>Select All Products</span>
													</label>
												</div>

												<div class="delete-btn-wrapper">
													<v-btn
														class="btn-white mr-4"
														@click="removePurchaseOrderItem(key)"
													>
														<CustomIcon
															marginLeft="3px"
															iconName="trash-can"
															color="#ff5252"
														/>
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
																	class="op-  px-3"
																	role="column-header"
																	:aria-label="item.text"
																	:style="
																		`color: #6D858F !important;width: ${item.width};text-align: ${item.textAlign}`
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
															<div
																v-if="
																	!poi.quickAddOrder && !item.quickAddProduct
																"
															>
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
																	<span class="mr-2">
																		<GenericIcon
																			color="#0171A1"
																			iconName="plus"
																		/>
																	</span>
																	<span>Add Product</span>
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
																class="action-image"
																v-show="item.isEditingField"
															>
																<v-btn
																	@click="saveProductDescriptions(item, key)"
																>
																	<img
																		src="@/assets/icons/check-white.svg"
																		alt=""
																		width="18px"
																	/>
																</v-btn>
																<v-btn
																	@click="clearProductDescriptions(item, key)"
																>
																	<img
																		src="@/assets/icons/white-close.svg"
																		alt=""
																		width="18px"
																	/>
																</v-btn>
															</div>

															<div
																style="text-align: right !important;"
																:class="
																	item.isEditingField ? 'text-description' : ''
																"
																v-if="!item.isEditingField"
																@click="showField(item, key)"
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
															<div
																style="text-align: right !important;"
																:class="
																	item.isEditingField ? 'text-description' : ''
																"
																v-if="item.isEditingField"
															>
																<textarea
																	class="field-value form-control"
																	v-model="productDescription"
																	rows="3"
																	cols="24"
																></textarea>
															</div>
														</div>
													</template>

													<template v-slot:[`item.addAll`]="{ item }">
														<div
															v-if="
																typeof item.special == 'undefined' &&
																	typeof item.addSpecial == 'undefined' &&
																	disabledProductsField(
																		poi.quickAddOrder,
																		item.quickAddProduct
																	)
															"
															class="d-flex product-add-all-wrapper"
														>
															<label
																:class="`${item.addAll ? 'checked' : ''}`"
																style="position: relative;"
															>
																<GenericIcon
																	:iconName="
																		`${item.addAll ? 'checked' : 'not-checked'}`
																	"
																/>
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
																<GenericIcon
																	:color="
																		item.product_id == ''
																			? '#B4CFE0'
																			: '#F93131'
																	"
																	iconName="trash-product-light"
																/>
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
													<div class="order-total-item info-message">
														<img
															src="@/assets/icons/info.svg"
															width="16px"
															height="16px"
														/>
														<span
															>The unit price and amount column is for custom
															filling</span
														>
													</div>
												</div>
											</div>
										</div>
										<div
											v-if="poi.layout === 'manual'"
											class="purchase-order-wrapper mb-4"
										>
											<div
												id="select-autocomplete-4"
												:class="
													`select-items-wrapper select-autocomplete-wrapper select-autocomplete-wrapperpom-${key}`
												"
											>
												<div class="mr-2">
													<v-text-field
														v-model="poi.purchase_order_number"
														:height="40"
														width="200px"
														type="text"
														dense
														:class="`custom-font-weight-400 custom-font-14`"
														:placeholder="
															`${
																editItem.role === 'shipper'
																	? 'Enter SO'
																	: 'Enter PO'
															}`
														"
														outlined
														hide-details="auto"
														:rules="[(v) => !!v || 'Order number is required']"
														class="manual-order-number"
													/>
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
														<CustomIcon
															marginLeft="3px"
															iconName="trash-can"
															color="#ff5252"
														/>
													</v-btn>
												</div>
											</div>
											<div class="d-flex flex-row total-carton-volume-weight">
												<div class="text-field-wrapper">
													<span class="text-field-label">Total Carton</span>
													<v-text-field
														:height="40"
														color="#002F44"
														width="200px"
														v-model="poi.total_cartons"
														type="text"
														dense
														outlined
														hide-details="auto"
														placeholderText="Enter Total Carton"
														:rules="[(v) => !!v || 'Total Carton is required']"
													>
													</v-text-field>
												</div>

												<div class="text-field-wrapper">
													<span class="text-field-label">Total Volume</span>
													<v-text-field
														:height="40"
														color="#002F44"
														width="200px"
														v-model="poi.total_volumes"
														type="text"
														dense
														outlined
														hide-details="auto"
														placeholderText="Enter Total Volume"
														:rules="[(v) => !!v || 'Total Volume is required']"
													>
													</v-text-field>
												</div>
												<div class="text-field-wrapper">
													<span class="text-field-label">Total Weight</span>
													<v-text-field
														:height="40"
														color="#002F44"
														width="200px"
														v-model="poi.total_weights"
														type="text"
														dense
														outlined
														hide-details="auto"
														placeholderText="Enter Total Weight"
														:rules="[(v) => !!v || 'Total Weight is required']"
													>
													</v-text-field>
												</div>
											</div>
											<div class="flex-row px-4 mt-4">
												<generic-table
													:headers="headersDocuments"
													:items.sync="poi.documents"
													:pomKey="key"
													textColor="#4A4A4A"
													headerBackground="#F7F7F7"
												>
													<!-- :shipmentId="item.id" -->
												</generic-table>
											</div>
										</div>
									</div>
									<div
										class="d-flex add-product-purchase-order-wrapper add-purchase-order-wrapper flex-row pl-0"
									>
										<v-btn
											class="btn-white mr-4 shipment-header-button-btn add-purchase-order-btn-wrapper btn-white-custom"
											@click="addPurchaseOrderItem()"
											:disabled="defaultBtnDisable"
										>
											<span class="add-purchase-order-span">{{
												editItem.role === "shipper"
													? "+ Add Orders"
													: "+ Add Orders"
											}}</span>
										</v-btn>
										<v-btn
											class="btn-white mr-4 shipment-header-button-btn add-purchase-order-btn-wrapper btn-white-custom"
											@click="addManualPurchaseOrder()"
											:disabled="manualBtnDisable"
										>
											<span
												class="add-purchase-order-span add-manual-po-span"
												>{{
													editItem.role === "shipper"
														? "+ Add Manual Orders"
														: "+ Add Manual Orders"
												}}</span
											>
										</v-btn>
									</div>
								</div>

								<div
									id="cargoDetailsSection"
									ref="cargoDetailsSection"
									class="d-flex flex-column cargo-details-section"
									:class="
										!checkOrdersItems() ? 'enable-cargo-details-section' : ''
									"
								>
									<div class="content-title">Cargo Details</div>
									<div class="d-flex cargo-details">
										<div class="text-field-wrapper">
											<span class="text-field-label">Total Cartons</span>
											<v-text-field
												height="40px"
												:disabled="checkOrdersItems()"
												color="#002F44"
												width="200px"
												:value="
													checkOrdersItems()
														? calculateOverAllTotal('carton')
														: totalCartons
												"
												type="number"
												dense
												class="unit"
												placeholder="Total cartons"
												outlined
												hide-details="auto"
												@change="calulateTotalCartons($event)"
											/>
										</div>
										<div class="text-field-wrapper">
											<span class="text-field-label"
												>Total Volume
												<span class="red--text ml-1" v-if="!checkOrdersItems()"
													>*</span
												></span
											>
											<v-text-field
												height="40px"
												:disabled="checkOrdersItems()"
												color="#002F44"
												width="200px"
												:value="
													checkOrdersItems()
														? calculateOverAllTotal('volume')
														: totalVolumes
												"
												type="number"
												dense
												class="unit"
												placeholder="Total volume"
												outlined
												hide-details="auto"
												:rules="[
													(v) => {
														if (!checkOrdersItems() && !v) {
															return 'Total volume is required';
														} else {
															return true;
														}
													},
												]"
												@change="calulateTotalVolume($event)"
											/>
										</div>
										<div class="text-field-wrapper">
											<span class="text-field-label"
												>Total Weight
												<span class="red--text ml-1" v-if="!checkOrdersItems()"
													>*</span
												></span
											>
											<v-text-field
												height="40px"
												:disabled="checkOrdersItems()"
												color="#002F44"
												width="200px"
												:value="
													checkOrdersItems()
														? calculateOverAllTotal('weight')
														: totalWeights
												"
												type="number"
												dense
												class="unit"
												placeholder="Total weight"
												outlined
												hide-details="auto"
												:rules="[
													(v) => {
														if (!checkOrdersItems() && !v) {
															return 'Total weight is required';
														} else {
															return true;
														}
													},
												]"
												@change="calulateTotalWeights($event)"
											/>
										</div>
									</div>
								</div>

								<div
									id="containersSection"
									ref="containersSection"
									class="d-flex flex-row containers-others-section"
								>
									<div class="container-section">
										<div class="content-title containers-title">
											Containers
										</div>
										<div>
											<containers-table
												:headers="headersNewContainers"
												:items.sync="containerNewItems"
												headerBackground="#F7F7F7"
											>
											</containers-table>
										</div>
									</div>
									<div class="other-section">
										<div class="content-title">Others</div>
										<div class="d-flex flex-column">
											<div class="text-field-wrapper text-area-wrapper-2 marks">
												<span class="text-field-label"
													>Marks <small>(Optional)</small></span
												>
												<v-textarea
													class="text-fields"
													outlined
													v-model="editItem.marks"
													placeholder="Enter Marks"
													hide-details="auto"
													autocomplete="off"
												/>
											</div>
											<div
												class="text-field-wrapper text-area-wrapper-2 spacial-instructions"
											>
												<span class="text-field-label"
													>Special Instructions<small>(Optional)</small></span
												>
												<v-textarea
													class="text-fields"
													outlined
													v-model="editItem.special_instructions"
													placeholder="Enter Special Instructions"
													hide-details="auto"
													autocomplete="off"
												/>
											</div>
										</div>
									</div>
								</div>
							</v-form>
						</div>
					</div>
				</v-card-text>
				<v-card-actions>
					<div class="d-flex footer">
						<v-btn @click="addShipment()" class="save-btn btn-blue mr-2" text>
							Submit Request
						</v-btn>
						<v-btn
							class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue"
							text
							@click="close"
						>
							Cancel
						</v-btn>
					</div>
				</v-card-actions>
			</v-card>
		</div>

		<SubmitRequestModal
			:submitShipmentId="submitShipmentId"
			:isMobile="isMobile"
			:bookingShipmentDialogView.sync="bookingShipmentDialogView"
			:submitRequestLoading.sync="submitRequestLoading"
			:showDialog.sync="showSubmittedRequestDialog"
			@close="showSubmittedRequestDialog = false"
			@submit="handleSubmit"
			@notificationError="notificationError"
			:submitRequestModalLoading.sync="submitRequestModalLoading"
			:show="submitRequestModalView"
			:isBookingInviteForm="true"
		>
			<template v-slot:title>
				<h2>
					{{ "Submit Request" }}
				</h2>
			</template>
			<template v-slot:actions="{ footer }">
				<div class="d-flex footer">
					<v-btn
						:disabled="footer.submitRequestLoading || footer.disableSubmit"
						class="save-btn btn-blue mr-2"
						text
						@click="footer.submit"
					>
						<span>{{ "Submit" }}</span>
					</v-btn>
					<v-btn
						class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue"
						text
						@click="footer.close"
					>
						<span>{{ "Cancel" }}</span>
					</v-btn>
				</div>
			</template>
		</SubmitRequestModal>

		<ConfirmDialog
			:dialogData.sync="requestBookingConfirmation"
			class="shipment-booking-request-dialog"
		>
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<!-- <h2 v-if="requestBookingConfirmationMessage">
					Booking Already submitted
				</h2> -->
				<h2 v-if="requestBookingErrorMessage">Invalid Token</h2>
			</template>

			<template v-slot:dialog_content>
				<!-- <p class="mb-0" v-if="requestBookingConfirmationMessage">
					Your Booking Already submitted from this invite.
				</p> -->
				<p class="mb-0" v-if="requestBookingErrorMessage">
					Booking Invite Token is Invalid.
				</p>
			</template>
		</ConfirmDialog>
	</div>
</template>

<script>
import _ from "lodash";
import jQuery from "jquery";
import moment from "moment";
import KeneticIcon from "@/components/Icons/KeneticIcon";
import CustomIcon from "@/components/Icons/CustomIcon.vue";
import GenericIcon from "@/components/Icons/GenericIcon";

import globalMethods from "@/utils/globalMethods";
import GenericTable from "@/components/Dialog/FormShipmentDialog/Tables/GenericTable";
import ContainersTable from "@/components/Dialog/FormShipmentDialog/Tables/ContainersTable";
import SubmitRequestModal from "@/components/Dialog/FormShipmentDialog/Modals/SubmitRequestModal";

import { mapGetters } from "vuex";
import iziToast from "izitoast";

// //import datas here
import headers from "@/components/Dialog/FormShipmentDialog/Datas/tableHeaders";
import BookingItem from "@/components/Dialog/FormShipmentDialog/Structures/BookingItem";
import SidebarItems from "@/components/Dialog/FormShipmentDialog/Datas/sidebarItems";
import DocumentTypeItems from "@/components/Dialog/FormShipmentDialog/Datas/documentTypes";
import ConfirmDialog from "../../components/Dialog/GlobalDialog/ConfirmDialog.vue";
import axios from "axios";

export default {
	name: "BookingShipmentDialog",
	data: () => ({
		reference: "formBookingShipment",
		is_notify_party: false,
		userRole: "shipper",
		prevRole: "",
		submitRequestLoading: false,
		submitShipmentId: "",
		selectedOrderIds: [],
		showDialog: true,
		editedIndex: -1,
		submitLoading: false,
		scrollAnimating: false,
		currentSection: "general",
		showConfirmRoleModal: false,
		consignees: [],
		shippers: [],
		userDetails: {},
		shipmentConsigneeData: [],
		submitRequestModalView: true,
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
		consigneeRules: [
			(v) => !!v || "Consignee is required. Please select consignee.",
		],
		shipperRules: [(v) => !!v || "Shipper is required. Please select shipper."],
		incoTermRules: [
			(v) => !!v || "Inco Term is required. Please select an inco term.",
		],
		roleRules: [(v) => !!v || "Role is required. Please select a role."],
		locationFromRules: [(v) => !!v || "Location from is required."],
		locationToRules: [(v) => !!v || "Location to is required."],
		roles: ["shipper", "consignee"],
		containerSizes: [],
		selectAll: false,
		menuProps: {
			bottom: true,
			offsetY: true,
		},
		products: [
			{
				text: "Test",
				value: "Test",
			},
		],
		purchaseOrderItems: [],
		purchaseOrderManualItems: [],
		containerItems: [],
		containerNewItems: [],
		headersDocuments: headers.documents.data,
		headersProducts: headers.shipmentBookingProducts.data,
		headersNewContainers: headers.newContainers.data,
		manualSupplierOptions: [],
		purchaseOrderId: 0,
		productId: 0,
		containerId: 0,
		editItem: BookingItem,
		attrs: {
			class: "mb-6",
			boilerplate: true,
			elevation: 2,
		},
		resourcesLoaded: 0,
		resourcesLimit: 6,
		valid: true,
		loaded: false,
		mode: "",
		from: "",
		to: "",
		carriers: [],
		etaDate: null,
		modes: ["Ocean", "Air"],
		paymentTerms: ["FOB", "CIF", "DDU", "DDP", "EXW", "FCA", "DAP"],
		type: "",
		types: ["FCL", "LTL"],
		sidebarItems: SidebarItems.booking.data,
		searchVendors: "",
		searchCustomers: "",
		manualBtnDisable: false,
		defaultBtnDisable: false,
		shipmentMode: "",
		totalCartons: "",
		totalVolumes: "",
		totalWeights: "",
		shipmentIncoTerm: "",
		ordersSearchText: "",
		searchOrders: true,
		orderProductsSearchText: "",
		searchProducts: true,
		editField: "",
		focusFields: false,
		selectedProductForQuickAdd: null,
		productDescription: "",
		shipmentData: null,
		windowWidth: 0,
		isMobile: false,
		shipper_company_name: "",
		consignee_company_name: "",
		showSubmittedRequestDialog: false,
		submitRequestModalLoading: false,
		showBookingRequestSubmittedModal: false,
		showSubmitRequestModal: false,
		bookingShipmentDialogView: false,
		requestBookingConfirmation: false,
		requestBookingConfirmationMessage: false,
		requestBookingErrorMessage: false,
		requestBookingToken: null,
	}),
	components: {
		KeneticIcon,
		CustomIcon,
		GenericIcon,
		GenericTable,
		ContainersTable,
		SubmitRequestModal,
		ConfirmDialog,
	},
	async mounted() {
		let data = document.querySelector('.v-main__wrap')
		data.classList.add("shipment-booking-request-form-wrapper");

		//set user details
		try {
			this.userDetails = JSON.parse(this.getUser);
		} catch (e) {
			this.userDetails = this.getUser;
		}

		//setup scroll
		this.setupScroll();
	},
	async beforeMount() {
		const currentUrl = window.location.href;
		const urlArray = currentUrl.split("/").slice(-1);
		await axios
			.post(`get-booking-invite-data`, {
				tokenKey: urlArray[0],
			})
			.then((response) => {
				// if (response.data.is_request_submitted == 1) {
				// 	this.requestBookingConfirmation = true;
				// 	this.requestBookingConfirmationMessage = true;
				// } else {
				this.editItem.shipper = response.data.data.supplier_id;
				this.editItem.consignee = response.data.data.consignee_id;
				this.editItem.shipper_details_info = response.data.shipperAddress;
				this.editItem.consignee_details_info = response.data.consigneeAddress;
				this.shipper_company_name = response.data.shipperName;
				this.consignee_company_name = response.data.consigneeName;
				this.requestBookingToken = response.data.data.token_key;
				// }

				//fetch container sizes
				this.$store.dispatch("booking/fetchContainerSizes").then(() => {
					if (this.getContainerSizes.length > 0) {
						//iterate through container sizes
						this.getContainerSizes.map((gcs) => {
							this.containerNewItems.push({
								size: gcs.name,
								size_id: gcs.id,
								checked: false,
								how_many: 0,
								plus: "+",
								minus: "-",
								ukey: 0,
							});
						});
					}
				});

				//load terminal regions
				this.$store
					.dispatch("fetchTerminalRegions", { is_paginate: 0 })
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
			})
			.catch((error) => {
				this.requestBookingConfirmation = true;
				this.requestBookingErrorMessage = true;
				console.log("error", error);
			});

		this.fetchPurchaseOrderOptions(
			"",
			"PO",
			this.editItem.consignee,
			this.editItem.shipper
		)
			.then(() => {
				this.resourcesLoaded++;
				if (this.resourcesLoaded === this.resourcesLimit) {
					this.loaded = true;
				}
				this.setupPurchaseOrderOptions();
			})
			.catch((e) => {
				console.log(e);
				this.resourcesLoaded++;
				if (this.resourcesLoaded === this.resourcesLimit) {
					this.loaded = true;
				}
			});
	},
	computed: {
		...mapGetters(["getTerminalRegions", "getUser"]),
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
		getContainerSizes() {
			return this.$store.getters["booking/getContainerSizes"];
		},
		purchaseOrderOptions() {
			let options = this.$store.getters["po/getPurchaseOrderOptions"];
			options = _.filter(
				options,
				(e) => e.status === "pending" || e.status === "partial_shipped"
			);
			return options;
		},
		filteredTerminalRegions() {
			let newTerminalRegions = [];
			this.getTerminalRegions.map((gt) => {
				newTerminalRegions.push({
					text: gt.name,
					value: gt.id,
				});
			});
			return newTerminalRegions;
		},
	},
	methods: {
		...globalMethods,
		onResize() {
			this.windowWidth = window.innerWidth;
			if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
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
		isString(x) {
			return Object.prototype.toString.call(x) === "[object String]";
		},
		toFixed(x) {
			if (this.isString(x)) {
				x = x.toLowerCase();
			}

			if (Math.abs(x) < 1.0) {
				let e = parseInt(x.toString().split("e-")[1]);
				if (e) {
					x *= Math.pow(10, e - 1);
					x = "0." + new Array(e).join("0") + x.toString().substring(2);
				}
			} else {
				let e = parseInt(x.toString().split("+")[1]);
				if (e > 20) {
					e -= 20;
					x /= Math.pow(10, e);
					x += new Array(e + 1).join("0");
				}
			}
			return x;
		},
		cartonTooltipMessage(item, quickAddOrder) {
			if (!item.quickAddProduct) {
				if (
					typeof item.unship_cartons !== "undefined" &&
					parseFloat(item.carton) > parseFloat(item.unship_cartons)
				) {
					return `You can only enter a maximum of ${
						item.unship_cartons
					} carton${item.unship_cartons > 1 ? "s" : ""}.`;
				} else {
					if (item.product_id == "" || item.product_id == null) {
						return "Select first a product";
					} else if (
						quickAddOrder &&
						(item.product_id !== "" || item.product_id !== null)
					) {
						return "Please enter carton";
					} else {
						return `Available ${item.unship_cartons} carton${
							item.unship_cartons > 1 ? "s" : ""
						}.`;
					}
				}
			} else {
				return "Please enter carton";
			}
		},
		cartonTooltipClass(item) {
			if (
				typeof item.unship_cartons !== "undefined" &&
				parseFloat(item.carton) > parseFloat(item.unship_cartons)
			) {
				return "tooltip-has-error";
			} else {
				if (parseInt(item.carton) == 0) {
					return "tooltip-has-error";
				} else {
					return "";
				}
			}
		},
		resetData() {
			this.editItem.role = 0;
			this.editItem.consignee = 0;
			this.editItem.shipper = 0;
			this.editItem.mode = "";
			this.shipmentIncoTerm = "";
			this.editItem.inco_term_other = "";
			this.purchaseOrderItems = [];
			this.purchaseOrderManualItems = [];
			this.containerNewItems = [];
			this.prevRole = "";
		},
		setupScroll() {
			//setup interval to wait for the dom to actually appear before adding event listener
			let t = setInterval(() => {
				let d = document.getElementById("second-column-id");
				if (d !== null) {
					d.addEventListener("scroll", () => {
						//get scroll top

						if (!this.scrollAnimating) {
							let x = jQuery("#second-column-id").scrollTop();
							//reuse the select page method
							if (x >= 0 && x < 560) {
								//general section
								if (this.currentSection != "general") {
									this.currentSection = "general";
									this.selectPage({
										icon: "general",
										scrolling: true,
									});
								}
							} else if (x >= 560 && x < 890) {
								//orders section
								if (this.currentSection != "po-icon") {
									this.currentSection = "po-icon";
									this.selectPage({
										icon: "po-icon",
										scrolling: true,
									});
								}
							} else {
								//containers section
								if (this.currentSection != "container-icon") {
									this.currentSection = "container-icon";
									this.selectPage({
										icon: "container-icon",
										scrolling: true,
									});
								}
							}
						}
					});
					clearInterval(t);
				}
			}, 100);
		},
		updateCargoReadyDateInput(pom, value) {
			if (moment(value).isValid())
				pom.cargo_ready_date = moment(value).format("YYYY-MM-DD");
			else pom.cargo_ready_date = "";
			pom.menuCargoReadyDate = false;
		},
		setupPurchaseOrderOptions() {
			this.purchaseOrderItems.map((poi, key) => {
				this.purchaseOrderItems[key].purchase_order_id = poi.id;

				this.purchaseOrderItems[key].products.map((po, keySecond) => {
					const unitsPerCarton = this.purchaseOrderItems[
						key
					].product_options.find((pOptions) => {
						return pOptions.product_id == po.product_id;
					});

					this.purchaseOrderItems[key].products[keySecond].cargo_ready_date =
						poi.cargo_ready_date;
					this.purchaseOrderItems[key].products[keySecond].units_per_carton =
						unitsPerCarton.units_per_carton;
				});

				this.purchaseOrderItems[key].purchase_order_id = poi.id;

				let options = this.$store.getters["po/getPurchaseOrderOptions"];
				if (this.reference === "formBookingShipment") {
					options = _.filter(
						options,
						(e) => e.status === "pending" || e.status === "partially_shipped"
					);
				}

				this.purchaseOrderItems[key].purchase_order_options = options;
			});
		},
		calculateOverAllTotal(entity) {
			//calculate only the default po
			let total = 0;
			if (this.purchaseOrderItems.length > 0) {
				this.purchaseOrderItems.map((poi) => {
					if (poi.layout === "default") {
						let getProducts = poi.products;
						if (getProducts.length > 0) {
							getProducts.map((gp) => {
								//total += parseInt(gp[entity])
								if (entity === "volume" || entity === "weight") {
									total += this.toFixed(parseFloat(gp[entity]));
								} else {
									total += parseInt(gp[entity]);
								}
							});
						}
					} else {
						if (entity === "volume") {
							total += this.toFixed(parseFloat(poi.total_volumes));
						} else if (entity === "carton") {
							total += parseInt(poi.total_cartons);
						} else if (entity === "weight") {
							total += this.toFixed(parseFloat(poi.total_weights));
						}
					}
				});
			}

			if (entity === "volume") {
				//iterate through container items sizes
				if (this.containerNewItems.length > 0) {
					this.containerNewItems.map((val, key) => {
						if (total >= 27 && total < 57 && val.key === "20'GP") {
							this.containerNewItems[key].checked = true;
						}

						if (total >= 57 && total < 67 && val.key === "40'GP") {
							this.containerNewItems[key].checked = true;
						}

						if (total >= 67 && total < 75 && val.key === "40'HQ") {
							this.containerNewItems[key].checked = true;
						}

						if (total >= 75 && val.key === "45'HQ") {
							this.containerNewItems[key].checked = true;
						}
					});
				}
			}

			return isNaN(total) ? 0 : total;
		},
		calculateTotals(po, entity) {
			//extract products from purchase order item
			let { products } = po;

			//initialize total
			let total = 0;

			//assign products origin value to newProducts
			let newProducts = products;
			let validProducts = _.filter(
				products,
				(e) =>
					typeof e.addSpecial == "undefined" && typeof e.special == "undefined"
			);

			if (
				po.selectAll ||
				(validProducts.length > 0 &&
					po.product_options.length > 0 &&
					validProducts.length == po.product_options.length)
			) {
				newProducts = _.filter(
					products,
					(e) => typeof e.addSpecial == "undefined"
				);
				po.selectAll = true;
			}
			//if there are products then sum all entity
			if (newProducts.length > 0) {
				newProducts.map((np) => {
					if (entity === "volume" || entity === "weight") {
						total = parseFloat(total) + parseFloat(np[entity]);
					} else {
						total = parseInt(total) + parseInt(np[entity]);
					}
				});
			}

			if (total === "00.00") total = "0.00";

			//finalize total volume and format to two decimal places
			if (entity === "volume" || entity === "weight") {
				total = parseFloat(total).toFixed(5);
			}

			return isNaN(total) ? (entity === "volume" ? "0.00" : 0) : total;
		},
		notifyParty() {
			this.is_notify_party = !this.is_notify_party;
		},
		getItemClass(item) {
			if (
				typeof item.addSpecial == "undefined" &&
				typeof item.special === "undefined"
			) {
				if (item.addAll) {
					return "item-row item-row-add";
				} else {
					return "item-row";
				}
			} else {
				return "item-row-special";
			}
		},
		filterProducts(po) {
			let { products } = po;

			let newProducts = products;
			let validProducts = _.filter(
				products,
				(e) =>
					typeof e.addSpecial == "undefined" && typeof e.special == "undefined"
			);
			if (
				po.selectAll ||
				(validProducts.length > 0 &&
					po.product_options.length > 0 &&
					validProducts.length == po.product_options.length)
			) {
				// newProducts = _.filter(
				// 	products,
				// 	(e) => typeof e.addSpecial == "undefined"
				// );
				po.selectAll = true;
			}
			return newProducts;
		},
		addAllCartons(item, key) {
			//set product key of set carton
			let productKey = this.purchaseOrderItems[key].products.indexOf(item);

			if (!item.addAll) {
				this.purchaseOrderItems[key].products[productKey].carton =
					item.unship_cartons;
			} else {
				this.purchaseOrderItems[key].products[productKey].carton = 1;
			}
			//update unit according to carton logic
			this.updateUnit(item, key);

			//toggle add all option of purchase order
			this.purchaseOrderItems[key].products[productKey].addAll = !item.addAll;
		},
		selectAllProducts(item, key) {
			//if there is selected purchase order then add all the products under that po
			if (item.purchase_order_id !== "") {
				this.purchaseOrderItems[key].products = [];

				//it means that user select all the purchase order
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
						//take out products with add special
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

						//take out products with special
						getProducts = _.filter(
							getProducts,
							(e) => typeof e.special == "undefined"
						);
						//set purchase order's products
						this.purchaseOrderItems[key].products = getProducts;
					}
				} else {
					//reset to default
					//clear products
					this.purchaseOrderItems[key].products = [];

					//if not mobile then do the logic
					//add the add products button and total section
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

				//set product label numbering
				this.purchaseOrderItems[key].products.map((p, k) => {
					let setIndex = parseInt(k) + 1;
					this.purchaseOrderItems[key].products[k].index =
						setIndex >= 10 ? setIndex : `0${setIndex}`;
				});

				//toggle select all
				this.purchaseOrderItems[key].selectAll = !item.selectAll;
			}
		},
		validateProduct(item, key) {
			//run validation against the selected product using the product_id
			//if the product is already used then return an error class
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
		updateUnit(item, key) {
			//update unit
			//formula: item's carton * item's unit per carton
			let productKey = this.purchaseOrderItems[key].products.indexOf(item);
			this.purchaseOrderItems[key].products[productKey].unit =
				item.carton * item.units_per_carton;

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
		updateCarton(item, key) {
			if (!item.quickAddProduct) {
				//get product key against the products list of the selected purchase order
				let productKey = this.purchaseOrderItems[key].products.indexOf(item);

				//divide the carton
				let divide_carton = parseInt(item.unit / item.units_per_carton);

				//see if there is remainder
				//if there is then add 1 carton
				if (item.unit % item.units_per_carton !== 0) {
					divide_carton++;
				}
				//set now the carton
				this.purchaseOrderItems[key].products[
					productKey
				].carton = divide_carton;
			}
		},
		calculateAmount(item) {
			//return this.currencyNumberFormat(item.unit_price * item.carton)
			//use parse float to format to decimal digit
			let total = parseFloat(item.unit_price) * parseFloat(item.unit);
			//assign now the total to item's amount
			item.amount = total;
			//fixed to 2 decimal point
			return `${isNaN(total) ? "0.00" : total.toFixed(2)}`;
		},
		customFilterPo(item, queryText, itemText) {
			//funtion for searching po
			//append the 3 data and then store them in data variable
			//convert them all to lowercase
			const data =
				item.po_number +
				item.ship_to.toLowerCase() +
				itemText.toLocaleLowerCase();

			//let's also convert search text to lowercase to match case with the data to search with
			this.ordersSearchText = queryText;

			//do the searching and return the index of the result
			//if no result then return with -1
			this.searchOrders = data.indexOf(queryText.toLowerCase()) > -1;
			return this.searchOrders;
		},
		customFilterProduct(item, queryText, itemText) {
			const data =
				item.product.category_sku +
				item.product.category_id +
				item.product.name.toLowerCase() +
				item.product.sku +
				itemText.toLocaleLowerCase();
			this.orderProductsSearchText = queryText;

			this.searchProducts = data.indexOf(queryText.toLowerCase()) > -1;
			return this.searchProducts;
		},
		customFilterSku(item, queryText, itemText) {
			const data =
				item.category_sku +
				item.category_id +
				item.name.toLowerCase() +
				item.sku +
				itemText.toLocaleLowerCase();
			this.orderProductsSearchText = queryText.toLowerCase();

			this.searchProducts = data.indexOf(this.orderProductsSearchText) > -1;
			return this.searchProducts;
		},
		getImgUrl(pic) {
			//get image url directory from po online
			let url = process.env.VUE_APP_PO_URL.slice(0, -3);
			let imageUrl = `${url}storage/`;

			//if pic is not null and defined
			if (typeof pic !== "undefined" && pic !== null) {
				if (pic.includes(imageUrl) !== "undefined" && !pic.includes(imageUrl)) {
					//concatonate the imageurl with the pic
					let newImage = `${imageUrl}${pic}`;
					return newImage;
				} else return pic;
			} else return require("../../assets/icons/default-product-icon.svg");
		},
		fetchPurchaseOrderOptions(qry, type, entity_id, other_id) {
			//fetch purchase order options
			// we did not pass query here as we will fetch purchase order options
			// based on the current login customer
			return new Promise((resolve) => {
				this.$store
					.dispatch("po/fetchPurchaseOrderOptions", {
						qry,
						type,
						entity_id,
						other_id,
					})
					.then((r) => {
						resolve(r);
					});
			});
		},
		updateProductPurchaseOrder(
			options,
			item,
			key,
			quickOrderAutoProduct = false
		) {
			//if we select a product from product options
			//then let's set it's meta values to other corresponding field in the row of product table
			//getting the index of the selected purchase order product
			//let findIndex = _.findIndex(this.purchaseOrderItems[key].products, e => (e.product_id == item.product_id))
			let findIndex = this.purchaseOrderItems[key].products.indexOf(item);
			//check against the available product options and then match it against the item's product id

			let findProduct = quickOrderAutoProduct
				? _.find(options, (e) => e.id == item.product_id)
				: _.find(options, (e) => e.product_id == item.product_id);

			//if we found it against the purchase order products options then let set the other relevant data now to other row item of that product
			// e.g carton, unit, units per carton,
			if (typeof findProduct !== "undefined") {
				// this.purchaseOrderItems[key].products[findIndex].product_id = item.product_id
				this.purchaseOrderItems[key].products[findIndex].carton = 1;

				this.purchaseOrderItems[key].products[findIndex].units_per_carton =
					findProduct.units_per_carton;

				this.purchaseOrderItems[key].products[findIndex].unit =
					this.purchaseOrderItems[key].products[findIndex].carton *
					findProduct.units_per_carton;

				if (quickOrderAutoProduct) {
					this.purchaseOrderItems[key].products[findIndex].weight =
						findProduct.weight == null
							? 0
							: findProduct.weight *
							  this.purchaseOrderItems[key].products[findIndex].unit;

					this.purchaseOrderItems[key].products[findIndex].volume =
						findProduct.volume == null
							? 0.00001
							: findProduct.volume *
							  this.purchaseOrderItems[key].products[findIndex].unit;

					this.purchaseOrderItems[key].products[findIndex].classification_code =
						findProduct.classification_code;

					this.purchaseOrderItems[key].products[
						findIndex
					].product_classification_description =
						findProduct.product_classification_description;

					this.purchaseOrderItems[key].products[findIndex].description =
						findProduct.description;
				} else {
					this.purchaseOrderItems[key].products[findIndex].weight =
						findProduct.product.weight == null
							? 0
							: findProduct.product.weight *
							  this.purchaseOrderItems[key].products[findIndex].unit;

					this.purchaseOrderItems[key].products[findIndex].volume =
						findProduct.product.volume == null
							? 0.00001
							: findProduct.product.volume *
							  this.purchaseOrderItems[key].products[findIndex].unit;
					this.purchaseOrderItems[key].products[findIndex].unship_cartons =
						findProduct.unship_cartons;

					this.purchaseOrderItems[key].products[findIndex].classification_code =
						findProduct.product.classification_code;

					this.purchaseOrderItems[key].products[
						findIndex
					].product_classification_description =
						findProduct.product.product_classification_description;

					this.purchaseOrderItems[key].products[findIndex].description =
						findProduct.product.description;
				}

				this.purchaseOrderItems[key].products[findIndex].unit_price =
					findProduct.unit_price;

				this.purchaseOrderItems[key].products[findIndex].meta = findProduct;

				this.purchaseOrderItems[key].products[
					findIndex
				].cargo_ready_date = this.purchaseOrderItems[key].cargo_ready_date;

				this.purchaseOrderItems[key].products[findIndex].volume =
					this.countShipmentsDecimals(
						this.purchaseOrderItems[key].products[findIndex].volume
					) > 5
						? this.purchaseOrderItems[key].products[findIndex].volume.toFixed(5)
						: this.purchaseOrderItems[key].products[findIndex].volume;

				this.purchaseOrderItems[key].products[findIndex].volume =
					this.purchaseOrderItems[key].products[findIndex].volume == 0
						? 0.00001
						: this.purchaseOrderItems[key].products[findIndex].volume;

				this.getProductsDescriptions();
			}
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
		updateProducts(item, key) {
			//always clear the checkbox when channging po
			this.purchaseOrderItems[key].loading = true;

			//if purchase order is unselected for some reason then clear all product options, products
			let otherOptions = _.find(
				this.purchaseOrderOptions,
				(e) => e.id == item.purchase_order_id
			);

			if (typeof otherOptions !== "undefined") {
				let supplier_id = otherOptions.supplier_id;
				let buyer_id = otherOptions.buyer_id;

				//fetch purchase order products when a po is selected then update the product options of the selected po
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
			return typeof item.warehouse !== "undefined" &&
				item.warehouse !== null &&
				typeof item.warehouse.name !== "undefined"
				? item.warehouse.name
				: "N/A";
		},
		deleteAllPurchaseOrders() {
			this.ordersSearchText = "";
			this.purchaseOrderItems = [];
		},
		formatDate(value) {
			return value ? moment(value).format("MMM DD, YYYY") : "N/A";
		},
		removePurchaseOrderManualItem(key) {
			this.purchaseOrderItems.splice(key, 1);
			this.checkOrdersButton();
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
			});
			this.checkOrdersButton();
		},
		setPurchaseOrderOptions() {
			let { selectedOrderIds } = this;
			this.purchaseOrderId++;

			selectedOrderIds = [];

			//get all seelcted purchase order id
			let items = this.purchaseOrderItems;
			let newOptions = [];

			if (items.length > 0) {
				items.map((i) => {
					if (selectedOrderIds.indexOf(i.purchase_order_id) == -1)
						selectedOrderIds.push(i.purchase_order_id);
				});
			}

			//iterate purchase order options
			this.purchaseOrderOptions.map((i) => {
				if (selectedOrderIds.length > 0) {
					if (selectedOrderIds.indexOf(i.id) == -1) {
						newOptions.push(i);
					}
				} else {
					newOptions.push(i);
				}
			});

			//assigned to data
			this.selectedOrderIds = selectedOrderIds;
			return newOptions;
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
		removeProductFromPurchaseOrders(key, item) {
			this.orderProductsSearchText = "";
			let getIndex = this.purchaseOrderItems[key].products.indexOf(item);
			this.purchaseOrderItems[key].selectAll = false;
			this.purchaseOrderItems[key].products.splice(getIndex, 1);

			this.getProductsDescriptions();
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
		notificationError(message) {
			iziToast.error({
				title: "Error",
				message: message,
				displayMode: 1,
				position: "bottomCenter",
				timeout: 3000,
			});
		},
		handleSubmit({ forwarders, is_review }) {
			let { reference } = this;

			if (this.$refs[reference].validate()) {
				this.submitRequestModalLoading = true;
				//get purchase order items
				let getPurchaseOrderItems = this.purchaseOrderItems;

				if (getPurchaseOrderItems.length > 0) {
					getPurchaseOrderItems.map((gpoi, key) => {
						//get also the products
						let getPurchaseOrderProducts = gpoi.products;

						getPurchaseOrderProducts.map((gpp, keySecond) => {
							getPurchaseOrderItems[key].products[keySecond].id =
								gpp.product_id;
						});
					});
				}

				//set document types map
				let documentTypes = DocumentTypeItems.items;

				//get the final purchase order items
				let finalPurchaseOrderItems = getPurchaseOrderItems;

				//iterate and modify documents on each purchase order items
				finalPurchaseOrderItems.map((fpi, key) => {
					let getDocuments = fpi.documents;
					let getProducts = fpi.products;

					if (typeof getDocuments !== "undefined") {
						if (getDocuments.length > 0) {
							getDocuments.map((gd, keySecond) => {
								let documentName = _.find(
									documentTypes,
									(e) => e.id == gd.type
								);
								if (typeof documentName !== "undefined") {
									getDocuments[keySecond].type = documentName.name;
								}
							});
							finalPurchaseOrderItems[key].documents = getDocuments;
						}
					} else finalPurchaseOrderItems[key].documents = [];

					if (typeof getProducts !== "undefined") {
						getProducts.map((gp, keySecond) => {
							getProducts[keySecond].ship_cartons = gp.carton;
						});
						finalPurchaseOrderItems[key].products = getProducts;
					}
				});

				if (finalPurchaseOrderItems && finalPurchaseOrderItems.length > 0) {
					this.totalCartons = this.calculateOverAllTotal("carton");
					this.totalVolumes = this.calculateOverAllTotal("volume");
					this.totalWeights = this.calculateOverAllTotal("weight");
				}

				//build payload
				let payload = {
					role: "consignee",
					shipper_id: this.editItem.shipper,
					consignee_id: this.editItem.consignee,
					shipper_details_info: this.editItem.shipper_details_info,
					consignee_details_info: this.editItem.consignee_details_info,
					location_from_id: this.editItem.location_from,
					location_to_id: this.editItem.location_to,
					notify_details_info:
						this.editItem.notify_details_info != null &&
						this.editItem.notify_details_info != "" &&
						this.editItem.notify_details_info != "null" &&
						this.editItem.notify_details_info != undefined &&
						this.editItem.notify_details_info != "undefined"
							? this.editItem.notify_details_info
							: "",
					mode: this.shipmentMode,
					type: this.editItem.type,
					commodities_info: this.editItem.commodities_info ?? "",
					marks: this.editItem.marks ?? "",
					another_type: this.editItem.anotherType,
					inco_term:
						this.shipmentIncoTerm === "Other"
							? this.editItem.inco_term_other
							: this.shipmentIncoTerm,
					purchase_orders: finalPurchaseOrderItems,
					is_review,
					forwarders: JSON.stringify(forwarders),
					customer_reference_number:
						this.editItem.customer_reference_number ?? "",
					final_address: this.editItem.final_address ?? "",
					containers: this.containerNewItems,
					special_instructions: this.editItem.special_instructions ?? "",
					schedule_id: new Date().getTime(),
					notify_party: this.editItem.is_notify_party ? 1 : 0,
					is_draft: 0,
					import_name_id: this.editItem.import_name_id,
					total_weight: this.totalWeights,
					total_volume: this.totalVolumes,
					total_cartons: this.totalCartons,
					bookingRequestForm: this.requestBookingToken,
				};

				this.submitLoading = false;

				//request now a booking
				this.$store
					.dispatch("booking/createShipment", payload)
					.then((res) => {
						this.submitRequestLoading = false;
						if (
							typeof res.data !== "undefined" &&
							typeof res.data.shipment !== "undefined" &&
							typeof res.data.shipment.id !== "undefined"
						) {
							this.submitShipmentId = res.data.shipment.id;
							this.$router.push(`/booking-request-submitted`);
						}
					})
					.catch((e) => {
						this.submitLoading = false;
						this.submitRequestLoading = false;
						this.notificationError(
							"An unexpected error occured. Please try again"
						);
						console.log(e);
					});
			} else {
				this.notificationErrorMessage();
				this.submitRequestLoading = false;
				this.selectPage({
					icon: "general",
					scrolling: true,
				});
				this.submitLoading = false;
				this.submitRequestLoading = false;
			}
		},
		notificationErrorMessage() {
			if (this.editItem.role == 0 || this.editItem.role == null) {
				this.notificationError("Please Select a Role");
			} else if (this.editItem.shipper == 0 || this.editItem.shipper == null) {
				this.notificationError("Please Select a shipper");
			} else if (
				this.editItem.consignee == 0 ||
				this.editItem.consignee == null
			) {
				this.notificationError("Please Select a consignee");
			} else if (
				this.editItem.location_from == 0 ||
				this.editItem.location_from == null
			) {
				this.notificationError("Please Select a Location from");
			} else if (
				this.editItem.location_to == 0 ||
				this.editItem.location_to == null
			) {
				this.notificationError("Please Select a Location to");
			} else if (this.shipmentMode == "" || this.shipmentMode == null) {
				this.notificationError("Please Select a Mode");
			} else if (
				(this.editItem.type == "" && this.shipmentMode !== "Air") ||
				this.editItem.type == null
			) {
				this.notificationError("Please Select a Type");
			} else if (this.shipmentIncoTerm == "" || this.shipmentIncoTerm == null) {
				this.notificationError("Please Select a inco term");
			} else if (!this.checkOrdersItems()) {
				if (this.totalVolumes == 0 || isNaN(this.totalVolumes)) {
					this.notificationError("Please Select a Total volume");
				} else if (this.totalWeights == 0 || isNaN(this.totalWeights)) {
					this.notificationError("Please Select a Total weight");
				}
			} else if (!this.manualBtnDisable) {
				this.purchaseOrderItems.map((item) => {
					if (
						item.purchase_order_number == "" ||
						item.purchase_order_number == null
					) {
						this.notificationError("Please Select a order number");
					} else if (item.total_cartons == 0 || isNaN(item.total_cartons)) {
						this.notificationError("Please Select a Total cartons");
					} else if (item.total_volumes == 0 || isNaN(item.total_volumes)) {
						this.notificationError("Please Select a Total volume");
					} else if (item.total_weights == 0 || isNaN(item.total_weights)) {
						this.notificationError("Please Select a Total weight");
					}
				});
			} else {
				this.purchaseOrderItems.map((item) => {
					if (item.quickAddOrder) {
						if (item.order_number == "" || item.order_number == null) {
							this.notificationError("Please Select a order number");
						}
					}
				});
			}
		},
		addShipment() {
			//show submit request modal
			let { reference } = this;

			if (this.$refs[reference].validate()) {
				this.submitRequestModalView = true;
				this.showSubmittedRequestDialog = true;
			} else {
				this.showSubmittedRequestDialog = false;
				this.submitRequestModalView = false;
				this.selectPage({
					icon: "general",
					scrolling: true,
				});
				this.notificationErrorMessage();
			}
		},
		ucFirst(str) {
			let pieces = str.split(" ");
			for (let i = 0; i < pieces.length; i++) {
				let j = pieces[i].charAt(0).toUpperCase();
				pieces[i] = j + pieces[i].substr(1);
			}
			return pieces.join(" ");
		},
		selectPage(item) {
			let findIndex = _.findIndex(this.sidebarItems, { icon: item.icon });
			this.sidebarItems.map((sidebarItem, key) => {
				this.sidebarItems[key].selected = false;
			});
			this.sidebarItems[findIndex].selected = true;
			if (!this.isMobile && typeof item.scrolling == "undefined") {
				this.scrollAnimating = true;
				//this.$refs[this.sidebarItems[findIndex].reference].scrollIntoView({block: 'start', behavior: 'smooth'})
				if (item.icon === "general") {
					jQuery("#second-column-id").animate({ scrollTop: 0 }, 500);
				} else if (item.icon === "po-icon") {
					jQuery("#second-column-id").animate({ scrollTop: 565 }, 500);
				} else if (item.icon === "container-icon") {
					jQuery("#second-column-id").animate({ scrollTop: 1000 }, 500);
				}
				setTimeout(() => {
					this.scrollAnimating = false;
				}, 500);
			}
		},
		clickOutside() {
			//reset to default before closing
			this.editItem = BookingItem;

			//close form
			this.$emit("close");
		},
		close() {
			//reset form
			this.resetData();

			if (typeof this.$refs[this.reference] !== "undefined") {
				this.$refs[this.reference].reset();
			}

			//close form
			this.$emit("close");
		},
		checkOrdersButton() {
			if (this.purchaseOrderItems.length > 0) {
				var _this = this;
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
		calulateTotalCartons(cartons) {
			this.totalCartons = parseFloat(cartons);
		},
		calulateTotalVolume(volume) {
			this.totalVolumes = parseInt(volume);
		},
		calulateTotalWeights(weight) {
			this.totalWeights = parseInt(weight);
		},
		checkOrdersItems() {
			if (this.manualBtnDisable) {
				return (
					this.purchaseOrderItems &&
					this.purchaseOrderItems.some((item) =>
						item?.products.some(
							(id) =>
								id.product_id > 0 ||
								(id.quickAddProduct && id.product_number !== "")
						)
					)
				);
			} else {
				return this.purchaseOrderItems && this.purchaseOrderItems.length > 0;
			}
		},
		selectOpenedProductBox(item) {
			let key = parseInt(item.index) - 1;
			this.selectedProductForQuickAdd = key;
		},
		showField(product, poKey) {
			this.purchaseOrderItems[poKey].products.filter((item) => {
				if (item.id == product.id) {
					item.isEditingField = item.quickAddProduct;
					this.productDescription = item.description;
				} else {
					item.isEditingField = false;
				}
			});
		},
		saveProductDescriptions(product, poKey) {
			this.purchaseOrderItems[poKey].products.filter((item) => {
				if (item.id == product.id) {
					item.description = this.productDescription;
					item.isEditingField = false;
				}
			});
		},
		clearProductDescriptions(product, poKey) {
			this.purchaseOrderItems[poKey].products.filter((item) => {
				if (item.id == product.id) {
					this.productDescription = "";
					item.isEditingField = false;
				}
			});
		},
		disabledProductsField(quickProduct, quickOrder) {
			return quickOrder || quickProduct ? false : true;
		},
	},
	watch: {
		is_notify_party(newValue) {
			this.editItem.is_notify_party = newValue;
		},
		userRole: {
			handler(newValue, oldValue) {
				console.log("oldValue", oldValue);
				console.log("newValue", newValue);
				this.editItem.role = newValue;
			},
			deep: true,
		},
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/bookingRequestForm/bookingRequestForm.scss";
</style>
<style>
.shipment-booking-request-form-wrapper {
	max-height: 100vh;
	overflow: hidden;
}
</style>
