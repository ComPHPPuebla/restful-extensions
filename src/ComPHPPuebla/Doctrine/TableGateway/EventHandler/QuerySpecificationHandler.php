<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventHandler;

use \Zend\EventManager\Event;
use \ComPHPPuebla\Doctrine\Specification\QueryBuilderSpecification;

class QuerySpecificationHandler
{
    /**
     * @var QueryBuilderSpecification
     */
    protected $specification;

    /**
     * @param QueryBuilderSpecification $specification
     * @param array $criteria
     */
    public function __construct(QueryBuilderSpecification $specification)
    {
        $this->specification = $specification;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $criteria
     */
    public function __invoke(Event $event)
    {
        $this->specification->setCriteria($event->getParam('criteria'));
        $this->specification->match($event->getParam('qb'));
    }
}