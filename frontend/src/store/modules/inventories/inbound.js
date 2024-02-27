import axios from "axios"
import store from '../../'
import ProductModel from '../../../models/ProductModel'
import * as warehouse from '../warehouse'

const state = {
	inboundInventoriesLoading: false,
	inboundInventories: [],
	inboundCreateLoading: false,
	singleInbound: null,
	singleInboundLoading: false,
	inboundUpdateLoading: false,
	cancelInboundLoading: false,
	truckArrivedLoading: false,
	receiveProductLoading: false,
	receiveProductMultipleLoading: false,
	// 
	pendingInbounds: [],
	floorInbounds: [],
	completedInbounds: [],
	cancelledInbounds: [],
	pendingInboundsLoading: true,
	floorInboundsLoading: false,
	completedInboundsLoading: false,
	cancelledInboundsLoading: false,
	pendingInboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	floorInboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	completedInboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	cancelledInboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	setCurrentTab: 0,
	newStorableUnitLoading: false,
	allLocations: [],
	allLocationsLoading: false,
	placeStorableUnitLoading: false,
	downloadDocLoading: false,
	uploadDocLoading: false,
	printOrder: null,
	printOrderLoading: false,
	searchedInbounds: [],
	searchedInboundsLoading: false,
	editStorableUnitLoading: false,
	isFetchFirstTime: true,
	placeMultipleStorableUnitLoading: false,
	allInboundProductsLists: [],
	allInboundProductsListsLoading: false,
	allInboundProductListsDropdownData: [],
	poBaseUrlState: process.env.VUE_APP_PO_URL,
	// filter and search
	filteredInboundsLoading:false,
	filteredInbounds: [],
	// 3pl
	receiveProduct3plLoading: false,
	receiveProductMultiple3plLoading: false,
	completeInboundOrderLoading: false,
	isShowCreateInboundDialog: false,
	// 
	allWarehouseCustomerInboundProductsLists: [],
	allWarehouseCustomerInboundProductsListsLoading: false,
	previousSelectedWarehouseID: null,
	draftInboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	draftInboundPaginationLoading: false,
	pendingReceiveInboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	pendingReceiveInboundPaginationLoading: false,
	pendingReceiveInboundPaginationWHCustomer: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	pendingReceiveInboundPaginationWHCustomerLoading: false,
	deleteInboundLoading: false,
	submitInboundLoading: false,
	acceptInboundLoading: false,
	rejectInboundLoading: false
}

const getters = {
	getInboundInventories: state => state.inboundInventories,
	getInboundInventoriesLoading: state => state.inboundInventoriesLoading,
	getInboundCreateLoading: state => state.inboundCreateLoading,
	getSingleInbound: state => state.singleInbound,
	getSingleInboundLoading: state => state.singleInboundLoading,
	getInboundUpdateLoading: state => state.inboundUpdateLoading,
	getCancelInboundLoading: state => state.cancelInboundLoading,
	getTruckArrivedLoading: state => state.truckArrivedLoading,
	getReceiveProductLoading: state => state.receiveProductLoading,
	getReceiveProductMultipleLoading: state => state.receiveProductMultipleLoading,
	// 
	getPendingInbounds: state => state.pendingInbounds,
	getFloorInbounds: state => state.floorInbounds,
	getCompletedInbounds: state => state.completedInbounds,
	getCancelledInbounds: state => state.cancelledInbounds,
	getPendingInboundsLoading: state => state.pendingInboundsLoading,
	getFloorInboundsLoading: state => state.floorInboundsLoading,
	getCompletedInboundsLoading: state => state.completedInboundsLoading,
	getCancelledInboundsLoading: state => state.cancelledInboundsLoading,
	// pagination
	getPendingInboundPagination: state => state.pendingInboundPagination,
	getFloorInboundPagination: state => state.floorInboundPagination,
	getCompletedInboundPagination: state => state.completedInboundPagination,
	getCancelledInboundPagination: state => state.cancelledInboundPagination,
	getCurrentInboundTab: state => state.setCurrentTab,
	getNewStorableUnitLoading: state => state.newStorableUnitLoading,
	getAllWarehouseLocations: state => state.allLocations,
	getAllWarehouseLocationsLoading: state => state.allLocationsLoading,
	getPlaceStorableLoading: state => state.placeStorableUnitLoading,
	getPlaceMultipleStorableLoading: state => state.placeMultipleStorableUnitLoading,
	getDownloadDocLoading: state => state.downloadDocLoading,
	getUploadDocLoading: state => state.uploadDocLoading,
	getPrintOrderData: state => state.printOrder,
	getPrintOrderDataLoading: state => state.printOrderLoading,
	getSearchedInbounds: state => state.searchedInbounds,
	getSearchedInboundsLoading: state => state.searchedInboundsLoading,
	getEditStorableUnitLoading: state => state.editStorableUnitLoading,
	getIsFetchFirstTime: state => state.isFetchFirstTime,
	getAllInboundProductsLists: state => state.allInboundProductsLists,
	getAllInboundProductsListsDropdownData: state => state.allInboundProductListsDropdownData,
	// 3pl
	getReceiveProductLoading3pl: state => state.receiveProduct3plLoading,
	getReceiveProductMultipleLoading3pl: state => state.receiveProductMultiple3plLoading,
	getCompleteInboundOrderLoading: state => state.completeInboundOrderLoading,
	getIsShowCreateInboundDialog: state => state.isShowCreateInboundDialog,
	// 
	getAllWarehouseCustomerInboundProductsLists: state => state.allWarehouseCustomerInboundProductsLists,
	getAllWarehouseCustomerInboundProductsListsLoading:state => state.allWarehouseCustomerInboundProductsListsLoading,
	getPreviousSelectedCustomerWarehouseID: state => state.previousSelectedWarehouseID,
	// search and filter
	getfilteredInboundsLoading: state => state.filteredInboundsLoading,
	getfilteredInbounds: state => state.filteredInbounds,
	// connected warehouses
	getDraftInboundPagination: state => state.draftInboundPagination,
	getDraftInboundPaginationLoading: state => state.draftInboundPaginationLoading,
	getPendingReceiveInboundPagination: state => state.pendingReceiveInboundPagination,
	getPendingReceiveInboundLoading: state => state.pendingReceiveInboundPaginationLoading,
	getPendingReceiveInboundPaginationWHCustomer: state => state.pendingReceiveInboundPaginationWHCustomer,
	getPendingReceiveInboundLoadingWHCustomer: state => state.pendingReceiveInboundPaginationWHCustomerLoading,
	getDeleteInboundLoading: state => state.deleteInboundLoading,
	getSubmitInboundLoading: state => state.submitInboundLoading,
	getAcceptInboundLoading: state => state.acceptInboundLoading,
	getRejectInboundLoading: state => state.rejectInboundLoading
}

const poBaseUrl = process.env.VUE_APP_PO_URL 

const actions = {
	fetchInboundInventories: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_INBOUND_INVENTORIES_LOADING", true)
				commit("SET_INBOUND_INVENTORIES", [])
				axios.get(`${poBaseUrl}/warehouses/${payload.wid}/inbounds?page=${payload.page}`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_INBOUND_INVENTORIES_LOADING", false)
								commit("SET_INBOUND_INVENTORIES", res.data)
							}
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
							}, 300)
						} else {
							commit('SET_INBOUND_INVENTORIES_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_INBOUND_INVENTORIES_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
		
	},
	createInbound: async ({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false
			
			function proceed() {
				commit("SET_CREATE_INBOUND_LOADING", true)
				let { warehouse_id, documents, ...otherProps } = payload
				// let { warehouse_id, documents, ...otherProps } = payload
				payload = { ...otherProps }

				// console.log(documents);

				// axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/inbounds/create`, payload)
				// .then(res => {
				// 	commit("SET_CREATE_INBOUND_LOADING", false)
				// 	if (typeof res!=='undefined' && res.status === 200) {
				// 		resolve(res.data)
				// 	}
				// })
				// .catch(err => {
				// 	commit("SET_CREATE_INBOUND_LOADING", false)
				// 	if (typeof err.error !== 'undefined') {
				// 		reject(err.error)
				// 	} else {
				// 		reject(err)
				// 	}
				// })
							
				// with documents
				if (documents.length > 0) {
					let bodyFormData = new FormData()
					let getPayloadKeys = Object.keys(payload)

					if (getPayloadKeys.length > 0) {
						getPayloadKeys.map(gpk => {
							bodyFormData.append(gpk, payload[gpk])
						})
					}

					for (var i = 0; i < documents.length; i++) {
						bodyFormData.append('documents[]', documents[i]);
					}

					// if (estimated_date !== null) {
					// 	bodyFormData.append('estimated_date', estimated_date);
					// }

					// if (estimated_time !== null) {
					// 	bodyFormData.append('estimated_time', estimated_time);
					// }

					axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/inbounds/create`, bodyFormData, {
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					})
					.then(res => {
						commit("SET_CREATE_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
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
								commit('SET_CREATE_INBOUND_LOADING', false)
								// reject('Token has been expired. Please reload the page.')
								reject(err)
							}
						}

						if (typeof err.error !=='undefined') {
							commit('SET_CREATE_INBOUND_LOADING', false)
							reject(err.error)
						}
					})
				} else {
					axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/inbounds/create`, payload)
					.then(res => {
						commit("SET_CREATE_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
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
								commit('SET_CREATE_INBOUND_LOADING', false)
								// reject('Token has been expired. Please reload the page.')
								reject(err)
							}
						}

						if (typeof err.error !=='undefined') {
							commit('SET_CREATE_INBOUND_LOADING', false)
							reject(err.error)
						}
					})
				}	
			}
			proceed()
			
		})
	},
	updateInbound: async ({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false

			function proceed() {
				commit("SET_UPDATE_INBOUND_LOADING", true)
				
				let { warehouse_id, inbound_id, documents, ...otherProps } = payload
				payload = { ...otherProps }

				//  with documents
				if (documents.length > 0) {
					let bodyFormData = new FormData()
					let getPayloadKeys = Object.keys(payload)
						
					if (getPayloadKeys.length > 0) {
						getPayloadKeys.map(gpk => {
							bodyFormData.append(gpk, payload[gpk])
						})
					}

					for (var i = 0; i < documents.length; i++) {
						bodyFormData.append(`documents[${[i]}]`, documents[i]);
					}				

					axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/inbounds/update/${inbound_id}`, bodyFormData, {
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					})
					.then(res => {
						commit("SET_UPDATE_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {

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
								commit('SET_UPDATE_INBOUND_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !=='undefined') {
							commit('SET_UPDATE_INBOUND_LOADING', false)
							reject(err.error)
						}
					})
				} else {
					payload.documents = []
					axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/inbounds/update/${inbound_id}`, payload)
					.then(res => {
						commit("SET_UPDATE_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
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
								commit('SET_UPDATE_INBOUND_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !=='undefined') {
							commit('SET_UPDATE_INBOUND_LOADING', false)
							reject(err.error)
						}
					})
				}
			}
			
			proceed()
		})		
			
	},
	fetchSingleInbound: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_SINGLE_INBOUND_LOADING", true)

				axios.get(`${poBaseUrl}/warehouses/${payload.wid}/inbounds/${payload.iid}`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_SINGLE_INBOUND_LOADING", false)
								commit("SET_SINGLE_INBOUND", res.data)
							}
						}
					}
					resolve()
				})
				.catch(err => {
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
							commit('SET_SINGLE_INBOUND_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_SINGLE_INBOUND_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	cancelInbound: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_CANCEL_INBOUND_LOADING", true)
				axios.get(`${poBaseUrl}/warehouses/${payload.wid}/inbounds/cancel/${payload.oid}`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_CANCEL_INBOUND_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_CANCEL_INBOUND_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err.message)

							// if (err.message === 'UnAuthenticated') {
							// 	reject('Token has been expired. Please reload the page.')
							// } else {
							// 	reject(err.message)
							// }
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_CANCEL_INBOUND_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
		
	},
	truckArrived: async ({
		commit
	}, payload) => {
		
		let attempt = false
		return new Promise((resolve, reject) => {
		
			function proceed() {
				commit("SET_TRUCK_ARRIVED_LOADING", true)
				let { warehouse_id, inbound_id } = payload

				// axios.put(`${poBaseUrl}/warehouses/${warehouse_id}/inbounds/truck-arrived/${inbound_id}`, payload)
				axios.get(`${poBaseUrl}/warehouse/${warehouse_id}/inbound/${inbound_id}/truck-arrived-confirm`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_TRUCK_ARRIVED_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_TRUCK_ARRIVED_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_TRUCK_ARRIVED_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	receiveProduct: async ({
		commit
	}, payload) => {
		let { warehouse_id, inbound_id, inbound_product_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_RECEIVE_PRODUCT_LOADING", true)

				axios.put(`${poBaseUrl}/warehouses/${warehouse_id}/inbounds/${inbound_id}/recieve-inbound-products/${inbound_product_id}`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_RECEIVE_PRODUCT_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_RECEIVE_PRODUCT_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_RECEIVE_PRODUCT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	receiveProductMultiple: async ({
		commit
	}, payload) => {
		let { warehouse_id, inbound_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_RECEIVE_PRODUCT_MULTIPLE_LOADING", true)

				axios.put(`${poBaseUrl}/warehouse/${warehouse_id}/inbound/${inbound_id}/recieve-multiple-inbound-products`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_RECEIVE_PRODUCT_MULTIPLE_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_RECEIVE_PRODUCT_MULTIPLE_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_RECEIVE_PRODUCT_MULTIPLE_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	setCurrentInboundTab( { commit }, payload) {
        commit("setCurrentInboundTab", payload)
    },
	// inbound types fetching
	fetchPendingInbounds: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_PENDING_INBOUNDS_LOADING", true)
				let passedData = {
					url: `${poBaseUrl}/warehouse/${payload.id}/inbound/common`,
					method: 'GET',
					params: {
						page: payload.page,
						status: [],
					},
					cancelToken: payload.cancelToken
				}

				if (typeof warehouse.default.state !== 'undefined' && warehouse.default.state.currentWarehouse !== null) {
					let currentWarehouse = warehouse.default.state.currentWarehouse

					if (currentWarehouse.warehouse_type_id === 1) {
						passedData.params.status = ['pending', 'arrived']
					} else {
						passedData.params.status = ['pending']

						if (currentWarehouse.is_own === 0) {
							passedData.params.ids = [`${currentWarehouse.warehouse_customer_id}`]
							passedData.params.filter = true
						}
					}
				}

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/inbound/pending?page=${payload.page}`)
				axios(passedData)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_PENDING_INBOUNDS_LOADING", false)
								commit("SET_PENDING_INBOUNDS", results)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									old_page: 1,
									last_page: results.last_page,
									per_page: results.per_page,
									data: results.data
								}

								commit("SET_PENDING_INBOUND_PAGINATION", pagination)
							}

							resolve()
						}
					}
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
							}, 300)
						} else {
							commit('SET_PENDING_INBOUNDS_LOADING', false)
							if (err.message !== "cancel_previous_request") reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PENDING_INBOUNDS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	fetchFloorInbounds: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_FLOOR_INBOUNDS_LOADING", true)
				let passedData = {
					url: `${poBaseUrl}/warehouse/${payload.id}/inbound/common`,
					method: 'GET',
					params: {
						page: payload.page,
						status: ['floor']
					},
					cancelToken: payload.cancelToken
				}

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/inbound/floor?page=${payload.page}`)
				axios(passedData)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_FLOOR_INBOUNDS_LOADING", false)
								commit("SET_FLOOR_INBOUNDS", results)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									last_page: results.last_page,
									per_page: results.per_page,
									data: results.data
								}

								commit("SET_FLOOR_INBOUND_PAGINATION", pagination)
							}

							resolve()
						}
					}
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
							}, 300)
						} else {
							commit('SET_FLOOR_INBOUNDS_LOADING', false)
							// reject(err.message)
							if (err.message !== "cancel_previous_request") reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_FLOOR_INBOUNDS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	fetchCompletedInbounds: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_COMPLETED_INBOUNDS_LOADING", true)
				let passedData = {
					url: `${poBaseUrl}/warehouse/${payload.id}/inbound/common`,
					method: 'GET',
					params: {
						page: payload.page,
						status: ['completed']
					},
					cancelToken: payload.cancelToken
				}

				if (typeof warehouse.default.state !== 'undefined' && warehouse.default.state.currentWarehouse !== null) {
					let currentWarehouse = warehouse.default.state.currentWarehouse
					if (currentWarehouse.is_own === 0) {
						passedData.params.ids = [`${currentWarehouse.warehouse_customer_id}`]
						passedData.params.filter = true
					}
				}

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/inbound/completed?page=${payload.page}`)
				axios(passedData)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_COMPLETED_INBOUNDS_LOADING", false)
								commit("SET_COMPLETED_INBOUNDS", results)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									last_page: results.last_page,
									per_page: results.per_page,
									data: results.data
								}

								commit("SET_COMPLETED_INBOUND_PAGINATION", pagination)
							}

							resolve()
						}
					}
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
							}, 300)
						} else {
							commit('SET_COMPLETED_INBOUNDS_LOADING', false)
							// reject(err.message)
							if (err.message !== "cancel_previous_request") reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_COMPLETED_INBOUNDS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	fetchCancelledInbounds: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_CANCELLED_INBOUNDS_LOADING", true)
				let passedData = {
					url: `${poBaseUrl}/warehouse/${payload.id}/inbound/common`,
					method: 'GET',
					params: {
						page: payload.page,
						status: []
					},
					cancelToken: payload.cancelToken
				}

				if (typeof warehouse.default.state !== 'undefined' && warehouse.default.state.currentWarehouse !== null) {
					let currentWarehouse = warehouse.default.state.currentWarehouse

					if (currentWarehouse.warehouse_type_id === 6) {
						passedData.params.status = ['cancelled', 'rejected']

						if (currentWarehouse.is_own === 0) {
							passedData.params.ids = [`${currentWarehouse.warehouse_customer_id}`]
							passedData.params.filter = true
						}
					} else {
						passedData.params.status = ['cancelled']
					}
				}

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/inbound/cancelled?page=${payload.page}`)
				axios(passedData)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_CANCELLED_INBOUNDS_LOADING", false)
								commit("SET_CANCELLED_INBOUNDS", results)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									last_page: results.last_page,
									per_page: results.per_page,
									data: results.data
								}

								commit("SET_CANCELLED_INBOUND_PAGINATION", pagination)
							}

							resolve()
						}
					}
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
							}, 300)
						} else {
							commit('SET_CANCELLED_INBOUNDS_LOADING', false)
							// reject(err.message)
							if (err.message !== "cancel_previous_request") reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_CANCELLED_INBOUNDS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},	
	// inbound types fetching for connected 3pl
	fetchDraftInbounds: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_DRAFT_INBOUNDS_LOADING", true)
				let passedData = {
					url: `${poBaseUrl}/warehouse/${payload.id}/inbound/common`,
					method: 'GET',
					params: {
						page: payload.page,
						status: ['draft'],
						filter: true
					},
					cancelToken: payload.cancelToken
				}

				if (typeof warehouse.default.state !== 'undefined' && warehouse.default.state.currentWarehouse !== null) {
					let currentWarehouse = warehouse.default.state.currentWarehouse
					if (currentWarehouse.is_own === 0) {
						passedData.params.ids = [`${currentWarehouse.warehouse_customer_id}`]
					}
				}

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/inbound/draft?page=${payload.page}`)
				axios(passedData)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_DRAFT_INBOUNDS_LOADING", false)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									old_page: 1,
									last_page: results.last_page,
									per_page: results.per_page,
									data: results.data
								}

								commit("SET_DRAFT_INBOUND_PAGINATION", pagination)
							}

							resolve()
						}
					}
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
							}, 300)
						} else {
							commit('SET_DRAFT_INBOUNDS_LOADING', false)
							// reject(err.message)
							if (err.message !== "cancel_previous_request") reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_DRAFT_INBOUNDS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	fetchPendingReceivingInbounds: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_PENDING_RECEIVING_INBOUNDS_LOADING", true)
				let passedData = {
					url: `${poBaseUrl}/warehouse/${payload.id}/inbound/common`,
					method: 'GET',
					params: {
						page: payload.page,
						status: ['receive-pending', 'arrived']
					},
					cancelToken: payload.cancelToken
				}

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/inbound/receive-pending?page=${payload.page}`)
				axios(passedData)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_PENDING_RECEIVING_INBOUNDS_LOADING", false)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									old_page: 1,
									last_page: results.last_page,
									per_page: results.per_page,
									data: results.data
								}

								commit("SET_PENDING_RECEIVING_INBOUND_PAGINATION", pagination)
							}

							resolve()
						}
					}
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
							}, 300)
						} else {
							commit('SET_PENDING_RECEIVING_INBOUNDS_LOADING', false)
							// reject(err.message)
							if (err.message !== "cancel_previous_request") reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PENDING_RECEIVING_INBOUNDS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	fetchPendingReceivingInboundsForWHCustomer: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_PENDING_RECEIVING_INBOUNDS_WH_CUSTOMER_LOADING", true)
				let passedData = {
					url: `${poBaseUrl}/warehouse/${payload.id}/inbound/common`,
					method: 'GET',
					params: {
						page: payload.page,
						status: ['receive-pending', 'arrived', 'floor'],
						filter: true
					},
					cancelToken: payload.cancelToken
				}

				if (typeof warehouse.default.state !== 'undefined' && warehouse.default.state.currentWarehouse !== null) {
					let currentWarehouse = warehouse.default.state.currentWarehouse
					if (currentWarehouse.is_own === 0) {
						passedData.params.ids = [`${currentWarehouse.warehouse_customer_id}`]
					}
				}

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/inbound/receive-pending-for-warehouse-customer?page=${payload.page}`)
				axios(passedData)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_PENDING_RECEIVING_INBOUNDS_WH_CUSTOMER_LOADING", false)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									old_page: 1,
									last_page: results.last_page,
									per_page: results.per_page,
									data: results.data
								}

								commit("SET_PENDING_RECEIVING_INBOUND_PAGINATION_WH_CUSTOMER", pagination)
							}

							resolve()
						}
					}
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
							}, 300)
						} else {
							commit('SET_PENDING_RECEIVING_INBOUNDS_WH_CUSTOMER_LOADING', false)
							// reject(err.message)
							if (err.message !== "cancel_previous_request") reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PENDING_RECEIVING_INBOUNDS_WH_CUSTOMER_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	// 
	newStorableUnit: async ({
		commit
	}, payload) => {
		let { warehouse_id, inbound_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_NEW_STORABLE_UNIT_LOADING", true)

				axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/inbounds/${inbound_id}/new-storable-unit`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_NEW_STORABLE_UNIT_LOADING", false)
							}
						}
					}
					resolve(res.data.data.label)
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
							}, 300)
						} else {
							commit('SET_NEW_STORABLE_UNIT_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_NEW_STORABLE_UNIT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	updateStorableUnit: async ({
		commit
	}, payload) => {
		let { warehouse_id, inbound_id, storable_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_EDIT_STORABLE_UNIT_LOADING", true)

				axios.post(`${poBaseUrl}/warehouse/${warehouse_id}/inbound/${inbound_id}/edit-storable-unit/${storable_id}`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_EDIT_STORABLE_UNIT_LOADING", false)
							}
						}
					}
					resolve()
				})
				.catch(err => {
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
							commit('SET_EDIT_STORABLE_UNIT_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_EDIT_STORABLE_UNIT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	getPlaceStorableLocations: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_WAREHOUSE_LOCATIONS_DETAILS_LOADING", true)

				let passedData = {
					url: ``,
					method: 'GET',
					params: {
						page: payload.page,
						per_page: payload.per_page
					}
				}

				if (payload.type === 'own') {
					passedData.url = `${poBaseUrl}/warehouse/${payload.warehouse_id}/inbound-place-locations`
				} else {
					passedData.url = `${poBaseUrl}/warehouse/${payload.warehouse_id}/warehouse-customer/${payload.warehouse_customer_id}/inbound-place-locations`
				}

				axios(passedData)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_WAREHOUSE_LOCATIONS_DETAILS_LOADING", false)
								commit("SET_WAREHOUSE_LOCATIONS_DETAILS", res.data)
							}
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if ( !store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {
							commit('SET_WAREHOUSE_LOCATIONS_DETAILS_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_WAREHOUSE_LOCATIONS_DETAILS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	setPlaceStorableUnit: async ({
		commit
	}, payload) => {
		let { warehouse_id, inbound_id, storable_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_PLACE_STORABLE_UNIT_LOADING", true)

				axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/inbounds/${inbound_id}/place-storable-unit/${storable_id}`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_PLACE_STORABLE_UNIT_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_PLACE_STORABLE_UNIT_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PLACE_STORABLE_UNIT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
		
	},
	setPlaceMultipleStorableUnit: async ({
		commit
	}, payload) => {
		let { warehouse_id, inbound_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_PLACE_MULTIPLE_STORABLE_UNIT_LOADING", true)

				axios.post(`${poBaseUrl}/warehouse/${warehouse_id}/inbound/${inbound_id}/place-multiple-storable-units`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_PLACE_MULTIPLE_STORABLE_UNIT_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_PLACE_MULTIPLE_STORABLE_UNIT_LOADING', false)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PLACE_MULTIPLE_STORABLE_UNIT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
		
	},
	downloadInboundDoc: async({commit}, item) => {
		let attempt = false
		commit('SET_DOWNLOAD_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios({
					url: `${poBaseUrl}/warehouses/${item.warehouse_id}/inbounds/${item.inbound_id}/download-document/${item.id}`,
					method: 'GET',
					responseType: 'blob'
				}).then(response => {
					commit('SET_DOWNLOAD_LOADING', false)
					let fileURL = window.URL.createObjectURL(new Blob([response.data]))
					let fileLink = document.createElement('a')
					fileLink.href = fileURL
					fileLink.setAttribute('download', `${(typeof item.original_name!=='undefined') ? item.original_name : ''}.pdf`)
					fileLink.click()
					resolve('ok')

				})
				.catch( err => {
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
							commit("SET_DOWNLOAD_LOADING", false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_DOWNLOAD_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	uploadInboundDocs: async ({
		commit
	}, payload) => {
		console.log(commit, 'commit');
		console.log(payload, 'payload');
		console.log(payload.event, 'event');

		// return new Promise((resolve, reject) => {
		// 	let attempt = false
			
		// 	function proceed() {
		// 		commit("SET_UPLOAD_INBOUND_DOC_LOADING", true)
		// 		let { warehouse_id, inbound_id, files, ...otherProps } = payload
		// 		payload = { ...otherProps }
							
		// 		let bodyFormData = new FormData()
		// 		let getPayloadKeys = Object.keys(payload)

		// 		if (getPayloadKeys.length > 0) {
		// 			getPayloadKeys.map(gpk => {
		// 				bodyFormData.append(gpk, payload[gpk])
		// 			})
		// 		}

		// 		for (var i = 0; i < files.length; i++) {
		// 			bodyFormData.append('upload_documents[]', files[i]);
		// 		}

		// 		axios.post(`${poBaseUrl}/warehouse/${warehouse_id}/inbound/${inbound_id}/upload-documents`, bodyFormData, {
		// 			headers: {
		// 				'Content-Type': 'multipart/form-data'
		// 			},
		// 			onUploadProgress
		// 		})
		// 		.then(res => {
		// 			commit("SET_UPLOAD_INBOUND_DOC_LOADING", false)
		// 			if (typeof res!=='undefined' && res.status === 200) {
		// 				resolve(res.data)
		// 			}
		// 		})
		// 		.catch(err => {
		// 			if (typeof err.message!=='undefined') {
		// 				if (!attempt) {
		// 					attempt = true
		// 					let t = setInterval(() => {
		// 						if (!store.getters.getIsRefreshing) {
		// 							proceed()
		// 							clearInterval(t)
		// 						}
		// 					}, 300)
		// 				} else {
		// 					commit('SET_UPLOAD_INBOUND_DOC_LOADING', false)
		// 					reject('Token has been expired. Please reload the page.')
		// 				}
		// 			}

		// 			if (typeof err.error !=='undefined') {
		// 				commit('SET_UPLOAD_INBOUND_DOC_LOADING', false)
		// 				reject(err.error)
		// 			}
		// 		})
		// 	}
		// 	proceed()
			
		// })
	},
	printInboundOrder: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_PRINT_ORDER_LOADING", true)

				axios({
					url: `${poBaseUrl}/warehouse/${payload.warehouse_id}/inbound/${payload.inbound_id}/print-order`,
					method: 'GET',
					responseType: 'blob'
				}).then(response => {
					commit('SET_PRINT_ORDER_LOADING', false)
					var newBlob = new Blob([response.data], { type: 'application/pdf' })

					if (window.navigator && window.navigator.msSaveOrOpenBlob) {
						window.navigator.msSaveOrOpenBlob(newBlob, `Inbound-${payload.order_id}`)
						return
					}
					const data = window.URL.createObjectURL(newBlob)
					var mywindow = window.open(data, "_blank");	 mywindow.print();
					// var link = document.createElement('a')
					// link.href = data
					// link.target = '_blank'
					// link.click()
					// setTimeout(function(){
					// 	console.log("link",link)
					// 	window.URL.revokeObjectURL(data);
					// }, 100);
					resolve('ok')
				}).catch(err => {
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
							commit('SET_PRINT_ORDER_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PRINT_ORDER_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},	
	setEmptyInventoryInboundData( { commit }, payload) {
        commit("setEmptyInventoryInboundData", payload)
    },
	setSearchedInboundLoading({ commit } , payload) {
		commit("SET_SEARCHED_INBOUND_LOADING", payload)
	},
    setInboundSearchedVal({ commit }, payload) {
		commit("setInboundSearchedVal", payload)
	},
	fetchSearchedInbounds: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_SEARCHED_INBOUND_LOADING", true)

				axios(payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_SEARCHED_INBOUND_LOADING", false)
								commit("SET_SEARCHED_INBOUND", results)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									old_page: results.current_page,
									last_page: results.last_page,
									per_page: results.per_page,
									tab: payload.tab,
									data: results.data
								}

								commit("SET_SEARCHED_INBOUND", pagination)
							}

							resolve()
						}
					}
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
							}, 300)
						} else {
							commit('SET_SEARCHED_INBOUND_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_SEARCHED_INBOUND_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	setSingleInboundData({ commit }, payload) {
		commit("SET_SINGLE_INBOUND", payload)
	},
	setIsFirstTimeFetching( { commit }, payload) {
        commit("setIsFirstTimeFetching", payload)
    },
	setAllInboundProductsLists( { commit }, payload) {
        commit("SET_ALL_PRODUCTS_INBOUND_LISTS_DROPDOWN_DATA", payload)
    },
	// fetch Inbound Products
	fetchInboundProducts: async ({
		commit, state
	}, page) => {
		const { poBaseUrlState } = state
		commit("SET_ALL_PRODUCTS_INBOUND_LISTS_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {			
			function proceed() {
				let product = new ProductModel(poBaseUrlState)
				product.getProductsDropdown(page).then(res => {
					commit("SET_ALL_PRODUCTS_INBOUND_LISTS_LOADING", false)
					if (res.status === 200) {
						let finalData = {
							products: [],
							current_page: 1,
							total: 0,
							last_page: 0,
							old_page: 1,
							per_page: 35
						}

						if (typeof res.data!=='undefined') {
							let productsData = []							

							if (typeof res.data.results!=='undefined' && typeof res.data.results.data!=='undefined')
								productsData = res.data.results.data
							
							if(productsData.length > 0){
								const tempProductsData = productsData.map(product => {
									let tempCartonDimensions = {
										l: 0,
										w: 0,
										h: 0,
										uom: 'cm'
									}
									
									if(product.carton_dimensions){
										tempCartonDimensions = {...JSON.parse(product.carton_dimensions)}
										tempCartonDimensions.l = tempCartonDimensions.l ? tempCartonDimensions.l : tempCartonDimensions.length 
										tempCartonDimensions.h = tempCartonDimensions.h ? tempCartonDimensions.h : tempCartonDimensions.height
										tempCartonDimensions.w = tempCartonDimensions.w ? tempCartonDimensions.w : tempCartonDimensions.width
									}

									let tempUnitDimensions = {
										l: 0,
										w: 0,
										h: 0,
										uom: 'cm'
									}

									if (product.unit_dimensions){
										tempUnitDimensions = {...JSON.parse(product.unit_dimensions)}
									}

									let tempUnitWeight = {
										value: 0,
										unit: 'kg'
									}

									if (product.unit_weight){
										tempUnitWeight = {...JSON.parse(product.unit_weight)}
									}

									return {
										...product,
										carton_dimensions: {
											l: tempCartonDimensions.l,
											w: tempCartonDimensions.w,
											h: tempCartonDimensions.h,
											uom: tempCartonDimensions.uom
										},
										unit_dimensions: {
											l: tempUnitDimensions.l,
											w: tempUnitDimensions.w,
											h: tempUnitDimensions.h,
											uom: tempUnitDimensions.uom
										},
										unit_weight: {
											value: tempUnitWeight.value,
											unit: tempUnitWeight.unit
										}
									}
								})
								
								productsData = tempProductsData
							}

							finalData = {
								products: productsData,
								current_page: res.data.results.current_page,
								total: res.data.results.total,
								old_page: res.data.results.current_page,
								last_page: res.data.results.last_page,
								per_page: res.data.results.per_page
							}

							commit("SET_ALL_PRODUCTS_INBOUND_LISTS", finalData)

						} else {
							commit("SET_ALL_PRODUCTS_INBOUND_LISTS", finalData)
						}

						resolve()
					}
				}).catch(err => {
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
							commit('SET_ALL_PRODUCTS_INBOUND_LISTS_LOADING', false)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_ALL_PRODUCTS_INBOUND_LISTS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	// 3PL API's
	receiveProduct3pl: async ({
		commit
	}, payload) => {
		let { warehouse_id, inbound_id, inbound_product_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_RECEIVE_PRODUCT_3PL_LOADING", true)

				axios.put(`${poBaseUrl}/warehouse-3pl/${warehouse_id}/inbound/${inbound_id}/recieve-inbound-product/${inbound_product_id}`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_RECEIVE_PRODUCT_3PL_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_RECEIVE_PRODUCT_3PL_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_RECEIVE_PRODUCT_3PL_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	receiveProductMultiple3pl: async ({
		commit
	}, payload) => {
		let { warehouse_id, inbound_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_RECEIVE_PRODUCT_MULTIPLE_3PL_LOADING", true)

				axios.put(`${poBaseUrl}/warehouse-3pl/${warehouse_id}/inbound/${inbound_id}/recieve-multiple-inbound-products`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_RECEIVE_PRODUCT_MULTIPLE_3PL_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_RECEIVE_PRODUCT_MULTIPLE_3PL_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_RECEIVE_PRODUCT_MULTIPLE_3PL_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	completeInboundOrder: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("COMPLETE_ORDER_3PL_LOADING", true)

				// axios.get(`${poBaseUrl}/warehouse-3pl/${payload.id}/inbound/${payload.inbound_id}/complete-order`)
				axios.get(`${poBaseUrl}/warehouse/${payload.id}/inbound/${payload.inbound_id}/complete-order`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("COMPLETE_ORDER_3PL_LOADING", false)
							}
							resolve()
						}
					}
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
							}, 300)
						} else {
							commit('COMPLETE_ORDER_3PL_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('COMPLETE_ORDER_3PL_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	updateInboundFor3PL: async ({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false

			function proceed() {
				commit("SET_UPDATE_INBOUND_LOADING", true)
				
				let { warehouse_id, inbound_id, documents, ...otherProps } = payload
				payload = { ...otherProps }

				//  with documents
				if (documents.length > 0) {
					let bodyFormData = new FormData()
					let getPayloadKeys = Object.keys(payload)
						
					if (getPayloadKeys.length > 0) {
						getPayloadKeys.map(gpk => {
							bodyFormData.append(gpk, payload[gpk])
						})
					}

					for (var i = 0; i < documents.length; i++) {
						bodyFormData.append(`documents[${[i]}]`, documents[i]);
					}				

					axios.post(`${poBaseUrl}/warehouse-3pl/${warehouse_id}/inbound/update/${inbound_id}`, bodyFormData, {
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					})
					.then(res => {
						commit("SET_UPDATE_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {

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
								commit('SET_UPDATE_INBOUND_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !=='undefined') {
							commit('SET_UPDATE_INBOUND_LOADING', false)
							reject(err.error)
						}
					})
				} else {
					payload.documents = []
					axios.post(`${poBaseUrl}/warehouse-3pl/${warehouse_id}/inbound/update/${inbound_id}`, payload)
					.then(res => {
						commit("SET_UPDATE_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
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
								commit('SET_UPDATE_INBOUND_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !=='undefined') {
							commit('SET_UPDATE_INBOUND_LOADING', false)
							reject(err.error)
						}
					})
				}
			}
			
			proceed()
		})		
			
	},
	updateInboundFor3PLAddInventory: async ({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false

			function proceed() {
				commit("SET_UPDATE_INBOUND_LOADING", true)
				
				let { warehouse_id, inbound_id, documents, ...otherProps } = payload
				payload = { ...otherProps }

				//  with documents
				if (documents.length > 0) {
					let bodyFormData = new FormData()
					let getPayloadKeys = Object.keys(payload)
						
					if (getPayloadKeys.length > 0) {
						getPayloadKeys.map(gpk => {
							bodyFormData.append(gpk, payload[gpk])
						})
					}

					for (var i = 0; i < documents.length; i++) {
						bodyFormData.append(`documents[${[i]}]`, documents[i]);
					}				

					axios.post(`${poBaseUrl}/warehouse-3pl/${warehouse_id}/add-inventory-inbound/update/${inbound_id}`, bodyFormData, {
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					})
					.then(res => {
						commit("SET_UPDATE_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
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
								commit('SET_UPDATE_INBOUND_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !=='undefined') {
							commit('SET_UPDATE_INBOUND_LOADING', false)
							reject(err.error)
						}
					})
				} else {
					payload.documents = []
					axios.post(`${poBaseUrl}/warehouse-3pl/${warehouse_id}/add-inventory-inbound/update/${inbound_id}`, payload)
					.then(res => {
						commit("SET_UPDATE_INBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
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
								commit('SET_UPDATE_INBOUND_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !=='undefined') {
							commit('SET_UPDATE_INBOUND_LOADING', false)
							reject(err.error)
						}
					})
				}
			}
			
			proceed()
		})		
			
	},
	setIsCreateInboundShow({ commit }, payload) {
		commit("setIsCreateInboundShow", payload)
	},
	// fetch Inbound Products for Warehouse Customer
	fetchWarehouseCustomerInboundProducts: async ({
		commit, state
	}, payload) => {
		const { poBaseUrlState } = state
		commit("SET_ALL_WAREHOUSE_PRODUCTS_INBOUND_LISTS_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {			
			function proceed() {
				let product = new ProductModel(poBaseUrlState)
				product.getProductsDropdown3PLProvider(payload).then(res => {
					commit("SET_ALL_WAREHOUSE_PRODUCTS_INBOUND_LISTS_LOADING", false)
					if (res.status === 200) {
						let finalData = {
							products: [],
							current_page: 1,
							total: 0,
							last_page: 0,
							old_page: 1,
							per_page: 35
						}

						if (typeof res.data!=='undefined') {
							let productsData = []

							if (typeof res.data.data!=='undefined' && typeof res.data.data.data!=='undefined')
								productsData = res.data.data.data
							
							if(productsData.length > 0){
								const tempProductsData = productsData.map(product => {
									let tempCartonDimensions = {
										l: 0,
										w: 0,
										h: 0,
										uom: 'cm'
									}
									
									if(product.carton_dimensions){
										tempCartonDimensions = {...JSON.parse(product.carton_dimensions)}
										tempCartonDimensions.l = tempCartonDimensions.l ? tempCartonDimensions.l : tempCartonDimensions.length 
										tempCartonDimensions.h = tempCartonDimensions.h ? tempCartonDimensions.h : tempCartonDimensions.height
										tempCartonDimensions.w = tempCartonDimensions.w ? tempCartonDimensions.w : tempCartonDimensions.width
									}

									let tempUnitDimensions = {
										l: 0,
										w: 0,
										h: 0,
										uom: 'cm'
									}

									if (product.unit_dimensions){
										tempUnitDimensions = {...JSON.parse(product.unit_dimensions)}
									}

									let tempUnitWeight = {
										value: 0,
										unit: 'kg'
									}

									if (product.unit_weight){
										tempUnitWeight = {...JSON.parse(product.unit_weight)}
									}

									return {
										...product,
										carton_dimensions: {
											l: tempCartonDimensions.l,
											w: tempCartonDimensions.w,
											h: tempCartonDimensions.h,
											uom: tempCartonDimensions.uom
										},
										unit_dimensions: {
											l: tempUnitDimensions.l,
											w: tempUnitDimensions.w,
											h: tempUnitDimensions.h,
											uom: tempUnitDimensions.uom
										},
										unit_weight: {
											value: tempUnitWeight.value,
											unit: tempUnitWeight.unit
										}
									}
								})
								
								productsData = tempProductsData
							}

							finalData = {
								products: productsData,
								current_page: res.data.data.current_page,
								total: res.data.data.total,
								old_page: res.data.data.current_page,
								last_page: res.data.data.last_page,
								per_page: res.data.data.per_page
							}

							commit("SET_ALL_WAREHOUSE_PRODUCTS_INBOUND_LISTS", finalData)

						} else {
							commit("SET_ALL_WAREHOUSE_PRODUCTS_INBOUND_LISTS", finalData)
						}

						resolve()
					}
				}).catch(err => {
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
							commit('SET_ALL_WAREHOUSE_PRODUCTS_INBOUND_LISTS_LOADING', false)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_ALL_PRODUCTS_INBOUND_LISTS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	setSelectedPrevCustomerWarehouseID({ commit }, payload) {
		commit("setSelectedPrevCustomerWarehouseID", payload)
	},
	// filter and seach 
	setFilteredInboundLoading({ commit }, payload) {
		commit("SET_FILTERED_INBOUND_LOADING", payload)
	},
	setInboundFilteredVal({ commit }, payload) {
		commit("setInboundFilteredVal", payload)
	},
	fetchFilterInboundsCustomers: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_FILTERED_INBOUND_LOADING", true)

				axios(payload)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									// console.log("filter res", res)
									let results = res.data.results
									commit("SET_FILTERED_INBOUND_LOADING", false)
									commit("SET_FILTERED_CUSTOMER_INBOUND", results)

									let pagination = {
										currentTab: 1,
										current_page: results.current_page,
										total: results.total,
										old_page: results.current_page,
										last_page: results.last_page,
										per_page: results.per_page,
										tab: payload.tab,
										data: results.data
									}

									commit("SET_FILTERED_CUSTOMER_INBOUND", pagination)
								}

								resolve()
							}
						}
					})
					.catch(err => {
						if (typeof err.message !== 'undefined') {
							if (!attempt) {
								attempt = true
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed()
										clearInterval(t)
									}
								}, 300)
							} else {
								commit('SET_FILTERED_INBOUND_LOADING', false)
								// reject('Token has been expired. Please reload the page.')
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_FILTERED_INBOUND_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	deleteInbound: async({
		commit
	}, payload) => {
		let attempt = false
		commit("SET_DELETE_INBOUND_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.delete(`${poBaseUrl}/warehouses/${payload.wid}/inbounds/delete/${payload.iid}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_DELETE_INBOUND_LOADING", false)
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
							commit("SET_DELETE_INBOUND_LOADING", false)
							reject(err.message)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_DELETE_INBOUND_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	submitInbound: async ({
		commit
	}, id) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_INBOUND_SUBMIT_LOADING", true)
				axios.get(`${poBaseUrl}/inbound/${id}/submit-inbound`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_INBOUND_SUBMIT_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_INBOUND_SUBMIT_LOADING', false)
							reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_INBOUND_SUBMIT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
		
	},
	acceptInbound: async ({
		commit
	}, id) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_INBOUND_ACCEPT_LOADING", true)
				axios.get(`${poBaseUrl}/inbound/${id}/accept-inbound`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_INBOUND_ACCEPT_LOADING", false)
							}
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
							}, 300)
						} else {
							commit('SET_INBOUND_ACCEPT_LOADING', false)
							reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_INBOUND_ACCEPT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
		
	},
	rejectInbound: async ({
        commit
    }, payload) => {
        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                let { id, ...otherProps } = payload

                payload = { ...otherProps }
                commit("SET_REJECT_INBOUND_LOADING", true)

                axios.put(`${poBaseUrl}/inbound/${id}/reject-inbound`, payload)
                .then(res => {
                    commit("SET_REJECT_INBOUND_LOADING", false)
                    if (typeof res !== 'undefined' && res.status === 200) {
                        resolve(res.data)
                    }
                })
                .catch(err => {
                    if (typeof err.message!=='undefined') {
                        if ( !attempt ){
                            attempt = true
                            let t = setInterval(() => {
                                if (!store.getters.getIsRefreshing) {
                                    proceed()
                                    clearInterval(t)
                                }
                            },300)
                        } else {
                            commit('SET_REJECT_INBOUND_LOADING', false)
                            reject(err.message)
                            console.log('Error is: ', err);
                        }
                    } else {
                        commit('SET_REJECT_INBOUND_LOADING', false)
                        reject(err.error)
                    }
                })
            }
            proceed()
            
        })
    },
}

const mutations = {
	SET_INBOUND_INVENTORIES: (state, payload) => {
		state.inboundInventories = payload
	},
	SET_INBOUND_INVENTORIES_LOADING: (state, payload) => {
		state.inboundInventoriesLoading = payload
	},
	SET_CREATE_INBOUND_LOADING: (state, payload) => {
		state.inboundCreateLoading = payload
	},
	SET_UPDATE_INBOUND_LOADING: (state, payload) => {
		state.inboundUpdateLoading = payload
	},
	SET_SINGLE_INBOUND: (state, payload) => {
		state.singleInbound = payload
	},
	SET_CANCEL_INBOUND_LOADING: (state, payload) => {
		state.cancelInboundLoading = payload
	},
	SET_SINGLE_INBOUND_LOADING: (state, payload) => {
		state.singleInboundLoading = payload
	},
	SET_TRUCK_ARRIVED_LOADING: (state, payload) => {
		state.truckArrivedLoading = payload
	},
	SET_RECEIVE_PRODUCT_LOADING: (state, payload) => {
		state.receiveProductLoading = payload
	},
	SET_RECEIVE_PRODUCT_MULTIPLE_LOADING: (state, payload) => {
		state.receiveProductMultipleLoading = payload
	},
	// 
	SET_PENDING_INBOUNDS: (state, payload) => {
		state.pendingInbounds = payload
	},
	SET_PENDING_INBOUNDS_LOADING: (state, payload) => {
		state.pendingInboundsLoading = payload
	},
	SET_FLOOR_INBOUNDS: (state, payload) => {
		state.floorInbounds = payload
	},
	SET_FLOOR_INBOUNDS_LOADING: (state, payload) => {
		state.floorInboundsLoading = payload
	},
	SET_COMPLETED_INBOUNDS: (state, payload) => {
		state.completedInbounds = payload
	},
	SET_COMPLETED_INBOUNDS_LOADING: (state, payload) => {
		state.completedInboundsLoading = payload
	},
	SET_CANCELLED_INBOUNDS: (state, payload) => {
		state.cancelledInbounds = payload
	},
	SET_CANCELLED_INBOUNDS_LOADING: (state, payload) => {
		state.cancelledInboundsLoading = payload
	},
	setCurrentInboundTab( state, payload) {
        state.setCurrentTab = payload
    },
	// PAGINATION
	SET_PENDING_INBOUND_PAGINATION: (state, payload) => {
		state.pendingInboundPagination = payload
	},
	SET_FLOOR_INBOUND_PAGINATION: (state, payload) => {
		state.floorInboundPagination = payload
	},
	SET_COMPLETED_INBOUND_PAGINATION: (state, payload) => {
		state.completedInboundPagination = payload
	},
	SET_CANCELLED_INBOUND_PAGINATION: (state, payload) => {
		state.cancelledInboundPagination = payload
	},
	SET_NEW_STORABLE_UNIT_LOADING: (state, payload) => {
		state.newStorableUnitLoading = payload
	},
	SET_EDIT_STORABLE_UNIT_LOADING: (state, payload) => {
		state.editStorableUnitLoading = payload
	},
	// LOCATIONS
	SET_WAREHOUSE_LOCATIONS_DETAILS: (state, payload) => {
		state.allLocations = payload
	},
	SET_WAREHOUSE_LOCATIONS_DETAILS_LOADING: (state, payload) => {
		state.allLocationsLoading = payload
	},
	SET_PLACE_STORABLE_UNIT_LOADING: (state, payload) => {
		state.placeStorableUnitLoading = payload
	},
	SET_PLACE_MULTIPLE_STORABLE_UNIT_LOADING: (state, payload) => {
		state.placeMultipleStorableUnitLoading = payload
	},
	SET_DOWNLOAD_LOADING: (state, payload) => {
		state.downloadDocLoading = payload
	},
	SET_UPLOAD_INBOUND_DOC_LOADING: (state, payload) => {
		state.uploadDocLoading = payload
	},
	SET_PRINT_ORDER: (state, payload) => {
		state.printOrder = payload
	},
	SET_PRINT_ORDER_LOADING: (state, payload) => {
		state.printOrderLoading = payload
	},
	setEmptyInventoryInboundData: (state, payload) => {
		let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            data: payload
        }

		state.draftInboundPagination = locationDefaultData
		state.pendingInboundPagination = locationDefaultData
		state.floorInboundPagination = locationDefaultData
		state.completedInboundPagination = locationDefaultData
		state.cancelledInboundPagination = locationDefaultData
		state.pendingReceiveInboundPagination = locationDefaultData
		state.pendingReceiveInboundPaginationWHCustomer = locationDefaultData
	},
	SET_SEARCHED_INBOUND: (state, payload) => {
        state.searchedInbounds = payload
    },
	SET_SEARCHED_INBOUND_LOADING: (state, payload) => {
        state.searchedInboundsLoading = payload
    },
    setInboundSearchedVal: (state, payload) => {
        // state.searchedInbounds = payload

		let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            data: payload
        }

        state.searchedInbounds = locationDefaultData
    },
	// filter and search
	SET_FILTERED_INBOUND_LOADING: (state, payload) => {
		state.filteredInboundsLoading = payload
	},
	SET_FILTERED_CUSTOMER_INBOUND: (state, payload) => {
		state.filteredInbounds = payload
	},
	setInboundFilteredVal: (state, payload) => {
		let locationDefaultData = {
			currentTab: 1,
			current_page: 1,
			old_page: 1,
			total: 0,
			per_page: 0,
			data: payload
		}

		state.filteredInbounds = locationDefaultData
	},
	setIsFirstTimeFetching: (state, payload) => {
		state.isFetchFirstTime = payload
	},
	SET_ALL_PRODUCTS_INBOUND_LISTS: (state, payload) => {
		state.allInboundProductsLists = payload
	},
	SET_ALL_PRODUCTS_INBOUND_LISTS_LOADING: (state, payload) => {
		state.allInboundProductsListsLoading = payload
	},
	SET_ALL_PRODUCTS_INBOUND_LISTS_DROPDOWN_DATA: (state, payload) => {
		state.allInboundProductListsDropdownData = payload
	},
	// 3pl Warehouses
	SET_RECEIVE_PRODUCT_3PL_LOADING: (state, payload) => {
		state.receiveProduct3plLoading = payload
	},
	SET_RECEIVE_PRODUCT_MULTIPLE_3PL_LOADING: (state, payload) => {
		state.receiveProductMultiple3plLoading = payload
	},
	COMPLETE_ORDER_3PL_LOADING: (state, payload) => {
		state.completeInboundOrderLoading = payload
	},
	setIsCreateInboundShow: (state, payload) => {
		state.isShowCreateInboundDialog = payload
	},
	SET_ALL_WAREHOUSE_PRODUCTS_INBOUND_LISTS: (state, payload) => {
		state.allWarehouseCustomerInboundProductsLists = payload
	},
	SET_ALL_WAREHOUSE_PRODUCTS_INBOUND_LISTS_LOADING: (state, payload) => {
		state.allWarehouseCustomerInboundProductsListsLoading = payload
	},
	setSelectedPrevCustomerWarehouseID: (state, payload) => {
		state.previousSelectedWarehouseID = payload
	},
	SET_DRAFT_INBOUND_PAGINATION: (state, payload) => {
		state.draftInboundPagination = payload
	},
	SET_DRAFT_INBOUNDS_LOADING: (state, payload) => {
		state.draftInboundPaginationLoading = payload
	},
	SET_PENDING_RECEIVING_INBOUND_PAGINATION: (state, payload) => {
		state.pendingReceiveInboundPagination = payload
	},
	SET_PENDING_RECEIVING_INBOUNDS_LOADING: (state, payload) => {
		state.pendingReceiveInboundPaginationLoading = payload
	},
	SET_PENDING_RECEIVING_INBOUND_PAGINATION_WH_CUSTOMER: (state, payload) => {
		state.pendingReceiveInboundPaginationWHCustomer = payload
	},
	SET_PENDING_RECEIVING_INBOUNDS_WH_CUSTOMER_LOADING: (state, payload) => {
		state.pendingReceiveInboundPaginationWHCustomerLoading = payload
	},
	SET_DELETE_INBOUND_LOADING: (state, payload) => {
		state.deleteInboundLoading = payload
	},
	SET_INBOUND_SUBMIT_LOADING: (state, payload) => {
		state.submitInboundLoading = payload
	},
	SET_INBOUND_ACCEPT_LOADING: (state, payload) => {
		state.acceptInboundLoading = payload
	},
	SET_REJECT_INBOUND_LOADING: (state, payload) => {
		state.rejectInboundLoading = payload
	},
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}