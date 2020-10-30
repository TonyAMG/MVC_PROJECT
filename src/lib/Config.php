<?php


namespace lib;


class Config
{
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

    public function __construct()
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
    }
}