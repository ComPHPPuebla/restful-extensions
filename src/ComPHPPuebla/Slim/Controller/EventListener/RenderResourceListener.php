<?php
namespace ComPHPPuebla\Slim\Controller\EventListener;

use \Slim\Http\Response;
use \Slim\Http\Request;
use \Zend\EventManager\Event;
use \Slim\View;

class RenderResourceListener
{
    /**
     * @var View
     */
    protected $view;

    /**
     * @param View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param array|null $resource
     * @param Request    $request
     * @param Response   $response
     */
    public function __invoke(Event $event)
    {
        $resource = $event->getParam('resource');
        $response = $event->getParam('response');

        $body = $this->renderView($resource, $response);
        $response->setBody($body);
    }

    /**
     * @param  array  $resource
     * @return string
     */
    public function renderView(array $resource, Response $response)
    {
        $viewFormat = $this->getViewFormat($response);
        $this->view->setData(['resource' => $resource]);

        return $this->view->display("resource/show.$viewFormat.twig");
    }

    /**
     * @param  Response $response
     * @return string
     */
    protected function getViewFormat(Response $response)
    {
        $typeParts = explode('/', $response->headers->get('Content-Type'));

        return array_pop($typeParts);
    }
}
