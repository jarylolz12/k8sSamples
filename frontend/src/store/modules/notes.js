/** @format */
import axios from "axios";

const state = {
	shipmentNotes: [],
	shipmentNotesLoading: false,
	createShipmentNotesLoading: false,
	updateShipmentNotesLoading: false,
	deleteShipmentNotesLoading: false,
};

const getters = {
	getShipmentNotes: (state) => state.shipmentNotes,
	getShipmentNotesLoading: (state) => state.shipmentNotesLoading,
	getCreateShipmentNotesLoading: (state) => state.createShipmentNotesLoading,
	getUpdateShipmentNotesLoading: (state) => state.updateShipmentNotesLoading,
	getDeleteShipmentNotesLoading: (state) => state.deleteShipmentNotesLoading,
};

const actions = {
	fetchShipmentNotes: async ({ commit },shipment_id) => {
		commit("SET_SHIPMENT_NOTES_LOADING", true);
		await axios
			.get(`shipment-notes?resourceId=${shipment_id}`)
			.then((res) => {
				if (res.status === 200) {
					if (typeof res.data !== "undefined") {
						let noteData = res.data.notes;
						commit("SET_SHIPMENT_NOTES", noteData);
					}
				}
				commit("SET_SHIPMENT_NOTES_LOADING", false);
			})
			.catch((err) => {
				commit("SET_SHIPMENT_NOTES_LOADING", false);
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			});
	},
	createShipmentNotes: async ({ commit }, payload) => {
		commit("CREATE_SHIPMENT_NOTES_LOADING", true);
		await axios
			.post(`shipment-notes`, payload)
			.then((res) => res)
			.catch((err) => {
				commit("CREATE_SHIPMENT_NOTES_LOADING", false);
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			})
			.finally(() => {
				commit("CREATE_SHIPMENT_NOTES_LOADING", false);
			});
	},
	updateShipmentNotes: async ({ commit }, payload) => {
		commit("UPDATE_SHIPMENT_NOTES_LOADING", true);
		await axios
			.put(`shipment-notes/${payload.noteId}`, payload)
			.catch((err) => {
				commit("UPDATE_SHIPMENT_NOTES_LOADING", false);
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			})
			.finally(() => {
				commit("UPDATE_SHIPMENT_NOTES_LOADING", false);
			});
	},
	deleteShipmentNotes: async ({ commit }, noteId) => {
		commit("DELETE_SHIPMENT_NOTES_LOADING", true);
		await axios
			.delete(`shipment-notes?noteId=${noteId}`)
			.catch((err) => {
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			})
			.finally(() => {
				commit("DELETE_SHIPMENT_NOTES_LOADING", false);
			});
	},
};

const mutations = {
	SET_SHIPMENT_NOTES: (state, payload) => {
		state.shipmentNotes = payload;
	},
	SET_SHIPMENT_NOTES_LOADING: (state, payload) => {
		state.shipmentNotesLoading = payload;
	},
	CREATE_SHIPMENT_NOTES_LOADING: (state, payload) => {
		state.createShipmentNotesLoading = payload;
	},
	UPDATE_SHIPMENT_NOTES_LOADING: (state, payload) => {
		state.updateShipmentNotesLoading = payload;
	},
	DELETE_SHIPMENT_NOTES_LOADING: (state, payload) => {
		state.deleteShipmentNotesLoading = payload;
	},
};

export default {
	state,
	getters,
	actions,
	mutations,
};
