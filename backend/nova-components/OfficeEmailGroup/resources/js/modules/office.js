const state = {
    item: {},
    items: [],
    itemLoading: false,
    itemsLoading: false
}

const getters = {
    getItem: state => state.item,
    getItems: state => state.items,
    fetchItemLoading: state => state.itemLoading,
    fetchItemsLoading: state => state.itemsLoading
}

const actions = {
    setItems: ({ commit }, payload) => {
        commit('SET_ITEMS', payload)
    },
    setItem: ({ commit }, payload) => {
        commit('SET_ITEM', payload)
    },
    setItemLoading: ({ commit }, payload) => {
        commit('SET_ITEM_LOADING', payload)
    },
    setItemsLoading: ({ commit }, payload) => {
        commit('SET_ITEMS_LOADING', payload)
    },
    fetchItem: async({
        commit
    }, id) => {
        return new Promise((resolve, reject) => {
            commit('SET_ITEM_LOADING', true)
            Nova.request().get(`/nova-vendor/kenetashi/office-email-group/office/${id}`).then(r => {
                resolve(r)
            }).catch(e => {
                reject(e)
            })
        })
    },
}

const mutations = {

    SET_ITEMS_LOADING: (state, payload) => {
        state.itemsLoading = payload
    },
    SET_ITEM_LOADING: (state, payload) => {
        state.itemLoading = payload
    },
    SET_ITEMS: (state, payload) => {
        state.items = payload
    },
    SET_ITEM: (state, payload) => {
        state.item = payload
    },
}

export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}
