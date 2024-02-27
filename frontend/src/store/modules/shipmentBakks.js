/** @format */

import axios from "axios";
import Shipment from "./../../custom/ShipmentResource";
//import moment from 'moment'
import store from "../";
//import _ from 'lodash'
<<<<<<< HEAD
import ShipmentDocumentModel from '../../models/ShipmentDocumentModel'
import ShipmentModel from '../../models/ShipmentModel'
import Invoice from "./../../custom/InvoiceResource";
const state = {
  shipments: [],
  createShipmentLoading: false,
  editShipmentLoading: false,
  shipment_loading: true,
  completedShipments: [],
  shipmentDocumentsUploadLoading: false,
  completedShipmentsLoading: true,
  shipmentDocumentsSubmitting: false,
  trackShipment_loading: false,
  shipmentDocuments: [],
  shipmentDocumentsPage: {
    current_page: 1,
    first_page_url: null,
    from: 1,
    last_page: 1,
    last_page_url: null,
    next_page_url: null,
    path: null,
    per_page: 10,
    prev_page_url: null,
    to: 0,
    total: 0
  },
  shipmentDocumentsLoading: false,
  pendingShipments: [],
  pendingShipmentsLoading: true,
  expiredShipments: [],
  expiredShipmentsLoading: true,
  pendingQuoteShipments: [],
  pendingQuoteShipmentsLoading: true,
  draftShipments: [],
  draftShipmentsLoading: true,
  snoozeShipments: [],
  snoozeShipmentsLoading: true,
  snoozeShipmentLoading: false,
  currentSelectedTab: 0,
  pagination: {
    currentTab: 1,
    currentSubTab: 0,
    current_page: 1,
    old_page: 1,
    total: 0,
    per_page: 0
  },
  // shipment
  shipmentsPagination: {
    pendingTab: {
      currentTab: 1,
      currentSubTab: 0,
      current_page: 1,
      old_page: 1,
      total: 0,
      per_page: 0
=======
import ShipmentDocumentModel from "../../models/ShipmentDocumentModel";
import Invoice from "./../../custom/InvoiceResource";

const state = {
    shipments: [],
    shipment_loading: true,
    completedShipments: [],
    shipmentDocumentsUploadLoading: false,
    completedShipmentsLoading: true,
    shipmentDocumentsSubmitting: false,
    trackShipment_loading: false,
    shipmentDocuments: [],
    shipmentDocumentsPage: {
        current_page: 1,
        first_page_url: null,
        from: 1,
        last_page: 1,
        last_page_url: null,
        next_page_url: null,
        path: null,
        per_page: 10,
        prev_page_url: null,
        to: 0,
        total: 0,
>>>>>>> 750a76eeac93872370ab483efa1489bf3f58b440
    },
    shipmentDocumentsLoading: false,
    pendingShipments: [],
    pendingShipmentsLoading: true,
    expiredShipments: [],
    expiredShipmentsLoading: true,
    pendingQuoteShipments: [],
    pendingQuoteShipmentsLoading: true,
    snoozeShipments: [],
    snoozeShipmentsLoading: true,
    snoozeShipmentLoading: false,
    currentSelectedTab: 0,
    pagination: {
        currentTab: 1,
        currentSubTab: 0,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
    },
    // shipment
    shipmentsPagination: {
        pendingTab: {
            currentTab: 1,
            currentSubTab: 0,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
        },
        pendingQuoteTab: {
            currentTab: 1,
            currentSubTab: 0,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
        },
        snoozeTab: {
            currentTab: 1,
            currentSubTab: 0,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
        },
        expiredTab: {
            currentTab: 1,
            currentSubTab: 0,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
        },
        shipmentTab: {
            currentTab: 1,
            currentSubTab: 0,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
        },
        completedTab: {
            currentTab: 1,
            currentSubTab: 0,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
        },
    },
    shipmentOrder: {
        order: "asc",
        field: "eta",
        // order: 'desc', // for shipment completed merge only
    },
<<<<<<< HEAD
    draftTab: {
        currentTab: 1,
        currentSubTab: 0,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0
    },
    shipmentTab: {
      currentTab: 1,
      currentSubTab: 0,
      current_page: 1,
      old_page: 1,
      total: 0,
      per_page: 0
    },
    completedTab: {
      currentTab: 1,
      currentSubTab: 0,
      current_page: 1,
      old_page: 1,
      total: 0,
      per_page: 0
    }
  },
  shipmentOrder: {
    order: 'asc',
    field: 'eta'
    // order: 'desc', // for shipment completed merge only
  },
  completedOrder: {
    order: 'desc'
  },
  // for searched shipments
  searchedPendingShipments: [],
  searchedExpiredShipments: [],
  searchedPendingQuoteShipments: [],
  searchedSnoozeShipments: [],
  searchedCompletedShipments: [],
  searchedDraftShipments: [],
  searchValue: '',
=======
    completedOrder: {
        order: "desc",
    },
    // for searched shipments
    searchedPendingShipments: [],
    searchedExpiredShipments: [],
    searchedPendingQuoteShipments: [],
    searchedSnoozeShipments: [],
    searchedCompletedShipments: [],
    searchValue: "",
>>>>>>> 750a76eeac93872370ab483efa1489bf3f58b440

    searchedShipments: [],
    searchedShipmentsPagination: {
        currentTab: 1,
        currentSubTab: 0,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        tab: "",
    },
    searchShipmentsLoading: true,
    shipmentSuppliers: [],
    shipmentSuppliersLoading: false,
    shipmentBills: [],
    shipmentBillsLoading: false,
};

const betaBaseUrl = process.env.VUE_APP_BASE_URL;

const getters = {
<<<<<<< HEAD
  getEditShipmentLoading: (state) => state.editShipmentLoading,
  getCreateShipmentLoading: (state) => state.createShipmentLoading,
  getSnoozeShipmentLoading: (state) => state.snoozeShipmentLoading,
  getShipmentDocumentsSubmitting: (state) => state.shipmentDocumentsSubmitting,
  getShipmentDocumentsUploadLoading: (state) => state.shipmentDocumentsUploadLoading,
  getShipmentDocuments: (state) => state.shipmentDocuments,
  getShipmentDocumentsPage: (state) => state.shipmentDocumentsPage,
  getShipmentDocumentsLoading: (state) => state.shipmentDocumentsLoading,
  getAllShipments: (state) => state.shipments,
  getShipmentLoadingStatus: (state) => state.shipment_loading,
  getAllCompletedShipments: (state) => state.completedShipments,
  getAllCompletedShipmentsLoading: (state) => state.completedShipmentsLoading,
  shipmentTrackingLoading: (state) => state.trackShipment_loading,
  getAllPendingShipments: (state) => state.pendingShipments,
  getAllPendingShipmentsLoading: (state) => state.pendingShipmentsLoading,
  getAllExpiredShipments: (state) => state.expiredShipments,
  getAllExpiredShipmentsLoading: (state) => state.expiredShipmentsLoading,
  getAllPendingQuoteShipments: (state) => state.pendingQuoteShipments,
  getAllDraftShipments: (state) => state.draftShipments,
  getAllDraftShipmentsLoading: (state) => state.draftShipmentsLoading,
  getAllPendingQuoteShipmentsLoading: (state) => state.pendingQuoteShipmentsLoading,
  getAllSnoozeShipments: (state) => state.snoozeShipments,
  getAllSnoozeShipmentsLoading: (state) => state.snoozeShipmentsLoading,
  getPaginationData: (state) => state.pagination,
  getSearchedShipments: (state) => state.searchedShipments,
  getSearchedShipmentLoading: (state) => state.searchShipmentsLoading,
  getSearchedShipmentPagination: (state) => state.searchedShipmentsPagination,
  getShipmentSuppliers: (state) => state.shipmentSuppliers,
  getShipmentSuppliersLoading: (state) => state.shipmentSuppliersLoading,
  getShipmentBills: (state) => state.shipmentBills,
  getShipmentBillsLoading: (state) => state.shipmentBillsLoading
}

const actions = {
  editShipment: async({ commit }, payload) => {
    commit('SET_EDIT_SHIPMENT_LOADING', true)
    return new Promise((resolve, reject) => {
      function proceed() {
        let sm = new ShipmentModel(betaBaseUrl)
        sm.editShipment(payload)
          .then((res) => {
            commit('SET_EDIT_SHIPMENT_LOADING', false)
            resolve(res)
          })
          .catch((err) => {
            commit('SET_EDIT_SHIPMENT_LOADING', false)
            reject(err)
          })
      }
      proceed()
    })
  },
  createShipment: async({ commit }, payload) => {
    commit('SET_CREATE_SHIPMENT_LOADING', true)
    return new Promise((resolve, reject) => {
      function proceed() {
        let sm = new ShipmentModel(betaBaseUrl)
        sm.createShipment(payload)
          .then((res) => {
            commit('SET_CREATE_SHIPMENT_LOADING', false)
            resolve(res)
          })
          .catch((err) => {
            commit('SET_CREATE_SHIPMENT_LOADING', false)
            reject(err)
          })
      }
      proceed()
    })
  },
  snoozeShipment: async ({ commit }, payload) => {
    let attempt = false
    let { shipment_id, snooze_date } = payload
=======
    getSnoozeShipmentLoading: (state) => state.snoozeShipmentLoading,
    getShipmentDocumentsSubmitting: (state) => state.shipmentDocumentsSubmitting,
    getShipmentDocumentsUploadLoading: (state) =>
        state.shipmentDocumentsUploadLoading,
    getShipmentDocuments: (state) => state.shipmentDocuments,
    getShipmentDocumentsPage: (state) => state.shipmentDocumentsPage,
    getShipmentDocumentsLoading: (state) => state.shipmentDocumentsLoading,
    getAllShipments: (state) => state.shipments,
    getShipmentLoadingStatus: (state) => state.shipment_loading,
    getAllCompletedShipments: (state) => state.completedShipments,
    getAllCompletedShipmentsLoading: (state) => state.completedShipmentsLoading,
    shipmentTrackingLoading: (state) => state.trackShipment_loading,
    getAllPendingShipments: (state) => state.pendingShipments,
    getAllPendingShipmentsLoading: (state) => state.pendingShipmentsLoading,
    getAllExpiredShipments: (state) => state.expiredShipments,
    getAllExpiredShipmentsLoading: (state) => state.expiredShipmentsLoading,
    getAllPendingQuoteShipments: (state) => state.pendingQuoteShipments,
    getAllPendingQuoteShipmentsLoading: (state) =>
        state.pendingQuoteShipmentsLoading,
    getAllSnoozeShipments: (state) => state.snoozeShipments,
    getAllSnoozeShipmentsLoading: (state) => state.snoozeShipmentsLoading,
    getPaginationData: (state) => state.pagination,
    getSearchedShipments: (state) => state.searchedShipments,
    getSearchedShipmentLoading: (state) => state.searchShipmentsLoading,
    getSearchedShipmentPagination: (state) => state.searchedShipmentsPagination,
    getShipmentSuppliers: (state) => state.shipmentSuppliers,
    getShipmentSuppliersLoading: (state) => state.shipmentSuppliersLoading,
    getShipmentBills: (state) => state.shipmentBills,
    getShipmentBillsLoading: (state) => state.shipmentBillsLoading,
};

const actions = {
    snoozeShipment: async({ commit }, payload) => {
        let attempt = false;
        let { shipment_id, snooze_date } = payload;
>>>>>>> 750a76eeac93872370ab483efa1489bf3f58b440

        return new Promise((resolve, reject) => {
            function proceed() {
                commit("SET_SNOOZE_SHIPMENT_LOADING", true);
                axios
                    .post(`/v2/snooze-shipment`, {
                        shipment_id,
                        snooze_date,
                    })
                    .then((res) => {
                        commit("SET_SNOOZE_SHIPMENT_LOADING", false);
                        if (res.status === 200) {
                            resolve();
                        } else {
                            reject("An error occured");
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
                                commit("SET_SNOOZE_SHIPMENT_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SHIPMENT_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    submitShipmentDocuments: async({ commit }, payload) => {
        commit("SET_SHIPMENT_DOCUMENTS_SUBMITTING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                let sd = new ShipmentDocumentModel(betaBaseUrl);
                sd.submitDocuments(payload)
                    .then((res) => {
                        commit("SET_SHIPMENT_DOCUMENTS_SUBMITTING", false);
                        resolve(res);
                    })
                    .catch((err) => {
                        commit("SET_SHIPMENT_DOCUMENTS_SUBMITTING", false);
                        if (typeof err.message !== "undefined") {
                            reject(err.message);
                        } else if (typeof err.error !== "undefined") {
                            reject(err.error);
                        } else {
                            reject("Submission was cancelled.");
                        }
                    });
            }
            proceed();
        });
    },
    uploadShipmentDocuments: async({ commit }, payload) => {
        commit("SET_SHIPMENT_DOCUMENTS_UPLOAD_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                let sd = new ShipmentDocumentModel(betaBaseUrl);
                sd.uploadDocuments(payload)
                    .then((res) => {
                        commit("SET_SHIPMENT_DOCUMENTS_UPLOAD_LOADING", false);
                        resolve(res);
                    })
                    .catch((err) => {
                        commit("SET_SHIPMENT_DOCUMENTS_UPLOAD_LOADING", false);
                        reject(err);
                        //reject('Token has been expired. Please reload the page.')
                    });
            }
            proceed();
        });
    },
    unSnoozeShipment: async({ commit }, payload) => {
        let attempt = false;
        let { shipment_id } = payload;

        return new Promise((resolve, reject) => {
            function proceed() {
                commit("SET_SNOOZE_SHIPMENT_LOADING", true);
                axios
                    .post(`/v2/unsnooze-shipment`, {
                        shipment_id,
                    })
                    .then((res) => {
                        commit("SET_SNOOZE_SHIPMENT_LOADING", false);
                        if (res.status === 200) {
                            resolve();
                        } else {
                            reject("An error occured");
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
                                commit("SET_SNOOZE_SHIPMENT_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SHIPMENT_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    fetchShipmentDocuments: async({ commit }, payload) => {
        commit("SET_SHIPMENT_DOCUMENTS_LOADING", true);
        //commit("SET_SHIPMENT_DOCUMENTS", [])
        return new Promise((resolve, reject) => {
            //let limit = 1
            //let start = 0
            function proceed() {
                let sd = new ShipmentDocumentModel(betaBaseUrl);
                sd.getShipmentDocuments(payload)
                    .then((res) => {
                        if (typeof res.items !== "undefined") {
                            commit("SET_SHIPMENT_DOCUMENTS_PAGE_OPTIONS", res.pageOptions);
                            commit("SET_SHIPMENT_DOCUMENTS", res.items);
                        }
                        commit("SET_SHIPMENT_DOCUMENTS_LOADING", false);
                        resolve(res);
                    })
                    .catch((err) => {
                        commit("SET_SHIPMENT_DOCUMENTS_LOADING", false);
                        if (typeof err.message !== "undefined") {
                            commit("SET_SHIPMENT_DOCUMENTS_LOADING", false);
                            reject("Token has been expired. Please reload the page.");
                        }
                        if (typeof err.error !== "undefined") {
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    clearShipmentDocuments: ({ commit }) => {
        commit("SET_SHIPMENT_DOCUMENTS", []);
    },
    fetchShipments: async({ commit, state }, payload) => {
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                commit("SET_SHIPMENT_LOADING", true);
                let { page, order, sort } = payload;
                // API calling for Shipments and Completed Merged
                // axios.get(`/v2/shipments-completed-merge?page=${page}&per_page=30&sort=eta&order=${order}`)

<<<<<<< HEAD
        // - only shipments
        axios
          .get(`/v2/shipments?page=${page}&per_page=30&sort=${state.shipmentOrder.field || sort}&order=${order}`)
          .then((res) => {
            if (res.status === 200) {
              if (typeof res.data !== 'undefined') {
                /*
                let shipmentsData = res.data.data
                
                let metaData = {
                  current_page: res.data.current_page,
                  from: res.data.from,
                  to: res.data.to,
                  last_page: res.data.last_page,
                  path: res.data.path,
                  per_page: res.data.per_page,
                  total: res.data.total
                }
                const shipment = new Shipment(shipmentsData)
                
                let finalData = {
                  current_page: metaData.current_page,
                  per_page: parseInt(metaData.per_page),
                  total: metaData.total,
                  last_page: metaData.last_page,
                  shipments: shipment.all()
                }*/
                
                let shipmentsData = res.data.data
                
                let metaData = res.data.meta
                const shipment = new Shipment(shipmentsData)
                let finalData = {
                  current_page: metaData.current_page,
                  per_page: parseInt(metaData.per_page),
                  total: metaData.total,
                  last_page: metaData.last_page,
                  shipments: shipment.all()
                }

                commit('SET_SHIPMENTS', finalData)
              }
            }
            commit('SET_SHIPMENT_LOADING', false)
            resolve()
          })
          .catch((err) => {
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
                console.log('reject', err)
                commit('SET_SHIPMENT_LOADING', false)
                reject('Token has been expired. Please reload the page.')
              }
            }

            if (typeof err.error !== 'undefined') {
              commit('SET_SHIPMENT_LOADING', false)
              reject(err.error)
            }
          })
      }
      proceed()
    })
  },
  fetchDraftShipments: async ({ commit }, page) => {
    commit('SET_DRAFT_SHIPMENTS_LOADING', true)
    let attempt = false
    return new Promise((resolve, reject) => {
      function proceed() {
        axios
          .get(`/v2/draft-shipments?page=${page}&per_page=30`)
          .then((res) => {
            if (res.status === 200) {
              if (typeof res.data !== 'undefined') {
                let draftShipments = res.data.data
                let metaData = res.data.meta
                const draftShipment = new Shipment(draftShipments)

                let finalData = {
                  current_page: metaData.current_page,
                  per_page: parseInt(metaData.per_page),
                  total: metaData.total,
                  last_page: metaData.last_page,
                  shipments: draftShipment.draft()
                }
                

                commit('SET_DRAFT_SHIPMENTS', finalData)
              }
            } else {
              commit('SET_DRAFT_SHIPMENTS', [])
            }
            commit('SET_DRAFT_SHIPMENTS_LOADING', false)
            resolve()
          })
          .catch((err) => {
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
                commit('SET_SNOOZE_SHIPMENTS_LOADING', false)
                reject('Token has been expired. Please reload the page.')
                //return Promise.reject('Token has been expired. Please reload the page.')
              }
            }

            if (typeof err.error !== 'undefined') {
              commit('SET_SNOOZE_SHIPMENTS_LOADING', false)
              reject(err.error)
              //commit("SET_PO_DETAIL_LOADING", false)
              //return Promise.reject(err.error)
            }
          })
      }
      proceed()
    })
  },
  fetchSnoozeShipments: async ({ commit }, page) => {
    commit('SET_SNOOZE_SHIPMENTS_LOADING', true)
    let attempt = false
    return new Promise((resolve, reject) => {
      function proceed() {
        axios
          .get(`/v2/snooze-shipments?page=${page}&per_page=30`)
          // await axios.get('/shipments-completed')
          .then((res) => {
            if (res.status === 200) {
              if (typeof res.data !== 'undefined') {
                let snoozeShipments = res.data.data
                let metaData = res.data.meta
                const snoozeShipment = new Shipment(snoozeShipments)

                let finalData = {
                  current_page: metaData.current_page,
                  per_page: parseInt(metaData.per_page),
                  total: metaData.total,
                  last_page: metaData.last_page,
                  shipments: snoozeShipment.snooze()
                }

                if (finalData.shipments.length > 0) {
                  finalData.shipments.map((val, key) => {
                    finalData.shipments[key].show_tooltip = false
                    finalData.shipments[key].unsnoozing = false
                  })
                }
                commit('SET_SNOOZE_SHIPMENTS', finalData)
              }
=======
                // - only shipments
                axios
                    .get(
                        `/v2/shipments?page=${page}&per_page=30&sort=${state.shipmentOrder
							.field || sort}&order=${order}`
                    )
                    .then((res) => {
                        if (res.status === 200) {
                            if (typeof res.data !== "undefined") {
                                let shipmentsData = res.data.data;
                                let metaData = res.data.meta;
                                const shipment = new Shipment(shipmentsData);
                                let finalData = {
                                    current_page: metaData.current_page,
                                    per_page: parseInt(metaData.per_page),
                                    total: metaData.total,
                                    last_page: metaData.last_page,
                                    shipments: shipment.all(),
                                };
                                commit("SET_SHIPMENTS", finalData);
                            }
                        }
                        commit("SET_SHIPMENT_LOADING", false);
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
                                commit("SET_SHIPMENT_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SHIPMENT_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },

    fetchSnoozeShipments: async({ commit }, page) => {
        commit("SET_SNOOZE_SHIPMENTS_LOADING", true);
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`/v2/snooze-shipments?page=${page}&per_page=30`)
                    // await axios.get('/shipments-completed')
                    .then((res) => {
                        if (res.status === 200) {
                            if (typeof res.data !== "undefined") {
                                let snoozeShipments = res.data.data;
                                let metaData = res.data.meta;
                                const snoozeShipment = new Shipment(snoozeShipments);

                                let finalData = {
                                    current_page: metaData.current_page,
                                    per_page: parseInt(metaData.per_page),
                                    total: metaData.total,
                                    last_page: metaData.last_page,
                                    shipments: snoozeShipment.snooze(),
                                };

                                if (finalData.shipments.length > 0) {
                                    finalData.shipments.map((val, key) => {
                                        finalData.shipments[key].show_tooltip = false;
                                        finalData.shipments[key].unsnoozing = false;
                                    });
                                }
                                commit("SET_SNOOZE_SHIPMENTS", finalData);
                            }
                        } else {
                            commit("SET_SNOOZE_SHIPMENTS", []);
                        }
                        commit("SET_SNOOZE_SHIPMENTS_LOADING", false);
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
                                commit("SET_SNOOZE_SHIPMENTS_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                                //return Promise.reject('Token has been expired. Please reload the page.')
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SNOOZE_SHIPMENTS_LOADING", false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }
                    });
            }
            proceed();
        });
    },
    clearShipmentsData({ commit }, payload) {
        let { pending_quote, snooze, expired, pending } = payload;

        pending_quote.shipments = [];
        snooze.shipments = [];
        expired.shipments = [];
        pending.shipments = [];

        commit("SET_PENDING_QUOTE_SHIPMENTS", pending_quote);
        commit("SET_SNOOZE_SHIPMENTS", snooze);
        commit("SET_PENDING_SHIPMENTS", pending);
        commit("SET_EXPIRED_SHIPMENTS", expired);
    },
    clearTooltips({ commit }, payload) {
        if (
            typeof payload !== "undefined" &&
            typeof payload.shipments !== "undefined"
        ) {
            payload.shipments.map((shipment, key) => {
                payload.shipments[key].show_tooltip = false;
            });
            if (typeof payload.shipment_type !== "undefined") {
                if (payload.shipment_type == "pendingQuote") {
                    commit("SET_PENDING_QUOTE_SHIPMENTS", payload);
                } else if (payload.shipment_type == "expired") {
                    commit("SET_EXPIRED_SHIPMENTS", payload);
                }
>>>>>>> 750a76eeac93872370ab483efa1489bf3f58b440
            } else {
                commit("SET_PENDING_QUOTE_SHIPMENTS", payload);
            }
        }
    },
    fetchPendingQuoteShipments: async({ commit }, page) => {
        commit("SET_PENDING_QUOTE_SHIPMENTS_LOADING", true);
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`/v2/pending-quote-shipments?page=${page}&per_page=30`)
                    // await axios.get('/shipments-completed')
                    .then((res) => {
                        if (res.status === 200) {
                            if (typeof res.data !== "undefined") {
                                let pendingQuoteShipments = res.data.data;
                                let metaData = res.data.meta;
                                const pendingQuoteShipment = new Shipment(
                                    pendingQuoteShipments
                                );

<<<<<<< HEAD
                
                let finalData = {
                  current_page: metaData.current_page,
                  per_page: parseInt(metaData.per_page),
                  total: metaData.total,
                  last_page: metaData.last_page,
                  shipments: pendingQuoteShipment.pendingQuote()
                }
                /*
                let finalData = {
                  current_page: metaData.current_page,
                  per_page: parseInt(metaData.per_page),
                  total: metaData.total,
                  last_page: metaData.last_page,
                  shipments: pendingQuoteShipment.pendingQuote()
                }*/
=======
                                let finalData = {
                                    current_page: metaData.current_page,
                                    per_page: parseInt(metaData.per_page),
                                    total: metaData.total,
                                    last_page: metaData.last_page,
                                    shipments: pendingQuoteShipment.pendingQuote(),
                                };
>>>>>>> 750a76eeac93872370ab483efa1489bf3f58b440

                                if (finalData.shipments.length > 0) {
                                    finalData.shipments.map((val, key) => {
                                        finalData.shipments[key].show_tooltip = false;
                                        finalData.shipments[key].snoozing = false;
                                        finalData.shipments[key].snooze_date_at = null;
                                    });
                                }
                                commit("SET_PENDING_QUOTE_SHIPMENTS", finalData);
                            }
                        } else {
                            commit("SET_PENDING_QUOTE_SHIPMENTS", []);
                        }
                        commit("SET_PENDING_QUOTE_SHIPMENTS_LOADING", false);
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
                                commit("SET_PENDING_QUOTE_SHIPMENTS_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                                //return Promise.reject('Token has been expired. Please reload the page.')
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_PENDING_QUOTE_SHIPMENTS_LOADING", false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }
                    });
            }
            proceed();
        });
    },
    fetchCompletedShipments: async({ commit }, payload) => {
        commit("SET_COMPLETED_SHIPMENTS_LOADING", true);
        let attempt = false;
        let { page, order } = payload;
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(
                        `/v2/completed-shipments?page=${page}&per_page=30&sort=eta&order=${order}`
                    )
                    // await axios.get('/shipments-completed')
                    .then((res) => {
                        if (res.status === 200) {
                            if (typeof res.data !== "undefined") {
                                let completedShipments = res.data.data;
                                let metaData = res.data.meta;

                                const completedShipment = new Shipment(completedShipments);

                                let finalData = {
                                    current_page: metaData.current_page,
                                    per_page: parseInt(metaData.per_page),
                                    total: metaData.total,
                                    last_page: metaData.last_page,
                                    shipments: completedShipment.completed(),
                                };

                                commit("SET_COMPLETED_SHIPMENTS", finalData);
                            }
                        } else {
                            commit("SET_COMPLETED_SHIPMENTS", []);
                        }
                        commit("SET_COMPLETED_SHIPMENTS_LOADING", false);
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
                                commit("SET_COMPLETED_SHIPMENTS_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                                //return Promise.reject('Token has been expired. Please reload the page.')
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_COMPLETED_SHIPMENTS_LOADING", false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }
                    });
            }
            proceed();
        });
    },
    fetchPendingShipments: async({ commit }, page) => {
        commit("SET_PENDING_SHIPMENTS_LOADING", true);
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`/v2/pending-shipments?page=${page}&per_page=30`)
                    .then((res) => {
                        if (res.status === 200) {
                            if (typeof res.data !== "undefined") {
                                let pendingShipments = res.data.data;
                                let metaData = res.data.meta;

                                const pendingShipment = new Shipment(pendingShipments);

                                let finalData = {
                                    current_page: metaData.current_page,
                                    per_page: parseInt(metaData.per_page),
                                    total: metaData.total,
                                    last_page: metaData.last_page,
                                    shipments: pendingShipment.pending(),
                                };

                                if (finalData.shipments.length > 0) {
                                    finalData.shipments.map((val, key) => {
                                        finalData.shipments[key].show_tooltip = false;
                                        finalData.shipments[key].snoozing = false;
                                    });
                                }

                                commit("SET_PENDING_SHIPMENTS", finalData);
                            }
                        } else {
                            commit("SET_PENDING_SHIPMENTS", []);
                        }
                        commit("SET_PENDING_SHIPMENTS_LOADING", false);
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
                                commit("SET_PENDING_SHIPMENTS_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                                //return Promise.reject('Token has been expired. Please reload the page.')
                            }
                        }

<<<<<<< HEAD
            if (typeof err.error !== 'undefined') {
              commit('SET_COMPLETED_SHIPMENTS_LOADING', false)
              reject(err.error)
              //commit("SET_PO_DETAIL_LOADING", false)
              //return Promise.reject(err.error)
            }
          })
      }
      proceed()
    })
  },
  fetchPendingShipments: async ({ commit }, page) => {
    commit('SET_PENDING_SHIPMENTS_LOADING', true)
    let attempt = false
    return new Promise((resolve, reject) => {
      function proceed() {
        axios
          .get(`/v2/pending-shipments?page=${page}&per_page=30`)
          .then((res) => {
            if (res.status === 200) {
              if (typeof res.data !== 'undefined') {
                let pendingShipments = res.data.data
                let metaData = res.data.meta

                const pendingShipment = new Shipment(pendingShipments)

                /*
                let finalData = {
                  current_page: metaData.current_page,
                  per_page: parseInt(metaData.per_page),
                  total: metaData.total,
                  last_page: metaData.last_page,
                  shipments: pendingShipment.pending()
                }*/
                
                let finalData = {
                  current_page: metaData.current_page,
                  per_page: parseInt(metaData.per_page),
                  total: metaData.total,
                  last_page: metaData.last_page,
                  shipments: pendingShipment.pending()
                }


                if (finalData.shipments.length > 0) {
                  finalData.shipments.map((val, key) => {
                    finalData.shipments[key].show_tooltip = false
                    finalData.shipments[key].snoozing = false
                  })
                }

                commit('SET_PENDING_SHIPMENTS', finalData)
              }
            } else {
              commit('SET_PENDING_SHIPMENTS', [])
            }
            commit('SET_PENDING_SHIPMENTS_LOADING', false)
            resolve()
          })
          .catch((err) => {
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
                commit('SET_PENDING_SHIPMENTS_LOADING', false)
                reject('Token has been expired. Please reload the page.')
                //return Promise.reject('Token has been expired. Please reload the page.')
              }
            }

            if (typeof err.error !== 'undefined') {
              commit('SET_PENDING_SHIPMENTS_LOADING', false)
              reject(err.error)
              //commit("SET_PO_DETAIL_LOADING", false)
              //return Promise.reject(err.error)
            }
            /*
=======
                        if (typeof err.error !== "undefined") {
                            commit("SET_PENDING_SHIPMENTS_LOADING", false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }
                        /*
>>>>>>> 750a76eeac93872370ab483efa1489bf3f58b440
					commit("SET_PENDING_SHIPMENTS_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						if (typeof err.message !== 'undefined') {
							if (err.message === 'UnAnuthenticated') {
								return Promise.reject('Token has been expired. Please reload the page.')
							} else {
								return Promise.reject(err.message)
							}
						}
					}*/
                    });
            }
            proceed();
        });
    },
    fetchExpiredShipments: async({ commit }, page) => {
        commit("SET_EXPIRED_SHIPMENTS_LOADING", true);
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`/v2/expired-shipments?page=${page}&per_page=30`)
                    .then((res) => {
                        if (res.status === 200) {
                            if (typeof res.data !== "undefined") {
                                let expiredShipments = res.data.data;
                                let metaData = res.data.meta;

                                const expiredShipment = new Shipment(expiredShipments);

                                let finalData = {
                                    current_page: metaData.current_page,
                                    per_page: parseInt(metaData.per_page),
                                    total: metaData.total,
                                    last_page: metaData.last_page,
                                    shipments: expiredShipment.expired(),
                                };

                                if (finalData.shipments.length > 0) {
                                    finalData.shipments.map((val, key) => {
                                        finalData.shipments[key].show_tooltip = false;
                                    });
                                }
                                commit("SET_EXPIRED_SHIPMENTS", finalData);
                            }
                        } else {
                            commit("SET_EXPIRED_SHIPMENTS", []);
                        }
                        commit("SET_EXPIRED_SHIPMENTS_LOADING", false);
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
                                commit("SET_EXPIRED_SHIPMENTS_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                                //return Promise.reject('Token has been expired. Please reload the page.')
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_EXPIRED_SHIPMENTS_LOADING", false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }
                        /*

					commit("SET_EXPIRED_SHIPMENTS_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						if (typeof err.message !== 'undefined') {
							if (err.message === 'UnAnuthenticated') {
								return Promise.reject('Token has been expired. Please reload the page.')
							} else {
								return Promise.reject(err.message)
							}
						}
					}*/
                    });
            }
            proceed();
        });
    },
    addTrackShipment: async({ commit }, params) => {
        commit("SET_TRACKING_SHIPMENT_LOADING", true);
        let attempt = false;
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .post("/tracking-shipments/new", params)
                    .then((res) => {
                        if (res.status === 200) {
                            if (typeof res.data !== "undefined") {
                                commit("SET_TRACKING_SHIPMENT_LOADING", false);
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
                                commit("SET_TRACKING_SHIPMENT_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                                //return Promise.reject('Token has been expired. Please reload the page.')
                            }
                        }

<<<<<<< HEAD
            if (typeof err.is_scac_validation_failed !== 'undefined') {
              commit('SET_TRACKING_SHIPMENT_LOADING', false)
              reject(err)
            }
          })
      }
      proceed()
    })
  },
  setReset({ commit }, payload) {
    commit('setReset', payload)
  },
  setSnoozePagination({ commit }, payload) {
    commit('setSnoozePagination', payload)
  },
  setShipmentPagination({ commit }, payload) {
    commit('setShipmentPagination', payload)
  },
  setDraftPagination({ commit }, payload) {
    commit('setDraftPagination', payload)
  },
  setPendingPagination({ commit }, payload) {
    commit('setPendingPagination', payload)
  },
  setPendingQuotePagination({ commit }, payload) {
    commit('setPendingQuotePagination', payload)
  },
  setExpiredPagination({ commit }, payload) {
    commit('setExpiredPagination', payload)
  },
  setCompletedPagination({ commit }, payload) {
    commit('setCompletedPagination', payload)
  },
  setShipmentOrder({ commit }, payload) {
    commit('setShipmentOrder', payload)
  },
  setCompletedOrder({ commit }, payload) {
    commit('setCompletedOrder', payload)
  },
  setSearchedPagination({ commit }, payload) {
    commit('setSearchedPagination', payload)
  },
  fetchShipmentsSearched: async ({ commit }, data) => {
    commit('SET_SEARCHED_SHIPMENTS_LOADING', true)
    return new Promise((resolve, reject) => {
      function proceed() {
        axios(data)
          .then((res) => {
            if (res.status === 200) {
              if (typeof res.data !== 'undefined') {
                let shipmentsData = res.data.data
                let metaData = res.data.meta
                const shipment = new Shipment(shipmentsData)
                if (
                  data.params.tab === 'shipments' ||
                  data.params.tab === 'shipments-completed-merge' ||
                  data.params.tab === 'pending' ||
                  data.params.tab === 'expired' ||
                  data.params.tab === 'pendingQuote' ||
                  data.params.tab === 'snooze'
                ) {
                  let finalData = {
                    current_page: metaData.current_page,
                    per_page: parseInt(metaData.per_page),
                    total: metaData.total,
                    last_page: metaData.last_page,
                    shipments: shipment.all(),
                    tab: data.params.tab
                  }
                  commit('SET_SEARCHED_SHIPMENTS', finalData)
                } else if (data.params.tab === 'completed') {
                  let finalData = {
                    current_page: metaData.current_page,
                    per_page: parseInt(metaData.per_page),
                    total: metaData.total,
                    last_page: metaData.last_page,
                    shipments: shipment.completed(),
                    tab: data.params.tab
                  }
                  commit('SET_SEARCHED_SHIPMENTS', finalData)
                }
              }
            }
            commit('SET_SEARCHED_SHIPMENTS_LOADING', false)
            resolve()
          })
          .catch((err) => {
            if (typeof err.message !== 'undefined') {
              commit('SET_SEARCHED_SHIPMENTS_LOADING', false)
              reject('Token has been expired. Please reload the page.')
=======
                        if (typeof err.is_scac_validation_failed !== "undefined") {
                            commit("SET_TRACKING_SHIPMENT_LOADING", false);
                            reject(err);
                        }
                    });
>>>>>>> 750a76eeac93872370ab483efa1489bf3f58b440
            }
            proceed();
        });
    },
    setReset({ commit }, payload) {
        commit("setReset", payload);
    },
    setSnoozePagination({ commit }, payload) {
        commit("setSnoozePagination", payload);
    },
    setShipmentPagination({ commit }, payload) {
        commit("setShipmentPagination", payload);
    },
    setPendingPagination({ commit }, payload) {
        commit("setPendingPagination", payload);
    },
    setPendingQuotePagination({ commit }, payload) {
        commit("setPendingQuotePagination", payload);
    },
    setExpiredPagination({ commit }, payload) {
        commit("setExpiredPagination", payload);
    },
    setCompletedPagination({ commit }, payload) {
        commit("setCompletedPagination", payload);
    },
    setShipmentOrder({ commit }, payload) {
        commit("setShipmentOrder", payload);
    },
    setCompletedOrder({ commit }, payload) {
        commit("setCompletedOrder", payload);
    },
    setSearchedPagination({ commit }, payload) {
        commit("setSearchedPagination", payload);
    },
    fetchShipmentsSearched: async({ commit }, data) => {
        commit("SET_SEARCHED_SHIPMENTS_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                axios(data)
                    .then((res) => {
                        if (res.status === 200) {
                            if (typeof res.data !== "undefined") {
                                let shipmentsData = res.data.data;
                                let metaData = res.data.meta;
                                const shipment = new Shipment(shipmentsData);
                                if (
                                    data.params.tab === "shipments" ||
                                    data.params.tab === "shipments-completed-merge" ||
                                    data.params.tab === "pending" ||
                                    data.params.tab === "expired" ||
                                    data.params.tab === "pendingQuote" ||
                                    data.params.tab === "snooze"
                                ) {
                                    let finalData = {
                                        current_page: metaData.current_page,
                                        per_page: parseInt(metaData.per_page),
                                        total: metaData.total,
                                        last_page: metaData.last_page,
                                        shipments: shipment.all(),
                                        tab: data.params.tab,
                                    };
                                    commit("SET_SEARCHED_SHIPMENTS", finalData);
                                } else if (data.params.tab === "completed") {
                                    let finalData = {
                                        current_page: metaData.current_page,
                                        per_page: parseInt(metaData.per_page),
                                        total: metaData.total,
                                        last_page: metaData.last_page,
                                        shipments: shipment.completed(),
                                        tab: data.params.tab,
                                    };
                                    commit("SET_SEARCHED_SHIPMENTS", finalData);
                                }
                            }
                        }
                        commit("SET_SEARCHED_SHIPMENTS_LOADING", false);
                        resolve();
                    })
                    .catch((err) => {
                        if (typeof err.message !== "undefined") {
                            commit("SET_SEARCHED_SHIPMENTS_LOADING", false);
                            reject("Token has been expired. Please reload the page.");
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SEARCHED_SHIPMENTS_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    setSearchedShipmentsLoading({ commit }, payload) {
        commit("SET_SEARCHED_SHIPMENTS_LOADING", payload);
    },
    setShipmentSearchedVal({ commit }, payload) {
        commit("setShipmentSearchedVal", payload);
    },
    setShipmentLoading({ commit }, payload) {
        commit("SET_SHIPMENT_LOADING", payload);
    },
    deleteTrackingShipmentApi: async({ commit }, id) => {
        let attempt = false;
        commit("SET_TRACKING_SHIPMENT_DELETE_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`/api/tracking-shipments/${id}/delete`)
                    .then((res) => {
                        commit("SET_TRACKING_SHIPMENT_DELETE_LOADING", false);
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
                                commit("SET_TRACKING_SHIPMENT_DELETE_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                                //return Promise.reject('Token has been expired. Please reload the page.')
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_TRACKING_SHIPMENT_DELETE_LOADING", false);
                            reject(err.error);
                            //commit("SET_PO_DETAIL_LOADING", false)
                            //return Promise.reject(err.error)
                        }

                        /*

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

    fetchShipmentSuppliers: async({ commit }, payload) => {
        let attempt = false;

        return new Promise((resolve, reject) => {
            function proceed() {
                commit("SET_SHIPMENT_SUPPLIERS_LOADING", true);

                axios
                    .get(`/shipment-suppliers?shipment_id=${payload}`)
                    .then((res) => {
                        if (typeof res.status !== "undefined") {
                            if (res.status === 200) {
                                if (res.data) {
                                    let results = res.data.data;
                                    commit("SET_SHIPMENT_SUPPLIERS_LOADING", false);
                                    commit("SET_SHIPMENT_SUPPLIERS", results);
                                }

                                resolve();
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
                                commit("SET_SHIPMENT_SUPPLIERS_LOADING", false);
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SHIPMENT_SUPPLIERS_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },

    fetchShipmentBills: async({ commit }, payload) => {
        let attempt = false;

        return new Promise((resolve, reject) => {
            function proceed() {
                commit("SET_SHIPMENT_BILLS_LOADING", true);

                axios
                    .get(`/quickbooks/invoices/${payload}`)
                    .then((res) => {
                        if (typeof res.status !== "undefined") {
                            if (res.status === 200) {
                                if (res.data) {
                                    let newInvoices = [];
                                    if (
                                        Array.isArray(res.data.data) &&
                                        res.data.data.length > 0
                                    ) {
                                        res.data.data.map((val) => {
                                            newInvoices.push(val);
                                        });
                                    }
                                    const invoice = new Invoice(newInvoices);

                                    commit("SET_SHIPMENT_BILLS_LOADING", false);
                                    commit("SET_SHIPMENT_BILLS", invoice.all());
                                }

                                resolve();
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
                                commit("SET_SHIPMENT_BILLS_LOADING", false);
                                reject(err.message);
                            }
                        }

                        if (typeof err.error !== "undefined") {
                            commit("SET_SHIPMENT_BILLS_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
};

const mutations = {
<<<<<<< HEAD
  SET_CREATE_SHIPMENT_LOADING: (state, payload) => {
    state.createShipmentLoading = payload
  },
  SET_EDIT_SHIPMENT_LOADING: (state, payload) => {
    state.editShipmentLoading = payload
  },
  SET_SNOOZE_SHIPMENT_LOADING: (state, payload) => {
    state.snoozeShipmentLoading = payload
  },
  SET_PENDING_QUOTE_SHIPMENTS_LOADING: (state, payload) => {
    state.pendingQuoteShipmentsLoading = payload
  },
  SET_DRAFT_SHIPMENTS_LOADING: (state, payload) => {
    state.draftShipmentsLoading = payload
  },
  SET_DRAFT_SHIPMENTS: (state, payload) => {
    state.draftShipments = payload
  },
  SET_PENDING_QUOTE_SHIPMENTS: (state, payload) => {
    state.pendingQuoteShipments = payload
  },
  SET_SNOOZE_SHIPMENTS_LOADING: (state, payload) => {
    state.snoozeShipmentsLoading = payload
  },
  SET_SNOOZE_SHIPMENTS: (state, payload) => {
    state.snoozeShipments = payload
  },
  SET_SHIPMENT_DOCUMENTS_PAGE_OPTIONS: (state, payload) => {
    state.shipmentDocumentsPage = payload
  },
  SET_SHIPMENT_DOCUMENTS_SUBMITTING: (state, payload) => {
    state.shipmentDocumentsSubmitting = payload
  },
  SET_SHIPMENT_DOCUMENTS_UPLOAD_LOADING: (state, payload) => {
    state.shipmentDocumentsUploadLoading = payload
  },
  SET_SHIPMENT_DOCUMENTS: (state, payload) => {
    state.shipmentDocuments = payload
  },
  SET_SHIPMENT_DOCUMENTS_LOADING: (state, payload) => {
    state.shipmentDocumentsLoading = payload
  },
  SET_SHIPMENTS: (state, payload) => {
    state.shipments = payload
  },
  SET_SHIPMENT_LOADING: (state, payload) => {
    state.shipment_loading = payload
  },
  SET_COMPLETED_SHIPMENTS: (state, payload) => {
    state.completedShipments = payload
  },
  SET_COMPLETED_SHIPMENTS_LOADING: (state, payload) => {
    state.completedShipmentsLoading = payload
  },
  SET_TRACKING_SHIPMENT_LOADING: (state, payload) => {
    state.trackShipment_loading = payload
  },
  SET_PENDING_SHIPMENTS: (state, payload) => {
    state.pendingShipments = payload
  },
  SET_PENDING_SHIPMENTS_LOADING: (state, payload) => {
    state.pendingShipmentsLoading = payload
  },
  SET_EXPIRED_SHIPMENTS: (state, payload) => {
    state.expiredShipments = payload
  },
  SET_EXPIRED_SHIPMENTS_LOADING: (state, payload) => {
    state.expiredShipmentsLoading = payload
  },
  setReset: (state, payload) => {
    state.shipments = payload
  },
  setShipmentPagination: (state, payload) => {
    state.shipmentsPagination.shipmentTab = payload
  },
  setSnoozePagination: (state, payload) => {
    state.shipmentsPagination.snoozeTab = payload
  },
  setPendingPagination: (state, payload) => {
    state.shipmentsPagination.pendingTab = payload
  },
  setDraftPagination: (state, payload) => {
    state.shipmentsPagination.draftTab = payload
  },
  setPendingQuotePagination: (state, payload) => {
    state.shipmentsPagination.pendingQuoteTab = payload
  },
  setExpiredPagination: (state, payload) => {
    state.shipmentsPagination.expiredTab = payload
  },
  setCompletedPagination: (state, payload) => {
    state.shipmentsPagination.completedTab = payload
  },
  setShipmentOrder: (state, payload) => {
    state.shipmentOrder = payload
  },
  setCompletedOrder: (state, payload) => {
    state.completedOrder.order = payload
  },
  setSearchedPagination: (state, payload) => {
    state.searchedShipmentsPagination = payload
  },
  SET_SEARCHED_SHIPMENTS: (state, payload) => {
    state.searchedShipments = payload
  },
  SET_SEARCHED_SHIPMENTS_LOADING: (state, payload) => {
    state.searchShipmentsLoading = payload
  },
  setShipmentSearchedVal: (state, payload) => {
    state.searchedShipments = payload
  },
  SET_SHIPMENT_SUPPLIERS: (state, payload) => {
    state.shipmentSuppliers = payload
  },
  SET_SHIPMENT_SUPPLIERS_LOADING: (state, payload) => {
    state.shipmentSuppliersLoading = payload
  },
  SET_SHIPMENT_BILLS: (state, payload) => {
    state.shipmentBills = payload
  },
  SET_SHIPMENT_BILLS_LOADING: (state, payload) => {
    state.shipmentBillsLoading = payload
  }
}
=======
    SET_SNOOZE_SHIPMENT_LOADING: (state, payload) => {
        state.snoozeShipmentLoading = payload;
    },
    SET_PENDING_QUOTE_SHIPMENTS_LOADING: (state, payload) => {
        state.pendingQuoteShipmentsLoading = payload;
    },
    SET_PENDING_QUOTE_SHIPMENTS: (state, payload) => {
        state.pendingQuoteShipments = payload;
    },
    SET_SNOOZE_SHIPMENTS_LOADING: (state, payload) => {
        state.snoozeShipmentsLoading = payload;
    },
    SET_SNOOZE_SHIPMENTS: (state, payload) => {
        state.snoozeShipments = payload;
    },
    SET_SHIPMENT_DOCUMENTS_PAGE_OPTIONS: (state, payload) => {
        state.shipmentDocumentsPage = payload;
    },
    SET_SHIPMENT_DOCUMENTS_SUBMITTING: (state, payload) => {
        state.shipmentDocumentsSubmitting = payload;
    },
    SET_SHIPMENT_DOCUMENTS_UPLOAD_LOADING: (state, payload) => {
        state.shipmentDocumentsUploadLoading = payload;
    },
    SET_SHIPMENT_DOCUMENTS: (state, payload) => {
        state.shipmentDocuments = payload;
    },
    SET_SHIPMENT_DOCUMENTS_LOADING: (state, payload) => {
        state.shipmentDocumentsLoading = payload;
    },
    SET_SHIPMENTS: (state, payload) => {
        state.shipments = payload;
    },
    SET_SHIPMENT_LOADING: (state, payload) => {
        state.shipment_loading = payload;
    },
    SET_COMPLETED_SHIPMENTS: (state, payload) => {
        state.completedShipments = payload;
    },
    SET_COMPLETED_SHIPMENTS_LOADING: (state, payload) => {
        state.completedShipmentsLoading = payload;
    },
    SET_TRACKING_SHIPMENT_LOADING: (state, payload) => {
        state.trackShipment_loading = payload;
    },
    SET_PENDING_SHIPMENTS: (state, payload) => {
        state.pendingShipments = payload;
    },
    SET_PENDING_SHIPMENTS_LOADING: (state, payload) => {
        state.pendingShipmentsLoading = payload;
    },
    SET_EXPIRED_SHIPMENTS: (state, payload) => {
        state.expiredShipments = payload;
    },
    SET_EXPIRED_SHIPMENTS_LOADING: (state, payload) => {
        state.expiredShipmentsLoading = payload;
    },
    setReset: (state, payload) => {
        state.shipments = payload;
    },
    setShipmentPagination: (state, payload) => {
        state.shipmentsPagination.shipmentTab = payload;
    },
    setSnoozePagination: (state, payload) => {
        state.shipmentsPagination.snoozeTab = payload;
    },
    setPendingPagination: (state, payload) => {
        state.shipmentsPagination.pendingTab = payload;
    },
    setPendingQuotePagination: (state, payload) => {
        state.shipmentsPagination.pendingQuoteTab = payload;
    },
    setExpiredPagination: (state, payload) => {
        state.shipmentsPagination.expiredTab = payload;
    },
    setCompletedPagination: (state, payload) => {
        state.shipmentsPagination.completedTab = payload;
    },
    setShipmentOrder: (state, payload) => {
        state.shipmentOrder = payload;
    },
    setCompletedOrder: (state, payload) => {
        state.completedOrder.order = payload;
    },
    setSearchedPagination: (state, payload) => {
        state.searchedShipmentsPagination = payload;
    },
    SET_SEARCHED_SHIPMENTS: (state, payload) => {
        state.searchedShipments = payload;
    },
    SET_SEARCHED_SHIPMENTS_LOADING: (state, payload) => {
        state.searchShipmentsLoading = payload;
    },
    setShipmentSearchedVal: (state, payload) => {
        state.searchedShipments = payload;
    },
    SET_SHIPMENT_SUPPLIERS: (state, payload) => {
        state.shipmentSuppliers = payload;
    },
    SET_SHIPMENT_SUPPLIERS_LOADING: (state, payload) => {
        state.shipmentSuppliersLoading = payload;
    },
    SET_SHIPMENT_BILLS: (state, payload) => {
        state.shipmentBills = payload;
    },
    SET_SHIPMENT_BILLS_LOADING: (state, payload) => {
        state.shipmentBillsLoading = payload;
    },
};
>>>>>>> 750a76eeac93872370ab483efa1489bf3f58b440

export default {
    state,
    getters,
    actions,
    mutations,
};