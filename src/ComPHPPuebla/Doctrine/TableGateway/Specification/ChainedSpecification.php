<?php
namespace ComPHPPuebla\Doctrine\TableGateway\Specification;

use Doctrine\DBAL\Query\QueryBuilder;

class ChainedSpecification extends QueryBuilderSpecification
{
    /**
     * @var array
     */
    protected $specifications;

    /**
     * Initialize the specifications array
     */
    public function __construct()
    {
        $this->specifications = [];
    }

    /**
     * @param QueryBuilderSpecification $specification
     */
    public function addSpecification(QueryBuilderSpecification $specification)
    {
        $this->specifications[] = $specification;
    }

    /**
     * @param QueryBuilder $qb
     */
    public function match(QueryBuilder $qb)
    {
        foreach ($this->specifications as $specification) {

            $specification->setCriteria($this->criteria);
            $specification->match($qb);
        }
    }
}
