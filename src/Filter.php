<?php
namespace Bulkdozer;

use Bulkdozer\Cache\CacheId;
use Bulkdozer\Cache\CacheInterface;
use Bulkdozer\Sender\SenderInterface;

class Filter
{
    const MAX_GROUP_SIZE = 10;
    protected $cache;
    protected $sender;

    public function __construct(
        CacheInterface $cache,
        SenderInterface $sender
    )
    {
        $this->cache = $cache;
        $this->sender = $sender;
    }

    public function filter(Email $email)
    {
        if ($id = $this->cache->search($email)) {
            $this->cache->add($id, $email);
            if ($this->exceedsCacheSize($id)) {
                $group = $this->cache->retrieve($id);
                $this->sender->send($group->getBulk());
                $this->cache->remove($id);
            }
        } else {
            $this->cache->create($email);
            $this->sender->send($email);
        }
    }

    protected function exceedsCacheSize(CacheId $id)
    {
        return ($this->cache->getSize($id) > static::MAX_GROUP_SIZE);
    }

    public function check()
    {
        while ($id = $this->cache->getPending()) {
            $group = $this->cache->retrieve($id);
            $this->cache->remove($id);
            $this->sender->send($group->getBulk());
        }
    }
}