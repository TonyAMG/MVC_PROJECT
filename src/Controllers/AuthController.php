<?php


namespace Controllers;


use View\View;

class AuthController
{

    private $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function main()
    {
        $this->view->htmlViewer('header', ["page_title" => "Вход на сайт"]);
        $this->view->htmlViewer('auth');
        $this->view->htmlViewer('footer');
    }
}