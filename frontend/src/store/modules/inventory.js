import axios from "axios"
import store from '../'

const state = {
    inventory: [],
    inventoryLoading: false,
    addInventory: {},
    isEditing: false,
    editInventory: [
        {
            product: {
                id: ''
            },
            carton_count: '',
            in_each_carton: '',
            total_unit: ''
        }
    ],
    viewInventory: {},
    addInventoryError: '',
    editInventorError: '',
    itemsLoading: true,
	deleteInventoryLoading: false,
	itemsFetching: '',
}

const getters = {
    getInventory: state => state.inventory,
    getInventoryLoading: state => state.inventoryLoading,
    addInventoryError: state => state.addInventoryError,
    editInventorError: state => state.editInventorError,
    editInventory: state => state.editInventory,
    isEditing: state => state.isEditing,
    itemsLoading: state => state.itemsLoading,
	itemsFetching: state => state.itemsFetching,
	getDeleteInventoryLoading: state => state.deleteInventoryLoading,
}

const poBaseUrl = process.env.VUE_APP_PO_URL

const actions = {
	setIsEditing: ({
		commit
	}, payload) => {
		commit('SET_IS_EDITING', payload)
	},
	setItemsLoading: ({
		commit
	}, payload) => {
		commit('SET_ITEMS_LOADING', payload)
	},
	setItemsFetching: ({
		commit
	}, payload) => {
		commit('SET_ITEMS_FETCHING', payload)
	},
	setEditInventory: ({
		commit
	}, payload) =>{
		commit('SET_EDIT_INVENTORY', payload)
	},
	updateInventory: async({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false
			function proceed() {
				let {
					warehouse_id,
					product_id,
					id,
					...otherProps
				} = payload

				payload = {
					product_id,
					_method: 'PUT',
					...otherProps
				}

				let fd = new FormData()
				let getPayloadKeys = Object.keys(payload)

				if (getPayloadKeys.length > 0) {
					getPayloadKeys.map(gpk => {
						fd.append(gpk, payload[gpk])
					})
				}
				
				//set loading to true
				commit("SET_INVENTORY_LOADING", true)
					
				axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/products/update/${id}`, fd)
				.then(res => {
					commit("SET_INVENTORY_LOADING", false)
					if (typeof res!=='undefined' && res.status === 200) {
						resolve(res.data)
					}
				})
				.catch(err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )	{
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_INVENTORY_LOADING', false)
							reject('Token has been expired. Please reload the page.')
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_INVENTORY_LOADING', false)
						//reject(err.error)
						//commit("SET_PO_DETAIL_LOADING", false)
						reject(err.error)
					}
					/*

					commit("SET_INVENTORY_LOADING", false)
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
	createInventory: async ({
		commit
	}, payload) => {

		return new Promise((resolve, reject) => {
			let attempt = false
			function proceed() {
				let {
					warehouse_id,
					...otherProps
				} = payload

				payload = {
					...otherProps
				}
				//set loading to true
				commit("SET_INVENTORY_LOADING", true)
				axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/products/create`, payload)
				.then(res => {
					commit("SET_INVENTORY_LOADING", false)
					if (typeof res!=='undefined' && res.status === 200) {
						resolve(res.data)
					}
				})
				.catch(err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )	{
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_INVENTORY_LOADING', false)
							reject('Token has been expired. Please reload the page.')
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_INVENTORY_LOADING', false)
						//reject(err.error)
						//commit("SET_PO_DETAIL_LOADING", false)
						reject(err.error)
					}
					/*
					commit("SET_INVENTORY_LOADING", false)
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
    fetchInventories: async ({
		commit
	}, id ) => {
		
		let attempt = false
		commit("SET_INVENTORY_LOADING", true)
		commit("SET_INVENTORY", [])
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouses/${id}/products`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							// console.log(res.data)
							commit("SET_INVENTORY_LOADING", false)
							commit("SET_INVENTORY", res.data)
						}
					}
					resolve()
				})
				.catch(err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )	{
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_INVENTORY_LOADING', false)
							reject('Token has been expired. Please reload the page.')
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_INVENTORY_LOADING', false)
						//reject(err.error)
						//commit("SET_PO_DETAIL_LOADING", false)
						reject(err.error)
					}
					/*
					commit("SET_INVENTORY_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						return Promise.reject('Token has been expired. Please reload the page.')
					}*/
				})	
			}
			proceed()
		})

		

		
	},
	deleteInventory: async ({
		commit
	}, payload ) => {

		let attempt = false
		commit("SET_DELETE_INVENTORY_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.delete(`${poBaseUrl}/warehouses/${payload.warehouseId}/products/delete/${payload.inventoryId}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_DELETE_INVENTORY_LOADING", false)
							// commit("SET_DELETE_CATEGORIES", res.data)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing )	{
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_ERROR", err)
							commit('SET_DELETE_INVENTORY_LOADING', false)
							reject('Token has been expired. Please reload the page.')
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_ERROR", err)
						commit('SET_DELETE_INVENTORY_LOADING', false)
						//reject(err.error)
						//commit("SET_PO_DETAIL_LOADING", false)
						reject(err.error)
					}
					/*
					commit("SET_ERROR", err)
					commit("SET_DELETE_INVENTORY_LOADING", false)
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
}

const mutations = {
	SET_TEST: (state, payload) => {
		state.test = payload
	},
    SET_INVENTORY: (state, payload) => {
		state.inventory = payload
	},
	SET_INVENTORY_LOADING: (state, payload) => {
		state.inventoryLoading = payload
	},
	SET_EDIT_INVENTORY: (state, payload) => {
		state.editInventory  = payload
	},
	SET_ITEMS_LOADING: (state, payload) => {
		state.itemsLoading = payload
	},
	SET_ITEMS_FETCHING: (state, payload) => {
		state.itemsFetching = payload
	},
	SET_IS_EDITING: (state, payload) => {
		state.isEditing = payload
	},
	SET_DELETE_INVENTORY_LOADING: (state, payload) => {
		state.deleteInventoryLoading = payload
	},
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}