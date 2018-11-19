<?php
/*
 * Get all routes from each modules for api
 * Author: Raksa Eng
 */

$component_path = app_path() . DIRECTORY_SEPARATOR . "Components";
$modules = $component_path . DIRECTORY_SEPARATOR . "MiniAspire/Modules";
if (\File::isDirectory($modules)) {
    $list = \File::directories($modules);
    foreach ($list as $module) {
        if (\File::isDirectory($module)) {
            if (\File::isFile($module . DIRECTORY_SEPARATOR . "routes.api.php")) {
                require_once $module . DIRECTORY_SEPARATOR . "routes.api.php";
            }
        }
    }
}
