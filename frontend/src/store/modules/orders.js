/** @format */
import axios from "axios";

// const betaBaseUrl = process.env.VUE_APP_BASE_URL;
const poBaseUrl = process.env.VUE_APP_PO_URL;

const state = {
	productStatusValue: [],
	productionStatusLoading: false,
};

const getters = {
	getProductStatusValue: (state) => state.productStatusValue,
	getProductionStatusLoading: (state) => state.productionStatusLoading,
};

const actions = {
	fetchProductStatus: async ({ commit }) => {
		await axios
			.get(`${poBaseUrl}/v3/orders/product/status`)
			.then((res) => {
				if (res.status === 200) {
					if (typeof res.data !== "undefined") {
						commit("SET_PRODUCT_STATUS_VALUE", res.data);
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
	updateProductionStatus: async ({ commit }, payload) => {
		commit("SET_PRODUCTION_STATUS_LOADING", true);
		await axios
			.put(`${poBaseUrl}/v3/orders/production/status/update`, payload)
			.catch((err) => {
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			})
			.finally(() => {
				commit("SET_PRODUCTION_STATUS_LOADING", false);
			});
	},
};

const mutations = {
	SET_PRODUCT_STATUS_VALUE: (state, payload) => {
		state.productStatusValue = payload;
	},
	SET_PRODUCTION_STATUS_LOADING: (state, payload) => {
		state.productionStatusLoading = payload;
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
	getters,
};
