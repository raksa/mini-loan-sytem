<?php
/*
 * Author: Raksa Eng
 */

Route::group(['middleware' => ['auth.api.once'], 'prefix' => 'v1'], function () {
    $controller = "\App\Components\CoreComponent\Modules\Loan\LoanController";
    Route::post('/loans/create', $controller . '@apiCreateLoan')->name('api.loans.create');
    Route::post('/loans/get/{id?}', $controller . '@apiGetLoan')->name('api.loans.get');
    Route::post('/loans/get_freq_type', $controller . '@apiGetFreqType')->name('api.loans.get_freq_type');
});
