<?php

//подключаем автолоадер
require dirname(__FILE__) . '/vendor/autoload.php';

//стартуем сессию
session_start();

$route = $_GET['route'] ?? '';

//стартуем IndexController
$index_controller = new Controllers\IndexController($route);
$index_controller->mainAction();