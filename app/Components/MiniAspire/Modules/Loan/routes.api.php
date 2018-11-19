<?php
/*
 * Author: Raksa Eng
 */

Route::group(['middleware' => ['auth.api.once'], 'prefix' => 'v1'], function () {
    $controller = "\App\Components\MiniAspire\Modules\Loan\LoanController";
    Route::post('/loans/create', $controller . '@apiCreateLoan')->name('api.loans.create');
});
