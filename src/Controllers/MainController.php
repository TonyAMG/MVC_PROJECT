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

    public function mainAction()
    {
        $this->view->htmlViewer('header', 'main_page');
        $this->view->htmlViewer('main');
        $this->view->htmlViewer('footer');
    }

    //action ошибки 404
    public function error404Action()
    {
        $this->view->htmlViewer('header', 'error404');
        $this->view->htmlViewer('404');
        $this->view->htmlViewer('footer');
    }

    //action ошибки сервера
    public function errorServerAction()
    {
        $this->view->htmlViewer('header', 'server_error');
        $this->view->htmlViewer('server_error');
        $this->view->htmlViewer('footer');
    }
}