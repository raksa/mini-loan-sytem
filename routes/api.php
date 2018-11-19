<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/*
 * Make modular route for api
 * Author: Raksa Eng
 */

$component_path = app_path() . DIRECTORY_SEPARATOR . "Components";

if (\File::isDirectory($component_path)) {
    $list = \File::directories($component_path);
    foreach ($list as $module) {
        if (\File::isDirectory($module)) {
            if (\File::isFile($module . DIRECTORY_SEPARATOR . "routes.api.php")) {
                require_once $module . DIRECTORY_SEPARATOR . "routes.api.php";
            }
        }
    }
}
