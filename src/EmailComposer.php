<?php

namespace Bulkdozer;

use Bulkdozer\Template\TemplateEngine;

class EmailComposer
{
    public function __construct(TemplateEngine $templateEngine)
    {

    }

    public function getEmailFromTemplate($template, $variables)
    {
        return new Email( "Hola" );
    }
}