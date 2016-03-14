<?php
namespace BulkDozer\Storage;

use Bulkdozer\Cache\StoredEmailGroup;

interface Storage
{
    /**
     * @param StoredEmailGroup $group
     * @return string
     */
    public function store(StoredEmailGroup $group);

    public function getShortcut($id);
}