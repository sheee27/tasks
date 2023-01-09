<?php
require __DIR__ . "/config/bootstrap.php";
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
if ((isset($uri[3]) && $uri[3] != 'user') || !isset($uri[3])) {
	var_dump($uri);
    header("HTTP/1.1 404 Not Found");
    exit();
}
require ROOT_PATH . "/Controller/Api/UserController.php";

$userObj = new UserController();
$strMethodName = $uri[4] . 'Method';
$userObj->{$strMethodName}();

?>