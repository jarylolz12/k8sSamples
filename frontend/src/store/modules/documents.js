import ShipmentDocumentModel from '../../models/ShipmentDocumentModel'

const state = {
	create: {
		loading: false,
	},
	deleteMultipleLoading: false,
	update: {
		loading: false
	},
	editId: 0,
	editDocument: {}
}

/* getters */
const getters = {
	getUpdateLoading: state => state.update.loading,
	getEditId: state => state.editId,
	getEditDocument: state => state.editDocument,
	getDeleteMultipleLoading: state => state.deleteMultipleLoading
}

/* constant urls */
const betaBaseUrl = process.env.VUE_APP_BASE_URL

/* actions */
const actions = {
	setEditId: async({
		commit
	}, id) => {
		commit('SET_EDIT_ID', id)
	},
	setEditDocument: async({
		commit
	}, payload) => {
		commit('SET_EDIT_DOCUMENT', payload)
	},
	setDeleteMultipleLoading: ({
		commit
	}, payload) => {
		commit('SET_DELETE_LOADING', payload)
	},
	deleteDocuments: async({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			function proceed () {
				commit('SET_DELETE_MULTIPLE_LOADING', true)
				let shipmentDocumentModel = new ShipmentDocumentModel(betaBaseUrl)
				shipmentDocumentModel.deleteDocuments(payload).then(() => {
					commit('SET_DELETE_MULTIPLE_LOADING', false)
					resolve()
				}).catch( err => {
					commit('SET_DELETE_MULTIPLE_LOADING', false)
					reject(err)
				})
			}
			proceed()
		})
	},
	updateNameType: async ({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			function proceed () {
				commit('SET_UPDATE_LOADING', true)
				let shipmentDocumentModel = new ShipmentDocumentModel(betaBaseUrl)

				shipmentDocumentModel.updateNameType(payload).then(() => {
					commit('SET_UPDATE_LOADING', false)
					resolve()
				}).catch( err => {
					commit('SET_UPDATE_LOADING', false)
					reject(err)
				})
			}
			proceed()
		})
	}
}
const mutations = {
	SET_UPDATE_LOADING: (state, payload) => {
		state.update.loading = payload	
	},
	SET_EDIT_ID: (state, payload) => {
		state.editId = payload	
	},
	SET_DELETE_MULTIPLE_LOADING: (state, payload) => {
		state.deleteMultipleLoading = payload
	},
	SET_EDIT_DOCUMENT: (state, payload) => {
		state.editDocument = payload	
	}
}

export default {
	namespaced: true,
	state,
	mutations,
	actions,
	getters
}