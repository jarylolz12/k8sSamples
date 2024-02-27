/** @format */

import axios from "axios";
import store from "../";
import WarehouseCustomerModel from "../../models/WarehouseCustomerModel";

const state = {
	warehouseCustomers: [],
	warehouseCustomersLoading: true,
    createWarehouseCustomer: null,
    createWarehouseCustomerLoading: false,
	updateWarehouseCustomer: null,
	updateWarehouseCustomerLoading: false,
	deleteWarehouseCustomerLoading: false,
	warehouseCustomersSearched: [],
	warehouseCustomersSearchedLoading: false,
	warehouseCustomersDropdown: [],
	warehouseCustomersDropdownLoading: false,
	has3PLProvider: false,
	checkWarehouseCustomersProvider: null,
	checkWarehouseCustomersProviderLoading: false,
	getAllWarehouseCustomerListsData: [],
	serviceProviderWarehouses: [],
	serviceProviderWarehousesLoading: false
};

const getters = {
	getWarehouseCustomers: (state) => state.warehouseCustomers,
	getWarehouseCustomersLoading: (state) => state.warehouseCustomersLoading,
    getWarehouseCreateCustomers: (state) => state.createWarehouseCustomer,
	getWarehouseCreateCustomersLoading: (state) => state.createWarehouseCustomerLoading,
	getWarehouseUpdateCustomers: (state) => state.updateWarehouseCustomer,
	getWarehouseUpdateCustomersLoading: (state) => state.updateWarehouseCustomerLoading,
	getWarehouseDeleteCustomersLoading: (state) => state.deleteWarehouseCustomerLoading,
	getSearchedWarehouseCustomers: (state) => state.warehouseCustomersSearched,
	getSearchedWarehouseCustomersLoading: (state) => state.warehouseCustomersSearchedLoading,
	getWarehouseCustomersDropdown: (state) => state.warehouseCustomersDropdown,
	getWarehouseCustomersDropdownLoading: (state) => state.warehouseCustomersDropdownLoading,
	getCheck3PLProviderWarehouseLoading: (state) => state.checkWarehouseCustomersProviderLoading,
	getHas3PLProviderWarehouse: (state) => state.has3PLProvider,
	getAllWarehouseCustomerListsData: (state) => state.getAllWarehouseCustomerListsData,
	get3PLServiceProviderWarehouses: (state) => state.serviceProviderWarehouses,
	get3PLServiceProviderWarehousesLoading: (state) => state.serviceProviderWarehousesLoading,
};

// const betaBaseUrl = process.env.VUE_APP_BASE_URL;
const poBaseUrl = process.env.VUE_APP_PO_URL;

const actions = {
	setSearchedWarehouseCustomerLoading({ commit } , payload) {
		commit("SET_SEARCHED_WAREHOUSE_CUSTOMER_LOADING", payload)
	},
    setSearchedWarehouseCustomerVal({ commit }, payload) {
		commit("setSearchedWarehouseCustomerVal", payload)
	},
	// warehouse customers module
	fetchWarehouseCustomers: async ({ commit }, payload) => {
		commit("SET_WAREHOUSE_CUSTOMERS_LOADING", true);
		let attempt = false;

		return new Promise((resolve, reject) => {
			function proceed() {
				let s = new WarehouseCustomerModel(poBaseUrl);
				s.getWarehouseCustomers(payload)
					.then((res) => {
						commit("SET_WAREHOUSE_CUSTOMERS_LOADING", false);
						commit("SET_WAREHOUSE_CUSTOMERS", res.data);
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_WAREHOUSE_CUSTOMERS_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_WAREHOUSE_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
    createWarehouseCustomers: async ({ commit }, payload) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_CREATE_WAREHOUSE_CUSTOMERS_LOADING", true);

				// axios.post(`${poBaseUrl}/customer/${payload.id}/warehouse-customer/create`, payload)
				axios.post(`${poBaseUrl}/warehouse-customer/create`, payload)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_CREATE_WAREHOUSE_CUSTOMERS_LOADING", false);
								commit("SET_CREATE_WAREHOUSE_CUSTOMERS", res.data);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_CREATE_WAREHOUSE_CUSTOMERS_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_CREATE_WAREHOUSE_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	updateWarehouseCustomers: async ({ commit }, payload) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_UPDATE_WAREHOUSE_CUSTOMERS_LOADING", true);

				axios
					.put(`${poBaseUrl}/warehouse-customer/${payload.wc_id}/update`, payload)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_UPDATE_WAREHOUSE_CUSTOMERS_LOADING", false);
								commit("SET_UPDATE_WAREHOUSE_CUSTOMERS", res.data);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_UPDATE_WAREHOUSE_CUSTOMERS_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_UPDATE_WAREHOUSE_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	deleteWarehouseCustomers: async ({ commit }, id) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_DELETE_WAREHOUSE_CUSTOMERS_LOADING", true);

				axios.delete(`${poBaseUrl}/warehouse-customer/${id}/delete`)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_DELETE_WAREHOUSE_CUSTOMERS_LOADING", false);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_DELETE_WAREHOUSE_CUSTOMERS_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_DELETE_WAREHOUSE_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},	
	fetchWarehouseCustomersSearched: async ({ commit }, payload) => {
		commit("SET_SEARCHED_WAREHOUSE_CUSTOMER_LOADING", true);
		let attempt = false;

		return new Promise((resolve, reject) => {
			function proceed() {
				let s = new WarehouseCustomerModel(poBaseUrl);
				s.searchWarehouseCustomers(payload)
					.then((res) => {
						let finalData = {
							data: [],
							current_page: 1,
							total: 0,
							last_page: 0,
							old_page: 1,
							per_page: 35
						}

						finalData = {
							data: res.data.data,
							current_page: res.data.current_page,
							total: res.data.total,
							old_page: res.data.current_page,
							last_page: res.data.last_page,
							per_page: res.data.per_page,
							tab: payload.tab
						}

						commit("SET_SEARCHED_WAREHOUSE_CUSTOMER_LOADING", false);
						commit("SET_SEARCHED_WAREHOUSE_CUSTOMER", finalData);
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_SEARCHED_WAREHOUSE_CUSTOMER_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_SEARCHED_WAREHOUSE_CUSTOMER_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	fetchWarehouseCustomersDropdown: async ({ commit }, payload) => {
		commit("SET_WAREHOUSE_CUSTOMERS_DROPDOWN_LOADING", true);
		let attempt = false;

		return new Promise((resolve, reject) => {
			function proceed() {
				let s = new WarehouseCustomerModel(poBaseUrl);
				s.getWarehouseCustomersDropdown(payload)
					.then((res) => {
						commit("SET_WAREHOUSE_CUSTOMERS_DROPDOWN_LOADING", false);
						commit("SET_WAREHOUSE_CUSTOMERS_DROPDOWN", res.data);
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_WAREHOUSE_CUSTOMERS_DROPDOWN_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_WAREHOUSE_CUSTOMERS_DROPDOWN_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	checkWarehouse3PLProvider: async ({ commit }, id) => {
		commit("SET_CHECK_WAREHOUSE_CUSTOMERS_LOADING", true);
		let attempt = false;

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/customer/${id}/has-warehouse-customer`)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_CHECK_WAREHOUSE_CUSTOMERS_LOADING", false);
								commit("SET_CHECK_WAREHOUSE_CUSTOMERS", res.data);
								resolve(res.data);
							}
						}
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_CHECK_WAREHOUSE_CUSTOMERS_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_CHECK_WAREHOUSE_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	setWarehouseTypeHas3PL({ commit }, payload) {
		commit("setWarehouseTypeHas3PL", payload)
	},
	setAllWarehouseCustomerLists( { commit }, payload) {
        commit("SET_ALL_WAREHOUSE_CUSTOMER_LISTS_DATA", payload)
    },
	// 
	fetch3PLServiceProviderWarehouses: async ({ commit }) => {
		commit("SET_3PL_PROVIDER_WAREHOUSES_LOADING", true);
		let attempt = false;

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/your-3pl-provider-warehouses`)
					.then((res) => {
						commit("SET_3PL_PROVIDER_WAREHOUSES_LOADING", false);
						commit("SET_3PL_PROVIDER_WAREHOUSES", res.data.results);
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_3PL_PROVIDER_WAREHOUSES_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_3PL_PROVIDER_WAREHOUSES_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
};

const mutations = {
	SET_WAREHOUSE_CUSTOMERS: (state, payload) => {
		state.warehouseCustomers = payload;
	},
	SET_WAREHOUSE_CUSTOMERS_LOADING: (state, payload) => {
		state.warehouseCustomersLoading = payload;
	},
    SET_CREATE_WAREHOUSE_CUSTOMERS: (state, payload) => {
		state.createWarehouseCustomer = payload;
	},
	SET_CREATE_WAREHOUSE_CUSTOMERS_LOADING: (state, payload) => {
		state.createWarehouseCustomerLoading = payload;
	},
	SET_UPDATE_WAREHOUSE_CUSTOMERS: (state, payload) => {
		state.updateWarehouseCustomer = payload;
	},
	SET_UPDATE_WAREHOUSE_CUSTOMERS_LOADING: (state, payload) => {
		state.updateWarehouseCustomerLoading = payload;
	},    
	SET_DELETE_WAREHOUSE_CUSTOMERS_LOADING: (state, payload) => {
		state.deleteWarehouseCustomerLoading = payload;
	},
	setSearchedWarehouseCustomerVal: (state, payload) => {
		let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            data: payload
        }

        state.warehouseCustomersSearched = locationDefaultData
	},
	SET_SEARCHED_WAREHOUSE_CUSTOMER: (state, payload) => {
		state.warehouseCustomersSearched = payload;
	},
	SET_SEARCHED_WAREHOUSE_CUSTOMER_LOADING: (state, payload) => {
		state.warehouseCustomersSearchedLoading = payload;
	},
	// dropdown
	SET_WAREHOUSE_CUSTOMERS_DROPDOWN: (state, payload) => {
		state.warehouseCustomersDropdown = payload;
	},
	SET_WAREHOUSE_CUSTOMERS_DROPDOWN_LOADING: (state, payload) => {
		state.warehouseCustomersDropdownLoading = payload;
	},
	SET_CHECK_WAREHOUSE_CUSTOMERS: (state, payload) => {
		state.checkWarehouseCustomersProvider = payload;
	},
	SET_CHECK_WAREHOUSE_CUSTOMERS_LOADING: (state, payload) => {
		state.checkWarehouseCustomersProviderLoading = payload;
	},
	setWarehouseTypeHas3PL: (state, payload) => {
		state.has3PLProvider = payload;
	},	
	SET_ALL_WAREHOUSE_CUSTOMER_LISTS_DATA: (state, payload) => {
		state.getAllWarehouseCustomerListsData = payload
	},
	// 
	SET_3PL_PROVIDER_WAREHOUSES: (state, payload) => {
		state.serviceProviderWarehouses = payload;
	},
	SET_3PL_PROVIDER_WAREHOUSES_LOADING: (state, payload) => {
		state.serviceProviderWarehousesLoading = payload;
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
	getters,
};
