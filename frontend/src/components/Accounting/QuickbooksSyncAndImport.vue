<template>
	<div>
		<v-card class="mt-1 pb-5" flat>
			<v-card-text v-if="selectedSyncQBO && selectedSyncQBO.value === 'bills'">
				<v-alert dark color="primary" icon="mdi-alert" outlined prominent>
					<span>Reminder:</span>
					<ul class="reminder-list">
						<li>
							Before importing Bills, make sure to import all of the items and vendors associated to your
							bills.
						</li>
						<li>
							Account-Based items are items associated directly as Accounts in Quickbooks, mostly used for
							bills.
						</li>
						<li>
							Shifl Accounting provides Double Entry Accounting System, Make sure to set approriate entry
							accounts to your imported items before importing your bills.
						</li>
					</ul>
				</v-alert>
			</v-card-text>
			<v-card-text>
				<v-sheet class="d-flex justify-space-between">
					<v-menu offset-y>
						<template v-slot:activator="{ on, attrs }">
							<v-btn
								v-bind="attrs"
								v-on="on"
								height="40"
								class="text-capitalize"
								color="btn-blue"
								:disabled="isImporting"
							>
								{{ 'Select Entity' + (selectedSyncQBO ? ': ' + selectedSyncQBO.text : '') }}
								<v-icon>mdi-menu-down</v-icon>
							</v-btn>
						</template>
						<v-card width="300">
							<v-card-text>
								<v-list dense class="py-0">
									<v-list-item-group v-model="selectedSyncQBO" color="primary" @change="fetchSyncQBO">
										<v-list-item v-for="item in syncQBOItems" :key="item.value" :value="item">
											<v-list-item-content>
												<v-list-item-title v-text="item.text"></v-list-item-title>
											</v-list-item-content>
										</v-list-item>
									</v-list-item-group>
								</v-list>
							</v-card-text>
						</v-card>
					</v-menu>
					<v-sheet class="d-flex align-center">
						<span class="mr-2 header-2 pb-0">{{ 'Auto Sync' }}</span>
						<v-btn
							class="pa-4"
							small
							rounded
							outlined
							:color="isAutoSynced ? 'success' : 'error'"
							:loading="isAutoSyncLoading"
							@click="onConfirmSync"
						>
							<div
								v-if="isAutoSynced"
								class="d-flex align-center justify-space-around"
								style="min-width: 60px"
							>
								{{ 'On' }}
								<v-icon>mdi-check-circle</v-icon>
							</div>
							<div v-else class="d-flex align-center justify-space-around" style="min-width: 60px">
								{{ 'Off' }}
								<v-icon>mdi-minus-circle</v-icon>
							</div>
						</v-btn>
					</v-sheet>
				</v-sheet>

				<!-- Vendors Table -->
				<div v-if="isImporting || isImportLoaded" class="mt-8 mt-sm-4 elevation-1">
					<v-data-table
						v-if="selectedSyncQBO && selectedSyncQBO.value === 'vendors'"
						:headers="vendorTableHeader"
						:items="importData"
						item-key="Id"
						class="v-table-middle-align"
						:loading="isSyncingImport || isImporting"
					>
						<template v-slot:top>
							<div class="d-flex justify-space-between align-center">
								<h1 class="text-h6 pa-4">{{ selectedSyncQBO ? selectedSyncQBO.text : '' }}</h1>
								<v-btn
									class="mr-2"
									color="primary"
									:disabled="!isImportLoaded"
									@click="onImport"
									:loading="isSyncingImport"
								>
									<v-icon>mdi-import</v-icon>
									{{ 'import' }}
								</v-btn>
							</div>
							<v-alert v-if="importErrorMessage" type="error">{{ importErrorMessage }}</v-alert>
							<v-alert v-if="importMessage" type="success">{{ importMessage }}</v-alert>
						</template>
					</v-data-table>
				</div>

				<!-- Items Table -->
				<div v-if="isImporting || isImportLoaded" class="mt-4 elevation-1">
					<v-data-table
						v-if="selectedSyncQBO && selectedSyncQBO.value === 'items'"
						:headers="itemTableHeader"
						:items="importData"
						item-key="Id"
						class="v-table-middle-align"
						:loading="isSyncingImport || isImporting"
					>
						<template v-slot:top>
							<div class="d-flex justify-space-between align-center">
								<h1 class="text-h6 pa-4">{{ selectedSyncQBO ? selectedSyncQBO.text : '' }}</h1>
								<v-btn
									class="mr-2"
									color="primary"
									:disabled="!isImportLoaded"
									@click="onImport"
									:loading="isSyncingImport"
								>
									<v-icon>mdi-import</v-icon>
									{{ 'Import' }}
								</v-btn>
							</div>
							<v-alert v-if="importErrorMessage" type="error">{{ importErrorMessage }}</v-alert>
							<v-alert v-if="importMessage" type="success">{{ importMessage }}</v-alert>
						</template>
					</v-data-table>
				</div>

				<!-- Account-Based Items Table -->
				<div v-if="isImporting || isImportLoaded" class="mt-4 elevation-1">
					<v-data-table
						v-if="selectedSyncQBO && selectedSyncQBO.value === 'account-based-items'"
						:headers="accountBasedItemTableHeader"
						:items="importData"
						item-key="Id"
						class="v-table-middle-align"
						:loading="isSyncingImport || isImporting"
					>
						<template v-slot:top>
							<div class="d-flex justify-space-between align-center">
								<h1 class="text-h6 pa-4">{{ selectedSyncQBO ? selectedSyncQBO.text : '' }}</h1>
								<v-btn
									class="mr-2"
									color="primary"
									:disabled="!isImportLoaded"
									@click="onImport"
									:loading="isSyncingImport"
								>
									<v-icon>mdi-import</v-icon>
									{{ 'Import' }}
								</v-btn>
							</div>
							<v-alert v-if="importErrorMessage" type="error">{{ importErrorMessage }}</v-alert>
							<v-alert v-if="importMessage" type="success">{{ importMessage }}</v-alert>
						</template>
					</v-data-table>
				</div>

				<!-- Bills Table -->
				<div v-if="isImporting || isImportLoaded" class="mt-4 elevation-1">
					<v-data-table
						v-if="selectedSyncQBO && ['bills', 'bill-payments'].includes(selectedSyncQBO.value)"
						:headers="selectedSyncQBO.value === 'bills' ? billTableHeader : billPaymentTableHeader"
						:items="importData"
						item-key="Id"
						class="v-table-middle-align"
						:single-select="false"
						show-select
						v-model="selectedImports"
						:loading="isSyncingImport || isImporting"
					>
						<template v-slot:top>
							<div class="d-flex justify-space-between align-center">
								<h1 class="text-h6 pa-4">{{ selectedSyncQBO ? selectedSyncQBO.text : '' }}</h1>
								<v-btn
									class="mr-2"
									color="primary"
									:disabled="!isImportLoaded"
									@click="onImportQBOBilling"
									:loading="isSyncingImport"
								>
									<v-icon>mdi-import</v-icon>
									{{ 'Import' }} ({{ selectedImports.length }})
								</v-btn>
							</div>
							<v-alert v-if="importErrorMessage" type="error">{{ importErrorMessage }}</v-alert>
							<v-alert v-if="importMessage" type="success">{{ importMessage }}</v-alert>
							<v-alert v-if="currentBillImport" type="success">{{ currentBillImport }}</v-alert>
						</template>
						<template v-slot:[`item.import`]="{ item }">
							<span class="success--text" v-if="item.imported === true"
								><v-icon>mdi-check</v-icon> {{ 'imported' }}</span
							>
							<span class="red--text" v-if="item.imported === false"
								><v-icon color="red">mdi-close</v-icon>{{ item.importMessage }}</span
							>
						</template>
					</v-data-table>
				</div>

				<!-- Customers / Invoices / Payments Table -->
				<div v-if="isImporting || isImportLoaded" class="mt-4 elevation-1">
					<v-data-table
						v-if="
							selectedSyncQBO &&
								['customers', 'invoices', 'payments', 'taxes'].includes(selectedSyncQBO.value)
						"
						:headers="
							selectedSyncQBO.value === 'customers'
								? customerTableHeader
								: selectedSyncQBO.value === 'invoices'
								? invoiceTableHeader
								: selectedSyncQBO.value === 'taxes'
								? taxTableHeader
								: paymentTableHeader
						"
						item-key="Id"
						:items="importData"
						class="v-table-middle-align"
						:single-select="false"
						show-select
						v-model="selectedImports"
						:loading="isSyncingImport || isImporting"
					>
						<template v-slot:top>
							<div class="d-flex justify-space-between align-center">
								<h1 class="text-h6 pa-4">{{ selectedSyncQBO ? selectedSyncQBO.text : '' }}</h1>
								<v-btn
									class="mr-2"
									color="primary"
									:disabled="!isImportLoaded"
									@click="
										selectedSyncQBO && ['customers', 'invoices', 'payments', 'taxes'].includes(selectedSyncQBO.value)
											? (showConfirmImport = true)
											: onImport
									"
									:loading="isSyncingImport"
								>
									<v-icon>mdi-import</v-icon>
									{{ 'Import' }} ({{ selectedImports.length }})
								</v-btn>
							</div>
							<v-alert v-if="importErrorMessage" type="error">{{ importErrorMessage }}</v-alert>
							<v-alert v-if="importMessage" type="success">{{ importMessage }}</v-alert>
						</template>
						<template v-slot:[`item.tag`]="{ item }">
							<span class="text-capitalize">{{ item.tag }}</span>
						</template>
						<template v-slot:[`item.import`]="{ item }">
							<span class="success--text" v-if="item.imported === true"
								><v-icon>mdi-check</v-icon> {{ 'imported' }}</span
							>
							<span class="red--text" v-if="item.imported === false"
								><v-icon color="red">mdi-close</v-icon>{{ item.importMessage }}</span
							>
						</template>
					</v-data-table>
				</div>
			</v-card-text>
		</v-card>

		<v-dialog
			v-model="showConfirmSync"
			max-width="400"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card :loading="isAutoSyncLoading">
				<v-card-title class="pa-0">
					<span>{{ 'Message' }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="showConfirmSync = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-sheet class="mt-6">
						<span class="header-2"
							>{{ 'Confirm Action' }}: {{ 'Auto Sync' }} {{ isAutoSynced ? 'Off' : 'On' }}</span
						>
					</v-sheet>
				</v-card-text>
				<v-divider></v-divider>
				<v-card-actions>
					<v-btn @click="onToggleSync" color="btn-blue" :loading="isAutoSyncLoading">{{ 'Yes' }}</v-btn>
					<v-btn @click="showConfirmSync = false" outlined text :disabled="isAutoSyncLoading">{{
						'No'
					}}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-dialog
			v-model="showConfirmImport"
			max-width="600"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card>
				<v-card-title class="pa-0">
					<span>{{ 'Message' }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="showConfirmImport = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-sheet class="mt-6">
						<v-alert v-if="selectedSyncQBO && (selectedSyncQBO.value === 'bills' || selectedSyncQBO.value === 'invoices' || selectedSyncQBO.value === 'payments')"  dark color="error" icon="mdi-alert" outlined prominent>
							<ul class="reminder-list">
								<li>
									<span v-if="selectedSyncQBO && selectedSyncQBO.value === 'bills'">
										Please make sure you imported all associated Vendor and Items before importing
										bills.
									</span>
									<span v-if="selectedSyncQBO && selectedSyncQBO.value === 'invoices'">
										Please make sure you have imported all associated Customer, Items and Taxes
										before importing invoices.
									</span>
									<span v-if="selectedSyncQBO && selectedSyncQBO.value === 'payments'">
										Please make sure you have imported all associated invoices.
									</span>
									<span v-else>
										Confirm to import.
									</span>
								</li>
							</ul>
						</v-alert>
						<v-alert v-else dark color="info" icon="mdi-alert" outlined prominent>
							<ul class="reminder-list">
								<li>
									<span>
										Are you sure you want to import selected entities?
									</span>
								</li>
							</ul>
						</v-alert>
					</v-sheet>
				</v-card-text>
				<v-divider></v-divider>
				<v-card-actions>
					<v-btn
						@click="
							() =>
								selectedSyncQBO && selectedSyncQBO.value === 'bills' ? onImportQBOBilling() : onImport()
						"
						color="btn-blue"
						>Import</v-btn
					>
					<v-btn
						class="text-capitalize"
						@click="showConfirmImport = false"
						outlined
						text
						:disabled="isAutoSyncLoading"
						>Cancel</v-btn
					>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
<script>
	// import { mapActions, mapGetters } from "vuex";
	import { apiErrorMessage, getErrorMessage } from '../../utils/globalMethods';
	import AkauntingService from '../../services/akaunting.service';
	export default {
		name: 'QuickbooksSyncAndImport',
		components: {},
		data() {
			return {
				isImporting: false,
				isImportLoaded: false,
				selectedSyncQBO: null,
				importMessage: '',
				selectedImports: [],
				syncQBOItems: [
					{
						value: 'account-based-items',
						text: 'Account-Based Item'
					},
					{
						value: 'items',
						text: 'Products & Services'
					},
					{
						value: 'taxes',
						text: 'Taxes'
					},
					{
						value: 'vendors',
						text: 'Vendors'
					},
					{
						value: 'customers',
						text: 'Customers'
					},
					{
						value: 'bills',
						text: 'Bills'
					},
					{
						value: 'invoices',
						text: 'Invoices'
					},
					{
						value: 'bill-payments',
						text: 'Billing Payments'
					},
					{
						value: 'payments',
						text: 'Payments'
					},
				],
				importData: [],
				isSyncingImport: false,
				isSyncingImportFailed: false,
				isSyncingImportSuccess: false,
				importErrorMessage: '',
				currentBillImport: '',
				isAutoSynced: false,
				isAutoSyncLoading: false,
				showConfirmSync: false,
				showConfirmImport: false
			};
		},
		beforeMount() {
			this.getSyncValue();
		},

		computed: {
			vendorTableHeader() {
				return [
					{
						text: 'Display Name',
						value: 'DisplayName',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Currency',
						value: 'CurrencyRef',
						class: 'text-uppercase th--text font-weight-bold'
					}
				];
			},

			itemTableHeader() {
				return [
					{
						text: 'Name',
						value: 'Name',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Type',
						value: 'Type',
						class: 'text-uppercase th--text font-weight-bold'
					}
				];
			},

			accountBasedItemTableHeader() {
				return [
					{
						text: 'Name',
						value: 'Name',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Currency',
						value: 'CurrencyRef',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Classification',
						value: 'Classification',
						class: 'text-uppercase th--text font-weight-bold'
					}
				];
			},

			billTableHeader() {
				return [
					{
						text: 'Document Number',
						value: 'DocNumber',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Due Date',
						value: 'DueDate',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Currency',
						value: 'CurrencyRef',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Amount',
						value: 'TotalAmt',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Balance',
						value: 'Balance',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: '',
						value: 'import'
					}
				];
			},

			billPaymentTableHeader() {
				return [
					{
						text: 'Document Number',
						value: 'DocNumber',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Currency',
						value: 'CurrencyRef',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Amount',
						value: 'TotalAmt',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Date',
						value: 'TxnDate',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: '',
						value: 'import'
					}
				];
			},

			customerTableHeader() {
				return [
					{
						text: 'Name',
						value: 'DisplayName',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Balance',
						value: 'Balance',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Currency',
						value: 'CurrencyRef',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: '',
						value: 'import'
					}
				];
			},

			invoiceTableHeader() {
				return [
					{
						text: 'Document Number',
						value: 'DocNumber',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Amount',
						value: 'TotalAmt',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Balance',
						value: 'Balance',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Invoice Date',
						value: 'TxnDate',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Due Date',
						value: 'DueDate',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: '',
						value: 'import'
					}
				];
			},
			paymentTableHeader() {
				return [
					{
						text: 'Date',
						value: 'TxnDate',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Amount',
						value: 'TotalAmt',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Currency',
						value: 'CurrencyRef',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: '',
						value: 'import'
					}
				];
			},

			taxTableHeader() {
				return [
					{
						text: 'Name',
						value: 'Name',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Description',
						value: 'Description',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: 'Tag',
						value: 'tag',
						class: 'text-uppercase th--text font-weight-bold'
					},
					{
						text: '',
						value: 'import'
					}
				];
			},

			hasSelectedImport() {
				return Boolean(this.selectedImports.length);
			},

			isMobile() {
				return this.$vuetify.breakpoint.mobile;
			}
		},

		methods: {
			async onImport() {
				if (this.isSyncingImport) {
					return;
				}

				if (['invoices', 'payments'].includes(this.selectedSyncQBO.value) && !this.showConfirmImport) {
					this.showConfirmImport = true;
					return;
				}

				this.showConfirmImport = false;

				this.isSyncingImport = true;
				this.isSyncingImportFailed = false;
				this.isSyncingImportSuccess = false;
				this.importMessage = '';
				this.importErrorMessage = '';
				try {
					let response = {};
					switch (this.selectedSyncQBO.value) {
						case 'account-based-items':
							response = await AkauntingService.importQBOAccountBasedItems({ run_import: 1 });
							break;
						case 'items':
							response = await AkauntingService.importQBOItems({ run_import: 1 });
							break;
						case 'vendors':
							response = await AkauntingService.importQBOVendors({ run_import: 1 });
							break;
						case 'invoices':
						case 'customers':
						case 'payments':
						case 'taxes':
							{
								if (this.selectedSyncQBO.value === 'customers') {
									response = await AkauntingService.importQBOCustomers({
										entities: this.selectedImports
									});
								}

								if (this.selectedSyncQBO.value === 'invoices') {
									response = await AkauntingService.importQBOInvoices({
										entities: this.selectedImports
									});
								}

								if (this.selectedSyncQBO.value === 'payments') {
									response = await AkauntingService.importQBOPayments({
										entities: this.selectedImports
									});
								}

								if (this.selectedSyncQBO.value === 'taxes') {
									response = await AkauntingService.importQBOTaxes({
										entities: this.selectedImports
									});
								}
								const data = response.data?.data?.data || [];
								const updateImportedData = this.importData.map((record) => {
									const responseData = data.find((record2) => record2.Id === record.Id);
									return {
										...record,
										imported: responseData ? responseData.success : null,
										importMessage: responseData ? responseData.error : null
									};
								});
								this.importData = updateImportedData;
							}
							break;
					}
					this.importMessage = response.data.data.message || response.data.message || '';
					this.isSyncingImport = false;
					this.isSyncingImportSuccess = true;
				} catch (error) {
					this.isSyncingImportFailed = true;
					this.isSyncingImport = false;
					this.importErrorMessage = 'Failed to import.';
				}
			},

			async fetchSyncQBO() {
				if (this.isImporting) {
					return;
				}
				this.isImporting = true;
				this.isImportLoaded = false;
				this.importData = [];
				this.selectedImports = [];
				this.importMessage = '';
				this.importErrorMessage = '';
				try {
					let response = {};

					switch (this.selectedSyncQBO.value) {
						case 'account-based-items':
							response = await AkauntingService.importQBOAccountBasedItems({});
							break;
						case 'items':
							response = await AkauntingService.importQBOItems({});
							break;
						case 'vendors':
							response = await AkauntingService.importQBOVendors({});
							break;
						case 'bills':
							response = await AkauntingService.fetchImportQBOBills({});
							break;
						case 'bill-payments':
							response = await AkauntingService.fetchImportQBOBillPayments({});
							break;
						case 'customers':
							response = await AkauntingService.fetchImportQBOCustomers({});
							break;
						case 'invoices':
							response = await AkauntingService.fetchImportQBOInvoices({});
							break;
						case 'payments':
							response = await AkauntingService.fetchQBOPayments();
							break;
						case 'taxes':
							response = await AkauntingService.fetchQBOTaxes();
							break;
					}

					this.isImporting = false;
					this.isImportLoaded = true;
					this.importMessage = response.data.message;
					const data = response.data?.data?.data || {};
					this.importData = Object.keys(data).map((key) => data[key]);
				} catch (error) {
					this.isImporting = false;
					this.isImportLoaded = true;
				}
			},

			async onImportQBOBilling() {
				if (this.isSyncingImport || this.selectedImports.length === 0) {
					return;
				}

				if (this.selectedSyncQBO.value === 'bills' && !this.showConfirmImport) {
					this.showConfirmImport = true;
					return;
				}

				this.showConfirmImport = false;

				this.isSyncingImport = true;
				this.isSyncingImportFailed = false;
				this.isSyncingImportSuccess = false;
				this.importMessage = '';
				this.importErrorMessage = '';
				this.currentBillImport = '';
				try {
					const requests = this.selectedImports.map(async (entity) => {
						// this.currentBillImport = entity.DocNumber;
						return this.selectedSyncQBO.value === 'bills'
							? AkauntingService.importQBOBills({ entity })
							: AkauntingService.importQBOBillPayments({ entity });
						// try {
						// return {success: true, id: entity.Id};
						// } catch(error) {
						//   return {success: false, id: entity.Id, message: getErrorMessage(error)};
						// }
					});

					const responses = await Promise.allSettled(requests);

					const response = responses.map((record) => {
						if (record.status === 'fulfilled') {
							const data = JSON.parse(record.value.config.data);
							return { success: true, id: data.entity.Id };
						} else if (record.status === 'rejected') {
							const data = JSON.parse(record.reason.config.data);
							return {
								success: false,
								id: data.entity.Id,
								message: getErrorMessage(record.reason.response.data)
							};
						}
					});

					// const response = await Promise.all(requests);

					const ids = response.filter((record) => record.success).flatMap((record) => record.id);
					const failedIds = response.filter((record) => !record.success).flatMap((record) => record.id);
					const failedMessages = {};
					response
						.filter((record) => !record.success)
						.forEach((record) => {
							failedMessages[record.id] = record.message;
						});

					this.importData = this.importData.map((record) => {
						if (failedIds.includes(record.Id)) {
							return { ...record, importMessage: failedMessages[record.Id], imported: false };
						}
						if (ids.includes(record.Id)) {
							return { ...record, imported: true };
						}
						return { ...record, importMessage: null, imported: null };
					});

					this.selectedImports = [];

					this.importMessage =
						ids.length +
						' ' +
						'Record(s) imported.'.toLowerCase() +
						' ' +
						failedIds.length +
						' ' +
						'Record(s) failed to import.'.toLowerCase();
					this.isSyncingImport = false;
					this.isSyncingImportSuccess = true;
					this.showConfirmImport = false;
				} catch (error) {
					console.log(error);
					this.isSyncingImportFailed = true;
					this.isSyncingImport = false;
					this.importErrorMessage = 'Failed to import.';
					this.showConfirmImport = false;
				}
			},

			async getSyncValue() {
				this.isAutoSyncLoading = true;
				try {
					const { data } = await AkauntingService.getAutoSync();
					if (data.data && data.data.value === '1') {
						this.isAutoSynced = true;
					}
					this.isAutoSyncLoading = false;
				} catch (error) {
					this.isAutoSyncLoading = false;
					apiErrorMessage(error);
				}
			},

			onConfirmSync() {
				this.showConfirmSync = true;
			},

			async onToggleSync() {
				if (this.isAutoSyncLoading) {
					return;
				}
				this.isAutoSyncLoading = true;
				try {
					this.isAutoSynced = !this.isAutoSynced;
					this.showConfirmSync = false;
					await AkauntingService.setAutoSync({ auto_sync: this.isAutoSynced });
					this.isAutoSyncLoading = false;
				} catch (error) {
					this.isAutoSyncLoading = false;
					this.showConfirmSync = false;
					apiErrorMessage(error);
				}
			}
		}
	};
</script>
<style lang="scss" scoped>
	.w-100 {
		width: 100%;
	}

	.v-list-item {
		min-height: 36px;
	}
	.row-stripe:nth-child(odd) {
		background-color: rgb(243, 243, 243);
	}

	.reminder-list li {
		list-style-type: disc;
	}
</style>
