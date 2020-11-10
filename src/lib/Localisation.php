<?php


namespace lib;


use Services\ConfigService;

class Localisation
{
    public $config;
    public $page_title;
    public $preview_template_loc;

    public function __construct()
    {
        $this->config = ConfigService::getInstance();
        require $this->config->localisation_dir.$this->config->localisation_file;
        $this->page_title = $page_title;
        $this->preview_template_loc = $preview_template_loc;
    }
}