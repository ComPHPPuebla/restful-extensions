<?php
namespace ComPHPPuebla\Slim\Controller;

use \ProxyManager\Factory\AccessInterceptorValueHolderFactory;
use \ProxyManager\Proxy\AccessInterceptorInterface;
use \ProxyManager\Configuration;
use \Zend\EventManager\EventManagerInterface;
use \ComPHPPuebla\Slim\Controller\Exception\ResourceNotFoundException;
use \ComPHPPuebla\Slim\Controller\Exception\BadRequestParameters;

class RestControllerProxyFactory extends SlimController
{
    /**
     * @var AccessInterceptorValueHolderFactory
     */
    protected $factory;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration, EventManagerInterface $eventManager)
    {
        $this->factory = new AccessInterceptorValueHolderFactory($configuration);
        $this->eventManager = $eventManager;
    }

    /**
     * @param AccessInterceptorInterface $proxy
     * @param EventManager $eventManager
     */
    public function addEventManagement(AccessInterceptorInterface $proxy)
    {
        $proxy->setMethodPrefixInterceptor(
            'get',
            function($proxy, $instance, $method, $params, &$returnEarly) {
                $returnEarly = true;

                try {
                    $resource = $instance->get($params['id']);
                    $this->triggerEvent('postDispatch', $instance, $resource);
                } catch(ResourceNotFoundException $e) {
                    $instance->response()->status(404); //Not Found
                }
            }
        );
        $proxy->setMethodPrefixInterceptor(
            'getList',
            function($proxy, $instance, $method, $params, &$returnEarly) {
                $returnEarly = true;

                $resources = $instance->getList();
                $this->triggerEvent('postDispatch', $instance, $resources);
            }
        );
        $proxy->setMethodPrefixInterceptor(
            'post',
            function($proxy, $instance, $method, $params, &$returnEarly) {
                $returnEarly = true;
                try {
                    $resource = $instance->post();
                    $this->triggerEvent('postDispatch', $instance, $resource);

                } catch (BadRequestParameters $e) {
                    $this->handleBadRequest($instance);
                }
            }
        );
        $proxy->setMethodPrefixInterceptor(
            'put',
            function($proxy, $instance, $method, $params, &$returnEarly) {
                $returnEarly = true;
                try {
                    $resource = $instance->put($params['id']);
                    $this->triggerEvent('postDispatch', $instance, $resource);
                } catch(ResourceNotFoundException $e) {
                    $instance->response()->status(404); //Not Found
                } catch (BadRequestParameters $e) {
                    $this->handleBadRequest($instance);
                }
            }
        );
        $proxy->setMethodPrefixInterceptor(
            'delete',
            function($proxy, $instance, $method, $params, &$returnEarly) {
                $returnEarly = true;
                try {
                    $instance->delete($params['id']);
                } catch(ResourceNotFoundException $e) {
                    $instance->response()->status(404); //Not Found
                }
            }
        );
    }

    /**
     * @return void
     */
    protected function handleBadRequest(RestController $controller)
    {
        $controller->response()->setStatus(400); //Bad request
        $this->triggerEvent('renderErrors', $controller, $controller->errors());
    }

    /**
     * @param string $eventName
     * @param RestController $controller
     * @param array|Paginator $resource
     */
    protected function triggerEvent($eventName, RestController $controller, $resource)
    {
        $argv = [
            'resource' => $resource,
            'request' => $controller->request(),
            'response' => $controller->response(),
        ];
        $this->eventManager->trigger($eventName, $controller, $argv);
    }

    /**
     * @param Table $table
     * @return AccessInterceptorInterface
     */
    public function createProxy(RestController $controller)
    {
        return $this->factory->createProxy($controller);
    }
}
