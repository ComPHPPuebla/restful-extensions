<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventListener;

use \ComPHPPuebla\Doctrine\TableGateway\Specification\QueryBuilderSpecification;
use \Zend\EventManager\EventInterface;

class QuerySpecificationListener
{
    /**
     * @var QueryBuilderSpecification
     */
    protected $specification;

    /**
     * @param QueryBuilderSpecification $specification
     * @param array                     $criteria
     */
    public function __construct(QueryBuilderSpecification $specification)
    {
        $this->specification = $specification;
    }

    /**
     * @param EventInterface $event
     */
    public function __invoke(EventInterface $event)
    {
        $this->specification->setCriteria($event->getParam('criteria'));
        $this->specification->match($event->getParam('qb'));
    }
}
