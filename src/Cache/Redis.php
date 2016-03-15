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
    const SIZE_PREFIX = "size:";
    const LAST_UPDATE_TIME = "lut:";

    public function __construct()
    {
    }

    public function search(Email $email, TextComparator $comparator)
    {
        $it = new Keyspace($this->redis, $this->getEmailKeyPrefix() . '*');
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
        $cached = $this->redis->lrange($this->getEmailKey($id), 0, -1);
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
    public function add($id, Email $email)
    {
        $ts = time();
        $this->redis->rpush($this->getEmailKey($id),
            array($ts, $email->getData())
        );
        $this->redis->incrby($this->getSizeKey($id),
            strlen($email->getData())
        );
        $this->redis->set($this->getLastUpdateTimeKey($id), $ts);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getSize($id)
    {
        return ($this->redis->get($this->getEmailKey($id)));
    }

    /**
     * @param Email $email
     * @return mixed
     */
    public function create(Email $email)
    {
        $key = $this->getNewKey();
        $this->add($key, $email);
    }

    protected function getNewKey()
    {
        return (uniqid('', TRUE));
    }

    protected function getKeyPrefix()
    {
        return (static::KEY_PREFIX);
    }

    protected function getEmailKeyPrefix()
    {
        return ($this->getKeyPrefix() . static::EMAIL_PREFIX);
    }

    protected function getEmailKey($key)
    {
        return ($this->getEmailKeyPrefix() . $key);
    }

    protected function stripEmailPrefixFromKey($key)
    {
        return (substr($key, strlen($this->getEmailKeyPrefix()), -1));
    }

    protected function getSizeKey($key)
    {
        return ($this->getKeyPrefix() . static::SIZE_PREFIX . $key);
    }

    protected function getLastUpdateTimePrefix()
    {
        return ($this->getKeyPrefix() . static::LAST_UPDATE_TIME);
    }

    protected function getLastUpdateTimeKey($key)
    {
        return ($this->getLastUpdateTimePrefix() . $key);
    }

    protected function stripLastUpdateTimePrefixFromKey($key)
    {
        return (substr($key, strlen($this->getLastUpdateTimePrefix()), -1));
    }

    /**
     * @return $id | FALSE
     */
    public function getPending($seconds)
    {
        $it = new Keyspace($this->redis, $this->getLastUpdateTimePrefix() . '*');
        foreach ($it as $key) {
            $ts = $this->redis->get($key);
            $lapse = time() - $ts;
            if ($lapse >= $seconds) {
                return ($this->stripLastUpdateTimePrefixFromKey($key));
            }
        }

        return (FALSE);
    }

    public function remove($id)
    {
        $this->redis->del(array($this->getEmailKey($id)));
    }
}