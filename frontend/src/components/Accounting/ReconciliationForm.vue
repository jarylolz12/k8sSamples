<template>
	<v-sheet>
		<v-dialog
			v-if="showDialog"
			v-model="showDialog"
			max-width="1360"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card :loading="isSaving">
				<v-card-title>
					<span class="headline">{{ isEditMode ? 'Edit Reconciliation' : 'New Reconciliation' }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text class="item-form-column">
					<v-form ref="reconciliationForm">
						<v-row>
							<v-col cols="12" sm="3" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-number">Start Date</label>
								<date-picker
									id="formdata-trans-date"
									v-model="formData.started_at"
									:rules="[(v) => !!v || 'Field is required']"
								/>
							</v-col>
							<v-col cols="12" sm="3" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-number">End Date</label>
								<date-picker
									id="formdata-trans-date"
									v-model="formData.ended_at"
									:rules="[(v) => !!v || 'Field is required']"
								/>
							</v-col>
							<v-col cols="12" sm="3" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-number">Closing Balance</label>
								<v-text-field
									id="formdata-customer"
									solo
									flat
									required
									dense
									outlined
									prepend-inner-icon="mdi-cash"
									v-model="formData.closing_balance"
									:rules="[(v) => !!v || 'Field is required']"
									hide-details="auto"
								></v-text-field>
							</v-col>
							<v-col cols="12" sm="3" class="pb-0">
								<label class="form-label text-uppercase" for="formdata-number">Account</label>
								<v-select
									v-model="formData.account_id"
									id="formdata-account_id"
									outlined
									dense
									:items="accountList"
									:item-text="item => item.name + ' (' + item.currency.symbol + ')'"
									:item-value="item => item.id"
									:rules="[(v) => !!v || 'Field is required']"
									hide-details="auto"
									placeholder="Select Bank Account"
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
									@change="onSelectAccount"
								></v-select>
							</v-col>
						</v-row>
						<v-row>
							<v-col cols="12" sm="12" md="3">
								<div>
									<span class="labelcolor--text text-uppercase font-medium font-18">Opening Balance</span>
										<p class="text-uppercase font-medium font-18 pt-2">{{ formData.opening_balance }}</p>
								</div>
							</v-col>
							<v-col cols="12" sm="12" md="3">
								<div>
									<span class="labelcolor--text text-uppercase font-medium font-18">Closing Balance</span>
									<p class="text-uppercase font-medium font-18 pt-2">
										{{ formData.closing_balance }}
									</p>
								</div>
							</v-col>
							<v-col cols="12" sm="12" md="3">
								<div>
									<span class="labelcolor--text text-uppercase font-medium font-18">Cleared Amount</span>
									<p class="text-uppercase font-medium font-18 pt-2">
										{{ clearedAmount }}
									</p>
								</div>
							</v-col>
							<v-col cols="12" sm="12" md="3">
								<div>
									<span class="labelcolor--text text-uppercase font-medium font-18">Difference</span>
									<p class="text-uppercase font-medium font-18 pt-2">
										{{ difference }}
									</p>
								</div>
							</v-col>
						</v-row>
						<v-data-table
							v-model="selectedTransactionsForClearing"
							class="v-table-middle-align"
							fixed-header
							disable-pagination
							:headers="transactionsTableHeader"
							:items="transactionsTableData"
							:single-select="false"
							:loading="isTransactionsLoading"
							:hide-default-footer="true"
						>
							<template v-slot:[`item.clear`]="{ item }">
								<v-simple-checkbox
									:ripple="false"
									v-model="item.selected"
									:value="item.reconciled"
								></v-simple-checkbox>
							</template>
							<template v-slot:[`item.paid_at`]="{ item }">
								{{ formatDate(item.paid_at) }}
							</template>
							<template v-slot:[`item.deposit`]="{ item }">
								{{ item.type == 'income' ? item.amount : '' }}
							</template>
							<template v-slot:[`item.withdrawal`]="{ item }">
								{{ item.type == 'expense' ? item.amount : '' }}
							</template>
						</v-data-table>
					</v-form>
				</v-card-text>							

				<v-card-actions class="justify-start">
					<v-btn @click="onSaveReconciliation(false)" class="btn-blue" :loading="isSaving" :disabled="isSaving">Save for Later</v-btn>
					<v-btn @click="onSaveReconciliation(true)" class="btn-blue" :loading="isSaving" :disabled="!formData.reconcile  || isSaving">Reconcile</v-btn>
					<v-btn outlined text class="text-capitalize primary--text btn-white" @click="onClose" :disabled="isSaving">
						Cancel
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-sheet>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';
import moment from 'moment';
import DatePicker from './DatePicker.vue';
import { apiErrorMessage } from '../../utils/globalMethods';
import akauntingService from '../../services/akaunting.service';
import globalMethods from '../../utils/globalMethods';
import _ from 'lodash';

export default {
	name: 'AccountingReconciliationForm',
	components: {
		DatePicker
	},
	props: ['open', 'isEditMode', 'formValues'],
	data() {
		return {
			formData: {
				account_id: '',
				currency_code: 'USD',
				currency_rate: 1,
				opening_balance: 0,
				closing_balance: '',
				started_at: moment().startOf('month').format('YYYY-MM-DD HH:mm:ss'),
				ended_at: moment().endOf('month').format('YYYY-MM-DD HH:mm:ss'),
				reconcile: false,
				transactions: {},
			},
			transactionList: [],
			accountList: [],
			defaultFields: {},
			isSaving: false,
			isTransactionsLoading: false,
			showSnackbar: false,
			snackbarOption: {},
			selectedTransactionsForClearing: [],
		};
	},

	created() {
		this.defaultFields = JSON.stringify(this.formData);
	},

	watch: {
		showDialog(isOpen) {
			if (isOpen) {
				this.getDefaults();
				this.getAccounts();
			} else {
				this.transactionList = [];
			}
		},

		formValues(value) {
			if(value.id){
				this.formData = {
					id: value.id,
					account_id: value.account_id,
					currency_code: value.account.currency_code,
					closing_balance: value.closing_balance,
					started_at: moment(value.started_at).format('YYYY-MM-DD HH:mm:ss'),
					ended_at: moment(value.ended_at).format('YYYY-MM-DD HH:mm:ss'),
					reconcile: value.reconcile,
					// transactions: value.transactions,
				}

			}
		},

		selectedTransactions(value){
			if(value){
				let trans = {};
				value.map(function(transaction){
					let key = transaction.type.toString() + '_' + transaction.id.toString();
					Object.assign(trans, {[key] : transaction.amount});
				},this);
				this.formData.transactions = trans;
			}
		},

		'formData.account_id': {
			deep: true,
			handler: function (newVal){
				if(newVal && this.formData.started_at && this.formData.ended_at){
					this.getAccountTransactions(newVal,{started_at:this.formData.started_at,ended_at:this.formData.ended_at});
				}
			}
		},
		'formData.started_at': {
			deep: true,
			handler: function (newVal){
				if(newVal && this.formData.account_id && this.formData.ended_at){
					this.getAccountTransactions(this.formData.account_id,{started_at:newVal,ended_at:this.formData.ended_at});
				}
			}
		},
		'formData.ended_at': {
			deep: true,
			handler: function (newVal){
				if(newVal && this.formData.account_id && this.formData.started_at){
					this.getAccountTransactions(this.formData.account_id,{started_at:this.formData.started_at,ended_at:newVal});
				}
			}
		},

		difference(val){
			if(val == 0 && this.formData.transactions){
				this.formData.reconcile = true;
			}else{
				this.formData.reconcile = false;
			}
		}
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

		transactionsTableData: {
			get() {
				return this.transactionList || [];
			}
		},

		accounts() {
			if (this.defaults) {
				return Object.keys(this.defaults.options.accounts).map((key) => ({
					value: key,
					text: this.defaults.options.accounts[key]
				}));
			}
			return [];
		},

		selectedTransactions(){
			if(this.transactionList){
				return _.filter(this.transactionList, function(c){
					return c.selected;
				});
			}
			return [];
		},

		transactionsTableHeader() {
			return [
				{
					text: 'Date',
					value: 'paid_at',
					class: 'text-uppercase th--text font-weight-bold',
					width: '20%',
					align: 'start'
				},
				{
					text: 'Description',
					value: 'number',
					class: 'text-uppercase th--text font-weight-bold',
					width: '15%',
					align: 'start'
				},
				{
					text: 'Contact',
					value: 'contact.name',
					class: 'text-uppercase th--text font-weight-bold',
					width: '18%',
					align: 'start'
				},
				{
					text: 'Deposit',
					value: 'deposit',
					class: 'text-uppercase th--text font-weight-bold',
					width: '25%',
					align: 'start'
				},
				{
					text: 'Withdrawal',
					value: 'withdrawal',
					class: 'text-uppercase th--text font-weight-bold',
					width: '12%',
					align: 'center',
					sortable: false
				},
				{
					text: 'Clear',
					value: 'clear',
					class: 'text-uppercase th--text font-weight-bold',
					width: '12%',
					align: 'center',
					sortable: false
				},
			];
		},

		clearedAmount(){
			let income = 0;
			let expense = 0;
			this.transactionList.map(function(c){
				if(c.selected){
					if(c.type === 'income'){
						income += c.amount;
					}else if(c.type === 'expense'){
						expense += c.amount;
					}
				}
			});
			return (this.formData.opening_balance) + (income - expense);
		},

		difference(){
			return this.formData.closing_balance - this.clearedAmount;
		},

		isReconciled(){
			if(this.formData.transactions && this.difference == 0){
				return true;
			}
			return false;
		},

	},

	methods: {
		...globalMethods,
		...mapActions('accounting', ['fetchDefaults','createReconciliation','updateReconciliation']),

		onClose() {
			this.formData = JSON.parse(this.defaultFields);
			this.$refs.reconciliationForm.reset();
			this.$emit('toggle');
		},

		onSelectAccount(account_id){
			if(account_id){
				const account = _.find(this.accountList, function(o){
					return o.id == account_id;
				});
				this.formData.currency_code = account.currency.code;
				this.formData.currency_rate = account.currency.rate;
			}
		},

		async onSaveReconciliation(isReconcile){
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.reconciliationForm.validate();
			if (validated) {
				this.isSaving = true;
				try {
					const _formData = {
						...this.formData,
						reconcile: isReconcile ? true : false,
					}

					this.isEditMode
						? await this.updateReconciliation({ id: this.formValues.id, payload: _formData })
						: await this.createReconciliation(_formData);

					const message = this.isEditMode ? 'Data was successfully updated.' : 'Data was successfully saved.';

					this.$refs.reconciliationForm.reset();
					this.$emit('toggle', { created: true, message });
				} catch (error) {
					console.log(error);
					const { data } = error.response || { data: {} };
					const message = data.message || 'Could not save the data.'
					
					// if(error.response.status && error.response.status == 422){
						
					// }else{

					// }
					this.notificationCustom(message)
				} finally {
					this.showSnackbar = true;
					this.isSaving = false;
				}
			}
		},

		async getAccounts() {
			try {
				const { data } = await akauntingService.getAccountsForSelection({limit:1000});
				const response = data.data;
				if(response){
					this.accountList = response.data;
				}
			} catch (error) {
				apiErrorMessage(error);
			}
		},

		async getDefaults() {
			try {
				await this.fetchDefaults();
			} catch (error) {
				apiErrorMessage(error);
			}
		},

		async getAccountTransactions(account_id,params){
			try {
				this.isTransactionsLoading = true;
				const { data } = await akauntingService.getAccountTransactionsByPeriod(account_id,params);
				const response = data.data;
				this.transactionList = response.transactions;
				this.formData.opening_balance = response.opening_balance;
			} catch (error) {
				console.log(error)
				apiErrorMessage(error);
			} finally{
				this.isTransactionsLoading = false;
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

		async onSaveForm() {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.reconciliationForm.validate();
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
						_formData.append(key, form[key]);
					});

					if (this.isEditMode) {
						_formData.append('_method', 'PATCH');
					}

					const data = this.isEditMode
						? await this.updateInvoicePayment({ id: this.formValues.id, payload: _formData })
						: await this.createInvoicePayment(_formData);

					const message = data.message || this.isEditMode ? 'Data was successfully updated.' : 'Data was successfully saved.';

					this.$refs.reconciliationForm.reset();
					this.$emit('toggle', { created: true, message });
				} catch (error) {
					const { data } = error.response || { data: {} };

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
