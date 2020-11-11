<?php


namespace Model;

use PDO;
use PDOException;
use Services\DbService;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = DbService::getInstance();
    }

    //регистрация пользователя
    public function registerUser($correct_answers): bool
    {
        try {
            $query = "INSERT INTO `users`
                      VALUES (NULL, :name, :password, :sex,
                              :birth_year, :email, :about_yourself,
                              :upload_photo, :send_email, :card_type,
                              :card_number, NOW())";
            return $this->db->create($query, $correct_answers);
            var_dump($correct_answers);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //извлекаем логин и пароль(хеш) пользователя
    public function extractUserCredentials()
    {
        $query = "SELECT `name`, `password`
                  FROM users";
        return $this->db->query($query, [], PDO::FETCH_KEY_PAIR);
    }

    public function extractUserName()
    {
        $query = "SELECT `name`
                  FROM `users`";
        $names_raw = $this->db->query($query, [], PDO::FETCH_COLUMN);
        foreach ($names_raw as $key => $value) {
            $names['name'][$key] = $value;
        }
        return @$names;
    }

    public function extractUserEmail()
    {
        $query = "SELECT `email`
                  FROM `users`";
        $emails_raw = $this->db->query($query, [], PDO::FETCH_COLUMN);
        foreach ($emails_raw as $key => $value) {
            $emails['email'][$key] = $value;
        }
        return @$emails;
    }

    //извлекаем целиком строку пользователя по id
    public function extractUserById($id)
    {
        $query = "SELECT *
                  FROM `users`
                  WHERE `user_id` = :user_id";
        return $this->db->query($query, ['user_id' => $id], PDO::FETCH_ASSOC);
    }


}
