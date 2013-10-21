<?php
namespace ComPHPPuebla\Slim\Controller\EventHandler;

use \Slim\Http\Response;
use \Slim\Http\Request;
use \Zend\EventManager\Event;
use \Slim\View;
use \ArrayObject;

class RenderErrorsHandler
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
     * @param Request $request
     * @param Response $response
     */
    public function __invoke(Event $event)
    {
        $resource = $event->getParam('resource');
        $response = $event->getParam('response');

        $this->renderErrors($resource, $response);
    }

    /**
     * @param array $errors
     */
    public function renderErrors(ArrayObject $errors, Response $response)
    {
        $viewExtension = $this->getViewExtension($response);
        $this->view->setData(['errors' => ['messages' => $errors->getArrayCopy()]]);

        return $this->view->display("error/errors.$viewExtension.twig");
    }

    /**
     * @param Response $response
     * @return string
     */
    protected function getViewExtension(Response $response)
    {
        $typeParts = explode('/', $response->headers->get('Content-Type'));

        return array_pop($typeParts);
    }
}
