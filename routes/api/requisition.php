<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/requisition'], function () {
    // currency
    Route::resource('/currency', 'Requisition\CurrencyController');
    Route::patch('/currency/{currency}/update_status', 'Requisition\CurrencyController@update_status');

    // supplier classification
    Route::resource('/supplier-classification', 'Requisition\SupplierClassificationController');
    Route::patch('/supplier-classification/{supplier_classification}/update_status', 'Requisition\SupplierClassificationController@update_status');

    // supplier
    Route::resource('/supplier', 'Requisition\SupplierController');
    Route::patch('/supplier/{supplier}/update_status', 'Requisition\SupplierController@update_status');

    // supplier contact
    Route::resource('/supplier-contact', 'Requisition\SupplierContactController');

    // utils
    Route::group(['prefix' => '/utils'], function () {
        Route::get('/get_supplier_classification', 'Requisition\SupplierController@get_supplier_classification');
        Route::get('/get_currency', 'Requisition\SupplierController@get_currency');
    });
});
