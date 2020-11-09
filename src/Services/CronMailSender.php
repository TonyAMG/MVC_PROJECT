<?php


namespace Services;

use Model\UserModel;

class CronMailSender
{

    private $mail;

    public function __construct()
    {
        $this->mail = MailService::getInstance();
    }

    public function sendRegMail()
    {
        $user = new UserModel();
        $user_regs = $user->extractUnsentRegForms();
        foreach ($user_regs as $key => $value) {
            if ($user_regs[$key]['sent_to_user'] === 'No') {
                $user_records_raw[] = $user->extractUserById($user_regs[$key]['user_id']);
            }
        }
        foreach ($user_records_raw as $key => $value) {
            $user_records[] = $value[0];
            $this->mail->registration($user_records[$key]);
            $this->mail->clearAddresses();
        }
    }
}