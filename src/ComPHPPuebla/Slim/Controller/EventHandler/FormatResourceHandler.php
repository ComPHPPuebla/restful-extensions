<?php
namespace ComPHPPuebla\Controller\EventHandler;

use \Zend\EventManager\Event;
use \ComPHPPuebla\Hypermedia\Formatter\HAL\Formatter;

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
        $resource = $event->getParam('resource');

        $event->setParam(
            'resource', $this->formatter->format($resource, $event->getParam('request')->params())
        );
    }
}
