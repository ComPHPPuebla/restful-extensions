<?php
namespace ComPHPPuebla\Paginator;

use \PHPUnit_Framework_TestCase as TestCase;

class PaginatorFactoryTest extends TestCase
{
    public function testCanCreatePaginator()
    {
        $paginator = new PagerfantaPaginator(2);

        $expectedUsers = [
            ['username' => 'luis', 'password' => 'changeme'],
            ['username' => 'misraim', 'password' => 'letmein'],
        ];

        $adapter = $this->getMockBuilder('\Pagerfanta\Adapter\FixedAdapter')
                        ->disableOriginalConstructor()
                        ->getMock();

        $adapter->expects($this->once())
                ->method('getNbResults')
                ->will($this->returnValue(10));

        $adapter->expects($this->once())
                ->method('getSlice')
                ->will($this->returnValue($expectedUsers));

        $userTable = $this->getMockBuilder('\ComPHPPuebla\Doctrine\TableGateway\UserTable')
                          ->disableOriginalConstructor()
                          ->getMock();

        $paginatorFactory = new PaginatorFactory($paginator);
        $paginatorFactory->setAdapter($adapter);

        $criteria = ['page' => 1];

        $paginator = $paginatorFactory->createPaginator($criteria, $userTable);

        $this->assertInstanceOf('\ComPHPPuebla\Paginator\PagerfantaPaginator', $paginator);
        $this->assertEquals($expectedUsers, $paginator->getCurrentPageResults());
        $this->assertEquals(5, $paginator->getNbPages());
        $this->assertTrue($paginator->hasNextPage());
    }
}
