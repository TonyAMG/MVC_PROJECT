<?php


namespace View;


class View
{
    private $parse_table;
    private $templates_dir;

    public function __construct()
    {
        require dirname(__DIR__) . '/../config/config.php';
        $this->parse_table = $parse_table;
        $this->templates_dir = $templates_dir;
    }

    //интерфейс viewer-а HTML-шаблонов
    public function htmlViewer($html_template, array $vars = [], $mode = "echo")
    {
        $this->html_template = $html_template;
        @$this->page_title = $vars["page_title"];
        if ($mode === "echo") {
            if ($this->html_template === 'header') header("Cache-Control: no-cache");
            echo   $this->templateParser($this->html_template);
        }
        if ($mode === "return") return $this->templateParser($this->html_template);
    }

    //резолвер имён HTML-шаблонов
    private function templateNameResolver($template_name)
    {
        return $this->templates_dir.$template_name.'.html';
    }

    //парсер HTML-шаблонов
    private function templateParser($html_template)
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
        return $this->htmlRender();
    }

    //рендер обработанной HTML-страницы
    private function htmlRender()
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