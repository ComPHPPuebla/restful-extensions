<?php
namespace ComPHPPuebla\Paginator;

use \PHPUnit_Framework_TestCase as TestCase;
use \Pagerfanta\Adapter\FixedAdapter;

class PagerfantaPaginatorTest extends TestCase
{
    public function testCanPaginate()
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
                ->will($this->returnValue(4));

        $adapter->expects($this->once())
                ->method('getSlice')
                ->will($this->returnValue($expectedUsers));

        $paginator->init($adapter);
        $paginator->setCurrentPage(1);

        $this->assertEquals(2, $paginator->getNbPages());
        $this->assertEquals($expectedUsers, $paginator->getCurrentPageResults());
    }
}
