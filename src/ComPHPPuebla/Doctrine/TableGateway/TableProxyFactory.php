<?php
namespace ComPHPPuebla\Doctrine\TableGateway;

use \ProxyManager\Factory\AccessInterceptorValueHolderFactory as Factory;
use \ProxyManager\Proxy\AccessInterceptorInterface;
use \ProxyManager\Configuration;
use \Zend\EventManager\EventManager;

class TableProxyFactory
{
    /**
     * @var AccessInterceptorValueHolderFactory
     */
    protected $factory;

    /**
     * @var PaginatorFactory
     */
    protected $paginatorFactory;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->factory = new Factory($configuration);
    }

    /**
     * @param AccessInterceptorInterface $proxy
     * @param EventManager $eventManager
     */
    public function addEventManagement(AccessInterceptorInterface $proxy, EventManager $eventManager)
    {
        $this->eventManager = $eventManager;

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
        $proxy->setMethodSuffixInterceptor(
            'insert',
            function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
                $this->eventManager->trigger('onSave', $instance, ['row' => $returnValue]);
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
            'findAll',
            function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
                $this->eventManager->trigger(
                    'postFindAll', $instance, ['qb' => $returnValue, 'criteria' => $params['criteria']]
                );
            }
        );
    }

    /**
     * @param AccessInterceptorInterface $proxy
     * @param PaginatorFactory $paginatorFactory
     * @return void
     */
    public function addPagination(AccessInterceptorInterface $proxy, PaginatorFactory $paginatorFactory)
    {
        $proxy->setMethodSuffixInterceptor('findAll', function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) use ($paginatorFactory) {
            $returnEarly = true;

            return $paginatorFactory->createPaginator($returnValue, $params['criteria'], $instance);
        });
    }

    /**
     * @param Table $table
     * @return AccessInterceptorInterface
     */
    public function createProxy(Table $table)
    {
        return $this->factory->createProxy($table);
    }
}
