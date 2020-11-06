<?php


namespace Model;

use PDO;
use PDOException;
use Services\DbService;

class UserModel
{
    //регистрация пользователя
    public function registerUser($correct_answers) : bool
    {
        try {
            $db = DbService::getInstance();
            $query = "INSERT INTO `users`
                      VALUES (NULL, :name, :password, :sex,
                              :birth_year, :email, :about_yourself,
                              :upload_photo, :send_email, :card_type,
                              :card_number, NOW())";
            return $db->create($query, $correct_answers);
        } catch (PDOException $e) {
            //echo $e->getMessage();
            return false;
        }
    }

    //добавление записи в cron
    public function addUserMailToCron() : bool
    {
        $db = DbService::getInstance();
        $user_id = $db->lastInsertId();
        $query = "INSERT INTO `cron_reg_mail`
                      VALUES (NULL, :user_id, :sent_to_user, :sent_to_admin)";
        $params = [
            'user_id'       => $user_id,
            'sent_to_user'  => 'No',
            'sent_to_admin' => 'No'
        ];
        return $db->create($query, $params);
    }

    public function extractUserCredentials()
    {
        $db = DbService::getInstance();
        $query = "SELECT `name`, `password`
                  FROM users";
        return $db->query($query, [], PDO::FETCH_KEY_PAIR);
    }
}