<?php
namespace Bulkdozer\Template\Engine;

use Bulkdozer\Template\TemplateEngine;

class Twig implements TemplateEngine
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    public function __construct()
    {

    }

    public function render($template, $variables)
    {
        return $this->twig->render($template, $variables);
    }
}