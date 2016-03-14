<?php
namespace Bulkdozer;

use Bulkdozer\Cache\Cache;
use Bulkdozer\Cache\StoredEmailGroup;
use Bulkdozer\Sender\Sender;
use BulkDozer\Storage\Storage;
use Bulkdozer\TemplateEngine\TemplateEngine;

class Filter
{
    const MAX_GROUP_SIZE = 10;
    protected $cache;
    protected $sender;
    protected $storage;
    protected $templateEngine;

    public function __construct(
        Cache $cache,
        Sender $sender,
        Storage $storage,
        TemplateEngine $templateEngine
    )
    {
        $this->cache = $cache;
        $this->sender = $sender;
        $this->storage = $storage;
        $this->templateEngine = $templateEngine;
    }

    public function filter(Email $email)
    {
        if ($id = $this->cache->search($email)) {
            $this->cache->add($id, $email);
            if ($this->exceedsCacheSize($id)) {
                $group = $this->cache->retrieve($id);
                $storageId = $this->storage->store($group);
                $link = $this->storage->getShortcut($storageId);
                $this->sendGroupSummaryEmail($group, $link);
                $this->cache->remove($id);
            }
        } else {
            $this->cache->create($email);
            $this->sender->send($email);
        }
    }

    protected function exceedsCacheSize($id)
    {
        return ($this->cache->getSize($id) > static::MAX_GROUP_SIZE);
    }

    protected function processCachedEmailGroup($id)
    {
        $group = $this->cache->retrieve($id);
        $storageId = $this->storage->store($group);
        $link = $this->storage->getShortcut($storageId);
        $this->sendGroupSummaryEmail($group, $link);
        $this->cache->remove($id);
    }

    protected function sendGroupSummaryEmail(StoredEmailGroup $group, $storageLink)
    {
        $email = $this->templateEngine->render(
            'mailable-template.twig',
            array(
                'emailCount' => $group->getCount(),
                'storageLink' => $storageLink,
                'lastEmail' => $group->getLast()
            )
        );
        $this->sender->send($email);
    }

    public function check()
    {
        while ($id = $this->cache->getPending()) {
            $this->processCachedEmailGroup($id);
        }
    }
}