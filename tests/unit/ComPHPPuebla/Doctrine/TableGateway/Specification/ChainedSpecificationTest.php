<?php
namespace ComPHPPuebla\Doctrine\TableGateway\Specification;

use \PHPUnit_Framework_TestCase as TestCase;

class ChainedSpecificationTest extends TestCase
{
    /**
     * @var ComPHPPuebla\Doctrine\TableGateway\Specification\Specification
     */
    protected $spec;

    /**
     * @var array
     */
    protected $criteria;

    protected function setUp()
    {
        $this->spec = $this->getMockBuilder('\ComPHPPuebla\Doctrine\TableGateway\Specification\QueryBuilderSpecification')
                           ->setMethods(['match', 'setCriteria'])
                           ->getMockForAbstractClass();
        $this->queryBuilder = $this->getMockBuilder('\Doctrine\DBAL\Query\QueryBuilder')
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $this->criteria = ['username' => 'montealegreluis'];
    }

    public function testChainedSpecificationMatches()
    {
        $specification = new ChainedSpecification();
        $specification->setCriteria($this->criteria);
        $specification->addSpecification($this->spec);

        $this->expectsSpecificationCriteriaIsSet();
        $this->expectsSpecificationMatchIsCalled();

        $specification->match($this->queryBuilder);
    }

    protected function expectsSpecificationCriteriaIsSet()
    {
        $this->spec->expects($this->once())
                   ->method('setCriteria')
                   ->with($this->criteria);
    }

    protected function expectsSpecificationMatchIsCalled()
    {
        $this->spec->expects($this->once())
                   ->method('match')
                   ->with($this->queryBuilder);
    }
}
