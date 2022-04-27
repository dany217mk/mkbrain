<?php
    spl_autoload_register(function ($class){
        $dirs = ['components', 'models', 'controllers'];
        $filename = strtolower($class) . '.php';
        foreach ($dirs as $dir){
            $fullName = $dir . "/" . $filename;
            if (file_exists($fullName)){
                require_once($fullName);
                break;
            }
        }
    });
