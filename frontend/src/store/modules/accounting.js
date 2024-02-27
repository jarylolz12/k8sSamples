/*eslint no-useless-catch: "off"*/
/*eslint no-empty-pattern: "off"*/

import AkauntingService from '../../services/akaunting.service';

const storageData = localStorage.getItem('shifl.accounting');

const state = () =>
  JSON.parse(storageData) || {
    itemList: [],
    expenseAccountLists: [],
    incomeAccountLists: [],
    QBOExpenseAccountLists: [],
    QBOIncomeAccountLists: [],
    QBOInventoryExpenseAccountLists: [],
    QBOInventoryIncomeAccountLists: [],
    QBOInventoryAssetAccountLists: [],
    isQBOAccountLoading: false,
    currencies: [],
    companyInformation: null,
    QBOInformation: null,
    isCheckingAccounting: true,
    isCheckingQBO: false,
    accountingLastPath: null,
    user: null,
    quickBooksToken: {},
    currentAccountingPageIndex: 0,
    defaults: null,
  };

// MUTATIONS
const mutations = {
  clearState: (state, payload) => {
    Object.keys(payload).forEach((key) => {
      state[key] = payload[key];
    });
  },

  updateCurrentPageIndex: (state, payload) => {
    state.currentAccountingPageIndex = payload;
  },

  updateAccountingCompany: (state, payload) => {
    state.companyInformation = payload;
  },

  updateQBO: (state, payload) => {
    state.QBOInformation = payload;
  },

  updateQuickbooksItemList: (state, payload) => {
    state.itemList = payload;
  },

  updateAkauntingExpenseAccountLists: (state, payload) => {
    state.expenseAccountLists = payload;
  },

  updateAkauntingIncomeAccountLists: (state, payload) => {
    state.incomeAccountLists = payload;
  },

  updateQBOIncomeAccountLists: (state, payload) => {
    state.QBOIncomeAccountLists = payload;
  },

  updateQBOExpenseAccountLists: (state, payload) => {
    state.QBOExpenseAccountLists = payload;
  },

  updateQBOInventoryIncomeAccountLists: (state, payload) => {
    state.QBOInventoryIncomeAccountLists = payload;
  },

  updateQBOInventoryExpenseAccountLists: (state, payload) => {
    state.QBOInventoryExpenseAccountLists = payload;
  },

  updateQBOAssetAccountLists: (state, payload) => {
    state.QBOInventoryAssetAccountLists = payload;
  },

  updateisQBOAccountLoading: (state, payload) => {
    state.isQBOAccountLoading = payload;
  },

  updateCurrencies: (state, payload) => {
    state.currencies = payload;
  },

  updateAccountingLastPath: (state, payload) => {
    state.accountingLastPath = payload;
  },

  updateCheckingAccounting: (state, payload) => {
    state.isCheckingAccounting = payload;
  },

  updateCheckingQBO: (state, payload) => {
    state.isCheckingQBO = payload;
  },

  updateUser: (state, payload) => {
    state.user = payload;
  },

  setDefaults: (state, payload) => {
    state.defaults = payload;
  },
};

// GETTERS
const getters = {
  getCurrentPageIndex(state) {
    return state.currentAccountingPageIndex;
  },
  isQBOEnabled(state) {
    return state.companyInformation &&
      Boolean(state.companyInformation?.accounting_company?.qbo_realm_id) &&
      state.QBOInformation
      ? 1
      : 0;
  },

  homeCurrency(state) {
    return state.companyInformation?.accounting_details?.currency || 'USD';
  },

  getDEtypes() {
    return {
      'double-entry::types.current_asset': 'Current Asset',
      'double-entry::types.fixed_asset': 'Fixed Asset',
      'double-entry::types.inventory': 'Inventory',
      'double-entry::types.non_current_asset': 'Non-current Asset',
      'double-entry::types.prepayment': 'Pre-Payment',
      'double-entry::types.bank_cash': 'Bank & Cash',
      'double-entry::types.current_liability': 'Current Liability',
      'double-entry::types.liability': 'Liability',
      'double-entry::types.non_current_liability': 'Non-current Liability',
      'double-entry::types.depreciation': 'Depreciation',
      'double-entry::types.direct_costs': 'Direct Costs',
      'double-entry::types.expense': 'Expense',
      'double-entry::types.revenue': 'Revenue',
      'double-entry::types.sales': 'Sales',
      'double-entry::types.other_income': 'Other Income',
      'double-entry::types.equity': 'Equity',
      'double-entry::types.tax': 'Tax'
    };
  },

  getUserToken: (state) => state?.user?.token?.accessToken
};

// ACTIONS
const actions = {
  clearData: ({ commit }) => {
    localStorage.removeItem('shifl.accounting');
    commit('clearState', {
      itemList: [],
      expenseAccountLists: [],
      incomeAccountLists: [],
      QBOExpenseAccountLists: [],
      QBOIncomeAccountLists: [],
      QBOInventoryExpenseAccountLists: [],
      QBOInventoryIncomeAccountLists: [],
      QBOInventoryAssetAccountLists: [],
      isQBOAccountLoading: false,
      currencies: [],
      companyInformation: null,
      QBOInformation: null,
      isCheckingAccounting: true,
      isCheckingQBO: false,
      accountingLastPath: null,
      user: null,
      quickBooksToken: {},
      currentAccountingPageIndex: 0,
      defaults: null,
    });
  },

  clearAccountingCompanyData: ({ commit }) => {
    commit('clearState', {
      itemList: [],
      expenseAccountLists: [],
      incomeAccountLists: [],
      QBOExpenseAccountLists: [],
      QBOIncomeAccountLists: [],
      QBOInventoryExpenseAccountLists: [],
      QBOInventoryIncomeAccountLists: [],
      QBOInventoryAssetAccountLists: [],
      isQBOAccountLoading: false,
      currencies: [],
      companyInformation: null,
      QBOInformation: null,
      isCheckingAccounting: true,
      isCheckingQBO: false,
      accountingLastPath: null,
      quickBooksToken: {},
      currentAccountingPageIndex: 0,
      defaults: null,
    });
  },

  setAccountingPageIndex: ({ commit }, payload) => {
    commit('updateCurrentPageIndex', payload);
  },

  loginUser: async ({ commit }, payload) => {
    try {
      const { data } = await AkauntingService.loginUser(payload);
      commit('updateUser', data?.data);
    } catch (error) {
      commit('SET_ERROR_MESSAGE', 'Accounting login error', { root: true });
      throw error;
    }
  },

  setQuickbooksAccessToken: async ({}, { code, realmId }) => {
    try {
      return await AkauntingService.exchangeToken({
        code,
        realmId
      });
    } catch (error) {
      throw error;
    }
  },

  createAccountingCompany: async ({ commit }, payload) => {
    try {
      const { data } = await AkauntingService.createAccountingCompany(payload);
      commit('updateAccountingCompany', data.data || null);
      if(data.data){
        return data;
      }
    } catch (error) {
      throw error;
    }
  },

  getQuickbooksLoginUrl: async () => {
    try {
      const { data } = await AkauntingService.getQBOLogin();

      // Determine here if the returned data contains URL
      if (data.data && data.data.authUrl) {
        return data.data.authUrl;
      }

      return null;
    } catch (error) {
      throw error;
    }
  },

  signOutQBO: async () => {
    try {
      const { data } = await AkauntingService.signOutQBO();
      return data;
    } catch (error) {
      throw error;
    }
  },

  getAccounting: async ({ commit }) => {
    try {
      commit('updateCheckingAccounting', true);
      const { data } = await AkauntingService.pingAccounting();
      commit('updateCheckingAccounting', false);
      return data;
    } catch (error) {
      commit('updateCheckingAccounting', false);
      throw error;
    }
  },

  getQBOConnection: async ({ commit }) => {
    try {
      commit('updateCheckingQBO', true);
      const { data } = await AkauntingService.pingQBO();
      commit('updateCheckingQBO', false);
      return data;
    } catch (error) {
      commit('updateCheckingQBO', false);
      throw error;
    }
  },

  getAkauntingCategoryExpense: async ({}) => {
    try {
      const { data } = await AkauntingService.getAkauntingCategoryExpense();
      return data?.data || [];
    } catch (error) {
      throw error;
    }
  },

  getQuickbooksItemsByCompany: async ({ commit }, company_id) => {
    try {
      const res = await AkauntingService.getQuickbooksItemsByCompany(company_id);
      const responseData = res.data;
      if (responseData.data.length > 0) {
        commit('updateQuickbooksItemList', responseData.data);
      }
    } catch (error) {
      throw error;
    }
  },

  getAkauntingAccounts: async ({ commit }) => {
    try {
      const { data } = await AkauntingService.getAkauntingAccounts();
      if (data.data && data.data.income_accounts) {
        commit('updateAkauntingIncomeAccountLists', data.data.income_accounts);
      }
      if (data.data && data.data.expense_accounts) {
        commit('updateAkauntingExpenseAccountLists', data.data.expense_accounts);
      }
    } catch (error) {
      // Do nothing. Log this error in the future
    }
  },

  getQBOInventoryAssetAccounts: async ({ commit }) => {
    try {
      const response = await AkauntingService.getQBOInventoryAssetAccounts();
      commit('updateQBOAssetAccountLists', response.data || []);
    } catch (error) {
      // Do nothing
    }
  },

  getQBOInventoryExpenseAccounts: async ({ commit }) => {
    try {
      const response = await AkauntingService.getQBOInventoryExpenseAccounts();
      commit('updateQBOInventoryExpenseAccountLists', response.data || []);
    } catch (error) {
      // Do nothing
    }
  },

  getQBOInventoryIncomeAccounts: async ({ commit }) => {
    try {
      const response = await AkauntingService.getQBOInventoryIncomeAccounts();
      commit('updateQBOInventoryIncomeAccountLists', response.data || []);
    } catch (error) {
      // Do nothing
    }
  },

  getQBOIncomeAccounts: async ({ commit }) => {
    try {
      const response = await AkauntingService.getQBOIncomeAccounts();
      commit('updateQBOIncomeAccountLists', response.data || []);
    } catch (error) {
      console.log(error);
    }
  },

  getQBOExpenseAccounts: async ({ commit }) => {
    try {
      const response = await AkauntingService.getQBOExpenseAccounts();
      commit('updateQBOExpenseAccountLists', response.data || []);
    } catch (error) {
      console.log(error);
    }
  },

  getQBOAccounts: async ({ commit, dispatch }, QBOItemType) => {
    try {
      commit('updateisQBOAccountLoading', true);

      if (QBOItemType === 'Inventory') {
        await Promise.all([
          dispatch('getQBOInventoryAssetAccounts'),
          dispatch('getQBOInventoryExpenseAccounts'),
          dispatch('getQBOInventoryIncomeAccounts')
        ]);
      }

      if (['NonInventory', 'Service'].includes(QBOItemType)) {
        await Promise.all([dispatch('getQBOIncomeAccounts'), dispatch('getQBOExpenseAccounts')]);
      }

      commit('updateisQBOAccountLoading', false);
    } catch (error) {
      // Do nothing. Log this error in the future
      commit('updateisQBOAccountLoading', false);
    }
  },

  // Item API
  createItemForm: async ({}, payload) => {
    try {
      return await AkauntingService.createItem(payload);
    } catch (error) {
      throw error;
    }
  },

  updateItemForm: async ({}, payload) => {
    try {
      return await AkauntingService.updateItem(payload);
    } catch (error) {
      throw error;
    }
  },

  disableItem: async ({ getters }, payload) => {
    try {
      const { data } = await AkauntingService.disableItem(payload, { qbo_enabled: getters.isQBOEnabled });
      return data;
    } catch (error) {
      throw error;
    }
  },

  getItemsData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getItems(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  // Category Based Items API

  getCategoryBasedItemData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getCategoryBasedItems(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  createCategoryBasedItemForm: async ({}, payload) => {
    try {
      return await AkauntingService.createCategoryBasedItem(payload);
    } catch (error) {
      throw error;
    }
  },

  updateCategoryBasedItemForm: async ({}, payload) => {
    try {
      return await AkauntingService.updateCategoryBasedItem(payload);
    } catch (error) {
      throw error;
    }
  },

  getQBOParentAccounts: async () => {
    try {
      const response = await AkauntingService.getQBOParentAccounts();
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  // Customers API

  getCustomersData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getCustomers(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  createCustomerForm: async ({}, payload) => {
    try {
      return await AkauntingService.createCustomer(payload);
    } catch (error) {
      throw error;
    }
  },

  updateCustomerForm: async ({}, payload) => {
    try {
      return await AkauntingService.updateCustomer(payload);
    } catch (error) {
      throw error;
    }
  },

  deleteCustomer: async ({ getters }, payload) => {
    try {
      return await AkauntingService.deleteCustomer(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  // Vendors API

  getVendorsData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getVendors(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  createVendorForm: async ({}, payload) => {
    try {
      return await AkauntingService.createVendor(payload);
    } catch (error) {
      throw error;
    }
  },

  updateVendorForm: async ({}, payload) => {
    try {
      return await AkauntingService.updateVendor(payload);
    } catch (error) {
      throw error;
    }
  },

  getCurrencies: async ({ commit }) => {
    try {
      const response = await AkauntingService.getCurrencies();
      commit('updateCurrencies', response?.data?.data?.data || []);
    } catch (error) {
      return;
    }
  },

  disableVendor: async ({ getters }, payload) => {
    try {
      return await AkauntingService.disableVendor(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  // Billing API
  createBillingForm: async ({}, payload) => {
    try {
      return await AkauntingService.createBilling(payload);
    } catch (error) {
      throw error;
    }
  },

  getBillsData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getBills(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  updateBillForm: async ({}, payload) => {
    try {
      return await AkauntingService.updateBill(payload.id, payload);
    } catch (error) {
      throw error;
    }
  },

  deleteBill: async ({ getters }, payload) => {
    try {
      return await AkauntingService.deleteBill(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  getBill: async ({}, payload) => {
    try {
      return await AkauntingService.getBill(payload);
    } catch (error) {
      throw error;
    }
  },

  billReceived: async ({ getters }, payload) => {
    try {
      return await AkauntingService.receiveBill(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  billCancelled: async ({ getters }, payload) => {
    try {
      return await AkauntingService.receiveBill(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  // Use when signing-in with QBO Oauth2
  setLastUrlPath: ({ commit }, payload) => {
    commit('updateAccountingLastPath', payload);
  },

  // Invoice Form

  createInvoiceForm: async ({}, payload) => {
    try {
      return await AkauntingService.createInvoice(payload);
    } catch (error) {
      throw error;
    }
  },

  getInvoicesData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getInvoices(payload);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  updateInvoice: async ({}, { id, payload }) => {
    try {
      return await AkauntingService.updateInvoice(id, payload);
    } catch (error) {
      throw error;
    }
  },

  deleteInvoice: async ({ getters }, payload) => {
    try {
      return await AkauntingService.deleteInvoice(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },
  ////// STATEMENT //////
  getStatements: async ({}, payload) => {
    try {
      const response = await AkauntingService.getStatements(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  getStatement: async ({}, payload) => {
    try {
      const response = await AkauntingService.getStatement(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  createStatementForm: async ({}, payload) => {
    try {
      return await AkauntingService.createStatement(payload);
    } catch (error) {
      throw error;
    }
  },

  updateStatementForm: async ({}, payload) => {
    try {
      return await AkauntingService.updateStatement(payload);
    } catch (error) {
      throw error;
    }
  },
  deleteStatement: async ({}, payload) => {
    try {
      return await AkauntingService.deleteStatement(payload);
    } catch (error) {
      throw error;
    }
  },
  //////////////////////

  markInvoiceAsSent: ({}, id) => {
    return AkauntingService.markInvoiceAsSent(id);
  },

  markInvoiceAsCancelled: ({}, id) => {
    return AkauntingService.markInvoiceAsCancelled(id);
  },

  getAccountingCurrencies: async ({}, payload) => {
    try {
      const response = await AkauntingService.getAccountingCurrencies(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  createCurrencyForm: async ({}, payload) => {
    try {
      const response = await AkauntingService.createCurrencyForm(payload);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  updateCurrencyForm: async ({}, payload) => {
    try {
      return await AkauntingService.updateCurrency(payload);
    } catch (error) {
      throw error;
    }
  },

  deleteCurrency: async ({}, payload) => {
    try {
      return await AkauntingService.deleteCurrency(payload);
    } catch (error) {
      throw error;
    }
  },

  // Accounts

  getAccountsData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getAccounts(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  createAccountForm: async ({}, payload) => {
    try {
      const response = await AkauntingService.createAccountForm(payload);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  updateAccountForm: async ({}, payload) => {
    try {
      const response = await AkauntingService.updateAccountForm(payload);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  deleteAccount: async ({ getters }, payload) => {
    try {
      return await AkauntingService.deleteAccount(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  disableAccountBasedItem: async ({ getters }, payload) => {
    try {
      const { data } = await AkauntingService.disableAccountBasedItem(payload, { qbo_enabled: getters.isQBOEnabled });
      return data;
    } catch (error) {
      throw error;
    }
  },

  getQBOAccountTypes: async ({}, payload) => {
    try {
      const response = await AkauntingService.getQBOAccountTypes(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  // Categories
  getCategories: async ({}, payload) => {
    try {
      const response = await AkauntingService.getCategories(payload);
      return response.data?.data || [];
    } catch (error) {
      throw error;
    }
  },

  // Chart of Accounts
  getCoaAccountsAndTypes: async ({}, payload) => {
    try {
      const response = await AkauntingService.getCoaAccountsAndTypes(payload);
      return response.data?.data?.data || {};
    } catch (error) {
      throw error;
    }
  },

  getCoaAccountsAndDefaults: async ({}, payload) => {
    try {
      const response = await AkauntingService.getCoaAccountsAndDefaults(payload);
      return response.data?.data?.data || {};
    } catch (error) {
      throw error;
    }
  },

  getChartAccountAndTypes: async ({}, payload) => {
    try {
      const response = await AkauntingService.getAccountAndTypes(payload);
      return response.data?.data?.data || {};
    } catch (error) {
      throw error;
    }
  },

  getChartAccounts: async ({}, payload) => {
    try {
      const response = await AkauntingService.getChartAccounts(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  createCoaAccount: async ({}, payload) => {
    try {
      const response = await AkauntingService.createCoaAccount(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  updateCoaAccount: async ({}, payload) => {
    try {
      const response = await AkauntingService.updateCoaAccount(payload);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  deleteCoaAccount: async ({ getters }, payload) => {
    try {
      return await AkauntingService.deleteCoaAccount(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  enableCoaAccount: async ({ getters }, payload) => {
    try {
      return await AkauntingService.enableCoaAccount(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  disableCoaAccount: async ({ getters }, payload) => {
    try {
      return await AkauntingService.disableCoaAccount(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  // TaxRate API
  getTaxRates: async ({}, payload) => {
    try {
      const response = await AkauntingService.getTaxRates(payload);
      return response.data?.data || null;
    } catch (error) {
      throw error;
    }
  },

  createTaxRate: async ({}, payload) => {
    try {
      const response = await AkauntingService.createTaxRate(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  updateTaxRate: async ({}, payload) => {
    try {
      const response = await AkauntingService.updateTaxRate(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  deleteTaxRate: async ({}, payload) => {
    try {
      const response = await AkauntingService.deleteTaxRate(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  // Invoice Payment API
  getInvoicePaymentData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getInvoicePayments(payload);
      return response.data?.data || null;
    } catch (error) {
      throw error;
    }
  },

  createInvoicePayment: async ({}, payload) => {
    try {
      const response = await AkauntingService.createInvoicePayment(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  updateInvoicePayment: async ({}, { id, payload }) => {
    try {
      const response = await AkauntingService.updateInvoicePayment(id,payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  fetchDefaults: async ({ commit }) => {
    const { data } = await AkauntingService.getAccountingDefaults();
    commit('setDefaults', data.data);
  },

  // Bill Payment API
  getBillPaymentData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getBillPayments(payload);
      return response.data?.data || null;
    } catch (error) {
      throw error;
    }
  },

  createBillPayment: async ({}, payload) => {
    try {
      const response = await AkauntingService.createBillPayment(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  updateBillPayment: async ({}, { id, payload }) => {
    try {
      const response = await AkauntingService.updateBillPayment(id,payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  // Transactions API
  deleteTransaction: async ({}, payload) => {
    try {
      const response = await AkauntingService.deleteTransaction(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  // Journal Entries API
  getJournalEntriesData: async ({}, payload) => {
    try {
      const response = await AkauntingService.getJournalEntries(payload);
      return response.data.data;
    } catch (error) {
      throw error;
    }
  },

  createJournalEntry: async ({}, payload) => {
    try {
      const response = await AkauntingService.createJournalEntry(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  updateJournalEntry: async ({}, { id, payload }) => {
    try {
      const response = await AkauntingService.updateJournalEntry(id,payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },
  
  deleteJournalEntry: async ({ getters }, payload) => {
    try {
      return await AkauntingService.deleteJournalEntry(payload, { qbo_enabled: getters.isQBOEnabled });
    } catch (error) {
      throw error;
    }
  },

  // Reconciliations API
  createReconciliation: async ({}, payload) => {
    try {
      const response = await AkauntingService.createReconciliation(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  updateReconciliation: async ({}, { id, payload }) => {
    try {
      const response = await AkauntingService.updateReconciliation(id,payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },

  deleteReconciliation: async ({}, payload) => {
    try {
      const response = await AkauntingService.deleteReconciliation(payload);
      return response.data || [];
    } catch (error) {
      throw error;
    }
  },
  
  getReconciliation: async ({}, payload) => {
    try {
      const response = await AkauntingService.getReconciliation(payload);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  getReconciliations: async ({}, payload) => {
    try {
      const response = await AkauntingService.getReconciliations(payload);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  getAccountOpeningBalance: async ({}, payload) => {
    try {
      const response = await AkauntingService.getAccountOpeningBalance(payload);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  getAccountTransactionsByPeriod: async ({}, payload) => {
    try {
      const response = await AkauntingService.getAccountTransactionsByPeriod(payload);
      return response.data;
    } catch (error) {
      throw error;
    }
  },


};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
