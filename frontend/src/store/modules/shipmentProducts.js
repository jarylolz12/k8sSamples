/** @format */

import axios from "axios";
import store from "../";

const state = {
	ShipmentProductsDetails: [],
	ShipmentProductsDetailsLoading: false,
	ShipmentProductsDownloadLoading: false,
};

const getters = {
	getShipmentProducts: (state) => state.ShipmentProductsDetails,
	getShipmentProductsLoading: (state) => state.ShipmentProductsDetailsLoading,
	getShipmentProductsDownloadLoading: (state) =>
		state.ShipmentProductsDownloadLoading,
};

const poBaseUrl = process.env.VUE_APP_PO_URL;

const actions = {
	fetchShipmentProducts: async ({ commit }, shipment_id) => {
		commit("SET_SHIPMENT_PRODUCTS", []);
		commit("SET_SHIPMENT_PRODUCTS_LOADING", true);
		await axios
			.get(`${poBaseUrl}/po/shipment-products/${shipment_id}`)
			.then((res) => {
				if (res.status === 200) {
					if (typeof res.data !== "undefined") {
						commit("SET_SHIPMENT_PRODUCTS", res.data.items);
					}
				}
				commit("SET_SHIPMENT_PRODUCTS_LOADING", false);
			})
			.catch((err) => {
				commit("SET_SHIPMENT_PRODUCTS_LOADING", false);
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			});
	},
	downloadOrder: async ({ commit }, item) => {
		let attempt = false;
		commit("SET_DOWNLOAD_LOADING", true);
		return new Promise((resolve, reject) => {
			function proceed() {
				axios({
					url: `${poBaseUrl}/orders/${item.id}/download`,
					method: "GET",
					responseType: "blob",
				})
					.then((response) => {
						commit("SET_DOWNLOAD_LOADING", false);
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
								commit("SET_DOWNLOAD_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_DOWNLOAD_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
};

const mutations = {
	SET_SHIPMENT_PRODUCTS: (state, payload) => {
		state.ShipmentProductsDetails = payload;
	},
	SET_SHIPMENT_PRODUCTS_LOADING: (state, payload) => {
		state.ShipmentProductsDetailsLoading = payload;
	},
	SET_DOWNLOAD_LOADING: (state, payload) => {
		state.ShipmentProductsDownloadLoading = payload;
	},
};

export default {
	state,
	getters,
	actions,
	mutations,
};
