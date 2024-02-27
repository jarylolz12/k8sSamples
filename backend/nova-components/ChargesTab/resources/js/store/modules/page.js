import axios from 'axios'

const state = {
    servicesSectionValue: [],
    showNotes: true
}

const getters = {
    getServicesSectionValue: state => state.servicesSectionValue,
    getShowNotes: state => state.showNotes
}

const actions = {
    setServicesSectionValue({ commit }, payload) {
        commit('SET_SERVICES_SECTION_VALUE', payload)
    },
    setShowNotes({ commit }, payload) {
        console.log('set show notes', payload)
        commit('SET_SHOW_NOTES', payload)
    }
}

const mutations = {
    SET_SERVICES_SECTION_VALUE: (state, payload) => {
        state.servicesSectionValue = payload
    },
    SET_SHOW_NOTES: (state, payload) => {
        state.showNotes = payload
    }
}
export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}