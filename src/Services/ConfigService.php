<?php


namespace Services;


class ConfigService
{
    private static $instance;
    public $host;
    public $templates_dir;
    public $localisation_dir;
    public $localisation_file;
    public $font;
    public $upload_dir;
    public $upload_photo_path;
    public $photo_max_size;
    public $routes_list;
    public $inputs_properties;
    public $db_check;
    public $parse_table;
    public $error_msg;
    public $mail_config;
    public $db_config;

    private function __construct()
    {
        require dirname(__FILE__) . '/../../config/config.php';
        $this->host = $host;
        $this->templates_dir = $templates_dir;
        $this->localisation_dir =$localisation_dir;
        $this->localisation_file =$localisation_file;
        $this->font = $font;
        $this->upload_dir = $upload_dir;
        $this->upload_photo_path = $upload_photo_path;
        $this->photo_max_size = $photo_max_size;
        $this->routes_list = $routes_list;
        $this->inputs_properties = $inputs_properties;
        $this->db_check = $db_check;
        $this->parse_table = $parse_table;
        $this->error_msg = $error_msg;
        $this->mail_config = require __DIR__ . '/../../config/mail_config.php';
        $this->db_config = require __DIR__ . '/../../config/db_config.php';
    }

    //singleton-интерфейс получения экземпляра
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}