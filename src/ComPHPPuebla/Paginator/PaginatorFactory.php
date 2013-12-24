<?php
namespace ComPHPPuebla\Paginator;

use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ComPHPPuebla\Doctrine\TableGateway\Table;
use \Doctrine\DBAL\Query\QueryBuilder;
use \Pagerfanta\Adapter\AdapterInterface;
use \Pagerfanta\Adapter\DoctrineDbalAdapter;

class PaginatorFactory
{
    /**
     * @var PagerfantaPaginator
     */
    protected $paginator;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param PagerfantaPaginator $paginator
     */
    public function __construct(PagerfantaPaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param  QueryBuilder        $qb
     * @param  array               $criteria
     * @return PagerfantaPaginator
     */
    public function createPaginator(array $criteria, Table $table)
    {
        $adapter = $this->getAdapter($table->getQueryFindAll($criteria), $table, $criteria);

        $this->paginator->init($adapter);
        $this->setupPaginator($criteria);

        return $this->paginator;
    }

    /**
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param  QueryBuilder     $queryBuilder
     * @param  Table            $table
     * @param  array            $criteria
     * @return AdapterInterface
     */
    protected function getAdapter(QueryBuilder $queryBuilder, Table $table, array $criteria)
    {
        if (!$this->adapter) {
            $this->adapter = new DoctrineDbalAdapter(
                $queryBuilder,
                function($queryBuilder) use ($table, $criteria) {
                    return $table->getQueryCount($criteria);
                }
            );
        }

        return $this->adapter;
    }

    /**
     * @param array $criteria
     */
    protected function setupPaginator(array $criteria)
    {
        if (isset($criteria['page_size'])) {
            $this->paginator->setMaxPerPage($criteria['page_size']);
        }

        $page = isset($criteria['page']) ? $criteria['page'] : 1;

        if ($page > $this->paginator->getNbPages()) {
            throw new PageOutOfRangeException("Page $page does not exists.");
        }

        $this->paginator->setCurrentPage($page);
    }
}
