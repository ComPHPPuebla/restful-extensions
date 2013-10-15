<?php
namespace ComPHPPuebla\Paginator;

interface Paginator
{
    /**
     * @param int $maxPerPage
     */
    public function setMaxPerPage($maxPerPage);

    /**
     * @return array
     */
    public function getCurrentPageResults();

    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage);

    /**
     * @return boolean
     */
    public function haveToPaginate();

    /**
     * @return boolean
     */
    public function hasNextPage();

    /**
     * @return boolean
     */
    public function hasPreviousPage();

    /**
     * @return int
     */
    public function getNbPages();

    /**
     * @param array $results
     * @param int $count
     */
    public function setResults(array $results, $count);
}
