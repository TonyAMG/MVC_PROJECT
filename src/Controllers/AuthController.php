<?php


namespace Controllers;


use lib\Captcha;
use lib\Config;
use lib\Form;
use Model\UserModel;
use View\View;


class AuthController
{

    private $view;
    private $config;
    private $captcha;
    private $secret_number;

    public function __construct()
    {
        $this->view = new View();
        $this->config = new Config();
        $this->captcha = new Captcha();
        $this->secret_number = mt_rand(1000, 9999);
    }

    public function mainAction()
    {
        //если нажата кнопка Войти
        if (isset($_POST["button"])) {
            $form = new Form();
            $name = $form->postReaper()['name'];
            $password = $form->postReaper()['password'];
            $user = new UserModel();
            $users_credentials = $user->extractUserCredentials();

            if (
                //проверяем существование пользователя в базе данных
                array_key_exists($name, $users_credentials)
                //проверяем введенный пароль по хешу из базы данных
                && password_verify($password, $users_credentials[$name])
            ) {
                $_SESSION['auth_status'] = 'success';
                header('Location: /'.$this->config->host.'/auth/successful');
            } else {
                $errors = $this->config->error_msg['name']['login_error'];
            }
        }

        $this->view->htmlViewer('global_header', 'auth');
        $this->view->htmlViewer('auth_form_main');
        !isset($errors) ?: $this->view->htmlViewer('global_form_errors', $errors);
        $this->view->htmlViewer('auth_form_footer');
        $this->view->htmlViewer('global_footer');
    }

    public function successfulAuthAction()
    {
        //проверяем, что пользователь был переброшен
        //на action сразу после успешной аутентификации
        if (@$_SESSION['auth_status'] === 'success'){
            unset($_SESSION['auth_status']);
            $this->view->htmlViewer('global_header', 'auth_successful');
            $this->view->htmlViewer('logged_in');
            $this->view->htmlViewer('global_footer');
            //запрещаем обращение к странице любым другим способом
        } else {
            $main_controller = new MainController();
            $main_controller->error404Action();
        }
    }

    public function captchaAction()
    {
        $this->captcha->generateCAPTCHA($this->secret_number);
    }
}