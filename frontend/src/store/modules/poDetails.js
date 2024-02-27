/** @format */

import axios from "axios";
import store from "../";

const state = {
    po_details: [],
    po_details_loading: true,
    poHistory: [],
    poHistoryLoading: false,
    poHistoryPreview: [],
    poHistoryPreviewLoading: false,
    poActivityLog: [],
    poActivityLogLoading: false,
};

const getters = {
    getPoDetails: (state) => state.po_details,
    getPoDetailsLoading: (state) => state.po_details_loading,
    getPoHistory: (state) => state.poHistory,
    getPoHistoryLoading: (state) => state.poHistoryLoading,
    getPoHistoryPreview: (state) => state.poHistoryPreview,
    getPoHistoryPreviewLoading: (state) => state.poHistoryPreviewLoading,
    getPoActivityLog: (state) => state.poActivityLog,
    getPoActivityLogLoading: (state) => state.poActivityLogLoading,
};

const poBaseUrl = process.env.VUE_APP_PO_URL;

const actions = {
    fetchPoDetails: async({ commit }, id) => {
        let attempt = false;
        commit("SET_PO_DETAILS_LOADING", true);
        return new Promise((resolve, reject) => {
            function proceed() {
                // await axios.get(`/purchaseorders/${id}`)
                axios
                    .get(`${poBaseUrl}/purchaseorders/${id}`)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                let d = res.data;

                                //var poDetailsUpdate = new Po(d)
                                // commit("SET_PO_DETAILS", poDetailsUpdate.all())
                                commit("SET_PO_DETAILS", d);
                            }
                        }
                        commit("SET_PO_DETAILS_LOADING", false);
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
                                commit("SET_PO_DETAILS_LOADING", false);
                                reject("Token has been expired. Please reload the page.");
                            }
                        } else {
                            commit("SET_PO_DETAILS_LOADING", false);
                            reject(err.error);
                        }
                        /*
					commit("SET_PO_DETAILS_LOADING", false)
					return Promise.reject(err)*/
                    });
            }
            proceed();
        });
    },
    fetchPoHistory: async({ commit }, id) => {
        let attempt = false;
        commit("SET_PO_HISTORY_LOADING", true);
        // commit("SET_PO_HISTORY", [])

        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`${poBaseUrl}/v3/orders/${id}/histories`)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                let d = res.data.results;
                                commit("SET_PO_HISTORY", d);
                            }
                        }
                        commit("SET_PO_HISTORY_LOADING", false);
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
                                commit("SET_PO_HISTORY_LOADING", false);
                                reject(err.message);
                            }
                        } else {
                            commit("SET_PO_HISTORY_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    fetchPoActivityLog: async({ commit }, id) => {
        let attempt = false;
        commit("SET_PO_ACTIVITY_LOG_LOADING", true);

        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`${poBaseUrl}/v3/orders/activity/log/${id}`)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                commit("SET_PO_ACTIVITY_LOG", res.data);
                            }
                        }
                        commit("SET_PO_ACTIVITY_LOG_LOADING", false);
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
                                commit("SET_PO_ACTIVITY_LOG_LOADING", false);
                                reject(err.message);
                            }
                        } else {
                            commit("SET_PO_ACTIVITY_LOG_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    fetchPoHistoryPreview: async({ commit }, id) => {
        let attempt = false;
        commit("SET_PO_HISTORY_PREVIEW_LOADING", true);
        commit("SET_PO_HISTORY_PREVIEW", []);

        return new Promise((resolve, reject) => {
            function proceed() {
                axios
                    .get(`${poBaseUrl}/v3/orders/history-preview/${id}`)
                    .then((res) => {
                        if (res.status === 200) {
                            if (res.data) {
                                let d = res.data.data;
                                commit("SET_PO_HISTORY_PREVIEW", d);
                            }
                        }
                        commit("SET_PO_HISTORY_PREVIEW_LOADING", false);
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
                                commit("SET_PO_HISTORY_PREVIEW_LOADING", false);
                                reject(err.message);
                            }
                        } else {
                            commit("SET_PO_HISTORY_PREVIEW_LOADING", false);
                            reject(err.error);
                        }
                    });
            }
            proceed();
        });
    },
    setPoHistoryEmpty({ commit }, payload) {
        commit("SET_PO_HISTORY", payload);
    },
};

const methods = {
    testing: () => {
        alert("testing");
    },
};
const mutations = {
    SET_PO_DETAILS: (state, payload) => {
        state.po_details = payload;
    },
    SET_PO_DETAILS_LOADING: (state, payload) => {
        state.po_details_loading = payload;
    },
    SET_PO_HISTORY: (state, payload) => {
        state.poHistory = payload;
    },
    SET_PO_HISTORY_LOADING: (state, payload) => {
        state.poHistoryLoading = payload;
    },
    SET_PO_HISTORY_PREVIEW: (state, payload) => {
        state.poHistoryPreview = payload;
    },
    SET_PO_HISTORY_PREVIEW_LOADING: (state, payload) => {
        state.poHistoryPreviewLoading = payload;
    },
    SET_PO_ACTIVITY_LOG: (state, payload) => {
        state.poActivityLog = payload;
    },
    SET_PO_ACTIVITY_LOG_LOADING: (state, payload) => {
        state.poActivityLogLoading = payload;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
    methods,
};