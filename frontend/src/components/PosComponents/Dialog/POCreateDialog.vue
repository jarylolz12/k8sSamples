<!-- @format -->

<template>
	<v-dialog
		v-model="dialogCreate"
		max-width="1440px"
		content-class="po-dialog"
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
							<div class="section-1">
								<div class="po-vendor mb-4">
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
										:rules="rules"
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
										@change="fetchVendorSkuOnChange(poItem.supplier_id)"
									>
									</v-autocomplete>
								</div>

								<div class="po-ship mb-5">
									<p class="po-title">Ship To</p>
									<v-text-field
										v-model="poItem.ship_to"
										class="select-items"
										:items="shipLists"
										placeholder="Select Warehouse/Address"
										outlined
										hide-details="auto"
									>
									</v-text-field>
								</div>

								<div class="po-ship-details mb-5">
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
										>
										</v-select>
									</div>
								</div>
							</div>

							<div class="section-2">
								<div class="po-vendor mb-4">
									<p class="po-title">
										Import name
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
									>
									</v-autocomplete>
								</div>

								<div class="po-ship-details mb-5">
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

								<div class="po-ship-details mb-5">
									<div class="ship-details-wrapper">
										<p class="po-title">Cargo Ready Date</p>
										<v-text-field
											type="date"
											class="text-fields select-items"
											placeholder="Select Date"
											outlined
											hide-details="auto"
											v-model="poItem.cargo_ready_date"
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
										/>
									</div>
								</div>
							</div>

							<div class="section-3">
								<div class="po-number mb-3">
									<p class="po-title">Po Number</p>
									<v-radio-group
										v-model="radioGroup1"
										mandatory
										v-if="editedIndex === -1"
										hide-details="auto"
									>
										<v-radio
											label="System Generated"
											color="primary"
											value="generated"
										></v-radio>
										<v-radio
											label="Custom PO Number"
											color="primary"
											value="custom"
										></v-radio>

										<span class="custom-wrapper" v-if="radioGroup1 == 'custom'">
											<img
												src="@/assets/icons/po-icon.svg"
												alt=""
												class="box-icon"
											/>

											<v-text-field
												type="text"
												height="40px"
												color="#002F44"
												width="200px"
												dense
												v-model="poItem.po_number"
												class="text-fields custom"
												placeholder="Enter Item Number"
												outlined
												:rules="[(v) => !!v || 'You must provide a PO number.']"
												hide-details="auto"
												@keydown="inputRestrictSpecialChar($event)"
												@wheel="$event.target.blur()"
											>
											</v-text-field>
										</span>
									</v-radio-group>

									<div class="custom-wrapper ml-0 mb-2" v-if="editedIndex > -1">
										<img
											src="@/assets/icons/po-icon.svg"
											alt=""
											class="box-icon"
										/>

										<v-text-field
											type="text"
											height="40px"
											color="#002F44"
											width="200px"
											dense
											readonly
											v-model="poItem.po_number"
											class="text-fields custom"
											placeholder="Enter Item Number"
											outlined
											:rules="[(v) => !!v || 'You must provide a PO number.']"
											hide-details="auto"
											@keydown="inputRestrictSpecialChar($event)"
											@wheel="$event.target.blur()"
										>
										</v-text-field>
									</div>
								</div>
								<div class="po-notes">
									<p class="po-title">
										Notes
										<span style=" text-transform: capitalize !important;"
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
									>
									</v-textarea>
								</div>
							</div>
						</div>

						<div class="product-info">
							<v-data-table
								:headers="headers"
								:items="dataProducts"
								:loading="poItem.loadingState"
								class="elevation-1 add-table"
								mobile-breakpoint="1023"
								hide-default-footer
								:items-per-page="500">

								<template v-slot:body="{ items }">
									<tbody v-if="items.length > 0">
										<tr v-for="(item, index) in items" :key="item.id">
											<td :class="getVendorSkusLoading ? 'td-disabled' : ''">
												<div 
													v-for="(product, i) in item.product_split"
													:key="i">
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

															<!-- :class="isProductFieldError(product.id, index,i)" -->
														<!-- v-model="poItem.products[index].id" -->
														<v-autocomplete
															v-model="product.id"
															spellcheck="false"
															:filter="customFilter"
															class="select-product"
															@change="updateProduct(index,i,product.id)"
															:items="productSkuDropDownItems"
															:placeholder="getVendorSkusLoading || productLoading ? 'Fetching Products':'Select SKU'"
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
															:disabled="getVendorSkusLoading"
															clearable
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
															<template v-slot:no-data>
																<div v-if="getVendorSkusLoading || productLoading" class="option-items loading"
																	style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
																	<div class="sku-item">
																		<v-progress-circular
																			:size="40"
																			color="#0171a1"
																			indeterminate>
																		</v-progress-circular>
																	</div>
																</div>
																<div v-if="!getVendorSkusLoading || !productLoading && (productSkuDropDownItems.length === 0)" class="option-items no-data"
																	style="min-height: 40px; padding: 12px; font-size: 14px;">
																	<div class="sku-item">
																		No available data
																	</div>
																</div>
															</template>
														</v-autocomplete>

														<span
															class="error-message"
															style="font-size: 12px; color: red"
														>
															<!-- {{ isProductSelected(product.id, index,i) }} -->
														</span>
													</div>
													<div v-if="!isMobile">
															
															<!-- :class="isProductFieldError(product.id, index,i)" -->
														<v-autocomplete
															v-if="i == 0"
															@change="updateProduct(index,i,product.id)"
															v-model="product.id"
															spellcheck="false"
															:filter="customFilter"
															class="select-product"
															:items="productSkuDropDownItems"
															:placeholder="getVendorSkusLoading || productLoading ? 'Fetching Products':'Select SKU'"
															outlined
															:disabled="getVendorSkusLoading || i > 0"
															item-text="name"
															item-value="id"
															:menu-props="{
																contentClass: 'product-lists-items',
																bottom: true,
																offsetY: true,
															}"
															:rules="rules"
															hide-details="auto"
															:clearable="i===0"
														>
															<template v-slot:selection="{ item, index }">
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

																	<!-- <br /> -->
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
															<template v-slot:no-data>
																<div v-if="getVendorSkusLoading || productLoading" class="option-items loading"
																	style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
																	<div class="sku-item">
																		<v-progress-circular
																			:size="40"
																			color="#0171a1"
																			indeterminate>
																		</v-progress-circular>
																	</div>
																</div>
																<div v-if="!getVendorSkusLoading || !productLoading" class="option-items no-data"
																	style="min-height: 40px; padding: 12px; font-size: 14px;">
																	<div class="sku-item">
																		No available data
																	</div>
																</div>
															</template>
														</v-autocomplete>

														<span
															class="error-message"
															style="font-size: 11px; color: red"
														>
															<!-- {{ isProductSelected(product.id, index,i) }} -->
														</span>

														<span
															class="error-message"
															style="
																font-size: 11px;
																color: white;
															"
															v-if="checkBothErrorUnitsAndCartons(product)"
														>
															.
														</span>
													</div>
												</div>
											</td>
											<td>
												<div class="po-ship-date-and-time-wrapper">
													<div class="po-eta"
														v-for="(product, i) in item.product_split"
														:key="i"
														:class="i !== 0 ? 'border-top-add' : (item.product_split.length > 1 ? '' : 'first-item-po')">
														<v-text-field
															type="date"
															class="table-text-fields" 
															placeholder="Select Date" 
															outlined
															:rules="rules"
															hide-details="auto"
															v-model="product.ship_date"
														/>
													</div>
												</div>
											</td>
											<td>
												<div
													class="item-actions"
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : (item.product_split.length > 1 ? '' : 'first-item-po')"
												>
													<div
														class="button-wrappers"
														:class="
															item.product_split.length > 1
																? 'border-right-add'
																: ''
														"
													>
														<button
															class="btn-icon"
															@click="splitProducts(product, index, i)"
															:disabled="splitButtonDisabled(product)"
														>
															<!-- :disabled="item.product_split[0].id == null" -->
															<InfoTooltip>
																<template v-slot:tooltip_icon>
																	<div class="header-wrapper-close">
																		<img
																			src="@/assets/icons/split-po-icon.svg"
																			alt="Split"
																			width="18px"
																			height="18px"
																		/>
																	</div>
																</template>
																<template v-slot:tooltip_info>
																	Split Production
																</template>
															</InfoTooltip>
														</button>
													</div>

													<div
														v-if="item.product_split.length > 1"
														class="button-wrappers border-right-add"
														
													>
														<button
															class="btn-black"
															@click="mergeTop(product, index, i)"
															:disabled="i === 0"
														>
															<div class="header-wrapper-close">
																<img
																	src="@/assets/icons/merge-top-icon.svg"
																	alt="Split"
																	width="18px"
																	height="18px"
																/>
															</div>
														</button>
													</div>

													<div
														v-if="item.product_split.length > 1"
														class="button-wrappers"
													>
														<button
															class="btn-black"
															@click="mergeBottom(product, index, i)"
															:disabled="
																item.product_split.length - 1 === i
															"
														>
															<div class="header-wrapper-close">
																<img
																	src="@/assets/icons/merge-bottom-icon.svg"
																	alt="Split"
																	width="18px"
																	height="18px"
																/>
															</div>
														</button>
													</div>
												</div>
											</td>

											
											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : (item.product_split.length > 1 ? '' : 'first-item-po')">
												<v-text-field
													type="number"
													@input="updateCartonCount(index,i)"
													v-model="product.carton_count"
													placeholder="0"
													outlined
													class="table-text-fields"
													hide-details="auto"
													:disabled="product.isDisabled"
													:class="cartonClassError(product) ? 'has-error' : ''"
													min="1"
													@keydown="restrictValues($event)"
													@wheel="$event.target.blur()"
												>
												</v-text-field>

												<!-- error if product duplicates -->
												<span class="error-message" style="color: white">
													<!-- {{ isProductSelectedOtherFieldsError(product.id, index,i) }} -->
												</span>

												<!-- error for updating carton count less than what is shipped -->
												<span class="error-message" style="visibility: hidden">
													{{ isCartonCountError(index) }}
												</span>

												<span
													class="error-message"
													style="font-size: 11px; color: red"
												>
													<!-- v-if="checkShowErrorMessageCartons(product)" - only shows upon clicking save -->

													{{
														typeof product.cartonErrorMessage !== "undefined" &&
														product.cartonErrorMessage !== null
															? product.cartonErrorMessage
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
													v-if="checkBothErrorUnitsAndCartons(product)"
												>
													.
												</span>
											</div>
											</td>

											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : (item.product_split.length > 1 ? '' : 'first-item-po')">
												<v-text-field
													type="number"
													placeholder="0"
													@input="updateCartonCount(index,i)"
													v-model="product.units_per_carton"
													outlined
													class="table-text-fields"
													hide-details="auto"
													:disabled="product.isDisabled"
													min="1"
													@keydown="restrictValues($event)"
													@wheel="$event.target.blur()"
												>
												</v-text-field>

												<!-- error if product duplicates -->
												<span class="error-message" style="color: white">
													<!-- {{ isProductSelectedOtherFieldsError(product.id, index,i) }} -->
												</span>

												<!-- error for updating units less than what is shipped -->
												<span class="error-message" style="visibility: hidden">
													{{ isUnitCountError(index) }}
												</span>

												<span
													class="error-message"
													style="font-size: 11px; color: red"
												>
													<!-- v-if="checkShowErrorMessageUnits(product)" - only shows upon clicking save -->
													{{
														typeof product.unitErrorMessage !== "undefined" &&
														product.unitErrorMessage !== null
															? product.unitErrorMessage
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
													v-if="checkBothErrorUnitsAndCartons(product)"
												>
													.
												</span>
											</div>
											</td>

											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : (item.product_split.length > 1 ? '' : 'first-item-po')">
												<v-text-field
													type="number"
													@input="unitsToCarton(product.units,index,i)"
													v-model="product.units"
													placeholder="0"
													outlined
													class="table-text-fields"
													:rules="cartonRules"
													hide-details="auto"
													:disabled="
														product.isDisabled ||
															(product.units_per_carton > 0 && product.carton_count > 0)
													"
													:class="unitClassError(product) ? 'has-error' : ''"
													min="1"
													@keydown="restrictValues($event)"
													@wheel="$event.target.blur()"
												>
												</v-text-field>
												
												<span class="error-message" style="color: white">
													<!-- {{ isProductSelectedOtherFieldsError(product.id, index,i) }} -->
												</span>

												
												<span class="error-message" style="visibility: hidden">
													{{ isUnitCountError(index) }}
												</span>

												<span
													class="error-message"
													style="font-size: 11px; color: red"
												>
													
													{{
														typeof product.unitErrorMessage !== "undefined" &&
														product.unitErrorMessage !== null
															? product.unitErrorMessage
															: ""
													}}
												</span>

												
												<span
													class="error-message"
													style="
														font-size: 11px;
														color: white;
													"
													v-if="checkBothErrorUnitsAndCartons(product)"
												>
													.
												</span>
											</div>
											</td>
											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : (item.product_split.length > 1 ? '' : 'first-item-po')"
												>
												<v-text-field
													v-model="product.volume"
													type="number"
													placeholder="0"
													outlined
													class="table-text-fields"
													hide-details="auto"
													:disabled="product.isDisabled"
													suffix="CBM"
													min="1"
													@keydown="restrictValues($event)"
													@wheel="$event.target.blur()"
												>
													<!-- :rules="priceRules" -->
												</v-text-field>
												<!-- error if product duplicates -->
												<span class="error-message" style="color: white">
													<!-- {{ isProductSelectedOtherFieldsError(product.id, index,i) }} -->
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
														typeof product.unitErrorMessage !== "undefined" &&
														product.unitErrorMessage !== null
															? product.unitErrorMessage
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
													v-if="checkBothErrorUnitsAndCartons(product)"
												>
													.
												</span>
											</div>
											</td>
											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : (item.product_split.length > 1 ? '' : 'first-item-po')">
												<v-text-field
													v-model="product.weight"
													type="number"
													placeholder="0"
													outlined
													class="table-text-fields"
													hide-details="auto"
													:disabled="product.isDisabled"
													min="1"
													suffix="KG"
													@keydown="restrictValues($event)"
													@wheel="$event.target.blur()"
												>
												<!-- :rules="priceRules" -->
												</v-text-field>

												<!-- error if product duplicates -->
												<span class="error-message" style="color: white">
													<!-- {{ isProductSelectedOtherFieldsError(product.id, index,i) }} -->
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
														typeof product.unitErrorMessage !== "undefined" &&
														product.unitErrorMessage !== null
															? product.unitErrorMessage
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
													v-if="checkBothErrorUnitsAndCartons(product)"
												>
													.
												</span>
											</div>
											</td>

											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : (item.product_split.length > 1 ? '' : 'first-item-po')">
												<v-text-field
													type="number"
													v-model="product.unit_price"
													placeholder="$00"
													outlined
													class="table-text-fields"
													:rules="priceRules"
													hide-details="auto"
													:disabled="product.isDisabled"
													min="1"
													@keydown="restrictValues($event)"
													@wheel="$event.target.blur()"
												>
												</v-text-field>
												<!-- :disabled="product.isDisabled || (editedIndex > -1)" -->

												<span class="error-message" style="color: white">
													<!-- {{ isProductSelectedOtherFieldsError(product.id, index,i) }} -->
												</span>

												<span
													class="error-message"
													style="
														font-size: 11px;
														color: white;
													"
													v-if="checkBothErrorUnitsAndCartons(product)"
												>
													.
												</span>
											</div>
											</td>

											<td>
												<div
													v-for="(product, i) in item.product_split"
													:key="i"
													:class="i !== 0 ? 'border-top-add' : (item.product_split.length > 1 ? '' : 'first-item-po')">
												<v-text-field
													outlined
													:value="updateAmount(product,index)"
													readonly
													class="table-text-fields amount"
													hide-details="auto"
												>
												</v-text-field>

												<span
													class="error-message"
													style="
														font-size: 11px;
														color: white;
													"
													v-if="checkBothErrorUnitsAndCartons(product)"
												>
													.
												</span>
											</div>
											</td>
											<td class="text-center">
												<v-btn
												icon
												class="btn remove-btn"
												@click="removeRow(item)"
												:disabled="dataProducts.length === 1"
											>
												<v-icon
													size="20"
													:style="
														dataProducts.length === 1
															? 'color: #B4CFE0 !important;'
															: ''
													"
												>
													mdi-close
												</v-icon>
											</v-btn>
											</td>
										</tr>
									</tbody>
								</template>

								<!-- <template v-slot:[`item.product`]="{ item, index }">
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

										
										<v-autocomplete
											v-model="item.id"
											spellcheck="false"
											:filter="customFilter"
											class="select-product"
											:class="isProductFieldError(item.id, index)"
											@change="updateProduct(index)"
											:items="productSkuDropDownItems"
											:placeholder="getVendorSkusLoading || productLoading ? 'Fetching Products':'Select SKU'"
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
											:disabled="getVendorSkusLoading"
											clearable
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
											<template v-slot:no-data>
												<div v-if="getVendorSkusLoading || productLoading" class="option-items loading"
													style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
													<div class="sku-item">
														<v-progress-circular
															:size="40"
															color="#0171a1"
															indeterminate>
														</v-progress-circular>
													</div>
												</div>
												<div v-if="!getVendorSkusLoading || !productLoading" class="option-items no-data"
													style="min-height: 40px; padding: 12px; font-size: 14px;">
													<div class="sku-item">
														No available data
													</div>
												</div>
											</template>
										</v-autocomplete>

										<span
											class="error-message"
											style="font-size: 12px; color: red"
										>
											{{ isProductSelected(item.id, index) }}
										</span>
									</div>

									
									<div v-if="!isMobile">
										<v-autocomplete
											@change="updateProduct(index)"
											v-model="item.id"
											spellcheck="false"
											:filter="customFilter"
											class="select-product"
											:class="isProductFieldError(item.id, index)"
											:items="productSkuDropDownItems"
											:placeholder="getVendorSkusLoading || productLoading ? 'Fetching Products':'Select SKU'"
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
										>
											<template v-slot:selection="{ item, index }">
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
											<template v-slot:no-data>
												<div v-if="getVendorSkusLoading || productLoading" class="option-items loading"
													style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
													<div class="sku-item">
														<v-progress-circular
															:size="40"
															color="#0171a1"
															indeterminate>
														</v-progress-circular>
													</div>
												</div>
												<div v-if="!getVendorSkusLoading || !productLoading" class="option-items no-data"
													style="min-height: 40px; padding: 12px; font-size: 14px;">
													<div class="sku-item">
														No available data
													</div>
												</div>
											</template>
										</v-autocomplete>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											{{ isProductSelected(item.id, index) }}
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
								</template> -->

								<!-- <template v-slot:[`item.volume`]="{ item, index }">
									<div class="h-100">
										<v-text-field
											v-model="item.volume"
											type="number"
											placeholder="0"
											outlined
											:rules="priceRules"
											class="table-text-fields"
											hide-details="auto"
											:disabled="item.isDisabled"
											suffix="CBM"
											min="1"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
										>
										</v-text-field>

										
										<span class="error-message" style="color: white">
											{{ isProductSelectedOtherFieldsError(item.id, index) }}
										</span>

										
										<span class="error-message" style="visibility: hidden">
											{{ isUnitCountError(index) }}
										</span>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											
											{{
												typeof item.unitErrorMessage !== "undefined" &&
												item.unitErrorMessage !== null
													? item.unitErrorMessage
													: ""
											}}
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
								</template> -->

								<!-- <template v-slot:[`item.weight`]="{ item, index }">
									<div class="h-100">
										<v-text-field
											v-model="item.weight"
											type="number"
											placeholder="0"
											outlined
											:rules="priceRules"
											class="table-text-fields"
											hide-details="auto"
											:disabled="item.isDisabled"
											min="1"
											suffix="KG"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
										>
										</v-text-field>

										
										<span class="error-message" style="color: white">
											{{ isProductSelectedOtherFieldsError(item.id, index) }}
										</span>

										
										<span class="error-message" style="visibility: hidden">
											{{ isUnitCountError(index) }}
										</span>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											
											{{
												typeof item.unitErrorMessage !== "undefined" &&
												item.unitErrorMessage !== null
													? item.unitErrorMessage
													: ""
											}}
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
								</template> -->

								<!-- <template
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
											:class="cartonClassError(item) ? 'has-error' : ''"
											min="1"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
										>
										</v-text-field>

										
										<span class="error-message" style="color: white">
											{{ isProductSelectedOtherFieldsError(item.id, index) }}
										</span>

										
										<span class="error-message" style="visibility: hidden">
											{{ isCartonCountError(index) }}
										</span>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											

											{{
												typeof item.cartonErrorMessage !== "undefined" &&
												item.cartonErrorMessage !== null
													? item.cartonErrorMessage
													: ""
											}}
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
								</template> -->

								<!-- <template v-slot:[`item.in_each`]="{ item, index }">
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
											min="1"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
										>
										</v-text-field>

										
										<span class="error-message" style="color: white">
											{{ isProductSelectedOtherFieldsError(item.id, index) }}
										</span>

										
										<span class="error-message" style="visibility: hidden">
											{{ isUnitCountError(index) }}
										</span>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											
											{{
												typeof item.unitErrorMessage !== "undefined" &&
												item.unitErrorMessage !== null
													? item.unitErrorMessage
													: ""
											}}
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
								</template> -->

								<!-- <template v-slot:[`item.units`]="{ item, index }">
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
											:class="unitClassError(item) ? 'has-error' : ''"
											min="1"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
										>
										</v-text-field>

										
										<span class="error-message" style="color: white">
											{{ isProductSelectedOtherFieldsError(item.id, index) }}
										</span>

										
										<span class="error-message" style="visibility: hidden">
											{{ isUnitCountError(index) }}
										</span>

										<span
											class="error-message"
											style="font-size: 11px; color: red"
										>
											
											{{
												typeof item.unitErrorMessage !== "undefined" &&
												item.unitErrorMessage !== null
													? item.unitErrorMessage
													: ""
											}}
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
								</template> -->

								<!-- <template v-slot:[`item.unit_price`]="{ item, index }">
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
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()"
										>
										</v-text-field>
										

										<span class="error-message" style="color: white">
											{{ isProductSelectedOtherFieldsError(item.id, index) }}
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
								</template> -->

								<!-- <template v-slot:[`item.amount`]="{ item }">
									<div class="h-100">
										<v-text-field
											outlined
											:value="updateAmount(item)"
											readonly
											class="table-text-fields amount"
											hide-details="auto"
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
								</template> -->

								<!-- <template v-slot:[`item.actions`]="{ item }">
									
									<v-btn
										icon
										class="btn remove-btn"
										@click="removeRow(item)"
										:disabled="dataProducts.length === 1"
									>
										<v-icon
											:style="
												dataProducts.length === 1
													? 'color: #B4CFE0 !important;'
													: ''
											"
										>
											mdi-close
										</v-icon>
									</v-btn>
								</template> -->
							</v-data-table>

							<div class="add-row-wrapper">
								<button class="btn add-btn" @click="addRow">
									<img
										class="mr-1"
										src="@/assets/icons/plus.svg"
										width="12px"
										height="12px"
									/>
									Add Product
								</button>
							</div>

							<PoTotalComponent
								:subTotal="subTotal()"
								:tax="poItem.tax"
								:shipping="poItem.shipping"
								:discount="poItem.discount"
								:total="poItem.total"
								@totalAmount="totalAmount"
							/>
						</div>
					</div>
				</v-form>
			</v-card-text>

			<v-card-actions class="po-button-actions">
				<button
					class="btn-blue"
					@click.prevent="savePo(false)"
					v-if="poItem.state == 'draft' || editedIndex === -1"
					:disabled="getPoCreateLoading || getPoDraftLoading"
				>
					<span v-if="editedIndex === -1">
						{{ getPoCreateLoading ? "Creating..." : "Create & Submit" }}
					</span>

					<span v-if="editedIndex > -1">
						{{ getPoCreateLoading ? "Saving PO..." : "Save & Submit" }}
					</span>
				</button>

				<button
					:class="editedIndex > -1 ? 'btn-blue' : 'btn-white'"
					@click.prevent="savePo()"
					:disabled="getPoDraftLoading || getPoCreateLoading || fromComponent == 'BookingShipmentDialog'"
				>
					<span v-if="editedIndex === -1">
						{{ getPoDraftLoading ? "Creating PO..." : "Save to Draft" }}
					</span>
					<span v-if="editedIndex > -1">
						{{ getPoDraftLoading ? "Saving PO..." : "Save" }}
					</span>
				</button>

				<button
					class="btn-white cancel"
					@click="close"
					:disabled="getPoCreateLoading || getPoDraftLoading"
				>
					Cancel
				</button>
			</v-card-actions>
		</v-card>

		<POInvalidRequest
			:dialogData.sync="dialogErrorUpdateCount"
			@close="closeWarning"
			@discardChanges="discardChanges"
		/>
	</v-dialog>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from "vuex";
import PoTotalComponent from "../PoTotalComponent.vue";
import globalMethods from "../../../utils/globalMethods";
import _ from "lodash";
import axios from "axios";
import moment from "moment";
import POInvalidRequest from "./POInvalidRequest.vue";
import InfoTooltip from "../../Tooltip/InfoTooltip.vue";
import {
	DRAFT,
	PENDING_APPROVAL,
	APPROVED,
	CHANGE_REQUEST,
	IN_PROGRESS,
} from "../../../constants/states";

export default {
	name: "POCreateDialog",
	props: ["dialog", "editedItems", "editedIndex", "isMobile", "fromComponent"],
	components: {
		PoTotalComponent,
		POInvalidRequest,
		InfoTooltip
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
			},
			{
				text: "Ship Date",
				align: "center",
				sortable: false,
				value: "ship_date",
				fixed: true,
				width: "12%",
			},
			{
				text: "Actions",
				align: "center",
				sortable: false,
				value: "actions",
				fixed: true,
				width: "8%",
			},
			{
				text: "Carton",
				align: "end",
				sortable: false,
				value: "carton_count",
				fixed: true,
				width: "9%",
			},
			{
				text: "In Each",
				align: "end",
				sortable: false,
				value: "in_each",
				fixed: true,
				width: "9%",
			},
			{
				text: "Unit",
				align: "end",
				sortable: false,
				value: "units",
				fixed: true,
				width: "9%",
			},
			{
				text: "Volume",
				align: "end",
				sortable: false,
				value: "volume",
				fixed: true,
				width: "11%",
			},
			{
				text: "Weight",
				align: "end",
				sortable: false,
				value: "weight",
				fixed: true,
				width: "9%",
			},
			{
				text: "Unit Price",
				align: "end",
				sortable: false,
				value: "unit_price",
				fixed: true,
				width: "9%",
			},
			{
				text: "Amount",
				align: "end",
				sortable: false,
				value: "amount",
				fixed: true,
				width: "9%",
			},
			{
				text: "",
				align: "center",
				sortable: false,
				value: "actions",
				fixed: true,
				width: "3%",
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
		fetchingSingleProductLoading: false,
		hasProductDuplicates: false,
		dialogErrorUpdateCount: false,
		hasCartonError: false,
		cartonErrorMessage: "",
		hasTotalUnitError: false,
		totalUnitErrorMessage: "",
		productLoading : false
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
			getVendorSkus:'po/getVendorSkus',
			getVendorSkusLoading:'po/getVendorSkusLoading'
		}),
		productListsDropdown: {
			get() {
				if (this.editedIndex === -1) {
					// let value = _.filter(
					// 	this.productListsDropdownData,
					// 	(e) => e.status === "active"
					// );
					// return value;
					return this.productListsDropdownData
				} else {
					let value = this.productListsDropdownData;
					let finalValue = [];
					if (Array.isArray(value) && value.length > 0) {
						if (
							typeof this.poItem !== "undefined" &&
							typeof this.poItem.products !== "undefined" &&
							Array.isArray(this.poItem.products) &&
							this.poItem.products.length > 0
						) {
							value.map((v) => {
								if (v.status !== "active") {
									let findProduct = _.findIndex(
										this.poItem.products,
										(e) => v.id === e.id
									);
									if (findProduct !== -1) {
										finalValue.push(v);
									}
								} else {
									finalValue.push(v);
								}
							});

							return finalValue;
						} else {
							return value;
						}
					} else {
						return value;
					}
				}
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
			if(this.poItem.supplier_id !== '' && this.poItem.supplier_id !== null){
				data = this.VendorSkuItems
			}else{
				data = this.productListsDropdown
			}
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
						: 	[
								{
									product_split:[
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
										volume_original: 0,
										weight: 0,
										weight_original: 0,
										ship_date:'',
										po_product_id:null
										}
									]
								},
						  	];

					this.poItem = {
						products: [
							{
								product_split:[
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
										volume_original: 0,
										weight: 0,
										weight_original: 0,
										ship_date:'',
										po_product_id:null
									}
								]
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
				let tempItem = { ...this.editedItems };				
				tempItem.po_products_splitting = []
				
				if(typeof tempItem.products !== "undefined" &&
					tempItem.products.length !== 0){
						if(this.editedIndex !== -1) { // editing a PO							
							tempItem.po_products_splitting = tempItem.products.map((v) => {
								let splitArray = [];

								// let volume_original = 0.00001, weight_original = 1;
								let volume_original = 0, weight_original = 0;
								
								if (v.volume !== null && v.volume !== 0 && v.volume !== "") {
									let val = parseFloat(v.volume)
									volume_original = val / v.units; // per unit
								}

								if (v.weight !== null && v.weight !== 0 && v.weight !== "") {
									let val = parseFloat(v.weight)
									weight_original = val / v.units; // per unit
								}

								splitArray = [{
									id: v.id,
									carton_count: v.carton_count,
									units: v.units,
									unit_price: v.unit_price,
									amount: v.amount,
									units_per_carton: v.units_per_carton,
									unship_cartons: v.unship_cartons,
									shipped_cartons: v.shipped_cartons,
									shipped_units: v.shipped_units,
									unshipped_units: v.unshipped_units,
									currentVolumeBeforeEdit: v.volume,
									volume: v.volume,
									volume_original: volume_original,
									weight: v.weight,
									weight_original: weight_original,
									ship_date:v.ship_date,
									po_product_id:v.po_product_id !== null && typeof v.po_product_id !== 'undefined' ? v.po_product_id:null
								},];
								
								return {
									id: v.id,
									product_split: splitArray,
								};
							})
							const arrayHashmap = tempItem.po_products_splitting.reduce((obj, item) => {
								obj[item.id] ? obj[item.id].product_split.push(...item.product_split) : (obj[item.id] = { ...item });
  								return obj;
							}, {});
							const mergedArray = Object.values(arrayHashmap);
							tempItem.po_products_splitting = mergedArray

						} else {
							tempItem.po_products_splitting = tempItem.products.map((v) => {
								let splitArray = [];
								splitArray = [{
									id: v.id,
									carton_count: v.carton_count,
									units: v.units,
									unit_price: v.unit_price,
									amount: v.amount,
									units_per_carton: v.units_per_carton,
									unship_cartons: v.unship_cartons,
									shipped_cartons: v.shipped_cartons,
									shipped_units: v.shipped_units,
									unshipped_units: v.unshipped_units,
									volume: parseFloat(v.volume).toFixed(2),
									volume_original: v.volume_original,
									weight: parseFloat(v.weight).toFixed(2),
									weight_original: v.weight_original,
									ship_date:v.ship_date,
									po_product_id:v.po_product_id !== null && typeof v.po_product_id !== 'undefined' ? v.po_product_id:null
								},];
								return {
									product_split: splitArray,
								};
							})
						}
					}
				 return tempItem;
			},
			set(value) {
				this.$emit("update:editedItems", value);
			},
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
			return this.editedIndex === -1
				? "Create Purchase Order"
				: "Edit Purchase Order";
		},
		vendorListOptions() {
			let newVendorLists = [];
			if (typeof this.getVendorLists !== "undefined" &&
				Array.isArray(this.getVendorLists) &&
				this.getVendorLists.length > 0) {
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
							// shipListsData = this.getWarehouse.results.map(value => ({
							//     label: `${value.name}, ${value.address}`,
							//     value: value.id
							// }))

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
			let filteredArr = []
                    this.dataProducts.map((Arr1) => {
                        Arr1?.product_split.map((Arr2) => {
                            const parentTwo = { ...Arr2 };
                            filteredArr.push(parentTwo);
                    });
                });
			let cargoDateFormat =
				this.poItem.cargo_ready_date !== undefined &&
				this.poItem.cargo_ready_date !== null &&
				this.poItem.cargo_ready_date !== ""
					? moment(this.poItem.cargo_ready_date).format("YYYY-MM-DD")
					: null;
			let mustArriveByFormat =
				this.poItem.must_arrive_by !== undefined &&
				this.poItem.must_arrive_by !== null &&
				this.poItem.must_arrive_by !== ""
					? moment(this.poItem.must_arrive_by).format("YYYY-MM-DD")
					: null;

			let buildItem = {
				order_type: "PO",
				sys_gen: this.radioGroup1 === "custom" ? false : true,
				customer_id:
					typeof this.getUser == "string"
						? JSON.parse(this.getUser).customer.id
						: "",
				supplier_id: this.poItem.supplier_id,
				notes: this.poItem.notes,
				products: filteredArr.map((eip) => ({
					id: eip.id,
					carton_count: parseInt(eip.carton_count),
					units_per_carton: parseInt(eip.units_per_carton),
					units: eip.units,
					quantity: parseInt(eip.carton_count), // to be removed later on
					unit_price: parseFloat(eip.unit_price),
					amount: eip.amount,
					volume: eip.volume,
					volume_original: eip.volume,
					weight: eip.weight,
					weight_original: eip.weight,
					ship_date:eip.ship_date,
					po_product_id:eip.po_product_id !== null && typeof eip.po_product_id !== 'undefined' ? eip.po_product_id:null
				})),
				cargo_ready_date: cargoDateFormat,
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
				unit_of_measurement: "CBM/KG",
			};

			if (!buildItem.sys_gen) {
				buildItem.po_number = this.poItem.po_number;
			}

			return buildItem;
		},
		buttonDisabled() {
			let button = false;
			let { supplier_id, products } = this.poItem;

			if (supplier_id == "" || supplier_id == null) {
				button = true;
			}

			products.map((item) => {
				if (item.id == null) {
					button = true;
				} else if (item.units == 0) {
					button = true;
				} else if (item.volume == 0) {
					button = true;
				} else if (item.weight == 0) {
					button = true;
				} else if (item.unit_price == 0) {
					button = true;
				}
			});

			return button;
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
			fetchPoHistory: "poDetails/fetchPoHistory",
			fetchVendorSku:'po/fetchVendorSku'
		}),
		...mapMutations({restProductAndSkuItems:'po/restProductAndSkuItems'}),
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
			let getIndex = this.poItem.po_products_splitting.indexOf(item);
			this.poItem.po_products_splitting.splice(getIndex, 1);
			this.dataProducts = this.poItem.po_products_splitting;
		},
		async updateProduct(index,i,val) {
			let value = this.dataProducts[index].product_split[i].id;
			this.fetchingSingleProductLoading = true;
			this.dataProducts[index].product_split[i].unit_price = 0;
			this.dataProducts[index].product_split[i].units = 0;
			this.dataProducts[index].product_split[i].carton_count = 1;

			if (value !== null) {
				this.dataProducts[index].product_split.map(value => value.id = val)
				this.dataProducts[index].product_split[i].isDisabled = true;
				this.fetchPoSelectProducts(value)
					.then((res) => {
						this.fetchingSingleProductLoading = false;
						this.dataProducts[index].product_split[i].isDisabled = false;

						if (typeof res.data !== "undefined") {
							if (
								typeof res.data.unit_price !== "undefined" &&
								res.data.unit_price !== "" &&
								res.data.unit_price !== null
							) {
								this.dataProducts[index].product_split[i].unit_price = parseFloat(
									res.data.unit_price
								).toFixed(2);
							} else {
								this.dataProducts[index].product_split[i].unit_price = 0;
							}

							if (
								typeof res.data.units_per_carton !== "undefined" &&
								res.data.units_per_carton !== "" &&
								res.data.units_per_carton !== null
							) {
								this.dataProducts[index].product_split[i].units_per_carton =
									res.data.units_per_carton;
							} else {
								this.dataProducts[index].product_split[i].units_per_carton = 0;
							}

							this.dataProducts[index].product_split[i].units =
								this.dataProducts[index].product_split[i].units_per_carton *
								this.dataProducts[index].product_split[i].carton_count;

							if (
								typeof res.data.volume !== "undefined" &&
								res.data.volume !== "" &&
								res.data.volume !== null &&
								res.data.volume !== 0
							) {
								this.dataProducts[index].product_split[i].volume = res.data.volume;
								this.dataProducts[index].product_split[i].volume_original = res.data.volume;
							} else {
								// this.dataProducts[index].product_split[i].volume = 0.00001;
								// this.dataProducts[index].product_split[i].volume_original = 0.00001;
								this.dataProducts[index].product_split[i].volume = 0;
								this.dataProducts[index].product_split[i].volume_original = 0;
							}

							if (
								typeof res.data.weight !== "undefined" &&
								res.data.weight !== "" &&
								res.data.weight !== null &&
								res.data.weight !== 0
							) {
								this.dataProducts[index].product_split[i].weight = res.data.weight;
								this.dataProducts[index].product_split[i].weight_original = res.data.weight;
							} else {
								// this.dataProducts[index].product_split[i].weight = 1;
								// this.dataProducts[index].product_split[i].weight_original = 1;
								this.dataProducts[index].product_split[i].weight = 0;
								this.dataProducts[index].product_split[i].weight_original = 0;
							}

							this.dataProducts[index].product_split[i].volume =
								this.dataProducts[index].product_split[i].volume *
								this.dataProducts[index].product_split[i].units;

							this.dataProducts[index].product_split[i].volume =
								this.countDecimals(this.dataProducts[index].product_split[i].volume) > 5
									? this.dataProducts[index].product_split[i].volume.toFixed(5)
									: this.dataProducts[index].product_split[i].volume;

							this.dataProducts[index].product_split[i].volume =
								this.dataProducts[index].product_split[i].volume == 0
									// ? 0.00001
									? 0
									: this.dataProducts[index].product_split[i].volume;

							this.dataProducts[index].product_split[i].weight =
								this.dataProducts[index].product_split[i].weight *
								this.dataProducts[index].product_split[i].units;
						} else {
							this.dataProducts[index].product_split[i].units_per_carton = 0;
							this.dataProducts[index].product_split[i].unit_price = 0;
						}
					})
					.catch((e) => {
						this.fetchingSingleProductLoading = false;
						this.dataProducts[index].product_split[i].isDisabled = false;
						this.dataProducts[index].product_split[i].unit_price = 0;
						this.dataProducts[index].product_split[i].units_per_carton = 0;
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

			/*
			try {
				let value = this.poItem.products[index].id
				try {


					const res = await axios.get(`${this.poBaseUrlState}/products/${value}`)
					if (res.status === 200) {
						this.fetchingSingleProductLoading = false
						this.poItem.products[index].isDisabled = false

						if (typeof res.data!=='undefined') {
							if (typeof res.data.unit_price!=='undefined' &&
								res.data.unit_price!=='' && res.data.unit_price!==null) {
								this.poItem.products[index].unit_price = parseFloat(res.data.unit_price).toFixed(2)
							} else {
								this.poItem.products[index].unit_price = 0
							}

							if (typeof res.data.units_per_carton!=='undefined' &&
								res.data.units_per_carton!=='' && res.data.units_per_carton!==null) {
								this.poItem.products[index].units_per_carton = res.data.units_per_carton
							} else {
								this.poItem.products[index].units_per_carton = 0
							}
						} else {
							this.poItem.products[index].units_per_carton = 0
							this.poItem.products[index].unit_price = 0
						}
					} else {
						this.fetchingSingleProductLoading = false
						this.poItem.products[index].isDisabled = false

						this.poItem.products[index].unit_price = 0
						this.poItem.products[index].units_per_carton = 0

					}
				} catch(e) {


					this.fetchingSingleProductLoading = false
					this.poItem.products[index].isDisabled = false

					if (typeof e.message!=='undefined' && e.message =='UnAuthenticated') {
						this.notificationError('Token has been expired. Please reload the page.')
						// this.$router.push('/login')
					}
				}
			} catch(e) {
				this.fetchingSingleProductLoading = false
				this.poItem.products[index].isDisabled = false

				if (typeof e.message!=='undefined' && e.message =='UnAuthenticated') {
					this.$router.push('/login')
				} else {
					this.poItem.products[index].unit_price = 0
					this.poItem.products[index].units_per_carton = 0
				}
			}*/
		},
		unitsToCarton(value,index,i){
			this.dataProducts[index].product_split[i].carton_count = value / this.dataProducts[index].product_split[i].units_per_carton 
		},
		async updateCartonCount(index,i) {
			let value = this.poItem.po_products_splitting[index].product_split[i];
			if (
				typeof value.units_per_carton !== "undefined" &&
				value.units_per_carton !== null
			) {
				let total_units = 0;
				total_units = value.units_per_carton * parseInt(value.carton_count);
				this.dataProducts[index].product_split[i].units = total_units;
				// let cartons = 0;
				// cartons = value.units_per_carton / parseInt(value.carton_count);
				// this.dataProducts[index].product_split[i].carton_count = cartons;

				if (this.editedIndex === -1) {
					let productData = this.dataProducts[index].product_split[i];

					if (!productData.volume_original) {
						productData.volume_original = productData.volume;
					}
					if (!productData.weight_original) {
						productData.weight_original = productData.weight;
					}

					this.dataProducts[index].product_split[i].volume =
						productData.volume_original * total_units;

					this.dataProducts[index].product_split[i].weight =
						productData.weight_original * total_units;
				} else {
					let productData = this.poItem.purchase_order_products[index];

					let volume_original = productData.product.volume;
					let weight_original = productData.product.weight;

					if (volume_original != 0) {
						this.dataProducts[index].product_split[i].volume = volume_original * total_units;
					} else {
						let volume = (total_units * productData.volume) / productData.units;
						this.dataProducts[index].product_split[i].volume = volume;
					}

					if (weight_original != 0) {
						this.dataProducts[index].product_split[i].weight = weight_original * total_units;
					} else {
						let weight = (total_units * productData.weight) / productData.units;
						this.dataProducts[index].product_split[i].weight = weight;
					}
				}

				this.dataProducts[index].product_split[i].volume =
					this.countDecimals(this.dataProducts[index].product_split[i].volume) > 5
						? this.dataProducts[index].product_split[i].volume.toFixed(5)
						: this.dataProducts[index].product_split[i].volume;

				this.dataProducts[index].product_split[i].volume =
					this.dataProducts[index].product_split[i].volume == 0
						// ? 0.00001
						? 0
						: this.dataProducts[index].product_split[i].volume;
			}
		},
		calculateVolumWeight(index,i) {
			let value = this.dataProducts[index].product_split[i];

			let productData = this.dataProducts[index].product_split[i];
			if (!productData.volume_original) {
				productData.volume_original = productData.volume;
			}
			if (!productData.weight_original) {
				productData.weight_original = productData.weight;
			}
			this.dataProducts[index].product_split[i].volume =
				productData.volume_original * value.units;
			this.dataProducts[index].product_split[i].weight =
				productData.weight_original * value.units;
		},
		addRow() {
			let getItem = this.poItem.po_products_splitting;
			let obj = {}
			obj.product_split =  [
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
					volume_original: 0,
					weight: 0,
					weight_original: 0,
					ship_date:'',
					po_product_id:null
				},
			];
			getItem.push(obj);
			// getItem.splice(1, 0, obj);
			
			this.dataProducts = getItem;
		},
		async fetchProductsPoDropdown() {
			let attempt = false;
			let productsData = [];

			return new Promise((resolve, reject) => {
				let context = this;
				function proceed() {
					context.productLoading = true
					axios
						// .get(
						// 	`${context.poBaseUrlState}/orders/products?page=${context.current_page}`
						// )
						.get(
							`${context.poBaseUrlState}/v2/products-po-dropdown`
						)
						.then((res) => {
							context.productLoading = false
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
							context.productLoading = false
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
			if (typeof this.poItem.products !== "undefined" &&
				this.poItem.products !== null &&
				Array.isArray(this.poItem.products) &&
				this.poItem.products.length > 0) {

				// 	if (
				// 	this.poItem.sub_total !==
				// 	_.sumBy(this.poItem.products, (e) => e.amount)
				// ) {
				// 	this.poItem.sub_total = _.sumBy(
				// 		this.poItem.products,
				// 		(e) => e.amount
				// 	);
				// }

				if (this.poItem.sub_total !== this.finalTotal()) {
					this.poItem.sub_total = this.finalTotal()
				}
				return isNaN(this.poItem.sub_total)
					? 0
					: parseFloat(this.poItem.sub_total).toFixed(2);

				} else {
				this.poItem.sub_total = 0;
				return 0;
			}
		},
		finalSubtotal(){
			return this.dataProducts.map((val, indexx) => {
				return this.dataProducts[indexx]?.product_split?.reduce((prev, item) => {
				if (prev ==='' || item.amount ==='') return prev
					return parseFloat(prev) + parseFloat(item.amount);
				}, 0);
			});
		},
		finalTotal(){
			return this.finalSubtotal().reduce((prev, item) => {
				if (prev ==='' || item ==='') return prev
					return parseFloat(prev) + parseFloat(item);
				}, 0);
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
		updateAmount(getItem,index) {
			if (typeof this.dataProducts[index].product_split !== "undefined") {
				let value = this.dataProducts[index].product_split[this.dataProducts[index].product_split.indexOf(getItem)];

				if (typeof value !== "undefined") {
					if (value.amount !==
						parseFloat(parseInt(getItem.units) * parseFloat(getItem.unit_price))) {
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
		close() {
			this.$refs.form.resetValidation();
			this.radioGroup1 = "generated";
			this.poItem.supplier_id = ''
			this.restProductAndSkuItems([])
			this.$emit("close");
		},
		async savePo(isDraft = true) {
			if (!this.hasCartonError && !this.hasTotalUnitError) {
				this.$refs.form.validate();

				setTimeout(async () => {
					if (this.$refs.form.validate()) {
						this.addPoTemplate.another = false;

						if (this.hasProductDuplicates) {
							this.notificationError("Duplicate products has been detected.");
						} else {
							try {
								if (this.editedIndex === -1) {
									if(this.fromComponent !== 'BookingShipmentDialog'){
										this.addPoTemplate.state = isDraft ? DRAFT : PENDING_APPROVAL;
									}else{
										this.addPoTemplate.state = IN_PROGRESS;
										this.addPoTemplate.createdFrom = 'BookingShipmentDialog';
									}
									await this.createPo(this.addPoTemplate);
									this.notificationMessage("PO has been created.");

									let pathData =
										typeof this.$router.history.current.query.tab !==
										"undefined"
											? this.$router.history.current.query.tab
											: null;

									if(this.fromComponent !== 'BookingShipmentDialog'){
										if (isDraft) {
											this.setCurrentPOTab(1);
											this.setPoCurrentOpenTab(0);
											if (pathData !== null && pathData !== "draft") {
												this.$router.push("?tab=draft");
											}

											await this.fetchPoAllNew({
												page: this.getAllPoPage,
												state: DRAFT,
											});
										} else {
											this.setCurrentPOTab(1);
											this.setPoCurrentOpenTab(2);

											if (pathData !== null && pathData !== "pending_approval") {
												this.$router.push("?tab=pending_approval");
											}
											await this.fetchPoAllNew({
												page: this.getAllPoPage,
												state: PENDING_APPROVAL,
											});
										}
									}else{
										await this.fetchPurchaseOrderOptions(
												"",
												"PO",
												this.poItem.entity_id,
												this.addPoTemplate.supplier_id
											);
									}

									this.close();

									if (this.isMobile) {
										this.callSinglePo();
									}
								} else {
									let editPoTemplate = this.addPoTemplate;
									editPoTemplate.id = this.poItem.id;
									editPoTemplate._method = "PUT";

									if (this.poItem.state == DRAFT) {
										editPoTemplate.state = isDraft ? DRAFT : PENDING_APPROVAL;
									} else if (this.poItem.state == APPROVED) {
										editPoTemplate.state = CHANGE_REQUEST;
									}
									await this.updatePo(editPoTemplate);
									this.notificationMessage("PO has been updated.");

									await this.getPo(this.poItem.id);
									await this.fetchPoHistory(this.poItem.id);

									let pathData =
										typeof this.$router.history.current.query.tab !==
										"undefined"
											? this.$router.history.current.query.tab
											: null;

									if (this.$router.history.current.name !== "PO Details") {
										if (this.poItem.state == DRAFT) {
											if (isDraft) {
												this.setCurrentPOTab(1);
												this.setPoCurrentOpenTab(0);

												if (pathData !== null && pathData !== "draft") {
													this.$router.push("?tab=draft");
												}
												await this.fetchPoAllNew({
													page: this.getAllPoPage,
													state: DRAFT,
												});
											} else {
												this.setCurrentPOTab(1);
												this.setPoCurrentOpenTab(2);

												if (
													pathData !== null &&
													pathData !== "pending_approval"
												) {
													this.$router.push("?tab=pending_approval");
												}
												await this.fetchPoAllNew({
													page: this.getAllPoPage,
													state: PENDING_APPROVAL,
												});
											}
										} else {
											await this.fetchPoAllNew({
												page: this.getAllPoPage,
												state: pathData !== "all" ? pathData : null,
											});
										}
									}
									this.close();

									if (this.isMobile) {
										this.callSinglePo();
									}
								}

								this.radioGroup1 = "generated";
							} catch (e) {
								this.warningToast(e);
							}
						}
					}
				}, 200);
			} else {
				this.dialogErrorUpdateCount = true;
			}
		},
		async addPoAnother() {
			this.$refs.form.validate();

			setTimeout(async () => {
				if (this.$refs.form.validate()) {
					if (this.hasProductDuplicates) {
						this.notificationError("Duplicate products has been detected.");
					} else {
						try {
							// save then close
							this.addPoTemplate.another = true;
							await this.createPo(this.addPoTemplate);
							this.notificationMessage("Po has been created.");

							if (this.getCurrentPoTab === 0) {
								await this.fetchPoAllNew({
									page: this.getAllPoPage,
								});
							} else if (this.getCurrentPoTab === 1) {
								await this.fetchPoPendingNew({
									page: this.getPendingPage,
								});
							} else if (this.getCurrentPoTab === 2) {
								await this.fetchPoShippedNew({
									page: this.getShippedPage,
								});
							}

							this;
							this.$emit("update:editedIndex", -1);
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
										ship_date:'',
										po_product_id:null
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
							};
							this.$refs.form.resetValidation();
							this.radioGroup1 = "generated";

							if (this.isMobile) {
								this.callSinglePo();
							}
						} catch (e) {
							this.notificationError(e);
							console.log(e);
						}
					}
				}
			}, 200);
		},
		async updatePoAnother() {
			this.$refs.form.validate();

			if (this.$refs.form.validate()) {
				if (this.hasProductDuplicates) {
					this.notificationError("Duplicate products has been detected.");
				} else {
					try {
						let editPoTemplate = this.addPoTemplate;
						editPoTemplate.id = this.poItem.id;
						editPoTemplate._method = "PUT";
						editPoTemplate.another = true;
						await this.updatePo(editPoTemplate);
						this.notificationMessage("PO has been updated.");
						await this.getPo(this.poItem.id);

						if (this.getCurrentPoTab === 0) {
							await this.fetchPoAllNew({
								page: 1,
							});
						} else if (this.getCurrentPoTab === 1) {
							await this.fetchPoPendingNew({
								page: 1,
							});
						} else if (this.getCurrentPoTab === 2) {
							await this.fetchPoShippedNew({
								page: 1,
							});
						}

						this.$emit("update:editedIndex", -1);
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
									ship_date:'',
									po_product_id:null
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
						};
						this.$refs.form.resetValidation();
						this.radioGroup1 = "generated";

						if (this.isMobile) {
							this.callSinglePo();
						}
					} catch (e) {
						this.notificationError(e);
						console.log(e);
					}
				}
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
		isProductSelected(id, index,i) {
			if (id !== null) {
				let findSelectedOption = _.findIndex(
					this.dataProducts[index].product_split,
					(e) => e.id == id
				);

				if (findSelectedOption == i) {
					this.hasProductDuplicates = true;
					return "This product has already been selected.";
				} else {
					this.hasProductDuplicates = false;
				}
			}
		},
		isProductFieldError(id, index,i) {
			if (id !== null) {
				let findSelectedOption = _.findIndex(
					this.dataProducts[index].product_split,
					(e) => e.id == id
				);

				if (findSelectedOption !== i) {
					return "has-duplicate not-empty";
				} else {
					return "not-empty";
				}
			}
		},
		isProductSelectedOtherFieldsError(id, index,i) {
			if (id !== null) {
				let findSelectedOption = _.findIndex(
					this.dataProducts[index].product_split,
					(e) => e.id == id
				);
				if (findSelectedOption !== i) {
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
			// use if error should only show after clicking save
			// if (item !== undefined && item !== 'undefined' && item !== null) {
			//     if ((item.showErrorUnit !== 'undefined' && item.showErrorUnit && item.hasUnitError) ||
			//         (item.showErrorCarton !== 'undefined' && item.showErrorCarton && item.hasCartonError)) {
			//         return true
			//     } else {
			//         return false
			//     }
			// } else {
			//     return false
			// }

			// show if errors should be seen right after user enters data
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
			// show if errors should only show after clicking save
			// if (item !== undefined && item !== 'undefined' && item !== null) {
			//     if (item.showErrorCarton !== 'undefined' && item.showErrorCarton && item.hasCartonError) {
			//         return true
			//     } else {
			//         return false
			//     }
			// } else {
			//     return false
			// }

			// show if errors should be seen right after user enters data
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
			// show if errors should only show after clicking save
			// if (item !== undefined && item !== 'undefined' && item !== null) {
			//     if (item.showErrorUnit !== 'undefined' && item.showErrorUnit && item.hasUnitError) {
			//         return true
			//     } else {
			//         return false
			//     }
			// } else {
			//     return false
			// }

			// show if errors should be seen right after user enters data
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
			const text = "#" + item.category_sku.toLowerCase();
			const textOne = item.category_sku.toLowerCase();
			const textTwo = itemText ? itemText.toLowerCase() : "";
			const textThree = item.name ? item.name.toLowerCase() : "";
			const searchText = queryText ? queryText.toLowerCase() : "";

			return (
				text.indexOf(searchText) > -1 ||
				textOne.indexOf(searchText) > -1 ||
				textTwo.indexOf(searchText) > -1 ||
				textThree.indexOf(searchText) > -1
				// || textPrice.indexOf(searchText) > -1
			);
		},
		totalAmount(item) {
			this.poItem.tax = item.tax;
			this.poItem.shipping = item.shipping;
			this.poItem.discount = item.discount;
			this.poItem.total = item.total;
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
		},
		// split Button
		splitButtonDisabled(product) {
			if (product.carton_count > 0) {
				return product.carton_count <= 1 ? true : false;
			} else {
				return product.units <= 1 ? true : false;
			}
		},
		mergeBottom(item,index,i) {
			let afterItem = i + 1;
			let getItem = this.dataProducts[index].product_split;

			if (afterItem < getItem.length) {
				let mergeQty = 0, mergeUnits = 0, mergeVolume = 0, mergeWeight = 0;

				mergeQty =
					parseInt(item.carton_count) + parseInt(getItem[afterItem].carton_count);
				mergeUnits =
					parseInt(item.units) + parseInt(getItem[afterItem].units);				
				mergeVolume = 
					parseFloat(item.volume) + parseFloat(getItem[afterItem].volume);
				mergeWeight = 
					parseFloat(item.weight) + parseFloat(getItem[afterItem].weight);

				getItem[afterItem].carton_count = mergeQty;
				getItem[afterItem].units = mergeUnits;
				// getItem[afterItem].expected_qty = getItem[afterItem].original_qty_count;
				getItem[afterItem].expected_qty = mergeQty;
				getItem[afterItem].weight = mergeWeight;

				// getItem[afterItem].volume = parseFloat(mergeVolume).toFixed(5);
				let volumeChecker = parseFloat(mergeVolume)
				getItem[afterItem].volume = this.countDecimals(volumeChecker) > 5
						? parseFloat(volumeChecker).toFixed(5) : volumeChecker

				let getIndex = this.dataProducts[index].product_split.indexOf(item);
				this.dataProducts[index].product_split.splice(getIndex, 1);

				if (parseInt(item.carton_count) > item.expected_qty) {
					item.hasError = true;
				} else {
					item.hasError = false;
				}
			}
		},
		mergeTop(item, index, i) {
			let beforeItem = i - 1;
			let getItem = this.dataProducts[index].product_split;

			if (index !== -1) {
				let mergeQty = 0, mergeUnits = 0, mergeVolume = 0, mergeWeight = 0;

				mergeQty =
					parseInt(item.carton_count) + parseInt(getItem[beforeItem].carton_count);
				mergeUnits =
					parseInt(item.units) + parseInt(getItem[beforeItem].units);
				mergeVolume = 
					parseFloat(item.volume) + parseFloat(getItem[beforeItem].volume);
				mergeWeight = 
					parseFloat(item.weight) + parseFloat(getItem[beforeItem].weight);
				 
				getItem[beforeItem].carton_count = mergeQty;
				getItem[beforeItem].units = mergeUnits;
				// getItem[beforeItem].expected_qty = getItem[beforeItem].original_qty_count;
				getItem[beforeItem].expected_qty = mergeQty;
				getItem[beforeItem].weight = mergeWeight;

				// getItem[beforeItem].volume = parseFloat(mergeVolume).toFixed(5);
				let volumeChecker = parseFloat(mergeVolume)
				getItem[beforeItem].volume = this.countDecimals(volumeChecker) > 5
						? parseFloat(volumeChecker).toFixed(5) : volumeChecker

				let getIndex = this.dataProducts[index].product_split.indexOf(item);
				this.dataProducts[index].product_split.splice(getIndex, 1);

				if (parseInt(item.carton_count) > item.expected_qty) {
					item.hasError = true;
				} else {
					item.hasError = false;
				}
			}
		},
		splitProducts(item, index, i) {
			let getItem = this.dataProducts[index].product_split;
			let new_row = {};

			if (item !== null) {
				let new_qty = 0, new_units = 0, new_vol = 0, new_weight = 0;
				
				// parse carton count
				item.carton_count = typeof item.carton_count === "string" && item.carton_count !== "" 
					? parseInt(item.carton_count) : item.carton_count

				if (item.carton_count > 0) {
					if (item.carton_count % 2 !== 0) {
						// subtract 1 if the number is odd
						new_qty = (item.carton_count - 1) / 2;
						new_units = new_qty * item.units_per_carton;
						new_weight = new_units * parseFloat(item.weight_original);

						let volumeChecker1 = parseFloat(new_units * parseFloat(item.volume_original))
						new_vol = this.countDecimals(volumeChecker1) > 5
							? parseFloat(volumeChecker1).toFixed(5)
							: volumeChecker1

						// add the remainder 1 to the previous index
						getItem[i].carton_count = new_qty + 1;
						getItem[i].units = getItem[i].carton_count * getItem[i].units_per_carton;
						getItem[i].expected_qty = getItem[i].carton_count;
						getItem[i].expected_units = getItem[i].units;

						getItem[i].weight = getItem[i].units * parseFloat(getItem[i].weight_original)
						// getItem[i].volume = parseFloat(getItem[i].units * parseFloat(getItem[i].volume_original)).toFixed(5);

						let volumeChecker2 = parseFloat(getItem[i].units * parseFloat(getItem[i].volume_original))
						getItem[i].volume = this.countDecimals(volumeChecker2) > 5
							? parseFloat(volumeChecker2).toFixed(5)
							: volumeChecker2
					} else {
						new_qty = item.carton_count / 2;
						new_units = new_qty * item.units_per_carton;
						new_weight = new_units * parseFloat(item.weight_original)
						// new_vol = parseFloat(new_units * parseFloat(item.volume_original)).toFixed(5);

						let volumeChecker1 = parseFloat(new_units * parseFloat(item.volume_original))
						new_vol = this.countDecimals(volumeChecker1) > 5
							? parseFloat(volumeChecker1).toFixed(5)
							: volumeChecker1

						item.carton_count = new_qty;
						item.units = new_units;
						item.expected_qty = new_qty;
						item.expected_units = new_units;
						item.weight = new_units * parseFloat(item.weight_original)
						// item.volume = parseFloat(new_units * parseFloat(item.volume_original)).toFixed(5);

						let volumeChecker2 = parseFloat(new_units * parseFloat(item.volume_original))
						item.volume = this.countDecimals(volumeChecker2) > 5
							? parseFloat(volumeChecker2).toFixed(5)
							: volumeChecker2
					}
				} 
				// else {
				// 	if (item.units % 2 !== 0) {
				// 		new_units = (item.units - 1) / 2;

				// 		getItem[i].quantity = new_qty;
				// 		getItem[i].units = new_units + 1;
				// 		getItem[i].expected_qty = new_qty;
				// 		getItem[i].expected_units = getItem[i].units;
				// 	} else {
				// 		new_units = item.units / 2;
				// 		item.carton_count = new_qty;
				// 		item.units = new_units;
				// 		item.expected_qty = new_qty;
				// 		item.expected_units = new_units;
				// 	}
				// }

				new_row = {
					id: item.id,
					carton_count: new_qty,
					units: new_units,
					unit_price: item.unit_price,
					amount: 0,
					units_per_carton: item.units_per_carton,
					unship_cartons: 0,
					shipped_cartons: 0,
					shipped_units: 0,
					unshipped_units: 0,
					volume: new_vol,
					weight: new_weight,
					volume_original: item.volume_original,
					weight_original: item.weight_original,
					// volume: item.volume,
					// weight: item.weight,
					ship_date:'',
					// po_product_id:item.po_product_id,
					po_product_id: null,
					expected_crd: item.expected_crd,
					expected_qty: new_qty,
					expected_units: new_units,
				};

				getItem.splice(i + 1, 0, new_row);

			}
			this.dataProducts[index].product_split = getItem;
		},
		fetchPurchaseOrderOptions(qry, type, entity_id, other_id) {
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
			this.po_products_splitting !== this.poItem.po_products_splitting
		) {
			this.dataProducts = this.poItem.po_products_splitting;
		}

		let valid = true;
		//let dataProducts = (typeof this.poItem.products!=='undefined') ? this.poItem.products : []
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
						item.carton_count === "" ||
						item.carton_count === 0 ||
						item.carton_count === null ||
						item.carton_count === "0" ||
						parseFloat(item.carton_count) == 0
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
@import "../../../assets/scss/pages_scss/po/poCreateEditDialog.scss";
</style>
