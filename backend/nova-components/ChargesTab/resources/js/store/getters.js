export default {
    showEditInvoiceModal: state => state.showEditInvoiceModal,
    showDeleteInvoiceModal: state => state.showDeleteInvoiceModal,
    updateInvoiceTable: state => state.updateInvoiceTable,
    shipmentInvoices: state => state.shipmentInvoices,

    showEditBillModal: state => state.showEditBillModal,
    shipmentBills: state => state.shipmentBills,
    showDeleteBillModal: state => state.showDeleteBillModal,

    overallTotalShipIncome: state => state.overallTotalShipIncome,
    overallTotalShipExpense: state => state.overallTotalShipExpense,

    qboCompanyAccounts: state => state.qboCompanyAccounts,
}