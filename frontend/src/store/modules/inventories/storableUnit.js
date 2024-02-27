import axios from "axios"
import store from '../../'

const state = {
    storableUnitsActive: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		items: []
	},
    storableUnitsHistory: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		items: []
	},
    storableUnitsActiveLoading: false,
    storableUnitsHistoryLoading: false,
	searchedStorableUnits: {
		currentTab: 1,
		current_page: 1,
		old_page: 1,
		total: 0,
		per_page: 0,
		last_page: 0,
		items: []
	},
	searchedStorableUnitsLoading: false,
    currentStorableUnitTab: 0,
    singleStorableUnit: null,
    singleStorableUnitLoading: false,
    storableSearch: '',
    // filter and search
	filteredStorableLoading:false,
	filteredStorable:[],
	locationUpdateLoading: false
}

const getters = {
    getStorableUnitsActive: state => state.storableUnitsActive,
    getStorableUnitsActiveLoading: state => state.storableUnitsActiveLoading,
    getStorableUnitsHistory: state => state.storableUnitsHistory,
    getStorableUnitsHistoryLoading: state => state.storableUnitsHistoryLoading,
    getCurrentStorableUnitTab: state => state.currentStorableUnitTab,
    getSearchedStorableUnit: state => state.searchedStorableUnits,
    getSearchedStorableUnitLoading: state => state.searchedStorableUnitsLoading,
    getSingleStorableUnit: state => state.singleStorableUnit,
    getSingleStorableUnitLoading: state => state.singleStorableUnitLoading,
    getStorableSearchData: state => state.storableSearch,
    // search and filter
	getFilteredStorableLoading: state => state.filteredStorableLoading,
	getFilteredStorable: state => state.filteredStorable,
	getLocationsUpdateLoading: state => state.locationUpdateLoading
}

const poBaseUrl = process.env.VUE_APP_PO_URL

const actions = {
    fetchStorableUnitsActive: async ({
        commit
    }, payload) => {
        let attempt = false
		commit("SET_STORABLE_UNITS_ACTIVE_LOADING", true)

		return new Promise( (resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouse/${payload.id}/storable-units/active?page=${payload.page}`,
                    { cancelToken: payload.cancelToken }
                ).then(res => {
					if ( res.status === 200 ) {
						if ( res.data ) {
                            let results = res.data.results    
    
                            if (typeof results !== 'undefined') { 
                                let activeStorableUnits = {
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
                                
                                commit("SET_STORABLE_UNITS_ACTIVE", activeStorableUnits)
                            }

                            commit("SET_STORABLE_UNITS_ACTIVE_LOADING", false)
                            resolve()
						} else {
							reject('not ok')
						}
					} else {
						reject('not ok')
					}
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
                            commit('SET_STORABLE_UNITS_ACTIVE_LOADING', false)
                            // reject('Token has been expired. Please reload the page.')
                            reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_STORABLE_UNITS_ACTIVE_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
    },
    fetchStorableUnitsHistory: async ({
        commit
    }, payload) => {
        let attempt = false
		commit("SET_STORABLE_UNITS_HISTORY_LOADING", true)

		return new Promise( (resolve, reject) => {
			function proceed() {
				axios.get(`${poBaseUrl}/warehouse/${payload.id}/storable-units/history?page=${payload.page}`,
                    { cancelToken: payload.cancelToken }
                ).then(res => {
					if ( res.status === 200 ) {
						if ( res.data ) {
                            let results = res.data.results    
    
                            if (typeof results !== 'undefined') { 
                                let historyStorableUnits = {
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
                                
                                commit("SET_STORABLE_UNITS_HISTORY", historyStorableUnits)
                            }

                            commit("SET_STORABLE_UNITS_HISTORY_LOADING", false)
                            resolve()
						} else {
							reject('not ok')
						}
					} else {
						reject('not ok')
					}
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
                            commit('SET_STORABLE_UNITS_HISTORY_LOADING', false)
                            // reject('Token has been expired. Please reload the page.')
                            reject(err.message)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_STORABLE_UNITS_HISTORY_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
    },
	setEmptyInventoryStorableUnitData( { commit }, payload) {
        commit("setEmptyInventoryStorableUnitData", payload)
    },
	setSearchedStorableUnitsLoading({ commit } , payload) {
		commit("SET_SEARCHED_STORABLE_UNIT_LOADING", payload)
	},
    setStorableUnitSearchedVal({ commit }, payload) {
		commit("setStorableUnitSearchedVal", payload)
	},
	fetchSearchedStorableUnits: async ({
        commit
    }, payload) => {
        commit("SET_SEARCHED_STORABLE_UNIT_LOADING", true)
    
        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                commit('SET_SEARCHED_STORABLE_UNIT_LOADING', true)
        
                axios(payload)
                .then(res => {
                    commit('SET_SEARCHED_STORABLE_UNIT_LOADING', false)
                    if (typeof res.status !== 'undefined') {
                        if (res.status === 200) {
                            if (res.data) {
                                let results = res.data.results    
    
                                if (typeof results !== 'undefined') { 
                                    let searchedStorableUnits = {
                                        currentTab: 1,
                                        current_page: results.current_page,
                                        old_page: results.current_page,
                                        total: results.total,
                                        last_page: results.last_page,
                                        per_page: results.per_page,
                                        prev_page_url: results.prev_page_url,
                                        next_page_url: results.next_page_url,
                                        items: results.data,
                                        tab: payload.tab
                                    }
                                    
                                    commit("SET_SEARCHED_STORABLE_UNIT", searchedStorableUnits)
                                }

                                commit("SET_SEARCHED_STORABLE_UNIT_LOADING", false)
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
                                if ( !store.getters.getIsRefreshing ) {
                                    proceed()
                                    clearInterval(t)
                                }
                            },300)
                        } else {
                            commit('SET_SEARCHED_STORABLE_UNIT_LOADING', false)
                            // reject('Token has been expired. Please reload the page.')
                            reject(err)
                        }
                    }
    
                    if (typeof err.error !=='undefined') {
                        commit('SET_SEARCHED_STORABLE_UNIT_LOADING', false)
                        reject(err.error)
                    }
                })
            }
            proceed()
        })        
    },
    setCurrentStorableUnitTab( { commit }, payload) {
        commit("setCurrentStorableUnitTab", payload)
    },
    fetchSingleStorableUnitInbound: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_SINGLE_STORABLE_UNIT_LOADING", true)

				axios.get(`${poBaseUrl}/warehouse/${payload.wid}/storable-unit/${payload.unit_id}`)
				.then(res => {
					if (typeof res.status !== 'undefined') {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_SINGLE_STORABLE_UNIT_LOADING", false)
								commit("SET_SINGLE_STORABLE_UNIT", res.data.results)
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
								if ( !store.getters.getIsRefreshing ) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_SINGLE_STORABLE_UNIT_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_SINGLE_STORABLE_UNIT_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
    setStorableSearchValue( { commit }, payload) {
        commit("setStorableSearchValue", payload)
    },
    // filter and search 
	setFilteredStorableLoading({ commit }, payload) {
		commit("SET_FILTERED_STORABLE_LOADING", payload)
	},
	setStorableFilteredVal({ commit }, payload) {
		commit("setStorableFilteredVal", payload)
	},
	fetchFilterStorableCustomers: async ({
		commit
	}, payload) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_FILTERED_STORABLE_LOADING", true)

				axios(payload)
					.then(res => {
						if (typeof res.status !== 'undefined') {
							if (res.status === 200) {
								if (res.data) {
                                    let results = res.data.results    
    
                                    if (typeof results !== 'undefined') { 
                                        let searchedStorableUnits = {
                                            currentTab: 1,
                                            current_page: results.current_page,
                                            old_page: results.current_page,
                                            total: results.total,
                                            last_page: results.last_page,
                                            per_page: results.per_page,
                                            prev_page_url: results.prev_page_url,
                                            next_page_url: results.next_page_url,
                                            items: results.data,
                                            tab: payload.tab
                                        }
                                        
                                        commit("SET_FILTERED_CUSTOMER_INBOUND", searchedStorableUnits)
                                    }

                                    commit("SET_FILTERED_STORABLE_LOADING", false)
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
								commit('SET_FILTERED_STORABLE_LOADING', false)
								// reject('Token has been expired. Please reload the page.')
								reject(err)
							}
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_FILTERED_STORABLE_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	//  update storable location
	updateStorableLocation: async ({
        commit
    }, payload) => {
        let attempt = false
        return new Promise((resolve, reject) => {
            function proceed() {
                commit("SET_UPDATE_LOCATION_LOADING", true)

				let { storable_unit_id, ...otherProps } = payload

                payload = {
                    ...otherProps
                }

                axios.put(`${poBaseUrl}/storable-unit/${storable_unit_id}/change-location`, payload)
                .then(res => {
                    commit("SET_UPDATE_LOCATION_LOADING", false)
                    if (typeof res !== 'undefined' && res.status === 200) {
                        resolve(res.data)
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
                            commit('SET_UPDATE_LOCATION_LOADING', false)
                            reject(err.message)
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
}

const mutations = {
    SET_STORABLE_UNITS_ACTIVE: (state, payload) => {
        state.storableUnitsActive = payload
    },
    SET_STORABLE_UNITS_ACTIVE_LOADING: (state, payload) => {
        state.storableUnitsActiveLoading = payload
    },
    SET_STORABLE_UNITS_HISTORY: (state, payload) => {
        state.storableUnitsHistory = payload
    },
    SET_STORABLE_UNITS_HISTORY_LOADING: (state, payload) => {
        state.storableUnitsHistoryLoading = payload
    },
	setEmptyInventoryStorableUnitData: (state, payload) => {
        let storableDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            items: payload
        }
        
        state.storableUnitsActive = storableDefaultData
        state.storableUnitsHistory = storableDefaultData
	},
	SET_SEARCHED_STORABLE_UNIT: (state, payload) => {
		state.searchedStorableUnits = payload
	},
	SET_SEARCHED_STORABLE_UNIT_LOADING: (state, payload) => {
        state.searchedStorableUnitsLoading = payload
    },
    setStorableUnitSearchedVal: (state, payload) => {
        let storableDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            items: payload
        }

        state.searchedStorableUnits = storableDefaultData
    },
    setCurrentStorableUnitTab( state, payload) {
        state.currentStorableUnitTab = payload
    },
    SET_SINGLE_STORABLE_UNIT: (state, payload) => {
		state.singleStorableUnit = payload
	},
	SET_SINGLE_STORABLE_UNIT_LOADING: (state, payload) => {
		state.singleStorableUnitLoading = payload
	},
    setStorableSearchValue( state, payload) {
        state.storableSearch = payload
    },
    // filter and search
	SET_FILTERED_STORABLE_LOADING: (state, payload) => {
		state.filteredStorableLoading = payload
	},
	SET_FILTERED_CUSTOMER_INBOUND: (state, payload) => {
		state.filteredStorable = payload
	},
	setStorableFilteredVal: (state, payload) => {
		let storableDefaultData = {
			currentTab: 1,
			current_page: 1,
			old_page: 1,
			total: 0,
			per_page: 0,
			data: payload
		}

		state.filteredStorable = storableDefaultData
	},
	SET_UPDATE_LOCATION_LOADING: (state, payload) => {
        state.locationUpdateLoading = payload
    },
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}