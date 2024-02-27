<?php

use App\Http\Middleware\ServerToServerTokenIsValid;
use Illuminate\Support\Facades\Route;
use Vishalmarakana\ShipmentNotes\Http\Controllers\ShipmentNotesController;
use App\Http\Controllers\API\GetImportNamesController;
use App\Http\Controllers\API\Shipments\ShipmentController AS APIShipmentController;
use \App\Http\Controllers\API\GroupPermissions\GroupController;
use \App\Http\Controllers\API\GroupPermissions\GroupPermissionsController;
use \App\Http\Controllers\API\GroupPermissions\ModulesController;
use \App\Http\Controllers\API\GroupPermissions\GroupTemplatesController;
use \App\Http\Controllers\API\GroupPermissions\GroupPermissionsTemplatesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route::post('login', 'API\UserController@login');

// Route::post('register', 'API\UserController@register');

Route::post('register', 'API\UserController@signup');
Route::post('register-invite-user', 'API\UserController@signUpForInvitedUser');
Route::post('/customer/invite-user-confirm', 'API\CustomerAdminController@inviteExistsUserConfirm');
Route::get('/users/user-register-completed/{email}', 'API\UserController@userRegisterCompleted');

Route::group(['middleware' => 'auth:api'], function () {

    /* get connected shipper */
    Route::get('/users/connected-shipper', 'API\UserController@getConnectedShipper');

    /* get connected consignee */
    Route::get('/users/connected-consignee', 'API\UserController@getConnectedConsignee');

    Route::get('/shipment-po-details/{po_id}', 'API\Shipments\ShipmentController@getPoShipments');
    Route::get('/getaccesstoken', 'API\Custom\UserController@validateTokenAndReturnUser');
    Route::post('details', 'API\UserController@details');
    Route::get('/users/{email}/exists', 'API\UserController@checkUserExistsByEmail');

    /* suppliers api */
    Route::get('suppliers', 'API\SupplierController@index');
    Route::post('suppliers', 'API\SupplierController@create');
    Route::put('suppliers/{id}', 'API\SupplierController@update');
    Route::delete('suppliers/{id}', 'API\SupplierController@destroy');
    Route::get('shipment-suppliers', 'API\SupplierController@getByShipmentId');

    /* inviting vendor */
    Route::post('send/invite/vendor', 'API\SupplierController@invite');

    /* inviting buyer */
    Route::post('send/invite/buyer', 'API\BuyerController@invite');

    /* buyers api */
    Route::get('buyers', 'API\BuyerController@index');
    Route::post('buyers', 'API\BuyerController@create');
    Route::put('buyers/{id}', 'API\BuyerController@update');
    Route::delete('buyers/{id}', 'API\BuyerController@destroy');

    // ach statements
    Route::get('statements', 'API\AchStatementController@show');

    //Route::post('logout', 'API\UserController@logout');

    Route::get('schedule-options', 'API\Shipments\ShipmentController@getScheduleOptions');
    Route::get('select-schedule', 'API\Shipments\ShipmentController@selectScheduleApp');
    Route::get('/request-new-schedule/{shipment_id}', 'API\Shipments\ShipmentController@requestNewSchedule');
    //Route::get('shipments', 'API\ShipmentController@indexTest');
    Route::get('events/{mbl_num}', 'API\Shipments\ShipmentController@indexTs');

    Route::get('carriers', 'API\CarrierController@index');
    Route::get('carrier/{id}', 'API\CarrierController@show');

    //Route::get('shipmentss', 'API\ShipmentController@index');

    // For search through Shipment Lists
    Route::post('shipments/search', 'API\SearchController@shipments');

    // for Shipment Details
    //Route::get('shipmentt/{id}', 'API\ShipmentController@showTest');
    //Route::get('shipment/{id}', 'API\ShipmentController@show');

    Route::get('shipment/{id}', 'API\Shipments\ShipmentController@details');
    //Route::get('shipment/{id}', 'API\ShipmentController@show');
    Route::post('shipment/check', 'API\Shipments\ShipmentController@checkShipmentStatus');
    Route::post('shipment/get-reference-number', [APIShipmentController::class, 'getReferenceNumber']);
    Route::get('shipment-customer-documents', 'DocumentController@fetchDocuments');

    Route::group(['prefix' => 'quickbooks'],
        function () {
            Route::get('invoices/{shipmentId?}', 'API\InvoiceController@index');
            Route::get('invoice/{id}', 'API\InvoiceController@show');
            Route::get('download-invoice', 'API\InvoiceController@download');
            Route::get('invoice-total-due', 'API\InvoiceController@totalDue');
        }
    );

    Route::get('terminal-regions', 'API\TerminalRegionController@index');
    Route::get('terminals', 'API\TerminalController@index');
    Route::get('terminal-real/{id}', 'API\TerminalController@show');


    //v4
    Route::group(['prefix' => 'v4'], function() {

        //experimental mode
        //in transit
        Route::get('shipments', 'API\Shipments\v4\ShipmentController@indexTransit');

        //pending shipments
        Route::get('pending-shipments', 'API\Shipments\v4\ShipmentController@indexPending');
        //Route::get('completed-shipments', 'API\Shipments\ShipmentController@indexCompleted');

        //pending quote
        Route::get('pending-quote-shipments', 'API\Shipments\v4\ShipmentController@indexPendingQuote');

    });

    //v2
    Route::group(['prefix' => 'v2'],
        function () {

            /*  fetch buyer supplier */
            Route::get('buyer-seller-details', 'API\UserController@fetchBuyerSupplier');


            /* suppliers ***** SPECIAL CASE ***** */
            Route::get('suppliers', 'API\SupplierController@indexV2');

            /* buyers ***** SPECIAL CASE ***** */
            Route::get('buyers', 'API\BuyerController@indexV2');

            // contacts = vendor + buyers
            Route::get('contacts', 'API\BuyerController@contactsDropdown');

            //snooze shipment
            Route::post('snooze-shipment', 'API\Shipments\ShipmentController@snoozeShipment');

            //unsnooze shipment
            Route::post('unsnooze-shipment', 'API\Shipments\ShipmentController@unSnoozeShipment');

            //create a booking
            Route::post('shipment/create-booking', 'API\Shipments\ShipmentController@createBookingShipment');
            Route::post('shipment/update-booking', 'API\Shipments\ShipmentController@updateBookingShipment');

            // track shipment without mbl
            Route::post('shipment/create-without-mbl', 'API\Shipments\ShipmentController@createShipmentWithoutMbl');

            //create a shipment
            Route::post('shipment/create', 'API\Shipments\ShipmentController@createShipment');

            //update a shipment
            Route::post('shipment/edit', 'API\Shipments\ShipmentController@editShipment');

            //mark book external
            Route::post('shipment/mark-book-external', 'API\Shipments\ShipmentController@markBookedExternal');

            //insert document to a shipment
            Route::post('shipment/document', 'API\DocumentController@insertDocument');
            Route::get('shipment/document/{id}', 'API\DocumentController@show');
            Route::delete('shipment/document/{id}', 'API\DocumentController@destroy');
            Route::post('shipment/document/{id}', 'API\DocumentController@update');
            Route::get('shipment/documents', 'API\DocumentController@fetchDocuments');
            Route::post('shipment/upload-multiple-documents', 'API\DocumentController@insertMultipleDocuments');
            Route::post('shipment/submit-multiple-documents', 'API\DocumentController@submitShipmentDocuments');
            Route::post('shipment/delete-multiple-documents', 'API\DocumentController@deleteDocuments');
            Route::post('shipment/update-name-type', 'API\DocumentController@updateNameType');

            //save customer preference
            Route::post('update-customer-preference', 'API\UserController@updateCustomerPreference');

            //save notification token
            Route::put('update-notification-token', 'API\UserController@updateNotificationToken');

            //in transit apptest (TEMPORARY)
            Route::get('shipments-completed-merge', 'API\Shipments\ShipmentController@indexTransitCompleted');


            //draft shipments
            Route::get('draft-shipments', 'API\Shipments\ShipmentController@indexDraft');

            //in transit
            Route::get('shipments', 'API\Shipments\ShipmentController@indexTransitTest');

            //in transit test
            Route::get('shipments-testing-beta', 'API\Shipments\ShipmentController@indexTransitTestBeta');


            //in transit testing mode
            //Route::get('shipments-testing', 'API\Shipments\ShipmentController@indexTransitTest');

            //all shipments with shipment id
            Route::get('shipments-by-ids','API\Shipments\ShipmentController@indexShipmentsByIds');

            //expired shipments
            Route::get('expired-shipments', 'API\Shipments\ShipmentController@indexExpired');
            //Route::get('regular-shipments', 'API\ShipmentController@indexRegular');

            //active pending shipments
            Route::get('pending-shipments', 'API\Shipments\ShipmentController@indexPending');
            Route::get('completed-shipments-testing', 'API\Shipments\ShipmentController@indexCompletedTest');
            Route::get('completed-shipments', 'API\Shipments\ShipmentController@indexCompletedTest');
            Route::get('completed-shipments-beta', 'API\Shipments\ShipmentController@indexCompletedTestBeta');

            //pending quote shipments
            //Route::get('pending-quote-shipments', 'API\ShipmentController@indexTransit');
            Route::get('pending-quote-shipments', 'API\Shipments\ShipmentController@indexPendingQuote');
            //snooze shipments
            Route::get('snooze-shipments', 'API\Shipments\ShipmentController@indexSnooze');

            //search shipment
            Route::get('shipments/search', 'API\Shipments\ShipmentController@search');

            //shipment details
            Route::get('shipment/{id}', 'API\Shipments\ShipmentController@details');

            //shipment find po number if it is conflict with shipment #
            Route::get('shipment-po', 'API\Shipments\ShipmentController@findByReferenceNumber');

            Route::get('import-names', 'API\ImportNameController@getAllImportName');
            Route::get('import-names/{customer_id}', 'API\ImportNameController@getImportNames');
            Route::get('import-name/{id}', 'API\ImportNameController@getImportNameById');
            Route::put('import-name/update/{id}', 'API\ImportNameController@update');
            Route::post('import-name/new', 'API\ImportNameController@create');
            Route::delete('import-name/{id}', 'API\ImportNameController@delete');

        }
    );

    //payment methods routes
    Route::group(['prefix' => 'payment-method'],
        function () {
            Route::group(['prefix' => 'ach'],
                function () {
                    Route::get('{default_customer_id}', '\App\Http\Controllers\API\PaymentMethod\PaymentMethodController@getACHPaymentMethod');
                    Route::post('add', '\App\Http\Controllers\API\PaymentMethod\PaymentMethodController@ACHSave');
                    Route::put('update', '\App\Http\Controllers\API\PaymentMethod\PaymentMethodController@ACHUpdate');
                    Route::delete('delete', '\App\Http\Controllers\API\PaymentMethod\PaymentMethodController@ACHDelete');
                }
            );
            Route::group(['prefix' => 'cc'],
                function () {
                    Route::get('{default_customer_id}', 'API\Poynt\CardController@index');
                    Route::post('add', '\App\Http\Controllers\API\PaymentMethod\PaymentMethodController@addPaymentMethod');
                    Route::put('update', 'API\Poynt\CardController@edit');
                    Route::delete('delete', 'API\Poynt\CardController@destroy');
                }
            );
            Route::get('all/{default_customer_id}', '\App\Http\Controllers\API\PaymentMethod\PaymentMethodController@getAllPaymentMethod');
        }
    );

    //Make payment routes
    Route::group(['prefix' => 'make-payment'],
        function () {
            Route::post('charge', '\App\Http\Controllers\API\Payment\MakePaymentController@chargePayment');
        }
    );

    //payment history routes
    Route::group(['prefix' => 'payment-history'], function () {
        Route::get('{default_customer_id}/download-receipt/{payment_id}', '\App\Http\Controllers\API\Payment\PaymentHistoryController@downloadReceipt');
        Route::get('{default_customer_id}/all', '\App\Http\Controllers\API\Payment\PaymentHistoryController@getPaymentHistory');
    });

    // For customer\company details own by admin User
    Route::get('customers', 'API\CustomerController@index');
    Route::get('customers/check_company_key/{company_key}', 'API\CustomerController@checkCompanyKey');
    Route::get('customers/company_keys', 'API\CustomerController@getCompanyKeys');
    Route::get('customer/{company_key}/detail', 'API\CustomerController@getByCompanyKey');
    Route::post('/customer/inviteUser', 'API\CustomerAdminController@sendAddUserInvitation');
    Route::post('/customer/reSendInviteUser', 'API\CustomerAdminController@reSendAddUserInvitation');

    // For search through Customer List
    Route::post('customers/search', 'API\SearchController@customers');

    // For specific customer\company details own by admin User
    Route::get('customer/{id}', 'API\CustomerController@show');

    Route::put("customers/profile/{id}", "API\CustomerController@updateProfile");

    // For global search over Shipment list + Customer List
    Route::post('search', 'API\SearchController@index');

    // Containers list and details api

    Route::get('containers', 'API\ContainerController@index');
    Route::get('container/{id}', 'API\ContainerController@show');

    // CarrierTypes list and details api

    Route::get('carrier-types', 'API\CarrierTypeController@index');
    Route::get('carrier-type/{id}', 'API\CarrierTypeController@show');

    // CarrierTypes list and details api

    Route::get('purchase-orders', 'API\PurchaseOrderController@index');
    Route::get('purchase-order/{id}', 'Api\PurchaseOrderController@show');

    // Carriers list and details api

    Route::get('carriers', 'API\CarrierController@index');
    Route::get('carrier/{id}', 'API\CarrierController@show');

    // Items list and details api

    Route::get('items', 'API\ItemController@index');
    Route::get('item/{id}', 'API\ItemController@show');

    // incoterms list and details api

    Route::get('incoterms', 'API\IncotermController@index');
    Route::get('incoterm/{id}', 'API\IncotermController@show');

    // ContainerSizes list and details api
    Route::get('container-sizes', 'API\ContainerSizeController@index');
    Route::get('container-size/{id}', 'API\ContainerSizeController@show');


    Route::post('logout', 'API\AuthController@logout')->name('logout');

    Route::get('terminal/{id}', 'API\TerminalRegionController@show');



    // api for milestones
    // api for milestones
    Route::get("eta_logs/{mbl_num}", 'API\EtaLogsController@index');

    Route::get("milestones/{mbl_num}", "API\ContainerMilestonesController@index");
    Route::get("terminal49/shipment/{mbl_num}", "API\Terminal49ShipmentController@index");

    Route::get("custom/customers", "API\Custom\CustomerController@index");
    Route::get("custom/customers/{id}", "API\Custom\CustomerController@show");
    Route::get("custom/customers/{id}/suppliers", "API\Custom\CustomerController@getCustomerSuppliers");
    Route::get("custom/customers/{id}/buyers", "API\Custom\CustomerController@getCustomerBuyers");

    Route::get("custom/suppliers/{id}", "API\Custom\SupplierController@show");
    Route::get("custom/connected-supplier/{id}", "API\Custom\SupplierController@getConnectedSupplier");
    Route::get("custom/buyers/{id}", "API\Custom\BuyerController@show");
    Route::get("custom/connected-buyer/{id}", "API\Custom\BuyerController@getConnectedBuyer");
    Route::post("custom/details", "API\Custom\UserController@details");
    Route::get("custom/customer-admin/suppliers", "API\Custom\CustomerAdminController@index");
    Route::get("custom/user-details/{creator_id}/{editor_id?}", "API\Custom\UserController@userDetails");

    // Tracking Shipment APIs
    Route::post("tracking-shipments/new", "API\TrackingShipmentController@create");
    Route::post("tracking-shipments/{shipment_id}/delete", "API\TrackingShipmentController@delete");

    // Customer Shipment Report
    // Route::get("customer-shipment-report/{id}", "API\ShipmentReportController@getShipmentReport");

    Route::get("custom/users/{id}", "API\Custom\CustomerController@getUserName");

    // get customer admins
    Route::get("customer-admins/{customer_id}", "API\CustomerAdminController@getCustomerAdmin");
    Route::post("customer-admins-delete/{id}", "API\CustomerAdminController@deleteCustomerAdmin");

    //email report schedule
    Route::get("email-report-schedule/{user_id}", "API\EmailReportScheduleController@getEmailReportSchedule");
    Route::post("email-report-schedule/delete/{id}", "API\EmailReportScheduleController@deleteEmailReportSchedule");
    Route::post("email-report-schedule/update", "API\EmailReportScheduleController@update");
    Route::post("email-report-schedule/new", "API\EmailReportScheduleController@create");
    Route::get("download-report/{id}", "API\EmailReportScheduleController@download");
    Route::get("email-report-schedule-default-values", "API\EmailReportScheduleController@getDropdownValue");

    //email report schedule version 2
    Route::get("email-report-schedule-v2/{user_id}", "API\EmailReportScheduleController@getEmailReportSchedule_v2");
    Route::get("download-report-v2/{user_id}/{id}", "API\EmailReportScheduleController@download_v2");

    //Shipment Customize Report
    Route::get("shipment-fields", "API\ShipmentCustomizeReport@getReportFields");
    Route::post("shipment-report/update/{email_report_id}", "API\ShipmentCustomizeReport@updateStatus");
    Route::get("shipment-report/{user_id}", "API\ShipmentCustomizeReport@show");
    Route::post("shipment-report/delete/{id}", "API\ShipmentCustomizeReport@deleteEmailReportSchedule");
    Route::post("shipment-report/send-report", "API\ShipmentCustomizeReport@sendReport");
    Route::get("download/email-report/{id}", "API\ShipmentCustomizeReport@download");
    Route::post("shipment-report/new", "API\ShipmentCustomizeReport@create");
    Route::post("shipment-report/edit", "API\ShipmentCustomizeReport@edit");
    Route::get("shipment-report/user/{user_id}/type/{type}", "API\ShipmentCustomizeReport@getShipmentReportData");

    Route::get('shipment/automated-tracking/{status}', "API\ShipmentCustomizeReport@getShipmentAutomatedTracking");

    //version 2 of shipment report
    Route::get("v2/shipment-report/user/{user_id}/type/{type}", "API\ShipmentCustomizeReport@getShipmentReport");
    Route::get("shipment-trucking/{mbl}", "API\ShipmentCustomizeReport@getShipmentTrucking");
    Route::get("shipment-trucking/customer/{id}", "API\ShipmentCustomizeReport@getTruckingCustomer");

    // Shipment Notes
    Route::get('/shipment-notes', [ShipmentNotesController::class, 'getNotes']);
    Route::post('/shipment-notes', [ShipmentNotesController::class, 'addNote']);
    Route::delete('/shipment-notes', [ShipmentNotesController::class, 'deleteNote']);
    Route::put('/shipment-notes/{noteId}', [ShipmentNotesController::class, 'updateNote']);

    //Import names route
    Route::get('/import-names', [GetImportNamesController::class, 'index']);
    Route::get('/import-name/{id}', [GetImportNamesController::class, 'getImportName']);

    //Groups and Permissions
    Route::get('/groups/{company_id}/company', [GroupController::class, 'getGroupsByCompany']);
    Route::get('/groups/{group_id}', [GroupController::class, 'getGroup']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::post('/groups/{id}/update', [GroupController::class, 'update']);
    Route::post('/groups/{id}/delete', [GroupController::class, 'delete']);
    Route::get('/groups/{id}/users', [GroupController::class, 'getGroupUsers']);
    Route::post('/groups/{id}/remove-users', [GroupController::class, 'removeGroupUsers']);
    Route::post('/groups/{id}/add-users', [GroupController::class, 'addUsersInGroup']);
    Route::post('/groups/{id}/delete-move', [GroupController::class, 'deleteGroupAndMove']);

    Route::post('/group-permissions', [GroupPermissionsController::class, 'store']);
    Route::put('/group-permissions', [GroupPermissionsController::class, 'update']);
    Route::get('/group-permissions/{group_id}', [GroupPermissionsController::class, 'getGroupPermissions']);

    Route::get('/modules', [ModulesController::class, 'getModules']);
    Route::get('/modules/constant', [ModulesController::class, 'getModuleConstant']);
    Route::get('/group-templates', [GroupTemplatesController::class, 'getGroupTemplate']);
    Route::get('/group-permissions-templates/{group_template_id}', [GroupPermissionsTemplatesController::class, 'getGroupPermissionsTemplate']);
});

Route::middleware('guest')->group(function () {
    Route::post('login', 'API\AuthController@login')->name('login');
    Route::post('refresh-token', 'API\AuthController@refreshToken')->name('refreshToken');
    Route::post('forgot-password', 'API\UserController@forgotPassword');
    Route::get('check-forgot-password-code', 'API\UserController@checkForgotPasswordCode');
    Route::post('change-password', 'API\UserController@changePassword');
    Route::post('join-waiting-list', 'API\WaitingListController@join');
});

//Route::get('/profit-monitor/getShipments', 'ProfitMonitorController@getCalculatedShipment');
// Route::get('/get-shipments', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getCalculatedShipment');
// Route::get('/get-shifl-offices', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getShiflOffices');

//
//Route::get('/get-shipments-v2', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getCalculatedShipmentV2');
//Route::get('/get-qbo-companies', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getQBOCompanies');
//Route::get('/get-sales-representatives', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getSalesRepresentatives');
//Route::get('/get-profit-by-request', \Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController::class.'@getTotalProfitByRequest');

//
Route::get('/get-shipments-v2', '\Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController@getCalculatedShipmentV2');
Route::get('/get-qbo-companies', '\Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController@getQBOCompanies');
Route::get('/get-sales-representatives', '\Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController@getSalesRepresentatives');
Route::get('/get-profit-by-request', '\Ezea\ProfitMonitor\Http\Controllers\ProfitMonitorController@getTotalProfitByRequest');

Route::middleware(['client', 'client.user'])->prefix('client')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('shipment-by-po/{po}', 'API\CLIENT\v1\ShipmentController@shipmentByPo');
        Route::get('shipment/status/{id}', 'API\CLIENT\v1\ShipmentController@shipmentStatus');
        Route::get('shipment/documents/download', 'API\CLIENT\v1\ShipmentController@download');
    });

    Route::get('shipment/do/download/{id}', 'API\CLIENT\FileDownloadController@downloadDO');
});

Route::middleware([ServerToServerTokenIsValid::class])->group(function () {
    Route::get('/main/shipments/{shipment_id}', 'PO_INSTANCE\ShipmentController@getShipment');
});

////// SHIFL INDEX //////
Route::get('/sell-buy-rates-index/data/get/graph/terminal', '\Juliverdevshifl\ContainerBuyRates\Http\Controllers\StatsController@graph');
Route::get('/sell-buy-rates-index/data/get/indexes/terminal', '\Juliverdevshifl\ContainerBuyRates\Http\Controllers\StatsController@indexes');
Route::get('/sell-buy-rates-index/data/get/container/{id}', '\Juliverdevshifl\ContainerBuyRates\Http\Controllers\StatsController@containers');
Route::get('/sell-buy-rates-index/data/get/indexes/{id}', '\Juliverdevshifl\ContainerBuyRates\Http\Controllers\StatsController@indexes');
