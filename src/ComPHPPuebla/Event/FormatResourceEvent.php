<?php
namespace ComPHPPuebla\Event;

use \Zend\EventManager\Event;
use \ComPHPPuebla\Hypermedia\Formatter\HAL\Formatter;

class FormatResourceEvent
{
    /**
     * @var Formatter
     */
    protected $formatter;

    /**
     * @param View $view
     */
    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @param array|null $resource
     * @param Request $request
     * @param Response $response
     */
    public function __invoke(Event $event)
    {
        if (400 === $event->getParam('response')->getStatus()) {

            return; //A validation error occured
        }

        $resource = $event->getParam('resource');
        if (empty($resource)) {

            return;
        }

        $event->setParam(
            'resource', $this->formatter->format($resource, $event->getParam('request')->params())
        );
    }
}
