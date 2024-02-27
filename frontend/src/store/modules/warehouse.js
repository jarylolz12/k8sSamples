import axios from "axios"
// // import router from '../../router/index'
// import Shipment from './../../custom/ShipmentResource'
import store from '../'
const state = {
	warehouse: [],
	warehouseLoading: false,
	warehouseDelete: null,
	warehouseDeleteLoading: false,
	countries: [],
	countriesLoading: false,
	states: [],
	statesLoading: false,
	cities: [],
	citiesLoading: false,
	isEditing: false,
	editWarehouse: {},
	warehouseCreateLoading: false,
	warehouseSaveAddLoading: false,
	singleWarehouse: null,
	singleWarehouseLoading: false,
	currentWarehouse: null,
	currentSort: 'name-asc',
	activeId: 0,
	// connected 3pl
	warehouse3PLData: null,
	warehouse3PLDataLoading: false,
	warehouseUpdate3PLDataLoading: false,
	warehouseDisconnectLoading: false
}

const getters = {
	getWarehouse: state => state.warehouse,
	getWarehouseLoading: state => state.warehouseLoading,
	getWarehouseCreateLoading: state => state.warehouseCreateLoading,
	getWarehouseDelete: state => state.warehouse,
	getWarehouseDeleteLoading: state => state.warehouseDeleteLoading,
	getCountries: state => state.countries,
	getCountriesLoading: state => state.countriesLoading,
	getStates: state => state.states,
	getStatesLoading: state => state.statesLoading,
	getCities: state => state.cities,
	getCitiesLoading: state => state.citiesLoading,
	editWarehouse: state => state.editWarehouse,
	isEditing: state => state.isEditing,
	getWarehouseSaveAddLoading: state => state.warehouseSaveAddLoading,
	getSingleWarehouse: state => state.singleWarehouse,
	getSingleWarehouseLoading: state => state.singleWarehouseLoading,
	getCurrentSort: state => state.currentSort,
	getCurrentWarehouse: state => state.currentWarehouse,
	getActiveId: state => state.activeId,
	getWarehouse3PLData: state => state.warehouse3PLData,
	getWarehouse3PLDataLoading: state => state.warehouse3PLDataLoading,
	getWarehouseUpdate3PLDataLoading: state => state.warehouseUpdate3PLDataLoading,
	getWarehouseDisconnectLoading: state => state.warehouseDisconnectLoading
}

const poBaseUrl = process.env.VUE_APP_PO_URL

const actions = {
	setIsEditing: ({
		commit
	}, payload) => {
		commit('SET_IS_EDITING', payload)
	},
	setSort({ commit }, payload) {
		commit("setSort", payload)
	},
	setCurrentWarehouse({ commit }, payload) {
		commit("setCurrentWarehouse", payload)
	},
	setActiveId({ commit }, payload) {
		commit("setActiveId", payload)
	},
	updateWarehouse: async ( {commit }, payload) => {

		return new Promise((resolve, reject) => {

			let attempt = false
			commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', true)
			let { 
				id,
				...otherPayload
			} = payload

			payload = {
				...otherPayload
			}
			function proceed() {

				axios.post(`${poBaseUrl}/warehouses/update/${id}`, payload).then(res => {
					commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', false)
					if (typeof res!=='undefined' && res.status === 200) {
						resolve(res.data)
					}
				})
				.catch( err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )   {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error!=='undefined') {
						commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', false)
						reject(err.error)
					}

					/*
					commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', false)
					if (typeof err.error !== 'undefined') {
						reject(err.error)
					} else {
						reject(err.message)
					}*/
				})
			}
			proceed()
		})
	},
	createWarehouse: async ( {commit }, payload) => {

		return new Promise((resolve, reject) => {

			let attempt = false
			commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', true)
			function proceed() {
				axios.post(`${poBaseUrl}/warehouses/create`, payload).then(res => {
					commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', false)
					if (typeof res!=='undefined' && res.status === 200) {
						resolve(res.data)
					}
				})
				.catch( err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )   {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error!=='undefined') {
						commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', false)
						reject(err.error)
					}

					/*
					commit((typeof payload.another!=='undefined') ? 'SET_WAREHOUSE_SAVE_ADD_LOADING': 'SET_WAREHOUSE_CREATE_LOADING', false)
					if (typeof err.error !== 'undefined') {
						reject(err.error)
					} else {
						reject(err.message)
					}*/
				})	
			}
			proceed()
			
		})
	},
	fetchWarehouse: async ({
		commit
	}, ) => {
		commit("SET_WAREHOUSE_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouses`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_WAREHOUSE_LOADING", false)					
							commit("SET_WAREHOUSE", res.data)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )   {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_WAREHOUSE_LOADING", false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_WAREHOUSE_LOADING", false)
						reject(err.error)
					}
					/*
					commit("SET_WAREHOUSE_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject(err.message)
					}*/
				})	
			}
			proceed()	
		})

		
		
	},
	deleteSelectedWarehouse: async({
		commit
	}, id) => {
		let attempt = false
		commit("SET_DELETE_WAREHOUSE_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.delete(`${poBaseUrl}/warehouses/delete/${id}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							// console.log(res.data);
							commit("SET_DELETE_WAREHOUSE_LOADING", false)
							// commit("SET_DELETE_WAREHOUSE", res.data)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )   {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_DELETE_WAREHOUSE_LOADING", false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_DELETE_WAREHOUSE_LOADING", false)
						reject(err.error)
					}
					/*
					commit("SET_DELETE_WAREHOUSE_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject(err.message)
					}*/
				})
			}
			proceed()
		})
	},
	fetchCountries: async ({
		commit
	}, ) => {
		let attempt = false

		return new Promise((resolve, reject) => {
			commit("SET_COUNTRIES_LOADING", true)
			function proceed() {
				axios.get(`${poBaseUrl}/countries`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							// console.log(res.data)
							commit("SET_COUNTRIES_LOADING", false)
							commit("SET_COUNTRIES", res.data)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )   {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_COUNTRIES_LOADING", false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_COUNTRIES_LOADING", false)
						reject(err.error)
					}
					/*
					commit("SET_COUNTRIES_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject(err.message)
					}*/
				})	
			}
			proceed()
		})

		
		
	},
	fetchStates: async ({
		commit
	}, country ) => {
		let attempt = false
		commit("SET_STATES_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/states/${country}`).then(result => {	
					commit("SET_STATES_LOADING", false)
					commit("SET_STATES", result.data)
					resolve()
				}).catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )   {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							console.log("What is error", err)
							commit("SET_STATES_LOADING", false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error!=='undefined') {
						console.log("What is error", err)
						commit("SET_STATES_LOADING", false)
						reject(err.error)
					}
				})
				//commit("SET_STATES_LOADING", false)
			}
			proceed()
		})

		// return new Promise( (resolve, reject ) => {
		// 	axios.get(`${poBaseUrl}/states/${country}`)
		// 	.then(res => {
		// 		if (res.status === 200) {
		// 			if (res.data) {
		// 				// console.log(res.data)
		// 				commit("SET_STATES_LOADING", false)
		// 				commit("SET_STATES", res.data)
		// 				resolve('ok')
		// 			}
		// 		}
		// 	})
		// 	.catch(err => {
		// 		commit("SET_STATES_LOADING", false)
		// 		if (typeof err.error !== 'undefined') {
		// 			reject(err.error)
		// 		} else {
		// 			reject(err.message)
		// 		}
		// 	})	
		// })
		
	},
	fetchCities: async ({
		commit
	}, payload ) => {

		let attempt = false
		commit("SET_CITIES_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				
				axios.get(`${poBaseUrl}/cities/${payload.country}/${payload.states}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							// console.log(res.data)
							commit("SET_CITIES_LOADING", false)
							commit("SET_CITIES", res.data)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )   {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_CITIES_LOADING", false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_CITIES_LOADING", false)
						reject(err.error)
					}

					/*
					commit("SET_CITIES_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject(err.message)
					}*/
				})
			}
			proceed()
		})
		

		
	},
	fetchSingleWarehouse: async ({
		commit
	}, id ) => {
		let attempt = false
		commit("SET_SINGLE_WAREHOUSE_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouses/${id}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							// console.log(res.data)
							commit("SET_SINGLE_WAREHOUSE_LOADING", false)
							commit("SET_SINGLE_WAREHOUSE", res.data)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )   {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_SINGLE_WAREHOUSE_LOADING", false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_SINGLE_WAREHOUSE_LOADING", false)
						reject(err.error)
					}
					/*
					commit("SET_SINGLE_WAREHOUSE_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject(err.message)
					}*/
				})	
			}
			proceed()	
		})
		

		
	},
	setStatesReset({ commit }, payload) {
		commit("setStatesReset", payload)
	},
	setCitiesReset({ commit }, payload) {
		commit("setCitiesReset", payload)
	},
	// connected 3pl data
	fetchConnected3PLWarehouseData: async ({
		commit
	}, payload) => {
		commit("SET_WAREHOUSE_CONNECTED_3PL_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouse/${payload.warehouse_id}/warehouse-customer/${payload.wh_customer_id}/get-connected-3pl-details`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_WAREHOUSE_CONNECTED_3PL_LOADING", false)					
							commit("SET_WAREHOUSE_CONNECTED_3PL", res.data.results)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_WAREHOUSE_CONNECTED_3PL_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_WAREHOUSE_CONNECTED_3PL_LOADING", false)
						reject(err.error)
					}
				})	
			}
			proceed()	
		})
	},
	updateConnected3PLWarehouse: async ( { commit }, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false

			let { warehouse_customer_id, ...otherPayload } = payload
			payload = { ...otherPayload }

			commit("SET_UPDATE_WAREHOUSE_CONNECTED_3PL_LOADING", true)

			function proceed() {
				axios.put(`${poBaseUrl}/edit-connected-3pl-warehouse/${warehouse_customer_id}`, payload).then(res => {					
					if (typeof res!=='undefined' && res.status === 200) {
						commit("SET_UPDATE_WAREHOUSE_CONNECTED_3PL_LOADING", false)
						resolve(res.data)
					}
				})
				.catch(err => {
					console.log(err);
					commit("SET_UPDATE_WAREHOUSE_CONNECTED_3PL_LOADING", false)

					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_UPDATE_WAREHOUSE_CONNECTED_3PL_LOADING", false)
							reject(err.message)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_UPDATE_WAREHOUSE_CONNECTED_3PL_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	setConnected3PLWarehouseReset({ commit }, payload) {
		commit("setConnected3PLWarehouseReset", payload)
	},
	// connected 3pl
	disconnectConnectedWarehouse: async({
		commit
	}, payload) => {
		let attempt = false
		commit("SET_DISCONNECT_WAREHOUSE_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.delete(`${poBaseUrl}/warehouse/${payload.id}/warehouse-customer/${payload.warehouse_cid}/delete-connected-3pl-warehouse`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_DISCONNECT_WAREHOUSE_LOADING", false)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ) {
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )   {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_DISCONNECT_WAREHOUSE_LOADING", false)
							reject(err.message)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_DISCONNECT_WAREHOUSE_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
}

const mutations = {	
	setSort: (state, payload) => {
		state.currentSort = payload
	},
	setActiveId: (state, payload) => {
		state.activeId = payload
	},
	setCurrentWarehouse: (state, payload) => {
		state.currentWarehouse = payload
	},
	SET_WAREHOUSE: (state, payload) => {
		state.warehouse = payload
	},
	SET_WAREHOUSE_LOADING: (state, payload) => {
		state.warehouseLoading = payload
	},
	SET_WAREHOUSE_CREATE_LOADING: (state, payload) => {
		state.warehouseCreateLoading = payload
	},
	SET_WAREHOUSE_SAVE_ADD_LOADING: (state, payload) => {
		state.warehouseSaveAddLoading = payload
	},
	SET_DELETE_WAREHOUSE: (state, payload) => {
		state.warehouseDelete = payload
	},
	SET_DELETE_WAREHOUSE_LOADING: (state, payload) => {
		state.warehouseDeleteLoading = payload
	},
	SET_COUNTRIES: (state, payload) => {
		state.countries = payload
	},
	SET_COUNTRIES_LOADING: (state, payload) => {
		state.countriesLoading = payload
	},
	SET_STATES: (state, payload) => {
		state.states = payload
	},
	SET_STATES_LOADING: (state, payload) => {
		state.statesLoading = payload
	},
	SET_CITIES: (state, payload) => {
		state.cities = payload
	},
	SET_CITIES_LOADING: (state, payload) => {
		state.citiesLoading = payload
	},
	SET_IS_EDITING: (state, payload) => {
		state.isEditing = payload
	},
	SET_SINGLE_WAREHOUSE: (state, payload) => {
		state.singleWarehouse = payload
	},
	SET_SINGLE_WAREHOUSE_LOADING: (state, payload) => {
		state.singleWarehouseLoading = payload
	},
	setStatesReset: (state, payload) => {
		state.states = payload
	},
	setCitiesReset: (state, payload) => {
		state.cities = payload
	},
	// Connected 3PL
	SET_WAREHOUSE_CONNECTED_3PL: (state, payload) => {
		state.warehouse3PLData = payload
	},
	SET_WAREHOUSE_CONNECTED_3PL_LOADING: (state, payload) => {
		state.warehouse3PLDataLoading = payload
	},
	SET_UPDATE_WAREHOUSE_CONNECTED_3PL_LOADING: (state, payload) => {
		state.warehouseUpdate3PLDataLoading = payload
	},
	setConnected3PLWarehouseReset: (state, payload) => {
		state.warehouse3PLData = payload
	},
	SET_DISCONNECT_WAREHOUSE_LOADING: (state, payload) => {
		state.warehouseDisconnectLoading = payload
	},
}

export default {
	namespaced: true,
	state,
	mutations,
	actions,
	getters
}