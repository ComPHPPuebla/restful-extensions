<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventListener;

use \PHPUnit_Framework_TestCase as TestCase;

class QuerySpecificationListenerTest extends TestCase
{
    protected $event;

    protected $specification;

    protected $queryBuilder;

    protected $criteria;

    protected function setUp()
    {
        $this->event = $this->getMockBuilder('\Zend\EventManager\Event')
                            ->setMethods(['getParam'])
                            ->getMock();
        $this->specification = $this->getMockBuilder('\ComPHPPuebla\Doctrine\TableGateway\Specification\QueryBuilderSpecification')
                                    ->setMethods(['setCriteria', 'match'])
                                    ->getMockForAbstractClass();
        $this->queryBuilder = $this->getMockBuilder('\Doctrine\DBAL\Query\QueryBuilder')
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $this->criteria = ['username' => 'montealegreluis'];
    }

    public function testQuerySpcecificationListenerCanBeInvoked()
    {
        $queryListener = new QuerySpecificationListener($this->specification);

        $this->expectsThatListenerCallsSpecificationSetCriteria();
        $this->expectsThatListenerCallsSpecificationMatch();

        $this->expectsThatParamCriteriaIsReturnedByEvent();
        $this->expectsThatParamQbIsReturnedByEvent();

        $queryListener($this->event);
    }

    protected function expectsThatParamCriteriaIsReturnedByEvent()
    {
        $this->event->expects($this->at(0))
                    ->method('getParam')
                    ->with('criteria')
                    ->will($this->returnValue($this->criteria));
    }

    protected function expectsThatParamQbIsReturnedByEvent()
    {
        $this->event->expects($this->at(1))
                    ->method('getParam')
                    ->with('qb')
                    ->will($this->returnValue($this->queryBuilder));
    }

    protected function expectsThatListenerCallsSpecificationSetCriteria()
    {
        $this->specification->expects($this->once())
                            ->method('setCriteria')
                            ->with($this->criteria);
    }

    protected function expectsThatListenerCallsSpecificationMatch()
    {
        $this->specification->expects($this->once())
                            ->method('match')
                            ->with($this->queryBuilder);
    }
}
