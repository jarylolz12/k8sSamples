import axios from "axios"
import store from '../../'

const state = {
    locations: [],
    locationsLoading: false,
    createLocationsLoading: false,
    singleLocation: null,
    singleLocationLoading: false,
    locationUpdateLoading: false,
    deleteLocationLoading: false,
    // 
    storageLocations: null,
    storageLocationsLoading: false,
    gateLocations: null,
    gateLocationsLoading: false,
    locationsTab: {
        typeTab: 0, // storage or gate tab
        typeSubTab: 0 // all, empty or filled tab
    },
    storageAllPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    storageFilledPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    storageEmptyPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    allStockPickableStaggingPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    storagePackingPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    storageSpecialPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    storageOthersPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    gateAllPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    gateFilledPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    gateEmptyPagination: {
        currentTab: 1,
        current_page: 1,
        old_page: 1,
        total: 0,
        per_page: 0,
        last_page: 1,
        items: []
    },
    allStorageLocationsLoading: false,
    filledStorageLocationsLoading: false,
    emptyStorageLocationsLoading: false,
    allStockPickableStaggingLoading:false,
    allpackingLoading:false,
    allspecialLoading: false,
    allothersLoading: false,
    allGateLocationsLoading: false,
    filledGateLocationsLoading: false,
    emptyGateLocationsLoading: false,
    // searchedLocations: {
    //     currentTab: 1,
    //     current_page: 1,
    //     old_page: 1,
    //     total: 0,
    //     per_page: 0,
    //     items: [],
    //     currentTabName: ''
    // },
    searchedLocations: [],
    searchedLocationsLoading: false,
    // filter and search
	filteredLocationsLoading: false,
	filteredLocations: [],
    // location types
    locationsTypes: [],
    locationsTypesLoading: false,
    // location products for default sku values
    locationsProductsForDeafultSkuLoading:false,
    locationsProductsForDeafultSku:[],
    allLocationProductListsDropdownData:[]
}

const getters = {
    getLocations: state => state.locations,
    getLocationsLoading: state => state.locationsLoading,
    getCreateLocationsLoading: state => state.createLocationsLoading,
    getSingleLocation: state => state.singleLocation,
    getSingleLocationLoading: state => state.singleLocationLoading,
    getUpdateLocationLoading: state => state.locationUpdateLoading,
    getDeleteLocationLoading: state => state.deleteLocationLoading,
    // 
    getStorageLocations: state => state.storageLocations,
    getStorageLocationsLoading: state => state.storageLocationsLoading,
    getGateLocations: state => state.gateLocations,
    getGateLocationsLoading: state => state.gateLocationsLoading,
    // storage locations
    getStorageAllPagination: state => state.storageAllPagination,
    getStorageFilledPagination: state => state.storageFilledPagination,
    getStorageEmptyPagination: state => state.storageEmptyPagination,
    getAllStockPickableStaggingPagination: state => state.allStockPickableStaggingPagination,
    getStoragePackingPagination: state => state.storagePackingPagination,
    getStorageSpecialPagination: state => state.storageSpecialPagination,
    getStorageOthersPagination: state => state.storageOthersPagination,
    // storage loadings
    getStorageAllPaginationLoading: state => state.allStorageLocationsLoading,
    getStorageFilledPaginationLoading: state => state.filledStorageLocationsLoading,
    getStorageEmptyPaginationLoading: state => state.emptyStorageLocationsLoading,
    getAllStockPickableStaggingLoading: state => state.allStockPickableStaggingLoading,
    getAllpackingLoading: state => state.allpackingLoading,
    getAllspecialLoading: state => state.allspecialLoading,
    getAllothersLoading: state => state.allothersLoading,
    // gate locations
    getGateAllPagination: state => state.gateAllPagination,
    getGateFilledPagination: state => state.gateFilledPagination,
    getGateEmptyPagination: state => state.gateEmptyPagination,
    // gate loadings
    getGateAllPaginationLoading: state => state.allGateLocationsLoading,
    getGateFilledPaginationLoading: state => state.filledGateLocationsLoading,
    getGateEmptyPaginationLoading: state => state.emptyGateLocationsLoading,
    // searched locations
    getSearchedLocations: state => state.searchedLocations,
    getSearchedLocationsLoading: state => state.searchedLocationsLoading,
    // search and filter
	getFilteredLocationsLoading: state => state.filteredLocationsLoading,
	getFilteredLocations: state => state.filteredLocations,
    // types
    getLocationTypes: state => state.locationsTypes,
    getLocationTypesLoading: state => state.locationsTypesLoading,
    // location products for default sku values
    getLocationsProductsForDeafultSkuLoading: state => state.locationsProductsForDeafultSkuLoading,
    getLocationsProductsForDeafultSku: state => state.locationsProductsForDeafultSku,
    getAllLocationProductListsDropdownData: state => state.allLocationProductListsDropdownData
}

const poBaseUrl = process.env.VUE_APP_PO_URL

const actions = {
    fetchLocations: async ({
        commit
    }, payload) => {
        commit("SET_LOCATIONS", [])
        commit("SET_LOCATIONS_LOADING", true)
        let attempt = false
        async function proceed() {
            await axios.get(`${poBaseUrl}/warehouses/${payload.wid}/locations?page=${payload.page}`)
            .then(res => {
                if (typeof res.status !== 'undefined') {
                    if (res.status === 200) {
                        if (res.data) {
                            commit("SET_LOCATIONS_LOADING", false)
                            commit("SET_LOCATIONS", res.data)
                        }
                    }
                }
            })
            .catch(err => {
                if (typeof err.message!=='undefined') {
                    if ( !attempt ){
                        attempt = true
                        let t =setInterval(() => {
                            if (!store.getters.getIsRefreshing) {
                                proceed()
                                clearInterval(t)
                            }
                        },300)
                    } else {
                        commit('SET_LOCATIONS_LOADING', false)
                        // return Promise.reject('Token has been expired. Please reload the page.')
                        return Promise.reject(err)
                    }
                } else {
                    commit('SET_LOCATIONS_LOADING', false)
                    return Promise.reject(err.error)
                }
            })    
        }
        proceed()
        
    },
    createLocations: async ({
        commit
    }, payload) => {
        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                let { warehouse_id, ...otherProps } = payload

                payload = { ...otherProps }

                //set loading to true
                commit("SET_CREATE_LOCATIONS_LOADING", true)

                axios.post(`${poBaseUrl}/warehouses/${warehouse_id}/locations/create`, payload)
                .then(res => {
                    commit("SET_CREATE_LOCATIONS_LOADING", false)
                    if (typeof res !== 'undefined' && res.status === 200) {
                        resolve(res.data)
                    }
                })
                .catch(err => {
                    if (typeof err.message!=='undefined') {
                        if ( !attempt ){
                            attempt = true
                            let t =setInterval(() => {
                                if (!store.getters.getIsRefreshing) {
                                    proceed()
                                    clearInterval(t)
                                }
                            },300)
                        } else {
                            commit('SET_CREATE_LOCATIONS_LOADING', false)
                            // reject('Token has been expired. Please reload the page.')
                            reject(err)
                            console.log('Error is: ', err);
                        }
                    } else {
                        commit('SET_CREATE_LOCATIONS_LOADING', false)
                        reject(err.error)
                    }
                })
            }
            proceed()
            
        })
    },
    fetchSingleLocation: async ({
        commit
    }, payload) => {
        let attempt = false
        async function proceed() {
            commit("SET_SINGLE_LOCATION", [])
            commit("SET_SINGLE_LOCATION_LOADING", true)
            await axios.get(`${poBaseUrl}/warehouses/${payload.wid}/locations/${payload.id}`)
            .then(res => {
                if (typeof res.status !== 'undefined') {
                    if (res.status === 200) {
                        if (res.data) {
                            commit("SET_SINGLE_LOCATION_LOADING", false)
                            commit("SET_SINGLE_LOCATION", res.data)
                        }
                    }
                }
            })
            .catch(err => {
                if (typeof err.message!=='undefined') {
                    if ( !attempt ){
                        attempt = true
                        let t =setInterval(() => {
                            if (!store.getters.getIsRefreshing) {
                                proceed()
                                clearInterval(t)
                            }
                        },300)
                    } else {
                        commit('SET_SINGLE_LOCATION_LOADING', false)
                        // return Promise.reject('Token has been expired. Please reload the page.')
                        return Promise.reject(err)
                    }
                } else {
                    commit('SET_SINGLE_LOCATION_LOADING', false)
                    return Promise.reject(err.error)
                }
            })
        }
        proceed()
    },
    updateLocation: async ({
        commit
    }, payload) => {
        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                let { warehouse_id, location_id, ...otherProps } = payload

                payload = {
                    ...otherProps
                }
                //set loading to true
                commit("SET_UPDATE_LOCATION_LOADING", true)

                axios.put(`${poBaseUrl}/warehouses/${warehouse_id}/locations/update/${location_id}`, payload)
                .then(res => {
                    commit("SET_UPDATE_LOCATION_LOADING", false)
                    if (typeof res !== 'undefined' && res.status === 200) {
                        resolve(res.data)
                    }
                })
                .catch(err => {
                    if (typeof err.message!=='undefined') {
                        if ( !attempt ){
                            attempt = true
                            let t =setInterval(() => {
                                if (!store.getters.getIsRefreshing) {
                                    proceed()
                                    clearInterval(t)
                                }
                            },300)
                        } else {
                            commit('SET_UPDATE_LOCATION_LOADING', false)
                            // reject('Token has been expired. Please reload the page.')
                            reject(err)
                            console.log('Error is: ', err);
                        }
                    } else {
                        commit('SET_UPDATE_LOCATION_LOADING', false)
                        reject(err.error)
                    }
                })
            }
            proceed()
            
        })
    },
    deleteLocation: async ({
        commit
    }, payload) => {
        commit("SET_DELETE_LOCATION_LOADING", true)

        await axios.delete(`${poBaseUrl}/warehouses/${payload.wid}/locations/delete/${payload.location_id}`)
            .then(res => {
                if (typeof res.status !== 'undefined') {
                    if (res.status === 200) {
                        if (res.data) {
                            commit("SET_DELETE_LOCATION_LOADING", false)
                            // commit("SET_LOCATIONS", res.data)
                        }
                    }
                }
            })
            .catch(err => {
                commit("SET_DELETE_LOCATION_LOADING", false)
                if (typeof err.error !== 'undefined') {
                    return Promise.reject(err.error)
                } else {
                    if (typeof err.message !== 'undefined' && err.message === 'UnAuthenticated') {
                        // return Promise.reject('Token has been expired. Please reload the page.')
                        return Promise.reject(err)
                    }
                }
            })
    },
    // storage locations
    fetchAllStorageLocations: async ({
        commit
    }, payload) => {
        commit("SET_ALL_STORAGE_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
			function proceed() {
				commit('SET_ALL_STORAGE_LOCATIONS_LOADING', true)

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/storage-location/all?page=${payload.page}`).then(res => {
                // axios.get(`${poBaseUrl}/warehouse/${payload.id}/locations/all?page=${payload.page}`,
                //     { cancelToken: payload.cancelToken }
                // )
                let passedData = {
                    url: `${poBaseUrl}/warehouse/${payload.id}/locations/common`,
                    method: 'GET',
                    params: {
                        type:payload.type,
                        tab:payload.tab,
                        page: payload.page,
                    },
                    cancelToken: payload.cancelToken
                }
                axios(passedData)
                .then(res => {
					commit('SET_ALL_STORAGE_LOCATIONS_LOADING', false)
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results

                                commit("SET_ALL_STORAGE_LOCATIONS_LOADING", false)

                                if (typeof results !== 'undefined') { 
                                    let allStoragePagination = {
                                        currentTab: 1,
                                        current_page: results.current_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        last_page: results.last_page,
                                        items: results.data,
                                        empty_count: res.data.empty_count,
                                        filled_count: res.data.filled_count
                                    }
                                    
                                    commit("SET_STORAGE_ALL_PAGINATION", allStoragePagination)
                                }

                                resolve()
                            }
                        }
                    }
				})
				.catch( err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_ALL_STORAGE_LOCATIONS_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
                            reject(err.message)
                            console.log('Error is: ', err);
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_ALL_STORAGE_LOCATIONS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})        
    },
    fetchFilledStorageLocations: async ({
        commit
    }, payload) => {
        commit("SET_FILLED_STORAGE_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
			function proceed() {
				commit('SET_FILLED_STORAGE_LOCATIONS_LOADING', true)

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/storage-location/filled?page=${payload.page}`).then(res => {
                // axios.get(`${poBaseUrl}/warehouse/${payload.id}/locations/filled?page=${payload.page}`,
                //     { cancelToken: payload.cancelToken }
                // )
                let passedData = {
                    url: `${poBaseUrl}/warehouse/${payload.id}/locations/common`,
                    method: 'GET',
                    params: {
                        tab:payload.tab,
                        type:payload.type,
                        page: payload.page
                    },
                    cancelToken: payload.cancelToken
                }
                axios(passedData)
                .then(res => {
					commit('SET_FILLED_STORAGE_LOCATIONS_LOADING', false)
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results
                                commit("SET_FILLED_STORAGE_LOCATIONS_LOADING", false)

                                if (typeof results !== 'undefined') { 
                                    let filledStoragePagination = {
                                        currentTab: 1,                            
                                        current_page: results.current_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        last_page: results.last_page,
                                        items: results.data,
                                        empty_count: res.data.empty_count,
                                        filled_count: res.data.filled_count
                                    }
                                    
                                    commit("SET_STORAGE_FILLED_PAGINATION", filledStoragePagination)      
                                }

                                resolve(results)
                            }
                        }
                    }
				})
				.catch( err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_FILLED_STORAGE_LOCATIONS_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
                            reject(err.message)
                            console.log('Error is: ', err);
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_FILLED_STORAGE_LOCATIONS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
        
    },
    fetchEmptyStorageLocations: async ({
        commit
    }, payload) => {
        commit("SET_EMPTY_STORAGE_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
			function proceed() {
				commit('SET_EMPTY_STORAGE_LOCATIONS_LOADING', true)

				// axios.get(`${poBaseUrl}/warehouse/${payload.id}/storage-location/empty?page=${payload.page}`).then(res => {
                // axios.get(`${poBaseUrl}/warehouse/${payload.id}/locations/empty?page=${payload.page}`, 
                //     { cancelToken: payload.cancelToken }
                // )
                let passedData = {
                    url: `${poBaseUrl}/warehouse/${payload.id}/locations/common`,
                    method: 'GET',
                    params: {
                        tab: payload.tab,
                        type: payload.type,
                        page: payload.page
                    },
                    cancelToken: payload.cancelToken
                }
                axios(passedData)
                .then(res => {
					commit('SET_EMPTY_STORAGE_LOCATIONS_LOADING', false)
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results

                                commit("SET_EMPTY_STORAGE_LOCATIONS_LOADING", false)

                                if (typeof results !== 'undefined') { 
                                    let emptyStoragePagination = {
                                        currentTab: 1,                            
                                        current_page: results.current_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        last_page: results.last_page,
                                        items: results.data,
                                        empty_count: res.data.empty_count,
                                        filled_count: res.data.filled_count
                                    }
                                    
                                    commit("SET_STORAGE_EMPTY_PAGINATION", emptyStoragePagination)      
                                }

                                resolve(results)
                            }
                        }
                    }
				})
				.catch( err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_EMPTY_STORAGE_LOCATIONS_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
                            reject(err.message)
                            console.log('Error is: ', err);
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_EMPTY_STORAGE_LOCATIONS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
        
    },
    fetchAllStockPickableStaggingStorageLocations: async ({
        commit
    }, payload) => {
        commit("SET_ALLL_STOCK_PICKABLE_STAGGING_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                commit('SET_ALLL_STOCK_PICKABLE_STAGGING_LOCATIONS_LOADING', true)

                // axios.get(`${poBaseUrl}/warehouse/${payload.id}/storage-location/filled?page=${payload.page}`).then(res => {
                // axios.get(`${poBaseUrl}/warehouse/${payload.id}/locations/filled?page=${payload.page}`,
                //     { cancelToken: payload.cancelToken }
                // )
                let passedData = {
                    url: `${poBaseUrl}/warehouse/${payload.id}/locations/common`,
                    method: 'GET',
                    params: {
                        tab: payload.tab,
                        type: payload.type,
                        page: payload.page
                    },
                    cancelToken: payload.cancelToken
                }
                axios(passedData)
                    .then(res => {
                        commit('SET_ALLL_STOCK_PICKABLE_STAGGING_LOCATIONS_LOADING', false)
                        if (typeof res.status !== 'undefined') {
                            if (res.status === 200) {
                                if (res.data) {
                                    let results = res.data.results
                                    commit("SET_ALLL_STOCK_PICKABLE_STAGGING_LOCATIONS_LOADING", false)

                                    if (typeof results !== 'undefined') {
                                        let filledStoragePagination = {
                                            currentTab: 1,
                                            current_page: results.current_page,
                                            old_page: results.current_page,
                                            total: results.total,
                                            per_page: results.per_page,
                                            prev_page_url: results.prev_page_url,
                                            next_page_url: results.next_page_url,
                                            last_page: results.last_page,
                                            items: results.data,
                                            empty_count: res.data.empty_count,
                                            filled_count: res.data.filled_count
                                        }

                                        commit("SET_ALL_STOCK_PICKABLE_STAGGIN_PAGINATION", filledStoragePagination)
                                    }

                                    resolve(results)
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
                                commit('SET_ALLL_STOCK_PICKABLE_STAGGING_LOCATIONS_LOADING', false)
                                // reject('Token has been expired. Please reload the page.')
                                reject(err.message)
                                console.log('Error is: ', err);
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit('SET_ALLL_STOCK_PICKABLE_STAGGING_LOCATIONS_LOADING', false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })

    },
    fetchPackingStorageLocations: async ({
        commit
    }, payload) => {
        commit("SET_PACKING_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                commit('SET_PACKING_LOCATIONS_LOADING', true)
                let passedData = {
                    url: `${poBaseUrl}/warehouse/${payload.id}/locations/common`,
                    method: 'GET',
                    params: {
                        tab: payload.tab,
                        type: payload.type,
                        page: payload.page
                    },
                    cancelToken: payload.cancelToken
                }
                axios(passedData)
                    .then(res => {
                        commit('SET_PACKING_LOCATIONS_LOADING', false)
                        if (typeof res.status !== 'undefined') {
                            if (res.status === 200) {
                                if (res.data) {
                                    let results = res.data.results
                                    commit("SET_PACKING_LOCATIONS_LOADING", false)

                                    if (typeof results !== 'undefined') {
                                        let filledStoragePagination = {
                                            currentTab: 1,
                                            current_page: results.current_page,
                                            old_page: results.current_page,
                                            total: results.total,
                                            per_page: results.per_page,
                                            prev_page_url: results.prev_page_url,
                                            next_page_url: results.next_page_url,
                                            last_page: results.last_page,
                                            items: results.data,
                                            empty_count: res.data.empty_count,
                                            filled_count: res.data.filled_count
                                        }

                                        commit("SET_PACKING_PAGINATION", filledStoragePagination)
                                    }

                                    resolve(results)
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
                                commit('SET_PACKING_LOCATIONS_LOADING', false)
                                // reject('Token has been expired. Please reload the page.')
                                reject(err.message)
                                console.log('Error is: ', err);
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit('SET_PACKING_LOCATIONS_LOADING', false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })

    },
    fetchSpecialStorageLocations: async ({
        commit
    }, payload) => {
        commit("SET_SPECIAL_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                commit('SET_SPECIAL_LOCATIONS_LOADING', true)
                let passedData = {
                    url: `${poBaseUrl}/warehouse/${payload.id}/locations/common`,
                    method: 'GET',
                    params: {
                        tab: payload.tab,
                        type: payload.type,
                        page: payload.page
                    },
                    cancelToken: payload.cancelToken
                }
                axios(passedData)
                    .then(res => {
                        commit('SET_SPECIAL_LOCATIONS_LOADING', false)
                        if (typeof res.status !== 'undefined') {
                            if (res.status === 200) {
                                if (res.data) {
                                    let results = res.data.results
                                    commit("SET_SPECIAL_LOCATIONS_LOADING", false)

                                    if (typeof results !== 'undefined') {
                                        let filledStoragePagination = {
                                            currentTab: 1,
                                            current_page: results.current_page,
                                            old_page: results.current_page,
                                            total: results.total,
                                            per_page: results.per_page,
                                            prev_page_url: results.prev_page_url,
                                            next_page_url: results.next_page_url,
                                            last_page: results.last_page,
                                            items: results.data,
                                            empty_count: res.data.empty_count,
                                            filled_count: res.data.filled_count
                                        }

                                        commit("SET_SPECIAL_PAGINATION", filledStoragePagination)
                                    }

                                    resolve(results)
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
                                commit('SET_SPECIAL_LOCATIONS_LOADING', false)
                                // reject('Token has been expired. Please reload the page.')
                                reject(err.message)
                                console.log('Error is: ', err);
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit('SET_SPECIAL_LOCATIONS_LOADING', false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })

    },
    fetchOthersStorageLocations: async ({
        commit
    }, payload) => {
        commit("SET_OTHERS_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                commit('SET_OTHERS_LOCATIONS_LOADING', true)
                let passedData = {
                    url: `${poBaseUrl}/warehouse/${payload.id}/locations/common`,
                    method: 'GET',
                    params: {
                        tab: payload.tab,
                        type: payload.type,
                        page: payload.page
                    },
                    cancelToken: payload.cancelToken
                }
                axios(passedData)
                    .then(res => {
                        commit('SET_OTHERS_LOCATIONS_LOADING', false)
                        if (typeof res.status !== 'undefined') {
                            if (res.status === 200) {
                                if (res.data) {
                                    let results = res.data.results
                                    commit("SET_OTHERS_LOCATIONS_LOADING", false)

                                    if (typeof results !== 'undefined') {
                                        let filledStoragePagination = {
                                            currentTab: 1,
                                            current_page: results.current_page,
                                            old_page: results.current_page,
                                            total: results.total,
                                            per_page: results.per_page,
                                            prev_page_url: results.prev_page_url,
                                            next_page_url: results.next_page_url,
                                            last_page: results.last_page,
                                            items: results.data,
                                            empty_count: res.data.empty_count,
                                            filled_count: res.data.filled_count
                                        }

                                        commit("SET_OTHERS_PAGINATION", filledStoragePagination)
                                    }

                                    resolve(results)
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
                                commit('SET_OTHERS_LOCATIONS_LOADING', false)
                                // reject('Token has been expired. Please reload the page.')
                                reject(err.message)
                                console.log('Error is: ', err);
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit('SET_OTHERS_LOCATIONS_LOADING', false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })

    },
    // gate locations
    fetchAllGateLocations: async ({
        commit
    }, payload) => {
        commit("SET_ALL_GATE_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
			function proceed() {
				commit('SET_ALL_GATE_LOCATIONS_LOADING', true)

				axios.get(`${poBaseUrl}/warehouse/${payload.id}/gate-location/all?page=${payload.page}`).then(res => {
					commit('SET_ALL_GATE_LOCATIONS_LOADING', false)
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results

                                commit("SET_ALL_GATE_LOCATIONS_LOADING", false)

                                if (typeof results !== 'undefined') { 
                                    let allGatePagination = {
                                        currentTab: 1,                            
                                        current_page: results.current_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        last_page: results.last_page,
                                        items: results.data
                                    }
                                    
                                    commit("SET_GATE_ALL_PAGINATION", allGatePagination)      
                                }

                                resolve(results)
                            }
                        }
                    }
				})
				.catch( err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_ALL_GATE_LOCATIONS_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
                            reject(err)
                            console.log('Error is: ', err);
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_ALL_GATE_LOCATIONS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
        
    },
    fetchFilledGateLocations: async ({
        commit
    }, payload) => {
        commit("SET_FILLED_GATE_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
			function proceed() {
				commit('SET_FILLED_GATE_LOCATIONS_LOADING', true)

				axios.get(`${poBaseUrl}/warehouse/${payload.id}/gate-location/filled?page=${payload.page}`).then(res => {
					commit('SET_FILLED_GATE_LOCATIONS_LOADING', false)
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results

                                commit("SET_FILLED_GATE_LOCATIONS_LOADING", false)

                                if (typeof results !== 'undefined') { 
                                    let filledGatePagination = {
                                        currentTab: 1,                            
                                        current_page: results.current_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        last_page: results.last_page,
                                        items: results.data
                                    }
                                    
                                    commit("SET_GATE_FILLED_PAGINATION", filledGatePagination)      
                                }

                                resolve(results)
                            }
                        }
                    }
				})
				.catch( err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_FILLED_GATE_LOCATIONS_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
                            reject(err)
                            console.log('Error is: ', err);
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_FILLED_GATE_LOCATIONS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
        
    },
    fetchEmptyGateLocations: async ({
        commit
    }, payload) => {
        commit("SET_EMPTY_GATE_LOCATIONS_LOADING", true)

        let attempt = false
        return new Promise((resolve, reject) => {
			function proceed() {
				commit('SET_EMPTY_GATE_LOCATIONS_LOADING', true)

				axios.get(`${poBaseUrl}/warehouse/${payload.id}/gate-location/empty?page=${payload.page}`).then(res => {
					commit('SET_EMPTY_GATE_LOCATIONS_LOADING', false)
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results

                                commit("SET_EMPTY_GATE_LOCATIONS_LOADING", false)

                                if (typeof results !== 'undefined') { 
                                    let emptyGatePagination = {
                                        currentTab: 1,                            
                                        current_page: results.current_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        last_page: results.last_page,
                                        items: results.data
                                    }
                                    
                                    commit("SET_GATE_EMPTY_PAGINATION", emptyGatePagination)      
                                }

                                resolve(results)
                            }
                        }
                    }
				})
				.catch( err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_EMPTY_GATE_LOCATIONS_LOADING', false)
							// reject('Token has been expired. Please reload the page.')
                            reject(err)
                            console.log('Error is: ', err);
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_EMPTY_GATE_LOCATIONS_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
        
    },
    // setting tabs
    setCurrentLocationTypeTab( { commit }, payload) {
        commit("setCurrentLocationTypeTab", payload)
    },
    setCurrentLocationTypeSubTab( { commit }, payload) {
        commit("setCurrentLocationTypeSubTab", payload)
    },
    setEmptyInventoryLocationsData( { commit }, payload) {
        commit("setEmptyInventoryLocationsData", payload)
    },
    fetchSearchedLocation: async ({
        commit
    }, payload) => {
        commit("SET_SEARCHED_LOCATIONS_LOADING", true)
    
        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                commit('SET_SEARCHED_LOCATIONS_LOADING', true)
        
                axios(payload)
                .then(res => {
                    commit('SET_SEARCHED_LOCATIONS_LOADING', false)
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results    
    
                                if (typeof results !== 'undefined') { 
                                    let searchedLocations = {
                                        currentTab: 1,
                                        current_page: results.current_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        items: results.data,
                                        last_page: results.last_page,
                                        currentTabName: payload.tab
                                    }
                                    
                                    commit("SET_SEARCHED_LOCATIONS", searchedLocations)
                                }

                                commit("SET_SEARCHED_LOCATIONS_LOADING", false)
                                resolve()
                            }
                        }
                    }
                })
                .catch( err => {
                    if (typeof err.message!=='undefined') {
                        if ( !attempt ){
                            attempt = true
                            let t =setInterval(() => {
                                if (!store.getters.getIsRefreshing) {
                                    proceed()
                                    clearInterval(t)
                                }
                            },300)
                        } else {
                            commit('SET_SEARCHED_LOCATIONS_LOADING', false)
                            // reject('Token has been expired. Please reload the page.')
                            reject(err)
                            console.log('Error is: ', err);
                        }
                    }
    
                    if (typeof err.error !=='undefined') {
                        commit('SET_SEARCHED_LOCATIONS_LOADING', false)
                        reject(err.error)
                    }
                })
            }
            proceed()
        })        
    },
    setSearchedLocationsLoading({ commit } , payload) {
		commit("SET_SEARCHED_LOCATIONS_LOADING", payload)
	},
    setLocationSearchedVal({ commit }, payload) {
		commit("setLocationSearchedVal", payload)
	},
    // filter and search 
	setFilteredLocationsLoading({ commit }, payload) {
		commit("SET_FILTERED_LOCATIONS_LOADING", payload)
	},
	setLocationsFilteredVal({ commit }, payload) {
		commit("setLocationsFilteredVal", payload)
	},
	fetchFilterLocationsCustomers: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_FILTERED_LOCATIONS_LOADING", true)

				axios(payload)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
                                    let results = res.data.results    
    
                                    if (typeof results !== 'undefined') { 
                                        let searchedLocations = {
                                            currentTab: 1,
                                            current_page: results.current_page,
                                            old_page: results.current_page,
                                            total: results.total,
                                            per_page: results.per_page,
                                            prev_page_url: results.prev_page_url,
                                            next_page_url: results.next_page_url,
                                            items: results.data,
                                            last_page: results.last_page,
                                            currentTabName: payload.tab
                                        }
                                        
                                        commit("SET_FILTERED_CUSTOMER_LOCATIONS", searchedLocations)
                                    }
                                    
									commit("SET_FILTERED_LOCATIONS_LOADING", false)
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
								commit('SET_FILTERED_LOCATIONS_LOADING', false)
								// reject('Token has been expired. Please reload the page.')
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_FILTERED_LOCATIONS_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
    // fetch location types
    fetchLocationsTypes: async ({
        commit
    }) => {
        commit("SET_LOCATIONS_TYPES", [])
        commit("SET_LOCATIONS_TYPES_LOADING", true)
        let attempt = false
        async function proceed() {
            await axios.get(`${poBaseUrl}/location-types`)
            .then(res => {
                if (typeof res.status !== 'undefined') {
                    if (res.status === 200) {
                        if (res.data) {
                            commit("SET_LOCATIONS_TYPES_LOADING", false)
                            commit("SET_LOCATIONS_TYPES", res.data)
                        }
                    }
                }
            })
            .catch(err => {
                if (typeof err.message!=='undefined') {
                    if ( !attempt ){
                        attempt = true
                        let t =setInterval(() => {
                            if (!store.getters.getIsRefreshing) {
                                proceed()
                                clearInterval(t)
                            }
                        },300)
                    } else {
                        commit('SET_LOCATIONS_TYPES_LOADING', false)
                        // return Promise.reject('Token has been expired. Please reload the page.')
                        return Promise.reject(err)
                    }
                } else {
                    commit('SET_LOCATIONS_TYPES_LOADING', false)
                    return Promise.reject(err.error)
                }
            })    
        }
        proceed()
        
    },
    setAllLOcationProductsLists({ commit }, payload) {
        commit("SET_ALL_PRODUCTS_LOCATION_LISTS", payload)
    },
    locationProductApi: async({
        commit
    }, payload) => {
    // const { poBaseUrlState } = state
    commit("SET_ALL_LOCATION_PRODUCTS_LOADING", true)
    let attempt = false
    return new Promise((resolve, reject) => {
        function proceed() {
            axios.get(`${poBaseUrl}/products?page=${payload.page}`, { cancelToken: payload.cancelToken })
                .then(res => {
                    commit("SET_ALL_LOCATION_PRODUCTS_LOADING", false)
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

                        if (typeof res.data !== 'undefined') {
                            let productsData = []

                            if (typeof res.data.results !== 'undefined' && res.data.results !== undefined)
                                productsData = res.data.results.data

                            finalData = {
                                products: productsData,
                                current_page: res.data.results.current_page,
                                total: res.data.results.total,
                                old_page: res.data.results.current_page,
                                last_page: res.data.results.last_page,
                                per_page: res.data.results.per_page
                            }

                            commit("SET_ALL_LOCATION_PRODUCTS_LISTS", finalData)

                        } else {
                            commit("SET_ALL_LOCATION_PRODUCTS_LISTS", finalData)
                        }

                        resolve()
                    }
                }).catch(err => {
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
                            commit('SET_ALL_LOCATION_PRODUCTS_LOADING', false)
                        }
                    }

                    if (typeof err.error !== 'undefined') {
                        commit('SET_ALL_LOCATION_PRODUCTS_LOADING', false)
                        reject(err.error)
                    }
                })
        }
        proceed()
    })
},
}

const mutations = {
    SET_LOCATIONS: (state, payload) => {
        state.locations = payload
    },
    SET_LOCATIONS_LOADING: (state, payload) => {
        state.locationsLoading = payload
    },
    SET_CREATE_LOCATIONS_LOADING: (state, payload) => {
        state.createLocationsLoading = payload
    },
    SET_UPDATE_LOCATION_LOADING: (state, payload) => {
        state.locationUpdateLoading = payload
    },
    SET_SINGLE_LOCATION: (state, payload) => {
        state.singleLocation = payload
    },
    SET_SINGLE_LOCATION_LOADING: (state, payload) => {
        state.singleLocationLoading = payload
    },
    SET_DELETE_LOCATION_LOADING: (state, payload) => {
        state.deleteLocationLoading = payload
    },
    // 
    SET_STORAGE_LOCATIONS: (state, payload) => {
        state.storageLocations = payload
    },
    SET_STORAGE_LOCATIONS_LOADING: (state, payload) => {
        state.storageLocationsLoading = payload
    },
    SET_GATE_LOCATIONS: (state, payload) => {
        state.gateLocations = payload
    },
    SET_GATE_LOCATIONS_LOADING: (state, payload) => {
        state.gateLocationsLoading = payload
    },
    SET_STORAGE_ALL_PAGINATION: (state, payload) => {
		state.storageAllPagination = payload
	},
    SET_STORAGE_FILLED_PAGINATION: (state, payload) => {
		state.storageFilledPagination = payload
	},
    SET_STORAGE_EMPTY_PAGINATION: (state, payload) => {
		state.storageEmptyPagination = payload
	},
    SET_ALL_STOCK_PICKABLE_STAGGIN_PAGINATION: (state, payload) => {
        state.allStockPickableStaggingPagination = payload
    },
    SET_PACKING_PAGINATION: (state, payload) => {
        state.storagePackingPagination = payload
    },
    SET_SPECIAL_PAGINATION: (state, payload) => {
        state.storageSpecialPagination = payload
    },
    SET_OTHERS_PAGINATION: (state, payload) => {
        state.storageOthersPagination = payload
    },
    SET_GATE_ALL_PAGINATION: (state, payload) => {
		state.gateAllPagination = payload
	},
    SET_GATE_FILLED_PAGINATION: (state, payload) => {
		state.gateFilledPagination = payload
	},
    SET_GATE_EMPTY_PAGINATION: (state, payload) => {
		state.gateEmptyPagination = payload
	},
    // 
    SET_ALL_STORAGE_LOCATIONS_LOADING: (state, payload) => {
        state.allStorageLocationsLoading = payload
    },
    SET_FILLED_STORAGE_LOCATIONS_LOADING: (state, payload) => {
        state.filledStorageLocationsLoading = payload
    },
    SET_EMPTY_STORAGE_LOCATIONS_LOADING: (state, payload) => {
        state.emptyStorageLocationsLoading = payload
    },
    SET_ALLL_STOCK_PICKABLE_STAGGING_LOCATIONS_LOADING: (state, payload) => {
        state.allStockPickableStaggingLoading = payload
    },
    SET_PACKING_LOCATIONS_LOADING: (state, payload) => {
        state.allpackingLoading = payload
    },
    SET_SPECIAL_LOCATIONS_LOADING: (state, payload) => {
        state.allspecialLoading = payload
    },
    SET_OTHERS_LOCATIONS_LOADING: (state, payload) => {
        state.allothersLoading = payload
    },
    SET_ALL_GATE_LOCATIONS_LOADING: (state, payload) => {
        state.allGateLocationsLoading = payload
    },
    SET_FILLED_GATE_LOCATIONS_LOADING: (state, payload) => {
        state.filledGateLocationsLoading = payload
    },
    SET_EMPTY_GATE_LOCATIONS_LOADING: (state, payload) => {
        state.emptyGateLocationsLoading = payload
    },
    setCurrentLocationTypeTab: (state, payload) => {
        state.locationsTab.typeTab = payload
    }, 
    setCurrentLocationTypeSubTab: (state, payload) => {
        state.locationsTab.typeSubTab = payload
    }, 
    setEmptyInventoryLocationsData: (state, payload) => {
        let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            items: payload
        }

        state.storageAllPagination = locationDefaultData
        state.storageFilledPagination = locationDefaultData
        state.storageEmptyPagination = locationDefaultData
        state.gateAllPagination = locationDefaultData
        state.gateFilledPagination = locationDefaultData
        state.gateEmptyPagination = locationDefaultData
    },
    SET_SEARCHED_LOCATIONS: (state, payload) => {
        state.searchedLocations = payload
    },
    SET_SEARCHED_LOCATIONS_LOADING: (state, payload) => {
        state.searchedLocationsLoading = payload
    },
    setLocationSearchedVal: (state, payload) => {
        let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            items: payload
        }

        state.searchedLocations = locationDefaultData
    },
    // filter and search
	SET_FILTERED_LOCATIONS_LOADING: (state, payload) => {
		state.filteredLocationsLoading = payload
	},
	SET_FILTERED_CUSTOMER_LOCATIONS: (state, payload) => {
		state.filteredLocations = payload
	},
	setLocationsFilteredVal: (state, payload) => {
		let locationDefaultData = {
			currentTab: 1,
			current_page: 1,
			old_page: 1,
			total: 0,
			per_page: 0,
			items: payload
		}

		state.filteredLocations = locationDefaultData
	},
    // types
    SET_LOCATIONS_TYPES: (state, payload) => {
        state.locationsTypes = payload
    },
    SET_LOCATIONS_TYPES_LOADING: (state, payload) => {
        state.locationsTypesLoading = payload
    },
    // location products for default sku values
    SET_ALL_LOCATION_PRODUCTS_LOADING: (state, payload) => {
        state.locationsProductsForDeafultSkuLoading = payload
    },
    SET_ALL_LOCATION_PRODUCTS_LISTS: (state, payload) => {
        state.locationsProductsForDeafultSku = payload
    },
    SET_ALL_PRODUCTS_LOCATION_LISTS: (state, payload) => {
        state.allLocationProductListsDropdownData = payload
    },

}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}