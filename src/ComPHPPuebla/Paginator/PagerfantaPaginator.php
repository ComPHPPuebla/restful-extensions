<?php
namespace ComPHPPuebla\Paginator;

use \Pagerfanta\Pagerfanta;
use \Pagerfanta\Adapter\AdapterInterface;

class PagerfantaPaginator implements Paginator
{
    /**
     * @var Pagerfanta
     */
    protected $pagerfanta;

    /**
     * @var int
     */
    protected $maxPerPage;

    /**
     * @param int $defaultMaxPerPage
     */
    public function __construct($defaultMaxPerPage)
    {
        $this->maxPerPage = $defaultMaxPerPage;
    }

    /**
     * @param int $maxPerPage
     */
    public function setMaxPerPage($maxPerPage)
    {
        $this->maxPerPage = $maxPerPage;
        if ($this->pagerfanta) {
            $this->pagerfanta->setMaxPerPage($maxPerPage);
        }
    }

    /**
     * @return array
     */
    public function getCurrentPageResults()
    {
        return $this->pagerfanta->getCurrentPageResults();
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->pagerfanta->setCurrentPage($currentPage);
    }

    /**
     * @return int
     * @see \ComPHPPuebla\Paginator\Paginator::getCurrentPage()
     */
    public function getCurrentPage()
    {
        return $this->pagerfanta->getCurrentPage();
    }

    /**
     * @return boolean
     */
    public function haveToPaginate()
    {
        return $this->pagerfanta->haveToPaginate();
    }

    /**
     * @return boolean
     */
    public function hasNextPage()
    {
        return $this->pagerfanta->hasNextPage();
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        return $this->pagerfanta->getNextPage();
    }

    /**
     * @return boolean
     */
    public function hasPreviousPage()
    {
        return $this->pagerfanta->hasPreviousPage();
    }

    /**
     * @return int
     */
    public function getPreviousPage()
    {
        return $this->pagerfanta->getPreviousPage();
    }

    /**
     * @return int
     */
    public function getNbPages()
    {
        return $this->pagerfanta->getNbPages();
    }

    /**
     * @param QueryBuilder $qb
     * @param callable     $countModifier
     */
    public function init(AdapterInterface $adapter)
    {
        $this->pagerfanta = new Pagerfanta($adapter);
        $this->pagerfanta->setMaxPerPage($this->maxPerPage);
    }
}
