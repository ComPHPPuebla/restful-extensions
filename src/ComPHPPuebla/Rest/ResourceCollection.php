<?php
namespace ComPHPPuebla\Rest;

use \ComPHPPuebla\Doctrine\TableGateway\Table;
use \ComPHPPuebla\Paginator\PaginatorFactory;

class ResourceCollection
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var PaginatorFactory
     */
    protected $paginatorFactory;

    /**
     * @param Table            $table
     * @param PaginatorFactory $paginatorFactory
     */
    public function __construct(Table $table, PaginatorFactory $paginatorFactory = null)
    {
        $this->table = $table;
        $this->paginatorFactory = $paginatorFactory;
    }

    /**
     * @param  array                             $criteria
     * @return \ComPHPPuebla\Paginator\Paginator
     */
    public function retrieveAll(array $criteria = [])
    {
        return $this->paginatorFactory->createPaginator($criteria, $this->table);
    }

    /**
     * @param  int   $id
     * @return array
     */
    public function retrieveOne($id)
    {
        return $this->table->find($id);
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $this->table->delete($id);
    }
}
