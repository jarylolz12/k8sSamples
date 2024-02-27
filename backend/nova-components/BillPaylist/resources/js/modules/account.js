const state = {
    items: [],
    itemsLoading: true,
    currentPage: 1,
    prevPageUrl: null,
    nextPageUrl: null,
    from: 0,
    to: 0,
    total: 0
}

const getters = {
    getItems: state => state.items,
    getItemsLoading: state => state.itemsLoading
}

const actions = {

    fetchAccounts({ commit }, payload) {

        commit("SET_ITEMS", [])
        commit("SET_ITEMS_LOADING", true)

        return new Promise((resolve, reject) => {
            axios.get('/custom/bill-payment-lists/qb/accounts')
            .then(res => {
                commit("SET_ITEMS_LOADING", false)
                if (res.status === 200) {
                    if (typeof res.data!=='undefined' && typeof res.data.results!=='undefined') {
                        let { results } = res.data
                        commit("SET_ITEMS", results)
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
}

const mutations = {
    SET_ITEMS: (state, payload) => {
        state.items = payload
    },
    SET_ITEMS_LOADING: (state, payload) => {
        state.itemsLoading = payload
    }
}
export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}