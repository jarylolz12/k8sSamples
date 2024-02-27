<template>
	<v-sheet>
		<v-dialog
			v-model="showDialog"
			max-width="980"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card :loading="isSaving">
				<v-card-title>
					<span class="headline">{{ isEditMode ? 'Edit Invoice Payment' : 'Add Invoice Payment' }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				
				<v-card-text class="item-form-column">
					<v-form ref="paymentForm">
						<v-row>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-number">Number</label>
								<v-text-field
									v-model="formData.number"
									id="formdata-number"
									solo
									flat
									required
									dense
									outlined
									:rules="[(v) => !!v || 'Field is required']"
									hide-details="auto"
									placeholder="Enter number"
								></v-text-field>
							</v-col>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-contact_id">Customer</label>
								<v-autocomplete
									id="formdata-customer"
									v-model="formData.contact_id"
									:loading="isCustomerDataLoading"
									:items="customerLists"
									:search-input.sync="searchCustomerText"
									clearable
									dense
									solo
									flat
									outlined
									item-text="name"
									item-value="id"
									:rules="[(v) => !!v || 'Field required']"
									@change="onChangeCustomer"
									hide-details="auto"
									placeholder="Select Customer"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								></v-autocomplete>
							</v-col>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-trans-date">Date</label>
								<date-picker
									id="formdata-trans-date"
									v-model="formData.paid_at"
									:rules="[(v) => !!v || 'Field is required']"
								/>
							</v-col>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-amount">Amount</label>
								<v-text-field
									id="formdata-customer"
									solo
									flat
									required
									dense
									outlined
									readonly
									prepend-inner-icon="mdi-cash"
									:value="invoiceTotalAmount"
									:rules="[(v) => !!v || 'Field is required']"
									hide-details="auto"
								></v-text-field>
									<!-- disabled -->
							</v-col>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-account_id"
									>Debit Account</label
								>
								<v-select
									v-model="formData.account_id"
									id="formdata-account_id"
									outlined
									dense
									:items="debitList"
									:rules="[(v) => !!v || 'Field is required']"
									hide-details="auto"
									placeholder="Select debit account"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								></v-select>
							</v-col>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-de_account_id"
									>Credit Account</label
								>
								<v-autocomplete
									v-model="formData.de_account_id"
									id="formdata-de_account_id"
									outlined
									dense
									:items="creditList"
									:rules="[(v) => !!v || 'Field is required']"
									hide-details="auto"
									placeholder="Select credit account"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								></v-autocomplete>
							</v-col>
							<v-col cols="12" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-description"
									>Description</label
								>
								<v-textarea
									v-model="formData.description"
									id="formdata-description"
									rows="2"
									outlined
									hide-details="auto"
									placeholder="Type description..."
								></v-textarea>
							</v-col>
							<v-col cols="12" sm="6">
								<label class="form-label text-uppercase" for="formdata-category_id">Category</label>
								<v-select
									v-model="formData.category_id"
									id="formdata-category_id"
									outlined
									dense
									:items="categoryList"
									:rules="[(v) => !!v || 'Field is required']"
									hide-details="auto"
									placeholder="Select category"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								></v-select>
							</v-col>
							<v-col cols="12" sm="6">
								<label class="form-label text-uppercase" for="formdata-payment_method"
									>Payment Method</label
								>
								<v-select
									v-model="formData.payment_method"
									id="formdata-payment_method"
									outlined
									dense
									:items="paymentMethodList"
									:rules="[(v) => !!v || 'Field is required']"
									hide-details="auto"
									placeholder="Select payment method"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								></v-select>
							</v-col>
						</v-row>
						<v-sheet height="4">
							<v-progress-linear v-if="isInvoiceLoading" value="15" indeterminate></v-progress-linear>
						</v-sheet>
						<h3 v-if="invoices.length > 0" >Outstanding Transactions</h3>
						<table v-if="invoices.length > 0" class="table mt-4" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th class="text-start py-2">No.</th>
									<th class="text-start">Description</th>
									<th class="text-start">Due Date</th>
									<th class="text-end">Amount</th>
									<th class="text-end">Balance</th>
									<th class="text-end">Payment Amount</th>
								</tr>
							</thead>
							<tbody>
								<template v-for="(invoice, i) in invoices">
									<tr :key="invoice.id">
										<td class="text-start">{{ i + 1 }}</td>
										<td class="text-start">
											<v-text-field
												v-model="invoice.description"
												class=""
												dense
												outlined
												hide-details="auto"
											/>
										</td>
										<td class="text-start">
											{{ formatDate(invoice.due_at) }}
										</td>
										<td class="text-end">
											{{ invoice.original_amount_formatted }}
										</td>
										<td class="text-end">
											{{ invoice.open_balance_formatted }}
										</td>
										<td style="width: 140px" class="text-end">
											<v-text-field
												v-model.number="invoice.amount"
												type="number"
												class=""
												dense
												outlined
												hide-details="auto"
												:rules="
													invoice.amount
														? [
																(v) =>
																	v <= invoice.open_balance ||
																	'A maximum of ' + invoice.open_balance
														]
														: []
												"
											/>
										</td>
									</tr>
								</template>
							</tbody>
						</table>
					</v-form>
				</v-card-text>							

				<v-card-actions class="justify-start">
					<v-btn v-if="!isEditMode" @click="onSaveForm" class="btn-blue" :loading="isSaving" :disabled="isSaving">Save</v-btn>
					<v-btn v-else-if="isEditMode" @click="onSaveForm" class="btn-blue" :loading="isSaving" :disabled="isSaving">Update</v-btn>
					<v-btn outlined text class="text-capitalize primary--text btn-white" @click="onClose" :disabled="isSaving">
						Cancel
					</v-btn>
				</v-card-actions>
			</v-card>
			<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar> -->
		</v-dialog>
	</v-sheet>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';
import moment from 'moment';
import DatePicker from './DatePicker.vue';
import { debounce, apiErrorMessage } from '../../utils/globalMethods';
import akauntingService from '../../services/akaunting.service';

export default {
	name: 'AccountingInvoicePaymentForm',
	components: {
		DatePicker
	},
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			formData: {
				transaction_type: 'INVOICE_PAYMENT',
				number: '',
				contact_id: null,
				paid_at: null,
				amount: 0,
				account_id: null,
				de_account_id: null,
				category_id: null,
				payment_method: null,
				currency_code: null,
				currency_rate: 1,
				entities: []
			},
			defaultFields: {},
			isSaving: false,
			showSnackbar: false,
			snackbarOption: {},
			isCustomerDataLoading: false,
			searchCustomerText: null,
			customerLists: [],
			invoices: [],
			isInvoiceLoading: false,
			creditList: []
		};
	},

	created() {
		this.defaultFields = JSON.stringify(this.formData);
	},

	watch: {
		showDialog(isOpen) {
			if (isOpen) {
				this.formData = {
					...this.formData,
					paid_at: moment().format('YYYY-MM-DD HH:mm:ss')
				};
				this.getDefaults();
				this.getDoubleEntries();

				if(this.isEditMode){
					this.setTransactionInvoices(this.formValues);
				}
			} else {
				this.invoices = [];
			}
		},

		formValues(value) {
			if (value) {
				this.formData = {
					transaction_type: 'INVOICE_PAYMENT',
					number: value.number,
					contact_id: value.contact_id,
					amount: value.amount,
					account_id: value.account_id,
					de_account_id: value.de_account_id,
					category_id: value.category_id,
					payment_method: value.payment_method,
					currency_code: value.currency_code,
					currency_rate: value.currency_rate,
					description: value.description,
					entities: [],
					paid_at: moment(value.paid_at).format('YYYY-MM-DD HH:mm:ss')
				};
				this.customerLists = [{ name: value.contact.name, id: value.contact_id }];
			}
		},

		searchCustomerText: debounce(function() {
			this.searchCustomer();
		}, 600)
	},

	computed: {
		...mapGetters('accounting', ['isQBOEnabled']),
		...mapState('accounting', ['defaults']),

		showDialog: {
			get() {
				return this.open;
			}
		},

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		},

		invoiceTotalAmount() {
			return this.invoices.reduce((c, n) => c + Number(n.amount), 0);
		},

		paymentMethodList() {
			if (this.defaults) {
				return Object.keys(this.defaults.options.payment_methods).map((key) => ({
					value: key,
					text: this.defaults.options.payment_methods[key]
				}));
			}
			return [];
		},

		debitList() {
			if (this.defaults) {
				return Object.keys(this.defaults.options.accounts).map((key) => ({
					value: key,
					text: this.defaults.options.accounts[key]
				}));
			}
			return [];
		},

		categoryList() {
			if (this.defaults) {
				return Object.keys(this.defaults.options.income_categories).map((key) => ({
					value: key,
					text: this.defaults.options.income_categories[key]
				}));
			}
			return [];
		}

		/* creditList() {
	if (this.defaults) {
		return Object.keys(this.defaults.options.expense_categories).map((key) => ({
		value: key,
		text: this.defaults.options.expense_categories[key]
		}));
	}
	return [];
	} */
	},

	methods: {
		...mapActions('accounting', ['createInvoicePayment', 'updateInvoicePayment', 'getCustomersData', 'fetchDefaults']),

		onClose() {
			this.formData = JSON.parse(this.defaultFields);
			this.$refs.paymentForm.reset();
			this.$emit('toggle');
		},

		async getDefaults() {
			try {
				await this.fetchDefaults();
				const { defaults } = this.defaults;
				this.formData = {
					...this.formData,
					account_id: defaults.account,
					category_id: defaults.income_category,
					payment_method: defaults.payment_method,
					currency_code: defaults.currency
				};
			} catch (error) {
				apiErrorMessage(error);
			}
		},

		async searchCustomer() {
			const search = this.searchCustomerText;
			if (!search) {
				return;
			}

			const customer = this.customerLists.find((record) => record.name === search);

			if (this.isCustomerDataLoading || !!customer) {
				return;
			}

			this.isCustomerDataLoading = true;
			try {
				const { data } = await this.getCustomersData({ page: 1, limit: 10, search: search + '' });
				this.customerLists = data || [];
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.isCustomerDataLoading = false;
			}
		},

		async onChangeCustomer(value) {
			this.isInvoiceLoading = true;
			try {
				const { data } = await akauntingService.getCustomerOpenInvoices(value);

				const _invoices = data?.data || [];
				this.invoices = _invoices.map((record) => ({
					document_id: record.id,
					description: record.description || record.document_number,
					due_at: record.due_at,
					original_amount: record.amount,
					original_amount_formatted: record.amount_formatted,
					open_balance: record.open_balance,
					open_balance_formatted: record.open_balance_formatted,
					amount: 0
				}));
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.isInvoiceLoading = false;
			}
		},

		async getDoubleEntries() {
			try {
				const { data } = await akauntingService.getCoaAccountsAndDefaults();
				const de = data.data.data;
				this.formData = {
					...this.formData,
					de_account_id: de.defaults.accounts_receivable
				};
				const options = de.accounts;
				const _data = [];
				Object.keys(options).forEach((key) => {
					_data.push({ header: key });
					Object.keys(options[key]).forEach((k) => {
						_data.push({ text: options[key][k], value: parseInt(k) });
					});
				});

				this.creditList = _data;
			} catch (error) {
				apiErrorMessage(error);
			}
		},

		// async onSaveForm() {
		// 	if (this.isSaving) {
		// 		return;
		// 	}

		// 	const validated = this.$refs.paymentForm.validate();
		// 	if (validated) {
		// 		this.isSaving = true;
		// 		try {
		// 			const { data } = await this.createInvoicePayment({
		// 				...this.formData,
		// 				qbo_enabled: this.isQBOEnabled,
		// 				amount: this.invoiceTotalAmount,
		// 				entities: JSON.stringify(
		// 					this.invoices.map((invoice) => ({
		// 						description: invoice.description,
		// 						amount: invoice.amount,
		// 						document_id: invoice.document_id
		// 					}))
		// 				)
		// 			});

		// 			const message = data.message || 'Data was successfully saved.';
		// 			this.snackbarOption = {
		// 				icon: 'mdi-check',
		// 				color: 'success',
		// 				message
		// 			};

		// 			this.$refs.paymentForm.reset();
		// 			this.$emit('toggle', { created: true, message });
		// 		} catch (error) {
		// 			const { data } = error.response || { data: {} };

		// 			this.snackbarOption = {
		// 				icon: 'mdi-alert-circle',
		// 				color: 'error',
		// 				message: data.message || 'Could not save the data.'
		// 			};
		// 		} finally {
		// 			this.showSnackbar = true;
		// 			this.isSaving = false;
		// 		}
		// 	}
		// },

		async onSaveForm() {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.paymentForm.validate();
			if (validated) {
				this.isSaving = true;
				try {

					const form = {
						...this.formData,
						qbo_enabled: this.isQBOEnabled,
						amount: this.invoiceTotalAmount,
						entities: JSON.stringify(
							this.invoices.filter(function(invoice) {
								return invoice.amount != 0;
							}).map((invoice) => ({
								description: invoice.description,
								amount: invoice.amount,
								document_id: invoice.document_id
							}))
						),
					}

					const _formData = new FormData();

					Object.keys(form).forEach((key) => {
						// console.log(key, form[key]);
						_formData.append(key, form[key]);
					});

					if (this.isEditMode) {
						_formData.append('_method', 'PATCH');
					}

					const data = this.isEditMode
						? await this.updateInvoicePayment({ id: this.formValues.id, payload: _formData })
						: await this.createInvoicePayment(_formData);

					const message = data.message || this.isEditMode ? 'Data was successfully updated.' : 'Data was successfully saved.';

					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };

					this.$refs.paymentForm.reset();
					this.$emit('toggle', { created: true, message });
				} catch (error) {
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

		formatDate(date) {
			return moment(date).format('MMM DD, YYYY');
		},

		async setTransactionInvoices(formValues){
			this.isInvoiceLoading = true;
			try {
				const { data } = await akauntingService.getCustomerOpenInvoices(formValues.contact_id);
				const openInvoices = data?.data || [];
				
				openInvoices.forEach(function(c){
					let amt = 0;
					formValues.transaction_items.map(function(v){
						if(v.document_id == c.id){
							amt = v.amount;
						}
					});

					if(this.isEditMode){
						c.open_balance = c.open_balance + amt;
					}

					this.invoices.push({
						document_id: c.id,
						description: c.description || c.document_number,
						due_at: c.due_at,
						original_amount: c.amount,
						original_amount_formatted: c.amount_formatted,
						open_balance: c.open_balance,
						open_balance_formatted: c.open_balance_formatted,
						amount: amt
					});
				},this)
				
				if(this.isEditMode){
					//include paid documents on edit
					formValues.transaction_items.map(function(v){
						// TODO: make sure invoice is not duplicated with open invoices
						if(v.document && v.document.status === 'paid'){
							this.invoices.push({
								document_id: v.document.id,
								description: v.description || v.document.document_number,
								due_at: v.document.due_at,
								original_amount: v.document.amount,
								original_amount_formatted: v.document.amount_formatted,
								open_balance: v.document.open_balance + v.amount,
								open_balance_formatted: v.document.open_balance_formatted,
								amount: v.amount
							})
						}
					},this);
				}

			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.isInvoiceLoading = false;
			}
		},
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

	.table {
		width: 100%;
		margin-bottom: 25px;

		thead {
			tr {
				th {
					box-shadow: none;
					padding: 8px 12px;
					background-color: #f7f7f7;
					text-transform: uppercase;
					font-size: 12px;
					color: #6D858F;

					&:nth-child(2) {
						padding-left: 4px;
					}
				}
			}
		}

		tbody {
			tr {
				td {
					box-shadow: none;
					padding: 8px 12px;

					&:nth-child(2) {
						padding-left: 0;
					}
				}
			}
		}
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
