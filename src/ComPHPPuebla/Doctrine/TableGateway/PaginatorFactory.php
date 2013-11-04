<?php
namespace ComPHPPuebla\Doctrine\TableGateway;

use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \Zend\EventManager\Event;
use \Doctrine\DBAL\Query\QueryBuilder;

class PaginatorFactory
{
    /**
     * @var PagerfantaPaginator
     */
    protected $paginator;

    /**
     * @param PagerfantaPaginator $paginator
     */
    public function __construct(PagerfantaPaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $criteria
     * @return PagerfantaPaginator
     */
    public function createPaginator(QueryBuilder $qb, array $criteria, Table $table)
    {
        if (!isset($criteria['page'])) {

            return $table->fetchAll($qb->getSQL());
        }

        $this->paginator->initAdapter($qb, array($table, 'count'));
        $this->setupPaginator($criteria);

        return $this->paginator;
    }

    /**
     * @param array $criteria
     */
    protected function setupPaginator(array $criteria)
    {
        $this->paginator->setCurrentPage($criteria['page']);
        if (isset($criteria['page_size'])) {

            $this->paginator->setMaxPerPage($criteria['page_size']);
        }
    }
}
