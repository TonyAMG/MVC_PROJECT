<?php


namespace Controllers;

use lib\FormReg;
use View\View;

class RegController
{
    private $view;
    private $config;
    private $form_reg;

    public function __construct()
    {
        $this->config = new ConfigController();
        $this->view = new View();
        $this->form_reg = new FormReg(
            $this->config->inputs_properties,
            $this->config->db_check,
            $this->config->error_msg
        );
    }

    //основной action - main
    public function main()
    {
        $this->form_reg->PostReaper();
        $this->form_reg->dbCheck();
        $this->form_reg->validator();
        $this->view->htmlViewer('header', ["page_title" => "Регистрация пользователя"]);
        $this->view->htmlViewer('registration_form');
        //если нажата кнопка Регистрации, подгружаем блок с ошибками
        if (isset($_POST["button"])) {
            $this->view->htmlViewer('errors',  $this->form_reg->validation_error);
        }

        $this->view->htmlViewer('preview', $this->form_reg->correctAnswersChecker());
        $this->view->htmlViewer('footer');
    }

    //action удаления сессий
    public function sessionUnset()
    {
        session_unset();
        //@unlink($upload_photo_path);
        header('Location: /'.$this->config->host.'/reg/');
    }
}