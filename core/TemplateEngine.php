<?php

class TemplateEngine
{
    private $templatePath;

    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function render($template, $data = [])
    {
        $templateFile = $this->templatePath . '/' . $template . '.php';

        if (file_exists($templateFile)) {
            ob_start();
            extract($data);
            include $templateFile;
            return ob_get_clean();
        } else {
            throw new Exception('Template file not found: ' . $templateFile);
        }
    }
}
