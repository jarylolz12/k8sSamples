import axios from "axios"
import store from '../'
import ProductModel from '../../models/ProductModel'

const state = {
	currentProductSelected: null,
	products: [],
	productsLoading: true,
	createProducts: null,
	createProductsLoading: false,
	updateProduct: null,
	updateProductLoading: false,
	deleteProductLoading: false,
	error: '',
	poBaseUrlState: process.env.VUE_APP_PO_URL,
	reset: false,
	singleProduct: null,
	singleProductLoading: false,
	productsPoDropdown: [],
	searchedProducts: [],
	searchedProductsLoading: false,
	productsGlobalSearchLoading: false,
	flags: [],
	flagsLoading: false,
	pdfReportLoading: false,
	csvReportLoading:false,
	setPdfReportData:[],
	setCsvReportData:[],
	productWarehouseBreakdown: null,
	productWarehouseBreakdownLoading: false,
	productProjectionReport: null,
	productProjectionReportLoading: false,
	archivedProducts: null,
	archivedProductsLoading: false,
	setArchivedProductsLoading: false,
	setUnarchivedProductsLoading: false,
	searchedArchivedProducts: null,
	searchedArchivedProductsLoading: false,
	productContacts: [],
	productContactsLoading: true
}

const getters = {
	poBaseUrlState: state => state.poBaseUrlState,
	getCurrentSelectedProduct: state => state.currentProductSelected,
	getProducts: state => state.products,
	getProductsPoDropdown: state => state.productsPoDropdown,
	getProductsLoading: state => state.productsLoading,
	getCreateProducts: state => state.createProducts,
	getCreateProductsLoading: state => state.createProductsLoading,
	getUpdatedProduct: state => state.updateProduct,
	getUpdatedProductLoading: state => state.updateProductLoading,
	getDeleteProductLoading: state => state.deleteProductLoading,
	getError: state => state.error,
	getSingleProduct: state => state.singleProduct,
	getSingleProductLoading: state => state.singleProductLoading,
	getSearchedProducts: state => state.searchedProducts,
	getSearchedProductsLoading: state => state.searchedProductsLoading,
	getProductGlobalSearchLoading: state => state.productsGlobalSearchLoading,
	getFlags: state => state.flags,
	getFlagsLoading: state => state.flagsLoading,
	getPdfReportLoading: state => state.pdfReportLoading,
	getCsvReportLoading: state => state.csvReportLoading,
	getSetPdfReportData: state => state.setPdfReportData,
	getSetCsvReportData: state => state.setCsvReportData,
	getProductWarehouseBreakdown: state => state.productWarehouseBreakdown,
	getProductWarehouseBreakdownLoading: state => state.productWarehouseBreakdownLoading,
	getProductProjectionReport: state => state.productProjectionReport,
	getProductProjectionReportLoading: state => state.productProjectionReportLoading,
	getArchivedProducts: state => state.archivedProducts,
	getArchivedProductsLoading: state => state.archivedProductsLoading,
	getDoArchivedProductsLoading: state => state.setArchivedProductsLoading,
	getDoUnarchivedProductsLoading: state => state.setUnarchivedProductsLoading,
	getSearchedArchivedProducts: state => state.searchedArchivedProducts,
	getSearchedArchivedProductsLoading: state => state.searchedArchivedProductsLoading,
	getProductContacts: state => state.productContacts,
	getProductContactsLoading: state => state.productContactsLoading,
}

const actions = {
	setProduct({ commit }, payload) {
		commit("setProduct", payload)
	},
	setReset({ commit }, payload) {
		commit("setReset", payload)
	},
	fetchProducts: async ({
		commit, state
	}, page) => {
		const {
			poBaseUrlState
		} = state
		commit("SET_PRODUCTS_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {
			let limit = 1
			let start = 0
			function proceed() {
				let product = new ProductModel(poBaseUrlState)
				product.getProducts(page).then(res => {
					commit("SET_PRODUCTS_LOADING", false)
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

							commit("SET_PRODUCTS", finalData)

						} else {
							commit("SET_PRODUCTS", finalData)
						}

						resolve()
					}
				}).catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									if ( start == limit) {
										store.dispatch('refreshToken').then(() => {
											attempt = false
											start = 0
											proceed()
										}).catch(e => {
											console.log(e)
											commit("SET_PRODUCTS_LOADING", false)
											reject(err)
										})
									} else {
										start++
										proceed()
										attempt = false
									}
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_PRODUCTS_LOADING", false)
							reject(err)
						}
					}
				})

				/*
				axios.get(`${process.env.VUE_APP_PO_URL}/products`)
				.then(res => {
					commit("SET_PRODUCTS_LOADING", false)
					if (res.status === 200) {
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

							commit("SET_PRODUCTS", productsData)

						} else {
							commit("SET_PRODUCTS", [])
						}
					}
					resolve()
				})
				.catch(err => {

					//commit("SET_PRODUCTS_LOADING", false)

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
							commit("SET_PRODUCTS_LOADING", false)
							//commit("SET_SHIPMENT_LOADING", false)
							reject('Token has been expired. Please reload the page.')
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_PRODUCTS_LOADING", false)
						//commit("SET_SHIPMENT_LOADING", false)
						reject(err.error)
					}

					/*
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						if (typeof err.message !== 'undefined') {
							return Promise.reject('Token has been expired. Please reload the page.')
						}
					}*/
				//})	
			}
			proceed()	
		})
	},
	importProducts: ({commit},payload) => {
		console.log(commit)
		const formData = new FormData();
		formData.append('file',payload)
		return new Promise((resolve, reject) => {
			 axios.post(`${process.env.VUE_APP_PO_URL}/products/import`, formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			}).then((result) => {
				resolve(result)
			 }).catch(error => {
				 reject(error)
			 })
		})
	},
	duplicateProducts: async ({
		commit
	}, payload ) => {

		let attempt = false
		commit("SET_CREATE_PRODUCTS_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				let fd = new FormData()
				let getPayloadKeys = Object.keys(payload)

				if (getPayloadKeys.length > 0) {
					getPayloadKeys.map(gpk => {
						fd.append(gpk, payload[gpk])
					})
				}

				if (payload !== null) {
					// if image is not null, use form data
					if (typeof payload.image !== 'undefined' && payload.image !== null) {
						axios.put(`${process.env.VUE_APP_PO_URL}/products/${payload.id}/duplicate`, fd, {
							headers: {
								'Content-Type': 'multipart/form-data'
							}
						})
						.then(res => {
							if (res.status === 200) {
								if (res.data) {
									commit("SET_CREATE_PRODUCTS_LOADING", false)
								}
							}
							resolve()
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
									commit('SET_CREATE_PRODUCTS_LOADING', false)
									reject(err.message)
								}
							}

							if (typeof err.error !=='undefined') {
								commit('SET_CREATE_PRODUCTS_LOADING', false)
								reject(err.error)
							}
						})

					// if image is null, do not use form data
					} else {
						axios.put(`${process.env.VUE_APP_PO_URL}/products/${payload.id}/duplicate`, payload)
						.then(res => {
							if (res.status === 200) {
								if (res.data) {
									commit("SET_CREATE_PRODUCTS_LOADING", false)
								}
							}
							resolve()
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
									commit('SET_CREATE_PRODUCTS_LOADING', false)
									reject(err.message)
								}
							}

							if (typeof err.error !=='undefined') {
								commit('SET_CREATE_PRODUCTS_LOADING', false)
								reject(err.error)
							}
						})
					}
				}
			}
			proceed()	
		})
			
	},
	createProducts: async ({
		commit
	}, payload ) => {

		let attempt = false
		commit("SET_CREATE_PRODUCTS_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				let fd = new FormData()
				let getPayloadKeys = Object.keys(payload)

				if (getPayloadKeys.length > 0) {
					getPayloadKeys.map(gpk => {
						fd.append(gpk, payload[gpk])
					})
				}

				if (payload !== null) {
					// if image is not null, use form data
					if (typeof payload.image !== 'undefined' && payload.image !== null) {
						axios.post(`${process.env.VUE_APP_PO_URL}/products/create`, fd, {
							headers: {
								'Content-Type': 'multipart/form-data'
							}
						})
						.then(res => {
							if (res.status === 200) {
								if (res.data) {
									commit("SET_CREATE_PRODUCTS_LOADING", false)
								}
							}
							resolve()
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
									commit('SET_CREATE_PRODUCTS_LOADING', false)
									reject(err.message)
								}
							}

							if (typeof err.error !=='undefined') {
								commit('SET_CREATE_PRODUCTS_LOADING', false)
								reject(err.error)
							}
						})

					// if image is null, do not use form data
					} else {
						axios.post(`${process.env.VUE_APP_PO_URL}/products/create`, payload)
						.then(res => {
							if (res.status === 200) {
								if (res.data) {
									commit("SET_CREATE_PRODUCTS_LOADING", false)
								}
							}
							resolve()
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
									commit('SET_CREATE_PRODUCTS_LOADING', false)
									reject(err.message)
								}
							}

							if (typeof err.error !=='undefined') {
								commit('SET_CREATE_PRODUCTS_LOADING', false)
								reject(err.error)
							}
						})
					}
				}
			}
			proceed()	
		})
			
	},
	updateProducts: async ({
		commit
	}, payload) => {
		
		let attempt = false
		commit("SET_UPDATE_PRODUCTS_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				let fd = new FormData()
				let getPayloadKeys = Object.keys(payload)

				if (getPayloadKeys.length > 0) {
					getPayloadKeys.map(gpk => {
						fd.append(gpk, payload[gpk])
					})
				}

				if (payload !== null) {
					// if image is not null, use form data
					if (typeof payload.image !== 'undefined' && payload.image !== null) {
						axios.post(`${process.env.VUE_APP_PO_URL}/products/update/${payload.prod_id}`, fd, {
							headers: {
								'Content-Type': 'multipart/form-data'
							}
						})
						.then(res => {
							if (res.status === 200) {
								if (res.data) {
									commit("SET_UPDATE_PRODUCTS_LOADING", false)
								}
							}
							resolve()
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
									commit('SET_UPDATE_PRODUCTS_LOADING', false)
									reject(err)
								}
							}

							if (typeof err.error !=='undefined') {
								commit('SET_UPDATE_PRODUCTS_LOADING', false)
								reject(err.error)
							}
						})

					// if image is null, do not use form data
					} else {
						axios.post(`${process.env.VUE_APP_PO_URL}/products/update/${payload.prod_id}`, payload)
						.then(res => {
							if (res.status === 200) {
								if (res.data) {
									commit("SET_UPDATE_PRODUCTS_LOADING", false)
								}
							}
							resolve()
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
									commit('SET_UPDATE_PRODUCTS_LOADING', false)
									reject(err)
								}
							}

							if (typeof err.error !=='undefined') {
								commit('SET_UPDATE_PRODUCTS_LOADING', false)
								reject(err.error)
							}
						})
					}
				}	
			}
			proceed()
		})		
	},
	deleteProduct: async({
		commit
	}, id) => {
		let attempt = false
		commit("SET_DELETE_PRODUCT_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.delete(`${process.env.VUE_APP_PO_URL}/products/delete/${id}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_DELETE_PRODUCT_LOADING", false)
						}
					}
					resolve()
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
							commit('SET_DELETE_PRODUCT_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_DELETE_PRODUCT_LOADING', false)
						reject(err.error)
					}
				})	
			}
			proceed()	
		})		
	},
	fetchSingleProduct: async ({
		commit,
	}, id) => {		
		let attempt = false
		commit("SET_SINGLE_PRODUCT_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				
				axios.get(`${process.env.VUE_APP_PO_URL}/products/${id}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_SINGLE_PRODUCT_LOADING", false)
							commit("SET_SINGLE_PRODUCT", res.data)
						}
					}
					resolve()
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
							commit('SET_SINGLE_PRODUCT_LOADING', false)
							reject(err)
						}
					}
					if (typeof err.error !=='undefined') {
						commit('SET_SINGLE_PRODUCT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	fetchSearchedProducts: async ({
		commit
	}, payload) => {
		commit("SET_SEARCHED_PRODUCTS_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {				
				axios(payload)
				.then(res => {
					commit("SET_SEARCHED_PRODUCTS_LOADING", false)
					if (res.status === 200) {
						let finalData = {
							products: [],
							current_page: 0,
							total: 0,
							last_page: 0,
							old_page: 1,
							per_page: 35
						}

						if (typeof res.data!=='undefined') {
							let productsData = []

							if (typeof res.data.results!=='undefined' && typeof res.data.results.data!=='undefined') {
								productsData = res.data.results.data
							}								
							
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
								last_page: res.data.results.last_page,
								per_page: res.data.results.per_page,
								old_page: res.data.results.current_page,
								tab: payload.tab
							}

							commit("SET_SEARCHED_PRODUCTS", finalData)

						} else {
							commit("SET_SEARCHED_PRODUCTS", finalData)
						}
					}
					resolve()
				})
				.catch(err => {
					// if (typeof err.message!=='undefined') {
					// 	if ( !attempt ){
					// 		attempt = true
					// 		let t = setInterval(() => {
					// 			if (!store.getters.getIsRefreshing) {
					// 				if ( start == limit) {
					// 					store.dispatch('refreshToken').then(() => {
					// 						attempt = false
					// 						start = 0
					// 						proceed()
					// 					}).catch(e => {
					// 						console.log(e)
					// 						commit("SET_SEARCHED_PRODUCTS_LOADING", false)
					// 						reject('Token has been expired. Please reload the page.')
					// 					})
					// 				} else {
					// 					start++
					// 					proceed()
					// 					attempt = false
					// 				}
					// 				clearInterval(t)
					// 			}
					// 		},300)
					// 	} else {
					// 		commit("SET_SEARCHED_PRODUCTS_LOADING", false)
					// 		reject('Token has been expired. Please reload the page.')
					// 	}
					// }

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {
							commit('SET_SEARCHED_PRODUCTS_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_SEARCHED_PRODUCTS_LOADING', false)
						reject(err.error)
					}
				})	
			}
			proceed()	
		})
	},	
	setSearchedProductsLoading({ commit } , payload) {
		commit("SET_SEARCHED_PRODUCTS_LOADING", payload)
	},
    setSearchedProductsVal({ commit }, payload) {
		commit("setSearchedProductsVal", payload)
	},
	fetchProductsSearchGlobal: async({ commit }, params) => {
		let attempt = false
		commit('SET_PRODUCTS_GLOBAL_SEARCH_LOADING', true)

		const { poBaseUrlState } = state

		return new Promise((resolve, reject) => {
			function proceed() {
				let product = new ProductModel(poBaseUrlState)

				product.search(params).then(res => {
					commit('SET_PRODUCTS_GLOBAL_SEARCH_LOADING', false)
					resolve(res)
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
							commit("SET_PRODUCTS_GLOBAL_SEARCH_LOADING", false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_PRODUCTS_GLOBAL_SEARCH_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	fetchFlags: async ({
		commit,
	},) => {		
		let attempt = false
		commit("SET_FLAGS_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				
				axios.get(`${process.env.VUE_APP_PO_URL}/flag/countries`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_FLAGS_LOADING", false)
							commit("SET_FLAGS", res.data)
						}
					}
					resolve()
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
							commit('SET_FLAGS_LOADING', false)
							reject(err)
						}
					}
					if (typeof err.error !=='undefined') {
						commit('SET_FLAGS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})	
	},
	fetchPdfReport: async ({
		commit
	},payload) => {
		let attempt = false
		commit("SET_PDF_REPORT_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				// axios.get(`${poBaseUrl}/products/get-pdf-report`)
				axios
					.get(
						`${process.env.VUE_APP_PO_URL }/products/get-pdf-report?date=${payload.date}`, {
							responseType: "blob",
							cancelToken: payload.cancelToken
						}
					)
					.then(res => {
						// let results = res.data
						commit("SET_PDF_REPORT_LOADING", false)
						commit("SET_PDF_REPORT_DATA", res.data)
						// var url = window.URL.createObjectURL(new Blob([res.data]));
						// var a = document.createElement("a");
						// a.href = url;
						// a.download = `productReport.pdf`;
						// document.body.appendChild(a);
						// a.click();
						// a.remove();
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
								commit('SET_PDF_REPORT_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_PDF_REPORT_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	fetchCsvReport: async ({
		commit
	}, payload) => {
		let attempt = false
		commit("SET_CSV_REPORT_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				// axios.get(`${poBaseUrl}/products/get-pdf-report`)
				axios
					.get(
						`${process.env.VUE_APP_PO_URL}/products/get-csv-report`, {
						responseType: "blob",
						cancelToken: payload.cancelToken
					}
					)
					.then(res => {
						// let results = res.data
						commit("SET_CSV_REPORT_LOADING", false)
						commit("SET_CSV_REPORT_DATA", res.data)
						resolve("ok")
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
								commit('SET_CSV_REPORT_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_CSV_REPORT_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	fetchProductWarehouseBreakdown: async ({ commit, }, id) => {
		let attempt = false
		commit("SET_PRODUCT_BREAKDOWN_LOADING", true)
		commit("SET_PRODUCT_BREAKDOWN", null)

		return new Promise((resolve, reject) => {
			function proceed() {
				
				axios.get(`${process.env.VUE_APP_PO_URL}/products/${id}/warehouse-breakdown`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_PRODUCT_BREAKDOWN_LOADING", false)
							commit("SET_PRODUCT_BREAKDOWN", res.data.results)
						}
					}
					resolve()
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
							commit('SET_PRODUCT_BREAKDOWN_LOADING', false)
							reject(err)
						}
					}
					if (typeof err.error !=='undefined') {
						commit('SET_PRODUCT_BREAKDOWN_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	fetchProductProjectionReport: async ({ commit, }, id) => {
		let attempt = false
		commit("SET_PRODUCT_REPORT_LOADING", true)
		commit("SET_PRODUCT_REPORT", null)

		return new Promise((resolve, reject) => {
			function proceed() {
				
				axios.get(`${process.env.VUE_APP_PO_URL}/products/${id}/report`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_PRODUCT_REPORT_LOADING", false)
							commit("SET_PRODUCT_REPORT", res.data.data)
						}
					}
					resolve()
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
							commit('SET_PRODUCT_REPORT_LOADING', false)
							reject(err)
						}
					}
					if (typeof err.error !=='undefined') {
						commit('SET_PRODUCT_REPORT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	fetchArchivedProducts: async ({ commit, }, page) => {
		let attempt = false
		commit("SET_ARCHIVED_PRODUCTS_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				
				axios.get(`${process.env.VUE_APP_PO_URL}/archive-products?page=${page}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_ARCHIVED_PRODUCTS_LOADING", false)

							let results = res.data.results

							let finalData = {
								data: results.data,
								current_page: results.current_page,
								total: results.total,
								old_page: results.current_page,
								last_page: results.last_page,
								per_page: results.per_page
							}

							commit("SET_ARCHIVED_PRODUCTS", finalData)
						}
					}
					resolve()
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
							commit('SET_ARCHIVED_PRODUCTS_LOADING', false)
							reject(err)
						}
					}
					if (typeof err.error !=='undefined') {
						commit('SET_ARCHIVED_PRODUCTS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	setArchiveProduct: async ({ commit, }, id) => {
		let attempt = false
		commit("SET_DO_ARCHIVED_PRODUCTS_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				
				axios.get(`${process.env.VUE_APP_PO_URL}/products/${id}/archive`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_DO_ARCHIVED_PRODUCTS_LOADING", false)
						}
					}
					resolve()
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
							commit('SET_DO_ARCHIVED_PRODUCTS_LOADING', false)
							reject(err)
						}
					}
					if (typeof err.error !=='undefined') {
						commit('SET_DO_ARCHIVED_PRODUCTS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	setUnarchiveProduct: async ({ commit, }, id) => {
		let attempt = false
		commit("SET_DO_UNARCHIVED_PRODUCTS_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				
				axios.get(`${process.env.VUE_APP_PO_URL}/products/${id}/unarchive`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_DO_UNARCHIVED_PRODUCTS_LOADING", false)
						}
					}
					resolve()
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
							commit('SET_DO_UNARCHIVED_PRODUCTS_LOADING', false)
							reject(err)
						}
					}
					if (typeof err.error !=='undefined') {
						commit('SET_DO_UNARCHIVED_PRODUCTS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},	
	fetchSearchedArchiveProducts: async ({
		commit
	}, payload) => {
		commit("SET_SEARCHED_ARCHIVED_PRODUCTS_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {				
				axios(payload)
				.then(res => {
					commit("SET_SEARCHED_ARCHIVED_PRODUCTS_LOADING", false)
					if (res.status === 200) {
						let results = res.data.results

						let finalData = {
							data: results.data,
							current_page: results.current_page,
							total: results.total,
							old_page: results.current_page,
							last_page: results.last_page,
							per_page: results.per_page,
							tab: payload.tab
						}

						commit("SET_SEARCHED_ARCHIVED_PRODUCTS", finalData)
					}
					resolve()
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
							}, 300)
						} else {
							commit('SET_SEARCHED_ARCHIVED_PRODUCTS_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_SEARCHED_ARCHIVED_PRODUCTS_LOADING', false)
						reject(err.error)
					}
				})	
			}
			proceed()	
		})
	},
	setSearchedArchivedProductsLoading({ commit } , payload) {
		commit("SET_SEARCHED_ARCHIVED_PRODUCTS_LOADING", payload)
	},
	setSearchedArchivedProductsVal({ commit }, payload) {
		commit("setSearchedArchivedProductsVal", payload)
	},
	setArchivedProductsVal({ commit }, payload) {
		commit("setArchivedProductsVal", payload)
	},
	fetchProductContacts: async ({ commit }) => {		
		let attempt = false
		commit("SET_PRODUCT_CONTACTS_LOADING", true)
		commit("SET_PRODUCT_CONTACTS", [])

		return new Promise((resolve, reject) => {
			function proceed() {				
				axios.get(`v2/contacts`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_PRODUCT_CONTACTS_LOADING", false)
							commit("SET_PRODUCT_CONTACTS", res.data)
						}
					}
					resolve()
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
							commit('SET_PRODUCT_CONTACTS_LOADING', false)
							reject(err)
						}
					}
					if (typeof err.error !=='undefined') {
						commit('SET_PRODUCT_CONTACTS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
	},
	setProductContactsLoading({ commit } , payload) {
		commit("SET_PRODUCT_CONTACTS_LOADING", payload)
	},
	fetchPdfReportProduct: async ({
		commit
	},payload) => {
		let attempt = false
		commit("SET_PDF_REPORT_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
					.get(
						`${process.env.VUE_APP_PO_URL }/products/get-pdf?date=${payload.date}`, {
							responseType: "blob",
							cancelToken: payload.cancelToken
						}
					)
					.then(res => {
						commit("SET_PDF_REPORT_LOADING", false)
						commit("SET_PDF_REPORT_DATA", res.data)
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
								commit('SET_PDF_REPORT_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_PDF_REPORT_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	fetchCsvReportProduct: async ({
		commit
	}, payload) => {
		let attempt = false
		commit("SET_CSV_REPORT_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
					.get(
						`${process.env.VUE_APP_PO_URL}/products/get-csv`, {
							responseType: "blob",
							cancelToken: payload.cancelToken
						}
					)
					.then(res => {
						commit("SET_CSV_REPORT_LOADING", false)
						commit("SET_CSV_REPORT_DATA", res.data)
						resolve("ok")
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
								commit('SET_CSV_REPORT_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_CSV_REPORT_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
}

const methods = {
	testing: () => {
		alert('testing')
	}
}

const mutations = {
	setReset: (state, payload) => {
		state.reset = payload
	},
	setProduct: (state, payload) => {
		state.currentProductSelected = payload
	},
	SET_PRODUCTS: (state, payload) => {
		state.products = payload
	},
	SET_PRODUCTS_LOADING: (state, payload) => {
		state.productsLoading = payload
	},
	SET_CREATE_PRODUCTS: (state, payload) => {
		state.createProducts = payload
	},
	SET_CREATE_PRODUCTS_LOADING: (state, payload) => {
		state.createProductsLoading = payload
	},
	SET_UPDATE_PRODUCTS: (state, payload) => {
		state.updateProduct = payload
	},
	SET_UPDATE_PRODUCTS_LOADING: (state, payload) => {
		state.updateProductLoading = payload
	},
	SET_DELETE_PRODUCT_LOADING: (state, payload) => {
		state.deleteProductLoading = payload
	},
	SET_SINGLE_PRODUCT: (state, payload) => {
		state.singleProduct = payload
	},
	SET_SINGLE_PRODUCT_LOADING: (state, payload) => {
		state.singleProductLoading = payload
	},
	SET_ERROR: (state, payload) => {
		state.error = payload
	},
	SET_SEARCHED_PRODUCTS: (state, payload) => {
		state.searchedProducts = payload
	},
	SET_SEARCHED_PRODUCTS_LOADING: (state, payload) => {
		state.searchedProductsLoading = payload
	},
	setSearchedProductsVal: (state, payload) => {
        let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            products: payload
        }

        state.searchedProducts = locationDefaultData
    },
	SET_PRODUCTS_GLOBAL_SEARCH_LOADING: (state, payload) => {
		state.productsGlobalSearchLoading = payload
	},
	SET_FLAGS: (state, payload) => {
		state.flags = payload
	},
	SET_FLAGS_LOADING: (state, payload) => {
		state.flagsLoading = payload
	},
	SET_PDF_REPORT_LOADING: (state, payload) => {
		state.pdfReportLoading = payload
	},
	SET_CSV_REPORT_LOADING: (state, payload) => {
		state.csvReportLoading = payload
	},
	SET_PDF_REPORT_DATA:(state,payload)=>{
		state.setPdfReportData = payload
	},
	SET_CSV_REPORT_DATA: (state, payload) => {
		state.setCsvReportData = payload
	},
	SET_PRODUCT_BREAKDOWN: (state, payload) => {
		state.productWarehouseBreakdown = payload
	},
	SET_PRODUCT_BREAKDOWN_LOADING: (state, payload) => {
		state.productWarehouseBreakdownLoading = payload
	},
	SET_PRODUCT_REPORT: (state, payload) => {
		state.productProjectionReport = payload
	},
	SET_PRODUCT_REPORT_LOADING: (state, payload) => {
		state.productProjectionReportLoading = payload
	},
	SET_ARCHIVED_PRODUCTS: (state, payload) => {
		state.archivedProducts = payload
	},
	SET_ARCHIVED_PRODUCTS_LOADING: (state, payload) => {
		state.archivedProductsLoading = payload
	},
	SET_DO_ARCHIVED_PRODUCTS_LOADING: (state, payload) => {
		state.setArchivedProductsLoading = payload
	},
	SET_DO_UNARCHIVED_PRODUCTS_LOADING: (state, payload) => {
		state.setUnarchivedProductsLoading = payload
	},
	SET_SEARCHED_ARCHIVED_PRODUCTS: (state, payload) => {
		state.searchedArchivedProducts = payload
	},
	SET_SEARCHED_ARCHIVED_PRODUCTS_LOADING: (state, payload) => {
		state.searchedArchivedProductsLoading = payload
	},
	setSearchedArchivedProductsVal: (state, payload) => {
        let defaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            data: payload
        }

        state.searchedArchivedProducts = defaultData
    },
	setArchivedProductsVal: (state, payload) => {
		state.archivedProducts = payload
	},
	SET_PRODUCT_CONTACTS: (state, payload) => {
		state.productContacts = payload
	},
	SET_PRODUCT_CONTACTS_LOADING: (state, payload) => {
		state.productContactsLoading = payload
	}
}

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations,
	methods
}
