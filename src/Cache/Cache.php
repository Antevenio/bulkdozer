<?php
namespace Bulkdozer\Cache;

use Bulkdozer\Email;

interface Cache
{
   /**
     * @param Email $email
     * @return $id | FALSE
     */
    public function search(Email $email);

    /**
     * @param $id
     * @return StoredEmailGroup | FALSE
     */
    public function retrieve($id);

    /**
     * @param $id
     * @param Email $email
     * @return mixed
     */
    public function add($id, Email $email);

    /**
     * @param $id
     * @return mixed
     */
    public function getSize($id);

    /**
     * @param Email $email
     * @return mixed
     */
    public function create(Email $email);

    /**
     * @return $id | FALSE
     */
    public function getPending();

    public function remove($id);
}