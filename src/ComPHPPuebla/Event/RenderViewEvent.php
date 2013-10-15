<?php
namespace ComPHPPuebla\Event;

use \Slim\Http\Response;
use \Slim\Http\Request;
use \Zend\EventManager\Event;
use \Slim\View;

class RenderViewEvent
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
        $request = $event->getParam('request');

        if (400 === $response->getStatus()) {
            $this->renderErrors($resource, $response);

            return;
        }

        if ($resource && $this->responseNeedsBody($request)) {

            $body = $this->renderView($resource, $response);
            $response->setBody($body);
        }
    }

    /**
     * @param Request $request
     * @return boolean
     */
    protected function responseNeedsBody(Request $request)
    {
        return !$request->isHead() && !$request->isOptions() && !$request->isDelete();
    }

    /**
     * @param array $resource
     * @return string
     */
    public function renderView(array $resource, Response $response)
    {
        $viewExtension = $this->getViewExtension($response);
        $this->view->setData(['resource' => $resource]);

        return $this->view->display("resource/show.$viewExtension.twig");
    }

    /**
     * @param array $errors
     */
    public function renderErrors(array $errors, Response $response)
    {
        $viewExtension = $this->getViewExtension($response);
        $this->view->setData(['errors' => ['messages' => $errors]]);

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
