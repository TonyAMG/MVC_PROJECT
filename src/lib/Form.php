<?php


namespace lib;


class Form
{
    protected $inputs_properties;
    protected $sanitized_post;
    protected $db_check;
    protected $error_msg;
    public $validation_error;

    public function __construct()
    {
        require dirname(__DIR__) . '/../config/config.php';
        $this->inputs_properties = $inputs_properties;
        $this->db_check = $db_check;
        $this->error_msg = $error_msg;
        $this->validation_error = '';
    }

    //метод извлечения и дезинфекции $_POST
    public function postReaper()
    {
        $ip = $this->inputs_properties;
        foreach ($ip as $key => $value) {
            if (isset($ip[$key]["max_length"])) {
                @$sanitized_post[$key] = mb_substr($_POST[$key], 0, $ip[$key]["max_length"]);
            } else {
                @$sanitized_post[$key] = $_POST[$key];
            }
            $sanitized_post[$key] = htmlspecialchars(trim($sanitized_post[$key]));
            //записываем дезинфицированные данные в сессию
            $_SESSION[$key] = $sanitized_post[$key];
        }
        $this->sanitized_post = $sanitized_post;
        return $sanitized_post;
    }


}