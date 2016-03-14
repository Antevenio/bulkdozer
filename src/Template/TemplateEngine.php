<?php
namespace Bulkdozer\Template;

interface TemplateEngine
{
    public function render($template, $variables);
}