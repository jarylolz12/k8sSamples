import axios from "axios"
import store from '../../'
// import ProductModel from '../../../models/ProductModel'
const state = {
	outboundInventoriesLoading: false,
	outboundInventories: [],
	outboundCreateLoading: false,
	singleOutbound: null,
	singleOutboundLoading: false,
	outboundUpdateLoading: false,
	outboundUpdate3plLoading:false,
	cancelOutboundLoading: false,
	cancelMultipleOutboundLoading:false,
	deleteOutboundLoading:false,
	pickOutboundLocationLoading:false,
	pickOutboundStorableLoading:false,
	confirmOrderCompletionLoading:false,
	loadProductLoading:false,
	setBatchProductLoading:false,
	allOutboundProductsLists: [],
	allOutboundProductListsDropdownData:[],
	allOutboundProductsListsLoading:false,
	// 
	pendingOutbounds: [],
	floorOutbounds: [],
	completedOutbounds: [],
	cancelledOutbounds: [],
	pickOutboundLocation:[],
	pickOutboundStorable:[],
	pendingOutboundsLoading: false,
	floorOutboundsLoading: false,
	completedOutboundsLoading: false,
	cancelledOutboundsLoading: false,
	downloadDocLoading: false,
	pickProductLoading:false,
	loadStorableUnitLoading:false,
	BatchProductLoading:false,
	storableUnitNewLoading:false,
	storableUnitUpdateLoading:false,
	enableButtonSortableUnit:false,
	printOutboundOrderLoading:false,
	setBatchStorableLoading:false,
	// search outbound
	searchedOutboundsLoading:false,
	searchedOutbounds: [],
	// 6pl
	warehouse6plProductsLoading:false,
	allProducts6PLists:[],
	poBaseUrlState: process.env.VUE_APP_PO_URL,
	// filter outbound customer
	filteredOutboundsLoading:false,
	filteredOutbounds:[],
	SearchCustomerFinalArrayCopy:[],
	//
	pendingOutboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	floorOutboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	completedOutboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	cancelledOutboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	// connected 3pl Provider
	draftSbmitLoading:false,
	draftOutboundsLoading :false,
	pendingShippedOutboundsLoading :false,
	pendingShippingProviderOutboundsLoading:false,
	acceptOrderByProviderOutboundLoading:false,
	rejectOrderByProviderOutboundLoading:false,
	draftOutboundPagination : {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	pendingShippedOutboundPagination: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	pendingShippingProviderOutboundPagination : {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		data: []
	},
	setCurrentTab: 0,
}

const getters = {
	getOutboundInventories: state => state.outboundInventories,
	getOutboundInventoriesLoading: state => state.outboundInventoriesLoading,
	getOutboundCreateLoading: state => state.outboundCreateLoading,
	getSingleOutbound: state => state.singleOutbound,
	getSingleOutboundLoading: state => state.singleOutboundLoading,
	getOutboundUpdateLoading: state => state.outboundUpdateLoading,
	getoutboundUpdate3plLoading:state => state.outboundUpdate3plLoading,
	getCancelOutboundLoading: state => state.cancelOutboundLoading,
	getcancelMultipleOutboundLoading:state => state.cancelMultipleOutboundLoading,
	getDeleteOutboundLoading: state => state.deleteOutboundLoading,
	getDownloadDocOutboundLoading: state => state.downloadDocLoading,
	getPickProductLoading:state=>state.pickProductLoading,
	getLoadStorableUnitLoading:state => state.loadStorableUnitLoading,
	getNewStorableUnitLoading:state => state.storableUnitNewLoading,
	getstorableUnitUpdateLoading:state => state.storableUnitUpdateLoading,
	getPrintOutboundOrderLoading: state =>state.printOutboundOrderLoading,
	getBatchProductLoading:state=> state.BatchProductLoading,
	getpickOutboundLocationLoading:state => state.pickOutboundLocationLoading,
	getpickOutboundLocationData: state => state.pickOutboundLocation,
	getpickOutboundStorableloading:state=>state.pickOutboundStorableLoading,
	getpickOutboundStorableData:state=>state.pickOutboundStorable,
	getsetBatchStorableLoading:state=>state.setBatchStorableLoading,
	getconfirmOrderCompletionLoading:state=>state.confirmOrderCompletionLoading,
	getloadProductLoading:state=>state.loadProductLoading,
	getsetBatchProductLoading:state=>state.setBatchProductLoading,
	getallOutboundProductsLists: state=> state.allOutboundProductsLists,
	getAllOutboundProductsListsDropdownData:state => state.allOutboundProductListsDropdownData,
	// search Outbound
	getsearchedOutboundsLoading:state=> state.searchedOutboundsLoading,
	getSearchedOutbounds:state=> state.searchedOutbounds,
	// filtered customer
	getfilteredOutboundsLoading:state => state.filteredOutboundsLoading,
	getSearchCustomerFinalArrayCopy: state => state.SearchCustomerFinalArrayCopy,
	getfilteredOutbounds:state => state.filteredOutbounds,
	// 6pl
	getwarehouse6plProductsLoading :state => state.warehouse6plProductsLoading,
	getallProducts6PLists:state=> state.allProducts6PLists,
	// 
	getPendingOutbounds: state => state.pendingOutbounds,
	getFloorOutbounds: state => state.floorOutbounds,
	getCompletedOutbounds: state => state.completedOutbounds,
	getCancelledOutbounds: state => state.cancelledOutbounds,
	getPendingOutboundsLoading: state => state.pendingOutboundsLoading,
	getFloorOutboundsLoading: state => state.floorOutboundsLoading,
	getCompletedOutboundsLoading: state => state.completedOutboundsLoading,
	getCancelledOutboundsLoading: state => state.cancelledOutboundsLoading,
	// pagination
	getPendingOutboundPagination: state => state.pendingOutboundPagination,
	getFloorOutboundPagination: state => state.floorOutboundPagination,
	getCompletedOutboundPagination: state => state.completedOutboundPagination,
	getCancelledOutboundPagination: state => state.cancelledOutboundPagination,
	getCurrentOutboundTab: state => state.setCurrentTab,
	getEnableButtonSortableUnit:state=>state.enableButtonSortableUnit,
	// connected 3pl provider
	getDraftOutboundPagination: state => state.draftOutboundPagination,
	getDraftOutboundsLoading: state => state.draftOutboundsLoading,
	getPendingShippedOutboundsLoading : state => state.pendingShippedOutboundsLoading,
	getPendingShippedOutboundPagination: state => state.pendingShippedOutboundPagination,
	getDraftSbmitLoading: state => state.draftSbmitLoading,
	getPendingShippingProviderOutboundPagination: state => state.pendingShippingProviderOutboundPagination,
	getPendingShippingProviderOutboundsLoading: state => state.pendingShippingProviderOutboundsLoading,
	getAcceptOrderByProviderOutboundLoading: state => state.acceptOrderByProviderOutboundLoading,
	getRejectOrderByProviderOutboundLoading: state => state.rejectOrderByProviderOutboundLoading
}

const poBaseUrl = process.env.VUE_APP_PO_URL

const actions = {
	fetchOutboundInventories: async ({
		commit
	}, wid) => {
		let attempt = false
		async function proceed() {

			commit("SET_OUTBOUND_INVENTORIES", [])
			commit("SET_OUTBOUND_INVENTORIES_LOADING", true)

			await axios.get(`${poBaseUrl}/warehouses/${wid}/outbounds`)
			.then(res => {
				if (typeof res.status !== 'undefined') {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_OUTBOUND_INVENTORIES_LOADING", false)
							commit("SET_OUTBOUND_INVENTORIES", res.data)
						}
					}
				}
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
						commit('SET_OUTBOUND_INVENTORIES_LOADING', false)
						return Promise.reject(err)
					}
				} else {
					commit('SET_OUTBOUND_INVENTORIES_LOADING', false)
					return Promise.reject(err.error)
				}
				/*
				commit("SET_OUTBOUND_INVENTORIES_LOADING", false)
				if (typeof err.error !== 'undefined') {
					return Promise.reject(err.error)
				} else {
					if (typeof err.message !== 'undefined') {
						return Promise.reject('Token has been expired. Please reload the page.')
					}
				}*/
			})
		}
		proceed()
		
	},
	createOutbound: async ({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			function proceed() {
				let {
					warehouse_id,
					documents,
					storable_units,			
					...otherProps
				} = payload

				payload = {
					...otherProps
				}
				//set loading to true
				commit("SET_CREATE_OUTBOUND_LOADING", true)
				
					let bodyFormData = new FormData()
					let getPayloadKeys = Object.keys(payload)
					
					if (getPayloadKeys.length > 0) {
						getPayloadKeys.filter(gpk => {
							bodyFormData.append(gpk, payload[gpk])
						})
					}
					if (documents.length > 0) {
					for (var i = 0; i < documents.length; i++) {
						bodyFormData.append(`documents[${[i]}]`, documents[i]);
					}
					}
					// if(storable_units.length>0){
					// 	for (var s = 0; s < storable_units.length; s++) {
					// 		bodyFormData.append(`storable_units[${[s]}]`, storable_units[s]);
					// 	}
					// }
					console.log(storable_units)
					
					axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/outbounds/create`, bodyFormData, {
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					})
					.then(res => {
						commit("SET_CREATE_OUTBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
						
							resolve(res.data)
						}
					})
					.catch(err => {
						if (typeof err.error !=='undefined') {
							commit('SET_CREATE_OUTBOUND_LOADING', false)
							//reject(err.error)
							//commit("SET_PO_DETAIL_LOADING", false)
							reject(err.error)
						}else{
							commit('SET_CREATE_OUTBOUND_LOADING', false)
							reject(err)
						}
						/*
						commit("SET_CREATE_OUTBOUND_LOADING", false)
						if (typeof err.error !== 'undefined') {
							reject(err.error)
						} else {
							reject(err)
						}*/
					})
			
					
			}
			proceed()
		})
	},
	updateOutbound: async ({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			function proceed() {
				let {
					warehouse_id,
					outbound_id,
					documents,
					storable_units,
					...otherProps
				} = payload

				payload = {
					...otherProps
				}
				
				//set loading to true
				commit("SET_UPDATE_OUTBOUND_LOADING", true)
				
				
					let bodyFormData = new FormData()
					let getPayloadKeys = Object.keys(payload)
					
					if (getPayloadKeys.length > 0) {
						getPayloadKeys.filter(gpk => {
							bodyFormData.append(gpk,payload[gpk])
						})
					}
					// for (var p = 0; p < products.length; p++) {
					// 	bodyFormData.append(`products[${[p]}]`, products[p]);
					// }
					if(documents.length >0){
						for (var i = 0; i < documents.length; i++) {
							bodyFormData.append(`documents[${[i]}]`, documents[i]);
						}
					}
					else{
						bodyFormData.append(`documents[]`,documents);
					}
				
					// if(storable_units.length>0){
					// 	for (var s = 0; s < storable_units.length; s++) {
					// 		bodyFormData.append(`storable_units[${[s]}]`, storable_units[s]);
					// 	}
					// }
					// else{
					// 	bodyFormData.append(`storable_units[]`,storable_units);
					// }
					console.log(storable_units)

					axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/outbounds/update/${outbound_id}`, bodyFormData, {
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					})
					.then(res => {
						commit("SET_UPDATE_OUTBOUND_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
						if (typeof err.error !=='undefined') {
							commit('SET_UPDATE_OUTBOUND_LOADING', false)
							reject(err.error)
						}
						else{
							commit("SET_UPDATE_OUTBOUND_LOADING", false)
							reject(err)
						}
						
					})
				
			}
			proceed()
				
		})
	},
	updateOutbound3pl: async ({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			function proceed() {
				let {
					warehouse_id,
					outbound_id,
					documents,
					storable_units,
					...otherProps
				} = payload

				payload = {
					...otherProps
				}
				commit("SET_UPDATE_OUTBOUND_3PL_LOADING", true)
				
				
					let bodyFormData = new FormData()
					let getPayloadKeys = Object.keys(payload)
					
					if (getPayloadKeys.length > 0) {
						getPayloadKeys.filter(gpk => {
							bodyFormData.append(gpk,payload[gpk])
						})
					}
					if(documents.length >0){
						for (var i = 0; i < documents.length; i++) {
							bodyFormData.append(`documents[${[i]}]`, documents[i]);
						}
					}
					else{
						bodyFormData.append(`documents[]`,documents);
					}
					console.log(storable_units)

					axios.post(`${poBaseUrl}/warehouse-3pl/${warehouse_id}/outbound/update/${outbound_id}`, bodyFormData, {
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					})
					.then(res => {
						commit("SET_UPDATE_OUTBOUND_3PL_LOADING", false)
						if (typeof res!=='undefined' && res.status === 200) {
							resolve(res.data)
						}
					})
					.catch(err => {
						if (typeof err.error !=='undefined') {
							commit('SET_UPDATE_OUTBOUND_3PL_LOADING', false)
							reject(err.error)
						}
						else{
							commit("SET_UPDATE_OUTBOUND_3PL_LOADING", false)
							reject(err)
						}
						
					})
				
			}
			proceed()
				
		})
	},
	fetchSingleOutbound: async ({
		commit
	}, payload) => {
		// commit("SET_SINGLE_OUTBOUND", [])
		commit("SET_SINGLE_OUTBOUND_LOADING", true)
		await axios.get(`${poBaseUrl}/warehouses/${payload.wid}/outbounds/${payload.oid}`)
		.then(res => {
			if (typeof res.status !== 'undefined') {
				if (res.status === 200) {
					if (res.data) {
						commit("SET_SINGLE_OUTBOUND_LOADING", false)
						commit("SET_SINGLE_OUTBOUND", res.data)
					}
				}
			}
		})
		.catch(err => {
			commit("SET_SINGLE_OUTBOUND_LOADING", false)
			if (typeof err.error !== 'undefined') {
				return Promise.reject(err.error)
			} else {
				if (typeof err.message !== 'undefined') {
					if (err.message == 'UnAuthenticated') {
						return Promise.reject(err)
					} else {
						return Promise.reject(err)
					}
				}
			}
		})
	},
	cancelOutbound: async({commit}, payload) => {
		let attempt = false
		commit('SET_CANCEL_OUTBOUND_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouses/${payload.wid}/outbounds/cancel/${payload.oid}`)
				.then(res => {
						commit('SET_CANCEL_OUTBOUND_LOADING', false)	
					resolve(res)
				})
				.catch( err => {
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
							commit("SET_CANCEL_OUTBOUND_LOADING", false)
							reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_CANCEL_OUTBOUND_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},	
	cancelMultipleOutboundApi: async({commit}, payload) => {
		let attempt = false
		commit('SET_CANCEL_MULTIPLE_OUTBOUND_LOADING', true)
		var searchParams = new URLSearchParams();
		for(var ser =0 ;ser <payload.idss.length;ser++){
			searchParams.append(`ids[]`, payload.idss[ser])
		}
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouse/${payload.wid}/outbound/cancel-multiple-outbound?`,{params: searchParams})
				.then(res => {
						commit('SET_CANCEL_MULTIPLE_OUTBOUND_LOADING', false)
					resolve(res)
				})
				.catch( err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing ){
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_CANCEL_MULTIPLE_OUTBOUND_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_CANCEL_MULTIPLE_OUTBOUND_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},	
	deleteOutbound: async ({
		commit
	}, payload) => {
		let attempt = false
		async function proceed() {
			commit("SET_DELETE_OUTBOUND_LOADING", true)
			await axios.delete(`${poBaseUrl}/warehouses/${payload.wid}/outbounds/delete/${payload.oid}`)
			.then(res => {
				if (typeof res.status !== 'undefined') {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_DELETE_OUTBOUND_LOADING", false)
						}
					}
				}
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
						commit('SET_DELETE_OUTBOUND_LOADING', false)
						if (err.message == 'UnAuthenticated') {
							return Promise.reject(err)
						} else {
							return Promise.reject(err)
						}
					}
				} else {
					commit('SET_DELETE_OUTBOUND_LOADING', false)
					return Promise.reject(err.error)
				}
			})
		}
		proceed()		
	},
	setOutboundReset({ commit }, payload) {
		commit("SET_OUTBOUND_RESET", payload)
	},
	// 
	setCurrentOutboundTab( { commit }, payload) {
        commit("setCurrentOutboundTab", payload)
    },
	fetchPendingOutbounds: async ({
		commit
	}, payload) => {
		commit("SET_PENDING_OUTBOUNDS_LOADING", true)
		let passedData = {
			url: `${poBaseUrl}/warehouse/${payload.id}/outbound/common`,
			method: 'GET',
			params: {
				page: payload.page,
				status: ["pending"]
			},
			cancelToken: payload.cancelToken
		}
		axios(passedData)

		// await axios.get(`${poBaseUrl}/warehouse/${payload.id}/outbound/pending?page=${payload.page}`,{ cancelToken: payload.cancelToken })
		.then(res => {
			if (typeof res.status !== 'undefined') {
				if (res.status === 200) {
					if (res.data) {
						let results = res.data.results
						commit("SET_PENDING_OUTBOUNDS_LOADING", false)
						commit("SET_PENDING_OUTBOUNDS", results)

						let pagination = {
							currentTab: 1,
							current_page: results.current_page,
							total: results.total,
							last_page: results.last_page,
							per_page: results.per_page,
							data : results.data
						}

						commit("SET_PENDING_OUTBOUND_PAGINATION", pagination)
					}
				}
			}
		})
		.catch(err => {
			commit("SET_PENDING_OUTBOUNDS_LOADING", false)
			if (typeof err.error !== 'undefined') {
				return Promise.reject(err.error)
			} else {
				if (typeof err.message !== 'undefined') {
					return Promise.reject(err)
				}
			}
		})
	},
	fetchFloorOutbounds: async ({
		commit
	}, payload) => {
		commit("SET_FLOOR_OUTBOUNDS_LOADING", true)
		let passedData = {
			url: `${poBaseUrl}/warehouse/${payload.id}/outbound/common`,
			method: 'GET',
			params: {
				page: payload.page,
				status: ["floor", "ready"]
			},
			cancelToken: payload.cancelToken
		}
		axios(passedData)
		// await axios.get(`${poBaseUrl}/warehouse/${payload.id}/outbound/floor?page=${payload.page}`,{ cancelToken: payload.cancelToken })
		.then(res => {
			if (typeof res.status !== 'undefined') {
				if (res.status === 200) {
					if (res.data) {
						let results = res.data.results
						commit("SET_FLOOR_OUTBOUNDS_LOADING", false)
						commit("SET_FLOOR_OUTBOUNDS", results)

						let pagination = {
							currentTab: 1,
							current_page: results.current_page,
							total: results.total,
							last_page: results.last_page,
							per_page: results.per_page,
							data:results.data
						}

						commit("SET_FLOOR_OUTBOUND_PAGINATION", pagination)
					}
				}
			}
		})
		.catch(err => {
			commit("SET_FLOOR_OUTBOUNDS_LOADING", false)
			if (typeof err.error !== 'undefined') {
				return Promise.reject(err.error)
			} else {
				if (typeof err.message !== 'undefined') {
					return Promise.reject(err)
				}
			}
		})
	},
	fetchCompletedOutbounds: async ({
		commit
	}, payload) => {
		commit("SET_COMPLETED_OUTBOUNDS_LOADING", true)
		let passedData = {
			url: `${poBaseUrl}/warehouse/${payload.id}/outbound/common`,
			method: 'GET',
			params: {
				page: payload.page,
				status: ["completed"]
			},
			cancelToken: payload.cancelToken
		}
		axios(passedData)
		// await axios.get(`${poBaseUrl}/warehouse/${payload.id}/outbound/completed?page=${payload.page}`,{ cancelToken: payload.cancelToken })
		.then(res => {
			if (typeof res.status !== 'undefined') {
				if (res.status === 200) {
					if (res.data) {
						let results = res.data.results
						commit("SET_COMPLETED_OUTBOUNDS_LOADING", false)
						commit("SET_COMPLETED_OUTBOUNDS", results)

						let pagination = {
							currentTab: 1,
							current_page: results.current_page,
							total: results.total,
							last_page: results.last_page,
							per_page: results.per_page,
							data:results.data
						}

						commit("SET_COMPLETED_OUTBOUND_PAGINATION", pagination)						
					}
				}
			}
		})
		.catch(err => {
			commit("SET_COMPLETED_OUTBOUNDS_LOADING", false)
			if (typeof err.error !== 'undefined') {
				return Promise.reject(err.error)
			} else {
				if (typeof err.message !== 'undefined') {
					return Promise.reject(err)
				}
			}
		})
	},
	fetchCancelledOutbounds: async ({
		commit
	}, payload) => {
		commit("SET_CANCELLED_OUTBOUNDS_LOADING", true)
		let passedData = {
			url: `${poBaseUrl}/warehouse/${payload.id}/outbound/common`,
			method: 'GET',
			params: {
				page: payload.page,
				status: payload.status
			},
			cancelToken: payload.cancelToken
		}
		axios(passedData)
		// await axios.get(`${poBaseUrl}/warehouse/${payload.id}/outbound/cancelled?page=${payload.page}`,{ cancelToken: payload.cancelToken })
		.then(res => {
			if (typeof res.status !== 'undefined') {
				if (res.status === 200) {
					if (res.data) {
						let results = res.data.results
						commit("SET_CANCELLED_OUTBOUNDS_LOADING", false)
						commit("SET_CANCELLED_OUTBOUNDS", results)

						let pagination = {
							currentTab: 1,
							current_page: results.current_page,
							total: results.total,
							last_page: results.last_page,
							per_page: results.per_page,
							data:results.data
						}

						commit("SET_CANCELLED_OUTBOUND_PAGINATION", pagination)
					}
				}
			}
		})
		.catch(err => {
			commit("SET_CANCELLED_OUTBOUNDS_LOADING", false)
			if (typeof err.error !== 'undefined') {
				return Promise.reject(err.error)
			} else {
				if (typeof err.message !== 'undefined') {
					return Promise.reject(err)
				}
			}
		})
	},
	downloadOutboundDoc: async({commit}, item) => {
		let attempt = false
		commit('SET_DOWNLOAD_OUTBOUND_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios({
					url: `${poBaseUrl}/warehouses/${item.warehouse_id}/outbounds/${item.outbound_id}/download-document/${item.id}`,
					method: 'GET',
					responseType: 'blob'
				}).then(response => {
					commit('SET_DOWNLOAD_OUTBOUND_LOADING', false)
					let fileURL = window.URL.createObjectURL(new Blob([response.data]))
					let fileLink = document.createElement('a')
					fileLink.href = fileURL
					fileLink.setAttribute('download', `${(typeof item.original_name!=='undefined') ? item.original_name : ''}.pdf`)
					fileLink.click()
					resolve('ok')

				})
				.catch( err => {
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
							commit("SET_DOWNLOAD_OUTBOUND_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_DOWNLOAD_OUTBOUND_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	pickproduct : async({commit}, item) => {
		let attempt = false
		commit('SET_PICKED_PRODUCT_LOADING', true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.post(`${poBaseUrl}/v2/warehouse/${item.wid}/outbound/${item.oid}/pick-outbound-product`, item).then(response => {
					commit('SET_PICKED_PRODUCT_LOADING', false)
					resolve(response.data)
				})
				.catch( err => {
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
							commit("SET_PICKED_PRODUCT_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_PICKED_PRODUCT_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	loadStorableUnit : async({commit}, item) => {
		let attempt = false
		commit('SET_LOAD_SORTABLE_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios({
					url: `${poBaseUrl}/warehouses/${item.warehouse_id}/outbounds/${item.outbound_id}/load-storable-unit/${item.id}`,
					method: 'GET',
				}).then(response => {
					commit('SET_LOAD_SORTABLE_LOADING', false)
					commit("ENABLE_BUTTON_ON_STORABLE_UNIT",true)
					resolve(response.data)
				})
				.catch( err => {
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
							commit("SET_LOAD_SORTABLE_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_LOAD_SORTABLE_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	newStorableUnitOutbound: async ({
		commit
	}, payload) => {
		let { warehouse_id, outbound_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_NEW_STORABLE_UNIT_OUTBOUND_LOADING", true)

				axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/outbounds/${outbound_id}/new-storable-unit`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_NEW_STORABLE_UNIT_OUTBOUND_LOADING", false)
							}
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
							commit('SET_NEW_STORABLE_UNIT_OUTBOUND_LOADING', false)
							reject(err)

							// if (err.message === 'UnAuthenticated') {
							// 	reject('Token has been expired. Please reload the page.')
							// } else {
							// 	reject(err.message)
							// }
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_NEW_STORABLE_UNIT_OUTBOUND_LOADING', false)
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
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_UPDATE_STORABLE_UNIT_OUTBOUND_LOADING", true)

				axios.post(`${poBaseUrl}/warehouse/${payload.warehouse_id}/outbound/${payload.outbound_id}/edit-storable-unit/${payload.storable_id}`,payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_UPDATE_STORABLE_UNIT_OUTBOUND_LOADING", false)
							}
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
							commit('SET_UPDATE_STORABLE_UNIT_OUTBOUND_LOADING', false)
							reject(err)

							// if (err.message === 'UnAuthenticated') {
							// 	reject('Token has been expired. Please reload the page.')
							// } else {
							// 	reject(err.message)
							// }
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_UPDATE_STORABLE_UNIT_OUTBOUND_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	printOutboundOrder: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_PRINT_OUTBOUND_ORDER_LOADING", true)

				axios({
					url: `${poBaseUrl}/warehouse/${payload.wid}/outbound/${payload.oid}/print-order`,
					method: 'GET',
					responseType: 'blob'
				}).then(response => {
					commit('SET_PRINT_OUTBOUND_ORDER_LOADING', false)
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
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing ) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_PRINT_OUTBOUND_ORDER_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PRINT_OUTBOUND_ORDER_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	
	BatchPickedApi : async({commit}, item) => {
		let attempt = false
		commit('SET_BATCH_MULTIPLE_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				// var searchParams = new URLSearchParams();
				// for(var ser =0 ;ser <item.idss.length;ser++){
				// 	searchParams.append(`ids[${JSON.parse([ser])}]`, item.idss[ser])
				// }
				
				axios.post( `${poBaseUrl}/v2/warehouse/${item.wid}/outbound/${item.oid}/pick-multiple-outbound-product`,item)
				.then(response => {
					commit('SET_BATCH_MULTIPLE_LOADING', false)
					commit("ENABLE_BUTTON_ON_STORABLE_UNIT",true)
					resolve(response.data)
				})
				.catch( err => {
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
							commit("SET_BATCH_MULTIPLE_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_BATCH_MULTIPLE_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},	
	BatchStorableLoadedApi:async({commit}, item) => {
		let attempt = false
		commit('SET_BATCH_MULTIPLE_STORABLE_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				var searchParams = new URLSearchParams();
				for(var ser =0 ;ser <item.idss.length;ser++){
					searchParams.append(`storable_units[]`, item.idss[ser])
				}
				axios.get( `${poBaseUrl}/warehouse/${item.wid}/outbound/${item.oid}/load-multiple-storable-units?`,{params: searchParams})
				.then(response => {
					commit('SET_BATCH_MULTIPLE_STORABLE_LOADING', false)
					commit("ENABLE_BUTTON_ON_STORABLE_UNIT",true)
					resolve(response.data)
				})
				.catch( err => {
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
							commit("SET_BATCH_MULTIPLE_STORABLE_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_BATCH_MULTIPLE_STORABLE_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	pickOutboundLocationApi: async({commit}, item) => {
		let attempt = false
		commit('SET_OUTBOUND_LOCATION_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get( `${poBaseUrl}/warehouse/${item.wid}/outbound/${item.oid}/get-locations/${item.loc_id}`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit('SET_OUTBOUND_LOCATION_LOADING', false)
								commit("SET_PICK_OUTBOUND_LOCATION",results)
							}
						}
					}
					resolve(res.data)
				})
				.catch( err => {
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
							commit("SET_OUTBOUND_LOCATION_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_OUTBOUND_LOCATION_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},	
	pickOutboundStorableApi: async({commit}, item) => {
		let attempt = false
		commit('SET_OUTBOUND_STORABLE_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get( `${poBaseUrl}/warehouse/${item.wid}/outbound/${item.oid}/get-storable-units/${item.str_id}`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit('SET_OUTBOUND_STORABLE_LOADING', false)
								commit("SET_PICK_OUTBOUND_STORABLE",results)
							}
						}
					}
					resolve(res.data)
				})
				.catch( err => {
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
							commit("SET_OUTBOUND_STORABLE_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_OUTBOUND_STORABLE_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	Confirm_Order_Completion_Api: async({commit}, item) => {
		let attempt = false
		commit('SET_CONFIRM_ORDER_COMPLETION_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get( `${poBaseUrl}/warehouse-3pl/${item.wid}/outbound/${item.oid}/complete-order`)
				.then(res => {
						commit('SET_CONFIRM_ORDER_COMPLETION_LOADING', false)
						resolve(res)
				})
				.catch( err => {
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
							commit("SET_CONFIRM_ORDER_COMPLETION_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_CONFIRM_ORDER_COMPLETION_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	loadProductApi: async({commit}, item) => {
		let attempt = false
		commit('SET_LOAD_PRODUCT_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get( `${poBaseUrl}/warehouse-3pl/${item.wid}/outbound/${item.oid}/load-outbound-product/${item.id}`)
				.then(res => {
						commit('SET_LOAD_PRODUCT_LOADING', false)
						resolve(res)
				})
				.catch( err => {
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
							commit("SET_LOAD_PRODUCT_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_LOAD_PRODUCT_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	multipleLoadProductApi:async({commit}, item) => {
		let attempt = false
		commit('SET_BATCH_MULTIPLE_PRODUCT_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				var searchParams = new URLSearchParams();
				item.idss.map(val =>{
					searchParams.append(`products[]`,val)
				})
				// for(var ser =0 ;ser <item.idss.length;ser++){
				// 	searchParams.append(`products[]`, item.idss[ser])
				// }
				axios.get(`${poBaseUrl}/warehouse-3pl/${item.wid}/outbound/${item.oid}/load-batch-outbound-products`, {params:searchParams})
				.then(response => {
					commit('SET_BATCH_MULTIPLE_PRODUCT_LOADING', false)
					resolve(response.data)
				})
				.catch( err => {
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
							commit("SET_BATCH_MULTIPLE_PRODUCT_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_BATCH_MULTIPLE_PRODUCT_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	setAllOutboundProductsLists( { commit }, payload) {
        commit("SET_ALL_PRODUCTS_OUTBOUND_LISTS_DROPDOWN_DATA", payload)
    },
	fetchOutboundProducts: async ({
		commit
	}, page) => {
		// const { poBaseUrlState } = state
		commit("SET_ALL_PRODUCTS_OUTBOUND_LISTS_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {			
			function proceed() {
				// let product = new ProductModel(poBaseUrlState)
				// product.getProductsDropdown(page)
				axios.get(`${poBaseUrl}/warehouse/${page.wid}/outbound-products-dropdown?page=${page.page}`, { cancelToken: page.cancelToken })
				.then(res => {
					commit("SET_ALL_PRODUCTS_OUTBOUND_LISTS_LOADING", false)
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

							if (typeof res.data.results!=='undefined' && typeof res.data.results!=='undefined')
								productsData = res.data.results
							
							// if(productsData.length > 0){
							// 	const tempProductsData = productsData.map(product => {
							// 		let tempCartonDimensions = {
							// 			l: 0,
							// 			w: 0,
							// 			h: 0,
							// 			uom: 'cm'
							// 		}
									
							// 		if(product.carton_dimensions){
							// 			tempCartonDimensions = {...JSON.parse(product.carton_dimensions)}
							// 			tempCartonDimensions.l = tempCartonDimensions.l ? tempCartonDimensions.l : tempCartonDimensions.length 
							// 			tempCartonDimensions.h = tempCartonDimensions.h ? tempCartonDimensions.h : tempCartonDimensions.height
							// 			tempCartonDimensions.w = tempCartonDimensions.w ? tempCartonDimensions.w : tempCartonDimensions.width
							// 		}

							// 		let tempUnitDimensions = {
							// 			l: 0,
							// 			w: 0,
							// 			h: 0,
							// 			uom: 'cm'
							// 		}

							// 		if (product.unit_dimensions){
							// 			tempUnitDimensions = {...JSON.parse(product.unit_dimensions)}
							// 		}

							// 		let tempUnitWeight = {
							// 			value: 0,
							// 			unit: 'kg'
							// 		}

							// 		if (product.unit_weight){
							// 			tempUnitWeight = {...JSON.parse(product.unit_weight)}
							// 		}

							// 		return {
							// 			...product,
							// 			carton_dimensions: {
							// 				l: tempCartonDimensions.l,
							// 				w: tempCartonDimensions.w,
							// 				h: tempCartonDimensions.h,
							// 				uom: tempCartonDimensions.uom
							// 			},
							// 			unit_dimensions: {
							// 				l: tempUnitDimensions.l,
							// 				w: tempUnitDimensions.w,
							// 				h: tempUnitDimensions.h,
							// 				uom: tempUnitDimensions.uom
							// 			},
							// 			unit_weight: {
							// 				value: tempUnitWeight.value,
							// 				unit: tempUnitWeight.unit
							// 			}
							// 		}
							// 	})
								
							// 	productsData = tempProductsData
							// }

							finalData = {
								products: productsData,
								current_page: res.data.results.current_page,
								total: res.data.results.total,
								old_page: res.data.results.current_page,
								last_page: res.data.results.last_page,
								per_page: res.data.results.per_page
							}

							commit("SET_ALL_PRODUCTS_OUTBOUND_LISTS", finalData)

						} else {
							commit("SET_ALL_PRODUCTS_OUTBOUND_LISTS", finalData)
						}

						resolve()
					}
				}).catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if ( !store.getters.getIsRefreshing ) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_ALL_PRODUCTS_OUTBOUND_LISTS_LOADING', false)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_ALL_PRODUCTS_OUTBOUND_LISTS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	// search outbound
	setSearchedOutboundLoading({ commit } , payload) {
		commit("SET_SEARCHED_OUTBOUND_LOADING", payload)
	},
	setOutboundSearchedVal({ commit }, payload) {
		commit("setOutboundSearchedVal", payload)
	},
	fetchSearchedOutbounds: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_SEARCHED_OUTBOUND_LOADING", true)

				axios(payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_SEARCHED_OUTBOUND_LOADING", false)
								commit("SET_SEARCHED_OUTBOUND", results)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									old_page: 1,
									last_page: results.last_page,
									per_page: results.per_page,
									tab: payload.tab,
									data: results.data
								}

								commit("SET_SEARCHED_OUTBOUND", pagination)
							}

							resolve()
						}
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
							commit('SET_SEARCHED_OUTBOUND_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_SEARCHED_OUTBOUND_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	setFilteredOutboundLoading({ commit } , payload) {
		commit("SET_FILTERED_OUTBOUND_LOADING", payload)
	},
	setOutboundFilteredVal({ commit }, payload) {
		commit("setOutboundFilteredVal", payload)
	},
	fetchFilterOutboundsCustomers: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_FILTERED_OUTBOUND_LOADING", true)

				axios(payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								let results = res.data.results
								commit("SET_FILTERED_OUTBOUND_LOADING", false)
								commit("SET_FILTERED_CUSTOMER_OUTBOUND", results)

								let pagination = {
									currentTab: 1,
									current_page: results.current_page,
									total: results.total,
									old_page: 1,
									last_page: results.last_page,
									per_page: results.per_page,
									tab: payload.tab,
									data: results.data
								}

								commit("SET_FILTERED_CUSTOMER_OUTBOUND", pagination)
							}

							resolve()
						}
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
							commit('SET_FILTERED_OUTBOUND_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_FILTERED_OUTBOUND_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	wareHouse6plProduct: async ({
		commit
	}, payload) => {
		// const { poBaseUrlState } = state
		commit("SET_ALL_PRODUCTS_6pl_OUTBOUND_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {			
			function proceed() {
				// let product = new ProductModel(poBaseUrlState)
				// product.getProductsDropdown3PLProvider(payload)
				axios.get(`${poBaseUrl}/warehouse/${payload.wid}/outbound-products-dropdown?warehouse_customer_id=${payload.warehouse_customer_id}&page=${payload.page}`)
				// axios.get(`https://po.shifl.com/api/customer/${payload.customer_id}/warehouse/${payload.wid}/warehouse-customer/${payload.warehouse_customer_id}/products?page=${payload.page}`)
				.then(res => {
					commit("SET_ALL_PRODUCTS_6pl_OUTBOUND_LOADING", false)
					if (res.status === 200) {
						// console.log('res 6l',res)
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

							if (typeof res.data.results !== 'undefined' && res.data.results !== undefined)
								productsData = res.data.results
							
							// if(productsData.length > 0){
							// 	const tempProductsData = productsData.map(product => {
							// 		let tempCartonDimensions = {
							// 			l: 0,
							// 			w: 0,
							// 			h: 0,
							// 			uom: 'cm'
							// 		}
									
							// 		if(product.carton_dimensions){
							// 			tempCartonDimensions = {...JSON.parse(product.carton_dimensions)}
							// 			tempCartonDimensions.l = tempCartonDimensions.l ? tempCartonDimensions.l : tempCartonDimensions.length 
							// 			tempCartonDimensions.h = tempCartonDimensions.h ? tempCartonDimensions.h : tempCartonDimensions.height
							// 			tempCartonDimensions.w = tempCartonDimensions.w ? tempCartonDimensions.w : tempCartonDimensions.width
							// 		}

							// 		let tempUnitDimensions = {
							// 			l: 0,
							// 			w: 0,
							// 			h: 0,
							// 			uom: 'cm'
							// 		}

							// 		if (product.unit_dimensions){
							// 			tempUnitDimensions = {...JSON.parse(product.unit_dimensions)}
							// 		}

							// 		let tempUnitWeight = {
							// 			value: 0,
							// 			unit: 'kg'
							// 		}

							// 		if (product.unit_weight){
							// 			tempUnitWeight = {...JSON.parse(product.unit_weight)}
							// 		}

							// 		return {
							// 			...product,
							// 			carton_dimensions: {
							// 				l: tempCartonDimensions.l,
							// 				w: tempCartonDimensions.w,
							// 				h: tempCartonDimensions.h,
							// 				uom: tempCartonDimensions.uom
							// 			},
							// 			unit_dimensions: {
							// 				l: tempUnitDimensions.l,
							// 				w: tempUnitDimensions.w,
							// 				h: tempUnitDimensions.h,
							// 				uom: tempUnitDimensions.uom
							// 			},
							// 			unit_weight: {
							// 				value: tempUnitWeight.value,
							// 				unit: tempUnitWeight.unit
							// 			}
							// 		}
							// 	})
								
							// 	productsData = tempProductsData
							// }

							finalData = {
								products: productsData,
								current_page: res.data.current_page,
								total: res.data.total,
								old_page: res.data.current_page,
								last_page: res.data.last_page,
								per_page: res.data.per_page
							}

							commit("SET_ALL_PRODUCTS_6PL_OUTBOUND_LISTS", finalData)

						} else {
							commit("SET_ALL_PRODUCTS_6PL_OUTBOUND_LISTS", finalData)
						}

						resolve()
					}
				}).catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if ( !store.getters.getIsRefreshing ) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_ALL_PRODUCTS_6pl_OUTBOUND_LOADING', false)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_ALL_PRODUCTS_6pl_OUTBOUND_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	setEmptyInventoryOutboundData( { commit }, payload) {
        commit("setEmptyInventoryOutboundData", payload)
    },
	// connected 3pl Provider
	fetchDraftOutbounds: async ({
		commit
	}, payload) => {
		commit("SET_DRAFT_OUTBOUNDS_LOADING", true)
		// await axios.get(`${poBaseUrl}/warehouse/${payload.id}/outbound/draft?page=${payload.page}`,{ cancelToken: payload.cancelToken })
		let passedData = {
			url: `${poBaseUrl}/warehouse/${payload.id}/outbound/common`,
			method: 'GET',
			params: {
				page: payload.page,
				status: ['draft']
			},
			cancelToken: payload.cancelToken
		}
		axios(passedData)
			.then(res => {
				if (typeof res.status !== 'undefined') {
					if (res.status === 200) {
						if (res.data) {
							let results = res.data.results
							commit("SET_DRAFT_OUTBOUNDS_LOADING", false)
							// commit("SET_DRAFT_OUTBOUNDS", results)

							let pagination = {
								currentTab: 1,
								current_page: results.current_page,
								total: results.total,
								last_page: results.last_page,
								per_page: results.per_page,
								data: results.data
							}

							commit("SET_DRAFT_OUTBOUND_PAGINATION", pagination)
						}
					}
				}
			})
			.catch(err => {
				commit("SET_DRAFT_OUTBOUNDS_LOADING", false)
				if (typeof err.error !== 'undefined') {
					return Promise.reject(err.error)
				} else {
					if (typeof err.message !== 'undefined') {
						return Promise.reject(err)
					}
				}
			})
	},
	fetchPendingShippedOutbounds: async ({
		commit
	}, payload) => {
		commit("SET_PENDING_SHIPPED_OUTBOUNDS_LOADING", true)
		// await axios.get(`${poBaseUrl}/warehouse/${payload.id}/outbound/shipping-pending-for-warehouse-customer?page=${payload.page}`,{ cancelToken: payload.cancelToken })
		let passedData = {
			url: `${poBaseUrl}/warehouse/${payload.id}/outbound/common`,
			method: 'GET',
			params: {
				page: payload.page,
				status: ["shipping-pending", "ready", "floor"],
				tab: 'pending-shipping'
			},
			cancelToken: payload.cancelToken
		}
		axios(passedData)
			.then(res => {
				if (typeof res.status !== 'undefined') {
					if (res.status === 200) {
						if (res.data) {
							let results = res.data.results
							commit("SET_PENDING_SHIPPED_OUTBOUNDS_LOADING", false)
							// commit("SET_DRAFT_OUTBOUNDS", results)

							let pagination = {
								currentTab: 1,
								current_page: results.current_page,
								total: results.total,
								last_page: results.last_page,
								per_page: results.per_page,
								data: results.data
							}

							commit("SET_PENDING_SHIPPED_OUTBOUND_PAGINATION", pagination)
						}
					}
				}
			})
			.catch(err => {
				commit("SET_PENDING_SHIPPED_OUTBOUNDS_LOADING", false)
				if (typeof err.error !== 'undefined') {
					return Promise.reject(err.error)
				} else {
					if (typeof err.message !== 'undefined') {
						return Promise.reject(err)
					}
				}
			})
	},
	fetchPendingShippingServiceProvider: async ({
		commit
	}, payload) => {
		commit("SET_PENDING_SHIPPING_PROVIDER_OUTBOUNDS_LOADING", true)
		// await axios.get(`${poBaseUrl}/warehouse/${payload.id}/outbound/shipping-pending?page=${payload.page}`,{ cancelToken: payload.cancelToken })
		let passedData = {
			url: `${poBaseUrl}/warehouse/${payload.id}/outbound/common`,
			method: 'GET',
			params: {
				page: payload.page,
				status: ["shipping-pending"]
			},
			cancelToken: payload.cancelToken
		}
		axios(passedData)
			.then(res => {
				if (typeof res.status !== 'undefined') {
					if (res.status === 200) {
						if (res.data) {
							let results = res.data.results
							commit("SET_PENDING_SHIPPING_PROVIDER_OUTBOUNDS_LOADING", false)
							// commit("SET_DRAFT_OUTBOUNDS", results)

							let pagination = {
								currentTab: 1,
								current_page: results.current_page,
								total: results.total,
								last_page: results.last_page,
								per_page: results.per_page,
								data: results.data
							}

							commit("SET_PENDING_SHIPPING_PROIDER_OUTBOUND_PAGINATION", pagination)
						}
					}
				}
			})
			.catch(err => {
				commit("SET_PENDING_SHIPPING_PROVIDER_OUTBOUNDS_LOADING", false)
				if (typeof err.error !== 'undefined') {
					return Promise.reject(err.error)
				} else {
					if (typeof err.message !== 'undefined') {
						return Promise.reject(err)
					}
				}
			})
	},
	draftOutboundApi: async ({ commit }, payload) => {
		let attempt = false
		commit('SET_DRAFT_OUTBOUND_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/outbound/${payload}/submit-outbound`)
					.then(res => {
						commit('SET_DRAFT_OUTBOUND_LOADING', false)
						resolve(res)
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
								commit("SET_DRAFT_OUTBOUND_LOADING", false)
								reject(err.message)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit("SET_DRAFT_OUTBOUND_LOADING", false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	AcceptConnectedOrderApi: async ({ commit }, payload) => {
		let attempt = false
		commit('SET_ACCEPTED_ORDER_BY_PROVIDER_OUTBOUND_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/outbound/${payload}/accept-outbound`)
					.then(res => {
						commit('SET_ACCEPTED_ORDER_BY_PROVIDER_OUTBOUND_LOADING', false)
						resolve(res)
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
								commit("SET_ACCEPTED_ORDER_BY_PROVIDER_OUTBOUND_LOADING", false)
								reject(err.message)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit("SET_ACCEPTED_ORDER_BY_PROVIDER_OUTBOUND_LOADING", false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	RejectConnectedOrderApi: async ({ commit }, payload) => {
		console.log("ok")
		let attempt = false
		commit('SET_REJECT_ORDER_OUTBOUND_LOADING', true)

		return new Promise((resolve, reject) => {
			function proceed() {
				let {
					oid,
					...otherProps
				} = payload

				payload = {
					...otherProps
				}
				axios.put(`${poBaseUrl}/outbound/${oid}/reject-outbound`,payload )
					.then(res => {
						commit('SET_REJECT_ORDER_OUTBOUND_LOADING', false)
						resolve(res)
					})
					.catch(err => {
						console.log(err)
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
								commit("SET_REJECT_ORDER_OUTBOUND_LOADING", false)
								reject(err.message)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit("SET_REJECT_ORDER_OUTBOUND_LOADING", false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	
}

const mutations = {
	SET_OUTBOUND_INVENTORIES: (state, payload) => {
		state.outboundInventories = payload
	},
	SET_OUTBOUND_INVENTORIES_LOADING: (state, payload) => {
		state.outboundInventoriesLoading = payload
	},
	SET_CREATE_OUTBOUND_LOADING: (state, payload) => {
		state.outboundCreateLoading = payload
	},
	SET_UPDATE_OUTBOUND_LOADING: (state, payload) => {
		state.outboundUpdateLoading = payload
	},
	SET_UPDATE_OUTBOUND_3PL_LOADING:(state, payload) => {
		state.outboundUpdate3plLoading = payload
	},
	SET_SINGLE_OUTBOUND: (state, payload) => {
		state.singleOutbound = payload
	},
	SET_SINGLE_OUTBOUND_LOADING: (state, payload) => {
		state.singleOutboundLoading = payload
	},
	SET_CANCEL_OUTBOUND_LOADING: (state, payload) => {
		state.cancelOutboundLoading = payload
	},
	SET_CANCEL_MULTIPLE_OUTBOUND_LOADING:(state, payload) => {
		state.cancelMultipleOutboundLoading = payload
	},
	SET_DELETE_OUTBOUND_LOADING:(state,payload) =>{
		state.deleteOutboundLoading =payload
	},
	SET_OUTBOUND_LOCATION_LOADING:(state,payload) =>{
		state.pickOutboundLocationLoading =payload
	},
	SET_PICK_OUTBOUND_LOCATION:(state,payload) =>{
		state.pickOutboundLocation =payload
	},
	SET_OUTBOUND_STORABLE_LOADING:(state,payload) =>{
		state.pickOutboundStorableLoading =payload
	},
	SET_PICK_OUTBOUND_STORABLE:(state,payload) =>{
		state.pickOutboundStorable =payload
	},
	SET_OUTBOUND_RESET: (state, payload) => {
		state.outboundInventories = payload
	},
	SET_CONFIRM_ORDER_COMPLETION_LOADING:(state, payload) => {
		state.confirmOrderCompletionLoading = payload
	},
	SET_LOAD_PRODUCT_LOADING:(state, payload) => {
		state.loadProductLoading = payload
	},
	SET_BATCH_MULTIPLE_PRODUCT_LOADING:(state, payload) => {
		state.setBatchProductLoading = payload
	},
	SET_ALL_PRODUCTS_OUTBOUND_LISTS_LOADING: (state, payload) => {
		state.allOutboundProductsListsLoading = payload
	},
	SET_ALL_PRODUCTS_OUTBOUND_LISTS : (state, payload) => {
		state.allOutboundProductsLists = payload
	},
	SET_ALL_PRODUCTS_OUTBOUND_LISTS_DROPDOWN_DATA: (state, payload) => {
		state.allOutboundProductListsDropdownData = payload
	},
	// search Outbound
	SET_SEARCHED_OUTBOUND: (state, payload) => {
        state.searchedOutbounds = payload
    },
	SET_SEARCHED_OUTBOUND_LOADING: (state, payload) => {
        state.searchedOutboundsLoading = payload
    },
	setOutboundSearchedVal: (state, payload) => {
		let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            data: payload
        }

        state.searchedOutbounds = locationDefaultData
    },
	// filter customer 
	SET_FILTERED_OUTBOUND_LOADING:(state, payload) => {
        state.filteredOutboundsLoading = payload
    },
	SET_FILTERED_CUSTOMER_OUTBOUND:(state,payload) =>{
		state.filteredOutbounds =payload
	},
	setOutboundFilteredVal:(state, payload) => {
		let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            data: payload
        }

        state.filteredOutbounds = locationDefaultData
    },
	setSearchCustomerFinalArray(state,payload){
		state.SearchCustomerFinalArrayCopy = payload
	},
	// 6pl
	SET_ALL_PRODUCTS_6pl_OUTBOUND_LOADING:(state, payload) => {
        state.warehouse6plProductsLoading = payload
    },
	SET_ALL_PRODUCTS_6PL_OUTBOUND_LISTS: (state, payload) => {
        state.allProducts6PLists = payload
    },
	// 
	SET_PENDING_OUTBOUNDS: (state, payload) => {
		state.pendingOutbounds = payload
	},
	SET_PENDING_OUTBOUNDS_LOADING: (state, payload) => {
		state.pendingOutboundsLoading = payload
	},
	SET_FLOOR_OUTBOUNDS: (state, payload) => {
		state.floorOutbounds = payload
	},
	SET_FLOOR_OUTBOUNDS_LOADING: (state, payload) => {
		state.floorOutboundsLoading = payload
	},
	SET_COMPLETED_OUTBOUNDS: (state, payload) => {
		state.completedOutbounds = payload
	},
	SET_COMPLETED_OUTBOUNDS_LOADING: (state, payload) => {
		state.completedOutboundsLoading = payload
	},
	SET_CANCELLED_OUTBOUNDS: (state, payload) => {
		state.cancelledOutbounds = payload
	},
	SET_CANCELLED_OUTBOUNDS_LOADING: (state, payload) => {
		state.cancelledOutboundsLoading = payload
	},
	setCurrentOutboundTab( state, payload) {
        state.setCurrentTab = payload
    },
	SET_DOWNLOAD_OUTBOUND_LOADING: (state, payload) => {
		state.downloadDocLoading = payload
	},
	SET_PICKED_PRODUCT_LOADING:(state,payload)=>{
		state.pickProductLoading=payload
	},
	SET_LOAD_SORTABLE_LOADING: (state,payload)=>{
		state.loadStorableUnitLoading=payload
	},
	SET_BATCH_MULTIPLE_LOADING:(state,payload)=>{
		state.BatchProductLoading=payload
	},
	SET_NEW_STORABLE_UNIT_OUTBOUND_LOADING: (state,payload)=>{
		state.storableUnitNewLoading=payload
	},
	SET_UPDATE_STORABLE_UNIT_OUTBOUND_LOADING:(state,payload)=>{
		state.storableUnitUpdateLoading=payload
	},
	SET_PRINT_OUTBOUND_ORDER_LOADING: (state,payload)=>{
		state.printOutboundOrderLoading=payload
	},
	SET_BATCH_MULTIPLE_STORABLE_LOADING: (state,payload)=>{
		state.setBatchStorableLoading=payload
	},
	// PAGINATION
	SET_PENDING_OUTBOUND_PAGINATION: (state, payload) => {
		state.pendingOutboundPagination = payload
	},
	SET_FLOOR_OUTBOUND_PAGINATION: (state, payload) => {
		state.floorOutboundPagination = payload
	},
	SET_COMPLETED_OUTBOUND_PAGINATION: (state, payload) => {
		state.completedOutboundPagination = payload
	},
	SET_CANCELLED_OUTBOUND_PAGINATION: (state, payload) => {
		state.cancelledOutboundPagination = payload
	},
	ENABLE_BUTTON_ON_STORABLE_UNIT:(state,payload)=>{
		state.enableButtonSortableUnit=payload
	},
	EMPTY_STORABLE_UNIT_lOCATION:(state,payload)=>{
		state.pickOutboundStorable=payload;
		state.pickOutboundLocation=payload
	},
	// connected 3pl provider
	SET_DRAFT_OUTBOUNDS_LOADING: (state, payload) => {
		state.draftOutboundsLoading = payload
	},
	SET_DRAFT_OUTBOUND_PAGINATION: (state, payload) => {
		state.draftOutboundPagination = payload
	},
	SET_PENDING_SHIPPED_OUTBOUNDS_LOADING: (state, payload) => {
		state.pendingShippedOutboundsLoading = payload
	},
	SET_PENDING_SHIPPED_OUTBOUND_PAGINATION: (state, payload) => {
		state.pendingShippedOutboundPagination = payload
	},
	SET_PENDING_SHIPPING_PROVIDER_OUTBOUNDS_LOADING: (state, payload) => {
		state.pendingShippingProviderOutboundsLoading = payload
	},
	SET_PENDING_SHIPPING_PROIDER_OUTBOUND_PAGINATION: (state, payload) => {
		state.pendingShippingProviderOutboundPagination = payload
	},
	SET_DRAFT_OUTBOUND_LOADING: (state, payload) => {
		state.draftSbmitLoading = payload
	},
	SET_ACCEPTED_ORDER_BY_PROVIDER_OUTBOUND_LOADING: (state, payload) => {
		state.acceptOrderByProviderOutboundLoading = payload
	},
	SET_REJECT_ORDER_OUTBOUND_LOADING: (state, payload) => {
		state.rejectOrderByProviderOutboundLoading = payload
	},
	setEmptyInventoryOutboundData: (state, payload) => {
		let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            data: payload
        }

		state.pendingOutboundPagination = locationDefaultData
		state.floorOutboundPagination = locationDefaultData
		state.completedOutboundPagination = locationDefaultData
		state.cancelledOutboundPagination = locationDefaultData
		state.draftOutboundPagination = locationDefaultData
		state.pendingShippedOutboundPagination = locationDefaultData
		state.pendingShippingProviderOutboundPagination = locationDefaultData

		state.pendingOutbounds = []
		state.floorOutbounds = []
		state.completedOutbounds = []
		state.cancelledOutbounds = []
	},
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}