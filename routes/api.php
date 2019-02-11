<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => '/financial'], function () {
    Route::resource('/account-category', 'Financial\AccountCategoryController');
    Route::patch('/account-category/{account_category}/update_status', 'Financial\AccountCategoryController@update_status');

    Route::resource('/chart-of-account', 'Financial\ChartOfAccountController');
    Route::patch('/chart-of-account/{chart_of_account}/update_status', 'Financial\ChartOfAccountController@update_status');
});

Route::group(['prefix' => '/ap'], function () {
    Route::resource('/bank', 'Ap\BankController');
    Route::patch('/bank/{bank}/update_status', 'Ap\BankController@update_status');

    Route::resource('/bank-account', 'Ap\BankAccountController');
    Route::patch('/bank-account/{bank_account}/update_status', 'Ap\BankAccountController@update_status');
    Route::patch('/bank-account/{bank_account}/update_beginning_bal', 'Ap\BankAccountController@update_beginning_bal');

    Route::resource('/check', 'Ap\CheckController');
    Route::get('/check/{sequence}/get_check_list', 'Ap\CheckController@get_check_list');
});

Route::group(['prefix' => '/requisition'], function () {
    Route::resource('/currency', 'Requisition\CurrencyController');
    Route::patch('/currency/{currency}/update_status', 'Requisition\CurrencyController@update_status');

    Route::resource('/supplier-classification', 'Requisition\SupplierClassificationController');
    Route::patch('/supplier-classification/{supplier_classification}/update_status', 'Requisition\SupplierClassificationController@update_status');

    Route::resource('/supplier', 'Requisition\SupplierController');
    Route::patch('/supplier/{supplier}/update_status', 'Requisition\SupplierController@update_status');

    Route::resource('/supplier-contact', 'Requisition\SupplierContactController');
});

Route::group(['prefix' => '/utils'], function () {
    Route::get('/get_acct_category', 'Financial\ChartOfAccountController@get_acct_category');
    Route::get('/get_posting_type', 'Financial\ChartOfAccountController@get_posting_type');
    Route::get('/get_typical_balance', 'Financial\ChartOfAccountController@get_typical_balance');

    Route::get('/get_bank/{bank}', 'Ap\BankAccountController@get_bank');
    Route::get('/get_bank_account/{bank_account}', 'Ap\CheckController@get_bank_account');
    Route::get('/get_acct_type', 'Ap\BankAccountController@get_acct_type');

    Route::get('/get_supplier_classification', 'Requisition\SupplierController@get_supplier_classification');
    Route::get('/get_currency', 'Requisition\SupplierController@get_currency');
});
