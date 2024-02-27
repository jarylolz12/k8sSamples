import axios from "axios"
import store from '../../'

const state = {
	// employees
	warehouseEmployees: [],
	warehouseEmployeesLoading: false,
	assignWarehouseEmployeeLoading: false,
	stagingLocationsAssign: [],
	stagingLocationsAssignLoading: false,
	assignLocationLoading: false
}

const getters = {
	getWarehouseEmployees: state => state.warehouseEmployees,
	getWarehouseEmployeesLoading: state => state.warehouseEmployeesLoading,
	getAssignWarehouseEmployeeLoading: state => state.assignWarehouseEmployeeLoading,
	getStagingLocationAssign: state => state.stagingLocationsAssign,
	getStagingLocationAssignLoading: state => state.stagingLocationsAssignLoading,
	getAssignLocationLoading: state => state.assignLocationLoading
}

const poBaseUrl = process.env.VUE_APP_PO_URL 

const actions = {
	fetchWarehouseEmployees: async ({ commit }, id) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_WAREHOUSE_EMPLOYEES_LOADING", true)
				// commit("SET_WAREHOUSE_EMPLOYEES", [])
				axios.get(`${poBaseUrl}/get-warehouse-employee/${id}`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_WAREHOUSE_EMPLOYEES_LOADING", false)
								commit("SET_WAREHOUSE_EMPLOYEES", res.data.results)
							}
						}
					}
					resolve()
				})
				.catch(err => {
					commit('SET_WAREHOUSE_EMPLOYEES_LOADING', false)

					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {
							reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						reject(err.error)
					}
				})
			}
			proceed()
		})		
	},
	assignWarehouseEmployee: async ({ commit }, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false
			
			function proceed() {
				commit("SET_ASSIGN_EMPLOYEE_TO_INBOUND_LOADING", true)

				axios.post(`${poBaseUrl}/assign-employee-to-inbound-multiple`, payload)
					.then(res => {
						commit("SET_ASSIGN_EMPLOYEE_TO_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
						commit('SET_ASSIGN_EMPLOYEE_TO_INBOUND_LOADING', false)

						if (typeof err.message!=='undefined') {
							if (!attempt) {
								attempt = true
								let t = setInterval(() => {
									if ( !store.getters.getIsRefreshing ) {
										proceed()
										clearInterval(t)
									}
								}, 300)
							} else {
								reject(err.message)
							}
						}

						if (typeof err.error !=='undefined') {
							reject(err.error)
						}
					})
			}
			proceed()			
		})
	},
	editTaskEmployee: async ({ commit }, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false
			
			function proceed() {
				commit("SET_ASSIGN_EMPLOYEE_TO_INBOUND_LOADING", true)

				axios.put(`${poBaseUrl}/edit-inbound-task`, payload)
					.then(res => {
						commit("SET_ASSIGN_EMPLOYEE_TO_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
						commit('SET_ASSIGN_EMPLOYEE_TO_INBOUND_LOADING', false)

						if (typeof err.message!=='undefined') {
							if (!attempt) {
								attempt = true
								let t = setInterval(() => {
									if ( !store.getters.getIsRefreshing ) {
										proceed()
										clearInterval(t)
									}
								}, 300)
							} else {
								reject(err.message)
							}
						}

						if (typeof err.error !=='undefined') {
							reject(err.error)
						}
					})
			}
			proceed()			
		})
	},
	fetchLocationsAssign: async ({ commit }, id) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_STAGING_LOCATIONS_ASSIGN_LOADING", true)
				axios.get(`${poBaseUrl}/get-staging-locations/${id}?status[]=staging&status[]=packing&status[]=special&status[]=others`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_STAGING_LOCATIONS_ASSIGN_LOADING", false)
								commit("SET_STAGING_LOCATIONS_ASSIGN", res.data.results)
							}
						}
					}
					resolve()
				})
				.catch(err => {
					commit('SET_STAGING_LOCATIONS_ASSIGN_LOADING', false)

					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {
							reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						reject(err.error)
					}
				})
			}
			proceed()
		})		
	},
	assignLocation: async ({ commit }, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false
			
			function proceed() {
				commit("SET_ASSIGN_LOCATION_LOADING", true)

				axios.post(`${poBaseUrl}/assign-staging-location`, payload)
					.then(res => {
						commit("SET_ASSIGN_LOCATION_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
						commit('SET_ASSIGN_LOCATION_LOADING', false)

						if (typeof err.message!=='undefined') {
							if (!attempt) {
								attempt = true
								let t = setInterval(() => {
									if ( !store.getters.getIsRefreshing ) {
										proceed()
										clearInterval(t)
									}
								}, 300)
							} else {
								reject(err.message)
							}
						}

						if (typeof err.error !=='undefined') {
							reject(err.error)
						}
					})
			}
			proceed()			
		})
	},
}

const mutations = {
	SET_WAREHOUSE_EMPLOYEES: (state, payload) => {
		state.warehouseEmployees = payload
	},
	SET_WAREHOUSE_EMPLOYEES_LOADING: (state, payload) => {
		state.warehouseEmployeesLoading = payload
	},
	SET_ASSIGN_EMPLOYEE_TO_INBOUND_LOADING: (state, payload) => {
		state.assignWarehouseEmployeeLoading = payload
	},
	SET_STAGING_LOCATIONS_ASSIGN: (state, payload) => {
		state.stagingLocationsAssign = payload
	},
	SET_STAGING_LOCATIONS_ASSIGN_LOADING: (state, payload) => {
		state.stagingLocationsAssignLoading = payload
	},	
	SET_ASSIGN_LOCATION_LOADING: (state, payload) => {
		state.assignLocationLoading = payload
	},
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}