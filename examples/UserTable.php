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
    {
        $qb = $this->createQueryBuilder();
        $qb->select('*')->from('users', 'u')->where('user_id = :userId');
        $qb->setParameter('userId', $id);

        return $this->fetchAssoc($qb->getSQL(), $qb->getParameters());
    }

    public function update(array $values, $id)
    {
        $this->doUpdate($values, ['user_id' => $id]);

        return $this->find($id);
    }

    public function delete($id)
    {
        $this->doDelete(['user_id' => $id]);
    }
}
