<?php
namespace ComPHPPuebla\Proxy;

use \PHPUnit_Framework_TestCase as TestCase;
use \ComPHPPuebla\Proxy\CacheProxyFactory;
use \ComPHPPuebla\Doctrine\TableGateway\UserTable;

class CacheProxyFactoryTest extends TestCase
{
    public function testCanCacheResults()
    {
        $connection = $this->getMockBuilder('\Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();

        $table = new UserTable($connection);

        $user = ['username' => 'luis', 'password' => 'changeme'];

        $cache = $this->getMockBuilder('\Doctrine\Common\Cache\ArrayCache')
                      ->setMethods(['contains', 'fetch', 'save'])
                      ->getMock();

        $cache->expects($this->at(0))
              ->method('contains')
              ->with('/users/1')
              ->will($this->returnValue(false));

        $cache->expects($this->at(2))
              ->method('contains')
              ->with('/users/1')
              ->will($this->returnValue(true));

        $cache->expects($this->once())
              ->method('fetch')
              ->with('/users/1')
              ->will($this->returnValue($user));

        $cache->expects($this->once())
              ->method('save')
              ->with('/users/1', $user);

        $cacheProxy = new CacheProxyFactory($cache, '/users/1', __DIR__ . '/../../../cache');

        $userTable = $cacheProxy->createProxy($table, ['find']);

        $this->assertEquals($user, $userTable->find(1));
        $this->assertEquals($user, $userTable->find(1));
    }
}
