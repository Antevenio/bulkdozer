<?php
namespace Bulkdozer\Comparator;

use Bulkdozer\Email;

interface EmailComparator
{
    public function isSimilar(Email $email1, Email $email2);
}