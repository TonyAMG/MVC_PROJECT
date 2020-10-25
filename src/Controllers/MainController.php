<?php


namespace Controllers;

use View\View;

class MainController
{
    private $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function main()
    {
        $this->view->htmlViewer('header', ["page_title" => "Главная страница"]);
        $this->view->htmlViewer('main');
        $this->view->htmlViewer('footer');
    }

    //action ошибки 404
    public function error404()
    {
        $this->view->htmlViewer('header', ["page_title" => "Ошибка"]);
        $this->view->htmlViewer('404');
        $this->view->htmlViewer('footer');
    }
}