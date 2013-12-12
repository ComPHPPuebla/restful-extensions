<?php
namespace ComPHPPuebla\Doctrine\TableGateway;

use \Doctrine\DBAL\Query\QueryBuilder;

class UserTable extends Table
{
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
     * @param array $criteria
     * @return QueryBuilder
     */
    protected function getQueryFindAll(array $criteria)
    {
    }

    /**
     * @param array $criteria
     * @return QueryBuilder
     */
    protected function getQueryCount(array $criteria)
    {
    }
}
