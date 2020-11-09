<?php


namespace Services;


use lib\Config;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use View\View;

class MailService
{

    private static $instance;
    private $config;
    private $mail;
    private $view;

    private function __construct()
    {
        $this->config = new Config();
        $this->mail = new PHPMailer(true);
        $this->view = new View();
    }

    public function registration($vars)
    {
        try {
            $this->mail->isSMTP();
            $this->mail->isHTML(true);
            $this->mail->SMTPAuth = true;
            $this->mail->Host = $this->config->mail_config['Host'];
            $this->mail->Username = $this->config->mail_config['Username'];
            $this->mail->Password = $this->config->mail_config['Password'];
            $this->mail->Subject = 'You have been successfully registered!';
            //генерируем html-страницу об успешной регистрации для отправки зарегистрированному пользователю на email
            $this->mail->Body = $this->view->htmlViewer('global_header', 'reg_successful', 'return').
                                $this->view->htmlViewer('mail_reg_form', $vars, 'return');
            $this->mail->addAddress($vars['email'], $vars['name']);
            $this->mail->setFrom('info@mvc-project.com', 'mvc-project.com');
            //$this->mail->AddEmbeddedImage($this->config->upload_photo_path, 'my-photo', 'my-photo.jpg ');
            $this->mail->send();

            echo '<b>Письмо было успешно отправлено!</b><br>';
        } catch (Exception $e) {
            echo "<b>Письмо не отправлено. Ошибка отправки</b>: {$this->mail->ErrorInfo}<br>";
        }
    }

    public function clearAddresses()
    {
        $this->mail->clearAddresses();
    }

    //singleton-интерфейс получения экземпляра
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}