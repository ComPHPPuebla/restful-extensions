<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventListener;

use \PHPUnit_Framework_TestCase as TestCase;
use \ComPHPPuebla\Doctrine\TableGateway\Specification\FilterByUsernameSpecification;

class QueryBuilderSpecificationTest extends TestCase
{
    protected $qb;

    protected function setUp()
    {
        $this->qb = $this->getMockBuilder('\Doctrine\DBAL\Query\QueryBuilder')
                         ->disableOriginalConstructor()
                         ->getMock();
    }

    public function testSpecificationMatches()
    {
        $specification = new FilterByUsernameSpecification();
        $specification->setCriteria(['username' => 'montealegreluis']);

        $this->expectsSpecificationAddsAndWhereToQb();
        $this->expectsSpecificationSetsUsernameParamToQb();

        $specification->match($this->qb);
    }

    protected function expectsSpecificationAddsAndWhereToQb()
    {
        $this->qb->expects($this->once())
                 ->method('andWhere')
                 ->with('u.username = :username');
    }

    protected function expectsSpecificationSetsUsernameParamToQb()
    {
        $this->qb->expects($this->once())
                  ->method('setParameter')
                  ->with('username', 'montealegreluis');
    }
}
