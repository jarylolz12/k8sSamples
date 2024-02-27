/** @format */

import axios from "axios";
import moment from "moment";

const state = {
    cards: [],
    ACHPayments: [],
    cardsLoading: false,
    ACHPaymentMethodLoading: false,
    updateCardLoading: false,
    deleteCardLoading: false,
};

const getters = {
    getcards: (state) => state.cards,
    getACHPayments: (state) => state.ACHPayments,
    getcardsLoading: (state) => state.cardsLoading,
    getACHPaymentMethodLoading: (state) => state.ACHPaymentMethodLoading,
    getUpdateCardLoading: (state) => state.updateCardLoading,
    getDeleteCardLoading: (state) => state.deleteCardLoading,
};

const actions = {
    fetchAllPaymentMethods: async({ commit }, default_customer_id) => {
        commit("SET_CARDS_LOADING", true);
        return await axios
            .get(`payment-method/all/${default_customer_id}`)
            .then((res) => {
                if (
                    res.status === 200 &&
                    typeof res.data !== "undefined" &&
                    res.data.code === 200
                ) {
                    commit("SET_CARDS", res.data.data);
                }
                commit("SET_CARDS_LOADING", false);
                return res;
            })
            .catch((err) => {
                commit("SET_CARDS_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            });
    },
    fetchPaymentMethods: async({ commit }, default_customer_id) => {
        commit("SET_CARDS_LOADING", true);
        await axios
            .get(`payment-method/cc/${default_customer_id}`)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        let cardDetail = res.data;
                        for (let i = 0; i < cardDetail.length; i++) {
                            cardDetail[i].created_at = moment
                                .utc(cardDetail[i].created_at)
                                .format(" MMM DD, YYYY");
                            cardDetail[i].updated_at = moment
                                .utc(cardDetail[i].updated_at)
                                .format(" MMM DD, YYYY");
                        }
                        commit("SET_CARDS", cardDetail);
                    }
                }
                commit("SET_CARDS_LOADING", false);
            })
            .catch((err) => {
                commit("SET_CARDS_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            });
    },
    fetchACHPaymentMethods: async({ commit }, default_customer_id) => {
        commit("SET_ACH_PAYMENT_METHOD_LOADING", true);
        await axios
            .get(`payment-method/ach/${default_customer_id}`)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        let ACHPaymentDetail = res.data;
                        for (let i = 0; i < ACHPaymentDetail.length; i++) {
                            ACHPaymentDetail[i].created_at = moment
                                .utc(ACHPaymentDetail[i].created_at)
                                .format(" MMM DD, YYYY");
                            ACHPaymentDetail[i].updated_at = moment
                                .utc(ACHPaymentDetail[i].updated_at)
                                .format(" MMM DD, YYYY");
                        }
                        commit("SET_ACH_PAYMENTS", ACHPaymentDetail);
                    }
                }
                commit("SET_ACH_PAYMENT_METHOD_LOADING", false);
            })
            .catch((err) => {
                commit("SET_ACH_PAYMENT_METHOD_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            });
    },
    addPaymentMethod: async({ commit }, payload) => {
        commit("SET_CARD_LOADING", true);
        return await axios
            .post("/payment-method/cc/add", payload)
            .then((res) => res)
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_CARD_LOADING", false);
            });
    },
    addACHPaymentMethod: async({ commit }, payload) => {
        commit("SET_ACH_PAYMENT_METHOD_LOADING", true);
        return await axios
            .post("/payment-method/ach/add", payload)
            .then((res) => res)
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_ACH_PAYMENT_METHOD_LOADING", false);
            });
    },
    updatePaymentMethod: async({ commit }, payload) => {
        commit("SET_CARD_LOADING", true);
        await axios
            .put("/payment-method/cc/update", payload)
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_CARD_LOADING", false);
            });
    },
    updateACHPaymentMethod: async({ commit }, payload) => {
        commit("SET_ACH_PAYMENT_METHOD_LOADING", true);
        return await axios
            .put("/payment-method/ach/update", payload)
            .then((res) => res)
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_ACH_PAYMENT_METHOD_LOADING", false);
            });
    },
    removePaymentMethod: async({ commit }, payload) => {
        commit("SET_CARD_DELETE_LOADING", true);
        await axios
            .delete(
                `/payment-method/cc/delete?card_id=${payload.card_id}&default_customer_id=${payload.default_customer_id}`
            )
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_CARD_DELETE_LOADING", false);
            });
    },
    removeACHPaymentMethod: async({ commit }, payload) => {
        commit("SET_CARD_DELETE_LOADING", true);
        await axios
            .delete(
                `/payment-method/ach/delete?record_id=${payload.record_id}&default_customer_id=${payload.default_customer_id}`
            )
            .catch((err) => {
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            })
            .finally(() => {
                commit("SET_CARD_DELETE_LOADING", false);
            });
    },
};

const mutations = {
    SET_CARDS: (state, payload) => {
        state.cards = payload;
    },
    SET_ACH_PAYMENTS: (state, payload) => {
        state.ACHPayments = payload;
    },
    SET_CARDS_LOADING: (state, payload) => {
        state.cardsLoading = payload;
    },
    SET_ACH_PAYMENT_METHOD_LOADING: (state, payload) => {
        state.ACHPaymentMethodLoading = payload;
    },
    SET_CARD_LOADING: (state, payload) => {
        state.updateCardLoading = payload;
    },
    SET_CARD_DELETE_LOADING: (state, payload) => {
        state.deleteCardLoading = payload;
    },
};

export default {
    state,
    mutations,
    actions,
    getters,
};