<?php


namespace lib;


class Form
{
    protected $inputs_properties;
    protected $db_check;
    protected $error_msg;
    protected $upload_dir;
    public $sanitized_post;

    public function __construct()
    {
        $config = new Config();
        $this->inputs_properties = $config->inputs_properties;
        $this->db_check = $config->db_check;
        $this->error_msg = $config->error_msg;
        $this->upload_dir = $config->upload_dir;
    }

    //метод извлечения и дезинфекции $_POST
    public function postReaper(): array
    {
        $ip = $this->inputs_properties;
        foreach ($ip as $key => $value) {
            //обрезаем данные до длины, указанной в свойствах
            if (isset($ip[$key]["max_length"])) {
                @$sanitized_post[$key] = mb_substr(
                    $_POST[$key]??$_SESSION["input"][$key],
                    0,
                    $ip[$key]["max_length"]
                );
            } else {
                @$sanitized_post[$key] = $_POST[$key]??$_SESSION["input"][$key];
            }
            //дезинфицируем данные
            $sanitized_post[$key] = htmlspecialchars(trim($sanitized_post[$key]));
            //записываем данные в сессию
            $_SESSION["input"][$key] = $sanitized_post[$key];
        }
        $this->sanitized_post = $sanitized_post;
        return $sanitized_post;
    }


}