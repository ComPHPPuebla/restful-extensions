<?php
namespace ComPHPPuebla\Doctrine\TableGateway;

use \Doctrine\DBAL\Query\QueryBuilder;
use \Doctrine\DBAL\Connection;

abstract class Table
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @param string     $tableName
     * @param Connection $connection
     */
    public function __construct($tableName, Connection $connection)
    {
        $this->tableName = $tableName;
        $this->connection = $connection;
    }

    /**
     * @param string $sql
     * @param array  $params
     */
    protected function fetchAll($sql, array $params = [])
    {
        return $this->connection->fetchAll($sql, $params);
    }

    /**
     * @return Doctrine\DBAL\Query\QueryBuilder
     */
    protected function createQueryBuilder()
    {
        return $this->connection->createQueryBuilder();
    }

    /**
     * @param  string $sql
     * @param  array  $params
     * @return array
     */
    protected function fetchAssoc($sql, array $params = [])
    {
        return $this->connection->fetchAssoc($sql, $params);
    }

    /**
     * @param  string $sql
     * @param  array  $params
     * @return array
     */
    protected function fetchColumn($sql, array $params = [])
    {
        return $this->connection->fetchColumn($sql, $params);
    }

    /**
     * @param array $values
     * @param array $identifier
     */
    protected function doUpdate(array $values, array $identifier)
    {
        $this->connection->update($this->tableName, $values, $identifier);
    }

    /**
     * @param array $identifier
     */
    protected function doDelete(array $identifier)
    {
        $this->connection->delete($this->tableName, $identifier);
    }

    /**
     * @param  array $values
     * @return int   The value of the last inserted ID
     */
    public function insert(array $values)
    {
        $this->connection->insert($this->tableName, $values);

        return $this->connection->lastInsertId();
    }

    /**
     * @param  array        $criteria
     * @return QueryBuilder
     */
    abstract public function getQueryFindAll(array $criteria);

    /**
     * @param QueryBuilder $qb
     */
    abstract public function modifyQueryCount(QueryBuilder $qb);

    /**
     * @param int
     * @return array
     */
    abstract public function find($id);

    /**
     * @param  array $values
     * @param  int   $id
     * @return array
     */
    abstract public function update(array $values, $id);

    /**
     * @param  int  $id
     * @return void
     */
    abstract public function delete($id);
}
