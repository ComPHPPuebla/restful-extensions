<?php
namespace ComPHPPuebla\Doctrine\TableGateway;

use \ProxyManager\Factory\AccessInterceptorValueHolderFactory;
use \ProxyManager\Proxy\AccessInterceptorInterface;
use \ProxyManager\Configuration;
use \Zend\EventManager\EventManagerInterface;

class TableProxyFactory
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
     * @param EventManager               $eventManager
     */
    public function addEventManagement(AccessInterceptorInterface $proxy)
    {
        $proxy->setMethodPrefixInterceptor(
            'find',
            function($proxy, $instance, $method, $params, &$returnEarly) {
                $results = $this->eventManager->trigger('preFind', $instance);

                if ($results->stopped()) {
                    $returnEarly = true;

                    return $results->last();
                }
            }
        );
        $proxy->setMethodSuffixInterceptor(
            'find',
            function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
                $this->eventManager->trigger('postFind', $instance, ['row' => $returnValue]);
            }
        );
        $proxy->setMethodPrefixInterceptor(
            'insert',
            function($proxy, $instance, $method, $params, &$returnEarly) {
                $returnEarly = true;
                $this->eventManager->trigger('preInsert', $instance, ['values' => &$params['values']]);

                return $instance->insert($params['values']);
            }
        );
        $proxy->setMethodPrefixInterceptor(
            'update',
            function($proxy, $instance, $method, $params, &$returnEarly) {
                $returnEarly = true;
                $this->eventManager->trigger('preUpdate', $instance, ['values' => &$params['values']]);

                return $instance->update($params['values'], $params['id']);
            }
        );
        $proxy->setMethodSuffixInterceptor(
            'update',
            function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
                $this->eventManager->trigger('onSave', $instance, ['row' => $returnValue]);
            }
        );
        $proxy->setMethodSuffixInterceptor(
            'delete',
            function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
                $this->eventManager->trigger('onDelete', $instance);
            }
        );
        $proxy->setMethodSuffixInterceptor(
            'getQueryFindAll',
            function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
                $this->eventManager->trigger(
                    'postFindAll', $instance, ['qb' => $returnValue, 'criteria' => $params['criteria']]
                );
            }
        );
    }

    /**
     * @param  Table                      $table
     * @return AccessInterceptorInterface
     */
    public function createProxy(Table $table)
    {
        return $this->factory->createProxy($table);
    }
}
