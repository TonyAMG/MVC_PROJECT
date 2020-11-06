<?php


namespace Controllers;


use lib\Captcha;
use Model\UserModel;
use View\View;

class AuthController
{

    private $view;
    private $captcha;
    private $secret_number;

    public function __construct()
    {
        $this->view = new View();
        $this->captcha = new Captcha();
        $this->secret_number = mt_rand(1000, 9999);
    }

    public function main()
    {
        //если нажата кнопка Войти
        if (isset($_POST["button"])) {
            $user = new UserModel();
            $users_credentials = $user->extractUserCredentials();

            if (array_key_exists($_POST['login'], $users_credentials)) {
                echo "Пользователь существует!";
            }

        }

        $this->view->htmlViewer('header', 'auth');
        $this->view->htmlViewer('auth');
        $this->view->htmlViewer('footer');
    }

    public function captcha()
    {
        $this->captcha->generateCAPTCHA($this->secret_number);
    }
}