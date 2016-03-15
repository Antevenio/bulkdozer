<?php
namespace Bulkdozer\Cache;

use Bulkdozer\Comparator\TextComparator;
use Bulkdozer\Email;

interface Cache
{
 /**
  * @param Email $email
  * @param TextComparator $comparator
  * @return  $id | FALSE
  */
    public function search(Email $email, TextComparator $comparator);

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
    public function getPending($seconds);

    public function remove($id);
}