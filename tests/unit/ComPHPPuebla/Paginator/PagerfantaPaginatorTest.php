<?php
namespace ComPHPPuebla\Paginator;

use \ArrayIterator;
use \Doctrine\DBAL\Driver\Statement;
use \Doctrine\DBAL\Query\QueryBuilder;
use \PHPUnit_Framework_TestCase as TestCase;

abstract class MockStatement extends ArrayIterator implements Statement {}

class PagerfantaPaginatorTest extends TestCase
{
    protected $qb;

    protected $statement;

    public function setUp()
    {
        $connection = $this->getMockBuilder('\Doctrine\DBAL\Connection')
                           ->setMethods(['connect', 'executeQuery', 'getDatabasePlatform'])
                           ->disableOriginalConstructor()
                           ->getMock();

        $qb = $this->getMockBuilder('\Doctrine\DBAL\Query\QueryBuilder')
                   ->setMethods(['execute'])
                   ->setConstructorArgs([$connection])->getMock();

        $statement = $this->getMockBuilder('\ComPHPPuebla\Paginator\MockStatement')
                          ->disableOriginalConstructor()
                          ->setMethods(['fetchAll', 'fetchColumn'])
                          ->getMockForAbstractClass();

        $qb->expects($this->exactly(2))
           ->method('execute')
           ->will($this->returnValue($statement));

        $this->qb = $qb;
        $this->statement = $statement;
    }

    public function testCanPaginate()
    {
        $paginator = new PagerfantaPaginator(2);

        $expectedUsers = [
            ['username' => 'luis', 'password' => 'changeme'],
            ['username' => 'misraim', 'password' => 'letmein'],
        ];

        $this->statement->expects($this->once())
                        ->method('fetchAll')
                        ->will($this->returnValue($expectedUsers));

        $this->statement->expects($this->once())
                        ->method('fetchColumn')
                        ->will($this->returnValue(4));

        $this->qb->select('*')->from('user', 'u');
        $countModifier = function(QueryBuilder $qb) {
            $qb->select('u.user_id')->setMaxResults(1);
        };

        $paginator->initAdapter($this->qb, $countModifier);
        $paginator->setCurrentPage(1);

        $this->assertEquals(2, $paginator->getNbPages());
        $this->assertEquals($expectedUsers, $paginator->getCurrentPageResults());
    }
}