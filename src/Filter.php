<?php
namespace Bulkdozer;

use Bulkdozer\Cache\Cache;
use Bulkdozer\Cache\StoredEmailGroup;
use Bulkdozer\Comparator\EmailComparator;
use Bulkdozer\Mime\MimeParser;
use Bulkdozer\Sender\Sender;
use BulkDozer\Storage\Storage;
use Bulkdozer\TemplateEngine\TemplateEngine;

class Filter
{
    const MAX_GROUP_SIZE = 10;
    const MAX_TIME_IN_CACHE = 600;

    protected $cache;
    protected $sender;
    protected $storage;
    protected $templateEngine;
    protected $textComparator;
    protected $mimeParser;

    public function __construct(
        Cache $cache,
        Sender $sender,
        Storage $storage,
        TemplateEngine $templateEngine,
        EmailComparator $emailComparator,
        MimeParser $mimeParser
    )
    {
        $this->cache = $cache;
        $this->sender = $sender;
        $this->storage = $storage;
        $this->templateEngine = $templateEngine;
        $this->emailComparator = $emailComparator;
        $this->mimeParser = $mimeParser;
    }

    public function filter($mimeEmail)
    {
        $email = $this->mimeParser->parse($mimeEmail);
        if ($id = $this->cache->findSimilar($email, $this->emailComparator)) {
            $this->cache->add($id, $email);
            if ($this->exceedsCacheSize($id)) {
                $this->sendSummarized($id);
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

    protected function sendSummarized($id)
    {
        $group = $this->cache->getGroup($id);
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
        while ($id = $this->cache->getPending(static::MAX_TIME_IN_CACHE)) {
            $this->sendSummarized($id);
        }
    }
}