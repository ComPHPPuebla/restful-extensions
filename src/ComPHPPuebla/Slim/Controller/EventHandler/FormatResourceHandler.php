<?php
namespace ComPHPPuebla\Slim\Controller\EventHandler;

use \Zend\EventManager\Event;
use \ComPHPPuebla\Hypermedia\Formatter\HAL\Formatter;
use \ArrayObject;

class FormatResourceHandler
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
        $resource = new ArrayObject($event->getParam('resource'));
        $request = $event->getParam('request');

        $formattedResource = $this->formatter->format($resource, $request->params());

        $event->setParam('resource', $formattedResource);
    }
}
