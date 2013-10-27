<?php
namespace ComPHPPuebla\Proxy;

use \ComPHPPuebla\Doctrine\TableGateway\Table;
use \Doctrine\Common\Cache\CacheProvider;
use \ProxyManager\Factory\AccessInterceptorValueHolderFactory as Factory;
use \ProxyManager\Configuration;

class CacheProxyFactory
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
	 * @param CacheProvider $cache
	 * @param string $cacheId
	 * @param string $proxiesDir
	 */
	public function __construct(CacheProvider $cache, $cacheId, Configuration $configuration)
	{
		$this->cache = $cache;
		$this->cacheId = $cacheId;
		$this->factory = new Factory($configuration);
	}

	/**
	 * @param Table $table
	 * @param array $methods
	 */
	public function createProxy(Table $table, array $methods)
	{
		$proxy = $this->factory->createProxy($table);

		foreach ($methods as $method) {
			$proxy->setMethodPrefixInterceptor($method, function($proxy, $instance, $method, $params, &$returnEarly) {
			    if ($this->cache->contains($this->cacheId)) {
				    $returnEarly = true;

				    return $this->cache->fetch($this->cacheId);
				}
			});
			$proxy->setMethodSuffixInterceptor($method, function($proxy, $instance, $method, $params, $returnValue, &$returnEarly) {
				$this->cache->save($this->cacheId, $returnValue);
			});
		}

		return $proxy;
	}
}
