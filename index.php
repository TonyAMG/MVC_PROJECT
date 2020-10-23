<?php

//подкллючаем автолоадер
require dirname(__FILE__) . '/vendor/autoload.php';

//подключаем конфиг
require dirname(__FILE__) . '/config/config.php';

//стартуем сессию
session_start();

$route = $_GET['route'] ?? '';
$routes = require __DIR__ . '/src/routes.php';

$isRouteFound = false;
foreach ($routes as $pattern => $controllerAndAction) {
    preg_match($pattern, $route, $matches);
    if (!empty($matches)) {
        $isRouteFound = true;
        break;
    }
}

//ошибка 404, если запрашиваемый путь не найден
if (!$isRouteFound) {
    $main = new \Controllers\MainController();
    $main->error404();
    return;
}

unset($matches[0]);

$controllerName = $controllerAndAction[0];
$actionName = $controllerAndAction[1];

$controller = new $controllerName();
$controller->$actionName(...$matches);