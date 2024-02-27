<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

use Vishalmarakana\ShipmentNotes\Http\Controllers\ShipmentNotesController;
// use App\Http\Controllers\CountryStateCityController;

Auth::routes(['register' => false]);

Route::get('check-processed', 'TestController@checkProcessed');

Route::get('update-shipments-test', 'TestController@updateShipments');

Route::post('/update-webhooks', 'Notifications\RealTimeController@updateWebhooks');

Route::get('/download', 'DownloadController@download');

Route::webhooks('shifl-entry-webhook');

Route::post('push-notifications', 'QuickbooksController@getPushNotifications');

Route::get('test-route-data', 'TestController@getTestData');

Route::get('update-rate-confirmed-script', 'ShipmentController@updateRateConfirmed');


Route::get('test-request', 'TestController@testRequest');

Route::get('bottom-shipments','TestController@getBottomShipments');

Route::middleware('auth')->prefix('custom-api')->group(function () {

    Route::post('shipment/supplier-section/file/delete', 'API\FileController@deleteShipmentSupplierSectionFile');
    Route::get('{resource_name}/{resource_id}/download/{field}', 'API\FileController@download');
    Route::get('{resource_name}/{resource_id}/download-custom/{field}', 'API\FileController@downloadCustom');
    Route::delete('{resource_name}/{resource_id}/field/{attribute}', 'API\FileController@delete');
    Route::get('crux-container-detail/{container_num}', 'API\CruxContainerDetailController@index');

    /**
     *  api support for Status tab.
     */
    Route::get('/shipments/status/terminal49/{mbl_num}', 'API\StatusTabController@index');
    Route::get('/shipments/status/eta_logs/{mbl_num}', 'API\EtaLogsController@index');
    Route::post('/shipments/status/terminal49/{mbl_num}/ignore_demurrage', 'API\StatusTabController@ignoreDemurrage');
    Route::post('/shipments/status/terminal49/resync', 'API\StatusTabController@resync');
    Route::post('/shipments/status/terminal49/raw_events', 'API\StatusTabController@raw_events');
    Route::post('/shipments/status/terminal49/standard_events', 'API\StatusTabController@standard_events');
    Route::post('/shipments/status/terminal49/terminals', 'API\StatusTabController@terminals');
    Route::get('/shipments/status/changelogs/{mbl_num}', 'API\StatusTabController@changelogs');
});
//
Route::prefix('api-services')->group(function () {
    Route::get('documentation', "API\CLIENT\DocumentationController@index")->name('api.services.documentation');
//    Route::get('documentation', "API\CLIENT\DocumentationController@index2")->name('api.services.documentation2');
//    Route::get('documentation', "API\CLIENT\DocumentationController@shipmentIntegration")->name('api.services.shipment-integration');
    Route::get('step-docs', "API\CLIENT\DocumentationController@stepsDocs")->name('api.services.steps-docs');
    Route::get('internal-token', "API\CLIENT\DocumentationController@genToken")->name('api.services.internal-token');
    Route::get('/developers', 'HomeController@index')->name('api.services.dashboard');
});

Route::group(['middleware' => 'auth'], function () {
    //return $request->user();
    Route::group(
        [
            'prefix' => 'custom',
        ],
        function () {
            Route::get('customer-by-shipment', 'CustomerController@getCustomerByShipment');
            Route::get('testingg', 'TestController@testingg');
            Route::get('indicate', 'TestController@indicate');
            Route::get('default-invoice-update','TestController@defaultInvoiceUpdate');
            Route::get('missing-invoices', 'TestController@dynamicInvoices');

            Route::get('update-processed-test', 'TestController@updateProcess');
            Route::get('update-processed-test-another', 'TestController@updateProcessAnother');

            Route::post('/delete-multiple-documents-admin', 'DocumentController@deleteDocuments');
            Route::post('/upload-multiple-documents-admin', 'DocumentController@insertMultipleDocuments');
            Route::get('/so-documents/{shipmentId}', 'DocumentController@getSoDocuments');
            Route::post('/ams-cut-off/save', 'SoController@SaveAmsCutOff');
            Route::get('/process-documents', 'ProcessingController@processDocuments');
            Route::get('/shipment-customer-documents', 'DocumentController@fetchDocuments');
            Route::get('/qbo/bills/bill-attachments', 'BillController@getBillAttachments');
            Route::post('/qbo/bills/bill-attachment', 'BillController@handleBillAttachment');
            Route::post('/qbo/bills/delete-bill-attachment', 'BillController@handleDeleteBillAttachment');
            Route::get('/process-documents', 'ProcessingController@processDocuments');
            Route::get('/unprocessed-invoices', 'ProcessingController@unProcessed');
            Route::get('/customer-admins/search', 'CustomerController@searchForCustomerAdmin');
            Route::post('/customer-admins/detach-customer', 'CustomerController@detachCustomer');
            Route::post('/customer-admins/attach-customer', 'CustomerController@attachCustomer');
            Route::get('/customers-by-customer-admin', 'CustomerController@getCustomersByCustomerAdmin');

            Route::get('/vendors', 'VendorController@index');
            Route::get('/vendor/{id}', 'VendorController@show');
            Route::get('/vendors/search', 'VendorController@search');


            Route::get('/agents', 'AgentController@index');
            Route::get('/agent/{id}', 'AgentController@show');

            Route::get('/quickbook-invoice-details/{id}', 'QuickbooksController@getQuickbookInvoiceDetails');

            Route::get('/download-mbl-copy-surrendered/{shipment_id}', 'ShipmentController@downloadMblCopySurrendered');
            Route::get('/delete-all-vendors-system', 'BillPaymentListController@deleteVendors');
            Route::post('/create-brex-vendor', 'BillPaymentListController@createBrexVendor');
            Route::get('/get-brex-vendor/{brex_id}', 'BillPaymentListController@getBrexVendor');
            Route::get('/get-brex-vendors', 'BillPaymentListController@getBrexVendors');
            Route::get('/get-bill-qb-info/{bill_id}', 'BillPaymentListController@getBillQbInfo');
            Route::get('/get-all-process-bills', 'BillPaymentListController@getAllBills');
            Route::get('/process-correct-bill/{bill_id}', 'BillPaymentListController@processCorrectBill');
            Route::get('/correct-bills', 'BillPaymentListController@correctBills');
            //Route::get('/check-bill-status', 'TestController@checkBillStatus');

            Route::post('/send-booking-email', 'ShipmentController@sendBookingEmail');
            Route::post('/send-booking-agent', 'ShipmentController@sendBookingToAgent');
            Route::get('/get-booking-agent-email/{agentId}', 'ShipmentController@getAgentBookingEmail');
            Route::post('/bill-payment-lists/{id}', 'BillPaymentListController@update');
            Route::get('/bill-payment-lists', 'BillPaymentListController@index');
            Route::get('/bill-payment-lists/search', 'BillPaymentListController@search');
            Route::post('/bill/pay', 'BillPaymentListController@pay');
            Route::get('/bill-payment-lists/qb/bill/{bill_id}', 'BillPaymentListController@findQbBillById');
            Route::get('/bill-payment-lists/qb/accounts', 'BillPaymentListController@getAllAccounts');

            Route::delete('/bill-payment-lists/remove-bill/{bill_paylist_id}', 'BillPaymentListController@removeBill');
            Route::get('/sync-external-vendors-quickbooks', 'BillPaymentListController@syncExternalVendors');
            Route::get('/sync-external-vendors-brex', 'BillPaymentListController@syncExternalVendorsBrex');
            Route::get('/process-get-all-customers', 'ProcessingController@getAllCustomers');
            Route::get('/process-get-all-invoices', 'ProcessingController@getAllInvoices');
            Route::get('/process-single-customer/{customer_id}', 'ProcessingController@processSingleCustomer');
            Route::get('/process-single-notification/{notification_id}', 'ProcessingController@processSingleNotification');
            Route::get('/process-get-all-bills', 'ProcessingController@getAllBills');
            Route::get('/process-single-bill/{bill_id}', 'ProcessingController@processSingleBill');
            Route::get('/process-bills', 'ProcessingController@processBills');
            Route::get('/process-get-all-notifications', 'ProcessingController@getAllNotifications');
            Route::get('/process-delete-notifications', 'ProcessingController@deleteNotifications');
            Route::get('/process-delete-all-notifications', 'ProcessingController@deleteAllNotifications');
            Route::get('/process-old-customers', 'ProcessingController@processCustomersView');
            Route::get('/process-notifications', 'ProcessingController@processNotificationsView');
            Route::get('/process-single-invoice/{invoice_id}', 'ProcessingController@processSingleInvoice');
            Route::get('/process-old-customers', 'ProcessingController@processCustomersView');
            Route::get('/process-invoices', 'ProcessingController@processInvoices');

            Route::get('/ss/test-now', 'ProcessingController@testtest');

            Route::get('/shifl-office/search', 'ShiflOfficeController@search');
            Route::get('/shifl-office/find-by-id', 'ShiflOfficeController@findById');
            Route::get('shifl-office/find-by-manager', 'ShiflOfficeController@findByManager');
            Route::get('/shifl-offices', 'ShiflOfficeController@getAll');

            Route::get('/bugs-fixer-view', 'ProcessingController@processBugView');
            Route::get('/process-bug-shipment/{shipment_id}', 'ProcessingController@processBugShipment');
            Route::get('/get-all-bugs-shipment', 'ProcessingController@getAllBugsShipment');
            Route::get('/test-email-view', 'ProcessingController@testEmailView');
            Route::get('/sale-agents/find-by-id', 'SaleAgentController@findSaleAgentById');
            Route::get('/sale-agents/index', 'SaleAgentController@index');
            Route::post('/account-managers', 'AccountManagerController@add');
            Route::delete('/account-managers', 'AccountManagerController@remove');
            Route::get('/account-managers/check', 'AccountManagerController@check');
            Route::get('/account-managers/index', 'AccountManagerController@index');
            Route::get('/account-managers/find-by-id', 'AccountManagerController@findAccountManagerById');
            Route::get('/account-managers/search', 'AccountManagerController@search');
            Route::post('/account-managers/where-in', 'AccountManagerController@whereIn');
            Route::get('/account-managers', 'AccountManagerController@getAll');
            Route::get('/account-managers-old', 'AccountManagerController@getAllOld');
            Route::get('/services', 'ServiceController@getAll');
            Route::post('/services/where-in', 'ServiceController@whereIn');


            Route::get('/suppliers', 'SupplierController@getAll');
            Route::get('/supplier', 'SupplierController@findById');
            Route::post('/suppliers/where-in', 'SupplierController@whereIn');
            Route::post('/suppliers/where-in-custom', 'SupplierController@whereInCustom');
            Route::get("connected-customer/{id}/{type}/{connectedId}", "SupplierController@getConnectedCustomer");

            Route::post('/buyers/where-in', 'BuyerController@whereIn');

            Route::get('/terminal-regions', 'TerminalRegionController@getAll');
            Route::get('/terminal-region', 'TerminalRegionController@findById');
            Route::post('/terminal-regions/where-in', 'TerminalRegionController@whereIn');

            Route::get('/shipment', 'ShipmentController@findById');
            Route::get('/shipment/get-reference-number/{shipment_id}', 'ShipmentController@getReferenceNumber');
            Route::get('/shipment/get-for-invoice', 'ShipmentController@getShipmentDataForInvoice');

            Route::get('/container-sizes', 'ContainerSizeController@getAll');
            Route::get('/container-size', 'ContainerSizeController@findById');
            Route::post('/container-sizes/where-in', 'ContainerSizeController@whereIn');

            Route::get('/containers', 'ContainerController@getAll');
            Route::post('/containers/where-in', 'ContainerController@whereIn');

            Route::get('/incoterms', 'IncoTermController@getAll');
            Route::get('/incoterm', 'IncoTermController@findById');
            Route::post('/incoterms/where-in', 'IncoTermController@whereIn');
            Route::get('/download', 'DownloadController@download');

            Route::get('/purchase-orders', 'PurchaseOrderController@getAll');
            Route::post('/purchase-orders/where-in', 'PurchaseOrderController@whereIn');

            Route::post('/shipment-tab-save', 'ShipmentController@saveByTab');

            //truckers
            Route::get('/truckers/search', 'TruckerController@search');
            Route::post('/truckers/save', 'TruckerController@save');

            //terminals
            Route::get('/terminals/search', 'TerminalController@search');
            Route::post('/terminals/save', 'TerminalController@save');

            //carriers
            Route::get('/carriers', 'CarrierController@getAll');
            Route::get('/carriers/search', 'CarrierController@search');
            Route::post('/carriers/save', 'CarrierController@save');
            Route::post('/carriers/where-in', 'CarrierController@whereIn');

            //departure notice
            Route::post('/departure-notice/save', 'DepartureNoticeController@save');


            //bookings save
            Route::post('/bookings/save', 'BookingsTabController@save');

            //select schedule from bookings
            Route::post('/bookings/select-schedule', 'BookingsTabController@selectSchedule');

            //cancel a schedule from bookings
            Route::post('/bookings/cancel-schedule', 'BookingsTabController@cancelSchedule');

            //select agent confirmation from bookings
            Route::post('/bookings/select-agent-confirmation', 'BookingsTabController@selectAgentConfirmation');

            //cancel a agent confirmation from bookings
            Route::post('/bookings/cancel-agent-confirmation', 'BookingsTabController@cancelAgentConfirmation');

            Route::post('/shipment-header-info/{id}', 'ShipmentController@updateHeaderInfo');

            Route::get('/process-departure-notice-database', 'ProcessingController@process');

            Route::get('/process-get-all-schedules', 'ProcessingController@getAll');

            Route::get('/process-single-shipment/{shipment_id}', 'ProcessingController@processSingleShipment');

            //get items by customer
            Route::get('/items-by-customer/{id}', 'ItemController@getByCustomer');

            Route::post('/qbo/invoice/add', 'ShipmentQBOController@addInvoice');
            Route::post('/qbo/invoice/edit', 'ShipmentQBOController@editInvoice');
            Route::post('/qbo/invoice/delete', 'ShipmentQBOController@deleteInvoice');
            Route::post('/qbo/invoice/send-invoice', 'ShipmentQBOController@sendInvoice');

            Route::get('/qbo/invoice/get-by-qbo-id', 'QBOController@getInvoiceById');

            //Route::get('/qbo/services', 'ShipmentQBOController@getServices');
            //Route::get('/qbo/customers', 'ShipmentQBOController@getCustomers');
            Route::get('/qbo/terms', 'ShipmentQBOController@getTerms');
            /* Route::get('/qbo/customers-search/{name}', 'ShipmentQBOController@searchCustomers'); */
            Route::get('/qbo/customers-search', 'ShipmentQBOController@searchCustomers');
            Route::get('/qbo/services-search', 'ShipmentQBOController@searchServices');

            Route::get('/invoices/by-shipment/{shipmentId}', 'InvoiceController@getByShipment');
            // Route::get('/invoices/by-shipment-by-qbo', 'InvoiceController@getByShipmentAndByQBO');
            Route::get('/invoices/get-invoice-attachments/{invoice_id}', 'InvoiceController@getInvoiceAttachements');

            Route::get('/services/by-invoice/{invoiceId}', 'InvoiceServiceController@getByInvoice');

            Route::get('/qbo/customers-active', 'ShipmentQBOBillController@getCustomers');

            Route::post('/qbo/bill/add', 'ShipmentQBOBillController@addBill');
            Route::post('/qbo/bill/edit', 'ShipmentQBOBillController@editBill');
            Route::post('/qbo/bill/delete', 'ShipmentQBOBillController@deleteBill');

            //Route::get('/qbo/bill/services', 'ShipmentQBOBillController@getItemServices');
            //Route::get('/qbo/bill/categories', 'ShipmentQBOBillController@getItemCategories');
            //Route::get('/qbo/bill/accounts-expense', 'ShipmentQBOBillController@getExpenseAccounts');
            Route::get('/qbo/bill/accounts-expense-search', 'ShipmentQBOBillController@searchExpenseAccounts');
            Route::get('/qbo/bill/customer-search', 'ShipmentQBOBillController@searchCustomers');
            Route::get('/qbo/bill/by-shipment/{shipmentId}', 'BillController@getByShipment');
            Route::get('/qbo/bill/by-id/{id}', 'ShipmentQBOBillController@getQBOBill');

            Route::get('/qbo/vendors-search', 'ShipmentQBOBillController@searchVendors');
            //Route::get('/qbo/vendors-active', 'ShipmentQBOBillController@getActiveVendors');
            //Route::get('qbo/vendors', 'ShipmentQBOController@getVendors');

            Route::post('/qbo/attachable/upload', 'ShipmentQBOController@uploadAttachmentToQBO');
            Route::get('/qbo/attachable/get-shipment-documents/{shipment_id}', 'ShipmentQBOController@getShipmentDocuments');
            Route::get('/qbo/attachable/get-shipment-commercial-documents/{shipment_id}', 'ShipmentQBOController@getShipmentCommercialDocuments');

            Route::get('/qbo/get-company-info', 'ShipmentQBOController@getQBOCompanyInfo');
            Route::get('/qbo/get-shipment-info/{shipment_id}', 'ShipmentQBOController@getShipmentInfo');
            Route::get('/qbo/get-company-info-v2', 'QBOController@getCompanyInfo');
            Route::get('/qbo/get-companies', 'QBOController@getQBOCompanies');

            Route::get('/qbo/tax/get-tax-rates', 'QBOController@getTaxRates');
            Route::get('/qbo/tax/get-tax-codes', 'QBOController@getTaxCodes');
            Route::get('/qbo/tax/get-taxrate-by-id', 'QBOController@getTaxRateById');

            Route::get('/qbo/exchangerate/get-by-currency-codes', 'QBOController@getExchangerateByCurrencyCode');
            Route::get('/qbo/customer/get-udated-customer-email/{customer_id}', 'QBOController@getUpdatedCustomerEmail');

            Route::get('/bill/by-shipment/{shipmentId}', 'BillController@getByShipment');
            Route::get('/bill/by-id/{id}', 'BillController@getBillById');

            Route::get('/invoice/by-id/{id}', 'InvoiceController@getInvoiceById');
            Route::get('/invoice/generate-invoice-num/{shipment_id}', 'InvoiceController@autoGenerateInvoiceNumber');

            Route::get('netchb/entry/{shipment_id}', 'NetCHB\EntryController@getEntryFromShipment');
            Route::get('netchb/entry/shipments/{shipment_id}/suppliers/{id}/{meta_id}', 'NetCHB\EntryController@getSupplier');
            Route::post('netchb/entry', 'NetCHB\EntryController@createEntry');
            Route::post('netchb/entry/documents', 'NetCHB\EntryController@uploadDocuments');

            Route::get('/customers', 'CustomerController@getCustomers');
            Route::get('/customers/{customer_id}', 'CustomerController@getCustomer');

            Route::get('/buyers/{customer_id}', 'BuyerController@getBuyer');
            Route::get('/customer-buyers/{customer_id}', 'BuyerController@getCustomerBuyer');

            Route::post('/customers-suppliers', 'AddCustomersSuppliersRelationship@setCustomerSuppliers');
            Route::get('/customers-suppliers', 'AddCustomersSuppliersRelationship@getCustomerSuppliersPage');
            Route::get('/suppliers/{supplier_id}', 'SupplierController@getSupplier');

            Route::post('/buyers/connect', 'BuyerController@connect');
            Route::post('/supplier/connect', 'SupplierController@connect');
            Route::post('/supplier/disconnect', 'SupplierController@disconnect');
            Route::post('/buyers/disconnect', 'BuyerController@disconnect');

            Route::get('/po/customers/{customer_id}/purchase-orders', 'PO_INSTANCE\PurchaseOrdersController@getPurchaseOrders');
            Route::get('/po/purchase-orders/{purchase_order_id}/products', 'PO_INSTANCE\PurchaseOrdersController@getPurchaseOrderProducts');
            Route::get('/po/shipments/{shipment_id}/purchase-orders', 'PO_INSTANCE\PurchaseOrdersController@getShipmentPurchaseOrders');

            Route::get('/po/customers/{customer_id}/sales-orders', 'PO_INSTANCE\PurchaseOrdersController@getSalesOrders');

            Route::get('/customer-orders/{id}/{order_type}', 'PO_INSTANCE\PurchaseOrdersController@getCustomerOrders');

            Route::delete('/shipment/{id}/orders', 'PO_INSTANCE\PurchaseOrdersController@removeOrdersFromShipment');

            // SHIPPING CHARGES TAB PROJECTED PROFIT
            Route::get('/projected-profit/by-shipment/{shipmentId}', 'ShipmentController@projected_profit');
            Route::get('/profit-monitor/get-shipments', 'ProfitMonitorController@getCalculatedShipment');

            //import names
            Route::get('/import-names/{customer_id}', 'ImportNameController@getImportNameByCustomerId');
            Route::get('/import-names/{customer_id}',  'ImportNameController@getImportNameByCustomerId');

            // Shipment Notes
            Route::get('/shipment-notes', [ShipmentNotesController::class, 'getNotes']);
            Route::post('/shipment-notes', [ShipmentNotesController::class, 'addNote']);
            Route::delete('/shipment-notes', [ShipmentNotesController::class, 'deleteNote']);
            Route::put('/shipment-notes/{noteId}', [ShipmentNotesController::class, 'updateNote']);

            Route::get('/our-automated-tracking', 'AutomatedTrackingController@getAllShipment');
            Route::post('/our-automated-tracking/update', 'AutomatedTrackingController@updateShipment');
            Route::post('/our-automated-tracking/update/{id}', 'AutomatedTrackingController@updateShipmentById');
        }
    );

   
      // country-state-city
      Route::get('/countries', 'CountryStateCityController@getCountries');
      Route::get('/countries/{country}/states', 'CountryStateCityController@getCountryStates');
      Route::get('/countries/{country}/states/{state}/cities', 'CountryStateCityController@getStateCities');


     Route::get('/bol/download/{shipment_id}/{supplier_id}', 'BolController@index');

    Route::get('/mbl/download/{shipment_id}', 'MblController@index');

      // country-state-city
      Route::get('/countries', 'CountryStateCityController@getCountries');
      Route::get('/countries/{country}/states', 'CountryStateCityController@getCountryStates');
      Route::get('/countries/{country}/states/{state}/cities', 'CountryStateCityController@getStateCities');
 


});

Route::prefix('webhook')->group(function () {
    Route::post("crux", "CruxWebhookController@handle")->middleware('crux');
    Route::post("terminal49", "Terminal49WebhookController@handle");
    //TEMP Route::post("qbowebhookentry", "QBOWebhookController@handle");
});

Route::get('/download/excel/{fileName}', function ($fileName) {
    ob_end_clean();
    return response()->download(storage_path('app/public/reports/excel/' . $fileName));
})->middleware('auth');
// Auth::routes();

Route::get('/passport', 'HomeController@index')->name('home');

Route::get('/test',function(){
    // $quickbooks = app('QuickBooks');

    // $q = $quickbooks->getDataService()->query("select * from Customer STARTPOSITION 1 MAXRESULTS 1");

    // $error = $quickbooks->getDataService()->getLastError();

    // if( $error ){
    //     dd(0);
    // }

    // dd($q);

    $quickbooks = app('QuickBooks');

    $q = $quickbooks->getDataService()->query("select * from Payment where CustomerRef = '8' ORDERBY Id DESC STARTPOSITION 1 MAXRESULTS 1");

    $error = $quickbooks->getDataService()->getLastError();

    if( $error ){
        dd($error);
    }

    dd(count($q) > 0 && isset($q[0]->MetaData->CreateTime) ? date_format(date_create($q[0]->MetaData->CreateTime),'F d, Y') : '');
});