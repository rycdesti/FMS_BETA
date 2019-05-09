<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/financial'], function () {
    // account category
    Route::resource('/account-category', 'Financial\AccountCategoryController');
    Route::patch('/account-category/{account_category}/update_status', 'Financial\AccountCategoryController@update_status');

    // chart of account
    Route::resource('/chart-of-account', 'Financial\ChartOfAccountController');
    Route::patch('/chart-of-account/{chart_of_account}/update_status', 'Financial\ChartOfAccountController@update_status');

    // utils
    Route::group(['prefix' => '/utils'], function () {
        Route::get('/get_chart_account', 'Financial\ChartOfAccountController@get_chart_account');
        Route::get('/get_acct_category', 'Financial\ChartOfAccountController@get_acct_category');
        Route::get('/get_posting_type', 'Financial\ChartOfAccountController@get_posting_type');
        Route::get('/get_typical_balance', 'Financial\ChartOfAccountController@get_typical_balance');
    });
});