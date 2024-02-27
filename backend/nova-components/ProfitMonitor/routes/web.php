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




Route::get('/download', 'DownloadController@download');
Route::webhooks('shifl-entry-webhook');

Route::middleware('auth')->prefix('custom-api')->group(function () {
    Route::post('shipment/supplier-section/file/delete', 'API\FileController@deleteShipmentSupplierSectionFile');
    Route::get('{resource_name}/{resource_id}/download/{field}', 'API\FileController@download');
    Route::delete('{resource_name}/{resource_id}/field/{attribute}', 'API\FileController@delete');
    Route::get('crux-container-detail/{container_num}', 'API\CruxContainerDetailController@index');

    /**
     *  api support for Status tab.
     */
    Route::get('/shipments/status/terminal49/{mbl_num}', 'API\StatusTabController@index');
});

Route::prefix('api-services')->group(function () {
    Route::get('documentation', function () {
        return view('api.documentation.index');
    });
});

Route::group(['middleware' => 'auth'], function () {

    //return $request->user();

    Route::group(
        [
            'prefix' => 'custom',
        ],
        function () {
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

            Route::get('/services', 'ServiceController@getAll');
            Route::post('/services/where-in', 'ServiceController@whereIn');


            Route::get('/suppliers', 'SupplierController@getAll');
            Route::get('/supplier', 'SupplierController@findById');
            Route::post('/suppliers/where-in', 'SupplierController@whereIn');

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


            Route::post('/shipment-header-info/{id}', 'ShipmentController@updateHeaderInfo');

            Route::get('/process-departure-notice-database', 'ProcessingController@process');

            Route::get('/process-get-all-schedules', 'ProcessingController@getAll');

            Route::get('/process-single-shipment/{shipment_id}', 'ProcessingController@processSingleShipment');

            //get items by customer
            Route::get('/items-by-customer/{id}', 'ItemController@getByCustomer');

            Route::post('/qbo/invoice/add', 'ShipmentQBOController@addInvoice');
            Route::post('/qbo/invoice/edit', 'ShipmentQBOController@editInvoice');
            Route::post('/qbo/invoice/delete', 'ShipmentQBOController@deleteInvoice');

            Route::get('/qbo/services', 'ShipmentQBOController@getServices');
            Route::get('/qbo/customers', 'ShipmentQBOController@getCustomers');
            Route::get('/qbo/terms', 'ShipmentQBOController@getTerms');
            /* Route::get('/qbo/customers-search/{name}', 'ShipmentQBOController@searchCustomers'); */
            Route::get('/qbo/customers-search', 'ShipmentQBOController@searchCustomers');
            Route::get('/qbo/services-search', 'ShipmentQBOController@searchServices');

            Route::get('/invoices/by-shipment/{shipmentId}', 'InvoiceController@getByShipment');

            Route::get('/services/by-invoice/{invoiceId}', 'InvoiceServiceController@getByInvoice');

            Route::get('/qbo/customers-active', 'ShipmentQBOBillController@getCustomers');

            Route::post('/qbo/bill/add', 'ShipmentQBOBillController@addBill');
            Route::post('/qbo/bill/edit', 'ShipmentQBOBillController@editBill');
            Route::post('/qbo/bill/delete', 'ShipmentQBOBillController@deleteBill');

            Route::get('/qbo/bill/services', 'ShipmentQBOBillController@getItemServices');
            Route::get('/qbo/bill/categories', 'ShipmentQBOBillController@getItemCategories');
            Route::get('/qbo/bill/accounts-expense', 'ShipmentQBOBillController@getExpenseAccounts');
            Route::get('/qbo/bill/accounts-expense-search', 'ShipmentQBOBillController@searchExpenseAccounts');
            Route::get('/qbo/bill/customer-search', 'ShipmentQBOBillController@searchCustomers');
            Route::get('/qbo/bill/by-shipment/{shipmentId}', 'BillController@getByShipment');
            Route::get('/qbo/bill/by-id/{id}', 'ShipmentQBOBillController@getQBOBill');
            //Route::get('/qbo/bill/get-sync-token/{id}', 'ShipmentQBOBillController@getBillSyncToken');

            Route::get('/qbo/vendors-search', 'ShipmentQBOBillController@searchVendors');
            Route::get('/qbo/vendors-active', 'ShipmentQBOBillController@getActiveVendors');
            Route::get('qbo/vendors', 'ShipmentQBOController@getVendors');

            Route::get('/bill/by-shipment/{shipmentId}', 'BillController@getByShipment');
            Route::get('/bill/by-id/{id}', 'BillController@getBillById');

            Route::get('/invoice/by-id/{id}', 'InvoiceController@getInvoiceById');

            Route::get('netchb/entry/{shipment_id}', 'NetCHB\EntryController@getEntryFromShipment');
            Route::post('netchb/entry', 'NetCHB\EntryController@createEntry');

            Route::get('/profit-monitor/get-shipments', 'ProfitMonitorController@getCalculatedShipment');
        }
    );

    Route::get('/bol/download/{shipment_id}/{supplier_id}', 'BolController@index');

    Route::get('/mbl/download/{shipment_id}', 'MblController@index');
});

Route::prefix('webhook')->group(function () {
    Route::post("crux", "CruxWebhookController@handle")->middleware('crux');
    Route::post("terminal49", "Terminal49WebhookController@handle");
});

Route::get('/download/excel/{fileName}', function ($fileName) {
    return response()->download(storage_path('app/public/reports/excel/'.$fileName));
})->middleware('auth');
// Auth::routes();

Route::get('/passport', 'HomeController@index')->name('home');
