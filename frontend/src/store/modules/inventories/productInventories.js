import axios from "axios"
import store from '../../'

const state = {
    productInventory: [],
    productInventoryLoading: false,
    productInventorySearched: [],
    productInventorySearchedLoading: false,
    productInventoryTab: 0,
	allCategoryDropdownLists: [],
	// 3pl
	productInventory3pl: [],
	productInventory3plLoading: false,
	addInventoryProductsLoading: false,
	productInventoryLogs: [],
	productInventoryLogsLoading: false,
	productInventoryBreakdownLoading:false,
	productInventoryBreakdown:[],
	isShowAddInventoryDialog: false,
	productInventoryHeader: [],
	productInventoryHeaderDefault: [],
	// search and filter
	productInventoryFilterLoading: false,
	productInventoryFiltered:[],
	inventoryReport: null,
	inventoryReportLoading: false,
	// search and filter of Reports
	searchedReportsLoading:false,
	searchedReports:[],
	filteredReportsLoading:false,
	filteredReports:[],
	editPreferredQtyLoading: false,
	transferInventoryProductLoading:false,
	adjustInventory:[],
	adjustInventoryLoading:false,
	adjustInventoryMultipleLoading:false,
	adjustInventoryMultiple:[],
	inventoryActivityLogs:[],
	inventoryActivityLogsLoading:false
}

const getters = {
    getProductInventory: state => state.productInventory,
    getProductInventoryLoading: state => state.productInventoryLoading,
    getProductInventorySearched: state => state.productInventorySearched,
    getProductInventorySearchedLoading: state => state.productInventorySearchedLoading,
	getProductInventoryTab: state => state.productInventoryTab,
	getAllCategoryDropdownLists: state => state.allCategoryDropdownLists,
	// 3pl
	getProductInventory3pl: state => state.productInventory3pl,
    getProductInventory3plLoading: state => state.productInventory3plLoading,
	getAddInventoryProductsLoading: state => state.addInventoryProductsLoading,
	getProductInventoryLogs: state => state.productInventoryLogs,
	getProductInventoryLogsLoading: state => state.productInventoryLogsLoading,
	getProductInventoryBreakdownLoading: state => state.productInventoryBreakdownLoading,
	getProductInventoryBreakdown: state => state.productInventoryBreakdown,
	getIsShowAddInventoryDialog: state => state.isShowAddInventoryDialog,
	getProductInventoryHeader: state => state.productInventoryHeader,
	getProductInventoryHeaderDefault: state => state.productInventoryHeaderDefault,
	// search and filter
	getProductInventoryFilterLoading: state => state.productInventoryFilterLoading,
	getProductInventoryFiltered: state => state.productInventoryFiltered,
	getInventoryReport: state => state.inventoryReport,
	getInventoryReportLoading: state => state.inventoryReportLoading,
	// search and filter of Reports
	getSearchedReports:state => state.searchedReports,
	getSearchedReportsLoading:state => state.searchedReportsLoading,
	getFilteredReportsLoading:state => state.filteredReportsLoading,
	getFilteredReports:state => state.filteredReports,
	getEditPreferredQtyLoading: state => state.editPreferredQtyLoading,
	getAdjustInventory: state => state.adjustInventory,
	getAdjustInventoryLoading: state => state.adjustInventoryLoading,
	getAdjustInventoryMultipleLoading: state => state.adjustInventoryMultipleLoading,
	getAdjustInventoryMultiple: state => state.adjustInventoryMultiple,
	getInventoryActivityLogs: state => state.inventoryActivityLogs,
	getInventoryActivityLogsLoading: state => state.inventoryActivityLogsLoading,
	getTransferInventoryProductLoading: state => state.transferInventoryProductLoading
}

const poBaseUrl = process.env.VUE_APP_PO_URL

const actions = {
    fetchProductInventories: async ({
		commit
	}, payload ) => {		
		let attempt = false
		commit("SET_PRODUCT_INVENTORY_LOADING", true)
		// commit("SET_PRODUCT_INVENTORY", [])

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouse/${payload.id}/products?page=${payload.page}`,{ cancelToken: payload.cancelToken })
				.then(res => {
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results  
                                commit("SET_PRODUCT_INVENTORY_LOADING", false)
    
                                if (typeof results !== 'undefined') { 
                                    let productInventory = {
                                        currentTab: 1,
                                        current_page: results.current_page,
										last_page: results.last_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        items: results.data
                                    }
                                    
                                    commit("SET_PRODUCT_INVENTORY", productInventory)
                                }

                                resolve()
                            }
                        }
                    }
				})
				.catch(err => {
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
							commit('SET_PRODUCT_INVENTORY_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PRODUCT_INVENTORY_LOADING', false)
						reject(err.error)
					}
				})	
			}
			proceed()
		})	
	},
    fetchProductInventoriesSearched: async ({
		commit
	}, payload ) => {		
		let attempt = false
		commit("SET_PRODUCT_INVENTORY_SEARCHED_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios(payload)
				.then(res => {
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results
                                commit("SET_PRODUCT_INVENTORY_SEARCHED_LOADING", false)
    
                                if (typeof results !== 'undefined') { 
                                    let productInventory = {
                                        currentTab: 1,
                                        current_page: results.current_page,
                                        old_page: results.current_page,
										last_page: results.last_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        items: results.data,
                                        tab: payload.tab
                                    }
                                    
                                    commit("SET_PRODUCT_INVENTORY_SEARCHED", productInventory)
                                }

                                resolve()
                            }
                        }
                    }
				})
				.catch(err => {
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
							commit('SET_PRODUCT_INVENTORY_SEARCHED_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PRODUCT_INVENTORY_SEARCHED_LOADING', false)
						reject(err.error)
					}					
				})	
			}
			proceed()
		})	
	},
    setSearchedInventoryProductsLoading({ commit } , payload) {
		commit("SET_PRODUCT_INVENTORY_SEARCHED_LOADING", payload)
	},
    setInventoryProductSearchedVal({ commit }, payload) {
		commit("setInventoryProductSearchedVal", payload)
	},
    setProductInventoryTab( { commit }, payload) {
        commit("setProductInventoryTab", payload)
    },
	setProductEmptyData( { commit }, payload) {
        commit("setProductEmptyData", payload)
    },
	setAllCategoryDropdownLists( { commit }, payload) {
        commit("SET_ALL_CATEGORY_DROPDOWN_LISTS", payload)
    },
	// 3PL
	fetchProductInventories3pl: async ({
		commit
	}, payload ) => {		
		let attempt = false
		commit("SET_PRODUCT_INVENTORY_3PL_LOADING", true)

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouse-3pl/${payload.id}/products?page=${payload.page}`,{cancelToken: payload.cancelToken })
				.then(res => {
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results  
                                commit("SET_PRODUCT_INVENTORY_3PL_LOADING", false)
    
                                if (typeof results !== 'undefined') { 
                                    let productInventory = {
                                        currentTab: 1,
                                        current_page: results.current_page,
										last_page: results.last_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        items: results.data
                                    }
                                    
                                    commit("SET_PRODUCT_INVENTORY_3PL", productInventory)
                                }

                                resolve()
                            }
                        }
                    }
				})
				.catch(err => {
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
							commit('SET_PRODUCT_INVENTORY_3PL_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PRODUCT_INVENTORY_3PL_LOADING', false)
						reject(err.error)
					}
				})	
			}
			proceed()
		})	
	},	
	addProductsInventories: async ({
		commit
	}, payload) => {
		return new Promise((resolve, reject) => {
			let attempt = false
			
			function proceed() {
				commit("SET_ADD_INVENTORY_PRODUCTS", true)
				let { warehouse_id, ...otherProps } = payload
				payload = { ...otherProps }
							
				axios.post(`${poBaseUrl}/warehouse-3pl/${warehouse_id}/add-inventory-products`, payload)
				.then(res => {
					commit("SET_ADD_INVENTORY_PRODUCTS", false)
					if (typeof res!=='undefined' && res.status === 200) {
						resolve(res.data)
					}
				})
				.catch(err => {
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
							commit('SET_ADD_INVENTORY_PRODUCTS', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_ADD_INVENTORY_PRODUCTS', false)
						reject(err.error)
					}
				})	
			}
			proceed()
			
		})
	},
	fetchProductInventoryLogs: async ({
		commit
	}, payload ) => {		
		let attempt = false
		commit("SET_PRODUCT_INVENTORY_LOGS_LOADING", true)
		
		let  { id, pid, page } = payload

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouse/${id}/products/${pid}/get-inventory-product-logs?page=${page}`)
				.then(res => {
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results  
                                commit("SET_PRODUCT_INVENTORY_LOGS_LOADING", false)
    
                                if (typeof results !== 'undefined') { 
                                    let productInventoryLogs = {
                                        currentTab: 1,
                                        current_page: results.current_page,
										last_page: results.last_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        items: results.data
                                    }
                                    
                                    commit("SET_PRODUCT_INVENTORY_LOGS", productInventoryLogs)
                                }

                                resolve()
                            }
                        }
                    }
				})
				.catch(err => {
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
							commit('SET_PRODUCT_INVENTORY_LOGS_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_PRODUCT_INVENTORY_LOGS_LOADING', false)
						reject(err.error)
					}
				})	
			}
			proceed()
		})	
	},
	setLogsData( { commit }, payload) {
        commit("SET_PRODUCT_INVENTORY_LOGS", payload)
		commit("SET_PRODUCT_INVENTORY_BREAKDOWN",payload)
    },
	fetchInventoryBreakdown: async ({
		commit
	}, payload) => {
		let attempt = false
		commit("SET_PRODUCT_INVENTORY_BREAKDOWN_LOADING", true)

		let { id, pid, page } = payload

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouse/${id}/products/${pid}/get-inventory-breakdown?page=${page}`)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									let results = res.data
									commit("SET_PRODUCT_INVENTORY_BREAKDOWN_LOADING", false)

									if (typeof results !== 'undefined') {
										let productInventoryBreakdown = {
											currentTab: 1,
											current_page: results.current_page,
											last_page: results.last_page,
											old_page: results.current_page,
											total: results.total,
											per_page: results.current_page_total,
											// prev_page_url: results.prev_page_url,
											// next_page_url: results.next_page_url,
											items: results.results
										}

										commit("SET_PRODUCT_INVENTORY_BREAKDOWN", productInventoryBreakdown)
									}

									resolve()
								}
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
								commit('SET_PRODUCT_INVENTORY_BREAKDOWN_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_PRODUCT_INVENTORY_BREAKDOWN_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	setIsAddInventoryShow({ commit }, payload) {
		commit("setIsAddInventoryShow", payload)
	},
	setProductInventoryHeader({ commit }, payload) {
		commit("setProductInventoryHeader", payload)
	},
	setProductInventoryHeaderDefault({ commit }, payload) {
		commit("setProductInventoryHeaderDefault", payload)
	},

	// search an filter
	setFilteredInventoryProductLoading({ commit }, payload) {
		commit("SET_FILTERED_INVENTORY_LOADING", payload)
	},
	setInvnetoryFilteredVal({ commit }, payload) {
		commit("setInvnetoryFilteredVal", payload)
	},
	fetchProductInventoryFiltered: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_FILTERED_INVENTORY_LOADING", true)

				axios(payload)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									let results = res.data.results
									commit("SET_FILTERED_INVENTORY_LOADING", false)
									commit("SET_FILTERED_CUSTOMER_INVENTORY", results)

									let productInventoryFilter = {
										currentTab: 1,
										current_page: results.current_page,
										old_page: results.current_page,
										last_page: results.last_page,
										total: results.total,
										per_page: results.per_page,
										prev_page_url: results.prev_page_url,
										next_page_url: results.next_page_url,
										items: results.data,
										tab: payload.tab
									}

									commit("SET_FILTERED_CUSTOMER_INVENTORY", productInventoryFilter)
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
								commit('SET_FILTERED_INVENTORY_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_FILTERED_INVENTORY_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	// get inventory report 
	fetchInventoryReport: async ({ commit },payload) => {
		let attempt = false
		commit("SET_PRODUCT_INVENTORY_REPORT_LOADING", true)
		commit("SET_PRODUCT_INVENTORY_REPORT", [])

		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/get-inventory-report?page=${payload}`)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									let results = res.data.results
									commit("SET_PRODUCT_INVENTORY_REPORT_LOADING", false)

									if (typeof results !== 'undefined') {
										let inventoryReport = {
											currentTab: 1,
											current_page: results.current_page,
											last_page: results.last_page,
											old_page: results.current_page,
											total: results.total,
											per_page: results.last_page,
											items: results.data
										}

										commit("SET_PRODUCT_INVENTORY_REPORT", inventoryReport)
									}

									resolve()
								}
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
								commit('SET_PRODUCT_INVENTORY_REPORT_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_PRODUCT_INVENTORY_REPORT_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	setSearchedReportLoading({ commit }, payload) {
		commit("SET_SEARCHED_PEPORTS_LOADING", payload)
	},
	setReportSearchedVal({ commit }, payload) {
		commit("setReportSearchedVal", payload)
	},
	fetchSearchedReports: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_SEARCHED_PEPORTS_LOADING", true)

				axios(payload)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									let results = res.data.results
									commit("SET_SEARCHED_PEPORTS_LOADING", false)

									let pagination = {
										currentTab: 1,
										current_page: results.current_page,
										total: results.total,
										old_page: 1,
										last_page: results.last_page,
										per_page: results.per_page,
										tab: payload.tab,
										items: results.data
									}

									commit("SET_SEARCHED_REPORTS", pagination)
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
								commit('SET_SEARCHED_PEPORTS_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_SEARCHED_PEPORTS_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	setFilteredReportsLoading({ commit }, payload) {
		commit("SET_FILTERED_PEPORTS_LOADING", payload)
	},
	setReportFilteredVal({ commit }, payload) {
		commit("setReportFilteredVal", payload)
	},
	fetchFilterReports: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_FILTERED_PEPORTS_LOADING", true)

				axios(payload)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									let results = res.data.results
									commit("SET_FILTERED_PEPORTS_LOADING", false)

									let pagination = {
										currentTab: 1,
										current_page: results.current_page,
										total: results.total,
										old_page: 1,
										last_page: results.last_page,
										per_page: results.per_page,
										tab: payload.tab,
										items: results.data
									}

									commit("SET_FILTERED_REPORTS", pagination)
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
								commit('SET_FILTERED_PEPORTS_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_FILTERED_PEPORTS_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	// edit preferred qty
	updatePreferredQtyAction: async ({
		commit
	}, payload) => {
		let { inventory_product_id, ...otherProps } = payload
		payload = { ...otherProps }

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_EDIT_PREFERRED_QTY_LOADING", true)

				axios.put(`${poBaseUrl}/inventory/${inventory_product_id}/edit-preferred`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_EDIT_PREFERRED_QTY_LOADING", false)
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
							commit('SET_EDIT_PREFERRED_QTY_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_EDIT_PREFERRED_QTY_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	updatePreferredQtyMultipleAction: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_EDIT_PREFERRED_QTY_LOADING", true)

				axios.put(`${poBaseUrl}/inventory/edit-preferred-multiple`, payload)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_EDIT_PREFERRED_QTY_LOADING", false)
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
							commit('SET_EDIT_PREFERRED_QTY_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_EDIT_PREFERRED_QTY_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	transferInventoryProductApi: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_TRANSFER_INVENTORY_PRODUCT_LOADING", true)

				axios.post(`${poBaseUrl}/v2/inventory/warehouse-transfer`, payload)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									commit("SET_TRANSFER_INVENTORY_PRODUCT_LOADING", false)
								}
							}
						}
						resolve()
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
								commit('SET_TRANSFER_INVENTORY_PRODUCT_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_TRANSFER_INVENTORY_PRODUCT_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	adjustInventoryApi: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_ADJUST_INVENTORY_LOADING", true)

				axios.post(`${poBaseUrl}/v2/inventory/${payload.inventory_product_id}/adjust`, payload.item)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									commit("SET_ADJUST_INVENTORY_LOADING", false)
								}
							}
						}
						resolve()
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
								commit('SET_ADJUST_INVENTORY_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_ADJUST_INVENTORY_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	adjustMultipleInventoryApi: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_ADJUST_MULTIPLE_INVENTORY_LOADING", true)

				axios.post(`${poBaseUrl}/v2/inventory/adjust-multiple`, payload)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									commit("SET_ADJUST_MULTIPLE_INVENTORY_LOADING", false)
								}
							}
						}
						resolve()
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
								commit('SET_ADJUST_MULTIPLE_INVENTORY_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_ADJUST_MULTIPLE_INVENTORY_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	fetchActivityLogs: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_ACTIVITY_LOGS_LOADING", true)

				axios.get(`${poBaseUrl}/v2/inventory/${payload.id}/activity-logs?page=${payload.page}`)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
									let results = res.data.results
									commit("SET_ACTIVITY_LOGS_LOADING", false)

									let pagination = {
										currentTab: 1,
										current_page: results.current_page,
										total: results.total,
										old_page: 1,
										last_page: results.last_page,
										per_page: results.per_page,
										tab: payload.tab,
										items: results.data
									}

									commit("SET_ACTIVITY_LOGS", pagination)
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
								commit('SET_ACTIVITY_LOGS_LOADING', false)
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_ACTIVITY_LOGS_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
}

const mutations = {
    SET_PRODUCT_INVENTORY: (state, payload) => {
		state.productInventory = payload
	},
	SET_PRODUCT_INVENTORY_LOADING: (state, payload) => {
		state.productInventoryLoading = payload
	},
    SET_PRODUCT_INVENTORY_SEARCHED: (state, payload) => {
		state.productInventorySearched = payload
	},
	SET_PRODUCT_INVENTORY_SEARCHED_LOADING: (state, payload) => {
		state.productInventorySearchedLoading = payload
	},
    setInventoryProductSearchedVal: (state, payload) => {
        // state.productInventorySearched = payload

		let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            items: payload
        }

        state.productInventorySearched = locationDefaultData
    },
	// filter customer 
	SET_FILTERED_INVENTORY_LOADING: (state, payload) => {
		state.productInventoryFilterLoading = payload
	},
	SET_FILTERED_CUSTOMER_INVENTORY: (state, payload) => {
		state.productInventoryFiltered = payload
	},
	setInvnetoryFilteredVal: (state, payload) => {
		let locationDefaultData = {
			currentTab: 1,
			current_page: 1,
			old_page: 1,
			total: 0,
			per_page: 0,
			data: payload
		}

		state.productInventoryFiltered = locationDefaultData
	},
    setProductInventoryTab: (state, payload) => {
        state.productInventoryTab = payload
    },
	setProductEmptyData: (state, payload) => {
		state.productInventory = payload
		state.productInventory3pl = payload
	},
	SET_ALL_CATEGORY_DROPDOWN_LISTS: (state, payload) => {
		state.allCategoryDropdownLists = payload
	},
	// 3PL
	SET_PRODUCT_INVENTORY_3PL: (state, payload) => {
		state.productInventory3pl = payload
	},
	SET_PRODUCT_INVENTORY_3PL_LOADING: (state, payload) => {
		state.productInventory3plLoading = payload
	},
	SET_ADD_INVENTORY_PRODUCTS: (state, payload) => {
		state.addInventoryProductsLoading = payload
	},
	SET_PRODUCT_INVENTORY_LOGS: (state, payload) => {
		state.productInventoryLogs = payload
	},
	SET_PRODUCT_INVENTORY_BREAKDOWN: (state, payload) => {
		state.productInventoryBreakdown = payload
	},
	SET_PRODUCT_INVENTORY_BREAKDOWN_LOADING: (state, payload) => {
		state.productInventoryBreakdownLoading = payload
	},
	SET_PRODUCT_INVENTORY_LOGS_LOADING: (state, payload) => {
		state.productInventoryLogsLoading = payload
	},
	setIsAddInventoryShow: (state, payload) => {
		state.isShowAddInventoryDialog = payload
	},
	setProductInventoryHeader: (state, payload) => {
		state.productInventoryHeader = payload
	},
	setProductInventoryHeaderDefault: (state, payload) => {
		state.productInventoryHeaderDefault = payload
	},
	SET_PRODUCT_INVENTORY_REPORT: (state, payload) => {
		state.inventoryReport = payload
	},
	SET_PRODUCT_INVENTORY_REPORT_LOADING: (state, payload) => {
		state.inventoryReportLoading = payload
	},
	// search Outbound
	SET_SEARCHED_REPORTS: (state, payload) => {
		state.searchedReports = payload
	},
	SET_SEARCHED_PEPORTS_LOADING: (state, payload) => {
		state.searchedReportsLoading = payload
	},
	setReportSearchedVal: (state, payload) => {
		let locationDefaultData = {
			currentTab: 1,
			current_page: 1,
			old_page: 1,
			total: 0,
			per_page: 0,
			data: payload
		}

		state.searchedReports = locationDefaultData
	},
	SET_FILTERED_PEPORTS_LOADING: (state, payload) => {
		state.filteredReportsLoading = payload
	},
	SET_FILTERED_REPORTS: (state, payload) => {
		state.filteredReports = payload
	},
	setReportFilteredVal: (state, payload) => {
		let locationDefaultData = {
			currentTab: 1,
			current_page: 1,
			old_page: 1,
			total: 0,
			per_page: 0,
			data: payload
		}

		state.filteredReports = locationDefaultData
	},
	SET_EDIT_PREFERRED_QTY_LOADING: (state, payload) => {
		state.editPreferredQtyLoading = payload
	},
	SET_TRANSFER_INVENTORY_PRODUCT_LOADING: (state, payload) => {
		state.transferInventoryProductLoading = payload
	},
	SET_ADJUST_INVENTORY_LOADING: (state, payload) => {
		state.adjustInventoryLoading = payload
	},
	SET_ADJUST_MULTIPLE_INVENTORY_LOADING: (state, payload) => {
		state.adjustInventoryMultipleLoading = payload
	},
	SET_ACTIVITY_LOGS_LOADING: (state, payload) => {
		state.inventoryActivityLogsLoading = payload
	},
	SET_ACTIVITY_LOGS: (state, payload) => {
		state.inventoryActivityLogs = payload
	},
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}