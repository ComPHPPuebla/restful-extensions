<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventHandler;

use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \Zend\EventManager\Event;

class PaginationHandler
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
    public function __invoke(Event $event)
    {
        $criteria = $event->getParam('criteria');
        $qb = $event->getParam('qb');
        $table = $event->getTarget();

        if (!isset($criteria['page'])) {
            $criteria['page'] = 1;
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
