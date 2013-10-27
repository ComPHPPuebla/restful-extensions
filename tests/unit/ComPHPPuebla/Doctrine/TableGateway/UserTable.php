<?php
namespace ComPHPPuebla\Doctrine\TableGateway;

use \Doctrine\DBAL\Query\QueryBuilder;

class UserTable extends Table
{
    /**
     * @param array $criteria
     * @return ComPHPPuebla\Paginator\Paginator
     */
    public function findAll(array $criteria) {}

    /**
     * @param array $values
     * @return array
     */
    public function insert(array $values) {}

	/**
	 * @param int
     * @return array
     */
    public function find($id)
    {
        return ['username' => 'luis', 'password' => 'changeme'];
    }

    /**
     * @param array $values
     * @param int $id
     * @return array
    */
     public function update(array $values, $id) {}

    /**
     * @param int $id
     * @return void
    */
     public function delete($id) {}

    /**
     * return int
    */
     public function count(QueryBuilder $qb) {}
}
