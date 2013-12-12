<?php
namespace ComPHPPuebla\Slim\Controller\EventListener;

use \Slim\Http\Response;
use \Slim\Http\Request;
use \Zend\EventManager\EventInterface;
use \Slim\View;
use \ArrayObject;

class RenderErrorsListener
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
    public function __invoke(EventInterface $event)
    {
        $resource = $event->getParam('resource');
        $response = $event->getParam('response');

        $this->renderErrors($resource, $response);
    }

    /**
     * @param array $errors
     */
    public function renderErrors(array $errors, Response $response)
    {
        $viewFormat = $this->getViewFormat$response);
        $this->view->setData(['errors' => ['messages' => $errors]]);

        return $this->view->display("error/errors.$viewFormat.twig");
    }

    /**
     * @param Response $response
     * @return string
     */
    protected function getViewFormat(Response $response)
    {
        $typeParts = explode('/', $response->headers->get('Content-Type'));

        return array_pop($typeParts);
    }
}
