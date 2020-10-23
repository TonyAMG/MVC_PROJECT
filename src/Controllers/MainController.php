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

    //action удаления сессий
    public function sessionUnset()
    {
        session_unset();
        //@unlink($upload_photo_path);
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }

    //action релокейшна на главную страницу из любой точки сайта
    public function mainPage()
    {
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/MVC_PROJECT/');
    }

    //action ошибки 404
    public function error404()
    {
        $this->view->htmlViewer('header', ["page_title" => "Ошибка"]);
        $this->view->htmlViewer('404');
        $this->view->htmlViewer('footer');
    }
}