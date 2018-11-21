<?php
/*
 * Author: Raksa Eng
 */

Route::group(['middleware' => ['auth.api.once'], 'prefix' => 'v1'], function () {
    $controller = "\App\Components\MiniAspire\Modules\User\UserController";
    Route::post('/users/create', $controller . '@apiCreateUser')->name('api.users.create');
    Route::post('/users/get/{id?}', $controller . '@apiGetUser')->name('api.users.get');
});
