<!-- @format -->

<template>
	<v-dialog
		v-model="dialogCreate"
		max-width="1392px"
		content-class="review-order-dialog"
		:retain-focus="false"
		persistent
		scrollable
	>
		<v-card class="po-dialog-card">
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
				<button icon dark class="btn-close" @click="close">
					<v-icon>mdi-close</v-icon>
				</button>
			</v-card-title>

			<v-card-text>
				<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
					<div class="create-wrapper">
						<div class="po-info">
							<div class="review-section-1">
								<div class="po-vendor mb-3">
									<p class="po-title">Vendor</p>

									<v-text-field
										v-if="poItem.supplier_id == null"
										readonly
										type="text"
										height="40px"
										color="#002F44"
										width="200px"
										dense
										v-model="poItem.customer_company_name"
										class="select-items"
										outlined
										hide-details="auto"
										disabled
									>
									</v-text-field>
									<v-autocomplete
										v-else
										v-model="poItem.supplier_id"
										class="select-items"
										:items="vendorListOptions"
										placeholder="Select Vendor"
										item-text="label"
										item-value="value"
										outlined
										hide-details="auto"
										:menu-props="{
											bottom: true,
											offsetY: true,
										}"
										disabled
										@change="fetchVendorSkuOnChange(poItem.supplier_id)"
									>
									</v-autocomplete>
								</div>

								<div class="po-ship mb-3">
									<p class="po-title">Ship To</p>
									<v-text-field
										v-model="poItem.ship_to"
										class="select-items"
										:items="shipLists"
										placeholder="Select Warehouse/Address"
										outlined
										hide-details="auto"
										:disabled="!poItem.is_issuer"
										@input="changeRequest"
										:class="
											checkFeildExists('ship_to', poItem.change_log)
												? 'change-log'
												: ''
										"
									>
									</v-text-field>
								</div>

								<div class="po-ship-details mb-3">
									<div class="ship-details-wrapper">
										<p class="po-title">Incoterm</p>
										<v-select
											class="select-items"
											:items="shipmentTerms"
											item-text="name"
											item-value="name"
											placeholder="Enter Term"
											outlined
											hide-details="auto"
											v-model="poItem.terms"
											:menu-props="{
												bottom: true,
												offsetY: true,
											}"
											@input="changeRequest"
											:class="
												checkFeildExists('terms', poItem.change_log)
													? 'change-log'
													: ''
											"
										>
										</v-select>
									</div>

									<div class="ship-details-wrapper">
										<p class="po-title">Payment Term</p>
										<v-select
											class="select-items"
											:items="[
												'Advance Payment',
												'Payment on receipt',
												'Net 30',
												'Net 60',
												'Net 90',
											]"
											item-text="label"
											item-value="value"
											placeholder="Enter Term"
											outlined
											hide-details="auto"
											v-model="poItem.payment_term"
											:menu-props="{
												bottom: true,
												offsetY: true,
											}"
											@input="changeRequest"
											:class="
												checkFeildExists('payment_term', poItem.change_log)
													? 'change-log'
													: ''
											"
										>
										</v-select>
									</div>
								</div>
								<div class="po-ship-details required-deposit mb-3">
									<div class="ship-details-wrapper detail-1-info">
										<p class="po-title">Required Deposite</p>
										<v-select
											class="select-items"
											:items="requireDeposite"
											v-model="poItem.required_deposit_type"
											item-text="name"
											item-value="value"
											placeholder=""
											outlined
											hide-details="auto"
											:menu-props="{
												bottom: true,
												offsetY: true,
											}"
											:disabled="poItem.is_issuer"
											@input="changeRequest"
										>
										</v-select>
									</div>

									<div class="ship-details-wrapper detail-2-amount">
										<v-text-field
											class="select-items deposite"
											placeholder=""
											v-model.number="poItem.required_deposit_value"
											outlined
											hide-details="auto"
											:disabled="poItem.is_issuer"
											@input="changeRequest"
										>
										</v-text-field>
									</div>
								</div>
							</div>

							<div class="review-section-2">
								<div class="po-vendor mb-3">
									<p class="po-title">
										Import Name
										<span style=" text-transform: capitalize !important;"
											>(Optional)</span
										>
									</p>

									<v-autocomplete
										v-model="poItem.import_name_id"
										:items="importNames"
										class="select-items"
										placeholder="Select Import Name"
										item-text="label"
										item-value="value"
										outlined
										hide-details="auto"
										:menu-props="{
											bottom: true,
											offsetY: true,
										}"
										:disabled="
											poItem.status === 'partial_shipped' ||
												poItem.status === 'partially shipped' ||
												poItem.status === 'partial shipped'
										"
										v-if="poItem.is_issuer"
										@input="changeRequest"
									>
									</v-autocomplete>
									<v-text-field
										v-model="poItem.import_name"
										outlined
										class="select-items text-left"
										hide-details="auto"
										v-else
										disabled
									>
									</v-text-field>
								</div>
								<div class="po-ship-details mb-3">
									<div class="ship-details-wrapper">
										<p class="po-title">Ship Via</p>
										<v-select
											class="select-items"
											:items="['Ocean', 'Air', 'Truck']"
											item-text="label"
											item-value="value"
											placeholder="Ship Via"
											outlined
											hide-details="auto"
											v-model="poItem.ship_via"
											:menu-props="{
												bottom: true,
												offsetY: true,
											}"
											:disabled="!poItem.is_issuer"
											@input="changeRequest"
											:class="
												checkFeildExists('ship_via', poItem.change_log)
													? 'change-log'
													: ''
											"
										>
										</v-select>
									</div>

									<div class="ship-details-wrapper">
										<p class="po-title">Units of Measurement</p>
										<v-select
											class="select-items"
											:items="['CBM/KG']"
											item-text="label"
											item-value="value"
											outlined
											hide-details="auto"
											disabled
											v-model="poItem.unit_of_measurement"
											:menu-props="{
												bottom: true,
												offsetY: true,
											}"
										>
										</v-select>
									</div>
								</div>

								<div class="po-ship-details mb-3">
									<div class="ship-details-wrapper">
										<p class="po-title">Original Cargo Ready Date</p>
										<v-text-field
											type="date"
											class="text-fields select-items"
											placeholder="Select Date"
											outlined
											hide-details="auto"
											v-model="poItem.cargo_ready_date"
											:disabled="!poItem.is_issuer"
											@input="changeRequest"
											:class="
												checkFeildExists('cargo_ready_date', poItem.change_log)
													? 'change-log'
													: ''
											"
										/>
									</div>

									<div class="ship-details-wrapper">
										<p class="po-title">
											Must Arrive By
											<span style=" text-transform: capitalize !important;"
												>(Optional)</span
											>
										</p>
										<v-text-field
											type="date"
											class="text-fields select-items"
											placeholder="Select Date"
											outlined
											hide-details="auto"
											v-model="poItem.must_arrive_by"
											:class="
												checkFeildExists('must_arrive_by', poItem.change_log)
													? 'change-log'
													: ''
											"
											@input="changeRequest"
										/>
									</div>
								</div>

								<div class="po-cargo mb-3">
									<p class="po-title">Committed Cargo Ready Date</p>
									<v-text-field
										type="date"
										class="text-fields select-items"
										placeholder="Select Date"
										outlined
										hide-details="auto"
										v-model="poItem.committed_cargo_ready_date"
										disabled
										@input="changeRequest"
									/>
								</div>
							</div>

							<div class="review-section-3">
								<div class="po-number mb-3">
									<p class="po-title">Po Number</p>

									<span
										style="font-family: 'Inter-Medium', sans-serif; color: #4a4a4a;"
									>
										{{ poItem.po_number }}
									</span>
								</div>
								<div class="po-notes">
									<p class="po-title">
										Notes
										<span
											style="
											text-transform: capitalize !important;
										"
											>(Optional)</span
										>
									</p>
									<v-textarea
										class="note"
										outlined
										v-model="poItem.notes"
										name="input-7-4"
										placeholder="Type your notes here..."
										hide-details="auto"
										:class="
											checkFeildExists('notes', poItem.change_log)
												? 'change-log'
												: ''
										"
									>
									</v-textarea>
								</div>
							</div>
						</div>

						<div class="product-info">
							<v-data-table
								:headers="dynamicHeader"
								:items="dataProducts"
								:loading="poItem.loadingState"
								class="elevation-1 add-table"
								mobile-breakpoint="1023"
								hide-default-footer
								:items-per-page="500"
								item-key="row_id"
							>
								<template v-slot:[`item.product`]="{ item, index }">
									<div class="product-mobile-wrapper" v-if="isMobile">
										<div
											style="min-height: 36px"
											class="
												product-mobile-header
												d-flex
												justify-space-between
												align-center
											"
										>
											<p
												class="
													mb-0
													po-create-product-title
												"
											>
												Product
												{{ getProductNumber(index) }}
											</p>
											<v-btn
												v-show="dataProducts.length > 1"
												icon
												class="btn remove-btn"
												@click="removeRow(item)"
											>
												<v-icon>mdi-close</v-icon>
											</v-btn>
										</div>

										<!-- v-model="poItem.products[index].id" -->
											<!-- :class="isProductFieldError(item.id, index)" -->
										<v-autocomplete
											v-model="item.id"
											spellcheck="false"
											:filter="customFilter"
											class="select-product"
											@change="updateProduct(index)"
											:items="productSkuDropDownItems"
											:placeholder="getVendorSkusLoading ? 'Fetching Products':'Select SKU'"
											outlined
											:disabled="getVendorSkusLoading"
											item-text="name"
											item-value="id"
											:menu-props="{
												contentClass: 'product-lists-items',
												bottom: true,
												offsetY: true,
											}"
											:rules="rules"
											hide-details="auto"
											clearable
											@input="changeProductRequest(index)"
										>
											<template
												v-slot:selection="{
													item,
													index,
												}"
											>
												<div
													class="
														v-select__selection
														v-select__selection--comma
													"
													:key="index"
												>
													#{{ item.category_sku }}
													<span
														v-if="item.status === 'deleted'"
														style="color: #ff5252"
													>
														(Deleted)
													</span>

													<br />
													{{ item.name }}
												</div>
											</template>

											<template v-slot:item="{ item }">
												<div class="option-items">
													<div class="sku-item">
														<div class="sku-details">
															<span>#{{ item.category_sku }}</span>
														</div>

														<div>
															<p>
																{{ item.name }}
															</p>
															<p
																style="
																	color: #6d858f !important;
																	font-family: 'Inter-Medium',
																		sans-serif;
																"
															>
																${{
																	item.unit_price !== null
																		? item.unit_price.toFixed(2)
																		: "0.00"
																}}
															</p>
														</div>
													</div>

													<div class="image-item">
														<img
															:src="getImgUrl(item.image)"
															height="60px"
															width="60px"
															alt=""
														/>
													</div>
												</div>
											</template>
										</v-autocomplete>

										<span
											class="error-message"
											style="font-size: 12px; color: red"
										>
											<!-- {{ isProductSelected(item.id, index) }} -->
										</span>
									</div>

									<!-- v-model="poItem.products[index].id" -->
											<!-- :class="isProductFieldError(item.id, index)" -->
									<div v-if="!isMobile">
										<v-autocomplete
											@change="updateProduct(index)"
											v-model="item.id"
											spellcheck="false"
											:filter="customFilter"
											class="select-product"
											:items="productSkuDropDownItems"
											:placeholder="getVendorSkusLoading ? 'Fetching Products':'Select SKU'"
											outlined
											item-text="name"
											item-value="id"
											:menu-props="{
												contentClass: 'product-lists-items',
												bottom: true,
												offsetY: true,
											}"
											:rules="rules"
											hide-details="auto"
											clearable
											v-if="poItem.is_issuer"
											:disabled="getVendorSkusLoading"
											@input="changeProductRequest(index)"
										>
											<template
												v-slot:selection="{
													item,
													index,
												}"
											>
												<div
													class="
														v-select__selection
														v-select__selection--comma
													"
													:key="index"
												>
													#<span>{{ item.category_sku }}</span>
													<span
														v-if="item.status === 'deleted'"
														style="color: #ff5252"
													>
														(Deleted)
													</span>

													<br />
													{{ item.name }}
												</div>
											</template>

											<template v-slot:item="{ item }">
												<div class="option-items" :id="item.id">
													<div class="sku-item">
														<div class="sku-details">
															<span
																style="
																	color: #4a4a4a !important;
																"
															>
																#{{ item.category_sku }}
															</span>
														</div>
														<div>
															<p>
																{{ item.name }}
															</p>
															<p
																style="
																	color: #6d858f !important;
																	font-family: 'Inter-Medium',
																		sans-serif;
																"
															>
																${{
																	item.unit_price !== null
																		? item.unit_price.toFixed(2)
																		: "0.00"
																}}
															</p>
														</div>
													</div>
													<div class="image-item">
														<img
															:src="getImgUrl(item.image)"
															height="60px"
															width="60px"
															alt=""
														/>
													</div>
												</div>
											</template>
										</v-autocomplete>

										<v-autocomplete
											v-model="item.other_party_product_id"
											spellcheck="false"
											:filter="customFilter"
											class="select-product"
											:items="productSkuDropDownItems"
											placeholder="Select SKU"
											outlined
											item-text="name"
											item-value="id"
											:menu-props="{
												contentClass: 'product-lists-items',
												bottom: true,
												offsetY: true,
											}"
											hide-details="auto"
											clearable
											v-if="!poItem.is_issuer"
											@input="changeProductRequest(index)"
										>
											<template
												v-slot:selection="{
													item,
													index,
												}"
											>
												<div
													class="
														v-select__selection
														v-select__selection--comma
													"
													:key="index"
												>
													#<span>{{ item.category_sku }}</span>
												</div>
											</template>

											<template v-slot:item="{ item }">
												<div class="option-items" :id="item.id">
													<div class="sku-item">
														<div class="sku-details">
															<span
																style="
																	color: #4a4a4a !important;
																"
															>
																#{{ item.category_sku }}
															</span>
														</div>
														<div>
															<p>
																{{ item.name }}
															</p>
															<p
																style="
																	color: #6d858f !important;
																	font-family: 'Inter-Medium',
																		sans-serif;
																"
															>
																${{
																	item.unit_price !== null
																		? item.unit_price.toFixed(2)
																		: "0.00"
																}}
															</p>
														</div>
													</div>
													<div class="image-item">
														<img
															:src="getImgUrl(item.image)"
															height="60px"
															width="60px"
															alt=""
														/>
													</div>
												</div>
											</template>
										</v-autocomplete>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											<!-- {{ isProductSelected(item.id, index) }} -->
										</span>

										<span
											class="error-message"
											style="
												font-size: 11px;
												color: white;
											"
											v-if="checkBothErrorUnitsAndCartons(item)"
										>
											.
										</span>
									</div>
								</template>

								<template v-slot:[`item.vendor_sku`]="{ item }">
									<div
										class="product-sku-name h-100"
										:class="item.category_sku"
									>
										<v-text-field
											outlined
											readonly
											:value="
												`${
													item.other_party_product_sku != undefined
														? '#' + item.other_party_product_sku
														: '--'
												}`
											"
											class="text-left"
											hide-details="auto"
											disabled
										>
										</v-text-field>
									</div>
								</template>

								<template v-slot:[`item.vendor_sku_description`]="{ item }">
									<div class="product-sku-name h-100">
										<v-textarea
											outlined
											:value="`#${item.category_sku} ${item.name} `"
											readonly
											auto-grow
											rows="2"
											class="text-left table-text-fields"
											hide-details="auto"
											disabled
										>
										</v-textarea>
									</div>
								</template>
								<template v-slot:[`item.ship_date`]="{ item }">
									<div class="po-ship-date-and-time-wrapper">
											<div class="po-eta">
												<v-text-field
													type="date"
													class="table-text-fields" 
													placeholder="Select Date" 
													outlined
													hide-details="auto"
													v-model="item.ship_date"
												/>
											</div>
										</div>
								</template>

								<template v-slot:[`item.volume`]="{ item, index }">
									<div class="h-100">
										<v-text-field
											v-model="item.volume"
											type="number"
											placeholder="0"
											outlined
											:rules="rules"
											class="table-text-fields"
											hide-details="auto"
											:disabled="item.isDisabled"
											suffix="CBM"
											min="1"
											:class="
												checkFeildExists('volume', item.change_log)
													? 'change-log'
													: ''
											"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
											@input="changeProductRequest(index)"
										>
										</v-text-field>
									</div>
								</template>

								<template v-slot:[`item.weight`]="{ item, index }">
									<div class="h-100">
										<v-text-field
											v-model="item.weight"
											type="number"
											placeholder="0"
											outlined
											:rules="rules"
											class="table-text-fields"
											hide-details="auto"
											:disabled="item.isDisabled"
											:class="
												checkFeildExists('weight', item.change_log)
													? 'change-log'
													: ''
											"
											min="1"
											suffix="KG"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
											@input="changeProductRequest(index)"
										>
										</v-text-field>
									</div>
								</template>

								<template
									v-slot:[`item.carton_count`]="{
										item,
										index,
									}"
								>
									<div class="h-100">
										<v-text-field
											type="number"
											@input="updateCartonCount(index)"
											v-model="item.carton_count"
											placeholder="0"
											outlined
											class="table-text-fields"
											hide-details="auto"
											:disabled="item.isDisabled"
											:class="
												cartonClassError(item)
													? 'has-error'
													: checkFeildExists('quantity', item.change_log)
													? 'change-log'
													: ''
											"
											min="1"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
										>
										</v-text-field>

										<!-- error if product duplicates -->
										<span class="error-message" style="color: white">
											<!-- {{ isProductSelectedOtherFieldsError(item.id, index) }} -->
										</span>

										<!-- error for updating carton count less than what is shipped -->
										<span class="error-message" style="visibility: hidden">
											{{ isCartonCountError(index) }}
										</span>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											<!-- v-if="checkShowErrorMessageCartons(item)" - only shows upon clicking save -->

											{{
												typeof item.cartonErrorMessage !== "undefined" &&
												item.cartonErrorMessage !== null
													? item.cartonErrorMessage
													: ""
											}}
										</span>

										<!-- error if it detects that units has error -->
										<span
											class="error-message"
											style="
												font-size: 11px;
												color: white;
											"
											v-if="checkBothErrorUnitsAndCartons(item)"
										>
											.
										</span>
									</div>
								</template>

								<template v-slot:[`item.in_each`]="{ item, index }">
									<div class="h-100">
										<v-text-field
											type="number"
											placeholder="0"
											@input="updateCartonCount(index)"
											v-model="item.units_per_carton"
											outlined
											class="table-text-fields"
											hide-details="auto"
											:disabled="item.isDisabled"
											:class="
												checkFeildExists('units_per_carton', item.change_log)
													? 'change-log'
													: ''
											"
											min="1"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
										>
										</v-text-field>

										<!-- error if product duplicates -->
										<span class="error-message" style="color: white">
											<!-- {{ isProductSelectedOtherFieldsError(item.id, index) }} -->
										</span>

										<!-- error for updating units less than what is shipped -->
										<span class="error-message" style="visibility: hidden">
											{{ isUnitCountError(index) }}
										</span>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											<!-- v-if="checkShowErrorMessageUnits(item)" - only shows upon clicking save -->
											{{
												typeof item.unitErrorMessage !== "undefined" &&
												item.unitErrorMessage !== null
													? item.unitErrorMessage
													: ""
											}}
										</span>

										<!-- error if it detects that carton has error -->
										<span
											class="error-message"
											style="
												font-size: 11px;
												color: white;
											"
											v-if="checkBothErrorUnitsAndCartons(item)"
										>
											.
										</span>
									</div>
								</template>

								<template v-slot:[`item.units`]="{ item, index }">
									<div class="h-100">
										<v-text-field
											type="number"
											v-model="item.units"
											placeholder="0"
											outlined
											class="table-text-fields"
											:rules="cartonRules"
											hide-details="auto"
											:disabled="
												item.isDisabled ||
													(item.units_per_carton > 0 && item.carton_count > 0)
											"
											:class="
												unitClassError(item)
													? 'has-error'
													: checkFeildExists('units', item.change_log)
													? 'change-log'
													: ''
											"
											min="1"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
											@input="changeProductRequest(index)"
										>
										</v-text-field>

										<!-- error if product duplicates -->
										<span class="error-message" style="color: white">
											<!-- {{ isProductSelectedOtherFieldsError(item.id, index) }} -->
										</span>

										<!-- error for updating units less than what is shipped -->
										<span class="error-message" style="visibility: hidden">
											{{ isUnitCountError(index) }}
										</span>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											<!-- v-if="checkShowErrorMessageUnits(item)" - only shows upon clicking save -->
											{{
												typeof item.unitErrorMessage !== "undefined" &&
												item.unitErrorMessage !== null
													? item.unitErrorMessage
													: ""
											}}
										</span>

										<!-- error if it detects that carton has error -->
										<span
											class="error-message"
											style="
												font-size: 11px;
												color: white;
											"
											v-if="checkBothErrorUnitsAndCartons(item)"
										>
											.
										</span>
									</div>
								</template>

								<template v-slot:[`item.unit_price`]="{ item, index }">
									<div class="h-100">
										<v-text-field
											type="number"
											v-model="item.unit_price"
											placeholder="$00"
											outlined
											class="table-text-fields"
											:rules="priceRules"
											hide-details="auto"
											:disabled="item.isDisabled"
											min="1"
											:class="
												checkFeildExists('unit_price', item.change_log)
													? 'change-log'
													: ''
											"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
											@input="changeProductRequest(index)"
										>
										</v-text-field>

										<span class="error-message" style="color: white">
											<!-- {{ isProductSelectedOtherFieldsError(item.id, index) }} -->
										</span>

										<span
											class="error-message"
											style="
												font-size: 11px;
												color: white;
											"
											v-if="checkBothErrorUnitsAndCartons(item)"
										>
											.
										</span>
									</div>
								</template>

								<template v-slot:[`item.amount`]="{ item }">
									<div class="h-100">
										<v-text-field
											outlined
											:value="updateAmount(item)"
											readonly
											class="table-text-fields amount"
											hide-details="auto"
											:class="
												checkFeildExists('amount', item.change_log)
													? 'amount-change-log'
													: ''
											"
										>
										</v-text-field>

										<span
											class="error-message"
											style="
												font-size: 11px;
												color: white;
											"
											v-if="checkBothErrorUnitsAndCartons(item)"
										>
											.
										</span>
									</div>
								</template>

								<template v-slot:[`item.actions`]="{ item }">
									<v-btn
										:disabled="dataProducts.length == 1"
										:class="
											dataProducts.length > 1 ? 'remove-btn' : 'gray-remove-btn'
										"
										icon
										class="btn remove-btn"
										@click="removeRow(item)"
									>
										<v-icon>mdi-close</v-icon>
									</v-btn>
								</template>
							</v-data-table>

							<div class="signature-wrapper ">
								<div class="po-signature mb-3">
									<v-container fluid class="pl-0" v-if="!poItem.is_issuer">
										<v-checkbox
											label="Issue Proforma Invoice"
											v-model="performaInvoice"
											hide-details
											:disabled="changeRequestButton"
										></v-checkbox>
									</v-container>
									<div
										class="proforma-invoice-details d-flex "
										v-if="!changeRequestButton && !poItem.change_request_button"
									>
										<div class="signature-details-wrapper">
											<p class="po-title">
												Signature
												<span
													class="pl-1 red--text"
													v-if="performaInvoice || getSignatureDetails !== null"
													>*</span
												>
											</p>
											<v-text-field
												class="select-items"
												v-model="poItem.signature"
												placeholder="Enter name"
												outlined
												hide-details="auto"
												:rules="
													performaInvoice || getSignatureDetails !== null
														? signatureRules
														: []
												"
											>
											</v-text-field>
										</div>

										<div class="date-details-wrapper">
											<p class="po-title">
												Date
												<span
													class="pl-1 red--text"
													v-if="performaInvoice || getSignatureDetails !== null"
													>*</span
												>
											</p>
											<v-text-field
												v-model="signatureDate"
												type="date"
												class="text-fields select-items"
												placeholder="Select Date"
												outlined
												hide-details="auto"
												:rules="
													performaInvoice || getSignatureDetails !== null
														? signatureDateRules
														: []
												"
											/>
										</div>
									</div>
								</div>
								<div class="signed-by" v-if="getSignatureDetails">
									<div class="pa-4 sign-details">
										<div
											v-for="(item, index) in getSignatureDetails"
											:key="index"
										>
											<p class="sign-title mb-0">
												Signed by
												{{ item.buyer_signature ? "Buyer" : "Vendor" }}
											</p>
											<div v-if="item.vendor_signature">
												<p class="mb-0 sign-text">
													{{
														item.vendor_signature
															? item.vendor_signature
															: "N/A"
													}}
												</p>
												<p class="mb-0 sign-text">
													{{
														item.vendor_signature_date
															? item.vendor_signature_date
															: "N/A"
													}}
												</p>
											</div>
											<div v-if="item.buyer_signature">
												<p class="mb-0 sign-text">
													{{
														item.buyer_signature ? item.buyer_signature : "N/A"
													}}
												</p>
												<p class="mb-0 sign-text">
													{{
														item.buyer_signature_date
															? item.buyer_signature_date
															: "N/A"
													}}
												</p>
											</div>
										</div>
									</div>
								</div>
								<PoTotalComponent
									:items="posAll"
									:poItemData="poItem"
									:orderType="'PO'"
									:subTotal="subTotal()"
									:tax="poItem.tax"
									:shipping="poItem.shipping"
									:discount="poItem.discount"
									:total="poItem.total"
									@totalAmount="totalAmount"
									@changeButton="changeButton"
									:reviewOrder="poItem.is_issuer"
									:changeLog="poItem.change_log"
									:fromComponent="fromComponent"
									:dialog="'reviewDialog'"
									:className="getSignatureDetails ? 'signed_by' : ''"
								/>
							</div>
						</div>
					</div>
				</v-form>
			</v-card-text>

			<v-card-actions class="po-button-actions">
				<button
					:disabled="getOrdersStateLoading || getPoCreateLoading"
					class="btn-blue"
					v-if="!changeRequestButton && !poItem.change_request_button"
					@click="orderStateUpdate('accept')"
				>
					Accept Order
				</button>

				<button
					:disabled="
						getPoCreateLoading || getPoDraftLoading || !changeRequestButton
					"
					class="btn-blue"
					v-if="changeRequestButton || poItem.change_request_button"
					@click="savePo()"
				>
					Request Change
				</button>

				<button
					class="btn-white"
					:disabled="
						getPoCreateLoading || getPoDraftLoading || getOrdersStateLoading
					"
					@click="orderRejectConfirmationCall()"
				>
					<span class="red--text">
						Reject Order
					</span>
				</button>

				<button
					class="btn-white cancel"
					@click="close"
					:disabled="
						getPoCreateLoading || getPoDraftLoading || getOrdersStateLoading
					"
				>
					Cancel
				</button>
			</v-card-actions>
		</v-card>

		<ConfirmDialog :dialogData.sync="orderRejectConfirmationFlag">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2 v-if="poItem.cr_by != null">Reject Change Request</h2>
				<h2 v-else>Reject Order</h2>
			</template>

			<template v-slot:dialog_content>
				<p v-if="poItem.cr_by != null">
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
					<span v-if="poItem.cr_by != null">Reject Changes</span>
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

		<POInvalidRequest
			:dialogData.sync="dialogErrorUpdateCount"
			@close="closeWarning"
			@discardChanges="discardChanges"
		/>
	</v-dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import PoTotalComponent from "../PoTotalComponent.vue";
import globalMethods from "../../../utils/globalMethods";
import _ from "lodash";
import axios from "axios";
import moment from "moment";
import POInvalidRequest from "./POInvalidRequest.vue";
import { PERFORMA_REQUEST, CHANGE_REQUEST } from "../../../constants/states";
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";

export default {
	name: "POCreateDialog",
	props: ["dialog", "editedItems", "editedIndex", "isMobile", "fromComponent"],
	components: {
		PoTotalComponent,
		POInvalidRequest,
		ConfirmDialog,
	},
	data: () => ({
		valid: true,
		current_page: 1,
		dataProducts: [],
		radioGroup1: "generated",
		headersMobile: [
			{
				text: "QUANTITY",
				align: "start",
				sortable: false,
				value: "quantity",
				fixed: true,
				width: "90%",
			},
			{
				text: "UNIT PRICE",
				align: "end",
				sortable: false,
				value: "unit_price",
				fixed: true,
				width: "90%",
			},
			{
				text: "AMOUNT",
				align: "end",
				sortable: false,
				value: "amount",
				fixed: true,
				width: "90%",
			},
		],
		headers: [
			{
				text: "SKU",
				align: "start",
				sortable: false,
				value: "product",
				fixed: true,
				width: "25%",
				sort_order: 1,
			},
			{
				text: "Ship Date",
				align: "start",
				sortable: false,
				value: "ship_date",
				fixed: true,
				width: "13%",
				sort_order: 4,
			},
			{
				text: "Carton",
				align: "end",
				sortable: false,
				value: "carton_count",
				fixed: true,
				width: "8%",
				sort_order: 4,
			},
			{
				text: "In Each",
				align: "end",
				sortable: false,
				value: "in_each",
				fixed: true,
				width: "8%",
				sort_order: 5,
			},
			{
				text: "Unit",
				align: "end",
				sortable: false,
				value: "units",
				fixed: true,
				width: "8%",
				sort_order: 6,
			},
			{
				text: "Volume",
				align: "end",
				sortable: false,
				value: "volume",
				fixed: true,
				width: "11%",
				sort_order: 7,
			},
			{
				text: "Weight",
				align: "end",
				sortable: false,
				value: "weight",
				fixed: true,
				width: "8%",
				sort_order: 8,
			},
			{
				text: "Unit Price",
				align: "end",
				sortable: false,
				value: "unit_price",
				fixed: true,
				width: "8%",
				sort_order: 9,
			},
			{
				text: "Amount",
				align: "end",
				sortable: false,
				value: "amount",
				fixed: true,
				width: "8%",
				sort_order: 10,
			},
			{
				text: "",
				align: "end",
				sortable: false,
				value: "actions",
				fixed: true,
				width: "3%",
				sort_order: 11,
			},
		],
		vendor_SKU: [
			{
				text: "Vendor’s SKU",
				align: "start",
				sortable: false,
				value: "vendor_sku",
				fixed: true,
				width: "12%",
				sort_order: 2,
			},
		],
		vendor_SKU_Description: [
			{
				text: "Vendor’s SKU & Description",
				align: "start",
				sortable: false,
				value: "vendor_sku_description",
				fixed: true,
				width: "18%",
				sort_order: 3,
			},
		],
		items: [
			{
				id: "",
				// quantity: 0,
				carton_count: 0,
				units: 0,
				unit_price: 0,
				amount: 0,
			},
		],
		requireDeposite: [
			{
				name: "In amount",
				value: "in-amount",
			},
			{
				name: "In percentage",
				value: "in-percentage",
			},
		],
		isProductsValidData: true,
		counter: 1,
		selected: [],
		productListsDropdownData: [],
		rules: [(v) => !!v || "Input is required."],
		quantityRules: [
			(v) => !!v || "Quantity is required",
			(v) => parseFloat(v) > 0 || "Quantity should be greater than 0",
		],
		cartonRules: [
			(v) => !!v || "Carton is required",
			(v) => parseFloat(v) > 0 || "Carton should be greater than 0",
		],
		priceRules: [
			(v) => !!v || "Price is required",
			(v) => parseFloat(v) > 0 || "Price should be greater than 0",
		],
		signatureRules: [(v) => !!v || "Signature is required"],
		signatureDateRules: [(v) => !!v || "Date is required"],
		fetchingSingleProductLoading: false,
		hasProductDuplicates: false,
		dialogErrorUpdateCount: false,
		hasCartonError: false,
		cartonErrorMessage: "",
		hasTotalUnitError: false,
		totalUnitErrorMessage: "",
		changeRequestButton: false,
		performaInvoice: false,
		orderRejectConfirmationFlag: false,
		signatureDate: null,
	}),
	computed: {
		...mapGetters({
			getIsRefreshing: "getIsRefreshing",
			getWarehouse: "warehouse/getWarehouse",
			getProducts: "products/getProducts",
			getProductsPoDropdown: "products/getProductsPoDropdown",
			poBaseUrlState: "products/poBaseUrlState",
			getPoCreateLoading: "po/getPoCreateLoading",
			getPoDraftLoading: "po/getPoDraftLoading",
			getPoSaveAddLoading: "po/getPoSaveAddLoading",
			getSuppliers: "suppliers/getSuppliers",
			getVendorLists: "po/getVendorLists",
			getUser: "getUser",
			getShipmentTerms: "getShipmentTerms",
			getCurrentPoTab: "po/getCurrentPoTab",
			getAllPoPage: "po/getAllPoPage",
			getPendingPage: "po/getPendingPage",
			getShippedPage: "po/getShippedPage",
			getPoLocalQuery: "po/getPoLocalQuery",
			getImportName: "po/getImportName",
			getPoDetail: "po/getPoDetail",
			getOrdersStateLoading: "po/getOrdersStateLoading",
			getAllPos: "po/getAllPos",
			getVendorSkus:'po/getVendorSkus',
			getVendorSkusLoading:'po/getVendorSkusLoading'
		}),
		productListsDropdown: {
			get() {
				let value = this.productListsDropdownData;
				// let finalValue = [];
				// if (Array.isArray(value) && value.length > 0) {
				// 	if (
				// 		typeof this.poItem !== "undefined" &&
				// 		typeof this.poItem.products !== "undefined" &&
				// 		Array.isArray(this.poItem.products) &&
				// 		this.poItem.products.length > 0
				// 	) {
				// 		value.map((v) => {
				// 			if (v.status !== "active") {
				// 				let findProduct = _.findIndex(
				// 					this.poItem.products,
				// 					(e) => v.id === e.id
				// 				);
				// 				if (findProduct !== -1) {
				// 					finalValue.push(v);
				// 				}
				// 			} else {
				// 				finalValue.push(v);
				// 			}
				// 		});

				// 		return finalValue;
				// 	} else {
				// 		return value;
				// 	}
				// } else {
				// 	return value;
				// }
				return value
			},
			set(value) {
				this.productListsDropdownData = value;
			},
		},
		VendorSkuItems(){
			let products = []
			if(this.getVendorSkus !== undefined && this.getVendorSkus !== null && this.getVendorSkus !== 'undefined'){
				products = this.getVendorSkus	
			}else{
				products = []
			}
			return products
		},
		productSkuDropDownItems(){
			let data = []
			// if(this.poItem.customer_id !== '' && this.poItem.customer_id !== null){
				// data = this.VendorSkuItems
			// }else{
				data = this.productListsDropdown
			// }
			return data
		},
		dialogCreate: {
			get() {
				return this.dialog;
			},
			set(value) {
				if (this.editedIndex === -1) {
					this.$refs.form.resetValidation();
					this.dataProducts = this.poItem.loadingState
						? []
						: [
								{
									id: null,
									// quantity: 0,
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
									ship_date:'',
									row_id:null
								},
						  ];

					this.poItem = {
						products: [
							{
								id: null,
								// quantity: 0,
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
								ship_date:'',
								row_id:null
							},
						],
						po_number: "",
						is_system_generated: 1,
						supplier_id: "",
						customer_id: "",
						notes: "",
						created_by: "",
						tax: 0,
						warehouse_id: "",
						sub_total: "",
						shipping: 0,
						total: "",
						discount: 0,
						import_name_id: "",
					};

					this.$emit("update:dialog", value);
				}
			},
		},
		poItem: {
			get() {
				return this.editedItems;
			},
			set(value) {
				this.$emit("update:editedItems", value);
			},
		},
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
		shipmentTerms() {
			let terms = [];

			if (
				typeof this.getShipmentTerms !== "undefined" &&
				this.getShipmentTerms !== null
			) {
				terms = this.getShipmentTerms;
			}

			return terms;
		},
		formTitle() {
			return this.poItem.state != "performa_request"
				? "Review Order"
				: "Review Proforma Invoice";
		},
		dynamicHeader() {
			let dynamicHeader;
			if (this.poItem.is_issuer) {
				dynamicHeader = [...this.headers, ...this.vendor_SKU];
			} else {
				dynamicHeader = [...this.headers, ...this.vendor_SKU_Description];
			}

			return _.orderBy(dynamicHeader, ["sort_order"], ["asc"]);
		},
		vendorListOptions() {
			let newVendorLists = [];
			if (
				typeof this.getVendorLists !== "undefined" &&
				Array.isArray(this.getVendorLists) &&
				this.getVendorLists.length > 0
			) {
				newVendorLists = this.getVendorLists.map((gvl) => ({
					label: gvl.company_name,
					value: gvl.id,
				}));
			}
			return newVendorLists;
		},
		importNames() {
			let importName = this.getImportName.map((item) => ({
				label: item.import_name,
				value: item.id,
			}));
			return importName;
		},
		shipLists() {
			let shipListsData = [];

			if (
				typeof this.getWarehouse !== "undefined" &&
				this.getWarehouse !== null
			) {
				if (
					typeof this.getWarehouse.results !== "undefined" &&
					this.getWarehouse.results !== null
				) {
					if (
						typeof this.getWarehouse.results !== "undefined" &&
						this.getWarehouse.results !== null
					) {
						if (
							typeof this.getWarehouse.results.length !== "undefined" &&
							this.getWarehouse.results.length !== null
						) {
							shipListsData = this.getWarehouse.results.map(
								(value) => `${value.name}, ${value.address}`
							);
						}
					}
				}
			}

			return shipListsData;
		},
		productLists() {
			let productListsData = [];

			if (
				typeof this.getProducts !== "undefined" &&
				this.getProducts !== null
			) {
				if (
					this.getProducts.length !== "undefined" &&
					this.getProducts.length !== null
				) {
					productListsData = this.getProducts.map((value) => {
						let data = {
							product_id: value.id,
							id: value.id,
							name: value.name,
							sku: value.sku,
							desc: value.description,
							image: value.image,
						};

						return data;
					});
				}
			}

			return productListsData;
		},
		addPoTemplate() {
			let cargoDateFormat =
				this.poItem.cargo_ready_date !== undefined &&
				this.poItem.cargo_ready_date !== null &&
				this.poItem.cargo_ready_date !== ""
					? moment(this.poItem.cargo_ready_date).format("YYYY-MM-DD")
					: null;
			let committedCargoDateFormat =
				this.poItem.committed_cargo_ready_date !== undefined &&
				this.poItem.committed_cargo_ready_date !== null &&
				this.poItem.committed_cargo_ready_date !== ""
					? moment(this.poItem.committed_cargo_ready_date).format("YYYY-MM-DD")
					: null;
			let mustArriveByFormat =
				this.poItem.must_arrive_by !== undefined &&
				this.poItem.must_arrive_by !== null &&
				this.poItem.must_arrive_by !== ""
					? moment(this.poItem.must_arrive_by).format("YYYY-MM-DD")
					: null;
			let buildItem = {
				sys_gen: this.radioGroup1 === "custom" ? false : true,
				customer_id:
					typeof this.getUser == "string"
						? JSON.parse(this.getUser).customer.id
						: "",
				supplier_id: this.poItem.supplier_id,
				notes: this.poItem.notes,
				products: this.poItem.products.map((eip) => ({
					id: eip.id,
					carton_count: parseInt(eip.carton_count),
					units_per_carton: parseInt(eip.units_per_carton),
					units: eip.units,
					quantity: parseInt(eip.carton_count), // to be removed later on
					unit_price: parseFloat(eip.unit_price),
					amount: eip.amount,
					volume: eip.volume,
					weight: eip.weight,
					other_party_product_id: eip.other_party_product_id,
					ship_date:eip.ship_date !== null ? eip.ship_date  :'',
					po_product_id:eip.po_product_id !== null ? eip.po_product_id  :null
				})),
				cargo_ready_date: cargoDateFormat,
				committed_cargo_ready_date: committedCargoDateFormat,
				must_arrive_by: mustArriveByFormat,
				ship_via: this.poItem.ship_via,
				packing_method: this.poItem.packing_method,
				terms: this.poItem.terms,
				payment_term: this.poItem.payment_term,
				warehouse_id: this.poItem.warehouse_id,
				sub_total: this.poItem.sub_total,
				total:
					parseFloat(this.poItem.sub_total) +
					parseFloat(this.poItem.shipping) +
					parseFloat(this.poItem.tax) -
					parseFloat(this.poItem.discount),
				shipping: this.poItem.shipping,
				discount: this.poItem.discount,
				tax: this.poItem.tax,
				ship_to: this.poItem.ship_to,
				import_name_id: this.poItem.import_name_id,
				signature: this.poItem.signature,
				required_deposit_type: this.poItem.required_deposit_type,
				required_deposit_value: this.poItem.required_deposit_value,
				unit_of_measurement: "CBM/KG",
				product_mapping: 1,
			};

			if (!buildItem.sys_gen) {
				buildItem.po_number = this.poItem.po_number;
			}

			return buildItem;
		},
		getSignatureDetails() {
			return this.poItem.signature_details !== null &&
				this.poItem.signature_details !== undefined
				? JSON.parse(this.poItem.signature_details)
				: null;
		},
	},
	methods: {
		...mapActions({
			fetchVendorLists: "po/fetchVendorLists",
			fetchPoSelectProducts: "po/fetchPoSelectProducts",
			fetchPo: "po/fetchPo",
			createPo: "po/createPo",
			updatePo: "po/updatePo",
			fetchWarehouse: "warehouse/fetchWarehouse",
			getPo: "po/getPo",
			fetchPoPending: "po/fetchPoPending",
			fetchPoShipped: "po/fetchPoShipped",
			fetchPoShippedNew: "po/fetchPoShippedNew",
			fetchPoPendingNew: "po/fetchPoPendingNew",
			fetchPoAllNew: "po/fetchPoAllNew",
			setCurrentPOTab: "po/setCurrentPOTab",
			setPoCurrentOpenTab: "po/setPoCurrentOpenTab",
			updateState: "po/updateState",
			setPoCurrentAllTab: "po/setPoCurrentAllTab",
			fetchPoHistory: "poDetails/fetchPoHistory",
			setPoCurrentInProgressTab: "po/setPoCurrentInProgressTab",
			fetchOrderCounter: "po/fetchOrderCounter",
			fetchVendorSku:'po/fetchVendorSku'
		}),
		...globalMethods,
		restrictValues(e) {
			if (
				(e.key == "-" && e.keyCode == 189) ||
				(e.key == "+" && e.keyCode == 187)
			) {
				e.preventDefault();
			}
		},
		removeRow(item) {
			let getIndex = this.poItem.products.indexOf(item);
			this.poItem.products.splice(getIndex, 1);
			this.dataProducts = this.poItem.products;
		},
		async updateProduct(index) {
			let value = this.poItem.products[index].id;
			this.fetchingSingleProductLoading = true;
			this.poItem.products[index].unit_price = 0;
			this.poItem.products[index].units = 0;
			this.poItem.products[index].carton_count = 0;

			if (value !== null) {
				this.poItem.products[index].isDisabled = true;
				this.fetchPoSelectProducts(value)
					.then((res) => {
						this.fetchingSingleProductLoading = false;
						this.poItem.products[index].isDisabled = false;

						if (typeof res.data !== "undefined") {
							if (
								typeof res.data.unit_price !== "undefined" &&
								res.data.unit_price !== "" &&
								res.data.unit_price !== null
							) {
								this.poItem.products[index].unit_price = parseFloat(
									res.data.unit_price
								).toFixed(2);
							} else {
								this.poItem.products[index].unit_price = 0;
							}

							if (
								typeof res.data.units_per_carton !== "undefined" &&
								res.data.units_per_carton !== "" &&
								res.data.units_per_carton !== null
							) {
								this.poItem.products[index].units_per_carton =
									res.data.units_per_carton;
							} else {
								this.poItem.products[index].units_per_carton = 0;
							}

							if (
								typeof res.data.volume !== "undefined" &&
								res.data.volume !== "" &&
								res.data.volume !== null
							) {
								this.poItem.products[index].volume = res.data.volume;
							} else {
								this.poItem.products[index].volume = 0;
							}

							if (
								typeof res.data.weight !== "undefined" &&
								res.data.weight !== "" &&
								res.data.weight !== null
							) {
								this.poItem.products[index].weight = res.data.weight;
							} else {
								this.poItem.products[index].weight = 0;
							}
						} else {
							this.poItem.products[index].units_per_carton = 0;
							this.poItem.products[index].unit_price = 0;
						}
					})
					.catch((e) => {
						this.fetchingSingleProductLoading = false;
						this.poItem.products[index].isDisabled = false;
						this.poItem.products[index].unit_price = 0;
						this.poItem.products[index].units_per_carton = 0;
						console.log("create dialog", e);
						if (
							typeof e.message !== "undefined" &&
							e.message == "UnAuthenticated"
						) {
							this.notificationError(
								"Token has been expired. Please reload the page."
							);
						}
					});
			}
		},
		async updateCartonCount(index) {
			let value = this.poItem.products[index];
			if (this.fromComponent == "po-details-page") {
				let productData = this.getPoDetail.purchase_order_products[index];

				if (productData.units_per_carton != value.units_per_carton) {
					this.changeRequestButton = true;
				} else if (productData.quantity != value.carton_count) {
					this.changeRequestButton = true;
				} else {
					this.changeRequestButton = false;
				}
			} else {
				let data = this.posAll.find((item) => {
					return item.id && item.id === this.poItem.id;
				});
				let productData = data.purchase_order_products[index];
				let { units_per_carton, quantity } = productData;

				if (units_per_carton != value.units_per_carton) {
					this.changeRequestButton = true;
				} else if (quantity != value.carton_count) {
					this.changeRequestButton = true;
				} else {
					this.changeRequestButton = false;
				}
			}

			if (
				typeof value.units_per_carton !== "undefined" &&
				value.units_per_carton !== null
			) {
				let total_units = 0;
				total_units = value.units_per_carton * parseInt(value.carton_count);

				this.poItem.products[index].units = total_units;

				let productData = this.poItem.purchase_order_products[index];

				let volume_original = productData.product.volume;
				let weight_original = productData.product.weight;

				if (volume_original != 0) {
					this.poItem.products[index].volume = volume_original * total_units;
				} else {
					let volume = (total_units * productData.volume) / productData.units;
					this.poItem.products[index].volume = volume;
				}

				this.poItem.products[index].volume =
					this.countDecimals(this.poItem.products[index].volume) > 5
						? this.poItem.products[index].volume.toFixed(5)
						: this.poItem.products[index].volume;

				this.poItem.products[index].volume =
					this.poItem.products[index].volume == 0
						? 0.00001
						: this.poItem.products[index].volume;

				if (weight_original != 0) {
					this.poItem.products[index].weight = weight_original * total_units;
				} else {
					let weight = (total_units * productData.weight) / productData.units;
					this.poItem.products[index].weight = weight;
				}
			}
			if (this.changeRequestButton) {
				this.performaInvoice = false;
			}
		},
		async fetchProductsPoDropdown() {
			let attempt = false;
			let productsData = [];

			return new Promise((resolve, reject) => {
				let context = this;
				function proceed() {
					axios
						// .get(
						// 	`${context.poBaseUrlState}/orders/products?page=${context.current_page}`
						// )
						.get(
							`${context.poBaseUrlState}/v2/products-po-dropdown`
						)
						.then((res) => {
							if (res.status === 200) {
								if (typeof res.data !== "undefined") {
									if (
										typeof res.data.results !== "undefined" &&
										typeof res.data.results !== "undefined"
									) {
										//iterate data to new data
										productsData = res.data.results;
										productsData = productsData.map((value) => {
											let data = {
												product_id: value.id,
												id: value.id,
												name: value.name,
												sku: value.sku,
												desc: value.description,
												image: value.image,
												status: value.status,
												category_id: value.category_id,
												unit_price: value.unit_price,
												category_sku: value.category_sku,
												other_party_product_id:
													value.other_party_product_id || 0,
											};

											return data;
										});
										//concatonate new data
										context.productListsDropdown = context.productListsDropdown.concat(
											productsData
										);

										//check next page
										if (
											typeof res.data.results.next_page_url !== "undefined" &&
											res.data.results.next_page_url !== null
										) {
											context.current_page = parseInt(this.current_page + 1);
											context.fetchProductsPoDropdown();
										}
									}

									resolve("ok");
								} else {
									reject("not ok");
								}
							} else {
								reject("not ok");
							}
						})
						.catch((err) => {
							if (typeof err.message !== "undefined") {
								if (!attempt) {
									attempt = true;
									let t = setInterval(() => {
										if (!context.getIsRefreshing) {
											proceed();
											clearInterval(t);
										}
									}, 300);
								} else {
									// reject('Token has been expired. Please reload the page.')
									reject(err);
								}
							}

							if (typeof err.error !== "undefined") {
								reject(err.error);
							}
						});
				}
				proceed();
			});
		},
		getImgUrl(pic) {
			// if returned image from the API has no https://po.shifl.com/storage/
			let imageUrl = "https://po.shifl.com/storage/";

			if (pic !== "undefined" && pic !== null) {
				if (pic.includes(imageUrl) !== "undefined" && !pic.includes(imageUrl)) {
					let newImage = imageUrl + pic;
					return newImage;
				} else {
					return pic;
				}
			} else {
				return require("../../../assets/icons/default-product-icon.svg");
			}
		},
		getProductNumber(index) {
			let setNumber = index + 1;

			if (setNumber < 10) {
				setNumber = "0" + setNumber;
			}
			return setNumber;
		},
		subTotal() {
			if (
				typeof this.poItem.products !== "undefined" &&
				this.poItem.products !== null &&
				Array.isArray(this.poItem.products) &&
				this.poItem.products.length > 0
			) {
				if (
					this.poItem.sub_total !==
					_.sumBy(this.poItem.products, (e) => e.amount)
				) {
					this.poItem.sub_total = _.sumBy(
						this.poItem.products,
						(e) => e.amount
					);
				}
				return isNaN(this.poItem.sub_total)
					? 0
					: parseFloat(this.poItem.sub_total).toFixed(2);
			} else {
				this.poItem.sub_total = 0;
				return 0;
			}
		},
		total() {
			if (
				this.poItem.total !==
				parseFloat(this.poItem.sub_total + this.poItem.tax)
			) {
				this.poItem.total = parseFloat(this.poItem.sub_total + this.poItem.tax);
			}
			return isNaN(this.poItem.total)
				? 0
				: parseFloat(this.poItem.total).toFixed(2);
		},
		tax() {
			this.poItem.tax = 0;
			return this.poItem.tax;
		},
		updateAmount(getItem) {
			if (typeof this.poItem.products !== "undefined") {
				let value = this.poItem.products[this.poItem.products.indexOf(getItem)];

				if (typeof value !== "undefined") {
					if (
						value.amount !==
						parseFloat(parseInt(getItem.units) * parseFloat(getItem.unit_price))
					) {
						value.amount = parseFloat(
							parseInt(getItem.units) * parseFloat(getItem.unit_price)
						);
					}

					return isNaN(value.amount) ? 0 : parseFloat(value.amount).toFixed(2);
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		},
		orderRejectConfirmationCall() {
			this.orderRejectConfirmationFlag = true;
		},
		orderRejectConfirmationDialogClose() {
			this.orderRejectConfirmationFlag = false;
		},
		close() {
			this.$refs.form.resetValidation();
			this.radioGroup1 = "generated";
			this.changeRequestButton = false;
			this.$emit("close");
		},
		async orderStateUpdate(orderState) {
			this.$refs.form.validate();
			if (this.$refs.form.validate()) {
				if (this.performaInvoice) {
					orderState = "performa_request";
					this.addPoTemplate.another = false;

					let editPoTemplate = this.addPoTemplate;
					editPoTemplate.id = this.poItem.id;
					editPoTemplate._method = "PUT";

					editPoTemplate.state = orderState;
					editPoTemplate.signature = this.poItem.signature;
					editPoTemplate.notes = this.poItem.notes;
					editPoTemplate.signatureDate = this.signatureDate;
					editPoTemplate.is_issuer = this.poItem.is_issuer;
					editPoTemplate.orderType = this.poItem.order_type;

					await this.updatePo(editPoTemplate);
				} else {
					const payload = {
						poNumber: this.poItem.po_number,
						orderAction: orderState,
						notes: this.poItem.notes,
						signature: this.poItem.signature,
						signatureDate: this.signatureDate,
						is_issuer: this.poItem.is_issuer,
						orderType : this.poItem.order_type
					};
					await this.updateState(payload);
				}

				let pathData =
					typeof this.$router.history.current.query.tab !== "undefined"
						? this.$router.history.current.query.tab
						: null;

				if (this.$router.history.current.name !== "PO Details") {
					if (orderState == "accept") {
						this.setCurrentPOTab(2);
						this.setPoCurrentInProgressTab(1);
						if (pathData !== null && pathData !== "approved") {
							this.$router.push("?tab=approved");
						}

						orderState = "approved";
					} else if (orderState == "reject") {
						this.setCurrentPOTab(0);
						this.setPoCurrentAllTab(2);
						if (pathData !== null && pathData !== "archived") {
							this.$router.push("?tab=archived");
						}

						orderState = "archived";
					} else if (orderState == "performa_request") {
						this.setCurrentPOTab(1);
						this.setPoCurrentOpenTab(2);
						if (pathData !== null && pathData !== "pending_approval") {
							this.$router.push("?tab=pending_approval");
						}
						orderState = "pending_approval";
					}

					this.fetchOrderCounter();
					await this.fetchPoAllNew({
						page: 1,
						state: orderState,
					});
				} else {
					await this.getPo(this.poItem.id);
				}
				this.orderRejectConfirmationFlag = false;

				this.$emit("close");
			}
		},
		async savePo() {
			if (!this.hasCartonError && !this.hasTotalUnitError) {
				this.$refs.form.validate();

				setTimeout(async () => {
					if (this.$refs.form.validate()) {
						this.addPoTemplate.another = false;

						if (this.hasProductDuplicates) {
							this.notificationError("Duplicate products has been detected.");
						} else {
							try {
								let editPoTemplate = this.addPoTemplate;
								editPoTemplate.id = this.poItem.id;
								editPoTemplate._method = "PUT";

								editPoTemplate.state = this.performaInvoice
									? PERFORMA_REQUEST
									: CHANGE_REQUEST;

								await this.updatePo(editPoTemplate);
								this.notificationMessage("PO has been updated.");

								await this.getPo(this.poItem.id);
								await this.fetchPoHistory(this.poItem.id);

								let pathData =
									typeof this.$router.history.current.query.tab !== "undefined"
										? this.$router.history.current.query.tab
										: null;

								if (this.$router.history.current.name !== "PO Details") {
									this.setCurrentPOTab(1);
									this.setPoCurrentOpenTab(2);

									if (pathData !== null && pathData !== "pending_approval") {
										this.$router.push("?tab=pending_approval");
									}
									this.fetchOrderCounter();
									await this.fetchPoAllNew({
										page: 1,
										state: "pending_approval",
									});
								}
								this.close();

								if (this.isMobile) {
									this.callSinglePo();
								}

								this.radioGroup1 = "generated";
							} catch (e) {
								this.notificationError(e);
								console.log(e);
							}
						}
					}
				}, 200);
			} else {
				this.dialogErrorUpdateCount = true;
			}
		},
		async callSinglePo() {
			const params = new URLSearchParams(window.location.search);

			if (params.has("id")) {
				let getPoId = params.get("id");
				try {
					await this.getPo(getPoId);
					await this.fetchVendorLists();
					await this.fetchWarehouse();
				} catch (e) {
					console.log("get po", e);
				}
			}
		},
		inputRestrictSpecialChar(e) {
			if (/^\W$/.test(e.key)) {
				if (e.key !== "-" && e.key !== " ") {
					e.preventDefault();
				}
			}
		},
		isProductSelected(id, index) {
			if (id !== null) {
				let findSelectedOption = _.findIndex(
					this.poItem.products,
					(e) => e.id == id
				);

				if (findSelectedOption !== index) {
					this.hasProductDuplicates = true;
					return "This product has already been selected.";
				} else {
					this.hasProductDuplicates = false;
				}
			}
		},
		isProductFieldError(id, index) {
			if (id !== null) {
				let findSelectedOption = _.findIndex(
					this.poItem.products,
					(e) => e.id == id
				);

				if (findSelectedOption !== index) {
					return "has-duplicate not-empty";
				} else {
					return "not-empty";
				}
			}
		},
		isProductSelectedOtherFieldsError(id, index) {
			if (id !== null) {
				let findSelectedOption = _.findIndex(
					this.poItem.products,
					(e) => e.id == id
				);

				if (findSelectedOption !== index) {
					return ".";
				}
			}
		},
		avoidClick(e) {
			e.preventDefault();
		},
		checkShowErrorMessageCartons(item) {
			if (item !== undefined && item !== "undefined" && item !== null) {
				if (item.showErrorCarton !== "undefined" && item.showErrorCarton) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		},
		checkShowErrorMessageUnits(item) {
			if (item !== undefined && item !== "undefined" && item !== null) {
				if (item.showErrorUnit !== "undefined" && item.showErrorUnit) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		},
		checkBothErrorUnitsAndCartons(item) {
			if (item !== undefined && item !== "undefined" && item !== null) {
				if (item.hasUnitError || item.hasCartonError) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		},
		isCartonCountError(index) {
			if (index !== null) {
				let value = this.poItem.products[index];

				if (
					this.poItem.status === "partially shipped" ||
					this.poItem.status === "partially_shipped" ||
					this.poItem.status === "partial_shipped"
				) {
					if (typeof value !== "undefined" && value !== null) {
						if (
							value.carton_count !== "undefined" &&
							value.carton_count !== null
						) {
							let parseCartonCount =
								typeof value.carton_count === "string"
									? parseInt(value.carton_count)
									: value.carton_count;

							if (parseCartonCount < value.shipped_cartons) {
								this.poItem.products[index].hasCartonError = true;
								this.poItem.products[
									index
								].cartonErrorMessage = `Can't be below ${value.shipped_cartons}`;
							} else {
								this.poItem.products[index].hasCartonError = false;
								this.poItem.products[index].cartonErrorMessage = "";
							}

							let findHasErrorPO = _.findIndex(
								this.poItem.products,
								(e) => e.hasCartonError === true
							);
							if (findHasErrorPO > -1) {
								this.hasCartonError = true;
							} else {
								this.hasCartonError = false;
							}
						}
					}
				}
			}
		},
		isUnitCountError(index) {
			if (index !== null) {
				let value = this.poItem.products[index];

				if (
					this.poItem.status === "partially shipped" ||
					this.poItem.status === "partially_shipped" ||
					this.poItem.status === "partial_shipped"
				) {
					if (typeof value !== "undefined" && value !== null) {
						if (value.units !== "undefined" && value.units !== null) {
							let parseUnits =
								typeof value.units === "string"
									? parseInt(value.units)
									: value.units;

							if (parseUnits < value.shipped_units) {
								this.poItem.products[index].hasUnitError = true;
								this.poItem.products[
									index
								].unitErrorMessage = `Can't be below ${value.shipped_units}`;
							} else {
								this.poItem.products[index].hasUnitError = false;
								this.poItem.products[index].unitErrorMessage = "";
							}

							let findHasErrorPO = _.findIndex(
								this.poItem.products,
								(e) => e.hasUnitError === true
							);
							if (findHasErrorPO > -1) {
								this.hasTotalUnitError = true;
							} else {
								this.hasTotalUnitError = false;
							}
						}
					}
				}
			}
		},
		cartonClassError(item) {
			if (item !== undefined && item !== "undefined" && item !== null) {
				if (item.hasCartonError) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		},
		unitClassError(item) {
			if (item !== undefined && item !== "undefined" && item !== null) {
				if (item.hasUnitError) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		},
		closeWarning() {
			this.dialogErrorUpdateCount = false;

			if (this.hasCartonError) {
				const indexItems = this.poItem.products
					.map((item, index) => (item.hasCartonError === true ? index : null))
					.filter((item) => item !== null);

				if (indexItems.length > 0) {
					indexItems.map((v) => {
						if (
							typeof this.poItem.products[v].showErrorCarton !== "undefined" &&
							this.poItem.products[v].showErrorCarton !== null
						) {
							this.poItem.products[v].showErrorCarton = true;
						}
					});
				}
			}

			if (this.hasTotalUnitError) {
				const indexItems = this.poItem.products
					.map((item, index) => (item.hasUnitError === true ? index : null))
					.filter((item) => item !== null);

				if (indexItems.length > 0) {
					indexItems.map((v) => {
						if (
							typeof this.poItem.products[v].showErrorUnit !== "undefined" &&
							this.poItem.products[v].showErrorUnit !== null
						) {
							this.poItem.products[v].showErrorUnit = true;
						}
					});
				}
			}
		},
		discardChanges() {
			if (typeof this.poItem !== "undefined" && this.poItem !== null) {
				if (
					typeof this.poItem.products !== "undefined" &&
					this.poItem.products !== null
				) {
					if (
						typeof this.poItem.products.length !== "undefined" &&
						this.poItem.products.length > 0
					) {
						this.poItem.products.map((item) => {
							item.carton_count = item.actual_carton_count;
							item.hasCartonError = false;
							item.cartonErrorMessage = "";
							item.showErrorCarton = false;

							item.units = item.actual_units;
							item.hasUnitError = false;
							item.unitErrorMessage = "";
							item.showErrorUnit = false;
						});
					}
				}
			}

			this.dialogErrorUpdateCount = false;
		},
		customFilter(item, queryText, itemText) {
			const textPrice = item.unit_price.toFixed(2).toLowerCase();
			const text = "#" + item.category_sku.toLowerCase();
			const textOne = item.category_sku.toLowerCase();
			const textTwo = itemText ? itemText.toLowerCase() : "";
			const textThree = item.name ? item.name.toLowerCase() : "";
			const searchText = queryText ? queryText.toLowerCase() : "";

			return (
				text.indexOf(searchText) > -1 ||
				textOne.indexOf(searchText) > -1 ||
				textTwo.indexOf(searchText) > -1 ||
				textThree.indexOf(searchText) > -1 ||
				textPrice.indexOf(searchText) > -1
			);
		},
		totalAmount(item) {
			this.poItem.tax = item.tax;
			this.poItem.shipping = item.shipping;
			this.poItem.discount = item.discount;
			this.poItem.total = item.total;
		},
		changeRequest() {
			if (this.fromComponent == "po-details-page") {
				if (!_.isEqual(this.getPoDetail, this.poItem)) {
					this.changeRequestButton = true;
				} else {
					this.changeRequestButton = false;
				}
			} else {
				let data = this.posAll.find((item) => {
					return item.id && item.id === this.poItem.id;
				});
				if (!_.isEqual(data, this.poItem)) {
					this.changeRequestButton = true;
				} else {
					this.changeRequestButton = false;
				}
			}
			if (this.changeRequestButton) {
				this.performaInvoice = false;
			}
		},
		changeButton(item) {
			this.changeRequestButton = item;
			if (this.changeRequestButton) {
				this.performaInvoice = false;
			}
		},
		changeProductRequest(index) {
			let changeProductData = this.poItem.products[index];

			if (this.fromComponent == "po-details-page") {
				let productData = this.getPoDetail.purchase_order_products[index];

				if (productData.product_id != changeProductData.id) {
					this.changeRequestButton = true;
				} else if (productData.volume != changeProductData.volume) {
					this.changeRequestButton = true;
				} else if (productData.weight != changeProductData.weight) {
					this.changeRequestButton = true;
				} else if (productData.unit_price != changeProductData.unit_price) {
					this.changeRequestButton = true;
				} else if (productData.unit != changeProductData.unit) {
					this.changeRequestButton = true;
				} else {
					this.changeRequestButton = false;
				}
			} else {
				let data = this.posAll.find((item) => {
					return item.id && item.id === this.poItem.id;
				});
				let productData = data.purchase_order_products[index];
				let { product_id, volume, weight, unit_price, unit } = productData;

				if (product_id != changeProductData.id) {
					this.changeRequestButton = true;
				} else if (parseFloat(volume).toFixed(2) != changeProductData.volume) {
					this.changeRequestButton = true;
				} else if (weight != changeProductData.weight) {
					this.changeRequestButton = true;
				} else if (unit_price != changeProductData.unit_price) {
					this.changeRequestButton = true;
				} else if (unit != changeProductData.unit) {
					this.changeRequestButton = true;
				} else {
					this.changeRequestButton = false;
				}
			}
			if (this.changeRequestButton) {
				this.performaInvoice = false;
			}
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
		// Vendor Sku Api 
		async fetchVendorSkuOnChange(val){
			if(val !== null && val !== undefined){
				try{
					await this.fetchVendorSku(val)
				}catch(e){
					this.notificationError(e)
				}
			}
		} 
	},
	async mounted() {
		try {
			this.fetchProductsPoDropdown();
		} catch (e) {
			console.log("An error occured.", e);
		}

		try {
			if (this.dataProducts !== this.poItem.products) {
				this.dataProducts = this.poItem.products;
			}

			if (typeof this.poItem.is_system_generated !== "undefined")
				this.radioGroup1 =
					this.poItem.is_system_generated === 0 ? "custom" : "generated";
			else this.radioGroup1 = "generated";
		} catch (e) {
			console.log("error fetching vendor lists", e);
		}
	},
	updated() {
		if (this.editedIndex === -1) {
			if (typeof this.$refs.form !== "undefined") {
				if (typeof this.$refs.form.resetValidation() !== "undefined") {
					this.$refs.form.resetValidation();
				}
			}
		}

		if (
			typeof this.poItem !== "undefined" &&
			this.dataProducts !== this.poItem.products
		) {
			this.dataProducts = this.poItem.products;
		}

		let valid = true;
		if (
			typeof this.poItem !== "undefined" &&
			this.poItem !== null &&
			this.poItem.products !== "undefined" &&
			this.poItem.products
		) {
			let dataProducts = this.poItem.products;
			if (Array.isArray(dataProducts) && dataProducts.length > 0) {
				dataProducts.map((item) => {
					if (
						item.quantity === "" ||
						item.quantity === 0 ||
						item.quantity === null ||
						item.quantity === "0" ||
						parseFloat(item.quantity) == 0
					)
						valid = false;

					if (
						item.unit_price == "" ||
						item.unit_price === 0 ||
						item.unit_price === null ||
						item.unit_price === "0" ||
						parseFloat(item.unit_price) == 0
					)
						valid = false;

					if (typeof item.id == "undefined") {
						valid = false;
					} else {
						if (
							item.id == "" ||
							item.id == null ||
							item.id == "0" ||
							item.id == 0
						)
							valid = false;
					}
				});
			}
		} else {
			valid = false;
		}

		if (this.isProductsValidData !== valid) this.isProductsValidData = valid;
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/po/poReviewDialog.scss";
</style>
