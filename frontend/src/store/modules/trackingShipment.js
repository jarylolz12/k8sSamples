import ShipmentModel from '../../models/ShipmentModel'

const state = {
	delete: {
		loading: false
	},
	deletedShipmentAlert: false
}

/* getters */
const getters = {
	getDeleteLoading: state => state.delete.loading,
	getDeletedShipmentAlert: state => state.deletedShipmentAlert
}

/* constant urls */
const betaBaseUrl = process.env.VUE_APP_BASE_URL

/* actions */
const actions = {
	setDeleteLoading:({ commit }, payload) => {
		commit('SET_DELETE_LOADING', payload)
	},
	setDeletedShipmentAlert: ({ commit }, payload) => {
		commit('SET_DELETED_SHIPMENT_ALERT', payload)
	},
	delete: async({ commit }, shipment_id) => {
		return new Promise((resolve, reject) => {
			function proceed () {
				commit('SET_DELETE_LOADING', true)
				let shipmentModel = new ShipmentModel(betaBaseUrl)
				//delete now the tracking shipment
				shipmentModel.delete(shipment_id).then(() => {
					commit('SET_DELETE_LOADING', false)
					resolve()
				}).catch( err => {
					commit('SET_DELETE_LOADING', false)
					reject(err)
				})
			}
			proceed()
		})
	}
}

/* mutations */
const mutations = {
	SET_DELETE_LOADING: (state, payload) => {
		state.delete.loading = payload
	},
	SET_DELETED_SHIPMENT_ALERT: (state, payload) => {
		state.deletedShipmentAlert = payload
	}
}

export default {
	namespaced: true,
	state,
	mutations,
	actions,
	getters
}