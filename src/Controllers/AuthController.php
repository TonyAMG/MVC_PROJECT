<?php


namespace Controllers;


use lib\Captcha;
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
        $this->view->htmlViewer('header', 'auth');
        $this->view->htmlViewer('auth');
        $this->view->htmlViewer('footer');
    }

    public function captcha()
    {
        $this->captcha->generateCAPTCHA($this->secret_number);
    }
}