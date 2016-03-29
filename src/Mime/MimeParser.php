<?php
namespace Bulkdozer\Mime;

use Bulkdozer\Email;

interface MimeParser
{
    /**
     * @param $mimeText
     * @return Email
     */
    public function parse($mimeText);
}