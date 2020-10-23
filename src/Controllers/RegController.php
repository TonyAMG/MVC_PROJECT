<?php


namespace Controllers;

use lib\FormReg;
use View\View;

class RegController
{

    private $view;

    public function __construct()
    {
        $this->view = new View();
        $this->form_reg = new FormReg();
    }

    //основной action - main
    public function main()
    {
        $this->view->htmlViewer('header', ["page_title" => "Регистрация пользователя"]);
        $this->view->htmlViewer('registration_form');
        $this->view->htmlViewer('errors');
        echo var_dump($this->form_reg->validation_error);
        $this->view->htmlViewer('footer');
    }

    //action нажатия на кнопку Регистрация
    public function register()
    {
        $this->form_reg->PostReaper();
        $this->form_reg->dbCheck();

        //$this->form_reg->validator();

        header('Location: http://'.$_SERVER['HTTP_HOST'].'/MVC_PROJECT/reg/');
    }


}