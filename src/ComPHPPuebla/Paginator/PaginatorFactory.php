<?php
namespace ComPHPPuebla\Paginator;

use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \Doctrine\DBAL\Query\QueryBuilder;
use \Pagerfanta\Adapter\FixedAdapter;
use \ComPHPPuebla\Doctrine\TableGateway\Table;
use Pagerfanta\Adapter\AdapterInterface;

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
     * @param QueryBuilder $qb
     * @param array $criteria
     * @return PagerfantaPaginator
     */
    public function createPaginator(array $criteria, Table $table)
    {
        $nbResults = $table->count($criteria);
        $results = $table->findAll($criteria);

        $this->paginator->init($this->getAdapter($nbResults, $results));
        $this->setupPaginator($criteria);

        return $this->paginator;
    }

    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    protected function getAdapter($nbResults, $results)
    {
        if (!$this->adapter) {
            $this->adapter = new FixedAdapter($nbResults, $results);
        }

        return $this->adapter;
    }

    /**
     * @param array $criteria
     */
    protected function setupPaginator(array $criteria)
    {
        $page = isset($criteria['page']) ? $criteria['page'] : 1;
        $this->paginator->setCurrentPage($page);

        if (isset($criteria['page_size'])) {

            $this->paginator->setMaxPerPage($criteria['page_size']);
        }
    }
}
