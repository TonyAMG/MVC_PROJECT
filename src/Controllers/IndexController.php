<?php


namespace Controllers;


use Services\ConfigService;

class IndexController
{

    private $config;
    private $route;


    public function __construct($route)
    {
        $this->config = ConfigService::getInstance();
        $this->route = $route;
    }

    public function mainAction() : void
    {
        $isRouteFound = false;
        foreach ($this->config->routes_list as $pattern => $controllerAndAction) {
            preg_match($pattern, $this->route, $matches);
            if (!empty($matches)) {
                $isRouteFound = true;
                break;
            }
        }
        //ошибка 404, если запрашиваемый путь не найден
        if (!$isRouteFound) {
            $main = new MainController();
            $main->error404Action();
            return;
        }

        unset($matches[0]);

        $controllerName = $controllerAndAction[0];
        $actionName = $controllerAndAction[1];

        $controller = new $controllerName();
        $controller->$actionName(...$matches);
    }
}