<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/ap'], function () {
    // withholding tax
    Route::resource('/withholding-tax', 'Ap\WithholdingTaxController');
    Route::patch('/withholding-tax/{withholding_tax}/update_status', 'Ap\WithholdingTaxController@update_status');

    // branch
    Route::resource('/branch', 'Ap\BranchController');
    Route::patch('/branch/{branch}/update_status', 'Ap\BranchController@update_status');

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

    // recurring payment
    Route::resource('/recurring-payment-distribution', 'Ap\RecurringPaymentDistributionController');

    // monthly payment
    Route::get('/monthly-payment/batch', 'Ap\MonthlyPaymentController@index_batch');
    Route::patch('/monthly-payment/batch/{batch_id}/{status_filter}', 'Ap\MonthlyPaymentController@update_batch');
    Route::resource('/monthly-payment', 'Ap\MonthlyPaymentController');

    // check payment request
    Route::resource('/check-payment-request', 'Ap\CheckPaymentRequestController');

    // bank deposit
    Route::resource('/bank-deposit', 'Ap\BankDepositController');

    // utils
    Route::group(['prefix' => '/utils'], function () {
        Route::get('/get_bank_account/{bank_account}', 'Ap\CheckController@get_bank_account');
        Route::get('/get_banks', 'Ap\BankController@get_banks');

        Route::get('/get_bank_accounts/{bank}', 'Ap\BankAccountController@get_bank_accounts');
        Route::get('/get_acct_type', 'Ap\BankAccountController@get_acct_type');
        Route::get('/get_currency', 'Ap\BankAccountController@get_currency');

        Route::get('/get_supplier', 'Ap\RecurringPaymentController@get_supplier');
        Route::get('/get_frequency', 'Ap\RecurringPaymentController@get_frequency');

        Route::get('/get_checks/{bank_account}', 'Ap\MonthlyPaymentController@get_checks');
        Route::get('/get_voucher_checks/{bank_account}', 'Ap\MonthlyPaymentController@get_voucher_checks');
        Route::get('/get_document_type', 'Ap\MonthlyPaymentController@get_document_type');

        Route::get('/get_payment_terms', 'Ap\CheckPaymentRequestController@get_payment_terms');

        Route::get('/get_branches', 'Ap\BranchController@get_branches');
        Route::get('/get_withholding_taxes', 'Ap\WithholdingTaxController@get_withholding_taxes');
    });
});
