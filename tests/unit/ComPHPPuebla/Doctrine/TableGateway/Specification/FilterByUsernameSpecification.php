<?php
namespace ComPHPPuebla\Doctrine\TableGateway\Specification;

use \Doctrine\DBAL\Query\QueryBuilder;

class FilterByUsernameSpecification extends QueryBuilderSpecification
{
	public function match(QueryBuilder $qb)
	{
	    if ($this->has('username')) {
            $qb->andWhere('u.username = :username');
            $qb->setParameter('username', $this->get('username'));
	    }
	}
}
