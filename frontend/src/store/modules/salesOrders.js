/** @format */

import axios from "axios";
import router from '../../router/index'
// import Shipment from './../../custom/ShipmentResource'
import store from "../";
import PoModel from "../../models/PoModel";
import ProductModel from "../../models/ProductModel";

const state = {
    currentPage: 1,
    allPage: 1,
    pendingPage: 1,
    shippedPage: 1,
    currentPoSelected: null,
    po: [],
    poGlobalQuery: "",
    soLocalQuery: "",
    // new
    poPending: [],
    poShipped: [],
    poSelectProductsLoading: false,
    poPendingLoading: false,
    poShippedLoading: false,
    poGlobalSearchLoading: false,
    currentSoTab: 0,
    currentSoAllTab: 0,
    currentSoOpenTab: 0,
    currentSoBookingTab: 0,
    currentSoInProgressTab: 0,
    //
    editPo: {},
    soCreateLoading: false,
    soDraftLoading: false,
    poSaveAddLoading: false,
    vendorLists: [],
    buyerLists: [],
    poDeleteLoading: false,
    vendorListsLoading: false,
    buyerListsLoading: false,
    poDetailLoading: false,
    downloadLoading: false,
    poDetail: {},
    sendEmailLoading: false,
    pendingPOPagination: {
        currentTab: 1,
        currentSubTab: 0,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        next_page_url: "",
        prev_page_url: "",
    },
    shippedPOPagination: {
        currentTab: 1,
        currentSubTab: 0,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        next_page_url: "",
        prev_page_url: "",
    },
    poShipmentDetails: [],
    poShipmentDetailsLoading: false,
    poAll: [],
    poAllLoading: false,
    allPOPagination: {
        currentTab: 1,
        currentSubTab: 0,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        next_page_url: "",
        prev_page_url: "",
    },
    orderStateLoading: false,
    importName: [],
    importNameLoading: false,
    cancelChangeRequestLoading: false,
    orderCounterValue: "",
    reviewPaymentLoading: false,
    paymentDocumentDownloading: false,
    csvReportLoading: false,
    setCsvReportData: [],
    allSalesOrdersData: {},
    suppliersSkus:[],
    suppliersSkusLoading:false
};

const getters = {
    fetchPoSelectProductsLoading: (state) => state.poSelectProductsLoading,
    getCurrentSoTab: (state) => state.currentSoTab,
    getCurrentSoAllTab: (state) => state.currentSoAllTab,
    getCurrentSoOpenTab: (state) => state.currentSoOpenTab,
    getCurrentSoBookingTab: (state) => state.currentSoBookingTab,
    getCurrentSoInProgressTab: (state) => state.currentSoInProgressTab,
    getCurrentPage: (state) => state.currentPage,
    getAllPoPage: (state) => state.allPage,
    getPendingPage: (state) => state.pendingPage,
    getShippedPage: (state) => state.shippedPage,
    getCurrentTab: (state) => state.currentTab,
    getPoGlobalSearchLoading: (state) => state.poGlobalSearchLoading,
    getCurrentSelectedPo: (state) => state.currentPoSelected,
    getAllPo: (state) => state.po,
    getSoCreateLoading: (state) => state.soCreateLoading,
    getSoDraftLoading: (state) => state.soDraftLoading,
    getPoSaveAddLoading: (state) => state.poSaveAddLoading,
    getVendorListsLoading: (state) => state.vendorListsLoading,
    getVendorLists: (state) => state.vendorLists,
    getBuyerLists: (state) => state.buyerLists,
    getBuyerListsLoading: (state) => state.buyerListsLoading,
    getPoDeleteLoading: (state) => state.poDeleteLoading,
    getPoDetailLoading: (state) => state.poDetailLoading,
    getPoDetail: (state) => state.poDetail,
    getDownloadLoading: (state) => state.downloadLoading,
    getEmailLoading: (state) => state.sendEmailLoading,
    getPendingPOPagination: (state) => state.pendingPOPagination,
    getShippedPOPagination: (state) => state.shippedPOPagination,
    getAllPoPending: (state) => state.poPending,
    getPoPendingLoading: (state) => state.poPendingLoading,
    getAllPoShipped: (state) => state.poShipped,
    getPoShippedLoading: (state) => state.poShippedLoading,
    getPoShipmentDetails: (state) => state.poShipmentDetails,
    getPoShipmentDetailsLoading: (state) => state.poShipmentDetailsLoading,
    getPoGlobalQuery: (state) => state.poGlobalQuery,
    getSoLocalQuery: (state) => state.soLocalQuery,
    getAllPos: (state) => state.poAll,
    getAllPosLoading: (state) => state.poAllLoading,
    getAllPosPagination: (state) => state.allPOPagination,
    getOrdersStateLoading: (state) => state.orderStateLoading,
    getImportName: (state) => state.importName,
    getImportNameLoading: (state) => state.importNameLoading,
    getCancelChangeRequestLoading: (state) => state.cancelChangeRequestLoading,
    getOrderCounterValue: (state) => state.orderCounterValue,
    getReviewPaymentLoading: (state) => state.reviewPaymentLoading,
    getPaymentDocumentDownloading: (state) => state.paymentDocumentDownloading,
    getCsvReportLoading: (state) => state.csvReportLoading,
    getSetCsvReportData: (state) => state.setCsvReportData,
    getSuppliersSkus: state => state.suppliersSkus,
    getSuppliersSkusLoading: state => state.suppliersSkusLoading
};

const betaBaseUrl = process.env.VUE_APP_BASE_URL;
const poBaseUrl = process.env.VUE_APP_PO_URL;
// const poStagingUrl = process.env.VUE_APP_POSTAGING_BASE_URL

const actions = {
    setPoPendingLoading: ({ commit }, payload) => {
        commit("SET_PO_PENDING_LOADING", payload);
    },
    setPoShippedLoading: ({ commit }, payload) => {
        commit("SET_PO_SHIPPED_LOADING", payload);
    },
    setPoAllLoading: ({ commit }, payload) => {
        commit("SET_PO_ALL_LOADING", payload);
    },
    setCurrentPage: ({ commit }, payload) => {
        commit("SET_CURRENT_PAGE", payload);
    },
    setAllPage: ({ commit }, payload) => {
        commit("SET_ALL_PAGE", payload);
    },
    setPendingPage: ({ commit }, payload) => {
        commit("SET_PENDING_PAGE", payload);
    },
    setShippedPage: ({ commit }, payload) => {
        commit("SET_SHIPPED_PAGE", payload);
    },
    setPoGlobalQuery: ({ commit }, payload) => {
        commit("SET_PO_GLOBAL_QUERY", payload);
    },
    setSoLocalQuery: ({ commit }, payload) => {
        commit("SET_PO_LOCAL_QUERY", payload);
    },
    fetchPoSelectProducts: async({ commit }, payload) => {
        let attempt = false;

        commit("SET_PO_SELECT_PRODUCTS_LOADING", true);
        return new Promise((resolve, reject) => {
            let limit = 1;
            let start = 0;

            function proceed() {
                let po = new ProductModel(poBaseUrl);
                po.findById(payload)
                    .then((res) => {
                        commit("SET_PO_SELECT_PRODUCTS_LOADING", false);
                        resolve(res);
                    })
                    .catch((err) => {
                        if (typeof err.message !== "undefined") {
                            if (!attempt) {
                                attempt = true;
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        if (start == limit) {
                                            store
                                                .dispatch("refreshToken")
                                                .then(() => {
                                                    attempt = false;
                                                    start = 0;
                                                    proceed();
                                                })
                                                .catch((e) => {
                                                    console.log(e);
                                                    commit("SET_PO_SELECT_PRODUCTS_LOADING", false);
                                                    reject(
                                                        "Token has been expired. Please reload the page."
                                                    );
                                                });
                                        } else {
                                            start++;
                                            proceed();
                                            attempt = false;
                                        }
                                        clearInterval(t);
                                    }
                                }, 300);
                            } else {
                                commit("SET_PO_SELECT_PRODUCTS_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }
                        if (typeof err.error !== "undefined") {
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    fetchPoGlobalSearch: async({ commit }, params) => {
        let attempt = false;
        commit("SET_PO_GLOBAL_SEARCH_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                let po = new PoModel(poBaseUrl);
                po.search(params)
                    .then((res) => {
                        commit("SET_PO_GLOBAL_SEARCH_LOADING", false);
                        resolve(res);
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
                                commit("SET_PO_GLOBAL_SEARCH_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_PO_GLOBAL_SEARCH_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    downloadPo: async({ commit }, item) => {
        let attempt = false;
        commit("SET_PO_DOWNLOAD_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                axios({
                        url: `${poBaseUrl}/orders/${item.id}/download-receipt?order_type=SO`,
                        method: "GET",
                        responseType: "blob",
                    })
                    .then((response) => {
                        commit("SET_PO_DOWNLOAD_LOADING", false);
                        let fileURL = window.URL.createObjectURL(new Blob([response.data]));
                        let fileLink = document.createElement("a");
                        fileLink.href = fileURL;
                        fileLink.setAttribute(
                            "download",
                            `SO-${
								typeof item.po_number !== "undefined" ? item.po_number : ""
							}.pdf`
                        );
                        fileLink.click();
                        resolve("ok");
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
                                commit("SET_PO_DOWNLOAD_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_PO_DOWNLOAD_LOADING", false);
                            reject(err.error);
                        }

                        /*
					commit('SET_PO_DOWNLOAD_LOADING', false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject(err.message)
					}*/
                    });
            }
            proceed();
        });
    },
    getPo: async({ commit }, id) => {
        let attempt = false;
        return new Promise((resolve, reject) => {
            let limit = 1;
            let start = 0;

            function proceed() {
                commit("SET_PO_DETAIL_LOADING", true);
                let po = new PoModel(poBaseUrl);
                po.findById(id)
                    .then((res) => {
                        commit("SET_PO_DETAIL_LOADING", false);
                        commit("SET_PO_DETAIL", res.data);
                        resolve(res.data);
                    })
                    .catch((err) => {
                        if (typeof err.message !== "undefined") {
                            if (!attempt) {
                                attempt = true;
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        if (start == limit) {
                                            store
                                                .dispatch("refreshToken")
                                                .then(() => {
                                                    attempt = false;
                                                    start = 0;
                                                    proceed();
                                                })
                                                .catch((e) => {
                                                    console.log(e);
                                                    commit("SET_PO_DETAIL_LOADING", false);
                                                    reject(
                                                        "Token has been expired. Please reload the page."
                                                    );
                                                });
                                        } else {
                                            start++;
                                            console.log("proceed again " + start);
                                            proceed();
                                            attempt = false;
                                        }
                                        clearInterval(t);
                                    }
                                }, 300);
                            } else {
                                commit("SET_PO_DETAIL_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }
                    });
                /*
                axios.get(`${poBaseUrl}/purchaseorders/${id}`).then(res => {
                	commit('SET_PO_DETAIL_LOADING', false)
                	if (typeof res!=='undefined' && res.status === 200) {
                		commit('SET_PO_DETAIL', res.data)
                		resolve(res.data)
                	}
                })
                .catch( err => {
                	console.log('proceed', store.getters.getIsRefreshing)
                	if (typeof err.message!=='undefined') {
                		if ( !attempt ){
                			attempt = true
                			let t =setInterval(() => {
                				if ( !store.getters.getIsRefreshing ) {
                					console.log('refreshing')
                					proceed()
                					clearInterval(t)
                				}
                			},300)
                		} else {
                			commit("SET_PO_DETAIL_LOADING", false)
                			reject('Token has been expired. Please reload the page.')
                		}
                	}

                	if (typeof err.error !=='undefined') {
                		commit("SET_PO_DETAIL_LOADING", false)
                		reject(err.error)
                	}
                	/*
                	commit('SET_PO_DETAIL_LOADING', false)
                	if (typeof err.error !== 'undefined') {
                		return Promise.reject(err.error)
                	} else {
                		return Promise.reject(err.message)
                	}*/
                //})
            }
            proceed();
        });
    },
    setPoDefault({ commit }) {
        commit("SET_PO", []);
    },
    setPo({ commit }, payload) {
        commit("setPo", payload);
    },
    updatePo: async({ commit }, payload) => {
        const stateLoading =
            payload.state == "draft" ?
            "SET_SO_DRAFT_LOADING" :
            "SET_SO_CREATE_LOADING";
        commit(stateLoading, true);
        let { id, ...otherPayload } = payload;

        payload = {
            ...otherPayload,
        };
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .post(`${poBaseUrl}/orders/update/${id}`, payload)
                    .then((res) => {
                        commit(stateLoading, false);
                        if (typeof res !== "undefined" && res.status === 200) {
                            resolve(res.data);
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
                                commit(stateLoading, false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit(stateLoading, false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }
                        /*
					commit((payload.another) ? 'SET_PO_SAVE_ADD_LOADING': 'SET_PO_CREATE_LOADING', false)
					if (typeof err.error !== 'undefined') {
						reject(err.error)
					} else {
						reject(err.message)
					}*/
                    });
            }
            proceed();
        });
    },
    createPo: async({ commit }, payload) => {
        const stateLoading =
            payload.state == "draft" ?
            "SET_SO_DRAFT_LOADING" :
            "SET_SO_CREATE_LOADING";

        commit(stateLoading, true);

        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .post(`${poBaseUrl}/orders/create`, payload)
                    .then((res) => {
                        commit(stateLoading, false);
                        if (typeof res !== "undefined" && res.status === 200) {
                            resolve(res.data);
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
                                commit(stateLoading, false);
                                reject(err.message);
                                //return Promise.reject('Token has been expired. Please reload the page.')
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit(stateLoading, false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }
                        /*
					commit((payload.another) ? 'SET_PO_SAVE_ADD_LOADING': 'SET_PO_CREATE_LOADING', false)
					if (typeof err.error !== 'undefined') {
						reject(err.error)
					} else {
						reject(err.message)
					}*/
                    });
            }
            proceed();
        });
    },
    fetchPoPagination: async({ commit }, pageNumber) => {
        let attempt = false;
        commit("SET_PO_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`${poBaseUrl}/purchaseorders`, {
                        params: {
                            page: pageNumber,
                        },
                    })
                    .then((res) => {
                        console.log(res);
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
                                commit("SET_PO_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_PO_LOADING", false);
                            reject(err.error);
                        }
                        console.error(err);
                    });
            }
            proceed();
        });
    },
    fetchVendorLists: async({ commit }) => {
        let attempt = false;
        commit("SET_VENDOR_LISTS_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get("suppliers")
                    .then((res) => {
                        commit("SET_VENDOR_LISTS_LOADING", false);
                        if (res.status === 200) {
                            if (res.data) {
                                if (res.data.data) {
                                    commit("SET_VENDOR_LISTS", res.data.data);
                                    resolve("ok");
                                } else {
                                    reject("not ok");
                                }
                            } else {
                                reject("not ok");
                            }
                        } else {
                            reject("not ok");
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
                                commit("SET_VENDOR_LISTS_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_VENDOR_LISTS_LOADING", false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }
                        /*
					commit("SET_VENDOR_LISTS_LOADING", false)
					if (typeof err.error !== 'undefined') {
						reject(err.error)
					} else {
						reject(err.message)
					}*/
                    });
            }
            proceed();
        });
    },
    fetchBuyerLists: async({ commit }) => {
        let attempt = false;
        commit("SET_BUYER_LISTS_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get("buyers")
                    .then((res) => {
                        commit("SET_BUYER_LISTS_LOADING", false);
                        if (res.status === 200) {
                            if (res.data) {
                                if (res.data.data) {
                                    commit("SET_BUYER_LISTS", res.data.data);
                                    resolve("ok");
                                } else {
                                    reject("not ok");
                                }
                            } else {
                                reject("not ok");
                            }
                        } else {
                            reject("not ok");
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
                                commit("SET_BUYER_LISTS_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                                //return Promise.reject('Token has been expired. Please reload the page.')
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_BUYER_LISTS_LOADING", false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }
                        /*
					commit("SET_BUYER_LISTS_LOADING", false)
					if (typeof err.error !== 'undefined') {
						reject(err.error)
					} else {
						reject(err.message)
					}*/
                    });
            }
            proceed();
        });
    },
    fetchPo: async({ commit }) => {
        commit("SET_PO_LOADING", true);
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`${poBaseUrl}/purchaseorders`)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                let pendingPO = res.data.results.pending;
                                // let shippedPO = res.data.results.shipped

                                commit("SET_PO_LOADING", false);
                                commit("SET_PO", res.data);
                                commit("SET_PENDING_PO", pendingPO);
                                return Promise.resolve("ok");
                            } else {
                                return Promise.reject("not ok");
                            }
                        } else {
                            return Promise.reject("not ok");
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                        if (!attempt) {
                            attempt = true;
                            let t = setInterval(() => {
                                if (!store.getters.getIsRefreshing) {
                                    proceed();
                                    clearInterval(t);
                                }
                            }, 300);
                        } else {
                            commit("SET_PO_LOADING", false);
                            // reject("Token has been expired. Please reload the page.");
                            reject(err.message);
                        }
                        /*

					commit("SET_PO_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject(err.message)
					}*/
                    });
            }
            proceed();
        });
    },
    deletePo: async({ commit }, id) => {
        let attempt = false;
        commit("SET_DELETE_PO_LOADING", true);

        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .delete(`${poBaseUrl}/orders/delete/${id}`)
                    .then((res) => {
                        commit("SET_DELETE_PO_LOADING", false);
                        resolve(res);
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
                                commit("SET_DELETE_PO_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_DELETE_PO_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    deleteMultiplePO: async({ commit }, ids) => {
        let attempt = true;
        commit("SET_DELETE_PO_LOADING", true);

        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .delete(`${poBaseUrl}/orders/delete-multiple`, {
                        params: { id: ids },
                    })
                    .then((res) => {
                        commit("SET_DELETE_PO_LOADING", false);
                        resolve(res);
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
                                commit("SET_DELETE_PO_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.error);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_DELETE_PO_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    downloadMultiplePO: async({ commit }, item) => {
        let attempt = false;
        commit("SET_PO_DOWNLOAD_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                let ids = [];

                if (item !== "undefined" && Array.isArray(item) && item.length > 0) {
                    ids = item.map((v) => {
                        return v.id;
                    });
                }

                axios({
                        url: `${poBaseUrl}/orders/download-multiple-receipt`,
                        params: { id: ids, order_type: "SO" },
                        method: "GET",
                        responseType: "blob",
                    })
                    .then((response) => {
                        commit("SET_PO_DOWNLOAD_LOADING", false);
                        let dateFormat = {
                            day: "2-digit",
                            month: "2-digit",
                            year: "numeric",
                        };

                        if (
                            item !== "undefined" &&
                            Array.isArray(item) &&
                            item.length > 0
                        ) {
                            let fileURL = window.URL.createObjectURL(
                                new Blob([response.data])
                            );
                            let fileLink = document.createElement("a");
                            fileLink.href = fileURL;
                            fileLink.setAttribute(
                                "download",
                                `PO-${new Date().toLocaleDateString("en-US", dateFormat)}.zip`
                            );
                            fileLink.click();
                            resolve("ok");
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
                                commit("SET_PO_DOWNLOAD_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_PO_DOWNLOAD_LOADING", false);
                            //reject(err.error)
                            //commit("SET_PO_DETAIL_LOADING", false)
                            reject(err.error);
                        }

                        /*
					commit('SET_PO_DOWNLOAD_LOADING', false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject(err.message)
					}*/
                    });
            }
            proceed();
        });
    },
    sendEmail: async({ commit }, payload) => {
        let { id, ...otherProps } = payload;
        payload = {
            ...otherProps,
        };
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                commit("SEND_EMAIL_LOADING", true);
                axios
                    .post(`${poBaseUrl}/orders/send-email/${id}`, payload)
                    .then((res) => {
                        commit("SEND_EMAIL_LOADING", false);
                        if (typeof res !== "undefined" && res.status === 200) {
                            // commit('SET_PO_DETAIL', res.data)
                            resolve(res.data);
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
                                commit("SEND_EMAIL_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SEND_EMAIL_LOADING", false);
                            //reject(err.error)
                            //commit("SET_PO_DETAIL_LOADING", false)
                            reject(err.error);
                        }
                        /*
					commit('SEND_EMAIL_LOADING', false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject(err.message)
					}*/
                    });
            }
            proceed();
        });
    },
    //
    fetchPoAllNew: async({ commit }, params) => {
        let attempt = false;
        commit("SET_PO_ALL", []);
        commit("SET_PO_ALL_LOADING", true);

        return new Promise((resolve, reject) => {
            function proceed() {
                const orderState = params.state ? "/" + params.state : "";
                let buildUrl = {
                    url: `${poBaseUrl}/v3/orders${orderState}`,
                    method: "get",
                    params: {
                        page: params.page,
                        orderType: "SO",
                    },
                    cancelToken: params.cancelToken,
                };

                if (typeof params.special !== "undefined") {
                    if (params.query !== null && params.query !== "") {
                        buildUrl.params.search = params.query;
                    }
                    buildUrl.cancelToken = params.cancelToken;
                }
            if (!params.checkPaginate) {
                axios(buildUrl)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                if (typeof res.data.data !== "undefined") {
                                    if (res.data.data.length > 0) {
                                        res.data.data.map((val, k) => {
                                            res.data.data[k].status =
                                                val.status === "partial_shipped" ?
                                                "partially shipped" :
                                                val.status;
                                        });
                                    }

                                    let buildData = {
                                        results: {
                                            current_page: 0,
                                            data: res.data.data,
                                            first_page_url: null,
                                            from: 1,
                                            last_page: 1,
                                            last_page_url: null,
                                            links: [],
                                            next_page_url: null,
                                            path: "",
                                            per_page: 0,
                                            prev_page_url: null,
                                            to: 0,
                                            total: 0,
                                        },
                                    };

                                    if (typeof res.data.meta !== "undefined") {
                                        let getMeta = res.data.meta;
                                        buildData.results.current_page = getMeta.current_page;
                                        buildData.results.links = getMeta.links;
                                        buildData.results.per_page = getMeta.per_page;
                                        buildData.results.total = getMeta.total;
                                        buildData.results.to = getMeta.to;
                                        buildData.results.from = getMeta.from;
                                        buildData.results.last_page = getMeta.last_page;
                                    }

                                    if (typeof res.data.data.links !== "undefined") {
                                        let getLinks = res.data.data.links;
                                        buildData.results.next_page_url = getLinks.next;
                                        buildData.results.prev_page_url = getLinks.prev;
                                        buildData.results.last_page_url = getLinks.last;
                                        buildData.results.first_page_url = getLinks.first;
                                    }

                                    let shippedPO = buildData.results;
                                    commit("SET_PO_ALL", buildData);
                                    commit("SET_ALL_PO_PAGINATION", shippedPO);
                                }
                            }
                        }
                        commit("SET_PO_ALL_LOADING", false);
                        resolve("ok");
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
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            reject(err.error);
                        }
                    });
                } else {
					let pathData =
						typeof router.history.current.query.tab !== "undefined"
							? router.history.current.query.tab
							: null;
					let posData = state.allSalesOrdersData[pathData + "_" + params.page];
					commit("SET_PO_ALL", posData);
					commit("SET_PO_ALL_LOADING", false);
				}
            }
            proceed();
        });
    },
    fetchPoPendingNew: async({ commit }, params) => {
        let attempt = false;

        commit("SET_PO_PENDING", []);
        commit("SET_PO_PENDING_LOADING", true);

        return new Promise((resolve, reject) => {
            function proceed() {
                let buildUrl = {
                    url: `${poBaseUrl}/v3/orders/pending`,
                    method: "get",
                    params: {
                        page: params.page,
                        orderType: "SO",
                    },
                };

                if (typeof params.special !== "undefined") {
                    if (params.query !== null && params.query !== "") {
                        buildUrl.params.search = params.query;
                    }
                    buildUrl.cancelToken = params.cancelToken;
                }

                axios(buildUrl)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                if (typeof res.data.data !== "undefined") {
                                    if (res.data.data.length > 0) {
                                        res.data.data.map((val, k) => {
                                            res.data.data[k].status =
                                                val.status === "partial_shipped" ?
                                                "partially shipped" :
                                                val.status;
                                        });
                                    }

                                    let buildData = {
                                        results: {
                                            current_page: 0,
                                            data: res.data.data,
                                            first_page_url: null,
                                            from: 1,
                                            last_page: 1,
                                            last_page_url: null,
                                            links: [],
                                            next_page_url: null,
                                            path: "",
                                            per_page: 0,
                                            prev_page_url: null,
                                            to: 0,
                                            total: 0,
                                        },
                                    };

                                    if (typeof res.data.meta !== "undefined") {
                                        let getMeta = res.data.meta;
                                        buildData.results.current_page = getMeta.current_page;
                                        buildData.results.links = getMeta.links;
                                        buildData.results.per_page = getMeta.per_page;
                                        buildData.results.total = getMeta.total;
                                        buildData.results.to = getMeta.to;
                                        buildData.results.from = getMeta.from;
                                        buildData.results.last_page = getMeta.last_page;
                                    }

                                    if (typeof res.data.data.links !== "undefined") {
                                        let getLinks = res.data.data.links;
                                        buildData.results.next_page_url = getLinks.next;
                                        buildData.results.prev_page_url = getLinks.prev;
                                        buildData.results.last_page_url = getLinks.last;
                                        buildData.results.first_page_url = getLinks.first;
                                    }

                                    let shippedPO = buildData.results;
                                    commit("SET_PO_PENDING", buildData);
                                    commit("SET_PENDING_PO_PAGINATION", shippedPO);
                                }
                            }
                        }
                        commit("SET_PO_PENDING_LOADING", false);
                        resolve("ok");
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
                                commit("SET_PO_PENDING_LOADING", false);
                                reject(err.message);

                                // if (err.message == "UnAuthenticated") {
                                //     reject("Token has been expired. Please reload the page.");
                                // } else {
                                //     reject(err.message);
                                // }
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_PO_PENDING_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    fetchPoShippedNew: async({ commit }, params) => {
        let attempt = false;
        commit("SET_PO_SHIPPED", []);
        commit("SET_PO_SHIPPED_LOADING", true);

        return new Promise((resolve, reject) => {
            function proceed() {
                let buildUrl = {
                    url: `${poBaseUrl}/v3/orders/shipped`,
                    method: "get",
                    params: {
                        page: params.page,
                        orderType: "SO",
                    },
                };

                if (typeof params.special !== "undefined") {
                    if (params.query !== null && params.query !== "") {
                        buildUrl.params.search = params.query;
                    }
                    buildUrl.cancelToken = params.cancelToken;
                }

                axios(buildUrl)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                if (typeof res.data.data !== "undefined") {
                                    let buildData = {
                                        results: {
                                            current_page: 0,
                                            data: res.data.data,
                                            first_page_url: null,
                                            from: 1,
                                            last_page: 1,
                                            last_page_url: null,
                                            links: [],
                                            next_page_url: null,
                                            path: "",
                                            per_page: 0,
                                            prev_page_url: null,
                                            to: 0,
                                            total: 0,
                                        },
                                    };

                                    if (typeof res.data.meta !== "undefined") {
                                        let getMeta = res.data.meta;
                                        buildData.results.current_page = getMeta.current_page;
                                        buildData.results.links = getMeta.links;
                                        buildData.results.per_page = getMeta.per_page;
                                        buildData.results.total = getMeta.total;
                                        buildData.results.to = getMeta.to;
                                        buildData.results.from = getMeta.from;
                                        buildData.results.last_page = getMeta.last_page;
                                    }

                                    if (typeof res.data.data.links !== "undefined") {
                                        let getLinks = res.data.data.links;
                                        buildData.results.next_page_url = getLinks.next;
                                        buildData.results.prev_page_url = getLinks.prev;
                                        buildData.results.last_page_url = getLinks.last;
                                        buildData.results.first_page_url = getLinks.first;
                                    }

                                    let shippedPO = buildData.results;
                                    commit("SET_PO_SHIPPED", buildData);
                                    commit("SET_SHIPPED_PO_PAGINATION", shippedPO);
                                }
                            }
                        }
                        commit("SET_PO_SHIPPED_LOADING", false);
                        resolve("ok");
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
                                commit("SET_PO_SHIPPED_LOADING", false);
                                reject(err.message);

                                // if (err.message == "UnAuthenticated") {
                                //     reject("Token has been expired. Please reload the page.");
                                // } else {
                                //     reject(err.message);
                                // }
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_PO_SHIPPED_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    fetchPoPending: async({ commit }, page) => {
        commit("SET_PO_PENDING", []);
        commit("SET_PO_PENDING_LOADING", true);

        await axios
            .get(`${poBaseUrl}/purchaseorder/pending?page=${page}`)
            .then((res) => {
                if (res.status === 200) {
                    if (res.data) {
                        let pendingPO = res.data.results;

                        commit("SET_PO_PENDING", res.data);
                        commit("SET_PENDING_PO_PAGINATION", pendingPO);
                        return Promise.resolve("ok");
                    } else {
                        return Promise.reject("not ok");
                    }
                } else {
                    return Promise.reject("not ok");
                }
            })
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    if (typeof err.message !== "undefined") {
                        if (err.message == "UnAuthenticated") {
                            return Promise.reject(
                                "Token has been expired. Please reload the page."
                            );
                        } else {
                            return Promise.reject(err.message);
                        }
                    }
                }
            })
            .finally(() => {
                commit("SET_PO_PENDING_LOADING", false);
            });
    },
    fetchPoShipped: async({ commit }, page) => {
        commit("SET_PO_SHIPPED", []);
        commit("SET_PO_SHIPPED_LOADING", true);

        await axios
            .get(`${poBaseUrl}/purchaseorder/shipped?page=${page}`)
            .then((res) => {
                if (res.status === 200) {
                    if (res.data) {
                        let shippedPO = res.data.results;

                        commit("SET_PO_SHIPPED", res.data);
                        commit("SET_SHIPPED_PO_PAGINATION", shippedPO);
                        return Promise.resolve("ok");
                    } else {
                        return Promise.reject("not ok");
                    }
                } else {
                    return Promise.reject("not ok");
                }
            })
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    if (typeof err.message !== "undefined") {
                        if (err.message == "UnAuthenticated") {
                            return Promise.reject(
                                "Token has been expired. Please reload the page."
                            );
                        } else {
                            return Promise.reject(err.message);
                        }
                    }
                }
            })
            .finally(() => {
                commit("SET_PO_SHIPPED_LOADING", false);
            });
    },
    setCurrentSOTab({ commit }, payload) {
        commit("setCurrentSOTab", payload);
    },
    setSoCurrentAllTab({ commit }, payload) {
        commit("SET_SO_CURRENT_ALL_TAB", payload);
    },
    setSoCurrentOpenTab({ commit }, payload) {
        commit("SET_SO_CURRENT_OPEN_TAB", payload);
    },
    setSoCurrentInProgressTab({ commit }, payload) {
        commit("SET_SO_CURRENT_IN_PROGRESS_TAB", payload);
    },
    setSoCurrentBookingTab({ commit }, payload) {
        commit("SET_SO_CURRENT_BOOKING_TAB", payload);
    },
    fetchPoShipmentDetails: async({ commit }, payload) => {
        let attempt = false;
        commit("SET_PO_SHIPMENT_DETAILS_LOADING", true);
        return new Promise((resolve, reject) => {
            let limit = 1;
            let start = 0;

            function proceed() {
                let po = new PoModel(betaBaseUrl);
                po.fetchShipmentPoDetails(payload)
                    .then((res) => {
                        commit("SET_PO_SHIPMENT_DETAILS_LOADING", false);
                        commit("SET_PO_SHIPMENT_DETAILS", res.data);
                        resolve("ok");
                    })
                    .catch((err) => {
                        if (typeof err.message !== "undefined") {
                            if (!attempt) {
                                attempt = true;
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        if (start == limit) {
                                            store
                                                .dispatch("refreshToken")
                                                .then(() => {
                                                    attempt = false;
                                                    start = 0;
                                                    proceed();
                                                })
                                                .catch((e) => {
                                                    console.log(e);
                                                    commit("SET_PO_SHIPMENT_DETAILS_LOADING", false);
                                                    reject(
                                                        "Token has been expired. Please reload the page."
                                                    );
                                                });
                                        } else {
                                            start++;
                                            proceed();
                                            attempt = false;
                                        }
                                        clearInterval(t);
                                    }
                                }, 300);
                            } else {
                                commit("SET_PO_SHIPMENT_DETAILS_LOADING", false);
                                // reject("Token has been expired. Please reload the page.");
                                reject(err.message);
                            }
                        }
                        if (typeof err.error !== "undefined") {
                            commit("SET_PO_SHIPMENT_DETAILS_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    updateState: async({ commit }, payload) => {
        commit("SET_ORDER_STATE_LOADING", true);
        await axios
            .put(`${poBaseUrl}/v3/orders/state`, payload)
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_ORDER_STATE_LOADING", false);
            });
    },
    fetchImportName: async({ commit }) => {
        commit("SET_IMPORT_NAME_LOADING", true);
        await axios
            .get(`import-names`)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        commit("SET_IMPORT_NAME_VALUE", res.data.data);
                    }
                }
                commit("SET_IMPORT_NAME_LOADING", false);
            })
            .catch((err) => {
                commit("SET_IMPORT_NAME_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            });
    },
    cancelChangeRequest: async({ commit }, payload) => {
        commit("SET_CANCEL_CHANGE_REQUEST_LOADING", true);
        await axios
            .put(`${poBaseUrl}/v3/orders/cancel-change-request`, payload)
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_CANCEL_CHANGE_REQUEST_LOADING", false);
            });
    },
    fetchOrderCounter: async({ commit }) => {
        await axios
            .get(`${poBaseUrl}/v3/orders/tab/count?orderType=SO`)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        commit("SET_ORDER_COUNTER_VALUE", res.data.data);
                    }
                }
            })
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            });
    },
    reviewPayment: async({ commit }, payload) => {
        commit("SET_REVIEW_PAYMENT_LOADING", true);
        await axios
            .post(`${poBaseUrl}/v3/orders/mark-as-paid/action`, payload)
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_REVIEW_PAYMENT_LOADING", false);
            });
    },
    downloadPaymentDocument: async({ commit }, orderId) => {
        let attempt = false;
        commit("SET_PAYMENT_DOCUMENT_DOWNLOAD_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                axios({
                        url: `${poBaseUrl}/v3/orders/mark-as-paid/download/${orderId}`,
                        method: "GET",
                        responseType: "blob",
                    })
                    .then((response) => {
                        commit("SET_PAYMENT_DOCUMENT_DOWNLOAD_LOADING", false);
                        let fileURL = window.URL.createObjectURL(new Blob([response.data]));
                        let fileLink = document.createElement("a");
                        fileLink.href = fileURL;
                        fileLink.setAttribute(
                            "download",
                            `payment-document-${orderId}.pdf`
                        );
                        fileLink.click();
                        resolve("ok");
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
                                commit("SET_PAYMENT_DOCUMENT_DOWNLOAD_LOADING", false);
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_PAYMENT_DOCUMENT_DOWNLOAD_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    fetchOrdersReport: async({ commit }, payload) => {
        let attempt = false;
        commit("SET_CSV_REPORT_LOADING", true);
        let apiURL = `${process.env.VUE_APP_PO_URL}/po/get-report?orderType=SO&state=${payload.state}`;
        if (payload.exportType == "all") {
            apiURL = `${process.env.VUE_APP_PO_URL}/po/get-report-multiple?orderType=SO&state=${payload.state}`;
        }
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(apiURL, {
                        responseType: "blob",
                        cancelToken: payload.cancelToken,
                    })
                    .then((res) => {
                        commit("SET_CSV_REPORT_LOADING", false);
                        commit("SET_CSV_REPORT_DATA", res.data);
                        resolve("ok");
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
                                commit("SET_CSV_REPORT_LOADING", false);
                                reject(err);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_CSV_REPORT_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    clearAllSalesOrdersData: ({ commit }, payload) => {
		commit("SET_ALL_SO_DATA_CLEAR", payload);
	},
    fetchSuppliersSku: async ({
        commit
    }, Id) => {
        commit("SET_SUPPLIERS_SKU_LOADING", true)
        // let reqInstance = axios.create({
        //     headers: {
        //         Authorization: 'Bearer'
        //     }
        // })

        await axios.get(`${poBaseUrl}/v2/products-po-dropdown/${Id}?contact_type=buyer`)
            .then(res => {
                // console.log('ok',typeof res == 'undefined')
                if (typeof res !== 'undefined' && typeof res.status !== 'undefined') {
                    if (res.status === 200) {
                        if (res.data) {
                            if (res.data.results) {
                                let results = res.data.results
                                commit("SET_SUPPLIERS_SKU_LOADING", false)

                                // let pagination = {
                                //     currentTab: 1,
                                //     current_page: results.current_page,
                                //     total: results.total,
                                //     last_page: results.last_page,
                                //     per_page: results.per_page,
                                //     data: results
                                // }

                                commit("SET_SUPPLIERS_SKU", results)
                            } else {
                                commit("SET_SUPPLIERS_SKU", [])
                            }

                        } else {
                            commit("SET_SUPPLIERS_SKU", [])
                        }
                    }
                }else{
                    commit("SET_SUPPLIERS_SKU", [])
                    commit("SET_SUPPLIERS_SKU_LOADING", false)
                }
            })
            .catch(err => {
                commit("SET_SUPPLIERS_SKU_LOADING", false)
                if (typeof err.error !== 'undefined') {
                    return Promise.reject(err.error)
                } else {
                    if (typeof err.message !== 'undefined') {
                        return Promise.reject(err)
                    }
                }
            })
        },
};

const mutations = {
    SET_PO_SELECT_PRODUCTS_LOADING: (state, payload) => {
        state.poSelectProductsLoading = payload;
    },
    SET_SHIPPED_PAGE: (state, payload) => {
        state.shippedPage = payload;
    },
    SET_PENDING_PAGE: (state, payload) => {
        state.pendingPage = payload;
    },
    SET_ALL_PAGE: (state, payload) => {
        state.allPage = payload;
    },
    SET_CURRENT_PAGE: (state, payload) => {
        state.currentPage = payload;
    },
    setPo: (state, payload) => {
        state.currentPoSelected = payload;
    },
    SET_DELETE_PO_LOADING: (state, payload) => {
        state.poDeleteLoading = payload;
    },
    SET_PO: (state, payload) => {
        state.po = payload;
    },
    SET_SO_CREATE_LOADING: (state, payload) => {
        state.soCreateLoading = payload;
    },
    SET_SO_DRAFT_LOADING: (state, payload) => {
        state.soDraftLoading = payload;
    },
    SET_PO_SAVE_ADD_LOADING: (state, payload) => {
        state.poSaveAddLoading = payload;
    },
    SET_VENDOR_LISTS_LOADING: (state, payload) => {
        state.vendorListsLoading = payload;
    },
    SET_VENDOR_LISTS: (state, payload) => {
        state.vendorLists = payload;
    },
    SET_BUYER_LISTS_LOADING: (state, payload) => {
        state.buyerListsLoading = payload;
    },
    SET_BUYER_LISTS: (state, payload) => {
        state.buyerLists = payload;
    },
    SET_PO_DETAIL_LOADING: (state, payload) => {
        state.poDetailLoading = payload;
    },
    SET_PO_DETAIL: (state, payload) => {
        state.poDetail = payload;
    },
    SET_PO_DOWNLOAD_LOADING: (state, payload) => {
        state.downloadLoading = payload;
    },
    SEND_EMAIL_LOADING: (state, payload) => {
        state.sendEmailLoading = payload;
    },
    SET_PENDING_PO: (state, payload) => {
        state.pendingPO = payload;
    },
    SET_ALL_PO_PAGINATION: (state, payload) => {
        state.allPOPagination = payload;
    },
    SET_PENDING_PO_PAGINATION: (state, payload) => {
        state.pendingPOPagination = payload;
    },
    SET_SHIPPED_PO_PAGINATION: (state, payload) => {
        state.shippedPOPagination = payload;
    },
    SET_PO_GLOBAL_QUERY: (state, payload) => {
        state.poGlobalQuery = payload;
    },
    SET_PO_LOCAL_QUERY: (state, payload) => {
        state.soLocalQuery = payload;
    },
    //
    SET_PO_ALL: (state, payload) => {
        state.poAll = payload;

        let pathData =
			typeof router.history.current.query.tab !== "undefined"
				? router.history.current.query.tab
				: null;

		state.allSalesOrdersData[
			pathData + "_" + payload?.results?.current_page
		] = payload;
    },
    SET_PO_ALL_LOADING: (state, payload) => {
        state.poAllLoading = payload;
    },
    SET_PO_PENDING: (state, payload) => {
        state.poPending = payload;
    },
    SET_PO_PENDING_LOADING: (state, payload) => {
        state.poPendingLoading = payload;
    },
    SET_PO_SHIPPED: (state, payload) => {
        state.poShipped = payload;
    },
    SET_PO_SHIPPED_LOADING: (state, payload) => {
        state.poShippedLoading = payload;
    },
    setCurrentSOTab: (state, payload) => {
        state.currentSoTab = payload;
    },
    SET_SO_CURRENT_ALL_TAB: (state, payload) => {
        state.currentSoAllTab = payload;
    },
    SET_SO_CURRENT_OPEN_TAB: (state, payload) => {
        state.currentSoOpenTab = payload;
    },
    SET_SO_CURRENT_IN_PROGRESS_TAB: (state, payload) => {
        state.currentSoInProgressTab = payload;
    },
    SET_SO_CURRENT_BOOKING_TAB: (state, payload) => {
        state.currentSoBookingTab = payload;
    },
    SET_PO_SHIPMENT_DETAILS: (state, payload) => {
        state.poShipmentDetails = payload;
    },
    SET_PO_SHIPMENT_DETAILS_LOADING: (state, payload) => {
        state.poShipmentDetailsLoading = payload;
    },
    SET_PO_GLOBAL_SEARCH_LOADING: (state, payload) => {
        state.poGlobalSearchLoading = payload;
    },
    SET_ORDER_STATE_LOADING: (state, payload) => {
        state.orderStateLoading = payload;
    },
    SET_IMPORT_NAME_VALUE: (state, payload) => {
        state.importName = payload;
    },
    SET_IMPORT_NAME_LOADING: (state, payload) => {
        state.importNameLoading = payload;
    },
    SET_CANCEL_CHANGE_REQUEST_LOADING: (state, payload) => {
        state.cancelChangeRequestLoading = payload;
    },
    SET_ORDER_COUNTER_VALUE: (state, payload) => {
        state.orderCounterValue = payload;
    },
    SET_REVIEW_PAYMENT_LOADING: (state, payload) => {
        state.reviewPaymentLoading = payload;
    },
    SET_PAYMENT_DOCUMENT_DOWNLOAD_LOADING: (state, payload) => {
        state.paymentDocumentDownloading = payload;
    },
    SET_CSV_REPORT_LOADING: (state, payload) => {
        state.csvReportLoading = payload;
    },
    SET_CSV_REPORT_DATA: (state, payload) => {
        state.setCsvReportData = payload;
    },
    SET_ALL_SO_DATA_CLEAR: (state) => {
		state.allSalesOrdersData = {};
		state.allPage = 1;
	},
    SET_SUPPLIERS_SKU_LOADING: (state, payload) => {
        state.suppliersSkusLoading = payload;
    },
    SET_SUPPLIERS_SKU: (state, payload) => {
        state.suppliersSkus = payload;
    },
    restProductAndSkuItemsSuppliers: (state, payload) => {
        state.suppliersSkus = payload;
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};