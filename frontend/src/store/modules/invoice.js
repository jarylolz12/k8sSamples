/** @format */
import axios from "axios";
import Invoice from "../../custom/InvoiceResource";
import moment from "moment";

const state = {
    invoices: [],
    invoice_loading: false,
    invoice_detail_loading: false,
    payment_loading: false,
    multiple_payment_loading: false,
    invoice_download_loading: false,
    invoice: {},
    billingLocalQuery: "",
};

const getters = {
    getInvoice: (state) => state.invoice,
    getAllInvoices: (state) => state.invoices,
    getInvoiceLoadingStatus: (state) => state.invoice_loading,
    getInvoiceDetailLoadingStatus: (state) => state.invoice_detail_loading,
    getInvoiceDownloadLoadingStatus: (state) => state.invoice_download_loading,
    getPaymentLoading: (state) => state.payment_loading,
    getMultiplePaymentLoading: (state) => state.multiple_payment_loading,
    getBillingLocalQuery: (state) => state.billingLocalQuery,
};

const actions = {
    fetchInvoices: async({ commit }) => {
        commit("SET_INVOICE_LOADING", true);

        await axios
            .get("/quickbooks/invoices")
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        let newInvoices = [];
                        if (Array.isArray(res.data.data) && res.data.data.length > 0) {
                            res.data.data.map((val) => {
                                newInvoices.push(val);
                            });
                        }
                        const invoice = new Invoice(newInvoices);
                        commit("SET_INVOICES", invoice.all());
                    }
                }
                commit("SET_INVOICE_LOADING", false);
            })
            .catch((err) => {
                commit("SET_INVOICE_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    if (typeof err.message !== "undefined") {
                        return Promise.reject(
                            "Token has been expired. Please reload the page."
                        );
                    }
                }
            });
    },
    fetchInvoice: async({ commit }, id) => {
        commit("SET_INVOICE_DETAIL_LOADING", true);

        await axios
            .get(`/quickbooks/invoice/${id}`)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        let invoiceDetail = res.data;
                        invoiceDetail.id = id;
                        //const invoice = new Invoice(newInvoices);
                        invoiceDetail.etd = moment
                            .utc(invoiceDetail.etd)
                            .format(" DD/MM/YYYY");
                        invoiceDetail.eta = moment
                            .utc(invoiceDetail.eta)
                            .format(" DD/MM/YYYY");
                        commit("SET_INVOICE_DETAIL", invoiceDetail);
                    }
                }
                commit("SET_INVOICE_DETAIL_LOADING", false);
            })
            .catch((err) => {
                commit("SET_INVOICE_DETAIL_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    if (typeof err.message !== "undefined") {
                        return Promise.reject(
                            "Token has been expired. Please reload the page."
                        );
                    }
                }
            });
    },
    makePayment: async({ commit }, payload) => {
        commit("SET_PAYMENT_LOADING", true);
        return await axios
            .post("/make-payment/charge", payload)
            .then((res) => res)
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    Promise.reject(err.error);
                } else {
                    Promise.reject(JSON.stringify(err.errors.type));
                }
            })
            .finally(() => {
                commit("SET_PAYMENT_LOADING", false);
            });
    },
    downloadInvoice: async({ commit }, payload) => {
        commit("SET_INVOICE_DOWNLOAD_LOADING", true);
        await axios
            .get(`/quickbooks/download-invoice?id=${payload.id}`, {
                responseType: "blob",
            })
            .then((res) => {
                var url = window.URL.createObjectURL(new Blob([res.data]));
                var a = document.createElement("a");
                a.href = url;
                a.download = `invoice-${payload.invoice_number}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
            })
            .catch((err) => {
                commit("SET_INVOICE_DOWNLOAD_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_INVOICE_DOWNLOAD_LOADING", false);
            });
    },
    setReset({ commit }, payload) {
        commit("setReset", payload);
    },
    setBillingLocalQuery: ({ commit }, payload) => {
        commit("SET_BILLING_LOCAL_QUERY", payload);
    },
};

const mutations = {
    SET_INVOICES: (state, payload) => {
        state.invoices = payload;
    },
    SET_INVOICE_DETAIL: (state, payload) => {
        state.invoice = payload;
    },
    SET_INVOICE_LOADING: (state, payload) => {
        state.invoice_loading = payload;
    },
    SET_PAYMENT_LOADING: (state, payload) => {
        state.payment_loading = payload;
    },
    SET_MULTIPLE_PAYMENT_LOADING: (state, payload) => {
        state.multiple_payment_loading = payload;
    },
    SET_INVOICE_DETAIL_LOADING: (state, payload) => {
        state.invoice_detail_loading = payload;
    },
    SET_INVOICE_DOWNLOAD_LOADING: (state, payload) => {
        state.invoice_download_loading = payload;
    },
    setReset: (state, payload) => {
        state.invoices = payload;
    },
    SET_BILLING_LOCAL_QUERY: (state, payload) => {
        state.billingLocalQuery = payload;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};