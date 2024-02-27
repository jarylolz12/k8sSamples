import axios from 'axios'

const state = {
    items: [],
    attachments_loading: false,
    edit_attachments: [],
    delete_bill_attachment_loading: false
}

const getters = {
    getItems: state => state.items,
    getAttachmentsLoading: state => state.attachments_loading,
    getEditAttachments: state => state.edit_attachments,
    getDeleteBillAttachmentLoading: state => state.delete_bill_attachment_loading
}

const actions = {
    deleteBillAttachment({ commit }, payload) {
        let {
            bill_id,
            qbo_bill_id,
            shipment_id,
            qbo_attachable_id
        } = payload

        commit('SET_DELETE_BILL_ATTACHMENT_LOADING', true)
        return new Promise((resolve, reject) => {
            let fd = new FormData()
            
            fd.append('bill_id', bill_id)
            fd.append('qbo_bill_id', qbo_bill_id)
            fd.append('shipment_id', shipment_id)
            fd.append('qbo_attachable_id', qbo_attachable_id)

            let passedData = {
                url: '/custom/qbo/bills/delete-bill-attachment',
                method: 'POST',
                data: fd
            }
            axios(passedData).then(response => {
                commit('SET_DELETE_BILL_ATTACHMENT_LOADING', false)
                resolve(response)
            }).catch(err => {
                commit('SET_DELETE_BILL_ATTACHMENT_LOADING', false)
                reject(err)
            })

        })

    },
    async fetchBillAttachments({ commit }, payload) {
        commit('SET_ATTACHMENTS_LOADING', true)
        commit('SET_EDIT_ATTACHMENTS', [])
        return new Promise((resolve, reject) => {
            let {
                shipment_id,
                bill_id
            } = payload

            axios.get(`/custom/qbo/bills/bill-attachments?shipment_id=${shipment_id}&bill_id=${bill_id}`).then( response => {
                commit('SET_ATTACHMENTS_LOADING', false)
                if ( typeof response.data!=='undefined' && typeof response.data.items!=='undefined') {
                    commit('SET_EDIT_ATTACHMENTS', response.data.items)
                    resolve(response.data.items)
                } else {
                    reject({
                        error: 'An unexpected error occured.'
                    })
                }
            }).catch( e => {
                commit('SET_ATTACHMENTS_LOADING', false)
                reject(e)
            })
        })
    },
    async uploadBillAttachment({ commit }, payload) {
        return new Promise((resolve, reject) => {
            
            let {
                bill_id,
                qbo_bill_id,
                bill_attachment_file,
                shipment_id
            } = payload

            let fd = new FormData()
            
            if ( bill_attachment_file.length > 0 ) {
                for ( var x = 0; x < bill_attachment_file.length; x++) {
                    fd.append(`bill_attachment_file[${x}][file]`, bill_attachment_file[x].file)
                }
            }
            fd.append('qbo_bill_id', qbo_bill_id)
            fd.append('shipment_id', shipment_id)
            fd.append('bill_id', bill_id)

            let passedData = {
                url: '/custom/qbo/bills/bill-attachment',
                method: 'POST',
                data: fd
            }

            axios(passedData).then(response => {
                resolve(response)
            }).catch(err => {
                reject(err)
            })
        })
    }
}

const mutations = {
    SET_DELETE_BILL_ATTACHMENT_LOADING: (state, payload) => {
        state.delete_bill_attachment_loading = payload
    },
    SET_ITEMS: (state, payload) => {
        state.items = payload
    },
    SET_EDIT_ATTACHMENTS: (state, payload) => {
        state.edit_attachments = payload
    },
    SET_ATTACHMENTS_LOADING: (state, payload) => {
        state.attachments_loading = payload
    }
}
export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}