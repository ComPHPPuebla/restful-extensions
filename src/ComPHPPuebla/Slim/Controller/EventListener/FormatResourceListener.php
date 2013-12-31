<?php
namespace ComPHPPuebla\Slim\Controller\EventListener;

use \Zend\EventManager\EventInterface;
use \ComPHPPuebla\Hypermedia\Formatter\Formatter;

class FormatResourceListener
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
     * @param Request    $request
     * @param Response   $response
     */
    public function __invoke(EventInterface $event)
    {
        $resource = $event->getParam('resource');
        $request = $event->getParam('request');

        $formattedResource = $this->formatter->format($resource, $request->params());

        $event->setParam('resource', $formattedResource);
    }
}
