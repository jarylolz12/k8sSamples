<template>
	<v-sheet>
		<v-dialog
			v-model="showDialog"
			max-width="1280"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card :loading="isSaving">
				<v-card-title>
					<span class="headline">{{ 'Statement of Account' + (formData.customer ?  ' - ' + formData.customer.name : '') }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				
				<v-card-text class="pa-0">
					<v-form ref="statementForm">
						<v-row no-gutters>
							<v-col md="12" cols="12" class="item-form-column white pa-2">
								<v-card flat>
									<v-card-text class="mb-0 pa-0">
										<v-row>
											<v-col cols="12" md="5" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-town">Start Date</label>
												<template>
			                                        <v-row>
			                                            <v-col
			                                            cols="12"
			                                            >
			                                            <v-menu
			                                                ref="menu"
			                                                v-model="menu"
			                                                :close-on-content-click="false"
			                                                :return-value.sync="formData.start_date"
			                                                transition="scale-transition"
			                                                offset-y
			                                                min-width="auto"
			                                            >
			                                                <template v-slot:activator="{ on, attrs }">
			                                                    <v-text-field
			                                                        flat solo
			                                                    v-model="formData.start_date"
			                                                    class="text-fields"
			                                                    outlined
			                                                    label="Select Date"
			                                                    append-icon="mdi-calendar"
			                                                    readonly
			                                                    v-bind="attrs"
			                                                    v-on="on"
			                                                        
			                                                    ></v-text-field>
			                                                </template>
			                                                <v-date-picker
			                                                v-model="formData.start_date"
			                                                no-title
			                                                scrollable
			                                                >
			                                                <v-spacer></v-spacer>
			                                                <v-btn
			                                                    text
			                                                    color="primary"
			                                                    @click="menu = false"
			                                                >
			                                                    Cancel
			                                                </v-btn>
			                                                <v-btn
			                                                    text
			                                                    color="primary"
			                                                    @click="$refs.menu.save(formData.start_date)"
			                                                >
			                                                    OK
			                                                </v-btn>
			                                                </v-date-picker>
			                                            </v-menu>
			                                            </v-col>
			                                        </v-row>
			                                        </template>
											</v-col>
											<v-col cols="12" md="5" class="pb-0">
												<label class="form-label text-uppercase" for="formdata-town">End Date</label>
												<v-menu
		                                            ref="menu1"
		                                            v-model="menu1"
		                                            :close-on-content-click="false"
		                                            :return-value.sync="formData.end_date"
		                                            transition="scale-transition"
		                                            offset-y
		                                            min-width="auto"
		                                        >
		                                            <template v-slot:activator="{ on, attrs }">
		                                                <v-text-field
		                                                    flat solo
		                                                    v-model="formData.end_date"
		                                                    class="text-fields"
		                                                    outlined
		                                                    label="Select Date"
		                                                    append-icon="mdi-calendar"
		                                                    readonly
		                                                    v-bind="attrs"
		                                                    v-on="on"
		                                                ></v-text-field>
		                                            </template>
		                                            <v-date-picker
		                                            v-model="formData.end_date"
		                                            no-title
		                                            scrollable
		                                            >
		                                            <v-spacer></v-spacer>
		                                            <v-btn
		                                                text
		                                                color="primary"
		                                                @click="menu1 = false"
		                                            >
		                                                Cancel
		                                            </v-btn>
		                                            <v-btn
		                                                text
		                                                color="primary"
		                                                @click="$refs.menu1.save(formData.end_date)"
		                                            >
		                                                OK
		                                            </v-btn>
		                                            </v-date-picker>
		                                        </v-menu>
											</v-col>
											<v-col cols="12" md="2" class="pb-0">
												<v-btn
													@click="fetchInvoiceData()"
													class="text-capitalize btn-primary btn-blue filter-btn mt-6"
													:loading="isSaving || isInvoicesLoading"
													:disabled="isSaving || isInvoicesLoading" 
													style="height:44px!important"
													>{{ 'Filter' }}</v-btn
												>
											</v-col>
											<!-- <v-col cols="12" md="6" class="pb-0">
										        <v-select
													:items="types" 
													v-model="formData.type"
													label="Type"
													class="form-label font14" 
													solo
													flat
													outlined
													dense
													hide-details
													clearable
													:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
												>
												</v-select>
											</v-col> -->
										</v-row>
										<v-row>
											<v-col cols="12" sm="12" md="3">
												<div>
													<p class="text-uppercase font-medium font-18 pt-2 text-center">{{ moneyFormat(formData.opening_balance) }}</p>
													<p class="labelcolor--text text-uppercase font-medium font-18 text-center">Opening Balance</p>
												</div>
											</v-col>
											<v-col cols="12" sm="12" md="1">
												<div>
													<p class="text-uppercase font-bold font-18 pt-2 text-center">+</p>
													<p class="labelcolor--text text-uppercase font-medium font-18 text-center"> </p>
												</div>
											</v-col>
											<v-col cols="12" sm="12" md="2">
												<div>
													<p class="text-uppercase font-medium font-18 pt-2 text-center">
														{{ moneyFormat(formData.total_amount) }}
													</p>
													<p class="labelcolor--text text-uppercase font-medium font-18 text-center">Invoice Amount</p>
												</div>
											</v-col>
											<v-col cols="12" sm="12" md="1">
												<div>
													<p class="text-uppercase font-bold font-18 pt-2 text-center">-</p>
													<p class="labelcolor--text text-uppercase font-medium font-18 text-center"> </p>
												</div>
											</v-col>
											<v-col cols="12" sm="12" md="2">
												<div>
													<p class="text-uppercase font-medium font-18 pt-2 text-center">
														{{ moneyFormat(formData.total_paid_amount) }}
													</p>
													<p class="labelcolor--text text-uppercase font-medium font-18 text-center">Amount Paid</p>
												</div>
											</v-col>
											<v-col cols="12" sm="12" md="1">
												<div>
													<p class="text-uppercase font-bold font-18 pt-2 text-center">=</p>
													<p class="labelcolor--text text-uppercase font-medium font-18"> </p>
												</div>
											</v-col>
											<v-col cols="12" sm="12" md="2">
												<div>
													<p class="text-uppercase font-bold font-18 pt-2 text-center">
														{{ moneyFormat(formData.closing_balance) }}
													</p>
													<p class="labelcolor--text text-uppercase font-bold font-18 text-center">Closing Balance</p>
												</div>
											</v-col>
										</v-row>
										<v-row>
											<v-col cols="12" md="12">
												<table class="custom-table">
													<thead>
														<tr>
								                            <th>Date</th>
								                            <th>Document</th>
								                            <th>Value</th>
								                            <th>Paid</th>
								                            <th>Balance</th>
								                        </tr>
													</thead>
													<tbody class="mb-3">
														<tr v-show="isInvoiceLoading">
															<td colspan="5">Loading...</td>
														</tr>
														<tr v-show="!isInvoiceLoading" v-for="(item,index) in formData.invoices" :key="index">
															<td>{{ formatDateTime(item.due_at,'YYYY-MM-DD') }}</td>
															<td>Invoice:{{ item.document_number }}</td>
															<td style="text-align:right">{{ moneyFormat( item.amount) }}</td>
															<td style="text-align:right">{{ moneyFormat(item.paid_amount) }}</td>
															<td style="text-align:right">{{ moneyFormat(item.open_balance) }}</td>
														</tr>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="2"><p><b>Total:</b> </p></td>
															<td style="border-left:none!important;border-right:none!important;text-align:right">
																<p>
																	<b>{{ moneyFormat(formData.amount) }}</b> 
																</p>
															</td>
															<td style="border-left:none!important;border-right:none!important;text-align:right">
																<p>
																	<b>{{ moneyFormat(formData.total_paid_amount) }}</b> 
																</p>
															</td>
															<td style="text-align:right;">
																<p>
																	<b> {{ moneyFormat(formData.amount_due) }}</b>
																</p>
															</td>
														</tr>
													</tfoot>
												</table>
												<v-divider />
												<pagination
													:total="pagination ? pagination.last_page : 1"
													:count="pagination ? pagination.total : 0"
													:current-page="invoice_pagination.page"
													:total-visible="10"
													:pageLimit.sync="invoice_pagination.limit"
													@pageSelected="onPaginationClick"
													@changeLimit="onChangePageLimit"
													:from="pagination ? pagination.from : 1"
													:to="pagination ? pagination.to : 1"
												/>
											</v-col>
										</v-row>
									</v-card-text>
								</v-card>
							</v-col>
						</v-row>
					</v-form>
				</v-card-text>

				<v-card-actions class="justify-start">
					<v-btn
						@click="onSaveForm(true)"
						class="text-capitalize btn-primary btn-blue"
						:loading="isSaving || isInvoicesLoading"
						:disabled="isSaving || isInvoicesLoading"
						>{{ 'Save and Send' }}</v-btn
					>
					<v-btn
						@click="onSaveForm(false)"
						class="text-capitalize btn-primary btn-blue"
						:loading="isSaving || isInvoicesLoading"
						:disabled="isSaving || isInvoicesLoading"
						>{{ 'Save' }}</v-btn
					>
					<v-btn outlined text class="text-capitalize primary--text btn-white" @click="onClose" :disabled="isSaving">{{
						'Cancel'
					}}</v-btn>
					<v-spacer></v-spacer>
				</v-card-actions>
			</v-card>
			<v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar>
		</v-dialog>
	</v-sheet>
</template>

<script>
	import { mapActions, mapState } from 'vuex';
	import AkauntingService from '../../services/akaunting.service';
	import { apiErrorMessage, debounce } from '../../utils/globalMethods';
	import Pagination from './Pagination.vue';
	import moment from 'moment';

	export default {
		components: { Pagination },
		props: ['open', 'isEditMode', 'formValues'],
		data() {
			return {
				select_all: 1,
				menu: false,
				menu1: false,
				isInvoiceLoading: false,
				types: ['open item'], // 'balance forward','open item','Transaction statement'
				formData: {
					type: 'open item',
					contact_id: this.$route.params.id,
					statement_id: false,
					is_send: 0,
					customer_data: false,
					customer: '',
					currency_code: '',
					start_date: moment().startOf('month').format('YYYY-MM-DD'),
					end_date: moment().format('YYYY-MM-DD'),
					invoices: [],
					amount: 0,
					amount_due: 0,
					total_amount: 0,
					total_paid_amount: 0,
					opening_balance: 0,
					closing_balance: 0
				},
				isInvoicesLoading: false,
				defaultFields: {},
				isSaving: false,
				showSnackbar: false,
				snackbarOption: {},
				otherCountry: [],
				invoicesData: false,
				invoice_pagination: {
					page: 1,
					limit: 100,
					searchText: ''
				}
			}
		},
		created() {
			this.defaultFields = JSON.parse(JSON.stringify(this.formData));
			this.getCurrencies();
		},
		watch: {
			formValues(values) {
				if (values) {
					this.formData.statement_id = values.id;
					this.formData.type = values.type;
					this.formData.contact_id = values.contact_id;
					this.formData.amount = values.amount;
					this.formData.amount_due = values.amount_due;
					this.formData.total_paid_amount = values.total_paid_amount;
					this.formData.opening_balance = values.opening_balance;
					this.formData.closing_balance = values.closing_balance;
					this.formData.start_date = values.start_date && values.start_date != '' ? moment(values.start_date).format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD');
					this.formData.end_date = values.end_date && values.end_date != '' ? moment(values.end_date).format('YYYY-MM-DD') : moment().format('YYYY-MM-DD');
					this.formData.invoices = values.docs;
				} else {
					this.formData = JSON.parse(JSON.stringify(this.defaultFields));
				}
			},
			open(isOpen) {
				let _this = this;
				if (isOpen) {
					this.formData.customer_data = JSON.parse(JSON.stringify(this.$store.state.customers.customer_data));
					this.formData.customer = JSON.parse(JSON.stringify(this.$store.state.customers.customer_data));
					this.getOpeningBalance(this.formData.customer.id,{start_date : this.formData.start_date});
				}

				if (!isOpen || ( isOpen && !this.isEditMode ) ) {
					this.formData.statement_id = false;
					this.formData.invoices = [];
					this.formData.amount = 0;
					this.formData.amount_due = 0;
					this.formData.total_amount = 0;
					this.formData.total_paid_amount = 0;
					this.formData.opening_balance = 0;
					this.formData.closing_balance = 0;

					setTimeout(()=>{
						_this.formData.start_date =  moment().startOf('month').format('YYYY-MM-DD');
						_this.formData.end_date = moment().format('YYYY-MM-DD');
						_this.formData.type = 'open item';
					},500);
				}
			},
			invoicesData(v){
				this.formData.invoices = v.data;
			},
			'invoice_pagination.searchText': debounce(function() {
				this.fetchInvoiceData();
			}, 300),
			totalAmount(v){
				this.formData.amount = v;
				this.formData.total_amount = v;
			},
			totalAmountDue(v){
				this.formData.amount_due = v;
			},
			totalPaidAmount(v){
				this.formData.total_paid_amount = v;
			},
			closingBalance(v){
				this.formData.closing_balance = v;
			},
			'formData.customer_data'(v){
				this.formData.currency_code = v.currency_code;
			}
		},
		computed: {
			...mapState('accounting', ['currencies']),
			totalAmount(){
				return this.formData.invoices.map( i => i.amount ).reduce( (a,b) => a+b, 0);
			},
			totalAmountDue(){
				return this.totalAmount - this.totalPaidAmount;
			},
			totalPaidAmount(){
				return this.formData.invoices.map( i => ( typeof i.paid_amount != 'undefined' ? i.paid_amount : i.total_paid_amount ) ).reduce( (a,b) => a+b, 0);
			},
			closingBalance(){
				return (this.formData.opening_balance + this.totalAmount) - (this.totalPaidAmount);
			},
			showDialog: {
				get() {
					return this.open;
				}
			},
			currencyData() {
				return this.currencies.map((currency) => ({ text: currency.name, value: currency.code }));
			},
			isMobile() {
				return this.$vuetify.breakpoint.mobile;
			},
			currencySymbol: {
				get() {
					if (this.formData.currency) {
						const selectedCurrency = this.formData.currency;
						return this.currencies.find((currency) => currency.code === selectedCurrency)?.symbol;
					}
					return '';
				}
			},
			currencyValue() {
				if (this.formData.currency) {
					const selectedCurrency = this.formData.currency;
					const currency = this.currencies.find((currency) => currency.code === selectedCurrency);
					if (currency) {
						return JSON.stringify({ name: currency.name, code: currency.code });
					}
				}
				return null;
			},
			pagination: {
				get() {
					return this.invoicesData ? this.invoicesData.meta : {};
				}
			}
		},
		methods: {
			...mapActions('accounting', ['createStatementForm', 'getCurrencies']),
			moneyFormat(item){
				if( item && this.formData.currency_code == 'USD' ){
					return item.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
				}else{
					return ( this.formData.currency_code == 'USD' ? '$' : '')+item;
				}
			},
			onPaginationClick(pageNumber) {
				this.invoice_pagination.page = pageNumber;
				this.fetchInvoiceData();
			},
			onChangePageLimit() {
				this.invoice_pagination.page = 1;
				this.fetchInvoiceData();
			},
			async fetchInvoiceData() {
				if (!this.open || this.isInvoicesLoading ) {
					return;
				}

				this.isInvoicesLoading = true;

				try {
					const data = await AkauntingService.getCustomerInvoices(this.$route.params.id,{
						page: this.invoice_pagination.page,
						limit: this.invoice_pagination.limit,
						search: this.invoice_pagination.searchText || '',
						start_date: this.formData.start_date,
						end_date: this.formData.end_date,
						og: 'cs' // origin
					});

					// const data2 = await AkauntingService.getCustomerInvoices(this.$route.params.id,{
					// 	page: this.invoice_pagination.page,
					// 	limit: this.invoice_pagination.limit,
					// 	search: this.invoice_pagination.searchText || '',
					// 	start_date: this.formData.start_date,
					// 	end_date: this.formData.end_date,
					// 	tp: 'open', // type
					// 	og: 'cs' // origin
					// });

					this.invoicesData = data.data;
					
					// if( data2 ){
					// 	this.formData.opening_balance = data2.data.data.map( i => i.open_balance ).reduce( (a,b) => a+b, 0);
					// }

					this.getOpeningBalance(this.$route.params.id,{start_date:this.formData.start_date});

					this.isInvoicesLoading = false;
				} catch (error) {
					this.isInvoicesLoading = false;
					apiErrorMessage(error);
				}
			},
			formatDateTime(d,f){
				return moment(d).format(f);
			},
			onClose() {
				this.$refs.statementForm.reset();
				this.$emit('toggle');
			},
			async onSaveForm(is_send) {
				if (this.isSaving) {
					return;
				}

				this.formData.is_send = is_send ? 1 : 0;

				const validated = this.$refs.statementForm.validate();

				if (validated) {
					this.isSaving = true;
					try {

						let rawdata = this.formData;

						const { data } = await this.createStatementForm({
							...rawdata,
							currency: this.currencyValue
						});

						const message = data.message || 'Statement has been created.';

						this.snackbarOption = {
							icon: 'mdi-check',
							color: 'success',
							message
						};

						this.$refs.statementForm.reset();

						this.$emit('toggle', { created: true, message });
					} catch (error) {
						const { data } = error.response || { data: {} };

						this.snackbarOption = {
							icon: 'mdi-alert-circle',
							color: 'error',
							message: data.message || 'Could not save the statement.'
						};
					} finally {
						this.showSnackbar = true;
						this.isSaving = false;
					}
				}
			},

			async getOpeningBalance(id,params){
				this.isInvoicesLoading = true;
				try {

					const data = await AkauntingService.getStatementOpeningBalance(id,{
						start_date: params.start_date,
						end_date: params.end_date
					});
					if(data){
						this.formData.opening_balance = data.data.balance;

						this.isInvoicesLoading = false;
					}
				} catch (error) {
					const { data } = error.response || { data: {} };

					this.snackbarOption = {
						icon: 'mdi-alert-circle',
						color: 'error',
						message: data.message || 'Failed to get opening balance, please try again.'
					};

					this.isInvoicesLoading = false;
				}
			},
		}
	};
</script>

<style lang="scss">
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';

.filter-btn{
	height:44px!important;
}
</style>

<style lang="scss" scoped>
	$button-bg-color: #0171a1;
	$form-label: #819fb2;

	.stat-box{
		width:100%;
		padding:16px;
		text-align:center;
		border:1px solid #F5F5F5;
		margin-bottom: 16px;
		h1{
			margin-bottom: 8px;
			font-weight: bold;
			color:#819fb2;
		}
		h4{
			margin-bottom: 0px;
			opacity: .7;
		}
	}
	.custom-table{
		border:none;
		width:100%;
		th{
			border-bottom-width: 2px !important;
		}
		th,td{
			padding:5px 8px;
			border:1px solid #F5F5F5;
		}
		tbody tr td{
			color: #242424;
		}
		
	}

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
