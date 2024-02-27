<template>
	<div class="text-center">
		<v-dialog
			v-model="showDialog"
			max-width="1600"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card :loading="isSaving">
				<v-card-title class="">
					<span class="headline">{{ isEditMode ? 'Edit Bill' : 'Add Bill' }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>

				<v-card-text class="px-0 pb-0">
					<!-- <v-container class="pa-0"> -->
					<v-form ref="billForm">
						<v-row no-gutters>
							<v-col cols="12" md="4" lg="4" class="item-form-column grey lighten-4 pa-4 pb-0 bg-grey-input">
								<v-card class="pa-0" color="transparent" outlined tile>
									<label class="form-label text-uppercase" for="formdata-bill-number">{{
										'Billing No.'
									}}</label>
									<v-text-field
										v-model="formData.document_number"
										:label="'Enter billing no.'"
										solo
										class="app-theme-input-border"
										outlined
										flat
										required
										dense
										id="formdata-bill-number"
										:rules="[(v) => !!v || 'Field required']"
										hide-details="auto"
										autocomplete="off"
									></v-text-field>
									<label class="form-label text-uppercase" for="formdata-order-number">{{
										'Order Number'
									}}</label>
									<v-text-field
										v-model="formData.order_number"
										:label="'Order Number'"
										solo
										class="app-theme-input-border"
										outlined
										flat
										required
										dense
										id="formdata-order-number"
										hide-details="auto"
										autocomplete="off"
									></v-text-field>

									<label class="form-label text-uppercase" for="formdata-vendor">{{
										'Vendor'
									}}</label>
									<v-autocomplete
										v-model="vendorId"
										:loading="isVendorDataLoading"
										:items="vendorDataList"
										:search-input.sync="searchVendorText"
										clearable
										dense
										solo
										flat
										outlined
										item-text="text"
										item-value="value"
										:rules="[(v) => !!v || 'Field required']"
										class="app-theme-input-border"
										@change="onChangeVendor"
										hide-details="auto"
										placeholder="Select Vendor"
										:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
									></v-autocomplete>

									<div v-if="homeCurrency !== vendorCurrency" class="mb-4">
										<label
											class="labelcolor--text text-uppercase"
											for="formdata-currency-rate"
											>{{ 'Currency Rate' }}</label
										>
										<v-text-field
											v-model="formData.currency_rate"
											:label="'invoice_currency_rate'"
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
												<span>{{ vendorCurrency }}</span>
											</template>
										</v-text-field>
									</div>

									<label class="form-label text-uppercase" for="formdata-category">{{
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
										:loading="isCategoryDataLoading"
										hide-details="auto"
										placeholder="Select Category"
										:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
									>
									</v-select>

									<div class="d-sm-flex justify-sm-space-between justify-end mb-sm-0 mb-2">
										<div class="mr-0 mr-sm-1">
											<label class="form-label text-uppercase" for="formdata-bill-date">{{
												'Bill Date'
											}}</label>
											<date-picker
												v-model="formData.issued_at"
												id="formdata-bill-date"
												:rules="[(v) => !!v || 'Field required']"
											></date-picker>
										</div>
										<div class="ml-0 ml-sm-1">
											<label class="form-label text-uppercase" for="formdata-due-date">{{
												'Due Date'
											}}</label>
											<date-picker
												v-model="formData.due_at"
												id="formdata-due-date"
												:rules="[(v) => !!v || 'Field required']"
											></date-picker>
										</div>
									</div>

									<label class="form-label text-uppercase" for="formdata-notes">{{
										'notes'
									}}</label>
									<v-textarea
										v-model="formData.notes"
										outlined
										solo
										:label="'Type your notes here' + '...'"
										class="app-theme-input-border"
										flat
										id="formdata-notes"
										rows="3"
										hide-details="auto"
									></v-textarea>
								</v-card>
							</v-col>
							<v-col class="item-form-column white pa-4 d-none_ d-sm-flex_">
								<v-card class="pa-0 pa-sm-2" flat>
									<div v-if="!isMobile">
										<!-- Category Item -->
										<div v-if="isQBOEnabled === 1">
											<table class="mb-0 w-100" dense v-if="categoryItems.length">
												<thead class="grey lighten-3">
													<tr>
														<th
															class="pl-3 text-left text-uppercase text-subtitle-2 th--text py-2"
															style="width: 25%;"
														>
															{{ 'category' }}
														</th>
														<th
															class="pl-3 text-left text-uppercase text-subtitle-2 th--text py-2"
														>
															{{ 'description' }}
														</th>
														<th
															class="text-center text-uppercase text-subtitle-2 th--text"
															style="width: 15%"
														>
															{{ 'amount' }} ({{ vendorCurrency }})
														</th>
														<th
															class="text-center text-uppercase text-subtitle-2 th--text"
															style="width: 15%"
														>
															{{ 'total' }}
														</th>
														<th width="5%" class="th--text">&nbsp;</th>
													</tr>
												</thead>
												<tbody>
													<template v-for="(item, index) in categoryItems">
														<tr :key="`category_item_index_${index}`">
															<td class="px-3 pt-0">
																<!-- <item-async-autocomplete
																		v-model="item.item"
																		@change="data => onCategoryItemChange(data, index)"
																		:is-category-item="true"
																	></item-async-autocomplete> -->
																<span>{{ item.item.name }}</span>
																<v-menu :close-on-content-click="false" offset-y>
																	<template v-slot:activator="{ on, attrs }">
																		<div>
																			<a
																				color="primary"
																				dark
																				v-bind="attrs"
																				v-on="on"
																			>
																				{{ 'Edit Expense Account' }}
																			</a>
																		</div>
																	</template>

																	<v-card width="300">
																		<v-card-text>
																			<p>
																				{{
																					'An expenses account is used for proper bookkeeping of your purchases and to keep your reports accurate.'
																				}}
																			</p>
																			<label
																				:for="`account-${item.item.id}`"
																				class="labelcolor--text text--capitalize"
																				>{{ 'Account' }}</label
																			>
																			<v-autocomplete
																				v-model="
																					item.item
																						.de_expense_account_value
																				"
																				:items="expenseAccountLists"
																				:id="`account-${item.item.id}`"
																				clearable
																				dense
																				outlined
																				:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
																			></v-autocomplete>
																		</v-card-text>
																	</v-card>
																</v-menu>
															</td>
															<td class="pt-2 pr-2">
																<v-textarea
																	v-model="item.description"
																	solo
																	class="app-theme-input-border"
																	flat
																	dense
																	row-height="1"
																	auto-grow
																	hide-details
																	outlined
																></v-textarea>
															</td>
															<td class="pt-2 px-0 pr-3">
																<v-text-field
																	v-model.number="item.price"
																	@input="computeCategoryItemTotalPerIndex(index)"
																	solo
																	flat
																	required
																	dense
																	type="number"
																	class="app-theme-input-border text-right"
																	hide-details
																	outlined
																>
																	<template slot="prepend-inner">{{
																		vendorCurrencySymbol
																	}}</template>
																</v-text-field>
															</td>
															<td class="pt-2 px-0 pr-3">
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
																></v-text-field>
															</td>
															<td>
																<v-btn
																	icon
																	color="red"
																	small
																	@click="onRemoveCategoryItem(index)"
																>
																	<v-icon>mdi-close</v-icon>
																</v-btn>
															</td>
														</tr>
													</template>
												</tbody>
											</table>
											<div
												v-if="showSelectAccountBasedItem"
												class="mt-4 d-flex justify-space-between"
											>
												<item-async-autocomplete
													v-model="accountBasedItem"
													@change="onCategoryItemChange"
													:is-category-item="true"
													:income="false"
												></item-async-autocomplete>
												<v-btn
													class="text-capitalize pt-2"
													text
													color="red"
													@click="showSelectAccountBasedItem = false"
													icon
												>
													<v-icon>mdi-close</v-icon>
												</v-btn>
											</div>
											<v-btn
												outlined
												small
												text
												@click="onAddCategoryItem"
												color="primary"
												class="mb-4 my-4 py-4 text-capitalize btn-white"
												width="150"
											>
												<v-icon size="18">mdi-plus</v-icon>
												{{ 'Add Category' }}
											</v-btn>
											<v-divider></v-divider>
										</div>

										<!-- Items -->
										<table class="mb-0 mt-4 w-100" dense v-if="formData.items.length">
											<thead class="grey lighten-3">
												<tr>
													<th
														class="pl-3 text-left text-uppercase text-subtitle-2 th--text py-2"
														style="width:25%;"
													>
														{{ 'Item' }}
													</th>
													<th
														class="pl-3 text-left text-uppercase text-subtitle-2 th--text py-2"
													>
														{{ 'Description' }}
													</th>
													<th
														class="text-center text-uppercase text-subtitle-2 th--text"
														style="width: 10%;"
													>
														{{ 'Quantity' }}
													</th>
													<th
														class="text-center text-uppercase text-subtitle-2 th--text"
														style="width:10%;"
													>
														{{ 'Price' }} ({{ vendorCurrency }})
													</th>
													<th
														class="text-center text-uppercase text-subtitle-2 th--text"
														style="width:10%;"
													>
														{{ 'Total' }}
													</th>
													<th class="th--text" style="width:5%;">&nbsp;</th>
												</tr>
											</thead>
											<tbody>
												<template v-for="(item, index) in formData.items">
													<tr :key="`item_index_${index}`">
														<td class="px-2 pt-0">
															<!-- <item-async-autocomplete
																	v-model="item.item"
																	@change="data => onItemChange(data, index)"
																></item-async-autocomplete> -->
															<span>{{ item.item.name }}</span>
															<v-menu :close-on-content-click="false" offset-y>
																<template v-slot:activator="{ on, attrs }">
																	<div>
																		<a
																			color="primary"
																			dark
																			v-bind="attrs"
																			v-on="on"
																		>
																			{{ 'Edit Expense Account' }}
																		</a>
																	</div>
																</template>

																<v-card width="300">
																	<v-card-text>
																		<p>
																			{{
																				'An expenses account is used for proper bookkeeping of your purchases and to keep your reports accurate.'
																			}}
																		</p>
																		<label
																			:for="`account-${item.item.id}`"
																			class="labelcolor--text text--capitalize"
																			>{{ 'Account' }}</label
																		>
																		<v-autocomplete
																			v-model="
																				item.item.de_expense_account_value
																			"
																			:items="expenseAccountLists"
																			:id="`account-${item.item.id}`"
																			clearable
																			dense
																			outlined
																			:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
																		></v-autocomplete>
																	</v-card-text>
																</v-card>
															</v-menu>
														</td>
														<td class="pt-2 pr-2">
															<v-text-field
																v-model="item.description"
																solo
																class="app-theme-input-border"
																flat
																dense
																auto-grow
																hide-details
																outlined
															></v-text-field>
														</td>
														<td class="pt-2 px-0 pr-3">
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
														<td class="pt-2 px-0 pr-3">
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
															>
																<template slot="prepend-inner">{{
																	vendorCurrencySymbol
																}}</template>
															</v-text-field>
														</td>
														<td class="pt-2 px-0 pr-3">
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
															></v-text-field>
														</td>
														<td>
															<v-btn
																icon
																color="red"
																small
																@click="onRemoveItem(index)"
															>
																<v-icon>mdi-close</v-icon>
															</v-btn>
														</td>
													</tr>
												</template>
											</tbody>
										</table>
										<div v-if="showSelectItem" class="mt-4 d-flex justify-space-between">
											<item-async-autocomplete
												v-model="item"
												@change="onItemChange"
												:income="false"
												:isCategoryItem="false"
											/>
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
											small
											text
											outlined
											@click="onAddItem"
											color="primary"
											class="my-4 py-4 text-capitalize btn-white"
											width="120"
										>
											<v-icon size="18">mdi-plus</v-icon>
											{{ 'Add Item' }}
										</v-btn>
										<v-divider></v-divider>
									</div>

									<div v-if="isMobile">
										<v-sheet
											color="transparent"
											class="d-flex flex-column d-md-none d-lg-none d-xl-none mb-2"
											v-if="isQBOEnabled === 1"
										>
											<v-subheader class="text-h6 labelcolor--text">{{
												'category'
											}}</v-subheader>
											<v-card
												outlined
												flat
												class="mx-2 mb-4"
												v-for="(item, index) in categoryItems"
												:key="`category_item_index_${index}`"
											>
												<v-toolbar flat>
													<div>
														<span>{{ item.item.name }}</span>
														<v-menu :close-on-content-click="false" offset-y>
															<template v-slot:activator="{ on, attrs }">
																<div>
																	<a
																		color="primary"
																		dark
																		v-bind="attrs"
																		v-on="on"
																	>
																		{{ 'Edit Expense Account' }}
																	</a>
																</div>
															</template>

															<v-card width="300">
																<v-card-text>
																	<p>
																		{{
																			'An expenses account is used for proper bookkeeping of your purchases and to keep your reports accurate.'
																		}}
																	</p>
																	<label
																		:for="`account-${item.item.id}`"
																		class="labelcolor--text text-capitalize"
																		>{{ 'Account' }}</label
																	>
																	<v-autocomplete
																		v-model="item.item.de_expense_account_value"
																		:items="expenseAccountLists"
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
													<v-spacer />
													<v-btn
														small
														icon
														color="red"
														@click="onRemoveCategoryItem(index)"
													>
														<v-icon>mdi-delete</v-icon>
													</v-btn>
												</v-toolbar>
												<div class="d-flex px-2 py-1">
													<div class="mx-1">
														<label
															class="form-label text-uppercase"
															for="formdata-quantity"
															>{{ 'Amount' }} ({{ vendorCurrency }})</label
														>
														<v-text-field
															v-model.number="item.price"
															@input="computeCategoryItemTotalPerIndex(index)"
															solo
															flat
															required
															dense
															type="number"
															class="app-theme-input-border text-right"
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
											</v-card>
											<div
												v-if="showSelectAccountBasedItem"
												class="mt-4 d-flex justify-space-between"
											>
												<item-async-autocomplete
													v-model="accountBasedItem"
													@change="onCategoryItemChange"
													:is-category-item="true"
													:income="false"
												></item-async-autocomplete>
												<v-btn
													class="text-capitalize pt-2"
													text
													color="red"
													@click="showSelectAccountBasedItem = false"
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
													class="text-capitalize btn-white"
													@click="onAddCategoryItem"
												>
													{{ 'Add Category' }}
												</v-btn>
											</div>
										</v-sheet>

										<v-sheet
											color="transparent"
											class="d-flex flex-column d-md-none d-lg-none d-xl-none mb-2"
										>
											<v-subheader class="text-h6 labelcolor--text">{{
												'Items'
											}}</v-subheader>
											<v-card
												outlined
												flat
												class="mx-2 mb-4"
												v-for="(item, index) in formData.items"
												:key="`item_index_${index}`"
											>
												<v-toolbar flat>
													<div>
														<span>{{ item.item.name }}</span>
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
																			{{ 'Edit Expense Account' }}
																		</a>
																	</div>
																</template>

																<v-card width="300">
																	<v-card-text>
																		<p>
																			{{
																				'An expenses account is used for proper bookkeeping of your purchases and to keep your reports accurate.'
																			}}
																		</p>
																		<label
																			:for="`account-${item.item.id}`"
																			class="text--capitalize labelcolor--text"
																			>{{ 'Account' }}</label
																		>
																		<v-autocomplete
																			v-model="
																				item.item.de_expense_account_value
																			"
																			:items="expenseAccountLists"
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
															>{{ 'quantity' }}</label
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
														/>
													</div>
													<div class="mx-1">
														<label
															class="form-label text-uppercase"
															for="formdata-quantity"
															>{{ 'price' }} ({{ vendorCurrency }})</label
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
														/>
													</div>
													<div>
														<label
															class="form-label text-uppercase"
															for="formdata-quantity"
															>{{ 'total' }}</label
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
												></v-textarea>
											</v-card>
											<div v-if="showSelectItem" class="mt-4 d-flex justify-space-between">
												<item-async-autocomplete
													v-model="item"
													@change="onItemChange"
													:income="false"
													:isCategoryItem="false"
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
									</div>

									<v-row class="mt-4">
										<v-col cols="12" sm="7"> </v-col>
										<v-col>
											<v-row class="py-2">
												<v-col class="text-right">{{ 'Subtotal' }}</v-col>
												<v-col class="text-right">{{
													currencyFormat(subTotal, vendorCurrency)
												}}</v-col>
											</v-row>
											<!-- <hr>
											<v-row>
												<v-col class="text-right">{{ ('tax') }}</v-col>
												<v-col class="text-right">${{ taxTotal }}</v-col>
											</v-row> -->
											<v-divider></v-divider>
											<v-row class="py-2">
												<v-col class="text-right font-weight-bold">{{ 'Total' }}</v-col>
												<v-col class="text-right font-weight-bold">{{
													currencyFormat(subTotal, vendorCurrency)
												}}</v-col>
											</v-row>
											<v-row v-if="homeCurrency !== vendorCurrency">
												<v-col cols="12" sm="2"></v-col>
												<v-col class="text-subtitle-1">
													<p>{{ 'currency_conversion' }}:</p>
													<p class="text-right">
														{{
															currencyFormat(
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
					</v-form>
					<!-- </v-container> -->
				</v-card-text>

				<v-divider></v-divider>

				<v-card-actions class="justify-start">
					<v-btn
						@click="onSaveForm"
						class="text-capitalize btn-primary btn-blue"
						v-if="!isEditMode"
						:disabled="isSaving"
						:loading="isSaving"
						>{{ 'save' }}</v-btn
					>
					<v-btn
						@click="onSaveForm"
						class="text-capitalize btn-primary btn-blue"
						v-if="isEditMode"
						:disabled="isSaving"
						:loading="isSaving"
						>{{ 'update' }}</v-btn
					>
					<v-btn text outlined class="text-capitalize primary--text btn-white" @click="onClose" :disabled="isSaving">{{
						'cancel'
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
import { mapActions, mapGetters } from 'vuex';
import DatePicker from './DatePicker.vue';
import AkauntingService from '../../services/akaunting.service';
import { apiErrorMessage, debounce } from '../../utils/globalMethods';
import ItemAsyncAutocomplete from './ItemAsyncAutocomplete.vue';

import currencyJson from './data/currencies.json';

import accountingMixin from './mixin';
import globalMethods from '../../utils/globalMethods'

export default {
	name: 'BillingForm',
	components: {
		DatePicker,
		ItemAsyncAutocomplete
	},
	props: ['open', 'formValues', 'isEditMode'],
	mixins: [accountingMixin],
	data() {
		return {
			formData: {
				document_number: '',
				order_number: '',
				vendor: null,
				currency_rate: 1,
				home_currency: null,
				category: null,
				issued_at: null,
				issued_at_time: '',
				due_at: null,
				due_at_time: '',
				notes: '',
				items: []
			},
			defaultFields: null,
			categoryItems: [],
			billDateMenu: false,
			billDueDateMenu: false,
			termsData: [
				{
					value: 1,
					text: '1 Year'
				}
			],
			taxData: [],
			isCategoryDataLoading: true,
			categoryData: [],
			isVendorDataLoading: false,
			vendorData: [],
			isSaving: false,
			snackbarOption: {},
			showSnackbar: false,
			billStatusList: ['Received', 'Draft'],
			searchVendorText: '',
			menu: false,
			defaultItems: {
				item: {
					id: 0,
					name: '',
					de_expense_account_value: {},
					qbo_item_id: 0,
					qbo_item_type: ''
				},
				description: null,
				quantity: null,
				price: null
			},
			showSelectItem: false,
			item: null,
			accountBasedItem: null,
			showSelectAccountBasedItem: false,
			vendorId: null,
			expenseAccountLists: []
		};
	},

	created() {
		this.defaultFields = JSON.parse(JSON.stringify(this.formData));
		this.fetchExpenseCategory();
		// this.fetchVendorList();
		// this.getDEaccounts();
	},

	watch: {
		formValues(values) {
			if (values && this.isEditMode) {
				const vendor = values.contact_ref;
				const issued = this.moment(values.issued_at, 'YYYY-MM-DD');
				const due = this.moment(values.due_at, 'YYYY-MM-DD');
				const items_ref = JSON.parse(values.items_ref);
				const items = items_ref
					.filter((data) => (data.item.qbo_item_type || '').toLowerCase() !== 'account')
					.map((data) => ({
						item: data.item,
						description: data.description,
						price: data.price,
						quantity: data.quantity,
						total: data.total
					}));
				this.formData = {
					id: values.id,
					document_number: values.document_number,
					order_number: values.order_number,
					vendor,
					category: values.category_ref,
					issued_at: issued.format('YYYY-MM-DD'),
					issued_at_time: issued.format('HH:mm'),
					due_at: due.format('YYYY-MM-DD'),
					due_at_time: due.format('HH:mm'),
					notes: values.notes,
					items,
					qbo_id: values.qbo_id,
					status: values.status,
					currency_rate: values.currency_rate,
					home_currency: values.currency_code
				};
				this.categoryItems = items_ref
					.filter((data) => (data.item.qbo_item_type || '').toLowerCase() === 'account')
					.map((data) => ({
						item: data.item,
						description: data.description,
						price: data.price,
						quantity: data.quantity,
						total: data.total
					}));
				const _vendor = JSON.parse(vendor);
				this.vendorId = _vendor.id;
				this.vendorData = [_vendor];
				this.isVendorDataLoading = false;
				// this.searchVendorText = _vendor.name;
			}
		},

		searchVendorText: debounce(function() {
			this.searchVendors();
		}, 1000),

		open(isOpen) {
			if (isOpen) {
				this.getDEaccountsAndTypes();
			}
		},

		showDialog(isTrue) {
			if (isTrue && !this.isEditMode) {
				const issued = this.moment(new Date(), 'YYYY-MM-DD');
				const due = this.moment(new Date(), 'YYYY-MM-DD');
				this.formData = {
					...this.defaultFields,
					issued_at: issued.format('YYYY-MM-DD'),
					issued_at_time: issued.format('HH:mm'),
					due_at: due.format('YYYY-MM-DD'),
					due_at_time: due.format('HH:mm'),
					items: []
				};
				this.categoryItems = [];
				this.fetchVendorList();
			}
		}
	},

	computed: {
		...mapGetters('accounting', ['isQBOEnabled', 'homeCurrency']),
		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		},

		defaultCategoryItems() {
			return {
				item: {
					id: 0,
					name: '',
					de_expense_account_value: {},
					qbo_item_id: 0,
					qbo_item_type: ''
				},
				description: null,
				quantity: 1,
				price: null,
				total: 0
			};
		},

		categoryDataList: {
			get() {
				return this.categoryData.map((category) => ({
					text: category.name,
					value: JSON.stringify({
						id: category.id,
						name: category.name,
						type: category.type
					})
				}));
			}
		},

		vendorDataList: {
			get() {
				return this.vendorData.map((vendor) => ({
					text: vendor.name,
					value: vendor.id
				}));
			}
		},

		formattedVendorValue() {
			if (this.vendorId) {
				const vendor = this.vendorData.find((record) => record.id === this.vendorId);
				return JSON.stringify({
					id: vendor.id,
					name: vendor.name,
					email: vendor.email,
					currency_code: vendor.currency_code,
					qbo_realm_id: vendor.qbo_realm_id,
					qbo_id: vendor.qbo_id,
					qbo_values: vendor.qbo_values,
					origin: vendor.origin
				});
			}
			return null;
		},

		showDialog: {
			get() {
				return this.open;
			},
			set(value) {
				this.$emit('toggle', value);
			}
		},

		vendorCurrency() {
			const vendor = JSON.parse(this.formData.vendor) || {};
			return vendor?.currency_code || 'USD';
		},

		vendorCurrencySymbol() {
			const symbol = currencyJson.find((data) => data.code === this.vendorCurrency) || {};
			return symbol?.symbolNative || '$';
		},

		itemTotal() {
			return this.subTotal + this.taxTotal;
		},

		subTotal() {
			let itemTotal = 0;
			let categoryItemTotal = 0;
			if (this.formData.items && this.formData.items.length) {
				itemTotal = this.formData.items
					.filter(({ item }) => item && item.name)
					.reduce((c, n) => c + Number(n.price || 0) * Number(n.quantity || 0), 0);
			}
			if (this.categoryItems && this.categoryItems.length) {
				categoryItemTotal = this.categoryItems
					.filter(({ item }) => item && item.name)
					.reduce((c, n) => c + Number(n.price || 0) * Number(n.quantity || 0), 0);
			}
			return itemTotal + categoryItemTotal;
		},

		taxTotal() {
			return 0;
		}

		/* expenseAccountLists() {
		return Object.keys(this.expenseAccountData).map(key => ({text: this.expenseAccountData[key], value: JSON.stringify({[key]: this.expenseAccountData[key]})}));
	}, */
	},

	methods: {
		...mapActions('accounting', [
			'getAkauntingCategoryExpense',
			'createBillingForm',
			'getItemsData',
			'updateBillForm'
		]),
		moment,
		...globalMethods,

		convertDate(dateStr) {
			return dateStr ? this.moment(dateStr, 'YYYY-MM-DD').format('MMM DD, YYYY') : '';
		},

		computeItemTotalPerIndex(index) {
			const { price, quantity } = this.formData.items[index];
			this.formData.items[index].total = price * quantity;
		},

		computeCategoryItemTotalPerIndex(index) {
			const { price, quantity } = this.categoryItems[index];
			this.categoryItems[index].total = price * quantity;
		},

		onClose() {
			this.formData = {
				...this.defaultFields,
				items: [this.defaultItems],
				issued_at: null,
				due_at: null
			};
			this.$refs.billForm.resetValidation();
			this.$emit('toggle');
		},

		onChangeVendor(value) {
			const vendor = JSON.parse(value);
			if (vendor) {
				if (this.homeCurrency === this.vendorCurrency) {
					this.formData = {
						...this.formData,
						currency_rate: 1
					};
				}
			}
		},

		async onSaveForm() {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.billForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const items = this.formData.items || [];
					const categoryItems = this.categoryItems || [];

					const _items = [...items, ...categoryItems].filter(({ item }) => item.name);

					const form = {
						...this.formData,
						items: JSON.stringify(_items),
						issued_at: `${this.formData.issued_at} ${
							this.formData.issued_at_time ? this.formData.issued_at_time + ':00' : '00:00:00'
						}`,
						due_at: `${this.formData.due_at} ${
							this.formData.due_at_time ? this.formData.due_at_time + ':00' : '00:00:00'
						}`,
						qbo_enabled: this.isQBOEnabled,
						amount: this.subTotal,
						home_currency: this.homeCurrency,
						vendor: this.formattedVendorValue
					};

					const data = this.isEditMode
						? await this.updateBillForm(form)
						: await this.createBillingForm(form);

					const message = data.message || 'Data was successfully saved.';

					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };

					this.$refs.billForm.reset();
					this.formData = {
						...this.defaultFields,
						items: [this.defaultItems]
					};
					this.categoryItems = [JSON.parse(JSON.stringify(this.defaultCategoryItems))];
					this.$emit('toggle', { created: true, message });
				} catch (error) {
					const { data } = error.response || { data: {} };

					// this.snackbarOption = {
					// 	icon: 'mdi-alert-circle',
					// 	color: 'error',
					// 	message: data.message || 'Could not save the data.'
					// };

					const message =  data.message || 'Could not save the data.'
					this.notificationCustom(message)
				} finally {
					this.showSnackbar = true;
					this.isSaving = false;
				}
			}
		},

		onAddItem() {
			/* this.formData.items.push({
			item: {
				id: 0,
				name: '',
				de_expense_account_value: {},
				qbo_item_id: 0,
				qbo_item_type: '',
			},
			description: '',
			quantity: 1,
			price: 0.00,
			total: 0.00, //price * quantity
		}); */
			this.showSelectItem = true;
		},

		onRemoveItem(index) {
			this.formData.items.splice(index, 1);
		},

		onItemChange(data = {}) {
			const quantity = 1;
			const price = data.purchase_price || 0;
			const description = data.description || '';
			const _item = {
				item: this.item,
				quantity,
				price,
				total: quantity * price,
				description
			};
			// this.$set(this.formData.items, index, item);
			this.formData.items.push(_item);
			this.showSelectItem = false;
			this.item = null;
		},

		onAddCategoryItem() {
			// this.categoryItems.push(JSON.parse(JSON.stringify(this.defaultCategoryItems)));
			this.showSelectAccountBasedItem = true;
		},

		onRemoveCategoryItem(index) {
			this.categoryItems.splice(index, 1);
		},

		onCategoryItemChange(data = {}) {
			// const items = JSON.parse(JSON.stringify(this.categoryItems));
			// let item = items[index];
			const quantity = 1;
			const price = data.sale_price || 0;
			const description = data.description || '';
			const _item = {
				item: this.accountBasedItem,
				quantity,
				price,
				total: quantity * price,
				description
			};
			// this.$set(this.categoryItems, index, item);
			this.categoryItems.push(_item);
			this.showSelectAccountBasedItem = false;
			this.accountBasedItem = null;
		},

		fetchExpenseCategory() {
			this.getAkauntingCategoryExpense()
				.then((response) => {
					this.categoryData = response.data || [];
					this.isCategoryDataLoading = false;
				})
				.catch(() => {
					//Do nothing
					this.isCategoryDataLoading = false;
				});
		},

		fetchVendorList() {
			this.isVendorDataLoading = true;
			AkauntingService.searchVendors({ page: 1, limit: 10 })
				.then(({ data }) => {
					this.vendorData = data.data.data || [];
					this.isVendorDataLoading = false;
				})
				.catch(() => {
					//Do nothing
					this.isVendorDataLoading = false;
				});
		},

		async searchVendors() {
			const search = this.searchVendorText;
			if (this.vendorId) {
				const vendor = JSON.parse(this.formattedVendorValue);
				if (vendor && vendor.name === search) {
					return;
				}
			}
			if (this.isVendorDataLoading || !search) {
				return;
			}
			this.isVendorDataLoading = true;
			try {
				const { data } = await AkauntingService.searchVendors({ limit: 20, page: 1, search });
				this.vendorData = data.data.data || [];
				this.isVendorDataLoading = false;
			} catch (error) {
				// nevermind the error
				this.isVendorDataLoading = false;
			}
		},

		async getDEaccountsAndTypes() {
			try {
				const { data } = await AkauntingService.getDEaccountsAndTypes();
				const accounts = data?.data?.data?.accounts || {};
				const filteredKeys = ['Direct Costs', 'Expense', 'Inventory'];
				const _data = [];
				Object.keys(accounts)
					.filter((key) => filteredKeys.includes(key))
					.forEach((key) => {
						_data.push({ header: key });
						Object.keys(accounts[key]).forEach((k) => {
							_data.push({ text: accounts[key][k], value: k });
						});
					});
				this.expenseAccountLists = _data;
			} catch (error) {
				apiErrorMessage(error);
			}
		}
	}
};
</script>

<style lang="scss">
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';
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

	hr {
		border-color: #ebf1f5;
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
		border: 1px dashed $form-label !important;
	}
	.border-bottom {
		border-bottom: 1px solid $form-label;
	}

	#conversion_rate_input {
		border: 1px solid $form-label;
		width: 50px;
	}

	::v-deep {
		.v-dialog {
			.v-input__control {
				background: transparent !important;
			}

			.v-text-field--enclosed .v-input__prepend-inner {
				margin-top: 0 !important;
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
