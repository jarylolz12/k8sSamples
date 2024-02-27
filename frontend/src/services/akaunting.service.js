import MainService from './main.service';

class AkauntingService extends MainService {
	constructor() {
		super();
	}

	loginUser(payload) {
		return super.post('/login', payload);
	}

	exchangeToken(payload) {
		return super.post('/v1/quickbooks/exchange-token', payload);
	}

	/* getItemsByCompany(params= {company_id:1}){
    let url = '/api/akaunting/items';        
    return super.get(url, params);
  } */

	createAccountingCompany(payload) {
		return super.post('/v1/create-accounting-company', payload);
	}

	pingAccounting() {
		return super.get('/v1/accounting/ping');
	}

	pingQBO() {
		return super.get('/v1/accounting/ping-qbo');
	}

	getQBOLogin() {
		return super.post('/v1/quickbooks/connect', {});
	}

	signOutQBO() {
		return super.post('/v1/quickbooks/signout', {});
	}

	getBills(data) {
		return super.get('/v1/accounting/bills/list', data);
	}

	updateBill(id, payload) {
		return super.patch(`/v1/accounting/bills/update/${id}`, payload);
	}

	// Customer API
	getCustomers(data) {
		return super.get('/v1/accounting/customers/list', data);
	}

	deleteCustomer(id, data) {
		return super.remove('/v1/accounting/customers/delete/' + id, data);
	}

	createCustomer(payload) {
		return super.post('/v1/accounting/customers/create', payload);
	}

	updateCustomer(payload) {
		return super.patch(`/v1/accounting/customers/update/${payload.id}`, payload);
	}
	getCustomer(id) {
		return super.get(`/v1/accounting/customers/show/${id}`);
	}

	// Invoice API
	createInvoice(payload) {
		return super.post('/v1/accounting/invoices/create', payload, { 'Content-Type': 'multipart/form-data' });
	}

	// payload is type of FormData
	updateInvoice(id, payload) {
		// payload.append('_method', 'PATCH');
		return super.post(`/v1/accounting/invoices/update/${id}`, payload, { 'Content-Type': 'multipart/form-data' });
	}

	getInvoices(data) {
		return super.get('/v1/accounting/invoices/list', data);
	}

	deleteInvoice(id, data) {
		return super.remove('/v1/accounting/invoices/delete/' + id, data);
	}

	getInvoice(id) {
		return super.get('/v1/accounting/invoices/show/' + id);
	}

	getInvoiceAttachments(id) {
		return super.get(`/v1/accounting/attachment/get-by-document?document_id=${id}&type=invoice`);
	}

	downloadInvoiceAttachment(id) {
		return super.get(`/v1/accounting/attachment/download/${id}`, {}, {}, { responseType: 'blob' });
	}

	downloadInvoiceAttachmentV2(id) {
		return super.get(`/v1/accounting/attachment/download-v2/${id}`, {}, {}, { responseType: 'blob' });
	}

	markInvoiceAsSent(id) {
		return super.get(`/v1/accounting/invoices/${id}/mark-sent`);
	}

	markInvoiceAsCancelled(id) {
		return super.get(`/v1/accounting/invoices/${id}/mark-cancelled`);
	}

	//vendors
	getVendors(data) {
		return super.get('/v1/accounting/vendors/list', data);
	}

	getVendor(id) {
		return super.get('/v1/accounting/vendors/show/' + id);
	}

	disableVendor(id, data) {
		return super.remove('/v1/accounting/vendors/delete/' + id, data);
	}

	getVendorBills(vendorId,data) {
		return super.get(`/v1/accounting/vendors/get-bills/${vendorId}`,data);
	}

	////// STATEMENT API //////
	getStatement(id) {
		return super.get(`/v1/accounting/statement/get/${id}`);
	}

	getStatements(data) {
		return super.get('/v1/accounting/statement/list',data);
	}

	deleteStatement(id) {
		return super.remove('/v1/accounting/statement/delete/' + id);
	}

	createStatement(payload) {
		return super.post('/v1/accounting/statement/create', payload);
	}

	updateStatement(payload) {
		return super.patch(`/v1/accounting/statement/update/${payload.id}`, payload);
	}

	getStatementOpeningBalance(id,data){
		return super.get(`/v1/accounting/statement/get-opening-balance/${id}`,data);
	}

	// Bills API

	deleteBill(id, data) {
		return super.remove('/v1/accounting/bills/delete/' + id, data);
	}

	getBill(id) {
		return super.get('/v1/accounting/bills/show/' + id);
	}

	receiveBill(id, data) {
		return super.get('/v1/accounting/bills/' + id + '/received', data);
	}

	cancelBill(id, data) {
		return super.get('/v1/accounting/bills/' + id + '/cancelled', data);
	}

	getAkauntingItemCategory() {
		return super.get('/v1/akaunting/categories/item');
	}

	getAkauntingIncomeCategory() {
		return super.get('/v1/akaunting/categories/income');
	}

	// Vendor API

	createVendor(payload) {
		return super.post('/v1/accounting/vendors/create', payload);
	}

	updateVendor(payload) {
		return super.patch(`/v1/accounting/vendors/update/${payload.id}`, payload);
	}

	getCurrencies() {
		return super.get('/v1/akaunting/currencies');
	}

	createBilling(payload) {
		return super.post('/v1/accounting/bills/create', payload);
	}

	getAkauntingCategoryExpense() {
		return super.get('/v1/akaunting/categories/expense');
	}

	getQuickbooksItemsByCompany(company_id) {
		return super.get('/akaunting/items?company_id=' + company_id);
	}

	getAkauntingAccounts() {
		return super.get('/v1/akaunting/get-de-accounts');
	}

	getDEaccounts() {
		return super.get('/v1/accounting/de/accounts/get-accounts');
	}

	getDEaccountsAndTypes() {
		return super.get('/v1/accounting/de/accounts/get-accounts-and-types');
	}

	getQBOInventoryAssetAccounts() {
		return super.get('/v1/quickbooks/inventory-item-asset-accounts');
	}

	getQBOInventoryExpenseAccounts() {
		return super.get('/v1/quickbooks/inventory-item-expense-accounts');
	}

	getQBOInventoryIncomeAccounts() {
		return super.get('/v1/quickbooks/inventory-item-income-accounts');
	}

	getQBOIncomeAccounts() {
		return super.get('/v1/quickbooks/income-accounts');
	}

	getQBOExpenseAccounts() {
		return super.get('/v1/quickbooks/expense-accounts');
	}

	// Item API

	createItem(payload) {
		return super.post('/v1/accounting/items/store', payload);
	}

	updateItem(payload) {
		return super.patch(`/v1/accounting/items/update/${payload.id}`, payload);
	}

	searchVendors(data) {
		return super.get('/v1/accounting/vendors/list', data);
	}

	searchItems(data) {
		return super.get('/v1/accounting/items/list', data);
	}

	getItems(data) {
		return super.get('/v1/accounting/items/list', data);
	}

	disableItem(id, data) {
		return super.remove('/v1/accounting/items/delete/' + id, data);
	}

	// Category Based Items API
	getCategoryBasedItems(data) {
		return super.get('/v1/accounting/items/qbo-account-based/list', data);
	}

	createCategoryBasedItem(payload) {
		return super.post('/v1/accounting/items/qbo-account-based/store', payload);
	}

	updateCategoryBasedItem(payload) {
		return super.patch(`/v1/accounting/items/qbo-account-based/update/${payload.id}`, payload);
	}

	disableAccountBasedItem(id, data) {
		return super.patch('/v1/accounting/items/qbo-account-based/disable/' + id, data);
	}

	// Currency API

	getAccountingCurrencies(data) {
		return super.get('/v1/accounting/currencies/list', data);
	}

	createCurrencyForm(payload) {
		return super.post('/v1/accounting/currencies/create', payload);
	}

	updateCurrency(payload) {
		return super.patch(`/v1/accounting/currencies/update/${payload.id}`, payload);
	}

	deleteCurrency(id) {
		return super.remove(`/v1/accounting/currencies/delete/${id}`);
	}

	getTerms() {
		return super.get('/v1/quickbooks/terms');
	}

	// Accounts API
	getAccounts(data) {
		return super.get('/v1/accounting/accounts/list', data);
	}

	createAccountForm(payload) {
		return super.post('/v1/accounting/accounts/create', payload);
	}

	updateAccountForm(payload) {
		return super.patch(`/v1/accounting/accounts/update/${payload.id}`, payload);
	}

	deleteAccount(id, data) {
		return super.remove('/v1/accounting/accounts/delete/' + id, data);
	}

	enableAccount(id) {
		return super.get(`/v1/accounting/accounts/${id}/enable`);
	}

	disableAccount(id) {
		return super.get(`/v1/accounting/accounts/${id}/disable`);
	}

	getQBOAccountTypes(data) {
		return super.get(`/v1/quickbooks/default-account-types`, data);
	}

	getQBOParentAccounts() {
		return super.get(`/v1/quickbooks/accounts`);
	}

	getAccountTransactions(id) {
		return super.get(`/v1/accounting/accounts/${id}/transactions`);
	}

	getAccountsForSelection(data) {
		return super.get('/v1/accounting/accounts/get-for-selection', data);
	}

	// Import QBO API
	importQBOVendors(data) {
		return super.get(`/v1/accounting/import/qbo/vendors/check`, data);
	}

	importQBOItems(data) {
		return super.get(`/v1/accounting/import/qbo/items/check`, data);
	}

	importQBOAccountBasedItems(data) {
		return super.get(`/v1/accounting/import/qbo/account-based-items/check`, data);
	}

	fetchImportQBOBills(data) {
		return super.get(`/v1/accounting/import/qbo/bills/check`, data);
	}

	importQBOBills(data) {
		return super.post(`/v1/accounting/import/qbo/bills/run`, data);
	}

	fetchImportQBOBillPayments(data) {
		return super.get(`/v1/accounting/import/qbo/bill-payments/check`, data);
	}

	importQBOBillPayments(data) {
		return super.post(`/v1/accounting/import/qbo/bill-payments/run`, data);
	}

	getAutoSync() {
		return super.get(`/v1/accounting/settings/qbo/auto-sync`);
	}

	setAutoSync(data) {
		return super.post(`/v1/accounting/settings/qbo/auto-sync`, data);
	}

	fetchImportQBOCustomers(data) {
		return super.get(`/v1/accounting/import/qbo/customers/check`, data);
	}

	importQBOCustomers(data) {
		return super.post(`/v1/accounting/import/qbo/customers/run`, data);
	}

	fetchImportQBOInvoices(data) {
		return super.get(`/v1/accounting/import/qbo/invoices/check`, data);
	}

	importQBOInvoices(data) {
		return super.post(`/v1/accounting/import/qbo/invoices/run`, data);
	}

	fetchQBOPayments() {
		return super.get('/v1/accounting/import/qbo/payments/check');
	}

	importQBOPayments(data) {
		return super.post('/v1/accounting/import/qbo/payments/run', data);
	}

	fetchQBOTaxes() {
		return super.get('/v1/accounting/import/qbo/taxes/check');
	}

	importQBOTaxes(data) {
		return super.post('/v1/accounting/import/qbo/taxes/run', data);
	}

	// Tax Code API
	getTaxCodes() {
		return super.get('/v1/accounting/settings/tax-codes/list');
	}

	// Tax Rate API
	getTaxRates(data) {
		return super.get('/v1/accounting/settings/taxes/list', data);
	}

	createTaxRate(data) {
		return super.post('/v1/accounting/settings/taxes/create', data);
	}

	updateTaxRate(data) {
		return super.patch(`/v1/accounting/settings/taxes/update/${data.id}`, data);
	}

	deleteTaxRate(data) {
		return super.remove(`/v1/accounting/settings/taxes/delete/${data.id}`, data);
	}

	// Category API
	getCategories(data) {
		return super.get(`/v1/accounting/categories/list`, data);
	}

	createCategory(payload) {
		return super.post('/v1/accounting/categories/create', payload);
	}

	updateCategory(payload) {
		return super.patch(`/v1/accounting/categories/update/${payload.id}`, payload);
	}

	deleteCategory(id, data) {
		return super.remove('/v1/accounting/categories/delete/' + id, data);
	}

	// Chart Account API

	getCoaAccountsAndTypes(data) {
		return super.get('/v1/accounting/de/accounts/get-accounts-and-types', data);
	}

	getCoaAccountsAndDefaults(data) {
		return super.get('/v1/accounting/de/accounts/get-accounts-and-defaults', data);
	}

	getAccountAndTypes(data) {
		return super.get('/v1/accounting/de/accounts/get-accounts-and-defaults', data);
	}

	getChartAccounts(data) {
		return super.get(`/v1/accounting/de/accounts/list`, data);
	}

	createCoaAccount(payload) {
		return super.post('/v1/accounting/de/accounts/create', payload);
	}

	updateCoaAccount(payload) {
		return super.patch(`/v1/accounting/de/accounts/update/${payload.id}`, payload);
	}

	deleteCoaAccount(id, data) {
		return super.remove('/v1/accounting/de/accounts/delete/' + id, data);
	}

	enableCoaAccount(id) {
		return super.get(`/v1/accounting/de/accounts/enable/${id}`);
	}

	disableCoaAccount(id) {
		return super.get(`/v1/accounting/de/accounts/disable/${id}`);
	}

	// Invoice Payments API
	getInvoicePayments(data) {
		return super.get('/v1/accounting/transactions/income/list', data);
	}

	createInvoicePayment(payload) {
		return super.post('/v1/accounting/transactions/income/create', payload);
	}

	updateInvoicePayment(id,payload) {
		return super.post(`/v1/accounting/transactions/income/update/${id}`, payload);
	}

	getCustomerOpenInvoices(customerId) {
		return super.get(`/v1/accounting/customers/get-open-invoices/${customerId}`);
	}

	getCustomerInvoices(customerId,data) {
		return super.get(`/v1/accounting/customers/get-invoices/${customerId}`,data);
	}

	getAccountingDefaults() {
		return super.get('/v1/accounting/settings/defaults/list');
	}

	getDoubleEntries() {
		return super.get('/v1/accounting/settings/double-entry/list');
	}

	// Bill Payments API
	getBillPayments(data) {
		return super.get('/v1/accounting/transactions/expense/list', data);
	}

	getVendorOpenBills(customerId) {
		return super.get(`/v1/accounting/vendors/get-open-bills/${customerId}`);
	}

	createBillPayment(payload) {
		return super.post('/v1/accounting/transactions/expense/create', payload);
	}

	updateBillPayment(id,payload) {
		return super.post(`/v1/accounting/transactions/expense/update/${id}`, payload);
	}

	// Transactions API
	deleteTransaction(data){
		return super.remove(`/v1/accounting/transactions/delete/${data.id}`, data);
	}

	getTransaction(id){
		return super.get(`/v1/accounting/transactions/show/${id}`);
	}

	// Reports
	getReportsList(data){
		return super.get(`/v1/accounting/reports/list`, data);
	}

	getReport(id){
		return super.get(`/v1/accounting/reports/read/`+ id);
	}

	//Journals
	getJournalEntries(data){
		return super.get(`/v1/accounting/journal-entries/list`, data);
	}

	createJournalEntry(payload){
		return super.post(`/v1/accounting/journal-entries/create`, payload);
	}

	updateJournalEntry(id,payload){
		return super.patch(`/v1/accounting/journal-entries/update/${id}`, payload);
	}

	deleteJournalEntry(id,data){
		return super.remove(`/v1/accounting/journal-entries/delete/${id}`, data);
	}

	// Reconciliation
	getReconciliations(data){
		return super.get(`/v1/accounting/reconciliations/list`,data);
	}

	createReconciliation(payload){
		return super.post(`/v1/accounting/reconciliations/create`, payload);
	}

	updateReconciliation(id,payload){
		return super.patch(`/v1/accounting/reconciliations/update/${id}`, payload);
	}

	deleteReconciliation(id,data){
		return super.remove(`/v1/accounting/reconciliations/delete/${id}`, data);
	}

	getAccountTransactionsByPeriod(id,payload){
		return super.get(`/v1/accounting/reconciliations/get-transactions/${id}`,payload)
	}
}

export default new AkauntingService();
