<?php


namespace View;


use lib\Config;

class View
{
    private $config;
    private $parse_table;
    private $templates_dir;
    private $page_title;

    public function __construct()
    {
        $this->config = new Config();
        $this->parse_table = $this->config->parse_table;
        $this->templates_dir = $this->config->templates_dir;
    }

    //интерфейс viewer-а HTML-шаблонов
    public function htmlViewer($html_template, $vars = '', $mode = "echo") : ?string
    {
        $this->html_template = $html_template;
        @$this->page_title = $vars["page_title"];
        if ($mode === "echo") {
            if ($this->html_template === 'header') {
                header("Cache-Control: no-cache");
                header("Content-Type: text/html; charset=utf-8");
            }
            echo   $this->templateParser($this->html_template, $vars);
            return NULL;
        }
        if ($mode === "return") return $this->templateParser($this->html_template, $vars);
    }

    //резолвер имён HTML-шаблонов
    private function templateNameResolver($template_name)
    {
        return $this->templates_dir.$template_name.'.html';
    }

    //парсер HTML-шаблонов
    private function templateParser($html_template, $vars = '')
    {
        $raw_html = file_get_contents($this->templateNameResolver($html_template));
        foreach ($this->parse_table as $key => $value) {
            $search[] = $key;
            $replace[] = $value;
        }
        @$replaced_template = str_replace($search, $replace, $raw_html);
        //подстановка требуемого заголовка в страницу
        @$replaced_template = str_replace('<!--TITLE-->', $this->page_title, $replaced_template);
        $this->parsed_html = preg_replace($this->parse_table["regex"][0][0], $this->parse_table["regex"][0][1], $replaced_template);
        return $this->htmlRender($vars);
    }

    //рендер обработанной HTML-страницы
    private function htmlRender($vars = '')
    {
        ob_start();
        eval("?> $this->parsed_html <?php ");
        $template = ob_get_contents();
        ob_end_clean();
        return $template;
    }

    //генератор пробелов в HTML-странице
    private function spaceGen($space_num)
    {
        $space = '';
        for ($i = 0; $i < $space_num; $i++) {
            $space .= '&nbsp;';
        }
        return $space;
    }

}