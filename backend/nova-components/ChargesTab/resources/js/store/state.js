export default () => ({
    qboCompanyInfo:null,
    resourceId:null,
    qboCompanyAccounts:null,

    selectedInvoice:null,
    showEditInvoiceModal:false,
    showDeleteInvoiceModal:false,
    updateInvoiceTable:false,

    selectedBill:null,
    showEditBillModal:false,
    showDeleteBillModal:false,

    shipmentInvoices:[],
    shipmentBills:[],

    overallTotalShipIncome:0,
    overallTotalShipExpense:0,
})