<?php


namespace lib;


class Localisation
{
    public $config;
    public $page_title;
    public $preview_template_loc;

    public function __construct()
    {
        $this->config = new Config();
        require $this->config->localisation_dir.$this->config->localisation_file;
        $this->page_title = $page_title;
        $this->preview_template_loc = $preview_template_loc;
    }
}