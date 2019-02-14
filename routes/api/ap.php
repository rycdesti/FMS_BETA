<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/ap'], function () {
    // bank
    Route::resource('/bank', 'Ap\BankController');
    Route::patch('/bank/{bank}/update_status', 'Ap\BankController@update_status');

    // bank account
    Route::resource('/bank-account', 'Ap\BankAccountController');
    Route::patch('/bank-account/{bank_account}/update_status', 'Ap\BankAccountController@update_status');
    Route::patch('/bank-account/{bank_account}/update_beginning_bal', 'Ap\BankAccountController@update_beginning_bal');

    // check
    Route::resource('/check', 'Ap\CheckController');
    Route::get('/check/{sequence}/get_check_list', 'Ap\CheckController@get_check_list');

    // recurring payment
    Route::resource('/recurring-payment', 'Ap\RecurringPaymentController');
    Route::patch('/recurring-payment/{recurring_payment}/update_status', 'Ap\RecurringPaymentController@update_status');

    // utils
    Route::group(['prefix' => '/utils'], function () {
        Route::get('/get_bank_account/{bank_account}', 'Ap\CheckController@get_bank_account');

        Route::get('/get_bank/{bank}', 'Ap\BankAccountController@get_bank');
        Route::get('/get_acct_type', 'Ap\BankAccountController@get_acct_type');
        Route::get('/get_currency', 'Ap\BankAccountController@get_currency');

        Route::get('/get_supplier', 'Ap\RecurringPaymentController@get_supplier');
        Route::get('/get_frequency', 'Ap\RecurringPaymentController@get_frequency');
    });
});
