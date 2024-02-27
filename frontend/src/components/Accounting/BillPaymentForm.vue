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
					<span class="headline">{{ isEditMode ? 'Edit Bill Payment' : 'Add Bill Payment' }}</span>
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
									placeholder="Enter number"
									hide-details="auto"
								></v-text-field>
							</v-col>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-contact_id">Vendor</label>
								<v-autocomplete
									id="formdata-vendor"
									v-model="formData.contact_id"
									:loading="isVendorDataLoading"
									:items="vendorLists"
									:search-input.sync="searchVendorText"
									clearable
									dense
									solo
									flat
									outlined
									item-text="name"
									item-value="id"
									:rules="[(v) => !!v || 'Field required']"
									@change="onChangeVendor"
									placeholder="Select vendor"
									hide-details="auto"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								></v-autocomplete>
							</v-col>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-trans-date">Date</label>
								<date-picker
									id="formdata-trans-date"
									v-model="formData.datatime"
									:rules="[(v) => !!v || 'Field is required']"
								/>
							</v-col>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-amount">Amount</label>
								<v-text-field
									id="formdata-vendor"
									solo
									flat
									required
									dense
									outlined
									prepend-inner-icon="mdi-cash"
									:value="billTotalAmount"
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
									v-model="formData.de_account_id"
									id="formdata-account_id"
									outlined
									dense
									:items="debitList"
									:rules="[(v) => !!v || 'Field is required']"
									placeholder="Select debit account"
									hide-details="auto"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								></v-select>
							</v-col>
							<v-col cols="12" sm="6" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-account_id"
									>Credit Account</label
								>
								<v-autocomplete
									v-model="formData.account_id"
									id="formdata-account_id"
									outlined
									dense
									:items="creditList"
									:rules="[(v) => !!v || 'Field is required']"
									placeholder="Select credit account"
									hide-details="auto"
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
									placeholder="Type description..."
									hide-details="auto"
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
									placeholder="Select category"
									hide-details="auto"
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
									placeholder="Select payment method"
									hide-details="auto"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								></v-select>
							</v-col>
						</v-row>
						<v-sheet height="4">
							<v-progress-linear v-if="isBillLoading" value="15" indeterminate></v-progress-linear>
						</v-sheet>
						<table class="table mt-4" cellpadding="0" cellspacing="0">
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
								<template v-for="(bill, i) in bills">
									<tr :key="bill.id">
										<td class="text-start py-2">{{ i + 1 }}</td>
										<td class="text-start">
											<v-text-field
												v-model="bill.description"
												class="ma-1"
												dense
												outlined
												hide-details
											/>
										</td>
										<td class="text-start">
											{{ formatDate(bill.due_at) }}
										</td>
										<td class="text-end">
											{{ bill.original_amount_formatted }}
										</td>
										<td class="text-end">
											{{ bill.open_balance_formatted }}
										</td>
										<td class="text-end" style="width: 140px">
											<v-text-field
												v-model.number="bill.amount"
												type="number"
												class="ma-1"
												dense
												outlined
												hide-details
												:rules="
													bill.amount
														? [
																(v) =>
																	v <= bill.open_balance ||
																	'A maximum of ' + bill.open_balance
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
					<v-btn @click="onSaveForm" class="btn-blue" :loading="isSaving" :disabled="isSaving">Save</v-btn>
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
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'AccountingBillPaymentForm',
	components: {
		DatePicker
	},
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			formData: {
				transaction_type: 'BILL_PAYMENT',
				number: '',
				contact_id: null,
				datatime: null,
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
			isVendorDataLoading: false,
			searchVendorText: null,
			vendorLists: [],
			bills: [],
			isBillLoading: false,
			debitList: []
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
					datatime: moment().format('YYYY-MM-DD')
				};

				this.getDefaults();
				this.getDoubleEntries();

				if(this.isEditMode){
					this.setTransactionBills(this.formValues);
				}

			} else {
				this.bills = [];
			}
		},

		formValues(value) {
			if (value) {
				this.formData = {
					transaction_type: 'BILL_PAYMENT',
					number: value.number,
					contact_id: value.contact_id,
					amount: value.amount,
					account_id: value.account_id.toString(),
					de_account_id: value.de_account_id,
					category_id: value.category_id.toString(),
					payment_method: value.payment_method,
					currency_code: value.currency_code,
					currency_rate: value.currency_rate,
					description: value.description,
					entities: [],
					paid_at: moment(value.paid_at).format('YYYY-MM-DD HH:mm:ss')
				};
				this.vendorLists = [{ name: value.contact.name, id: value.contact_id }];
			}
		},

		searchVendorText: debounce(function() {
			this.searchVendor();
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

		billTotalAmount() {
			return this.bills.reduce((c, n) => c + Number(n.amount), 0);
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

		creditList() {
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
				return Object.keys(this.defaults.options.expense_categories).map((key) => ({
					value: key,
					text: this.defaults.options.expense_categories[key]
				}));
			}
			return [];
		}
	},

	methods: {
		...mapActions('accounting', ['createBillPayment', 'updateBillPayment', 'getVendorsData', 'fetchDefaults']),
		...globalMethods,
		onClose() {
			this.formData = JSON.parse(this.defaultFields);
			this.$refs.paymentForm.reset();
			this.$emit('toggle');
		},

		async getDefaults() {
			try {
				await this.fetchDefaults();
				const { defaults } = this.defaults;
				if(!this.isEditMode){
					this.formData = {
						...this.formData,
						account_id: defaults.account,
						category_id: defaults.expense_category,
						payment_method: defaults.payment_method,
						currency_code: defaults.currency
					};
				}
			} catch (error) {
				apiErrorMessage(error);
			}
		},

		async searchVendor() {
			const search = this.searchVendorText;
			const vendor = this.vendorLists.find((record) => record.name === search);

			if (this.isVendorDataLoading || !search || !!vendor) {
				return;
			}
			this.isVendorDataLoading = true;
			try {
				const { data } = await this.getVendorsData({ page: 1, limit: 10, search });
				this.vendorLists = data || [];
			} catch (error) {
				apiErrorMessage(error);
			} finally {
				this.isVendorDataLoading = false;
			}
		},

		async onChangeVendor(value) {
			this.isBillLoading = true;
			try {
				const { data } = await akauntingService.getVendorOpenBills(value);

				const _bills = data?.data || [];
				this.bills = _bills.map((record) => ({
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
				this.isBillLoading = false;
			}
		},

		async getDoubleEntries() {
			try {
				const { data } = await akauntingService.getCoaAccountsAndDefaults();
				const de = data.data.data;
				this.formData = {
					...this.formData,
					de_account_id: de.defaults.accounts_payable
				};
				const options = de.accounts;
				const _data = [];
				Object.keys(options).forEach((key) => {
					_data.push({ header: key });
					Object.keys(options[key]).forEach((k) => {
						_data.push({ text: options[key][k], value: parseInt(k) });
					});
				});

				this.debitList = _data;
			} catch (error) {
				apiErrorMessage(error);
			}
		},

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
						amount: this.billTotalAmount,
						entities: JSON.stringify(
							this.bills.filter(function(bill){
								return bill.amount != 0;
							}).map((bill) => ({
								description: bill.description,
								amount: bill.amount,
								document_id: bill.document_id
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
						? await this.updateBillPayment({ id: this.formValues.id, payload: _formData })
						: await this.createBillPayment(_formData);

					// const message = data.message || this.isEditMode ? 'Data was successfully updated.' : 'Data was successfully saved.';
					const message = data.message

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

		async setTransactionBills(formValues){
			this.isBillLoading = true;
			try {
				const { data } = await akauntingService.getVendorOpenBills(formValues.contact_id);
				const openBills = data?.data || [];
				
				openBills.forEach(function(c){
					let amt = 0;
					formValues.transaction_items.map(function(v){
						if(v.document_id == c.id){
							amt = v.amount;
						}
					},this);

					if(this.isEditMode){
						c.open_balance = c.open_balance + amt;
					}

					this.bills.push({
						document_id: c.id,
						description: c.description || c.document_number,
						due_at: c.due_at,
						original_amount: c.amount,
						original_amount_formatted: c.amount_formatted,
						open_balance: c.open_balance,
						open_balance_formatted: c.open_balance_formatted,
						amount: amt
					});
				},this);

				if(this.isEditMode){
					//include paid documents on edit
					formValues.transaction_items.map(function(v){
						// TODO: make sure invoice is not duplicated with open invoices
						if(v.document && v.document.status === 'paid'){
							this.bills.push({
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
				this.isBillLoading = false;
			}
		},
	}
};
</script>

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
