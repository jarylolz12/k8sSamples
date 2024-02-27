import axios from 'axios'

const state = {
    customer_loading: false,
    default_customer: null,
    sales_representative: null
}

const getters = {
    getDefaultCustomer: state => state.default_customer,
    getCustomerByShipmentLoading: state => state.customer_loading,
    getSalesRepresentative: state => state.sales_representative
}

const actions = {
    getCustomer({ commit }, payload) {
        let {
            shipment_id
        } = payload
        commit('SET_CUSTOMER_LOADING', true)
        return new Promise((resolve, reject) => {
            
            axios(`/custom/customer-by-shipment?shipment_id=${shipment_id}`).then(response => {
                commit('SET_CUSTOMER_LOADING', false)
                if ( typeof response.data!=='undefined' && response.data.customer!==null ){
                    commit('SET_DEFAULT_CUSTOMER', response.data.customer)
                    commit('SET_SALES_REPRESENTATIVE', response.data.customer.sales_representative)
                }

                resolve(response)
            }).catch(err => {
                commit('SET_CUSTOMER_LOADING', false)
                reject(err)
            })

        })
    }
}

const mutations = {
    SET_CUSTOMER_LOADING: (state, payload) => {
        state.customer_loading = payload
    },
    SET_SALES_REPRESENTATIVE: (state, payload) => {
        state.sales_representative = payload
    },
    SET_DEFAULT_CUSTOMER: (state, payload) => {
        state.default_customer = payload
    },
}
export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}