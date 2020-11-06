<?php


namespace Model;

use PDO;
use PDOException;
use Services\DbService;

class UserModel
{
    public function registerUser($correct_answers) : bool
    {
        try {
            $db = DbService::getInstance();
            $query = "INSERT INTO `users`
                      VALUES (NULL, :name, :password, :sex, :birth_year, :email, :about_yourself, :upload_photo, :send_email, :card_type, :card_number)";
            return $db->create($query, $correct_answers);
        } catch (PDOException $e) {
            return false;
        }
    }
}