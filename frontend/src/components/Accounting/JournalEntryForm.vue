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
						{{ isEditMode ? 'Edit Journal Entry' + (formData.journal_number ? ': ' + formData.journal_number : '') : 'Create Journal Entry' }}
					</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose" :disabled="isSaving">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>

				<v-card-text class="px-0 pb-0">
					<v-form ref="journalEntryForm">
						<v-card class="pa-0 grey lighten-4" outlined tile :loading="isSaving">
							<v-row no-gutters>
								<v-col cols="12" sm="12" md="4" class="item-form-column pa-4">
									<v-card class="grey lighten-4" outlined tile>

										<label
											class="labelcolor--text text-uppercase form-label"
											for="formdata-date"
										>{{ 'Date' }}</label>
										<date-picker
											v-model="formData.paid_at"
											id="formdata-date"
											:rules="[(v) => !!v || 'Field required']"
										></date-picker>

										<label
											class="labelcolor--text text-uppercase form-label"
											for="formdata-number"
											>{{ 'Journal No.' }}</label
										>
										<v-text-field
											v-model="formData.journal_number"
											:label="'Enter Journal No.'"
											solo
											class="app-theme-input-border"
											flat
											required
											dense
											outlined
											id="formdata-number"
											:rules="[(v) => !!v || 'Field required']"
											hide-details="auto"
										></v-text-field>

										<label
											class="labelcolor--text text-uppercase form-label"
											for="formdata-reference"
											>{{ 'Reference Number' }}</label
										>
										<v-text-field
											v-model="formData.reference"
											:label="'Enter Reference'"
											solo
											class="app-theme-input-border"
											flat
											required
											dense
											id="formdata-reference"
											outlined
											hide-details="auto"
										></v-text-field>

										<label class="form-label text-uppercase" for="formdata-currency">{{'Currency'}}</label>
										<v-select
											v-model="formData.currency_code"
											id="formdata-currency"
											item-text="name"
											item-value="code"
											outlined
											dense
											:items="currencies"
											:rules="[(v) => !!v || 'Field is required']"
											hide-details="auto"
											placeholder="Select Currency"
											@change="onCurrencySelect"
										>
											<!-- <template v-slot:prepend-inner>
												<span class="primary--text">{{ currencySymbol }}</span>
											</template> -->
										</v-select>

										<label class="form-label text-uppercase form-label" for="formdata-basis">{{
											'Basis'
										}}</label>
										<v-select
											v-model="formData.basis"
											:items="basisList"
											:rules="[(v) => !!v || 'Field required']"
											id="formdata-basis"
											solo
											flat
											class="app-theme-input-border"
											dense
											outlined
											item-value="value"
											item-text="name"
											hide-details="auto"
											label="Select Basis"
										>
										</v-select>

										<label class="labelcolor--text text-uppercase form-label" for="formdata-description">{{
											'Description'
										}}</label>
										<v-textarea
											:rules="[(v) => !!v || 'Field required']"
											v-model="formData.description"
											solo
											:label="'Type your description here' + '...'"
											class="app-theme-input-border"
											flat
											id="formdata-description"
											rows="2"
											hide-details
											outlined
										></v-textarea>

										<!-- <v-spacer class="mt-2"></v-spacer> -->
									</v-card>
								</v-col>
								<v-col class="item-form-column white pa-4">
									<v-card class="" flat>
										<div v-if="!isMobile">
											<table
												class="mb-0 w-100 accounting-table"
												dense
											>
												<thead class="grey lighten-3">
													<tr>
														<th
															class="pl-3 text-left text-uppercase text-subtitle-2 th--text py-2"
															width="25%"
														>
															{{ 'Account' }}
														</th>
														<th
															class="text-left text-uppercase text-subtitle-2 th--text"
														>
															{{ 'Notes' }}
														</th>
														<th
															class="text-center text-uppercase text-subtitle-2 th--text"
															width="15%"
														>
															{{ 'Debit' }}
														</th>
														<th
															class="text-center text-uppercase text-subtitle-2 th--text"
															width="15%"
														>
															{{ 'Credit' }}
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
																{{ item.account_name }}
															</td>
															<td class="pr-2">
																<v-text-field
																	v-model="item.notes"
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
																	v-model.number="item.debit"
																	solo
																	flat
																	@input="computeItemTotalPerIndex(index)"
																	required
																	dense
																	type="number"
																	class="app-theme-input-border text-input-right"
																	hide-details
																	outlined
																></v-text-field>
															</td>
															<td class="px-0 pr-2">
																<v-text-field
																	v-model.number="item.credit"
																	@input="computeItemTotalPerIndex(index)"
																	solo
																	flat
																	required
																	dense
																	type="number"
																	class="app-theme-input-border text-input-right"
																	hide-details
																	outlined
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
														<tr :key="`item_index_${index}-border`">
															<td colspan="6" class="pt-3">
																<v-divider></v-divider>
															</td>
														</tr>
													</template>
													<template>
														<tr>
															<td>
																<div v-if="showSelectItem" class="mt-6 d-flex justify-space-between">
																	<v-select
																		v-model="item"
																		return-object
																		id="formdata-account_id"
																		outlined
																		dense
																		:items="deAccountList"
																		:rules="[(v) => !!v || 'Field is required']"
																		hide-details="auto"
																		placeholder="Select Account"
																		@change="onItemChange"
																	></v-select>
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
															</td>
															<td></td>
															<td></td>
															<td></td>
															<td></td>
														</tr>
													</template>
													<template>
														<tr v-if="formData.items.length">
															<td></td>
															<td>Total</td>
															<td class="px-0 pr-2">
																<v-text-field
																	v-model.number="totalDebit"
																	readonly
																	solo
																	flat
																	required
																	dense
																	type="number"
																	class="app-theme-input-border text-input-right"
																	hide-details
																	outlined
																></v-text-field>
															</td>
															<td class="px-0 pr-2">
																<v-text-field
																	v-model.number="totalCredit"
																	readonly
																	solo
																	flat
																	required
																	dense
																	type="number"
																	class="app-theme-input-border text-input-right"
																	hide-details
																	outlined
																></v-text-field>
															</td>
															<td></td>
														</tr>
													</template>

												</tbody>
											</table>
											<!-- <div v-if="showSelectItem" class="mt-6 d-flex justify-space-between">
												<v-select
													v-model="item"
													return-object
													id="formdata-account_id"
													outlined
													dense
													:items="deAccountList"
													:rules="[(v) => !!v || 'Field is required']"
													hide-details="auto"
													placeholder="Select Account"
													@change="onItemChange"
												></v-select>
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
											<v-divider /> -->
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
														<h4>{{ item.account_name }}</h4>
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
												<!--  -->
											</v-col>
											<v-col cols="12" sm="4" offset-sm="2" style="color: #4a4a4a;">
												<v-row v-if="isQBOEnabled === 1" class="mt-0">
													<v-col class="text-right" cols="8">
														<v-menu offset-y :close-on-content-click="false">
															<template v-slot:activator="{ on, attrs }">
																<a color="primary" dark v-bind="attrs" v-on="on">
																	Add Discount
																</a>
															</template>
															<v-card width="280" class="filter-items-wrapper">
																<v-card-text>
																	<div
																		class="d-flex justify-space-between mb-2 align-center"
																	>
																		<div class="labelcolor--text form-label">Discount</div>
																		<v-btn
																			small
																			class="labelcolor--text mt-2 text-capitalize btn-white"
																			depressed
																			outlined
																			text
																			@click="onClearDiscount"
																		>
																			Clear
																		</v-btn>
																	</div>
																	<div class="d-flex align-center">
																		<v-btn-toggle
																			v-model="discountData.discount_type"
																			mandatory
																			dense
																		>
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
																				dense
																				hide-details
																				outlined
																			/>
																		</v-sheet>
																		<span class="ml-2 labelcolor--text form-label mb-0"
																			>of Subtotal</span
																		>
																	</div>
																</v-card-text>
															</v-card>
														</v-menu>
													</v-col>
													<v-col class="text-right">-{{ currencyFormat(discount) }}</v-col>
												</v-row>
											</v-col>
										</v-row>
									</v-card>
								</v-col>
							</v-row>
						</v-card>
					</v-form>
				</v-card-text>

				<v-card-actions class="justify-start">
					<v-btn
						@click="onSaveForm"
						class="text-capitalize btn-blue"
						:loading="isSaving"
						v-if="!isEditMode"
						>{{ 'Save' }}</v-btn
					>
					<v-btn @click="onSaveForm" class="text-capitalize btn-blue" :loading="isSaving" v-if="isEditMode">{{
						'Update'
					}}</v-btn>
					<v-btn text class="text-capitalize btn-white" @click="onClose" outlined :disabled="isSaving">{{
						'Cancel'
					}}</v-btn>
					<v-spacer></v-spacer>
				</v-card-actions>
			</v-card>
			<v-snackbar timeout="5000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar>
		</v-dialog>
	</div>
</template>

<script>
	import moment from 'moment';
	import { mapActions, mapGetters, mapState } from 'vuex';
	import DatePicker from './DatePicker.vue';
	import ItemAsyncAutocomplete from './ItemAsyncAutocomplete.vue';
	import AkauntingService from '../../services/akaunting.service';
	import { apiErrorMessage } from '../../utils/globalMethods';
	import _ from 'lodash';

	import currencies from './data/currencies.json';

	export default {
		name: 'JournalEntryForm',
		components: {
			DatePicker,
			ItemAsyncAutocomplete
		},
		props: ['open', 'isEditMode', 'formValues'],
		data() {
			return {
				basisList : [
					{ name: 'Accrual', value: 'accrual' },
					{ name: 'Cash', value: 'cash' },
				],
				deAccountList: [],
				formData: {
					id: 0,
					journal_number: '',
					reference: '',
					currency: '',
					currency_code: '',
					currency_rate: 1,
					notes: '',
					description: '',
					paid_at: '',
					basis:'accrual',
					items: [],
					total_debit: 0,
					total_credit: 0,
				},
				item: null,
				defaultForm: {},
				isSaving: false,
				snackbarOption: {},
				showSnackbar: false,
				showSelectItem: false,
				selectedCurrency: '',
			};
		},

		created() {
			this.getDoubleEntries();
			this.getCurrencies();

			this.defaultForm = JSON.parse(JSON.stringify({ ...this.formData }));
		},

		watch: {
			open(isTrue) {
				if (isTrue) {
					if (!this.isEditMode) {
						const currentDate = this.moment(new Date(), 'YYYY-MM-DD');
						let formData = JSON.parse(JSON.stringify(this.formData));
						this.formData = {
							...formData,
							currency_code: this.homeCurrency,
							paid_at: currentDate.format('YYYY-MM-DD'),
						};
					} else if (this.isEditMode) {
						const value = this.formValues;
						const deList = this.deAccountList;
						let items = [];
						value.ledgers.map(function(l){
							let accountName = '';
							Object.keys(deList).map(function(key){
								if(deList[key].value && deList[key].value == l.account_id){
									accountName = deList[key].text;
								}
							});
							items.push({
								id: l.id,
								account_name: accountName,
								account_id: l.account_id,
								notes: l.notes,
								debit: l.debit,
								credit: l.credit,
							});
						});
						this.formData = {
							id: value.id,
							journal_number: value.journal_number,
							reference: value.reference,
							currency_code: value.currency_code,
							currency_rate: value.currency_rate,
							notes: null,
							description: value.description,
							paid_at: value.paid_at,
							basis: value.basis,
							items: items,
						};
					}
				} else {
					this.formData = JSON.parse(JSON.stringify(this.defaultForm));
				}
			},
		},

		computed: {
			...mapGetters('accounting', ['isQBOEnabled', 'homeCurrency']),
			...mapState('accounting', ['companyInformation','currencies']),

			currencyData(){
				return this.currencies;
			},

			currencySymbol: {
			// TODO: FIX GETTER
				get() {
					if (this.formData.currency) {
						const selectedCurrency = this.formData.currency;
						return this.currencies.find((currency) => currency.code === selectedCurrency.code)?.symbol;
					}
					return '';
				}
			},

			// currencyValue() {
			// 	if (this.formData.currency) {
			// 		const selectedCurrency = this.formData.currency;
			// 		const currency = this.currencies.find((currency) => currency.code === selectedCurrency);
			// 		if (currency) {
			// 			return JSON.stringify({ name: currency.name, code: currency.code });
			// 		}
			// 	}
			// 	return null;
			// },
			
			totalCredit:
			{
				get(){
					let credit = 0;
					this.formData.items.map(function(c){
						credit = credit + c.credit;
					});
					return credit;
				},
				set(){
					// 
				}
			},

			totalDebit:
			{
				get(){
					let debit = 0;
					this.formData.items.map(function(c){
						debit = debit + c.debit;
					});
					return debit;
				},
				set(){
					// 
				}
			},

			isMobile() {
				return this.$vuetify.breakpoint.mobile;
			},

			showDialog: {
				get() {
					return this.open;
				},
				set() {
					this.$emit('toggle');
				}
			},

			selectedCurrencySymbol() {
				const currency = currencies.find((record) => record.code === this.customerCurrency);
				return currency?.symbolNative || '$';
			},
		},

		methods: {
			moment,
			...mapActions('accounting', ['getCurrencies']),
			...mapActions('accounting', ['createJournalEntry','updateJournalEntry']),

			async getDoubleEntries() {
				try {
					const { data } = await AkauntingService.getCoaAccountsAndDefaults();
					const de = data.data.data;
					const options = de.accounts;
					const _data = [];
					Object.keys(options).forEach((key) => {
						_data.push({ header: key });
						Object.keys(options[key]).forEach((k) => {
							_data.push({ text: options[key][k], value: parseInt(k) });
						});
					});

					this.deAccountList = _data;
				} catch (error) {
					apiErrorMessage(error);
				}
			},

			onItemChange(data = {}) {
				// const items = this.formData.items;
				const _item = {
					account_name: data.text,
					account_id: data.value,
					notes: '',
					debit: 0,
					credit: 0,
				};
				//  this.$set(this.formData.items, index, item);
				this.formData.items.push(_item);
				this.showSelectItem = false;
				this.item = null;
			},

			currencyFormat(amount) {
				return new Intl.NumberFormat('en-US', { style: 'currency', currency: this.selectedCurrency }).format(
					amount
				);
			},

			numberToCurrency(number, currency) {
				return new Intl.NumberFormat('en-US', { style: 'currency', currency }).format(number);
			},

			computeItemTotalPerIndex() {
				let credit = 0;
				let debit = 0;
				this.formData.items.map(function(c){
					credit = credit + c.credit;
					debit = debit + c.debit;
				});
				this.totalCredit = credit;
				this.totalDebit = debit;
				this.formData.total_credit = credit;
				this.formData.total_debit = debit;
			},

			onClose() {
				this.showSelectItem = false;
				this.formData = null;
				this.$emit('toggle');
			},

			onCurrencySelect(data = {}){
				this.formData.currency_code = data;
				this.selectedCurrency = data;
			},

			async onSaveForm() {
				if (this.isSaving) {
					return;
				}
				const validated = this.$refs.journalEntryForm.validate();
				if (validated) {
					this.isSaving = true;
					try{
						const _formData = {
							...this.formData,
							items: JSON.stringify(
								this.formData.items.map((item) => {
									return {
										...item,
									};
								}) || []
							),
						}
						const data = this.isEditMode
							? await this.updateJournalEntry({ id: _formData.id, payload: _formData })
							: await this.createJournalEntry(_formData);

						const message =
							data.data || this.isEditMode
								? 'Data was successfully updated.'
								: 'Data was successfully created.';

						this.snackbarOption = {
							icon: 'mdi-check',
							color: 'success',
							message
						};
						this.$refs.journalEntryForm.reset();
						this.$emit('toggle', { created: true, message });
					} catch (error) {
						console.log(error);

						const { data } = error.response || { data: {} };

						this.snackbarOption = {
							icon: 'mdi-alert-circle',
							color: 'error',
							message: data.message || 'Could not save the data.'
						};
					} finally {
						this.showSnackbar = true;
						this.isSaving = false;
					}
				}
				// 	try {
				// 		const { issued_at, due_date } = this.formData;
				// 		const amount = this.subTotal;
				// 		let discounted = 0;

				// 		if (this.discountData.discount) {
				// 			discounted = this.discountData.discount;
				// 			if (
				// 				this.discountData.discount > this.subTotal &&
				// 				this.discountData.discount_type === 'fixed'
				// 			) {
				// 				discounted = this.subTotal;
				// 			}

				// 			if (this.discountData.discount_type === 'percentage') {
				// 				if ((this.discountData.discount / 100) * this.subTotal > this.subTotal) {
				// 					discounted = 100;
				// 				}
				// 			}
				// 		}

				// 		const form = {
				// 			...this.formData,
				// 			items: JSON.stringify(
				// 				this.formData.items.map((item) => {
				// 					return {
				// 						...item,
				// 						tax_rates: item.tax_rates.filter((rate) => rate.id)
				// 					};
				// 				}) || []
				// 			),
				// 			issued_at: moment(issued_at, 'YYYY-MM-DD').format('YYYY-MM-DD h:mm:ss'),
				// 			due_at: moment(due_date, 'YYYY-MM-DD').format('YYYY-MM-DD h:mm:ss'),
				// 			qbo_enabled: this.isQBOEnabled,
				// 			amount,
				// 			home_currency: this.homeCurrency,
				// 			currency_rate:
				// 				this.selectedCurrency === this.homeCurrency ? 1 : this.formData.currency_rate,
				// 			discount_ref: JSON.stringify({
				// 				discount: discounted,
				// 				discount_type: this.discountData.discount_type
				// 			})
				// 		};

				// 		const _formData = new FormData();
				// 		Object.keys(form).forEach((key) => {
				// 			// console.log(key, form[key]);
				// 			_formData.append(key, form[key]);
				// 		});

				// 		if (this.isEditMode) {
				// 			_formData.append('_method', 'PATCH');
				// 		}

				// 		let attachmentKey = 'attachment';
				// 		if (this.isQBOEnabled) {
				// 			attachmentKey = 'attachmentv2';
				// 		}

				// 		for (let i = 0, count = this.fileList.length; i < count; i++) {
				// 			_formData.append(`${attachmentKey}[${i}]`, this.fileList[i].file);
				// 		}

				// 		if (this.attachedFiles.length) {
				// 			if (this.isQBOEnabled) {
				// 				_formData.append(
				// 					'attachments_for_delete',
				// 					JSON.stringify(
				// 						this.attachedFiles.filter((file) => this.deletedAttachmendIds.includes(file.id))
				// 					)
				// 				);
				// 			} else {
				// 				_formData.append(
				// 					'uploaded_attachment',
				// 					JSON.stringify(
				// 						this.attachedFiles.filter(
				// 							(file) => !this.deletedAttachmendIds.includes(file.id)
				// 						)
				// 					)
				// 				);
				// 			}
				// 		}

				// 		const data = this.isEditMode
				// 			? await this.updateInvoice({ id: form.id, payload: _formData })
				// 			: await this.createInvoiceForm(_formData);

				// 		const message =
				// 			data.message || this.isEditMode
				// 				? 'Data was successfully updated.'
				// 				: 'Data was successfully saved.';

				// 		this.snackbarOption = {
				// 			icon: 'mdi-check',
				// 			color: 'success',
				// 			message
				// 		};

				// 		this.$refs.invoiceForm.reset();
				// 		this.fileList = [];
				// 		this.$emit('toggle', { created: true, message });
				// 	} catch (error) {
				// 		console.log(error);

				// 		const { data } = error.response || { data: {} };

				// 		this.snackbarOption = {
				// 			icon: 'mdi-alert-circle',
				// 			color: 'error',
				// 			message: data.message || 'Could not save the data.'
				// 		};
				// 	} finally {
				// 		this.showSnackbar = true;
				// 		this.isSaving = false;
				// 	}
				// }
			},

			onAddItem() {
				this.showSelectItem = true;
			},

			onRemoveItem(index) {
				this.formData.items.splice(index, 1);
			},

			async getDEaccounts() {
				try {
					const { data } = await AkauntingService.getDEaccounts();
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

			roundAmount(amount, decimal = 3) {
				return _.round(amount, decimal);
			},
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

		.text-input-right input{
			text-align: right !important;
		}
	}
</style>
