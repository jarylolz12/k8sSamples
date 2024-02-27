<template>
	<v-dialog v-model="dialog" max-width="1200px" :content-class="isWarehouseConnected == true ? 'create-outbound-dialog create-outbound-dialog-connected' : 'create-outbound-dialog'" :retain-focus="false" persistent v-resize="onResize" scrollable>
		<v-card>			
			<v-card-title>
				<span class="headline">
					{{ outboundItems.isDuplicate ? "Create Outbound" : formTitle }}
				</span>
				<v-spacer></v-spacer>
				<v-btn icon dark class="btn-close" @click="close" :disabled="getOutboundCreateLoading || getOutboundUpdateLoading">
					<v-icon>mdi-close</v-icon>
				</v-btn>
			</v-card-title>

			<v-card-text class="create-outbound-info-wrapper">
				<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
					<div class="create-outbound-info">
						<div class="create-outbound-first-column">
							<div class="outbound-order mb-3">
								<p class="outbound-title">Order Id</p>
								<v-radio-group
									v-model="isCustom"
									mandatory
									hide-details="auto"
									v-if="editedIndex === -1">

									<v-radio label="System Generated" value="generated"></v-radio>
									<v-radio label="Custom Number" value="custom"></v-radio>

									<div class="custom-wrapper mb-1" v-if="isCustom == 'custom'">
										<img
											src="@/assets/icons/item-icon.svg"
											alt=""
											class="box-icon" />

										<v-text-field
											height="40px"
											color="#002F44"
											width="200px"
											type="text"
											dense
											class="text-fields custom"
											placeholder="Enter Order ID"
											outlined
											v-model="outboundItems.order_id"
											:rules="customSkuRules"
											hide-details="auto"
											@wheel="$event.target.blur()">
										</v-text-field>
									</div>
								</v-radio-group>

								<div class="custom-wrapper editing mb-3" v-if="editedIndex > -1">
									<img src="@/assets/icons/item-icon.svg"
										alt=""
										class="box-icon" />

									<v-text-field
										height="40px"
										color="#002F44"
										width="200px"
										type="text"
										dense
										class="text-fields custom"
										placeholder="Enter Order ID"
										outlined
										v-model="outboundItems.order_id"
										:rules="customSkuRules"
										hide-details="auto"
										@wheel="$event.target.blur()">
									</v-text-field>
								</div>
							</div>

							<div class="outbound-customer mb-4" v-if="isWarehouse6PL ==true && !isWarehouseConnected">
								<p class="outbound-title">warehouse customer</p>
								<v-select
									:items="warehouseCustomerLists"
									class="select-product shrink"
									item-text="name"
									item-value="id"
									placeholder="Select Warehouse Customer"
									outlined
									v-model="outboundItems.warehouse_customer_id"
									hide-details="auto"
									:menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true,closeOnContentClick: true }"
									clearable
									@change="clearValueonchange">
									<!-- @change="Warehouse6PL_ProductsOnchange(outboundItems)" -->
									<template v-slot:item="{item,index}">
										<div @click="Warehouse6PL_ProductsOnchange(item,index)" class="py-3" style="width:100% !important">
											<div class="white pb-1" v-if="item.name !== null" style="color: #4A4A4A !important;fontSize:14px">
												{{ item.name }}
											</div>
											<div style="color: #6D858F !important; fontSize:12px">
												{{item.address}}
											</div>
										</div>
									</template>

									<template v-slot:no-data>
										<div v-if="fetchWarehouseCustomersLoading" class="option-items loading"
											style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
											<div class="sku-item">
												<v-progress-circular
													:size="40"
													color="#0171a1"
													indeterminate>
												</v-progress-circular>
											</div>
										</div>
										<div v-if="!fetchWarehouseCustomersLoading" class="option-items no-data"
											style="min-height: 40px; padding: 12px; font-size: 14px;">
											<div class="sku-item">
												No available data
											</div>
										</div>
									</template>
								</v-select>
							</div>

							<div class="outbound-customer mb-4" v-if="!isWarehouse6PL">
								<p class="outbound-title">Customer</p>
								<v-text-field
									placeholder="Enter Customer"
									outlined
									class="text-fields"
									v-model="outboundItems.customer"
									hide-details="auto">
								</v-text-field>
							</div>

							<div class="outbound-deliver mb-4">
								<p class="outbound-title">Shipping To</p>
								<v-textarea
									class="deliver-address"
									outlined
									name="input-7-4"
									placeholder="Enter Address"
									v-model="outboundItems.deliver_to"
									hide-details="auto">
								</v-textarea>
							</div>

							<div class="outbound-reference mb-4">
								<p class="outbound-title">Reference</p>
								<v-text-field
									placeholder="Enter Reference"
									outlined
									class="text-fields"
									v-model="outboundItems.ref_no"
									hide-details="auto">
								</v-text-field>
							</div>

							<div class="outbound-truck mb-4">
								<p class="outbound-title">BOL Number</p>
								<v-text-field
									placeholder="Enter BOL"
									outlined
									class="text-fields"
									v-model="outboundItems.bol_number"
									hide-details="auto">
								</v-text-field>
								<!-- :rules="[v => !!v || 'bol number is Required']" -->
							</div>

							<div class="outbound-truck mb-4">
								<p class="outbound-title">Truck</p>
								<v-text-field
									placeholder="Enter Truck Name"
									outlined
									class="text-fields"
									v-model="outboundItems.name"
									hide-details="auto">
								</v-text-field>
							</div>

							<div class="outbound-date-and-time-wrapper">
								<div class="outbound-eta mb-3">
									<p class="outbound-title">ETA</p>
									<v-text-field
										type="date"
										class="text-fields" 
										placeholder="Select Date" 
										outlined
										hide-details="auto"
										:min="currentDateFind"
										v-model="outboundItems.estimated_date" />
								</div>
								<div class="outbound-time mb-3">
									<p class="outbound-title">TIME <span>(Optional)</span></p>
									<v-text-field
										class="text-fields input-time"
										placeholder="12:30:00"
										v-model="outboundItems.estimated_time"
										type="time"
										outlined
										hide-details="auto"
										hint="FORMAT: 12:00 AM">
									</v-text-field>
								</div>
							</div>       

							<div class="outbound-deliver mb-4">
								<p v-if="!isWarehouseConnected" class="outbound-title"> Note <span>(Optional)</span></p>
								<p v-else class="outbound-title"> special instructions <span>(Optional)</span></p>
								<v-textarea
									class="deliver-address"
									outlined
									name="input-7-4"
									placeholder="Type Notes"
									v-model="outboundItems.notes"
									hide-details="auto">
								</v-textarea>
							</div>
						</div>

						<div class="create-outbound-second-column">
							<!-- <v-tabs
							class="outbound-inner-tab"
							@change="onTabChange"
							v-model="currentTab">

							<v-tab
								v-for="(tab, index) in tabs"
								:key="index"
								:class="index == 2 ? 'outbound-inner-tab-last' : ''"
								@click="scrollToSection(tab.id)">
								{{ tab.name }}
							</v-tab>
							</v-tabs> -->

							<div class="outbound-tables">
								<div id="products-id" class="products-section">
									<h3>Products</h3>

									<v-data-table
										:headers="productsHeader"
										:items="outboundProducts"
										class="elevation-1 add-products-outbound-table"
										mobile-breakpoint="769"
										hide-default-footer>
										<!-- old codes without duplication entry of sku's -->
										<!-- <template v-slot:[`item.shipping_unit`]="{ item, index }">
											<div class="product-selection" v-if="!isMobile">
											<v-select
												:items="shippingUnit"
												class="select-product shrink"
												item-text="name"
												item-value="shipping_unit"
												placeholder="Select Unit"
												:rules="[v => !!v || 'input is Required']"
												outlined
												v-model="outboundProducts[index].shipping_unit"
												:menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
												hide-details="auto"
												:disabled="item.status == 'picked' && editedOutboundIndex > -1">
											</v-select>
											</div>

											<div class="product-mobile-wrapper" v-if="isMobile">
											<div class="product-mobile-header">
												<p>Shipping unit</p>
												<v-btn
												v-show="outboundProducts.length > 1"
												icon
												class="btn remove-btn"
												@click="removeRow(item)"
												:disabled="item.status == 'picked' && editedOutboundIndex > -1">
												<v-icon>mdi-close</v-icon>
												</v-btn>
											</div>

											<v-select
												:items="shippingUnit"
												class="select-product shrink"
												item-text="name"
												item-value="shipping_unit"
												placeholder="Select Unit"
												outlined
												:rules="[v => !!v || 'input is Required']"
												v-model="outboundProducts[index].shipping_unit"
												:menu-props="{
												contentClass: 'product-lists-items',
												bottom: true,
												offsetY: true,
												}"
												hide-details="auto"
												:disabled="item.status == 'picked' && editedOutboundIndex > -1">
											</v-select>
											</div>
										</template>

										<template v-slot:[`item.product_id`]="{ item, index }">
											<div class="product-selection" v-if="!isMobile">
											<v-autocomplete
												:items="productsDropdown"
												class="select-product shrink"
												:class="isProductFieldError(item, index)"
												item-text="name"
												item-value="product_id"
												placeholder="Select Product"
												outlined
												v-model="outboundProducts[index].product_id"
												hide-details="auto"
												:rules="[productError.required]"
												:menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
												:disabled="(item.status === 'picked' || item.status === 'loaded') && editedOutboundIndex > -1">
												<template v-slot:item="{ item }">
																			<div class="option-items">
																				<div class="sku-item">
																					<div class="sku-details">
																						<span>
																							#
																							<span v-if="item.category_id !== null">
																								{{ item.category_id }}-
																							</span>
																							<span>{{ item.sku  }}</span>
																						</span>
																					</div>

																					<div>
																						<p class="name" style="color: #4a4a4a !important;"> 
																							{{ item.name }} 
																						</p>
																					</div>

																					<div>
																						<p class="name" style="font-size: 12px !important;"> 
																							{{ item.in_each_carton !==null ? item.in_each_carton :'--' }} Units/Carton
																						</p>
																					</div>
																				</div>

																				<div class="image-item">
																					<img :src="getImgUrl(item.image)" height="60px" width="60px" alt="">
																				</div>
																			</div>
																		</template>

																		<template v-slot:no-data>
																			<div class="option-items loading" 
																				v-if="getProductInventoryLoading"
																				style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
																				<div class="sku-item">
																					<v-progress-circular
																						:size="40"
																						color="#0171a1"
																						indeterminate>
																					</v-progress-circular>
																				</div>
																			</div>

																			<div class="option-items no-data" 
																				v-if="!getProductInventoryLoading"
																				style="min-height: 40px; padding: 12px; font-size: 14px;">
																				<div class="sku-item">
																					No available data
																				</div>
																			</div>
																		</template>
											</v-autocomplete>
											

											<span
											v-if="hidewareFloorTab ==false"
												class="error-message"
												style="font-size: 12px; color: red" >
											{{ isProductSelected(item, index) }}
											</span>
											<span v-else
											style="font-size: 11px; color: grey">
												{{isProductSelected3pl(item,index)}}
											</span>
											</div>

											<div class="product-mobile-wrapper" v-if="isMobile">
											<div class="product-mobile-header">
												<p>Product</p>
											</div>

											<v-autocomplete
												:disabled="(item.status === 'picked' || item.status === 'loaded') && editedOutboundIndex > -1"
												:items="productsDropdown"
												class="select-product shrink"
												item-text="name"
												item-value="product_id"
												placeholder="Select Product"
												:rules="[productError.required]"
												outlined
												v-model="item.product_id"
												:menu-props="{ contentClass: 'product-lists-items' }"
												hide-details="auto">
												<template v-slot:item="{ item }">
																			<div class="option-items">
																				<div class="sku-item">
																					<div class="sku-details">
																						<span>
																							#
																							<span v-if="item.category_id !== null">
																								{{ item.category_id }}-
																							</span>
																							<span>{{ item.sku  }}</span>
																						</span>
																					</div>

																					<div>
																						<p class="name" style="color: #4a4a4a !important;"> 
																							{{ item.name }} 
																						</p>
																					</div>

																					<div>
																						<p class="name" style="font-size: 12px !important;"> 
																							{{ item.in_each_carton !==null ? item.in_each_carton :'--' }} Units/Carton
																						</p>
																					</div>
																				</div>

																				<div class="image-item">
																					<img :src="getImgUrl(item.image)" height="60px" width="60px" alt="">
																				</div>
																			</div>
																		</template>

																		<template v-slot:no-data>
																			<div class="option-items loading" 
																				v-if="getProductInventoryLoading"
																				style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
																				<div class="sku-item">
																					<v-progress-circular
																						:size="40"
																						color="#0171a1"
																						indeterminate>
																					</v-progress-circular>
																				</div>
																			</div>

																			<div class="option-items no-data" 
																				v-if="!getProductInventoryLoading"
																				style="min-height: 40px; padding: 12px; font-size: 14px;">
																				<div class="sku-item">
																					No available data
																				</div>
																			</div>
																		</template>
											</v-autocomplete>
											</div>
										</template>

										<template v-slot:[`item.quantity`]="{ item,index }">
											<div :class="statusLoaded(outboundProducts[index].quantity,item) ? '':'mywidth'">
											<v-text-field
												placeholder="0"
												type="number"
												outlined
												class="table-text-fields itu-total-unit shrink"
												v-model="outboundProducts[index].quantity"
												:min="minValue"
												:rules="[quantityCountCheckError(outboundProducts[index].quantity,item),statusLoaded(item),unitRules.required,unitRules.greater]"
												hide-details="auto"
												:disabled="item.status == 'picked' && editedOutboundIndex > -1"
												@keydown="restrictValues($event)"
												@wheel="$event.target.blur()">
											</v-text-field>

											</div>
											<span
												class="error-message"
												style="font-size: 12px; color: red" >
											{{statusLoadedError(item)}} {{minimumValueError(outboundProducts[index].quantity)}}
											</span>
										</template>

										<template v-slot:[`item.actions`]="{ item }">
											<v-btn
											v-show="outboundProducts.length > 1"
											icon
											class="btn remove-btn"
											@click="removeRow(item)"
											:disabled="item.status == 'picked' && editedOutboundIndex > -1">
											<v-icon>mdi-close</v-icon>
											</v-btn>
										</template>							 -->
										<template v-slot:item="{ item, index }">
											<tr class="inbound-row-data" v-if="!isMobile">
												<td class="inbound-col-data">
													<div class="product-selection">
														<v-autocomplete
															:items="productsDropdownOutbound"
															:filter="customFilter"
															class="select-product shrink"
															:class="isProductFieldError(item, index)"
															item-text="name"
															item-value="product_id"
															:placeholder="fetchProductLoading ? 'Fetching products...' : 'Select Product'"
															outlined
															v-model="outboundProducts[index].product_id"
															hide-details="auto"
															:rules="[productError.required]"
															validate-on-blur
															@blur="() => $refs.form.resetValidation()"
															:menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
															:disabled="((item.status === 'picked' || item.status === 'loaded' ) && editedOutboundIndex > -1) || fetchProductLoading">
															<template v-slot:item="{ item }">
																<div class="option-items">
																	<div class="sku-item">
																		<div class="sku-details">
																			<span>
																			#
																			<span v-if="item.category_id !== null">
																				{{ item.category_id }}-
																				</span>
																				<span>{{ item.sku }}</span>
																				</span>
																		</div>

																		<div>
																			<p class="name" style="color: #4a4a4a !important;"> 
																				{{ item.name }} 
																			</p>
																		</div>

																		<div>
																			<p class="name" style="font-size: 12px !important;"> 
																				{{ item.in_each_carton !==null ? item.in_each_carton :'--' }} Units/Carton
																			</p>
																		</div>
																	</div>

																	<div class="image-item">
																		<img :src="getImgUrl(item.image)" height="60px" width="60px" alt=""/>
																	</div>
																</div>
															</template>

															<template v-slot:no-data>
																<div class="option-items loading" 
																	v-if="fetchProductLoading"
																	style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
																	<div class="sku-item">
																		<v-progress-circular
																			:size="40"
																			color="#0171a1"
																			indeterminate>
																		</v-progress-circular>
																	</div>
																</div>
																<!-- v-if="!getProductInventoryLoading" -->
																<div class="option-items no-data" 
																	v-if="!fetchProductLoading"
																	style="min-height: 40px; padding: 12px; font-size: 14px;">
																	<div class="sku-item">
																		No available data
																	</div>
																</div>
															</template>
														</v-autocomplete>
														<span class="error-message"
															style="font-size: 11px; color: transparent"
															v-if="minimumValueError(outboundProducts[index].quantity) !==''" >.
														</span>
													</div>
												</td>

												<td class="inbound-col-data">
													<div class="product-selection">
														<v-select
															:items="itemDisabled(outboundProducts[index].product_id) === true ? shippingUnit : shippingUnitCopy"
															class="select-product shrink"
															item-text="name"
															item-value="shipping_unit"
															placeholder="Select Unit"
															:rules="[v => !!v || 'input is Required']"
															outlined
															v-model="outboundProducts[index].shipping_unit"
															:menu-props="{ bottom: true, offsetY: true }"
															hide-details="auto"
															:disabled="(item.status == 'picked' && editedOutboundIndex > -1) || fetchProductLoading">
														</v-select>
														<span class="error-message"
															style="font-size: 11px; color: transparent;"
															v-if="minimumValueError(outboundProducts[index].quantity) !==''" >.
														</span>
													</div>
												</td>

												<td class="inbound-col-data">
													<div>
														<v-text-field
															placeholder="0"
															type="number"
															outlined
															class="table-text-fields itu-total-unit shrink"
															v-model="outboundProducts[index].quantity"
															:min="minValue"
															:rules="[unitRules.required,unitRules.greater]"
															hide-details="auto"
															:disabled="(item.status == 'picked' && editedOutboundIndex > -1) || fetchProductLoading"
															@keydown="restrictValues($event)"
															@wheel="$event.target.blur()">
														</v-text-field>
														<span class="error-message"
															style="font-size: 11px; color: #ff5252;"
															v-if="minimumValueError(outboundProducts[index].quantity) !==''" ><span style="font-size: 11px; color: transparent;">.</span>
															<span>
																{{minimumValueError(outboundProducts[index].quantity)}}
															</span>
														</span>
													</div>
												</td>

												<td v-if="isWarehouseConnected" class="inbound-col-data">
													<div class="connected-warehouse-oubound-instructions">
														<input 
															class="instructions-input-field-outbound"
															type="text"
															v-model="outboundProducts[index].instructions"
															placeholder="Type instructions here"
															@wheel="$event.target.blur()"
															:disabled="fetchProductLoading" />
														<!-- <v-text-field
															v-model="outboundProducts[index].instructions"
															placeholder="instructions"
															type="text"
															outlined
															class="shrink instruction-create-outbound"
															hide-details="auto"
															:disabled="fetchProductLoading"
															@wheel="$event.target.blur()"
															maxlength="50">
														</v-text-field> -->
														<span
															class="error-message"
															style="font-size: 11px; color: transparent"
															v-if="minimumValueError(outboundProducts[index].quantity) !==''" >.
														</span>
													</div>
												</td>
												
												<td class="inbound-col-data">
													<v-btn
														v-show="outboundProducts.length > 1"
														icon
														class="btn remove-btn"
														@click="removeRow(item)"
														:disabled="(item.status == 'picked' && editedOutboundIndex > -1) || fetchProductLoading">
														<v-icon>mdi-close</v-icon>
													</v-btn>
													<span
														class="error-message"
														style="font-size: 11px; color: transparent"
														v-if="minimumValueError(outboundProducts[index].quantity) !==''" >.
													</span>
												</td>
											</tr>

											<tr class="inbound-row-data pb-0" v-if="isMobile">
												<td class="inbound-col-data">
													<div class="product-selection" >
														<div class="product-mobile-wrapper">
															<div class="product-mobile-header-product">
																<p>Product</p>
																<v-btn
																	v-show="outboundProducts.length > 1"
																	icon
																	class="btn remove-btn"
																	@click="removeRow(item)"
																	:disabled="(item.status == 'picked' && editedOutboundIndex > -1) || fetchProductLoading">
																	<v-icon>mdi-close</v-icon>
																</v-btn>
															</div>
														</div>

														<v-autocomplete
															:items="productsDropdownOutbound"
															:filter="customFilter"
															class="select-product shrink"
															:class="isProductFieldError(item, index)"
															item-text="name"
															item-value="product_id"
															placeholder="Select Product"
															outlined
															v-model="outboundProducts[index].product_id"
															hide-details="auto"
															:rules="[productError.required]"
															:menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
															:disabled="((item.status === 'picked' || item.status === 'loaded' ) && editedOutboundIndex > -1) || fetchProductLoading">
															<template v-slot:item="{ item }">
																<div class="option-items">
																	<div class="sku-item">
																		<div class="sku-details">
																			<span>
																			#
																			<span v-if="item.category_id !== null">
																				{{ item.category_id }}-
																				</span>
																				<span>{{ item.sku }}</span>
																				</span>
																		</div>

																		<div>
																			<p class="name" style="color: #4a4a4a !important;"> 
																				{{ item.name }} 
																			</p>
																		</div>

																		<div>
																			<p class="name" style="font-size: 12px !important;"> 
																				{{ item.in_each_carton !==null ? item.in_each_carton :'--' }} Units/Carton
																			</p>
																		</div>
																	</div>

																	<div class="image-item">
																		<img :src="getImgUrl(item.image)" height="60px" width="60px" alt=""/>
																	</div>
																</div>
															</template>

															<template v-slot:no-data>
																<div class="option-items loading" 
																	v-if="fetchProductLoading"
																	style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
																	<div class="sku-item">
																		<v-progress-circular
																			:size="40"
																			color="#0171a1"
																			indeterminate>
																		</v-progress-circular>
																	</div>
																</div>
																<!-- v-if="!getProductInventoryLoading" -->
																<div class="option-items no-data" 
																	v-if="!fetchProductLoading"
																	style="min-height: 40px; padding: 12px; font-size: 14px;">
																	<div class="sku-item">
																		No available data
																	</div>
																</div>
															</template>
														</v-autocomplete>
														<span
															class="error-message"
															style="font-size: 11px; color: white;"
															v-if="minimumValueError(outboundProducts[index].quantity) !==''" >.
														</span>
													</div>
												</td>

												<td class="unit-qty-col-wrapper">
													<div class="unit-qty-wrapper">
														<td class="inbound-col-data shipping-unit-mobile">
															<div class="product-mobile-wrapper">
																<div class="product-mobile-header">
																	<p>Shipping unit</p>
																</div>
																<v-select
																	:items="shippingUnit"
																	class="select-product shrink"
																	item-text="name"
																	item-value="shipping_unit"
																	placeholder="Select Unit"
																	:rules="[v => !!v || 'input is Required']"
																	outlined
																	v-model="outboundProducts[index].shipping_unit"
																	:menu-props="{ bottom: true, offsetY: true }"
																	hide-details="auto"
																	:disabled="(item.status == 'picked' && editedOutboundIndex > -1) || fetchProductLoading">
																</v-select>
																<span
																	class="error-message"
																	style="font-size: 11px; color: white;"
																	v-if="minimumValueError(outboundProducts[index].quantity) !==''" >.
																</span>
															</div>
														</td>

														<td class="inbound-col-data text-end">
															<div class="product-mobile-wrapper">
																<div class="product-mobile-header">
																	<p class="text-end">Quantity</p>
																</div>
																<v-text-field
																	placeholder="0"
																	type="number"
																	outlined
																	class="table-text-fields itu-total-unit shrink"
																	v-model="outboundProducts[index].quantity"
																	:min="minValue"
																	:rules="[unitRules.required,unitRules.greater]"
																	hide-details="auto"
																	:disabled="(item.status == 'picked' && editedOutboundIndex > -1) || fetchProductLoading"
																	@keydown="restrictValues($event)"
																	@wheel="$event.target.blur()">
																</v-text-field>
																<span
																	class="error-message"
																	
																	v-if="minimumValueError(outboundProducts[index].quantity) !==''" > <span style="font-size: 11px; color: white;">.</span>
																	<span>
																	{{minimumValueError(outboundProducts[index].quantity)}}
																	</span>
																</span>
															</div>
														</td>
													</div>
												</td>
											</tr>

											<tr v-if="isProductSelected(item, index)">
												<td colspan="12" class="error-skus-duplication">
													{{ isProductSelected(item, index) }}
												</td>
											</tr>

											<tr v-if="quantityCountCheckError(outboundProducts[index].quantity,item)==false  || statusLoaded(item) ==false || quantityCountCheckError6PL(outboundProducts[index].quantity,item) ==false">
												<td colspan="12" class="error-skus-duplication" :class="quantityCountCheckError6PL(outboundProducts[index].quantity,item) ==false ? 'color_change':''">
													{{ quantityCountCheckErrorShow(outboundProducts[index].quantity,item) }} {{statusLoadedError(item)}}
													{{quantityCountCheckErrorShow6PL(outboundProducts[index].quantity,item)}} 
												</td>
											</tr>
										</template>
									</v-data-table>

									<div class="add-row-wrapper">
										<button class="btn-white add-btn" @click="addRow">
											+ Add Product
										</button>
									</div>
								</div>

							<!-- <div id="storable-units-id" class="storable-section">
								<h3>Storable Units</h3>

								<v-data-table
								:headers="headersStorable"
								:items="itemStorable"
								class="elevation-1 add-storable-outbound-table"
								mobile-breakpoint="769"
								hide-default-footer
								fixed-header
								:expanded.sync="expanded1"
								single-expand
								show-expand
								:item-class="itemRowBackground">

								<template v-slot:[`item.type`]="{ item }">
									<div class="inventory-wrapper">
									<div style="text-transform: capitalize">
									{{ item.type !== null ? item.type : "--" }}
									</div>
									</div>
								</template>

								<template v-slot:[`item.label`]="{ item }">
									<div v-if="!isMobile"
									class="inventory-wrapper inventory-blue-text">
									<div>{{ item.label !== null ? item.label : "--" }}</div>
									</div>

									<div v-if="isMobile"
									class="inventory-blue-text mx-0 px-0 my-0 py-0 d-flex justify-space-between">
									<div>
										<span class="labelColor">Label</span>
										<span class="skuColor">
										{{ item.label !== null ? `#${item.label}` : "--" }}
										</span>
									</div>

									<v-btn
										v-show="itemStorable.length > 1"
										icon
										class="btn remove-btn"
										@click="removeRowStorable(item)" >
										<v-icon>mdi-close</v-icon>
									</v-btn>
									</div>

									<div
									v-if="isMobile"
									class="inventory-blue-text d-flex pt-3 justify-space-between">
									<span class="labelColor"> Dimensions </span>
									<span>
										<div 
										v-if="item.parse_dimensions && typeof item.parse_dimensions == 'string'">
										{{ JSON.parse(item.parse_dimensions).l }}x
										{{ JSON.parse(item.parse_dimensions).w }}x
										{{ JSON.parse(item.parse_dimensions).h }}
										{{ JSON.parse(item.parse_dimensions).uom }}
										</div>

										<div
										v-if="item.parse_dimensions && typeof item.parse_dimensions == 'object'">
										{{ item.parse_dimensions.l }}x
										{{ item.parse_dimensions.w }}x
										{{ item.parse_dimensions.h }}
										{{ item.parse_dimensions.uom }}
										</div>
									</span>
									</div>

									<div
									v-if="isMobile"
									class="
									inventory-blue-text
									d-flex
									pt-3
									justify-space-between
									"
									>
									<span class="labelColor"> Weight </span>
									<span>
									<div
										v-if="
										item.parse_weight &&
										typeof item.parse_weight == 'string'
										"
									>
										{{ JSON.parse(item.parse_weight).value }}
										{{ JSON.parse(item.parse_weight).unit }}
									</div>
									<div
										v-if="
										item.parse_weight &&
										typeof item.parse_weight == 'object'
										"
									>
										{{ item.parse_weight.value }}
										{{ item.parse_weight.unit }}
									</div>
									</span>
									</div>
								</template>

								<template v-slot:expanded-item="{ headers, item }">
									<td v-if="!isMobile" :colspan="headers.length">
									<div class="expanded-item-info">
										<div class="expanded-header-wrapper">
										<div class="expanded-header-content">
										<div class="header-title w-80">SKU</div>
										<div class="header-title w-10">CARTON</div>
										<div class="header-title w-10">UNIT</div>
										</div>
										</div>

										<div v-if="item.storable_unit_products" class="expanded-body-wrapper">
										<div class="expanded-body-content"
											v-for="(v, index) in item.storable_unit_products"
											:key="index" >

											<div class="header-data w-80">
											#{{ v.product.sku }}{{ v.product.name }}
											</div>
											<div class="header-data w-10">
											{{ v.carton_count }}
											</div>
											<div class="header-data w-10">
											{{ v.total_unit }}
											</div>
										</div>
										</div>

										<div v-if="typeof item.outbound_storable_unit_products !== 'undefined' &&
										item.outbound_storable_unit_products !== null &&
										item.outbound_storable_unit_products.length > 0"
										class="expanded-body-wrapper">

										<div class="expanded-body-content"
											v-for="(v, index) in item.outbound_storable_unit_products"
											:key="index">

											<div v-if="item.outbound_storable_unit_products"
											class="header-data w-80">
											{{GetNameAndSku(item.outbound_storable_unit_products)}}
											#{{ v.product.sku }}{{v.product.name}}
											</div>

											<div class="header-data w-10">
											{{ v.carton_count }}
											</div>
											<div class="header-data w-10">
											{{ v.total_unit }}
											</div>
										</div>
										</div>
									</div>
									</td>

									<td v-if="isMobile" :colspan="headers.length">
									<div class="cartons-separator"
										v-if="item.storable_unit_products">
										<p class="ml-3">
										{{
											item.storable_unit_products !== null &&
											item.storable_unit_products.length > 0
											? item.storable_unit_products.length
											: 0
										}}
										sku
										</p>
										<span class="separator"></span>
										<p>
										{{ getTotalCartonsAndUnit(item, "carton") }} cartons
										</p>
										<span class="separator"></span>
										<p>
										{{ getTotalCartonsAndUnit(item, "unit") }} units
										</p>
									</div>

									<div class="cartons-separator"
										v-if="typeof item.outbound_storable_unit_products !== 'undefined' &&
										item.outbound_storable_unit_products !== null &&
										item.outbound_storable_unit_products.length > 0">

										<p class="ml-3">
										{{
											item.outbound_storable_unit_products !== null &&
											item.outbound_storable_unit_products.length > 0
											? item.outbound_storable_unit_products.length
											: 0
										}}
										sku
										</p>
										<span class="separator"></span>
										<p>
										{{ getTotalCartonsAndUnits(item, "carton") }} cartons
										</p>
										<span class="separator"></span>
										<p>
										{{ getTotalCartonsAndUnits(item, "unit") }} units
										</p>
									</div>
									</td>
								</template>

								<template v-slot:[`item.dimension`]="{ item }">
									<div class="item-wrapper" v-if="!isMobile">
									<div v-if="item.parse_dimensions &&
										typeof item.parse_dimensions == 'string'">
										{{ JSON.parse(item.parse_dimensions).l }}x
										{{ JSON.parse(item.parse_dimensions).w }}x
										{{ JSON.parse(item.parse_dimensions).h }}
										{{ JSON.parse(item.parse_dimensions).uom }}
									</div>

									<div v-if="item.parse_dimensions &&
										typeof item.parse_dimensions == 'object'">
										{{ item.parse_dimensions.l }}x
										{{ item.parse_dimensions.w }}x
										{{ item.parse_dimensions.h }}
										{{ item.parse_dimensions.uom }}
									</div>
									</div>
								</template>

								<template v-slot:[`item.weight`]="{ item }">
									<div class="item-wrapper" v-if="!isMobile">
									<div v-if="item.parse_weight &&
										typeof item.parse_weight == 'string'">
										{{ JSON.parse(item.parse_weight).value }}
										{{ JSON.parse(item.parse_weight).unit }}
									</div>

									<div v-if=" item.parse_weight &&
										typeof item.parse_weight == 'object'">
										{{ item.parse_weight.value }}
										{{ item.parse_weight.unit }}
									</div>
									</div>
								</template>

								<template v-slot:[`item.total_carton_count`]="{ item }">
									<span v-if="item.total_carton_count">
									{{
										item.total_carton_count !== null
										? item.total_carton_count
										: 0
									}}
									</span>

									<span v-if="item.outbound_storable_unit_products">
									{{
										item.outbound_storable_unit_products[0].carton_count !==
										null
										? item.outbound_storable_unit_products[0].carton_count
										: 0
									}}
									</span>
								</template>

								<template v-slot:[`item.total_units`]="{ item }">
									<span>
									{{
										item.total_units !== null ? item.total_units : 0
									}}
									</span>
								</template>

								<template v-slot:[`item.actions`]="{ item }">
									<v-btn
									v-show="itemStorable.length > 1"
									icon
									class="btn remove-btn"
									@click="removeRowStorable(item)">
									<v-icon>mdi-close</v-icon>
									</v-btn>
								</template>
								</v-data-table>

								<div class="add-row-wrapper">
								<button class="btn-white add-btn" @click.stop="createStorable" >
									+ Add Storable Unit
								</button>
								</div>

								<v-dialog
								v-model="dialogStorable"
								max-width="960px"
								content-class="storable-outbound-dialog"
								persistent>

								<v-card>
									<v-card-title>
									<span class="headline"> Add Storable Unit </span>

									<v-spacer></v-spacer>

									<v-btn
										icon
										dark
										class="btn-close"
										@click="closeStorable">
										<v-icon>mdi-close</v-icon>
									</v-btn>
									</v-card-title>

									<v-card-text>
									<div class="storableDialog-header">
									<Search
										placeholder="Search Storable Unit"
										className="search custom-search inventory-tab-search"
										:inputData.sync="search"
									/>
									</div>
									<v-data-table
									:headers="headersStorableDialog"
									:items="combineArray"
									class="storable-outbound-table"
									:class="
										combineArray.length == 0 ? 'no-data-table' : ''
									"
									mobile-breakpoint="769"
									fixed-header
									:expanded.sync="expanded2"
									single-expand
									show-expand
									show-select
									:item-class="itemRowBackground"
									hide-default-footer
									v-model="selectedInside"
									>
									<template v-slot:expanded-item="{ headers, item }">
										<td :colspan="headers.length">
										<div class="expanded-item-info">
										<div class="expanded-header-wrapper">
										<div class="expanded-header-content">
											<div class="header-title w-80">SKU</div>
											<div class="header-title w-10">CARTON</div>
											<div class="header-title w-10">UNIT</div>
										</div>
										</div>
										<div class="expanded-body-wrapper">
										<div
											class="expanded-body-content"
											v-for="(
											v, index
											) in item.storable_unit_products"
											:key="index"
										>
											<div class="header-data w-80">
											#{{ v.product.sku }} {{ v.product.name }}
											</div>
											<div class="header-data w-10">
											{{ v.carton_count }}
											</div>
											<div class="header-data w-10">
											{{ v.total_unit }}
											</div>
										</div>
										</div>
										</div>
										</td>
									</template>

									<template v-slot:[`item.type`]="{ item }">
										<div class="inventory-wrapper">
										<div style="text-transform: capitalize">
										{{ item.type !== null ? item.type : "--" }}
										</div>
										</div>
									</template>

									<template v-slot:[`item.label`]="{ item }">
										<div class="inventory-wrapper inventory-blue-text">
										<div>
										{{ item.label !== null ? item.label : "--" }}
										</div>
										</div>
									</template>

									<template v-slot:[`item.spec`]="{ item }">
										<div class="storable-spec-wrapper">
										<div class="inventory-wrapper">
										<div>
										{{ item.parse_dimensions.l }}x{{
											item.parse_dimensions.w
										}}x{{ item.parse_dimensions.h }}
										{{ item.parse_dimensions.uom }}
										</div>
										</div>

										<div class="weight">
										<div>
										{{ item.parse_weight.value }}
										{{ item.parse_weight.unit }}
										</div>
										</div>
										</div>
									</template>

									<template v-slot:[`item.location`]="{ item }">
										<div class="inventory-wrapper">
										<div>
										{{
										item.location !== null
											? item.location.shelf +
											item.location.row +
											item.location.rack
											: "--"
										}}
										</div>
										</div>
									</template>

									<template v-slot:[`item.no_sku`]="{ item }">
										<span>{{ item.no_of_sku }}</span>
									</template>

									<template
										v-slot:[`item.total_carton_count`]="{ item }"
									>
										<span>{{
										item.total_carton_count !== null
										? item.total_carton_count
										: 0
										}}</span>
									</template>

									<template v-slot:[`item.total_units`]="{ item }">
										<span>{{
										item.total_units !== null ? item.total_units : 0
										}}</span>
									</template>

									<template v-slot:no-data>
										<div
										class="loading-wrapper"
										v-if="getStorableUnitsLoading"
										>
										<v-progress-circular
										:size="40"
										color="#0171a1"
										indeterminate
										>
										</v-progress-circular>
										</div>

										<div
										class="no-data-wrapper"
										v-if="
										!getStorableUnitsLoading &&
										combineArray.length == 0
										"
										>
										<div class="no-data-heading">
										<img
										src="@/assets/icons/empty-inventory-icon.svg"
										width="40px"
										height="42px"
										alt=""
										/>

										<h3>Empty Storable Unit</h3>
										</div>
										</div>
									</template>
									</v-data-table>
									</v-card-text>

									<v-card-actions class="storable-unit-button-attach">
									<v-btn @click="addStorableItemInside" class="btn-blue">
										Add Storable Unit
									</v-btn>

									<v-btn
										v-if="!isMobile"
										@click="closeStorable"
										class="btn-white">
										Cancel
									</v-btn>
									</v-card-actions>
								</v-card>
								</v-dialog>
							</div> -->

							<div id="documents-id" class="documents-upload-wrapper mt-7">
								<h3>Documents</h3>

								<div class="documents-section">
								<p class="outbound-title">ATTACHMENTS</p>

								<div
									id="documents-files-select-box-id"
									class="documents-files-select-box"
									@click.stop="addDocuments()">
									Browse Files

									<button
									class="btn-white btn-upload"
									@click.stop="addDocuments()">

									<img
										src="@/assets/icons/upload.svg"
										width="16px"
										height="16px"/>
									Upload
									</button>
								</div>

								<input
									ref="documents_reference"
									type="file"
									id="documents_files"
									@change="(e) => inputChanged(e)"
									name="documents[]"
									accept=".pdf,.docx"
									multiple />
								</div>

								<div class="document-lists"
								v-if="files !== null &&
								files.length !== 'undefined' &&
								files.length > 0">

								<div
									class="lists-items"
									v-for="(file, index) in files"
									:key="index">
									<div class="items">
									<span class="d-flex align-center">
										<img
										src="@/assets/images/document.svg"
										width="14px"
										height="14px"
										class="mr-2" />
									<span style="color: #4a4a4a;">{{ getNameWithoutExt(file.name) }}</span>  
									</span>

									<button class="close" @click="remove(index)">
										<img
										src="@/assets/images/close.svg"
										width="18px"
										height="18px" />
									</button>
									</div>
								</div>
								</div>
							</div>
							</div>
						</div>
					</div>
				</v-form>
			</v-card-text>

			<v-card-actions v-if="!isWarehouseConnected" class="outbound-button-actions">
				<button
					v-if="isWarehouse3PL ==true && editedIndex > -1 && !outboundItems.isDuplicate"
					class="btn-blue"
					text
					@click="buildAndPrintfor3plEdit"
					:disabled="getoutboundUpdate3plLoading">
					<span v-if="editedIndex > -1 && !outboundItems.isDuplicate">
						{{ getoutboundUpdate3plLoading ? "Saving Changes..." : "Save Changes" }}
					</span>
				</button>
				<button
					v-else
					class="btn-blue"
					text
					@click="buildAndPrint('not-connected')"
					:disabled="getOutboundCreateLoading || getOutboundUpdateLoading">
					
					<span v-if="editedIndex == -1 || outboundItems.isDuplicate">
						{{ getOutboundCreateLoading ? "Creating Outbound..." : "Create Outbound" }}
					</span>

					<span v-if="editedIndex > -1 && !outboundItems.isDuplicate">
						{{ getOutboundUpdateLoading ? "Saving Changes..." : "Save Changes" }}
					</span>
				</button>

				<button :disabled="getOutboundCreateLoading || getOutboundUpdateLoading || getoutboundUpdate3plLoading" class="btn-white" style="color: #4a4a4a !important;" text @click="close">Cancel</button>
			</v-card-actions>

			<v-card-actions v-else class="outbound-button-actions">
				<button
					class="btn-blue"
					text
					@click="buildAndPrint('pending')"
					:disabled="getOutboundCreateLoading || getOutboundUpdateLoading || isCreateAndSubmitting || isSavingDraft">
					
					<span v-if="editedIndex == -1 || outboundItems.isDuplicate">
						{{ isCreateAndSubmitting ? "Creating..." : "Create & Submit" }}
					</span>

					<span v-if="editedIndex > -1 && !outboundItems.isDuplicate">
						{{ isUpdateAndSubmitting ? "Saving..." : "Save & Submit" }}
					</span>
				</button>
				<button
					class="btn-white"
					style="font-weight:500"
					text
					@click="buildAndPrint('draft')"
					:disabled="getOutboundCreateLoading || getOutboundUpdateLoading">
					
					<span v-if="editedIndex == -1 || outboundItems.isDuplicate">
						{{ isSavingDraft ? "Saving..." : "Save as Draft" }}
					</span>

					<span v-if="editedIndex > -1 && !outboundItems.isDuplicate">
						{{ isUpdatingDraft ? "Saving..." : "Save as Draft" }}
					</span>
				</button>

				<button 
					:disabled="getOutboundCreateLoading || getOutboundUpdateLoading || getoutboundUpdate3plLoading" 
					class="btn-white" style="color: #4a4a4a !important;" text @click="close">
					Cancel
				</button>
			</v-card-actions>		
		</v-card>

		<ConfirmDialog :dialogData.sync="showWarning_Warehouse_Product_Dialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert">
				</div>
			</template>
			
			<template v-slot:dialog_title>
				<h2> Update Warehouse Customer</h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					By changing the Warehouse Customer, the exising products will be removed. Do you want to continue
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="confirmChangeCustomerDropdown" text>
					Update
				</v-btn>

				<v-btn class="btn-white" text @click="closeWarning">
					Discard
				</v-btn>
			</template>
		</ConfirmDialog>
	</v-dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
// import Search from "../../Search.vue";
// import StorableOutboundDialog from '@/StorableOutboundDialog.vue'
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue'
import _ from "lodash";
import moment from "moment";
import globalMethods from "../../../utils/globalMethods";

export default {
  	name: "CreateOutboundDialog",
  	props: [
		"dialogCreate",
		"editedOutboundIndex",
		"editedOutboundItems",
		"defaultOutboundItems",
		"dropdownProducts",
		"currentWarehouseSelected",
		"isFetchSingleOutboundbound",
		"status",
		"outboundProducts_clone",
		"fetchProductLoading",
		"productsListsData",
		"customerListsData",
		"fetchWarehouseCustomersLoading",
		"isWarehouseConnected"
  	],
  	components: {
		// Search,
		// StorableOutboundDialog
		ConfirmDialog
  	},
  	data: () => ({
		minValue:0,
		combineArray: [],
		current_page: 1,
		valid: true,
		search: "",
		headers: [
	  		{
				text: "SKU",
				align: "start",
				sortable: false,
				value: "product_id",
				fixed: true,
				width: "45%",
	  		},
	  		{
				text: "SHIPPING UNIT",
				align: "start",
				sortable: false,
				value: "shipping_unit",
				fixed: true,
				width: "15%",
	  		},
	  		{
				text: "QUANTITY",
				align: "end",
				sortable: false,
				value: "quantity",
				fixed: true,
				width: "15%",
	  		},
			{
				text: "INSTRUCTIONS",
				align: "start",
				sortable: false,
				value: "instructions",
				fixed: true,
				width: "35%",
	  		},
	  		{
				text: "",
				align: "end",
				sortable: false,
				value: "actions",
				fixed: true,
				width: "5%",
	  		},
		],
		headersStorable: [
	  		{
				text: "Label",
				align: "start",
				sortable: false,
				value: "label",
				fixed: true,
				width: "7%",
	  		},
	  		{ text: "", value: "data-table-expand" },
	  		{
				text: "Type",
				align: "start",
				sortable: false,
				value: "type",
				fixed: true,
				width: "15%",
	  		},
	  		{
				text: "Dimension",
				align: "start",
				sortable: false,
				value: "dimension",
				fixed: true,
				width: "20%",
	  		},
	  		{
				text: "Weight",
				align: "start",
				sortable: false,
				value: "weight",
				fixed: true,
				width: "15%",
	  		},
	  		{
				text: "Carton",
				align: "end",
				sortable: false,
				value: "total_carton_count",
				fixed: true,
				width: "10%",
	  		},
	  		{
				text: "No of SKU",
				align: "end",
				sortable: false,
				value: "no_of_sku",
				fixed: true,
				width: "10%",
	  		},
	  		{
				text: "",
				align: "end",
				sortable: false,
				value: "actions",
				fixed: true,
				width: "10%",
	  		},
		],
		itemStorable: [],
		headersStorableDialog: [
	  		{
				text: "Label",
				align: "start",
				sortable: false,
				value: "label",
				fixed: true,
				width: "7%",
	  		},
	  		{ text: "", value: "data-table-expand" },
	  		{
				text: "Type",
				align: "start",
				sortable: false,
				value: "type",
				fixed: true,
				width: "8%",
	  		},
	  		{
				text: "Spec",
				align: "start",
				sortable: false,
				value: "spec",
				fixed: true,
				width: "20%",
	  		},
	  		{
				text: "Location",
				align: "start",
				sortable: false,
				value: "location",
				fixed: true,
				width: "20%",
	  		},
	  		{
				text: "No of SKU",
				align: "end",
				sortable: false,
				value: "no_of_sku",
				fixed: true,
				width: "10%",
	  		},
	  		{
				text: "Carton",
				align: "end",
				sortable: false,
				value: "total_carton_count",
				fixed: true,
				width: "10%",
	  		},
	  		{
				text: "Unit",
				align: "end",
				sortable: false,
				value: "total_units",
				fixed: true,
				width: "10%",
	  		},
		],
		outboundHeaders: [
	  		{
				text: "PRODUCT",
				align: "start",
				sortable: false,
				value: "product",
				fixed: true,
				width: "45%",
	  		},
	  		{
				text: "CARTON",
				align: "end",
				sortable: false,
				value: "carton_count",
				fixed: true,
				width: "20%",
	  		},
	  		{
				text: "UNIT",
				align: "end",
				sortable: false,
				value: "total_unit",
				fixed: true,
				width: "20%",
	  		},
	  		{
				text: "",
				align: "end",
				sortable: false,
				value: "actions",
				fixed: true,
				width: "5%",
	  		},
		],
		counter: 0,
		selected: null,
		rules: [(v) => !!v || "Input is required."],
		productError:{
		  	required:(v) => !!v || "Product is required."
		},
		cartonRules: [
	  		(v) => !!v || "Carton is required",
	  		(v) => parseFloat(v) > 0 || "Carton should be greater than 0",
		],
		unitRules: {
	 		required: (v) => !!v || "Unit is required",
	 		greater: (v) => parseFloat(v) > 0 || "Unit should be greater than 0",
		},
		ref_No_Rule: [(v) => !!v || "Refrence No is required"],
		Name_Rule: [(v) => !!v || "Name is required"],
		menu: false,
		menu2: false,
		tabs: [
	  		{
	  		  	name: "Products",
	  		  	id: "products-id",
	  		},
	  		{
	  		  	name: "Storable Units",
	  		  	id: "storable-units-id",
	  		},
	  		{
	  		  	name: "Document",
	  		  	id: "documents-id",
	  		},
		],
		currentTab: 0,
		outboundProducts: [
	  		{
				product: {
		  			product_id: "",
				},
				product_id: "",
				quantity: "",
				shipping_unit: "",
				total_unit: "",
				outbound_product_id:null,
				pr_id:'',
				remaining_carton_count:0,
				remaining_total_unit:0,
				instructions:''
	  		},
		],
		isCustom: "generated",
		productListsDropdownData: [],
		date: "",
		time: "",
		isMobile: false,
		expanded1: [],
		expanded2: [],
		selectedInside: [],
		dialogStorable: false,
		hasProductDuplicates: false,
		shippingUnit: [
	  		{
	  		  name: "Carton",
	  		  shipping_unit: "carton",
	  		},
	  		{
				name: "Single Item",
				shipping_unit: "single_item",
	  		},
	  			// {
	  			//     name: "Bundle Item",
	  			//     shipping_unit: "bundle"
	  			// },
		],
		shippingUnitCopy: [
	  		{
				name: "Single Item",
				shipping_unit: "single_item",
	  		},
		],
		files: [],
		showWarning_Warehouse_Product_Dialog:false,
		onchangeValue_of_warehouse_customer:null,
		oldValueSAve:'',
		customSkuRules: [
            (v) => !!v || "Input is required.",
            v => (v || '' ).length <= 50 || 'Only 50 maximum characters allowed.'
        ],
		// connected warehouse 3pl provider
		isCreateAndSubmitting: false,
        isSavingDraft: false,
        isUpdateAndSubmitting: false,
        isUpdatingDraft: false
  	}),
  	watch: {
		// outboundProducts: function () {
	  	// 	if (this.editedOutboundIndex > -1 || this.outboundItems.isDuplicate) {
		// 		if (this.outboundProducts.length > 0) {
		//   			this.outboundProducts.filter((val, index) => {
		// 				if (
		// 	  				this.outboundProducts[index].shipping_unit == "single_item" &&
		// 	  				this.outboundProducts[index].total_unit !== null &&
		// 	  				this.outboundProducts[index].total_unit !== "" &&
		// 	  				this.outboundProducts[index].total_unit !== "undefined"
		// 				) {
		// 	  				this.outboundProducts[index].quantity = val.total_unit;
		// 				}
		//   			});
		// 		}
	  	// 	}
		// },
  	},
  	computed: {
		...mapGetters({
	  		getInventory: "inventory/getInventory",
	  		getOutboundCreateLoading: "outbound/getOutboundCreateLoading",
	  		getUser: "getUser",
	  		getOutboundUpdateLoading: "outbound/getOutboundUpdateLoading",
	  		getoutboundUpdate3plLoading:"outbound/getoutboundUpdate3plLoading",
	  		getProducts: "products/getProducts",
	  		getStorableUnits: "storableUnit/getStorableUnitsActive",
	  		getStorableUnitsLoading: "storableUnit/getStorableUnitsActiveLoading",
	  		getProductInventory:"productInventories/getProductInventory",
	  		getProductInventoryLoading:"productInventories/getProductInventoryLoading",
	  		// 3PL
	  		getProductInventory3pl : 'productInventories/getProductInventory3pl',
	  		getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
			getCurrentOutboundTab: "outbound/getCurrentOutboundTab"

		}),
		productsHeader(){

			let defaultHeaders = this.headers
			defaultHeaders = defaultHeaders.filter(v => {
                    if (this.isWarehouseConnected) {
                        if (v.text == 'SKU') {
                            v.width = '30%'
                        }
                        
                        if (v.text === 'SHIPPING UNIT') {
                            v.width = '15%'
                        }
						if(v.text === 'QUANTITY') {
							v.width = '15%'
						}
						if(v.text === 'INSTRUCTIONS') {
							v.width = '35%'
						}
						if(v.text === ''){
							v.width = '5%'
						}

                        return v
                    } else {
                        return v.text !== 'INSTRUCTIONS'
                    }
                })
			return defaultHeaders	
		},
		formTitle() {
		  	return this.editedIndex === -1 ? "Create Outbound" : "Edit Outbound";
		},
	 	isWarehouse3PL() {
			if (this.currentWarehouseSelected !== null) {
				if (typeof this.currentWarehouseSelected.warehouse_type_id !== 'undefined' && 
					this.currentWarehouseSelected.warehouse_type_id === 3) {
					return true
				} else {
					return false
					}
			} else {
				return false
			}
		},
	  	isWarehouse6PL() {
			if (this.currentWarehouseSelected !== null) {
				if (typeof this.currentWarehouseSelected.warehouse_type_id !== 'undefined' && 
					this.currentWarehouseSelected.warehouse_type_id === 6) {
					return true
				} else {
					return false
					}
			} else {
				return false
			}
		},
		currentDateFind(){
	  		return new Date().toISOString().substr(0, 10)
		},
		dialog: {
	  		get() {
				return this.dialogCreate;
	  		},
	  		set(value) {
				this.$emit("update:dialogCreate", value);
	  		},
		},
		editedIndex: {
	 		get() {
				return this.editedOutboundIndex;
	  		},
	  		set(value) {
				this.$emit("update:editedOutboundIndex", value);
	  		},
		},
		outboundItems: {
	  		get() {
				return this.editedOutboundItems;
	  		},
	  		set(value) {
				this.$emit("update:editedOutboundItems", value);
	  		},
		},
		productsDropdownOutbound: {
			get() {
				return this.productsListsData
			},
			set(value) {
				this.$emit('update:productsListsData', value)
			}
		},
		productsDropdown: {
	  		get() {
				let value = this.productListsDropdownData;
				let finalValue = [];

				if (Array.isArray(value) && value.length > 0) {
		  			if (
						typeof this.getInventory !== "undefined" &&
						this.getInventory.results !== "undefined" &&
						Array.isArray(this.getInventory.results) &&
						this.getInventory.results.length > 0
		  			) {
						value.map((v) => {
			  				let findProduct = _.findIndex(
								this.getInventory.results,
								(e) => v.product_id === e.product_id
			  				);

			  				if (findProduct !== -1) {
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
	  		},
	  		set(value) {
				this.productListsDropdownData = value;
	  		},
		},
		addOutboundTemplate() {
	  		let {
				order_id,
				name,
				ref_no,
				customer,
				deliver_to,
				bol_number,
				estimated_date,
				estimated_time,
				notes,
				warehouse_customer_id
	  		} = this.outboundItems;

	  		estimated_date =
			estimated_date !== "" && estimated_date !== null
		  	? moment(estimated_date).format("MM/DD/YYYY")
		  	: "";
		  	estimated_time = estimated_time!=="" && estimated_time !==null && estimated_time !=="null" && estimated_time !=='undefined'? estimated_time :'';
		  	notes= notes!=="" && notes !==null && notes !=="null" && notes !=='undefined'? notes :'';
		  	customer = customer!=="" && customer !==null && customer !=="null" && customer !=='undefined'? customer :'';

	  		let outboundDataPassed = {
				customer_id:
		  		typeof this.getUser == "string"
				? JSON.parse(this.getUser).customer.id
				: "",
				customer_admin_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).id : '',
				customer_admin_name: (typeof this.getUser=='string') ? JSON.parse(this.getUser).name : '',    
				name,
				ref_no,
				customer,
				deliver_to,
				estimated_date,
				estimated_time,
				products: [],
				bol_number,
				notes,
				storable_units: [],
				documents: this.files.length > 0 ? this.files : [],
				warehouse_id: this.currentWarehouseSelected.id,
	  		};

	  		// check if outbound is custom or system generated
	  		if (this.isCustom == "custom") {
				outboundDataPassed.order_id = order_id;
	  		} else {
				outboundDataPassed.sys_gen = true;
	  		}
	  		if(this.isWarehouse6PL == true && !this.isWarehouseConnected){
	   			outboundDataPassed.warehouse_customer_id = warehouse_customer_id == null ? '' : warehouse_customer_id
				outboundDataPassed.outbound_status = 'shipping-pending'
	  		}
			if(this.isWarehouseConnected == true){
				outboundDataPassed.is_from_connected_3pl = 1
				outboundDataPassed.warehouse_customer_id = this.currentWarehouseSelected.warehouse_customer_id
			}

	  		// map products
	  		if (
				typeof this.outboundProducts !== "undefined" &&
				this.outboundProducts !== null
	  		) {
				if (
		  			this.outboundProducts.length > 0 &&
		  			this.outboundProducts[0].product_id !== ""
				) {
		  			this.outboundProducts.map((item) => {
						let { product_id, quantity, shipping_unit,instructions } = item;
						if(!this.isWarehouseConnected){
							outboundDataPassed.products.push({
			  					product_id,
			  					quantity,
			  					shipping_unit
							});
						}else{
							outboundDataPassed.products.push({
			  					product_id,
			  					quantity,
			  					shipping_unit,
								instructions

							});
						}
						
		 	 		});
				} else {
		  			outboundDataPassed.products = [];
				}
	  		}

	  		// get storable unit id's
	  		if (
				typeof this.itemStorable !== "undefined" &&
				this.itemStorable !== null
	  		) {
				if (this.itemStorable.length > 0) {
		  			this.itemStorable.map((item) => {
						let { id } = item;
						outboundDataPassed.storable_units.push(id);
		  			});
				} else {
		 	 		outboundDataPassed.storable_units = [];
				}
	  		}

	  		return outboundDataPassed;
		},
		editOutboundTemplate() {
	  		let {
				id,
				order_id,
				name,
				ref_no,
				bol_number,
				customer,
				deliver_to,
				estimated_date,
				estimated_time,
				notes,
				warehouse_customer_id
	  		} = this.outboundItems;

	  		estimated_date =
			estimated_date !== "" && estimated_date !== null
		  	? moment(estimated_date).format("MM/DD/YYYY")
		  	: "";
		  	estimated_time =  estimated_time!=="" && estimated_time !==null && estimated_time !=="null" && estimated_time !=='undefined'? estimated_time :'';
		  	notes= notes!=="" && notes !==null && notes !=="null" && notes !=='undefined'? notes :'';
		  	customer = customer!=="" && customer !==null && customer !=="null" && customer !=='undefined'? customer :'';
			customer = customer!=="" && customer !==null && customer !=="null" && customer !=='undefined'? customer :'';
	  		let outboundDataPassed = {
				order_id,
				customer_id:
		  		typeof this.getUser == "string"
				? JSON.parse(this.getUser).customer.id
				: "",  
				name,
				ref_no,
				customer,
				deliver_to,
				estimated_date,
				estimated_time,
				products: [],
				storable_units: [],
				documents: this.files.length > 0 ? this.files : [],
				warehouse_id: this.currentWarehouseSelected.id,
				outbound_id: id,
				id,
				notes,
				bol_number,
				_method: "PUT",
	  		};
	  		if(this.isWarehouse6PL ==true && !this.isWarehouseConnected){
				outboundDataPassed.warehouse_customer_id = warehouse_customer_id == null ? '' : warehouse_customer_id
	  		}
			if(this.isWarehouseConnected){
				outboundDataPassed.warehouse_customer_id = warehouse_customer_id
				outboundDataPassed.is_from_connected_3pl = 1
			}
	  		// map products
	  		if (
				typeof this.outboundProducts !== "undefined" &&
				this.outboundProducts !== null
	  		) {
				if (
		  			this.outboundProducts.length > 0 &&
		  			this.outboundProducts[0].product_id !== ""
				) {
		  			this.outboundProducts.map((item) => {
						let { product_id, quantity, shipping_unit,outbound_product_id,instructions } = item;
						if(!this.isWarehouseConnected){
							outboundDataPassed.products.push({
						  		product_id,
						  		quantity,
						  		shipping_unit,
						  		outbound_product_id
							});
						}else{
							outboundDataPassed.products.push({
						  		product_id,
						  		quantity,
						  		shipping_unit,
						  		outbound_product_id,
								instructions
							});
						}
						
		  			});
				} else {
		  			outboundDataPassed.products = [];
				}
	  		}

	  		// get storable unit id's
	  		if (
				typeof this.outboundItems.outbound_storable_units !== "undefined" &&
				this.outboundItems.outbound_storable_units !== null
	  		) {
				if (this.outboundItems.outbound_storable_units.length > 0) {
		  			this.outboundItems.outbound_storable_units.map((item) => {
						if (item.storable_unit_id) {
			  			//  let { id } = item
			  				outboundDataPassed.storable_units.push(item.storable_unit_id);
						} else {
			  			// if(item.storable_unit_id !== null){
			  				outboundDataPassed.storable_units.push(item.id);
			  			// }
						}
		  			});
				} else {
		  			outboundDataPassed.storable_units = [];
				}
	  		}

	  		return outboundDataPassed;
		},
		storableItemsDisplay() {
	  		let units = [];

	  		if (
				typeof this.getStorableUnits !== "undefined" &&
				this.getStorableUnits !== null &&
				this.getStorableUnits.items !== "undefined" &&
				Array.isArray(this.getStorableUnits.items)
	  		) {
				let items = this.getStorableUnits.items;

				items.map((v) => {
		  			let { dimensions, weight, storable_unit_products, ...otherItems } = v;
		  			let parse_dimensions =
					dimensions !== "" && dimensions !== null
			  		? JSON.parse(dimensions)
			  		: null;
		  			let parse_weight =
		   	 		weight !== "" && weight !== null ? JSON.parse(weight) : null;
		  			let no_of_sku =
					storable_unit_products.length !== "undefined"
			  		? storable_unit_products.length
			  		: 0;

		  			units.push({
						parse_dimensions,
						parse_weight,
						no_of_sku,
						storable_unit_products,
						...otherItems,
		  			});
				});
	  		}

	  		return units;
		},
		warehouseCustomerLists() {
			let data = []

			if (typeof this.getWarehouseCustomersDropdown !== "undefined" && this.getWarehouseCustomersDropdown !== null) {
				if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
					this.getWarehouseCustomersDropdown.data.length !== "undefined") {
					data = this.getWarehouseCustomersDropdown.data;

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
			}

			return data
		}
  	},
  	methods: {
		...mapActions({
	  		createOutbound: "outbound/createOutbound",
	  		updateOutbound: "outbound/updateOutbound",
	  		fetchPendingOutbounds: "outbound/fetchPendingOutbounds",
	  		fetchCompletedOutbounds: "outbound/fetchCompletedOutbounds",
	  		fetchStorableUnitsActive: "storableUnit/fetchStorableUnitsActive",
	  		fetchSingleOutbound: "outbound/fetchSingleOutbound",
	  		fetchProductInventories:'productInventories/fetchProductInventories',
	  		// 3PL
	  		fetchProductInventories3pl : 'productInventories/fetchProductInventories3pl',
	  		updateOutbound3pl:"outbound/updateOutbound3pl",
	  		// Empty Products
	  		setAllOutboundProductsLists: 'outbound/setAllOutboundProductsLists',
			// 3pl Provider
			fetchPendingShippingServiceProvider:'outbound/fetchPendingShippingServiceProvider',
			// connected 3pl Provider
			fetchDraftOutbounds: "outbound/fetchDraftOutbounds",

		}),
		...globalMethods,
		onTabChange(i) {
	  		this.currentTab = i;
		},
		onResize() {
	  		if (window.innerWidth < 1024) {
				this.isMobile = true;
	  		} else {
				this.isMobile = false;
	  		}
		},
		close() {
	  		this.$emit("close");
	  		(this.outboundProducts = [
				{
		  			product: {
						product_id: "",
		  			},
		  			product_id: "",
		  			quantity: "",
		  			shipping_unit: "",
		  			outbound_product_id:null,
		 	 		pr_id:'',
					remaining_carton_count:0,
					remaining_total_unit:0,
					instructions:''
				},
	  		]),
			(this.date = "");
	  		this.time = "";
	  		this.outboundItems = this.defaultOutboundItems;
	  		this.itemStorable = [];
	  		this.out;
	  		this.files = [];
	  		this.$refs.documents_reference.value = "";
	  		this.$refs.form.resetValidation();
		},
		convertTimeTo12(time) {
	  		let finalTime = null;

	  		if (time !== "" && time !== null) {
				finalTime = moment(time, ["HH:mm"]).format("hh:mm A");
	  		}

	  		return finalTime;
		},
		async buildAndPrint(params) {
	  		// console.log("add",this.addOutboundTemplate);
	  		// console.log("files",this.files[0]);
	  		// console.log( 'edit',this.editOutboundTemplate);
	  		this.$refs.form.validate();

	  		if (this.$refs.form.validate()) {
				try {
		  			if (this.hasProductDuplicates) {
						this.notificationError("Duplicate products has been detected.");
		  			} else {
						if (typeof this.addOutboundTemplate !== "undefined") {
			  				if (this.editedOutboundIndex === -1) {
								let addOutboundTemplate = this.addOutboundTemplate;
								if(this.isWarehouseConnected == true && params == 'pending'){
									Object.assign(addOutboundTemplate, {outbound_status: "pending"});
									this.isCreateAndSubmitting = true
								}else{
									if(this.isWarehouseConnected == true && params == 'draft'){
									Object.assign(addOutboundTemplate, {outbound_status: "draft"});
									this.isSavingDraft = true
									}
								}
								if (typeof addOutboundTemplate.products == "string") {
				  					addOutboundTemplate.products = JSON.parse(
										addOutboundTemplate.products
				  					);
								}
								addOutboundTemplate.products = JSON.stringify(
				  				addOutboundTemplate.products
								);
								// console.log("outbound array is", addOutboundTemplate);
								await this.createOutbound(addOutboundTemplate);
								if(!this.isWarehouseConnected){
									this.notificationMessage("Outbound has been created.");
								}else{
									if(params == 'pending'){
										this.notificationMessage("Outbound has been created and submitted.");
									}else if(params == 'draft'){
										this.notificationMessage("Outbound has been saved as draft.");
									}
								}
								this.isCreateAndSubmitting = false
                                this.isSavingDraft = false

								try {
									let storeOutboundTab = this.$store.state.outbound
									let dataWithPage = {
				  						id: this.currentWarehouseSelected.id,
				  						page: 1
									}
									dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
									if(this.isWarehouseConnected == true){
										if(this.getCurrentOutboundTab == 0){
											await this.fetchDraftOutbounds(dataWithPage)
										}else if(this.getCurrentOutboundTab == 1){
				 	 						await this.fetchPendingOutbounds(dataWithPage);
										}
									}else{
										if(this.getCurrentOutboundTab == 0 && !this.isWarehouse6PL){
				 	 						await this.fetchPendingOutbounds(dataWithPage);
										}else if(this.isWarehouse6PL && !this.isWarehouseConnected){
											await this.fetchPendingShippingServiceProvider(dataWithPage);
										}
									}
				  					if(this.isWarehouse6PL ==true && !this.isWarehouseConnected &&  this.outboundItems.warehouse_customer_id !=='' ){
										this.setAllOutboundProductsLists([])
										let val = {
					  						id:this.outboundItems.warehouse_customer_id
					  					}
										this.$emit('Warehouse6PL_ProductsOnchange',val)
				  					}else{
										this.setAllOutboundProductsLists([])
										this.$emit('fetchOutboundProductsAPiFunction','Outbound','nothing')
				  					}
				  
				  					this.close();
								} catch (e) {
				  					this.notificationError(e);
								}
			  				} else {
								let editOutboundTemplate = this.editOutboundTemplate;

								if(this.isWarehouseConnected == true && params == 'pending'){
									Object.assign(editOutboundTemplate, {outbound_status: "pending"});
									this.isUpdateAndSubmitting = true
								}else{
									if(this.isWarehouseConnected == true && params == 'draft'){
									Object.assign(editOutboundTemplate, {outbound_status: "draft"});
									this.isUpdatingDraft = true	
									}
								}
								if (typeof editOutboundTemplate.products == "string") {
				  					editOutboundTemplate.products = JSON.parse(
									editOutboundTemplate.products
				  					);
								}
								editOutboundTemplate.products = JSON.stringify(
				  				editOutboundTemplate.products
								);
								await this.updateOutbound(editOutboundTemplate);
								if(!this.isWarehouseConnected){
									this.notificationMessage("Outbound has been updated.");
								}else{
									if(params == 'pending'){
										this.notificationMessage("Outbound has been updated and submitted.");
									}else if(params == 'draft'){
										this.notificationMessage("Outbound has been saved.");
									}
									
								}
								this.isUpdateAndSubmitting = false
                                this.isUpdatingDraft = false
								
								try {
				  					if(this.isFetchSingleOutboundbound){
					   					let oid = new URL(location.href).searchParams.get("id");
					   					let wid = new URL(location.href).searchParams.get("wid");
					  					let payload = { wid: wid, oid: oid };
				   						await this.fetchSingleOutbound(payload)
				  					}else{
										let storeOutboundTab = this.$store.state.outbound
										let dataWithPage = {
					 						id: this.currentWarehouseSelected.id,
					 						page: 1
										}
										dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
										if(this.isWarehouseConnected == true){
											if(this.getCurrentOutboundTab == 0){
											await this.fetchDraftOutbounds(dataWithPage)
											}else if(this.getCurrentOutboundTab == 1){
				 	 						await this.fetchPendingOutbounds(dataWithPage);
											}
										}else{
											if(this.getCurrentOutboundTab == 0 && !this.isWarehouse6PL){
				 	 							await this.fetchPendingOutbounds(dataWithPage);
											}else if(this.isWarehouse6PL && !this.isWarehouseConnected){
												await this.fetchPendingShippingServiceProvider(dataWithPage);
											}
										}
				  					}
				  					if(this.isWarehouse6PL ==true && !this.isWarehouseConnected && this.outboundItems.warehouse_customer_id !=='' ){
										this.setAllOutboundProductsLists([])
										let val = {
					  						id:this.outboundItems.warehouse_customer_id
					  					}
										this.$emit('Warehouse6PL_ProductsOnchange',val)
				  					}else{
										this.setAllOutboundProductsLists([])
										this.$emit('fetchOutboundProductsAPiFunction','Outbound','nothing')
				  					}
				  					this.close();
								} catch (e) {
				  					this.notificationError(e);
									this.isCreateAndSubmitting = false
                                    this.isSavingDraft = false
                                    this.isUpdateAndSubmitting = false
                                    this.isUpdatingDraft = false

								}
			  				}
						} else {
			  				this.notificationError(
								"Something's wrong in creating an outbound. Please reload your page and try again."
			  				);
							this.isCreateAndSubmitting = false
                            this.isSavingDraft = false
                            this.isUpdateAndSubmitting = false
                            this.isUpdatingDraft = false
						}
		  			}
				} catch (e) {
		  			this.notificationError(e);
					this.isCreateAndSubmitting = false
                    this.isSavingDraft = false
                    this.isUpdateAndSubmitting = false
                    this.isUpdatingDraft = false
				}
	  		}
		},
		async buildAndPrintfor3plEdit(){
	  		if(this.isWarehouse3PL ==true && this.$refs.form.validate()){
				try {
			  		let editOutboundTemplate = this.editOutboundTemplate;

			  		if (typeof editOutboundTemplate.products == "string") {
						editOutboundTemplate.products = JSON.parse(
				  		editOutboundTemplate.products
						);
			  		}
			  			editOutboundTemplate.products = JSON.stringify(
						editOutboundTemplate.products
			  			);
			  			await this.updateOutbound3pl(editOutboundTemplate);
			  			this.notificationMessage("Outbound has been updated.");

			  		try {
						let storeOutboundTab = this.$store.state.outbound
				  			let dataWithPage = {
							id: this.currentWarehouseSelected.id,
							page: 1
							}
						if(this.isFetchSingleOutboundbound){
							let oid = new URL(location.href).searchParams.get("id");
							let wid = new URL(location.href).searchParams.get("wid");
							let payload = { wid: wid, oid: oid };
				  			await this.fetchSingleOutbound(payload)
							if(this.getCurrentOutboundTab !== 'undefined' || this.getCurrentOutboundTab !== undefined){
								if(this.getCurrentOutboundTab == 2){
									dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
									await this.fetchCompletedOutbounds(dataWithPage)
			  					}
								else{
									if(this.getCurrentOutboundTab == 0){
										dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
										await this.fetchPendingOutbounds(dataWithPage);
									}
								}
							}	
						}
						else{				  			
			  				if(this.status =='completed'){
								dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
								await this.fetchCompletedOutbounds(dataWithPage)
			  				}
							else{
								dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
								await this.fetchPendingOutbounds(dataWithPage);
							}
						}
						if(this.isWarehouse6PL ==true && this.outboundItems.warehouse_customer_id !=='' ){
							this.setAllOutboundProductsLists([])
						 	let val = {
						  		id:this.outboundItems.warehouse_customer_id
							}
							this.$emit('Warehouse6PL_ProductsOnchange',val)
					  	}else{
							this.setAllOutboundProductsLists([])
							this.$emit('fetchOutboundProductsAPiFunction','Outbound','nothing')
					  	}
						this.close();
					} catch (e) {
						this.notificationError(e);
					}
				}catch (e) {
			  		this.notificationError(e);
				}
	 		}
		},
		removeRow(item) {
	  		let getIndex = this.outboundProducts.indexOf(item);
	  		this.outboundProducts.splice(getIndex, 1);
		},
		removeRowStorable(item) {
	  		let getIndex = this.itemStorable.indexOf(item);
	  		this.itemStorable.splice(getIndex, 1);
		},
		addRow() {
	  		let getItem = this.outboundProducts;
	  
	  		getItem.push({
			product: {
			  	product_id: "",
			},
			product_id: "",
			quantity: "",
			shipping_unit: "",
			total_unit: "",
			outbound_product_id:null,
			pr_id:'',
			remaining_carton_count:0,
			remaining_total_unit:0,
			instructions:''
	  		});

	  		this.outboundItems.products = getItem;
		},
		//
		addStorableRow() {
	  		let getItem = this.outboundStorable;

	  		getItem.push({
				product: {
		  			product_id: "",
				},
				carton_count: "",
				total_unit: "",
	  		});

	  		this.outboundItems.outbound_products = getItem;
		},
		addStorableItemInside() {
	  		if (this.selectedInside.length !== 0 && this.editedOutboundIndex === -1) {
				this.itemStorable = this.selectedInside;
				this.closeStorable();
				// console.log(this.itemStorable);
				// console.log(this.selectedInside);
	  		}
	  		if (this.editedOutboundIndex > -1) {
				this.outboundItems.outbound_storable_units = [
		  			...this.outboundItems.outbound_storable_units,
		  			...this.selectedInside,
				];
				this.closeStorable();
				// console.log("this.outboundItems.outbound_storable_units",this.outboundItems.outbound_storable_units);
	  		}
		},
		clicked(value) {
	  		// code for opening multiple
	  		const index = this.expanded.indexOf(value);
	  		value.isExpanded = false;

	  		if (index === -1) {
				this.expanded.push(value);
	  		} else {
				this.expanded.splice(index, 1);
	  		}
		},
		itemRowBackground(item) {
	  		return item.isExpanded ? "background-light-blue" : "";
		},
		async createStorable() {
	  		this.dialogStorable = true;
	  		let payload = {
				id: this.currentWarehouseSelected.id,
				page: this.current_page,
	  		};
	  		try {
				await this.fetchStorableUnitsActive(payload);
				this.combineArray = [
				  	...this.combineArray,
				  	...this.storableItemsDisplay,
				];
				if (
				  	this.getStorableUnits.current_page < this.getStorableUnits.last_page
				) {
				  	this.current_page++;
				  	this.createStorable();
				}
	  		} catch (e) {
				this.notificationError(e);
	  		}
		},
		closeStorable() {
	  		this.dialogStorable = false;
	  		this.combineArray = [];
	  		this.current_page = 1;
		},
		scrollToSection(id) {
	  		const el = document.getElementById(id);
	  		el.scrollIntoView({ behavior: "smooth" });
		},
		addDocuments() {
	  		this.$refs.documents_reference.click();
		},
		inputChanged() {
	  		let files = this.$refs.documents_reference.files;

	  		if (!files.length) {
				return false;
	  		}

	  		for (let i = 0; i < files.length; i++) {
				this.files.push(files[i]);
	  		}
		},
		remove(index) {
			this.$refs.documents_reference.value = "";
	  		this.files.splice(index, 1);
		},
		isProductSelected(item, index) {
	  		if (item.product_id !== "" && item.quantity !=='' && item.shipping_unit !=='') {
				let findSelectedOption = _.findIndex(
		  			this.outboundProducts,
		  			(e) =>
					(e.product_id == item.product_id &&
					e.shipping_unit == item.shipping_unit &&
					e.quantity == item.quantity)
					);
				if (findSelectedOption !== index) {
		 		 	this.hasProductDuplicates = false;
		  			return "Duplicate entry found. You have already added this SKU with same specification.";
				} else {
		  			this.hasProductDuplicates = false;
				}
	  		}
		},
		isProductFieldError(item, index) {
	  		if (item.product_id !== "") {
				let findSelectedOption = _.findIndex(
		  			this.outboundProducts,
		  			(e) =>
					e.product_id == item.product_id &&
					e.shipping_unit == item.shipping_unit
					);

				if (findSelectedOption !== index) {
		  			return "has-duplicate";
				} else {
		  			return "";
				}
	  		}
		},
		GetNameAndSku(nameAndSku){
	   		if(typeof nameAndSku !=='undefined' && nameAndSku !== null && nameAndSku !== 'null'){
		 		if(typeof nameAndSku[0].storable_unit_product !=='undefined' && nameAndSku[0].storable_unit_product !== null && nameAndSku[0].storable_unit_product !== 'null'){
		   			if(typeof nameAndSku[0].storable_unit_product.product !=='undefined'  && nameAndSku[0].storable_unit_product.product !== null && nameAndSku[0].storable_unit_product.product !== 'null'){
			 			return '#'+ nameAndSku[0].storable_unit_product.product.sku + ' ' +  nameAndSku[0].storable_unit_product.product.name 
		   			}
		 			}else{
		   				return '--'
		 			}
	  			}
	  		else{
				return '--'
	  		}
		},
		getTotalCartonsAndUnits(data, forItem) {
			let total = 0

			if (data !== 'undefined' && data !== null && data.outbound_storable_unit_products !== 'undefined' 
				&& Array.isArray(data.outbound_storable_unit_products) && data.outbound_storable_unit_products.length > 0) {
				let arr = data.outbound_storable_unit_products

				if (forItem === 'carton') {
					arr.forEach(function(value) {
						total += value.carton_count                       
					})
				} else if (forItem === 'unit') {
					arr.forEach(function(value) {
						total += value.total_unit
					})
				}
			}
			total = total === 0 ? '--' : total
			return total
		},
		getTotalCartonsAndUnit(data, forItem) {
			let total = 0

			if (data !== 'undefined' && data !== null && data.storable_unit_products !== 'undefined' 
		   	 	&& Array.isArray(data.storable_unit_products) && data.storable_unit_products.length > 0) {
				let arr = data.storable_unit_products

				if (forItem === 'carton') {
					arr.forEach(function(value) {
						total += value.carton_count                       
					})
				} else if (forItem === 'unit') {
					arr.forEach(function(value) {
						total += value.total_unit
					})
				}
			}
			total = total === 0 ? '--' : total
			return total
		},
		getImgUrl(pic) {
			let imageUrl = 'https://po.shifl.com/storage/'

			if (pic !== 'undefined' && pic !== null && pic !== undefined) {
				if (pic.includes(imageUrl) !== 'undefined' && !pic.includes(imageUrl)) {
					let newImage = imageUrl + pic
					return newImage
				} else {
					return pic
				}
			} else {
				return require('../../../assets/icons/default-product-icon.svg')
			}
		},
		restrictValues(e) {
			if(e.key=='-' && e.keyCode==189 || e.key=='+' && e.keyCode==187 ) {
				e.preventDefault()
			}
		},
		quantityCountCheckError(item,items_id){
			if (item !== '' && item !== null && this.isWarehouse3PL ==false && this.isWarehouse6PL ==false) {
		  		if(this.editedOutboundIndex > -1){
					let ProductMatch = this.outboundProducts.filter(val => val.product_id == items_id.product_id  && val.shipping_unit == items_id.shipping_unit)
			 		let finalValue =  ProductMatch.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.quantity) 
					}, 0);
					let ProductMatch_remaining_carton = this.outboundProducts_clone.filter(val => val.product_id == items_id.product_id  && items_id.shipping_unit =='carton')
			 		// let finalValue_remaining_carton =  ProductMatch_remaining_carton.reduce(function(prev, index){
					// return parseInt(prev) + parseInt(index.remaining_carton_count) 
					// }, 0);
					let finalValue_remaining_carton_dividedbyInEachCarton =  ProductMatch_remaining_carton.reduce(function(prev, index){
					return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0);
		  			let ProductMatch_remaining_Units = this.outboundProducts_clone.filter(val => val.product_id == items_id.product_id  &&  items_id.shipping_unit == "single_item")
			 		let finalValue_remaining_Units =  ProductMatch_remaining_Units.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0);
		  			let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === items_id.product_id)
					if (findItem !== "undefined" && findItem !== undefined) {
			  			if(findItem.is_from_inventory ==true){
							// variables for Calculation
							let availableCartonUnits = findItem.available == null ? 0 : findItem.available
							let In_Each_Carton = findItem.in_each_carton !== null ? findItem.in_each_carton : 1
							let expected_carton_count = availableCartonUnits
			  				let final_total_Unit = availableCartonUnits

			  				// total unit value get
							let oldvalue = finalValue_remaining_Units
							let newvalue = finalValue
							let result = ((oldvalue) - (newvalue))
							let final_result = (result) + (availableCartonUnits)
			  				if(final_result < 0){
								final_total_Unit = final_result
			  				}else{
								final_total_Unit = availableCartonUnits + finalValue_remaining_Units
			  				}

							// Available Carton Check
							let oldvalueC = finalValue_remaining_carton_dividedbyInEachCarton
							let newvalueC = finalValue
							let resultC = ((oldvalueC) - (newvalueC))
							let final_resultC = (resultC) + (availableCartonUnits)
							if(final_resultC < 0){
								expected_carton_count = final_resultC
			  				}else{
								expected_carton_count = (parseInt(availableCartonUnits) + parseInt(finalValue_remaining_carton_dividedbyInEachCarton) ) / parseInt(In_Each_Carton)
			  				}
							if (items_id.shipping_unit =='carton' && parseInt(finalValue) > parseInt(expected_carton_count) ) return false
		  					else if(items_id.shipping_unit =='single_item' && parseInt(finalValue) > parseInt(final_total_Unit)) return false
		  					return true		
			  			}else{
							return false
			  			}
	
		  			} else{
			  			return true
					}
		  		}
				else{
					let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === items_id.product_id)
					if (findItem !== "undefined" && findItem !== undefined) {
			  			if(findItem.is_from_inventory ==true){
			  			let final_total_value = findItem.total_unit < 0 ? 0 : findItem.total_unit
						let In_Each_Carton = findItem.in_each_carton !== null ? findItem.in_each_carton : 1
						let expected_carton_count = parseInt(item)  * parseInt(In_Each_Carton)
		  				if (items_id.shipping_unit =='carton' && parseInt(expected_carton_count) > parseInt(final_total_value)) return false
		  				else if(items_id.shipping_unit =='single_item' && parseInt(item) > parseInt(final_total_value)) return false
		  				return true
			  			}
						else{
							return false
			  			}
		  			} else{
			  			return true
					}
		  		}
		  	}else{
				return true
			}
		},
		quantityCountCheckErrorShow(item,items_id){
		  	if (item !== '' && item !== null && this.isWarehouse3PL ==false && this.isWarehouse6PL ==false) {
				if(this.editedOutboundIndex > -1){
					let ProductMatch = this.outboundProducts.filter(val => val.product_id == items_id.product_id  && val.shipping_unit == items_id.shipping_unit)
			 		let finalValue =  ProductMatch.reduce(function(prev, index){
					return parseInt(prev) + parseInt(index.quantity) 
					}, 0);
					let ProductMatch_remaining_carton = this.outboundProducts_clone.filter(val => val.product_id == items_id.product_id && items_id.shipping_unit =='carton')
			 		// let finalValue_remaining_carton =  ProductMatch_remaining_carton.reduce(function(prev, index){
					// return parseInt(prev) + parseInt(index.remaining_carton_count) 
					// }, 0);
					let finalValue_remaining_carton_dividedbyInEachCarton =  ProductMatch_remaining_carton.reduce(function(prev, index){
					return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0);
		  			let ProductMatch_remaining_Units = this.outboundProducts_clone.filter(val => val.product_id == items_id.product_id && items_id.shipping_unit == "single_item")
			 		let finalValue_remaining_Units =  ProductMatch_remaining_Units.reduce(function(prev, index){
					return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0);
					let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === items_id.product_id)
					if (findItem !== "undefined" && findItem !== undefined) {
			  			if(findItem.is_from_inventory ==true){
							// variables for Calculation
							let availableCartonUnits = findItem.available == null ? 0 : findItem.available
							let In_Each_Carton = findItem.in_each_carton !== null ? findItem.in_each_carton : 1
			  				let expected_carton_count = availableCartonUnits
			  				let final_total_Unit =  availableCartonUnits;

			  				// total unit value get
							let oldvalue = finalValue_remaining_Units
							let newvalue = finalValue
							let result = ((oldvalue) - (newvalue))
							let final_result = (result) + (availableCartonUnits)
			  				if(final_result < 0){
								final_total_Unit = final_result
			  				}else{
								final_total_Unit = availableCartonUnits + finalValue_remaining_Units
			  				}

							// Available Carton Check
							let oldvalueC = finalValue_remaining_carton_dividedbyInEachCarton
							let resultC = ((oldvalueC) - (newvalueC))
							let newvalueC = finalValue
							let final_resultC = (resultC) + (availableCartonUnits)

							if(final_resultC < 0){
								expected_carton_count = final_resultC
			  				}else{
								expected_carton_count = (parseInt(availableCartonUnits) + parseInt(finalValue_remaining_carton_dividedbyInEachCarton) ) / parseInt(In_Each_Carton)
			  				}
			  				if (items_id.shipping_unit == "carton" && parseInt(finalValue) > parseInt(expected_carton_count) ) return "We don't have enough product in the inventory";
			  				else if (items_id.shipping_unit == "single_item" && parseInt(finalValue) > parseInt(final_total_Unit)) {
								return "We don't have enough product in the inventory";
			  				}
			  				return ''	
			  			}else{
							return "This product is not from inventory"
			  			}
					}else{
			  			return ''
					}
				}
				else{
			 		let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === items_id.product_id)
					if (findItem !== "undefined" && findItem !== undefined) {
			  			if(findItem.is_from_inventory ==true){
			  				let final_total_value = findItem.total_unit <0 ? 0 : findItem.total_unit
			  				let final_total_Unit = final_total_value;
							let In_Each_Carton = findItem.in_each_carton !== null ? findItem.in_each_carton : 1
							let Expected_carton_count = parseInt(item)  * parseInt(In_Each_Carton)
			  				if (items_id.shipping_unit == "carton" && parseInt(Expected_carton_count) > parseInt(final_total_Unit) ) return "We don't have enough product in the inventory";
			  				else if (items_id.shipping_unit == "single_item" && parseInt(item) > parseInt(final_total_Unit)) {
							return "We don't have enough product in the inventory";
			  				}
			  				return ''
			  			}
						else{
							return "This product is not from inventory"
			  			}
					}
					else{
			  			return ''
					}
				}
		  	}
			else{
				return ''
		  	}
		},
		statusLoaded(item_status){
		  	if(this.isWarehouse3PL ==true){
				if(this.editedOutboundIndex > -1){
			 		let ProductMatch = this.outboundProducts.filter(val => val.product_id == item_status.product_id  && val.shipping_unit == item_status.shipping_unit)
			 		let finalValue =  ProductMatch.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.quantity) 
					}, 0);
		  			let ProductMatch_remaining_carton = this.outboundProducts_clone.filter(val => val.product_id == item_status.product_id  && item_status.shipping_unit =='carton')
			 		let finalValue_remaining_carton =  ProductMatch_remaining_carton.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.remaining_carton_count) 
					}, 0);
		  			let ProductMatch_remaining_Units = this.outboundProducts_clone.filter(val => val.product_id == item_status.product_id  && item_status.shipping_unit == "single_item")
			 		let finalValue_remaining_Units =  ProductMatch_remaining_Units.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0);
		   			let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === item_status.product_id )
		   			if(findItem !=='undefined' && findItem !== undefined && finalValue !=='undefined' && item_status.shipping_unit !==''){
						if(findItem.is_from_inventory ==true){
							let final_total_value = findItem.total_unit
							let final_total = final_total_value
							let Expected_carton_count = findItem.expected_carton_count
							// total unit value get
			  				if(findItem.total_unit < 0){
								final_total = final_total_value
			  				}else{
								final_total = final_total_value + finalValue_remaining_Units
			  				}
			  				// total carton value get
			  				if(findItem.expected_carton_count < 0){
								Expected_carton_count = findItem.expected_carton_count
			  				}
			  				else{
								Expected_carton_count =  findItem.expected_carton_count + finalValue_remaining_carton
			  				}
							if(item_status.shipping_unit =='single_item' && finalValue  > final_total  ) return false
							else if(item_status.shipping_unit =='carton' && finalValue > Expected_carton_count ) return false
							return true
						}
						else{
			  				return false
						}
		   			}
					return true
				}
				else{
			  		let ProductMatch = this.outboundProducts.filter(val => val.product_id == item_status.product_id  && val.shipping_unit == item_status.shipping_unit)
			 		let finalValue =  ProductMatch.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.quantity) 
					}, 0);
		   			let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === item_status.product_id )
		   			if(findItem !=='undefined' && findItem !== undefined && finalValue !=='undefined' && item_status.shipping_unit !==''){
						if(findItem.is_from_inventory ==true){
							let final_total_value = findItem.total_unit <0 ? 0 : findItem.total_unit
							let final_total = final_total_value
							let Expected_carton_count = findItem.expected_carton_count < 0 ? 0 : findItem.expected_carton_count
							if(item_status.shipping_unit =='single_item' && finalValue  > final_total  ) return false
							else if(item_status.shipping_unit =='carton' && finalValue > Expected_carton_count ) return false
							return true
						}
						else{
			  			return false
						}
		   			}
					return true
				}
		  	}
		  	return true
		},
		statusLoadedError(item_status){
		  	if(this.isWarehouse3PL ==true){
				if(this.editedOutboundIndex > -1){
			 		let ProductMatch = this.outboundProducts.filter(val => val.product_id == item_status.product_id && val.shipping_unit == item_status.shipping_unit)
			 		let finalValue =  ProductMatch.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.quantity) 
					}, 0)
		  			let ProductMatch_remaining_carton = this.outboundProducts_clone.filter(val => val.product_id == item_status.product_id  && item_status.shipping_unit =='carton')
			 		let finalValue_remaining_carton =  ProductMatch_remaining_carton.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.remaining_carton_count) 
					}, 0)
		  			let ProductMatch_remaining_Units = this.outboundProducts_clone.filter(val => val.product_id == item_status.product_id  && item_status.shipping_unit == "single_item")
			 		let finalValue_remaining_Units =  ProductMatch_remaining_Units.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0)
		   			let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === item_status.product_id )
		   			if(findItem !=='undefined' && findItem !== undefined && finalValue !=='undefined' && item_status.shipping_unit !==''){
			 			if(findItem.is_from_inventory ==true){
			 				let Expected_carton_count = findItem.expected_carton_count 
							let final_total_value = findItem.total_unit 
							let final_total = final_total_value
							// total unit value get
			  				if(findItem.total_unit < 0){
								final_total = final_total_value
			  				}else{
								final_total = final_total_value + finalValue_remaining_Units
			  				}
			  				// total carton value get
			  				if(findItem.expected_carton_count < 0){
								Expected_carton_count = findItem.expected_carton_count
			  				}
			  				else{
								Expected_carton_count =  findItem.expected_carton_count + finalValue_remaining_carton
			  				}
							if(item_status.shipping_unit =='single_item' && finalValue  > final_total  ) return "We don't have enough product in the inventory"
							else if(item_status.shipping_unit =='carton' && finalValue > Expected_carton_count) return "We don't have enough product in the inventory"
							return ''
			 			}
			 			else{
			  				return "This product is not from Inventory"
			 			}
		   			}
					return ''
				}
				else{
					let ProductMatch = this.outboundProducts.filter(val => val.product_id == item_status.product_id && val.shipping_unit == item_status.shipping_unit)
			 		let finalValue =  ProductMatch.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.quantity) 
					}, 0)
		   			let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === item_status.product_id )
		   			if(findItem !=='undefined' && findItem !== undefined && finalValue !=='undefined' && item_status.shipping_unit !==''){
			 			if(findItem.is_from_inventory ==true){
							let final_total_value = findItem.total_unit <0 ? 0 : findItem.total_unit
							let final_total = final_total_value  
							let expected_carton_count = findItem.expected_carton_count < 0 ? 0 : findItem.expected_carton_count
							if(item_status.shipping_unit =='single_item' && finalValue  > final_total  ) return "We don't have enough product in the inventory"
							else if(item_status.shipping_unit =='carton' && finalValue > expected_carton_count ) return "We don't have enough product in the inventory"
							return ''
			 			}
			 			else{
			  				return "This product is not from Inventory"
			 			}
		   			}
					return ''
				}
		  	}
		  	return ''
		},
		quantityCountCheckError6PL(item,items_id){
			if (item !== '' && item !== null && this.isWarehouse3PL ==false && this.isWarehouse6PL ==true) {
		  		if(this.editedOutboundIndex > -1){
					let ProductMatch = this.outboundProducts.filter(val => val.product_id == items_id.product_id  && val.shipping_unit == items_id.shipping_unit)
			 		let finalValue =  ProductMatch.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.quantity) 
					}, 0);
					let ProductMatch_remaining_carton = this.outboundProducts_clone.filter(val => val.product_id == items_id.product_id  && items_id.shipping_unit =='carton')
			 		// let finalValue_remaining_carton =  ProductMatch_remaining_carton.reduce(function(prev, index){
					// 	return parseInt(prev) + parseInt(index.remaining_carton_count) 
					// }, 0);
					let finalValue_remaining_carton_dividedbyInEachCarton =  ProductMatch_remaining_carton.reduce(function(prev, index){
					return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0);
		  			let ProductMatch_remaining_Units = this.outboundProducts_clone.filter(val => val.product_id == items_id.product_id  &&  items_id.shipping_unit == "single_item")
			 		let finalValue_remaining_Units =  ProductMatch_remaining_Units.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0);
		  			let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === items_id.product_id)
					if (findItem !== "undefined" && findItem !== undefined) {
						if(findItem.is_from_inventory ==true){
							// variables for Calculation
							let availableCartonUnits = findItem.available == null ? 0 : findItem.available
							let In_Each_Carton = findItem.in_each_carton !== null ? findItem.in_each_carton : 1
							let expected_carton_count = availableCartonUnits
							let final_total_Unit = availableCartonUnits

							// total unit value get
							let oldvalue = finalValue_remaining_Units
							let newvalue = finalValue
							let result = ((oldvalue) - (newvalue))
							let final_result = (result) + (availableCartonUnits)
								final_total_Unit = final_result

			  				if(final_result < 0){
								final_total_Unit = final_result
			 			 	}else{
								final_total_Unit = availableCartonUnits + finalValue_remaining_Units
			  				}

							// availableCartonUnits
							let oldvalueC = finalValue_remaining_carton_dividedbyInEachCarton
							let newvalueC = finalValue
							let resultC = ((oldvalueC) - (newvalueC))
							let final_resultC = (resultC) + (availableCartonUnits)

							if(final_resultC < 0){
								expected_carton_count = final_resultC
			 			 	}else{
								expected_carton_count = (parseInt(availableCartonUnits) + parseInt(finalValue_remaining_carton_dividedbyInEachCarton) ) / parseInt(In_Each_Carton)
			  				}
							if (items_id.shipping_unit =='carton' && parseInt(finalValue) > parseInt(expected_carton_count)) return false
		  					else if(items_id.shipping_unit =='single_item' && parseInt(finalValue) > parseInt(final_total_Unit)) return false
		  					return true		
						}else{
			  				return false
						}
		  			}
					else{
			  			return true
					}
		  		}
				else{
					let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === items_id.product_id)
					if (findItem !== "undefined" && findItem !== undefined) {
			  			if(findItem.is_from_inventory ==true){
							let In_Each_Carton = findItem.in_each_carton !== null ? findItem.in_each_carton : 1
							let expected_carton_count = parseInt(item)  * parseInt(In_Each_Carton)
			  				let final_total_value = findItem.total_unit <0 ? 0 : findItem.total_unit
			  				let final_total_Unit = final_total_value
		  					if (items_id.shipping_unit =='carton' && parseInt(expected_carton_count) > parseInt(final_total_Unit)) return false
		  					else if(items_id.shipping_unit =='single_item' && parseInt(item) > parseInt(final_total_Unit)) return false
		  					return true
			  			}
			  			else{
							return false
			  			}
		  			}
					else{
			  			return true
					}
		  		}
			}
		  	else{
				return true
		  	}
		},
		quantityCountCheckErrorShow6PL(item,items_id){
		  	if (item !== '' && item !== null && this.isWarehouse3PL ==false && this.isWarehouse6PL ==true) {
				if(this.editedOutboundIndex > -1){
					let ProductMatch = this.outboundProducts.filter(val => val.product_id == items_id.product_id  && val.shipping_unit == items_id.shipping_unit)
			 		let finalValue =  ProductMatch.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.quantity) 
					}, 0);
					let ProductMatch_remaining_carton = this.outboundProducts_clone.filter(val => val.product_id == items_id.product_id && items_id.shipping_unit =='carton')
			 		// let finalValue_remaining_carton =  ProductMatch_remaining_carton.reduce(function(prev, index){
					// 	return parseInt(prev) + parseInt(index.remaining_carton_count) 
					// }	, 0);
					let finalValue_remaining_carton_dividedbyInEachCarton =  ProductMatch_remaining_carton.reduce(function(prev, index){
					return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0);
		  			let ProductMatch_remaining_Units = this.outboundProducts_clone.filter(val => val.product_id == items_id.product_id && items_id.shipping_unit == "single_item")
			 		let finalValue_remaining_Units =  ProductMatch_remaining_Units.reduce(function(prev, index){
						return parseInt(prev) + parseInt(index.remaining_total_unit) 
					}, 0);
					let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === items_id.product_id)
					if (findItem !== "undefined" && findItem !== undefined) {
			  			if(findItem.is_from_inventory ==true){
							// variables for Calculation
							let availableCartonUnits = findItem.available == null ? 0 : findItem.available
							let In_Each_Carton = findItem.in_each_carton !== null ? findItem.in_each_carton : 1 
			  				let final_total_Unit = availableCartonUnits;
			  				let Expected_carton_count = availableCartonUnits 

			  				// total unit value get
							let oldvalue = finalValue_remaining_Units
							let newvalue = finalValue
							let result = ((oldvalue) - (newvalue))
							let final_result = (result) + (availableCartonUnits)
	
			  				if(final_result < 0){
								final_total_Unit = final_result
			  				}else{
								final_total_Unit = availableCartonUnits + finalValue_remaining_Units
			  				}

							// AvailableCartonUnits
							let oldvalueC = finalValue_remaining_carton_dividedbyInEachCarton
							let newvalueC = finalValue
							let resultC = ((oldvalueC) - (newvalueC))
							let final_resultC = (resultC) + (availableCartonUnits)

							if(final_resultC < 0){
								Expected_carton_count = final_resultC
							}else{
								Expected_carton_count = (parseInt(availableCartonUnits) + parseInt(finalValue_remaining_carton_dividedbyInEachCarton)) / parseInt(In_Each_Carton)
							}

			  				if (items_id.shipping_unit == "carton" && parseInt(finalValue) > parseInt(Expected_carton_count)) return "We don't have enough product in the inventory";
			  				else if (items_id.shipping_unit == "single_item" && parseInt(finalValue) > parseInt(final_total_Unit)) {
								return "We don't have enough product in the inventory";
			  				}
			  				return ''		
			  			}else{
							return "This product is not from inventory"
			  			}
					}else{
			  			return ''
					}
				}else{
			 		let findItem = _.find(this.productsDropdownOutbound, (e) => e.product_id === items_id.product_id)
					if (findItem !== "undefined" && findItem !== undefined) {
			  			if(findItem.is_from_inventory ==true){
							let In_Each_Carton = findItem.in_each_carton !== null ? findItem.in_each_carton : 1
							let Expected_carton_count = parseInt(item)  * parseInt(In_Each_Carton)
							let final_total_value = findItem.total_unit <0 ? 0 : findItem.total_unit
							let final_total_Unit = final_total_value
			  				if (items_id.shipping_unit == "carton" && parseInt(Expected_carton_count) > parseInt(final_total_Unit)) return "We don't have enough product in the inventory";
			  				else if (items_id.shipping_unit == "single_item" && parseInt(item) > parseInt(final_total_Unit)) {
								return "We don't have enough product in the inventory";
			  				}
			  				return ''
						}else{
							return "This product is not from inventory"
						}
					}else{
						return ''
					}
				}
		  	}else{
				return ''
			}
		},
		minimumValueError(item_status){
	  		let quantity =parseInt(item_status)
		 	if( quantity ===0){
		   		return "Quantity can't be zero"
		 	}
		 	return ''
		},
		// customFilter (item, queryText) {
		//   const textOne = item.name.toLowerCase()
		//   let textTwo = "";
		//   if (item.category_sku_with_skuID !== null) {
		//     textTwo = item.category_sku_with_skuID.toString().toLowerCase();
		//   }

		//   const searchText = queryText.toLowerCase();

		//   return (
		//     textOne.indexOf(searchText) > -1 || textTwo.indexOf(searchText) > -1
		//   );
		// },
		customFilter(item, queryText, itemText) {
			const data = item.category_sku + item.category_id + item.name.toLowerCase() + item.sku + itemText.toLocaleLowerCase()
			const searchText = queryText.toLowerCase()
			return data.indexOf(searchText) > -1 
		},
		getNameWithoutExt(name) {
	  		if (name !== null && name !== "") {
				let filter = name.lastIndexOf(".");
				if (filter !== -1 && filter > 37) {
				  	return name.substring(0, 37) + "...";
				} else if (filter == -1 ) {
				  	return name.substring(0, 37);
				} else {
				  	return name.substring(0, filter);
				}
	  		} else {
				return "--";
	  		}
		},
		// warehouse 6PL
		// ------ <<<< ---------->>>>> -------
		clearValueonchange(product_value){
	  		if(product_value ==null){
				let val = {
			  		id:null
				}
			 	this.$emit('Warehouse6PL_ProductsOnchange',val)
				return this.outboundProducts.filter(val => val.product_id = '')
	  		}
		},
	 	Warehouse6PL_ProductsOnchange(product_value){
	  		this.oldValueSAve = this.outboundItems.warehouse_customer_id
	  		if(this.outboundProducts.some(val => val.product_id !=='' && val.product_id !==null && product_value.warehouse_customer_id !==null)){
				this.showWarning_Warehouse_Product_Dialog =true
				this.onchangeValue_of_warehouse_customer =product_value
	  		}else{
				this.$emit('Warehouse6PL_ProductsOnchange',product_value)
				this.outboundProducts.filter(val => val.product_id = '')
	  		}
		},
	 	confirmChangeCustomerDropdown(){
	  		this.$emit('Warehouse6PL_ProductsOnchange',this.onchangeValue_of_warehouse_customer)
	  		this.showWarning_Warehouse_Product_Dialog =false
	  		this.outboundProducts.filter(val => val.product_id = '')
		},
		closeWarning(){
	  		this.showWarning_Warehouse_Product_Dialog =false
	  		this.outboundItems.warehouse_customer_id = this.oldValueSAve
		},
		itemDisabled(item){
			let findOutboundItem = _.find(this.productsDropdownOutbound, e => e.product_id === item && ( e.in_each_carton === null || e.in_each_carton == 0))
			if (findOutboundItem !== 'undefined' && findOutboundItem !==undefined ) {
				return false
			}else{
				return true
			}	
		}
  	},
  	async mounted() {
  	},
  	updated() {
		if (
	  		typeof this.outboundItems !== "undefined" &&
	  		(this.editedOutboundIndex > -1 || this.outboundItems.isDuplicate)
		) {
	  		if (this.outboundProducts !== this.outboundItems.outbound_products) {
				this.outboundProducts = this.outboundItems.outbound_products;
	  		}
	  		if (this.files !== this.outboundItems.outbound_documents) {
				this.files = this.outboundItems.outbound_documents;
	  		}
	  		if (this.itemStorable !== this.outboundItems.outbound_storable_units && this.outboundItems.isDuplicate !== true) {
				this.itemStorable = this.outboundItems.outbound_storable_units;
				// console.log("itemStorable",this.itemStorable)
	  		}
	  		// if (
	  		//   this.outboundItems.estimated_date !== null &&
	  		//   this.outboundItems.estimated_date !== ""
	  		// ) {
	  		//   this.outboundItems.estimated_date = moment(
	  		//     this.outboundItems.estimated_date
	  		//   ).format("MM/DD/YYYY");
	  		// }
	  		if (
				this.outboundItems.notes == "undefined" ||
				this.outboundItems.notes == "null"
	  		) {
				this.outboundItems.notes = "";
	  		}
	  		if (
				this.outboundItems.name == "undefined" ||
				this.outboundItems.name == "null"
	  		) {
				this.outboundItems.name = "";
	  		}
	  		if (
				this.outboundItems.bol_number == "undefined" ||
				this.outboundItems.bol_number == "null"
	  		) {
				this.outboundItems.bol_number = "";
	  		}
	  		if (
				this.outboundItems.ref_no == "undefined" ||
				this.outboundItems.ref_no == "null"
	  		) {
				this.outboundItems.ref_no = "";
	  		}
	  		if (
				this.outboundItems.deliver_to == "undefined" ||
				this.outboundItems.deliver_to == "null"
	  		) {
				this.outboundItems.deliver_to = "";
	  		}
	   		if (
				this.outboundItems.customer == "undefined" ||
				this.outboundItems.customer == "null"
	  		) {
				this.outboundItems.customer = "";
	  		}
		}
		if (this.editedIndex === -1) {
	  		if (typeof this.$refs.form !== "undefined") {
				if (typeof this.$refs.form.resetValidation() !== "undefined") {
		  			this.$refs.form.resetValidation();
				}
	  		}
		}
  	},
};
</script>

<style lang="scss">
	@import "@/assets/scss/pages_scss/inventory/outbound/outboundDialog.scss";
	@import "@/assets/scss/pages_scss/inventory/outbound/storableOutboundDialog.scss";

	.storable-unit-button-attach {
		position: sticky !important;
	}

	.cartons-separator {
		display: flex;
		justify-content: flex-start;
		align-items: center;
		margin-bottom: 3px;

		p {
			text-align: left !important;
			font-size: 12px !important;
			color: #6d858f;
			margin-bottom: 0;
		}

		.separator {
			width: 6px;
			height: 6px;
			background-color: #ebf2f5;
			border-radius: 50%;
			margin: 0 6px;
		}
	}

	.labelColor {
		color: #6d858f !important;
		font-size: 12px !important;
		font-family: "Inter-SemiBold", sans-serif;
		font-weight: unset;
	}

	.skuColor {
		color: #4a4a4a !important;
		font-weight: 600 !important;
	}
	.mywidth{
	  width: 300px !important;
	}
	.shipping-unit-width{
	  width: 150px !important;
	}
	.color_change {
	  color: #D68A1A !important;
	}
	.create-outbound-dialog-connected {
		max-width: 1360px !important;
	}
	.instructions-input-field-outbound {
		white-space: nowrap;
    	overflow: hidden;
    	text-overflow: ellipsis;
		border: 1px solid #B4CFE0;
		padding: 0 12px;
		border-radius: 4px;
		height: 45px;
		font-size: 14px;
		color: #4A4A4A;;
		outline: none;
		width: 100%;

	}
	.connected-warehouse-oubound-instructions input::placeholder  {
		color: #B4CFE0;
	}
	
</style>