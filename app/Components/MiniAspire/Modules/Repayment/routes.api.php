<?php
/*
 * Author: Raksa Eng
 */

// Route::group(['middleware' => ['auth.api.once'], 'prefix' => 'v1'], function () {
Route::group(['prefix' => 'v1'], function () {
    $controller = "\App\Components\MiniAspire\Modules\Repayment\RepaymentController";
    Route::post('/repayments/get/{id}', $controller . '@apiGetRepayment')->name('api.repayments.get');
    Route::post('/repayments/pay/{id}', $controller . '@apiPay')->name('api.repayments.pay');
});
