<?php
namespace ComPHPPuebla\Paginator;

use \Pagerfanta\Adapter\FixedAdapter;
use \Pagerfanta\Pagerfanta;

class PagerFantaPaginator implements Paginator
{
    /**
     * @var Pagerfanta
     */
    protected $pagerFanta;

    /**
     * @var int
     */
    protected $maxPerPage;

    /**
     * @param int $maxPerPage
     */
    public function setMaxPerPage($maxPerPage)
    {
        $this->maxPerPage = $maxPerPage;
    }

    /**
     * @return array
     */
    public function getCurrentPageResults()
    {
        return $this->pagerFanta->getCurrentPageResults();
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->pagerFanta->setCurrentPage($currentPage);
    }

    /**
     * @return boolean
     */
    public function haveToPaginate()
    {
        return $this->pagerFanta->haveToPaginate();
    }

    /**
     * @return boolean
     */
    public function hasNextPage()
    {
        return $this->pagerFanta->hasNextPage();
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        return $this->pagerFanta->getNextPage();
    }

    /**
     * @return boolean
     */
    public function hasPreviousPage()
    {
        return $this->pagerFanta->hasPreviousPage();
    }

    /**
     * @return int
     */
    public function getPreviousPage()
    {
        return $this->pagerFanta->getPreviousPage();
    }

    /**
     * @return int
     */
    public function getNbPages()
    {
        return $this->pagerFanta->getNbPages();
    }

    /**
     * @param array $results
     * @param int $count
     */
    public function setResults(array $results, $count)
    {
        $this->pagerFanta = new Pagerfanta(new FixedAdapter($count, $results));
        $this->pagerFanta->setMaxPerPage($this->maxPerPage);
    }
}
