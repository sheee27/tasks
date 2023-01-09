<?php
define("ROOT_PATH", __DIR__ . "/../");
require_once ROOT_PATH . "/config/config.php";
require_once ROOT_PATH . "/Controller/Api/BaseController.php";
require_once ROOT_PATH . "/config/JwtHandler.php";
require_once ROOT_PATH . "/middlewares/Auth.php";
require_once ROOT_PATH . "/config/Database.php";

spl_autoload_register(function ($class_name) {
   require_once  dirname(__FILE__,2) . '/modules/'.$class_name . '.php';
});
?>