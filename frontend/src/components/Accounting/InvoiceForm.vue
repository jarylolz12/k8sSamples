<template>
	<div class="text-center">
		<v-dialog
			v-model="showDialog"
			max-width="1366"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card :loading="isSaving">
				<v-card-title class="pa-0 z-index-front">
					<span class="headline">
						{{ isEditMode ? 'Edit Invoice' : 'Create Invoice' }}
						<v-chip
							v-if="isEditMode"
							class="ml-2 text-uppercase"
							filter
							:text-color="statusColor.color"
							:color="statusColor.background"
						>
							{{ formData.status }}
						</v-chip>
					</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose" :disabled="isSaving">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>

				<v-card-text class="px-0 pb-0">
					<!-- <v-container class="grey lighten-4 pa-0 mx-0"> -->
					<v-form ref="invoiceForm">
						<v-card class="pa-0 grey lighten-4" outlined tile :loading="isSaving">
							<v-row no-gutters>
								<v-col cols="12" sm="12" md="4" class="item-form-column pa-4">
									<v-card class="grey lighten-4" outlined tile>
										<label
											class="labelcolor--text text-uppercase form-label"
											for="formdata-invoice-number"
											>{{ 'Invoice No.' }}</label
										>
										<v-text-field
											v-model="formData.document_number"
											:label="'Enter Invoice No.'"
											solo
											class="app-theme-input-border"
											flat
											required
											dense
											outlined
											id="formdata-invoice-number"
											:rules="[(v) => !!v || 'Field required']"
											hide-details="auto"
											autocomplete="off"
										></v-text-field>

										<label
											class="labelcolor--text text-uppercase form-label"
											for="formdata-order-number"
											>{{ 'Order Number' }}</label
										>
										<v-text-field
											v-model="formData.order_number"
											:label="'Enter order number'"
											solo
											class="app-theme-input-border"
											flat
											required
											dense
											id="formdata-order-number"
											outlined
											hide-details="auto"
											autocomplete="off"
										></v-text-field>

										<label class="labelcolor--text text-uppercase form-label" for="formdata-customer">{{
											'customer'
										}}</label>
										<v-autocomplete
											id="formdata-customer"
											v-model="formData.customer"
											:loading="isCustomerDataLoading"
											:items="customerDataComputed"
											:search-input.sync="searchCustomerText"
											clearable
											dense
											solo
											flat
											outlined
											item-text="text"
											item-value="value"
											:rules="[(v) => !!v || 'Field required']"
											class="app-theme-input-border"
											@change="onChangeCustomer"
											hide-details="auto"
											label="Select Customer"
											:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
										></v-autocomplete>

										<div v-if="homeCurrency !== customerCurrency" class="mb-4">
											<label
												class="labelcolor--text text-uppercase form-label"
												for="formdata-currency-rate"
												>{{ 'Currency Rate' }}</label
											>
											<v-text-field
												v-model="formData.currency_rate"
												:label="'Currency Rate'"
												:rules="[(v) => (!!v && v > 0) || 'Field required']"
												solo
												class="app-theme-input-border align-center"
												outlined
												flat
												required
												dense
												hide-details="auto"
												id="formdata-currency-rate"
											>
												<template v-slot:prepend>
													<span>&nbsp;1&nbsp;</span>
													<span>{{ homeCurrency }}</span>
													<span>&nbsp;=</span>
												</template>
												<template v-slot:append-outer>
													<span>{{ customerCurrency }}</span>
												</template>
											</v-text-field>
										</div>

										<div class="d-sm-flex justify-sm-space-between justify-end mb-sm-0 mb-2">
											<div class="mr-0 mr-sm-1">
												<label
													class="labelcolor--text text-uppercase form-label"
													for="formdata-invoice-date"
													>{{ 'Invoice Date' }}</label
												>
												<date-picker
													v-model="formData.issued_at"
													id="formdata-invoice-date"
													:rules="[(v) => !!v || 'Field required']"
												></date-picker>

												<!-- <v-text-field
													type="date"
													class="text-fields" 
													placeholder="Select Date" 
													outlined
													hide-details="auto"
													v-model="formData.issued_at"
													id="formdata-invoice-date"
													:rules="[(v) => !!v || 'Field required']" /> -->
											</div>
											<div class="ml-0 ml-sm-1">
												<label
													class="labelcolor--text text-uppercase form-label"
													for="formdata-due-date"
													>{{ 'Due Date' }}</label
												>
												<date-picker
													v-model="formData.due_date"
													id="formdata-due-date"
													:rules="[(v) => !!v || 'Field required']"
													:min="formData.issued_at"
												></date-picker>

												<!-- <v-text-field
													type="date"
													class="text-fields" 
													placeholder="Select Date" 
													outlined
													hide-details="auto"
													v-model="formData.due_date"
													id="formdata-due-date"
													:min="formData.issued_at"
													:rules="[(v) => !!v || 'Field required']" /> -->
											</div>
										</div>

										<label class="form-label text-uppercase form-label" for="formdata-category">{{
											'category'
										}}</label>
										<v-select
											v-model="formData.category"
											:items="categoryDataList"
											:rules="[(v) => !!v || 'Field required']"
											id="formdata-category"
											solo
											flat
											class="app-theme-input-border"
											dense
											outlined
											item-value="value"
											item-text="text"
											:loading="isCategoryDataLoading"
											hide-details="auto"
											label="Select Category"
											:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
										>
										</v-select>

										<label class="labelcolor--text text-uppercase form-label" for="formdata-notes">{{
											'notes'
										}}</label>
										<v-textarea
											v-model="formData.notes"
											solo
											:label="'Type your notes here' + '...'"
											class="app-theme-input-border"
											flat
											id="formdata-notes"
											rows="2"
											hide-details
											outlined
										></v-textarea>

										<!-- <v-spacer class="mt-2"></v-spacer> -->

										<div v-if="isQBOEnabled" class="">
											<label class="form-label text-uppercase form-label" for="formdata-terms">{{
												'terms'
											}}</label>
											<v-select
												v-model="formData.qbo_term_ref"
												:items="termsDataList"
												id="formdata-terms"
												solo
												flat
												class="app-theme-input-border"
												dense
												outlined
												:rules="[(v) => !!v || 'Field required']"
												hide-details="auto"
												placeholder="Select Terms"
												:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
											>
											</v-select>

											<label
												class="labelcolor--text text-uppercase form-label"
												for="formdata-bill-email"
												>{{ 'Customer Email' }}</label
											>
											<v-text-field
												v-model="formData.bill_to_email"
												:label="'Enter email'"
												solo
												class="app-theme-input-border"
												flat
												required
												dense
												id="formdata-bill-email"
												:rules="[(v) => !!v || 'Field required']"
												outlined
												hide-details="auto"
											></v-text-field>

											<label
												class="labelcolor--text text-uppercase form-label"
												for="formdata-message-statement"
												>{{ 'Message on Statement' }}</label
											>
											<v-textarea
												v-model="formData.qbo_private_note"
												:label="'Enter message'"
												solo
												class="app-theme-input-border"
												flat
												id="formdata-message-statement"
												rows="2"
												hide-details="auto"
												outlined
											></v-textarea>
											<!-- <div class="my-4">&nbsp;</div> -->
											<label
												class="labelcolor--text text-uppercase form-label"
												for="formdata-billing-address"
												>{{ 'Billing Address' }}</label
											>
											<v-textarea
												v-model="formData.bill_to_address"
												solo
												class="app-theme-input-border"
												flat
												id="formdata-billing-address"
												rows="2"
												hide-details="auto"
												outlined
											></v-textarea>
										</div>
									</v-card>
								</v-col>
								<v-col class="item-form-column white pa-4">
									<v-card class="" flat>
										<div v-if="!isMobile">
											<table
												class="mb-0 w-100 accounting-table"
												dense
												v-if="formData.items.length"
											>
												<thead class="grey lighten-3">
													<tr>
														<th
															class="pl-3 text-left text-uppercase text-subtitle-2 th--text py-2"
															width="25%"
														>
															{{ 'Item' }}
														</th>
														<th
															class="text-left text-uppercase text-subtitle-2 th--text"
														>
															{{ 'Description' }}
														</th>
														<th
															class="text-start text-uppercase text-subtitle-2 th--text"
															width="10%"
														>
															{{ 'Quantity' }}
														</th>
														<th
															class="text-start text-uppercase text-subtitle-2 th--text"
															width="10%"
														>
															{{ 'Price' }}
														</th>
														<th
															class="text-start text-uppercase text-subtitle-2 th--text"
															width="10%"
														>
															{{ 'Amount' }}
														</th>
														<th class="th--text" width="5%">&nbsp;</th>
													</tr>
												</thead>
												<tbody>
													<template v-for="(item, index) in formData.items">
														<tr :key="`item_index_${index}-top`">
															<td colspan="6">
																<v-sheet height="8"></v-sheet>
															</td>
														</tr>
														<tr :key="`item_index_${index}`">
															<td class="px-3 pt-1">
																{{ item.item.name }}
																<v-menu content-class="accounting-invoice-menu" :close-on-content-click="false" offset-y>
																	<template v-slot:activator="{ on, attrs }">
																		<div>
																			<a
																				color="primary"
																				dark
																				v-bind="attrs"
																				v-on="on"
																			>
																				{{ 'Edit Income Account' }}
																			</a>
																		</div>
																	</template>

																	<v-card width="300">
																		<v-card-text>
																			<p style="color: #4a4a4a;">
																				{{
																					'An income account is used for proper bookkeeping of your sales and to keep your reports accurate.'
																				}}
																			</p>
																			<label
																				:for="`account-${item.item.id}`"
																				class="labelcolor--text form-label"
																				>{{ 'Account' }}</label
																			>
																			<v-autocomplete
																				v-model="
																					item.item
																						.de_income_account_value
																				"
																				:items="incomeAccountLists"
																				:id="`account-${item.item.id}`"
																				clearable
																				dense
																				outlined
																				placeholder="Select Account"
																				hide-details="auto"
																				:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
																			></v-autocomplete>
																		</v-card-text>
																	</v-card>
																</v-menu>
															</td>
															<td class="pr-2">
																<!-- <v-textarea
																	v-model="item.description"
																	solo
																	class="app-theme-input-border"
																	flat
																	dense
																	row-height="1"
																	auto-grow
																	hide-details
																	outlined
																></v-textarea> -->

																<v-text-field
																	v-model="item.description"
																	solo
																	flat
																	dense
																	type="text"
																	class="app-theme-input-border text-right"
																	hide-details
																	outlined
																></v-text-field>
															</td>
															<td class="px-0 pr-2">
																<v-text-field
																	v-model.number="item.quantity"
																	solo
																	flat
																	@input="computeItemTotalPerIndex(index)"
																	required
																	dense
																	type="number"
																	class="app-theme-input-border text-right"
																	hide-details
																	outlined
																></v-text-field>
															</td>
															<td class="px-0 pr-2">
																<v-text-field
																	v-model.number="item.price"
																	@input="computeItemTotalPerIndex(index)"
																	solo
																	flat
																	required
																	dense
																	type="number"
																	class="app-theme-input-border text-right"
																	hide-details
																	outlined
																></v-text-field>
															</td>
															<td class="px-0 pr-2">
																<v-text-field
																	v-model.number="item.total"
																	solo
																	flat
																	required
																	hide-details
																	dense
																	readonly
																	outlined
																	class="app-theme-input-border text-right"
																></v-text-field>
															</td>
															<td>
																<v-btn
																	icon
																	color="red"
																	small
																	@click="onRemoveItem(index)"
																	class="pt-3"
																>
																	<v-icon>mdi-close</v-icon>
																</v-btn>
															</td>
														</tr>
														<!-- UI for QBO User -->
														<template v-if="isQBOEnabled">
															<tr :key="`item_index-${index}-switch-tax`">
																<td>
																	<v-switch
																		v-model="item.qbo_taxable"
																		:value="1"
																		label="Taxable"
																		class="mr-12 mt-0"
																		hide-details
																		@change="onChangeTaxableItem($event, index)"
																	/>
																</td>
																<td>
																	<div class="mt-1">
																		<v-menu
																			v-if="item.qbo_taxable === 1"
																			offset-y
																			:close-on-content-click="false"
																		>
																			<template
																				v-slot:activator="{ on, attrs }"
																			>
																				<a
																					color="primary"
																					dark
																					v-bind="attrs"
																					v-on="on"
																				>
																					Tax Rates
																				</a>
																			</template>
																			<v-card width="300">
																				<v-card-text>
																					<span class="labelcolor--text"
																						>Tax Rate Applied</span
																					>
																					<div
																						v-for="tax in item.tax_rates"
																						:key="tax.id"
																					>
																						<div
																							class="d-flex justify-space-between"
																						>
																							<span>
																								{{ tax.name }} -
																								{{ tax.rate }}%
																							</span>
																							<span>
																								{{
																									currencyFormat(
																										roundAmount(
																											(Number(
																												tax.rate ||
																													0
																											) /
																												100) *
																												(item.total -
																													discountPerItem(
																														item.total
																													))
																										)
																									)
																								}}
																							</span>
																						</div>
																					</div>
																				</v-card-text>
																			</v-card>
																		</v-menu>
																	</div>
																</td>
															</tr>
														</template>
														<!-- UI for Non-QBO User -->
														<template v-if="isQBOEnabled === 0">
															<tr :key="`item_tax_rate_taxable-${index}`">
																<td>
																	<v-switch
																		v-model="item.qbo_taxable"
																		:value="1"
																		label="Taxable"
																		class="mr-12 mt-0"
																		hide-details
																		@change="onChangeTaxableItem($event, index)"
																	/>
																</td>
																<td colspan="5">
																	<div
																		v-for="(tax_rates,
																		index2) in item.tax_rates"
																		:key="`tax_rate_item-${index2}`"
																	>
																		<div
																			class="d-flex align-center justify-end mr-1 py-2"
																		>
																			<v-sheet width="340" class="ml-2">
																				<v-select
																					v-model="tax_rates.id"
																					item-text="name"
																					item-value="id"
																					dense
																					outlined
																					flat
																					hide-details="auto"
																					placeholder="- Select Tax -"
																					:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
																					:items="taxRateLists"
																					:item-disabled="
																						(item) =>
																							checkIsTaxItemDisabled(
																								item,
																								index
																							)
																					"
																					:disabled="!item.qbo_taxable"
																					@change="
																						onChangeItemTax(
																							$event,
																							index,
																							index2
																						)
																					"
																				>
																					<template
																						v-slot:item="{ item }"
																					>
																						<span
																							>{{ item.name }} ({{
																								item.rate
																							}}%)</span
																						>
																					</template>
																					<template
																						v-slot:selection="{ item }"
																					>
																						<span
																							>{{ item.name }} ({{
																								item.rate
																							}}%)</span
																						>
																					</template>
																				</v-select>
																			</v-sheet>
																			<v-sheet width="80" class="pl-4 pb-3">
																				{{
																					roundAmount(
																						(Number(
																							tax_rates.rate || 0
																						) /
																							100) *
																							item.total
																					)
																				}}
																			</v-sheet>
																			<v-sheet width="50" class="text-center">
																				<v-btn
																					v-if="
																						index2 + 1 <
																							item.tax_rates.length
																					"
																					icon
																					color="red"
																					small
																					@click="
																						onRemoveItemTax(
																							index,
																							index2
																						)
																					"
																				>
																					<v-icon>mdi-close</v-icon>
																				</v-btn>
																			</v-sheet>
																		</div>
																	</div>
																</td>
															</tr>
														</template>
														<tr :key="`item_index_${index}-border`">
															<td colspan="6" class="pt-3">
																<v-divider></v-divider>
															</td>
														</tr>
													</template>
												</tbody>
											</table>
											<div v-if="showSelectItem" class="mt-6 d-flex justify-space-between">
												<item-async-autocomplete
													:isCategoryItem="false"
													:income="true"
													v-model="item"
													@change="onItemChange"
												></item-async-autocomplete>
												<v-btn
													class="text-capitalize pt-2"
													text
													color="red"
													@click="showSelectItem = false"
													icon
												>
													<v-icon>mdi-close</v-icon>
												</v-btn>
											</div>
											<v-btn
												outlined
												text
												small
												@click="onAddItem"
												color="#0889a0"
												class="my-4 labelcolor--text py-4 text-capitalize btn-white"
											>
												<v-icon size="18">mdi-plus</v-icon>
												{{ 'Add Item' }}
											</v-btn>
											<v-divider />
										</div>

										<v-sheet
											color="transparent"
											class="d-flex flex-column d-md-none d-lg-none d-xl-none mb-2"
											v-if="isMobile"
										>
											<v-subheader class="text-h6 labelcolor--text">{{
												'Items'
											}}</v-subheader>
											<v-card
												outlined
												flat
												class="mx-2 mb-4 pb-2"
												v-for="(item, index) in formData.items"
												:key="`item_index_${index}`"
											>
												<v-toolbar flat>
													<div>
														<h4>{{ item.item.name }}</h4>
														<div>
															<v-menu :close-on-content-click="false" offset-y>
																<template v-slot:activator="{ on, attrs }">
																	<div>
																		<a
																			color="primary"
																			dark
																			v-bind="attrs"
																			v-on="on"
																		>
																			{{ 'Edit Income Account' }}
																		</a>
																	</div>
																</template>

																<v-card width="300">
																	<v-card-text>
																		<p>
																			{{
																				'An income account is used for proper bookkeeping of your sales and to keep your reports accurate.'
																			}}
																		</p>
																		<label
																			:for="`account-${item.item.id}`"
																			class="labelcolor--text"
																			>{{ 'Account' }}</label
																		>
																		<v-autocomplete
																			v-model="
																				item.item.de_income_account_value
																			"
																			:items="incomeAccountLists"
																			:id="`account-${item.item.id}`"
																			clearable
																			dense
																			outlined
																			:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
																		></v-autocomplete>
																	</v-card-text>
																</v-card>
															</v-menu>
														</div>
													</div>
													<v-spacer />
													<v-btn small icon color="red" @click="onRemoveItem(index)">
														<v-icon>mdi-delete</v-icon>
													</v-btn>
												</v-toolbar>
												<div class="d-flex px-2 py-1">
													<div>
														<label
															class="form-label text-uppercase"
															for="formdata-quantity"
															>{{ 'Quantity' }}</label
														>
														<v-text-field
															v-model.number="item.quantity"
															solo
															flat
															@input="computeItemTotalPerIndex(index)"
															:rules="[(v) => (v && v >= 1) || 'Minimum is 1']"
															required
															dense
															type="number"
															class="app-theme-input-border text-right"
															hide-details
															outlined
														/>
													</div>
													<div class="mx-1">
														<label
															class="form-label text-uppercase"
															for="formdata-quantity"
															>{{ 'Price' }}</label
														>
														<v-text-field
															v-model.number="item.price"
															@input="computeItemTotalPerIndex(index)"
															solo
															flat
															required
															dense
															type="number"
															class="app-theme-input-border text-right"
															hide-details
															outlined
														/>
													</div>
													<div>
														<label
															class="form-label text-uppercase"
															for="formdata-quantity"
															>{{ 'Total' }}</label
														>
														<v-text-field
															v-model.number="item.total"
															solo
															flat
															required
															hide-details
															dense
															readonly
															class="app-theme-input-border text-right"
															outlined
														/>
													</div>
												</div>
												<v-textarea
													v-model="item.description"
													solo
													class="app-theme-input-border mx-2"
													flat
													dense
													placeholder="Description..."
													rows="2"
													outlined
												></v-textarea>
												<div class="px-2">
													<div class="d-flex justify-space-between align-center">
														<v-switch
															v-model="item.qbo_taxable"
															:value="1"
															label="Taxable"
															dense
															hide-details
															class="mt-0"
															@change="onChangeTaxableItem($event, index)"
														></v-switch>
														<v-menu
															v-if="item.qbo_taxable === 1 && isQBOEnabled === 1"
															offset-y
															:close-on-content-click="false"
														>
															<template v-slot:activator="{ on, attrs }">
																<a color="primary" dark v-bind="attrs" v-on="on">
																	Tax Rates
																</a>
															</template>
															<v-card width="300">
																<v-card-text>
																	<span class="labelcolor--text"
																		>Tax Rate Applied</span
																	>
																	<div
																		v-for="tax in item.tax_rates"
																		:key="tax.id"
																	>
																		<div class="d-flex justify-space-between">
																			<span>
																				{{ tax.name }} - {{ tax.rate }}%
																			</span>
																			<span>
																				{{
																					currencyFormat(
																						roundAmount(
																							Number(
																								(Number(
																									tax.rate || 0
																								) /
																									100) *
																									item.total -
																									discountPerItem(
																										item.total
																									)
																							)
																						)
																					)
																				}}
																			</span>
																		</div>
																	</div>
																</v-card-text>
															</v-card>
														</v-menu>
													</div>
													<div v-if="isQBOEnabled === 0">
														<div
															v-for="(tax_rates, index2) in item.tax_rates"
															:key="`item_index_${index}-${index2}`"
														>
															<div class="mb-2">
																<div class="d-flex justify-space-between">
																	<label class="labelcolor--text"
																		>Tax Rates</label
																	>
																	<v-btn
																		v-if="index2 + 1 < item.tax_rates.length"
																		icon
																		color="red"
																		small
																		@click="onRemoveItemTax(index, index2)"
																	>
																		<v-icon>mdi-delete</v-icon>
																	</v-btn>
																	<span v-else>&nbsp;</span>
																</div>
																<div
																	class="d-flex justify-space-between align-center"
																>
																	<v-sheet>
																		<v-select
																			v-model="tax_rates.id"
																			item-text="name"
																			item-value="id"
																			dense
																			outlined
																			flat
																			hide-details
																			placeholder="- Select Tax -"
																			:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
																			:items="taxRateLists"
																			:item-disabled="
																				(item) =>
																					checkIsTaxItemDisabled(
																						item,
																						index
																					)
																			"
																			:disabled="!item.qbo_taxable"
																			@change="
																				onChangeItemTax(
																					$event,
																					index,
																					index2
																				)
																			"
																		>
																			<template v-slot:item="{ item }">
																				<span
																					>{{ item.name }} ({{
																						item.rate
																					}}%)</span
																				>
																			</template>
																			<template v-slot:selection="{ item }">
																				<span
																					>{{ item.name }} ({{
																						item.rate
																					}}%)</span
																				>
																			</template>
																		</v-select>
																	</v-sheet>
																	<v-sheet width="60" class="text-center">
																		<span>
																			{{
																				roundAmount(
																					(Number(tax_rates.rate || 0) /
																						100) *
																						(item.total -
																							discountPerItem(
																								item.total
																							))
																				)
																			}}
																		</span>
																	</v-sheet>
																</div>
															</div>
														</div>
													</div>
												</div>
											</v-card>
											<div v-if="showSelectItem" class="d-flex justify-space-between">
												<item-async-autocomplete
													:income="true"
													v-model="item"
													@change="onItemChange"
													:is-category-item="false"
												></item-async-autocomplete>
												<v-btn
													class="text-capitalize"
													text
													color="red"
													@click="showSelectItem = false"
													icon
												>
													<v-icon>mdi-close</v-icon>
												</v-btn>
											</div>
											<div class="mx-2 mb-4">
												<v-btn
													block
													text
													outlined
													color="primary"
													class="text-capitalize"
													@click="onAddItem"
												>
													{{ 'Add Item' }}
												</v-btn>
											</div>
										</v-sheet>

										<v-row class="mt-4">
											<v-col cols="12" sm="6">
												<div v-if="isQBOEnabled === 1 && hasItemTaxable" class="mb-4">
													<label for="formdata-taxcode" class="labelcolor--text form-label"
														>Tax Code</label
													>
													<v-select
														v-model="formData.tax_code_ref"
														id="formdata-taxcode"
														dense
														outlined
														flat
														hide-details
														:items="[
															{ text: '- Select -', value: '' },
															...taxCodeLists
														]"
														:rules="[(v) => !!v || 'Field required']"
														@change="onChangeTaxCode"
														:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
													></v-select>
												</div>
												<label class="text-uppercase labelcolor--text form-label">{{
													'Attachments'
												}}</label>
												<v-card
													class="text-center pa-5 border-dashed"
													flat
													@dragover="dragover"
													@dragleave="dragleave"
													@drop="drop"
													:class="[isBrowseFileHovered ? 'grey lighten-2' : '']"
												>
													<v-card-text class="pa-0 mb-0">
														<v-skeleton-loader
															class="mx-auto"
															max-width="300"
															type="card"
															v-if="attachmentLoading"
														></v-skeleton-loader>
														<v-list
															subheader
															two-line
															v-if="fileList.length || attachedFiles.length"
														>
															<v-subheader class="text-subtitle-1">{{
																'Files'
															}}</v-subheader>

															<v-list-item
																v-for="attachedFile in attachedFiles"
																:key="`attached-${attachedFile.id}`"
																dense
																class="pa-0"
																:class="{
																	'red--text': deletedAttachmendIds.includes(
																		attachedFile.id
																	)
																}"
															>
																<v-list-item-avatar height="20" width="20">
																	<v-icon
																		color="#0889a0"
																		v-text="'mdi-image'"
																	></v-icon>
																</v-list-item-avatar>

																<v-list-item-content>
																	<v-list-item-title
																		v-text="attachedFile.filename"
																		class="text-left"
																	></v-list-item-title>
																</v-list-item-content>

																<v-list-item-action class="flex-row">
																	<v-btn
																		icon
																		:title="'delete'"
																		@click="
																			onSelectAttachmentToDelete(attachedFile)
																		"
																	>
																		<v-icon
																			color="red"
																			v-if="
																				deletedAttachmendIds.includes(
																					attachedFile.id
																				)
																			"
																			>mdi-delete-off</v-icon
																		>
																		<v-icon color="red" v-else
																			>mdi-delete</v-icon
																		>
																	</v-btn>
																	<v-btn
																		icon
																		:title="'download'"
																		@click="onDownloadAttachment(attachedFile)"
																		:loading="
																			downloadingFileIds.includes(
																				attachedFile.id
																			)
																		"
																	>
																		<v-icon color="primary"
																			>mdi-download</v-icon
																		>
																	</v-btn>
																</v-list-item-action>
															</v-list-item>

															<v-list-item
																v-for="({ file, id }, index) in fileList"
																:key="id"
																dense
																class="pa-0"
															>
																<v-list-item-avatar height="20" width="20">
																	<v-icon
																		color="#0889a0"
																		v-text="'mdi-image'"
																	></v-icon>
																</v-list-item-avatar>

																<v-list-item-content>
																	<v-list-item-title
																		v-text="file.name"
																		class="text-left"
																	></v-list-item-title>
																</v-list-item-content>

																<v-list-item-action>
																	<v-btn icon @click="removeFile(index)">
																		<v-icon color="red">mdi-close</v-icon>
																	</v-btn>
																</v-list-item-action>
															</v-list-item>
														</v-list>
														<input
															type="file"
															multiple
															name="filelist[images][]"
															id="file-input-expense"
															class="display-none"
															@change="onFileBrowseChange"
															ref="fileAttachment"
															accept=".ai,.csv,.doc,.docx,.eps,.gif,.jpeg,.jpg,.ods,.pdf,.png,.rtf,.tif,.txt,.xls,.xlsx,.xml"
														/>
														<label
															for="file-input-expense"
															class="form-label pa-5"
															title="Click to browse file"
														>
															<h5 class="pa-2 labelcolor--text"
																style="font-size: 10px;">
																{{ 'Browse Or Drop Image' }}
															</h5>
														</label>
													</v-card-text>
												</v-card>
											</v-col>
											<v-col cols="12" sm="4" offset-sm="2" style="color: #4a4a4a;">
												<v-row>
													<v-col class="text-right" cols="8">{{ 'Subtotal' }}</v-col>
													<v-col class="text-right">{{ currencyFormat(subTotal) }}</v-col>
												</v-row>
												<v-row v-if="isQBOEnabled === 1" class="mt-0">
													<v-col class="text-right" cols="8">
														<v-menu offset-y left :close-on-content-click="false">
															<template v-slot:activator="{ on, attrs }">
																<a color="primary" dark v-bind="attrs" v-on="on">
																	Add Discount
																</a>
															</template>
															<v-card width="280" class="filter-items-wrapper">
																<v-card-text>
																	<div class="d-flex justify-space-between mb-2 align-center">
																		<div class="labelcolor--text form-label">Discount</div>
																		<v-btn
																			small
																			class="labelcolor--text text-capitalize btn-white"
																			depressed
																			outlined
																			text
																			@click="onClearDiscount">
																			Clear
																		</v-btn>
																	</div>
																	<div class="d-flex align-center">
																		<v-btn-toggle
																			v-model="discountData.discount_type"
																			mandatory
																			dense>
																			<v-btn
																				min-width="14"
																				min-height="40"
																				value="fixed"
																			>
																				<v-icon small
																					>mdi-currency-usd</v-icon
																				>
																			</v-btn>
																			<v-btn
																				min-width="14"
																				min-height="40"
																				value="percentage"
																			>
																				<v-icon small
																					>mdi-percent-outline</v-icon
																				>
																			</v-btn>
																		</v-btn-toggle>
																		<v-sheet width="80">
																			<v-text-field
																				v-model.number="
																					discountData.discount
																				"
																				type="number"
																				class="mt-0"
																				dense
																				hide-details
																				outlined
																				style="font-size: 14px;"
																			/>
																		</v-sheet>
																		<!-- <input
																			v-model.number="discountData.discount"
																			type="number"
																			class="discount-input"
																			/> -->
																		<span class="ml-2 labelcolor--text form-label mb-0">of Subtotal</span>
																	</div>
																</v-card-text>
															</v-card>
														</v-menu>
													</v-col>
													<v-col class="text-right"
														>-{{ currencyFormat(discount) }}</v-col
													>
												</v-row>
												<v-row class="mt-0">
													<v-col class="text-right" cols="8">{{ 'Tax' }}</v-col>
													<v-col class="text-right">{{ currencyFormat(taxTotal) }}</v-col>
												</v-row>
												<v-divider class="mt-2"></v-divider>
												<v-row class="mt-0">
													<v-col class="text-right" cols="8">{{ 'Total' }}</v-col>
													<v-col class="text-right">
														{{ currencyFormat(itemTotal) }}
													</v-col>
												</v-row>
												<v-row v-if="homeCurrency !== customerCurrency">
													<v-col cols="12" sm="2"></v-col>
													<v-col class="text-subtitle-1">
														<p>{{ 'Currency conversion' }}:</p>
														<p class="text-right">
															{{
																numberToCurrency(
																	itemTotal / formData.currency_rate,
																	homeCurrency
																)
															}}
															({{ homeCurrency }}) {{ 'at' }}
															<input
																type="number"
																:value="formData.currency_rate"
																readonly
																id="conversion_rate_input"
																class="px-2"
															/>
														</p>
													</v-col>
												</v-row>
											</v-col>
										</v-row>
									</v-card>
								</v-col>
							</v-row>
						</v-card>
					</v-form>
					<!-- </v-container> -->
				</v-card-text>

				<!-- <v-divider></v-divider> -->

				<v-card-actions class="justify-start">
					<v-btn @click="onSaveForm(true)" class="text-capitalize btn-blue" :loading="isSaving" v-if="!isEditMode || (isEditMode && formData.status === 'draft')" :disabled="isSaving">{{
						'Save and Send'
					}}</v-btn>
					<v-btn @click="onSaveForm(false)" class="text-capitalize btn-blue" :loading="isSaving" :disabled="isSaving">{{
						'Save'
					}}</v-btn>
					<v-btn text class="text-capitalize btn-white" @click="onClose" outlined :disabled="isSaving">{{
						'Cancel'
					}}</v-btn>
					<v-spacer></v-spacer>
				</v-card-actions>
			</v-card>
			<!-- <v-snackbar timeout="5000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar> -->
		</v-dialog>
	</div>
</template>

<script>
import moment from 'moment';
import { mapActions, mapGetters, mapState } from 'vuex';
import DatePicker from './DatePicker.vue';
import ItemAsyncAutocomplete from './ItemAsyncAutocomplete.vue';
import AkauntingService from '../../services/akaunting.service';
import { debounce, apiErrorMessage } from '../../utils/globalMethods';
import _ from 'lodash';
import globalMethods from '../../utils/globalMethods'

import currencies from './data/currencies.json';

export default {
	name: 'InvoiceForm',
	components: {
		DatePicker,
		ItemAsyncAutocomplete
	},
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			formData: {
				document_number: '',
				order_number: '',
				notes: '',
				issued_at: '',
				due_date: '',
				category: '',
				customer: null,
				items: [],
				qbo_term_ref: null,
				bill_to_email: null,
				qbo_private_note: null,
				bill_to_address: null,
				currency_rate: 1,
				home_currency: null,
				attachment: [],
				tax_code_ref: null,
				discount_ref: null,
				is_send: 0
			},
			discountData: {
				discount_type: 'fixed',
				discount: 0
			},
			customerData: [],
			item: null,
			itemsData: [],
			defaultForm: {},
			categoryData: [],
			isCategoryDataLoading: false,
			isCustomerDataLoading: false,
			isSaving: false,
			searchCustomerText: null,
			snackbarOption: {},
			showSnackbar: false,
			termsDataList: [],
			fileList: [],
			isBrowseFileHovered: false,
			showSelectItem: false,
			incomeAccountLists: [],
			attachedFiles: [],
			attachmentLoading: false,
			downloadingFileIds: [],
			deletedAttachmendIds: [],
			taxRates: [],
			taxCodes: []
		};
	},

	created() {
		this.defaultForm = JSON.parse(JSON.stringify({ ...this.formData }));
		this.fetchIncomeCategory();
		this.fetchCustomerList();
		this.fetchTerms();
	},

	watch: {
		searchCustomerText: debounce(function() {
			this.searchCustomer();
		}, 600),

		open(isTrue) {
			if (isTrue) {
				this.getDEaccountsAndTypes();
				this.fetchTaxRates();
				if (this.isQBOEnabled) {
					this.fetchTaxCodes();
				}
				if (!this.isEditMode) {
					const currentDate = this.moment(new Date(), 'YYYY-MM-DD');
					this.formData = {
						...JSON.parse(JSON.stringify(this.defaultForm)),
						issued_at: currentDate.format('YYYY-MM-DD'),
						due_date: currentDate.format('YYYY-MM-DD')
					};
					// this.$refs.invoiceForm.reset();
				} else if (this.isEditMode) {
					const {
						issued_at,
						due_at,
						document_number,
						order_number,
						category_ref,
						contact_ref,
						notes,
						status,
						currency_code,
						currency_rate,
						id,
						qbo_id,
						items_ref,
						qbo_term_ref,
						bill_to_address,
						bill_to_email,
						qbo_private_note,
						discount_ref,
						tax_code_ref,
						attachment
					} = this.formValues;
					const _items_ref = JSON.parse(items_ref) || [];
					const customer = JSON.parse(contact_ref);
					this.formData = {
						id,
						qbo_id,
						issued_at: moment(issued_at, 'YYYY-MM-DD').format('YYYY-MM-DD'),
						due_date: moment(due_at, 'YYYY-MM-DD').format('YYYY-MM-DD'),
						document_number,
						order_number,
						notes,
						category: category_ref,
						customer: JSON.stringify(customer),
						items: _items_ref.map((data) => ({
							item: data.item,
							quantity: data.quantity,
							price: data.price,
							total: data.total,
							description: data.description,
							qbo_taxable: data.qbo_taxable,
							tax_rates: data.qbo_taxable === 1 ? JSON.parse(tax_code_ref)?.tax_rates : [{}]
						})),
						qbo_term_ref,
						bill_to_email: bill_to_email || customer.email,
						qbo_private_note,
						bill_to_address,
						status,
						home_currency: currency_code,
						currency_rate,
						discount_ref: JSON.parse(discount_ref)
					};
					this.discountData = JSON.parse(discount_ref) || this.discountData;
					this.customerData = [JSON.parse(contact_ref)];
					if (this.isQBOEnabled) {
						this.fetchAttachments(id);
					}
					if (this.isQBOEnabled === 0) {
						this.attachedFiles = attachment;
					}
				}
			} else {
				this.formData = JSON.parse(JSON.stringify(this.defaultForm));
			}
		},

		discountData: {
			handler(value) {
				if (value) {
					if (value.discount > this.subTotal && value.discount_type === 'fixed') {
						this.$set(this.discountData, 'discount', this.subTotal);
					}

					if (value.discount_type === 'percentage') {
						if ((value.discount / 100) * this.subTotal > this.subTotal) {
							this.$set(this.discountData, 'discount', 100);
						}
					}
				}
			},
			deep: true
		}
	},

	computed: {
		...mapGetters('accounting', ['isQBOEnabled', 'homeCurrency']),
		...mapState('accounting', ['companyInformation']),
		/* isQBOEnabled() {
	return 0;
	}, */
		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		},
		customerDataComputed: {
			get() {
				return this.customerData.map(function({
					id,
					name,
					email,
					currency_code,
					qbo_realm_id,
					qbo_id,
					qbo_values,
					origin
				}) {
					return {
						text: name,
						value: JSON.stringify({
							id,
							name,
							email,
							currency_code,
							qbo_realm_id,
							qbo_id,
							qbo_values,
							origin
						})
					};
				});
			}
		},
		showDialog: {
			get() {
				return this.open;
			},
			set() {
				this.$emit('toggle');
			}
		},

		subTotal() {
			if (this.formData.items && this.formData.items.length) {
				return this.formData.items.reduce((c, n) => c + Number(n.price || 0) * Number(n.quantity || 0), 0);
			}
			return 0;
		},

		discount() {
			let discounted = this.discountData?.discount || 0;
			if (this.discountData.discount_type === 'percentage') {
				discounted = this.subTotal * (discounted / 100);
			}

			if (discounted > this.subTotal) {
				return this.subTotal;
			}
			return discounted;
		},

		taxTotal() {
			if (this.formData.items && this.formData.items.length) {
				return this.formData.items
					.filter((item) => item.qbo_taxable === 1)
					.reduce((c, item) => {
						const taxes = item.tax_rates.filter((rate) => rate.id);
						return (
							c +
							taxes.reduce((current, next) => {
								return (
									current +
									(Number(next.rate || 0) / 100) * (item.total - this.discountPerItem(item.total))
								);
							}, 0)
						);
					}, 0);
			}
			return 0;
		},

		taxItemLists() {
			if (this.formData.items && this.formData.items.length) {
				return this.formData.items
					.map((item) => {
						return item.tax_rates.filter((rate) => rate.id);
					})
					.flat();
			}
			return [];
		},

		itemTotal() {
			return this.subTotal - this.discount + this.taxTotal;
		},

		statusColor() {
			if (this.formData && this.formData.status) {
				switch (this.formData.status.toLowerCase()) {
					case 'paid':
						return { color: '#49af41', background: '#fff' };
					case 'overdue':
						return { color: '#e32d2d', background: '#fff2f2' };
					case 'partial':
						return { color: '#7cdab8', background: '#ecffed' };
					case 'open':
						return { color: '#0171a1', background: '#cbf1ff' };
					case 'draft':
						return { color: '#819fb2', background: '#efefef' };
					case 'sent':
						return { color: '#ff0000', background: '#efefef' };
					case 'cancelled':
						return { color: '#888888', background: '#efefef' };
				}
			}
			return { color: '#fff', background: '#fff' };
		},

		categoryDataList() {
			return this.categoryData.map((category) => ({
				text: category.name,
				value: JSON.stringify({
					id: category.id,
					name: category.name,
					type: category.type
				})
			}));
		},

		customerCurrency() {
			const customer = JSON.parse(this.formData.customer) || {};
			return customer?.currency_code || 'USD';
		},

		customerCurrencySymbol() {
			const currency = currencies.find((record) => record.code === this.customerCurrency);
			return currency?.symbolNative || '$';
		},

		taxCodeLists() {
			return this.taxCodes.map((record) => ({
				text: `${record.name}`,
				value: JSON.stringify(record)
			}));
		},

		taxRateLists() {
			if (this.isQBOEnabled === 1) {
				const taxcode = JSON.parse(this.formData.tax_code_ref || null);
				if (taxcode) {
					return taxcode?.tax_rates?.data || [];
				}
				return [];
			}
			return this.taxRates;
		},

		hasItemTaxable() {
			return this.formData.items.map((item) => item.qbo_taxable).filter((value) => value === 1).length > 0;
		}
	},

	methods: {
		moment,
		...mapActions('accounting', ['createInvoiceForm', 'getCustomersData', 'updateInvoice', 'getTaxRates']),
		...globalMethods,
		currencyFormat(amount) {
			return new Intl.NumberFormat('en-US', { style: 'currency', currency: this.customerCurrency }).format(
				amount
			);
		},

		numberToCurrency(number, currency) {
			return new Intl.NumberFormat('en-US', { style: 'currency', currency }).format(number);
		},

		async fetchTaxRates() {
			try {
				const data = await this.getTaxRates();
				this.taxRates = data?.data || [];
			} catch (error) {
				apiErrorMessage(error);
			}
		},

		async fetchTaxCodes() {
			try {
				const { data } = await AkauntingService.getTaxCodes();
				this.taxCodes = data?.data?.data || [];
				if (this.isEditMode) {
					const taxCode = this.formValues.tax_code;
					if (taxCode) {
						const tax = this.taxCodes.find((record) => record.id === taxCode.id);
						this.$set(this.formData, 'tax_code_ref', JSON.stringify(tax) || null);
					}
				}
			} catch (error) {
				apiErrorMessage(error);
			}
		},

		fetchTerms() {
			if (this.isQBOEnabled === 0) {
				return;
			}
			AkauntingService.getTerms()
				.then(({ data }) => {
					this.termsDataList = data.map((record) => ({
						text: record.Name,
						value: JSON.stringify(record)
					}));
				})
				.catch((error) => {
					console.log(error);
					this.termsDataList = [];
				});
		},

		computeItemTotalPerIndex(index) {
			const { price, quantity } = this.formData.items[index];
			this.formData.items[index].total = price * quantity;
		},

		onChangeItemTax(taxRateId, itemIndex, index) {
			const taxRate = this.taxRateLists.find((record) => record.id === taxRateId);
			this.formData.items[itemIndex].tax_rates[index] = JSON.parse(JSON.stringify(taxRate));
			if (index + 1 === this.formData.items[itemIndex].tax_rates.length) {
				this.formData.items[itemIndex].tax_rates.push({});
			}
			this.$forceUpdate();
		},

		onRemoveItemTax(itemIndex, index) {
			this.formData.items[itemIndex].tax_rates.splice(index, 1);
		},

		checkIsTaxItemDisabled(item, itemIndex) {
			const taxRates = this.formData.items[itemIndex].tax_rates.filter(
				(record) => typeof record.id !== 'undefined'
			);
			return !!taxRates.find((record) => record.id === item.id);
		},

		onClose() {
			this.$refs.invoiceForm.reset();
			this.fileList = [];
			this.showSelectItem = false;
			this.deletedAttachmendIds = [];
			this.attachedFiles = [];
			this.$emit('toggle');
		},

		async fetchCustomerList() {
			if (this.isCustomerDataLoading) {
				return;
			}
			this.isCustomerDataLoading = true;
			try {
				const { data } = await this.getCustomersData({ page: 1, limit: 10 });
				this.customerData = data || [];
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.isCustomerDataLoading = false;
			}
		},

		async searchCustomer() {
			const search = this.searchCustomerText;
			if (this.formData.customer) {
				const customer = JSON.parse(this.formData.customer);
				if (customer && customer.name === search) {
					return;
				}
			}
			if (this.isCustomerDataLoading || !search) {
				return;
			}
			this.isCustomerDataLoading = true;
			try {
				const { data } = await this.getCustomersData({ page: 1, limit: 10, search });
				this.customerData = data || [];
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.isCustomerDataLoading = false;
			}
		},

		onChangeCustomer(value) {
			const customer = JSON.parse(value);
			if (customer) {
				const formData = { ...this.formData };
				if (this.homeCurrency === this.customerCurrency) {
					formData.currency_rate = 1;
				}
				this.formData = {
					...formData,
					bill_to_email: customer.email
				};
			}
		},

		fetchIncomeCategory() {
			this.isCategoryDataLoading = true;
			AkauntingService.getAkauntingIncomeCategory()
				.then((response) => {
					this.categoryData = response?.data?.data?.data || [];
					this.isCategoryDataLoading = false;
				})
				.catch(() => {
					//Do nothing
					this.isCategoryDataLoading = false;
				});
		},
		async onSaveForm(is_send) {

			if (this.isSaving) {
				return;
			}

			this.formData.is_send = is_send ? 1 : 0;

			const validated = this.$refs.invoiceForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const { issued_at, due_date } = this.formData;
					const amount = this.subTotal + this.taxTotal;
					let discounted = 0;

					if (this.discountData.discount) {
						discounted = this.discountData.discount;
						if (
							this.discountData.discount > this.subTotal &&
							this.discountData.discount_type === 'fixed'
						) {
							discounted = this.subTotal;
						}

						if (this.discountData.discount_type === 'percentage') {
							if ((this.discountData.discount / 100) * this.subTotal > this.subTotal) {
								discounted = 100;
							}
						}
					}

					const form = {
						...this.formData,
						items: JSON.stringify(
							this.formData.items.map((item) => {
								return {
									...item,
									tax_rates: item.tax_rates.filter((rate) => rate.id)
								};
							}) || []
						),
						issued_at: moment(issued_at, 'YYYY-MM-DD').format('YYYY-MM-DD h:mm:ss'),
						due_at: moment(due_date, 'YYYY-MM-DD').format('YYYY-MM-DD h:mm:ss'),
						qbo_enabled: this.isQBOEnabled,
						amount,
						home_currency: this.homeCurrency,
						currency_rate:
							this.customerCurrency === this.homeCurrency ? 1 : this.formData.currency_rate,
						discount_ref: JSON.stringify({
							discount: discounted,
							discount_type: this.discountData.discount_type
						})
					};

					const _formData = new FormData();
					Object.keys(form).forEach((key) => {
						// console.log(key, form[key]);
						_formData.append(key, form[key]);
					});

					if (this.isEditMode) {
						_formData.append('_method', 'PATCH');
					}

					let attachmentKey = 'attachment';
					if (this.isQBOEnabled) {
						attachmentKey = 'attachmentv2';
					}

					for (let i = 0, count = this.fileList.length; i < count; i++) {
						_formData.append(`${attachmentKey}[${i}]`, this.fileList[i].file);
					}

					if (this.attachedFiles.length) {
						if (this.isQBOEnabled) {
							_formData.append(
								'attachments_for_delete',
								JSON.stringify(
									this.attachedFiles.filter((file) => this.deletedAttachmendIds.includes(file.id))
								)
							);
						} else {
							_formData.append(
								'uploaded_attachment',
								JSON.stringify(
									this.attachedFiles.filter(
										(file) => !this.deletedAttachmendIds.includes(file.id)
									)
								)
							);
						}
					}

					const data = this.isEditMode
						? await this.updateInvoice({ id: form.id, payload: _formData })
						: await this.createInvoiceForm(_formData);

					const message =
						data.message || this.isEditMode
							? 'Data was successfully updated.'
							: 'Data was successfully saved.';

					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };

					this.$refs.invoiceForm.reset();
					this.fileList = [];
					this.$emit('toggle', { created: true, message });
				} catch (error) {
					console.log(error);

					const { data } = error.response || { data: {} };

					// this.snackbarOption = {
					// 	icon: 'mdi-alert-circle',
					// 	color: 'error',
					// 	message: data.message || 'Could not save the data.'
					// };

					const message = data.message || 'Could not save the data.'
					this.notificationCustom(message)
				} finally {
					this.showSnackbar = true;
					this.isSaving = false;
				}
			}
		},

		onAddItem() {
			this.showSelectItem = true;
		},

		onItemChange(data = {}) {
			// const items = this.formData.items;
			const quantity = 1;
			const price = data.sale_price || 0;
			const description = data.description || '';
			const _item = {
				item: this.item,
				quantity,
				price,
				total: quantity * price,
				description,
				tax_rates: [{}],
				qbo_taxable: 0
			};
			//  this.$set(this.formData.items, index, item);
			this.formData.items.push(_item);
			this.showSelectItem = false;
			this.item = null;
		},

		onRemoveItem(index) {
			this.formData.items.splice(index, 1);
		},

		onFileBrowseChange() {
			const files = [];
			const fileNames = this.fileList.map(({ file }) => file.name).flat();
			for (let i = 0, len = this.$refs.fileAttachment.files.length; i < len; i++) {
				const file = this.$refs.fileAttachment.files[i];
				if (!fileNames.includes(file.name)) {
					files.push({
						file,
						id: Date.now() + i
					});
				}
			}
			this.fileList = [...this.fileList, ...files];
		},

		removeFile(i) {
			this.fileList.splice(i, 1);
		},

		dragover(event) {
			event.preventDefault();
			this.isBrowseFileHovered = true;
		},

		dragleave() {
			this.isBrowseFileHovered = false;
		},

		drop(event) {
			event.preventDefault();
			this.$refs.fileAttachment.files = event.dataTransfer.files;
			this.onFileBrowseChange();
			this.isBrowseFileHovered = false;
		},

		async getDEaccountsAndTypes() {
			try {
				const { data } = await AkauntingService.getDEaccountsAndTypes();
				const accounts = data?.data?.data?.accounts || {};
				const filteredKeys = ['Revenue'];
				const _data = [];
				Object.keys(accounts)
					.filter((key) => filteredKeys.includes(key))
					.forEach((key) => {
						_data.push({ header: key });
						Object.keys(accounts[key]).forEach((k) => {
							_data.push({ text: accounts[key][k], value: k });
						});
					});
				this.incomeAccountLists = _data;
			} catch (error) {
				apiErrorMessage(error);
			}
		},

		async fetchAttachments(id) {
			if (this.attachmentLoading) {
				return;
			}
			this.attachmentLoading = true;
			try {
				const { data } = await AkauntingService.getInvoiceAttachments(id);
				this.attachedFiles = data?.data || [];
				this.attachmentLoading = false;
			} catch (error) {
				apiErrorMessage(error);
				this.attachmentLoading = false;
			}
		},

		async onDownloadAttachment(file) {
			if (this.downloadingFileIds.includes(file.id)) {
				return;
			}
			this.downloadingFileIds.push(file.id);
			try {
				const { data, headers } = this.isQBOEnabled
					? await AkauntingService.downloadInvoiceAttachment(file.id)
					: await AkauntingService.downloadInvoiceAttachmentV2(file.id);
				var blob = new Blob([data], { type: headers['content-type'] });
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = `${file.filename}.${file.extension}`;
				link.click();
				link.remove();
				this.downloadingFileIds = this.downloadingFileIds.filter((fileId) => fileId !== file.id);
			} catch (error) {
				apiErrorMessage(error);
			}
		},

		onSelectAttachmentToDelete(file) {
			if (this.deletedAttachmendIds.includes(file.id)) {
				this.deletedAttachmendIds = this.deletedAttachmendIds.filter((id) => id !== file.id);
			} else {
				this.deletedAttachmendIds.push(file.id);
			}
		},

		onChangeTaxCode(value) {
			let taxcode = { data: [] };
			if (value) {
				const _taxcode = JSON.parse(value);
				taxcode.data = _taxcode.tax_rates.data;
			}
			this.formData = {
				...this.formData,
				items: this.formData.items.map((item) => ({
					...item,
					tax_rates:
						item.qbo_taxable === 1
							? taxcode.data.map((tax) => ({
									enabled: tax.enabled,
									id: tax.id,
									name: tax.name,
									qbo_agency_ref: tax.qbo_agency_ref,
									qbo_id: tax.qbo_id,
									qbo_sync_token: tax.qbo_sync_token,
									rate: tax.rate,
									tax_code_id: tax.tax_code_id,
									title: tax.title || tax.name,
									type: tax.type
								}))
							: [{}]
				}))
			};
		},

		onChangeTaxableItem(value) {
			if (this.isQBOEnabled === 1) {
				this.onChangeTaxCode(this.formData.tax_code_ref);
			} else {
				if (value === null) {
					this.formData = {
						...this.formData,
						items: this.formData.items.map((item) => ({
							...item,
							tax_rates: [{}]
						}))
					};
				}
			}
		},

		onClearDiscount() {
			this.discountData = {
				discount_type: 'fixed',
				discount: 0
			};
		},

		roundAmount(amount, decimal = 3) {
			return _.round(amount, decimal);
		},

		discountPerItem(itemAmount) {
			if (this.discountData.discount_type === 'fixed') {
				return _.round(this.discountData.discount / this.formData.items.length, 2);
			}

			if (this.discountData.discount_type === 'percentage') {
				return itemAmount * (this.discountData.discount / 100);
			}

			return 0;
		}
	}
};
</script>

<style lang="scss">
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';

.filter-items-wrapper  {
	padding: 16px !important;
	font-size: 14px;

	.v-card__text {
		padding: 0;

		.form-label {
			font-size: 10px !important;
			color: #819fb2 !important;
			font-family: "Inter-SemiBold", sans-serif !important;
			text-transform: uppercase;
			margin-bottom: 5px;
			letter-spacing: 0 !important;
			line-height: 18px;
		}
	}
}
</style>

<style lang="scss" scoped>
	$button-bg-color: #0171a1;
	$form-label: #819fb2;
	.form-label {
		color: $form-label;
	}
	.w-100 {
		width: 100%;
	}
	.display-none {
		display: none;
	}

	th {
		color: $form-label;
		font-weight: bold;
	}
	.btn-primary {
		background-color: $button-bg-color !important;
		color: #fff !important;
	}
	.border-dashed {
		border: 2px dashed #b4cfe0 !important;
		margin-top: 3px;
	}
	#conversion_rate_input {
		border: 1px solid $form-label;
		width: 50px;
	}

	.accounting-table {
		td {
			vertical-align: top;
		}
	}

	.discount-input {
		border: 1px solid $form-label !important;
		height: 34px;
		width: 80px;
		text-indent: 12px;
	}
	::v-deep {
		.v-dialog {
			.v-input__control {
				background: transparent !important;
			}

			.v-text-field--enclosed .v-input__prepend-inner {
				margin-top: 0 !important;
			}

			.v-chip {
				font-size: 12px;
				height: 30px;
				text-transform: capitalize !important;

				.v-chip__content {					
					font-family: 'Inter-Medium', sans-serif;
				}
			}

			.v-input {
				&.v-input--is-disabled {
					fieldset {
						background-color: #ebf2f5 !important;
						border: 1px solid #ebf2f5 !important;
					}
				}
			}
		}

		.v-select--is-menu-active {
			background: transparent !important;
		}

		fieldset {
			border: 1px solid #b3cfe0;
			font-size: 14px;
		}

		fieldset:focus {
			border: 1px solid #b3cfe0 !important;
			outline: 0;
		}

		.v-text-field__slot input::placeholder {
			color: #b4cfe0 !important;
		}
	}
</style>
