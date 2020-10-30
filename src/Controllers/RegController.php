<?php


namespace Controllers;

use lib\Config;
use lib\FileUploader;
use lib\FormReg;
use lib\Localisation;
use View\View;

class RegController
{
    private $view;
    private $config;
    private $localisation;
    private $form_reg;
    private $file_uploader;

    public function __construct()
    {
        $this->config = new Config();
        $this->view = new View();
        $this->localisation = new Localisation();
        $this->form_reg = new FormReg(
            $this->config->inputs_properties,
            $this->config->db_check,
            $this->config->error_msg,
            $this->config->upload_dir
        );
        $this->file_uploader = new FileUploader(
            $this->config->photo_max_size,
            $this->config->upload_photo_path,
            $this->config->error_msg
        );
    }

    //основной action - main
    public function mainAction() : void
    {
        $this->form_reg->PostReaper();
        $this->form_reg->dbCheck();
        $this->form_reg->validator();
        $correct_answers = $this->form_reg->correctAnswersChecker();
        $this->view->htmlViewer('header', ["page_title" => $this->localisation->page_title["registration"]]);
        $this->view->htmlViewer('registration_form');
        //если нажата кнопка Регистрации
        if (isset($_POST["button"])) {
            $is_photo_uploaded = $this->file_uploader->photoUploader($this->form_reg->validation_error);
            //подгружаем блок с ошибками, если они есть
            if (!empty($this->form_reg->validation_error)) {
                $this->view->htmlViewer('errors',  $this->form_reg->validation_error);
            }
            //сообщаем об успешном заполении всех полей, и загрузке фото
            if ((@count($correct_answers) === count($this->config->inputs_properties))
                && $is_photo_uploaded) {
                $this->view->htmlViewer('success');
            }
        }
        $this->view->htmlViewer('preview', $correct_answers);
        $this->view->htmlViewer('footer');
    }

    //action удаления сессий
    public function sessionUnsetAction() : void
    {
        session_unset();
        @unlink($this->config->upload_photo_path);
        header('Location: /'.$this->config->host.'/reg/');
    }
}