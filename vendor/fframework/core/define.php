<?php
    $root = realpath(getcwd());
    
    if(basename($root) == 'public') { $root = substr($root,0, -strlen(DIRECTORY_SEPARATOR.'public')); }

    define("DS", DIRECTORY_SEPARATOR);
    define("ROOT",$root.DS);
    define("STORAGE_PATH",ROOT."storage".DS);
    define("APP_PATH",ROOT . "app".DS);
    define("VENDOR_PATH",ROOT . "vendor". DS);
    define("PUBLIC_PATH",ROOT . "public" . DS);
    define("CONFIG_PATH",APP_PATH . "config". DS);
    define("MODEL_PATH",APP_PATH . "models" . DS);
    define("CORE_PATH", VENDOR_PATH .DS."fframework".DS."core". DS);
    define("FRAMEWORK_PATH", VENDOR_PATH .DS."fframework".DS);
    define("DB_PATH", FRAMEWORK_PATH . "database" . DS);
    define("HELPER_PATH", FRAMEWORK_PATH . "helpers" . DS);
    unset($root);
?>