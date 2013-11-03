<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventListener;

use \Doctrine\DBAL\Driver\Statement;
use \Zend\EventManager\EventManager;
use \PHPUnit_Framework_TestCase as TestCase;
use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ArrayIterator;

abstract class MockStatement extends ArrayIterator implements Statement {}

class PaginationListenerTest extends TestCase
{
    public function testCanCreatePaginator()
    {
        $paginator = new PagerfantaPaginator(2);

        $userTable = $this->getMockBuilder('\ComPHPPuebla\Doctrine\TableGateway\UserTable')
                          ->disableOriginalConstructor()
                          ->getMock();

        $qb = $this->getMockBuilder('\Doctrine\DBAL\Query\QueryBuilder')
                   ->disableOriginalConstructor()
                   ->setMethods(['execute'])
                   ->getMock();

        $statement = $this->getMockBuilder('ComPHPPuebla\Doctrine\TableGateway\EventListener\MockStatement')
                          ->setMethods(['fetchAll'])
                          ->getMockForAbstractClass();

        $expectedUsers = [
            ['username' => 'luis', 'password' => 'changeme'],
            ['username' => 'misraim', 'password' => 'letmein'],
        ];

        $statement->expects($this->once())
                  ->method('fetchAll')
                  ->will($this->returnValue($expectedUsers));

        $statement->expects($this->once())
                  ->method('fetchColumn')
                  ->will($this->returnValue(10));

        $qb->expects($this->exactly(2))
           ->method('execute')
           ->will($this->returnValue($statement));

        $paginationListener = new PaginationListener($paginator);

        $eventManager = new EventManager();
        $eventManager->attach('paginateQuery', $paginationListener);

        $criteria = ['page' => 1];

        $result = $eventManager->trigger('paginateQuery', $userTable, [
            'qb' => $qb, 'criteria' => $criteria,
        ]);

        $paginator = $result->first();

        $this->assertInstanceOf('\ComPHPPuebla\Paginator\PagerfantaPaginator', $paginator);
        $this->assertEquals($expectedUsers, $paginator->getCurrentPageResults());
        $this->assertEquals(5, $paginator->getNbPages());
        $this->assertTrue($paginator->hasNextPage());
    }
}
