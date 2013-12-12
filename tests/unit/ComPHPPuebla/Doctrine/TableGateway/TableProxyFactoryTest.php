<?php
namespace ComPHPPuebla\Doctrine\TableGateway;

use \ProxyManager\Configuration;
use \PHPUnit_Framework_TestCase as TestCase;
use \ComPHPPuebla\Doctrine\TableGateway\UserTable;
use \Zend\EventManager\EventManager;
use \ComPHPPuebla\Doctrine\TableGateway\EventListener\CacheListener;

class TableProxyFactoryTest extends TestCase
{
    /**
     * @var ArrayCache
     */
    protected $cache;

    /**
     * @var array
     */
    protected $user;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * Initialize mocked dependencies
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->cache = $this->getMockBuilder('\Doctrine\Common\Cache\ArrayCache')
                            ->setMethods(['contains', 'fetch', 'save'])
                            ->getMock();
        $this->user = ['username' => 'luis', 'password' => 'changeme'];
        $this->connection = $this->getMockBuilder('\Doctrine\DBAL\Connection')
                                 ->disableOriginalConstructor()
                                ->getMock();
    }

    public function testCanCacheResults()
    {
        $this->expectsThatCacheIsInitiallyEmpty();
        $this->expectsThatCacheContainsKey('/users/1');
        $this->expectsThatCacheCanFetchValueWithKey('/users/1');
        $this->expectsThatCacheCanSaveItemWithKey('/users/1');

        $config = new Configuration();
        $config->setProxiesTargetDir(__DIR__ . '/../../../../cache');
        spl_autoload_register($config->getProxyAutoloader());

        $factory = new TableProxyFactory($config);

        $userTable = $factory->createProxy(new UserTable('user', $this->connection));
        $eventManager = new EventManager();
        $eventManager->attachAggregate(new CacheListener($this->cache, '/users/1'));
        $factory->addEventManagement($userTable, $eventManager);

        $this->assertEquals($this->user, $userTable->find(1));
        $this->assertEquals($this->user, $userTable->find(1));
    }

    protected function expectsThatCacheIsInitiallyEmpty()
    {
        $this->cache->expects($this->at(0))
                    ->method('contains')
                    ->with('/users/1')
                    ->will($this->returnValue(false));
    }

    protected function expectsThatCacheContainsKey($key)
    {
        $this->cache->expects($this->at(2))
                    ->method('contains')
                    ->with($key)
                    ->will($this->returnValue(true));
    }

    protected function expectsThatCacheCanFetchValueWithKey($key)
    {
        $this->cache->expects($this->once())
                    ->method('fetch')
                    ->with()
                    ->will($this->returnValue($this->user));
    }

    protected function expectsThatCacheCanSaveItemWithKey($key)
    {
        $this->cache->expects($this->once())
                    ->method('save')
                    ->with($key, $this->user);
    }
}
