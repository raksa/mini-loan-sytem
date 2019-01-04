<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/*
 * Make modular route
 * Author: Raksa Eng
 */
$component_path = app_path() . DIRECTORY_SEPARATOR . "Components";

if (\File::isDirectory($component_path)) {
    $list = \File::directories($component_path);
    foreach ($list as $module) {
        if (\File::isDirectory($module)) {
            if (\File::isFile($module . DIRECTORY_SEPARATOR . "routes.php")) {
                require_once $module . DIRECTORY_SEPARATOR . "routes.php";
            }
        }
    }
}

Auth::routes();

Route::get('/', 'HomeController@welcome')->name('welcome');
Route::get('/home', 'HomeController@index')->name('home');
