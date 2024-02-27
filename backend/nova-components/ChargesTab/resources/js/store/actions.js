import axios from 'axios';


const fetchShipmentInvoices = ({commit},shipment_id) => {
    axios.get("/custom/invoices/by-shipment/"+shipment_id)
    .then(response => {
        if(response.data.success){
            const responseData = response.data.results;
            if(responseData.length > 0){
                commit('pushShipmentInvoices_CT',responseData);
            }else{
                commit('pushShipmentInvoices_CT',[]);
            }
        }else{
            commit('pushShipmentInvoices_CT',[]);
        }
    })
}

const updateBill = async ({ commit }, payload) => {

    return new Promise((resolve, reject) => {
        axios.post("/custom/bill-payment-lists/" + payload.id, payload)
        .then (res => {
             if (res.status === 200) {
                if (typeof res.data!=='undefined' && typeof res.data.status!=='undefined') {
                    resolve({status: 'ok'})
                } else {
                    reject({status: 'not ok'})
                }
            } else {
                reject({status: 'not ok'})
            }
        }).catch(e => {
            reject({status: 'not ok'})
        })
    })
    
}

const fetchShipmentBills = ({commit},shipment_id) => {
    axios.get("/custom/bill/by-shipment/"+shipment_id)
    .then(response => {
        if(response.data.success){
            const responseData = response.data.results;
            if(responseData.length > 0){
                commit('pushShipmentBills_CT',responseData);
            }else{
                commit('pushShipmentBills_CT',[]);
            }
        }else{
            commit('pushShipmentBills_CT',[]);
        }
    })
}

export default{
    fetchShipmentInvoices,
    fetchShipmentBills,
    updateBill
}