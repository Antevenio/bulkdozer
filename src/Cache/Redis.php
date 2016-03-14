<?php
namespace Bulkdozer\Cache;

use Bulkdozer\Comparator\TextComparator;
use Bulkdozer\Email;
use Predis\Collection\Iterator\Keyspace;

class Redis implements Cache
{
    /**
     * @var \PRedis\ClientInterface
     */
    protected $redis;
    const KEY_PREFIX = "blkdz:";
    const EMAIL_PREFIX = "email:";
    const TIME_PREFIX = "time:";

    public function __construct()
    {
    }

    public function search(Email $email, TextComparator $comparator)
    {
        $it = new Keyspace($this->redis, static::KEY_PREFIX .
            static::EMAIL_PREFIX . '*'
        );
        foreach ($it as $key) {
            list($cachedData) = $this->redis->lrange($key, 0, 0);
            if ($comparator->isSimilar(
                $this->$email->getData(),
                $cachedData
            )
            ) {
                return substr($key, strlen(static::KEY_PREFIX .
                    static::EMAIL_PREFIX), -1);
            }
        }

        return FALSE;
    }

    /**
     * @param $id
     * @return StoredEmailGroup | FALSE
     */
    public function retrieve($id)
    {
        $cached = $this->redis->lrange(static::KEY_PREFIX . static::EMAIL_PREFIX . $id, 0, -1);
        $idx = 0;
        $storedEmailGroup = new StoredEmailGroup();
        while ($idx < count($cached)) {
            $ts = $cached[$idx++];
            $data = $cached[$idx++];
            $storedEmailGroup->add(new StoredEmail(
                new Email($data), $ts
            ));
        }

        return ($storedEmailGroup);
    }

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

    protected function isSimilar($entry,
}