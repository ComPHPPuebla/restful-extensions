<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventListener;

use \Zend\EventManager\EventInterface;
use \Zend\EventManager\AbstractListenerAggregate;
use \Zend\EventManager\EventManagerInterface;
use \Doctrine\Common\Cache\CacheProvider;

class CacheListener extends AbstractListenerAggregate
{
    /**
     * @var CacheProvider
     */
    protected $cache;

    /**
     * @var string
     */
    protected $cacheId;

    /**
     * @param CacheProvider $cache
     * @param string $cacheId
     */
    public function __construct(CacheProvider $cache, $cacheId)
    {
        $this->cache = $cache;
        $this->cacheId = $cacheId;
    }

    /**
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('preFind', [$this, 'preFind']);
        $this->listeners[] = $events->attach('postFind', [$this, 'postFind']);
        $this->listeners[] = $events->attach('onSave', [$this, 'onSave']);
        $this->listeners[] = $events->attach('onDelete', [$this, 'onDelete']);
    }

    /**
     * @param EventInterface $event
     */
    public function preFind(EventInterface $event)
    {
        if ($this->cache->contains($this->cacheId)) {
            $event->stopPropagation(true);

            return $this->cache->fetch($this->cacheId);
        }
    }

    /**
     * @param EventInterface $event
     */
    public function postFind(EventInterface $event)
    {
        $this->cache->save($this->cacheId, $event->getParam('row'));
    }

    /**
     * @param EventInterface $event
     */
    public function onSave(EventInterface $event)
    {
        $this->cache->save($this->cacheId, $event->getParam('row'));
    }

    /**
     * @param EventInterface $event
     */
    public function onDelete(EventInterface $event)
    {
        $this->cache->delete($this->cacheId);
    }
}
