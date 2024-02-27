/** @format */

import axios from "axios";
import router from '../../router/index'
// import Shipment from './../../custom/ShipmentResource'
import store from "../";
import PoModel from "../../models/PoModel";
import ProductModel from "../../models/ProductModel";
//import _ from 'lodash'

const state = {
    posShipment: [],
    currentPage: 1,
    allPage: 1,
    pendingPage: 1,
    shippedPage: 1,
    currentPoSelected: null,
    po: [],
    poGlobalQuery: "",
    poLocalQuery: "",
    // new
    poPending: [],
    poShipped: [],
    poSelectProductsLoading: false,
    poPendingLoading: false,
    poShippedLoading: false,
    poGlobalSearchLoading: false,
    currentPoTab: 0,
    currentPoAllTab: 0,
    currentPoOpenTab: 0,
    currentPoBookingTab: 0,
    currentPoInProgressTab: 0,
    //
    editPo: {},
    poCreateLoading: false,
    poDraftLoading: false,
    poSaveAddLoading: false,
    vendorLists: [],
    poDeleteLoading: false,
    vendorListsLoading: false,
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
    purchaseOrderOptions: [],
    purchaseOrderOptionsLoading: false,
    poShipments: [],
    orderStateLoading: false,
    importName: [],
    importNameLoading: false,
    cancelChangeRequestLoading: false,
    orderCounterValue: "",
    uploadPaymentDocuments: false,
    csvReportLoading: false,
    setCsvReportData: [],
    allPosData: {},
    vendorSkus:[],
    vendorSkusLoading:false,
    // Notes
    orderNotesLoading:false,
    orderNotes:[],
    addNotesLoading:false,
    addNotes:[],
    editNotesLoading:false,
    editNotes:[],
    deleteNotesLoading:false,
    downloadNotesLoading:false,
    downloadNotes:[]
};

const getters = {
    getPurchaseOrderOptions: (state) => state.purchaseOrderOptions,
    getPurchaseOrderOptionsLoading: (state) => state.purchaseOrderOptionsLoading,
    fetchPoSelectProductsLoading: (state) => state.poSelectProductsLoading,
    getCurrentPoTab: (state) => state.currentPoTab,
    getCurrentPoOpenTab: (state) => state.currentPoOpenTab,
    getCurrentPoAllTab: (state) => state.currentPoAllTab,
    getCurrentPoBookingTab: (state) => state.currentPoBookingTab,
    getCurrentPoInProgressTab: (state) => state.currentPoInProgressTab,
    getCurrentPage: (state) => state.currentPage,
    getAllPoPage: (state) => state.allPage,
    getPendingPage: (state) => state.pendingPage,
    getShippedPage: (state) => state.shippedPage,
    getCurrentTab: (state) => state.currentTab,
    getPoGlobalSearchLoading: (state) => state.poGlobalSearchLoading,
    getCurrentSelectedPo: (state) => state.currentPoSelected,
    getAllPo: (state) => state.po,
    getPoCreateLoading: (state) => state.poCreateLoading,
    getPoDraftLoading: (state) => state.poDraftLoading,
    getPoSaveAddLoading: (state) => state.poSaveAddLoading,
    getVendorListsLoading: (state) => state.vendorListsLoading,
    getVendorLists: (state) => state.vendorLists,
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
    getPoLocalQuery: (state) => state.poLocalQuery,
    getAllPos: (state) => state.poAll,
    getAllPosLoading: (state) => state.poAllLoading,
    getAllPosPagination: (state) => state.allPOPagination,
    getOrdersStateLoading: (state) => state.orderStateLoading,
    getPosShipment: state => state.posShipment,
    getImportName: (state) => state.importName,
	getImportNameLoading: (state) => state.importNameLoading,
    getCancelChangeRequestLoading: (state) => state.cancelChangeRequestLoading,
	getOrderCounterValue: (state) => state.orderCounterValue,
    getUploadPaymentDocuments: (state) => state.uploadPaymentDocuments,
    getCsvReportLoading: (state) => state.csvReportLoading,
    getSetCsvReportData: (state) => state.setCsvReportData,
    getVendorSkus: state => state.vendorSkus,
    getVendorSkusLoading: state => state.vendorSkusLoading,
    // Notes
    getOrderNotesLoading: state => state.orderNotesLoading,
    getOrderNotes: state => state.orderNotes,
    getAddNotesLoading: state => state.addNotesLoading,
    getAddNotes: state => state.addNotes,
    getEditNotesLoading: state => state.editNotesLoading,
    getEditNotes: state => state.editNotes,
    getDeleteNotesLoading: state => state.deleteNotesLoading,
    getDownloadNotesLoading: state => state.downloadNotesLoading,
    getDownloadNotes: state => state.downloadNotes
};

const betaBaseUrl = process.env.VUE_APP_BASE_URL;
const poBaseUrl = process.env.VUE_APP_PO_URL;
// const poStagingUrl = process.env.VUE_APP_POSTAGING_BASE_URL

const actions = {
    fetchPurchaseOrderOptions({ commit },params ) {
        
        return new Promise((resolve, reject) => {
            let pm = new PoModel(poBaseUrl)
            pm.fetchPurchaseOrderOptions(params).then(response => {
                let finalData = response.data
                if ( finalData.length > 0 ) {
                    finalData.map( (fd, key) => {
                        finalData[key].loading = false
                    })
                    commit('SET_PO_OPTIONS', finalData)
                    resolve(finalData)
                } else {
                    commit('SET_PO_OPTIONS', [])
                    resolve([])
                }
            }).catch(e => {
                reject(e)
                console.log(e)
            })
        })
    },
    fetchPosByShipmentId: ({ commit }, shipment_id) => {
        
        return new Promise((resolve ,reject) => {
            let po = new PoModel(poBaseUrl)
            po.fetchPosByShipmentId(shipment_id).then(response => {
                let {
                    data
                } = response

                if ( typeof data!=='undefined' && typeof data.items!=='undefined' )
                    commit('SET_POS_SHIPMENT', data.items)
                resolve(response)
            }).catch(e => {
                reject(e)
            })
        })
    },
    setPoAll: ({ commit }, payload) => {
        commit("SET_PO_ALL", payload);
    },
    setPoShipped: ({ commit }, payload) => {
        commit("SET_PO_SHIPPED", payload);
    },
    setPoPending: ({ commit }, payload) => {
        commit("SET_PO_PENDING", payload)
    },
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
    setPoLocalQuery: ({ commit }, payload) => {
        commit("SET_PO_LOCAL_QUERY", payload);
    },
    fetchPurchaseOrderProducts: async(data, purchase_order_id) => {
        return new Promise((resolve, reject) => {
            let po = new PoModel(poBaseUrl)
            po.fetchPurchaseOrderProducts(purchase_order_id).then( res => {
                resolve(res)
            }).catch(e => {
                reject(e)
            })
        })
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
                        url: `${poBaseUrl}/orders/${item.id}/download-receipt?order_type=PO`,
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
                            `PO-${
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
                console.log("proceed");
                commit("SET_PO_DETAIL_LOADING", true);
                let po = new PoModel(poBaseUrl);
                console.log("po details");
                po.findById(id)
                    .then((res) => {
                        console.log("resolve po");
                        commit("SET_PO_DETAIL_LOADING", false);
                        commit("SET_PO_DETAIL", res.data);
                        resolve(res.data);
                    })
                    .catch((err) => {
                        console.log("err", err);
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
            "SET_PO_DRAFT_LOADING" :
            "SET_PO_CREATE_LOADING";

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
            "SET_PO_DRAFT_LOADING" :
            "SET_PO_CREATE_LOADING";

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
    importPOs: ({ commit }, payload) => {
        console.log(commit);
        const formData = new FormData();
        formData.append("file", payload);
        return new Promise((resolve, reject) => {
            axios
                .post(`${process.env.VUE_APP_PO_URL}/orders/import`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((result) => {
                    resolve(result);
                })
                .catch((error) => {
                    reject(error);
                });
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
                        params: { id: ids, order_type: "PO" },
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
                        orderType: "PO",
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
					let posData = state.allPosData[pathData + "_" + params.page];
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
                        orderType: "PO",
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
                        orderType: "PO",
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
    setCurrentPOTab({ commit }, payload) {
        commit("setCurrentPOTab", payload);
    },
    setPoCurrentAllTab({ commit }, payload) {
        commit("SET_PO_CURRENT_ALL_TAB", payload);
    },
    setPoCurrentOpenTab({ commit }, payload) {
        commit("SET_PO_CURRENT_OPEN_TAB", payload);
    },
    setPoCurrentBookingTab({ commit }, payload) {
        commit("SET_PO_CURRENT_BOOKING_TAB", payload);
    },
    setPoCurrentInProgressTab({ commit }, payload) {
        commit("SET_PO_CURRENT_IN_PROGRESS_TAB", payload);
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
            .get(`${poBaseUrl}/v3/orders/tab/count?orderType=PO`)
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
    uploadPaymentDocuments: ({ commit }, payload) => {
        commit("SET_UPLOAD_PAYMENT_DOCUMENTS", true);
        const formData = new FormData();
        formData.append("document", payload.file);
        formData.append("note", payload.notes);
        formData.append("order_id", payload.order_id);
        formData.append("status", payload.status);
        return new Promise((resolve, reject) => {
            axios
                .post(`${poBaseUrl}/v3/orders/mark-as-paid`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((result) => {
                    commit("c", false);
                    resolve(result);
                })
                .catch((error) => {
                    reject(error);
                });
        });
    },
    fetchOrdersReport: async({ commit }, payload) => {
        let attempt = false;
        commit("SET_CSV_REPORT_LOADING", true);
        let apiURL = `${process.env.VUE_APP_PO_URL}/po/get-report?orderType=PO&state=${payload.state}`;
        if (payload.exportType == "all") {
            apiURL = `${process.env.VUE_APP_PO_URL}/po/get-report-multiple?orderType=PO&state=${payload.state}`;
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
    clearAllPosData: ({ commit }, payload) => {
		commit("SET_ALL_PO_DATA_CLEAR", payload);
	},
    // Vendor Skus Fetch
    fetchVendorSku: async({
        commit
    }, Id) => {
    commit("SET_VENDOR_SKU_LOADING", true)
        // let reqInstance = axios.create({
        //     headers: {
        //         Authorization: 'Bearer'
        //     }
        // })

        await axios.get(`${poBaseUrl}/v2/products-po-dropdown/${Id}?contact_type=vendor`)
        .then(res => {
            // console.log('ok',res)
            if (typeof res !== 'undefined' && typeof res.status !== 'undefined') {
                if (res.status === 200) {
                    if (res.data) {
                        if(res.data.results){
                            let results = res.data.results
                            commit("SET_VENDOR_SKU_LOADING", false)

                            // let pagination = {
                            //     currentTab: 1,
                            //     current_page: results.current_page,
                            //     total: results.total,
                            //     last_page: results.last_page,
                            //     per_page: results.per_page,
                            //     data: results
                            // }

                            commit("SET_VENDOR_SKU", results)
                        }else{
                            commit("SET_VENDOR_SKU", [])   
                        }
                        
                    }else{
                        commit("SET_VENDOR_SKU", [])
                    }
                }
            } else {
                commit("SET_VENDOR_SKU", [])
                commit("SET_VENDOR_SKU_LOADING", false)
            }
        })
        .catch(err => {
            commit("SET_VENDOR_SKU_LOADING", false)
            if (typeof err.error !== 'undefined') {
                return Promise.reject(err.error)
            } else {
                if (typeof err.message !== 'undefined') {
                    return Promise.reject(err)
                }
            }
        })
    },

    // Notes

    fetchAllNotesApi: async ({ commit }, Id) => {
        let attempt = false
        commit('SET_ORDER_NOTES_LOADING', true)

        return new Promise((resolve, reject) => {
            function proceed() {
                axios.get(`${poBaseUrl}/v2/order-note/${Id}`)
                    .then(res => {
                        if (res.status === 200) {
                            if (res.data) {
                                if (res.data.results) {
                                    let results = res.data.results
                                    commit('SET_ORDER_NOTES_LOADING', false)
                                    let pagination = {
                                            currentTab: 1,
                                            current_page: results.current_page,
                                            total: results.total,
                                            last_page: results.last_page,
                                            per_page: results.per_page,
                                            data: results.data
                                        }
                                    commit('SET_ORDER_NOTES', pagination)
                                    resolve(res)
                                }
                            }
                        }
                    })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_ORDER_NOTES_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_ORDER_NOTES_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    addNotesApi: async ({ commit }, payload) => {
        let attempt = false
        commit('SET_ADD_NOTES_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                let formdata = new FormData()
                formdata.append("purchase_order_id", payload.id)
                formdata.append("title", payload.title)
                formdata.append("body", payload.body)
                for (var i = 0; i < payload.files.length; i++) {
                    formdata.append(`documents[]`, payload.files[i]);
                }
                axios.post(`${poBaseUrl}/v2/add-order-note`, formdata, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    commit('SET_ADD_NOTES_LOADING', false)
                    resolve(response.data)
                    commit('SET_ADD_NOTES', response.data.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_ADD_NOTES_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.message !== 'undefined') {
                            commit("SET_ADD_NOTES_LOADING", false)
                            reject(err.message)
                        }
                    })
            }
            proceed()
        })
    },
    editNotesApi: async ({ commit }, payload) => {
        let attempt = false
        commit('SET_EDIT_NOTES_LOADING', true)
        return new Promise((resolve, reject) => {
            
            function proceed() {
                let formdata = new FormData()
                // formdata.append("purchase_order_id",this.orderId)
                formdata.append("title", payload.data.title)
                formdata.append("body", payload.data.body)
                formdata.append("_method", 'PUT')
                for (var j = 0; j < payload.files.length; j++) {
                    formdata.append(`documents[]`, payload.files[j]);
                }
                axios.post(`${poBaseUrl}/v2/edit-order-note/${payload.id}`, formdata,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    commit('SET_EDIT_NOTES_LOADING', false)
                    commit('SET_EDIT_NOTES', response.data.data)
                    resolve(response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_EDIT_NOTES_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_EDIT_NOTES_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    deleteNotesApi: async ({ commit }, Id) => {
        let attempt = false
        commit('SET_DELETE_NOTES_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.delete(`${poBaseUrl}/v2/delete-order-note/${Id}`).then(response => {
                    commit('SET_DELETE_NOTES_LOADING', false)
                    resolve(response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_DELETE_NOTES_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_DELETE_NOTES_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    downloadNotesDoc: async ({ commit }, payload) => {
        let attempt = false
        commit('SET_DOWNLOAD_NOTES_LOADING', true)

        return new Promise((resolve, reject) => {
            function proceed() {
                axios({
                    url: `${poBaseUrl}/v2/order-note/download-document/${payload.item.id}`,
                    method: 'GET',
                    responseType: 'blob'
                }).then(response => {
                    commit('SET_DOWNLOAD_NOTES_LOADING', false)
                    commit("SET_DOWNLOAD_DATA", response.data)
                    resolve('ok')

                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_DOWNLOAD_NOTES_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_DOWNLOAD_NOTES_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },

};

const mutations = {
    SET_PO_SHIPMENTS:(state, payload) => {
        state.poShipments = payload
    },
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
    SET_PO_CREATE_LOADING: (state, payload) => {
        state.poCreateLoading = payload;
    },
    SET_PO_DRAFT_LOADING: (state, payload) => {
        state.poDraftLoading = payload;
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
        state.poLocalQuery = payload;
    },
    //
    SET_PO_ALL: (state, payload) => {
        state.poAll = payload;
        let pathData =
			typeof router.history.current.query.tab !== "undefined"
				? router.history.current.query.tab
				: null;

		state.allPosData[pathData + "_" + payload?.results?.current_page] = payload;
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
    setCurrentPOTab: (state, payload) => {
        state.currentPoTab = payload;
    },
    SET_PO_CURRENT_ALL_TAB: (state, payload) => {
        state.currentPoAllTab = payload;
    },
    SET_PO_CURRENT_OPEN_TAB: (state, payload) => {
        state.currentPoOpenTab = payload;
    },
    SET_PO_CURRENT_BOOKING_TAB: (state, payload) => {
        state.currentPoBookingTab = payload;
    },
    SET_PO_CURRENT_IN_PROGRESS_TAB: (state, payload) => {
		state.currentPoInProgressTab = payload;
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
    SET_PO_OPTIONS: (state, payload) => {
        state.purchaseOrderOptions = payload;
    },
    SET_ORDER_STATE_LOADING: (state, payload) => {
        state.orderStateLoading = payload;
    },
    SET_POS_SHIPMENT: (state, payload) => {
        state.posShipment = payload;
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
    SET_UPLOAD_PAYMENT_DOCUMENTS: (state, payload) => {
        state.uploadPaymentDocuments = payload;
    },
    SET_CSV_REPORT_LOADING: (state, payload) => {
        state.csvReportLoading = payload;
    },
    SET_CSV_REPORT_DATA: (state, payload) => {
        state.setCsvReportData = payload;
    },
    SET_ALL_PO_DATA_CLEAR: (state) => {
		state.allPosData = {};
		state.allPage = 1;
	},
    SET_VENDOR_SKU_LOADING: (state, payload) => {
        state.vendorSkusLoading = payload;
    },
    SET_VENDOR_SKU: (state, payload) => {
        state.vendorSkus = payload;
    },
    restProductAndSkuItems: (state, payload) => {
        state.vendorSkus = payload;
    },
    // Notes
    SET_ORDER_NOTES_LOADING: (state, payload) => {
        state.orderNotesLoading = payload;
    },
    SET_ORDER_NOTES: (state, payload) => {
        state.orderNotes = payload;
    },
    SET_ADD_NOTES_LOADING: (state, payload) => {
        state.addNotesLoading = payload;
    },
    SET_ADD_NOTES:(state, payload) => {
        state.addNotes = payload;
    },
    SET_EDIT_NOTES_LOADING: (state, payload) => {
        state.editNotesLoading = payload;
    },
    SET_EDIT_NOTES: (state, payload) => {
        state.editNotes = payload;
    },
    SET_DELETE_NOTES_LOADING: (state, payload) => {
        state.deleteNotesLoading = payload;
    },
    SET_DOWNLOAD_NOTES_LOADING: (state, payload) => {
        state.downloadNotesLoading = payload;
    },
    SET_DOWNLOAD_DATA: (state, payload) => {
        state.downloadNotes = payload;
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};