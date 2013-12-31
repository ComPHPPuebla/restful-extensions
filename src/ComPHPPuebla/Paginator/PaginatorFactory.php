<?php
namespace ComPHPPuebla\Paginator;

use \ComPHPPuebla\Doctrine\TableGateway\Table;

interface PaginatorFactory
{
    /**
     * @param  array     $criteria
     * @param  Table     $table
     * @return Paginator
     */
    public function createPaginator(array $criteria, Table $table);
}
