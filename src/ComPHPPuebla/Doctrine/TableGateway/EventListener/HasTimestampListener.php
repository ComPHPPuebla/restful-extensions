<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventListener;

use \Zend\EventManager\EventInterface;
use \Zend\EventManager\AbstractListenerAggregate;
use \Zend\EventManager\EventManagerInterface;
use \DateTime;

class HasTimestampListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('preInsert', array($this, 'onInsert'));
        $this->listeners[] = $events->attach('preUpdate', array($this, 'onUpdate'));
    }

    public function onInsert(EventInterface $event)
    {
    	$values = $event->getParam('values');

    	$now = new DateTime();
    	$now = $now->format('Y-m-d h:i:s');

    	$values['created_at'] = $now;
    	$values['last_updated_at'] = $now;

    	$event->setParam('values', $values);
    }

    public function onUpdate(EventInterface $event)
    {
        $values = $event->getParam('values');

        $now = new DateTime();
        $values['last_updated_at'] = $now->format('Y-m-d h:i:s');

        $event->setParam('values', $values);
    }
}
