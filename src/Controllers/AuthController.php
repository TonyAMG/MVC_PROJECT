<?php


namespace Controllers;


use lib\Captcha;
use lib\Config;
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
            $login = $_POST['login'];
            $password = $_POST['password'];
            $user = new UserModel();
            $users_credentials = $user->extractUserCredentials();
            if (array_key_exists($login, $users_credentials)) {
                if ($users_credentials[$login] === $password) {
                    $_SESSION['auth_status'] = 'success';
                    header('Location: /'.$this->config->host.'/auth/successful');
                }
            }
        }

        $this->view->htmlViewer('header', 'auth');
        $this->view->htmlViewer('auth');
        $this->view->htmlViewer('footer');
    }

    public function successfulAuthAction()
    {
        //проверяем, что пользователь был переброшен
        //на action сразу после успешной аутентификации
        if (@$_SESSION['auth_status'] === 'success'){
            unset($_SESSION['auth_status']);
            $this->view->htmlViewer('header', 'auth_successful');
            $this->view->htmlViewer('logged_in');
            $this->view->htmlViewer('footer');
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