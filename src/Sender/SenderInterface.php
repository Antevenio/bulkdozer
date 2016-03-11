<?php
namespace Bulkdozer\Sender;

use Bulkdozer\Email;

interface SenderInterface
{
    public function send(Email $email);
}