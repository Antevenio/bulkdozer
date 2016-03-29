<?php

namespace Bulkdozer;

class Email {
    protected $data;

    public function __construct( $mimeData )
    {
        $this->mimeData = $mimeData;
    }

    public function buildMime()
    {
        
    }

    public function getData()
    {
        return ($this->data);
    }
}