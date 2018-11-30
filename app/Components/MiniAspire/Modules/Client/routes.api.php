<?php
/*
 * Author: Raksa Eng
 */

Route::group(['middleware' => ['auth.api.once'], 'prefix' => 'v1'], function () {
    $controller = "\App\Components\MiniAspire\Modules\Client\ClientController";
    Route::post('/clients/create', $controller . '@apiCreateClient')->name('api.clients.create');
    Route::post('/clients/get/{id?}', $controller . '@apiGetClient')->name('api.clients.get');
});
