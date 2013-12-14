<?php
namespace ComPHPPuebla\Doctrine\TableGateway;

use \ProxyManager\Configuration;
use \PHPUnit_Framework_TestCase as TestCase;
use \ComPHPPuebla\Doctrine\TableGateway\UserTable;

class TableProxyFactoryTest extends TestCase
{
    /**
     * @var \Zend\EventManager\EventManager
     */
    protected $manager;

    /**
     * @var \ComPHPPuebla\Doctrine\TableGateway\UserTable
     */
    protected $table;

    /**
     * @var \ProxyManager\Proxy\AccessInterceptorInterface
     */
    protected $proxy;

    /**
     * Initialize mocked dependencies
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->manager = $this->getMockBuilder('\Zend\EventManager\EventManager')->getMock();
        $connection = $this->getMockBuilder('\Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();
        $this->table = new UserTable('user', $connection);
        $this->proxy = $this->getMockBuilder('\ProxyManager\Proxy\AccessInterceptorInterface')
                            ->disableOriginalConstructor()
                            ->getMock(['setMethodPrefixInterceptor', 'setMethodSuffixInterceptor']);
    }

    public function testProxyIsInitializedCorrectly()
    {
        $config = new Configuration();
        spl_autoload_register($config->getProxyAutoloader());

        $factory = new TableProxyFactory($config, $this->manager);

        $this->assertInstanceOf(
            '\ProxyManager\Proxy\AccessInterceptorInterface',
            $factory->createProxy($this->table)
        );

        $this->expectsThatProxyInitializes3PrefixInterceptors();
        $this->expectsThatProxyInitializes4SuffixInterceptors();

        $factory->addEventManagement($this->proxy);
    }

    protected function expectsThatProxyInitializes3PrefixInterceptors()
    {
        $this->proxy->expects($this->exactly(3))
                    ->method('setMethodPrefixInterceptor');
    }

    protected function expectsThatProxyInitializes4SuffixInterceptors()
    {
        $this->proxy->expects($this->exactly(4))
                    ->method('setMethodSuffixInterceptor');
    }
}
