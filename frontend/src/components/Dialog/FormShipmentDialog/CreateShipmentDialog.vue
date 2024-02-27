<!-- @format -->

<template>
	<div style="position: relative;">
		<create-shipment-dialog-mobile
			v-if="isMobile"
			:isMobile="isMobile"
			:show="show"
			@addShipmentSuccess="addShipmentSuccess"
			:loaded="loaded"
			:cartonRules="cartonRules"
			:sidebarItems="sidebarItems"
			:containerItems="containerItems"
			:filteredContainerSizes="filteredContainerSizes"
			@getImgUrl="getImgUrl"
			@removeContainer="removeContainer"
			@notificationSuccess="notificationSuccess"
			@reloadShipments="reloadShipments"
			@selectAllProducts="selectAllProducts"
			@calculateAmount="calculateAmount"
			@addAllCartons="addAllCartons"
			@updateUnit="updateUnit"
			@addContainer="addContainer"
			:headersContainers="headersContainers"
			@removeProductFromPurchaseOrders="removeProductFromPurchaseOrders"
			@updateCarton="updateCarton"
			@addProductToPurchaseOrders="addProductToPurchaseOrders"
			@updateProducts="updateProducts"
			@removePurchaseOrderItem="removePurchaseOrderItem"
			@addPurchaseOrderItem="addPurchaseOrderItem"
			@addManualOrderItem="addManualOrderItem"
			@selectPage="selectPage"
			:headersProducts="headersProducts"
			@updateProductPurchaseOrder="updateProductPurchaseOrder"
			@validateProduct="validateProduct"
			@customFilterProduct="customFilterProduct"
			@getWarehouseName="getWarehouseName"
			@getPoStatus="getPoStatus"
			@currencyNumberFormat="currencyNumberFormat"
			@formatDate="formatDate"
			@close="close"
			:filteredTerminals="filteredTerminals"
			:filteredTerminalRegions="filteredTerminalRegions"
			:rules="rules"
			:windowWidth="windowWidth"
			:valid="valid"
			:customFilterPo="customFilterPo"
			:purchaseOrderOptions="purchaseOrderOptions"
			:menuEtd="menuEtd"
			:modes="modes"
			:menuEta="menuEta"
			:menuProps="menuProps"
			:purchaseOrderItems="purchaseOrderItems"
			:types="types"
			:editItem="editItem"
			:filteredCarriers="filteredCarriers"
		/>
		<v-dialog
			v-if="!isMobile"
			@click:outside="clickOutside"
			v-model="show"
			:max-width="`${getMarkingBookedExternal ? '772px' : '1376px'}`"
			:content-class="`${className} create-shipment-ultimate-wrapper`"
		>
			<v-card id="edit-shipment-dialog-id" class="edit-shipment-dialog">
				<v-card-title class="headline">
					<slot name="title"></slot>
					<!-- <div class="d-flex shipment-dialog-header" v-if="!getMarkingBookedExternal">
						<v-btn class="btn-white shipment-dialog-header-btn-left" :class="multiShipmentForm ? '' : 'active-btn'" @click="addSingleShipment()">
							<span>Add Single Shipment</span>
						</v-btn>
						<v-btn class="btn-white shipment-dialog-header-btn-right" :class="multiShipmentForm ? 'active-btn' : ''" @click="addMultiShipment()">
							<span>Add In Bulk</span>
						</v-btn>
					</div> -->
					<button icon dark class="btn-close" @click.stop="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>
				<v-card-text class="pb-0" v-if="!multiShipmentForm">
					<div
						:style="
							`${getMarkingBookedExternal ? 'min-height: 0px !important;' : ''}`
						"
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
							v-if="!loaded && !getMarkingBookedExternal"
						>
							<v-progress-circular :size="40" color="#0171a1" indeterminate>
							</v-progress-circular>
						</div>
						<div
							v-if="(loaded && !specialLoading) || getMarkingBookedExternal"
							class="d-flex flex-column edit-shipment-dialog-main-content second-column add-shipment-tracking"
							:style="`${getMarkingBookedExternal ? 'width: 100%;' : ''}`"
						>
							<v-form
								v-if="loaded || getMarkingBookedExternal"
								class=""
								:ref="reference"
								action="#"
								@submit.prevent=""
							>
								<div v-if="getMarkingBookedExternal" class="d-flex flex-row">
									<p class="mark-book-external-quotation">
										{{
											"Please provide at least one of the following information to mark the shipment as 'Booked External'"
										}}
									</p>
								</div>
								<div class="d-flex flex-row">
									<div
										id="second-column-id-create"
										v-if="
											getEditingShipment ||
												getMarkingBookedExternal ||
												getAddingShipmentTracking
										"
										class="form-edit-shipment-form-first-column form-wrapper"
										:style="
											`${getMarkingBookedExternal ? 'padding-right: 24px' : ''}`
										"
									>
										<div
											v-if="getAddingShipmentTracking"
											style="margin-bottom: 16px !important;"
											id="generalInformationSection"
											ref="generalInformationSection"
											class="d-flex flex-row justify-space-between"
										>
											<div
												v-if="loaded && !specialLoading"
												class="content-title"
											>
												{{ "General Information" }}
											</div>
										</div>
										<div class="form-label required">
											<span>{{ "MBL #" }}</span>
											<span v-if="1 == 2">{{ " (Required)" }}</span>
										</div>
										<text-field-style
											:id="
												`${
													getMarkingBookedExternal
														? 'mbl-wrapper-external'
														: 'mbl-wrapper'
												}`
											"
										>
											<template v-slot:content>
												<div
													:class="
														`d-flex flex-row ${
															getMarkingBookedExternal ? '' : ''
														}`
													"
												>
													<div
														:style="
															`${
																getAddingShipmentTracking
																	? 'margin-bottom: 12px !important;'
																	: 'margin-bottom: 24px !important;'
															} width: ${
																getMarkingBookedExternal ? '100%;' : '73%;'
															}`
														"
														id="input-text-1`"
														class="text-field-wrapper input-text-wrapper"
													>
														<v-text-field
															:height="40"
															color="#002F44"
															width="200px"
															v-model="editItem.mbl_num"
															type="text"
															dense
															:class="`${mblTrackFail ? 'mbl-fail' : ''}`"
															placeholder="Enter MBL number"
															outlined
															hide-details="auto"
														>
														</v-text-field>
													</div>

													<button
														style="max-height: 40px !important; width: 27%; border-left: 0px solid transparent;"
														v-if="!getMarkingBookedExternal"
														@click.stop="trackShipmentInfo"
														:class="
															`track-shipment-info-side ${
																editItem.mbl_num != ''
																	? 'track-shipment-info-has'
																	: ''
															} ${checkTrackingNumber ? ' tracking' : ''} ${
																mblTracked ? ' tracked' : ''
															}`
														"
													>
														<svg
															style="margin-right: 4px;"
															v-if="mblTracked"
															width="12"
															height="12"
															viewBox="0 0 12 12"
															fill="none"
															xmlns="http://www.w3.org/2000/svg"
														>
															<path
																d="M4.61212 8.11566L2.5649 5.96609C2.25248 5.63805 1.74595 5.63805 1.43353 5.96609C1.12111 6.29413 1.12111 6.82598 1.43353 7.15402L4.10019 9.95401C4.43283 10.3033 4.9793 10.2773 5.28045 9.8978L10.6138 3.17782C10.8966 2.82143 10.8508 2.29176 10.5113 1.99476C10.1719 1.69777 9.66748 1.74592 9.38462 2.10231L4.61212 8.11566Z"
																fill="#16B442"
															/>
														</svg>
														{{ trackShipmentInfoLabel }}
													</button>
												</div>
												<div
													v-if="!mblTrackFail && !getMarkingBookedExternal"
													style="margin-bottom: 16px;"
													class="form-label required"
												>
													<span>{{
														"We will use the MBL number to automatically track the shipment"
													}}</span>
												</div>
												<div v-if="mblTrackFail" class="form-label required">
													<span style="color: red !important;">{{
														"Can’t track the shipment. Please make sure you entered a valid number."
													}}</span>
												</div>
											</template>
										</text-field-style>
										<div
											v-if="getMarkingBookedExternal"
											class="form-label required"
										>
											<span
												:style="
													`${
														editItem.mbl_num != '' && editItem.mbl_num != null
															? 'color: #B4CFE0 !important;'
															: ''
													}`
												"
												>{{ "ETD" }}</span
											>
										</div>
										<div
											style="width: 100%;"
											v-if="getMarkingBookedExternal"
											class="text-field-wrapper dates-wrapper"
										>
											<v-menu
												ref="menuEtd"
												v-model="menuEtd"
												:close-on-content-click="false"
												transition="scale-transition"
												offset-y
												min-width="auto"
											>
												<template v-slot:activator="{ on, attrs }">
													<generic-custom-style id="mark-etd-id">
														<template v-slot:content>
															<v-text-field
																:class="
																	`text-fields etd-field tracking-etd-field date-fields ${
																		editItem.mbl_num != '' &&
																		editItem.mbl_num != null
																			? 'mark-date-disabled'
																			: ''
																	}`
																"
																placeholder="MM-DD-YYYY"
																outlined
																:disabled="
																	editItem.mbl_num != '' &&
																		editItem.mbl_num != null
																"
																v-bind="attrs"
																v-on="on"
																type="date"
																hide-details="auto"
																clear-icon
																:height="40"
																:min="minDate"
																:rules="etdMarkRules"
																v-model="editItem.etd"
																append-icons="mdi-calendar"
															/>
														</template>
													</generic-custom-style>
												</template>
												<v-date-picker
													v-if="1 == 2"
													@input="updateEtd"
													v-model="editItem.etdDate"
												></v-date-picker>
											</v-menu>
										</div>

										<div
											v-if="
												!getMarkingBookedExternal && !getAddingShipmentTracking
											"
											style="margin-bottom: 16px !important;"
											id="generalInformationSection"
											ref="generalInformationSection"
											class="d-flex flex-row justify-space-between"
										>
											<div
												v-if="loaded && !specialLoading"
												class="content-title"
											>
												{{ "General Information" }}
											</div>
										</div>

										<div v-if="!getMarkingBookedExternal" class="form-label">
											<span>{{ "BOOKING NUMBER" }}</span>
										</div>
										<div
											v-if="!getMarkingBookedExternal"
											class="text-field-wrapper input-text-wrapper"
										>
											<v-text-field
												:height="40"
												color="#B4CFE0"
												width="200px"
												v-model="editItem.booking_num"
												type="text"
												dense
												class=""
												placeholder="Enter the Booking number"
												outlined
												hide-details="auto"
											>
											</v-text-field>
										</div>

										<select-auto-complete
											v-if="1 == 2"
											content-class="location-from-main-wrapper"
											label="FROM"
											:field.sync="editItem.location_from"
											:items="filteredTerminalRegions"
											:menuProps="{
												contentClass: 'po-lists-items',
												...menuProps,
											}"
											marginBottom="16px"
											width="93%"
											background="not-selected"
										>
											<template v-slot:input="{ mainContent }">
												<v-autocomplete
													attach=".location-from-main-wrapper"
													spellcheck="false"
													:items="mainContent.items"
													placeholder="Select Origin"
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

										<div v-if="1 == 2" class="form-label">
											<span>{{ "FROM" }}</span>
										</div>
										<div
											v-if="1 == 2"
											id="dropdown-wrapper-1"
											class="text-field-wrapper dropdown-wrapper"
										>
											<v-select
												attach="#dropdown-wrapper-1"
												class="text-fields select-items carton-uom"
												:items="filteredTerminalRegions"
												:height="40"
												outlined
												placeholder="Select Origin"
												v-model="editItem.location_from"
												hide-details="auto"
												:menu-props="{ bottom: true, offsetY: true }"
											>
											</v-select>
										</div>
										<div v-if="1 == 2" class="form-label">
											<span>{{ "TO" }}</span>
										</div>
										<div
											v-if="1 == 2"
											id="dropdown-wrapper-3"
											class="text-field-wrapper dropdown-wrapper"
										>
											<v-select
												attach="#dropdown-wrapper-3"
												class="text-fields select-items carton-uom"
												:items="filteredTerminalRegions"
												:height="40"
												outlined
												placeholder="Select Destination"
												v-model="editItem.location_to"
												hide-details="auto"
												:menu-props="{ bottom: true, offsetY: true }"
											>
											</v-select>
										</div>
										<select-auto-complete
											content-class="location-to-main-wrapper"
											label="TO"
											v-if="1 == 2"
											:field.sync="editItem.location_to"
											:items="filteredTerminalRegions"
											:menuProps="{
												contentClass: 'po-lists-items',
												...menuProps,
											}"
											marginBottom="16px"
											width="93%"
											background="not-selected"
										>
											<template v-slot:input="{ mainContent }">
												<v-autocomplete
													attach=".location-from-main-wrapper"
													spellcheck="false"
													:items="mainContent.items"
													placeholder="Select Destination"
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
										<div
											v-if="getEditingShipment && item.booking_confirmed == 0"
											class="form-label required"
										>
											<span>{{ "ETD" }}</span>
											<span>{{ " (Required)" }}</span>
										</div>
										<div
											v-if="getEditingShipment && item.booking_confirmed == 0"
											class="text-field-wrapper dates-wrapper"
										>
											<v-menu
												ref="menuEtd"
												v-model="menuEtd"
												:close-on-content-click="false"
												transition="scale-transition"
												offset-y
												min-width="auto"
											>
												<template v-slot:activator="{ on, attrs }">
													<v-text-field
														class="text-fields etd-field tracking-etd-field date-fields"
														placeholder="MM-DD-YYYY"
														outlined
														v-bind="attrs"
														v-on="on"
														type="text"
														hide-details="auto"
														clear-icon
														:height="40"
														:rules="etdRules"
														@input="updateEtdInput"
														v-model="editItem.etd"
														append-icon="mdi-calendar"
													/>
												</template>
												<v-date-picker
													@input="updateEtd"
													v-model="editItem.etdDate"
												></v-date-picker>
											</v-menu>
										</div>
										<div
											v-if="getEditingShipment && item.booking_confirmed == 0"
											class="form-label eta-label required"
										>
											<span>{{ "ETA" }}</span>
											<span>{{ " (Required)" }}</span>
										</div>
										<div
											v-if="getEditingShipment && item.booking_confirmed == 0"
											class="text-field-wrapper dates-wrapper"
										>
											<v-menu
												ref="menuEta"
												v-model="menuEta"
												:close-on-content-click="false"
												transition="scale-transition"
												offset-y
												min-width="auto"
											>
												<template v-slot:activator="{ on, attrs }">
													<v-text-field
														class="text-fields eta-field date-fields"
														placeholder="MM-DD-YYYY"
														outlined
														v-bind="attrs"
														v-on="on"
														type="text"
														clear-icon
														:height="40"
														hide-details="auto"
														:rules="etaRules"
														@input="updateEtaInput"
														v-model="editItem.eta"
														append-icon="mdi-calendar"
													/>
												</template>
												<v-date-picker
													@input="updateEta"
													v-model="editItem.etaDate"
												></v-date-picker>
											</v-menu>
										</div>
										<div v-if="1 == 2" class="form-label">
											<span style="text-transform: uppercase;">{{
												"TERMINAL"
											}}</span>
										</div>
										<div
											v-if="1 == 2"
											id="dropdown-wrapper-2"
											class="text-field-wrapper dropdown-wrapper"
										>
											<v-select
												attach="#dropdown-wrapper-2"
												class="text-fields select-items carton-uom"
												:items="filteredTerminals"
												:height="40"
												outlined
												placeholder="Select Terminal"
												v-model="editItem.terminal"
												hide-details="auto"
												:menu-props="{ bottom: true, offsetY: true }"
											>
											</v-select>
										</div>

										<div
											style="margin-bottom: 6px !important"
											class="form-label"
											v-if="!getMarkingBookedExternal"
										>
											<span>{{ "MODE" }}</span>
											<span style="color:red !important; padding-left: 5px;">{{
												"*"
											}}</span>
										</div>
										<div
											class="d-flex radio-group-wrapper radio-group-wrapper-web"
											v-if="!getMarkingBookedExternal"
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
														style="position: absolute; opacity: 0;"
														type="radio"
														v-model="editItem.mode"
														@click.stop="checkSelectedMode(m)"
													/>
													<span></span>
												</label>
												<kenetic-icon
													:marginLeft="8"
													:iconName="m.toLowerCase()"
												/>
											</div>
										</div>

									</div>

									<div
										:style="`${getMarkingBookedExternal ? 'width: 50%;' : ''}`"
										id="input-text-3"
										class="form-edit-shipment-form-second-column form-wrapper"
									>
										<text-field-style
											v-if="!getMarkingBookedExternal"
											id="customer-reference-number"
										>
											<template v-slot:content>
												<generic-custom-style
													v-if="getAddingShipmentTracking"
													id="special-note-mbl-missing"
												>
													<template v-slot:content>
														<div>
															{{
																"If you can not find MBL number, please reach out to us at"
															}}
															<span>hello@shifl.com</span>
														</div>
													</template>
												</generic-custom-style>
												<div
													v-if="getAddingShipmentTracking"
													style="margin-top: 40px;"
													class="form-label eta-label required"
												>
													<span style="color: #6D858F !important;">{{
														"Custom Reference No"
													}}</span>
													<span>{{ " (Optional)" }}</span>
												</div>
												<div
													v-if="getAddingShipmentTracking"
													style="width: 97%;"
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
														class=""
														placeholder="Enter custom reference number"
														outlined
														hide-details="auto"
													>
													</v-text-field>
												</div>
											</template>
										</text-field-style>
										<text-field-style
											v-if="getMarkingBookedExternal"
											id="booking-reference-number"
										>
											<template v-slot:content>
												<div class="form-label eta-label required">
													<span>{{ "BOOKING NUMBER" }}</span>
												</div>
												<div
													style="width: 100%; margin-bottom: 24px;"
													id="input-text-1"
													class="text-field-wrapper input-text-wrapper"
												>
													<v-text-field
														:height="40"
														color="#002F44"
														width="200px"
														v-model="editItem.booking_number"
														type="text"
														dense
														:rules="bookingNumRules"
														class=""
														placeholder="Enter booking number"
														outlined
														hide-details="auto"
													>
													</v-text-field>
												</div>
											</template>
										</text-field-style>
										<div
											v-if="getMarkingBookedExternal"
											class="form-label required"
										>
											<span
												:style="
													`${
														editItem.mbl_num != '' && editItem.mbl_num != null
															? 'color: #B4CFE0 !important;'
															: ''
													}`
												"
												>{{ "ETA" }}</span
											>
										</div>
										<div
											style="width: 100%;"
											v-if="getMarkingBookedExternal"
											class="text-field-wrapper dates-wrapper"
										>
											<v-menu
												ref="menuEta"
												v-model="menuEta"
												:close-on-content-click="false"
												transition="scale-transition"
												offset-y
												min-width="auto"
											>
												<template v-slot:activator="{ on, attrs }">
													<generic-custom-style id="mark-eta-id">
														<template v-slot:content>
															<v-text-field
																:class="
																	`text-fields etd-field tracking-eta-field date-fields ${
																		editItem.mbl_num != '' &&
																		editItem.mbl_num != null
																			? 'mark-date-disabled'
																			: ''
																	}`
																"
																placeholder="MM-DD-YYYY"
																outlined
																v-bind="attrs"
																v-on="on"
																type="date"
																:disabled="
																	editItem.mbl_num != '' &&
																		editItem.mbl_num != null
																"
																hide-details="auto"
																clear-icon
																:height="40"
																:min="minDate"
																:rules="etaMarkRules"
																@input="updateEtaInput"
																v-model="editItem.eta"
																append-icons="mdi-calendar"
															/>
														</template>
													</generic-custom-style>
												</template>
												<v-date-picker
													v-if="1 == 2"
													@input="updateEta"
													v-model="editItem.etaDate"
												></v-date-picker>
											</v-menu>
										</div>
										<select-auto-complete
											content-class="terminal-main-wrapper"
											label="TERMINAL"
											:field.sync="editItem.terminal"
											:items="filteredTerminalRegions"
											:menuProps="{
												contentClass: 'po-lists-items',
												...menuProps,
											}"
											marginBottom="16px"
											width="93%"
											background="not-selected"
											v-if="1 == 2"
										>
											<template v-slot:input="{ mainContent }">
												<v-autocomplete
													attach=".terminal-main-wrapper"
													spellcheck="false"
													:items="mainContent.items"
													placeholder="Select Terminal"
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
											v-if="1 == 2"
											content-class="carrier-main-wrapper"
											label="CARRIER"
											placeholder="Select Carrier"
											:items="filteredCarriers"
											:field.sync="editItem.carrier_id"
											:menuProps="{
												contentClass: 'po-lists-items',
												...menuProps,
											}"
											marginBottom="16px"
											width="93%"
											background="not-selected"
										>
											<template v-slot:input="{ mainContent }">
												<v-autocomplete
													attach=".carrier-main-wrapper"
													spellcheck="false"
													:items="mainContent.items"
													placeholder="Select Terminal"
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
										<div v-if="1 == 2" id="input-text-4" class="form-label">
											<span style="text-transform: uppercase;">{{
												"Voyage Number"
											}}</span>
										</div>
										<div
											v-if="1 == 2"
											class="text-field-wrapper input-text-wrapper"
										>
											<v-text-field
												v-model="editItem.voyage_number"
												:height="40"
												color="#002F44"
												width="200px"
												type="text"
												dense
												class=""
												placeholder="Voyage Number"
												outlined
												hide-details="auto"
											>
											</v-text-field>
										</div>
										<div
											v-if="1 == 2"
											style="padding-top: 2px;"
											class="d-flex flex-row"
										>
											<div>
												<div
													class="form-label d-flex flex-row justify-space-between align-end"
												>
													<span
														:class="
															`${editItem.type == '' ? 'unselected' : ''}`
														"
														>{{ "Type" }}</span
													>
												</div>
												<div
													:class="
														`d-flex radio-group-wrapper radio-group-wrapper-web ${
															editItem.type == '' ? 'unselected' : ''
														}`
													"
												>
													<div
														v-bind:key="`mode-${key}`"
														v-for="(t, key) in filteredTypes"
														:class="
															`d-flex radio-item align-center ${
																t === 'LCL' ? 'mr-8' : ''
															} ${t === editItem.type ? 'selected' : ''}`
														"
													>
														<label
															style="position: relative;"
															class="radio-label-wrapper"
														>
															{{ t }}
															<input
																name="type"
																:value="t"
																class="custom-radio"
																style="position: absolute; opacity: 0;"
																type="radio"
																v-model="editItem.type"
															/>
															<span></span>
														</label>
														<v-radio-group
															v-if="1 == 2"
															v-model="editItem.type"
														>
															<v-radio
																color="primary"
																:label="t"
																:value="t"
															></v-radio>
														</v-radio-group>
														<kenetic-icon
															:color="
																`${
																	editItem.type === '' ? '#B4CFE0' : '#6D858F'
																}`
															"
															:marginLeft="8"
															:iconName="t.toLowerCase()"
														/>
													</div>
												</div>
											</div>
											<div v-if="1 == 2">
												<div
													class="form-label d-flex flex-row justify-space-between"
												>
													<span>{{ "Rail" }}</span>
												</div>
												<div class="d-flex radio-group-wrapper">
													<div
														v-bind:key="`mode-${key}`"
														v-for="(t, key) in anotherTypes"
														:class="
															`d-flex radio-item align-center ${
																t === type ? 'selected' : ''
															}`
														"
													>
														<v-radio-group v-model="type">
															<v-radio
																color="primary"
																:label="t"
																:value="t"
															></v-radio>
														</v-radio-group>
														<kenetic-icon
															:marginLeft="8"
															:iconName="t.toLowerCase()"
														/>
													</div>
												</div>
											</div>
										</div>
										<div v-if="1 == 2" class="d-flex flex-column">
											<div class="form-label">
												<span style="text-transform: uppercase;">{{
													"VESSEL"
												}}</span>
											</div>
											<div
												id="input-text-2"
												class="text-field-wrapper input-text-wrapper"
											>
												<v-text-field
													v-model="editItem.vessel"
													:height="40"
													color="#002F44"
													width="200px"
													type="text"
													dense
													class=""
													placeholder="Type vassel no"
													outlined
													hide-details="auto"
												>
												</v-text-field>
											</div>
										</div>

										<div
											v-if="filteredTypes.length > 0 && !getMarkingBookedExternal"
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
											v-if="filteredTypes.length > 0 && !getMarkingBookedExternal"
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

									</div>
								</div>
								<div v-if="getMarkingBookedExternal" class="d-flex flex-column">
									<div class="d-flex flex-row">
										<generic-custom-style
											id="mark-booked-external-content-title"
											customClass="content-title"
										>
											<template v-slot:content>
												<span>
													{{ "Additional Info" }}
												</span>
												<span>
													{{ "(Optional)" }}
												</span>
											</template>
										</generic-custom-style>
									</div>
									<text-field-style>
										<template v-slot:content>
											<div class="form-label eta-label required">
												<span>{{ "CUSTOM REFERENCE NUMBER" }}</span>
											</div>
											<div
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
													class=""
													placeholder="Enter custom reference number"
													outlined
													hide-details="auto"
												>
												</v-text-field>
											</div>
										</template>
									</text-field-style>
								</div>
								<div
									v-if="trackInfoData !== null"
									style="margin-top: 16px !important;margin-bottom: 16px !important;"
									class="d-flex flex-row justify-space-between"
								>
									<div class="content-title">
										{{ "Tracked Information" }}
									</div>
								</div>
								<track-information-wrapper
									v-if="trackInfoData !== null"
									id="tracked-information-wrapper"
								>
									<template v-slot:content>
										<div class="d-flex flex-row">
											<div style="width: 50%;" class="d-flex flex-column">
												<div
													class="d-flex flex-row tracked-information-label-wrapper"
												>
													<div>From & ETD</div>
													<div class="dates-wrapper">
														<span>{{
															trackInfoData.location_from + ", "
														}}</span>
														<span>{{
															trackingDateConvert(trackInfoData.etd)
														}}</span>
													</div>
												</div>
												<div
													class="d-flex flex-row tracked-information-label-wrapper"
												>
													<div>To & ETA</div>
													<div class="dates-wrapper">
														<span>{{ trackInfoData.location_to + ", " }}</span>
														<span>{{
															trackingDateConvert(trackInfoData.eta)
														}}</span>
													</div>
												</div>
												<div
													class="d-flex flex-row tracked-information-label-wrapper"
												>
													<div>Carrier</div>
													<div>{{ trackInfoData.carrier }}</div>
												</div>
											</div>
											<div style="width: 50%;" class="d-flex flex-column">
												<div
													class="d-flex flex-row tracked-information-label-wrapper"
												>
													<div>Vessel & Voyage</div>
													<div>
														{{
															trackInfoData.vessel +
																", " +
																trackInfoData.voyage_number
														}}
													</div>
												</div>
												<div
													class="d-flex flex-row tracked-information-label-wrapper"
												>
													<div>Terminal</div>
													<div>{{ trackInfoData.terminal_name ? trackInfoData.terminal_name : 'N/A' }}</div>
												</div>
												<!-- <div
													class="d-flex flex-row tracked-information-label-wrapper"
												>
													<div>Incoterms</div>
													<div>Yantian, June 05, 2022</div>
												</div> -->
											</div>
										</div>
									</template>
								</track-information-wrapper>

								<div
									v-if="!getMarkingBookedExternal"
									id="purchaseOrderSection"
									ref="purchaseOrderSection"
									class="d-flex flex-column purchase-orders-section"
								>
									<div style="padding-bottom: 8px;" class="content-title">
										{{
											getAddingShipmentTracking ? "Orders" : "Purchase Orders"
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
												<v-select
													v-model="defaultOrderType"
													class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
													:items="orderTypeValue"
													placeholder="Select Order Type"
													@change="getDefaultOrderType($event, key)"
													outlined
													item-text="name"
													item-value="type"
													dense
												></v-select>
												<v-autocomplete
													:filter="customFilterPo"
													v-model="poi.purchase_order_id"
													spellcheck="false"
													:attach="`.select-autocomplete-wrapperpoi-${key}`"
													class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
													:items="poi.purchase_order_options"
													:placeholder="
														ordersLoading ? 'Orders Loading...' : 'Select Order'
													"
													outlined
													item-text="po_number"
													item-value="id"
													:menu-props="{
														contentClass: 'po-lists-items',
														...menuProps,
													}"
													hide-details="auto"
													@change="updateProducts(poi, key)"
													clearable
													:disabled="defaultOrderType == '' || ordersLoading"
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
															<div
																class="d-flex last-row"
																style="width: 100%; padding-bottom: 24px;"
															>
																<a class="last-row-status">
																	{{ getPoStatus(item.status) }}
																</a>
															</div>
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
													<button
														v-if="1 == 2"
														@click.stop="removePurchaseOrderItem(key)"
														class="btn-white"
													>
														<custom-icon
															iconName="trash-can"
															color="#ff5252"
														></custom-icon>
													</button>
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
																:class="validateProduct(item, key)"
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
																	contentClass: 'po-lists-items',
																	...menuProps,
																}"
																hide-details="auto"
																clearable
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
															</v-autocomplete>
															<div
																v-if="1 == 2"
																class="error-message"
																style="font-size: 12px; color: red; height: 30px; padding-top: 19px;"
															>
																{{
																	typeof item.addSpecial == "undefined" &&
																	typeof item.special == "undefined" &&
																	validateProduct(item, key) === "error"
																		? "This product has already been selected."
																		: ""
																}}
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
													<template v-slot:[`item.addAll`]="{ item }">
														<div
															v-if="
																typeof item.special == 'undefined' &&
																	typeof item.addSpecial == 'undefined'
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
																	class=""
																/>
																<span></span>
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
																		:disabled="
																			item.product_id == '' || item.addAll
																		"
																		v-model="item.carton"
																		type="number"
																		dense
																		:rules="[
																			(v) => !!v || 'Carton is required.',
																			cartonRules(item),
																		]"
																		class="carton"
																		placeholder=""
																		@keyup="updateUnit(item, key)"
																		outlined
																		hide-details="auto"
																	>
																	</v-text-field>
																</div>
															</template>
															<span>
																{{ cartonTooltipMessage(item) }}
															</span>
															<span
																v-if="typeof item.unship_cartons == 'undefined'"
															>
																{{ "Select first a product." }}
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
															placeholder="Weight"
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
															disabled
															v-model="item.unit"
															@keyup="updateCarton(item, key)"
															type="number"
															dense
															class="unit"
															placeholder="Unit"
															outlined
															hide-details="auto"
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
														<div>
															<div
																v-if="
																	typeof item.special == 'undefined' &&
																		typeof item.addSpecial == 'undefined'
																"
																class="d-flex unit-price"
															>
																{{ "$" + item.unit_price }}
															</div>
															<div
																class="total-label-product"
																style="width: 100%; text-align: right;"
																v-if="
																	typeof item.special !== 'undefined' &&
																		typeof item.addSpecial == 'undefined' &&
																		1 == 2
																"
															>
																{{ "Total" }}
															</div>
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
															<a
																style="display: block; text-align: right; width: 100% !important; padding-right: 8px !important;"
																class="total-label-product-value"
																v-if="
																	typeof item.special !== 'undefined' &&
																		typeof item.addSpecial == 'undefined'
																"
																>{{ calculateTotal(key) }}</a
															>
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
													style="margin-bottom: 12px;"
													v-if="filterProducts(poi).length > 0"
													class="d-flex flex-row po-total-wrapper"
												>
													<div class="po-total-item">
														Total Cartons: {{ calculateTotals(poi, "carton") }}
													</div>
													<div class="po-total-item">
														Total Volume: {{ calculateTotals(poi, "volume") }}
													</div>
													<div class="po-total-item">
														Total Weight: {{ calculateTotals(poi, "weight") }}
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
												<v-select
													v-model="defaultOrderType"
													class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
													:items="orderTypeValue"
													placeholder="Select Order Type"
													@change="getDefaultOrderType($event, key)"
													outlined
													item-text="name"
													item-value="type"
													dense
												></v-select>
												<div style="margin-right: 8px;">
													<input-text
														:noLabel="true"
														:field.sync="poi.purchase_order_number"
														labelColor="#819FB2"
														:inputFontWeight="400"
														:inputFontSize="14"
														placeholderText="Enter Order No"
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
															>
															</v-text-field>
														</template>
													</input-text>
												</div>
												<v-select v-if="defaultOrderType == 'PO'"
													v-model="poi.supplier_id"
													class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
													placeholder="Select or Enter Supplier"
													outlined
													:items="getSupplierOptions"
													dense
													item-text="name"
													item-value="value"
												></v-select>
												<v-select v-else-if="defaultOrderType == 'SO'"
													v-model="poi.buyer_id"
													class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
													placeholder="Select or Enter Supplier"
													outlined
													:items="getBuyerOptions"
													dense
													item-text="name"
													item-value="value"
												></v-select>
												<v-select v-else
													class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
													placeholder="Select or Enter Supplier"
													outlined
													dense
													item-text="name"
													item-value="value"
												></v-select>
												<div class="text-field-wrapper dates-wrapper">
													<v-menu
														v-model="poi.menuCargoReadyDate"
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
																v-model="poi.cargo_ready_date"
																placeholder="MM-DD-YYYY"
																outlined
																class="text-fields etd-field date-fields pom-cargo-ready-date"
																hide-details="auto"
																:min="minCargoDate"
																@input="(val) => updateCargoReadyDateInput(poi, val)"
															>
															</v-text-field>
														</template>
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
										class="d-flex add-product-purchase-order-wrapper add-purchase-order-wrapper"
									>
										<v-btn
											class="btn-white mr-4 shipment-header-button-btn add-purchase-order-btn-wrapper"
											@click="addPurchaseOrderItem()"
											:disabled="defaultBtnDisable"
										>
											<span class="add-purchase-order-span">{{
												"+ Add Order"
											}}</span>
										</v-btn>
										<v-btn
											class="btn-white mr-4 shipment-header-button-btn add-purchase-order-btn-wrapper"
											@click="addManualOrderItem()"
											:disabled="manualBtnDisable"
										>
											<span class="add-manual-po-span">+ Add Manually</span>
										</v-btn>
									</div>
								</div>
								<div
									v-if="getAddingShipmentTracking"
									id="cargoDetailsSection"
									ref="cargoDetailsSection"
									class="d-flex flex-column cargo-details-section"
								>
									<div class="content-title">
										{{ "Cargo Details" }}
									</div>
									<div class="d-flex total-cartons">
										<div class="d-flex flex-column" style="width: 50%;">
											<div class="form-label">
												<span class="text-uppercase">{{
													"Total Cartons"
												}}</span>
											</div>
											<div class="text-field-wrapper input-text-wrapper">
												<v-text-field
													height="40px"
													:disabled="purchaseOrderItems.length > 0"
													color="#002F44"
													width="200px"
													:value="
														purchaseOrderItems.length > 0
															? calculateOverAllTotal('carton')
															: totalCartons
													"
													type="number"
													dense
													class="unit"
													placeholder="Total cartons"
													outlined
													hide-details="auto"
												>
												</v-text-field>
											</div>
											<div
												id="cargo-details-volume-weight"
												style="width: 93%;"
												class="d-flex"
											>
												<div
													class="d-flex flex-column"
													style="width: 50%; margin-right: 16px;"
												>
													<div class="form-label">
														<span class="text-uppercase">{{
															"Total Volumes"
														}}</span>
													</div>
													<div class="text-field-wrapper input-text-wrapper">
														<v-text-field
															height="40px"
															:disabled="purchaseOrderItems.length > 0"
															color="#002F44"
															width="200px"
															:value="
																purchaseOrderItems.length > 0
																	? calculateOverAllTotal('volume')
																	: totalVolumes
															"
															type="text"
															dense
															class="unit"
															placeholder="Total volume"
															outlined
															hide-details="auto"
														>
														</v-text-field>
													</div>
												</div>
												<div style="width: 50%;">
													<div class="form-label">
														<span class="text-uppercase">{{
															"Total Weight"
														}}</span>
													</div>
													<div class="text-field-wrapper input-text-wrapper">
														<v-text-field
															height="40px"
															:disabled="purchaseOrderItems.length > 0"
															color="#002F44"
															width="200px"
															:value="
																purchaseOrderItems.length > 0
																	? calculateOverAllTotal('weight')
																	: totalWeights
															"
															type="text"
															dense
															class="unit"
															placeholder="Total weight"
															outlined
															hide-details="auto"
														>
														</v-text-field>
													</div>
												</div>
											</div>
										</div>
										<div class="d-flex flex-column" style="width: 50%;">
											<marks-style id="booking-marks-wrapper-3">
												<template v-slot:content>
													<custom-text-area
														label="COMMODITY, HS CODE, MATERIAL, USAGE"
														marginTop="0px"
														contentClass="commodity-other-info-wrapper"
														:field.sync="editItem.commodities_info"
														labelColor="#819FB2"
														placeholderText="Select Consignee for notify details"
														id="consignee-textarea-wrapper-id"
														:inputFontWeight="400"
														:inputFontSize="14"
													>
														<template v-slot:label="{ label }">
															<div class="form-label">
																<span class="text-uppercase">{{ label }}</span>
															</div>
														</template>
														<template v-slot:input="{ mainContent }">
															<v-textarea
																:class="
																	`text-fields custom-font-weight-${mainContent.inputFontWeight} custom-font-${mainContent.inputFontSize}`
																"
																outlined
																:value="editItem.commodities_info"
																:height="600"
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

											<div v-if="1 == 2" class="form-label">
												<span class="text-uppercase">{{
													"Commodity, hs code, material, Usage"
												}}</span>
											</div>
											<div
												v-if="1 == 2"
												id="text-area-wrapper-7"
												class="text-field-wrapper input-text-wrapper textarea-wrapper commodity-other-info-wrapper"
											>
												<v-textarea
													class="text-fields"
													outlineds
													v-model="editItem.commodities_info"
													placeholder="Select Consignee for notify details"
													hide-details="auto"
													:height="600"
													autocomplete="off"
												>
												</v-textarea>
											</div>
										</div>
									</div>
								</div>
								<!-- <div
									v-if="!getMarkingBookedExternal"
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
								</div> -->
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
				<v-card-text class="pb-0" v-if="multiShipmentForm">
					<create-multi-shipment
						@addBulkShipmentSuccess="addBulkShipmentSuccess"
						@close="close"
						@reloadShipments="reloadShipments"
					/>
				</v-card-text>
				<v-card-actions v-if="!multiShipmentForm">
					<slot
						v-bind:footer="{
							close: close,
							createShipment: addShipment,
							createLoading: getCreateShipmentLoading,
							updateShipment: updateShipment,
							updateLoading: updateLoading,
							checkMarkExternalValidation: checkMarkExternalValidation,
							loaded: loaded,
						}"
						name="actions"
					></slot>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<ConfirmDialog :dialogData.sync="missingMblNumberDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Missing MBL Number</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					You have not provided a MBL number, We will not be able to track the
					shipment info without the MBL number.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="continueWithoutMbl()">
					<span>Continue without MBL</span>
				</v-btn>
				<v-btn class="btn-white" text @click="AddMbl()">
					Add MBL
				</v-btn>
			</template>
		</ConfirmDialog>

		<ConfirmDialog :dialogData.sync="shipmentExitDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Shipment Already Exist!</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					There is an existing shipment with the same MBL number. Do you want to
					view the shipment?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="viewShipment()">
					<span>View Shipment</span>
				</v-btn>
				<v-btn
					class="btn-white"
					text
					@click="
						shipmentExitDialog = false;
						editItem.mbl_num = '';
						mblTracked = false;
					"
				>
					Change MBL
				</v-btn>
			</template>
		</ConfirmDialog>

		<ConfirmDialog :dialogData.sync="lclShipmentDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Is this a LCL shipment?</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					There is an existing shipment with the same MBL number for a different
					customer. Do you want to track this as a LCL Shipment?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="lclShipmentDialog = false">
					<span>Yes, Continue</span>
				</v-btn>
				<v-btn
					class="btn-white"
					text
					@click="
						lclShipmentDialog = false;
						editItem.mbl_num = '';
						mblTracked = false;
					"
				>
					No, Change MBL
				</v-btn>
			</template>
		</ConfirmDialog>

		<ConfirmDialog :dialogData.sync="shipmentModeConfirmation">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Want to change Mode?</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Based on the MBL Number, Shipment mode should be {{ editItem.mode }}, please check again!
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="changeSelectedMode('Ocean')">
					<span>Select Ocean</span>
				</v-btn>
				<v-btn class="btn-white" text @click="changeSelectedMode('Air')">
					<span>Select Air</span>
				</v-btn>
			</template>
		</ConfirmDialog>

		<TrackShipmetWithoutMblDialog
			:dialogData.sync="trackShipmentWithoutMblNumberDialog"
			@closeDialog="closeTrackShipmentWithoutMblNumberDialog"
			@trackShipment="trackShipment"
		/>
	</div>
</template>
<style lang="scss">
@import "./scss/createShipmentDialogTracking.scss";
@import "./scss/fields.scss";
</style>
<script>
import _ from "lodash";
import moment from "moment";
import KeneticIcon from "../../Icons/KeneticIcon";
import CustomIcon from "../../Icons/CustomIcon";
import GenericIcon from "../../Icons/GenericIcon";
import globalMethods from "../../../utils/globalMethods";
import SelectAutoComplete from "./SelectAutoCompletes/SelectAutoComplete";
// import ContainersTrackingTable from "./Tables/ContainersTrackingTable.vue";
import TextFieldStyle from "./StyleWrappers/TextFieldStyle.vue";
import TrackInformationWrapper from "./StyleWrappers/TrackInformationWrapper.vue";
import MarksStyle from "./StyleWrappers/MarksStyle.vue";
import CustomTextArea from "./TextAreas/TextArea.vue";
import iziToast from "izitoast";
import { mapGetters, mapActions } from "vuex";
import GenericCustomStyle from "./StyleWrappers/GenericCustomStyle.vue";
import InputText from "./InputTexts/InputText";
import GenericTable from "./Tables/GenericTable";

import jQuery from "jquery";
import CreateShipmentDialogMobile from "./CreateShipmentDialogMobile";

//import datas here
import headers from "./Datas/tableHeaders";
import CreateShipmentItem from "./Structures/CreateShipmentItem";
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";
import TrackShipmetWithoutMblDialog from "@/components/Dialog/FormShipmentDialog/TrackShipmetWithoutMblDialog.vue";
import CreateMultiShipment from './CreateMultiShipment.vue';

export default {
	name: "CreateShipmentDialog",
	props: [
		"reference",
		"show",
		"className",
		"rules",
		"isMobile",
		"windowWidth",
		"addShipmentDialogModalView",
		"refNumber",
		"item",
		"isEdit",
		"addBulkShipmentDialogModalView",
	],
	data: () => ({
		checkTrackingNumber: false,
		updateLoading: false,
		//purchaseOrderOptions: [],
		selectedOrderIds: [],
		scrollAnimating: false,
		currentSection: "general",
		mblNumInfo: null,
		containerInfo: null,
		mblTracked: false,
		containerNewItems: [],
		mblTrackFail: false,
		trackedInformation: [],
		ordersLoading: false,
		defaultBtnDisable: false,
		manualBtnDisable: false,
		totalCartons: null,
		totalVolumes: null,
		totalWeights: null,
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
		customerReferenceRules: [
			(v) => !!v || "Custom Reference Number is required.",
		],
		bookingNumRules: [
			() => {
				return true;
				/*
                if ( moment(vv.editItem.etd).isValid() || moment(vv.editItem.eta).isValid()) {
                    return true
                } else {
                    if ( v!==null && v!=='' ) {
                        return true
                    } else {
                        return 'Booking Number is required.'
                    }
                }*/

				//return 'Booking Number is required.'
			},
		],
		etaMarkRules: [
			() => {
				/*
                //if both fields are not filled then require eta
                if ( moment(vv.editItem.etd).isValid() ) {
                    return true
                } else {
                    if ( moment(v).isValid() ) {
                        return true
                    } else {
                        return 'ETA is invalid. Format should be MM-DD-YYYY.'
                    }
                }*/
				return true;
			},
		],
		etdMarkRules: [
			() => {
				//if both fields are not filled then require etd
				/*
                if ( moment(vv.editItem.eta).isValid() ) {
                    return true
                } else {
                    if ( moment(v).isValid() ) {
                        return true
                    } else {
                        return 'ETD is invalid. Format should be MM-DD-YYYY.'
                    }
                }*/
				return true;
			},
		],
		etaRules: [
			(v) => !!v || "ETA is required.",
			(v) =>
				moment(v).isValid() || "ETA is invalid. Format should be MM-DD-YYYY.",
		],
		etdRules: [
			(v) => !!v || "ETD is required.",
			(v) =>
				moment(v).isValid() || "ETD is invalid. Format should be MM-DD-YYYY.",
		],
		containerSizes: [],
		containerVolumes: [],
		containerWeights: [],
		selectAll: false,
		menuProps: {
			bottom: true,
			offsetY: true,
		},
		discharges: [],
		fetchSinglePurchasOrderLoading: false,
		products: [
			{
				text: "Test",
				value: "Test",
			},
		],
		purchaseOrderItems: [],
		purchaseOrderProductItems: [],
		containerItems: [],
		headersDocuments: headers.documents.data,
		headersContainers: headers.containersTracking.data,
		headersProducts: headers.products.data,
		headersPurchaseOrders: headers.purchaseOrders.data,
		dynamicHeight: 0,
		purchaseOrderId: 0,
		productId: 0,
		containerId: 0,
		editItem: CreateShipmentItem,
		attrs: {
			class: "mb-6",
			boilerplate: true,
			elevation: 2,
		},
		resourcesLoaded: 0,
		resourcesLimit: 4,
		valid: true,
		modeGroup: 1,
		loaded: false,
		mode: "",
		from: "",
		to: "",
		carriers: [],
		menuEtd: false,
		menuEta: false,
		etaField: null,
		etaDate: null,
		etdField: null,
		etaSample: null,
		etdSample: null,
		modes: ["Ocean", "Air"],
		paymentTerms: ["FOB", "EXW", "DDP", "FCA", "CFR", "CIF"],
		type: "",
		anotherTypes: ["Rail"],
		types: ["FCL", "LCL"],
		trackInfoData: null,
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
		specialLoading: false,
		isBookingConfirmed: false,
		missingMblNumberDialog: false,
		trackShipmentWithoutMblNumberDialog: false,
		ShipmentsTracking: false,
		ShipmentsTrackingData: null,
		orderTypeValue: [
			{ type: "PO", name: "Purchase Order" },
			{ type: "SO", name: "Sales Order" },
		],
		defaultOrderType: "",
		shipmentExitDialog: false,
		shipmentExistId: 0,
		lclShipmentDialog: false,
		userDetails: {},
		manualOrderType: "",
		supplierOrBuyer: "",
		shipmentMode: "",
		shipmentModeConfirmation: false,
		shiflShipmentRef: "",
		minCargoDate: "",
		defaultTerminalMode: "",
		multiShipmentForm: false,
	}),
	components: {
		KeneticIcon,
		CustomIcon,
		CreateShipmentDialogMobile,
		SelectAutoComplete,
		// ContainersTrackingTable,
		GenericIcon,
		TextFieldStyle,
		GenericCustomStyle,
		TrackInformationWrapper,
		MarksStyle,
		CustomTextArea,
		ConfirmDialog,
		TrackShipmetWithoutMblDialog,
		InputText,
		GenericTable,
		CreateMultiShipment,
	},
	async mounted() {
		//setup scroll
		this.setupScroll();

		if (
			this.getEditingShipment ||
			this.reference !== "formCreateShipment" ||
			this.getMarkingBookedExternal
		) {
			//this.editItem = this.item
			//load shipment details
			this.specialLoading = true;

			await this.fetchShipmentDetails(this.item.id);
			this.fetchMilestones(this.getShipmentDetails.mbl_num)
				.then(() => {
					this.specialLoading = false;
				})
				.catch((e) => {
					this.specialLoading = false;
					console.log(e);
				});

			//assign shipment details
			this.editItem = this.getShipmentDetails;
			this.editItem.eta = moment(this.editItem.eta).format("MM-DD-YYYY");
			this.editItem.etd = moment(this.editItem.etd).format("MM-DD-YYYY");

			//check booking confirmation
			this.isBookingConfirmed = this.editItem.booking_confirmed;

			//check validity
			if (this.editItem.eta === "Invalid date") {
				this.editItem.eta = "";
				//this.editItem.eta = moment().format('MM-DD-YYYY')
				this.editItem.etaDate = "";
			}

			if (this.editItem.etd === "Invalid date") {
				//this.editItem.etd = moment().format('MM-DD-YYYY')
				this.editItem.etd = "";
				this.editItem.etdDate = "";
			}

			this.editItem.customer_reference_number =
				this.editItem.customer_reference_number === null ||
				this.editItem.customer_reference_number == "null"
					? ""
					: this.editItem.customer_reference_number;

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

			if (containers.length > 0) {
				containers.map((c, kk) => {
					containers[kk].size = parseInt(c.size);
				});
			}
			this.containerItems = containers === null ? [] : containers;

			if (schedules.length > 0) {
				this.editItem.eta = moment(schedules[0].eta).format("MM-DD-YYYY");
				this.editItem.etd = moment(schedules[0].etd).format("MM-DD-YYYY");
				this.editItem.etaDate = moment(schedules[0].eta).format("YYYY-MM-DD");
				this.editItem.etdDate = moment(schedules[0].etd).format("YYYY-MM-DD");

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

			this.editItem.commodities_info = "";

		} else {
			this.editItem.mbl_num = "";
			this.editItem.vessel = "";
			this.editItem.voyage_number = "";
			this.editItem.mode = "";
			this.editItem.type = "";
			this.editItem.carrier_id = "";
			this.editItem.location_from = "";
			this.editItem.location_to = "";
			this.editItem.carrier_id = "";
			this.editItem.booking_num = "";
			this.editItem.commodities_info = "";
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

		this.checkOrdersButton();

		//fetch consignee options
		this.$store.dispatch("booking/getConsigneeOptions", {
			page: 1,
		});

		//fetch shipper options
		this.$store.dispatch("booking/getShipperOptions", {
			page: 1,
		});

		try {
			this.userDetails = JSON.parse(this.getUser);
		} catch (e) {
			this.userDetails = this.getUser;
		}
	},
	computed: {
		minDate() {
			return new Date().toISOString().substr(0, 10);
			//return moment().format('MM-DD-YYYY')
		},
		currentDate() {
			let dateObject = new Date();
			let year = dateObject.getFullYear();
			let month =
				dateObject.getMonth() + 1 < 10
					? `0${dateObject.getMonth() + 1}`
					: dateObject.getMonth() + 1;
			let day =
				dateObject.getDay() + 1 < 10
					? `0${dateObject.getDay() + 1}`
					: dateObject.getDay() + 1;
			return `${year}-${month}-${day}`;
		},
		...mapGetters([
			"getCarriers",
			"getTerminalRegions",
			"getTerminals",
			"getCreateShipmentLoading",
			"getShipmentContainerSizes",
			"getUser",
			"getOrders",
		]),
		trackShipmentInfoLabel() {
			if (this.editItem.mbl_num === "" && !this.mblTracked) {
				return "Track Shipment Info";
			} else {
				if (this.mblTracked) {
					return "Tracked";
				} else {
					return "Track";
				}
			}
		},
		getShipmentDetails() {
			return this.$store.getters["booking/getShipmentDetails"];
		},
		getEditingShipment() {
			return this.$store.getters["page/getEditingShipment"];
		},
		getMarkingBookedExternal() {
			return this.$store.getters["page/getMarkingBookedExternal"];
		},
		getAddingShipmentTracking() {
			return this.$store.getters["page/getAddingShipmentTracking"];
		},
		getCreatingBookingRequest() {
			return this.$store.getters["page/getCreatingBookingRequest"];
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
					options: ["FCL", "LCL"],
				},
				{
					label: "Ocean",
					options: ["FCL", "LCL"],
				},
			];

			let setMode = this.editItem.mode;
			if (this.editItem.mode === "") setMode = "Ocean";

			//return options
			let getOptions = _.find(types, (e) => e.label === setMode);
			return getOptions.options;
		},
		purchaseOrderOptions() {
			let options = this.$store.getters["getOrders"];
			if (!this.getMarkingBookedExternal || !this.getEditingShipment) {
				options = _.filter(
					options,
					(e) => e.status === "pending" || e.status === "partially_shipped"
				);
			}
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
		selectedPage() {
			return _.find(this.sidebarItems, { selected: true }).icon;
		},
		getSupplierOptions() {
			let supplierArr = [];
			let suppliers =  this.$store.getters["booking/getShippers"];
			supplierArr =
				suppliers &&
				suppliers.map((item) => {
					return {
						name: item.company_name,
						value: item.id,
					};
				});
			return supplierArr;
		},
		getBuyerOptions() {
			let buyerArr = [];
			let buyers = this.$store.getters["booking/getConsignees"];
			buyerArr =
				buyers &&
				buyers.map((item) => {
					return {
						name: item.company_name,
						value: item.id,
					};
				});
			return buyerArr;
		},
	},
	methods: {
		...mapActions([
			"fetchCarriers",
			"fetchTerminalRegions",
			"createShipment",
			"fetchContainers",
			"editShipment",
			"fetchTerminals",
			"fetchMilestones",
			"markBookExternal",
			"createShipmentWithoutMblNumber",
			"fetchOrders",
		]),
		...globalMethods,
		async fetchShipmentDetails(id) {
			await this.$store.dispatch("booking/fetchShipmentDetails", id);
		},
		cartonTooltipMessage(item) {
			if (
				typeof item.unship_cartons !== "undefined" &&
				parseFloat(item.carton) > parseFloat(item.unship_cartons)
			) {
				return `You can only enter a maximum of ${item.unship_cartons} carton${
					item.unship_cartons > 1 ? "s" : ""
				}.`;
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
		setupScroll() {
			//setup interval to wait for the dom to actually appear before adding event listener
			let t = setInterval(() => {
				let d = document.getElementById("second-column-id-create");
				if (d !== null) {
					d.addEventListener("scroll", () => {
						//get scroll top
						if (!this.scrollAnimating) {
							let x = jQuery("#second-column-id-create").scrollTop();
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
		trackingDateConvert(date) {
			return date !== null ? moment(date).format("MMM Do YY") : null;
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
								if (entity === "volume") {
									if (typeof gp[entity] !== "undefined") {
										total += parseFloat(gp[entity]);
									}
								} else {
									total += parseInt(gp[entity]);
								}
							});
						}
					} else {
						if (entity === "volume") {
							total += parseFloat(poi.total_volumes);
						} else if (entity === "carton") {
							total += parseInt(poi.total_cartons);
						} else if (entity === "weight") {
							total += parseInt(poi.total_weights);
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
		calculateOverAllTotale(entity) {
			//calculate only the default po
			let total = 0;
			if (this.purchaseOrderItems.length > 0) {
				this.purchaseOrderItems.map((poi) => {
					if (poi.layout === "default") {
						let getProducts = poi.products;
						if (getProducts.length > 0) {
							getProducts.map((gp) => {
								total += parseInt(gp[entity]);

								if (entity === "volume") {
									total += parseFloat(gp[entity]);
								}
							});
						}
					}
				});
			}

			if (entity === "volume") {
				//iterate through container items sizes
				if (this.containerNewItems.length > 0) {
					this.containerNewItems.map((val, key) => {
						/*
                        let map_predefined_values = [{
                        key: '20\'GP',
                        value: 27,  
                        },
                        {
                        key: '40\'GP',
                        value: 57,  
                        },
                        {
                        key: '40\'HQ',
                        value: 67,
                        },
                        {
                        key: '45\'HQ',
                        value: 75,
                        }]*/

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
		trackShipmentInfo() {
			if (!this.checkTrackingNumber && this.editItem.mbl_num !== "") {
				this.checkTrackingNumber = true;
				this.mblTrackFail = false;
				this.mblTracked = false;
				let mbl_num = this.editItem.mbl_num;
				this.$store
					.dispatch("booking/checkMblNumber", mbl_num)
					.then((r) => {
						if (
							typeof r.data !== "undefined" &&
							typeof r.data.containers !== "undefined"
						) {
							this.containerInfo = r.data.containers;
							this.mblNumInfo = r.data;
							this.mblTracked = true;
							this.mblTrackFail = false;
							this.trackInfoData = {
								eta: this.mblNumInfo.attributes.pod_eta_at == null
										? this.mblNumInfo.attributes.pod_ata_at
										: this.mblNumInfo.attributes.pod_eta_at,
								etd: this.mblNumInfo.attributes.pol_etd_at == null
										? this.mblNumInfo.attributes.pol_atd_at
										: this.mblNumInfo.attributes.pol_etd_at
									,
								location_from: this.mblNumInfo.attributes.port_of_lading_name,
								location_to: this.mblNumInfo.attributes.port_of_discharge_name,
								carrier: this.mblNumInfo.attributes.shipping_line_short_name,
								vessel: this.mblNumInfo.attributes.pod_vessel_name,
								voyage_number: this.mblNumInfo.attributes.pod_voyage_number,
								terminal_name: this.mblNumInfo.terminal_name,
							};
							if (this.mblNumInfo.is_already_exists.is_already_exists == true) {
								if (
									this.mblNumInfo.is_already_exists.type &&
									this.mblNumInfo.is_already_exists.type == "LCL"
								) {
									this.lclShipmentDialog = true;
								} else {
									this.shipmentExitDialog = true;
									this.shipmentExistId = this.mblNumInfo.is_already_exists.shipment_id;
								}
							}
							this.selectMode(mbl_num);
							this.setCargoDate(this.trackInfoData.etd);
						} else {
							this.mblTrackFail = true;
						}
						this.checkTrackingNumber = false;
					})
					.catch((e) => {
						console.log("e", e);
						this.mblTrackFail = true;
						this.checkTrackingNumber = false;
					});
			}
		},
		setPurchaseOrderOptions() {
			let { selectedOrderIds } = this;
			this.purchaseOrderId++;

			selectedOrderIds = [];

			//get all selected purchase order id
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
		setupPurchaseOrderOptions() {
			// let newOptions = []
			// let newOptionsMap = []
			this.purchaseOrderItems.map((poi, key) => {
				this.purchaseOrderItems[key].purchase_order_id = poi.id;
				this.purchaseOrderItems[key].products.map((po, keySecond) => {
					this.purchaseOrderItems[key].products[keySecond].cargo_ready_date =
						poi.cargo_ready_date;
				});

				this.purchaseOrderItems[key].purchase_order_id = poi.id;
				/*
                options.map(o => {
                    if ( parseInt(o.id) === parseInt(poi.purchase_order_id) && newOptionsMap.indexOf(o.id)==-1) {
                        newOptionsMap.push(o.id)
                        newOptions.push(o)
                    }
                })*/
				if (!this.getMarkingBookedExternal || !this.getEditingShipment) {
					this.purchaseOrderOptions = _.filter(
						this.purchaseOrderOptions,
						(e) => e.status === "pending" || e.status === "partially_shipped"
					);
				}

				this.purchaseOrderItems[
					key
				].purchase_order_options = this.purchaseOrderOptions;
			});
		},
		calculateTotalCartons(po) {
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
			//if there are products then sum all cartons
			if (newProducts.length > 0) {
				newProducts.map((np) => {
					total += parseInt(np.carton);
				});
			}

			return isNaN(total) ? 0 : total;
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
		updateEtdInput(value) {
			if (moment(value).isValid())
				this.editItem.etdDate = moment(value).format("YYYY-MM-DD HH:i:s");
			else {
				this.editItem.etdDate = moment().format("YYYY-MM-DD HH:i:s");
			}
			this.menuEtd = false;
		},
		updateEtaInput(value) {
			if (moment(value).isValid())
				this.editItem.etaDate = moment(value).format("YYYY-MM-DD");
			else this.editItem.etaDate = moment().format("YYYY-MM-DD HH:i:s");
			this.menuEta = false;
		},
		updateEtd(value) {
			this.editItem.etd = moment(value).format("MM-DD-YYYY");
			this.menuEtd = false;
		},
		updateEta(value) {
			this.editItem.eta = moment(value).format("MM-DD-YYYY");
			this.menuEta = false;
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
				newProducts = _.filter(
					products,
					(e) => typeof e.addSpecial == "undefined"
				);
				po.selectAll = true;
			}
			return newProducts;
		},
		checkMarkExternalValidation() {
			let valid = false;
			if (
				this.editItem.booking_number != null &&
				this.editItem.booking_number != ""
			)
				valid = true;

			if (!valid) {
				if (this.editItem.mbl_num != null && this.editItem.mbl_num != "")
					valid = true;
			}

			if (!valid) {
				if (
					moment(this.editItem.etd).isValid() &&
					moment(this.editItem.eta).isValid()
				)
					valid = true;
			}
			return valid;
		},
		updateShipment() {
			if (this.$refs[this.reference].validate()) {
				let payload = this.editItem;
				this.updateLoading = true;
				payload.purchase_orders = this.purchaseOrderItems;
				payload.containers = this.containerItems;
				let supplier_timestamps = [];

				if (payload.purchase_orders.length > 0) {
					payload.purchase_orders.map(() => {
						supplier_timestamps.push(new Date().getTime());
					});
				}

				payload.booking_num = payload.booking_number;
				payload.date_id = new Date().getTime();
				payload.supplier_timestamps = supplier_timestamps;

				//if mark book external then proceed with different api call pthen than edit shipment
				if (this.getMarkingBookedExternal) {
					//format dates

					if (payload.mbl_num != null && payload.mbl_num != "") {
						payload.eta = "";
						payload.etd = "";
					} else {
						payload.eta = moment(this.editItem.eta).format("YYYY-MM-DD");
						payload.etd = moment(this.editItem.etd).format("YYYY-MM-DD");
					}

					//this.updateLoading = true
					this.markBookExternal(payload)
						.then(() => {
							this.updateLoading = false;
							this.notificationSuccess(
								"Marked External Shipment successfully."
							);
							this.fetchShipmentDetails();
							setTimeout(() => {
								window.location.reload();
								this.close();
							}, 4000);
						})
						.catch((e) => {
							console.log(e);
						});
				} else {
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
			} else {
				this.notificationError(
					" Please make sure that there is no error in form."
				);
			}
		},
		addAllCartons(item, key) {
			//set product key of set carton
			let productKey = this.purchaseOrderItems[key].products.indexOf(item);

			if (!item.addAll) {
				this.purchaseOrderItems[key].products[productKey].carton =
					item.unship_cartons;
			} else {
				this.purchaseOrderItems[key].products[productKey].carton = 0;
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
						this.purchaseOrderItems[key].products.push({
							product_id: poi.product_id,
							addAll: false,
							cartonShow: true,
							carton: 0,
							unit: 0,
							weight: poi.weight == null ? 0 : poi.weight,
							volume: poi.volume == null ? 0 : poi.volume,
							unit_price: poi.unit_price,
							units_per_carton: poi.units_per_carton,
							amount: 0,
							unship_cartons: poi.unship_cartons,
							cargo_ready_date: item.cargo_ready_date,
							action: "",
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
		},
		updateCarton(item, key) {
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
			this.purchaseOrderItems[key].products[productKey].carton = divide_carton;
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
					if (entity === "volume") {
						total = parseFloat(total) + parseFloat(np[entity]);
					} else {
						total = parseInt(total) + parseInt(np[entity]);
					}
				});
			}

			if (total === "00.00") total = "0.00";

			//finalize total volume and format to two decimal places
			if (entity === "volume") {
				total = parseFloat(total).toFixed(2);
			}

			return isNaN(total) ? (entity === "volume" ? "0.00" : 0) : total;
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
		calculateTotal(key) {
			//calculate total
			let total = 0;

			//by iterating the purchase order products we multiply each of them using the formula
			// purchase order's unit price * purchase order's number of units
			this.purchaseOrderItems[key].products.map((p) => {
				total += parseFloat(p.unit_price) * parseFloat(p.unit);
			});
			//prepend $ for display usage
			return `${isNaN(total) ? "$0.00" : "$" + total.toFixed(2)}`;
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
			const searchText = queryText.toLowerCase();

			//do the searching and return the index of the result
			//if no result then return with -1
			return data.indexOf(searchText) > -1;
		},
		customFilterProduct(item, queryText, itemText) {
			const data =
				item.product.category_sku +
				item.product.category_id +
				item.product.name.toLowerCase() +
				item.product.sku +
				itemText.toLocaleLowerCase();
			const searchText = queryText.toLowerCase();
			return data.indexOf(searchText) > -1;
		},
		getImgUrl(pic) {
			//get image url directory from po online
			let imageUrl = "https://po.shifl.com/storage/";

			//if pic is not null and defined
			if (typeof pic !== "undefined" && pic !== null) {
				if (pic.includes(imageUrl) !== "undefined" && !pic.includes(imageUrl)) {
					//concatonate the imageurl with the pic
					let newImage = `${imageUrl}${pic}`;
					return newImage;
				} else return pic;
			} else return require("../../../assets/icons/default-product-icon.svg");
		},
		updateProductPurchaseOrder(options, item, key) {
			//if we select a product from product options
			//then let's set it's meta values to other corresponding field in the row of product table
			//getting the index of the selected purchase order product
			let findIndex = this.purchaseOrderItems[key].products.indexOf(item);

			//check against the available product options and then match it against the item's product id
			let findProduct = _.find(options, (e) => e.product_id == item.product_id);

			//if we found it against the purchase order products options then let set the other relevant data now to other row item of that product
			// e.g carton, unit, units per carton,
			if (typeof findProduct !== "undefined") {
				this.purchaseOrderItems[key].products[findIndex].carton = 0;
				this.purchaseOrderItems[key].products[findIndex].unit = 0;
				this.purchaseOrderItems[key].products[findIndex].units_per_carton =
					findProduct.units_per_carton;
				this.purchaseOrderItems[key].products[findIndex].weight =
					findProduct.weight == null ? 0 : findProduct.weight;
				this.purchaseOrderItems[key].products[findIndex].volume =
					findProduct.volume == null ? 0 : findProduct.volume;
				this.purchaseOrderItems[key].products[findIndex].unship_cartons =
					findProduct.unship_cartons;
				//this.purchaseOrderItems[key].products[findIndex].unit = findProduct.units
				this.purchaseOrderItems[key].products[findIndex].unit_price =
					findProduct.unit_price;
				this.purchaseOrderItems[key].products[findIndex].meta = findProduct;
				this.purchaseOrderItems[key].products[
					findIndex
				].cargo_ready_date = this.purchaseOrderItems[key].cargo_ready_date;
				this.getProductsDescriptions();
			}
		},
		updateProducts(item, key) {
			this.purchaseOrderItems[key].selectAll = false;
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
						this.purchaseOrderItems[key].product_options = res.data;
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
								},
							];
						}
					})
					.catch((e) => {
						console.log(e);
					});
			} else {
				this.purchaseOrderItems[key].product_options = [];
				this.purchaseOrderItems[key].products = [];
			}

			//always clear the checkbox when channging po
			this.purchaseOrderItems[key].selectAll = false;
		},
		getWarehouseName(item) {
			return typeof item.warehouse !== "undefined" &&
				item.warehouse !== null &&
				typeof item.warehouse.name !== "undefined"
				? item.warehouse.name
				: "N/A";
		},
		updatePurchaseOrder(index) {
			this.purchaseOrderItems[index].products = [];
		},
		removeContainer(item) {
			let getIndex = this.containerItems.indexOf(item);
			this.containerItems.splice(getIndex, 1);
		},
		addContainer() {
			this.containerItems.push({
				id: new Date().getTime(),
				container_num: "",
				size: "",
				cbm: null,
				kg: null,
				cartons: 0,
				seal_num: null,
			});
		},
		formatDate(value) {
			return moment(value).format("MMM DD, YYYY");
		},
		getPoStatus(value) {
			return value === "pending" ? "Pending" : "Partially Booked";
		},
		removePurchaseOrderItem(key) {
			this.purchaseOrderItems.splice(key, 1);
			this.getProductsDescriptions();
			this.checkOrdersButton();
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
			});
			this.checkOrdersButton();
		},
		removeProductFromPurchaseOrders(key, item) {
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
				unit_price: 0,
				units_per_carton: 0,
				amount: 0,
				id: this.productId,
				action: "",
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
					units_per_carton: 0,
					unit_price: 0,
				});

				getProducts = _.filter(
					getProducts,
					(e) => typeof e.special == "undefined"
				);
				getProducts.push({
					special: true,
					action: "",
					amount: 0,
					carton: 0,
					unit: 0,
					units_per_carton: 0,
					unit_price: 0,
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
			this.$emit("update:refNumber", this.shiflShipmentRef);
		},
		addBulkShipmentSuccess(counter) {
			this.$emit("update:addBulkShipmentDialogModalView", true);
			this.$emit("update:shipmentCounter", counter);
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
		reloadShipments() {
			this.$emit("reloadShipments");
		},
		addShipment() {
			if (this.$refs[this.reference].validate()) {
				let payload = this.editItem;
				payload.purchase_orders = this.purchaseOrderItems;
				payload.containers = this.containerItems;

				payload.eta = this.trackInfoData ? moment(this.trackInfoData.eta).format('YYYY-MM-DD') : ''
				payload.etd = this.trackInfoData ? moment(this.trackInfoData.etd).format('YYYY-MM-DD') : ''

				let totalCartons = this.calculateOverAllTotal("carton");
				let totalVolumes = this.calculateOverAllTotal("volume");
				let totalWeights = this.calculateOverAllTotal("weight");

				let supplier_timestamps = [];
				if (payload.purchase_orders.length > 0) {
					payload.purchase_orders.map(() => {
						supplier_timestamps.push(new Date().getTime());
					});
				}
				payload.date_id = new Date().getTime();
				payload.supplier_timestamps = supplier_timestamps;

				payload.total_weight = totalWeights;
				payload.total_volume = totalVolumes;
				payload.total_cartons = totalCartons;
				payload.containerInfo = this.containerInfo;
				payload.location_from = this.trackInfoData ? this.trackInfoData.location_from : '';
				payload.location_to = this.trackInfoData ? this.trackInfoData.location_to : '';

				if (payload.mbl_num) {
					//create now shipment
					this.createShipment(payload)
						.then((response) => {
							if(response.data && response.data.is_already_exists && response.data.is_already_exists.is_already_exists === false) {
								this.mblTrackFail = true;
							} else if(response.data && response.data.is_already_exists && response.data.is_already_exists.is_already_exists === true && response.data.terminal_name !== null) {
								this.shipmentExitDialog = true;
							} else {
								this.close();
								this.shiflShipmentRef = response.data.shipment ? response.data.shipment.shifl_ref : '';
								this.addShipmentSuccess();
								this.reloadShipments();
							}
						})
						.catch((e) => {
							console.log(e);
						});
				} else if (!this.ShipmentsTracking) {
					this.missingMblNumberDialog = true;
				} else {
					payload.location_from = this.ShipmentsTrackingData.from;
					payload.location_to = this.ShipmentsTrackingData.to;
					payload.eta = this.ShipmentsTrackingData.eta;
					payload.etd = this.ShipmentsTrackingData.etd;
					payload.mode = this.ShipmentsTrackingData.mode;
					payload.type = this.ShipmentsTrackingData.type;
					payload.inco_term = this.ShipmentsTrackingData.incoTerm;
					payload.terminal = this.ShipmentsTrackingData.terminal;
					payload.vessel = this.ShipmentsTrackingData.vessel;
					payload.voyage_number = this.ShipmentsTrackingData.voyage;
					payload.carrier_id = this.ShipmentsTrackingData.carrier;

					this.createShipmentWithoutMblNumber(payload)
						.then((response) => {
							this.close();
							this.shiflShipmentRef = response.data.shipment ? response.data.shipment.shifl_ref : '';
							this.addShipmentSuccess();
							this.reloadShipments();
						})
						.catch((e) => {
							console.log(e);
						});
				}
			} else {
				this.notificationError(
					" Please make sure that there is no error in form."
				);
			}
		},
		continueWithoutMbl() {
			this.trackShipmentWithoutMblNumberDialog = true;
		},
		closeTrackShipmentWithoutMblNumberDialog() {
			this.trackShipmentWithoutMblNumberDialog = false;
		},
		AddMbl() {
			this.missingMblNumberDialog = false;
		},
		notificationError(message) {
			iziToast.error({
				title: "Error",
				message: "An unexpected error occured. Please try again." + message,
				displayMode: 1,
				position: "bottomCenter",
				timeout: 3000,
			});
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
			if (!this.isMobile) {
				this.$refs[this.sidebarItems[findIndex].reference].scrollIntoView({
					block: "start",
					behavior: "smooth",
				});
			}
		},
		clickOutside() {
			this.$emit("close");
		},
		close() {
			this.$emit("close");
		},
		trackShipment(item) {
			if (item.from && item.to) {
				this.ShipmentsTracking = true;
				this.ShipmentsTrackingData = item;
			} else {
				this.ShipmentsTracking = false;
			}
			this.missingMblNumberDialog = false;
		},
		removePurchaseOrderManualItem(key) {
			this.purchaseOrderItems.splice(key, 1);
			this.checkOrdersButton();
		},
		async getDefaultOrderType(orderType) {
			this.ordersLoading = true;

			let payload = {
				orderType: orderType,
				customer_id: this.userDetails.default_customer.id,
			};

			this.purchaseOrderItems.map((poi, key) => {
				this.purchaseOrderItems[key].purchase_order_options = [];
			});

			if (this.defaultBtnDisable !== true) {
				await this.fetchOrders(payload);
			}

			this.purchaseOrderItems.map((poi, key) => {
				this.defaultOrderType = orderType;
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
		addManualOrderItem() {
			this.purchaseOrderItems.push({
				id: new Date().getTime(),
				products: [],
				supplier_id: null,
				buyer_id: null,
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
		viewShipment() {
			this.$router.push(`/shipment/${this.shipmentExistId}`);
		},
		continueLclShipment() {
			console.log("continueLclShipment");
		},
		closeLclShipmentDialog() {
			console.log("closeLclShipmentDialog");
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
				this.defaultOrderType = null;
			}
		},
		selectMode(mblNum) {
			let startsWith = mblNum.charAt(0);
			let isLetter = this.checkIsLetter(startsWith);
			if(isLetter === true) {
				this.defaultTerminalMode = "Ocean";
				this.editItem.mode = "Ocean";
			} else {
				this.defaultTerminalMode = "Air";
				this.editItem.mode = "Air";
			}
		},
		checkIsLetter(c) {
			return c.toLowerCase() != c.toUpperCase();
		},
		changeSelectedMode(modeValue) {
			if(modeValue === 'Ocean') {
				this.editItem.mode = "Ocean";
			}
			if(modeValue === 'Air') {
				this.editItem.mode = "Air";
			}
			this.shipmentModeConfirmation = false;
		},
		checkSelectedMode(cValue) {
			if(this.editItem.mbl_num && this.defaultTerminalMode !== cValue) {
				this.shipmentModeConfirmation = true;
			}
		},
		setCargoDate(etd) {
			var today = new Date(etd);
			var dd = String(today.getDate()).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = today.getFullYear();
			this.minCargoDate = yyyy + '-' + mm + '-' + dd;
		},
		addSingleShipment() {
			this.multiShipmentForm = false;
		},
		addMultiShipment() {
			this.multiShipmentForm = true;
		},
	},
};
</script>
