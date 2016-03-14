<?php
namespace Bulkdozer\TemplateEngine;

interface TemplateEngine
{
    public function render($template, $variables);
}