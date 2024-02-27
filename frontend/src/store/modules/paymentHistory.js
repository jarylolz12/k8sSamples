/** @format */
import axios from "axios";

const state = {
    payments: [],
    paymentsLoading: false,
    paymentsReceiptLoading: false,
    totalRecords: 0,
    paginationLoader: false,
};

const getters = {
    getPayments: (state) => state.payments,
    getBillingPaymentsLoading: (state) => state.paymentsLoading,
    getBillingPaymentsReceiptLoading: (state) => state.paymentsReceiptLoading,
    getTotalRecords: (state) => state.totalRecords,
    getPaginationLoader: (state) => state.paginationLoader,
};

const actions = {
    fetchPayments: async({ commit }, payload) => {
        commit("SET_PAYMENTS_LOADING", true);
        if (payload.paginationLoader === true) {
            commit("SET_PAGINATION_LOADER", true);
        }
        return await axios
            .get(
                `/payment-history/${payload.defaultCustomerId}/all?offset=${payload.startPosition}&limit=${payload.maximumResultPerPage}`
            )
            .then((res) => {
                if (
                    res.data &&
                    typeof res.data !== "undefined" &&
                    res.data.code === 200
                ) {
                    commit("SET_PAYMENTS", res.data.data);
                    commit("SET_TOTAL_RECORDS", res.data.totalRecords);
                } else {
                    commit("SET_PAYMENTS", []);
                    commit("SET_TOTAL_RECORDS", 0);
                    return Promise.reject(res.data.message);
                }
                commit("SET_PAGINATION_LOADER", false);
            })
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    if (typeof err.message !== "undefined") {
                        return Promise.reject(
                            "Token has been expired. Please reload the page."
                        );
                    }
                }
            })
            .finally(() => {
                commit("SET_PAYMENTS_LOADING", false);
                commit("SET_PAGINATION_LOADER", false);
            });
    },
    downloadPaymentReceipt: async({ commit }, payload) => {
        commit("SET_PAYMENTS_RECEIPT_LOADING", true);
        await axios
            .get(
                `/payment-history/${payload.defaultCustomerId}/download-receipt/${payload.receiptNo}`, {
                    responseType: "blob",
                }
            )
            .then((res) => {
                var url = window.URL.createObjectURL(new Blob([res.data]));
                var a = document.createElement("a");
                a.href = url;
                a.download = `payment-receipt-${payload.receiptNo}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
            })
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_PAYMENTS_RECEIPT_LOADING", false);
            });
    },
};

const mutations = {
    SET_PAYMENTS: (state, payload) => {
        state.payments = payload;
    },
    SET_PAYMENTS_LOADING: (state, payload) => {
        state.paymentsLoading = payload;
    },
    SET_PAYMENTS_RECEIPT_LOADING: (state, payload) => {
        state.paymentsReceiptLoading = payload;
    },
    SET_TOTAL_RECORDS: (state, payload) => {
        state.totalRecords = payload;
    },
    SET_PAGINATION_LOADER: (state, payload) => {
        state.paginationLoader = payload;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};