<!-- @format -->

<template>
	<div
		style="position: absolute; top: -64px; left: 0px;z-index: 2000; width: 100%;"
	>
		<!-- reused components goes here -->
		<SupplierDialog
			:editedIndexData.sync="editedIndex"
			:dialogData.sync="showSupplierDialog"
			:editedItemData.sync="supplierEditItem"
			:defaultItemData.sync="defaultSupplierItem"
			@setToDefault="setToDefaultSupplierItem"
			:customerPagination="customerPagination"
			:suppliersPagination="suppliersPagination"
			@close="close"
			:searchVendors.sync="searchVendors"
			:searchCustomers.sync="searchCustomers"
			:suppliersData="suppliers"
			:customersData="customers"
			:fromComponent="'BookingShipmentDialog'"
		/>

		<booking-shipment-dialog-mobile
			v-if="isMobile"
			:reference="reference"
			:showSubmitRequestModal="showSubmitRequestModal"
			@selectRole="selectRole"
			:containerNewItems="containerNewItems"
			@updateCargoReadyDateInput="updateCargoReadyDateInput"
			@addManualPurchaseOrder="addManualPurchaseOrder"
			@updateProducts="updateProducts"
			:paymentTerms="paymentTerms"
			:paymentTermsOthers="paymentTermsOthers"
			:roles="roles"
			:anotherTypes="anotherTypes"
			:locationFromRules="locationFromRules"
			:locationToRules="locationToRules"
			@notifyParty="notifyParty"
			:filteredShipperOptions="filteredShipperOptions"
			:filteredConsigneeOptions="filteredConsigneeOptions"
			:filteredTerminalRegions="filteredTerminalRegions"
			:isMobile="isMobile"
			:show="show"
			@addShipmentSuccess="addShipmentSuccess"
			:loaded="loaded"
			:sidebarItems="sidebarItems"
			:containerItems="containerItems"
			@getImgUrl="getImgUrl"
			@reloadShipments="reloadShipments"
			@selectAllProducts="selectAllProducts"
			@addAllCartons="addAllCartons"
			@removeProductFromPurchaseOrders="removeProductFromPurchaseOrders"
			@addProductToPurchaseOrders="addProductToPurchaseOrders"
			@removePurchaseOrderItem="removePurchaseOrderItem"
			@addPurchaseOrderItem="addPurchaseOrderItem"
			@selectPage="selectPage"
			@updateProductPurchaseOrder="updateProductPurchaseOrder"
			@currencyNumberFormat="currencyNumberFormat"
			@close="close"
			:windowWidth="windowWidth"
			:purchaseOrderOptions="purchaseOrderOptions"
			:modes="modes"
			:menuProps="menuProps"
			:purchaseOrderItems.sync="purchaseOrderItems"
			:types="types"
			:manualSupplierOptions="manualSupplierOptions"
			:editItem="editItem"
			@addShipment="addShipment"
			:customFilterPo="customFilterPo"
		/>

		<v-dialog
			v-model="show"
			v-if="!isMobile"
			@click:outside="clickOutside"
			max-width="1376px"
			:content-class="`${className} create-shipment-ultimate-wrapper`"
		>
			<v-card id="booking-shipment-dialog-id" class="booking-shipment-dialog">
				<v-card-title class="headline">
					<slot name="title"></slot>
					<button icon dark class="btn-close" @click.stop="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>

				<v-card-text class="pb-0">
					<div
						id="card-text-wrapper"
						ref="cardTextWrapper"
						class="d-flex flex-row"
					>
						<slot
							v-bind:mainContent="{
								sidebarItems: sidebarItems,
								selectPage: selectPage,
								loaded: loaded,
							}"
							name="sidebar"
						></slot>
						<div
							class="preloader d-flex w-full justify-center align-center pt-4 pb-4"
							v-if="!loaded"
						>
							<v-progress-circular :size="40" color="#0171a1" indeterminate>
							</v-progress-circular>
						</div>
						<div
							id="second-column-id"
							v-if="loaded"
							class="d-flex flex-column edit-shipment-dialog-main-content second-column"
						>
							<div
								id="generalInformationSection"
								ref="generalInformationSection"
								class="d-flex flex-row justify-space-between"
							>
								<div v-if="loaded" class="content-title general-title">
									{{ "General Information" }}
								</div>
							</div>
							<v-form
								v-if="loaded"
								class=""
								:ref="reference"
								action="#"
								@submit.prevent=""
							>
								<div class="d-flex flex-row">
									<div
										class="form-edit-shipment-form-first-column form-wrapper"
									>
										<div class="d-flex flex-row">
											<div style="width: 100%;" class="d-flex flex-column">
												<div class="form-label">
													<span>{{ "YOUR ROLE" }}</span>
													<span
														style="color:red !important; padding-left: 5px;"
														>{{ "*" }}</span
													>
												</div>
												<div
													style="margin-bottom: 0px;"
													class="d-flex radio-group-wrapper"
												>
													<div
														v-bind:key="`role-${key}`"
														v-for="(r, key) in roles"
														:class="
															`d-flex radio-item align-center ${
																r === editItem.role ? 'selected' : ''
															}`
														"
													>
														<label
															style="position: relative;"
															class="radio-label-wrapper"
														>
															{{ ucFirst(r) }}
															<input
																name="role"
																@click.stop="selectRole(r)"
																:value="r"
																class="custom-radio"
																style="position: absolute; opacity: 0;"
																type="radio"
																v-model="userRole"
															/>
															<span></span>
														</label>
													</div>
												</div>
												<div>
													<role-error-style id="role-error-wrapper-id">
														<template v-slot:content>
															<v-text-field
																:rules="roleRules"
																v-model="userRole"
															></v-text-field>
														</template>
													</role-error-style>
												</div>
											</div>
										</div>

										<select-auto-complete
											content-class="shipper-main-wrapper"
											label="SHIPPER"
											:required="true"
											:item="editItem"
											@showSupplierModal="showSupplierModal"
											:field.sync="editItem.shipper"
											:items="filteredShipperOptions"
											:menuProps="{
												contentClass: 'po-lists-items',
												...menuProps,
											}"
											marginBottom="16px"
											width="93%"
											:background="
												`${
													editItem.role === 'shipper'
														? 'selected'
														: 'not-selected'
												}`
											"
										>
											<template v-slot:input="{ mainContent }">
												<v-autocomplete
													attach=".shipper-main-wrapper"
													spellcheck="false"
													:items="mainContent.items"
													placeholder="Select Shipper"
													outlined
													@change="
														(value) => {
															mainContent.updateRole(
																value,
																filteredShipperOptions,
																'shipper'
															);
															deleteAllPurchaseOrders();
														}
													"
													item-text="name"
													item-value="id"
													:height="40"
													:value="mainContent.field"
													:disabled="editItem.role === 'shipper'"
													:menu-props="{ ...mainContent.menuProps }"
													hide-details="auto"
													clearable
												>
													<template v-slot:item="{ item }">
														<div
															class="d-flex flex-column row-wrapper"
															style="width: 100%;"
														>
															<div
																style="width: 100%;"
																class="d-flex first-row justify-space-between"
															>
																<span
																	v-if="!item.special"
																	style="color: #4A4A4A !important;"
																	>{{ item.name }}</span
																>
																<a
																	style="color: #4A4A4A !important; display: flex; width: 100%; text-decoration: none; cursor:pointer;"
																	v-if="item.special"
																	@click.stop="
																		(value) =>
																			mainContent.updateCustomValue(item, value)
																	"
																	><span>{{ item.name + " +" }}</span></a
																>
															</div>
														</div>
													</template>
												</v-autocomplete>
											</template>
										</select-auto-complete>
										<div class="form-label">
											<span style="text-transform: uppercase;">{{
												"Shipper’s details info"
											}}</span>
										</div>
										<div
											id="text-area-wrapper-5"
											class="text-field-wrapper input-text-wrapper textarea-wrapper"
										>
											<v-textarea
												class="text-fields"
												outlined
												:height="76"
												v-model="editItem.shipper_details_info"
												placeholder="Shipper's info"
												hide-details="auto"
												autocomplete="off"
											>
											</v-textarea>
										</div>

										<select-auto-complete
											content-class="location-from-main-wrapper"
											label="FROM"
											:required="true"
											:field.sync="editItem.location_from"
											:items="filteredTerminalRegions"
											:menuProps="{
												contentClass: 'po-lists-items',
												...menuProps,
											}"
											marginBottom="16px"
											width="93%"
											:rules="locationFromRules"
											background="not-selected"
										>
											<template v-slot:input="{ mainContent }">
												<v-autocomplete
													attach=".location-from-main-wrapper"
													spellcheck="false"
													:rules="mainContent.rules"
													:items="mainContent.items"
													:value="editItem.location_from"
													placeholder="Enter Location (Port, Warehouse etc.)"
													outlined
													@change="mainContent.updateValue"
													:height="40"
													:menu-props="{ ...mainContent.menuProps }"
													hide-details="auto"
													clearable
												>
													<template v-slot:item="{ item }">
														<div
															class="d-flex flex-column row-wrapper"
															style="width: 100%;"
														>
															<div
																style="width: 100%;"
																class="d-flex first-row justify-space-between"
															>
																<span style="color: #4A4A4A !important;">{{
																	item.text
																}}</span>
															</div>
														</div>
													</template>
												</v-autocomplete>
											</template>
										</select-auto-complete>

										<select-auto-complete
											content-class="location-to-main-wrapper"
											label="TO"
											:required="true"
											:field.sync="editItem.location_to"
											:items="filteredTerminalRegions"
											:menuProps="{
												contentClass: 'po-lists-items',
												...menuProps,
											}"
											marginBottom="16px"
											width="93%"
											background="not-selected"
											:rules="locationToRules"
										>
											<template v-slot:input="{ mainContent }">
												<v-autocomplete
													attach=".location-to-main-wrapper"
													spellcheck="false"
													:items="mainContent.items"
													placeholder="Enter Location (Port, Warehouse etc.)"
													:value="editItem.location_to"
													outlined
													@change="mainContent.updateValue"
													:height="40"
													:rules="mainContent.rules"
													:menu-props="{ ...mainContent.menuProps }"
													hide-details="auto"
													clearable
												>
													<template v-slot:item="{ item }">
														<div
															class="d-flex flex-column row-wrapper"
															style="width: 100%;"
														>
															<div
																style="width: 100%;"
																class="d-flex first-row justify-space-between"
															>
																<span style="color: #4A4A4A !important;">{{
																	item.text
																}}</span>
															</div>
														</div>
													</template>
												</v-autocomplete>
											</template>
										</select-auto-complete>

										<div
											style="margin-bottom: 6px !important"
											class="form-label"
										>
											<span>{{ "MODE" }}</span>
											<span style="color:red !important; padding-left: 5px;">{{
												"*"
											}}</span>
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
												<kenetic-icon
													:marginLeft="8"
													:iconName="m.toLowerCase()"
												/>
											</div>
										</div>

										<div class="form-label required">
											<span>{{ "FINAL ADDRESS" }}</span>
										</div>
										<text-field-style id="final-address-wrapper">
											<template v-slot:content>
												<div class="d-flex flex-row">
													<div
														style="width: 100%;"
														id="input-text-1"
														class="text-field-wrapper input-text-wrapper"
													>
														<v-text-field
															:height="40"
															color="#002F44"
															width="200px"
															v-model="editItem.final_address"
															type="text"
															dense
															placeholder="Enter Final Address"
															outlined
															hide-details="auto"
														>
														</v-text-field>
													</div>
												</div>
											</template>
										</text-field-style>

										<div class="form-label required">
											<span>{{ "CUSTOM REFERENCE" }}</span>
										</div>

										<text-field-style id="custom-reference-wrapper">
											<template v-slot:content>
												<div class="d-flex flex-row">
													<div
														style="width: 100%;"
														id="input-text-1"
														class="text-field-wrapper input-text-wrapper"
													>
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
														>
														</v-text-field>
													</div>
												</div>
											</template>
										</text-field-style>
									</div>

									<div
										:style="`${getEditingShipment ? '' : 'padding-top: 76px;'}`"
										class="form-edit-shipment-form-second-column form-wrapper"
									>
										<div
											class="form-label eta-label required"
											v-if="editItem.role === 'consignee'"
										>
											<span>CONSIGNEE</span>
										</div>

										<v-select
											class="text-fields select-items carton-uom consignee-name-wrapper mb-4"
											:items="filteredConsigneeOptions"
											height="40px"
											outlined
											item-text="name"
											item-value="id"
											v-model="editItem.consignee"
											hide-details="auto"
											v-if="editItem.role === 'consignee' && filteredImportNameOptions.length == 0"
											disabled
										/>

										<v-autocomplete
											class="text-fields select-items carton-uom import-and-consignnee pb-4"
											:items="filteredImportNameOptions"
											height="40px"
											outlined
											v-model="editItem.import_name_id"
											item-text="name"
											item-value="id"
											hide-details="auto"
											:menu-props="{
												contentClass: 'consignee-import-name-wrapper',
												bottom: true,
												offsetY: true,
											}"
											v-if="editItem.role === 'consignee' && filteredImportNameOptions && filteredImportNameOptions.length > 0"
										>
											<v-list
												slot="prepend-item"
												class="consignee-details pl-4"
											>
												<div class="import-title company-title py-2">
													<span> Company Information </span>
												</div>
												<div
													v-for="(consignee, index) in consigneeDetails"
													:key="index"
													class="py-2"
												>
													<img
														src="@/assets/images/building.svg"
														alt="building"
														class="building-image"
													/>
													<div class="company-details-wrapper">
														<div
															style="width: 100%;"
															class="d-flex first-row justify-space-between"
														>
															<span class="company-name">{{
																consignee.company_name
															}}</span>
														</div>
														<div
															class="d-flex second-row"
															style="width: 100%; padding-bottom: 4px;"
														>
															<span> {{ consignee.address }}</span>
														</div>
														<div
															class="d-flex third-row"
															style="width: 100%; padding-bottom: 4px;"
														>
															<span class="text-list">Email: </span
															><span> {{ consignee.email }} </span>
														</div>
														<div
															class="d-flex fourth-row"
															style="width: 100%; padding-bottom: 4px;"
														>
															<span class="text-list">Phone Number: </span>
															<span> {{ consignee.phone }} </span>
														</div>
													</div>
												</div>
												<div class="import-title py-2">
													<span> Import name </span>
												</div>
											</v-list>
											<template v-slot:item="{ item }">
												<div
													class="d-flex flex-column import-name-details"
													style="width: 100%;"
												>
													<div
														style="width: 100%;"
														class="d-flex first-row justify-space-between"
													>
														<span class="import-name">{{ item.name }}</span>
													</div>
													<div
														style="width: 100%;"
														class="d-flex first-row justify-space-between"
													>
														<span class="import-name-address">{{
															item.importAddress
														}}</span>
													</div>
												</div>
											</template>
										</v-autocomplete>

										<select-auto-complete
											v-if="editItem.role !== 'consignee'"
											content-class="consignee-main-wrapper"
											label="CONSIGNEE"
											:required="true"
											:item.sync="editItem"
											@showSupplierModal="showSupplierModal"
											:field.sync="editItem.consignee"
											:items="filteredConsigneeOptions"
											:menuProps="menuProps"
											marginBottom="16px"
											:background="
												`${
													editItem.role === 'consignee'
														? 'selected'
														: 'not-selected'
												}`
											"
										>
											<template v-slot:input="{ mainContent }">
												<v-autocomplete
													attach=".consignee-main-wrapper"
													spellcheck="false"
													:items="mainContent.items"
													placeholder="Select Consignee"
													outlined
													:value="mainContent.field"
													:disabled="editItem.role === 'consignee'"
													@change="
														(value) => {
															mainContent.updateRole(
																value,
																filteredConsigneeOptions,
																'consignee'
															);
															deleteAllPurchaseOrders();
														}
													"
													item-text="name"
													item-value="id"
													:height="40"
													:menu-props="{ ...mainContent.menuProps }"
													hide-details="auto"
													clearable
												>
													<template v-slot:item="{ item }">
														<div
															class="d-flex flex-column row-wrapper"
															style="width: 100%;"
														>
															<div v-if="!item.special">
																<div
																	style="width: 100%;"
																	class="d-flex first-row justify-space-between"
																>
																	<span>{{ item.name }}</span>
																</div>
																<div
																	class="d-flex second-row"
																	style="width: 100%; padding-bottom: 4px;"
																>
																	<span>
																		{{ item.address }}
																	</span>
																</div>
															</div>
															<a
																style="color: #4A4A4A !important; display: flex; width: 100%; text-decoration: none; cursor: pointer;"
																v-if="item.special"
																@click.stop="
																	(value) => {
																		mainContent.updateCustomValue(item, value);
																	}
																"
																><span>{{ item.name + "+" }}</span></a
															>
														</div>
													</template>
												</v-autocomplete>
											</template>
										</select-auto-complete>

										<div class="form-label">
											<span style="text-transform: uppercase;">{{
												"CONSIGNEE’S DETAILS INFO"
											}}</span>
										</div>
										<div
											style="width: 100% !important;"
											id="text-area-wrapper-3"
											class="text-field-wrapper input-text-wrapper textarea-wrapper"
										>
											<v-textarea
												class="text-fields"
												outlined
												:height="76"
												v-model="editItem.consignee_details_info"
												placeholder="Enter consignees details info"
												hide-details="auto"
												autocomplete="off"
											>
											</v-textarea>
										</div>

										<div
											class="checkbox-wrapper-create checkbox-wrapper-desktop checkbox-wrapper-notify-party"
										>
											<label
												:class="`${is_notify_party ? 'checked' : ''}`"
												style="position: relative;"
											>
												<generic-icon
													:marginLeft="0"
													:iconName="
														`${is_notify_party ? 'checked' : 'not-checked'}`
													"
												></generic-icon>
												<input
													@click.prevent="notifyParty()"
													style="position: absolute; opacity: 0;"
													type="checkbox"
													:checked="is_notify_party"
													v-model="is_notify_party"
													class=""
												/>
												<span
													style="color: #4A4A4A; padding-left: 12px !important;"
													>Notify party is different from Consignee</span
												>
											</label>
										</div>

										<div class="form-label">
											<span style="text-transform: uppercase;">{{
												"NOTIFY"
											}}</span>
										</div>
										<div
											style="width: 100% !important;"
											id="text-area-wrapper-3"
											class="text-field-wrapper input-text-wrapper textarea-wrapper"
										>
											<v-textarea
												:disabled="!is_notify_party"
												class="text-fields"
												outlined
												:height="76"
												v-model="editItem.notify_details_info"
												placeholder="Select Consignee for notify details"
												hide-details="auto"
												autocomplete="off"
											>
											</v-textarea>
										</div>

										<div
											v-if="filteredTypes.length > 0"
											style="margin-bottom: 6px !important;"
											class="form-label"
										>
											<span
												:class="
													`${
														editItem.type == '' && shipmentMode == ''
															? 'unselected'
															: 'selected'
													}`
												"
												>{{ "TYPE" }}</span
											>
											<span style="color:red !important; padding-left: 5px;">{{
												"*"
											}}</span>
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
												<kenetic-icon
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

										<div
											style="margin-bottom: 7px;"
											class="form-label d-flex incoterms-wrapper"
										>
											<span :class="`d-flex align-end`">{{ "INCOTERMS" }}</span>
											<span style="color:red !important; padding-left: 5px;">{{
												"*"
											}}</span>
											<span id="incoterms-tooltip-wrapper">
												<v-tooltip
													attach="#incoterms-tooltip-wrapper"
													left
													content-class="incoterms-tooltip"
												>
													<template v-slot:activator="{ on, attrs }">
														<div
															style="position: relative; top: 2px;"
															v-on="on"
															v-bind="attrs"
														>
															<generic-icon
																color="#4A4A4A"
																iconName="info-icon"
															></generic-icon>
														</div>
													</template>
													<div>
														{{
															"International Commercial Terms, are a set of international rules produced by the International Chamber of Commerce (ICC)."
														}}
													</div>
												</v-tooltip>
											</span>
										</div>
										<div
											style="max-height: none !important; margin-bottom: 0px;"
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

											<div>
												<role-error-style id="incoterm-error-wrapper-id">
													<template v-slot:content>
														<v-text-field
															:rules="incoTermRules"
															v-model="shipmentIncoTerm"
														></v-text-field>
													</template>
												</role-error-style>
											</div>
											<div style="clear:both;"></div>
										</div>
									</div>
								</div>

								<div
									id="purchaseOrderSection"
									ref="purchaseOrderSection"
									class="d-flex flex-column purchase-orders-section"
								>
									<div
										style="padding-bottom: 8px;"
										class="content-title orders-title"
									>
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
																Quickly add one here for this booking request.
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
													<v-list
														slot="append-item"
														class="px-4 add-new-orders-options quick-add-content"
													>
														<div class="add-new-orders-buttons">
															<p
																class="mb-0 quick-order"
																@click.stop="quickAddOrders(key)"
															>
																<img
																	src="@/assets/icons/plus.svg"
																	class="mr-2"
																	width="12px"
																	height="12px"
																	alt=""
																/>
																Quick Add
																<span>{{
																	editItem.role === "shipper"
																		? ordersSearchText
																			? " ‘SO " + ordersSearchText + "’"
																			: "SO"
																		: ordersSearchText
																		? " ‘PO " + ordersSearchText + "’"
																		: "PO"
																}}</span>
															</p>
															<p
																class="mb-0 add-order"
																@click.stop="createOrder"
															>
																Open Full Form
																<v-icon>mdi-chevron-right</v-icon>
															</p>
														</div>
													</v-list>
												</v-autocomplete>
												<div v-if="poi.quickAddOrder">
													<v-text-field
														height="40px"
														color="#002F44"
														v-model="poi.order_number"
														type="text"
														dense
														class="select-purchase-order-po select-purchase-order"
														:placeholder="
															`${
																editItem.role === 'shipper'
																	? 'Enter SO'
																	: 'Enter PO'
															}`
														"
														outlined
														hide-details="auto"
													/>
												</div>
												<div
													class="checkbox-wrapper-create checkbox-wrapper-desktop"
													v-if="!poi.quickAddOrder"
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
												<div
													class="text-field-wrapper dates-wrapper"
													v-if="poi.quickAddOrder"
												>
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
																	<v-list
																		slot="append-item"
																		class="px-4 add-new-orders-options quick-add-content"
																	>
																		<div class="add-new-orders-buttons">
																			<p
																				class="mb-0 quick-order"
																				@click.stop="
																					quickAddProducts(
																						key,
																						selectedProductForQuickAdd
																					)
																				"
																			>
																				<img
																					src="@/assets/icons/plus.svg"
																					class="mr-1"
																					width="12px"
																					height="12px"
																				/>
																				Quick Add
																				<span>{{
																					orderProductsSearchText
																						? orderProductsSearchText
																						: "Product"
																				}}</span>
																			</p>
																			<p
																				class="mb-0 add-order"
																				@click.stop="createProduct(key)"
																			>
																				Open Full Form
																				<v-icon>mdi-chevron-right</v-icon>
																			</p>
																		</div>
																	</v-list>
																</v-autocomplete>
															</div>

															<div
																v-if="
																	poi.quickAddOrder && !item.quickAddProduct
																"
															>
																<v-autocomplete
																	v-if="
																		typeof item.addSpecial == 'undefined' &&
																			typeof item.special == 'undefined'
																	"
																	:filter="customFilterSku"
																	v-model="item.product_id"
																	spellcheck="false"
																	class="select-product select-purchase-order"
																	:items="
																		editItem.role === 'shipper'
																			? getBuyersProducts
																			: getVendorsProducts
																	"
																	placeholder="Select Product"
																	outlined
																	item-text="name"
																	item-value="id"
																	:menu-props="{
																		contentClass:
																			'po-lists-items order-items-lists',
																		...menuProps,
																	}"
																	hide-details="auto"
																	clearable
																	@change="
																		updateProductPurchaseOrder(
																			editItem.role === 'shipper'
																				? getBuyersProducts
																				: getVendorsProducts,
																			item,
																			key,
																			true
																		)
																	"
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
																						>#{{ item.category_sku }}</span
																					>
																				</div>
																				<div>
																					<p>{{ item.name }}</p>
																					<p class="product-unit-price">
																						${{
																							item.unit_price !== null
																								? item.unit_price.toFixed(2)
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
																					:src="getImgUrl(item.image)"
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
																	<v-list
																		slot="append-item"
																		class="px-4 add-new-orders-options quick-add-content"
																	>
																		<div class="add-new-orders-buttons">
																			<p
																				class="mb-0 quick-order"
																				@click.stop="
																					quickAddProducts(
																						key,
																						selectedProductForQuickAdd
																					)
																				"
																			>
																				<img
																					src="@/assets/icons/plus.svg"
																					class="mr-1"
																					width="12px"
																					height="12px"
																				/>
																				Quick Add
																				<span>{{
																					orderProductsSearchText
																						? orderProductsSearchText
																						: "Product"
																				}}</span>
																			</p>
																			<p
																				class="mb-0 add-order"
																				@click.stop="createProduct()"
																			>
																				Open Full Form
																				<v-icon>mdi-chevron-right</v-icon>
																			</p>
																		</div>
																	</v-list>
																</v-autocomplete>
															</div>

															<div v-if="item.quickAddProduct">
																<v-text-field
																	v-if="
																		typeof item.addSpecial == 'undefined' &&
																			typeof item.special == 'undefined'
																	"
																	height="40px"
																	color="#002F44"
																	v-model="item.product_number"
																	type="text"
																	dense
																	class="unit sku-id"
																	placeholder="SKU ID"
																	outlined
																	hide-details="auto"
																/>
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
													:shipmentId="item.id"
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
											<span class="add-purchase-order-span">{{
												editItem.role === "shipper"
													? "+ Add Sales Orders"
													: "+ Add Purchase Orders"
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
														? "+ Add Manual SO"
														: "+ Add Manual PO"
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
											<div class="form-label">
												<span class="text-uppercase">Total Cartons</span>
											</div>
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
											<div class="form-label">
												<span class="text-uppercase">Total Volume </span>
												<span
													class="required-asterisk"
													v-if="!checkOrdersItems()"
													>*</span
												>
											</div>
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
											<div class="form-label">
												<span class="text-uppercase">Total Weight</span>
												<span
													class="required-asterisk"
													v-if="!checkOrdersItems()"
													>*</span
												>
											</div>
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
									v-if="false"
									id="containerSection"
									ref="containerSection"
									class="d-flex flex-column containers-section"
								>
									<div class="content-title">
										{{ "Containers" }}
									</div>
									<containers-tracking-table
										:items.sync="containerItems"
										:headers="headersContainers"
										:sizes="filteredContainerSizes"
										contentClass="container-wrapper"
									>
										<template v-slot:removeIcon>
											<generic-icon
												color="#F93131"
												iconName="trash-product"
											></generic-icon>
										</template>
									</containers-tracking-table>
								</div>

								<div
									id="containerSection"
									ref="containerSection"
									class="d-flex flex-row containers-section"
								>
									<div style="width: 50%;">
										<div class="content-title containers-title">
											{{ "Containers" }}
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
									<div style="width: 50%;">
										<div class="content-title">
											{{ "Others" }}
										</div>
										<div class="d-flex flex-column">
											<marks-style id="booking-marks-wrapper">
												<template v-slot:content>
													<custom-text-area
														label="Marks"
														marginTop="0px"
														:field.sync="editItem.marks"
														labelColor="#819FB2"
														placeholderText="Enter Marks"
														:inputFontWeight="400"
														:inputFontSize="14"
													>
														<template v-slot:label="{ label }">
															<div class="form-label">
																<span class="text-uppercase">{{ label }}</span>
																<span style="color: #819FB2 !important;">
																	{{ "(Optional)" }}</span
																>
															</div>
														</template>
														<template v-slot:input="{ mainContent }">
															<v-textarea
																:class="
																	`text-fields custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize}`
																"
																outlined
																:height="600"
																v-model="editItem.marks"
																:value="mainContent.field"
																@change="mainContent.updateValue"
																:placeholder="mainContent.placeholderText"
																hide-details="auto"
																autocomplete="off"
															>
															</v-textarea>
														</template>
													</custom-text-area>
												</template>
											</marks-style>
											<marks-style id="booking-marks-wrapper-2">
												<template v-slot:content>
													<custom-text-area
														marginBottom="100px"
														label="Special Instructions"
														id="text-area-wrapper-custom-2"
														:field.sync="editItem.special_instructions"
														labelColor="#819FB2"
														placeholderText="Enter Special Instructions"
														:inputFontWeight="400"
														:inputFontSize="14"
													>
														<template v-slot:label="{ label }">
															<div class="form-label">
																<span class="text-uppercase">{{ label }}</span>
																<span style="color: #819FB2 !important;">
																	{{ "(Optional)" }}</span
																>
															</div>
														</template>
														<template v-slot:input="{ mainContent }">
															<v-textarea
																outlined
																:height="600"
																v-model="editItem.special_instructions"
																:value="mainContent.field"
																@change="mainContent.updateValue"
																:placeholder="mainContent.placeholderText"
																:class="
																	`text-fields custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize}`
																"
																hide-details="auto"
																autocomplete="off"
															>
															</v-textarea>
														</template>
													</custom-text-area>
												</template>
											</marks-style>
										</div>
									</div>
								</div>
							</v-form>
						</div>
						<slot
							v-bind:mainContent="{
								loaded: loaded,
								item: editItem,
								sidebarItems: sidebarItems,
								rules: rules,
							}"
							name="content"
						></slot>
					</div>
				</v-card-text>
				<v-card-actions>
					<slot
						v-bind:footer="{
							close: close,
							createShipment: addShipment,
							createLoading: getCreateShipmentLoading,
							updateShipment: updateShipment,
							saveAsDraft: saveAsDraft,
							submitLoading: submitLoading,
						}"
						name="actions"
					></slot>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<confirm-role-modal
			:isMobile="isMobile"
			:show="showConfirmRoleModal"
			@close="closeConfirmRoleModal"
			@submit="handleConfirmRole"
			@cancelRole="cancelRole"
			:currentRole="editItem.role"
		>
			<template v-slot:title>
				<generic-icon iconName="warning" color="#D68A1A"></generic-icon>
			</template>
			<template v-slot:actions="{ footer }">
				<div class="d-flex footer">
					<v-btn
						:disabled="footer.submitLoading"
						style="margin-right: 8px;"
						class="save-btn btn-blue"
						text
						@click="footer.submit"
					>
						<span>{{ "Change Role" }}</span>
					</v-btn>
					<v-btn
						class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue"
						text
						@click="footer.cancelRole"
					>
						<span>{{ "Cancel" }}</span>
					</v-btn>
				</div>
			</template>
		</confirm-role-modal>

		<submit-request-modal
			:submitShipmentId="submitShipmentId"
			:isMobile="isMobile"
			:bookingShipmentDialogView.sync="bookingShipmentDialogView"
			:submitRequestLoading.sync="submitRequestLoading"
			:showDialog.sync="showSubmittedRequestDialog"
			:show="submitRequestModalView"
			@close="closeSubmitRequestModal"
			@submit="handleSubmit"
			@reloadShipments="reloadShipments"
			@notificationError="notificationError"
			:submitRequestModalLoading.sync="submitRequestModalLoading"
			:showBookingRequestSubmittedModal.sync="bookingRequestSubmittedModalView"
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
						style="margin-right: 8px;"
						class="save-btn btn-blue"
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
		</submit-request-modal>

		<POCreateDialog
			:dialog.sync="dialogCreatePo"
			:editedIndex.sync="editedOrderIndex"
			:editedItems.sync="editedOrderItems"
			@close="closeCreateOrder"
			:fromComponent="'BookingShipmentDialog'"
			:isMobile="isMobile"
			ref="customModal"
		></POCreateDialog>

		<SalesOrderCreateDialog
			:dialog.sync="dialogCreateSo"
			:editedIndex.sync="editedOrderIndex"
			:editedItems.sync="editedOrderItems"
			@close="closeCreateOrder"
			:fromComponent="'BookingShipmentDialog'"
			:isMobile="isMobile"
			ref="customModal"
		></SalesOrderCreateDialog>

		<ProductAddDialog
			:dialog.sync="dialogAddProduct"
			:editedIndex.sync="editedIndexProduct"
			:defaultItem.sync="defaultProductItem"
			:editedItem.sync="editedProductItem"
			:categoryLists="categoryListData"
			@close="closeProduct"
			:isMobile="isMobile"
			:isInventoryPage="false"
			:isWarehouse3PL="false"
			:isWarehouse3PLProvider="false"
			:productsData="products"
			:isBookingShipmentDialog="true"
			:shipmentData="shipmentData"
		/>
	</div>
</template>
<style lang="scss">
@import "./scss/createShipmentDialog.scss";
@import "./scss/fields.scss";
</style>
<script>
import _ from "lodash";
import jQuery from "jquery";
import moment from "moment";
import KeneticIcon from "../../Icons/KeneticIcon";
import CustomIcon from "../../Icons/CustomIcon";
import GenericIcon from "../../Icons/GenericIcon";
import TextFieldStyle from "./StyleWrappers/TextFieldStyle.vue";
import globalMethods from "../../../utils/globalMethods";
import InputText from "./InputTexts/InputText";
import SelectAutoComplete from "./SelectAutoCompletes/SelectAutoComplete";
import CustomTextArea from "./TextAreas/TextArea";
import GenericTable from "./Tables/GenericTable";
import ContainersTrackingTable from "./Tables/ContainersTrackingTable.vue";
import ContainersTable from "./Tables/ContainersTable";
import SubmitRequestModal from "./Modals/SubmitRequestModal";
import ConfirmRoleModal from "./Modals/ConfirmRoleModal";
import POCreateDialog from "@/components/PosComponents/Dialog/POCreateDialog.vue";
import SalesOrderCreateDialog from "@/components/SalesOrdersComponenets/Dialog/SalesOrderCreateDialog.vue";
import ProductAddDialog from "@/components/ProductComponents/Dialog/ProductAddDialog.vue";

import { mapGetters, mapActions } from "vuex";
import iziToast from "izitoast";

import BookingShipmentDialogMobile from "./BookingShipmentDialogMobile";

//import datas here
import headers from "./Datas/tableHeaders";
import BookingItem from "./Structures/BookingItem";
import SupplierItem from "./Structures/SupplierItem";
import SidebarItems from "./Datas/sidebarItems";
import DocumentTypeItems from "./Datas/documentTypes";
import createOrderItems from "./Structures/createOrderItems";
import createProductItems from "./Structures/createProductItems";

//import BookingRequestSubmittedModal from "./Modals/BookingRequestSubmittedModal.vue";

//reuse components here
import SupplierDialog from "../../../components/SupplierComponents/SupplierDialog.vue";

import RoleErrorStyle from "./StyleWrappers/RoleErrorStyle";
import MarksStyle from "./StyleWrappers/MarksStyle";

export default {
	name: "BookingShipmentDialog",
	props: [
		"reference",
		"show",
		"className",
		"rules",
		"isMobile",
		"windowWidth",
		"addShipmentDialogModalView",
		"item",
		"editedItem",
		"isEdit",
		"submitRequestModalView",
		"bookingShipmentDialogView",
		"bookingRequestSubmittedModalView",
	],
	data: () => ({
		is_notify_party: false,
		userRole: "",
		prevRole: "",
		submitRequestLoading: false,
		submitShipmentId: "",
		selectedOrderIds: [],
		showDialog: true,
		editedIndex: -1,
		submitLoading: false,
		showSupplierDialog: false,
		scrollAnimating: false,
		currentSection: "general",
		showSubmittedRequestDialog: true,
		submitRequestModalLoading: false,
		showBookingRequestSubmittedModal: false,
		showConfirmRoleModal: false,
		connectedShipperOptions: [],
		connectedConsigneeOptions: [],
		consignees: [],
		shippers: [],
		userDetails: {},
		shipmentConsigneeData: [],
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
		purchaseOrderProductItems: [],
		containerItems: [],
		containerNewItems: [],
		headersDocuments: headers.documents.data,
		headersContainers: headers.containers.data,
		showSubmitRequestModal: false,
		headersProducts: headers.shipmentBookingProducts.data,
		headersPurchaseOrders: headers.purchaseOrders.data,
		headersNewContainers: headers.newContainers.data,
		manualSupplierOptions: [],
		purchaseOrderId: 0,
		productId: 0,
		containerId: 0,
		supplierEditItem: SupplierItem,
		defaultSupplierItem: SupplierItem,
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
		modes: ["Ocean", "Truck", "Air"],
		paymentTerms: ["FOB", "EXW", "DAP", "FCA", "DDU", "DDP", "CIF"],
		paymentTermsOthers: ["CNF", "CIF", "DAT"],
		type: "",
		anotherTypes: ["Rail"],
		types: ["FCL", "LTL"],
		sidebarItems: SidebarItems.booking.data,
		searchVendors: "",
		searchCustomers: "",
		manualBtnDisable: false,
		defaultBtnDisable: false,
		shipmentMode: "",
		totalCartons: 0,
		totalVolumes: 0,
		totalWeights: 0,
		shipmentIncoTerm: "",
		ordersSearchText: "",
		searchOrders: true,
		dialogCreatePo: false,
		dialogCreateSo: false,
		editedOrderItems: createOrderItems,
		defaultOrdersItems: createOrderItems,
		editedOrderIndex: -1,
		orderProductsSearchText: "",
		searchProducts: true,
		dialogAddProduct: false,
		editedIndexProduct: -1,
		editedProductItem: createProductItems,
		defaultProductItem: createProductItems,
		categoryListData: [],
		editField: "",
		focusFields: false,
		productLoading: false,
		selectedProductForQuickAdd: null,
		productDescription: "",
		shipmentData: null,
	}),
	components: {
		KeneticIcon,
		CustomIcon,
		GenericIcon,
		GenericTable,
		InputText,
		CustomTextArea,
		ContainersTable,
		SubmitRequestModal,
		SelectAutoComplete,
		BookingShipmentDialogMobile,
		SupplierDialog,
		ConfirmRoleModal,
		ContainersTrackingTable,
		RoleErrorStyle,
		MarksStyle,
		TextFieldStyle,
		POCreateDialog,
		SalesOrderCreateDialog,
		ProductAddDialog,
	},
	async mounted() {
		//set user details
		try {
			this.userDetails = JSON.parse(this.getUser);
		} catch (e) {
			this.userDetails = this.getUser;
		}

		this.$store.dispatch("po/fetchImportName");

		//setup scroll
		this.setupScroll();

		//fetch prefilled consignee and push it connected consignee options
		this.$store.dispatch("booking/getPrefilledConsignee").then((res) => {
			//check one of the key to make sure it's a valid item
			if (typeof res.address !== "undefined")
				this.connectedConsigneeOptions.push(res);
		});

		//fetch prefilled shipper and push it connected shipper options
		this.$store.dispatch("booking/getPrefilledShipper").then((res) => {
			//insert only if tehre are connected shipper options
			//check one of the key to make sure it's a valid item
			if (typeof res.address !== "undefined")
				this.connectedShipperOptions.push(res);
		});

		//fetch consignee options
		this.$store.dispatch("booking/getConsigneeOptions", {
			page: 1,
		});

		//fetch shipper options
		this.$store.dispatch("booking/getShipperOptions", {
			page: 1,
		});

		//fetch container sizes
		await this.$store.dispatch("booking/fetchContainerSizes").then(() => {
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

		// fetch incoterms
		await this.$store.dispatch("fetchTerms");

		if (this.reference !== "formBookingShipment") {
			if (this.item.id) {
				await this.fetchShipmentDetailsInfo(this.item.id);
			}

			this.editItem = this.getShipmentDetails; //this.item
			this.editItem.eta = moment().format("MM-DD-YYYY");
			this.editItem.etd = moment().format("MM-DD-YYYY");
			this.editItem.booking_num = this.editItem.booking_number;

			//check schedules
			let schedules = this.editItem.schedules_group_bookings;
			schedules = Array.isArray(schedules) ? schedules : JSON.parse(schedules);
			let findScheduleIndex = -1;
			this.editItem.location_from = "";
			this.editItem.location_to = "";
			this.editItem.terminal = this.editItem.terminal_id;

			//check containers
			let containers = this.editItem.containers_group;

			containers = Array.isArray(containers)
				? containers
				: JSON.parse(containers);

			this.containerItems = containers === null ? [] : containers;

			this.containerItems.map((ci, key) => {
				this.containerItems[key].size = parseInt(ci.size);
			});

			const containerNewItemsUpdate = [];

			this.containerNewItems.map((gcs) => {
				const container = this.containerItems.find(
					(row) => row.size == gcs.size_id
				);
				let cartoons = 0;
				let isChecked = false;
				if (container) {
					cartoons = container.cartons;
					isChecked = container.cartons == 0 ? false : true;
				}

				containerNewItemsUpdate.push({
					size: gcs.size,
					size_id: gcs.size_id,
					checked: isChecked,
					how_many: cartoons,
					plus: "+",
					minus: "-",
					ukey: 0,
				});
			});
			this.containerNewItems = containerNewItemsUpdate;

			if (schedules.length > 0) {
				this.editItem.eta =
					schedules[0].eta ?? moment(schedules[0].eta).format("MM-DD-YYYY");
				this.editItem.etd =
					schedules[0].etd ?? moment(schedules[0].etd).format("MM-DD-YYYY");
				this.editItem.etaDate =
					schedules[0].eta ?? moment(schedules[0].eta).format("YYYY-MM-DD");
				this.editItem.etdDate =
					schedules[0].etd ?? moment(schedules[0].etd).format("YYYY-MM-DD");

				this.editItem.location_from = schedules[0].location_from;
				this.editItem.location_to = schedules[0].location_to;

				findScheduleIndex = 0;
				//findScheduleIndex = _.findIndex(schedules, { is_confirmed: 1})
				if (findScheduleIndex !== -1) {
					this.editItem.mode =
						schedules[findScheduleIndex].mode !== null &&
						schedules[findScheduleIndex].mode !== ""
							? this.ucFirst(schedules[findScheduleIndex].mode.toLowerCase())
							: "";
					this.shipmentMode = this.editItem.mode;
					this.editItem.type = schedules[findScheduleIndex].type;

					if (
						typeof schedules[findScheduleIndex].carrier_name !== "undefined" &&
						schedules[findScheduleIndex].carrier_name !== null &&
						schedules[findScheduleIndex].carrier_name !== "" &&
						typeof schedules[findScheduleIndex].carrier_name.id !== "undefined"
					) {
						this.editItem.carrier_id = parseInt(
							schedules[findScheduleIndex].carrier_name.id
						);
					}
				}
			}
			this.editItem.mbl_num =
				typeof this.editItem.mbl_num !== "undefined" &&
				this.editItem.mbl_num !== null &&
				this.editItem.mbl_num !== ""
					? this.editItem.mbl_num
					: "";
			this.editItem.vessel =
				typeof this.editItem.vessel !== "undefined" &&
				this.editItem.vessel !== null &&
				this.editItem.vessel !== ""
					? this.editItem.vessel
					: "";

			this.editItem.voyage_number =
				typeof this.editItem.voyage_number !== "undefined" &&
				this.editItem.voyage_number !== null &&
				this.editItem.voyage_number !== ""
					? this.editItem.voyage_number
					: "";

			//get purchase orders if there are purchase orders
			let purchase_orders =
				this.getShipmentDetails !== null &&
				typeof this.getShipmentDetails.purchase_orders !== "undefined" &&
				typeof this.getShipmentDetails.purchase_orders.items !== "undefined"
					? this.getShipmentDetails.purchase_orders.items
					: [];

			if (purchase_orders.length > 0) {
				purchase_orders.map((po) => {
					let { products, id, product_options, ...others } = po;

					const updatedProducts = products.map(
						({ carton, units_per_carton, volume, product, ...rest }) => {
							let volumes;
							if (volume !== null) {
								volumes = this.toFixed(volume);
								//* carton * units_per_carton;
								volumes =
									this.countShipmentsDecimals(volumes) > 5
										? volumes.toFixed(5)
										: volumes;
								volumes = volumes == 0 ? 0.00001 : volumes;
							} else {
								volumes = 0.00001;
							}

							return {
								product_id: product.id,
								unit: carton * units_per_carton,
								carton: carton,
								volume: volumes,
								classification_code: product.classification_code,
								product_classification_description:
									product.product_classification_description,
								description: product.description,
								isEditingField: false,
								quickAddProduct: false,
								...rest,
							};
						}
					);

					this.purchaseOrderItems.push({
						products: updatedProducts,
						purchase_order_id: id,
						product_options,
						id,
						layout: "default",
						selectAll: false,
						quickAddOrder: false,
						...others,
					});
				});
			}
			let supplierInfo =
				this.getShipmentDetails.add_manual_po_so_data == "[]" ||
				this.getShipmentDetails.add_manual_po_so_data == null ||
				this.getShipmentDetails.add_manual_po_so_data == ""
					? this.getShipmentDetails?.suppliers_group_bookings
					: this.getShipmentDetails?.add_manual_po_so_data;

			supplierInfo = Array.isArray(supplierInfo)
				? supplierInfo
				: JSON.parse(supplierInfo);

			supplierInfo.map((supplierInfoRow) => {
				if (supplierInfoRow.po_num != "" && supplierInfoRow.po_num != null) {
					this.purchaseOrderItems.push({
						id: supplierInfoRow.id,
						products: [],
						supplier_id: 0,
						documents: [],
						cargo_ready_date: supplierInfoRow.cargo_ready_date,
						menuCargoReadyDate: false,
						selectAll: false,
						containers: [],
						purchase_order_number: supplierInfoRow.po_num,
						total_cartons: supplierInfoRow.total_cartons,
						total_volumes: supplierInfoRow.volume,
						total_weights: supplierInfoRow.kg,
						layout: "manual",
					});
				}
			});

			let [supplierInfoData] = supplierInfo;
			let {
				incoterm_id,
				product_description,
				kg,
				cbm,
				total_cartons,
			} = supplierInfoData;

			let inco_terms = this.$store.getters["getShipmentTerms"];
			let incotermData = inco_terms.find((item) => {
				return item.id === incoterm_id;
			});

			let shipment_metas = _.get(this.getShipmentDetails, "shipment_metas", {});
			let shipper_id = _.get(shipment_metas, "shipper_id", 0);
			let consignee_id = _.get(shipment_metas, "consignee_id", 0);
			let notify_details_info = _.get(
				shipment_metas,
				"notify_details_info",
				""
			);
			let notify_party = _.get(shipment_metas, "notify_party", false);
			let marks = _.get(shipment_metas, "marks", "");

			this.is_notify_party =
				notify_party == 0 || notify_party == false ? false : true;
			this.editItem.notify_details_info =
				notify_details_info && notify_details_info != "null"
					? notify_details_info
					: "";
			this.editItem.special_instructions =
				this.getShipmentDetails?.custom_notes ?? "";
			this.editItem.marks = marks ?? "";
			this.shipmentIncoTerm =
				incotermData.name === "Exworks" ? "EXW" : incotermData.name;
			this.editItem.shipper = shipper_id;
			this.editItem.consignee = consignee_id;
			let getRole = this.roles.find((role) => {
				return role == shipment_metas.role;
			});
			this.editItem.customer_reference_number =
				this.getShipmentDetails?.customer_reference_number ?? "";

			this.editItem.commodities_info = product_description ?? "";
			this.totalCartons = total_cartons ?? "";
			this.totalVolumes = cbm ?? "";
			this.totalWeights = kg ?? "";

			this.userRole = getRole;
			this.selectRole(getRole, "edit");

			// fetch products

			if (this.editItem.role === "shipper") {
				if (this.editItem.consignee !== "") {
					this.$store.dispatch(
						"salesOrders/fetchSuppliersSku",
						this.editItem.consignee
					);
				}
			} else {
				if (this.editItem.shipper !== "") {
					this.$store.dispatch("po/fetchVendorSku", this.editItem.shipper);
				}
			}
		} else {
			this.editItem.mbl_num = "";
			this.editItem.vessel = "";
			this.editItem.voyage_number = "";
			this.shipmentMode = "";
			this.editItem.type = "";
			this.editItem.carrier_id = "";
			this.editItem.location_from = "";
			this.editItem.location_to = "";
			this.editItem.carrier_id = "";
			this.editItem.booking_num = "";
		}

		//load terminals
		this.fetchTerminals({ is_paginate: 0 })
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

		//load carriers
		this.fetchCarriers()
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

		//load terminal regions
		this.fetchTerminalRegions({ is_paginate: 0 })
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

		//load container sizes
		this.fetchContainers()
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
		if (this.reference !== "formBookingShipment") {
			//fetch purchase orders options
			// this.fetchPurchaseOrderOptions();

			this.checkOrdersButton();
		} else {
			this.loaded = true;
		}
	},
	async beforeMount() {
		await this.fetchSuppliers().then(() => {
			this.resourcesLoaded++;
			if (this.resourcesLoaded === this.resourcesLimit) {
				this.loaded = true;
				if (typeof this.getSuppliers !== "undefined") {
					this.editItem.poManual.suppliersOptions = this.getSuppliers.data;
					this.manualSupplierOptions = this.getSuppliers.data;
				}
			}
		});

		let params = {
			itemsPerPage: this.customerPagination.per_page,
			pageNumber: this.customerPagination.current_page,
		};

		await this.$store.dispatch("customers/fetchCustomers", params).then(() => {
			this.shipmentConsigneeData = this.$store.getters[
				"customers/getCustomers"
			];
		});
	},
	computed: {
		...mapGetters([
			"getTerminalRegions",
			"getCreateShipmentLoading",
			"getShipmentContainerSizes",
			"getUser",
		]),
		suppliers() {
			let data = [];

			if (
				typeof this.getSearchedSuppliers !== "undefined" &&
				this.getSearchedSuppliers !== null
			) {
				if (
					this.searchVendors !== "" &&
					this.getSearchedSuppliers.tab === "vendors"
				) {
					if (
						typeof this.getSearchedSuppliers.data !== "undefined" &&
						this.getSearchedSuppliers.data.length !== "undefined"
					) {
						data = this.getSearchedSuppliers.data;
					}
				} else {
					if (
						typeof this.getSuppliers !== "undefined" &&
						this.getSuppliers !== null
					) {
						if (
							typeof this.getSuppliers.data !== "undefined" &&
							this.getSuppliers.data.length !== "undefined"
						) {
							data = this.getSuppliers.data;
						}
					}
				}
			} else {
				if (
					typeof this.getSuppliers !== "undefined" &&
					this.getSuppliers !== null
				) {
					if (
						typeof this.getSuppliers.data !== "undefined" &&
						this.getSuppliers.data.length !== "undefined"
					) {
						data = this.getSuppliers.data;
					}
				}
			}

			if (data.length > 0) {
				data.map((value) => {
					if (value.address === null) {
						value.address = "";
					}

					if (value.phone === null) {
						value.phone = "";
					}

					if (value.emails === null) {
						value.emails = "";
					}
				});
			}

			return data;
		},
		customers() {
			let customerData = [];

			if (
				typeof this.getSearchedCustomer !== "undefined" &&
				this.getSearchedCustomer !== null
			) {
				if (
					this.searchCustomers !== "" &&
					this.getSearchedCustomer.tab === "customers"
				) {
					if (
						typeof this.getSearchedCustomer.data !== "undefined" &&
						this.getSearchedCustomer.data.length !== "undefined"
					) {
						customerData = this.getSearchedCustomer.data;
					}
				} else {
					if (
						typeof this.getCustomers !== "undefined" &&
						this.getCustomers !== null
					) {
						if (
							typeof this.getCustomers.data !== "undefined" &&
							this.getCustomers.data.length !== "undefined"
						) {
							customerData = this.getCustomers.data;
						}
					}
				}
			} else {
				if (
					typeof this.getCustomers !== "undefined" &&
					this.getCustomers !== null
				) {
					if (
						typeof this.getCustomers.data !== "undefined" &&
						this.getCustomers.data.length !== "undefined"
					) {
						customerData = this.getCustomers.data;
					}
				}
			}

			if (customerData.length > 0) {
				customerData.map((value) => {
					value.name = value.company_name;

					if (value.address !== null) {
						value.address = value.address.replace(/,(?=[^\s])/g, ", ");
					} else {
						value.address = "";
					}

					if (value.phone == null) {
						value.phone = "";
					}

					if (value.emails == null) {
						value.emails = "";
					}
				});
			}

			return customerData;
		},
		filteredTypes() {
			//map types
			let types = [
				{
					label: "Truck",
					options: ["FTL", "LTL"],
				},
				{
					label: "Air",
					options: [],
				},
				{
					label: "Ocean",
					options: ["FCL", "LCL"],
				},
			];

			let setMode = this.shipmentMode;
			if (this.shipmentMode === "" || this.shipmentMode == "N/A")
				setMode = "Ocean";

			//return options
			let getOptions = _.find(types, (e) => e.label === setMode);
			return getOptions.options;
		},
		filteredShipperOptions() {
			//shippers array container
			let newShippers = [];

			//modify it if the selected role is shipper, use the connected shipper options
			if (this.editItem.role === "shipper") {
				newShippers = [this.userDetails.default_customer];
				//newShippers = this.connectedShipperOptions
			} else {
				newShippers = this.$store.getters["booking/getShippers"];
			}

			//leaves only company name and address
			if (
				newShippers.length > 0 &&
				typeof newShippers[0].special === "undefined"
			) {
				newShippers = newShippers.map(({ company_name, address, id }) => {
					return {
						name: company_name,
						id,
						address,
						special: false,
					};
				});
			}

			//push to the array bottom
			newShippers.push({
				name: "Add Shipper",
				special: true,
				id: "",
				address: "",
			});

			return newShippers;
		},
		filteredConsigneeOptions() {
			//consignees array container
			let newConsignees = [];

			//modify it if the selected role is consignee, use the connected consignee options
			if (this.editItem.role === "consignee") {
				//newConsignees = this.connectedConsigneeOptions
				newConsignees = [this.userDetails.default_customer];
			} else {
				newConsignees = this.$store.getters["booking/getConsignees"];
			}

			//leaves only company name and address
			if (
				newConsignees.length > 0 &&
				typeof newConsignees[0].special == "undefined"
			) {
				newConsignees = newConsignees.map(({ company_name, address, id }) => {
					return {
						name: company_name,
						id,
						address,
						special: false,
					};
				});
			}
			//push to the array bottom
			newConsignees.push({
				name: "Add Consignee",
				special: true,
				id: "",
				address: "",
			});

			return newConsignees;
		},
		consigneeDetails() {
			let newConsignees = [this.userDetails.default_customer];

			newConsignees = newConsignees.map(
				({ company_name, address, emails, phone }) => {
					let email = emails
						.map((item) => {
							return item.email;
						})
						.join(", ");

					return {
						company_name,
						address,
						email,
						phone,
					};
				}
			);

			return newConsignees;
		},
		filteredImportNameOptions() {
			let importName = [];

			importName = this.$store.getters["po/getImportName"];

			importName =
				importName &&
				importName.map(({ address, id, import_name }) => {
					return {
						name: import_name,
						id,
						importAddress: address,
					};
				});

			return importName;
		},
		getShipmentDetails() {
			return this.$store.getters["booking/getShipmentDetails"];
		},
		getEditingShipment() {
			return this.$store.getters["page/getEditingShipment"];
		},
		getEditingDraftShipment() {
			return this.$store.getters["page/getEditingDraftShipment"];
		},
		getMarkingBookedExternal() {
			return this.$store.getters["page/getMarkingBookedExternal"];
		},
		getSearchedSuppliers() {
			return this.$store.getters["suppliers/getSearchedSuppliers"];
		},
		getCustomers() {
			return this.$store.getters["customers/getCustomers"];
		},
		getSearchedCustomer() {
			return this.$store.getters["customers/getSearchedCustomer"];
		},
		getContainerSizes() {
			return this.$store.getters["booking/getContainerSizes"];
		},
		getSuppliers() {
			return this.$store.getters["suppliers/getSuppliers"];
		},
		purchaseOrderOptions() {
			let options = this.$store.getters["po/getPurchaseOrderOptions"];
			options = _.filter(
				options,
				(e) => e.status === "pending" || e.status === "partial_shipped"
			);
			return options;
		},
		filteredContainerSizes() {
			let newContainerSizes = [];
			if (this.loaded) {
				this.getShipmentContainerSizes.map((gcs) => {
					newContainerSizes.push({
						text: gcs.name,
						value: gcs.id,
					});
				});
			}
			return newContainerSizes;
		},
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
		getVendorsProducts() {
			return this.$store.getters["po/getVendorSkus"];
		},
		getBuyersProducts() {
			return this.$store.getters["salesOrders/getSuppliersSkus"];
		},
	},
	methods: {
		...mapActions([
			"fetchCarriers",
			"fetchTerminalRegions",
			"createShipment",
			"fetchContainers",
			"fetchShipmentDetails",
			"editShipment",
			"fetchTerminals",
			"fetchDraftShipments",
			"fetchPendingShipments",
			"fetchPendingQuoteShipments",
		]),
		...globalMethods,
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
		async handleLoadShipmentTabsData() {
			await this.fetchDraftShipments(1);
			await this.fetchPendingShipments(1);
			await this.fetchPendingQuoteShipments(1);
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
		async fetchShipmentDetailsInfo(id) {
			await this.$store.dispatch("booking/fetchShipmentDetails", id);
		},
		closeConfirmRoleModal() {
			this.showConfirmRoleModal = false;
		},
		updateRoleValues(role, type = "") {
			if (role === "shipper") {
				if (type != "edit") {
					this.editItem.shipper = this.userDetails.default_customer.id;
					this.editItem.shipper_details_info = this.userDetails.default_customer.address;
					this.editItem.consignee_details_info = "";
					this.editItem.consignee = "";
				} else {
					const selectedCustomers = this.shipmentConsigneeData.data.find(
						(customer) => customer.id == this.editItem.consignee
					);
					this.editItem.shipper = this.userDetails.default_customer.id;
					this.editItem.shipper_details_info = this.userDetails.default_customer.address;
					this.editItem.consignee_details_info = selectedCustomers.address;
					this.editItem.consignee = selectedCustomers.id;
				}

				//fetch so instead po
				//but make sure the value is not equal to ''
				if (this.editItem.shipper !== "") {
					this.fetchPurchaseOrderOptions(
						"",
						"SO",
						this.editItem.shipper,
						this.editItem.consignee
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
				}
			} else {
				if (type != "edit") {
					this.editItem.consignee = this.userDetails.default_customer.id;
					this.editItem.consignee_details_info = this.userDetails.default_customer.address;
					this.editItem.shipper = "";
					this.editItem.shipper_details_info = "";
				} else {
					if (
						typeof this.getSuppliers !== "undefined" &&
						this.getSuppliers !== null
					) {
						const selectedSupplier = this.getSuppliers.data.find(
							(supplier) => supplier.id == this.editItem.shipper
						);
						this.editItem.consignee = this.userDetails.default_customer.id;
						this.editItem.consignee_details_info = this.userDetails.default_customer.address;
						this.editItem.shipper = selectedSupplier?.id;
						this.editItem.shipper_details_info = selectedSupplier?.address;
					}
				}

				//fetch po instead so
				//but make sure the value is not equal to ''
				if (this.editItem.consignee !== "")
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
			}
		},
		showSupplierModal(isSupplier) {
			this.supplierEditItem.isSupplier = isSupplier;
			this.showSupplierDialog = true;
			this.editItem.isSupplier = isSupplier;
		},
		suppliersPagination() {
			if (this.searchVendors === "") {
				return this.getSuppliers;
			} else {
				return this.getSearchedSuppliers;
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
		customerPagination() {
			if (this.searchCustomers === "") {
				return this.getCustomers;
			} else {
				return this.getSearchedCustomer;
			}
		},
		setToDefaultSupplierItem() {
			this.supplierEditItem = this.defaultSupplierItem;
			this.close();
			this.showSupplierDialog = true;
		},
		saveAsDraft() {
			//add loading
			this.submitLoading = true;
			//add is draft parameters
			this.handleSubmit({
				forwarders: [],
				is_review: false,
				is_draft: 1,
			});
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
		closeSubmitRequestModal() {
			this.$emit("update:submitRequestModalView", false);
		},
		cancelRole() {
			this.userRole = this.prevRole;
			this.showConfirmRoleModal = false;
		},
		selectRole(role, type = "") {
			//additional check for purchase order id
			//let checkPurchaseOrderid = _.find(this.purchaseOrderItems, e => e.purchase_order_id != '')

			//show only confirm dialog if it has valid purchase order items
			if (
				this.prevRole !== "" &&
				this.prevRole !== role &&
				this.purchaseOrderItems.length > 0
			) {
				this.showConfirmRoleModal = true;
			} else {
				//always empty when the previous role is different
				if (this.prevRole !== "" && this.prevRole !== role) {
					this.purchaseOrderItems = [];
				}
				this.prevRole = role;

				//update role values
				this.updateRoleValues(role, type);
			}
		},
		updateCargoReadyDateInput(pom, value) {
			if (moment(value).isValid())
				pom.cargo_ready_date = moment(value).format("YYYY-MM-DD");
			else pom.cargo_ready_date = "";
			pom.menuCargoReadyDate = false;
		},
		fetchSuppliers() {
			return new Promise((resolve) => {
				this.$store
					.dispatch("suppliers/fetchSuppliers", {
						pageNumber: 1,
					})
					.then(() => {
						resolve("ok");
					});
			});
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
		updateShipment() {
			if (this.$refs["formEditShipment"].validate()) {
				let payload = this.editItem;
				payload.purchase_orders = this.purchaseOrderItems;
				payload.containers = this.containerItems;
				let supplier_timestamps = [];
				if (payload.purchase_orders.length > 0) {
					payload.purchase_orders.map(() => {
						supplier_timestamps.push(new Date().getTime());
					});
				}

				payload.date_id = new Date().getTime();
				payload.supplier_timestamps = supplier_timestamps;

				this.editShipment(payload)
					.then(() => {
						this.notificationSuccess("Shipment was edited successfully.");
						this.fetchShipmentDetails();
						setTimeout(() => {
							window.location.reload();
							this.close();
						}, 4000);
					})
					.catch((e) => {
						console.log(e);
					});
			}
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
			} else return require("../../../assets/icons/default-product-icon.svg");
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
		addShipmentSuccess() {
			this.$emit("update:addShipmentDialogModalView", true);
		},
		notificationSuccess(message) {
			iziToast.success({
				theme: "dark",
				message: `<h4 style="font-weight: 500 !important; color: #fff !important;">${message}</h4>`,
				backgroundColor: "#16B442",
				messageColor: "#ffffff",
				iconColor: "#ffffff",
				progressBarColor: "#ADEEBD",
				displayMode: 1,
				position: "bottomCenter",
				timeout: 3000,
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
		reloadShipments() {
			this.$emit("reloadShipments");
		},
		handleConfirmRole() {
			this.prevRole = this.editItem.role;
			this.showConfirmRoleModal = false;
			//empty purchase order items
			this.purchaseOrderItems = [];

			//update role values
			this.updateRoleValues(this.editItem.role);
		},
		handleSubmit({ forwarders, is_review, is_draft }) {
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
					role: this.editItem.role,
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
					is_draft: typeof is_draft !== "undefined" ? 1 : 0,
					import_name_id: this.editItem.import_name_id,
					total_weight: this.totalWeights,
					total_volume: this.totalVolumes,
					total_cartons: this.totalCartons,
				};
				if (this.getEditingDraftShipment) payload.shipment_id = this.item.id;

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
						}

						if (payload.is_draft === 1) {
							//for draft save button
							this.submitLoading = false;

							//sends message to the user
							this.notificationSuccess("Shipment successfully saved as draft.");

							//reload shipments api
							this.reloadShipments();

							//close booking form
							this.$emit("close");
							if (this.getEditingDraftShipment)
								this.$store.dispatch("page/setEditingDraftShipment", false);

							this.resetData();

							this.handleLoadShipmentTabsData();

							if (typeof this.$refs[this.reference] !== "undefined") {
								this.$refs[this.reference].reset();
							}
						} else {
							this.resetData();

							if (typeof this.$refs[this.reference] !== "undefined") {
								this.$refs[this.reference].reset();
							}
							this.submitRequestModalLoading = false;
							this.$emit("update:bookingRequestSubmittedModalView", true);
							if (this.getEditingDraftShipment)
								this.$store.dispatch("page/setEditingDraftShipment", false);
							//reload shipments api
							this.reloadShipments();
							this.handleLoadShipmentTabsData();
							//this.closeSubmitRequestModal()
							//this.showBookingRequestSubmittedModal = true
							//this.showSubmittedRequestDialog = false
						}
					})
					.catch((e) => {
						this.submitLoading = false;
						this.submitRequestLoading = false;
						this.submitRequestModalLoading = false;
						this.notificationError(
							"An unexpected error occured. Please try again"
						);
						console.log(e);
					});
			} else {
				this.notificationErrorMessage();
				this.$emit("update:submitRequestModalView", false);
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
			//this.showSubmitRequestModal = true
			let { reference } = this;

			if (this.$refs[reference].validate()) {
				this.$emit("update:submitRequestModalView", true);
			} else {
				this.selectPage({
					icon: "general",
					scrolling: true,
				});
				this.notificationErrorMessage();
			}

			//this.showDialog = false
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
		createOrder() {
			if (this.editItem.role === "shipper") {
				this.$store.dispatch("salesOrders/fetchBuyerLists");
				this.$store.dispatch(
					"salesOrders/fetchSuppliersSku",
					this.editItem.consignee
				);

				this.dialogCreateSo = true;
				this.editedOrderItems.buyer_id = this.editItem.consignee;
				this.editedOrderItems.entity_id = this.editItem.shipper;
			} else {
				this.$store.dispatch("po/fetchVendorLists");
				this.$store.dispatch("po/fetchVendorSku", this.editItem.shipper);

				this.dialogCreatePo = true;
				this.editedOrderItems.supplier_id = this.editItem.shipper;
				this.editedOrderItems.entity_id = this.editItem.consignee;
			}

			(this.editedOrderItems.products = [
				{
					id: null,
					carton_count: 0,
					units: 0,
					unit_price: 0,
					amount: 0,
					units_per_carton: 0,
					unship_cartons: 0,
					shipped_cartons: 0,
					shipped_units: 0,
					unshipped_units: 0,
					volume: 0,
					weight: 0,
				},
			]),
				(this.editedOrderIndex = -1);
		},
		closeCreateOrder() {
			if (this.editItem.role === "shipper") {
				this.dialogCreateSo = false;
				this.editedOrderItems.buyer_id = 0;
			} else {
				this.dialogCreatePo = false;
				this.editedOrderItems.supplier_id = 0;
			}
			let newOptions = this.setPurchaseOrderOptions();
			this.purchaseOrderItems.map(
				(item) => (item.purchase_order_options = newOptions)
			);

			this.$nextTick(() => {
				this.editedOrderItems = Object.assign({}, this.defaultOrdersItems);
				this.editedOrderIndex = -1;
			});
		},
		quickAddOrders(key) {
			this.purchaseOrderItems[key].quickAddOrder = true;
			this.purchaseOrderItems[key].order_number = this.ordersSearchText;
			if (this.editItem.role === "shipper") {
				this.$store.dispatch(
					"salesOrders/fetchSuppliersSku",
					this.editItem.consignee
				);
			} else {
				this.$store.dispatch("po/fetchVendorSku", this.editItem.shipper);
			}
			this.addProductToPurchaseOrders(key);
			this.ordersSearchText = "";
		},
		async createProduct() {
			this.$store.dispatch("products/fetchProductContacts");
			await this.$store.dispatch("category/fetchCategoriesDropdown", 1);
			this.loadMoreCategories();
			this.shipmentData = {
				role: this.editItem.role,
				consignee: this.editItem.consignee,
				shipper: this.editItem.shipper,
			};
			this.dialogAddProduct = true;
		},
		quickAddProducts(key, productKey) {
			this.purchaseOrderItems[key].products[productKey].quickAddProduct = true;
			this.purchaseOrderItems[key].products[productKey].description = "";
			this.purchaseOrderItems[key].products[productKey].carton = 0;
			this.purchaseOrderItems[key].products[productKey].unit = 0;
			this.purchaseOrderItems[key].products[productKey].volume = 0;
			this.purchaseOrderItems[key].products[productKey].weight = 0;
			this.purchaseOrderItems[key].products[productKey].unit_price = 0;
			this.purchaseOrderItems[key].products[productKey].classification_code =
				"";
			this.purchaseOrderItems[key].products[
				productKey
			].product_classification_description = "";
			this.purchaseOrderItems[key].products[productKey].product_id = "";

			this.validateProduct(
				this.purchaseOrderItems[key].products[productKey],
				key
			);
			this.purchaseOrderItems[key].products[
				productKey
			].product_number = this.orderProductsSearchText;
			this.orderProductsSearchText = "";
			this.productDescription = "";
		},
		selectOpenedProductBox(item) {
			let key = parseInt(item.index) - 1;
			this.selectedProductForQuickAdd = key;
		},
		closeProduct() {
			this.$nextTick(() => {
				this.editedProductItem = Object.assign({}, this.defaultProductItem);
				this.editedIndexProduct = -1;
			});
			this.dialogAddProduct = false;
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
		async loadMoreCategories() {
			let getCategoriesDropdown = this.$store.getters[
				"category/getCategoriesDropdown"
			];
			try {
				if (
					typeof getCategoriesDropdown !== "undefined" &&
					getCategoriesDropdown !== null &&
					typeof getCategoriesDropdown.categories !== "undefined" &&
					Array.isArray(getCategoriesDropdown.categories) &&
					getCategoriesDropdown.categories.length > 0
				) {
					let newloaddata = getCategoriesDropdown.categories.map((value) => {
						let nameWithId = value.name + " (" + value.id + ")";

						return {
							id: value.id,
							name: value.name,
							nameWithId,
						};
					});

					this.categoryListData = [...this.categoryListData, ...newloaddata];

					this.$store.dispatch(
						"category/setAllCategoriesDropdown",
						this.categoryListData
					);
				} else {
					this.categoryListData;
					this.$store.dispatch(
						"category/setAllCategoriesDropdown",
						this.categoryListData
					);
				}
			} catch (e) {
				this.notificationError(e);
			}
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
				// Note: `newValue` will be equal to `oldValue` here
				// on nested mutations as long as the object itself
				// hasn't been replaced.
			},
			deep: true,
		},
	},
};
</script>
