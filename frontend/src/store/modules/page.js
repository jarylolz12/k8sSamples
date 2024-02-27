const state = {
  currentPage: 'test page',
  currentTab: 1,
  currentShipmentPage: 1,
  currentPendingShipmentTab: 0,
  currentSettingsTab: 0,
  currentSettingsTabName: 'users',
  currentInventoryTab: 0,
  betaBaseUrl: process.env.VUE_APP_BASE_URL,
  poBaseUrl: process.env.VUE_APP_PO_URL,
  currentAccountingTab: 0,
  creatingBookingRequest: false,
  addingShipmentTracking: false,
  markingBookedExternal: false,
  editingShipment: false,
  editingDraftShipment: false,
  currentPath: {},
};

const getters = {
  getBetaUrl: (state) => state.betaBaseUrl,
  getPoBaseUrl: (state) => state.poBaseUrl,
  getCurrentPage: (state) => state.currentPage,
  getMarkingBookedExternal: (state) => state.markingBookedExternal,
  getAddingShipmentTracking: (state) => state.addingShipmentTracking,
  getCreatingBookingRequest: (state) => state.creatingBookingRequest,
  getEditingShipment: (state) => state.editingShipment,
  getEditingDraftShipment: (state) => state.editingDraftShipment,
  getCurrentPath: (state) => state.currentPath
};

const actions = {
    setMarkingBookedExternal({ commit }, payload) {
        commit('setMarkingBookedExternal', payload)
    },
    setEditingShipment({ commit }, payload) {
        commit('setEditingShipment', payload)
    },
    setEditingDraftShipment({ commit }, payload) {
        commit('setEditingDraftShipment', payload)
    },
    setAddingShipmentTracking({ commit }, payload) {
        commit('setAddingShipmentTracking', payload)
    },
    setCreatingBookingRequest({ commit }, payload) {
        commit('setCreatingBookingRequest', payload)
    },
    setPage({ commit }, payload) {
        commit('setPage', payload);
    },
    setTab({ commit }, payload) {
        commit('setTab', payload);
    },
    setCurrentShipmentPage({ commit }, payload) {
        commit('setCurrentShipmentPage', payload);
    },
    setCurrentPendingShipmentTab({ commit }, payload) {
        commit('setCurrentPendingShipmentTab', payload);
    },
    setCurrentSettingsTab({ commit }, payload) {
        commit('setCurrentSettingsTab', payload);
    },
    setCurrentSettingsTabName({ commit }, payload) {
        commit('setCurrentSettingsTabName', payload);
    },
    setCurrentInventoryTab({ commit }, payload) {
        commit('setCurrentInventoryTab', payload);
    },
    setCurrentAccountingTab({ commit }, payload) {
        commit('setCurrentAccountingTab', payload);
    },
    setCurrentPath({ commit }, payload) {
        commit('setCurrentPath', payload);
    }
};

const mutations = {
    setPage: (state, payload) => {
        state.currentPage = payload;
    },
    setTab: (state, payload) => {
        state.currentTab = payload;
    },
    setCurrentShipmentPage: (state, payload) => {
        state.currentShipmentPage = payload;
    },
    setCurrentPendingShipmentTab: (state, payload) => {
        state.currentPendingShipmentTab = payload;
    },
    setCurrentSettingsTab: (state, payload) => {
        state.currentSettingsTab = payload;
    },
    setCurrentSettingsTabName: (state, payload) => {
        state.currentSettingsTabName = payload;
    },
    setCurrentInventoryTab: (state, payload) => {
        state.currentInventoryTab = payload;
    },
    setCurrentAccountingTab: (state, payload) => {
        state.currentAccountingTab = payload;
    },
    setMarkingBookedExternal: (state, payload) => {
        state.markingBookedExternal = payload;
    },
    setAddingShipmentTracking: (state, payload) => {
        state.addingShipmentTracking = payload;
    },
    setCreatingBookingRequest: (state, payload) => {
        state.creatingBookingRequest = payload;
    },
    setEditingShipment: (state, payload) => {
        state.editingShipment = payload;
    },
    setEditingDraftShipment: (state, payload) => {
        state.editingDraftShipment = payload;
    },
    setCurrentPath:(state, payload) => {
        state.currentPath = payload;
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
};
