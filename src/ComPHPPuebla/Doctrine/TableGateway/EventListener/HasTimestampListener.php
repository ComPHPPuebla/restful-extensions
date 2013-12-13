<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventListener;

use \Zend\EventManager\EventInterface;
use \Zend\EventManager\AbstractListenerAggregate;
use \Zend\EventManager\EventManagerInterface;
use \DateTime;

class HasTimestampListener extends AbstractListenerAggregate
{
    /**
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('preInsert', array($this, 'onInsert'));
        $this->listeners[] = $events->attach('preUpdate', array($this, 'onUpdate'));
    }

    /**
     * Add current `DateTime` value for fields `created_at` and `last_updated_at`
     *
     * @param EventInterface $event
     */
    public function onInsert(EventInterface $event)
    {
        $values = $event->getParam('values');

        $now = new DateTime();
        $now = $now->format('Y-m-d h:i:s');

        $values['created_at'] = $now;
        $values['last_updated_at'] = $now;

        $event->setParam('values', $values);
    }

    /**
     * Add current `DateTime` value for field `last_updated_at`
     *
     * @param EventInterface $event
     */
    public function onUpdate(EventInterface $event)
    {
        $values = $event->getParam('values');

        $now = new DateTime();
        $values['last_updated_at'] = $now->format('Y-m-d h:i:s');

        $event->setParam('values', $values);
    }
}
