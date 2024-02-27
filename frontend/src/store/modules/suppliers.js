/** @format */

import axios from "axios";
import store from "../";
import SupplierModel from "../../models/SupplierModel";
import qs from "qs";

const state = {
    suppliers: [],
    suppliersLoading: true,
    createSupplier: null,
    updateSupplier: null,
    updateSupplierLoading: false,
    createSupplierLoading: false,
    supplierSaveAddLoading: false,
    deleteSuppliersLoading: false,
    sendInvitationSupplier: null,
    sendInvitationSupplierLoading: false,
    searchedSuppliers: [],
    searchedSuppliersLoading: false,
    companyKeyLoading: false,
    companyKeyResponse: true,
    customerDetailsData: [],
};

const getters = {
    getSuppliers: (state) => state.suppliers,
    getSuppliersLoading: (state) => state.suppliersLoading,
    getCreateSuppliers: (state) => state.createSupplier,
    getCreateSuppliersLoading: (state) => state.createSupplierLoading,
    getUpdateSuppliers: (state) => state.updateSupplier,
    getUpdateSuppliersLoading: (state) => state.updateSupplierLoading,
    getDeleteSuppliersLoading: (state) => state.deleteSuppliersLoading,
    getSupplierSaveAddLoading: (state) => state.supplierSaveAddLoading,
    getSendInvitationSupplierLoading: (state) =>
        state.sendInvitationSupplierLoading,
    getSearchedSuppliers: (state) => state.searchedSuppliers,
    getSearchedSuppliersLoading: (state) => state.searchedSuppliersLoading,
    getCompanyKeyLoading: (state) => state.companyKeyLoading,
    getCompanyKeyResponse: (state) => state.companyKeyResponse,
    getCustomerDetailsData: (state) => state.customerDetailsData,
};

const betaBaseUrl = process.env.VUE_APP_BASE_URL;
// const poBaseUrl = "https://beta.shifl.com/api"

const actions = {
    fetchSuppliers: async({ commit }, payload) => {
        commit("SET_SUPPLIERS_LOADING", true);
        let attempt = false;

        return new Promise((resolve, reject) => {
            // let limit = 1;
            // let start = 0;
            function proceed() {
                let s = new SupplierModel(betaBaseUrl);
                s.getSuppliers(payload)
                    .then((res) => {
                        commit("SET_SUPPLIERS_LOADING", false);
                        commit("SET_SUPPLIERS", res.data);
                        resolve();
                    })
                    .catch((err) => {
                        // console.log("err", err);
                        // if (typeof err.message !== "undefined") {
                        // 	if (!attempt) {
                        // 		attempt = true;
                        // 		let t = setInterval(() => {
                        // 			if (!store.getters.getIsRefreshing) {
                        // 				if (start == limit) {
                        // 					store
                        // 						.dispatch("refreshToken")
                        // 						.then(() => {
                        // 							attempt = false;
                        // 							start = 0;
                        // 							proceed();
                        // 						})
                        // 						.catch((e) => {
                        // 							console.log(e);
                        // 							commit("SET_SUPPLIERS_LOADING", false);
                        // 							reject(
                        // 								"Token has been expired. Please reload the page."
                        // 							);
                        // 						});
                        // 				} else {
                        // 					start++;
                        // 					proceed();
                        // 					attempt = false;
                        // 				}
                        // 				clearInterval(t);
                        // 			}
                        // 		}, 300);
                        // 	} else {
                        // 		commit("SET_SUPPLIERS_LOADING", false);
                        // 		// reject('Token has been expired. Please reload the page.')
                        // 		reject(err);
                        // 	}
                        // }
                        // if (typeof err.error !== "undefined") {
                        // 	reject(err.error);
                        // }

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
                                commit("SET_SUPPLIERS_LOADING", false);
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SUPPLIERS_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    createSuppliers: async({ commit }, payload) => {
        // commit("SET_CREATE_SUPPLIERS_LOADING", true)
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                let data = {
                    custom_customers_id: payload.custom_customers_id,
                    company_name: payload.company_name,
                    address: payload.address,
                    phone: payload.phone,
                    emails: payload.emails,
                    company_key: payload.company_key,
                };

                commit(
                    payload.customerMethod == "save-add" ?
                    "SET_SUPPLIERS_SAVE_ADD_LOADING" :
                    "SET_CREATE_SUPPLIERS_LOADING",
                    true
                );

                axios
                    .post(`/suppliers`, data)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                commit(
                                    payload.customerMethod == "save-add" ?
                                    "SET_SUPPLIERS_SAVE_ADD_LOADING" :
                                    "SET_CREATE_SUPPLIERS_LOADING",
                                    false
                                );
                                commit("SET_CREATE_SUPPLIERS", res.data);
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
                                commit(
                                    payload.customerMethod == "save-add" ?
                                    "SET_SUPPLIERS_SAVE_ADD_LOADING" :
                                    "SET_CREATE_SUPPLIERS_LOADING",
                                    false
                                );
                                // reject('Token has been expired. Please reload the page.')
                                reject(err);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit(
                                payload.customerMethod == "save-add" ?
                                "SET_SUPPLIERS_SAVE_ADD_LOADING" :
                                "SET_CREATE_SUPPLIERS_LOADING",
                                false
                            );
                            reject(err.error);
                        }

                        /*
					commit((payload.customerMethod=='save-add') ? 'SET_SUPPLIERS_SAVE_ADD_LOADING': 'SET_CREATE_SUPPLIERS_LOADING', false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						if (typeof err.message !== 'undefined') {
							return Promise.reject('Token has been expired. Please reload the page.')
						}
					}*/
                    });
            }
            proceed();
        });
    },
    updateSuppliers: async({ commit }, payload) => {
        let attempt = false;

        return new Promise((resolve, reject) => {
            function proceed() {
                let data = {
                    company_name: payload.company_name,
                    address: payload.address,
                    phone: payload.phone,
                    emails: payload.emails,
                };

                commit(
                    payload.customerMethod == "save-add" ?
                    "SET_SUPPLIERS_SAVE_ADD_LOADING" :
                    "SET_CREATE_SUPPLIERS_LOADING",
                    true
                );
                axios
                    .put(`/suppliers/${payload.id}`, data)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                commit(
                                    payload.customerMethod == "save-add" ?
                                    "SET_SUPPLIERS_SAVE_ADD_LOADING" :
                                    "SET_CREATE_SUPPLIERS_LOADING",
                                    false
                                );
                                commit("SET_UPDATE_SUPPLIERS", res.data);
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
                                commit(
                                    payload.customerMethod == "save-add" ?
                                    "SET_SUPPLIERS_SAVE_ADD_LOADING" :
                                    "SET_CREATE_SUPPLIERS_LOADING",
                                    false
                                );
                                // reject('Token has been expired. Please reload the page.')
                                reject(err);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit(
                                payload.customerMethod == "save-add" ?
                                "SET_SUPPLIERS_SAVE_ADD_LOADING" :
                                "SET_CREATE_SUPPLIERS_LOADING",
                                false
                            );
                            reject(err.error);
                        }
                        /*
					commit((payload.customerMethod=='save-add') ? 'SET_SUPPLIERS_SAVE_ADD_LOADING': 'SET_CREATE_SUPPLIERS_LOADING', false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						if (typeof err.message !== 'undefined') {
							return Promise.reject('Token has been expired. Please reload the page.')
						}
					}*/
                    });
            }
            proceed();
        });
    },
    deleteSuppliers: async({ commit }, payload) => {
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                commit("SET_DELETE_SUPPLIERS_LOADING", true);

                axios
                    .delete(`/suppliers/${payload}`)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                commit("SET_DELETE_SUPPLIERS_LOADING", false);
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
                                commit("SET_DELETE_SUPPLIERS_LOADING", false);
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_DELETE_SUPPLIERS_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    sendInvitationSupplier: async({ commit }, payload) => {
        let attempt = false;
        return new Promise((resolve, reject) => {
            console.log("data", payload);

            function proceed() {
                commit("SET_SEND_INVITATION_SUPPLIERS_LOADING", true);
                axios
                    .post(`/send-supplier-invitation`, qs.stringify(payload), {
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    })
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                commit("SET_SEND_INVITATION_SUPPLIERS_LOADING", false);
                                commit("SET_SEND_INVITATION_SUPPLIERS", res.data);
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
                                commit("SET_SEND_INVITATION_SUPPLIERS_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SEND_INVITATION_SUPPLIERS_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    fetchSearchedVendors: async({ commit }, payload) => {
        commit("SET_SEARCHED_SUPPLIERS_LOADING", true);
        let attempt = false;

        return new Promise((resolve, reject) => {
            function proceed() {
                let s = new SupplierModel(betaBaseUrl);
                s.searchSuppliers(payload)
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

                        commit("SET_SEARCHED_SUPPLIERS_LOADING", false);
                        commit("SET_SEARCHED_SUPPLIERS", finalData);
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
                                commit("SET_SEARCHED_SUPPLIERS_LOADING", false);
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SEARCHED_SUPPLIERS_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    setSearchedVendorsLoading({ commit }, payload) {
        commit("SET_SEARCHED_SUPPLIERS_LOADING", payload)
    },
    setSearchedVendorsVal({ commit }, payload) {
        commit("setSearchedVendorsVal", payload)
    },
    checkCompanyKeyExists: async({ commit }, payload) => {
        commit("SET_COMPANY_KEY_LOADING", true);
        commit("SET_COMPANY_KEY_RESPONSE", true);
        return await axios
            .get(`/customers/check_company_key/${payload}`)
            .then((res) => {
                if (res.status === 200 && res.data.results && res.data.results !== 'success') {
                    commit("SET_COMPANY_KEY_RESPONSE", false);
                }
                commit("SET_COMPANY_KEY_LOADING", false);
            })
            .catch((err) => {
                console.log("err::", err);
                commit("SET_COMPANY_KEY_RESPONSE", false);
                commit("SET_COMPANY_KEY_LOADING", false);
            });
    },
    fetchCustomerDetails: async({ commit }, payload) => {
        commit("SET_COMPANY_KEY_LOADING", true);
        commit("SET_CUSTOMER_DETAILS_RESPONSE", []);
        return await axios
            .get(`/customer/${payload}/detail`)
            .then((res) => {
                if (res.status === 200) {
                    commit("SET_COMPANY_KEY_LOADING", false);
                    commit("SET_CUSTOMER_DETAILS_RESPONSE", res.data.results);
                }
            })
            .catch((err) => {
                console.log("err::", err);
                commit("SET_COMPANY_KEY_LOADING", false);
            });
    },
};

const mutations = {
    SET_SUPPLIERS: (state, payload) => {
        state.suppliers = payload;
    },
    SET_SUPPLIERS_LOADING: (state, payload) => {
        state.suppliersLoading = payload;
    },
    SET_CREATE_SUPPLIERS: (state, payload) => {
        state.createSupplier = payload;
    },
    SET_CREATE_SUPPLIERS_LOADING: (state, payload) => {
        state.createSupplierLoading = payload;
    },
    SET_SUPPLIERS_SAVE_ADD_LOADING: (state, payload) => {
        state.supplierSaveAddLoading = payload;
    },
    SET_UPDATE_SUPPLIERS: (state, payload) => {
        state.updateSupplier = payload;
    },
    SET_UPDATE_SUPPLIERS_LOADING: (state, payload) => {
        state.updateSuppliersLoading = payload;
    },
    SET_DELETE_SUPPLIERS_LOADING: (state, payload) => {
        state.deleteSuppliersLoading = payload;
    },
    SET_SEND_INVITATION_SUPPLIERS: (state, payload) => {
        state.sendInvitationSupplier = payload;
    },
    SET_SEND_INVITATION_SUPPLIERS_LOADING: (state, payload) => {
        state.sendInvitationSupplierLoading = payload;
    },
    SET_SEARCHED_SUPPLIERS: (state, payload) => {
        state.searchedSuppliers = payload;
    },
    SET_SEARCHED_SUPPLIERS_LOADING: (state, payload) => {
        state.searchedSuppliersLoading = payload;
    },
    setSearchedVendorsVal: (state, payload) => {
        let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            data: payload
        }

        state.searchedSuppliers = locationDefaultData
    },
    SET_COMPANY_KEY_LOADING: (state, payload) => {
        state.companyKeyLoading = payload;
    },
    SET_COMPANY_KEY_RESPONSE: (state, payload) => {
        state.companyKeyResponse = payload;
    },
    SET_CUSTOMER_DETAILS_RESPONSE: (state, payload) => {
        state.customerDetailsData = payload;
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};