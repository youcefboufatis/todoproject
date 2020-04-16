<?php

$path = __DIR__;
$website = 'http://localhost/todoproject/';


$classes = $path . '/classes/';
$includes = $path . '/includes/';
$templates = $includes . '/templates/';
include_once $classes . "DBcontroller.php";
include_once $classes . "functions.php";

$db = new functions();
$db->connect();



 ?>
