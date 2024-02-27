import axios from "axios"
import ShipmentModel from '../../models/ShipmentModel'

const betaBaseUrl = process.env.VUE_APP_BASE_URL
const state = {
    consignees: [],
    shippers: [],
    containerSizes: [],
    consigneesLoading: false,
    shippersLoading: false,
    connectedConsignee: 0,
    connectedConsigneeLoading: false,
    connectedShipper: 0,
    connectedShipperLoading: false,
    createBookingShipmentLoading: false,
    containerSizesLoading: false,
    shipmentDetails: null,
    shipmentDetailsLoading: false,
    checkingMblNumber: false,
    shipper_details_info: null,
    buyer_details_info: null,
    shipmentCustomerDocuments: [],
    deleteShipmentCustomerDocumentsLoading: false,
    setMBLNumberData: null,

}

const getters = {
    getShippersDetailsInfo: state => state.shipper_details_info,
    getBuyersDetailsInfo: state => state.buyer_details_info_info,
    getConsignees: state => state.consignees,
    getShipmentDetails: state => state.shipmentDetails,
    getShipmentDetailsLoading: state => state.shipmentDetailsLoading,
    getShippers: state => state.shippers,
    getContainerSizes: state => state.containerSizes,
    getCreateBookingShipmentLoading: state => state.createBookingShipmentLoading,
    getContainerSizesLoading: state => state.containerSizesLoading,
    getConnectedShipper: state => state.connectedShipper,
    getConnectedConsignee: state => state.connectedConsignee,
    getConnectedShipperLoading: state => state.connectedShipperLoading,
    getConnectedConsigneeLoading: state => state.connectedConsigneeLoading,
    getCheckingMblNumber: state => state.checkingMblNumber,
    getShipmentCustomerDocuments: state => state.shipmentCustomerDocuments,
    getDeleteShipmentCustomerDocumentsLoading: state => state.deleteShipmentCustomerDocumentsLoading,
    getMBLNumberData: state => state.setMBLNumberData,
}

const mutations = {
    SET_SHIPPER_DETAILS_INFO: (state, payload) => {
        state.shipper_details_info = payload
    },
    SET_BUYER_DETAILS_INFO: (state, payload) => {
        state.buyer_details_info = payload
    },
    SET_CHECKING_MBL_NUMBER: (state, payload) => {
        state.checkingMblNumber = payload
    },
    SET_CONSIGNEES_LOADING: (state, payload) => {
        state.consigneesLoading = payload
    },
    SET_CONTAINER_SIZES_LOADING: (state, payload) => {
        state.containerSizesLoading = payload
    },
    SET_CONTAINER_SIZES: (state, payload) => {
        state.containerSizes = payload
    },
    SET_CONSIGNEES: (state, payload) => {
        state.consignees = payload
    },
    SET_CONNECTED_CONSIGNEE_LOADING: (state, payload) => {
        state.connectedConsigneeLoading = payload
    },
    SET_CONNECTED_CONSIGNEE: (state, payload) => {
        state.connectedConsignee = payload
    },
    SET_CONNECTED_SHIPPER_LOADING: (state, payload) => {
        state.connectedShipperLoading = payload
    },
    SET_CONNECTED_SHIPPER: (state, payload) => {
        state.connectedShipper = payload
    },
    SET_SHIPPERS_LOADING: (state, payload) => {
        state.shippersLoading = payload
    },
    SET_SHIPPERS: (state, payload) => {
        state.shippers = payload
    },
    SET_CREATE_BOOKING_SHIPMENT_LOADING: (state, payload) => {
        state.createBookingShipmentLoading = payload
    },
    SET_SHIPMENT_DETAILS: (state, payload) => {
        state.shipmentDetails = payload
    },
    SET_SHIPMENT_DETAILS_LOADING: (state, payload) => {
        state.shipmentDetailsLoading = payload
    },
    SET_SHIPMENT_CUSTOMER_DOCUMENTS: (state, payload) => {
        state.shipmentCustomerDocuments = payload
    },
    SET_SHIPMENT_CUSTOMER_DOCUMENTS_LOADING: (state, payload) => {
        state.deleteShipmentCustomerDocumentsLoading = payload
    },
    SET_MBL_NUMBER_DATA: (state, payload) => {
        state.setMBLNumberData = payload
    },
}

/* factory for fetching resource */
function fetchResource({ resources, loading, loaded, url, responseKey }, commit) {

    commit(loading, true)

    return new Promise((resolve, reject) => {

        url = typeof url=='undefined' ? `/v2/${resources}?no_pagination=1&per_page=1000` : url
        axios.get(url).then( res => {
            commit(loading, false)
            if ( typeof res.data !=='undefined' && typeof res.data[responseKey] !=='undefined' ) {
                commit(loaded, res.data[responseKey])
                resolve(res.data[responseKey])
            } else {
                commit(loaded,res.data)
                resolve(res.data)
            }
            
        }).catch(e => {
            commit(loading, false)
            reject(e)
        })
    })
}

const actions = {
    checkMblNumber: async({ commit }, mbl_num) => {  
        commit('SET_CHECKING_MBL_NUMBER', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                let sm = new ShipmentModel(betaBaseUrl)
                sm.checkMblNumber(mbl_num).then(res => {
                    commit('SET_CHECKING_MBL_NUMBER', false)
                    commit('SET_MBL_NUMBER_DATA', res.data);
                    resolve(res)
                }).catch(e => {
                    commit('SET_CHECKING_MBL_NUMBER', false)
                    reject(e)
                })
            }
            proceed()
        })
    },
    fetchBuyerSeller: async({ commit }, params) => {
        return new Promise((resolve, reject) => {
            function proceed() {
                let sm = new ShipmentModel(betaBaseUrl)
                sm.fetchBuyerSupplier(params).then(res => {
                    if ( params.role === 'shipper' ) {
                        commit('SET_SHIPPER_DETAILS_INFO', res.data )
                    } else {
                        commit('SET_BUYER_DETAILS_INFO', res.data)
                    }
                    resolve(res.data)
                }).catch(e => {
                    reject(e)
                })
            }
            proceed()
        })
    },
    fetchShipmentDetails: async({ commit }, id) => {
        commit('SET_SHIPMENT_DETAILS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
              let sm = new ShipmentModel(betaBaseUrl)
              sm.fetchShipmentDetails(id)
                .then((res) => {
                  commit('SET_SHIPMENT_DETAILS_LOADING', false)
                  commit('SET_SHIPMENT_DETAILS', res)
                  resolve(res)
                })
                .catch((err) => {
                  commit('SET_SHIPMENT_DETAILS_LOADING', false)
                  reject(err)
                })
            }
            proceed()
        })
    },
    fetchContainerSizes: async({ commit }) => {
        return new Promise((resolve, reject ) => {
            fetchResource({
                url: '/container-sizes',
                loading: 'SET_CONTAINER_SIZES_LOADING',
                loaded: 'SET_CONTAINER_SIZES',
                responseKey: 'data'
            }, commit).then(res => {
                resolve(res)
            }).catch(e => {
                reject(e)
            })
        })
    },
    getPrefilledConsignee: async({ commit }) => {
        return new Promise((resolve, reject ) => {
            fetchResource({
                url: '/users/connected-consignee',
                loading: 'SET_CONNECTED_CONSIGNEE_LOADING',
                loaded: 'SET_CONNECTED_CONSIGNEE',
                responseKey: 'data'
            }, commit).then(res => {
                resolve(res)
            }).catch(e => {
                reject(e)
            })
        })
    },
    getPrefilledShipper: async({ commit }) => {
        return new Promise((resolve, reject ) => {
            fetchResource({
                url: '/users/connected-shipper',
                loading: 'SET_CONNECTED_SHIPPER_LOADING',
                loaded: 'SET_CONNECTED_SHIPPER',
                responseKey: 'data'
            }, commit).then(res => {
                resolve(res)
            }).catch(e => {
                reject(e)
            })
        })
    },
    getConsigneeOptions: async({ commit }) => {

        fetchResource({
            resources: 'buyers',
            loading: 'SET_CONSIGNEES_LOADING',
            loaded: 'SET_CONSIGNEES',
            responseKey: 'data'
        }, commit)
    },
    createShipment: async({ commit }, payload) => {

        commit('SET_CREATE_BOOKING_SHIPMENT_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
              let sm = new ShipmentModel(betaBaseUrl)
              sm.createBookingShipment(payload)
                .then((res) => {
                  commit('SET_CREATE_BOOKING_SHIPMENT_LOADING', false)
                  resolve(res)
                })
                .catch((err) => {
                  commit('SET_CREATE_BOOKING_SHIPMENT_LOADING', false)
                  reject(err)
                })
            }
            proceed()
        })
    },
    getShipperOptions: async({ commit }) => {
        fetchResource({
            resources: 'suppliers',
            loading: 'SET_SHIPPERS_LOADING',
            loaded: 'SET_SHIPPERS',
            responseKey: 'data'
        }, commit)
    },
    fetchShipmentCustomerDocuments: async({ commit }, id) => {
        commit('SET_SHIPMENT_DETAILS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                let sm = new ShipmentModel(betaBaseUrl)
                sm.fetchShipmentCustomerDocuments(id)
                    .then((res) => {
                        commit('SET_SHIPMENT_DETAILS_LOADING', false)
                        commit('SET_SHIPMENT_CUSTOMER_DOCUMENTS', res)
                        resolve(res)
                    })
                    .catch((err) => {
                        commit('SET_SHIPMENT_DETAILS_LOADING', false)
                        reject(err)
                    })
            }
            proceed()
        })
    },
    deleteShipmentCustomerDocuments: async({ commit }, id) => {
        commit('SET_SHIPMENT_CUSTOMER_DOCUMENTS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                let sm = new ShipmentModel(betaBaseUrl)
                sm.deleteShipmentCustomerDocuments(id)
                    .then((res) => {
                        commit('SET_SHIPMENT_CUSTOMER_DOCUMENTS_LOADING', false)
                        resolve(res)
                    })
                    .catch((err) => {
                        commit('SET_SHIPMENT_CUSTOMER_DOCUMENTS_LOADING', false)
                        reject(err)
                    })
            }
            proceed()
        })
    },

}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}