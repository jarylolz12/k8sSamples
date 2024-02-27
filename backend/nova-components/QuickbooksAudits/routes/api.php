<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

// Route::get('/endpoint', function (Request $request) {
//     //
// });
//Route::get('invoice/run-data-check',\Shifl\QuickbooksAudits\Http\Controllers\QBOChangeDataCaptureController::class.'@runCDC');
Route::get('invoice/search-mismatch',\Shifl\QuickbooksAudits\Http\Controllers\SearchMismatchEntitiesController::class.'@searchMismatchEntities');
Route::get('invoice/verify-in-qbo',\Shifl\QuickbooksAudits\Http\Controllers\VerifyInvoiceController::class.'@verifyInvoiceInQBO');
Route::get('invoice/verify-in-shifl',\Shifl\QuickbooksAudits\Http\Controllers\VerifyInvoiceController::class.'@verifyInvoiceInShifl');
Route::post('invoice/delete-invoice',\Shifl\QuickbooksAudits\Http\Controllers\VerifyInvoiceController::class.'@deleteInvoice');
//Route::post('qbo-audits/invoice/save-to-archive',\Shifl\QuickbooksAudits\Http\Controllers\VerifyInvoiceController::class.'@saveToArchive');

Route::get('get-qbo-company-info',\Shifl\QuickbooksAudits\Http\Controllers\SearchMismatchEntitiesController::class.'@getQBOCompanyInfo');

Route::get('bill/search-mismatch',\Shifl\QuickbooksAudits\Http\Controllers\SearchMismatchEntitiesController::class.'@searchMismatchEntities');
Route::get('bill/verify-in-qbo',\Shifl\QuickbooksAudits\Http\Controllers\VerifyBillController::class.'@verifyBillInQBO');
Route::get('bill/verify-in-shifl',\Shifl\QuickbooksAudits\Http\Controllers\VerifyBillController::class.'@verifyBillInShifl');
Route::post('bill/delete-bill',\Shifl\QuickbooksAudits\Http\Controllers\VerifyBillController::class.'@deleteBill');
