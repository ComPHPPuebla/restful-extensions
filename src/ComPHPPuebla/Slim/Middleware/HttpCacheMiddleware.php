<?php
namespace ComPHPPuebla\Slim\Middleware;

use \Doctrine\Common\Cache\CacheProvider;
use \Slim\Middleware;

class HttpCacheMiddleware extends Middleware
{
    /**
     * @var CacheProvider
     */
    protected $cache;

    /**
     * @param CacheProvider $cache
     */
    public function __construct(CacheProvider $cache)
    {
        $this->cache = $cache;
    }

    public function call()
    {
        if (!in_array($this->app->request()->getMethod(), ['GET', 'HEAD'])) {

            return $this->next->call();
        }

        $cacheKey = $this->app->request()->getPathInfo();

        if ($this->cache->contains($cacheKey)) {
            $resource = $this->cache->fetch($cacheKey);
            $this->app->lastModified($resource['last_updated_at']);
        }

        $this->next->call();
    }
}
