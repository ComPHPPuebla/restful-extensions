<?php
namespace ComPHPPuebla\Paginator;

use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ComPHPPuebla\Doctrine\TableGateway\Table;
use \Doctrine\DBAL\Query\QueryBuilder;
use \Pagerfanta\Adapter\AdapterInterface;
use \Pagerfanta\Adapter\DoctrineDbalAdapter;

class PagerfantaPaginatorFactory
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
     * @param  array               $criteria
     * @param  Table               $table
     * @return PagerfantaPaginator
     */
    public function createPaginator(array $criteria, Table $table)
    {
        $adapter = $this->getAdapter($table->getQueryFindAll($criteria), $table);

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
    protected function getAdapter(QueryBuilder $queryBuilder, Table $table)
    {
        if (!$this->adapter) {
            $this->adapter = new DoctrineDbalAdapter(
                $queryBuilder,
                function($queryBuilder) use ($table) {
                    $table->modifyQueryCount($queryBuilder);
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

        $this->paginator->setCurrentPage($page);
    }
}
