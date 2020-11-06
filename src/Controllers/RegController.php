<?php


namespace Controllers;

use lib\Config;
use lib\FileUploader;
use lib\FormReg;
use Model\UserModel;
use Services\Mail;
use View\View;

class RegController
{
    private $view;
    private $config;
    private $form_reg;
    private $file_uploader;

    public function __construct()
    {
        $this->config = new Config();
        $this->view = new View();
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
        //обработка введенных данных
        $this->form_reg->PostReaper();
        $this->form_reg->dbCheck();
        $this->form_reg->validator();
        $correct_answers = $this->form_reg->correctAnswersChecker();
        $is_photo_uploaded = $this->file_uploader->photoUploader($this->form_reg->validation_error);

        //если нажата кнопка Регистрации
        if (isset($_POST["button"])) {
            //подгружаем блок с ошибками, если они есть
            if (!empty($this->form_reg->validation_error)) {
                $errors = $this->view->htmlViewer('errors', $this->form_reg->validation_error, 'return');
            }
            //если успешно заполенены все поля и загружено фото
            if ((@count($correct_answers) === count($this->config->inputs_properties))
                && $is_photo_uploaded) {

                $user_model = new UserModel();
                $reg_status = $user_model->registerUser($correct_answers);
                if ($reg_status === true) {
                    $_SESSION['reg_status'] = 'success';
                    header('Location: /'.$this->config->host.'/reg/successful');
                } else {
                    $main_controller = new MainController();
                    $main_controller->errorServerAction();
                    exit;
                }
                //отправляем форму регистрации на Email
                //$mail = new Mail();
                //$mail->registration($correct_answers);
                    //$_SESSION['reg_status'] = 'success';
                    //header('Location: /'.$this->config->host.'/reg_successful/');

            }
        }
        //блок вывода HTML-страницы
        $this->view->htmlViewer('header', 'registration');
        $this->view->htmlViewer('registration_form');
        echo $errors ?? '';
        $this->view->htmlViewer('preview', $correct_answers);
        $this->view->htmlViewer('footer');
    }

    //action успешной регистрации пользователя
    public function successfulRegAction() : void
    {
        //проверяем, что пользователь был переброшен
        //на action сразу после успешной регистрации
        if (@$_SESSION['reg_status'] === 'success'){
            unset($_SESSION['reg_status']);
            $this->view->htmlViewer('header', 'reg_successful');
            $this->view->htmlViewer('mail_reg_form', $_SESSION['input']);
            $this->view->htmlViewer('footer');
        //запрещаем обращение к странице любым другим способом
        } else {
            $main_controller = new MainController();
            $main_controller->error404();
        }
    }

    //action удаления сессии
    public function sessionUnsetAction() : void
    {
        session_unset();
        @unlink($this->config->upload_photo_path);
        header('Location: /'.$this->config->host.'/reg/');
    }
}