const updateQBOCompanyInfo_CT = (state,payload) => {
    state.qboCompanyInfo = payload
}
const updateResourceId_CT = (state,payload) => {
    state.resourceId = payload
}
const updateQBOCompanyAccount_CT = (state,payload) => {
    state.qboCompanyAccounts = payload;
}


const updateSelectedInvoice_CT = (state,payload) => {
    state.selectedInvoice = payload
}
const showEditInvoiceModal_CT = (state,payload) => {
    state.showEditInvoiceModal = payload;
}
const showDeleteInvoiceModal_CT = (state,payload) => {
    state.showDeleteInvoiceModal = payload;
}

const updateInvoiceTable_CT = (state,payload) => {
    state.updateInvoiceTable = payload;
}

const pushShipmentInvoices_CT = (state, payload) => {
    state.shipmentInvoices = payload;
    let totalInvoiceAmt = 0;
    if(payload.length > 0){
        payload.map(function(o){
            let invoiceSubTotal = 0;
            o.invoice.invoice_services.forEach((service) => {
                invoiceSubTotal = (invoiceSubTotal + (service.quantity * parseFloat(service.rate)))
            });
            totalInvoiceAmt += invoiceSubTotal;
        })
    }
    state.overallTotalShipIncome = totalInvoiceAmt;
}

const pushShipmentBills_CT = (state, payload) => {
    state.shipmentBills = payload;
    let totalBillAmt = 0;
    if(payload.length > 0){
        payload.map(function(o){
            let billSubTotal = 0;
            o.bill.bill_items.forEach((item) => {
                billSubTotal = billSubTotal + parseFloat(item.qbo_amount);
            });
            totalBillAmt += billSubTotal;
        });
    }
    state.overallTotalShipExpense = totalBillAmt;
}
const updateSelectedBill_CT = (state,payload) => {
    state.selectedBill = payload
}
const showEditBillModal_CT = (state,payload) => {
    state.showEditBillModal = payload;
}
const showDeleteBillModal_CT = (state,payload) => {
    state.showDeleteBillModal = payload;
}


export default{
    updateQBOCompanyInfo_CT,
    updateResourceId_CT,
    updateQBOCompanyAccount_CT,
    updateSelectedInvoice_CT,
    showEditInvoiceModal_CT,
    showDeleteInvoiceModal_CT,
    updateInvoiceTable_CT,
    pushShipmentInvoices_CT,
    pushShipmentBills_CT,
    updateSelectedBill_CT,
    showEditBillModal_CT,
    showDeleteBillModal_CT,
}