/** @format */

import axios from "axios";
 //import store from "..";
// import qs from "qs";

const state = {
	drayageDetails: [],
	drayageLoading: true,
};
const poBaseUrl = process.env.VUE_APP_TRUCKING_URL;
const getters = {
	getDrayage: (state) => state.drayageDetails,
	getDrayageLoading: (state) => state.drayageLoading,	
};

const actions = {
	fetchDrayage: async ({ commit }, payload) => {
		commit("SET_CUSTOMERS_LOADING", true);		
            await axios.get(`${poBaseUrl}/drayage/customers/`+payload+`/shipments`)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_CUSTOMERS_LOADING", false);
								commit("SET_DRAYAGE", res.data);
								//resolve();
							}
						}
					})
					.catch((err) => {
                        //console.log(err);
                        commit("SET_CUSTOMERS_LOADING", false);
						if (typeof err.message !== "undefined") {
							if (typeof err.error !== 'undefined') {
                                return Promise.reject(err.error)
                            } else {
                                return Promise.reject(err.message)
                            }
						}						
					});	
	},	
};

const mutations = {
	SET_DRAYAGE: (state, payload) => {
		state.drayageDetails = payload;
	},
	SET_CUSTOMERS_LOADING: (state, payload) => {
		state.drayageLoading = payload;
	}	
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
	getters,
};
