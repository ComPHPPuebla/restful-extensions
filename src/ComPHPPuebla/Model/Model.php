<?php
namespace ComPHPPuebla\Model;

use \ComPHPPuebla\Validator\Validator;
use \ComPHPPuebla\Doctrine\TableGateway\Table;

class Model implements Validator
{
    /**
     * @var array
     */
    protected $optionsList;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var repository
     */
    protected $repository;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Table $table
     */
    public function __construct(Table $table, Validator $validator)
    {
        $this->table = $table;
        $this->validator = $validator;
        $this->optionsList = ['GET', 'POST', 'OPTIONS'];
        $this->options = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];
    }

    /**
     * @param array $criteria
     * @param ResourceCollection $resources
     */
    public function retrieveAll(array $criteria)
    {
        $collection = $this->table->findAll($criteria);
        $collection['count'] = $this->table->count();

        return $collection;
    }

    /**
     * @param int $id
     * @param Resource $resource
     * @return array
     */
    public function retrieveOne($id)
    {
        return $this->table->find($id);
    }

    /**
     * @param array $newResource
     * @param Resource $resource
     * @return array
     */
    public function create(array $newResource)
    {
        $id = $this->table->insert($newResource);

        return $this->table->find($id);
    }

    /**
     * @param array $resourceValues
     * @param int $id
     * @return array
     */
    public function update(array $resourceValues, $id)
    {
        return $this->table->update($resourceValues, $id);
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $this->table->delete($id);
    }

    /**
     * @param array $values
     * @return boolean
     */
    public function isValid(array $values)
    {
        return $this->validator->isValid($values);
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }
}
