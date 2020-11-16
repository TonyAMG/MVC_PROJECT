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

    public function mainAction(): void
    {
        $this->view->htmlViewer('global_header', 'main_page');
        $this->view->htmlViewer('main_page');
        $this->view->htmlViewer('global_footer');
    }

    //action ошибки 404
    public function error404Action(): void
    {
        $this->view->htmlViewer('global_header', 'error404');
        $this->view->htmlViewer('global_error_404');
        $this->view->htmlViewer('global_footer');
    }

    //action ошибки сервера
    public function errorServerAction(): void
    {
        $this->view->htmlViewer('global_header', 'server_error');
        $this->view->htmlViewer('global_error_server');
        $this->view->htmlViewer('global_footer');
    }
}