<?php


namespace rollun\logger\Writer\Filter;


use Zend\Cache\Storage\StorageInterface;
use Zend\Log\Filter\FilterInterface;

class UniqMessageFilter implements FilterInterface
{

    /**
     * @var StorageInterface
     *
     */
    protected $cacheStorage;

    /**
     * UniqMessageFilter constructor.
     * @param StorageInterface $cacheStorage
     */
    public function __construct(StorageInterface $cacheStorage)
    {
        $this->cacheStorage = $cacheStorage;
    }

    /**
     * Returns TRUE to accept the message, FALSE to block it.
     *
     * @param array $event event data
     * @return bool accepted?
     */
    public function filter(array $event)
    {
        $messageId = hash("sha256", $event["message"]);
        $result = !$this->cacheStorage->hasItem($messageId);
        if($result) {
            $this->cacheStorage->setItem($messageId, true);
        }
        return $result;
    }
}