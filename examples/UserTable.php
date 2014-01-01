<?php
use ComPHPPuebla\Doctrine\TableGateway\Table;
use Doctrine\DBAL\Query\QueryBuilder;

class UserTable extends Table
{
    public function getQueryFindAll(array $criteria)
    {
        $qb = $this->createQueryBuilder();
        $qb->select('*')->from('users', 'u');

        return $qb;
    }

    public function modifyQueryCount(QueryBuilder $qb)
    {
        $qb->select('COUNT(*)');
    }

    public function find($id)
    {}

    public function update(array $values, $id)
    {}

    public function delete($id)
    {}
}
