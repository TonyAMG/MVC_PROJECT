<?php


namespace Controllers;

use lib\Config;
use lib\FileUploader;
use lib\FormReg;
use Model\UserModel;
use Services\MailService;
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
        $this->form_reg = new FormReg();
        $this->file_uploader = new FileUploader(
            $this->config->photo_max_size,
            $this->config->upload_photo_path,
            $this->config->error_msg
        );
    }

    //основной action - main
    public function mainAction(): void
    {
        //обработка введенных данных
        $this->form_reg->PostReaper();
        $user = new UserModel();
        $users_names = $user->extractUserName();
        $users_emails = $user->extractUserEmail();
        $this->form_reg->dbCheck($users_names+$users_emails);
        $this->form_reg->validator();
        $correct_answers = $this->form_reg->correctAnswersChecker();
        $is_photo_uploaded = $this->file_uploader->photoUploader($this->form_reg->validation_error);

        //если нажата кнопка Регистрации
        if (isset($_POST["button"])) {
            //подгружаем блок с ошибками, если они есть
            if (!empty($this->form_reg->validation_error)) {
                $errors = $this->view->htmlViewer('global_form_errors', $this->form_reg->validation_error, 'return');
            }
            //если успешно заполенены все поля и загружено фото
            if ((@count($correct_answers) === count($this->config->inputs_properties)) && $is_photo_uploaded) {
                //создаем хеш пароля
                $correct_answers['password'] = password_hash($correct_answers['password'], PASSWORD_DEFAULT);
                //пытаемся зарегистрировать пользователя

                $user = new UserModel();
                $reg_status = $user->registerUser($correct_answers);

                if ($reg_status === true) {
                    //добавляем задание в табоицу cron
                    $mail_cron_status = $user->addUserMailToCron();
                    //отправляем форму регистрации на Email
                    //$mail = new MailService();
                    //$mail->registration($correct_answers);
                    $_SESSION['reg_status'] = 'success';
                    header('Location: /'.$this->config->host.'/reg/successful');
                } else {
                    $main_controller = new MainController();
                    $main_controller->errorServerAction();
                    exit;
                }
            }
        }
        //блок вывода HTML-страницы
        $this->view->htmlViewer('global_header', 'registration');
        $this->view->htmlViewer('reg_form_main');
        echo $errors ?? '';
        $this->view->htmlViewer('reg_form_preview', $correct_answers);
        $this->view->htmlViewer('global_footer');
    }

    //action успешной регистрации пользователя
    public function successfulRegAction(): void
    {
        //проверяем, что пользователь был переброшен
        //на action сразу после успешной регистрации
        if (@$_SESSION['reg_status'] === 'success'){
            unset($_SESSION['reg_status']);
            $this->view->htmlViewer('global_header', 'reg_successful');
            $this->view->htmlViewer('mail_reg_form', $_SESSION['input']);
            $this->view->htmlViewer('global_footer');
        //запрещаем обращение к странице любым другим способом
        } else {
            $main_controller = new MainController();
            $main_controller->error404Action();
        }
    }

    //action удаления сессии
    public function sessionUnsetAction(): void
    {
        session_unset();
        @unlink($this->config->upload_photo_path);
        header('Location: /'.$this->config->host.'/reg/');
    }
}