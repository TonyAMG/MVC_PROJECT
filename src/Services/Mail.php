<?php


namespace Services;


use lib\Config;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use View\View;

class Mail
{

    private $config;
    private $mail;
    private $view;

    public function __construct()
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
            $this->mail->Body = $this->view->htmlViewer('header', 'reg_successful', 'return').
                                $this->view->htmlViewer('mail_reg_form', $vars, 'return').
                                $this->view->htmlViewer('footer', '', 'return');
            $this->mail->addAddress($vars['email'], $vars['name']);
            $this->mail->setFrom('info@mvc-project.com', 'mvc-project.com');
            $this->mail->AddEmbeddedImage($this->config->upload_photo_path, 'my-photo', 'my-photo.jpg ');
            $this->mail->send();

            echo '<b>Письмо было успешно отправлено!</b>';
        } catch (Exception $e) {
            echo "<b>Письмо не отправлено. Ошибка отправки</b>: {$this->mail->ErrorInfo}";
        }
    }
}