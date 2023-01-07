<?php 

require "config/init.php";


$task = new Task($db);
var_dump($task->add("Jon Doe") ? "OK" : "okkkk");die;