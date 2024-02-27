<template>
	<div class="text-center">
		<v-dialog v-model="showDialog" max-width="980" origin="top center" content-class="accounting-module-dialog pa-0" persistent scrollable>
			<v-card>
				<v-card-title class="pa-0 z-index-front">
					<v-toolbar light elevation="1" class="">
						<v-toolbar-title>{{ isEditMode ? 'Edit Payment' : 'Receive Payment' }}</v-toolbar-title>
						<v-spacer></v-spacer>
						<v-btn icon @click="onClose">
							<v-icon>mdi-close</v-icon>
						</v-btn>
					</v-toolbar>
				</v-card-title>

				<v-card-text class="mt-8">
					<v-row>
						<v-col>
							<div class="mb-2">
								<span class="text-uppercase form-label">{{ 'Invoice' }} #</span>
								<span class="ml-10 font-weight-black">{{ invoice.invoiceNumber }}</span>
							</div>
							<div class="mb-2">
								<span class="text-uppercase form-label">{{ 'Reference' }}</span>
								<span class="ml-8">
									<a href="#">#{{ invoice.refNumber }}</a>
								</span>
							</div>
							<div>
								<span class="text-uppercase form-label">{{ 'Issue Date' }}</span>
								<span class="ml-8 font-weight-black">{{
									getMonthNameDayYearFormat(invoice.invoiceDate)
								}}</span>
							</div>
						</v-col>
						<v-col>
							<div class="mb-2">
								<span class="text-uppercase form-label">{{ 'Due Date' }}</span>
								<span class="ml-12 font-weight-black">{{
									getMonthNameDayYearFormat(invoice.dueDate)
								}}</span>
							</div>
							<div>
								<span class="text-uppercase form-label">{{ 'Balance Due' }}</span>
								<span class="ml-5 font-weight-black">{{ currencyUSDFormat(invoice.balanceDue) }}</span>
							</div>
						</v-col>
					</v-row>
					<v-form ref="receivePaymentForm">
						<v-row>
							<v-col cols="6">
								<label class="form-label text-uppercase" for="formdata-payment-date">{{
									'Payment Date'
								}}</label>
								<date-picker
									v-model="formData.paymentDate"
									id="formdata-payment-date"
									:rules="[(v) => !!v || 'Field required']"
								></date-picker>
							</v-col>

							<v-col cols="6">
								<label class="form-label text-uppercase" for="formdata-payment-method">{{
									'Payment Method'
								}}</label>
								<v-select
									v-model="formData.paymentMethod"
									:items="paymentMethodData"
									:rules="[(v) => !!v || 'Field required']"
									id="formdata-payment-method"
									:label="'Select Payment Method'"
									solo
									flat
									class="app-theme-input-border"
									dense
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								>
								</v-select>
							</v-col>

							<v-col cols="6">
								<label class="form-label text-uppercase" for="formdata-deposit-to">{{
									'Deposit To'
								}}</label>
								<v-select
									v-model="formData.depositTo"
									:items="depositToData"
									:rules="[(v) => !!v || 'Field required']"
									id="formdata-deposit-to"
									:label="'select_deposit_to'"
									solo
									flat
									class="app-theme-input-border"
									dense
									:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
								>
								</v-select>
							</v-col>

							<v-col cols="6">
								<label class="form-label text-uppercase" for="formdata-amount-received">{{
									'Amount Received'
								}}</label>
								<v-text-field
									v-model="formData.amountReceived"
									:label="'Enter amount received'"
									solo
									class="app-theme-input-border"
									flat
									required
									dense
									id="formdata-amount-received"
								></v-text-field>
							</v-col>

							<v-col cols="6">
								<label class="text-uppercase form-label">{{ 'Attachments' }}</label>
								<v-card
									class="text-center pa-5 border-dashed"
									flat
									@dragover="dragover"
									@dragleave="dragleave"
									@drop="drop"
									:class="[isBrowseFileHovered ? 'grey lighten-2' : '']"
								>
									<v-list subheader two-line v-if="fileList.length" dense>
										<v-list-item v-for="(file, index) in fileList" :key="file.name" dense>
											<v-list-item-avatar height="20">
												<v-icon color="#0889a0" v-text="'mdi-image'"></v-icon>
											</v-list-item-avatar>

											<v-list-item-content>
												<v-list-item-title v-text="file.name"></v-list-item-title>
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
										id="file-input-invoice-payment-form"
										class="display-none"
										@change="onFileBrowseChange"
										ref="fileInvoicePaymentAttachment"
										accept=".jpg,.jpeg,.png"
									/>
									<label for="file-input-invoice-payment-form" class="form-label pa-5">
										<h6 class="pb-4">{{ 'Browse Or Drop Image' }}</h6>
									</label>
									<v-btn
										small
										text
										class="text-capitalize pa-3 app-theme-input-border"
										color="#0889a0"
										outlined
										@click="onUploadFile"
									>
										<v-icon>mdi-upload</v-icon>
										{{ 'Upload' }}
									</v-btn>
								</v-card>
							</v-col>

							<v-col cols="6">
								<label class="form-label text-uppercase" for="formdata-notes">{{ 'Notes' }}</label>
								<v-textarea
									v-model="formData.notes"
									solo
									:label="'Type your notes here' + '...'"
									class="app-theme-input-border"
									flat
									id="formdata-notes"
									rows="4"
								></v-textarea>
							</v-col>
						</v-row>
					</v-form>
				</v-card-text>

				<v-divider></v-divider>

				<v-card-actions class="justify-start">
					<v-btn @click="onSaveForm" class="text-capitalize btn-primary">{{
						isEditMode ? 'Update' : 'Save'
					}}</v-btn>
					<v-btn text class="text-capitalize" @click="onClose">{{ 'Cancel' }}</v-btn>
					<v-spacer></v-spacer>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
	import DatePicker from './DatePicker.vue';

	export default {
		name: 'ReceivePaymentForm',
		components: {
			DatePicker
		},
		props: ['open', 'isEditMode', 'formValues', 'invoice'],
		data() {
			return {
				formData: {
					paymentDate: '',
					paymentMethod: '',
					depositTo: '',
					amountReceived: 0,
					notes: ''
				},
				paymentDate: false,
				depositToData: [],
				paymentMethodData: [],
				fileList: [],
				isBrowseFileHovered: false
			};
		},

		created() {
			if (this.formValues) {
				this.formData = JSON.parse(JSON.stringify(this.formValues));
			}
		},

		watch: {
			formValues(values) {
				if (values) {
					this.formData = {
						...values
					};
				} else {
					this.formData = {};
				}
			}
		},

		computed: {
			showDialog: {
				get() {
					// If the modal is opened, set the default form values here
					if (this.formValues) {
						this.formData = JSON.parse(JSON.stringify(this.formValues));
					}
					return this.open;
				},
				set(value) {
					this.$emit('toggle', value);
					this.formData = {
						billCategories: []
					};
				}
			}
		},

		methods: {
			currencyUSDFormat(amount) {
				return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
			},

			getMonthNameDayYearFormat(dateString) {
				if (!dateString) {
					return '';
				}
				const months = ['Jan', 'Feb', 'March', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
				const _date = new Date(dateString);
				return `${months[_date.getMonth()]} ${_date.getDate()}, ${_date.getFullYear()}`;
			},

			onClose() {
				this.formData = {};
				this.fileList = [];
				this.$emit('toggle');
			},

			onSaveForm() {
				this.$emit('toggle');
			},

			onFileBrowseChange() {
				this.fileList = [...this.$refs.fileInvoicePaymentAttachment.files];
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
				this.$refs.fileInvoicePaymentAttachment.files = event.dataTransfer.files;
				this.onFileBrowseChange();
				this.isBrowseFileHovered = false;
			},

			onUploadFile() {
				// Upload to server here
				console.log('Uploading...');
			}
		}
	};
</script>

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

	.btn-primary {
		background-color: $button-bg-color !important;
		color: #fff !important;
	}
	.border-dashed {
		border: 1px dashed $form-label !important;
	}
</style>
