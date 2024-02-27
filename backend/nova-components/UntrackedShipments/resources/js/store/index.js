
const state = {
    sort: {
        field: 'etd',
        type: 'desc'
    },
    selectedFilter: {
        type: 'all',
        name: 'All Shipments'
    },
    stf : [],
    selectedModeFilter: null,
    selectedMblFilter: null,
    selectedOfficeFromFilter: null,
    selectedExternalInternalFilter: null,
    currentPage: 1,
    perPage: 10

}

const getters ={
    getSort : state => state.sort,
    getSelectedFilter: state => state.selectedFilter,
    getCurrentPage : state => state.currentPage,
    getPerPage: state => state.perPage,
    getStf: state => state.stf,
    getSelectedMblFilter: state => state.selectedMblFilter,
    getSelectedModeFilter: state => state.selectedModeFilter,
    getSelectedOfficeFromFilter: state => state.selectedOfficeFromFilter,
    getSelectedExternalInternalFilter: state => state.selectedExternalInternalFilter,
}

const mutations = {
    SET_SORT : (state,payload) => {
        state.sort = payload
    },
    SET_SELECTED_FILTER : (state,payload) => {
        state.selectedFilter = payload
    },
    SET_CURRENT_PAGE:(state,payload) => {
        state.currentPage = payload
    },
    SET_PER_PAGE: (state,payload) => {
        state.perPage = payload
    },
    SET_STF: (state,payload) => {
        state.stf = payload
    },
    SET_SELECTED_MBL_FILTER : (state,payload) => {
        state.selectedMblFilter = payload
    },
    SET_SELECTED_MODE_FILTER: (state,payload) => {
        state.selectedModeFilter = payload
    },
    SET_SELECTED_OFFICE_FROM_FILTER: (state, payload) => {
        state.selectedOfficeFromFilter = payload
    },
    SET_SELECTED_EXTERNAL_INTERNAL_FILTER: (state, payload) => {
        state.selectedExternalInternalFilter = payload
    }
}
const actions = {

}

export default {
    state,
    mutations,
    actions,
    getters
}
