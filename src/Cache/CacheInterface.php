<?php
namespace Bulkdozer\Cache;

use Bulkdozer\Cache\CacheId;
use Bulkdozer\Email;

interface CacheInterface
{
   /**
     * @param Email $email
     * @return CacheId | FALSE
     */
    public function search(Email $email);

    /**
     * @param CacheId $id
     * @return StoredEmailGroup | FALSE
     */
    public function retrieve(CacheId $id);

    /**
     * @param CacheId $id
     * @param Email $email
     * @return mixed
     */
    public function add(CacheId $id, Email $email);

    /**
     * @param CacheId $id
     * @return mixed
     */
    public function getSize(CacheId $id);

    /**
     * @param Email $email
     * @return mixed
     */
    public function create(Email $email);

    /**
     * @return CacheId | FALSE
     */
    public function getPending();

    public function remove(CacheId $id);
}