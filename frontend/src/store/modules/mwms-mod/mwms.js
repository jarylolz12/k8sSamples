import axios from "axios"
import store from '../..'

const state = {
	// employees
	employeeWarehouses: [],
	employeeWarehousesLoading: false,
	employeeOrders: [],
	employeeOrdersLoading: false
}

const getters = {
	getEmployeeWarehouses: state => state.employeeWarehouses,
	getEmployeeWarehousesLoading: state => state.employeeWarehousesLoading,
	getEmployeeOrders: state => state.employeeOrders,
	getEmployeeOrdersLoading: state => state.employeeOrdersLoading,
}

const poBaseUrl = process.env.VUE_APP_PO_URL 

const actions = {
	fetchEmployeeWarehouses: async ({ commit }, id) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_EMPLOYEE_WAREHOUSES_LOADING", true)
				axios.get(`${poBaseUrl}/v2/get-user-warehouses/${id}`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_EMPLOYEE_WAREHOUSES_LOADING", false)
								commit("SET_EMPLOYEE_WAREHOUSES", res.data.results)
							}
						}
					}
					resolve()
				})
				.catch(err => {
					commit('SET_EMPLOYEE_WAREHOUSES_LOADING', false)

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
	fetchEmployeeOrders: async ({ commit }, payload) => {
		let attempt = false
		let { warehouse_id, customer_id } = payload

		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_EMPLOYEE_ORDERS_LOADING", true)
				axios.get(`${poBaseUrl}/v2/warehouse/${warehouse_id}/get-orders/${customer_id}`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_EMPLOYEE_ORDERS_LOADING", false)
								commit("SET_EMPLOYEE_ORDERS", res.data.results)
							}
						}
					}
					resolve()
				})
				.catch(err => {
					commit('SET_EMPLOYEE_ORDERS_LOADING', false)

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
}

const mutations = {
	SET_EMPLOYEE_WAREHOUSES: (state, payload) => {
		state.employeeWarehouses = payload
	},
	SET_EMPLOYEE_WAREHOUSES_LOADING: (state, payload) => {
		state.employeeWarehousesLoading = payload
	},
	SET_EMPLOYEE_ORDERS: (state, payload) => {
		state.employeeOrders = payload
	},
	SET_EMPLOYEE_ORDERS_LOADING: (state, payload) => {
		state.employeeOrdersLoading = payload
	},
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}