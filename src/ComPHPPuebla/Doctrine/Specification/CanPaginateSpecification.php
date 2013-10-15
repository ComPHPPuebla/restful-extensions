<?php
namespace ComPHPPuebla\Doctrine\Specification;

use \Doctrine\DBAL\Query\QueryBuilder;

class CanPaginateSpecification extends QueryBuilderSpecification
{
    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $pageSize;

    /**
     * @param int $defaultPageSize
     */
    public function __construct($defaultPageSize)
    {
        $this->pageSize = $defaultPageSize;
    }

    /**
     * @return int
     */
    public function calculateOffset()
    {
        return $this->pageSize * ($this->page - 1);
    }

    /**
     * @see \ComPHPPuebla\Doctrine\Query\Specification::match()
     */
    public function match(QueryBuilder $qb)
    {
        if ($this->has('page')) {

            $this->page = $this->get('page');
            $this->initPageSize();
            $qb->setFirstResult($this->calculateOffset())->setMaxResults($this->pageSize);
        }
    }

    /**
     * @return void
     */
    protected function initPageSize()
    {
        if ($this->has('page_size')) {

            $this->pageSize = $this->get('page_size');
        }
    }
}
