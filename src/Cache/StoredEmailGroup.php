<?php
namespace Bulkdozer\Cache;

use Bulkdozer\Email;

class StoredEmailGroup
{
    /**
     * @var StoredEmail[]
     */
    protected $storedEmails;

    public function __construct()
    {
        $this->storedEmails = array();
    }

    public function add(StoredEmail $storedEmail)
    {
        $this->storedEmails[] = $storedEmail;
    }

    public function getCount()
    {
        return count($this->storedEmails);
    }

    public function getFirst()
    {
        return ($this->storedEmails[0]);
    }

    public function getLast()
    {
        return ($this->storedEmails[count($this->storedEmails)-1]);
    }
}