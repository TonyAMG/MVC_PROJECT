<?php


namespace lib;


class Form
{
    protected $inputs_properties;
    protected $db_check;
    protected $error_msg;
    public $sanitized_post;


    public function __construct($inputs_properties, $db_check, $error_msg)
    {
        $this->inputs_properties = $inputs_properties;
        $this->db_check = $db_check;
        $this->error_msg = $error_msg;
    }

    //метод извлечения и дезинфекции $_POST
    public function postReaper()
    {
        $ip = $this->inputs_properties;
        foreach ($ip as $key => $value) {
            if (isset($ip[$key]["max_length"])) {
                @$sanitized_post[$key] = mb_substr($_POST[$key]??$_SESSION[$key], 0, $ip[$key]["max_length"]);
            } else {
                @$sanitized_post[$key] = $_POST[$key]??$_SESSION[$key];
            }
            $sanitized_post[$key] = htmlspecialchars(trim($sanitized_post[$key]));
            //записываем дезинфицированные данные в сессию
            $_SESSION[$key] = $sanitized_post[$key];
        }
        $this->sanitized_post = $sanitized_post;
        return $sanitized_post;
    }


}