<?php


namespace Daemons;

use Model\UserModel;
use Model\CronModel;
use Services\MailService;

class CronMailSender
{

    private $mail;

    public function __construct()
    {
        $this->mail = MailService::getInstance();
    }

    public function sendRegMail()
    {
        $cron = new CronModel();
        $user = new UserModel();
        //извлекаем данные из БД о пользовательских регистрациях
        $user_regs = $cron->extractUnsentRegForms();
        foreach ($user_regs as $key => $value) {
            //если форма успешной регистрации еще не отправлена
            //на email, пакуем данные в массив для дальнешей работы
            if ($user_regs[$key]['sent_to_user'] === 'No') {
                $user_records_raw[] = $user->extractUserById($user_regs[$key]['user_id']);
            }
        }
        //если в базе есть не отправленные письма
        if (isset($user_records_raw)) {
            foreach ($user_records_raw as $key => $value) {
                $user_records[] = $value[0];
                //пытаемся отправить письмо
                if ($this->mail->registration($user_records[$key])) {
                    //если письмо успешно отослано
                    //обновляем таблицу в БД
                    $cron->updateSentMailStatus($user_records[$key]['user_id']);
                    $this->mail->clearAddresses();
                }
            }
        }
    }
}