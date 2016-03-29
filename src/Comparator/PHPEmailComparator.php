<?php
namespace Bulkdozer\Comparator;

use Bulkdozer\Email;
use Comparator\PHPTextComparator;

class PHPEmailComparator implements EmailComparator
{
    protected $textComparator;

    public function __construct(PHPTextComparator $comparator)
    {
        $this->textComparator = $comparator;
    }

    public function isSimilar(Email $email1, Email $email2)
    {
        return ($this->textComparator->isSimilar($email1->getData(), $email2->getData()));
    }
}