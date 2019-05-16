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


Route::group(['prefix' => 'reports'], function() {
    Route::group(['prefix' => 'requisition'], function() {
        Route::get('supplier', 'Requisition\SupplierController@generatePDFReport');
        Route::get('supplier_contact/{id}', 'Requisition\SupplierContactController@generatePDFReport');
        Route::get('supplier_classification', 'Requisition\SupplierClassificationController@generatePDFReport');
        Route::get('currency', 'Requisition\CurrencyController@generatePDFReport');
    });


    Route::group(['prefix' => 'financial'], function() {
        Route::get('chart-of-account', 'Financial\ChartOfAccountController@generatePDFReport');
        Route::get('account-category', 'Financial\AccountCategoryController@generatePDFReport');
    });

    Route::group(['prefix' => 'ap'], function() {
        Route::get('bank', 'Ap\BankController@generatePDFReport');
        Route::get('bank-account/{id}', 'Ap\BankAccountController@generatePDFReport');
        Route::get('check/{id}', 'Ap\CheckController@generatePDFReport');
        Route::get('recurring-payment', 'Ap\RecurringPaymentController@generatePDFReport');
        Route::get('monthly-payment', 'Ap\MonthlyPaymentController@generatePDFReport');
    });
});
