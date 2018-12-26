<?php
    /**
     * Get all routes from each modules
     */
    $component_path = app_path() . DIRECTORY_SEPARATOR . "Components";
    $modules = $component_path . DIRECTORY_SEPARATOR . "CoreComponent/Modules";
    if (\File::isDirectory($modules)){
        $list = \File::directories($modules);
        foreach($list as $module){
            if (\File::isDirectory($module)){
                if(\File::isFile($module. DIRECTORY_SEPARATOR . "routes.php")){
                    require_once($module. DIRECTORY_SEPARATOR. "routes.php");
                }
            }
        }
    }
