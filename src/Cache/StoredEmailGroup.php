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
    }

    public function add(StoredEmail $storedEmail)
    {
        $this->storedEmails[] = $storedEmail;
    }

    public function getBulk()
    {
        $data = "";
        foreach( $this->storedEmails as $storedEmail )
        {
            $data .= $storedEmail->getEmail()->getData();
        }
        return new Email($data);
    }
}