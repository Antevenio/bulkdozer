<?php

namespace Bulkdozer;

class Email {
    protected $data;

    public function __construct( $data )
    {
        $this->data = $data;
    }

    public function getData()
    {
        return ($this->data);
    }
}