<?php


namespace Controllers;


use lib\Captcha;
use Services\ConfigService;
use lib\Form;
use Model\UserModel;
use View\View;


class AuthController
{

    private $view;
    private $config;
    private $captcha;

    public function __construct()
    {
        $this->view = new View();
        $this->config = ConfigService::getInstance();
        $this->captcha = new Captcha();
    }

    public function mainAction(): void
    {
        //если нажата кнопка Войти
        if (isset($_POST["button"])) {
            $form = new Form();
            $name = $form->postReaper()['name'];
            $password = $form->postReaper()['password'];
            $secret_number = (int) $_POST['captcha'];
            $user = new UserModel();
            $users_credentials = $user->extractUserCredentials();

            //проверяем что введенная пользователем капча верна
            if ($secret_number === $_SESSION['secret_number']){
                if (
                    //проверяем существование пользователя в базе данных
                    array_key_exists($name, $users_credentials)
                    //проверяем введенный пароль по хешу из базы данных
                    && password_verify($password, $users_credentials[$name])
                ) {
                    $_SESSION['auth_status'] = 'success';
                    unset($_SESSION['secret_number']);
                    header('Location: /'.$this->config->host.'/auth/successful');
                } else {
                    $errors = $this->config->error_msg['name']['login_error'];
                }
            } else {
                $errors = $this->config->error_msg['name']['captcha_error'];
            }
        }

        //блок вывода HTML-страницы
        $this->view->htmlViewer('global_header', 'auth');
        $this->view->htmlViewer('auth_form_main');
        !isset($errors) ?: $this->view->htmlViewer('global_form_errors', $errors);
        $this->view->htmlViewer('auth_form_footer');
        $this->view->htmlViewer('global_footer');
    }

    public function successfulAuthAction(): void
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

    public function captchaAction(): void
    {
        $this->captcha->generateCAPTCHA();
    }
}