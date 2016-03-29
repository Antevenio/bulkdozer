<?php
namespace Bulkdozer\Cache;

use Bulkdozer\Comparator\EmailComparator;
use Bulkdozer\Comparator\TextComparator;
use Bulkdozer\Email;

interface Cache
{
 /**
  * @param Email $email
  * @param TextComparator $comparator
  * @return  $id | FALSE
  */
    public function findSimilar(Email $email, EmailComparator $comparator);

    /**
     * @param $id
     * @return StoredEmailGroup | FALSE
     */
    public function getGroup($id);

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