<?php
namespace ComPHPPuebla\Doctrine\TableGateway\Specification;

use \Doctrine\DBAL\Query\QueryBuilder;

abstract class QueryBuilderSpecification
{
    /**
     * @var array
     */
    protected $criteria;

    /**
     * @param array $criteria
     */
    public function setCriteria(array $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @param  string  $key
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->criteria[$key]);
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->criteria[$key];
    }

    /**
     * Add/Modify conditions in the query builder
     *
     * @param QueryBuilder $qb
     */
    abstract public function match(QueryBuilder $qb);
}
