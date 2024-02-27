import ShipmentModel from '../models/ShipmentModel'
import DocumentModel from '../models/DocumentModel'

const state = {
    shipmentSupplierGroupField: {
        attribute: "suppliers_group",
        name: "HBL Section",
        value: '',
        loading: false
    },
}

const getters = {
    getShipmentSupplierGroupField: state => state.shipmentSupplierGroupField
}

const actions = {
    setShipmentSupplierGroupField({ commit }, payload) {
        commit("SET_SUPPLIERS_FIELD", payload)
    },
    async deleteDocument( { commit }, payload ) {
        return new Promise( (resolve, reject) => {
            let {
                item,
                url
            } = payload
            let d = new DocumentModel(url)
            d.deleteDocument({
                item
            }).then(() => {
                resolve()
            }).catch(e => {
                reject(e)
            })
        })
    },
    async uploadDocuments( { commit }, payload ) {
        return new Promise( (resolve, reject) => {
            let {
                resourceId,
                documents,
                url
            } = payload

            let d = new DocumentModel(url)
            d.uploadDocuments({
                resourceId,
                documents
            }).then(() => {
                resolve()
            }).catch(e => {
                reject(e)
            })
        })
    },
    fetchShipmentSuppliers({ commit }, payload) {
        let {
            shipment_supplier_field,
            base_url,
            shipment_id
        } = payload

        let {
            value
        } = shipment_supplier_field

        let get_suppliers_group = []
        try {
            get_suppliers_group = JSON.parse(value)
        } catch(e) {
            get_suppliers_group = value
        }

        shipment_supplier_field.loading = true
        commit('SET_SUPPLIERS_FIELD', shipment_supplier_field)
        let sm = new ShipmentModel(base_url)

        sm.fetchSuppliers({
            get_suppliers_group,
            shipment_id
        }).then( response => {
            if ( typeof response.data.results!=='undefined' ) {
                get_suppliers_group.map( (gsg, key) => {
                    let get_corresponding_supplier = _.find(response.data.results, e => (e.id == parseInt(gsg.supplier_id)))
                    if ( typeof get_corresponding_supplier.new_documents!=='undefined') {
                        get_corresponding_supplier.documents = get_corresponding_supplier.new_documents
                        let commercial_documents_customer = []
                        let packing_list_customer = []
                        let invoice_packing_list_customer = []
                        let commercial_invoice_customer = []
                        let delivery_order_customer = []
                        let hbl_customer = []
                        if ( typeof get_corresponding_supplier.documents!=='undefined' && get_corresponding_supplier.documents.length > 0 ) {

                            get_corresponding_supplier.documents.map( gcsd => {
                                gcsd.is_deleting_loading = false
                                let {
                                    type
                                } = gcsd

                                if ( type === 'Other Commercial Docs' ) {
                                    commercial_documents_customer.push(gcsd)
                                } else if( type === 'Invoice And Packing List' ) {
                                    invoice_packing_list_customer.push(gcsd)
                                } else if ( type === 'Commercial Invoice' ) {
                                    commercial_invoice_customer.push(gcsd)
                                } else if ( type === 'Delivery Order' ) {
                                    delivery_order_customer.push(gcsd)
                                } else if ( type === 'Hbl' ) {
                                   hbl_customer.push(gcsd)
                                } else if ( type === 'Delivery Order' ) {
                                    delivery_order_customer.push(gcsd)
                                } else if ( type === 'Packing List') {
                                    packing_list_customer.push(gcsd)
                                }
                            })
                        }
                        get_suppliers_group[key].commercial_documents_customer =commercial_documents_customer
                        get_suppliers_group[key].packing_list_customer =  packing_list_customer
                        get_suppliers_group[key].invoice_packing_list_customer = invoice_packing_list_customer
                        get_suppliers_group[key].commercial_invoice_customer = commercial_invoice_customer
                        get_suppliers_group[key].delivery_order_customer = delivery_order_customer
                        get_suppliers_group[key].hbl_customer = hbl_customer

                    }
                    get_suppliers_group[key].is_loading = false
                })
                shipment_supplier_field.value = JSON.stringify(get_suppliers_group)
                shipment_supplier_field.supplier_updated = true
                shipment_supplier_field.loading = false
                commit('SET_SUPPLIERS_FIELD', shipment_supplier_field)
            }
        }).catch( e => {
            shipment_supplier_field.loading = false
            commit('SET_SUPPLIERS_FIELD', shipment_supplier_field)
        })
    }
}
const mutations = {
    SET_SUPPLIERS_FIELD: (state, payload) => {
        state.shipmentSupplierGroupField = payload
    }
}
export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}
