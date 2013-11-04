<?php
namespace ComPHPPuebla\Doctrine\TableGateway;

use \Doctrine\Common\Cache\CacheProvider;
use \ProxyManager\Factory\AccessInterceptorValueHolderFactory as Factory;
use \ProxyManager\Proxy\AccessInterceptorInterface;
use \ProxyManager\Configuration;

class TableProxyFactory
{
    /**
     * @var CacheProvider
     */
    protected $cache;

    /**
     * @var string
     */
    protected $cacheId;

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
     * @param AccessInterceptorInterface $proxy
     * @param CacheProvider $cache
     * @param string $cacheId
     * @return void
     */
    public function addCaching(AccessInterceptorInterface $proxy, CacheProvider $cache, $cacheId)
    {
        $this->cache = $cache;
        $this->cacheId = $cacheId;

        $proxy->setMethodPrefixInterceptor('find', function($proxy, $instance, $method, $params, &$returnEarly) {
            if ($this->cache->contains($this->cacheId)) {
                $returnEarly = true;

                return $this->cache->fetch($this->cacheId);
            }
        });
        $proxy->setMethodSuffixInterceptor('find', function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
            $this->cache->save($this->cacheId, $returnValue);
        });

        $proxy->setMethodSuffixInterceptor('update', function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
            $this->cache->save($this->cacheId, $returnValue);
        });

        $proxy->setMethodSuffixInterceptor('delete', function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
            $this->cache->delete($this->cacheId);
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
