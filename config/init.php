<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);



define("PATH_MODULE", __DIR__ . DIRECTORY_SEPARATOR);
define("PATH_BASE", dirname(PATH_MODULE) . DIRECTORY_SEPARATOR);

session_start();

require PATH_MODULE . "database.php";
$db = new Database();

spl_autoload_register(function ($class_name) {
   include dirname(__FILE__,2) . '/modules/'.$class_name . '.php';
});
