
const state = {
    items: [],
    itemsLoading: true,
    prevPageUrl: null,
    nextPageUrl: null,
    from: 0,
    to: 0,
    total: 0,

    sort: {
        field: 'etd',
        type: 'desc'
    },
    selectedFilterValue: { 'rateConfirm' : 'all', 'shiflOfficeFrom': 'all' },
}

const getters ={
    getItems: state => state.items,
    getItemsLoading: state => state.itemsLoading,
    getNextPageUrl: state => state.nextPageUrl,
    getPrevPageUrl: state => state.prevPageUrl,
    getTo: state => state.to,
    getFrom: state => state.from,
    getTotal: state => state.total,
    getSelectedFilter: state => state.selectedFilterValue,
}

const mutations = {
    SET_SELECTED_FILTER : (state,payload) => {
        state.selectedFilterValue = payload
    },
    SET_PAGE: (state, payload) => {
        state.currentPage = payload
    },
    SET_ITEMS: (state, payload) => {
        state.items = payload
    },
    SET_ITEMS_LOADING: (state, payload) => {
        state.itemsLoading = payload
    },
    SET_NEXT_PAGE_URL: (state, payload) => {
        state.nextPageUrl = payload
    },
    SET_PREV_PAGE_URL: (state, payload) => {
        state.prevPageUrl = payload
    },
    SET_TO: (state, payload) => {
        state.to = payload
    },
    SET_TOTAL: (state, payload) => {
        state.total = payload
    },
    SET_FROM: (state, payload) => {
        state.from = payload
    },
}
const actions = {
    fetchItems: async ({
                           commit
                       }, payload) => {
        commit("SET_ITEMS_LOADING", true)
        commit("SET_ITEMS", [])
        return new Promise((resolve, reject) => {
            let field = ''
            if (typeof payload.params!=='undefined' ) {
                field = `field=${payload.params.sort[0].field}&orderBy=${payload.params.sort[0].type}`
            }
            for (var key in state.selectedFilterValue) {
                if(key == 'rateConfirmed'){
                    if(state.selectedFilterValue[key] == 1){
                        field += `&${key}=0`
                    } else {
                        field += `&${key}=1`
                    }
                } else {
                    field += `&${key}=${state.selectedFilterValue[key]}`
                }
            }
            axios.get(`/nova-vendor/shipment-operations/shipments?${field}&page=${payload.params.page}`)
                .then(res => {
                    if (res.status === 200) {
                        if (typeof res.data!=='undefined' && typeof res.data.results!=='undefined') {
                            let { results } = res.data

                            if (res.data.results.data.length > 0) {
                                commit("SET_PREV_PAGE_URL", results.prev_page_url)
                                commit("SET_NEXT_PAGE_URL", results.next_page_url)
                                commit("SET_TO", results.to)
                                commit("SET_FROM", results.from)
                                commit("SET_TOTAL", results.total)
                            }
                            commit("SET_ITEMS_LOADING", false)
                            commit("SET_ITEMS", res.data.results.data)
                            resolve({status: 'ok'})

                        } else {
                            commit("SET_ITEMS_LOADING", [])
                        }
                    } else {
                        reject({status: 'not ok'})
                    }
                }).catch(e => {
                reject({status: 'not ok'})
            })
        })
    },
    fetchPage({ commit}, payload) {
        commit("SET_ITEMS_LOADING", true)
        commit("SET_ITEMS", [])
        return new Promise((resolve, reject) => {
            let field  = ''

            if (typeof payload.params!=='undefined' ) {
                field = `&field=${payload.params.sort[0].field}&orderBy=${payload.params.sort[0].type}`
            }
            for (var key in state.selectedFilterValue) {
                if(key == 'rateConfirmed'){
                    if(state.selectedFilterValue[key] == 1){
                        field += `&${key}=0`
                    } else {
                        field += `&${key}=1`
                    }
                } else {
                    field += `&${key}=${state.selectedFilterValue[key]}`
                }
            }

            axios.get(payload.url + field )
                .then(res => {
                    if (res.status === 200) {
                        if (typeof res.data!=='undefined' && typeof res.data.results!=='undefined') {
                            let { results } = res.data
                            if (res.data.results.data.length > 0) {
                                commit("SET_PREV_PAGE_URL", results.prev_page_url)
                                commit("SET_NEXT_PAGE_URL", results.next_page_url)
                                commit("SET_TO", results.to)
                                commit("SET_FROM", results.from)
                                commit("SET_TOTAL", results.total)
                            }
                            commit("SET_ITEMS_LOADING", false)
                            commit("SET_ITEMS", res.data.results.data)
                            resolve({status: 'ok'})
                        } else {
                            commit("SET_ITEMS", [])
                        }
                    } else {
                        reject({status: 'not ok'})
                    }
                }).catch(e => {
                reject({status: 'not ok'})
            })
        })
    },
    updateRateConfirmed: async ({
                           commit
                       }, payload) => {
        commit("SET_ITEMS_LOADING", true)

        const bodyFormData = new FormData();

        payload.params.forEach((item) => {
            bodyFormData.append('shipment_id_list[]', item);
        });
        return axios.post(`/nova-vendor/shipment-operations/shipments/update-rate-confirmed`, bodyFormData)

    },
    fetchShiflOffices: async ({
                           commit
                       }, payload) => {
        return axios.get(`/nova-vendor/shipment-operations/shipments/shifl-offices`)

    },
    setItems({ commit }, payload) {
        commit("SET_ITEMS", payload)
    },
    setTo({ commit }, payload) {
        commit("SET_TO", payload)
    },
    setFrom({ commit }, payload) {
        commit("SET_FROM", payload)
    },
    setTotal({ commit }, payload) {
        commit("SET_TOTAL", payload)
    },
    setNextPageUrl({ commit }, payload) {
        commit("SET_NEXT_PAGE_URL", payload)
    },
    setPrevPageUrl({ commit }, payload) {
        commit("SET_PREV_PAGE_URL", payload)
    },
    setPage({ commit }, payload) {
        commit("SET_PAGE", payload)
    },
    setItemsLoading({ commit }, payload) {
        commit("SET_ITEMS_LOADING", payload)
    },
    setSelectedFilter({ commit }, payload) {
        commit("SET_SELECTED_FILTER", payload)
    },
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}
