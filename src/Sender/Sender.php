<?php
namespace Bulkdozer\Sender;

use Bulkdozer\Email;

interface Sender
{
    public function send(Email $email);
}