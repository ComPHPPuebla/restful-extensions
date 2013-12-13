<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventListener;

use \PHPUnit_Framework_TestCase as TestCase;

class CacheListenerTest extends TestCase
{
    protected $cache;

    protected $event;

    protected $user;

	protected function setUp()
	{
	    $this->cache = $this->getMockBuilder('\Doctrine\Common\Cache\ArrayCache')
                            ->setMethods(['contains', 'fetch', 'save', 'delete'])
                            ->getMock();
	    $this->event = $this->getMockBuilder('\Zend\EventManager\EventInterface')->getMock();
	    $this->user = ['username' => 'luis', 'password' => 'changeme'];
	}

	public function testValueIsNotCachedIfKeyDoesNotEndWithANumber()
	{
	    $cacheListener = new CacheListener($this->cache, '/users');
	    $this->assertNull($cacheListener->preFind($this->event));
	}

	public function testValueIsNotCachedInitially()
	{
	    $cacheListener = new CacheListener($this->cache, '/users/1');
        $this->expectsThatCacheIsInitiallyEmpty();
	    $this->assertNull($cacheListener->preFind($this->event));
	}

	public function testValueIsFetchedFromCache()
	{
	    $cacheListener = new CacheListener($this->cache, '/users/1');
	    $this->expectsThatCacheContainsAGivenValue();
	    $this->expectsThatEventPropagationIsStopped();
	    $this->expectsThatCacheCanFetchSavedValue();
	    $this->assertEquals($this->user, $cacheListener->preFind($this->event));
	}

	public function testNoValueIsReturnedIfItIsNotCached()
	{
	    $cacheListener = new CacheListener($this->cache, '/users/1');
	    $this->expectsThatCacheDoesNotContainAGivenValue();
	    $this->assertNull($cacheListener->preFind($this->event));
	}

	public function testOnlyNonNullValuesAreCached()
	{
	    $cacheListener = new CacheListener($this->cache, '/users/1');
	    $this->expectsThatEventRowParamIsNull();
	    $this->assertNull($cacheListener->postFind($this->event));
	}

	public function testValuesCanBeCached()
	{
	    $cacheListener = new CacheListener($this->cache, '/users/1');
	    $this->expectsThatEventRowParamIsNotNull(2);
	    $this->assertNull($cacheListener->postFind($this->event));
	}

	public function testValuesCanBeCachedOnSave()
	{
	    $cacheListener = new CacheListener($this->cache, '/users/1');
	    $this->expectsThatEventRowParamIsNotNull(1);
	    $this->expectsThatCacheSavesValue();
	    $this->assertNull($cacheListener->onSave($this->event));
	}

	public function testValuesCanBeDeletedFromCache()
	{
	    $cacheListener = new CacheListener($this->cache, '/users/1');
	    $this->expectsThatValuesIsDeletedFromCache();
	    $this->assertNull($cacheListener->onDelete($this->event));
	}

	protected function expectsThatCacheIsInitiallyEmpty()
	{
	    $this->cache->expects($this->once())
                    ->method('contains')
                    ->with('/users/1')
                    ->will($this->returnValue(false));
	}

	protected function expectsThatCacheContainsAGivenValue()
	{
	    $this->cache->expects($this->once())
                    ->method('contains')
                    ->with('/users/1')
                    ->will($this->returnValue(true));
	}

	protected function expectsThatCacheDoesNotContainAGivenValue()
	{
	    $this->cache->expects($this->once())
                    ->method('contains')
                    ->with('/users/1')
                    ->will($this->returnValue(false));
	}

	protected function expectsThatEventPropagationIsStopped()
	{
		$this->event->expects($this->once())
		            ->method('stopPropagation')
		            ->with(true);
	}

	protected function expectsThatCacheCanFetchSavedValue()
	{
	    $this->cache->expects($this->once())
                    ->method('fetch')
                    ->with('/users/1')
                    ->will($this->returnValue($this->user));
	}

	protected function expectsThatEventRowParamIsNull()
	{
		$this->event->expects($this->once())
		            ->method('getParam')
		            ->with('row')
		            ->will($this->returnValue(null));
	}

	protected function expectsThatEventRowParamIsNotNull($timesCalled)
	{
	    $this->event->expects($this->exactly($timesCalled))
                    ->method('getParam')
                    ->with('row')
                    ->will($this->returnValue($this->user));
	}

	protected function expectsThatCacheSavesValue()
	{
	    $this->cache->expects($this->once())
                    ->method('save')
                    ->with('/users/1', $this->user)
                    ->will($this->returnValue(true));
	}

	protected function expectsThatValuesIsDeletedFromCache()
	{
		$this->cache->expects($this->once())
		            ->method('delete')
		            ->with('/users/1');
	}
}
