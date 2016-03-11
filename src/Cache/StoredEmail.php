<?php
namespace Bulkdozer\Cache;

use Bulkdozer\Email;

class StoredEmail
{
    /**
     * @var Email
     */
    protected $email;
    protected $timestamp;

    public function __construct(Email $email, $timestamp)
    {
        $this->email = $email;
        $this->timestamp = $timestamp;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }
}