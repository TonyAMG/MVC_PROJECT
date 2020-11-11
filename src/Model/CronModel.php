<?php


namespace Model;

use PDO;
use Services\DbService;

class CronModel
{
    private $db;

    public function __construct()
    {
        $this->db = DbService::getInstance();
    }

    //добавление записи в cron
    public function addUserMailToCron(): bool
    {
        $user_id = $this->db->lastInsertId();
        $query = "INSERT INTO `cron_reg_mail`
                      VALUES (NULL, :user_id, :sent_to_user, :sent_to_admin)";
        $params = [
            'user_id'       => $user_id,
            'sent_to_user'  => 'No',
            'sent_to_admin' => 'No'
        ];
        return $this->db->create($query, $params);
    }

    //обновляем статус отправленного email
    public function updateSentMailStatus($user_id): bool
    {
        $query = "UPDATE `cron_reg_mail`
                  SET `sent_to_user` = 'Yes'
                  WHERE `user_id` = :user_id";
        return $this->db->create($query, ['user_id' => $user_id]);
    }

    //извлечение данных о неотправленных регистрационных формах
    public function extractUnsentRegForms()
    {
        $query = "SELECT *
                  FROM `cron_reg_mail`";
        return $this->db->query($query, [], PDO::FETCH_ASSOC);
    }

}